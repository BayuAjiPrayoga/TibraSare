<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Terima Kasih Atas Kunjungan Anda</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; }
        .text-primary { color: #0f172a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(file_exists(public_path('images/headeremail.png')))
                <img src="{{ $message->embed(public_path('images/headeremail.png')) }}" alt="Tibra Sare Hotel" style="max-width: 100%; height: auto; display: block; border-radius: 8px 8px 0 0;">
            @else
                <h1 class="text-primary">Tibra Sare Hotel</h1>
                <p>Sampai Jumpa Kembali!</p>
            @endif
        </div>
        
        <div class="card">
            <h2>Halo, {{ $reservation->guest->full_name }}!</h2>
            <p>Proses <strong>Check-Out</strong> Anda untuk reservasi dengan kode <strong>{{ $reservation->booking_code }}</strong> telah berhasil diproses pada {{ now()->format('d M Y H:i') }}.</p>
            
            <p>Rincian Tagihan Anda:</p>
            <ul>
                <li><strong>Kamar:</strong> {{ $reservation->room->room_number }}</li>
                <li><strong>Durasi:</strong> {{ $reservation->nights }} Malam</li>
                <li><strong>Total Tagihan:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</li>
                <li><strong>Status Pembayaran:</strong> <span style="color: #16a34a; font-weight: bold;">LUNAS</span></li>
            </ul>
            
            <p>Terima kasih telah memilih Tibra Sare Hotel sebagai tempat menginap Anda. Kami berharap dapat menyambut Anda kembali di masa mendatang.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tibra Sare Hotel Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
