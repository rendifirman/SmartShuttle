<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartShuttle - Customer')</title>
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
        }
    </style>

    @stack('styles')
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

            <li>
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
        <!-- HEADER - OTOMATIS BERDASARKAN ROUTE -->
        <div class="top-header">
            <div class="header">
                @php
                    // Mendapatkan nama route saat ini
                    $currentRoute = Route::currentRouteName();

                    // Mapping icon dan judul berdasarkan route
                    $headers = [
                        'customer.dashboardprofile' => ['icon' => 'fa-home', 'title' => 'Dashboard'],
                        'customer.profilcust' => ['icon' => 'fa-user-circle', 'title' => 'Profil Saya'],
                        'customer.riwayat' => ['icon' => 'fa-history', 'title' => 'Riwayat Pesanan'],
                        'customer.membership' => ['icon' => 'fa-crown', 'title' => 'Membership'],
                        'customer.beranda' => ['icon' => 'fa-home', 'title' => 'Beranda'],
                    ];

                    // Default jika route tidak dikenali
                    $header = $headers[$currentRoute] ?? ['icon' => 'fa-home', 'title' => 'Dashboard'];
                @endphp

                <i class="fas {{ $header['icon'] }}"></i>
                {{ $header['title'] }}
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        @yield('content')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logout confirmation
        const logoutLink = document.querySelector('.logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin logout?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Highlight active menu berdasarkan URL saat ini
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu a.menu-link');

        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href.replace(/^https?:\/\/[^\/]+/, ''))) {
                link.parentElement.classList.add('active');
            } else {
                link.parentElement.classList.remove('active');
            }
        });

        // Fallback untuk dashboard jika tidak ada menu aktif
        if (currentPath.includes('dashboard') && !document.querySelector('.menu li.active')) {
            menuLinks[0]?.parentElement?.classList.add('active');
        }
    });
</script>

@stack('scripts')
</body>
</html>
