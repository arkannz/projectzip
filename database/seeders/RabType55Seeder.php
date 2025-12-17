<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType55Seeder extends Seeder
{
    public function run()
    {
        // Cari type 55
        $type = Type::where('nama', '55')->first();

        if (!$type) {
            echo "⚠ Type 55 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 55 (dari data Excel kamu)
        // Dikelompokkan per kategori untuk menghindari duplicate key overwrites
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 13,
                'Besi ø8 Ulir' => 45,
                'Kayu 3/5' => 38,
                'Cerucuk' => 81,
                'Papan Mal' => 180,
                'Pasir' => 14,
                'Batu' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 42,
            ],

            // B — URUG
            'B' => [
                'Pasir ' => 35,
                'Pipa 4"' => 2,
                'Pipa 2"' => 2,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 17,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 15,
                'Plastik cor' => 7,
            ],

            // D — PINTU
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 3,
                'Pintu WC PVC Biru' => 2,
                'Pintu WC Edenjoice Putih' => 1,
                'Engsel Pintu 4"' => 15,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 3,
                'Avian' => 4,
                'Tinner Nb Kaleng' => 2,
                'Slot 6"' => 1,
                'Slot 4"' => 1,
            ],

            // E — BATA
            'E' => [
                'Papan' => 26,
                'Kayu 3/5' => 28,
                'Besi ø6' => 5,
                'Besi ø8 ' => 9,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 3,
                'Bata' => 2562,
                'Semen PCC 40 Kg Tiga Roda' => 21,
                'Pasir' => 5,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 5,
                'Pasir' => 1,
                'Batu' => 2,
                'Besi ø6 ' => 3,
                'Besi ø8' => 4,
                'Multiplek' => 1,
                'Kayu 3/5' => 22,
                'Bata' => 81,
                'Cerucuk' => 8,
                'Papan Mal' => 7,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 38,
                'Pasir' => 8,
                'Pipa 5/8' => 7,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 66,
                'Kayu 4/6' => 44,
                'Kayu 5/7' => 32,
                'Lisplank' => 10,
                'Seng Metal 4 Susun (merah)' => 75,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 1.5,
                'Paku 2 inch' => 1,
                'Paku 3 inch' => 1,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 95,
                'Gypsum' => 25,
                'Baut' => 4,
                'Paku Beton 4 inch' => 1,
                'Paku 3 inch' => 1,
                'Paku 2 inch' => 1,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 120,
                'Pasir' => 3,
                'Semen PCC 40 Kg Tiga Roda' => 10,
                'Keramik 60 x 60 Blackmatt' => 7,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 22,
                'Kayu 3/5' => 8,
                'Cerucuk' => 5,
                'Papan Mal' => 10,
                'Plastik cor' => 7,
                'Besi ø6' => 2,
                'Besi ø8' => 15,
                'Batu' => 1,
                'Pasir' => 6,
                'Keramik 60 x 60 Blackmatt' => 31,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 35,
                'Keramik 60 x 60 Blackmatt' => 14,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 24,
                'Oker' => 5,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30 ' => 6,
                'Keramik 25 x 40' => 22,
                'Closed jongkok Ina' => 2,
                'Closed Duduk Volk' => 1,
                'Floor drain' => 2,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 4,
                'Bak Air' => 2,
                'Pipa 1/2"' => 2,
                'Lbow 1/2"' => 6,
                'Kran Air Plastik 1/2"' => 3,
                'SDD 1/2"' => 1,
            ],

            // N — ACI MINIMALIS
            'N' => [
                'Semen TR-30 40 Kg Tiga Roda' => 3,
            ],

            // O — CAT
            'O' => [
                'Mowilex weathercoat' => 1,
                'Nippon paint Q-LUC' => 3,
                'Semen TR-30 40 Kg Tiga Roda' => 13,
                'Semen Aci Putih 25 kg' => 1,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 100,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 3,
                'Besi ø6 ' => 2,
                'Besi ø8' => 4,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 9,
                'Pasir' => 2,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 4,
                'Pipa 2"' => 2,
                'Cekakan pipa 2"' => 2,
                'Lbow 3" ke 2"' => 1,
                'Sambungan 3" ke 2"' => 1,
                'Lbow 2"' => 3,
                'Penutup pipa 3"' => 1,
                'Lem pipa fox' => 1,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 4,
                'Aluminium white ink (m)' => 2,
                'Aluminium white ink (stoper casmen)' => 6,
                'List ornamen 20 mm white' => 6,
                'Klem sedang 13 x 26 x 1,4 mm' => 1,
                'Karet C besar HTM' => 2,
                'Karet cacing HTM' => 2,
                'Rambuncis dks white kanan' => 3,
                'Rambuncis dks white kiri' => 2,
                'Engsel casment glatino 8 inch' => 5,
                'Marks sosis black 620 ml' => 4,
                'Sekrup 8 x 3 rata' => 25,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 40,
                'Sekrup 8 x 1 bulat' => 40,
                'Sekrup 8 x 0,5 rata' => 80,
                'Rivet GT 429' => 1,
                'Fisher S6 vini star' => 1,
                'Reben 5, 203 cm x 102 cm asahi' => 6,
                'Marks sosis white 620 ml' => 4,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 6,
                'Saklar dobel broco' => 1,
                'Stop kontak tunggal broco' => 11,
                'Kabel NYA 1,5 Eterna (100m)' => 1,
                'Kabel NYA 2,5 Eterna (100m)' => 7,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 25,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 35,
                'Mcb 10A Powell' => 1,
                'Box Mcb tanam 3 grup' => 1,
                'Mcb Kwh 1300 amper' => 1,
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
                // Cari template berdasarkan item_name DAN category_id untuk memastikan template yang benar
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

        echo "✔ SELESAI — DATA TYPE 55 BERHASIL DIIMPORT\n";
    }
}