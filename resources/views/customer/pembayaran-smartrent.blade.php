@extends('layouts.app')

@section('title', 'Pembayaran SmartRent - SmartRent')

@push('styles')
<style>
    :root{
        --primary:#FF6B2C;
        --primary-soft:#FFE1D5;
        --primary-light:#FFF0E9;
        --dark:#1E3A5F;
        --dark-light:#2D4A7A;
        --border:#E5E5E5;
        --bg:#F2F2F2;
        --success:#1E9E4A;
        --success-light:#E7F7EC;
        --info:#0c5460;
        --info-light:#d1ecf1;
    }

    *{box-sizing:border-box;font-family:'Segoe UI',sans-serif}

    .page-wrap{
        background:var(--bg);
        padding:100px 0 80px;
        min-height: 100vh;
    }

    .container{
        max-width:100%;
        width: 100%;
        margin:auto;
        display:grid;
        grid-template-columns:2fr 1fr;
        gap:24px;
        padding: 0 30px;
    }

    .card{
        background:#fff;
        border-radius:12px;
        padding:28px;
        box-shadow:0 6px 18px rgba(0,0,0,.08);
        width: 100%;
    }

    h2{
        font-size:20px;
        font-weight:700;
        color:var(--dark);
        margin-bottom:12px;
        width: 100%;
    }

    .divider{
        height:2px;
        background:var(--primary);
        margin-bottom:20px;
        width: 100%;
    }

    /* ===== DETAIL PESANAN ===== */
    .car-name{
        background:var(--primary-soft);
        border:2px solid var(--primary);
        color:var(--dark);
        padding:14px;
        text-align:center;
        font-weight:700;
        border-radius:10px;
        margin-bottom:18px;
        font-size: 18px;
        width: 100%;
    }

    .detail-row{
        display:flex;
        justify-content:space-between;
        padding:8px 0;
        border-bottom:1px solid var(--border);
        font-size:14px;
        width: 100%;
    }

    .detail-row span:first-child{
        color:#666;
    }

    .detail-row span:last-child{
        font-weight:600;
        color: var(--dark);
    }

    /* ===== TABLE ===== */
    table{
        width:100%;
        border-collapse:collapse;
        margin-top:14px;
    }

    th,td{
        border:1px solid #ddd;
        padding:10px;
        font-size:13px;
        text-align:center;
    }

    th{
        font-weight:700;
        background: var(--primary-soft);
    }

    /* ===== PRICE BOX ===== */
    .price-box{
        background:var(--primary-soft);
        border-radius:12px;
        padding:20px;
        margin-top:28px;
        width: 100%;
    }

    .price-row{
        display:flex;
        justify-content:space-between;
        margin-bottom:10px;
        font-size:14px;
        width: 100%;
    }

    .price-row.discount span:last-child{
        color:#1E9E4A;
    }

    .price-divider{
        height:1px;
        background:var(--primary);
        margin:14px 0;
        width: 100%;
    }

    .total{
        display:flex;
        justify-content:space-between;
        font-weight:800;
        color:var(--primary);
        font-size:16px;
        width: 100%;
    }

    /* ===== PEMBAYARAN ===== */
    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-soft);
        width: 100%;
    }

    .alert {
        padding: 15px 18px;
        border-radius: 8px;
        margin-bottom: 24px;
        width: 100%;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .alert-info {
        background: var(--info-light);
        color: var(--info);
        border: 1px solid #bee5eb;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* ===== METODE PEMBAYARAN STYLES ===== */
    .payment-methods {
        width: 100%;
        margin-bottom: 24px;
    }

    .payment-method-card {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }
    
    .payment-method-card:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    
    .payment-method-card.selected {
        border-color: var(--primary);
        background: var(--primary-light);
        box-shadow: 0 4px 12px rgba(255, 107, 44, 0.15);
    }
    
    .method-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        width: 100%;
    }
    
    .method-info {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        flex: 1;
    }
    
    .method-text {
        flex: 1;
    }
    
    .method-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
        margin-bottom: 4px;
    }
    
    .method-desc {
        font-size: 13px;
        color: #666;
        line-height: 1.4;
    }
    
    .method-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        flex-shrink: 0;
    }
    
    .method-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary);
        transform: scale(0);
        transition: transform 0.3s;
    }
    
    .payment-method-card.selected .method-radio::after {
        transform: scale(1);
    }
    
    .payment-method-card.selected .method-radio {
        border-color: var(--primary);
    }
    
    input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    /* ===== DETAIL PEMBAYARAN ===== */
    .payment-detail {
        display: none;
        margin-top: 24px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid var(--border);
        width: 100%;
        box-sizing: border-box;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .detail-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 16px;
        width: 100%;
    }
    
    .detail-content {
        width: 100%;
    }
    
    .payment-info-box {
        background: white;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        width: 100%;
        box-sizing: border-box;
    }
    
    .info-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .qris-container {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--border);
        margin-bottom: 16px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .qris-code {
        width: 200px;
        height: 200px;
        margin: 0 auto 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 15px;
        background: white;
    }
    
    .instructions-box {
        background: var(--info-light);
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
        border-left: 4px solid var(--info);
        width: 100%;
        box-sizing: border-box;
    }
    
    .instructions-title {
        font-weight: 600;
        color: var(--info);
        margin-bottom: 10px;
        font-size: 14px;
    }
    
    .instructions-list {
        font-size: 13px;
        color: var(--info);
        line-height: 1.5;
        padding-left: 18px;
        margin: 0;
    }
    
    .instructions-list li {
        margin-bottom: 6px;
    }
    
    /* ===== BUTTON ===== */
    .btn-confirm {
        width: 100%;
        padding: 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 20px;
    }
    
    .btn-confirm:hover {
        background: #ff581e;
        box-shadow: 0 4px 12px rgba(255, 107, 44, 0.3);
    }
    
    /* ===== ORDER NUMBER ===== */
    .order-number {
        text-align: center;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary-light), #fff);
        border-radius: 10px;
        margin-bottom: 24px;
        font-weight: 600;
        color: var(--dark);
        border: 1px solid var(--primary-soft);
        width: 100%;
        box-sizing: border-box;
    }
    
    .order-number span {
        color: var(--primary);
        font-size: 16px;
        letter-spacing: 0.5px;
    }

    /* ===== METHOD BADGE ===== */
    .method-badge {
        display: inline-block;
        padding: 4px 10px;
        background: var(--primary-soft);
        color: var(--dark);
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
    }

    @media(max-width:900px){
        .container{
            grid-template-columns:1fr;
            padding: 0 15px;
        }
        
        .page-wrap {
            padding: 80px 0 40px;
        }
        
        .method-info {
            gap: 10px;
        }
        
        .method-name {
            font-size: 14px;
        }
        
        .method-desc {
            font-size: 12px;
        }
        
        .qris-code {
            width: 180px;
            height: 180px;
        }
    }
    
    @media(max-width: 480px) {
        .card {
            padding: 20px 15px;
        }
        
        h2 {
            font-size: 18px;
        }
        
        .car-name {
            font-size: 16px;
            padding: 12px;
        }
        
        .section-title {
            font-size: 16px;
        }
        
        .payment-method-card {
            padding: 14px;
        }
        
        .method-header {
            margin-bottom: 6px;
        }
        
        .btn-confirm {
            padding: 14px;
            font-size: 15px;
        }
        
        .order-number {
            padding: 12px;
            font-size: 14px;
        }
        
        .order-number span {
            font-size: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-wrap">
    <div class="container">
        {{-- LEFT - DETAIL PESANAN --}}
        <div class="card">
            <div class="order-number">
                Nomor Pesanan: <span>{{ isset($order_number) ? $order_number : 'SR-' . date('Ymd') . '-' . strtoupper(uniqid()) }}</span>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <h2>DETAIL PESANAN</h2>
            <div class="divider"></div>

            <div class="car-name">{{ isset($vehicle) && $vehicle ? $vehicle['name'] : 'Toyota Hiace Commuter' }}</div>

            <div class="detail-row">
                <span>Kota</span>
                <span>{{ isset($customerData['city']) ? ucfirst($customerData['city']) : 'Jakarta' }}</span>
            </div>
            <div class="detail-row">
                <span>Tanggal sewa</span>
                <span>{{ isset($rentDate) ? date('d/m/Y', strtotime($rentDate)) : date('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span>Durasi sewa</span>
                <span>{{ isset($duration) ? $duration : 3 }} Hari</span>
            </div>
            <div class="detail-row">
                <span>Layanan</span>
                <span>{{ (isset($service) && $service == 'self-drive') || (isset($customerData['service_type']) && $customerData['service_type'] == 'self-drive') ? 'Lepas Kunci' : 'Dengan Sopir' }}</span>
            </div>
            <div class="detail-row">
                <span>Tipe Kendaraan</span>
                <span>{{ isset($vehicle) && $vehicle ? $vehicle['type'] : 'Minibus' }}</span>
            </div>
            <div class="detail-row">
                <span>Harga per hari</span>
                <span>Rp {{ isset($vehicle) && $vehicle ? number_format($vehicle['price'], 0, ',', '.') : '1.100.000' }}</span>
            </div>

            <h2 style="margin-top:36px;">DATA PEMESAN</h2>
            <div class="divider"></div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>{{ isset($customerData['full_name']) ? $customerData['full_name'] : 'Luna Anya' }}</td>
                        <td>{{ isset($customerData['phone']) ? $customerData['phone'] : '085892793185' }}</td>
                        <td>{{ isset($customerData['email']) ? $customerData['email'] : 'L.ayya@gmail.com' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="price-box">
                <div class="price-row">
                    <span>Harga Sewa / Hari</span>
                    <span>Rp {{ isset($vehicle) && $vehicle ? number_format($vehicle['price'], 0, ',', '.') : '1.100.000' }}</span>
                </div>
                <div class="price-row">
                    <span>Durasi Sewa</span>
                    <span>{{ isset($duration) ? $duration : 3 }} Hari</span>
                </div>
                @if((isset($service) && $service == 'with-driver') || (isset($customerData['service_type']) && $customerData['service_type'] == 'with-driver'))
                <div class="price-row">
                    <span>Biaya Driver / Hari</span>
                    <span>Rp 150.000</span>
                </div>
                @endif
                
                <div class="price-divider"></div>

                <div class="total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '3.750.000' }}</span>
                </div>
            </div>
        </div>

        {{-- RIGHT - METODE PEMBAYARAN --}}
        <div class="card">
            <h2 class="section-title">METODE PEMBAYARAN</h2>
            
            <div class="alert alert-info">
                Pilih metode pembayaran yang Anda inginkan, kemudian klik "Konfirmasi Pembayaran"
            </div>

            <form action="{{ route('smartrent.payment') }}" method="POST" id="paymentForm">
                @csrf
                <input type="hidden" name="order_id" value="{{ isset($order_id) ? $order_id : '' }}">
                
                @if(isset($vehicleId))
                    <input type="hidden" name="vehicle_id" value="{{ $vehicleId }}">
                @endif
                @if(isset($customerData['full_name']))
                    <input type="hidden" name="full_name" value="{{ $customerData['full_name'] }}">
                @endif
                @if(isset($customerData['phone']))
                    <input type="hidden" name="phone" value="{{ $customerData['phone'] }}">
                @endif
                @if(isset($customerData['email']))
                    <input type="hidden" name="email" value="{{ $customerData['email'] }}">
                @endif
                @if(isset($customerData['pickup_address']))
                    <input type="hidden" name="pickup_address" value="{{ $customerData['pickup_address'] }}">
                @endif
                @if(isset($rentDate))
                    <input type="hidden" name="rent_date" value="{{ $rentDate }}">
                @endif
                @if(isset($duration))
                    <input type="hidden" name="duration" value="{{ $duration }}">
                @endif
                @if(isset($customerData['city']))
                    <input type="hidden" name="city" value="{{ $customerData['city'] }}">
                @endif
                @if(isset($customerData['service_type']))
                    <input type="hidden" name="service_type" value="{{ $customerData['service_type'] }}">
                @endif
                @if(isset($customerData['notes']))
                    <input type="hidden" name="notes" value="{{ $customerData['notes'] }}">
                @endif

                {{-- METODE PEMBAYARAN --}}
                <div class="payment-methods">
                    {{-- QRIS --}}
                    <div class="payment-method-card" data-method="qris">
                        <input type="radio" name="payment_method" value="qris" id="qris" required>
                        <div class="method-header">
                            <div class="method-info">
                                <div class="method-text">
                                    <div class="method-name">QRIS</div>
                                    <div class="method-desc">Scan QR Code untuk pembayaran cepat dan mudah</div>
                                    <span class="method-badge">Metode Populer</span>
                                </div>
                            </div>
                            <div class="method-radio"></div>
                        </div>
                    </div>

                    {{-- BCA Virtual Account --}}
                    <div class="payment-method-card" data-method="bca_va">
                        <input type="radio" name="payment_method" value="bca_va" id="bca_va">
                        <div class="method-header">
                            <div class="method-info">
                                <div class="method-text">
                                    <div class="method-name">BCA Virtual Account</div>
                                    <div class="method-desc">Transfer melalui BCA Virtual Account</div>
                                    <span class="method-badge">Bank Transfer</span>
                                </div>
                            </div>
                            <div class="method-radio"></div>
                        </div>
                    </div>

                    {{-- Mandiri Virtual Account --}}
                    <div class="payment-method-card" data-method="mandiri_va">
                        <input type="radio" name="payment_method" value="mandiri_va" id="mandiri_va">
                        <div class="method-header">
                            <div class="method-info">
                                <div class="method-text">
                                    <div class="method-name">Mandiri Virtual Account</div>
                                    <div class="method-desc">Transfer melalui Mandiri Virtual Account</div>
                                    <span class="method-badge">Bank Transfer</span>
                                </div>
                            </div>
                            <div class="method-radio"></div>
                        </div>
                    </div>
                </div>

                {{-- DETAIL PEMBAYARAN --}}
                <div id="payment-detail" class="payment-detail">
                    {{-- QRIS --}}
                    <div id="qris-section" class="detail-content" style="display:none;">
                        <div class="detail-title">
                            Detail Pembayaran QRIS
                        </div>
                        
                        <div class="qris-container">
                            <div class="qris-code">
                                <!-- QR Code Placeholder -->
                            </div>
                            <div class="info-label">Scan kode QR di atas untuk melakukan pembayaran</div>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Total Pembayaran</div>
                            <div class="info-value">Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '3.750.000' }}</div>
                        </div>
                        
                        <div class="instructions-box">
                            <div class="instructions-title">Cara Pembayaran:</div>
                            <ol class="instructions-list">
                                <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                <li>Pilih menu Scan QR atau QRIS</li>
                                <li>Arahkan kamera ke kode QR di atas</li>
                                <li>Konfirmasi nominal pembayaran yang muncul</li>
                                <li>Selesaikan pembayaran dan simpan bukti transaksi</li>
                            </ol>
                        </div>
                    </div>

                    {{-- Virtual Account --}}
                    <div id="va-section" class="detail-content" style="display:none;">
                        <div class="detail-title">
                            <span id="va-title">Detail Virtual Account</span>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Nomor Virtual Account</div>
                            <div class="info-value" id="va-number">22224312986742</div>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Nama Penerima</div>
                            <div class="info-value">SMARTRENT CAR RENTAL</div>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Total Pembayaran</div>
                            <div class="info-value">Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '3.750.000' }}</div>
                        </div>
                        
                        <div class="instructions-box">
                            <div class="instructions-title">Instruksi Pembayaran:</div>
                            <ol class="instructions-list" id="va-instructions">
                                <li>Buka aplikasi mobile banking bank yang dipilih</li>
                                <li>Pilih menu Transfer atau Pembayaran</li>
                                <li>Pilih Virtual Account atau VA Transfer</li>
                                <li>Masukkan nomor Virtual Account yang tertera</li>
                                <li>Konfirmasi dan selesaikan pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL KONFIRMASI --}}
                <button type="submit" class="btn-confirm">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Data Virtual Account untuk masing-masing bank
    const vaData = {
        'bca_va': {
            number: '22224312986742',
            title: 'BCA Virtual Account',
            instructions: [
                'Buka aplikasi MyBCA atau BCA Mobile',
                'Pilih menu Transfer',
                'Pilih Virtual Account BCA',
                'Masukkan nomor VA: 22224312986742',
                'Konfirmasi dan lakukan pembayaran sebelum waktu habis'
            ]
        },
        'mandiri_va': {
            number: '888880123456789',
            title: 'Mandiri Virtual Account',
            instructions: [
                'Buka aplikasi Livin\' by Mandiri',
                'Pilih menu Pembayaran',
                'Pilih Virtual Account',
                'Masukkan nomor VA: 888880123456789',
                'Konfirmasi dan selesaikan pembayaran'
            ]
        }
    };

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', function() {
        const methodCards = document.querySelectorAll('.payment-method-card');
        const paymentDetail = document.getElementById('payment-detail');
        const qrisSection = document.getElementById('qris-section');
        const vaSection = document.getElementById('va-section');
        const vaTitle = document.getElementById('va-title');
        const vaNumber = document.getElementById('va-number');
        const vaInstructions = document.getElementById('va-instructions');
        
        // Fungsi reset semua pilihan
        function resetPaymentDetail() {
            paymentDetail.style.display = 'none';
            qrisSection.style.display = 'none';
            vaSection.style.display = 'none';
            methodCards.forEach(card => {
                card.classList.remove('selected');
            });
        }
        
        // Fungsi untuk menampilkan detail pembayaran
        function showPaymentDetail(method) {
            resetPaymentDetail();
            
            const selectedCard = document.querySelector(`[data-method="${method}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
                paymentDetail.style.display = 'block';
                
                if (method === 'qris') {
                    qrisSection.style.display = 'block';
                } else if (vaData[method]) {
                    vaSection.style.display = 'block';
                    vaTitle.textContent = vaData[method].title;
                    vaNumber.textContent = vaData[method].number;
                    
                    // Update instruksi
                    vaInstructions.innerHTML = '';
                    vaData[method].instructions.forEach(instruction => {
                        const li = document.createElement('li');
                        li.textContent = instruction;
                        vaInstructions.appendChild(li);
                    });
                }
            }
        }
        
        // Event listener untuk setiap metode pembayaran
        methodCards.forEach(card => {
            card.addEventListener('click', function() {
                const method = this.dataset.method;
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                showPaymentDetail(method);
            });
        });
        
        // Validasi form pembayaran
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!paymentMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return false;
            }
            
            // Konfirmasi sebelum submit
            if (!confirm(`Apakah Anda yakin ingin melanjutkan pembayaran dengan metode ${paymentMethod.value === 'qris' ? 'QRIS' : 'Virtual Account'}?`)) {
                e.preventDefault();
                return false;
            }
        });
        
        // Auto select metode pertama sebagai default
        if (methodCards.length > 0) {
            const firstMethod = methodCards[0].dataset.method;
            const firstRadio = methodCards[0].querySelector('input[type="radio"]');
            firstRadio.checked = true;
            showPaymentDetail(firstMethod);
        }
        
        // Ambil data dari session storage jika ada
        const checkoutData = sessionStorage.getItem('smartrent_checkout_data');
        
        if (checkoutData) {
            try {
                const data = JSON.parse(checkoutData);
                
                // Isi form hidden jika ada
                Object.keys(data).forEach(key => {
                    const hiddenInput = document.querySelector(`input[name="${key}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = data[key];
                    }
                });
                
                // Hapus data dari session storage setelah digunakan
                sessionStorage.removeItem('smartrent_checkout_data');
            } catch (error) {
                console.error('Error parsing checkout data:', error);
            }
        }
    });
</script>
@endpush
@endsection