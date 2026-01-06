@extends('layouts.app-admin')

@section('title', 'Master Data - Cabang')

@push('styles')
<style>
/* ===== RESET ===== */
* { box-sizing: border-box; }
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6fb;
}

/* ===== CONTENT ===== */
.wrapper {
    margin-left: -280px;
}
.content {
    padding: 25px;
}

/* ===== SUMMARY ===== */
.summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
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
}
.summary-card p {
    margin: 5px 0 0;
    color: #777;
    font-size: 13px;
}

/* ===== FILTER ===== */
.filter-box {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}
.filter-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}
.filter-row select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}
.filter-action {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}
.search-box {
    flex: 1;
    display: flex;
    gap: 10px;
}
.search-box input {
    flex: 1;
    padding: 10px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}
.btn-filter {
    background: #ff6a00;
    color: #fff;
    padding: 10px 25px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    white-space: nowrap;
    transition: background-color 0.3s;
}
.btn-filter:hover {
    background: #e55c00;
}

/* ===== BUTTON ===== */
.btn-add {
    background: #1e88e5;
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}

/* ===== TABLE ===== */
.table-box {
    background: #fff;
    border-radius: 10px;
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
thead {
    background: #0b2a4a;
    color: #fff;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}
.status {
    padding: 5px 12px;
    border-radius: 20px;
    color: #fff;
    font-size: 12px;
    display: inline-block;
    min-width: 70px;
    text-align: center;
}
.status.active { background: #2ecc71; }
.status.inactive { background: #e74c3c; }

/* ===== FORM CARD ===== */
.form-card {
    max-width: 1200px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
}
.form-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 12px;
    font-size: 20px;
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
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
}
textarea {
    resize: none;
    min-height: 80px;
}
.time-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 10px;
    align-items: center;
}
.time-separator {
    text-align: center;
    font-weight: bold;
    color: #666;
}
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
}
.btn-cancel:hover {
    background: #5a6268;
}
.hidden {
    display: none;
}

/* Form Layout for Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .time-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .time-separator {
        display: none;
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
}

@media (max-width: 576px) {
    .filter-row {
        grid-template-columns: 1fr;
    }

    .filter-action {
        flex-direction: column;
    }

    .search-box {
        width: 100%;
    }

    .btn-filter {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="wrapper">
    <main class="content">

        <!-- ================= LIST CABANG ================= -->
        <div id="page-list">

            <div class="summary">
                <div class="summary-card">
                    <h3>3</h3>
                    <p>Total Cabang</p>
                </div>
                <div class="summary-card">
                    <h3>21</h3>
                    <p>Cabang Aktif</p>
                </div>
                <div class="summary-card">
                    <h3>0</h3>
                    <p>Cabang Non-Aktif</p>
                </div>
            </div>

            <div class="filter-box">
                <div class="filter-row">
                    <select>
                        <option value="">Pilih Kota</option>
                        <option value="bandung">Bandung</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="surabaya">Surabaya</option>
                    </select>
                    <select>
                        <option value="">Pilih Nama Cabang</option>
                        <option value="bandung-utara">Bandung Utara</option>
                        <option value="bandung-selatan">Bandung Selatan</option>
                    </select>
                    <select>
                        <option value="">Pilih Kode</option>
                        <option value="BDG-01">BDG-01</option>
                        <option value="BDG-02">BDG-02</option>
                    </select>
                    <select>
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="filter-action">
                    <div class="search-box">
                        <input type="text" placeholder="Cari cabang berdasarkan kota, nama, kode, atau status...">
                        <button type="button" class="btn-filter">Filter</button>
                    </div>
                </div>
            </div>

            <button class="btn-add" onclick="showForm()">
                <i class="fas fa-plus"></i> Tambah Cabang
            </button>

            <div class="table-box">
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
                        <tr>
                            <td>BDG-01</td>
                            <td>Bandung Utara</td>
                            <td>Bandung</td>
                            <td>Jl. Soekarno Hatta No. 123</td>
                            <td>(022) 1234-5678</td>
                            <td>bandung@smartshuttle.com</td>
                            <td>-6.917464,107.619125</td>
                            <td>06:00 - 22:00</td>
                            <td><span class="status active">Aktif</span></td>
                            <td>
                                <button class="btn-edit" onclick="showForm()">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>BDG-02</td>
                            <td>Bandung Selatan</td>
                            <td>Bandung</td>
                            <td>Jl. Raya Kopo No. 456</td>
                            <td>(022) 8765-4321</td>
                            <td>bandung.selatan@smartshuttle.com</td>
                            <td>-6.954824, 107.586945</td>
                            <td>05:30 - 21:30</td>
                            <td><span class="status active">Aktif</span></td>
                            <td>
                                <button class="btn-edit" onclick="showForm()">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>JKT-01</td>
                            <td>Jakarta Pusat</td>
                            <td>Jakarta</td>
                            <td>Jl. Thamrin No. 10</td>
                            <td>(021) 5555-1234</td>
                            <td>jakarta@smartshuttle.com</td>
                            <td>-6.186486, 106.822915</td>
                            <td>05:00 - 23:00</td>
                            <td><span class="status inactive">Non-Aktif</span></td>
                            <td>
                                <button class="btn-edit" onclick="showForm()">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= FORM TAMBAH CABANG ================= -->
        <div id="page-form" class="hidden">
            <button class="btn-back" onclick="showList()">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Cabang
            </button>

            <div class="form-card">
                <h3>Tambahkan Data Cabang</h3>

                <form id="cabangForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kodeCabang">Kode Cabang <span style="color: red">*</span></label>
                            <input type="text" id="kodeCabang" placeholder="Contoh: BDG-01" required>
                        </div>
                        <div class="form-group">
                            <label for="namaCabang">Nama Cabang <span style="color: red">*</span></label>
                            <input type="text" id="namaCabang" placeholder="Masukkan nama cabang" required>
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota <span style="color: red">*</span></label>
                            <input type="text" id="kota" placeholder="Masukkan nama kota" required>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon <span style="color: red">*</span></label>
                            <input type="tel" id="telepon" placeholder="Masukkan nomor telepon" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap <span style="color: red">*</span></label>
                        <textarea id="alamat" rows="3" placeholder="Masukkan alamat lengkap cabang" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span style="color: red">*</span></label>
                        <input type="email" id="email" placeholder="contoh: cabang@smartshuttle.com" required>
                    </div>

                    <div class="form-group">
                        <label for="koordinat">Koordinat GPS</label>
                        <input type="text" id="koordinat" placeholder="Format: latitude,longitude (Contoh: -6.234494,106.989615)">
                        <small style="color: #666; font-size: 12px;">*Opsional: Isi dengan koordinat GPS cabang</small>
                    </div>

                    <div class="form-group">
                        <label>Jam Operasional <span style="color: red">*</span></label>
                        <div class="time-row">
                            <div>
                                <input type="time" id="jamBuka" required>
                                <small style="color: #666; font-size: 12px;">Jam Buka</small>
                            </div>
                            <div class="time-separator">-</div>
                            <div>
                                <input type="time" id="jamTutup" required>
                                <small style="color: #666; font-size: 12px;">Jam Tutup</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Cabang <span style="color: red">*</span></label>
                        <select id="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non Aktif</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button class="btn-save" type="submit">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                        <button class="btn-reset" type="reset">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button class="btn-cancel" type="button" onclick="showList()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
function showForm() {
    document.getElementById('page-list').classList.add('hidden');
    document.getElementById('page-form').classList.remove('hidden');
    // Reset form saat membuka
    document.getElementById('cabangForm').reset();
    // Scroll ke atas
    window.scrollTo(0, 0);
}

function showList() {
    document.getElementById('page-form').classList.add('hidden');
    document.getElementById('page-list').classList.remove('hidden');
}

// Form submission handler
document.getElementById('cabangForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validasi form
    const kodeCabang = document.getElementById('kodeCabang').value;
    const namaCabang = document.getElementById('namaCabang').value;
    const kota = document.getElementById('kota').value;
    const telepon = document.getElementById('telepon').value;
    const alamat = document.getElementById('alamat').value;
    const email = document.getElementById('email').value;
    const jamBuka = document.getElementById('jamBuka').value;
    const jamTutup = document.getElementById('jamTutup').value;
    const status = document.getElementById('status').value;

    if (!kodeCabang || !namaCabang || !kota || !telepon || !alamat || !email || !jamBuka || !jamTutup || !status) {
        alert('Harap isi semua field yang wajib diisi!');
        return;
    }

    // Validasi format email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Format email tidak valid!');
        return;
    }

    // Validasi jam operasional
    if (jamBuka >= jamTutup) {
        alert('Jam buka harus lebih awal dari jam tutup!');
        return;
    }

    // Simulasi penyimpanan data
    alert('Data cabang berhasil disimpan!');
    showList();
});

// Filter button handler
document.querySelector('.btn-filter').addEventListener('click', function() {
    const searchTerm = document.querySelector('.search-box input').value;
    const kotaFilter = document.querySelector('.filter-row select:nth-child(1)').value;
    const namaFilter = document.querySelector('.filter-row select:nth-child(2)').value;
    const kodeFilter = document.querySelector('.filter-row select:nth-child(3)').value;
    const statusFilter = document.querySelector('.filter-row select:nth-child(4)').value;

    // Implementasi logika filter di sini
    console.log('Filter diterapkan:', {
        search: searchTerm,
        kota: kotaFilter,
        nama: namaFilter,
        kode: kodeFilter,
        status: statusFilter
    });

    alert('Filter diterapkan!');
});

// Reset filter
document.querySelector('.search-box input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.querySelector('.btn-filter').click();
    }
});
</script>
@endsection
