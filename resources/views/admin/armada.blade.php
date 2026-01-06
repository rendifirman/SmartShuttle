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
}

/* ================= SUMMARY ================= */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}
.summary-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    gap: 15px;
    align-items: center;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}
.summary-icon {
    font-size: 26px;
}
.summary-text h3 {
    margin: 0;
    font-size: 18px;
}
.summary-text p {
    margin: 2px 0 0;
    font-size: 13px;
    color: #777;
}
.total { border-left: 6px solid #cfcfcf; }
.active { background: #e9fff1; border-left: 6px solid #27ae60; }
.inactive { background: #ffecec; border-left: 6px solid #e74c3c; }
.service { background: #fff6cc; border-left: 6px solid #f1c40f; }

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
}

/* Tambahkan CSS untuk tombol Edit Profile */
.btn-edit-profile {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 15px;
}
/* Responsif untuk tombol */
@media (max-width: 768px) {
    .btn-edit-profile {
        padding: 10px 18px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    div[style*="display: flex; justify-content: space-between"] {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .btn-back,
    .btn-edit-profile {
        width: 100%;
        text-align: center;
    }
}

/* ================= TABLE ================= */
.table-wrapper {
    background: #fff;
    border-radius: 14px;
    padding: 15px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}
.table-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}
.btn-excel {
    background: #12b600;
    color: #fff;
    padding: 8px 18px;
    border-radius: 20px;
    border: none;
    font-weight: 600;
}
.btn-pdf {
    background: #ddd;
    padding: 8px 18px;
    border-radius: 20px;
    border: none;
    font-weight: 600;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
thead {
    background: #f1f1f1;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}
.badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-active {
    background: #b8f0a3;
    color: #1e7e34;
}
.badge-inactive {
    background: #ff9a9a;
    color: #8b0000;
}
.btn-view {
    background: #888;
    color: #fff;
    padding: 6px 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}
.btn-edit {
    background: #f9b000;
    color: #fff;
    padding: 6px 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

/* ================= PAGINATION ================= */
.pagination {
    margin-top: 15px;
    font-size: 13px;
    color: #777;
}

/* ================= DETAIL ARMADA ================= */
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
}
.detail-item span {
    font-weight: 600;
    font-size: 13px;
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

.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 15px;
}

.hidden {
    display: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-top {
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-grid-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .filter-top {
        grid-template-columns: 1fr;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .page-container {
        padding: 15px;
    }
}

/* ================= FORM EDIT ARMADA ================= */
.form-card {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}

.form-title {
    font-weight: 700;
    font-size: 16px;
    margin-bottom: 20px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 8px;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 13px;
}

.form-group input[type="file"] {
    padding: 8px;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.checkbox-grid label {
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
}

.btn-save {
    background: #0b2a4a;
    color: #fff;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
}

.btn-reset {
    background: #ff6a00;
    color: #fff;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .form-grid-2,
    .form-grid-3,
    .checkbox-grid {
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
            <h2>Data Armada</h2>
            <button class="btn-add">+ Tambah Armada</button>
        </div>

        <!-- SUMMARY -->
        <div class="summary-grid">
            <div class="summary-card total">
                <div class="summary-icon">🚐</div>
                <div class="summary-text">
                    <h3>100 Kendaraan</h3>
                    <p>Total Kendaraan</p>
                </div>
            </div>
            <div class="summary-card active">
                <div class="summary-icon">📄</div>
                <div class="summary-text">
                    <h3>70 Aktif</h3>
                    <p>Status Kendaraan</p>
                </div>
            </div>
            <div class="summary-card inactive">
                <div class="summary-icon">📄</div>
                <div class="summary-text">
                    <h3>20 Tidak Aktif</h3>
                    <p>Status Kendaraan</p>
                </div>
            </div>
            <div class="summary-card service">
                <div class="summary-icon">🛠</div>
                <div class="summary-text">
                    <h3>10 Dalam Perbaikan</h3>
                    <p>Status Kendaraan</p>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <div class="filter-top">
                <select><option>Pilih Merk</option></select>
                <select><option>Pilih Tipe</option></select>
                <select><option>Pilih Warna</option></select>
                <select><option>Pilih Status</option></select>
            </div>
            <div class="filter-bottom">
                <input type="text" placeholder="Cari Kode / No Polisi / Merk / Model / Tipe">
                <button class="btn-filter">Filter</button>
            </div>
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
                    <tr>
                        <td>ARM 01</td>
                        <td>B 1234 AD</td>
                        <td>Toyota</td>
                        <td>Avanza</td>
                        <td>Sedan</td>
                        <td>12</td>
                        <td>2013</td>
                        <td>Putih</td>
                        <td><span class="badge badge-active">Aktif</span></td>
                        <td>
                            <button class="btn-view" onclick="showDetail()">View</button>
                            <button class="btn-edit">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>ARM 02</td>
                        <td>B 9856 ZA</td>
                        <td>Honda</td>
                        <td>Jazz</td>
                        <td>Elf</td>
                        <td>15</td>
                        <td>2019</td>
                        <td>Putih</td>
                        <td><span class="badge badge-inactive">Tidak Aktif</span></td>
                        <td>
                            <button class="btn-view" onclick="showDetail()">View</button>
                            <button class="btn-edit" onclick="showEdit()">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>ARM 03</td>
                        <td>B 4567 BC</td>
                        <td>Mitsubishi</td>
                        <td>L300</td>
                        <td>Pickup</td>
                        <td>8</td>
                        <td>2017</td>
                        <td>Hitam</td>
                        <td><span class="badge badge-active">Aktif</span></td>
                        <td>
                            <button class="btn-view" onclick="showDetail()">View</button>
                            <button class="btn-edit">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination">1 2 3</div>
        </div>
    </div>

    <!-- ================= DETAIL PAGE ================= -->
    <div id="detail-page" class="hidden">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <button class="btn-back" onclick="showList()">← Kembali ke Daftar Armada</button>
            <button class="btn-edit-profile" onclick="editProfile()">✏️ Edit Profile</button>
        </div>


        <div class="detail-container">

            <!-- DATA KENDARAAN -->
            <div class="detail-card">
                <div class="detail-title">Data Kendaraan</div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Kode</label><span>ARM 01</span></div>
                    <div class="detail-item"><label>No Polisi</label><span>B 1234 AD</span></div>
                    <div class="detail-item"><label>Tahun</label><span>2013</span></div>

                    <div class="detail-item"><label>Model</label><span>Hiace</span></div>
                    <div class="detail-item"><label>Tipe</label><span>Sedan</span></div>
                    <div class="detail-item"><label>Warna</label><span>Putih</span></div>

                    <div class="detail-item"><label>Merk</label><span>Toyota Hiace</span></div>
                    <div class="detail-item"><label>Kapasitas</label><span>12</span></div>
                    <div class="detail-item"><label>Status</label><span>Aktif</span></div>
                </div>
            </div>

            <!-- LEGALITAS -->
            <div class="detail-card">
                <div class="detail-title">Legalitas & Asuransi</div>
                <div class="detail-grid-2">
                    <div class="detail-item"><label>Nomor STNK</label><span>1234567890</span></div>
                    <div class="detail-item"><label>Masa Berlaku STNK</label><span>12-12-2026</span></div>

                    <div class="detail-item"><label>Nomor KIR</label><span>KIR889900</span></div>
                    <div class="detail-item"><label>Masa Berlaku KIR</label><span>12-12-2026</span></div>

                    <div class="detail-item"><label>Asuransi</label><span>All Risk</span></div>
                    <div class="detail-item"><label>Masa Berlaku Asuransi</label><span>12-12-2026</span></div>
                </div>
            </div>

            <!-- OWNERSHIP -->
            <div class="detail-card">
                <div class="detail-title">Ownership</div>
                <div class="detail-grid-2">
                    <div class="detail-item"><label>Jenis Kepemilikan</label><span>Milik Perusahaan</span></div>
                    <div class="detail-item"><label>Nama Pemilik / Vendor</label><span>PT Trans Nusantara</span></div>

                    <div class="detail-item"><label>Nilai Asset</label><span>Rp 350.000.000</span></div>
                    <div class="detail-item"><label>Status Armada</label><span>Aktif</span></div>

                    <div class="detail-item"><label>Tanggal Masuk Operasi</label><span>01-01-2023</span></div>
                    <div class="detail-item"><label>Masa Berlaku Kontrak</label><span>01-01-2023 s/d 31-12-2025</span></div>
                </div>
            </div>

            <!-- KELENGKAPAN -->
            <div class="detail-card">
                <div class="detail-title">Kelengkapan & Perlengkapan</div>
                <div class="detail-grid">
                    <span class="badge-check">✔ Dongkrak</span>
                    <span class="badge-check">✔ P3K</span>
                    <span class="badge-check">✔ APAR</span>
                    <span class="badge-check">✔ Ban Cadangan</span>
                    <span class="badge-check">✔ Charger</span>
                    <span class="badge-check">✔ Tools Kit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= EDIT PAGE ================= -->
    <div id="edit-page" class="hidden">

        <button class="btn-back" onclick="showDetail()">← Kembali</button>

        <!-- DATA KENDARAAN -->
        <div class="form-card">
            <div class="form-title">Data Kendaraan</div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Kode Armada</label>
                    <input type="text" value="ARM 01">
                </div>
                <div class="form-group">
                    <label>No Polisi</label>
                    <input type="text" value="B 1234 AD">
                </div>
                <div class="form-group">
                    <label>Tahun</label>
                    <input type="number" value="2013">
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" value="Toyota">
                </div>
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" value="Hiace">
                </div>
                <div class="form-group">
                    <label>Tipe</label>
                    <select>
                        <option>Sedan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" value="Putih">
                </div>
                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="number" value="12">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select>
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- LEGALITAS -->
        <div class="form-card">
            <div class="form-title">Legalitas & Asuransi</div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Nomor STNK</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Masa Berlaku STNK</label>
                    <input type="date">
                </div>

                <div class="form-group">
                    <label>Nomor KIR</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Masa Berlaku KIR</label>
                    <input type="date">
                </div>

                <div class="form-group">
                    <label>Asuransi</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Masa Berlaku Asuransi</label>
                    <input type="date">
                </div>
            </div>

            <div class="form-group" style="margin-top:15px">
                <input type="file">
            </div>
        </div>

        <!-- OWNERSHIP -->
        <div class="form-card">
            <div class="form-title">Ownership</div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Jenis Kepemilikan</label>
                    <select><option>Milik Perusahaan</option></select>
                </div>
                <div class="form-group">
                    <label>Nama Pemilik / Vendor</label>
                    <input type="text">
                </div>

                <div class="form-group">
                    <label>Tanggal Masuk Operasional</label>
                    <input type="date">
                </div>
                <div class="form-group">
                    <label>Masa Berlaku Kontrak</label>
                    <input type="date">
                </div>

                <div class="form-group">
                    <label>Nilai Asset</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Status Armada</label>
                    <select><option>Aktif</option></select>
                </div>
            </div>

            <div class="form-group" style="margin-top:15px">
                <input type="file">
            </div>
        </div>

        <!-- KELENGKAPAN -->
        <div class="form-card">
            <div class="form-title">Kelengkapan & Perlengkapan</div>
            <div class="checkbox-grid">
                <label><input type="checkbox"> Dongkrak</label>
                <label><input type="checkbox"> P3K</label>
                <label><input type="checkbox"> APAR</label>
                <label><input type="checkbox"> Ban Cadangan</label>
                <label><input type="checkbox"> Charger</label>
                <label><input type="checkbox"> Tools Kit</label>
            </div>
        </div>

        <!-- ACTION -->
        <div class="form-actions">
            <button class="btn-save">Simpan</button>
            <button class="btn-reset" type="reset">Reset</button>
        </div>
    </div>
</div>

<script>
function editProfile() {
    alert('Edit profile armada akan ditampilkan!');
    // Implementasi fungsi edit profile di sini
}

function showEdit() {
    document.getElementById('list-page').classList.add('hidden');
    document.getElementById('detail-page').classList.add('hidden');
    document.getElementById('edit-page').classList.remove('hidden');
    window.scrollTo(0,0);
}

function showDetail() {
    document.getElementById('edit-page').classList.add('hidden');
    document.getElementById('list-page').classList.add('hidden');
    document.getElementById('detail-page').classList.remove('hidden');
    window.scrollTo(0,0);
}

function showList() {
    document.getElementById('edit-page').classList.add('hidden');
    document.getElementById('detail-page').classList.add('hidden');
    document.getElementById('list-page').classList.remove('hidden');
    window.scrollTo(0,0);
}

// Event listener untuk tombol view di semua baris
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener ke semua tombol view
    const viewButtons = document.querySelectorAll('.btn-view');
    viewButtons.forEach(button => {
        button.addEventListener('click', showDetail);
    });

    // Event listener untuk tombol filter
    const filterButton = document.querySelector('.btn-filter');
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            const searchTerm = document.querySelector('.filter-bottom input').value;
            const merkFilter = document.querySelector('.filter-top select:nth-child(1)').value;
            const tipeFilter = document.querySelector('.filter-top select:nth-child(2)').value;
            const warnaFilter = document.querySelector('.filter-top select:nth-child(3)').value;
            const statusFilter = document.querySelector('.filter-top select:nth-child(4)').value;

            // Implementasi logika filter di sini
            console.log('Filter diterapkan:', {
                search: searchTerm,
                merk: merkFilter,
                tipe: tipeFilter,
                warna: warnaFilter,
                status: statusFilter
            });

            alert('Filter diterapkan!');
        });
    }

    // Event listener untuk pencarian dengan Enter
    const searchInput = document.querySelector('.filter-bottom input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.btn-filter').click();
            }
        });
    }
});
</script>
@endsection
