<?php
require __DIR__ . '/vendor/autoload.php';

// bootstrap laravel
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pembayaran;
use App\Services\PaylabsService;

$first = Pembayaran::first();
if (!$first) {
    echo "No Pembayaran records found\n";
    exit(1);
}

$svc = new PaylabsService();
$result = $svc->createPayment($first, 'QRIS', 'QRIS');
print_r($result);
