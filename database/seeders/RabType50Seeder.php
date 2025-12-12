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

        // -----------------------------------------------
        // 1. DAFTAR BAHAN BAKU TYPE 50 (final dari kamu)
        // -----------------------------------------------
        $values = [

            // A — PONDASI
            'Besi ø6' => 12,
            'Besi ø8 Ulir' => 41,
            'Kayu 3/5' => 35,
            'Cerucuk' => 74,
            'Papan Mal' => 164,
            'Pasir' => 13,
            'Batu' => 2,
            'Semen PCC 40 Kg Tiga Roda' => 38,

            // B — URUG
            'Pasir ' => 32,
            'Pipa 4"' => 2,
            'Pipa 2"' => 2,

            // C — COR LANTAI
            'Besi ø6' => 15,
            'Pasir' => 5,
            'Semen PCC 40 Kg Tiga Roda' => 14,
            'Plastik cor' => 6,

            // D — PINTU
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
            'Slot 6 "' => 1,
            'Slot 4"' => 1,

            // E — BATA
            'Papan' => 24,
            'Kayu 3/5' => 25,
            'Besi ø6' => 5,
            'Besi ø8 ' => 8,
            'Kusen Pintu ( 2 Daun )' => 1,
            'Kusen Pintu ( 1 Daun )' => 3,
            'Bata' => 2329,
            'Pasir' => 5,
            'Semen PCC 40 Kg Tiga Roda' => 19,
            'Besi ø6 ' => 5,

            // F — COR TIANG DEPAN
            'Semen PCC 40 Kg Tiga Roda' => 5,
            'Pasir' => 1,
            'Batu' => 2,
            'Besi ø6 ' => 3,
            'Besi ø8' => 4,
            'Multiplek' => 1,
            'Kayu 3/5' => 20,
            'Bata' => 74,
            'Cerucuk' => 7,
            'Papan Mal' => 6,

            // G — PLASTER
            'Pipa 5/8' => 6,

            // H — ATAP
            'Kayu 3/5' => 60,
            'Kayu 4/6' => 40,
            'Kayu 5/7' => 29,
            'Lisplank' => 9,
            'Seng  Metal 4 Susun (merah)' => 68,
            'Seng  Metal 2 Susun (hitam)' => 0,
            'Perabung Hitam' => 0,
            'Perabung Merah' => 1.5,
            'Paku 2 inch' => 1,
            'Paku 3 inch' => 1,

            // I — DEK
            'Gypsum' => 23,
            'Baut' => 4,
            'Paku Beton 4 inch' => 1,
            'Paku 3 imch' => 1,
            'paku 2 inch' => 1,
            'Kayu 3/5' => 86,

            // J — MINIMALIS
            'Keramik 60 x 60 Blackmatt' => 7,
            'Semen PCC 40 Kg Tiga Roda' => 9,
            'Pasir' => 3,
            'Bata' => 109,

            // K — CARPORT
            'Keramik 60 x 60 Blackmatt' => 31,
            'Plastik cor' => 7,
            'Papan Mal' => 10,
            'Cerucuk' => 5,
            'Kayu 3/5' => 8,
            'Semen PCC 40 Kg Tiga Roda' => 22,
            'Batu' => 1,
            'Besi ø6' => 2,
            'Besi ø8' => 15,
            'Pasir' => 6,

            // L — KERAMIK
            'Keramik 60 x 60 Cream' => 32,
            'Keramik 60 x 60 Blackmatt' => 13,
            'Semen PCC 40 Kg Tiga Roda' => 22,
            'Pasir' => 5,
            'Oker' => 5,

            // M — WC
            'Closed jongkok Ina' => 2,
            'Closed Duduk Volk' => 1,
            'Keramik 30 x 30 ' => 3,
            'Keramik 25 x 40' => 11,
            'Floor drain' => 2,
            'Semen PCC 40 Kg Tiga Roda' => 4,
            'Pasir' => 1,
            'Bak Air' => 2,
            'Pipa 1/2"' => 2,
            'Lbow 1/2"' => 6,
            'Kran Air Plastik 1/2"' => 3,
            'SDD 1/2"' => 1,

            // N — ACI MINIMALIS
            'Semen TR-30 40 Kg Tiga Roda' => 3,

            // O — CAT
            'Mowilex weathercoat' => 1,
            'Nippon pain  Q--luc' => 3,
            'Semen TR-30 40 Kg Tiga Roda' => 12,
            'Semen Aci Putih 25 kg' => 1,

            // P — PAGAR
            'Besi ø6 ' => 2,
            'Besi ø8' => 4,
            'Pasir' => 1,
            'Semen PCC 40 Kg Tiga Roda' => 3,
            'Bata' => 100,

            // Q — MINIMALIS CARPORT
            'Semen PCC 40 Kg Tiga Roda' => 9,
            'Pasir' => 2,

            // R — TALANG AIR
            'Pipa 3"' => 1,
            'Pipa 2 "' => 2,
            'Cekakan pipa 2 "' => 2,
            'Lbow 3" ke 2"' => 1,
            'Sambungan 3" ke 2"' => 1,
            'Lbow 2"' => 3,
            'Penutup pipa 3"' => 1,
            'Lem pipa fox' => 1,

            // S — JENDELA
            'Aluminium white ink (openback)' => 4,
            'Aluminium white ink (m)' => 2,
            'Aluminium white ink (stoper casmen)' => 6,
            'List ornamen 20 mm white(0117,4)' => 6,
            'Klem sedang 13 x 26 x1,4 mm (KSD)' => 1,
            'Karet C besar htm 35 m (0788)' => 2,
            'Karet cacing htm 35 m (606lkh)' => 2,
            'Rambuncis dks white kanan (425,4)' => 3,
            'Rambuncis dks white kiri (425,4)' => 2,
            'Engsel casment glatino 8 inch' => 5,
            'Marks sosis black 620 ml (msb)' => 4,
            'Sekrup 8 x 3 rata' => 25,
            'Sekrup 8 x 1,5 rata @ 50 pcs' => 40,
            'Sekrup 8 x 1 bulat' => 40,
            'Sekrup 8 x 0,5 rata' => 80,
            'Rivet gt 429' => 1,
            'Fisher S6 vini star' => 1,
            'Reben 5, 203 cm x 102 cm asahi (r8040)' => 6,
            'Marks sosis white 620 ml (msw)' => 4,

            // T — ELEKTRICAL
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
        ];

        // ----------------------------------------------------
        // 2. MATCH KE TEMPLATE & INSERT
        // ----------------------------------------------------
        foreach ($values as $itemName => $qty) {

            $tpl = RabTemplate::where('item_name', $itemName)->first();

            if (!$tpl) {
                echo "⚠ ITEM TIDAK DITEMUKAN DI TEMPLATE: $itemName\n";
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

        echo "✔ SELESAI — DATA TYPE 50 BERHASIL DIIMPORT\n";
    }
}
