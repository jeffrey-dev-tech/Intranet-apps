<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubsystemController extends Controller
{
    public function index(Request $request, $type)
    {
        // Save which subsystem user picked
        session(['subsystem' => $type]);

        // Redirect to the main dashboard (or any common view)
        return redirect()->route('dashboard');
    }
}
