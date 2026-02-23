@extends('layouts.app-driver')

@section('title', 'Perjalanan - Smart Shuttle Driver')

@push('styles')
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* ======== SIDEBAR ======== */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0d3559;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px;
            box-sizing: border-box;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 22px;
            font-weight: bold;
            color: #ff6a00;
            margin-bottom: 35px;
            line-height: 1.3;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 10px;
            margin-bottom: 10px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu-active {
            background: #ff6a00;
            color: white;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
        }

        /* ======== CONTENT ======== */
        .content {
            margin-left: 290px;
            padding: 40px;
            min-height: 100vh;
            background: #f5f7fa;
        }

        .top-profile {
            text-align: right;
            font-size: 15px;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        /* ===== HEADER SECTION ===== */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #333;
        }

        .profile-box {
            background: white;
            padding: 10px 18px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb-item {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb-item:hover {
            color: #ff6a00;
        }

        .breadcrumb-separator {
            color: #999;
        }

        .breadcrumb-current {
            color: #333;
            font-weight: 600;
        }

        /* ===== CARD ===== */
        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        /* STYLE UNTUK HALAMAN DAFTAR PERJALANAN */
        .driver-page {
            background: #F5F5F5;
            min-height: 100vh;
            padding: 20px 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .date-display {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .trip-item {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            background: #fff;
            cursor: pointer;
        }

        .trip-item:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .trip-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .trip-number {
            font-size: 24px;
            font-weight: 700;
            color: #ff6a00;
        }

        .seat-info {
            font-size: 13px;
            color: #777;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .trip-route {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 5px 0;
        }

        .trip-time {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .status-badge {
            text-align: center;
            margin: 15px 0;
        }

        .status {
            background: #FFE38E;
            color: #8a6d3b;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-selesai {
            background: #9BE79B;
            color: #2d572c;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .btn-detail {
            background: #0d3559;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer !important;
            transition: background 0.3s ease;
            font-size: 14px;
            pointer-events: auto !important;
            user-select: none;
        }

        .btn-detail:hover {
            background: #0a2b4a;
        }

        .btn-detail:active {
            transform: scale(0.98);
        }

        .btn-back {
            background: #f0f0f0;
            color: #333;
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: #e0e0e0;
        }

        .history-filter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            font-size: 14px;
        }

        .history-item {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 15px;
            background: #fff;
        }

        .history-route {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .history-date {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }

        .history-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .passenger-count {
            font-size: 14px;
            color: #444;
            font-weight: 500;
        }

        .status-completed {
            background: #9BE79B;
            color: #2d572c;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* STYLE UNTUK HALAMAN DETAIL PERJALANAN */
        /* ===== CARD PERJALANAN AKTIF ===== */
        .card-aktif {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .card-left {
            width: 45%;
        }

        .card-title-detail {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }

        .card-title-detail i {
            color: #36B35A;
        }

        .location {
            margin-bottom: 15px;
        }

        .loc-title {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }

        .loc-title i.fa-play {
            color: #36B35A;
        }

        .loc-title i.fa-location-dot {
            color: #FF6A00;
        }

        .loc-title i.fa-map-marker-alt {
            color: #0095FF;
        }

        .loc-sub {
            font-size: 14px;
            color: #666;
            margin-left: 28px;
        }

        .line {
            width: 2px;
            height: 25px;
            background: #DDD;
            margin: 10px 0 10px 12px;
        }

        .card-right {
            width: 55%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .info-section {
            flex: 1;
        }

        .button-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-left: 30px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-row i {
            width: 20px;
            text-align: center;
            color: #0B2A4A;
        }

        .info-title {
            font-size: 14px;
            color: #555;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: #333;
        }

        .btn-primary {
            background: #0095FF;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            min-width: 180px;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .btn-primary:hover {
            background: #007acc;
        }

        .btn-success {
            background: #36B35A;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            min-width: 180px;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .btn-success:hover {
            background: #2d9c4a;
        }

        .btn-success.hidden {
            display: none;
        }

        /* ===== MAP BOX ===== */
        .map-box {
            height: 230px;
            background: #DCDCDC;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 14px;
        }

        .map-placeholder {
            text-align: center;
            color: #555;
            font-size: 18px;
        }

        .map-placeholder i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
            color: #888;
        }

        /* ===== PROGRESS INDICATOR ===== */
        .progress-container {
            margin: 20px 0;
        }

        .progress-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #E0E0E0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #36B35A;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-stops {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .progress-stop {
            font-size: 12px;
            color: #666;
            text-align: center;
            flex: 1;
        }

        .progress-stop.active {
            color: #36B35A;
            font-weight: 600;
        }

        .progress-stop.completed {
            color: #0095FF;
            font-weight: 600;
        }

        /* ===== DAFTAR PENUMPANG ===== */
        .penumpang-section {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .penumpang-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .penumpang-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .penumpang-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .penumpang-info {
            flex: 1;
        }

        .penumpang-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .penumpang-phone {
            font-size: 14px;
            color: #666;
        }

        .penumpang-seat {
            text-align: right;
            min-width: 100px;
        }

        .seat-number {
            font-size: 18px;
            font-weight: 700;
            color: #ff6a00;
            margin-bottom: 5px;
        }

        .seat-status {
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: 600;
            display: inline-block;
        }

        .status-refund {
            background: #FFE5E5;
            color: #D32F2F;
        }

        .status-terdaftar {
            background: #E3F2FD;
            color: #1976D2;
        }

        .status-terverifikasi {
            background: #E8F5E9;
            color: #2E7D32;
        }

        /* ===== MODAL UPDATE LOKASI ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            pointer-events: auto;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease;
            position: relative;
            pointer-events: auto;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .modal-subtitle {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            position: relative;
            z-index: 2001;
        }

        .btn-cancel {
            flex: 1;
            background: #f0f0f0;
            border: none;
            padding: 12px;
            border-radius: 8px;
            color: #333;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            pointer-events: auto;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
        }

        .btn-update {
            flex: 1;
            background: #0095FF;
            border: none;
            padding: 12px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            pointer-events: auto;
        }

        .btn-update:hover {
            background: #007acc;
        }

        /* DIVIDER */
        .divider {
            width: 100%;
            height: 3px;
            background: #E2E2E2;
            margin: 0 0 25px 0;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 15px 10px;
            }

            .sidebar-title {
                font-size: 14px;
                margin-bottom: 25px;
            }

            .menu-item span {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 15px 5px;
            }

            .content {
                margin-left: 90px;
                padding: 20px;
            }

            .card-aktif {
                flex-direction: column;
            }

            .card-left, .card-right {
                width: 100%;
            }

            .button-section {
                margin-left: 0;
                margin-top: 20px;
            }

            .header-section {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card-right {
                flex-direction: column;
                gap: 20px;
            }

            .info-section {
                width: 100%;
            }

            .button-section {
                width: 100%;
            }

            .btn-primary, .btn-success, .btn-detail, .btn-back {
                min-width: 100%;
            }

            .penumpang-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .penumpang-seat {
                text-align: left;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-content {
                width: 95%;
                margin: 20px;
            }
        }

        /* KELAS UNTUK MENYEMBUNYIKAN ELEMEN */
        .hidden {
            display: none !important;
        }

        .visible {
            display: block !important;
        }
    </style>
@endpush

@section('content')<!-- ========================== -->
    <!-- HALAMAN DAFTAR PERJALANAN -->
    <!-- ========================== -->
    <div id="daftarPerjalananPage" class="driver-page">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item">Dashboard</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Perjalanan</span>
        </div>

        <!-- Header -->
        <div class="header-section">
            <h1 class="page-title">Daftar Perjalanan</h1>
            <div style="font-weight:500; color:#555;">👤 {{ auth()->guard('driver')->user()?->name ?? "Driver" }}</div>
        </div>

        <!-- CARD DAFTAR PERJALANAN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Perjalanan Hari Ini</div>
                <div class="date-display">Tanggal: <span id="currentDateDisplay">{{ \Carbon\Carbon::today()->format('d M Y') }}</span></div>
            </div>

            <!-- LIST PERJALANAN -->
            @forelse($tripsData as $trip)
            <div class="trip-item" data-trip-id="{{ $trip['id_jadwal_driver'] }}" data-from="{{ $trip['from'] }}" data-to="{{ $trip['to'] }}" data-time="{{ $trip['time'] }}" data-eta="{{ $trip['eta'] ?? '' }}" data-duration="{{ $trip['estimated_duration'] }}" data-distance="{{ $trip['distance'] ?? '' }}" data-seats="{{ $trip['occupied_seats'] }}/{{ $trip['total_seats'] }}" data-passengers="{{ $trip['occupied_seats'] }}">
                <div class="trip-header">
                    <div class="trip-number">{{ $trip['trip_number'] }}</div>
                    <div class="seat-info">{{ $trip['occupied_seats'] }}/{{ $trip['total_seats'] }} kursi</div>
                </div>

                <div class="trip-route">{{ $trip['from'] }} → {{ $trip['to'] }}</div>
                <div class="trip-time">{{ $trip['time'] }} • {{ $trip['estimated_duration'] ?? '-' }}</div>

                <div class="status-badge">
                    <span class="status" id="status-{{ $trip['id_jadwal_driver'] }}">
                        @php
                            $status = $trip['status'] ?? 'belum_dimulai';
                            $statusDisplay = match($status) {
                                'belum_dimulai' => 'Akan Berangkat',
                                'aktif' => 'Perjalanan Aktif',
                                'selesai' => 'Selesai',
                                default => ucfirst(str_replace('_', ' ', $status))
                            };
                        @endphp
                        {{ $statusDisplay }}
                    </span>
                </div>

                <div style="text-align:right;">
                    <button type="button" class="btn-detail">Lihat Detail</button>
                </div>
            </div>
            @empty
            <div style="padding: 30px; text-align: center; color: #999;">
                <i class="fas fa-calendar-days" style="font-size: 40px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                <p style="margin: 0; font-size: 16px;">Tidak ada perjalanan hari ini</p>
                <p style="margin: 5px 0; font-size: 13px;">Menunggu jadwal dari admin</p>
            </div>
            @endforelse
        </div>

        <!-- RIWAYAT PERJALANAN (DI HALAMAN AWAL) -->
        <div class="card">
            <div class="history-filter">
                <div class="card-title">Riwayat Perjalanan</div>
                <select class="filter-select" id="historyFilterSelect">
                    <option>Semua</option>
                    <option>Minggu ini</option>
                    <option>Bulan ini</option>
                    <option>3 bulan terakhir</option>
                </select>
            </div>

            <div id="historyItemsContainer">
                <!-- History items akan dirender oleh JavaScript -->
            </div>
        </div>
    </div>

    <!-- ========================== -->
    <!-- HALAMAN DETAIL PERJALANAN -->
    <!-- ========================== -->
    <div id="detailPerjalananPage" class="driver-page hidden">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="#" id="backToDaftar" class="breadcrumb-item">Perjalanan</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Detail Perjalanan</span>
        </div>

        <!-- Header dengan tombol kembali -->
        <div class="header-section">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="btn-back" id="backButton">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <h1 class="title">Detail Perjalanan</h1>
            </div>
            <div class="profile-box">
                <i class="fas fa-user"></i>
                <span>{{ auth()->guard('driver')->user()?->name ?? "Driver" }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- CARD PERJALANAN AKTIF -->
        <div class="card card-aktif">
            <div class="card-left">
                <h3 class="card-title-detail"><i class="fa-solid fa-circle-play"></i> <span id="tripTitle">Perjalanan Aktif</span></h3>

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-title">Progress Perjalanan</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                    </div>
                    <div class="progress-stops">
                        <div class="progress-stop completed" id="stop-0">Start</div>
                        <div class="progress-stop" id="stop-1">Stop 1</div>
                        <div class="progress-stop" id="stop-2">Stop 2</div>
                        <div class="progress-stop" id="stop-3">Finish</div>
                    </div>
                </div>

                <!-- Rute Perjalanan -->
                <div class="location">
                    <div class="loc-title"><i class="fa-solid fa-play"></i> <span id="currentLocation">Bandung</span></div>
                    <div class="loc-sub" id="currentLocationDetail">Terminal Bus Leuwipanjang</div>
                </div>

                <div class="line"></div>

                <div class="location">
                    <div class="loc-title"><i class="fa-solid fa-location-dot"></i> <span id="finalDestination">Jakarta Pusat</span></div>
                    <div class="loc-sub" id="finalDestinationDetail">Terminal Bus Kampung Rambutan</div>
                </div>

                <!-- Titik Pemberhentian -->
                <div id="stopPoints" style="margin-top: 20px;">
                    <!-- Titik pemberhentian akan ditampilkan di sini -->
                </div>
            </div>

            <div class="card-right">
                <div class="info-section">
                    <div class="info-row">
                        <i class="fa-solid fa-clock"></i>
                        <div>
                            <div class="info-title">Waktu Tempuh</div>
                            <div class="info-value" id="travelTime">3 jam 15 menit</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-road"></i>
                        <div>
                            <div class="info-title">Jarak</div>
                            <div class="info-value" id="distance">145 km</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <div class="info-title">Penumpang</div>
                            <div class="info-value" id="passengerCount">10/12</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-flag-checkered"></i>
                        <div>
                            <div class="info-title">Titik Selanjutnya</div>
                            <div class="info-value" id="nextStop">Rest Area KM 58</div>
                        </div>
                    </div>
                </div>

                <div class="button-section">
                    <button class="btn-success" id="mulaiPerjalananBtn">
                        <i class="fa-solid fa-play"></i> Mulai Perjalanan
                    </button>
                    <button class="btn-primary hidden" id="updateLokasiBtn">
                        <i class="fa-solid fa-location-arrow"></i> Update Lokasi
                    </button>
                    <button class="btn-success hidden" id="selesaiPerjalananBtn">
                        <i class="fa-solid fa-check"></i> Selesaikan Perjalanan
                    </button>
                </div>
            </div>
        </div>

        <!-- MAPS -->
        <div class="card map-box">
            <div class="map-placeholder">
                <i class="fa-solid fa-location-dot"></i>
                <p>Maps akan tampil saat perjalanan aktif</p>
                <div style="margin-top: 15px; font-size: 14px;">
                    <strong>Posisi Saat Ini:</strong> <span id="mapLocation">Bandung</span>
                </div>
            </div>
        </div>

        <!-- DAFTAR PENUMPANG (DI HALAMAN DETAIL) -->
        <div class="card penumpang-section">
            <div class="section-header">
                <h3 class="section-title">Daftar Penumpang</h3>
                <div style="font-size: 14px; color: #666;">
                    Total: <strong id="totalPenumpang">10</strong> penumpang
                </div>
            </div>

            <!-- Daftar Penumpang - akan di-generate secara dinamis -->
            <div class="penumpang-list" id="penumpangList">
                <!-- Daftar penumpang akan di-generate disini -->
            </div>
        </div>
    </div>
</main>

@endsection

<!-- MODAL UPDATE LOKASI -->
<div class="modal-overlay" id="updateLokasiModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Lokasi</h3>
            <p class="modal-subtitle">Lokasi bus akan berpindah ke titik berikutnya</p>
            <p class="modal-subtitle" style="margin-top: 10px; font-weight: 600;" id="modalNextLocation"></p>

            <!-- ★★★ OUTLETS INFO SECTION ★★★ -->
            <div id="modalOutletsInfo" style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px; display: none;">
                <p style="font-weight: 600; margin-bottom: 10px; color: #333;">Outlets di Pemberhentian:</p>
                <div id="modalOutletsList" style="margin-left: 15px;"></div>
            </div>
        </div>

        <div class="modal-buttons">
            <button class="btn-cancel" id="cancelUpdateBtn">Batal</button>
            <button class="btn-update" id="confirmUpdateBtn">Update</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Server-provided trips data (generated in DriverController)
    const tripsData = {!! json_encode($tripsData ?? []) !!};
    const completedTrips = {!! json_encode($completedTrips ?? []) !!};
    const currentDriverId = {!! json_encode(auth()->guard('driver')->user()?->id ?? null) !!};

    // ★★★ DEBUG: LOG DATA YANG DITERIMA ★★★
    console.log('%c=== PERJALANAN DATA DEBUG ===', 'color: blue; font-weight: bold; font-size: 14px;');
    console.log('Total Jadwal Aktif:', tripsData.length);
    console.log('Total Jadwal Selesai:', completedTrips.length);
    console.log('Driver ID:', currentDriverId);

    if (tripsData.length > 0) {
        console.log('%cJADWAL AKTIF:', 'color: green; font-weight: bold;');
        tripsData.forEach((t, idx) => {
            console.log(`  ${idx + 1}. [${t.id_jadwal_driver}] ${t.from} → ${t.to} | ${t.date} | ${t.time} | Status: ${t.status}`);
        });
    }

    if (completedTrips.length > 0) {
        console.log('%cJADWAL SELESAI:', 'color: orange; font-weight: bold;');
        completedTrips.forEach((t, idx) => {
            console.log(`  ${idx + 1}. [${t.id_jadwal_driver}] ${t.from} → ${t.to} | ${t.date || t.tanggal} | Status: ${t.status}`);
        });
    }
    console.log('%c==============================', 'color: blue; font-weight: bold;');

    // ★★★ JOURNEY START STATE TRACKING ★★★
    let journeyStarted = {}; // Tracks which trips have been started: { tripId: true/false }

    // Fallback journey data (used when route stop points are not available)
    let journeyData = {
        currentStopIndex: 0,
        stops: [
            { name: "Start", detail: "Lokasi Awal", type: "start" },
            { name: "Titik 1", detail: "Titik Pemberhentian 1", type: "stop" },
            { name: "Titik 2", detail: "Titik Pemberhentian 2", type: "stop" },
            { name: "Finish", detail: "Tujuan Akhir", type: "finish" }
        ],
        travelTimes: ["-", "-", "-", "-"],
        distances: ["-", "-", "-", "-"]
    };

    // Variable untuk menyimpan ID perjalanan yang sedang aktif
    let currentTripId = null;

    // Fungsi untuk mengatur status aktif pada menu sidebar
    function setActiveMenu() {
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });

        const currentPath = window.location.pathname;
        let activeLink = null;

        if (currentPath.includes('dashboard')) {
            activeLink = document.getElementById('dashboard-link');
        } else if (currentPath.includes('perjalanan')) {
            activeLink = document.getElementById('perjalanan-link');
        } else if (currentPath.includes('jadwal')) {
            activeLink = document.getElementById('jadwal-link');
        } else if (currentPath.includes('profile')) {
            activeLink = document.getElementById('profile-link');
        } else if (currentPath.includes('laporan')) {
            activeLink = document.getElementById('laporan-link');
        } else if (currentPath.includes('pengaturan')) {
            activeLink = document.getElementById('pengaturan-link');
        } else if (currentPath.includes('bantuan')) {
            activeLink = document.getElementById('bantuan-link');
        }

        if (!activeLink) {
            activeLink = document.getElementById('dashboard-link');
        }

        if (activeLink) {
            activeLink.classList.add('menu-active');
        }
    }

    // ★★★ FUNGSI UNTUK MEMBANGUN JOURNEY DATA DARI STOP POINTS ★★★
    function buildJourneyDataFromStopPoints(tripData) {
        const stopPoints = tripData.stop_points || [];

        // Inisialisasi struktur journey data
        journeyData = {
            currentStopIndex: 0,
            stops: [],
            travelTimes: [],
            distances: []
        };

        // ★★★ PERBAIKAN: MEMBUAT SATU ENTRY PER OUTLET, BUKAN PER BRANCH ★★★
        // Ini memastikan perpindahan antar outlet dalam branch yang sama terdeteksi sebagai update

        // STEP 1: Ambil titik awal - buat entry untuk SETIAP outlet di stop point pertama
        if (Array.isArray(stopPoints) && stopPoints.length > 0) {
            const firstStop = stopPoints[0];

            if (firstStop.outlets && firstStop.outlets.length > 0) {
                // ★★★ PERBEDAAN: Loop setiap outlet dan buat entry terpisah ★★★
                firstStop.outlets.forEach((outlet, outletIdx) => {
                    const isFirstOutlet = outletIdx === 0;
                    journeyData.stops.push({
                        name: outlet.nama_outlet,
                        detail: `${firstStop.branch_name} (Awal)`,
                        type: isFirstOutlet ? "start" : "stop",
                        outlet_id: outlet.id,
                        outlet_detail: outlet,
                        branch_id: firstStop.branch_id,
                        kota: firstStop.kota,
                        is_starting_outlet: true,
                        is_outlet: true
                    });

                    journeyData.travelTimes.push("-");
                    journeyData.distances.push("-");
                });
            } else {
                // Fallback jika first stop tidak punya outlets
                journeyData.stops.push({
                    name: tripData.from,
                    detail: `Titik Awal - ${tripData.from}`,
                    type: "start",
                    is_outlet: false
                });
                journeyData.travelTimes.push("-");
                journeyData.distances.push("-");
            }
        } else {
            // Fallback jika tidak ada stop points sama sekali
            journeyData.stops.push({
                name: tripData.from,
                detail: `Titik Awal - ${tripData.from}`,
                type: "start",
                is_outlet: false
            });
            journeyData.travelTimes.push("-");
            journeyData.distances.push("-");
        }

        // STEP 2: Tambahkan outlets pemberhentian - buat entry untuk SETIAP outlet di setiap stop
        if (Array.isArray(stopPoints) && stopPoints.length > 1) {
            // Skip stop pertama (index 0) karena sudah diproses
            for (let stopIdx = 1; stopIdx < stopPoints.length; stopIdx++) {
                const stop = stopPoints[stopIdx];

                // ★★★ PERBEDAAN: Loop setiap outlet dan buat entry terpisah ★★★
                if (stop.outlets && stop.outlets.length > 0) {
                    stop.outlets.forEach((outlet, outletIdx) => {
                        journeyData.stops.push({
                            name: outlet.nama_outlet,
                            detail: `${stop.branch_name} | ${stop.durasi_singgah || 10} menit singgah`,
                            type: "stop",
                            outlet_id: outlet.id,
                            outlet_detail: outlet,
                            branch_id: stop.branch_id,
                            kota: stop.kota,
                            duration: stop.durasi_singgah || 10,
                            is_outlet: true,
                            stop_point_index: stopIdx
                        });

                        journeyData.travelTimes.push(`${stop.durasi_singgah || 10} menit singgah`);
                        journeyData.distances.push("-");
                    });
                } else {
                    // Fallback jika stop tidak punya outlets
                    journeyData.stops.push({
                        name: stop.kota || `Stop ${stopIdx}`,
                        detail: stop.branch_name || '',
                        type: "stop",
                        duration: stop.durasi_singgah || 10,
                        is_outlet: false,
                        stop_point_index: stopIdx
                    });

                    journeyData.travelTimes.push(`${stop.durasi_singgah || 10} menit singgah`);
                    journeyData.distances.push("-");
                }
            }
        } else if (Array.isArray(stopPoints) && stopPoints.length === 1) {
            // Jika hanya 1 stop point, itu adalah final destination
            const lastStop = stopPoints[0];
            if (lastStop.outlets && lastStop.outlets.length > 0) {
                lastStop.outlets.forEach((outlet, outletIdx) => {
                    journeyData.stops.push({
                        name: outlet.nama_outlet,
                        detail: `${lastStop.branch_name} (Tujuan Akhir)`,
                        type: outletIdx === lastStop.outlets.length - 1 ? "finish" : "stop",
                        outlet_id: outlet.id,
                        outlet_detail: outlet,
                        branch_id: lastStop.branch_id,
                        kota: lastStop.kota,
                        duration: lastStop.durasi_singgah || 10,
                        is_outlet: true,
                        is_final_outlet: true
                    });

                    journeyData.travelTimes.push("-");
                    journeyData.distances.push("-");
                });
            }
        } else {
            // Fallback jika tidak ada stop points sama sekali
            journeyData.stops.push({
                name: "Titik 1",
                detail: "Titik Pemberhentian 1",
                type: "stop",
                is_outlet: false
            });
            journeyData.travelTimes.push("-");
            journeyData.distances.push("-");
        }

        // STEP 3: Tambahkan titik akhir jika ada stop points
        if (Array.isArray(stopPoints) && stopPoints.length > 1) {
            const lastStop = stopPoints[stopPoints.length - 1];

            // ★★★ PERBEDAAN: Buat entry untuk SETIAP outlet di stop point terakhir ★★★
            if (lastStop.outlets && lastStop.outlets.length > 0) {
                lastStop.outlets.forEach((outlet, outletIdx) => {
                    const isLastOutlet = outletIdx === lastStop.outlets.length - 1;
                    journeyData.stops.push({
                        name: outlet.nama_outlet,
                        detail: `${lastStop.branch_name} (Tujuan Akhir)`,
                        type: isLastOutlet ? "finish" : "stop",
                        outlet_id: outlet.id,
                        outlet_detail: outlet,
                        branch_id: lastStop.branch_id,
                        kota: lastStop.kota,
                        is_outlet: true,
                        is_final_outlet: true
                    });

                    journeyData.travelTimes.push("-");
                    journeyData.distances.push("-");
                });
            } else {
                // Fallback jika last stop tidak punya outlets
                journeyData.stops.push({
                    name: tripData.to,
                    detail: `Tujuan Akhir - ${tripData.to}`,
                    type: "finish",
                    is_outlet: false
                });
                journeyData.travelTimes.push("-");
                journeyData.distances.push("-");
            }
        }

        console.log('✅ Journey data built dengan struktur per-OUTLET (bukan per-branch)', journeyData);
    }

    // ★★★ FUNGSI UNTUK CEK DAN LOG DATA OUTLETS ★★★
    function debugOutletsData(tripData) {
        console.log('═══════════════════════════════════════════════════════');
        console.log('📊 DEBUG: OUTLETS DATA UNTUK TRIP', tripData.id_jadwal_driver);
        console.log('═══════════════════════════════════════════════════════');

        // Log stop points
        const stopPoints = tripData.stop_points || [];
        console.log(`\n📍 Total Stop Points: ${stopPoints.length}`);

        stopPoints.forEach((stop, index) => {
            console.log(`\n--- Stop ${index} ---`);
            console.log(`  Kota: ${stop.kota}`);
            console.log(`  Branch: ${stop.branch_name} (ID: ${stop.branch_id})`);
            console.log(`  Durasi Singgah: ${stop.durasi_singgah} menit`);

            if (stop.outlets && stop.outlets.length > 0) {
                console.log(`  📦 Outlets (${stop.outlets.length}):`);
                stop.outlets.forEach((outlet, outletIdx) => {
                    console.log(`     [${outletIdx + 1}] ${outlet.nama_outlet}`);
                    console.log(`         Alamat: ${outlet.alamat}`);
                    console.log(`         Kota: ${outlet.kota}`);
                });
            } else {
                console.log(`  ⚠️ No outlets data!`);
            }
        });

        // Log journey structure after processing
        console.log('\n═══════════════════════════════════════════════════════');
        console.log('🚌 JOURNEY STRUCTURE SETELAH DIPROSES:');
        console.log('═══════════════════════════════════════════════════════');

        journeyData.stops.forEach((stop, index) => {
            console.log(`\n[${index}] ${stop.type.toUpperCase()} - ${stop.name}`);
            console.log(`    Detail: ${stop.detail}`);
            if (stop.outlets && stop.outlets.length > 0) {
                console.log(`    Outlets (${stop.outlets.length}):`);
                stop.outlets.forEach(outlet => {
                    console.log(`      • ${outlet.nama_outlet} - ${outlet.alamat}`);
                });
            }
        });

        console.log('\n═══════════════════════════════════════════════════════');
        console.log('✅ Debug selesai. Cek konsol browser untuk detail outlet.');
        console.log('═══════════════════════════════════════════════════════\n');
    }

    // ★★★ FUNGSI UNTUK VALIDASI OUTLETS COMPLETENESS ★★★
    function validateOutletsCompleteness() {
        console.log('\n--- VALIDASI OUTLETS COMPLETENESS ---');
        let issues = [];
        let warnings = [];

        journeyData.stops.forEach((stop, index) => {
            if (!stop.outlets || stop.outlets.length === 0) {
                if (stop.type !== 'finish' && stop.type !== 'start') {
                    warnings.push(`Stop ${index} (${stop.name}) tidak punya outlets data`);
                }
            } else {
                console.log(`✓ Stop ${index} (${stop.name}): ${stop.outlets.length} outlet(s)`);
            }
        });

        if (warnings.length > 0) {
            console.warn('⚠️ Warnings:', warnings);
        }

        if (issues.length === 0 && warnings.length === 0) {
            console.log('✅ Semua outlet data lengkap!');
        }
    }

    // Fungsi untuk mengupdate tampilan perjalanan di halaman detail
    function updateJourneyDisplay() {
        const currentStop = journeyData.stops[journeyData.currentStopIndex];
        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        const isLastStop = journeyData.currentStopIndex === journeyData.stops.length - 1;

        // Update lokasi saat ini
        document.getElementById('currentLocation').textContent = currentStop.name;

        // ★★★ PERBAIKAN: Struktur sekarang per-OUTLET, bukan per-branch ★★★
        // Jadi kita tampilkan outlet detail langsung, bukan loop outlets array
        const currentLocationDetailEl = document.getElementById('currentLocationDetail');
        if (currentStop.is_outlet && currentStop.outlet_detail) {
            let html = '';
            if (currentStop.detail) html += `<strong>${currentStop.detail}</strong>`;
            html += `<div style="font-size:13px; color:#666; margin-top:6px;">`;
            html += `<div style="margin-bottom:6px;"><strong>${currentStop.outlet_detail.nama_outlet}</strong>`;
            if (currentStop.outlet_detail.alamat) {
                html += `<div style="font-size:11px; color:#666;">${currentStop.outlet_detail.alamat}</div>`;
            }
            html += `</div></div>`;
            currentLocationDetailEl.innerHTML = html;
        } else {
            currentLocationDetailEl.textContent = currentStop.detail || '';
        }
        document.getElementById('mapLocation').textContent = currentStop.name;

        // Update tujuan akhir
        const finalDestination = journeyData.stops[journeyData.stops.length - 1];
        document.getElementById('finalDestination').textContent = finalDestination.name;
        const finalDestinationEl = document.getElementById('finalDestinationDetail');
        if (finalDestination.is_outlet && finalDestination.outlet_detail) {
            let html = '';
            if (finalDestination.detail) html += `<strong>${finalDestination.detail}</strong>`;
            html += `<div style="font-size:13px; color:#666; margin-top:6px;">`;
            html += `<div style="margin-bottom:6px;"><strong>${finalDestination.outlet_detail.nama_outlet}</strong>`;
            if (finalDestination.outlet_detail.alamat) {
                html += `<div style="font-size:11px; color:#666;">${finalDestination.outlet_detail.alamat}</div>`;
            }
            html += `</div></div>`;
            finalDestinationEl.innerHTML = html;
        } else {
            finalDestinationEl.textContent = finalDestination.detail || '';
        }

        // ★★★ PERBAIKAN: Gunakan data dari tripsData untuk info utama ★★★
        // Cari trip data yang sedang aktif
        const activeTrip = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));

        if (activeTrip) {
            // Waktu Tempuh:优先使用 estimated_duration
            const travelEl = document.getElementById('travelTime');
            if (activeTrip.estimated_duration && activeTrip.estimated_duration !== '-') {
                travelEl.textContent = activeTrip.estimated_duration;
            } else if (activeTrip.time && activeTrip.eta) {
                travelEl.textContent = `${activeTrip.time} - ${activeTrip.eta}`;
            } else {
                travelEl.textContent = journeyData.travelTimes[journeyData.currentStopIndex] || '-';
            }

            // Jarak: 使用 distance dari trip data
            const distEl = document.getElementById('distance');
            if (activeTrip.distance) {
                distEl.textContent = typeof activeTrip.distance === 'number' ? `${activeTrip.distance} km` : activeTrip.distance;
            } else {
                distEl.textContent = journeyData.distances[journeyData.currentStopIndex] || '-';
            }

            // Penumpang: 使用 occupied_seats dan total_seats
            const passengerEl = document.getElementById('passengerCount');
            if (passengerEl) {
                passengerEl.textContent = `${activeTrip.occupied_seats || 0}/${activeTrip.total_seats || 0}`;
            }

            // Total Penumpang di section bawah
            const totalPenumpangEl = document.getElementById('totalPenumpang');
            if (totalPenumpangEl) {
                totalPenumpangEl.textContent = activeTrip.passengers ? activeTrip.passengers.length : 0;
            }
        } else {
            // Fallback jika tidak ada data
            document.getElementById('travelTime').textContent = journeyData.travelTimes[journeyData.currentStopIndex] || '-';
            document.getElementById('distance').textContent = journeyData.distances[journeyData.currentStopIndex] || '-';
        }

        // Update titik selanjutnya
        if (!isLastStop) {
            document.getElementById('nextStop').textContent = nextStop.name;
        } else {
            document.getElementById('nextStop').textContent = "Tujuan Akhir";
        }

        // Build progress nodes based on outlets sequence
        const progressContainer = document.querySelector('.progress-stops');
        if (progressContainer) {
            progressContainer.innerHTML = '';

            // ★★★ PERBAIKAN: Struktur sudah per-outlet, jadi cukup loop stops sekali saja ★★★
            const progressNodes = [];
            if (journeyData.stops.length > 0) {
                journeyData.stops.forEach((stop, idx) => {
                    // Setiap stop sudah berupa outlet individual, tidak perlu nested loop
                    progressNodes.push({
                        label: stop.name,
                        type: stop.type,
                        is_outlet: stop.is_outlet
                    });
                });
            }

            // Render progress stops
            progressNodes.forEach((node, pIdx) => {
                const div = document.createElement('div');
                div.className = 'progress-stop';
                div.textContent = node.label;
                if (pIdx === journeyData.currentStopIndex) {
                    div.classList.add('active');
                } else if (pIdx < journeyData.currentStopIndex) {
                    div.classList.add('completed');
                }
                progressContainer.appendChild(div);
            });

            // Update progress bar width
            const progressPercent = (journeyData.currentStopIndex / Math.max(1, (progressNodes.length - 1))) * 100;
            document.getElementById('progressFill').style.width = `${progressPercent}%`;
        }

        // Update status titik pemberhentian
        updateStopPoints();

        // Tampilkan/sembunyikan tombol selesaikan perjalanan
        const finishButton = document.getElementById('selesaiPerjalananBtn');
        if (isLastStop) {
            finishButton.classList.remove('hidden');
        } else {
            finishButton.classList.add('hidden');
        }

        // Update modal
        if (!isLastStop) {
            document.getElementById('modalNextLocation').textContent = `Menuju: ${nextStop.name}`;
        }
    }

    // Fungsi untuk menampilkan titik pemberhentian di halaman detail
    function updateStopPoints() {
        const stopPointsContainer = document.getElementById('stopPoints');
        stopPointsContainer.innerHTML = '';

        // Hanya tampilkan titik pemberhentian yang sudah dilewati dan yang sedang aktif
        for (let i = 1; i < journeyData.stops.length - 1; i++) {
            const stop = journeyData.stops[i];
            if (i <= journeyData.currentStopIndex) {
                const stopElement = document.createElement('div');
                stopElement.className = 'location';
                // Build detail as separate outlets if available
                let detailHtmlPassed = '';
                if (stop.outlets && stop.outlets.length > 0) {
                    if (stop.detail) detailHtmlPassed += `<strong>${stop.detail}</strong>`;
                    detailHtmlPassed += '<div style="font-size:13px; color:#888; margin-top:6px;">';
                    stop.outlets.forEach(o => {
                        detailHtmlPassed += `<div style="margin-bottom:6px;"><strong>${o.nama_outlet}</strong>`;
                        if (o.alamat) detailHtmlPassed += `<div style="font-size:11px; color:#888;">${o.alamat}</div>`;
                        detailHtmlPassed += `</div>`;
                    });
                    detailHtmlPassed += '</div>';
                } else {
                    detailHtmlPassed = stop.detail || '';
                }

                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span style="text-decoration: line-through; color: #888;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #888;">${detailHtmlPassed} (Telah dilewati)</div>
                `;
                stopPointsContainer.appendChild(stopElement);

                // Tambahkan garis pemisah
                if (i < journeyData.stops.length - 2) {
                    const line = document.createElement('div');
                    line.className = 'line';
                    stopPointsContainer.appendChild(line);
                }
            } else if (i === journeyData.currentStopIndex + 1) {
                const stopElement = document.createElement('div');
                stopElement.className = 'location';
                let detailHtmlNext = '';
                if (stop.outlets && stop.outlets.length > 0) {
                    if (stop.detail) detailHtmlNext += `<strong>${stop.detail}</strong>`;
                    detailHtmlNext += '<div style="font-size:13px; color:#0095FF; margin-top:6px;">';
                    stop.outlets.forEach(o => {
                        detailHtmlNext += `<div style="margin-bottom:6px;"><strong>${o.nama_outlet}</strong>`;
                        if (o.alamat) detailHtmlNext += `<div style="font-size:11px; color:#0095FF;">${o.alamat}</div>`;
                        detailHtmlNext += `</div>`;
                    });
                    detailHtmlNext += '</div>';
                } else {
                    detailHtmlNext = stop.detail || '';
                }

                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span style="color: #0095FF;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #0095FF;">${detailHtmlNext} (Titik Selanjutnya)</div>
                `;
                stopPointsContainer.appendChild(stopElement);

                // Tambahkan garis pemisah
                if (i < journeyData.stops.length - 2) {
                    const line = document.createElement('div');
                    line.className = 'line';
                    stopPointsContainer.appendChild(line);
                }
            }
        }

        // Update progress stops
        const progressStops = document.querySelectorAll('.progress-stop');
        progressStops.forEach((stop, index) => {
            stop.classList.remove('active', 'completed');
            if (index === journeyData.currentStopIndex) {
                stop.classList.add('active');
            } else if (index < journeyData.currentStopIndex) {
                stop.classList.add('completed');
            }
        });
    }

    // ★★★ FUNGSI UNTUK MEMULAI PERJALANAN ★★★
    function mulaiPerjalanan() {
        if (!currentTripId) {
            alert('Tidak ada perjalanan yang dipilih!');
            return;
        }
        // Optimistic UI update while persisting start to server
        const startBtn = document.getElementById('mulaiPerjalananBtn');
        const updateBtn = document.getElementById('updateLokasiBtn');
        const statusElement = document.getElementById(`status-${currentTripId}`);

        // Temporarily update UI
        journeyStarted[currentTripId] = true;
        if (startBtn) startBtn.classList.add('hidden');
        if (updateBtn) updateBtn.classList.remove('hidden');
        if (statusElement) {
            statusElement.textContent = 'Dalam Perjalanan';
            statusElement.className = 'status';
        }

        // Prepare payload
        const payload = {
            id_jadwal_driver: parseInt(currentTripId),
            total_stops: journeyData && journeyData.stops ? journeyData.stops.length : 0
        };

        // Get CSRF token (try meta, input, or window.Laravel)
        let csrfToken = null;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) csrfToken = csrfMeta.getAttribute('content');
        if (!csrfToken) {
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) csrfToken = csrfInput.value;
        }
        if (!csrfToken && window.Laravel && window.Laravel.csrfToken) csrfToken = window.Laravel.csrfToken;

        if (!csrfToken) {
            console.error('CSRF token not found when starting journey');
            alert('Terjadi kesalahan keamanan. Silakan refresh halaman dan coba lagi.');
            // revert optimistic UI
            journeyStarted[currentTripId] = false;
            if (startBtn) startBtn.classList.remove('hidden');
            if (updateBtn) updateBtn.classList.add('hidden');
            if (statusElement) {
                statusElement.textContent = 'Menunggu';
                statusElement.className = '';
            }
            return;
        }

        fetch('{{ route("api.driver.journey.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(result => {
            if (result && result.success) {
                console.log('Journey started persisted:', result.data);
                alert('Perjalanan dimulai! Anda sekarang bisa mengupdate lokasi.');
            } else {
                console.error('Failed to persist journey start', result);
                alert('Gagal memulai perjalanan di server. Coba lagi.');
                // revert optimistic UI
                journeyStarted[currentTripId] = false;
                if (startBtn) startBtn.classList.remove('hidden');
                if (updateBtn) updateBtn.classList.add('hidden');
                if (statusElement) {
                    statusElement.textContent = 'Menunggu';
                    statusElement.className = '';
                }
            }
        })
        .catch(err => {
            console.error('Error starting journey:', err);
            alert('Terjadi kesalahan saat memulai perjalanan. Periksa koneksi Anda.');
            // revert optimistic UI
            journeyStarted[currentTripId] = false;
            if (startBtn) startBtn.classList.remove('hidden');
            if (updateBtn) updateBtn.classList.add('hidden');
            if (statusElement) {
                statusElement.textContent = 'Menunggu';
                statusElement.className = '';
            }
        });
    }

    // Fungsi untuk menampilkan modal update lokasi
    function showUpdateLokasiModal() {
        // ★★★ CEK APAKAH PERJALANAN SUDAH DIMULAI ★★★
        if (!journeyStarted[currentTripId]) {
            alert('Anda harus memulai perjalanan terlebih dahulu!');
            return;
        }

        // Cek apakah sudah sampai tujuan akhir
        if (journeyData.currentStopIndex >= journeyData.stops.length - 1) {
            alert('Anda sudah sampai di tujuan akhir!');
            return;
        }

        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        document.getElementById('modalNextLocation').textContent = `Menuju: ${nextStop.name}`;

        // ★★★ PERBAIKAN: Setiap stop sekarang adalah per-OUTLET, bukan per-branch ★★★
        // Jadi kita tampilkan detail outlet dari stop saat ini
        const outletsInfo = document.getElementById('modalOutletsInfo');
        const outletsList = document.getElementById('modalOutletsList');

        if (nextStop.is_outlet && nextStop.outlet_detail) {
            // Tampilkan informasi outlet yang akan dituju
            outletsInfo.style.display = 'block';
            outletsList.innerHTML = '';

            const outletItem = document.createElement('div');
            outletItem.style.cssText = 'margin-bottom: 10px; padding: 12px; background: white; border-radius: 4px; border-left: 4px solid #22c55e;';
            outletItem.innerHTML = `
                <strong style="color: #22c55e;">📍 ${nextStop.outlet_detail.nama_outlet}</strong><br>
                <small style="color: #666; display: block; margin-top: 5px;">
                    ${nextStop.outlet_detail.alamat || 'Alamat tidak tersedia'}
                </small>
                <small style="color: #999; display: block; margin-top: 3px;">
                    Kota: ${nextStop.kota || nextStop.outlet_detail.kota || '-'}
                </small>
            `;
            outletsList.appendChild(outletItem);

            // Log untuk debugging
            console.log('✅ Menampilkan outlet untuk update:', {
                outlet_name: nextStop.outlet_detail.nama_outlet,
                stop_index: journeyData.currentStopIndex + 1,
                branch: nextStop.detail
            });
        } else {
            // Fallback jika tidak ada outlet detail
            outletsInfo.style.display = 'none';
        }

        const modal = document.getElementById('updateLokasiModal');
        modal.style.display = 'flex';
    }

    // Fungsi untuk menyembunyikan modal update lokasi
    function hideUpdateLokasiModal() {
        const modal = document.getElementById('updateLokasiModal');
        modal.style.display = 'none';
    }

    // Fungsi untuk mengkonfirmasi update lokasi
    function confirmUpdateLokasi() {
        console.log('🔄 confirmUpdateLokasi dipanggil...');

        // ★★★ CEK APAKAH PERJALANAN SUDAH DIMULAI ★★★
        if (!journeyStarted[currentTripId]) {
            console.warn('⚠️ Perjalanan belum dimulai untuk trip:', currentTripId);
            alert('Anda harus memulai perjalanan terlebih dahulu!');
            hideUpdateLokasiModal();
            return;
        }

        // Kirim update ke server via API
        if (!currentTripId) {
            console.error('❌ currentTripId kosong');
            alert('Tidak ada perjalanan aktif yang dipilih.');
            return;
        }

        // Tentukan titik berikutnya
        const nextIndex = journeyData.currentStopIndex + 1;
        if (nextIndex >= journeyData.stops.length) {
            console.log('✅ Sudah mencapai tujuan akhir');
            alert('Sudah mencapai tujuan akhir.');
            hideUpdateLokasiModal();
            return;
        }

        const nextStop = journeyData.stops[nextIndex];
        const status = nextStop.type === 'finish' ? 'completed' : 'arrived';

        // ★★★ PERBAIKAN: Kirim informasi outlet dan stop_index yang akurat ★★★
        const payload = {
            id_jadwal_driver: parseInt(currentTripId),
            location_name: nextStop.name,  // Nama outlet
            location_detail: nextStop.detail || '',  // Branch name + durasi
            latitude: null,
            longitude: null,
            stop_index: nextIndex,  // Ini sekarang adalah per-outlet, bukan per-branch
            status: status,
            // ★★★ TAMBAHKAN INFO TAMBAHAN UNTUK TRACKING ★★★
            outlet_id: nextStop.outlet_id || null,
            kota: nextStop.kota || null,
            branch_id: nextStop.branch_id || null
        };

        console.log('📤 Mengirim update lokasi:', {
            outlet: nextStop.name,
            stop_index: nextIndex,
            type: nextStop.type,
            is_outlet: nextStop.is_outlet
        });

        // ★★★ AMBIL CSRF TOKEN DENGAN ERROR HANDLING ★★★
        let csrfToken = null;

        // Cara 1: Dari meta tag
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            csrfToken = csrfMeta.getAttribute('content');
        }

        // Cara 2: Dari input hidden field jika meta tidak ada
        if (!csrfToken) {
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) {
                csrfToken = csrfInput.value;
            }
        }

        // Cara 3: Dari window object jika tersedia
        if (!csrfToken && window.Laravel && window.Laravel.csrfToken) {
            csrfToken = window.Laravel.csrfToken;
        }

        // Jika masih tidak ada CSRF token, tampilkan error
        if (!csrfToken) {
            console.error('❌ CSRF Token tidak ditemukan!');
            alert('Terjadi kesalahan keamanan. Silakan refresh halaman dan coba lagi.');
            hideUpdateLokasiModal();
            return;
        }

        fetch('{{ route("api.driver.location.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        }).then(r => r.json())
        .then(result => {
            if (result && result.success) {
                // Perbarui state lokal
                journeyData.currentStopIndex = nextIndex;
                updateJourneyDisplay();

                // Update status pada daftar perjalanan jika tersedia
                if (currentTripId) {
                    const statusElement = document.getElementById(`status-${currentTripId}`);
                    if (statusElement) {
                        if (status === 'completed') {
                            statusElement.textContent = 'Selesai';
                            statusElement.className = 'status-selesai';
                        } else {
                            statusElement.textContent = 'Dalam Perjalanan';
                            statusElement.className = 'status';
                        }
                    }
                }

                hideUpdateLokasiModal();
                console.log('✅ Update lokasi berhasil: ' + nextStop.name);

                // ★★★ PERBAIKAN: OTOMATIS SELESAIKAN PERJALANAN JIKA SAMPAI OUTLET AKHIR ★★★
                if (status === 'completed' || nextStop.type === 'finish') {
                    console.log('🎉 Perjalanan Selesai! Outlet tujuan tercapai:', nextStop.name);

                    // Update UI untuk status selesai
                    const detailIcon = document.querySelector('.card-title-detail i');
                    if (detailIcon) {
                        detailIcon.style.color = "#2d572c";
                        detailIcon.className = "fa-solid fa-circle-check";
                    }

                    document.getElementById('tripTitle').textContent = "✅ Perjalanan Selesai";

                    // Hide Update Lokasi button
                    const updateBtn = document.getElementById('updateLokasiBtn');
                    if (updateBtn) updateBtn.classList.add('hidden');

                    // Hide Mulai Perjalanan button
                    const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
                    if (mulaiBtn) mulaiBtn.classList.add('hidden');

                    // Hide Selesaikan Perjalanan button
                    const selesaiBtn = document.getElementById('selesaiPerjalananBtn');
                    if (selesaiBtn) selesaiBtn.classList.add('hidden');

                    alert(`✅ Selamat! Perjalanan selesai. Anda telah sampai di ${nextStop.name}`);
                } else {
                    alert(`Lokasi berhasil diupdate! Sekarang di: ${nextStop.name}`);
                }
            } else {
                console.error('Update location failed', result);
                alert('Gagal mengupdate lokasi. Silakan coba lagi.');
            }
        }).catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat mengirim data lokasi.');
        });
    }

    // Fungsi untuk menyelesaikan perjalanan
    function selesaikanPerjalanan() {
        if (journeyData.currentStopIndex === journeyData.stops.length - 1) {
            if (confirm('Apakah Anda yakin ingin menyelesaikan perjalanan?')) {
                console.log('🎉 Selesaikan perjalanan ID:', currentTripId);

                // Ambil CSRF token
                let csrfToken = null;
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    csrfToken = csrfMeta.getAttribute('content');
                }

                if (!csrfToken) {
                    const csrfInput = document.querySelector('input[name="_token"]');
                    if (csrfInput) {
                        csrfToken = csrfInput.value;
                    }
                }

                if (!csrfToken) {
                    alert('Terjadi kesalahan keamanan. Silakan refresh halaman dan coba lagi.');
                    return;
                }

                // Kirim request ke server untuk update status
                fetch('{{ route("api.driver.trip.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id_jadwal_driver: parseInt(currentTripId)
                    })
                })
                .then(r => r.json())
                .then(result => {
                    if (result && result.success) {
                        console.log('✅ Perjalanan berhasil diselesaikan di database');

                        // Update status di halaman detail
                        document.querySelector('.card-title-detail i').style.color = "#2d572c";
                        document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-check";
                        document.getElementById('tripTitle').textContent = "✅ Perjalanan Selesai";

                        // Update status di halaman daftar perjalanan
                        if (currentTripId) {
                            const statusElement = document.getElementById(`status-${currentTripId}`);
                            if (statusElement) {
                                statusElement.textContent = "Selesai";
                                statusElement.className = "status-selesai";
                            }
                        }

                        // Sembunyikan tombol
                        document.getElementById('selesaiPerjalananBtn').classList.add('hidden');
                        const updateBtn = document.getElementById('updateLokasiBtn');
                        if (updateBtn) updateBtn.classList.add('hidden');
                        const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
                        if (mulaiBtn) mulaiBtn.classList.add('hidden');

                        alert('✅ Perjalanan telah diselesaikan! Status telah disimpan ke database.');
                    } else {
                        console.error('Gagal menyelesaikan perjalanan:', result);
                        alert('Gagal menyelesaikan perjalanan. Silakan coba lagi.');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi kesalahan saat menghubungi server.');
                });
            }
        } else {
            alert('Anda belum mencapai tujuan akhir. Lanjutkan update lokasi hingga tujuan.');
        }
    }

    // ★★★ FUNGSI UNTUK ALIGN KURSI DARI OUTLETS PEMBERHENTIAN ★★★
    function alignSeatsFromStopPoints() {
        // Jika ada stop points dengan outlets, kita bisa align kursi di sini
        // Untuk sekarang, kita akan display stops dengan outlet info
        if (journeyData.stops && journeyData.stops.length > 0) {
            journeyData.stops.forEach((stop, index) => {
                if (stop.outlets && stop.outlets.length > 0) {
                    // Outlets sudah tersedia dari stop points
                    console.log(`Stop ${index}: ${stop.name} - Outlets: ${stop.outlets.map(o => o.nama_outlet).join(', ')}`);
                }
            });
        }
    }

    // ★★★ VARIABLE GLOBAL UNTUK AUTO-REFRESH DATA PENUMPANG ★★★
    let passengerRefreshInterval = null;
    let lastPassengerCount = 0; // Track perubahan jumlah penumpang

    // ★★★ FUNGSI UNTUK FETCH DATA PENUMPANG REAL-TIME DARI API ★★★
    async function fetchPassengersRealtime(tripId) {
        try {
            const route = "{{ route('driver.api.passengers.realtime', ':tripId') }}".replace(':tripId', tripId);
            const response = await fetch(route, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (!response.ok) {
                console.warn(`❌ Gagal fetch data penumpang real-time (Status: ${response.status})`);
                return null;
            }

            const result = await response.json();

            if (result.success && result.data) {
                console.log('✅ Data penumpang real-time berhasil di-fetch:', {
                    total_passengers: result.data.total_passengers,
                    occupied_seats: result.data.occupied_seats,
                    timestamp: result.data.timestamp
                });

                // Check jika ada perubahan jumlah penumpang
                if (result.data.total_passengers !== lastPassengerCount) {
                    console.log(`📊 Ada perubahan jumlah penumpang! ${lastPassengerCount} → ${result.data.total_passengers}`);
                    lastPassengerCount = result.data.total_passengers;
                }

                return result.data;
            } else {
                console.warn('❌ Response tidak valid:', result);
                return null;
            }
        } catch (error) {
            console.error('❌ Error fetching passengers realtime:', error);
            return null;
        }
    }

    // ★★★ FUNGSI UNTUK UPDATE PENUMPANG DARI DATA REAL-TIME ★★★
    function updatePassengersFromRealtime(passengersData) {
        if (!passengersData || !passengersData.passengers) {
            console.warn('⚠️ Data penumpang tidak valid');
            return;
        }

        const penumpangListElement = document.getElementById('penumpangList');
        const totalPenumpangElement = document.getElementById('totalPenumpang');
        const passengerCountEl = document.getElementById('passengerCount');

        if (!penumpangListElement) return;

        // Bersihkan list lama
        penumpangListElement.innerHTML = '';

        // Update total penumpang
        if (totalPenumpangElement) {
            totalPenumpangElement.textContent = passengersData.total_passengers;
        }

        // Update info penumpang di header
        if (passengerCountEl) {
            passengerCountEl.textContent = `${passengersData.occupied_seats}/${passengersData.total_seats}`;
        }

        // Render setiap penumpang
        passengersData.passengers.forEach(passenger => {
            let statusClass = 'status-terverifikasi';
            let statusText = 'Terverifikasi';

            // Tentukan kelas dan teks status
            switch((passenger.status || '').toLowerCase()) {
                case 'refund':
                    statusClass = 'status-refund';
                    statusText = 'Refund';
                    break;
                case 'terdaftar':
                    statusClass = 'status-terdaftar';
                    statusText = 'Terdaftar';
                    break;
                case 'terverifikasi':
                    statusClass = 'status-terverifikasi';
                    statusText = 'Terverifikasi';
                    break;
                default:
                    statusClass = 'status-terdaftar';
                    statusText = 'Terdaftar';
            }

            // Buat elemen penumpang
            const penumpangItem = document.createElement('div');
            penumpangItem.className = 'penumpang-item';
            penumpangItem.innerHTML = `
                <div class="penumpang-info">
                    <div class="penumpang-name">${passenger.name}</div>
                    <div class="penumpang-phone">${passenger.phone || ''}</div>
                </div>
                <div class="penumpang-seat">
                    <div class="seat-number">${passenger.seat || 'N/A'}</div>
                    <span class="seat-status ${statusClass}">${statusText}</span>
                </div>
            `;

            penumpangListElement.appendChild(penumpangItem);
        });

        console.log(`✅ Penumpang diupdate: ${passengersData.total_passengers} penumpang`);
    }

    // ★★★ FUNGSI UNTUK SETUP AUTO-REFRESH DATA PENUMPANG ★★★
    function setupPassengerAutoRefresh(tripId) {
        // Clear interval lama jika ada
        if (passengerRefreshInterval) {
            clearInterval(passengerRefreshInterval);
        }

        // Initial load
        fetchPassengersRealtime(tripId).then(data => {
            if (data) {
                updatePassengersFromRealtime(data);
            }
        });

        // Setup auto-refresh setiap 5 detik
        passengerRefreshInterval = setInterval(async () => {
            const data = await fetchPassengersRealtime(tripId);
            if (data) {
                updatePassengersFromRealtime(data);
            }
        }, 5000); // Refresh setiap 5 detik

        console.log('✅ Auto-refresh data penumpang diaktifkan (update setiap 5 detik)');
    }

    // Fungsi untuk menampilkan daftar penumpang berdasarkan ID perjalanan
    function generatePenumpangList(tripId) {
        // ★★★ PERBAIKAN: Gunakan real-time data dari API daripada dari initial data ★★★
        // Setup auto-refresh dan fetch data real-time
        setupPassengerAutoRefresh(tripId);
    }

    // Fungsi untuk mengambil state perjalanan dari server dan mengembalikan promise
    function fetchJourneyState(tripId) {
        return new Promise((resolve, reject) => {
            if (!tripId) return resolve(null);

            // Ambil CSRF token jika diperlukan (GET biasanya tidak perlu, tapi keep for consistency)
            let url = `{{ url('/api/driver/journey') }}`;
            // Use named route URL
            url = '{{ route("api.driver.journey.state", ["tripId" => "__TRIPID__"]) }}'.replace('__TRIPID__', tripId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(r => r.json())
            .then(result => {
                if (result && result.success) {
                    resolve(result.data);
                } else {
                    resolve(null);
                }
            }).catch(err => {
                console.error('Error fetching journey state:', err);
                resolve(null);
            });
        });
    }

    // Fungsi untuk menampilkan halaman detail perjalanan
    function showDetailPerjalanan(tripData) {
        // Sembunyikan halaman daftar
        document.getElementById('daftarPerjalananPage').classList.add('hidden');

        // Tampilkan halaman detail
        document.getElementById('detailPerjalananPage').classList.remove('hidden');

        // Simpan ID perjalanan yang sedang aktif
        currentTripId = tripData.id;

        // Update judul perjalanan
        document.getElementById('tripTitle').textContent = `Perjalanan #${tripData.id} - ${tripData.from} → ${tripData.to}`;

        // Update info penumpang sesuai data dari halaman daftar
        document.getElementById('passengerCount').textContent = tripData.seats;

        // ★★★ CARI FULL TRIP DATA DARI TRIPS DATA ★★★
        const fullTripData = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(tripData.id));

        // Jika server menyediakan data lengkap, gunakan nilai tersebut untuk info utama
        if (fullTripData) {
            // ★★★ PENUMPANG: Dari kursi yang terisi di jadwal ★★★
            const passengerCountEl = document.getElementById('passengerCount');
            if (passengerCountEl) {
                passengerCountEl.textContent = `${fullTripData.occupied_seats || 0}/${fullTripData.total_seats || 0}`;
            }

            // ★★★ WAKTU TEMPUH: Dari waktu berangkat sampai waktu sampat ★★★
            const travelEl = document.getElementById('travelTime');
            if (travelEl) {
                let travelText = '-';
                if (fullTripData.time && fullTripData.eta) {
                    // Format: HH:MM - HH:MM (durasi)
                    travelText = `${fullTripData.time} - ${fullTripData.eta}`;
                    if (fullTripData.estimated_duration && fullTripData.estimated_duration !== '-') {
                        travelText += ` (${fullTripData.estimated_duration})`;
                    }
                } else if (fullTripData.estimated_duration && fullTripData.estimated_duration !== '-') {
                    travelText = fullTripData.estimated_duration;
                }
                travelEl.textContent = travelText;
            }

            // ★★★ JARAK: Dari data rute yang ada di jadwal ★★★
            const distEl = document.getElementById('distance');
            if (distEl && fullTripData.distance) {
                const distanceText = (typeof fullTripData.distance === 'number') ? `${fullTripData.distance} km` : fullTripData.distance;
                distEl.textContent = distanceText;
            }
        }

        // ★★★ BANGUN JOURNEY DATA DARI STOP POINTS ★★★
        buildJourneyDataFromStopPoints({
            from: tripData.from,
            to: tripData.to,
            stop_points: fullTripData ? fullTripData.stop_points : [],
            id_jadwal_driver: tripData.id
        });

        // ★★★ CEK DATA OUTLETS (LOG KE CONSOLE) ★★★
        if (fullTripData && fullTripData.stop_points) {
            debugOutletsData(fullTripData);
            validateOutletsCompleteness();
        }

        // Reset data perjalanan untuk perjalanan baru
        journeyData.currentStopIndex = 0;

        // ★★★ RESET JOURNEY START STATE (default) ★★★
        journeyStarted[tripData.id] = false;

        // Try to restore journey state from server (in case page was reloaded)
        fetchJourneyState(tripData.id).then(state => {
            if (state) {
                // state can be an object or model; normalize
                const status = state.status || (state.data && state.data.status) || 'not_started';
                const idx = state.current_stop_index ?? state.data?.current_stop_index ?? 0;
                journeyStarted[tripData.id] = (status === 'in_progress');
                journeyData.currentStopIndex = parseInt(idx) || 0;
                // Update UI accordingly
                updateJourneyDisplay();
            } else {
                updateJourneyDisplay();
            }
        }).catch(() => {
            updateJourneyDisplay();
        });

        // Generate daftar penumpang berdasarkan ID perjalanan
        generatePenumpangList(parseInt(tripData.id));

        // Reset button visibility based on journey state
        const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
        const updateBtn = document.getElementById('updateLokasiBtn');

        // ★★★ CEK STATUS DARI SERVER DATA, BUKAN HANYA DARI DOM ELEMENT ★★★
        let tripStatus = 'aktif'; // Default status
        if (fullTripData && fullTripData.status) {
            tripStatus = fullTripData.status;
        } else {
            // Fallback: cek dari DOM element jika server data tidak ada
            const statusElement = document.getElementById(`status-${tripData.id}`);
            if (statusElement && statusElement.textContent === "Selesai") {
                tripStatus = 'selesai';
            }
        }

        // Update UI berdasarkan status dari database
        if (tripStatus === 'selesai') {
            // Jika perjalanan sudah selesai
            document.querySelector('.card-title-detail i').style.color = "#2d572c";
            document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-check";
            document.getElementById('tripTitle').textContent = "✅ Perjalanan Selesai";
            document.getElementById('selesaiPerjalananBtn').classList.add('hidden');
            if (mulaiBtn) mulaiBtn.classList.add('hidden');
            if (updateBtn) updateBtn.classList.add('hidden');
        } else {
            // Jika perjalanan masih aktif
            document.querySelector('.card-title-detail i').style.color = "#36B35A";
            document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-play";
            document.getElementById('tripTitle').textContent = `Perjalanan #${tripData.id} - ${tripData.from} → ${tripData.to}`;

            // Show "Mulai Perjalanan" if not started yet
            if (journeyStarted[tripData.id]) {
                // Journey already started
                if (mulaiBtn) mulaiBtn.classList.add('hidden');
                if (updateBtn) updateBtn.classList.remove('hidden');
            } else {
                // Journey not started yet
                if (mulaiBtn) mulaiBtn.classList.remove('hidden');
                if (updateBtn) updateBtn.classList.add('hidden');
            }
        }

        // Scroll ke atas
        window.scrollTo(0, 0);
    }

    // Fungsi untuk kembali ke halaman daftar perjalanan
    function backToDaftarPerjalanan() {
        // ★★★ CLEANUP: Hentikan auto-refresh data penumpang ★★★
        if (passengerRefreshInterval) {
            clearInterval(passengerRefreshInterval);
            passengerRefreshInterval = null;
            console.log('✅ Auto-refresh data penumpang dimatikan');
        }

        // Sembunyikan halaman detail
        document.getElementById('detailPerjalananPage').classList.add('hidden');

        // Tampilkan halaman daftar
        document.getElementById('daftarPerjalananPage').classList.remove('hidden');

        // Scroll ke atas
        window.scrollTo(0, 0);
    }

    // ★★★ FUNGSI UNTUK RENDER RIWAYAT PERJALANAN (HANYA YANG SELESAI) ★★★
    function renderCompletedTripsHistory() {
        const container = document.getElementById('historyItemsContainer');
        if (!container) return;

        container.innerHTML = '';

        if (!completedTrips || completedTrips.length === 0) {
            container.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">Belum ada riwayat perjalanan</div>';
            return;
        }

        // ★★★ FILTER: Ambil hanya perjalanan dengan status 'selesai' ★★★
        const finishedTrips = completedTrips.filter(trip => trip.status === 'selesai' || trip.status === 'completed');

        if (finishedTrips.length === 0) {
            container.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">Belum ada riwayat perjalanan yang selesai</div>';
            return;
        }

        // ★★★ RENDER SETIAP COMPLETED TRIP ★★★
        finishedTrips.forEach(trip => {
            const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
            const formattedDate = tripDate ? tripDate.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'N/A';

            const time = trip.waktu_keberangkatan || trip.time || '-';
            const from = trip.asal || trip.from || trip.jadwal?.asal || trip.masterRute?.kota_asal || '-';
            const to = trip.tujuan || trip.to || trip.jadwal?.tujuan || trip.masterRute?.kota_tujuan || '-';

            // Hitung jumlah penumpang dari bookings jika tersedia
            let passengerCount = 0;
            if (trip.bookings && Array.isArray(trip.bookings)) {
                trip.bookings.forEach(booking => {
                    const details = booking.detail_penumpang || booking.detailPenumpang || [];
                    if (Array.isArray(details)) {
                        passengerCount += details.length;
                    } else if (typeof details === 'object') {
                        passengerCount += Object.keys(details).length;
                    }
                });
            }
            // Fallback: gunakan occupied_seats jika tersedia
            if (passengerCount === 0 && trip.occupied_seats) {
                passengerCount = trip.occupied_seats;
            }

            const displayRoute = trip.route_name || (trip.rute && trip.rute.nama_rute) || (trip.jadwal && trip.jadwal.rutes && trip.jadwal.rutes[0] && trip.jadwal.rutes[0].nama_rute) || `${from} → ${to}`;

            const item = document.createElement('div');
            item.className = 'history-item';
            item.innerHTML = `
                <div class="history-route">${displayRoute}</div>
                <div class="history-date">${formattedDate} | ${time}</div>
                <div class="history-footer">
                    <div class="passenger-count">${passengerCount} penumpang</div>
                    <span class="status-completed">Selesai</span>
                </div>
            `;

            container.appendChild(item);
        });
    }

    // ★★★ FILTER RIWAYAT BERDASARKAN PERIODE ★★★
    function filterCompletedTripsHistory(filterValue) {
        const container = document.getElementById('historyItemsContainer');
        if (!container) return;

        container.innerHTML = '';

        if (!completedTrips || completedTrips.length === 0) {
            container.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">Belum ada riwayat perjalanan</div>';
            return;
        }

        // Filter trips yang selesai
        let filteredTrips = completedTrips.filter(trip => trip.status === 'selesai' || trip.status === 'completed');

        // Apply date filter
        if (filterValue && filterValue !== 'Semua') {
            const now = new Date();
            filteredTrips = filteredTrips.filter(trip => {
                const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
                if (!tripDate) return false;

                switch (filterValue) {
                    case 'Minggu ini':
                        const weekStart = new Date(now);
                        weekStart.setDate(now.getDate() - now.getDay());
                        return tripDate >= weekStart && tripDate <= now;
                    case 'Bulan ini':
                        return tripDate.getMonth() === now.getMonth() && tripDate.getFullYear() === now.getFullYear();
                    case '3 bulan terakhir':
                        const threeMonthsAgo = new Date(now);
                        threeMonthsAgo.setMonth(now.getMonth() - 3);
                        return tripDate >= threeMonthsAgo && tripDate <= now;
                    default:
                        return true;
                }
            });
        }

        if (filteredTrips.length === 0) {
            container.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">Tidak ada riwayat perjalanan dalam periode yang dipilih</div>';
            return;
        }

        // Render filtered trips
        filteredTrips.forEach(trip => {
            const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
            const formattedDate = tripDate ? tripDate.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'N/A';

            const time = trip.waktu_keberangkatan || trip.time || '-';
            const from = trip.asal || trip.from || trip.jadwal?.asal || trip.masterRute?.kota_asal || '-';
            const to = trip.tujuan || trip.to || trip.jadwal?.tujuan || trip.masterRute?.kota_tujuan || '-';

            // Hitung jumlah penumpang
            let passengerCount = 0;
            if (trip.bookings && Array.isArray(trip.bookings)) {
                trip.bookings.forEach(booking => {
                    const details = booking.detail_penumpang || booking.detailPenumpang || [];
                    if (Array.isArray(details)) {
                        passengerCount += details.length;
                    } else if (typeof details === 'object') {
                        passengerCount += Object.keys(details).length;
                    }
                });
            }
            if (passengerCount === 0 && trip.occupied_seats) {
                passengerCount = trip.occupied_seats;
            }

            const displayRoute = trip.route_name || (trip.rute && trip.rute.nama_rute) || (trip.jadwal && trip.jadwal.rutes && trip.jadwal.rutes[0] && trip.jadwal.rutes[0].nama_rute) || `${from} → ${to}`;

            const item = document.createElement('div');
            item.className = 'history-item';
            item.innerHTML = `
                <div class="history-route">${displayRoute}</div>
                <div class="history-date">${formattedDate} | ${time}</div>
                <div class="history-footer">
                    <div class="passenger-count">${passengerCount} penumpang</div>
                    <span class="status-completed">Selesai</span>
                </div>
            `;

            container.appendChild(item);
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenu();

        // ★★★ RENDER RIWAYAT PERJALANAN SAAT PAGE LOAD ★★★
        renderCompletedTripsHistory();

        // ★★★ ADD EVENT LISTENER UNTUK FILTER RIWAYAT ★★★
        const historyFilter = document.getElementById('historyFilterSelect');
        if (historyFilter) {
            historyFilter.addEventListener('change', function() {
                filterCompletedTripsHistory(this.value);
            });
        }

        // Render daftar perjalanan dari server data
        function renderTripList() {
            const containerCard = document.querySelector('#daftarPerjalananPage .card');
            if (!containerCard) return;

            const header = containerCard.querySelector('.card-header');
            const listContainer = document.createElement('div');
            listContainer.className = 'trip-list-container';

            if (!tripsData || tripsData.length === 0) {
                listContainer.innerHTML = '<div class="trip-item"><div class="trip-route">Tidak ada perjalanan</div></div>';
            } else {
                // ★★★ PERBAIKAN: PISAHKAN JADWAL AKTIF DAN SELESAI ★★★
                const activeTrips = tripsData.filter(t => t.status !== 'selesai');
                const completedDisplayTrips = tripsData.filter(t => t.status === 'selesai');

                // Gabungkan: aktif dulu, baru selesai
                const sortedTrips = [...activeTrips, ...completedDisplayTrips];

                console.log(`📊 Total jadwal: ${tripsData.length} (Aktif: ${activeTrips.length}, Selesai: ${completedDisplayTrips.length})`);

                sortedTrips.forEach((t, idx) => {
                    const tripId = t.id_jadwal_driver || '';
                    const from = t.from || t.rute?.kota_asal || t.from || '-';
                    const to = t.to || t.rute?.kota_tujuan || '-';
                    const seats = (t.occupied_seats || 0) + '/' + (t.total_seats || 0);
                    const passengers = (t.passengers || []).length || 0;
                    const time = t.time || t.waktu_keberangkatan || '-';
                    const eta = t.eta || t.waktu_kedatangan || '-';
                    const distance = t.distance ? (typeof t.distance === 'number' ? `${t.distance} km` : t.distance) : '-';

                    const statusText = t.status === 'selesai' ? 'Selesai' : (t.status || 'Akan Berangkat');

                    const item = document.createElement('div');
                    item.className = 'trip-item';
                    item.setAttribute('data-trip-id', tripId);
                    item.setAttribute('data-from', from);
                    item.setAttribute('data-to', to);
                    item.setAttribute('data-time', time);
                    item.setAttribute('data-eta', eta);
                    item.setAttribute('data-duration', t.estimated_duration || '-');
                    item.setAttribute('data-distance', distance);
                    item.setAttribute('data-seats', seats);
                    item.setAttribute('data-passengers', passengers);
                    item.setAttribute('data-status', t.status);
                    item.setAttribute('data-date', t.date);

                    // ★★★ TAMPILKAN: Waktu berangkat - waktu sampat (durasi) ★★★
                    const travelTimeDisplay = time && eta ? `${time} - ${eta}` : time || '-';
                    const durationDisplay = t.estimated_duration ? ` (${t.estimated_duration})` : '';

                    item.innerHTML = `
                        <div class="trip-header">
                            <div class="trip-number">${idx + 1}</div>
                            <div class="seat-info">${seats} kursi</div>
                        </div>

                        <div class="trip-route">${from} → ${to}</div>
                        <div class="trip-time">${travelTimeDisplay}${durationDisplay}</div>
                        <div style="font-size: 12px; color: #999; margin-top: 5px;">📅 ${t.date}</div>

                        <div class="status-badge">
                            <span class="status" id="status-${tripId}">${statusText}</span>
                        </div>

                        <div style="text-align:right;">
                            <button type="button" class="btn-detail">Lihat Detail</button>
                        </div>
                    `;

                    listContainer.appendChild(item);
                });
            }

            // Remove existing static trip items
            const existingItems = containerCard.querySelectorAll('.trip-item');
            existingItems.forEach(n => n.remove());

            // Append generated list
            header.insertAdjacentElement('afterend', listContainer);
        }

        // ★★★ SETUP EVENT LISTENER SEBELUM RENDER ★★★
        // Ini memastikan listener siap kapan saja tombol diklik
        // Gunakan setTimeout kecil untuk memastikan DOM fully ready
        setTimeout(() => {
            setupDetailButtonListener();
            renderTripList();
        }, 100);

        // ★★★ FUNCTION UNTUK SETUP EVENT LISTENER BUTTON DETAIL ★★★
        function setupDetailButtonListener() {
        try {
            console.log('%c⚙️ Setup Event Listener untuk Button Detail', 'color: purple; font-weight: bold; font-size: 12px;');

            // Gunakan capturing phase untuk memastikan event terdeteksi
            document.addEventListener('click', function(e) {
                // Check apakah yang diklik adalah button dengan tag BUTTON dan class .btn-detail
                const button = e.target.closest('button.btn-detail');

                if (button && button.closest('.trip-item')) {
                    console.log('%c🔘 BUTTON DETAIL DIKLIK!', 'color: #ff6a00; font-weight: bold; font-size: 13px;');
                    e.preventDefault();
                    e.stopPropagation();

                    try {
                        const tripItem = button.closest('.trip-item');

                        if (tripItem) {
                            console.log('✅ Trip item ditemukan');

                            // Ambil trip ID dari data attribute
                            const tripId = tripItem.getAttribute('data-trip-id');

                            if (!tripId) {
                                console.error('❌ data-trip-id tidak ditemukan!', tripItem.outerHTML.substring(0, 200));
                                return;
                            }

                            // Siapkan data dari attributes
                            const tripDataToShow = {
                                id: tripId,
                                from: tripItem.getAttribute('data-from') || 'N/A',
                                to: tripItem.getAttribute('data-to') || 'N/A',
                                time: tripItem.getAttribute('data-time') || 'N/A',
                                eta: tripItem.getAttribute('data-eta') || 'N/A',
                                duration: tripItem.getAttribute('data-duration') || 'N/A',
                                distance: tripItem.getAttribute('data-distance') || 'N/A',
                                seats: tripItem.getAttribute('data-seats') || 'N/A',
                                passengers: tripItem.getAttribute('data-passengers') || '0'
                            };

                            console.log('%cData ditampilkan:', 'color: green; font-weight: bold;', tripDataToShow);

                            // Panggil function
                            showDetailPerjalanan(tripDataToShow);
                        }
                    } catch (err) {
                        console.error('❌ Error:', err);
                    }
                }
            }, true); // Capturing phase

            console.log('%c✅ Event listener button detail ready', 'color: green; font-weight: bold;');
        } catch (error) {
            console.error('❌ Error di setupDetailButtonListener:', error);
        }
    }

    // ★★★ EVENT LISTENER UNTUK BUTTON LAINNYA ★★★
        document.addEventListener('click', function(e) {
            // Handle Mulai Perjalanan button
            if (e.target?.id === 'mulaiPerjalananBtn' || e.target?.closest('#mulaiPerjalananBtn')) {
                console.log('🔘 Tombol Mulai Perjalanan diklik');
                mulaiPerjalanan();
            }

            // Handle Update Lokasi button
            if (e.target?.id === 'updateLokasiBtn' || e.target?.closest('#updateLokasiBtn')) {
                console.log('🔘 Tombol Update Lokasi diklik');
                showUpdateLokasiModal();
            }

            // Handle Cancel button
            if (e.target?.id === 'cancelUpdateBtn' || e.target?.closest('#cancelUpdateBtn')) {
                console.log('🔘 Tombol Batal diklik');
                hideUpdateLokasiModal();
            }

            // Handle Confirm Update button - PENTING!
            if (e.target?.id === 'confirmUpdateBtn' || e.target?.closest('#confirmUpdateBtn')) {
                console.log('🔘 Tombol Update (Confirm) diklik');
                e.preventDefault();
                confirmUpdateLokasi();
            }

            // Handle Selesaikan Perjalanan button
            if (e.target?.id === 'selesaiPerjalananBtn' || e.target?.closest('#selesaiPerjalananBtn')) {
                console.log('🔘 Tombol Selesaikan Perjalanan diklik');
                selesaikanPerjalanan();
            }

            // Handle Back button
            if (e.target?.id === 'backButton' || e.target?.closest('#backButton')) {
                console.log('🔘 Tombol Kembali diklik');
                backToDaftarPerjalanan();
            }
        });

        // Fallback: Direct event listeners untuk kompatibilitas lebih baik
        const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
        if (mulaiBtn) {
            mulaiBtn.addEventListener('click', mulaiPerjalanan);
            console.log('✅ Direct listener untuk mulaiPerjalananBtn');
        }

        const updateBtn = document.getElementById('updateLokasiBtn');
        if (updateBtn) {
            updateBtn.addEventListener('click', showUpdateLokasiModal);
            console.log('✅ Direct listener untuk updateLokasiBtn');
        }

        const cancelBtn = document.getElementById('cancelUpdateBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', hideUpdateLokasiModal);
            console.log('✅ Direct listener untuk cancelUpdateBtn');
        }

        const confirmBtn = document.getElementById('confirmUpdateBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', confirmUpdateLokasi);
            console.log('✅ Direct listener untuk confirmUpdateBtn');
        }

        const selesaiBtn = document.getElementById('selesaiPerjalananBtn');
        if (selesaiBtn) {
            selesaiBtn.addEventListener('click', selesaikanPerjalanan);
            console.log('✅ Direct listener untuk selesaiPerjalananBtn');
        }

        const backBtn = document.getElementById('backButton');
        if (backBtn) {
            backBtn.addEventListener('click', backToDaftarPerjalanan);
            console.log('✅ Direct listener untuk backButton');
        }

        const backToDaftarLink = document.getElementById('backToDaftar');
        if (backToDaftarLink) {
            backToDaftarLink.addEventListener('click', function(e) {
                e.preventDefault();
                backToDaftarPerjalanan();
            });
            console.log('✅ Direct listener untuk backToDaftar');
        }

        // Event listener untuk menutup modal saat klik di luar modal
        const modal = document.getElementById('updateLokasiModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    console.log('🔴 Menutup modal - klik di luar');
                    hideUpdateLokasiModal();
                }
            });
            console.log('✅ Direct listener untuk updateLokasiModal close');
        }

        console.log('✅✅✅ Semua event listeners (delegation + direct) berhasil setup');

        // ----- Real-time listeners via Laravel Echo (jika tersedia) -----
        // Listen to journey started/completed events for each trip so UI updates automatically
        function handleJourneyStartedEvent(payload) {
            try {
                const tripId = String(payload.trip_id || payload.tripId || payload.data?.trip_id || payload.data?.tripId);
                console.log('🔔 Received DriverJourneyStarted for', tripId, payload);

                // Update tripsData status
                const idx = tripsData.findIndex(t => String(t.id_jadwal_driver) === String(tripId));
                if (idx !== -1) {
                    tripsData[idx].status = 'dalam_perjalanan';
                }

                // If the trip exists in completedTrips (edge-case), remove it
                const completedIdx = completedTrips.findIndex(t => String(t.id_jadwal_driver) === String(tripId));
                if (completedIdx !== -1) {
                    completedTrips.splice(completedIdx, 1);
                }

                // Rerender lists
                renderTripList();
                renderCompletedTripsHistory();

                // If the trip is open in detail page, update buttons/UI
                if (currentTripId && String(currentTripId) === String(tripId)) {
                    journeyStarted[currentTripId] = true;
                    const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
                    const updateBtn = document.getElementById('updateLokasiBtn');
                    if (mulaiBtn) mulaiBtn.classList.add('hidden');
                    if (updateBtn) updateBtn.classList.remove('hidden');
                    const statusEl = document.getElementById(`status-${tripId}`);
                    if (statusEl) { statusEl.textContent = 'Dalam Perjalanan'; statusEl.className = 'status'; }
                }
            } catch (err) {
                console.error('Error handling DriverJourneyStarted event:', err);
            }
        }

        function handleJourneyCompletedEvent(payload) {
            try {
                const tripId = String(payload.trip_id || payload.tripId || payload.data?.trip_id || payload.data?.tripId);
                console.log('🔔 Received DriverJourneyCompleted for', tripId, payload);

                // Move trip from tripsData to completedTrips
                const idx = tripsData.findIndex(t => String(t.id_jadwal_driver) === String(tripId));
                if (idx !== -1) {
                    const tripObj = tripsData.splice(idx, 1)[0];
                    tripObj.status = 'selesai';
                    completedTrips.unshift(tripObj);
                } else {
                    // If not found in tripsData, ensure it's marked completed in completedTrips
                    const cidx = completedTrips.findIndex(t => String(t.id_jadwal_driver) === String(tripId));
                    if (cidx === -1) {
                        completedTrips.unshift({ id_jadwal_driver: tripId, status: 'selesai' });
                    }
                }

                // Rerender lists
                renderTripList();
                renderCompletedTripsHistory();

                // If currently viewing detail of this trip, navigate back to daftar
                if (currentTripId && String(currentTripId) === String(tripId)) {
                    alert('Perjalanan telah selesai dan dipindahkan ke Riwayat. Anda akan kembali ke daftar perjalanan.');
                    backToDaftarPerjalanan();
                }
            } catch (err) {
                console.error('Error handling DriverJourneyCompleted event:', err);
            }
        }

        // Subscribe to channels if Echo is available
        if (window.Echo) {
            try {
                // Subscribe to per-trip channels
                tripsData.forEach(t => {
                    const tripId = t.id_jadwal_driver;
                    if (!tripId) return;

                    try {
                        window.Echo.private(`trip.journey.${tripId}`)
                            .listen('DriverJourneyStarted', (e) => handleJourneyStartedEvent(e))
                            .listen('DriverJourneyCompleted', (e) => handleJourneyCompletedEvent(e));
                    } catch (e) {
                        console.warn('Echo subscription error for trip', tripId, e);
                    }
                });

                // Also listen to admin/global channel for any journey events
                try {
                    window.Echo.private('admin.driver-journeys')
                        .listen('DriverJourneyStarted', (e) => handleJourneyStartedEvent(e))
                        .listen('DriverJourneyCompleted', (e) => handleJourneyCompletedEvent(e));
                } catch (e) {
                    console.warn('Echo subscription error for admin.driver-journeys', e);
                }

                console.log('✅ Real-time Echo listeners registered for journey events');
            } catch (err) {
                console.warn('Failed to initialize Echo listeners', err);
            }
        } else {
            console.log('ℹ️ Laravel Echo not found; real-time journey updates are disabled in this browser');
        }
    });
</script>
@endpush
