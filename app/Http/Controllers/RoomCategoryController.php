<?php

namespace App\Http\Controllers;

use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomCategoryController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $query = RoomCategory::withCount('rooms')->latest();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->paginate(12)
            ->withQueryString()
            ->through(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image_path' => $category->image_path ? Storage::url($category->image_path) : null,
                    'base_price' => (int) $category->base_price,
                    'total_rooms' => $category->rooms_count,
                    'facilities' => [],
                ];
            });

        return view('room-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_categories',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('room_categories', 'public');
        }

        RoomCategory::create($validated);

        return back()->with('success', 'Kategori kamar berhasil ditambahkan.');
    }

    public function update(Request $request, RoomCategory $roomCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_categories,name,'.$roomCategory->id,
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($roomCategory->image_path) {
                Storage::disk('public')->delete($roomCategory->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('room_categories', 'public');
        }

        $roomCategory->update($validated);

        return back()->with('success', 'Kategori kamar berhasil diperbarui.');
    }

    public function destroy(RoomCategory $roomCategory)
    {
        // Delete only if no rooms are attached
        if ($roomCategory->rooms()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki kamar terdaftar.');
        }

        if ($roomCategory->image_path) {
            Storage::disk('public')->delete($roomCategory->image_path);
        }

        $roomCategory->delete();

        return back()->with('success', 'Kategori kamar berhasil dihapus.');
    }
}
