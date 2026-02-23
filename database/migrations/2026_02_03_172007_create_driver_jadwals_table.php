<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_jadwals', function (Blueprint $table) {
            $table->id('id_jadwal_driver');
            $table->foreignId('id_jadwal')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('id_driver')->constrained('users')->onDelete('cascade');
            $table->string('rute');
            $table->date('tanggal');
            $table->string('armada');
            $table->time('waktu_keberangkatan');
            $table->time('waktu_kedatangan');
            $table->decimal('harga', 15, 2);
            $table->integer('total_kursi');
            $table->integer('kursi_terisi')->default(0);
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamp('waktu_diambil')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['id_jadwal', 'id_driver']);
            $table->index('status');
            $table->index('tanggal');
            $table->unique('id_jadwal'); // Satu jadwal admin hanya bisa diambil satu driver
            
            // Tambahkan index untuk pencarian customer
            $table->index(['status', 'tanggal']);
            $table->index(['rute', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_jadwals');
    }
};