<?php
// Check for bracket mismatch
$file = file_get_contents('app/Http/Controllers/API/PaymentController.php');
$lines = explode("\n", $file);

$braceCount = 0;
$lastNegativeLine = -1;

foreach ($lines as $lineNum => $line) {
    // Skip comments
    $line = preg_replace('#//.*$#', '', $line);
    $line = preg_replace('#/\*.*?\*/#s',  '', $line);

    $opens = substr_count($line, '{');
    $closes = substr_count($line, '}');
    $braceCount += $opens - $closes;

    if ($opens != $closes) {
        echo "Line " . ($lineNum + 1) . ": $opens open, $closes close, Total: $braceCount\n";
        if (trim($line)) {
            echo "  Content: " . trim(substr($line, 0, 60)) . "\n";
        }
    }

    if ($braceCount < 0) {
        $lastNegativeLine = $lineNum + 1;
        echo "ERROR: Negative brace count at line $lastNegativeLine\n";
        break;
    }
}

echo "\nFinal brace count: $braceCount\n";
if ($braceCount != 0) {
    echo "ERROR: Unmatched braces! Count is $braceCount\n";
}
?>
