<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('status_admin')->nullable()->after('status')
                  ->comment('Status untuk admin: null=tersedia, diambil=sudah diambil driver');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('jadwals', 'status_admin')) {
                $table->dropColumn('status_admin');
            }
        });
    }
};
