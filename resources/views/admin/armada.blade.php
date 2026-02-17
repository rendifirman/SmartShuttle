@extends('layouts.app-admin')

@section('title', 'Data Armada')

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
}

/* ================= SUMMARY - SEPERTI CABANG ================= */
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
.filter-box select,
.filter-box input {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
}
.filter-bottom {
    display: flex;
    gap: 15px;
}
.filter-bottom input {
    flex: 1;
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
}
.btn-edit-profile {
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

/* ================= FORM CARD (SEPERTI CABANG) ================= */
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

/* ================= KELENGKAPAN DINAMIS ================= */
.kelengkapan-container {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    margin-top: 10px;
}

.kelengkapan-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.kelengkapan-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
    background: white;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.kelengkapan-item input[type="checkbox"] {
    margin: 0;
}

.kelengkapan-item label {
    flex: 1;
    margin: 0;
    font-weight: normal;
    font-size: 13px;
}

.remove-kelengkapan {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 4px;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.add-kelengkapan-form {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.add-kelengkapan-form input {
    flex: 1;
    padding: 10px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.btn-add-kelengkapan {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-add-kelengkapan:hover {
    background: #219653;
}

/* ================= FORM ACTIONS (SEPERTI CABANG) ================= */
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
.badge-check {
    background: #f4f6fb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    display: inline-flex;
    gap: 6px;
    align-items: center;
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

    .detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-grid-2 {
        grid-template-columns: 1fr;
    }

    .table-actions {
        flex-wrap: wrap;
    }

    .kelengkapan-list {
        grid-template-columns: 1fr;
    }

    .add-kelengkapan-form {
        flex-direction: column;
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

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
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
            <h2>Data Armada</h2>
            @can('manage_armada')
            <button class="btn-add" onclick="showAddForm()">
                <i class="fas fa-plus"></i> Tambah Armada
            </button>
            @endcan
        </div>

        <!-- SUMMARY - SEPERTI CABANG -->
        <div class="summary">
            <div class="summary-card armada-total">
                <h3>{{ $totalShuttles }}</h3>
                <p>Total Kendaraan</p>
            </div>
            <div class="summary-card armada-aktif">
                <h3>{{ $activeShuttles }}</h3>
                <p>Kendaraan Aktif</p>
            </div>
            <div class="summary-card armada-inactive">
                <h3>{{ $inactiveShuttles }}</h3>
                <p>Kendaraan Tidak Aktif</p>
            </div>
            <div class="summary-card armada-service">
                <h3>{{ $serviceShuttles }}</h3>
                <p>Dalam Perbaikan</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <form method="GET" action="{{ route('admin.armada') }}">
                <div class="filter-top">
                    <select name="merk" id="merkFilter">
                        <option value="">Semua Merk</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ request('merk') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                        @endforeach
                    </select>
                    <select name="tipe" id="tipeFilter">
                        <option value="">Semua Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('tipe') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <select name="warna" id="warnaFilter">
                        <option value="">Semua Warna</option>
                        @foreach($colors as $color)
                            <option value="{{ $color }}" {{ request('warna') == $color ? 'selected' : '' }}>{{ $color }}</option>
                        @endforeach
                    </select>
                    <select name="status" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="servis" {{ request('status') == 'servis' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                </div>
                <div class="filter-bottom">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari kode, merk, model, atau plat nomor...">
                    <button type="submit" class="btn-filter">Terapkan Filter</button>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <button class="btn-excel">X | Excel</button>
                <button class="btn-pdf">M | PDF</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>No Polisi</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Tipe</th>
                        <th>Kapasitas</th>
                        <th>Tahun</th>
                        <th>Warna</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shuttles as $shuttle)
                    <tr>
                        <td>{{ $shuttle->kode }}</td>
                        <td>{{ $shuttle->nomor_polisi }}</td>
                        <td>{{ $shuttle->merk }}</td>
                        <td>{{ $shuttle->model }}</td>
                        <td>{{ $shuttle->tipe_shuttle }}</td>
                        <td>{{ $shuttle->kapasitas_kursi }}</td>
                        <td>{{ $shuttle->tahun }}</td>
                        <td>{{ $shuttle->warna }}</td>
                        <td>
                            @if($shuttle->status == 'aktif')
                                <span class="status-badge status-active">Aktif</span>
                            @elseif($shuttle->status == 'nonaktif')
                                <span class="status-badge status-inactive">Tidak Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Perbaikan</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-action btn-view" onclick="showDetail({{ $shuttle->id }})">View</button>
                            @can('manage_armada')
                            <button class="btn-action btn-edit" onclick="showEditForm({{ $shuttle->id }})">Edit</button>
                            <form method="POST" action="{{ route('admin.armada.destroy', $shuttle->id) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus armada ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" style="background: #dc3545; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; margin-right: 5px; cursor: pointer;">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px;">Tidak ada data armada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $shuttles->appends(request()->query())->links() }}
                <span class="pagination-info">
                    Menampilkan {{ $shuttles->firstItem() }}-{{ $shuttles->lastItem() }} dari {{ $shuttles->total() }} data
                </span>
            </div>
        </div>
    </div>

    <!-- ================= FORM TAMBAH/EDIT ARMADA ================= -->
    <div id="form-page" class="hidden">
        <button class="btn-back" onclick="showList()">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Armada
        </button>

        <div class="form-card">
            <h3 id="form-title">Tambah Data Armada</h3>

            <form id="armadaForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="kodeArmada">Kode Armada <span style="color: red">*</span></label>
                        <input type="text" id="kodeArmada" placeholder="Contoh: ARM 01" required>
                    </div>
                    <div class="form-group">
                        <label for="noPolisi">No Polisi <span style="color: red">*</span></label>
                        <input type="text" id="noPolisi" placeholder="Contoh: B 1234 AD" required>
                    </div>
                    <div class="form-group">
                        <label for="merk">Merk <span style="color: red">*</span></label>
                        <select id="merk" required>
                            <option value="">-- Pilih Merk --</option>
                            <option value="toyota">Toyota</option>
                            <option value="honda">Honda</option>
                            <option value="mitsubishi">Mitsubishi</option>
                            <option value="suzuki">Suzuki</option>
                            <option value="isuzu">Isuzu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="model">Model <span style="color: red">*</span></label>
                        <input type="text" id="model" placeholder="Contoh: Avanza, L300, Jazz" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipe">Tipe Kendaraan <span style="color: red">*</span></label>
                        <select id="tipe" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="sedan">Sedan</option>
                            <option value="minibus">Minibus</option>
                            <option value="pickup">Pickup</option>
                            <option value="suv">SUV</option>
                            <option value="bus">Bus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas (orang) <span style="color: red">*</span></label>
                        <input type="number" id="kapasitas" min="1" max="50" placeholder="Contoh: 12" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun Pembuatan <span style="color: red">*</span></label>
                        <input type="number" id="tahun" min="2000" max="2024" placeholder="Contoh: 2020" required>
                    </div>
                    <div class="form-group">
                        <label for="warna">Warna <span style="color: red">*</span></label>
                        <input type="text" id="warna" placeholder="Contoh: Putih, Hitam, Silver" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="noSTNK">Nomor STNK</label>
                        <input type="text" id="noSTNK" placeholder="Nomor STNK kendaraan">
                    </div>
                    <div class="form-group">
                        <label for="masaSTNK">Masa Berlaku STNK</label>
                        <input type="date" id="masaSTNK">
                    </div>
                    <div class="form-group">
                        <label for="noKIR">Nomor KIR</label>
                        <input type="text" id="noKIR" placeholder="Nomor KIR kendaraan">
                    </div>
                    <div class="form-group">
                        <label for="masaKIR">Masa Berlaku KIR</label>
                        <input type="date" id="masaKIR">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jenisKepemilikan">Jenis Kepemilikan <span style="color: red">*</span></label>
                        <select id="jenisKepemilikan" required>
                            <option value="">-- Pilih Jenis Kepemilikan --</option>
                            <option value="milik-perusahaan">Milik Perusahaan</option>
                            <option value="sewa">Sewa</option>
                            <option value="vendor">Vendor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaPemilik">Nama Pemilik/Vendor</label>
                        <input type="text" id="namaPemilik" placeholder="Nama pemilik atau vendor">
                    </div>
                    <div class="form-group">
                        <label for="tanggalMasuk">Tanggal Masuk Operasi <span style="color: red">*</span></label>
                        <input type="date" id="tanggalMasuk" required>
                    </div>
                    <div class="form-group">
                        <label for="nilaiAsset">Nilai Asset (Rp)</label>
                        <input type="text" id="nilaiAsset" placeholder="Contoh: 350.000.000">
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status Armada <span style="color: red">*</span></label>
                    <select id="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak-aktif">Tidak Aktif</option>
                        <option value="perbaikan">Dalam Perbaikan</option>
                    </select>
                </div>

                <!-- KELENGKAPAN & PERLENGKAPAN DINAMIS -->
                <div class="form-group">
                    <label>Kelengkapan & Perlengkapan</label>
                    <div class="kelengkapan-container">
                        <div id="kelengkapanList" class="kelengkapan-list">
                            <!-- Kelengkapan akan ditambahkan dinamis di sini -->
                        </div>

                        <div class="add-kelengkapan-form">
                            <input type="text" id="newKelengkapan" placeholder="Masukkan kelengkapan baru...">
                            <button type="button" class="btn-add-kelengkapan" onclick="addKelengkapan()">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-save" type="submit">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <button class="btn-reset" type="reset" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                    <button class="btn-cancel" type="button" onclick="showList()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= DETAIL PAGE ================= -->
    <div id="detail-page" class="hidden">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <button class="btn-back" onclick="showList()">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Armada
            </button>
            <button class="btn-edit-profile" onclick="showEditForm('ARM 01')">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
        </div>

        <div class="detail-container">
            <!-- DATA KENDARAAN -->
            <div class="detail-card">
                <div class="detail-title">Data Kendaraan</div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Kode</label><span id="detail-kode">ARM 01</span></div>
                    <div class="detail-item"><label>No Polisi</label><span id="detail-noPolisi">B 1234 AD</span></div>
                    <div class="detail-item"><label>Tahun</label><span id="detail-tahun">2013</span></div>
                    <div class="detail-item"><label>Model</label><span id="detail-model">Hiace</span></div>
                    <div class="detail-item"><label>Tipe</label><span id="detail-tipe">Sedan</span></div>
                    <div class="detail-item"><label>Warna</label><span id="detail-warna">Putih</span></div>
                    <div class="detail-item"><label>Merk</label><span id="detail-merk">Toyota Hiace</span></div>
                    <div class="detail-item"><label>Kapasitas</label><span id="detail-kapasitas">12</span></div>
                    <div class="detail-item"><label>Status</label><span id="detail-status">Aktif</span></div>
                </div>
            </div>

            <!-- LEGALITAS -->
            <div class="detail-card">
                <div class="detail-title">Legalitas & Asuransi</div>
                <div class="detail-grid-2">
                    <div class="detail-item"><label>Nomor STNK</label><span id="detail-noSTNK">1234567890</span></div>
                    <div class="detail-item"><label>Masa Berlaku STNK</label><span id="detail-masaSTNK">12-12-2026</span></div>
                    <div class="detail-item"><label>Nomor KIR</label><span id="detail-noKIR">KIR889900</span></div>
                    <div class="detail-item"><label>Masa Berlaku KIR</label><span id="detail-masaKIR">12-12-2026</span></div>
                    <div class="detail-item"><label>Asuransi</label><span id="detail-asuransi">All Risk</span></div>
                    <div class="detail-item"><label>Masa Berlaku Asuransi</label><span id="detail-masaAsuransi">12-12-2026</span></div>
                </div>
            </div>

            <!-- OWNERSHIP -->
            <div class="detail-card">
                <div class="detail-title">Ownership</div>
                <div class="detail-grid-2">
                    <div class="detail-item"><label>Jenis Kepemilikan</label><span id="detail-jenisKepemilikan">Milik Perusahaan</span></div>
                    <div class="detail-item"><label>Nama Pemilik / Vendor</label><span id="detail-namaPemilik">PT Trans Nusantara</span></div>
                    <div class="detail-item"><label>Nilai Asset</label><span id="detail-nilaiAsset">Rp 350.000.000</span></div>
                    <div class="detail-item"><label>Status Armada</label><span id="detail-statusArmada">Aktif</span></div>
                    <div class="detail-item"><label>Tanggal Masuk Operasi</label><span id="detail-tanggalMasuk">01-01-2023</span></div>
                    <div class="detail-item"><label>Masa Berlaku Kontrak</label><span id="detail-masaKontrak">01-01-2023 s/d 31-12-2025</span></div>
                </div>
            </div>

            <!-- KELENGKAPAN -->
            <div class="detail-card">
                <div class="detail-title">Kelengkapan & Perlengkapan</div>
                <div class="detail-grid" id="detail-kelengkapan">
                    <!-- Kelengkapan akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Data contoh untuk armada
const armadaData = {
    'ARM 01': {
        kode: 'ARM 01',
        noPolisi: 'B 1234 AD',
        merk: 'Toyota',
        model: 'Hiace',
        tipe: 'Sedan',
        kapasitas: 12,
        tahun: 2013,
        warna: 'Putih',
        noSTNK: '1234567890',
        masaSTNK: '2026-12-12',
        noKIR: 'KIR889900',
        masaKIR: '2026-12-12',
        asuransi: 'All Risk',
        masaAsuransi: '2026-12-12',
        jenisKepemilikan: 'Milik Perusahaan',
        namaPemilik: 'PT Trans Nusantara',
        tanggalMasuk: '2023-01-01',
        nilaiAsset: '350.000.000',
        status: 'aktif',
        kelengkapan: [
            { name: 'Dongkrak', checked: true },
            { name: 'P3K', checked: true },
            { name: 'APAR', checked: true },
            { name: 'Ban Cadangan', checked: true },
            { name: 'Charger', checked: true },
            { name: 'Tools Kit', checked: true },
            { name: 'Starter Kit', checked: false },
            { name: 'Spare Tire', checked: true }
        ]
    },
    'ARM 02': {
        kode: 'ARM 02',
        noPolisi: 'B 9856 ZA',
        merk: 'Honda',
        model: 'Jazz',
        tipe: 'Elf',
        kapasitas: 15,
        tahun: 2019,
        warna: 'Putih',
        noSTNK: '9876543210',
        masaSTNK: '2025-06-30',
        noKIR: 'KIR123456',
        masaKIR: '2025-06-30',
        asuransi: 'TLO',
        masaAsuransi: '2025-06-30',
        jenisKepemilikan: 'Sewa',
        namaPemilik: 'CV Sejahtera',
        tanggalMasuk: '2022-03-15',
        nilaiAsset: '250.000.000',
        status: 'tidak-aktif',
        kelengkapan: [
            { name: 'Dongkrak', checked: true },
            { name: 'P3K', checked: true },
            { name: 'Ban Cadangan', checked: true },
            { name: 'Spare Tire', checked: true },
            { name: 'Jumper Cable', checked: false }
        ]
    },
    'ARM 03': {
        kode: 'ARM 03',
        noPolisi: 'B 4567 BC',
        merk: 'Mitsubishi',
        model: 'L300',
        tipe: 'Pickup',
        kapasitas: 8,
        tahun: 2017,
        warna: 'Hitam',
        noSTNK: '5555555555',
        masaSTNK: '2024-09-30',
        noKIR: 'KIR777777',
        masaKIR: '2024-09-30',
        asuransi: 'All Risk',
        masaAsuransi: '2024-09-30',
        jenisKepemilikan: 'Vendor',
        namaPemilik: 'PT Jaya Abadi',
        tanggalMasuk: '2021-08-10',
        nilaiAsset: '180.000.000',
        status: 'aktif',
        kelengkapan: [
            { name: 'Dongkrak', checked: true },
            { name: 'P3K', checked: true },
            { name: 'APAR', checked: true },
            { name: 'Tools Kit', checked: true },
            { name: 'Emergency Light', checked: true }
        ]
    }
};

// Daftar kelengkapan standar
const defaultKelengkapan = [
    'Dongkrak',
    'P3K',
    'APAR',
    'Ban Cadangan',
    'Charger',
    'Tools Kit',
    'Spare Tire',
    'Jumper Cable',
    'Emergency Light',
    'First Aid Kit',
    'Fire Extinguisher',
    'Warning Triangle',
    'Reflective Vest',
    'Tire Pressure Gauge',
    'Jack Stand'
];

// Mode form (tambah/edit)
let formMode = 'add';
let currentArmadaId = '';

// Fungsi untuk menampilkan halaman list
function showList() {
    // Redirect to list page
    window.location.href = '/admin/armada';
}

// Fungsi untuk menampilkan form tambah
function showAddForm() {
    // Redirect to create page
    window.location.href = '/admin/armada/create';
}

// Fungsi untuk menampilkan form edit
function showEditForm(shuttleId) {
    // Redirect to edit page
    window.location.href = `/admin/armada/${shuttleId}/edit`;
}

// Fungsi untuk render daftar kelengkapan
function renderKelengkapanList(kelengkapanItems) {
    const container = document.getElementById('kelengkapanList');
    container.innerHTML = '';

    kelengkapanItems.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'kelengkapan-item';
        div.innerHTML = `
            <input type="checkbox" id="kelengkapan-${index}" ${item.checked ? 'checked' : ''}>
            <label for="kelengkapan-${index}">${item.name}</label>
            <button type="button" class="remove-kelengkapan" onclick="removeKelengkapan('${item.name}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });
}

// Fungsi untuk menambah kelengkapan baru
function addKelengkapan() {
    const input = document.getElementById('newKelengkapan');
    const name = input.value.trim();

    if (!name) {
        alert('Masukkan nama kelengkapan terlebih dahulu!');
        return;
    }

    // Cek apakah sudah ada
    const container = document.getElementById('kelengkapanList');
    const existingItems = Array.from(container.querySelectorAll('.kelengkapan-item label'));
    const alreadyExists = existingItems.some(label => label.textContent === name);

    if (alreadyExists) {
        alert('Kelengkapan ini sudah ada dalam daftar!');
        return;
    }

    // Tambah item baru
    const div = document.createElement('div');
    div.className = 'kelengkapan-item';
    const index = container.children.length;
    div.innerHTML = `
        <input type="checkbox" id="kelengkapan-new-${index}" checked>
        <label for="kelengkapan-new-${index}">${name}</label>
        <button type="button" class="remove-kelengkapan" onclick="removeKelengkapan('${name}')">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);

    // Reset input
    input.value = '';
    input.focus();
}

// Fungsi untuk menghapus kelengkapan
function removeKelengkapan(name) {
    if (!confirm(`Apakah Anda yakin ingin menghapus "${name}" dari daftar kelengkapan?`)) {
        return;
    }

    const container = document.getElementById('kelengkapanList');
    const items = Array.from(container.querySelectorAll('.kelengkapan-item'));

    items.forEach(item => {
        const label = item.querySelector('label');
        if (label && label.textContent === name) {
            container.removeChild(item);
        }
    });
}

// Fungsi untuk mendapatkan data kelengkapan dari form
function getKelengkapanData() {
    const container = document.getElementById('kelengkapanList');
    const items = Array.from(container.querySelectorAll('.kelengkapan-item'));

    return items.map(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        const label = item.querySelector('label');
        return {
            name: label.textContent,
            checked: checkbox.checked
        };
    });
}

// Fungsi untuk menampilkan detail
function showDetail(shuttleId) {
    // Redirect to detail page
    window.location.href = `/admin/armada/${shuttleId}`;
}

// Fungsi untuk format tanggal
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Fungsi untuk reset form
function resetForm() {
    document.getElementById('armadaForm').reset();
    renderKelengkapanList(defaultKelengkapan.map(name => ({ name, checked: false })));
}

// Fungsi untuk format tanggal
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Form submission handler
document.getElementById('armadaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validasi form
    const kodeArmada = document.getElementById('kodeArmada').value;
    const noPolisi = document.getElementById('noPolisi').value;
    const merk = document.getElementById('merk').value;
    const model = document.getElementById('model').value;
    const tipe = document.getElementById('tipe').value;
    const kapasitas = document.getElementById('kapasitas').value;
    const tahun = document.getElementById('tahun').value;
    const warna = document.getElementById('warna').value;
    const tanggalMasuk = document.getElementById('tanggalMasuk').value;
    const status = document.getElementById('status').value;

    if (!kodeArmada || !noPolisi || !merk || !model || !tipe || !kapasitas || !tahun || !warna || !tanggalMasuk || !status) {
        alert('Harap isi semua field yang wajib diisi!');
        return;
    }

    // Validasi tahun
    const currentYear = new Date().getFullYear();
    if (tahun < 2000 || tahun > currentYear) {
        alert(`Tahun harus antara 2000 dan ${currentYear}`);
        return;
    }

    // Validasi kapasitas
    if (kapasitas < 1 || kapasitas > 50) {
        alert('Kapasitas harus antara 1 dan 50 orang');
        return;
    }

    // Submit form normally (will be handled by backend)
    this.submit();
});

// Filter functionality is now handled by form submission

// Set tanggal default untuk form
window.onload = function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggalMasuk').value = today;
    document.getElementById('masaSTNK').min = today;
    document.getElementById('masaKIR').min = today;

    // Render kelengkapan default saat pertama kali load
    renderKelengkapanList(defaultKelengkapan.map(name => ({ name, checked: false })));
};
</script>
@endsection
