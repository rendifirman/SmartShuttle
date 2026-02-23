<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_harga', function (Blueprint $table) {
            $table->id();
            $table->string('kode_harga')->unique();
            $table->string('nama_harga');
            $table->enum('jenis_harga', ['jarak', 'berat', 'dimensi', 'administrasi']);
            
            // Konfigurasi harga
            $table->decimal('harga_dasar', 15, 2)->default(0);
            $table->decimal('harga_per_satuan', 15, 2)->nullable();
            $table->string('satuan')->nullable(); // km, kg, cm, etc
            $table->decimal('minimal_nilai', 10, 2)->nullable();
            $table->decimal('maksimal_nilai', 10, 2)->nullable();
            
            // Aturan khusus untuk berat (5kg pertama = 7000, berikutnya 2000/kg)
            $table->decimal('berat_pertama', 10, 2)->nullable()->default(5);
            $table->decimal('harga_berat_pertama', 15, 2)->nullable()->default(7000);
            $table->decimal('harga_berat_berikutnya', 15, 2)->nullable()->default(2000);
            
            // Aturan khusus untuk jarak (per 10km = 2000)
            $table->decimal('kelipatan_jarak', 10, 2)->nullable()->default(10);
            $table->decimal('harga_per_kelipatan', 15, 2)->nullable()->default(2000);
            
            // Status dan validitas
            $table->date('tanggal_berlaku')->default(now());
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->boolean('status_aktif')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['jenis_harga', 'status_aktif']);
            $table->index('kode_harga');
        });
        
    
    }

    public function down(): void
    {
        Schema::dropIfExists('master_harga');
    }
};