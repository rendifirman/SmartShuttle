<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartShuttle - Customer')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary: #FF6B2C;
            --primary-soft: rgba(255,107,44,.12);
            --dark: #00274D;
            --bg: #F4F6F9;
            --text: #1f2937;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 280px;
            background: var(--dark);
            color: #fff;
            position: fixed;
            height: 100vh;
            padding: 0;
            box-shadow: 4px 0 18px rgba(0,0,0,.08);
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(0);
            display: flex;
            flex-direction: column;
            top: 0;
            left: 0;
        }

        .sidebar.mobile-hidden {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* SIDEBAR CONTENT - Untuk scrollable content */
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            color: var(--primary);
            margin-bottom: 30px;
            cursor: pointer;
            letter-spacing: .8px;
            padding: 10px 0;
        }

        .menu {
            list-style: none;
            margin-bottom: 20px;
            flex: 1;
        }

        .menu li {
            margin-bottom: 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            transition: all .25s ease;
            font-size: 15px;
        }

        .menu-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .menu-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .menu li.active .menu-link {
            background: var(--primary-soft);
            color: #fff;
            position: relative;
        }

        .menu li.active .menu-link::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 4px;
            background: var(--primary);
            border-radius: 4px;
        }

        /* ===== Sidebar Footer ===== */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-footer .menu-link {
            font-size: 14.5px;
        }

        .beranda-link {
            color: var(--primary) !important;
        }

        .logout-link {
            color: #ff9b9b !important;
        }

        /* ================= CONTENT ================= */
        .content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 28px 32px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            width: 100%;
        }

        .content.full-width {
            margin-left: 0;
        }

        /* ================= HEADER ================= */
        .top-header {
            background: #fff;
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px rgba(0,0,0,.05);
            margin-bottom: 26px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 22px;
            font-weight: 600;
            color: var(--dark);
        }

        .header i {
            color: var(--primary);
            font-size: 22px;
        }

        .profile-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B2C, #FF8E53);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: transform .2s;
        }

        .profile-icon:hover {
            transform: scale(1.06);
        }

        /* ================= MOBILE HEADER ================= */
        .mobile-header {
            display: none;
            background: #fff;
            border-radius: 14px;
            padding: 16px 22px;
            box-shadow: 0 10px 25px rgba(0,0,0,.05);
            margin-bottom: 20px;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
        }

        .mobile-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .mobile-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger-btn {
            background: none;
            border: none;
            color: var(--dark);
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hamburger-btn:hover {
            background: var(--bg);
        }

        .mobile-logo {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            cursor: pointer;
        }

        /* ================= SIDEBAR HEADER (MOBILE) ================= */
        .sidebar-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: var(--dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header .logo {
            margin-bottom: 0;
            text-align: left;
            padding: 0;
            font-size: 20px;
        }

        .sidebar-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .sidebar-close-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 300px;
                top: 0; /* Pastikan sidebar mulai dari atas */
                height: 100vh; /* Full height */
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 20px;
                padding-top: 0; /* Hilangkan padding atas untuk mobile */
            }

            .top-header {
                display: none;
            }

            .mobile-header {
                display: flex;
                position: relative; /* Hapus sticky/absolute */
                margin-top: 0;
                border-radius: 0 0 14px 14px; /* Hanya bulatkan bawah */
            }

            .sidebar-header {
                display: flex;
            }

            .desktop-logo {
                display: none;
            }

            .sidebar-content {
                padding-top: 0;
            }

            /* Atur container untuk mobile */
            .container {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 16px;
                padding-top: 0;
            }

            .mobile-header {
                padding: 14px 18px;
                border-radius: 0 0 12px 12px;
                margin-bottom: 16px;
            }

            .hamburger-btn {
                font-size: 22px;
                width: 36px;
                height: 36px;
            }

            .mobile-logo {
                font-size: 18px;
            }

            .sidebar {
                width: 280px;
            }

            .sidebar-header {
                padding: 12px 20px;
            }

            .sidebar-header .logo {
                font-size: 18px;
            }
        }

        @media (max-width: 576px) {
            .content {
                padding: 12px;
                padding-top: 0;
            }

            .mobile-header {
                padding: 12px 16px;
                margin-bottom: 12px;
                border-radius: 0 0 10px 10px;
            }

            .sidebar {
                width: 85%;
                max-width: 280px;
            }

            .sidebar-content {
                padding: 15px;
            }

            .menu-link {
                padding: 10px 14px;
                font-size: 14px;
            }

            .menu-link i {
                font-size: 16px;
            }

            .sidebar-header {
                padding: 10px 15px;
            }
        }

        @media (max-width: 380px) {
            .mobile-header {
                padding: 10px 14px;
            }

            .mobile-logo {
                font-size: 16px;
            }

            .hamburger-btn {
                font-size: 20px;
                width: 32px;
                height: 32px;
            }

            .content {
                padding: 10px;
                padding-top: 0;
            }

            .sidebar-header .logo {
                font-size: 16px;
            }

            .sidebar-content {
                padding: 12px;
            }
        }
        
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- MOBILE HEADER -->
<div class="mobile-header">
    <div class="mobile-header-content">
        <div class="mobile-header-left">
            <button class="hamburger-btn" id="hamburgerBtn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="mobile-logo" onclick="location.href='{{ route('customer.beranda') }}'">
                SMART SHUTTLE
            </div>
        </div>
        <div class="profile-icon" onclick="location.href='{{ route('customer.profilcust') }}'">
            <i class="fa-solid fa-user"></i>
        </div>
    </div>
