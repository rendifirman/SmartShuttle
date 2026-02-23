@extends('layouts.app-driver')

@section('title', 'Edit Profile Driver - Smart Shuttle')

@push('styles')
<style>
    .profile-card {
        background: #0d3559;
        color: white;
        margin-top: 20px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 30px;
        margin-bottom: 30px;
    }

    .profile-photo {
        flex-shrink: 0;
    }

    .profile-photo img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 8px;
    }

    .profile-id-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .profile-id {
        font-size: 16px;
        opacity: 0.9;
    }

    .back-btn {
        background: #ff6a00;
        color: white;
        border: none;
        padding: 6px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .back-btn:hover {
        background: #e55e00;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .profile-status {
        display: inline-block;
        background: #2ecc71;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 14px;
        margin-top: 5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-top: 30px;
        justify-items: center;
    }

    .form-column {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: center;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 85%;
    }

    .form-group label {
        font-size: 14px;
        opacity: 0.9;
        display: block;
        margin-bottom: 8px;
        width: 100%;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        margin-top: 6px;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 15px;
        background: white;
        box-sizing: border-box;
        transition: all 0.3s ease;
        color: #333;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        box-shadow: 0 0 0 2px #ff6a00;
        border-color: #ff6a00;
    }

    .form-group input[readonly] {
        background: #e9ecef;
        cursor: not-allowed;
        color: #666;
    }

    .upload-section {
        margin-top: 8px;
        width: 100%;
    }

    .upload-box {
        background: white;
        padding: 12px;
        border-radius: 8px;
        color: black;
        text-align: center;
        font-size: 14px;
        cursor: pointer;
        border: 2px dashed #ddd;
        width: 100%;
        box-sizing: border-box;
        position: relative;
        transition: all 0.3s ease;
    }

    .upload-box:hover {
        background: #f8f9fa;
        border-color: #0d3559;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-name {
        margin-top: 5px;
        font-size: 12px;
        color: #2ecc71;
        display: none;
    }

    .existing-file {
        margin-top: 8px;
        padding: 8px 12px;
        background: #e8f5e9;
        border-radius: 4px;
        font-size: 12px;
        color: #2e7d32;
    }

    .existing-file a {
        color: #1976d2;
        text-decoration: none;
        margin-left: 8px;
    }

    .save-btn {
        display: block;
        margin: 35px auto 0 auto;
        background: white;
        color: #0d3559;
        padding: 12px 35px;
        border-radius: 25px;
        border: none;
        font-size: 15px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .save-btn:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .save-btn:disabled {
        background: #cccccc;
        cursor: not-allowed;
        transform: none;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 20px;
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

    .error-text {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .required-asterisk {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 25px;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-group {
            width: 100%;
        }

        .profile-id-section {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')

<h2>Edit Profile Driver</h2>
<hr>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-photo">
            @if ($driver->photo_file && Storage::disk('public')->exists($driver->photo_file))
                <img src="{{ Storage::url($driver->photo_file) }}" alt="Foto Profil">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Foto Default">
            @endif
        </div>
        <div class="profile-info">
            <h2>{{ $driver->name }}</h2>
            <div class="profile-id-section">
                <div class="profile-id">ID Pengemudi: {{ $driver->id_pengemudi ?? 'Belum ada' }}</div>
                <a href="{{ route('driver.profile') }}" class="back-btn">← Kembali</a>
            </div>
            <div class="profile-status">{{ ucfirst($driver->status) }}</div>
        </div>
    </div>

    <form action="{{ route('driver.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf

        <div class="form-grid">
            <!-- KOLOM KIRI -->
            <div class="form-column">
                <div class="form-group">
                    <label>Nama Lengkap <span class="required-asterisk">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $driver->name) }}" required>
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email <span class="required-asterisk">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $driver->email) }}" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nomor Telepon <span class="required-asterisk">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" required>
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>NIK (16 digit) <span class="required-asterisk">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $driver->nik) }}" maxlength="16" required>
                    @error('nik')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Upload KTP<br><small>.JPG/PNG Max 5MB</small></label>
                    <div class="upload-section">
                        <div class="upload-box" id="ktpUploadBox">
                            <span>Upload File KTP</span>
                            <input type="file" class="file-input" name="ktp_file" id="ktpInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'ktpFileName')">
                        </div>
                        <div class="file-name" id="ktpFileName"></div>
                        @if ($driver->ktp_file && Storage::disk('public')->exists($driver->ktp_file))
                            <div class="existing-file">
                                ✓ File sudah diupload
                                <a href="{{ Storage::url($driver->ktp_file) }}" target="_blank">Lihat</a>
                            </div>
                        @endif
                        @error('ktp_file')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="form-column">
                <div class="form-group">
                    <label>Tanggal Bergabung</label>
                    <input type="text" value="{{ $driver->created_at->format('d F Y') }}" readonly>
                </div>

                <div class="form-group">
                    <label>ID Pengemudi</label>
                    <input type="text" value="{{ $driver->id_pengemudi ?? 'Akan dibuat otomatis' }}" readonly>
                </div>

                <div class="form-group">
                    <label>Nomor SIM <span class="required-asterisk">*</span></label>
                    <input type="text" name="nomor_sim" value="{{ old('nomor_sim', $driver->nomor_sim) }}" required>
                    @error('nomor_sim')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Masa Berlaku SIM <span class="required-asterisk">*</span></label>
                    <input type="date" name="masa_berlaku_sim" value="{{ old('masa_berlaku_sim', $driver->masa_berlaku_sim ? $driver->masa_berlaku_sim->format('Y-m-d') : '') }}" required>
                    @error('masa_berlaku_sim')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Upload SIM<br><small>.JPG/PNG Max 5MB</small></label>
                    <div class="upload-section">
                        <div class="upload-box" id="simUploadBox">
                            <span>Upload File SIM</span>
                            <input type="file" class="file-input" name="sim_file" id="simInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'simFileName')">
                        </div>
                        <div class="file-name" id="simFileName"></div>
                        @if ($driver->sim_file && Storage::disk('public')->exists($driver->sim_file))
                            <div class="existing-file">
                                ✓ File sudah diupload
                                <a href="{{ Storage::url($driver->sim_file) }}" target="_blank">Lihat</a>
                            </div>
                        @endif
                        @error('sim_file')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="save-btn">Simpan Perubahan</button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function handleFileUpload(input, fileNameId) {
        const file = input.files[0];
        const fileNameElement = document.getElementById(fileNameId);

        if (file) {
            // Validasi ukuran file (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                input.value = '';
                return;
            }

            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung! Hanya JPG/PNG yang diperbolehkan.');
                input.value = '';
                return;
            }

            // Tampilkan nama file
            fileNameElement.textContent = `File terpilih: ${file.name}`;
            fileNameElement.style.display = 'block';

            // Ubah tampilan upload box
            const uploadBox = input.parentElement;
            uploadBox.style.backgroundColor = '#e8f5e8';
            uploadBox.style.borderColor = '#2ecc71';
            uploadBox.querySelector('span').textContent = 'File Terpilih';
            uploadBox.querySelector('span').style.color = '#2ecc71';
        }
    }

    // Drag and drop functionality
    document.querySelectorAll('.upload-box').forEach(box => {
        box.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f0f8ff';
            this.style.borderColor = '#0d3559';
        });

        box.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.backgroundColor = 'white';
            this.style.borderColor = '#ddd';
        });

        box.addEventListener('drop', function(e) {
            e.preventDefault();
            const input = this.querySelector('.file-input');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                const fileNameId = input.id === 'ktpInput' ? 'ktpFileName' : 'simFileName';
                handleFileUpload(input, fileNameId);
            }
        });
    });
</script>
@endpush
