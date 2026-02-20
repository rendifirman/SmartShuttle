<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            // Tambah kolom untuk kondisi promo
            $table->enum('kategori_promo', ['keluarga', 'membership', 'umum'])->default('umum')->after('tipe_promo');
            $table->integer('min_tiket')->nullable()->after('minimal_pembelian');
            $table->boolean('khusus_member')->default(false)->after('min_tiket');
            $table->text('pesan_error')->nullable()->after('deskripsi');
            
            // Index untuk performa query
            $table->index(['kategori_promo', 'status']);
            $table->index(['khusus_member', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            // Drop indexes if they exist
            if (Schema::hasTable('promo')) {
                DB::statement('DROP INDEX IF EXISTS promo_kategori_promo_status_index');
                DB::statement('DROP INDEX IF EXISTS promo_khusus_member_status_index');
            }
            $table->dropColumn(['kategori_promo', 'min_tiket', 'khusus_member', 'pesan_error']);
        });
    }
};