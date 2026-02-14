#!/usr/bin/env php
<?php
require_once __DIR__ . '/bootstrap/app.php';

$app = app();

// Cek data pemesanan terbaru
$pemesanan = \App\Models\Pemesanan::with('detailPenumpang')->latest()->first();
if ($pemesanan) {
    echo "=== PEMESANAN TERBARU ===\n";
    echo "ID: {$pemesanan->id}\n";
    echo "Kode Booking: {$pemesanan->kode_booking}\n";
    echo "Status: {$pemesanan->status}\n";
    echo "Jumlah Penumpang: {$pemesanan->jumlah_penumpang}\n";
    echo "Customer ID: {$pemesanan->customer_id}\n";
    echo "Jadwal ID: {$pemesanan->jadwal_id}\n";
    echo "Driver Jadwal ID: {$pemesanan->id_jadwal_driver}\n";

    echo "\n=== DETAIL PENUMPANG ===\n";
    $details = $pemesanan->detailPenumpang;
    echo "Jumlah: " . $details->count() . "\n";
    foreach ($details as $detail) {
        echo "  - {$detail->nama} (ID: {$detail->id}), Kursi: " . ($detail->nomor_kursi ?? 'belum dipilih') . "\n";
    }

    echo "\n=== VALIDASI STATUS ===\n";
    if ($pemesanan->status === 'menunggu_kursi') {
        echo "✓ Status benar untuk pilih kursi\n";
    } else {
        echo "✗ Status SALAH: {$pemesanan->status} (harus menunggu_kursi)\n";
    }
} else {
    echo "Tidak ada pemesanan\n";
}
?>
