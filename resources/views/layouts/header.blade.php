<!-- resources/views/layouts/header.blade.php -->
<style>
    /* CSS Variables untuk Header */
    :root {
        --header-primary: #123352;
        --header-secondary: #FF581E;
        --header-secondary-light: #FF8E53;
    }

    /* ========== NAVBAR UTAMA ========== */
    .custom-navbar {
        background: transparent;
        padding: 15px 5%;
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000 !important;
        transition: all 0.4s ease;
        min-height: 70px;
        transform: translateY(0);
        box-shadow: none;
        width: 100%;
        max-width: 100vw;
        transform: translateZ(0);
        will-change: transform;
    }

    .custom-navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1200px;
        position: relative;
    }

    /* Panel Oval untuk Navbar */
    .nav-panel {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 50px;
        padding: 8px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        overflow: visible !important;
    }

    .nav-brand img {
        height: 30px;
        width: auto;
        max-width: 100%;
    }

    /* MOBILE MENU TOGGLE - HAMBURGER MENU */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--header-primary);
        cursor: pointer;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1001;
        position: relative;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .mobile-menu-toggle:hover {
        background-color: rgba(18, 51, 82, 0.1);
    }

    .mobile-menu-toggle.active i {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    .mobile-menu-toggle i {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-menu-toggle.active .fa-bars {
        opacity: 0;
        transform: rotate(90deg);
    }

    .mobile-menu-toggle.active .fa-times {
        opacity: 1;
        transform: rotate(0deg);
    }

    .mobile-menu-toggle .fa-times {
        opacity: 0;
        position: absolute;
        transform: rotate(-90deg);
    }

    .mobile-sidebar {
    padding-top: 70px;
}



    /* NAV MENU DESKTOP */
    .nav-menu {
        display: flex;
        justify-content: center;
        flex: 1;
    }

    .nav-links {
        display: flex;
        gap: 15px;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-links a {
        text-decoration: none;
        color: var(--header-primary);
        font-weight: 500;
        font-size: 0.85rem;
        transition: color 0.3s;
        position: relative;
        white-space: nowrap;
        padding: 5px 8px;
        font-family: 'Roboto', sans-serif;
    }

    .nav-links a:hover {
        color: var(--header-secondary);
    }

    .nav-links a.active {
        color: var(--header-secondary);
    }

    .nav-links a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 0;
        background-color: var(--header-secondary);
        transition: width 0.3s;
    }

    .nav-links a:hover::after,
    .nav-links a.active::after {
        width: 100%;
    }

    .nav-auth {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .btn-login {
        background-color: var(--header-primary);
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 20px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
    }

    .btn-login:hover {
        background-color: var(--header-secondary);
        transform: translateY(-2px);
        text-decoration: none;
        color: white;
    }

    /* ========== AVATAR STYLING ========== */
    .profile-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--header-secondary), var(--header-secondary-light));
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
        font-size: 14px;
        text-transform: uppercase;
        font-family: 'Roboto', sans-serif;
        letter-spacing: 0.5px;
        min-width: 36px;
        min-height: 36px;
        overflow: hidden;
    }

    .profile-avatar.initials-only {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    .mobile-profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--header-secondary), var(--header-secondary-light));
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
        flex-shrink: 0;
        font-size: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        min-width: 50px;
        min-height: 50px;
        overflow: hidden;
    }

    .mobile-profile-avatar.initials-only {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .mobile-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    /* ========== PROFILE DROPDOWN ========== */
    .profile-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        padding: 6px 12px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        font-family: 'Roboto', sans-serif;
        min-height: 44px;
        flex-shrink: 0;
    }

    .profile-btn:hover,
    .profile-btn:focus {
        outline: none;
        background: rgba(0, 33, 94, 0.05);
    }

    .profile-btn.active {
        background: rgba(18, 51, 82, 0.1);
    }

    .profile-name {
        font-size: 12px;
        color: var(--header-primary);
        font-weight: 600;
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-family: 'Roboto', sans-serif;
    }

    /* DROPDOWN MENU - DESKTOP */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 180px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        padding: 8px 0;
        border: 1px solid #e2e8f0;
        animation: fadeIn 0.2s ease-out;
        font-family: 'Roboto', sans-serif;
        overflow: hidden;
        z-index: 1003 !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-menu a {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        color: var(--header-primary);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Roboto', sans-serif;
    }

    .dropdown-menu a i {
        margin-right: 10px;
        width: 16px;
        text-align: center;
    }

    .dropdown-menu a:hover {
        background-color: rgba(255, 88, 30, 0.08);
        color: var(--header-secondary);
    }

    .dropdown-menu form {
        margin: 0;
        border-top: 1px solid #f1f5f9;
        padding-top: 4px;
    }

    .dropdown-menu button[type="submit"] {
        display: flex;
        align-items: center;
        width: 100%;
        text-align: left;
        padding: 10px 16px;
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .dropdown-menu button[type="submit"] i {
        margin-right: 10px;
        width: 16px;
        text-align: center;
    }

    .dropdown-menu button[type="submit"]:hover {
        background-color: rgba(239, 68, 68, 0.08);
        color: #dc2626;
    }

    .dropdown-menu.show {
        display: block !important;
    }

    /* ===== MOBILE SIDEBAR ===== */
    .mobile-sidebar-overlay,
    .mobile-sidebar {
        display: none;
    }

    /* ==================== RESPONSIVE STYLES ==================== */
    @media (max-width: 768px) {
        /* NAVBAR MOBILE */
        .custom-navbar {
            padding: 12px 4%;
            min-height: 60px;
            width: 100vw;
            max-width: 100vw;
            z-index: 1000;
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
        }
        
        .custom-navbar.scrolled {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
        }

        .nav-container {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        .nav-panel {
            position: relative;
            padding: 8px 15px;
            border-radius: 25px;
            flex-wrap: nowrap;
            overflow: visible !important;
            width: 100%;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-height: 56px;
            justify-content: space-between;
        }

        /* HAMBURGER MENU TOGGLE - DI KIRI */
        .mobile-menu-toggle {
            display: flex;
            order: 1;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            font-size: 1.375rem;
            margin-right: 5px;
        }

        /* LOGO - TENGAH */
        .nav-brand {
            order: 2;
            flex-shrink: 0;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-brand img {
            height: 28px;
        }

        /* SEMBUNYIKAN NAV MENU DI MOBILE */
        .nav-menu {
            display: none !important;
        }

        /* PROFILE BUTTON - DI KANAN */
        .nav-auth {
            order: 3;
            flex-shrink: 0;
            margin-left: 0;
        }

        .profile-wrapper {
            position: relative;
        }

        .profile-btn {
            padding: 4px 8px;
            min-height: 40px;
            border-radius: 20px;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-width: 32px;
            min-height: 32px;
        }

        .profile-name {
            display: none !important; /* Sembunyikan nama di mobile */
        }

        .mobile-profile-avatar {
            width: 45px;
            height: 45px;
            font-size: 18px;
            min-width: 45px;
            min-height: 45px;
        }

        /* DROPDOWN MENU MOBILE - MUNCUL DI BAWAH PROFILE BUTTON */
        .dropdown-menu {
            position: fixed !important;
            top: 70px !important; /* Posisi tepat di bawah navbar */
            right: 20px !important;
            left: auto !important;
            bottom: auto !important;
            min-width: 180px;
            max-width: calc(100vw - 40px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid #e5e7eb;
            animation: slideDownMobile 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: top right;
            border-radius: 14px;
            z-index: 1001 !important;
            display: none;
        }

        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 15px;
            width: 16px;
            height: 16px;
            background: white;
            transform: rotate(45deg);
            border-left: 1px solid #e5e7eb;
            border-top: 1px solid #e5e7eb;
        }

        @keyframes slideDownMobile {
            from { 
                opacity: 0; 
                transform: translateY(-10px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        .dropdown-menu.show {
            display: block !important;
        }

        .dropdown-menu a,
        .dropdown-menu button[type="submit"] {
            padding: 12px 16px;
            font-size: 14px;
            min-height: 46px;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button[type="submit"]:hover {
            background-color: rgba(255, 88, 30, 0.1);
        }

        /* Tombol login di mobile */
        .btn-login {
            padding: 8px 16px;
            font-size: 0.85rem;
            min-height: 36px;
            display: flex;
            align-items: center;
        }

        /* TAMPILKAN SIDEBAR COMPONENTS DI MOBILE */
        .mobile-sidebar-overlay {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            backdrop-filter: blur(3px);
        }

        .mobile-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-sidebar {
            display: block;
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: var(--header-primary);
            color: white;
            z-index: 999;
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .mobile-sidebar.active {
            transform: translateX(280px);
        }

        .mobile-sidebar-header {
            padding: 25px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-sidebar-logo {
            font-size: 22px;
            font-weight: 700;
            color: var(--header-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mobile-sidebar-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 4px;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .mobile-sidebar-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--header-secondary);
        }

        .mobile-sidebar-content {
            flex: 1;
            padding: 20px 0;
        }

        .mobile-nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-menu li {
            padding: 0;
            margin: 6px 15px;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }

        .mobile-nav-menu li:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .mobile-nav-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            border-left: 3px solid transparent;
            position: relative;
            cursor: pointer;
        }

        .mobile-nav-menu a:hover {
            color: white;
            border-left-color: rgba(255, 88, 30, 0.5);
        }

        .mobile-nav-menu li.active {
            background: linear-gradient(90deg, rgba(255, 88, 30, 0.15), rgba(255, 88, 30, 0.05));
        }

        .mobile-nav-menu li.active a {
            background: var(--header-secondary) !important;
            color: white !important;
            border-radius: 12px;
            border-left-color: var(--header-secondary);
            box-shadow: 0 4px 12px rgba(255, 88, 30, 0.25);
            font-weight: 600;
        }

        .mobile-nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .mobile-sidebar-bottom {
            margin-top: auto;
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .mobile-profile-section {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin: 0 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .mobile-profile-section:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-profile-info {
            flex: 1;
            min-width: 0;
        }

        .mobile-profile-name {
            font-weight: 600;
            color: white;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mobile-profile-email {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    @media (max-width: 480px) {
        .custom-navbar {
            padding: 10px 3%;
        }

        .nav-panel {
            padding: 6px 12px;
            border-radius: 20px;
            min-height: 52px;
        }

        .nav-brand img {
            height: 26px;
        }

        .mobile-sidebar {
            width: 260px;
            left: -260px;
        }

        .mobile-sidebar.active {
            transform: translateX(260px);
        }

        .mobile-sidebar-header {
            padding: 20px 15px;
        }

        .mobile-sidebar-logo {
            font-size: 20px;
        }

        .mobile-nav-menu a {
            padding: 14px 18px;
            font-size: 14px;
        }

        .mobile-profile-avatar {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }

        .mobile-profile-name {
            font-size: 13px;
        }

        .mobile-profile-email {
            font-size: 11px;
        }

        .mobile-menu-toggle {
            width: 36px;
            height: 36px;
            font-size: 1.25rem;
        }
        
        .dropdown-menu {
            top: 65px !important;
            right: 15px !important;
            min-width: 160px;
        }
        
        .profile-btn {
            padding: 4px 6px;
        }
        
        .profile-avatar {
            width: 30px;
            height: 30px;
            font-size: 12px;
            min-width: 30px;
            min-height: 30px;
        }
        
        .dropdown-menu a,
        .dropdown-menu button[type="submit"] {
            padding: 10px 14px;
            min-height: 44px;
            font-size: 13px;
        }
    }

    /* Scrollbar Styling untuk Sidebar */
    .mobile-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .mobile-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }

    .mobile-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 88, 30, 0.4);
        border-radius: 8px;
    }

    .mobile-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 88, 30, 0.6);
    }
</style>

<!-- Custom Navbar -->
<nav class="custom-navbar" id="navbar">
    <div class="nav-container">
        <div class="nav-panel">
            <!-- HAMBURGER MENU TOGGLE (Hanya tampil di mobile) -->
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
            </button>
            
            <div class="nav-brand">
                <img src="{{ asset('/images/smartshuttlelogo.png') }}" alt="Smart Shuttle">
            </div>
            
            <!-- NAV MENU (Tampil di desktop, disembunyikan di mobile) -->
            <div class="nav-menu" id="nav-menu">
                <ul class="nav-links">
                    @php
                        $currentRoute = Route::currentRouteName();
                        $currentPath = request()->path();
                    @endphp
                    
                    <li><a href="/customer/beranda" 
                           class="{{ $currentRoute == 'customer.beranda' || $currentPath == 'customer/beranda' ? 'active' : '' }}">
                           Beranda
                       </a>
                    </li>
                    
                    <li><a href="{{ route('customer.search') }}" 
                           class="{{ $currentRoute == 'customer.search' || str_contains($currentPath, 'search') ? 'active' : '' }}">
                           Cari Tiket
                       </a>
                    </li>
                    
                    <li><a href="{{ route('customer.outlet') }}" 
                           class="{{ $currentRoute == 'customer.outlet' || str_contains($currentPath, 'outlet') ? 'active' : '' }}">
                           Outlet
                       </a>
                    </li>
                    
                    <li><a href="{{ route('customer.smartsend') }}" 
                           class="{{ $currentRoute == 'customer.smartsend' || str_contains($currentPath, 'smartsend') ? 'active' : '' }}">
                           Kirim Paket
                       </a>
                    </li>
                    
                    <li><a href="#" onclick="alert('Fitur Sewa Armada akan segera hadir!'); return false;" 
                           class="{{ $currentRoute == 'customer.sewa-armada' ? 'active' : '' }}">
                           Sewa Armada
                       </a>
                    </li>
                    
                    <li><a href="{{ route('customer.contact') }}" 
                           class="{{ $currentRoute == 'customer.contact' || str_contains($currentPath, 'contact') ? 'active' : '' }}">
                           Kontak
                       </a>
                    </li>
                    
                    <li><a href="{{ route('customer.cek-reservasi') }}" 
                           class="{{ $currentRoute == 'customer.cek-reservasi' || str_contains($currentPath, 'cek-reservasi') ? 'active' : '' }}">
                           Cek Reservasi
                       </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-auth">
                <!-- BAGIAN AVATAR DI NAVBAR -->
                @if(session()->has('user') && isset(session('user')['id']))
                    @php
                        $user = session('user');
                        $userName = $user['name'] ?? 'User';
                        $avatarUrl = $user['avatar'] ?? null;
                        
                        // Generate initials dari nama user
                        $initials = 'GU'; // Guest User default
                        if (!empty($userName)) {
                            $nameParts = explode(' ', trim($userName));
                            if (count($nameParts) >= 2) {
                                // Jika ada 2 kata atau lebih: ambil huruf pertama dari kata pertama dan terakhir
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                            } else {
                                // Jika hanya satu kata: ambil 2 huruf pertama
                                $initials = strtoupper(substr($userName, 0, 2));
                            }
                        }
                        
                        // Debug: cek apa yang ada di avatarUrl
                        // dd($avatarUrl, session()->all());
                        
                        // Tentukan apakah ada avatar yang valid
                        $hasValidAvatar = false;
                        
                        if ($avatarUrl) {
                            // Cek jika avatar adalah path storage (format: 'avatars/filename.jpg')
                            if (str_starts_with($avatarUrl, 'avatars/')) {
                                $hasValidAvatar = true;
                                $avatarUrl = asset('storage/' . $avatarUrl);
                            }
                            // Cek jika avatar sudah URL lengkap
                            elseif (filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
                                $hasValidAvatar = true;
                            }
                            // Cek jika avatar adalah path public
                            elseif (str_starts_with($avatarUrl, 'public/avatars/')) {
                                $hasValidAvatar = true;
                                $avatarUrl = asset(str_replace('public/', 'storage/', $avatarUrl));
                            }
                        }
                    @endphp
                    
                    <div class="profile-wrapper">
                        <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                            @if($hasValidAvatar && $avatarUrl)
                                <span class="profile-avatar">
                                    <img src="{{ $avatarUrl }}" 
                                         alt="Avatar {{ $userName }}"
                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='{{ $initials }}'; this.parentElement.classList.add('initials-only');">
                                </span>
                            @else
                                <span class="profile-avatar initials-only">{{ $initials }}</span>
                            @endif
                            
                            <span class="profile-name">
                                {{ strlen($userName) > 12 ? substr($userName, 0, 12).'...' : $userName }}
                            </span>
                            <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div id="dropdown-menu" class="dropdown-menu">
                            <a href="{{ route('customer.dashboardprofile') }}">
                                <i class="fas fa-user-circle"></i>
                                Profil
                            </a>
                            <a href="{{ route('customer.riwayat') }}">
                                <i class="fas fa-history"></i>
                                Riwayat
                            </a>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="btn-login" id="login-btn">
                        <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i>
                        Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- MOBILE SIDEBAR OVERLAY (Hanya tampil di mobile) -->
<div class="mobile-sidebar-overlay" id="mobile-sidebar-overlay"></div>

<!-- MOBILE SIDEBAR (Hanya tampil di mobile) -->
<div class="mobile-sidebar" id="mobile-sidebar">
    <div class="mobile-sidebar-header">
        <div class="mobile-sidebar-logo">
            <i class="fas fa-bus"></i>
            <span>SMART SHUTTLE</span>
        </div>
    </div>

    <div class="mobile-sidebar-content">
        @if(session()->has('user') && isset(session('user')['id']))
            @php
                $mobileUser = session('user');
                $mobileUserName = $mobileUser['name'] ?? 'User';
                $mobileAvatarUrl = $mobileUser['avatar'] ?? null;
                
                // Generate initials untuk mobile
                $mobileInitials = 'GU';
                if (!empty($mobileUserName)) {
                    $mobileNameParts = explode(' ', trim($mobileUserName));
                    if (count($mobileNameParts) >= 2) {
                        $mobileInitials = strtoupper(substr($mobileNameParts[0], 0, 1) . substr(end($mobileNameParts), 0, 1));
                    } else {
                        $mobileInitials = strtoupper(substr($mobileUserName, 0, 2));
                    }
                }
                
                // Tentukan apakah ada avatar yang valid untuk mobile
                $mobileHasValidAvatar = false;
                
                if ($mobileAvatarUrl) {
                    if (str_starts_with($mobileAvatarUrl, 'avatars/')) {
                        $mobileHasValidAvatar = true;
                        $mobileAvatarUrl = asset('storage/' . $mobileAvatarUrl);
                    } elseif (filter_var($mobileAvatarUrl, FILTER_VALIDATE_URL)) {
                        $mobileHasValidAvatar = true;
                    } elseif (str_starts_with($mobileAvatarUrl, 'public/avatars/')) {
                        $mobileHasValidAvatar = true;
                        $mobileAvatarUrl = asset(str_replace('public/', 'storage/', $mobileAvatarUrl));
                    }
                }
            @endphp
            
            <div class="mobile-profile-section" onclick="location.href='{{ route('customer.dashboardprofile') }}'">
                @if($mobileHasValidAvatar && $mobileAvatarUrl)
                    <div class="mobile-profile-avatar">
                        <img src="{{ $mobileAvatarUrl }}" 
                             alt="Avatar {{ $mobileUserName }}"
                             onerror="this.onerror=null; this.innerHTML='{{ $mobileInitials }}'; this.classList.add('initials-only');">
                    </div>
                @else
                    <div class="mobile-profile-avatar initials-only">{{ $mobileInitials }}</div>
                @endif
                <div class="mobile-profile-info">
                    <div class="mobile-profile-name">{{ $mobileUserName }}</div>
                    <div class="mobile-profile-email">{{ $mobileUser['email'] ?? '' }}</div>
                </div>
            </div>
        @endif

        <ul class="mobile-nav-menu">
            @php
                $currentRoute = Route::currentRouteName();
                $currentPath = request()->path();
            @endphp
            
            <li class="{{ $currentRoute == 'customer.beranda' || $currentPath == 'customer/beranda' ? 'active' : '' }}">
                <a href="/customer/beranda">
                    <span class="mobile-nav-icon"><i class="fas fa-home"></i></span>
                    <span>Beranda</span>
                </a>
            </li>
            
            <li class="{{ $currentRoute == 'customer.search' || str_contains($currentPath, 'search') ? 'active' : '' }}">
                <a href="{{ route('customer.search') }}">
                    <span class="mobile-nav-icon"><i class="fas fa-search"></i></span>
                    <span>Cari Tiket</span>
                </a>
            </li>
            
            <li class="{{ $currentRoute == 'customer.outlet' || str_contains($currentPath, 'outlet') ? 'active' : '' }}">
                <a href="{{ route('customer.outlet') }}">
                    <span class="mobile-nav-icon"><i class="fas fa-store"></i></span>
                    <span>Outlet</span>
                </a>
            </li>
            
            <li class="{{ $currentRoute == 'customer.smartsend' || str_contains($currentPath, 'smartsend') ? 'active' : '' }}">
                <a href="{{ route('customer.smartsend') }}">
                    <span class="mobile-nav-icon"><i class="fas fa-box"></i></span>
                    <span>Kirim Paket</span>
                </a>
            </li>
            
            <li>
                <a href="#" onclick="alert('Fitur Sewa Armada akan segera hadir!'); return false;">
                    <span class="mobile-nav-icon"><i class="fas fa-car"></i></span>
                    <span>Sewa Armada</span>
                </a>
            </li>
            
            <li class="{{ $currentRoute == 'customer.contact' || str_contains($currentPath, 'contact') ? 'active' : '' }}">
                <a href="{{ route('customer.contact') }}">
                    <span class="mobile-nav-icon"><i class="fas fa-phone"></i></span>
                    <span>Kontak</span>
                </a>
            </li>
            
            <li class="{{ $currentRoute == 'customer.cek-reservasi' || str_contains($currentPath, 'cek-reservasi') ? 'active' : '' }}">
                <a href="{{ route('customer.cek-reservasi') }}">
                    <span class="mobile-nav-icon"><i class="fas fa-ticket-alt"></i></span>
                    <span>Cek Reservasi</span>
                </a>
            </li>

            <!-- Menu tambahan jika user sudah login -->
            @if(session()->has('user') && isset(session('user')['id']))
                <li class="{{ $currentRoute == 'customer.dashboardprofile' ? 'active' : '' }}">
                    <a href="{{ route('customer.dashboardprofile') }}">
                        <span class="mobile-nav-icon"><i class="fas fa-user-circle"></i></span>
                        <span>Profil</span>
                    </a>
                </li>
                
                <li class="{{ $currentRoute == 'customer.riwayat' ? 'active' : '' }}">
                    <a href="{{ route('customer.riwayat') }}">
                        <span class="mobile-nav-icon"><i class="fas fa-history"></i></span>
                        <span>Riwayat</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="mobile-sidebar-bottom">
        <ul class="mobile-nav-menu">
            @if(session()->has('user') && isset(session('user')['id']))
                <li>
                    <form action="{{ route('customer.logout') }}" method="POST" style="display: none;" id="logout-form-mobile">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" style="color: #EF4444;">
                        <span class="mobile-nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span>Keluar</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('customer.login') }}">
                        <span class="mobile-nav-icon"><i class="fas fa-sign-in-alt"></i></span>
                        <span>Login</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

<script>
// NAVBAR GLOBAL SCRIPT
document.addEventListener('DOMContentLoaded', function() {
    console.log('Navbar script loaded - Mobile Profile di Kanan dengan Toggle');
    
    // Elements
    const navbar = document.getElementById('navbar');
    const dropdownButton = document.getElementById('profile-dropdown');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
    const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
    
    const isMobile = window.innerWidth <= 768;
    
    /* ========== NAVBAR SCROLL ========== */
    if (navbar) {
        // Force white background on mobile
        if (isMobile) {
            navbar.style.background = 'white';
            navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.08)';
        }
        
        window.addEventListener('scroll', function () {
            if (!isMobile) {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        }, { passive: true });
        
        if (!isMobile && window.scrollY > 50) {
            navbar.classList.add('scrolled');
        }
    }

    /* ========== PROFILE DROPDOWN TOGGLE (KLIK BUKA, KLIK LAGI TUTUP) ========== */
    if (dropdownButton && dropdownMenu) {
        dropdownButton.setAttribute('aria-haspopup', 'true');
        dropdownButton.setAttribute('aria-expanded', 'false');
        
        // Function to close dropdown
        function closeDropdown() {
            dropdownMenu.classList.remove('show');
            dropdownButton.classList.remove('active');
            dropdownButton.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = ''; // Reset scroll
        }
        
        // Function to open dropdown
        function openDropdown() {
            // Close sidebar if open on mobile
            if (isMobile && mobileSidebar && mobileSidebar.classList.contains('active')) {
                closeMobileSidebar();
            }
            
            dropdownMenu.classList.add('show');
            dropdownButton.classList.add('active');
            dropdownButton.setAttribute('aria-expanded', 'true');
            
            // Prevent body scroll on mobile when dropdown is open
            if (isMobile) {
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Toggle dropdown on click (klik bua, klik lagi tutup)
        dropdownButton.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            
            // Cek apakah dropdown sedang terbuka
            if (dropdownMenu.classList.contains('show')) {
                // Jika terbuka, tutup
                closeDropdown();
            } else {
                // Jika tertutup, buka
                openDropdown();
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                const isClickInside = dropdownMenu.contains(e.target) || 
                                     dropdownButton.contains(e.target);
                
                if (!isClickInside) {
                    closeDropdown();
                }
            }
        });
        
        // Close with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && dropdownMenu && dropdownMenu.classList.contains('show')) {
                closeDropdown();
            }
        });
        
        // Close when clicking links inside dropdown
        dropdownMenu.addEventListener('click', function (e) {
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                // Small delay to allow click to register
                setTimeout(() => {
                    closeDropdown();
                }, 100);
            }
        });
    }
    
    /* ========== MOBILE SIDEBAR FUNCTIONS ========== */
    function toggleMobileSidebar() {
        if (mobileSidebar) {
            const isOpen = mobileSidebar.classList.contains('active');
            
            if (isOpen) {
                // Jika sedang terbuka, tutup
                closeMobileSidebar();
            } else {
                // Jika sedang tertutup, buka
                openMobileSidebar();
            }
        }
    }

    function openMobileSidebar() {
        if (mobileSidebar) {
            // Close dropdown if open
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                closeDropdown();
            }
            
            mobileSidebar.classList.add('active');
            mobileSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (mobileMenuToggle) {
                mobileMenuToggle.classList.add('active');
            }
        }
    }

    function closeMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('active');
            mobileSidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
            if (mobileMenuToggle) {
                mobileMenuToggle.classList.remove('active');
            }
        }
    }
    
    /* ========== EVENT LISTENERS ========== */
    // Open/close mobile sidebar dengan toggle
    if (mobileMenuToggle && mobileSidebar) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            toggleMobileSidebar();
        });
    }

    // Close mobile sidebar with overlay click
    if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', function(e) {
            // Jika dropdown profile sedang terbuka, jangan tutup sidebar
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                e.stopPropagation();
                return;
            }
            closeMobileSidebar();
        });
    }
    
    // Close sidebar when clicking links (except alerts)
    if (mobileSidebar) {
        const mobileNavLinks = mobileSidebar.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
            const onclickAttr = link.getAttribute('onclick');
            if (!onclickAttr || 
                (!onclickAttr.includes('alert') && !onclickAttr.includes('submit'))) {
                link.addEventListener('click', function(e) {
                    if (this.href && this.target !== '_blank') {
                        setTimeout(closeMobileSidebar, 300);
                    }
                });
            }
        });
    }
    
    // Close all on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (mobileSidebar && mobileSidebar.classList.contains('active')) {
                closeMobileSidebar();
            }
            
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                closeDropdown();
            }
        }
    });
    
    /* ========== WINDOW RESIZE HANDLER ========== */
    function handleResize() {
        const nowMobile = window.innerWidth <= 768;
        
        // Force white background on mobile
        if (navbar) {
            if (nowMobile) {
                navbar.style.background = 'white';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.08)';
                navbar.classList.remove('scrolled');
            } else {
                navbar.style.background = '';
                navbar.style.boxShadow = '';
            }
        }
        
        // Close mobile sidebar when resizing to desktop
        if (!nowMobile && mobileSidebar && mobileSidebar.classList.contains('active')) {
            closeMobileSidebar();
        }
        
        // Always close dropdown on resize (safer)
        if (dropdownMenu && dropdownMenu.classList.contains('show')) {
            closeDropdown();
        }
    }
    
    window.addEventListener('resize', handleResize);
    
    // Initial setup for mobile
    if (isMobile && navbar) {
        navbar.style.background = 'white';
        navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.08)';
    }
    
    /* ========== AVATAR IMAGE ERROR HANDLING ========== */
    const avatarImages = document.querySelectorAll('.profile-avatar img, .mobile-profile-avatar img');
    avatarImages.forEach(img => {
        img.addEventListener('error', function() {
            console.log('Avatar image failed to load, showing initials');
            
            const parent = this.parentElement;
            const altText = this.alt || '';
            const userName = altText.replace('Avatar ', '').replace('avatar ', '') || 'User';
            
            // Get initials
            let initials = 'GU';
            if (userName && userName !== 'User') {
                const words = userName.split(' ');
                if (words.length >= 2) {
                    initials = words[0].charAt(0) + words[words.length - 1].charAt(0);
                } else {
                    initials = userName.substring(0, 2);
                }
                initials = initials.toUpperCase();
            }
            
            // Replace image with initials
            parent.innerHTML = initials;
            parent.classList.add('initials-only');
        });
    });
});
</script>