<?php
// database/migrations/2025_01_15_000000_add_qris_fields_to_pembayaran_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Paylabs v2.3 QRIS fields
            $table->string('paylabs_store_id')->nullable()->after('paylabs_merchant_id');
            $table->string('paylabs_request_id')->nullable()->after('paylabs_store_id');
            $table->string('qris_url')->nullable()->after('qr_code');
            $table->string('nmid')->nullable()->after('qris_url');
            $table->string('platform_trade_no')->nullable()->after('nmid');
            $table->string('tid')->nullable()->after('platform_trade_no');
            $table->string('rrn')->nullable()->after('tid');
            $table->string('payer_name')->nullable()->after('rrn');
            $table->string('payer_phone')->nullable()->after('payer_name');
            $table->string('issuer_id')->nullable()->after('payer_phone');
            $table->decimal('trans_fee_rate', 12, 6)->nullable()->after('issuer_id');
            $table->decimal('trans_fee_amount', 12, 2)->nullable()->after('trans_fee_rate');
            $table->decimal('total_trans_fee', 12, 2)->nullable()->after('trans_fee_amount');
            $table->decimal('vat_fee', 12, 2)->nullable()->after('total_trans_fee');
            $table->string('account_no')->nullable()->after('vat_fee');
            $table->string('create_time')->nullable()->after('account_no');
            $table->string('success_time')->nullable()->after('create_time');
            $table->string('expired_time')->nullable()->after('success_time');
            $table->string('paylabs_raw_response')->nullable()->after('paylabs_response');
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->string('fee_type')->default('BEN')->after('paylabs_channel_name');
            $table->json('product_info')->nullable()->after('instruksi');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $columns = [
                'paylabs_store_id',
                'paylabs_request_id',
                'qris_url',
                'nmid',
                'platform_trade_no',
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
                'success_time',
                'expired_time',
                'paylabs_raw_response'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('metode_pembayaran', 'fee_type')) {
                $table->dropColumn('fee_type');
            }
            if (Schema::hasColumn('metode_pembayaran', 'product_info')) {
                $table->dropColumn('product_info');
            }
        });
    }
};
