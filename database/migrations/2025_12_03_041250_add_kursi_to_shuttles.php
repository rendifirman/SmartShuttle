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
        // Tambah kolom kursi di tabel shuttles
        Schema::table('shuttles', function (Blueprint $table) {
            $table->integer('total_kursi')->default(9)->after('kapasitas_kursi');
            $table->json('layout_kursi')->nullable()->after('total_kursi');
        });

        // Buat tabel kursi terpesan
        Schema::create('kursi_terpesan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->string('nomor_kursi');
            $table->foreignId('detail_penumpang_id')->nullable()->constrained('detail_penumpang')->onDelete('set null');
            $table->foreignId('pemesanan_id')->nullable()->constrained('pemesanan')->onDelete('cascade');
            $table->enum('status', ['tersedia', 'terpesan', 'terisi'])->default('tersedia');
            $table->timestamps();
            
            $table->unique(['jadwal_id', 'nomor_kursi']);
            $table->index(['jadwal_id', 'status']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            $table->dropColumn(['total_kursi', 'layout_kursi']);
        });
        
        Schema::dropIfExists('kursi_terpesan');
    }
};