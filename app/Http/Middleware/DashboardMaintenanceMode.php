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
        $route = $request->route();

        if (!$route) {
            return $next($request);
        }

        $routeName = $route->getName();

        if (!$routeName) {
            return $next($request);
        }

        // Check if this route is in maintenance
        $maintenance = MaintenanceMode::where('route_name', $routeName)
            ->where('enabled', true)
            ->exists();

        if (!$maintenance) {
            return $next($request);
        }

        // Allow developer bypass (role 6)
        if (Auth::check() && Auth::user()->role == 6) {
            return $next($request);
        }

    $allowedRoutesForUser34 = [
    'document.render',
    'categorytbl',
];

if (in_array($routeName, $allowedRoutesForUser34) && Auth::id() == 34) {
    return $next($request);
}

        // Custom maintenance view for activities.log-form
        if ($routeName === 'activities.log-form') {
            return response()->view('errors.dashboard-maintenance', [], 503);
        }

        // Default maintenance view
        return response()->view('errors.dashboard-maintenance', [], 503);
    }
}
