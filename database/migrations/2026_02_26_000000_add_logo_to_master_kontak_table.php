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
        Schema::table('m_master_kontak', function (Blueprint $table) {
            // Add logo field after alamat_kantor_pusat
            $table->string('logo')->nullable()->after('alamat_kantor_pusat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_master_kontak', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
