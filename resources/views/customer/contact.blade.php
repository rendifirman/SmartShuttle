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
        }

        .contact-container {
            padding: 0 15px !important;
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
    }
</style>
@endpush

@section('content')
<div class="contact-page">
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
                            <small style="color: #e74c3c; display: block; margin-top: -10px; margin-bottom: 10px; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="email" name="email" placeholder="Email" class="form-input"
                               value="{{ old('email') }}" required />
                        @error('email')
                            <small style="color: #e74c3c; display: block; margin-top: -10px; margin-bottom: 10px; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <input type="text" name="telepon" placeholder="Nomor Telepon" class="form-input"
                               value="{{ old('telepon') }}" />
                        @error('telepon')
                            <small style="color: #e74c3c; display: block; margin-top: -10px; margin-bottom: 10px; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror

                        <textarea name="pesan" placeholder="Pesan atau ulasan Anda" rows="4" class="form-textarea" required>{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <small style="color: #e74c3c; display: block; margin-top: -10px; margin-bottom: 10px; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-exclamation-circle"></i> {{ message }}
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
        /* ---------- FORM SUBMISSION ---------- */
        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');

        if (contactForm && submitBtn) {
            contactForm.addEventListener('submit', function(e) {
                const nama = contactForm.querySelector('input[name="nama"]').value.trim();
                const email = contactForm.querySelector('input[name="email"]').value.trim();
                const pesan = contactForm.querySelector('textarea[name="pesan"]').value.trim();

                if (!nama || !email || !pesan) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return false;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Mohon masukkan alamat email yang valid!');
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            });
        }

        /* ---------- CHECK BACKGROUND PHOTO ---------- */
        const contactPage = document.querySelector('.contact-page');
        const bgImage = new Image();
        const photoUrl = "{{ asset('images/backgroundpeta.png') }}";

        bgImage.onerror = function() {
            if (contactPage) {
                contactPage.style.background = 'linear-gradient(135deg, rgba(44, 62, 80, 0.85), rgba(127, 140, 141, 0.8))';
            }
        };

        bgImage.src = photoUrl;

        /* ---------- FIX NAVBAR FORCE CENTER ---------- */
        const navbar = document.querySelector('.navbar-main-wrapper');
        if (navbar) {
            // Force center with JavaScript sebagai fallback
            const viewportWidth = window.innerWidth;
            const navbarWidth = navbar.offsetWidth;
            const offset = (viewportWidth - navbarWidth) / 2;

            if (offset > 0) {
                navbar.style.marginLeft = 'auto';
                navbar.style.marginRight = 'auto';
            }
        }
    });
</script>
@endpush
