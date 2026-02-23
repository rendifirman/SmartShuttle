<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Rute;
use App\Models\Branch;

echo "=== FIX RUTE PEMBERHENTIAN ===\n";

$routes = Rute::with(['cabangAsal.outlets', 'cabangTujuan.outlets'])->get();
$count = 0;
$fixed = 0;
$skipped = 0;

foreach ($routes as $rute) {
    $count++;
    // Get raw attribute value from DB
    $raw = $rute->getAttributes()['rute_pemberhentian'] ?? null;

    $needSave = false;

    // If raw is null or empty string, attempt reconstruction from branch outlets
    if (is_null($raw) || $raw === '') {
        $new = [];

        $cabangAsal = $rute->cabangAsal;
        $cabangTujuan = $rute->cabangTujuan;

        if ($cabangAsal && $cabangAsal->outlets) {
            $outletAsalList = collect($cabangAsal->outlets)
                ->filter(function($o){ return isset($o->status) && $o->status == 'aktif'; })
                ->map(function($o){ return $o->nama_outlet ?? null; })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($outletAsalList)) {
                $new[] = [
                    'kota' => $cabangAsal->kota,
                    'cabang' => $cabangAsal->nama_cabang,
                    'outlets' => $outletAsalList,
                    'durasi_singgah' => 0,
                    'jenis' => 'asal'
                ];
            }
        }

        if ($cabangTujuan && $cabangTujuan->outlets) {
            $outletTujuanList = collect($cabangTujuan->outlets)
                ->filter(function($o){ return isset($o->status) && $o->status == 'aktif'; })
                ->map(function($o){ return $o->nama_outlet ?? null; })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($outletTujuanList)) {
                $new[] = [
                    'kota' => $cabangTujuan->kota,
                    'cabang' => $cabangTujuan->nama_cabang,
                    'outlets' => $outletTujuanList,
                    'durasi_singgah' => 0,
                    'jenis' => 'tujuan'
                ];
            }
        }

        if (!empty($new)) {
            $rute->rute_pemberhentian = $new;
            $rute->save();
            echo "[FIXED] Rute ID {$rute->id}: reconstructed from branches\n";
            $fixed++;
            continue;
        }

        // nothing to reconstruct
        echo "[SKIP] Rute ID {$rute->id}: no data to reconstruct\n";
        $skipped++;
        continue;
    }

    // If raw is a JSON string, try to decode and re-save as normalized array
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
            $rute->rute_pemberhentian = $decoded;
            $rute->save();
            echo "[NORMALISED] Rute ID {$rute->id}: decoded JSON string and saved array\n";
            $fixed++;
            continue;
        }

        // If decode failed or empty, attempt to reconstruct similar to above
        $cabangAsal = $rute->cabangAsal;
        $cabangTujuan = $rute->cabangTujuan;

        $new = [];
        if ($cabangAsal && $cabangAsal->outlets) {
            $outletAsalList = collect($cabangAsal->outlets)
                ->filter(function($o){ return isset($o->status) && $o->status == 'aktif'; })
                ->map(function($o){ return $o->nama_outlet ?? null; })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($outletAsalList)) {
                $new[] = [
                    'kota' => $cabangAsal->kota,
                    'cabang' => $cabangAsal->nama_cabang,
                    'outlets' => $outletAsalList,
                    'durasi_singgah' => 0,
                    'jenis' => 'asal'
                ];
            }
        }

        if ($cabangTujuan && $cabangTujuan->outlets) {
            $outletTujuanList = collect($cabangTujuan->outlets)
                ->filter(function($o){ return isset($o->status) && $o->status == 'aktif'; })
                ->map(function($o){ return $o->nama_outlet ?? null; })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($outletTujuanList)) {
                $new[] = [
                    'kota' => $cabangTujuan->kota,
                    'cabang' => $cabangTujuan->nama_cabang,
                    'outlets' => $outletTujuanList,
                    'durasi_singgah' => 0,
                    'jenis' => 'tujuan'
                ];
            }
        }

        if (!empty($new)) {
            $rute->rute_pemberhentian = $new;
            $rute->save();
            echo "[RECONSTRUCTED] Rute ID {$rute->id}: decode failed, reconstructed from branches\n";
            $fixed++;
            continue;
        }

        echo "[SKIP] Rute ID {$rute->id}: decode failed and nothing to reconstruct\n";
        $skipped++;
        continue;
    }

    // If raw is already array (unlikely because accessor may transform), skip
    echo "[SKIP] Rute ID {$rute->id}: already normalized or unhandled type\n";
    $skipped++;
}

echo "\nDone. Scanned: {$count}, Fixed: {$fixed}, Skipped: {$skipped}\n";

