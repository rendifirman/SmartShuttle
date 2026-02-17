@extends('layouts.app-admin')

@section('title', 'Data Penumpang Jadwal')

@push('styles')
<style>
    :root {
        --bg-primary: #f8f7f3;
        --bg-secondary: #ffffff;
        --bg-card: #ffffff;
        --text-primary: #0b2a4a;
        --text-secondary: #333333;
        --text-muted: #777777;
        --border-color: #dddddd;
        --shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        --shadow-light: 0 2px 8px rgba(0, 0, 0, 0.05);
        --primary-color: #ff6a00;
        --secondary-color: #1e88e5;
        --success-color: #12b600;
        --warning-color: #f9b000;
        --danger-color: #e74c3c;
        --info-color: #6c757d;
    }

    body {
        background: #f4f6fb;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        overflow-x: hidden;
    }

    .page-container {
        padding: 20px;
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h2 {
        font-size: 22px;
        color: var(--text-primary);
        margin: 0;
        font-weight: 700;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-back {
        background: var(--info-color);
        color: #fff;
    }

    .btn-back:hover {
        background: #5a6268;
    }

    .jadwal-info {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-item span {
        font-size: 16px;
        color: var(--text-primary);
        font-weight: 600;
    }

    .table-wrapper {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 20px;
        box-shadow: var(--shadow);
        overflow-x: auto;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    thead {
        background: rgba(0, 0, 0, 0.05);
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
        font-size: 13px;
        color: var(--text-primary);
    }

    td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-secondary);
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: rgba(255, 106, 0, 0.05);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 16px;
    }

    .passenger-detail {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .passenger-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .passenger-email {
        font-size: 12px;
        color: var(--text-muted);
    }

    .passenger-phone {
        font-size: 12px;
        color: var(--text-muted);
    }

    .btn-action {
        padding: 6px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        margin-right: 5px;
        transition: all 0.3s;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-detail {
        background: var(--secondary-color);
        color: #fff;
    }

    .btn-detail:hover {
        background: #1565c0;
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 15px;
        }

        .jadwal-info {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 10px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- ================= HEADER ================= -->
    <div class="page-header">
        <h2>Data Penumpang Jadwal</h2>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- ================= JADWAL INFO ================= -->
    <div class="jadwal-info">
        <div class="info-item">
            <label>Rute</label>
            <span>
                @if($jadwal->rutes->isNotEmpty())
                    {{ $jadwal->rutes->first()->kota_asal }} → {{ $jadwal->rutes->first()->kota_tujuan }}
                @else
                    Rute tidak ditemukan
                @endif
            </span>
        </div>
        <div class="info-item">
            <label>Tanggal Keberangkatan</label>
            <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d M Y') }}</span>
        </div>
        <div class="info-item">
            <label>Waktu Keberangkatan</label>
            <span>{{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_kedatangan)->format('H:i') }}</span>
        </div>
        <div class="info-item">
            <label>Armada</label>
            <span>{{ $jadwal->shuttle->nama_shuttle ?? '-' }} ({{ $jadwal->shuttle->plat_nomor ?? '-' }})</span>
        </div>
        <div class="info-item">
            <label>Total Penumpang</label>
            <span>{{ $pemesanan->sum('jumlah_penumpang') }} orang</span>
        </div>
        <div class="info-item">
            <label>Total Pemesanan</label>
            <span>{{ $pemesanan->count() }} pemesanan</span>
        </div>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="table-wrapper">
        @if($pemesanan->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada data penumpang untuk jadwal ini.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Booking</th>
                        <th>Nama Pemesan</th>
                        <th>Jumlah Penumpang</th>
                        <th>Status</th>
                        <th>Status Pembayaran</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemesanan as $pesan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $pesan->kode_booking }}</strong>
                            </td>
                            <td>
                                <div class="passenger-detail">
                                    <span class="passenger-name">{{ $pesan->nama_pemesan }}</span>
                                    <span class="passenger-email">{{ $pesan->email_pemesan }}</span>
                                    <span class="passenger-phone">{{ $pesan->telepon_pemesan }}</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $pesan->jumlah_penumpang }}</strong> orang
                            </td>
                            <td>
                                @php
                                    $statusClass = 'status-pending';
                                    if ($pesan->status == 'confirmed') {
                                        $statusClass = 'status-confirmed';
                                    } elseif ($pesan->status == 'completed') {
                                        $statusClass = 'status-completed';
                                    } elseif ($pesan->status == 'cancelled') {
                                        $statusClass = 'status-cancelled';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $pesan->status)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $paymentStatusClass = 'status-pending';
                                    if ($pesan->status_pembayaran == 'paid') {
                                        $paymentStatusClass = 'status-completed';
                                    } elseif ($pesan->status_pembayaran == 'failed') {
                                        $paymentStatusClass = 'status-cancelled';
                                    }
                                @endphp
                                <span class="status-badge {{ $paymentStatusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $pesan->status_pembayaran ?? 'pending')) }}
                                </span>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($pesan->total_bayar ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('admin.jadwal.index') }}" class="btn-action btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($pemesanan->isNotEmpty())
        <!-- ================= DETAIL PENUMPANG ================= -->
        <div class="table-wrapper">
            <h3 style="margin-top: 0; color: var(--text-primary);">
                <i class="fas fa-users" style="margin-right: 10px;"></i> Rincian Detail Penumpang
            </h3>

            @foreach($pemesanan as $pesan)
                <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
                    <h4 style="margin-top: 0; margin-bottom: 15px; color: var(--primary-color);">
                        {{ $pesan->kode_booking }} - {{ $pesan->nama_pemesan }}
                    </h4>

                    @if($pesan->detailPenumpang->isEmpty())
                        <p style="color: var(--text-muted);">Tidak ada detail penumpang.</p>
                    @else
                        <table style="margin: 0;">
                            <thead>
                                <tr style="background: rgba(255, 106, 0, 0.1);">
                                    <th>No</th>
                                    <th>Nama Penumpang</th>
                                    <th>Nomor Identitas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nomor Kursi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesan->detailPenumpang as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->nama_lengkap }}</td>
                                        <td>{{ $detail->nik ?? '-' }}</td>
                                        <td>
                                            @if($detail->jenis_kelamin == 'L')
                                                Laki-laki
                                            @elseif($detail->jenis_kelamin == 'P')
                                                Perempuan
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $detail->nomor_kursi ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
