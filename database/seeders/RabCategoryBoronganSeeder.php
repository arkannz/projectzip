<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\RabCategory;
use App\Models\RabCategoryBorongan;

class RabCategoryBoronganSeeder extends Seeder
{
    public function run()
    {
        // =====================================================
        // BORONGAN PER KATEGORI PER TYPE
        // Setiap type memiliki nilai borongan berbeda per kategori
        // =====================================================

        $boronganData = [
            // =============== TYPE 36 ===============
            '36' => [
                'A' => 2500000,  // PONDASI
                'B' => 1000000,  // URUG
                'C' => 600000,   // COR LANTAI
                'D' => 0,        // PINTU
                'E' => 4000000,  // BATA
                'F' => 0,        // COR TIANG DEPAN
                'G' => 3500000,  // PLASTER
                'H' => 2000000,  // ATAP
                'I' => 1200000,  // DEK
                'J' => 1000000,  // MINIMALIS
                'K' => 1000000,  // CARPORT
                'L' => 1800000,  // KERAMIK
                'M' => 750000,   // WC
                'N' => 1000000,  // ACI MINIMALIS
                'O' => 2000000,  // CAT
                'P' => 400000,   // PAGAR
                'Q' => 500000,   // MINIMALIS CARPORT
                'R' => 0,        // TALANG AIR
                'S' => 0,        // JENDELA
                'T' => 0,        // ELEKTRICAL
            ],

           // =============== TYPE 40 ===============
            '40' => [
                'A' => 1668000,   // PONDASI
                'B' => 668000,     // URUG
                'C' => 400000,     // COR LANTAI
                'D' => 2780000,    // PINTU
                'E' => 2500000,    // BATA
                'F' => 1336000,    // COR TIANG DEPAN
                'G' => 780000,     // PLASTER
                'H' => 1112000,    // ATAP
                'I' => 450000,     // DEK
                'J' => 504000,     // MINIMALIS
                'K' => 1390000,    // CARPORT
                'L' => 434000,     // KERAMIK
                'M' => 600000,     // WC
                'N' => 552000,     // ACI MINIMALIS
                'O' => 445000,     // CAT
                'P' => 265000,     // PAGAR
                'Q' => 900000,     // MINIMALIS CARPORT
                'R' => 0,          // TALANG AIR
                'S' => 0,          // JENDELA
                'T' => 0,          // ELEKTRICAL
            ],

            // =============== TYPE 45 ===============
            '45' => [
                'A' => 1876500,    // PONDASI
                'B' => 751500,     // URUG
                'C' => 450000,     // COR LANTAI
                'D' => 3127500,    // PINTU
                'E' => 2812500,    // BATA
                'F' => 1503000,    // COR TIANG DEPAN
                'G' => 877500,     // PLASTER
                'H' => 1251000,    // ATAP
                'I' => 450000,     // DEK
                'J' => 567000,     // MINIMALIS
                'K' => 1563750,    // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600075,     // WC
                'N' => 552500,     // ACI MINIMALIS
                'O' => 445000,     // CAT
                'P' => 265000,     // PAGAR
                'Q' => 900000,     // MINIMALIS CARPORT
                'R' => 0,          // TALANG AIR
                'S' => 0,          // JENDELA
                'T' => 0,          // ELEKTRICAL
            ],

            // =============== TYPE 50 ===============
            '50' => [
                'A' => 2085000,   // PONDASI
                'B' => 835000,    // URUG
                'C' => 500000,    // COR LANTAI
                'D' => 3475000,   // PINTU
                'E' => 3125000,   // BATA
                'F' => 1670000,   // COR TIANG DEPAN
                'G' => 975000,    // PLASTER
                'H' => 1390000,   // ATAP
                'I' => 450000,     // DEK
                'J' => 630000,    // MINIMALIS
                'K' => 1737000,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600000,    // WC
                'N' => 552500,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 55 ===============
            '55' => [
                'A' => 2293500,   // PONDASI
                'B' => 918500,    // URUG
                'C' => 550000,    // COR LANTAI
                'D' => 38225000,  // PINTU
                'E' => 3437500,    // BATA
                'F' => 1837000,   // COR TIANG DEPAN
                'G' => 1072500,   // PLASTER
                'H' => 1529000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 693000,    // MINIMALIS
                'K' => 1911250,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600105,    // WC
                'N' => 552500,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 60 ===============
            '60' => [
                'A' => 2502000,   // PONDASI
                'B' => 1002000,   // URUG
                'C' => 600000,    // COR LANTAI
                'D' => 4170000,   // PINTU
                'E' => 3750000,   // BATA
                'F' => 2004000,   // COR TIANG DEPAN
                'G' => 1170000,   // PLASTER
                'H' => 1668000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 756000,    // MINIMALIS
                'K' => 2085000,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600000,    // WC
                'N' => 552000,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 70 ===============
            '70' => [
                'A' => 2919000,   // PONDASI
                'B' => 1196000,   // URUG
                'C' => 700000,    // COR LANTAI
                'D' => 4865000,   // PINTU
                'E' => 4375000,   // BATA
                'F' => 2338000,   // COR TIANG DEPAN
                'G' => 1365000,   // PLASTER
                'H' => 1946000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 882000,    // MINIMALIS
                'K' => 2432500,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600600,    // WC
                'N' => 552500,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 80 ===============
            '80' => [
                'A' => 3336000,   // PONDASI
                'B' => 1336000,   // URUG
                'C' => 800000,    // COR LANTAI
                'D' => 5560000,   // PINTU
                'E' => 5000000,   // BATA
                'F' => 2672000,   // COR TIANG DEPAN
                'G' => 1560000,   // PLASTER
                'H' => 2224000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 1008000,   // MINIMALIS
                'K' => 2780000,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600000,    // WC
                'N' => 552500,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 100 ===============
            '100' => [
                'A' => 4170000,   // PONDASI
                'B' => 1670000,   // URUG
                'C' => 1000000,   // COR LANTAI
                'D' => 6950000,   // PINTU
                'E' => 6250000,   // BATA
                'F' => 3340000,   // COR TIANG DEPAN
                'G' => 1950000,   // PLASTER
                'H' => 2780000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 1260000,   // MINIMALIS
                'K' => 3475000,   // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600000,    // WC
                'N' => 552500,    // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],
        ];

        // Ambil semua categories
        $categories = RabCategory::all()->keyBy('kode');

        foreach ($boronganData as $typeName => $categoryBorongans) {
            
            $type = Type::where('nama', $typeName)->first();
            
            if (!$type) {
                echo "⚠ Type {$typeName} tidak ditemukan!\n";
                continue;
            }

            foreach ($categoryBorongans as $kodeKategori => $nilaiBorongan) {
                
                $category = $categories->get($kodeKategori);
                
                if (!$category) {
                    echo "⚠ Kategori {$kodeKategori} tidak ditemukan!\n";
                    continue;
                }

                // Note: Borongan ini adalah nilai default per type
                // Akan di-copy ke RabCategoryBorongan saat RAB dibuat untuk unit tertentu
                // Atau bisa digunakan sebagai referensi default

                echo "✔ Type {$typeName} - {$category->nama}: Rp " . number_format($nilaiBorongan, 0, ',', '.') . "\n";
            }
        }

        echo "\n✔ SELESAI — DATA BORONGAN BERHASIL DIKONFIGURASI\n";
        echo "Note: Nilai borongan ini akan digunakan sebagai default saat membuat RAB baru.\n";
    }

    /**
     * Get default borongan value for a specific type and category
     * Dapat dipanggil dari controller atau service
     */
    public static function getDefaultBorongan($typeName, $kodeKategori)
    {
        $boronganData = self::getBoronganData();
        
        return $boronganData[$typeName][$kodeKategori] ?? 0;
    }

    /**
     * Get all borongan data
     */
    public static function getBoronganData()
    {
        return [
            '36' => [
                'A' => 2500000, 'B' => 1000000, 'C' => 600000, 'D' => 0,
                'E' => 4000000, 'F' => 0, 'G' => 3500000, 'H' => 2000000,
                'I' => 1200000, 'J' => 1000000, 'K' => 1000000, 'L' => 1800000,
                'M' => 750000, 'N' => 1000000, 'O' => 2000000, 'P' => 400000,
                'Q' => 500000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '40' => [
                'A' => 1668000, 'B' => 668000, 'C' => 400000, 'D' => 2780000,
                'E' => 2500000, 'F' => 1336000, 'G' => 780000, 'H' => 1112000,
                'I' => 450000, 'J' => 504000, 'K' => 1390000, 'L' => 434000,
                'M' => 600000, 'N' => 552000, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '45' => [
                'A' => 1876500, 'B' => 751500, 'C' => 450000, 'D' => 3127500,
                'E' => 2812500, 'F' => 1503000, 'G' => 877500, 'H' => 1251000,
                'I' => 450000, 'J' => 567000, 'K' => 1563750, 'L' => 1085000,
                'M' => 600075, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '50' => [
                'A' => 2085000, 'B' => 835000, 'C' => 500000, 'D' => 3475000,
                'E' => 3125000, 'F' => 1670000, 'G' => 975000, 'H' => 1390000,
                'I' => 450000, 'J' => 630000, 'K' => 1737000, 'L' => 1085000,
                'M' => 600000, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '55' => [
                'A' => 2293500, 'B' => 918500, 'C' => 550000, 'D' => 38225000,
                'E' => 3437500, 'F' => 1837000, 'G' => 1072500, 'H' => 1529000,
                'I' => 450000, 'J' => 693000, 'K' => 1911250, 'L' => 1085000,
                'M' => 600105, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '60' => [
                'A' => 2502000, 'B' => 1002000, 'C' => 600000, 'D' => 4170000,
                'E' => 3750000, 'F' => 2004000, 'G' => 1170000, 'H' => 1668000,
                'I' => 450000, 'J' => 756000, 'K' => 2085000, 'L' => 1085000,
                'M' => 600000, 'N' => 552000, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '70' => [
                'A' => 2919000, 'B' => 1196000, 'C' => 700000, 'D' => 4865000,
                'E' => 4375000, 'F' => 2338000, 'G' => 1365000, 'H' => 1946000,
                'I' => 450000, 'J' => 882000, 'K' => 2432500, 'L' => 1085000,
                'M' => 600600, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '80' => [
                'A' => 3336000, 'B' => 1336000, 'C' => 800000, 'D' => 5560000,
                'E' => 5000000, 'F' => 2672000, 'G' => 1560000, 'H' => 2224000,
                'I' => 450000, 'J' => 1008000, 'K' => 2780000, 'L' => 1085000,
                'M' => 600000, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '100' => [
                'A' => 4170000, 'B' => 1670000, 'C' => 1000000, 'D' => 6950000,
                'E' => 6250000, 'F' => 3340000, 'G' => 1950000, 'H' => 2780000,
                'I' => 450000, 'J' => 1260000, 'K' => 3475000, 'L' => 1085000,
                'M' => 600000, 'N' => 552500, 'O' => 445000, 'P' => 265000,
                'Q' => 900000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
        ];
    }
}