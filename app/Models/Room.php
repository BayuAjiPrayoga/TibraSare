<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $room_category_id
 * @property string $room_number
 * @property int|null $floor
 * @property string $price
 * @property RoomStatus $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read RoomCategory $category
 * @property-read Collection<int, Facility> $facilities
 * @property-read Collection<int, Reservation> $reservations
 * @property-read Collection<int, RoomImage> $images
 */
class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'room_category_id',
        'room_number',
        'floor',
        'price',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => RoomStatus::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class)->withTimestamps();
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }
}
