<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType40Seeder extends Seeder
{
    public function run()
    {
        // Cari type 40
        $type = Type::where('nama', '40')->first();

        if (!$type) {
            echo "⚠ Type 40 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 40 (skala 0.8x dari Type 50)
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 10,
                'Besi ø8 Ulir' => 33,
                'Kayu 3/5' => 28,
                'Cerucuk' => 59,
                'Papan Mal' => 131,
                'Pasir' => 10,
                'Batu' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 30,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 26,
                'Pipa 4"' => 2,
                'Pipa 2"' => 2,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 12,
                'Pasir' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 11,
                'Plastik cor' => 5,
            ],

            // D — PINTU (fixed items - tidak diskala)
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 2,
                'Pintu WC PVC Biru' => 1,
                'Pintu WC Edenjoice Putih' => 1,
                'Engsel Pintu 4"' => 12,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 2,
                'Avian' => 3,
                'Tinner Nb Kaleng' => 2,
                'Slot 6"' => 1,
                'Slot 4"' => 1,
            ],

            // E — BATA
            'E' => [
                'Papan' => 19,
                'Kayu 3/5' => 20,
                'Besi ø6' => 8,
                'Besi ø8' => 6,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 2,
                'Bata' => 1863,
                'Pasir' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 15,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 4,
                'Pasir' => 1,
                'Batu' => 2,
                'Besi ø6' => 2,
                'Besi ø8' => 3,
                'Multiplek' => 1,
                'Kayu 3/5' => 16,
                'Bata' => 59,
                'Cerucuk' => 6,
                'Papan Mal' => 5,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 28,
                'Pasir' => 6,
                'Pipa 5/8' => 5,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 48,
                'Kayu 4/6' => 32,
                'Kayu 5/7' => 23,
                'Lisplank' => 7,
                'Seng Metal 4 Susun (merah)' => 54,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 1.2,
                'Paku 2 inch' => 1,
                'Paku 3 inch' => 1,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 69,
                'Gypsum' => 18,
                'Baut' => 3,
                'Paku Beton 4 inch' => 1,
                'Paku 3 inch' => 1,
                'Paku 2 inch' => 1,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 87,
                'Pasir' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 7,
                'Keramik 60 x 60 Blackmatt' => 6,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 18,
                'Kayu 3/5' => 6,
                'Cerucuk' => 4,
                'Papan Mal' => 8,
                'Plastik cor' => 6,
                'Besi ø6' => 2,
                'Besi ø8' => 12,
                'Batu' => 1,
                'Pasir' => 5,
                'Keramik 60 x 60 Blackmatt' => 25,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 26,
                'Keramik 60 x 60 Blackmatt' => 10,
                'Pasir' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 18,
                'Oker' => 4,
            ],

            // M — WC (fixed items)
            'M' => [
                'Keramik 30 x 30' => 2,
                'Keramik 25 x 40' => 9,
                'Closed jongkok Ina' => 1,
                'Closed Duduk Volk' => 1,
                'Floor drain' => 1,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 3,
                'Bak Air' => 1,
                'Pipa 1/2"' => 2,
                'Lbow 1/2"' => 5,
                'Kran Air Plastik 1/2"' => 2,
                'SDD 1/2"' => 1,
            ],

            // N — ACI MINIMALIS
            'N' => [
                'Semen TR-30 40 Kg Tiga Roda' => 2,
            ],

            // O — CAT
            'O' => [
                'Mowilex weathercoat' => 1,
                'Nippon paint Q-LUC' => 2,
                'Semen TR-30 40 Kg Tiga Roda' => 10,
                'Semen Aci Putih 25 kg' => 1,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 80,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 2,
                'Besi ø6' => 2,
                'Besi ø8' => 3,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 7,
                'Pasir' => 2,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 1,
                'Pipa 2"' => 2,
                'Cekakan pipa 2"' => 2,
                'Lbow 3" ke 2"' => 1,
                'Sambungan 3" ke 2"' => 1,
                'Lbow 2"' => 2,
                'Penutup pipa 3"' => 1,
                'Lem pipa fox' => 1,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 3,
                'Aluminium white ink (m)' => 2,
                'Aluminium white ink (stoper casmen)' => 5,
                'List ornamen 20 mm white' => 5,
                'Klem sedang 13 x 26 x 1,4 mm' => 1,
                'Karet C besar HTM' => 2,
                'Karet cacing HTM' => 2,
                'Rambuncis dks white kanan' => 2,
                'Rambuncis dks white kiri' => 2,
                'Engsel casment glatino 8 inch' => 4,
                'Marks sosis black 620 ml' => 3,
                'Sekrup 8 x 3 rata' => 20,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 32,
                'Sekrup 8 x 1 bulat' => 32,
                'Sekrup 8 x 0,5 rata' => 64,
                'Rivet GT 429' => 1,
                'Fisher S6 vini star' => 1,
                'Reben 5, 203 cm x 102 cm asahi' => 5,
                'Marks sosis white 620 ml' => 3,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 5,
                'Saklar dobel broco' => 1,
                'Stop kontak tunggal broco' => 9,
                'Kabel NYA 1,5 Eterna (100m)' => 1,
                'Kabel NYA 2,5 Eterna (100m)' => 6,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 20,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 28,
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

        echo "✔ SELESAI — DATA TYPE 40 BERHASIL DIIMPORT\n";
    }
}
