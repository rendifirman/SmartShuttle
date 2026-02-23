{{-- FILE: resources/views/customer/smartrent-success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - SmartRent')

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
        --white:#FFFFFF;
        --gray-light:#f8f9fa;
        --gray:#e9ecef;
    }

    *{box-sizing:border-box;font-family:'Segoe UI',sans-serif}

    .page-wrap{
        background:var(--bg);
        padding:100px 0 80px;
        min-height:100vh;
    }

    .container-single{
        max-width:800px;
        margin:0 auto;
        padding:0 30px;
    }

    .card{
        background:#fff;
        border-radius:16px;
        padding:32px;
        box-shadow:0 8px 20px rgba(0,0,0,.06);
        width:100%;
    }

    h2{
        font-size:18px;
        font-weight:700;
        color:var(--dark);
        margin-bottom:16px;
        padding-bottom:12px;
        border-bottom:2px solid var(--primary-soft);
        display:flex;
        align-items:center;
        gap:10px;
    }

    h2 i{
        color:var(--primary);
        font-size:18px;
    }

    /* ===== ALERT SUCCESS ===== */
    .alert-success-custom {
        background: var(--success-light);
        border: 2px solid var(--success);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        text-align: center;
    }

    .success-icon {
        width: 60px;
        height: 60px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 28px;
    }

    .success-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 4px;
    }

    .success-message {
        font-size: 15px;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .success-submessage {
        font-size: 13px;
        color: #666;
    }

    /* ===== DETAIL PESANAN GRID ===== */
    .detail-pesanan-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px 32px;
        margin-bottom: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #000000;
    }

    /* ===== ORDER NUMBER IN GRID ===== */
    .order-number-grid {
        display: flex;
        flex-direction: column;
    }

    .order-number-grid .detail-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .order-number-grid .detail-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    /* ===== TOTAL PEMBAYARAN ===== */
    .total-payment-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--primary-light);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .total-label {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }

    .total-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--primary);
    }

    /* ===== PAYMENT INFO CARD ===== */
    .payment-info-card {
        background: var(--info-light);
        border: 1px solid var(--info);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .payment-method-section {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .payment-icon {
        width: 52px;
        height: 52px;
        background: var(--white);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--info);
        font-size: 26px;
    }

    .payment-method-detail {
        display: flex;
        flex-direction: column;
    }

    .payment-method-label {
        font-size: 11px;
        color: var(--info);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 4px;
    }

    .payment-method-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }

    .payment-date-section {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .payment-date-icon {
        width: 52px;
        height: 52px;
        background: var(--white);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--info);
        font-size: 26px;
    }

    .payment-date-detail {
        display: flex;
        flex-direction: column;
    }

    .payment-date-label {
        font-size: 11px;
        color: var(--info);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 4px;
    }

    .payment-date-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    /* ===== CUSTOMER INFO ===== */
    .customer-info-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .customer-info-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--primary-soft);
    }

    .customer-info-header i {
        width: 32px;
        height: 32px;
        background: var(--primary-soft);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 16px;
    }

    .customer-info-header span {
        font-weight: 700;
        color: var(--dark);
        font-size: 16px;
    }

    .customer-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 30px;
    }

    .customer-info-item {
        display: flex;
        flex-direction: column;
    }

    .customer-info-item.full-width {
        grid-column: span 2;
    }

    .customer-info-label {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .customer-info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        padding: 6px 0;
        border-bottom: 1px dashed var(--border);
    }

    /* ===== IMPORTANT INFO ===== */
    .important-info {
        background: var(--warning-light);
        border-left: 5px solid var(--warning);
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
        margin-bottom: 32px;
    }

    .important-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .important-title i {
        color: var(--warning);
    }

    .important-list {
        margin: 0;
        padding-left: 0;
        list-style: none;
        font-size: 14px;
        color: var(--dark);
        line-height: 1.6;
    }

    .important-list li {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .important-list i {
        color: var(--warning);
        font-size: 14px;
        margin-top: 3px;
    }

    /* ===== BUTTON ===== */
    .button-group {
        display: flex;
        justify-content: center;
        margin-top: 16px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        background: var(--primary);
        color: white;
        border-radius: 40px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #ff581e;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 107, 44, 0.2);
    }

    .btn-primary i {
        font-size: 16px;
    }

    @media(max-width:768px){
        .container-single{
            padding:0 16px;
        }
        .card{
            padding:24px 20px;
        }
        .detail-pesanan-grid{
            grid-template-columns:1fr;
            gap:16px;
        }
        .customer-info-grid{
            grid-template-columns:1fr;
        }
        .customer-info-item.full-width {
            grid-column: span 1;
        }
        .payment-info-card{
            flex-direction:column;
            align-items:flex-start;
        }
        .payment-method-section, .payment-date-section{
            width:100%;
        }
        .btn-primary{
            width:100%;
            justify-content:center;
        }
    }
</style>
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="page-wrap">
    <div class="container-single">
        <div class="card">
            {{-- ===== KOTAK HIJAU PEMBAYARAN BERHASIL ===== --}}
            <div class="alert-success-custom">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-title">Pembayaran Berhasil!</div>
                <div class="success-message">Pembayaran Anda telah dikonfirmasi</div>
                <div class="success-submessage">Terima kasih telah menyewa kendaraan di SmartRent</div>
            </div>

            {{-- ===== DETAIL PESANAN ===== --}}
            <h2>
                <i class="fas fa-file-invoice"></i> DETAIL PESANAN
            </h2>

            {{-- GRID DETAIL PESANAN 2 KOLOM --}}
            <div class="detail-pesanan-grid">
                {{-- Kolom KIRI --}}
                <div class="detail-item">
                    <span class="detail-label">Tanggal Mulai Sewa</span>
                    <span class="detail-value">{{ $rent_date ?? '12-02-2026' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Lokasi/Outlet</span>
                    <span class="detail-value">{{ $customer_info['city'] ?? 'Jakarta' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jam Mulai</span>
                    <span class="detail-value">09:00 WIB</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Layanan</span>
                    <span class="detail-value">Dengan Supir</span>
                </div>

                {{-- Kolom KANAN --}}
                <div class="detail-item">
                    <span class="detail-label">Tanggal Selesai Sewa</span>
                    <span class="detail-value">13-02-2026</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Durasi</span>
                    <span class="detail-value">1 Hari</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jam Selesai</span>
                    <span class="detail-value">22:00 WIB</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kapasitas</span>
                    <span class="detail-value">14 Orang</span>
                </div>

                {{-- Baris 5 - Armada & No. Pesanan --}}
                <div class="detail-item">
                    <span class="detail-label">Armada</span>
                    <span class="detail-value">{{ $vehicle_name ?? 'Isuzu Elf Long' }}</span>
                </div>
                <div class="order-number-grid">
                    <span class="detail-label">No. Pesanan</span>
                    <span class="detail-value">{{ $order_number ?? 'SR2026021229B558' }}</span>
                </div>
            </div>

            {{-- ===== TOTAL PEMBAYARAN ===== --}}
            <div class="total-payment-box">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-value">Rp {{ number_format($total_price ?? 3750000, 0, ',', '.') }}</span>
            </div>

            {{-- ===== METODE PEMBAYARAN & TANGGAL PEMBAYARAN ===== --}}
            <div class="payment-info-card">
                <div class="payment-method-section">
                    <div class="payment-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="payment-method-detail">
                        <span class="payment-method-label">Metode Pembayaran</span>
                        <span class="payment-method-name">{{ strtoupper(str_replace('_', ' ', $payment_method ?? 'BCA Virtual Account')) }}</span>
                    </div>
                </div>
                <div class="payment-date-section">
                    <div class="payment-date-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="payment-date-detail">
                        <span class="payment-date-label">Tanggal Pembayaran</span>
                        <span class="payment-date-value">{{ session('smartrent_payment.payment_date') ?? now()->format('d/m/Y H:i') . ' WIB' }}</span>
                    </div>
                </div>
            </div>

            {{-- ===== INFORMASI PEMESAN ===== --}}
            <h2 style="margin-top:32px;">
                <i class="fas fa-user-circle"></i> INFORMASI PEMESAN
            </h2>

            <div class="customer-info-card">
                <div class="customer-info-header">
                    <i class="fas fa-id-card"></i>
                    <span>Data Pemesan</span>
                </div>
                <div class="customer-info-grid">
                    <div class="customer-info-item">
                        <span class="customer-info-label">Nama Lengkap</span>
                        <span class="customer-info-value">{{ $customer_info['full_name'] ?? 'Nama Pemesan' }}</span>
                    </div>
                    <div class="customer-info-item">
                        <span class="customer-info-label">Nomor Telepon</span>
                        <span class="customer-info-value">{{ $customer_info['phone'] ?? '-' }}</span>
                    </div>
                    <div class="customer-info-item full-width">
                        <span class="customer-info-label">Email</span>
                        <span class="customer-info-value">{{ $customer_info['email'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- ===== INFORMASI PENTING ===== --}}
            <div class="important-info">
                <div class="important-title">
                    <i class="fas fa-info-circle"></i> Informasi Penting
                </div>
                <ul class="important-list">
                    <li><i class="fas fa-check-circle"></i> Driver akan menghubungi Anda maksimal H-1 sebelum jadwal sewa.</li>
                    <li><i class="fas fa-check-circle"></i> Harap standby minimal 30 menit sebelum waktu penjemputan di lokasi yang telah disepakati.</li>
                    <li><i class="fas fa-check-circle"></i> Penyewa wajib menunjukkan identitas asli (KTP/SIM) saat serah terima kendaraan.</li>
                    <li><i class="fas fa-check-circle"></i> Pengisian bahan bakar selama masa sewa menjadi tanggung jawab penyewa.</li>
                    <li><i class="fas fa-check-circle"></i> Keterlambatan pengembalian dapat dikenakan biaya tambahan sesuai ketentuan.</li>
                </ul>
            </div>

            {{-- ===== TOMBOL KEMBALI KE BERANDA ===== --}}
            <div class="button-group">
                    <a href="{{ route('customer.riwayat') }}" class="btn-primary">
                        <i class="fas fa-history"></i> Lihat Riwayat Pembayaran
                    </a>
            </div>
        </div>
    </div>
</div>
@endsection