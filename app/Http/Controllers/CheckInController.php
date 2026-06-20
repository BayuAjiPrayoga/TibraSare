<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        
        $reservations = Reservation::with(['guest', 'room.category'])
            ->whereIn('status', [ReservationStatus::Reserved])
            ->whereDate('check_in_date', '<=', $today)
            ->get()
            ->map(function ($res) {
                return [
                    'id' => $res->id,
                    'booking_code' => $res->booking_code,
                    'status' => $res->status->value,
                    'check_in_date' => $res->check_in_date->format('Y-m-d'),
                    'check_out_date' => $res->check_out_date->format('Y-m-d'),
                    'total_price' => (int) $res->total_price,
                    'guest' => [
                        'full_name' => $res->guest->full_name,
                    ],
                    'room' => [
                        'room_number' => $res->room->room_number,
                        'category' => [
                            'name' => $res->room->category->name,
                        ],
                    ]
                ];
            });

        return view('checkin.index', [
            'todayReservations' => $reservations,
        ]);
    }

    public function store(Reservation $reservation)
    {
        if ($reservation->status !== ReservationStatus::Reserved) {
            return back()->with('error', 'Reservasi ini tidak dapat di-check-in.');
        }

        $reservation->update([
            'status' => ReservationStatus::CheckedIn,
            'checked_in_at' => now(),
        ]);

        $reservation->room->update([
            'status' => \App\Enums\RoomStatus::Occupied,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Check In',
            'description' => "Memproses check-in untuk reservasi {$reservation->booking_code}.",
            'ip_address' => request()->ip(),
        ]);

        $hasWifi = $reservation->room->facilities()->where('name', 'like', '%Wifi%')->exists();
        $wifiSsid = env('WIFI_SSID', 'Tibra Sare Guest');
        $wifiPass = env('WIFI_PASSWORD', 'Tibrasare123');

        dispatch(function () use ($reservation, $hasWifi, $wifiSsid, $wifiPass) {
            if ($reservation->guest->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($reservation->guest->email)->send(new \App\Mail\CheckInNotification($reservation, $hasWifi, $wifiSsid, $wifiPass));
                } catch (\Exception $e) {
                    // Ignore email failure
                }
            }

            if ($reservation->guest->phone) {
                try {
                    $wifiInfoWa = $hasWifi ? "\n\nSebagai informasi tambahan, kamar Anda dilengkapi dengan fasilitas Wi-Fi. Berikut detail aksesnya:\n*Nama Wi-Fi (SSID)*: {$wifiSsid}\n*Kata Sandi*: {$wifiPass}" : "";
                    $message = "Yth. Bpk/Ibu *{$reservation->guest->full_name}*,\n\nSelamat datang di *Tibra Sare Hotel*.\n\nKami menginformasikan bahwa proses Check-In Anda dengan Kode Reservasi *{$reservation->booking_code}* telah berhasil dilakukan. Anda menempati kamar *{$reservation->room->room_number}*.\n\nFasilitas kami sepenuhnya tersedia untuk Anda.{$wifiInfoWa}\n\nApabila Anda membutuhkan bantuan atau layanan tambahan selama menginap, staf kami siap membantu Anda 24 jam.\n\nSelamat beristirahat dan nikmati pengalaman menginap Anda bersama kami.\n\nSalam hangat,\n*Manajemen Tibra Sare Hotel*";
                    \App\Services\WamifyService::sendMessage($reservation->guest->phone, $message);
                } catch (\Exception $e) {}
            }
        })->afterResponse();

        return back()->with('success', 'Check-in berhasil diproses.');
    }
}
