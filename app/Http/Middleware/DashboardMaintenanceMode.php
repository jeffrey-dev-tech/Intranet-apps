<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenanceMode;
class DashboardMaintenanceMode
{
    public function handle(Request $request, Closure $next)
{
    $routeName = $request->route()?->getName();

    if ($routeName) {
        $maintenance = MaintenanceMode::where('route_name', $routeName)
            ->where('enabled', true)
            ->exists();

        if ($maintenance) {
            
            // If this specific route is in maintenance, show special view
            if ($routeName === 'activities.log-form') {
               return response()->view('errors.message_closed', [], 503);
            }
            // Developer bypass
            if (Auth::check() && Auth::user()->role === '6') {
                return $next($request);
            }


            // Default maintenance view for all other routes
            return response()->view('errors.dashboard-maintenance', [], 503);
        }
    }

    return $next($request);
}

}
