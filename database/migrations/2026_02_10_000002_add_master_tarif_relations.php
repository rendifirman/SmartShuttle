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
        // Add master_tarif_id to rutes table
        if (!Schema::hasColumn('rutes', 'master_tarif_id')) {
            Schema::table('rutes', function (Blueprint $table) {
                $table->foreignId('master_tarif_id')
                    ->nullable()
                    ->constrained('master_tarif')
                    ->onDelete('set null')
                    ->after('master_harga_id');
            });
        }

        // Add master_tarif_id to driver_jadwals table
        if (!Schema::hasColumn('driver_jadwals', 'master_tarif_id')) {
            Schema::table('driver_jadwals', function (Blueprint $table) {
                $table->foreignId('master_tarif_id')
                    ->nullable()
                    ->constrained('master_tarif')
                    ->onDelete('set null')
                    ->after('harga');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            if (Schema::hasColumn('rutes', 'master_tarif_id')) {
                $table->dropForeign(['master_tarif_id']);
                $table->dropColumn('master_tarif_id');
            }
        });

        Schema::table('driver_jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('driver_jadwals', 'master_tarif_id')) {
                $table->dropForeign(['master_tarif_id']);
                $table->dropColumn('master_tarif_id');
            }
        });
    }
};
