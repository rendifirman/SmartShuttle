<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paylabs Callback Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Paylabs Callback Simulator</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Simulate Paylabs Callback</h2>

            <form id="callback-form" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Merchant ID</label>
                    <input type="text" name="merchantId" value="{{ config('paylabs.mid', '010529') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Merchant Trade No (kode_pembayaran)</label>
                    <input type="text" name="merchantTradeNo" value="TEST{{ time() }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Platform Trade No</label>
                    <input type="text" name="platformTradeNo" value="PLT{{ time() }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" value="100000"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded">
                        <option value="01">01 - Pending (QRIS)</option>
                        <option value="02">02 - Paid (QRIS)</option>
                        <option value="09">09 - Failed (QRIS)</option>
                        <option value="PENDING">PENDING (Other)</option>
                        <option value="PAID">PAID (Other)</option>
                        <option value="FAILED">FAILED (Other)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Payment Type</label>
                    <select name="paymentType" class="w-full px-3 py-2 border border-gray-300 rounded">
                        <option value="QRIS">QRIS</option>
                        <option value="VIRTUAL_ACCOUNT">Virtual Account</option>
                        <option value="E_WALLET">E-Wallet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Success Time (yyyyMMddHHmmss)</label>
                    <input type="text" name="successTime" value="{{ date('YmdHis') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Expired Time (yyyyMMddHHmmss)</label>
                    <input type="text" name="expiredTime" value="{{ date('YmdHis', strtotime('+30 minutes')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Error Code</label>
                    <input type="text" name="errCode" value="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Error Description</label>
                    <input type="text" name="errCodeDes" value="Success"
                           class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Simulate Callback
                    </button>
                    <button type="button" onclick="fillSuccessData()" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Fill Success Data
                    </button>
                    <button type="button" onclick="fillFailedData()" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                        Fill Failed Data
                    </button>
                </div>
            </form>

            <div id="callback-result" class="mt-6 p-4 bg-gray-50 rounded hidden"></div>
        </div>
    </div>

    <script>
        document.getElementById('callback-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            const resultDiv = document.getElementById('callback-result');
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<div class="text-blue-600">Sending callback...</div>';

            try {
                const response = await fetch('{{ route("paylabs.test.callback-simulate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-TIMESTAMP': new Date().toISOString(),
                        'X-PARTNER-ID': data.merchantId,
                        'X-REQUEST-ID': 'SIM' + Date.now(),
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    resultDiv.innerHTML = `
                        <div class="text-green-600 font-semibold">✓ Callback Sent Successfully</div>
                        <div class="mt-2 text-sm">Status: ${response.status}</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(result, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="text-red-600 font-semibold">✗ Callback Failed</div>
                        <div class="mt-2 text-sm">Status: ${response.status}</div>
                        <pre class="mt-2 text-sm bg-gray-800 text-gray-100 p-3 rounded overflow-auto">${JSON.stringify(result, null, 2)}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-600 font-semibold">✗ Error: ${error.message}</div>
                `;
            }
        });

        function fillSuccessData() {
            document.querySelector('input[name="status"]').value = '02';
            document.querySelector('input[name="errCode"]').value = '0';
            document.querySelector('input[name="errCodeDes"]').value = 'Success';
            document.querySelector('select[name="status"]').value = '02';
        }

        function fillFailedData() {
            document.querySelector('input[name="status"]').value = '09';
            document.querySelector('input[name="errCode"]').value = '99';
            document.querySelector('input[name="errCodeDes"]').value = 'Payment failed';
            document.querySelector('select[name="status"]').value = '09';
        }
    </script>
</body>
</html>
