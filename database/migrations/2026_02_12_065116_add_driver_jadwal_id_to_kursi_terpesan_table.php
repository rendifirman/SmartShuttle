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
        Schema::table('kursi_terpesan', function (Blueprint $table) {
            $table->foreignId('id_jadwal_driver')->nullable()->after('jadwal_id')->constrained('driver_jadwals', 'id_jadwal_driver')->onDelete('cascade');
            $table->index(['id_jadwal_driver', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursi_terpesan', function (Blueprint $table) {
            if (Schema::hasColumn('kursi_terpesan', 'id_jadwal_driver')) {
                $table->dropForeign(['id_jadwal_driver']);
                $table->dropColumn('id_jadwal_driver');
            }
        });
    }
};
