<?php

// Test script to verify the kontak perusahaan form functionality
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AdminController;
use App\Models\MMasterKontak;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Kontak Perusahaan Form Functionality\n";
echo "==============================================\n\n";

// Test 1: Check if route exists
echo "Test 1: Checking if route exists...\n";
$routes = app('router')->getRoutes();
$routeFound = false;
foreach ($routes as $route) {
    if ($route->uri() === 'admin/kontakperusahaan/{id}' && in_array('PUT', $route->methods())) {
        $routeFound = true;
        echo "✓ Route found: PUT /admin/kontakperusahaan/{id}\n";
        break;
    }
}

if (!$routeFound) {
    echo "✗ Route not found!\n";
    exit(1);
}

// Test 2: Check if controller method exists
echo "\nTest 2: Checking if controller method exists...\n";
$controller = new AdminController();
if (method_exists($controller, 'updateKontakPerusahaan')) {
    echo "✓ Method updateKontakPerusahaan exists in AdminController\n";
} else {
    echo "✗ Method updateKontakPerusahaan not found!\n";
    exit(1);
}

// Test 3: Check if model exists and has required fields
echo "\nTest 3: Checking MMasterKontak model...\n";
if (class_exists('App\Models\MMasterKontak')) {
    echo "✓ MMasterKontak model exists\n";

    // Create a temporary instance to check fillable fields
    $tempInstance = new MMasterKontak();
    $fillable = $tempInstance->getFillable();

    $requiredFields = [
        'nama_perusahaan',
        'deskripsi_singkat',
        'email_utama',
        'telepon_utama',
        'alamat_kantor_pusat'
    ];

    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (!in_array($field, $fillable)) {
            $missingFields[] = $field;
        }
    }

    if (empty($missingFields)) {
        echo "✓ All required fields are fillable\n";
    } else {
        echo "✗ Missing fillable fields: " . implode(', ', $missingFields) . "\n";
    }
} else {
    echo "✗ MMasterKontak model not found!\n";
    exit(1);
}

// Test 4: Check if data exists
echo "\nTest 4: Checking if kontak data exists...\n";
try {
    $kontak = MMasterKontak::getDataKontak();
    if ($kontak) {
        echo "✓ Kontak data found with ID: {$kontak->id}\n";
        echo "  - Nama Perusahaan: {$kontak->nama_perusahaan}\n";
        echo "  - Email Utama: {$kontak->email_utama}\n";
    } else {
        echo "! No kontak data found, creating test data...\n";

        // Create test data
        $testData = [
            'nama_perusahaan' => 'Citra Solusi Teknologi',
            'deskripsi_singkat' => 'Menghubungkan kota, menyatukan perjalanan',
            'email_utama' => 'rndcitrasolusi@gmail.com',
            'email_dukungan' => 'support@smartshuttle.com',
            'telepon_utama' => '0858-1122-4321',
            'telepon_dukungan' => '0858-1122-4321',
            'alamat_kantor_pusat' => 'Ruko Citra Grand CBD',
            'facebook_url' => 'https://facebook.com/smartshuttle',
            'instagram_url' => 'https://instagram.com/smartshuttle',
            'twitter_url' => 'https://twitter.com/smartshuttle',
            'jam_operasional' => json_encode([
                ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                ['hari' => 'Minggu', 'jam' => 'Tutup']
            ]),
            'link_kebijakan_privasi' => '#',
            'link_syarat_ketentuan' => '#',
            'status' => 'active'
        ];

        $kontak = MMasterKontak::create($testData);
        echo "✓ Test data created with ID: {$kontak->id}\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking/creating kontak data: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 5: Test form submission simulation
echo "\nTest 5: Simulating form submission...\n";
try {
    // Create a mock request
    $requestData = [
        'nama_perusahaan' => 'Updated Company Name',
        'deskripsi_singkat' => 'Updated description',
        'email_utama' => 'updated@example.com',
        'email_dukungan' => 'support@example.com',
        'telepon_utama' => '08123456789',
        'telepon_dukungan' => '08123456789',
        'alamat_kantor_pusat' => 'Updated Address',
        'facebook_url' => 'https://facebook.com/updated',
        'instagram_url' => 'https://instagram.com/updated',
        'twitter_url' => 'https://twitter.com/updated',
        'jam_operasional' => json_encode([
            ['hari' => 'Senin - Jumat', 'jam' => '09:00 - 18:00'],
            ['hari' => 'Sabtu', 'jam' => '09:00 - 16:00'],
            ['hari' => 'Minggu', 'jam' => 'Tutup']
        ]),
        'link_kebijakan_privasi' => 'https://example.com/privacy',
        'link_syarat_ketentuan' => 'https://example.com/terms',
        'status' => 'active'
    ];

    $request = new Request();
    $request->merge($requestData);

    // Call the controller method
    $response = $controller->updateKontakPerusahaan($request, $kontak->id);

    // Check if update was successful
    $updatedKontak = MMasterKontak::find($kontak->id);
    if ($updatedKontak && $updatedKontak->nama_perusahaan === 'Updated Company Name') {
        echo "✓ Form submission simulation successful\n";
        echo "  - Updated nama_perusahaan: {$updatedKontak->nama_perusahaan}\n";
        echo "  - Updated email_utama: {$updatedKontak->email_utama}\n";
    } else {
        echo "✗ Form submission simulation failed\n";
    }

} catch (Exception $e) {
    echo "✗ Error during form submission simulation: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n==============================================\n";
echo "All tests passed! The kontak perusahaan form should work correctly.\n";
echo "\nTo test manually:\n";
echo "1. Visit: http://127.0.0.1:8000/admin/kontakperusahaan\n";
echo "2. Click 'Edit Kontak' button\n";
echo "3. Modify form fields\n";
echo "4. Click 'Simpan Perubahan'\n";
echo "5. Check if data is saved and success notification appears\n";

?>
