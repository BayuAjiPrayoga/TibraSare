<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class CheckInNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $hasWifi;
    public $wifiSsid;
    public $wifiPass;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, bool $hasWifi = false, string $wifiSsid = '', string $wifiPass = '')
    {
        $this->reservation = $reservation;
        $this->hasWifi = $hasWifi;
        $this->wifiSsid = $wifiSsid;
        $this->wifiPass = $wifiPass;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Tibra Sare Hotel - Check-In Berhasil',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.check-in',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
