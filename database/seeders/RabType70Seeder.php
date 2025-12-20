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
        // Cari type 70
        $type = Type::where('nama', '70')->first();

        if (!$type) {
            echo "⚠ Type 70 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 70 (skala 1.4x dari Type 50)
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 17,
                'Besi ø8 Ulir' => 57,
                'Kayu 3/5' => 49,
                'Cerucuk' => 104,
                'Papan Mal' => 230,
                'Pasir' => 18,
                'Batu' => 3,
                'Semen PCC 40 Kg Tiga Roda' => 53,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 45,
                'Pipa 4"' => 3,
                'Pipa 2"' => 3,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 21,
                'Pasir' => 7,
                'Semen PCC 40 Kg Tiga Roda' => 20,
                'Plastik cor' => 8,
            ],

            // D — PINTU (fixed items)
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 4,
                'Pintu WC PVC Biru' => 2,
                'Pintu WC Edenjoice Putih' => 1,
                'Engsel Pintu 4"' => 21,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 4,
                'Avian' => 6,
                'Tinner Nb Kaleng' => 3,
                'Slot 6"' => 1,
                'Slot 4"' => 1,
            ],

            // E — BATA
            'E' => [
                'Papan' => 34,
                'Kayu 3/5' => 35,
                'Besi ø6' => 14,
                'Besi ø8' => 11,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 4,
                'Bata' => 3261,
                'Pasir' => 7,
                'Semen PCC 40 Kg Tiga Roda' => 27,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 7,
                'Pasir' => 1,
                'Batu' => 3,
                'Besi ø6' => 4,
                'Besi ø8' => 6,
                'Multiplek' => 1,
                'Kayu 3/5' => 28,
                'Bata' => 104,
                'Cerucuk' => 10,
                'Papan Mal' => 8,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 49,
                'Pasir' => 10,
                'Pipa 5/8' => 8,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 84,
                'Kayu 4/6' => 56,
                'Kayu 5/7' => 41,
                'Lisplank' => 13,
                'Seng Metal 4 Susun (merah)' => 95,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 2.1,
                'Paku 2 inch' => 1,
                'Paku 3 inch' => 1,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 120,
                'Gypsum' => 32,
                'Baut' => 6,
                'Paku Beton 4 inch' => 1,
                'Paku 3 inch' => 1,
                'Paku 2 inch' => 1,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 153,
                'Pasir' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 13,
                'Keramik 60 x 60 Blackmatt' => 10,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 31,
                'Kayu 3/5' => 11,
                'Cerucuk' => 7,
                'Papan Mal' => 14,
                'Plastik cor' => 10,
                'Besi ø6' => 3,
                'Besi ø8' => 21,
                'Batu' => 1,
                'Pasir' => 8,
                'Keramik 60 x 60 Blackmatt' => 43,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 45,
                'Keramik 60 x 60 Blackmatt' => 18,
                'Pasir' => 7,
                'Semen PCC 40 Kg Tiga Roda' => 31,
                'Oker' => 7,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30' => 4,
                'Keramik 25 x 40' => 15,
                'Closed jongkok Ina' => 2,
                'Closed Duduk Volk' => 1,
                'Floor drain' => 3,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 6,
                'Bak Air' => 2,
                'Pipa 1/2"' => 3,
                'Lbow 1/2"' => 8,
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
                'Semen TR-30 40 Kg Tiga Roda' => 17,
                'Semen Aci Putih 25 kg' => 1,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 140,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 4,
                'Besi ø6' => 3,
                'Besi ø8' => 6,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 13,
                'Pasir' => 3,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 1,
                'Pipa 2"' => 3,
                'Cekakan pipa 2"' => 3,
                'Lbow 3" ke 2"' => 1,
                'Sambungan 3" ke 2"' => 1,
                'Lbow 2"' => 4,
                'Penutup pipa 3"' => 1,
                'Lem pipa fox' => 1,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 6,
                'Aluminium white ink (m)' => 3,
                'Aluminium white ink (stoper casmen)' => 8,
                'List ornamen 20 mm white' => 8,
                'Klem sedang 13 x 26 x 1,4 mm' => 1,
                'Karet C besar HTM' => 3,
                'Karet cacing HTM' => 3,
                'Rambuncis dks white kanan' => 4,
                'Rambuncis dks white kiri' => 3,
                'Engsel casment glatino 8 inch' => 7,
                'Marks sosis black 620 ml' => 6,
                'Sekrup 8 x 3 rata' => 35,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 56,
                'Sekrup 8 x 1 bulat' => 56,
                'Sekrup 8 x 0,5 rata' => 112,
                'Rivet GT 429' => 1,
                'Fisher S6 vini star' => 1,
                'Reben 5, 203 cm x 102 cm asahi' => 8,
                'Marks sosis white 620 ml' => 6,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 8,
                'Saklar dobel broco' => 1,
                'Stop kontak tunggal broco' => 15,
                'Kabel NYA 1,5 Eterna (100m)' => 1,
                'Kabel NYA 2,5 Eterna (100m)' => 10,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 35,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 49,
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

        echo "✔ SELESAI — DATA TYPE 70 BERHASIL DIIMPORT\n";
    }
}
