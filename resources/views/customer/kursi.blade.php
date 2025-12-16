@extends('layouts.app')

@section('title', 'Smart Shuttle - Pilih Kursi')

@push('styles')
<style>
    /* Main Container */
    .tiket-page {
        min-height: 100vh;
        background: #F5F5F5;
    }

    .tiket-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        padding-bottom: 120px;
    }

    .tiket-layout {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    @media (min-width: 1024px) {
        .tiket-layout {
            flex-direction: row;
        }
    }

    /* Columns */
    .left-column, .right-column {
        flex: 1;
    }

    /* Cards */
    .detail-card, .info-card, .seat-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .seat-card {
        height: fit-content;
    }

    .card-content {
        padding: 24px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #00215E;
        margin-bottom: 16px;
    }

    /* Shuttle Info Summary */
    .shuttle-info-summary {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #FF581E;
    }

    .shuttle-name {
        font-size: 16px;
        font-weight: 600;
        color: #00215E;
        margin-bottom: 8px;
    }

    .shuttle-detail {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
    }

    /* Fasilitas Badges */
    .fasilitas-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }

    .badge-fasilitas {
        background: linear-gradient(135deg, #E8F4FD 0%, #D4E9FA 100%);
        color: #00215E;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #BFDCF7;
        box-shadow: 0 2px 4px rgba(0, 33, 94, 0.1);
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .badge-fasilitas:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 33, 94, 0.15);
    }

    .price-info {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .price {
        font-size: 18px;
        font-weight: 700;
        color: #FF581E;
    }

    .per-seat {
        font-size: 12px;
        color: #666;
    }

    /* Journey Info */
    .journey-info {
        margin-bottom: 24px;
    }

    .route-title {
        font-size: 18px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 8px;
    }

    .journey-detail {
        font-size: 14px;
        color: #00215E;
        margin-bottom: 4px;
    }

    /* Route Section */
    .route-section {
        margin-top: 16px;
    }

    .section-subtitle {
        font-size: 14px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 12px;
    }

    .route-timeline {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .route-point {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .point-marker {
        width: 12px;
        height: 12px;
        background: #FF581E;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .point-info {
        flex: 1;
    }

    .point-location {
        font-size: 14px;
        font-weight: 500;
        color: #00215E;
        margin-bottom: 2px;
    }

    .point-time {
        font-size: 12px;
        color: #666666;
    }

    .route-connector {
        margin-left: 6px;
        border-left: 2px dashed #d1d5db;
        height: 16px;
    }

    /* Route Details Toggle Button */
    .route-details-toggle {
        margin-top: 16px;
        display: flex;
        justify-content: center;
    }

    .btn-route-toggle {
        background: #00215E;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-route-toggle:hover {
        background: #0038A8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 33, 94, 0.2);
    }

    .btn-route-toggle i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .btn-route-toggle.active i {
        transform: rotate(180deg);
    }

    /* Route Details Dropdown */
    .route-details-dropdown {
        margin-top: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
    }

    .route-details-dropdown.show {
        max-height: 400px;
    }

    .route-details-content {
        padding: 20px;
        max-height: 350px;
        overflow-y: auto;
    }

    .route-detail-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .route-detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .route-detail-title {
        font-size: 14px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .route-detail-title i {
        font-size: 12px;
    }

    .route-stop {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-left: 8px;
    }

    .stop-marker {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .stop-marker.origin {
        background: #00215E;
    }

    .stop-marker.stop {
        background: #94a3b8;
    }

    .stop-marker.destination {
        background: #10b981;
    }

    .stop-info {
        flex: 1;
    }

    .stop-name {
        font-size: 13px;
        font-weight: 500;
        color: #00215E;
        margin-bottom: 2px;
    }

    .stop-time {
        font-size: 11px;
        color: #666;
        margin-bottom: 4px;
    }

    .stop-outlet {
        font-size: 10px;
        color: #FF581E;
        background: rgba(255, 88, 30, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    /* Seat Legend - SIMPLIFIED */
    .seat-legend {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .legend-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .seat-box {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seat-box.available {
        background: white;
        border: 2px solid #d1d5db;
    }

    .seat-box.sold {
        background: #D9D9D9;
    }

    .seat-box.selected {
        background: #FF581E;
    }

    .legend-text {
        font-size: 14px;
        color: #00215E;
        font-weight: 500;
    }

    /* Info Notice */
    .info-notice {
        background: #fef3cd;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ffeaa7;
    }

    .notice-text {
        font-size: 12px;
        color: #00215E;
        line-height: 1.5;
    }

    /* Price Summary - ENHANCED DESIGN */
    .price-summary {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .price-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #FF581E, #00215E);
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .price-item:last-child {
        border-bottom: none;
    }

    .price-label {
        font-size: 14px;
        color: #00215E;
        font-weight: 500;
    }

    .price-value {
        font-size: 14px;
        font-weight: 600;
        color: #00215E;
    }

    .price-item.discount .price-value {
        color: #10b981;
        font-weight: 700;
    }

    .price-item.total {
        margin-top: 8px;
        padding-top: 16px;
        padding-bottom: 0;
        border-top: 2px solid rgba(255, 88, 30, 0.2);
        border-bottom: none;
    }

    .price-item.total .price-label {
        font-size: 16px;
        font-weight: 700;
        color: #00215E;
    }

    .price-item.total .price-value {
        font-size: 20px;
        font-weight: 800;
        color: #FF581E;
        text-shadow: 0 1px 2px rgba(255, 88, 30, 0.1);
    }

    .price-breakdown {
        font-size: 11px;
        color: #666;
        margin-left: 4px;
        font-weight: normal;
        opacity: 0.8;
    }

    /* Driver Section */
    .driver-section {
        margin-bottom: 24px;
        text-align: center;
    }

    .driver-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .driver-indicator i {
        color: #00215E;
        font-size: 14px;
    }

    .driver-text {
        font-size: 14px;
        font-weight: 500;
        color: #00215E;
    }

    /* Seat Grid - 9 KURSI (3x3) */
    .seat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    .seat {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        position: relative;
    }

    .seat.available {
        background: white;
        border: 2px solid #d1d5db;
        color: #00215E;
    }

    .seat.available:hover {
        background: #f8f9fa;
        border-color: #FF581E;
    }

    .seat.sold {
        background: #D9D9D9;
        color: white;
        cursor: not-allowed;
    }

    .seat.selected {
        background: #FF581E;
        color: white;
    }

    /* Selected Seats */
    .selected-seats {
        background: #dbeafe;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
        margin-bottom: 24px;
    }

    .selected-title {
        font-size: 14px;
        font-weight: 600;
        color: #00215E;
        margin-bottom: 8px;
    }

    .selected-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        min-height: 20px;
    }

    .seat-badge-item {
        background: #FF581E;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .total-price {
        text-align: right;
        padding-top: 8px;
        border-top: 1px solid #bfdbfe;
    }

    .total-amount {
        font-size: 16px;
        font-weight: 700;
        color: #FF581E;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .payment-btn, .btn-secondary {
        flex: 1;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .payment-btn {
        background: #FF581E;
        color: white;
    }

    .payment-btn:hover:not(:disabled) {
        background: #E54E1A;
    }

    .payment-btn:disabled {
        background: #D9D9D9;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: white;
        color: #00215E;
        border: 2px solid #00215E;
    }

    .btn-secondary:hover {
        background: #00215E;
        color: white;
    }

    /* Additional styles for seat layout */
    .seat-status-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        font-size: 12px;
        opacity: 0.7;
    }

    .available-icon {
        color: #28a745;
    }

    .selected-icon {
        color: white;
    }

    .seat.sold .seat-status-icon {
        color: white;
    }

    .seat-number {
        font-weight: bold;
        font-size: 16px;
    }

    .seat-premium-badge {
        display: block;
        font-size: 9px;
        background: gold;
        color: #000;
        padding: 2px 4px;
        border-radius: 3px;
        margin-top: 2px;
    }

    .seat-price-extra {
        display: block;
        font-size: 10px;
        color: #ff581e;
        font-weight: bold;
    }

    .seat-badge-item .remove-seat {
        font-size: 10px;
        opacity: 0.8;
    }

    .seat-badge-item .remove-seat:hover {
        opacity: 1;
    }

    .payment-btn.disabled {
        background: #D9D9D9;
        cursor: not-allowed;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tiket-container {
            padding: 16px;
            padding-bottom: 100px;
        }

        .card-content {
            padding: 16px;
        }

        .seat-legend {
            grid-template-columns: repeat(2, 1fr);
        }

        .seat-grid {
            max-width: 250px;
            grid-template-columns: repeat(3, 1fr);
        }

        .seat {
            width: 50px;
            height: 50px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .fasilitas-badges {
            gap: 4px;
        }

        .badge-fasilitas {
            font-size: 10px;
            padding: 4px 8px;
        }

        .route-details-content {
            padding: 16px;
            max-height: 300px;
        }
    }

    @media (max-width: 480px) {
        .tiket-container {
            padding: 12px;
            padding-bottom: 80px;
        }

        .card-title {
            font-size: 18px;
        }

        .seat-legend {
            grid-template-columns: 1fr;
        }

        .seat-grid {
            max-width: 200px;
        }

        .seat {
            width: 45px;
            height: 45px;
            font-size: 14px;
        }

        .fasilitas-badges {
            grid-template-columns: repeat(2, 1fr);
            display: grid;
        }

        .badge-fasilitas {
            font-size: 9px;
            padding: 3px 6px;
            text-align: center;
        }

        .price-summary {
            padding: 16px;
        }

        .price-item.total .price-value {
            font-size: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="tiket-page">
    <!-- Main Content -->
    <main class="tiket-container">
        <div class="tiket-layout">

           <!-- Left Column - DETAIL PERJALANAN & INFORMASI KURSI -->
<div class="left-column">
    <!-- DETAIL PERJALANAN -->
    <div class="detail-card">
        <div class="card-content">
            <h2 class="card-title">DETAIL PERJALANAN</h2>

            <div class="journey-info">
                <h3 class="route-title" id="route-title">
                    @if(isset($pemesanan) && $pemesanan->jadwal)
                        {{ $pemesanan->jadwal->rute_pertama->kota_asal ?? 'Kota Asal' }} →
                        {{ $pemesanan->jadwal->rute_terakhir->kota_tujuan ?? 'Kota Tujuan' }}
                    @else
                        {{ request('kota_asal', 'Jakarta') }} → {{ request('kota_tujuan', 'Jatinangor') }}
                    @endif
                </h3>
                <p class="journey-detail" id="journey-date">
                    @if(isset($pemesanan) && $pemesanan->jadwal)
                        @php
                            $date = \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan);
                            // Format hari dalam Bahasa Indonesia
                            $hari = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu'
                            ];
                            $hariIndo = $hari[$date->format('l')];

                            // Format bulan dalam Bahasa Indonesia
                            $bulan = [
                                'January' => 'Januari',
                                'February' => 'Februari',
                                'March' => 'Maret',
                                'April' => 'April',
                                'May' => 'Mei',
                                'June' => 'Juni',
                                'July' => 'Juli',
                                'August' => 'Agustus',
                                'September' => 'September',
                                'October' => 'Oktober',
                                'November' => 'November',
                                'December' => 'Desember'
                            ];
                            $bulanIndo = $bulan[$date->format('F')];

                            echo $hariIndo . ', ' . $date->format('d') . ' ' . $bulanIndo . ' ' . $date->format('Y');
                        @endphp
                    @else
                        @php
                            $departureDate = request('departure_date');
                            if ($departureDate) {
                                $date = \Carbon\Carbon::parse($departureDate);
                                // Format hari dalam Bahasa Indonesia
                                $hari = [
                                    'Sunday' => 'Minggu',
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu'
                                ];
                                $hariIndo = $hari[$date->format('l')];

                                // Format bulan dalam Bahasa Indonesia
                                $bulan = [
                                    'January' => 'Januari',
                                    'February' => 'Februari',
                                    'March' => 'Maret',
                                    'April' => 'April',
                                    'May' => 'Mei',
                                    'June' => 'Juni',
                                    'July' => 'Juli',
                                    'August' => 'Agustus',
                                    'September' => 'September',
                                    'October' => 'Oktober',
                                    'November' => 'November',
                                    'December' => 'Desember'
                                ];
                                $bulanIndo = $bulan[$date->format('F')];

                                echo $hariIndo . ', ' . $date->format('d') . ' ' . $bulanIndo . ' ' . $date->format('Y');
                            } else {
                                echo 'Sabtu, 15 November 2025';
                            }
                        @endphp
                    @endif
                </p>
                <p class="journey-detail" id="journey-time">
                    @if(isset($pemesanan) && $pemesanan->jadwal)
                        {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i') }} WIB
                    @else
                        09:00 WIB
                    @endif
                </p>
                <p class="journey-detail" id="passenger-count">
                    @if(isset($pemesanan))
                        {{ $pemesanan->jumlah_penumpang }} Penumpang
                    @else
                        {{ request('passengers', 1) }} Penumpang
                    @endif
                </p>
            </div>

                        <!-- Rute Perjalanan -->
                        <div class="route-section">
                            <h4 class="section-subtitle">Rute perjalanan</h4>
                            <div class="route-timeline" id="route-timeline">
                                @if(isset($pemesanan) && $pemesanan->jadwal && $pemesanan->jadwal->rutes)
                                    @php
                                        $waktu = \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan);
                                    @endphp
                                    @foreach($pemesanan->jadwal->rutes as $index => $rute)
                                        <div class="route-point">
                                            <div class="point-marker"></div>
                                            <div class="point-info">
                                                <p class="point-location">{{ $rute->kota_asal }}</p>
                                                <p class="point-time">{{ $waktu->format('H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="route-connector"></div>
                                        @php
                                            $durasiParts = explode(':', $rute->durasi);
                                            $durasiMenit = ((int)$durasiParts[0] * 60) + ((int)($durasiParts[1] ?? 0));
                                            $waktu->addMinutes($durasiMenit);
                                        @endphp
                                        <div class="route-point">
                                            <div class="point-marker"></div>
                                            <div class="point-info">
                                                <p class="point-location">{{ $rute->kota_tujuan }}</p>
                                                <p class="point-time">{{ $waktu->format('H:i') }}</p>
                                            </div>
                                        </div>
                                        @if($index < count($pemesanan->jadwal->rutes) - 1)
                                            <div class="route-connector"></div>
                                        @endif
                                    @endforeach
                                @else
                                    <!-- Default route jika tidak ada data -->
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">Jakarta Pusat</p>
                                            <p class="point-time">09.00 WIB</p>
                                        </div>
                                    </div>
                                    <div class="route-connector"></div>
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">Rest Area Bush Batu</p>
                                            <p class="point-time">11.30 WIB</p>
                                        </div>
                                    </div>
                                    <div class="route-connector"></div>
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">Jatinangor</p>
                                            <p class="point-time">12.30 WIB</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Route Details Toggle Button -->
                            <div class="route-details-toggle">
                                <button class="btn-route-toggle" onclick="toggleRouteDetails()">
                                    <i class="fas fa-chevron-down"></i> Lihat Rute Detail
                                </button>
                            </div>

                            <!-- Route Details Dropdown -->
                            <div class="route-details-dropdown" id="route-details-dropdown">
                                <div class="route-details-content">
                                    @if(isset($pemesanan) && $pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->count() > 0)
                                        @foreach($pemesanan->jadwal->rutes as $index => $rute)
                                            <div class="route-detail-item">
                                                <div class="route-detail-title">
                                                    <i class="fas fa-route"></i> {{ $rute->nama_rute ?? 'Rute ' . ($index + 1) }}
                                                </div>

                                                <!-- Origin -->
                                                <div class="route-stop">
                                                    <div class="stop-marker origin"></div>
                                                    <div class="stop-info">
                                                        <div class="stop-name">{{ $rute->kota_asal }}</div>
                                                        <div class="stop-time">
                                                            Keberangkatan: {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i') }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Stops (Simplified - show only first 2 stops if many) -->
                                                @php
                                                    $stops = json_decode($rute->rute_pemberhentian, true) ?? [];
                                                    $currentTime = \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan);
                                                    $showLimit = 2; // Limit to show only 2 stops to keep it simple
                                                @endphp

                                                @foreach(array_slice($stops, 0, $showLimit) as $stopIndex => $stop)
                                                    @php
                                                        $currentTime->addMinutes(30);
                                                    @endphp
                                                    <div class="route-stop">
                                                        <div class="stop-marker stop"></div>
                                                        <div class="stop-info">
                                                            <div class="stop-name">{{ $stop['kota'] ?? 'Kota Singgah' }}</div>
                                                            <div class="stop-time">
                                                                {{ $currentTime->format('H:i') }} • Durasi: {{ $stop['durasi_singgah'] ?? 10 }} menit
                                                            </div>
                                                            @if(!empty($stop['outlets']))
                                                            <div class="stop-outlet">
                                                                Outlet: {{ is_array($stop['outlets']) ? implode(', ', array_slice($stop['outlets'], 0, 2)) : $stop['outlets'] }}
                                                                @if(count($stop['outlets']) > 2)
                                                                    +{{ count($stop['outlets']) - 2 }} lainnya
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @php
                                                        $currentTime->addMinutes($stop['durasi_singgah'] ?? 10);
                                                    @endphp
                                                @endforeach

                                                @if(count($stops) > $showLimit)
                                                    <div class="route-stop">
                                                        <div class="stop-marker stop"></div>
                                                        <div class="stop-info">
                                                            <div class="stop-name">+{{ count($stops) - $showLimit }} pemberhentian lainnya</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Destination -->
                                                @php
                                                    $currentTime->addMinutes(30);
                                                @endphp
                                                <div class="route-stop">
                                                    <div class="stop-marker destination"></div>
                                                    <div class="stop-info">
                                                        <div class="stop-name">{{ $rute->kota_tujuan }}</div>
                                                        <div class="stop-time">
                                                            Kedatangan: {{ $currentTime->format('H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p style="text-align: center; color: #666; padding: 20px;">
                                            Informasi rute detail tidak tersedia
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFORMASI KURSI - SIMPLIFIED -->
                <div class="info-card">
                    <div class="card-content">
                        <h2 class="card-title">INFORMASI KURSI</h2>

                        <!-- Kotak warna seukuran data kursi -->
                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="seat-box available"></div>
                                <span class="legend-text">Tersedia</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-box sold"></div>
                                <span class="legend-text">Terjual</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-box selected"></div>
                                <span class="legend-text">Dipilih</span>
                            </div>
                        </div>

                        <div class="info-notice">
                            <p class="notice-text">
                                Penumpang harap datang di outlet 15 menit sebelum keberangkatan untuk mengkonfirmasi keberangkatan
                            </p>
                        </div>

                        <!-- Info Harga - ENHANCED DESIGN -->
                        @if(isset($pemesanan))
                        <div class="price-summary">
                            <div class="price-item">
                                <span class="price-label">Harga Dasar:</span>
                                <span class="price-value">
                                    Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}
                                    <span class="price-breakdown">/orang</span>
                                </span>
                            </div>
                            <div class="price-item">
                                <span class="price-label">Total Penumpang:</span>
                                <span class="price-value">{{ $pemesanan->jumlah_penumpang }} orang</span>
                            </div>
                            <div class="price-item">
                                <span class="price-label">Subtotal:</span>
                                <span class="price-value">
                                    Rp {{ number_format($pemesanan->harga_total * $pemesanan->jumlah_penumpang, 0, ',', '.') }}
                                    <span class="price-breakdown">
                                        ({{ $pemesanan->jumlah_penumpang }} × {{ number_format($pemesanan->harga_total, 0, ',', '.') }})
                                    </span>
                                </span>
                            </div>
                            @if($pemesanan->diskon > 0)
                            <div class="price-item discount">
                                <span class="price-label">Diskon:</span>
                                <span class="price-value">- Rp {{ number_format($pemesanan->diskon, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="price-item total">
                                <span class="price-label">Total Bayar:</span>
                                <span class="price-value">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - DATA KURSI -->
            <div class="right-column">
                <div class="seat-card">
                    <div class="card-content">
                        <h2 class="card-title">DATA KURSI</h2>

                        @if(!isset($pemesanan))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Data pemesanan tidak ditemukan.
                                <a href="{{ route('customer.search') }}" class="alert-link">Kembali ke pencarian</a>
                            </div>
                        @else
                        <!-- Informasi Shuttle dengan Fasilitas Badges -->
                        <div class="shuttle-info-summary">
                            <div class="shuttle-name" id="shuttle-name">{{ $pemesanan->jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle Standard 2' }}</div>

                            <div class="shuttle-detail" id="shuttle-detail">
                                @if(isset($pemesanan->jadwal->shuttle->fasilitas))
                                    @php
                                        // Pisahkan fasilitas berdasarkan koma
                                        $fasilitasArray = explode(',', $pemesanan->jadwal->shuttle->fasilitas);
                                    @endphp
                                    <div class="fasilitas-badges">
                                        @foreach($fasilitasArray as $fasilitas)
                                            <span class="badge-fasilitas">{{ trim($fasilitas) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Fallback jika tidak ada data fasilitas -->
                                    <div class="fasilitas-badges">
                                        <span class="badge-fasilitas">Share Shuttle</span>
                                        <span class="badge-fasilitas">AC</span>
                                        <span class="badge-fasilitas">WiFi</span>
                                        <span class="badge-fasilitas">Charger</span>
                                        <span class="badge-fasilitas">TV LED</span>
                                        <span class="badge-fasilitas">Snack Premium</span>
                                        <span class="badge-fasilitas">Mineral Water</span>
                                    </div>
                                @endif
                            </div>

                            <div class="price-info">
                                <span class="price" id="shuttle-price">Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}</span>
                                <span class="per-seat">/kursi</span>
                            </div>
                        </div>

                        <!-- Bagian Pengemudi -->
                        <div class="driver-section">
                            <div class="driver-indicator">
                                <i class="fas fa-steering-wheel"></i>
                                <span class="driver-text">Kursi Pengemudi</span>
                            </div>
                        </div>

                        <!-- Form untuk memilih kursi -->
                        <form id="kursi-form" action="{{ route('customer.kursi.proses') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">

                            <!-- Seat Grid - Layout STABIL dari database -->
                            <div class="seat-grid" id="seat-grid">
                                @if(!empty($layoutKursi) && is_array($layoutKursi))
                                    <!-- MENAMPILKAN SEMUA KURSI DARI LAYOUT FIX -->
                                    @foreach($layoutKursi as $kursi)
                                        @php
                                            // Tentukan status: 'terpesan' atau 'tersedia'
                                            // Status sudah ada di $layoutKursi dari controller
                                            $status = $kursi['status'] ?? 'tersedia';
                                            $terpesan = $status === 'terpesan';
                                        @endphp

                                        <div class="seat {{ $terpesan ? 'sold' : 'available' }}"
                                             data-seat="{{ $kursi['nomor'] }}"
                                             data-harga="{{ $pemesanan->harga_total + ($kursi['harga_tambahan'] ?? 0) }}"
                                             data-status="{{ $status }}"
                                             onclick="selectSeat(this, {{ !$terpesan ? 'true' : 'false' }}, '{{ $kursi['nomor'] }}')">

                                            <span class="seat-number">{{ $kursi['nomor'] }}</span>

                                            @if(isset($kursi['tipe']) && $kursi['tipe'] === 'premium')
                                                <small class="seat-premium-badge">PREMIUM</small>
                                            @endif

                                            @if(isset($kursi['harga_tambahan']) && $kursi['harga_tambahan'] > 0)
                                                <small class="seat-price-extra">+{{ number_format($kursi['harga_tambahan'], 0, ',', '.') }}</small>
                                            @endif

                                            <!-- Icon status -->
                                            @if($terpesan)
                                                <i class="fas fa-lock seat-status-icon"></i>
                                            @else
                                                <i class="fas fa-check seat-status-icon available-icon"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <!-- FALLBACK: Generate 9 kursi default (jika layout kosong) -->
                                    @php
                                        $totalKursi = $pemesanan->jadwal->shuttle->total_kursi ?? 9;
                                        $rows = ceil($totalKursi / 3);
                                        $kursiCounter = 1;
                                    @endphp

                                    @for($row = 1; $row <= $rows; $row++)
                                        @for($col = 1; $col <= 3; $col++)
                                            @if($kursiCounter <= $totalKursi)
                                                @php
                                                    $seatNumber = $row . chr(64 + $col);
                                                    // Cek apakah kursi ini terpesan
                                                    $isTerpesan = in_array($seatNumber, $kursiTerpesan ?? []);
                                                @endphp

                                                <div class="seat {{ $isTerpesan ? 'sold' : 'available' }}"
                                                     data-seat="{{ $seatNumber }}"
                                                     data-harga="{{ $pemesanan->harga_total }}"
                                                     data-status="{{ $isTerpesan ? 'terpesan' : 'tersedia' }}"
                                                     onclick="selectSeat(this, {{ !$isTerpesan ? 'true' : 'false' }}, '{{ $seatNumber }}')">

                                                    <span class="seat-number">{{ $seatNumber }}</span>

                                                    @if($isTerpesan)
                                                        <i class="fas fa-lock seat-status-icon"></i>
                                                    @else
                                                        <i class="fas fa-check seat-status-icon available-icon"></i>
                                                    @endif
                                                </div>

                                                @php $kursiCounter++; @endphp
                                            @endif
                                        @endfor
                                    @endfor
                                @endif
                            </div>

                            <!-- Input hidden untuk menyimpan kursi yang dipilih -->
                            <div id="selected-seats-inputs">
                                <!-- Akan diisi oleh JavaScript -->
                            </div>

                            <!-- Informasi kursi yang dipilih -->
                            <div class="selected-seats">
                                <h4 class="selected-title">Kursi yang Dipilih (<span id="selected-count">0</span>/<span id="max-seats">{{ $pemesanan->jumlah_penumpang }}</span>):</h4>
                                <div class="selected-list" id="selected-list">
                                    <!-- Daftar kursi yang dipilih akan muncul di sini -->
                                </div>
                                <div class="total-price" id="total-price-container" style="display: none;">
                                    <div class="d-flex justify-content-between">
                                        <span>Total: </span>
                                        <span class="total-amount" id="total-amount">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="action-buttons">
                                <button type="button" class="btn-secondary" onclick="window.history.back()">
                                    Kembali
                                </button>
                                <button type="submit" class="payment-btn" id="payment-btn" disabled>
                                    Lanjutkan ke Detail Pesanan
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedSeats = [];
        const maxSeats = {{ $pemesanan->jumlah_penumpang ?? 1 }};
        const basePrice = {{ $pemesanan->harga_total ?? 150000 }};
        const discount = {{ $pemesanan->diskon ?? 0 }};
        const pemesananId = {{ $pemesanan->id }};
        const jadwalId = {{ $pemesanan->jadwal_id }};

        // Fungsi untuk memilih kursi
        window.selectSeat = function(seatElement, isAvailable, seatNumber) {
            if (!isAvailable) {
                alert(`Kursi ${seatNumber} sudah terpesan. Silakan pilih kursi lain.`);
                return;
            }

            const seatId = seatElement.getAttribute('data-seat');
            const seatPrice = parseInt(seatElement.getAttribute('data-harga'));

            // Cek jika kursi sudah dipilih
            const seatIndex = selectedSeats.findIndex(seat => seat.id === seatId);

            if (seatIndex > -1) {
                // Batalkan pemilihan
                selectedSeats.splice(seatIndex, 1);
                seatElement.classList.remove('selected');
                seatElement.classList.add('available');
                if (seatElement.querySelector('.seat-status-icon')) {
                    seatElement.querySelector('.seat-status-icon').className = 'fas fa-check seat-status-icon available-icon';
                }
            } else {
                // Cek apakah sudah mencapai batas maksimal
                if (selectedSeats.length >= maxSeats) {
                    alert(`Anda hanya dapat memilih maksimal ${maxSeats} kursi.`);
                    return;
                }

                // Tambahkan kursi yang dipilih
                selectedSeats.push({
                    id: seatId,
                    number: seatNumber,
                    price: seatPrice
                });

                seatElement.classList.remove('available');
                seatElement.classList.add('selected');
                if (seatElement.querySelector('.seat-status-icon')) {
                    seatElement.querySelector('.seat-status-icon').className = 'fas fa-user seat-status-icon selected-icon';
                }
            }

            updateSelectedSeatsDisplay();
            updateFormInputs();
            updatePaymentButton();
        }

        // Fungsi untuk memperbarui tampilan kursi yang dipilih
        function updateSelectedSeatsDisplay() {
            const selectedCount = document.getElementById('selected-count');
            const selectedList = document.getElementById('selected-list');
            const totalPriceContainer = document.getElementById('total-price-container');
            const totalAmount = document.getElementById('total-amount');

            // Update count
            selectedCount.textContent = selectedSeats.length;

            // Update list
            selectedList.innerHTML = '';
            let subtotal = 0;

            selectedSeats.forEach(seat => {
                const badge = document.createElement('span');
                badge.className = 'seat-badge-item';
                badge.innerHTML = `
                    ${seat.number}
                    <i class="fas fa-times remove-seat" onclick="removeSelectedSeat('${seat.id}')" style="cursor: pointer; margin-left: 5px;"></i>
                `;
                selectedList.appendChild(badge);

                subtotal += seat.price;
            });

            // Update total harga
            if (selectedSeats.length > 0) {
                const finalTotal = subtotal - discount;
                totalAmount.textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
                totalPriceContainer.style.display = 'block';
            } else {
                totalPriceContainer.style.display = 'none';
            }
        }

        // Fungsi untuk menghapus kursi yang dipilih
        window.removeSelectedSeat = function(seatId) {
            const seatElement = document.querySelector(`[data-seat="${seatId}"]`);
            if (seatElement) {
                const isAvailable = seatElement.getAttribute('data-status') === 'tersedia';
                const seatNumber = seatElement.querySelector('.seat-number')?.textContent || seatElement.textContent.trim();
                selectSeat(seatElement, isAvailable, seatNumber);
            }
        }

        // Fungsi untuk memperbarui input form
        function updateFormInputs() {
            const container = document.getElementById('selected-seats-inputs');
            container.innerHTML = '';

            selectedSeats.forEach((seat, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `kursi[${index}]`;
                input.value = seat.id;
                container.appendChild(input);
            });
        }

        // Fungsi untuk memperbarui status tombol pembayaran
        function updatePaymentButton() {
            const paymentBtn = document.getElementById('payment-btn');

            if (selectedSeats.length === maxSeats) {
                paymentBtn.disabled = false;
                paymentBtn.textContent = 'Lanjutkan ke Detail Pesanan';
                paymentBtn.classList.remove('disabled');
            } else {
                paymentBtn.disabled = true;
                paymentBtn.textContent = `Pilih ${maxSeats} Kursi (${selectedSeats.length}/${maxSeats})`;
                paymentBtn.classList.add('disabled');
            }
        }

        // Fungsi untuk toggle dropdown rute detail
        window.toggleRouteDetails = function() {
            const dropdown = document.getElementById('route-details-dropdown');
            const button = document.querySelector('.btn-route-toggle');

            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                button.classList.remove('active');
                button.innerHTML = '<i class="fas fa-chevron-down"></i> Lihat Rute Detail';
            } else {
                dropdown.classList.add('show');
                button.classList.add('active');
                button.innerHTML = '<i class="fas fa-chevron-up"></i> Tutup Rute Detail';
            }
        }

        // Validasi form sebelum submit - VERSI SEDERHANA
        document.getElementById('kursi-form').addEventListener('submit', function(e) {
            // Validasi jumlah kursi
            if (selectedSeats.length !== maxSeats) {
                e.preventDefault();
                alert(`Silakan pilih ${maxSeats} kursi terlebih dahulu.`);
                return;
            }

            // Tampilkan loading
            const paymentBtn = document.getElementById('payment-btn');
            paymentBtn.disabled = true;
            paymentBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

            // Submit form secara normal (tanpa validasi AJAX)
            // Form akan dikirim ke route 'customer.kursi.proses'
        });

        // Inisialisasi
        updatePaymentButton();

        // Debug info
        console.log('Pemesanan ID:', pemesananId);
        console.log('Jadwal ID:', jadwalId);
        console.log('Max Seats:', maxSeats);
        console.log('Layout Kursi:', @json($layoutKursi ?? []));
    });
</script>
@endpush
