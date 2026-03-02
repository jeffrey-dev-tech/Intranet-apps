<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Policy;
use App\Models\CategoryEdms;
use phpseclib3\Net\SFTP;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PolicyController extends Controller
{


public function getCategoryData()
{
    // Example: Fetch category and its related documents
    $category = CategoryEdms::all();

    return [
        'category_id'   => $category->id,
        'category_name' => $category->name,
    ];
}


  public function getData(Request $request)
{
    $department = $request->input('department'); // e.g., HR
    $doc_type = $request->input('doc_type');     // e.g., POL

    // Get the latest filename like 'HRPOL001'
    $latestFilename = Policy::where('department', $department)
                            ->where('doc_type', $doc_type)
                            ->orderBy('id', 'desc')
                            ->value('filename');

    if (!$latestFilename) {
        $nextFilename = $department . $doc_type . '001';
    } else {
        // Calculate prefix length
        $prefixLength = strlen($department . $doc_type);

        // Extract numeric part
        $number = (int) substr($latestFilename, $prefixLength);

        // Increment and format to 3 digits
        $nextNumber = str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        
        $nextFilename = $department . $doc_type . $nextNumber;
    }

    return response()->json(['filename' => $nextFilename]);
}
public function categorytbl()
{
    $userRole = Auth::user()->role; // sample: 1,2,3,4,5
    $allowedLevels = range(1, $userRole);

    $results = CategoryEdms::withCount(['policies' => function ($query) use ($allowedLevels) {
        $query->whereIn('access_level', $allowedLevels);
    }])->get();

    return view('edms.category', compact('results'));
}

public function policy_tbl(Request $request)
{
    $department = trim($request->input('department', 'No department')); // ✅ correct for POST

                $userRole = Auth::user()->role;
                if ($userRole == 'user_s3' || $userRole == 'developer' || $userRole == 'users_s1' || $userRole =='admin' ) {
                    $data = Policy::where('department', $department)->get();
                } elseif ($userRole == 'users_s2') {
                    $data = Policy::where('department', $department)
                                ->where('control_type', 'non_confidential')
                                ->get();
                }
    

  return response()->json([
    'userRole' => $userRole,
    'data'     => $data
]);
}
public function toggleStatus(Request $request)
{
    // ✅ Validate incoming request
    $validated = $request->validate([
        'id' => 'required|integer|exists:documents,id',
        'status' => 'required|boolean', // match the JS key
    ]);

    try {
        // Wrap in transaction for rollback
        DB::beginTransaction();

        $policy = Policy::find($validated['id']);
        if (!$policy) {
            // Rollback just in case (though nothing changed yet)
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Document not found.'
            ], 404);
        }

        // Update status
        $policy->active = $validated['status'];
        $policy->save();

        // Commit transaction
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'active' => $policy->active
        ]);

    } catch (\Exception $e) {
        // Rollback on any error
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Failed to update status: ' . $e->getMessage()
        ], 500);
    }
}

public function document_tbl(Request $request)
{
    $category_id = trim($request->input('category_id', 'No Category ID')); 
    $userRole = Auth::user()->role; // sample value is 1,2,3,4,5
    $userID = Auth::user()->id;
    // Get all access levels up to the user's role
    $allowedLevels = range(1, $userRole);
    $allowedLevelsTop = range(1, 4);
    $userIDs = [63, 45];
    if(in_array($userID, $userIDs)){ // neil and gerryca
    $data = Policy::where('category_id', $category_id)
                  ->whereIn('access_level',$allowedLevelsTop)
                  ->get();
    }else{
        $data = Policy::where('category_id', $category_id)
                  ->whereIn('access_level', $allowedLevels)
                  ->get();
    }


    

    return response()->json([
        'userRole' => $userRole,
        'allowedLevels' => $allowedLevels,
        'data'     => $data
    ]);
}




