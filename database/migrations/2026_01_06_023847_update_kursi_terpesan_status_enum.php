<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan hanya status 'terpesan' dan 'dibatalkan' yang valid
        DB::statement("ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check");
        DB::statement("ALTER TABLE kursi_terpesan ADD CONSTRAINT kursi_terpesan_status_check CHECK (status IN ('terpesan', 'dibatalkan'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check");
    }
};
