@extends('layouts.app-admin')

@section('title', 'Detail Rute')

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
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s;
}
.btn-back:hover {
    background: #5a6268;
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
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s;
}
.btn-edit:hover {
    background: #e09c00;
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
    display: block;
    word-break: break-word;
}

.detail-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.detail-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
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
.status-aktif {
    background: #b8f0a3;
    color: #1e7e34;
}
.status-tidak-aktif {
    background: #ff9a9a;
    color: #8b0000;
}

.stop-card {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid #ff6a00;
}

.stop-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.stop-number {
    background: #ff6a00;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
.stop-city {
    font-weight: 600;
    color: #0b2a4a;
    font-size: 14px;
}
.stop-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.stop-item label {
    font-size: 11px;
    color: #777;
    margin-bottom: 3px;
}
.stop-item span {
    font-size: 12px;
    font-weight: 500;
    color: #333;
}

.info-badge {
    background: #f4f6fb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

@media (max-width: 992px) {
    .detail-grid, .detail-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    .stop-details {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    .page-header h2 {
        font-size: 18px;
    }
    .header-actions {
        display: flex;
        gap: 10px;
        width: 100%;
    }
    .header-actions .btn-back,
    .header-actions .btn-edit {
        flex: 1;
        justify-content: center;
    }
    .detail-grid, .detail-grid-2, .detail-grid-4, .stop-details {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Detail Rute</h2>
        <div class="header-actions">
            <a href="{{ route('admin.rute.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('admin.rute.edit', $rute->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit Rute
            </a>
        </div>
    </div>

    <!-- DETAIL CONTENT -->
    <div class="detail-container">
        <!-- DATA RUTE -->
        <div class="detail-card">
            <div class="detail-title">Data Rute</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Kode Rute</label>
                    <span>{{ $rute->kode_rute }}</span>
                </div>
                <div class="detail-item">
                    <label>Nama Rute</label>
                    <span>{{ $rute->nama_rute }}</span>
                </div>
                <div class="detail-item">
                    <label>Layanan</label>
                    <span>{{ $rute->layanan->nama_layanan ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <span class="status-badge status-{{ $rute->status }}">
                        {{ $rute->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- RUTE UTAMA -->
        <div class="detail-card">
            <div class="detail-title">Rute Utama</div>
            <div class="detail-grid-4">
                <div class="detail-item">
                    <label>Kota Asal</label>
                    <span>{{ $rute->kota_asal }}</span>
                </div>
                <div class="detail-item">
                    <label>Kota Tujuan</label>
                    <span>{{ $rute->kota_tujuan }}</span>
                </div>
                <div class="detail-item">
                    <label>Durasi</label>
                    <span>{{ $rute->formatted_durasi }}</span>
                </div>
                <div class="detail-item">
                    <label>Jarak</label>
                    <span>{{ number_format($rute->jarak, 0, ',', '.') }} km</span>
                </div>
                <div class="detail-item">
                    <label>Harga Dasar</label>
                    <span>Rp {{ number_format($rute->harga_dasar, 0, ',', '.') }}</span>
                </div>
                @if($rute->harga_premium)
                <div class="detail-item">
                    <label>Harga Premium</label>
                    <span>Rp {{ number_format($rute->harga_premium, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- TARIF YANG BERLAKU -->
        <div class="detail-card">
            <div class="detail-title">Tarif yang Berlaku</div>
            @if($rute->masterTarifs && $rute->masterTarifs->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                    @foreach($rute->masterTarifs as $tarif)
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid #ff6a00;">
                        <div style="font-weight: 600; color: #0b2a4a; margin-bottom: 8px;">{{ $tarif->nama_tarif }}</div>
                        <div style="font-size: 12px; color: #666; margin-bottom: 8px;">
                            <strong>Kode:</strong> {{ $tarif->kode_tarif }}<br>
                            <strong>Jenis:</strong> {{ $tarif->jenis_tarif }}<br>
                            <strong>Harga Dasar:</strong> Rp {{ number_format($tarif->harga_dasar, 0, ',', '.') }}<br>
                            @if($tarif->diskon_persentase > 0 || $tarif->diskon_nominal > 0)
                            <strong>Diskon:</strong>
                                @if($tarif->diskon_persentase > 0){{ $tarif->diskon_persentase }}%@endif
                                @if($tarif->diskon_nominal > 0)Rp {{ number_format($tarif->diskon_nominal, 0, ',', '.') }}@endif<br>
                            @endif
                            <strong>Status:</strong> <span class="status-badge status-{{ $tarif->status }}">{{ $tarif->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; color: #666; text-align: center;">
                    <em>Tidak ada tarif yang dipilih untuk rute ini</em>
                </div>
            @endif
        </div>

        <!-- RUTE PEMBERHENTIAN -->
        @php
            $pemberhentian = $rute->rute_pemberhentian ?? [];

            // Ensure pemberhentian is an array. Some records may store it as JSON string.
            if (is_string($pemberhentian)) {
                $decoded = json_decode($pemberhentian, true);
                $pemberhentian = is_array($decoded) ? $decoded : [];
            }
        @endphp
        @if(!empty($pemberhentian) && is_array($pemberhentian) && count($pemberhentian) > 0)
        <div class="detail-card">
            <div class="detail-title">Rute Pemberhentian</div>
            <div style="display: grid; gap: 12px;">
                @foreach($pemberhentian as $index => $stop)
                <div class="stop-card">
                    <div class="stop-header">
                        <div class="stop-number">{{ $index + 1 }}</div>
                        <div class="stop-city">{{ $stop['kota'] ?? '-' }}</div>
                    </div>
                    <div class="stop-details">
                        <div class="stop-item">
                            <label>Outlets</label>
                            <span>
                                @if(!empty($stop['outlets']) && is_array($stop['outlets']))
                                    {{ implode(', ', $stop['outlets']) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="stop-item">
                            <label>Durasi Singgah</label>
                            <span>{{ $stop['durasi_singgah'] ?? 0 }} menit</span>
                        </div>
                        @if(isset($stop['keterangan']) && $stop['keterangan'])
                        <div class="stop-item">
                            <label>Keterangan</label>
                            <span>{{ $stop['keterangan'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="detail-card">
            <div class="detail-title">Rute Pemberhentian</div>
            <div style="text-align: center; padding: 20px; color: #777;">
                <i class="fas fa-map-marker-alt" style="font-size: 24px; margin-bottom: 10px;"></i>
                <p>Tidak ada pemberhentian dalam rute ini</p>
            </div>
        </div>
        @endif

        <!-- INFORMASI TAMBAHAN -->
        <div class="detail-card">
            <div class="detail-title">Informasi Tambahan</div>
            <div class="detail-grid-2">
                <div class="detail-item">
                    <label>Dibuat Pada</label>
                    <div class="info-badge">
                        <i class="far fa-calendar-plus"></i>
                        {{ $rute->created_at->format('d M Y H:i') }}
                    </div>
                </div>
                <div class="detail-item">
                    <label>Diperbarui Pada</label>
                    <div class="info-badge">
                        <i class="far fa-calendar-check"></i>
                        {{ $rute->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
            @if($rute->keterangan)
            <div class="detail-item" style="margin-top: 15px;">
                <label>Keterangan</label>
                <div style="background: #f9f9f9; padding: 12px; border-radius: 8px; margin-top: 5px;">
                    <span>{{ $rute->keterangan }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
