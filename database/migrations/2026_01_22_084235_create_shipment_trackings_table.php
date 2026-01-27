<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            
            // Lokasi tracking
            $table->foreignId('rute_segment_id')->nullable()->constrained('rute_segments')->onDelete('set null');
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->onDelete('set null');
            
            // Status dan deskripsi
            $table->enum('status', [
                'paket_diterima',
                'paket_diproses',
                'paket_dalam_perjalanan',
                'paket_sampai_outlet',
                'paket_siap_diambil',
                'paket_diambil_kurir',
                'paket_diantar',
                'paket_terkirim',
                'paket_batal'
            ]);
            
            $table->text('deskripsi')->nullable();
            $table->text('catatan')->nullable();
            
            // Foto/lampiran (optional)
            $table->string('foto_bukti')->nullable();
            
            // User yang melakukan update
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('updated_by_role')->nullable();
            
            $table->timestamp('waktu_status')->useCurrent();
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['shipment_id', 'waktu_status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_trackings');
    }
};