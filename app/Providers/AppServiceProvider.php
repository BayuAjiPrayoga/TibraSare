<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        \App\Models\Room::observe(\App\Observers\RoomObserver::class);
        \App\Models\Guest::observe(\App\Observers\GuestObserver::class);
        \App\Models\Reservation::observe(\App\Observers\ReservationObserver::class);
    }
}
