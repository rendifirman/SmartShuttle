@extends('layouts.app')

@section('title', 'SmartSend - Kirim Paket')

@section('content')
@php
    use App\Models\Artikel;
    use App\Models\MProfilePerusahaan;

    $profile = MProfilePerusahaan::first();
    $user = session()->get('user', null);
    $activeService = 'kirim-paket';

    // Ambil 3 artikel terbaru dengan status aktif
    $articles = Artikel::where('status', true)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
@endphp

<!-- Hero Section -->
<div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
    <div class="hero-content">
        <h1 class="hero-title">SmartSend</h1>
        <p class="hero-desc">
            Setiap kiriman punya tujuan — Paket terkirim cepat, aman, dan terpantau.
        </p>
        <div class="hero-services">
            <a href="/customer/beranda" class="hero-service" id="shuttle-link">
                <i class="fas fa-shuttle-van"></i>
                <span>Tiket Shuttle</span>
            </a>
            <a href="{{ route('customer.smartsend') }}" class="hero-service active" id="kirim-paket-link">
                <i class="fas fa-box"></i>
                <span>Kirim Paket</span>
            </a>
            <a href="#" class="hero-service" onclick="alert('Fitur Sewa Armada akan segera hadir!')">
                <i class="fas fa-car"></i>
                <span>Sewa Armada</span>
            </a>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="search-section">
    <div class="search-container">
        <!-- Form Kirim Paket -->
        <div class="search-row" style="grid-template-columns: 1fr; gap: 15px;">
            <!-- Tombol Cek Paket -->
            <div class="search-field">
                <button type="button" class="search-btn vertical-btn" id="btn-cek-paket">
                    <div class="btn-text">
                        <div class="btn-main-text">CEK PAKET</div>
                        <div class="btn-label">
                            Cek status paket yang sudah anda kirim kan
                        </div>
                    </div>
                </button>

                <!-- ========== MODAL CEK PAKET ========== -->
                <div class="modal-cek-paket" id="modal-cek-paket">
                    <div class="modal-content-cek">
                        <button type="button" class="close-modal" id="close-modal-cek-paket">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Body dengan form input - TOMBOL DI SAMPING -->
                        <div class="modal-body">
                            <form class="resi-form" id="form-cek-resi" method="GET" action="{{ route('customer.tracking-paket') }}">
                                @csrf
                                <div class="resi-input-group">
                                    <input type="text" class="form-control" id="kode-resi" name="resi"
                                           placeholder="Masukkan kode resi (Contoh: SS-2024-00123)" required>
                                    <button type="submit" class="btn-cek-resi" id="btn-cek-resi">
                                        <i class="fas fa-search"></i> CEK STATUS PAKET
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Kirim Paket -->
            <div class="search-field">
                <button type="button" class="search-btn vertical-btn" id="btn-kirim-paket">
                    <div class="btn-text">
                        <div class="btn-main-text">KIRIM PAKET</div>
                        <div class="btn-label">
                            Kirim paket ke beberapa daerah dengan harga terbaik
                        </div>
                    </div>
                </button>

                <!-- ========== MODAL KIRIM PAKET ========== -->
                <div class="modal-kirim-paket" id="modal-kirim-paket">
                    <div class="modal-content-kirim">
                        <button type="button" class="close-modal" id="close-modal-kirim-paket">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Body dengan form input -->
                        <div class="modal-body">
                            <form class="kirim-paket-form" id="form-kirim-paket">
                                <h4>Data Pengiriman</h4>

                                <!-- Kota Asal -->
                                <div class="form-group-vertical">
                                    <label class="form-label" for="asal-paket">Kota Asal</label>
                                    <select class="form-control select2-modal" id="asal-paket" name="asal_paket">
                                        <option value="">Pilih Kota Asal</option>
                                        <option value="Bandung">Bandung</option>
                                        <option value="Jakarta">Jakarta</option>
                                        <option value="Surabaya">Surabaya</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                        <option value="Semarang">Semarang</option>
                                        <option value="Malang">Malang</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Medan">Medan</option>
                                        <option value="Palembang">Palembang</option>
                                    </select>
                                </div>

                                <!-- Kota Tujuan -->
                                <div class="form-group-vertical">
                                    <label class="form-label" for="tujuan-paket">Kota Tujuan</label>
                                    <select class="form-control select2-modal" id="tujuan-paket" name="tujuan_paket">
                                        <option value="">Pilih Kota Tujuan</option>
                                        <option value="Bandung">Bandung</option>
                                        <option value="Jakarta">Jakarta</option>
                                        <option value="Surabaya">Surabaya</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                        <option value="Semarang">Semarang</option>
                                        <option value="Malang">Malang</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Medan">Medan</option>
                                        <option value="Palembang">Palembang</option>
                                    </select>
                                </div>

                                <!-- Berat Paket -->
                                <div class="form-group-vertical">
                                    <label class="form-label" for="berat-paket">Berat Paket (kg)</label>
                                    <div class="input-with-suffix">
                                        <input type="number" class="form-control" id="berat-paket"
                                               name="berat_paket" placeholder="0.1" min="0.1" step="0.1" value="0.1">
                                        <span class="input-suffix">kg</span>
                                    </div>
                                    <small class="form-text">*Minimum 0.1 kg</small>
                                </div>

                                <!-- Dimensi Paket (Opsional) -->
                                <div class="form-group-vertical">
                                    <label class="form-label">Dimensi Paket (cm) <span class="optional-text">(Opsional)</span></label>
                                    <div class="dimensi-container">
                                        <div class="dimensi-input">
                                            <input type="number" class="form-control" id="panjang-paket"
                                                   name="panjang_paket" placeholder="Panjang" min="0" step="0.1">
                                            <span class="dimensi-label">Panjang</span>
                                        </div>
                                        <div class="dimensi-input">
                                            <input type="number" class="form-control" id="lebar-paket"
                                                   name="lebar_paket" placeholder="Lebar" min="0" step="0.1">
                                            <span class="dimensi-label">Lebar</span>
                                        </div>
                                        <div class="dimensi-input">
                                            <input type="number" class="form-control" id="tinggi-paket"
                                                   name="tinggi_paket" placeholder="Tinggi" min="0" step="0.1">
                                            <span class="dimensi-label">Tinggi</span>
                                        </div>
                                    </div>
                                    <small class="form-text">*Berat volumetric: (P × L × T) ÷ 6000</small>
                                </div>

                                <!-- Tombol Cek Harga -->
                                <button type="button" class="btn-cek-harga" id="btn-cek-harga">
                                    <i class="fas fa-calculator"></i> CEK HARGA SEKARANG
                                </button>

                                <!-- Hasil Perhitungan -->
                                <div id="hasil-perhitungan" class="hasil-container">
                                    <h4><i class="fas fa-check-circle"></i> Hasil Perhitungan</h4>

                                    <div class="total-harga-container">
                                        <div class="success-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <span class="total-harga-label">Total Biaya Pengiriman:</span>
                                        <span class="total-harga-value" id="harga-total">Rp 0</span>
                                        <span class="total-harga-desc">
                                            Harga sudah termasuk semua biaya pengiriman standar
                                        </span>
                                    </div>

                                    <button type="button" class="btn-cek-ulang" id="btn-cek-ulang">
                                        <i class="fas fa-redo"></i> CEK HARGA LAINNYA
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Divider -->
<div class="divider"></div>

