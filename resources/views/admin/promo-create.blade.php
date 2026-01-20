@extends('layouts.app-admin')

@section('title', 'Tambah Data Promo')

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
.btn-back {
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

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
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
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Tambah Data Promo</h2>
        <button class="btn-back" onclick="window.location.href='{{ route('admin.promo') }}'">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Promo
        </button>
    </div>

    <!-- FORM -->
    <div class="form-card">
        <h3>Informasi Promo</h3>

        <form method="POST" action="{{ route('admin.promo.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="kode_promo">Kode Promo <span style="color: red">*</span></label>
                    <input type="text" id="kode_promo" name="kode_promo" placeholder="Contoh: PROMO50" required>
                </div>
                <div class="form-group">
                    <label for="nama_promo">Nama Promo <span style="color: red">*</span></label>
                    <input type="text" id="nama_promo" name="nama_promo" placeholder="Contoh: Diskon 50%" required>
                </div>
                <div class="form-group">
                    <label for="jenis_diskon">Jenis Diskon <span style="color: red">*</span></label>
                    <select id="jenis_diskon" name="jenis_diskon" required onchange="toggleDiskonFields()">
                        <option value="">-- Pilih Jenis Diskon --</option>
                        <option value="persentase">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nilai_diskon">Nilai Diskon <span style="color: red">*</span></label>
                    <input type="number" id="nilai_diskon" name="nilai_diskon" min="0" step="0.01" placeholder="Contoh: 50" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="maksimal_diskon">Maksimal Diskon (Rp)</label>
                    <input type="number" id="maksimal_diskon" name="maksimal_diskon" min="0" placeholder="Contoh: 50000">
                </div>
                <div class="form-group">
                    <label for="minimal_pembelian">Minimal Pembelian (Rp)</label>
                    <input type="number" id="minimal_pembelian" name="minimal_pembelian" min="0" placeholder="Contoh: 100000">
                </div>
                <div class="form-group">
                    <label for="min_tiket">Minimal Tiket</label>
                    <input type="number" id="min_tiket" name="min_tiket" min="0" placeholder="Contoh: 1">
                </div>
                <div class="form-group">
                    <label for="kuota">Kuota Penggunaan</label>
                    <input type="number" id="kuota" name="kuota" min="0" placeholder="Kosongkan jika unlimited">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kategori_promo">Kategori Promo <span style="color: red">*</span></label>
                    <select id="kategori_promo" name="kategori_promo" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="umum">Umum</option>
                        <option value="keluarga">Keluarga</option>
                        <option value="membership">Membership</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipe_promo">Tipe Promo <span style="color: red">*</span></label>
                    <select id="tipe_promo" name="tipe_promo" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="all">All (Semua)</option>
                        <option value="shuttle">Shuttle</option>
                        <option value="paket">Paket</option>
                        <option value="sewa">Sewa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_berakhir">Tanggal Berakhir <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Promo <span style="color: red">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan detail promo ini..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="pesan_error">Pesan Error</label>
                    <textarea id="pesan_error" name="pesan_error" placeholder="Pesan yang ditampilkan jika promo tidak bisa digunakan"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gambar">Gambar Promo</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <small style="color: #666; display: block; margin-top: 5px;">Format: JPG, PNG, Max: 2MB</small>
                </div>
                <div class="form-group">
                    <label style="display: block;">Opsi Tambahan</label>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                            <input type="checkbox" id="khusus_member" name="khusus_member" value="1">
                            Khusus Member
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                            <input type="checkbox" id="status" name="status" value="1" checked>
                            Aktif
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Promo
                </button>
                <button type="reset" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.promo') }}'">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Fungsi untuk toggle field maksimal diskon berdasarkan jenis diskon
function toggleDiskonFields() {
    const jenisDiskon = document.getElementById('jenis_diskon').value;
    const maksimalDiskon = document.getElementById('maksimal_diskon');
    const nilaiDiskon = document.getElementById('nilai_diskon');

    if (jenisDiskon === 'persentase') {
        maksimalDiskon.style.display = 'block';
        nilaiDiskon.placeholder = 'Contoh: 50';
        nilaiDiskon.max = '100';
    } else if (jenisDiskon === 'nominal') {
        maksimalDiskon.style.display = 'none';
        nilaiDiskon.placeholder = 'Contoh: 50000';
        nilaiDiskon.removeAttribute('max');
    }
}

// Fungsi untuk reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang.')) {
        document.querySelector('form').reset();
        toggleDiskonFields();
    }
}

// Set tanggal default dan inisialisasi
window.onload = function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_mulai').value = today;

    // Set tanggal berakhir default (7 hari dari sekarang)
    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 7);
    document.getElementById('tanggal_berakhir').value = nextWeek.toISOString().split('T')[0];

    // Inisialisasi toggle diskon
    toggleDiskonFields();
};
</script>
@endsection
