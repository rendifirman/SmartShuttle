{{-- FILE: resources/views/payment/index.blade.php --}}
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
        --danger:#DC3545;
        --danger-light:#F8D7DA;
        --warning:#FFC107;
        --warning-light:#FFF3CD;
        --blue-dark:#0f2942;
        --white:#FFFFFF;
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

    .container-single{
        max-width:800px;
        margin:0 auto;
        padding:0 30px;
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

    /* ===== STATUS PEMBAYARAN ===== */
    .payment-status-section {
        margin-bottom: 20px;
        width: 100%;
    }

    .payment-status-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .payment-status-badge {
        display: inline-block;
        background: var(--danger-light);
        border: 1px solid var(--danger);
        color: var(--danger);
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
    }

    /* ===== DEADLINE CARD ===== */
    .deadline-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .deadline-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
    }

    .deadline-date {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }

    .deadline-time {
        background: var(--primary-soft);
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        color: var(--dark);
    }

    /* ===== VEHICLE CARD ===== */
    .vehicle-detail-card{
        display:flex;
        gap:24px;
        background:var(--primary-light);
        border:2px solid var(--primary-soft);
        border-radius:16px;
        padding:20px;
        margin-bottom:24px;
        width:100%;
        align-items:center;
    }

    .vehicle-image{
        width:130px;
        height:130px;
        border-radius:10px;
        background:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        flex-shrink:0;
        border:1px solid var(--border);
    }

    .vehicle-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .vehicle-info-compact{
        flex:1;
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    .vehicle-name-compact{
        font-size:22px;
        font-weight:700;
        color:var(--dark);
        margin-bottom:12px;
    }

    .vehicle-specs{
        display:flex;
        flex-wrap:wrap;
        gap:20px;
    }

    .spec-item{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:14px;
        color:#555;
        background:white;
        padding:6px 14px;
        border-radius:30px;
        border:1px solid var(--border);
    }

    .spec-item i{
        color:var(--primary);
        font-size:14px;
    }

    /* ===== DETAIL GRID ===== */
    .detail-grid{
        display:grid;
        grid-template-columns:repeat(2, 1fr);
        gap:16px;
        margin-bottom:28px;
        width:100%;
    }

    .detail-item{
        background:#f9f9f9;
        border:1px solid var(--border);
        border-radius:10px;
        padding:14px 18px;
    }

    .detail-label{
        font-size:12px;
        color:#666;
        margin-bottom:6px;
        text-transform:uppercase;
        letter-spacing:0.3px;
    }

    .detail-value{
        font-size:16px;
        font-weight:600;
        color:var(--dark);
    }

    /* ===== CUSTOMER DATA SPLIT - HANYA JUDUL BIRU ===== */
    .customer-split{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
        margin-bottom:28px;
        width:100%;
    }

    .customer-info-box, .order-info-box{
        background:var(--white);
        border:1px solid var(--border);
        border-radius:12px;
        padding:0;
        overflow:hidden;
    }

    .box-header{
        background:var(--blue-dark);
        padding:14px 20px;
        display:flex;
        align-items:center;
        gap:10px;
    }

    .box-header i{
        color:var(--primary);
        font-size:16px;
    }

    .box-header span{
        font-size:15px;
        font-weight:600;
        color:var(--white);
    }

    .box-content{
        padding:18px 20px;
    }

    .customer-row{
        display:flex;
        margin-bottom:12px;
    }

    .customer-row:last-child{
        margin-bottom:0;
    }

    .customer-label{
        width:100px;
        font-size:13px;
        color:#666;
    }

    .customer-value{
        flex:1;
        font-size:14px;
        font-weight:500;
        color:var(--dark);
    }

    .order-id-highlight{
        background:var(--primary-soft);
        padding:8px 14px;
        border-radius:8px;
        font-weight:700;
        color:var(--dark);
        font-size:15px;
        letter-spacing:0.5px;
        display:inline-block;
        margin-bottom:12px;
    }

    .order-time{
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:16px;
        color:#555;
        font-size:14px;
    }

    .order-time i{
        color:var(--primary);
        font-size:14px;
    }

    .order-status-badge{
        display:inline-block;
        padding:6px 16px;
        background:var(--warning-light);
        color:#856404;
        border:1px solid var(--warning);
        border-radius:20px;
        font-size:13px;
        font-weight:600;
    }

    @media(max-width:768px){
        .customer-split{
            grid-template-columns:1fr;
        }
        .vehicle-detail-card{
            flex-direction:column;
            text-align:center;
        }
        .vehicle-image{
            width:100%;
            height:180px;
        }
        .vehicle-specs{
            justify-content:center;
        }
        .container{
            grid-template-columns:1fr;
        }
        .deadline-card{
            flex-direction:column;
            align-items:flex-start;
            gap:12px;
        }
    }

    /* ===== PRICE BOX ===== */
    .price-box{
        background:var(--primary-soft);
        border-radius:14px;
        padding:22px;
        margin-top:16px;
        width:100%;
    }

    .price-row{
        display:flex;
        justify-content:space-between;
        margin-bottom:12px;
        font-size:14px;
        width:100%;
    }

    .price-divider{
        height:1px;
        background:var(--primary);
        margin:16px 0;
        width:100%;
        opacity:0.3;
    }

    .total{
        display:flex;
        justify-content:space-between;
        font-weight:800;
        color:var(--primary);
        font-size:18px;
        width:100%;
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

    /* ===== ALERT SUCCESS ===== */
    .alert-success-custom {
        background: var(--success-light);
        border: 2px solid var(--success);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
        text-align: center;
    }

    .success-icon {
        width: 70px;
        height: 70px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 32px;
    }

    .success-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 8px;
    }

    .success-message {
        font-size: 16px;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .success-submessage {
        font-size: 14px;
        color: #666;
    }

    /* ===== INFO PENTING ===== */
    .important-info {
        background: var(--info-light);
        border-left: 5px solid var(--info);
        border-radius: 10px;
        padding: 20px;
        margin-top: 28px;
    }

    .important-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--info);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .important-list {
        margin: 0;
        padding-left: 20px;
        font-size: 14px;
        color: var(--info);
        line-height: 1.6;
    }

    .important-list li {
        margin-bottom: 8px;
    }

    /* ===== METODE PEMBAYARAN ===== */
    .payment-methods {
        width: 100%;
        margin-bottom: 24px;
    }

    .payment-method-card {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        width: 100%;
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
        width: 100%;
    }
    
    .method-info {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        flex: 1;
    }
    
    .method-info i {
        color: var(--primary);
        font-size: 24px;
        margin-top: 2px;
    }
    
    .method-text {
        flex: 1;
    }
    
    .method-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 16px;
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
    
    .method-badge {
        display: inline-block;
        padding: 4px 10px;
        background: var(--primary-soft);
        color: var(--dark);
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }
    
    /* ===== DETAIL PEMBAYARAN ===== */
    .payment-detail {
        display: none;
        margin-top: 24px;
        padding: 22px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid var(--border);
        width: 100%;
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
        margin-bottom: 18px;
        width: 100%;
    }
    
    .payment-info-box {
        background: white;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        width: 100%;
    }
    
    .info-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .qris-container {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        border: 1px solid var(--border);
        margin-bottom: 16px;
    }
    
    .qris-code {
        width: 200px;
        height: 200px;
        margin: 0 auto 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 15px;
        background: white;
    }
    
    .instructions-box {
        background: var(--info-light);
        border-radius: 10px;
        padding: 18px;
        margin-top: 16px;
        border-left: 4px solid var(--info);
    }
    
    .instructions-title {
        font-weight: 600;
        color: var(--info);
        margin-bottom: 12px;
        font-size: 14px;
    }
    
    .instructions-list {
        font-size: 13px;
        color: var(--info);
        line-height: 1.6;
        padding-left: 20px;
        margin: 0;
    }
    
    /* ===== BUTTON ===== */
    .btn-confirm {
        width: 100%;
        padding: 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 24px;
    }
    
    .btn-confirm:hover {
        background: #ff581e;
        box-shadow: 0 4px 12px rgba(255, 107, 44, 0.3);
        transform: translateY(-2px);
    }

    .btn-back {
        display: inline-block;
        padding: 12px 24px;
        background: var(--dark);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: var(--dark-light);
    }

    .copy-link {
        font-size: 12px;
        color: var(--primary);
        cursor: pointer;
        margin-top: 8px;
        display: inline-block;
    }
    
    .copy-link i {
        margin-right: 4px;
    }

    .payment-method-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }

    .payment-method-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
    }

    .payment-method-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media(max-width:900px){
        .container{
            grid-template-columns:1fr;
            padding: 0 15px;
        }
        .container-single{
            padding: 0 15px;
        }
        .page-wrap {
            padding: 80px 0 40px;
        }
        .qris-code {
            width: 180px;
            height: 180px;
        }
    }
    
    @media(max-width: 480px) {
        .card {
            padding: 20px 16px;
        }
        h2 {
            font-size: 18px;
        }
        .vehicle-name-compact {
            font-size: 20px;
        }
        .detail-grid {
            grid-template-columns:1fr;
        }
    }
