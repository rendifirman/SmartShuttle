<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            // Paylabs QRIS
            [
                'nama' => 'QRIS',
                'kode' => 'qris',
                'jenis' => 'qris',
                'deskripsi' => 'QR Code Indonesia Standard',
                'biaya_admin' => 0,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    'Scan QR Code dengan aplikasi e-wallet atau mobile banking',
                    'DANA, OVO, GoPay, ShopeePay, LinkAja, dan bank-bank lain'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'QRIS',
                'paylabs_channel_name' => 'QRIS',
                'gambar' => 'qris.png',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Paylabs Virtual Account
            [
                'nama' => 'BCA Virtual Account',
                'kode' => 'bca_va',
                'jenis' => 'virtual_account',
                'deskripsi' => 'Virtual Account Bank BCA',
                'biaya_admin' => 4000,
                'estimasi_waktu' => 15,
                'instruksi' => json_encode([
                    'ATM BCA: Pilih Transfer > BCA Virtual Account',
                    'm-BCA: Pilih m-Transfer > BCA Virtual Account',
                    'Internet Banking: Pilih Transfer > BCA Virtual Account'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA',
                'paylabs_channel_name' => 'BCA',
                'gambar' => 'bca.png',
                'urutan' => 2,
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
                'instruksi' => json_encode([
                    'ATM Mandiri: Pilih Bayar/Beli > Multi Payment',
                    'Livin by Mandiri: Pilih Pembayaran > Virtual Account',
                    'Internet Banking: Pilih Pembayaran > Virtual Account'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA',
                'paylabs_channel_name' => 'MANDIRI',
                'gambar' => 'mandiri.png',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'BNI Virtual Account',
                'kode' => 'bni_va',
                'jenis' => 'virtual_account',
                'deskripsi' => 'Virtual Account Bank BNI',
                'biaya_admin' => 4000,
                'estimasi_waktu' => 15,
                'instruksi' => json_encode([
                    'ATM BNI: Pilih Menu Lainnya > Transfer > Virtual Account',
                    'Mobile Banking: Pilih Transfer > Virtual Account',
                    'Internet Banking: Pilih Transfer > Virtual Account'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA',
                'paylabs_channel_name' => 'BNI',
                'gambar' => 'bni.png',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'BRI Virtual Account',
                'kode' => 'bri_va',
                'jenis' => 'virtual_account',
                'deskripsi' => 'Virtual Account Bank BRI',
                'biaya_admin' => 4000,
                'estimasi_waktu' => 15,
                'instruksi' => json_encode([
                    'ATM BRI: Pilih Pembayaran > Lainnya > BRIVA',
                    'Mobile Banking: Pilih Pembayaran > BRIVA',
                    'Internet Banking: Pilih Pembayaran > BRIVA'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'VA',
                'paylabs_channel_name' => 'BRI',
                'gambar' => 'bri.png',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // E-Wallets
            [
                'nama' => 'DANA',
                'kode' => 'dana',
                'jenis' => 'ewallet',
                'deskripsi' => 'Pembayaran via DANA',
                'biaya_admin' => 2000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    'Buka aplikasi DANA',
                    'Pilih "Bayar" atau "Transfer"',
                    'Scan QR Code atau masukkan kode pembayaran'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'EW',
                'paylabs_channel_name' => 'DANA',
                'gambar' => 'dana.png',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'GoPay',
                'kode' => 'gopay',
                'jenis' => 'ewallet',
                'deskripsi' => 'Pembayaran via GoPay',
                'biaya_admin' => 2000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    'Buka aplikasi Gojek',
                    'Pilih "GoPay"',
                    'Scan QR Code atau masukkan kode pembayaran'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'EW',
                'paylabs_channel_name' => 'GOPAY',
                'gambar' => 'gopay.png',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'OVO',
                'kode' => 'ovo',
                'jenis' => 'ewallet',
                'deskripsi' => 'Pembayaran via OVO',
                'biaya_admin' => 2000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    'Buka aplikasi OVO',
                    'Pilih "Bayar"',
                    'Scan QR Code atau masukkan kode pembayaran'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'EW',
                'paylabs_channel_name' => 'OVO',
                'gambar' => 'ovo.png',
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'ShopeePay',
                'kode' => 'shopeepay',
                'jenis' => 'ewallet',
                'deskripsi' => 'Pembayaran via ShopeePay',
                'biaya_admin' => 2000,
                'estimasi_waktu' => 5,
                'instruksi' => json_encode([
                    'Buka aplikasi Shopee',
                    'Pilih "ShopeePay"',
                    'Scan QR Code atau masukkan kode pembayaran'
                ]),
                'aktif' => true,
                'is_paylabs' => true,
                'paylabs_channel_code' => 'EW',
                'paylabs_channel_name' => 'SHOPEEPAY',
                'gambar' => 'shopeepay.png',
                'urutan' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($methods as $method) {
            DB::table('metode_pembayaran')->updateOrInsert(
                ['kode' => $method['kode']],
                $method
            );
        }
    }
}
