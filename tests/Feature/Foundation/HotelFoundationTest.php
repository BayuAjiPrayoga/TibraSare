<?php

namespace Tests\Feature\Foundation;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\ActivityLog;
use App\Models\Facility;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_foundation_models_can_be_persisted_with_expected_relationships(): void
    {
        $user = User::factory()->create();

        $category = RoomCategory::create([
            'name' => 'Deluxe',
            'description' => 'Kamar deluxe',
            'base_price' => 450000,
        ]);

        $room = Room::create([
            'room_category_id' => $category->id,
            'room_number' => '201',
            'floor' => 2,
            'price' => 500000,
            'status' => RoomStatus::Available,
            'description' => 'Kamar lantai dua',
        ]);

        $facility = Facility::create([
            'name' => 'Wi-Fi',
            'description' => 'Akses internet',
        ]);

        $room->facilities()->attach($facility);

        $guest = Guest::create([
            'identity_number' => '3174010101900001',
            'full_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'address' => 'Jakarta',
        ]);

        $reservation = Reservation::create([
            'booking_code' => 'TS-20260617-0001',
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'created_by' => $user->id,
            'check_in_date' => '2026-06-20',
            'check_out_date' => '2026-06-22',
            'nights' => 2,
            'total_price' => 1000000,
            'status' => ReservationStatus::Reserved,
            'notes' => 'Late check-in',
        ]);

        $activityLog = ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'reservation.created',
            'description' => 'Reservasi dibuat',
            'properties' => ['booking_code' => $reservation->booking_code],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $this->assertTrue($category->rooms->contains($room));
        $this->assertTrue($room->facilities->contains($facility));
        $this->assertTrue($facility->rooms->contains($room));
        $this->assertTrue($guest->reservations->contains($reservation));
        $this->assertTrue($room->reservations->contains($reservation));
        $this->assertTrue($user->reservations->contains($reservation));
        $this->assertTrue($user->activityLogs->contains($activityLog));

        $this->assertSame(RoomStatus::Available, $room->fresh()->status);
        $this->assertSame(ReservationStatus::Reserved, $reservation->fresh()->status);
        $this->assertSame('TS-20260617-0001', $activityLog->fresh()->properties['booking_code']);
    }

    public function test_soft_deletes_are_enabled_for_rooms_guests_and_reservations(): void
    {
        $user = User::factory()->create();
        $category = RoomCategory::create(['name' => 'Standard', 'base_price' => 250000]);
        $room = Room::create([
            'room_category_id' => $category->id,
            'room_number' => '101',
            'price' => 250000,
            'status' => RoomStatus::Available,
        ]);
        $guest = Guest::create([
            'identity_number' => '3174010101900002',
            'full_name' => 'Siti Aminah',
        ]);
        $reservation = Reservation::create([
            'booking_code' => 'TS-20260617-0002',
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'created_by' => $user->id,
            'check_in_date' => '2026-06-21',
            'check_out_date' => '2026-06-22',
            'nights' => 1,
            'total_price' => 250000,
            'status' => ReservationStatus::Reserved,
        ]);

        $room->delete();
        $guest->delete();
        $reservation->delete();

        $this->assertSoftDeleted($room);
        $this->assertSoftDeleted($guest);
        $this->assertSoftDeleted($reservation);
    }
}
