/**
 * Application-wide constants for Tibra Sare Hotel.
 * Single source of truth for enums, labels, and status mappings.
 */

/* ─── User Roles ─── */
export const ROLES = {
    ADMIN: 'admin',
    RECEPTIONIST: 'receptionist',
};

export const ROLE_LABELS = {
    [ROLES.ADMIN]: 'Admin',
    [ROLES.RECEPTIONIST]: 'Resepsionis',
};

/* ─── Room Status ─── */
export const ROOM_STATUS = {
    AVAILABLE: 'available',
    OCCUPIED: 'occupied',
    RESERVED: 'reserved',
    MAINTENANCE: 'maintenance',
};

export const ROOM_STATUS_CONFIG = {
    [ROOM_STATUS.AVAILABLE]: {
        label: 'Tersedia',
        color: 'success',
        dotClass: 'status-dot-available',
    },
    [ROOM_STATUS.OCCUPIED]: {
        label: 'Terisi',
        color: 'destructive',
        dotClass: 'status-dot-occupied',
    },
    [ROOM_STATUS.RESERVED]: {
        label: 'Dipesan',
        color: 'warning',
        dotClass: 'status-dot-reserved',
    },
    [ROOM_STATUS.MAINTENANCE]: {
        label: 'Maintenance',
        color: 'muted',
        dotClass: 'status-dot-maintenance',
    },
};

/* ─── Reservation Status ─── */
export const RESERVATION_STATUS = {
    RESERVED: 'reserved',
    CHECKED_IN: 'checked_in',
    CHECKED_OUT: 'checked_out',
    CANCELLED: 'cancelled',
};

export const RESERVATION_STATUS_CONFIG = {
    [RESERVATION_STATUS.RESERVED]: {
        label: 'Menunggu',
        color: 'warning',
    },
    [RESERVATION_STATUS.CHECKED_IN]: {
        label: 'In House',
        color: 'success',
    },
    [RESERVATION_STATUS.CHECKED_OUT]: {
        label: 'Selesai',
        color: 'muted',
    },
    [RESERVATION_STATUS.CANCELLED]: {
        label: 'Dibatalkan',
        color: 'destructive',
    },
};

/* ─── Navigation (Mobile Bottom Nav) ─── */
export const BOTTOM_NAV_ITEMS = [
    { label: 'Dashboard', href: '/dashboard', icon: 'LayoutDashboard' },
    { label: 'Reservasi', href: '/reservations', icon: 'CalendarCheck' },
    { label: 'Kamar', href: '/rooms', icon: 'BedDouble', adminOnly: true },
    { label: 'Lainnya', href: '#more', icon: 'Menu' },
];

/* ─── Sidebar Menu ─── */
export const SIDEBAR_MENU = [
    {
        group: 'Utama',
        items: [
            { label: 'Dashboard', href: '/dashboard', icon: 'LayoutDashboard' },
            { label: 'Reservasi', href: '/reservations', icon: 'CalendarCheck' },
            { label: 'Check-In', href: '/check-in', icon: 'LogIn' },
            { label: 'Check-Out', href: '/check-out', icon: 'LogOut' },
        ],
    },
    {
        group: 'Kelola',
        items: [
            { label: 'Kamar', href: '/rooms', icon: 'BedDouble', adminOnly: true },
            { label: 'Kategori Kamar', href: '/room-categories', icon: 'LayoutGrid', adminOnly: true },
            { label: 'Fasilitas', href: '/facilities', icon: 'Wifi', adminOnly: true },
            { label: 'Tamu', href: '/guests', icon: 'Users' },
        ],
    },
    {
        group: 'Laporan',
        adminOnly: true,
        items: [
            { label: 'Laporan', href: '/reports', icon: 'BarChart3', adminOnly: true },
        ],
    },
    {
        group: 'Sistem',
        adminOnly: true,
        items: [
            { label: 'Pengguna', href: '/users', icon: 'UserCog', adminOnly: true },
            { label: 'Activity Log', href: '/activity-logs', icon: 'History', adminOnly: true },
            { label: 'Pengaturan', href: '/settings', icon: 'Settings', adminOnly: true },
        ],
    },
];

/* ─── Breakpoints ─── */
export const BREAKPOINTS = {
    SM: 640,
    MD: 768,
    LG: 1024,
    XL: 1280,
};
