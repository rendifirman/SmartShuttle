@extends('layouts.app')

@section('title', 'Checkout SmartRent - SmartRent')

@push('styles')
<style>
    :root {
        --primary-color: #0f2942;
        --secondary-color: #FF581E;
        --light-bg: #f8f9fa;
        --border-color: #eef2f7;
        --success-color: #28a745;
    }
    
    .checkout-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    .checkout-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .checkout-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .checkout-subtitle {
        color: #666;
        font-size: 16px;
    }
    
    /* Vehicle Summary Card */
    .vehicle-summary-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 41, 66, 0.1);
        border: 1px solid var(--border-color);
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        gap: 20px;
    }
    
    .vehicle-summary-image {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .vehicle-summary-details {
        flex: 1;
    }
    
    .vehicle-summary-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .vehicle-summary-type {
        color: var(--secondary-color);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
    }
    
    .vehicle-summary-specs {
        display: flex;
        gap: 15px;
        margin-bottom: 10px;
    }
    
    .vehicle-summary-spec {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #666;
        font-size: 12px;
    }
    
    .vehicle-summary-spec i {
        color: var(--secondary-color);
    }
    
    /* Form Section */
    .form-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 41, 66, 0.1);
        border: 1px solid var(--border-color);
        padding: 30px;
        margin-bottom: 25px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: var(--secondary-color);
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--primary-color);
        font-size: 14px;
    }
    
    .form-label .required {
        color: #dc3545;
        margin-left: 2px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s ease;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }
    
    /* Grid untuk tanggal dan jam */
    .datetime-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .time-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    @media (max-width: 768px) {
        .datetime-grid {
            grid-template-columns: 1fr;
        }
        .time-group {
            grid-template-columns: 1fr;
        }
        .vehicle-summary-card {
            flex-direction: column;
        }
        .vehicle-summary-image {
            width: 100%;
            height: 150px;
        }
    }
    
    /* File Upload */
    .file-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 20px;
        background: var(--light-bg);
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    
    .file-upload-area:hover {
        border-color: var(--secondary-color);
    }
    
    .file-upload-area.has-file {
        border-color: var(--success-color);
        background: rgba(40, 167, 69, 0.05);
    }
    
    .file-upload-text {
        color: var(--primary-color);
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .file-upload-info {
        color: #666;
        font-size: 12px;
        margin-bottom: 10px;
    }
    
    .file-name {
        display: flex;
        align-items: center;
        gap: 8px;
        background: white;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        margin-top: 10px;
        font-size: 13px;
    }
    
    .file-name i {
        color: var(--success-color);
    }
    
    /* Order Summary */
    .order-summary {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .summary-label {
        color: #666;
        font-size: 14px;
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
    }
    
    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--border-color);
    }
    
    .total-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .total-value {
        font-size: 20px;
        font-weight: 900;
        color: var(--secondary-color);
    }
    
    /* Textarea */
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-back, .btn-payment {
        flex: 1;
        padding: 16px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        text-decoration: none;
        text-align: center;
    }
    
    .btn-back {
        background: white;
        color: var(--primary-color);
        border: 1px solid var(--border-color);
    }
    
    .btn-back:hover {
        background: var(--light-bg);
        border-color: var(--primary-color);
    }
    
    .btn-payment {
        background: var(--secondary-color);
        color: white;
    }
    
    .btn-payment:hover {
        background: #E54E1A;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.2);
    }
    
    /* Alert Messages */
    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        font-size: 14px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    
    /* Readonly fields */
    .form-control[readonly] {
        background-color: var(--light-bg);
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@php
    use App\Models\MProfilePerusahaan;
    $profile = MProfilePerusahaan::first();
    
    // CEK SESSION DULU, baru fallback ke URL parameters
    $checkoutData = session('smartrent_checkout');
    
    if ($checkoutData) {
        // Ambil data dari session
        $vehicle_id = $checkoutData['vehicle_id'] ?? 1;
        $vehicle_name = $checkoutData['vehicle_name'] ?? 'Toyota Hiace Commuter';
        $vehicle_image = $checkoutData['vehicle_image'] ?? asset('images/toyotahiace.png');
        $vehicle_price = $checkoutData['vehicle_price'] ?? 1200000;
        $driver_price = $checkoutData['driver_price'] ?? 150000;
        $service_type = $checkoutData['service_type'] ?? 'with_driver';
        $duration = $checkoutData['duration'] ?? 1;
        $rent_date = $checkoutData['rent_date'] ?? date('Y-m-d');
        $vehicle_total = $checkoutData['vehicle_total'] ?? ($vehicle_price * $duration);
        $driver_total = $checkoutData['driver_total'] ?? (($service_type == 'with_driver') ? $driver_price * $duration : 0);
        $total_price = $checkoutData['total_price'] ?? ($vehicle_total + $driver_total);
        $booking_code = $checkoutData['booking_code'] ?? ('SR' . date('Ymd') . rand(100, 999));
    } else {
        // Fallback ke URL parameters (untuk kompatibilitas)
        $vehicle_id = request()->get('vehicle_id', 1);
        $vehicle_name = request()->get('vehicle_name', 'Toyota Hiace Commuter');
        $vehicle_image = request()->get('vehicle_image', asset('images/toyotahiace.png'));
        $vehicle_price = request()->get('vehicle_price', 1200000);
        $driver_price = request()->get('driver_price', 150000);
        $service_type = request()->get('service_type', 'with_driver');
        $service_type = str_replace('with-driver', 'with_driver', $service_type);
        $duration = request()->get('duration', 1);
        $rent_date = request()->get('rent_date', date('Y-m-d'));
        
        // Hitung ulang
        $vehicle_total = $vehicle_price * $duration;
        $driver_total = ($service_type == 'with_driver') ? $driver_price * $duration : 0;
        $total_price = $vehicle_total + $driver_total;
        $booking_code = 'SR' . date('Ymd') . rand(100, 999);
    }
    
    // Hitung tanggal selesai
    $end_date = date('Y-m-d', strtotime($rent_date . ' + ' . ($duration) . ' days'));
    
    // Pastikan service_type formatnya benar
    $service_display = ($service_type == 'with_driver') ? 'Dengan Sopir' : 'Lepas Kunci';
@endphp

<div class="checkout-container">
    <!-- Header -->
    <div class="checkout-header">
        <h1 class="checkout-title">Checkout SmartRent</h1>
        <p class="checkout-subtitle">Lengkapi data berikut untuk menyelesaikan pemesanan</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Ringkasan Kendaraan -->
    <div class="vehicle-summary-card">
        <img src="{{ $vehicle_image }}" alt="{{ $vehicle_name }}" class="vehicle-summary-image"
             onerror="this.onerror=null; this.src='{{ asset('images/default-vehicle.jpg') }}';">
        <div class="vehicle-summary-details">
            <h4 class="vehicle-summary-name">{{ $vehicle_name }}</h4>
            <span class="vehicle-summary-type">{{ $service_display }}</span>
            <div class="vehicle-summary-specs">
                <div class="vehicle-summary-spec">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $duration }} Hari</span>
                </div>
                <div class="vehicle-summary-spec">
                    <i class="fas fa-clock"></i>
                    <span>{{ date('d M Y', strtotime($rent_date)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Harga -->
    <div class="order-summary">
        <h3 style="font-size: 16px; font-weight: 600; color: var(--primary-color); margin-bottom: 15px;">
            <i class="fas fa-file-invoice"></i> Ringkasan Harga
        </h3>
        
        <div class="summary-item">
            <span class="summary-label">Harga Sewa ({{ $duration }} Hari):</span>
            <span class="summary-value">Rp {{ number_format($vehicle_total, 0, ',', '.') }}</span>
        </div>
        
        @if($service_type == 'with_driver')
        <div class="summary-item">
            <span class="summary-label">Biaya Sopir ({{ $duration }} Hari):</span>
            <span class="summary-value">Rp {{ number_format($driver_total, 0, ',', '.') }}</span>
        </div>
        @endif
        
        <div class="summary-total">
            <span class="total-label">Total Pembayaran:</span>
            <span class="total-value">Rp {{ number_format($total_price, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <!-- Form Checkout -->
    <form action="{{ route('smartrent.checkout.finalize') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
        @csrf
        
        <!-- Hidden inputs untuk data booking -->
        <input type="hidden" name="vehicle_id" value="{{ $vehicle_id }}">
        <input type="hidden" name="vehicle_name" value="{{ $vehicle_name }}">
        <input type="hidden" name="vehicle_image" value="{{ $vehicle_image }}">
        <input type="hidden" name="vehicle_price" value="{{ $vehicle_price }}">
        <input type="hidden" name="driver_price" value="{{ $driver_price }}">
        <input type="hidden" name="service_type" value="{{ $service_type }}">
        <input type="hidden" name="duration" value="{{ $duration }}">
        <input type="hidden" name="rent_date" value="{{ $rent_date }}">
        <input type="hidden" name="end_date" value="{{ $end_date }}">
        <input type="hidden" name="vehicle_total" value="{{ $vehicle_total }}">
        <input type="hidden" name="driver_total" value="{{ $driver_total }}">
        <input type="hidden" name="total_price" value="{{ $total_price }}">
        <input type="hidden" name="booking_code" value="{{ $booking_code }}">
        
        <!-- SECTION 1: DATA PEMESAN -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Data Pemesan
            </h3>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="full_name" class="form-control" required 
                       value="{{ old('full_name', auth()->user()->name ?? '') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Nomor Telepon/WhatsApp <span class="required">*</span></label>
                <input type="tel" name="phone" class="form-control" required
                       value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                       pattern="[0-9]{10,13}" placeholder="Contoh: 081234567890">
            </div>
            
            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" required
                       value="{{ old('email', auth()->user()->email ?? '') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                <textarea name="address" class="form-control" required rows="3" 
                          placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota">{{ old('address', auth()->user()->address ?? '') }}</textarea>
            </div>
        </div>
        
        <!-- SECTION 2: DETAIL PENYEWAAN -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-calendar-alt"></i>
                Detail Penyewaan
            </h3>
            
            <div class="datetime-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" readonly
                           value="{{ $rent_date }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" readonly
                           value="{{ $end_date }}">
                </div>
            </div>
            
            <div class="time-group">
                <div class="form-group">
                    <label class="form-label">Jam Mulai <span class="required">*</span></label>
                    <input type="time" name="start_time" class="form-control" required
                           value="{{ old('start_time', '08:00') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Jam Selesai <span class="required">*</span></label>
                    <input type="time" name="end_time" class="form-control" required
                           value="{{ old('end_time', '17:00') }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Lokasi/Outlet Penjemputan <span class="required">*</span></label>
                <select name="pickup_location" class="form-control" required id="pickupLocation">
                    <option value="">Pilih Lokasi Penjemputan</option>
                    <option value="Jakarta - Kantor Pusat SmartRent" {{ old('pickup_location') == 'Jakarta - Kantor Pusat SmartRent' ? 'selected' : '' }}>
                        Jakarta - Kantor Pusat SmartRent
                    </option>
                    <option value="Bandung - Outlet Merdeka SmartRent" {{ old('pickup_location') == 'Bandung - Outlet Merdeka SmartRent' ? 'selected' : '' }}>
                        Bandung - Outlet Merdeka SmartRent
                    </option>
                    <option value="Surabaya - Outlet Tunjungan SmartRent" {{ old('pickup_location') == 'Surabaya - Outlet Tunjungan SmartRent' ? 'selected' : '' }}>
                        Surabaya - Outlet Tunjungan SmartRent
                    </option>
                    <option value="Yogyakarta - Outlet Malioboro SmartRent" {{ old('pickup_location') == 'Yogyakarta - Outlet Malioboro SmartRent' ? 'selected' : '' }}>
                        Yogyakarta - Outlet Malioboro SmartRent
                    </option>
                    <option value="Bali - Outlet Kuta SmartRent" {{ old('pickup_location') == 'Bali - Outlet Kuta SmartRent' ? 'selected' : '' }}>
                        Bali - Outlet Kuta SmartRent
                    </option>
                    <option value="Lainnya (Sesuai Kesepakatan)" {{ old('pickup_location') == 'Lainnya (Sesuai Kesepakatan)' ? 'selected' : '' }}>
                        Lainnya (Sesuai Kesepakatan)
                    </option>
                </select>
                <small class="text-muted" style="display: block; margin-top: 5px; font-size: 13px;">
                    * Untuk lokasi lain, tim kami akan menghubungi Anda untuk konfirmasi
                </small>
            </div>
        </div>
        
        <!-- SECTION 3: DOKUMEN -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-file-alt"></i>
                Dokumen
            </h3>
            
            <div class="form-group">
                <label class="form-label">Upload KTP (Foto/Salinan) <span class="required">*</span></label>
                <div class="file-upload-area" onclick="document.getElementById('ktp_file').click()" id="ktp-upload-area">
                    <div class="file-upload-text">
                        <i class="fas fa-cloud-upload-alt"></i> Klik untuk upload KTP
                    </div>
                    <div class="file-upload-info">
                        Format: JPG, PNG, PDF | Maks: 2MB
                    </div>
                </div>
                <input type="file" name="ktp_file" id="ktp_file" accept=".jpg,.jpeg,.png,.pdf" 
                       style="display: none" onchange="handleFileUpload(this, 'ktp-upload-area', 'ktp-file-name')" required>
                <div class="file-name" id="ktp-file-name" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span>Belum ada file</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Upload SIM (Foto/Salinan) <span class="required">*</span></label>
                <div class="file-upload-area" onclick="document.getElementById('sim_file').click()" id="sim-upload-area">
                    <div class="file-upload-text">
                        <i class="fas fa-cloud-upload-alt"></i> Klik untuk upload SIM
                    </div>
                    <div class="file-upload-info">
                        Format: JPG, PNG, PDF | Maks: 2MB
                    </div>
                </div>
                <input type="file" name="sim_file" id="sim_file" accept=".jpg,.jpeg,.png,.pdf" 
                       style="display: none" onchange="handleFileUpload(this, 'sim-upload-area', 'sim-file-name')" required>
                <div class="file-name" id="sim-file-name" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span>Belum ada file</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Upload Dokumen Lainnya (Opsional)</label>
                <div class="file-upload-area" onclick="document.getElementById('other_document').click()" id="other-upload-area">
                    <div class="file-upload-text">
                        <i class="fas fa-cloud-upload-alt"></i> Klik untuk upload dokumen lainnya
                    </div>
                    <div class="file-upload-info">
                        Format: JPG, PNG, PDF, DOC, DOCX | Maks: 5MB
                    </div>
                </div>
                <input type="file" name="other_document" id="other_document" 
                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display: none" 
                       onchange="handleFileUpload(this, 'other-upload-area', 'other-file-name')">
                <div class="file-name" id="other-file-name" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span>Belum ada file</span>
                </div>
                <small class="text-muted" style="display: block; margin-top: 5px; font-size: 13px;">
                    * Contoh: Surat keterangan kerja, surat izin perusahaan, dll (opsional)
                </small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" class="form-control" rows="3" 
                          placeholder="Contoh: Alamat lengkap penjemputan, permintaan khusus, instruksi khusus untuk sopir, dll">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <!-- ACTION BUTTONS -->
        <div class="action-buttons">
            <a href="{{ route('smartrent.booking', ['vehicle_id' => $vehicle_id]) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <button type="submit" class="btn-payment" id="submitBtn">
                <i class="fas fa-lock"></i> Proses Pembayaran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Handle file upload
function handleFileUpload(input, areaId, fileNameId) {
    const file = input.files[0];
    const area = document.getElementById(areaId);
    const fileName = document.getElementById(fileNameId);
    
    if (file) {
        // Validasi ukuran file
        const maxSize = areaId === 'other-upload-area' ? 5 : 2; // MB
        if (file.size > maxSize * 1024 * 1024) {
            alert(`Ukuran file terlalu besar. Maksimal ${maxSize}MB.`);
            input.value = '';
            area.classList.remove('has-file');
            fileName.style.display = 'none';
            return;
        }
        
        // Validasi tipe file
        const fileNameLower = file.name.toLowerCase();
        let isValid = false;
        
        if (areaId === 'other-upload-area') {
            isValid = fileNameLower.match(/\.(jpg|jpeg|png|pdf|doc|docx)$/);
        } else {
            isValid = fileNameLower.match(/\.(jpg|jpeg|png|pdf)$/);
        }
        
        if (!isValid) {
            const allowed = areaId === 'other-upload-area' 
                ? 'JPG, PNG, PDF, DOC, DOCX' 
                : 'JPG, PNG, PDF';
            alert(`Format file tidak didukung. Gunakan ${allowed}.`);
            input.value = '';
            area.classList.remove('has-file');
            fileName.style.display = 'none';
            return;
        }
        
        const fileNameText = file.name.length > 25 ? file.name.substring(0, 22) + '...' : file.name;
        fileName.querySelector('span').textContent = fileNameText;
        fileName.style.display = 'flex';
        area.classList.add('has-file');
    } else {
        fileName.style.display = 'none';
        area.classList.remove('has-file');
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            
            // Validasi file upload
            const ktpFile = document.getElementById('ktp_file').files.length;
            const simFile = document.getElementById('sim_file').files.length;
            
            if (ktpFile === 0) {
                e.preventDefault();
                alert('Harap unggah KTP terlebih dahulu');
                return false;
            }
            
            if (simFile === 0) {
                e.preventDefault();
                alert('Harap unggah SIM terlebih dahulu');
                return false;
            }
            
            // Validasi jam untuk sewa hari yang sama
            const startTime = document.querySelector('input[name="start_time"]').value;
            const endTime = document.querySelector('input[name="end_time"]').value;
            
            if (startTime && endTime && startTime >= endTime) {
                e.preventDefault();
                alert('Jam selesai harus setelah jam mulai');
                return false;
            }
            
            // Validasi lokasi
            const pickupLocation = document.getElementById('pickupLocation').value;
            if (!pickupLocation) {
                e.preventDefault();
                alert('Harap pilih lokasi penjemputan');
                return false;
            }
            
            // Validasi email
            const email = document.querySelector('input[name="email"]').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid');
                return false;
            }
            
            // Validasi nomor telepon
            const phone = document.querySelector('input[name="phone"]').value;
            const phonePattern = /^[0-9]{10,13}$/;
            if (!phonePattern.test(phone)) {
                e.preventDefault();
                alert('Nomor telepon harus 10-13 digit angka');
                return false;
            }
            
            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endpush
@endsection