<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        $maintenanceRoutes = [
            'computer.inventory',
            
            // Add other route names here
        ];

        $routeName = $request->route() ? $request->route()->getName() : null;

        // Check if partial maintenance mode is ON and the route is in maintenance list
        if (
            env('PARTIAL_MAINTENANCE', false) &&
            $routeName &&
            in_array($routeName, $maintenanceRoutes)
        ) {
            // Allow developer role to bypass maintenance
            if (Auth::check() && Auth::user()->role === 'developer') {
                return $next($request);
            }

            return response()->view('errors.dashboard-maintenance', [], 503);
        }

        return $next($request);
    }
}
