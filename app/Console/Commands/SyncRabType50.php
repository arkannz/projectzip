<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Location;
use App\Models\RabItem;
use App\Models\RabTypeValue;
use App\Models\RabTemplate;
use App\Models\InventoryItem;

class SyncRabType50 extends Command
{
    protected $signature = 'rab:sync-type50 {--type=50} {--unit=} {--location=}';
    protected $description = 'Sync RAB Type 50 data from seeder to rab_items';

    public function handle()
    {
        $typeName = $this->option('type');
        $unitId = $this->option('unit');
        $locationId = $this->option('location');

        // Cari type
        $type = Type::where('nama', $typeName)->first();
        if (!$type) {
            $this->error("Type {$typeName} tidak ditemukan!");
            return 1;
        }

        $typeId = $type->id;
        $this->info("Type ID: {$typeId}");

        // Ambil semua RabTypeValue untuk type ini
        $typeValues = RabTypeValue::where('type_id', $typeId)
            ->with('template')
            ->get();

        $this->info("Total RabTypeValue: " . $typeValues->count());

        if ($typeValues->isEmpty()) {
            $this->warn("Tidak ada data di rab_type_values untuk type {$typeName}!");
            $this->info("Jalankan: php artisan db:seed --class=RabType50Seeder");
            return 1;
        }

        // Jika unit dan location tidak ditentukan, sync untuk semua kombinasi
        if (!$unitId || !$locationId) {
            $units = Unit::all();
            $locations = Location::all();

            $this->info("Sync untuk semua kombinasi Unit x Location...");

            foreach ($units as $unit) {
                foreach ($locations as $location) {
                    $this->syncForUnitLocation($typeId, $unit->id, $location->id, $typeValues);
                }
            }
        } else {
            $this->syncForUnitLocation($typeId, $unitId, $locationId, $typeValues);
        }

        $this->info("✔ Sync selesai!");
        return 0;
    }

    protected function syncForUnitLocation($typeId, $unitId, $locationId, $typeValues)
    {
        $unit = \App\Models\Unit::find($unitId);
        $location = \App\Models\Location::find($locationId);

        $unitName = $unit ? $unit->kode_unit : 'N/A';
        $locationName = $location ? $location->nama : 'N/A';
        $this->line("Syncing: Type {$typeId}, Unit {$unitId} ({$unitName}), Location {$locationId} ({$locationName})");

        $updatedCount = 0;
        $createdCount = 0;

        foreach ($typeValues as $typeValue) {
            $tpl = $typeValue->template;
            
            if (!$tpl) {
                continue;
            }

            // Cari harga bahan di inventory
            $inventory = InventoryItem::where('nama', $tpl->item_name)->first();
            $harga = $inventory ? $inventory->harga : 0;

            // Gunakan bahan_baku dari TypeValue
            $bahanBaku = $typeValue->bahan_baku;

            // Cari RabItem berdasarkan kategori DAN uraian
            // PENTING: Beberapa item bisa punya nama sama di kategori berbeda
            $rabItem = RabItem::where('type_id', $typeId)
                ->where('unit_id', $unitId)
                ->where('location_id', $locationId)
                ->where('rab_category_id', $tpl->category_id) // TAMBAHKAN KATEGORI!
                ->where('uraian', $tpl->item_name)
                ->first();

            if ($rabItem) {
                $oldBahanBaku = $rabItem->bahan_baku;
                $oldBahanOut = $rabItem->bahan_out;
                
                $rabItem->bahan_baku = $bahanBaku;
                
                if ($rabItem->harga_bahan != $harga && $harga > 0) {
                    $rabItem->harga_bahan = $harga;
                }
                
                if ($oldBahanOut == $oldBahanBaku || $oldBahanOut == 0) {
                    $rabItem->bahan_out = 0;
                    $rabItem->total_harga = 0;
                } else {
                    $rabItem->total_harga = $rabItem->bahan_out * $harga;
                }
                
                $rabItem->save();
                $updatedCount++;
            } else {
                RabItem::create([
                    'type_id'          => $typeId,
                    'unit_id'          => $unitId,
                    'location_id'      => $locationId,
                    'rab_category_id'  => $tpl->category_id,
                    'uraian'           => $tpl->item_name,
                    'bahan_baku'       => $bahanBaku,
                    'bahan_out'        => 0,
                    'harga_bahan'      => $harga,
                    'total_harga'      => 0,
                    'upah'             => 0,
                    'borongan'         => 0,
                    'untung_rugi'      => 0,
                    'progres'          => 0,
                ]);
                $createdCount++;
            }
        }

        $this->info("  → Updated: {$updatedCount}, Created: {$createdCount}");
    }
}

