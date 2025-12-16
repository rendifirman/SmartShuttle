<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('metode_pembayaran')) {
            Schema::create('metode_pembayaran', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('kode')->unique();
                $table->enum('jenis', ['qris', 'virtual_account', 'ewallet', 'transfer_bank', 'lainnya']);
                $table->text('deskripsi')->nullable();
                $table->decimal('biaya_admin', 10, 2)->default(0);
                $table->integer('estimasi_waktu')->default(60); // dalam menit
                $table->json('instruksi')->nullable();
                $table->boolean('aktif')->default(true);
                $table->string('nomor_rekening')->nullable();
                $table->string('nama_rekening')->nullable();
                $table->string('gambar')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });

            // Insert data default
            DB::table('metode_pembayaran')->insert([
                [
                    'nama' => 'QRIS',
                    'kode' => 'qris',
                    'jenis' => 'qris',
                    'deskripsi' => 'Pembayaran via QR Code Indonesia Standard',
                    'biaya_admin' => 0,
                    'estimasi_waktu' => 5,
                    'instruksi' => json_encode(['Scan QR Code dengan aplikasi e-wallet atau mobile banking']),
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama' => 'BCA Virtual Account',
                    'kode' => 'bca_va',
                    'jenis' => 'virtual_account',
                    'deskripsi' => 'Virtual Account Bank BCA',
                    'biaya_admin' => 4000,
                    'estimasi_waktu' => 15,
                    'instruksi' => json_encode(['Transfer ke Virtual Account BCA', 'Menggunakan ATM, Mobile Banking, atau Internet Banking BCA']),
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama' => 'Mandiri Virtual Account',
                    'kode' => 'mandiri_va',
                    'jenis' => 'virtual_account',
                    'deskripsi' => 'Virtual Account Bank Mandiri',
                    'biaya_admin' => 4000,
                    'estimasi_waktu' => 15,
                    'instruksi' => json_encode(['Transfer ke Virtual Account Mandiri', 'Menggunakan ATM, Livin by Mandiri, atau Internet Banking']),
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};
