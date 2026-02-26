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

    /* Container utama dengan flex untuk tombol samping */
    .e-ticket-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
        position: relative;
        z-index: 10;
        display: flex;
        align-items: flex-start;
        gap: 30px;
    }

    /* Tombol kembali di samping kiri */
    .ticket-sidebar {
        flex: 0 0 auto;
        position: sticky;
        top: 30px;
        margin-top: 40px;
    }

    .back-btn-side {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        background: var(--primary);
        color: white;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0, 33, 94, 0.2);
    }

    .back-btn-side:hover {
        background: #00338a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 33, 94, 0.3);
        text-decoration: none;
        color: white;
    }

    .back-btn-side i {
        font-size: 14px;
    }

    /* Konten utama di kanan */
    .ticket-main-content {
        flex: 1;
        width: 100%;
    }

    /* Header title */
    .ticket-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .header-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
    }

    /* Grid wrapper untuk tiket - DI TENGAH */
    .tickets-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        width: 100%;
        margin: 0 auto;
    }

    /* Ticket card */
    .ticket-card {
        width: 100%;
        max-width: 450px;
        background: var(--card-bg);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: var(--shadow);
        position: relative;
        z-index: 10;
        box-sizing: border-box;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    /* PRINT SPECIFIC STYLES (tetap sama) */
    @media print {
        /* ... semua aturan print tetap seperti aslinya, tidak diubah ... */
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

        .ticket-sidebar,
        .ticket-header {
            display: none !important;
        }

        .e-ticket-container {
            width: 100% !important;
            height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }

        .ticket-main-content {
            width: 100% !important;
        }

        .tickets-wrapper {
            flex-direction: column !important;
            align-items: center !important;
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 8mm !important;
            gap: 4mm !important;
        }

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
        }

        .ticket-card:not(:last-child) {
            margin-bottom: 3mm !important;
        }

        .actions {
            display: none !important;
        }

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

        .section-title {
            font-size: 14px !important;
            margin-top: 3mm !important;
            margin-bottom: 2mm !important;
            font-weight: 900 !important;
            color: var(--primary) !important;
        }

        .data-row {
            margin-top: 2mm !important;
            margin-bottom: 1mm !important;
        }

        .data-row .left,
        .data-row .right {
            font-size: 11px !important;
            line-height: 1.5 !important;
        }

        .dashed-divider {
            border-top: 1.5px dashed #ccc !important;
            margin: 3mm 0 !important;
        }

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

        .route-row {
            margin-top: 3mm !important;
            margin-bottom: 2mm !important;
        }

        @page {
            margin: 10mm;
            size: A4 portrait;
        }

        html, body {
            overflow: hidden !important;
        }

        @supports (display: flex) {
            .tickets-wrapper {
                max-height: 277mm !important;
                overflow: visible !important;
            }
        }
    }

    /* Styling tiket (non-print) */
    .ticket-logo {
        width: 82px;
        display: block;
        margin: 0 auto 10px;
    }

    h1.ticket-title {
        margin: 0;
        text-align: center;
        font-size: 22px;
        color: var(--primary);
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .ticket-id {
        text-align: center;
        font-size: 16px;
        font-weight: 700;
        color: var(--accent);
        margin-top: 8px;
    }

    .dashed-divider {
        border-top: 2px dashed #ddd;
        margin: 15px 0;
    }

    .qr-wrap {
        text-align: center;
        margin: 15px 0;
    }

    .qr-wrap img {
        width: 180px;
        height: 180px;
        object-fit: cover;
        background: white;
        padding: 12px;
        border-radius: 12px;
        display: inline-block;
        border: 2px solid #eee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .kode-pemesanan {
        text-align: center;
        margin-top: 10px;
        font-weight: 700;
        color: var(--accent);
        font-size: 14px;
    }

    /* Route row dengan tanda panah di tengah */
    .route-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        gap: 10px;
        position: relative;
    }

    .route-city {
        flex: 1;
        font-weight: 800;
        font-size: 18px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
    }

    /* Tanda panah di tengah */
    .route-arrow {
        color: var(--accent);
        font-weight: bold;
        font-size: 20px;
        flex-shrink: 0;
        margin: 0 5px;
    }

    .subinfo {
        color: var(--muted);
        font-size: 13px;
        margin-top: 8px;
        text-align: center;
        font-weight: 600;
    }

    .info-columns {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 15px;
    }

    .info-col {
        width: 48%;
    }

    .label {
        font-size: 14px;
        color: #666;
        font-weight: 600;
    }

    .value {
        margin-top: 8px;
        font-size: 15px;
        font-weight: 800;
        color: #102a43;
    }

    .section-title {
        margin-top: 15px;
        font-size: 16px;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: 0.5px;
    }

    /* Data row - FIXED FOR MOBILE */
    .data-row {
        display: flex;
        margin-top: 10px;
        width: 100%;
        flex-wrap: nowrap;
        align-items: flex-start;
    }

    .data-row .left {
        min-width: 100px;
        max-width: 35%;
        color: #444;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .data-row .right {
        flex: 1;
        color: #222;
        font-weight: 700;
        font-size: 14px;
        word-break: break-word;
        overflow-wrap: break-word;
        margin-left: 5px;
    }

    ul.instructions {
        margin: 10px 0 0 20px;
        color: #444;
        font-size: 14px;
        padding: 0;
    }

    ul.instructions li {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .total-box {
        text-align: center;
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    /* Action buttons - MOBILE OPTIMIZED */
    .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        min-width: 120px;
        flex: 1;
        min-height: 48px;
        touch-action: manipulation;
        text-decoration: none;
    }

    .btn-print {
        background: var(--primary);
        color: #fff;
    }

    .btn-print:hover {
        background: #00338a;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 33, 94, 0.3);
    }

    .btn-download {
        background: #10b981;
        color: #fff;
    }

    .btn-download:hover {
        background: #0da271;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-share {
        background: #8b5cf6;
        color: #fff;
    }

    .btn-share:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
    }

    .btn i {
        font-size: 16px;
    }

    /* Tombol cetak semua tiket */
    .print-all-btn {
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 30px auto;
        padding: 15px 30px;
        background: linear-gradient(135deg, var(--primary) 0%, #0038A8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(0, 33, 94, 0.25);
    }

    .print-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 33, 94, 0.35);
    }

    /* ==================== */
    /* RESPONSIVE BREAKPOINTS */
    /* ==================== */

    /* Tablet Landscape / Desktop Kecil */
    @media (max-width: 1200px) {
        .tickets-wrapper {
            gap: 20px;
        }

        .ticket-card {
            max-width: 420px;
        }
    }

    /* Tablet Portrait / Desktop Sangat Kecil */
    @media (max-width: 992px) {
        .e-ticket-container {
            flex-direction: column;
            gap: 20px;
            padding: 20px 15px;
        }

        .ticket-sidebar {
            position: static;
            margin-top: 0;
            order: 1;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 0 15px 0;
            border-bottom: 1px solid rgba(0, 33, 94, 0.1);
        }

        .back-btn-side {
            width: auto;
            min-width: 180px;
            margin: 0;
            justify-content: center;
        }

        .ticket-main-content {
            order: 2;
        }

        .tickets-wrapper {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .ticket-card {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }

        .header-title {
            font-size: 24px;
        }

        .qr-wrap img {
            width: 160px;
            height: 160px;
        }

        .route-city {
            font-size: 17px;
        }

        .route-arrow {
            font-size: 18px;
        }
    }

    /* Mobile Landscape */
    @media (max-width: 768px) {
        .e-ticket-container {
            padding: 15px;
        }

        .ticket-header {
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 22px;
        }

        .ticket-card {
            padding: 18px;
            min-height: auto;
        }

        .qr-wrap img {
            width: 150px;
            height: 150px;
        }

        /* Data row mobile fix */
        .data-row {
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .data-row .left {
            width: 100%;
            max-width: 100%;
            min-width: auto;
            font-size: 13px;
            margin-bottom: 2px;
            color: #555;
        }

        .data-row .right {
            width: 100%;
            font-size: 14px;
            margin-left: 0;
            padding-left: 10px;
            border-left: 2px solid #e9ecef;
            color: #222;
        }

        /* Tombol action mobile */
        .actions {
            flex-direction: row;
            gap: 8px;
            padding-top: 16px;
            margin-top: 16px;
        }

        .btn {
            padding: 12px 10px;
            font-size: 13px;
            min-width: 0;
            flex: 1 1 calc(33.333% - 8px);
            max-width: calc(33.333% - 8px);
            min-height: 44px;
            border-radius: 8px;
        }

        .btn i {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .btn span {
            display: block;
            font-size: 12px;
            line-height: 1.2;
        }

        .route-city {
            font-size: 16px;
        }

        .route-arrow {
            font-size: 16px;
        }

        /* Info columns mobile */
        .info-columns {
            flex-direction: column;
            gap: 12px;
        }

        .info-col {
            width: 100%;
        }

        .print-all-btn {
            padding: 14px 25px;
            font-size: 15px;
            max-width: 280px;
        }
    }

    /* Mobile Portrait */
    @media (max-width: 576px) {
        .ticket-header {
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 20px;
        }

        .back-btn-side {
            padding: 10px 16px;
            font-size: 14px;
            min-width: 160px;
        }

        .ticket-card {
            padding: 16px;
            border-radius: 16px;
        }

        .qr-wrap img {
            width: 140px;
            height: 140px;
        }

        .ticket-title {
            font-size: 20px;
        }

        .kode-pemesanan {
            font-size: 13px;
        }

        /* Data row sangat kecil */
        .data-row .left {
            font-size: 12px;
        }

        .data-row .right {
            font-size: 13px;
            padding-left: 8px;
        }

        /* Tombol untuk screen sangat kecil */
        .actions {
            gap: 6px;
        }

        .btn {
            padding: 10px 8px;
            font-size: 12px;
            flex: 1 1 calc(33.333% - 6px);
            max-width: calc(33.333% - 6px);
            min-height: 42px;
        }

        .btn i {
            font-size: 13px;
            margin-bottom: 1px;
        }

        .btn span {
            font-size: 11px;
        }

        .route-city {
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .route-arrow {
            font-size: 15px;
        }

        .value {
            font-size: 14px;
        }

        .section-title {
            font-size: 15px;
        }

        .print-all-btn {
            padding: 12px 20px;
            font-size: 14px;
            max-width: 260px;
        }
    }

    /* Mobile Sangat Kecil */
    @media (max-width: 400px) {
        .ticket-header {
            margin-bottom: 12px;
        }

        .header-title {
            font-size: 18px;
        }

        .back-btn-side {
            padding: 8px 14px;
            font-size: 13px;
            min-width: 140px;
        }

        .ticket-card {
            padding: 14px;
            border-radius: 14px;
        }

        .qr-wrap img {
            width: 130px;
            height: 130px;
            padding: 10px;
        }

        .ticket-title {
            font-size: 18px;
        }

        .ticket-id {
            font-size: 14px;
        }

        .kode-pemesanan {
            font-size: 12px;
        }

        .data-row .left {
            font-size: 11px;
        }

        .data-row .right {
            font-size: 12px;
            padding-left: 6px;
        }

        .actions {
            gap: 5px;
        }

        .btn {
            padding: 9px 6px;
            font-size: 11px;
            flex: 1 1 calc(33.333% - 5px);
            max-width: calc(33.333% - 5px);
            min-height: 40px;
        }

        .btn i {
            font-size: 12px;
        }

        .btn span {
            font-size: 10px;
        }

        .route-city {
            font-size: 14px;
        }

        .route-arrow {
            font-size: 14px;
            margin: 0 3px;
        }

        .subinfo {
            font-size: 12px;
        }

        .value {
            font-size: 13px;
        }

        .print-all-btn {
            padding: 10px 16px;
            font-size: 13px;
            max-width: 240px;
        }
    }

    /* Untuk tablet landscape atau layar sedang (768-992px) */
    @media (min-width: 769px) and (max-width: 992px) {
        .btn {
            padding: 12px 14px;
            font-size: 13px;
            min-width: 100px;
        }

        .data-row .left {
            min-width: 120px;
        }
    }

    /* Active state untuk mobile touch */
    .btn:active,
    .back-btn-side:active,
    .print-all-btn:active {
        transform: translateY(0) !important;
        opacity: 0.9;
        transition: transform 0.1s ease;
    }

    /* Pastikan QR code tetap proporsional */
    .qr-wrap img {
        max-width: 100%;
        height: auto;
    }

    /* Touch target yang lebih besar untuk mobile */
    @media (max-width: 768px) {
        .btn,
        .back-btn-side,
        .print-all-btn {
            min-height: 44px;
        }
    }
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    function formatTanggalIndonesia($dateString, $includeTime = false) {
        if (!$dateString) return '-';

        try {
            $date = Carbon::parse($dateString);

            $hari = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

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

    function buildQrSrc($pemesanan, $ticketCode) {
        if(!empty($pemesanan->qr_path) && file_exists(public_path($pemesanan->qr_path))){
            return asset($pemesanan->qr_path);
        } elseif(!empty($pemesanan->qr_code) && file_exists(storage_path('app/public/qr/'.$pemesanan->qr_code))){
            return asset('storage/qr/'.$pemesanan->qr_code);
        } else {
            return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data='.urlencode('SMARTSHUTTLE:'.$ticketCode.':CHECKIN:'.($pemesanan->id ?? ''));
        }
    }

    $backUrl = url()->previous() ?: (Route::has('customer.riwayat') ? route('customer.riwayat') : url('/riwayat'));
@endphp

<div class="e-ticket-container">
    <!-- Tombol kembali di samping kiri -->
    <div class="ticket-sidebar">
        <a class="back-btn-side" href="{{ route('customer.riwayat') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Konten utama di kanan -->
    <div class="ticket-main-content">
        <!-- Header title -->
        <div class="ticket-header">
            <h1 class="header-title">E-Ticket Smart Shuttle</h1>
        </div>

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
                                <div class="route-arrow" aria-hidden="true">→</div>
                                <div class="route-city">{{ strtoupper($destination) }}</div>
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

                        @if($total_bayar ?? false)
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
                                <i class="fa fa-print" aria-hidden="true"></i>
                                <span>Cetak</span>
                            </button>

                            <a class="btn btn-download" href="{{ route('customer.e_ticket.download', ['kode_booking' => $pemesanan->kode_booking]) }}" aria-label="Download PDF tiket (Ctrl+S)" download target="_blank" rel="noopener">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                <span>Download</span>
                            </a>

                            <button class="btn btn-share" onclick="shareTicket({{ $loop->iteration }})" aria-label="Bagikan tiket">
                                <i class="fa fa-share-alt" aria-hidden="true"></i>
                                <span>Bagikan</span>
                            </button>
                        </div>
                    </main>
                @endforeach

                <!-- Tombol Cetak Semua Tiket -->
                @if($pemesanan->detailPenumpang->count() > 1)
                    <button class="print-all-btn" onclick="printAllOptimizedTickets()">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        <span>Cetak Semua Tiket ({{ $pemesanan->detailPenumpang->count() }})</span>
                    </button>
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
                            <div class="route-arrow" aria-hidden="true">→</div>
                            <div class="route-city">{{ strtoupper($destination) }}</div>
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

                    @if($total_bayar ?? false)
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
                            <i class="fa fa-print" aria-hidden="true"></i>
                            <span>Cetak</span>
                        </button>

                        <a class="btn btn-download" href="{{ route('customer.e_ticket.download', ['kode_booking' => $pemesanan->kode_booking]) }}" aria-label="Download PDF tiket (Ctrl+S)" download target="_blank" rel="noopener">
                            <i class="fa fa-download" aria-hidden="true"></i>
                            <span>Download</span>
                        </a>

                        <button class="btn btn-share" onclick="shareTicket()" aria-label="Bagikan tiket">
                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                            <span>Bagikan</span>
                        </button>
                    </div>
                </main>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
    function printOptimizedTicket(ticketNumber = null) {
        const backBtn = document.querySelector('.back-btn-side');
        if (backBtn) backBtn.style.display = 'none';

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

        const qrImages = document.querySelectorAll('.qr-wrap img');
        qrImages.forEach(img => {
            const currentSrc = img.src;
            if (currentSrc.includes('api.qrserver.com')) {
                img.src = currentSrc.replace('size=300x300', 'size=400x400');
                img.style.width = '200px';
                img.style.height = '200px';
            }
        });

        setTimeout(() => {
            window.print();

            setTimeout(() => {
                if (ticketNumber) {
                    const allTickets = document.querySelectorAll('.ticket-card');
                    allTickets.forEach(ticket => {
                        ticket.style.display = 'flex';
                    });
                }
                if (backBtn) backBtn.style.display = '';

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

    function printAllOptimizedTickets() {
        const backBtn = document.querySelector('.back-btn-side');
        if (backBtn) backBtn.style.display = 'none';

        const qrImages = document.querySelectorAll('.qr-wrap img');
        qrImages.forEach(img => {
            const currentSrc = img.src;
            if (currentSrc.includes('api.qrserver.com')) {
                img.src = currentSrc.replace('size=300x300', 'size=400x400');
                img.style.width = '200px';
                img.style.height = '200px';
            }
        });

        setTimeout(() => {
            window.print();

            setTimeout(() => {
                if (backBtn) backBtn.style.display = '';

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

    // Fungsi untuk share ticket (mobile friendly)
    function shareTicket(ticketNumber = null) {
        const ticketCode = ticketNumber ?
            `Tiket ${ticketNumber} - ${document.querySelectorAll('.ticket-card')[ticketNumber - 1]?.querySelector('.ticket-id')?.textContent || 'Smart Shuttle'}` :
            'Tiket Smart Shuttle';

        const shareData = {
            title: 'E-Ticket Smart Shuttle',
            text: ticketCode,
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData)
                .then(() => console.log('Berhasil dibagikan'))
                .catch(err => console.log('Error sharing:', err));
        } else {
            // Fallback untuk non-share API
            navigator.clipboard.writeText(window.location.href)
                .then(() => {
                    alert('Link tiket berhasil disalin ke clipboard!');
                })
                .catch(err => {
                    // Fallback untuk browser lama
                    const textArea = document.createElement('textarea');
                    textArea.value = window.location.href;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Link tiket berhasil disalin!');
                });
        }
    }

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
            if (link) {
                window.open(link.href, '_blank');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
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
