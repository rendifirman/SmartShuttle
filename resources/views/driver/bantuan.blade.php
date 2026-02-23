<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - Smart Shuttle Driver</title>
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

        /* ===== FAQ SECTION ===== */
        .faq-section {
            margin-bottom: 30px;
        }

        .faq-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .faq-question:hover {
            color: #ff6a00;
        }

        .faq-answer {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            display: none;
        }

        .faq-toggle {
            font-size: 20px;
            color: #ff6a00;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-toggle {
            transform: rotate(45deg);
        }

        /* ===== CONTACT SECTION ===== */
        .contact-section {
            background: linear-gradient(135deg, #0d3559 0%, #1a4d7a 100%);
            color: white;
            padding: 30px;
            border-radius: 14px;
            text-align: center;
        }

        .contact-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .contact-text {
            font-size: 16px;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }

        .contact-item i {
            font-size: 20px;
            color: #ff6a00;
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

            .contact-info {
                flex-direction: column;
                gap: 15px;
            }

            .header-section {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
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
    </a>
    <a href="{{ route('driver.dashboard') ?? '#' }}" class="menu-item" id="dashboard-link">
        <div class="menu-icon"><i class="fas fa-chart-bar"></i></div>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('driver.perjalanan') ?? '#' }}" class="menu-item" id="perjalanan-link">
        <div class="menu-icon"><i class="fas fa-route"></i></div>
        <span>Perjalanan</span>
    </a>
    <a href="{{ route('driver.jadwal') ?? '#' }}" class="menu-item" id="jadwal-link">
        <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
        <span>Jadwal</span>
    </a>
    <a href="{{ route('driver.laporan') ?? '#' }}" class="menu-item" id="laporan-link">
        <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
        <span>Laporan</span>
    </a>
    <a href="{{ route('driver.pengaturan') ?? '#' }}" class="menu-item" id="pengaturan-link">
        <div class="menu-icon"><i class="fas fa-cog"></i></div>
        <span>Pengaturan</span>
    </a>
    <a href="{{ route('driver.bantuan') ?? '#' }}" class="menu-item menu-active" id="bantuan-link">
        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
        <span>Bantuan</span>
    </a>
</div>

<!-- MAIN CONTENT -->
<main class="content">
    <!-- Top Profile untuk konten utama -->
    <div class="top-profile">
        <i class="fas fa-user-circle"></i>
        <span>{{ auth()->guard('driver')->user()?->name ?? 'Driver' }}</span>
    </div>

    <!-- HEADER SECTION -->
    <div class="header-section">
        <h1 class="title">Bantuan & FAQ</h1>
    </div>

    <div class="divider"></div>

    <!-- FAQ SECTION -->
    <div class="card faq-section">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #333;">Pertanyaan yang Sering Diajukan</h3>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara login ke aplikasi driver?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Untuk login ke aplikasi driver Smart Shuttle, gunakan email dan password yang telah diberikan oleh admin. Pastikan koneksi internet stabil saat login.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara update lokasi selama perjalanan?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Pada halaman Perjalanan, klik tombol "Update Lokasi" untuk mengirimkan posisi terkini kepada penumpang. Pastikan GPS perangkat Anda aktif.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apa yang harus dilakukan jika shuttle mengalami masalah teknis?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Segera hubungi admin melalui kontak yang tersedia atau gunakan fitur laporan di aplikasi untuk melaporkan masalah teknis shuttle.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara melihat jadwal perjalanan saya?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Kunjungi menu "Jadwal" di sidebar untuk melihat semua jadwal perjalanan yang telah ditugaskan kepada Anda.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara membuat laporan perjalanan?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Akses menu "Laporan" untuk membuat dan mengirimkan laporan perjalanan harian. Pastikan semua data terisi dengan lengkap.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apa yang harus dilakukan jika ada penumpang yang tidak hadir?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Catat penumpang yang tidak hadir dalam laporan perjalanan dan informasikan kepada admin untuk tindak lanjut refund atau penjadwalan ulang.
            </div>
        </div>
    </div>

    <!-- CONTACT SECTION -->
    <div class="contact-section">
        <h3 class="contact-title">Butuh Bantuan Lebih Lanjut?</h3>
        <p class="contact-text">Hubungi tim support kami untuk mendapatkan bantuan langsung</p>

        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <span>0858-1122-4321</span>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span>support@smartshuttle.id</span>
            </div>
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <span>24/7 Support</span>
            </div>
        </div>
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

    // Fungsi untuk toggle FAQ
    function toggleFAQ(element) {
        const faqItem = element.parentElement;
        const answer = faqItem.querySelector('.faq-answer');
        const toggle = element.querySelector('.faq-toggle');

        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            faqItem.classList.remove('active');
        } else {
            answer.style.display = 'block';
            faqItem.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', setActiveMenu);
</script>

</body>
</html>
