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
        Schema::create('shuttles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_shuttle');
            $table->string('tipe_shuttle')->nullable(); // Executive, Express, etc
            $table->integer('kapasitas_kursi');
            $table->string('fasilitas')->nullable();
            $table->string('nomor_polisi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'servis'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('shuttles');
    }
};
