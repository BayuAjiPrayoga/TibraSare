<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use App\Models\ActivityLog;
use Carbon\Carbon;
use App\Services\XenditService;

class BookingController extends Controller
{
    public function create(RoomCategory $category): View
    {
        // For simple MVP, we just verify the category has available rooms right now.
        $availableRoomsCount = $category->rooms()->where('status', RoomStatus::Available)->count();

        // Get gallery images from all rooms in this category
        $galleryImages = \App\Models\RoomImage::whereHas('room', function ($q) use ($category) {
            $q->where('room_category_id', $category->id);
        })->inRandomOrder()->limit(6)->get();

        return view('public.book', [
            'category' => $category,
            'availableRoomsCount' => $availableRoomsCount,
            'user' => auth()->user(),
            'galleryImages' => $galleryImages,
        ]);
    }

    public function store(Request $request, RoomCategory $category)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'identity_type' => 'required|string|in:KTP,Passport,SIM',
            'identity_number' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
        ]);

        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);

        // Find an available room in this category that has no overlapping reservations
        $room = $category->rooms()
            ->where('status', '!=', RoomStatus::Maintenance)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', [ReservationStatus::Reserved, ReservationStatus::CheckedIn])
                      ->where('check_in_date', '<', $checkOut)
                      ->where('check_out_date', '>', $checkIn);
            })
            ->first();

        if (!$room) {
            return back()->withErrors(['room_id' => 'Maaf, semua kamar pada kategori ini sedang penuh pada tanggal tersebut.']);
        }

        $user = auth()->user();
        $guest = null;

        try {
            $reservation = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $validated, $room, $checkIn, $checkOut, &$guest) {
                // Find or update Guest based on authenticated user
                $guest = Guest::updateOrCreate(
                    ['identity_number' => $validated['identity_number']],
                    [
                        'full_name' => $user->name,
                        'phone' => $validated['phone'],
                        'identity_type' => $validated['identity_type'],
                        'email' => $user->email,
                    ]
                );

                $nights = $checkIn->diffInDays($checkOut);
                $totalPrice = $nights * $room->price;

                $reservation = Reservation::create([
                    'booking_code' => 'RES-' . strtoupper(uniqid()),
                    'guest_id' => $guest->id,
                    'room_id' => $room->id,
                    'created_by' => $user->id,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'nights' => $nights,
                    'total_price' => $totalPrice,
                    'status' => ReservationStatus::Reserved,
                ]);

                // Only update room status to Reserved if it's currently Available
                if ($room->status === RoomStatus::Available) {
                    $room->update(['status' => RoomStatus::Reserved]);
                }

                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => "Tamu melakukan reservasi mandiri ({$reservation->booking_code}) untuk Kamar {$room->room_number}.",
                    'ip_address' => request()->ip(),
                ]);

                return $reservation;
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses reservasi. Silakan coba lagi.');
        }

        // Send Email Notification & WhatsApp Message asynchronously
        dispatch(function () use ($reservation, $guest, $room, $checkIn, $checkOut) {
            if ($guest && $guest->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($guest->email)->send(new \App\Mail\ReservationConfirmed($reservation));
                } catch (\Exception $e) {}
            }

            if ($guest && $guest->phone) {
                try {
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . urlencode($reservation->booking_code);
                    $caption = "Yth. Bpk/Ibu *{$guest->full_name}*,\n\nTerima kasih telah mempercayakan akomodasi Anda di *Tibra Sare Hotel*. Kami dengan senang hati mengonfirmasi reservasi Anda dengan rincian sebagai berikut:\n\n*Kode Booking*: {$reservation->booking_code}\n*Kamar*: {$room->room_number} ({$room->category->name})\n*Check-In*: {$checkIn->format('d M Y')}\n*Check-Out*: {$checkOut->format('d M Y')}\n\nSelesaikan pembayaran Anda segera untuk mengamankan pesanan ini.\n\nSalam hangat,\n*Manajemen Tibra Sare Hotel*";
                    \App\Services\WamifyService::sendMediaMessage($guest->phone, $qrUrl, $caption);
                } catch (\Exception $e) {}
            }
        })->afterResponse();

        // --- XENDIT INTEGRATION ---
        $xenditService = new XenditService();
        $invoice = $xenditService->createInvoice([
            'external_id' => $reservation->booking_code,
            'amount' => $reservation->total_price,
            'description' => 'Reservasi Kamar ' . $room->room_number . ' (' . $room->category->name . ')',
            'payer_email' => $guest->email,
            'customer_name' => $guest->full_name,
            'customer_phone' => $guest->phone,
        ]);

        if ($invoice && isset($invoice['invoice_url'])) {
            $reservation->update(['payment_url' => $invoice['invoice_url']]);
            return redirect($invoice['invoice_url']);
        }

        // Fallback jika Duitku error
        return redirect()->route('dashboard')->with('success', 'Reservasi berhasil dibuat! Namun Payment Gateway saat ini sedang tidak tersedia.');
    }
}
