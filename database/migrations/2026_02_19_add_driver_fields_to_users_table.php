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
            // Field untuk SIM
            $table->string('nomor_sim')->nullable()->after('nik')->comment('Nomor SIM Pengemudi');
            $table->date('masa_berlaku_sim')->nullable()->after('nomor_sim')->comment('Tanggal Masa Berlaku SIM');

            // Field untuk upload file
            $table->string('ktp_file')->nullable()->after('masa_berlaku_sim')->comment('File KTP');
            $table->string('sim_file')->nullable()->after('ktp_file')->comment('File SIM');
            $table->string('photo_file')->nullable()->after('sim_file')->comment('Foto Profil Driver');

            // ID Pengemudi yang dibuat otomatis
            $table->string('id_pengemudi')->nullable()->unique()->after('photo_file')->comment('ID Pengemudi Unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_sim',
                'masa_berlaku_sim',
                'ktp_file',
                'sim_file',
                'photo_file',
                'id_pengemudi',
            ]);
        });
    }
};
