@extends('layouts.app-driver')

@section('title', 'Pengaturan Driver - Smart Shuttle')

@push('styles')
<style>
    :root {
        --primary-color: #0d3559;
        --secondary-color: #ff6a00;
        --accent-color: #2E86AB;
        --background-color: #f5f7fa;
        --text-dark: #333333;
    }

    /* ================= FILTER BAR / CARD STYLE ================= */
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .filter-bar label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .filter-bar select {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        color: var(--text-dark);
        background: white;
        cursor: pointer;
    }

    .filter-bar select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .filter-btn, .btn-primary {
        padding: 10px 24px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 2px solid transparent;
    }

    .filter-btn:hover, .btn-primary:hover {
        background: #1a4a7a;
    }

    .btn-outline {
        background: white;
        color: black;
        border: 2px solid var(--secondary-color);
    }

    .btn-outline:hover {
        background: var(--secondary-color);
        color: white;
    }

    /* ================= TAB MENU (tidak dipakai, tapi dipertahankan agar konsisten) ================= */
    .tab-wrapper {
        background: var(--primary-color);
        padding: 15px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 25px;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 25px;
    }

    .tab-btn {
        padding: 10px 25px;
        border-radius: 20px;
        border: none;
        background: transparent;
        color: white;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .tab-active {
        background: var(--secondary-color);
    }

    /* ================= CARD & FORM ================= */
    .card-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 25px;
    }

    .card-header-custom {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .card-header-custom h5, .card-header-custom h6 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 600;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge.bg-success {
        background: #e7f7ef;
        color: #28a745;
    }

    .badge.bg-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge.bg-primary {
        background: var(--primary-color);
        color: white;
    }

    /* Alert */
    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: none;
    }

    .alert-success {
        background: #e7f7ef;
        color: #28a745;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    /* Radio Cards */
    .radio-card {
        border: 1px solid #ddd;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        background: white;
    }

    .radio-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }

    .radio-card .card-body {
        padding: 20px;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .text-muted {
        color: #6c757d;
    }

    ul.text-muted {
        padding-left: 20px;
    }

    /* Button simpan */
    .btn-simpan {
        background: var(--primary-color);
        color: white;
        border-radius: 20px;
        padding: 12px 30px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-simpan:hover {
        background: #1a4a7a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-bar select {
            width: 100%;
        }

        .tab-wrapper {
            flex-wrap: wrap;
            gap: 10px;
        }

        .row {
            margin: 0 -10px;
        }

        .col-md-6 {
            padding: 0 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Judul Halaman -->
    <h2 class="mb-0" style="color: var(--primary-color);">Pengaturan Driver</h2>
    <hr class="mt-2 mb-4">

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Pengaturan Mode Penerimaan Jadwal -->
    <div class="card-custom">
        <div class="card-header-custom">
            <h5 class="mb-0">
                <i class="fas fa-sliders-h me-2" style="color: var(--secondary-color);"></i>
                Mode Penerimaan Jadwal
            </h5>
            <p class="text-muted small mb-0 mt-1">
                Pilih bagaimana Anda ingin menerima jadwal dari admin.
            </p>
        </div>

        <div class="card-body">
            <form action="{{ route('driver.pengaturan.update-schedule-accept-mode') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- AUTO_ACCEPT Mode -->
                    <div class="col-md-6">
                        <div class="radio-card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                           id="auto_accept" value="AUTO_ACCEPT"
                                           {{ $driver->schedule_accept_mode === 'AUTO_ACCEPT' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_accept">
                                        <strong>Penerimaan Otomatis</strong>
                                    </label>
                                </div>
                                <div class="ps-4 mt-3">
                                    <span class="badge bg-success mb-2">Aktif</span>
                                    <p class="small text-muted mb-2">
                                        Admin dapat langsung menugaskan jadwal kepada Anda. Jadwal akan otomatis aktif tanpa perlu konfirmasi.
                                    </p>
                                    <ul class="small text-muted mb-0">
                                        <li>Jadwal langsung menjadi milik Anda</li>
                                        <li>Jadwal langsung aktif tanpa perlu diambil</li>
                                        <li>Tidak ada kompetisi dengan driver lain</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MANUAL_CONFIRM Mode -->
                    <div class="col-md-6">
                        <div class="radio-card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                           id="manual_confirm" value="MANUAL_CONFIRM"
                                           {{ $driver->schedule_accept_mode === 'MANUAL_CONFIRM' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="manual_confirm">
                                        <strong>Konfirmasi Manual</strong>
                                    </label>
                                </div>
                                <div class="ps-4 mt-3">
                                    <span class="badge bg-info mb-2">Pilihan</span>
                                    <p class="small text-muted mb-2">
                                        Admin membuat jadwal global yang dapat Anda lihat dan ambil melalui halaman "Ambil Jadwal".
                                    </p>
                                    <ul class="small text-muted mb-0">
                                        <li>Lihat semua jadwal global yang tersedia</li>
                                        <li>Berebut untuk mengambil jadwal pilihan Anda</li>
                                        <li>Jadwal yang pertama diklaim akan menjadi milik driver tersebut</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="mt-4 text-end">
                    <button type="submit" class="btn-simpan">
                        <i class="fas fa-save me-2"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Mode Saat Ini -->
    <div class="alert alert-info">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-3 fs-4"></i>
            <div>
                <strong>Mode Saat Ini:</strong>
                @if($driver->schedule_accept_mode === 'AUTO_ACCEPT')
                    <span class="badge bg-success ms-2">Penerimaan Otomatis</span>
                    <br><small>Admin dapat langsung menugaskan jadwal kepada Anda.</small>
                @else
                    <span class="badge bg-info ms-2">Konfirmasi Manual</span>
                    <br><small>Anda dapat melihat dan mengambil jadwal global melalui halaman "Ambil Jadwal".</small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Jika ada interaksi tambahan, bisa ditambahkan di sini
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Halaman Pengaturan Driver siap.');
    });
</script>
@endpush
