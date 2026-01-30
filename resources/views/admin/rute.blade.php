@extends('layouts.app-admin')

@section('title', 'Data Rute')

@push('styles')
<style>
/* ================= BASE ================= */
body {
    background: #f4f6fb;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
}

.page-container {
    padding: 25px;
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
    color: #0b2a4a;
    font-size: 22px;
    margin: 0;
    font-weight: 600;
}

.btn-add {
    background: #1e88e5;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
}

.btn-add:hover {
    background: #0d74d1;
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

.filter-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.filter-box select,
.filter-box input {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    width: 100%;
}

.filter-action {
    display: flex;
    gap: 15px;
}

.filter-action input {
    flex: 1;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.btn-filter {
    background: #ff6a00;
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-filter:hover {
    background: #e55c00;
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
    border-radius: 20px;
    padding: 8px 18px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 13px;
}

.btn-pdf {
    background: #ddd;
    color: #333;
    border-radius: 20px;
    padding: 8px 18px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 13px;
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

/* Untuk tombol delete di form */
.btn-action[style*="background: #dc3545"]:hover {
    background: #c82333 !important;
}

/* Container untuk aksi agar lebih rapi */
td:last-child {
    min-width: 180px;
}

/* ================= PAGINATION ================= */
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.pagination button {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
}

.pagination button.active {
    background: #0b2a4a;
    color: white;
    border-color: #0b2a4a;
}

.pagination-info {
    font-size: 13px;
    color: #666;
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
    resize: vertical;
    min-height: 100px;
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

/* ================= DETAIL CARD ================= */
.detail-container {
    display: grid;
    gap: 20px;
    max-width: 1200px;
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

/* ================= UTILITIES ================= */
.hidden {
    display: none !important;
}

.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 20px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back:hover {
    background: #5a6268;
}

/* ================= ALERTS ================= */
.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
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

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .summary {
        grid-template-columns: 1fr;
    }

    .filter-row {
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

    .detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-grid-2 {
        grid-template-columns: 1fr;
    }

    .table-actions {
        flex-wrap: wrap;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    td:last-child {
        min-width: 150px;
    }
    
    .btn-action {
        padding: 5px 10px;
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .filter-row {
        grid-template-columns: 1fr;
    }

    .filter-action {
        flex-direction: column;
    }

    .filter-action input {
        width: 100%;
    }

    .btn-filter {
        width: 100%;
    }

    .page-container {
        padding: 15px;
    }

    .btn-action {
        padding: 4px 8px;
        font-size: 10px;
        margin-bottom: 5px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    td:last-child {
        min-width: 120px;
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
            <h2>Data Rute</h2>
            <a href="{{ route('admin.rute.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Rute
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-card rute-total">
                <h3>{{ $totalRute }}</h3>
                <p>Total Rute</p>
            </div>
            <div class="summary-card rute-aktif">
                <h3>{{ $activeRute }}</h3>
                <p>Rute Aktif</p>
            </div>
            <div class="summary-card rute-inactive">
                <h3>{{ $inactiveRute }}</h3>
                <p>Rute Tidak Aktif</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <form method="GET" action="{{ route('admin.rute') }}">
                <div class="filter-row">
                    <select name="kota_asal" id="filter-kota-asal">
                        <option value="">Kota Asal</option>
                        @foreach($kotaAsalList as $kota)
                            <option value="{{ $kota }}" {{ request('kota_asal') == $kota ? 'selected' : '' }}>
                                {{ $kota }}
                            </option>
                        @endforeach
                    </select>
                    <select name="kota_tujuan" id="filter-kota-tujuan">
                        <option value="">Kota Tujuan</option>
                        @foreach($kotaTujuanList as $kota)
                            <option value="{{ $kota }}" {{ request('kota_tujuan') == $kota ? 'selected' : '' }}>
                                {{ $kota }}
                            </option>
                        @endforeach
                    </select>
                    <select name="layanan_id" id="filter-layanan">
                        <option value="">Pilih Layanan</option>
                        @foreach($layananList as $layanan)
                            <option value="{{ $layanan->id_layanan }}" {{ request('layanan_id') == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" id="filter-status">
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="filter-action">
                    <input type="text" name="search" id="search-rute" placeholder="Cari Kode, Nama Rute, Kota" value="{{ request('search') }}">
                    <button type="submit" class="btn-filter">Filter</button>
                    <a href="{{ route('admin.rute') }}" class="btn-cancel" style="padding: 12px 20px; text-decoration: none; display: inline-flex; align-items: center;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <button class="btn-excel" onclick="exportExcel()">X | Excel</button>
                <button class="btn-pdf" onclick="exportPDF()">M | PDF</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Rute</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Durasi</th>
                        <th>Jarak</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="rute-table-body">
                    @forelse($rutes as $rute)
                    <tr>
                        <td>{{ $rute->kode_rute }}</td>
                        <td>{{ $rute->nama_rute }}</td>
                        <td>{{ $rute->kota_asal }}</td>
                        <td>{{ $rute->kota_tujuan }}</td>
                        <td>{{ $rute->formatted_durasi }}</td>
                        <td>{{ number_format($rute->jarak, 0, ',', '.') }} km</td>
                        <td>Rp {{ number_format($rute->harga_dasar, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-{{ $rute->status == 'aktif' ? 'active' : 'inactive' }}">
                                {{ $rute->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.rute.show', $rute->id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.rute.edit', $rute->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.rute.destroy', $rute->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action" style="background: #dc3545; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; margin: 0; cursor: pointer; font-weight: 600;" onclick="confirmDelete({{ $rute->id }}, '{{ $rute->kode_rute }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px;">
                            <p style="color: #666;">Tidak ada data rute ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="pagination">
                {{ $rutes->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="hidden" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 15px;">
    <div style="background: white; padding: 25px; border-radius: 12px; max-width: 400px; width: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; color: #0b2a4a; font-size: 18px; margin-bottom: 15px;">Konfirmasi Hapus</h3>
        <p id="deleteMessage" style="color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus rute ini?</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button id="confirmDelete" class="btn-action" style="background: #dc3545; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; flex: 1;">
                <i class="fas fa-check"></i> Ya, Hapus
            </button>
            <button onclick="closeDeleteModal()" class="btn-action" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; flex: 1;">
                <i class="fas fa-times"></i> Batal
            </button>
        </div>
    </div>
</div>

<script>
// Variables
let deleteUrl = '';
let currentRuteId = '';

// Function to confirm delete
function confirmDelete(id, kodeRute) {
    deleteUrl = `{{ url('admin/rute') }}/${id}`;
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus rute "${kodeRute}"?`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Function to close delete modal
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteUrl = '';
}

// Function to execute delete
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteUrl) {
        console.log('Deleting from URL:', deleteUrl);
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error('HTTP ' + response.status);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message || 'Rute berhasil dihapus!');
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus rute');
                closeDeleteModal();
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error: ' + error.message);
            closeDeleteModal();
        });
    }
});

// Export functions
function exportExcel() {
    // Get filter parameters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');

    window.open(`{{ route('admin.rute') }}?${params.toString()}`, '_blank');
}

function exportPDF() {
    // Get filter parameters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');

    window.open(`{{ route('admin.rute') }}?${params.toString()}`, '_blank');
}

// Quick filter functions
function applyFilter() {
    document.getElementById('filterForm').submit();
}

// Search with Enter
document.getElementById('search-rute').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilter();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Rute management page loaded');
});
</script>
@endsection
