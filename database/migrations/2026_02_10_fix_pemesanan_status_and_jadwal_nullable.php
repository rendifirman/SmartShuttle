<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make jadwal_id nullable and ensure status enum/check allows 'menunggu_kursi'
        // Uses raw SQL suitable for PostgreSQL
        DB::statement('ALTER TABLE IF EXISTS pemesanan ALTER COLUMN jadwal_id DROP NOT NULL');

        // Drop existing check constraint if present and recreate with expanded values
        DB::statement("DO $$
        BEGIN
            IF EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'pemesanan_status_check') THEN
                ALTER TABLE pemesanan DROP CONSTRAINT IF EXISTS pemesanan_status_check;
            END IF;
        END$$;");

        DB::statement("ALTER TABLE pemesanan ADD CONSTRAINT pemesanan_status_check CHECK (status IN ('menunggu_pembayaran','menunggu_konfirmasi','menunggu_kursi','diproses','dibayar','selesai','dibatalkan'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert jadwal_id to not null (only if column exists)
        DB::statement('ALTER TABLE IF EXISTS pemesanan ALTER COLUMN jadwal_id SET NOT NULL');

        // Restore previous check constraint without 'menunggu_kursi'
        DB::statement("DO $$
        BEGIN
            IF EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'pemesanan_status_check') THEN
                ALTER TABLE pemesanan DROP CONSTRAINT IF EXISTS pemesanan_status_check;
            END IF;
        END$$;");

        DB::statement("ALTER TABLE pemesanan ADD CONSTRAINT pemesanan_status_check CHECK (status IN ('menunggu_pembayaran','menunggu_konfirmasi','diproses','dibayar','selesai','dibatalkan'));");
    }
};
