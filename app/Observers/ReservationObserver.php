<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\ActivityLog;

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        ActivityLog::log('Create Reservation', "Membuat reservasi {$reservation->booking_code}", $reservation->toArray());
    }

    public function updated(Reservation $reservation): void
    {
        $changes = $reservation->getChanges();
        if (empty($changes)) return;
        ActivityLog::log('Update Reservation', "Mengubah status/data reservasi {$reservation->booking_code}", $changes);
    }

    public function deleted(Reservation $reservation): void
    {
        ActivityLog::log('Delete Reservation', "Membatalkan/Menghapus reservasi {$reservation->booking_code}", $reservation->toArray());
    }
}
