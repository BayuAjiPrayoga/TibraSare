<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Reservation;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(): View
    {
        // Dummy logic for MVP, normally this would group by month
        $monthlyRevenue = Reservation::whereYear('created_at', Carbon::now()->year)
                                     ->where('payment_status', 'PAID')
                                     ->sum('total_price');

        $totalReservations = Reservation::whereYear('created_at', Carbon::now()->year)
                                        ->count();
                                        
        $reportData = Reservation::with(['guest', 'room.category'])
            ->latest()
            ->take(50) // Limit to latest 50 for the report MVP
            ->get()
            ->map(function ($res) {
                return [
                    'id' => $res->id,
                    'booking_code' => $res->booking_code,
                    'guest_name' => $res->guest->full_name ?? '-',
                    'room_number' => $res->room->room_number ?? '-',
                    'check_in' => $res->check_in_date->format('Y-m-d'),
                    'check_out' => $res->check_out_date->format('Y-m-d'),
                    'total_price' => (int) $res->total_price,
                    'status' => $res->status->value,
                ];
            });

        return view('reports.index', [
            'stats' => [
                'monthly_revenue' => (int) $monthlyRevenue,
                'total_reservations' => $totalReservations,
            ],
            'reportData' => $reportData,
        ]);
    }
}
