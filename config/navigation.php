<?php

/**
 * Navigation configuration for Tibra Sare Hotel.
 * Single source of truth for sidebar and bottom nav menus.
 */

return [
    'sidebar' => [
        [
            'group' => 'Utama',
            'admin_only' => false,
            'items' => [
                ['label' => 'Dashboard',  'href' => '/dashboard',  'icon' => 'layout-dashboard'],
                ['label' => 'Reservasi',  'href' => '/reservations', 'icon' => 'calendar-check'],
                ['label' => 'Check-In',   'href' => '/check-in',   'icon' => 'log-in'],
                ['label' => 'Check-Out',  'href' => '/check-out',  'icon' => 'log-out'],
            ],
        ],
        [
            'group' => 'Kelola',
            'admin_only' => false,
            'items' => [
                ['label' => 'Kamar',           'href' => '/rooms',            'icon' => 'bed-double',   'admin_only' => true],
                ['label' => 'Kategori Kamar',  'href' => '/room-categories',  'icon' => 'layout-grid',  'admin_only' => true],
                ['label' => 'Fasilitas',       'href' => '/facilities',       'icon' => 'wifi',         'admin_only' => true],
                ['label' => 'Tamu',            'href' => '/guests',           'icon' => 'users'],
            ],
        ],
        [
            'group' => 'Laporan',
            'admin_only' => true,
            'items' => [
                ['label' => 'Laporan', 'href' => '/reports', 'icon' => 'bar-chart-3', 'admin_only' => true],
            ],
        ],
        [
            'group' => 'Sistem',
            'admin_only' => true,
            'items' => [
                ['label' => 'Pengguna',      'href' => '/users',          'icon' => 'user-cog',  'admin_only' => true],
                ['label' => 'Activity Log',  'href' => '/activity-logs',  'icon' => 'history',   'admin_only' => true],
                ['label' => 'Pengaturan',    'href' => '/settings',       'icon' => 'settings',  'admin_only' => true],
            ],
        ],
    ],

    'bottom_nav' => [
        ['label' => 'Dashboard', 'href' => '/dashboard',    'icon' => 'layout-dashboard'],
        ['label' => 'Reservasi', 'href' => '/reservations',  'icon' => 'calendar-check'],
        ['label' => 'Kamar',     'href' => '/rooms',         'icon' => 'bed-double', 'admin_only' => true],
        ['label' => 'Profil',    'href' => '/profile',       'icon' => 'user'],
    ],

    'room_status' => [
        'available' => ['label' => 'Tersedia',     'color' => 'success',     'dot_class' => 'status-dot-available'],
        'occupied' => ['label' => 'Terisi',        'color' => 'destructive', 'dot_class' => 'status-dot-occupied'],
        'reserved' => ['label' => 'Dipesan',       'color' => 'warning',     'dot_class' => 'status-dot-reserved'],
        'maintenance' => ['label' => 'Maintenance',   'color' => 'muted',       'dot_class' => 'status-dot-maintenance'],
    ],

    'reservation_status' => [
        'reserved' => ['label' => 'Menunggu',    'color' => 'warning'],
        'checked_in' => ['label' => 'In House',    'color' => 'success'],
        'checked_out' => ['label' => 'Selesai',     'color' => 'muted'],
        'cancelled' => ['label' => 'Dibatalkan',  'color' => 'destructive'],
    ],
];
