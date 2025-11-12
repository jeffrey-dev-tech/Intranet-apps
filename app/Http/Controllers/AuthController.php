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
        'email' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // 🔒 Check for suspension
       if ($user->status === 'Inactive') {
    Auth::logout();
    return response()->view('auth.suspended');
}

        // 🔐 Regenerate session for security
        $request->session()->regenerate();

        // 🪵 Log successful login
        Log::info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'time' => now()->toDateTimeString(),
        ]);

        // ✅ If the user hasn't changed password yet, redirect to change-password
        if (!$user->password_changed) {
            return redirect()->route('password.change')
                ->with('info', 'Please change your password before continuing.');
        }

        // ✅ Otherwise, go to intended dashboard
        return redirect()->intended('/dashboard');
    }

    // 🚫 Log failed login attempt
    Log::warning('Failed login attempt', [
        'email' => $request->email,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'time' => now()->toDateTimeString(),
    ]);

    // ⚠️ Show error message
    return back()->with('error', 'Invalid credentials');
}




  public function showChangeForm()
    {
        return view('auth.changePass');
    }

   
public function update(Request $request)
{
    try {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in first.'
            ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->password_changed = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Handle other unexpected errors
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(), // 🔥 you can pass this to SweetAlert
        ], 500);
    }
}




public function logout(Request $request)
{
    $start = microtime(true);

    // If user is authenticated
    if (Auth::check()) {
        $user = Auth::user(); // ✅ capture user before logout

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out successfully.', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    } else {
        Log::info('Logout attempted but user was already logged out or session expired.');
    }

    $end = microtime(true);

    Log::info('Logout time', [
        'duration_seconds' => $end - $start,
        'user_id' => isset($user) ? $user->id : null,
        'email' => isset($user) ? $user->email : null,
    ]);

    return redirect()->route('login');
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
