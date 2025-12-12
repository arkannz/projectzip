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
        Schema::create('rab_category_borongans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rab_category_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('location_id');
            $table->decimal('borongan', 15, 2)->default(0);
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->unique(['rab_category_id', 'type_id', 'unit_id', 'location_id'], 'rab_cat_borongan_unique');
            
            $table->foreign('rab_category_id')->references('id')->on('rab_categories')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_category_borongans');
    }
};
