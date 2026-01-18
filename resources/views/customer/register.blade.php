<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Smart Shuttle | Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --primary-blue: #123352; /* Biru utama */
      --accent-orange: #FF581E; /* Oren */
      --white: #ffffff;
      --text-gray: #4a5568; /* teks abu */
    }

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
    }

    body {
      background-image: url('/images/bgSmartshuttle.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-color: #00215E; /* Fallback color */
    }

    @media (max-width: 768px) {
      body {
        background-position: 70% center;
        background-attachment: scroll;
      }
    }

    .glass-effect {
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
    }

    .left-overlay {
      background: rgba(255, 254, 254, 0.85);
    }

    .right-overlay {
      background: rgba(59, 59, 59, 0.7);
    }

    @media (max-width: 768px) {
      .right-overlay {
        background: rgba(59, 59, 59, 0.85);
      }
      .left-overlay {
        display: none;
      }
    }

    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(18, 51, 82, 0.95);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      overflow-y: auto;
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .modal-container {
      background: linear-gradient(135deg, var(--white) 0%, #f8fafc 100%);
      border-radius: 16px;
      width: 100%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      position: relative;
      border: 1px solid rgba(255, 88, 30, 0.15);
    }

    @media (max-width: 768px) {
      .modal-container {
        max-height: 85vh;
        border-radius: 12px;
      }
    }

    .modal-header {
      padding: 20px 25px 15px;
      background: linear-gradient(135deg, var(--primary-blue) 0%, #0f283f 100%);
      border-radius: 16px 16px 0 0;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
      border-bottom: 3px solid rgba(255, 88, 30, 0.95);
    }

    @media (max-width: 768px) {
      .modal-header {
        padding: 18px 20px 12px;
        border-radius: 12px 12px 0 0;
      }
    }

    .modal-header h3 {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--white);
      letter-spacing: 0.3px;
    }

    @media (max-width: 768px) {
      .modal-header h3 {
        font-size: 1.2rem;
      }
    }

    .modal-body {
      padding: 20px 25px 25px;
      color: var(--text-gray);
      line-height: 1.6;
      font-size: 0.9rem;
      background: transparent;
    }

    @media (max-width: 768px) {
      .modal-body {
        padding: 18px 20px 20px;
        font-size: 0.85rem;
        line-height: 1.5;
      }
    }

    .modal-body h4 {
      color: var(--text-gray);
      font-size: 1rem;
      font-weight: 600;
      margin-top: 1.2rem;
      margin-bottom: 0.5rem;
      padding-bottom: 0.4rem;
      border-bottom: 2px solid rgba(255, 88, 30, 0.25);
      position: relative;
    }

    @media (max-width: 768px) {
      .modal-body h4 {
        font-size: 0.95rem;
        margin-top: 1rem;
      }
    }

    .modal-body h4:after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -2px;
      width: 50px;
      height: 2px;
      background: var(--accent-orange);
    }

    .modal-body p {
      margin-bottom: 1rem;
      color: var(--text-gray);
    }

    .modal-body ul {
      margin-left: 1rem;
      margin-bottom: 1.2rem;
    }

    .modal-body li {
      margin-bottom: 0.5rem;
      position: relative;
      padding-left: 1.2rem;
      color: var(--text-gray);
    }

    @media (max-width: 768px) {
      .modal-body li {
        padding-left: 1rem;
        margin-bottom: 0.4rem;
      }
    }

    .modal-body li:before {
      content: '';
      position: absolute;
      left: 0;
      top: 0.5rem;
      width: 6px;
      height: 6px;
      background: var(--accent-orange);
      border-radius: 50%;
    }

    .close-btn {
      position: absolute;
      top: 12px;
      right: 12px;
      background: rgba(255, 255, 255, 0.9);
      border: none;
      color: var(--primary-blue);
      font-size: 1.2rem;
      cursor: pointer;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.25s;
      z-index: 11;
      box-shadow: 0 4px 12px rgba(18,51,82,0.08);
    }

    @media (max-width: 768px) {
      .close-btn {
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        font-size: 1rem;
      }
    }

    .close-btn:hover {
      transform: rotate(90deg);
      background: var(--accent-orange);
      color: var(--white);
    }

    .agree-btn {
      background: linear-gradient(135deg, var(--accent-orange) 0%, #e04d19 100%);
      color: var(--white);
      border: none;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.25s;
      margin-top: 20px;
      width: 100%;
      font-size: 0.95rem;
      letter-spacing: 0.3px;
      box-shadow: 0 6px 15px rgba(255,88,30,0.18);
    }

    @media (max-width: 768px) {
      .agree-btn {
        padding: 14px 20px;
        font-size: 0.9rem;
        margin-top: 15px;
        border-radius: 8px;
      }
    }

    .agree-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(255,88,30,0.22);
    }

    .scroll-indicator {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      color: #718096;
      font-size: 0.75rem;
      display: flex;
      align-items: center;
      gap: 6px;
      animation: bounce 2s infinite;
    }

    @media (max-width: 768px) {
      .scroll-indicator {
        font-size: 0.7rem;
        bottom: 8px;
      }
    }

    @keyframes bounce {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(-5px); }
    }

    /* Password Container Styling - PERBAIKAN */
    .password-container {
      position: relative;
    }

    .password-container .relative {
      position: relative;
    }

    /* Desktop password toggle */
    .right-overlay .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
      padding: 5px;
      z-index: 10;
      transition: color 0.2s;
    }

    .right-overlay .password-toggle:hover {
      color: var(--primary-blue);
    }

    /* Input dengan padding untuk ikon mata */
    .right-overlay .password-container input {
      padding-right: 45px !important;
    }

    /* Mobile password toggle */
    .mobile-form-container .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
      padding: 5px;
      z-index: 10;
      transition: color 0.2s;
    }

    .mobile-form-container .password-toggle:hover {
      color: var(--primary-blue);
    }

    /* Input mobile dengan padding untuk ikon mata */
    .mobile-form-container .password-container input {
      padding-right: 45px !important;
    }

    /* Password strength indicator */
    .password-strength {
      height: 4px;
      border-radius: 2px;
      margin-top: 5px;
      transition: all 0.3s;
    }

    .strength-weak {
      width: 25%;
      background: #ef4444;
    }
    .strength-medium {
      width: 50%;
      background: #f59e0b;
    }
    .strength-strong {
      width: 75%;
      background: #10b981;
    }
    .strength-very-strong {
      width: 100%;
      background: #059669;
    }

    /* Mobile container styling */
    @media (max-width: 768px) {
      .mobile-main-container {
        background: rgba(0, 33, 94, 0.8);
        min-height: 100vh;
        padding-bottom: 20px;
      }

      .mobile-content-wrapper {
        padding: 0 16px 30px;
      }

      /* Features section for mobile */
      .mobile-features-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        padding: 25px 20px;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(0, 33, 94, 0.1);
      }

      .mobile-features-header {
        text-align: center;
        margin-bottom: 20px;
      }

      .mobile-features-main-title {
        color: #00215E;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 5px;
      }

      .mobile-features-subtitle {
        color: #FF581E;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
      }

      .mobile-feature-item {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        border-left: 4px solid #00215E;
        box-shadow: 0 2px 6px rgba(0, 33, 94, 0.08);
      }

      .mobile-feature-content {
        display: flex;
        align-items: center;
      }

      .mobile-feature-icon {
        background: #00215E;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
      }

      .mobile-feature-title {
        color: #00215E;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 4px;
      }

      .mobile-feature-desc {
        color: #4a5568;
        font-size: 0.85rem;
        line-height: 1.4;
      }

      /* Form section for mobile */
      .mobile-form-container {
        background: rgba(59, 59, 59, 0.9);
        border-radius: 16px;
        padding: 25px 20px;
        margin-top: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      }

      .mobile-form-title {
        text-align: center;
        color: white;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .mobile-form-subtitle {
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        margin-bottom: 20px;
      }

      .mobile-input {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(0, 40, 100, 0.2) !important;
        color: #333 !important;
      }

      .mobile-input::placeholder {
        color: #666 !important;
      }

      .mobile-label {
        color: white !important;
        font-weight: 500;
        margin-bottom: 8px;
      }
    }

    /* Mobile logo and header */
    .mobile-header {
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 20px 15px;
        background: linear-gradient(135deg, #00215E 0%, #123352 100%);
        border-bottom: 3px solid #FF581E;
        text-align: center;
      }
    }

  </style>
