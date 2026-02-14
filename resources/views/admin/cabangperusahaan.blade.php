@extends('layouts.app-admin')

@section('title', 'Master Data - Cabang')

@push('styles')
<style>
/* ================= BASE ================= */
.page-container {
    padding: 24px 30px;
    background: #f8f7f3;
    min-height: 100vh;
}

/* ================= HEADER ================= */
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
.btn-add {
    background: #1e88e5;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-add:hover {
    background: #0d7dd8;
}

/* ================= SUMMARY ================= */
.summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}
.summary-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.summary-card h3 {
    margin: 0;
    font-size: 24px;
    color: #0b2a4a;
}
.summary-card p {
    margin: 5px 0 0;
    color: #777;
    font-size: 13px;
}

/* ================= FILTER ================= */
.filter-box {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.filter-top {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}
.filter-box select {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    background: white;
}
.filter-bottom {
    display: flex;
    gap: 15px;
}
.filter-bottom input {
    flex: 1;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
}
.btn-filter {
    background: #ff6a00;
    color: #fff;
    padding: 12px 30px;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    white-space: nowrap;
}
.btn-filter:hover {
    background: #e55c00;
}

/* ================= BUTTONS ================= */
.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

/* ================= TABLE ================= */
.table-wrapper {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    overflow-x: auto;
    margin-bottom: 20px;
}
.table-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}
.btn-excel {
    background: #12b600;
    color: #fff;
    padding: 8px 18px;
    border-radius: 20px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-pdf {
    background: #ddd;
    color: #333;
    padding: 8px 18px;
    border-radius: 20px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
thead {
    background: #f1f1f1;
}
th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #ddd;
    font-size: 13px;
}
td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}
tbody tr:hover {
    background-color: #f9f9f9;
}

/* Status Badges */
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

/* Action Buttons */
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
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-view {
    background: #888;
    color: #fff;
}
.btn-view:hover {
    background: #777;
}
.btn-edit {
    background: #f9b000;
    color: #fff;
}
.btn-edit:hover {
    background: #e09b00;
}
.btn-delete {
    background: #dc3545;
    color: #fff;
}
.btn-delete:hover {
    background: #c82333;
}

