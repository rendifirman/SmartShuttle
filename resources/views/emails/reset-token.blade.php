<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Smart Shuttle</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f6f9;
    font-family:'Segoe UI',sans-serif;
    background-image: url('{{ asset('images/bgSmartshuttle.png') }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
">

    <div style="
        max-width:480px;
        margin:120px auto;
        background:white;
        padding:30px;
        border-radius:0;
        box-shadow:0 6px 18px rgba(0,0,0,0.08);
        backdrop-filter: blur(3px);
    ">

        <!-- Logo / Judul -->
        <h2 style="margin:0; text-align:center; color:#123352; font-size:28px; font-weight:700;">
            Smart Shuttle
        </h2>

        <p style="text-align:center; margin-top:4px; font-size:14px; color:#FF581E;">
            Reset Password Akun Anda
        </p>

        <hr style="border:0; border-top:1px solid #e6e6e6; margin:25px 0;">

        <!-- Pesan -->
        <p style="font-size:15px; color:#333; line-height:1.6;">
            Halo,
            <br><br>
            Kami menerima permintaan reset password untuk akun Anda.
            Gunakan token berikut untuk mereset password (berlaku selama <b>15 menit</b>):
        </p>

        <!-- Token -->
        <div style="text-align:center; margin:25px 0;">
            <div style="
                display:inline-block;
                padding:14px 28px;
                background:#FF581E;
                color:white;
                font-size:22px;
                font-weight:700;
                border-radius:12px;
                letter-spacing:2px;
            ">
                {{ $token }}
            </div>
        </div>

        <p style="font-size:15px; color:#333; line-height:1.6;">
            Masukkan token ini pada halaman reset password di aplikasi.
        </p>

        <!-- Footer -->
        <p style="font-size:14px; color:#555; margin-top:25px; line-height:1.6;">
            Jika Anda tidak meminta reset password, abaikan pesan ini.
            <br><br>
            Terima kasih,
            <br>
            <b>Tim Smart Shuttle</b>
        </p>
    </div>

    <p style="text-align:center; font-size:13px; color:#777; margin-top:15px;">
        © {{ date('Y') }} Smart Shuttle. All rights reserved.
    </p>

</body>
</html>