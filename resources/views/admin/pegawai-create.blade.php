@extends('layouts.app-admin')

@section('title', 'Tambah Pegawai')

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
}
.btn-back:hover {
    background: #5a6268;
}

/* ================= TABS ================= */
.tabs {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}
.tab {
    padding: 10px 0;
    color: #777;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    transition: color 0.3s;
}
.tab.active {
    color: #ff6a00;
}
.tab.active::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #ff6a00;
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
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
.form-group label.required::after {
    content: ' *';
    color: #dc3545;
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
.form-group input.error,
.form-group select.error,
.form-group textarea.error {
    border-color: #dc3545;
}
.form-error {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: none;
}
.form-error.show {
    display: block;
}
textarea {
    resize: none;
    min-height: 80px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}

/* ================= FILE UPLOAD ================= */
.file-upload {
    position: relative;
    display: inline-block;
    width: 100%;
}
.file-upload input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
.file-upload-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 1px dashed #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}
.file-upload-label:hover {
    background: #e9ecef;
    border-color: #1e88e5;
}
.file-upload-label i {
    color: #666;
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
@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .form-grid {
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

    .tabs {
        flex-wrap: wrap;
        gap: 10px;
    }

    .tab {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 15px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .btn-back {
        width: 100%;
        justify-content: center;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= FORM PAGE ================= -->
    <div id="form-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Input Detail Pegawai</h2>
            <a href="{{ route('admin.pegawai') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <!-- TABS -->
        <div class="tabs">
            <a href="{{ route('admin.pegawai') }}" class="tab">Tampilan Hasil</a>
            <div class="tab active">Input Pegawai</div>
        </div>

        <!-- Display success/error messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('admin.pegawai.store') }}" method="POST" enctype="multipart/form-data" id="pegawaiForm">
            @csrf

            <!-- DATA PRIBADI -->
            <div class="form-card">
                <h3>Data Pribadi</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="required">Nama Lengkap</label>
                        <input type="text" name="name" id="name"
                               placeholder="Masukkan Nama Lengkap"
                               value="{{ old('name') }}"
                               required>
                        <div class="form-error" id="error-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="nik" class="required">NIK</label>
                        <input type="text" name="nik" id="nik"
                               placeholder="Masukkan NIK"
                               value="{{ old('nik') }}"
                               required>
                        <div class="form-error" id="error-nik"></div>
                    </div>

                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                               placeholder="Masukkan Tempat Lahir"
                               value="{{ old('tempat_lahir') }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                               value="{{ old('tanggal_lahir') }}">
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin" class="required">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="form-error" id="error-jenis_kelamin"></div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="required">Nomer Telepon</label>
                        <input type="tel" name="phone" id="phone"
                               placeholder="Masukkan Nomer Telepon"
                               value="{{ old('phone') }}"
                               required>
                        <div class="form-error" id="error-phone"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                               placeholder="Masukkan Email"
                               value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="kontak_darurat">Kontak Darurat</label>
                        <input type="tel" name="kontak_darurat" id="kontak_darurat"
                               placeholder="Masukkan Kontak Darurat"
                               value="{{ old('kontak_darurat') }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="agama">Agama</label>
                        <select name="agama" id="agama">
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status_pernikahan">Status</label>
                        <select name="status_pernikahan" id="status_pernikahan">
                            <option value="">Pilih Status</option>
                            <option value="Menikah" {{ old('status_pernikahan') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Belum Menikah" {{ old('status_pernikahan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- DATA KEPEGAWAIAN -->
            <div class="form-card">
                <h3>Data Kepegawaian</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tanggal_bergabung" class="required">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" id="tanggal_bergabung"
                               value="{{ old('tanggal_bergabung') }}"
                               required>
                        <div class="form-error" id="error-tanggal_bergabung"></div>
                    </div>
                    <div class="form-group">
                        <label for="status_pegawai" class="required">Status Pegawai</label>
                        <select name="status_pegawai" id="status_pegawai" required>
                            <option value="">Pilih Status Pegawai</option>
                            <option value="Tetap" {{ old('status_pegawai') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="Kontrak" {{ old('status_pegawai') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang" {{ old('status_pegawai') == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        <div class="form-error" id="error-status_pegawai"></div>
                    </div>
                    <div class="form-group">
                        <label for="masa_kerja">Masa Kerja</label>
                        <input type="text" name="masa_kerja" id="masa_kerja"
                               placeholder="Contoh: 1 Tahun 2 Bulan"
                               value="{{ old('masa_kerja') }}">
                    </div>

                    <div class="form-group">
                        <label for="posisi" class="required">Posisi</label>
                        <select name="posisi" id="posisi" required>
                            <option value="">Pilih Posisi</option>
                            <option value="Admin Pusat" {{ old('posisi') == 'Admin Pusat' ? 'selected' : '' }}>Admin Pusat</option>
                            <option value="Admin Cabang" {{ old('posisi') == 'Admin Cabang' ? 'selected' : '' }}>Admin Cabang</option>
                            <option value="Driver" {{ old('posisi') == 'Driver' ? 'selected' : '' }}>Driver</option>
                            <option value="Manager" {{ old('posisi') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                        <div class="form-error" id="error-posisi"></div>
                    </div>
                    <div class="form-group">
                        <label for="branch_id" class="required">Lokasi Kerja (Cabang)</label>
                        <select name="branch_id" id="branch_id" required>
                            <option value="">Pilih Lokasi Kerja</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama_cabang }} ({{ $branch->kota }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-error" id="error-branch_id"></div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="required">Status aktif</label>
                        <select name="status" id="status" required>
                            <option value="">Pilih Status Aktif</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <div class="form-error" id="error-status"></div>
                    </div>
                </div>
            </div>

            <!-- PENDIDIKAN & KEAHLIAN -->
            <div class="form-card">
                <h3>Pendidikan & Keahlian</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" id="pendidikan_terakhir">
                            <option value="">Pilih Pendidikan</option>
                            <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D1" {{ old('pendidikan_terakhir') == 'D1' ? 'selected' : '' }}>D1</option>
                            <option value="D2" {{ old('pendidikan_terakhir') == 'D2' ? 'selected' : '' }}>D2</option>
                            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="institusi">Institusi</label>
                        <input type="text" name="institusi" id="institusi"
                               placeholder="Masukkan Institusi"
                               value="{{ old('institusi') }}">
                    </div>
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="text" name="tahun_lulus" id="tahun_lulus"
                               placeholder="Masukkan Tahun Lulus"
                               value="{{ old('tahun_lulus') }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="keahlian">Keahlian</label>
                        <input type="text" name="keahlian" id="keahlian"
                               placeholder="Masukkan Keahlian Pegawai (pisahkan dengan koma)"
                               value="{{ old('keahlian') }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="pengalaman_kerja">Pengalaman Kerja</label>
                        <textarea name="pengalaman_kerja" id="pengalaman_kerja"
                                  placeholder="Masukkan Pengalaman Kerja"
                                  rows="3">{{ old('pengalaman_kerja') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- DOKUMEN -->
            <div class="form-card">
                <h3>Dokumen</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="dokumen_ktp">KTP</label>
                        <div class="file-upload">
                            <input type="file" name="dokumen_ktp" id="dokumen_ktp" accept=".jpg,.jpeg,.png,.pdf">
                            <label for="dokumen_ktp" class="file-upload-label">
                                <span>Pilih file</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                        <div class="form-error" id="error-dokumen_ktp"></div>
                    </div>
                    <div class="form-group">
                        <label for="dokumen_ijazah">Ijazah</label>
                        <div class="file-upload">
                            <input type="file" name="dokumen_ijazah" id="dokumen_ijazah" accept=".jpg,.jpeg,.png,.pdf">
                            <label for="dokumen_ijazah" class="file-upload-label">
                                <span>Pilih file</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dokumen_npwp">NPWP</label>
                        <div class="file-upload">
                            <input type="file" name="dokumen_npwp" id="dokumen_npwp" accept=".jpg,.jpeg,.png,.pdf">
                            <label for="dokumen_npwp" class="file-upload-label">
                                <span>Pilih file</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dokumen_skck">SKCK</label>
                        <div class="file-upload">
                            <input type="file" name="dokumen_skck" id="dokumen_skck" accept=".jpg,.jpeg,.png,.pdf">
                            <label for="dokumen_skck" class="file-upload-label">
                                <span>Pilih file</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Profil</label>
                        <div class="file-upload">
                            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png">
                            <label for="foto" class="file-upload-label">
                                <span>Pilih foto</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <button type="reset" class="btn-reset">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>

    </div>

</div>

<script>
// Form validation
document.getElementById('pegawaiForm').addEventListener('submit', function(e) {
    let isValid = true;
    const requiredFields = [
        'id_pegawai', 'nama', 'nik', 'jenis_kelamin',
        'telepon', 'tanggal_bergabung', 'status_pegawai',
        'posisi', 'branch_id', 'status'
    ];

    // Clear previous errors
    requiredFields.forEach(field => {
        const errorElement = document.getElementById(`error-${field}`);
        const inputElement = document.getElementById(field);

        if (errorElement) errorElement.classList.remove('show');
        if (inputElement) inputElement.classList.remove('error');
    });

    // Validate required fields
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
                const errorElement = document.getElementById(`error-${field}`);
                if (errorElement) {
                    errorElement.textContent = 'Field ini wajib diisi';
                    errorElement.classList.add('show');
                }
            }
        }
    });

    // Validate email if provided
    const email = document.getElementById('email');
    if (email.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            isValid = false;
            email.classList.add('error');
            const errorElement = document.getElementById('error-email');
            if (errorElement) {
                errorElement.textContent = 'Format email tidak valid';
                errorElement.classList.add('show');
            }
        }
    }

    if (!isValid) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Harap periksa kembali form yang Anda isi',
            timer: 3000,
            showConfirmButton: false
        });
    }
});

// File input preview
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const label = this.nextElementSibling;
        const fileName = this.files[0] ? this.files[0].name : 'Pilih file';
        label.querySelector('span').textContent = fileName;
    });
});

// Date formatting
document.getElementById('tanggal_lahir').addEventListener('change', function() {
    if (this.value) {
        const date = new Date(this.value);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        this.value = `${year}-${month}-${day}`;
    }
});

// Reset form
document.querySelector('.btn-reset').addEventListener('click', function() {
    document.querySelectorAll('.form-error').forEach(error => {
        error.classList.remove('show');
    });
    document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(input => {
        input.classList.remove('error');
    });

    Swal.fire({
        title: 'Reset Form?',
        text: "Semua data yang telah diisi akan dihapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff6a00',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('pegawaiForm').reset();
            document.querySelectorAll('.file-upload-label span').forEach(span => {
                span.textContent = 'Pilih file';
            });
        }
    });
});

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
