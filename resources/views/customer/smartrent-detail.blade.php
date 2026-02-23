@extends('layouts.app')

@section('title', $vehicle['name'] . ' - Detail SmartRent')

@push('styles')
<style>
    /* Reset & Base */
    :root {
        --primary-color: #0f2942;
        --secondary-color: #FF581E;
        --accent-color: #FFD700;
        --light-bg: #f8f9fa;
        --border-color: #eef2f7;
        --text-light: #666;
        --text-dark: #333;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
        line-height: 1.6;
    }

    /* Container utama */
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    /* Breadcrumb minimalis */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
        font-size: 14px;
    }

    .breadcrumb-link {
        color: var(--text-light);
        text-decoration: none;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .breadcrumb-link:hover {
        color: var(--secondary-color);
    }

    .breadcrumb-separator {
        color: #999;
        font-size: 12px;
    }

    .breadcrumb-current {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Layout utama */
    .detail-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    /* Left Column - Vehicle Info */
    .vehicle-info-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 41, 66, 0.08);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    /* Vehicle Header */
    .vehicle-header {
        padding: 30px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #1a3a5e 100%);
        color: white;
        position: relative;
    }

    .availability-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        color: var(--primary-color);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(15, 41, 66, 0.1);
    }

    .vehicle-title {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .vehicle-subtitle {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 25px;
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .vehicle-subtitle .type-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
    }

    .vehicle-price-container {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .price-label {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-amount {
        font-size: 36px;
        font-weight: 900;
        color: var(--accent-color);
        line-height: 1;
    }

    .price-period {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
        margin-left: 5px;
    }

    /* Image Gallery */
    .gallery-section {
        padding: 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .main-image-container {
        width: 100%;
        height: 320px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 15px;
        background: var(--light-bg);
        border: 1px solid var(--border-color);
    }

    .main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .main-image:hover {
        transform: scale(1.02);
    }

    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .thumbnail {
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        background: var(--light-bg);
        border: 1px solid var(--border-color);
    }

    .thumbnail:hover {
        border-color: var(--secondary-color);
    }

    .thumbnail.active {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.1);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ===== SPESIFIKASI - DIPERKECIL DAN DIPADATKAN ===== */
    .specs-section {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-title i {
        color: var(--secondary-color);
        font-size: 18px;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--light-bg);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .spec-icon {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid var(--border-color);
    }

    .spec-icon i {
        color: var(--secondary-color);
        font-size: 16px;
    }

    .spec-content {
        flex: 1;
        min-width: 0;
    }

    .spec-label {
        display: block;
        font-size: 10px;
        color: var(--text-light);
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    .spec-value {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-color);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Description */
    .description-section {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .description-content {
        font-size: 14px;
        line-height: 1.6;
        color: #444;
    }

    /* ===== FASILITAS - GRID 3 KOLOM ===== */
    .features-section {
        padding: 20px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        background: var(--light-bg);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        font-size: 12px;
        color: var(--primary-color);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .feature-item i {
        color: var(--secondary-color);
        font-size: 11px;
        width: 14px;
        text-align: center;
        flex-shrink: 0;
    }

    .feature-item span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Right Column - Booking Form */
    .booking-section {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .booking-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 41, 66, 0.08);
        overflow: hidden;
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }

    .booking-header {
        background: var(--primary-color);
        color: white;
        padding: 20px;
        text-align: center;
    }

    .booking-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .booking-form {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s ease;
        background: white;
        color: var(--text-dark);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    /* Price Summary */
    .price-summary {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 20px;
        margin-top: 10px;
        border: 1px solid var(--border-color);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }

    .summary-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .summary-label {
        color: var(--text-light);
    }

    .summary-value {
        font-weight: 600;
        color: var(--primary-color);
    }

    .summary-total {
        border-top: 2px solid var(--border-color);
        padding-top: 15px;
        margin-top: 15px;
    }

    .total-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .total-value {
        font-size: 24px;
        font-weight: 900;
        color: var(--secondary-color);
    }

    /* Action Buttons */
    .action-buttons {
        margin-top: 25px;
    }

    .btn-book {
        width: 100%;
        padding: 16px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .btn-book:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 88, 30, 0.2);
    }

    .btn-secondary {
        width: 100%;
        padding: 14px;
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        border-color: var(--primary-color);
        background: var(--light-bg);
    }

    /* Additional Info */
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(15, 41, 66, 0.05);
        margin-top: 20px;
        border: 1px solid var(--border-color);
    }

    .info-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }

    .info-content {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.6;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 8px;
    }

    .info-item i {
        color: var(--secondary-color);
        font-size: 12px;
        margin-top: 2px;
    }

    /* Login Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .login-modal {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 400px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), #1a3a5e);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .modal-icon i {
        font-size: 32px;
        color: white;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .modal-message {
        font-size: 16px;
        color: var(--text-light);
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .modal-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .btn-modal-login {
        padding: 14px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-modal-login:hover {
        background: #E54E1A;
        transform: translateY(-2px);
    }

    .btn-modal-cancel {
        padding: 14px;
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-modal-cancel:hover {
        border-color: var(--primary-color);
        background: var(--light-bg);
    }

    /* ===== RESPONSIVE MOBILE - SPESIFIKASI & FASILITAS SANGAT RAPIH ===== */
    @media (max-width: 992px) {
        .detail-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .booking-section {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .detail-container {
            padding: 15px;
        }

        /* Header */
        .vehicle-header {
            padding: 20px 16px;
        }

        .vehicle-title {
            font-size: 22px;
            padding-right: 85px;
        }

        .availability-badge {
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            font-size: 11px;
        }

        .vehicle-subtitle {
            font-size: 14px;
            margin-bottom: 16px;
        }

        .price-amount {
            font-size: 24px;
        }

        /* Gallery */
        .gallery-section {
            padding: 16px;
        }

        .main-image-container {
            height: 200px;
        }

        .thumbnail-grid {
            gap: 6px;
        }

        .thumbnail {
            height: 55px;
        }

        /* ===== SPESIFIKASI MOBILE - LEBIH PADAT ===== */
        .specs-section {
            padding: 16px;
        }

        .section-title {
            font-size: 16px;
            margin-bottom: 12px;
            padding-bottom: 6px;
        }

        .section-title i {
            font-size: 16px;
        }

        .specs-grid {
            gap: 8px;
        }

        .spec-item {
            padding: 8px;
            gap: 8px;
        }

        .spec-icon {
            width: 32px;
            height: 32px;
        }

        .spec-icon i {
            font-size: 14px;
        }

        .spec-label {
            font-size: 9px;
            letter-spacing: 0.2px;
        }

        .spec-value {
            font-size: 12px;
        }

        /* Description */
        .description-section {
            padding: 16px;
        }

        .description-content {
            font-size: 13px;
            line-height: 1.5;
        }

        /* ===== FASILITAS MOBILE - GRID 3 KOLOM ===== */
        .features-section {
            padding: 16px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .feature-item {
            padding: 6px 8px;
            border-radius: 16px;
            font-size: 11px;
            gap: 4px;
            justify-content: flex-start;
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .feature-item i {
            font-size: 10px;
            width: 12px;
        }

        .feature-item span {
            font-size: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Booking Form */
        .booking-form {
            padding: 16px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .price-summary {
            padding: 14px;
        }

        .total-value {
            font-size: 20px;
        }

        .btn-book {
            padding: 14px;
            font-size: 15px;
        }
    }

    @media (max-width: 576px) {
        .vehicle-title {
            font-size: 20px;
            padding-right: 80px;
        }

        .availability-badge {
            padding: 3px 8px;
            font-size: 10px;
        }

        .main-image-container {
            height: 180px;
        }

        .thumbnail {
            height: 50px;
        }

        /* Spesifikasi makin padat */
        .specs-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .spec-item {
            padding: 6px;
        }

        .spec-icon {
            width: 28px;
            height: 28px;
        }

        .spec-icon i {
            font-size: 12px;
        }

        .spec-label {
            font-size: 8px;
        }

        .spec-value {
            font-size: 11px;
        }

        /* Fasilitas tetap 3 kolom */
        .features-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        .feature-item {
            padding: 5px 6px;
            font-size: 10px;
        }

        .feature-item i {
            font-size: 9px;
            width: 10px;
        }

        .feature-item span {
            font-size: 9px;
        }
    }

    @media (max-width: 400px) {
        .vehicle-title {
            font-size: 18px;
            padding-right: 75px;
        }

        .main-image-container {
            height: 160px;
        }

        .thumbnail {
            height: 45px;
        }

        /* Spesifikasi ultra kompak */
        .spec-item {
            padding: 5px;
            gap: 5px;
        }

        .spec-icon {
            width: 26px;
            height: 26px;
        }

        .spec-icon i {
            font-size: 11px;
        }

        .spec-label {
            font-size: 7px;
            letter-spacing: 0.1px;
        }

        .spec-value {
            font-size: 10px;
        }

        /* Fasilitas 3 kolom dengan teks sangat kecil */
        .features-grid {
            gap: 4px;
        }

        .feature-item {
            padding: 4px 5px;
            border-radius: 12px;
        }

        .feature-item i {
            font-size: 8px;
            width: 8px;
        }

        .feature-item span {
            font-size: 8px;
        }
    }

    /* Landscape mode */
    @media (max-width: 896px) and (orientation: landscape) {
        .detail-layout {
            grid-template-columns: 1fr;
        }

        .main-image-container {
            height: 200px;
        }

        .specs-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .features-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
@php
    use App\Models\MProfilePerusahaan;
    $profile = MProfilePerusahaan::first();
    
    // Tetapkan harga sopir tetap Rp 150.000
    $driverPricePerDay = 150000;
@endphp

<div class="detail-container">
    <!-- Breadcrumb minimalis -->
    <nav class="breadcrumb-nav">
        <a href="{{ route('customer.beranda') }}" class="breadcrumb-link">
            <i class="fas fa-home"></i> Beranda
        </a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('smartrent.index') }}" class="breadcrumb-link">
            SmartRent
        </a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">{{ Str::limit($vehicle['name'], 30) }}</span>
    </nav>

    <div class="detail-layout">
        <!-- Left Column - Vehicle Info -->
        <div class="vehicle-info-section">
            <!-- Vehicle Header -->
            <div class="vehicle-header">
                <div class="availability-badge">
                    @if($vehicle['available'])
                        <i class="fas fa-check-circle"></i> Tersedia
                    @else
                        <i class="fas fa-clock"></i> Menunggu
                    @endif
                </div>
                
                <h1 class="vehicle-title">{{ $vehicle['name'] }}</h1>
                <div class="vehicle-subtitle">
                    {{ $vehicle['category'] }}
                    <span class="type-badge">{{ $vehicle['type'] }}</span>
                </div>
                
                <div class="vehicle-price-container">
                    <span class="price-label">Mulai dari</span>
                    <span class="price-amount">Rp {{ $vehicle['price_formatted'] }}</span>
                    <span class="price-period">{{ $vehicle['period'] }}</span>
                </div>
            </div>

            <!-- Image Gallery -->
            <section class="gallery-section">
                <div class="main-image-container">
                    <img src="{{ $vehicle['image'] ?? asset('images/shuttle1.jpeg') }}" alt="{{ $vehicle['name'] }}" class="main-image" id="mainImage">
                </div>
                
                @if(count($vehicle['images'] ?? []) > 1)
                <div class="thumbnail-grid" id="thumbnailGrid">
                    @foreach($vehicle['images'] as $index => $image)
                    <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ $image }}', this)">
                        <img src="{{ $image }}" alt="{{ $vehicle['name'] }} - {{ $index + 1 }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-vehicle.jpg') }}';">
                    </div>
                    @endforeach
                </div>
                @endif
            </section>

            <!-- Specifications - VERSI PADAT -->
            <section class="specs-section">
                <h3 class="section-title">
                    <i class="fas fa-list-alt"></i> Spesifikasi
                </h3>
                
                <div class="specs-grid">
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Kursi</span>
                            <span class="spec-value">{{ $vehicle['seats'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-suitcase"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Bagasi</span>
                            <span class="spec-value">{{ $vehicle['luggage'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Bahan Bakar</span>
                            <span class="spec-value">{{ $vehicle['fuel'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Transmisi</span>
                            <span class="spec-value">{{ $vehicle['transmission'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">AC</span>
                            <span class="spec-value">{{ $vehicle['ac'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Tahun</span>
                            <span class="spec-value">{{ $vehicle['year'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Asuransi</span>
                            <span class="spec-value">{{ $vehicle['insurance'] }}</span>
                        </div>
                    </div>
                    
                    <div class="spec-item">
                        <div class="spec-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="spec-content">
                            <span class="spec-label">Sopir</span>
                            <span class="spec-value">{{ $vehicle['driver'] }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Description -->
            <section class="description-section">
                <h3 class="section-title">
                    <i class="fas fa-file-alt"></i> Deskripsi
                </h3>
                <div class="description-content">
                    {!! nl2br(e($vehicle['description'])) !!}
                </div>
            </section>

            <!-- Features - VERSI GRID 3 KOLOM -->
            <section class="features-section">
                <h3 class="section-title">
                    <i class="fas fa-star"></i> Fasilitas
                </h3>
                <div class="features-grid">
                    @foreach($vehicle['features'] as $feature)
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Right Column - Booking Form -->
        <div class="booking-section">
            <div class="booking-card">
                <div class="booking-header">
                    <h3 class="booking-title">Form Pemesanan</h3>
                </div>
                
                <form class="booking-form" id="bookingForm" action="{{ route('smartrent.checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle['id'] }}">
                    <input type="hidden" name="vehicle_name" value="{{ $vehicle['name'] }}">
                    <input type="hidden" name="vehicle_price" value="{{ $vehicle['price'] }}">
                    <input type="hidden" name="vehicle_image" value="{{ $vehicle['image'] }}">
                    <input type="hidden" name="vehicle_type" value="{{ $vehicle['type'] }}">
                    <input type="hidden" name="driver_price" value="{{ $driverPricePerDay }}">
                    <input type="hidden" name="pickup_location" value="Bandung">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="rent_date" class="form-control" 
                                   min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Durasi Sewa *</label>
                            <select name="duration" class="form-control" id="durationSelect" required>
                                @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
                                    {{ $i }} Hari
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Opsi Layanan *</label>
                        <select name="service_type" class="form-control" id="driverSelect" required>
                            <option value="with_driver">Dengan Sopir (+Rp {{ number_format($driverPricePerDay, 0, ',', '.') }}/hari)</option>
                            <option value="self_drive">Tanpa Sopir (Lepas Kunci)</option>
                        </select>
                    </div>
                    
                    <!-- Price Summary -->
                    <div class="price-summary">
                        <div class="summary-item">
                            <span class="summary-label">Harga Sewa/Hari</span>
                            <span class="summary-value" id="vehiclePrice">Rp {{ number_format($vehicle['price'], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">Biaya Sopir</span>
                            <span class="summary-value" id="driverPrice">Rp 0</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">Durasi</span>
                            <span class="summary-value" id="durationText">1 Hari</span>
                        </div>
                        
                        <div class="summary-item summary-total">
                            <span class="total-label">Total Biaya</span>
                            <span class="total-value" id="totalPrice">Rp {{ number_format($vehicle['price'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        @auth
                        <button type="submit" class="btn-book" id="bookButton">
                            <i class="fas fa-calendar-check"></i>
                            Lanjutkan ke Checkout
                        </button>
                        @else
                        <button type="button" class="btn-book" onclick="showLoginModal()">
                            <i class="fas fa-calendar-check"></i>
                            Lanjutkan ke Checkout
                        </button>
                        @endauth
                        
                        <a href="{{ route('smartrent.booking') }}" class="btn-secondary">
                            <i class="fas fa-exchange-alt"></i>
                            Bandingkan Lainnya
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="info-card">
                <h4 class="info-title">
                    <i class="fas fa-info-circle"></i> Informasi Checkout
                </h4>
                <div class="info-content">
                    <div class="info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Anda akan diarahkan ke halaman checkout</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Di halaman checkout, Anda bisa memasukkan data pribadi</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Setelah checkout, lanjutkan ke pembayaran</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Konfirmasi booking akan dikirim ke email Anda</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal-overlay" id="loginModal">
    <div class="login-modal">
        <div class="modal-icon">
            <i class="fas fa-lock"></i>
        </div>
        
        <h3 class="modal-title">Login Diperlukan</h3>
        
        <p class="modal-message">
            Untuk melanjutkan pemesanan, Anda perlu login terlebih dahulu. 
            Masuk dengan akun Anda untuk mengakses fitur pemesanan.
        </p>
        
        <div class="modal-buttons">
            <a href="{{ route('login') }}" class="btn-modal-login">
                <i class="fas fa-sign-in-alt"></i> Login Sekarang
            </a>
            
            <button class="btn-modal-cancel" onclick="hideLoginModal()">
                Batalkan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.changeImage = function(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.classList.add('active');
    };
    
    const basePrice = {{ $vehicle['price'] }};
    const driverPricePerDay = {{ $driverPricePerDay }}; // Rp 150.000
    
    const durationSelect = document.getElementById('durationSelect');
    const driverSelect = document.getElementById('driverSelect');
    const vehiclePriceEl = document.getElementById('vehiclePrice');
    const driverPriceEl = document.getElementById('driverPrice');
    const durationTextEl = document.getElementById('durationText');
    const totalPriceEl = document.getElementById('totalPrice');
    const bookButton = document.getElementById('bookButton');
    
    function formatIDR(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }
    
    function calculateTotal() {
        const duration = parseInt(durationSelect.value);
        const driverOption = driverSelect.value;
        const driverCost = driverOption === 'with_driver' ? driverPricePerDay : 0;
        
        const vehicleCost = basePrice * duration;
        const totalDriverCost = driverCost * duration;
        const totalCost = vehicleCost + totalDriverCost;
        
        vehiclePriceEl.textContent = formatIDR(vehicleCost);
        driverPriceEl.textContent = driverCost > 0 ? formatIDR(totalDriverCost) : 'Rp 0';
        durationTextEl.textContent = duration + ' Hari';
        totalPriceEl.textContent = formatIDR(totalCost);
        
        if (driverOption === 'with_driver') {
            const driverOptionText = document.querySelector('option[value="with_driver"]');
            if (driverOptionText) {
                driverOptionText.textContent = `Dengan Sopir (+Rp ${driverPricePerDay.toLocaleString('id-ID')}/hari)`;
            }
        }
    }
    
    durationSelect.addEventListener('change', calculateTotal);
    driverSelect.addEventListener('change', calculateTotal);
    
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            if (!{{ $vehicle['available'] ? 'true' : 'false' }}) {
                e.preventDefault();
                alert('Maaf, kendaraan ini sedang tidak tersedia untuk disewa.');
                return;
            }
            
            const rentDate = document.querySelector('input[name="rent_date"]').value;
            const today = new Date().toISOString().split('T')[0];
            if (rentDate < today) {
                e.preventDefault();
                alert('Tanggal mulai tidak boleh kurang dari hari ini.');
                return;
            }
            
            const duration = parseInt(durationSelect.value);
            if (duration < 1 || duration > 30) {
                e.preventDefault();
                alert('Durasi sewa harus antara 1-30 hari.');
                return;
            }
            
            if (bookButton) {
                const originalText = bookButton.innerHTML;
                bookButton.innerHTML = '<span class="loading"></span> Memproses...';
                bookButton.disabled = true;
            }
        });
    }
    
    calculateTotal();
    
    window.showLoginModal = function() {
        document.getElementById('loginModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    window.hideLoginModal = function() {
        document.getElementById('loginModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    };
    
    document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideLoginModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideLoginModal();
        }
    });
    
    @if(session('error') && str_contains(session('error'), 'login'))
        showLoginModal();
    @endif
});
</script>
@endpush
