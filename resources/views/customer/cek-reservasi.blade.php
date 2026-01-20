@extends('layouts.app')

@section('title', 'Cek Reservasi - Smart Shuttle')

@push('styles')
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
        width: 100%;
        max-width: 100%;
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

    /* ========== FORM PANJANG KESAMPING ========== */
    .hero-box {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        display: flex;
        width: 100%;
        margin-top: 40px;
        padding: 10px;
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
        font-size: 18px;
        border-radius: 14px;
        color: #333;
        background: transparent;
        width: 100%;
        min-width: 0;
    }

    .hero-box input::placeholder {
        color: #666;
        font-size: 17px;
    }

    .hero-box button {
        background: linear-gradient(135deg, #FF581E, #ff7b4d);
        border: none;
        color: white;
        padding: 0 60px;
        border-radius: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s;
        font-size: 17px;
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        flex-shrink: 0;
        margin-left: 10px;
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
            max-width: 100%;
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
            gap: 15px;
            padding: 20px;
            margin-top: 30px;
        }

        .hero-box input {
            width: 100%;
            padding: 18px;
            font-size: 16px;
            margin-bottom: 0;
        }

        .hero-box button {
            width: 100%;
            padding: 18px;
            justify-content: center;
            font-size: 16px;
            margin-left: 0;
            gap: 10px;
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
            padding: 16px;
            font-size: 15px;
        }
    }
</style>
@endpush

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
        <div class="hero-content">
            <h1 class="hero-title">Cek Reservasi</h1>
            <p class="hero-desc">
                Verifikasi status perjalanan Anda dengan mudah. Cukup masukkan kode booking yang Anda terima saat pemesanan untuk mendapatkan informasi lengkap tiket Anda.
            </p>

            <!-- FORM PANJANG KESAMPING -->
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endpush
