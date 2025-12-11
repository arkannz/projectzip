<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Location;
use App\Models\RabCategory;
use App\Models\RabCategoryBorongan;
use Database\Seeders\RabCategoryBoronganSeeder;

class UpdateBoronganType50 extends Command
{
    protected $signature = 'rab:update-borongan-type50 {--type=50} {--unit=} {--location=}';
    protected $description = 'Update borongan values for Type 50 from seeder data';

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

        // Ambil data borongan dari seeder
        $boronganData = RabCategoryBoronganSeeder::getBoronganData();
        
        if (!isset($boronganData[$typeName])) {
            $this->error("Data borongan untuk Type {$typeName} tidak ditemukan!");
            return 1;
        }

        $typeBorongan = $boronganData[$typeName];
        $categories = RabCategory::all();

        // Jika unit dan location tidak ditentukan, update untuk semua kombinasi
        if (!$unitId || !$locationId) {
            $units = Unit::all();
            $locations = Location::all();

            $this->info("Update borongan untuk semua kombinasi Unit x Location...");

            foreach ($units as $unit) {
                foreach ($locations as $location) {
                    $this->updateBoronganForUnitLocation($typeId, $unit->id, $location->id, $categories, $typeBorongan);
                }
            }
        } else {
            $this->updateBoronganForUnitLocation($typeId, $unitId, $locationId, $categories, $typeBorongan);
        }

        $this->info("✔ Update borongan selesai!");
        return 0;
    }

    protected function updateBoronganForUnitLocation($typeId, $unitId, $locationId, $categories, $typeBorongan)
    {
        $unit = \App\Models\Unit::find($unitId);
        $location = \App\Models\Location::find($locationId);

        $unitName = $unit ? $unit->kode_unit : 'N/A';
        $locationName = $location ? $location->nama : 'N/A';
        $this->line("Updating: Type {$typeId}, Unit {$unitId} ({$unitName}), Location {$locationId} ({$locationName})");

        $updatedCount = 0;

        foreach ($categories as $category) {
            $kode = $category->kode;
            $nilaiBorongan = $typeBorongan[$kode] ?? 0;

            // Update atau create borongan
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
            $updatedCount++;
        }

        $this->info("  → Updated: {$updatedCount} categories");
    }
}

