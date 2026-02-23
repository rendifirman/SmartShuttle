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
        Schema::table('smartrent_transactions', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->after('payment_proof_path');
            $table->string('qr_path')->nullable()->after('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smartrent_transactions', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'qr_path']);
        });
    }
};