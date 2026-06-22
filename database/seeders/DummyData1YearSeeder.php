<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyData1YearSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            $this->command->warn('Tidak ada kamar. Silakan jalankan seeder utama terlebih dahulu.');
            return;
        }

        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $this->command->info('Membuat 50 tamu dummy...');
        $guests = [];
        
        $firstNames = ['Budi', 'Siti', 'Agus', 'Ayu', 'Rizky', 'Putri', 'Hendra', 'Dian', 'Tomy', 'Linda', 'Fajar', 'Maya', 'Eko', 'Sari', 'Indra', 'Dewi', 'Andi', 'Nina', 'Gilang', 'Rina'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Sari', 'Setiawan', 'Hidayat', 'Saputra', 'Wahyudi', 'Siregar', 'Lestari', 'Nugroho', 'Wibowo', 'Kurniawan', 'Putra'];

        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName . ' ' . rand(1, 99); // Tambahkan angka untuk mencegah duplikat nama persis
            $email = strtolower($firstName) . '.' . strtolower($lastName) . $i . '@example.com';
            $phone = '08' . rand(1111111111, 9999999999);
            
            // Generate 16 digit NIK manually
            $nik = '3273' . str_pad((string)rand(0, 999999999999), 12, '0', STR_PAD_LEFT);

            $guests[] = Guest::create([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'identity_type' => 'KTP',
                'identity_number' => $nik,
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
