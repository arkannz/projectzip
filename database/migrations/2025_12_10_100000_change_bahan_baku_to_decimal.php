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
        // Ubah bahan_baku di rab_type_values menjadi decimal
        Schema::table('rab_type_values', function (Blueprint $table) {
            $table->decimal('bahan_baku', 10, 2)->default(0)->change();
        });

        // Ubah bahan_baku, bahan_out, dan total_harga di rab_items menjadi decimal
        Schema::table('rab_items', function (Blueprint $table) {
            $table->decimal('bahan_baku', 10, 2)->default(0)->change();
            $table->decimal('bahan_out', 10, 2)->default(0)->change();
            $table->decimal('total_harga', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke integer
        Schema::table('rab_type_values', function (Blueprint $table) {
            $table->integer('bahan_baku')->default(0)->change();
        });

        Schema::table('rab_items', function (Blueprint $table) {
            $table->integer('bahan_baku')->default(0)->change();
        });
    }
};

