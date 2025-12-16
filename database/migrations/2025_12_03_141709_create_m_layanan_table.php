<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('kode_layanan', 50)->unique();
            $table->string('nama_layanan', 100);
            $table->string('slug', 100)->unique();
            $table->string('deskripsi_singkat', 255);
            $table->text('deskripsi_panjang')->nullable();
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->enum('kategori_layanan', ['transport', 'logistics', 'rental']);
            $table->boolean('status_aktif')->default(true);
            $table->integer('urutan_tampilan')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            
            $table->index('kategori_layanan');
            $table->index('status_aktif');
            $table->index('urutan_tampilan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_layanan');
    }
};