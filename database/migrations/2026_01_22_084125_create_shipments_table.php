<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_resi')->unique();
            
            // Relasi ke rute dan segment
            $table->foreignId('rute_id')->nullable()->constrained('rutes')->onDelete('set null');
            $table->foreignId('segment_asal_id')->nullable()->constrained('rute_segments')->onDelete('set null');
            $table->foreignId('segment_tujuan_id')->nullable()->constrained('rute_segments')->onDelete('set null');
            $table->foreignId('outlet_asal_id')->nullable()->constrained('outlets')->onDelete('set null');
            $table->foreignId('outlet_tujuan_id')->nullable()->constrained('outlets')->onDelete('set null');
            
            // Data lokasi
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            
            // Data fisik paket
            $table->decimal('berat', 10, 2); // dalam kg
            $table->decimal('jarak', 10, 2); // dalam km
            $table->decimal('panjang', 10, 2)->nullable();
            $table->decimal('lebar', 10, 2)->nullable();
            $table->decimal('tinggi', 10, 2)->nullable();
            
            // Komponen harga
            $table->decimal('harga_berat', 15, 2)->default(0);
            $table->decimal('harga_jarak', 15, 2)->default(0);
            $table->decimal('harga_tambahan', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('harga_total', 15, 2);
            
            // Data pengirim & penerima
            $table->string('nama_pengirim');
            $table->string('telepon_pengirim');
            $table->string('email_pengirim')->nullable();
            $table->string('nama_penerima');
            $table->string('telepon_penerima');
            $table->string('email_penerima')->nullable();
            $table->text('alamat_tujuan')->nullable();
            
            // Status
            $table->enum('status', [
                'draft',
                'menunggu_konfirmasi',
                'diproses',
                'diterima_outlet_asal',
                'dalam_perjalanan',
                'sampai_outlet_tujuan',
                'siap_diambil',
                'terkirim',
                'dibatalkan'
            ])->default('draft');
            
            // Timeline
            $table->timestamp('waktu_diterima_outlet_asal')->nullable();
            $table->timestamp('waktu_dalam_perjalanan')->nullable();
            $table->timestamp('waktu_sampai_outlet_tujuan')->nullable();
            $table->timestamp('waktu_siap_diambil')->nullable();
            $table->timestamp('waktu_terkirim')->nullable();
            
            // Data tambahan
            $table->text('catatan')->nullable();
            $table->string('foto_paket')->nullable();
            
            // Relasi user
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Data perhitungan
            $table->decimal('berat_aktual', 10, 2)->default(0);
            $table->decimal('jarak_tempuh', 10, 2)->default(0);
            
            // Timestamps
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_dikirim')->nullable();
            $table->timestamp('tanggal_diterima')->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index('kode_resi');
            $table->index('status');
            $table->index(['user_id', 'created_at']);
            $table->index('outlet_asal_id');
            $table->index('outlet_tujuan_id');
            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};