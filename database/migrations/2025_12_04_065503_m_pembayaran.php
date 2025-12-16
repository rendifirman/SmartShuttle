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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
            $table->string('kode_pembayaran')->unique();
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode', ['qris', 'bca_va', 'mandiri_va', 'bni_va', 'bri_va', 'gopay', 'ovo', 'dana', 'shopeepay'])->default('qris');
            $table->enum('status', ['menunggu', 'diproses', 'berhasil', 'gagal', 'kadaluarsa'])->default('menunggu');
            $table->string('no_virtual_account')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('nama_bank')->nullable();
            $table->text('instruksi_pembayaran')->nullable();
            $table->timestamp('waktu_kadaluarsa');
            $table->timestamp('waktu_pembayaran')->nullable();
            $table->timestamps();
            
            $table->index(['kode_pembayaran', 'status']);
            $table->index(['pemesanan_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};