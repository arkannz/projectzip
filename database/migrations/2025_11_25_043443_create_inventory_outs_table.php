<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');

            $table->integer('jumlah_keluar');

            // relasi ke project (lokasi + type + unit)
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('unit_id')->constrained('units');

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_outs');
    }
};
