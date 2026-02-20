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
        min-width: 140px;
        align-items: flex-end;
    }

    .filter-button {
        padding: 12px 20px;
        height: 46px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        white-space: nowrap;
        width: 100%;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(255, 88, 30, 0.2);
    }

    .filter-button i {
        font-size: 16px;
    }

    .filter-button:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
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
        background-color: white;
        transition: all 0.2s;
    }

    .filter-input:focus,
    .filter-input:hover {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    /* Select2 */
    .select2-container--default .select2-selection--single {
        height: 46px !important;
        border: 2px solid var(--border-color) !important;
        border-radius: 8px !important;
        transition: all 0.2s;
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: var(--secondary-color) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 15px !important;
        padding-right: 30px !important;
        color: #333 !important;
        font-size: 14px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .select2-dropdown {
        border: 2px solid var(--border-color) !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-results__option--highlighted {
        background-color: var(--secondary-color) !important;
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
    }

    .vehicle-info {
        padding: 15px;
    }

    .vehicle-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
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
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-info {
        display: flex;
        flex-direction: column;
    }

    .price-amount {
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary-color);
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

    /* ===== VEHICLE DETAIL ===== */
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        transition: all 0.2s;
    }

    .detail-spec:hover {
        border-color: var(--secondary-color);
        background: #fffaf8;
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
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.1);
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
        text-decoration: none;
    }

    .continue-btn:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
        color: white;
        text-decoration: none;
    }

    .continue-btn:visited, .continue-btn:active, .continue-btn:link {
        color: white;
        text-decoration: none;
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
            order: 1;
            margin-bottom: 15px;
        }
        
        .filter-button {
            width: 100%;
        }
        
        .filter-inputs-group {
            min-width: 100%;
            order: 2;
        }
        
        .filter-group {
            min-width: calc(33.333% - 10px);
        }
        
        .detail-image {
            width: 400px;
            height: 200px;
        }
    }

    @media (max-width: 1024px) {
        .booking-layout {
            grid-template-columns: 1fr;
            gap: 20px;
            min-height: auto;
        }

        .vehicle-list-section {
            position: static;
            max-height: none;
            margin-bottom: 30px;
        }
        
        .detail-image {
            width: 500px;
            height: 250px;
        }
    }

    @media (max-width: 768px) {
        .booking-container {
            padding: 20px 15px;
        }
        
        .booking-title {
            font-size: 28px;
        }
        
        .filter-section {
            padding: 20px;
        }
        
        .filter-row {
            flex-direction: column;
        }
        
        .filter-buttons-group {
            min-width: 100%;
        }
        
        .filter-inputs-group {
            flex-direction: column;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
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
        
        .detail-specs-grid {
            gap: 10px;
        }
        
        .service-options-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .price-summary {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .booking-title {
            font-size: 24px;
        }
        
        .filter-section {
            padding: 15px;
        }
        
        .filter-button {
            padding: 12px 15px;
            font-size: 14px;
        }
        
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
        
        .vehicle-image {
            height: 120px;
        }
        
        .total-value {
            font-size: 20px;
        }
        
        .continue-btn {
            padding: 14px;
            font-size: 16px;
        }
    }
</style>
@endpush

@section('content')
@php
    $filterData = $filterData ?? [
        'city' => '',
        'capacity' => '',
        'brand' => '',
        'rent_date' => date('Y-m-d'),
        'duration' => 1,
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
                </div>
                
                <div class="filter-inputs-group">
                    <div class="filter-group">
                        <label class="filter-label">Kota</label>
                        <select name="city" class="filter-input select2">
                            <option value="">Semua Kota</option>
                            <option value="jakarta" {{ ($filterData['city'] ?? '') == 'jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="bandung" {{ ($filterData['city'] ?? '') == 'bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="surabaya" {{ ($filterData['city'] ?? '') == 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="yogyakarta" {{ ($filterData['city'] ?? '') == 'yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="bali" {{ ($filterData['city'] ?? '') == 'bali' ? 'selected' : '' }}>Bali</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Kapasitas Penumpang</label>
                        <select name="capacity" class="filter-input select2">
                            <option value="">Semua Kapasitas</option>
                            <option value="1-4" {{ ($filterData['capacity'] ?? '') == '1-4' ? 'selected' : '' }}>1-4 Orang</option>
                            <option value="5-7" {{ ($filterData['capacity'] ?? '') == '5-7' ? 'selected' : '' }}>5-7 Orang</option>
                            <option value="8-12" {{ ($filterData['capacity'] ?? '') == '8-12' ? 'selected' : '' }}>8-12 Orang</option>
                            <option value="13+" {{ ($filterData['capacity'] ?? '') == '13+' ? 'selected' : '' }}>13+ Orang</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Merk Mobil</label>
                        <select name="brand" class="filter-input select2">
                            <option value="">Semua Merk</option>
                            <option value="toyota" {{ ($filterData['brand'] ?? '') == 'toyota' ? 'selected' : '' }}>Toyota</option>
                            <option value="honda" {{ ($filterData['brand'] ?? '') == 'honda' ? 'selected' : '' }}>Honda</option>
                            <option value="daihatsu" {{ ($filterData['brand'] ?? '') == 'daihatsu' ? 'selected' : '' }}>Daihatsu</option>
                            <option value="suzuki" {{ ($filterData['brand'] ?? '') == 'suzuki' ? 'selected' : '' }}>Suzuki</option>
                            <option value="mitsubishi" {{ ($filterData['brand'] ?? '') == 'mitsubishi' ? 'selected' : '' }}>Mitsubishi</option>
                            <option value="isuzu" {{ ($filterData['brand'] ?? '') == 'isuzu' ? 'selected' : '' }}>Isuzu</option>
                            <option value="nissan" {{ ($filterData['brand'] ?? '') == 'nissan' ? 'selected' : '' }}>Nissan</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="rent_date" value="{{ $filterData['rent_date'] ?? date('Y-m-d') }}">
            <input type="hidden" name="duration" value="{{ $filterData['duration'] ?? 1 }}">
            <input type="hidden" name="vehicle_id" id="selectedVehicleId" value="{{ $filterData['vehicle_id'] ?? '' }}">
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
                @forelse($vehicles as $vehicle)
                <div class="vehicle-item {{ $selectedVehicle && $selectedVehicle['id'] == $vehicle['id'] ? 'active' : '' }}" 
                     data-vehicle-id="{{ $vehicle['id'] }}">
                    <img src="{{ $vehicle['image'] ?? asset('images/default-vehicle.jpg') }}" 
                         alt="{{ $vehicle['name'] }}" 
                         class="vehicle-image"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-vehicle.jpg') }}';">
                    <div class="vehicle-info">
                        <h3 class="vehicle-name">{{ $vehicle['name'] }}</h3>
                        <span class="vehicle-type">{{ $vehicle['type'] ?? 'MPV' }}</span>
                        
                        <div class="vehicle-specs">
                            <div class="spec">
                                <i class="fas fa-chair"></i>
                                <span>{{ $vehicle['seats'] ?? 7 }} Kursi</span>
                            </div>
                            <div class="spec">
                                <i class="fas fa-suitcase"></i>
                                <span>{{ $vehicle['luggage'] ?? 2 }} Bagasi</span>
                            </div>
                            <div class="spec">
                                <i class="fas fa-gas-pump"></i>
                                <span>{{ $vehicle['fuel'] ?? 'Bensin' }}</span>
                            </div>
                        </div>
                        
                        <div class="vehicle-price">
                            <div class="price-info">
                                <div class="price-amount">Rp {{ number_format($vehicle['price'] ?? 0, 0, ',', '.') }}</div>
                                <div class="price-period">{{ $vehicle['period'] ?? '/hari' }}</div>
                            </div>
                            <button type="button" class="select-btn" onclick="selectVehicle({{ $vehicle['id'] }})">
                                Pilih
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-vehicles">
                    <i class="fas fa-car"></i>
                    <h3>Tidak ada kendaraan ditemukan</h3>
                    <p>Coba ubah filter pencarian Anda</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Vehicle Detail Section -->
        <div class="vehicle-detail-section" id="vehicleDetailSection"></div>
    </div>
</div>

<!-- Template untuk Vehicle Detail -->
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
            <span class="summary-value" id="summaryDuration">{{ $filterData['duration'] ?? 1 }} Hari</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total Sewa</span>
            <span class="total-value" id="summaryTotal">Rp 0</span>
        </div>
        <p class="summary-note">
            <i class="fas fa-info-circle"></i> Harga sudah termasuk PPN 11%. Belum termasuk akomodasi sopir untuk sewa > 1 hari.
        </p>
        <a href="/smartrent/checkout" class="continue-btn" onclick="return continueToPayment(event)">
            <i class="fas fa-arrow-right"></i> Lanjutkan ke Pembayaran
        </a>
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
let duration = {{ $filterData['duration'] ?? 1 }};
let rentDate = '{{ $filterData['rent_date'] ?? date('Y-m-d') }}';

$(document).ready(function() {
    // Initialize select2
    $('.select2').select2({
        minimumResultsForSearch: 3,
        width: '100%',
        placeholder: 'Pilih opsi',
        allowClear: true
    });

    if (selectedVehicleId) {
        showVehicleDetail(selectedVehicleId);
    }
});

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
            $('#vehicleDetailSection')[0].scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }, 100);
    }
}

function showVehicleDetail(vehicleId) {
    const vehicle = vehicles.find(v => v.id == vehicleId);
    if (!vehicle) return;
    
    $('#vehicleDetailSection').show().addClass('active');
    
    const template = document.getElementById('vehicleDetailTemplate');
    $('#vehicleDetailSection').html(template.innerHTML);
    
    // Isi data kendaraan
    $('#detailImage').attr('src', vehicle.image || '{{ asset('images/default-vehicle.jpg') }}').on('error', function() {
        this.src = '{{ asset('images/default-vehicle.jpg') }}';
    });
    $('#detailName').text(vehicle.name);
    $('#detailType').text(vehicle.type || 'MPV');
    $('#detailSeats').text(vehicle.seats + ' Kursi');
    $('#detailLuggage').text(vehicle.luggage + ' Bagasi');
    $('#detailFuel').text(vehicle.fuel || 'Bensin');
    $('#detailDescription').text(vehicle.description || 'Tidak ada deskripsi untuk kendaraan ini.');
    
    // Isi fasilitas
    const facilitiesGrid = $('#facilitiesGrid');
    facilitiesGrid.empty();
    if (vehicle.facilities && vehicle.facilities.length > 0) {
        vehicle.facilities.forEach(facility => {
            facilitiesGrid.append(`<div class="facility-badge"><i class="fas fa-check-circle"></i> ${facility}</div>`);
        });
    } else {
        const defaultFacilities = ['AC', 'Audio', 'Power Window', 'Central Lock'];
        defaultFacilities.forEach(facility => {
            facilitiesGrid.append(`<div class="facility-badge"><i class="fas fa-check-circle"></i> ${facility}</div>`);
        });
    }
    
    // Update link href dengan parameter
    updateContinueButtonLink(vehicleId);
    
    updatePrices(vehicle);
}

function updateContinueButtonLink(vehicleId) {
    const params = new URLSearchParams({
        vehicle_id: vehicleId,
        service: selectedService,
        duration: duration,
        rent_date: rentDate
    });
    
    // Gunakan URL langsung
    $('.continue-btn').attr('href', '/smartrent/checkout?' + params.toString());
}

function updatePrices(vehicle) {
    const vehiclePrice = parseInt(vehicle.price) || 0;
    const driverPrice = vehicle.driver_price ? parseInt(vehicle.driver_price) : 150000;
    
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
    
    // Update link href dengan service baru
    if (selectedVehicleId) {
        updateContinueButtonLink(selectedVehicleId);
    }
    
    calculateTotal();
}

function calculateTotal() {
    const vehicle = vehicles.find(v => v.id == selectedVehicleId);
    if (!vehicle) return;
    
    const vehiclePrice = parseInt(vehicle.price) || 0;
    let driverPrice = 0;
    
    if (selectedService === 'with-driver') {
        driverPrice = vehicle.driver_price ? parseInt(vehicle.driver_price) : 150000;
    }
    
    const totalPerDay = vehiclePrice + driverPrice;
    const total = totalPerDay * duration;
    
    $('#summaryTotal').text(`Rp ${formatNumber(total)}`);
}

function continueToPayment(event) {
    if (!selectedVehicleId) {
        event.preventDefault();
        alert('Silakan pilih kendaraan terlebih dahulu');
        return false;
    }
    
    // Cari data kendaraan yang dipilih
    const selectedVehicle = vehicles.find(v => v.id == selectedVehicleId);
    
    // Simpan data lengkap kendaraan ke sessionStorage
    const checkoutData = {
        vehicle_id: selectedVehicleId,
        vehicle_name: selectedVehicle.name,
        vehicle_image: selectedVehicle.image,
        vehicle_brand: selectedVehicle.brand || 'Toyota',
        vehicle_type: selectedVehicle.type || 'MPV',
        vehicle_seats: selectedVehicle.seats || 7,
        vehicle_luggage: selectedVehicle.luggage || 2,
        vehicle_fuel: selectedVehicle.fuel || 'Bensin',
        vehicle_price: selectedVehicle.price || 0,
        service: selectedService,
        duration: duration,
        rent_date: rentDate,
        driver_price: selectedVehicle.driver_price || 150000
    };
    
    // Simpan ke sessionStorage
    sessionStorage.setItem('smartrent_checkout_data', JSON.stringify(checkoutData));
    
    // Lanjutkan ke halaman checkout (link sudah di-set di href)
    return true;
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
@endsection