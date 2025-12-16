<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Smart Shuttle | Lupa Password</title>
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

        /* Overlay biru gelap + blur */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(61, 63, 67, 0.7); 
            backdrop-filter: blur(3px);
            z-index: 1;
        }

        /* Form container */
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
            <img src="/images/smartshuttlelogo.png"
                 class="w-24 mx-auto mb-4"
                 alt="Smart Shuttle Logo">

            <h1 class="text-2xl font-bold text-[#123352]">
                Masukkan Email
            </h1>

            <p class="text-sm text-[#123352] mt-1">
                Cek email Anda — masukkan email terdaftar.
            </p>
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

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-[#00215E] mb-2">Email</label>
                <input type="email"
                       name="email"
                       required
                       placeholder="user@example.com"
                       class="w-full p-3 border border-gray-300 rounded-lg 
                              bg-white/90 text-[#00215E]
                              focus:outline-none focus:ring-2 focus:ring-[#00C8FF] focus:border-transparent">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-lg bg-[#FF581E] text-[#FFFFFF] font-semibold 
                           hover:bg-[#E04D19] transition duration-300 hover:scale-[1.02] shadow-md hover:shadow-lg">
                Kirim Link Reset
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#00215E]">
            Belum menerima email? 
            <a href="#" class="text-[#FF581E] font-semibold hover:text-[#E04D19] transition duration-300">
                Kirim ulang
            </a>
        </p>

        <p class="mt-4 text-center text-sm text-[#00215E]">
            Kembali ke 
            <a href="{{ route('customer.login') }}"  
               class="text-[#FF581E] font-semibold hover:text-[#E04D19] transition duration-300">
                Halaman Login
            </a>
        </p>

    </div>

</body>
</html>