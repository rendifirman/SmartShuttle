<?php
require __DIR__ . '/vendor/autoload.php';

// bootstrap laravel
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\MembershipPayment;
use Illuminate\Support\Facades\Auth;

// simulate environment where user is logged in
$user = App\Models\User::first();
Auth::login($user);

// prepare a pembayaran record
$pb = Pembayaran::first();
if (!$pb) {
    echo "no pembayaran\n";
    exit;
}

$req = Request::create('/dummy', 'GET');
$controller = new PembayaranController(app(App\Services\PaylabsService::class));
$result = $controller->simulasiPembayaran($req, $pb->kode_pembayaran, 'berhasil');

echo "SimulasiPembayaran returned type: ".get_class($result)."\n";
if (method_exists($result,'getContent')) {
    echo $result->getContent();
}

// membership payment simulation
$mp = MembershipPayment::where('user_id',$user->id)->first();
if ($mp) {
    $req2 = Request::create('/membership/simulate', 'POST', [
        'transaction_id' => $mp->transaction_id,
        'payment_method' => 'qris'
    ]);
    $controller2 = new CustomerController(app(App\Services\PaylabsService::class));
    $res2 = $controller2->simulateMembershipPayment($req2);
    echo "\nMembership simulate: ";
    print_r($res2->getContent());
}
