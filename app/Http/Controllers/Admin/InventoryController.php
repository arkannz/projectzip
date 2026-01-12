<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use App\Models\Location;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Angkutan;
use App\Helpers\ActivityLogger;

class InventoryController extends Controller
{
    /**
     * Menampilkan halaman utama inventory
     */
    public function index()
    {
        $items = InventoryItem::all();

        $masuk   = InventoryIn::with('item')->latest()->get();
        $keluar  = InventoryOut::with(['item','location','type','unit'])->latest()->get();

        $locations = Location::all();
        $types     = Type::all();
        $units     = Unit::with('location')->get();

        return view('admin.inventory.index', compact(
            'items', 'masuk', 'keluar', 'locations', 'types', 'units'
        ));
    }

    public function history(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $location_id = $request->location_id;

        $masuk = InventoryIn::with('item')->orderBy('created_at', 'desc');
        $keluar = InventoryOut::with(['item','location','type','unit'])->orderBy('created_at', 'desc');

        // ================================
        // Filter tanggal fleksibel
        // ================================

        // Jika hanya start_date diisi → end_date = hari ini
        if ($start && !$end) {
            $end = now()->format('Y-m-d');
        }

        // Jika start & end terisi → jalankan filter
        if ($start && $end) {
            $masuk->whereBetween('created_at', ["$start 00:00:00", "$end 23:59:59"]);
            $keluar->whereBetween('created_at', ["$start 00:00:00", "$end 23:59:59"]);
        }

        // ============== FILTER LOKASI ==============
        if ($location_id) {
            $keluar->where('location_id', $location_id);
        }

        return view('admin.inventory.history', [
            'masuk' => $masuk->get(),
            'keluar' => $keluar->get(),
            'locations' => \App\Models\Location::all(),
            'start' => $start,
            'end' => $end,
            'location_id' => $location_id,
        ]);
    }

