@extends('layouts.app-admin')

@section('title', 'Tambah Artikel')

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
    min-height: 120px;
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
        <h2>Tambah Artikel</h2>
        <button class="btn-back" onclick="window.location.href='{{ route('admin.artikel.index') }}'">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
        </button>
    </div>

    <!-- FORM -->
    <div class="form-card">
        <h3>Informasi Artikel</h3>

        <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="judul">Judul Artikel <span style="color: red">*</span></label>
                    <input type="text" id="judul" name="judul" placeholder="Masukkan judul artikel" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori <span style="color: red">*</span></label>
                    <select id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="berita">Berita</option>
                        <option value="tips">Tips & Trik</option>
                        <option value="panduan">Panduan</option>
                        <option value="promo">Promo</option>
                        <option value="layanan">Layanan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="penulis">Penulis <span style="color: red">*</span></label>
                    <input type="text" id="penulis" name="penulis" placeholder="Nama penulis" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_publikasi">Tanggal Publikasi <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_publikasi" name="tanggal_publikasi" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="konten">Konten Artikel <span style="color: red">*</span></label>
                    <textarea id="konten" name="konten" placeholder="Tulis konten artikel di sini..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar Artikel</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <small style="color: #666; display: block; margin-top: 5px;">Format: JPG, PNG, Max: 2MB</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label style="display: block;">Status Publikasi</label>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                            <input type="checkbox" id="status" name="status" value="1" checked>
                            Publikasikan Artikel
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Artikel
                </button>
                <button type="reset" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.artikel.index') }}'">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Fungsi untuk reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang.')) {
        document.querySelector('form').reset();
    }
}

// Set tanggal default
window.onload = function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_publikasi').value = today;
};
</script>
@endsection
