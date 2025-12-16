<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    
    /* Modal Styles - menggunakan warna yang diminta */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      /* gunakan warna biru utama dengan opacity untuk overlay */
      background: rgba(18, 51, 82, 0.9); /* var(--primary-blue) */
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    
    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    
    .modal-container {
      background: linear-gradient(135deg, var(--white) 0%, #f8fafc 100%);
      border-radius: 20px;
      width: 100%;
      max-width: 600px;
      max-height: 85vh;
      overflow-y: auto;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
      position: relative;
      /* border tipis dengan warna oranye yang lembut */
      border: 1px solid rgba(255, 88, 30, 0.15);
    }
    
    .modal-header {
      padding: 30px 40px 20px;
      /* header gelap biru dengan aksen oranye halus */
      background: linear-gradient(135deg, var(--primary-blue) 0%, #0f283f 100%);
      border-radius: 20px 20px 0 0;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
      /* garis bawah oranye sebagai aksen */
      border-bottom: 3px solid rgba(255, 88, 30, 0.95);
    }
    
    .modal-header h3 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--white);
      letter-spacing: 0.5px;
    }
    
    .modal-body {
      padding: 30px 40px 40px;
      color: var(--text-gray);
      line-height: 1.7;
      font-size: 0.95rem;
      background: transparent;
    }
    
    .modal-body h4 {
      /* teks tetap abu, namun garis aksen oranye */
      color: var(--text-gray);
      font-size: 1.1rem;
      font-weight: 600;
      margin-top: 1.6rem;
      margin-bottom: 0.6rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid rgba(255, 88, 30, 0.25);
      position: relative;
    }
    
    .modal-body h4:after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -2px;
      width: 60px;
      height: 2px;
      background: var(--accent-orange);
    }
    
    .modal-body p {
      margin-bottom: 1.2rem;
      color: var(--text-gray);
    }
    
    .modal-body ul {
      margin-left: 1.2rem;
      margin-bottom: 1.5rem;
    }
    
    .modal-body li {
      margin-bottom: 0.6rem;
      position: relative;
      padding-left: 1.5rem;
      color: var(--text-gray);
    }
    
    .modal-body li:before {
      content: '';
      position: absolute;
      left: 0;
      top: 0.6rem;
      width: 8px;
      height: 8px;
      /* bullet oranye untuk aksen */
      background: var(--accent-orange);
      border-radius: 50%;
    }
    
    .close-btn {
      position: absolute;
      top: 18px;
      right: 18px;
      background: rgba(255, 255, 255, 0.9);
      border: none;
      color: var(--primary-blue);
      font-size: 1.2rem;
      cursor: pointer;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.25s;
      z-index: 11;
      box-shadow: 0 6px 18px rgba(18,51,82,0.08);
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
      padding: 14px 40px;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.25s;
      margin-top: 30px;
      width: 100%;
      font-size: 1rem;
      letter-spacing: 0.5px;
      box-shadow: 0 8px 20px rgba(255,88,30,0.18);
    }
    
    .agree-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(255,88,30,0.22);
    }
    
    .scroll-indicator {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      color: #718096;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 8px;
      animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(-5px); }
    }
    
    @media (max-width: 768px) {
      .modal-container {
        max-width: 95%;
        max-height: 90vh;
      }
      
      .modal-header {
        padding: 25px 30px 15px;
      }
      
      .modal-body {
        padding: 25px 30px 30px;
      }
      
      .close-btn {
        top: 20px;
        right: 20px;
        width: 32px;
        height: 32px;
      }
    }
  </style>
