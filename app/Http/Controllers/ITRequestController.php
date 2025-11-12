<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ItRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ITRequestMailController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ItRequestApproval;
use App\Services\RequestMailService;
use phpseclib3\Net\SFTP;
use Exception;
class ITRequestController extends Controller
{

public function isInternetAvailable()
{
    try {
     
        $connected = @fsockopen("8.8.8.8", 53, $errno, $errstr, 2);
        if ($connected) {
            fclose($connected);
            return true;
        }
    } catch (\Exception $e) {
        return false;
    }
    return false;
}
public function it_request_insert(Request $request)
{
    try {
        $type = $request->input('type_request');
        // --- Base validation ---
        $rules = [
            'type_request' => 'required|string',
            'requestor_name' => 'required|string',
            'department' => 'required|string',
            'requestor_email' => 'required|email',
            'description_of_request' => 'required|string|max:1000',
            'attachments.*' => 'file|max:10240|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,ppt,pptx',
        ];

        // --- Conditional validation based on type_request ---
        switch ($type) {
            case 'Repair_Request':
                $rules['issue'] = 'required|string';
               
                break;
            case 'Borrow_Item':
                $rules['item_name'] = 'required|string';
                $rules['date_needed'] = 'required|date';
                $rules['plan_return_date'] = 'required|date';
                break;
            case 'Purchase_Item':
                $rules['purchase_item_name'] = 'required|string';
                    
                break;
            case 'Project_Request':
                $rules['project_details'] = 'required|string';
                    
                break;
            case 'Intranet_Request':
                $rules['intranet_request_type'] = 'required|string';
                break;
          
        }

        $validated = $request->validate($rules);
        Log::info('Validated IT Request data:', $validated);

        $user = Auth::user();
        $userRole =  $user->role;
        if (in_array($userRole, ['2', '3', '4', '5', '6'])) 
        {
        //supervisor,Manager,Management,Admin,developer
        //Approver is IT
        $initialApproverRole = 'MIS';
        $initialApproverEmail = 'mis.scp@sanden-rs.com';
        $initialApproverName = 'MIS';
        }else{
        // user role is 1 = staff
        $initialApproverRole = $user->head->role ?? null;
        $initialApproverEmail = $user->head->email ?? null;
        $initialApproverName = $user->head->name ?? null;
        }

        // ✅ SFTP config
   if ($request->hasFile('attachments')) {
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');
    $it_request_folder = 'Attachments_IT_Request';

    // Initialize SFTP
    $sftp = new SFTP($sftp_host, $sftp_port);
    if (!$sftp->login($sftp_user, $sftp_pass)) {
        throw new Exception('SFTP login failed.');
    }

    $remoteDir = "$base_remote_path/$it_request_folder";
    if (!$sftp->is_dir($remoteDir)) {
        throw new Exception("Remote directory '$remoteDir' does not exist on SFTP.");
    }

    $uploadedFiles = [];

    foreach ($request->file('attachments') as $file) {
        // ✅ Generate unique filename: original_name + timestamp + random string
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $uniqueFilename = $originalName . '_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $extension;

        $remoteFilePath = "$remoteDir/$uniqueFilename";

        // Upload file content
        if (!$sftp->put($remoteFilePath, file_get_contents($file->getRealPath()))) {
            throw new Exception("Failed to upload file: $uniqueFilename");
        }

        $uploadedFiles[] = $uniqueFilename;
        Log::info("📤 Uploaded file to SFTP", [
            'filename' => $uniqueFilename,
            'remote_path' => $remoteFilePath,
        ]);
    }

    // Store the filenames in DB field (comma-separated or JSON)
    $validated['attachment_name'] = json_encode($uploadedFiles);
}


        // --- Start transaction ---
        DB::beginTransaction();
       
        // Add current approver and status
        $validated['current_approver_role'] = $initialApproverRole;
        $validated['status'] = 'Pending';

// Create IT request
$item = ItRequest::create($validated);

// Create initial approval record
    ItRequestApproval::create([
    'reference_no'          => $item->reference_no,
    'approved_by'           => $initialApproverName,
    'role'                  => $initialApproverRole,
    'status'                => 'Pending',
    'remarks'               => '',
    'approver_email'        => $initialApproverEmail,
    'current_approver_role' => $initialApproverRole,
]);

        DB::commit();

        Log::info('IT Request created successfully', ['id' => $item->id, 'reference_no' => $item->reference_no]);

        // --- Handle file uploads ---
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('temp_attachments');
                $attachments[] = [
                    'path' => storage_path('app/' . $path),
                    'name' => $file->getClientOriginalName(),
                ];
            }
        }

