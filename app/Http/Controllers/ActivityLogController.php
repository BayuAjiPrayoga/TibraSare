<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        // Use pagination instead of get() for performance since logs can be huge
        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('activity-logs.index', [
            'logs' => $logs,
        ]);
    }
}
