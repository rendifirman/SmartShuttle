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
        Schema::table('membership_payments', function (Blueprint $table) {
            // Paylabs request/response fields
            $table->string('paylabs_request_id')->nullable()->after('paid_at');
            $table->string('paylabs_transaction_id')->nullable()->after('paylabs_request_id');
            $table->json('paylabs_response')->nullable()->after('paylabs_transaction_id');
            $table->text('paylabs_raw_response')->nullable()->after('paylabs_response');

            // QRIS fields
            $table->text('qr_code')->nullable()->after('paylabs_raw_response');
            $table->text('qris_url')->nullable()->after('qr_code');

            // VA fields
            $table->string('no_virtual_account')->nullable()->after('qris_url');
            $table->string('platform_trade_no')->nullable()->after('no_virtual_account');

            // Transaction metadata
            $table->string('nmid')->nullable()->after('platform_trade_no');
            $table->string('tid')->nullable()->after('nmid');
            $table->string('rrn')->nullable()->after('tid');

            // Payer information
            $table->string('payer_name')->nullable()->after('rrn');
            $table->string('payer_phone')->nullable()->after('payer_name');
            $table->string('issuer_id')->nullable()->after('payer_phone');

            // Fee information
            $table->decimal('trans_fee_rate', 10, 4)->nullable()->after('issuer_id');
            $table->decimal('trans_fee_amount', 15, 2)->nullable()->after('trans_fee_rate');
            $table->decimal('total_trans_fee', 15, 2)->nullable()->after('trans_fee_amount');
            $table->decimal('vat_fee', 15, 2)->nullable()->after('total_trans_fee');

            // Additional fields
            $table->string('account_no')->nullable()->after('vat_fee');
            $table->timestamp('create_time')->nullable()->after('account_no');
            $table->timestamp('expired_time')->nullable()->after('create_time');
            $table->timestamp('success_time')->nullable()->after('expired_time');
            $table->text('checkout_url')->nullable()->after('success_time');
            $table->text('deeplink')->nullable()->after('checkout_url');
            $table->string('fee_type')->nullable()->after('deeplink');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_payments', function (Blueprint $table) {
            $table->dropColumn([
                'paylabs_request_id',
                'paylabs_transaction_id',
                'paylabs_response',
                'paylabs_raw_response',
                'qr_code',
                'qris_url',
                'no_virtual_account',
                'platform_trade_no',
                'nmid',
                'tid',
                'rrn',
                'payer_name',
                'payer_phone',
                'issuer_id',
                'trans_fee_rate',
                'trans_fee_amount',
                'total_trans_fee',
                'vat_fee',
                'account_no',
                'create_time',
                'expired_time',
                'success_time',
                'checkout_url',
                'deeplink',
                'fee_type'
            ]);
        });
    }
};
