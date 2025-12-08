<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Location;
use App\Models\RabItem;
use App\Models\RabTemplate;
use App\Models\RabCategory;
use App\Models\RabTypeValue;
use App\Models\RabCategoryBorongan;
use App\Models\InventoryItem;
use Database\Seeders\RabCategoryBoronganSeeder;

class RabController extends Controller
{
    public function index(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        // nilai default kosong
        $rabItems = collect();

        if ($typeId && $unitId && $locationId) {

            // AMBIL DATA RAB YANG SUDAH ADA
            $rabItems = RabItem::with('category')
                ->where('type_id', $typeId)
                ->where('unit_id', $unitId)
                ->where('location_id', $locationId)
                ->orderBy('rab_category_id')
                ->orderBy('id')
                ->get();

            // JIKA KOSONG → GENERATE BARU
            if ($rabItems->isEmpty()) {

                $this->generateRabFromTemplate($typeId, $unitId, $locationId);

                $rabItems = RabItem::with('category')
                    ->where('type_id', $typeId)
                    ->where('unit_id', $unitId)
                    ->where('location_id', $locationId)
                    ->orderBy('rab_category_id')
                    ->orderBy('id')
                    ->get();
            }
        }

        return view('admin.rab.index', [
            'types'         => $types,
            'units'         => $units,
            'locations'     => $locations,
            'type_id'       => $typeId,
            'unit_id'       => $unitId,
            'location_id'   => $locationId,
            'rabItems'      => $rabItems, // <<==== PENTING!
            'rabs'          => $rabItems,
        ]);
    }


    /**
     * Generate RAB items dari TEMPLATE
     */
    protected function generateRabFromTemplate($typeId, $unitId, $locationId)
    {
        // Ambil type untuk mendapatkan nama type
        $type = Type::find($typeId);
        $typeName = $type ? $type->nama : '';

        // Ambil semua template dengan relasi
        $templates = RabTemplate::with(['category', 'inventoryItem'])
            ->orderBy('category_id')
            ->orderBy('id')
            ->get();

        foreach ($templates as $tpl) {

            // Cari harga bahan di inventory
            $inventory = InventoryItem::where('nama', $tpl->item_name)->first();
            $harga = $inventory ? $inventory->harga : 0;

            // Cari bahan_baku dari RabTypeValue untuk type yang dipilih
            $typeValue = RabTypeValue::where('type_id', $typeId)
                ->where('rab_template_id', $tpl->id)
                ->first();

            // Gunakan bahan_baku dari TypeValue jika ada, atau default
            $bahanBaku = $typeValue ? $typeValue->bahan_baku : ($tpl->default_bahan_baku ?? 0);

            // Hitung total harga
            $totalHarga = $bahanBaku * $harga;

            RabItem::create([
                'type_id'          => $typeId,
                'unit_id'          => $unitId,
                'location_id'      => $locationId,
                'rab_category_id'  => $tpl->category_id,
                'uraian'           => $tpl->item_name,
                'bahan_baku'       => $bahanBaku,
                'bahan_out'        => 0,
                'harga_bahan'      => $harga,
                'total_harga'      => $totalHarga,
                'upah'             => 0,
                'borongan'         => 0,
                'untung_rugi'      => 0,
                'progres'          => 0,
            ]);
        }

        // Generate default borongan per kategori dari seeder data
        $this->generateDefaultBorongan($typeId, $unitId, $locationId, $typeName);
    }

    /**
     * Generate default borongan values per kategori berdasarkan type
     */
    protected function generateDefaultBorongan($typeId, $unitId, $locationId, $typeName)
    {
        $categories = RabCategory::all();
        $boronganData = RabCategoryBoronganSeeder::getBoronganData();

        // Cek apakah ada data borongan untuk type ini
        if (!isset($boronganData[$typeName])) {
            return;
        }

        $typeBorongan = $boronganData[$typeName];

        foreach ($categories as $category) {
            $kode = $category->kode;
            $nilaiBorongan = $typeBorongan[$kode] ?? 0;

            // Buat atau update borongan untuk kategori ini
            RabCategoryBorongan::updateOrCreate(
                [
                    'rab_category_id' => $category->id,
                    'type_id'         => $typeId,
                    'unit_id'         => $unitId,
                    'location_id'     => $locationId,
                ],
                [
                    'borongan' => $nilaiBorongan,
                ]
            );

            // Update untung_rugi untuk items di kategori ini
            $this->recalculateCategoryUntungRugi($category->id, $typeId, $unitId, $locationId);
        }
    }

