@extends('layouts.app-driver')

@section('title', 'Jadwal Tersedia - Driver')

@push('styles')
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* ==========================================================================
       JADWAL TERSEDIA - DRIVER DASHBOARD
       Theme Match dengan Halaman Bantuan (#0d3559 & #ff6a00)
       ========================================================================== */

    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --success-green: #10b981;
        --success-green-light: rgba(16, 185, 129, 0.1);
        --danger-red: #ef4444;
        --danger-red-light: rgba(239, 68, 68, 0.1);
        --info-blue: #3b82f6;
        --info-blue-light: rgba(59, 130, 246, 0.1);
        --gray-bg: #f8fafc;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --gray-dark: #334155;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.03);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.08);
        --radius-sm: 8px;
        --radius-md: 14px;
        --transition: all 0.3s ease;
    }

    /* Container & Layout */
    .container-fluid {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION - MATCHING BANTUAN PAGE ===== */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .title i {
        color: var(--primary-orange);
        font-size: 1.6rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .profile-box {
        background: var(--white);
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary-dark);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }

    .profile-box:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .profile-box i {
        color: var(--primary-orange);
        font-size: 1rem;
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    /* Buttons dengan efek */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        line-height: 1.3;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .btn:hover::after {
        width: 200px;
        height: 200px;
    }

    .btn i {
        font-size: 0.9rem;
        transition: transform 0.2s ease;
    }

    .btn:hover i {
        transform: translateX(2px) scale(1.1);
    }

    .btn-primary {
        background: var(--primary-orange);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .btn-primary:hover {
        background: #e65c00;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .btn-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--primary-orange);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .btn-success {
        background: var(--success-green);
        color: var(--white);
        width: 100%;
        padding: 0.75rem;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        background: #0f9e6e;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-outline:hover {
        background: var(--primary-orange-light);
        border-color: var(--primary-orange);
        color: var(--primary-orange);
    }

    .btn-block {
        width: 100%;
    }

    /* Alerts dengan tema - MATCHING BANTUAN PAGE */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        position: relative;
        border-left: 4px solid transparent;
        animation: slideIn 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: var(--success-green-light);
        color: #065f46;
        border-left-color: var(--success-green);
    }

    .alert-info {
        background: var(--info-blue-light);
        color: #1e40af;
        border-left-color: var(--info-blue);
    }

    .alert-danger {
        background: var(--danger-red-light);
        color: #991b1b;
        border-left-color: var(--danger-red);
    }

    .alert i {
        font-size: 1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
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
        transition: var(--transition);
    }

    .alert .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* Grid System */
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

    /* Cards dengan tema - MATCHING BANTUAN PAGE */
    .card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 0;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

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

    .card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
        transform: translateY(-2px);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #1a4a6e 100%);
        border-bottom: none;
        position: relative;
    }

    .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--primary-orange);
        font-size: 1rem;
        animation: bounce 2s infinite;
    }

    .card-body {
        padding: 1.5rem;
        flex: 1;
        background: var(--white);
    }

    /* Card Content */
    .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
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
        letter-spacing: 0.5px;
        color: var(--gray-text);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .info-label i {
        color: var(--primary-orange);
        font-size: 0.75rem;
        width: 16px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--gray-dark);
        margin: 0;
        line-height: 1.4;
    }

    .info-value strong {
        color: var(--primary-dark);
    }

    .divider2 {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
        height: 1px;
        margin: 1.25rem 0;
    }

    /* Badges - MATCHING BANTUAN PAGE */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 30px;
        background: var(--gray-bg);
        color: var(--gray-dark);
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge-info {
        background: var(--primary-orange-light);
        color: var(--primary-orange);
        border-color: rgba(255, 106, 0, 0.2);
    }

    .badge-info i {
        color: var(--primary-orange);
    }

    .price-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-orange);
        margin: 0;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(255, 106, 0, 0.1);
    }

    /* Form Checkbox dengan tema */
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding: 0.75rem 1rem;
        background: var(--gray-bg);
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }

    .form-check:hover {
        border-color: var(--primary-orange);
        background: var(--white);
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--primary-orange);
    }

    .form-check-label {
        font-size: 0.85rem;
        color: var(--gray-dark);
        cursor: pointer;
        flex: 1;
    }

    .form-check-label strong {
        font-weight: 600;
        color: var(--primary-dark);
    }

    /* Empty State - MATCHING BANTUAN PAGE */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray-border);
        margin-bottom: 1rem;
        animation: float 3s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.95rem;
        color: var(--gray-text);
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state .btn-group {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Pagination dengan tema */
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
        min-width: 36px;
        height: 36px;
        padding: 0 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--primary-dark);
        background: var(--white);
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: var(--transition);
    }

    .pagination li a:hover {
        background: var(--primary-orange-light);
        border-color: var(--primary-orange);
        color: var(--primary-orange);
        transform: translateY(-2px);
    }

    .pagination li.active span {
        background: var(--primary-orange);
        color: var(--white);
        border-color: var(--primary-orange);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .pagination li.disabled span {
        color: var(--gray-text);
        background: var(--gray-bg);
        border-color: var(--gray-border);
        cursor: not-allowed;
    }

    /* Utilities */
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 0.75rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .mt-2 { margin-top: 0.75rem; }
    .mt-3 { margin-top: 1rem; }
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

    /* ===== RESPONSIVE MOBILE IMPROVEMENTS - MATCHING BANTUAN PAGE ===== */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 1.5rem;
        }

        .title i {
            font-size: 1.5rem;
        }

        .divider {
            width: 80px;
            margin-bottom: 1rem;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            padding: 0.7rem;
            font-size: 0.9rem;
        }

        .card {
            border-radius: 12px;
        }

        .card-header {
            padding: 1rem 1.25rem;
        }

        .card-header h5 {
            font-size: 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .info-row {
            flex-direction: column;
            gap: 0.75rem;
        }

        .info-col {
            width: 100%;
        }

        .info-label {
            font-size: 0.65rem;
        }

        .info-value {
            font-size: 0.9rem;
        }

        .price-value {
            font-size: 1.3rem;
        }

        .form-check {
            padding: 0.6rem 0.75rem;
        }

        .form-check-label {
            font-size: 0.8rem;
        }

        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state h4 {
            font-size: 1.2rem;
        }

        .empty-state .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .empty-state .btn-group .btn {
            width: 100%;
        }

        .pagination li a,
        .pagination li span {
            min-width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }

        .alert {
            padding: 0.8rem 1rem;
            font-size: 0.8rem;
            gap: 0.6rem;
        }

        .alert i {
            font-size: 0.9rem;
        }

        .alert .btn-close {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

        .card-header {
            padding: 0.875rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .info-value {
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.6rem;
        }

        .price-value {
            font-size: 1.2rem;
        }

        .form-check {
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        .form-check-label {
            font-size: 0.75rem;
        }

        .btn-success {
            padding: 0.6rem;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }

        .title i {
            font-size: 1.2rem;
        }

        .info-label {
            font-size: 0.6rem;
        }

        .info-value {
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.6rem;
        }

        .price-value {
            font-size: 1.1rem;
        }
    }

    /* Landscape mode optimization */
    @media (max-width: 896px) and (orientation: landscape) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .header-actions {
            flex-direction: row;
        }

        .btn {
            width: auto;
        }
    }

    /* Tablet devices */
    @media (min-width: 769px) and (max-width: 1024px) {
        .container-fluid {
            padding: 1rem;
        }

        .col-lg-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- HEADER SECTION - MATCHING BANTUAN PAGE -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-calendar-alt menu-icon"></i>
            Jadwal Tersedia
        </h1>
        <div class="header-actions">
            <a href="{{ route('driver.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('driver.jadwal.saya') }}" class="btn btn-secondary">
                <i class="fas fa-calendar-alt"></i>
                Jadwal Saya
            </a>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Mode Info Alert - MATCHING BANTUAN PAGE STYLE -->
    @php
        $driver = Auth::guard('driver')->user();
    @endphp

    @if($driver && $driver->schedule_accept_mode === 'AUTO_ACCEPT')
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Mode: Penerimaan Otomatis</strong> - Halaman ini menampilkan jadwal yang telah ditugaskan admin khusus untuk Anda. Jadwal akan langsung aktif tanpa perlu konfirmasi lanjutan.
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Mode: Konfirmasi Manual</strong> - Halaman ini menampilkan jadwal global yang telah dibuat admin. Anda dapat berebut untuk mengambil jadwal pilihan Anda. Jadwal yang pertama diklaim akan menjadi milik Anda.
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
        </div>
    @endif

    <!-- Notifikasi Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    <!-- Daftar Jadwal Tersedia -->
    <div class="row">
        @forelse($jadwalTersedia as $jadwal)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-route"></i>
                        {{ optional($jadwal->rutes->first())->kota_asal ?? 'Jakarta' }}
                        <i class="fas fa-arrow-right" style="font-size: 0.9rem;"></i>
                        {{ optional($jadwal->rutes->first())->kota_tujuan ?? 'Bandung' }}
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
                                <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d M Y') }}</strong>
                            </div>
                        </div>
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> WAKTU
                            </div>
                            <div class="info-value">
                                {{ $jadwal->waktu_keberangkatan }} - {{ $jadwal->waktu_kedatangan }}
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
                                {{ $jadwal->shuttle->nama_shuttle ?? 'Tidak diketahui' }}
                            </div>
                        </div>
                        <div class="info-col">
                            <div class="info-label">
                                <i class="fas fa-chair"></i> KURSI
                            </div>
                            <div class="info-value">
                                <span class="badge badge-info">
                                    <i class="fas fa-users"></i>
                                    {{ $jadwal->kursi_tersedia }} tersedia
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rute -->
                    <div class="mb-3">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> RUTE
                        </div>
                        <div class="info-value">
                            {{ optional($jadwal->rutes->first())->nama_rute ?? 'Tidak diketahui' }}
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="divider2"></div>

                    <!-- Harga -->
                    <div class="d-flex justify-between align-center mb-3">
                        <span class="info-label" style="margin-bottom: 0;">TOTAL HARGA</span>
                        <div class="price-value">
                            Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Form Ambil Jadwal -->
                    <form action="{{ route('driver.jadwal.ambil', $jadwal->id) }}" method="POST">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   id="konfirmasi{{ $jadwal->id }}" name="konfirmasi" required>
                            <label class="form-check-label" for="konfirmasi{{ $jadwal->id }}">
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
                    <p>Tidak ada jadwal yang tersedia untuk diambil saat ini. Silakan cek kembali nanti.</p>
                    <div class="btn-group">
                        <a href="{{ route('driver.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('driver.jadwal.saya') }}" class="btn btn-outline">
                            <i class="fas fa-calendar-alt"></i> Jadwal Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jadwalTersedia instanceof \Illuminate\Pagination\LengthAwarePaginator && $jadwalTersedia->hasPages())
    <div class="pagination-wrapper">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($jadwalTersedia->onFirstPage())
                    <li class="disabled"><span><i class="fas fa-chevron-left"></i></span></li>
                @else
                    <li><a href="{{ $jadwalTersedia->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($jadwalTersedia->getUrlRange(1, $jadwalTersedia->lastPage()) as $page => $url)
                    @if ($page == $jadwalTersedia->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($jadwalTersedia->hasMorePages())
                    <li><a href="{{ $jadwalTersedia->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                @else
                    <li class="disabled"><span><i class="fas fa-chevron-right"></i></span></li>
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
        'use strict';

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                setTimeout(() => alert.remove(), 400);
            });
        }, 5000);

        // Checkbox styling on change
        document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const formCheck = this.closest('.form-check');
                if (this.checked) {
                    formCheck.style.borderColor = '#10b981';
                    formCheck.style.backgroundColor = '#f0fff4';
                    formCheck.style.boxShadow = '0 0 0 2px rgba(16, 185, 129, 0.1)';
                } else {
                    formCheck.style.borderColor = 'var(--gray-border)';
                    formCheck.style.backgroundColor = 'var(--gray-bg)';
                    formCheck.style.boxShadow = 'none';
                }
            });
        });

        // Add hover effect for cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });

        // Animate badge counts
        const badges = document.querySelectorAll('.badge-info');
        badges.forEach(badge => {
            badge.style.transition = 'all 0.3s ease';
        });
    })();
</script>
@endpush