@extends('layouts.app-admin')

@section('title', 'Tambah User')

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
    text-decoration: none;
    font-size: 14px;
}
.btn-back:hover {
    background: #5a6268;
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
.form-group small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
}
.required {
    color: #ff0000;
}
/* ================= ALERT ================= */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}
.alert-error {
    background-color: #fdecea;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.alert ul {
    margin: 0;
    padding-left: 20px;
}
.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
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
    text-decoration: none;
}
.btn-cancel:hover {
    background: #5a6268;
}

/* ================= PERMISSIONS ================= */
.permission-category {
    margin-bottom: 25px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.permission-category h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    color: #0b2a4a;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.permission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
}
.permission-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    position: relative;
}
.permission-item:hover {
    border-color: #1e88e5;
    background: #f8f9ff;
}
.permission-item input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}
.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #ddd;
    border-radius: 3px;
    margin-right: 10px;
    position: relative;
    transition: all 0.3s;
}
.permission-item input[type="checkbox"]:checked ~ .checkmark {
    background: #1e88e5;
    border-color: #1e88e5;
}
.permission-item input[type="checkbox"]:checked ~ .checkmark::after {
    content: '✓';
    position: absolute;
    top: -2px;
    left: 2px;
    color: white;
    font-size: 12px;
    font-weight: bold;
}
.btn-sm {
    background: #6c757d;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    transition: background-color 0.3s;
}
.btn-sm:hover {
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
        justify-content: center;
    }
    .permission-grid {
        grid-template-columns: 1fr;
    }
    .permission-category h4 {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Tambah Data User</h2>
        <a href="{{ route('admin.user') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
        </a>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->
    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <div class="form-card">
        <h3>Informasi User</h3>

        <form method="POST" action="{{ route('admin.user.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan alamat email" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone') }}"
                           placeholder="Masukkan nomor telepon">
                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" name="nik" id="nik"
                           value="{{ old('nik') }}"
                           placeholder="Masukkan NIK">
                    @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                           value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role">Role <span class="required">*</span></label>
                    <select name="role" id="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_pusat" {{ old('role') == 'admin_pusat' ? 'selected' : '' }}>Admin Pusat</option>
                        <option value="admin_cabang" {{ old('role') == 'admin_cabang' ? 'selected' : '' }}>Admin Cabang</option>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @error('role')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select name="status" id="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row" id="branchField" style="display: none;">
                <div class="form-group">
                    <label for="branch_id">Cabang <span class="required">*</span></label>
                    <select name="branch_id" id="branch_id">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }} ({{ $branch->kota }})
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <small>Field ini wajib diisi untuk role Admin Cabang</small>
                </div>
                <div class="form-group">
                    <!-- Spacer untuk menjaga grid -->
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="password"
                           placeholder="Masukkan password" required>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Konfirmasi password" required>
                    @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- PERMISSIONS SECTION -->
            <div id="permissionsSection" style="display: none;">
                <div class="form-card">
                    <h3>Hak Akses</h3>
                    <small class="text-muted">Pilih hak akses yang akan diberikan kepada user ini</small>

                    <!-- Dashboard -->
                    <div class="permission-category">
                        <h4>Dashboard</h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_dashboard" data-category="dashboard">
                                <span class="checkmark"></span>
                                Lihat Dashboard
                            </label>
                        </div>
                    </div>

                    <!-- Master Data -->
                    <div class="permission-category">
                        <h4>Master Data
                            <button type="button" class="select-all-category btn-sm" data-category="master_data">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_master_data" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Master Data
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_profile_perusahaan" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Profile Perusahaan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_profile_perusahaan" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Profile Perusahaan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_cabang" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Cabang
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_cabang" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Cabang
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_outlet" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Outlet
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_outlet" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Outlet
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_promo" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Promo
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_promo" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Promo
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_kontak" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Kontak
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_kontak" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Kontak
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_artikel" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Artikel
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_artikel" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Artikel
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_armada" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Armada
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_armada" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Armada
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_driver" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Driver
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_driver" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Driver
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_pegawai" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Pegawai
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_pegawai" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Pegawai
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_rute" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Rute
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_rute" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Rute
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_jadwal" data-category="master_data">
                                <span class="checkmark"></span>
                                Lihat Jadwal
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_jadwal" data-category="master_data">
                                <span class="checkmark"></span>
                                Kelola Jadwal
                            </label>
                        </div>
                    </div>

                    <!-- Transaksi -->
                    <div class="permission-category">
                        <h4>Transaksi
                            <button type="button" class="select-all-category btn-sm" data-category="transaksi">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Lihat Transaksi
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartsend_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Lihat Transaksi SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_smartsend_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Kelola Transaksi SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_perjalanan_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Lihat Transaksi Perjalanan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_perjalanan_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Kelola Transaksi Perjalanan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_armada_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Lihat Transaksi Armada
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_armada_transaksi" data-category="transaksi">
                                <span class="checkmark"></span>
                                Kelola Transaksi Armada
                            </label>
                        </div>
                    </div>

                    <!-- SmartSend -->
                    <div class="permission-category">
                        <h4>SmartSend
                            <button type="button" class="select-all-category btn-sm" data-category="smartsend">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartsend" data-category="smartsend">
                                <span class="checkmark"></span>
                                Lihat SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartsend_tiket" data-category="smartsend">
                                <span class="checkmark"></span>
                                Lihat Tiket SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_smartsend_tiket" data-category="smartsend">
                                <span class="checkmark"></span>
                                Kelola Tiket SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartsend_perjalanan" data-category="smartsend">
                                <span class="checkmark"></span>
                                Lihat Perjalanan SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_smartsend_perjalanan" data-category="smartsend">
                                <span class="checkmark"></span>
                                Kelola Perjalanan SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartsend_armada" data-category="smartsend">
                                <span class="checkmark"></span>
                                Lihat Armada SmartSend
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_smartsend_armada" data-category="smartsend">
                                <span class="checkmark"></span>
                                Kelola Armada SmartSend
                            </label>
                        </div>
                    </div>

                    <!-- SmartRent -->
                    <div class="permission-category">
                        <h4>SmartRent
                            <button type="button" class="select-all-category btn-sm" data-category="smartrent">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_smartrent" data-category="smartrent">
                                <span class="checkmark"></span>
                                Lihat SmartRent
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_smartrent" data-category="smartrent">
                                <span class="checkmark"></span>
                                Kelola SmartRent
                            </label>
                        </div>
                    </div>

                    <!-- Laporan -->
                    <div class="permission-category">
                        <h4>Laporan
                            <button type="button" class="select-all-category btn-sm" data-category="laporan">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_laporan" data-category="laporan">
                                <span class="checkmark"></span>
                                Lihat Laporan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_laporan" data-category="laporan">
                                <span class="checkmark"></span>
                                Kelola Laporan
                            </label>
                        </div>
                    </div>

                    <!-- Pengaturan -->
                    <div class="permission-category">
                        <h4>Pengaturan
                            <button type="button" class="select-all-category btn-sm" data-category="pengaturan">Pilih Semua</button>
                        </h4>
                        <div class="permission-grid">
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_pengaturan" data-category="pengaturan">
                                <span class="checkmark"></span>
                                Lihat Pengaturan
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_user" data-category="pengaturan">
                                <span class="checkmark"></span>
                                Lihat User
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_user" data-category="pengaturan">
                                <span class="checkmark"></span>
                                Kelola User
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="view_menu" data-category="pengaturan">
                                <span class="checkmark"></span>
                                Lihat Menu
                            </label>
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="manage_menu" data-category="pengaturan">
                                <span class="checkmark"></span>
                                Kelola Menu
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan User
                </button>
                <button type="reset" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <a href="{{ route('admin.user') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide branch field based on role selection
    const roleSelect = document.getElementById('role');
    const branchField = document.getElementById('branchField');
    const branchSelect = document.getElementById('branch_id');

    function toggleBranchField() {
        if (roleSelect.value === 'admin_cabang') {
            branchField.style.display = 'grid';
            branchSelect.setAttribute('required', 'required');
        } else {
            branchField.style.display = 'none';
            branchSelect.removeAttribute('required');
        }
    }

    roleSelect.addEventListener('change', toggleBranchField);
    toggleBranchField(); // Initial check

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    function validatePassword() {
        if (password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Password tidak cocok');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }

    password.addEventListener('change', validatePassword);
    passwordConfirmation.addEventListener('keyup', validatePassword);

    // Format phone number input
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except plus sign for international numbers
        let value = this.value.replace(/[^\d+]/g, '');

        // If it starts with +, allow it for international numbers
        if (value.startsWith('+')) {
            // Keep the + and following digits
            this.value = value;
        } else {
            // For local numbers, format with dash
            value = value.replace(/\D/g, '');
            if (value.length > 3 && value.length <= 6) {
                this.value = value.slice(0, 3) + '-' + value.slice(3);
            } else if (value.length > 6 && value.length <= 10) {
                this.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
            } else if (value.length > 10) {
                this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
            } else {
                this.value = value;
            }
        }
    });

    // Format NIK input
    const nikInput = document.getElementById('nik');
    nikInput.addEventListener('input', function() {
        // Remove non-numeric characters
        let value = this.value.replace(/\D/g, '');

        // Limit to 16 digits for NIK
        if (value.length > 16) {
            value = value.slice(0, 16);
        }

        // Add dots for readability every 4 digits
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += '.';
            }
            formatted += value[i];
        }

        this.value = formatted;
    });

    // Set minimum date for tanggal_lahir (100 years ago)
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    const today = new Date();
    const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
    const maxDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate()); // Minimum age 17

    tanggalLahirInput.min = minDate.toISOString().split('T')[0];
    tanggalLahirInput.max = maxDate.toISOString().split('T')[0];

    // Handle role change for permissions
    const permissionsSection = document.getElementById('permissionsSection');

    roleSelect.addEventListener('change', function() {
        const selectedRole = this.value;

        // Show/hide permissions section
        if (selectedRole && selectedRole !== 'customer' && selectedRole !== 'driver') {
            permissionsSection.style.display = 'block';
            loadPermissionsForRole(selectedRole);
        } else {
            permissionsSection.style.display = 'none';
            // Clear any checked permissions when role does not use permissions
            document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
        }
    });

    // Function to load permissions for selected role
    function loadPermissionsForRole(role) {
        // Get all permission checkboxes as an array
        const permissionCheckboxes = Array.from(document.querySelectorAll('input[name="permissions[]"]'));

        // Clear all first
        permissionCheckboxes.forEach(cb => cb.checked = false);

        if (role === 'admin_pusat') {
            // Admin pusat: check everything
            permissionCheckboxes.forEach(cb => cb.checked = true);
            return;
        }

        if (role === 'admin_cabang') {
            // Admin cabang defaults:
            // - Master Data: check only `view_` permissions
            // - Transaksi: check all
            // - SmartSend: check all
            // - SmartRent: check all
            // - Laporan: check all
            // - Pengaturan: none
            permissionCheckboxes.forEach(cb => {
                const cat = cb.dataset.category;
                const val = cb.value || '';

                if (cat === 'master_data' && val.startsWith('view_')) {
                    cb.checked = true;
                    return;
                }

                if (['transaksi', 'smartsend', 'smartrent', 'laporan'].includes(cat)) {
                    cb.checked = true;
                    return;
                }
            });
            return;
        }

        // Operator / driver / others: keep existing minimal sensible defaults
        if (role === 'operator') {
            // keep previous operator defaults (check a subset useful for operator)
            const opDefaults = ['view_dashboard','view_master_data','view_outlet','view_promo','manage_promo','view_armada','manage_armada','view_driver','manage_driver','view_rute','manage_rute','view_jadwal','manage_jadwal','view_transaksi','view_smartsend_transaksi','manage_smartsend_transaksi','view_perjalanan_transaksi','manage_perjalanan_transaksi','view_armada_transaksi','manage_armada_transaksi','view_smartsend','view_smartsend_tiket','manage_smartsend_tiket','view_smartsend_perjalanan','manage_smartsend_perjalanan','view_smartsend_armada','manage_smartsend_armada','view_smartrent','manage_smartrent','view_laporan','manage_laporan'];
            permissionCheckboxes.forEach(cb => cb.checked = opDefaults.includes(cb.value));
            return;
        }

        if (role === 'driver') {
            const drvDefaults = ['view_dashboard','view_jadwal'];
            permissionCheckboxes.forEach(cb => cb.checked = drvDefaults.includes(cb.value));
            return;
        }

        // Default: none
    }

    // Select all / deselect all for each category
    document.querySelectorAll('.select-all-category').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            const checkboxes = document.querySelectorAll(`input[name="permissions[]"][data-category="${category}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Pilih Semua' : 'Batal Pilih Semua';
        });
    });
});

// Fungsi untuk reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang.')) {
        document.querySelector('form').reset();

        // Reset branch field visibility
        const roleSelect = document.getElementById('role');
        const branchField = document.getElementById('branchField');
        const branchSelect = document.getElementById('branch_id');
        const permissionsSection = document.getElementById('permissionsSection');

        if (roleSelect.value === 'admin_cabang') {
            branchField.style.display = 'grid';
            branchSelect.setAttribute('required', 'required');
        } else {
            branchField.style.display = 'none';
            branchSelect.removeAttribute('required');
        }

        // Reset permissions section
        if (roleSelect.value && roleSelect.value !== 'customer') {
            permissionsSection.style.display = 'block';
        } else {
            permissionsSection.style.display = 'none';
        }
    }
}
</script>
@endsection
