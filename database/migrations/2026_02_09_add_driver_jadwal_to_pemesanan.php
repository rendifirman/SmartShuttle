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
            // Add driver_jadwal reference if not exists
            if (!Schema::hasColumn('pemesanan', 'id_jadwal_driver')) {
                $table->unsignedBigInteger('id_jadwal_driver')->nullable()->after('jadwal_id');
                $table->foreign('id_jadwal_driver')
                    ->references('id_jadwal_driver')
                    ->on('driver_jadwals')
                    ->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'id_jadwal_driver')) {
                $table->dropForeign(['id_jadwal_driver']);
                $table->dropColumn('id_jadwal_driver');
            }
        });
    }
};
