<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RabCategory;

class RabCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['kode' => 'A', 'nama' => 'PONDASI'],
            ['kode' => 'B', 'nama' => 'URUG'],
            ['kode' => 'C', 'nama' => 'COR LANTAI'],
            ['kode' => 'D', 'nama' => 'BATA'],
            ['kode' => 'E', 'nama' => 'PLASTER'],
            ['kode' => 'F', 'nama' => 'ATAP'],
            ['kode' => 'G', 'nama' => 'DEK'],
            ['kode' => 'H', 'nama' => 'KRAMIK'],
            ['kode' => 'I', 'nama' => 'WC'],
            ['kode' => 'J', 'nama' => 'MINIMALIS'],
            ['kode' => 'K', 'nama' => 'CAT'],
            ['kode' => 'L', 'nama' => 'ACI MINIMALIS'],
            ['kode' => 'M', 'nama' => 'CARPORT'],
            ['kode' => 'N', 'nama' => 'MINIMALIS CARPORT'],
            ['kode' => 'O', 'nama' => 'PAGAR'],
            ['kode' => 'P', 'nama' => 'PINTU'],
            ['kode' => 'Q', 'nama' => 'WC 2'],
            ['kode' => 'R', 'nama' => 'TALANG AIR'],
            ['kode' => 'S', 'nama' => 'JENDELA'],
            ['kode' => 'T', 'nama' => 'ELEKTICAL'],
        ];

        foreach ($categories as $cat) {
            RabCategory::updateOrCreate(
                ['kode' => $cat['kode']],
                ['nama' => $cat['nama']]
            );
        }
    }
}
