<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if ($authUser->role === UserRole::Guest) {
            $myReservations = Reservation::with('room.category')
                ->where('created_by', auth()->id())
                ->latest()
                ->get()
                ->map(function (Reservation $res): array {
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
                        ],
                    ];
                });
            $roomCategories = RoomCategory::withCount(['rooms as available_rooms_count' => function ($query) {
                $query->where('status', RoomStatus::Available);
            }])
                ->get();

            return view('guest.dashboard', [
                'reservations' => $myReservations,
                'roomCategories' => $roomCategories,
            ]);
        }

        $totalRooms = Room::count();
        $availableRooms = Room::where('status', RoomStatus::Available)->count();
        $occupiedRooms = Room::where('status', RoomStatus::Occupied)->count();
        $totalGuests = Guest::count();

        $totalReservations = Reservation::whereIn('status', [
            ReservationStatus::Reserved,
            ReservationStatus::CheckedIn,
        ])->count();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function (ActivityLog $log): array {
                return [
                    'action' => $log->action,
                    'user' => ['name' => $log->user->name ?? 'System'],
                    'time_ago' => $log->created_at?->diffForHumans(),
                ];
            });

        // Revenue Chart Data (Last 6 Months)
        $revenueData = collect(range(5, 0))->map(function (int $i): array {
            $date = now()->subMonths($i);
            $total = Reservation::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('payment_status', 'PAID')
                ->sum('total_price');

            return [
                'month' => $date->format('M'),
                'revenue' => (float) $total,
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
