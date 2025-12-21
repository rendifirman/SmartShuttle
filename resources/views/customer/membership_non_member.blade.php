<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership - SmartShuttle</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Copy semua CSS dari file tunggal */
        /* Hanya tampilkan CSS yang relevan untuk halaman ini */
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

        /* MEMBERSHIP STYLES */
        .membership-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* PAGE 1: NOT REGISTERED */
        .header-card {
            background: linear-gradient(135deg, #00274D 0%, #003366 100%);
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 39, 77, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #FF6B2C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .header-text h2 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .level-badge-small {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }

        .header-right {
            display: flex;
            gap: 15px;
        }

        .point-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            min-width: 130px;
            position: relative;
        }

        .point-badge-locked::before {
            content: '🔒';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 14px;
        }

        .point-badge-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .point-badge-value {
            color: white;
            font-size: 22px;
            font-weight: 700;
        }

        .progress-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .progress-card h6 {
            color: #00274D;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .membership-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .membership-card h5 {
            color: #00274D;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .tier-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #ddd;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .tier-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .tier-item.bronze { border-left-color: #CD7F32; }
        .tier-item.silver { border-left-color: #C0C0C0; }
        .tier-item.gold { border-left-color: #FFD700; }
        .tier-item.platinum { border-left-color: #E5E4E2; }

        .tier-name {
            font-size: 16px;
            font-weight: 700;
            color: #00274D;
        }

        .tier-icon {
            font-size: 20px;
            color: #adb5bd;
        }

        .register-card {
            background: linear-gradient(135deg, #FFE5D9 0%, #FFD4C4 100%);
            padding: 35px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .register-card h5 {
            color: #FF6B2C;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .register-card p {
            color: #666;
            font-size: 15px;
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .btn-register {
            background: linear-gradient(135deg, #FF6B2C, #FF8E53);
            color: white;
            border: none;
            padding: 14px 45px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 44, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 44, 0.4);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .header-card {
                flex-direction: column;
                text-align: center;
            }

            .header-left {
                flex-direction: column;
            }

            .header-right {
                flex-direction: column;
                width: 100%;
            }

            .point-badge {
                width: 100%;
            }
        }

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

            .header-card,
            .progress-card,
            .membership-card,
            .register-card {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .header-card,
            .progress-card,
            .membership-card,
            .register-card {
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
                <i class="fas fa-crown"></i>
                <span>Membership</span>
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="membership-wrapper">
            <!-- PAGE 1: NOT REGISTERED -->
            <div id="page-not-registered">

                <div class="header-card">
                    <div class="header-left">
                        <div class="profile-avatar" id="avatarNotRegistered">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="header-text">
                            <h2 id="welcomeNotRegistered">Hello, {{ Auth::user()->name }}</h2>
                            <span class="level-badge-small">Belum Terdaftar Membership</span>
                        </div>
                    </div>

                    <div class="header-right">
                        <div class="point-badge point-badge-locked">
                            <div class="point-badge-label">Point Member</div>
                            <div class="point-badge-value">0</div>
                        </div>
                        <div class="point-badge point-badge-locked">
                            <div class="point-badge-label">Loyalty Point</div>
                            <div class="point-badge-value">0</div>
                        </div>
                    </div>
                </div>

                <div class="progress-card">
                    <h6><i class="fas fa-chart-line"></i> Progress Level Membership</h6>
                    <p class="progress-description">
                        Daftar membership untuk mulai mengumpulkan point dan naik level dari Bronze hingga Platinum
                    </p>
                </div>

                <div class="membership-card">
                    <h5>Tingkat Membership</h5>

                    <div class="tier-item bronze">
                        <div class="tier-name">Bronze</div>
                        <div class="tier-icon">🔒</div>
                    </div>

                    <div class="tier-item silver">
                        <div class="tier-name">Silver</div>
                        <div class="tier-icon">🔒</div>
                    </div>

                    <div class="tier-item gold">
                        <div class="tier-name">Gold</div>
                        <div class="tier-icon">🔒</div>
                    </div>

                    <div class="tier-item platinum">
                        <div class="tier-name">Platinum</div>
                        <div class="tier-icon">🔒</div>
                    </div>
                </div>

                <div class="register-card">
                    <h5>Belum Jadi Member?</h5>
                    <p>
                        Daftar membership sekarang dan dapatkan akses ke berbagai keuntungan eksklusif,
                        termasuk diskon, point rewards, dan prioritas layanan.
                    </p>
                    <a href="{{ route('customer.membership.form') }}" class="btn-register">
                        <i class="fas fa-crown"></i> Daftar Membership
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Membership Non Member Page Loaded');
    });
</script>
</body>
</html>
