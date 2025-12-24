    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Smart Shuttle' }}</title>

        <!-- CSS OFFLINE -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <style>
            :root {
                --primary-color: #00215E;
                --secondary-color: #FF581E;
                --accent-color: #3498db;
                --light-bg: #f8f9fa;
                --card-bg: #ffffff;
                --text-color: #333333;
                --muted-text: #7f8c8d;
            }

            body {
                font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: var(--light-bg);
                color: var(--text-color);
                margin: 0;
                padding: 0;
                line-height: 1.6;
            }

            /* Fix untuk modal */
            .modal-backdrop {
                z-index: 1040;
            }

            .modal {
                z-index: 1050;
            }

            /* Pastikan main content tidak tertutup */
            main {
                min-height: calc(100vh - 120px);
                position: relative;
            }

            /* === NAVBAR STYLES === */
            /* Reset margin dan padding untuk body */
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                background-color: white;
            }

            /* Custom Navbar Styles - TRANSPARAN */
            .custom-navbar {
                background: white; /* Untuk app.blade.php, gunakan background putih */
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
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
                background: white;
                border-radius: 50px;
                padding: 8px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
                gap: 25px; /* Diperkecil sedikit agar semua menu muat */
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .nav-links a {
                text-decoration: none;
                color: var(--primary-color);
                font-weight: 500;
                font-size: 0.95rem; /* Diperkecil sedikit */
                transition: color 0.3s;
                position: relative;
                white-space: nowrap;
                font-family: 'Roboto', sans-serif;
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
                align-items: center;
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
                text-decoration: none;
                display: inline-block;
                text-align: center;
                font-family: 'Roboto', sans-serif;
            }

            .btn-login:hover {
                background-color: var(--secondary-color);
                transform: translateY(-2px);
                text-decoration: none;
                color: white;
            }

            /* Navbar saat di-scroll */
            .custom-navbar.scrolled {
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .custom-navbar.scrolled .nav-panel {
                background: white;
                box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            }

            /* Profile icon + small name - PERBAIKAN */
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
                background: linear-gradient(135deg, var(--secondary-color), #ff7b4d);
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
                color: var(--primary-color);
                font-weight: 600;
                max-width: 110px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-family: 'Roboto', sans-serif;
            }

            /* Dropdown Menu - PERBAIKAN */
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
                color: var(--primary-color);
                text-decoration: none;
                border-radius: 0;
                margin: 0;
                transition: background-color 0.2s;
                font-size: 14px;
                font-family: 'Roboto', sans-serif;
            }

            .dropdown-menu a:hover {
                background-color: rgba(255, 88, 30, 0.1);
                color: var(--secondary-color);
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
                color: var(--primary-color);
                cursor: pointer;
                font-family: 'Roboto', sans-serif;
                font-size: 14px;
                transition: background-color 0.2s;
            }

            .dropdown-menu button[type="submit"]:hover {
                background-color: rgba(255, 88, 30, 0.1);
                color: var(--secondary-color);
            }

            /* Tambahkan class untuk show */
            .dropdown-menu.show {
                display: block;
            }

            /* Pastikan tombol login di navbar bisa diklik */
            .nav-auth a.btn-login {
                pointer-events: auto !important;
                position: relative;
                z-index: 10;
            }

            /* Responsive Styles untuk Navbar */
            @media (max-width: 1024px) {
                .nav-panel {
                    padding: 8px 20px;
                }

                .nav-links {
                    gap: 20px; /* Lebih kecil di tablet */
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
            }

            @media (max-width: 480px) {
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
        @stack('styles')
    </head>
    <body>
        {{-- Ambil data perusahaan dari database --}}
        @php
            use App\Models\MProfilePerusahaan;
            $profilePerusahaan = MProfilePerusahaan::first();

            // Ambil data user dari session
            $user = session()->get('user', null);
        @endphp

        {{-- Header berdasarkan role --}}
        @if(isset($isDriver) && $isDriver)
            @include('layouts.header-driver', ['profilePerusahaan' => $profilePerusahaan])
        @else
            <!-- Custom Navbar -->
            <nav class="custom-navbar" id="navbar">
                <div class="nav-container">
                    <div class="nav-panel">
                        <div class="nav-brand">
                            <img src="{{ asset($profilePerusahaan->logo_perusahaan ?? '/images/smartshuttlelogo.png') }}" alt="{{ $profilePerusahaan->nama_dagang ?? 'Smart Shuttle' }}">
                        </div>
                        <div class="nav-menu">
                            <ul class="nav-links">
                                <li><a href="/customer/beranda" class="{{ request()->is('customer/beranda') ? 'active' : '' }}">Beranda</a></li>
                                <li><a href="{{ route('customer.search') }}" class="{{ request()->is('customer/search') ? 'active' : '' }}">Cari Tiket</a></li>
                                <li><a href="{{ route('customer.outlet') }}" class="{{ request()->is('customer/outlet') ? 'active' : '' }}">Outlet</a></li>
                                <li><a href="{{ route('customer.contact') }}" class="{{ request()->is('customer/contact') ? 'active' : '' }}">Kontak</a></li>
                                <!-- Menu baru ditambahkan di sini -->
                                <li><a href="#" onclick="alert('Fitur Kirim Paket akan segera hadir!'); return false;">Kirim Paket</a></li>
                                <li><a href="#" onclick="alert('Fitur Sewa Armada akan segera hadir!'); return false;">Sewa Armada</a></li>
                                <li><a href="#" onclick="alert('Fitur Cek Reservasi akan segera hadir!'); return false;">Cek Reservasi</a></li>
                            </ul>
                        </div>
                        <!-- BAGIAN NAV-AUTH -->
                        <div class="nav-auth">
                            @if($user && isset($user['id']) && $user['id'])
                                <div class="profile-wrapper">
                                    <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                                        @if(!empty($user['avatar']))
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
                                        <a href="{{ route('customer.riwayat') }}">Riwayat</a>
                                        <form action="{{ route('customer.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('customer.login') }}" class="btn-login" id="login-btn">Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <main style="padding-top: 80px;"> {{-- Tambahkan padding-top untuk mengkompensasi navbar fixed --}}
            @yield('content')
        </main>

        {{-- Footer berdasarkan role --}}
        @if(!isset($isDriver) || !$isDriver)
            @include('layouts.footer', ['profilePerusahaan' => $profilePerusahaan])
        @endif

        <!-- JavaScript OFFLINE -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Script untuk memastikan modal bekerja dan navbar -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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

                /* ---------- PROFILE DROPDOWN - PERBAIKAN ---------- */
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

                    // Hanya tutup dropdown jika klik di luar dropdown DAN tombol profile
                    document.addEventListener('click', function (e) {
                        if (dropdownMenu.classList.contains('show')) {
                            // Jika klik di luar dropdown DAN di luar tombol profile
                            if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
                                dropdownMenu.classList.remove('show');
                                dropdownButton.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });

                    // Tutup dropdown saat tombol Escape ditekan
                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                            dropdownMenu.classList.remove('show');
                            dropdownButton.setAttribute('aria-expanded', 'false');
                            dropdownButton.focus();
                        }
                    });

                    // Tutup dropdown saat item dipilih
                    dropdownMenu.addEventListener('click', function (e) {
                        if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                            dropdownMenu.classList.remove('show');
                            dropdownButton.setAttribute('aria-expanded', 'false');
                        }
                    });
                }

                /* ---------- MODAL BOOTSTRAP ---------- */
                var modals = document.querySelectorAll('.modal');
                modals.forEach(function(modalEl) {
                    try {
                        new bootstrap.Modal(modalEl);
                    } catch (err) {
                        console.warn('Gagal inisialisasi modal: ', err);
                    }
                });

                // Session messages
                const successMsg = @json(session('success'));
                const errorMsg = @json(session('error'));

                if (successMsg) {
                    alert(successMsg);
                }
                if (errorMsg) {
                    alert(errorMsg);
                }
            });
        </script>

        @stack('scripts')
    </body>
    </html>
