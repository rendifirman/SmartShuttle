

@extends('layouts.app-driver')

@section('title', 'Profile Driver - Smart Shuttle')

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

    .edit-profile-btn {
        background: #ff6a00;
        color: white;
        border: none;
        padding: 6px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .edit-profile-btn:hover {
        background: #e55e00;
        transform: translateY(-2px);
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
    }
</style>
@endpush

@section('content')


<h2>Profile Driver</h2>
<hr>

<div class="profile-card">
    <!-- PROFILE HEADER DENGAN FOTO DI SAMPING -->
    <div class="profile-header">
        <div class="profile-photo">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Photo">
        </div>
        <div class="profile-info">
            <h2>Dimas Mahendra</h2>
            <div class="profile-id-section">
                <div class="profile-id">ID Pengemudi: DRV-2023-001</div>
            </div>
            <div class="profile-status">Aktif</div>
        </div>
    </div>

    <div class="form-grid">
        <!-- KOLOM KIRI -->
        <div class="form-column">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="Dimas Mahendra">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="dimas.pratama.driver@gmail.com">
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" value="0812-7788-3344">
            </div>

            <div class="form-group">
                <label>NIK (16 digit)</label>
                <input type="text" value="3201152206970004">
            </div>

            <div class="form-group">
                <label>Upload KTP<br><small>.JPG/PNG Max 5MB</small></label>
                <div class="upload-section">
                    <div class="upload-box" id="ktpUploadBox">
                        <span>Upload File</span>
                        <input type="file" class="file-input" id="ktpInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'ktpFileName')">
                    </div>
                    <div class="file-name" id="ktpFileName"></div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="form-column">
            <div class="form-group">
                <label>Tanggal Bergabung</label>
                <input type="text" value="12 Januari 2023">
            </div>

            <div class="form-group">
                <label>ID Pengemudi</label>
                <input type="text" value="DRV-2023-001">
            </div>

            <div class="form-group">
                <label>Nomor SIM</label>
                <input type="text" value="A9876543210">
            </div>

            <div class="form-group">
                <label>Masa Berlaku SIM</label>
                <input type="text" value="12 Januari 2027">
            </div>

            <div class="form-group">
                <label>Upload SIM<br><small>.JPG/PNG Max 5MB</small></label>
                <div class="upload-section">
                    <div class="upload-box" id="simUploadBox">
                        <span>Upload File</span>
                        <input type="file" class="file-input" id="simInput" accept=".jpg,.jpeg,.png" onchange="handleFileUpload(this, 'simFileName')">
                    </div>
                    <div class="file-name" id="simFileName"></div>
                </div>
            </div>
        </div>
    </div>
    <button class="save-btn" onclick="saveChanges()">Simpan Perubahan</button>
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
                fileNameElement.textContent = File terpilih: ${file.name};
                fileNameElement.style.display = 'block';

                // Ubah tampilan upload box
                const uploadBox = input.parentElement;
                uploadBox.style.backgroundColor = '#e8f5e8';
                uploadBox.style.borderColor = '#2ecc71';
                uploadBox.querySelector('span').textContent = 'File Terpilih';
                uploadBox.querySelector('span').style.color = '#2ecc71';
            }
        }

    function saveChanges() {
        let isValid = true;

        const requiredInputs = document.querySelectorAll('.form-group input[type="text"]');
        const ktpInput = document.getElementById('ktpInput');
        const simInput = document.getElementById('simInput');

        // Reset semua error sebelumnya
        document.querySelectorAll('.error-text').forEach(el => el.remove());
        requiredInputs.forEach(input => input.style.border = 'none');

        // Validasi input text
        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                isValid = false;
                showFieldError(input, 'Data wajib diisi');
            }
        });

        // Validasi file KTP
        if (!ktpInput.files[0]) {
            isValid = false;
            showFileError('ktpUploadBox', 'Upload KTP wajib diisi');
        }

        // Validasi file SIM
        if (!simInput.files[0]) {
            isValid = false;
            showFileError('simUploadBox', 'Upload SIM wajib diisi');
        }

        if (!isValid) {
            alert('Data harus dilengkapi terlebih dahulu!');
            return;
        }

        alert('Perubahan berhasil disimpan!');
    }


    function resetUploadBox(boxId) {
        const uploadBox = document.getElementById(boxId);
        uploadBox.style.backgroundColor = 'white';
        uploadBox.style.borderColor = '#ddd';
        uploadBox.querySelector('span').textContent = 'Upload File';
        uploadBox.querySelector('span').style.color = 'black';
    }

    // Optional: Drag and drop functionality
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

    // Fungsi untuk edit profile
    document.querySelector('.edit-profile-btn').addEventListener('click', function() {
        alert('Fitur edit profile akan membuka form edit lengkap');
        // Di sini bisa ditambahkan logika untuk membuka modal atau form edit
    });

    function showFieldError(input, message) {
        input.style.border = '2px solid #ff4d4d';

        const error = document.createElement('small');
        error.className = 'error-text';
        error.style.color = '#ff4d4d';
        error.textContent = message;

        input.parentElement.appendChild(error);
    }

    function showFileError(boxId, message) {
        const box = document.getElementById(boxId);
        box.style.border = '2px dashed #ff4d4d';

        const error = document.createElement('small');
        error.className = 'error-text';
        error.style.color = '#ff4d4d';
        error.textContent = message;

        box.parentElement.appendChild(error);
    }

</script>
@endpush
