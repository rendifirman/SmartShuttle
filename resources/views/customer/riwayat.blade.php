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
    .status.open {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    .status.proses {
        background-color: #fff3e0;
        color: #ef6c00;
    }
    .status.selesai {
        background-color: #e3f2fd;
        color: #1565c0;
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
        flex-wrap: wrap;
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

    @media (max-width: 768px) {
        .profile-box h3 {
            font-size: 28px;
        }
        .order-header {
            flex-direction: column;
        }
        .order-status {
            align-items: flex-start;
            margin-top: 20px;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
        }
        .button-qr-container {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        .ticket-container, .qr-modal-box {
            width: 95%;
        }
        .filter-options {
            flex-direction: column;
        }
        .cek-tiket-btn,
        .qr-container {
            width: 100%;
        }
        .qr-container {
            min-width: unset;
            height: 130px;
        }
        .qr-thumbnail {
            width: 90px;
            height: 90px;
        }
        .qr-modal-img {
            width: 200px;
            height: 200px;
        }
    }

    @media (max-width: 480px) {
        .qr-container {
            height: 120px;
        }
        .qr-thumbnail {
            width: 80px;
            height: 80px;
        }
        .qr-modal-img {
            width: 180px;
            height: 180px;
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
        <button class="filter-btn" data-filter="open">Open</button>
        <button class="filter-btn" data-filter="proses">Proses</button>
        <button class="filter-btn" data-filter="selesai">Selesai</button>
    </div>
</div>

<div class="divider"></div>

<div class="order-history">
    @forelse($riwayat as $index => $pemesanan)
        @php
            // Gunakan status dinamis dari database (baca status terbaru dari pembayaran)
            $status = $pemesanan->status_display;
            $statusLabel = $pemesanan->status_label;

            $routeString = 'Rute Tidak Diketahui';
            $kotaAsal = 'Jakarta';
            $kotaTujuan = 'Jatinangor';
            if ($pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->count() > 0) {
                $firstRoute = $pemesanan->jadwal->rutes->first();
                $lastRoute = $pemesanan->jadwal->rutes->last();
                $routeString = $firstRoute->kota_asal . ' → ' . $lastRoute->kota_tujuan;
                $kotaAsal = $firstRoute->kota_asal;
                $kotaTujuan = $lastRoute->kota_tujuan;
            }

            $formattedDate = $pemesanan->jadwal ? \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan)->locale('id')->isoFormat('dddd, D MMMM YYYY') : 'Tanggal Tidak Diketahui';
            $formattedTime = $pemesanan->jadwal ? \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i') : 'Waktu Tidak Diketahui';
            $estimatedArrival = $pemesanan->jadwal ? \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->addHours(3)->addMinutes(30)->format('H:i') : '';
            $estimationTime = $pemesanan->jadwal ? $formattedTime . ' - ' . $estimatedArrival : '09:00 - 12:30';
            $seatNumber = substr($pemesanan->kode_booking, -2);
            if (!is_numeric($seatNumber)) { $seatNumber = '01'; }
            $totalBayarFormatted = 'Rp ' . number_format($pemesanan->total_bayar ?? 0, 0, ',', '.');

            // Generate QR Code URL seperti di e_ticket
            $qrData = 'SMARTSHUTTLE:' . ($pemesanan->kode_booking ?? '') . ':CHECKIN';
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($qrData);

            // Jika ada QR path di database, gunakan itu
            if(!empty($pemesanan->qr_path) && file_exists(public_path($pemesanan->qr_path))){
                $qrUrl = asset($pemesanan->qr_path);
            } elseif(!empty($pemesanan->qr_code) && file_exists(storage_path('app/public/qr/'.$pemesanan->qr_code))){
                $qrUrl = asset('storage/qr/'.$pemesanan->qr_code);
            }

            // seat list untuk modal jika ada detail penumpang
            $seatList = [];
            $qrCodesData = [];

            if (isset($pemesanan->detailPenumpang) && $pemesanan->detailPenumpang->count()>0) {
                $seatList = $pemesanan->detailPenumpang->pluck('nomor_kursi')->filter()->values()->all();

                // Buat data QR Code untuk setiap penumpang (seperti di e_ticket)
                foreach($pemesanan->detailPenumpang as $index => $dp) {
                    $ticketCode = $pemesanan->kode_booking . '-P' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
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
                    'ticket_code' => $pemesanan->kode_booking,
                    'qr_url' => $qrUrl,
                    'seat' => $seatNumber,
                    'name' => $pemesanan->nama_pemesan ?? 'Penumpang'
                ];
            }
        @endphp

        <div class="order-item blue-bg" data-status="{{ $status }}">
            <div class="order-header">
                <div class="order-info">
                    <div class="route">{{ $routeString }}</div>
                    <div class="date">{{ $formattedDate }}</div>
                    <div class="time">{{ $formattedTime }} WIB</div>
                    <div class="passengers">{{ $pemesanan->jumlah_penumpang }} Penumpang</div>
                </div>

                <div class="order-status">
                    <div class="status {{ $status }}">
                        {{ $statusLabel }}
                    </div>

                    <div class="button-qr-container">
                        <!-- Cek Tiket: link ke halaman e_ticket penuh -->
                        <a class="cek-tiket-btn"
                           href="{{ route('customer.e_ticket', ['kode_booking' => $pemesanan->kode_booking]) }}">
                            <i class="fas fa-ticket-alt"></i> Cek Tiket
                        </a>

                        <!-- QR Code container: klik -> buka popup QR Code(s) -->
                        <div class="qr-container qr-open-btn"
                             role="button"
                             tabindex="0"
                             data-booking="{{ $pemesanan->kode_booking }}"
                             data-qrcodes='@json($qrCodesData)'
                             title="Klik untuk melihat QR Code">
                            <img src="{{ $qrUrl }}"
                                 alt="QR Code {{ $pemesanan->kode_booking }}"
                                 class="qr-thumbnail"
                                 loading="lazy">
                            <div class="qr-code">
                                {{ $pemesanan->kode_booking }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <div class="status open">-</div>
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

<!-- MODAL TIKET SEDERHANA -->
<div class="modal-overlay" id="ticketModal">
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <h1 style="font-size: 24px; font-weight: 800; margin: 0;">E-TICKET</h1>
            <div class="ticket-code" id="modalBookingCode">SMART SHUTTLE</div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <!-- Kode Booking -->
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="font-size: 11px; color: #666; letter-spacing: 1px;">KODE PEMESANAN</div>
                <div style="background: #f0f7ff; padding: 8px 15px; border-radius: 20px; font-family: monospace; font-size: 14px; color: #0066cc; display: inline-block; margin-top: 5px;" id="modalBookingCodeText">
                    TKT-20251120-001
                </div>
            </div>

            <!-- Rute -->
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="font-size: 18px; font-weight: 700; color: #00215E;" id="modalRoute">
                    Jakarta → Jatinangor
                </div>
                <div style="color: #666; font-size: 14px; margin-top: 5px;" id="modalDate">
                    Sabtu, 15 November 2025
                </div>
                <div style="color: #666; font-size: 14px;" id="modalTime">
                    09:00 - 12:30 WIB
                </div>
            </div>

            <!-- Info Penting -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #FF6B2C;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="font-size: 12px; color: #666;">Status</div>
                    <div style="font-weight: 600; color: #10b981; font-size: 13px;">
                        <i class="fas fa-check-circle"></i> <span id="modalStatus">Dibayar</span>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="font-size: 12px; color: #666;">Penumpang</div>
                    <div style="font-weight: 600; color: #00215E; font-size: 13px;" id="modalPassengerCount">
                        1 orang
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div style="font-size: 12px; color: #666;">Kursi</div>
                    <div style="font-weight: 600; color: #00215E; font-size: 13px;" id="modalSeatNumber">
                        01
                    </div>
                </div>
            </div>

            <!-- QR Code Mini -->
            <div style="text-align: center; margin: 20px 0;">
                <div style="font-size: 12px; color: #666; margin-bottom: 8px;">Scan untuk Check-in</div>
                <div style="width: 120px; height: 120px; background: white; margin: 0 auto; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid #e0e0e0;" id="qrCodeMini">
                    <i class="fas fa-qrcode" style="font-size: 40px; color: #00215E;"></i>
                </div>
                <div style="font-size: 11px; color: #999; margin-top: 8px;" id="modalCheckinCode">
                    Kode: 20-001
                </div>
            </div>

            <!-- Instruksi Singkat -->
            <div style="background: #fff7ed; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 3px solid #f97316;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <i class="fas fa-info-circle" style="color: #f97316; font-size: 14px;"></i>
                    <div style="font-weight: 600; color: #f97316; font-size: 13px;">Instruksi Check-in</div>
                </div>
                <div style="font-size: 12px; color: #666;">
                    <div>• Datang 30 menit sebelum keberangkatan</div>
                    <div>• Tunjukkan tiket ini dan identitas</div>
                    <div>• Scan QR Code di lokasi</div>
                </div>
            </div>

            <!-- Total Harga -->
            <div style="text-align: center; margin: 20px 0;">
                <div style="font-size: 12px; color: #666;">Total Pembayaran</div>
                <div style="font-weight: 700; font-size: 20px; color: #00215E;" id="modalTotalBayar">
                    Rp 150.000
                </div>
            </div>
        </div>

        <!-- Footer dengan Tombol -->
        <div class="ticket-footer" style="padding: 15px;">
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <button class="print-button" id="printTicketBtn">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button class="detail-modal-btn" id="detailTicketBtn">
                    <i class="fas fa-external-link-alt"></i> Detail Tiket
                </button>
            </div>
            <button class="close-modal-btn" id="closeTicketModalBtn">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
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

        // setup modal ticket events
        setupModalEvents();
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
                    qrCodes = [];
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
    }

    // show modal dengan satu atau multiple QR Codes (sesuai data dari e_ticket)
    function showQrModal(bookingCode, qrCodes = []) {
        const modal = document.getElementById('qrModal');
        const grid = document.getElementById('qrGrid');
        const codeSpan = document.getElementById('qrModalCode');

        if (!modal || !grid || !codeSpan) return;

        codeSpan.textContent = bookingCode || 'TKT-...';

        // clear previous content
        grid.innerHTML = '';

        // jika tidak ada data, buat default
        if (qrCodes.length === 0) {
            qrCodes = [{
                ticket_code: bookingCode,
                qr_url: `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent('SMARTSHUTTLE:' + bookingCode + ':CHECKIN')}`,
                seat: null,
                name: 'Penumpang'
            }];
        }

        // tampilkan QR Code untuk setiap penumpang
        qrCodes.forEach((qrData, index) => {
            // Create QR item wrapper
            const item = document.createElement('div');
            item.className = 'qr-modal-item';

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
            qrImg.src = qrData.qr_url;
            qrImg.alt = `QR Code ${qrData.ticket_code}`;
            qrImg.loading = 'lazy';

            // Create code text
            const codeText = document.createElement('div');
            codeText.className = 'qr-modal-text';
            codeText.textContent = qrData.ticket_code || bookingCode;

            // Append elements
            item.appendChild(qrImg);
            item.appendChild(codeText);

            // Tambahkan info kursi jika ada
            if (qrData.seat) {
                const seatInfo = document.createElement('div');
                seatInfo.style.cssText = 'font-size: 13px; color: #666; margin-top: 8px;';
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
       Ticket modal (detail) functions
       ------------------------------ */
    function setupModalEvents() {
        // ticket modal open/close wiring
        const ticketModal = document.getElementById('ticketModal');
        if (!ticketModal) return;

        // Close modal when clicking overlay
        ticketModal.addEventListener('click', function(e) {
            if (e.target === ticketModal) {
                tutupModalTiket();
            }
        });

        // Close modal button
        const closeBtn = document.getElementById('closeTicketModalBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                tutupModalTiket();
            });
        }

        // Print ticket button inside ticket modal
        const printBtn = document.getElementById('printTicketBtn');
        if (printBtn) {
            printBtn.addEventListener('click', function(e) {
                e.preventDefault();
                printTicket();
            });
        }

        // Detail ticket button — redirect to e_ticket page
        const detailBtn = document.getElementById('detailTicketBtn');
        if (detailBtn) {
            detailBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = document.getElementById('ticketModal');
                const bookingCode = modal.dataset.bookingCode;
                if (bookingCode) {
                    tutupModalTiket();
                    window.location.href = "{{ route('customer.e_ticket', ['kode_booking' => ':kode']) }}".replace(':kode', bookingCode);
                } else {
                    alert('Kode booking tidak ditemukan!');
                }
            });
        }
    }

    // Buka modal tiket sederhana
    function bukaModalTiket(kodeBooking, routeFrom, routeTo, date, time, passengers, seatNumber, estimationTime, passengerName, totalBayar) {
        const modal = document.getElementById('ticketModal');
        if (!modal) return;
        modal.dataset.bookingCode = kodeBooking || '';

        document.getElementById('modalRoute').textContent = `${routeFrom || 'Jakarta'} → ${routeTo || 'Jatinangor'}`;
        document.getElementById('modalDate').textContent = date || '';
        document.getElementById('modalTime').textContent = estimationTime || time || '';
        document.getElementById('modalBookingCodeText').textContent = kodeBooking || '';
        document.getElementById('modalBookingCode').textContent = kodeBooking || '';
        document.getElementById('modalPassengerCount').textContent = (passengers || 1) + ' orang';
        document.getElementById('modalSeatNumber').textContent = seatNumber || '';
        document.getElementById('modalTotalBayar').textContent = totalBayar || '';

        // generate QR Code
        const qrContainer = document.getElementById('qrCodeMini');
        qrContainer.innerHTML = '';
        const qrImg = document.createElement('img');
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${encodeURIComponent('SMARTSHUTTLE:' + kodeBooking + ':CHECKIN')}`;
        qrImg.alt = 'QR Code Check-in';
        qrImg.style.width = '100%';
        qrImg.style.height = '100%';
        qrImg.style.objectFit = 'cover';
        qrImg.style.borderRadius = '4px';
        qrContainer.appendChild(qrImg);

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function tutupModalTiket() {
        const modal = document.getElementById('ticketModal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Fungsi untuk mencetak tiket (modal)
    function printTicket() {
        const modal = document.getElementById('ticketModal');
        const bookingCode = modal ? modal.dataset.bookingCode : null;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Tiket - SMART SHUTTLE</title>
                    <style>
                        @media print {
                            body { margin: 0; padding: 0; background: white !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                        }
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: white; }
                        .ticket-container { width: 100%; max-width: 400px; margin: 0 auto; background: white; border: 1px solid #ddd; border-radius: 12px; overflow: hidden; }
                        .ticket-header { background: #00215E; color: white; padding: 20px; text-align: center; border-bottom: 3px dashed #f0f0f0; }
                        .ticket-body { padding: 20px; }
                    </style>
                </head>
                <body>
                    <div class="ticket-container">
                        <div class="ticket-header">
                            <h1 style="font-size: 24px; font-weight: 800; margin: 0;">E-TICKET</h1>
                            <div style="font-size: 13px; opacity: 0.9; letter-spacing: 1px; margin-top: 5px;">
                                ${bookingCode || 'TKT-UNKNOWN'}
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div style="text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #e0e0e0;">
                                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Kode Pemesanan</div>
                                <div style="font-family: monospace; font-weight: 600; font-size: 16px; color: #00215E;">
                                    ${bookingCode || 'TKT-UNKNOWN'}
                                </div>
                            </div>
                            <div style="font-size: 12px; text-align: center; color: #666; margin-top: 20px;">
                                Tiket ini dicetak pada: ${new Date().toLocaleString('id-ID')}
                            </div>
                        </div>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 500);
                        };
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
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
            });
        });
    }

    function filterOrders(status) {
        const allOrders = document.querySelectorAll('.order-item');
        allOrders.forEach(order => {
            if (status === 'all' || status === 'semua') {
                order.style.display = '';
                const nextDivider = order.nextElementSibling;
                if (nextDivider && nextDivider.classList.contains('divider')) nextDivider.style.display = '';
            } else {
                const orderStatus = order.getAttribute('data-status');
                if (orderStatus === status) {
                    order.style.display = '';
                    const nextDivider = order.nextElementSibling;
                    if (nextDivider && nextDivider.classList.contains('divider')) nextDivider.style.display = '';
                } else {
                    order.style.display = 'none';
                    const nextDivider = order.nextElementSibling;
                    if (nextDivider && nextDivider.classList.contains('divider')) nextDivider.style.display = 'none';
                }
            }
        });
    }
</script>
@endpush
