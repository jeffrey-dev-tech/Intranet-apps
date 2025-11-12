<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is logged in but hasn't changed password
        if ($user && !$user->password_changed) {
            // Allow only password change route
            if (!$request->is('password/change') && !$request->is('logout')) {
                return redirect()->route('password.change')
                    ->with('info', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
