php
// Test untuk verifikasi integrasi perjalanan jadwal
$trips = \App\Models\DriverJadwal::with(['jadwal', 'jadwal.rutes'])
    ->where('tanggal', '>=', \Carbon\Carbon::today())
    ->limit(1)
    ->get();

echo "Total trips: " . $trips->count() . "\n\n";

if ($trips->count() > 0) {
    $trip = $trips->first();
    echo "Trip ID: {$trip->id_jadwal_driver}\n";

    $jadwal = $trip->jadwal;
    echo "Jadwal ditemukan: " . ($jadwal ? 'YES' : 'NO') . "\n";

    if ($jadwal) {
        $rutes = $jadwal->rutes;
        echo "Rutes count: {$rutes->count()}\n\n";

        foreach ($rutes as $rute) {
            echo "Rute: {$rute->nama_rute}\n";
            $pemberhentian = $rute->rute_pemberhentian;
            echo "Pemberhentian type: " . gettype($pemberhentian) . "\n";

            if (is_array($pemberhentian)) {
                echo "Pemberhentian count: " . count($pemberhentian) . "\n";

                foreach ($pemberhentian as $idx => $stop) {
                    $kota = $stop['kota'] ?? 'N/A';
                    echo "  Stop $idx: $kota\n";
                }
            }
        }
    }
}

// Test branch dan outlets
echo "\n\nTest Branch & Outlets:\n";
$branches = \App\Models\Branch::with('outlets')->limit(2)->get();
echo "Branches count: {$branches->count()}\n";

foreach ($branches as $branch) {
    echo "Branch: {$branch->nama_cabang} (City: {$branch->kota})\n";
    echo "Outlets: {$branch->outlets()->count()}\n";
}
