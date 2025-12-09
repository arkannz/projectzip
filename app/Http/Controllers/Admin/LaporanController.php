<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Location;
use App\Models\RabItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function laporanTukang(Request $request)
    {
        $types = Type::all();
        $units = Unit::with('location')->get();
        $locations = Location::all();

        $typeId = $request->type_id;
        $unitId = $request->unit_id;
        $locationId = $request->location_id;
        $periode = $request->periode; // format: YYYY-MM-DD atau range YYYY-MM-DD:YYYY-MM-DD

        // Query untuk laporan tukang
        $query = RabItem::with(['type', 'unit', 'location', 'category'])
            ->where('upah', '>', 0); // Hanya ambil yang ada upah

        // Filter berdasarkan parameter
        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        // Filter periode
        if ($periode) {
            if (strpos($periode, ':') !== false) {
                // Range tanggal
                [$startDate, $endDate] = explode(':', $periode);
                $query->whereBetween('created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            } else {
                // Single date
                $query->whereDate('created_at', Carbon::parse($periode));
            }
        }

        $laporanTukang = $query->orderBy('created_at', 'desc')->get();

        // Group by untuk summary
        $summary = $laporanTukang->groupBy(function ($item) {
            return $item->type->name . ' - ' . $item->location->name . ' - ' . $item->unit->kode_unit;
        })->map(function ($group) {
            return [
                'total_upah' => $group->sum('upah'),
                'total_item' => $group->count(),
                'type' => $group->first()->type->name,
                'location' => $group->first()->location->name,
                'unit' => $group->first()->unit->kode_unit,
            ];
        });

        return view('admin.laporan.tukang', [
            'types' => $types,
            'units' => $units,
            'locations' => $locations,
            'type_id' => $typeId,
            'unit_id' => $unitId,
            'location_id' => $locationId,
            'periode' => $periode,
            'laporanTukang' => $laporanTukang,
            'summary' => $summary,
        ]);
    }
}

