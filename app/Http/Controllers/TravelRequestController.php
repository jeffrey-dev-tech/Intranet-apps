<?php

namespace App\Http\Controllers;
use App\Models\TravelRequestApproval;
use App\Models\TravelRequest;
use App\Models\User;
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
        return view('forms.TravelRequestForm');
}
public function TravelRequestPage()
{
    return view('form_data.travel_request'); // Your Blade with JS tabs
}

 public function TravelRequestData()
    {
        $userId = Auth::id();

        // Base query with user relationship
        $query = TravelRequest::with('user')->orderBy('created_at', 'desc');

        // Only show user's requests if not admin
        if ($userId != TravelRequestApproval::ADMIN_USER) {
            $query->where('user_id', $userId);
        }

        $travelData = $query->get();

        // Return JSON for JS
        return response()->json($travelData);
    }




    /**
     * Insert a new travel request.
     */
public function TravelRequestInsert(Request $request)
{
    DB::beginTransaction(); // Start transaction

    try {
        $userId = Auth::id();
        $type = $request->input('request_type');
        Log::info("TravelRequestInsert started.", ['user_id' => $userId, 'request_type' => $type]);

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
            $rules['preferred_time_departure'] = 'required|date_format:Y-m-d\TH:i';
            $rules['preferred_time_return'] = 'required|date_format:Y-m-d\TH:i';
            $rules['accommodation'] = 'required|boolean';
            $rules['attachments'] = 'required|array';
            $rules['attachments.*'] = 'file|max:10240|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,ppt,pptx';
        }

        $validated = $request->validate($rules);
        Log::info("Validation passed.", ['user_id' => $userId, 'validated' => $validated]);

        // --- Generate TRF_NO ---
        $trfNo = TravelRequest::generateTrfNo($type);
        Log::info("TRF number generated.", ['trf_no' => $trfNo]);

        // --- Parse travel dates ---
        $travelFrom = Carbon::createFromFormat('Y-m-d\TH:i', $validated['travel_date_from']);
        $travelTo = Carbon::createFromFormat('Y-m-d\TH:i', $validated['travel_date_to']);

        $attachments = [];

        // --- Handle SFTP upload ---
        if ($request->hasFile('attachments')) {
            Log::info("Processing file attachments.", ['user_id' => $userId]);
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

                Log::info("Uploaded file to SFTP.", ['filename' => $uniqueFilename, 'remote_path' => $remoteFilePath]);
            }
        }

        // --- Save Travel Request ---
        $travelRequest = TravelRequest::create([
            'user_id' => $userId,
            'department' => $validated['department'],
            'travel_date_from' => $travelFrom,
            'travel_date_to' => $travelTo,
            'destination' => $validated['destination'],
            'request_type' => $validated['request_type'],
            'trf_no' => $trfNo,
            'preferred_time_departure' => $validated['preferred_time_departure'] ?? null,
            'preferred_time_return' => $validated['preferred_time_return'] ?? null,
            'accommodation' => $validated['accommodation'] ?? false,
            'purpose' => $validated['purpose'],
            'additional_request' => $validated['additional_request'] ?? null,
            'attachments' => $attachments,
            'status' => TravelRequest::STATUS_PENDING,
        ]);
        Log::info("TravelRequest created successfully.", ['trf_no' => $trfNo]);

        // --- Determine email recipient & Approval workflow ---
