<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyMailable;
 use App\Services\GmailService;
class MailController extends Controller
{
  

public function sendGmail($id)
{
    $item = \App\Models\Inventory::findOrFail($id);
    $gmail = new GmailService();
    //$gmail->sendEmail('jeffrey.salagubang.js@sanden-rs.com', 'Hello from Laravel', 'This is a test email sent via Gmail API.'.$item['id']);
    return 'Email sent via Gmail API!';
}

}
