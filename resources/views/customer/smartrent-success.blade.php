{{-- FILE: resources/views/customer/smartrent-success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - SmartRent')

@push('styles')
<style>
    :root {
        --primary: #FF6B2C;
        --primary-light: #FFF0E9;
        --dark: #1E3A5F;
        --success: #1E9E4A;
        --success-light: #E7F7EC;
        --border: #E5E5E5;
        --bg: #F2F2F2;
        --white: #FFFFFF;
    }

    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    .page-wrap {
        background: var(--bg);
        padding: 100px 0 80px;
        min-height: 100vh;
    }

    .container-single {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .card {
        background: var(--white);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        width: 100%;
        margin-bottom: 24px;
    }

    .success-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: var(--success-light);
        border: 3px solid var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 40px;
        color: var(--success);
    }

    .success-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 8px;
    }

    .success-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 24px;
    }

    .order-number-box {
        background: var(--primary-light);
        border: 2px solid var(--primary);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 32px;
    }

    .order-number-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .order-number-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }

    /* Style untuk tombol e-ticket */
    .order-number-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 15px;
    }

    .btn-eticket {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-eticket.view {
        background: var(--primary);
        color: white;
    }

    .btn-eticket.view:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
    }

    .btn-eticket.download {
        background: var(--dark);
        color: white;
    }

    .btn-eticket.download:hover {
        background: #2D4A7A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
    }

    .summary-section h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--primary-light);
    }

    .summary-section h3 i {
        color: var(--primary);
        font-size: 18px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        font-size: 14px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        color: #666;
        font-weight: 500;
        flex: 0 0 40%;
    }

    .summary-value {
        color: var(--dark);
        font-weight: 600;
        text-align: right;
        flex: 1;
    }

    .total-section {
        background: var(--success-light);
        border-left: 4px solid var(--success);
        padding: 24px;
        border-radius: 8px;
        margin-bottom: 32px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 20px;
        font-weight: 700;
        color: var(--success);
    }

    .divider {
        height: 2px;
        background: var(--border);
        margin: 32px 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--primary-light);
    }

    .section-title i {
        color: var(--primary);
        font-size: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        flex: 1;
        padding: 14px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
    }

    .btn-secondary {
        background: white;
        color: var(--dark);
        border: 2px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--bg);
        border-color: var(--dark);
    }

    .info-box {
        background: #f0f5ff;
        border-left: 4px solid #0066cc;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #333;
        line-height: 1.6;
    }

    .info-box strong {
        color: #0066cc;
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            padding: 24px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .order-number-actions {
            flex-direction: column;
        }

        .btn-eticket {
            width: 100%;
            justify-content: center;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            font-size: 30px;
        }

        .success-title {
            font-size: 24px;
        }

        .order-number-value {
            font-size: 20px;
        }

        .summary-row {
            flex-direction: column;
            gap: 4px;
        }

        .summary-label {
            flex: 1;
            text-align: left;
        }

        .summary-value {
            text-align: left;
        }
    }
