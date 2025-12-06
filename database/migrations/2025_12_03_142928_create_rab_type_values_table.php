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
        Schema::create('rab_type_values', function (Blueprint $table) {
            $table->id();

            // Type Rumah
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')
                ->references('id')->on('types')
                ->onDelete('cascade');

            // Mengacu ke uraian template
            $table->unsignedBigInteger('rab_template_id');
            $table->foreign('rab_template_id')
                ->references('id')->on('rab_templates')
                ->onDelete('cascade');

            // Jumlah bahan baku default
            $table->integer('bahan_baku')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_type_values');
    }
};
