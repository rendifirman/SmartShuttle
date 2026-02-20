<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Paylabs specific fields
            $table->string('paylabs_transaction_id')->nullable()->after('qr_code');
            $table->string('paylabs_merchant_id')->nullable()->after('paylabs_transaction_id');
            $table->string('paylabs_payment_code')->nullable()->after('paylabs_merchant_id');
            $table->text('paylabs_response')->nullable()->after('paylabs_payment_code');
            $table->string('paylabs_status')->nullable()->after('paylabs_response');

            // QRIS specific
            $table->string('qris_raw_data')->nullable()->after('qr_code');
            $table->string('qris_nmid')->nullable()->after('qris_raw_data');
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->boolean('is_paylabs')->default(false)->after('aktif');
            $table->string('paylabs_channel_code')->nullable()->after('is_paylabs');
            $table->string('paylabs_channel_name')->nullable()->after('paylabs_channel_code');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $columns = [
                'paylabs_transaction_id',
                'paylabs_merchant_id',
                'paylabs_payment_code',
                'paylabs_response',
                'paylabs_status',
                'qris_raw_data',
                'qris_nmid'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $columns = ['is_paylabs', 'paylabs_channel_code', 'paylabs_channel_name'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('metode_pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
