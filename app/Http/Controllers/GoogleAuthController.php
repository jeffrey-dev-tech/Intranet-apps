<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Gmail;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Gmail::GMAIL_SEND); // Or GMAIL_READONLY etc.
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(route('google.callback'));

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setRedirectUri(route('google.callback'));

        $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if (isset($accessToken['error'])) {
            return response()->json(['error' => $accessToken['error']], 400);
        }

        Storage::put('google/token.json', json_encode($accessToken));

        return response()->json(['message' => '✅ Gmail token saved successfully!']);
    }
}
