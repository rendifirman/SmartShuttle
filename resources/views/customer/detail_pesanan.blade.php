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
        background-color: #f0f0f0;
        color: #333;
        line-height: 1.6;
        padding: 0;
    }

    /* CARD */
    .card {
        background: white;
        width: 80%;
        margin: 100px auto 40px;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .dotted-line {
        border-bottom: 2px dashed #bfbfbf;
        margin: 15px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table, th, td {
        border: 1px solid #bfbfbf;
    }

    th, td {
        padding: 12px;
        text-align: left;
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

    .btn-orange:hover {
        background-color: #e54e1a;
    }

    .secondary-button {
        background-color: #f0f0f0;
        color: #333;
        border: none;
        padding: 10px;
        margin-top: 10px;
        border-radius: 8px;
        font-weight: 500;
        width: 100%;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .secondary-button:hover {
        background-color: #e0e0e0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .total-price {
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        margin-top: 15px;
        font-size: 18px;
        padding-top: 15px;
        border-top: 2px solid #e5e5e5;
    }

    .info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    /* Style untuk shuttle info yang lebih rapi */
    .shuttle-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border: 1px solid #dee2e6;
    }

    .shuttle-item {
        text-align: center;
        flex: 1;
        padding: 0 10px;
    }

    .shuttle-label {
        font-size: 14px;
        color: #666;
        display: block;
        margin-bottom: 5px;
    }

    .shuttle-value {
        font-weight: 600;
        font-size: 16px;
        color: #333;
    }

    .shuttle-value.booking-code {
        color: #FF581E;
        font-weight: 700;
    }

    /* Style untuk nomor kursi oranye */
    .seat-number {
        background-color: #FF581E;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 70px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card {
            width: 95%;
            padding: 20px;
            margin: 80px auto 20px;
        }

        table {
            font-size: 14px;
        }

        th, td {
            padding: 8px;
        }

        .shuttle-info {
            flex-direction: column;
            gap: 15px;
        }

        .shuttle-item {
            text-align: left;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .shuttle-item:last-child {
            border-bottom: none;
        }
    }
</style>
@endpush

@section('content')
<!-- CARD CONTENT -->
<div class="card">

    <!-- JUDUL DETAIL PESANAN - DIPERBESAR -->
    <h1 style="font-weight:700; font-size: 32px; margin-bottom: 10px;">DETAIL PESANAN</h1>
    <div class="dotted-line"></div>

    <!-- SHUTTLE INFO - DIUBAH MENJADI LEBIH RAPI -->
    <div class="shuttle-info">
        <div class="shuttle-item">
            <span class="shuttle-label">Kode Booking</span>
            <span class="shuttle-value booking-code">{{ $pemesanan->kode_booking }}</span>
        </div>
        <div class="shuttle-item">
            <span class="shuttle-label">Shuttle</span>
            <span class="shuttle-value">{{ $pemesanan->jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle' }}</span>
        </div>
        <div class="shuttle-item">
            <span class="shuttle-label">Plat Nomor</span>
            <span class="shuttle-value">{{ $pemesanan->jadwal->shuttle->plat_nomor ?? 'B 1234 CD' }}</span>
        </div>
    </div>

    <!-- Kota dan tanggal -->
    <div style="display:flex; justify-content:space-between; margin-top:30px;">
        <div style="font-weight:700; font-size: 28px; text-transform:uppercase;">
            {{ $from ?? 'KOTA ASAL' }}<br>
            <span style="font-weight:400; font-size: 18px; color: #666;">
                @php
                    $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                    $tanggal = $date ?? now();
                    if (!($tanggal instanceof \DateTime)) {
                        $tanggal = new DateTime($tanggal);
                    }

                    $hari = $hariIndo[$tanggal->format('w')];
                    $bulan = $bulanIndo[$tanggal->format('n') - 1];

                    echo $hari . ', ' . $tanggal->format('d') . ' ' . $bulan . ' ' . $tanggal->format('Y') . ' ' . ($time ?? '');
                @endphp
            </span>
        </div>

        <div style="text-align:right; font-weight:700; font-size: 28px; text-transform:uppercase;">
            {{ $to ?? 'KOTA TUJUAN' }}
        </div>
    </div>
    <div class="dotted-line" style="margin-top:30px;"></div>

    <!-- DATA PEMESAN - DIPERBESAR -->
    <h1 style="font-weight:700; font-size: 28px; margin-bottom: 15px;">DATA PEMESAN</h1>

    <div style="display:flex; justify-content:space-between; margin-top:10px;">
        <div style="font-size: 18px;">
            <strong>Nama pemesan</strong><br>
            <span style="font-weight: 600;">{{ $customer_name }}</span>
        </div>

        <div style="text-align:right; font-size: 18px;">
            {{ $customer_phone }}<br>
            {{ $customer_email }}
        </div>
    </div>

    <br>

    <!-- TABEL PENUMPANG DENGAN KOLOM TELEPON -->
    <h2 style="font-weight:600; font-size: 20px; margin-bottom: 10px; color: #333;">DATA PENUMPANG</h2>
    <p style="color: #666; margin-bottom: 10px;">Jumlah penumpang: {{ $pemesanan->jumlah_penumpang }} orang</p>

    <!-- Container untuk tabel agar responsive -->
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nama Penumpang</th>
                    <th style="width:200px;">NIK</th>
                    <th style="width:150px;">Telepon</th>
                    <th style="width:150px;">Nomor Kursi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penumpang as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->nik ?? '-' }}</td>
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
            </tbody>
        </table>
    </div>

    <div class="dotted-line" style="margin-top:30px;"></div>

    <!-- RINCIAN HARGA -->
    <div style="margin-top:20px;">
        <h2 style="font-weight:600; font-size: 24px; margin-bottom: 15px; color: #333;">RINCIAN HARGA</h2>

        <div class="price-row">
            <span>Harga tiket</span>
            <span>Rp {{ number_format($pemesanan->harga_total / $pemesanan->jumlah_penumpang, 0, ',', '.') }}</span>
        </div>

        <div class="price-row">
            <span>Jumlah penumpang</span>
            <span>X {{ $pemesanan->jumlah_penumpang }}</span>
        </div>

        <div class="price-row">
            <span>Sub total</span>
            <span>Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}</span>
        </div>

        @if($pemesanan->diskon > 0)
        <div class="price-row" style="color: #28a745;">
            <span>Diskon voucher</span>
            <span>-Rp {{ number_format($pemesanan->diskon, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-price" style="color: #FF581E;">
            <span>Total harga</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <!-- Action Buttons -->
<div style="margin-top: 30px;">
    @if($pemesanan->status == 'menunggu_pembayaran')
        <a href="{{ route('customer.pembayaran', ['kode_booking' => $pemesanan->kode_booking]) }}"
           class="btn-orange">
            Lanjut Pembayaran
        </a>
    @elseif($pemesanan->status == 'dibayar')
        <a href="{{ route('customer.e_ticket', ['kode_booking' => $pemesanan->kode_booking]) }}"
           class="btn-orange" style="background-color: #28a745;">
            Lihat E-Ticket
        </a>
    @else
        <button class="btn-orange" style="background-color: #6c757d;" disabled>
            Status: {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
        </button>
    @endif
</div>

    <!-- Important Notes -->
    <div class="info-box">
        <h3 style="font-weight:600; color: #d35400; margin-bottom: 8px;">Penting!</h3>
        <ul style="color: #666; padding-left: 20px; font-size: 14px;">
            <li>Pastikan data penumpang sudah sesuai</li>
            <li>Kursi yang dipilih sudah tersimpan</li>
            <li>Lakukan pembayaran dalam waktu 10 menit</li>
            <li>E-ticket akan dikirim setelah pembayaran berhasil</li>
        </ul>
    </div>

</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('error') }}
</div>
@endif

<script>
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
