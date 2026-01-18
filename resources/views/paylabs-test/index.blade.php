<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paylabs Test Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Paylabs Integration Test</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Test Connection Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Test Connection</h2>
                <p class="text-gray-600 mb-4">Test koneksi ke server Paylabs</p>
                <button onclick="testConnection()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Test Connection
                </button>
                <div id="connection-result" class="mt-4 p-3 bg-gray-50 rounded hidden"></div>
            </div>

            <!-- Quick Test Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Quick Test</h2>
                <p class="text-gray-600 mb-4">Buat payment dummy untuk testing</p>
                <button onclick="quickTest()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Quick Test
                </button>
                <div id="quick-test-result" class="mt-4 p-3 bg-gray-50 rounded hidden"></div>
            </div>

            <!-- Test Payment Methods -->
            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Test Payment Methods</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <button onclick="testPayment('qris')" class="bg-purple-500 text-white px-4 py-3 rounded hover:bg-purple-600">
                        QRIS
                    </button>
                    <button onclick="testPayment('bca_va')" class="bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700">
                        BCA VA
                    </button>
                    <button onclick="testPayment('mandiri_va')" class="bg-red-500 text-white px-4 py-3 rounded hover:bg-red-600">
                        Mandiri VA
                    </button>
                    <button onclick="testPayment('bni_va')" class="bg-yellow-500 text-white px-4 py-3 rounded hover:bg-yellow-600">
                        BNI VA
                    </button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button onclick="testPayment('bri_va')" class="bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700">
                        BRI VA
                    </button>
                    <button onclick="testPayment('dana')" class="bg-teal-500 text-white px-4 py-3 rounded hover:bg-teal-600">
                        DANA
                    </button>
                    <button onclick="testPayment('gopay')" class="bg-green-500 text-white px-4 py-3 rounded hover:bg-green-600">
                        GoPay
                    </button>
                    <button onclick="testPayment('ovo')" class="bg-purple-600 text-white px-4 py-3 rounded hover:bg-purple-700">
                        OVO
                    </button>
                </div>
                <div id="payment-test-result" class="mt-4 p-3 bg-gray-50 rounded hidden"></div>
            </div>

            <!-- Configuration Info -->
            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Configuration</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Environment:</span>
                        <span class="font-semibold">{{ config('paylabs.environment', 'sandbox') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Merchant ID:</span>
                        <span class="font-semibold">{{ config('paylabs.mid', 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Base URL:</span>
                        <span class="font-semibold">{{ config('paylabs.base_url', 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Testing Mode:</span>
                        <span class="font-semibold {{ config('paylabs.testing.enabled', false) ? 'text-green-600' : 'text-red-600' }}">
                            {{ config('paylabs.testing.enabled', false) ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Callback Simulator -->
            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Callback Simulator</h2>
                <p class="text-gray-600 mb-4">Simulasikan callback dari Paylabs</p>
                <a href="{{ route('paylabs.test.callback-simulate') }}" class="inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                    Open Callback Simulator
                </a>
            </div>
        </div>
    </div>

    <script>
        async function testConnection() {
            const resultDiv = document.getElementById('connection-result');
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<div class="text-blue-600">Testing connection...</div>';

            try {
                const response = await fetch('{{ route("paylabs.test.connection") }}');
                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="text-green-600 font-semibold">✓ Connection Successful</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="text-red-600 font-semibold">✗ Connection Failed</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-600 font-semibold">✗ Error: ${error.message}</div>
                `;
            }
        }

        async function quickTest() {
            const resultDiv = document.getElementById('quick-test-result');
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<div class="text-blue-600">Running quick test...</div>';

            try {
                const response = await fetch('{{ route("paylabs.test.quick") }}');
                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="text-green-600 font-semibold">✓ Quick Test Successful</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="text-red-600 font-semibold">✗ Quick Test Failed</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-600 font-semibold">✗ Error: ${error.message}</div>
                `;
            }
        }

        async function testPayment(method) {
            const resultDiv = document.getElementById('payment-test-result');
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = `<div class="text-blue-600">Testing ${method} payment...</div>`;

            try {
                const response = await fetch(`{{ route("paylabs.test.create", ["method" => ":method"]) }}`.replace(':method', method));
                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="text-green-600 font-semibold">✓ ${method.toUpperCase()} Payment Created</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="text-red-600 font-semibold">✗ ${method.toUpperCase()} Payment Failed</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-600 font-semibold">✗ Error: ${error.message}</div>
                `;
            }
        }
    </script>
</body>
</html>
