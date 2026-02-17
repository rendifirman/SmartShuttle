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

    /* ===== FILTER SECTION ===== */
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

    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 15px;
        flex-wrap: nowrap;
    }

    .filter-buttons-group {
        display: flex;
        gap: 10px;
        min-width: 250px;
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
        flex: 1;
    }

    .reset-button {
        background: white;
        color: #666;
        border: 2px solid var(--border-color);
    }

    .filter-inputs-group {
        display: flex;
        flex: 1;
        gap: 15px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 5px;
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
        height: 46px;
        box-sizing: border-box;
    }

    /* ===== VEHICLE LIST ===== */
    .booking-layout {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 30px;
        min-height: 800px;
    }

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
    }

    .vehicle-item.active {
        border-color: var(--secondary-color);
        background: #fffaf8;
    }

    .vehicle-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .vehicle-info {
        padding: 15px;
    }

    .vehicle-price {
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-amount {
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary-color);
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
    }

    /* ===== VEHICLE DETAIL - DIRAPIHKAN UNTUK MOBILE ===== */
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
        text-align: center;
        margin-bottom: 25px;
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
        font-size: 16px;
        display: inline-block;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--blue-medium);
    }

    /* Spesifikasi Grid - DIPERBAIKI UNTUK MOBILE */
    .detail-specs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin: 25px 0;
    }

    .detail-spec {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 15px 10px;
        background: white;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .detail-spec i {
        color: #333;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .spec-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Deskripsi - DIPERBAIKI */
    .detail-description {
        color: #555;
        line-height: 1.6;
        margin-bottom: 25px;
        padding: 20px;
        background: var(--light-gray);
        border-radius: 10px;
        font-size: 15px;
        text-align: left;
        border-left: 4px solid var(--secondary-color);
    }

    /* Fasilitas - DIPERBAIKI */
    .facilities-section {
        margin-bottom: 30px;
    }

    .facilities-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .facilities-title i {
        color: var(--secondary-color);
        font-size: 18px;
    }

    .facilities-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .facility-badge {
        background: var(--blue-light);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 500;
        border: 1px solid var(--blue-medium);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .facility-badge i {
        color: var(--secondary-color);
        font-size: 12px;
    }

    /* Service Options - DIPERBAIKI UNTUK MOBILE */
    .service-options {
        margin-bottom: 30px;
    }

    .service-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .service-title i {
        color: var(--secondary-color);
    }

    .service-options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .service-option {
        display: flex;
        flex-direction: column;
        padding: 16px 14px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        position: relative;
    }

    .service-option:hover,
    .service-option.active {
        border-color: var(--secondary-color);
        background: #fffaf8;
    }

    .service-option.active {
        box-shadow: 0 2px 8px rgba(255, 88, 30, 0.1);
    }

    .service-option.active::after {
        content: "✓";
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--secondary-color);
        font-weight: 900;
        font-size: 16px;
    }

    .service-info {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }

    .service-icon {
        width: 40px;
        height: 40px;
        background: var(--blue-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 18px;
        flex-shrink: 0;
    }

    .service-text {
        flex: 1;
    }

    .service-text h4 {
        margin: 0 0 4px 0;
        color: var(--primary-color);
        font-size: 15px;
        font-weight: 700;
    }

    .service-text p {
        margin: 0;
        color: #666;
        font-size: 12px;
        line-height: 1.4;
    }

    .service-price {
        font-size: 16px;
        font-weight: 700;
        color: var(--secondary-color);
        text-align: right;
        margin-top: 5px;
        padding-top: 10px;
        border-top: 1px dashed var(--border-color);
    }

    /* Price Summary - DIPERBAIKI */
    .price-summary {
        background: linear-gradient(to bottom, var(--light-gray), #ffffff);
        border-radius: 16px;
        padding: 25px;
        margin-top: 30px;
        border: 1px solid var(--border-color);
    }

    .summary-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .summary-title i {
        color: var(--secondary-color);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }

    .summary-label {
        color: #666;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .summary-label i {
        color: var(--secondary-color);
        font-size: 14px;
        width: 18px;
    }

    .summary-value {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 15px;
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
        font-size: 13px;
        margin-top: 15px;
        padding: 12px;
        background: #fff3e6;
        border-radius: 8px;
        border-left: 4px solid var(--secondary-color);
    }

    .continue-btn {
        width: 100%;
        padding: 16px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .continue-btn i {
        font-size: 20px;
    }

    .continue-btn:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    /* ===== RESPONSIVE MOBILE - DETAIL DIUTAMAKAN ===== */
    @media (max-width: 1024px) {
        .booking-layout {
            grid-template-columns: 1fr;
            gap: 20px;
            min-height: auto;
        }

        .vehicle-list-section {
            position: static;
            max-height: none;
        }
    }

    @media (max-width: 768px) {
        .booking-container {
            padding: 20px 15px;
        }

        /* Vehicle Detail Mobile - SANGAT RAPIH */
        .vehicle-detail-section {
            padding: 20px 15px;
        }

        .detail-image {
            width: 100%;
            height: 200px;
        }

        .detail-info h2 {
            font-size: 24px;
        }

        .detail-type {
            font-size: 14px;
            padding-bottom: 12px;
        }

        .detail-specs-grid {
            gap: 10px;
            margin: 20px 0;
        }

        .detail-spec {
            padding: 12px 8px;
        }

        .detail-spec i {
            font-size: 22px;
            margin-bottom: 8px;
        }

        .spec-value {
            font-size: 15px;
        }

        .detail-description {
            padding: 16px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .facilities-grid {
            gap: 8px;
        }

        .facility-badge {
            padding: 6px 14px;
            font-size: 13px;
        }

        .service-options-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .service-option {
            padding: 16px;
        }

        .service-info {
            margin-bottom: 8px;
        }

        .service-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .service-text h4 {
            font-size: 15px;
        }

        .service-text p {
            font-size: 12px;
        }

        .service-price {
            font-size: 15px;
            padding-top: 8px;
        }

        .price-summary {
            padding: 20px;
        }

        .summary-title {
            font-size: 18px;
        }

        .summary-row {
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .summary-label,
        .summary-value {
            font-size: 14px;
        }

        .total-label {
            font-size: 18px;
        }

        .total-value {
            font-size: 22px;
        }

        .summary-note {
            font-size: 12px;
            padding: 10px;
        }

        .continue-btn {
            padding: 14px;
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .detail-image {
            height: 180px;
        }

        .detail-info h2 {
            font-size: 22px;
        }

        .detail-specs-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .detail-spec {
            flex-direction: row;
            text-align: left;
            padding: 12px 16px;
            align-items: center;
        }

        .detail-spec i {
            margin-bottom: 0;
            margin-right: 15px;
            font-size: 20px;
            width: 24px;
        }

        .detail-spec-text {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex: 1;
        }

        .spec-label {
            margin-bottom: 0;
            font-size: 13px;
        }

        .service-option {
            padding: 14px;
        }

        .service-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .total-value {
            font-size: 20px;
        }
    }

    /* Landscape mode */
    @media (max-width: 896px) and (orientation: landscape) {
        .detail-specs-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .service-options-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
@php
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

    <!-- Filter Section -->
    <div class="filter-section">
        <h3 class="filter-title">Filter Pencarian</h3>
        
        <form id="bookingFilterForm" action="{{ route('smartrent.booking') }}" method="GET">
            <div class="filter-row">
                <div class="filter-buttons-group">
                    <button type="submit" class="filter-button search-button">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button type="button" class="filter-button reset-button" onclick="resetFilter()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
                
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
                        <label class="filter-label">Tanggal Sewa</label>
                        <input type="date" name="rent_date" class="filter-input" 
                               value="{{ $filterData['rent_date'] }}" min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Durasi</label>
                        <select name="duration" class="filter-input">
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" {{ $filterData['duration'] == $i ? 'selected' : '' }}>
                                    {{ $i }} Hari
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Kapasitas</label>
                        <select name="capacity" class="filter-input">
                            <option value="">Semua</option>
                            <option value="1-4" {{ $filterData['capacity'] == '1-4' ? 'selected' : '' }}>1-4 Orang</option>
                            <option value="5-7" {{ $filterData['capacity'] == '5-7' ? 'selected' : '' }}>5-7 Orang</option>
                            <option value="8-12" {{ $filterData['capacity'] == '8-12' ? 'selected' : '' }}>8-12 Orang</option>
                            <option value="13+" {{ $filterData['capacity'] == '13+' ? 'selected' : '' }}>13+ Orang</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tipe</label>
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
        <div class="vehicle-detail-section" id="vehicleDetailSection"></div>
    </div>
</div>

<!-- Template untuk Vehicle Detail - DIPERBAIKI DENGAN ICON -->
<template id="vehicleDetailTemplate">
    <div class="detail-header">
        <div class="detail-image-container">
            <img src="" alt="" class="detail-image" id="detailImage">
        </div>
        
        <div class="detail-info">
            <h2 id="detailName"></h2>
            <span class="detail-type" id="detailType"></span>
            
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

    <div class="detail-description" id="detailDescription"></div>

    <div class="facilities-section">
        <h3 class="facilities-title">
            <i class="fas fa-clipboard-check"></i> Fasilitas
        </h3>
        <div class="facilities-grid" id="facilitiesGrid"></div>
    </div>

    <div class="service-options">
        <h3 class="service-title">
            <i class="fas fa-hand-holding-heart"></i> Pilih Layanan
        </h3>
        <div class="service-options-grid">
            <div class="service-option" data-service="self-drive" onclick="selectService('self-drive')">
                <div class="service-info">
                    <div class="service-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="service-text">
                        <h4>Lepas Kunci</h4>
                        <p>Mobil + asuransi dasar</p>
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
                        <p>BBM, tol, parkir, makan sopir</p>
                    </div>
                </div>
                <div class="service-price" id="withDriverPrice">Rp 0/hari</div>
            </div>
        </div>
    </div>

    <div class="price-summary">
        <h3 class="summary-title">
            <i class="fas fa-file-invoice-dollar"></i> Ringkasan Harga
        </h3>
        <div class="summary-row">
            <span class="summary-label">
                <i class="fas fa-car"></i> Harga Sewa Mobil
            </span>
            <span class="summary-value" id="summaryVehiclePrice">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">
                <i class="fas fa-user-tie"></i> Biaya Sopir
            </span>
            <span class="summary-value" id="summaryDriverPrice">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">
                <i class="fas fa-calendar-alt"></i> Durasi
            </span>
            <span class="summary-value" id="summaryDuration">{{ $filterData['duration'] }} Hari</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total Sewa</span>
            <span class="total-value" id="summaryTotal">Rp 0</span>
        </div>
        <p class="summary-note">
            <i class="fas fa-info-circle"></i> Harga sudah termasuk PPN 11%. Belum termasuk akomodasi sopir untuk sewa > 1 hari.
        </p>
        <button class="continue-btn" onclick="continueToPayment()">
            <i class="fas fa-arrow-right"></i> Lanjutkan ke Pembayaran
        </button>
    </div>
</template>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const vehicles = @json($vehicles);
let selectedVehicleId = {{ $selectedVehicle ? $selectedVehicle['id'] : 'null' }};
let selectedService = 'with-driver';
let duration = {{ $filterData['duration'] }};

$(document).ready(function() {
    $('select').select2({
        minimumResultsForSearch: 3
    });

    if (selectedVehicleId) {
        showVehicleDetail(selectedVehicleId);
    }
});

function resetFilter() {
    $('#bookingFilterForm')[0].reset();
    $('#selectedVehicleId').val('');
    $('select').val(null).trigger('change');
    $('input[name="rent_date"]').val('{{ date("Y-m-d") }}');
}

function selectVehicle(vehicleId) {
    selectedVehicleId = vehicleId;
    
    const url = new URL(window.location.href);
    url.searchParams.set('vehicle_id', vehicleId);
    window.history.replaceState({}, '', url);
    
    $('#selectedVehicleId').val(vehicleId);
    $('.vehicle-item').removeClass('active');
    $(`.vehicle-item[data-vehicle-id="${vehicleId}"]`).addClass('active');
    
    showVehicleDetail(vehicleId);
    
    if (window.innerWidth <= 1024) {
        setTimeout(() => {
            $('#vehicleDetailSection')[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
}

function showVehicleDetail(vehicleId) {
    const vehicle = vehicles.find(v => v.id == vehicleId);
    if (!vehicle) return;
    
    $('#vehicleDetailSection').show().addClass('active');
    
    const template = document.getElementById('vehicleDetailTemplate');
    $('#vehicleDetailSection').html(template.innerHTML);
    
    $('#detailImage').attr('src', vehicle.image).on('error', function() {
        this.src = '{{ asset('images/default-vehicle.jpg') }}';
    });
    $('#detailName').text(vehicle.name);
    $('#detailType').text(vehicle.type);
    $('#detailSeats').text(vehicle.seats);
    $('#detailLuggage').text(vehicle.luggage);
    $('#detailFuel').text(vehicle.fuel);
    $('#detailDescription').text(vehicle.description || 'Deskripsi tidak tersedia');
    
    const facilitiesGrid = $('#facilitiesGrid');
    facilitiesGrid.empty();
    if (vehicle.facilities && vehicle.facilities.length > 0) {
        vehicle.facilities.forEach(facility => {
            facilitiesGrid.append(`<div class="facility-badge"><i class="fas fa-check-circle"></i> ${facility}</div>`);
        });
    }
    
    updatePrices(vehicle);
}

function updatePrices(vehicle) {
    const vehiclePrice = parseInt(vehicle.price);
    const driverPrice = vehicle.driver_price ? parseInt(vehicle.driver_price) : 0;
    
    $('#selfDrivePrice').text('Rp 0');
    $('#withDriverPrice').text(`Rp ${formatNumber(driverPrice)}/hari`);
    $('#summaryVehiclePrice').text(`Rp ${formatNumber(vehiclePrice)}/hari`);
    $('#summaryDriverPrice').text(`Rp ${formatNumber(driverPrice)}/hari`);
    $('#summaryDuration').text(`${duration} Hari`);
    
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
    
    const params = new URLSearchParams({
        vehicle_id: selectedVehicleId,
        service: selectedService,
        duration: duration,
        rent_date: '{{ $filterData["rent_date"] }}'
    });
    
    window.location.href = '{{ route("smartrent.checkout.booking") }}?' + params.toString();
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
@endsection