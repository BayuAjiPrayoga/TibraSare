<?php

namespace App\Observers;

use App\Models\Guest;
use App\Models\ActivityLog;

class GuestObserver
{
    public function created(Guest $guest): void
    {
        ActivityLog::log('Create Guest', "Mendaftarkan tamu baru {$guest->full_name}", $guest->toArray());
    }

    public function updated(Guest $guest): void
    {
        $changes = $guest->getChanges();
        if (empty($changes)) return;
        ActivityLog::log('Update Guest', "Mengubah data tamu {$guest->full_name}", $changes);
    }

    public function deleted(Guest $guest): void
    {
        ActivityLog::log('Delete Guest', "Menghapus data tamu {$guest->full_name}", $guest->toArray());
    }
}
