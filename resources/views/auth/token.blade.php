<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Masukkan Token - Smart Shuttle</title>
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
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-8 form-container rounded-2xl mx-4">
    <div class="text-center mb-6">
      <img src="/images/smartshuttlelogo.png" class="w-24 mx-auto mb-4" alt="logo">
      <h1 class="text-2xl font-bold text-[#00215E]">Masukkan Token</h1>
      <p class="text-sm text-[#00215E] mt-2">Cek email Anda — masukkan token 6 karakter yang dikirim.</p>
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

    <form action="{{ route('password.token.verify') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium text-[#00215E] mb-2">Email</label>
        <!-- readonly supaya user tidak mengubah, tapi tetap dikirim (readonly fields dikirim) -->
        <input
          type="email"
          name="email"
          required
          value="{{ old('email', session('email_for_reset')) }}"
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00215E] focus:border-transparent bg-white/90 text-[#00215E] placeholder-[#00215E]/70"
          placeholder="Email terdaftar"
          readonly
        >
        @if(session('email_for_reset'))
          <p class="text-xs text-[#00215E]/70 mt-1">Token dikirim ke email ini</p>
        @endif
      </div>

      <div>
        <label class="block text-sm font-medium text-[#00215E] mb-2">Token (6 karakter)</label>
        <input
          type="text"
          name="token"
          inputmode="text"
          maxlength="6"
          minlength="6"
          pattern="[A-Z0-9]{6}"
          value="{{ old('token') }}"
          required
          autocomplete="one-time-code"
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00215E] focus:border-transparent bg-white/90 text-[#00215E] placeholder-[#00215E]/70 tracking-widest text-center text-lg"
          placeholder="______"
        >
        <p class="text-xs text-[#00215E]/70 mt-1">Masukkan token persis seperti yang dikirim (contoh: 1A2B3C).</p>
      </div>

      <!-- Safety: jika email somehow non-readable by server when readonly, tambahkan hidden email -->
      <input type="hidden" name="email_for_reset_copy" value="{{ session('email_for_reset') ?: old('email') }}">

      <button type="submit"
              class="w-full py-3 rounded-lg bg-[#FF581E] hover:bg-[#E04D19] text-[#FFFFFF] font-semibold transition duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg">
        Verifikasi Token
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-[#00215E]">
      Belum menerima email? <a href="{{ route('password.request') }}" class="text-[#00215E] font-semibold hover:text-[#00215E] transition duration-300">Kirim ulang</a>
    </p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tokenInput = document.querySelector('input[name="token"]');
      const emailInput = document.querySelector('input[name="email"]');

      if (tokenInput) {
        // Auto uppercase & hanya huruf besar + angka
        tokenInput.addEventListener('input', function() {
          this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6);
        });

        // autofocus jika email sudah terisi dari session
        if (emailInput && emailInput.value) {
          tokenInput.focus();
        }
      }
    });
  </script>
</body>
</html>