<!-- === ARTIKEL/BERITA SECTION === -->
<section class="articles-section">
    <h2 class="articles-title">Artikel & Berita Terbaru</h2>
    <p class="articles-subtitle">
        Dapatkan informasi terbaru seputar layanan SmartSend, tips pengiriman paket, dan berita terbaru dari kami.
    </p>

    @if($articles->count() > 0)
        <div class="articles-grid">
            @foreach($articles as $article)
            <div class="article-card">
                <!-- Gambar artikel -->
                @php
                    $imageUrl = $article->gambar && Storage::exists($article->gambar)
                        ? asset('storage/' . $article->gambar)
                        : asset('images/default-article.jpg');
                @endphp

                <img src="{{ $imageUrl }}"
                     alt="{{ $article->judul }}" class="article-image"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-article.jpg') }}';">

                <div class="article-content">
                    <span class="article-category">{{ $article->kategori ?? 'Artikel' }}</span>
                    <h3 class="article-title">{{ $article->judul }}</h3>
                    <p class="article-excerpt">
                        {{ Str::limit(strip_tags($article->konten), 120) }}
                    </p>
                    <div class="article-meta">
                        <div class="article-date">
                            <i class="far fa-calendar-alt"></i>
                            {{ $article->created_at->format('d M Y') }}
                        </div>
                        <a href="{{ route('customer.artikel.detail', $article->id) }}" class="article-read-more">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <a href="{{ route('customer.artikel') }}" class="view-all-articles">
            Lihat Semua Artikel <i class="fas fa-arrow-right"></i>
        </a>
    @else
        <div class="no-articles" style="text-align: center; padding: 40px; color: #666;">
            <p>Tidak ada artikel tersedia.</p>
        </div>
    @endif
</section>

