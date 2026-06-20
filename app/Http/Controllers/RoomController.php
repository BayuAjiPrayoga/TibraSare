<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\Facility;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        
        $roomsQuery = Room::with('category')->latest();
        
        if ($search) {
            $roomsQuery->where('room_number', 'like', "%{$search}%");
        }

        $rooms = $roomsQuery->paginate(12)->withQueryString()->through(function ($room) {
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'status' => $room->status->value,
                'price' => (int) $room->price,
                'category_id' => $room->category_id,
                'category' => [
                    'name' => $room->category->name ?? '-',
                ],
                'facilities' => $room->facilities->pluck('id')->toArray(),
                'images' => $room->images->map(fn($img) => ['id' => $img->id, 'url' => asset('storage/' . $img->image_path)])->toArray(),
            ];
        });

        $categories = RoomCategory::select('id', 'name')->get();
        $facilities = Facility::select('id', 'name')->get();

        return view('rooms.index', [
            'rooms' => $rooms,
            'categories' => $categories,
            'facilities' => $facilities,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms',
            'room_category_id' => 'required|exists:room_categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $room = Room::create($validated);

        if (!empty($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('room_images', 'public');
                $room->images()->create(['image_path' => $path]);
            }
        }

        return back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'room_category_id' => 'required|exists:room_categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $room->update($validated);

        if (isset($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        } else {
            $room->facilities()->sync([]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('room_images', 'public');
                $room->images()->create(['image_path' => $path]);
            }
        }

        return back()->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $room->delete();

        return back()->with('success', 'Kamar berhasil dihapus.');
    }

    public function destroyImage(RoomImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Foto kamar berhasil dihapus.');
    }
}
