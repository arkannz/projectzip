<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Location;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('location')->get();
        $locations = Location::all();

        return view('admin.data_rumah.unit', compact('units', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'kode_unit' => 'required|string|max:10',
        ]);

        Unit::create($request->only('location_id', 'kode_unit'));

        return back()->with('success', 'Unit rumah berhasil ditambahkan.');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'kode_unit' => 'required|string|max:10',
        ]);

        $unit->update($request->only('location_id', 'kode_unit'));

        return back()->with('success', 'Unit rumah berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit rumah berhasil dihapus.');
    }
}
    