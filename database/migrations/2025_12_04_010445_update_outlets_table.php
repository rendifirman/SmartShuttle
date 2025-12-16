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
        Schema::table('outlets', function (Blueprint $table) {
            // Hapus kolom kota karena sudah ada di branch
            $table->dropColumn('kota');
            
            // Tambahkan foreign key ke branches
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            
            // Ubah nama kolom untuk lebih spesifik
            $table->renameColumn('alamat', 'alamat_lengkap');
            $table->renameColumn('gambar', 'foto_outlet');
            
            // Tambahkan kolom baru
            $table->string('tipe_outlet')->nullable(); // Terminal, Stasiun, Pusat Perbelanjaan, dll
            $table->integer('kapasitas_parkir')->nullable();
            $table->boolean('tersedia_toilet')->default(false);
            $table->boolean('tersedia_musholla')->default(false);
            $table->boolean('tersedia_atm')->default(false);
            $table->boolean('tersedia_wifi')->default(false);
            $table->string('zona_pelayanan')->nullable();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->string('kota')->nullable();
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->renameColumn('alamat_lengkap', 'alamat');
            $table->renameColumn('foto_outlet', 'gambar');
            $table->dropColumn([
                'tipe_outlet',
                'kapasitas_parkir',
                'tersedia_toilet',
                'tersedia_musholla',
                'tersedia_atm',
                'tersedia_wifi',
                'zona_pelayanan'
            ]);
        });
    }
};