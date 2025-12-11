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
                'A' => 2293500,   // PONDASI
                'B' => 918600,    // URUG
                'C' => 650000,    // COR LANTAI
                'D' => 0,         // PINTU
                'E' => 3847500,   // BATA
                'F' => 0,
                'G' => 1072500,   // PLASTER
                'H' => 1529000,   // ATAP
                'I' => 450000,    // DEK
                'J' => 693000,    // MINIMALIS
                'K' => 0,         // CARPORT
                'L' => 1085000,   // KERAMIK
                'M' => 600105,    // WC
                'N' => 82500,     // ACI MINIMALIS
                'O' => 445000,    // CAT
                'P' => 265000,    // PAGAR
                'Q' => 900000,    // MINIMALIS CARPORT
                'R' => 0,
                'S' => 0,
                'T' => 0,
            ],

            // =============== TYPE 45 ===============
            '45' => [
                'A' => 3000000,
                'B' => 1200000,
                'C' => 700000,
                'D' => 0,
                'E' => 5000000,
                'F' => 0,
                'G' => 4500000,
                'H' => 2400000,
                'I' => 1400000,
                'J' => 1300000,
                'K' => 1200000,
                'L' => 2000000,
                'M' => 900000,
                'N' => 1200000,
                'O' => 2500000,
                'P' => 500000,
                'Q' => 600000,
                'R' => 0,
                'S' => 0,
                'T' => 0,
            ],

            // =============== TYPE 50 ===============
            // (Berdasarkan gambar laporan yang diberikan)
            '50' => [
                'A' => 3336000,   // PONDASI - Rp 3.336.000
                'B' => 1336000,   // URUG - Rp 1.336.000
                'C' => 800000,    // COR LANTAI - Rp 800.000
                'D' => 0,         // PINTU - dikerjakan sendiri
                'E' => 5560000,   // BATA - Rp 5.560.000
                'F' => 0,         // COR TIANG DEPAN
                'G' => 5000000,   // PLASTER - Rp 5.000.000
                'H' => 2672000,   // ATAP - Rp 2.672.000
                'I' => 1560000,   // DEK - Rp 1.560.000
                'J' => 1512000,   // MINIMALIS - Rp 1.512.000
                'K' => 1336000,   // CARPORT - Rp 1.336.000
                'L' => 2224000,   // KERAMIK - Rp 2.224.000
                'M' => 990000,    // WC - Rp 990.000
                'N' => 1302000,   // ACI MINIMALIS - Rp 1.302.000
                'O' => 2780000,   // CAT - Rp 2.780.000
                'P' => 534000,    // PAGAR - Rp 534.000
                'Q' => 663000,    // MINIMALIS CARPORT - Rp 663.000
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 55 ===============
            '55' => [
                'A' => 2293500,   // PONDASI
                'B' => 918600,    // URUG
                'C' => 550000,    // COR LANTAI
                'D' => 265000,   // PINTU
                'E' => 3847500,   // BATA
                'F' => 0,         // COR TIANG DEPAN
                'G' => 3437500,   // PLASTER
                'H' => 1837000,   // ATAP
                'I' => 1072500,    // DEK
                'J' => 693000,    // MINIMALIS
                'K' => 600105,    // CARPORT
                'L' => 1529000,   // KERAMIK
                'M' => 450000,    // WC
                'N' => 1085000,   // ACI MINIMALIS
                'O' => 1911250,   // CAT    
                'P' => 445000,    // PAGAR
                'Q' => 552500,    // MINIMALIS CARPORT
                'R' => 0,         // TALANG AIR
                'S' => 0,         // JENDELA
                'T' => 0,         // ELEKTRICAL
            ],

            // =============== TYPE 60 ===============
            '60' => [
                'A' => 4000000,
                'B' => 1700000,
                'C' => 1000000,
                'D' => 0,
                'E' => 6500000,
                'F' => 0,
                'G' => 6000000,
                'H' => 3200000,
                'I' => 1900000,
                'J' => 1800000,
                'K' => 1700000,
                'L' => 2600000,
                'M' => 1200000,
                'N' => 1600000,
                'O' => 3300000,
                'P' => 700000,
                'Q' => 800000,
                'R' => 0,
                'S' => 0,
                'T' => 0,
            ],

            // =============== TYPE 70 ===============
            '70' => [
                'A' => 4500000,
                'B' => 2000000,
                'C' => 1200000,
                'D' => 0,
                'E' => 7500000,
                'F' => 0,
                'G' => 7000000,
                'H' => 3700000,
                'I' => 2200000,
                'J' => 2100000,
                'K' => 2000000,
                'L' => 3000000,
                'M' => 1400000,
                'N' => 1900000,
                'O' => 3800000,
                'P' => 850000,
                'Q' => 950000,
                'R' => 0,
                'S' => 0,
                'T' => 0,
            ],

            // =============== TYPE 80 ===============
            '80' => [
                'A' => 5000000,
                'B' => 2300000,
                'C' => 1400000,
                'D' => 0,
                'E' => 8500000,
                'F' => 0,
                'G' => 8000000,
                'H' => 4200000,
                'I' => 2500000,
                'J' => 2400000,
                'K' => 2300000,
                'L' => 3400000,
                'M' => 1600000,
                'N' => 2200000,
                'O' => 4300000,
                'P' => 1000000,
                'Q' => 1100000,
                'R' => 0,
                'S' => 0,
                'T' => 0,
            ],

            // =============== TYPE 100 ===============
            '100' => [
                'A' => 6000000,
                'B' => 2800000,
                'C' => 1700000,
                'D' => 0,
                'E' => 10000000,
                'F' => 0,
                'G' => 9500000,
                'H' => 5000000,
                'I' => 3000000,
                'J' => 2900000,
                'K' => 2800000,
                'L' => 4000000,
                'M' => 1900000,
                'N' => 2700000,
                'O' => 5200000,
                'P' => 1200000,
                'Q' => 1300000,
                'R' => 0,
                'S' => 0,
                'T' => 0,
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
                'A' => 2800000, 'B' => 1100000, 'C' => 650000, 'D' => 0,
                'E' => 4500000, 'F' => 0, 'G' => 4000000, 'H' => 2200000,
                'I' => 1300000, 'J' => 1100000, 'K' => 1100000, 'L' => 1900000,
                'M' => 800000, 'N' => 1100000, 'O' => 2200000, 'P' => 450000,
                'Q' => 550000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '45' => [
                'A' => 3000000, 'B' => 1200000, 'C' => 700000, 'D' => 0,
                'E' => 5000000, 'F' => 0, 'G' => 4500000, 'H' => 2400000,
                'I' => 1400000, 'J' => 1300000, 'K' => 1200000, 'L' => 2000000,
                'M' => 900000, 'N' => 1200000, 'O' => 2500000, 'P' => 500000,
                'Q' => 600000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '50' => [
                'A' => 3336000, 'B' => 1336000, 'C' => 800000, 'D' => 0,
                'E' => 5560000, 'F' => 0, 'G' => 5000000, 'H' => 2672000,
                'I' => 1560000, 'J' => 1512000, 'K' => 1336000, 'L' => 2224000,
                'M' => 990000, 'N' => 1302000, 'O' => 2780000, 'P' => 534000,
                'Q' => 663000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '55' => [
                'A' => 2293500, 'B' => 918600, 'C' => 550000, 'D' => 265000,
                'E' => 3847500, 'F' => 0, 'G' => 3437500, 'H' => 1837000,
                'I' => 1072500, 'J' => 693000, 'K' => 600105, 'L' => 1529000,
                'M' => 450000, 'N' => 1085000, 'O' => 1911250, 'P' => 445000,
                'Q' => 552500, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '60' => [
                'A' => 4000000, 'B' => 1700000, 'C' => 1000000, 'D' => 0,
                'E' => 6500000, 'F' => 0, 'G' => 6000000, 'H' => 3200000,
                'I' => 1900000, 'J' => 1800000, 'K' => 1700000, 'L' => 2600000,
                'M' => 1200000, 'N' => 1600000, 'O' => 3300000, 'P' => 700000,
                'Q' => 800000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '70' => [
                'A' => 4500000, 'B' => 2000000, 'C' => 1200000, 'D' => 0,
                'E' => 7500000, 'F' => 0, 'G' => 7000000, 'H' => 3700000,
                'I' => 2200000, 'J' => 2100000, 'K' => 2000000, 'L' => 3000000,
                'M' => 1400000, 'N' => 1900000, 'O' => 3800000, 'P' => 850000,
                'Q' => 950000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '80' => [
                'A' => 5000000, 'B' => 2300000, 'C' => 1400000, 'D' => 0,
                'E' => 8500000, 'F' => 0, 'G' => 8000000, 'H' => 4200000,
                'I' => 2500000, 'J' => 2400000, 'K' => 2300000, 'L' => 3400000,
                'M' => 1600000, 'N' => 2200000, 'O' => 4300000, 'P' => 1000000,
                'Q' => 1100000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
            '100' => [
                'A' => 6000000, 'B' => 2800000, 'C' => 1700000, 'D' => 0,
                'E' => 10000000, 'F' => 0, 'G' => 9500000, 'H' => 5000000,
                'I' => 3000000, 'J' => 2900000, 'K' => 2800000, 'L' => 4000000,
                'M' => 1900000, 'N' => 2700000, 'O' => 5200000, 'P' => 1200000,
                'Q' => 1300000, 'R' => 0, 'S' => 0, 'T' => 0,
            ],
        ];
    }
}
