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
        Schema::create('rab_categories', function (Blueprint $table) {
            $table->id();
            $table->string('kode'); // A, B, C, ..., T
            $table->string('nama'); // Pondasi, Urug, dst
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_categories');
    }
};