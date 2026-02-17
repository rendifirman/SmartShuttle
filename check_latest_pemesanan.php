<?php
// Autoload untuk test DB tanpa Artisan
$basePath = __DIR__;
if (!file_exists($basePath . '/vendor/autoload.php')) {
    die('Autoload not found');
}

require $basePath . '/vendor/autoload.php';
$app = require $basePath . '/bootstrap/app.php';

use App\Models\Pemesanan;

$pemesanan = Pemesanan::latest()->with('detailPenumpang')->first();

echo json_encode([
    'success' => true,
    'data' => [
        'id' => $pemesanan->id,
        'kode_booking' => $pemesanan->kode_booking,
        'status' => $pemesanan->status,
        'jumlah_penumpang' => $pemesanan->jumlah_penumpang,
        'id_jadwal_driver' => $pemesanan->id_jadwal_driver,
        'jadwal_id' => $pemesanan->jadwal_id,
        'detail_penumpang_count' => $pemesanan->detailPenumpang->count(),
        'detail_penumpang' => $pemesanan->detailPenumpang->map(function($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nomor_kursi' => $p->nomor_kursi,
            ];
        }),
        'status_menunggu_kursi' => $pemesanan->status === 'menunggu_kursi',
    ]
], JSON_PRETTY_PRINT);
?>
