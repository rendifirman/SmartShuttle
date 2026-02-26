@extends('layouts.app-driver')

@section('title', 'Pengaturan Driver - Smart Shuttle')

@push('styles')
<style>
    /* ==========================================================================
       PENGATURAN DRIVER - SMART SHUTTLE
       Theme Match dengan Halaman Lainnya (#0d3559 & #ff6a00)
       Optimized for Mobile
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
        --gray-bg: #f5f7fa;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --gray-dark: #334155;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    /* Container & Layout */
    .container-fluid {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    h2 i {
        color: var(--primary-orange);
        font-size: 1.8rem;
        animation: spin 4s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    /* Alert */
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

    .alert-danger {
        background: var(--danger-red-light);
        color: #991b1b;
        border-left-color: var(--danger-red);
    }

    .alert-info {
        background: var(--info-blue-light);
        color: #1e40af;
        border-left-color: var(--info-blue);
    }

    .alert i {
        font-size: 1.1rem;
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
        opacity: 0.5;
        padding: 0 0.5rem;
        transition: var(--transition);
        color: inherit;
    }

    .alert .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* ================= CARD & FORM ================= */
    .card-custom {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: var(--transition);
        animation: fadeIn 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .card-custom:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
        transform: translateY(-2px);
    }

    .card-header-custom {
        border-bottom: 1px solid var(--gray-border);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .card-header-custom::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--primary-orange);
    }

    .card-header-custom h5 {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header-custom h5 i {
        color: var(--primary-orange);
        font-size: 1.1rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }

    .card-header-custom p {
        color: var(--gray-text);
        font-size: 0.85rem;
        margin: 0.5rem 0 0 2rem;
    }

    /* Badge */
    .badge {
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        transition: var(--transition);
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge.bg-success {
        background: var(--success-green-light);
        color: var(--success-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge.bg-info {
        background: var(--info-blue-light);
        color: var(--info-blue);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .badge.bg-primary {
        background: var(--primary-dark);
        color: var(--white);
    }

    /* Radio Cards */
    .radio-card {
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-md);
        transition: var(--transition);
        height: 100%;
        background: var(--white);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .radio-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
        transform: translateY(-4px);
    }

    .radio-card.selected {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.2);
    }

    .radio-card .card-body {
        padding: 1.5rem;
    }

    /* Form Check */
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--primary-orange);
        transition: var(--transition);
    }

    .form-check-input:checked {
        background-color: var(--primary-orange);
        border-color: var(--primary-orange);
    }

    .form-check-label {
        font-weight: 600;
        color: var(--primary-dark);
        cursor: pointer;
        font-size: 1rem;
    }

    /* Radio content */
    .ps-4 {
        padding-left: 2.5rem !important;
        margin-top: 0.75rem;
    }

    .ps-4 .badge {
        margin-bottom: 0.75rem;
    }

    .text-muted {
        color: var(--gray-text) !important;
    }

    .small {
        font-size: 0.8rem;
        line-height: 1.5;
    }

    ul.small {
        padding-left: 1.2rem;
        margin-top: 0.5rem;
    }

    ul.small li {
        margin-bottom: 0.35rem;
        position: relative;
    }

    ul.small li::marker {
        color: var(--primary-orange);
    }

    /* Button Simpan */
    .btn-simpan {
        background: var(--primary-dark);
        color: var(--white);
        border-radius: var(--radius-lg);
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(13, 53, 89, 0.2);
    }

    .btn-simpan::after {
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

    .btn-simpan:hover::after {
        width: 200px;
        height: 200px;
    }

    .btn-simpan:hover {
        background: var(--primary-orange);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .btn-simpan:active {
        transform: translateY(0);
    }

    .btn-simpan i {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }

    .btn-simpan:hover i {
        transform: scale(1.1);
    }

    /* Grid System */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.75rem;
    }

    .col-md-6 {
        padding: 0 0.75rem;
        width: 100%;
    }

    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    .g-4 {
        gap: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .text-end {
        text-align: right;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .me-3 {
        margin-right: 0.75rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .fs-4 {
        font-size: 1.2rem;
    }

    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center;
    }

    /* ===== RESPONSIVE MOBILE IMPROVEMENTS ===== */
    @media screen and (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h2 i {
            font-size: 1.5rem;
        }

        .divider {
            width: 80px;
            margin-bottom: 1rem;
        }

        .card-custom {
            padding: 1.25rem;
        }

        .card-header-custom h5 {
            font-size: 1.1rem;
        }

        .card-header-custom p {
            font-size: 0.8rem;
            margin-left: 1.75rem;
        }

        .row {
            margin: 0 -0.5rem;
        }

        .col-md-6 {
            padding: 0 0.5rem;
            margin-bottom: 1rem;
        }

        .col-md-6:last-child {
            margin-bottom: 0;
        }

        .radio-card .card-body {
            padding: 1.25rem;
        }

        .form-check-label {
            font-size: 0.95rem;
        }

        .ps-4 {
            padding-left: 2rem !important;
        }

        .ps-4 .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.8rem;
        }

        .small {
            font-size: 0.75rem;
        }

        ul.small {
            padding-left: 1rem;
        }

        ul.small li {
            margin-bottom: 0.25rem;
        }

        .text-end {
            text-align: center;
        }

        .btn-simpan {
            width: 100%;
            justify-content: center;
            padding: 0.7rem 1.5rem;
            font-size: 0.85rem;
        }

        .alert {
            padding: 0.875rem 1.25rem;
            font-size: 0.85rem;
            gap: 0.75rem;
        }

        .alert i {
            font-size: 1rem;
        }

        .alert .btn-close {
            font-size: 1rem;
        }
    }

    @media screen and (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }

        h2 {
            font-size: 1.3rem;
        }

        h2 i {
            font-size: 1.3rem;
        }

        .card-custom {
            padding: 1rem;
        }

        .card-header-custom h5 {
            font-size: 1rem;
        }

        .card-header-custom i {
            font-size: 1rem;
        }

        .card-header-custom p {
            font-size: 0.75rem;
        }

        .radio-card .card-body {
            padding: 1rem;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .ps-4 {
            padding-left: 1.75rem !important;
        }

        .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.7rem;
        }

        .small {
            font-size: 0.7rem;
        }
    }

    @media screen and (max-width: 360px) {
        h2 {
            font-size: 1.2rem;
        }

        .card-header-custom h5 {
            font-size: 0.95rem;
        }

        .form-check-label {
            font-size: 0.85rem;
        }

        .ps-4 {
            padding-left: 1.5rem !important;
        }
    }

    /* Landscape mode optimization */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .row {
            flex-direction: row;
        }

        .col-md-6 {
            margin-bottom: 0;
        }

        .btn-simpan {
            width: auto;
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .container-fluid {
            padding: 1rem;
        }

        .card-custom {
            padding: 1.25rem;
        }
    }

    /* Animation for radio cards when selected */
    @keyframes selectPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 106, 0, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 106, 0, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 106, 0, 0);
        }
    }

    .radio-card.selected {
        animation: selectPulse 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <h2>
        <i class="fas fa-cog fa-spin"></i>
        Pengaturan Driver
    </h2>
    <div class="divider"></div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <!-- Card Pengaturan Mode Penerimaan Jadwal -->
    <div class="card-custom">
        <div class="card-header-custom">
            <h5>
                <i class="fas fa-sliders-h"></i>
                Mode Penerimaan Jadwal
            </h5>
            <p>
                Pilih bagaimana Anda ingin menerima jadwal dari admin.
            </p>
        </div>

        <div class="card-body">
            <form action="{{ route('driver.pengaturan.update-schedule-accept-mode') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- AUTO_ACCEPT Mode -->
                    <div class="col-md-6">
                        <div class="radio-card {{ $driver->schedule_accept_mode === 'AUTO_ACCEPT' ? 'selected' : '' }}">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                           id="auto_accept" value="AUTO_ACCEPT"
                                           {{ $driver->schedule_accept_mode === 'AUTO_ACCEPT' ? 'checked' : '' }}
                                           onclick="this.closest('.radio-card').classList.add('selected')">
                                    <label class="form-check-label" for="auto_accept">
                                        <strong>Penerimaan Otomatis</strong>
                                    </label>
                                </div>
                                <div class="ps-4">
                                    <span class="badge bg-success mb-2">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
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
                        <div class="radio-card {{ $driver->schedule_accept_mode === 'MANUAL_CONFIRM' ? 'selected' : '' }}">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                           id="manual_confirm" value="MANUAL_CONFIRM"
                                           {{ $driver->schedule_accept_mode === 'MANUAL_CONFIRM' ? 'checked' : '' }}
                                           onclick="this.closest('.radio-card').classList.add('selected')">
                                    <label class="form-check-label" for="manual_confirm">
                                        <strong>Konfirmasi Manual</strong>
                                    </label>
                                </div>
                                <div class="ps-4">
                                    <span class="badge bg-info mb-2">
                                        <i class="fas fa-clock me-1"></i> Pilihan
                                    </span>
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
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Mode Saat Ini -->
    <div class="alert alert-info">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Mode Saat Ini:</strong>
                @if($driver->schedule_accept_mode === 'AUTO_ACCEPT')
                    <span class="badge bg-success ms-2">
                        <i class="fas fa-check-circle me-1"></i> Penerimaan Otomatis
                    </span>
                    <br><small class="text-muted">Admin dapat langsung menugaskan jadwal kepada Anda.</small>
                @else
                    <span class="badge bg-info ms-2">
                        <i class="fas fa-clock me-1"></i> Konfirmasi Manual
                    </span>
                    <br><small class="text-muted">Anda dapat melihat dan mengambil jadwal global melalui halaman "Ambil Jadwal".</small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        console.log('Halaman Pengaturan Driver siap.');

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                if (alert && !alert.classList.contains('alert-info')) {
                    alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(20px)';
                    setTimeout(() => alert.remove(), 400);
                }
            });
        }, 5000);

        // Radio card selection styling
        const radioCards = document.querySelectorAll('.radio-card');
        const radioInputs = document.querySelectorAll('.form-check-input');

        radioInputs.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all cards
                radioCards.forEach(card => {
                    card.classList.remove('selected');
                });

                // Add selected class to parent card
                const parentCard = this.closest('.radio-card');
                if (parentCard) {
                    parentCard.classList.add('selected');
                }
            });
        });

        // Add click event to whole card for better UX
        radioCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Prevent click if clicking on radio directly (to avoid double trigger)
                if (e.target.classList.contains('form-check-input')) {
                    return;
                }

                // Find the radio input inside this card
                const radio = this.querySelector('.form-check-input');
                if (radio) {
                    radio.checked = true;

                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    radio.dispatchEvent(event);
                }
            });
        });

        // Animate the cog icon
        const cogIcon = document.querySelector('h2 i');
        if (cogIcon) {
            setInterval(() => {
                cogIcon.style.transition = 'transform 0.5s ease';
                cogIcon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    cogIcon.style.transform = 'rotate(360deg)';
                }, 250);
                setTimeout(() => {
                    cogIcon.style.transform = '';
                }, 500);
            }, 3000);
        }
    });
</script>
@endpush