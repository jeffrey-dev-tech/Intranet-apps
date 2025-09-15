<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

class GmailService
{
    public function sendEmail(
        array $to,
        array $cc = [],
        array $bcc = [],
        string $subject,
        string $htmlBody,
        array $attachments = [] // Optional
    ) {
        // --- Setup Google Client ---
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setScopes(Gmail::GMAIL_SEND);
        $client->setAccessType('offline');

        $tokenPath = storage_path('app/google/token.json');
        if (!file_exists($tokenPath)) {
            throw new \Exception('Token file not found. Authorize this app via CLI first.');
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

        // --- Create Email ---
        $boundary = uniqid('boundary');

        // Headers
        $rawMessage = "From: me\r\n";
        $rawMessage .= "To: " . implode(', ', $to) . "\r\n";
        if (!empty($cc)) $rawMessage .= "Cc: " . implode(', ', $cc) . "\r\n";
        if (!empty($bcc)) $rawMessage .= "Bcc: " . implode(', ', $bcc) . "\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        if (!empty($attachments)) {
    // Multipart email if attachments exist
    $rawMessage .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";

    // Email body
    $rawMessage .= "--$boundary\r\n";
    $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
    $rawMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $rawMessage .= $htmlBody . "\r\n\r\n";

    // Add attachments
    foreach ($attachments as $attachment) {
        $filePath = $attachment['path'];
        $fileName = $attachment['name'];

        if (!file_exists($filePath)) {
            throw new \Exception("Attachment file not found: $filePath");
        }

        $fileData = file_get_contents($filePath);
        $mimeType = mime_content_type($filePath);
        $base64File = rtrim(chunk_split(base64_encode($fileData)));

        $rawMessage .= "--$boundary\r\n";
        $rawMessage .= "Content-Type: $mimeType; name=\"$fileName\"\r\n";
        $rawMessage .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
        $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $rawMessage .= $base64File . "\r\n\r\n";
    }

    $rawMessage .= "--$boundary--";
} else {
    // Simple email (no attachments)
    $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
    $rawMessage .= $htmlBody;
}


        // Encode and send
        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');
        $message = new Message();
        $message->setRaw($encodedMessage);

        return $service->users_messages->send('me', $message);
    }
}
