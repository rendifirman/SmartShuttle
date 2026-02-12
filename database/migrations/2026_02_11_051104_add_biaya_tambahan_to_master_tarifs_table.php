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
        Schema::table('master_tarif', function (Blueprint $table) {
            $table->decimal('biaya_tambahan', 15, 2)->default(0)->after('harga_maksimum');
            $table->text('keterangan_biaya_tambahan')->nullable()->after('biaya_tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_tarifs', function (Blueprint $table) {
            $table->dropColumn(['biaya_tambahan', 'keterangan_biaya_tambahan']);
        });
    }
};
