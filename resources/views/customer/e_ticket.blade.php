{{-- resources/views/customer/e_ticket.blade.php --}}
@extends('layouts.app')

@section('title', 'E-Ticket - Smart Shuttle')

@php
    // Ambil background langsung dari public/images (tidak menggunakan MProfilePerusahaan)
    $bgPath = 'images/backgroundpeta.png';
@endphp

@push('styles')
<style>
    /* ------------------------------------------------------------
       Gaya utama tiket
       ------------------------------------------------------------ */
    :root{
        --primary:#00215E;
        --accent:#e04500;
        --muted:#666;
        --card-bg:#ffffff;
        --shadow: 0 4px 16px rgba(0,0,0,0.18);
        --radius:20px;
    }

    /* --- BACKGROUND DARI PROFILE PERUSAHAAN --- */
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial;
        background: url('{{ asset($bgPath) }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        position: relative;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }

    /* optional overlay untuk keterbacaan (non-intrusive) */
    body::before{
        content:'';
        position: absolute;
        inset:0;
        z-index: 1;
        /* background: rgba(0,0,0,0.12); */ /* uncomment kalau mau gelapkan sedikit */
    }

    /* sembunyikan nav/header/footer global saja (jika layout menampilkannya) */
    header, nav, .navbar, .site-header, .main-nav, .topbar, .app-header,
    footer, .site-footer, .app-footer, .footer {
        display: none !important;
    }

    /* Tombol kembali ke riwayat */
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

    /* Grid wrapper: 3 kartu per baris pada layar besar */
    .tickets-wrapper{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        max-width: 1200px;
        margin: 12px auto 40px;
        padding: 0 12px;
        align-items: start;
        position: relative;
        z-index: 10;
    }

    /* ticket card */
    .ticket-card{
        width: 100%;
        margin: 0;
        background: var(--card-bg);
        border-radius: var(--radius);
        padding: 18px;
        box-shadow: var(--shadow);
        position: relative;
        z-index: 10;
        box-sizing: border-box;
        min-height: 420px;
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
    }

    @media print{
        .ticket-card{ page-break-after: always; box-shadow:none; border-radius:0; margin:0; padding:8px; }
        .tickets-wrapper{ display:block; }
    }

    .ticket-logo{ width: 82px; display:block; margin: 0 auto 6px; }
    h1.ticket-title{ margin:0; text-align:center; font-size:20px; color:var(--primary); font-weight:700; letter-spacing:0.3px; }
    .ticket-id{ text-align:center; font-size:15px; font-weight:700; color:var(--accent); margin-top:6px; }
    .dashed-divider{ border-top:1px dashed #ccc; margin:12px 0; }
    .qr-wrap{ text-align:center; margin:10px 0; }
    .qr-wrap img{ width:140px; height:140px; object-fit:cover; background:white; padding:6px; border-radius:8px; display:inline-block; }
    .kode-pemesanan{ text-align:center; margin-top:8px; font-weight:700; color:var(--accent); font-size:13px; }
    .route-row{ display:flex; justify-content:space-between; align-items:center; margin-top:12px; gap:12px; }
    .route-city{ width:48%; font-weight:700; font-size:16px; color:var(--primary); text-transform:uppercase; }
    .subinfo{ color:var(--muted); font-size:12px; margin-top:6px; text-align:center; }
    .info-columns{ display:flex; justify-content:space-between; gap:12px; margin-top:10px; }
    .info-col{ width:48%; }
    .label{ font-size:13px; color:#777; }
    .value{ margin-top:6px; font-size:14px; font-weight:700; color:#102a43; }
    .section-title{ margin-top:12px; font-size:15px; font-weight:700; color:var(--primary); }
    .data-row{ display:flex; margin-top:6px; }
    .data-row .left{ width:45%; color:#444; font-weight:600; }
    .data-row .right{ width:55%; color:#222; font-weight:700; }
    ul.instructions{ margin:6px 0 0 18px; color:#444; font-size:13px; padding:0; }
    ul.instructions li{ margin-bottom:6px; }
    .total-box{ text-align:center; margin-top:10px; }

    .actions{ display:flex; gap:10px; justify-content:center; margin-top:auto; }
    .btn{ padding:8px 12px; border-radius:8px; border:0; cursor:pointer; font-weight:700; font-size:13px; }
    .btn-print{ background:var(--primary); color:#fff; }
    .btn-download{ background:#10b981; color:#fff; }
    .btn-share{ background:#8b5cf6; color:#fff; }

    @media (max-width:1100px){
        .tickets-wrapper{ grid-template-columns: repeat(2, 1fr); max-width: 960px; }
        .qr-wrap img{ width:130px; height:130px; }
    }

    @media (max-width:640px){
        .tickets-wrapper{ grid-template-columns: 1fr; padding: 12px; }
        .qr-wrap img{ width:120px; height:120px; }
        .route-city{ font-size:14px; }
    }
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    // pastikan $pemesanan tersedia
    if (!isset($pemesanan)) {
        echo '<div style="max-width:760px;margin:40px auto;background:#fff;padding:20px;border-radius:12px;text-align:center;">E-Ticket tidak ditemukan.</div>';
        return;
    }

    // dasar kode booking
    $kode = $pemesanan->kode_booking ?? ('TKT-'.date('Ymd').'-'.str_pad($pemesanan->id ?? 0,3,'0',STR_PAD_LEFT));

    // origin/destination
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

    $tanggal_display = $pemesanan->tanggal_formatted ?? (isset($pemesanan->jadwal->tanggal_keberangkatan) ? Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY') : '-');
    $waktu_display = $pemesanan->waktu_formatted ?? (isset($pemesanan->jadwal->waktu_keberangkatan) ? Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i').' WIB' : '-');

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
            return 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='.urlencode('SMARTSHUTTLE:'.$ticketCode.':CHECKIN');
        }
    }

    // back url
    $backUrl = url()->previous() ?: (Route::has('customer.riwayat') ? route('customer.riwayat') : url('/riwayat'));
@endphp

<div style="max-width:1200px;margin:12px auto;padding:0 12px;">
    <a class="back-btn" href="{{ $backUrl }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> <span>Kembali ke Riwayat</span>
    </a>
</div>

<div class="tickets-wrapper" aria-live="polite">
    @if(isset($pemesanan->detailPenumpang) && $pemesanan->detailPenumpang->count()>0)
        @foreach($pemesanan->detailPenumpang as $dp)
            @php
                $ticketKode = $kode . '-P' . str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                $qr_src = buildQrSrc($pemesanan, $ticketKode);
                $penumpang_nama = $dp->nama_lengkap ?? $dp->nama ?? $nama_pemesan;
                $penumpang_telepon = $dp->telepon ?? $telepon_pemesan;
                $penumpang_nik = $dp->nik ?? null;
                $kursi = $dp->nomor_kursi ?? '01';
            @endphp

            <main class="ticket-card" role="article" aria-labelledby="ticket-title-{{ $loop->iteration }}">
                @if(file_exists(public_path('images/smartshuttlelogo.png')))
                    <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle" class="ticket-logo" />
                @else
                    <div style="text-align:center;font-weight:800;color:var(--primary);margin-bottom:6px;">SMART SHUTTLE</div>
                @endif

                <h1 id="ticket-title-{{ $loop->iteration }}" class="ticket-title">E-TICKET</h1>
                <div class="ticket-id" aria-hidden="true">{{ $ticketKode }}</div>

                <div class="dashed-divider" aria-hidden="true"></div>

                <div class="qr-wrap" role="img" aria-label="QR Code Check-in (penumpang {{ $loop->iteration }})">
                    <img src="{{ $qr_src }}" alt="QR Code untuk check-in (kode: {{ $ticketKode }})" loading="lazy" />
                </div>

                <div class="kode-pemesanan">Kode Pemesanan : {{ $kode }}</div>

                <div class="dashed-divider" aria-hidden="true"></div>

                <section aria-labelledby="route-heading-{{ $loop->iteration }}" class="route">
                    <div class="route-row">
                        <div class="route-city">{{ strtoupper($origin) }}</div>
                        <div class="route-city" style="text-align:right;">{{ strtoupper($destination) }}</div>
                    </div>

                    <div class="subinfo" id="route-heading-{{ $loop->iteration }}">{{ $tanggal_display }} | {{ $waktu_display }}</div>
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
                    <button class="btn btn-print" type="button" onclick="window.print();" aria-label="Cetak tiket (Ctrl+P)">
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
                <div style="text-align:center;font-weight:800;color:var(--primary);margin-bottom:6px;">SMART SHUTTLE</div>
            @endif

            <h1 id="ticket-title" class="ticket-title">E-TICKET</h1>
            <div class="ticket-id" aria-hidden="true">{{ $ticketKode }}</div>

            <div class="dashed-divider" aria-hidden="true"></div>

            <div class="qr-wrap" role="img" aria-label="QR Code Check-in">
                <img src="{{ $qr_src }}" alt="QR Code untuk check-in (kode: {{ $ticketKode }})" loading="lazy" />
            </div>

            <div class="kode-pemesanan">Kode Pemesanan : {{ $kode }}</div>

            <div class="dashed-divider" aria-hidden="true"></div>

            <section aria-labelledby="route-heading" class="route">
                <div class="route-row">
                    <div class="route-city">{{ strtoupper($origin) }}</div>
                    <div class="route-city" style="text-align:right;">{{ strtoupper($destination) }}</div>
                </div>

                <div class="subinfo" id="route-heading">{{ $tanggal_display }} | {{ $waktu_display }}</div>
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
                <div class="right"><span>: {{ $jumlah_penumpang }}</span></div>
            </div>

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
                <button class="btn btn-print" type="button" onclick="window.print();" aria-label="Cetak tiket (Ctrl+P)">
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
@endsection

@push('scripts')
<!-- Font Awesome CDN (untuk ikon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
    // Shortcuts keyboard: Ctrl+P cetak, Ctrl+S download
    document.addEventListener('keydown', function(e){
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
            e.preventDefault();
            const link = document.querySelector('.btn-download');
            if (link) window.location.href = link.href;
        }
    });

    // Share API (jika tersedia) — delegasi event untuk beberapa tombol share (per-tiket)
    document.addEventListener('click', async function(e){
        if (!e.target) return;
        const btn = e.target.closest('[id^="shareBtn"]');
        if (!btn) return;

        if (navigator.share) {
            try {
                await navigator.share({
                    title: 'E-Ticket Smart Shuttle',
                    text: btn.closest('.ticket-card') ? btn.closest('.ticket-card').querySelector('.ticket-id').textContent.trim() : 'E-Ticket Smart Shuttle',
                    url: window.location.href
                });
            } catch (err) {
                // user canceled
            }
        } else {
            try {
                await navigator.clipboard.writeText(window.location.href);
                alert('Link tiket disalin ke clipboard.');
            } catch (e) {
                alert('Fitur bagikan tidak tersedia di browser ini.');
            }
        }
    });
</script>
@endpush
