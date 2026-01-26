<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Membership - SmartShuttle</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @extends('layouts.app-profile')

    @section('title', 'Membership SmartShuttle')

    @push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
        }

        /* PAYMENT STYLES */
        .membership-payment-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
            min-height: calc(100vh - 180px);
        }

        .card-box {
            background: #fff;
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 22px;
            border: 1px solid #e5e7eb;
        }

        .section-title {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 18px;
            color: #111;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 20px;
            background: #FF6B2C;
            border-radius: 2px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            font-size: 14px;
            color: #111;
        }

        .badge-code {
            background: linear-gradient(135deg, #E8F0FE 0%, #DBEAFE 100%);
            color: #1D4ED8;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px solid #BFDBFE;
            box-shadow: 0 2px 4px rgba(29, 78, 216, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            padding: 8px 0;
        }

        .summary-row:not(.total) {
            border-bottom: 1px dashed #e5e7eb;
        }

        .summary-row.total {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 2px solid #E5E7EB;
            font-weight: 700;
            font-size: 16px;
            color: #111;
        }

        .summary-row.total span:last-child {
            color: #FF6B2C;
        }

        .payment-option {
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            background: #fff;
        }

        .payment-option:hover {
            border-color: #FF6B2C;
            background: #FFF7F3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 44, 0.1);
        }

        .payment-option input[type="radio"] {
            margin-right: 14px;
            width: 18px;
            height: 18px;
            accent-color: #FF6B2C;
            cursor: pointer;
        }

        .payment-option input[type="radio"]:checked ~ span {
            color: #FF6B2C;
            font-weight: 600;
        }

        .btn-pay {
            width: 100%;
            background: linear-gradient(135deg, #FF6B2C 0%, #FF8C5A 100%);
            border: none;
            color: #fff;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 24px;
            box-shadow: 0 4px 15px rgba(255, 107, 44, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-pay:hover {
            background: linear-gradient(135deg, #e85b1f 0%, #FF6B2C 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 44, 0.4);
        }

        .btn-pay:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 8px;
        }

        .status-inactive {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .status-active {
            background: #DCFCE7;
            color: #16A34A;
            border: 1px solid #BBF7D0;
        }

        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .success-message {
            background: #DCFCE7;
            border: 1px solid #BBF7D0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            display: none;
        }

        .success-message.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-message {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 16px;
            font-size: 14px;
            display: none;
        }

        .error-message.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .payment-details {
            margin-top: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            display: none;
        }

        .payment-details.show {
            display: block;
        }

        .qris-code {
            text-align: center;
            margin: 20px 0;
        }

        .qris-code img {
            max-width: 200px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }

        .va-number {
            background: white;
            border: 2px dashed #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            color: #00274D;
            margin: 15px 0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .membership-payment-container {
                padding: 0 15px;
            }

            .card-box {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .card-box {
                padding: 18px;
            }

            .section-title {
                font-size: 18px;
            }

            .payment-option {
                padding: 14px 16px;
            }

            .btn-pay {
                padding: 14px;
                font-size: 15px;
            }
        }

        @media (max-width: 576px) {
            .card-box {
                padding: 16px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-row .info-value {
                align-self: flex-end;
            }

            .payment-option span {
                font-size: 14px;
            }

            .btn-pay {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
    @endpush
</head>
<body>
    @section('content')
    <!-- MAIN CONTENT -->
    <div class="membership-payment-container">
        {{-- ================= KONFIRMASI MEMBERSHIP ================= --}}
        <div class="card-box">
            <div class="section-title">Konfirmasi Membership</div>

            <div class="info-row">
                <div class="info-label">Nama</div>
                <div class="info-value" id="confirmName">{{ Auth::user()->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value" id="confirmEmail">{{ Auth::user()->email }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Kode Member</div>
                <div class="badge-code">SS-MBS-{{ date('dmy') }}-{{ strtoupper(substr(md5(Auth::id()), 0, 4)) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    Belum Aktif
                    <span class="status-indicator status-inactive">Menunggu Pembayaran</span>
                </div>
            </div>
        </div>

        {{-- ================= RINGKASAN PEMBAYARAN ================= --}}
        <div class="card-box">
            <div class="section-title">Ringkasan Pembayaran</div>

            <div class="summary-row">
                <span>Cetak Kartu Fisik</span>
                <span>Rp 20.000</span>
            </div>

            <div class="summary-row">
                <span>Biaya Admin</span>
                <span>Rp 0</span>
            </div>

            <div class="summary-row total">
                <span>Total Pembayaran</span>
                <span>Rp 20.000</span>
            </div>

            <div class="summary-row" style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                <span>Masa aktif: 12 bulan</span>
                <span>Mulai: {{ date('d M Y') }}</span>
            </div>
        </div>

        {{-- ================= METODE PEMBAYARAN ================= --}}
        <div class="card-box">
            <div class="section-title">Metode Pembayaran</div>

            <form action="{{ route('customer.membership.payment.submit') }}" method="POST" id="paymentForm">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $payment->transaction_id ?? '' }}">

                <!-- QRIS Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="qris" checked>
                    <span>QRIS (Scan via aplikasi / outlet)</span>
                </label>

                <!-- BCA VA Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="bca_va">
                    <span>BCA Virtual Account</span>
                </label>

                <!-- Mandiri VA Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="mandiri_va">
                    <span>Mandiri Virtual Account</span>
                </label>

                <!-- BNI VA Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="bni_va">
                    <span>BNI Virtual Account</span>
                </label>

                <!-- BRI VA Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="bri_va">
                    <span>BRI Virtual Account</span>
                </label>

                <!-- Manual Transfer Option -->
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="manual_transfer">
                    <span>Manual Transfer Bank</span>
                </label>

                <!-- QRIS Details (hidden by default) -->
                <div class="payment-details" id="qrisDetails">
                    <div class="qris-code">
                        @if($payment && $payment->qr_code)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($payment->qr_code) }}" alt="QR Code">
                        @elseif($payment && $payment->qris_url)
                            <img src="{{ $payment->qris_url }}" alt="QR Code">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('SS-MEMBERSHIP-' . ($payment->transaction_id ?? 'TEST') . '-Rp20000') }}" alt="QR Code">
                        @endif
                        <p style="margin-top: 10px; font-size: 14px;">Scan QR Code di atas untuk pembayaran</p>
                    </div>
                    <p style="text-align: center; font-size: 13px; color: #666;">
                        QR Code akan kadaluarsa dalam 24 jam
                    </p>
                </div>

                <!-- BCA VA Details (hidden by default) -->
                <div class="payment-details" id="bcaDetails">
                    <p style="margin-bottom: 10px;">Silakan transfer ke Virtual Account berikut:</p>
                    <div class="va-number">
                        {{ $payment->no_virtual_account ?? '8888123456789012' }}
                    </div>
                    <p style="font-size: 13px; color: #666;">
                        <strong>Bank:</strong> BCA<br>
                        <strong>Atas Nama:</strong> SMART SHUTTLE<br>
                        <strong>Jumlah:</strong> Rp {{ number_format($payment->total_amount ?? 20000, 0, ',', '.') }}<br>
                        <strong>Masa berlaku:</strong> 24 jam
                    </p>
                </div>

                <!-- Mandiri VA Details (hidden by default) -->
                <div class="payment-details" id="mandiriDetails">
                    <p style="margin-bottom: 10px;">Silakan transfer ke Virtual Account berikut:</p>
                    <div class="va-number">
                        {{ $payment->no_virtual_account ?? '8888123456789012' }}
                    </div>
                    <p style="font-size: 13px; color: #666;">
                        <strong>Bank:</strong> Mandiri<br>
                        <strong>Atas Nama:</strong> SMART SHUTTLE<br>
                        <strong>Jumlah:</strong> Rp {{ number_format($payment->total_amount ?? 20000, 0, ',', '.') }}<br>
                        <strong>Masa berlaku:</strong> 24 jam
                    </p>
                </div>

                <!-- BNI VA Details (hidden by default) -->
                <div class="payment-details" id="bniDetails">
                    <p style="margin-bottom: 10px;">Silakan transfer ke Virtual Account berikut:</p>
                    <div class="va-number">
                        {{ $payment->no_virtual_account ?? '8888123456789012' }}
                    </div>
                    <p style="font-size: 13px; color: #666;">
                        <strong>Bank:</strong> BNI<br>
                        <strong>Atas Nama:</strong> SMART SHUTTLE<br>
                        <strong>Jumlah:</strong> Rp {{ number_format($payment->total_amount ?? 20000, 0, ',', '.') }}<br>
                        <strong>Masa berlaku:</strong> 24 jam
                    </p>
                </div>

                <!-- BRI VA Details (hidden by default) -->
                <div class="payment-details" id="briDetails">
                    <p style="margin-bottom: 10px;">Silakan transfer ke Virtual Account berikut:</p>
                    <div class="va-number">
                        {{ $payment->no_virtual_account ?? '8888123456789012' }}
                    </div>
                    <p style="font-size: 13px; color: #666;">
                        <strong>Bank:</strong> BRI<br>
                        <strong>Atas Nama:</strong> SMART SHUTTLE<br>
                        <strong>Jumlah:</strong> Rp {{ number_format($payment->total_amount ?? 20000, 0, ',', '.') }}<br>
                        <strong>Masa berlaku:</strong> 24 jam
                    </p>
                </div>

                <!-- Manual Transfer Details (hidden by default) -->
                <div class="payment-details" id="manualTransferDetails">
                    <p style="margin-bottom: 10px;">Silakan transfer ke salah satu rekening berikut:</p>
                    <div style="background: white; border-radius: 8px; padding: 15px; margin: 15px 0;">
                        <p><strong>Bank BCA</strong><br>
                        123-456-7890<br>
                        PT. Smart Shuttle Indonesia</p>
                    </div>
                    <div style="background: white; border-radius: 8px; padding: 15px; margin: 15px 0;">
                        <p><strong>Bank Mandiri</strong><br>
                        098-765-4321<br>
                        PT. Smart Shuttle Indonesia</p>
                    </div>
                    <p style="font-size: 13px; color: #666;">
                        Setelah transfer, silakan konfirmasi dengan mengirim bukti transfer ke WhatsApp: 0858-1122-4321
                    </p>
                </div>

                <!-- Error Message -->
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText"></span>
                </div>

                <!-- Success Message -->
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    Pembayaran berhasil! Membership Anda akan segera aktif.
                </div>

                <button type="submit" class="btn-pay" id="payButton">
                    <span id="buttonText">Bayar & Aktifkan Membership</span>
                </button>

                <!-- Simulation Button (for testing) -->
                @if(env('APP_ENV') === 'local' || env('APP_DEBUG') === true)
                <button type="button" class="btn-pay" id="simulateButton" style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); margin-top: 12px;" onclick="simulateMembershipPayment()">
                    <span id="simulateButtonText"><i class="fas fa-flask"></i> Simulasi Pembayaran</span>
                </button>
                @endif
            </form>

            <div style="text-align: center; margin-top: 12px; font-size: 12px; color: #9ca3af;">
                Dengan melanjutkan, Anda menyetujui
                <a href="{{ route('customer.syarat.ketentuan.membership') }}" target="_blank" style="color: #FF6B2C; text-decoration: none;">Syarat dan Ketentuan Membership</a>
            </div>
        </div>
    </div>
    @endsection

    <script>
        // Simulasi pembayaran untuk testing - GLOBAL SCOPE
        function simulateMembershipPayment() {
            const simulateButton = document.getElementById('simulateButton');
            const simulateButtonText = document.getElementById('simulateButtonText');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');
            const transactionId = document.querySelector('input[name="transaction_id"]').value;

            // Hide previous messages
            errorMessage.classList.remove('show');
            successMessage.classList.remove('show');

            if (!transactionId) {
                errorText.textContent = 'Data transaksi tidak valid!';
                errorMessage.classList.add('show');
                return;
            }

            // Show loading state
            simulateButtonText.innerHTML = '<span class="loading"></span> Mensimulasi Pembayaran...';
            simulateButton.disabled = true;

            // Call simulate API
            fetch('{{ route("customer.membership.payment.simulate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    transaction_id: transactionId
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || `HTTP ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Simulate response:', data);

                if (data.success) {
                    // Show success message
                    successMessage.classList.add('show');
                    simulateButtonText.innerHTML = '<i class="fas fa-check-circle"></i> Simulasi Berhasil!';

                    setTimeout(() => {
                        window.location.href = '{{ route("customer.membership") }}';
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Simulasi pembayaran gagal');
                }
            })
            .catch(error => {
                console.error('Simulate error:', error);
                simulateButtonText.innerHTML = '<i class="fas fa-flask"></i> Simulasi Pembayaran';
                simulateButton.disabled = false;
                errorText.textContent = error.message || 'Gagal mensimulasi pembayaran. Silakan coba lagi.';
                errorMessage.classList.add('show');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Payment form submission
            const paymentForm = document.getElementById('paymentForm');
            const payButton = document.getElementById('payButton');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');

            // Payment method details sections
            const qrisDetails = document.getElementById('qrisDetails');
            const bcaDetails = document.getElementById('bcaDetails');
            const mandiriDetails = document.getElementById('mandiriDetails');
            const bniDetails = document.getElementById('bniDetails');
            const briDetails = document.getElementById('briDetails');
            const manualTransferDetails = document.getElementById('manualTransferDetails');

            // Payment method selection
            const paymentOptions = document.querySelectorAll('input[name="payment_method"]');

            // Show/hide payment details based on selected method
            function showPaymentDetails(method) {
                // Hide all details first
                qrisDetails.classList.remove('show');
                bcaDetails.classList.remove('show');
                mandiriDetails.classList.remove('show');
                bniDetails.classList.remove('show');
                briDetails.classList.remove('show');
                manualTransferDetails.classList.remove('show');

                // Show selected method details
                if (method === 'qris') {
                    qrisDetails.classList.add('show');
                } else if (method === 'bca_va') {
                    bcaDetails.classList.add('show');
                } else if (method === 'mandiri_va') {
                    mandiriDetails.classList.add('show');
                } else if (method === 'bni_va') {
                    bniDetails.classList.add('show');
                } else if (method === 'bri_va') {
                    briDetails.classList.add('show');
                } else if (method === 'manual_transfer') {
                    manualTransferDetails.classList.add('show');
                }
            }

            // Initialize with selected method
            const initialMethod = document.querySelector('input[name="payment_method"]:checked');
            if (initialMethod) {
                showPaymentDetails(initialMethod.value);
            }

            // Add change listeners to payment options
            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    showPaymentDetails(this.value);
                });
            });

            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Hide previous messages
                    errorMessage.classList.remove('show');
                    successMessage.classList.remove('show');

                    // Validate payment method
                    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (!selectedMethod) {
                        errorText.textContent = 'Pilih metode pembayaran terlebih dahulu!';
                        errorMessage.classList.add('show');
                        return;
                    }

                    // Validate transaction ID
                    const transactionId = document.querySelector('input[name="transaction_id"]');
                    if (!transactionId || !transactionId.value) {
                        errorText.textContent = 'Data transaksi tidak valid! Silakan refresh halaman.';
                        errorMessage.classList.add('show');
                        return;
                    }

                    // Show loading state
                    const buttonText = document.getElementById('buttonText');
                    const originalText = buttonText.innerHTML;
                    buttonText.innerHTML = '<span class="loading"></span> Memproses Pembayaran...';
                    payButton.disabled = true;

                    // Submit form via AJAX
                    const formData = new FormData(paymentForm);

                    fetch(paymentForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || `HTTP ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Payment response:', data);

                        if (data.success) {
                            if (data.status === 'success') {
                                // Payment successful - membership immediately active
                                successMessage.classList.add('show');
                                buttonText.innerHTML = '<i class="fas fa-check-circle"></i> Pembayaran Berhasil!';

                                setTimeout(() => {
                                    window.location.href = '{{ route("customer.membership") }}';
                                }, 2000);
                            } else if (data.status === 'pending') {
                                // Payment created - show payment instructions
                                showPaymentInstructions(data.payment_data, selectedMethod.value);
                                buttonText.innerHTML = originalText;
                                payButton.disabled = false;
                            }
                        } else {
                            throw new Error(data.message || data.error || 'Gagal memproses pembayaran');
                        }
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                        buttonText.innerHTML = originalText;
                        payButton.disabled = false;
                        errorText.textContent = error.message || 'Gagal memproses pembayaran. Silakan coba lagi.';
                        errorMessage.classList.add('show');
                    });
                });
            }

            // Function to show payment instructions
            function showPaymentInstructions(paymentData, method) {
                // Create a modal to show payment instructions
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                `;

                let content = '';

                if (method === 'qris') {
                    content = `
                        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                            <h2 style="text-align: center; margin-bottom: 20px; color: #111;">Scan QR Code untuk Pembayaran</h2>
                            <div style="text-align: center; margin: 20px 0;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(paymentData.qr_code || 'SS-MEMBERSHIP-' + paymentData.transaction_id + '-Rp' + paymentData.amount)}"
                                     alt="QR Code" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 10px; background: white; max-width: 250px;">
                            </div>
                            <p style="text-align: center; font-size: 14px; color: #6b7280; margin: 15px 0;">
                                <strong>Jumlah:</strong> Rp ${new Intl.NumberFormat('id-ID').format(paymentData.amount)}<br>
                                <strong>Referensi:</strong> ${paymentData.transaction_id}<br>
                                <strong>Berlaku hingga:</strong> ${new Date(paymentData.expiry_time).toLocaleString('id-ID')}
                            </p>
                            <p style="font-size: 13px; color: #666; line-height: 1.6;">
                                Silakan scan QR Code di atas menggunakan aplikasi e-wallet atau mobile banking Anda untuk melakukan pembayaran.
                            </p>
                            <button onclick="this.parentElement.parentElement.remove()" style="width: 100%; background: #FF6B2C; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 20px;">
                                Tutup
                            </button>
                        </div>
                    `;
                } else if (['bca_va', 'mandiri_va', 'bni_va', 'bri_va'].includes(method)) {
                    const bankName = paymentData.va_bank;
                    content = `
                        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                            <h2 style="text-align: center; margin-bottom: 20px; color: #111;">Virtual Account Transfer</h2>
                            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin: 15px 0;">
                                <p style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Nomor Virtual Account</p>
                                <p style="font-size: 20px; font-weight: 700; color: #FF6B2C; text-align: center; word-break: break-all;">
                                    ${paymentData.va_number}
                                </p>
                            </div>
                            <p style="text-align: center; font-size: 14px; color: #6b7280; margin: 15px 0;">
                                <strong>Bank:</strong> ${bankName}<br>
                                <strong>Atas Nama:</strong> SMART SHUTTLE<br>
                                <strong>Jumlah:</strong> Rp ${new Intl.NumberFormat('id-ID').format(paymentData.amount)}<br>
                                <strong>Berlaku hingga:</strong> ${new Date(paymentData.expiry_time).toLocaleString('id-ID')}
                            </p>
                            <p style="font-size: 13px; color: #666; line-height: 1.6; background: #fff7f3; padding: 12px; border-radius: 6px; border-left: 3px solid #FF6B2C;">
                                <strong>Instruksi Transfer:</strong><br>
                                1. Buka aplikasi mobile banking Anda<br>
                                2. Pilih menu Transfer antar bank<br>
                                3. Masukkan nomor Virtual Account di atas<br>
                                4. Masukkan jumlah yang sesuai dengan nominal<br>
                                5. Selesaikan transaksi
                            </p>
                            <button onclick="this.parentElement.parentElement.remove()" style="width: 100%; background: #FF6B2C; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 20px;">
                                Tutup
                            </button>
                        </div>
                    `;
                } else {
                    content = `
                        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                            <h2 style="text-align: center; margin-bottom: 20px; color: #111;">Pembayaran Dikirim</h2>
                            <p style="text-align: center; font-size: 14px; color: #6b7280; margin: 15px 0;">
                                <strong>Referensi:</strong> ${paymentData.transaction_id}<br>
                                <strong>Jumlah:</strong> Rp ${new Intl.NumberFormat('id-ID').format(paymentData.amount)}
                            </p>
                            <p style="font-size: 13px; color: #666; line-height: 1.6;">
                                Pembayaran Anda telah diterima dan sedang menunggu verifikasi dari admin.
                                Status membership akan otomatis aktif setelah pembayaran diverifikasi.
                            </p>
                            <button onclick="this.parentElement.parentElement.remove()" style="width: 100%; background: #FF6B2C; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 20px;">
                                Tutup
                            </button>
                        </div>
                    `;
                }

                modal.innerHTML = content;
                modal.onclick = function(e) {
                    if (e.target === modal) {
                        modal.remove();
                    }
                };
                document.body.appendChild(modal);
            }

            // Check if there are any validation errors from server
            @if($errors->any())
                errorText.textContent = '{{ $errors->first() }}';
                errorMessage.classList.add('show');
            @endif

            // Check if there's a success message from server
            @if(session('success'))
                successMessage.classList.add('show');
                setTimeout(() => {
                    successMessage.classList.remove('show');
                }, 5000);
            @endif
        });
    </script>
</body>
</html>
