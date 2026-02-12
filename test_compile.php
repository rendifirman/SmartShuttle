<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    $content = file_get_contents('resources/views/customer/pesan.blade.php');
    $compiled = \Blade::compile($content);
    echo "Compilation successful!\n";
    echo "Last 500 chars:\n";
    echo substr($compiled, -500);
} catch (\Exception $e) {
    echo "Compilation error:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
}
