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
        Schema::table('shuttles', function (Blueprint $table) {
            // Basic vehicle information
            $table->string('kode')->nullable()->after('id');
            $table->string('merk')->nullable()->after('nama_shuttle');
            $table->string('model')->nullable()->after('merk');
            $table->year('tahun')->nullable()->after('tipe_shuttle');
            $table->string('warna')->nullable()->after('tahun');

            // Legal documents
            $table->string('no_stnk')->nullable()->after('nomor_polisi');
            $table->date('masa_stnk')->nullable()->after('no_stnk');
            $table->string('no_kir')->nullable()->after('masa_stnk');
            $table->date('masa_kir')->nullable()->after('no_kir');

            // Ownership information
            $table->enum('jenis_kepemilikan', ['milik-perusahaan', 'sewa', 'vendor'])->nullable()->after('masa_kir');
            $table->string('nama_pemilik')->nullable()->after('jenis_kepemilikan');
            $table->date('tanggal_masuk')->nullable()->after('nama_pemilik');
            $table->decimal('nilai_asset', 15, 2)->nullable()->after('tanggal_masuk');

            // Insurance
            $table->string('asuransi')->nullable()->after('nilai_asset');
            $table->date('masa_asuransi')->nullable()->after('asuransi');

            // Contract information
            $table->text('masa_kontrak')->nullable()->after('masa_asuransi');

            // Equipment/facilities (JSON format for dynamic list)
            $table->json('kelengkapan')->nullable()->after('fasilitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            $table->dropColumn([
                'kode',
                'merk',
                'model',
                'tahun',
                'warna',
                'no_stnk',
                'masa_stnk',
                'no_kir',
                'masa_kir',
                'jenis_kepemilikan',
                'nama_pemilik',
                'tanggal_masuk',
                'nilai_asset',
                'asuransi',
                'masa_asuransi',
                'masa_kontrak',
                'kelengkapan'
            ]);
        });
    }
};
