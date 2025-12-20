<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType60Seeder extends Seeder
{
    public function run()
    {
        // Cari type 60
        $type = Type::where('nama', '60')->first();

        if (!$type) {
            echo "⚠ Type 60 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 60 (skala 1.2x dari Type 50)
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 14,
                'Besi ø8 Ulir' => 49,
                'Kayu 3/5' => 42,
                'Cerucuk' => 89,
                'Papan Mal' => 197,
                'Pasir' => 16,
                'Batu' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 46,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 38,
                'Pipa 4"' => 2,
                'Pipa 2"' => 2,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 18,
                'Pasir' => 6,
                'Semen PCC 40 Kg Tiga Roda' => 17,
                'Plastik cor' => 7,
            ],

            // D — PINTU (fixed items)
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 4,
                'Pintu WC PVC Biru' => 2,
                'Pintu WC Edenjoice Putih' => 1,
                'Engsel Pintu 4"' => 18,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 4,
                'Avian' => 5,
                'Tinner Nb Kaleng' => 2,
                'Slot 6"' => 1,
                'Slot 4"' => 1,
            ],

            // E — BATA
            'E' => [
                'Papan' => 29,
                'Kayu 3/5' => 30,
                'Besi ø6' => 12,
                'Besi ø8' => 10,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 4,
                'Bata' => 2795,
                'Pasir' => 6,
                'Semen PCC 40 Kg Tiga Roda' => 23,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 6,
                'Pasir' => 1,
                'Batu' => 2,
                'Besi ø6' => 4,
                'Besi ø8' => 5,
                'Multiplek' => 1,
                'Kayu 3/5' => 24,
                'Bata' => 89,
                'Cerucuk' => 8,
                'Papan Mal' => 7,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 42,
                'Pasir' => 8,
                'Pipa 5/8' => 7,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 72,
                'Kayu 4/6' => 48,
                'Kayu 5/7' => 35,
                'Lisplank' => 11,
                'Seng Metal 4 Susun (merah)' => 82,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 1.8,
                'Paku 2 inch' => 1,
                'Paku 3 inch' => 1,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 103,
                'Gypsum' => 28,
                'Baut' => 5,
                'Paku Beton 4 inch' => 1,
                'Paku 3 inch' => 1,
                'Paku 2 inch' => 1,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 131,
                'Pasir' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 11,
                'Keramik 60 x 60 Blackmatt' => 8,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 26,
                'Kayu 3/5' => 10,
                'Cerucuk' => 6,
                'Papan Mal' => 12,
                'Plastik cor' => 8,
                'Besi ø6' => 2,
                'Besi ø8' => 18,
                'Batu' => 1,
                'Pasir' => 7,
                'Keramik 60 x 60 Blackmatt' => 37,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 38,
                'Keramik 60 x 60 Blackmatt' => 16,
                'Pasir' => 6,
                'Semen PCC 40 Kg Tiga Roda' => 26,
                'Oker' => 6,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30' => 4,
                'Keramik 25 x 40' => 13,
                'Closed jongkok Ina' => 2,
                'Closed Duduk Volk' => 1,
                'Floor drain' => 2,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 5,
                'Bak Air' => 2,
                'Pipa 1/2"' => 2,
                'Lbow 1/2"' => 7,
                'Kran Air Plastik 1/2"' => 4,
                'SDD 1/2"' => 1,
            ],

            // N — ACI MINIMALIS
            'N' => [
                'Semen TR-30 40 Kg Tiga Roda' => 4,
            ],

            // O — CAT
            'O' => [
                'Mowilex weathercoat' => 1,
                'Nippon paint Q-LUC' => 4,
                'Semen TR-30 40 Kg Tiga Roda' => 14,
                'Semen Aci Putih 25 kg' => 1,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 120,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 4,
                'Besi ø6' => 2,
                'Besi ø8' => 5,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 11,
                'Pasir' => 2,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 1,
                'Pipa 2"' => 2,
                'Cekakan pipa 2"' => 2,
                'Lbow 3" ke 2"' => 1,
                'Sambungan 3" ke 2"' => 1,
                'Lbow 2"' => 4,
                'Penutup pipa 3"' => 1,
                'Lem pipa fox' => 1,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 5,
                'Aluminium white ink (m)' => 2,
                'Aluminium white ink (stoper casmen)' => 7,
                'List ornamen 20 mm white' => 7,
                'Klem sedang 13 x 26 x 1,4 mm' => 1,
                'Karet C besar HTM' => 2,
                'Karet cacing HTM' => 2,
                'Rambuncis dks white kanan' => 4,
                'Rambuncis dks white kiri' => 2,
                'Engsel casment glatino 8 inch' => 6,
                'Marks sosis black 620 ml' => 5,
                'Sekrup 8 x 3 rata' => 30,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 48,
                'Sekrup 8 x 1 bulat' => 48,
                'Sekrup 8 x 0,5 rata' => 96,
                'Rivet GT 429' => 1,
                'Fisher S6 vini star' => 1,
                'Reben 5, 203 cm x 102 cm asahi' => 7,
                'Marks sosis white 620 ml' => 5,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 7,
                'Saklar dobel broco' => 1,
                'Stop kontak tunggal broco' => 13,
                'Kabel NYA 1,5 Eterna (100m)' => 1,
                'Kabel NYA 2,5 Eterna (100m)' => 8,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 30,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 42,
                'MCB 10A Powell' => 1,
                'Box MCB tanam 3 grup' => 1,
                'MCB Kwh 1300 amper' => 1,
            ],
        ];

        // ========================================
        // INSERT KE rab_type_values
        // ========================================
        foreach ($valuesByCategory as $categoryKode => $items) {
            $category = $categories->get($categoryKode);
            
            if (!$category) {
                echo "⚠ KATEGORI {$categoryKode} TIDAK DITEMUKAN!\n";
                continue;
            }

            foreach ($items as $itemName => $qty) {
                $tpl = RabTemplate::where('item_name', $itemName)
                    ->where('category_id', $category->id)
                    ->first();

                if (!$tpl) {
                    echo "⚠ ITEM TIDAK DITEMUKAN DI TEMPLATE: {$itemName} (Kategori: {$categoryKode})\n";
                    continue;
                }

                RabTypeValue::updateOrCreate(
                    [
                        'type_id' => $typeId,
                        'rab_template_id' => $tpl->id,
                    ],
                    [
                        'bahan_baku' => $qty,
                    ]
                );
            }
        }

        echo "✔ SELESAI — DATA TYPE 60 BERHASIL DIIMPORT\n";
    }
}
