{{-- resources/views/customer/e_ticket.blade.php --}}
@extends('layouts.app')

@section('title', 'E-Ticket - Smart Shuttle')

@php
    $bgPath = 'images/backgroundpeta.png';
@endphp

@push('styles')
<style>
    :root{
        --primary:#00215E;
        --accent:#e04500;
        --muted:#666;
        --card-bg:#ffffff;
        --shadow: 0 4px 16px rgba(0,0,0,0.18);
        --radius:20px;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial;
        background: url('{{ asset($bgPath) }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }

    body::before{
        content:'';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    header, nav, .navbar, .site-header, .main-nav, .topbar, .app-header,
    footer, .site-footer, .app-footer, .footer {
        display: none !important;
    }

    .back-btn{
        display:inline-flex;
        align-items:center;
        gap:8px;
        margin: 12px auto;
        max-width:1200px;
        padding:8px 12px;
        border-radius:10px;
        background:#ffffff;
        color:#00215E;
        font-weight:700;
        text-decoration:none;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        border:1px solid rgba(0,0,0,0.04);
        position: relative;
        z-index: 20;
    }
    .back-btn i{ font-size:14px; }

    /* Container untuk memusatkan tiket */
    .ticket-center-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Grid wrapper - POSISI DI TENGAH */
    .tickets-wrapper{
        display: flex;
        justify-content: center;   /* PUSAT HORIZONTAL */
        align-items: flex-start;   /* posisi atas tapi tetap center */
        gap: 18px;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto 40px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    /* ticket card - LEBIH BESAR KE SAMPING */
    .ticket-card{
        width: 100%;
        max-width: 460px; /* Lebih besar dari 450px */
        background: var(--card-bg);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: var(--shadow);
        position: relative;
        z-index: 10;
        box-sizing: border-box;
        min-height: 480px;
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
        /* Tambahan untuk print */
        page-break-inside: avoid;
        break-inside: avoid;
    }

    /* PRINT SPECIFIC STYLES - QR CODE BESAR */
    /* GANTI BAGIAN @media print (baris 93-243) dengan kode ini */

@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        page-break-before: avoid !important;
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
    }

    body {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 210mm !important;
        height: auto !important;
        min-height: 297mm !important;
    }

    body::before {
        display: none !important;
    }

    .back-btn {
        display: none !important;
    }

    /* Container tiket dalam 1 halaman */
    .ticket-center-container {
        width: 100% !important;
        height: auto !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        padding: 0 !important;
    }

    .tickets-wrapper {
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        align-items: center !important;
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 8mm !important;
        gap: 4mm !important;
    }

    /* Tiket disesuaikan agar muat dalam 1 halaman - LEBIH PANJANG */
    .ticket-card {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
        box-shadow: none !important;
        border-radius: 8px !important;
        border: 2px solid var(--primary) !important;
        margin: 0 !important;
        padding: 8mm !important;
        width: 100% !important;
        max-width: 180mm !important;
        height: auto !important;
        min-height: auto !important;
        max-height: none !important;
        font-size: 11px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        box-sizing: border-box !important;
    }

    /* Untuk multiple tickets */
    .ticket-card:not(:last-child) {
        margin-bottom: 3mm !important;
    }

    /* Hilangkan tombol aksi saat print */
    .actions {
        display: none !important;
    }

    /* QR CODE - ukuran lebih besar */
    .qr-wrap {
        margin: 4mm auto !important;
        text-align: center !important;
    }

    .qr-wrap img {
        width: 110px !important;
        height: 110px !important;
        padding: 8px !important;
        border: 2px solid #eee !important;
        border-radius: 8px !important;
        background: white !important;
        display: inline-block !important;
        object-fit: contain !important;
    }

    .qr-wrap div {
        font-size: 10px !important;
        margin-top: 3mm !important;
    }

    /* Logo & Title - LEBIH BESAR */
    .ticket-logo {
        width: 70px !important;
        margin: 0 auto 3mm !important;
        display: block !important;
    }

    h1.ticket-title {
        font-size: 20px !important;
        color: var(--primary) !important;
        margin: 0 0 2mm 0 !important;
        font-weight: 900 !important;
    }

    .ticket-id {
        font-size: 14px !important;
        margin-top: 2mm !important;
        color: var(--accent) !important;
        font-weight: 700 !important;
    }

    .kode-pemesanan {
        font-size: 13px !important;
        margin-top: 2mm !important;
        color: var(--accent) !important;
        font-weight: 700 !important;
    }

    /* Route Info - LEBIH BESAR */
    .route-city {
        font-size: 16px !important;
        font-weight: 900 !important;
        color: var(--primary) !important;
    }

    .subinfo {
        font-size: 13px !important;
        margin-top: 2mm !important;
        color: var(--muted) !important;
        font-weight: 600 !important;
    }

    /* Info Columns - LEBIH BESAR */
    .info-columns {
        margin-top: 3mm !important;
        gap: 3mm !important;
    }

    .label {
        font-size: 11px !important;
        color: #666 !important;
        margin-top: 2mm !important;
    }

    .value {
        font-size: 13px !important;
        margin-top: 2mm !important;
        font-weight: 800 !important;
        color: #102a43 !important;
    }

    /* Section Title - LEBIH BESAR */
    .section-title {
        font-size: 14px !important;
        margin-top: 3mm !important;
        margin-bottom: 2mm !important;
        font-weight: 900 !important;
        color: var(--primary) !important;
    }

    /* Data Row - LEBIH BESAR & SPASI */
    .data-row {
        margin-top: 2mm !important;
        margin-bottom: 1mm !important;
    }

    .data-row .left,
    .data-row .right {
        font-size: 11px !important;
        line-height: 1.5 !important;
    }

    /* Divider - LEBIH TEBAL */
    .dashed-divider {
        border-top: 1.5px dashed #ccc !important;
        margin: 3mm 0 !important;
    }

    /* Instructions - LEBIH BESAR */
    .instructions {
        margin: 2mm 0 0 5mm !important;
        padding: 0 !important;
    }

    .instructions li {
        font-size: 11px !important;
        margin-bottom: 2mm !important;
        color: #444 !important;
        line-height: 1.4 !important;
    }

    /* Total Box - LEBIH BESAR */
    .total-box {
        margin-top: 3mm !important;
    }

    .total-box div {
        font-size: 11px !important;
        margin-top: 2mm !important;
    }

    .total-box div:first-child {
        color: #666 !important;
    }

    .total-box div:nth-child(2) {
        font-size: 18px !important;
        font-weight: 900 !important;
        color: var(--primary) !important;
        margin-top: 2mm !important;
    }

    .total-box div:last-child {
        font-size: 10px !important;
        margin-top: 2mm !important;
    }

    /* Route Row */
    .route-row {
        margin-top: 3mm !important;
        margin-bottom: 2mm !important;
    }

    /* Page Settings - CRITICAL */
    @page {
        margin: 10mm;
        size: A4 portrait;
    }

    /* Pastikan tidak ada overflow */
    html, body {
        overflow: hidden !important;
    }

    /* Untuk memastikan semua tiket fit dalam 1 halaman */
    @supports (display: flex) {
        .tickets-wrapper {
            max-height: 277mm !important;
            overflow: visible !important;
        }
    }
}

        @page :first {
            margin: 10mm;
        }


    .ticket-logo{ width: 82px; display:block; margin: 0 auto 6px; }
    h1.ticket-title{ margin:0; text-align:center; font-size:20px; color:var(--primary); font-weight:700; letter-spacing:0.3px; }
    .ticket-id{ text-align:center; font-size:15px; font-weight:700; color:var(--accent); margin-top:6px; }
    .dashed-divider{ border-top:1px dashed #ccc; margin:12px 0; }
    .qr-wrap{ text-align:center; margin:10px 0; }
    .qr-wrap img{ width:180px; height:180px; object-fit:cover; background:white; padding:10px; border-radius:10px; display:inline-block; border:2px solid #eee; }
    .kode-pemesanan{ text-align:center; margin-top:8px; font-weight:700; color:var(--accent); font-size:13px; }
    .route-row{ display:flex; justify-content:space-between; align-items:center; margin-top:12px; gap:12px; }
    .route-city{ width:48%; font-weight:700; font-size:16px; color:var(--primary); text-transform:uppercase; }
    .subinfo{ color:var(--muted); font-size:12px; margin-top:6px; text-align:center; }
    .info-columns{ display:flex; justify-content:space-between; gap:12px; margin-top:10px; }
    .info-col{ width:48%; }
    .label{ font-size:13px; color:#777; }
    .value{ margin-top:6px; font-size:14px; font-weight:700; color:#102a43; }
    .section-title{ margin-top:12px; font-size:15px; font-weight:700; color:var(--primary); }

    /* DATA ROW - LEBIH LEBAR AGAR EMAIL TIDAK MENUMPUK */
    .data-row{
        display: flex;
        margin-top: 8px;
        width: 100%;
    }
    .data-row .left{
        width: 25%; /* Lebih sempit */
        color:#444;
        font-weight:600;
        font-size: 14px;
    }
    .data-row .right{
        width: 75%; /* Lebih lebar */
        color:#222;
        font-weight:700;
        font-size: 14px;
        word-break: break-all; /* Email panjang akan patah di huruf */
    }

    ul.instructions{ margin:6px 0 0 18px; color:#444; font-size:13px; padding:0; }
    ul.instructions li{ margin-bottom:6px; }
    .total-box{ text-align:center; margin-top:10px; }

    .actions{ display:flex; gap:10px; justify-content:center; margin-top:auto; }
    .btn{ padding:8px 12px; border-radius:8px; border:0; cursor:pointer; font-weight:700; font-size:13px; }
    .btn-print{ background:var(--primary); color:#fff; }
    .btn-download{ background:#10b981; color:#fff; }
    .btn-share{ background:#8b5cf6; color:#fff; }

    @media (max-width:1200px){
        .tickets-wrapper{
            grid-template-columns: repeat(2, 1fr);
            max-width: 1000px;
        }
        .ticket-card{
            max-width: 480px;
        }
        .qr-wrap img{ width:170px; height:170px; }
    }

    @media (max-width:768px){
        .tickets-wrapper{
            grid-template-columns: 1fr;
            padding: 12px;
            max-width: 500px;
        }
        .ticket-card{
            max-width: 100%;
        }
        .qr-wrap img{ width:160px; height:160px; }
        .route-city{ font-size:14px; }
    }
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    // Fungsi helper untuk format tanggal Indonesia
    function formatTanggalIndonesia($dateString, $includeTime = false) {
        if (!$dateString) return '-';

        try {
            $date = Carbon::parse($dateString);

            // Array nama hari Indonesia
            $hari = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            // Array nama bulan Indonesia
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

            $hariInggris = $date->format('l');
            $bulanInggris = $date->format('F');

            $hariIndonesia = $hari[$hariInggris] ?? $hariInggris;
            $bulanIndonesia = $bulan[$bulanInggris] ?? $bulanInggris;

            $formatTanggal = $hariIndonesia . ', ' . $date->format('j') . ' ' . $bulanIndonesia . ' ' . $date->format('Y');

            if ($includeTime) {
                $formatTanggal .= ' ' . $date->format('H:i');
            }

            return $formatTanggal;
        } catch (\Exception $e) {
            return '-';
        }
    }

    if (!isset($pemesanan)) {
        echo '<div style="max-width:760px;margin:40px auto;background:#fff;padding:20px;border-radius:12px;text-align:center;">E-Ticket tidak ditemukan.</div>';
        return;
    }

    $kode = $pemesanan->kode_booking ?? ('TKT-'.date('Ymd').'-'.str_pad($pemesanan->id ?? 0,3,'0',STR_PAD_LEFT));

    $origin = 'Jakarta';
    $destination = 'Tujuan';
    if(!empty($pemesanan->jadwal) && !empty($pemesanan->jadwal->rutes) && $pemesanan->jadwal->rutes->count()>0){
        $first = $pemesanan->jadwal->rutes->first();
        $last  = $pemesanan->jadwal->rutes->last();
        $origin = $first->kota_asal ?? $origin;
        $destination = $last->kota_tujuan ?? $destination;
    } else {
        if(method_exists($pemesanan,'outletAsal') && $pemesanan->outletAsal) {
            $origin = $pemesanan->outletAsal->kota ?? ($pemesanan->outletAsal->nama_outlet ?? $origin);
        }
        if(method_exists($pemesanan,'outletTujuan') && $pemesanan->outletTujuan) {
            $destination = $pemesanan->outletTujuan->kota ?? ($pemesanan->outletTujuan->nama_outlet ?? $destination);
        }
    }

    // Format tanggal dan waktu menjadi bahasa Indonesia
    $tanggal_display = '-';
    $waktu_display = '-';

    if (isset($pemesanan->jadwal->tanggal_keberangkatan)) {
        $tanggal_display = formatTanggalIndonesia($pemesanan->jadwal->tanggal_keberangkatan, false);
    } elseif (isset($pemesanan->tanggal_formatted)) {
        $tanggal_display = $pemesanan->tanggal_formatted;
    }

    if (isset($pemesanan->jadwal->waktu_keberangkatan)) {
        $waktu_display = Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i');
    } elseif (isset($pemesanan->waktu_formatted)) {
        $waktu_display = str_replace(' WIB', '', $pemesanan->waktu_formatted);
    }

    // Gabungkan tanggal dan waktu untuk display di subinfo
    $tanggal_waktu_display = $tanggal_display;
    if ($waktu_display !== '-') {
        $tanggal_waktu_display .= ' ' . $waktu_display;
    }

    $estimasi = '-';
    if(!empty($pemesanan->jadwal->waktu_keberangkatan)){
        try {
            $start = Carbon::parse($pemesanan->jadwal->waktu_keberangkatan);
            $arrive = $start->copy()->addHours(3)->addMinutes(30);
            $estimasi = $start->format('H:i') . ' - ' . $arrive->format('H:i');
        } catch(\Exception $e){
            $estimasi = '-';
        }
    } elseif(!empty($pemesanan->estimasi_waktu)) {
        $estimasi = $pemesanan->estimasi_waktu;
    }

    $plat = $pemesanan->jadwal->shuttle->nomor_polisi ?? $pemesanan->shuttle->nomor_polisi ?? ($pemesanan->nomor_polisi ?? 'B 1234 CD');

    $nama_pemesan = $pemesanan->nama_pemesan ?? ($pemesanan->user->name ?? 'Nama Pemesan');
    $telepon_pemesan = $pemesanan->telepon_pemesan ?? ($pemesanan->user->phone ?? '-');
    $email_pemesan = $pemesanan->email_pemesan ?? ($pemesanan->user->email ?? '-');

    $jumlah_penumpang = $pemesanan->jumlah_penumpang ?? ($pemesanan->detailPenumpang->count() ?? 1);

    // PERBAIKAN: QR Code lebih besar untuk print
    function buildQrSrc($pemesanan, $ticketCode) {
        if(!empty($pemesanan->qr_path) && file_exists(public_path($pemesanan->qr_path))){
            return asset($pemesanan->qr_path);
        } elseif(!empty($pemesanan->qr_code) && file_exists(storage_path('app/public/qr/'.$pemesanan->qr_code))){
            return asset('storage/qr/'.$pemesanan->qr_code);
        } else {
            // QR Code lebih besar untuk print (300x300 untuk web, 600x600 untuk high-res print)
            return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data='.urlencode('SMARTSHUTTLE:'.$ticketCode.':CHECKIN:'.($pemesanan->id ?? ''));
        }
    }

    $backUrl = url()->previous() ?: (Route::has('customer.riwayat') ? route('customer.riwayat') : url('/riwayat'));
@endphp

<div class="ticket-center-container">
    <a class="back-btn" href="{{ $backUrl }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> <span>Kembali ke Riwayat</span>
    </a>

    <div class="tickets-wrapper" aria-live="polite">
        @if(isset($pemesanan->detailPenumpang) && $pemesanan->detailPenumpang->count()>0)
            @foreach($pemesanan->detailPenumpang as $dp)
                @php
                    $ticketKode = $kode . '-P' . str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                    $qr_src = buildQrSrc($pemesanan, $ticketKode);
                    $penumpang_nama = $dp->nama_lengkap ?? $dp->nama ?? $nama_pemesan;
                    $penumpang_telepon = $dp->telepon ?? $telepon_pemesan;
                    $penumpang_email = $dp->email ?? $email_pemesan;
                    $penumpang_nik = $dp->nik ?? null;
                    $kursi = $dp->nomor_kursi ?? '01';
                @endphp

                <main class="ticket-card" role="article" aria-labelledby="ticket-title-{{ $loop->iteration }}">
                    @if(file_exists(public_path('images/smartshuttlelogo.png')))
                        <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle" class="ticket-logo" />
                    @else
                        <div style="text-align:center;font-weight:800;color:var(--primary);margin-bottom:6px;font-size:24px;">SMART SHUTTLE</div>
                    @endif

                    <h1 id="ticket-title-{{ $loop->iteration }}" class="ticket-title">E-TICKET</h1>


                    <div class="dashed-divider" aria-hidden="true"></div>

                    <div class="qr-wrap" role="img" aria-label="QR Code Check-in (penumpang {{ $loop->iteration }})">
                        <img src="{{ $qr_src }}" alt="QR Code untuk check-in (kode: {{ $ticketKode }})" loading="lazy"
                             style="max-width:100%; height:auto;" />
                        <div style="margin-top:8px; font-size:12px; color:#666; font-weight:600;">
                            Scan untuk check-in
                        </div>
                    </div>

                    <div class="kode-pemesanan">Kode Pemesanan : {{ $kode }}</div>

                    <div class="dashed-divider" aria-hidden="true"></div>

                    <section aria-labelledby="route-heading-{{ $loop->iteration }}" class="route">
                        <div class="route-row">
                            <div class="route-city">{{ strtoupper($origin) }}</div>
                            <div class="route-city" style="text-align:right;">{{ strtoupper($destination) }}</div>
                        </div>

                        <div class="subinfo" id="route-heading-{{ $loop->iteration }}">{{ $tanggal_waktu_display }}</div>
                    </section>

                    <div class="info-columns" role="group" aria-label="Informasi perjalanan">
                        <div class="info-col">
                            <div class="label">ID Tiket</div>
                            <div class="value">{{ $ticketKode }}</div>

                            <div class="label" style="margin-top:8px;">Plat Shuttle</div>
                            <div class="value">{{ $plat }}</div>
                        </div>

                        <div class="info-col">
                            <div class="label">Kursi</div>
                            <div class="value">{{ $kursi }}</div>

                            <div class="label" style="margin-top:8px;">Estimasi</div>
                            <div class="value">{{ $estimasi }}</div>
                        </div>
                    </div>

                    <div class="dashed-divider" aria-hidden="true"></div>

                    <div class="section-title">DATA PENUMPANG</div>

                    <!-- URUTAN: NAMA, TELEPON, EMAIL, PENUMPANG, DAN NIK -->
                    <div class="data-row" aria-label="Nama penumpang {{ $loop->iteration }}">
                        <div class="left"><span>Nama</span></div>
                        <div class="right"><span>: {{ $penumpang_nama }}</span></div>
                    </div>

                    @if(!empty($penumpang_telepon))
                    <div class="data-row">
                        <div class="left"><span>Telepon</span></div>
                        <div class="right"><span>: {{ $penumpang_telepon }}</span></div>
                    </div>
                    @endif

                    @if(!empty($penumpang_email))
                    <div class="data-row">
                        <div class="left"><span>Email</span></div>
                        <div class="right"><span>: {{ $penumpang_email }}</span></div>
                    </div>
                    @endif

                    <div class="data-row">
                        <div class="left"><span>Penumpang</span></div>
                        <div class="right"><span>: {{ $loop->iteration }} dari {{ $pemesanan->detailPenumpang->count() }}</span></div>
                    </div>

                    @if(!empty($penumpang_nik))
                    <div class="data-row">
                        <div class="left"><span>NIK</span></div>
                        <div class="right"><span>: {{ $penumpang_nik }}</span></div>
                    </div>
                    @endif

                    <div class="dashed-divider" aria-hidden="true"></div>

                    <div class="section-title" style="font-size:14px;">Konfirmasi Kedatangan</div>
                    <ul class="instructions" aria-label="Instruksi check-in">
                        <li>Harap tiba 30 menit sebelum jadwal keberangkatan.</li>
                        <li>Silakan scan QR Code saat Anda sudah tiba di lokasi.</li>
                    </ul>

                    @if($total_bayar)
                        <div class="total-box" aria-label="Total pembayaran">
                            <div style="font-size:12px;color:#666;margin-top:8px;">Total Pembayaran</div>
                            <div style="font-weight:800;font-size:16px;color:var(--primary);margin-top:6px;">{{ $total_bayar }}</div>
                            @if(!empty($pemesanan->metode_pembayaran))
                                <div style="font-size:12px;color:#666;margin-top:6px;">{{ $pemesanan->metode_pembayaran }} • {{ \Carbon\Carbon::parse($pemesanan->created_at ?? now())->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                    @endif

                    <div class="actions" role="toolbar" aria-label="Aksi tiket">
                        <button class="btn btn-print" type="button" onclick="printOptimizedTicket({{ $loop->iteration }})" aria-label="Cetak tiket (Ctrl+P)">
                            <i class="fa fa-print" aria-hidden="true"></i> Cetak
                        </button>

                        <a class="btn btn-download" href="{{ route('customer.e_ticket.download', ['kode_booking' => $ticketKode]) }}" aria-label="Download PDF tiket (Ctrl+S)">
                            <i class="fa fa-download" aria-hidden="true"></i> Download
                        </a>

                        <button class="btn btn-share" type="button" id="shareBtn-{{ $loop->iteration }}" aria-label="Bagikan tiket">
                            <i class="fa fa-share-alt" aria-hidden="true"></i> Bagikan
                        </button>
                    </div>
                </main>
            @endforeach

            <!-- Tombol Cetak Semua Tiket -->
            @if($pemesanan->detailPenumpang->count() > 1)
                <div style="width:100%; text-align:center; margin-top:20px;">
                    <button class="back-btn" onclick="printAllOptimizedTickets()" style="background:var(--primary); color:white; border:none; cursor:pointer;">
                        <i class="fa fa-print" aria-hidden="true"></i> Cetak Semua Tiket ({{ $pemesanan->detailPenumpang->count() }})
                    </button>
                </div>
            @endif

        @else
            @php
                $ticketKode = $kode;
                $qr_src = buildQrSrc($pemesanan, $ticketKode);
                $kursi = $nomor_kursi ?? '01';
            @endphp

            <main class="ticket-card" role="article" aria-labelledby="ticket-title">
                @if(file_exists(public_path('images/smartshuttlelogo.png')))
                    <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle" class="ticket-logo" />
                @else
                    <div style="text-align:center;font-weight:800;color:var(--primary);margin-bottom:6px;font-size:24px;">SMART SHUTTLE</div>
                @endif

                <h1 id="ticket-title" class="ticket-title">E-TICKET</h1>
                <div class="ticket-id" aria-hidden="true">{{ $ticketKode }}</div>

                <div class="dashed-divider" aria-hidden="true"></div>

                <div class="qr-wrap" role="img" aria-label="QR Code Check-in">
                    <img src="{{ $qr_src }}" alt="QR Code untuk check-in (kode: {{ $ticketKode }})" loading="lazy"
                         style="max-width:100%; height:auto;" />
                    <div style="margin-top:8px; font-size:12px; color:#666; font-weight:600;">
                        Scan untuk check-in
                    </div>
                </div>

                <div class="kode-pemesanan">Kode Pemesanan : {{ $kode }}</div>

                <div class="dashed-divider" aria-hidden="true"></div>

                <section aria-labelledby="route-heading" class="route">
                    <div class="route-row">
                        <div class="route-city">{{ strtoupper($origin) }}</div>
                        <div class="route-city" style="text-align:right;">{{ strtoupper($destination) }}</div>
                    </div>

                    <div class="subinfo" id="route-heading">{{ $tanggal_waktu_display }}</div>
                </section>

                <div class="info-columns" role="group" aria-label="Informasi perjalanan">
                    <div class="info-col">
                        <div class="label">ID Tiket</div>
                        <div class="value">{{ $ticketKode }}</div>

                        <div class="label" style="margin-top:8px;">Plat Shuttle</div>
                        <div class="value">{{ $plat }}</div>
                    </div>

                    <div class="info-col">
                        <div class="label">Kursi</div>
                        <div class="value">{{ $kursi }}</div>

                        <div class="label" style="margin-top:8px;">Estimasi</div>
                        <div class="value">{{ $estimasi }}</div>
                    </div>
                </div>

                <div class="dashed-divider" aria-hidden="true"></div>

                <div class="section-title">DATA PENUMPANG</div>

                <!-- URUTAN: NAMA, TELEPON, EMAIL, PENUMPANG, DAN NIK -->
                <div class="data-row">
                    <div class="left"><span>Nama</span></div>
                    <div class="right"><span>: {{ $nama_pemesan }}</span></div>
                </div>

                <div class="data-row">
                    <div class="left"><span>Telepon</span></div>
                    <div class="right"><span>: {{ $telepon_pemesan }}</span></div>
                </div>

                <div class="data-row">
                    <div class="left"><span>Email</span></div>
                    <div class="right"><span>: {{ $email_pemesan }}</span></div>
                </div>

                <div class="data-row">
                    <div class="left"><span>Penumpang</span></div>
                    <div class="right"><span>: {{ $jumlah_penumpang }} orang</span></div>
                </div>

                @if(!empty($penumpang_nik))
                <div class="data-row">
                    <div class="left"><span>NIK</span></div>
                    <div class="right"><span>: {{ $penumpang_nik }}</span></div>
                </div>
                @endif

                <div class="dashed-divider" aria-hidden="true"></div>

                <div class="section-title" style="font-size:16px;">Konfirmasi Kedatangan</div>
                <ul class="instructions" aria-label="Instruksi check-in">
                    <li>Harap tiba 30 menit sebelum jadwal keberangkatan.</li>
                    <li>Silakan scan QR Code saat Anda sudah tiba di lokasi.</li>
                </ul>

                @if($total_bayar)
                    <div class="total-box" aria-label="Total pembayaran">
                        <div style="font-size:12px;color:#666;margin-top:12px;">Total Pembayaran</div>
                        <div style="font-weight:800;font-size:16px;color:var(--primary);margin-top:6px;">{{ $total_bayar }}</div>
                        @if(!empty($pemesanan->metode_pembayaran))
                            <div style="font-size:12px;color:#666;margin-top:6px;">{{ $pemesanan->metode_pembayaran }} • {{ \Carbon\Carbon::parse($pemesanan->created_at ?? now())->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                @endif

                <div class="actions" role="toolbar" aria-label="Aksi tiket">
                    <button class="btn btn-print" type="button" onclick="printOptimizedTicket()" aria-label="Cetak tiket (Ctrl+P)">
                        <i class="fa fa-print" aria-hidden="true"></i> Cetak
                    </button>

                    <a class="btn btn-download" href="{{ route('customer.e_ticket.download', ['kode_booking' => $ticketKode]) }}" aria-label="Download PDF tiket (Ctrl+S)">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </a>

                    <button class="btn btn-share" type="button" id="shareBtn" aria-label="Bagikan tiket">
                        <i class="fa fa-share-alt" aria-hidden="true"></i> Bagikan
                    </button>
                </div>
            </main>
        @endif
    </div> {{-- end tickets-wrapper --}}
</div> {{-- end ticket-center-container --}}
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
    // Fungsi untuk print tiket dengan QR Code optimal
    function printOptimizedTicket(ticketNumber = null) {
        // Sembunyikan tombol kembali
        const backBtn = document.querySelector('.back-btn');
        if (backBtn) backBtn.style.display = 'none';

        // Jika ada multiple tickets, tampilkan hanya yang dipilih
        if (ticketNumber) {
            const allTickets = document.querySelectorAll('.ticket-card');
            allTickets.forEach(ticket => {
                ticket.style.display = 'none';
            });

            const selectedTicket = document.querySelector(`[aria-labelledby="ticket-title-${ticketNumber}"]`);
            if (selectedTicket) {
                selectedTicket.style.display = 'flex';
            }
        }

        // Tingkatkan kualitas QR Code untuk print
        const qrImages = document.querySelectorAll('.qr-wrap img');
        qrImages.forEach(img => {
            // Ganti dengan QR Code resolusi tinggi untuk print
            const currentSrc = img.src;
            if (currentSrc.includes('api.qrserver.com')) {
                // Tingkatkan ukuran QR Code untuk print
                img.src = currentSrc.replace('size=300x300', 'size=400x400');
                img.style.width = '200px';
                img.style.height = '200px';
            }
        });

        // Tunggu sebentar untuk memastikan gambar dimuat
        setTimeout(() => {
            window.print();

            // Reset setelah print
            setTimeout(() => {
                if (ticketNumber) {
                    const allTickets = document.querySelectorAll('.ticket-card');
                    allTickets.forEach(ticket => {
                        ticket.style.display = 'flex';
                    });
                }
                if (backBtn) backBtn.style.display = '';

                // Reset QR Code ke ukuran normal
                qrImages.forEach(img => {
                    const currentSrc = img.src;
                    if (currentSrc.includes('api.qrserver.com')) {
                        img.src = currentSrc.replace('size=400x400', 'size=300x300');
                        img.style.width = '';
                        img.style.height = '';
                    }
                });
            }, 500);
        }, 500);
    }

    // Fungsi untuk print semua tiket
    function printAllOptimizedTickets() {
        // Sembunyikan tombol kembali
        const backBtn = document.querySelector('.back-btn');
        if (backBtn) backBtn.style.display = 'none';

        // Tingkatkan kualitas QR Code untuk print
        const qrImages = document.querySelectorAll('.qr-wrap img');
        qrImages.forEach(img => {
            const currentSrc = img.src;
            if (currentSrc.includes('api.qrserver.com')) {
                // Tingkatkan ukuran QR Code untuk print
                img.src = currentSrc.replace('size=300x300', 'size=400x400');
                img.style.width = '200px';
                img.style.height = '200px';
            }
        });

        // Tunggu sebentar untuk memastikan gambar dimuat
        setTimeout(() => {
            window.print();

            // Reset setelah print
            setTimeout(() => {
                if (backBtn) backBtn.style.display = '';

                // Reset QR Code ke ukuran normal
                qrImages.forEach(img => {
                    const currentSrc = img.src;
                    if (currentSrc.includes('api.qrserver.com')) {
                        img.src = currentSrc.replace('size=400x400', 'size=300x300');
                        img.style.width = '';
                        img.style.height = '';
                    }
                });
            }, 500);
        }, 500);
    }

    // Handle Ctrl+P untuk print
    document.addEventListener('keydown', function(e){
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            const tickets = document.querySelectorAll('.ticket-card');
            if (tickets.length > 1) {
                printAllOptimizedTickets();
            } else {
                printOptimizedTicket();
            }
        }

        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
            e.preventDefault();
            const link = document.querySelector('.btn-download');
            if (link) window.location.href = link.href;
        }
    });

    // Handle share button
    document.addEventListener('click', async function(e){
        if (!e.target) return;

        const shareBtn = e.target.closest('[id^="shareBtn"]');
        if (shareBtn) {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'E-Ticket Smart Shuttle',
                        text: shareBtn.closest('.ticket-card') ? shareBtn.closest('.ticket-card').querySelector('.ticket-id').textContent.trim() : 'E-Ticket Smart Shuttle',
                        url: window.location.href
                    });
                } catch (err) {
                    console.log('Error sharing:', err);
                }
            } else {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    alert('Link tiket disalin ke clipboard.');
                } catch (e) {
                    alert('Fitur bagikan tidak tersedia di browser ini.');
                }
            }
        }
    });

    // Preload QR Code images untuk print
    document.addEventListener('DOMContentLoaded', function() {
        // Preload high-res QR Code untuk print
        const qrImages = document.querySelectorAll('.qr-wrap img');
        const highResImages = [];

        qrImages.forEach(img => {
            const currentSrc = img.src;
            if (currentSrc.includes('api.qrserver.com')) {
                const highResSrc = currentSrc.replace('size=300x300', 'size=400x400');
                const preloadImg = new Image();
                preloadImg.src = highResSrc;
                highResImages.push(preloadImg);
            }
        });
    });
</script>
@endpush
