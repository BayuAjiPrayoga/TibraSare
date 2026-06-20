<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ActivityLog;
use App\Enums\UserRole;
use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

        // Room Categories
        $standard = RoomCategory::firstOrCreate(['name' => 'Standard'], [
            'description' => 'Kamar standar untuk 2 orang.',
            'base_price' => 250000,
        ]);
        
        $deluxe = RoomCategory::firstOrCreate(['name' => 'Deluxe'], [
            'description' => 'Kamar lebih luas dengan pemandangan.',
            'base_price' => 450000,
        ]);

        // Rooms
        Room::firstOrCreate(['room_number' => '101'], [
            'room_category_id' => $standard->id,
            'price' => 250000,
            'status' => RoomStatus::Available,
        ]);
        $room201 = Room::firstOrCreate(['room_number' => '201'], [
            'room_category_id' => $deluxe->id,
            'price' => 450000,
            'status' => RoomStatus::Occupied,
        ]);

        // Guests
        $guest = Guest::firstOrCreate(['identity_number' => '1234567890123456'], [
            'full_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
        ]);

        // Reservations
        Reservation::firstOrCreate(['booking_code' => 'BK-0001'], [
            'guest_id' => $guest->id,
            'room_id' => $room201->id,
            'created_by' => $receptionist->id,
            'check_in_date' => Carbon::now(),
            'check_out_date' => Carbon::now()->addDays(2),
            'checked_in_at' => Carbon::now(),
            'nights' => 2,
            'total_price' => 900000,
            'status' => ReservationStatus::CheckedIn,
        ]);

        // Activity Log
        ActivityLog::firstOrCreate(['action' => 'Login'], [
            'user_id' => $admin->id,
            'ip_address' => '127.0.0.1',
        ]);
    }
}
