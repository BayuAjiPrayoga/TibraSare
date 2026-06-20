<?php

namespace App\Observers;

use App\Models\Room;
use App\Models\ActivityLog;

class RoomObserver
{
    public function created(Room $room): void
    {
        ActivityLog::log('Create Room', "Membuat kamar baru {$room->room_number}", $room->toArray());
    }

    public function updated(Room $room): void
    {
        $changes = $room->getChanges();
        if (empty($changes)) return;
        ActivityLog::log('Update Room', "Mengubah data kamar {$room->room_number}", $changes);
    }

    public function deleted(Room $room): void
    {
        ActivityLog::log('Delete Room', "Menghapus kamar {$room->room_number}", $room->toArray());
    }

    public function restored(Room $room): void
    {
        ActivityLog::log('Restore Room', "Memulihkan kamar {$room->room_number}", $room->toArray());
    }

    public function forceDeleted(Room $room): void
    {
        ActivityLog::log('Force Delete Room', "Menghapus permanen kamar {$room->room_number}", $room->toArray());
    }
}
