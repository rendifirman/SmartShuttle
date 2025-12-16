<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smart Shuttle | Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html, body {
      height: 100%;
      font-family: 'Inter', sans-serif;
    }
    
    body {     
      background-image: url('/images/bgSmartshuttle.png');
      background-size: cover;
      background-position: center 35%;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    
    .glass-effect {
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .left-overlay {
      background: rgba(255, 254, 254, 0.73);
      backdrop-filter: blur(2px);
    }

    .right-overlay {
      background: rgba(59, 59, 59, 0.5);
    }
  </style>
</head>
<body>
  <div class="flex min-h-screen">
    <!-- LEFT SIDE -->
    <div class="w-1/2 flex flex-col justify-center px-16 relative left-overlay">
      <!-- Logo di kiri atas -->
      <div class="absolute -top-6 -left-4 flex items-center space-x-3">
        <div class="w-32 h-32 flex items-center justify-center">
          <img src="/images/smartshuttleogo.png" alt="Smart Shuttle" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Content -->
      <div class="relative z-10">
        <!-- Judul utama -->
        <div class="flex flex-col items-center mb-8">
          <div class="w-full text-center">
            <h1 class="text-5xl font-bold text-[#00215E] mb-2">Smart Shuttle</h1>
            <div class="w-24 h-1 bg-[#FF581E] mx-auto rounded-full"></div>
          </div>
        </div>

        <p class="text-[#00215E] text-lg mt-6 font-medium leading-relaxed text-center">
          Bergabung Bersama Kami<br>
          Masuk dan akses layanan terpadu kami
        </p>

        <div class="mt-12 space-y-6">
          <!-- Feature 1 -->
          <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
            <div class="w-12 h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-[#00215E]">Tiket Antar Kota</p>
              <p class="text-[#00215E]/70 text-sm">Jemput dan antar penumpang</p>
            </div>
          </div>

          <!-- Feature 2 -->
          <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
            <div class="w-12 h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-[#00215E]">Pengiriman Barang</p>
              <p class="text-[#00215E]/70 text-sm">Antar barang ke seluruh kota</p>
            </div>
          </div>

          <!-- Feature 3 -->
          <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
            <div class="w-12 h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-[#00215E]">Sewa Armada</p>
              <p class="text-[#00215E]/70 text-sm">Sewakan kendaraan Anda</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE FORM LOGIN -->
    <div class="w-1/2 flex flex-col justify-center px-20 text-white relative right-overlay">
      <!-- Logo kecil di pojok kanan atas -->
      <div class="absolute top-8 right-8">
        <div class="w-12 h-12 flex items-center justify-center">
          <!-- Logo kecil bisa ditambahkan di sini jika perlu -->
        </div>
      </div>
      
      <div class="flex flex-col items-center mb-8">
        <div class="w-full text-center">
          <h2 class="text-3xl font-bold mb-10 text-white">Masuk ke Akun Anda</h2>
        </div>
      </div>

      <!-- FORM -->
      <form action="{{ route('customer.login.post') }}" method="POST" class="space-y-5">
        @csrf
        
        <!-- Email -->
        <div>
          <label class="block mb-2 text-white/80 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Email
          </label>
          <input 
            type="email" 
            name="email" 
            class="w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300" 
            placeholder="Masukkan email Anda"
            required
          />
          @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label class="block mb-2 text-white/80 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Password
          </label>
          <input 
            type="password" 
            name="password" 
            class="w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300" 
            placeholder="Masukkan password Anda"
            required
          />
          @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex justify-between items-center">
          <label class="flex items-center">
            <input type="checkbox" name="remember" value="1" class="rounded text-[#00C8FF] focus:ring-[#00C8FF]">
            <span class="ml-2 text-white/80">Ingat saya</span>
          </label>
          
       <a href="{{ route('password.request') }}" class="text-[#FF581E] hover:underline">
    Lupa password?
</a>
        </div>

        @error('message')
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
          </div>
        @enderror

        <button 
          type="submit" 
          class="w-full bg-[#FF581E] hover:bg-[#FF581E] text-[#FFFF] font-bold py-3 rounded-lg mt-5 transition-all duration-300 transform hover:scale-105"
        >
          Masuk
        </button>
      </form>

      <!-- Link ke register -->
      <p class="mt-6 text-white/80 text-center">
        Belum punya akun? 
        <a href="{{ route('customer.register') }}" class="text-[#FF581E] font-semibold hover:text-[#FFFF] transition-colors">
          Daftar di sini
        </a>
      </p>

      <!-- Divider atau spacer untuk menjaga layout tetap rapi -->
      <div class="mt-8"></div>
    </div>
  </div>

</body>
</html>