</style>
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="page-wrap">
    <div class="container">
        {{-- LEFT - DETAIL PESANAN --}}
        <div class="card">
            {{-- STATUS PEMBAYARAN --}}
            <div class="payment-status-section">
                <div class="payment-status-label">STATUS PEMBAYARAN</div>
                <div class="payment-status-badge">
                    <i class="fas fa-clock" style="margin-right:6px;"></i> Menunggu Pembayaran
                </div>
            </div>
            
            {{-- BATAS WAKTU PEMBAYARAN - CARD PUTIH --}}
            <div class="deadline-card">
                <div>
                    <div class="deadline-label">Batas waktu pembayaran</div>
                    <div class="deadline-date">{{ isset($payment_deadline) ? date('d/m/Y', strtotime($payment_deadline)) : '12/02/2026' }}</div>
                </div>
                <div>
                    <div class="deadline-time" id="deadline-timer">
                        {{ isset($payment_deadline) ? date('H:i', strtotime($payment_deadline)) : '23:59' }} WIB
                    </div>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <h2>DETAIL PESANAN</h2>
            <div class="divider"></div>

            {{-- VEHICLE CARD DENGAN FOTO --}}
            <div class="vehicle-detail-card">
                <div class="vehicle-image">
                    @if(isset($vehicle) && isset($vehicle['image']))
                        <img src="{{ $vehicle['image'] }}" alt="{{ $vehicle['name'] }}">
                    @else
                        <img src="/images/vehicles/shuttle-bus.jpg" alt="Shuttle Bus" style="background:#f5f5f5; display:flex; align-items:center; justify-content:center; color:#999;">
                    @endif
                </div>
                <div class="vehicle-info-compact">
                    <div class="vehicle-name-compact">{{ isset($vehicle) && $vehicle ? $vehicle['name'] : 'Toyota Hiace Commuter' }}</div>
                    <div class="vehicle-specs">
                        <div class="spec-item">
                            <i class="fas fa-shuttle-van"></i> {{ isset($vehicle) && $vehicle ? ($vehicle['type'] ?? 'Shuttle') : 'Shuttle' }}
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-users"></i> 6-8 Orang
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-suitcase"></i> 4 Bagasi
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAIL GRID KOTAK KECIL --}}
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Kota</div>
                    <div class="detail-value">{{ isset($customerData['city']) ? ucfirst($customerData['city']) : 'Jakarta' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tanggal sewa</div>
                    <div class="detail-value">{{ isset($rentDate) ? date('d/m/Y', strtotime($rentDate)) : '12/02/2026' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Durasi sewa</div>
                    <div class="detail-value">{{ isset($duration) ? $duration : 1 }} Hari</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Layanan</div>
                    <div class="detail-value">{{ (isset($service) && $service == 'self-drive') || (isset($customerData['service_type']) && $customerData['service_type'] == 'self-drive') ? 'Lepas Kunci' : 'Dengan Sopir' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tipe Kendaraan</div>
                    <div class="detail-value">{{ isset($vehicle) && $vehicle ? ($vehicle['type'] ?? 'Shuttle') : 'Shuttle' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Harga per hari</div>
                    <div class="detail-value">Rp {{ isset($vehicle) && $vehicle ? number_format($vehicle['price'], 0, ',', '.') : '1.200.000' }}</div>
                </div>
            </div>

            {{-- DATA PEMESAN SPLIT 2 KOTAK - HANYA HEADER BIRU --}}
            <h2 style="margin-top:16px;">DATA PEMESAN</h2>
            <div class="divider"></div>

            <div class="customer-split">
                {{-- KOTAK KIRI: INFORMASI PEMESAN --}}
                <div class="customer-info-box">
                    <div class="box-header">
                        <i class="fas fa-user"></i>
                        <span>Informasi Pemesan</span>
                    </div>
                    <div class="box-content">
                        <div class="customer-row">
                            <div class="customer-label">Nama</div>
                            <div class="customer-value">{{ isset($customerData['full_name']) ? $customerData['full_name'] : 'Haryantie Chinta Dewi' }}</div>
                        </div>
                        <div class="customer-row">
                            <div class="customer-label">No. Telepon</div>
                            <div class="customer-value">{{ isset($customerData['phone']) ? $customerData['phone'] : '089653456787' }}</div>
                        </div>
                        <div class="customer-row">
                            <div class="customer-label">Email</div>
                            <div class="customer-value">{{ isset($customerData['email']) ? $customerData['email'] : 'haryantiechintadewi@gmail.com' }}</div>
                        </div>
                    </div>
                </div>

                {{-- KOTAK KANAN: DETAIL PESANAN --}}
                <div class="order-info-box">
                    <div class="box-header">
                        <i class="fas fa-file-alt"></i>
                        <span>Detail Pesanan</span>
                    </div>
                    <div class="box-content">
                        <div style="margin-bottom:16px;">
                            <div style="font-size:12px; color:#666; margin-bottom:6px;">ID Pesanan</div>
                            <div class="order-id-highlight">{{ isset($order_number) ? $order_number : 'SR2026021230D448' }}</div>
                        </div>
                        <div class="order-time">
                            <i class="fas fa-clock"></i> Waktu Pesan: {{ isset($order_time) ? date('d/m/Y H:i', strtotime($order_time)) : '12/02/2026 14:23' }} WIB
                        </div>
                        <div>
                            <div style="font-size:12px; color:#666; margin-bottom:6px;">Status</div>
                            <span class="order-status-badge"><i class="fas fa-hourglass-half" style="margin-right:6px;"></i> Menunggu Pembayaran</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOTAL HARGA --}}
            <div class="price-box">
                <div class="price-row">
                    <span>Harga Sewa / Hari</span>
                    <span>Rp {{ isset($vehicle) && $vehicle ? number_format($vehicle['price'], 0, ',', '.') : '1.200.000' }}</span>
                </div>
                <div class="price-row">
                    <span>Durasi Sewa</span>
                    <span>{{ isset($duration) ? $duration : 1 }} Hari</span>
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
                    <span>Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '1.350.000' }}</span>
                </div>
            </div>
        </div>

        {{-- RIGHT - METODE PEMBAYARAN --}}
        <div class="card">
            <h2 class="section-title">METODE PEMBAYARAN</h2>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle" style="margin-right:8px;"></i> Pilih metode pembayaran yang Anda inginkan, kemudian klik "Konfirmasi Pembayaran"
            </div>

            <form action="{{ route('smartrent.process.payment') }}" method="POST" id="paymentForm">
                @csrf
                <input type="hidden" name="order_id" value="{{ isset($order_id) ? $order_id : '' }}">
                <input type="hidden" name="order_number" value="{{ isset($order_number) ? $order_number : 'SR2026021230D448' }}">
                <input type="hidden" name="total_price" value="{{ isset($totalPrice) ? $totalPrice : 1350000 }}">
                
                @if(isset($vehicleId))
                    <input type="hidden" name="vehicle_id" value="{{ $vehicleId }}">
                @endif
                @if(isset($vehicle))
                    <input type="hidden" name="vehicle_name" value="{{ $vehicle['name'] ?? 'Toyota Hiace Commuter' }}">
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

                {{-- METODE PEMBAYARAN --}}
                <div class="payment-methods">
                    {{-- QRIS --}}
                    <div class="payment-method-card" data-method="qris">
                        <input type="radio" name="payment_method" value="QRIS" id="qris" required>
                        <div class="method-header">
                            <div class="method-info">
                                <i class="fas fa-qrcode"></i>
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
                        <input type="radio" name="payment_method" value="BCA Virtual Account" id="bca_va">
                        <div class="method-header">
                            <div class="method-info">
                                <i class="fas fa-university"></i>
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
                        <input type="radio" name="payment_method" value="Mandiri Virtual Account" id="mandiri_va">
                        <div class="method-header">
                            <div class="method-info">
                                <i class="fas fa-university"></i>
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
                                <div style="width:100%; height:100%; background:#f8f8f8; display:flex; align-items:center; justify-content:center; border-radius:6px; flex-direction:column;">
                                    <i class="fas fa-qrcode" style="font-size:80px; color:#ddd;"></i>
                                    <span style="color:#999; margin-top:10px; font-size:12px;">QR Code</span>
                                </div>
                            </div>
                            <div class="info-label">Scan kode QR di atas untuk melakukan pembayaran</div>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Total Pembayaran</div>
                            <div class="info-value">Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '1.350.000' }}</div>
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
                            <span class="copy-link" onclick="copyVANumber(this)">
                                <i class="fas fa-copy"></i> Salin Nomor VA
                            </span>
                        </div>
                        
                        <div class="payment-info-box">
                            <div class="info-label">Total Pembayaran</div>
                            <div class="info-value">Rp {{ isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : '1.350.000' }}</div>
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
                    <i class="fas fa-check-circle" style="margin-right:8px;"></i> Konfirmasi Pembayaran
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

    // Fungsi copy VA Number
    window.copyVANumber = function(element) {
        const vaNumber = document.getElementById('va-number').textContent;
        navigator.clipboard.writeText(vaNumber).then(() => {
            const originalHTML = element.innerHTML;
            element.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
            element.style.color = 'var(--success)';
            
            setTimeout(() => {
                element.innerHTML = '<i class="fas fa-copy"></i> Salin Nomor VA';
                element.style.color = 'var(--primary)';
            }, 2000);
        });
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
        
        // Reset semua pilihan
        function resetPaymentDetail() {
            paymentDetail.style.display = 'none';
            qrisSection.style.display = 'none';
            vaSection.style.display = 'none';
            methodCards.forEach(card => {
                card.classList.remove('selected');
            });
        }
        
        // Tampilkan detail pembayaran
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
        
        // Event listener metode pembayaran
        methodCards.forEach(card => {
            card.addEventListener('click', function() {
                const method = this.dataset.method;
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                showPaymentDetail(method);
            });
        });
        
        // Auto select metode pertama
        if (methodCards.length > 0) {
            const firstMethod = methodCards[0].dataset.method;
            const firstRadio = methodCards[0].querySelector('input[type="radio"]');
            firstRadio.checked = true;
            showPaymentDetail(firstMethod);
        }
        
        // Validasi form
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!paymentMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return false;
            }
            
            // Konfirmasi sebelum submit
            return confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran?');
        });

        // Countdown timer
        function updateDeadlineTimer() {
            const deadlineTimer = document.getElementById('deadline-timer');
            if (!deadlineTimer) return;
            
            // Set deadline 1 jam dari sekarang
            const now = new Date();
            const deadline = new Date(now.getTime() + (60 * 60 * 1000));
            
            function timer() {
                const currentTime = new Date().getTime();
                const deadlineTime = deadline.getTime();
                const distance = deadlineTime - currentTime;
                
                if (distance < 0) {
                    deadlineTimer.innerHTML = 'Waktu habis';
                    deadlineTimer.style.background = 'var(--danger-light)';
                    deadlineTimer.style.color = 'var(--danger)';
                    return;
                }
                
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                const timeString = `${deadline.getHours().toString().padStart(2,'0')}:${deadline.getMinutes().toString().padStart(2,'0')} WIB (sisa ${hours}j ${minutes}m ${seconds}d)`;
                deadlineTimer.innerHTML = timeString;
            }
            
            timer();
            setInterval(timer, 1000);
        }
        
        updateDeadlineTimer();
    });
</script>
@endpush
@endsection