</head>
<body class="bg-gray-50">
  <!-- Mobile View -->
  <div class="md:hidden mobile-main-container">
    <!-- Mobile Header - Hanya Smart Shuttle -->
    <div class="mobile-header">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-white">Smart Shuttle</h1>
        <div class="w-16 h-1 bg-[#FF581E] mx-auto rounded-full mt-1"></div>
      </div>
    </div>

    <div class="mobile-content-wrapper">
      <!-- Features Section for Mobile -->
      <div class="mobile-features-container">
        <!-- Header dengan kedua teks dalam satu frame -->
        <div class="mobile-features-header">
          <h2 class="mobile-features-main-title">Bergabung Bersama Kami</h2>
          <div class="mobile-features-subtitle">Buat akun dan akses layanan terpadu kami</div>
        </div>

        <!-- FEATURE DINAMIS DARI DATABASE -->
        @if(isset($layanan) && count($layanan) > 0)
          @foreach($layanan as $item)
          <div class="mobile-feature-item">
            <div class="mobile-feature-content">
              <div class="mobile-feature-icon">
                @if($item->layanan_icon)
                  <img src="{{ asset('storage/' . $item->layanan_icon) }}" alt="{{ $item->layanan_nama }}" class="w-5 h-5 text-white">
                @else
                  <!-- Default icon untuk tiket -->
                  @if($item->layanan_kode == 'tiket')
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <!-- Default icon untuk pengiriman -->
                  @elseif($item->layanan_kode == 'pengiriman')
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <!-- Default icon untuk sewa -->
                  @else
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  @endif
                @endif
              </div>
              <div>
                <p class="mobile-feature-title">{{ $item->layanan_nama }}</p>
                <p class="mobile-feature-desc">{{ $item->layanan_deskripsi }}</p>
                <!-- Tampilkan harga range jika ada -->
                @if($item->layanan_harga_min && $item->layanan_harga_max)
                <p class="text-[#00215E]/60 text-xs mt-1">
                  Mulai dari Rp {{ number_format($item->layanan_harga_min, 0, ',', '.') }}
                </p>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        @else
          <!-- FALLBACK JIKA TIDAK ADA DATA -->
          <div class="mobile-feature-item">
            <div class="mobile-feature-content">
              <div class="mobile-feature-icon">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <p class="mobile-feature-title">Tiket Antar Kota</p>
                <p class="mobile-feature-desc">Jemput dan antar penumpang</p>
              </div>
            </div>
          </div>

          <div class="mobile-feature-item">
            <div class="mobile-feature-content">
              <div class="mobile-feature-icon">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <div>
                <p class="mobile-feature-title">Pengiriman Barang</p>
                <p class="mobile-feature-desc">Antar barang ke seluruh kota</p>
              </div>
            </div>
          </div>

          <div class="mobile-feature-item">
            <div class="mobile-feature-content">
              <div class="mobile-feature-icon">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
              </div>
              <div>
                <p class="mobile-feature-title">Sewa Armada</p>
                <p class="mobile-feature-desc">Sewakan kendaraan Anda</p>
              </div>
            </div>
          </div>
        @endif
      </div>

      <!-- Form Section for Mobile -->
      <div class="mobile-form-container">
        <h2 class="mobile-form-title">Daftar Akun Anda</h2>
        <p class="mobile-form-subtitle">Isi data diri Anda untuk mulai menggunakan layanan</p>

        <form action="{{ route('customer.register.post') }}" method="POST" class="space-y-4">
          @csrf

          <!-- Name -->
          <div>
            <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Nama Lengkap
            </label>
            <input
              type="text"
              name="name"
              class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
              placeholder="Masukkan nama lengkap Anda"
              required
              value="{{ old('name') }}"
            />
            @error('name')
              <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              Email
            </label>
            <input
              type="email"
              name="email"
              class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
              placeholder="Masukkan email Anda"
              required
              value="{{ old('email') }}"
            />
            @error('email')
              <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div class="password-container">
            <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
              Password
            </label>
            <div class="relative">
              <input
                type="password"
                name="password"
                id="password"
                class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
                placeholder="Buat password (minimal 8 karakter)"
                required
                minlength="8"
              />
              <button type="button" class="password-toggle" id="togglePassword">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>
            <div class="password-strength" id="passwordStrength"></div>
            @error('password')
              <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div class="password-container">
            <label class="block mb-2 mobile-label flex items-center gap-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
              Konfirmasi Password
            </label>
            <div class="relative">
              <input
                type="password"
                name="password_confirmation"
                id="confirmPassword"
                class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input"
                placeholder="Konfirmasi password Anda"
                required
              />
              <button type="button" class="password-toggle" id="toggleConfirmPassword">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeConfirmIcon">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>
            <div class="text-xs mt-1 text-white/60" id="passwordMatch"></div>
          </div>

          <!-- Terms and Conditions -->
          <div class="pt-2">
            <label class="flex items-start text-white">
              <input type="checkbox" name="terms" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-4 h-4 mt-1" required>
              <span class="ml-3 text-xs leading-tight">
                Saya menyetujui
                <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="terms-link">Syarat & Ketentuan</a>
                dan
                <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="privacy-link">Kebijakan Privasi</a>
              </span>
            </label>
            @error('terms')
              <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          @error('message')
            <div class="bg-red-900/50 border border-red-700 text-red-200 px-3 py-2 rounded-lg text-sm" role="alert">
              <span class="block">{{ $message }}</span>
            </div>
          @enderror

          <button
            type="submit"
            class="w-full bg-gradient-to-r from-[#FF581E] to-[#FF581E] text-white font-semibold py-3 rounded-lg mt-3 transition-all duration-300 active:scale-95 shadow-lg text-sm"
            id="submitButton"
          >
            Daftar Sekarang
          </button>
        </form>

        <!-- Link ke login -->
        <p class="mt-5 text-center text-sm text-white">
          Sudah punya akun?
          <a href="{{ route('customer.login') }}" class="text-[#FF581E] font-semibold hover:text-white transition-colors">
            Masuk di sini
          </a>
        </p>
      </div>
    </div>
  </div>

  <!-- Desktop View (Original Code) -->
  <div class="hidden md:flex flex-col min-h-screen md:flex-row">
    <!-- LEFT SIDE - Desktop Only -->
    <div class="hidden md:w-1/2 md:flex flex-col px-8 lg:px-16 relative left-overlay">
      <!-- Logo di kiri atas -->
      <div class="absolute top-4 left-4 lg:top-6 lg:left-6 flex items-center space-x-3">
        <div class="w-20 h-20 lg:w-20 lg:h-20 flex items-center justify-center">
          <img src="/images/smartshuttlelogo.png" alt="Smart Shuttle" class="w-full h-full object-contain">
        </div>
      </div>
      <!-- Content -->
      <div class="relative z-10 pt-24 lg:pt-28">
        <!-- Judul utama -->
        <div class="flex flex-col items-center mb-8">
          <div class="w-full text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-[#00215E] mb-2">Smart Shuttle</h1>
            <div class="w-20 lg:w-24 h-1 bg-[#FF581E] mx-auto rounded-full"></div>
          </div>
        </div>

        <p class="text-[#00215E] text-lg mt-6 font-medium leading-relaxed text-center">
          Bergabung Bersama Kami<br>
          Buat akun dan akses layanan terpadu kami
        </p>

        <div class="mt-10 space-y-5">
          <!-- FEATURE DINAMIS DARI DATABASE -->
          @if(isset($layanan) && count($layanan) > 0)
            @foreach($layanan as $item)
            <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
              <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                @if($item->layanan_icon)
                  <img src="{{ asset('storage/' . $item->layanan_icon) }}" alt="{{ $item->layanan_nama }}" class="w-5 h-5 lg:w-6 lg:h-6">
                @else
                  <!-- Default icon untuk tiket -->
                  @if($item->layanan_kode == 'tiket')
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <!-- Default icon untuk pengiriman -->
                  @elseif($item->layanan_kode == 'pengiriman')
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <!-- Default icon untuk sewa -->
                  @else
                  <svg class="w-5 h-5 lg:w-6 lg-h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  @endif
                @endif
              </div>
              <div>
                <p class="font-semibold text-[#00215E] text-sm lg:text-base">{{ $item->layanan_nama }}</p>
                <p class="text-[#00215E]/70 text-xs lg:text-sm">{{ $item->layanan_deskripsi }}</p>
                <!-- Tampilkan harga range jika ada -->
                @if($item->layanan_harga_min && $item->layanan_harga_max)
                <p class="text-[#00215E]/60 text-xs mt-1">
                  Mulai dari Rp {{ number_format($item->layanan_harga_min, 0, ',', '.') }}
                </p>
                @endif
              </div>
            </div>
            @endforeach
          @else
            <!-- FALLBACK JIKA TIDAK ADA DATA -->
            <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
              <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-[#00215E] text-sm lg:text-base">Tiket Antar Kota</p>
                <p class="text-[#00215E]/70 text-xs lg:text-sm">Jemput dan antar penumpang</p>
              </div>
            </div>

            <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
              <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-[#00215E] text-sm lg:text-base">Pengiriman Barang</p>
                <p class="text-[#00215E]/70 text-xs lg:text-sm">Antar barang ke seluruh kota</p>
              </div>
            </div>

            <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
              <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-[#00215E] text-sm lg:text-base">Sewa Armada</p>
                <p class="text-[#00215E]/70 text-xs lg:text-sm">Sewakan kendaraan Anda</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE FORM - Desktop -->
    <div class="hidden md:flex md:w-1/2 items-center justify-center px-12 lg:px-20 text-white relative right-overlay min-h-screen md:min-h-0">
      <div class="w-full max-w-md mx-auto md:mx-0 py-8 md:py-0">
        <!-- Tambahkan margin atas untuk desktop -->
        <div class="pt-24 lg:pt-28">
          <!-- JUDUL DI TENGAH -->
          <div class="flex flex-col items-center mb-8">
            <div class="w-full text-center">
              <h2 class="text-4xl font-bold mb-4 text-white tracking-tight">Daftar Akun Anda</h2>
              <p class="text-white/70 text-base mb-6">
                Isi data diri Anda untuk mulai menggunakan layanan
              </p>
            </div>
          </div>

          <form action="{{ route('customer.register.post') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
              <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Nama Lengkap
              </label>
              <input
                type="text"
                name="name"
                class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30 text-base"
                placeholder="Masukkan nama lengkap Anda"
                required
                value="{{ old('name') }}"
              />
              @error('name')
                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Email -->
            <div>
              <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Email
              </label>
              <input
                type="email"
                name="email"
                class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30 text-base"
                placeholder="Masukkan email Anda"
                required
                value="{{ old('email') }}"
              />
              @error('email')
                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="password-container">
              <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Password
              </label>
              <div class="relative">
                <input
                  type="password"
                  name="password"
                  id="password-desktop"
                  class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30 text-base"
                  placeholder="Buat password (minimal 8 karakter)"
                  required
                  minlength="8"
                />
                <button type="button" class="password-toggle" id="togglePasswordDesktop">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIconDesktop">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
              <div class="password-strength" id="passwordStrengthDesktop"></div>
              @error('password')
                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="password-container">
              <label class="block mb-2 text-white/80 flex items-center gap-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Konfirmasi Password
              </label>
              <div class="relative">
                <input
                  type="password"
                  name="password_confirmation"
                  id="confirmPassword-desktop"
                  class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30 text-base"
                  placeholder="Konfirmasi password Anda"
                  required
                />
                <button type="button" class="password-toggle" id="toggleConfirmPasswordDesktop">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeConfirmIconDesktop">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
              <div class="text-sm mt-1 text-white/60" id="passwordMatchDesktop"></div>
            </div>

            <!-- Terms and Conditions -->
            <div class="pt-2">
              <label class="flex items-start text-white/80">
                <input type="checkbox" name="terms" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-5 h-5 mt-1" required>
                <span class="ml-3 text-sm leading-tight">
                  Saya menyetujui
                  <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="terms-link-desktop">Syarat & Ketentuan</a>
                  dan
                  <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="privacy-link-desktop">Kebijakan Privasi</a>
                </span>
              </label>
              @error('terms')
                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            @error('message')
              <div class="bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg text-base" role="alert">
                <span class="block sm:inline">{{ $message }}</span>
              </div>
            @enderror

            <button
              type="submit"
              class="w-full bg-gradient-to-r from-[#FF581E] to-[#FF581E] hover:from-[#FF581E] hover:to-[#FF581E] text-white font-bold py-4 rounded-lg mt-8 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg hover:shadow-xl text-base"
              id="submitButtonDesktop"
            >
              Daftar Sekarang
            </button>
          </form>

          <!-- Link ke login -->
          <p class="mt-8 text-white/80 text-center text-base">
            Sudah punya akun?
            <a href="{{ route('customer.login') }}" class="text-[#FF581E] font-semibold hover:text-white transition-colors">
              Masuk di sini
            </a>
          </p>
        </div>

        <!-- Spacer untuk desktop -->
        <div class="mt-8"></div>
      </div>
    </div>
  </div>

  <!-- MODAL UNTUK SYARAT & KETENTUAN -->
  <div class="modal-overlay" id="termsModal">
    <div class="modal-container">
      <button class="close-btn" id="closeModal">&times;</button>

      <div class="modal-header">
        <h3 id="modalTitle">Syarat & Ketentuan</h3>
      </div>

      <div class="modal-body">
        <div id="modalContent">
          <!-- Konten akan diisi oleh JavaScript -->
        </div>
        <div class="scroll-indicator">
          <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
          </svg>
          Scroll untuk melanjutkan
        </div>
        <button class="agree-btn" id="agreeBtn">
          <svg class="w-4 h-4 md:w-5 md:h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Saya Setuju
        </button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Function untuk menangani mobile dan desktop
      function initializeForm(isMobile = true) {
        const form = isMobile ? document.querySelector('.mobile-main-container form') : document.querySelector('.right-overlay form');
        const termsLink = isMobile ? document.getElementById('terms-link') : document.getElementById('terms-link-desktop');
        const privacyLink = isMobile ? document.getElementById('privacy-link') : document.getElementById('privacy-link-desktop');
        const passwordInput = isMobile ? document.getElementById('password') : document.getElementById('password-desktop');
        const confirmPasswordInput = isMobile ? document.getElementById('confirmPassword') : document.getElementById('confirmPassword-desktop');
        const passwordStrength = isMobile ? document.getElementById('passwordStrength') : document.getElementById('passwordStrengthDesktop');
        const passwordMatch = isMobile ? document.getElementById('passwordMatch') : document.getElementById('passwordMatchDesktop');
        const togglePassword = isMobile ? document.getElementById('togglePassword') : document.getElementById('togglePasswordDesktop');
        const toggleConfirmPassword = isMobile ? document.getElementById('toggleConfirmPassword') : document.getElementById('toggleConfirmPasswordDesktop');
        const eyeIcon = isMobile ? document.getElementById('eyeIcon') : document.getElementById('eyeIconDesktop');
        const eyeConfirmIcon = isMobile ? document.getElementById('eyeConfirmIcon') : document.getElementById('eyeConfirmIconDesktop');
        const submitButton = isMobile ? document.getElementById('submitButton') : document.getElementById('submitButtonDesktop');
        const termsCheckbox = form ? form.querySelector('input[name="terms"]') : null;

        if (!form) return;

        // Password strength checker
        function checkPasswordStrength(password) {
          let strength = 0;

          if (password.length >= 8) strength++;
          if (password.match(/[a-z]+/)) strength++;
          if (password.match(/[A-Z]+/)) strength++;
          if (password.match(/[0-9]+/)) strength++;
          if (password.match(/[$@#&!]+/)) strength++;

          return strength;
        }

        function updatePasswordStrength() {
          const password = passwordInput.value;
          const strength = checkPasswordStrength(password);

          // Reset classes
          passwordStrength.className = 'password-strength';

          if (password.length === 0) {
            passwordStrength.style.width = '0%';
            return;
          }

          switch(strength) {
            case 0:
            case 1:
              passwordStrength.classList.add('strength-weak');
              break;
            case 2:
              passwordStrength.classList.add('strength-medium');
              break;
            case 3:
            case 4:
              passwordStrength.classList.add('strength-strong');
              break;
            case 5:
              passwordStrength.classList.add('strength-very-strong');
              break;
          }
        }

        function checkPasswordMatch() {
          const password = passwordInput.value;
          const confirmPassword = confirmPasswordInput.value;

          if (confirmPassword.length === 0) {
            passwordMatch.textContent = '';
            return;
          }

          if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Password cocok';
            passwordMatch.style.color = '#10b981';
          } else {
            passwordMatch.textContent = '✗ Password tidak cocok';
            passwordMatch.style.color = '#ef4444';
          }
        }

        // Toggle password visibility
        function togglePasswordVisibility(input, icon) {
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);

          // Toggle icon
          if (type === 'text') {
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
            `;
          } else {
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
          }
        }

        // Form validation
        form.addEventListener('submit', function(e) {
          const password = passwordInput.value;
          const confirmPassword = confirmPasswordInput.value;

          if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Harap setujui Syarat & Ketentuan dan Kebijakan Privasi');
            termsCheckbox.focus();
            return;
          }

          if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            confirmPasswordInput.focus();
            return;
          }

          if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal harus 8 karakter!');
            passwordInput.focus();
            return;
          }

          // Disable button dan tampilkan loading state
          submitButton.disabled = true;
          submitButton.innerHTML = 'Memproses Pendaftaran...';
          if (isMobile) {
            submitButton.classList.remove('active:scale-95');
          } else {
            submitButton.classList.remove('hover:scale-[1.02]');
          }
        });

        // Event listeners for password strength and match
        if (passwordInput && passwordStrength) {
          passwordInput.addEventListener('input', updatePasswordStrength);
          passwordInput.addEventListener('input', checkPasswordMatch);
        }
        if (confirmPasswordInput) {
          confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        }

        // Event listeners for toggle password visibility
        if (togglePassword && eyeIcon) {
          togglePassword.addEventListener('click', () => togglePasswordVisibility(passwordInput, eyeIcon));
        }
        if (toggleConfirmPassword && eyeConfirmIcon) {
          toggleConfirmPassword.addEventListener('click', () => togglePasswordVisibility(confirmPasswordInput, eyeConfirmIcon));
        }

        // Initialize password strength display
        if (passwordInput && passwordStrength) {
          updatePasswordStrength();
        }
      }

      // Initialize mobile form
      initializeForm(true);

      // Initialize desktop form
      initializeForm(false);

      // Modal handling (sama untuk mobile dan desktop)
      const termsModal = document.getElementById('termsModal');
      const closeModal = document.getElementById('closeModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalContent = document.getElementById('modalContent');
      const agreeBtn = document.getElementById('agreeBtn');

      // Konten dari database (dikirim dari controller)
      const termsContent = @json($syaratKetentuan->sk_konten_html ?? '');
      const privacyContent = @json($kebijakanPrivasi->kp_konten_html ?? '');

      // Judul dari database
      const termsTitle = @json($syaratKetentuan->sk_judul ?? 'Syarat & Ketentuan');
      const privacyTitle = @json($kebijakanPrivasi->kp_judul ?? 'Kebijakan Privasi');

      // Fallback content jika database kosong
      const fallbackTermsContent = `
        <div class="text-center py-6 md:py-8">
          <p class="text-gray-500 mb-4 text-sm md:text-base">Syarat dan ketentuan tidak tersedia.</p>
          <div class="mt-6 md:mt-8 p-3 md:p-4" style="background:rgba(18,51,82,0.05); border-radius:8px; border:1px solid rgba(18,51,82,0.06);">
            <p class="text-xs md:text-sm" style="color:var(--text-gray);">
              Dengan mencentang persetujuan, Anda mengkonfirmasi telah membaca dan memahami seluruh ketentuan yang berlaku.
            </p>
          </div>
        </div>
      `;

      const fallbackPrivacyContent = `
        <div class="text-center py-6 md:py-8">
          <p class="text-gray-500 mb-4 text-sm md:text-base">Kebijakan privasi tidak tersedia.</p>
          <div class="mt-6 md:mt-8 p-3 md:p-4" style="background:rgba(18,51,82,0.05); border-radius:8px; border:1px solid rgba(18,51,82,0.06);">
            <p class="text-xs md:text-sm" style="color:var(--text-gray);">
              Data Anda akan dilindungi sesuai dengan standar keamanan yang berlaku.
            </p>
          </div>
        </div>
      `;

      // Open modal dengan konten yang sesuai
      function openModal(type, title, content) {
        modalTitle.textContent = title;

        // Jika konten kosong, gunakan fallback
        if (!content || content.trim() === '') {
          if (type === 'terms') {
            modalContent.innerHTML = fallbackTermsContent;
          } else {
            modalContent.innerHTML = fallbackPrivacyContent;
          }
        } else {
          modalContent.innerHTML = content;
        }

        termsModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Scroll to top of modal content
        modalContent.scrollTop = 0;
      }

      // Event listener untuk link Syarat & Ketentuan
      const termsLinks = document.querySelectorAll('#terms-link, #terms-link-desktop');
      termsLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          openModal('terms', termsTitle, termsContent);
        });
      });

      // Event listener untuk link Kebijakan Privasi
      const privacyLinks = document.querySelectorAll('#privacy-link, #privacy-link-desktop');
      privacyLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          openModal('privacy', privacyTitle, privacyContent);
        });
      });

      // Event listener untuk tombol close modal
      closeModal.addEventListener('click', function() {
        termsModal.classList.remove('active');
        document.body.style.overflow = 'auto';
      });

      // Event listener untuk tombol setuju
      agreeBtn.addEventListener('click', function() {
        termsModal.classList.remove('active');
        document.body.style.overflow = 'auto';

        // Centang checkbox terms untuk semua form
        const allTermsCheckboxes = document.querySelectorAll('input[name="terms"]');
        allTermsCheckboxes.forEach(checkbox => {
          checkbox.checked = true;
        });

        // Smooth scroll ke form aktif
        const activeForm = document.querySelector('form');
        if (activeForm) {
          const checkbox = activeForm.querySelector('input[name="terms"]');
          if (checkbox) {
            checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Highlight briefly
            checkbox.parentElement.style.backgroundColor = 'rgba(255, 88, 30, 0.1)';
            setTimeout(() => {
              checkbox.parentElement.style.backgroundColor = '';
            }, 1000);
          }
        }
      });

      // Event listener untuk klik di luar modal
      termsModal.addEventListener('click', function(e) {
        if (e.target === termsModal) {
          termsModal.classList.remove('active');
          document.body.style.overflow = 'auto';
        }
      });

      // Event listener untuk tombol ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && termsModal.classList.contains('active')) {
          termsModal.classList.remove('active');
          document.body.style.overflow = 'auto';
        }
      });

      // HAPUS KODE YANG MENCEGAH SCROLL DI MOBILE
      // Kode ini dihapus karena menyebabkan masalah:
      // 1. Input focus tidak bisa di-scroll
      // 2. Page stuck dan harus refresh
      // 3. User tidak bisa scroll untuk melihat bagian lain form

    });
  </script>
</body>
</html>
