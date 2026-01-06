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
        // Add new columns only if they don't already exist
        if (!Schema::hasColumn('metode_pembayaran', 'is_paylabs')) {
            Schema::table('metode_pembayaran', function (Blueprint $table) {
                $table->boolean('is_paylabs')->default(false)->after('aktif');
            });
        }

        if (!Schema::hasColumn('metode_pembayaran', 'paylabs_channel_code')) {
            Schema::table('metode_pembayaran', function (Blueprint $table) {
                $table->string('paylabs_channel_code')->nullable()->after('is_paylabs');
            });
        }

        if (!Schema::hasColumn('metode_pembayaran', 'paylabs_channel_name')) {
            Schema::table('metode_pembayaran', function (Blueprint $table) {
                $table->string('paylabs_channel_name')->nullable()->after('paylabs_channel_code');
            });
        }

        // Apply column type/nullable changes (these expect the columns to exist)
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->decimal('biaya_admin', 12, 2)->default(0)->change();
            $table->text('instruksi')->nullable()->change();
        });

        // Insert Paylabs payment methods
        // Use insertOrIgnore to skip records with duplicate unique keys (e.g., 'kode')
        DB::table('metode_pembayaran')->insertOrIgnore([
            [
                'nama' => 'QRIS',
                'kode' => 'qris',
                'jenis' => 'qris',
                'deskripsi' => 'Pembayaran via QRIS (QR Code)',
                'biaya_admin' => 0,
                'estimasi_waktu' => 1,
                'instruksi' => json_encode([
                    '1. Buka aplikasi mobile banking atau e-wallet',
                    '2. Pilih menu Scan QR',
                    '3. Scan QR code di atas',
                    '4. Konfirmasi pembayaran'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'QRIS',
                'paylabs_channel_name' => 'QRIS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'BCA Virtual Account',
                'kode' => 'bca_va',
                'jenis' => 'virtual_account',
                'deskripsi' => 'Virtual Account BCA',
                'biaya_admin' => 4000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    '1. Login BCA Mobile/m-BCA',
                    '2. Pilih Transfer',
                    '3. Pilih BCA Virtual Account',
                    '4. Masukkan nomor VA',
                    '5. Konfirmasi dan bayar'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA_BCA',
                'paylabs_channel_name' => 'BCA Virtual Account',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mandiri Virtual Account',
                'kode' => 'mandiri_va',
                'jenis' => 'virtual_account',
                'deskripsi' => 'Virtual Account Mandiri',
                'biaya_admin' => 4000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    '1. Login Livin by Mandiri',
                    '2. Pilih Pembayaran',
                    '3. Pilih Virtual Account',
                    '4. Masukkan nomor VA',
                    '5. Konfirmasi dan bayar'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA_MANDIRI',
                'paylabs_channel_name' => 'Mandiri Virtual Account',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns only if they exist to avoid errors on rollback
        $drops = [];
        if (Schema::hasColumn('metode_pembayaran', 'is_paylabs')) {
            $drops[] = 'is_paylabs';
        }
        if (Schema::hasColumn('metode_pembayaran', 'paylabs_channel_code')) {
            $drops[] = 'paylabs_channel_code';
        }
        if (Schema::hasColumn('metode_pembayaran', 'paylabs_channel_name')) {
            $drops[] = 'paylabs_channel_name';
        }

        if (!empty($drops)) {
            Schema::table('metode_pembayaran', function (Blueprint $table) use ($drops) {
                $table->dropColumn($drops);
            });
        }
    }
};
