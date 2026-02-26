{{-- resources/views/customer/e_ticket_pdf.blade.php --}}
{{-- Versi sederhana untuk cetak PDF --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket PDF - Smart Shuttle</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #fff;
            color: #000;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Atur margin halaman PDF */
        @page {
            margin: 1.5cm 1cm 1.5cm 1cm;
            size: A4 portrait;
        }

        /* Kontainer utama */
        .ticket-container {
            max-width: 100%;
            padding: 0;
            margin: 0 auto;
        }

        /* Setiap tiket dalam halaman terpisah */
        .ticket-card {
            page-break-after: always;
            page-break-inside: avoid;
            break-inside: avoid;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: none;
            width: 100%;
            max-width: 100%;
        }

        /* Header logo & judul */
        .ticket-logo {
            width: 70px;
            display: block;
            margin: 0 auto 10px;
        }

        .ticket-title {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            color: #00215E;
            letter-spacing: 0.5px;
            margin: 0 0 5px;
        }

        .booking-code {
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #e04500;
            margin: 0 0 10px;
        }

        hr.dashed {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 15px 0;
        }

        /* QR Code */
        .qr-wrap {
            text-align: center;
            margin: 15px 0;
        }
        .qr-wrap img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            background: white;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .qr-caption {
            font-size: 10px;
            color: #555;
            margin-top: 5px;
            font-weight: 600;
        }

        /* Rute */
        .route-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0 5px;
        }
        .route-city {
            font-size: 16px;
            font-weight: 800;
            color: #00215E;
            text-transform: uppercase;
            text-align: center;
            flex: 1;
        }
        .route-arrow {
            color: #e04500;
            font-size: 18px;
            font-weight: bold;
            margin: 0 8px;
        }
        .subinfo {
            text-align: center;
            font-size: 12px;
            color: #666;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Dua kolom info */
        .info-columns {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin: 15px 0;
        }
        .info-col {
            width: 48%;
        }
        .label {
            font-size: 11px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
        }
        .value {
            font-size: 14px;
            font-weight: 800;
            color: #102a43;
            margin-top: 2px;
        }

        /* Data penumpang */
        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #00215E;
            margin: 15px 0 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }
        .data-row {
            display: flex;
            margin: 5px 0;
            font-size: 12px;
        }
        .data-left {
            width: 90px;
            font-weight: 600;
            color: #444;
        }
        .data-right {
            flex: 1;
            font-weight: 500;
            color: #222;
        }

        /* Tidak ada aksi, instruksi, total pembayaran */
        .no-print, .actions, .instructions, .total-box, .print-all-btn, .ticket-sidebar, .ticket-header {
            display: none !important;
        }

        /* Responsif kecil */
        @media print {
            .ticket-card {
                border: 1px solid #999 !important;
                padding: 15px 20px !important;
                margin: 0 !important;
            }
            .qr-wrap img {
                width: 130px !important;
                height: 130px !important;
            }
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;

    function formatTanggalIndonesia($dateString, $includeTime = false) {
        if (!$dateString) return '-';
        try {
            $date = Carbon::parse($dateString);
            $hari = ['Sunday' => 'Minggu','Monday' => 'Senin','Tuesday' => 'Selasa','Wednesday' => 'Rabu','Thursday' => 'Kamis','Friday' => 'Jumat','Saturday' => 'Sabtu'];
            $bulan = ['January' => 'Januari','February' => 'Februari','March' => 'Maret','April' => 'April','May' => 'Mei','June' => 'Juni','July' => 'Juli','August' => 'Agustus','September' => 'September','October' => 'Oktober','November' => 'November','December' => 'Desember'];
            $hariIndonesia = $hari[$date->format('l')] ?? $date->format('l');
            $bulanIndonesia = $bulan[$date->format('F')] ?? $date->format('F');
            $format = $hariIndonesia . ', ' . $date->format('j') . ' ' . $bulanIndonesia . ' ' . $date->format('Y');
            if ($includeTime) $format .= ' ' . $date->format('H:i');
            return $format;
        } catch (\Exception $e) {
            return '-';
        }
    }

    if (!isset($pemesanan)) {
        echo '<div style="text-align:center;padding:40px;">E-Ticket tidak ditemukan.</div>';
        return;
    }

    $kode = $pemesanan->kode_booking ?? ('TKT-'.date('Ymd').'-'.str_pad($pemesanan->id ?? 0,3,'0',STR_PAD_LEFT));

    // Ambil asal & tujuan
    $origin = 'Jakarta';
    $destination = 'Tujuan';
    if (!empty($pemesanan->jadwal) && !empty($pemesanan->jadwal->rutes) && $pemesanan->jadwal->rutes->count() > 0) {
        $first = $pemesanan->jadwal->rutes->first();
        $last  = $pemesanan->jadwal->rutes->last();
        $origin = $first->kota_asal ?? $origin;
        $destination = $last->kota_tujuan ?? $destination;
    } else {
        if (method_exists($pemesanan,'outletAsal') && $pemesanan->outletAsal) {
            $origin = $pemesanan->outletAsal->kota ?? ($pemesanan->outletAsal->nama_outlet ?? $origin);
        }
        if (method_exists($pemesanan,'outletTujuan') && $pemesanan->outletTujuan) {
            $destination = $pemesanan->outletTujuan->kota ?? ($pemesanan->outletTujuan->nama_outlet ?? $destination);
        }
    }

    // Tanggal & waktu
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
    $tanggal_waktu_display = $tanggal_display . ($waktu_display !== '-' ? ' ' . $waktu_display : '');

    // Plat
    $plat = $pemesanan->jadwal->shuttle->nomor_polisi ?? $pemesanan->shuttle->nomor_polisi ?? ($pemesanan->nomor_polisi ?? 'B 1234 CD');

    // Data pemesan (untuk fallback jika tidak ada detailPenumpang)
    $nama_pemesan = $pemesanan->nama_pemesan ?? ($pemesanan->user->name ?? 'Nama Pemesan');
    $email_pemesan = $pemesanan->email_pemesan ?? ($pemesanan->user->email ?? '-');
    $telepon_pemesan = $pemesanan->telepon_pemesan ?? ($pemesanan->user->phone ?? '-');

    // Helper QR
    function buildQrSrc($pemesanan, $ticketCode) {
        if (!empty($pemesanan->qr_path) && file_exists(public_path($pemesanan->qr_path))) {
            return asset($pemesanan->qr_path);
        } elseif (!empty($pemesanan->qr_code) && file_exists(storage_path('app/public/qr/'.$pemesanan->qr_code))) {
            return asset('storage/qr/'.$pemesanan->qr_code);
        } else {
            return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data='.urlencode('SMARTSHUTTLE:'.$ticketCode.':CHECKIN:'.($pemesanan->id ?? ''));
        }
    }
@endphp

<div class="ticket-container">
    @if(isset($pemesanan->detailPenumpang) && $pemesanan->detailPenumpang->count() > 0)
        @foreach($pemesanan->detailPenumpang as $dp)
            @php
                $ticketKode = $kode . '-P' . str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                $qr_src = $qr_map[$ticketKode] ?? buildQrSrc($pemesanan, $ticketKode);
                $penumpang_nama = $dp->nama_lengkap ?? $dp->nama ?? $nama_pemesan;
                $penumpang_email = $dp->email ?? $email_pemesan;
                $penumpang_nik = $dp->nik ?? null;
                $kursi = $dp->nomor_kursi ?? '01';
            @endphp
            <div class="ticket-card">
                <!-- Logo (opsional) -->
                @if(file_exists(public_path('images/smartshuttlelogo.png')))
                    <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle" class="ticket-logo">
                @else
                    <div style="text-align:center; font-weight:800; color:#00215E; font-size:22px;">SMART SHUTTLE</div>
                @endif

                <div class="ticket-title">E-TICKET</div>
                <div class="booking-code">Kode: {{ $kode }}</div>

                <hr class="dashed">

                <!-- QR -->
                <div class="qr-wrap">
                    <img src="{{ $qr_src }}" alt="QR Code">
                    <div class="qr-caption">Scan untuk check-in</div>
                </div>

                <!-- Rute -->
                <div class="route-row">
                    <span class="route-city">{{ strtoupper($origin) }}</span>
                    <span class="route-arrow">→</span>
                    <span class="route-city">{{ strtoupper($destination) }}</span>
                </div>
                <div class="subinfo">{{ $tanggal_waktu_display }}</div>

                <!-- Info kolom: tiket id, kursi, plat -->
                <div class="info-columns">
                    <div class="info-col">
                        <div class="label">ID Tiket</div>
                        <div class="value">{{ $ticketKode }}</div>
                        <div class="label" style="margin-top:8px;">Plat</div>
                        <div class="value">{{ $plat }}</div>
                    </div>
                    <div class="info-col">
                        <div class="label">Kursi</div>
                        <div class="value">{{ $kursi }}</div>
                    </div>
                </div>

                <hr class="dashed">

                <!-- Data Penumpang (hanya nama, email, NIK) -->
                <div class="section-title">Data Penumpang</div>
                <div class="data-row">
                    <span class="data-left">Nama</span>
                    <span class="data-right">: {{ $penumpang_nama }}</span>
                </div>
                <div class="data-row">
                    <span class="data-left">Email</span>
                    <span class="data-right">: {{ $penumpang_email }}</span>
                </div>
                @if(!empty($penumpang_nik))
                <div class="data-row">
                    <span class="data-left">NIK</span>
                    <span class="data-right">: {{ $penumpang_nik }}</span>
                </div>
                @endif
                <!-- Tidak menampilkan telepon, total, instruksi, dll -->
            </div>
        @endforeach
    @else
        @php
            $ticketKode = $kode;
            $qr_src = buildQrSrc($pemesanan, $ticketKode);
            $kursi = $nomor_kursi ?? '01';
            $penumpang_nik = $pemesanan->nik ?? ($pemesanan->user->nik ?? null);
        @endphp
        <div class="ticket-card">
            @if(file_exists(public_path('images/smartshuttlelogo.png')))
                <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle" class="ticket-logo">
            @else
                <div style="text-align:center; font-weight:800; color:#00215E; font-size:22px;">SMART SHUTTLE</div>
            @endif

            <div class="ticket-title">E-TICKET</div>
            <div class="booking-code">Kode: {{ $kode }}</div>

            <hr class="dashed">

            <div class="qr-wrap">
                <img src="{{ $qr_src }}" alt="QR Code">
                <div class="qr-caption">Scan untuk check-in</div>
            </div>

            <div class="route-row">
                <span class="route-city">{{ strtoupper($origin) }}</span>
                <span class="route-arrow">→</span>
                <span class="route-city">{{ strtoupper($destination) }}</span>
            </div>
            <div class="subinfo">{{ $tanggal_waktu_display }}</div>

            <div class="info-columns">
                <div class="info-col">
                    <div class="label">ID Tiket</div>
                    <div class="value">{{ $ticketKode }}</div>
                    <div class="label" style="margin-top:8px;">Plat</div>
                    <div class="value">{{ $plat }}</div>
                </div>
                <div class="info-col">
                    <div class="label">Kursi</div>
                    <div class="value">{{ $kursi }}</div>
                </div>
            </div>

            <hr class="dashed">

            <div class="section-title">Data Penumpang</div>
            <div class="data-row">
                <span class="data-left">Nama</span>
                <span class="data-right">: {{ $nama_pemesan }}</span>
            </div>
            <div class="data-row">
                <span class="data-left">Email</span>
                <span class="data-right">: {{ $email_pemesan }}</span>
            </div>
            @if(!empty($penumpang_nik))
            <div class="data-row">
                <span class="data-left">NIK</span>
                <span class="data-right">: {{ $penumpang_nik }}</span>
            </div>
            @endif
        </div>
    @endif
</div>


</body>
</html>
