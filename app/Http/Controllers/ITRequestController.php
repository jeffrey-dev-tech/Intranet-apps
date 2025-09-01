<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ItemRequestMailController;
use Illuminate\Support\Facades\Log;

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

        // --- Base validation (common fields) ---
        $rules = [
            'type_request' => 'required|string',
            'requestor_name' => 'required|string',
            'department' => 'required|string',
            'requestor_email' => 'required|email',
           'description_of_request' => 'required|string|max:1000',

        ];

        // --- Conditional rules per type ---
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
            case 'New_Intranet_Subsystem':
                $rules['subsystem_title'] = 'required|string';
                $rules['manager_email'] = 'required|email';
                break;
            case 'Change_Request_Intranet':
                $rules['change_request_intranet'] = 'required|string';
                $rules['manager_email'] = 'required|email';
                break;
        }

        // --- Validate request ---
        $validated = $request->validate($rules);

        \Log::info('Validated IT Request data:', $validated);

        // --- Start transaction ---
        DB::beginTransaction();

        // --- Create IT request ---
        $item = ItRequest::create($validated);

        \Log::info('IT Request created:', ['id' => $item->id, 'reference_no' => $item->reference_no]);

        DB::commit(); // Commit immediately after successful creation

        // --- Prepare email ---
        try {
            $to = ['sanden.mis.sm@sanden-rs.com'];
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

            // Check internet connection before sending
            if ($this->isInternetAvailable()) {
                $mailController = new ItemRequestMailController();
                $emailSent = $mailController->insert_request_mail($to, $cc, $bcc, $subject, $body);

                if (!$emailSent) {
                    \Log::error('Email sending failed for IT request ID: '.$item->id);
                    // Email failure does NOT roll back the request
                }
            } else {
                \Log::warning('No internet, email not sent for IT request ID: '.$item->id);
            }

        } catch (\Exception $emailEx) {
            \Log::error('Exception during email sending: ', ['exception' => $emailEx]);
        }

        // --- Return success ---
        return response()->json([
            'status' => 'success',
            'message' => 'Item request created successfully!',
            'data' => $item
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Validation failed:', $e->errors());
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('IT Request creation failed:', ['exception' => $e]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create item request',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function fetch_all_data()
{
      // Retrieve all records from ItemRequest
    $data = ItRequest::all();
    // Return as JSON (common for APIs)
    return response()->json($data);
}

public function view_item_request_data()
{
      return view('form_data.it_request');   
}

}
