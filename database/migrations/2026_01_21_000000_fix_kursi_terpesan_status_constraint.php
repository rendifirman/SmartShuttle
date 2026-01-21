<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('kursi_terpesan')) {
            // Drop the existing check constraint
            DB::statement("ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check");

            // Add the updated check constraint including 'terisi' for booked seats after payment
            DB::statement("ALTER TABLE kursi_terpesan ADD CONSTRAINT kursi_terpesan_status_check CHECK (status IN ('terpesan', 'dibatalkan', 'dipilih', 'terisi'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kursi_terpesan')) {
            // Revert: Drop the updated constraint and add back the previous version
            DB::statement("ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check");
            DB::statement("ALTER TABLE kursi_terpesan ADD CONSTRAINT kursi_terpesan_status_check CHECK (status IN ('terpesan', 'dibatalkan', 'dipilih'))");
        }
    }
};