    /**
     * RAB Type 50 - Halaman Laporan Printable
     */
    public function type50(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        // nilai default kosong
        $rabItems = collect();
        $categories = collect();
        $categoryBorongans = collect();

        if ($typeId && $unitId && $locationId) {

            // AMBIL DATA RAB YANG SUDAH ADA
            $rabItems = RabItem::with('category')
                ->where('type_id', $typeId)
                ->where('unit_id', $unitId)
                ->where('location_id', $locationId)
                ->orderBy('rab_category_id')
                ->orderBy('id')
                ->get();

            // JIKA KOSONG → GENERATE BARU
            if ($rabItems->isEmpty()) {
                $this->generateRabFromTemplate($typeId, $unitId, $locationId);

                $rabItems = RabItem::with('category')
                    ->where('type_id', $typeId)
                    ->where('unit_id', $unitId)
                    ->where('location_id', $locationId)
                    ->orderBy('rab_category_id')
                    ->orderBy('id')
                    ->get();
            }

            // Group items by category
            $categories = RabCategory::orderBy('id')->get();

            // Get borongan per category
            $categoryBorongans = RabCategoryBorongan::where('type_id', $typeId)
                ->where('unit_id', $unitId)
                ->where('location_id', $locationId)
                ->get()
                ->keyBy('rab_category_id');
        }

        // Get selected data for display
        $selectedType = $typeId ? Type::find($typeId) : null;
        $selectedUnit = $unitId ? Unit::find($unitId) : null;
        $selectedLocation = $locationId ? Location::find($locationId) : null;

        return view('admin.rab.type50', [
            'types'             => $types,
            'units'             => $units,
            'locations'         => $locations,
            'type_id'           => $typeId,
            'unit_id'           => $unitId,
            'location_id'       => $locationId,
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * Print view untuk RAB Type 50
     */
    public function type50Print(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type50')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
        }

        $rabItems = RabItem::with('category')
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->orderBy('rab_category_id')
            ->orderBy('id')
            ->get();

        $categories = RabCategory::orderBy('id')->get();

        $categoryBorongans = RabCategoryBorongan::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get()
            ->keyBy('rab_category_id');

        $selectedType = Type::find($typeId);
        $selectedUnit = Unit::find($unitId);
        $selectedLocation = Location::find($locationId);

        return view('admin.rab.type50-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * Update single RAB item (bahan_out, upah, progres)
     */
    public function updateItem(Request $request, RabItem $item)
    {
        $request->validate([
            'bahan_out' => 'nullable|numeric|min:0',
            'upah'      => 'nullable|numeric|min:0',
            'progres'   => 'nullable|numeric|min:0|max:100',
        ]);

        // Update bahan_out jika ada
        if ($request->has('bahan_out')) {
            $item->bahan_out = $request->bahan_out;
            // Hitung ulang total_harga = bahan_out * harga_bahan
            $item->total_harga = $item->bahan_out * $item->harga_bahan;
        }

        // Update upah jika ada
        if ($request->has('upah')) {
            $item->upah = $request->upah;
        }

        // Update progres jika ada
        if ($request->has('progres')) {
            $item->progres = $request->progres;
        }

        $item->save();

        // Hitung ulang untung/rugi untuk kategori ini
        $this->recalculateCategoryUntungRugi(
            $item->rab_category_id,
            $item->type_id,
            $item->unit_id,
            $item->location_id
        );

        return response()->json([
            'success' => true,
            'item' => $item->fresh(),
            'message' => 'Data berhasil diupdate'
        ]);
    }

    /**
     * Update borongan per category
     */
    public function updateCategoryBorongan(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:rab_categories,id',
            'type_id'      => 'required|exists:types,id',
            'unit_id'      => 'required|exists:units,id',
            'location_id'  => 'required|exists:locations,id',
            'borongan'     => 'required|numeric|min:0',
        ]);

        $borongan = RabCategoryBorongan::updateOrCreate(
            [
                'rab_category_id' => $request->category_id,
                'type_id'         => $request->type_id,
                'unit_id'         => $request->unit_id,
                'location_id'     => $request->location_id,
            ],
            [
                'borongan' => $request->borongan,
            ]
        );

        // Hitung ulang untung/rugi untuk kategori ini
        $this->recalculateCategoryUntungRugi(
            $request->category_id,
            $request->type_id,
            $request->unit_id,
            $request->location_id
        );

        return response()->json([
            'success' => true,
            'borongan' => $borongan,
            'message' => 'Borongan berhasil diupdate'
        ]);
    }

    /**
     * Recalculate untung/rugi for a category
     */
    protected function recalculateCategoryUntungRugi($categoryId, $typeId, $unitId, $locationId)
    {
        // Get total upah for this category
        $totalUpah = RabItem::where('rab_category_id', $categoryId)
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->sum('upah');

        // Get borongan for this category
        $categoryBorongan = RabCategoryBorongan::where('rab_category_id', $categoryId)
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->first();

        $borongan = $categoryBorongan ? $categoryBorongan->borongan : 0;

        // Untung/Rugi = Borongan - Total Upah
        $untungRugi = $borongan - $totalUpah;

        // Update semua items di kategori ini dengan untung_rugi yang sama
        // (bisa juga disimpan di level category, tapi untuk simplicity kita simpan di items)
        RabItem::where('rab_category_id', $categoryId)
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->update(['untung_rugi' => $untungRugi, 'borongan' => $borongan]);
    }

    /**
     * Batch update untuk multiple items
     */
    public function batchUpdate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:rab_items,id',
            'items.*.bahan_out' => 'nullable|numeric|min:0',
            'items.*.upah' => 'nullable|numeric|min:0',
            'items.*.progres' => 'nullable|numeric|min:0|max:100',
        ]);

