@extends('layouts.app')

@section('title', 'Detail Pesanan - Smart Shuttle')

@push('styles')
<style>
    /* Reset dan gaya dasar */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        padding: 0;
    }

    /* Main Container */
    .detail-container {
        max-width: 1200px;
        margin: 100px auto 40px;
        padding: 0 20px;
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    /* CARD */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        padding: 28px;
        border: 1px solid rgba(0,0,0,0.08);
    }

    .card-left {
        flex: 0 0 45%;
        min-width: 320px;
    }

    .card-right {
        flex: 0 0 50%;
        min-width: 320px;
    }

    .section-header {
        color: #00215E;
        border-bottom: 2px solid #FF581E;
        padding-bottom: 12px;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 18px;
    }

    .dotted-line {
        border-bottom: 2px dashed #e0e0e0;
        margin: 20px 0;
    }

    /* Journey Route Display */
    .route-display {
        text-align: center;
        margin-bottom: 20px;
        padding: 15px;
        background: linear-gradient(135deg, #FFE8E0 0%, #FFF0EB 100%);
        border-radius: 12px;
        border: 2px solid #FF581E;
    }

    .city-name {
        font-size: 20px;
        font-weight: 700;
        color: #00215E;
        display: inline-block;
    }

    .route-arrow {
        color: #FF581E;
        font-size: 20px;
        margin: 0 15px;
        font-weight: bold;
    }

    /* Journey Details */
    .journey-details {
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
        min-width: 120px;
    }

    .detail-value {
        font-weight: 500;
        color: #333;
        text-align: right;
        flex: 1;
    }

    /* Shuttle Info */
    .shuttle-info {
        background: #f0f7ff;
        border-radius: 8px;
        padding: 15px;
        margin: 15px 0;
        border-left: 4px solid #00215E;
    }

    /* Fasilitas Badges */
    .fasilitas-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .fasilitas-badge {
        background: linear-gradient(135deg, #00215E 0%, #1a3d7c 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 4px rgba(0, 33, 94, 0.2);
    }

    .fasilitas-badge i {
        font-size: 10px;
    }

    .fasilitas-badge.wifi {
        background: linear-gradient(135deg, #FF581E 0%, #ff7b4d 100%);
    }

    .fasilitas-badge.charger {
        background: linear-gradient(135deg, #28a745 0%, #4caf50 100%);
    }

    .fasilitas-badge.toilet {
        background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
    }

    /* PRICE SUMMARY – SINKRON DENGAN KURSI.BLADE.PHP */
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

    /* Seat Number Badge */
    .seat-number {
        background-color: #FF581E;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 70px;
        font-size: 13px;
    }

    /* Data Pemesan Info */
    .info-section {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #FF581E;
    }

    .info-item {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #666;
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #00215E;
    }

    /* Table untuk Penumpang */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table, th, td {
        border: 1px solid #e0e0e0;
    }

    th {
        background-color: #00215E;
        color: white;
        font-weight: 600;
        padding: 12px;
        text-align: left;
        font-size: 13px;
    }

    td {
        padding: 12px;
        font-size: 13px;
    }

    tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tbody tr:hover {
        background-color: #f0f7ff;
    }

    /* Tarif Tambahan Section – Style Sama Seperti Kursi */
    .tarif-tambahan-box {
        margin: 12px 0;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #FF581E;
    }

    .tarif-tambahan-title {
        color: #00215E;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tarif-item {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 13px;
        border-bottom: 1px solid #e0e0e0;
    }

    .tarif-item:last-child {
        border-bottom: none;
    }

    .tarif-name {
        color: #00215E;
        font-weight: 600;
    }

    .tarif-price {
        color: #FF581E;
        font-weight: 700;
    }

    /* BUTTON */
    .btn-orange {
        background-color: #FF581E;
        color: white;
        border: none;
        padding: 15px;
        margin-top: 20px;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .btn-orange:hover:not(:disabled) {
        background-color: #e54e1a;
    }

    .btn-orange:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .secondary-button {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #dee2e6;
        padding: 10px;
        margin-top: 10px;
        border-radius: 8px;
        font-weight: 500;
        width: 100%;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .secondary-button:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    /* Info Box */
    .info-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        border-left: 4px solid #ffc107;
    }

    .info-box h3 {
        color: #d39e00;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .info-box ul {
        color: #856404;
        padding-left: 20px;
        font-size: 14px;
    }

    .info-box li {
        margin-bottom: 4px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .detail-container {
            flex-direction: column;
        }

        .card-left,
        .card-right {
            flex: 0 0 100%;
        }
    }

    @media (max-width: 768px) {
        .detail-container {
            margin-top: 80px;
            padding: 10px;
        }

        .card {
            padding: 20px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 8px;
        }

        .info-section {
            flex-direction: column;
            gap: 15px;
        }

        .city-name {
            font-size: 18px;
        }

        .fasilitas-badges {
            gap: 6px;
        }

        .fasilitas-badge {
            font-size: 11px;
            padding: 4px 10px;
        }
    }

    @media (max-width: 480px) {
        .detail-container {
            margin-top: 70px;
            gap: 15px;
        }

        .card {
            padding: 15px;
        }

        .section-header {
            font-size: 16px;
        }

        .city-name {
            font-size: 16px;
        }

        .route-arrow {
            font-size: 16px;
            margin: 0 10px;
        }

        table {
            font-size: 11px;
            overflow-x: auto;
        }

        th, td {
            padding: 6px;
        }

        .price-item.total .price-value {
            font-size: 18px;
        }
    }
</style>
@endpush

@section('content')

@php
    // ======================================================================
    //   INISIALISASI DATA HARGA – SINKRON 100% DENGAN KURSI.BLADE.PHP
    // ======================================================================
    if (!isset($pemesanan) || !$pemesanan) {
        echo '<div class="alert alert-danger">Data pemesanan tidak ditemukan.</div>';
        return;
    }

    $jadwal = $pemesanan->jadwal ?? null;
    $usesDriverJadwal = $usesDriverJadwal ?? false;
    $driverJadwal = $driverJadwal ?? null;
    $shuttle_obj = $usesDriverJadwal ? ($driverJadwal?->shuttle ?? ($jadwal?->shuttle ?? null)) : ($jadwal?->shuttle ?? null);

    // Rute: ambil dari rute_pertama / rute_terakhir atau dari koleksi rutes
    $from = 'Kota Asal';
    $to = 'Kota Tujuan';
    if ($jadwal) {
        if (isset($jadwal->rute_pertama) && $jadwal->rute_pertama) {
            $from = $jadwal->rute_pertama->kota_asal ?? $jadwal->rute_pertama->kota ?? $from;
        } elseif (isset($jadwal->rutes) && $jadwal->rutes->count() > 0) {
            $first = $jadwal->rutes->first();
            $from = $first->kota_asal ?? $first->kota ?? $from;
        }

        if (isset($jadwal->rute_terakhir) && $jadwal->rute_terakhir) {
            $to = $jadwal->rute_terakhir->kota_tujuan ?? $jadwal->rute_terakhir->kota ?? $to;
        } elseif (isset($jadwal->rutes) && $jadwal->rutes->count() > 0) {
            $last = $jadwal->rutes->last();
            $to = $last->kota_tujuan ?? $last->kota ?? $to;
        }
    }

    // Plat nomor shuttle
    $plat_nomor = $shuttle_obj->plat_nomor ?? ($jadwal->shuttle->plat_nomor ?? '-');

    // =====================================================
    //   KOMPONEN HARGA – PERSIS SEPERTI DI KURSI.BLADE.PHP
    // =====================================================
    $hargaPerOrang      = $jadwal->harga_total ?? 0;
    $jumlahPenumpang    = $pemesanan->jumlah_penumpang ?? 1;

    // Total tarif tambahan (dikirim oleh controller, fallback 0) - SAMA DENGAN KURSI.BLADE.PHP
    $totalTarif = $totalTarif ?? 0;

    $subtotal   = ($hargaPerOrang * $jumlahPenumpang) + $totalTarif;
    $diskon     = $diskon ?? ($pemesanan->diskon ?? 0);
    $totalBayar = max(0, $subtotal - $diskon);
@endphp

<!-- DETAIL CONTAINER -->
<div class="detail-container">

    <!-- CARD KIRI: DETAIL PERJALANAN + RINGKASAN HARGA -->
    <div class="card card-left">
        <h2 class="section-header">DETAIL PERJALANAN</h2>

        @if($pemesanan && isset($pemesanan->jadwal))
            {{-- Route Display --}}
            <div class="route-display">
                <span class="city-name">{{ $from }}</span>
                <span class="route-arrow">→</span>
                <span class="city-name">{{ $to }}</span>
            </div>

            {{-- Journey Details --}}
            <div class="journey-details">
                <div class="detail-row">
                    <span class="detail-label">Tanggal</span>
                    <span class="detail-value">
                        {{ $jadwal->tanggal_keberangkatan ? \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '-' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu</span>
                    <span class="detail-value">
                        {{ $jadwal->waktu_keberangkatan ? \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') : '-' }} WIB
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Penumpang</span>
                    <span class="detail-value">{{ $jumlahPenumpang }} Orang</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jenis Kendaraan</span>
                    <span class="detail-value">{{ $shuttle_obj->nama_shuttle ?? ($jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Plat Nomor</span>
                    <span class="detail-value">{{ $plat_nomor }}</span>
                </div>
            </div>

            {{-- Shuttle Info dengan Fasilitas Badges --}}
            @if($jadwal->shuttle)
                <div class="shuttle-info">
                    <div style="font-weight: 600; color: #00215E; margin-bottom: 8px;">
                        {{ $jadwal->shuttle->nama_shuttle }}
                    </div>
                    @if($jadwal->shuttle->fasilitas)
                        <div class="fasilitas-badges">
                            @php
                                $fasilitasArray = explode(',', $jadwal->shuttle->fasilitas);
                                $fasilitasMap = [
                                    'ac' => ['icon' => 'fas fa-snowflake', 'label' => 'AC'],
                                    'wifi' => ['icon' => 'fas fa-wifi', 'label' => 'WiFi'],
                                    'charger' => ['icon' => 'fas fa-bolt', 'label' => 'Charger'],
                                    'toilet' => ['icon' => 'fas fa-restroom', 'label' => 'Toilet'],
                                    'reclining seat' => ['icon' => 'fas fa-chair', 'label' => 'Reclining Seat'],
                                    'tv' => ['icon' => 'fas fa-tv', 'label' => 'TV'],
                                    'blanket' => ['icon' => 'fas fa-bed', 'label' => 'Blanket'],
                                    'snack' => ['icon' => 'fas fa-cookie-bite', 'label' => 'Snack'],
                                    'driver' => ['icon' => 'fas fa-user-tie', 'label' => 'Driver']
                                ];
                            @endphp

                            @foreach($fasilitasArray as $fasilitas)
                                @php
                                    $fasilitas = strtolower(trim($fasilitas));
                                    $found = false;

                                    foreach($fasilitasMap as $key => $value) {
                                        if(strpos($fasilitas, $key) !== false) {
                                            $icon = $value['icon'];
                                            $label = $value['label'];
                                            $found = true;
                                            break;
                                        }
                                    }

                                    if(!$found) {
                                        $icon = 'fas fa-check';
                                        $label = ucwords($fasilitas);
                                    }
                                @endphp

                                <span class="fasilitas-badge">
                                    <i class="{{ $icon }}"></i>
                                    {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- ===================================================== --}}
            {{--   RINGKASAN HARGA – SINKRON 100% DENGAN KURSI        --}}
            {{-- ===================================================== --}}
            <div class="price-summary">
                {{-- Harga tiket per orang --}}
                <div class="price-item">
                    <span class="price-label">Harga Tiket per orang</span>
                    <span class="price-value" id="harga-per-orang-label">Rp {{ number_format($hargaPerOrang, 0, ',', '.') }}</span>
                </div>

                {{-- Tarif Tambahan – persis seperti di kursi.blade.php --}}
                @if(!empty($availableTarifs) && count($availableTarifs) > 0)
                    <div class="tarif-tambahan-box">
                        <div class="tarif-tambahan-title">
                            <i class="fas fa-plus-circle"></i> Tarif Tambahan (per tiket):
                        </div>
                        @foreach($availableTarifs as $tarif)
                            @php
                                $nama = $tarif['nama_tarif'] ?? ($tarif['nama'] ?? 'Biaya Tambahan');
                                $nilai = $tarif['final_price'] ?? $tarif['harga_dasar'] ?? 0;
                            @endphp
                            <div class="tarif-item">
                                <span class="tarif-name">{{ $nama }}</span>
                                <span class="tarif-price">Rp {{ number_format($nilai, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Jumlah Penumpang --}}
                <div class="price-item">
                    <span class="price-label">Jumlah Penumpang</span>
                    <span class="price-value">× {{ $jumlahPenumpang }}</span>
                </div>

                {{-- Rincian jika ada tarif tambahan (detail subtotal) --}}
                @if($totalTarif > 0)
                    <div style="padding:8px 0; border-bottom:1px solid #e0e0e0; font-size:12px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                            <span style="color:#666;">Harga Dasar × {{ $jumlahPenumpang }}:</span>
                            <span class="fw-bold">Rp {{ number_format($hargaPerOrang * $jumlahPenumpang, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#666;">+ Total Tarif Tambahan:</span>
                            <span class="fw-bold" style="color:#FF581E;">
                                Rp {{ number_format($totalTarif, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Subtotal --}}
                <div class="price-item">
                    <span class="price-label">Subtotal</span>
                    <span class="price-value" id="subtotal-amount-left">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Diskon --}}
                <div class="price-item discount">
                    <span class="price-label">Diskon Promo</span>
                    <span class="price-value">
                        - Rp {{ number_format($diskon, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Total Bayar --}}
                <div class="price-item total">
                    <span class="price-label">Total Bayar</span>
                    <span class="price-value">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        @else
            <div class="alert alert-danger">
                Data jadwal tidak ditemukan
            </div>
        @endif
    </div>

    <!-- CARD KANAN: DATA PESANAN -->
    <div class="card card-right">
        <h2 class="section-header">DATA PESANAN</h2>

        {{-- Kode Booking --}}
        <div class="info-section">
            <div class="info-item">
                <span class="info-label">Kode Booking</span>
                <span class="info-value" style="color: #FF581E; font-size: 16px;">{{ $pemesanan->kode_booking }}</span>
            </div>
            <div class="info-item" style="text-align: right;">
                <span class="info-label">Status Pesanan</span>
                <span class="info-value">{{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}</span>
            </div>
        </div>

        <div class="dotted-line"></div>

        {{-- Data Pemesan --}}
        <h3 style="font-weight: 600; color: #00215E; font-size: 16px; margin-bottom: 15px;">
            <i class="fas fa-user-circle"></i> Data Pemesan
        </h3>

        <div class="info-section" style="border-left-color: #00215E;">
            <div class="info-item">
                <span class="info-label">Nama Pemesan</span>
                <span class="info-value">{{ $customer_name ?? $pemesanan->nama_pemesan ?? 'N/A' }}</span>
            </div>
            <div class="info-item" style="text-align: right;">
                <span class="info-label">Telepon</span>
                <span class="info-value">{{ $customer_phone ?? $pemesanan->telepon_pemesan ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="info-section" style="border-left-color: #28a745; background: #f8fff9; margin-top: 0;">
            <div class="info-item" style="width: 100%;">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $customer_email ?? $pemesanan->email_pemesan ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="dotted-line"></div>

        {{-- Data Penumpang --}}
        <h3 style="font-weight: 600; color: #00215E; font-size: 16px; margin-bottom: 15px;">
            <i class="fas fa-people-group"></i> Data Penumpang
        </h3>

        <p style="color: #666; margin-bottom: 15px; font-size: 13px;">
            <i class="fas fa-info-circle text-info"></i> Total: <strong>{{ $jumlahPenumpang }} orang</strong>
        </p>

        {{-- Tabel Penumpang --}}
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Penumpang</th>
                        <th style="width: 150px;">NIK</th>
                        <th style="width: 120px;">Telepon</th>
                        <th style="width: 140px;">Nomor Kursi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($penumpang) && count($penumpang) > 0)
                        @foreach($penumpang as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->nama_lengkap ?? 'N/A' }}</td>
                            <td style="font-family: monospace; font-size: 12px;">{{ $p->nik ?? '-' }}</td>
                            <td>{{ $p->telepon ?? '-' }}</td>
                            <td>
                                @if(!empty($p->nomor_kursi))
                                    <span class="seat-number">Kursi {{ $p->nomor_kursi }}</span>
                                @else
                                    <span style="color: #999;">Belum dipilih</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">Data penumpang tidak tersedia</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="dotted-line"></div>

        {{-- Action Buttons --}}
        <div style="margin-top: 30px;">
            @if($pemesanan->status == 'menunggu_konfirmasi')
                {{-- STEP 3: Konfirmasi detail pesanan dengan checkbox --}}
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #FF581E; margin-bottom: 20px;">
                    <h4 style="color: #00215E; margin-bottom: 15px; font-size: 14px;">
                        <i class="fas fa-clipboard-check"></i> Konfirmasi Data Pesanan
                    </h4>

                    <div style="margin-bottom: 15px;">
                        <label style="display: flex; align-items: center; cursor: pointer; font-size: 14px;">
                            <input type="checkbox"
                                   id="konfirmasi-checkbox"
                                   style="width: 20px; height: 20px; cursor: pointer; margin-right: 10px;"
                                   onchange="toggleKonfirmasiButton()">
                            <span style="color: #333;">
                                Saya telah memeriksa dan <strong>menyetujui semua data pesanan</strong> termasuk:
                            </span>
                        </label>
                    </div>

                    <ul style="margin: 15px 0 0 30px; color: #666; font-size: 13px; line-height: 1.8;">
                        <li> Rute dan jadwal perjalanan sudah benar</li>
                        <li> Data penumpang dan nomor kursi sudah sesuai</li>
                        <li> Total harga dan biaya tambahan sudah sesuai</li>
                        <li> Data pemesan (nama, telepon, email) sudah benar</li>
                    </ul>
                </div>

                {{-- Form untuk submit konfirmasi --}}
                <form action="{{ route('customer.detail_pemesanan.konfirmasi', ['kode_booking' => $pemesanan->kode_booking]) }}"
                      method="POST"
                      id="konfirmasi-form">
                    @csrf
                    <button type="submit"
                            id="btn-konfirmasi"
                            class="btn-orange"
                            disabled
                            style="opacity: 0.6; cursor: not-allowed;">
                        <i class="fas fa-check"></i> Konfirmasi & Lanjut Pembayaran
                    </button>
                </form>

            @elseif($pemesanan->status == 'menunggu_pembayaran')
                {{-- STEP 4: Go to payment --}}
                <a href="{{ route('customer.pembayaran', ['kode_booking' => $pemesanan->kode_booking]) }}"
                   class="btn-orange">
                    <i class="fas fa-credit-card"></i> Lanjut Pembayaran
                </a>
            @elseif($pemesanan->status == 'dibayar')
                {{-- STEP 5: View e-ticket --}}
                <a href="{{ route('customer.e_ticket', ['kode_booking' => $pemesanan->kode_booking]) }}"
                   class="btn-orange" style="background-color: #28a745;">
                    <i class="fas fa-ticket-alt"></i> Lihat E-Ticket
                </a>
            @else
                {{-- Other statuses --}}
                <button class="btn-orange" style="background-color: #6c757d;" disabled>
                    <i class="fas fa-ban"></i> Status: {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                </button>
            @endif

            <a href="{{ route('customer.riwayat') }}" class="secondary-button" style="margin-top: 10px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
        </div>

        {{-- Important Notes --}}
        <div class="info-box">
            <h3><i class="fas fa-exclamation"></i> Informasi Penting</h3>
            <ul>
                <li>Pastikan data penumpang sudah sesuai dengan identitas</li>
                <li>Kursi yang dipilih telah tersimpan dalam sistem</li>
                @if($pemesanan->status == 'menunggu_pembayaran')
                    <li>Selesaikan pembayaran dalam waktu 10 menit sebelum reservasi dibatalkan</li>
                @endif
                <li>E-ticket akan dikirim ke email pengguna setelah pembayaran berhasil</li>
                <li>Bawa E-ticket pada saat keberangkatan</li>
            </ul>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<script>
// Function untuk toggle konfirmasi button berdasarkan checkbox
function toggleKonfirmasiButton() {
    const checkbox = document.getElementById('konfirmasi-checkbox');
    const button = document.getElementById('btn-konfirmasi');

    if (checkbox && button) {
        if (checkbox.checked) {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        } else {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        }
    }
}

// Auto-hide notifications
setTimeout(() => {
    const notifications = document.querySelectorAll('.fixed');
    notifications.forEach(notification => {
        notification.style.transition = 'opacity 0.5s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    });
}, 5000);
</script>
@endsection
