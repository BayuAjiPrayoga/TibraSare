<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $roomCategories = \App\Models\RoomCategory::withCount(['rooms as available_rooms_count' => function ($query) {
            $query->where('status', \App\Enums\RoomStatus::Available);
        }])
        ->get();

    return view('public.landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'roomCategories' => $roomCategories,
    ]);
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PaymentCallbackController;

Route::post('/api/payment/xendit-callback', [PaymentCallbackController::class, 'handleXendit'])->name('payment.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    // Receptionist & Admin routes (General Operations)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('guests', App\Http\Controllers\GuestController::class)->except(['create', 'show', 'edit']);
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('/check-in/{reservation}', [CheckInController::class, 'store'])->name('check-in.store');
    Route::get('/check-out', [CheckOutController::class, 'index'])->name('check-out.index');
    Route::post('/check-out/{reservation}', [CheckOutController::class, 'store'])->name('check-out.store');

    // Guest Booking routes
    Route::get('/book/{category}', [\App\Http\Controllers\BookingController::class, 'create'])->name('book.create');
    Route::post('/book/{category}', [\App\Http\Controllers\BookingController::class, 'store'])->name('book.store');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('rooms', App\Http\Controllers\RoomController::class)->except(['create', 'show', 'edit']);
        Route::delete('rooms/images/{image}', [App\Http\Controllers\RoomController::class, 'destroyImage'])->name('rooms.images.destroy');
        Route::resource('room-categories', App\Http\Controllers\RoomCategoryController::class)->except(['create', 'show', 'edit'])->parameters(['room-categories' => 'roomCategory']);
        Route::resource('users', App\Http\Controllers\UserController::class)->except(['create', 'show', 'edit']);
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::resource('facilities', App\Http\Controllers\FacilityController::class)->except(['create', 'show', 'edit']);
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\SettingController::class, 'store'])->name('settings.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
