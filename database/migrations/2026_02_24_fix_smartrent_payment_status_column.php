<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom payment_status ke STRING di PostgreSQL
        Schema::table('smartrent_transactions', function (Blueprint $table) {
            DB::statement('ALTER TABLE smartrent_transactions ALTER COLUMN payment_status TYPE VARCHAR(50) USING payment_status::VARCHAR(50)');
            DB::statement("ALTER TABLE smartrent_transactions ALTER COLUMN payment_status SET DEFAULT 'unpaid'");
        });
        
        // Normalisasi nilai
        $this->normalizePaymentStatuses();
    }

    private function normalizePaymentStatuses()
    {
        $updates = [
            ['settlement', 'paid'],
            ['success', 'paid'],
            ['completed', 'paid'],
            ['lunas', 'paid'],
            ['waiting', 'pending'],
            ['menunggu', 'pending'],
            ['process', 'pending'],
            ['expired', 'failed'],
            ['cancelled', 'failed'],
            ['batal', 'failed'],
            ['rejected', 'failed'],
        ];
        
        foreach ($updates as [$old, $new]) {
            DB::table('smartrent_transactions')
                ->where('payment_status', $old)
                ->update(['payment_status' => $new]);
        }
    }

    public function down(): void
    {
        // Kembalikan ke ENUM jika diperlukan
        // Atau biarkan saja sebagai string
    }
};