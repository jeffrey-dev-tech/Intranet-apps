<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // ✅ Import Log
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Gmail;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            $client = new GoogleClient();
            $client->setAuthConfig(storage_path('app/google/credentials.json'));
            $client->addScope([
                Gmail::GMAIL_SEND,
                \Google\Service\Drive::DRIVE, // Add Drive scope if needed
            ]);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $client->setRedirectUri(route('google.callback'));

            $authUrl = $client->createAuthUrl();

            Log::info('Redirecting to Google OAuth', ['auth_url' => $authUrl]);

            return redirect()->away($authUrl);
        } catch (\Exception $e) {
            Log::error('Error in redirectToGoogle', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            if (!$request->has('code')) {
                Log::warning('Google callback hit without code', ['request' => $request->all()]);
                return response()->json(['error' => 'Authorization code not found'], 400);
            }

            Log::info('Google callback received', ['code' => $request->get('code')]);

            $client = new GoogleClient();
            $client->setAuthConfig(storage_path('app/google/credentials.json'));
            $client->setRedirectUri(route('google.callback'));

            $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            if (isset($accessToken['error'])) {
                Log::error('Error fetching access token', ['error' => $accessToken['error']]);
                return response()->json(['error' => $accessToken['error']], 400);
            }

            Storage::put('google/token.json', json_encode($accessToken));

            Log::info('Access token saved successfully', ['token' => $accessToken]);

            return response()->json(['message' => '✅ Gmail token saved successfully!']);
        } catch (\Exception $e) {
            Log::error('Exception in handleGoogleCallback', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