        // --- Prepare email ---
        $to = [$initialApproverEmail];
        $cc = [];
        $bcc = [];
        $subject = "IT Request: {$type}-{$item->reference_no}";

        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));

        $body = view('emails.it_request_mail', [
            'logoDataUri' => $logoDataUri,
            'type_request' => $type,
            'request_data' => $validated,
            'reference_no' => $item->reference_no
        ])->render();

        try {
            if ($this->isInternetAvailable()) {
                $mailController = new ITRequestMailController();
                $emailSent = $mailController->insert_request_mail(
                    $to,
                    $cc,
                    $bcc,
                    $subject,
                    $body,
                    $attachments
                );

                if (!$emailSent) {
                    Log::error('Email sending failed for IT request ID: ' . $item->id);
                }
            } else {
                Log::warning('No internet, email not sent for IT request ID: ' . $item->id);
            }
        } catch (\Exception $emailEx) {
            Log::error('Exception during email sending: ', ['exception' => $emailEx]);
        } finally {
            // Cleanup uploaded temp files
            foreach ($attachments as $file) {
                if (file_exists($file['path'])) {
                    unlink($file['path']);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your request created successfully!',
            'data' => $item
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Validation failed:', $e->errors());
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('IT Request creation failed:', ['exception' => $e]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create IT request',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function fetch_all_data()
{
      // Retrieve all records from ItemRequest
if (auth()->user()->department == "MIS") {
    // User is in IT, return all data
    $data = ItRequest::all();
}elseif (str_contains(auth()->user()->position, 'Manager')) {
     $user = Auth::user();
$approvedRefs = ItRequestApproval::where('approver_email', $user->email)
        ->pluck('reference_no'); // get all approved reference numbers

$data = ItRequest::where('requestor_email', $user->email)
                 ->orWhereIn('reference_no', $approvedRefs) // match reference_no instead of id
                 ->get();
} else {
    // Otherwise, only return their own requests
    $data = ItRequest::where('requestor_email', auth()->user()->email)->get();
}

    return response()->json($data);
}

public function view_IT_REQUEST_data()
{
      return view('form_data.it_request');   
}
public function referenceData($reference_no)
{
    // Eager load approvals
    $request = ITRequest::with('approvals')
        ->where('reference_no', $reference_no)
        ->firstOrFail();

    // Common fields
    $data = [
        'reference_no'           => $request->reference_no,
        'status'                 => $request->status,
        'requestor_name'         => $request->requestor_name,
        'department'             => $request->department,
        'description_of_request' => $request->description_of_request,
        'Date_of_Request'        => $request->created_at->toDateString(),
    ];

    // Add fields based on request type
    switch ($request->type_request) {
        case 'Repair_Request':
            $data['issue'] = $request->issue;
            $data['priority'] = $request->priority;
            break;

        case 'Borrow_Item':
            $data['item_name'] = $request->item_name;
            $data['date_needed'] = optional($request->date_needed)->toDateString();
            $data['plan_return_date'] = optional($request->plan_return_date)->toDateString();
            break;

        case 'Purchase_Item':
            $data['purchase_item_name'] = $request->purchase_item_name;
            break;

        case 'Project_Request':
            $data['project_details'] = $request->project_details;
            break;

        case 'Intranet_Request':
            $data['intranet_request_type'] = $request->intranet_request_type;
            $data['manager_email'] = $request->manager_email;
            break;

    }

    // Add approvals (if any)
    $data['approvals'] = $request->approvals->map(function ($approval) {
        return [
            'approved_by'       => $approval->approved_by,
            'status'            => $approval->status,
            'remarks'           => $approval->remarks,
            'date_accomplished' => $approval->created_at->toDateTimeString(),
        ];
    });

    return response()->json($data);
}

public function downloadAttachment($filename)
{
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');
    $it_request_folder = 'Attachments_IT_Request';

    $remoteFilePath = "$base_remote_path/$it_request_folder/$filename";

    $sftp = new SFTP($sftp_host, $sftp_port);

    // Try to connect
    if (!$sftp->login($sftp_user, $sftp_pass)) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>
            ⚠️ SFTP login failed. Please contact IT support.
        </h3>");
    }

    // ✅ Check if file exists on SFTP
    if (!$sftp->file_exists($remoteFilePath)) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>
            ⚠️ The file <strong>$filename</strong> has already been deleted or is no longer available.
        </h3>", 404);
    }

    // Get file content
    $fileContent = $sftp->get($remoteFilePath);

    if ($fileContent === false) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>
            ⚠️ Unable to read file <strong>$filename</strong>. It may have been removed or corrupted.
        </h3>", 404);
    }

    // ✅ Detect MIME type (so browser knows how to open it)
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'txt'  => 'text/plain',
        'html' => 'text/html',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];
    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    // ✅ Inline display (opens in new tab)
    return response($fileContent)
        ->header('Content-Type', $mimeType)
        ->header('Content-Disposition', 'inline; filename="' . basename($filename) . '"');
}

