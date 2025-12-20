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

        // Cek apakah type ini memiliki RabTypeValue (seperti type 55)
        $hasTypeValues = RabTypeValue::where('type_id', $typeId)->exists();

        if ($hasTypeValues) {
            // Untuk type yang memiliki RabTypeValue (seperti type 55), hanya generate item yang ada di RabTypeValue
            // Urutkan berdasarkan category_id dan rab_template_id agar sesuai urutan di template seeder
            $typeValues = RabTypeValue::where('type_id', $typeId)
                ->with(['template.category'])
                ->join('rab_templates', 'rab_type_values.rab_template_id', '=', 'rab_templates.id')
                ->orderBy('rab_templates.category_id')
                ->orderBy('rab_templates.id')
                ->select('rab_type_values.*')
                ->get();

            foreach ($typeValues as $typeValue) {
                $tpl = $typeValue->template;
                if (!$tpl) continue;

                // Cari harga bahan di inventory
                $inventory = InventoryItem::where('nama', $tpl->item_name)->first();
                $harga = $inventory ? (float)$inventory->harga : 0;

                // Gunakan bahan_baku dari TypeValue (bisa desimal seperti 1.5)
                $bahanBaku = (float)($typeValue->bahan_baku ?? 0);

                // Jangan skip item dengan bahan_baku = 0, tetap tampilkan
                // Total harga HANYA dihitung dari bahan_out * harga_bahan
                // Saat generate, bahan_out = 0, jadi total_harga = 0
                $totalHarga = 0;

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
        } else {
            // Untuk type yang tidak memiliki RabTypeValue (seperti type 50), generate semua template
        $templates = RabTemplate::with(['category', 'inventoryItem'])
            ->orderBy('category_id')
            ->orderBy('id')
            ->get();

        foreach ($templates as $tpl) {
            // Cari harga bahan di inventory
            $inventory = InventoryItem::where('nama', $tpl->item_name)->first();
                $harga = $inventory ? (float)$inventory->harga : 0;

                // Gunakan default bahan_baku dari template (bisa desimal)
                $bahanBaku = (float)($tpl->default_bahan_baku ?? 0);

                // Total harga HANYA dihitung dari bahan_out * harga_bahan
                // Saat generate, bahan_out = 0, jadi total_harga = 0
                $totalHarga = 0;

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
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Otomatis cari Type 50
        $fixedType = Type::where('nama', '50')->first();
        if (!$fixedType) {
            return redirect()->route('rab.index')->with('error', 'Type 50 tidak ditemukan di database.');
        }
        
        $typeId     = $request->type_id ?: $fixedType->id; // Gunakan fixedType jika tidak ada di request
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
            'units'             => $units,
            'locations'         => $locations,
            'type_id'           => $typeId,
            'unit_id'           => $unitId,
            'location_id'       => $locationId,
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType ?: $fixedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
            'fixedType'         => $fixedType,
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
     * RAB Type 55 - Halaman Laporan Printable
     */
    public function type55(Request $request)
    {
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Otomatis cari Type 55
        $fixedType = Type::where('nama', '55')->first();
        if (!$fixedType) {
            return redirect()->route('rab.index')->with('error', 'Type 55 tidak ditemukan di database.');
        }
        
        $typeId     = $request->type_id ?: $fixedType->id; // Gunakan fixedType jika tidak ada di request
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

        return view('admin.rab.type55', [
            'units'             => $units,
            'locations'         => $locations,
            'type_id'           => $typeId,
            'unit_id'           => $unitId,
            'location_id'       => $locationId,
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType ?: $fixedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * RAB Type by Number - Method dinamis untuk semua type
     */
    public function typeByNumber(Request $request, $typeNumber)
    {
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Otomatis cari Type berdasarkan nomor
        $fixedType = Type::where('nama', $typeNumber)->first();
        if (!$fixedType) {
            return redirect()->route('rab.index')->with('error', "Type {$typeNumber} tidak ditemukan di database.");
        }
        
        $typeId     = $request->type_id ?: $fixedType->id; // Gunakan fixedType jika tidak ada di request
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

        // Gunakan view yang sesuai dengan type number
        $viewName = "admin.rab.type{$typeNumber}";

        return view($viewName, [
            'units'             => $units,
            'locations'         => $locations,
            'type_id'           => $typeId,
            'unit_id'           => $unitId,
            'location_id'       => $locationId,
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType ?: $fixedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type by Number - Method dinamis untuk semua type
     */
    public function typeByNumberPrint(Request $request, $typeNumber)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route("rab.type{$typeNumber}")->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        // Gunakan view print yang sesuai dengan type number
        $viewName = "admin.rab.type{$typeNumber}-print";

        return view($viewName, [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * Print view untuk RAB Type 55
     */
    public function type55Print(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type55')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type55-print', [
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
     * Update borongan, upah, progress per category
     */
    public function updateCategoryBorongan(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:rab_categories,id',
            'type_id'      => 'required|exists:types,id',
            'unit_id'      => 'required|exists:units,id',
            'location_id'  => 'required|exists:locations,id',
            'borongan'     => 'nullable|numeric|min:0',
            'upah'         => 'nullable|numeric|min:0',
            'progress'     => 'nullable|numeric|min:0|max:100',
        ]);

        $updateData = [];
        
        if ($request->has('borongan')) {
            $updateData['borongan'] = $request->borongan;
        }
        if ($request->has('upah')) {
            $updateData['upah'] = $request->upah;
        }
        if ($request->has('progress')) {
            $updateData['progress'] = $request->progress;
        }

        $categoryBorongan = RabCategoryBorongan::updateOrCreate(
            [
                'rab_category_id' => $request->category_id,
                'type_id'         => $request->type_id,
                'unit_id'         => $request->unit_id,
                'location_id'     => $request->location_id,
            ],
            $updateData
        );

        // Hitung ulang untung/rugi untuk kategori ini
        $this->recalculateCategoryUntungRugiNew(
            $request->category_id,
            $request->type_id,
            $request->unit_id,
            $request->location_id
        );

        return response()->json([
            'success' => true,
            'data' => $categoryBorongan,
            'message' => 'Data kategori berhasil diupdate'
        ]);
    }

    /**
     * Recalculate untung/rugi for a category (new version - using category upah)
     */
    protected function recalculateCategoryUntungRugiNew($categoryId, $typeId, $unitId, $locationId)
    {
        // Get category borongan data
        $categoryBorongan = RabCategoryBorongan::where('rab_category_id', $categoryId)
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->first();

        if (!$categoryBorongan) {
            return;
        }

        $borongan = $categoryBorongan->borongan ?? 0;
        $upah = $categoryBorongan->upah ?? 0;

        // Untung/Rugi = Borongan - Upah
        $untungRugi = $borongan - $upah;

        // Update semua items di kategori ini dengan untung_rugi yang sama
        RabItem::where('rab_category_id', $categoryId)
            ->where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->update(['untung_rugi' => $untungRugi, 'borongan' => $borongan]);
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
                // Total harga HANYA dihitung dari bahan_out * harga_bahan
                if ($itemData['bahan_out'] == 0) {
                    $item->total_harga = 0;
                } else {
                    $item->total_harga = round($item->bahan_out * $item->harga_bahan, 2);
                }
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
     * Juga update bahan_baku dari RabTypeValue jika masih 0
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

        // Ambil semua templates untuk lookup bahan_baku
        $templates = RabTemplate::all()->keyBy('item_name');
        
        // Ambil semua type values untuk type ini
        $typeValues = RabTypeValue::where('type_id', $typeId)
            ->with('template')
            ->get();

        // Ambil RAB items
        $rabItems = RabItem::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get();

        $updated = 0;
        $updatedBahanBaku = 0;
        foreach ($rabItems as $item) {
            $updatedItem = false;
            
            // Update bahan_baku dari type values (selalu update jika ada di type values)
            // Cari type value berdasarkan uraian dan category untuk lebih akurat
            $typeValue = $typeValues->first(function($tv) use ($item) {
                return $tv->template && 
                       $tv->template->item_name === $item->uraian &&
                       $tv->template->category_id === $item->rab_category_id;
            });
            
            if ($typeValue) {
                $item->bahan_baku = (float)$typeValue->bahan_baku;
                $updatedBahanBaku++;
                $updatedItem = true;
            }
            
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
                // Total harga HANYA dihitung dari bahan_out * harga_bahan
                if ($item->bahan_out == 0) {
                    $item->total_harga = 0;
                } else {
                    $item->total_harga = round((float)$item->bahan_out * (float)$inventory->harga, 2);
                }
                $updatedItem = true;
            }
            
            if ($updatedItem) {
                $item->save();
                $updated++;
            }
        }

        $message = "Berhasil update harga untuk {$updated} item";
        if ($updatedBahanBaku > 0) {
            $message .= " dan update bahan_baku untuk {$updatedBahanBaku} item";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'updated' => $updated,
            'updated_bahan_baku' => $updatedBahanBaku,
        ]);
    }

    /**
     * Update bahan_baku di rab_items berdasarkan rab_type_values terbaru
     * Tanpa menghapus data yang sudah ada (bahan_out, dll)
     */
    public function updateBahanBaku(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->locationId;

        if (!$typeId || !$unitId || !$locationId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Ambil semua type values untuk type ini
        $typeValues = RabTypeValue::where('type_id', $typeId)
            ->with('template')
            ->get()
            ->keyBy(function($tv) {
                return $tv->template ? $tv->template->item_name : null;
            });

        // Ambil RAB items
        $rabItems = RabItem::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get();

        $updated = 0;
        foreach ($rabItems as $item) {
            // Cari type value berdasarkan uraian dan category
            $typeValue = $typeValues->first(function($tv) use ($item) {
                return $tv->template && 
                       $tv->template->item_name === $item->uraian &&
                       $tv->template->category_id === $item->rab_category_id;
            });

            if ($typeValue) {
                // Update bahan_baku dari type value
                $item->bahan_baku = (float)$typeValue->bahan_baku;
                
                // Update total_harga jika bahan_out = 0 (belum digunakan)
                if ($item->bahan_out == 0) {
                    $item->total_harga = round($item->bahan_baku * $item->harga_bahan, 2);
                }
                
                $item->save();
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil update {$updated} item dengan bahan_baku terbaru",
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

        // Ambil type untuk mendapatkan nama type
        $type = Type::find($typeId);
        $typeName = $type ? $type->nama : '';

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
            'message' => 'RAB berhasil di-regenerate dengan borongan terbaru',
        ]);
    }
}
