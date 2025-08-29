<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

class GmailService
{
    public function sendEmail(array $to, array $cc = [], array $bcc = [], string $subject, string $htmlBody)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setScopes(Gmail::GMAIL_SEND);
        $client->setAccessType('offline');

        $tokenPath = storage_path('app/google/token.json');

        if (!file_exists($tokenPath)) {
            throw new \Exception('Token file not found. You need to authorize this app via CLI first.');
        }

        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                throw new \Exception('No refresh token found. Reauthorize the app.');
            }
        }

        $service = new Gmail($client);

        // Build headers
        $rawMessage = "From: me\r\n";
        $rawMessage .= "To: " . implode(', ', $to) . "\r\n";

        if (!empty($cc)) {
            $rawMessage .= "Cc: " . implode(', ', $cc) . "\r\n";
        }

        if (!empty($bcc)) {
            $rawMessage .= "Bcc: " . implode(', ', $bcc) . "\r\n";
        }

        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $rawMessage .= $htmlBody;

        // Encode message for Gmail API
        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');
        $message = new Message();
        $message->setRaw($encodedMessage);

        return $service->users_messages->send('me', $message);
    }
}
