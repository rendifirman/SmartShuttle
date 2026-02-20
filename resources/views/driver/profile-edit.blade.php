@extends('layouts.app-driver')

@section('title', 'Edit Profile Driver - Smart Shuttle')

@push('styles')
<style>
    /* ======== PROFILE CARD ======== */
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
        position: relative;
    }

    .profile-photo img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        transition: all 0.3s ease;
    }

    .profile-photo-edit {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #ff6a00;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .profile-photo-edit:hover {
        background: #e55e00;
        transform: scale(1.1);
    }

    .profile-photo-edit i {
        color: white;
        font-size: 18px;
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

    .back-to-profile-btn {
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

    .back-to-profile-btn:hover {
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
    }

    .form-group input {
        width: 100%;
        margin-top: 6px;
        padding: 12px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        background: white;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        box-shadow: 0 0 0 2px #ff6a00;
    }

    .form-group input:read-only {
        background: #f5f5f5;
        cursor: not-allowed;
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
        border: 1px dashed #ddd;
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

    .current-file {
        margin-top: 10px;
        width: 100%;
        border-radius: 4px;
        overflow: hidden;
    }

    .current-file img {
        width: 100%;
        max-height: 100px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .file-info {
        font-size: 12px;
        color: #2ecc71;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .remove-file {
        color: #ff4d4d;
        cursor: pointer;
        font-size: 12px;
        text-decoration: underline;
    }

    .remove-file:hover {
        color: #ff0000;
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
        box-shadow: none;
    }

    /* Alert Messages */
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Error Text */
    .error-text {
        color: #ff4d4d;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    /* Responsif untuk profile */
    @media (max-width: 1024px) {
        .form-grid {
            gap: 30px;
        }
        
        .form-group {
            width: 90%;
        }
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

    @media (max-width: 480px) {
        .profile-card {
            padding: 20px;
        }
        
        .profile-photo img {
            width: 100px;
            height: 100px;
        }
        
        .profile-info h2 {
            font-size: 20px;
        }
        
        .save-btn {
            width: 100%;
            padding: 12px 20px;
        }
    }
</style>
@endpush

@section('content')
<h2>Edit Profile Driver</h2>
<hr>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-error">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="profile-card">
    <!-- PROFILE HEADER DENGAN FOTO DI SAMPING -->
    <div class="profile-header">
        <div class="profile-photo">
            @if($driver && $driver->avatar)
                <img src="{{ Storage::url($driver->avatar) }}" alt="Profile Photo" id="profilePhotoDisplay">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Photo" id="profilePhotoDisplay">
            @endif
            <div class="profile-photo-edit" id="editPhotoBtn">
                <i class="fas fa-camera"></i>
            </div>
            <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;" name="avatar">
        </div>
        <div class="profile-info">
            <h2>{{ $driver ? $driver->name : 'Dimas Mahendra' }}</h2>
            <div class="profile-id-section">
                <div class="profile-id">ID Pengemudi: {{ $driver ? $driver->driver_id : 'DRV-2023-001' }}</div>
                <a href="{{ route('driver.profile') }}" class="back-to-profile-btn">← Kembali ke Profile</a>
            </div>
            <div class="profile-status">{{ $driver && $driver->status == 'active' ? 'Aktif' : 'Non-Aktif' }}</div>
        </div>
    </div>

    @if($driver)
    <form action="{{ route('driver.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <!-- KOLOM KIRI -->
            <div class="form-column">
                <div class="form-group">
                    <label>Nama Lengkap <span style="color: #ff4d4d">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $driver->name) }}" required>
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email <span style="color: #ff4d4d">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $driver->email) }}" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nomor Telepon <span style="color: #ff4d4d">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" required>
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>NIK (16 digit) <span style="color: #ff4d4d">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $driver->nik) }}" required maxlength="16">
                    @error('nik')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Upload KTP (Opsional)<br><small>.JPG/PNG Max 5MB</small></label>
                    <div class="upload-section">
                        <div class="upload-box" id="ktpUploadBox">
                            <span>Upload File KTP</span>
                            <input type="file" class="file-input" name="ktp_file" id="ktpInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'ktpFileName', 'ktpPreview')">
                        </div>
                        <div class="file-name" id="ktpFileName"></div>
                        
                        @if($driver->ktp_file)
                        <div class="current-file" id="currentKtpContainer">
                            <img src="{{ Storage::url($driver->ktp_file) }}" alt="Current KTP" id="ktpPreview">
                            <div class="file-info">
                                <span>File saat ini: {{ basename($driver->ktp_file) }}</span>
                                <span class="remove-file" onclick="removeFile('ktp')">Hapus</span>
                            </div>
                        </div>
                        @else
                        <div class="current-file" id="currentKtpContainer" style="display: none;">
                            <img src="" alt="KTP Preview" id="ktpPreview">
                            <div class="file-info">
                                <span id="ktpFileNameText"></span>
                            </div>
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
                    <label>Tanggal Bergabung <span style="color: #ff4d4d">*</span></label>
                    <input type="date" name="join_date" value="{{ old('join_date', $driver->join_date ? \Carbon\Carbon::parse($driver->join_date)->format('Y-m-d') : '2023-01-12') }}" required>
                    @error('join_date')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>ID Pengemudi</label>
                    <input type="text" value="{{ $driver->driver_id }}" readonly disabled>
                    <small style="color: #ccc;">ID Driver tidak dapat diubah</small>
                </div>

                <div class="form-group">
                    <label>Nomor SIM <span style="color: #ff4d4d">*</span></label>
                    <input type="text" name="sim_number" value="{{ old('sim_number', $driver->sim_number) }}" required>
                    @error('sim_number')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Masa Berlaku SIM <span style="color: #ff4d4d">*</span></label>
                    <input type="date" name="sim_expiry_date" value="{{ old('sim_expiry_date', $driver->sim_expiry_date ? \Carbon\Carbon::parse($driver->sim_expiry_date)->format('Y-m-d') : '2027-01-12') }}" required>
                    @error('sim_expiry_date')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Upload SIM (Opsional)<br><small>.JPG/PNG Max 5MB</small></label>
                    <div class="upload-section">
                        <div class="upload-box" id="simUploadBox">
                            <span>Upload File SIM</span>
                            <input type="file" class="file-input" name="sim_file" id="simInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'simFileName', 'simPreview')">
                        </div>
                        <div class="file-name" id="simFileName"></div>
                        
                        @if($driver->sim_file)
                        <div class="current-file" id="currentSimContainer">
                            <img src="{{ Storage::url($driver->sim_file) }}" alt="Current SIM" id="simPreview">
                            <div class="file-info">
                                <span>File saat ini: {{ basename($driver->sim_file) }}</span>
                                <span class="remove-file" onclick="removeFile('sim')">Hapus</span>
                            </div>
                        </div>
                        @else
                        <div class="current-file" id="currentSimContainer" style="display: none;">
                            <img src="" alt="SIM Preview" id="simPreview">
                            <div class="file-info">
                                <span id="simFileNameText"></span>
                            </div>
                        </div>
                        @endif
                        
                        @error('sim_file')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden input untuk menghapus file -->
        <input type="hidden" name="remove_ktp" id="removeKtp" value="0">
        <input type="hidden" name="remove_sim" id="removeSim" value="0">
        
        <button type="submit" class="save-btn" id="saveBtn">Simpan Perubahan</button>
    </form>
    @else
    <div style="text-align: center; padding: 40px;">
        <p style="font-size: 18px; margin-bottom: 20px;">Data driver tidak ditemukan</p>
        <a href="{{ route('driver.profile') }}" class="back-to-profile-btn">← Kembali ke Profile</a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Profile photo upload
    document.getElementById('editPhotoBtn').addEventListener('click', function() {
        document.getElementById('profilePhotoInput').click();
    });

    document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && isValidImage(file)) {
            // Validasi ukuran
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profilePhotoDisplay').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Fungsi untuk handle upload file dengan preview
    function handleFileUpload(input, fileNameId, previewId) {
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
            
            // Preview foto
            const reader = new FileReader();
            reader.onload = function(event) {
                // Tentukan container dan preview berdasarkan ID
                if (previewId === 'ktpPreview') {
                    const container = document.getElementById('currentKtpContainer');
                    const preview = document.getElementById('ktpPreview');
                    preview.src = event.target.result;
                    container.style.display = 'block';
                    
                    // Update file info
                    const fileInfo = container.querySelector('.file-info span:first-child');
                    if (fileInfo) {
                        fileInfo.textContent = `File baru: ${file.name}`;
                    }
                } else if (previewId === 'simPreview') {
                    const container = document.getElementById('currentSimContainer');
                    const preview = document.getElementById('simPreview');
                    preview.src = event.target.result;
                    container.style.display = 'block';
                    
                    // Update file info
                    const fileInfo = container.querySelector('.file-info span:first-child');
                    if (fileInfo) {
                        fileInfo.textContent = `File baru: ${file.name}`;
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Fungsi untuk menghapus file
    function removeFile(type) {
        if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            if (type === 'ktp') {
                document.getElementById('currentKtpContainer').style.display = 'none';
                document.getElementById('ktpInput').value = '';
                document.getElementById('removeKtp').value = '1';
                
                // Reset upload box
                const uploadBox = document.getElementById('ktpUploadBox');
                uploadBox.style.backgroundColor = 'white';
                uploadBox.style.borderColor = '#ddd';
                uploadBox.querySelector('span').textContent = 'Upload File KTP';
                uploadBox.querySelector('span').style.color = 'black';
                document.getElementById('ktpFileName').style.display = 'none';
                
            } else if (type === 'sim') {
                document.getElementById('currentSimContainer').style.display = 'none';
                document.getElementById('simInput').value = '';
                document.getElementById('removeSim').value = '1';
                
                // Reset upload box
                const uploadBox = document.getElementById('simUploadBox');
                uploadBox.style.backgroundColor = 'white';
                uploadBox.style.borderColor = '#ddd';
                uploadBox.querySelector('span').textContent = 'Upload File SIM';
                uploadBox.querySelector('span').style.color = 'black';
                document.getElementById('simFileName').style.display = 'none';
            }
        }
    }

    function isValidImage(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung! Hanya JPG/PNG yang diperbolehkan.');
            return false;
        }
        return true;
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
            const input = this.querySelector('.file-input');
            if (!input.files[0]) {
                this.style.backgroundColor = 'white';
                this.style.borderColor = '#ddd';
            }
        });
        
        box.addEventListener('drop', function(e) {
            e.preventDefault();
            const input = this.querySelector('.file-input');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                const fileNameId = input.id === 'ktpInput' ? 'ktpFileName' : 'simFileName';
                const previewId = input.id === 'ktpInput' ? 'ktpPreview' : 'simPreview';
                handleFileUpload(input, fileNameId, previewId);
            }
        });
    });

    // Form validation and submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const saveBtn = document.getElementById('saveBtn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Clear previous errors
                document.querySelectorAll('.error-text').forEach(el => {
                    if (!el.classList.contains('server-error')) {
                        el.remove();
                    }
                });
                
                let isValid = true;
                
                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        const error = document.createElement('span');
                        error.className = 'error-text';
                        error.textContent = 'Field ini wajib diisi';
                        field.parentElement.appendChild(error);
                        field.style.border = '2px solid #ff4d4d';
                    } else {
                        field.style.border = 'none';
                    }
                });
                
                // Validate NIK length
                const nikField = form.querySelector('[name="nik"]');
                if (nikField && nikField.value.trim().length !== 16) {
                    isValid = false;
                    const error = document.createElement('span');
                    error.className = 'error-text';
                    error.textContent = 'NIK harus 16 digit';
                    nikField.parentElement.appendChild(error);
                    nikField.style.border = '2px solid #ff4d4d';
                }
                
                // Validate email format
                const emailField = form.querySelector('[name="email"]');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailField && !emailRegex.test(emailField.value)) {
                    isValid = false;
                    const error = document.createElement('span');
                    error.className = 'error-text';
                    error.textContent = 'Format email tidak valid';
                    emailField.parentElement.appendChild(error);
                    emailField.style.border = '2px solid #ff4d4d';
                }
                
                // Validate dates
                const joinDateField = form.querySelector('[name="join_date"]');
                const simExpiryField = form.querySelector('[name="sim_expiry_date"]');
                const today = new Date().toISOString().split('T')[0];
                
                if (joinDateField && joinDateField.value > today) {
                    isValid = false;
                    const error = document.createElement('span');
                    error.className = 'error-text';
                    error.textContent = 'Tanggal bergabung tidak boleh di masa depan';
                    joinDateField.parentElement.appendChild(error);
                    joinDateField.style.border = '2px solid #ff4d4d';
                }
                
                if (simExpiryField && simExpiryField.value <= today) {
                    isValid = false;
                    const error = document.createElement('span');
                    error.className = 'error-text';
                    error.textContent = 'Masa berlaku SIM harus di masa depan';
                    simExpiryField.parentElement.appendChild(error);
                    simExpiryField.style.border = '2px solid #ff4d4d';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Harap periksa kembali data yang dimasukkan.');
                    return;
                }
                
                // Disable button and show loading
                saveBtn.disabled = true;
                saveBtn.innerHTML = 'Menyimpan...';
            });
        }
        
        // Format phone number
        const phoneField = document.querySelector('[name="phone"]');
        if (phoneField) {
            phoneField.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    value = value.match(/.{1,4}/g)?.join('-') || value;
                }
                e.target.value = value;
            });
        }
        
        // Format NIK
        const nikField = document.querySelector('[name="nik"]');
        if (nikField) {
            nikField.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 16);
            });
        }
    });
</script>
@endpush