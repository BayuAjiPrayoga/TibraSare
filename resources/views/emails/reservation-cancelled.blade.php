<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembatalan Reservasi</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .card { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; }
        .text-destructive { color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(file_exists(public_path('images/headeremail.png')))
                <img src="{{ $message->embed(public_path('images/headeremail.png')) }}" alt="Tibra Sare Hotel" style="max-width: 100%; height: auto; display: block; border-radius: 8px 8px 0 0;">
            @else
                <h1>Tibra Sare Hotel</h1>
                <p>Pembatalan Reservasi</p>
            @endif
        </div>
        
        <div class="card">
            <h2>Halo, {{ explode(' ', $reservation->guest->full_name)[0] }}</h2>
            <p>Sesuai permintaan Anda, reservasi Anda di Tibra Sare Hotel telah <strong>dibatalkan</strong> secara sistem.</p>
            
            <ul>
                <li><strong>Kode Booking:</strong> {{ $reservation->booking_code }}</li>
                <li><strong>Kamar:</strong> Kamar {{ $reservation->room->room_number }} ({{ $reservation->room->category->name }})</li>
                <li><strong>Check-in Semula:</strong> {{ $reservation->check_in_date->format('d M Y') }}</li>
            </ul>
            
            <p>Jika ini adalah kesalahan atau Anda ingin mengatur jadwal ulang, silakan buat reservasi baru melalui aplikasi kami.</p>
            <p>Terima kasih dan semoga hari Anda menyenangkan.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tibra Sare Hotel Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
