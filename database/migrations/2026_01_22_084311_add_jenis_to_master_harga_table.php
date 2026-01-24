<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisToMasterHargaTable extends Migration
{
    public function up()
    {
        Schema::table('master_harga', function (Blueprint $table) {
            // Tambahkan kolom jenis jika belum ada
            if (!Schema::hasColumn('master_harga', 'jenis')) {
                $table->string('jenis', 50)->nullable()->after('id');
            }
            
            // Pastikan kolom untuk scope aktif ada
            if (!Schema::hasColumn('master_harga', 'status_aktif')) {
                $table->boolean('status_aktif')->default(true);
            }
            if (!Schema::hasColumn('master_harga', 'tanggal_berlaku')) {
                $table->dateTime('tanggal_berlaku')->nullable();
            }
            if (!Schema::hasColumn('master_harga', 'tanggal_kadaluarsa')) {
                $table->dateTime('tanggal_kadaluarsa')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('master_harga', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'status_aktif', 'tanggal_berlaku', 'tanggal_kadaluarsa']);
        });
    }
}