public function upload_sftp_sql(Request $request)
{
    $requestId = uniqid('upload_', true); // 🔑 Unique ID to trace request in logs

    Log::info("🚀 upload_sftp_sql started", [
        'request_id' => $requestId,
        'ip'         => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
    ]);

    try {
        // ✅ Validate request
        try {
            $request->validate([
                'fileToUpload' => 'required|file|mimetypes:application/pdf,application/x-pdf,application/octet-stream,binary/octet-stream|mimes:pdf|max:10000', // 10 MB
                'department'   => 'required|string',
                'label_name'   => 'required|string|max:255',
                'filename'     => 'required|string|max:255',
                'doc_type'     => 'required|string|max:255',
                'access_level' => 'required|integer',
                'category_id'  => 'required|integer|exists:category_tbl,id', // ✅ Added validation
            ], [
                'fileToUpload.required' => 'Please select a file to upload.',
                'fileToUpload.file' => 'The uploaded file is invalid.',
                'fileToUpload.mimetypes' => 'Only PDF files are allowed.',
                'fileToUpload.mimes' => 'The file must be a PDF.',
                'fileToUpload.max' => 'The file size may not exceed 10 MB.',
                'department.required' => 'Please select a department.',
                'label_name.required' => 'Please enter a label name.',
                'filename.required' => 'Please enter a file name.',
                'category_id.required' => 'Please select a category.',
                'category_id.exists' => 'The selected category does not exist.',
            ]);
        } catch (ValidationException $ve) {
            Log::error("❌ Validation failed", [
                'request_id' => $requestId,
                'errors'     => $ve->errors(),
            ]);
            return response()->json(['errors' => $ve->errors()], 422);
        }

        $request->headers->set('Accept', 'application/json');

        // ✅ Sanitize and prepare values
        $department   = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('department'));
        $label        = basename($request->input('label_name'));
        $rawFilename  = $request->input('filename');
        $filename     = Str::endsWith($rawFilename, '.pdf') ? $rawFilename : $rawFilename . '.pdf';
        $doc_type     = $request->input('doc_type');
        $access_level = $request->input('access_level');
        $category_id  = (int) $request->input('category_id'); // ✅ Added
        $file         = $request->file('fileToUpload');

        Log::debug("📄 Prepared file metadata", [
            'request_id'   => $requestId,
            'department'   => $department,
            'label'        => $label,
            'filename'     => $filename,
            'doc_type'     => $doc_type,
            'access_level' => $access_level,
            'category_id'  => $category_id, // ✅ Logged
            'filesize'     => $file->getSize(),
        ]);

        // ✅ SFTP config
        $sftp_host = env('SFTP_HOST');
        $sftp_port = env('SFTP_PORT');
        $sftp_user = env('SFTP_USER');
        $sftp_pass = env('SFTP_PASS');
        $base_remote_path = env('SFTP_BASE_PATH');

        DB::beginTransaction();
        try {
            // ✅ Connect to SFTP
            Log::info('🔌 Connecting to SFTP', [
                'request_id' => $requestId,
                'host'       => $sftp_host,
                'port'       => $sftp_port,
                'user'       => $sftp_user,
            ]);

            $sftp = new SFTP($sftp_host, $sftp_port);
            if (!$sftp->login($sftp_user, $sftp_pass)) {
                throw new Exception('SFTP login failed.');
            }
            Log::info('✅ SFTP login successful', ['request_id' => $requestId]);

            $remoteDir      = "$base_remote_path/$department";
            $remoteFilePath = "$remoteDir/$filename";

            if (!$sftp->is_dir($remoteDir)) {
                throw new Exception("Department folder '$department' does not exist.");
            }

            Log::info("📂 Remote directory exists", [
                'request_id' => $requestId,
                'remoteDir'  => $remoteDir
            ]);

            if (!$sftp->put($remoteFilePath, file_get_contents($file->getRealPath()))) {
                throw new Exception('SFTP upload failed.');
            }

            Log::info("📤 File uploaded successfully", [
                'request_id' => $requestId,
                'path'       => $remoteFilePath,
            ]);

            // ✅ Save DB record with category_id
            $policy = Policy::create([
                'filename'      => $filename,
                'label'         => $label,
                'department'    => $department,
                'doc_type'      => $doc_type,
                'access_level'  => $access_level,
                'category_id'   => $category_id,  // ✅ Added here
                'upload_date'   => now(),
            ]);

            Log::info('🗄️ Policy saved to DB', [
                'request_id' => $requestId,
                'policy_id'  => $policy->id,
            ]);

            DB::commit();
            Log::info('🎉 Transaction committed', ['request_id' => $requestId]);

            return response()->json(['message' => 'File uploaded and saved successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("❌ Transaction failed", [
                'request_id' => $requestId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => '❌ ' . $e->getMessage()], 500);
        }
    } catch (Exception $e) {
        Log::critical("🔥 Unexpected system error in upload_sftp_sql", [
            'request_id' => $requestId,
            'error'      => $e->getMessage(),
            'trace'      => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Unexpected server error'], 500);
    }
}

public function update_sftp_sql(Request $request)
{
    
    $request->headers->set('Accept', 'application/json');

    // Validate input
    $request->validate([
        'id'           => 'required|integer|exists:documents,id',
        'fileToUpload' => 'nullable|file|mimes:pdf|max:100240',
        'new_label'    => 'required|string|max:255',
        'filename'     => 'required|string|max:255',
        'access_level'     => 'required|integer',
         'category_id'     => 'required|integer',
        'upload_date'  => now(),
    ]);

    $policy = Policy::findOrFail($request->id);
    $department = $policy->department;

    $label = basename($request->input('new_label'));
    $rawFilename = $request->input('filename');
    $access_level = $request->input('access_level');
      $category_id = $request->input('category_id');
    $filename = Str::endsWith($rawFilename, '.pdf') ? $rawFilename : $rawFilename . '.pdf';

    // SFTP config
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');

    DB::beginTransaction();
    try {
        // Connect to SFTP
        $sftp = new SFTP($sftp_host, $sftp_port);
        if (!$sftp->login($sftp_user, $sftp_pass)) {
            throw new \Exception('SFTP login failed.');
        }

        $remoteDir = "$base_remote_path/$department";
        $remoteFilePath = "$remoteDir/$filename";

        if (!$sftp->is_dir($remoteDir)) {
            throw new \Exception("Department folder '$department' does not exist.");
        }

        if ($request->hasFile('fileToUpload')) {
            $file = $request->file('fileToUpload');
            if (!$file->isValid()) {
                throw new \Exception('Uploaded file is invalid.');
            }
            if (!$sftp->put($remoteFilePath, file_get_contents($file->getRealPath()))) {
                throw new \Exception('SFTP upload failed.');
            }
        }

        // Update DB
        $policy->update([
            'label'    => $label,
            'filename' => $filename,
             'access_level' => $access_level,
              'category_id' => $category_id,

        ]);

        DB::commit();
        return response()->json(['message' => '✅ Successfully updated']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => '❌ ' . $e->getMessage()], 500);
    }
}



      public function policy_render(Request $request)
        {
            $department = $request->query('department', 'No department');
            return view('pages.policy_render', ['department' => $department]);
        }

public function document_render($category_id, $category_name)
{

   $results = CategoryEdms::all();
  return view('edms.document_render', [
        'category_id' => $category_id,
        'category_name' => $category_name,
        'categories' => $results
    ]);
}

}
