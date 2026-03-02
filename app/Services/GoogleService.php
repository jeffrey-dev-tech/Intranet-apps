<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->addScope([
            Gmail::GMAIL_SEND,
            Drive::DRIVE,
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $tokenPath = storage_path('app/google/token.json');

        if (!file_exists($tokenPath)) {
            throw new \Exception('Token file not found. Run OAuth authorization first.');
        }

        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $this->client->setAccessToken($accessToken);

        // Refresh token automatically
        if ($this->client->isAccessTokenExpired() && $this->client->getRefreshToken()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
        }
    }

    /**
     * Get authenticated Drive service
     */

    /**
     * Upload a single file to Google Drive folder
     */
    public function uploadToDrive(string $filePath, string $fileName, string $mimeType, string $folderId = null)
    {
        $service = $this->getDriveService();

        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : []
        ]);

        $content = file_get_contents($filePath);

        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart'
        ]);

        return $file->id;
    }



protected function getDriveService(): Drive
{
    return new Drive($this->client);
}

public function downloadFile(string $fileId, string $fileName)
{
    $service = $this->getDriveService();

    // Get access token
    $accessToken = $this->client->getAccessToken()['access_token'];

    // Direct Google Drive download URL
    $url = "https://www.googleapis.com/drive/v3/files/$fileId?alt=media";

    $client = new \GuzzleHttp\Client();

    return response()->streamDownload(function () use ($client, $url, $accessToken) {
        $res = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'stream' => true,
        ]);

        // Stream chunks to browser
        $body = $res->getBody();
        while (!$body->eof()) {
            echo $body->read(1024 * 8); // 8 KB chunks
        }
    }, $fileName);
}

    /**
     * List all files in a folder
     */
    public function listFilesInFolder(string $folderId): array
    {
        $service = $this->getDriveService();
        $allFiles = [];
        $pageToken = null;

        do {
            $response = $service->files->listFiles([
                'q' => "'$folderId' in parents and trashed = false",
                'fields' => 'nextPageToken, files(id, name, mimeType, size, createdTime)',
                'pageToken' => $pageToken,
            ]);

            foreach ($response->files as $file) {
                $allFiles[] = [
                    'id' => $file->id,
                    'name' => $file->name,
                    'mimeType' => $file->mimeType,
                    'size' => $file->size ?? 0,
                    'createdTime' => $file->createdTime,
                ];
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken != null);

        return $allFiles;
    }

    /**
     * Send Gmail email
     */
/**
 * Get current Month-Year folder inside a parent folder. Create it if not exists.
 */
public function getOrCreateMonthFolder(string $parentFolderId, ?string $folderName = null): string
{
    $driveService = $this->getDriveService();

    // Default to current month if no folder name provided
    $folderName = $folderName ?? \Carbon\Carbon::now()->format('F-Y');

    // Check if folder exists
    $existingFolder = $driveService->files->listFiles([
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$folderName' and '$parentFolderId' in parents and trashed=false",
        'fields' => 'files(id, name)',
    ]);

    if (count($existingFolder->getFiles()) > 0) {
        return $existingFolder->getFiles()[0]->getId();
    }

    // Create folder if not exists
    $folderMetadata = new DriveFile([
        'name' => $folderName,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => [$parentFolderId],
    ]);

    $folder = $driveService->files->create($folderMetadata, ['fields' => 'id']);
    return $folder->id;
}


/**
 * List files in the Month-Year folder under parent folder
 */
public function listFilesInMonthFolder(string $parentFolderId): array
{
    $monthFolderId = $this->getOrCreateMonthFolder($parentFolderId);
    return $this->listFilesInFolder($monthFolderId);
}

    public function sendEmail(array $to, array $cc, array $bcc, string $subject, string $htmlBody, array $attachments = []): Message
    {
        $service = new Gmail($this->client);

        $boundary = uniqid('boundary');

        $rawMessage = "From: me\r\n";
        $rawMessage .= "To: " . implode(', ', $to) . "\r\n";
        if (!empty($cc)) $rawMessage .= "Cc: " . implode(', ', $cc) . "\r\n";
        if (!empty($bcc)) $rawMessage .= "Bcc: " . implode(', ', $bcc) . "\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";

        if (!empty($attachments)) {
            $rawMessage .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
            $rawMessage .= "--$boundary\r\n";
            $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
            $rawMessage .= $htmlBody . "\r\n\r\n";

            foreach ($attachments as $attachment) {
                $fileData = file_get_contents($attachment['path']);
                $mimeType = mime_content_type($attachment['path']);
                $base64File = chunk_split(base64_encode($fileData));

                $rawMessage .= "--$boundary\r\n";
                $rawMessage .= "Content-Type: $mimeType; name=\"{$attachment['name']}\"\r\n";
                $rawMessage .= "Content-Disposition: attachment; filename=\"{$attachment['name']}\"\r\n";
                $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $rawMessage .= $base64File . "\r\n";
            }

            $rawMessage .= "--$boundary--";
        } else {
            $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
            $rawMessage .= $htmlBody;
        }

        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');
        $message = new Message();
        $message->setRaw($encodedMessage);

        return $service->users_messages->send('me', $message);
    }

    public function uploadPdfToMonthFolder(string $pdfContent, string $filename, string $parentFolderId)
{
    $date = \Carbon\Carbon::now();
    $folderName = $date->format('F-Y');

    $driveService = $this->getDriveService();

    // Check if Month-Year folder exists
    $existingFolder = $driveService->files->listFiles([
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$folderName' and '$parentFolderId' in parents and trashed=false",
        'fields' => 'files(id, name)',
    ]);

    if (count($existingFolder->getFiles()) > 0) {
        $folderId = $existingFolder->getFiles()[0]->getId();
    } else {
        $folderMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId],
        ]);

        $folder = $driveService->files->create($folderMetadata, ['fields' => 'id']);
        $folderId = $folder->id;
    }

    // Upload PDF
    $fileMetadata = new DriveFile([
        'name' => $filename,
        'parents' => [$folderId],
    ]);

    $file = $driveService->files->create($fileMetadata, [
        'data' => $pdfContent,
        'mimeType' => 'application/pdf',
        'uploadType' => 'multipart',
        'fields' => 'id',
    ]);

    return $file->id;
}
}
