<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syarat_ketentuan', function (Blueprint $table) {
            $table->id();
            $table->string('sk_kode')->unique()->comment('Contoh: sk_pengguna, sk_driver, sk_pengiriman');
            $table->string('sk_judul');
            $table->longText('sk_konten_html');
            $table->string('sk_versi')->default('1.0');
            $table->date('sk_tanggal_efektif');
            $table->boolean('sk_status_aktif')->default(true);
            $table->string('sk_tipe')->comment('pengguna, driver, mitra, pengiriman');
            $table->timestamps();
            
            $table->index(['sk_tipe', 'sk_status_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_ketentuan');
    }
};