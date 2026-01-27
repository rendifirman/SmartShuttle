<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kursi_terpesan')) {
            return;
        }

        // PostgreSQL menggunakan DROP CONSTRAINT IF EXISTS
        // Ini seharusnya berjalan di PostgreSQL
        DB::statement('ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check');
        
        // Tambah constraint baru
        DB::statement("ALTER TABLE kursi_terpesan ADD CONSTRAINT kursi_terpesan_status_check CHECK (status IN ('terpesan', 'dibatalkan'))");
    }

    public function down(): void
    {
        if (!Schema::hasTable('kursi_terpesan')) {
            return;
        }

        DB::statement('ALTER TABLE kursi_terpesan DROP CONSTRAINT IF EXISTS kursi_terpesan_status_check');
    }
};