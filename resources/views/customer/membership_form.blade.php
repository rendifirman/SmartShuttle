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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 300px;
            background: #00274D;
            color: #fff;
            padding: 30px 0;
            flex-shrink: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #FF6B2C;
            margin-bottom: 40px;
            padding: 0 20px;
            cursor: pointer;
            transition: transform 0.3s, color 0.3s;
        }

        .logo:hover {
            transform: scale(1.05);
            color: #FF8E53;
        }

        .menu {
            list-style: none;
            padding-left: 0;
        }

        .menu li {
            padding: 0;
            margin: 5px 15px;
            border-radius: 5px;
            overflow: hidden;
        }

        .menu a.menu-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px 30px;
            color: inherit;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
            width: 100%;
        }

        .menu a.menu-link:hover {
            background: rgba(255, 107, 44, 0.1);
        }

        .menu li.active a.menu-link {
            background: #FF6B2C;
            border-radius: 5px;
        }

        .menu-icon {
            width: 25px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-link {
            color: #ff6b6b !important;
        }

        .logout-link:hover {
            background: rgba(255, 107, 44, 0.1) !important;
        }

        .beranda-link {
            color: #FF6B2C !important;
        }

        .beranda-link:hover {
            background: rgba(255, 107, 44, 0.1) !important;
        }

        /* CONTENT AREA */
        .content {
            flex: 1;
            padding: 30px;
            position: relative;
            margin-left: 300px;
            width: calc(100% - 300px);
            min-height: 100vh;
        }

        /* HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            font-size: 28px;
            font-weight: 700;
            color: #00274D;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header i {
            color: #FF6B2C;
            font-size: 24px;
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF6B2C, #FF8E53);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .profile-icon:hover {
            transform: scale(1.05);
        }

        /* PAGE 2: REGISTRATION FORM STYLES */
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

        /* Responsive */
        @media (max-width: 992px) {
            .feature-cards,
            .membership-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-left: 0;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .hero-banner,
            .feature-cards,
            .form-card,
            .keuntungan-box {
                padding: 20px;
            }

            .feature-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .hero-banner,
            .form-card,
            .keuntungan-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <!-- LOGO YANG BISA DIKLIK UNTUK KE HALAMAN BERANDA -->
        <div class="logo" onclick="location.href='{{ route('customer.beranda') }}'">
            SMART SHUTTLE
        </div>

        <ul class="menu">
            <li>
                <a href="{{ route('customer.dashboardprofile') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-home"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('customer.profilcust') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-circle"></i></span>
                    <span>Profil Saya</span>
                </a>
            </li>

            <li>
                <a href="{{ route('customer.riwayat') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-history"></i></span>
                    <span>Riwayat Pesanan</span>
                </a>
            </li>

            <li class="active">
                <a href="{{ route('customer.membership') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-crown"></i></span>
                    <span>Membership</span>
                </a>
            </li>

            <!-- TOMBOL BERANDA DI BAWAH LOGOUT -->
            <li class="sidebar-footer">
                <a href="{{ route('customer.beranda') }}" class="menu-link beranda-link">
                    <span class="menu-icon"><i class="fas fa-arrow-left"></i></span>
                    <span>Beranda</span>
                </a>
            </li>

            <li class="sidebar-footer">
                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link logout-link">
                    <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CONTENT AREA -->
    <div class="content">
        <!-- HEADER -->
        <div class="top-header">
            <div class="header">
                <i class="fas fa-crown"></i>
                <span>Daftar Membership</span>
            </div>

            <div class="profile-icon" title="Lihat Profil" onclick="location.href='{{ route('customer.profilcust') }}'">
                <i class="fas fa-user"></i>
            </div>
        </div>

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
                                       value="{{ old('phone', $customer->phone ?? '') }}">
                                <small class="form-hint">Contoh: 081234567890</small>
                                @error('phone')
                                    <span style="color: #e53e3e; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <label class="form-label">
                                    Tanggal Lahir <span class="required">*</span>
                                </label>
                                <input type="date" id="birthdate" name="birthdate" class="form-input"
                                       value="{{ old('birthdate', $customer->birthdate ?? '') }}" required>
                                <small class="form-hint">Format: Tahun-Bulan-Hari (YYYY-MM-DD)</small>
                                @error('birthdate')
                                    <span style="color: #e53e3e; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <label class="form-label">
                                    Jenis Kelamin <span class="required">*</span>
                                </label>
                                <select name="gender" class="form-select" required>
                                    <option value="" disabled {{ !old('gender') ? 'selected' : '' }}>Pilih jenis kelamin</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <span style="color: #e53e3e; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }} required>
                                    <span>Saya menyetujui <a href="{{ route('customer.syarat.ketentuan') }}" target="_blank" style="color: #ff5a1f; text-decoration: underline;">Syarat & Ketentuan</a> dan <a href="{{ route('customer.kebijakan.privasi') }}" target="_blank" style="color: #ff5a1f; text-decoration: underline;">Kebijakan Privasi</a> membership Smart Shuttle <span class="required">*</span></span>
                                </label>
                                @error('agree_terms')
                                    <span style="color: #e53e3e; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn-membership">
                                <i class="fas fa-check-circle"></i> Daftar Membership
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 12) value = value.substring(0, 12);
                e.target.value = value;
            });
        }

        // Form validation
        const form = document.getElementById('membershipForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const phone = document.getElementById('phone').value;
                const birthdate = document.getElementById('birthdate').value;
                const gender = document.querySelector('select[name="gender"]').value;
                const agreeTerms = document.querySelector('input[name="agree_terms"]').checked;

                if (!phone || !birthdate || !gender || !agreeTerms) {
                    e.preventDefault();
                    alert('Harap lengkapi semua field yang wajib diisi!');
                    return;
                }

                if (!phone.match(/^[0-9]{10,12}$/)) {
                    e.preventDefault();
                    alert('Nomor telepon harus 10-12 digit angka');
                    return;
                }
            });
        }
    });
</script>
</body>
</html>
