<!DOCTYPE html>
<html lang="id">
<head>
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART SHUTTLE - Admin')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* ======== SIDEBAR ======== */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #0d3559;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 0;
            box-sizing: border-box;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 15px;
        }

        .sidebar-title {
            font-size: 22px;
            font-weight: bold;
            color: #ff6a00;
            line-height: 1.3;
        }

        .sidebar-title span {
            color: white;
        }

        .sidebar-nav {
            padding: 0 15px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-active {
            background:  #ff6a00 !important;
            color: #0d3559 !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
        }

        .menu-arrow {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .menu-arrow.rotated {
            transform: rotate(180deg);
        }

        /* Submenu Styling */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-left: 20px;
            padding-left: 15px;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            margin-bottom: 5px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 15px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .submenu-item.active {
            background: #ff6a00 !important;
            color: #0d3559 !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
        }

        .submenu-icon {
            width: 16px;
            text-align: center;
            font-size: 14px;
        }

        /* User Profile di Sidebar */
        .sidebar-user {
            padding: 20px 25px 0;
            margin-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #ff6a00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .user-info h4 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .user-info p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Logout Button */
        .logout-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin: 15px 25px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            color: white;
            transition: all 0.3s ease;
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            text-decoration: none;
        }

        .logout-button:hover {
            background: rgba(220, 53, 69, 0.3);
            border-color: rgba(220, 53, 69, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }

        .logout-button i {
            width: 20px;
            text-align: center;
        }

        /* ======== CONTENT ======== */
        .content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            background: #f5f7fa;
            transition: margin-left 0.3s ease;
        }

        /* Header di Content */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .page-title {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: #ff6a00;
        }

        .user-info-top {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .user-info-top i {
            color: #ff6a00;
            font-size: 20px;
        }

        .user-info-top span {
            font-weight: 500;
            color: #333;
        }

        /* ======== MOBILE HEADER ======== */
        .mobile-header {
            display: none;
            background: #0d3559;
            color: white;
            padding: 0 15px;
            font-size: 18px;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            height: 60px;
            align-items: center;
        }

        .mobile-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .mobile-logo {
            color: #ff6a00;
            font-size: 16px;
            font-weight: bold;
            flex: 1;
            text-align: center;
            padding-right: 40px;
        }

        .mobile-hamburger {
            background: transparent;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            z-index: 1101;
            position: relative;
        }

        .mobile-hamburger:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .overlay.show {
            display: block;
        }

        /* Logout Confirmation Modal */
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .logout-modal.show {
            display: flex;
        }

        .logout-modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .logout-modal-icon {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .logout-modal-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .logout-modal-text {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .logout-modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .logout-modal-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
        }

        .logout-modal-btn.cancel {
            background: #6c757d;
            color: white;
        }

        .logout-modal-btn.cancel:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .logout-modal-btn.confirm {
            background: #dc3545;
            color: white;
        }

        .logout-modal-btn.confirm:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        /* Responsif */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 20px;
                margin-top: 60px;
            }

            .mobile-header {
                display: flex;
            }

            .logout-button {
                margin: 15px 15px 25px;
            }
        }

        @media (min-width: 993px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        @media (max-width: 480px) {
            .mobile-header {
                height: 55px;
                padding: 0 12px;
            }

            .mobile-logo {
                font-size: 14px;
            }

            .mobile-hamburger {
                width: 35px;
                height: 35px;
                font-size: 20px;
            }

            .content {
                padding: 15px;
                margin-top: 55px;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .user-info-top {
                width: 100%;
                justify-content: center;
            }

            .logout-modal-content {
                padding: 20px;
                width: 95%;
            }

            .logout-modal-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .logout-modal-btn {
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Logout Confirmation Modal -->
<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-content">
        <div class="logout-modal-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h3 class="logout-modal-title">Konfirmasi Logout</h3>
        <p class="logout-modal-text">Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="logout-modal-buttons">
            <button class="logout-modal-btn cancel" id="cancelLogoutBtn">Batal</button>
            <button class="logout-modal-btn confirm" id="confirmLogoutBtn">Ya, Logout</button>
        </div>
    </div>
</div>

<!-- Mobile Header -->
<div class="mobile-header" id="mobileHeader">
    <div class="mobile-header-content">
        <button class="mobile-hamburger" id="mobileHamburgerBtn">
            <i class="fas fa-bars"></i>
        </button>
        <span class="mobile-logo">SMART SHUTTLE {{ Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasRole('admin_pusat') ? 'ADMIN PUSAT' : 'ADMIN CABANG' }}</span>
    </div>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <div class="sidebar-title">SMART SHUTTLE<br><span>{{ Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasRole('admin_pusat') ? 'ADMIN PUSAT' : 'ADMIN CABANG' }}</span></div>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">
        <!-- Dashboard -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_dashboard'))
        <a href="{{ route('admin.dashboard') }}" class="menu-item" id="dashboard-link">
            <div class="menu-left">
                <i class="fas fa-chart-bar menu-icon"></i>
                <span>Dashboard</span>
            </div>
        </a>
        @endif

        <!-- Master Data (with submenu) -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_master_data'))
        <div class="menu-item" id="master-data-toggle">
            <div class="menu-left">
                <i class="fas fa-database menu-icon"></i>
                <span>Master Data</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="master-data-submenu">
            <!-- PROFILE PERUSAHAAN - Ditambahkan di paling atas -->
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_profile_perusahaan'))
            <a href="{{ route('admin.profileperusahaan') }}" class="submenu-item" id="profile-perusahaan-link">
                <i class="fas fa-building submenu-icon"></i>
                <span>Profile Perusahaan</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_cabang'))
            <a href="{{ route('admin.cabangperusahaan') }}" class="submenu-item" id="cabang-link">
                <i class="fas fa-code-branch submenu-icon"></i>
                <span>Cabang</span>
            </a>
            @endif

            <!-- MENU BARU YANG DITAMBAHKAN -->
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_outlet'))
            <a href="{{ route('admin.outletperusahaan') }}" class="submenu-item" id="outlet-link">
                <i class="fas fa-store submenu-icon"></i>
                <span>Outlet</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_promo'))
            <a href="{{ route('admin.promo') }}" class="submenu-item" id="promo-link">
                <i class="fas fa-tag submenu-icon"></i>
                <span>Promo</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_tarif'))
            <a href="{{ route('admin.master-tarif.index') }}" class="submenu-item" id="tarif-link">
                <i class="fas fa-money-bill-wave submenu-icon"></i>
                <span>Tarif</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_kontak'))
            <a href="{{ route('admin.kontakperusahaan') }}" class="submenu-item" id="kontak-link">
                <i class="fas fa-address-book submenu-icon"></i>
                <span>Kontak</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_artikel'))
            <a href="{{ route('admin.artikel.index') }}" class="submenu-item" id="artikel-link">
                <i class="fas fa-newspaper submenu-icon"></i>
                <span>Artikel</span>
            </a>
            @endif

            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_armada'))
            <a href="{{ route('admin.armada') }}" class="submenu-item" id="armada-link">
                <i class="fas fa-bus submenu-icon"></i>
                <span>Armada</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_driver'))
            <a href="{{ route('admin.driver') }}" class="submenu-item" id="driver-link">
                <i class="fas fa-user-tie submenu-icon"></i>
                <span>Driver</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_pegawai'))
            <a href="{{ route('admin.pegawai') }}" class="submenu-item" id="pegawai-link">
                <i class="fas fa-users submenu-icon"></i>
                <span>Pegawai</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_rute'))
            <a href="{{ route('admin.rute.index') }}" class="submenu-item" id="rute-link">
                <i class="fas fa-route submenu-icon"></i>
                <span>Rute</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_jadwal'))
            <!-- ★★★ PERBAIKAN DI SINI: Ubah route('admin.jadwal') menjadi route('admin.jadwal.index') ★★★ -->
            <a href="{{ route('admin.jadwal.index') }}" class="submenu-item" id="jadwal-link">
                <i class="fas fa-calendar-alt submenu-icon"></i>
                <span>Jadwal</span>
            </a>
            @endif
        </div>
        @endif

        <!-- Transaksi (with submenu) -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_transaksi'))
        <div class="menu-item" id="transaksi-toggle">
            <div class="menu-left">
                <i class="fas fa-exchange-alt menu-icon"></i>
                <span>Transaksi</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="transaksi-submenu">
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartsend_transaksi'))
            <a href="{{ route('admin.smartsend-transaksi') }}" class="submenu-item" id="smartsend-transaksi-link">
                <i class="fas fa-shopping-cart submenu-icon"></i>
                <span>Smartsend</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_perjalanan_transaksi'))
            <a href="{{ route('admin.perjalanan') }}" class="submenu-item" id="perjalanan-link">
                <i class="fas fa-route submenu-icon"></i>
                <span>Perjalanan</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_armada_transaksi'))
            <a href="{{ route('admin.armada-transaksi') }}" class="submenu-item" id="armada-transaksi-link">
                <i class="fas fa-bus submenu-icon"></i>
                <span>Armada</span>
            </a>
            @endif
        </div>
        @endif

        <!-- SmartSend (with submenu) -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartsend'))
        <div class="menu-item" id="smartsend-toggle">
            <div class="menu-left">
                <i class="fas fa-shipping-fast menu-icon"></i>
                <span>SmartSend</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="smartsend-submenu">
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartsend_tiket'))
            <a href="{{ route('admin.smartsend-tiket') }}" class="submenu-item" id="smartsend-tiket-link">
                <i class="fas fa-ticket-alt submenu-icon"></i>
                <span>Tiket</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartsend_perjalanan'))
            <a href="{{ route('admin.smartsend-perjalanan') }}" class="submenu-item" id="smartsend-perjalanan-link">
                <i class="fas fa-route submenu-icon"></i>
                <span>Perjalanan</span>
            </a>
            @endif
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartsend_armada'))
            <a href="{{ route('admin.smartsend-armada') }}" class="submenu-item" id="smartsend-armada-link">
                <i class="fas fa-bus submenu-icon"></i>
                <span>Armada</span>
            </a>
            @endif
        </div>
        @endif

        <!-- SmartRent -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_smartrent'))
        <a href="{{ route('admin.smartrent') }}" class="menu-item" id="smartrent-link">
            <div class="menu-left">
                <i class="fas fa-car menu-icon"></i>
                <span>SmartRent</span>
            </div>
        </a>
        @endif

        <!-- Laporan -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_laporan'))
        <a href="{{ route('admin.laporan') }}" class="menu-item" id="laporan-link">
            <div class="menu-left">
                <i class="fas fa-file-alt menu-icon"></i>
                <span>Laporan</span>
            </div>
        </a>
        @endif

        <!-- Setting/Menu (with submenu) -->
        @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_pengaturan'))
        <div class="menu-item" id="setting-toggle">
            <div class="menu-left">
                <i class="fas fa-cog menu-icon"></i>
                <span>Pengaturan</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="setting-submenu">
            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_user'))
            <a href="{{ route('admin.user') }}" class="submenu-item" id="user-link">
                <i class="fas fa-user-cog submenu-icon"></i>
                <span>User</span>
            </a>
            @endif

            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasPermissionTo('view_menu'))
            <a href="{{ route('admin.menu') }}" class="submenu-item" id="menu-link">
                <i class="fas fa-bars submenu-icon"></i>
                <span>Menu</span>
            </a>
            @endif
        </div>
        @endif
    </div>

    <!-- User Profile di Sidebar -->
    <div class="sidebar-user">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h4>{{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : 'User' }}</h4>
                <p>{{ Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasRole('admin_pusat') ? 'Admin Pusat' : 'Admin Cabang' }}</p>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="#" class="logout-button" id="logoutBtn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    <!-- Header di Content -->
    <div class="content-header">
        <h1 class="page-title" id="page-title">
            <i id="page-icon"></i>
            <span id="page-title-text">Dashboard</span>
        </h1>
        <div class="user-info-top">
            <i class="fas fa-user-circle"></i>
            <span>{{ Auth::guard('admin')->user() && Auth::guard('admin')->user()->hasRole('admin_pusat') ? 'Admin Pusat' : 'Admin Cabang' }}</span>
        </div>
    </div>

    @yield('content')
</main>

<script>
    // Data untuk mapping halaman ke judul dan icon
    const pageData = {
        'dashboard': { title: 'Dashboard', icon: 'fas fa-chart-bar' },
        'profileperusahaan': { title: 'Profile Perusahaan', icon: 'fas fa-building' },
        'pusat': { title: 'Master Data - Pusat', icon: 'fas fa-city' },
        'cabangperusahaan': { title: 'Master Data - Cabang', icon: 'fas fa-code-branch' },
        'outlet': { title: 'Master Data - Outlet', icon: 'fas fa-store' },
        'promo': { title: 'Master Data - Promo', icon: 'fas fa-tag' },
        'kontak': { title: 'Master Data - Kontak', icon: 'fas fa-address-book' },
        'artikel': { title: 'Master Data - Artikel', icon: 'fas fa-newspaper' },
        'tarif': { title: 'Master Data - Tarif', icon: 'fas fa-money-bill-wave' },
        'armada': { title: 'Master Data - Armada', icon: 'fas fa-bus' },
        'driver': { title: 'Master Data - Driver', icon: 'fas fa-user-tie' },
        'pegawai': { title: 'Master Data - Pegawai', icon: 'fas fa-users' },
        'rute': { title: 'Master Data - Rute', icon: 'fas fa-route' },
        'jadwal': { title: 'Master Data - Jadwal', icon: 'fas fa-calendar-alt' },
        'smartsend-transaksi': { title: 'Transaksi - SmartSend', icon: 'fas fa-shopping-cart' },
        'perjalanan': { title: 'Transaksi - Perjalanan', icon: 'fas fa-route' },
        'armada-transaksi': { title: 'Transaksi - Armada', icon: 'fas fa-bus' },
        'smartsend-tiket': { title: 'SmartSend - Tiket', icon: 'fas fa-ticket-alt' },
        'smartsend-perjalanan': { title: 'SmartSend - Perjalanan', icon: 'fas fa-route' },
        'smartsend-armada': { title: 'SmartSend - Armada', icon: 'fas fa-bus' },
        'smartrent': { title: 'SmartRent', icon: 'fas fa-car' },
        'laporan': { title: 'Laporan', icon: 'fas fa-file-alt' },
        'user': { title: 'Pengaturan - User', icon: 'fas fa-user-cog' },
        'menu': { title: 'Pengaturan - Menu', icon: 'fas fa-bars' }
    };

    // Fungsi untuk update page title berdasarkan halaman
    function updatePageTitle(pageKey) {
        const pageTitleEl = document.getElementById('page-title-text');
        const pageIconEl = document.getElementById('page-icon');

        if (pageData[pageKey]) {
            pageTitleEl.textContent = pageData[pageKey].title;
            pageIconEl.className = pageData[pageKey].icon;
        } else {
            // Default ke dashboard
            pageTitleEl.textContent = 'Dashboard';
            pageIconEl.className = 'fas fa-chart-bar';
        }
    }

    // Fungsi untuk toggle submenu
    function toggleSubmenu(submenuId, arrowElement = null) {
        const submenu = document.getElementById(submenuId);
        submenu.classList.toggle('open');

        if (arrowElement) {
            arrowElement.classList.toggle('rotated');
        }
    }

    // Fungsi untuk toggle nested submenu
    function toggleNestedSubmenu(parentItemId, submenuId) {
        const parentItem = document.getElementById(parentItemId);
        const submenu = document.getElementById(submenuId);
        const arrow = parentItem.querySelector('.submenu-arrow');

        submenu.classList.toggle('open');
        arrow.classList.toggle('rotated');

        // Close other nested submenus at the same level
        const allNestedSubmenus = document.querySelectorAll('.nested-submenu');
        allNestedSubmenus.forEach(nested => {
            if (nested.id !== submenuId && nested.classList.contains('open')) {
                nested.classList.remove('open');
                const nestedArrow = nested.parentElement.querySelector('.submenu-arrow');
                if (nestedArrow) nestedArrow.classList.remove('rotated');
            }
        });
    }

    // Fungsi untuk mengatur menu aktif berdasarkan URL
    function setActiveMenu() {
        const menuLinks = document.querySelectorAll('.menu-item');
        const submenuLinks = document.querySelectorAll('.submenu-item');

        // Reset semua menu aktif
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });
        submenuLinks.forEach(link => link.classList.remove('active'));

        // Buka submenu yang sesuai dengan halaman aktif
        const currentPath = window.location.pathname;

        // Dashboard
        if (currentPath.includes('dashboard') || currentPath.endsWith('/admin') || currentPath.endsWith('/admin/')) {
            document.getElementById('dashboard-link').classList.add('menu-active');
            updatePageTitle('dashboard');
        }

        // Master Data (dengan Profile Perusahaan)
        else if (currentPath.includes('profile-perusahaan')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            // Set active submenu item
            document.getElementById('profile-perusahaan-link').classList.add('active');
            updatePageTitle('profileperusahaan');
        }
        else if (currentPath.includes('pusat')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('pusat-link').classList.add('active');
            updatePageTitle('pusat');
        }
        else if (currentPath.includes('cabangperusahaan') || currentPath.includes('cabang')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('cabang-link').classList.add('active');
            updatePageTitle('cabangperusahaan');
        }
        else if (currentPath.includes('outlet')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('outlet-link').classList.add('active');
            updatePageTitle('outlet');
        }
        else if (currentPath.includes('promo')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('promo-link').classList.add('active');
            updatePageTitle('promo');
        }
        else if (currentPath.includes('kontak')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('kontak-link').classList.add('active');
            updatePageTitle('kontak');
        }
        else if (currentPath.includes('artikel')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('artikel-link').classList.add('active');
            updatePageTitle('artikel');
        }
        else if (currentPath.includes('armada') && !currentPath.includes('armada-transaksi')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('armada-link').classList.add('active');
            updatePageTitle('armada');
        }
        else if (currentPath.includes('driver')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('driver-link').classList.add('active');
            updatePageTitle('driver');
        }
        else if (currentPath.includes('pegawai')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('pegawai-link').classList.add('active');
            updatePageTitle('pegawai');
        }
        else if (currentPath.includes('rute')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('rute-link').classList.add('active');
            updatePageTitle('rute');
        }
        else if (currentPath.includes('jadwal')) {
            // Buka submenu master data
            const masterDataSubmenu = document.getElementById('master-data-submenu');
            const masterDataArrow = document.getElementById('master-data-toggle').querySelector('.menu-arrow');
            masterDataSubmenu.classList.add('open');
            masterDataArrow.classList.add('rotated');

            document.getElementById('jadwal-link').classList.add('active');
            updatePageTitle('jadwal');
        }

        // Transaksi - Smartsend (termasuk tiket-perjalanan untuk backward compatibility)
        else if (currentPath.includes('smartsend-transaksi')) {
            // Buka submenu transaksi
            const transaksiSubmenu = document.getElementById('transaksi-submenu');
            const transaksiArrow = document.getElementById('transaksi-toggle').querySelector('.menu-arrow');
            transaksiSubmenu.classList.add('open');
            transaksiArrow.classList.add('rotated');

            document.getElementById('smartsend-transaksi-link').classList.add('active'); // ID diubah
            updatePageTitle('smartsend-transaksi'); // Key diubah
        }
        // Transaksi - Perjalanan
        else if (currentPath.includes('perjalanan') &&
                !currentPath.includes('tiket-perjalanan') &&
                !currentPath.includes('smartsend-perjalanan')) {
            // Buka submenu transaksi
            const transaksiSubmenu = document.getElementById('transaksi-submenu');
            const transaksiArrow = document.getElementById('transaksi-toggle').querySelector('.menu-arrow');
            transaksiSubmenu.classList.add('open');
            transaksiArrow.classList.add('rotated');

            document.getElementById('perjalanan-link').classList.add('active');
            updatePageTitle('perjalanan');
        }
        // Transaksi - Armada (termasuk tiket-armada untuk backward compatibility)
        else if (currentPath.includes('armada-transaksi') || currentPath.includes('tiket-armada')) {
            // Buka submenu transaksi
            const transaksiSubmenu = document.getElementById('transaksi-submenu');
            const transaksiArrow = document.getElementById('transaksi-toggle').querySelector('.menu-arrow');
            transaksiSubmenu.classList.add('open');
            transaksiArrow.classList.add('rotated');

            document.getElementById('armada-transaksi-link').classList.add('active');
            updatePageTitle('armada-transaksi');
        }

        // SmartSend
        else if (currentPath.includes('smartsend-tiket')) {
            // Buka submenu smartsend
            const smartsendSubmenu = document.getElementById('smartsend-submenu');
            const smartsendArrow = document.getElementById('smartsend-toggle').querySelector('.menu-arrow');
            smartsendSubmenu.classList.add('open');
            smartsendArrow.classList.add('rotated');

            document.getElementById('smartsend-tiket-link').classList.add('active');
            updatePageTitle('smartsend-tiket');
        }
        else if (currentPath.includes('smartsend-perjalanan')) {
            // Buka submenu smartsend
            const smartsendSubmenu = document.getElementById('smartsend-submenu');
            const smartsendArrow = document.getElementById('smartsend-toggle').querySelector('.menu-arrow');
            smartsendSubmenu.classList.add('open');
            smartsendArrow.classList.add('rotated');

            document.getElementById('smartsend-perjalanan-link').classList.add('active');
            updatePageTitle('smartsend-perjalanan');
        }
        else if (currentPath.includes('smartsend-armada')) {
            // Buka submenu smartsend
            const smartsendSubmenu = document.getElementById('smartsend-submenu');
            const smartsendArrow = document.getElementById('smartsend-toggle').querySelector('.menu-arrow');
            smartsendSubmenu.classList.add('open');
            smartsendArrow.classList.add('rotated');

            document.getElementById('smartsend-armada-link').classList.add('active');
            updatePageTitle('smartsend-armada');
        }

        // SmartRent
        else if (currentPath.includes('smartrent')) {
            document.getElementById('smartrent-link').classList.add('menu-active');
            updatePageTitle('smartrent');
        }

        // Laporan
        else if (currentPath.includes('laporan')) {
            document.getElementById('laporan-link').classList.add('menu-active');
            updatePageTitle('laporan');
        }

        // Setting
        else if (currentPath.includes('user')) {
            // Buka submenu setting
            const settingSubmenu = document.getElementById('setting-submenu');
            const settingArrow = document.getElementById('setting-toggle').querySelector('.menu-arrow');
            settingSubmenu.classList.add('open');
            settingArrow.classList.add('rotated');

            document.getElementById('user-link').classList.add('active');
            updatePageTitle('user');
        }
        else if (currentPath.includes('menu')) {
            // Buka submenu setting
            const settingSubmenu = document.getElementById('setting-submenu');
            const settingArrow = document.getElementById('setting-toggle').querySelector('.menu-arrow');
            settingSubmenu.classList.add('open');
            settingArrow.classList.add('rotated');

            document.getElementById('menu-link').classList.add('active');
            updatePageTitle('menu');
        }

        // Default ke dashboard
        else {
            document.getElementById('dashboard-link').classList.add('menu-active');
            updatePageTitle('dashboard');
        }
    }

    // Fungsi untuk toggle sidebar di mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');

        const hamburgerIcon = document.querySelector('#mobileHamburgerBtn i');
        if (sidebar.classList.contains('show')) {
            hamburgerIcon.classList.remove('fa-bars');
            hamburgerIcon.classList.add('fa-times');
        } else {
            hamburgerIcon.classList.remove('fa-times');
            hamburgerIcon.classList.add('fa-bars');
        }
    }

    // Fungsi untuk menutup sidebar
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const hamburgerIcon = document.querySelector('#mobileHamburgerBtn i');

        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        hamburgerIcon.classList.remove('fa-times');
        hamburgerIcon.classList.add('fa-bars');
    }

    // Fungsi untuk menampilkan modal logout
    function showLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        logoutModal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Fungsi untuk menyembunyikan modal logout
    function hideLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        logoutModal.classList.remove('show');
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Fungsi untuk logout
    function logout() {
        // Buat form untuk logout dan submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.logout") }}';

        // Tambahkan CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Tambahkan ke body dan submit
        document.body.appendChild(form);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenu();

        // Event listeners untuk toggle submenu
        document.getElementById('master-data-toggle').addEventListener('click', function() {
            toggleSubmenu('master-data-submenu', this.querySelector('.menu-arrow'));
        });

        document.getElementById('transaksi-toggle').addEventListener('click', function() {
            toggleSubmenu('transaksi-submenu', this.querySelector('.menu-arrow'));
        });

        document.getElementById('smartsend-toggle').addEventListener('click', function() {
            toggleSubmenu('smartsend-submenu', this.querySelector('.menu-arrow'));
        });

        document.getElementById('setting-toggle').addEventListener('click', function() {
            toggleSubmenu('setting-submenu', this.querySelector('.menu-arrow'));
        });

        // Logout functionality
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

        // Show logout confirmation modal
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showLogoutModal();
        });

        // Cancel logout
        cancelLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hideLogoutModal();
        });

        // Confirm logout
        confirmLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });

        // Close modal when clicking outside
        logoutModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideLogoutModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && logoutModal.classList.contains('show')) {
                hideLogoutModal();
            }
        });

        // Mobile functionality
        const mobileHamburgerBtn = document.getElementById('mobileHamburgerBtn');
        const overlay = document.getElementById('overlay');
        const menuLinks = document.querySelectorAll('.menu-item, .submenu-item');

        // Pastikan hamburger button bisa di-klik
        mobileHamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah event bubbling
            toggleSidebar();
        });

        overlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking a link on mobile
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    // Untuk item yang bukan toggle (tanpa submenu), langsung tutup sidebar
                    if (!this.id.includes('toggle')) {
                        closeSidebar();
                    }
                }
            });
        });

        // Juga tutup sidebar saat klik logout button di mobile
        logoutBtn.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                closeSidebar();
            }
        });

        // Responsive behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                closeSidebar();
            }
        });

        // Close sidebar with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
                hideLogoutModal();
            }
        });

        // Mencegah klik pada sidebar menutup sidebar itu sendiri
        document.getElementById('sidebar').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>

@stack('scripts')
</body>
</html>
