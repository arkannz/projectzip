<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : null;

        $activities = [
            [
                'user_id' => $userId,
                'type' => 'inventory',
                'action' => 'create',
                'description' => 'Menambah bahan baru: Semen Portland',
                'model_type' => 'App\Models\InventoryItem',
                'model_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $userId,
                'type' => 'inventory',
                'action' => 'update',
                'description' => 'Update harga bahan: Pasir menjadi Rp 150.000',
                'model_type' => 'App\Models\InventoryItem',
                'model_id' => 2,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5),
            ],
            [
                'user_id' => $userId,
                'type' => 'angkutan',
                'action' => 'create',
                'description' => 'Menambah data angkutan Pasir 100m³ di Lokasi A',
                'model_type' => 'App\Models\Angkutan',
                'model_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $userId,
                'type' => 'rab',
                'action' => 'create',
                'description' => 'Membuat RAB Type 45 untuk Unit A1',
                'model_type' => 'App\Models\Rab',
                'model_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $userId,
                'type' => 'print',
                'action' => 'print',
                'description' => 'Print/Export laporan inventory',
                'model_type' => null,
                'model_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $userId,
                'type' => 'master',
                'action' => 'create',
                'description' => 'Menambah lokasi baru: Perumahan ABC',
                'model_type' => 'App\Models\Location',
                'model_id' => 1,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create($activity);
        }
    }
}
