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
        Schema::table('rutes', function (Blueprint $table) {
            // Add foreign key columns for cabang (branch)
            $table->unsignedBigInteger('cabang_asal_id')->nullable()->after('layanan_id');
            $table->unsignedBigInteger('cabang_tujuan_id')->nullable()->after('cabang_asal_id');

            // Add foreign key constraints
            $table->foreign('cabang_asal_id')
                ->references('id')
                ->on('branches')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('cabang_tujuan_id')
                ->references('id')
                ->on('branches')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            if (Schema::hasColumn('rutes', 'cabang_asal_id')) {
                // Drop foreign key constraints first
                $table->dropForeign(['cabang_asal_id']);
            }
            if (Schema::hasColumn('rutes', 'cabang_tujuan_id')) {
                $table->dropForeign(['cabang_tujuan_id']);
            }

            // Drop columns
            if (Schema::hasColumn('rutes', 'cabang_asal_id') || Schema::hasColumn('rutes', 'cabang_tujuan_id')) {
                $table->dropColumn(['cabang_asal_id', 'cabang_tujuan_id']);
            }
        });
    }
};
