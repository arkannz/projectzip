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
            InventoryItemSeeder::class,
            TypeSeeder::class,           // Type rumah (36, 45, 50, 60, 70, 90)
            
            // RAB Template & Categories
            RabCategorySeeder::class,
            RabTemplateSeeder::class,
            
            // RAB Type Values (bahan_baku per type)
            RabType50Seeder::class,      // Data bahan_baku untuk type 50
        ]);

    }
}
