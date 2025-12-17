<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType45Seeder extends Seeder
{
    public function run()
    {
        // Cari type 45 dari database
        $type = Type::where('nama', '45')->first();
        
        if (!$type) {
            echo "⚠ Type 45 tidak ditemukan! Jalankan TypeSeeder terlebih dahulu.\n";
            return;
        }
        
        $typeId = $type->id;

        // Ambil semua template dan set bahan_baku = 0
        $templates = RabTemplate::all();

        foreach ($templates as $tpl) {
            RabTypeValue::updateOrCreate(
                [
                    'type_id' => $typeId,
                    'rab_template_id' => $tpl->id,
                ],
                [
                    'bahan_baku' => 0,
                ]
            );
        }

        echo "✔ SELESAI — DATA TYPE 45 BERHASIL DIIMPORT (bahan_baku kosong)\n";
    }
}

