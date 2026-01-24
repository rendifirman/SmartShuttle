<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rute_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rutes')->onDelete('cascade');
            $table->integer('urutan_segment');
            
            // Lokasi segment
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->onDelete('set null');
            $table->string('kota');
            $table->string('nama_lokasi');
            
            // Data jarak
            $table->decimal('jarak_segment', 10, 2)->default(0);
            $table->decimal('jarak_kumulatif', 10, 2)->default(0);
            $table->integer('estimasi_waktu')->nullable(); // dalam menit
            
            // Data harga untuk segment ini
            $table->decimal('harga_segment', 15, 2)->nullable();
            
            // Status
            $table->boolean('is_pickup_point')->default(false);
            $table->boolean('is_drop_point')->default(false);
            $table->boolean('status_aktif')->default(true);
            
            $table->timestamps();
            
            // Index untuk performa
            $table->unique(['rute_id', 'urutan_segment']);
            $table->index(['rute_id', 'outlet_id']);
            $table->index('kota');
            $table->index('status_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rute_segments');
    }
};