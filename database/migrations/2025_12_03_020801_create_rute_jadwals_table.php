<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('rute_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('rute_id')->constrained('rutes')->onDelete('cascade');
            $table->integer('urutan')->default(1);
            $table->integer('durasi_segment')->nullable()->comment('Durasi dalam menit untuk segment ini');
            $table->decimal('harga_segment', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['jadwal_id', 'urutan']);
            $table->index(['jadwal_id', 'rute_id']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('rute_jadwals');
    }
};