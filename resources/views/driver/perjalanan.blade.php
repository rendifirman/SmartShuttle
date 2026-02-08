<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjalanan - Smart Shuttle Driver</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .btn-detail:hover {
            background: #0a2b4a;
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
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease;
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
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">SMART SHUTTLE DRIVER</div>

    <a href="#" class="menu-item" id="profile-link">
        <div class="menu-icon"><i class="fas fa-user"></i></div>
        <span>Profile Saya</span>
    </a>

    <a href="#" class="menu-item" id="dashboard-link">
        <div class="menu-icon"><i class="fas fa-chart-bar"></i></div>
        <span>Dashboard</span>
    </a>
    <a href="#" class="menu-item menu-active" id="perjalanan-link">
        <div class="menu-icon"><i class="fas fa-route"></i></div>
        <span>Perjalanan</span>
    </a>
    <a href="#" class="menu-item" id="jadwal-link">
        <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
        <span>Jadwal</span>
    </a>
    
    <a href="#" class="menu-item" id="laporan-link">
        <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
        <span>Laporan</span>
    </a>
    <a href="#" class="menu-item" id="pengaturan-link">
        <div class="menu-icon"><i class="fas fa-cog"></i></div>
        <span>Pengaturan</span>
    </a>
    <a href="#" class="menu-item" id="bantuan-link">
        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
        <span>Bantuan</span>
    </a>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    <!-- ========================== -->
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
            <div style="font-weight:500; color:#555;">👤 Dimas Mahendra</div>
        </div>

        <!-- CARD DAFTAR PERJALANAN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Perjalanan Hari Ini</div>
                <div class="date-display">Tanggal: 03 Des 2025</div>
            </div>

            <!-- LIST PERJALANAN -->
            <!-- ITEM 1 -->
            <div class="trip-item" data-trip-id="1" data-from="Jakarta" data-to="Bandung" data-time="08:00" data-duration="3 jam 15 menit" data-seats="10/12" data-passengers="10">
                <div class="trip-header">
                    <div class="trip-number">1</div>
                    <div class="seat-info">10/12 kursi</div>
                </div>

                <div class="trip-route">Jakarta → Bandung</div>
                <div class="trip-time">08:00 • 3 jam 15 menit</div>

                <div class="status-badge">
                    <span class="status" id="status-1">Akan Berangkat</span>
                </div>

                <div style="text-align:right;">
                    <button class="btn-detail">Lihat Detail</button>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="trip-item" data-trip-id="2" data-from="Bandung" data-to="Jakarta" data-time="13:00" data-duration="3 jam 15 menit" data-seats="12/12" data-passengers="12">
                <div class="trip-header">
                    <div class="trip-number">2</div>
                    <div class="seat-info">12/12 kursi</div>
                </div>

                <div class="trip-route">Bandung → Jakarta</div>
                <div class="trip-time">13:00 • 3 jam 15 menit</div>

                <div class="status-badge">
                    <span class="status" id="status-2">Akan Berangkat</span>
                </div>

                <div style="text-align:right;">
                    <button class="btn-detail">Lihat Detail</button>
                </div>
            </div>

            <!-- ITEM 3 -->
            <div class="trip-item" data-trip-id="3" data-from="Jakarta" data-to="Bandung" data-time="18:00" data-duration="3 jam 15 menit" data-seats="8/12" data-passengers="8">
                <div class="trip-header">
                    <div class="trip-number">3</div>
                    <div class="seat-info">8/12 kursi</div>
                </div>

                <div class="trip-route">Jakarta → Bandung</div>
                <div class="trip-time">18:00 • 3 jam 15 menit</div>

                <div class="status-badge">
                    <span class="status" id="status-3">Akan Berangkat</span>
                </div>

                <div style="text-align:right;">
                    <button class="btn-detail">Lihat Detail</button>
                </div>
            </div>
        </div>

        <!-- RIWAYAT PERJALANAN (DI HALAMAN AWAL) -->
        <div class="card">
            <div class="history-filter">
                <div class="card-title">Riwayat Perjalanan</div>
                <select class="filter-select">
                    <option>Minggu ini</option>
                    <option>Bulan ini</option>
                    <option>3 bulan terakhir</option>
                </select>
            </div>

            <div class="history-item">
                <div class="history-route">Jakarta → Bandung</div>
                <div class="history-date">20-11-2025 | 08:00</div>
                <div class="history-footer">
                    <div class="passenger-count">10 penumpang</div>
                    <span class="status-completed">Selesai</span>
                </div>
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
                <span>Dimas Mahendra</span>
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
                    <button class="btn-primary" id="updateLokasiBtn">
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

<!-- MODAL UPDATE LOKASI -->
<div class="modal-overlay" id="updateLokasiModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Lokasi</h3>
            <p class="modal-subtitle">Lokasi bus akan berpindah ke titik berikutnya</p>
            <p class="modal-subtitle" style="margin-top: 10px; font-weight: 600;" id="modalNextLocation"></p>
        </div>
        
        <div class="modal-buttons">
            <button class="btn-cancel" id="cancelUpdateBtn">Batal</button>
            <button class="btn-update" id="confirmUpdateBtn">Update</button>
        </div>
    </div>
</div>

<script>
    // Data perjalanan - Bandung ke Jakarta dengan beberapa pemberhentian
    const journeyData = {
        currentStopIndex: 0,
        stops: [
            {
                name: "Bandung",
                detail: "Terminal Bus Leuwipanjang",
                type: "start"
            },
            {
                name: "Rest Area KM 58",
                detail: "Tol Padaleunyi",
                type: "stop"
            },
            {
                name: "Rest Area KM 97",
                detail: "Tol Cipularang",
                type: "stop"
            },
            {
                name: "Jakarta Pusat",
                detail: "Terminal Bus Kampung Rambutan",
                type: "finish"
            }
        ],
        travelTimes: ["3 jam 15 menit", "2 jam 30 menit", "1 jam 15 menit", "0 menit"],
        distances: ["145 km", "100 km", "45 km", "0 km"]
    };

    // Data penumpang untuk setiap perjalanan
    const penumpangData = {
        1: [
            { name: "Budi Santoso", phone: "0812-3456-7890", seat: "A01", status: "terverifikasi" },
            { name: "Siti Rahmawati", phone: "0813-9876-5432", seat: "A02", status: "terdaftar" },
            { name: "Ahmad Wijaya", phone: "0821-1122-3344", seat: "A03", status: "terverifikasi" },
            { name: "Dewi Lestari", phone: "0815-6677-8899", seat: "A04", status: "terdaftar" },
            { name: "Rudi Hartono", phone: "0822-5566-7788", seat: "A05", status: "terverifikasi" },
            { name: "Maya Indah", phone: "0817-2233-4455", seat: "A06", status: "terdaftar" },
            { name: "Hendra Pratama", phone: "0818-3344-5566", seat: "A07", status: "refund" },
            { name: "Lisa Anggraeni", phone: "0819-4455-6677", seat: "A08", status: "terdaftar" },
            { name: "Fajar Nugroho", phone: "0823-6677-8899", seat: "A09", status: "terverifikasi" },
            { name: "Rina Marlina", phone: "0814-7788-9900", seat: "A10", status: "terdaftar" }
        ],
        2: [
            { name: "Andi Susanto", phone: "0811-2233-4455", seat: "A01", status: "terverifikasi" },
            { name: "Rina Wijaya", phone: "0812-3344-5566", seat: "A02", status: "terverifikasi" },
            { name: "Bambang Setiawan", phone: "0813-4455-6677", seat: "A03", status: "terverifikasi" },
            { name: "Sari Dewi", phone: "0814-5566-7788", seat: "A04", status: "terverifikasi" },
            { name: "Agus Riyadi", phone: "0815-6677-8899", seat: "A05", status: "terverifikasi" },
            { name: "Nurul Hasanah", phone: "0816-7788-9900", seat: "A06", status: "terverifikasi" },
            { name: "Eko Prasetyo", phone: "0817-8899-0011", seat: "A07", status: "terverifikasi" },
            { name: "Dian Purnama", phone: "0818-9900-1122", seat: "A08", status: "terverifikasi" },
            { name: "Hari Santoso", phone: "0819-0011-2233", seat: "A09", status: "terverifikasi" },
            { name: "Maya Sari", phone: "0820-1122-3344", seat: "A10", status: "terverifikasi" },
            { name: "Joko Widodo", phone: "0821-2233-4455", seat: "A11", status: "terverifikasi" },
            { name: "Ani Rahayu", phone: "0822-3344-5566", seat: "A12", status: "terverifikasi" }
        ],
        3: [
            { name: "Rudi Hidayat", phone: "0811-9988-7766", seat: "A01", status: "terverifikasi" },
            { name: "Sri Wahyuni", phone: "0812-8877-6655", seat: "A02", status: "terdaftar" },
            { name: "Faisal Rahman", phone: "0813-7766-5544", seat: "A03", status: "terverifikasi" },
            { name: "Desi Anggraini", phone: "0814-6655-4433", seat: "A04", status: "terdaftar" },
            { name: "Ari Wibowo", phone: "0815-5544-3322", seat: "A05", status: "terverifikasi" },
            { name: "Lina Marlina", phone: "0816-4433-2211", seat: "A06", status: "refund" },
            { name: "Hendra Kurniawan", phone: "0817-3322-1100", seat: "A07", status: "terdaftar" },
            { name: "Wulan Sari", phone: "0818-2211-0099", seat: "A08", status: "terverifikasi" }
        ]
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

    // Fungsi untuk mengupdate tampilan perjalanan di halaman detail
    function updateJourneyDisplay() {
        const currentStop = journeyData.stops[journeyData.currentStopIndex];
        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        const isLastStop = journeyData.currentStopIndex === journeyData.stops.length - 1;
        
        // Update lokasi saat ini
        document.getElementById('currentLocation').textContent = currentStop.name;
        document.getElementById('currentLocationDetail').textContent = currentStop.detail;
        document.getElementById('mapLocation').textContent = currentStop.name;
        
        // Update tujuan akhir
        const finalDestination = journeyData.stops[journeyData.stops.length - 1];
        document.getElementById('finalDestination').textContent = finalDestination.name;
        document.getElementById('finalDestinationDetail').textContent = finalDestination.detail;
        
        // Update info perjalanan
        document.getElementById('travelTime').textContent = journeyData.travelTimes[journeyData.currentStopIndex];
        document.getElementById('distance').textContent = journeyData.distances[journeyData.currentStopIndex];
        
        // Update titik selanjutnya
        if (!isLastStop) {
            document.getElementById('nextStop').textContent = nextStop.name;
        } else {
            document.getElementById('nextStop').textContent = "Tujuan Akhir";
        }
        
        // Update progress bar
        const progressPercent = (journeyData.currentStopIndex / (journeyData.stops.length - 1)) * 100;
        document.getElementById('progressFill').style.width = `${progressPercent}%`;
        
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
                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i> 
                        <span style="text-decoration: line-through; color: #888;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #888;">${stop.detail} (Telah dilewati)</div>
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
                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i> 
                        <span style="color: #0095FF;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #0095FF;">${stop.detail} (Titik Selanjutnya)</div>
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

    // Fungsi untuk menampilkan modal update lokasi
    function showUpdateLokasiModal() {
        // Cek apakah sudah sampai tujuan akhir
        if (journeyData.currentStopIndex >= journeyData.stops.length - 1) {
            alert('Anda sudah sampai di tujuan akhir!');
            return;
        }
        
        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        document.getElementById('modalNextLocation').textContent = `Menuju: ${nextStop.name}`;
        
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
        // Pindah ke titik berikutnya
        journeyData.currentStopIndex++;
        
        // Update tampilan
        updateJourneyDisplay();
        
        // Tampilkan notifikasi
        const currentStop = journeyData.stops[journeyData.currentStopIndex];
        alert(`Lokasi berhasil diupdate! Sekarang berada di: ${currentStop.name}`);
        
        // Sembunyikan modal
        hideUpdateLokasiModal();
        
        // Jika sudah sampai tujuan akhir, tampilkan konfirmasi
        if (journeyData.currentStopIndex === journeyData.stops.length - 1) {
            setTimeout(() => {
                alert('Selamat! Anda telah sampai di tujuan akhir. Silakan selesaikan perjalanan.');
            }, 500);
        }
    }

    // Fungsi untuk menyelesaikan perjalanan
    function selesaikanPerjalanan() {
        if (journeyData.currentStopIndex === journeyData.stops.length - 1) {
            if (confirm('Apakah Anda yakin ingin menyelesaikan perjalanan?')) {
                // Update status di halaman detail
                document.querySelector('.card-title-detail i').style.color = "#2d572c";
                document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-check";
                document.getElementById('tripTitle').textContent = "Perjalanan Selesai";
                
                // Update status di halaman daftar perjalanan
                if (currentTripId) {
                    const statusElement = document.getElementById(`status-${currentTripId}`);
                    if (statusElement) {
                        statusElement.textContent = "Selesai";
                        statusElement.className = "status-selesai";
                    }
                }
                
                // Tambahkan ke riwayat
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                const formattedTime = currentDate.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                // Sembunyikan tombol
                document.getElementById('selesaiPerjalananBtn').classList.add('hidden');
                
                alert('Perjalanan telah diselesaikan! Status telah diperbarui.');
            }
        } else {
            alert('Anda belum sampai di tujuan akhir!');
        }
    }

    // Fungsi untuk menampilkan daftar penumpang berdasarkan ID perjalanan
    function generatePenumpangList(tripId) {
        const penumpangListElement = document.getElementById('penumpangList');
        const totalPenumpangElement = document.getElementById('totalPenumpang');
        
        // Kosongkan daftar penumpang
        penumpangListElement.innerHTML = '';
        
        // Ambil data penumpang berdasarkan ID perjalanan
        const passengers = penumpangData[tripId] || [];
        
        // Update total penumpang
        totalPenumpangElement.textContent = passengers.length;
        
        // Generate elemen untuk setiap penumpang
        passengers.forEach(passenger => {
            let statusClass = '';
            let statusText = '';
            
            // Tentukan kelas dan teks status
            switch(passenger.status) {
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
                    <div class="penumpang-phone">${passenger.phone}</div>
                </div>
                <div class="penumpang-seat">
                    <div class="seat-number">${passenger.seat}</div>
                    <span class="seat-status ${statusClass}">${statusText}</span>
                </div>
            `;
            
            // Tambahkan ke daftar
            penumpangListElement.appendChild(penumpangItem);
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
        
        // Reset data perjalanan untuk perjalanan baru
        journeyData.currentStopIndex = 0;
        
        // Update tampilan perjalanan
        updateJourneyDisplay();
        
        // Generate daftar penumpang berdasarkan ID perjalanan
        generatePenumpangList(parseInt(tripData.id));
        
        // Cek status perjalanan
        const statusElement = document.getElementById(`status-${tripData.id}`);
        if (statusElement && statusElement.textContent === "Selesai") {
            // Jika perjalanan sudah selesai
            document.querySelector('.card-title-detail i').style.color = "#2d572c";
            document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-check";
            document.getElementById('tripTitle').textContent = "Perjalanan Selesai";
            document.getElementById('selesaiPerjalananBtn').classList.add('hidden');
        } else {
            // Jika perjalanan masih aktif
            document.querySelector('.card-title-detail i').style.color = "#36B35A";
            document.querySelector('.card-title-detail i').className = "fa-solid fa-circle-play";
            document.getElementById('tripTitle').textContent = `Perjalanan #${tripData.id} - ${tripData.from} → ${tripData.to}`;
        }
        
        // Scroll ke atas
        window.scrollTo(0, 0);
    }

    // Fungsi untuk kembali ke halaman daftar perjalanan
    function backToDaftarPerjalanan() {
        // Sembunyikan halaman detail
        document.getElementById('detailPerjalananPage').classList.add('hidden');
        
        // Tampilkan halaman daftar
        document.getElementById('daftarPerjalananPage').classList.remove('hidden');
        
        // Scroll ke atas
        window.scrollTo(0, 0);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenu();
        
        // Event listener untuk tombol "Lihat Detail" pada setiap perjalanan
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const tripItem = this.closest('.trip-item');
                const tripData = {
                    id: tripItem.dataset.tripId,
                    from: tripItem.dataset.from,
                    to: tripItem.dataset.to,
                    time: tripItem.dataset.time,
                    duration: tripItem.dataset.duration,
                    seats: tripItem.dataset.seats,
                    passengers: tripItem.dataset.passengers
                };
                showDetailPerjalanan(tripData);
            });
        });
        
        // Event listener untuk klik pada item perjalanan (selain tombol)
        document.querySelectorAll('.trip-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Jangan trigger jika klik pada tombol detail
                if (!e.target.closest('.btn-detail')) {
                    const tripData = {
                        id: this.dataset.tripId,
                        from: this.dataset.from,
                        to: this.dataset.to,
                        time: this.dataset.time,
                        duration: this.dataset.duration,
                        seats: this.dataset.seats,
                        passengers: this.dataset.passengers
                    };
                    showDetailPerjalanan(tripData);
                }
            });
        });
        
        // Event listener untuk tombol Update Lokasi di halaman detail
        document.getElementById('updateLokasiBtn').addEventListener('click', showUpdateLokasiModal);
        
        // Event listener untuk tombol Batal di modal
        document.getElementById('cancelUpdateBtn').addEventListener('click', hideUpdateLokasiModal);
        
        // Event listener untuk tombol Update di modal
        document.getElementById('confirmUpdateBtn').addEventListener('click', confirmUpdateLokasi);
        
        // Event listener untuk tombol Selesaikan Perjalanan
        document.getElementById('selesaiPerjalananBtn').addEventListener('click', selesaikanPerjalanan);
        
        // Event listener untuk tombol kembali
        document.getElementById('backButton').addEventListener('click', backToDaftarPerjalanan);
        document.getElementById('backToDaftar').addEventListener('click', function(e) {
            e.preventDefault();
            backToDaftarPerjalanan();
        });
        
        // Event listener untuk menutup modal saat klik di luar modal
        document.getElementById('updateLokasiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideUpdateLokasiModal();
            }
        });
    });
</script>

</body>
</html>