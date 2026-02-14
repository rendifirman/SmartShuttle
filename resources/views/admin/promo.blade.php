@extends('layouts.app-admin')

@section('title', 'Data Promo')

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

.status-aktif {
    background: #b8f0a3;
    color: #1e7e34;
}

.status-nonaktif {
    background: #ff9a9a;
    color: #8b0000;
}

.status-expired {
    background: #ffd8a3;
    color: #8b5700;
}

/* Kategori Badges */
.kategori-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.kategori-umum {
    background: #a3d8ff;
    color: #0056b3;
}

.kategori-keluarga {
    background: #ffa3d1;
    color: #8b0053;
}

.kategori-membership {
    background: #d8a3ff;
    color: #5e008b;
}

/* Tipe Badges */
.tipe-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.tipe-all {
    background: #a3ffb8;
    color: #008b1e;
}

.tipe-shuttle {
    background: #a3e0ff;
    color: #00608b;
}

.tipe-paket {
    background: #ffd8a3;
    color: #8b5e00;
}

.tipe-sewa {
    background: #ffa3a3;
    color: #8b0000;
}

/* Discount Display */
.discount-value {
    font-weight: 600;
    color: #0b2a4a;
}

.discount-type {
    font-size: 11px;
    color: #777;
    display: block;
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

.form-group .checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: normal;
    cursor: pointer;
}

.form-group .checkbox-label input[type="checkbox"] {
    width: auto;
    transform: scale(1.2);
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

/* Image Preview */
.image-preview {
    max-width: 200px;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px solid #ddd;
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

/* ================= MODAL ================= */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
    .summary {
        grid-template-columns: repeat(2, 1fr);
    }
}

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
        padding: 5px 10px;
        font-size: 11px;
        margin-bottom: 5px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
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
            <h2>Data Promo</h2>
            @can('manage_promo')
            <a href="{{ route('admin.promo.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Promo
            </a>
            @endcan
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
            <div class="summary-card promo-total">
                <h3>{{ $totalPromo }}</h3>
                <p>Total Promo</p>
            </div>
            <div class="summary-card promo-aktif">
                <h3>{{ $activePromo }}</h3>
                <p>Promo Aktif</p>
            </div>
            <div class="summary-card promo-berjalan">
                <h3>{{ $ongoingPromo }}</h3>
                <p>Sedang Berjalan</p>
            </div>
            <div class="summary-card promo-expired">
                <h3>{{ $expiredPromo }}</h3>
                <p>Promo Expired</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <form method="GET" action="{{ route('admin.promo') }}" id="filterForm">
                <div class="filter-row">
                    <select name="kategori_promo" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        <option value="umum" {{ request('kategori_promo') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="keluarga" {{ request('kategori_promo') == 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="membership" {{ request('kategori_promo') == 'membership' ? 'selected' : '' }}>Membership</option>
                    </select>

                    <select name="tipe_promo" id="filter-tipe">
                        <option value="">Semua Tipe</option>
                        <option value="all" {{ request('tipe_promo') == 'all' ? 'selected' : '' }}>All (Semua)</option>
                        <option value="shuttle" {{ request('tipe_promo') == 'shuttle' ? 'selected' : '' }}>Shuttle</option>
                        <option value="paket" {{ request('tipe_promo') == 'paket' ? 'selected' : '' }}>Paket</option>
                        <option value="sewa" {{ request('tipe_promo') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                    </select>

                    <select name="jenis_diskon" id="filter-diskon">
                        <option value="">Jenis Diskon</option>
                        <option value="persentase" {{ request('jenis_diskon') == 'persentase' ? 'selected' : '' }}>Persentase</option>
                        <option value="nominal" {{ request('jenis_diskon') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                    </select>

                    <select name="status" id="filter-status">
                        <option value="">Status Promo</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <div class="filter-action">
                    <input type="text" name="search" id="search-promo" placeholder="Cari Kode, Nama Promo" value="{{ request('search') }}">
                    <button type="submit" class="btn-filter">Filter</button>
                    <a href="{{ route('admin.promo') }}" class="btn-cancel" style="padding: 12px 20px; text-decoration: none; display: inline-flex; align-items: center;">
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
                        <th>Kode Promo</th>
                        <th>Nama Promo</th>
                        <th>Diskon</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="promo-table-body">
                    @forelse($promos as $promo)
                    <tr>
                        <td>
                            <strong>{{ $promo->kode_promo }}</strong>
                            @if($promo->khusus_member)
                                <br><small class="discount-type">Hanya Member</small>
                            @endif
                        </td>
                        <td>{{ $promo->nama_promo }}</td>
                        <td>
                            <span class="discount-value">
                                @if($promo->jenis_diskon == 'persentase')
                                    {{ $promo->nilai_diskon }}%
                                @else
                                    Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                @endif
                            </span>
                            <small class="discount-type">
                                @if($promo->jenis_diskon == 'persentase')
                                    Maks: Rp {{ number_format($promo->maksimal_diskon, 0, ',', '.') }}
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="kategori-badge kategori-{{ $promo->kategori_promo }}">
                                {{ ucfirst($promo->kategori_promo) }}
                            </span>
                        </td>
                        <td>
                            <span class="tipe-badge tipe-{{ $promo->tipe_promo }}">
                                {{ ucfirst($promo->tipe_promo) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($promo->tanggal_berakhir)->format('d/m/Y') }}</td>
                        <td>
                            @if($promo->kuota)
                                {{ $promo->terpakai }} / {{ $promo->kuota }}
                            @else
                                Unlimited
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'status-nonaktif';
                                $statusText = 'Nonaktif';

                                if($promo->status) {
                                    $now = now();
                                    $startDate = \Carbon\Carbon::parse($promo->tanggal_mulai);
                                    $endDate = \Carbon\Carbon::parse($promo->tanggal_berakhir);

                                    if($now->between($startDate, $endDate)) {
                                        $statusClass = 'status-aktif';
                                        $statusText = 'Aktif';
                                    } else if($now->gt($endDate)) {
                                        $statusClass = 'status-expired';
                                        $statusText = 'Expired';
                                    }
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.promo.show', $promo->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @can('manage_promo')
                            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus promo ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 30px;">
                            <p style="color: #666;">Tidak ada data promo ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="pagination">
                {{ $promos->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="modal hidden">
    <div class="modal-content">
        <h3 style="margin-top: 0; color: #0b2a4a;">Konfirmasi Hapus</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus promo ini?</p>
        <div class="modal-actions">
            <button id="confirmDelete" class="btn-save" style="background: #dc3545;">Ya, Hapus</button>
            <button onclick="closeDeleteModal()" class="btn-cancel">Batal</button>
        </div>
    </div>
</div>

<script>
// Variables
let deleteUrl = '';

// Function to confirm delete
function confirmDelete(id, kodePromo) {
    deleteUrl = `{{ url('admin/promo') }}/${id}`;
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus promo "${kodePromo}"?`;
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
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Promo berhasil dihapus!');
                location.reload();
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Gagal menghapus promo');
                });
            }
        })
        .catch(error => {
            alert(error.message);
            closeDeleteModal();
        });
    }
});

// Export functions
function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.open(`{{ route('admin.promo') }}?${params.toString()}`, '_blank');
}

function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.open(`{{ route('admin.promo') }}?${params.toString()}`, '_blank');
}

// Search with Enter
document.getElementById('search-promo').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('filterForm').submit();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Promo management page loaded');
});
</script>
@endsection
