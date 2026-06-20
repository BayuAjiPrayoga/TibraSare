<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $room_id
 * @property string $image_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Room $room
 */
class RoomImage extends Model
{
    protected $fillable = [
        'room_id',
        'image_path',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
