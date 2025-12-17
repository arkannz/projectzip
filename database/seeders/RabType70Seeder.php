<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType70Seeder extends Seeder
{
    public function run()
    {
        // Cari type 70 dari database
        $type = Type::where('nama', '70')->first();
        
        if (!$type) {
            echo "⚠ Type 70 tidak ditemukan! Jalankan TypeSeeder terlebih dahulu.\n";
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

        echo "✔ SELESAI — DATA TYPE 70 BERHASIL DIIMPORT (bahan_baku kosong)\n";
    }
}

