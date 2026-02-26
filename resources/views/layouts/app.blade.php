<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? ($masterKontak->nama_perusahaan ?? 'Smart Shuttle') }}</title>

    <!-- CSS OFFLINE -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #00215E;
            --secondary-color: #FF581E;
            --accent-color: #3498db;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-color: #333333;
            --muted-text: #7f8c8d;
            --whatsapp-green: #25D366;
            --phone-blue: #3498DB;
        }

        /* RESET UTAMA */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR - TIDAK STICKY LAGI */
        .navbar-main-wrapper {
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative; /* Diubah dari 'sticky' menjadi 'relative' */
            z-index: 1000;
            display: flex;
            justify-content: center;
            padding: 0 !important;
            margin: 0 !important;
            left: 0;
            right: 0;
        }

        .navbar-main-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto !important;
            padding: 0 20px !important;
        }

        /* Navbar Bootstrap override */
        .navbar {
            padding: 0.5rem 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .navbar > .container,
        .navbar > .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin: 0 auto !important;
            padding: 0 15px !important;
            max-width: 100% !important;
        }

        /* Navbar brand */
        .navbar-brand {
            margin-right: auto;
            padding-left: 0 !important;
        }

        /* Navbar links */
        .navbar-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            margin: 0 !important;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 1.2rem !important;
            margin: 0 0.1rem;
        }

        /* Navbar toggler */
        .navbar-toggler {
            margin-left: auto;
            border: none;
            padding: 0.25rem 0.5rem;
        }

        /* Main content area */
        main {
            flex: 1;
            width: 100%;
            position: relative;
        }

        /* Content wrapper */
        .content-wrapper {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Fix untuk modal */
        .modal-backdrop {
            z-index: 1040;
        }

        .modal {
            z-index: 1050;
        }

        /* ========== GLOBAL FLOATING CUSTOMER SERVICE BUTTONS ========== */
        .floating-cs-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .cs-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            text-decoration: none;
            animation: float 3s ease-in-out infinite;
        }

        .cs-button.whatsapp {
            background: linear-gradient(135deg, var(--whatsapp-green), #128C7E);
        }

        .cs-button.phone {
            background: linear-gradient(135deg, var(--phone-blue), #2980b9);
        }

        .cs-button i {
            color: white;
            font-size: 28px;
        }

        .cs-button:hover {
            transform: translateY(-8px) scale(1.15);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .cs-button:hover i {
            transform: rotate(15deg) scale(1.1);
        }

        /* Tooltip */
        .cs-button::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .cs-button:hover::after {
            opacity: 1;
        }

        /* Animasi floating */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive design */
        @media (max-width: 1400px) {
            .navbar-main-container,
            .content-wrapper {
                padding: 0 30px !important;
            }
        }

        @media (max-width: 992px) {
            .navbar-nav {
                text-align: center;
                padding: 1rem 0;
            }

            .navbar-collapse {
                background: white;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                margin-top: 10px;
            }
        }

        @media (max-width: 768px) {
            .navbar-main-container {
                padding: 0 15px !important;
            }

            .floating-cs-container {
                bottom: 20px;
                right: 20px;
            }

            .cs-button {
                width: 55px;
                height: 55px;
            }

            .cs-button i {
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            .navbar-main-container {
                padding: 0 10px !important;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .floating-cs-container {
                bottom: 15px;
                right: 15px;
            }

            .cs-button {
                width: 50px;
                height: 50px;
            }

            .cs-button i {
                font-size: 20px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Header Wrapper --}}
    <div class="navbar-main-wrapper">
        <div class="navbar-main-container">
            @if(isset($isDriver) && $isDriver)
                @include('layouts.header-driver')
            @else
                @include('layouts.header')
            @endif
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @if(!isset($isDriver) || !$isDriver)
        @include('layouts.footer')
    @endif

    <!-- Floating WA & Telp Buttons - SEMUA HALAMAN -->
    @if(!isset($isDriver) || !$isDriver)
        @if(isset($masterKontak) && $masterKontak)
            <div class="floating-cs-container">
                <!-- WhatsApp Button -->
                @php
                    $whatsappNumber = $masterKontak->telepon_utama ?? '085811224321';
                    $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
                    if (substr($whatsappNumber, 0, 1) === '0') {
                        $whatsappNumber = '62' . substr($whatsappNumber, 1);
                    }
                    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=Halo%20" . urlencode($masterKontak->nama_perusahaan ?? 'Smart Shuttle') . "%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20shuttle.";
                @endphp
                <a href="{{ $whatsappUrl }}"
                   target="_blank"
                   class="cs-button whatsapp"
                   data-tooltip="Chat WhatsApp"
                   title="Chat via WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>

                <!-- Phone Button -->
                @php
                    $phoneNumber = $masterKontak->telepon_utama ?? '0858-1122-4321';
                    $phoneUrl = "tel:" . preg_replace('/[^0-9+]/', '', $phoneNumber);
                @endphp
                <a href="{{ $phoneUrl }}"
                   class="cs-button phone"
                   data-tooltip="Telepon Customer Service"
                   title="Telepon Customer Service">
                    <i class="fas fa-phone"></i>
                </a>
            </div>
        @else
            <!-- Default jika $masterKontak tidak ada -->
            <div class="floating-cs-container">
                <a href="https://wa.me/6285811224321?text=Halo%20Smart%20Shuttle%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20shuttle."
                   target="_blank"
                   class="cs-button whatsapp"
                   data-tooltip="Chat WhatsApp"
                   title="Chat via WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="tel:+6285811224321"
                   class="cs-button phone"
                   data-tooltip="Telepon Customer Service"
                   title="Telepon Customer Service">
                    <i class="fas fa-phone"></i>
                </a>
            </div>
        @endif
    @endif

    <!-- JavaScript OFFLINE -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi modal Bootstrap
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modalEl) {
                try {
                    new bootstrap.Modal(modalEl);
                } catch (err) {
                    console.warn('Modal init error: ', err);
                }
            });

            // Highlight menu aktif
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Debug: Cek floating button
            console.log('Floating button aktif di semua halaman');
        });
    </script>

    <!-- Global Scripts for Route Outlets Helper -->
    <script src="{{ asset('js/route-outlets-helper.js') }}"></script>

    @stack('scripts')
</body>
</html>
