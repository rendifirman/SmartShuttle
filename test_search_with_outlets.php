<?php

/**
 * Test script untuk verifikasi search results menampilkan outlets pemberhentian
 *
 * Requirements:
 * - Login sebagai customer untuk mendapatkan token
 * - Ada rute dengan segments dan outlets
 */

// Configurasi
$baseUrl = 'http://localhost:8000/api';
$searchEndpoint = $baseUrl . '/v1/schedule/search';

// Sample search parameters
$searchParams = [
    'departure_outlet' => 1, // Sesuaikan dengan outlet ID di database Anda
    'destination_outlet' => 3,
    'date' => date('Y-m-d', strtotime('+1 day')),
    'passenger_count' => 2
];

echo "========================================\n";
echo "TESTING SEARCH ENDPOINT WITH OUTLETS\n";
echo "========================================\n\n";

echo "Search Parameters:\n";
echo json_encode($searchParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Prepare cURL request
$ch = curl_init($searchEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchParams));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status Code: " . $httpCode . "\n\n";

if ($response) {
    $decoded = json_decode($response, true);

    if (isset($decoded['success']) && $decoded['success']) {
        echo "✓ Search Success!\n\n";

        if (!empty($decoded['data'])) {
            foreach ($decoded['data'] as $index => $schedule) {
                echo "--- Schedule " . ($index + 1) . " ---\n";
                echo "ID: " . $schedule['id'] . "\n";
                echo "Waktu Keberangkatan: " . $schedule['waktu_keberangkatan'] . "\n";
                echo "Kursi Tersedia: " . $schedule['kursi_tersedia'] . "\n";

                if (!empty($schedule['rutes'])) {
                    echo "\nRoutes:\n";
                    foreach ($schedule['rutes'] as $rute) {
                        echo "  - " . $rute['nama_rute'] . " (" . $rute['kota_asal'] . " → " . $rute['kota_tujuan'] . ")\n";

                        // Check if outlets_pemberhentian exists
                        if (isset($rute['outlets_pemberhentian']) && !empty($rute['outlets_pemberhentian'])) {
                            echo "    Outlets yang akan dilalui:\n";
                            foreach ($rute['outlets_pemberhentian'] as $outlet) {
                                echo "      " . $outlet['urutan'] . ". " . $outlet['nama_outlet'];
                                if (isset($outlet['is_pickup_point']) && $outlet['is_pickup_point']) {
                                    echo " (PICKUP)";
                                }
                                if (isset($outlet['is_drop_point']) && $outlet['is_drop_point']) {
                                    echo " (DROP)";
                                }
                                echo "\n";
                                if (isset($outlet['alamat'])) {
                                    echo "         Alamat: " . $outlet['alamat'] . "\n";
                                }
                                if (isset($outlet['telepon'])) {
                                    echo "         Telepon: " . $outlet['telepon'] . "\n";
                                }
                            }
                        } else {
                            echo "    ⚠ Tidak ada outlets pemberhentian ditemukan\n";
                        }
                    }
                }
                echo "\n";
            }
        } else {
            echo "⚠ Tidak ada jadwal tersedia untuk parameter pencarian ini\n";
        }
    } else {
        echo "✗ Search Failed!\n";
        echo "Message: " . ($decoded['message'] ?? 'Unknown error') . "\n";
        if (isset($decoded['errors'])) {
            echo "Errors: " . json_encode($decoded['errors'], JSON_PRETTY_PRINT) . "\n";
        }
    }
} else {
    echo "✗ Failed to make request\n";
}

echo "\n========================================\n";
echo "TEST COMPLETED\n";
echo "========================================\n";
?>
