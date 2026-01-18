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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Change fields that can exceed 255 characters to text
            $table->text('qr_code')->nullable()->change();
            $table->text('paylabs_raw_response')->nullable()->change();
            $table->text('paylabs_response')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Revert fields back to string(255)
            $table->string('qr_code', 255)->nullable()->change();
            $table->string('paylabs_raw_response', 255)->nullable()->change();
            $table->string('paylabs_response', 255)->nullable()->change();
        });
    }
};
