<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Reservasi - Smart Shuttle</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            background-color: white;
        }

        /* ================= NAVBAR SAMA DENGAN BERANDA ================= */
        /* Custom Navbar Styles - TRANSPARAN */
        .custom-navbar {
            background: transparent;
            padding: 20px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            min-height: 80px;
            transform: translateY(0);
            box-shadow: none;
        }

        .custom-navbar.hidden {
            transform: translateY(-100%);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        /* Panel Oval untuk Navbar - TRANSPARAN DENGAN BLUR */
        .nav-panel {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .nav-brand img {
            height: 35px;
            width: auto;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            flex: 1;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: #00215E;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
            font-family: 'Roboto', sans-serif;
        }

        .nav-links a:hover {
            color: #FF581E;
        }

        .nav-links a.active {
            color: #FF581E;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #FF581E;
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
        }

        .btn-login {
            background-color: #00215E;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        }

        .btn-login:hover {
            background-color: #FF581E;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        /* Navbar saat di-scroll - LEBIH TRANSPARAN */
        .custom-navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        /* Profile icon + small name */
        .profile-wrapper {
            position: relative;
            display: inline-block;
            z-index: 100;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: none;
            padding: 6px 8px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 101;
            position: relative;
            font-family: 'Roboto', sans-serif;
        }

        .profile-btn:hover,
        .profile-btn:focus {
            outline: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            background: rgba(0, 33, 94, 0.05);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #FF581E, #ff7b4d);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            flex-shrink: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-family: 'Roboto', sans-serif;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-name {
            font-size: 12px;
            color: #00215E;
            font-weight: 600;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-family: 'Roboto', sans-serif;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            z-index: 1000;
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 170px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 10px 0;
            border: 1px solid #e0e0e0;
            animation: fadeIn 0.2s ease-out;
            font-family: 'Roboto', sans-serif;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 16px;
            color: #00215E;
            text-decoration: none;
            border-radius: 0;
            margin: 0;
            transition: background-color 0.2s;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
        }

        .dropdown-menu a:hover {
            background-color: rgba(255, 88, 30, 0.1);
            color: #FF581E;
        }

        .dropdown-menu form {
            margin: 0;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .dropdown-menu button[type="submit"] {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 16px;
            background: none;
            border: none;
            color: #00215E;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .dropdown-menu button[type="submit"]:hover {
            background-color: rgba(255, 88, 30, 0.1);
            color: #FF581E;
        }

        /* Tambahkan class untuk show */
        .dropdown-menu.show {
            display: block;
        }

        /* Responsive Navbar */
        @media (max-width: 1024px) {
            .nav-panel {
                padding: 8px 20px;
            }

            .nav-links {
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .custom-navbar {
                padding: 15px 3%;
            }

            .nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-panel {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
                border-radius: 25px;
            }

            .nav-brand, .nav-menu, .nav-auth {
                width: 100%;
                justify-content: center;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .profile-name {
                display: none;
            }

            .profile-btn {
                padding: 6px;
            }

            .dropdown-menu {
                right: 50%;
                transform: translateX(50%);
                min-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .nav-panel {
                padding: 12px;
                border-radius: 20px;
            }

            .nav-links a {
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 6px 15px;
                font-size: 0.9rem;
            }
        }

        /* ================= HERO SECTION SAMA DENGAN BERANDA ================= */
        .hero-section {
            position: relative;
            height: 100vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 6%;
            margin-bottom: 30px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 50%;
            color: white;
            text-align: left;
        }

        .hero-title {
            font-size: 54px;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
            letter-spacing: -0.5px;
        }

        .hero-desc {
            font-size: 18px;
            line-height: 1.7;
            max-width: 520px;
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
        }

        /* Search Box untuk Cek Reservasi */
        .hero-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            display: flex;
            max-width: 800px;
            width: 100%;
            margin-top: 40px;
            padding: 5px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .hero-box:focus-within {
            border-color: #FF581E;
            box-shadow: 0 25px 60px rgba(255, 88, 30, 0.4);
            transform: translateY(-5px);
        }

        .hero-box input {
            flex: 1;
            border: none;
            outline: none;
            padding: 22px 25px;
            font-size: 16px;
            border-radius: 14px;
            color: #333;
            background: transparent;
        }

        .hero-box input::placeholder {
            color: #666;
        }

        .hero-box button {
            background: linear-gradient(135deg, #FF581E, #ff7b4d);
            border: none;
            color: white;
            padding: 0 45px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(255, 88, 30, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-box button:hover {
            background: linear-gradient(135deg, #E54E1A, #ff7a32);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 88, 30, 0.5);
        }

        /* ================= CONTENT SECTION ================= */
        .content-section {
            max-width: 1200px;
            margin: 100px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .content-section h2 {
            color: #FF581E;
            font-size: 36px;
            margin-bottom: 25px;
            font-weight: 700;
            line-height: 1.3;
        }

        .content-section p {
            color: #555;
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Card Image */
        .card-image {
            max-width: 450px;
            margin-left: auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
            background: white;
            transition: all 0.4s;
        }

        .card-image:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
        }

        .card-image img {
            width: 100%;
            display: block;
            height: auto;
            transition: transform 0.5s;
        }

        .card-image:hover img {
            transform: scale(1.05);
        }

        /* Alert */
        .alert-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .alert {
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            font-weight: 500;
            animation: slideIn 0.5s ease;
        }

        .alert-danger {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            color: #c62828;
        }

        .alert-success {
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            color: #2e7d32;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .content-section {
                grid-template-columns: 1fr;
                text-align: center;
                margin: 70px auto;
                gap: 50px;
            }

            .card-image {
                margin: 0 auto;
            }

            .hero-content {
                max-width: 80%;
                text-align: center;
            }

            .hero-title {
                font-size: 46px;
            }

            .hero-section {
                height: auto;
                min-height: 80vh;
                padding: 120px 20px 60px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 15px 40px;
                min-height: 70vh;
            }

            .hero-content {
                max-width: 100%;
            }

            .hero-title {
                font-size: 40px;
                margin-bottom: 15px;
            }

            .hero-desc {
                font-size: 16px;
            }

            .hero-box {
                flex-direction: column;
                gap: 10px;
                padding: 20px;
                margin-top: 30px;
            }

            .hero-box input,
            .hero-box button {
                width: 100%;
                padding: 18px;
            }

            .content-section h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 34px;
            }

            .hero-section {
                padding: 90px 15px 30px;
                min-height: 60vh;
            }

            .hero-desc {
                font-size: 14px;
            }

            .content-section h2 {
                font-size: 28px;
            }

            .content-section {
                margin: 50px auto;
                gap: 40px;
            }

            .hero-box button {
                padding: 15px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR SAMA DENGAN BERANDA -->
    <nav class="custom-navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-panel">
                <div class="nav-brand">
                    <a href="{{ route('customer.beranda') }}">
                        <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle">
                    </a>
                </div>
                <div class="nav-menu">
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('customer.beranda') }}"
                               class="{{ request()->routeIs('customer.beranda') ? 'active' : '' }}">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.search') }}"
                               class="{{ request()->routeIs('customer.search') ? 'active' : '' }}">
                                Tiket
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.outlet') }}"
                               class="{{ request()->routeIs('customer.outlet') ? 'active' : '' }}">
                                Outlet
                            </a>
                        </li>
                        <!-- Menu baru ditambahkan di sini -->
                        <li><a href="#" onclick="alert('Fitur Kirim Paket akan segera hadir!'); return false;">Kirim Paket</a></li>
                        <li><a href="#" onclick="alert('Fitur Sewa Armada akan segera hadir!'); return false;">Sewa Armada</a></li>
                        <li>
                            <a href="{{ route('customer.contact') }}"
                               class="{{ request()->routeIs('customer.contact') ? 'active' : '' }}">
                                Kontak
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.cek-reservasi') }}"
                               class="{{ request()->routeIs('customer.cek-reservasi') ? 'active' : '' }}">
                                Cek Reservasi
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- BAGIAN NAV-AUTH -->
                <div class="nav-auth">
                    @auth
                        <div class="profile-wrapper">
                            <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                                @if(!empty(Auth::user()->avatar))
                                    <span class="profile-avatar">
                                        <img src="{{ Auth::user()->avatar }}" alt="avatar">
                                    </span>
                                @else
                                    <span class="profile-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                                @endif
                                <span class="profile-name">{{ strlen(Auth::user()->name ?? '') > 12 ? substr(Auth::user()->name, 0, 12).'...' : (Auth::user()->name ?? 'User') }}</span>
                            </button>
                            <div id="dropdown-menu" class="dropdown-menu">
                                <a href="{{ route('customer.dashboardprofile') }}">Profil</a>
                                <a href="{{ route('customer.riwayat') }}">Riwayat</a>
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn-login" id="login-btn">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- HERO SECTION SAMA DENGAN BERANDA -->
        <section class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
            <div class="hero-content">
                <h1 class="hero-title">Cek Reservasi</h1>
                <p class="hero-desc">
                    Verifikasi status perjalanan Anda dengan mudah. Cukup masukkan kode booking yang Anda terima saat pemesanan untuk mendapatkan informasi lengkap tiket Anda.
                </p>

                <form class="hero-box" method="POST" action="{{ route('customer.cek-reservasi.proses') }}">
                    @csrf
                    <input
                        type="text"
                        name="kode"
                        placeholder="Masukkan kode reservasi atau tiket Anda..."
                        required
                        autocomplete="off"
                        autofocus
                    >
                    <button type="submit">
                        <i class="fas fa-search"></i>
                        <span>Cek Reservasi</span>
                    </button>
                </form>
            </div>
        </section>

        <!-- CONTENT SECTION -->
        <section class="content-section">
            <div>
                <h2>Cek Reservasi Dengan Mudah</h2>
                <p>
                    Dengan fitur cek reservasi Smart Shuttle, Anda dapat dengan mudah dan cepat
                    memverifikasi status perjalanan Anda. Tidak perlu lagi mencari email konfirmasi
                    atau bingung di terminal.
                </p>
                <p>
                    Semua informasi reservasi bisa diakses hanya dalam beberapa detik dengan
                    memasukkan kode booking yang Anda terima saat pemesanan.
                </p>
                <p style="color: #666; font-style: italic;">
                    Cukup masukkan kode, dan dapatkan informasi lengkap perjalanan Anda!
                </p>
            </div>

            <div>
                <div class="card-image">
                    <img src="{{ asset('images/kalender.png') }}" alt="Ilustrasi Kalender Reservasi">
                </div>
            </div>
        </section>

        <!-- ALERT MESSAGES -->
        @if(session('error'))
            <div class="alert-container">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(isset($pemesanan))
            <div class="alert-container">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Reservasi ditemukan! Berikut detail tiket Anda:
                </div>
            </div>

            <div class="container mt-4 mb-5">
                @include('customer.e_ticket', [
                    'pemesanan' => $pemesanan,
                    'jadwal' => $jadwal ?? null,
                    'from' => $from ?? null,
                    'to' => $to ?? null,
                    'date' => $date ?? null,
                    'time' => $time ?? null,
                    'estimasi_sampai' => $estimasi_sampai ?? null,
                    'customer_name' => $customer_name ?? null,
                    'customer_phone' => $customer_phone ?? null,
                    'customer_email' => $customer_email ?? null,
                    'penumpang' => $penumpang ?? [],
                    'shuttle' => $shuttle ?? null,
                    'nomor_kursi' => $nomor_kursi ?? null,
                    'kode_booking' => $kode_booking ?? null,
                    'total_bayar' => $total_bayar ?? 0,
                ])
            </div>
        @endif
    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-main">
                <!-- Smart Shuttle -->
                <div class="footer-column">
                    <h3 class="footer-title">Smart Shuttle</h3>
                    <p class="footer-text">
                        Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.
                    </p>
                </div>

                <!-- Kontak -->
                <div class="footer-column">
                    <h4 class="footer-subtitle">Kontak</h4>
                    <div class="contact-list">
                        <div class="contact-line">
                            <span>Whatsapp: +62 858-1122-4321</span>
                        </div>
                        <div class="contact-line">
                            <span>Email: mdcitrasolusi@gmail.com</span>
                        </div>
                        <div class="contact-line">
                            <span class="address">Alamat: Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434</span>
                        </div>
                    </div>
                </div>

                <!-- Sosial Media -->
                <div class="footer-column">
                    <h4 class="footer-subtitle">Sosial Media</h4>
                    <p class="footer-text">
                        Dengan layanan unggulan yang kami hadirkan, kami berkomitmen untuk menjadikan setiap momen perjalanan Anda lebih istimewa.
                    </p>
                    <div class="social-buttons">
                        <a href="#" class="social-button">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="citrasolusi.id" class="social-button">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-button">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; 2024 Smart Shuttle. All rights reserved.
                    </p>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Privacy Policy</a>
                        <a href="#" class="footer-link">Terms of Service</a>
                        <a href="#" class="footer-link">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* FOOTER STYLES */
        .site-footer {
            background: #00215E;
            color: white;
            padding: 50px 40px 20px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            gap: 40px;
        }

        .footer-column {
            flex: 1;
        }

        .footer-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #FF581E;
        }

        .footer-subtitle {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #FF581E;
        }

        .footer-text {
            font-size: 14px;
            color: #e0e0e0;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* Contact List */
        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-line {
            font-size: 14px;
            color: #e0e0e0;
            line-height: 1.4;
        }

        .address {
            font-size: 13px;
            line-height: 1.5;
        }

        /* Social Buttons */
        .social-buttons {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }

        .social-button {
            width: 32px;
            height: 32px;
            background: #FF581E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-button:hover {
            background: #E54E1A;
            transform: translateY(-2px);
        }

        .social-button i {
            color: white;
            font-size: 12px;
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .copyright {
            font-size: 14px;
            color: #b0b0b0;
            margin: 0;
        }

        .footer-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .footer-link {
            font-size: 14px;
            color: #b0b0b0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .site-footer {
                padding: 40px 20px 20px;
            }

            .footer-main {
                flex-direction: column;
                gap: 30px;
                margin-bottom: 30px;
            }

            .footer-column {
                width: 100%;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>

    <!-- JavaScript -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // NAVBAR SCROLL FUNCTIONALITY
        const navbar = document.getElementById('navbar');
        let lastScrollY = window.scrollY || 0;

        if (navbar) {
            window.addEventListener('scroll', function () {
                const currentY = window.scrollY || 0;
                if (currentY > 100) {
                    navbar.classList.add('scrolled');
                    if (currentY > lastScrollY && currentY > 100) {
                        navbar.classList.add('hidden');
                    } else {
                        navbar.classList.remove('hidden');
                    }
                } else {
                    navbar.classList.remove('scrolled');
                    navbar.classList.remove('hidden');
                }
                lastScrollY = currentY;
            }, { passive: true });
        }

        // PROFILE DROPDOWN FUNCTIONALITY
        const dropdownButton = document.getElementById('profile-dropdown');
        const dropdownMenu = document.getElementById('dropdown-menu');

        if (dropdownButton && dropdownMenu) {
            dropdownButton.setAttribute('aria-haspopup', 'true');
            dropdownButton.setAttribute('aria-expanded', 'false');

            dropdownButton.addEventListener('click', function (e) {
                e.stopPropagation();
                const isShown = dropdownMenu.classList.toggle('show');
                dropdownButton.setAttribute('aria-expanded', isShown ? 'true' : 'false');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (dropdownMenu.classList.contains('show')) {
                    // Jika klik di luar dropdown DAN di luar tombol profile
                    if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownButton.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Close dropdown on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    dropdownButton.focus();
                }
            });

            // Close dropdown when item is selected
            dropdownMenu.addEventListener('click', function (e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Focus on search input
        const searchInput = document.querySelector('input[name="kode"]');
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
            }, 300);
        }

        // Form submission animation
        const form = document.querySelector('.hero-box');
        if (form) {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button');
                if (button) {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
                    button.disabled = true;
                }
            });
        }

        // Fallback jika bg.png tidak ditemukan
        const bgImage = new Image();
        bgImage.src = '/images/bg.png';
        bgImage.onerror = function() {
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.background = 'linear-gradient(135deg, #00215E, #00308F)';
            }
        };
    });
    </script>
</body>
</html>
