@extends('layouts.app')

@section('title', 'Cek Resi - SmartSend')

@section('content')
@php
    $user = session()->get('user', null);
    $profile = App\Models\MProfilePerusahaan::first();
@endphp

<!-- Hero Section -->
<div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
    <div class="hero-content">
        <h1 class="hero-title">Cek Resi Paket</h1>
        <p class="hero-desc">
            Lacak status pengiriman paket Anda dengan mudah menggunakan kode resi.
        </p>
    </div>
</div>

<!-- Form Cek Resi -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">
                        <i class="fas fa-search text-primary me-2"></i> Cek Status Pengiriman
                    </h2>
                    
                    <p class="text-center text-muted mb-4">
                        Masukkan kode resi yang Anda terima saat membuat pengiriman paket.
                    </p>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- PERBAIKAN: Gunakan route yang sudah ada di web.php -->
                    <form method="POST" action="{{ route('customer.proses-cek-resi') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="kode_resi" class="form-label fw-bold">
                                Kode Resi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('kode_resi') is-invalid @enderror" 
                                       id="kode_resi" 
                                       name="kode_resi"
                                       placeholder="Contoh: ss-20260119-0001"
                                       value="{{ old('kode_resi') }}"
                                       required
                                       autofocus>
                            </div>
                            @error('kode_resi')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Format: <code>ss-YYYYMMDD-XXXX</code> (contoh: ss-20260119-0001)
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i> Lacak Paket
                            </button>
                            
                            <a href="{{ route('customer.smartsend') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke SmartSend
                            </a>
                        </div>
                    </form>
                    
                    <!-- Info -->
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-info me-2"></i> Informasi Penting
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-qrcode text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Format Kode Resi</h6>
                                        <p class="mb-0 small">ss-YYYYMMDD-XXXX</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Data Update</h6>
                                        <p class="mb-0 small">Data diperbarui setiap 1 jam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <h6><i class="fas fa-lightbulb me-2"></i> Tips Mencari Resi:</h6>
                            <ul class="mb-0">
                                <li>Copy-paste kode resi dari email/SMS untuk menghindari kesalahan ketik</li>
                                <li>Resi tidak case-sensitive (ss-20260119-0001 = SS-20260119-0001)</li>
                                <li>Pastikan tidak ada spasi di awal/akhir kode resi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    position: relative;
    height: 300px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    padding: 0 6%;
    margin-bottom: 30px;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
    max-width: 800px;
}

.hero-title {
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 15px;
}

.hero-desc {
    font-size: 18px;
    line-height: 1.6;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.input-group-text {
    border-radius: 10px 0 0 10px !important;
}

.btn-primary {
    background: linear-gradient(135deg, #123352, #1a4a7a);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(18, 51, 82, 0.3);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 32px;
    }
    
    .hero-desc {
        font-size: 16px;
    }
    
    .card-body {
        padding: 30px 20px !important;
    }
}
</style>
@endsection