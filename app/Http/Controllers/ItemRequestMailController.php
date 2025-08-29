<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GmailService;

class ItemRequestMailController extends Controller
{
    public function insert_request_mail(array $to, array $cc = [], array $bcc = [], string $title, string $body)
    {
        $gmail = new GmailService();
        
        // Call the GmailService function with arrays
        return $gmail->sendEmail($to, $cc, $bcc, $title, $body);
    }
}
