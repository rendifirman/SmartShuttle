<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Smart Shuttle | Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{
            --primary-blue: #00215E; /* Biru utama - SAMA DENGAN CUSTOMER */
            --accent-orange: #FF581E; /* Oren - SAMA DENGAN CUSTOMER */
            --white: #ffffff;
            --text-gray: #4a5568; /* teks abu */
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        body {
            background-image: url('/images/bgSmartshuttle.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #00215E; /* Fallback color */
        }

        @media (max-width: 768px) {
            body {
                background-position: 70% center;
                background-attachment: scroll;
            }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .left-overlay {
            background: rgba(255, 254, 254, 0.85);
        }

        .right-overlay {
            background: rgba(59, 59, 59, 0.7);
        }

        @media (max-width: 768px) {
            .right-overlay {
                background: rgba(59, 59, 59, 0.85);
            }
            .left-overlay {
                display: none;
            }
        }

        /* Mobile logo and header */
        .mobile-header {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 20px 0 15px;
                background: rgba(255, 254, 254, 0.9);
                backdrop-filter: blur(5px);
                border-bottom: 1px solid rgba(0, 33, 94, 0.1);
            }
        }

        /* Password Container Styles */
        .password-container {
            position: relative;
        }

        .password-container .relative {
            position: relative;
        }

        /* Desktop password toggle */
        .right-overlay .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 5px;
            z-index: 10;
            transition: color 0.2s;
        }

        .right-overlay .password-toggle:hover {
            color: var(--primary-blue);
        }

        /* Input desktop dengan padding untuk ikon mata */
        .right-overlay .password-container input {
            padding-right: 45px !important;
        }

        /* Mobile password toggle */
        .mobile-form-container .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 5px;
            z-index: 10;
            transition: color 0.2s;
        }

        .mobile-form-container .password-toggle:hover {
            color: var(--primary-blue);
        }

        /* Input mobile dengan padding untuk ikon mata */
        .mobile-form-container .password-container input {
            padding-right: 45px !important;
        }

        /* Mobile container styling */
        @media (max-width: 768px) {
            .mobile-main-container {
                background: rgba(0, 33, 94, 0.8);
                min-height: 100vh;
                padding-bottom: 20px;
            }

            .mobile-content-wrapper {
                padding: 0 16px 30px;
            }

            /* Features section for mobile */
            .mobile-features-container {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 16px;
                padding: 25px 20px;
                margin-top: 20px;
                box-shadow: 0 4px 12px rgba(0, 33, 94, 0.1);
            }

            .mobile-features-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .mobile-features-main-title {
                color: #00215E;
                font-size: 1.3rem;
                font-weight: 700;
                margin-bottom: 5px;
            }

            .mobile-features-subtitle {
                color: #FF581E; /* ORANGE - SAMA DENGAN CUSTOMER */
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 15px;
            }

            .mobile-features-title {
                color: #00215E;
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 2px solid #FF581E; /* ORANGE - SAMA DENGAN CUSTOMER */
            }

            .mobile-feature-item {
                background: white;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 12px;
                border-left: 4px solid #00215E; /* BIRU TUA - SAMA DENGAN CUSTOMER */
                box-shadow: 0 2px 6px rgba(0, 33, 94, 0.08);
            }

            .mobile-feature-content {
                display: flex;
                align-items: center;
            }

            .mobile-feature-icon {
                background: #00215E; /* BIRU TUA - SAMA DENGAN CUSTOMER */
                width: 40px;
                height: 40px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                flex-shrink: 0;
            }

            .mobile-feature-title {
                color: #00215E; /* BIRU TUA - SAMA DENGAN CUSTOMER */
                font-weight: 600;
                font-size: 0.95rem;
                margin-bottom: 4px;
            }

            .mobile-feature-desc {
                color: #4a5568;
                font-size: 0.85rem;
                line-height: 1.4;
            }

            /* Form section for mobile */
            .mobile-form-container {
                background: rgba(59, 59, 59, 0.9);
                border-radius: 16px;
                padding: 25px 20px;
                margin-top: 25px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .mobile-form-title {
                text-align: center;
                color: white;
                font-size: 1.3rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .mobile-form-subtitle {
                text-align: center;
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.9rem;
                margin-bottom: 20px;
            }

            .mobile-input {
                background: rgba(255, 255, 255, 0.95) !important;
                border: 1px solid rgba(0, 40, 100, 0.2) !important;
                color: #333 !important;
            }

            .mobile-input::placeholder {
                color: #666 !important;
            }

            .mobile-label {
                color: white !important;
                font-weight: 500;
                margin-bottom: 8px;
            }

            /* Badge styling */
            .admin-badge {
                background: #FF581E; /* ORANGE - SAMA DENGAN CUSTOMER */
                color: white;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                display: inline-block;
                margin-left: 8px;
            }

            /* Test credentials styling */
            .test-credentials {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 12px;
                padding: 15px;
                margin-top: 20px;
                border-left: 4px solid #00215E; /* BIRU TUA - SAMA DENGAN CUSTOMER */
            }

            .test-credentials-title {
                color: #00215E; /* BIRU TUA - SAMA DENGAN CUSTOMER */
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .test-credentials-content {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .test-credential-item {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 12px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .test-credential-item:hover {
                background: #e9ecef;
                transform: translateY(-2px);
            }

            .test-credential-role {
                color: #FF581E; /* ORANGE - SAMA DENGAN CUSTOMER */
                font-weight: 600;
                font-size: 0.8rem;
                margin-bottom: 5px;
            }

            .test-credential-details {
                color: #4a5568;
                font-size: 0.75rem;
                line-height: 1.4;
            }
        }

        /* Desktop specific styles */
        .admin-desktop-badge {
            background: #FF581E; /* ORANGE - SAMA DENGAN CUSTOMER */
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }

        /* Button gradient - SAMA DENGAN CUSTOMER */
        .btn-gradient {
            background: linear-gradient(135deg, #FF581E 0%, #FF7A42 100%) !important;
            border: none !important;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #FF7A42 0%, #FF581E 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 88, 30, 0.4) !important;
        }

        /* Focus ring color - SAMA DENGAN CUSTOMER */
        .focus-ring-orange:focus {
            border-color: #FF581E !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 88, 30, 0.25) !important;
        }

        /* Custom orange color for links */
        .text-orange {
            color: #FF581E !important;
        }

        .hover-text-orange:hover {
            color: #FF581E !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile View -->
    <div class="md:hidden mobile-main-container">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-[#00215E]">Smart Shuttle</h1>
                <div class="w-16 h-1 bg-[#FF581E] mx-auto rounded-full mt-1"></div>
                <div class="admin-badge mt-2">Admin Portal</div>
            </div>
        </div>

        <div class="mobile-content-wrapper">
            <!-- Features Section for Mobile -->
            <div class="mobile-features-container">
                <!-- Header dengan kedua teks dalam satu frame -->
                <div class="mobile-features-header">
                    <h2 class="mobile-features-main-title">Admin Management System</h2>
                    <div class="mobile-features-subtitle">Akses sistem manajemen terpadu</div>
                </div>

                <!-- Feature 1 -->
                <div class="mobile-feature-item">
                    <div class="mobile-feature-content">
                        <div class="mobile-feature-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mobile-feature-title">Sistem Keamanan</p>
                            <p class="mobile-feature-desc">Akses terenkripsi dan terproteksi</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="mobile-feature-item">
                    <div class="mobile-feature-content">
                        <div class="mobile-feature-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mobile-feature-title">Manajemen Sistem</p>
                            <p class="mobile-feature-desc">Kelola seluruh operasional bisnis</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="mobile-feature-item">
                    <div class="mobile-feature-content">
                        <div class="mobile-feature-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mobile-feature-title">Analisis Data</p>
                            <p class="mobile-feature-desc">Pantau performa dan laporan bisnis</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section for Mobile -->
            <div class="mobile-form-container">
                <h2 class="mobile-form-title">Masuk ke Admin Panel</h2>
                <p class="mobile-form-subtitle">Masukkan kredensial admin untuk mengakses sistem</p>

                <!-- TAMPILKAN PESAN SUKSES JIKA ADA -->
                @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
                @endif

                <!-- TAMPILKAN ERROR JIKA ADA -->
                @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong>Gagal Login!</strong>
                    </div>
                    <ul class="mb-0 mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="text-sm">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- FORM -->
                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Admin
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
                            placeholder="Masukkan email admin"
                            required
                            autofocus
                            value="{{ old('email') }}"
                        />
                        @error('email')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="password-container">
                        <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
                                placeholder="Masukkan password admin"
                                required
                            />
                            <button type="button" class="password-toggle" id="togglePassword">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label class="flex items-center text-sm text-white">
                            <input type="checkbox" name="remember" value="1" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-4 h-4" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2">Ingat saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full btn-gradient text-white font-semibold py-3 rounded-lg mt-3 transition-all duration-300 active:scale-95 shadow-lg text-sm"
                        id="submitButton"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login Admin
                    </button>
                </form>

                <!-- Test Credentials for Development -->
                @if(app()->environment('local'))
                <div class="test-credentials mt-6">
                    <div class="test-credentials-title">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Test Credentials (Development Only)
                    </div>
                    <div class="test-credentials-content">
                        <div class="test-credential-item" onclick="fillCredentials('admin@smartshuttle.test', 'admin123')">
                            <div class="test-credential-role">Admin Pusat</div>
                            <div class="test-credential-details">
                                Email: admin@smartshuttle.test<br>
                                Password: admin123
                            </div>
                        </div>
                        <div class="test-credential-item" onclick="fillCredentials('jakarta@smartshuttle.test', 'password123')">
                            <div class="test-credential-role">Branch Admin</div>
                            <div class="test-credential-details">
                                Email: jakarta@smartshuttle.test<br>
                                Password: password123
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Link kembali ke beranda -->
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.beranda') }}" class="text-white hover:text-orange text-sm transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop View -->
    <div class="hidden md:flex flex-col min-h-screen md:flex-row">
        <!-- LEFT SIDE - Hanya ditampilkan di desktop -->
        <div class="hidden md:w-1/2 md:flex flex-col justify-center px-8 lg:px-16 relative left-overlay">
            <!-- Logo di kiri atas -->
            @php
                $logoPath = $masterKontak->logo ?? null;
                if ($logoPath) {
                    if (Str::contains($logoPath, '/')) {
                        $logoUrl = asset('storage/' . $logoPath);
                    } else {
                        $logoUrl = asset('storage/' . $logoPath);
                    }
                } else {
                    $logoUrl = asset('/images/smartshuttlelogo.png');
                }
            @endphp
            <div class="absolute top-4 left-4 lg:top-6 lg:left-6 flex items-center space-x-3">
                <div class="w-20 h-20 lg:w-20 lg:h-20 flex items-center justify-center">
                    <img src="{{ $logoUrl }}" alt="{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }}" class="w-full h-full object-contain">
                </div>
                <div class="admin-desktop-badge text-sm">Admin Portal</div>
            </div>

            <!-- Content -->
            <div class="relative z-10 mt-12 md:mt-0">
                <!-- Judul utama -->
                <div class="flex flex-col items-center mb-6 lg:mb-8">
                    <div class="w-full text-center">
                        <h1 class="text-3xl lg:text-5xl font-bold text-[#00215E] mb-2">Smart Shuttle</h1>
                        <div class="w-16 lg:w-24 h-1 bg-[#FF581E] mx-auto rounded-full"></div>
                    </div>
                </div>

                <p class="text-[#00215E] text-base lg:text-lg mt-4 lg:mt-6 font-medium leading-relaxed text-center">
                    Admin Management System<br>
                    Akses sistem manajemen terpadu
                </p>

                <div class="mt-8 lg:mt-12 space-y-4 lg:space-y-6">
                    <!-- Feature 1 -->
                    <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#00215E] text-sm lg:text-base">Sistem Keamanan</p>
                            <p class="text-[#00215E]/70 text-xs lg:text-sm">Akses terenkripsi dan terproteksi</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#00215E] text-sm lg:text-base">Manajemen Sistem</p>
                            <p class="text-[#00215E]/70 text-xs lg:text-sm">Kelola seluruh operasional bisnis</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#00215E] text-sm lg:text-base">Analisis Data</p>
                            <p class="text-[#00215E]/70 text-xs lg:text-sm">Pantau performa dan laporan bisnis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE FORM LOGIN - Desktop -->
        <div class="hidden md:flex md:w-1/2 items-start justify-center px-12 lg:px-20 text-white relative right-overlay min-h-screen pt-16">
            <div class="w-full max-w-md mx-auto">
                <div class="flex flex-col items-center mb-8">
                    <div class="w-full text-center">
                        <h2 class="text-4xl font-bold mb-4 text-white tracking-tight">
                            Masuk ke Admin Panel
                        </h2>
                        <p class="text-white/70 text-base">
                            Masukkan kredensial admin untuk mengakses sistem
                        </p>
                    </div>
                </div>

                <!-- TAMPILKAN PESAN SUKSES JIKA ADA -->
                @if(session('success'))
                <div class="mb-4 mt-8 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-base">
                    {{ session('success') }}
                </div>
                @endif

                <!-- TAMPILKAN ERROR JIKA ADA -->
                @if($errors->any())
                <div class="mb-4 mt-8 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-base">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong>Gagal Login!</strong>
                    </div>
                    <ul class="mb-0 mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- FORM Desktop -->
                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5 mt-8" novalidate>
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Admin
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="w-full p-3 text-base rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30"
                            placeholder="Masukkan email admin"
                            required
                            autofocus
                            value="{{ old('email') }}"
                        />
                        @error('email')
                        <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="password-container">
                        <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password-desktop"
                                class="w-full p-3 text-base rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30"
                                placeholder="Masukkan password admin"
                                required
                            />
                            <button type="button" class="password-toggle" id="togglePasswordDesktop">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIconDesktop">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label class="flex items-center text-base">
                            <input type="checkbox" name="remember" value="1" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-4 h-4" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2 text-white/80">Ingat saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full btn-gradient text-white font-bold py-4 rounded-lg mt-5 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg hover:shadow-xl text-base"
                        id="submitButtonDesktop"
                    >
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login Admin
                    </button>
                </form>

                <!-- Test Credentials for Development -->
                @if(app()->environment('local'))
                <div class="mt-8 p-6 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-white font-semibold">Test Credentials (Development Only)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/20 p-4 rounded-lg cursor-pointer hover:bg-white/30 transition-all duration-300"
                             onclick="fillCredentialsDesktop('admin@smartshuttle.test', 'admin123')">
                            <div class="font-semibold text-white mb-1">Admin Pusat</div>
                            <div class="text-white/80 text-sm">
                                Email: admin@smartshuttle.test<br>
                                Password: admin123
                            </div>
                        </div>
                        <div class="bg-white/20 p-4 rounded-lg cursor-pointer hover:bg-white/30 transition-all duration-300"
                             onclick="fillCredentialsDesktop('jakarta@smartshuttle.test', 'password123')">
                            <div class="font-semibold text-white mb-1">Branch Admin</div>
                            <div class="text-white/80 text-sm">
                                Email: jakarta@smartshuttle.test<br>
                                Password: password123
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Link kembali ke beranda -->
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.beranda') }}" class="text-white hover:text-orange text-base transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk interaksi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle untuk mobile
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            // Password toggle untuk desktop
            const passwordInputDesktop = document.getElementById('password-desktop');
            const togglePasswordDesktop = document.getElementById('togglePasswordDesktop');
            const eyeIconDesktop = document.getElementById('eyeIconDesktop');

            // Form elements
            const formMobile = document.querySelector('.mobile-main-container form');
            const submitButtonMobile = document.getElementById('submitButton');
            const formDesktop = document.querySelector('.right-overlay form');
            const submitButtonDesktop = document.getElementById('submitButtonDesktop');

            function togglePasswordVisibility(input, icon) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                if (type === 'text') {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    `;
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            }

            // Event listeners untuk mobile
            if (togglePassword) {
                togglePassword.addEventListener('click', () => togglePasswordVisibility(passwordInput, eyeIcon));
            }

            // Event listeners untuk desktop
            if (togglePasswordDesktop) {
                togglePasswordDesktop.addEventListener('click', () => togglePasswordVisibility(passwordInputDesktop, eyeIconDesktop));
            }

            // Form submission handling untuk mobile
            if (formMobile) {
                formMobile.addEventListener('submit', function() {
                    submitButtonMobile.disabled = true;
                    submitButtonMobile.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;
                });
            }

            // Form submission handling untuk desktop
            if (formDesktop) {
                formDesktop.addEventListener('submit', function() {
                    submitButtonDesktop.disabled = true;
                    submitButtonDesktop.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;
                });
            }

            // Prevent zoom on input focus on mobile
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    setTimeout(() => {
                        window.scrollTo(0, 0);
                        document.body.style.height = '100%';
                        document.body.style.overflow = 'hidden';
                    }, 100);
                });

                input.addEventListener('blur', () => {
                    document.body.style.height = '';
                    document.body.style.overflow = '';
                });
            });
        });

        // Function to fill test credentials (mobile)
        function fillCredentials(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.getElementById('password').value = password;

            // Show notification
            showNotification(`Mengisi kredensial untuk ${email}`);
        }

        // Function to fill test credentials (desktop)
        function fillCredentialsDesktop(email, password) {
            document.querySelector('.right-overlay input[name="email"]').value = email;
            document.getElementById('password-desktop').value = password;

            // Show notification
            showNotification(`Mengisi kredensial untuk ${email}`);
        }

        // Function to show notification
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fade-out {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-10px); }
            }

            .animate-fade-in {
                animation: fade-in 0.3s ease-out;
            }

            .animate-fade-out {
                animation: fade-out 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
