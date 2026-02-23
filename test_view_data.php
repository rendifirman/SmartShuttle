<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;
use App\Models\User;

echo "=== SIMULATING SEARCH REQUEST ===\n\n";

// Simulate the exact request parameters from the logs
$asal = 'Jakarta';
$tujuan = 'Bekasi';
$tanggal = '2026-02-08';
$penumpang = 1;

echo "Request Parameters:\n";
echo "  asal: $asal\n";
echo "  tujuan: $tujuan\n";
echo "  tanggal: $tanggal\n";
echo "  penumpang: $penumpang\n\n";

// Validate exactly as controller does
$validated = [
    'asal' => $asal,
    'tujuan' => $tujuan,
    'tanggal' => $tanggal,
    'penumpang' => $penumpang,
];

echo "Validated array:\n";
echo json_encode($validated, JSON_PRETTY_PRINT) . "\n\n";

// Build query exactly as controller does
echo "Building query...\n";
$query = DriverJadwal::with(['driver', 'jadwal'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);

// Add LIKE filters
$query->where(function($q) use ($asal, $tujuan) {
    $q->where('rute', 'LIKE', "%{$asal}%{$tujuan}%")
      ->orWhere('rute', 'LIKE', "%{$asal} %{$tujuan}%")
      ->orWhere('rute', 'LIKE', "%{$asal}→%{$tujuan}%")
      ->orWhere('rute', 'LIKE', "%{$asal}->%{$tujuan}%");
});

// Apply date filter
$query->whereDate('tanggal', $tanggal);

echo "SQL: " . $query->toSql() . "\n\n";

// Paginate as controller does
$jadwals = $query->orderBy('tanggal', 'asc')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->paginate(10);

echo "Query Results:\n";
echo "  Total count: " . $jadwals->count() . "\n";
echo "  Total (full): " . $jadwals->total() . "\n";
echo "  Is empty: " . ($jadwals->isEmpty() ? 'TRUE' : 'FALSE') . "\n";
echo "  Isset check: " . (isset($jadwals) ? 'TRUE' : 'FALSE') . "\n\n";

if ($jadwals->count() > 0) {
    echo "First Record Details:\n";
    $first = $jadwals->first();
    echo "  ID: " . $first->id_jadwal_driver . "\n";
    echo "  Rute: " . $first->rute . "\n";
    echo "  Rute String (accessor): " . $first->rute_string . "\n";
    echo "  Tanggal: " . $first->tanggal . "\n";
    echo "  Status: " . $first->status . "\n";
    echo "  Waktu Keberangkatan: " . $first->waktu_keberangkatan . "\n";
    echo "  Waktu Kedatangan: " . $first->waktu_kedatangan . "\n";
    echo "  Total Kursi: " . $first->total_kursi . "\n";
    echo "  Kursi Terisi: " . $first->kursi_terisi . "\n";
    echo "  Kursi Tersedia (accessor): " . $first->kursi_tersedia . "\n";
    echo "  Shuttle (virtual object):\n";
    $shuttle = $first->shuttle;
    echo "    - nama_shuttle: " . $shuttle->nama_shuttle . "\n";
    echo "    - tipe_shuttle: " . $shuttle->tipe_shuttle . "\n";
    echo "    - kapasitas_kursi: " . $shuttle->kapasitas_kursi . "\n";
    echo "\n";
}

// Now simulate what will be passed to the view
echo "Data passed to view (as array_merge would do):\n";
$viewData = array_merge(
    $validated,
    [
        'user' => User::first(),
        'jadwals' => $jadwals,
        'kotaAsalList' => [],
        'kotaTujuanList' => [],
        'outletsGrouped' => [],
        'penumpang' => $penumpang
    ]
);

echo "  isset(\$validated): " . (isset($viewData['asal']) && isset($viewData['tujuan']) ? 'TRUE' : 'FALSE') . "\n";
echo "  isset(\$jadwals): " . (isset($viewData['jadwals']) ? 'TRUE' : 'FALSE') . "\n";
echo "  \$jadwals->isEmpty(): " . ($viewData['jadwals']->isEmpty() ? 'TRUE' : 'FALSE') . "\n";
echo "  \$jadwals->count(): " . $viewData['jadwals']->count() . "\n";

echo "\n=== SIMULATION COMPLETE ===\n";
