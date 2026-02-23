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
            $table->foreignId('layanan_id')->nullable()->constrained('m_layanan', 'id_layanan')->onDelete('set null');
            $table->foreignId('master_harga_id')->nullable();
            $table->string('kode_rute')->unique();
            $table->string('nama_rute');
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->string('durasi')->comment('Durasi dalam format HH:MM');
            $table->decimal('jarak', 8, 2)->nullable();
            $table->decimal('harga_dasar', 10, 2);
            $table->text('rute_pemberhentian')->nullable();
            $table->json('segment_details')->nullable()->comment('Detail segment-segment dalam rute');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->unique(['kota_asal', 'kota_tujuan']);
            $table->index(['layanan_id', 'status']);
            $table->index(['master_harga_id']);
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