    public function addLocation(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $location = \App\Models\Location::create([
            'nama' => $request->nama
        ]);

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'id' => $location->id,
                'nama' => $location->nama
            ]);
        }

        return back()->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function addType(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $type = \App\Models\Type::create([
            'nama' => $request->nama
        ]);

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'id' => $type->id,
                'nama' => $type->nama
            ]);
        }

        return back()->with('success', 'Type rumah berhasil ditambahkan.');
    }

    public function addUnit(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type_id'     => 'required|exists:types,id',
            'kode_unit'   => 'required|string|max:50',
        ]);

        $unit = \App\Models\Unit::create([
            'location_id' => $request->location_id,
            'type_id'     => $request->type_id,
            'kode_unit'   => $request->kode_unit,
        ]);

        // Load relasi location untuk mendapatkan nama lokasi
        $unit->load('location');

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'id' => $unit->id,
                'kode_unit' => $unit->kode_unit,
                'location_nama' => $unit->location->nama
            ]);
        }

        return back()->with('success', 'Unit rumah berhasil ditambahkan.');
    }


    /**
     * Simpan master data bahan
     */
    public function storeItem(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'harga'  => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        InventoryItem::create($request->only('nama', 'satuan', 'harga', 'keterangan', 'stok_awal'));

        return back()->with('success', 'Bahan baru berhasil ditambahkan.');
    }

    /**
     * Edit master item
     */
    public function updateItem(Request $request, InventoryItem $item)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'harga'  => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $item->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Data bahan berhasil diperbarui.');
    }

    /**
     * Hapus master item
     */
    public function destroyItem(InventoryItem $item)
    {
        $item->delete();
        return back()->with('success', 'Bahan berhasil dihapus.');
    }

    /**
     * Tambah transaksi barang masuk
     */
    public function storeIn(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'jumlah_masuk'      => 'required|integer|min:1',
            'keterangan'        => 'nullable|string'
        ]);

        InventoryIn::create($request->only('inventory_item_id', 'jumlah_masuk', 'keterangan'));

        return back()->with('success', 'Transaksi masuk berhasil ditambahkan.');
    }

    /**
     * Tambah transaksi barang keluar
     */
    public function storeOut(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'jumlah_keluar'     => 'required|integer|min:1',
            'location_id'       => 'required|exists:locations,id',
            'type_id'           => 'required|exists:types,id',
            'unit_id'           => 'required|exists:units,id',
            'keterangan'        => 'nullable|string'
        ]);

        InventoryOut::create($request->only(
            'inventory_item_id',
            'jumlah_keluar',
            'location_id',
            'type_id',
            'unit_id',
            'keterangan'
        ));

        return back()->with('success', 'Transaksi keluar berhasil ditambahkan.');
    }

    /**
     * Print view untuk inventory stock
     */
    public function print()
    {
        // Ambil semua item dengan stok awal dan stok akhir
        $items = InventoryItem::all()->map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'satuan' => $item->satuan,
                'harga' => $item->harga,
                'stok_awal' => $item->stok_awal,
                'stok_akhir' => $item->stok_akhir, // Stock saat ini
            ];
        })->filter(function($item) {
            // Hanya tampilkan item yang memiliki stok (stok_akhir > 0 atau ada di database)
            return true; // Tampilkan semua item, termasuk yang stok 0
        })->values();

        return view('admin.inventory.print', compact('items'));
    }

    /**
     * Menampilkan halaman data angkutan
     */
    public function angkutan()
    {
        $angkatans = Angkutan::orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.angkutan.index', compact('angkatans'));
    }

    /**
     * Menyimpan data angkutan baru
     */
    public function storeAngkutan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode' => 'required|string|max:10',
            'lokasi' => 'required|string|max:255',
            'angkutan' => 'required|in:Pasir,Batu',
            'jumlah' => 'required|integer|min:1',
            'pangkalan' => 'required|string|max:255',
        ]);

        $angkutan = Angkutan::create($request->all());

        // Log aktivitas
        ActivityLogger::angkutan('create', "Menambah data angkutan {$angkutan->angkutan} {$angkutan->jumlah} di {$angkutan->lokasi}", $angkutan);

        return redirect()->route('inventory.angkutan')
            ->with('success', 'Data angkutan berhasil ditambahkan!');
    }

    /**
     * Update data angkutan
     */
    public function updateAngkutan(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode' => 'required|string|max:10',
            'lokasi' => 'required|string|max:255',
            'angkutan' => 'required|in:Pasir,Batu',
            'jumlah' => 'required|integer|min:1',
            'pangkalan' => 'required|string|max:255',
        ]);

        $angkutan = Angkutan::findOrFail($id);
        $oldValues = $angkutan->toArray();
        $angkutan->update($request->all());
        $newValues = $angkutan->fresh()->toArray();

        // Log aktivitas
        ActivityLogger::angkutan('update', "Update data angkutan {$angkutan->angkutan} di {$angkutan->lokasi}", $angkutan, $oldValues, $newValues);

        return redirect()->route('inventory.angkutan')
            ->with('success', 'Data angkutan berhasil diupdate!');
    }

    /**
     * Hapus data angkutan
     */
    public function destroyAngkutan($id)
    {
        $angkutan = Angkutan::findOrFail($id);
        $description = "Menghapus data angkutan {$angkutan->angkutan} {$angkutan->jumlah} di {$angkutan->lokasi}";
        $angkutan->delete();

        // Log aktivitas
        ActivityLogger::angkutan('delete', $description, null);

        return redirect()->route('inventory.angkutan')
            ->with('success', 'Data angkutan berhasil dihapus!');
    }

    /**
     * Print/Export data angkutan
     */
    public function printAngkutan()
    {
        $angkatans = Angkutan::orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Log aktivitas print
        ActivityLogger::print('angkutan', 'Print/Export data angkutan');

        return view('admin.angkutan.print', compact('angkatans'));
    }
}