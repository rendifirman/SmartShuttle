@extends('layouts.app-admin')

@section('title', 'Detail Armada')

@push('styles')
<style>
.page-container {
    padding: 24px 30px;
    background: #f8f7f3;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.page-header h2 {
    font-size: 22px;
    color: #0b2a4a;
    margin: 0;
}
.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-edit {
    background: #f9b000;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.detail-container {
    display: grid;
    gap: 20px;
}

.detail-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}

.detail-title {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 15px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 8px;
    color: #0b2a4a;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.detail-item label {
    font-size: 12px;
    color: #777;
    display: block;
    margin-bottom: 5px;
}

.detail-item span {
    font-weight: 600;
    font-size: 13px;
    color: #333;
}

.detail-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.badge-check {
    background: #f4f6fb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    display: inline-flex;
    gap: 6px;
    align-items: center;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}
.status-active {
    background: #b8f0a3;
    color: #1e7e34;
}
.status-inactive {
    background: #ff9a9a;
    color: #8b0000;
}
.status-service {
    background: #ffd699;
    color: #b35900;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .detail-grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Detail Armada</h2>
        <div style="display: flex; gap: 10px;">
            <button class="btn-back" onclick="window.location.href='{{ route('admin.armada') }}'">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <button class="btn-edit" onclick="window.location.href='{{ route('admin.armada.edit', $shuttle->id) }}'">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>
    </div>

    <!-- DETAIL CONTENT -->
    <div class="detail-container">
        <!-- DATA KENDARAAN -->
        <div class="detail-card">
            <div class="detail-title">Data Kendaraan</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Kode Armada</label>
                    <span>{{ $shuttle->kode }}</span>
                </div>
                <div class="detail-item">
                    <label>Nama Shuttle</label>
                    <span>{{ $shuttle->nama_shuttle }}</span>
                </div>
                <div class="detail-item">
                    <label>Layanan</label>
                    <span>{{ $shuttle->layanan->nama_layanan ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Merk</label>
                    <span>{{ $shuttle->merk }}</span>
                </div>
                <div class="detail-item">
                    <label>Model</label>
                    <span>{{ $shuttle->model }}</span>
                </div>
                <div class="detail-item">
                    <label>Tipe</label>
                    <span>{{ $shuttle->tipe_shuttle }}</span>
                </div>
                <div class="detail-item">
                    <label>Tahun</label>
                    <span>{{ $shuttle->tahun }}</span>
                </div>
                <div class="detail-item">
                    <label>Warna</label>
                    <span>{{ $shuttle->warna }}</span>
                </div>
                <div class="detail-item">
                    <label>No Polisi</label>
                    <span>{{ $shuttle->nomor_polisi }}</span>
                </div>
                <div class="detail-item">
                    <label>Kapasitas Kursi</label>
                    <span>{{ $shuttle->kapasitas_kursi }} kursi</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <span class="status-badge @if($shuttle->status == 'aktif') status-active @elseif($shuttle->status == 'tidak-aktif') status-inactive @else status-service @endif">
                        {{ ucfirst($shuttle->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- LEGALITAS -->
        <div class="detail-card">
            <div class="detail-title">Legalitas & Asuransi</div>
            <div class="detail-grid-2">
                <div class="detail-item">
                    <label>Nomor STNK</label>
                    <span>{{ $shuttle->no_stnk ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Masa Berlaku STNK</label>
                    <span>{{ $shuttle->masa_stnk ? \Carbon\Carbon::parse($shuttle->masa_stnk)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Nomor KIR</label>
                    <span>{{ $shuttle->no_kir ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Masa Berlaku KIR</label>
                    <span>{{ $shuttle->masa_kir ? \Carbon\Carbon::parse($shuttle->masa_kir)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- OWNERSHIP -->
        <div class="detail-card">
            <div class="detail-title">Ownership</div>
            <div class="detail-grid-2">
                <div class="detail-item">
                    <label>Jenis Kepemilikan</label>
                    <span>{{ ucfirst(str_replace('-', ' ', $shuttle->jenis_kepemilikan)) }}</span>
                </div>
                <div class="detail-item">
                    <label>Nama Pemilik/Vendor</label>
                    <span>{{ $shuttle->nama_pemilik ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Nilai Asset</label>
                    <span>{{ $shuttle->nilai_asset ? 'Rp ' . number_format($shuttle->nilai_asset, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Tanggal Masuk Operasi</label>
                    <span>{{ $shuttle->tanggal_masuk ? \Carbon\Carbon::parse($shuttle->tanggal_masuk)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- FASILITAS -->
        <div class="detail-card">
            <div class="detail-title">Fasilitas</div>
            <div class="detail-grid">
                <div class="detail-item" style="grid-column: span 3;">
                    <span>{{ $shuttle->fasilitas ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- KELENGKAPAN -->
        @if($shuttle->kelengkapan && is_array($shuttle->kelengkapan) && count($shuttle->kelengkapan) > 0)
        <div class="detail-card">
            <div class="detail-title">Kelengkapan & Perlengkapan</div>
            <div class="detail-grid">
                @foreach($shuttle->kelengkapan as $item)
                <div class="badge-check">
                    <i class="fas {{ $item['checked'] ?? false ? 'fa-check-circle' : 'fa-times-circle' }}"
                       style="color: {{ $item['checked'] ?? false ? '#28a745' : '#dc3545' }};"></i>
                    {{ $item['name'] }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- LAYOUT KURSI -->
        <div class="detail-card">
            <div class="detail-title">Layout Kursi</div>
            <div class="detail-grid">
                <div class="detail-item" style="grid-column: span 3;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                        @php
                            $layout = $shuttle->layout_kursi_array;
                        @endphp
                        @foreach($layout as $kursi)
                        <div style="
                            width: 60px;
                            height: 60px;
                            background: #e9ecef;
                            border-radius: 8px;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            font-size: 11px;
                            color: #333;
                            border: 1px solid #ddd;
                        ">
                            <div>{{ $kursi['nomor'] }}</div>
                            <div style="font-size: 9px; color: #666;">{{ $kursi['posisi'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
