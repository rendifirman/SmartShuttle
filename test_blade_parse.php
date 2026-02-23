<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Test blade compilation
$blade = $app->make('view');

try {
    // Compile profile.blade.php
    $compiler = $blade->getEngineResolver()->resolve('blade')->getCompiler();

    $profilePath = resource_path('views/driver/profile.blade.php');
    $editProfilePath = resource_path('views/driver/profile-edit.blade.php');

    echo "Testing profile.blade.php...\n";
    $result1 = $compiler->compile($profilePath);
    echo "✓ profile.blade.php compiled successfully\n\n";

    echo "Testing profile-edit.blade.php...\n";
    $result2 = $compiler->compile($editProfilePath);
    echo "✓ profile-edit.blade.php compiled successfully\n\n";

    echo "All blade files compiled successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
