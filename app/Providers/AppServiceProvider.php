<?php

namespace App\Providers;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Observers\GuestObserver;
use App\Observers\ReservationObserver;
use App\Observers\RoomObserver;
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

        Room::observe(RoomObserver::class);
        Guest::observe(GuestObserver::class);
        Reservation::observe(ReservationObserver::class);
    }
}
