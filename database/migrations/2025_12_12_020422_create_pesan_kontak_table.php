<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_kontak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengirim');
            $table->string('email_pengirim');
            $table->string('nomor_telepon')->nullable();
            $table->text('pesan');
            $table->enum('status', ['terkirim', 'dibaca', 'dibalas'])->default('terkirim');
            
            // ==== TAMBAHKAN FIELD BARU INI ====
            $table->string('kategori')->nullable()->default('umum');
            $table->string('subjek')->nullable();
            $table->string('dibaca_oleh')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->text('balasan')->nullable();
            $table->timestamp('dibalas_pada')->nullable();
            $table->string('dibalas_oleh')->nullable();
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            // ==== END TAMBAHAN ====
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_kontak');
    }
};