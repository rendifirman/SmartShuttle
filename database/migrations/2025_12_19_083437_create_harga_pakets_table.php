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
        Schema::create('harga_paket', function (Blueprint $table) {
            $table->id();
            $table->string('kode_harga')->unique();
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->decimal('harga_per_kg', 12, 2);
            $table->decimal('harga_minimum', 12, 2);
            $table->decimal('harga_volume_per_cm3', 12, 4)->nullable();
            $table->integer('estimasi_hari_min');
            $table->integer('estimasi_hari_max');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['kota_asal', 'kota_tujuan', 'status']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_paket');
    }
};
