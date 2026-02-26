<?php
$file = file_get_contents('app/Http/Controllers/API/PaymentController.php');
$braces = 0;
$lines = explode(PHP_EOL, $file);

foreach ($lines as $lineNum => $content) {
    $opens = substr_count($content, '{');
    $closes = substr_count($content, '}');
    $braces += $opens - $closes;

    if ($braces < 0) {
        echo 'ERROR at line ' . ($lineNum + 1) . ': ' . trim(substr($content, 0, 80)) . PHP_EOL;
        echo 'Brace count becomes: ' . $braces . PHP_EOL;
        break;
    }

    if ($opens > 0) {
        echo 'Line ' . ($lineNum + 1) . ': +' . $opens . ' -> total: ' . $braces . PHP_EOL;
    }
    if ($closes > 0) {
        echo 'Line ' . ($lineNum + 1) . ': -' . $closes . ' -> total: ' . $braces . PHP_EOL;
    }
}

echo PHP_EOL . 'Final brace count: ' . $braces . PHP_EOL;
?>
