<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    
   public function processLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check for suspension
        if ($user->suspended_until && now()->lessThan($user->suspended_until)) {
            Auth::logout();

            // Optional: return a view instead of redirect
          return response()->view('auth.suspended', [
    'until' => $user->suspended_until
]);

        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->with('error', 'Invalid credentials');
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login'); // or use `view('auth.login')` if needed
}

public function changePassword(Request $request)
{
    try {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'You must be logged in.']);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.']);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Return validation errors
        return response()->json([
            'success' => false,
            'message' => $e->validator->errors()->first()
        ], 422);
    } catch (\Throwable $e) {
        Log::error('Password change failed: '.$e->getMessage(), [
            'user_id' => Auth::id(),
            'stack' => $e->getTraceAsString()
        ]);

        return response()->json(['success' => false, 'message' => 'An error occurred while changing the password.'], 500);
    }
}


}
