@extends('layouts.app-admin')

@section('title', 'Master Data - Tarif')

@push('styles')
<style>
/* Base Styling */
body {
    background: #f4f6fb;
    font-family: 'Segoe UI', sans-serif;
}

.page-container {
    padding: 25px;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    color: #0b2a4a;
    font-size: 24px;
    margin: 0;
    font-weight: 700;
}

.btn-add {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
    color: #fff;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
}

.btn-add:hover {
    background: linear-gradient(135deg, #0d74d1 0%, #0a47a3 100%);
    box-shadow: 0 6px 16px rgba(30, 136, 229, 0.4);
    transform: translateY(-2px);
}

/* Summary Cards */
.summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.summary-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    border-left: 4px solid #1e88e5;
    transition: all 0.3s;
}

.summary-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
    transform: translateY(-4px);
}

.summary-card h3 {
    margin: 0;
    font-size: 28px;
    color: #0b2a4a;
    font-weight: 700;
}

.summary-card p {
    margin: 8px 0 0;
    color: #777;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter Section */
.filter-box {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    margin-bottom: 25px;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group select,
.filter-group input {
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
}

.btn-filter {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8f00 100%);
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    background: linear-gradient(135deg, #e55c00 0%, #ff8f00 100%);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3);
}

/* Table Section */
.table-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table thead {
    background: #f8f9fa;
    border-bottom: 2px solid #e0e0e0;
}

table th {
    padding: 14px;
    text-align: left;
    font-weight: 600;
    color: #0b2a4a;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

table td {
    padding: 14px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

table tbody tr:hover {
    background: #f8f9fa;
}

/* Status Badge */
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
}

.btn-edit {
    background: #e3f2fd;
    color: #1e88e5;
}

.btn-edit:hover {
    background: #bbdefb;
    color: #0d47a1;
}

.btn-show {
    background: #f3e5f5;
    color: #7b1fa2;
}

.btn-show:hover {
    background: #e1bee7;
    color: #4a148c;
}

.btn-delete {
    background: #ffebee;
    color: #c62828;
}

.btn-delete:hover {
    background: #ffcdd2;
    color: #b71c1c;
}

.btn-deactivate {
    background: #fff3e0;
    color: #e65100;
}

.btn-deactivate:hover {
    background: #ffe0b2;
    color: #bf360c;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

/* Alert Messages */
.alert {
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #4caf5030;
    border-left: 4px solid #4caf50;
    color: #2e7d32;
}

.alert-danger {
    background: #f4433630;
    border-left: 4px solid #f44336;
    color: #c62828;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
}

@media (max-width: 767px) {
    .summary {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-row {
        grid-template-columns: 1fr;
    }

    table {
        font-size: 12px;
    }

    table th,
    table td {
        padding: 10px;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-tag"></i> Master Tarif</h2>
        </div>
        @can('manage_master_tarif')
        <a href="{{ route('admin.master-tarif.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Tarif
        </a>
        @endcan
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-card">
            <h3>{{ $totalTarif }}</h3>
            <p>Total Tarif</p>
        </div>
        <div class="summary-card">
            <h3>{{ $tarifAktif }}</h3>
            <p>Tarif Aktif</p>
        </div>
        <div class="summary-card">
            <h3>{{ $tarifTidakAktif }}</h3>
            <p>Tidak Aktif</p>
        </div>
        <div class="summary-card">
            <h3>{{ collect($jenisOptions)->count() }}</h3>
            <p>Jenis Tarif</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-box">
        <form action="{{ route('admin.master-tarif.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: flex-end;">
            <div class="filter-group">
                <label>Cari Tarif</label>
                <input type="text" name="search" placeholder="Nama atau Kode Tarif..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Jenis Tarif</label>
                <select name="jenis">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                            {{ ucwords($jenis) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-wrapper">
        @if($tarifs->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Tarif</th>
                        <th>Nama Tarif</th>
                        <th>Jenis</th>
                        <th>SK Tarif</th>
                        <th>Harga Dasar</th>
                        <th>Diskon</th>
                        <th>Status</th>
                        <th>Berlaku Dari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tarifs as $index => $tarif)
                        <tr>
                            <td>{{ ($tarifs->currentPage() - 1) * $tarifs->perPage() + $index + 1 }}</td>
                            <td>
                                <strong>{{ $tarif->kode_tarif }}</strong>
                            </td>
                            <td>{{ $tarif->nama_tarif }}</td>
                            <td>
                                <span class="badge badge-jenis">{{ $tarif->jenis_tarif }}</span>
                            </td>
                            <td>{{ $tarif->sk_tarif ?? '-' }}</td>
                            <td>Rp {{ number_format($tarif->harga_dasar, 0, ',', '.') }}</td>
                            <td>
                                @if($tarif->diskon_persentase > 0 || $tarif->diskon_nominal > 0)
                                    <small>
                                        @if($tarif->diskon_persentase > 0)
                                            {{ $tarif->diskon_persentase }}%
                                        @endif
                                        @if($tarif->diskon_nominal > 0)
                                            Rp {{ number_format($tarif->diskon_nominal, 0, ',', '.') }}
                                        @endif
                                    </small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($tarif->status === 'aktif')
                                    <span class="badge badge-aktif">Aktif</span>
                                @else
                                    <span class="badge badge-tidak-aktif">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                {{ $tarif->tanggal_berlaku ? $tarif->tanggal_berlaku->format('d-m-Y') : '-' }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.master-tarif.show', $tarif->id) }}" class="btn-icon btn-show" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('admin.master-tarif.edit', $tarif->id) }}" class="btn-icon btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @if($tarif->status === 'aktif')
                                        <form action="{{ route('admin.master-tarif.deactivate', $tarif->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-deactivate" title="Nonaktifkan" onclick="return confirm('Nonaktifkan tarif ini?')">
                                                <i class="fas fa-times"></i> Nonaktif
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.master-tarif.activate', $tarif->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="Aktifkan" style="background: #c8e6c9; color: #2e7d32;" onclick="return confirm('Aktifkan tarif ini?')">
                                                <i class="fas fa-check"></i> Aktif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if($tarifs->hasPages())
                <div class="pagination-wrapper">
                    {{ $tarifs->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada data tarif</p>
            </div>
        @endif
    </div>
</div>
@endsection
