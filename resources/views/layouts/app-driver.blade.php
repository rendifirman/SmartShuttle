<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART SHUTTLE - Driver')</title>

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
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-active {
            background: #ff6a00 !important;
            color: #0d3559 !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(255, 106, 0, 0.3);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
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
            border: none;
            width: calc(100% - 50px);
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
                width: calc(100% - 30px);
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
        <span class="mobile-logo">SMART SHUTTLE DRIVER</span>
    </div>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <div class="sidebar-title">SMART SHUTTLE<br><span>DRIVER</span></div>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">
        <a href="{{ route('driver.dashboard') ?? '#' }}" class="menu-item" id="dashboard-link">
            <i class="fas fa-chart-bar menu-icon"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('driver.profile') ?? '#' }}" class="menu-item" id="profile-link">
            <i class="fas fa-user-circle"></i>
            <span>Profile Saya</span>
        </a>

        <a href="{{ route('driver.perjalanan') ?? '#' }}" class="menu-item" id="perjalanan-link">
            <i class="fas fa-route menu-icon"></i>
            <span>Perjalanan</span>
        </a>
        
        <a href="{{ route('driver.jadwal') ?? '#' }}" class="menu-item" id="jadwal-link">
            <i class="fas fa-calendar-alt menu-icon"></i>
            <span>Jadwal</span>
        </a>

        <a href="{{ route('driver.laporan') ?? '#' }}" class="menu-item" id="laporan-link">
            <i class="fas fa-file-alt menu-icon"></i>
            <span>Laporan</span>
        </a>
        
        <a href="{{ route('driver.pengaturan') ?? '#' }}" class="menu-item" id="pengaturan-link">
            <i class="fas fa-cog menu-icon"></i>
            <span>Pengaturan</span>
        </a>
        
        <a href="{{ route('driver.bantuan') ?? '#' }}" class="menu-item" id="bantuan-link">
            <i class="fas fa-headset"></i>
            <span>Bantuan</span>
        </a>
    </div>

    <!-- User Profile di Sidebar -->
    <div class="sidebar-user">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h4>{{ auth()->guard('driver')->user()?->name ?? 'Driver' }}</h4>
                <p>Driver</p>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <button type="button" class="logout-button" id="logoutBtn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </button>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    @yield('content')
</main>

<script>
    // Fungsi untuk mengatur menu aktif berdasarkan URL
    function setActiveMenu() {
        const menuLinks = document.querySelectorAll('.menu-item');
        
        // Reset semua menu aktif
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });

        const currentPath = window.location.pathname;
        let activeLink = null;

        if (currentPath.includes('dashboard')) {
            activeLink = document.getElementById('dashboard-link');
        } else if (currentPath.includes('profile')) {
            activeLink = document.getElementById('profile-link');
        } else if (currentPath.includes('perjalanan')) {
            activeLink = document.getElementById('perjalanan-link');
        } else if (currentPath.includes('jadwal')) {
            activeLink = document.getElementById('jadwal-link');
        } else if (currentPath.includes('laporan')) {
            activeLink = document.getElementById('laporan-link');
        } else if (currentPath.includes('pengaturan')) {
            activeLink = document.getElementById('pengaturan-link');
        } else if (currentPath.includes('bantuan')) {
            activeLink = document.getElementById('bantuan-link');
        }

        if (!activeLink) {
            activeLink = document.getElementById('dashboard-link');
        }

        if (activeLink) {
            activeLink.classList.add('menu-active');
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
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menyembunyikan modal logout
    function hideLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        logoutModal.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Fungsi untuk logout
    function logout() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("driver.logout") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenu();

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
        const menuLinks = document.querySelectorAll('.menu-item');

        // Hamburger button click
        mobileHamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });

        // Overlay click
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking a link on mobile
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    closeSidebar();
                }
            });
        });

        // Close sidebar when clicking logout button di mobile
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