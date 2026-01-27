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
        Schema::create('riwayat_cek_harga', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->decimal('berat', 8, 2);
            $table->decimal('panjang', 8, 2)->nullable();
            $table->decimal('lebar', 8, 2)->nullable();
            $table->decimal('tinggi', 8, 2)->nullable();
            $table->decimal('berat_volumetric', 8, 2)->nullable();
            $table->decimal('berat_terpakai', 8, 2);
            $table->decimal('harga_per_kg', 12, 2);
            $table->decimal('harga_total', 12, 2);
            $table->string('kode_harga');
            $table->integer('estimasi_hari');
            $table->timestamps();

            $table->index(['session_id', 'created_at']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_cek_harga');
    }
};
