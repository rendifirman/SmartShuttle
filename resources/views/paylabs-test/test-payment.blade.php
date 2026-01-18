<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Payment Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Payment Test Page</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- QR Code Display -->
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold text-blue-700 mb-3">QRIS Payment</h2>
                    @if($pembayaran->qr_code)
                    <div class="text-center">
                        <img src="{{ $pembayaran->qr_code }}" alt="QR Code" class="mx-auto mb-4">
                        <p class="text-sm text-gray-600">Scan dengan aplikasi e-wallet atau mobile banking</p>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">QR Code tidak tersedia</p>
                    </div>
                    @endif
                </div>

                <!-- Payment Details -->
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-700">Payment Details</h3>
                        <div class="mt-2 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode Pembayaran:</span>
                                <span class="font-semibold">{{ $pembayaran->kode_pembayaran }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Metode:</span>
                                <span class="font-semibold">{{ $pembayaran->metode }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold {{ $pembayaran->status === 'berhasil' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $pembayaran->status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expired:</span>
                                <span class="font-semibold">{{ $pembayaran->waktu_kadaluarsa->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div>
                        <h3 class="font-semibold text-gray-700">Booking Details</h3>
                        <div class="mt-2 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode Booking:</span>
                                <span class="font-semibold">{{ $pemesanan->kode_booking }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rute:</span>
                                <span class="font-semibold">{{ $from }} → {{ $to }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-semibold">{{ $date }} {{ $time }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="mt-8 pt-6 border-t">
                <h3 class="font-semibold text-gray-700 mb-4">Test Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('paylabs.test.index') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Back to Test Dashboard
                    </a>
                    <button onclick="simulateSuccess()"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Simulate Success Payment
                    </button>
                    <button onclick="simulateFailed()"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Simulate Failed Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function simulateSuccess() {
            if (confirm('Simulate successful payment?')) {
                try {
                    const response = await fetch(`/api/payment/callback`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            merchantId: '{{ config("paylabs.mid") }}',
                            merchantTradeNo: '{{ $pembayaran->kode_pembayaran }}',
                            platformTradeNo: '{{ $pembayaran->platform_trade_no }}' || 'PLT' + Date.now(),
                            amount: {{ $total }},
                            status: '02',
                            errCode: '0',
                            errCodeDes: 'Success',
                            paymentType: 'QRIS',
                            successTime: '{{ date("YmdHis") }}',
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Payment simulation successful!');
                        location.reload();
                    } else {
                        alert('Simulation failed: ' + (result.message || 'Unknown error'));
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                }
            }
        }

        async function simulateFailed() {
            if (confirm('Simulate failed payment?')) {
                try {
                    const response = await fetch(`/api/payment/callback`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            merchantId: '{{ config("paylabs.mid") }}',
                            merchantTradeNo: '{{ $pembayaran->kode_pembayaran }}',
                            platformTradeNo: '{{ $pembayaran->platform_trade_no }}' || 'PLT' + Date.now(),
                            amount: {{ $total }},
                            status: '09',
                            errCode: '99',
                            errCodeDes: 'Payment failed',
                            paymentType: 'QRIS',
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Payment failure simulated!');
                        location.reload();
                    } else {
                        alert('Simulation failed: ' + (result.message || 'Unknown error'));
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                }
            }
        }
    </script>
</body>
</html>
