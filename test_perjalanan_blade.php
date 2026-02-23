<?php
// Test blade compilation for perjalanan view

try {
    // Initialize the view factory
    $blade = \Illuminate\Support\Facades\Blade::make('driver.perjalanan', [
        'tripsData' => json_encode([
            [
                'id' => 1,
                'jadwal_id' => 1,
                'driver_id' => 1,
                'departure_time' => '08:00',
                'route_name' => 'Jakarta - Bandung',
                'occupied_seats' => 10,
                'total_seats' => 12,
                'status' => 'Akan Berangkat'
            ]
        ]),
        'driver' => auth()->guard('driver')->user()
    ]);

    echo "✓ Blade template 'driver.perjalanan' compiles successfully!\n";
    echo "Template found at: resources/views/driver/perjalanan.blade.php\n";

} catch (\Exception $e) {
    echo "✗ Error compiling blade template:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
?>
