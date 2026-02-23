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
        Schema::create('rute_master_tarif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rutes')->onDelete('cascade');
            $table->foreignId('master_tarif_id')->constrained('master_tarif')->onDelete('cascade');
            $table->timestamps();

            // Index untuk performa query
            $table->unique(['rute_id', 'master_tarif_id']);
            $table->index('rute_id');
            $table->index('master_tarif_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rute_master_tarif');
    }
};
