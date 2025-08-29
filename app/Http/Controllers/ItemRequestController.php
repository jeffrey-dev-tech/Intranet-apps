<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItemRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ItemRequestMailController;

class ItemRequestController extends Controller
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

    public function insert_request_item(Request $request)
            {
    try {
        
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'date_needed' => 'required|date',
            'date_plan_return' => 'required|date',
            'email' => 'required|email',
            'department' => 'required|string|max:255',
            'requestor_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255'
        ]);

        DB::beginTransaction();

        
        $item = ItemRequest::create($validated);

        //  Prepare email
        $to = ['sanden.mis.sm@sanden-rs.com'];
        $cc = [];
        $bcc = [];
        $subject = "Item Request: {$request->item_name}";
        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));

        $body = view('emails.item_request', [
            'logoDataUri' => $logoDataUri,
            'requestor_name' => $request->requestor_name,
            'item_name' => $request->item_name,
            'qty' => $request->qty,
            'date_needed' => $request->date_needed,
            'date_plan_return' => $request->date_plan_return,
            'department' => $request->department,
            'email' => $request->email,
            'purpose' => $request->purpose
        ])->render();

   if (! $this->isInternetAvailable()) {
    DB::rollBack();
    return response()->json([
        'status' => 'error',
        'message' => 'SandenIntranet has no Internet. Please try again later or contact the developer.'
    ], 501); //  Custom HTTP status code
}

// ✅ Send email
$mailController = new ItemRequestMailController();
$emailSent = $mailController->insert_request_mail($to, $cc, $bcc, $subject, $body);

if (!$emailSent) {
    DB::rollBack();
    return response()->json([
        'status' => 'error',
        'message' => 'Failed to send email, request not saved.'
    ], 500);
}


        //  Commit only if email succeeded
        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Item request created and email sent successfully!',
            'data' => $item
        ], 201);

    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
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
    $data = ItemRequest::all();
    // Return as JSON (common for APIs)
    return response()->json($data);
}

public function view_item_request_data()
{
      return view('form_data.item_request');   
}

}
