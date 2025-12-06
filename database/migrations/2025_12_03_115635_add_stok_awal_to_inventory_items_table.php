<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->integer('stok_awal')->default(0)->after('harga');
            $table->integer('stok_akhir')->default(0)->after('stok_awal');
        });
    }

    public function down()
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn(['stok_awal', 'stok_akhir']);
        });
    }
};