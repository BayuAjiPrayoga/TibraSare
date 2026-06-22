<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DummyData1YearSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            $this->command->warn('Tidak ada kamar. Silakan jalankan seeder utama terlebih dahulu.');
            return;
        }

        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $this->command->info('Membuat 50 tamu dummy...');
        $guests = [];
        for ($i = 0; $i < 50; $i++) {
            $guests[] = Guest::create([
                'full_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'identity_type' => 'KTP',
                'identity_number' => $faker->numerify('3273############'), // 16 digit NIK
            ]);
        }

        $startDate = Carbon::now()->subYear()->startOfMonth();
        $endDate = Carbon::now()->subDays(2); // Hindari tabrakan dengan hari ini/besok

        $currentDate = clone $startDate;

        $this->command->info('Membuat data reservasi dari 1 tahun yang lalu...');

        $count = 0;
        while ($currentDate <= $endDate) {
            // Randomly create 1-4 reservations per day
            $dailyBookings = rand(1, 4);

            for ($i = 0; $i < $dailyBookings; $i++) {
                $room = $rooms->random();
                $guest = $guests[array_rand($guests)];
                
                $checkIn = clone $currentDate;
                $nights = rand(1, 3);
                $checkOut = (clone $checkIn)->addDays($nights);

                $totalPrice = $nights * (float) $room->price;

                $bookingCode = 'RES-' . strtoupper(uniqid());
                
                $createdAt = (clone $checkIn)->subDays(rand(1, 14));

                Reservation::create([
                    'booking_code' => $bookingCode,
                    'guest_id' => $guest->id,
                    'room_id' => $room->id,
                    'created_by' => $adminId,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'checked_in_at' => (clone $checkIn)->addHours(14),
                    'checked_out_at' => (clone $checkOut)->addHours(11),
                    'nights' => $nights,
                    'total_price' => $totalPrice,
                    'status' => ReservationStatus::CheckedOut,
                    'payment_status' => 'PAID',
                    'created_at' => $createdAt,
                    'updated_at' => $checkOut,
                ]);
                
                $count++;
            }

            $currentDate->addDay();
        }

        $this->command->info("Selesai! $count reservasi telah dibuat.");
    }
}
