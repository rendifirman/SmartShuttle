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
            // Tambahkan kolom schedule_accept_mode dengan default value AUTO_ACCEPT
            $table->enum('schedule_accept_mode', ['AUTO_ACCEPT', 'MANUAL_CONFIRM'])
                  ->default('AUTO_ACCEPT')
                  ->after('status')
                  ->comment('Mode penerimaan jadwal untuk driver: AUTO_ACCEPT (langsung aktif) atau MANUAL_CONFIRM (perlu diambil dari jadwal global)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('schedule_accept_mode');
        });
    }
};
