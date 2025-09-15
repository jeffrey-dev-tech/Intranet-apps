<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GmailService;

class ITRequestMailController extends Controller
{
public function insert_request_mail(array $to, array $cc, array $bcc, string $subject, string $body, array $attachments = [])
{
    $gmailService = new \App\Services\GmailService();
    return $gmailService->sendEmail($to, $cc, $bcc, $subject, $body, $attachments);
}

}
