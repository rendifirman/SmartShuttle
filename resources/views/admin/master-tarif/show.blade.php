@extends('layouts.app-admin')

@section('title', 'Detail Master Tarif')

@push('styles')
<style>
body {
    background: #f4f6fb;
}

.page-container {
    padding: 25px;
    min-height: 100vh;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.btn-back {
    background: #f0f0f0;
    color: #0b2a4a;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #e0e0e0;
}

.page-header h2 {
    color: #0b2a4a;
    font-size: 24px;
    margin: 0;
    font-weight: 700;
}

.detail-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
}

.detail-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}

.detail-section {
    margin-bottom: 25px;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-section h4 {
    color: #0b2a4a;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-section h4 i {
    color: #1e88e5;
    font-size: 16px;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 15px;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 11px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.detail-value {
    font-size: 15px;
    color: #0b2a4a;
    font-weight: 600;
}

.detail-value.text-muted {
    color: #999;
    font-weight: 400;
}

.detail-value.text-success {
    color: #2e7d32;
}

.detail-value.text-danger {
    color: #c62828;
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.badge-aktif {
    background: #c8e6c9;
    color: #2e7d32;
}

.badge-tidak-aktif {
    background: #ffccbc;
    color: #d84315;
}

.badge-jenis {
    background: #e3f2fd;
    color: #1565c0;
    text-transform: capitalize;
}

/* Sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}

.sidebar-card h5 {
    color: #0b2a4a;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sidebar-card h5 i {
    color: #1e88e5;
    font-size: 16px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 13px;
    color: #999;
}

.stat-value {
    font-size: 16px;
    font-weight: 700;
    color: #0b2a4a;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 15px;
}

.btn-action {
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-edit {
    background: #bbdefb;
    color: #0d47a1;
}

.btn-edit:hover {
    background: #90caf9;
}

.btn-delete {
    background: #ffccbc;
    color: #d84315;
}

.btn-delete:hover {
    background: #ffab91;
}

.btn-back-link {
    background: #f0f0f0;
    color: #0b2a4a;
    text-decoration: none;
}

.btn-back-link:hover {
    background: #e0e0e0;
}

/* Description List */
.description-list {
    display: grid;
    gap: 15px;
}

.description-item {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.description-item:last-child {
    border-bottom: none;
}

.description-label {
    font-size: 12px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 35%;
    min-width: 120px;
}

.description-value {
    flex: 1;
    color: #0b2a4a;
    font-size: 14px;
}

.description-value.text-muted {
    color: #999;
}

@media (max-width: 967px) {
    .detail-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .detail-row {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: row;
    }

    .btn-action {
        flex: 1;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.master-tarif.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2>Detail Master Tarif</h2>
    </div>

    <!-- Main Content -->
    <div class="detail-container">
        <!-- Main Card -->
        <div class="detail-card">
            <!-- Informasi Dasar -->
            <div class="detail-section">
                <h4><i class="fas fa-info-circle"></i> Informasi Dasar</h4>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Kode Tarif</span>
                        <span class="detail-value">{{ $tarif->kode_tarif }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nama Tarif</span>
                        <span class="detail-value">{{ $tarif->nama_tarif }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Jenis Tarif</span>
                        <span class="badge badge-jenis">{{ $tarif->jenis_tarif }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">SK Tarif</span>
                        <span class="detail-value {{ !$tarif->sk_tarif ? 'text-muted' : '' }}">
                            {{ $tarif->sk_tarif ?? '-' }}
                        </span>
                    </div>
                </div>
                @if($tarif->keterangan)
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Keterangan</span>
                            <span class="detail-value">{{ $tarif->keterangan }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Harga dan Diskon -->
            <div class="detail-section">
                <h4><i class="fas fa-money-bill"></i> Harga dan Diskon</h4>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Harga Dasar</span>
                        <span class="detail-value">Rp {{ number_format($tarif->harga_dasar, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Harga Minimum</span>
                        <span class="detail-value">Rp {{ number_format($tarif->harga_minimum, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Harga Maksimum</span>
                        <span class="detail-value {{ !$tarif->harga_maksimum ? 'text-muted' : '' }}">
                            {{ $tarif->harga_maksimum ? 'Rp ' . number_format($tarif->harga_maksimum, 0, ',', '.') : 'Tidak Dibatasi' }}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Diskon Persentase</span>
                        <span class="detail-value">{{ $tarif->diskon_persentase }}%</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Diskon Nominal</span>
                        <span class="detail-value">
                            {{ $tarif->diskon_nominal > 0 ? 'Rp ' . number_format($tarif->diskon_nominal, 0, ',', '.') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Periode Berlaku -->
            <div class="detail-section">
                <h4><i class="fas fa-calendar"></i> Periode Berlaku</h4>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Berlaku Dari</span>
                        <span class="detail-value {{ !$tarif->tanggal_berlaku ? 'text-muted' : '' }}">
                            {{ $tarif->tanggal_berlaku ? $tarif->tanggal_berlaku->format('d F Y') : 'Tidak Ditentukan' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Berlaku Hingga</span>
                        <span class="detail-value {{ !$tarif->tanggal_kadaluarsa ? 'text-muted' : '' }}">
                            {{ $tarif->tanggal_kadaluarsa ? $tarif->tanggal_kadaluarsa->format('d F Y') : 'Tidak Ditentukan' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($tarif->catatan)
                <!-- Catatan -->
                <div class="detail-section">
                    <h4><i class="fas fa-sticky-note"></i> Catatan</h4>
                    <div style="padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #1e88e5; color: #0b2a4a; font-size: 13px; line-height: 1.6;">
                        {{ $tarif->catatan }}
                    </div>
                </div>
            @endif

            <!-- Audit Info -->
            <div class="detail-section">
                <h4><i class="fas fa-history"></i> Informasi Audit</h4>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Dibuat Oleh</span>
                        <span class="detail-value text-muted">{{ $tarif->created_by ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Dibuat</span>
                        <span class="detail-value text-muted">{{ $tarif->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Diubah Oleh</span>
                        <span class="detail-value text-muted">{{ $tarif->updated_by ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Diubah</span>
                        <span class="detail-value text-muted">{{ $tarif->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Status Card -->
            <div class="sidebar-card">
                <h5><i class="fas fa-toggle-on"></i> Status</h5>
                <div style="text-align: center;">
                    @if($tarif->status === 'aktif')
                        <span class="badge badge-aktif" style="font-size: 12px; padding: 8px 16px;">Aktif</span>
                    @else
                        <span class="badge badge-tidak-aktif" style="font-size: 12px; padding: 8px 16px;">Tidak Aktif</span>
                    @endif
                </div>
            </div>

            <!-- Penggunaan Card -->
            <div class="sidebar-card">
                <h5><i class="fas fa-chart-bar"></i> Penggunaan</h5>
                <div class="stat-item">
                    <span class="stat-label">Digunakan di Rute</span>
                    <span class="stat-value">{{ $relatedRutes }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Digunakan di Jadwal</span>
                    <span class="stat-value">{{ $relatedJadwals }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="sidebar-card">
                <h5><i class="fas fa-cogs"></i> Aksi</h5>
                <div class="action-buttons">
                    <a href="{{ route('admin.master-tarif.edit', $tarif->id) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.master-tarif.index') }}" class="btn-action btn-back-link">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
