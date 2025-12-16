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
        Schema::table('pemesanan', function (Blueprint $table) {
            // Hapus kolom yang mungkin sudah ada tapi namanya berbeda
            $columnsToDrop = [
                'status_pembayaran',
                'status_pemesanan',
                'tanggal_pembayaran',
                'waktu_pembayaran',
                'metode_pembayaran',
                'kode_promo'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('pemesanan', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Tambah kolom baru dengan struktur yang benar
            if (!Schema::hasColumn('pemesanan', 'status')) {
                $table->enum('status', [
                    'menunggu_pembayaran', 
                    'dibayar', 
                    'diproses', 
                    'selesai', 
                    'dibatalkan'
                ])->default('menunggu_pembayaran');
            }

            if (!Schema::hasColumn('pemesanan', 'tanggal_pembayaran')) {
                $table->date('tanggal_pembayaran')->nullable();
            }

            if (!Schema::hasColumn('pemesanan', 'waktu_pembayaran')) {
                $table->timestamp('waktu_pembayaran')->nullable();
            }

            if (!Schema::hasColumn('pemesanan', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable();
            }

            if (!Schema::hasColumn('pemesanan', 'kode_promo')) {
                $table->string('kode_promo')->nullable();
            }

            if (!Schema::hasColumn('pemesanan', 'waktu_kadaluarsa')) {
                $table->timestamp('waktu_kadaluarsa')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            // Kembalikan kolom jika perlu, hanya jika kolom ada
            $columnsToDrop = [
                'status',
                'tanggal_pembayaran',
                'waktu_pembayaran',
                'metode_pembayaran',
                'kode_promo',
                'waktu_kadaluarsa'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('pemesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};