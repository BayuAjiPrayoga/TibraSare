$routes = ['/dashboard', '/rooms', '/room-categories', '/guests', '/users', '/reservations', '/reservations/create', '/check-in', '/check-out', '/facilities', '/reports', '/settings', '/profile'];
$user = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
foreach ($routes as $uri) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $request->setUserResolver(function () use ($user) { return $user; });
    $response = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
    echo $uri . ': ' . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        if ($response->exception) echo "   -> " . $response->exception->getMessage() . "\n";
    }
}
