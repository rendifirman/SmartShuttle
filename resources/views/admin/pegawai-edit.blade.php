@extends('layouts.app-admin')

@section('title', 'Edit Pegawai')

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
.btn-save {
    background: #28a745;
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
.btn-save:hover {
    background: #218838;
}

/* ================= FORM ================= */
.form-container {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 25px;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    color: #333;
    font-weight: 500;
}
.form-control {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}
.form-control:focus {
    border-color: #1e88e5;
    outline: none;
}
.form-control[readonly] {
    background-color: #f5f5f5;
    cursor: not-allowed;
}
.form-text {
    margin-top: 5px;
    font-size: 12px;
    color: #777;
}

/* File Upload Styles */
.file-upload-container {
    margin-top: 10px;
}
.file-upload-label {
    display: inline-block;
    background: #e9ecef;
    color: #495057;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s;
}
.file-upload-label:hover {
    background: #dee2e6;
}
.file-name {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
}
.current-file {
    margin-top: 8px;
    font-size: 13px;
    color: #28a745;
}

/* Skills Input */
.skills-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}
.skill-tag {
    background: #e9f1ff;
    color: #3366cc;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.skill-tag-remove {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    font-size: 12px;
    padding: 0;
}

/* Alert Messages */
.alert {
    padding: 12px 15px;
    border-radius: 8px;
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
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ================= PROFILE PREVIEW ================= */
.profile-preview {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e0e0e0;
}
.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.profile-info h3 {
    margin: 0 0 5px 0;
    font-size: 20px;
    color: #0b2a4a;
}
.profile-badge {
    display: inline-block;
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .page-header-actions {
        display: flex;
        gap: 10px;
        width: 100%;
    }

    .btn-back, .btn-save {
        flex: 1;
        justify-content: center;
    }

    .profile-preview {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 15px;
    }

    .form-container {
        padding: 20px;
    }

    .form-control {
        padding: 10px 12px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= EDIT PAGE ================= -->
    <div id="edit-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Edit Pegawai</h2>
            <div class="page-header-actions">
                <a href="{{ route('admin.pegawai.show', $pegawai->id) }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Detail
                </a>
                <button type="submit" form="editPegawaiForm" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <!-- Display messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Mohon periksa kesalahan di bawah ini:
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- PROFILE PREVIEW -->
        <div class="profile-preview">
            <div class="profile-avatar">
                <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : 'https://i.pravatar.cc/100?u=' . $pegawai->id }}"
                     alt="{{ $pegawai->name }}"
                     id="avatarPreview">
            </div>
            <div class="profile-info">
                <h3>{{ $pegawai->name }}</h3>
                <span class="profile-badge">{{ $pegawai->posisi }}</span>
                <div style="margin-top: 10px; font-size: 13px; color: #666;">
                    ID: {{ $pegawai->id }} |
                    NIK: {{ $pegawai->nik }} |
                    Status: {{ $pegawai->status }}
                </div>
            </div>
        </div>

        <!-- EDIT FORM -->
        <form id="editPegawaiForm" method="POST" action="{{ route('admin.pegawai.update', $pegawai->id) }}"
              enctype="multipart/form-data" class="form-container">
            @csrf
            @method('PUT')

            <div class="form-row">
                <!-- ID Pegawai -->
                <div class="form-group">
                    <label class="form-label" for="id">ID Pegawai</label>
                    <input type="text" id="id" name="id"
                        value="{{ old('id', $pegawai->id) }}"
                        class="form-control" readonly style="background-color: #f5f5f5;">
                    <div class="form-text">ID Pegawai (auto-generate, tidak dapat diubah)</div>
                </div>

                <!-- Nama Lengkap -->
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $pegawai->name) }}"
                           class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <!-- NIK -->
                <div class="form-group">
                    <label class="form-label" for="nik">NIK *</label>
                    <input type="text" id="nik" name="nik"
                           value="{{ old('nik', $pegawai->nik) }}"
                           class="form-control" required maxlength="16">
                    <div class="form-text">Nomor Induk Kependudukan 16 digit</div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $pegawai->email) }}"
                           class="form-control">
                </div>
            </div>

            <div class="form-row">
                <!-- Tempat Lahir -->
                <div class="form-group">
                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                           class="form-control">
                </div>

                <!-- Tanggal Lahir -->
                <div class="form-group">
                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d') : '') }}"
                           class="form-control">
                </div>
            </div>

            <div class="form-row">
                <!-- Jenis Kelamin -->
                <div class="form-group">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin *</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                <!-- Telepon -->
                <div class="form-group">
                    <label class="form-label" for="phone">Telepon *</label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone', $pegawai->phone) }}"
                           class="form-control" required>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label class="form-label" for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat', $pegawai->alamat) }}</textarea>
            </div>

            <div class="form-row">
                <!-- Kontak Darurat -->
                <div class="form-group">
                    <label class="form-label" for="kontak_darurat">Kontak Darurat</label>
                    <input type="text" id="kontak_darurat" name="kontak_darurat"
                           value="{{ old('kontak_darurat', $pegawai->kontak_darurat) }}"
                           class="form-control">
                </div>

                <!-- Agama -->
                <div class="form-group">
                    <label class="form-label" for="agama">Agama</label>
                    <select id="agama" name="agama" class="form-control">
                        <option value="">Pilih Agama</option>
                        <option value="Islam" {{ old('agama', $pegawai->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('agama', $pegawai->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ old('agama', $pegawai->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama', $pegawai->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama', $pegawai->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama', $pegawai->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <!-- Status Pernikahan -->
                <div class="form-group">
                    <label class="form-label" for="status_pernikahan">Status Pernikahan</label>
                    <select id="status_pernikahan" name="status_pernikahan" class="form-control">
                        <option value="">Pilih Status</option>
                        <option value="Belum Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>
                            Belum Menikah
                        </option>
                        <option value="Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>
                            Menikah
                        </option>
                        <option value="Cerai" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Cerai' ? 'selected' : '' }}>
                            Cerai
                        </option>
                    </select>
                </div>

                <!-- Tanggal Bergabung -->
                <div class="form-group">
                    <label class="form-label" for="tanggal_bergabung">Tanggal Bergabung *</label>
                    <input type="date" id="tanggal_bergabung" name="tanggal_bergabung"
                           value="{{ old('tanggal_bergabung', $pegawai->tanggal_bergabung ? \Carbon\Carbon::parse($pegawai->tanggal_bergabung)->format('Y-m-d') : '') }}"
                           class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <!-- Status Pegawai -->
                <div class="form-group">
                    <label class="form-label" for="status_pegawai">Status Pegawai *</label>
                    <select id="status_pegawai" name="status_pegawai" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="Tetap" {{ old('status_pegawai', $pegawai->status_pegawai) == 'Tetap' ? 'selected' : '' }}>
                            Tetap
                        </option>
                        <option value="Kontrak" {{ old('status_pegawai', $pegawai->status_pegawai) == 'Kontrak' ? 'selected' : '' }}>
                            Kontrak
                        </option>
                        <option value="Magang" {{ old('status_pegawai', $pegawai->status_pegawai) == 'Magang' ? 'selected' : '' }}>
                            Magang
                        </option>
                    </select>
                </div>

                <!-- Posisi -->
                <div class="form-group">
                    <label class="form-label" for="posisi">Posisi *</label>
                    <select id="posisi" name="posisi" class="form-control" required>
                        <option value="">Pilih Posisi</option>
                        <option value="Admin Pusat" {{ old('posisi', $pegawai->posisi) == 'Admin Pusat' ? 'selected' : '' }}>
                            Admin Pusat
                        </option>
                        <option value="Admin Cabang" {{ old('posisi', $pegawai->posisi) == 'Admin Cabang' ? 'selected' : '' }}>
                            Admin Cabang
                        </option>
                        <option value="Driver" {{ old('posisi', $pegawai->posisi) == 'Driver' ? 'selected' : '' }}>
                            Driver
                        </option>
                        <option value="Manager" {{ old('posisi', $pegawai->posisi) == 'Manager' ? 'selected' : '' }}>
                            Manager
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <!-- Lokasi Kerja -->
                <div class="form-group">
                    <label class="form-label" for="lokasi_kerja">Lokasi Kerja *</label>
                    <input type="text" id="lokasi_kerja" name="lokasi_kerja"
                           value="{{ old('lokasi_kerja', $pegawai->lokasi_kerja) }}"
                           class="form-control" required>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="form-label" for="status">Status *</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="Aktif" {{ old('status', $pegawai->status) == 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="Non-Aktif" {{ old('status', $pegawai->status) == 'Non-Aktif' ? 'selected' : '' }}>
                            Non-Aktif
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <!-- Pendidikan Terakhir -->
                <div class="form-group">
                    <label class="form-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                    <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control">
                        <option value="">Pilih Pendidikan</option>
                        <option value="SD" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'D1' ? 'selected' : '' }}>D1</option>
                        <option value="D2" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'D2' ? 'selected' : '' }}>D2</option>
                        <option value="D3" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="S1" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>

                <!-- Institusi -->
                <div class="form-group">
                    <label class="form-label" for="institusi">Institusi</label>
                    <input type="text" id="institusi" name="institusi"
                           value="{{ old('institusi', $pegawai->institusi) }}"
                           class="form-control">
                </div>
            </div>

            <div class="form-row">
                <!-- Tahun Lulus -->
                <div class="form-group">
                    <label class="form-label" for="tahun_lulus">Tahun Lulus</label>
                    <input type="number" id="tahun_lulus" name="tahun_lulus"
                           value="{{ old('tahun_lulus', $pegawai->tahun_lulus) }}"
                           class="form-control" min="1950" max="{{ date('Y') }}">
                </div>

                <!-- Masa Kerja -->
                <div class="form-group">
                    <label class="form-label" for="masa_kerja">Masa Kerja</label>
                    <input type="text" id="masa_kerja" name="masa_kerja"
                           value="{{ old('masa_kerja', $pegawai->masa_kerja) }}"
                           class="form-control" placeholder="Contoh: 2 Tahun 5 Bulan">
                </div>
            </div>

            <!-- Keahlian -->
            <div class="form-group">
                <label class="form-label" for="keahlian_input">Keahlian</label>
                <div class="skills-container" id="skillsContainer">
                    @if($pegawai->keahlian)
                        @foreach(explode(',', $pegawai->keahlian) as $skill)
                            @if(trim($skill))
                                <span class="skill-tag">
                                    {{ trim($skill) }}
                                    <button type="button" class="skill-tag-remove" onclick="removeSkill(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <input type="text" id="keahlian_input" class="form-control"
                           style="flex: 1;" placeholder="Tambahkan keahlian...">
                    <button type="button" onclick="addSkill()" class="btn-save" style="padding: 8px 15px;">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
                <input type="hidden" name="keahlian" id="keahlian_hidden"
                       value="{{ old('keahlian', $pegawai->keahlian) }}">
            </div>

            <!-- Pengalaman Kerja -->
            <div class="form-group">
                <label class="form-label" for="pengalaman_kerja">Pengalaman Kerja</label>
                <textarea id="pengalaman_kerja" name="pengalaman_kerja" class="form-control" rows="4">{{ old('pengalaman_kerja', $pegawai->pengalaman_kerja) }}</textarea>
            </div>

            <!-- Dokumen Upload Section -->
            <h3 style="margin-top: 30px; margin-bottom: 20px; color: #0b2a4a; font-size: 18px; border-bottom: 2px solid #ff6a00; padding-bottom: 10px;">
                Upload Dokumen
            </h3>

            <div class="form-row">
                <!-- Foto -->
                <div class="form-group">
                    <label class="form-label" for="foto">Foto Profil</label>
                    <div class="file-upload-container">
                        <label for="foto" class="file-upload-label">
                            <i class="fas fa-camera"></i> Upload Foto
                        </label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="d-none" onchange="previewImage(this)">
                        @if($pegawai->foto)
                            <div class="current-file">
                                <i class="fas fa-check-circle"></i> File saat ini: {{ basename($pegawai->foto) }}
                            </div>
                        @endif
                        <div class="file-name" id="fotoFileName"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <!-- Dokumen KTP -->
                <div class="form-group">
                    <label class="form-label" for="dokumen_ktp">Dokumen KTP</label>
                    <div class="file-upload-container">
                        <label for="dokumen_ktp" class="file-upload-label">
                            <i class="fas fa-file-upload"></i> Upload KTP
                        </label>
                        <input type="file" id="dokumen_ktp" name="dokumen_ktp"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none"
                               onchange="updateFileName(this, 'ktpFileName')">
                        @if($pegawai->dokumen_ktp)
                            <div class="current-file">
                                <i class="fas fa-check-circle"></i> File saat ini: {{ basename($pegawai->dokumen_ktp) }}
                            </div>
                        @endif
                        <div class="file-name" id="ktpFileName"></div>
                    </div>
                </div>

                <!-- Dokumen Ijazah -->
                <div class="form-group">
                    <label class="form-label" for="dokumen_ijazah">Dokumen Ijazah</label>
                    <div class="file-upload-container">
                        <label for="dokumen_ijazah" class="file-upload-label">
                            <i class="fas fa-file-upload"></i> Upload Ijazah
                        </label>
                        <input type="file" id="dokumen_ijazah" name="dokumen_ijazah"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none"
                               onchange="updateFileName(this, 'ijazahFileName')">
                        @if($pegawai->dokumen_ijazah)
                            <div class="current-file">
                                <i class="fas fa-check-circle"></i> File saat ini: {{ basename($pegawai->dokumen_ijazah) }}
                            </div>
                        @endif
                        <div class="file-name" id="ijazahFileName"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <!-- Dokumen NPWP -->
                <div class="form-group">
                    <label class="form-label" for="dokumen_npwp">Dokumen NPWP</label>
                    <div class="file-upload-container">
                        <label for="dokumen_npwp" class="file-upload-label">
                            <i class="fas fa-file-upload"></i> Upload NPWP
                        </label>
                        <input type="file" id="dokumen_npwp" name="dokumen_npwp"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none"
                               onchange="updateFileName(this, 'npwpFileName')">
                        @if($pegawai->dokumen_npwp)
                            <div class="current-file">
                                <i class="fas fa-check-circle"></i> File saat ini: {{ basename($pegawai->dokumen_npwp) }}
                            </div>
                        @endif
                        <div class="file-name" id="npwpFileName"></div>
                    </div>
                </div>

                <!-- Dokumen SKCK -->
                <div class="form-group">
                    <label class="form-label" for="dokumen_skck">Dokumen SKCK</label>
                    <div class="file-upload-container">
                        <label for="dokumen_skck" class="file-upload-label">
                            <i class="fas fa-file-upload"></i> Upload SKCK
                        </label>
                        <input type="file" id="dokumen_skck" name="dokumen_skck"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none"
                               onchange="updateFileName(this, 'skckFileName')">
                        @if($pegawai->dokumen_skck)
                            <div class="current-file">
                                <i class="fas fa-check-circle"></i> File saat ini: {{ basename($pegawai->dokumen_skck) }}
                            </div>
                        @endif
                        <div class="file-name" id="skckFileName"></div>
                    </div>
                </div>
            </div>

        </form>

    </div>

</div>

<script>
// Skills Management
function addSkill() {
    const input = document.getElementById('keahlian_input');
    const skill = input.value.trim();

    if (!skill) {
        alert('Mohon masukkan keahlian');
        return;
    }

    // Check if skill already exists
    const existingSkills = document.querySelectorAll('.skill-tag');
    for (let tag of existingSkills) {
        if (tag.textContent.replace('×', '').trim() === skill) {
            alert('Keahlian ini sudah ditambahkan');
            input.value = '';
            return;
        }
    }

    // Create skill tag
    const tag = document.createElement('span');
    tag.className = 'skill-tag';
    tag.innerHTML = `${skill} <button type="button" class="skill-tag-remove" onclick="removeSkill(this)"><i class="fas fa-times"></i></button>`;

    document.getElementById('skillsContainer').appendChild(tag);
    updateSkillsHiddenInput();

    input.value = '';
}

function removeSkill(button) {
    button.parentElement.remove();
    updateSkillsHiddenInput();
}

function updateSkillsHiddenInput() {
    const skills = [];
    document.querySelectorAll('.skill-tag').forEach(tag => {
        const skillText = tag.textContent.replace('×', '').trim();
        if (skillText) {
            skills.push(skillText);
        }
    });
    document.getElementById('keahlian_hidden').value = skills.join(', ');
}

// File Upload Functions
function updateFileName(input, targetId) {
    if (input.files.length > 0) {
        document.getElementById(targetId).textContent = 'File dipilih: ' + input.files[0].name;
    } else {
        document.getElementById(targetId).textContent = '';
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
        document.getElementById('fotoFileName').textContent = 'File dipilih: ' + input.files[0].name;
    }
}

// Initialize form file labels
document.querySelectorAll('.file-upload-label').forEach(label => {
    const input = label.nextElementSibling;
    label.addEventListener('click', () => input.click());
});

// Form validation
document.getElementById('editPegawaiForm').addEventListener('submit', function(e) {
    const requiredFields = ['id_pegawai', 'nama', 'nik', 'jenis_kelamin', 'telepon',
                           'tanggal_bergabung', 'status_pegawai', 'posisi', 'lokasi_kerja', 'status'];

    let isValid = true;
    let firstInvalidField = null;

    for (let field of requiredFields) {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            if (!firstInvalidField) {
                firstInvalidField = input;
            }
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    }

    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi (ditandai dengan *)');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
    }
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
