<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembayaran Berhasil</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; }
        .text-primary { color: #0f172a; }
        .text-success { color: #16a34a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(file_exists(public_path('images/headeremail.png')))
                <img src="{{ $message->embed(public_path('images/headeremail.png')) }}" alt="Tibra Sare Hotel" style="max-width: 100%; height: auto; display: block; border-radius: 8px 8px 0 0;">
            @else
                <h1 class="text-primary">Tibra Sare Hotel</h1>
                <p>Pembayaran Berhasil</p>
            @endif
        </div>
        
        <div class="card">
            <h2>Halo, {{ $reservation->guest->full_name }}!</h2>
            <p>Terima kasih! Pembayaran Anda untuk reservasi kamar di Tibra Sare Hotel telah <strong class="text-success">BERHASIL DITERIMA</strong>.</p>
            
            <ul>
                <li><strong>Kode Booking:</strong> {{ $reservation->booking_code }}</li>
                <li><strong>Kamar:</strong> Kamar {{ $reservation->room->room_number }} ({{ $reservation->room->category->name }})</li>
                <li><strong>Total Dibayar:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</li>
                <li><strong>Status Pembayaran:</strong> LUNAS</li>
            </ul>

            @if($reservation->qr_code_path)
            <div style="text-align: center; margin: 20px 0;">
                <p><strong>QR Code Check-In:</strong></p>
                <img src="{{ $message->embed(storage_path('app/public/' . $reservation->qr_code_path)) }}" alt="QR Code" style="width: 200px; height: 200px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
                <p style="font-size: 12px; color: #64748b; margin-top: 5px;">Tunjukkan QR Code ini kepada resepsionis saat check-in</p>
            </div>
            @endif
            
            <p>Sebagai bukti sah, kami telah melampirkan <strong>Invoice Lunas</strong> beserta rincian pemesanan Anda pada email ini.</p>
            <p>Kami sangat menantikan kedatangan Anda di Tibra Sare Hotel!</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tibra Sare Hotel Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
