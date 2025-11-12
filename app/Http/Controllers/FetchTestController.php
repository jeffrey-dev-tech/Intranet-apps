<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamUser;
class FetchTestController extends Controller
{
    public function fetch()
    {
    $results = User::with('teams')
    ->whereHas('teams')
    ->get();
    return view('test', compact('results'));
    }
}
