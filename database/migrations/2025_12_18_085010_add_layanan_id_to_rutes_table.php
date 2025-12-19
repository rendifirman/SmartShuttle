<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            $table->foreignId('layanan_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('m_layanan', 'id_layanan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            $table->dropForeign(['layanan_id']);
            $table->dropColumn('layanan_id');
        });
    }
};
