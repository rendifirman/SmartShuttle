<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan field Pendidikan & Keahlian serta Dokumen untuk Pegawai
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pendidikan & Keahlian fields
            if (!Schema::hasColumn('users', 'pendidikan_terakhir')) {
                $table->string('pendidikan_terakhir')->nullable()->after('lokasi_kerja');
            }
            if (!Schema::hasColumn('users', 'institusi')) {
                $table->string('institusi')->nullable()->after('pendidikan_terakhir');
            }
            if (!Schema::hasColumn('users', 'tahun_lulus')) {
                $table->string('tahun_lulus')->nullable()->after('institusi');
            }
            if (!Schema::hasColumn('users', 'keahlian')) {
                $table->text('keahlian')->nullable()->after('tahun_lulus');
            }
            if (!Schema::hasColumn('users', 'pengalaman_kerja')) {
                $table->text('pengalaman_kerja')->nullable()->after('keahlian');
            }

            // Dokumen fields
            if (!Schema::hasColumn('users', 'dokumen_ktp')) {
                $table->string('dokumen_ktp')->nullable()->after('pengalaman_kerja');
            }
            if (!Schema::hasColumn('users', 'dokumen_ijazah')) {
                $table->string('dokumen_ijazah')->nullable()->after('dokumen_ktp');
            }
            if (!Schema::hasColumn('users', 'dokumen_npwp')) {
                $table->string('dokumen_npwp')->nullable()->after('dokumen_ijazah');
            }
            if (!Schema::hasColumn('users', 'dokumen_skck')) {
                $table->string('dokumen_skck')->nullable()->after('dokumen_npwp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists([
                'pendidikan_terakhir',
                'institusi',
                'tahun_lulus',
                'keahlian',
                'pengalaman_kerja',
                'dokumen_ktp',
                'dokumen_ijazah',
                'dokumen_npwp',
                'dokumen_skck',
            ]);
        });
    }
};
