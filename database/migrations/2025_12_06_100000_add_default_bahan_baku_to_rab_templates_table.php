<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rab_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('rab_templates', 'default_bahan_baku')) {
                $table->integer('default_bahan_baku')->default(0)->after('item_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rab_templates', function (Blueprint $table) {
            $table->dropColumn('default_bahan_baku');
        });
    }
};
