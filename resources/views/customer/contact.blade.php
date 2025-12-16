<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }} - Kontak</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #00215E;
            --secondary-color: #FF581E;
        }

        /* Reset dan gaya dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
        }

        /* Custom Navbar Styles */
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

        /* Panel Oval untuk Navbar */
        .nav-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50px;
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
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
            gap: 35px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .nav-links a.active {
            color: var(--secondary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--secondary-color);
            transition: width 0.3s;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-auth {
            display: flex;
            justify-content: flex-end;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Navbar saat di-scroll */
        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        }

        .custom-navbar.scrolled .nav-links a {
            color: var(--primary-color);
        }

        .custom-navbar.scrolled .btn-login {
            background-color: var(--secondary-color);
        }

        /* Profile icon + small name */
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
            padding: 6px 8px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
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
            background: linear-gradient(135deg, var(--secondary-color), #ff7b4d);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            flex-shrink: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-name {
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 600;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dropdown-menu {
            z-index: 3000;
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 170px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 10px;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 12px;
            color: #00215E;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background-color 0.2s;
        }

        .dropdown-menu a:hover {
            background-color: rgba(0, 33, 94, 0.05);
        }

        .dropdown-menu form {
            margin: 0;
        }

        .dropdown-menu button[type="submit"] {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            background: none;
            border: none;
            color: #00215E;
            cursor: pointer;
            border-radius: 5px;
            font-family: inherit;
            font-size: inherit;
            transition: background-color 0.2s;
        }

        .dropdown-menu button[type="submit"]:hover {
            background-color: rgba(0, 33, 94, 0.05);
        }

        /* Background dengan foto Indonesia dan overlay terang */
        .contact-page {
            font-family: 'Inter', sans-serif;
            position: relative;
            min-height: 100vh;
            background:
                linear-gradient(
                    to bottom,
                    rgba(255, 255, 255, 0.95) 0%,
                    rgba(255, 255, 255, 0.85) 50%,
                    rgba(255, 255, 255, 0.95) 100%
                ),
                url('{{ asset("images/indonesia.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            padding-top: 100px;
        }

        .contact-page.no-bg {
            background:
                linear-gradient(
                    to bottom,
                    rgba(255, 255, 255, 0.95) 0%,
                    rgba(255, 255, 255, 0.85) 50%,
                    rgba(255, 255, 255, 0.95) 100%
                );
            background-attachment: fixed;
        }

        .contact-section {
            padding: 80px 0;
            background: transparent;
            position: relative;
            z-index: 1;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .contact-title {
            font-size: 52px;
            font-weight: 900;
            color: #0E2A47;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .contact-subtitle {
            color: #FF5722;
            font-size: 20px;
            font-weight: 500;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Left Card */
        .contact-info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 50px;
            width: 370px;
            box-shadow: 0 10px 30px rgba(14, 42, 71, 0.15);
            border: 1px solid rgba(255, 87, 34, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(14, 42, 71, 0.2);
        }

        .contact-info-card .contact-subtitle {
            color: #FF5722;
            margin-bottom: 40px;
            margin-top: 20px;
            font-weight: 700;
            font-style: italic;
            font-size: 24px;
            text-align: left;
        }

        .contact-item {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: flex-start;
            border: 1px solid #eaeaea;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            border-color: #FF5722;
            box-shadow: 0 5px 15px rgba(255, 87, 34, 0.1);
            transform: translateY(-2px);
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-item p {
            margin: 0;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
        }

        .contact-icon {
            color: #FF5722;
            font-size: 22px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
            margin-top: 3px;
        }

        .jam-operasional {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .jam-operasional h4 {
            color: #0E2A47;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .jam-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }

        .jam-item:last-child {
            border-bottom: none;
        }

        .jam-hari {
            color: #666;
            font-weight: 500;
        }

        .jam-waktu {
            color: #FF5722;
            font-weight: 600;
        }

        /* Right Form */
        .contact-form-card {
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.95), rgba(255, 107, 0, 0.95));
            text-align: center;
            color: #fff;
            border-radius: 12px;
            padding: 40px;
            width: 460px;
            box-shadow: 0 10px 30px rgba(255, 87, 34, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 87, 34, 0.35);
        }

        .form-title {
            font-weight: 800;
            margin-bottom: 10px;
            margin-top: 10px;
            font-style: italic;
            font-size: 24px;
            color: #fff;
        }

        .form-subtitle {
            margin-bottom: 30px;
            font-size: 14px;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        .contact-form {
            text-align: left;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 6px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        .form-input:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 6px;
            margin-bottom: 25px;
            resize: vertical;
            min-height: 140px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-textarea::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        .form-textarea:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            font-weight: 700;
            background: #fff;
            color: #FF5722;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* Footer Styles */
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

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .contact-content {
                flex-direction: column;
                align-items: center;
            }

            .contact-info-card, .contact-form-card {
                width: 100%;
                max-width: 500px;
            }

            .nav-panel {
                padding: 8px 20px;
            }

            .nav-links {
                gap: 25px;
            }

            .footer-main {
                flex-direction: column;
                gap: 30px;
                margin-bottom: 30px;
            }

            .footer-column {
                width: 100%;
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

            .contact-title {
                font-size: 42px;
            }

            .contact-subtitle {
                font-size: 18px;
            }

            .contact-info-card, .contact-form-card {
                padding: 30px;
            }

            .contact-info-card .contact-subtitle, .form-title {
                font-size: 20px;
            }

            .contact-page {
                background-attachment: scroll;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .contact-title {
                font-size: 32px;
            }

            .contact-subtitle {
                font-size: 16px;
            }

            .contact-info-card, .contact-form-card {
                padding: 25px;
            }

            .contact-info-card .contact-subtitle, .form-title {
                font-size: 18px;
            }

            .contact-item {
                padding: 15px;
            }

            .form-input, .form-textarea {
                padding: 12px;
            }

            .nav-links a {
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .nav-panel {
                padding: 12px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Custom Navbar -->
    <nav class="custom-navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-panel">
                <div class="nav-brand">
                    @php
                        $profile = \App\Models\MProfilePerusahaan::first();
                    @endphp
                    <img src="{{ asset($profile->logo_perusahaan ?? '/images/smartshuttlelogo.png') }}" alt="{{ $profile->nama_dagang ?? 'Smart Shuttle' }}">
                </div>
                <div class="nav-menu">
                    <ul class="nav-links">
                        <li><a href="{{ route('customer.beranda') }}">Beranda</a></li>
                        <li><a href="{{ route('customer.search') }}">Cari Tiket</a></li>
                        <li><a href="{{ route('customer.outlet') }}">Outlet</a></li>
                        <li><a href="{{ route('customer.contact') }}" class="active">Kontak</a></li>
                    </ul>
                </div>
                <div class="nav-auth">
                    @if(isset($user) && $user)
                        <div class="profile-wrapper">
                            <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                                @if(!empty($user['avatar'] ?? null))
                                    <span class="profile-avatar">
                                        <img src="{{ $user['avatar'] }}" alt="avatar">
                                    </span>
                                @else
                                    <span class="profile-avatar">{{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}</span>
                                @endif
                                <span class="profile-name">{{ strlen($user['name'] ?? '') > 12 ? substr($user['name'], 0, 12).'...' : ($user['name'] ?? 'User') }}</span>
                            </button>

                            <div id="dropdown-menu" class="dropdown-menu">
                                <a href="{{ route('customer.dashboardprofile') }}">Profil</a>
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn-login">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="contact-page" id="contactPage">
            <section class="contact-section">
                <div class="contact-header">
                    <h1 class="contact-title">Hubungi Kami</h1>
                    <p class="contact-subtitle">{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }} - Perjalanan Nyaman, Pengalaman Tak Terlupakan</p>
                </div>

                <div class="contact-content">
                    <!-- Left Card -->
                    <div class="contact-info-card">
                        <h6 class="contact-subtitle">Kami disini untuk membantu anda</h6>

                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> {{ session('info') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i> Mohon periksa kembali data yang Anda masukkan.
                            </div>
                        @endif

                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                            <p>{{ $masterKontak->email_utama ?? 'mdcitrasolusi@gmail.com' }}</p>
                        </div>

                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-headphones"></i></span>
                            <p>{{ $masterKontak->email_dukungan ?? $masterKontak->email_utama ?? 'mdcitrasolusi@gmail.com' }}</p>
                        </div>

                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-phone"></i></span>
                            <p>{{ $masterKontak->telepon_utama ?? '0858-1122-4321' }}</p>
                        </div>

                        <div class="contact-item">
                            <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <p>{{ $masterKontak->alamat_kantor_pusat ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}</p>
                        </div>

                        @if(isset($masterKontak->jam_operasional) && is_array($masterKontak->jam_operasional))
                            <div class="jam-operasional">
                                <h4><i class="fas fa-clock"></i> Jam Operasional</h4>
                                @foreach($masterKontak->jam_operasional as $jam)
                                    <div class="jam-item">
                                        <span class="jam-hari">{{ $jam['hari'] ?? 'Senin - Jumat' }}</span>
                                        <span class="jam-waktu">{{ $jam['jam'] ?? '08:00 - 17:00' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right Form -->
                    <div class="contact-form-card">
                        <h5 class="form-title">Kenyamanan anda, prioritas kami</h5>
                        <p class="form-subtitle">Beri kami masukan agar {{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }} terus jadi pilihan terbaik untuk perjalanan Anda.</p>

                        <form action="{{ route('customer.contact.submit') }}" method="POST" class="contact-form" id="contactForm">
                            @csrf

                            <input type="text" name="nama" placeholder="Nama Lengkap" class="form-input"
                                   value="{{ old('nama') }}" required />
                            @error('nama')
                                <small style="color: #ffcccc; display: block; margin-top: -15px; margin-bottom: 15px;">
                                    {{ $message }}
                                </small>
                            @enderror

                            <input type="email" name="email" placeholder="Email" class="form-input"
                                   value="{{ old('email') }}" required />
                            @error('email')
                                <small style="color: #ffcccc; display: block; margin-top: -15px; margin-bottom: 15px;">
                                    {{ $message }}
                                </small>
                            @enderror

                            <input type="text" name="telepon" placeholder="Nomor Telepon" class="form-input"
                                   value="{{ old('telepon') }}" />
                            @error('telepon')
                                <small style="color: #ffcccc; display: block; margin-top: -15px; margin-bottom: 15px;">
                                    {{ $message }}
                                </small>
                            @enderror

                            <textarea name="pesan" placeholder="Pesan atau ulasan Anda" rows="4" class="form-textarea" required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <small style="color: #ffcccc; display: block; margin-top: -15px; margin-bottom: 15px;">
                                    {{ $message }}
                                </small>
                            @enderror

                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-main">
                <!-- Company Info -->
                <div class="footer-column">
                    <h3 class="footer-title">{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }}</h3>
                    <p class="footer-text">
                        {{ $masterKontak->deskripsi_singkat ?? 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.' }}
                    </p>
                </div>

                <!-- Contact Info -->
                <div class="footer-column">
                    <h4 class="footer-subtitle">Kontak</h4>
                    <div class="contact-list">
                        <div class="contact-line">
                            <i class="fas fa-phone"></i>
                            <span>Whatsapp: {{ $masterKontak->telepon_utama ?? '+62 858-1122-4321' }}</span>
                        </div>
                        <div class="contact-line">
                            <i class="fas fa-envelope"></i>
                            <span>Email: {{ $masterKontak->email_utama ?? 'mdcitrasolusi@gmail.com' }}</span>
                        </div>
                        <div class="contact-line">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="address">Alamat: {{ $masterKontak->alamat_kantor_pusat ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="footer-column">
                    <h4 class="footer-subtitle">Sosial Media</h4>
                    <p class="footer-text">
                        Ikuti kami di media sosial untuk informasi terbaru dan promo menarik.
                    </p>
                    <div class="social-buttons">
                        @if($masterKontak->facebook_url ?? '#')
                            <a href="{{ $masterKontak->facebook_url }}" class="social-button" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if($masterKontak->instagram_url ?? '#')
                            <a href="{{ $masterKontak->instagram_url }}" class="social-button" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($masterKontak->twitter_url ?? '#')
                            <a href="{{ $masterKontak->twitter_url }}" class="social-button" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; {{ date('Y') }} {{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }}. All rights reserved.
                    </p>
                    <div class="footer-links">
                        @if($masterKontak->link_kebijakan_privasi ?? '#')
                            <a href="{{ $masterKontak->link_kebijakan_privasi }}" class="footer-link">Kebijakan Privasi</a>
                        @endif
                        @if($masterKontak->link_syarat_ketentuan ?? '#')
                            <a href="{{ $masterKontak->link_syarat_ketentuan }}" class="footer-link">Syarat & Ketentuan</a>
                        @endif
                        <a href="{{ route('customer.contact') }}" class="footer-link">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded - init scripts kontak');

            /* ---------- NAVBAR SCROLL ---------- */
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

            /* ---------- PROFILE DROPDOWN ---------- */
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

                document.addEventListener('click', function (e) {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        if (dropdownMenu.classList.contains('show')) {
                            dropdownMenu.classList.remove('show');
                            dropdownButton.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                dropdownMenu.addEventListener('click', function (e) {
                    const tag = e.target.tagName;
                    if (tag === 'A' || tag === 'BUTTON') {
                        dropdownMenu.classList.remove('show');
                        dropdownButton.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                        dropdownButton.setAttribute('aria-expanded', 'false');
                        dropdownButton.focus();
                    }
                });
            }

            /* ---------- FORM SUBMISSION ---------- */
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');

            if (contactForm && submitBtn) {
                contactForm.addEventListener('submit', function(e) {
                    // Validasi sederhana
                    const nama = contactForm.querySelector('input[name="nama"]').value.trim();
                    const email = contactForm.querySelector('input[name="email"]').value.trim();
                    const pesan = contactForm.querySelector('textarea[name="pesan"]').value.trim();

                    if (!nama || !email || !pesan) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi!');
                        return false;
                    }

                    // Validasi email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        alert('Mohon masukkan alamat email yang valid!');
                        return false;
                    }

                    // Disable tombol submit
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                });
            }

            /* ---------- CHECK BACKGROUND IMAGE ---------- */
            const contactPage = document.getElementById('contactPage');
            const bgImage = new Image();

            const localImageUrl = "{{ asset('images/indonesia.jpeg') }}";

            bgImage.onload = function() {
                console.log('Background image loaded successfully');
            };

            bgImage.onerror = function() {
                console.log('Local background image not found, using fallback');
                if (contactPage) {
                    contactPage.classList.add('no-bg');
                }
            };

            bgImage.src = localImageUrl;
        });
    </script>
</body>
</html>