        $categoryIds = [];

        foreach ($request->items as $itemData) {
            $item = RabItem::find($itemData['id']);
            
            if (isset($itemData['bahan_out'])) {
                $item->bahan_out = $itemData['bahan_out'];
                $item->total_harga = $item->bahan_out * $item->harga_bahan;
            }
            
            if (isset($itemData['upah'])) {
                $item->upah = $itemData['upah'];
            }
            
            if (isset($itemData['progres'])) {
                $item->progres = $itemData['progres'];
            }
            
            $item->save();
            
            $categoryIds[$item->rab_category_id] = [
                'type_id' => $item->type_id,
                'unit_id' => $item->unit_id,
                'location_id' => $item->location_id,
            ];
        }

        // Recalculate untung/rugi for affected categories
        foreach ($categoryIds as $categoryId => $params) {
            $this->recalculateCategoryUntungRugi(
                $categoryId,
                $params['type_id'],
                $params['unit_id'],
                $params['location_id']
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    /**
     * Get summary data untuk RAB
     */
    public function getSummary(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $rabItems = RabItem::with('category')
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get();

        $totalHargaBahan = $rabItems->sum('total_harga');
        $totalUpah = $rabItems->sum('upah');

        // Get total borongan dari category borongans
        $totalBorongan = RabCategoryBorongan::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->sum('borongan');

        $totalUntungRugi = $totalBorongan - $totalUpah;

        return response()->json([
            'total_harga_bahan' => $totalHargaBahan,
            'total_upah' => $totalUpah,
            'total_borongan' => $totalBorongan,
            'total_untung_rugi' => $totalUntungRugi,
        ]);
    }

    /**
     * Refresh/Sync harga bahan dari inventory ke RAB items
     */
    public function refreshPrices(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Ambil semua inventory items untuk lookup
        $inventoryItems = InventoryItem::all()->keyBy('nama');

        // Ambil RAB items
        $rabItems = RabItem::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get();

        $updated = 0;
        foreach ($rabItems as $item) {
            // Cari di inventory dengan exact match
            $inventory = $inventoryItems->get($item->uraian);
            
            // Jika tidak ketemu, coba cari dengan LIKE
            if (!$inventory) {
                $inventory = InventoryItem::where('nama', 'LIKE', '%' . $item->uraian . '%')
                    ->orWhere('nama', 'LIKE', '%' . str_replace(' ', '', $item->uraian) . '%')
                    ->first();
            }

            if ($inventory && $inventory->harga > 0) {
                $item->harga_bahan = $inventory->harga;
                $item->total_harga = $item->bahan_out > 0 
                    ? $item->bahan_out * $inventory->harga 
                    : $item->bahan_baku * $inventory->harga;
                $item->save();
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil update harga untuk {$updated} item",
            'updated' => $updated,
        ]);
    }

    /**
     * Reset dan regenerate RAB items (hapus yang lama, buat baru)
     */
    public function regenerate(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Hapus RAB items yang ada
        RabItem::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->delete();

        // Hapus borongan yang ada
        RabCategoryBorongan::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->delete();

        // Generate ulang
        $this->generateRabFromTemplate($typeId, $unitId, $locationId);

        return response()->json([
            'success' => true,
            'message' => 'RAB berhasil di-regenerate',
        ]);
    }
}
