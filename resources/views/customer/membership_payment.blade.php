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

        /* PAYMENT STYLES */
        .membership-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px 0;
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

            .card-box {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .card-box {
                padding: 20px;
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
                <i class="fas fa-credit-card"></i>
                <span>Pembayaran Membership</span>
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="membership-container">
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
                    <div class="badge-code">SS-MBS-{{ date('dmy') }}</div>
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

                <form action="{{ route('customer.membership.process-payment') }}" method="POST" id="paymentForm">
                    @csrf

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="qris" checked>
                        <span>QRIS (Scan via aplikasi / outlet)</span>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bca">
                        <span>BCA Virtual Account</span>
                    </label>

                    <div class="success-message" id="successMessage">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        Pembayaran berhasil! Membership Anda akan segera aktif.
                    </div>

                    <button type="submit" class="btn-pay" id="payButton">
                        <span id="buttonText">Bayar & Aktifkan Membership</span>
                    </button>
                </form>

                <div style="text-align: center; margin-top: 12px; font-size: 12px; color: #9ca3af;">
                    Dengan melanjutkan, Anda menyetujui
                    <a href="#" style="color: #FF6B2C; text-decoration: none;">Syarat dan Ketentuan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment form submission
        const paymentForm = document.getElementById('paymentForm');
        const payButton = document.getElementById('payButton');
        const successMessage = document.getElementById('successMessage');

        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const buttonText = document.getElementById('buttonText');
                const originalText = buttonText.innerHTML;
                buttonText.innerHTML = '<span class="loading"></span> Memproses...';
                payButton.disabled = true;

                // Simulate API call delay
                setTimeout(() => {
                    // In real implementation, submit form
                    // For demo, show success message
                    successMessage.classList.add('show');

                    // Update status
                    const statusElement = document.querySelector('.status-indicator');
                    if (statusElement) {
                        statusElement.textContent = 'Processing';
                    }

                    // Submit form after 2 seconds
                    setTimeout(() => {
                        paymentForm.submit();
                    }, 2000);
                }, 1500);
            });
        }

        // Payment method selection
        const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
        paymentOptions.forEach(option => {
            option.addEventListener('change', function() {
                console.log('Selected payment method:', this.value);
            });
        });
    });
</script>
</body>
</html>
