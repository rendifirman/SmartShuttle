@extends('layouts.app')

@section('title', 'Lokasi Outlet SmartShuttle')

@push('styles')
<style>
    /* Tombol Reset Filter */
    .btn-secondary {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        min-width: 120px; /* Lebar minimal untuk tombol */
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #FF581E 0%, #FF7A4A 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:active {
        transform: translateY(0);
    }

    .btn-secondary i {
        margin-right: 8px;
    }

    /* Style untuk input dengan datalist */
    input[list] {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230C2D48' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 12px;
        padding-right: 40px;
        cursor: pointer;
    }

    input[list]:hover {
        border-color: #FF581E;
    }

    /* Outlet Page Styles dengan Background Foto */
    .outlet-page {
        background:
            linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0.95) 0%,
                rgba(255, 255, 255, 0.85) 50%,
                rgba(255, 255, 255, 0.95) 100%
            ),
            url('{{ asset("images/indonesia.jpeg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        padding-top: 80px;
        min-height: 100vh;
        position: relative;
    }

    /* Fallback jika foto tidak ada */
    .outlet-page.no-bg {
        background:
            linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0.95) 0%,
                rgba(255, 255, 255, 0.85) 50%,
                rgba(255, 255, 255, 0.95) 100%
            );
        background-attachment: fixed;
    }

    .outlet-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .outlet-title {
        font-size: 28px;
        font-weight: 700;
        color: #0C2D48;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
    }

    /* FILTER SECTION - DIRAPIKAN */
    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        backdrop-filter: blur(5px);
        width: 100%;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-section h4 {
        color: #0C2D48;
        margin-bottom: 20px;
        font-size: 18px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(12, 45, 72, 0.1);
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px 15px;
        background: white;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        height: 44px;
    }

    .form-control:focus {
        border-color: #FF581E;
        box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.2);
    }

    /* LAYOUT FILTER YANG SEIMBANG - Filter item lebih panjang, button lebih pendek */
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr; /* Kolom pertama dan kedua lebih panjang, kolom ketiga lebih pendek */
        gap: 70px;
        align-items: end;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .filter-item label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 14px;
        white-space: nowrap;
    }

    .filter-button {
        display: flex;
        margin-right: 35px;
        margin-bottom: 2px;
        align-items: flex-end;
        height: 100%;
        justify-content: flex-end; /* Tombol di sebelah kanan */
    }

    .filter-button .btn-secondary {
        height: 65px;
        width: auto; /* Lebar otomatis berdasarkan konten */
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        font-size: 16px;
    }

    /* Outlet Grid */
    .outlet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
        margin: 0 auto;
    }

    /* Outlet Card - DIRAPIKAN */
    .outlet-card {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .outlet-card-inner {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        border: 1px solid rgba(221, 221, 221, 0.5);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(5px);
        position: relative;
    }

    .outlet-card-inner:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(12, 45, 72, 0.2);
        border-color: rgba(255, 88, 30, 0.3);
    }

    /* Card Header (Nama Outlet di ATAS) */
    .card-header {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        padding: 20px;
        text-align: center;
        font-weight: 600;
        font-size: 18px;
        order: 1;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(255, 88, 30, 0.5), transparent);
    }

    /* Card Image */
    .card-image {
        width: 100%;
        height: 200px;
        overflow: hidden;
        order: 2;
        position: relative;
    }

    .card-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), transparent);
        z-index: 1;
    }

    .outlet-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .outlet-card:hover .outlet-img {
        transform: scale(1.05);
    }

    /* Card Body yang DIRAPIKAN */
    .card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        order: 3;
        background: #fff;
    }

    /* Grid untuk informasi outlet - LAYOUT RAPI */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 15px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label i {
        color: #FF581E;
        font-size: 10px;
        width: 12px;
        text-align: center;
    }

    .info-value {
        font-size: 13px;
        color: #333;
        line-height: 1.4;
        font-weight: 500;
    }

    .info-value.address {
        color: #555;
        font-weight: normal;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Contact & Hours Section */
    .contact-hours {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .contact-hours-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .contact-item, .hours-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .contact-label, .hours-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #666;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .contact-label i, .hours-label i {
        color: #FF581E;
        font-size: 10px;
    }

    .contact-value, .hours-value {
        font-size: 13px;
        color: #333;
        font-weight: 500;
    }

    .btn-detail {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
        margin-top: 20px;
        width: 100%;
        text-align: center;
        font-size: 14px;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-detail::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #FF581E 0%, #FF7A4A 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 88, 30, 0.3);
    }

    .btn-detail:hover::before {
        left: 100%;
    }

    .btn-detail i {
        font-size: 12px;
    }

    /* ============ POPUP LAYOUT: FOTO DI ATAS, INFO DI BAWAH ============ */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }

    .popup-container {
        max-width: 700px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    .popup-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        position: relative;
    }

    .popup-header {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        padding: 20px;
        text-align: center;
        font-weight: 600;
        font-size: 20px;
        position: relative;
        letter-spacing: 0.5px;
    }

    .btn-close-popup {
        position: absolute;
        top: 15px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close-popup:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    /* Foto di ATAS popup */
    .popup-top-image {
        width: 100%;
        height: 250px;
        overflow: hidden;
    }

    .popup-top-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .popup-top-image:hover img {
        transform: scale(1.05);
    }

    /* Konten di BAWAH dalam 2 kolom */
    .popup-content {
        padding: 25px;
    }

    /* Layout Dua Kolom untuk Info */
    .popup-two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }

    /* Kolom Kiri */
    .popup-left-column {
        display: flex;
        flex-direction: column;
    }

    /* Kolom Kanan */
    .popup-right-column {
        display: flex;
        flex-direction: column;
    }

    /* Info Items */
    .popup-info-item {
        margin-bottom: 18px;
    }

    .popup-label {
        font-weight: 600;
        color: #0C2D48;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .popup-label i {
        color: #FF581E;
        font-size: 14px;
    }

    .popup-value {
        color: #555;
        line-height: 1.5;
        font-size: 15px;
        padding-left: 22px;
        word-break: break-word;
    }

    /* Kontak Section */
    .popup-contact-section {
        margin-top: 10px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    /* Fasilitas Section */
    .popup-facilities {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .popup-facilities-label {
        font-weight: 600;
        color: #0C2D48;
        margin-bottom: 15px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .popup-facilities-label i {
        color: #FF581E;
    }

    .popup-facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 10px;
    }

    .popup-facility-item {
        background: #e3f2fd;
        color: #0C2D48;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .popup-facility-item:hover {
        transform: translateY(-2px);
        background: #bbdefb;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .popup-facility-item i {
        font-size: 12px;
        color: #FF581E;
    }

    /* No Facilities */
    .no-facilities {
        text-align: center;
        padding: 20px;
        color: #888;
        font-style: italic;
        background: #f8f9fa;
        border-radius: 8px;
        grid-column: 1 / -1;
    }

    /* Alamat Khusus (Full width) */
    .full-width-section {
        grid-column: 1 / -1;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0C2D48;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .empty-state h3 {
        color: #666;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .empty-state p {
        color: #888;
        margin-bottom: 20px;
    }

    .empty-state a {
        color: #FF581E;
        text-decoration: none;
        font-weight: 500;
    }

    .empty-state a:hover {
        text-decoration: underline;
    }

    /* Animasi */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .outlet-container {
            padding: 20px 16px;
        }

        .outlet-title {
            font-size: 24px;
            margin-bottom: 24px;
        }

        .outlet-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .filter-section {
            margin-bottom: 30px;
            padding: 20px;
        }

        .filter-grid {
            grid-template-columns: 1fr; /* Di mobile jadi 1 kolom */
            gap: 15px;
        }

        .filter-button .btn-secondary {
            height: 44px;
            width: 100%; /* Di mobile tombol full width */
            justify-content: center;
        }

        .card-image {
            height: 180px;
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            padding: 16px;
            font-size: 16px;
        }

        .info-grid {
            gap: 10px;
        }

        .contact-hours-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
        }

        /* Popup Responsive */
        .popup-container {
            width: 95%;
            max-width: 500px;
        }

        .popup-header {
            padding: 16px;
            font-size: 18px;
        }

        .popup-top-image {
            height: 200px;
        }

        .popup-content {
            padding: 20px;
        }

        .popup-two-columns {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .popup-facilities-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .popup-label {
            font-size: 12px;
        }

        .popup-value {
            font-size: 14px;
        }

        .btn-close-popup {
            top: 12px;
            right: 16px;
            width: 28px;
            height: 28px;
            font-size: 16px;
        }

        .outlet-page {
            background-attachment: scroll;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .filter-grid {
            grid-template-columns: 2fr 2fr 1fr; /* Tetap 2:2:1 di tablet */
            gap: 15px;
        }

        .filter-button .btn-secondary {
            padding: 12px 15px; /* Sedikit lebih kecil di tablet */
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .outlet-container {
            padding: 16px 12px;
        }

        .outlet-title {
            font-size: 22px;
        }

        .card-image {
            height: 160px;
        }

        .popup-top-image {
            height: 180px;
        }

        .popup-content {
            padding: 16px;
        }

        .popup-facilities-grid {
            grid-template-columns: 1fr;
        }

        .popup-header {
            padding: 14px;
            font-size: 16px;
        }

        .btn-close-popup {
            top: 10px;
            right: 14px;
            width: 26px;
            height: 26px;
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="outlet-page" id="outletPage">
    <div class="outlet-container">
        <h1 class="outlet-title">LOKASI OUTLET SMARTSHUTTLE</h1>

        <!-- FILTER SECTION - DIRAPIKAN -->
        <div class="filter-section">
            <h4>Filter Outlet</h4>
            <form method="GET" action="{{ route('customer.outlet.filter') }}" id="filterForm">
                <div class="filter-grid">
                    <!-- Filter Kota dengan Input Datalist - LEBAR PANJANG -->
                    <div class="filter-item">
                        <label for="kotaInput">Filter berdasarkan Kota:</label>
                        <input type="text"
                               name="kota"
                               class="form-control"
                               id="kotaInput"
                               list="kotaOptions"
                               placeholder="Ketik atau pilih kota"
                               value="{{ request('kota') }}"
                               onchange="submitFilterForm()">
                        <datalist id="kotaOptions">
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Filter Cabang dengan Input Datalist - LEBAR PANJANG -->
                    <div class="filter-item">
                        <label for="branchInput">Filter berdasarkan Cabang:</label>
                        <input type="text"
                               name="branch_name"
                               class="form-control"
                               id="branchInput"
                               list="branchOptions"
                               placeholder="Ketik atau pilih cabang"
                               value="{{ request('branch_name') }}"
                               onchange="submitFilterForm()">
                        <input type="hidden" name="branch_id" id="branchIdInput" value="{{ request('branch_id') }}">
                        <datalist id="branchOptions">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->nama_cabang }} - {{ $branch->kota }}"
                                        data-id="{{ $branch->id }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Tombol Reset Filter - LEBAR PENDEK -->
                    <div class="filter-button">
                        <button type="button" class="btn btn-secondary" onclick="resetFilter()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Outlet -->
        <div class="outlet-grid">
            @foreach($outlets as $outlet)
                <div class="outlet-card" data-city="{{ $outlet->branch ? $outlet->branch->kota : '' }}">
                    <div class="outlet-card-inner">
                        <div class="card-header">
                            {{ $outlet->nama_outlet }}
                        </div>
                        <div class="card-image">
                            @php
                                $gambar = $outlet->foto_url ??
                                         (isset($outlet->foto_outlet) ? asset($outlet->foto_outlet) :
                                         asset('images/placeholder-outlet.jpg'));
                            @endphp
                            <img src="{{ $gambar }}"
                                 alt="{{ $outlet->nama_outlet }}"
                                 class="outlet-img"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-outlet.jpg') }}'">
                        </div>
                        <div class="card-body">
                            <!-- Grid informasi outlet -->
                            <div class="info-grid">
                                <!-- Cabang -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-store"></i> CABANG
                                    </div>
                                    <div class="info-value">
                                        {{ $outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui' }}
                                    </div>
                                </div>

                                <!-- Kota -->
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-city"></i> KOTA
                                    </div>
                                    <div class="info-value">
                                        {{ $outlet->branch ? $outlet->branch->kota : 'Tidak diketahui' }}
                                    </div>
                                </div>

                                <!-- Alamat (Full Width) -->
                                <div class="info-item full-width">
                                    <div class="info-label">
                                        <i class="fas fa-map-marker-alt"></i> ALAMAT
                                    </div>
                                    <div class="info-value address">
                                        {{ $outlet->alamat_lengkap ?? $outlet->alamat }}
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Hours -->
                            <div class="contact-hours">
                                <div class="contact-hours-grid">
                                    <!-- Telepon -->
                                    <div class="contact-item">
                                        <div class="contact-label">
                                            <i class="fas fa-phone"></i> TELEPON
                                        </div>
                                        <div class="contact-value">
                                            {{ $outlet->telepon ?? '-' }}
                                        </div>
                                    </div>

                                    <!-- Jam Operasional -->
                                    <div class="hours-item">
                                        <div class="hours-label">
                                            <i class="fas fa-clock"></i> JAM OPERASIONAL
                                        </div>
                                        <div class="hours-value">
                                            {{ $outlet->jam_operasional ?? '24 Jam' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Button Detail -->
                            <button class="btn-detail" onclick="showOutletPopup({{ $outlet->id }})">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Jika tidak ada outlet -->
        @if($outlets->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada outlet ditemukan</h3>
            <p>Silakan coba filter lain atau <a href="{{ route('customer.outlet') }}">reset filter</a></p>
        </div>
        @endif

        <!-- Popup Overlay -->
        <div class="popup-overlay" id="popupOverlay" style="display: none;">
            <div class="popup-container" role="dialog" aria-modal="true" aria-labelledby="popupTitle">
                <div class="popup-card" id="popupCard">
                    <!-- Konten popup diisi lewat JS -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

// Fungsi untuk mendapatkan gambar
function getOutletImage($outlet) {
    if (!empty($outlet->foto_outlet)) {
        if (Str::startsWith($outlet->foto_outlet, ['http://', 'https://'])) {
            return $outlet->foto_outlet;
        }

        if (Storage::exists($outlet->foto_outlet)) {
            return Storage::url($outlet->foto_outlet);
        }

        $publicPath = 'images/' . ltrim($outlet->foto_outlet, '/');
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }

        if (file_exists(public_path($outlet->foto_outlet))) {
            return asset($outlet->foto_outlet);
        }
    }

    return asset('images/placeholder-outlet.jpg');
}

$outletsArray = $outlets->map(function($o) {
    // fasilitas → array
    $fasilitas = $o->fasilitas
        ? array_map('trim', explode(',', $o->fasilitas))
        : [];

    // gambar → cek url atau pakai placeholder
    $gambar = getOutletImage($o);

    // Buat array fasilitas tambahan dari boolean fields
    $fasilitasTambahan = [];
    if ($o->tersedia_toilet) $fasilitasTambahan[] = 'Toilet';
    if ($o->tersedia_musholla) $fasilitasTambahan[] = 'Musholla';
    if ($o->tersedia_atm) $fasilitasTambahan[] = 'ATM';
    if ($o->tersedia_wifi) $fasilitasTambahan[] = 'WiFi';

    // Gabungkan semua fasilitas
    $semuaFasilitas = array_merge($fasilitas, $fasilitasTambahan);

    // Jika kosong, tambahkan default
    if (empty($semuaFasilitas)) {
        $semuaFasilitas = ['Ruang Tunggu', 'Informasi Tiket'];
    }

    return [
        'id' => $o->id,
        'nama' => $o->nama_outlet,
        'cabang' => $o->branch ? $o->branch->nama_cabang : 'Tidak diketahui',
        'kota' => $o->branch ? $o->branch->kota : 'Tidak diketahui',
        'alamat' => $o->alamat_lengkap ?? $o->alamat,
        'telepon' => $o->telepon,
        'email' => $o->email,
        'fasilitas' => $semuaFasilitas,
        'jam_operasional' => $o->jam_operasional ?? '24 Jam',
        'tipe_outlet' => $o->tipe_outlet,
        'zona_pelayanan' => $o->zona_pelayanan,
        'kapasitas_parkir' => $o->kapasitas_parkir,
        'gambar' => $gambar,
        'foto_url' => $o->foto_url ?? null,
    ];
})->values();
@endphp

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    // Fungsi untuk submit form filter
    function submitFilterForm() {
        document.getElementById('filterForm').submit();
    }

    // Fungsi untuk reset filter
    function resetFilter() {
        // Reset input values
        document.getElementById('kotaInput').value = '';
        document.getElementById('branchInput').value = '';
        document.getElementById('branchIdInput').value = '';

        // Submit form
        document.getElementById('filterForm').submit();
    }

    // Handle branch selection from datalist
    document.addEventListener('DOMContentLoaded', function() {
        const branchInput = document.getElementById('branchInput');
        const branchIdInput = document.getElementById('branchIdInput');
        const branchOptions = document.getElementById('branchOptions');

        branchInput.addEventListener('input', function() {
            // Cari branch yang sesuai dengan input
            const inputValue = this.value.toLowerCase();
            let foundBranchId = null;

            // Loop melalui semua option di datalist
            Array.from(branchOptions.options).forEach(option => {
                if (option.value.toLowerCase() === inputValue) {
                    foundBranchId = option.getAttribute('data-id');
                }
            });

            // Set hidden input untuk branch_id
            branchIdInput.value = foundBranchId || '';

            // Jika tidak ada yang cocok, clear hidden input
            if (!foundBranchId) {
                // Optional: submit form untuk mencari cabang berdasarkan nama
                setTimeout(() => {
                    branchIdInput.value = '';
                }, 100);
            }
        });

        // Tambahkan event listener untuk enter key
        branchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFilterForm();
            }
        });

        const kotaInput = document.getElementById('kotaInput');
        kotaInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFilterForm();
            }
        });

        // Auto-submit ketika memilih dari datalist (untuk browser yang support)
        branchInput.addEventListener('change', function() {
            setTimeout(submitFilterForm, 100);
        });

        if (kotaInput) {
            kotaInput.addEventListener('change', function() {
                setTimeout(submitFilterForm, 100);
            });
        }
    });

    // Data outlets diambil dari server (Blade -> JS)
    const outletsData = @json($outletsArray);
    const placeholderImage = "{{ asset('images/placeholder-outlet.jpg') }}";

    // Icon mapping untuk fasilitas
    const facilityIcons = {
        'Toilet': 'fas fa-restroom',
        'Musholla': 'fas fa-mosque',
        'ATM': 'fas fa-money-bill-wave',
        'WiFi': 'fas fa-wifi',
        'AC': 'fas fa-snowflake',
        'Ruang Tunggu': 'fas fa-couch',
        'Parkir': 'fas fa-parking',
        'Cafe': 'fas fa-coffee',
        'Restoran': 'fas fa-utensils',
        'Mini Market': 'fas fa-store',
        'Toilet Disabilitas': 'fas fa-wheelchair',
        'Ruang Menyusui': 'fas fa-baby',
        'Area Merokok': 'fas fa-smoking',
        '24 Jam': 'fas fa-clock',
        'Informasi Tiket': 'fas fa-ticket-alt',
    };

    // Utility: cari outlet berdasarkan id
    function getOutletById(id) {
        return outletsData.find(o => Number(o.id) === Number(id));
    }

    // Get icon for facility
    function getFacilityIcon(facility) {
        for (const [key, icon] of Object.entries(facilityIcons)) {
            if (facility.toLowerCase().includes(key.toLowerCase())) {
                return icon;
            }
        }
        return 'fas fa-check-circle';
    }

    // Tampilkan popup dengan layout: Foto di ATAS, INFO DI BAWAH
    function showOutletPopup(id) {
        const outlet = getOutletById(id);
        const popupCard = document.getElementById('popupCard');
        const popupOverlay = document.getElementById('popupOverlay');

        if (!outlet || !popupCard || !popupOverlay) return;

        // build fasilitas HTML dari array fasilitas
        let facilitiesHtml = '';
        if (outlet.fasilitas && outlet.fasilitas.length) {
            outlet.fasilitas.forEach(f => {
                const icon = getFacilityIcon(f);
                facilitiesHtml += `
                    <div class="popup-facility-item">
                        <i class="${icon}"></i>
                        <span>${escapeHtml(f)}</span>
                    </div>
                `;
            });
        } else {
            facilitiesHtml = `
                <div class="no-facilities">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data fasilitas
                </div>
            `;
        }

        const contentHtml = `
            <div class="popup-header" id="popupTitle">
                ${escapeHtml(outlet.nama)}
                <button class="btn-close-popup" aria-label="Tutup" onclick="hideOutletPopup()">×</button>
            </div>

            <!-- FOTO DI ATAS -->
            <div class="popup-top-image">
                <img src="${escapeHtml(outlet.gambar)}"
                     alt="${escapeHtml(outlet.nama)}"
                     onerror="this.onerror=null;this.src='${placeholderImage}'">
            </div>

            <!-- INFO DI BAWAH dalam 2 kolom -->
            <div class="popup-content">
                <!-- Alamat Lengkap (Full Width) -->
                <div class="full-width-section">
                    <div class="popup-label">
                        <i class="fas fa-map-marker-alt"></i>
                        ALAMAT LENGKAP
                    </div>
                    <div class="popup-value">${escapeHtml(outlet.alamat)}</div>
                </div>

                <!-- Grid 2 Kolom untuk Info Lainnya -->
                <div class="popup-two-columns">
                    <!-- Kolom Kiri -->
                    <div class="popup-left-column">
                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-store"></i>
                                CABANG
                            </div>
                            <div class="popup-value">${escapeHtml(outlet.cabang)}</div>
                        </div>

                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-city"></i>
                                KOTA
                            </div>
                            <div class="popup-value">${escapeHtml(outlet.kota)}</div>
                        </div>

                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-tag"></i>
                                TIPE OUTLET
                            </div>
                            <div class="popup-value">${escapeHtml(outlet.tipe_outlet || 'Standard')}</div>
                        </div>

                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-clock"></i>
                                JAM OPERASIONAL
                            </div>
                            <div class="popup-value">${escapeHtml(outlet.jam_operasional)}</div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="popup-right-column">
                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-map-marked-alt"></i>
                                ZONA PELAYANAN
                            </div>
                            <div class="popup-value">${escapeHtml(outlet.zona_pelayanan || 'Seluruh kota')}</div>
                        </div>

                        <div class="popup-info-item">
                            <div class="popup-label">
                                <i class="fas fa-car"></i>
                                KAPASITAS PARKIR
                            </div>
                            <div class="popup-value">${outlet.kapasitas_parkir || 0} kendaraan</div>
                        </div>

                        <!-- Kontak Section -->
                        <div class="popup-contact-section">
                            <div class="popup-info-item">
                                <div class="popup-label">
                                    <i class="fas fa-phone"></i>
                                    TELEPON
                                </div>
                                <div class="popup-value">${escapeHtml(outlet.telepon || '-')}</div>
                            </div>

                            <div class="popup-info-item">
                                <div class="popup-label">
                                    <i class="fas fa-envelope"></i>
                                    EMAIL
                                </div>
                                <div class="popup-value">${escapeHtml(outlet.email || '-')}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas (Full Width di bawah) -->
                <div class="popup-facilities">
                    <div class="popup-facilities-label">
                        <i class="fas fa-star"></i>
                        FASILITAS
                    </div>
                    <div class="popup-facilities-grid">
                        ${facilitiesHtml}
                    </div>
                </div>
            </div>
        `;

        popupCard.innerHTML = contentHtml;
        popupOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // fokus ke tombol close untuk aksesibilitas
        const closeBtn = popupCard.querySelector('.btn-close-popup');
        if (closeBtn) closeBtn.focus();
    }

    function hideOutletPopup() {
        const popupOverlay = document.getElementById('popupOverlay');
        if (!popupOverlay) return;
        popupOverlay.style.display = 'none';
        document.body.style.overflow = '';
        const popupCard = document.getElementById('popupCard');
        if (popupCard) popupCard.innerHTML = '';
    }

    // Escape HTML untuk mencegah XSS jika data tidak trusted
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Tutup popup saat klik di luar card
    document.addEventListener('click', function(event) {
        const popupOverlay = document.getElementById('popupOverlay');
        if (!popupOverlay) return;
        // jika klik tepat pada overlay (bukan isi)
        if (event.target === popupOverlay) {
            hideOutletPopup();
        }
    });

    // Tutup popup dengan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') hideOutletPopup();
    });

    // Cek apakah background image berhasil dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const outletPage = document.getElementById('outletPage');
        const bgImage = new Image();

        const localImageUrl = "{{ asset('images/indonesia.jpeg') }}";

        bgImage.onerror = function() {
            outletPage.classList.add('no-bg');
        };

        bgImage.src = localImageUrl;
    });
</script>
@endpush
