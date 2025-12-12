<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Models\Location;
use App\Models\Type;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk dashboard
        $totalItems = InventoryItem::count();
        $totalUnits = Unit::count();
        $totalLocations = Location::count();
        $totalTypes = Type::count();

        return view('admin.dashboard.index', compact(
            'totalItems',
            'totalUnits',
            'totalLocations',
            'totalTypes'
        ));
    }
}