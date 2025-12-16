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
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('metode_pembayaran', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('aktif');
            }
            if (!Schema::hasColumn('metode_pembayaran', 'nama_rekening')) {
                $table->string('nama_rekening')->nullable()->after('nomor_rekening');
            }
            if (!Schema::hasColumn('metode_pembayaran', 'gambar')) {
                $table->string('gambar')->nullable()->after('nama_rekening');
            }
            if (!Schema::hasColumn('metode_pembayaran', 'urutan')) {
                $table->integer('urutan')->default(0)->after('gambar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('metode_pembayaran', 'nomor_rekening')) {
                $table->dropColumn('nomor_rekening');
            }
            if (Schema::hasColumn('metode_pembayaran', 'nama_rekening')) {
                $table->dropColumn('nama_rekening');
            }
            if (Schema::hasColumn('metode_pembayaran', 'gambar')) {
                $table->dropColumn('gambar');
            }
            if (Schema::hasColumn('metode_pembayaran', 'urutan')) {
                $table->dropColumn('urutan');
            }
        });
    }
};
