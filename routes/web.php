<?php

use App\Enums\RoomStatus;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\RoomCategory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $roomCategories = RoomCategory::withCount(['rooms as available_rooms_count' => function ($query) {
        $query->where('status', RoomStatus::Available);
    }])
        ->get();

    return view('public.landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'roomCategories' => $roomCategories,
    ]);
});

Route::post('/api/payment/xendit-callback', [PaymentCallbackController::class, 'handleXendit'])->name('payment.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    // Receptionist & Admin routes (General Operations)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('guests', GuestController::class)->except(['create', 'show', 'edit']);
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('/check-in/{reservation}', [CheckInController::class, 'store'])->name('check-in.store');
    Route::get('/check-out', [CheckOutController::class, 'index'])->name('check-out.index');
    Route::post('/check-out/{reservation}', [CheckOutController::class, 'store'])->name('check-out.store');

    // Guest Booking routes
    Route::get('/book/{category}', [BookingController::class, 'create'])->name('book.create');
    Route::post('/book/{category}', [BookingController::class, 'store'])->name('book.store');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/guest-rooms', [\App\Http\Controllers\GuestRoomController::class, 'index'])->name('guest.rooms.index');
    Route::get('/guest-rooms/{roomCategory}', [\App\Http\Controllers\GuestRoomController::class, 'show'])->name('guest.rooms.show');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('rooms', RoomController::class)->except(['create', 'show', 'edit']);
        Route::delete('rooms/images/{image}', [RoomController::class, 'destroyImage'])->name('rooms.images.destroy');
        Route::resource('room-categories', RoomCategoryController::class)->except(['create', 'show', 'edit'])->parameters(['room-categories' => 'roomCategory']);
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::resource('facilities', FacilityController::class)->except(['create', 'show', 'edit']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
