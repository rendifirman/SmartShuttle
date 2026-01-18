@extends('layouts.app')

@section('title', 'Kontak - Smart Shuttle')

@push('styles')
<style>
    /* CSS Variables - Warna netral */
    :root {
        --contact-primary: #9f2800ff;
        --contact-secondary: #e0704aff;
        --contact-accent: #FF581E;
        --text-dark: #2C3E50;
        --text-light: #666;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --whatsapp-green: #25D366;
        --phone-blue: #3498DB;
    }

    /* FIX: Pastikan navbar tidak memiliki margin/padding extra */
    .navbar-main-wrapper {
        left: 0 !important;
        right: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: none !important;
    }

    /* Background dengan efek blur */
    .contact-page {
        font-family: 'Inter', sans-serif;
        position: relative;
        min-height: calc(100vh - 80px);
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
        padding: 40px 0 80px;
        backdrop-filter: blur(5px);
        margin-top: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        animation: fadeInBackground 1s ease-out;
    }

    /* Container utama - PASTIKAN SAMA DENGAN NAVBAR */
    .contact-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Konten utama */
    .contact-section {
        padding: 40px 0;
        position: relative;
        z-index: 1;
        width: 100%;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 50px;
        width: 100%;
    }

    .contact-title {
        font-size: 48px;
        font-weight: 900;
        color: var(--white);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    }

    .contact-subtitle {
        color: var(--white);
        font-size: 18px;
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
        padding: 0 15px;
        width: 100%;
    }

    /* Left Card */
    .contact-info-card {
        background: rgba(255, 255, 255, 0.92);
        border-radius: 16px;
        padding: 40px;
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
    }

    .contact-info-card .contact-subtitle {
        color: var(--contact-primary);
        margin-bottom: 30px;
        margin-top: 10px;
        font-weight: 700;
        font-style: italic;
        font-size: 22px;
        text-align: left;
        text-shadow: none;
        opacity: 1;
    }

    .contact-item {
        margin-bottom: 25px;
        display: flex;
        gap: 15px;
        align-items: flex-start;
        padding: 15px;
        border-radius: 12px;
        background: rgba(248, 249, 250, 0.8);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .contact-item:hover {
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 8px 25px rgba(127, 140, 141, 0.2);
        transform: translateY(-5px);
    }

    .contact-item p {
        margin: 0;
        color: var(--text-dark);
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
    }

    .contact-icon {
        color: var(--contact-secondary);
        font-size: 22px;
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
        background: var(--contact-secondary);
        color: white;
        transform: rotate(15deg) scale(1.1);
    }

    .jam-operasional {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px dashed rgba(44, 62, 80, 0.1);
    }

    .jam-operasional h4 {
        color: var(--text-dark);
        margin-bottom: 20px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .jam-operasional h4 i {
        color: var(--contact-secondary);
        background: rgba(127, 140, 141, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .jam-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .jam-item:hover {
        background: rgba(127, 140, 141, 0.05);
        padding: 10px 12px;
        border-radius: 8px;
    }

    .jam-hari {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 13px;
    }

    .jam-waktu {
        color: var(--contact-secondary);
        font-weight: 700;
        font-size: 13px;
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
        padding: 40px;
        width: 460px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
        overflow: hidden;
    }

    .contact-form-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
    }

    .form-title {
        font-weight: 800;
        margin-bottom: 10px;
        font-style: italic;
        font-size: 24px;
        color: var(--contact-primary);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to right, var(--contact-primary), var(--contact-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-subtitle {
        margin-bottom: 25px;
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.6;
    }

    .contact-form {
        text-align: left;
    }

    .form-input {
        width: 100%;
        padding: 15px;
        border: 2px solid rgba(44, 62, 80, 0.1);
        border-radius: 10px;
        margin-bottom: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
        font-size: 15px;
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
        border-color: var(--contact-secondary);
        box-shadow: 0 6px 15px rgba(127, 140, 141, 0.2);
    }

    .form-textarea {
        width: 100%;
        padding: 15px;
        border: 2px solid rgba(44, 62, 80, 0.1);
        border-radius: 12px;
        margin-bottom: 20px;
        resize: vertical;
        min-height: 120px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-textarea:focus {
        outline: none;
        background: white;
        border-color: var(--contact-secondary);
        box-shadow: 0 6px 15px rgba(127, 140, 141, 0.2);
    }

    .submit-btn {
        width: 100%;
        padding: 16px;
        border: none;
        font-weight: 700;
        background: linear-gradient(135deg, var(--contact-secondary), var(--contact-accent));
        color: white;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(127, 140, 141, 0.3);
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, var(--contact-accent), var(--contact-secondary));
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(127, 140, 141, 0.4);
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    .alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
        border-left: 5px solid;
        animation: slideIn 0.5s ease;
        font-size: 14px;
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

    .error-message {
        color: #e74c3c;
        display: block;
        margin-top: -10px;
        margin-bottom: 10px;
        font-size: 12px;
        font-weight: 500;
    }

    .form-input.error,
    .form-textarea.error {
        border-color: #e74c3c;
        background-color: rgba(231, 76, 60, 0.05);
    }

    /* Tombol Konfigurasi di Kartu Kontak */
    .config-button-container {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 3;
    }

    .btn-config-kontak {
        background: linear-gradient(135deg, var(--contact-primary), #34495E);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 12px;
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
        background: linear-gradient(135deg, #34495E, var(--contact-primary));
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

    /* Animasi floating */
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }

    /* Animation classes */
    .animate__animated {
        animation-duration: 0.5s;
    }

    .animate__fadeInDown {
        animation-name: fadeInDown;
    }

    .animate__fadeOutUp {
        animation-name: fadeOutUp;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOutUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    @keyframes fadeInBackground {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
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
            font-size: 38px;
        }
        
        .contact-page {
            min-height: calc(100vh - 80px);
        }
    }

    @media (max-width: 768px) {
        .contact-title {
            font-size: 32px;
        }

        .contact-subtitle {
            font-size: 16px;
            padding: 0 20px;
        }

        .contact-info-card, .contact-form-card {
            padding: 30px;
        }

        .contact-info-card .contact-subtitle, .form-title {
            font-size: 20px;
        }

        .contact-page {
            padding: 20px 15px 60px;
            background-attachment: scroll;
        }
        
        .contact-container {
            padding: 0 15px !important;
        }

        /* Responsive floating buttons */
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
        .contact-title {
            font-size: 26px;
        }

        .contact-subtitle {
            font-size: 14px;
        }

        .contact-info-card, .contact-form-card {
            padding: 20px;
        }

        .contact-info-card .contact-subtitle, .form-title {
            font-size: 18px;
        }

        .contact-item {
            padding: 12px;
        }

        .form-input, .form-textarea {
            padding: 12px;
            font-size: 14px;
        }
        
        .contact-page {
            padding: 20px 10px 50px;
        }
        
        .contact-container {
            padding: 0 10px !important;
        }
        
        .contact-content {
            padding: 0 10px;
        }

        /* Responsive floating buttons */
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
</style>
@endpush

@section('content')
<div class="contact-page">
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

    <div class="contact-container">
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

                    {{-- ======= ALERT UNTUK FORM ======= --}}
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

                    <form action="{{ route('customer.contact.submit') }}" method="POST" class="contact-form" id="contactForm">
                        @csrf
                        <input type="hidden" name="ajax_submit" value="1">
                        
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="form-input"
                               value="{{ old('nama') }}" required />
                        @error('nama')
                            <small class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="email" name="email" placeholder="Email" class="form-input"
                               value="{{ old('email') }}" required />
                        @error('email')
                            <small class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="text" name="telepon" placeholder="Nomor Telepon" class="form-input"
                               value="{{ old('telepon') }}" />
                        @error('telepon')
                            <small class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <textarea name="pesan" placeholder="Pesan atau ulasan Anda" rows="4" class="form-textarea" required>{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <small class="error-message">
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
        const contactPage = document.querySelector('.contact-page');
        const bgImage = new Image();
        const photoUrl = "{{ asset('images/backgroundpeta.png') }}";

        bgImage.onload = function() {
            console.log('Background photo loaded successfully');
        };

        bgImage.onerror = function() {
            console.log('Custom background photo not found');
            // Jika foto tidak ditemukan, gunakan warna netral
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

        /* ---------- VALIDASI REAL-TIME FORM ---------- */
        const formInputs = document.querySelectorAll('.form-input, .form-textarea');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = '#27ae60';
                    this.classList.remove('error');
                    const errorMessage = this.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
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
    });

    {{-- AJAX Form Submission --}}
    $(document).ready(function() {
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var submitBtn = $('#submitBtn');
            var originalBtnText = submitBtn.html();
            
            // Disable button and show loading
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
            
            // Clear previous alerts
            $('.alert').remove();
            
            // Remove previous error messages
            $('.error-message').remove();
            $('.form-input, .form-textarea').removeClass('error');
            
            // AJAX request
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message);
                        
                        // Reset form
                        form[0].reset();
                        
                        // Focus on first input
                        form.find('input[name="nama"]').focus();
                    } else {
                        // Show error message
                        showAlert('error', response.message || 'Terjadi kesalahan');
                        
                        // Show validation errors if any
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                var input = form.find('[name="' + key + '"]');
                                input.addClass('error');
                                input.after('<small class="error-message"><i class="fas fa-exclamation-circle"></i> ' + value[0] + '</small>');
                            });
                        }
                    }
                },
                error: function(xhr) {
                    var message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('error', message);
                },
                complete: function() {
                    // Re-enable button
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalBtnText);
                }
            });
        });
        
        // Function to show alert
        function showAlert(type, message) {
            var alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            var alertHtml = '<div class="alert ' + alertClass + ' animate__animated animate__fadeInDown">' +
                            '<i class="fas ' + icon + '"></i> ' + message +
                            '</div>';
            
            // Insert alert before form
            $('#contactForm').before(alertHtml);
            
            // Auto remove alert after 5 seconds
            setTimeout(function() {
                $('.alert').addClass('animate__fadeOutUp');
                setTimeout(function() {
                    $('.alert').remove();
                }, 500);
            }, 5000);
        }
        
        // Remove error class and message on input
        $('#contactForm input, #contactForm textarea').on('input', function() {
            $(this).removeClass('error');
            $(this).next('.error-message').remove();
        });
    });
</script>
@endpush