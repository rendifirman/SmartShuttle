<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Membership - SmartShuttle</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @extends('layouts.app-profile')

    @section('title', 'Membership SmartShuttle')

    @push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
        }

        /* PAGE 2: REGISTRATION FORM STYLES */
        .membership-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .hero-banner {
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            text-decoration: none;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            text-decoration: none;
            color: white;
        }

        .hero-banner h2 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero-banner p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            margin: 0;
        }

        .feature-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .feature-card h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1a202c;
        }

        .feature-card p {
            font-size: 14px;
            color: #718096;
            line-height: 1.5;
            margin: 0;
        }

        .membership-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-card h4 {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 8px;
            color: #1a202c;
        }

        .form-card > p {
            font-size: 14px;
            color: #718096;
            margin-bottom: 25px;
        }

        .membership-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #2d3748;
        }

        .form-label .required {
            color: #e53e3e;
        }

        .form-input,
        .form-select {
            height: 42px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #cbd5e0;
            width: 100%;
            padding: 10px 14px;
            transition: all 0.2s ease;
            background-color: #fff;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #ff5a1f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 90, 31, 0.1);
        }

        .form-input:read-only {
            background-color: #f7fafc;
            color: #718096;
            cursor: not-allowed;
        }

        .form-hint {
            font-size: 11px;
            color: #718096;
            margin-top: 4px;
            font-style: italic;
        }

        .btn-membership {
            background: #ff5a1f;
            color: #fff;
            border: none;
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 90, 31, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-membership:hover {
            background: #e94e1b;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        .btn-membership:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .keuntungan-box {
            background: #e0f2fe;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #bae6fd;
        }

        .keuntungan-box h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a202c;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 12px;
        }

        .keuntungan-box ul {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .keuntungan-box ul li {
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 12px;
            padding-left: 24px;
            position: relative;
        }

        .keuntungan-box ul li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #0369a1;
            font-weight: bold;
        }

        .cost-card {
            background: #fce7f3;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            border: 1px solid #fbcfe8;
        }

        .cost-card p {
            font-size: 13px;
            color: #2d3748;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .cost-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #be185d;
            margin: 0;
        }

        /* Error Messages */
        .error-message {
            background: #fed7d7;
            border: 1px solid #fc8181;
            color: #c53030;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-message i {
            font-size: 14px;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .feature-cards,
            .membership-content {
                grid-template-columns: 1fr;
            }

            .hero-banner {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .hero-banner,
            .feature-cards,
            .form-card,
            .keuntungan-box {
                padding: 20px;
            }

            .feature-cards {
                grid-template-columns: 1fr;
            }

            .hero-banner h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 576px) {
            .hero-banner,
            .form-card,
            .keuntungan-box {
                padding: 15px;
            }

            .hero-banner h2 {
                font-size: 22px;
            }

            .btn-membership {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
    @endpush
</head>
<body>
    @section('content')
    <!-- MAIN CONTENT -->
    <div class="membership-wrapper">
        <!-- PAGE 2: REGISTRATION FORM -->
        <div id="page-registration-form">

            <div class="hero-banner">
                <a href="{{ route('customer.membership') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h2>Membership Smart Shuttle</h2>
                <p>Dapatkan poin, diskon, dan kemudahan booking setiap perjalanan</p>
            </div>

            <div class="feature-cards">
                <div class="feature-card">
                    <h5>Kode Member & QR</h5>
                    <p>Gunakan kode member atau QR untuk booking online maupun di loket.</p>
                </div>
                <div class="feature-card">
                    <h5>Poin & Level</h5>
                    <p>Kumpulkan poin dan naik level dari Bronze hingga Platinum.</p>
                </div>
                <div class="feature-card">
                    <h5>Diskon Member</h5>
                    <p>Gunakan Loyalty Point untuk potongan harga perjalanan.</p>
                </div>
            </div>

            <div class="membership-content">
                <div class="form-card">
                    <h4>Form Pendaftaran Membership</h4>
                    <p>Isi data berikut untuk menjadi member Smart Shuttle</p>

                    @if($errors->any())
                    <div class="error-message" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Terjadi kesalahan dalam pengisian form. Silakan periksa kembali.</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.membership.form.submit') }}" class="membership-form" id="membershipForm">
                        @csrf
                        <div class="form-row">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-input" id="formName"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ Auth::user()->name }}"
                                   readonly>
                        </div>

                        <div class="form-row">
                            <label class="form-label">
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" name="email" class="form-input" id="formEmail"
                                   placeholder="contoh@email.com"
                                   value="{{ Auth::user()->email }}"
                                   readonly>
                            <small class="form-hint">Email untuk login dan notifikasi</small>
                        </div>

                        <div class="form-row">
                            <label class="form-label">
                                No. Telepon <span class="required">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" class="form-input"
                                   placeholder="08xxxxxxxx" required
                                   value="{{ old('phone', Auth::user()->phone ?? '') }}">
                            <small class="form-hint">Contoh: 081234567890 (10-12 digit)</small>
                            @error('phone')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <label class="form-label">
                                Tanggal Lahir <span class="required">*</span>
                            </label>
                            <input type="date" id="birthdate" name="birthdate" class="form-input"
                                   value="{{ old('birthdate', Auth::user()->tanggal_lahir ?? '') }}" required>
                            <small class="form-hint">Minimal usia 17 tahun</small>
                            @error('birthdate')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <label class="form-label">
                                Jenis Kelamin <span class="required">*</span>
                            </label>
                            <select name="gender" class="form-select" id="gender" required>
                                <option value="" disabled {{ !old('gender') && !Auth::user()->jenis_kelamin ? 'selected' : '' }}>Pilih jenis kelamin</option>
                                <option value="L" {{ (old('gender') == 'L' || Auth::user()->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ (old('gender') == 'P' || Auth::user()->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="agree_terms" value="1" id="agree_terms" {{ old('agree_terms') ? 'checked' : '' }} required>
                                <span>Saya menyetujui <a href="{{ route('customer.syarat.ketentuan') }}" target="_blank" style="color: #ff5a1f; text-decoration: underline;">Syarat & Ketentuan</a> dan <a href="{{ route('customer.kebijakan.privasi') }}" target="_blank" style="color: #ff5a1f; text-decoration: underline;">Kebijakan Privasi</a> membership Smart Shuttle <span class="required">*</span></span>
                            </label>
                            @error('agree_terms')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-membership" id="submitButton">
                            <span id="buttonText">
                                <i class="fas fa-check-circle"></i> Daftar Membership & Lanjutkan Pembayaran
                            </span>
                            <span id="buttonLoading" style="display: none;">
                                <span class="spinner"></span> Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <div class="keuntungan-box">
                    <h4>Keuntungan Membership</h4>
                    <ul>
                        <li>Dapat Point setiap transaksi</li>
                        <li>Diskon khusus member</li>
                        <li>Booking lebih cepat (online / loket)</li>
                        <li>Naik level otomatis</li>
                        <li>Notifikasi promo eksklusif</li>
                        <li>Prioritas customer service</li>
                    </ul>

                    <div class="cost-card">
                        <p>💳 Biaya cetak kartu fisik:</p>
                        <h3>Rp 20.000</h3>
                        <p style="font-size: 11px; color: #9ca3af; margin-top: 5px;">Masa aktif 12 bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('membershipForm');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoading = document.getElementById('buttonLoading');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validasi form sebelum submit
                    const phone = document.getElementById('phone').value;
                    const birthdate = document.getElementById('birthdate').value;
                    const gender = document.getElementById('gender').value;
                    const agreeTerms = document.getElementById('agree_terms').checked;

                    // Validasi phone (10-12 digit angka)
                    const phoneRegex = /^[0-9]{10,12}$/;
                    if (!phone || !phoneRegex.test(phone)) {
                        e.preventDefault();
                        alert('Nomor telepon harus 10-12 digit angka (contoh: 081234567890)');
                        document.getElementById('phone').focus();
                        return;
                    }

                    // Validasi birthdate (minimal 17 tahun)
                    if (!birthdate) {
                        e.preventDefault();
                        alert('Tanggal lahir harus diisi');
                        document.getElementById('birthdate').focus();
                        return;
                    }

                    const birthDate = new Date(birthdate);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    if (age < 17) {
                        e.preventDefault();
                        alert('Anda harus berusia minimal 17 tahun untuk mendaftar membership.');
                        document.getElementById('birthdate').focus();
                        return;
                    }

                    // Validasi gender
                    if (!gender) {
                        e.preventDefault();
                        alert('Jenis kelamin harus dipilih');
                        document.getElementById('gender').focus();
                        return;
                    }

                    // Validasi agree terms
                    if (!agreeTerms) {
                        e.preventDefault();
                        alert('Anda harus menyetujui Syarat & Ketentuan dan Kebijakan Privasi');
                        document.getElementById('agree_terms').focus();
                        return;
                    }

                    // Show loading state
                    buttonText.style.display = 'none';
                    buttonLoading.style.display = 'flex';
                    submitButton.disabled = true;

                    // Form akan submit secara normal
                    console.log('Form sedang diproses...');
                });
            }

            // Format phone number input
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 12) value = value.substring(0, 12);
                    e.target.value = value;
                });
            }

            // Set max date for birthdate (minimal 17 tahun dari sekarang)
            const birthdateInput = document.getElementById('birthdate');
            if (birthdateInput) {
                const today = new Date();
                const maxDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate());
                const maxDateString = maxDate.toISOString().split('T')[0];
                birthdateInput.max = maxDateString;

                // Set default value jika user sudah punya data
                if (!birthdateInput.value && Auth::user() && Auth::user().tanggal_lahir) {
                    birthdateInput.value = "{{ Auth::user()->tanggal_lahir ?? '' }}";
                }
            }
        });

        // Tambahkan variabel Auth user untuk JavaScript (jika perlu)
        const Auth = {
            user: @json(Auth::user())
        };
    </script>
</body>
</html>
