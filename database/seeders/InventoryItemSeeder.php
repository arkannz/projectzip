<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run()
    {
        // Format: 'nama' => ['harga' => xxx, 'satuan' => 'xxx']
        $items = [

            // A — PONDASI
            ['nama' => 'Besi ø6', 'harga' => 21500, 'satuan' => 'Batang'],
            ['nama' => 'Besi ø8 Ulir', 'harga' => 49000, 'satuan' => 'Batang'],
            ['nama' => 'Kayu 3/5', 'harga' => 11000, 'satuan' => 'Batang'],
            ['nama' => 'Cerucuk', 'harga' => 8000, 'satuan' => 'Batang'],
            ['nama' => 'Papan Mal', 'harga' => 11000, 'satuan' => 'Lembar'],
            ['nama' => 'Pasir', 'harga' => 150000, 'satuan' => 'Kubik'],
            ['nama' => 'Batu', 'harga' => 500000, 'satuan' => 'Truk'],
            ['nama' => 'Semen PCC 40 Kg Tiga Roda', 'harga' => 60700, 'satuan' => 'Sak'],

            // B — URUG
            ['nama' => 'Pipa 4"', 'harga' => 133500, 'satuan' => 'Batang'],
            ['nama' => 'Pipa 2"', 'harga' => 48000, 'satuan' => 'Batang'],

            // C — COR LANTAI
            ['nama' => 'Plastik cor', 'harga' => 15000, 'satuan' => 'Meter'],

            // D — PINTU
            ['nama' => 'Pintu Kayu 120 x 220', 'harga' => 1550000, 'satuan' => 'Unit'],
            ['nama' => 'Pintu Kayu 80 x 220', 'harga' => 775000, 'satuan' => 'Unit'],
            ['nama' => 'Pintu WC PVC Biru', 'harga' => 260000, 'satuan' => 'Unit'],
            ['nama' => 'Pintu WC Edenjoice Putih', 'harga' => 799000, 'satuan' => 'Unit'],
            ['nama' => 'Engsel Pintu 4"', 'harga' => 15000, 'satuan' => 'Pcs'],
            ['nama' => 'Peganggan pintu 2 daun', 'harga' => 100000, 'satuan' => 'Set'],
            ['nama' => 'Pengunci Pintu 2 daun', 'harga' => 60000, 'satuan' => 'Set'],
            ['nama' => 'Pegangan kunci 1 daun', 'harga' => 65000, 'satuan' => 'Set'],
            ['nama' => 'Avian', 'harga' => 83000, 'satuan' => 'Kaleng'],
            ['nama' => 'Tinner Nb Kaleng', 'harga' => 23000, 'satuan' => 'Kaleng'],
            ['nama' => 'Slot 6"', 'harga' => 17000, 'satuan' => 'Pcs'],
            ['nama' => 'Slot 4"', 'harga' => 11000, 'satuan' => 'Pcs'],

            // E — BATA
            ['nama' => 'Papan', 'harga' => 11000, 'satuan' => 'Lembar'],
            ['nama' => 'Besi ø8', 'harga' => 35000, 'satuan' => 'Batang'],
            ['nama' => 'Kusen Pintu (2 Daun)', 'harga' => 363250, 'satuan' => 'Set'],
            ['nama' => 'Kusen Pintu (1 Daun)', 'harga' => 337050, 'satuan' => 'Set'],
            ['nama' => 'Bata', 'harga' => 1850, 'satuan' => 'Pcs'],

            // F — COR TIANG DEPAN
            ['nama' => 'Multiplek', 'harga' => 130000, 'satuan' => 'Lembar'],

            // G — PLASTER
            ['nama' => 'Pipa 5/8', 'harga' => 8600, 'satuan' => 'Batang'],

            // H — ATAP
            ['nama' => 'Kayu 4/6', 'harga' => 15000, 'satuan' => 'Batang'],
            ['nama' => 'Kayu 5/7', 'harga' => 30000, 'satuan' => 'Batang'],
            ['nama' => 'Lisplank', 'harga' => 60000, 'satuan' => 'Lembar'],
            ['nama' => 'Seng Metal 4 Susun (merah)', 'harga' => 52000, 'satuan' => 'Lembar'],
            ['nama' => 'Seng Metal 2 Susun (hitam)', 'harga' => 36000, 'satuan' => 'Lembar'],
            ['nama' => 'Perabung Hitam', 'harga' => 50000, 'satuan' => 'Pcs'],
            ['nama' => 'Perabung Merah', 'harga' => 650000, 'satuan' => 'Pcs'],
            ['nama' => 'Paku 2 inch', 'harga' => 130000, 'satuan' => 'Kg'],
            ['nama' => 'Paku 3 inch', 'harga' => 130000, 'satuan' => 'Kg'],

            // I — DEK
            ['nama' => 'Gypsum', 'harga' => 60000, 'satuan' => 'Lembar'],
            ['nama' => 'Baut', 'harga' => 37000, 'satuan' => 'Kotak'],
            ['nama' => 'Paku Beton 4 inch', 'harga' => 5000, 'satuan' => 'Kg'],

            // J — MINIMALIS
            ['nama' => 'Keramik 60 x 60 Blackmatt', 'harga' => 165000, 'satuan' => 'Dus'],

            // K — CARPORT
            // Items already exist above

            // L — KERAMIK
            ['nama' => 'Keramik 60 x 60 Cream', 'harga' => 118000, 'satuan' => 'Dus'],
            ['nama' => 'Oker', 'harga' => 15000, 'satuan' => 'Kg'],

            // M — WC
            ['nama' => 'Keramik 30 x 30', 'harga' => 60000, 'satuan' => 'Dus'],
            ['nama' => 'Keramik 25 x 40', 'harga' => 60000, 'satuan' => 'Dus'],
            ['nama' => 'Closed jongkok Ina', 'harga' => 270000, 'satuan' => 'Unit'],
            ['nama' => 'Closed Duduk Volk', 'harga' => 780000, 'satuan' => 'Unit'],
            ['nama' => 'Floor drain', 'harga' => 5000, 'satuan' => 'Pcs'],
            ['nama' => 'Bak Air', 'harga' => 140000, 'satuan' => 'Unit'],
            ['nama' => 'Pipa 1/2"', 'harga' => 18000, 'satuan' => 'Batang'],
            ['nama' => 'Lbow 1/2"', 'harga' => 2500, 'satuan' => 'Pcs'],
            ['nama' => 'Kran Air Plastik 1/2"', 'harga' => 10000, 'satuan' => 'Pcs'],
            ['nama' => 'SDD 1/2"', 'harga' => 5000, 'satuan' => 'Pcs'],

            // N — ACI MINIMALIS
            ['nama' => 'Semen TR-30 40 Kg Tiga Roda', 'harga' => 124500, 'satuan' => 'Sak'],

            // O — CAT
            ['nama' => 'Mowilex weathercoat', 'harga' => 2434900, 'satuan' => 'Pail'],
            ['nama' => 'Nippon paint Q-LUC', 'harga' => 275000, 'satuan' => 'Pail'],
            ['nama' => 'Semen Aci Putih 25 kg', 'harga' => 115000, 'satuan' => 'Sak'],

            // P — PAGAR
            // Items exist above

            // Q — MINIMALIS CARPORT
            // Using same items as above

            // R — TALANG AIR
            ['nama' => 'Pipa 3"', 'harga' => 82000, 'satuan' => 'Batang'],
            ['nama' => 'Pipa 2 "', 'harga' => 50000, 'satuan' => 'Batang'],
            ['nama' => 'Cekakan pipa 2"', 'harga' => 4000, 'satuan' => 'Pcs'],
            ['nama' => 'Lbow 3" ke 2"', 'harga' => 15000, 'satuan' => 'Pcs'],
            ['nama' => 'Sambungan 3" ke 2"', 'harga' => 12000, 'satuan' => 'Pcs'],
            ['nama' => 'Lbow 2"', 'harga' => 4000, 'satuan' => 'Pcs'],
            ['nama' => 'Penutup pipa 3"', 'harga' => 8000, 'satuan' => 'Pcs'],
            ['nama' => 'Lem pipa fox', 'harga' => 22000, 'satuan' => 'Tube'],

            // S — JENDELA
            ['nama' => 'Aluminium white ink (openback)', 'harga' => 165000, 'satuan' => 'Batang'],
            ['nama' => 'Aluminium white ink (m)', 'harga' => 198000, 'satuan' => 'Batang'],
            ['nama' => 'Aluminium white ink (stoper casmen)', 'harga' => 86000, 'satuan' => 'Batang'],
            ['nama' => 'List ornamen 20 mm white', 'harga' => 50000, 'satuan' => 'Batang'],
            ['nama' => 'Klem sedang 13 x 26 x 1,4 mm', 'harga' => 60000, 'satuan' => 'Kotak'],
            ['nama' => 'Karet C besar HTM', 'harga' => 55000, 'satuan' => 'Meter'],
            ['nama' => 'Karet cacing HTM', 'harga' => 50000, 'satuan' => 'Meter'],
            ['nama' => 'Rambuncis dks white kanan', 'harga' => 11000, 'satuan' => 'Pcs'],
            ['nama' => 'Rambuncis dks white kiri', 'harga' => 11000, 'satuan' => 'Pcs'],
            ['nama' => 'Engsel casment glatino 8 inch', 'harga' => 17000, 'satuan' => 'Pcs'],
            ['nama' => 'Marks sosis black 620 ml', 'harga' => 40000, 'satuan' => 'Tube'],
            ['nama' => 'Sekrup 8 x 3 rata', 'harga' => 400, 'satuan' => 'Pcs'],
            ['nama' => 'Sekrup 8 x 1,5 rata @ 50 pcs', 'harga' => 15000, 'satuan' => 'Pak'],
            ['nama' => 'Sekrup 8 x 1 bulat', 'harga' => 200, 'satuan' => 'Pcs'],
            ['nama' => 'Sekrup 8 x 0,5 rata', 'harga' => 100, 'satuan' => 'Pcs'],
            ['nama' => 'Rivet GT 429', 'harga' => 55000, 'satuan' => 'Kotak'],
            ['nama' => 'Fisher S6 vini star', 'harga' => 10000, 'satuan' => 'Pak'],
            ['nama' => 'Reben 5, 203 cm x 102 cm asahi', 'harga' => 300000, 'satuan' => 'Lembar'],
            ['nama' => 'Marks sosis white 620 ml', 'harga' => 40000, 'satuan' => 'Tube'],

            // T — ELEKTRIKAL
            ['nama' => 'Saklar tunggal broco', 'harga' => 14000, 'satuan' => 'Pcs'],
            ['nama' => 'Saklar dobel broco', 'harga' => 18000, 'satuan' => 'Pcs'],
            ['nama' => 'Stop kontak tunggal broco', 'harga' => 15000, 'satuan' => 'Pcs'],
            ['nama' => 'Kabel NYA 1,5 Eterna (100m)', 'harga' => 336000, 'satuan' => 'Roll'],
            ['nama' => 'Kabel NYA 2,5 Eterna (100m)', 'harga' => 5500, 'satuan' => 'Meter'],
            ['nama' => 'Kabel NYM 2 x 1,5 Jumbo (50m)', 'harga' => 8200, 'satuan' => 'Meter'],
            ['nama' => 'Kabel NYM 2 x 2,5 Jumbo (50m)', 'harga' => 12500, 'satuan' => 'Meter'],
            ['nama' => 'MCB 10A Powell', 'harga' => 19000, 'satuan' => 'Pcs'],
            ['nama' => 'Box MCB tanam 3 grup', 'harga' => 14000, 'satuan' => 'Pcs'],
            ['nama' => 'MCB Kwh 1300 amper', 'harga' => 60000, 'satuan' => 'Pcs'],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(
                ['nama' => $item['nama']],
                [
                    'harga'      => $item['harga'],
                    'stok_awal'  => 0,
                    'satuan'     => $item['satuan'],
                    'keterangan' => null,
                ]
            );
        }

        echo "✔ SELESAI — " . count($items) . " ITEM INVENTORY BERHASIL DIIMPORT\n";
    }
}
