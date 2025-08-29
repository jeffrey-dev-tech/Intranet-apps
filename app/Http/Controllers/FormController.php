<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function IT_Request_Form()
    {
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
