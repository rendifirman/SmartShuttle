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
        /* [CSS SAMA DENGAN YANG SEBELUMNYA] */
        /* Copy semua CSS dari file sebelumnya */
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
                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
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
                <span>Menunggu Pembayaran Membership</span>
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="pending-container">
            <!-- Success Message dari Controller -->
            @if(session('success'))
            <div class="status-card" style="border-top-color: #28a745;">
                <div class="status-icon" style="color: #28a745;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="status-title">Pendaftaran Berhasil!</h1>
                <p class="status-description">
                    {{ session('success') }}
                </p>
            </div>
            @endif

            <!-- Countdown Timer -->
            @if(isset($pendingPayment) && $pendingPayment->waktu_kadaluarsa)
            <div class="countdown">
                <div class="countdown-title">Sisa Waktu Pembayaran:</div>
                <div class="countdown-timer" id="countdownTimer">
                    {{ \Carbon\Carbon::parse($pendingPayment->waktu_kadaluarsa)->diff(now())->format('%H:%I:%S') }}
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
            @if(isset($pendingPayment))
            <div class="payment-details">
                <h3>Detail Pembayaran</h3>

                <div class="detail-row">
                    <span class="detail-label">Kode Transaksi</span>
                    <span class="detail-value transaction-id">{{ $pendingPayment->transaction_id ?? 'MEM-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6)) }}</span>
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
                    <span class="detail-value">Rp 20.000</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total Pembayaran</span>
                    <span class="detail-value" style="font-size: 1.2rem; color: #00274D;">
                        Rp {{ number_format($pendingPayment->total_amount ?? 20000, 0, ',', '.') }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran</span>
                    <span class="detail-value" style="color: #ffc107; font-weight: 700;">
                        <i class="fas fa-clock"></i> Menunggu Pembayaran
                    </span>
                </div>

                @if(isset($pendingPayment) && $pendingPayment->waktu_kadaluarsa)
                <div class="detail-row">
                    <span class="detail-label">Batas Waktu Pembayaran</span>
                    <span class="detail-value" style="color: #dc3545;">
                        {{ \Carbon\Carbon::parse($pendingPayment->waktu_kadaluarsa)->format('d F Y H:i:s') }}
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
                    <li>Membership akan aktif secara otomatis setelah pembayaran terverifikasi</li>
                </ol>
            </div>

            <!-- Payment Actions -->
            <div class="payment-actions">
                <a href="{{ route('customer.membership.payment') }}" class="btn-pay">
                    <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                </a>

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
        @if(isset($pendingPayment) && $pendingPayment->waktu_kadaluarsa)
        const waktuKadaluarsa = new Date('{{ $pendingPayment->waktu_kadaluarsa }}').getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = waktuKadaluarsa - now;

            if (timeLeft <= 0) {
                document.getElementById('countdownTimer').textContent = '00:00:00';
                // Auto refresh page when expired
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
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