if ($type === 'Land Transport') {
    $system_id = 'INTRANET001';
    $now = now();

    $workflowApprovers = [
        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 1],
    ];

    $approversToCreateFinal = [];
    foreach ($workflowApprovers as $approver) {
        $approversToCreateFinal[] = array_merge($approver, [
            'trf_no' => $trfNo,
            'system_id' => $system_id,
            'status' => TravelRequestApproval::STATUS_PENDING,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    if (!empty($approversToCreateFinal)) {
        TravelRequestApproval::insert($approversToCreateFinal);
        Log::info("Land Transport ApprovalRequest inserted successfully.", [
            'trf_no' => $trfNo,
            'approvers' => $approversToCreateFinal
        ]);
    }

    // Email to Admin
    $to = [User::where('id', TravelRequestApproval::ADMIN_USER)->value('email')];
    Log::info("Land Transport: email sent to Admin.", ['to' => $to]);
 } else { // Air Travel
            $user = Auth::user();
            $head_id = $user->head->id ?? null;
            $head_user_id = $user->head->user_id ?? null;
            $user_role = $user->role ?? null;
            $user_id = $user->id ?? null;
            $system_id = 'INTRANET001';
            $approversToCreateFinal = [];
            $now = now();

            Log::info("Air Travel: preparing approval workflow.", [
                'user_id' => $userId,
                'head_id' => $head_id,
                'user_role' => $user_role,
                'trf_no' => $trfNo
            ]);
            $firstApproverEmail = [];
            switch ($head_id) {
                case 13: // COO head id
                    if ($user_role == 2) {
                        $workflowApprovers = [
                        ['user_id' => TravelRequestApproval::COO_USER_ID, 'approval_level' => 1], //COO user_id
                        ['user_id' => TravelRequestApproval::SENIOR_MNGR_MGMT_DIV, 'approval_level' => 2], //Senior Manager user_id
                        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 3], //Admin Personnel
                          ];

                    
                    } elseif ($user_id == TravelRequestApproval::SENIOR_MNGR_MGMT_DIV) {                  // User id is from Senior Manager Management Division
                          $workflowApprovers = [
                        ['user_id' => TravelRequestApproval::COO_USER_ID, 'approval_level' => 1],  //COO user id
                        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 2], //Admin Personnel
                          ];
                    } elseif ($user_id == TravelRequestApproval::VICE_USER_ID) {                  // User id is from Vice President of Cold Chain
                          $workflowApprovers = [
                        ['user_id' => TravelRequestApproval::SENIOR_MNGR_MGMT_DIV, 'approval_level' => 1], //Senior Manager user_id
                        ['user_id' => TravelRequestApproval::COO_USER_ID, 'approval_level' => 2],  //COO user_id
                        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 3], //Admin Personnel user_id
                          ];
                    }
                    break;

                case 6: // Senior Manager Management Division head id
                    if ($user_role == 2 || $user_role == 5) {
                        $workflowApprovers = [
                        ['user_id' => TravelRequestApproval::SENIOR_MNGR_MGMT_DIV, 'approval_level' => 1], //Senior Manager user id
                        ['user_id' => TravelRequestApproval::COO_USER_ID, 'approval_level' => 2],  //COO user id
                        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 3], //Admin Personnel
                          
                        ];
                    }
                    break;

                default:
                    $workflowApprovers = [
                        ['user_id' => $head_user_id, 'approval_level' => 1],
                        ['user_id' => TravelRequestApproval::SENIOR_MNGR_MGMT_DIV, 'approval_level' => 2], //Senior Manager user_id
                        ['user_id' => TravelRequestApproval::COO_USER_ID, 'approval_level' => 3],  //COO user_id
                        ['user_id' => TravelRequestApproval::ADMIN_USER, 'approval_level' => 4], //Admin Personnel user_id
                    ];
                    Log::info("Default workflow applied for unknown head_id {$head_id}.");
                    break;
            }
            
            $firstApproverUserId = collect($workflowApprovers)
                        ->where('approval_level', 1)
                        ->pluck('user_id')
                        ->first();

            $firstApproverEmail = User::where('id', $firstApproverUserId)->value('email');

            foreach ($workflowApprovers as $approver) {
                $approversToCreateFinal[] = array_merge($approver, [
                    'trf_no' => $trfNo,
                    'system_id' => $system_id,
                    'status' => TravelRequestApproval::STATUS_PENDING,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if (!empty($approversToCreateFinal)) {
                TravelRequestApproval::insert($approversToCreateFinal);
                Log::info("ApprovalRequest inserted successfully.", ['trf_no' => $trfNo, 'approvers' => $approversToCreateFinal]);
            } else {
                Log::warning("ApprovalRequest not inserted: no approvers found.", ['trf_no' => $trfNo, 'head_id' => $head_id, 'user_role' => $user_role]);
            }

            // $to = [$user->head->email ?? 'jeffrey.salagubang.js@sanden-rs.com'];
            $to = [$firstApproverEmail];
            // $to = ['jeffrey.salagubang.js@sanden-rs.com'];
            Log::info("Email recipient set for Air Travel request.", ['to' => $to]);
        }

        $subject = "Travel Request {$trfNo} ({$type})";

        // --- Send notification email ---
        $this->sendNotificationEmail($to, $subject, $travelRequest, $trfNo);
        Log::info("Notification email sent.", ['trf_no' => $trfNo, 'to' => $to]);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Travel request submitted successfully!',
            'data' => $travelRequest
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        Log::warning("Validation failed.", ['errors' => $e->errors()]);
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
    array $to,
    string $subject,
    $travelData,
    string $reference_no,
    array $cc = [],
    string $view = 'emails.TravelRequestApproval',
    array $attachments = []
) {
    try {
        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $body = view($view, [
            'reference_no' => $reference_no,
            'type_request' => $travelData->request_type,
            'request_data' => $travelData,
            'logoDataUri'  => $logoDataUri,
        ])->render();

        (new RequestMailService())->registration_mail(
            $to,
            $cc,
            [],
            $subject,
            $body,
            $attachments
        );

    } catch (\Exception $e) {
        Log::error("[$reference_no] Email send failed: " . $e->getMessage());
    }
}


public function approvalForm($trf_no)
{
    $travelData = TravelRequest::with([
        'approvals' => function ($query) {
            $query->orderBy('approval_level', 'asc');
        },
        'approvals.user'
    ])->where('trf_no', $trf_no)
      ->firstOrFail();

    $user = auth()->user();
    $isAdmin = $user->id === 63;

    // Find current approval for logged-in user (if any)
    $currentApproval = $travelData->approvals->where('user_id', $user->id)->first();
    $isApprover = !is_null($currentApproval);

    // Permission to view: requestor, approver, admin
    $canView = $isApprover || $isAdmin || $travelData->user_id === $user->id;
    if (!$canView) abort(404);

    // Determine if user can act
    $canAct = false;
    $hasFinalAction = false;

    if ($isApprover && $currentApproval) {
        if ($currentApproval->status === 'pending') {
            $previousApprovals = $travelData->approvals
                ->where('approval_level', '<', $currentApproval->approval_level);
            $allPreviousApproved = $previousApprovals->every(fn($a) => $a->status === 'approved');
            $canAct = $allPreviousApproved;
        }
        $hasFinalAction = in_array($currentApproval->status, ['approved', 'disapproved']);
    } elseif ($isAdmin) {
        // Admin can act only if all approvals done
        $allApprovalsDone = $travelData->approvals
            ->every(fn($a) => in_array($a->status, ['approved','disapproved']));
        
        $canAct = $allApprovalsDone && $travelData->status !== 'completed';
        $hasFinalAction = !$canAct; // hide row if admin already acted
    }

    return view('approval_form.travel_approval', compact(
        'travelData',
        'isApprover',
        'isAdmin',
        'canView',
        'canAct',
        'hasFinalAction',
        'currentApproval'
    ));
}




public function updateStatus(Request $request, $trf_no)
{
    $user = auth()->user();
    $userId = $user->id;

    // Load travel request with approvals
    $travel = TravelRequest::with('approvals.user')->where('trf_no', $trf_no)->firstOrFail();
    $isAdmin = $userId === TravelRequestApproval::ADMIN_USER;

    // Find current approval row for logged-in user (only active approvals)
    $currentApproval = $travel->approvals
        ->where('user_id', $userId)
        ->where('active', 1)
        ->first();

    $canAct = $currentApproval && $currentApproval->status === TravelRequestApproval::STATUS_PENDING;

    // Validation rules
    $rules = [
        'status' => 'required|string|in:approved,disapproved,received,rejected',
        'remarks' => 'nullable|string|max:1000',
    ];

 $requestType = $travel->request_type ?? null;

// Admin attachments required only if Air Travel AND can act AND status is Received

// Initialize attachment rules
if ($request->hasFile('attachments')) {
    // Any uploaded files must be validated
    $rules['attachments.*'] = 'file|max:10240|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,ppt,pptx';
}

// Admin attachments required only if Air Travel AND status is Received
if ($isAdmin && $canAct 
    && $requestType === 'Air Travel'
    && $request->input('status') === TravelRequestApproval::STATUS_RECEIVED) {
    
    $rules['attachments'] = 'required|array';
}

    $request->validate($rules);

    DB::beginTransaction();

    try {
        $status = $request->status;
        $remarks = $request->remarks;
        $uploadedFiles = [];
        $attachmentsForEmail = [];

        // ------------------ Non-admin approver ------------------
        if (!$isAdmin) {
            if (!$currentApproval) {
                return response()->json(['status' => 'error', 'message' => 'You are not an approver.'], 403);
            }

            $previousApprovals = $travel->approvals
                ->where('approval_level', '<', $currentApproval->approval_level)
                ->where('active', 1);

            $allPreviousApproved = $previousApprovals->every(fn($a) => $a->status === TravelRequestApproval::STATUS_APPROVED);

            if (!$allPreviousApproved) {
                return response()->json(['status' => 'error', 'message' => 'Previous approvals are pending.'], 403);
            }

            $currentApproval->update([
                'status' => $status,
                'remarks' => $remarks,
                'active' => 0
            ]);

            if ($status === TravelRequestApproval::STATUS_APPROVED) {
                $nextApproval = $travel->approvals
                    ->where('status', TravelRequestApproval::STATUS_PENDING)
                    ->where('active', 0)
                    ->sortBy('approval_level')
                    ->first();

                if ($nextApproval) {
                    $nextApproval->update(['active' => 1]);

                    if ($nextApproval->user) {
                        $this->sendNotificationEmail(
                            [$nextApproval->user->email],
                            "Travel Request {$trf_no} Pending Your Approval",
                            $travel,
                            $trf_no
                        );
                    }
                }
            }

        } else {
            // ------------------ Admin ------------------
            if (!in_array($status, [TravelRequestApproval::STATUS_RECEIVED, TravelRequestApproval::STATUS_REJECTED])) {
                return response()->json(['status' => 'error', 'message' => 'Admin can only mark as Received or Rejected.'], 403);
            }

            $allApprovalsDone = $travel->approvals
                ->where('user_id', '!=', $userId)
                ->where('active', 1)
                ->every(fn($a) => in_array($a->status, [
                    TravelRequestApproval::STATUS_APPROVED,
                    TravelRequestApproval::STATUS_REJECTED
                ]));

            if (!$allApprovalsDone) {
                return response()->json(['status' => 'error', 'message' => 'Not all approvals are completed yet.'], 403);
            }

            $adminApproval = $travel->approvals->where('user_id', $userId)->where('active', 1)->first();
            if ($adminApproval) {
                $adminApproval->update([
                    'status' => $status,
                    'remarks' => $remarks,
                    'active' => 0
                ]);
            }

            // ------------------ Handle attachments ------------------
           if ($request->hasFile('attachments')) {
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');
    $travel_folder = 'Attachments_Travel_Request';

    $sftp = new \phpseclib3\Net\SFTP($sftp_host, $sftp_port);
    if (!$sftp->login($sftp_user, $sftp_pass)) throw new \Exception('SFTP login failed.');
    $remoteDir = "$base_remote_path/$travel_folder";
    if (!$sftp->is_dir($remoteDir)) throw new \Exception("Remote directory does not exist.");

    foreach ($request->file('attachments') as $file) {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $uniqueFilename = "{$trf_no}_" . now()->format('Ymd_His') . "_" . Str::upper(Str::random(5)) . "_{$originalName}.{$extension}";

        $localPath = storage_path("app/temp/{$uniqueFilename}");
        $file->move(storage_path('app/temp'), $uniqueFilename);

        $remoteFilePath = "$remoteDir/$uniqueFilename";
        if (!$sftp->put($remoteFilePath, file_get_contents($localPath))) {
            throw new \Exception("Failed to upload file: $uniqueFilename");
        }

        $uploadedFiles[] = $uniqueFilename;

        $attachmentsForEmail[] = [
            'path' => $localPath,
            'name' => $file->getClientOriginalName()
        ];
    }

    // ------------------ Merge admin attachments as plain array ------------------
    $existingAdminAttachments = $travel->attachments_admin ?? [];

    // If somehow it's stored as JSON string, decode it
    if (is_string($existingAdminAttachments)) {
        $existingAdminAttachments = json_decode($existingAdminAttachments, true);
        if (!is_array($existingAdminAttachments)) {
            $existingAdminAttachments = [];
        }
    }

    // Merge new uploaded files
    $travel->attachments_admin = array_merge($existingAdminAttachments, $uploadedFiles);
}


            if ($status === TravelRequestApproval::STATUS_RECEIVED) {
                $travel->status = TravelRequest::STATUS_COMPLETED;
            } elseif ($status === TravelRequestApproval::STATUS_REJECTED) {
                $travel->status = TravelRequest::STATUS_REJECTED;
                $travel->approvals()
                    ->where('status', TravelRequestApproval::STATUS_PENDING)
                    ->where('active', 1)
                    ->update(['active' => 0]);
            }

            $travel->save();

            $requestorEmail = $travel->user->email ?? null;
            if ($requestorEmail) {
                $this->sendNotificationEmail(
                    [$requestorEmail],
                    "Your Travel Request {$trf_no} has been {$status}",
                    $travel,
                    $trf_no,
                    ['jeffreysalagubang10@gmail.com'],
                    'emails.TravelRequestApproval',
                    $attachmentsForEmail
                );
            }

            // ------------------ Cleanup temporary files ------------------
            foreach ($attachmentsForEmail as $file) {
                if (file_exists($file['path'])) unlink($file['path']);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully.',
            'attachments' => $uploadedFiles
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('updateStatus failed', ['user_id' => $userId, 'trf_no' => $trf_no, 'message' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}



public function downloadTravelAttachment($filename)
{
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');
    $travel_folder = 'Attachments_Travel_Request';

    $filename = urldecode($filename); // decode spaces and special chars
    $remoteDir = "$base_remote_path/$travel_folder";

    $sftp = new \phpseclib3\Net\SFTP($sftp_host, $sftp_port);
    if (!$sftp->login($sftp_user, $sftp_pass)) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>⚠️ SFTP login failed. Please contact IT support.</h3>");
    }

    // ✅ List files in folder
    $files = $sftp->nlist($remoteDir);
    if (!$files || !in_array($filename, $files)) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>⚠️ The file <strong>$filename</strong> has already been deleted or is no longer available.</h3>", 404);
    }

    $fileContent = $sftp->get("$remoteDir/$filename");
    if ($fileContent === false) {
        return response("<h3 style='color:red;text-align:center;margin-top:50px;'>⚠️ Unable to read file <strong>$filename</strong>.</h3>", 404);
    }

    // Detect MIME type
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = [
        'pdf'=>'application/pdf', 'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png',
        'gif'=>'image/gif','txt'=>'text/plain','html'=>'text/html','doc'=>'application/msword',
        'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'=>'application/vnd.ms-excel','xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'=>'application/vnd.ms-powerpoint','pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];
    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    return response($fileContent)
        ->header('Content-Type', $mimeType)
        ->header('Content-Disposition', 'inline; filename="' . basename($filename) . '"');
}



}
