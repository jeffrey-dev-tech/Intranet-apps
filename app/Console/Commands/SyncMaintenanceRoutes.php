<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\MaintenanceMode;

class SyncMaintenanceRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example: php artisan maintenance:sync
     */
    protected $signature = 'maintenance:sync';

    /**
     * The console command description.
     */
    protected $description = 'Sync all named routes into the maintenance_modes table';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $routes = collect(Route::getRoutes())
        ->map(fn($route) => $route->getName())
        ->filter()
        ->unique();

    $this->info("Found {$routes->count()} named routes...");

    // Insert or update existing ones
    foreach ($routes as $routeName) {
        \App\Models\MaintenanceMode::firstOrCreate(
            ['route_name' => $routeName],
            ['enabled' => false]
        );
    }

    // Remove rows for routes that no longer exist
    $deleted = \App\Models\MaintenanceMode::whereNotIn('route_name', $routes)->delete();

    $this->info("Maintenance routes synced successfully ✅");
    if ($deleted > 0) {
        $this->warn("Removed {$deleted} old route(s) that no longer exist.");
    }
}

}
