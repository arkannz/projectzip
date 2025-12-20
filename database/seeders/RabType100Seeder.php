<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;

class RabType100Seeder extends Seeder
{
    public function run()
    {
        // Cari type 100
        $type = Type::where('nama', '100')->first();

        if (!$type) {
            echo "⚠ Type 100 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 100 (skala 2.0x dari Type 50)
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                'Besi ø6' => 24,
                'Besi ø8 Ulir' => 82,
                'Kayu 3/5' => 70,
                'Cerucuk' => 148,
                'Papan Mal' => 328,
                'Pasir' => 26,
                'Batu' => 4,
                'Semen PCC 40 Kg Tiga Roda' => 76,
            ],

            // B — URUG
            'B' => [
                'Pasir' => 64,
                'Pipa 4"' => 4,
                'Pipa 2"' => 4,
            ],

            // C — COR LANTAI
            'C' => [
                'Besi ø6' => 30,
                'Pasir' => 10,
                'Semen PCC 40 Kg Tiga Roda' => 28,
                'Plastik cor' => 12,
            ],

            // D — PINTU (fixed items)
            'D' => [
                'Pintu Kayu 120 x 220' => 1,
                'Pintu Kayu 80 x 220' => 6,
                'Pintu WC PVC Biru' => 3,
                'Pintu WC Edenjoice Putih' => 2,
                'Engsel Pintu 4"' => 30,
                'Peganggan pintu 2 daun' => 2,
                'Pengunci Pintu 2 daun' => 1,
                'Pegangan kunci 1 daun' => 6,
                'Avian' => 8,
                'Tinner Nb Kaleng' => 4,
                'Slot 6"' => 1,
                'Slot 4"' => 2,
            ],

            // E — BATA
            'E' => [
                'Papan' => 48,
                'Kayu 3/5' => 50,
                'Besi ø6' => 20,
                'Besi ø8' => 16,
                'Kusen Pintu (2 Daun)' => 1,
                'Kusen Pintu (1 Daun)' => 6,
                'Bata' => 4658,
                'Pasir' => 10,
                'Semen PCC 40 Kg Tiga Roda' => 38,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                'Semen PCC 40 Kg Tiga Roda' => 10,
                'Pasir' => 2,
                'Batu' => 4,
                'Besi ø6' => 6,
                'Besi ø8' => 8,
                'Multiplek' => 2,
                'Kayu 3/5' => 40,
                'Bata' => 148,
                'Cerucuk' => 14,
                'Papan Mal' => 12,
            ],

            // G — PLASTER
            'G' => [
                'Semen PCC 40 Kg Tiga Roda' => 70,
                'Pasir' => 14,
                'Pipa 5/8' => 12,
            ],

            // H — ATAP
            'H' => [
                'Kayu 3/5' => 120,
                'Kayu 4/6' => 80,
                'Kayu 5/7' => 58,
                'Lisplank' => 18,
                'Seng Metal 4 Susun (merah)' => 136,
                'Seng Metal 2 Susun (hitam)' => 0,
                'Perabung Hitam' => 0,
                'Perabung Merah' => 3,
                'Paku 2 inch' => 2,
                'Paku 3 inch' => 2,
            ],

            // I — DEK
            'I' => [
                'Kayu 3/5' => 172,
                'Gypsum' => 46,
                'Baut' => 8,
                'Paku Beton 4 inch' => 2,
                'Paku 3 inch' => 2,
                'Paku 2 inch' => 2,
            ],

            // J — MINIMALIS
            'J' => [
                'Bata' => 218,
                'Pasir' => 6,
                'Semen PCC 40 Kg Tiga Roda' => 18,
                'Keramik 60 x 60 Blackmatt' => 14,
            ],

            // K — CARPORT
            'K' => [
                'Semen PCC 40 Kg Tiga Roda' => 44,
                'Kayu 3/5' => 16,
                'Cerucuk' => 10,
                'Papan Mal' => 20,
                'Plastik cor' => 14,
                'Besi ø6' => 4,
                'Besi ø8' => 30,
                'Batu' => 2,
                'Pasir' => 12,
                'Keramik 60 x 60 Blackmatt' => 62,
            ],

            // L — KERAMIK
            'L' => [
                'Keramik 60 x 60 Cream' => 64,
                'Keramik 60 x 60 Blackmatt' => 26,
                'Pasir' => 10,
                'Semen PCC 40 Kg Tiga Roda' => 44,
                'Oker' => 10,
            ],

            // M — WC
            'M' => [
                'Keramik 30 x 30' => 6,
                'Keramik 25 x 40' => 22,
                'Closed jongkok Ina' => 3,
                'Closed Duduk Volk' => 2,
                'Floor drain' => 4,
                'Pasir' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 8,
                'Bak Air' => 4,
                'Pipa 1/2"' => 4,
                'Lbow 1/2"' => 12,
                'Kran Air Plastik 1/2"' => 6,
                'SDD 1/2"' => 2,
            ],

            // N — ACI MINIMALIS
            'N' => [
                'Semen TR-30 40 Kg Tiga Roda' => 6,
            ],

            // O — CAT
            'O' => [
                'Mowilex weathercoat' => 2,
                'Nippon paint Q-LUC' => 6,
                'Semen TR-30 40 Kg Tiga Roda' => 24,
                'Semen Aci Putih 25 kg' => 2,
            ],

            // P — PAGAR
            'P' => [
                'Bata' => 200,
                'Pasir' => 2,
                'Semen PCC 40 Kg Tiga Roda' => 6,
                'Besi ø6' => 4,
                'Besi ø8' => 8,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                'Semen PCC 40 Kg Tiga Roda' => 18,
                'Pasir' => 4,
            ],

            // R — TALANG AIR
            'R' => [
                'Pipa 3"' => 2,
                'Pipa 2"' => 4,
                'Cekakan pipa 2"' => 4,
                'Lbow 3" ke 2"' => 2,
                'Sambungan 3" ke 2"' => 2,
                'Lbow 2"' => 6,
                'Penutup pipa 3"' => 2,
                'Lem pipa fox' => 2,
            ],

            // S — JENDELA
            'S' => [
                'Aluminium white ink (openback)' => 8,
                'Aluminium white ink (m)' => 4,
                'Aluminium white ink (stoper casmen)' => 12,
                'List ornamen 20 mm white' => 12,
                'Klem sedang 13 x 26 x 1,4 mm' => 2,
                'Karet C besar HTM' => 4,
                'Karet cacing HTM' => 4,
                'Rambuncis dks white kanan' => 6,
                'Rambuncis dks white kiri' => 4,
                'Engsel casment glatino 8 inch' => 10,
                'Marks sosis black 620 ml' => 8,
                'Sekrup 8 x 3 rata' => 50,
                'Sekrup 8 x 1,5 rata @ 50 pcs' => 80,
                'Sekrup 8 x 1 bulat' => 80,
                'Sekrup 8 x 0,5 rata' => 160,
                'Rivet GT 429' => 2,
                'Fisher S6 vini star' => 2,
                'Reben 5, 203 cm x 102 cm asahi' => 12,
                'Marks sosis white 620 ml' => 8,
            ],

            // T — ELEKTRICAL
            'T' => [
                'Saklar tunggal broco' => 12,
                'Saklar dobel broco' => 2,
                'Stop kontak tunggal broco' => 22,
                'Kabel NYA 1,5 Eterna (100m)' => 2,
                'Kabel NYA 2,5 Eterna (100m)' => 14,
                'Kabel NYM 2 x 1,5 Jumbo (50m)' => 50,
                'Kabel NYM 2 x 2,5 Jumbo (50m)' => 70,
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

        echo "✔ SELESAI — DATA TYPE 100 BERHASIL DIIMPORT\n";
    }
}
