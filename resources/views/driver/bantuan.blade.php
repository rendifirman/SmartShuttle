@extends('layouts.app-driver')

@section('title', 'Bantuan - Driver')

@push('styles')
<style>
    /* ==========================================================================
       BANTUAN & FAQ - SMART SHUTTLE DRIVER
       Theme Match dengan Halaman Lainnya (#0d3559 & #ff6a00)
       Optimized for Mobile
       ========================================================================== */

    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --success-green: #10b981;
        --gray-bg: #f5f7fa;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --gray-dark: #334155;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --transition: all 0.3s ease;
    }

    /* ===== CONTENT AREA (karena layout sudah include sidebar) ===== */
    .content-wrapper {
        padding: 1.5rem;
        background: var(--gray-bg);
        min-height: calc(100vh - 60px);
    }

    /* ===== HEADER SECTION ===== */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .title i {
        color: var(--primary-orange);
        font-size: 1.8rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .profile-box {
        background: var(--white);
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary-dark);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }

    .profile-box:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .profile-box i {
        color: var(--primary-orange);
        font-size: 1rem;
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    /* ===== CARD ===== */
    .card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-border);
    }

    .card h3 i {
        color: var(--primary-orange);
        font-size: 1.1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .card h3::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--primary-orange);
    }

    /* ===== FAQ SECTION ===== */
    .faq-section {
        margin-bottom: 1.5rem;
    }

    .faq-item {
        border-bottom: 1px solid var(--gray-border);
        padding: 1.25rem 0;
        transition: var(--transition);
    }

    .faq-item:first-child {
        padding-top: 0;
    }

    .faq-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .faq-question {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        transition: var(--transition);
        padding: 0.25rem 0;
    }

    .faq-question:hover {
        color: var(--primary-orange);
    }

    .faq-question span {
        flex: 1;
        line-height: 1.4;
    }

    .faq-toggle {
        font-size: 1.1rem;
        color: var(--primary-orange);
        transition: transform 0.3s ease;
        min-width: 24px;
        text-align: center;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(45deg);
    }

    .faq-answer {
        font-size: 0.9rem;
        color: var(--gray-text);
        line-height: 1.6;
        display: none;
        margin-top: 0.75rem;
        padding-left: 0.5rem;
        border-left: 2px solid var(--primary-orange-light);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    /* ===== CONTACT SECTION ===== */
    .contact-section {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #1a4d7a 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--radius-md);
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease 0.2s both;
    }

    .contact-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .contact-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .contact-title i {
        color: var(--primary-orange);
        animation: bounce 2s infinite;
    }

    .contact-text {
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
        position: relative;
    }

    .contact-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        position: relative;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.6rem 1.2rem;
        border-radius: 40px;
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .contact-item:hover {
        background: rgba(255, 106, 0, 0.2);
        transform: translateY(-2px);
        border-color: var(--primary-orange);
    }

    .contact-item i {
        font-size: 1.1rem;
        color: var(--primary-orange);
    }

    /* ===== RESPONSIVE MOBILE ===== */
    @media screen and (max-width: 768px) {
        .content-wrapper {
            padding: 1rem;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 1.5rem;
        }

        .title i {
            font-size: 1.5rem;
        }

        .profile-box {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .divider {
            width: 80px;
            margin-bottom: 1rem;
        }

        .card {
            padding: 1.25rem;
        }

        .card h3 {
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
        }

        .faq-item {
            padding: 1rem 0;
        }

        .faq-question {
            font-size: 0.95rem;
        }

        .faq-answer {
            font-size: 0.85rem;
        }

        .contact-section {
            padding: 1.5rem;
        }

        .contact-title {
            font-size: 1.3rem;
        }

        .contact-title i {
            font-size: 1.3rem;
        }

        .contact-text {
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        .contact-info {
            flex-direction: column;
            gap: 0.75rem;
        }

        .contact-item {
            width: 100%;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }

    @media screen and (max-width: 576px) {
        .content-wrapper {
            padding: 0.75rem;
        }

        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

        .card {
            padding: 1rem;
        }

        .card h3 {
            font-size: 1rem;
        }

        .faq-question {
            font-size: 0.9rem;
        }

        .faq-answer {
            font-size: 0.8rem;
        }

        .contact-title {
            font-size: 1.2rem;
        }

        .contact-item {
            font-size: 0.85rem;
        }
    }

    @media screen and (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }

        .profile-box {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }

        .faq-question {
            font-size: 0.85rem;
        }

        .faq-toggle {
            font-size: 1rem;
            min-width: 20px;
        }
    }

    /* Landscape mode */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .contact-info {
            flex-direction: row;
        }

        .contact-item {
            width: auto;
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .content-wrapper {
            padding: 1.5rem;
        }

        .contact-info {
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- HEADER SECTION -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-headset"></i>
            Bantuan & FAQ
        </h1>
    </div>

    <div class="divider"></div>

    <!-- FAQ SECTION -->
    <div class="card faq-section">
        <h3>
            <i class="fas fa-question-circle"></i>
            Pertanyaan yang Sering Diajukan
        </h3>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara login ke aplikasi driver?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Untuk login ke aplikasi driver Smart Shuttle, gunakan email dan password yang telah diberikan oleh admin. Pastikan koneksi internet stabil saat login. Jika lupa password, hubungi admin untuk reset password.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara update lokasi selama perjalanan?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Pada halaman <strong>Perjalanan</strong>, klik tombol "Update Lokasi" untuk mengirimkan posisi terkini kepada penumpang. Pastikan GPS perangkat Anda aktif dan izin lokasi diberikan untuk aplikasi.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apa yang harus dilakukan jika shuttle mengalami masalah teknis?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Segera hubungi admin melalui kontak yang tersedia atau gunakan fitur laporan di aplikasi untuk melaporkan masalah teknis shuttle. Tim kami akan segera membantu menangani kendala yang terjadi.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara melihat jadwal perjalanan saya?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Kunjungi menu <strong>Jadwal</strong> di sidebar untuk melihat semua jadwal perjalanan yang telah ditugaskan kepada Anda. Di sana Anda bisa melihat detail jadwal, rute, dan daftar penumpang.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara membuat laporan perjalanan?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Akses menu <strong>Laporan</strong> untuk membuat dan mengirimkan laporan perjalanan harian. Pastikan semua data terisi dengan lengkap termasuk jumlah penumpang, paket, dan kendala yang dialami.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apa yang harus dilakukan jika ada penumpang yang tidak hadir?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Catat penumpang yang tidak hadir dalam laporan perjalanan dan informasikan kepada admin untuk tindak lanjut refund atau penjadwalan ulang. Tunggu konfirmasi dari admin sebelum melanjutkan perjalanan.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara mengganti mode penerimaan jadwal?</span>
                <i class="fas fa-plus faq-toggle"></i>
            </div>
            <div class="faq-answer">
                Masuk ke menu <strong>Pengaturan</strong>, lalu pilih antara mode "Penerimaan Otomatis" atau "Konfirmasi Manual" sesuai preferensi Anda. Simpan perubahan untuk mengaktifkan mode yang dipilih.
            </div>
        </div>
    </div>

    <!-- CONTACT SECTION -->
    <div class="contact-section">
        <h3 class="contact-title">
            <i class="fas fa-phone-alt"></i>
            Butuh Bantuan Lebih Lanjut?
        </h3>
        <p class="contact-text">Hubungi tim support kami untuk mendapatkan bantuan langsung 24/7</p>

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

        <div style="margin-top: 1.5rem; font-size: 0.85rem; opacity: 0.8;">
            <i class="fas fa-map-marker-alt me-1"></i> Jl. Soekarno Hatta No. 123, Bandung
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        console.log('Halaman Bantuan & FAQ siap.');

        // Set active menu untuk halaman bantuan
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
            if (link.id === 'bantuan-link') {
                link.classList.add('menu-active');
            }
        });
    });

    // Fungsi untuk toggle FAQ
    function toggleFAQ(element) {
        const faqItem = element.closest('.faq-item');
        const answer = faqItem.querySelector('.faq-answer');
        const toggle = element.querySelector('.faq-toggle');

        if (faqItem.classList.contains('active')) {
            faqItem.classList.remove('active');
            answer.style.display = 'none';
        } else {
            faqItem.classList.add('active');
            answer.style.display = 'block';
        }
    }

    // Buka FAQ pertama secara default
    window.addEventListener('load', function() {
        const firstFaq = document.querySelector('.faq-item');
        if (firstFaq) {
            const question = firstFaq.querySelector('.faq-question');
            toggleFAQ(question);
        }
    });
</script>
@endpush