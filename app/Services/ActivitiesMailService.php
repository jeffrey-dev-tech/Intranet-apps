<?php

namespace App\Services;

class ActivitiesMailService
{
    public function registration_mail(
        array $to,
        array $cc,
        array $bcc,
        string $subject,
        string $body,
        array $attachments = []
    ) {
        $gmailService = new GmailService();
        return $gmailService->sendEmail($to, $cc, $bcc, $subject, $body, $attachments);
    }
}
