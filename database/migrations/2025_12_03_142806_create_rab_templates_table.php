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
        Schema::create('rab_templates', function (Blueprint $table) {
            $table->id();

            // Kategori (A–T)
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                ->references('id')->on('rab_categories')
                ->onDelete('cascade');

            // Nama uraian pekerjaan
            $table->string('uraian');

            $table->string('item_name');

            // Bisa null karena tidak semua uraian berasal dari inventory
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->foreign('inventory_item_id')
                ->references('id')->on('inventory_items')
                ->nullOnDelete(); // otomatis null jika item inventory dihapus

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_templates');
    }
};
