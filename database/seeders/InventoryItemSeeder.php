<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run()
    {
        $items = [

            // A — PONDASI
            'Besi ø6',
            'Besi ø8 Ulir',
            'Kayu 3/5',
            'Cerucuk',
            'Papan Mal',
            'Pasir',
            'Batu',
            'Semen PCC 40 Kg Tiga Roda',

            // B — URUG
            'Pipa 4"',
            'Pipa 2"',

            // C — COR LANTAI
            'Plastik cor',

            // D — PINTU
            'Pintu Kayu 120 x 220',
            'Pintu Kayu 80 x 220',
            'Pintu WC PVC Biru',
            'Pintu WC Edenjoice Putih',
            'Engsel Pintu 4"',
            'Peganggan pintu 2 daun',
            'Pengunci Pintu 2 daun',
            'Pegangan kunci 1 daun',
            'Avian',
            'Tinner Nb Kaleng',
            'Slot 6"',
            'Slot 4"',

            // E — BATA
            'Papan',
            'Kusen Pintu (2 Daun)',
            'Kusen Pintu (1 Daun)',
            'Bata',

            // F — COR TIANG DEPAN
            'Multiplek',

            // G — PLASTER
            'Pipa 5/8',

            // H — ATAP
            'Kayu 4/6',
            'Kayu 5/7',
            'Lisplank',
            'Seng Metal 4 Susun (merah)',
            'Seng Metal 2 Susun (hitam)',
            'Perabung Hitam',
            'Perabung Merah',
            'Paku 2 inch',
            'Paku 3 inch',

            // I — DEK
            'Gypsum',
            'Baut',
            'Paku Beton 4 inch',
            'Paku 3 inch',
            'Paku 2 inch',

            // J — MINIMALIS
            'Keramik 60 x 60 Blackmatt',

            // K — CARPORT
            'Keramik 60 x 60 Cream',

            // L — KERAMIK
            'Oker',

            // M — WC
            'Keramik 30 x 30',
            'Keramik 25 x 40',
            'Closed jongkok Ina',
            'Closed Duduk Volk',
            'Floor drain',
            'Bak Air',
            'Pipa 1/2"',
            'Lbow 1/2"',
            'Kran Air Plastik 1/2"',
            'SDD 1/2"',

            // N — ACI MINIMALIS
            'Semen TR-30 40 Kg Tiga Roda',
            'Semen Aci Putih 25 kg',

            // O — CAT
            'Mowilex weathercoat',
            'Nippon paint Q-LUC',

            // P — PAGAR
            'Box MCB tanam 3 grup',

            // Q — MINIMALIS CARPORT

            // R — TALANG AIR
            'Pipa 3"',
            'Cekakan pipa 2"',
            'Lbow 3" ke 2"',
            'Sambungan 3" ke 2"',
            'Penutup pipa 3"',
            'Lem pipa fox',

            // S — JENDELA
            'Aluminium white ink (openback)',
            'Aluminium white ink (m)',
            'Aluminium white ink (stoper casmen)',
            'List ornamen 20 mm white',
            'Klem sedang 13 x 26 x 1,4 mm',
            'Karet C besar HTM',
            'Karet cacing HTM',
            'Rambuncis dks white kanan',
            'Rambuncis dks white kiri',
            'Engsel casment glatino 8 inch',
            'Marks sosis black 620 ml',
            'Sekrup 8 x 3 rata',
            'Sekrup 8 x 1,5 rata @ 50 pcs',
            'Sekrup 8 x 1 bulat',
            'Sekrup 8 x 0,5 rata',
            'Rivet GT 429',
            'Fisher S6 vini star',
            'Reben 5, 203 cm x 102 cm asahi',
            'Marks sosis white 620 ml',

            // T — ELEKTRIKAL
            'Saklar tunggal broco',
            'Saklar dobel broco',
            'Stop kontak tunggal broco',
            'Kabel NYA 1,5 Eterna (100m)',
            'Kabel NYA 2,5 Eterna (100m)',
            'Kabel NYM 2 x 1,5 Jumbo (50m)',
            'Kabel NYM 2 x 2,5 Jumbo (50m)',
            'MCB 10A Powell',
            'MCB Kwh 1300 amper',
        ];

        foreach ($items as $name) {
            InventoryItem::firstOrCreate(
                ['nama' => $name],
                [
                    'harga'      => 0,
                    'stok_awal'  => 0,
                    'satuan'     => 'Unit',
                    'keterangan' => null,
                ]
            );
        }
    }
}
