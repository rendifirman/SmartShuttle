@extends('layouts.app')

@section('title', 'Sewa Mobil - SmartRent')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* CSS Variables */
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --modal-bg: rgba(74, 66, 62, 0.50);
        --whatsapp-green: #25D366;
        --phone-blue: #3498DB;
    }

    /* FIX: Reset margin dan padding untuk body - TAMBAHKAN overflow-x: hidden */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden; /* FIX: Mencegah scroll horizontal */
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background-color: white;
        width: 100%;
    }

    .content-wrapper {
        flex: 1;
        background-color: white;
        width: 100%;
        overflow-x: hidden; /* FIX: Tambahkan ini */
    }

    /* ========== HERO SECTION MOBILE FIX ========== */
    .hero-section {
        position: relative;
        height: 100vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0 6%;
        margin-bottom: 30px;
        width: 100%;
        overflow: hidden; /* FIX: Hindari overflow */
        margin-top: -60px; /* Untuk mengkompensasi navbar fixed */
        padding-top: 60px; /* Untuk memberi ruang untuk navbar */
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 50%;
        color: white;
        width: 100%;
    }

    .hero-title {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
        font-family: 'Roboto', sans-serif;
        line-height: 1.1;
    }

    .hero-desc {
        font-size: 18px;
        line-height: 1.7;
        max-width: 520px;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }

    .hero-services {
        display: flex;
        text-decoration: none;
        justify-content: flex-start;
        gap: 8px;
        margin-top: 35px;
        max-width: 400px;
        width: 100%;
    }

    .hero-service {
        text-decoration: none;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        backdrop-filter: blur(6px);
        transition: background 0.3s, transform 0.3s, border 0.3s;
        flex: 1;
        min-width: 110px;
        border: 2px solid transparent;
        font-family: 'Roboto', sans-serif;
    }

    .hero-service:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05);
    }

    .hero-service.active {
        background: rgba(255, 255, 255, 0.3) !important;
        border: 2px solid var(--secondary-color) !important;
        transform: scale(1.05);
    }

    .hero-service i {
        font-size: 32px;
        color: #fff;
    }

    .hero-service span {
        color: #fff;
        font-weight: 600;
        font-family: 'Roboto', sans-serif;
    }

    /* ========== SEARCH SECTION MOBILE FIX ========== */
    .search-section {
        position: relative;
        z-index: 20;
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: -138px;
        background: transparent;
        padding: 0 20px; /* FIX: Tambahkan padding */
        box-sizing: border-box;
    }

    .search-container {
        width: 100%;
        max-width: 1200px;
        background: rgba(255, 255, 255, 0.25);
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.18);
        overflow: hidden; /* FIX: Hindari overflow */
    }

    .search-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 14px;
        align-items: center;
        width: 100%;
    }

    /* FIELD */
    .search-field {
        width: 100%;
        position: relative;
        height: auto;
        min-height: fit-content;
    }

    .search-input {
        width: 100%;
        height: 48px;
        border-radius: 6px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
        background: #ffffff;
        color: black;
        font-weight: bold;
        padding: 0 12px;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.25);
    }

    /* PERBAIKAN SELECT2 DI BERANDA - STYLE SAMA PERSIS */
    .search-field .select2-container {
        width: 100% !important;
    }

    .search-field .select2-selection {
        height: 48px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
    }

    .search-field .select2-selection__rendered {
        line-height: 46px !important;
        color: black !important;
        font-size: 14px !important;
        padding-left: 12px !important;
        font-weight: bold !important;
        font-family: 'Roboto', sans-serif !important;
    }

    .search-field .select2-selection__arrow {
        height: 46px !important;
    }

    .search-field .select2-dropdown {
        border: 2px solid #e0e0e0 !important;
        border-radius: 6px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        z-index: 1051 !important; /* Tambahkan z-index tinggi */
    }

    /* Fix untuk dropdown Select2 */
    .select2-container--open .select2-dropdown {
        z-index: 1052 !important; /* Lebih tinggi dari container */
    }

    /* BUTTON */
    .search-btn-container {
        height: 48px;
    }

    .search-btn {
        height: 100%;
        border-radius: 12px;
        background: white;
        color: var(--secondary-color);
        border: 2px solid var(--secondary-color);
        font-weight: 700;
        padding: 0 32px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Roboto', sans-serif;
    }

    .search-btn:hover {
        background: var(--secondary-color);
        color: white;
    }

    /* ========== VEHICLES SECTION REVISI ========== */
    .smartrent-vehicles-section {
        padding: 80px 0;
        background: white;
        width: 100%;
    }

    .vehicles-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .vehicles-header {
        text-align: center;
        margin-bottom: 50px;
    }

    /* PERBAIKAN: Judul lebih bold */
    .vehicles-title {
        font-size: 32px;
        font-weight: 900 !important; /* Lebih bold */
        color: var(--primary-color);
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .vehicles-subtitle {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
        font-family: 'Roboto', sans-serif;
    }

    .vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .vehicle-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        position: relative;
    }

    .vehicle-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .vehicle-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }

    .vehicle-content {
        padding: 25px;
    }

    .vehicle-name {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
        font-family: 'Roboto', sans-serif;
    }

    .vehicle-type {
        display: inline-block;
        background: #f0f7ff;
        color: var(--primary-color);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .vehicle-specs {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .spec-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 5px;
        color: #666;
        font-size: 14px;
        font-family: 'Roboto', sans-serif;
    }

    .spec-icon {
        width: 40px;
        height: 40px;
        background: #f5f5f5;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
    }

    .spec-icon i {
        color: var(--secondary-color);
        font-size: 18px;
    }

    .spec-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 2px;
    }

    .spec-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .vehicle-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .price-label {
        font-size: 14px;
        color: #666;
        font-family: 'Roboto', sans-serif;
    }

    .price-amount {
        font-size: 24px;
        font-weight: 700;
        color: var(--secondary-color);
        font-family: 'Roboto', sans-serif;
    }

    .price-period {
        font-size: 14px;
        color: #666;
        font-weight: 500;
        font-family: 'Roboto', sans-serif;
    }

    .vehicle-actions {
        display: flex;
        gap: 10px;
    }

    .btn-rent {
        flex: 1;
        padding: 12px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Roboto', sans-serif;
    }

    .btn-rent:hover {
        background: #E54E1A;
        transform: translateY(-2px);
    }

    .btn-detail {
        flex: 1;
        padding: 12px;
        background: white;
        color: var(--primary-color);
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Roboto', sans-serif;
    }

    .btn-detail:hover {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
        background: #fffaf8;
    }

    /* ===== TOMBOL LIHAT SEMUA MOBIL ===== */
    .view-all-container {
        text-align: center;
        margin-top: 50px;
        margin-bottom: 60px;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 40px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(255, 88, 30, 0.25);
        font-family: 'Roboto', sans-serif;
        text-decoration: none;
    }

    .btn-view-all:hover {
        background: #E54E1A;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 88, 30, 0.35);
    }

    .btn-view-all i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .btn-view-all:hover i {
        transform: translateX(5px);
    }

    /* ===== CARA PEMESANAN SMARTRENT STYLE (SAMA DENGAN SMARTSEND) ===== */
    .cara-pemesanan-section {
        padding: 60px 20px;
        background: #ffffff;
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* PERBAIKAN: Judul lebih bold */
    .cara-pemesanan-title {
        font-size: 28px;
        font-weight: 900 !important; /* Lebih bold */
        color: #123352;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .cara-pemesanan-subtitle {
        max-width: 780px;
        margin: 0 auto 40px;
        font-size: 14px;
        line-height: 1.7;
        color: #1a4a7a;
        font-weight: 500;
    }

    .cara-pemesanan-steps {
        max-width: 900px;
        margin: 0 auto;
    }

    .cara-step {
        position: relative;
        background: linear-gradient(135deg, #123352, #1a4a7a);
        border-radius: 12px;
        padding: 22px 25px 22px 80px;
        margin-bottom: 20px;
        box-shadow: 0 6px 20px rgba(18, 51, 82, 0.18);
        text-align: left;
        color: white;
    }

    .step-number {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background: #ff581e;
        color: #fff;
        border-radius: 50%;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(255, 88, 30, 0.4);
    }

    .step-content h3 {
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .step-content p {
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    /* ========== FLOATING BUTTONS ========== */
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
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .cs-button.whatsapp {
        background: linear-gradient(135deg, #25D366, #128C7E);
    }

    .cs-button.phone {
        background: linear-gradient(135deg, #3498DB, #2980b9);
    }

    .cs-button i {
        color: white;
        font-size: 24px;
        z-index: 1;
    }

    .cs-button:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 42px;
        }

        .search-row {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .search-btn {
            grid-column: span 2;
            width: 100%;
        }

        .vehicles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        /* HERO MOBILE */
        .hero-section {
            height: auto;
            min-height: 90vh;
            padding: 120px 20px 60px;
            background-position: center center;
            width: 100%;
            max-width: 100vw;
            overflow: hidden;
        }

        .hero-content {
            max-width: 100%;
            text-align: center;
            width: 100%;
            padding: 0 10px;
        }

        .hero-title {
            font-size: 32px;
            margin-bottom: 15px;
            line-height: 1.2;
            word-wrap: break-word;
        }

        .hero-desc {
            font-size: 15px;
            margin: 0 auto 20px;
            max-width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .hero-services {
            flex-direction: column;
            max-width: 100%;
            gap: 12px;
            padding: 0 10px;
            width: 100%;
        }

        .hero-service {
            width: 100%;
            max-width: 280px;
            margin: 0 auto;
            padding: 15px;
            flex-direction: row;
            justify-content: center;
            gap: 15px;
            box-sizing: border-box;
        }

        .hero-service i {
            font-size: 28px;
        }

        .hero-service span {
            font-size: 15px;
        }

        /* SEARCH MOBILE */
        .search-section {
            margin-top: -120px;
            padding: 0 15px;
            width: 100%;
            box-sizing: border-box;
        }

        .search-container {
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .search-row {
            grid-template-columns: 1fr;
            gap: 15px;
            width: 100%;
        }

        .search-btn {
            grid-column: span 1;
            width: 100%;
        }

        .vehicles-grid {
            grid-template-columns: 1fr;
        }

        .vehicle-specs {
            grid-template-columns: repeat(3, 1fr);
        }

        .vehicle-actions {
            flex-direction: column;
        }

        .cara-step {
            padding: 22px 25px 22px 70px;
        }

        .step-number {
            width: 35px;
            height: 35px;
            font-size: 18px;
            left: 15px;
        }

        /* Tombol View All Responsive */
        .btn-view-all {
            padding: 14px 32px;
            font-size: 15px;
        }

        .floating-cs-container {
            bottom: 20px;
            right: 20px;
        }

        .cs-button {
            width: 50px;
            height: 50px;
        }

        .cs-button i {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 28px;
            padding: 0 5px;
        }

        .hero-desc {
            font-size: 14px;
            padding: 0 5px;
        }

        .hero-service {
            padding: 12px;
            max-width: 100%;
        }

        .hero-service i {
            font-size: 24px;
        }

        .hero-service span {
            font-size: 13px;
        }

        .vehicles-title {
            font-size: 24px;
            font-weight: 900 !important;
        }

        .vehicle-name {
            font-size: 20px;
        }

        .price-amount {
            font-size: 20px;
        }

        .vehicle-specs {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .spec-item {
            font-size: 12px;
        }

        .cara-pemesanan-title {
            font-size: 24px;
            font-weight: 900 !important;
        }

        .cara-step {
            padding: 20px 15px 20px 60px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            font-size: 16px;
            left: 15px;
        }

        .step-content h3 {
            font-size: 16px;
        }

        .step-content p {
            font-size: 13px;
        }

        .btn-view-all {
            padding: 12px 24px;
            font-size: 14px;
            width: 90%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
@php
    use App\Models\MProfilePerusahaan;
    $profile = MProfilePerusahaan::first();
    
    // Data mobil shuttle dengan layout yang diminta
    $vehicles = [
        [
            'id' => 1,
            'name' => 'Toyota Hiace Commuter',
            'type' => 'Shuttle | Manual',
            'image' => asset('images/shuttle1.jpeg'),
            'seats' => '12 Seat',
            'luggage' => '4 Koper',
            'fuel' => 'Bensin',
            'price' => '1.200.000',
            'period' => '/hari',
            'available' => true
        ],
        [
            'id' => 2,
            'name' => 'Isuzu Elf Long',
            'type' => 'MPV | Manual',
            'image' => asset('images/shuttle1.jpeg'),
            'seats' => '18 Seat',
            'luggage' => '6 Koper',
            'fuel' => 'Solar',
            'price' => '1.500.000',
            'period' => '/hari',
            'available' => true
        ],
        [
            'id' => 3,
            'name' => 'Mitsubishi L300',
            'type' => 'Shuttle | Manual',
            'image' => asset('images/shuttle1.jpeg'),
            'seats' => '8 Seat',
            'luggage' => 'Besar',
            'fuel' => 'Solar',
            'price' => '800.000',
            'period' => '/hari',
            'available' => true
        ],
       
    ];
@endphp

<!-- Hero Section - SAMA PERSIS seperti di beranda -->
<div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
   <div class="hero-content">
        <h1 class="hero-title">SmartRent </h1>
        <p class="hero-desc">
            Layanan penyewaan armada terbaik yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan kenyamanan maksimal dan harga terbaik.
        </p>
        <div class="hero-services">
           <a href="{{ route('customer.beranda') }}" class="hero-service">
                <i class="fas fa-shuttle-van"></i>
                <span>Tiket Shuttle</span>
            </a>

            <a href="{{ route('customer.smartsend') }}" class="hero-service">
                <i class="fas fa-box"></i>
                <span>Kirim Paket</span>
            </a>

<a href="{{ route('customer.smartrent') }}" class="hero-service active">
    <i class="fas fa-car"></i>
    <span>Sewa Armada</span>
</a>

        </div>
    </div>
</div>

<!-- Search Section - SAMA PERSIS seperti di beranda -->
<div class="search-section">
    <div class="search-container">
        <!-- Form SmartRent -->
        <form class="search-form" id="smartrent-search-form">
            <div class="search-row">
                <div class="search-field">
                    <select class="search-input" id="smartrent-city" name="city">
                        <option value="">Pilih Kota</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="bandung">Bandung</option>
                        <option value="surabaya">Surabaya</option>
                        <option value="yogyakarta">Yogyakarta</option>
                        <option value="bali">Bali</option>
                        <option value="semarang">Semarang</option>
                        <option value="medan">Medan</option>
                        <option value="makassar">Makassar</option>
                    </select>
                </div>
                
                <div class="search-field">
                    <select class="search-input" id="smartrent-vehicle-type" name="vehicle_type">
                        <option value="">Semua Tipe</option>
                        <option value="shuttle">Shuttle</option>
                        <option value="family">Family</option>
                        <option value="barang">Barang</option>
                        <option value="luxury">Luxury</option>
                        <option value="mpv">MPV</option>
                    </select>
                </div>
                
                <div class="search-field">
                    <input type="date" class="search-input" name="rent_date" 
                           min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                </div>
                
                <div class="search-btn-container">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        Cari Armada
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Vehicles Section dengan layout baru -->
<section class="smartrent-vehicles-section" id="vehicles">
    <div class="vehicles-container">
        <div class="vehicles-header">
            <h2 class="vehicles-title">Sewa Mobil SmartRent</h2>
            <p class="vehicles-subtitle">
                Pilih Kendaraan Sesuai Kebutuhan Anda. Armada terawat dan sopir berpengalaman siap melayani perjalanan Anda.
            </p>
        </div>
        
        <div class="vehicles-grid">
            @foreach($vehicles as $vehicle)
            <div class="vehicle-card">
                @if($vehicle['available'])
                <div style="position: absolute; top: 15px; left: 15px; background: var(--secondary-color); color: white; padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 2;">
                    Tersedia
                </div>
                @endif
                
                <img src="{{ $vehicle['image'] }}" alt="{{ $vehicle['name'] }}" class="vehicle-image"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-vehicle.jpg') }}';">
                
                <div class="vehicle-content">
                    <h3 class="vehicle-name">{{ $vehicle['name'] }}</h3>
                    <span class="vehicle-type">{{ $vehicle['type'] }}</span>
                    
                    <div class="vehicle-specs">
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-chair"></i>
                            </div>
                            <div class="spec-label">Kapasitas</div>
                            <div class="spec-value">{{ $vehicle['seats'] }}</div>
                        </div>
                        
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-suitcase"></i>
                            </div>
                            <div class="spec-label">Bagasi</div>
                            <div class="spec-value">{{ $vehicle['luggage'] }}</div>
                        </div>
                        
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-gas-pump"></i>
                            </div>
                            <div class="spec-label">Bahan Bakar</div>
                            <div class="spec-value">{{ $vehicle['fuel'] }}</div>
                        </div>
                    </div>
                    
                    <div class="vehicle-price">
                        <div>
                            <div class="price-label">Mulai dari</div>
                            <div class="price-amount">Rp {{ $vehicle['price'] }}</div>
                        </div>
                        <div class="price-period">{{ $vehicle['period'] }}</div>
                    </div>
                    
                    <div class="vehicle-actions">
                        <button class="btn-rent" onclick="rentVehicle({{ $vehicle['id'] }})">
                            <i class="fas fa-calendar-check"></i>
                            Sewa Mobil
                        </button>
         {{-- Atau gunakan alias --}}
<button class="btn-detail" onclick="window.location.href='{{ route('smartrent.detail', ['id' => $vehicle['id']]) }}'">
    <i class="fas fa-info-circle"></i>
    Detail
</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- TOMBOL LIHAT SEMUA MOBIL -->
        <div class="view-all-container">
            <button class="btn-view-all" onclick="viewAllVehicles()">
                Lihat Semua Mobil
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Cara Pemesanan SmartRent Section (Sama dengan SmartSend) -->
<section class="cara-pemesanan-section" id="how-it-works">
    <h2 class="cara-pemesanan-title">Cara Pemesanan SmartRent</h2>

    <p class="cara-pemesanan-subtitle">
        Proses sewa mobil yang mudah dan cepat dengan SmartRent. Hanya 4 langkah sederhana untuk mendapatkan mobil impian Anda.
    </p>

    <div class="cara-pemesanan-steps">
        <!-- STEP 1 -->
        <div class="cara-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Cari & Pilih Mobil</h3>
                <p>
                    Pilih mobil yang sesuai dengan kebutuhan Anda berdasarkan tipe, kapasitas penumpang, dan harga sewa.
                </p>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="cara-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Tentukan Jadwal Sewa</h3>
                <p>
                    Pilih tanggal mulai dan selesai sewa sesuai dengan rencana perjalanan Anda.
                </p>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="cara-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Isi Data & Pembayaran</h3>
                <p>
                    Lengkapi data diri dan lakukan pembayaran sesuai dengan metode yang tersedia.
                </p>
            </div>
        </div>

        <!-- STEP 4 -->
        <div class="cara-step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Ambil Kendaraan</h3>
                <p>
                    Ambil kendaraan di outlet yang ditentukan dengan menunjukkan bukti pembayaran dan identitas.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Floating Customer Service Buttons -->
<div class="floating-cs-container">
    <!-- WhatsApp Button -->
    <a href="https://wa.me/6285811224321?text=Halo%20Smart%20Rent%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20sewa%20mobil."
       target="_blank"
       class="cs-button whatsapp"
       title="Chat via WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- Phone Button -->
    <a href="tel:+6285811224321"
       class="cs-button phone"
       title="Telepon Customer Service">
        <i class="fas fa-phone"></i>
    </a>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    console.log('SmartRent page loaded');
    
    /* ========== SELECT2 INITIALIZATION - SAMA SEPERTI BERANDA ========== */
    function initializeSelect2() {
        console.log('Initializing Select2...');
        
        // Hapus select2 yang sudah ada
        if ($('#smartrent-city').data('select2')) {
            $('#smartrent-city').select2('destroy');
        }

        if ($('#smartrent-vehicle-type').data('select2')) {
            $('#smartrent-vehicle-type').select2('destroy');
        }

        // Inisialisasi ulang dengan konfigurasi minimal
        $('#smartrent-city').select2({
            placeholder: "Pilih Kota",
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: 3,
            dropdownParent: $('#smartrent-search-form')
        });

        $('#smartrent-vehicle-type').select2({
            placeholder: "Semua Tipe",
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: 3,
            dropdownParent: $('#smartrent-search-form')
        });

        console.log('✓ Select2 initialized');
    }

    // Inisialisasi dengan delay kecil
    setTimeout(initializeSelect2, 300);
    
   
 // Form submission - Redirect ke halaman booking dengan parameter
const searchForm = document.getElementById('smartrent-search-form');
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        
        // Redirect ke halaman booking dengan parameter
        window.location.href = `{{ route('smartrent.booking') }}?${params.toString()}`;
    });
}
});

// Vehicle functions
function rentVehicle(vehicleId) {
    console.log('Renting vehicle:', vehicleId);
    
    // Redirect langsung ke halaman booking dengan vehicle_id
    window.location.href = `{{ route('smartrent.booking') }}?vehicle_id=${vehicleId}`;
}

function showVehicleDetail(vehicleId) {
    console.log('Showing vehicle detail:', vehicleId);
    
    // TODO: Implement modal or detail page
    alert(`Detail kendaraan ID: ${vehicleId}\nFitur ini akan segera tersedia.`);
}

// Fungsi untuk tombol Lihat Semua Mobil
function viewAllVehicles() {
    console.log('Viewing all vehicles...');
    
    // Redirect langsung ke halaman booking tanpa filter
    window.location.href = '{{ route("smartrent.booking") }}';
}
</script>
@endpush
@endsection