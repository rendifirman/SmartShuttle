<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebijakan_privasi', function (Blueprint $table) {
            $table->id();
            $table->string('kp_kode')->unique()->comment('Contoh: kp_pengguna, kp_driver');
            $table->string('kp_judul');
            $table->longText('kp_konten_html');
            $table->string('kp_versi')->default('1.0');
            $table->date('kp_tanggal_efektif');
            $table->boolean('kp_status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebijakan_privasi');
    }
};