<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use App\Repositories\OfferRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Make sure the interface binding happens properly
        $this->app->singleton(OfferRepositoryInterface::class, function ($app) {
            return new OfferRepository();
        });
        
        $this->app->singleton(BookingRepositoryInterface::class, function ($app) {
            return new BookingRepository();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
