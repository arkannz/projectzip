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
        Schema::table('rab_category_borongans', function (Blueprint $table) {
            $table->decimal('upah', 15, 2)->default(0)->after('borongan');
            $table->integer('progress')->default(0)->after('upah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab_category_borongans', function (Blueprint $table) {
            $table->dropColumn(['upah', 'progress']);
        });
    }
};


