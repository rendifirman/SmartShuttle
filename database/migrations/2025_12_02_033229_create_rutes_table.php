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
        Schema::create('rutes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rute')->unique();
            $table->string('nama_rute');
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->string('durasi')->comment('Durasi dalam format HH:MM');
            $table->decimal('jarak', 8, 2)->nullable();
            $table->decimal('harga_dasar', 10, 2);
            $table->text('rute_pemberhentian')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            
            $table->unique(['kota_asal', 'kota_tujuan']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutes');
    }
};