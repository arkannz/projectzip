<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType80Seeder extends Seeder
{
    public function run()
    {
        // Cari type 80
        $type = Type::where('nama', '80')->first();

        if (!$type) {
            echo "⚠ Type 80 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 80 (skala 1.6x dari Type 50)
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 19,
                'Besi ø8 Ulir' => 66,
                'Kayu 3/5' => 56,
                'Cerucuk' => 118,
                'Papan Mal' => 262,
                'Pasir' => 21,
                'Batu' => 3,
                'Semen PCC 40 Kg Tiga Roda' => 61,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 51,
                'Pipa 4"' => 3,
                'Pipa 2"' => 3,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 24,
                'Pasir' => 8,
                'Semen PCC 40 Kg Tiga Roda' => 22,
                'Plastik cor' => 10,
            ],

            // D — PINTU (fixed items)
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 5,
                'Pintu WC PVC Biru' => 2,
                'Pintu WC Edenjoice Putih' => 2,
                'Engsel Pintu 4"' => 24,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 5,
                'Avian' => 6,
                'Tinner Nb Kaleng' => 3,
                'Slot 6"' => 1,
                'Slot 4"' => 2,
            ],

            // E — BATA
            'E' => [
                'Papan' => 38,
                'Kayu 3/5' => 40,
                'Besi ø6' => 16,
                'Besi ø8' => 13,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 5,
                'Bata' => 3726,
                'Pasir' => 8,
                'Semen PCC 40 Kg Tiga Roda' => 30,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 8,
                'Pasir' => 2,
                'Batu' => 3,
                'Besi ø6' => 5,
                'Besi ø8' => 6,
                'Multiplek' => 2,
                'Kayu 3/5' => 32,
                'Bata' => 118,
                'Cerucuk' => 11,
                'Papan Mal' => 10,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 56,
                'Pasir' => 11,
                'Pipa 5/8' => 10,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 96,
                'Kayu 4/6' => 64,
                'Kayu 5/7' => 46,
                'Lisplank' => 14,
                'Seng Metal 4 Susun (merah)' => 109,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 2.4,
                'Paku 2 inch' => 2,
                'Paku 3 inch' => 2,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 138,
                'Gypsum' => 37,
                'Baut' => 6,
                'Paku Beton 4 inch' => 2,
                'Paku 3 inch' => 2,
                'Paku 2 inch' => 2,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 174,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 14,
                'Keramik 60 x 60 Blackmatt' => 11,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 35,
                'Kayu 3/5' => 13,
                'Cerucuk' => 8,
                'Papan Mal' => 16,
                'Plastik cor' => 11,
                'Besi ø6' => 3,
                'Besi ø8' => 24,
                'Batu' => 2,
                'Pasir' => 10,
                'Keramik 60 x 60 Blackmatt' => 50,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 51,
                'Keramik 60 x 60 Blackmatt' => 21,
                'Pasir' => 8,
                'Semen PCC 40 Kg Tiga Roda' => 35,
                'Oker' => 8,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30' => 5,
                'Keramik 25 x 40' => 18,
                'Closed jongkok Ina' => 2,
                'Closed Duduk Volk' => 2,
                'Floor drain' => 3,
                'Pasir' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 6,
                'Bak Air' => 3,
                'Pipa 1/2"' => 3,
                'Lbow 1/2"' => 10,
                'Kran Air Plastik 1/2"' => 5,
                'SDD 1/2"' => 2,
            ],

            // N — ACI MINIMALIS
            'N' => [
                'Semen TR-30 40 Kg Tiga Roda' => 5,
            ],

            // O — CAT
            'O' => [
                'Mowilex weathercoat' => 2,
                'Nippon paint Q-LUC' => 5,
                'Semen TR-30 40 Kg Tiga Roda' => 19,
                'Semen Aci Putih 25 kg' => 2,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 160,
                'Pasir' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 5,
                'Besi ø6' => 3,
                'Besi ø8' => 6,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 14,
                'Pasir' => 3,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 2,
                'Pipa 2"' => 3,
                'Cekakan pipa 2"' => 3,
                'Lbow 3" ke 2"' => 2,
                'Sambungan 3" ke 2"' => 2,
                'Lbow 2"' => 5,
                'Penutup pipa 3"' => 2,
                'Lem pipa fox' => 2,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 6,
                'Aluminium white ink (m)' => 3,
                'Aluminium white ink (stoper casmen)' => 10,
                'List ornamen 20 mm white' => 10,
                'Klem sedang 13 x 26 x 1,4 mm' => 2,
                'Karet C besar HTM' => 3,
                'Karet cacing HTM' => 3,
                'Rambuncis dks white kanan' => 5,
                'Rambuncis dks white kiri' => 3,
                'Engsel casment glatino 8 inch' => 8,
                'Marks sosis black 620 ml' => 6,
                'Sekrup 8 x 3 rata' => 40,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 64,
                'Sekrup 8 x 1 bulat' => 64,
                'Sekrup 8 x 0,5 rata' => 128,
                'Rivet GT 429' => 2,
                'Fisher S6 vini star' => 2,
                'Reben 5, 203 cm x 102 cm asahi' => 10,
                'Marks sosis white 620 ml' => 6,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 10,
                'Saklar dobel broco' => 2,
                'Stop kontak tunggal broco' => 18,
                'Kabel NYA 1,5 Eterna (100m)' => 2,
                'Kabel NYA 2,5 Eterna (100m)' => 11,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 40,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 56,
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

        echo "✔ SELESAI — DATA TYPE 80 BERHASIL DIIMPORT\n";
    }
}
