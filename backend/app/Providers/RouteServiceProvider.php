<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    // ...existing code...

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        // ...existing code...

        $this->routes(function () {
            // ...existing code...

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Ajoutez cette ligne pour charger votre fichier auth.php
            Route::middleware('api')
                ->group(base_path('routes/auth.php'));

            // ...existing code...
        });

        // ...existing code...
    }
}