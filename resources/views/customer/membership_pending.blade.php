<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran - SmartShuttle</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 300px;
            background: #00274D;
            color: #fff;
            padding: 30px 0;
            flex-shrink: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #FF6B2C;
            margin-bottom: 40px;
            padding: 0 20px;
            cursor: pointer;
            transition: transform 0.3s, color 0.3s;
        }

        .logo:hover {
            transform: scale(1.05);
            color: #FF8E53;
        }

        .menu {
            list-style: none;
            padding-left: 0;
        }

        .menu li {
            padding: 0;
            margin: 5px 15px;
            border-radius: 5px;
            overflow: hidden;
        }

        .menu a.menu-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px 30px;
            color: inherit;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
            width: 100%;
        }

        .menu a.menu-link:hover {
            background: rgba(255, 107, 44, 0.1);
        }

        .menu li.active a.menu-link {
            background: #FF6B2C;
            border-radius: 5px;
        }

        .menu-icon {
            width: 25px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-link {
            color: #ff6b6b !important;
        }

        .logout-link:hover {
            background: rgba(255, 107, 44, 0.1) !important;
        }

        .beranda-link {
            color: #FF6B2C !important;
        }

        .beranda-link:hover {
            background: rgba(255, 107, 44, 0.1) !important;
        }

        /* CONTENT AREA */
        .content {
            flex: 1;
            padding: 30px;
            position: relative;
            margin-left: 300px;
            width: calc(100% - 300px);
            min-height: 100vh;
        }

        /* HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            font-size: 28px;
            font-weight: 700;
            color: #00274D;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header i {
            color: #FF6B2C;
            font-size: 24px;
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF6B2C, #FF8E53);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .profile-icon:hover {
            transform: scale(1.05);
        }

        /* PENDING STYLES */
        .pending-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .status-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #FF6B2C;
        }

        .status-icon {
            font-size: 4rem;
            color: #FF6B2C;
            margin-bottom: 20px;
        }

        .status-title {
            color: #00274D;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .status-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .payment-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: left;
        }

        .payment-details h3 {
            color: #00274D;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e8ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #00274D;
        }

        .detail-value {
            font-weight: 600;
            color: #FF6B2C;
        }

        .detail-value.transaction-id {
            font-family: monospace;
            font-size: 1.1rem;
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 4px;
            color: #00274D;
        }

        .payment-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn-pay {
            background: linear-gradient(135deg, #FF6B2C 0%, #FF8E53 100%);
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 200px;
            box-shadow: 0 5px 20px rgba(255, 107, 44, 0.3);
        }

        .btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 44, 0.4);
            background: linear-gradient(135deg, #e55a1f 0%, #e57640 100%);
            color: white;
            text-decoration: none;
        }

        .btn-cancel {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 200px;
        }

        .btn-cancel:hover {
            background: #dc3545;
            color: white;
            text-decoration: none;
        }

        .btn-back {
            background: white;
            color: #00274D;
            border: 2px solid #00274D;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 200px;
        }

        .btn-back:hover {
            background: #00274D;
            color: white;
            text-decoration: none;
        }

        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }

        .instructions h4 {
            color: #856404;
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .instructions ol {
            margin: 0;
            padding-left: 20px;
            color: #856404;
        }

        .instructions li {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .countdown {
            background: linear-gradient(135deg, #00274D 0%, #004080 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .countdown-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .countdown-timer {
            font-size: 2.5rem;
            font-weight: 800;
            font-family: monospace;
            letter-spacing: 2px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-left: 0;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .status-card {
                padding: 25px 20px;
            }

            .status-icon {
                font-size: 3rem;
            }

            .status-title {
                font-size: 1.7rem;
            }

            .payment-actions {
                flex-direction: column;
            }

            .btn-pay, .btn-cancel, .btn-back {
                width: 100%;
            }

            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <!-- LOGO YANG BISA DIKLIK UNTUK KE HALAMAN BERANDA -->
        <div class="logo" onclick="location.href='{{ route('customer.beranda') }}'">
            SMART SHUTTLE
        </div>

        <ul class="menu">
            <li>
                <a href="{{ route('customer.dashboardprofile') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-home"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('customer.profilcust') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-circle"></i></span>
                    <span>Profil Saya</span>
                </a>
            </li>

            <li>
                <a href="{{ route('customer.riwayat') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-history"></i></span>
                    <span>Riwayat Pesanan</span>
                </a>
            </li>

            <li class="active">
                <a href="{{ route('customer.membership') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-crown"></i></span>
                    <span>Membership</span>
                </a>
            </li>

            <!-- TOMBOL BERANDA DI BAWAH LOGOUT -->
            <li class="sidebar-footer">
                <a href="{{ route('customer.beranda') }}" class="menu-link beranda-link">
                    <span class="menu-icon"><i class="fas fa-arrow-left"></i></span>
                    <span>Beranda</span>
                </a>
            </li>

            <li class="sidebar-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link logout-link">
                    <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CONTENT AREA -->
    <div class="content">
        <!-- HEADER -->
        <div class="top-header">
            <div class="header">
                <i class="fas fa-clock"></i>
                <span>Menunggu Pembayaran</span>
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="pending-container">
            <!-- Countdown Timer -->
            @if(isset($payment) && $payment->expired_at)
            <div class="countdown">
                <div class="countdown-title">Sisa Waktu Pembayaran:</div>
                <div class="countdown-timer" id="countdownTimer">
                    {{ $payment->expired_at->diff(now())->format('%H:%I:%S') }}
                </div>
            </div>
            @endif

            <!-- Status Card -->
            <div class="status-card">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h1 class="status-title">Menunggu Pembayaran</h1>
                <p class="status-description">
                    Silakan selesaikan pembayaran untuk mengaktifkan membership Anda.
                    Transaksi akan kadaluarsa dalam <strong>24 jam</strong> jika tidak dilakukan pembayaran.
                </p>
            </div>

            <!-- Payment Details -->
            @if(isset($payment))
            <div class="payment-details">
                <h3>Detail Pembayaran</h3>

                <div class="detail-row">
                    <span class="detail-label">Kode Transaksi</span>
                    <span class="detail-value transaction-id">{{ $payment->transaction_id ?? 'TRX-' . date('YmdHis') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Nama Member</span>
                    <span class="detail-value">{{ Auth::user()->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ Auth::user()->email }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Biaya Membership</span>
                    <span class="detail-value">Rp 100.000</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Diskon</span>
                    <span class="detail-value">Rp 0</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total Pembayaran</span>
                    <span class="detail-value" style="font-size: 1.2rem; color: #00274D;">
                        Rp {{ number_format($payment->amount ?? 20000, 0, ',', '.') }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran</span>
                    <span class="detail-value" style="color: #ffc107; font-weight: 700;">
                        <i class="fas fa-clock"></i> Menunggu Pembayaran
                    </span>
                </div>

                @if(isset($payment) && $payment->expired_at)
                <div class="detail-row">
                    <span class="detail-label">Batas Waktu Pembayaran</span>
                    <span class="detail-value" style="color: #dc3545;">
                        {{ $payment->expired_at->format('d F Y H:i:s') }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Payment Instructions -->
            <div class="instructions">
                <h4><i class="fas fa-info-circle"></i> Cara Pembayaran</h4>
                <ol>
                    <li>Klik tombol "Lanjutkan Pembayaran" di bawah</li>
                    <li>Pilih metode pembayaran yang tersedia</li>
                    <li>Transfer sesuai dengan total pembayaran yang tertera</li>
                    <li>Unggah bukti pembayaran (opsional untuk simulasi)</li>
                    <li>Membership akan aktif secara otomatis setelah pembayaran terverifikasi</li>
                </ol>
            </div>

            <!-- Payment Actions -->
            <div class="payment-actions">
                <a href="{{ route('customer.membership.payment') }}" class="btn-pay">
                    <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                </a>

                <form action="{{ route('customer.membership.payment.cancel') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-cancel" onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran?')">
                        <i class="fas fa-times"></i> Batalkan Pembayaran
                    </button>
                </form>

                <a href="{{ route('customer.membership') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Membership
                </a>
            </div>
            @else
            <div class="status-card" style="border-top-color: #dc3545;">
                <div class="status-icon" style="color: #dc3545;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="status-title">Pembayaran Tidak Ditemukan</h1>
                <p class="status-description">
                    Tidak ada pembayaran membership yang tertunda. Silakan daftar ulang untuk membuat pembayaran baru.
                </p>

                <div class="payment-actions">
                    <a href="{{ route('customer.membership.form') }}" class="btn-pay">
                        <i class="fas fa-redo"></i> Daftar Ulang Membership
                    </a>
                    <a href="{{ route('customer.membership') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Membership
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Countdown timer
        @if(isset($payment) && $payment->expired_at)
        const waktuKadaluarsa = new Date('{{ $payment->expired_at }}').getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = waktuKadaluarsa - now;

            if (timeLeft <= 0) {
                document.getElementById('countdownTimer').textContent = '00:00:00';
                return;
            }

            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // Update countdown timer
            document.getElementById('countdownTimer').textContent =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
        @endif
    });
</script>
</body>
</html>
