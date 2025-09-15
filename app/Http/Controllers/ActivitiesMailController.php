<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivitiesMailController extends Controller
{
    public function registration_mail(array $to, array $cc, array $bcc, string $subject, string $body, array $attachments = [])
{
    $gmailService = new \App\Services\GmailService();
    return $gmailService->sendEmail($to, $cc, $bcc, $subject, $body, $attachments);
}

}
