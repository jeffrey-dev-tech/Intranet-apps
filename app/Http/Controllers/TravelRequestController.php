<?php

namespace App\Http\Controllers;
use App\Models\ApprovalRequest;
use App\Models\TravelRequest;
use phpseclib3\Net\SFTP;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\RequestMailService;
class TravelRequestController extends Controller
{
    /**
     * Show the travel request form.
     */
public function TravelRequestForm()
{
        return view('forms.Travel_Request_Form');
}

    /**
     * Insert a new travel request.
     */
public function TravelRequestInsert(Request $request)
{
    DB::beginTransaction(); // Start transaction

    try {
        $type = $request->input('request_type');

        // --- Validation ---
        $rules = [
            'request_type' => 'required|string|in:Air Travel,Land Transport',
            'department' => 'required|string',
            'destination' => 'required|string',
            'purpose' => 'required|string',
            'additional_request' => 'nullable|string',
            'travel_date_from' => 'required|date_format:Y-m-d\TH:i',
            'travel_date_to' => 'required|date_format:Y-m-d\TH:i|after_or_equal:travel_date_from',
        ];

        if ($type === 'Air Travel') {
            $rules['preferred_time_departure'] = 'required|date_format:H:i';
            $rules['preferred_time_return'] = 'required|date_format:H:i';
            $rules['accommodation'] = 'required|boolean';
            $rules['attachments'] = 'sometimes|array';
            $rules['attachments.*'] = 'file|max:10240|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,ppt,pptx';
        }

        $validated = $request->validate($rules);

        // --- Generate TRF_NO ---
        $trfNo = TravelRequest::generateTrfNo($type);

        // --- Parse travel dates ---
        $travelFrom = Carbon::createFromFormat('Y-m-d\TH:i', $validated['travel_date_from']);
        $travelTo = Carbon::createFromFormat('Y-m-d\TH:i', $validated['travel_date_to']);

        $attachments = [];

        // --- Handle SFTP upload ---
        if ($request->hasFile('attachments')) {
            $sftp_host = env('SFTP_HOST');
            $sftp_port = env('SFTP_PORT');
            $sftp_user = env('SFTP_USER');
            $sftp_pass = env('SFTP_PASS');
            $base_remote_path = env('SFTP_BASE_PATH');
            $travel_folder = 'Attachments_Travel_Request';

            $sftp = new SFTP($sftp_host, $sftp_port);
            if (!$sftp->login($sftp_user, $sftp_pass)) {
                throw new Exception('SFTP login failed.');
            }

            $remoteDir = "$base_remote_path/$travel_folder";
            if (!$sftp->is_dir($remoteDir)) {
                throw new Exception("Remote directory '$remoteDir' does not exist on SFTP.");
            }

            foreach ($request->file('attachments') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $uniqueFilename = "{$trfNo}_" . now()->format('Ymd_His') . "_" . Str::upper(Str::random(5)) . "_{$originalName}.{$extension}";
                $remoteFilePath = "$remoteDir/$uniqueFilename";

                if (!$sftp->put($remoteFilePath, file_get_contents($file->getRealPath()))) {
                    throw new Exception("Failed to upload file: $uniqueFilename");
                }

                $attachments[] = $uniqueFilename;

                Log::info("📤 Uploaded file to SFTP", [
                    'filename' => $uniqueFilename,
                    'remote_path' => $remoteFilePath,
                ]);
            }
        }

        // --- Save Travel Request ---
        $travelRequest = TravelRequest::create([
            'user_id' => Auth::id(),
            'department' => $validated['department'],
            'travel_date_from' => $travelFrom,
            'travel_date_to' => $travelTo,
            'destination' => $validated['destination'],
            'request_type' => $validated['request_type'],
            'trf_no' => $trfNo,
            'preferred_time_departure' => $validated['preferred_time_departure'] ?? null,
            'preferred_time_return' => $validated['preferred_time_return'] ?? null,
            'accommodation' => $validated['accommodation'] ?? false,
            'purpose' => $validated['purpose'] ?? null,
            'additional_request' => $validated['additional_request'] ?? null,
            'attachments' => $attachments, // JSON array of filenames
            'status' => 'Pending',
        ]);

        // --- Determine email recipient ---

if ($type === 'Land Transport') {
    // Land Transport: fixed recipient
    $to = ['jeffrey.salagubang.js@sanden-rs.com'];
} else { // Air Travel

    $user = Auth::user();
$head_id = $user->head->id ?? null;
$user_role = $user->role ?? null;
$trf_no = $trfNo;
$system_id = 'INTRANET001';
$approversToCreateFinal = [];
$now = now();

switch ($head_id) {

    case 13: // COO
        $workflowApprovers = [
            ['user_id' => 9, 'approval_level' => 1],
            ['user_id' => 10, 'approval_level' => 2],
            ['user_id' => 11, 'approval_level' => 3],
        ];

        // Role-specific changes
        if ($user_role == 2) {
            // Promote user 10 to approval_level 1
            $workflowApprovers = [
                ['user_id' => 10, 'approval_level' => 1],
                ['user_id' => 9, 'approval_level' => 2],
                ['user_id' => 11, 'approval_level' => 3],
            ];
        } elseif ($user_role == 3) {
            // Add extra approver for role 3
            $workflowApprovers[] = ['user_id' => 99, 'approval_level' => 4, 'remarks' => 'Extra approver for role 3'];
        }
        break;

    case 18: // Another head
        $workflowApprovers = [
            ['user_id' => 34, 'approval_level' => 1],
            ['user_id' => 18, 'approval_level' => 2],
            ['user_id' => 13, 'approval_level' => 3],
            ['user_id' => 63, 'approval_level' => 4],
        ];

        // Role-specific adjustments
        if ($user_role == 6) {
            // Swap approval levels for role 6
            $workflowApprovers = [
                ['user_id' => 34, 'approval_level' => 1],
                ['user_id' => 18, 'approval_level' => 3], // moved down
                ['user_id' => 13, 'approval_level' => 2], // moved up
                ['user_id' => 63, 'approval_level' => 4],
            ];
        }
        break;

    default:
        // Default approvers for unknown heads
        $workflowApprovers = [
            ['user_id' => 1, 'approval_level' => 0, 'remarks' => 'Default approver 1'],
            ['user_id' => 2, 'approval_level' => 0, 'remarks' => 'Default approver 2'],
        ];

        Log::info("Default workflow applied for unknown head_id {$head_id}.");
        break;
}

// Prepare final array for bulk insert
foreach ($workflowApprovers as $approver) {
    $approversToCreateFinal[] = array_merge($approver, [
        'trf_no' => $trf_no,
        'system_id' => $system_id,
        'status' => ApprovalRequest::STATUS_PENDING,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
}

// Bulk insert if not empty
if (!empty($approversToCreateFinal)) {
    ApprovalRequest::insert($approversToCreateFinal);
    Log::info("ApprovalRequest inserted successfully for TRF {$trf_no}, head_id {$head_id}.");
} else {
    Log::warning("ApprovalRequest not inserted: no approvers found for head_id {$head_id}, role {$user_role}.");
}

    // Set email recipient to department head if exists, otherwise fallback
    $to = [$user->head->email ?? 'jeffrey.salagubang.js@sanden-rs.com'];
}

        $subject = "Travel Request {$trfNo} ({$type})";

        // --- Send notification email ---
        $this->sendNotificationEmail(
            $to,
            $subject,
            $travelRequest,
            $trfNo,
            $cc = []
        );

        DB::commit(); // Commit transaction

        return response()->json([
            'status' => 'success',
            'message' => 'Travel request submitted successfully!',
            'data' => $travelRequest
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('TravelRequestInsert failed', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to submit travel request',
            'error' => $e->getMessage()
        ], 500);
    }
}


private function sendNotificationEmail(
    $to,
    $subject,
    $itRequest,
    $reference_no,
    $cc = [],
    $view = 'emails.TravelRequestApproval'
) {
    try {
        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $body = view($view, [
            'reference_no' => $reference_no,
            'type_request' => $itRequest->type_request,
            'request_data' => $itRequest,
            'logoDataUri'  => $logoDataUri,
        ])->render();

        (new RequestMailService())->registration_mail(
            $to,       // TO
            $cc,       // CC
            [],        // BCC
            $subject,
            $body,
            []         // Attachments
        );

    } catch (\Exception $mailEx) {
        Log::error("[$reference_no] Email send failed: " . $mailEx->getMessage());
    }
}

public function approvalForm($trf_no)
{
    return view('approval_form.travel_approval', compact('trf_no'));
}


}
