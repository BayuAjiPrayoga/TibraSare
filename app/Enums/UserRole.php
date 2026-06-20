<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Receptionist = 'receptionist';
    case Guest = 'guest';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Receptionist => 'Receptionist',
            self::Guest => 'Tamu',
        };
    }
}
