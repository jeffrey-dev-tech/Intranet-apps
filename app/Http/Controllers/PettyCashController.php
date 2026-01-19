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

$departments = Department::whereIn('id', $allowedDepartmentIds)
    ->get()
    ->map(function($dept) {
        // Capitalize first letter of each word
          $dept->name = strtoupper($dept->name);
        return $dept;
    });
   

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
 public function generateRandomString($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
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
        'ADMIN' => 'ADM',
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

    $seriesNumbers = [];
    $uniquePairs = []; // Define outside closure so it’s accessible for PDF

    while ($attempt < $maxRetries) {
        try {
            // DB transaction
            list($seriesNumbers, $uniquePairs) = DB::transaction(function () use ($departmentId, $prefix, $year, $user) {

                $lastLog = PCVLog::where('department', $departmentId)
                    ->lockForUpdate()
                    ->latest('created_at')
                    ->first();

                $startNumber = 1;
                if ($lastLog && preg_match('/-(\d{4})$/', $lastLog->last_series_no, $matches)) {
                    $startNumber = intval($matches[1]) + 1;
                }
$seriesNumbers = []; // generated series numbers
$uniquePairs = [];   // series-no => unique-code mapping

for ($i = 0; $i < 4; $i++) {
    $series = sprintf('%s%s-%04d', $prefix, $year, $startNumber + $i);
    $uniqueCode = $this->generateRandomString(12);
    $seriesNumbers[] = $series;
    $uniquePairs[$series] = $uniqueCode; // map each series to its own unique code
}

// Save last series with unique code
PCVLog::create([
    'user_id' => $user->id,
    'department' => $departmentId,
    'last_series_no' => end($seriesNumbers),
    'unique_code' => json_encode($uniquePairs), // store all pairs if needed
]);


                return [$seriesNumbers, $uniquePairs];
            });

            break; // success, exit retry loop
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // duplicate entry
                $attempt++;
                usleep(100000); // wait 0.1s
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

    // Pass series numbers and unique codes to PDF view
    $html = view('sanden_forms.pc_voucher', [
        'user' => $user,
        'department' => $department,
        'seriesNumbers' => $seriesNumbers,
        'date' => now(),
        'uniquePairs' => $uniquePairs, // list of [series-unique code]
    ])->render();

    $mpdf->WriteHTML($html);

    $mpdf->SetProtection(['print'], '', 'admin-secret-123');

    $mpdf->SetHTMLFooter('
        <div style="text-align: center; font-size: 10px; color: #777;">
            This document is system generated
        </div>
    ');

    $pdfContent = $mpdf->Output('', 'S');
    $fileName = $seriesNumbers[0] . '_to_' . end($seriesNumbers) . '.pdf';

    return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
}




}
