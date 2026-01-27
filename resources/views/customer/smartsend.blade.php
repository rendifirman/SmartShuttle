@extends('layouts.app')

@section('title', 'SmartSend - Kirim Paket')

@section('content')
@php
    use App\Models\Artikel;
    use App\Models\MProfilePerusahaan;
    use App\Models\Outlet;
    
    $profile = MProfilePerusahaan::first();
    $user = session()->get('user', null);
    $activeService = 'kirim-paket';
    
    // Ambil 3 artikel terbaru dengan status aktif
    $articles = Artikel::where('status', true)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    // Ambil kota unik dari outlet yang aktif
    $kotaList = Outlet::where('status', 'aktif')
        ->with('branch')
        ->get()
        ->filter(function($outlet) {
            return $outlet->branch && $outlet->branch->kota;
        })
        ->map(function($outlet) {
            return $outlet->branch->kota;
        })
        ->unique()
        ->sort()
        ->values();
    
    // Ambil semua outlet aktif untuk kalkulator
    $outlets = Outlet::where('status', 'aktif')->with('branch')->get();
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

                        <!-- Body dengan form input -->
                        <div class="modal-body">
                            <!-- PERBAIKAN: Gunakan route yang sudah ada -->
                            <form class="resi-form" id="form-cek-resi" onsubmit="cekStatusPaket(event)" action="{{ route('customer.cek-status-paket') }}" method="POST">
                                @csrf
                                <div class="resi-input-group">
                                    <input type="text" class="form-control" id="kode-resi" name="resi"
                                           placeholder="Masukkan kode resi (Contoh: ss-20260119-0001)" required>
                                    <button type="submit" class="btn-cek-resi" id="btn-cek-resi">
                                        <i class="fas fa-search"></i> CEK STATUS PAKET
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Kirim Paket (Dengan Kalkulator Berdasarkan Rute) -->
            <div class="search-field">
                <button type="button" class="search-btn vertical-btn" id="btn-kirim-paket">
                    <div class="btn-text">
                        <div class="btn-main-text">KIRIM PAKET</div>
                        <div class="btn-label">
                            Kirim paket ke beberapa daerah dengan harga terbaik
                        </div>
                    </div>
                </button>
                
                <!-- ========== MODAL KIRIM PAKET (CEK HARGA BERDASARKAN RUTE) ========== -->
                <div class="modal-kirim-paket" id="modal-kirim-paket">
                    <div class="modal-content-kirim">
                        <button type="button" class="close-modal" id="close-modal-kirim-paket">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Header Modal -->
                        <div class="modal-header-kirim">
                            <div class="modal-main-text">CEK HARGA PAKET</div>
                            <div class="modal-label">
                                Hitung estimasi biaya pengiriman berdasarkan rute
                            </div>
                        </div>

                        <!-- Body dengan form input -->
                        <div class="modal-body">
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Harga dihitung otomatis berdasarkan rute dari outlet asal ke outlet tujuan</small>
                            </div>
                            
                            <form class="kirim-paket-form" id="form-cek-harga">
                                @csrf
                                
                                <div class="form-group-vertical">
                                    <label class="form-label" for="outlet_asal">Outlet Asal <span class="required-text">*</span></label>
                                    <div class="select-wrapper">
                                        <select class="form-control custom-select" id="outlet_asal" name="outlet_asal" required>
                                            <option value="">Pilih Outlet Asal</option>
                                            @foreach($outlets as $outlet)
                                                <option value="{{ $outlet->id }}">
                                                    {{ $outlet->nama_outlet }} - {{ $outlet->branch->kota ?? 'Tidak diketahui' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="select-arrow">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <small class="form-text">Pilih outlet tempat Anda akan menyerahkan paket</small>
                                    <div class="error-message" id="error-outlet-asal" style="display: none;"></div>
                                </div>

                                <div class="form-group-vertical">
                                    <label class="form-label" for="outlet_tujuan">Outlet Tujuan <span class="required-text">*</span></label>
                                    <div class="select-wrapper">
                                        <select class="form-control custom-select" id="outlet_tujuan" name="outlet_tujuan" required disabled>
                                            <option value="">Pilih Outlet Asal terlebih dahulu</option>
                                        </select>
                                        <div class="select-arrow">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <small class="form-text text-info">
                                        <i class="fas fa-filter"></i> 
                                        Outlet tujuan akan otomatis difilter berdasarkan rute dari outlet asal
                                    </small>
                                    <div class="error-message" id="error-outlet-tujuan" style="display: none;"></div>
                                </div>

                                <!-- Berat dan Dimensi dalam satu baris -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-vertical">
                                            <label class="form-label" for="berat">Berat (kg) <span class="required-text">*</span></label>
                                            <input type="number" class="form-control" id="berat" name="berat" 
                                                   placeholder="Contoh: 3.5" min="0.1" step="0.1" max="100" required>
                                            <small class="form-text">Minimal 0.1 kg</small>
                                            <div class="error-message" id="error-berat" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-vertical">
                                            <label class="form-label">Dimensi (cm) - Opsional</label>
                                            <div class="row g-1">
                                                <div class="col-4">
                                                    <input type="number" class="form-control" placeholder="P" 
                                                           id="panjang" name="panjang" min="0" step="0.1">
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" class="form-control" placeholder="L" 
                                                           id="lebar" name="lebar" min="0" step="0.1">
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" class="form-control" placeholder="T" 
                                                           id="tinggi" name="tinggi" min="0" step="0.1">
                                                </div>
                                            </div>
                                            <small class="form-text">Berat volumetric = (P×L×T)÷6000</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jarak Otomatis (READ ONLY) -->
                                <div class="form-group-vertical" id="jarak-container" style="display: none;">
                                    <label class="form-label">Jarak Tempuh</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="jarak_display" readonly>
                                        <span class="input-group-text">km</span>
                                    </div>
                                    <small class="form-text text-info">
                                        <i class="fas fa-route"></i> Jarak dihitung otomatis berdasarkan rute yang dipilih
                                    </small>
                                </div>

                                <div class="form-group-vertical" style="margin-top: 20px;">
                                    <button type="button" class="btn-cek-harga" id="btn-hitung-harga">
                                        <i class="fas fa-calculator"></i> HITUNG HARGA
                                    </button>
                                </div>
                            </form>

                            <!-- Hasil Perhitungan -->
                            <div id="hasil-perhitungan" class="hasil-container-realtime mt-4" style="display: none;">
                                <div class="hasil-header">
                                    <i class="fas fa-calculator"></i>
                                    <h4>Hasil Perhitungan Harga (Kalkulator)</h4>
                                </div>
                                
                                <!-- INPUT SUMMARY -->
                                <div class="harga-detail">
                                    <div class="detail-row">
                                        <span class="detail-label">📍 Dari Outlet:</span>
                                        <span class="detail-value" id="detail-asal">-</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">📍 Ke Outlet:</span>
                                        <span class="detail-value" id="detail-tujuan">-</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">📏 Berat Aktual:</span>
                                        <span class="detail-value" id="detail-berat">0 kg</span>
                                    </div>
                                    <div class="detail-row" id="row-volumetric" style="display: none;">
                                        <span class="detail-label">📦 Berat Volumetric:</span>
                                        <span class="detail-value" id="detail-volumetric">0 kg</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">⚖️ Berat Terpakai:</span>
                                        <span class="detail-value" id="detail-berat-terpakai">0 kg</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">🛣️ Jarak Rute:</span>
                                        <span class="detail-value" id="detail-jarak">0 km <small>(otomatis)</small></span>
                                    </div>
                                </div>
                                
                                <!-- CALCULATION BREAKDOWN -->
                                <div class="harga-note" style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-left: 4px solid #3498db; border-radius: 4px;">
                                    <strong style="display: block; margin-bottom: 10px; color: #2c3e50;">
                                        <i class="fas fa-list-ol"></i> Breakdown Perhitungan (Step by Step):
                                    </strong>
                                    <div id="detail-perhitungan" style="white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.9em; line-height: 1.6; color: #34495e;">
                                        Perhitungan akan ditampilkan di sini
                                    </div>
                                </div>
                                
                                <!-- PRICE BREAKDOWN -->
                                <div class="harga-detail" style="margin-top: 20px;">
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-weight"></i> Harga Berat:</span>
                                        <span class="detail-value" id="harga-berat">Rp 0</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-road"></i> Harga Jarak:</span>
                                        <span class="detail-value" id="harga-jarak">Rp 0</span>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="total-row">
                                        <span class="total-label"><strong>💰 TOTAL BIAYA:</strong></span>
                                        <span class="total-value" id="harga-total-realtime">Rp 0</span>
                                    </div>
                                </div>
                                
                                <!-- SOURCE INFORMATION -->
                                <div class="harga-note" style="margin-top: 15px; padding: 10px; background: #e8f8f5; border-left: 4px solid #1abc9c;">
                                    <i class="fas fa-info-circle" style="color: #1abc9c;"></i>
                                    <small>
                                        ✓ Semua harga berasal dari <strong>master_harga</strong> database<br>
                                        ✓ Jarak otomatis dari <strong>rute</strong> yang dipilih (bukan input manual)<br>
                                        ✓ Kalkulator ini hanya untuk cek harga (read-only)
                                    </small>
                                </div>
                                
                                <!-- Tombol Tutup -->
                                <div class="mt-3 text-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-tutup-hasil">
                                        <i class="fas fa-times"></i> Tutup
                                    </button>
                                </div>
                            </div>
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
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --light-bg: #f8f9fa;
    }

    /* ========== HERO SECTION - TANPA OVERLAY GELAP ========== */
    .hero-section {
        position: relative;
        height: auto;
        min-height: 85vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 80px 6% 50px;
        margin-bottom: 30px;
        width: 100%;
        overflow: hidden;
    }

    /* Overlay ringan untuk meningkatkan kontras teks */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            90deg, 
            rgba(255, 255, 255, 0.08) 0%,   /* Overlay sangat transparan */
            rgba(255, 255, 255, 0.03) 50%,
            transparent 100%
        );
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 55%;
        color: white;
        width: 100%;
        padding-top: 20px;
    }

    .hero-title {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        letter-spacing: -0.5px;
        line-height: 1.1;
        text-shadow: 
            0 2px 10px rgba(0, 0, 0, 0.5),    /* Shadow gelap untuk kontras */
            0 4px 20px rgba(0, 0, 0, 0.3);    /* Shadow kedua untuk depth */
    }

    .hero-desc {
        font-size: 18px;
        line-height: 1.8;
        max-width: 520px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: 400;
        margin-bottom: 20px;
        text-shadow: 
            0 1px 6px rgba(0, 0, 0, 0.4),
            0 2px 12px rgba(0, 0, 0, 0.2);
    }

    .hero-services {
        display: flex;
        text-decoration: none;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 40px;
        max-width: 450px;
        width: 100%;
    }

    .hero-service {
        text-decoration: none;
        background: rgba(255, 255, 255, 0.18); /* Sedikit lebih transparan */
        border-radius: 14px;
        padding: 15px 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
        flex: 1;
        min-width: 120px;
        border: 2px solid transparent;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .hero-service:hover {
        background: rgba(255, 255, 255, 0.28); /* Sedikit lebih gelap saat hover */
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .hero-service.active {
        background: rgba(255, 255, 255, 0.25) !important;
        border: 2px solid var(--secondary-color) !important;
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(255, 88, 30, 0.4);
    }

    .hero-service i {
        font-size: 34px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .hero-service span {
        color: #fff;
        font-weight: 600;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .search-section {
        position: relative;
        z-index: 100;
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: -90px;
        background: transparent;
        padding: 0 20px;
        box-sizing: border-box;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section::before {
            background: linear-gradient(
                to bottom, 
                rgba(0, 0, 0, 0.15) 0%,
                transparent 30%,
                transparent 70%,
                rgba(0, 0, 0, 0.1) 100%
            );
        }
        
        .hero-title {
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        }
        
        .hero-desc {
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
        }
    }

    .search-container {
        width: 100%;
        max-width: 1200px;
        background: rgba(255, 255, 255, 0.25);
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.18);
        overflow: visible;
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
    }

    /* TOMBOL YANG DIPERKECIL */
    .search-btn.vertical-btn {
        width: 100%;
        padding: 12px 18px;
        height: auto;
        min-height: 70px;
        text-align: left;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-radius: 10px;
        background: white;
        color: var(--primary-color);
        border: 2px solid #e0e0e0;
        font-weight: 700;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        box-sizing: border-box;
        position: relative;
        z-index: 10;
    }

    .search-btn.vertical-btn:hover {
        border-color: var(--secondary-color);
        transform: translateY(-2px);
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
        font-size: 18px;
        margin-bottom: 5px;
        color: var(--primary-color);
        text-align: left;
        width: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .search-btn.vertical-btn .btn-label {
        font-size: 12px;
        line-height: 1.4;
        margin-top: 0;
        color: #666;
        font-weight: 400;
        max-width: 100%;
        text-align: left;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ========== MODAL CEK PAKET ========== */
    .modal-cek-paket {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 10px;
        padding: 0;
        z-index: 1100;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        border: 1px solid #e0e0e0;
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

    /* ========== MODAL KIRIM PAKET ========== */
    .modal-kirim-paket {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 10px;
        padding: 0;
        z-index: 1100;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        border: 1px solid #e0e0e0;
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

    /* Header Modal - CEK PAKET */
    .modal-header-cek {
        background: white;
        padding: 20px 25px 12px 25px;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
        border-bottom: 2px solid var(--light-bg);
    }

    .modal-header-cek .modal-main-text {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 6px;
        color: var(--primary-color);
        text-align: left;
        width: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .modal-header-cek .modal-label {
        font-size: 13px;
        line-height: 1.4;
        margin-top: 0;
        color: #666;
        font-weight: 400;
        max-width: 100%;
        text-align: left;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header Modal - KIRIM PAKET */
    .modal-header-kirim {
        background: white;
        padding: 20px 25px 12px 25px;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
        border-bottom: 2px solid var(--light-bg);
    }

    .modal-header-kirim .modal-main-text {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 6px;
        color: var(--primary-color);
        text-align: left;
        width: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .modal-header-kirim .modal-label {
        font-size: 13px;
        line-height: 1.4;
        margin-top: 0;
        color: #666;
        font-weight: 400;
        max-width: 100%;
        text-align: left;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Container untuk form input */
    .modal-body {
        width: 100%;
        padding: 25px;
        box-sizing: border-box;
        background: white;
        height: auto;
        min-height: fit-content;
    }

    /* Alert Info */
    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d7ff;
        color: #004085;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 13px;
        border-left: 4px solid #0066cc;
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

    /* Input field untuk cek resi */
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
        padding: 12px 18px;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        background: white;
        color: #333;
        font-size: 13px;
        box-sizing: border-box;
        text-align: left;
        transition: all 0.3s ease;
        min-width: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    /* Tombol CEK PAKET - di samping */
    .btn-cek-resi {
        width: auto;
        padding: 12px 25px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        height: 45px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-width: 180px;
    }

    .btn-cek-resi:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    /* Tombol close modal */
    .close-modal {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #f8f9fa;
        border: none;
        color: #666;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        z-index: 20;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .close-modal:hover {
        background: var(--secondary-color);
        color: white;
        transform: rotate(90deg);
    }

    /* ========== FORM KIRIM PAKET ========== */
    .kirim-paket-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
        width: 100%;
        margin: 0 auto;
        height: auto;
    }

    .kirim-paket-form h4 {
        color: var(--primary-color);
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border-bottom: 2px solid var(--light-bg);
        padding-bottom: 8px;
    }

    /* Form group untuk setiap baris */
    .form-group {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 18px;
        width: 100%;
        flex-wrap: nowrap;
    }

    /* Label untuk form */
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary-color);
        min-width: 110px;
        white-space: nowrap;
        flex-shrink: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Input container */
    .form-input-container {
        flex: 1;
        min-width: 0;
        width: 100%;
    }

    /* ========== SELECT DROPDOWN CUSTOM ========== */
    .select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select {
        width: 100%;
        padding: 12px 18px;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        background: white;
        color: #333;
        font-size: 13px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        transition: all 0.3s ease;
    }

    .custom-select:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    .custom-select:hover {
        border-color: #ccc;
    }

    .select-arrow {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #666;
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .custom-select:focus + .select-arrow {
        transform: translateY(-50%) rotate(180deg);
    }

    /* Form Vertical untuk Kirim Paket */
    .form-group-vertical {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 18px;
        width: 100%;
    }

    .form-group-vertical .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 4px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-group-vertical .form-text {
        font-size: 11px;
        color: #999;
        margin-top: 4px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Tombol Cek Harga */
    .btn-cek-harga {
        width: 100%;
        padding: 12px 20px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        height: 45px;
        margin-top: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .btn-cek-harga:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    /* ========== HASIL PERHITUNGAN REAL-TIME ========== */
    .hasil-container-realtime {
        margin-top: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .hasil-container-realtime.show {
        display: block;
        animation: slideInUp 0.5s ease;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hasil-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        color: var(--primary-color);
    }

    .hasil-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .hasil-header i {
        font-size: 18px;
        color: var(--secondary-color);
    }

    .harga-detail {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .detail-label {
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        color: var(--primary-color);
        font-weight: 600;
    }

    .separator {
        height: 1px;
        background: linear-gradient(90deg, transparent, #FF581E, transparent);
        margin: 12px 0;
        opacity: 0.5;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 2px solid var(--secondary-color);
    }

    .total-label {
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 700;
    }

    .total-value {
        color: var(--secondary-color);
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .harga-note {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: #666;
        padding: 8px 12px;
        background: rgba(255, 88, 30, 0.08);
        border-radius: 6px;
        border-left: 3px solid var(--secondary-color);
    }

    .harga-note i {
        color: var(--secondary-color);
        font-size: 12px;
    }

    /* Error Message */
    .error-message {
        color: #dc3545;
        font-size: 11px;
        margin-top: 4px;
        display: none;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-control.error {
        border-color: #dc3545 !important;
        background-color: #fff8f8;
    }

    .form-control.valid {
        border-color: #28a745 !important;
    }

    .required-text {
        color: #dc3545;
        font-size: 11px;
        font-weight: normal;
    }

    /* Divider */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
        margin: 40px 0;
        opacity: 0.6;
        width: 100%;
    }

    /* ========== ARTIKEL SECTION ========== */
    .articles-section {
        padding: 50px 30px;
        background: #f8f9fa;
        text-align: center;
        margin-bottom: 40px;
        width: 100%;
        overflow: hidden;
        box-sizing: border-box;
    }

    .articles-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .articles-subtitle {
        font-size: 16px;
        color: #444;
        line-height: 1.6;
        max-width: 780px;
        margin: 0 auto 35px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .article-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-align: left;
        height: 100%;
        border: 1px solid #e0e0e0;
        width: 100%;
    }

    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .article-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }

    .article-content {
        padding: 20px;
    }

    .article-category {
        display: inline-block;
        background: var(--secondary-color);
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .article-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
        line-height: 1.4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 50px;
    }

    .article-excerpt {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 60px;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #888;
        border-top: 1px solid #f0f0f0;
        padding-top: 12px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        cursor: pointer;
        font-size: 12px;
        white-space: nowrap;
    }

    .article-read-more:hover {
        text-decoration: underline;
    }

    .view-all-articles {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 25px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .view-all-articles:hover {
        background: #E54E1A;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
    }

    .no-articles {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #666;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 16px;
    }

    /* ===== FIGMA MATCH STYLE ===== */
    .cara-pemesanan-section {
        padding: 60px 20px;
        background: #ffffff;
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cara-pemesanan-title {
        font-size: 28px;
        font-weight: 800;
        color: #123352;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .cara-pemesanan-subtitle {
        max-width: 780px;
        margin: 0 auto 40px;
        font-size: 14px;
        line-height: 1.7;
        color: #1a4a7a;
        font-weight: 500;
    }

    .cara-pemesanan-steps {
        max-width: 900px;
        margin: 0 auto;
    }

    .cara-step {
        position: relative;
        background: linear-gradient(135deg, #123352, #1a4a7a);
        border-radius: 12px;
        padding: 22px 25px 22px 80px;
        margin-bottom: 20px;
        box-shadow: 0 6px 20px rgba(18, 51, 82, 0.18);
        text-align: left;
        color: white;
    }

    .step-number {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background: #ff581e;
        color: #fff;
        border-radius: 50%;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(255, 88, 30, 0.4);
    }

    .step-content h3 {
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .step-content p {
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    /* ========== TAMBAHAN CSS DARI PROMPT ========== */
    /* Style untuk optgroup dalam select */
    optgroup {
        font-weight: bold;
        color: #123352;
    }

    optgroup option {
        font-weight: normal;
        color: #333;
        padding-left: 20px;
    }

    /* Style untuk dimensi dalam satu baris */
    .row.g-1 {
        margin-right: -5px;
        margin-left: -5px;
    }

    .row.g-1 > .col-4 {
        padding-right: 5px;
        padding-left: 5px;
    }

    .row.g-1 .form-control {
        padding: 8px 10px;
        font-size: 13px;
        text-align: center;
    }

    /* Animasi untuk hasil perhitungan */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .harga-detail {
        animation: fadeIn 0.5s ease;
    }

    /* Style untuk hasil perhitungan */
    .detail-row {
        border-bottom: 1px dashed #f0f0f0;
        padding-bottom: 6px;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        color: #123352;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
    }

    .separator {
        height: 1px;
        background: linear-gradient(90deg, transparent, #FF581E, transparent);
        margin: 12px 0;
        opacity: 0.3;
    }

    /* Tombol Tutup Hasil */
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        padding: 5px 15px;
        font-size: 12px;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    /* Responsive untuk dimensi */
    @media (max-width: 768px) {
        .row.g-1 .form-control {
            font-size: 12px;
            padding: 6px 8px;
        }
        
        .total-value {
            font-size: 20px;
        }
    }

    /* ========== RESPONSIVE STYLES ========== */
    /* Tablet (1024px and below) */
    @media (max-width: 1024px) {
        .hero-content {
            max-width: 60%;
        }

        .hero-title {
            font-size: 40px;
        }

        .hero-desc {
            font-size: 15px;
        }

        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .article-image {
            height: 150px;
        }

        .article-title {
            font-size: 16px;
            min-height: 45px;
        }

        .article-excerpt {
            font-size: 13px;
        }
    }

    /* Mobile (768px and below) */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 60vh;
            padding: 40px 20px 30px;
        }

        .hero-content {
            max-width: 100%;
            text-align: center;
        }

        .hero-title {
            font-size: 32px;
        }

        .hero-desc {
            font-size: 14px;
            margin: 0 auto 15px;
        }

        .hero-services {
            flex-direction: column;
            max-width: 100%;
            gap: 10px;
        }

        .hero-service {
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
            padding: 12px;
            flex-direction: row;
            justify-content: center;
            gap: 12px;
        }

        .hero-service i {
            font-size: 24px;
        }

        .hero-service span {
            font-size: 13px;
        }

        .search-section {
            margin-top: -40px;
            padding: 0 15px;
        }

        .search-container {
            padding: 15px;
        }

        .search-btn.vertical-btn {
            padding: 10px 15px;
            min-height: 60px;
        }

        .btn-main-text {
            font-size: 16px;
        }

        .search-btn.vertical-btn .btn-label {
            font-size: 11px;
        }

        .modal-cek-paket,
        .modal-kirim-paket {
            top: calc(100% + 5px);
            max-height: 70vh;
        }

        .modal-header-cek,
        .modal-header-kirim {
            padding: 15px 20px 8px 20px;
        }

        .modal-body {
            padding: 15px;
        }

        .resi-form {
            flex-direction: column;
            gap: 10px;
        }

        .resi-input-group {
            flex-direction: column;
            gap: 8px;
        }

        .btn-cek-resi {
            width: 100%;
            min-width: auto;
        }

        .form-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .form-label {
            min-width: 100%;
            margin-bottom: 4px;
        }

        .articles-section {
            padding: 40px 20px;
        }

        .articles-title {
            font-size: 24px;
        }

        .articles-subtitle {
            font-size: 14px;
            margin-bottom: 25px;
        }

        .articles-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .article-image {
            height: 160px;
        }

        .article-title {
            font-size: 17px;
            min-height: auto;
        }

        .article-excerpt {
            font-size: 13px;
            min-height: auto;
        }

        .cara-pemesanan-section {
            padding: 40px 15px;
        }

        .cara-pemesanan-title {
            font-size: 22px;
        }

        .cara-pemesanan-subtitle {
            font-size: 13px;
            margin-bottom: 30px;
        }

        .cara-step {
            padding: 18px 15px 18px 15px;
            text-align: center;
        }

        .step-number {
            position: static;
            transform: none;
            margin: 0 auto 10px;
        }
    }

    /* Small Mobile (480px and below) */
    @media (max-width: 480px) {
        .hero-title {
            font-size: 28px;
        }

        .hero-desc {
            font-size: 13px;
        }

        .hero-service {
            padding: 10px;
            max-width: 100%;
        }

        .hero-service i {
            font-size: 20px;
        }

        .hero-service span {
            font-size: 12px;
        }

        .search-section {
            margin-top: -30px;
            padding: 0 10px;
        }

        .search-container {
            padding: 12px;
        }

        .search-btn.vertical-btn {
            padding: 8px 12px;
            min-height: 55px;
        }

        .btn-main-text {
            font-size: 14px;
        }

        .search-btn.vertical-btn .btn-label {
            font-size: 10px;
        }

        .form-control {
            padding: 10px 15px;
            font-size: 12px;
        }

        .btn-cek-resi,
        .btn-cek-harga {
            padding: 10px 15px;
            font-size: 12px;
            height: 40px;
        }

        .total-harga-value {
            font-size: 20px;
        }

        .articles-title {
            font-size: 20px;
        }

        .articles-subtitle {
            font-size: 13px;
        }

        .article-image {
            height: 140px;
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

        .cara-pemesanan-title {
            font-size: 20px;
        }

        .step-content h3 {
            font-size: 16px;
        }

        .step-content p {
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ========== LOGIKA CEK HARGA PAKET BERDASARKAN RUTE ==========

// 1. Event change untuk outlet asal - VERSI PERBAIKAN
$(document).on('change', '#outlet_asal', function() {
    const outletAsalId = $(this).val();
    const outletTujuanSelect = $('#outlet_tujuan');
    
    if (!outletAsalId) {
        outletTujuanSelect.prop('disabled', true).html('<option value="">Pilih Outlet Asal terlebih dahulu</option>');
        $('#jarak-container').hide();
        return;
    }
    
    console.log('Mengambil outlet tujuan untuk outlet asal:', outletAsalId);
    
    // Reset dulu
    outletTujuanSelect.prop('disabled', true).html('<option value="">Loading outlet tujuan...</option>');
    
    // AJAX call untuk get outlet tujuan
    $.ajax({
        url: '{{ route("customer.getOutletTujuanByRute") }}', // Pastikan route ini benar
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
            outlet_asal_id: outletAsalId
        },
        success: function(response) {
            console.log('Response get outlet tujuan:', response);
            
            if (response.success && response.data && response.data.length > 0) {
                let options = '<option value="">Pilih Outlet Tujuan</option>';
                
                // GROUP BY KOTA untuk tampilan yang lebih rapi
                const kotaGroups = {};
                
                response.data.forEach(function(outlet) {
                    const kota = outlet.kota || 'Unknown';
                    if (!kotaGroups[kota]) {
                        kotaGroups[kota] = [];
                    }
                    kotaGroups[kota].push(outlet);
                });
                
                // Urutkan kota secara alfabet
                const sortedKota = Object.keys(kotaGroups).sort();
                
                sortedKota.forEach(function(kota) {
                    options += `<optgroup label="${kota}">`;
                    kotaGroups[kota].forEach(function(outlet) {
                        options += `<option value="${outlet.id}" 
                                   data-jarak="${outlet.jarak_dari_asal || 0}" 
                                   data-kota="${kota}">
                            ${outlet.nama_outlet} - ${outlet.alamat ? outlet.alamat.substring(0, 30) + '...' : kota}
                            ${outlet.jarak_dari_asal ? ` (${outlet.jarak_dari_asal} km)` : ''}
                        </option>`;
                    });
                    options += `</optgroup>`;
                });
                
                outletTujuanSelect.html(options).prop('disabled', false);
                $('#jarak-container').show();
                
                // Reset jarak display
                $('#jarak_display').val('');
                
                console.log('Outlet tujuan loaded:', response.data.length, 'options');
                
            } else {
                let errorMsg = 'Tidak ada outlet tujuan dalam rute yang sama';
                if (!response.success && response.message) {
                    errorMsg = response.message;
                }
                
                outletTujuanSelect.html(`<option value="">${errorMsg}</option>`);
                $('#jarak-container').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error get outlet tujuan:', {
                status: status,
                error: error,
                response: xhr.responseText,
                statusCode: xhr.status
            });
            
            let errorMessage = 'Gagal memuat data rute';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch(e) {
                // Response bukan JSON
            }
            
            outletTujuanSelect.html(`<option value="">${errorMessage}</option>`);
            $('#jarak-container').hide();
        }
    });
});

// 2. Event change untuk outlet tujuan - untuk update jarak
$(document).on('change', '#outlet_tujuan', function() {
    const selectedOption = $(this).find('option:selected');
    const jarak = selectedOption.data('jarak') || 0;
    $('#jarak_display').val(jarak > 0 ? jarak + ' km (otomatis dari rute)' : '');
});

// 3. Tombol Hitung Harga
$(document).on('click', '#btn-hitung-harga', function() {
    hitungHargaBerdasarkanRute();
});

// Fungsi validasi form sebelum hitung harga
function validasiFormCekHarga() {
    let isValid = true;
    const errors = [];
    
    // Reset error
    $('.error-message').hide();
    $('.form-control').removeClass('error');
    
    // Validasi outlet asal
    if (!$('#outlet_asal').val()) {
        $('#error-outlet-asal').text('Pilih outlet asal terlebih dahulu').show();
        $('#outlet_asal').addClass('error');
        isValid = false;
        errors.push('Outlet asal harus dipilih');
    }
    
    // Validasi outlet tujuan
    if (!$('#outlet_tujuan').val() || $('#outlet_tujuan').prop('disabled')) {
        $('#error-outlet-tujuan').text('Pilih outlet tujuan terlebih dahulu').show();
        $('#outlet_tujuan').addClass('error');
        isValid = false;
        errors.push('Outlet tujuan harus dipilih');
    }
    
    // Validasi berat
    const berat = parseFloat($('#berat').val());
    if (!berat || berat < 0.1 || berat > 100) {
        $('#error-berat').text('Berat harus antara 0.1 - 100 kg').show();
        $('#berat').addClass('error');
        isValid = false;
        errors.push('Berat harus antara 0.1 - 100 kg');
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Form Belum Lengkap',
            html: 'Silakan lengkapi:<br>' + errors.map(err => `• ${err}`).join('<br>'),
            confirmButtonText: 'Tutup'
        });
    }
    
    return isValid;
}

// 4. Fungsi utama untuk menghitung harga
function hitungHargaBerdasarkanRute() {
    // Validasi form terlebih dahulu
    if (!validasiFormCekHarga()) {
        return;
    }
    
    // Ambil data dari form
    const outletAsalId = $('#outlet_asal').val();
    const outletTujuanId = $('#outlet_tujuan').val();
    const berat = $('#berat').val();
    const panjang = $('#panjang').val() || 0;
    const lebar = $('#lebar').val() || 0;
    const tinggi = $('#tinggi').val() || 0;
    
    // Tampilkan loading
    const btnHitung = $('#btn-hitung-harga');
    const originalText = btnHitung.html();
    btnHitung.html('<i class="fas fa-spinner fa-spin"></i> Menghitung...').prop('disabled', true);
    
    // Kirim data ke server
    $.ajax({
        url: '{{ route("customer.smartsend.kalkulator-harga") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
            outlet_asal_id: outletAsalId,
            outlet_tujuan_id: outletTujuanId,
            berat: berat,
            panjang: panjang,
            lebar: lebar,
            tinggi: tinggi
        },
        success: function(response) {
            btnHitung.html(originalText).prop('disabled', false);
            
            if (response.success) {
                tampilkanHasilPerhitungan(response.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghitung Harga',
                    html: response.message + '<br><br>' +
                          '<small>Silakan cek kembali data yang dimasukkan.</small>',
                    confirmButtonText: 'Tutup'
                });
            }
        },
        error: function(xhr) {
            btnHitung.html(originalText).prop('disabled', false);
            
            let errorMsg = 'Terjadi kesalahan saat menghitung harga.';
            let errorDetail = '';
            
            if (xhr.status === 422) {
                errorMsg = 'Validasi gagal: ';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg += Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
            } else if (xhr.status === 404) {
                errorMsg = 'Tidak ada rute yang tersedia antara outlet yang dipilih';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menghitung Harga',
                html: errorMsg + '<br><br>' +
                      '<small>Error: ' + xhr.status + ' - ' + xhr.statusText + '</small>',
                confirmButtonText: 'Tutup'
            });
        }
    });
}

// 5. Display calculation results
function tampilkanHasilPerhitungan(data) {
    // Update tampilan hasil
    $('#detail-asal').text(data.outlet_asal.nama + ' - ' + data.outlet_asal.kota);
    $('#detail-tujuan').text(data.outlet_tujuan.nama + ' - ' + data.outlet_tujuan.kota);
    $('#detail-berat').text(data.berat.aktual + ' kg');
    
    // Tampilkan berat volumetric jika ada
    if (data.berat.volumetric > 0) {
        $('#row-volumetric').show();
        $('#detail-volumetric').text(data.berat.volumetric + ' kg');
    } else {
        $('#row-volumetric').hide();
    }
    
    $('#detail-berat-terpakai').text(data.berat.terpakai + ' kg');
    $('#detail-jarak').text(data.jarak + ' km');
    $('#harga-berat').text(data.harga.formatted.berat);
    $('#harga-jarak').text(data.harga.formatted.jarak);
    $('#harga-total-realtime').text(data.harga.formatted.total);
    
    // Display calculation breakdown like a calculator
    if (data.perhitungan) {
        let perhitunganHtml = data.perhitungan
            .replace(/===\s*(.*?)\s*===/g, '<strong style="color: #2c3e50; font-size: 1.1em;">$1</strong>')
            .replace(/^([A-Z][A-Z\s]+):$/gm, '<div style="margin-top: 10px; margin-bottom: 5px; font-weight: bold; color: #34495e;">$1:</div>')
            .replace(/^  -\s/gm, '  • ')
            .replace(/^  /gm, '<span style="margin-left: 20px; display: block;">') // indentation
            .replace(/\n\n/g, '</span></p><p class="mb-3" style="white-space: pre-wrap; font-family: monospace; line-height: 1.6; font-size: 0.95em;">')
            .replace(/\n/g, '<br>');
        
        $('#detail-perhitungan').html('<p class="mb-3" style="white-space: pre-wrap; font-family: monospace; line-height: 1.6; font-size: 0.95em;">' + perhitunganHtml + '</span></p>');
    }
    
    // Update jarak display (otomatis dari rute)
    $('#jarak_display').val(data.jarak + ' km (otomatis dari rute)');
    
    // Tampilkan hasil perhitungan
    $('#hasil-perhitungan').show().addClass('show');
    
    // Scroll ke hasil
    setTimeout(() => {
        $('#hasil-perhitungan')[0].scrollIntoView({ behavior: 'smooth' });
    }, 300);
}

// 6. Tombol Tutup Hasil
$('#btn-tutup-hasil').click(function() {
    $('#hasil-perhitungan').hide().removeClass('show');
});

// 7. Fungsi reset form
window.resetFormCekHarga = function() {
    console.log('Resetting form cek harga');
    
    $('#outlet_asal').val('').trigger('change');
    $('#outlet_tujuan').val('').prop('disabled', true).html('<option value="">Pilih Outlet Asal terlebih dahulu</option>');
    $('#berat').val('');
    $('#panjang').val('');
    $('#lebar').val('');
    $('#tinggi').val('');
    $('#jarak_display').val('');
    $('#jarak-container').hide();
    
    // Hide hasil perhitungan
    const hasilElement = document.getElementById('hasil-perhitungan');
    if (hasilElement) {
        hasilElement.style.display = 'none';
        hasilElement.classList.remove('show');
    }
    
    // Reset error states
    $('.error-message').hide();
    $('.form-control').removeClass('error valid');
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('SmartSend page loaded - Event handlers initialized');
    
    /* ========== MODAL KIRIM PAKET ========== */
    const btnKirimPaket = document.getElementById('btn-kirim-paket');
    const modalKirimPaket = document.getElementById('modal-kirim-paket');
    const closeModalKirimPaket = document.getElementById('close-modal-kirim-paket');
    
    // Fungsi untuk menutup modal kirim paket
    function closeModalKirim() {
        if (modalKirimPaket) {
            modalKirimPaket.classList.remove('show');
            // Reset form saat modal ditutup
            resetFormCekHarga();
        }
    }
    
    // Buka modal kirim paket
    if (btnKirimPaket) {
        btnKirimPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Opening modal kirim paket');
            
            // Tutup modal lain jika ada
            const modalCekPaket = document.getElementById('modal-cek-paket');
            if (modalCekPaket) modalCekPaket.classList.remove('show');
            
            // Buka modal kirim paket
            modalKirimPaket.classList.add('show');
            
            // Fokus ke input pertama setelah modal terbuka
            setTimeout(() => {
                const outletAsal = document.getElementById('outlet_asal');
                if (outletAsal) outletAsal.focus();
            }, 100);
        });
    }
    
    // Tutup modal kirim paket
    if (closeModalKirimPaket) {
        closeModalKirimPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModalKirim();
        });
    }
    
    // Tutup modal saat klik di luar
    document.addEventListener('click', function(e) {
        if (modalKirimPaket && modalKirimPaket.classList.contains('show')) {
            // Jika klik di luar modal dan bukan tombol pembuka modal
            if (!modalKirimPaket.contains(e.target) && !btnKirimPaket.contains(e.target)) {
                closeModalKirim();
            }
        }
    });
    
    /* ========== MODAL CEK PAKET ========== */
    const btnCekPaket = document.getElementById('btn-cek-paket');
    const modalCekPaket = document.getElementById('modal-cek-paket');
    const closeModalCekPaket = document.getElementById('close-modal-cek-paket');

    // Fungsi untuk menutup semua modal
    function closeAllModals() {
        if (modalCekPaket) modalCekPaket.classList.remove('show');
        if (modalKirimPaket) modalKirimPaket.classList.remove('show');
    }

    // Toggle modal cek paket
    if (btnCekPaket) {
        btnCekPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
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

    // Close modal buttons
    if (closeModalCekPaket) {
        closeModalCekPaket.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (modalCekPaket) modalCekPaket.classList.remove('show');
        });
    }

    // Tutup modal saat klik di luar
    document.addEventListener('click', function(e) {
        if (modalCekPaket && modalCekPaket.classList.contains('show')) {
            if (!modalCekPaket.contains(e.target) && !btnCekPaket.contains(e.target)) {
                modalCekPaket.classList.remove('show');
            }
        }
    });

    // Tutup modal saat tekan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
});

/* ========== CEK STATUS PAKET (AJAX) ========== */
function cekStatusPaket(event) {
    event.preventDefault();
    
    const resi = document.getElementById('kode-resi').value.trim();
    const btnCekResi = document.getElementById('btn-cek-resi');
    
    if (!resi) {
        alert('Silakan masukkan nomor resi!');
        return;
    }
    
    // Tampilkan loading
    const originalText = btnCekResi.innerHTML;
    btnCekResi.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
    btnCekResi.disabled = true;
    
    // Kirim AJAX request
    fetch('{{ route("customer.cek-status-paket") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ resi: resi })
    })
    .then(response => response.json())
    .then(data => {
        btnCekResi.innerHTML = originalText;
        btnCekResi.disabled = false;
        
        if (data.success) {
            // Tampilkan hasil dalam modal
            showTrackingResult(data.data);
        } else {
            alert(data.message || 'Resi tidak ditemukan.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btnCekResi.innerHTML = originalText;
        btnCekResi.disabled = false;
        alert('Terjadi kesalahan saat mengecek status paket.');
    });
}

/* ========== TAMPILKAN HASIL TRACKING ========== */
function showTrackingResult(data) {
    // Buat modal untuk menampilkan hasil
    const modalHtml = `
        <div class="tracking-result-modal" id="tracking-result-modal" 
             style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.7); z-index: 9999; display: flex; 
                    align-items: center; justify-content: center; padding: 20px;">
            <div style="background: white; border-radius: 10px; max-width: 500px; 
                        width: 100%; max-height: 90vh; overflow-y: auto;">
                <div style="padding: 20px; position: relative;">
                    <button onclick="closeTrackingModal()" 
                            style="position: absolute; top: 10px; right: 10px; 
                                   background: none; border: none; font-size: 20px; 
                                   cursor: pointer; color: #666;">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <h3 style="color: #123352; margin-bottom: 20px; font-size: 22px;">
                        <i class="fas fa-box"></i> Status Pengiriman
                    </h3>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div>
                                <strong>No. Resi:</strong><br>
                                <span style="font-size: 18px; font-weight: bold; color: #123352;">${data.resi}</span>
                            </div>
                            <div style="text-align: right;">
                                <strong>Status:</strong><br>
                                <span style="padding: 5px 10px; border-radius: 5px; 
                                      background: ${data.status_color === 'success' ? '#d4edda' : 
                                                   data.status_color === 'warning' ? '#fff3cd' : 
                                                   data.status_color === 'danger' ? '#f8d7da' : '#cce5ff'}; 
                                      color: ${data.status_color === 'success' ? '#155724' : 
                                              data.status_color === 'warning' ? '#856404' : 
                                              data.status_color === 'danger' ? '#721c24' : '#004085'};">
                                    ${data.status_text}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #123352; margin-bottom: 10px; font-size: 16px;">
                            <i class="fas fa-route"></i> Rute Pengiriman
                        </h4>
                        <div style="display: flex; align-items: center; justify-content: space-between; 
                                    padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <div style="text-align: center;">
                                <div style="font-weight: bold; color: #123352;">${data.kota_asal}</div>
                                <div style="font-size: 12px; color: #666;">Kota Asal</div>
                            </div>
                            <div style="font-size: 20px; color: #FF581E;">
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-weight: bold; color: #123352;">${data.kota_tujuan}</div>
                                <div style="font-size: 12px; color: #666;">Kota Tujuan</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #123352; margin-bottom: 10px; font-size: 16px;">
                            <i class="fas fa-info-circle"></i> Informasi Penerima
                        </h4>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <div><strong>Nama Penerima:</strong> ${data.nama_penerima}</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #123352; margin-bottom: 10px; font-size: 16px;">
                            <i class="fas fa-calendar-alt"></i> Timeline
                        </h4>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <div><strong>Tanggal Pengiriman:</strong> ${data.tanggal_pengiriman || '-'}</div>
                            <div><strong>Estimasi Sampai:</strong> ${data.estimasi_sampai || '-'}</div>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="closeTrackingModal()" 
                                style="padding: 10px 20px; background: #FF581E; color: white; 
                                       border: none; border-radius: 5px; cursor: pointer; 
                                       font-weight: bold;">
                            <i class="fas fa-check"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Tambahkan modal ke body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

/* ========== TUTUP MODAL TRACKING ========== */
window.closeTrackingModal = function() {
    const modal = document.getElementById('tracking-result-modal');
    if (modal) {
        modal.remove();
    }
}
</script>
@endpush