<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();

            // relasi dengan type, unit, location (FK)
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('location_id');

            // data rab
            $table->string('uraian')->nullable();
            $table->string('kategori')->nullable(); // A, B, C, atau nomor
            $table->integer('bahan_baku')->nullable();
            $table->integer('bahan_out')->nullable();
            $table->integer('harga_bahan')->nullable();
            $table->integer('total_harga')->nullable();
            $table->integer('upah')->nullable();
            $table->integer('borongan')->nullable();
            $table->integer('untung_rugi')->nullable();
            $table->string('progres')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};
