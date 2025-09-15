<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Policy;
use phpseclib3\Net\SFTP;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
class PolicyController extends Controller
{
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


public function upload_sftp_sql(Request $request)
{
    // ✅ Validate request
    $request->validate([
        'fileToUpload' => 'required|file|mimes:pdf|max:100240', // Must be PDF
        'department'   => 'required|string',
        'label_name'   => 'required|string|max:255',
        'filename'     => 'required|string|max:255',
        'file_type'     => 'required|string|max:255',
        'doc_type'     => 'required|string|max:255',
        'control_type' => 'required|string|max:255',
    ]);
    $request->headers->set('Accept', 'application/json');
    // ✅ Sanitize and prepare values
    $department = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('department'));
    $label = basename($request->input('label_name'));
    $rawFilename = $request->input('filename');
    $filename = Str::endsWith($rawFilename, '.pdf') ? $rawFilename : $rawFilename . '.pdf';
    $doc_type =  $request->input('doc_type');
    $control_type =  $request->input('control_type');
    $file_type =  $request->input('file_type');
    $file = $request->file('fileToUpload');

    // ✅ SFTP config
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_remote_path = env('SFTP_BASE_PATH');

    DB::beginTransaction();
    try {
        // ✅ Connect to SFTP
        $sftp = new SFTP($sftp_host, $sftp_port);
        if (!$sftp->login($sftp_user, $sftp_pass)) {
            throw new Exception('SFTP login failed.');
        }

        $remoteDir = "$base_remote_path/$department";
        $remoteFilePath = "$remoteDir/$filename";

        // ✅ Check if department folder exists
        if (!$sftp->is_dir($remoteDir)) {
            throw new Exception("Department folder '$department' does not exist.");
        }

        // ✅ Upload file to SFTP
        if (!$sftp->put($remoteFilePath, file_get_contents($file->getRealPath()))) {
            throw new Exception('SFTP upload failed.');
        }

        // ✅ Save to DB
        Policy::create([
            'filename'   => $filename, // Use correct filename
            'label'      => $label,
            'department' => $department,
            'file_type'  => $file_type,
             'doc_type'  => $doc_type,
             'control_type'  => $control_type,
              'upload_date'  => now(),
        ]);

        DB::commit(); // ✅ Commit after both SFTP and DB succeed

        return response()->json(['message' => 'File uploaded and saved successfully.']);
    } catch (Exception $e) {
        DB::rollBack(); // ✅ Rollback DB if error occurs
        return response()->json(['error' => '❌ ' . $e->getMessage()], 500);
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
         'filetype'     => 'required|string|max:255',
          'control_type'     => 'required|string|max:255',
           'upload_date'  => now(),
    ]);

    $policy = Policy::findOrFail($request->id);
    $department = $policy->department;

    $label = basename($request->input('new_label'));
    $filetype = $request->input('filetype');
    $rawFilename = $request->input('filename');
     $control_type = $request->input('control_type');
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
             'file_type' => $filetype,
             'control_type' => $control_type,

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
    
}