public function approvalForm($reference_no)
{
    $user = auth()->user();
    $userEmail = strtolower($user->email ?? '');
    $userDept  = strtoupper($user->department ?? '');

    // Fetch IT Request with all approvals
    $itRequest = ITRequest::with('approvals')
        ->where('reference_no', $reference_no)
        ->firstOrFail();

    // ✅ Check if request level is HIGH or VERY HIGH
    $levelRequest = strtolower($itRequest->level_request ?? '');
    $isHighLevel = in_array($levelRequest, ['high', 'very high']);

    // ✅ Check MIS
    $isMIS = str_contains($userDept, 'MIS');

    if ($isMIS) {
        $approval = ItRequestApproval::where('reference_no', $reference_no)
            ->where('approver_email', 'mis.scp@sanden-rs.com')
            ->first();
    } else {
        // User's individual approval record
        $approval = ItRequestApproval::where('reference_no', $reference_no)
            ->where('approver_email', $userEmail)
            ->first();
    }

    // ✅ Has MIS pending approval
    $hasPendingMIS = $itRequest->approvals->contains(function ($a) {
        return strtolower($a->approver_email) === 'mis.scp@sanden-rs.com'
            && strtolower($a->status) === 'pending';
    });

    // ✅ Requestor check
    $isRequestor = $userEmail === strtolower($itRequest->requestor_email);

    // 🔒 Restrict unauthorized
    if (!$approval && !$isMIS && !$isRequestor) {
        abort(403, 'You are not authorized to view this approval page.');
    }

    // ✅ Only MIS can edit or complete if not already completed
    $isCompleted = strtolower($itRequest->status ?? '') === 'completed';
    $mis_approval_status = $approval->status ?? null;

    // ✅ Who can approve
    $canApprove =
        // Non-MIS: if their own approval is pending
        ($approval && strtolower($approval->status) === 'pending' )
        // MIS: can act if request is not yet completed
        || ($isMIS && !$isCompleted);

    return view('approval_form.it_approval', compact(
        'itRequest',
        'approval',
        'canApprove',
        'isMIS',
        'mis_approval_status',
        'isHighLevel'
    ));
}


