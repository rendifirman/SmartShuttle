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
                'kode_promo',
                'outlet_asal_id',
                'outlet_tujuan_id'
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
                    'menunggu_konfirmasi',
                    'diproses',
                    'dibayar',
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

            if (!Schema::hasColumn('pemesanan', 'outlet_asal_id')) {
                $table->unsignedBigInteger('outlet_asal_id')->nullable();
                $table->foreign('outlet_asal_id')->references('id')->on('outlets')->onDelete('set null');
            }

            if (!Schema::hasColumn('pemesanan', 'outlet_tujuan_id')) {
                $table->unsignedBigInteger('outlet_tujuan_id')->nullable();
                $table->foreign('outlet_tujuan_id')->references('id')->on('outlets')->onDelete('set null');
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
                'waktu_kadaluarsa',
                'outlet_asal_id',
                'outlet_tujuan_id'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('pemesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
