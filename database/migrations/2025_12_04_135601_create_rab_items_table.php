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
        Schema::create('rab_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rab_category_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('location_id');

            $table->string('uraian');
            $table->integer('bahan_baku')->default(0);
            $table->integer('bahan_out')->default(0);
            $table->integer('harga_bahan')->default(0);
            $table->integer('total_harga')->default(0);
            $table->integer('upah')->default(0);
            $table->integer('borongan')->nullable();
            $table->integer('untung_rugi')->default(0);
            $table->integer('progres')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_items');
    }
};
