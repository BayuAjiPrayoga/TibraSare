<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Mail\CheckOutNotification;
use App\Models\ActivityLog;
use App\Models\Reservation;
use App\Services\WamifyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckOutController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $reservations = Reservation::with(['guest', 'room.category'])
            ->where('status', ReservationStatus::CheckedIn)
            ->whereDate('check_out_date', '<=', $today)
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
                    ],
                ];
            });

        return view('checkout.index', [
            'todayCheckOuts' => $reservations,
        ]);
    }

    public function store(Reservation $reservation)
    {
        if ($reservation->status !== ReservationStatus::CheckedIn) {
            return back()->with('error', 'Reservasi ini tidak dapat di-check-out.');
        }

        $reservation->update([
            'status' => ReservationStatus::CheckedOut,
            'checked_out_at' => now(),
        ]);

        $reservation->room->update([
            'status' => RoomStatus::Available,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Check Out',
            'description' => "Memproses check-out untuk reservasi {$reservation->booking_code}.",
            'ip_address' => request()->ip(),
        ]);

        dispatch(function () use ($reservation) {
            if ($reservation->guest->email) {
                try {
                    Mail::to($reservation->guest->email)->send(new CheckOutNotification($reservation));
                } catch (\Exception $e) {
                    // Ignore email failure
                }
            }

            if ($reservation->guest->phone) {
                try {
                    $message = "Yth. Bpk/Ibu *{$reservation->guest->full_name}*,\n\nTerima kasih telah memilih *Tibra Sare Hotel* sebagai akomodasi Anda.\n\nKami menginformasikan bahwa proses Check-Out Anda (Kode: *{$reservation->booking_code}*) telah selesai. Total keseluruhan tagihan Anda adalah *Rp ".number_format($reservation->total_price, 0, ',', '.')."*.\n\nKami berharap Anda memiliki pengalaman menginap yang menyenangkan dan membawa kenangan indah. Kami selalu menantikan kedatangan Anda kembali di masa mendatang.\n\nHati-hati di jalan, dan semoga hari Anda menyenangkan!\n\nSalam hangat,\n*Manajemen Tibra Sare Hotel*";
                    WamifyService::sendMessage($reservation->guest->phone, $message);
                } catch (\Exception $e) {
                }
            }
        })->afterResponse();

        return back()->with('success', 'Check-out berhasil diproses.');
    }
}