public function updateStatus($reference_no, Request $request)
{
    DB::beginTransaction();

    try {
        $user = Auth::user();
        Log::info("updateStatus: Authenticated user", ['user' => $user]);

        if (!$user) {
            DB::rollBack();
            Log::warning("updateStatus: Unauthenticated access attempt");
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
        }

        // Validate input
        $validated = $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
            'Level_Request' => 'nullable|string',
        ]);

        $status = strtolower(trim($validated['status']));
        $remarks = $validated['remarks'] ?? '';
        $levelRequest = strtolower($validated['Level_Request'] ?? '');
        $allowed = ['pending', 'approved', 'disapproved', 'completed'];

        Log::info("updateStatus: Input values", [
            'status' => $status,
            'remarks' => $remarks,
            'Level_Request' => $levelRequest
        ]);

        if (!in_array($status, $allowed)) {
            DB::rollBack();
            Log::warning("updateStatus: Invalid status value", ['status' => $status]);
            return response()->json(['status' => 'error', 'message' => 'Invalid status value.'], 422);
        }

        $itRequest = ItRequest::with('approvals')->where('reference_no', $reference_no)->first();
        Log::info("updateStatus: Loaded IT request", ['itRequest' => $itRequest]);

        if (!$itRequest) {
            DB::rollBack();
            Log::warning("updateStatus: IT request not found", ['reference_no' => $reference_no]);
            return response()->json(['status' => 'error', 'message' => 'IT request not found.'], 404);
        }

        /**
         * ✅ CASE 1: SENIOR MANAGER APPROVER
         */
        if ($user->id == 18) {
            Log::info("updateStatus: Senior Manager branch");
            $updatedSM = ItRequestApproval::where('reference_no', $reference_no)
                ->where('approver_email', $user->email)
                ->where('status', 'Pending')
                ->update([
                    'approved_by' => $user->name,
                    'status' => ucfirst($status),
                    'remarks' => $remarks,
                    'updated_at' => now(),
                ]);

            Log::info("updateStatus: Updated Senior Manager approval", ['updatedSM' => $updatedSM]);

            if (!$updatedSM) {
                DB::rollBack();
                Log::warning("updateStatus: No pending approval found for Senior Manager", ['user' => $user]);
                return response()->json(['status' => 'error', 'message' => 'No pending approval found for Senior Manager.'], 404);
            }

            ItRequest::where('reference_no', $reference_no)
                ->update(['status' => ucfirst($status), 'updated_at' => now()]);

            $this->sendNotificationEmail(
                ['jeffrey.salagubang.js@sanden-rs.com2'],
                "[IT Request {$reference_no}] - Senior Manager {$status}",
                $itRequest,
                $reference_no
            );

            DB::commit();
            Log::info("updateStatus: Senior Manager approval completed and committed", ['reference_no' => $reference_no]);
            return response()->json(['status' => 'success', 'message' => "Request {$status} by Senior Manager."]);
        }

        /**
         * ✅ CASE 2: NON-MIS APPROVER
         */
        if (strtolower($user->department) !== 'mis' && in_array($status, ['approved', 'disapproved'])) {
            Log::info("updateStatus: Non-MIS branch", ['user' => $user]);
            $updatedNonMIS = ItRequestApproval::where('reference_no', $reference_no)
                ->where('approver_email', $user->email)
                ->where('status', 'Pending')
                ->update([
                    'approved_by' => $user->name,
                    'status' => ucfirst($status),
                    'remarks' => $remarks,
                    'updated_at' => now(),
                ]);

            Log::info("updateStatus: Updated Non-MIS approval", ['updatedNonMIS' => $updatedNonMIS]);

            if (!$updatedNonMIS) {
                DB::rollBack();
                Log::warning("updateStatus: No pending approval found for Non-MIS user", ['user' => $user]);
                return response()->json(['status' => 'error', 'message' => 'No pending approval found for this user.'], 404);
            }

            if ($status === 'approved') {
                $existsMIS = ItRequestApproval::where('reference_no', $reference_no)
                    ->where('role', 'MIS')
                    ->exists();
                Log::info("updateStatus: MIS approval exists?", ['existsMIS' => $existsMIS]);

                if (!$existsMIS) {
                    ItRequestApproval::create([
                        'reference_no' => $reference_no,
                        'approved_by' => 'MIS',
                        'role' => 'MIS',
                        'current_approver_role' => 'MIS',
                        'approver_email' => 'mis.scp@sanden-rs.com',
                        'status' => 'Pending',
                        'remarks' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info("updateStatus: MIS approval created", ['reference_no' => $reference_no]);
                }

                $this->sendNotificationEmail(
                    ['jeffrey.salagubang.js@sanden-rs.com'],
                    "[IT Request Approval] {$reference_no} - MIS Review Needed",
                    $itRequest,
                    $reference_no
                );

                DB::commit();
                Log::info("updateStatus: Non-MIS approved and forwarded to MIS", ['reference_no' => $reference_no]);
                return response()->json(['status' => 'success', 'message' => 'Request approved and forwarded to MIS for review.']);
            } else {
                $this->finalizeRequestStatus($itRequest, $reference_no, 'Disapproved', $levelRequest);
                DB::commit();
                Log::info("updateStatus: Non-MIS disapproved and finalized", ['reference_no' => $reference_no]);
                return response()->json(['status' => 'success', 'message' => 'Request has been disapproved and finalized.']);
            }
        }

        /**
         * ✅ CASE 3: MIS APPROVER
         */
        Log::info("updateStatus: MIS branch");

        $updatedMIS = ItRequestApproval::where('reference_no', $reference_no)
            ->where('approver_email', 'mis.scp@sanden-rs.com')
            ->whereIn('status', ['Pending', 'Approved'])
            ->update([
                'approved_by' => $user->name,
                'status' => ucfirst($status),
                'remarks' => DB::raw("CONCAT(COALESCE(remarks, ''), '\n" . addslashes($remarks) . "')"),
                'updated_at' => now(),
            ]);

        Log::info("updateStatus: Updated MIS approval", ['updatedMIS' => $updatedMIS]);

        if (!$updatedMIS) {
            DB::rollBack();
            Log::warning("updateStatus: No pending MIS approval found");
            return response()->json(['status' => 'error', 'message' => 'No pending MIS approval found.'], 404);
        }

        if (!empty($levelRequest)) {
            ItRequest::where('reference_no', $reference_no)
                ->update(['Level_Request' => ucfirst($levelRequest), 'updated_at' => now()]);
            Log::info("updateStatus: Level_Request updated", ['Level_Request' => $levelRequest]);
        }

        // MIS Logic by status
        Log::info("updateStatus: Handling MIS logic by status", ['status' => $status]);
        switch ($status) {
            case 'completed':
                $hasPendingNonMIS = DB::table('it_request_approval')
                    ->join('users', 'users.email', '=', 'it_request_approval.approver_email')
                    ->where('it_request_approval.reference_no', $reference_no)
                    ->where('it_request_approval.status', 'Pending')
                    ->where('users.department', 'not like', 'MIS%')
                    ->exists();

                Log::info("updateStatus: Pending Non-MIS approvals exist?", ['hasPendingNonMIS' => $hasPendingNonMIS]);

                if ($hasPendingNonMIS) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot mark as Completed. There are still pending Non-MIS approvals.',
                    ], 400);
                }

                ItRequest::where('reference_no', $reference_no)
                    ->update([
                        'status' => 'Completed',
                        'Level_Request' => $levelRequest ?: $itRequest->Level_Request,
                        'updated_at' => now(),
                    ]);
                Log::info("updateStatus: IT request marked as Completed", ['reference_no' => $reference_no]);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Request marked as Completed and requestor notified.']);

            case 'approved':
                if (in_array($levelRequest, ['high', 'very high'])) {
                    $existsSM = ItRequestApproval::where('reference_no', $reference_no)
                        ->where('role', 'Senior Manager')
                        ->exists();
                    Log::info("updateStatus: Senior Manager escalation needed?", ['existsSM' => $existsSM]);

                    if (!$existsSM) {
                        ItRequestApproval::create([
                            'reference_no' => $reference_no,
                            'approved_by' => 'Senior Manager',
                            'role' => 'Senior Manager',
                            'current_approver_role' => 'Senior Manager',
                            'approver_email' => 'fernan.dichoso.cj@sanden-rs.com',
                            'status' => 'Pending',
                            'remarks' => '',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        Log::info("updateStatus: Senior Manager approval created for escalation");
                    }

                    $this->sendNotificationEmail(
                        ['jeffrey.salagubang.js@sanden-rs.com'],
                        "[IT Request Approval] {$reference_no} - Senior Manager Review Needed",
                        $itRequest,
                        $reference_no
                    );

                    DB::commit();
                    return response()->json(['status' => 'success', 'message' => 'Forwarded to Senior Manager for final approval.']);
                }

                $this->finalizeRequestStatus($itRequest, $reference_no, 'Approved', $levelRequest);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Request Approved and requestor notified.']);

            case 'disapproved':
                $this->finalizeRequestStatus($itRequest, $reference_no, 'Disapproved', $levelRequest);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Request Disapproved and requestor notified.']);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => "Request {$status}."]);

    } catch (\Exception $ex) {
        DB::rollBack();
        Log::error("updateStatus: Exception occurred", [
            'message' => $ex->getMessage(),
            'trace' => $ex->getTraceAsString(),
        ]);
        return response()->json(['status' => 'error', 'message' => 'Internal error occurred.'], 500);
    }
}


