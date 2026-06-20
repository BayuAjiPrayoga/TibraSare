<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settingsDb = Setting::pluck('value', 'key')->toArray();

        $settings = [
            'hotel_name' => $settingsDb['hotel_name'] ?? 'Tibra Sare Hotel',
            'address' => $settingsDb['address'] ?? 'Jl. Merdeka No. 123, Jakarta',
            'phone' => $settingsDb['phone'] ?? '+62 812-3456-7890',
            'email' => $settingsDb['email'] ?? 'contact@tibrasare.com',
            'check_in_time' => $settingsDb['check_in_time'] ?? '14:00',
            'check_out_time' => $settingsDb['check_out_time'] ?? '12:00',
        ];

        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'check_in_time' => 'required|string|max:10',
            'check_out_time' => 'required|string|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
