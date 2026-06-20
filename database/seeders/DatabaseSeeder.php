<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::updateOrCreate(['email' => 'admin@tibrasare.test'], [
            'name' => 'Admin Tibra Sare',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $receptionist = User::updateOrCreate(['email' => 'receptionist@tibrasare.test'], [
            'name' => 'Resepsionis 1',
            'password' => Hash::make('password'),
            'role' => UserRole::Receptionist,
            'email_verified_at' => now(),
        ]);

        // Activity Log
        ActivityLog::firstOrCreate(['action' => 'Login'], [
            'user_id' => $admin->id,
            'ip_address' => '127.0.0.1',
        ]);

        // Panggil seeder spesifik Tibra Sare (Sunda)
        $this->call(SundaTibraSareSeeder::class);
    }
}
