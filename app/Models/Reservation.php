<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $booking_code
 * @property int $guest_id
 * @property int $room_id
 * @property int|null $created_by
 * @property Carbon $check_in_date
 * @property Carbon $check_out_date
 * @property Carbon|null $checked_in_at
 * @property Carbon|null $checked_out_at
 * @property int $nights
 * @property string $total_price
 * @property ReservationStatus $status
 * @property string|null $payment_url
 * @property string|null $payment_status
 * @property string|null $notes
 * @property string|null $qr_code_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Guest $guest
 * @property-read Room $room
 * @property-read User|null $creator
 */
class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'guest_id',
        'room_id',
        'created_by',
        'check_in_date',
        'check_out_date',
        'checked_in_at',
        'checked_out_at',
        'nights',
        'total_price',
        'status',
        'payment_url',
        'payment_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'nights' => 'integer',
            'total_price' => 'decimal:2',
            'status' => ReservationStatus::class,
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
