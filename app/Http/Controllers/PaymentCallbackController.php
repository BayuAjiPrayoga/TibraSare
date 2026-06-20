<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handleXendit(Request $request)
    {
        Log::info('Xendit Callback Received', $request->all());

        // Validate Xendit Webhook Token
        $xenditToken = config('services.xendit.webhook_token');
        $callbackToken = $request->header('x-callback-token');

        if (!empty($xenditToken) && $callbackToken !== $xenditToken) {
            Log::warning('Xendit Callback - Invalid Token', ['ip' => $request->ip()]);
            return response()->json(['status' => 'Bad Signature'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');

        $reservation = Reservation::where('booking_code', $externalId)->first();

        if (!$reservation) {
            Log::error('Xendit Callback - Reservation Not Found', ['order' => $externalId]);
            return response()->json(['status' => 'Not Found'], 404);
        }

        if ($status === 'PAID' || $status === 'SETTLED') {
            // Generate QR Code
            $qrCodeFileName = 'qrcodes/' . $reservation->booking_code . '.png';
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('qrcodes')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('qrcodes');
            }
            $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($reservation->booking_code);
            \Illuminate\Support\Facades\Storage::disk('public')->put($qrCodeFileName, $qrImage);

            $reservation->update([
                'payment_status' => 'PAID',
                'qr_code_path' => $qrCodeFileName,
            ]);
            Log::info("Reservation {$externalId} marked as PAID with QR Code.");

            // Send Invoice Email asynchronously
            if ($reservation->guest && $reservation->guest->email) {
                dispatch(function () use ($reservation) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($reservation->guest->email)->send(new \App\Mail\PaymentSuccess($reservation));
                    } catch (\Exception $e) {
                        Log::error('Failed to send PaymentSuccess email: ' . $e->getMessage());
                    }
                })->afterResponse();
            }

        } elseif ($status === 'EXPIRED') {
            if ($reservation->status === ReservationStatus::Reserved) {
                $reservation->update([
                    'payment_status' => 'FAILED',
                    'status' => ReservationStatus::Cancelled,
                ]);

                // Free the room if it's not today's check-in that was already forced
                if ($reservation->room->status === RoomStatus::Reserved) {
                    $reservation->room->update(['status' => RoomStatus::Available]);
                }

                Log::info("Reservation {$externalId} automatically CANCELLED due to payment expiry.");
                
                // Optional: Send cancel email
                if ($reservation->guest->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($reservation->guest->email)->send(new \App\Mail\ReservationCancelled($reservation));
                    } catch (\Exception $e) {}
                }
            }
        }

        return response()->json(['status' => 'Success']);
    }
}
