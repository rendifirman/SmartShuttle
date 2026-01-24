<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rute_segments', function (Blueprint $table) {
            if (!Schema::hasColumn('rute_segments', 'jarak_segment')) {
                $table->decimal('jarak_segment', 10, 2)->default(0)->after('nama_lokasi');
            }
            if (!Schema::hasColumn('rute_segments', 'jarak_kumulatif')) {
                $table->decimal('jarak_kumulatif', 10, 2)->default(0)->after('jarak_segment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rute_segments', function (Blueprint $table) {
            $table->dropColumn(['jarak_segment', 'jarak_kumulatif']);
        });
    }
};