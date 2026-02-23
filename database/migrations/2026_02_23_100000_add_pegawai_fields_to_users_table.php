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
        Schema::table('users', function (Blueprint $table) {
            // Add pegawai fields if they don't exist
            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable();
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('users', 'agama')) {
                $table->string('agama')->nullable();
            }
            if (!Schema::hasColumn('users', 'status_pernikahan')) {
                $table->string('status_pernikahan')->nullable();
            }
            if (!Schema::hasColumn('users', 'kontak_darurat')) {
                $table->string('kontak_darurat')->nullable();
            }
            if (!Schema::hasColumn('users', 'tanggal_bergabung')) {
                $table->date('tanggal_bergabung')->nullable();
            }
            if (!Schema::hasColumn('users', 'status_pegawai')) {
                $table->enum('status_pegawai', ['Tetap', 'Kontrak', 'Magang'])->nullable();
            }
            if (!Schema::hasColumn('users', 'masa_kerja')) {
                $table->string('masa_kerja')->nullable();
            }
            if (!Schema::hasColumn('users', 'posisi')) {
                $table->string('posisi')->nullable();
            }
            if (!Schema::hasColumn('users', 'lokasi_kerja')) {
                $table->string('lokasi_kerja')->nullable();
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable();
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
                'tempat_lahir',
                'alamat',
                'agama',
                'status_pernikahan',
                'kontak_darurat',
                'tanggal_bergabung',
                'status_pegawai',
                'masa_kerja',
                'posisi',
                'lokasi_kerja',
                'foto'
            ]);
        });
    }
};
