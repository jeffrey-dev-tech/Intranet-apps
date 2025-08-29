<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ensure user is logged in
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['auth' => 'You must be logged in to change password.']);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update and save
        $user->password = Hash::make($request->password);
        $user->save(); // Eloquent method — works on model instances

        return back()->with('status', 'Password changed successfully!');
    }
}

