@extends('layouts.app-admin')

@section('title', 'Tambah Cabang')

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

/* ===== ALERTS ===== */
.alert {
    padding: 15px;
    border-radius: 6px;
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
    .btn-cancel {
        width: 100%;
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<div class="wrapper">
    <main class="content">

        <!-- Display success/error messages -->
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

        <div class="form-card">
            <h3>Tambahkan Data Cabang</h3>

            <form action="{{ route('admin.cabang.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_cabang">Kode Cabang <span style="color: red">*</span></label>
                        <input type="text" id="kode_cabang" name="kode_cabang" placeholder="Contoh: BDG-01" required>
                        @error('kode_cabang')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_cabang">Nama Cabang <span style="color: red">*</span></label>
                        <input type="text" id="nama_cabang" name="nama_cabang" placeholder="Masukkan nama cabang" required>
                        @error('nama_cabang')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kota">Kota <span style="color: red">*</span></label>
                        <input type="text" id="kota" name="kota" placeholder="Masukkan nama kota" required>
                        @error('kota')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon <span style="color: red">*</span></label>
                        <input type="tel" id="telepon" name="telepon" placeholder="Masukkan nomor telepon" required>
                        @error('telepon')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap <span style="color: red">*</span></label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap cabang" required></textarea>
                    @error('alamat')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span style="color: red">*</span></label>
                    <input type="email" id="email" name="email" placeholder="contoh: cabang@smartshuttle.com" required>
                    @error('email')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="koordinat_gps">Koordinat GPS</label>
                    <input type="text" id="koordinat_gps" name="koordinat_gps" placeholder="Format: latitude,longitude (Contoh: -6.234494,106.989615)">
                    <small style="color: #666; font-size: 12px;">*Opsional: Isi dengan koordinat GPS cabang</small>
                    @error('koordinat_gps')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Jam Operasional <span style="color: red">*</span></label>
                    <div class="time-row">
                        <div>
                            <input type="time" id="jam_buka" name="jam_buka" required>
                            <small style="color: #666; font-size: 12px;">Jam Buka</small>
                        </div>
                        <div class="time-separator">-</div>
                        <div>
                            <input type="time" id="jam_tutup" name="jam_tutup" required>
                            <small style="color: #666; font-size: 12px;">Jam Tutup</small>
                        </div>
                    </div>
                    @error('jam_buka')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                    @error('jam_tutup')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status Cabang <span style="color: red">*</span></label>
                    <select id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non Aktif</option>
                    </select>
                    @error('status')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="btn-save" type="submit">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <a href="{{ route('admin.cabangperusahaan') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>

    </main>
</div>
@endsection
