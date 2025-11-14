<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vpn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\RequestMailService;
class VpnController extends Controller
{
    public function vpn_page()
    {
        $results = Vpn::all();
        return view('pages.vpn', compact('results'));
    }

public function generatepass(Request $request)
{
    // Validate input
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:vpn_accounts,id'
    ]);

    $generatedPasswords = [];

    DB::beginTransaction(); // only start transaction once
    try {
        foreach ($request->user_ids as $id) {
            $vpn = Vpn::find($id);
            if (!$vpn) continue;

            $password = $this->generateRandomPassword(8);
            $vpn->password = $password;
            $vpn->send_status = false; // reset send_status

            if (!$vpn->save()) {
                throw new \Exception("Failed to update password for ID {$id}");
            }

            $generatedPasswords[$id] = $password;
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'passwords' => $generatedPasswords
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function vpn_send_mail(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:vpn_accounts,id'
    ]);

    $sent = [];
  foreach ($request->user_ids as $id) {
    $vpn = Vpn::find($id);
    if (!$vpn) continue;

    // Skip if email already sent
    if ($vpn->send_status) continue;

    $logoPath = public_path('img/sanden-logo-white.png');
    $logoDataUri = file_exists($logoPath)
        ? 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath))
        : '';

    $view = 'emails.vpn_credentials';
    $body = view($view, [
        'vpn' => $vpn,
        'logoDataUri' => $logoDataUri
    ])->render();

    try {
        $to = [$vpn->email]; // must be array
        $subject = 'Your VPN Credentials';

        (new RequestMailService())->registration_mail($to, [], [], $subject, $body, []);

        $vpn->send_status = true;
        $vpn->save();

        $sent[] = $vpn->id;
    } catch (\Exception $e) {
        Log::error("Failed to send VPN email to ID {$vpn->id}: " . $e->getMessage());
    }
}


    return response()->json([
        'status' => 'success',
        'sent_ids' => $sent
    ]);
}

public function emailExpiredList()
{
    // Find all VPN accounts with passwords older than 30 days
    $expiredVpns = Vpn::where('updated_at', '<=', now()->subDays(30))->get();

    // Prepare email body using a view
    $body = view('emails.vpn_expired_list', [
        'expiredVpns' => $expiredVpns,
    ])->render();

    // Send email to yourself
    $to = ['jeffrey.salagubang.js@sanden-rs.com']; // must be an array
    $subject = 'VPN Accounts with Expired Passwords';

    try {
        (new RequestMailService())->registration_mail($to, [], [], $subject, $body, []);

        Log::info("VPN expired list email sent successfully. Count: {$expiredVpns->count()}");
    } catch (\Exception $e) {
        Log::error("Failed to email expired VPN list: " . $e->getMessage());
    }
}




    /**
     * Generate random 8-character password with at least 1 uppercase and 1 number
     */
    private function generateRandomPassword($length = 8)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $numbers = '0123456789';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $password = '';
        // Ensure at least 1 number and 1 uppercase
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];

        // Fill the remaining length with random chars
        for ($i = 2; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return str_shuffle($password);
    }
}
