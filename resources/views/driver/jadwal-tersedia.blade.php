@extends('layouts.app-driver')

@section('title', 'Jadwal Tersedia - Driver')

@push('styles')
<style>
    /* ==========================================================================
       JADWAL TERSEDIA - DRIVER DASHBOARD
       Minimalist & Clean Design
       ========================================================================== */

    /* CSS Variables */
    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --success-green: #28a745;
        --success-dark: #218838;
        --gray-bg: #f8f9fc;
        --gray-border: #e9ecef;
        --gray-light: #e0e5ec;
        --gray-text: #7a7a7a;
        --gray-dark: #5a5c69;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.06);
        --radius-md: 10px;
        --radius-sm: 6px;
        --transition: all 0.2s ease;
    }

    /* ===== Container & Layout ===== */
    .container-fluid {
        width: 100%;
        padding: 0.5rem 1rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== Header Section ===== */
    .header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .header-title p {
        font-size: 0.95rem;
        color: var(--gray-text);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* ===== Buttons - Extra Small ===== */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.4rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        line-height: 1.3;
        white-space: nowrap;
    }

    .btn i {
        font-size: 0.75rem;
    }

    .btn-primary {
        background: var(--primary-orange);
        color: var(--white);
    }

    .btn-primary:hover {
        background: #e65c00;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(255, 106, 0, 0.15);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success-green);
        color: var(--white);
        width: 100%;
        padding: 0.6rem;
        font-size: 0.85rem;
    }

    .btn-success:hover {
        background: var(--success-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.15);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-outline:hover {
        background: var(--gray-bg);
        border-color: var(--primary-dark);
    }

    .btn-xs {
        padding: 0.3rem 0.7rem;
        font-size: 0.75rem;
    }

    .btn-xs i {
        font-size: 0.7rem;
    }

    .btn-block {
        width: 100%;
    }

    /* ===== Alerts ===== */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        position: relative;
        border-left: 3px solid transparent;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: var(--success-green);
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #dc3545;
    }

    .alert i {
        font-size: 1rem;
    }

    .alert .btn-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.5;
        padding: 0 0.5rem;
    }

    .alert .btn-close:hover {
        opacity: 1;
    }

    /* ===== Grid System ===== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.75rem;
    }

    .col-md-6,
    .col-lg-4,
    .col-12 {
        padding: 0 0.75rem;
        width: 100%;
    }

    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (min-width: 992px) {
        .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    /* ===== Cards ===== */
    .card {
        background: var(--white);
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .card:hover {
        border-color: var(--gray-light);
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .card-header {
        padding: 1rem 1.25rem;
        background: var(--primary-dark);
        border-bottom: none;
    }

    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header i {
        color: var(--primary-orange);
        font-size: 0.9rem;
    }

    .card-body {
        padding: 1.25rem;
        flex: 1;
    }

    /* ===== Card Content ===== */
    .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .info-col {
        flex: 1;
        min-width: 120px;
    }

    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--gray-text);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .info-label i {
        color: var(--primary-orange);
        font-size: 0.75rem;
        width: 12px;
    }

    .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: #333;
        margin: 0;
        line-height: 1.4;
    }

    .divider {
        height: 1px;
        background: var(--gray-border);
        margin: 1rem 0;
    }

    /* ===== Badges ===== */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 20px;
        background: var(--gray-bg);
        color: var(--gray-dark);
    }

    .badge-info {
        background: #e3f2fd;
        color: #0d6efd;
    }

    .badge-success {
        background: #d4edda;
        color: var(--success-green);
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .price-value {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--primary-orange);
        margin: 0;
        line-height: 1.2;
    }

    /* ===== Form Checkbox ===== */
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background: var(--gray-bg);
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-border);
    }

    .form-check-input {
        width: 14px;
        height: 14px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--primary-orange);
    }

    .form-check-label {
        font-size: 0.8rem;
        color: #333;
        cursor: pointer;
        flex: 1;
    }

    .form-check-label strong {
        font-weight: 600;
        color: var(--primary-dark);
    }

    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--gray-border);
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        color: var(--gray-text);
        margin-bottom: 1.5rem;
    }

    .empty-state .btn-group {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ===== Pagination ===== */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
        padding: 0;
        list-style: none;
        margin: 0;
    }

    .pagination li a,
    .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--primary-dark);
        background: var(--white);
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
    }

    .pagination li a:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
    }

    .pagination li.active span {
        background: var(--primary-orange);
        color: var(--white);
        border-color: var(--primary-orange);
    }

    .pagination li.disabled span {
        color: var(--gray-text);
        background: var(--gray-bg);
        border-color: var(--gray-border);
        cursor: not-allowed;
    }

    /* ===== Utilities ===== */
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 0.75rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    
    .mt-2 { margin-top: 0.75rem; }
    .mt-3 { margin-top: 1rem; }
    .mt-4 { margin-top: 1.5rem; }
    
    .me-1 { margin-right: 0.25rem; }
    .me-2 { margin-right: 0.5rem; }
    
    .ms-2 { margin-left: 0.5rem; }
    
    .w-100 { width: 100%; }
    .h-100 { height: 100%; }
    
    .d-flex { display: flex; }
    .align-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .flex-wrap { flex-wrap: wrap; }
    .gap-1 { gap: 0.5rem; }
    .gap-2 { gap: 1rem; }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .header-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn {
            flex: 1;
        }

        .info-row {
            flex-direction: column;
            gap: 0.75rem;
        }

        .info-col {
            width: 100%;
        }

        .empty-state .btn-group {
            flex-direction: column;
        }

        .empty-state .btn-group .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 0.875rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .price-value {
            font-size: 1.2rem;
        }

        .pagination li a,
        .pagination li span {
            min-width: 30px;
            height: 30px;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="header-wrapper">
        <div class="header-title">
            <h1>Jadwal Tersedia</h1>
            <p>Pilih jadwal yang tersedia untuk Anda ambil</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('driver.dashboard') ?? '#' }}" class="btn btn-secondary btn-xs">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('driver.jadwal.saya') ?? '#' }}" class="btn btn-secondary btn-xs">
                <i class="fas fa-calendar-alt"></i>
                Jadwal Saya
            </a>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    <!-- Jadwal Tersedia -->
    <div class="row">
        @forelse($jadwalTersedia ?? [] as $jadwal)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-route"></i>
                        {{ $jadwal->rute->kota_asal ?? 'Jakarta' }} 
                        <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i> 
                        {{ $jadwal->rute->kota_tujuan ?? 'Bandung' }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Info Baris 1: Tanggal & Waktu -->
                    <div class="info-row">
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-calendar"></i> TANGGAL
                            </div>
                            <div class="info-value">
                                @if(isset($jadwal->tanggal_keberangkatan))
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d M Y') }}
                                @else
                                    15 Mar 2024
                                @endif
                            </div>
                        </div>
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> WAKTU
                            </div>
                            <div class="info-value">
                                {{ $jadwal->waktu_keberangkatan ?? '08:00' }}
                            </div>
                        </div>
                    </div>

                    <!-- Info Baris 2: Armada & Kursi -->
                    <div class="info-row">
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-bus"></i> ARMADA
                            </div>
                            <div class="info-value">
                                {{ $jadwal->shuttle->nama_shuttle ?? 'Toyota Hiace' }}
                            </div>
                        </div>
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-chair"></i> KURSI
                            </div>
                            <div class="info-value">
                                <span class="badge badge-info">
                                    {{ $jadwal->kursi_tersedia ?? 12 }} tersedia
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rute -->
                    <div class="mb-2">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> RUTE
                        </div>
                        <div class="info-value">
                            {{ $jadwal->rute->nama_rute ?? 'Jakarta - Bandung via Tol' }}
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="divider"></div>

                    <!-- Harga -->
                    <div class="d-flex justify-between align-center mb-3">
                        <span class="info-label" style="margin-bottom: 0;">TOTAL HARGA</span>
                        <div class="price-value">
                            Rp {{ number_format($jadwal->harga_total ?? 150000, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Form Ambil Jadwal -->
                    <form action="{{ route('driver.jadwal.ambil', $jadwal->id ?? 1) }}" method="POST">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="konfirmasi{{ $jadwal->id ?? 1 }}" name="konfirmasi" required>
                            <label class="form-check-label" for="konfirmasi{{ $jadwal->id ?? 1 }}">
                                Saya <strong>siap</strong> melayani jadwal ini
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check-circle"></i> Ambil Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Tidak Ada Jadwal Tersedia</h4>
                    <p class="text-muted">Tidak ada jadwal yang tersedia untuk diambil saat ini.</p>
                    <div class="btn-group">
                        <a href="{{ route('driver.dashboard') ?? '#' }}" class="btn btn-primary btn-xs">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('driver.jadwal.saya') ?? '#' }}" class="btn btn-outline btn-xs">
                            <i class="fas fa-calendar-alt"></i> Jadwal Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($jadwalTersedia) && $jadwalTersedia instanceof \Illuminate\Pagination\LengthAwarePaginator && $jadwalTersedia->hasPages())
    <div class="pagination-wrapper">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($jadwalTersedia->onFirstPage())
                    <li class="disabled">
                        <span><i class="fas fa-chevron-left"></i></span>
                    </li>
                @else
                    <li>
                        <a href="{{ $jadwalTersedia->previousPageUrl() }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($jadwalTersedia->getUrlRange(1, $jadwalTersedia->lastPage()) as $page => $url)
                    @if ($page == $jadwalTersedia->currentPage())
                        <li class="active">
                            <span>{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($jadwalTersedia->hasMorePages())
                    <li>
                        <a href="{{ $jadwalTersedia->nextPageUrl() }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="disabled">
                        <span><i class="fas fa-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Checkbox styling on change
        document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const formCheck = this.closest('.form-check');
                if (this.checked) {
                    formCheck.style.borderColor = '#28a745';
                    formCheck.style.backgroundColor = '#f0fff4';
                } else {
                    formCheck.style.borderColor = 'var(--gray-border)';
                    formCheck.style.backgroundColor = 'var(--gray-bg)';
                }
            });
        });

        // Form submission confirmation
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (this.querySelector('button[type="submit"]')) {
                    const confirmed = confirm('Konfirmasi pengambilan jadwal?');
                    if (!confirmed) {
                        e.preventDefault();
                    }
                }
            });
        });
    })();
</script>
@endpush