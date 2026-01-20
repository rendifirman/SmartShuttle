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

        .divider {
            width: 100%;
            height: 3px;
            background: #E2E2E2;
            margin: 0 0 25px 0;
        }

        /* ===== CARD ===== */
        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            margin-bottom: 25px;
        }

        /* ===== CARD PERJALANAN AKTIF ===== */
        .card-aktif {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .card-left {
            width: 45%;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }

        .card-title i {
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

        /* ===== RIWAYAT ===== */
        .riwayat-box {
            padding-top: 20px;
            padding-bottom: 40px;
        }

        .riwayat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .riwayat-header h3 {
            color: #333;
            font-size: 20px;
            font-weight: 700;
        }

        .riwayat-header select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: white;
            font-size: 14px;
        }

        .riwayat-empty {
            text-align: center;
            font-size: 16px;
            margin-top: 45px;
            color: #555;
            padding: 40px 0;
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

            .btn-primary, .btn-success {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">SMART SHUTTLE DRIVER</div>

    <a href="{{ route('driver.profile') ?? '#' }}" class="menu-item" id="profile-link">
        <div class="menu-icon"><i class="fas fa-user"></i></div>
        <span>Profile Saya</span>
    <a href="{{ route('driver.dashboard') ?? '#' }}" class="menu-item" id="dashboard-link">
        <div class="menu-icon"><i class="fas fa-chart-bar"></i></div>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('driver.perjalanan') ?? '#' }}" class="menu-item menu-active" id="perjalanan-link">
        <div class="menu-icon"><i class="fas fa-route"></i></div>
        <span>Perjalanan</span>
    </a>
    <a href="{{ route('driver.jadwal') ?? '#' }}" class="menu-item" id="jadwal-link">
        <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
        <span>Jadwal</span>
    </a>
    </a>
    <a href="{{ route('driver.laporan') ?? '#' }}" class="menu-item" id="laporan-link">
        <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
        <span>Laporan</span>
    </a>
    <a href="{{ route('driver.pengaturan') ?? '#' }}" class="menu-item" id="pengaturan-link">
        <div class="menu-icon"><i class="fas fa-cog"></i></div>
        <span>Pengaturan</span>
    </a>
    <a href="{{ route('driver.bantuan') ?? '#' }}" class="menu-item" id="bantuan-link">
        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
        <span>Bantuan</span>
    </a>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    <!-- Top Profile untuk konten utama -->
    <div class="top-profile">
        <i class="fas fa-user-circle"></i>
        <span>Dimas Mahendra</span>
    </div>

    <!-- HEADER SECTION -->
    <div class="header-section">
        <h1 class="title">Perjalanan</h1>

    </div>

    <div class="divider"></div>

    <!-- CARD PERJALANAN AKTIF -->
    <div class="card card-aktif">
        <div class="card-left">
            <h3 class="card-title"><i class="fa-solid fa-circle-play"></i> Perjalanan Aktif</h3>

            <div class="location">
                <div class="loc-title"><i class="fa-solid fa-play"></i> Jakarta Pusat</div>
                <div class="loc-sub">Terminal Bus Kampung Rambutan</div>
            </div>

            <div class="line"></div>

            <div class="location">
                <div class="loc-title"><i class="fa-solid fa-location-dot"></i> Bandung</div>
                <div class="loc-sub">Terminal Bus Leuwipanang</div>
            </div>
        </div>

        <div class="card-right">
            <div class="info-section">
                <div class="info-row">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <div class="info-title">Waktu Tempuh</div>
                        <div class="info-value">3 jam 15 menit</div>
                    </div>
                </div>

                <div class="info-row">
                    <i class="fa-solid fa-road"></i>
                    <div>
                        <div class="info-title">Jarak</div>
                        <div class="info-value">145 km</div>
                    </div>
                </div>

                <div class="info-row">
                    <i class="fa-solid fa-users"></i>
                    <div>
                        <div class="info-title">Penumpang</div>
                        <div class="info-value">22/30</div>
                    </div>
                </div>
            </div>

            <div class="button-section">
                <button class="btn-primary"><i class="fa-solid fa-location-arrow"></i> Update Lokasi</button>
                <button class="btn-success"><i class="fa-solid fa-check"></i> Selesaikan Perjalanan</button>
            </div>
        </div>
    </div>

    <!-- MAPS -->
    <div class="card map-box">
        <div class="map-placeholder">
            <i class="fa-solid fa-location-dot"></i>
            <p>Maps akan tampil saat perjalanan aktif</p>
        </div>
    </div>

    <!-- RIWAYAT -->
    <div class="card riwayat-box">
        <div class="riwayat-header">
            <h3>Riwayat Perjalanan</h3>
            <select>
                <option>Minggu Ini</option>
                <option>Bulan Ini</option>
                <option>Semua</option>
            </select>
        </div>

        <p class="riwayat-empty">Tidak ada riwayat perjalanan minggu ini</p>
    </div>
</main>

<script>
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

    document.addEventListener('DOMContentLoaded', setActiveMenu);
</script>

</body>
</html>
