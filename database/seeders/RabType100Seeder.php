<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabTemplate;
use App\Models\RabTypeValue;
use App\Models\RabCategory;

class RabType100Seeder extends Seeder
{
    public function run()
    {
        // Cari type 100 dari database
        $type = Type::where('nama', '100')->first();
        
        if (!$type) {
            echo "⚠ Type 100 tidak ditemukan! Jalankan TypeSeeder terlebih dahulu.\n";
            return;
        }
        
        $typeId = $type->id;

        // Ambil kategori
        $categories = RabCategory::pluck('id', 'kode')->toArray();

        // -----------------------------------------------
        // DAFTAR BAHAN BAKU TYPE 100
        // Format: [kode_kategori, nama_item, qty]
        // -----------------------------------------------
        $data = [

            // A — PONDASI
            ['A', 'Besi ø6', 24],
            ['A', 'Besi ø8 Ulir', 82],
            ['A', 'Kayu 3/5', 70],
            ['A', 'Cerucuk', 148],
            ['A', 'Papan Mal', 328],
            ['A', 'Pasir ', 26],
            ['A', 'Batu', 4],
            ['A', 'Semen PCC 40 Kg Tiga Roda', 77],

            // B — URUG
            ['B', 'Pasir ', 64],
            ['B', 'Pipa 4"', 2],
            ['B', 'Pipa 2"', 2],

            // C — COR LANTAI
            ['C', 'Besi ø6', 31],
            ['C', 'Pasir', 10],
            ['C', 'Semen PCC 40 Kg Tiga Roda', 28],
            ['C', 'Plastik cor', 13],

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
            ['E', 'Papan', 48],
            ['E', 'Kayu 3/5', 51],
            ['E', 'Besi ø6', 10],
            ['E', 'Besi ø8', 17],
            ['E', 'Besi ø6 ', 4],
            ['E', 'Kusen Pintu ( 2 Daun )', 2],
            ['E', 'Kusen Pintu ( 1 Daun )', 6],
            ['E', 'Bata', 4659],
            ['E', 'Semen PCC 40 Kg Tiga Roda', 39],
            ['E', 'Pasir', 10],
            ['E', 'Besi ø6', 10],

            // F — COR TIANG DEPAN
            ['F', 'Semen PCC 40 Kg Tiga Roda', 10],
            ['F', 'Pasir', 2],
            ['F', 'Batu', 4],
            ['F', 'Besi ø6 ', 6],
            ['F', 'Besi ø8', 8],
            ['F', 'Multiplek', 2],
            ['F', 'Kayu 3/5', 40],
            ['F', 'Bata', 148],
            ['F', 'Cerucuk', 15],
            ['F', 'Papan Mal', 15],

            // G — PLASTER
            ['G', 'Semen PCC 40 Kg Tiga Roda', 70],
            ['G', 'Pasir', 15],
            ['G', 'Pipa 5/8', 15],

            // H — ATAP
            ['H', 'Kayu 3/5', 120],
            ['H', 'Kayu 4/6', 80],
            ['H', 'Kayu 5/7', 59],
            ['H', 'Lisplank', 19],
            ['H', 'Seng  Metal 4 Susun (merah)', 137],
            ['H', 'Seng  Metal 2 Susun (hitam)', 0],
            ['H', 'Perabung Hitam', 0],
            ['H', 'Perabung Merah', 1.5],
            ['H', 'Paku 2 inch', 2],
            ['H', 'Paku 3 inch', 2],

            // I — DEK
            ['I', 'Kayu 3/5', 173],
            ['I', 'Gypsum', 46],
            ['I', 'Baut', 8],
            ['I', 'Paku Beton 4 inch', 2],
            ['I', 'Paku 3 inch', 2],
            ['I', 'paku 2 inch', 2],

            // J — MINIMALIS
            ['J', 'Bata', 120],
            ['J', 'Pasir', 3],
            ['J', 'Semen PCC 40 Kg Tiga Roda', 10],
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
            ['L', 'Keramik 60 x 60 Cream', 64],
            ['L', 'Keramik 60 x 60 Blackmatt', 26],
            ['L', 'Pasir', 10],
            ['L', 'Semen PCC 40 Kg Tiga Roda', 44],
            ['L', 'Oker', 5],

            // M — WC
            ['M', 'Keramik 30 x 30', 6],
            ['M', 'Keramik 25 x 40', 22],
            ['M', 'Closed jongkok Ina', 2],
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
            ['N', 'Semen TR-30 40 Kg Tiga Roda', 4],

            // O — CAT
            ['O', 'Mowilex weathercoat', 1],
            ['O', 'Nippon paint Q-LUC', 6],
            ['O', 'Semen TR-30 40 Kg Tiga Roda', 24],
            ['O', 'Semen Aci Putih 25 kg', 2],

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

        echo "✔ SELESAI — DATA TYPE 100 BERHASIL DIIMPORT ($inserted item)\n";
        
        if (count($notFound) > 0) {
            echo "⚠ ITEM TIDAK DITEMUKAN DI TEMPLATE:\n";
            foreach ($notFound as $item) {
                echo "   - $item\n";
            }
        }
    }
}

