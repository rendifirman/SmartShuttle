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
        Schema::table('jadwals', function (Blueprint $table) {
            // Tambahkan driver_id untuk jadwal yang di-assign langsung ke driver dengan mode AUTO_ACCEPT
            $table->foreignId('driver_id')->nullable()
                  ->after('shuttle_id')
                  ->constrained('users', 'id')
                  ->onDelete('set null')
                  ->comment('Driver yang ditugasi jadwal ini (AUTO_ACCEPT mode)');

            // Tandai apakah ini jadwal global untuk driver dengan MANUAL_CONFIRM mode
            $table->boolean('is_global_schedule')->default(false)
                  ->after('driver_id')
                  ->comment('Jadwal global yang dapat diambil oleh driver dengan MANUAL_CONFIRM mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['driver_id']);
            $table->dropColumn(['driver_id', 'is_global_schedule']);
        });
    }
};
