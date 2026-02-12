<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$view = file_get_contents(__DIR__ . '/resources/views/customer/pesan.blade.php');
$compiler = $app['blade.compiler'];

try {
    $compiled = $compiler->compile($view);
    echo "Blade compilation successful!" . PHP_EOL;
    echo "Compiled length: " . strlen($compiled) . " bytes" . PHP_EOL;
} catch(Exception $e) {
    echo 'Blade Compilation Error: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . PHP_EOL;
    echo 'Line: ' . $e->getLine() . PHP_EOL;
}
?>
