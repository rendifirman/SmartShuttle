{{-- resources/views/riwayat.blade.php --}}
@extends('layouts.app-profile')

@section('title', 'Riwayat Pesanan - SmartShuttle')

@push('styles')
<style>
    /* STYLES KHUSUS HALAMAN RIWAYAT PESANAN */
    .profile-box {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .profile-box h3 {
        font-size: 32px;
        margin-bottom: 8px;
        color: #00274D;
        font-weight: 700;
    }
    .profile-box p {
        color: #666;
        font-size: 18px;
    }
    .divider {
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #FF6B2C 50%, transparent 100%);
        margin: 30px 0;
        opacity: 0.6;
    }
    .order-history {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 0;
        overflow: hidden;
    }
    .order-item {
        padding: 25px;
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }
    .order-item:hover {
        background-color: #f8f9fa;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .order-info {
        flex: 1;
    }
    .route {
        font-size: 20px;
        font-weight: 700;
        color: #00274D;
        margin-bottom: 12px;
    }
    .date {
        color: #FF6B2C;
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .time {
        color: #666;
        font-size: 16px;
        margin-bottom: 8px;
    }
    .passengers {
        color: #666;
        font-size: 16px;
        display: flex;
        align-items: center;
    }
    .passengers::before {
        content: "👤";
        margin-right: 8px;
    }
    .order-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 15px;
    }
    .status {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status.lunas, .status.paid, .status.settlement, .status.selesai {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    .status.menunggu, .status.pending, .status.waiting, .status.proses {
        background-color: #fff3e0;
        color: #ef6c00;
    }
    .status.batal, .status.expired, .status.failed, .status.cancelled {
        background-color: #ffebee;
        color: #c62828;
    }
    .cek-tiket-btn {
        background: linear-gradient(135deg, #FF6B2C 0%, #ff7b4d 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 107, 44, 0.3);
        text-decoration: none;
    }
    .cek-tiket-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 44, 0.4);
    }

    /* QR Code Container Styling (konsisten dengan e_ticket) */
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: white;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        min-width: 140px;
        height: 140px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .qr-container:hover {
        border-color: #00215E;
        box-shadow: 0 4px 12px rgba(0, 33, 94, 0.15);
        transform: translateY(-2px);
    }
    .qr-thumbnail {
        width: 100px;
        height: 100px;
        object-fit: contain;
        background: white;
        padding: 4px;
        border-radius: 4px;
        margin-bottom: 6px;
    }
    .qr-code {
        font-size: 10px;
        text-align: center;
        color: #666;
        margin-top: 4px;
        font-family: monospace;
        letter-spacing: 0.5px;
        word-break: break-all;
        max-width: 120px;
    }

    .order-item.blue-bg {
        background: linear-gradient(135deg, #00274D 0%, #001f3d 100%);
        color: white;
    }
    .order-item.blue-bg .route {
        color: white;
    }
    .order-item.blue-bg .date {
        color: #FF6B2C;
    }
    .order-item.blue-bg .time {
        color: rgba(255,255,255,0.9);
    }
    .order-item.blue-bg .passengers {
        color: rgba(255,255,255,0.9);
    }

    /* Modal QR Code */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
        padding: 20px;
        overflow-y: auto;
    }

    /* QR Modal Box */
    .qr-modal-box {
        max-width: 400px;
        width: 100%;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        text-align: center;
    }

    .qr-modal-img {
        width: 240px;
        height: 240px;
        object-fit: contain;
        background: white;
        padding: 12px;
        border-radius: 8px;
        margin: 15px auto;
        border: 1px solid #e0e0e0;
    }

    .qr-modal-text {
        margin-top: 8px;
        font-family: monospace;
        font-size: 13px;
        color: #00215E;
        word-break: break-all;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border: 1px dashed #ddd;
    }

    .qr-modal-item {
        padding: 15px;
        margin-bottom: 15px;
        border: 1px dashed #e0e0e0;
        border-radius: 8px;
        background: #fafafa;
    }

    /* Modal Tiket Sederhana */
    .ticket-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .ticket-header {
        background: #00215E;
        color: white;
        padding: 20px;
        text-align: center;
        border-bottom: 3px dashed #f0f0f0;
    }
    .ticket-body {
        padding: 20px;
    }
    .ticket-footer {
        padding: 15px;
        border-top: 1px solid #eee;
    }
    .print-button, .detail-modal-btn {
        padding: 10px 15px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 1;
    }
    .print-button {
        background: #00215E;
        color: white;
    }
    .detail-modal-btn {
        background: #10b981;
        color: white;
    }
    .close-modal-btn {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* footer */
    .footer {
        text-align: center;
        padding: 25px;
        color: #999;
        font-size: 12px;
        border-top: 1px solid #eee;
        margin-top: 30px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .filter-section {
        margin-bottom: 25px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .filter-title {
        font-size: 18px;
        color: #00274D;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .filter-options {
        display: flex;
        gap: 15px;
        flex-wrap: nowrap;
    }
    .filter-btn {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 20px;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        white-space: nowrap;
    }
    .filter-btn:hover {
        background: #e9ecef;
    }
    .filter-btn.active {
        background: #00274D;
        color: white;
        border-color: #00274D;
    }
    .button-qr-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* DESKTOP STYLES */
    @media (min-width: 769px) {
        .filter-options {
            flex-wrap: wrap;
        }
    }

    /* MOBILE OPTIMIZATION */
    @media (max-width: 768px) {
        .profile-box {
            padding: 20px;
        }
        
        .profile-box h3 {
            font-size: 24px;
        }
        
        .profile-box p {
            font-size: 16px;
        }
        
        .filter-section {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .filter-title {
            font-size: 16px;
            margin-bottom: 12px;
        }
        
        .filter-options {
            flex-direction: row;
            overflow-x: auto;
            padding-bottom: 8px;
            gap: 10px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            flex-wrap: nowrap;
        }
        
        .filter-options::-webkit-scrollbar {
            display: none;
        }
        
        .filter-btn {
            padding: 8px 14px;
            font-size: 13px;
            white-space: nowrap;
            flex-shrink: 0;
            border-radius: 18px;
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .order-status {
            align-items: flex-start;
            margin-top: 20px;
            flex-direction: column;
            width: 100%;
            gap: 15px;
        }
        
        .button-qr-container {
            flex-direction: column;
            gap: 12px;
            width: 100%;
            align-items: stretch;
        }
        
        .cek-tiket-btn,
        .qr-container {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
        
        .qr-container {
            min-width: unset;
            height: 130px;
            padding: 10px;
        }
        
        .qr-thumbnail {
            width: 90px;
            height: 90px;
        }
        
        .route {
            font-size: 18px;
        }
        
        .date, .time, .passengers {
            font-size: 14px;
        }
        
        .order-item {
            padding: 20px;
        }
        
        .status {
            padding: 7px 15px;
            font-size: 13px;
        }
        
        .ticket-container, .qr-modal-box {
            width: 95%;
        }
    }

    @media (max-width: 480px) {
        .profile-box {
            padding: 18px;
        }
        
        .profile-box h3 {
            font-size: 22px;
        }
        
        .filter-btn {
            padding: 7px 12px;
            font-size: 12px;
        }
        
        .order-item {
            padding: 18px;
        }
        
        .route {
            font-size: 17px;
            line-height: 1.3;
        }
        
        .qr-container {
            height: 120px;
        }
        
        .qr-thumbnail {
            width: 85px;
            height: 85px;
        }
        
        .qr-code {
            font-size: 11px;
        }
        
        .status {
            padding: 6px 14px;
            font-size: 12px;
        }
        
        .cek-tiket-btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .qr-modal-img {
            width: 200px;
            height: 200px;
        }
    }

    /* Untuk tampilan yang sangat kecil (iPhone SE dll) */
    @media (max-width: 375px) {
        .filter-btn {
            padding: 6px 10px;
            font-size: 11px;
        }
        
        .order-item {
            padding: 16px;
        }
        
        .route {
            font-size: 16px;
        }
        
        .qr-container {
            height: 110px;
        }
        
        .qr-thumbnail {
            width: 80px;
            height: 80px;
        }
        
        .date, .time, .passengers {
            font-size: 13px;
        }
        
        .qr-modal-img {
            width: 180px;
            height: 180px;
        }
    }

    /* Untuk Galaxy Fold & device sangat kecil */
    @media (max-width: 320px) {
        .profile-box h3 {
            font-size: 20px;
        }
        
        .profile-box p {
            font-size: 14px;
        }
        
        .filter-btn {
            padding: 5px 8px;
            font-size: 10px;
        }
        
        .route {
            font-size: 15px;
        }
        
        .qr-container {
            height: 100px;
        }
        
        .qr-thumbnail {
            width: 70px;
            height: 70px;
        }
        
        .cek-tiket-btn {
            padding: 8px 14px;
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Message -->
<div class="profile-box">
    <h3>Hello, {{ $user['name'] ?? 'Pengguna' }}</h3>
    <p>Lihat riwayat perjalanan Anda</p>
</div>

<!-- Filter Options -->
<div class="filter-section">
    <div class="filter-title">Filter Status:</div>
    <div class="filter-options">
        <button class="filter-btn active" data-filter="all">Semua</button>
        <button class="filter-btn" data-filter="lunas">Lunas</button>
        <button class="filter-btn" data-filter="menunggu">Menunggu</button>
        <button class="filter-btn" data-filter="batal">Batal</button>
    </div>
</div>

<div class="divider"></div>

<div class="order-history">
    @forelse($riwayat as $index => $item)
       @if($item instanceof \App\Models\SmartRentTransaction)
    {{-- SmartRent Rental Transaction Display --}}
    @php
        $isSmartRent = true;
        
        // Gunakan accessor dari model
        $filterStatus = $item->filter_status; // 'lunas', 'menunggu', atau 'batal'
        $statusLabel = $item->payment_status_label;
        
        // CEK LANGSUNG apakah sudah lunas
        $isPaid = $item->is_paid;
        
        $serviceTypeLabel = $item->service_type_label;
        $totalBayarFormatted = $item->formatted_total_price;
        $formattedStartDate = $item->start_date ? \Carbon\Carbon::parse($item->start_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '-';
        $formattedEndDate = $item->end_date ? \Carbon\Carbon::parse($item->end_date)->locale('id')->isoFormat('D MMMM YYYY') : '-';
        $duration = $item->duration;
        $vehicleInfo = $item->vehicle_name . ' - ' . $serviceTypeLabel;
        
        // Cek apakah bisa show e-ticket menggunakan method dari model
        $canShowETicket = $item->canShowETicket();
        
        // Debug (opsional, hapus setelah fix)
        $debugInfo = 'Status DB: ' . $item->payment_status . ' | Filter: ' . $filterStatus . ' | Is Paid: ' . ($isPaid ? 'Ya' : 'Tidak');
    @endphp

    <div class="order-item blue-bg" data-status="{{ $filterStatus }}">
        <div class="order-header">
            <div class="order-info">
                <div class="route">🚗 {{ $vehicleInfo }}</div>
                <div class="date">{{ $formattedStartDate }}</div>
                <div class="time">{{ $formattedEndDate }} ({{ $duration }} hari)</div>
                <div class="passengers">📍 {{ $item->pickup_location ?? 'Lokasi Tidak Diketahui' }}</div>
                {{-- Debug info (hapus setelah fix) --}}
                {{-- <div style="font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 5px;">{{ $debugInfo }}</div> --}}
            </div>

            <div class="order-status">
                <div class="status {{ $filterStatus }}">
                    {{ $statusLabel }}
                </div>

                <div class="button-qr-container">
                    @if($canShowETicket)
                        <!-- E-Ticket Button (hanya untuk yang sudah lunas) -->
                        <a class="cek-tiket-btn" href="{{ route('smartrent.e-ticket', $item->order_number) }}" title="Lihat E-Ticket">
                            <i class="fas fa-ticket-alt"></i>
                            E-Ticket
                        </a>
                    @else
                        <!-- Detail Button (untuk yang belum lunas - tetap bisa lihat detail) -->
                        <a class="cek-tiket-btn" href="{{ route('smartrent.e-ticket', $item->order_number) }}" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    @endif

                    <!-- Show QR if paid and has qr_path -->
                    @if($canShowETicket && $item->qr_path)
                    <div class="qr-container qr-open-btn"
                         role="button"
                         tabindex="0"
                         data-booking="{{ $item->order_number }}"
                         data-qr="{{ asset($item->qr_path) }}"
                         title="Klik untuk melihat QR Code">
                        <img src="{{ asset($item->qr_path) }}"
                             alt="QR Code {{ $item->order_number }}"
                             class="qr-thumbnail"
                             loading="lazy">
                        <div class="qr-code">
                            {{ substr($item->order_number, -8) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="order-details">
            <div class="details-section" style="margin-top: 15px;">
                <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 14px;">
                    <div style="flex: 1; min-width: 200px;">
                        <span style="color: rgba(255,255,255,0.7);">Pemesan:</span>
                        <span style="color: white; margin-left: 8px;">{{ $item->customer_name ?? 'N/A' }}</span>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <span style="color: rgba(255,255,255,0.7);">No. Pesanan:</span>
                        <span style="color: white; margin-left: 8px;">{{ $item->order_number ?? 'N/A' }}</span>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <span style="color: rgba(255,255,255,0.7);">Total:</span>
                        <span style="color: #FF6B2C; font-weight: 700; margin-left: 8px;">{{ $totalBayarFormatted }}</span>
                    </div>
                    @if($item->payment_method)
                    <div style="flex: 1; min-width: 200px;">
                        <span style="color: rgba(255,255,255,0.7);">Metode:</span>
                        <span style="color: white; margin-left: 8px;">{{ ucfirst(str_replace('_', ' ', $item->payment_method)) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
            {{-- Regular Pemesanan (Shuttle) Display --}}
            @php
                // Perbaiki penentuan status berdasarkan pembayaran
                $paymentStatus = strtolower($item->status_pembayaran ?? 'pending');
                $orderStatus = strtolower($item->status ?? 'pending');
                
                // Tentukan status untuk filter
                if (in_array($paymentStatus, ['paid', 'settlement', 'success', 'lunas'])) {
                    $filterStatus = 'lunas';
                    $statusLabel = 'Lunas';
                } elseif (in_array($paymentStatus, ['pending', 'waiting', 'menunggu'])) {
                    $filterStatus = 'menunggu';
                    $statusLabel = 'Menunggu Pembayaran';
                } elseif (in_array($paymentStatus, ['expired', 'failed', 'cancelled', 'batal'])) {
                    $filterStatus = 'batal';
                    $statusLabel = 'Dibatalkan';
                } else {
                    $filterStatus = 'menunggu';
                    $statusLabel = ucfirst($paymentStatus);
                }

                $routeString = 'Rute Tidak Diketahui';
                $kotaAsal = 'Jakarta';
                $kotaTujuan = 'Jatinangor';
                if ($item->jadwal && $item->jadwal->rutes && $item->jadwal->rutes->count() > 0) {
                    $firstRoute = $item->jadwal->rutes->first();
                    $lastRoute = $item->jadwal->rutes->last();
                    $routeString = $firstRoute->kota_asal . ' → ' . $lastRoute->kota_tujuan;
                    $kotaAsal = $firstRoute->kota_asal;
                    $kotaTujuan = $lastRoute->kota_tujuan;
                }

                $formattedDate = $item->jadwal ? \Carbon\Carbon::parse($item->jadwal->tanggal_keberangkatan)->locale('id')->isoFormat('dddd, D MMMM YYYY') : 'Tanggal Tidak Diketahui';
                $formattedTime = $item->jadwal ? \Carbon\Carbon::parse($item->jadwal->waktu_keberangkatan)->format('H:i') : 'Waktu Tidak Diketahui';
                $estimatedArrival = $item->jadwal ? \Carbon\Carbon::parse($item->jadwal->waktu_keberangkatan)->addHours(3)->addMinutes(30)->format('H:i') : '';
                $estimationTime = $item->jadwal ? $formattedTime . ' - ' . $estimatedArrival . ' WIB' : '09:00 - 12:30 WIB';
                $totalBayarFormatted = 'Rp ' . number_format($item->total_bayar ?? 0, 0, ',', '.');

                // Generate QR Code URL
                $qrData = 'SMARTSHUTTLE:' . ($item->kode_booking ?? '') . ':CHECKIN';
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($qrData);

                // Jika ada QR path di database, gunakan itu
                if(!empty($item->qr_path) && file_exists(public_path($item->qr_path))){
                    $qrUrl = asset($item->qr_path);
                } elseif(!empty($item->qr_code) && file_exists(storage_path('app/public/qr/'.$item->qr_code))){
                    $qrUrl = asset('storage/qr/'.$item->qr_code);
                }

                // seat list untuk modal jika ada detail penumpang
                $qrCodesData = [];

                if (isset($item->detailPenumpang) && $item->detailPenumpang->count()>0) {
                    foreach($item->detailPenumpang as $index => $dp) {
                        $ticketCode = $item->kode_booking . '-P' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                        $qrData = 'SMARTSHUTTLE:' . $ticketCode . ':CHECKIN';
                        $individualQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);

                        $qrCodesData[] = [
                            'ticket_code' => $ticketCode,
                            'qr_url' => $individualQrUrl,
                            'seat' => $dp->nomor_kursi ?? null,
                            'name' => $dp->nama_lengkap ?? $dp->nama ?? 'Penumpang ' . ($index + 1)
                        ];
                    }
                } else {
                    // Jika tidak ada detail penumpang, buat satu QR Code saja
                    $qrCodesData[] = [
                        'ticket_code' => $item->kode_booking,
                        'qr_url' => $qrUrl,
                        'seat' => substr($item->kode_booking, -2),
                        'name' => $item->nama_pemesan ?? 'Penumpang'
                    ];
                }
            @endphp

            <div class="order-item blue-bg" data-status="{{ $filterStatus }}">
                <div class="order-header">
                    <div class="order-info">
                        <div class="route">{{ $routeString }}</div>
                        <div class="date">{{ $formattedDate }}</div>
                        <div class="time">{{ $estimationTime }}</div>
                        <div class="passengers">{{ $item->jumlah_penumpang ?? 1 }} Penumpang</div>
                    </div>

                    <div class="order-status">
                        <div class="status {{ $filterStatus }}">
                            {{ $statusLabel }}
                        </div>

                        <div class="button-qr-container">
                            <!-- Cek Tiket: link ke halaman e_ticket penuh (bisa diakses semua status) -->
                            <a class="cek-tiket-btn"
                               href="{{ route('customer.e_ticket', ['kode_booking' => $item->kode_booking]) }}">
                                <i class="fas fa-ticket-alt"></i> 
                                {{ $filterStatus == 'lunas' ? 'E-Ticket' : 'Detail' }}
                            </a>

                            <!-- QR Code container: hanya tampil jika lunas -->
                            @if($filterStatus == 'lunas')
                            <div class="qr-container qr-open-btn"
                                 role="button"
                                 tabindex="0"
                                 data-booking="{{ $item->kode_booking }}"
                                 data-qrcodes='@json($qrCodesData)'
                                 title="Klik untuk melihat QR Code">
                                <img src="{{ $qrUrl }}"
                                     alt="QR Code {{ $item->kode_booking }}"
                                     class="qr-thumbnail"
                                     loading="lazy">
                                <div class="qr-code">
                                    {{ $item->kode_booking }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!$loop->last)
            <div class="divider"></div>
        @endif
    @empty
        <div class="order-item blue-bg">
            <div class="order-header">
                <div class="order-info">
                    <div class="route">Belum ada riwayat pemesanan</div>
                    <div class="date">Silakan lakukan pemesanan terlebih dahulu</div>
                </div>
                <div class="order-status">
                    <div class="status">-</div>
                    <div>
                        <a href="{{ route('customer.beranda') }}" class="cek-tiket-btn">
                            <i class="fas fa-shopping-cart"></i>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="footer">
    <p>&copy; 2025 SMART SHUTTLE. Semua hak dilindungi.</p>
</div>

<!-- MODAL QR CODE -->
<div class="modal-overlay" id="qrModal" aria-hidden="true">
    <div class="qr-modal-box" role="dialog" aria-modal="true" aria-labelledby="qrModalTitle">
        <h2 id="qrModalTitle" style="margin:0; font-size:18px; color:#00215E;">QR Code Check-in</h2>

        <div style="margin-top:8px; font-size:13px; color:#666;" id="qrModalBookingText">
            Kode Booking: <span id="qrModalCode">TKT-...</span>
        </div>

        <div class="qr-modal-grid" id="qrGrid" style="margin-top: 15px;">
            <!-- QR Code akan di-generate di sini -->
        </div>

        <button class="close-qr-btn" id="closeQrBtn" style="margin-top: 15px; padding: 10px 20px; background: #00215E; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>
</div>

@endsection

@push('scripts')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Riwayat: DOM loaded');

        // setup QR code buttons
        setupQrButtons();

        // setup filter buttons
        setupFilterButtons();
        
        // optimize mobile filter
        optimizeMobileFilter();
    });

    // Set up click handler untuk membuka modal QR Code
    function setupQrButtons() {
        document.querySelectorAll('.qr-open-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const booking = this.getAttribute('data-booking');
                let qrCodes = [];
                
                try {
                    const raw = this.getAttribute('data-qrcodes');
                    if (raw) qrCodes = JSON.parse(raw);
                } catch (err) {
                    console.log('Error parsing QR codes:', err);
                    // Jika gagal parse, buat default dari data-qr
                    const qrUrl = this.getAttribute('data-qr');
                    if (qrUrl) {
                        qrCodes = [{
                            ticket_code: booking,
                            qr_url: qrUrl,
                            seat: null,
                            name: 'Penumpang'
                        }];
                    }
                }

                if (qrCodes.length === 0 && booking) {
                    // Default QR code
                    qrCodes = [{
                        ticket_code: booking,
                        qr_url: `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent('SMARTSHUTTLE:' + booking + ':CHECKIN')}`,
                        seat: null,
                        name: 'Penumpang'
                    }];
                }

                showQrModal(booking, qrCodes);
            });

            // keyboard accessibility (enter/space)
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // close button
        const closeBtn = document.getElementById('closeQrBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeQrModal();
            });
        }

        // click outside modal to close
        const qrModal = document.getElementById('qrModal');
        if (qrModal) {
            qrModal.addEventListener('click', function(e) {
                if (e.target === qrModal) closeQrModal();
            });
        }
        
        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQrModal();
            }
        });
    }

    // show modal dengan satu atau multiple QR Codes
    function showQrModal(bookingCode, qrCodes = []) {
        const modal = document.getElementById('qrModal');
        const grid = document.getElementById('qrGrid');
        const codeSpan = document.getElementById('qrModalCode');

        if (!modal || !grid || !codeSpan) return;

        codeSpan.textContent = bookingCode || 'TKT-...';

        // clear previous content
        grid.innerHTML = '';

        // tampilkan QR Code untuk setiap penumpang
        qrCodes.forEach((qrData, index) => {
            // Create QR item wrapper
            const item = document.createElement('div');
            item.className = 'qr-modal-item';
            item.style.cssText = 'padding: 15px; margin-bottom: 15px; border: 1px dashed #e0e0e0; border-radius: 8px; background: #fafafa;';

            // Create title jika ada multiple penumpang
            if (qrCodes.length > 1) {
                const title = document.createElement('div');
                title.style.cssText = 'font-size: 14px; font-weight: 600; color: #00215E; margin-bottom: 8px;';
                title.textContent = `Penumpang ${index + 1}: ${qrData.name || ''}`;
                item.appendChild(title);
            }

            // Create QR image
            const qrImg = document.createElement('img');
            qrImg.className = 'qr-modal-img';
            qrImg.style.cssText = 'width: 200px; height: 200px; object-fit: contain; background: white; padding: 10px; border-radius: 8px; margin: 10px auto; border: 1px solid #e0e0e0; display: block;';
            qrImg.src = qrData.qr_url;
            qrImg.alt = `QR Code ${qrData.ticket_code}`;
            qrImg.loading = 'lazy';

            // Create code text
            const codeText = document.createElement('div');
            codeText.className = 'qr-modal-text';
            codeText.style.cssText = 'margin-top: 8px; font-family: monospace; font-size: 13px; color: #00215E; word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 6px; border: 1px dashed #ddd;';
            codeText.textContent = qrData.ticket_code || bookingCode;

            // Append elements
            item.appendChild(qrImg);
            item.appendChild(codeText);

            // Tambahkan info kursi jika ada
            if (qrData.seat) {
                const seatInfo = document.createElement('div');
                seatInfo.style.cssText = 'font-size: 13px; color: #666; margin-top: 8px; text-align: center;';
                seatInfo.innerHTML = `<strong>Kursi:</strong> ${qrData.seat}`;
                item.appendChild(seatInfo);
            }

            grid.appendChild(item);
        });

        // Show modal
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        if (!modal) return;
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
        // Clear content
        const grid = document.getElementById('qrGrid');
        if (grid) grid.innerHTML = '';
    }

    /* ------------------------------
       Filter functions
       ------------------------------ */
    function setupFilterButtons() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterOrders(filter);
                
                // Scroll filter ke posisi aktif di mobile
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        this.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }, 100);
                }
            });
        });
        
        // Set initial filter
        filterOrders('all');
    }

    function filterOrders(status) {
        const allOrders = document.querySelectorAll('.order-item');
        const allDividers = document.querySelectorAll('.divider');
        
        let visibleCount = 0;
        
        allOrders.forEach(order => {
            if (status === 'all' || status === 'semua') {
                order.style.display = '';
                visibleCount++;
            } else {
                const orderStatus = order.getAttribute('data-status');
                if (orderStatus === status) {
                    order.style.display = '';
                    visibleCount++;
                } else {
                    order.style.display = 'none';
                }
            }
        });
        
        // Hide/show dividers based on visible orders
        allDividers.forEach(divider => {
            divider.style.display = 'none';
        });
        
        // Show message if no orders
        if (visibleCount === 0 && allOrders.length > 0) {
            const firstOrder = allOrders[0];
            if (firstOrder && firstOrder.querySelector('.route').textContent === 'Belum ada riwayat pemesanan') {
                // Already showing empty message
            } else {
                // Could show a "no results" message
                console.log('No orders found for filter:', status);
            }
        }
    }

    /* ------------------------------
       Mobile optimization for filter
       ------------------------------ */
    function optimizeMobileFilter() {
        if (window.innerWidth <= 768) {
            const filterOptions = document.querySelector('.filter-options');
            if (filterOptions) {
                // Scroll filter ke posisi aktif
                const activeBtn = filterOptions.querySelector('.filter-btn.active');
                if (activeBtn) {
                    setTimeout(() => {
                        activeBtn.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }, 100);
                }
            }
        }
    }

    // Panggil saat halaman dimuat dan di-resize
    window.addEventListener('resize', optimizeMobileFilter);
</script>
@endpush