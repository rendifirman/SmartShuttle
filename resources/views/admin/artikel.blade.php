@extends('layouts.app-admin')

@section('title', 'Data Artikel')

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

/* Artikel Thumbnail */
.artikel-thumbnail {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
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

.status-publik {
    background: #b8f0a3;
    color: #1e7e34;
}

.status-draft {
    background: #ffd89a;
    color: #856404;
}

/* Kategori Badge */
.kategori-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    background: #e3f2fd;
    color: #1565c0;
    display: inline-block;
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

/* File Upload */
.file-upload {
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s;
}

.file-upload:hover {
    border-color: #1e88e5;
}

.file-upload input {
    display: none;
}

.file-upload label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    color: #666;
}

.file-upload label i {
    font-size: 32px;
    color: #1e88e5;
}

.thumbnail-preview {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 15px;
    display: none;
}

/* Rich Text Editor */
.editor-toolbar {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-bottom: none;
    border-radius: 6px 6px 0 0;
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.editor-btn {
    background: white;
    border: 1px solid #ddd;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s;
}

.editor-btn:hover {
    background: #f8f9fa;
    border-color: #1e88e5;
}

.editor-content {
    min-height: 300px;
    border: 1px solid #ddd;
    border-radius: 0 0 6px 6px;
    padding: 15px;
    font-size: 14px;
    line-height: 1.6;
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

.detail-content {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #1e88e5;
}

.detail-content p {
    margin-bottom: 10px;
    line-height: 1.6;
}

.detail-content h3 {
    color: #0b2a4a;
    margin-top: 20px;
    margin-bottom: 10px;
    font-size: 16px;
}

.thumbnail-large {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
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
    text-decoration: none;
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
            <h2>Data Artikel</h2>
            @can('manage_artikel')
            <a href="{{ route('admin.artikel.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Artikel
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
            <div class="summary-card artikel-total">
                <h3>{{ $totalArtikel }}</h3>
                <p>Total Artikel</p>
            </div>
            <div class="summary-card artikel-publik">
                <h3>{{ $artikelAktif }}</h3>
                <p>Artikel Publik</p>
            </div>
            <div class="summary-card artikel-draft">
                <h3>{{ $artikelDraft }}</h3>
                <p>Artikel Draft</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <form method="GET" action="{{ route('admin.artikel.index') }}">
                <div class="filter-row">
                    <select name="kategori" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select name="penulis" id="filter-penulis">
                        <option value="">Semua Penulis</option>
                        @foreach($penulisList as $penulis)
                            <option value="{{ $penulis }}" {{ request('penulis') == $penulis ? 'selected' : '' }}>
                                {{ $penulis }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" id="filter-status">
                        <option value="">Semua Status</option>
                        <option value="publik" {{ request('status') == 'publik' ? 'selected' : '' }}>Publik</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>

                    <input type="date" name="tanggal_dari" id="filter-tanggal-dari"
                           value="{{ request('tanggal_dari') }}" placeholder="Tanggal Dari">
                </div>

                <div class="filter-row">
                    <input type="date" name="tanggal_sampai" id="filter-tanggal-sampai"
                           value="{{ request('tanggal_sampai') }}" placeholder="Tanggal Sampai">

                    <div style="grid-column: span 3;">
                        <!-- Empty for alignment -->
                    </div>
                </div>

                <div class="filter-action">
                    <input type="text" name="search" id="search-artikel"
                           placeholder="Cari judul, konten, atau penulis..." value="{{ request('search') }}">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.artikel.index') }}" class="btn-cancel"
                       style="padding: 12px 20px; text-decoration: none; display: inline-flex; align-items: center;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <button class="btn-excel" onclick="exportExcel()">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportPDF()">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tanggal Publikasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="artikel-table-body">
                    @forelse($artikels as $artikel)
                    <tr>
                        <td>
                            @if($artikel->thumbnail)
                                <img src="{{ Storage::url($artikel->thumbnail) }}"
                                     alt="Thumbnail" class="artikel-thumbnail">
                            @else
                                <img src="{{ asset('images/default-thumbnail.jpg') }}"
                                     alt="No Thumbnail" class="artikel-thumbnail">
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($artikel->judul, 50) }}</strong><br>
                            <small style="color: #666;">{{ Str::limit(strip_tags($artikel->konten), 70) }}</small>
                        </td>
                        <td>
                            <span class="kategori-badge">{{ $artikel->kategori }}</span>
                        </td>
                        <td>{{ $artikel->penulis }}</td>
                        <td>
                            {{ $artikel->tanggal_publikasi ? $artikel->tanggal_publikasi->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <span class="status-badge status-{{ $artikel->status }}">
                                {{ $artikel->status == 'publik' ? 'Publik' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.artikel.show', $artikel->id) }}"
                               class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @can('manage_artikel')
                            <a href="{{ route('admin.artikel.edit', $artikel->id) }}"
                               class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn-action btn-delete"
                                    onclick="confirmDelete({{ $artikel->id }}, '{{ $artikel->judul }}')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px;">
                            <p style="color: #666;">Tidak ada data artikel ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="pagination">
                {{ $artikels->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="modal hidden"
     style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 400px; width: 90%;">
        <h3 style="margin-top: 0; color: #0b2a4a;">Konfirmasi Hapus</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus artikel ini?</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button id="confirmDelete" class="btn-save" style="background: #dc3545;">
                <i class="fas fa-trash"></i> Ya, Hapus
            </button>
            <button onclick="closeDeleteModal()" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
            </button>
        </div>
    </div>
</div>

<script>
// Variables
let deleteUrl = '';
let currentArtikelId = '';

// Function to confirm delete
function confirmDelete(id, judul) {
    deleteUrl = `{{ url('admin/artikel') }}/${id}`;
    document.getElementById('deleteMessage').textContent =
        `Apakah Anda yakin ingin menghapus artikel "${judul}"?`;
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
        // Create form for deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method spoofing for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
});

// Export functions
function exportExcel() {
    // Get filter parameters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');

    window.open(`{{ route('admin.artikel.index') }}?${params.toString()}`, '_blank');
}

function exportPDF() {
    // Get filter parameters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');

    window.open(`{{ route('admin.artikel.index') }}?${params.toString()}`, '_blank');
}

// Search with Enter
document.getElementById('search-artikel').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        this.closest('form').submit();
    }
});

// Initialize date pickers
document.addEventListener('DOMContentLoaded', function() {
    console.log('Artikel management page loaded');

    // Set today as default for "Tanggal Sampai"
    const tanggalSampai = document.getElementById('filter-tanggal-sampai');
    if (tanggalSampai && !tanggalSampai.value) {
        const today = new Date().toISOString().split('T')[0];
        tanggalSampai.value = today;
    }
});
</script>
@endsection
