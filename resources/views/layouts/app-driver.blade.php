p<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            width: 260px;
            height: 100vh;
            background: #0d3559;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px;
            box-sizing: border-box;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 22px;
            font-weight: bold;
            color: #ff6a00;
            margin-bottom: 35px;
            line-height: 1.3;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 10px;
            margin-bottom: 10px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu-active {
            background: #ff6a00;
            color: white;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
        }

        /* ======== CONTENT ======== */
        .content {
            margin-left: 290px;
            padding: 40px;
            min-height: 100vh;
            background: #f5f7fa;
        }

        .top-profile {
            text-align: right;
            font-size: 15px;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 15px 10px;
            }

            .sidebar-title {
                font-size: 14px;
                margin-bottom: 25px;
            }

            .menu-item span {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 15px 5px;
            }

            .content {
                margin-left: 90px;
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">SMART SHUTTLE DRIVER</div>

    <a href="{{ route('driver.dashboard') ?? '#' }}" class="menu-item" id="dashboard-link">
        <div class="menu-icon"><i class="fas fa-chart-bar"></i></div>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('driver.profile') ?? '#' }}" class="menu-item" id="profile-link">
        <div class="menu-icon"><i class="fas fa-user"></i></div>
        <span>Profile Saya</span>
    </a>

    <a href="{{ route('driver.perjalanan') ?? '#' }}" class="menu-item" id="perjalanan-link">
        <div class="menu-icon"><i class="fas fa-route"></i></div>
        <span>Perjalanan</span>
    </a>
    <a href="{{ route('driver.jadwal') ?? '#' }}" class="menu-item" id="jadwal-link">
        <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
        <span>Jadwal</span>
    </a>

    <a href="{{ route('driver.laporan') ?? '#' }}" class="menu-item" id="laporan-link">
        <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
        <span>Laporan</span>
    </a>
    <a href="{{ route('driver.pengaturan') ?? '#' }}" class="menu-item" id="pengaturan-link">
        <div class="menu-icon"><i class="fas fa-cog"></i></div>
        <span>Pengaturan</span>
    </a>
    <a href="{{ route('driver.bantuan') ?? '#' }}" class="menu-item" id="bantuan-link">
        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
        <span>Bantuan</span>
    </a>

    <form method="POST" action="{{ route('driver.logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="menu-item" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
            <div class="menu-icon"><i class="fas fa-sign-out-alt"></i></div>
            <span>Logout</span>
        </button>
    </form>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    <!-- Top Profile untuk konten utama -->
    <div class="top-profile">
        <i class="fas fa-user-circle"></i>
        <span>Dimas Mahendra</span>
    </div>

    @yield('content')
</main>

<script>
    // Fungsi untuk mengatur status aktif pada menu sidebar
    function setActiveMenu() {
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });

        const currentPath = window.location.pathname;
        let activeLink = null;

        if (currentPath.includes('dashboard')) {
            activeLink = document.getElementById('dashboard-link');
        } else if (currentPath.includes('perjalanan')) {
            activeLink = document.getElementById('perjalanan-link');
        } else if (currentPath.includes('jadwal')) {
            activeLink = document.getElementById('jadwal-link');
        } else if (currentPath.includes('profile')) {
            activeLink = document.getElementById('profile-link');
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

    document.addEventListener('DOMContentLoaded', setActiveMenu);
</script>

@stack('scripts')
</body>
</html>