</style>
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="page-wrap">
    <div class="container-single">
        {{-- SUCCESS HEADER --}}
        <div class="card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">Pembayaran Berhasil!</h1>
                <p class="success-subtitle">Pesanan SmartRent Anda telah dikonfirmasi dan sedang diproses</p>
            </div>

            <div class="order-number-box">
                <div class="order-number-label">Nomor Pesanan</div>
                <div class="order-number-value">{{ $transaction->order_number ?? ($order_number ?? '-') }}</div>
                
                {{-- Tombol Lihat dan Download E-Ticket --}}
                <div class="order-number-actions">
                    <a href="{{ route('customer.smartrent.e-ticket', ['orderNumber' => $transaction->order_number ?? $order_number]) }}" 
                       class="btn-eticket view" 
                       target="_blank">
                        <i class="fas fa-ticket-alt"></i> Lihat E-Ticket
                    </a>
                    <a href="{{ route('customer.smartrent.e-ticket.download', ['orderNumber' => $transaction->order_number ?? $order_number]) }}" 
                       class="btn-eticket download">
                        <i class="fas fa-download"></i> Download E-Ticket
                    </a>
                </div>
            </div>

            @if($transaction->invoice_number ?? false)
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Nomor Invoice</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--dark);">{{ $transaction->invoice_number }}</div>
            </div>
            @endif
        </div>

        {{-- PAYMENT SUMMARY --}}
        <div class="card">
            <h2 class="section-title"><i class="fas fa-receipt"></i> Ringkasan Pesanan</h2>

            <div class="summary-grid">
                {{-- LEFT: VEHICLE & RENTAL --}}
                <div>
                    <h3 class="summary-section h3" style="font-size: 14px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-car" style="color: var(--primary); font-size: 18px;"></i> Kendaraan & Jadwal
                    </h3>
                    <div class="summary-row">
                        <span class="summary-label">Kendaraan</span>
                        <span class="summary-value">{{ $transaction->vehicle_name ?? '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tipe Layanan</span>
                        <span class="summary-value">{{ $transaction->service_type === 'with_driver' ? 'Dengan Sopir' : 'Self Drive' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Mulai Sewa</span>
                        <span class="summary-value">{{ $transaction->start_date ? $transaction->start_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Selesai Sewa</span>
                        <span class="summary-value">{{ $transaction->end_date ? $transaction->end_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Durasi</span>
                        <span class="summary-value">{{ $transaction->duration ?? '-' }} Hari</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Lokasi Penjemputan</span>
                        <span class="summary-value">{{ $transaction->pickup_location ?? '-' }}</span>
                    </div>
                </div>

                {{-- RIGHT: CUSTOMER & PAYMENT --}}
                <div>
                    <h3 class="summary-section h3" style="font-size: 14px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user" style="color: var(--primary); font-size: 18px;"></i> Data Pemesan & Pembayaran
                    </h3>
                    <div class="summary-row">
                        <span class="summary-label">Nama Pemesan</span>
                        <span class="summary-value">{{ $transaction->customer_name ?? '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Email</span>
                        <span class="summary-value" style="font-size: 12px;">{{ $transaction->customer_email ?? '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Telepon</span>
                        <span class="summary-value">{{ $transaction->customer_phone ?? '-' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Metode Pembayaran</span>
                        <span class="summary-value">
                            @if($transaction->payment_method === 'qris')
                                QRIS
                            @elseif($transaction->payment_method === 'bca_va')
                                BCA Virtual Account
                            @elseif($transaction->payment_method === 'mandiri_va')
                                Mandiri Virtual Account
                            @else
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? '-')) }}
                            @endif
                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tanggal Pembayaran</span>
                        <span class="summary-value">{{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y') : ($transaction->updated_at ? $transaction->updated_at->format('d/m/Y') : '-') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Status Pembayaran</span>
                        <span class="summary-value" style="color: var(--success);">
                            <i class="fas fa-check-circle"></i> Terbayar
                        </span>
                    </div>
                </div>
            </div>

            {{-- PRICE BREAKDOWN --}}
            <div class="divider"></div>

            <h3 style="font-size: 14px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid var(--primary-light);">
                <i class="fas fa-tag" style="color: var(--primary); margin-right: 8px;"></i> Rincian Harga
            </h3>

            <div style="margin-bottom: 24px;">
                <div class="summary-row" style="margin-bottom: 10px;">
                    <span class="summary-label">Harga Sewa/Hari</span>
                    <span class="summary-value">Rp {{ $transaction->vehicle_price ? number_format($transaction->vehicle_price, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="summary-row" style="margin-bottom: 10px;">
                    <span class="summary-label">Durasi (Hari)</span>
                    <span class="summary-value">{{ $transaction->duration ?? '-' }}</span>
                </div>
                <div class="summary-row" style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
                    <span class="summary-label" style="font-weight: 700;">Total Sewa</span>
                    <span class="summary-value" style="font-weight: 700;">Rp {{ $transaction->vehicle_total ? number_format($transaction->vehicle_total, 0, ',', '.') : '-' }}</span>
                </div>

                @if(($transaction->service_type ?? '') === 'with_driver' && ($transaction->driver_total ?? 0) > 0)
                <div class="summary-row" style="margin-bottom: 10px;">
                    <span class="summary-label">Biaya Sopir/Hari</span>
                    <span class="summary-value">Rp {{ $transaction->driver_price_per_day ? number_format($transaction->driver_price_per_day, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="summary-row" style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
                    <span class="summary-label" style="font-weight: 700;">Total Sopir</span>
                    <span class="summary-value" style="font-weight: 700;">Rp {{ $transaction->driver_total ? number_format($transaction->driver_total, 0, ',', '.') : '-' }}</span>
                </div>
                @endif
            </div>

            {{-- TOTAL --}}
            <div class="total-section">
                <div class="total-row">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ $transaction->total_price ? number_format($transaction->total_price, 0, ',', '.') : '-' }}</span>
                </div>
            </div>

            {{-- INFORMATION BOX --}}
            <div class="info-box">
                <strong><i class="fas fa-info-circle"></i> Informasi Penting</strong><br>
                ✓ Pesanan Anda telah dikonfirmasi dan akan segera diproses<br>
                ✓ E-Ticket telah tersedia, klik tombol "Lihat E-Ticket" di atas untuk melihat detail lengkap<br>
                ✓ Bukti pembayaran telah dikirim ke email: <strong>{{ $transaction->customer_email ?? '-' }}</strong><br>
                ✓ Driver akan menghubungi Anda H-1 sebelum jadwal sewa<br>
                ✓ Harap standby 30 menit sebelum penjemputan<br>
                ✓ Cek status pesanan di halaman "Riwayat Pesanan"
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="action-buttons">
                <a href="{{ route('customer.riwayat') }}" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Lihat Riwayat Pesanan
                </a>
                <a href="{{ route('smartrent.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.scrollTo(0, 0);
    
    // Auto refresh token CSRF jika diperlukan
    setInterval(function() {
        fetch('/refresh-csrf')
            .then(response => response.json())
            .then(data => {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
            })
            .catch(error => console.error('Error refreshing CSRF:', error));
    }, 3600000); // Refresh setiap 1 jam
</script>

<script>
    // Tracking event untuk analytics (jika diperlukan)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('SmartRent payment success page loaded for order: {{ $transaction->order_number ?? $order_number ?? "unknown" }}');
        
        // Track page view
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase', {
                'transaction_id': '{{ $transaction->order_number ?? $order_number ?? "" }}',
                'value': {{ $transaction->total_price ?? 0 }},
                'currency': 'IDR'
            });
        }
    });
</script>

<script>
    // Fungsi untuk copy nomor pesanan ke clipboard
    function copyOrderNumber() {
        const orderNumber = '{{ $transaction->order_number ?? $order_number ?? "" }}';
        navigator.clipboard.writeText(orderNumber).then(function() {
            alert('Nomor pesanan berhasil disalin!');
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush

@push('styles')
<style>
    /* Additional styles */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }
    
    .btn-eticket.view, .btn-eticket.download {
        position: relative;
        overflow: hidden;
    }
    
    .btn-eticket.view::after, .btn-eticket.download::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-eticket.view:hover::after, .btn-eticket.download:hover::after {
        width: 300px;
        height: 300px;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 107, 44, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 107, 44, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 44, 0);
        }
    }
    
    .order-number-box {
        animation: pulse 2s infinite;
    }
    
    @media print {
        .action-buttons, .order-number-actions {
            display: none !important;
        }
        
        .page-wrap {
            padding: 20px 0;
            background: white;
        }
        
        .card {
            box-shadow: none;
            border: 1px solid #ddd;
            break-inside: avoid;
        }
        
        .order-number-box {
            animation: none;
            border: 2px solid #FF6B2C;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .total-section {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endpush