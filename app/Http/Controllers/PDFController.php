<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Net\SFTP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Mpdf\Mpdf;
class PDFController extends Controller
{
    public function view(Request $request)
    {
        $department = $request->query('department');
        $filename = basename($request->query('file', ''));

        if (!$department || !$filename) {
            return response('❌ Missing file or department', 400);
        }
            $sftp_host = env('SFTP_HOST');
            $sftp_port = env('SFTP_PORT');
            $sftp_user = env('SFTP_USER');
            $sftp_pass = env('SFTP_PASS');
            $remotePath = "/EFS/$department/$filename";

        // Determine content type
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $contentTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'txt'  => 'text/plain',
        ];

        $contentType = $contentTypes[$ext] ?? 'application/octet-stream';

        // SFTP connection
        $sftp = new SFTP($sftp_host, $sftp_port);
        if (!$sftp->login($sftp_user, $sftp_pass)) {
            return response('❌ SFTP Login Failed.', 500);
        }

        $fileContent = $sftp->get($remotePath);

        if ($fileContent === false) {
            return response("❌ Failed to fetch file: $remotePath", 404);
        }

        return response($fileContent, 200)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }



public function deleteFile(Request $request)
{
    $request->headers->set('Accept', 'application/json');

    $request->validate([
        'department' => 'required|string|max:255',
        'filename' => 'required|string|max:255'
    ]);

    $department = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('department'));
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $request->input('filename'));

    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT');
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $base_path = env('SFTP_BASE_PATH', '/EFS');
    
    // ✅ Construct full path to the file
    $remoteFilePath = "$base_path/$department/$filename";

    try {
        DB::beginTransaction();

        $sftp = new SFTP($sftp_host, $sftp_port);
        if (!$sftp->login($sftp_user, $sftp_pass)) {
            throw new \Exception("❌ SFTP login failed.");
        }

        if (!$sftp->delete($remoteFilePath)) {
            throw new \Exception("❌ Failed to delete file: $remoteFilePath");
        }

        DB::table('documents')
            ->where('filename', $filename)
            ->where('department', $department)
            ->delete();

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => '✅ File deleted successfully.'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('File delete error', ['error' => $e->getMessage()]);

        return response()->json([
            'status' => 'error',
            'message' => '❌ ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
public function downloadFile(Request $request)
{
    // Decode Base64 params
    $encodedDept = $request->query('department');
    $encodedFile = $request->query('file');

    if (!$encodedDept || !$encodedFile) {
        return response('❌ Missing parameters', 400);
    }

    $department = base64_decode($encodedDept, true);
    $filename = base64_decode($encodedFile, true);

    if ($department === false || $filename === false) {
        return response('❌ Invalid encoded data', 400);
    }

    // Validate filename
    if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
        return response('❌ Invalid file name', 403);
    }

    // Load SFTP credentials
    $sftp_host = env('SFTP_HOST');
    $sftp_port = env('SFTP_PORT', 22);
    $sftp_user = env('SFTP_USER');
    $sftp_pass = env('SFTP_PASS');
    $remotePath = "/EFS/$department/$filename";

    // Connect to SFTP
    $sftp = new SFTP($sftp_host, $sftp_port);
    if (!$sftp->login($sftp_user, $sftp_pass)) {
        return response('❌ SFTP Login Failed.', 500);
    }

    // Fetch PDF file
    $fileContent = $sftp->get($remotePath);
    if ($fileContent === false) {
        return response('❌ File not found on SFTP server.', 404);
    }

    // ✅ Add watermark using mPDF
    $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tempFile, $fileContent);

    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => storage_path('app/mpdf-temp'),
    ]);

    $watermarkText = 'UNCONTROLLED'; // Change or make dynamic
    $mpdf->SetWatermarkText($watermarkText, 0.1);
    $mpdf->showWatermarkText = true;

    // Import original PDF and apply watermark
 $pagecount = $mpdf->SetSourceFile($tempFile);

for ($i = 1; $i <= $pagecount; $i++) {
    $tplId = $mpdf->ImportPage($i);
    $mpdf->AddPage();
    $mpdf->UseTemplate($tplId);
}


    $fileContent = $mpdf->Output('', 'S'); // Get as string

    // ✅ Stream the watermarked PDF as download
    return response()->streamDownload(function () use ($fileContent) {
        echo $fileContent;
    }, $filename, [
        'Content-Type' => 'application/pdf',
        'Content-Length' => strlen($fileContent),
    ]);
}

}
