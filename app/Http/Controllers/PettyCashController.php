<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PCVUser;
use App\Models\Department;
use App\Models\PCVLog; // import the PCVLog model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PettyCashController extends Controller
{
public function pettycashform()
{
    $user = Auth::user();
    // Superusers: IDs 53 and 18 can access all departments
    if (in_array($user->id, [53, 18,100])) {
        $departments = Department::all();
        $allowedDepartmentIds = $departments->pluck('id')->toArray();
    } else {
        // Check PCVUser for allowed departments
        $pcvUser = PCVUser::where('user_id', $user->id)->first();

        // Deny access if not found
        if (!$pcvUser) {
            abort(403, 'You do not have access to the Petty Cash Voucher form.');
        }

        // Make sure allowed_department is an array
        $allowedDepartmentIds = is_array($pcvUser->allowed_department)
            ? $pcvUser->allowed_department
            : json_decode($pcvUser->allowed_department, true) ?? [];

        $departments = Department::whereIn('id', $allowedDepartmentIds)->get();
    }

    // Fetch latest N logs per department
    $logs = PCVLog::with('user')
    ->whereIn('department', $allowedDepartmentIds)
    ->orderBy('created_at', 'desc')
    ->get()
    ->groupBy('department');

    return view('pages.petty_cash', [
        'departments' => $departments,
        'pcvLogs' => $logs,
    ]);
}

public function download_voucher(Request $request)
{
    $user = Auth::user();
    $departmentId = $request->input('department');

    $department = Department::find($departmentId);
    if (!$department) {
        return response()->json(['error' => 'Invalid department selected'], 400);
    }

    $regionCodeMap = [
        'RO-NL' => 'NL',
        'RO-MINDANAO' => 'DVO',
        'RO-VISAYAS' => 'VIS',
        'FINANCE' => 'FIN',
        'HUMAN RESOURCE' => 'HRD',
        'COLD CHAIN' => 'CC',
        'AFTER SALES' => 'AFT',
        'SCM' => 'LOG',
        'SOUTH LUZON' => 'SL',
        'PROCUREMENT & IMPORTATION' => 'PROC',
        'MIS' => 'MIS',
    ];

    $prefix = $regionCodeMap[$department->name] ?? 'GEN';
    $year = now()->format('y');

    $maxRetries = 5;
    $attempt = 0;

    while ($attempt < $maxRetries) {
        try {
            $seriesNumbers = DB::transaction(function () use ($departmentId, $prefix, $year, $user) {
                // Lock the last row
                $lastLog = PCVLog::where('department', $departmentId)
                    ->lockForUpdate()
                    ->latest('created_at')
                    ->first();

                $startNumber = 1;
                if ($lastLog && preg_match('/-(\d{4})$/', $lastLog->last_series_no, $matches)) {
                    $startNumber = intval($matches[1]) + 1;
                }

                // Generate 4 series numbers
                $seriesNumbers = [];
                for ($i = 0; $i < 4; $i++) {
                    $seriesNumbers[] = sprintf('%s%s-%04d', $prefix, $year, $startNumber + $i);
                }

                // Save only the last series
                $lastSeries = end($seriesNumbers);
                PCVLog::create([
                    'user_id' => $user->id,
                    'department' => $departmentId,
                    'last_series_no' => $lastSeries,
                ]);

                return $seriesNumbers;
            });

            // If transaction succeeds, break the retry loop
            break;

        } catch (\Illuminate\Database\QueryException $e) {
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->getCode() == 23000) {
                $attempt++;
                usleep(100000); // wait 100ms before retry
                continue;
            }
            throw $e;
        }
    }

    if ($attempt == $maxRetries) {
        return response()->json(['error' => 'Could not generate unique voucher series. Please try again.'], 500);
    }

    // mPDF setup
    if (!class_exists(\Mpdf\Mpdf::class)) {
        throw new \Exception('mPDF is not installed. Run: composer require mpdf/mpdf');
    }

    $tempDir = storage_path('app/mpdf');
    if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);

    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => $tempDir,
        'format'  => 'A4',
        'margin_top' => 15,
        'margin_bottom' => 15,
        'margin_left' => 10,
        'margin_right' => 10,
    ]);

    $html = view('sanden_forms.pc_voucher', [
        'user' => $user,
        'department' => $department,
        'seriesNumbers' => $seriesNumbers,
        'date' => now(),
    ])->render();

    $mpdf->WriteHTML($html);
    $mpdf->SetProtection(
    ['print'],          // allowed actions (empty = no permissions)
    '',                 // user password (leave empty)
    'admin-secret-123'  // owner password (REQUIRED)
);
$mpdf->SetHTMLFooter('
<div style="text-align: center; font-size: 10px; color: #777;">
    This document is system generated
</div>
');
    // Draw horizontal cut line
    $pageWidth = $mpdf->w;
    $pageHeight = $mpdf->h;
    // $mpdf->SetLineWidth(0.5);
    // $mpdf->Line(10, $pageHeight / 2, $pageWidth - 10, $pageHeight / 2);

    // Direct download
    $fileName = $seriesNumbers[0] . '_to_' . end($seriesNumbers) . '.pdf';
    return response($mpdf->Output($fileName, 'D'));
}




}