<!-- === CARA PEMESANAN SMARTSEND (FIGMA FIX) === -->
<section class="cara-pemesanan-section">
    <h2 class="cara-pemesanan-title">Cara Pemesanan SmartSend</h2>

    <p class="cara-pemesanan-subtitle">
        SmartSend memungkinkan Anda mengecek tarif pengiriman paket secara online.
        Untuk proses pengiriman, paket wajib diserahkan langsung ke outlet resmi Smart Shuttle.
    </p>

    <div class="cara-pemesanan-steps">
        <!-- STEP 1 -->
        <div class="cara-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Cek Tarif Online</h3>
                <p>
                    Masukkan kota asal, tujuan, berat, dan dimensi paket untuk mengetahui estimasi biaya pengiriman.
                </p>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="cara-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Datang ke Outlet</h3>
                <p>
                    Setelah mengetahui estimasi tarif, silakan datang ke outlet Smart Shuttle terdekat.
                </p>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="cara-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Pengecekan Paket</h3>
                <p>
                    Petugas akan melakukan penimbangan ulang, pengecekan fisik, dan konfirmasi harga final.
                </p>
            </div>
        </div>

        <!-- STEP 4 -->
        <div class="cara-step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Proses Pengiriman</h3>
                <p>
                    Paket dikirim sesuai layanan yang dipilih dan pelanggan akan menerima informasi pengiriman.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* CSS Variables untuk halaman SmartSend */
    /* CSS Variables */
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --modal-bg: rgba(74, 66, 62, 0.50);
        --whatsapp-green: #25D366;
        --phone-blue: #3498DB;
    }

    /* FIX: Reset margin dan padding untuk body - TAMBAHKAN overflow-x: hidden */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden; /* FIX: Mencegah scroll horizontal */
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background-color: white;
        width: 100%;
    }

    .content-wrapper {
        flex: 1;
        background-color: white;
        width: 100%;
        overflow-x: hidden; /* FIX: Tambahkan ini */
    }

    /* ========== HERO SECTION MOBILE FIX ========== */
    .hero-section {
        position: relative;
        height: 100vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0 6%;
        margin-bottom: 30px;
        width: 100%;
        overflow: hidden; /* FIX: Hindari overflow */
        margin-top: -60px; /* Untuk mengkompensasi navbar fixed */
        padding-top: 60px; /* Untuk memberi ruang untuk navbar */
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 50%;
        color: white;
        width: 100%;
    }

    .hero-title {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
        font-family: 'Roboto', sans-serif;
        line-height: 1.1;
    }

    .hero-desc {
        font-size: 18px;
        line-height: 1.7;
        max-width: 520px;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }

    .hero-services {
        display: flex;
        text-decoration: none;
        justify-content: flex-start;
        gap: 8px;
        margin-top: 35px;
        max-width: 400px;
        width: 100%;
    }

    .hero-service {
        text-decoration: none;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        backdrop-filter: blur(6px);
        transition: background 0.3s, transform 0.3s, border 0.3s;
        flex: 1;
        min-width: 110px;
        border: 2px solid transparent;
        font-family: 'Roboto', sans-serif;
    }

    .hero-service:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05);
    }

    .hero-service.active {
        background: rgba(255, 255, 255, 0.3) !important;
        border: 2px solid var(--secondary-color) !important;
        transform: scale(1.05);
    }

    .hero-service i {
        font-size: 32px;
        color: #fff;
    }

    .hero-service span {
        color: #fff;
        font-weight: 600;
        font-family: 'Roboto', sans-serif;
    }

    /* ========== SEARCH SECTION MOBILE FIX ========== */
    .search-section {
        position: relative;
        z-index: 20;
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: -138px;
        background: transparent;
        padding: 0 20px; /* FIX: Tambahkan padding */
        box-sizing: border-box;
    }

    .search-container {
        width: 100%;
        max-width: 1200px;
        background: rgba(255, 255, 255, 0.25);
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.18);
        overflow: hidden; /* FIX: Hindari overflow */
    }

    /* ========== TOMBOL FULL WIDTH VERTIKAL ========== */
    .search-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        align-items: stretch;
        width: 100%;
    }

    .search-field {
        width: 100%;
        position: relative;
        height: auto;
        min-height: fit-content;
    }

    /* TOMBOL VERTIKAL - MENYESUAIKAN DENGAN BERANDA */
    .search-btn.vertical-btn {
        width: 100%;
        padding: 12px 18px;
        height: auto;
        min-height: 80px;
        text-align: left;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-radius: 12px;
        background: white;
        color: var(--primary-color);
        border: 2px solid #e0e0e0;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        box-sizing: border-box;
        position: relative;
        z-index: 10;
    }

    .search-btn.vertical-btn:hover {
        border-color: var(--secondary-color);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 88, 30, 0.15);
    }

    .btn-text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        width: 100%;
    }

    .btn-main-text {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 8px;
        color: var(--primary-color);
        text-align: left;
        width: 100%;
        font-family: 'Roboto', sans-serif;
    }

    .search-btn.vertical-btn .btn-label {
        font-size: 14px;
        line-height: 1.4;
        margin-top: 0;
        color: #666;
        font-weight: 500;
        max-width: 100%;
        text-align: left;
        font-family: 'Roboto', sans-serif;
    }

    /* ========== MODAL CEK PAKET ========== */
    .modal-cek-paket {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: var(--modal-bg);
        border-radius: 12px;
        padding: 0;
        z-index: 1100;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
        box-sizing: border-box;
        overflow: hidden;
        min-height: fit-content;
    }

    .modal-cek-paket.show {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        height: auto;
    }

    /* Animasi untuk modal turun */
    @keyframes slideDownModal {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-cek-paket.show,
    .modal-kirim-paket.show {
        animation: slideDownModal 0.3s ease-out;
    }

    /* Header Modal */
    .modal-header-cek {
        background: var(--modal-bg);
        padding: 25px 30px 12px 30px;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
        backdrop-filter: blur(5px);
    }

    .modal-header-cek .modal-main-text {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 8px;
        color: var(--secondary-color);
        text-align: left;
        width: 100%;
        font-family: 'Roboto', sans-serif;
    }

    .modal-header-cek .modal-label {
        font-size: 14px;
        line-height: 1.4;
        margin-top: 0;
        color: white;
        font-weight: 500;
        max-width: 100%;
        text-align: left;
        font-family: 'Roboto', sans-serif;
    }

    /* Body Modal */
    .modal-body {
        width: 100%;
        padding: 30px;
        box-sizing: border-box;
        background: var(--modal-bg);
        height: auto;
        min-height: fit-content;
        flex-shrink: 0;
    }

    /* ========== FORM CEK RESI ========== */
    .resi-form {
        display: flex;
        flex-direction: row;
        gap: 12px;
        width: 100%;
        margin: 0 auto;
        height: auto;
        align-items: center;
    }

    .resi-input-group {
        display: flex;
        flex-direction: row;
        gap: 12px;
        width: 100%;
        align-items: center;
        height: auto;
        min-height: fit-content;
    }

    .form-control {
        flex: 1;
        padding: 14px 18px;
        border-radius: 8px;
        border: 2px solid rgba(255,255,255,0.3);
        background: white;
        color: #333;
        font-size: 15px;
        box-sizing: border-box;
        text-align: left;
        transition: all 0.3s ease;
        min-width: 0;
        font-family: 'Roboto', sans-serif;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.2);
    }

    /* Tombol CEK PAKET */
    .btn-cek-resi {
        width: auto;
        padding: 14px 30px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        height: 48px;
        font-family: 'Roboto', sans-serif;
        min-width: 200px;
    }

    .btn-cek-resi:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    /* Tombol close modal */
    .close-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.1);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
        z-index: 20;
        font-family: 'Roboto', sans-serif;
    }

    .close-modal:hover {
        background: rgba(255,255,255,0.2);
        color: var(--secondary-color);
        transform: rotate(90deg);
    }

    /* ========== MODAL KIRIM PAKET ========== */
    .modal-kirim-paket {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: var(--modal-bg);
        border-radius: 12px;
        padding: 0;
        z-index: 1100;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
        box-sizing: border-box;
        overflow: hidden;
        min-height: fit-content;
    }

    .modal-kirim-paket.show {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        height: auto;
    }

    .modal-header-kirim {
        background: var(--modal-bg);
        padding: 25px 30px 12px 30px;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
        backdrop-filter: blur(5px);
    }

    .modal-header-kirim .modal-main-text {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 8px;
        color: var(--secondary-color);
        text-align: left;
        width: 100%;
        font-family: 'Roboto', sans-serif;
    }

    .modal-header-kirim .modal-label {
        font-size: 14px;
        line-height: 1.4;
        margin-top: 0;
        color: white;
        font-weight: 500;
        max-width: 100%;
        text-align: left;
        font-family: 'Roboto', sans-serif;
    }

    /* ========== FORM KIRIM PAKET ========== */
    .kirim-paket-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        margin: 0 auto;
        height: auto;
    }

    .kirim-paket-form h4 {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
        border-bottom: 2px solid rgba(255,255,255,0.1);
        padding-bottom: 10px;
    }

    /* Form group untuk setiap baris */
    .form-group {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 20px;
        width: 100%;
        flex-wrap: nowrap;
    }

    /* Form Vertical untuk Kirim Paket */
    .form-group-vertical {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
        width: 100%;
    }

    .form-group-vertical .form-label {
        font-size: 14px;
        font-weight: 600;
        color: white;
        margin-bottom: 6px;
        font-family: 'Roboto', sans-serif;
    }

    .form-group-vertical .form-text {
        font-size: 12px;
        color: rgba(255,255,255,0.7);
        margin-top: 4px;
        font-family: 'Roboto', sans-serif;
    }

    /* Label untuk form */
    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: white;
        min-width: 120px;
        white-space: nowrap;
        flex-shrink: 0;
        font-family: 'Roboto', sans-serif;
    }

    /* Input container */
    .form-input-container {
        flex: 1;
        min-width: 0;
        width: 100%;
    }

    /* Select2 untuk modal */
    .select2-modal {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single.select2-modal {
        height: 48px !important;
        border: 2px solid rgba(255,255,255,0.3) !important;
        border-radius: 8px !important;
        background: white !important;
        font-family: 'Roboto', sans-serif !important;
    }

    .select2-container--default .select2-selection--single.select2-modal .select2-selection__rendered {
        line-height: 48px !important;
        color: #333 !important;
        font-size: 15px !important;
        padding-left: 18px !important;
        font-family: 'Roboto', sans-serif !important;
    }

    .select2-container--default .select2-selection--single.select2-modal .select2-selection__arrow {
        height: 48px !important;
    }

    .input-with-suffix {
        display: flex;
        align-items: center;
        width: 100%;
        position: relative;
        max-width: 200px;
    }

    .input-with-suffix .form-control {
        padding-right: 45px;
        width: 100%;
        height: 48px;
        font-size: 15px;
    }

    .input-suffix {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        color: #666 !important;
        font-size: 14px !important;
        padding: 5px !important;
        border-radius: 3px !important;
        position: absolute !important;
        right: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 100 !important;
        font-weight: 500;
        font-family: 'Roboto', sans-serif;
    }

    .dimensi-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        width: 100%;
    }

    .dimensi-input {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .dimensi-label {
        font-size: 12px;
        color: rgba(255,255,255,0.7);
        text-align: center;
        font-family: 'Roboto', sans-serif;
    }

    .optional-text {
        color: rgba(255,255,255,0.6);
        font-weight: normal;
        font-size: 12px;
    }

    /* Tombol Cek Harga */
    .btn-cek-harga {
        width: 100%;
        padding: 15px 20px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        white-space: nowrap;
        flex-shrink: 0;
        height: 48px;
        margin-top: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .btn-cek-harga:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    /* Hasil Perhitungan */
    .hasil-container {
        display: none;
        margin-top: 25px;
        padding-top: 15px;
        border-top: 2px solid rgba(255,255,255,0.1);
    }

    .hasil-container h4 {
        color: white;
        font-size: 18px;
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hasil-container h4 i {
        color: var(--success-color);
    }

    .total-harga-container {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 18px;
        margin: 15px 0;
        border: 2px solid var(--secondary-color);
        text-align: center;
        width: 100%;
        box-sizing: border-box;
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .success-icon {
        font-size: 28px;
        color: var(--success-color);
        margin-bottom: 10px;
    }

    .total-harga-label {
        font-size: 14px;
        color: rgba(255,255,255,0.8);
        margin-bottom: 6px;
        display: block;
        line-height: 1.2;
        font-family: 'Roboto', sans-serif;
    }

    .total-harga-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 6px;
        display: block;
        line-height: 1.2;
        font-family: 'Roboto', sans-serif;
    }

    .total-harga-desc {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        margin-top: 6px;
        display: block;
        line-height: 1.4;
        font-family: 'Roboto', sans-serif;
    }

    .btn-cek-ulang {
        width: 100%;
        padding: 12px;
        background: rgba(255,255,255,0.1);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 15px;
        font-family: 'Roboto', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cek-ulang:hover {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    /* Animasi untuk hasil perhitungan */
    #hasil-perhitungan {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }

    #hasil-perhitungan.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Divider */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
        margin: 50px 0;
        opacity: 0.6;
        width: 100%;
    }

    /* ========== ARTIKEL SECTION MOBILE FIX ========== */
    .articles-section {
        padding: 80px 40px;
        background: #f8f9fa;
        text-align: center;
        margin-bottom: 50px;
        width: 100%;
        overflow: hidden;
        box-sizing: border-box;
    }

    .articles-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 10px;
        font-family: 'Roboto', sans-serif;
    }

    .articles-subtitle {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
        max-width: 780px;
        margin: 0 auto 50px;
        font-family: 'Roboto', sans-serif;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .article-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-align: left;
        height: 100%;
        border: 1px solid #e0e0e0;
        width: 100%;
    }

    .article-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }

    .article-content {
        padding: 25px;
    }

    .article-category {
        display: inline-block;
        background: var(--secondary-color);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        font-family: 'Roboto', sans-serif;
    }

    .article-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 12px;
        line-height: 1.4;
        font-family: 'Roboto', sans-serif;
    }

    .article-excerpt {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #888;
        border-top: 1px solid #f0f0f0;
        padding-top: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .article-date {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .article-read-more {
        color: var(--secondary-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s;
        font-family: 'Roboto', sans-serif;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        font-size: 12px;
    }

    .article-read-more:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .view-all-articles {
        display: inline-block;
        margin-top: 40px;
        padding: 12px 30px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
    }

    .view-all-articles:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
        text-decoration: none;
        color: white;
    }

    .no-articles {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #666;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
    }

    /* ===== CARA PEMESANAN STYLE ===== */
    .cara-pemesanan-section {
        padding: 80px 40px;
        background: #ffffff;
        text-align: center;
        font-family: 'Roboto', sans-serif;
    }

    .cara-pemesanan-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 10px;
    }

    .cara-pemesanan-subtitle {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
        max-width: 780px;
        margin: 0 auto 50px;
        font-family: 'Roboto', sans-serif;
    }

    .cara-pemesanan-steps {
        max-width: 900px;
        margin: 0 auto;
    }

    .cara-step {
        position: relative;
        background: linear-gradient(135deg, #123352, #1a4a7a);
        border-radius: 12px;
        padding: 25px 30px 25px 100px;
        margin-bottom: 20px;
        box-shadow: 0 6px 20px rgba(18, 51, 82, 0.18);
        text-align: left;
        color: white;
        transition: all 0.3s ease;
    }

    .cara-step:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(18, 51, 82, 0.25);
    }

    .step-number {
        position: absolute;
        left: 25px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: var(--secondary-color);
        color: #fff;
        border-radius: 50%;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.4);
    }

    .step-content h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        font-family: 'Roboto', sans-serif;
    }

    .step-content p {
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-family: 'Roboto', sans-serif;
    }

    /* ========== RESPONSIVE STYLES ========== */
    /* Tablet (1024px and below) */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 42px;
        }

        .hero-desc {
            font-size: 16px;
        }

        .search-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .search-btn.vertical-btn {
            min-height: 70px;
        }

        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .article-image {
            height: 180px;
        }

        .article-title {
            font-size: 16px;
        }

        .article-excerpt {
            font-size: 13px;
        }

        .cara-step {
            padding: 22px 25px 22px 80px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            font-size: 20px;
            left: 20px;
        }

        .step-content h3 {
            font-size: 18px;
        }

        .step-content p {
            font-size: 13px;
        }
    }

    /* Mobile (768px and below) */
    @media (max-width: 768px) {
        /* HERO MOBILE */
        .hero-section {
            height: auto;
            min-height: 90vh;
            padding: 120px 20px 60px;
            background-position: center center;
            width: 100%;
            max-width: 100vw;
            overflow: hidden;
        }

        .hero-content {
            max-width: 100%;
            text-align: center;
            width: 100%;
            padding: 0 10px;
        }

        .hero-title {
            font-size: 32px;
            margin-bottom: 15px;
            line-height: 1.2;
            word-wrap: break-word;
        }

        .hero-desc {
            font-size: 15px;
            margin: 0 auto 20px;
            max-width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .hero-services {
            flex-direction: column;
            max-width: 100%;
            gap: 12px;
            padding: 0 10px;
            width: 100%;
        }

        .hero-service {
            width: 100%;
            max-width: 280px;
            margin: 0 auto;
            padding: 15px;
            flex-direction: row;
            justify-content: center;
            gap: 15px;
            box-sizing: border-box;
        }

        .hero-service i {
            font-size: 28px;
        }

        .hero-service span {
            font-size: 15px;
        }

        /* SEARCH MOBILE */
        .search-section {
            margin-top: -120px;
            padding: 0 15px;
            width: 100%;
            box-sizing: border-box;
        }

        .search-container {
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .search-btn.vertical-btn {
            padding: 12px 15px;
            min-height: 70px;
        }

        .btn-main-text {
            font-size: 18px;
        }

        .search-btn.vertical-btn .btn-label {
            font-size: 13px;
        }

        /* Modal Adjustments */
        .modal-cek-paket,
        .modal-kirim-paket {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header-cek,
        .modal-header-kirim {
            padding: 20px 25px 10px 25px;
        }

        .modal-body {
            padding: 20px;
        }

        .resi-form {
            flex-direction: column;
            gap: 10px;
        }

        .resi-input-group {
            flex-direction: column;
            gap: 10px;
        }

        .btn-cek-resi {
            width: 100%;
            min-width: auto;
        }

        .form-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .form-label {
            min-width: 100%;
            margin-bottom: 5px;
        }

        .dimensi-container {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        /* ARTICLES MOBILE */
        .articles-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .articles-title {
            font-size: 22px;
            padding: 0 10px;
        }

        .articles-subtitle {
            font-size: 13px;
            margin-bottom: 30px;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .articles-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            width: 100%;
        }

        .article-image {
            height: 180px;
        }

        .article-content {
            padding: 20px;
        }

        .article-title {
            font-size: 17px;
        }

        .article-excerpt {
            font-size: 13px;
        }

        .view-all-articles {
            padding: 10px 25px;
            font-size: 13px;
        }

        /* CARA PEMESANAN MOBILE */
        .cara-pemesanan-section {
            padding: 50px 20px;
        }

        .cara-pemesanan-title {
            font-size: 22px;
        }

        .cara-pemesanan-subtitle {
            font-size: 13px;
            margin-bottom: 30px;
        }

        .cara-step {
            padding: 20px 20px 20px 70px;
        }

        .step-number {
            width: 35px;
            height: 35px;
            font-size: 18px;
            left: 15px;
        }

        .step-content h3 {
            font-size: 16px;
        }

        .step-content p {
            font-size: 12px;
        }
    }

    /* Small Mobile (480px and below) */
    @media (max-width: 480px) {
        .hero-title {
            font-size: 28px;
            padding: 0 5px;
        }

        .hero-desc {
            font-size: 14px;
            padding: 0 5px;
        }

        .hero-service {
            padding: 12px;
            max-width: 100%;
        }

        .hero-service i {
            font-size: 24px;
        }

        .hero-service span {
            font-size: 13px;
        }

        .search-section {
            margin-top: -110px;
            padding: 0 10px;
        }

        .search-container {
            padding: 15px;
        }

        .search-btn.vertical-btn {
            padding: 10px 12px;
            min-height: 65px;
        }

        .btn-main-text {
            font-size: 16px;
        }

        .search-btn.vertical-btn .btn-label {
            font-size: 12px;
        }

        .form-control {
            padding: 12px 15px;
            font-size: 14px;
        }

        .btn-cek-resi,
        .btn-cek-harga {
            padding: 12px 15px;
            font-size: 14px;
            height: 45px;
        }

        .total-harga-value {
            font-size: 24px;
        }

        /* Articles Responsive */
        .articles-title {
            font-size: 20px;
        }

        .articles-subtitle {
            font-size: 12px;
        }

        .article-image {
            height: 160px;
        }

        .article-content {
            padding: 15px;
        }

        .article-title {
            font-size: 16px;
        }

        .article-excerpt {
            font-size: 12px;
        }

        /* Cara Pemesanan Responsive */
        .cara-pemesanan-title {
            font-size: 20px;
        }

        .cara-pemesanan-subtitle {
            font-size: 12px;
        }

        .cara-step {
            padding: 18px 15px 18px 60px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            font-size: 16px;
            left: 12px;
        }

        .step-content h3 {
            font-size: 15px;
        }

        .step-content p {
            font-size: 11px;
        }
    }

    /* Landscape Mobile */
    @media (max-height: 600px) and (orientation: landscape) {
        .hero-section {
            min-height: 120vh;
            padding: 100px 20px 40px;
        }

        .hero-content {
            padding-top: 40px;
        }

        .hero-title {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .hero-desc {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .hero-services {
            margin-top: 20px;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .hero-service {
            min-width: 100px;
            padding: 10px;
        }
    }

    /* Fix untuk iOS Safari */
    @supports (-webkit-touch-callout: none) {
        .hero-section {
            height: -webkit-fill-available;
            min-height: -webkit-fill-available;
        }
    }

    /* FIX TAMBAHAN UNTUK MENGATASI OVERFLOW */
    html, body {
        max-width: 100%;
        overflow-x: hidden;
    }

    img, video, iframe {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Smartsend page loaded');

    /* ========== MODAL CEK PAKET ========== */
    const btnCekPaket = document.getElementById('btn-cek-paket');
    const modalCekPaket = document.getElementById('modal-cek-paket');
    const closeModalCekPaket = document.getElementById('close-modal-cek-paket');
    const modalKirimPaket = document.getElementById('modal-kirim-paket');
    const btnKirimPaket = document.getElementById('btn-kirim-paket');

    // Fungsi untuk menutup semua modal
    function closeAllModals() {
        if (modalCekPaket) modalCekPaket.classList.remove('show');
        if (modalKirimPaket) modalKirimPaket.classList.remove('show');
    }

    if (btnCekPaket) {
        btnCekPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle modal cek paket
            const isOpen = modalCekPaket.classList.contains('show');
            closeAllModals();

            if (!isOpen) {
                modalCekPaket.classList.add('show');
                setTimeout(() => {
                    const resiInput = document.getElementById('kode-resi');
                    if (resiInput) resiInput.focus();
                }, 100);
            }
        });
    }

    if (closeModalCekPaket) {
        closeModalCekPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (modalCekPaket) modalCekPaket.classList.remove('show');
        });
    }

    /* ========== MODAL KIRIM PAKET ========== */
    const modalKirimPaketElement = document.getElementById('modal-kirim-paket');
    const closeModalKirimPaket = document.getElementById('close-modal-kirim-paket');

    if (btnKirimPaket) {
        btnKirimPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle modal kirim paket
            const isOpen = modalKirimPaketElement.classList.contains('show');
            closeAllModals();

            if (!isOpen) {
                modalKirimPaketElement.classList.add('show');

                // Inisialisasi Select2 jika tersedia
                if (typeof $ !== 'undefined') {
                    setTimeout(() => {
                        $('#asal-paket, #tujuan-paket').select2({
                            placeholder: "Pilih kota",
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#modal-kirim-paket')
                        });
                    }, 100);
                }
            }
        });
    }

    if (closeModalKirimPaket) {
        closeModalKirimPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (modalKirimPaketElement) {
                modalKirimPaketElement.classList.remove('show');
            }
        });
    }

    /* ========== TUTUP MODAL SAAT KLIK DI LUAR ========== */
    document.addEventListener('click', function(e) {
        // Tutup modal cek paket jika klik di luar
        if (modalCekPaket && modalCekPaket.classList.contains('show')) {
            if (!modalCekPaket.contains(e.target) && !btnCekPaket.contains(e.target)) {
                modalCekPaket.classList.remove('show');
            }
        }

        // Tutup modal kirim paket jika klik di luar
        if (modalKirimPaketElement && modalKirimPaketElement.classList.contains('show')) {
            if (!modalKirimPaketElement.contains(e.target) && !btnKirimPaket.contains(e.target)) {
                modalKirimPaketElement.classList.remove('show');
            }
        }
    });

    /* ========== TUTUP MODAL SAAT TEKAN ESC ========== */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    /* ========== FORM CEK RESI - REDIRECT KE HALAMAN TRACKING ========== */
    const formCekResi = document.getElementById('form-cek-resi');
    if (formCekResi) {
        formCekResi.addEventListener('submit', function(e) {
            e.preventDefault();

            const resi = document.getElementById('kode-resi').value.trim();

            if (!resi) {
                alert('Silakan masukkan nomor resi!');
                return;
            }

            // Close modal
            closeAllModals();

            // Redirect ke halaman tracking dengan parameter resi
            window.location.href = `{{ route('customer.tracking-paket') }}?resi=${encodeURIComponent(resi)}`;
        });
    }

    /* ---------- CEK HARGA PAKET AJAX ---------- */
    const btnCekHarga = document.getElementById('btn-cek-harga');
    const btnCekUlang = document.getElementById('btn-cek-ulang');
    const hasilPerhitungan = document.getElementById('hasil-perhitungan');

    if (btnCekHarga) {
        btnCekHarga.addEventListener('click', function(e) {
            e.preventDefault();

            const asal = document.getElementById('asal-paket').value;
            const tujuan = document.getElementById('tujuan-paket').value;
            const berat = parseFloat(document.getElementById('berat-paket').value) || 0.1;

            // Validasi dasar
            if (!asal || !tujuan) {
                alert('Silakan pilih asal dan tujuan terlebih dahulu!');
                return;
            }

            if (asal === tujuan) {
                alert('Kota asal dan tujuan tidak boleh sama!');
                return;
            }

            if (berat <= 0) {
                alert('Silakan isi berat paket (minimal 0.1 kg)!');
                return;
            }

            // Tampilkan loading
            const originalText = btnCekHarga.innerHTML;
            btnCekHarga.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghitung...';
            btnCekHarga.disabled = true;

            // Kirim AJAX request
            fetch('{{ route("customer.cek-harga-paket") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    asal: asal,
                    tujuan: tujuan,
                    berat: berat
                })
            })
            .then(response => response.json())
            .then(data => {
                btnCekHarga.innerHTML = originalText;
                btnCekHarga.disabled = false;

                if (data.success) {
                    hasilPerhitungan.style.display = 'block';
                    document.getElementById('harga-total').textContent = data.data.harga_total;
                    hasilPerhitungan.classList.add('show');

                    // Scroll ke hasil perhitungan
                    hasilPerhitungan.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                        inline: 'nearest'
                    });

                } else {
                    alert(data.message || 'Terjadi kesalahan saat menghitung harga.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnCekHarga.innerHTML = originalText;
                btnCekHarga.disabled = false;
                alert('Terjadi kesalahan saat menghitung harga.');
            });
        });
    }

    // Tombol cek ulang
    if (btnCekUlang) {
        btnCekUlang.addEventListener('click', function() {
            // Reset form
            if (typeof $ !== 'undefined') {
                $('#asal-paket').val('').trigger('change');
                $('#tujuan-paket').val('').trigger('change');
            } else {
                document.getElementById('asal-paket').value = '';
                document.getElementById('tujuan-paket').value = '';
            }

            document.getElementById('berat-paket').value = '0.1';
            document.getElementById('panjang-paket').value = '';
            document.getElementById('lebar-paket').value = '';
            document.getElementById('tinggi-paket').value = '';

            // Sembunyikan hasil
            if (hasilPerhitungan) {
                hasilPerhitungan.style.display = 'none';
                hasilPerhitungan.classList.remove('show');
            }

            // Scroll ke form
            const formKirimPaket = document.getElementById('form-kirim-paket');
            if (formKirimPaket) {
                formKirimPaket.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    /* ========== WINDOW RESIZE HANDLER ========== */
    function handleResize() {
        // Tutup semua modal saat resize
        closeAllModals();
    }

    window.addEventListener('resize', handleResize);
});
</script>
@endpush
