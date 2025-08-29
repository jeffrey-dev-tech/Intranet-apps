<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Models\Feature;
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Register your model policies here if any
    ];

   public function boot(): void
{
    $this->registerPolicies();

    if (Schema::hasTable('features')) { // Prevent errors during migrations
        Feature::all()->each(function ($feature) {
            Gate::define($feature->name, function (User $user) use ($feature) {
                return $user->features()->where('features.id', $feature->id)->exists();
            });
        });
    }
}

}
