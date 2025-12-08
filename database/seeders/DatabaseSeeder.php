<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([   
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Memanggil Seeder
        $this->call([
            // Master Data
            InventoryItemSeeder::class,  // Data inventory dengan harga bahan
            TypeSeeder::class,           // Type rumah (36, 40, 45, 50, 55, 60, 70, 80, 100)
            
            // RAB Template & Categories
            RabCategorySeeder::class,    // Kategori RAB (A-T)
            RabTemplateSeeder::class,    // Template RAB items
            
            // RAB Type Values (bahan_baku per type)
            RabType50Seeder::class,      // Data bahan_baku untuk type 50
            
            // RAB Borongan per Category per Type
            RabCategoryBoronganSeeder::class,  // Data borongan default per kategori per type
        ]);

    }
}
