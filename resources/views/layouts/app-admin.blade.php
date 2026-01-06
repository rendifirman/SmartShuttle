<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMART SHUTTLE - Admin Pusat')</title>

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
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }

        .mobile-header-content {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }

        .mobile-logo {
            color: #ff6a00;
            font-size: 16px;
            font-weight: bold;
            margin-left: 10px;
            flex: 1;
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
        }

        @media (min-width: 993px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        @media (max-width: 480px) {
            .mobile-header {
                height: 55px;
                padding: 12px 15px;
            }

            .mobile-logo {
                font-size: 14px;
                margin-left: 8px;
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
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Mobile Header -->
<div class="mobile-header" id="mobileHeader">
    <div class="mobile-header-content">
        <button class="mobile-hamburger" id="mobileHamburgerBtn">
            <i class="fas fa-bars"></i>
        </button>
        <span class="mobile-logo">SMART SHUTTLE ADMIN</span>
        <div style="width: 40px;"></div>
    </div>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <div class="sidebar-title">SMART SHUTTLE<br><span>ADMIN PUSAT</span></div>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="menu-item" id="dashboard-link">
            <div class="menu-left">
                <i class="fas fa-chart-bar menu-icon"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <!-- Master Data (with submenu) -->
        <div class="menu-item" id="master-data-toggle">
            <div class="menu-left">
                <i class="fas fa-database menu-icon"></i>
                <span>Master Data</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="master-data-submenu">
            <!-- PROFILE PERUSAHAAN - Ditambahkan di paling atas -->
            <a href="{{ route('admin.profileperusahaan') }}" class="submenu-item" id="profile-perusahaan-link">
                <i class="fas fa-building submenu-icon"></i>
                <span>Profile Perusahaan</span>
            </a>

            <a href="{{ route('admin.pusat') }}" class="submenu-item" id="pusat-link">
                <i class="fas fa-city submenu-icon"></i>
                <span>Pusat</span>
            </a>
            <a href="{{ route('admin.cabangperusahaan') }}" class="submenu-item" id="cabang-link">
                <i class="fas fa-code-branch submenu-icon"></i>
                <span>Cabang</span>
            </a>
            <a href="{{ route('admin.armada') }}" class="submenu-item" id="armada-link">
                <i class="fas fa-bus submenu-icon"></i>
                <span>Armada</span>
            </a>
            <a href="{{ route('admin.driver') }}" class="submenu-item" id="driver-link">
                <i class="fas fa-user-tie submenu-icon"></i>
                <span>Driver</span>
            </a>
            <a href="{{ route('admin.pegawai') }}" class="submenu-item" id="pegawai-link">
                <i class="fas fa-users submenu-icon"></i>
                <span>Pegawai</span>
            </a>
            <a href="{{ route('admin.rute') }}" class="submenu-item" id="rute-link">
                <i class="fas fa-route submenu-icon"></i>
                <span>Rute</span>
            </a>
        </div>

        <!-- Transaksi (with submenu) -->
        <div class="menu-item" id="transaksi-toggle">
            <div class="menu-left">
                <i class="fas fa-exchange-alt menu-icon"></i>
                <span>Transaksi</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="transaksi-submenu">
            <div class="submenu-item" id="tiket-toggle">
                <div class="menu-left">
                    <i class="fas fa-ticket-alt submenu-icon"></i>
                    <span>Tiket</span>
                </div>
                <i class="fas fa-chevron-right submenu-arrow"></i>
            </div>

            <div class="submenu nested-submenu" id="tiket-submenu">
                <a href="{{ route('admin.tiket-perjalanan') }}" class="submenu-item" id="tiket-perjalanan-link">
                    <i class="fas fa-route submenu-icon"></i>
                    <span>Perjalanan</span>
                </a>
                <a href="{{ route('admin.tiket-armada') }}" class="submenu-item" id="tiket-armada-link">
                    <i class="fas fa-bus submenu-icon"></i>
                    <span>Armada</span>
                </a>
            </div>
        </div>

        <!-- SmartSend (with submenu) -->
        <div class="menu-item" id="smartsend-toggle">
            <div class="menu-left">
                <i class="fas fa-shipping-fast menu-icon"></i>
                <span>SmartSend</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="smartsend-submenu">
            <a href="{{ route('admin.smartsend-tiket') }}" class="submenu-item" id="smartsend-tiket-link">
                <i class="fas fa-ticket-alt submenu-icon"></i>
                <span>Tiket</span>
            </a>
            <a href="{{ route('admin.smartsend-perjalanan') }}" class="submenu-item" id="smartsend-perjalanan-link">
                <i class="fas fa-route submenu-icon"></i>
                <span>Perjalanan</span>
            </a>
            <a href="{{ route('admin.smartsend-armada') }}" class="submenu-item" id="smartsend-armada-link">
                <i class="fas fa-bus submenu-icon"></i>
                <span>Armada</span>
            </a>
        </div>

        <!-- SmartRent -->
        <a href="{{ route('admin.smartrent') }}" class="menu-item" id="smartrent-link">
            <div class="menu-left">
                <i class="fas fa-car menu-icon"></i>
                <span>SmartRent</span>
            </div>
        </a>

        <!-- Laporan -->
        <a href="{{ route('admin.laporan') }}" class="menu-item" id="laporan-link">
            <div class="menu-left">
                <i class="fas fa-file-alt menu-icon"></i>
                <span>Laporan</span>
            </div>
        </a>

        <!-- Setting/Menu (with submenu) -->
        <div class="menu-item" id="setting-toggle">
            <div class="menu-left">
                <i class="fas fa-cog menu-icon"></i>
                <span>Pengaturan</span>
            </div>
            <i class="fas fa-chevron-down menu-arrow"></i>
        </div>

        <div class="submenu" id="setting-submenu">
            <a href="{{ route('admin.user') }}" class="submenu-item" id="user-link">
                <i class="fas fa-user-cog submenu-icon"></i>
                <span>User</span>
            </a>
            <a href="{{ route('admin.menu') }}" class="submenu-item" id="menu-link">
                <i class="fas fa-bars submenu-icon"></i>
                <span>Menu</span>
            </a>
        </div>
    </div>

    <!-- User Profile di Sidebar -->
    <div class="sidebar-user">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h4>Admin Pusat</h4>
                <p>Super Administrator</p>
            </div>
        </div>
    </div>
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
            <span>Admin Pusat</span>
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
        'armada': { title: 'Master Data - Armada', icon: 'fas fa-bus' },
        'driver': { title: 'Master Data - Driver', icon: 'fas fa-user-tie' },
        'pegawai': { title: 'Master Data - Pegawai', icon: 'fas fa-users' },
        'rute': { title: 'Master Data - Rute', icon: 'fas fa-route' },
        'tiket-perjalanan': { title: 'Transaksi Tiket - Perjalanan', icon: 'fas fa-route' },
        'tiket-armada': { title: 'Transaksi Tiket - Armada', icon: 'fas fa-bus' },
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
        else if (currentPath.includes('profileperusahaan')) {
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
        else if (currentPath.includes('armada')) {
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

        // Transaksi
        else if (currentPath.includes('tiket-perjalanan')) {
            // Buka submenu transaksi
            const transaksiSubmenu = document.getElementById('transaksi-submenu');
            const transaksiArrow = document.getElementById('transaksi-toggle').querySelector('.menu-arrow');
            transaksiSubmenu.classList.add('open');
            transaksiArrow.classList.add('rotated');

            // Buka nested submenu tiket
            const tiketSubmenu = document.getElementById('tiket-submenu');
            const tiketArrow = document.getElementById('tiket-toggle').querySelector('.submenu-arrow');
            tiketSubmenu.classList.add('open');
            tiketArrow.classList.add('rotated');

            document.getElementById('tiket-perjalanan-link').classList.add('active');
            updatePageTitle('tiket-perjalanan');
        }
        else if (currentPath.includes('tiket-armada')) {
            // Buka submenu transaksi
            const transaksiSubmenu = document.getElementById('transaksi-submenu');
            const transaksiArrow = document.getElementById('transaksi-toggle').querySelector('.menu-arrow');
            transaksiSubmenu.classList.add('open');
            transaksiArrow.classList.add('rotated');

            // Buka nested submenu tiket
            const tiketSubmenu = document.getElementById('tiket-submenu');
            const tiketArrow = document.getElementById('tiket-toggle').querySelector('.submenu-arrow');
            tiketSubmenu.classList.add('open');
            tiketArrow.classList.add('rotated');

            document.getElementById('tiket-armada-link').classList.add('active');
            updatePageTitle('tiket-armada');
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

        // Event listener untuk nested submenu tiket
        document.getElementById('tiket-toggle').addEventListener('click', function() {
            toggleNestedSubmenu('tiket-toggle', 'tiket-submenu');
        });

        // Mobile functionality
        const mobileHamburgerBtn = document.getElementById('mobileHamburgerBtn');
        const overlay = document.getElementById('overlay');
        const menuLinks = document.querySelectorAll('.menu-item, .submenu-item');

        mobileHamburgerBtn.addEventListener('click', toggleSidebar);
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
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
