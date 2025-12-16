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
        // Cek apakah tabel pembayaran sudah ada
        if (!Schema::hasTable('pembayaran')) {
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
        } else {
            // Jika tabel sudah ada, tambahkan kolom yang mungkin belum ada
            Schema::table('pembayaran', function (Blueprint $table) {
                if (!Schema::hasColumn('pembayaran', 'no_virtual_account')) {
                    $table->string('no_virtual_account')->nullable()->after('status');
                }
                if (!Schema::hasColumn('pembayaran', 'qr_code')) {
                    $table->string('qr_code')->nullable()->after('no_virtual_account');
                }
                if (!Schema::hasColumn('pembayaran', 'nama_bank')) {
                    $table->string('nama_bank')->nullable()->after('qr_code');
                }
                if (!Schema::hasColumn('pembayaran', 'instruksi_pembayaran')) {
                    $table->text('instruksi_pembayaran')->nullable()->after('nama_bank');
                }
                if (!Schema::hasColumn('pembayaran', 'waktu_kadaluarsa')) {
                    $table->timestamp('waktu_kadaluarsa')->after('instruksi_pembayaran');
                }
                if (!Schema::hasColumn('pembayaran', 'waktu_pembayaran')) {
                    $table->timestamp('waktu_pembayaran')->nullable()->after('waktu_kadaluarsa');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jangan drop tabel jika sudah ada data
        // Hanya hapus kolom yang kita tambahkan
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'no_virtual_account',
                'qr_code',
                'nama_bank',
                'instruksi_pembayaran',
                'waktu_kadaluarsa',
                'waktu_pembayaran'
            ]);
        });
    }
};