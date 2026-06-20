<?php

namespace App\Http\Controllers;

use App\Enums\RoomStatus;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class GuestRoomController extends Controller
{
    /**
     * Display a listing of the room categories.
     */
    public function index()
    {
        $roomCategories = RoomCategory::withCount(['rooms as available_rooms_count' => function ($query) {
            $query->where('status', RoomStatus::Available);
        }])->get();

        return view('guest.room-list', compact('roomCategories'));
    }

    /**
     * Display the specified room category to the guest.
     */
    public function show(RoomCategory $roomCategory)
    {
        // Ambil fasilitas dari kamar pertama di kategori ini
        $facilities = $roomCategory->rooms()->with('facilities')->first()?->facilities ?? collect();
        
        // Ambil semua galeri foto dari semua kamar di kategori ini
        $images = $roomCategory->rooms()->with('images')->get()->pluck('images')->flatten();

        // Ambil rekomendasi kamar lain
        $otherCategories = RoomCategory::where('id', '!=', $roomCategory->id)
            ->withCount(['rooms as available_rooms_count' => function ($query) {
                $query->where('status', \App\Enums\RoomStatus::Available);
            }])
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('guest.room-detail', compact('roomCategory', 'facilities', 'images', 'otherCategories'));
    }
}
