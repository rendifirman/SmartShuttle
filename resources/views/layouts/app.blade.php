<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Smart Shuttle' }}</title>
    
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
            --muted-tex t: #7f8c8d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    </style>
    @stack('styles')
</head>
<body>
    {{-- Header berdasarkan role --}}
    @if(isset($isDriver) && $isDriver)
        @include('layouts.header-driver')
    @else
        @include('layouts.header')
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer berdasarkan role --}}
    @if(!isset($isDriver) || !$isDriver)
        @include('layouts.footer')
    @endif

    <!-- JavaScript OFFLINE -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Script untuk memastikan modal bekerja -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan modal Bootstrap terinisialisasi
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modalEl) {
                try {
                    new bootstrap.Modal(modalEl);
                } catch (err) {
                    console.warn('Gagal inisialisasi modal: ', err);
                }
            });

            // Debug: Log untuk memastikan script berjalan
            console.log('Modal script loaded');
            
            // Test modal manually jika diperlukan (cek dulu elemen ada)
            if (document.getElementById('outletModal')) {
                window.testModal = function() {
                    try {
                        var modal = new bootstrap.Modal(document.getElementById('outletModal'));
                        modal.show();
                    } catch (err) {
                        console.warn('Gagal membuka outletModal:', err);
                    }
                };
            } else {
                window.testModal = function() {
                    console.warn('Tidak ada element #outletModal di halaman ini.');
                };
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
