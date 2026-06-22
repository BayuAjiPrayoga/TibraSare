<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $reservation->booking_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0f172a;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            color: #64748b;
        }
        .invoice-details {
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-details td {
            vertical-align: top;
        }
        .guest-info {
            width: 50%;
        }
        .booking-info {
            width: 50%;
            text-align: right;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .table th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: bold;
        }
        .table .text-right {
            text-align: right;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
        }
        .status {
            text-align: center;
            margin-top: 50px;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            border: 2px dashed;
        }
        .status-paid {
            color: #16a34a;
            border-color: #16a34a;
        }
        .status-unpaid {
            color: #eab308;
            border-color: #eab308;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        @if(file_exists(public_path('images/IconTS.png')))
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/IconTS.png'))) }}" alt="Tibra Sare Logo" style="height: 60px; margin-bottom: 10px;">
        @endif
        <h1>TIBRA SARE HOTEL</h1>
        <p>Jalan Soekarno-Hatta No. 378, Kota Bandung, Jawa Barat.</p>
        <p>Telepon: (022) 123456 | Email: tibrasare@luhur.my.id</p>
    </div>

    <table class="invoice-details">
        <tr>
            <td class="guest-info">
                <strong>Tagihan Kepada:</strong><br>
                {{ $reservation->guest->full_name }}<br>
                {{ $reservation->guest->phone }}<br>
                {{ $reservation->guest->email }}
            </td>
            <td class="booking-info">
                <strong>Nomor Invoice:</strong> INV/{{ $reservation->booking_code }}<br>
                <strong>Tanggal Terbit:</strong> {{ now()->format('d M Y') }}<br>
                <strong>Kode Booking:</strong> {{ $reservation->booking_code }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th class="text-right">Malam</th>
                <th class="text-right">Harga/Malam</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Kamar {{ $reservation->room->room_number }}</strong><br>
                    <small>Tipe: {{ $reservation->room->category->name }}</small>
                </td>
                <td>{{ $reservation->check_in_date->format('d M Y') }}</td>
                <td>{{ $reservation->check_out_date->format('d M Y') }}</td>
                <td class="text-right">{{ $reservation->nights }}</td>
                <td class="text-right">Rp {{ number_format($reservation->room->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL TAGIHAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        @if($reservation->payment_status === 'PAID' || $reservation->payment_status === 'SETTLED' || in_array($reservation->status->value, ['Checked In', 'Checked Out']))
        <div class="status status-paid">
            STATUS: LUNAS (PAID)
        </div>
        <p style="margin-top: 20px; color: #64748b; font-size: 14px;">
            Pembayaran telah berhasil diverifikasi oleh sistem.
        </p>
        @else
        <div class="status status-unpaid">
            STATUS: MENUNGGU PEMBAYARAN (UNPAID)
        </div>
        <p style="margin-top: 20px; color: #64748b; font-size: 14px;">
            Sistem menunggu pembayaran Anda.<br>
            Untuk saat ini, silakan selesaikan pembayaran.
        </p>
        @endif
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Tibra Sare Hotel Management. Terima kasih atas kepercayaan Anda.
    </div>

</body>
</html>
