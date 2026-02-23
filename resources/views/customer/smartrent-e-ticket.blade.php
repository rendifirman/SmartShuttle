{{-- FILE: resources/views/customer/smartrent-e-ticket.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket SmartRent - {{ $transaction->order_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B2C;
            --primary-soft: #FFE1D5;
            --primary-light: #FFF0E9;
            --dark: #1E3A5F;
            --dark-light: #2D4A7A;
            --border: #E5E5E5;
            --bg: #F2F2F2;
            --success: #1E9E4A;
            --success-light: #E7F7EC;
            --white: #FFFFFF;
            --gray-light: #f8f9fa;
            --gray: #e9ecef;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            margin: 0;
            padding: 20px;
        }

        .container-ticket {
            max-width: 900px;
            margin: 0 auto;
        }

        .ticket-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
            position: relative;
        }

        .ticket-header {
            background: linear-gradient(135deg, var(--dark) 0%, #2D4A7A 100%);
            color: white;
            padding: 30px 35px;
            position: relative;
            border-bottom: 3px dashed rgba(255, 255, 255, 0.3);
        }

        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            right: 0;
            height: 24px;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.1) 10px,
                rgba(255, 255, 255, 0.1) 20px
            );
        }

        .ticket-brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .ticket-brand h1 {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
        }

        .ticket-brand h1 span {
            color: var(--primary);
            font-weight: 400;
            font-size: 14px;
            display: block;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        .ticket-type-badge {
            background: var(--primary);
            padding: 10px 20px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 16px;
            color: var(--white);
            box-shadow: 0 4px 10px rgba(255, 107, 44, 0.4);
        }

        .ticket-title {
            text-align: center;
            margin: 10px 0 5px;
        }

        .ticket-title .main-title {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 5px;
            margin-bottom: 5px;
            text-shadow: 2px 2px 0 rgba(0,0,0,0.2);
        }

        .ticket-title .sub-title {
            font-size: 16px;
            opacity: 0.9;
            letter-spacing: 2px;
        }

        .order-number-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 60px;
            padding: 15px 25px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .order-number-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .order-number-value {
            font-size: 24px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 20px;
            border-radius: 40px;
        }

        .ticket-body {
            padding: 40px 35px;
            background: white;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px 30px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item.full-width {
            grid-column: span 2;
        }

        .info-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-soft);
            word-break: break-word;
        }

        .info-value.highlight {
            color: var(--success);
            font-weight: 700;
            font-size: 18px;
        }

        .info-value i {
            color: var(--primary);
            margin-right: 5px;
        }

        .vehicle-info-card {
            background: linear-gradient(135deg, var(--primary-light) 0%, #FFF9F5 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .vehicle-info-card::before {
            content: '🚗';
            position: absolute;
            right: 20px;
            bottom: 10px;
            font-size: 80px;
            opacity: 0.1;
            transform: rotate(-10deg);
        }

        .vehicle-name {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .vehicle-details {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .vehicle-detail-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .vehicle-detail-item i {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .vehicle-detail-text {
            display: flex;
            flex-direction: column;
        }

        .vehicle-detail-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }

        .vehicle-detail-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
        }

        .rental-period {
            background: var(--gray-light);
            border-radius: 16px;
            padding: 20px;
            margin: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px dashed var(--primary);
        }

        .period-date {
            text-align: center;
            flex: 1;
        }

        .period-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .period-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
        }

        .period-time {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }

        .period-arrow {
            color: var(--primary);
            font-size: 24px;
            margin: 0 15px;
        }

        .price-section {
            background: var(--dark);
            color: white;
            border-radius: 16px;
            padding: 25px;
            margin: 25px 0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .price-row.total {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
            font-size: 20px;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid white;
        }

        .price-label {
            opacity: 0.9;
        }

        .price-value {
            font-weight: 600;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px 0 20px;
            padding: 25px;
            background: var(--gray-light);
            border-radius: 16px;
            border: 2px dashed var(--primary);
        }

        .qr-title {
            font-size: 14px;
            color: var(--dark);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .qr-container {
            background: white;
            padding: 15px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            object-fit: contain;
        }

        .qr-code-text {
            font-size: 14px;
            color: var(--dark);
            font-family: monospace;
            background: white;
            padding: 8px 20px;
            border-radius: 40px;
            border: 1px solid #ddd;
            font-weight: 600;
        }

        .instructions {
            background: #FFF9E6;
            border-left: 5px solid #FFB800;
            padding: 20px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .instructions-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .instructions-title i {
            color: #FFB800;
        }

        .instructions-list {
            margin: 0;
            padding-left: 20px;
        }

        .instructions-list li {
            margin-bottom: 10px;
            color: #555;
        }

        .ticket-footer {
            background: var(--gray-light);
            padding: 25px 35px;
            border-top: 2px dashed var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-note {
            color: #666;
            font-size: 12px;
        }

        .footer-note i {
            color: var(--primary);
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 107, 44, 0.3);
        }

        .btn-primary:hover {
            background: #E55A1E;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 107, 44, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--dark);
            border: 2px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--gray);
            border-color: var(--dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--dark);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--gray-light);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-info i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .ticket-header {
                padding: 20px;
            }

            .ticket-brand {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .ticket-title .main-title {
                font-size: 28px;
            }

            .order-number-section {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .ticket-body {
                padding: 25px 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .info-item.full-width {
                grid-column: span 1;
            }

            .vehicle-details {
                flex-direction: column;
                gap: 15px;
            }

            .rental-period {
                flex-direction: column;
                gap: 15px;
            }

            .period-arrow {
                transform: rotate(90deg);
            }

            .ticket-footer {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .vehicle-name {
                font-size: 20px;
            }

            .qr-image {
                width: 150px;
                height: 150px;
            }

            .period-value {
                font-size: 14px;
            }

            .info-value {
                font-size: 14px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .action-buttons,
            .btn,
            .ticket-header::after,
            .vehicle-info-card::before {
                display: none !important;
            }

            .ticket-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .ticket-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .vehicle-info-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .price-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container-ticket">
        @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
        @endif

        <div class="ticket-card">
            <div class="ticket-header">
                <div class="ticket-brand">
                    <h1>
                        SMART RENT
                        <span>RENTAL KENDARAAN</span>
                    </h1>
                    <div class="ticket-type-badge">
                        {{ $transaction->service_type === 'with_driver' ? 'Dengan Sopir' : 'Sewa Mandiri' }}
                    </div>
                </div>

                <div class="ticket-title">
                    <div class="main-title">E-TICKET</div>
                    <div class="sub-title">RENTAL VEHICLE TICKET</div>
                </div>

                <div class="order-number-section">
                    <span class="order-number-label">Nomor Pesanan</span>
                    <span class="order-number-value">{{ $transaction->order_number }}</span>
                </div>
            </div>

            <div class="ticket-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nomor Invoice</span>
                        <span class="info-value">{{ $transaction->invoice_number ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Pemesanan</span>
                        <span class="info-value">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value highlight">
                            <i class="fas fa-check-circle"></i> {{ $transaction->status_label ?? 'Confirmed' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Pembayaran</span>
                        <span class="info-value">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>

                <div class="vehicle-info-card">
                    <div class="vehicle-name">
                        {{ $transaction->vehicle_name }}
                    </div>
                    <div class="vehicle-details">
                        <div class="vehicle-detail-item">
                            <i class="fas fa-tag"></i>
                            <div class="vehicle-detail-text">
                                <span class="vehicle-detail-label">Layanan</span>
                                <span class="vehicle-detail-value">{{ $transaction->service_type === 'with_driver' ? 'Dengan Sopir' : 'Sewa Mandiri' }}</span>
                            </div>
                        </div>
                        <div class="vehicle-detail-item">
                            <i class="fas fa-clock"></i>
                            <div class="vehicle-detail-text">
                                <span class="vehicle-detail-label">Durasi</span>
                                <span class="vehicle-detail-value">{{ $transaction->duration }} Hari</span>
                            </div>
                        </div>
                        <div class="vehicle-detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="vehicle-detail-text">
                                <span class="vehicle-detail-label">Penjemputan</span>
                                <span class="vehicle-detail-value">{{ $transaction->pickup_location ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rental-period">
                    <div class="period-date">
                        <div class="period-label">Mulai Sewa</div>
                        <div class="period-value">
                            {{ $transaction->start_date ? $transaction->start_date->format('d M Y') : '-' }}
                        </div>
                        @if($transaction->start_time)
                        <div class="period-time">
                            {{ $transaction->start_time }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="period-arrow">
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </div>
                    
                    <div class="period-date">
                        <div class="period-label">Selesai Sewa</div>
                        <div class="period-value">
                            {{ $transaction->end_date ? $transaction->end_date->format('d M Y') : '-' }}
                        </div>
                        @if($transaction->end_time)
                        <div class="period-time">
                            {{ $transaction->end_time }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="info-grid" style="margin-top: 20px;">
                    <div class="info-item full-width">
                        <span class="info-label">Nama Pemesan</span>
                        <span class="info-value">{{ $transaction->customer_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $transaction->customer_email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nomor Telepon</span>
                        <span class="info-value">{{ $transaction->customer_phone }}</span>
                    </div>
                    @if($transaction->customer_address)
                    <div class="info-item full-width">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">{{ $transaction->customer_address }}</span>
                    </div>
                    @endif
                </div>

                <div class="price-section">
                    @foreach($priceBreakdown as $item)
                    <div class="price-row">
                        <span class="price-label">{{ $item['label'] }}</span>
                        <span class="price-value">{{ $item['formatted'] }}</span>
                    </div>
                    @endforeach
                    
                    <div class="price-row total">
                        <span class="price-label">TOTAL PEMBAYARAN</span>
                        <span class="price-value">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="qr-section">
                    <div class="qr-title">SCAN UNTUK VERIFIKASI</div>
                    <div class="qr-container">
                        @if($transaction->qr_path)
                        <img src="{{ asset($transaction->qr_path) }}" alt="QR Code" class="qr-image">
                        @else
                        <div style="width: 200px; height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-qrcode" style="font-size: 50px; color: #999;"></i>
                        </div>
                        @endif
                    </div>
                    <div class="qr-code-text">
                        {{ $transaction->order_number }}
                    </div>
                </div>

                <div class="instructions">
                    <div class="instructions-title">
                        <i class="fas fa-info-circle"></i> Informasi & Instruksi Penting
                    </div>
                    <ul class="instructions-list">
                        <li>✤ Tunjukkan E-Ticket ini beserta identitas (KTP/SIM) saat pengambilan kendaraan.</li>
                        <li>✤ Driver akan menghubungi Anda maksimal H-1 sebelum jadwal sewa.</li>
                        <li>✤ Harap datang 30 menit sebelum waktu penjemputan.</li>
                        <li>✤ Pengisian bahan bakar selama masa sewa menjadi tanggung jawab penyewa.</li>
                        <li>✤ Keterlambatan pengembalian dikenakan biaya tambahan.</li>
                        <li>✤ Untuk bantuan, hubungi Customer Service: <strong>+62 21 1234 5678</strong></li>
                    </ul>
                </div>

                @if($transaction->notes)
                <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <strong style="color: var(--dark);">Catatan:</strong>
                    <p style="margin-top: 5px; color: #666;">{{ $transaction->notes }}</p>
                </div>
                @endif

                <div style="font-size: 11px; color: #999; text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #eee;">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> 
                    E-Ticket ini adalah bukti pembayaran yang sah. Segala bentuk pemalsuan dapat dikenakan sanksi hukum.
                </div>
            </div>

            <div class="ticket-footer">
                <div class="footer-note">
                    <i class="fas fa-print"></i> Dicetak pada: {{ now()->format('d M Y H:i') }}
                </div>
                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="{{ route('customer.smartrent.e-ticket.download', $transaction->order_number) }}" class="btn btn-outline">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <a href="{{ route('customer.riwayat') }}" class="btn btn-outline">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                    <a href="{{ route('smartrent.index') }}" class="btn btn-primary">
                        <i class="fas fa-car"></i> Sewa Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>