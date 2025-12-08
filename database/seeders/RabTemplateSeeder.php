<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RabCategory;
use App\Models\RabTemplate;

class RabTemplateSeeder extends Seeder
{
    public function run()
    {
        $categories = RabCategory::pluck('id', 'kode')->toArray();

        $data = [

            // ============================
            // A — PONDASI
            // ============================
            ['A', 'Besi ø6'],
            ['A', 'Besi ø8 Ulir'],
            ['A', 'Kayu 3/5'],
            ['A', 'Cerucuk'],
            ['A', 'Papan Mal'],
            ['A', 'Pasir'],
            ['A', 'Batu'],
            ['A', 'Semen PCC 40 Kg Tiga Roda'],

            // ============================
            // B — URUG
            // ============================
            ['B', 'Pasir'],
            ['B', 'Pipa 4"'],
            ['B', 'Pipa 2"'],

            // ============================
            // C — COR LANTAI
            // ============================
            ['C', 'Besi ø6'],
            ['C', 'Pasir'],
            ['C', 'Semen PCC 40 Kg Tiga Roda'],
            ['C', 'Plastik cor'],

            // ============================
            // D — PINTU
            // ============================
            ['D', 'Pintu Kayu 120 x 220'],
            ['D', 'Pintu Kayu 80 x 220'],
            ['D', 'Pintu WC PVC Biru'],
            ['D', 'Pintu WC Edenjoice Putih'],
            ['D', 'Engsel Pintu 4"'],
            ['D', 'Peganggan pintu 2 daun'],
            ['D', 'Pengunci Pintu 2 daun'],
            ['D', 'Pegangan kunci 1 daun'],
            ['D', 'Avian'],
            ['D', 'Tinner Nb Kaleng'],
            ['D', 'Slot 6"'],
            ['D', 'Slot 4"'],

            // ============================
            // E — BATA
            // ============================
            ['E', 'Papan'],
            ['E', 'Kayu 3/5'],
            ['E', 'Besi ø6'],
            ['E', 'Besi ø8'],
            ['E', 'Kusen Pintu (2 Daun)'],
            ['E', 'Kusen Pintu (1 Daun)'],
            ['E', 'Bata'],
            ['E', 'Semen PCC 40 Kg Tiga Roda'],
            ['E', 'Pasir'],

            // ============================
            // F — COR TIANG DEPAN
            // ============================
            ['F', 'Semen PCC 40 Kg Tiga Roda'],
            ['F', 'Pasir'],
            ['F', 'Batu'],
            ['F', 'Besi ø6'],
            ['F', 'Besi ø8'],
            ['F', 'Multiplek'],
            ['F', 'Kayu 3/5'],
            ['F', 'Bata'],
            ['F', 'Cerucuk'],
            ['F', 'Papan Mal'],

            // ============================
            // G — PLASTER
            // ============================
            ['G', 'Semen PCC 40 Kg Tiga Roda'],
            ['G', 'Pasir'],
            ['G', 'Pipa 5/8'],

            // ============================
            // H — ATAP
            // ============================
            ['H', 'Kayu 3/5'],
            ['H', 'Kayu 4/6'],
            ['H', 'Kayu 5/7'],
            ['H', 'Lisplank'],
            ['H', 'Seng Metal 4 Susun (merah)'],
            ['H', 'Seng Metal 2 Susun (hitam)'],
            ['H', 'Perabung Hitam'],
            ['H', 'Perabung Merah'],
            ['H', 'Paku 2 inch'],
            ['H', 'Paku 3 inch'],

            // ============================
            // I — DEK
            // ============================
            ['I', 'Kayu 3/5'],
            ['I', 'Gypsum'],
            ['I', 'Baut'],
            ['I', 'Paku Beton 4 inch'],
            ['I', 'Paku 3 inch'],
            ['I', 'Paku 2 inch'],

            // ============================
            // J — MINIMALIS
            // ============================
            ['J', 'Bata'],
            ['J', 'Pasir'],
            ['J', 'Semen PCC 40 Kg Tiga Roda'],
            ['J', 'Keramik 60 x 60 Blackmatt'],

            // ============================
            // K — CARPORT
            // ============================
            ['K', 'Semen PCC 40 Kg Tiga Roda'],
            ['K', 'Kayu 3/5'],
            ['K', 'Cerucuk'],
            ['K', 'Papan Mal'],
            ['K', 'Plastik cor'],
            ['K', 'Besi ø6'],
            ['K', 'Besi ø8'],
            ['K', 'Batu'],
            ['K', 'Pasir'],
            ['K', 'Keramik 60 x 60 Blackmatt'],

            // ============================
            // L — KERAMIK
            // ============================
            ['L', 'Keramik 60 x 60 Cream'],
            ['L', 'Keramik 60 x 60 Blackmatt'],
            ['L', 'Pasir'],
            ['L', 'Semen PCC 40 Kg Tiga Roda'],
            ['L', 'Oker'],

            // ============================
            // M — WC
            // ============================
            ['M', 'Keramik 30 x 30'],
            ['M', 'Keramik 25 x 40'],
            ['M', 'Closed jongkok Ina'],
            ['M', 'Closed Duduk Volk'],
            ['M', 'Floor drain'],
            ['M', 'Pasir'],
            ['M', 'Semen PCC 40 Kg Tiga Roda'],
            ['M', 'Bak Air'],
            ['M', 'Pipa 1/2"'],
            ['M', 'Lbow 1/2"'],
            ['M', 'Kran Air Plastik 1/2"'],
            ['M', 'SDD 1/2"'],

            // ============================
            // N — ACI MINIMALIS
            // ============================
            ['N', 'Semen TR-30 40 Kg Tiga Roda'],

            // ============================
            // O — CAT
            // ============================
            ['O', 'Mowilex weathercoat'],
            ['O', 'Nippon paint Q-LUC'],
            ['O', 'Semen TR-30 40 Kg Tiga Roda'],
            ['O', 'Semen Aci Putih 25 kg'],

            // ============================
            // P — PAGAR
            // ============================
            ['P', 'Bata'],
            ['P', 'Pasir'],
            ['P', 'Semen PCC 40 Kg Tiga Roda'],
            ['P', 'Besi ø6'],
            ['P', 'Besi ø8'],

            // ============================
            // Q — MINIMALIS CARPORT
            // ============================
            ['Q', 'Semen PCC 40 Kg Tiga Roda'],
            ['Q', 'Pasir'],

            // ============================
            // R — TALANG AIR
            // ============================
            ['R', 'Pipa 3"'],
            ['R', 'Pipa 2"'],
            ['R', 'Cekakan pipa 2"'],
            ['R', 'Lbow 3" ke 2"'],
            ['R', 'Sambungan 3" ke 2"'],
            ['R', 'Lbow 2"'],
            ['R', 'Penutup pipa 3"'],
            ['R', 'Lem pipa fox'],

            // ============================
            // S — JENDELA
            // ============================
            ['S', 'Aluminium white ink (openback)'],
            ['S', 'Aluminium white ink (m)'],
            ['S', 'Aluminium white ink (stoper casmen)'],
            ['S', 'List ornamen 20 mm white'],
            ['S', 'Klem sedang 13 x 26 x 1,4 mm'],
            ['S', 'Karet C besar HTM'],
            ['S', 'Karet cacing HTM'],
            ['S', 'Rambuncis dks white kanan'],
            ['S', 'Rambuncis dks white kiri'],
            ['S', 'Engsel casment glatino 8 inch'],
            ['S', 'Marks sosis black 620 ml'],
            ['S', 'Sekrup 8 x 3 rata'],
            ['S', 'Sekrup 8 x 1,5 rata @ 50 pcs'],
            ['S', 'Sekrup 8 x 1 bulat'],
            ['S', 'Sekrup 8 x 0,5 rata'],
            ['S', 'Rivet GT 429'],
            ['S', 'Fisher S6 vini star'],
            ['S', 'Reben 5, 203 cm x 102 cm asahi'],
            ['S', 'Marks sosis white 620 ml'],

            // ============================
            // T — ELEKTRICAL
            // ============================
            ['T', 'Saklar tunggal broco'],
            ['T', 'Saklar dobel broco'],
            ['T', 'Stop kontak tunggal broco'],
            ['T', 'Kabel NYA 1,5 Eterna (100m)'],
            ['T', 'Kabel NYA 2,5 Eterna (100m)'],
            ['T', 'Kabel NYM 2 x 1,5 Jumbo (50m)'],
            ['T', 'Kabel NYM 2 x 2,5 Jumbo (50m)'],
            ['T', 'MCB 10A Powell'],
            ['T', 'Box MCB tanam 3 grup'],
            ['T', 'MCB Kwh 1300 amper'],
        ];

        foreach ($data as $d) {
            RabTemplate::create([
                'category_id'        => $categories[$d[0]],
                'uraian'             => $d[1],
                'item_name'          => $d[1],
                'default_bahan_baku' => 0,
            ]);
        }
    }
}
