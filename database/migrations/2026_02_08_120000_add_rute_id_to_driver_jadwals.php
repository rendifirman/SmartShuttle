<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_jadwals', function (Blueprint $table) {
            if (!Schema::hasColumn('driver_jadwals', 'rute_id')) {
                $table->foreignId('rute_id')->nullable()->after('id_jadwal')->constrained('rutes')->onDelete('set null');
                $table->index('rute_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('driver_jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('driver_jadwals', 'rute_id')) {
                $table->dropForeign(['rute_id']);
                $table->dropIndex(['rute_id']);
                $table->dropColumn('rute_id');
            }
        });
    }
};
