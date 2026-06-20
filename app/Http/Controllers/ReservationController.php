<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Mail\ReservationCancelled;
use App\Mail\ReservationConfirmed;
use App\Models\ActivityLog;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\XenditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $query = Reservation::with(['guest', 'room.category'])->latest();

        if ($search) {
            $query->where('booking_code', 'like', "%{$search}%")
                ->orWhereHas('guest', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                });
        }

        $reservations = $query->paginate(12)->withQueryString()->through(function ($res) {
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

        return view('reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    public function create(): View
    {
        // Simple logic to pass available rooms for the wizard
        $availableRooms = Room::with('category')->where('status', 'available')->get()->map(function ($room) {
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'price' => (int) $room->price,
                'category' => [
                    'name' => $room->category->name,
                ],
            ];
        });

        $guests = Guest::select('id', 'full_name', 'email', 'phone')->get();

        return view('reservations.create', [
            'availableRooms' => $availableRooms,
            'guests' => $guests,
        ]);
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['guest', 'room.category', 'creator']);

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',

            // Guest data (either existing guest_id OR new guest data)
            'guest_id' => 'nullable|exists:guests,id',
            'full_name' => 'required_without:guest_id|string|max:255',
            'email' => 'required_without:guest_id|email|max:255',
            'phone' => 'required_without:guest_id|string|max:20',
            'identity_type' => 'required_without:guest_id|string|in:KTP,Passport,SIM',
            'identity_number' => 'required_without:guest_id|string|max:50',
        ]);

        // Get or Create Guest
        if (empty($validated['guest_id'])) {
            $guest = Guest::firstOrCreate(
                ['identity_number' => $validated['identity_number']],
                [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                ]
            );
            $guestId = $guest->id;
        } else {
            $guestId = $validated['guest_id'];
        }

        $room = Room::findOrFail($validated['room_id']);

        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $nights * $room->price;

        $reservation = Reservation::create([
            'booking_code' => 'RES-'.strtoupper(uniqid()),
            'guest_id' => $guestId,
            'room_id' => $room->id,
            'created_by' => auth()->id(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'nights' => $nights,
            'total_price' => $totalPrice,
            'status' => ReservationStatus::Reserved,
        ]);

        $room->update(['status' => RoomStatus::Reserved]);

        // Log Activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => "Membuat reservasi baru ({$reservation->booking_code}) untuk tamu {$reservation->guest->full_name}.",
            'ip_address' => $request->ip(),
        ]);

        // Send Email Notification asynchronously to speed up response
        if ($reservation->guest->email) {
            dispatch(function () use ($reservation) {
                try {
                    Mail::to($reservation->guest->email)->send(new ReservationConfirmed($reservation));
                } catch (\Exception $e) {
                    // Log email error if needed, but don't fail the reservation
                }
            })->afterResponse();
        }

        // --- XENDIT INTEGRATION ---
        $xenditService = new XenditService();
        $guestObj = Guest::find($guestId);

        $invoice = $xenditService->createInvoice([
            'external_id' => $reservation->booking_code,
            'amount' => $reservation->total_price,
            'description' => 'Reservasi Kamar '.$room->room_number.' ('.$room->category->name.')',
            'payer_email' => $guestObj->email,
            'customer_name' => $guestObj->full_name,
            'customer_phone' => $guestObj->phone,
        ]);

        if ($invoice && isset($invoice['invoice_url'])) {
            $reservation->update(['payment_url' => $invoice['invoice_url']]);

            return redirect($invoice['invoice_url']);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat. Namun sistem pembayaran sedang gangguan.');
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        // Pastikan hanya tamu yang bersangkutan atau admin yang bisa membatalkan
        if (auth()->user()->role?->value !== 'admin' && $reservation->guest->email !== auth()->user()->email) {
            return back()->with('error', 'Anda tidak berhak membatalkan reservasi ini.');
        }

        if ($reservation->status !== ReservationStatus::Reserved) {
            return back()->with('error', 'Hanya reservasi dengan status "Dipesan" yang dapat dibatalkan.');
        }

        $reservation->update(['status' => ReservationStatus::Cancelled]);

        // Jika kamar belum check-in hari ini, bebaskan status kamar
        if ($reservation->room->status === RoomStatus::Reserved) {
            $reservation->room->update(['status' => RoomStatus::Available]);
        }

        // Log Activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => "Membatalkan reservasi ({$reservation->booking_code}).",
            'ip_address' => $request->ip(),
        ]);

        // Send Email Notification asynchronously to speed up response
        if ($reservation->guest->email) {
            dispatch(function () use ($reservation) {
                try {
                    Mail::to($reservation->guest->email)->send(new ReservationCancelled($reservation));
                } catch (\Exception $e) {
                    // Ignore email error
                }
            })->afterResponse();
        }

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
