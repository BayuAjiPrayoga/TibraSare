$views = [
    'dashboard.index',
    'guest.dashboard',
    'public.landing',
    'rooms.index',
    'room-categories.index',
    'guests.index',
    'users.index',
    'reservations.index',
    'reservations.create',
    'checkin.index',
    'checkout.index',
    'facilities.index',
    'reports.index',
    'settings.index',
    'profile.edit',
];

foreach ($views as $viewName) {
    try {
        echo "Testing $viewName... ";
        $path = view($viewName)->getPath();
        app('blade.compiler')->compileString(file_get_contents($path));
        echo "OK\n";
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
