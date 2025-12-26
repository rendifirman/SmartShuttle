@extends('layouts.app')

@section('title', 'Kontak - Smart Shuttle')

@push('styles')
<style>
    /* CSS Variables - Warna netral */
    :root {
        --primary-color: #9f2800ff;
        --footer-color: #00215E;
        --secondary-color: #FF581E;
        --accent-color: #e0704aff;
        --whatsapp-green: #25D366;
        --phone-blue: #3498DB;
        --text-dark: #2C3E50;
        --text-light: #666;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --border-color: #eaeaea;
        --card-bg: rgba(255, 255, 255, 0.92);
    }

    /* Background dengan efek blur */
    .contact-page {
        font-family: 'Inter', sans-serif;
        position: relative;
        min-height: 100vh;
        background:
            linear-gradient(
                rgba(0, 0, 0, 0.4),
                rgba(0, 0, 0, 0.4)
            ),
            url('{{ asset("images/backgroundpeta.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        padding-top: 100px;
        backdrop-filter: blur(5px);
    }

    /* Filter blur untuk background */
    .contact-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        backdrop-filter: blur(10px);
        z-index: -1;
    }

    /* Konten utama di atas blurred background */
    .contact-section {
        padding: 80px 0;
        position: relative;
        z-index: 1;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .contact-title {
        font-size: 52px;
        font-weight: 900;
        color: var(--white);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    }

    .contact-subtitle {
        color: var(--white);
        font-size: 20px;
        font-weight: 500;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.95;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
    }

    .contact-content {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Left Card */
    .contact-info-card {
        background: rgba(255, 255, 255, 0.92);
        border-radius: 16px;
        padding: 50px;
        width: 370px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
    }

    .contact-info-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        border-color: rgba(127, 140, 141, 0.5);
    }

    .contact-info-card .contact-subtitle {
        color: var(--primary-color);
        margin-bottom: 40px;
        margin-top: 20px;
        font-weight: 700;
        font-style: italic;
        font-size: 24px;
        text-align: left;
        text-shadow: none;
        opacity: 1;
    }

    .contact-item {
        margin-bottom: 30px;
        display: flex;
        gap: 15px;
        align-items: flex-start;
        border: 2px solid transparent;
        padding: 20px;
        border-radius: 12px;
        background: rgba(248, 249, 250, 0.8);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .contact-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, transparent, var(--accent-color), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .contact-item:hover::before {
        transform: translateX(100%);
    }

    .contact-item:hover {
        border-color: var(--accent-color);
        box-shadow: 0 8px 25px rgba(127, 140, 141, 0.2);
        transform: translateY(-5px);
    }

    .contact-item:last-child {
        margin-bottom: 0;
    }

    .contact-item p {
        margin: 0;
        color: var(--text-dark);
        font-size: 16px;
        font-weight: 600;
        line-height: 1.6;
    }

    .contact-icon {
        color: var(--accent-color);
        font-size: 24px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(127, 140, 141, 0.1);
        border-radius: 8px;
        padding: 5px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .contact-item:hover .contact-icon {
        background: var(--accent-color);
        color: white;
        transform: rotate(15deg) scale(1.1);
    }

    .jam-operasional {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px dashed rgba(44, 62, 80, 0.1);
        position: relative;
    }

    .jam-operasional::before {
        content: '';
        position: absolute;
        top: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, var(--accent-color), transparent);
    }

    .jam-operasional h4 {
        color: var(--text-dark);
        margin-bottom: 20px;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .jam-operasional h4 i {
        color: var(--accent-color);
        background: rgba(127, 140, 141, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .jam-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .jam-item:hover {
        background: rgba(127, 140, 141, 0.05);
        padding: 12px 15px;
        border-radius: 8px;
        transform: translateX(5px);
    }

    .jam-item:last-child {
        border-bottom: none;
    }

    .jam-hari {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 14px;
    }

    .jam-waktu {
        color: var(--accent-color);
        font-weight: 700;
        font-size: 14px;
        background: rgba(127, 140, 141, 0.1);
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid rgba(127, 140, 141, 0.2);
    }

    /* Right Form */
    .contact-form-card {
        background: rgba(255, 255, 255, 0.92);
        text-align: center;
        color: var(--text-dark);
        border-radius: 16px;
        padding: 50px;
        width: 460px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
        overflow: hidden;
    }

    .contact-form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
    }

    .contact-form-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
    }

    .form-title {
        font-weight: 800;
        margin-bottom: 10px;
        margin-top: 10px;
        font-style: italic;
        font-size: 28px;
        color: var(--primary-color);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-subtitle {
        margin-bottom: 30px;
        font-size: 15px;
        color: var(--text-light);
        line-height: 1.6;
    }

    .contact-form {
        text-align: left;
    }

    .form-input {
        width: 100%;
        padding: 18px 1px;
        border: 2px solid rgba(44, 62, 80, 0.1);
        border-radius: 10px;
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-input::placeholder {
        color: rgba(44, 62, 80, 0.4);
        font-weight: 400;
    }

    .form-input:focus {
        outline: none;
        background: white;
        border-color: var(--accent-color);
        box-shadow: 0 6px 15px rgba(127, 140, 141, 0.2);
        transform: translateY(-2px);
    }

    .form-textarea {
        width: 100%;
        padding: 10px 1px;
        border: 2px solid rgba(44, 62, 80, 0.1);
        border-radius: 12px;
        margin-bottom: 25px;
        resize: vertical;
        min-height: 150px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-textarea::placeholder {
        color: rgba(44, 62, 80, 0.4);
        font-weight: 400;
    }

    .form-textarea:focus {
        outline: none;
        background: white;
        border-color: var(--accent-color);
        box-shadow: 0 6px 15px rgba(127, 140, 141, 0.2);
        transform: translateY(-2px);
    }

    .submit-btn {
        width: 100%;
        padding: 18px;
        border: none;
        font-weight: 700;
        background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
        color: white;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(127, 140, 141, 0.3);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(127, 140, 141, 0.4);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    .alert {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        font-weight: 500;
        border-left: 5px solid;
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(212, 237, 218, 0.9), rgba(195, 230, 203, 0.9));
        color: #155724;
        border-left-color: #27ae60;
    }

    .alert-error {
        background: linear-gradient(135deg, rgba(248, 215, 218, 0.9), rgba(245, 198, 203, 0.9));
        color: #721c24;
        border-left-color: #e74c3c;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(209, 236, 241, 0.9), rgba(190, 229, 235, 0.9));
        color: #0c5460;
        border-left-color: #3498db;
    }

    /* Tombol Konfigurasi di Kartu Kontak */
    .config-button-container {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 3;
    }

    .btn-config-kontak {
        background: linear-gradient(135deg, var(--primary-color), #34495E);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(44, 62, 80, 0.25);
        text-decoration: none;
    }

    .btn-config-kontak:hover {
        background: linear-gradient(135deg, #34495E, var(--primary-color));
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(44, 62, 80, 0.4);
    }

    /* ========== FLOATING CUSTOMER SERVICE BUTTONS ========== */
    .floating-cs-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        transition: all 0.3s ease;
    }

    .cs-button {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        text-decoration: none;
        animation: float 3s ease-in-out infinite;
        overflow: hidden;
    }

    .cs-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        border-radius: 50%;
    }

    .cs-button.whatsapp {
        background: linear-gradient(135deg, var(--whatsapp-green), #128C7E);
    }

    .cs-button.phone {
        background: linear-gradient(135deg, var(--phone-blue), #2980b9);
    }

    .cs-button i {
        color: white;
        font-size: 28px;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .cs-button:hover {
        transform: translateY(-8px) scale(1.15);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }

    .cs-button:hover i {
        transform: rotate(15deg) scale(1.1);
    }

    .cs-button:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* Tooltip untuk button */
    .cs-button::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 15px);
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .cs-button::before.tooltip-arrow {
        content: '';
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.85) transparent transparent transparent;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10001;
    }

    .cs-button:hover::before.tooltip-arrow {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* Animasi floating */
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }

    /* Responsive floating buttons */
    @media (max-width: 768px) {
        .floating-cs-container {
            bottom: 20px;
            right: 20px;
        }

        .cs-button {
            width: 55px;
            height: 55px;
        }

        .cs-button i {
            font-size: 22px;
        }

        .cs-button::after {
            font-size: 12px;
            padding: 8px 12px;
        }
    }

    @media (max-width: 480px) {
        .floating-cs-container {
            bottom: 15px;
            right: 15px;
            gap: 10px;
        }

        .cs-button {
            width: 50px;
            height: 50px;
        }

        .cs-button i {
            font-size: 20px;
        }

        .cs-button::after {
            font-size: 11px;
            padding: 6px 10px;
        }
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
        .contact-content {
            flex-direction: column;
            align-items: center;
        }

        .contact-info-card, .contact-form-card {
            width: 100%;
            max-width: 550px;
        }

        .contact-title {
            font-size: 42px;
        }
    }

    @media (max-width: 768px) {
        .contact-title {
            font-size: 36px;
        }

        .contact-subtitle {
            font-size: 18px;
            padding: 0 20px;
        }

        .contact-info-card, .contact-form-card {
            padding: 35px;
        }

        .contact-info-card .contact-subtitle, .form-title {
            font-size: 22px;
        }

        .contact-page {
            background-attachment: scroll;
            backdrop-filter: blur(8px);
        }

        .config-button-container {
            top: 15px;
            right: 15px;
        }
    }

    @media (max-width: 480px) {
        .contact-title {
            font-size: 28px;
        }

        .contact-subtitle {
            font-size: 16px;
        }

        .contact-info-card, .contact-form-card {
            padding: 25px;
        }

        .contact-info-card .contact-subtitle, .form-title {
            font-size: 20px;
        }

        .contact-item {
            padding: 15px;
        }

        .form-input, .form-textarea {
            padding: 15px;
        }

        .btn-config-kontak {
            padding: 6px 12px;
            font-size: 11px;
        }

        .config-button-container {
            top: 10px;
            right: 10px;
        }

        .contact-page {
            backdrop-filter: blur(5px);
        }
    }

    /* Animasi untuk background photo loading */
    @keyframes fadeInBackground {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .contact-page {
        animation: fadeInBackground 1s ease-out;
    }

    /* Efek blur tambahan untuk konten */
    .contact-info-card, .contact-form-card {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Floating Customer Service Buttons -->
    <div class="floating-cs-container">
        <!-- WhatsApp Button -->
        @php
            $whatsappNumber = $masterKontak->telepon_utama ?? '085811224321';
            // Format nomor WhatsApp: hapus semua karakter non-digit
            $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
            // Jika nomor diawali dengan 0, ganti dengan 62
            if (substr($whatsappNumber, 0, 1) === '0') {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            }
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=Halo%20{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }}%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20shuttle.";
        @endphp
        <a href="{{ $whatsappUrl }}"
           target="_blank"
           class="cs-button whatsapp"
           data-tooltip="Chat WhatsApp"
           title="Chat via WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>

        <!-- Phone Button -->
        @php
            $phoneNumber = $masterKontak->telepon_utama ?? '0858-1122-4321';
            // Format untuk tel: link
            $phoneUrl = "tel:" . preg_replace('/[^0-9+]/', '', $phoneNumber);
        @endphp
        <a href="{{ $phoneUrl }}"
           class="cs-button phone"
           data-tooltip="Telepon Customer Service"
           title="Telepon Customer Service">
            <i class="fas fa-phone"></i>
        </a>
    </div>

    <div class="contact-page" id="contactPage">
        <section class="contact-section" id="contactSection">
            <div class="contact-header">
                <h1 class="contact-title">Hubungi Kami</h1>
                <p class="contact-subtitle">{{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }} - Perjalanan Nyaman, Pengalaman Tak Terlupakan</p>
            </div>

            <div class="contact-content">
                <!-- Left Card -->
                <div class="contact-info-card">
                    <!-- Tombol Konfigurasi di Kartu Kontak -->
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <div class="config-button-container">
                            <a href="{{ route('admin.kontak') }}" class="btn-config-kontak" title="Edit Kontak">
                                <i class="fas fa-edit"></i> Edit Kontak
                            </a>
                        </div>
                    @endif

                    <h6 class="contact-subtitle">Kami disini untuk membantu anda</h6>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> {{ session('info') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> Mohon periksa kembali data yang Anda masukkan.
                        </div>
                    @endif

                    <div class="contact-item">
                        <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                        <p>{{ $masterKontak->email_utama ?? 'mdcitrasolusi@gmail.com' }}</p>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon"><i class="fas fa-headphones"></i></span>
                        <p>{{ $masterKontak->email_dukungan ?? $masterKontak->email_utama ?? 'mdcitrasolusi@gmail.com' }}</p>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon"><i class="fas fa-phone"></i></span>
                        <p>{{ $masterKontak->telepon_utama ?? '0858-1122-4321' }}</p>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <p>{{ $masterKontak->alamat_kantor_pusat ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}</p>
                    </div>

                    @if(isset($masterKontak->jam_operasional) && is_array($masterKontak->jam_operasional))
                        <div class="jam-operasional">
                            <h4><i class="fas fa-clock"></i> Jam Operasional</h4>
                            @foreach($masterKontak->jam_operasional as $jam)
                                <div class="jam-item">
                                    <span class="jam-hari">{{ $jam['hari'] ?? 'Senin - Jumat' }}</span>
                                    <span class="jam-waktu">{{ $jam['jam'] ?? '08:00 - 17:00' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Form -->
                <div class="contact-form-card">
                    <h5 class="form-title">Kenyamanan anda, prioritas kami</h5>
                    <p class="form-subtitle">Beri kami masukan agar {{ $masterKontak->nama_perusahaan ?? 'Smart Shuttle' }} terus jadi pilihan terbaik untuk perjalanan Anda.</p>

                    <form action="{{ route('customer.contact.submit') }}" method="POST" class="contact-form" id="contactForm">
                        @csrf

                        <input type="text" name="nama" placeholder="Nama Lengkap" class="form-input"
                               value="{{ old('nama') }}" required />
                        @error('nama')
                            <small style="color: #e74c3c; display: block; margin-top: -15px; margin-bottom: 15px; font-size: 13px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="email" name="email" placeholder="Email" class="form-input"
                               value="{{ old('email') }}" required />
                        @error('email')
                            <small style="color: #e74c3c; display: block; margin-top: -15px; margin-bottom: 15px; font-size: 13px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="text" name="telepon" placeholder="Nomor Telepon" class="form-input"
                               value="{{ old('telepon') }}" />
                        @error('telepon')
                            <small style="color: #e74c3c; display: block; margin-top: -15px; margin-bottom: 15px; font-size: 13px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <textarea name="pesan" placeholder="Pesan atau ulasan Anda" rows="4" class="form-textarea" required>{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <small style="color: #e74c3c; display: block; margin-top: -15px; margin-bottom: 15px; font-size: 13px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded - init scripts kontak');

        /* ---------- FORM SUBMISSION ---------- */
        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');

        if (contactForm && submitBtn) {
            contactForm.addEventListener('submit', function(e) {
                // Validasi sederhana
                const nama = contactForm.querySelector('input[name="nama"]').value.trim();
                const email = contactForm.querySelector('input[name="email"]').value.trim();
                const pesan = contactForm.querySelector('textarea[name="pesan"]').value.trim();

                if (!nama || !email || !pesan) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return false;
                }

                // Validasi email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Mohon masukkan alamat email yang valid!');
                    return false;
                }

                // Disable tombol submit
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            });
        }

        /* ---------- CHECK BACKGROUND PHOTO ---------- */
        const contactPage = document.getElementById('contactPage');
        const bgImage = new Image();

        // Ganti 'your-photo.jpg' dengan nama foto yang kamu berikan
        const photoUrl = "{{ asset('images/backgroundpeta.png') }}";

        bgImage.onload = function() {
            console.log('Background photo loaded successfully');
            // Jika foto berhasil dimuat, background sudah diatur di CSS
        };

        bgImage.onerror = function() {
            console.log('Custom background photo not found');
            // Jika foto tidak ditemukan, tetap gunakan efek blur dengan warna netral
            if (contactPage) {
                contactPage.style.background = 'linear-gradient(135deg, rgba(44, 62, 80, 0.85), rgba(127, 140, 141, 0.8))';
            }
        };

        bgImage.src = photoUrl;

        /* ---------- FLOATING BUTTONS EFFECT ---------- */
        const csButtons = document.querySelectorAll('.cs-button');

        csButtons.forEach(button => {
            // Tambahkan efek klik
            button.addEventListener('click', function(e) {
                // Animasi klik
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });

            // Tambahkan tooltip arrow
            const tooltipArrow = document.createElement('div');
            tooltipArrow.className = 'tooltip-arrow';
            this.appendChild(tooltipArrow);
        });

        /* ---------- ANIMASI UNTUK KARTU KONTAK ---------- */
        const contactCards = document.querySelectorAll('.contact-info-card, .contact-form-card');
        contactCards.forEach((card, index) => {
            // Delay untuk animasi bertahap
            card.style.animationDelay = `${index * 0.2}s`;
        });

        /* ---------- VALIDASI REAL-TIME FORM ---------- */
        const formInputs = document.querySelectorAll('.form-input, .form-textarea');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = '#27ae60';
                } else {
                    this.style.borderColor = 'rgba(44, 62, 80, 0.1)';
                }
            });

            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = 'rgba(44, 62, 80, 0.1)';
                }
            });
        });

        /* ---------- EFEK BLUR DINAMIS ---------- */
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            const contactSection = document.getElementById('contactSection');

            if (contactSection) {
                // Sesuaikan blur berdasarkan scroll
                const blurIntensity = Math.min(10, 5 + (scrollPosition * 0.01));
                contactPage.style.backdropFilter = `blur(${blurIntensity}px)`;
            }
        });
    });
</script>
@endpush
