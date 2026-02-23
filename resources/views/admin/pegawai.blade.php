@extends('layouts.app-admin')

@section('title', 'Master Data - Pegawai')

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
    grid-template-columns: repeat(4, 1fr);
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
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
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
.btn-export {
    background: #eee;
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

/* Posisi Badges */
.posisi-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.posisi-admin {
    background: #e3f2fd;
    color: #1565c0;
}
.posisi-driver {
    background: #e8f5e9;
    color: #2e7d32;
}
.posisi-manager {
    background: #fff3e0;
    color: #ef6c00;
}
.posisi-other {
    background: #f3e5f5;
    color: #7b1fa2;
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
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-delete:hover {
    background: #c82333;
}

/* Role Badge */
.role-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-right: 4px;
    text-align: center;
}
.role-badge.role-admin-pusat {
    background-color: #ff6a00;
    color: white;
}
.role-badge.role-admin-cabang {
    background-color: #ffc107;
    color: #333;
}
.role-badge.role-driver {
    background-color: #28a745;
    color: white;
}

/* Status Badge */
.badge-active {
    background: #d4edda;
    color: #155724;
}
.badge-inactive {
    background: #f8d7da;
    color: #721c24;
}

/* Delete Form */
.delete-form {
    display: inline;
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

/* ================= ALERTS ================= */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
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
.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
.alert i {
    font-size: 18px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 992px) {
    .summary {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .filter-top {
        grid-template-columns: repeat(2, 1fr);
    }

    .table-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    table {
        display: block;
        overflow-x: auto;
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

/* SweetAlert Custom Styles */
.swal2-popup {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-radius: 12px;
}
.swal2-title {
    font-size: 20px !important;
    color: #333 !important;
}
.swal2-html-container {
    font-size: 15px !important;
    color: #666 !important;
}
.btn-confirm-delete {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
}
.btn-cancel-delete {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
}
.swal2-icon {
    border-width: 3px !important;
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= LIST PAGE ================= -->
    <div id="list-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Master Data - Pegawai</h2>
            <a href="{{ route('admin.pegawai.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Pegawai
            </a>
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
                <h3>{{ $totalPegawai ?? '0' }}</h3>
                <p>Total Pegawai</p>
            </div>
            <div class="summary-card">
                <h3>{{ $aktifPegawai ?? '0' }}</h3>
                <p>Aktif</p>
            </div>
            <div class="summary-card">
                <h3>{{ $nonAktifPegawai ?? '0' }}</h3>
                <p>Non-Aktif</p>
            </div>
            <div class="summary-card">
                <h3>{{ $adminPusat ?? '0' }}</h3>
                <p>Admin Pusat</p>
            </div>
        </div>

        <!-- FILTER -->
        <form id="filterForm" method="GET" action="{{ route('admin.pegawai') }}" class="filter-box">
            @csrf
            <div class="filter-top">
                <select name="posisi" id="filterPosisi">
                    <option value="">Pilih Posisi</option>
                    <option value="Admin Pusat" {{ request('posisi') == 'Admin Pusat' ? 'selected' : '' }}>Admin Pusat</option>
                    <option value="Admin Cabang" {{ request('posisi') == 'Admin Cabang' ? 'selected' : '' }}>Admin Cabang</option>
                    <option value="Driver" {{ request('posisi') == 'Driver' ? 'selected' : '' }}>Driver</option>
                    <option value="Manager" {{ request('posisi') == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Staff" {{ request('posisi') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Supervisor" {{ request('posisi') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                </select>
                <select name="tahun_bergabung" id="filterTahun">
                    <option value="">Pilih Tahun Bergabung</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ request('tahun_bergabung') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
                <select name="status" id="filterStatus">
                    <option value="">Pilih Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                <button type="submit" class="btn-filter" id="filterButton">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="filter-bottom">
                <input type="text" name="search" id="searchInput"
                       placeholder="Cari nama pegawai, NIK, posisi..."
                       value="{{ request('search') }}">
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <div>
                    <!-- TOMBOL EXPORT -->
                    <button class="btn-excel" onclick="showComingSoon('Excel')">
                        <i class="fas fa-file-excel"></i> X | Excel
                    </button>
                    <button class="btn-pdf" onclick="showComingSoon('PDF')">
                        <i class="fas fa-file-pdf"></i> M | PDF
                    </button>
                    <button class="btn-export" onclick="showExportOptions()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <div class="pagination-info">
                    @if(isset($pegawais) && $pegawais->count() > 0)
                        Menampilkan {{ $pegawais->firstItem() }}-{{ $pegawais->lastItem() }} dari {{ $pegawais->total() }} data
                    @else
                        Menampilkan 0 dari 0 data
                    @endif
                </div>
            </div>

            @if(isset($pegawais) && $pegawais->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telepon</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pegawais as $pegawai)
                        <tr>
                            <td>{{ $loop->iteration + (($pegawais->currentPage() - 1) * $pegawais->perPage()) }}</td>
                            <td>
                                <strong>{{ $pegawai->name }}</strong>
                                @if($pegawai->tanggal_lahir)
                                    <br><small style="color: #999;">{{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $pegawai->email }}</td>
                            <td>{{ $pegawai->phone ?? '-' }}</td>
                            <td>{{ $pegawai->nik ?? '-' }}</td>
                            <td>
                                @foreach($pegawai->roles as $role)
                                    @php
                                        $roleClass = str_replace('_', '-', $role->name);
                                    @endphp
                                    <span class="role-badge role-{{ $roleClass }}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($pegawai->branch)
                                    {{ $pegawai->branch->nama_cabang }}
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $pegawai->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $pegawai->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pegawai.show', $pegawai->id) }}" class="btn-action btn-view" title="View">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @can('manage_pegawai')
                                <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endcan

                                <!-- FORM DELETE dengan konfirmasi SweetAlert -->
                                <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}"
                                      method="POST"
                                      class="delete-form"
                                      onsubmit="return confirmDelete(event, '{{ addslashes($pegawai->name) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada data pegawai
                </div>
            @endif

                <!-- PAGINATION -->
                @if($pegawais->hasPages())
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if($pegawais->onFirstPage())
                            <button disabled><i class="fas fa-chevron-left"></i></button>
                        @else
                            <form method="GET" action="{{ route('admin.pegawai') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="page" value="{{ $pegawais->currentPage() - 1 }}">
                                <button type="submit"><i class="fas fa-chevron-left"></i></button>
                            </form>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $pegawais->currentPage();
                            $lastPage = $pegawais->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($startPage > 1)
                            <form method="GET" action="{{ route('admin.pegawai') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="page" value="1">
                                <button type="submit" class="{{ 1 == $currentPage ? 'active' : '' }}">1</button>
                            </form>
                            @if($startPage > 2)
                                <span>...</span>
                            @endif
                        @endif

                        @for($i = $startPage; $i <= $endPage; $i++)
                            <form method="GET" action="{{ route('admin.pegawai') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="page" value="{{ $i }}">
                                <button type="submit" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</button>
                            </form>
                        @endfor

                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span>...</span>
                            @endif
                            <form method="GET" action="{{ route('admin.pegawai') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="page" value="{{ $lastPage }}">
                                <button type="submit" class="{{ $lastPage == $currentPage ? 'active' : '' }}">{{ $lastPage }}</button>
                            </form>
                        @endif

                        {{-- Next Page Link --}}
                        @if($pegawais->hasMorePages())
                            <form method="GET" action="{{ route('admin.pegawai') }}" style="display:inline;">
                                @foreach(request()->except('page') as $name => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="page" value="{{ $pegawais->currentPage() + 1 }}">
                                <button type="submit"><i class="fas fa-chevron-right"></i></button>
                            </form>
                        @else
                            <button disabled><i class="fas fa-chevron-right"></i></button>
                        @endif
                    </div>
                @endif
        </div>

    </div>

</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ====================== FUNGSI DELETE ======================
function confirmDelete(event, nama) {
    // Mencegah form submit langsung
    event.preventDefault();

    // Gunakan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Hapus Pegawai?',
        html: `<div style="text-align: center;">
                  <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                  <p>Anda akan menghapus pegawai: <strong>${nama}</strong></p>
                  <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, submit form
            event.target.submit();
        }
    });

    return false; // Mencegah submit default
}

// ====================== FUNGSI EXPORT ======================
function showComingSoon(format) {
    Swal.fire({
        title: 'Fitur Dalam Pengembangan',
        html: `<div style="text-align: center;">
                  <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                  <p>Export ke <strong>${format}</strong> sedang dalam pengembangan</p>
                  <p class="text-muted">Fitur ini akan segera hadir dalam update berikutnya</p>
               </div>`,
        icon: 'info',
        confirmButtonText: 'Mengerti',
        confirmButtonColor: '#3085d6',
        showCancelButton: false
    });
}

function showExportOptions() {
    Swal.fire({
        title: 'Export Data Pegawai',
        html: `<div style="text-align: center;">
                  <i class="fas fa-download fa-3x text-success mb-3"></i>
                  <p>Pilih format export:</p>
                  <div class="mt-4" style="display: flex; gap: 10px; justify-content: center;">
                    <button id="export-excel-btn" class="btn-excel" style="padding: 10px 20px;">
                      <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button id="export-pdf-btn" class="btn-pdf" style="padding: 10px 20px;">
                      <i class="fas fa-file-pdf"></i> PDF
                    </button>
                  </div>
               </div>`,
        icon: 'question',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        cancelButtonColor: '#6c757d',
        didOpen: () => {
            document.getElementById('export-excel-btn').addEventListener('click', () => {
                Swal.close();
                showComingSoon('Excel');
            });

            document.getElementById('export-pdf-btn').addEventListener('click', () => {
                Swal.close();
                showComingSoon('PDF');
            });
        }
    });
}

// ====================== FILTER FUNGSI ======================
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});

// ====================== NOTIFIKASI SUCCESS/ERROR ======================
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
@endif
</script>
@endsection
