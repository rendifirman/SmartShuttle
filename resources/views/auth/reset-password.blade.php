<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reset Password - Smart Shuttle</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('/images/bgSmartshuttle.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      position: relative;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(61, 63, 67, 0.7);
      backdrop-filter: blur(3px);
      z-index: 1;
    }
    
    .form-container {
      position: relative;
      z-index: 2;
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    
    input::placeholder {
      color: #00215E !important;
      opacity: 0.7;
    }
    
    input:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-8 form-container rounded-2xl mx-4">
    <div class="text-center mb-6">
      <img src="/images/smartshuttlelogo.png" class="w-24 mx-auto mb-4" alt="logo">
      <h1 class="text-2xl font-bold text-[#00215E]">Reset Password</h1>
      <p class="text-sm text-[#00215E] mt-2">Buat password baru untuk akun Anda.</p>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div>
        <label class="block text-sm font-medium text-[#00215E] mb-2">Email</label>
        <!-- PERBAIKAN DI SINI: Gunakan $email dari controller, bukan request('email') -->
        <input type="email" name="email" value="{{ $email ?? old('email') }}" required 
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none bg-gray-100/80 text-[#00215E] placeholder-[#00215E]/70"
               disabled readonly>
        <p class="text-xs text-[#00215E]/70 mt-1">Email tidak dapat diubah</p>
        <!-- Tambahkan input hidden untuk memastikan email terkirim -->
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
      </div>

      <div>
        <label class="block text-sm font-medium text-[#00215E] mb-2">Password Baru</label>
        <input type="password" name="password" required 
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00215E] focus:border-transparent bg-white/90 text-[#00215E] placeholder-[#00215E]/70" 
               placeholder="Minimal 8 karakter">
      </div>

      <div>
        <label class="block text-sm font-medium text-[#00215E] mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required 
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00215E] focus:border-transparent bg-white/90 text-[#00215E] placeholder-[#00215E]/70" 
               placeholder="Ulangi password">
      </div>

      <button type="submit" 
              class="w-full py-3 rounded-lg bg-[#FF581E] hover:bg-[#E04D19] text-[#0b2848] font-semibold transition duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg">
        Reset Password
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-[#00215E]">
      Kembali ke halaman login? <a href="{{ route('customer.login') }}" 
      class="text-[#FF581E] font-semibold hover:text-[#E04D19] transition duration-300">Masuk di sini</a>
    </p>
  </div>

  <script>
    // Auto focus ke input password pertama
    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.querySelector('input[name="password"]');
      if (passwordInput) {
        passwordInput.focus();
      }
      
      // Validasi password match
      const password = document.querySelector('input[name="password"]');
      const confirmPassword = document.querySelector('input[name="password_confirmation"]');
      
      if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
          if (password.value !== confirmPassword.value) {
            confirmPassword.classList.add('border-red-300');
            confirmPassword.classList.remove('border-gray-300');
          } else {
            confirmPassword.classList.remove('border-red-300');
            confirmPassword.classList.add('border-gray-300');
          }
        });
      }
      
      // Debug: tampilkan nilai email dan token di console
      console.log('Email:', '{{ $email }}');
      console.log('Token:', '{{ $token }}');
    });
  </script>
</body>
</html>