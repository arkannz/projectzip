<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['nama' => '36'],
            ['nama' => '40'],
            ['nama' => '45'],
            ['nama' => '50'],
            ['nama' => '55'],
            ['nama' => '60'],
            ['nama' => '70'],
            ['nama' => '80'],
            ['nama' => '100'],
            ['nama' => '107'],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(
                ['nama' => $type['nama']],
                $type
            );
        }
    }
}
