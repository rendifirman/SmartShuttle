<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('nik');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->enum('membership_status', ['non_member', 'pending', 'active', 'expired'])->default('non_member')->after('membership_level');
            $table->date('membership_start_date')->nullable()->after('membership_status');
            $table->date('membership_end_date')->nullable()->after('membership_start_date');
            $table->decimal('membership_fee', 15, 2)->nullable()->after('membership_end_date');
            $table->string('membership_payment_method')->nullable()->after('membership_fee');
            $table->string('membership_payment_status')->nullable()->after('membership_payment_method');
            $table->string('membership_transaction_id')->nullable()->after('membership_payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_lahir',
                'jenis_kelamin',
                'membership_status',
                'membership_start_date',
                'membership_end_date',
                'membership_fee',
                'membership_payment_method',
                'membership_payment_status',
                'membership_transaction_id'
            ]);
        });
    }
};
