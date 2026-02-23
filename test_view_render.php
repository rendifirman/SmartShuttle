<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    $view = view('customer.pesan', []);
    echo "View rendered successfully!";
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo 'Line: ' . $e->getLine() . PHP_EOL;
    echo 'File: ' . $e->getFile() . PHP_EOL;
    if ($e instanceof ParseError) {
        echo 'ParseError detected!' . PHP_EOL;
    }
}
?>
