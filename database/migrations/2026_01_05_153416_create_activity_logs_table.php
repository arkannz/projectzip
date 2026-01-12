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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type'); // 'inventory', 'rab', 'angkutan', 'location', 'unit', 'type', 'print'
            $table->string('action'); // 'create', 'update', 'delete', 'print'
            $table->string('description');
            $table->string('model_type')->nullable(); // Nama model yang diubah
            $table->unsignedBigInteger('model_id')->nullable(); // ID model yang diubah
            $table->json('old_values')->nullable(); // Data lama (untuk update)
            $table->json('new_values')->nullable(); // Data baru
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['type', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
