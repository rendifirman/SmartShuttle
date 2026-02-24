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
        // Change payment_status column from ENUM to STRING(50)
        Schema::table('smartrent_transactions', function (Blueprint $table) {
            // Drop the enum column and recreate as string
            // For MySQL, we need to use raw SQL or drop/recreate
            DB::statement("ALTER TABLE smartrent_transactions MODIFY COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'unpaid' AFTER paid_at");
        });
        
        // Standardize all existing payment status values
        $this->normalizePaymentStatuses();
    }

    /**
     * Normalize existing payment status values
     */
    private function normalizePaymentStatuses()
    {
        // Map old values to standardized values
        $normalizations = [
            // Paid statuses: settlement, success, completed, lunas → paid
            ['old' => 'settlement', 'new' => 'paid'],
            ['old' => 'success', 'new' => 'paid'],
            ['old' => 'completed', 'new' => 'paid'],
            ['old' => 'lunas', 'new' => 'paid'],
            
            // Pending statuses: pending, waiting, unpaid, menunggu, process → pending
            ['old' => 'waiting', 'new' => 'pending'],
            ['old' => 'menunggu', 'new' => 'pending'],
            ['old' => 'process', 'new' => 'pending'],
            
            // Failed/Cancelled statuses: expired, failed, cancelled, batal, rejected → failed
            ['old' => 'expired', 'new' => 'failed'],
            ['old' => 'cancelled', 'new' => 'failed'],
            ['old' => 'batal', 'new' => 'failed'],
            ['old' => 'rejected', 'new' => 'failed'],
        ];
        
        foreach ($normalizations as $map) {
            DB::table('smartrent_transactions')
                ->where('payment_status', $map['old'])
                ->update(['payment_status' => $map['new']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to enum if needed - comment out if you want to keep string type
        // Schema::table('smartrent_transactions', function (Blueprint $table) {
        //     DB::statement("ALTER TABLE smartrent_transactions MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'failed', 'cancelled') NOT NULL DEFAULT 'unpaid' AFTER paid_at");
        // });
    }
};
