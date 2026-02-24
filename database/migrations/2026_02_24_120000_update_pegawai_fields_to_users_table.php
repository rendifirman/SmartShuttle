<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update struktur pegawai fields: menghapus lokasi_kerja dan menggunakan branch_id
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom lokasi_kerja karena sekarang menggunakan branch_id
            if (Schema::hasColumn('users', 'lokasi_kerja')) {
                $table->dropColumn('lokasi_kerja');
            }

            // Ubah status menjadi enum untuk consistency dengan sistem
            // (verifikasi status column ada dan update jika diperlukan)
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kembali lokasi_kerja jika rollback
            if (!Schema::hasColumn('users', 'lokasi_kerja')) {
                $table->string('lokasi_kerja')->nullable();
            }

            // Jika diperlukan, dropdown status enum kembali ke string
            // (sesuaikan dengan kondisi production)
        });
    }
};
