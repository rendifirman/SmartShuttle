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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shuttle_id')->constrained('shuttles')->onDelete('cascade');
            $table->date('tanggal_keberangkatan');
            $table->time('waktu_keberangkatan');
            $table->time('waktu_kedatangan');
            $table->decimal('harga_total', 10, 2);
            $table->integer('kursi_tersedia');
            $table->enum('status', ['tersedia', 'penuh', 'berangkat', 'dibatalkan'])->default('tersedia');
            $table->timestamps();
            
            $table->index(['tanggal_keberangkatan', 'status']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};