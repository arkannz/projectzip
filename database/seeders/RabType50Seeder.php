<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType50Seeder extends Seeder
{
    public function run()
    {
        // Cari type 50 dari database
        $type = Type::where('nama', '50')->first();
        
        if (!$type) {
            echo "⚠ Type 50 tidak ditemukan! Jalankan TypeSeeder terlebih dahulu.\n";
            return;
        }
        
        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // -----------------------------------------------
        // 1. DAFTAR BAHAN BAKU TYPE 50 (final dari kamu)
        // Dikelompokkan per kategori untuk menghindari duplicate key overwrites
        // -----------------------------------------------
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 12,
                'Besi ø8 Ulir' => 41,
                'Kayu 3/5' => 35,
                'Cerucuk' => 74,
                'Papan Mal' => 164,
                'Pasir' => 13,
                'Batu' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 38,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 32,
                'Pipa 4"' => 2,
                'Pipa 2"' => 2,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 15,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 14,
                'Plastik cor' => 6,
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
                'Papan' => 24,
                'Kayu 3/5' => 25,
                'Besi ø6' => 10,
                'Besi ø8' => 8,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 3,
                'Bata' => 2329,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 19,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 5,
                'Pasir' => 1,
                'Batu' => 2,
                'Besi ø6' => 3,
                'Besi ø8' => 4,
                'Multiplek' => 1,
                'Kayu 3/5' => 20,
                'Bata' => 74,
                'Cerucuk' => 7,
                'Papan Mal' => 6,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 35,
                'Pasir' => 7,
                'Pipa 5/8' => 6,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 60,
                'Kayu 4/6' => 40,
                'Kayu 5/7' => 29,
                'Lisplank' => 9,
                'Seng Metal 4 Susun (merah)' => 68,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 1.5,
                'Paku 2 inch' => 1,
                'Paku 3 inch' => 1,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 86,
                'Gypsum' => 23,
                'Baut' => 4,
                'Paku Beton 4 inch' => 1,
                'Paku 3 inch' => 1,
                'Paku 2 inch' => 1,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 109,
                'Pasir' => 3,
                'Semen PCC 40 Kg Tiga Roda' => 9,
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
                'Keramik 60 x 60 Cream' => 32,
                'Keramik 60 x 60 Blackmatt' => 13,
                'Pasir' => 5,
                'Semen PCC 40 Kg Tiga Roda' => 22,
                'Oker' => 5,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30' => 3,
                'Keramik 25 x 40' => 11,
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
                'Semen TR-30 40 Kg Tiga Roda' => 12,
                'Semen Aci Putih 25 kg' => 1,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 100,
                'Pasir' => 1,
                'Semen PCC 40 Kg Tiga Roda' => 3,
                'Besi ø6' => 2,
                'Besi ø8' => 4,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 9,
                'Pasir' => 2,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 1,
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
                'MCB 10A Powell' => 1,
                'Box MCB tanam 3 grup' => 1,
                'MCB Kwh 1300 amper' => 1,
            ],
        ];

        // ----------------------------------------------------
        // 2. MATCH KE TEMPLATE & INSERT
        // ----------------------------------------------------
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

        echo "✔ SELESAI — DATA TYPE 50 BERHASIL DIIMPORT\n";
    }
}