<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QRPrintController extends Controller
{
    public function print(Request $request)
    {
        // 1) Get the QR value (from your frontend)
        $qr = (string) $request->input('qr', '');

        if ($qr === '') {
            return response()->json(['status' => 'error', 'message' => 'QR is empty'], 422);
        }

        // 2) Write to a temp file that smbclient can send
        $tmpFile = tempnam(sys_get_temp_dir(), 'qrprint_');
        file_put_contents($tmpFile, $qr . PHP_EOL);

        // 3) Build smbclient command safely
        $host   = env('SMB_HOST');
        $share  = env('SMB_SHARE');   // may contain spaces
        $domain = env('SMB_DOMAIN');
        $user   = env('SMB_USER');
        $pass   = env('SMB_PASS');

        // SMB path with quotes to handle spaces
        $smbPath = sprintf('//%s/%s', $host, $share);

        // Domain\User%Pass string (quote to avoid shell interpretation)
        $auth = $domain . '\\' . $user . '%' . $pass;

        // Wrap arguments with escapeshellarg for safety
        $cmd = sprintf(
            'smbclient %s -U %s -c %s',
            escapeshellarg($smbPath),
            escapeshellarg($auth),
            escapeshellarg('print ' . $tmpFile)
        );

        // 4) Execute
        exec($cmd . ' 2>&1', $output, $code);

        // 5) Cleanup
        @unlink($tmpFile);

        if ($code === 0) {
            return response()->json(['status' => 'success']);
        } else {
            Log::error('QR print failed', ['cmd' => $cmd, 'output' => $output, 'code' => $code]);
            return response()->json([
                'status' => 'error',
                'message' => 'SMB print failed',
                'details' => $output
            ], 500);
        }
    }
}
