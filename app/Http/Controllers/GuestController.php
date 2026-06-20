<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Guest;

class GuestController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $query = Guest::withCount('reservations')
            ->with(['reservations' => function($q) {
                $q->latest('check_in_date')->limit(1);
            }]);

        if ($search) {
            $query->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $guests = $query->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(function ($guest) {
                $lastStay = $guest->reservations->first();
                $status = $lastStay && in_array($lastStay->status->value, ['checked_in', 'in_house']) ? 'in_house' : 'regular';

                return [
                    'id' => $guest->id,
                    'full_name' => $guest->full_name,
                    'email' => $guest->email,
                    'phone' => $guest->phone,
                    'identity_number' => $guest->identity_number,
                    'identity_type' => $guest->identity_type,
                    'status' => $status,
                    'last_stay' => $lastStay ? $lastStay->check_in_date->format('Y-m-d') : null,
                    'total_visits' => $guest->reservations_count,
                ];
            });

        return view('guests.index', [
            'guests' => $guests,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests',
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:50|unique:guests',
            'identity_type' => 'required|string|in:KTP,Passport,SIM',
        ]);

        Guest::create($validated);

        return back()->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests,email,' . $guest->id,
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:50|unique:guests,identity_number,' . $guest->id,
            'identity_type' => 'required|string|in:KTP,Passport,SIM',
        ]);

        $guest->update($validated);

        return back()->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $guest)
    {
        // Prevent deleting if guest has reservations
        if ($guest->reservations()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus tamu yang memiliki riwayat reservasi.');
        }

        $guest->delete();

        return back()->with('success', 'Data tamu berhasil dihapus.');
    }
}
