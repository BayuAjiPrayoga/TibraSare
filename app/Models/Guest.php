<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $identity_number
 * @property string|null $identity_type
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Reservation> $reservations
 * @property-read int|null $reservations_count
 */
class Guest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'identity_number',
        'full_name',
        'phone',
        'email',
        'address',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
