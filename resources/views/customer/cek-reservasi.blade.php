@extends('layouts.app')

@section('title', 'Cek Reservasi - Smart Shuttle')

@push('styles')
<style>
/* ================= ROOT ================= */
:root{
    --primary:#ff6a2a;
    --soft:#fff3ec;
    --dark:#333;
}

/* ================= HERO ================= */
.hero{
    background: linear-gradient(180deg, var(--soft), #fff);
    padding:90px 20px 70px;
    text-align:center;
    position:relative;
    overflow:hidden;
}

.hero::after{
    content:'';
    position:absolute;
    inset:0;
    background:url('/images/illustration-city.png') center/cover no-repeat;
    opacity:.08;
    pointer-events:none;
}

.hero-logo{
    font-size:46px;
    font-weight:800;
    color:var(--primary);
    margin-bottom:35px;
    position:relative;
    z-index:2;
}

/* search box */
.hero-box{
    background:white;
    border-radius:14px;
    box-shadow:0 10px 35px rgba(0,0,0,.12);
    display:flex;
    max-width:760px;
    margin:auto;
    padding:10px;
    position:relative;
    z-index:2;
}

.hero-box input{
    flex:1;
    border:none;
    outline:none;
    padding:16px 18px;
    font-size:15px;
}

.hero-box button{
    background:var(--primary);
    border:none;
    color:white;
    padding:0 34px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:8px;
}

.hero-box button:hover{
    opacity:.9;
}

/* ================= CONTENT ================= */
.content{
    max-width:1200px;
    margin:90px auto;
    padding:0 20px;
    display:grid;
    grid-template-columns:1.1fr 1fr;
    gap:70px;
    align-items:center;
}

.content h2{
    color:var(--primary);
    font-size:32px;
    margin-bottom:16px;
}

.content p{
    color:#555;
    line-height:1.8;
    font-size:15px;
}

/* ================= MOCKUP ================= */
.card-mockup{
    background:white;
    border-radius:20px;
    box-shadow:0 14px 40px rgba(0,0,0,.18);
    max-width:430px;
    padding:22px;
    margin-left:auto;
}

.card-header{
    background:var(--primary);
    height:54px;
    border-radius:14px;
    margin-bottom:22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 22px;
}

.dot{
    width:14px;
    height:14px;
    background:white;
    border-radius:50%;
}

.card-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.card-item{
    background:var(--primary);
    height:50px;
    border-radius:12px;
}
/* ================= CARD IMAGE ================= */
.card-image{
    max-width:430px;
    margin-left:auto;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 14px 40px rgba(0,0,0,.18);
    background:white;
}

.card-image img{
    width:100%;
    display:block;
}

/* responsive */
@media(max-width:900px){
    .card-image{
        margin:auto;
    }
}

/* ================= RESPONSIVE ================= */
@media(max-width:900px){
    .content{
        grid-template-columns:1fr;
        text-align:center;
    }
    .card-mockup{
        margin:auto;
    }
    .hero-logo{
        font-size:38px;
    }
}
</style>
@endpush

@section('content')
<section class="hero">
    <div class="hero-logo">
        Smart<span style="color:#333">Shuttle</span>
    </div>

    <form class="hero-box" method="POST" action="{{ route('customer.cek-reservasi.proses') }}">

        @csrf
        <input
            type="text"
            name="kode"
            placeholder="KODE RESERVASI / TIKET"
            required
        >
        <button type="submit">
            🔍 Cek
        </button>
    </form>
</section>

<section class="content">
    <div>
        <h2>Cek Reservasi</h2>
        <p>
            Dengan fitur cek reservasi, kamu dapat dengan mudah dan cepat
            memverifikasi status perjalanan kamu.
            Tidak perlu lagi mencari email konfirmasi atau bingung di terminal.
            Semua informasi reservasi bisa kamu akses hanya dalam beberapa detik.
        </p>
    </div>

    <div>
    <div class="card-image">
    <img src="{{ asset('images/kalender.png') }}" alt="Kalender Reservasi">
</div>

    </div>
</section>
@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
@endif

@if(isset($pemesanan))
    {{-- Include the e-ticket view inline with the prepared data --}}
    <section class="container mt-4">
        @include('customer.e_ticket', [
            'pemesanan' => $pemesanan,
            'jadwal' => $jadwal ?? null,
            'from' => $from ?? null,
            'to' => $to ?? null,
            'date' => $date ?? null,
            'time' => $time ?? null,
            'estimasi_sampai' => $estimasi_sampai ?? null,
            'customer_name' => $customer_name ?? null,
            'customer_phone' => $customer_phone ?? null,
            'customer_email' => $customer_email ?? null,
            'penumpang' => $penumpang ?? [],
            'shuttle' => $shuttle ?? null,
            'nomor_kursi' => $nomor_kursi ?? null,
            'kode_booking' => $kode_booking ?? null,
            'total_bayar' => $total_bayar ?? 0,
        ])
    </section>
@endif
@endsection
