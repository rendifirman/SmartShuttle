@extends('layouts.app')

@section('title', 'Booking SmartRent - SmartRent')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --light-gray: #f8f9fa;
        --border-color: #e0e0e0;
        --blue-light: #E8F0FE;
        --blue-medium: #D6E4FF;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: white;
    }

    .booking-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .booking-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .booking-title {
        font-size: 36px;
        font-weight: 900;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .booking-subtitle {
        font-size: 16px;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ===== FILTER SECTION - LAYOUT BARU ===== */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    .filter-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    /* Layout flex untuk semua elemen dalam satu baris */
    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 15px;
        flex-wrap: nowrap;
    }

    /* Kelompok tombol di paling kiri - sekarang horizontal */
    .filter-buttons-group {
        display: flex;
        gap: 10px;
        min-width: 250px; /* Lebar untuk 2 tombol sejajar */
        align-items: flex-end;
    }

    .filter-button {
        padding: 12px 15px;
        height: 46px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex: 1; /* Tombol mengisi ruang yang sama */
    }

    .reset-button {
        background: white;
        color: #666;
        border: 2px solid var(--border-color);
    }

    .reset-button:hover {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }

    .search-button:hover {
        background: #E54E1A;
    }

    /* Group untuk input filter */
    .filter-inputs-group {
        display: flex;
        flex: 1;
        gap: 15px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .filter-inputs-group::-webkit-scrollbar {
        height: 4px;
    }

    .filter-inputs-group::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    .filter-inputs-group::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 2px;
    }

    .filter-inputs-group::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .filter-group {
        margin-bottom: 0;
        min-width: 180px;
        flex: 1;
    }

    .filter-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        white-space: nowrap;
    }

    .filter-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Roboto', sans-serif;
        background: white;
        height: 46px;
        box-sizing: border-box;
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    /* Select2 disesuaikan agar tinggi sama */
    .select2-container--default .select2-selection--single {
        height: 46px !important;
        border: 2px solid var(--border-color) !important;
        border-radius: 8px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 15px !important;
        padding-right: 30px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    /* ===== MAIN LAYOUT ===== */
    .booking-layout {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 30px;
        min-height: 800px;
    }

    /* ===== VEHICLE LIST SECTION ===== */
    .vehicle-list-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        height: fit-content;
        position: sticky;
        top: 100px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .list-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .vehicle-count {
        font-size: 14px;
        color: #666;
        font-weight: normal;
    }

    .vehicle-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .vehicle-item {
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0;
    }

    .vehicle-item:hover {
        border-color: var(--secondary-color);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.15);
    }

    .vehicle-item.active {
        border-color: var(--secondary-color);
        background: #fffaf8;
        box-shadow: 0 4px 20px rgba(255, 88, 30, 0.2);
    }

    .vehicle-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .vehicle-info {
        flex: 1;
        padding: 15px;
        display: flex;
        flex-direction: column;
    }

    .vehicle-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .vehicle-type {
        color: var(--secondary-color);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
    }

    .vehicle-specs {
        display: flex;
        gap: 15px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .spec {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #666;
        font-size: 12px;
    }

    .spec i {
        color: var(--secondary-color);
        font-size: 14px;
    }

    .vehicle-price {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .price-info {
        display: flex;
        flex-direction: column;
    }

    .price-amount {
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary-color);
        line-height: 1.2;
    }

    .price-period {
        color: #666;
        font-size: 12px;
    }

    .select-btn {
        padding: 8px 20px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        white-space: nowrap;
    }

    .select-btn:hover {
        background: #E54E1A;
    }

    .no-vehicles {
        text-align: center;
        padding: 40px 20px;
        background: var(--light-gray);
        border-radius: 8px;
    }

    .no-vehicles i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
    }

    .no-vehicles h3 {
        color: #666;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .no-vehicles p {
        color: #999;
        font-size: 14px;
    }

    /* ===== VEHICLE DETAIL SECTION ===== */
    .vehicle-detail-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        display: none;
    }

    .vehicle-detail-section.active {
        display: block;
    }

    /* Gambar lebih kecil */
    .detail-header {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-image-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .detail-image {
        width: 450px;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
    }

    .detail-info {
        width: 100%;
        text-align: center;
    }

    .detail-info h2 {
        font-size: 28px;
        font-weight: 900;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .detail-type {
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 25px;
        display: block;
        font-size: 16px;
    }

    /* Spesifikasi dengan ikon hitam */
    .detail-specs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .detail-spec {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px 15px;
        background: white;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .detail-spec i {
        color: #333;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .detail-spec-text span {
        display: block;
    }

    .spec-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .detail-description {
        color: #666;
        line-height: 1.7;
        margin-bottom: 30px;
        padding: 20px;
        background: var(--light-gray);
        border-radius: 10px;
        font-size: 15px;
        text-align: center;
    }

    /* Fasilitas dengan warna biru muda */
    .facilities-section {
        margin-bottom: 30px;
    }

    .facilities-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--blue-medium);
        text-align: center;
    }

    .facilities-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }

    .facility-badge {
        background: var(--blue-light);
        padding: 10px 18px;
        border-radius: 20px;
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 500;
        border: 1px solid var(--blue-medium);
    }

    /* ===== SERVICE OPTIONS - 3x LEBIH KECIL ===== */
    .service-options {
        margin-bottom: 30px;
    }

    .service-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
        text-align: center;
    }

    .service-options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .service-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 8px 6px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
        text-align: center;
        min-height: 80px;
        background: white;
    }

    .service-option:hover {
        border-color: var(--secondary-color);
        background: #fffaf8;
        transform: translateY(-1px);
        box-shadow: 0 1px 3px rgba(255, 88, 30, 0.1);
    }

    .service-option.active {
        border-color: var(--secondary-color);
        background: #fffaf8;
        box-shadow: 0 1px 4px rgba(255, 88, 30, 0.15);
    }

    .service-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        margin-bottom: 4px;
        flex: 1;
    }

    .service-icon {
        width: 24px;
        height: 24px;
        background: var(--light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 12px;
        margin-bottom: 3px;
    }

    .service-text h4 {
        margin: 0 0 2px 0;
        color: var(--primary-color);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.1;
    }

    .service-text p {
        margin: 0;
        color: #666;
        font-size: 9px;
        line-height: 1.2;
        max-width: 140px;
    }

    .service-price {
        font-size: 12px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-top: 3px;
    }

    /* ===== PRICE SUMMARY ===== */
    .price-summary {
        background: var(--light-gray);
        border-radius: 12px;
        padding: 25px;
        margin-top: 30px;
    }

    .summary-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .summary-label {
        color: #666;
        font-size: 16px;
    }

    .summary-value {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 16px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--border-color);
    }

    .total-label {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .total-value {
        font-size: 24px;
        font-weight: 900;
        color: var(--secondary-color);
    }

    .summary-note {
        color: #666;
        font-size: 14px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
    }

    .continue-btn {
        width: 100%;
        padding: 16px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: background 0.3s;
    }

    .continue-btn:hover {
        background: #E54E1A;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .booking-layout {
            grid-template-columns: 300px 1fr;
            gap: 20px;
        }
        
        .filter-row {
            flex-wrap: wrap;
        }
        
        .filter-buttons-group {
            min-width: 100%;
            order: 1; /* Tombol di atas di tablet */
            margin-bottom: 15px;
        }
        
        .filter-inputs-group {
            min-width: 100%;
            order: 2;
            overflow-x: visible;
        }
        
        .filter-group {
            min-width: calc(50% - 8px);
        }
        
        .detail-specs-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .detail-image {
            width: 400px;
            height: 200px;
        }
        
        .service-option {
            min-height: 75px;
            padding: 7px 5px;
        }
        
        .service-icon {
            width: 22px;
            height: 22px;
            font-size: 11px;
        }
    }

    @media (max-width: 1024px) {
        .booking-layout {
            grid-template-columns: 1fr;
        }
        
        .vehicle-list-section {
            position: static;
            max-height: none;
            margin-bottom: 30px;
        }
        
        .filter-group {
            min-width: calc(33.333% - 10px);
        }
        
        .detail-image {
            width: 500px;
            height: 250px;
        }
        
        .detail-specs-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .service-options-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }
    }

    @media (max-width: 768px) {
        .booking-title {
            font-size: 28px;
        }
        
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-buttons-group {
            justify-content: center;
            gap: 10px;
        }
        
        .filter-button {
            flex: 1;
        }
        
        .filter-inputs-group {
            flex-direction: column;
            gap: 15px;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .vehicle-list-section {
            padding: 20px;
        }
        
        .vehicle-detail-section {
            padding: 20px;
        }
        
        .detail-image {
            width: 100%;
            max-width: 400px;
            height: 200px;
        }
        
        .detail-specs-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .detail-spec {
            padding: 15px 10px;
        }
        
        .detail-spec i {
            font-size: 20px;
        }
        
        .service-options-grid {
            grid-template-columns: 1fr;
            gap: 6px;
        }
        
        .service-option {
            min-height: 70px;
            padding: 6px 5px;
        }
        
        .service-icon {
            width: 20px;
            height: 20px;
            font-size: 10px;
        }
        
        .service-text h4 {
            font-size: 11px;
        }
        
        .service-text p {
            font-size: 8px;
            max-width: 100%;
        }
        
        .service-price {
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .booking-container {
            padding: 20px 15px;
        }
        
        .filter-section {
            padding: 20px;
        }
        
        .filter-buttons-group {
            flex-direction: column;
        }
        
        .detail-info h2 {
            font-size: 24px;
        }
        
        .detail-type {
            font-size: 14px;
        }
        
        .detail-specs-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-spec {
            flex-direction: row;
            text-align: left;
            padding: 12px;
        }
        
        .detail-spec i {
            margin-right: 12px;
            margin-bottom: 0;
            font-size: 18px;
        }
        
        .total-value {
            font-size: 20px;
        }
        
        .vehicle-image {
            height: 120px;
        }
        
        .detail-image {
            height: 180px;
        }
        
        .service-option {
            min-height: 65px;
            padding: 5px 4px;
        }
        
        .service-icon {
            width: 18px;
            height: 18px;
            font-size: 9px;
        }
        
        .service-text h4 {
            font-size: 10px;
        }
        
        .service-text p {
            font-size: 7px;
        }
        
        .service-price {
            font-size: 10px;
        }
    }
</style>
@endpush

@section('content')
@php
    // Data dari controller
    $filterData = $filterData ?? [
        'city' => '',
        'vehicle_type' => '',
        'rent_date' => date('Y-m-d'),
        'duration' => 1,
        'capacity' => '',
        'vehicle_id' => ''
    ];
    
    $vehicles = $vehicles ?? [];
    $selectedVehicle = $selectedVehicle ?? null;
@endphp

<div class="booking-container">
    <div class="booking-header">
        <h1 class="booking-title">Sewa Mobil SmartRent</h1>
        <p class="booking-subtitle">Pilih kendaraan yang sesuai dengan kebutuhan perjalanan Anda</p>
    </div>

    <!-- Filter Section dengan layout baru -->
    <div class="filter-section">
        <h3 class="filter-title">Filter Pencarian</h3>
        
        <form id="bookingFilterForm" action="{{ route('smartrent.booking') }}" method="GET">
            <div class="filter-row">
                <!-- Tombol di paling kiri - sekarang sejajar -->
                <div class="filter-buttons-group">
                    <button type="submit" class="filter-button search-button">
                        <i class="fas fa-search"></i> Cari Armada
                    </button>
                    <button type="button" class="filter-button reset-button" onclick="resetFilter()">
                        <i class="fas fa-redo"></i> Reset Filter
                    </button>
                </div>
                
                <!-- Semua filter input dalam satu baris -->
                <div class="filter-inputs-group">
                    <div class="filter-group">
                        <label class="filter-label">Kota</label>
                        <select name="city" class="filter-input">
                            <option value="">Semua Kota</option>
                            <option value="jakarta" {{ $filterData['city'] == 'jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="bandung" {{ $filterData['city'] == 'bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="surabaya" {{ $filterData['city'] == 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="yogyakarta" {{ $filterData['city'] == 'yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="bali" {{ $filterData['city'] == 'bali' ? 'selected' : '' }}>Bali</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Mulai Sewa</label>
                        <input type="date" name="rent_date" class="filter-input" 
                               value="{{ $filterData['rent_date'] }}" min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Durasi (Hari)</label>
                        <select name="duration" class="filter-input">
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" {{ $filterData['duration'] == $i ? 'selected' : '' }}>
                                    {{ $i }} Hari
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Kapasitas Penumpang</label>
                        <select name="capacity" class="filter-input">
                            <option value="">Semua Kapasitas</option>
                            <option value="1-4" {{ $filterData['capacity'] == '1-4' ? 'selected' : '' }}>1-4 Orang</option>
                            <option value="5-7" {{ $filterData['capacity'] == '5-7' ? 'selected' : '' }}>5-7 Orang</option>
                            <option value="8-12" {{ $filterData['capacity'] == '8-12' ? 'selected' : '' }}>8-12 Orang</option>
                            <option value="13+" {{ $filterData['capacity'] == '13+' ? 'selected' : '' }}>13+ Orang</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tipe Armada</label>
                        <select name="vehicle_type" class="filter-input">
                            <option value="">Semua Tipe</option>
                            <option value="shuttle" {{ $filterData['vehicle_type'] == 'shuttle' ? 'selected' : '' }}>Shuttle</option>
                            <option value="family" {{ $filterData['vehicle_type'] == 'family' ? 'selected' : '' }}>Family</option>
                            <option value="economy" {{ $filterData['vehicle_type'] == 'economy' ? 'selected' : '' }}>Economy</option>
                            <option value="mpv" {{ $filterData['vehicle_type'] == 'mpv' ? 'selected' : '' }}>MPV</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="vehicle_id" id="selectedVehicleId" value="{{ $filterData['vehicle_id'] }}">
        </form>
    </div>

    <!-- Main Content -->
    <div class="booking-layout">
        <!-- Vehicle List Section -->
        <div class="vehicle-list-section">
            <h3 class="list-title">
                Daftar Armada 
                <span class="vehicle-count">({{ count($vehicles) }} ditemukan)</span>
            </h3>
            
            <div class="vehicle-list" id="vehicleList">
                @foreach($vehicles as $vehicle)
                <div class="vehicle-item {{ $selectedVehicle && $selectedVehicle['id'] == $vehicle['id'] ? 'active' : '' }}" 
                     data-vehicle-id="{{ $vehicle['id'] }}">
                    <img src="{{ $vehicle['image'] }}" alt="{{ $vehicle['name'] }}" class="vehicle-image"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-vehicle.jpg') }}';">
                    <div class="vehicle-info">
                        <h3 class="vehicle-name">{{ $vehicle['name'] }}</h3>
                        <span class="vehicle-type">{{ $vehicle['type'] }}</span>
                        
                        <div class="vehicle-specs">
                            <div class="spec">
                                <i class="fas fa-chair"></i>
                                <span>{{ $vehicle['seats'] }}</span>
                            </div>
                            <div class="spec">
                                <i class="fas fa-suitcase"></i>
                                <span>{{ $vehicle['luggage'] }}</span>
                            </div>
                            <div class="spec">
                                <i class="fas fa-gas-pump"></i>
                                <span>{{ $vehicle['fuel'] }}</span>
                            </div>
                        </div>
                        
                        <div class="vehicle-price">
                            <div class="price-info">
                                <div class="price-amount">Rp {{ number_format($vehicle['price'], 0, ',', '.') }}</div>
                                <div class="price-period">{{ $vehicle['period'] }}</div>
                            </div>
                            <button class="select-btn" onclick="selectVehicle({{ $vehicle['id'] }})">
                                Pilih
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if(count($vehicles) == 0)
                <div class="no-vehicles">
                    <i class="fas fa-car"></i>
                    <h3>Tidak ada kendaraan ditemukan</h3>
                    <p>Coba ubah filter pencarian Anda</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Vehicle Detail Section -->
        <div class="vehicle-detail-section" id="vehicleDetailSection">
            <!-- Konten akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<!-- Template untuk Vehicle Detail -->
<template id="vehicleDetailTemplate">
    <!-- Layout dengan gambar di atas -->
    <div class="detail-header">
        <!-- Gambar lebih kecil dan di tengah -->
        <div class="detail-image-container">
            <img src="" alt="" class="detail-image" id="detailImage">
        </div>
        
        <div class="detail-info">
            <h2 id="detailName"></h2>
            <span class="detail-type" id="detailType"></span>
            
            <!-- Spesifikasi dengan ikon hitam -->
            <div class="detail-specs-grid">
                <div class="detail-spec">
                    <i class="fas fa-chair"></i>
                    <div class="detail-spec-text">
                        <span class="spec-label">Kursi</span>
                        <span class="spec-value" id="detailSeats"></span>
                    </div>
                </div>
                <div class="detail-spec">
                    <i class="fas fa-suitcase"></i>
                    <div class="detail-spec-text">
                        <span class="spec-label">Bagasi</span>
                        <span class="spec-value" id="detailLuggage"></span>
                    </div>
                </div>
                <div class="detail-spec">
                    <i class="fas fa-gas-pump"></i>
                    <div class="detail-spec-text">
                        <span class="spec-label">Bahan Bakar</span>
                        <span class="spec-value" id="detailFuel"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="detail-description" id="detailDescription"></div>

    <!-- Fasilitas dengan warna biru muda -->
    <div class="facilities-section">
        <h3 class="facilities-title">Fasilitas</h3>
        <div class="facilities-grid" id="facilitiesGrid">
            <!-- Fasilitas akan diisi -->
        </div>
    </div>

    <!-- Service Options dengan layout grid 2 kolom (3x lebih kecil) -->
    <div class="service-options">
        <h3 class="service-title">Pilih Layanan</h3>
        <div class="service-options-grid">
            <div class="service-option" data-service="self-drive" onclick="selectService('self-drive')">
                <div class="service-info">
                    <div class="service-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="service-text">
                        <h4>Lepas Kunci</h4>
                        <p>Anda menyetir sendiri. Hanya termasuk mobil dan asuransi dasar.</p>
                    </div>
                </div>
                <div class="service-price" id="selfDrivePrice">Rp 0</div>
            </div>
            <div class="service-option active" data-service="with-driver" onclick="selectService('with-driver')">
                <div class="service-info">
                    <div class="service-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="service-text">
                        <h4>Dengan Sopir</h4>
                        <p>Sopir profesional, BBM, tol, parkir, dan makan sopir sudah termasuk.</p>
                    </div>
                </div>
                <div class="service-price" id="withDriverPrice">Rp 0/hari</div>
            </div>
        </div>
    </div>

    <div class="price-summary">
        <h3 class="summary-title">Ringkasan Harga</h3>
        <div class="summary-row">
            <span class="summary-label">Harga Sewa Mobil</span>
            <span class="summary-value" id="summaryVehiclePrice">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Biaya Sopir</span>
            <span class="summary-value" id="summaryDriverPrice">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Durasi</span>
            <span class="summary-value" id="summaryDuration">{{ $filterData['duration'] }} Hari</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total Sewa</span>
            <span class="total-value" id="summaryTotal">Rp 0</span>
        </div>
        <p class="summary-note">
            * Harga sudah termasuk PPN 11%. Belum termasuk akomodasi sopir untuk sewa lebih dari 1 hari.
        </p>
        <button class="continue-btn" onclick="continueToPayment()">
            Lanjutkan ke Pembayaran
        </button>
    </div>
</template>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Data kendaraan dari PHP
const vehicles = @json($vehicles);
let selectedVehicleId = {{ $selectedVehicle ? $selectedVehicle['id'] : 'null' }};
let selectedService = 'with-driver';
let duration = {{ $filterData['duration'] }};

$(document).ready(function() {
    // Initialize select2
    $('select').select2({
        minimumResultsForSearch: 3
    });

    // Jika ada vehicle yang dipilih dari halaman sebelumnya, tampilkan detail
    if (selectedVehicleId) {
        showVehicleDetail(selectedVehicleId);
    }
});

function resetFilter() {
    // Reset form ke nilai default
    $('#bookingFilterForm')[0].reset();
    $('#selectedVehicleId').val('');
    
    // Reset select2
    $('select').val(null).trigger('change');
    
    // Reset rent date ke hari ini
    $('input[name="rent_date"]').val('{{ date("Y-m-d") }}');
}

function selectVehicle(vehicleId) {
    selectedVehicleId = vehicleId;
    
    // Update URL dengan vehicle_id
    const url = new URL(window.location.href);
    url.searchParams.set('vehicle_id', vehicleId);
    window.history.replaceState({}, '', url);
    
    // Update hidden input
    $('#selectedVehicleId').val(vehicleId);
    
    // Update UI
    $('.vehicle-item').removeClass('active');
    $(`.vehicle-item[data-vehicle-id="${vehicleId}"]`).addClass('active');
    
    // Tampilkan detail
    showVehicleDetail(vehicleId);
    
    // Scroll ke detail section pada mobile
    if (window.innerWidth <= 1024) {
        $('#vehicleDetailSection')[0].scrollIntoView({ behavior: 'smooth' });
    }
}

function showVehicleDetail(vehicleId) {
    const vehicle = vehicles.find(v => v.id == vehicleId);
    if (!vehicle) return;
    
    // Tampilkan detail section
    $('#vehicleDetailSection').show().addClass('active');
    
    // Isi template
    const template = document.getElementById('vehicleDetailTemplate');
    $('#vehicleDetailSection').html(template.innerHTML);
    
    // Isi data
    $('#detailImage').attr('src', vehicle.image).on('error', function() {
        this.src = '{{ asset('images/default-vehicle.jpg') }}';
    });
    $('#detailName').text(vehicle.name);
    $('#detailType').text(vehicle.type);
    $('#detailSeats').text(vehicle.seats);
    $('#detailLuggage').text(vehicle.luggage);
    $('#detailFuel').text(vehicle.fuel);
    $('#detailDescription').text(vehicle.description || 'Deskripsi tidak tersedia');
    
    // Isi fasilitas
    const facilitiesGrid = $('#facilitiesGrid');
    facilitiesGrid.empty();
    if (vehicle.facilities && vehicle.facilities.length > 0) {
        vehicle.facilities.forEach(facility => {
            facilitiesGrid.append(`<div class="facility-badge">${facility}</div>`);
        });
    }
    
    // Update harga
    updatePrices(vehicle);
}

function updatePrices(vehicle) {
    const vehiclePrice = parseInt(vehicle.price);
    const driverPrice = vehicle.driver_included ? parseInt(vehicle.driver_included_price) : 0;
    
    $('#selfDrivePrice').text('Rp 0');
    $('#withDriverPrice').text(`Rp ${formatNumber(driverPrice)}/hari`);
    $('#summaryVehiclePrice').text(`Rp ${formatNumber(vehiclePrice)}/hari`);
    $('#summaryDriverPrice').text(`Rp ${formatNumber(driverPrice)}/hari`);
    $('#summaryDuration').text(`${duration} Hari`);
    
    // Hitung total
    calculateTotal();
}

function selectService(service) {
    selectedService = service;
    $('.service-option').removeClass('active');
    $(`.service-option[data-service="${service}"]`).addClass('active');
    calculateTotal();
}

function calculateTotal() {
    const vehicle = vehicles.find(v => v.id == selectedVehicleId);
    if (!vehicle) return;
    
    const vehiclePrice = parseInt(vehicle.price);
    const driverPrice = selectedService === 'with-driver' && vehicle.driver_included ? 
                        parseInt(vehicle.driver_included_price) : 0;
    
    const totalPerDay = vehiclePrice + driverPrice;
    const total = totalPerDay * duration;
    
    $('#summaryTotal').text(`Rp ${formatNumber(total)}`);
}

function continueToPayment() {
    if (!selectedVehicleId) {
        alert('Silakan pilih kendaraan terlebih dahulu');
        return;
    }
    
    // Collect data
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("smartrent.checkout") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const vehicleIdInput = document.createElement('input');
    vehicleIdInput.type = 'hidden';
    vehicleIdInput.name = 'vehicle_id';
    vehicleIdInput.value = selectedVehicleId;
    form.appendChild(vehicleIdInput);
    
    const serviceInput = document.createElement('input');
    serviceInput.type = 'hidden';
    serviceInput.name = 'service';
    serviceInput.value = selectedService;
    form.appendChild(serviceInput);
    
    const durationInput = document.createElement('input');
    durationInput.type = 'hidden';
    durationInput.name = 'duration';
    durationInput.value = duration;
    form.appendChild(durationInput);
    
    const rentDateInput = document.createElement('input');
    rentDateInput.type = 'hidden';
    rentDateInput.name = 'rent_date';
    rentDateInput.value = '{{ $filterData["rent_date"] }}';
    form.appendChild(rentDateInput);
    
    // Simpan form ke dokumen dan submit
    document.body.appendChild(form);
    form.submit();
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
@endsection 