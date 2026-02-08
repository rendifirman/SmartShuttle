@extends('layouts.app')

@section('title', 'Pesanan SmartRent - SmartRent')

@push('styles')
<style>
    /* ===== GLOBAL ===== */
    .smartrent-wrapper{
        background:#f2f2f2;
        padding:40px 0 80px;
        min-height: 100vh;
        padding-top: 100px;
    }

    .smartrent-card{
        max-width: 100%;
        width: 100%;
        margin:auto;
        background:#fff;
        border-radius:12px;
        padding:32px 36px 40px;
        box-shadow:0 6px 18px rgba(0,0,0,.08);
    }

    .container-full {
        max-width: 100%;
        padding: 0 30px;
    }

    @media (min-width: 1400px) {
        .container-full {
            max-width: 1400px;
            margin: 0 auto;
        }
    }

    h2.section-title{
        font-size:18px;
        font-weight:700;
        color:#1E3A5F;
        margin-bottom:14px;
    }

    .divider{
        height:2px;
        background:#FF6B2C;
        margin-bottom:22px;
        width: 100%;
    }

    /* ===== FORM ===== */
    .form-group{
        margin-bottom:18px;
        width: 100%;
    }

    label{
        display:block;
        font-size:14px;
        font-weight:600;
        margin-bottom:6px;
        color:#000;
        width: 100%;
    }

    label span{
        color:red;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="tel"],
    select,
    textarea{
        width:100%;
        padding:12px 14px;
        border-radius:6px;
        border:1px solid #e0e0e0;
        font-size:14px;
        background:#DDE7FF;
        outline:none;
        box-sizing: border-box;
    }
    
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    input[type="tel"]:focus,
    select:focus,
    textarea:focus {
        border-color: #FF6B2C;
        background: #fff;
    }

    textarea{
        background:#fff;
        resize:none;
        height:90px;
        width: 100%;
    }

    .row-3{
        display:grid;
        grid-template-columns:1fr 1fr 1fr;
        gap:20px;
        width: 100%;
    }

    /* ===== UPLOAD ===== */
    .upload-box{
        border:2px dashed #cfcfcf;
        border-radius:8px;
        padding:22px;
        text-align:center;
        color:#777;
        background:#f9f9f9;
        font-size:14px;
        cursor:pointer;
        transition: all 0.3s;
        width: 100%;
        box-sizing: border-box;
    }
    
    .upload-box:hover {
        border-color: #FF6B2C;
        background: #fffaf8;
    }

    .upload-box i{
        display:block;
        font-size:18px;
        margin-bottom:6px;
    }

    .upload-note{
        font-size:12px;
        margin-top:4px;
        color:#999;
        width: 100%;
    }

    /* ===== BUTTON ===== */
    .btn-area{
        display:flex;
        justify-content:space-between;
        margin-top:34px;
        width: 100%;
    }

    .btn-back{
        padding:12px 28px;
        background:#d9d9d9;
        border:none;
        border-radius:6px;
        font-weight:600;
        cursor:pointer;
        transition: background 0.3s;
        min-width: 150px;
    }
    
    .btn-back:hover {
        background: #ccc;
    }

    .btn-next{
        padding:12px 34px;
        background:#FF6B2C;
        color:#fff;
        border:none;
        border-radius:6px;
        font-weight:600;
        cursor:pointer;
        transition: background 0.3s;
        min-width: 200px;
    }

    .btn-next:hover{
        background:#ff581e;
    }

    /* ===== VEHICLE INFO ===== */
    .vehicle-summary {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        width: 100%;
    }
    
    .vehicle-summary h3 {
        color: #1E3A5F;
        margin-bottom: 15px;
        font-size: 18px;
    }
    
    .vehicle-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        width: 100%;
    }
    
    .vehicle-detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        width: 100%;
    }
    
    .vehicle-detail-label {
        color: #666;
        font-size: 14px;
    }
    
    .vehicle-detail-value {
        font-weight: 600;
        color: #1E3A5F;
    }
    
    .total-price {
        background: #fff5f2;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        text-align: center;
        width: 100%;
    }
    
    .total-price .label {
        color: #666;
        font-size: 14px;
    }
    
    .total-price .amount {
        font-size: 22px;
        font-weight: 700;
        color: #FF6B2C;
        margin-top: 5px;
    }

    /* ===== ERROR MESSAGE ===== */
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        width: 100%;
    }
    
    .has-error input,
    .has-error select,
    .has-error textarea {
        border-color: #dc3545;
    }
    
    .alert {
        width: 100%;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        box-sizing: border-box;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* ===== FORM CONTAINER ===== */
    .form-container {
        width: 100%;
    }

    /* ===== HEADER ===== */
    .page-header {
        text-align: center;
        margin-bottom: 30px;
        width: 100%;
    }
    
    .page-header h1 {
        color: #1E3A5F;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .page-header p {
        color: #666;
        font-size: 16px;
        max-width: 800px;
        margin: 0 auto;
    }

    /* ===== FILE INPUT STYLING ===== */
    .file-input-container {
        width: 100%;
    }
    
    .file-input {
        display: none;
    }
    
    .file-name {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        display: block;
        width: 100%;
        text-align: center;
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width: 768px){
        .smartrent-wrapper {
            padding: 20px 15px;
        }
        
        .smartrent-card {
            padding: 20px 15px;
        }
        
        .container-full {
            padding: 0 15px;
        }
        
        .row-3{
            grid-template-columns:1fr;
        }
        
        .btn-area{
            flex-direction:column;
            gap:14px;
        }
        
        .btn-back,
        .btn-next{
            width:100%;
        }
        
        .vehicle-details {
            grid-template-columns: 1fr;
        }
    }

    /* ===== FOR SMALL SCREENS ===== */
    @media(max-width: 480px){
        .page-header h1 {
            font-size: 22px;
        }
        
        .smartrent-card {
            padding: 15px 10px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="tel"],
        select,
        textarea {
            padding: 10px 12px;
            font-size: 13px;
        }
        
        .btn-back,
        .btn-next {
            padding: 10px 20px;
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="smartrent-wrapper">
    <div class="container-full">
        <div class="smartrent-card">
            <div class="page-header">
                <h1>Formulir Pemesanan SmartRent</h1>
                <p>Lengkapi data diri Anda untuk menyelesaikan proses pemesanan</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- DATA KENDARAAN --}}
            <div class="vehicle-summary">
                <h3>Detail Kendaraan</h3>
                @if(isset($vehicle) && $vehicle)
                <div class="vehicle-details">
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Nama Kendaraan:</span>
                        <span class="vehicle-detail-value">{{ $vehicle['name'] }}</span>
                    </div>
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Tipe:</span>
                        <span class="vehicle-detail-value">{{ $vehicle['type'] }}</span>
                    </div>
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Layanan:</span>
                        <span class="vehicle-detail-value">
                            {{ isset($service) && $service == 'self-drive' ? 'Lepas Kunci' : 'Dengan Sopir' }}
                        </span>
                    </div>
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Durasi:</span>
                        <span class="vehicle-detail-value">{{ isset($duration) ? $duration : 1 }} Hari</span>
                    </div>
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Tanggal Mulai:</span>
                        <span class="vehicle-detail-value">
                            {{ isset($rentDate) ? date('d F Y', strtotime($rentDate)) : date('d F Y') }}
                        </span>
                    </div>
                    <div class="vehicle-detail-item">
                        <span class="vehicle-detail-label">Harga per hari:</span>
                        <span class="vehicle-detail-value">Rp {{ isset($vehicle['price']) ? number_format($vehicle['price'], 0, ',', '.') : '0' }}</span>
                    </div>
                </div>
                <div class="total-price">
                    <div class="label">Total Biaya:</div>
                    <div class="amount">Rp {{ isset($total) ? number_format($total, 0, ',', '.') : '0' }}</div>
                </div>
                @else
                <p style="color: #dc3545; text-align: center; padding: 10px;">Data kendaraan tidak ditemukan. Silakan kembali ke halaman pemilihan kendaraan.</p>
                @endif
            </div>

            {{-- FORM PEMESANAN --}}
            <!-- PERUBAHAN DISINI: action ke halaman pembayaran -->
            <form action="{{ route('smartrent.payment') }}" method="GET" class="form-container">
                @csrf
                
                <!-- Hidden fields untuk data kendaraan -->
                <input type="hidden" name="vehicle_id" value="{{ $vehicleId ?? '' }}">
                <input type="hidden" name="service" value="{{ $service ?? '' }}">
                <input type="hidden" name="duration" value="{{ $duration ?? '' }}">
                <input type="hidden" name="rent_date" value="{{ $rentDate ?? '' }}">
                <input type="hidden" name="total_price" value="{{ $total ?? 0 }}">

                {{-- DATA PEMESANAN --}}
                <h2 class="section-title">DATA PEMESANAN</h2>
                <div class="divider"></div>

                <div class="form-group @error('full_name') has-error @enderror">
                    <label>Nama Lengkap <span>*</span></label>
                    <input type="text" name="full_name" placeholder="Masukkan nama lengkap" 
                           value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('phone') has-error @enderror">
                    <label>Nomor Telepon <span>*</span></label>
                    <input type="tel" name="phone" placeholder="Masukkan nomor telepon" 
                           value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('email') has-error @enderror">
                    <label>Email <span>*</span></label>
                    <input type="email" name="email" placeholder="Masukkan email" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('pickup_address') has-error @enderror">
                    <label>Alamat Penjemputan <span>*</span></label>
                    <input type="text" name="pickup_address" placeholder="Masukkan alamat penjemputan" 
                           value="{{ old('pickup_address') }}" required>
                    @error('pickup_address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DETAIL PENYEWAAN --}}
                <h2 class="section-title" style="margin-top:34px;">DETAIL PENYEWAAN</h2>
                <div class="divider"></div>

                <div class="row-3">
                    <div class="form-group @error('rent_date') has-error @enderror">
                        <label>Tanggal Mulai <span>*</span></label>
                        <input type="date" name="rent_date" 
                               value="{{ old('rent_date', $rentDate ?? date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" required>
                        @error('rent_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group @error('duration') has-error @enderror">
                        <label>Durasi Sewa (Hari) <span>*</span></label>
                        <select name="duration" required>
                            <option value="">Pilih durasi</option>
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" 
                                    {{ old('duration', $duration ?? 1) == $i ? 'selected' : '' }}>
                                    {{ $i }} Hari
                                </option>
                            @endfor
                        </select>
                        @error('duration')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group @error('city') has-error @enderror">
                        <label>Kota <span>*</span></label>
                        <select name="city" required>
                            <option value="">Pilih kota</option>
                            <option value="jakarta" {{ old('city') == 'jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="bandung" {{ old('city') == 'bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="surabaya" {{ old('city') == 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="yogyakarta" {{ old('city') == 'yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="bali" {{ old('city') == 'bali' ? 'selected' : '' }}>Bali</option>
                        </select>
                        @error('city')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group @error('service_type') has-error @enderror">
                    <label>Tipe Layanan <span>*</span></label>
                    <select name="service_type" required>
                        <option value="">Pilih layanan</option>
                        <option value="self-drive" {{ old('service_type', $service ?? '') == 'self-drive' ? 'selected' : '' }}>
                            Lepas Kunci
                        </option>
                        <option value="with-driver" {{ old('service_type', $service ?? '') == 'with-driver' ? 'selected' : '' }}>
                            Dengan Sopir
                        </option>
                    </select>
                    @error('service_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DOKUMEN --}}
                <h2 class="section-title" style="margin-top:34px;">DOKUMEN</h2>
                <div class="divider"></div>

                <div class="form-group @error('ktp_file') has-error @enderror">
                    <label>Upload KTP <span>*</span></label>
                    <input type="file" name="ktp_file" id="ktp_file" class="file-input" accept=".jpg,.jpeg,.png,.pdf">
                    <div class="upload-box" onclick="document.getElementById('ktp_file').click()">
                        <i class="fa-regular fa-file"></i>
                        <span id="ktp_label">Klik untuk mengunggah KTP</span>
                        <div class="upload-note">Format: JPG, PNG, PDF (max. 2MB)</div>
                    </div>
                    <span id="ktp_file_name" class="file-name"></span>
                    @error('ktp_file')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($service) && $service == 'self-drive')
                <div class="form-group @error('sim_file') has-error @enderror">
                    <label>Upload SIM <span>*</span></label>
                    <input type="file" name="sim_file" id="sim_file" class="file-input" accept=".jpg,.jpeg,.png,.pdf">
                    <div class="upload-box" onclick="document.getElementById('sim_file').click()">
                        <i class="fa-regular fa-file"></i>
                        <span id="sim_label">Klik untuk mengunggah SIM</span>
                        <div class="upload-note">Format: JPG, PNG, PDF (max. 2MB)</div>
                    </div>
                    <span id="sim_file_name" class="file-name"></span>
                    @error('sim_file')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea name="notes" placeholder="Masukkan catatan anda">{{ old('notes') }}</textarea>
                </div>

                {{-- BUTTON --}}
                <div class="btn-area">
                    <button type="button" class="btn-back" onclick="window.history.back()">Kembali</button>
                    <button type="submit" class="btn-next">Lanjutkan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// File upload preview
document.getElementById('ktp_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Klik untuk mengunggah KTP';
    document.getElementById('ktp_label').textContent = fileName;
    document.getElementById('ktp_file_name').textContent = fileName;
});

document.getElementById('sim_file')?.addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Klik untuk mengunggah SIM';
    document.getElementById('sim_label').textContent = fileName;
    document.getElementById('sim_file_name').textContent = fileName;
});

// Validasi form
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            valid = false;
            if (!field.classList.contains('has-error')) {
                field.classList.add('has-error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'Field ini wajib diisi.';
                field.parentNode.appendChild(errorDiv);
            }
        } else {
            field.classList.remove('has-error');
            const errorDiv = field.parentNode.querySelector('.error-message');
            if (errorDiv && errorDiv.textContent === 'Field ini wajib diisi.') {
                errorDiv.remove();
            }
        }
    });
    
    // Validasi file upload
    const ktpFile = document.getElementById('ktp_file');
    if (ktpFile && !ktpFile.files[0]) {
        valid = false;
        const ktpGroup = ktpFile.parentNode.parentNode;
        if (!ktpGroup.querySelector('.error-message')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = 'Harap upload file KTP.';
            ktpGroup.appendChild(errorDiv);
        }
    }
    
    // Validasi SIM untuk layanan self-drive
    const serviceType = document.querySelector('select[name="service_type"]');
    if (serviceType && serviceType.value === 'self-drive') {
        const simFile = document.getElementById('sim_file');
        if (simFile && !simFile.files[0]) {
            valid = false;
            const simGroup = simFile.parentNode.parentNode;
            if (!simGroup.querySelector('.error-message')) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'Harap upload file SIM.';
                simGroup.appendChild(errorDiv);
            }
        }
    }
    
    if (!valid) {
        e.preventDefault();
        // Scroll ke field pertama yang error
        const firstError = document.querySelector('.has-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

// Clear error ketika field diisi
document.querySelectorAll('input, select, textarea').forEach(field => {
    field.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            const errorDiv = this.parentNode.querySelector('.error-message');
            if (errorDiv && errorDiv.textContent === 'Field ini wajib diisi.') {
                errorDiv.remove();
            }
        }
    });
});

// Simpan data ke session storage sebelum submit
document.querySelector('form').addEventListener('submit', function() {
    // Simpan data form ke session storage
    const formData = new FormData(this);
    const formObject = {};
    
    formData.forEach((value, key) => {
        formObject[key] = value;
    });
    
    // Simpan file names jika ada
    const ktpFile = document.getElementById('ktp_file').files[0];
    const simFile = document.getElementById('sim_file')?.files[0];
    
    if (ktpFile) {
        formObject['ktp_file_name'] = ktpFile.name;
    }
    
    if (simFile) {
        formObject['sim_file_name'] = simFile.name;
    }
    
    // Simpan ke session storage
    sessionStorage.setItem('smartrent_checkout_data', JSON.stringify(formObject));
});
</script>
@endpush
@endsection