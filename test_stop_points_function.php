<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TEST FUNCTION: getStopPointsFromSchedule() ===\n\n";

function getStopPointsFromSchedule($trip)
{
    $stopPoints = [];

    try {
        $jadwal = $trip->jadwal ?? null;

        if (!$jadwal) {
            echo "  -> Jadwal tidak ditemukan\n";
            return $stopPoints;
        }

        $rutes = $jadwal->rutes ?? \collect([]);

        if ($rutes->isEmpty()) {
            echo "  -> Rutes kosong\n";
            return $stopPoints;
        }

        echo "  -> Processing " . $rutes->count() . " rutes...\n";

        foreach ($rutes as $rute) {
            echo "     Rute: {$rute->nama_rute}\n";

            $pemberhentian = $rute->rute_pemberhentian ?? [];

            if (!is_array($pemberhentian)) {
                $pemberhentian = json_decode($pemberhentian, true) ?? [];
            }

            echo "     Pemberhentian count: " . count($pemberhentian) . "\n";

            foreach ($pemberhentian as $stopIndex => $stop) {
                if (!is_array($stop)) {
                    continue;
                }

                $kota = $stop['kota'] ?? '';
                $outlets = $stop['outlets'] ?? [];
                $durasiSinggah = $stop['durasi_singgah'] ?? 10;

                echo "       Stop " . ($stopIndex + 1) . ": $kota (outlets: " . implode(", ", $outlets) . ")\n";

                $branch = \App\Models\Branch::where('kota', $kota)->first();

                if (!$branch) {
                    echo "         ERROR: Branch not found for kota '$kota'\n";
                    continue;
                }

                echo "         Found branch: {$branch->nama_cabang}\n";

                $branchOutlets = \App\Models\Outlet::where('branch_id', $branch->id)
                    ->where('status', 'aktif')
                    ->get();

                echo "         Branch outlets: " . $branchOutlets->count() . "\n";

                $outletDetails = [];
                foreach ($branchOutlets as $outlet) {
                    if (in_array($outlet->nama_outlet, $outlets)) {
                        echo "           - Matched: {$outlet->nama_outlet}\n";

                        $outletDetails[] = [
                            'id' => $outlet->id,
                            'nama_outlet' => $outlet->nama_outlet,
                            'alamat' => $outlet->alamat_lengkap ?? '',
                            'kota' => $branch->kota,
                        ];
                    }
                }

                if (!empty($outletDetails)) {
                    $stopPoint = [
                        'urutan' => $stopIndex + 1,
                        'kota' => $kota,
                        'branch_id' => $branch->id,
                        'branch_name' => $branch->nama_cabang,
                        'durasi_singgah' => $durasiSinggah,
                        'outlets' => $outletDetails,
                    ];

                    $stopPoints[] = $stopPoint;
                    echo "         ✓ Stop point added with " . count($outletDetails) . " outlets\n";
                }
            }
        }

    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        \Log::error('Error getting stop points from schedule: ' . $e->getMessage());
    }

    return $stopPoints;
}

// Get trips
$today = \Carbon\Carbon::today();
$trips = \App\Models\DriverJadwal::with(['jadwal', 'jadwal.rutes'])
    ->where('tanggal', '>=', $today)
    ->limit(1)
    ->get();

echo "Testing with " . $trips->count() . " trips\n\n";

foreach ($trips as $trip) {
    echo "Trip ID: {$trip->id_jadwal_driver}\n";
    echo "Calling getStopPointsFromSchedule()...\n";

    $stopPoints = getStopPointsFromSchedule($trip);

    echo "\nResult:\n";
    echo json_encode($stopPoints, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

    echo "\nStop points count: " . count($stopPoints) . "\n";

    foreach ($stopPoints as $sp) {
        echo "\n  Stop {$sp['urutan']}: {$sp['kota']} ({$sp['branch_name']})\n";
        echo "    Outlets: " . count($sp['outlets']) . "\n";
        foreach ($sp['outlets'] as $outlet) {
            echo "      - {$outlet['nama_outlet']}\n";
        }
    }
}

echo "\n=== TEST COMPLETED ===\n";
