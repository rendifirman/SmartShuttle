<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kontak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->string('email_utama')->nullable();
            $table->string('email_dukungan')->nullable();
            $table->string('telepon_utama')->nullable();
            $table->string('telepon_dukungan')->nullable();
            $table->text('alamat_kantor_pusat')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->text('jam_operasional')->nullable();
            $table->text('link_kebijakan_privasi')->nullable();
            $table->text('link_syarat_ketentuan')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kontak');
    }
};