/**
 * 🔹 Centralized Email Sender
 */
private function sendNotificationEmail($to, $subject, $itRequest, $reference_no, $view = 'emails.ITRequestApproval', $cc = [])
{
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

        (new RequestMailService())->registration_mail($to, $cc, [], $subject, $body, []);
    } catch (\Exception $mailEx) {
        Log::error("[$reference_no] Email send failed: " . $mailEx->getMessage());
    }
}
private function finalizeRequestStatus($itRequest, $reference_no, $status, $levelRequest)
{
    // Default Level_Request to 'Low' if empty
    $levelRequest = !empty($levelRequest) ? $levelRequest : 'Low';

    $updateData = [
        'status' => $status,
        'Level_Request' => $levelRequest,
        'updated_at' => now()
    ];

    Log::info("finalizeRequestStatus: Updating IT request {$reference_no}", $updateData);

    ItRequest::where('reference_no', $reference_no)->update($updateData);

    $requestorEmail = $itRequest->requestor_email;
    if (!filter_var($requestorEmail, FILTER_VALIDATE_EMAIL)) {
        Log::warning("[$reference_no] Invalid requestor email. Using fallback.");
        $requestorEmail = 'mis.scp@sanden-rs.com';
    }

    $this->sendNotificationEmail(
        [$requestorEmail],
        "[IT Request {$status}] {$reference_no} - Your request has been {$status}",
        $itRequest,
        $reference_no
    );
}

public function index()
{
    $requests = ItRequestApproval::latest()->paginate(10);
    return view('it_request.partial_view', compact('requests'));
}
public function show($id)
{
    $request = ItRequestApproval::findOrFail($id);

    // Return JSON for modal
    return response()->json([
        'id' => $request->id,
        'reference_no' => $request->reference_no,
        'status' => $request->status,
        
        'created_at' => $request->created_at,
    ]);
}




}
