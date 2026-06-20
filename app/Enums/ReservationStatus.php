<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Reserved = 'reserved';
    case CheckedIn = 'checked_in';
    case CheckedOut = 'checked_out';
    case Cancelled = 'cancelled';
}
