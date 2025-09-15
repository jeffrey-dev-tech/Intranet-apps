<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ITRequestMailController;
use Illuminate\Support\Facades\Log;
use App\Models\ItRequestApproval;
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
                $rules['priority'] = 'required|string';
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
            case 'New_Intranet_Subsystem':https://chatgpt.com/library
                $rules['subsystem_title'] = 'required|string';
                $rules['manager_email'] = 'required|email';
                break;
            case 'Change_Request_Intranet':
                $rules['change_request_intranet'] = 'required|string';
                $rules['manager_email'] = 'required|email';
                break;
        }

        $validated = $request->validate($rules);
        Log::info('Validated IT Request data:', $validated);

        // --- Start transaction ---
        DB::beginTransaction();

  $initialApproverRole = match($type) {
    'New_Intranet_Subsystem', 'Change_Request_Intranet' => 'Manager',
    'Repair_Request' => 'IT',
    default => 'IT',
};

// Determine initial approver email
$initialApproverEmail = match($type) {
    'New_Intranet_Subsystem', 'Change_Request_Intranet' => $request->input('manager_email'),
    'Repair_Request' => 'mis.scp@sanden-rs.com', // manual email
    default => 'mis.scp@sanden-rs.com',
};

// Determine initial approver name
$initialApproverName = match($type) {
    'New_Intranet_Subsystem', 'Change_Request_Intranet' => 
        \App\Models\User::where('email', $initialApproverEmail)->first()?->name ?? null,
    'Repair_Request' => 'MIS SM', // manual name
    default => 'IT Support',
};

// Add current approver and status
$validated['current_approver_role'] = $initialApproverRole;
$validated['status'] = 'Pending';

// Create IT request
$item = ItRequest::create($validated);

// Create initial approval record
\App\Models\ItRequestApproval::create([
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
        $to = ['mis.scp@sanden-rs.com'];
        $cc = [$request->input('requestor_email')];
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
            'message' => 'Your request created successfully! Check your email!',
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
     if (auth()->user()->department == "(SCP) RS-IT") {
    // User is in IT, return all data
    $data = ItRequest::all();
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

        case 'New_Intranet_Subsystem':
            $data['subsystem_title'] = $request->subsystem_title;
            $data['manager_email'] = $request->manager_email;
            break;

        case 'Change_Request_Intranet':
            $data['change_request_intranet'] = $request->change_request_intranet;
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

public function storeApproval(Request $request, $reference_no)
{
    $validated = $request->validate([
        'status'  => 'required|string|in:Pending,Approved,Completed,Rejected',
        'remarks' => 'nullable|string|max:500',
    ]);

    // Fetch the IT request
    $itRequest = ItRequest::where('reference_no', $reference_no)->firstOrFail();

    // Check the type_request value
    $typeRequest = $itRequest->type_request;

    // Find existing approval (if any)
   $approval = ItRequestApproval::where('reference_no', $reference_no)
             ->latest('id')
             ->first();


    if ($approval) {
        // Update existing approval
        $approval->update([
            'status'  => $validated['status'],
            'remarks' => $validated['remarks'],
            'approved_by' => auth()->user()->name ?? 'System',
        ]);
    } else {
        // Optionally, create approval if it doesn't exist
        $approval = ItRequestApproval::create([
            'reference_no' => $reference_no,
            'approved_by'  => auth()->user()->name ?? 'System',
            'status'       => $validated['status'],
            'remarks'      => $validated['remarks'],
        ]);
    }

    // Update only the status in the main IT request
    $itRequest->status = $validated['status'];
    $itRequest->save();

    return response()->json([
        'message'       => 'Approval processed and IT request status updated successfully!',
        'type_request'  => $typeRequest,
        'approval'      => $approval,
        'itRequest'     => $itRequest
    ]);
}




public function index()
{
    $requests = \App\Models\ItRequestApproval::latest()->paginate(10);
    return view('it_request.partial_view', compact('requests'));
}
public function show($id)
{
    $request = \App\Models\ItRequestApproval::findOrFail($id);

    // Return JSON for modal
    return response()->json([
        'id' => $request->id,
        'reference_no' => $request->reference_no,
        'status' => $request->status,
        
        'created_at' => $request->created_at,
    ]);
}




}
