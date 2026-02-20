<?php
// File untuk debugging - akses via browser di /test-schedules
// Gunakan url: http://localhost/test-schedules

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'tinker',
    ]),
    new \Symfony\Component\Console\Output\BufferedOutput
);

$container = $app->make(\Illuminate\Container\Container::class);

// Manually bootstrap
foreach ([
    \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
] as $bootstrapper) {
    $container->make($bootstrapper)->bootstrap($app);
}

use Illuminate\Support\Facades\DB;
use App\Models\DriverJadwal;

echo "=== Database Check ===\n\n";
echo "Total DriverJadwal records: " . DriverJadwal::count() . "\n";
echo "\nFirst 5 records:\n";

$records = DriverJadwal::select('id_jadwal_driver', 'id_driver', 'tanggal', 'rute', 'waktu_keberangkatan', 'waktu_kedatangan')->limit(5)->get();

foreach ($records as $r) {
    echo "  ID: {$r->id_jadwal_driver}, Driver: {$r->id_driver}, Tanggal: {$r->tanggal}, Rute: {$r->rute}\n";
}

echo "\nUnique tanggal values:\n";
$dates = DriverJadwal::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->limit(10)->get();

foreach ($dates as $d) {
    echo "  - {$d->tanggal}\n";
}