</div>

<div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <!-- Mobile Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo" onclick="location.href='{{ route('customer.beranda') }}'">
                SMART SHUTTLE
            </div>
            <button class="sidebar-close-btn" id="sidebarCloseBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Desktop Logo (hidden on mobile) -->
            <div class="logo desktop-logo" onclick="location.href='{{ route('customer.beranda') }}'">
                SMART SHUTTLE
            </div>

            <ul class="menu">
                <li class="{{ request()->routeIs('customer.dashboardprofile') ? 'active' : '' }}">
                    <a href="{{ route('customer.dashboardprofile') }}" class="menu-link">
                        <i class="fas fa-chart-bar menu-icon"></i>
                        Dashboard
                    </a>
                </li>

                <li class="{{ request()->routeIs('customer.profilcust') ? 'active' : '' }}">
                    <a href="{{ route('customer.profilcust') }}" class="menu-link">
                        <i class="fa-regular fa-user"></i>
                        Profil Saya
                    </a>
                </li>

                <li class="{{ request()->routeIs('customer.riwayat') ? 'active' : '' }}">
                    <a href="{{ route('customer.riwayat') }}" class="menu-link">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Riwayat Pesanan
                    </a>
                </li>

                <li class="{{ request()->routeIs('customer.membership') ? 'active' : '' }}">
                    <a href="{{ route('customer.membership') }}" class="menu-link">
                        <i class="fa-solid fa-crown"></i>
                        Membership
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="{{ route('customer.beranda') }}" class="menu-link beranda-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    Beranda
                </a>

                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>

                <a href="#" class="menu-link logout-link"
                   onclick="event.preventDefault(); if(confirm('Yakin ingin logout?')) document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content" id="content">
        <!-- Desktop Header -->
        <div class="top-header">
            <div class="header">
                @php
                    $headers = [
                        'customer.dashboardprofile' => ['icon' => 'fa-grid-2', 'title' => 'Dashboard'],
                        'customer.profilcust' => ['icon' => 'fa-user', 'title' => 'Profil Saya'],
                        'customer.riwayat' => ['icon' => 'fa-clock-rotate-left', 'title' => 'Riwayat Pesanan'],
                        'customer.membership' => ['icon' => 'fa-crown', 'title' => 'Membership'],
                        'customer.membership.payment' => ['icon' => 'fa-credit-card', 'title' => 'Pembayaran Membership'],
                        'customer.membership.pending' => ['icon' => 'fa-clock', 'title' => 'Menunggu Pembayaran'],
                        'customer.membership.form' => ['icon' => 'fa-crown', 'title' => 'Daftar Membership'],
                    ];
                    $header = $headers[Route::currentRouteName()] ?? ['icon' => 'fa-grid-2', 'title' => 'Dashboard'];
                @endphp

                <i class="fa-solid {{ $header['icon'] }}"></i>
                {{ $header['title'] }}
            </div>

            <div class="profile-icon" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        @yield('content')
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const content = document.getElementById('content');
        
        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            content.classList.add('full-width');
            document.body.style.overflow = 'hidden';
        }
        
        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            content.classList.remove('full-width');
            document.body.style.overflow = '';
        }
        
        // Toggle sidebar with hamburger button
        hamburgerBtn.addEventListener('click', openSidebar);
        
        // Close sidebar with close button
        sidebarCloseBtn.addEventListener('click', closeSidebar);
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', closeSidebar);
        
        // Close sidebar when clicking on menu links (mobile only)
        const menuLinks = document.querySelectorAll('.menu-link');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    closeSidebar();
                }
            });
        });
        
        // Close sidebar when clicking on footer links (mobile only)
        const footerLinks = document.querySelectorAll('.sidebar-footer .menu-link');
        footerLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    closeSidebar();
                }
            });
        });
        
        // Close sidebar on window resize if it's desktop
        function handleResize() {
            if (window.innerWidth > 992) {
                closeSidebar();
            }
        }
        
        // Listen for window resize
        window.addEventListener('resize', handleResize);
        
        // Close sidebar with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
        
        // Initial check on page load
        handleResize();
    });
</script>

@stack('scripts')
</body>
</html>