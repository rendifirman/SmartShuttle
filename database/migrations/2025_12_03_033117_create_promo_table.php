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
        Schema::create('promo', function (Blueprint $table) {
            $table->id();
            $table->string('kode_promo')->unique();
            $table->string('nama_promo');
            $table->enum('jenis_diskon', ['persentase', 'nominal']);
            $table->decimal('nilai_diskon', 10, 2);
            $table->decimal('maksimal_diskon', 10, 2)->nullable();
            $table->decimal('minimal_pembelian', 10, 2)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->integer('kuota')->nullable();
            $table->integer('terpakai')->default(0);
            $table->boolean('status')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index(['kode_promo', 'status']);
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};