</head>
<body>
  <div class="flex min-h-screen">
    <!-- LEFT SIDE -->
    <div class="w-1/2 flex flex-col justify-center px-16 relative left-overlay">
      <!-- HAPUS LOGO -->
      
      <!-- Content - DINAIIKKAN TAPI SEDIKIT AJA -->
      <div class="relative z-10 -mt-4"> 
        <!-- Judul utama -->
        <div class="flex flex-col items-center mb-8">
          <div class="w-full text-center">
            <h1 class="text-5xl font-bold text-[#00215E] mb-2">Smart Shuttle</h1>
            <div class="w-24 h-1 bg-[#FF581E] mx-auto rounded-full"></div>
          </div>
        </div>

        <p class="text-[#00215E] text-lg mt-4 font-medium leading-relaxed text-center">
          Bergabung Bersama Kami<br>
          Buat akun dan akses layanan terpadu kami
        </p>

        <div class="mt-8 space-y-6">
          <!-- FEATURE DINAMIS DARI DATABASE -->
          @if(isset($layanan) && count($layanan) > 0)
            @foreach($layanan as $item)
            <div class="flex items-center space-x-4 glass-effect p-4 rounded-xl">
              <div class="w-12 h-12 bg-[#00215E]/20 rounded-lg flex items-center justify-center">
                @if($item->layanan_icon)
                  <img src="{{ asset('storage/' . $item->layanan_icon) }}" alt="{{ $item->layanan_nama }}" class="w-6 h-6">
                @else
                  <!-- Default icon untuk tiket -->
                  @if($item->layanan_kode == 'tiket')
                  <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <!-- Default icon untuk pengiriman -->
                  @elseif($item->layanan_kode == 'pengiriman')
                  <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <!-- Default icon untuk sewa -->
                  @else
                  <svg class="w-6 h-6 text-[#00215E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  @endif
                @endif
              </div>
              <div>
                <p class="font-semibold text-[#00215E]">{{ $item->layanan_nama }}</p>
                <p class="text-[#00215E]/70 text-sm">{{ $item->layanan_deskripsi }}</p>
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
          @endif
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE FORM -->
    <div class="w-1/2 flex flex-col justify-center px-20 text-white relative right-overlay">
      <!-- Logo kecil di pojok kanan atas -->
      <div class="absolute top-8 right-8">
        <div class="w-12 h-12 flex items-center justify-center">
          <!-- Logo kecil bisa ditambahkan di sini jika perlu -->
        </div>
      </div>
      
      <!-- JUDUL DI TENGAH - TURUNKAN LAGI -->
      <div class="flex flex-col items-center mb-4">
        <div class="w-full text-center mt-8"> 
          <h2 class="text-3xl font-bold mb-4 text-white tracking-tight">Daftar Akun Anda</h2>
        </div>
      </div>

      <form action="{{ route('customer.register.post') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Name -->
        <div>
          <label class="block mb-2 text-white/80 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Nama Lengkap
          </label>
          <input 
            type="text" 
            name="name" 
            class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30" 
            placeholder="Masukkan nama lengkap Anda"
            required
          />
          @error('name')
            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

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
            class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30" 
            placeholder="Masukkan email Anda"
            required
          />
          @error('email')
            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
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
            class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30" 
            placeholder="Buat password Anda (minimal 8 karakter)"
            required
          />
          @error('password')
            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block mb-2 text-white/80 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Konfirmasi Password
          </label>
          <input
            type="password"
            name="password_confirmation"
            class="w-full p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00C8FF] text-gray-800 transition-all duration-300 bg-white/95 border border-white/30"
            placeholder="Konfirmasi password Anda"
            required
          />
        </div>

        <!-- Terms and Conditions -->
        <div class="pt-2">
          <label class="flex items-center text-white/80">
            <input type="checkbox" name="terms" class="rounded text-[#00C8FF] focus:ring-[#00C8FF] w-5 h-5" required>
            <span class="ml-3 text-sm">
              Saya menyetujui
              <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="terms-link">Syarat & Ketentuan</a>
              dan
              <a href="#" class="text-[#FF581E] hover:text-white font-medium transition-colors" id="privacy-link">Kebijakan Privasi</a>
            </span>
          </label>
          @error('terms')
            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        @error('message')
          <div class="bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
          </div>
        @enderror

        <button 
          type="submit" 
          class="w-full bg-gradient-to-r from-[#FF581E] to-[#FF581E] hover:from-[#FF581E] hover:to-[#FF581E] text-white font-bold py-4 rounded-lg mt-8 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
        >
          Daftar Sekarang
        </button>
      </form>

      <!-- Link ke login -->
      <p class="mt-8 text-white/80 text-center">
        Sudah punya akun? 
        <a href="/customer/login" class="text-[#FF581E] font-semibold hover:text-white transition-colors">
          Masuk di sini
        </a>
      </p>

      <!-- Divider atau spacer untuk menjaga layout tetap rapi -->
      <div class="mt-4"></div>
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
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
          </svg>
          Scroll untuk melanjutkan
        </div>
        <button class="agree-btn" id="agreeBtn">
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Saya Setuju
        </button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const termsLink = document.getElementById('terms-link');
      const privacyLink = document.getElementById('privacy-link');
      const termsModal = document.getElementById('termsModal');
      const closeModal = document.getElementById('closeModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalContent = document.getElementById('modalContent');
      const agreeBtn = document.getElementById('agreeBtn');
      const termsCheckbox = document.querySelector('input[name="terms"]');
      
      // Konten dari database (dikirim dari controller)
      const termsContent = @json($syaratKetentuan->sk_konten_html ?? '');
      const privacyContent = @json($kebijakanPrivasi->kp_konten_html ?? '');
      
      // Judul dari database
      const termsTitle = @json($syaratKetentuan->sk_judul ?? 'Syarat & Ketentuan');
      const privacyTitle = @json($kebijakanPrivasi->kp_judul ?? 'Kebijakan Privasi');
      
      // Fallback content jika database kosong
      const fallbackTermsContent = `
        <div class="text-center py-8">
          <p class="text-gray-500 mb-4">Syarat dan ketentuan tidak tersedia.</p>
          <div class="mt-8 p-4" style="background:rgba(18,51,82,0.05); border-radius:8px; border:1px solid rgba(18,51,82,0.06);">
            <p class="text-sm" style="color:var(--text-gray);">
              Dengan mencentang persetujuan, Anda mengkonfirmasi telah membaca dan memahami seluruh ketentuan yang berlaku.
            </p>
          </div>
        </div>
      `;
      
      const fallbackPrivacyContent = `
        <div class="text-center py-8">
          <p class="text-gray-500 mb-4">Kebijakan privasi tidak tersedia.</p>
          <div class="mt-8 p-4" style="background:rgba(18,51,82,0.05); border-radius:8px; border:1px solid rgba(18,51,82,0.06);">
            <p class="text-sm" style="color:var(--text-gray);">
              Data Anda akan dilindungi sesuai dengan standar keamanan yang berlaku.
            </p>
          </div>
        </div>
      `;
      
      // Form validation
      form.addEventListener('submit', function(e) {
        const password = form.querySelector('input[name="password"]').value;
        const confirmPassword = form.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirmPassword) {
          e.preventDefault();
          alert('Password dan konfirmasi password tidak cocok!');
          return;
        }
      });
      
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
      }
      
      // Event listener untuk link Syarat & Ketentuan
      termsLink.addEventListener('click', function(e) {
        e.preventDefault();
        openModal('terms', termsTitle, termsContent);
      });
      
      // Event listener untuk link Kebijakan Privasi
      privacyLink.addEventListener('click', function(e) {
        e.preventDefault();
        openModal('privacy', privacyTitle, privacyContent);
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
        // Centang checkbox terms
        if (termsCheckbox) {
          termsCheckbox.checked = true;
          termsCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
    });
  </script>
</body>
</html>