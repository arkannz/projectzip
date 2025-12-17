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
        // Cari type 45
        $type = Type::where('nama', '45')->first();

        if (!$type) {
            echo "⚠ Type 45 tidak ditemukan! Pastikan TypeSeeder sudah dijalankan.\n";
            return;
        }

        $typeId = $type->id;

        // Ambil mapping kategori untuk lookup
        $categories = \App\Models\RabCategory::all()->keyBy('kode');

        // ========================================
        // BAHAN BAKU TYPE 45
        // Dikelompokkan per kategori untuk menghindari duplicate key overwrites
        // ISI SENDIRI nilai bahan_baku untuk setiap item di bawah ini
        // ========================================
        $valuesByCategory = [
            // A — PONDASI
            'A' => [
                // 'Besi ø6' => 0,
                // 'Besi ø8 Ulir' => 0,
                // 'Kayu 3/5' => 0,
                // 'Cerucuk' => 0,
                // 'Papan Mal' => 0,
                // 'Pasir' => 0,
                // 'Batu' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
            ],

            // B — URUG
            'B' => [
                // 'Pasir ' => 0,
                // 'Pipa 4"' => 0,
                // 'Pipa 2"' => 0,
            ],

            // C — COR LANTAI
            'C' => [
                // 'Besi ø6' => 0,
                // 'Pasir' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Plastik cor' => 0,
            ],

            // D — PINTU
            'D' => [
                // 'Pintu Kayu 120 x 220' => 0,
                // 'Pintu Kayu 80 x 220' => 0,
                // 'Pintu WC PVC Biru' => 0,
                // 'Pintu WC Edenjoice Putih' => 0,
                // 'Engsel Pintu 4"' => 0,
                // 'Peganggan pintu 2 daun' => 0,
                // 'Pengunci Pintu 2 daun' => 0,
                // 'Pegangan kunci 1 daun' => 0,
                // 'Avian' => 0,
                // 'Tinner Nb Kaleng' => 0,
                // 'Slot 6"' => 0,
                // 'Slot 4"' => 0,
            ],

            // E — BATA
            'E' => [
                // 'Papan' => 0,
                // 'Kayu 3/5' => 0,
                // 'Besi ø6' => 0,
                // 'Besi ø8 ' => 0,
                // 'Kusen Pintu (2 Daun)' => 0,
                // 'Kusen Pintu (1 Daun)' => 0,
                // 'Bata' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Pasir' => 0,
            ],

            // F — COR TIANG DEPAN
            'F' => [
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Pasir' => 0,
                // 'Batu' => 0,
                // 'Besi ø6 ' => 0,
                // 'Besi ø8' => 0,
                // 'Multiplek' => 0,
                // 'Kayu 3/5' => 0,
                // 'Bata' => 0,
                // 'Cerucuk' => 0,
                // 'Papan Mal' => 0,
            ],

            // G — PLASTER
            'G' => [
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Pasir' => 0,
                // 'Pipa 5/8' => 0,
            ],

            // H — ATAP
            'H' => [
                // 'Kayu 3/5' => 0,
                // 'Kayu 4/6' => 0,
                // 'Kayu 5/7' => 0,
                // 'Lisplank' => 0,
                // 'Seng Metal 4 Susun (merah)' => 0,
                // 'Seng Metal 2 Susun (hitam)' => 0,
                // 'Perabung Hitam' => 0,
                // 'Perabung Merah' => 0,
                // 'Paku 2 inch' => 0,
                // 'Paku 3 inch' => 0,
            ],

            // I — DEK
            'I' => [
                // 'Kayu 3/5' => 0,
                // 'Gypsum' => 0,
                // 'Baut' => 0,
                // 'Paku Beton 4 inch' => 0,
                // 'Paku 3 inch' => 0,
                // 'Paku 2 inch' => 0,
            ],

            // J — MINIMALIS
            'J' => [
                // 'Bata' => 0,
                // 'Pasir' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Keramik 60 x 60 Blackmatt' => 0,
            ],

            // K — CARPORT
            'K' => [
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Kayu 3/5' => 0,
                // 'Cerucuk' => 0,
                // 'Papan Mal' => 0,
                // 'Plastik cor' => 0,
                // 'Besi ø6' => 0,
                // 'Besi ø8' => 0,
                // 'Batu' => 0,
                // 'Pasir' => 0,
                // 'Keramik 60 x 60 Blackmatt' => 0,
            ],

            // L — KERAMIK
            'L' => [
                // 'Keramik 60 x 60 Cream' => 0,
                // 'Keramik 60 x 60 Blackmatt' => 0,
                // 'Pasir' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Oker' => 0,
            ],

            // M — WC
            'M' => [
                // 'Keramik 30 x 30 ' => 0,
                // 'Keramik 25 x 40' => 0,
                // 'Closed jongkok Ina' => 0,
                // 'Closed Duduk Volk' => 0,
                // 'Floor drain' => 0,
                // 'Pasir' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Bak Air' => 0,
                // 'Pipa 1/2"' => 0,
                // 'Lbow 1/2"' => 0,
                // 'Kran Air Plastik 1/2"' => 0,
                // 'SDD 1/2"' => 0,
            ],

            // N — ACI MINIMALIS
            'N' => [
                // 'Semen TR-30 40 Kg Tiga Roda' => 0,
            ],

            // O — CAT
            'O' => [
                // 'Mowilex weathercoat' => 0,
                // 'Nippon paint Q-LUC' => 0,
                // 'Semen TR-30 40 Kg Tiga Roda' => 0,
                // 'Semen Aci Putih 25 kg' => 0,
            ],

            // P — PAGAR
            'P' => [
                // 'Bata' => 0,
                // 'Pasir' => 0,
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Besi ø6 ' => 0,
                // 'Besi ø8' => 0,
            ],

            // Q — MINIMALIS CARPORT
            'Q' => [
                // 'Semen PCC 40 Kg Tiga Roda' => 0,
                // 'Pasir' => 0,
            ],

            // R — TALANG AIR
            'R' => [
                // 'Pipa 3"' => 0,
                // 'Pipa 2"' => 0,
                // 'Cekakan pipa 2"' => 0,
                // 'Lbow 3" ke 2"' => 0,
                // 'Sambungan 3" ke 2"' => 0,
                // 'Lbow 2"' => 0,
                // 'Penutup pipa 3"' => 0,
                // 'Lem pipa fox' => 0,
            ],

            // S — JENDELA
            'S' => [
                // 'Aluminium white ink (openback)' => 0,
                // 'Aluminium white ink (m)' => 0,
                // 'Aluminium white ink (stoper casmen)' => 0,
                // 'List ornamen 20 mm white' => 0,
                // 'Klem sedang 13 x 26 x 1,4 mm' => 0,
                // 'Karet C besar HTM' => 0,
                // 'Karet cacing HTM' => 0,
                // 'Rambuncis dks white kanan' => 0,
                // 'Rambuncis dks white kiri' => 0,
                // 'Engsel casment glatino 8 inch' => 0,
                // 'Marks sosis black 620 ml' => 0,
                // 'Sekrup 8 x 3 rata' => 0,
                // 'Sekrup 8 x 1,5 rata @ 50 pcs' => 0,
                // 'Sekrup 8 x 1 bulat' => 0,
                // 'Sekrup 8 x 0,5 rata' => 0,
                // 'Rivet GT 429' => 0,
                // 'Fisher S6 vini star' => 0,
                // 'Reben 5, 203 cm x 102 cm asahi' => 0,
                // 'Marks sosis white 620 ml' => 0,
            ],

            // T — ELEKTRICAL
            'T' => [
                // 'Saklar tunggal broco' => 0,
                // 'Saklar dobel broco' => 0,
                // 'Stop kontak tunggal broco' => 0,
                // 'Kabel NYA 1,5 Eterna (100m)' => 0,
                // 'Kabel NYA 2,5 Eterna (100m)' => 0,
                // 'Kabel NYM 2 x 1,5 Jumbo (50m)' => 0,
                // 'Kabel NYM 2 x 2,5 Jumbo (50m)' => 0,
                // 'Mcb 10A Powell' => 0,
                // 'Box Mcb tanam 3 grup' => 0,
                // 'Mcb Kwh 1300 amper' => 0,
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

        echo "✔ SELESAI — DATA TYPE 45 BERHASIL DIIMPORT\n";
    }
}

