<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function IT_Request_Form()
{
    // Get user info if logged in
    $user = Auth::check() ? Auth::user()->name : 'Guest';
    

    $time = now();

    // Log the visit
    Log::info("IT Request Form visited by {$user} at {$time}");

    // Continue to the view
    return view('forms.IT_Request_Form');
}
     public function Shuttle_Form()
    {
        return view('forms.Shuttle_Form');
    }
     public function Deposit_Form()
    {
        return view('forms.Deposit_Form');
    }

     public function LunchPass_Form()
    {
        return view('forms.LunchPass');
    }
}
