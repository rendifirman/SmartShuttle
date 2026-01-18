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
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            $table->string('link_bantuan')->nullable();
            $table->string('link_faq')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            $table->dropColumn(['link_bantuan', 'link_faq']);
        });
    }
};
