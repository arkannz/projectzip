<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;
use App\Models\RabCategory;

class RabType40Seeder extends Seeder
{
    public function run()
    {
        // Cari type 40 dari database
        $type = Type::where('nama', '40')->first();
        
        if (!$type) {
            echo "⚠ Type 40 tidak ditemukan! Jalankan TypeSeeder terlebih dahulu.\n";
            return;
        }
        
        $typeId = $type->id;

        // Ambil kategori
        $categories = RabCategory::pluck('id', 'kode')->toArray();

        // -----------------------------------------------
        // DAFTAR BAHAN BAKU TYPE 40
        // Format: [kode_kategori, nama_item, qty]
        // -----------------------------------------------
        $data = [

            // A — PONDASI
            ['A', 'Besi ø6', 9],
            ['A', 'Besi ø8 Ulir', 33],
            ['A', 'Kayu 3/5', 28],
            ['A', 'Cerucuk', 59],
            ['A', 'Papan Mal', 131],
            ['A', 'Pasir ', 10],
            ['A', 'Batu', 1],
            ['A', 'Semen PCC 40 Kg Tiga Roda', 31],

            // B — URUG
            ['B', 'Pasir ', 25],
            ['B', 'Pipa 4"', 2],
            ['B', 'Pipa 2"', 2],

            // C — COR LANTAI
            ['C', 'Besi ø6', 12],
            ['C', 'Pasir', 4],
            ['C', 'Semen PCC 40 Kg Tiga Roda', 11],
            ['C', 'Plastik cor', 5],

            // D — PINTU
            ['D', 'Pintu Kayu 120 x 220', 1],
            ['D', 'Pintu Kayu 80 x 220', 3],
            ['D', 'Pintu WC PVC Biru', 2],
            ['D', 'Pintu WC Edenjoice Putih', 1],
            ['D', 'Engsel Pintu 4"', 15],
            ['D', 'Peganggan pintu 2 daun', 2],
            ['D', 'Pengunci Pintu 2 daun', 1],
            ['D', 'Pegangan kunci 1 daun', 3],
            ['D', 'Avian', 4],
            ['D', 'Tinner Nb Kaleng', 2],
            ['D', 'Slot 6 "', 1],
            ['D', 'Slot 4"', 1],

            // E — BATA
            ['E', 'Papan', 19],
            ['E', 'Kayu 3/5', 20],
            ['E', 'Besi ø6', 4],
            ['E', 'Besi ø8', 7],
            ['E', 'Besi ø6 ', 1],
            ['E', 'Kusen Pintu ( 2 Daun )', 1],
            ['E', 'Kusen Pintu ( 1 Daun )', 3],
            ['E', 'Bata', 1863],
            ['E', 'Semen PCC 40 Kg Tiga Roda', 15],
            ['E', 'Pasir', 4],
            ['E', 'Besi ø6', 4],

            // F — COR TIANG DEPAN
            ['F', 'Semen PCC 40 Kg Tiga Roda', 4],
            ['F', 'Pasir', 1],
            ['F', 'Batu', 1],
            ['F', 'Besi ø6 ', 2],
            ['F', 'Besi ø8', 3],
            ['F', 'Multiplek', 1],
            ['F', 'Kayu 3/5', 16],
            ['F', 'Bata', 59],
            ['F', 'Cerucuk', 6],
            ['F', 'Papan Mal', 5],

            // G — PLASTER
            ['G', 'Semen PCC 40 Kg Tiga Roda', 28],
            ['G', 'Pasir', 6],
            ['G', 'Pipa 5/8', 5],

            // H — ATAP
            ['H', 'Kayu 3/5', 48],
            ['H', 'Kayu 4/6', 32],
            ['H', 'Kayu 5/7', 23],
            ['H', 'Lisplank', 7],
            ['H', 'Seng  Metal 4 Susun (merah)', 55],
            ['H', 'Seng  Metal 2 Susun (hitam)', 0],
            ['H', 'Perabung Hitam', 0],
            ['H', 'Perabung Merah', 1.5],
            ['H', 'Paku 2 inch', 1],
            ['H', 'Paku 3 inch', 1],

            // I — DEK
            ['I', 'Kayu 3/5', 69],
            ['I', 'Gypsum', 18],
            ['I', 'Baut', 3],
            ['I', 'Paku Beton 4 inch', 1],
            ['I', 'Paku 3 inch', 1],
            ['I', 'paku 2 inch', 1],

            // J — MINIMALIS
            ['J', 'Bata', 87],
            ['J', 'Pasir', 2],
            ['J', 'Semen PCC 40 Kg Tiga Roda', 7],
            ['J', 'Keramik 60 x 60 Blackmatt', 7],

            // K — CARPORT
            ['K', 'Semen PCC 40 Kg Tiga Roda', 22],
            ['K', 'Kayu 3/5', 8],
            ['K', 'Cerucuk', 5],
            ['K', 'Papan Mal', 10],
            ['K', 'Plastik cor', 7],
            ['K', 'Besi ø6', 2],
            ['K', 'Besi ø8', 15],
            ['K', 'Batu', 1],
            ['K', 'Pasir', 6],
            ['K', 'Keramik 60 x 60 Blackmatt', 31],

            // L — KERAMIK
            ['L', 'Keramik 60 x 60 Cream', 25],
            ['L', 'Keramik 60 x 60 Blackmatt', 10],
            ['L', 'Pasir', 4],
            ['L', 'Semen PCC 40 Kg Tiga Roda', 17],
            ['L', 'Oker', 5],

            // M — WC
            ['M', 'Keramik 30 x 30', 3],
            ['M', 'Keramik 25 x 40', 11],
            ['M', 'Closed jongkok Ina', 1],
            ['M', 'Closed Duduk Volk', 1],
            ['M', 'Floor drain', 2],
            ['M', 'Pasir', 1],
            ['M', 'Semen PCC 40 Kg Tiga Roda', 4],
            ['M', 'Bak Air', 2],
            ['M', 'Pipa 1/2"', 2],
            ['M', 'Lbow 1/2"', 6],
            ['M', 'Kran Air Plastik 1/2"', 3],
            ['M', 'SDD 1/2"', 1],

            // N — ACI MINIMALIS
            ['N', 'Semen TR-30 40 Kg Tiga Roda', 3],

            // O — CAT
            ['O', 'Mowilex weathercoat', 1],
            ['O', 'Nippon paint Q-LUC', 3],
            ['O', 'Semen TR-30 40 Kg Tiga Roda', 9],
            ['O', 'Semen Aci Putih 25 kg', 1],

            // P — PAGAR
            ['P', 'Bata', 100],
            ['P', 'Pasir', 1],
            ['P', 'Semen PCC 40 Kg Tiga Roda', 3],
            ['P', 'Besi ø6 ', 2],
            ['P', 'Besi ø8', 4],

            // Q — MINIMALIS CARPORT
            ['Q', 'Semen PCC 40 Kg Tiga Roda', 9],
            ['Q', 'Pasir', 2],

            // R — TALANG AIR
            ['R', 'Pipa 3"', 0],
            ['R', 'Pipa 2 "', 2],
            ['R', 'Cekakan pipa 2"', 2],
            ['R', 'Lbow 3" ke 2"', 1],
            ['R', 'Sambungan 3" ke 2"', 1],
            ['R', 'Lbow 2"', 3],
            ['R', 'Penutup pipa 3"', 1],
            ['R', 'Lem pipa fox', 1],

            // S — JENDELA
            ['S', 'Aluminium white ink (openback)', 4],
            ['S', 'Aluminium white ink (m)', 2],
            ['S', 'Aluminium white ink (stoper casmen)', 6],
            ['S', 'List ornamen 20 mm white', 6],
            ['S', 'Klem sedang 13 x 26 x 1,4 mm', 1],
            ['S', 'Karet C besar HTM', 2],
            ['S', 'Karet cacing HTM', 2],
            ['S', 'Rambuncis dks white kanan', 3],
            ['S', 'Rambuncis dks white kiri', 2],
            ['S', 'Engsel casment glatino 8 inch', 5],
            ['S', 'Marks sosis black 620 ml', 4],
            ['S', 'Sekrup 8 x 3 rata', 25],
            ['S', 'Sekrup 8 x 1,5 rata @ 50 pcs', 40],
            ['S', 'Sekrup 8 x 1 bulat', 40],
            ['S', 'Sekrup 8 x 0,5 rata', 80],
            ['S', 'Rivet gt 429', 1],
            ['S', 'Fisher S6 vini star', 1],
            ['S', 'Reben 5, 203 cm x 102 cm asahi', 6],
            ['S', 'Marks sosis white 620 ml', 4],

            // T — ELEKTICAL
            ['T', 'Saklar tunggal broco', 6],
            ['T', 'Saklar dobel broco', 1],
            ['T', 'Stop kontak tunggal broco', 11],
            ['T', 'Kabel NYA 1,5 Eterna (100m)', 1],
            ['T', 'Kabel NYA 2,5 Eterna (100m)', 7],
            ['T', 'Kabel NYM 2 x 1,5 Jumbo (50m)', 25],
            ['T', 'Kabel NYM 2 x 2,5 Jumbo (50m)', 35],
            ['T', 'Mcb 10A Powell', 1],
            ['T', 'Box Mcb tanam 3 grup', 1],
            ['T', 'Mcb Kwh 1300 amper', 1],
        ];

        // ----------------------------------------------------
        // INSERT KE rab_type_values
        // ----------------------------------------------------
        $inserted = 0;
        $notFound = [];

        foreach ($data as $d) {
            $categoryKode = $d[0];
            $itemName = $d[1];
            $qty = $d[2];

            // Cari template berdasarkan category_id dan item_name
            $categoryId = $categories[$categoryKode] ?? null;
            
            if (!$categoryId) {
                echo "⚠ KATEGORI TIDAK DITEMUKAN: $categoryKode\n";
                continue;
            }

            $tpl = RabTemplate::where('category_id', $categoryId)
                ->where('item_name', $itemName)
                ->first();

            if (!$tpl) {
                $notFound[] = "$categoryKode - $itemName";
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
            $inserted++;
        }

        echo "✔ SELESAI — DATA TYPE 40 BERHASIL DIIMPORT ($inserted item)\n";
        
        if (count($notFound) > 0) {
            echo "⚠ ITEM TIDAK DITEMUKAN DI TEMPLATE:\n";
            foreach ($notFound as $item) {
                echo "   - $item\n";
            }
        }
    }
}

