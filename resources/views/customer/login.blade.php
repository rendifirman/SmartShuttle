<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Smart Shuttle | Login</title>
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

    /* Mobile logo and header */
    .mobile-header {
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 0 15px;
        background: rgba(255, 254, 254, 0.9);
        backdrop-filter: blur(5px);
        border-bottom: 1px solid rgba(0, 33, 94, 0.1);
      }
    }

    /* Password toggle */
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
    }

    .password-container {
      position: relative;
    }

    /* Style untuk alignment judul */
    .title-alignment {
      min-height: 180px; /* Sesuaikan tinggi dengan bagian kiri */
      display: flex;
      flex-direction: column;
      justify-content: flex-start; /* Mulai dari atas */
    }

    @media (max-width: 768px) {
      .title-alignment {
        min-height: auto;
        margin-top: 20px;
      }
    }

    /* Posisi teks deskripsi */
    .description-text {
      margin-top: 1.5rem; /* mt-6 */
      line-height: 1.6;
    }

    /* Alignment judul kanan agar sejajar dengan judul kiri */
    .right-title-align {
      margin-top: 4.5rem; /* desktop baseline */
    }

    @media (max-width: 768px) {
      .right-title-align {
        margin-top: 0;
      }
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

      .mobile-features-title {
        color: #00215E;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #FF581E;
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

      /* Divider styling for mobile */
      .mobile-or-divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
      }

      .mobile-or-line {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.3);
      }

      .mobile-or-text {
        padding: 0 15px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        white-space: nowrap;
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
        <h1 class="text-2xl font-bold text-[#00215E]">Smart Shuttle</h1>
        <div class="w-16 h-1 bg-[#FF581E] mx-auto rounded-full mt-1"></div>
      </div>
    </div>

    <div class="mobile-content-wrapper">
      <!-- Features Section for Mobile -->
      <div class="mobile-features-container">
        <!-- Header dengan kedua teks dalam satu frame -->
        <div class="mobile-features-header">
          <h2 class="mobile-features-main-title">Bergabung Bersama Kami</h2>
          <div class="mobile-features-subtitle">Masuk dan akses layanan terpadu kami</div>
        </div>

        <!-- Feature 1 -->
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

        <!-- Feature 2 -->
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

        <!-- Feature 3 -->
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
      </div>

      <!-- Form Section for Mobile -->
      <div class="mobile-form-container">
        <h2 class="mobile-form-title">Masuk ke Akun Anda</h2>
        <p class="mobile-form-subtitle">Masukkan kredensial Anda untuk mengakses layanan</p>

        <!-- TAMPILKAN PESAN SUKSES JIKA ADA -->
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
          {{ session('success') }}
        </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('customer.login.post') }}" method="POST" class="space-y-4">
          @csrf

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
            <input
              type="password"
              name="password"
              id="password"
              class="w-full p-3 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 mobile-input pr-10"
              placeholder="Masukkan password Anda"
              required
            />
            <button type="button" class="password-toggle" id="togglePassword">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
            @error('password')
              <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex flex-col justify-between items-start gap-3">
            <label class="flex items-center text-sm text-white">
              <input type="checkbox" name="remember" value="1" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-4 h-4">
              <span class="ml-2">Ingat saya</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-[#FF581E] hover:text-white font-medium text-sm">
              Lupa password?
            </a>
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
            Masuk
          </button>
        </form>

        <!-- TOMBOL GOOGLE LOGIN - Mobile Version -->
        <div class="mobile-or-divider">
          <div class="mobile-or-line"></div>
          <span class="mobile-or-text">Atau masuk dengan</span>
          <div class="mobile-or-line"></div>
        </div>

        <div class="mb-4">
          <a href="{{ route('login.google') }}"
             class="w-full flex justify-center items-center gap-3 py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF581E] transition-all duration-300 text-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span class="font-medium">Masuk dengan Google</span>
          </a>
        </div>

        <!-- Link ke register -->
        <p class="text-center text-sm text-white">
          Belum punya akun?
          <a href="{{ route('customer.register') }}" class="text-[#FF581E] font-semibold hover:text-white transition-colors">
            Daftar di sini
          </a>
        </p>
      </div>
    </div>
  </div>

  <!-- Desktop View (Original Code) -->
  <div class="hidden md:flex flex-col min-h-screen md:flex-row">
    <!-- LEFT SIDE - Hanya ditampilkan di desktop -->
    <div class="hidden md:w-1/2 md:flex flex-col justify-center px-8 lg:px-16 relative left-overlay">
      <!-- Logo di kiri atas -->
      <div class="absolute top-4 left-4 lg:top-6 lg:left-6 flex items-center space-x-3">
        <div class="w-20 h-20 lg:w-20 lg:h-20 flex items-center justify-center">
          <img src="/images/smartshuttlelogo.png" alt="Smart Shuttle" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Content -->
      <div class="relative z-10 mt-12 md:mt-0">
        <!-- Judul utama -->
        <div class="flex flex-col items-center mb-6 lg:mb-8">
          <div class="w-full text-center">
            <h1 class="text-3xl lg:text-5xl font-bold text-[#00215E] mb-2">Smart Shuttle</h1>
            <div class="w-16 lg:w-24 h-1 bg-[#FF581E] mx-auto rounded-full"></div>
          </div>
        </div>

        <p class="text-[#00215E] text-base lg:text-lg mt-4 lg:mt-6 font-medium leading-relaxed text-center">
          Bergabung Bersama Kami<br>
          Masuk dan akses layanan terpadu kami
        </p>

        <div class="mt-8 lg:mt-12 space-y-4 lg:space-y-6">
          <!-- Feature 1 -->
          <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
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

          <!-- Feature 2 -->
          <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
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

          <!-- Feature 3 -->
          <div class="flex items-center space-x-3 lg:space-x-4 glass-effect p-3 lg:p-4 rounded-xl">
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
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE FORM LOGIN - Desktop -->
    <div class="hidden md:flex md:w-1/2 items-start justify-center px-12 lg:px-20 text-white relative right-overlay min-h-screen pt-16">
      <div class="w-full max-w-md mx-auto">
        <div class="flex flex-col items-center mb-8">
          <div class="w-full text-center">
            <h2 class="text-4xl font-bold mb-4 text-white tracking-tight">
              Masuk ke Akun Anda
            </h2>
            <p class="text-white/70 text-base">
              Masukkan kredensial Anda untuk mengakses layanan
            </p>
          </div>
        </div>

        <!-- TAMPILKAN PESAN SUKSES JIKA ADA -->
        @if(session('success'))
        <div class="mb-4 mt-8 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-base">
          {{ session('success') }}
        </div>
        @endif

        <!-- FORM Desktop -->
        <form action="{{ route('customer.login.post') }}" method="POST" class="space-y-5 mt-8">
          @csrf

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
              class="w-full p-3 text-base rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30"
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
            <input
              type="password"
              name="password"
              id="password-desktop"
              class="w-full p-3 text-base rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30 pr-10"
              placeholder="Masukkan password Anda"
              required
            />
            <button type="button" class="password-toggle" id="togglePasswordDesktop">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIconDesktop">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
            @error('password')
              <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex justify-between items-center">
            <label class="flex items-center text-base">
              <input type="checkbox" name="remember" value="1" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-4 h-4">
              <span class="ml-2 text-white/80">Ingat saya</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-[#FF581E] hover:text-white hover:underline text-base">
              Lupa password?
            </a>
          </div>

          @error('message')
            <div class="bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg text-base" role="alert">
              <span class="block sm:inline">{{ $message }}</span>
            </div>
          @enderror

          <button
            type="submit"
            class="w-full bg-gradient-to-r from-[#FF581E] to-[#FF581E] hover:from-[#FF581E] hover:to-[#FF581E] text-white font-bold py-4 rounded-lg mt-5 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg hover:shadow-xl text-base"
            id="submitButtonDesktop"
          >
            Masuk
          </button>
        </form>

        <!-- TOMBOL GOOGLE LOGIN Desktop - Fixed Divider -->
        <div class="mt-6">
          <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center">
              <span class="px-4 bg-transparent text-white/80 text-sm">Atau masuk dengan</span>
            </div>
          </div>

          <div class="mt-2">
            <a href="{{ route('login.google') }}"
               class="w-full flex justify-center items-center gap-3 py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF581E] transition-all duration-300 text-base">
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              <span class="font-medium">Masuk dengan Google</span>
            </a>
          </div>
        </div>

        <!-- Link ke register Desktop -->
        <p class="mt-6 text-white/80 text-center text-base">
          Belum punya akun?
          <a href="{{ route('customer.register') }}" class="text-[#FF581E] font-semibold hover:text-white transition-colors">
            Daftar di sini
          </a>
        </p>
      </div>
    </div>
  </div>

  <!-- JavaScript untuk interaksi -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Password toggle untuk mobile
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('togglePassword');
      const eyeIcon = document.getElementById('eyeIcon');

      // Password toggle untuk desktop
      const passwordInputDesktop = document.getElementById('password-desktop');
      const togglePasswordDesktop = document.getElementById('togglePasswordDesktop');
      const eyeIconDesktop = document.getElementById('eyeIconDesktop');

      // Form elements
      const formMobile = document.querySelector('.mobile-main-container form');
      const submitButtonMobile = document.getElementById('submitButton');
      const formDesktop = document.querySelector('.right-overlay form');
      const submitButtonDesktop = document.getElementById('submitButtonDesktop');

      function togglePasswordVisibility(input, icon) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

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

      // Event listeners untuk mobile
      if (togglePassword) {
        togglePassword.addEventListener('click', () => togglePasswordVisibility(passwordInput, eyeIcon));
      }

      // Event listeners untuk desktop
      if (togglePasswordDesktop) {
        togglePasswordDesktop.addEventListener('click', () => togglePasswordVisibility(passwordInputDesktop, eyeIconDesktop));
      }

      // Form submission handling untuk mobile
      if (formMobile) {
        formMobile.addEventListener('submit', function() {
          submitButtonMobile.disabled = true;
          submitButtonMobile.innerHTML = 'Memproses...';
        });
      }

      // Form submission handling untuk desktop
      if (formDesktop) {
        formDesktop.addEventListener('submit', function() {
          submitButtonDesktop.disabled = true;
          submitButtonDesktop.innerHTML = 'Memproses...';
        });
      }

      // Prevent zoom on input focus on mobile
      const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
      inputs.forEach(input => {
        input.addEventListener('focus', () => {
          setTimeout(() => {
            window.scrollTo(0, 0);
            document.body.style.height = '100%';
            document.body.style.overflow = 'hidden';
          }, 100);
        });

        input.addEventListener('blur', () => {
          document.body.style.height = '';
          document.body.style.overflow = '';
        });
      });
    });
  </script>
</body>
</html>
