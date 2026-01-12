<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Models\Location;
use App\Models\Type;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk dashboard
        $totalItems = InventoryItem::count();
        $totalUnits = Unit::count();
        $totalLocations = Location::count();
        $totalTypes = Type::count();

        // Aktivitas terakhir (7 hari terakhir)
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalItems',
            'totalUnits',
            'totalLocations',
            'totalTypes',
            'recentActivities'
        ));
    }

    /**
     * Halaman detail aktivitas
     */
    public function activities(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        $activities = $query->paginate(20);

        // Statistik aktivitas
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
        ];

        // Aktivitas per type
        $activitiesByType = ActivityLog::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->get();

        return view('admin.dashboard.activities', compact('activities', 'stats', 'activitiesByType'));
    }
}