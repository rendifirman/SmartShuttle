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
            padding: 28px 20px 110px;
            box-shadow: 4px 0 18px rgba(0,0,0,.08);
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            color: var(--primary);
            margin-bottom: 36px;
            cursor: pointer;
            letter-spacing: .8px;
        }

        .menu {
            list-style: none;
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
            position: absolute;
            bottom: 28px;
            left: 20px;
            right: 20px;
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

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .sidebar-footer {
                position: relative;
                bottom: auto;
                left: auto;
                right: auto;
                margin-top: 20px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo" onclick="location.href='{{ route('customer.beranda') }}'">
            SMART SHUTTLE
        </div>

        <ul class="menu">
            <li class="{{ request()->routeIs('customer.dashboardprofile') ? 'active' : '' }}">
                <a href="{{ route('customer.dashboardprofile') }}" class="menu-link">
                    <i class="fa-solid fa-grid-2"></i>
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
    </aside>

    <!-- CONTENT -->
    <main class="content">
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

@stack('scripts')
</body>
</html>
