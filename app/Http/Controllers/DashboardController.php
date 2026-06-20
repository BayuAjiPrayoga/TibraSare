<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ActivityLog;
use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (auth()->user()->role->value === \App\Enums\UserRole::Guest->value) {
            $myReservations = Reservation::with('room.category')
                ->where('created_by', auth()->id())
                ->latest()
                ->get()
                ->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'booking_code' => $res->booking_code,
                        'status' => $res->status->value,
                        'check_in_date' => $res->check_in_date->format('Y-m-d'),
                        'check_out_date' => $res->check_out_date->format('Y-m-d'),
                        'payment_status' => $res->payment_status,
                        'payment_url' => $res->payment_url,
                        'total_price' => (int) $res->total_price,
                        'room' => [
                            'room_number' => $res->room->room_number,
                            'category' => [
                                'name' => $res->room->category->name,
                            ],
                        ]
                    ];
                });
            $roomCategories = \App\Models\RoomCategory::withCount(['rooms as available_rooms_count' => function ($query) {
                    $query->where('status', \App\Enums\RoomStatus::Available);
                }])
                ->get();

            return view('guest.dashboard', [
                'reservations' => $myReservations,
                'roomCategories' => $roomCategories
            ]);
        }

        $totalRooms = Room::count();
        $availableRooms = Room::where('status', RoomStatus::Available)->count();
        $occupiedRooms = Room::where('status', RoomStatus::Occupied)->count();
        $totalGuests = Guest::count();
        
        $totalReservations = Reservation::whereIn('status', [
            ReservationStatus::Reserved, 
            ReservationStatus::CheckedIn
        ])->count();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'action' => $log->action,
                    'user' => ['name' => $log->user->name ?? 'System'],
                    'time_ago' => $log->created_at->diffForHumans(),
                ];
            });

        // Revenue Chart Data (Last 6 Months)
        $revenueData = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            $total = \App\Models\Reservation::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('payment_status', 'PAID')
                ->sum('total_price');
                
            return [
                'month' => $date->format('M'),
                'revenue' => (float) $total
            ];
        });

        return view('dashboard.index', [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'totalGuests' => $totalGuests,
            'totalReservations' => $totalReservations,
            'recentActivities' => $recentActivities,
            'revenueData' => $revenueData,
        ]);
    }
}
