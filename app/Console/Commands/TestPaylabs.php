<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaylabsService;
use App\Models\Pembayaran;
use App\Models\Pemesanan;

class TestPaylabs extends Command
{
    protected $signature = 'paylabs:test {--booking= : Kode booking untuk testing}';
    protected $description = 'Test Paylabs integration';

    public function handle()
    {
        $paylabsService = new PaylabsService();

        $this->info('=== PAYLABS INTEGRATION TEST ===');

        // Test 1: Connection
        $this->info('1. Testing connection...');
        $connection = $paylabsService->testConnection();

        if ($connection['success']) {
            $this->info('✓ Connection successful');
            $this->line('   Base URL: ' . $connection['base_url']);
            $this->line('   MID: ' . $connection['mid']);
            $this->line('   Status: ' . $connection['status_code']);
        } else {
            $this->error('✗ Connection failed: ' . ($connection['error'] ?? 'Unknown error'));
        }

        // Test 2: Create payment if booking code provided
        if ($booking = $this->option('booking')) {
            $this->info("\n2. Testing payment creation for booking: " . $booking);

            $pemesanan = Pemesanan::where('kode_booking', $booking)->first();

            if (!$pemesanan) {
                $this->error('Booking not found');
                return;
            }

            // Create test payment
            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'kode_pembayaran' => 'TEST' . time(),
                'jumlah' => 100000,
                'metode' => 'qris',
                'status' => 'menunggu',
                'waktu_kadaluarsa' => now()->addMinutes(30),
            ]);

            $result = $paylabsService->createPayment($pembayaran, 'QRIS', 'QRIS');

            if ($result['success']) {
                $this->info('✓ Payment creation successful');
                $this->line('   Transaction ID: ' . $result['transaction_id']);
                $this->line('   Status: ' . $result['payment_data']['status']);

                if (isset($result['payment_data']['qr_code'])) {
                    $this->line('   QR Code: ' . substr($result['payment_data']['qr_code'], 0, 50) . '...');
                }
            } else {
                $this->error('✗ Payment creation failed: ' . $result['error']);
            }

            // Cleanup
            $pembayaran->delete();
        }

        // Test 3: Signature generation
        $this->info("\n3. Testing signature generation...");
        try {
            $testData = [
                'requestType' => 'test',
                'merchantId' => config('paylabs.mid'),
                'amount' => 100000,
            ];

            $signature = $paylabsService->generateSignature($testData);
            $this->info('✓ Signature generation successful');
            $this->line('   Signature: ' . substr($signature, 0, 50) . '...');
            $this->line('   Length: ' . strlen($signature) . ' chars');
        } catch (\Exception $e) {
            $this->error('✗ Signature generation failed: ' . $e->getMessage());
        }

        $this->info("\n=== TEST COMPLETE ===");

        return 0;
    }
}
