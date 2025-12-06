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
use App\Models\InventoryItem;

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
    }
}
