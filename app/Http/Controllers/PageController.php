<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryEdms;

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
        public function dropball()
    {
        return view('dropball.dropball');
    }


    // app/Http/Controllers/PageController.php

public function login()
{
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login'); // or whatever your login blade is
}

public function getCategoryData()
{
    $categories = CategoryEdms::all();

    // You can return the entire collection directly
    return $categories;
}



public function policies(Request $request)
{
    $categories = $this->getCategoryData();
    $department = $request->query('department');

    return view('pages.policies', [
        'department' => $department,
        'categories' => $categories
    ]);
}
}
