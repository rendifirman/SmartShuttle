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
        Schema::create('master_tarif', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tarif')->unique();
            $table->string('nama_tarif');
            $table->enum('jenis_tarif', ['penumpang', 'paket', 'cargo', 'charter'])->default('penumpang');
            $table->string('sk_tarif')->nullable()->comment('Surat Keputusan/Nomor SK Tarif');
            $table->decimal('harga_dasar', 15, 2)->default(0);
            $table->decimal('harga_minimum', 15, 2)->default(0);
            $table->decimal('harga_maksimum', 15, 2)->nullable();
            $table->decimal('diskon_persentase', 5, 2)->default(0)->comment('Diskon dalam persen');
            $table->decimal('diskon_nominal', 15, 2)->default(0)->comment('Diskon dalam nominal');
            $table->string('keterangan')->nullable();
            $table->text('catatan')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');

            // Audit fields
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            // Index untuk query cepat
            $table->index('jenis_tarif');
            $table->index('status');
            $table->index('tanggal_berlaku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tarif');
    }
};
