<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
     public function dashboard()
    {
        return view('pages.dashboard');
    }

        public function computer_inventory()
    {
        return view('pages.computer_inventory');
    }
          public function scanner()
    {
        return view('QR.scanner');
    }

    public function docs_version()
    {
        return view('pages.version_control');
    }

    // app/Http/Controllers/PageController.php

public function login()
{
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login'); // or whatever your login blade is
}

    public function policies(Request $request)
    {
    $department = $request->query('department'); // or request('department')

    // Example: if you want to load a model
    // $dept = Department::where('slug', $department)->firstOrFail();

    return view('pages.policies', ['department' => $department]);
    }
}
