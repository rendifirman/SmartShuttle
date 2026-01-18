@extends('layouts.app-admin')

@section('title', 'Tambah Outlet')

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
    margin-bottom: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
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

/* ================= FACILITIES CHECKBOXES ================= */
.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 10px;
}
.facility-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.facility-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #1e88e5;
}
.facility-item label {
    font-size: 14px;
    color: #555;
    margin: 0;
    cursor: pointer;
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

/* ================= ALERTS ================= */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
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
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-save,
    .btn-reset {
        width: 100%;
        text-align: center;
    }

    .page-container {
        padding: 15px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Tambah Outlet Baru</h2>
        <a href="{{ route('admin.outletperusahaan') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
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

    <!-- FORM -->
    <form action="{{ route('admin.outletperusahaan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-card">
            <h3><i class="fas fa-plus"></i> Informasi Outlet Baru</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="branch_id">Cabang <span style="color: red;">*</span></label>
                    <select name="branch_id" id="branch_id" required>
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }} - {{ $branch->kota }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_outlet">Nama Outlet <span style="color: red;">*</span></label>
                    <input type="text" name="nama_outlet" id="nama_outlet" required
                           value="{{ old('nama_outlet') }}"
                           placeholder="Masukkan nama outlet">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="alamat_lengkap">Alamat Lengkap <span style="color: red;">*</span></label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" required
                              placeholder="Masukkan alamat lengkap outlet">{{ old('alamat_lengkap') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="telepon">Telepon <span style="color: red;">*</span></label>
                    <input type="text" name="telepon" id="telepon" required
                           value="{{ old('telepon') }}"
                           placeholder="Masukkan nomor telepon">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan alamat email">
                </div>

                <div class="form-group">
                    <label for="tipe_outlet">Tipe Outlet <span style="color: red;">*</span></label>
                    <select name="tipe_outlet" id="tipe_outlet" required>
                        <option value="">Pilih Tipe Outlet</option>
                        <option value="regular" {{ old('tipe_outlet') == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="premium" {{ old('tipe_outlet') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="express" {{ old('tipe_outlet') == 'express' ? 'selected' : '' }}>Express</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kapasitas_parkir">Kapasitas Parkir</label>
                    <input type="number" name="kapasitas_parkir" id="kapasitas_parkir" min="0"
                           value="{{ old('kapasitas_parkir') }}"
                           placeholder="Masukkan kapasitas parkir">
                </div>

                <div class="form-group">
                    <label for="zona_pelayanan">Zona Pelayanan</label>
                    <input type="text" name="zona_pelayanan" id="zona_pelayanan"
                           value="{{ old('zona_pelayanan') }}"
                           placeholder="Masukkan zona pelayanan">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jam_operasional">Jam Operasional</label>
                    <input type="text" name="jam_operasional" id="jam_operasional"
                           value="{{ old('jam_operasional') }}"
                           placeholder="Contoh: 08:00 - 20:00">
                </div>

                <div class="form-group">
                    <label for="status">Status <span style="color: red;">*</span></label>
                    <select name="status" id="status" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Fasilitas</label>
                <div class="facilities-grid">
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="WiFi" id="wifi" {{ in_array('WiFi', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="wifi">WiFi</label>
                    </div>
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="Toilet" id="toilet" {{ in_array('Toilet', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="toilet">Toilet</label>
                    </div>
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="Musholla" id="musholla" {{ in_array('Musholla', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="musholla">Musholla</label>
                    </div>
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="ATM" id="atm" {{ in_array('ATM', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="atm">ATM</label>
                    </div>
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="Charger USB" id="charger" {{ in_array('Charger USB', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="charger">Charger USB</label>
                    </div>
                    <div class="facility-item">
                        <input type="checkbox" name="fasilitas[]" value="AC" id="ac" {{ in_array('AC', old('fasilitas', [])) ? 'checked' : '' }}>
                        <label for="ac">AC</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Outlet
                </button>
                <button type="reset" class="btn-reset">
                    <i class="fas fa-undo"></i> Reset Form
                </button>
            </div>
        </div>
    </form>

</div>

<script>
// SweetAlert for notifications
@if(session('success') || session('error'))
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
});
@endif
</script>

<!-- SweetAlert2 -->
@if(session('success') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endif

@endsection
