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
        Schema::create('angkatans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode', 10); // PB, JK, dll
            $table->string('lokasi');
            $table->enum('angkutan', ['Pasir', 'Batu']);
            $table->integer('jumlah');
            $table->string('pangkalan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatans');
    }
};
