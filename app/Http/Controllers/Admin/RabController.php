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
            $typeValues = RabTypeValue::where('type_id', $typeId)
                ->with(['template.category'])
                ->get();

            foreach ($typeValues as $typeValue) {
                $tpl = $typeValue->template;
                if (!$tpl) continue;

                // Cari harga bahan di inventory
                $inventory = InventoryItem::where('nama', $tpl->item_name)->first();
                $harga = $inventory ? $inventory->harga : 0;

                // Gunakan bahan_baku dari TypeValue
                $bahanBaku = $typeValue->bahan_baku ?? 0;

                // Tampilkan semua item termasuk yang bahan_baku = 0
                // Total harga = 0 karena bahan_out belum diinput
                // Total harga akan dihitung saat bahan_out diisi
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
                $harga = $inventory ? $inventory->harga : 0;

                // Gunakan default bahan_baku dari template
                $bahanBaku = $tpl->default_bahan_baku ?? 0;

                // Total harga = 0 karena bahan_out belum diinput
                // Total harga akan dihitung saat bahan_out diisi
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
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 50 untuk halaman ini
        $type50 = Type::where('nama', '50')->first();
        $typeId     = $type50 ? $type50->id : null;
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
        $fixedType = $type50; // Fixed type untuk halaman ini

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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 50
     */
    public function type50Print(Request $request)
    {
        // Force Type 50
        $type50 = Type::where('nama', '50')->first();
        $typeId     = $type50 ? $type50->id : $request->type_id;
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
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 55 untuk halaman ini
        $type55 = Type::where('nama', '55')->first();
        $typeId     = $type55 ? $type55->id : null;
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
        $fixedType = $type55; // Fixed type untuk halaman ini

        return view('admin.rab.type55', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 55
     */
    public function type55Print(Request $request)
    {
        // Force Type 55
        $type55 = Type::where('nama', '55')->first();
        $typeId     = $type55 ? $type55->id : $request->type_id;
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
     * RAB Type 40 - Halaman Laporan
     */
    public function type40(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 40 untuk halaman ini
        $type40 = Type::where('nama', '40')->first();
        $typeId     = $type40 ? $type40->id : null;
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
        $fixedType = $type40; // Fixed type untuk halaman ini

        return view('admin.rab.type40', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 40
     */
    public function type40Print(Request $request)
    {
        // Force Type 40
        $type40 = Type::where('nama', '40')->first();
        $typeId     = $type40 ? $type40->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type40')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type40-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 45 - Halaman Laporan
     */
    public function type45(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 45 untuk halaman ini
        $type45 = Type::where('nama', '45')->first();
        $typeId     = $type45 ? $type45->id : null;
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
        $fixedType = $type45; // Fixed type untuk halaman ini

        return view('admin.rab.type45', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 45
     */
    public function type45Print(Request $request)
    {
        // Force Type 45
        $type45 = Type::where('nama', '45')->first();
        $typeId     = $type45 ? $type45->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type45')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type45-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 60 - Halaman Laporan
     */
    public function type60(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 60 untuk halaman ini
        $type60 = Type::where('nama', '60')->first();
        $typeId     = $type60 ? $type60->id : null;
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
        $fixedType = $type60; // Fixed type untuk halaman ini

        return view('admin.rab.type60', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 60
     */
    public function type60Print(Request $request)
    {
        // Force Type 60
        $type60 = Type::where('nama', '60')->first();
        $typeId     = $type60 ? $type60->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type60')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type60-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 70 - Halaman Laporan
     */
    public function type70(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 70 untuk halaman ini
        $type70 = Type::where('nama', '70')->first();
        $typeId     = $type70 ? $type70->id : null;
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
        $fixedType = $type70; // Fixed type untuk halaman ini

        return view('admin.rab.type70', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 70
     */
    public function type70Print(Request $request)
    {
        // Force Type 70
        $type70 = Type::where('nama', '70')->first();
        $typeId     = $type70 ? $type70->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type70')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type70-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 80 - Halaman Laporan
     */
    public function type80(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 80 untuk halaman ini
        $type80 = Type::where('nama', '80')->first();
        $typeId     = $type80 ? $type80->id : null;
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
        $fixedType = $type80; // Fixed type untuk halaman ini

        return view('admin.rab.type80', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 80
     */
    public function type80Print(Request $request)
    {
        // Force Type 80
        $type80 = Type::where('nama', '80')->first();
        $typeId     = $type80 ? $type80->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type80')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type80-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 100 - Halaman Laporan
     */
    public function type100(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 100 untuk halaman ini
        $type100 = Type::where('nama', '100')->first();
        $typeId     = $type100 ? $type100->id : null;
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
        $fixedType = $type100; // Fixed type untuk halaman ini

        return view('admin.rab.type100', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 100
     */
    public function type100Print(Request $request)
    {
        // Force Type 100
        $type100 = Type::where('nama', '100')->first();
        $typeId     = $type100 ? $type100->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type100')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type100-print', [
            'rabItems'          => $rabItems,
            'categories'        => $categories,
            'categoryBorongans' => $categoryBorongans,
            'selectedType'      => $selectedType,
            'selectedUnit'      => $selectedUnit,
            'selectedLocation'  => $selectedLocation,
        ]);
    }

    /**
     * RAB Type 107 - Halaman Laporan
     */
    public function type107(Request $request)
    {
        $types     = Type::all();
        $units     = Unit::with('location')->get();
        $locations = Location::all();

        // Force Type 107 untuk halaman ini
        $type107 = Type::where('nama', '107')->first();
        $typeId     = $type107 ? $type107->id : null;
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
        $fixedType = $type107; // Fixed type untuk halaman ini

        return view('admin.rab.type107', [
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
            'fixedType'         => $fixedType,
        ]);
    }

    /**
     * Print view untuk RAB Type 107
     */
    public function type107Print(Request $request)
    {
        // Force Type 107
        $type107 = Type::where('nama', '107')->first();
        $typeId     = $type107 ? $type107->id : $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return redirect()->route('rab.type107')->with('error', 'Silakan pilih Type, Lokasi, dan Unit terlebih dahulu');
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

        return view('admin.rab.type107-print', [
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
     * 
     * LOGIKA:
     * 1. Update harga_bahan dari inventory (jika ada perubahan)
     * 2. Update total_harga = bahan_out × harga_bahan (menggunakan OUT yang sudah diinput)
     * 3. Update bahan_baku dari RabTypeValue jika masih 0 atau kosong
     * 
     * DATA YANG TIDAK AKAN BERUBAH:
     * - bahan_out (OUT) - tetap menggunakan nilai yang sudah diinput user
     * - upah - tidak diubah (disimpan di rab_category_borongans)
     * - progress - tidak diubah (disimpan di rab_category_borongans)
     * - borongan - tidak diubah (disimpan di rab_category_borongans)
     */
    public function refreshPrices(Request $request)
    {
        $typeId     = $request->type_id;
        $unitId     = $request->unit_id;
        $locationId = $request->location_id;

        if (!$typeId || !$unitId || !$locationId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Ambil semua inventory items untuk lookup (lebih efisien)
        $inventoryItems = InventoryItem::all()->keyBy('nama');

        // Ambil semua type values untuk type ini (untuk update bahan_baku jika perlu)
        $typeValues = RabTypeValue::where('type_id', $typeId)
            ->with('template')
            ->get()
            ->keyBy(function($tv) {
                return $tv->template->item_name ?? null;
            });

        // Ambil RAB items yang akan di-update
        $rabItems = RabItem::where('type_id', $typeId)
            ->where('unit_id', $unitId)
            ->where('location_id', $locationId)
            ->get();

        $updated = 0;
        $updatedBahanBaku = 0;
        $notFound = [];

        foreach ($rabItems as $item) {
            $updatedItem = false;
            $oldHargaBahan = $item->harga_bahan;
            
            // ========================================
            // 1. UPDATE BAHAN_BAKU (jika masih 0 atau kosong)
            // ========================================
            if (($item->bahan_baku == 0 || $item->bahan_baku == null) && isset($typeValues[$item->uraian])) {
                $typeValue = $typeValues[$item->uraian];
                $item->bahan_baku = $typeValue->bahan_baku;
                $updatedBahanBaku++;
                $updatedItem = true;
            }
            
            // ========================================
            // 2. CARI HARGA DI INVENTORY
            // ========================================
            // Coba exact match dulu
            $inventory = $inventoryItems->get($item->uraian);
            
            // Jika tidak ketemu, coba dengan LIKE (untuk handle perbedaan spasi/kecil-besar)
            if (!$inventory) {
                $inventory = InventoryItem::where('nama', 'LIKE', '%' . $item->uraian . '%')
                    ->orWhere('nama', 'LIKE', '%' . str_replace(' ', '', $item->uraian) . '%')
                    ->first();
            }

            // ========================================
            // 3. UPDATE HARGA BAHAN DAN TOTAL HARGA
            // ========================================
            if ($inventory && $inventory->harga > 0) {
                // Update harga_bahan dari inventory
                $item->harga_bahan = $inventory->harga;
                
                // Update total_harga = bahan_out × harga_bahan
                // PENTING: Menggunakan bahan_out yang sudah diinput user (tidak diubah)
                $item->total_harga = $item->bahan_out * $inventory->harga;
                
                $updatedItem = true;
            } else {
                // Item tidak ditemukan di inventory
                $notFound[] = $item->uraian;
            }
            
            // ========================================
            // 4. SIMPAN JIKA ADA PERUBAHAN
            // ========================================
            // Hanya save jika ada perubahan harga atau bahan_baku
            // bahan_out, upah, progress TIDAK DIUBAH
            if ($updatedItem) {
                $item->save();
                $updated++;
            }
        }

        // ========================================
        // 5. RESPONSE MESSAGE
        // ========================================
        $message = "Berhasil update harga untuk {$updated} item";
        if ($updatedBahanBaku > 0) {
            $message .= " dan update bahan_baku untuk {$updatedBahanBaku} item";
        }
        if (count($notFound) > 0) {
            $message .= ". " . count($notFound) . " item tidak ditemukan di inventory";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'updated' => $updated,
            'updated_bahan_baku' => $updatedBahanBaku,
            'not_found' => $notFound,
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
