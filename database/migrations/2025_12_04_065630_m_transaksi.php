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
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pembayaran_id')->constrained('pembayaran')->onDelete('cascade');
                $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
                $table->string('kode_transaksi')->unique();
                $table->decimal('jumlah', 12, 2);
                $table->decimal('biaya_admin', 10, 2)->default(0);
                $table->decimal('total', 12, 2);
                $table->text('catatan')->nullable();
                $table->string('bukti_pembayaran')->nullable();
                $table->timestamp('waktu_transaksi')->nullable();
                $table->timestamps();
                
                $table->index(['kode_transaksi', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};