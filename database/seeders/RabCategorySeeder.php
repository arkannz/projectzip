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
            ['kode' => 'D', 'nama' => 'PINTU'],
            ['kode' => 'E', 'nama' => 'BATA'],
            ['kode' => 'F', 'nama' => 'COR TIANG DEPAN'],
            ['kode' => 'G', 'nama' => 'PLASTER'],
            ['kode' => 'H', 'nama' => 'ATAP'],
            ['kode' => 'I', 'nama' => 'DEK'],
            ['kode' => 'J', 'nama' => 'MINIMALIS'],
            ['kode' => 'K', 'nama' => 'CARPORT'],
            ['kode' => 'L', 'nama' => 'KERAMIK'],
            ['kode' => 'M', 'nama' => 'WC'],
            ['kode' => 'N', 'nama' => 'ACI MINIMALIS'],
            ['kode' => 'O', 'nama' => 'CAT'],
            ['kode' => 'P', 'nama' => 'PAGAR'],
            ['kode' => 'Q', 'nama' => 'MINIMALIS CARPORT'],
            ['kode' => 'R', 'nama' => 'TALANG AIR'],
            ['kode' => 'S', 'nama' => 'JENDELA'],
            ['kode' => 'T', 'nama' => 'ELEKTRICAL'],
        ];

        foreach ($categories as $cat) {
            RabCategory::updateOrCreate(
                ['kode' => $cat['kode']],
                ['nama' => $cat['nama']]
            );
        }
    }
}