/* ================= PAGINATION ================= */
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.pagination button,
.pagination .page-btn {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    min-width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.pagination button.active,
.pagination .page-btn.active {
    background: #0b2a4a;
    color: white;
    border-color: #0b2a4a;
}
.pagination-info {
    font-size: 13px;
    color: #666;
    margin-left: 15px;
}

/* ================= NO DATA ================= */
.no-data {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.no-data i {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}
.no-data h3 {
    margin: 0 0 10px 0;
    color: #666;
}
.no-data p {
    color: #999;
    margin: 0;
}

/* ================= FORM CARD ================= */
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.form-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 12px;
    font-size: 20px;
    color: #0b2a4a;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    font-size: 14px;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
    color: #333;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
}
textarea {
    resize: none;
    min-height: 80px;
}

/* ================= FORM ACTIONS ================= */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.btn-save {
    background: #0b2a4a;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-save:hover {
    background: #1a3a5f;
}
.btn-reset {
    background: #ff6a00;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-reset:hover {
    background: #e55c00;
}
.btn-cancel {
    background: #6c757d;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-cancel:hover {
    background: #5a6268;
}

/* ================= ALERTS ================= */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ================= UTILITIES ================= */
.hidden {
    display: none !important;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .summary {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-top {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-save,
    .btn-reset,
    .btn-cancel {
        width: 100%;
        text-align: center;
    }

    .table-actions {
        flex-wrap: wrap;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .summary {
        grid-template-columns: 1fr;
    }

    .filter-top {
        grid-template-columns: 1fr;
    }

    .filter-bottom {
        flex-direction: column;
    }

    .filter-bottom input {
        width: 100%;
    }

    .btn-filter {
        width: 100%;
    }

    .page-container {
        padding: 15px;
    }

    .btn-action {
        padding: 5px 10px;
        font-size: 11px;
        margin-bottom: 5px;
    }

    .pagination {
        gap: 5px;
    }

    .pagination button,
    .pagination .page-btn {
        min-width: 35px;
        height: 35px;
        padding: 5px 8px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= LIST PAGE ================= -->
    <div id="list-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Master Data - Cabang Perusahaan</h2>
            @can('manage_cabang')
            <a href="{{ route('admin.cabang.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Cabang
            </a>
            @endcan
        </div>

        <!-- Display success/error messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-card">
                <h3>{{ $totalBranches }}</h3>
                <p>Total Cabang</p>
            </div>
            <div class="summary-card">
                <h3>{{ $activeBranches }}</h3>
                <p>Cabang Aktif</p>
            </div>
            <div class="summary-card">
                <h3>{{ $inactiveBranches }}</h3>
                <p>Cabang Non-Aktif</p>
            </div>
        </div>

        <!-- FILTER -->
        <form id="filterForm" method="GET" action="{{ route('admin.cabangperusahaan') }}" class="filter-box">
            @csrf
            <div class="filter-top">
                <select name="kota" id="filterKota">
                    <option value="">Pilih Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('kota') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
                <select name="nama" id="filterNama">
                    <option value="">Pilih Nama Cabang</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->nama_cabang }}" {{ request('nama') == $branch->nama_cabang ? 'selected' : '' }}>
                            {{ $branch->nama_cabang }}
                        </option>
                    @endforeach
                </select>
                <select name="kode" id="filterKode">
                    <option value="">Pilih Kode</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->kode_cabang }}" {{ request('kode') == $branch->kode_cabang ? 'selected' : '' }}>
                            {{ $branch->kode_cabang }}
                        </option>
                    @endforeach
                </select>
                <select name="status" id="filterStatus">
                    <option value="">Pilih Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="filter-bottom">
                <input type="text" name="search" id="searchInput"
                       placeholder="Cari cabang berdasarkan kota, nama, kode, atau status..."
                       value="{{ request('search') }}">
                <button type="button" class="btn-filter" id="filterButton">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <button class="btn-excel">
                    <i class="fas fa-file-excel"></i> X | Excel
                </button>
                <button class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> V | PDF
                </button>
                <div class="pagination-info">
                    Menampilkan {{ $branches->firstItem() ?: 0 }}-{{ $branches->lastItem() ?: 0 }} dari {{ $branches->total() }} data
                </div>
            </div>

            @if($branches->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Cabang</th>
                            <th>Kota</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Koordinat GPS</th>
                            <th>Jam Operasional</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                        <tr>
                            <td><strong>{{ $branch->kode_cabang }}</strong></td>
                            <td>{{ $branch->nama_cabang }}</td>
                            <td>{{ $branch->kota }}</td>
                            <td title="{{ $branch->alamat }}">{{ Str::limit($branch->alamat, 50) }}</td>
                            <td>{{ $branch->telepon }}</td>
                            <td>{{ $branch->email }}</td>
                            <td>{{ $branch->koordinat_gps ?? '-' }}</td>
                            <td>{{ $branch->jam_operasional }}</td>
                            <td>
                                <span class="status-badge {{ $branch->status == 'aktif' ? 'status-active' : 'status-inactive' }}">
                                    {{ $branch->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}
                                </span>
                            </td>
                            <td>
                                @can('manage_cabang')
                                <a href="{{ route('admin.cabang.edit', $branch->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cabang.destroy', $branch->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- PAGINATION -->
                @if($branches->hasPages())
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if($branches->onFirstPage())
                            <button disabled><i class="fas fa-chevron-left"></i></button>
                        @else
                            <form method="GET" action="{{ route('admin.cabangperusahaan') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="page" value="{{ $branches->currentPage() - 1 }}">
                                <button type="submit"><i class="fas fa-chevron-left"></i></button>
                            </form>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $branches->currentPage();
                            $lastPage = $branches->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($startPage > 1)
                            <form method="GET" action="{{ route('admin.cabangperusahaan') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="page" value="1">
                                <button type="submit" class="{{ 1 == $currentPage ? 'active' : '' }}">1</button>
                            </form>
                            @if($startPage > 2)
                                <span>...</span>
                            @endif
                        @endif

                        @for($i = $startPage; $i <= $endPage; $i++)
                            <form method="GET" action="{{ route('admin.cabangperusahaan') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="page" value="{{ $i }}">
                                <button type="submit" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</button>
                            </form>
                        @endfor

                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span>...</span>
                            @endif
                            <form method="GET" action="{{ route('admin.cabangperusahaan') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="page" value="{{ $lastPage }}">
                                <button type="submit" class="{{ $lastPage == $currentPage ? 'active' : '' }}">{{ $lastPage }}</button>
                            </form>
                        @endif

                        {{-- Next Page Link --}}
                        @if($branches->hasMorePages())
                            <form method="GET" action="{{ route('admin.cabangperusahaan') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="page" value="{{ $branches->currentPage() + 1 }}">
                                <button type="submit"><i class="fas fa-chevron-right"></i></button>
                            </form>
                        @else
                            <button disabled><i class="fas fa-chevron-right"></i></button>
                        @endif
                    </div>
                @endif

            @else
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <h3>Tidak ada data cabang ditemukan</h3>
                    <p>Silakan tambahkan cabang baru untuk memulai.</p>
                </div>
            @endif
        </div>

    </div>

</div>

<script>
// Delete confirmation with better UX
function confirmDelete(event) {
    event.preventDefault();
    const form = event.target.closest('form');

    Swal.fire({
        title: 'Hapus Cabang?',
        text: "Data cabang akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// Filter functionality
document.getElementById('filterButton').addEventListener('click', function() {
    document.getElementById('filterForm').submit();
});

// Enter key in search input
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});

// Reset filter form
function resetFilter() {
    document.getElementById('filterForm').reset();
    document.getElementById('filterForm').submit();
}

// SweetAlert for notifications
@if(session('success') || session('error'))
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
});
@endif
</script>

<!-- SweetAlert2 -->
@if(session('success') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endif

@endsection
