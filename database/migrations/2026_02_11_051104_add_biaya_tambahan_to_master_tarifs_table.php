<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table('master_tarifs', function (Blueprint $table) {
        //     $table->decimal('biaya_tambahan', 10, 2)->nullable()->after('harga');
        //     $table->string('keterangan_biaya_tambahan')->nullable()->after('biaya_tambahan');
        // });
    }

    public function down(): void
    {
        // Schema::table('master_tarifs', function (Blueprint $table) {
        //     $table->dropColumn(['biaya_tambahan', 'keterangan_biaya_tambahan']);
        // });
    }
};