@extends('layouts.app-admin')

@section('title', 'Profile Perusahaan - Smart Shuttle Admin')
@section('page-title', 'Profile Perusahaan')

@push('styles')
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-rounded/css/uicons-regular-rounded.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-rounded/css/uicons-solid-rounded.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-brands/css/uicons-brands.css">

<style>
/* ====== GENERAL STYLES ====== */
.main-content {
    padding: 32px;
    background: #f8f8f6;
    min-height: 100vh;
}

.tab-container {
    margin-bottom: 32px;
}

.tabs {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 32px;
}

.tab-btn {
    padding: 12px 24px;
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    color: #374151;
}

.tab-btn.active {
    color: #ff6a21;
    border-bottom-color: #ff6a21;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ====== INPUT FORM STYLES ====== */
.profile {
    padding: 0;
}

.card {
    background: #ffffff;
    border-radius: 12px;
    padding: 28px 32px;
    margin-bottom: 28px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.card h4 {
    margin: 0 0 24px;
    padding-bottom: 12px;
    border-bottom: 2px solid #ff6a21;
    font-size: 16px;
    font-weight: 700;
    color: #0d3559;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 20px;
}

.form-row.three {
    grid-template-columns: repeat(3, 1fr);
}

.form-row.full {
    grid-template-columns: 1fr;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    font-size: 13px;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 12px 16px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    color: #374151;
    background: #ffffff;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #ff6a21;
    box-shadow: 0 0 0 3px rgba(255, 106, 33, 0.1);
}

.form-group input:disabled,
.form-group textarea:disabled {
    background: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
    line-height: 1.5;
}

/* SERVICES */
.services {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 28px;
}

.service {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    background: #ffffff;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-sizing: border-box;
}

.service:hover {
    border-color: #ff6a21;
    box-shadow: 0 4px 12px rgba(255, 106, 33, 0.1);
}

.service .form-group {
    margin-bottom: 16px;
}

.service .upload {
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    font-size: 13px;
    color: #9ca3af;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100px;
}

.service .upload:hover {
    border-color: #ff6a21;
    background: #fff7ed;
}

/* BUTTONS */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary {
    background: #ff6a21;
    color: white;
}

.btn-primary:hover {
    background: #e55d00;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 106, 33, 0.2);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-outline {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #374151;
}

.btn-outline:hover {
    border-color: #ff6a21;
    color: #ff6a21;
}

/* Loading state */
.btn.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.8;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* UPLOAD PREVIEW */
.upload-preview {
    display: none;
    margin-bottom: 16px;
}

.upload-preview img {
    width: 100%;
    height: 120px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
}

.upload-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.upload-actions .btn {
    padding: 6px 12px;
    font-size: 12px;
    flex: 1;
}

/* BADGE */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-primary {
    background: rgba(255, 106, 33, 0.1);
    color: #ff6a21;
    border: 1px solid rgba(255, 106, 33, 0.2);
}

.badge-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* STATUS INDICATOR */
.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #d1d5db;
}

.status-dot.active {
    background: #10b981;
}

/* CARD HEADER ACTIONS */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header h4 {
    margin: 0;
    border: none;
    padding: 0;
}

/* ====== RESULT VIEW STYLES ====== */
.result-content h1 {
    font-size: 24px;
    font-weight: 700;
    color: #0d3559;
    margin: 0 0 32px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #0d3559;
    margin: 0 0 24px;
    padding-bottom: 12px;
    border-bottom: 2px solid #ff6a21;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title i {
    transition: transform 0.3s ease;
    color: #6b7280;
}

.section-content {
    overflow: hidden;
    transition: max-height 0.3s ease, opacity 0.3s ease;
}

.company-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

.company-left, .company-right {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-field {
    margin-bottom: 20px;
}

.field-label {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 8px;
}

.field-value {
    font-size: 16px;
    color: #374151;
    line-height: 1.5;
}

.contact-field {
    margin-bottom: 20px;
}

.contact-field .field-label {
    font-size: 14px;
    margin-bottom: 16px;
    color: #374151;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.contact-icon {
    color: #ff6a21;
    width: 20px;
    flex-shrink: 0;
    font-size: 16px;
}

.contact-text {
    font-size: 14px;
    color: #374151;
}

.layanan-section {
    margin-top: 32px;
}

.services-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.service-big-card {
    background: #ffffff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.service-big-card:hover {
    border-color: #ff6a21;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(255, 106, 33, 0.1);
}

.service-image {
    width: 100%;
    height: 180px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ff6a21;
    font-size: 64px;
    border-bottom: 2px solid #e5e7eb;
}

.service-content {
    padding: 24px;
}

.service-title {
    font-size: 18px;
    font-weight: 700;
    color: #0d3559;
    margin: 0 0 12px;
    text-align: center;
}

.service-description {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
    text-align: center;
    margin: 0;
}

.visi-misi-section {
    margin-top: 32px;
}

.visi-misi-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

.visi-box, .misi-box {
    background: #f9fafb;
    padding: 24px;
    border-radius: 8px;
    border-left: 4px solid #ff6a21;
}

.visi-box h3, .misi-box h3 {
    font-size: 16px;
    font-weight: 700;
    color: #0d3559;
    margin: 0 0 16px;
}

.visi-text {
    font-size: 14px;
    color: #374151;
    line-height: 1.6;
    margin: 0;
}

.misi-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.misi-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
}

.misi-bullet {
    color: #ff6a21;
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.misi-text {
    font-size: 14px;
    color: #374151;
    line-height: 1.6;
    flex: 1;
}

.legal-section {
    margin-top: 32px;
}

.legal-rows {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.legal-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.legal-item {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.legal-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.legal-value {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.formation-section {
    margin-top: 32px;
}

.formation-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.structure-field {
    margin-top: 20px;
}

.structure-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.structure-desc {
    font-size: 13px;
    color: #6b7280;
    font-style: italic;
    margin: 0;
}

.policy-section {
    margin-top: 32px;
}

.policy-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.policy-field {
    margin-bottom: 20px;
}

.policy-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.policy-link {
    font-size: 14px;
    color: #0066cc;
    text-decoration: none;
    word-break: break-all;
}

.policy-link:hover {
    text-decoration: underline;
    color: #ff6a21;
}

.fi {
    font-size: inherit;
}

.edit-button {
    text-align: right;
    margin-bottom: 20px;
}

/* FORM CONTROLS */
.form-controls {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.form-controls .btn {
    flex: 1;
    justify-content: center;
}

/* NOTIFICATION SYSTEM */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    z-index: 10000;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-width: 400px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification.show {
    transform: translateX(0);
    opacity: 1;
}

.notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
    border-left: 4px solid #047857;
}

.notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-left: 4px solid #b91c1c;
}

.notification.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-left: 4px solid #b45309;
}

.notification.info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-left: 4px solid #1d4ed8;
}

.notification-icon {
    font-size: 18px;
    flex-shrink: 0;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 0;
    margin-left: auto;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.notification-close:hover {
    opacity: 1;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .main-content {
        padding: 20px;
    }

    .card {
        padding: 24px;
    }

    .form-row,
    .form-row.three {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .services {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    .services-cards {
        grid-template-columns: 1fr 1fr;
    }

    .company-info-grid {
        grid-template-columns: 1fr;
    }

    .visi-misi-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 16px;
    }

    .card {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .services {
        grid-template-columns: 1fr;
    }

    .services-cards {
        grid-template-columns: 1fr;
    }

    .legal-row {
        grid-template-columns: 1fr;
    }

    .formation-grid {
        grid-template-columns: 1fr;
    }

    .policy-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .tabs {
        flex-direction: column;
    }

    .tab-btn {
        width: 100%;
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<!-- Notification Container -->
<div id="notificationContainer"></div>

<div class="main-content">
    <div class="tab-container">
        <div class="tabs">
            <button class="tab-btn active" data-tab="result">Tampilan Hasil</button>
             <button class="tab-btn" data-tab="input">Input Profile</button>
        </div>
    </div>

    <!-- TAB INPUT FORM -->
    <div id="inputTab" class="tab-content">
        <form id="profileForm" enctype="multipart/form-data">
            @csrf
        <div class="profile">
            {{-- Informasi Perusahaan --}}
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Perusahaan</h4>
                    <div class="status-indicator">
                        <span class="status-dot active"></span>
                        <span>Data Lengkap</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Nama Perusahaan</label>
                        <input type="text" id="namaPerusahaan" value="{{ $profile->nama_perusahaan ?? '' }}">
                        <div class="info-text">Nama resmi sesuai akta pendirian</div>
                    </div>
                    <div class="form-group">
                        <label>Nama Dagang</label>
                        <input type="text" id="namaDagang" value="{{ $profile->nama_dagang ?? '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="required">Deskripsi Singkat</label>
                    <textarea id="deskripsi" rows="3">{{ $profile->deskripsi_singkat ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="required">Alamat</label>
                    <textarea id="alamat" rows="2">{{ $profile->alamat_kantor_pusat ?? '' }}</textarea>
                </div>

                <div class="form-row three">
                    <div class="form-group">
                        <label class="required">Telepon</label>
                        <input type="tel" id="telepon" value="{{ $profile->telepon ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" id="email" value="{{ $profile->email ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="url" id="website" value="{{ $profile->website ?? '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Logo Perusahaan</label>
                        <div class="upload">
                            <span>Upload Logo (PNG/JPG, max 2MB)</span>
                            <input type="file" accept="image/*" class="file-input" id="logoUpload">
                        </div>
                        <div class="upload-preview" id="logoPreview">
                            <img src="" alt="Logo Preview">
                            <div class="upload-actions">
                                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('logoUpload').click()">Ganti</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="removePreview('logoPreview', 'logoUpload')">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Foto Kantor</label>
                        <div class="upload">
                            <span>Upload Foto Kantor (PNG/JPG, max 2MB)</span>
                            <input type="file" accept="image/*" class="file-input" id="officeUpload">
                        </div>
                        <div class="upload-preview" id="officePreview">
                            <img src="" alt="Office Preview">
                            <div class="upload-actions">
                                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('officeUpload').click()">Ganti</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="removePreview('officePreview', 'officeUpload')">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Layanan --}}
            <div class="card">
                <div class="card-header">
                    <h4>Layanan & Unit Bisnis</h4>
                    <span class="badge badge-primary">3 Layanan Aktif</span>
                </div>

                <div class="services" id="servicesContainer">
                    @php
                        $services = [
                            ['id' => 'shuttle', 'name' => 'SmartShuttle', 'desc' => 'Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.'],
                            ['id' => 'send', 'name' => 'SmartSend', 'desc' => 'Layanan pengiriman barang cepat dan aman dengan sistem tracking real-time untuk seluruh Indonesia.'],
                            ['id' => 'rent', 'name' => 'SmartRent', 'desc' => 'Penyewaan kendaraan jangka panjang dengan fasilitas lengkap dan maintenance terjamin.']
                        ];
                    @endphp

                    @foreach ($services as $index => $service)
                    <div class="service" id="service-{{ $service['id'] }}">
                        <div class="form-group">
                            <label>Logo Layanan</label>
                            <div class="upload">
                                <span>Upload Logo {{ $service['name'] }}</span>
                                <input type="file" accept="image/*" class="service-logo-input" data-service="{{ $service['id'] }}">
                            </div>
                            <div class="upload-preview service-preview-{{ $service['id'] }}">
                                <img src="" alt="Service Preview">
                                <div class="upload-actions">
                                    <button type="button" class="btn btn-outline btn-sm change-service" data-service="{{ $service['id'] }}">Ganti</button>
                                    <button type="button" class="btn btn-outline btn-sm remove-service" data-service="{{ $service['id'] }}">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="required">Nama Layanan</label>
                            <input type="text" class="service-name" data-service="{{ $service['id'] }}" value="{{ $service['name'] }}">
                        </div>

                        <div class="form-group">
                            <label class="required">Deskripsi Layanan</label>
                            <textarea rows="3" class="service-desc" data-service="{{ $service['id'] }}">{{ $service['desc'] }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="service-status" data-service="{{ $service['id'] }}">
                                <option value="active" selected>Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="form-controls">
                            <button type="button" class="btn btn-outline btn-sm edit-service" data-service="{{ $service['id'] }}">Edit</button>
                            <button type="button" class="btn btn-outline btn-sm delete-service" data-service="{{ $service['id'] }}">Hapus</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Visi Perusahaan</label>
                    <textarea id="visi" rows="4">{{ $profile->visi ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="required">Misi Perusahaan</label>
                        <textarea id="misi" rows="4">{{ $profile->misi ?? '' }}</textarea>
                    </div>
                </div>

                <div class="card-actions">
                    <button type="button" class="btn btn-outline" id="addServiceBtn">Tambah Layanan</button>
                    <button type="button" class="btn btn-outline" id="refreshServicesBtn">Refresh</button>
                </div>
            </div>

            {{-- Legal --}}
            <div class="card">
                <div class="card-header">
                    <h4>Legal & Administratif</h4>
                    <span class="badge badge-success">Dokumen Lengkap</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">NPWP</label>
                    <input type="text" id="npwp" value="{{ $profile->npwp ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>Kode Izin Penyelenggaraan</label>
                        <input type="text" id="kodeIzin" value="{{ $profile->kode_izin_penyelenggaraan ?? '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>SIUP</label>
                        <input type="text" id="siup" value="{{ $profile->siup ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>NIB</label>
                        <input type="text" id="nib" value="{{ $profile->nib ?? '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nomor Sertifikat Transportasi</label>
                        <input type="text" id="sertifikat" value="{{ $profile->nomor_sertifikat_transportasi ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>TDP</label>
                        <input type="text" id="tdp" value="{{ $profile->tdp ?? '' }}">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label>Upload Dokumen Legal</label>
                        <div class="upload" style="min-height: 120px;">
                            <span>Drag & drop atau klik untuk upload dokumen</span>
                            <div class="upload-text">Format: PDF, JPG, PNG (Max 5MB per file)</div>
                            <input type="file" accept=".pdf,.jpg,.jpeg,.png" multiple id="legalDocs">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pembentukan --}}
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Pembentukan Perusahaan</h4>
                </div>

                <div class="form-row three">
                    <div class="form-group">
                        <label class="required">Tanggal Berdiri</label>
                        <input type="date" id="tanggalBerdiri" value="{{ $profile->tanggal_berdiri ? $profile->tanggal_berdiri->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Penanggung Jawab Utama</label>
                        <input type="text" id="penanggungJawab" value="{{ $profile->penanggung_jawab_utama ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Nama Pendiri</label>
                        <input type="text" id="namaPendiri" value="{{ $profile->nama_pendiri ?? '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Struktur Organisasi</label>
                    <div class="upload" style="min-height: 120px;">
                        <span>Upload Struktur Organisasi (PDF/JPG/PNG)</span>
                        <input type="file" accept=".pdf,.jpg,.jpeg,.png" id="structureUpload">
                    </div>
                </div>
            </div>

            {{-- Link --}}
            <div class="card">
                <div class="card-header">
                    <h4>Link Halaman Kebijakan</h4>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Link Refund</label>
                        <input type="url" id="linkRefund" value="https://smartshuttle.co.id/refund-policy">
                    </div>
                    <div class="form-group">
                        <label>Link Privasi</label>
                        <input type="url" id="linkPrivasi" value="https://smartshuttle.co.id/privacy-policy">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Link Syarat & Ketentuan</label>
                        <input type="url" id="linkSyarat" value="https://smartshuttle.co.id/terms">
                    </div>
                    <div class="form-group">
                        <label>Link Bantuan</label>
                        <input type="url" id="linkBantuan" value="https://smartshuttle.co.id/bantuan">
                    </div>
                </div>

                <div class="form-group">
                    <label>Link FAQ</label>
                    <input type="url" id="linkFaq" value="https://smartshuttle.co.id/faq">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="card">
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Batal</button>
                    <button type="button" class="btn btn-outline" id="previewBtn">Preview</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Simpan Perubahan</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- TAB RESULT VIEW -->
    <div id="resultTab" class="tab-content active">
        <div class="result-content">
            <div class="edit-button">
                <button type="button" class="btn btn-primary" id="editProfileBtn">
                    <i class="fi fi-rr-edit"></i> Edit Profile
                </button>
            </div>

            <h1>Profile Perusahaan</h1>

            <!-- Informasi Perusahaan Section -->
            <div class="card">
                <div class="section-title">Informasi Perusahaan</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="company-info-grid">
                        <!-- Kiri: Nama Perusahaan dan Deskripsi -->
                        <div class="company-left">
                            <div class="info-field">
                                <div class="field-label">Nama Perusahaan</div>
                                <div class="field-value" id="resultNamaPerusahaan">{{ $profile->nama_perusahaan ?? '' }}</div>
                            </div>

                            <div class="info-field">
                                <div class="field-label">Deskripsi Singkat</div>
                                <div class="field-value" id="resultDeskripsi">{{ $profile->deskripsi_singkat ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Kanan: Nama Dagang dan Kontak -->
                        <div class="company-right">
                            <div class="info-field">
                                <div class="field-label">Nama Dagang</div>
                                <div class="field-value" id="resultNamaDagang">{{ $profile->nama_dagang ?? '' }}</div>
                            </div>

                            <div class="contact-field">
                                <div class="field-label">Kontak</div>
                                <div class="contact-details">
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-marker"></i></div>
                                        <div class="contact-text" id="resultAlamat">Alamat: {{ $profile->alamat_kantor_pusat ?? 'Jl. Sudirman No. 45, Jakarta Selatan' }}</div>
                                    </div>
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-phone-call"></i></div>
                                        <div class="contact-text" id="resultTelepon">Telepon: {{ $profile->telepon ?? '(021) 555-1234' }}</div>
                                    </div>
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-envelope"></i></div>
                                        <div class="contact-text" id="resultEmail">Email: {{ $profile->email ?? 'info@smartshuttle.co.id' }}</div>
                                    </div>
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-globe"></i></div>
                                        <div class="contact-text" id="resultWebsite">Website: {{ $profile->website ?? 'www.smartshuttle.co.id' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layanan & Unit Bisnis Section -->
            <div class="card layanan-section">
                <div class="section-title">Layanan & Unit Bisnis</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="services-cards">
                        <!-- SmartShuttle -->
                        <div class="service-big-card">
                            <div class="service-image">
                                <i class="fi fi-rr-bus-alt" style="font-size: 64px;"></i>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">SmartShuttle</h3>
                                <p class="service-description" id="resultDescShuttle">
                                    Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.
                                </p>
                            </div>
                        </div>

                        <!-- SmartSend -->
                        <div class="service-big-card">
                            <div class="service-image">
                                <i class="fi fi-rr-package" style="font-size: 64px;"></i>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">SmartSend</h3>
                                <p class="service-description" id="resultDescSend">{{ $profile->deskripsi_send ?? 'Layanan pengiriman cepat dan aman untuk berbagai jenis paket dengan cakupan luas di seluruh Indonesia.' }}</p>
                            </div>
                        </div>

                        <!-- SmartRent -->
                        <div class="service-big-card">
                            <div class="service-image">
                                <i class="fi fi-rr-car" style="font-size: 64px;"></i>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">SmartRent</h3>
                                <p class="service-description" id="resultDescRent">
                                    Solusi penyewaan kendaraan fleksibel yang memudahkan pelanggan dalam memenuhi kebutuhan transportasi jangka pendek atau panjang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Visi Misi -->
                    <div class="visi-misi-section">
                        <div class="visi-misi-grid">
                            <div class="visi-box">
                                <h3><i class="fi fi-rr-eye" style="margin-right: 8px;"></i> Visi</h3>
                                <p class="visi-text" id="resultVisi">{{ $profile->visi ?? 'Menjadi perusahaan terdepan di Indonesia dalam menyediakan solusi mobilitas dan logistik yang inovatif dan berkelanjutan demi kemudahan masyarakat.' }}</p>
                            </div>

                            <div class="misi-box">
                                <h3><i class="fi fi-rr-target" style="margin-right: 8px;"></i> Misi</h3>
                                <ul class="misi-list" id="resultMisi">
                                    <li class="misi-item">
                                        <div class="misi-bullet"><i class="fi fi-rr-check"></i></div>
                                        <div class="misi-text">Menyediakan layanan transportasi dan logistik yang cepat, aman, dan ramah lingkungan.</div>
                                    </li>
                                    <li class="misi-item">
                                        <div class="misi-bullet"><i class="fi fi-rr-check"></i></div>
                                        <div class="misi-text">Mengoptimalkan penggunaan teknologi untuk meningkatkan efisiensi dan kepuasan pelanggan.</div>
                                    </li>
                                    <li class="misi-item">
                                        <div class="misi-bullet"><i class="fi fi-rr-check"></i></div>
                                        <div class="misi-text">Membangun jaringan luas untuk mendukung mobilitas masyarakat di seluruh Indonesia.</div>
                                    </li>
                                    <li class="misi-item">
                                        <div class="misi-bullet"><i class="fi fi-rr-check"></i></div>
                                        <div class="misi-text">Mengedepankan keselamatan dan kenyamanan dalam setiap layanan yang diberikan.</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal & Administratif Section -->
            <div class="card legal-section">
                <div class="section-title">
                    Legal & Administratif
                    <i class="fi fi-rr-angle-up"></i>
                </div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="legal-rows">
                        <!-- Baris 1 -->
                        <div class="legal-row">
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-document" style="margin-right: 6px;"></i> NPWP</div>
                                <div class="legal-value" id="resultNpwp">{{ $profile->npwp ?? '01.234.567.8-901.000' }}</div>
                            </div>
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-license" style="margin-right: 6px;"></i> Kode Izin Penyelenggaraan</div>
                                <div class="legal-value" id="resultKodeIzin">{{ $profile->kode_izin_penyelenggaraan ?? 'KIP-56789-XYZ' }}</div>
                            </div>
                        </div>

                        <!-- Baris 2 -->
                        <div class="legal-row">
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-file-contract" style="margin-right: 6px;"></i> SIUP</div>
                                <div class="legal-value" id="resultSiup">{{ $profile->siup ?? 'SIUP-2024-12345' }}</div>
                            </div>
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-id-card-clip-alt" style="margin-right: 6px;"></i> NIB</div>
                                <div class="legal-value" id="resultNib">{{ $profile->nib ?? '1234567890123' }}</div>
                            </div>
                        </div>

                        <!-- Baris 3 -->
                        <div class="legal-row">
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-bus" style="margin-right: 6px;"></i> Nomor Sertifikat Transportasi</div>
                                <div class="legal-value" id="resultSertifikat">{{ $profile->nomor_sertifikat_transportasi ?? 'TRNS-00012345' }}</div>
                            </div>
                            <div class="legal-item">
                                <div class="legal-label"><i class="fi fi-rr-file-certificate" style="margin-right: 6px;"></i> TDP</div>
                                <div class="legal-value" id="resultTdp">{{ $profile->tdp ?? 'TDP-2024-98765' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembentukan Perusahaan -->
            <div class="card formation-section">
                <div class="section-title">
                    Informasi Pembentukan Perusahaan
                    <i class="fi fi-rr-angle-up"></i>
                </div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="formation-grid">
                        <div class="legal-item">
                            <div class="legal-label"><i class="fi fi-rr-calendar" style="margin-right: 6px;"></i> Tanggal Berdiri</div>
                            <div class="legal-value" id="resultTanggal">{{ $profile->tanggal_berdiri ? \Carbon\Carbon::parse($profile->tanggal_berdiri)->format('d F Y') : '10 November 2025' }}</div>
                        </div>
                        <div class="legal-item">
                            <div class="legal-label"><i class="fi fi-rr-user" style="margin-right: 6px;"></i> Penanggung Jawab Utama</div>
                            <div class="legal-value" id="resultPenanggung">Dr. Rina Dewi</div>
                        </div>
                        <div class="legal-item">
                            <div class="legal-label"><i class="fi fi-rr-users" style="margin-right: 6px;"></i> Nama Pendiri</div>
                            <div class="legal-value" id="resultPendiri">Ir. Agus Santoso</div>
                        </div>
                    </div>

                    <!-- Struktur Organisasi -->
                    <div class="structure-field">
                        <div class="structure-label"><i class="fi fi-rr-sitemap" style="margin-right: 8px;"></i> Struktur Organisasi</div>
                        <p class="structure-desc">Dokumen struktur organisasi tersedia pada bagian administrasi perusahaan</p>
                    </div>
                </div>
            </div>

            <!-- Link Halaman Kebijakan -->
            <div class="card policy-section">
                <div class="section-title">
                    Link Halaman Kebijakan
                    <i class="fi fi-rr-angle-up"></i>
                </div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="policy-grid">
                        <div class="policy-field">
                            <div class="policy-label"><i class="fi fi-rr-arrow-left" style="margin-right: 6px;"></i> Link Refund</div>
                            <a href="https://smartshuttle.co.id/refund-policy" class="policy-link" target="_blank" id="resultLinkRefund">
                                <i class="fi fi-rr-link" style="margin-right: 6px;"></i> https://smartshuttle.co.id/refund-policy
                            </a>
                        </div>

                        <div class="policy-field">
                            <div class="policy-label"><i class="fi fi-rr-shield-check" style="margin-right: 6px;"></i> Link Privasi</div>
                            <a href="https://smartshuttle.co.id/privacy-policy" class="policy-link" target="_blank" id="resultLinkPrivasi">
                                <i class="fi fi-rr-link" style="margin-right: 6px;"></i> https://smartshuttle.co.id/privacy-policy
                            </a>
                        </div>

                        <div class="policy-field">
                            <div class="policy-label"><i class="fi fi-rr-document-signed" style="margin-right: 6px;"></i> Link Syarat & Ketentuan</div>
                            <a href="https://smartshuttle.co.id/terms" class="policy-link" target="_blank" id="resultLinkSyarat">
                                <i class="fi fi-rr-link" style="margin-right: 6px;"></i> https://smartshuttle.co.id/terms
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ====== NOTIFICATION SYSTEM ======
    function showNotification(message, type = 'info', duration = 5000) {
        const container = document.getElementById('notificationContainer');

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        // Set icon based on type
        let icon = 'fi fi-rr-info';
        if (type === 'success') icon = 'fi fi-rr-check';
        else if (type === 'error') icon = 'fi fi-rr-exclamation';
        else if (type === 'warning') icon = 'fi fi-rr-exclamation-triangle';

        notification.innerHTML = `
            <div class="notification-icon"><i class="${icon}"></i></div>
            <div class="notification-message">${message}</div>
            <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
        `;

        // Add to container
        container.appendChild(notification);

        // Trigger animation
        setTimeout(() => notification.classList.add('show'), 10);

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, duration);
        }

        return notification;
    }

    // ====== TAB SYSTEM ======
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Update active tab button
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Show active tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId + 'Tab') {
                    content.classList.add('active');
                }
            });

            // If switching to result tab, update the view
            if (tabId === 'result') {
                updateResultView();
            }
        });
    });

    // ====== INPUT FORM FUNCTIONALITY ======

    // File upload functionality
    function setupFileUploads() {
        const uploadAreas = document.querySelectorAll('.upload');

        uploadAreas.forEach(uploadArea => {
            const fileInput = uploadArea.querySelector('input[type="file"]');
            const parent = uploadArea.closest('.form-group');
            const preview = parent.querySelector('.upload-preview');

            // Click on upload area triggers file input
            uploadArea.addEventListener('click', function(e) {
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });

            // File input change event
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        if (preview) {
                            const img = preview.querySelector('img');
                            if (img) {
                                img.src = e.target.result;
                            }
                            preview.style.display = 'block';
                        }
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    }

    // Remove preview
    window.removePreview = function(previewId, inputId) {
        const preview = document.getElementById(previewId);
        const input = document.getElementById(inputId);

        if (preview) {
            const img = preview.querySelector('img');
            if (img) img.src = '';
            preview.style.display = 'none';
        }

        if (input) input.value = '';
    }

    // Service management
    let serviceCount = 3; // Starting with 3 services

    document.getElementById('addServiceBtn').addEventListener('click', function() {
        const servicesContainer = document.getElementById('servicesContainer');
        const newId = 'service-' + Date.now();

        const newService = document.createElement('div');
        newService.className = 'service';
        newService.id = newId;
        newService.innerHTML = `
            <div class="form-group">
                <label>Logo Layanan</label>
                <div class="upload">
                    <span>Upload Logo Layanan Baru</span>
                    <input type="file" accept="image/*" class="service-logo-input" data-service="${newId}">
                </div>
                <div class="upload-preview service-preview-${newId}">
                    <img src="" alt="Service Preview">
                    <div class="upload-actions">
                        <button type="button" class="btn btn-outline btn-sm change-service" data-service="${newId}">Ganti</button>
                        <button type="button" class="btn btn-outline btn-sm remove-service" data-service="${newId}">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="required">Nama Layanan</label>
                <input type="text" class="service-name" data-service="${newId}" placeholder="Nama Layanan Baru">
            </div>

            <div class="form-group">
                <label class="required">Deskripsi Layanan</label>
                <textarea rows="3" class="service-desc" data-service="${newId}" placeholder="Deskripsi layanan baru"></textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="service-status" data-service="${newId}">
                    <option value="active" selected>Aktif</option>
                    <option value="inactive">Non-Aktif</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-controls">
                <button type="button" class="btn btn-outline btn-sm edit-service" data-service="${newId}">Edit</button>
                <button type="button" class="btn btn-outline btn-sm delete-service" data-service="${newId}">Hapus</button>
            </div>
        `;

        servicesContainer.appendChild(newService);
        serviceCount++;

        // Update badge
        const badge = document.querySelector('.badge-primary');
        if (badge) {
            badge.textContent = serviceCount + ' Layanan Aktif';
        }

        // Re-setup event listeners
        setupServiceEvents(newId);
        setupFileUploads();
    });

    function setupServiceEvents(serviceId) {
        const serviceElement = document.getElementById(serviceId);
        if (!serviceElement) return;

        // Edit service
        const editBtn = serviceElement.querySelector('.edit-service');
        if (editBtn) {
            editBtn.addEventListener('click', function() {
                alert('Edit layanan: ' + serviceId);
            });
        }

        // Delete service
        const deleteBtn = serviceElement.querySelector('.delete-service');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                    serviceElement.remove();
                    serviceCount--;

                    // Update badge
                    const badge = document.querySelector('.badge-primary');
                    if (badge) {
                        badge.textContent = serviceCount + ' Layanan Aktif';
                    }
                }
            });
        }

        // Change service image
        const changeBtn = serviceElement.querySelector('.change-service');
        const removeBtn = serviceElement.querySelector('.remove-service');
        const fileInput = serviceElement.querySelector('.service-logo-input');

        if (changeBtn && fileInput) {
            changeBtn.addEventListener('click', function() {
                fileInput.click();
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                const preview = serviceElement.querySelector('.upload-preview');
                if (preview) {
                    const img = preview.querySelector('img');
                    if (img) img.src = '';
                    preview.style.display = 'none';
                }
                if (fileInput) fileInput.value = '';
            });
        }
    }

    // Initialize service events
    ['service-shuttle', 'service-send', 'service-rent'].forEach(serviceId => {
        setupServiceEvents(serviceId);
    });

    // ====== SAVE DATA ======
    document.getElementById('saveBtn').addEventListener('click', async function() {
        const saveBtn = this;
        const originalText = saveBtn.innerHTML;

        try {
            console.log('Save button clicked');

            // Validasi form sebelum submit
            if (!validateForm()) {
                return;
            }

            // Show loading state
            saveBtn.classList.add('loading');
            saveBtn.innerHTML = '<i class="fi fi-rr-spinner"></i> Menyimpan...';
            saveBtn.disabled = true;

            // Create FormData object
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Collect all form data
            collectFormDataIntoFormData(formData);

            // Log untuk debugging
            console.log('Sending data to:', '/admin/profile-perusahaan/update');

            // Create AbortController untuk timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 detik timeout

            // Send data to server using AJAX
            const response = await fetch('/admin/profile-perusahaan/update', {
                method: 'POST',
                body: formData,
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            clearTimeout(timeoutId);

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                showNotification(data.message, 'success');

                // Update result view dengan data dari response server
                if (data.data) {
                    console.log('Updating with server response data:', data.data);
                    updateResultView(data.data);
                } else {
                    console.log('No data in response, using form data');
                    // Fallback to form data
                    updateResultViewFromForm();
                }

                // Force reload the page to get fresh data from database
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Gagal menyimpan data');
            }
        } catch (error) {
            console.error('Error:', error);

            if (error.name === 'AbortError') {
                showNotification('Waktu permintaan habis. Silakan coba lagi.', 'error');
            } else if (error.message) {
                showNotification(error.message, 'error');
            } else {
                showNotification('Terjadi kesalahan jaringan atau server tidak merespon.', 'error');
            }
        } finally {
            // Reset button state
            saveBtn.classList.remove('loading');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }
    });

    // Fungsi validasi form
    function validateForm() {
        const requiredFields = [
            { id: 'namaPerusahaan', name: 'Nama Perusahaan' },
            { id: 'deskripsi', name: 'Deskripsi Singkat' },
            { id: 'alamat', name: 'Alamat' },
            { id: 'telepon', name: 'Telepon' },
            { id: 'email', name: 'Email' },
            { id: 'visi', name: 'Visi' },
            { id: 'misi', name: 'Misi' },
            { id: 'npwp', name: 'NPWP' },
            { id: 'tanggalBerdiri', name: 'Tanggal Berdiri' },
            { id: 'penanggungJawab', name: 'Penanggung Jawab Utama' },
            { id: 'namaPendiri', name: 'Nama Pendiri' }
        ];

        for (const field of requiredFields) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                showNotification(`${field.name} harus diisi`, 'error');
                element?.focus();
                return false;
            }
        }

        // Validasi email
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showNotification('Format email tidak valid', 'error');
            document.getElementById('email').focus();
            return false;
        }

        return true;
    }

    // Fungsi mengumpulkan data ke FormData
    function collectFormDataIntoFormData(formData) {
        // Add form fields
        const tanggalBerdiriValue = document.getElementById('tanggalBerdiri').value;
        console.log('Tanggal berdiri raw value:', tanggalBerdiriValue);

        const formDataObj = {
            nama_perusahaan: document.getElementById('namaPerusahaan').value,
            nama_dagang: document.getElementById('namaDagang').value,
            deskripsi_singkat: document.getElementById('deskripsi').value,
            alamat_kantor_pusat: document.getElementById('alamat').value,
            telepon: document.getElementById('telepon').value,
            email: document.getElementById('email').value,
            website: document.getElementById('website').value,
            visi: document.getElementById('visi').value,
            misi: document.getElementById('misi').value,
            npwp: document.getElementById('npwp').value,
            kode_izin_penyelenggaraan: document.getElementById('kodeIzin').value,
            siup: document.getElementById('siup').value,
            nib: document.getElementById('nib').value,
            nomor_sertifikat_transportasi: document.getElementById('sertifikat').value,
            tdp: document.getElementById('tdp').value,
            tanggal_berdiri: tanggalBerdiriValue,
            penanggung_jawab_utama: document.getElementById('penanggungJawab').value,
            nama_pendiri: document.getElementById('namaPendiri').value,
            link_kebijakan_refund: document.getElementById('linkRefund').value,
            link_kebijakan_privasi: document.getElementById('linkPrivasi').value,
            link_syarat_ketentuan: document.getElementById('linkSyarat').value,
            link_bantuan: document.getElementById('linkBantuan').value,
            link_faq: document.getElementById('linkFaq').value
        };

        console.log('Form data being sent:', formDataObj);

        // Add to FormData
        Object.keys(formDataObj).forEach(key => {
            formData.append(key, formDataObj[key]);
        });

        // Handle file uploads
        const logoInput = document.getElementById('logoUpload');
        if (logoInput.files && logoInput.files[0]) {
            // Validasi ukuran file (max 2MB)
            if (logoInput.files[0].size > 2 * 1024 * 1024) {
                throw new Error('Logo perusahaan terlalu besar. Maksimal 2MB');
            }
            formData.append('logo_perusahaan', logoInput.files[0]);
        }

        const officeInput = document.getElementById('officeUpload');
        if (officeInput.files && officeInput.files[0]) {
            if (officeInput.files[0].size > 2 * 1024 * 1024) {
                throw new Error('Foto kantor terlalu besar. Maksimal 2MB');
            }
            formData.append('background_website', officeInput.files[0]);
        }

        const structureInput = document.getElementById('structureUpload');
        if (structureInput.files && structureInput.files[0]) {
            formData.append('struktur_organisasi', structureInput.files[0]);
        }

        // Collect service data
        const serviceElements = document.querySelectorAll('.service');
        serviceElements.forEach((service, index) => {
            const nameInput = service.querySelector('.service-name');
            const descInput = service.querySelector('.service-desc');
            const statusSelect = service.querySelector('.service-status');
            const fileInput = service.querySelector('.service-logo-input');

            if (nameInput && descInput) {
                const serviceId = nameInput.getAttribute('data-service');

                formData.append(`layanan[${index}][id]`, serviceId);
                formData.append(`layanan[${index}][nama]`, nameInput.value);
                formData.append(`layanan[${index}][deskripsi]`, descInput.value);
                formData.append(`layanan[${index}][status]`, statusSelect ? statusSelect.value : 'active');

                if (fileInput && fileInput.files && fileInput.files[0]) {
                    if (fileInput.files[0].size > 2 * 1024 * 1024) {
                        throw new Error(`Logo layanan ${nameInput.value} terlalu besar. Maksimal 2MB`);
                    }
                    formData.append(`layanan[${index}][logo]`, fileInput.files[0]);
                }
            }
        });
    }

    // Fungsi update result view dari form
    function updateResultViewFromForm() {
        const data = {
            nama_perusahaan: document.getElementById('namaPerusahaan').value,
            nama_dagang: document.getElementById('namaDagang').value,
            deskripsi_singkat: document.getElementById('deskripsi').value,
            alamat_kantor_pusat: document.getElementById('alamat').value,
            telepon: document.getElementById('telepon').value,
            email: document.getElementById('email').value,
            website: document.getElementById('website').value,
            visi: document.getElementById('visi').value,
            misi: document.getElementById('misi').value,
            npwp: document.getElementById('npwp').value,
            kode_izin_penyelenggaraan: document.getElementById('kodeIzin').value,
            siup: document.getElementById('siup').value,
            nib: document.getElementById('nib').value,
            nomor_sertifikat_transportasi: document.getElementById('sertifikat').value,
            tdp: document.getElementById('tdp').value,
            tanggal_berdiri: document.getElementById('tanggalBerdiri').value,
            penanggung_jawab_utama: document.getElementById('penanggungJawab').value,
            nama_pendiri: document.getElementById('namaPendiri').value,
            link_refund: document.getElementById('linkRefund').value,
            link_privasi: document.getElementById('linkPrivasi').value,
            link_syarat: document.getElementById('linkSyarat').value
        };

        updateResultView(data);
    }

    // ====== PREVIEW BUTTON ======
    document.getElementById('previewBtn').addEventListener('click', function() {
        // Collect form data
        const formData = collectFormData();

        // Save to session for preview
        sessionStorage.setItem('preview_profile_data', JSON.stringify(formData));

        // Update result view with current data
        updateResultView();

        // Switch to result tab
        document.querySelector('.tab-btn[data-tab="result"]').click();
    });

    // ====== EDIT PROFILE BUTTON ======
    document.getElementById('editProfileBtn').addEventListener('click', function() {
        document.querySelector('.tab-btn[data-tab="input"]').click();
    });

    // ====== CANCEL BUTTON ======
    document.getElementById('cancelBtn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
            // Reload page or reset form
            location.reload();
        }
    });

    // ====== REFRESH SERVICES BUTTON ======
    document.getElementById('refreshServicesBtn').addEventListener('click', function() {
        if (confirm('Refresh akan mengembalikan layanan ke keadaan default. Lanjutkan?')) {
            // Reset services to default
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = `
                <div class="service" id="service-shuttle">
                    <div class="form-group">
                        <label>Logo Layanan</label>
                        <div class="upload">
                            <span>Upload Logo SmartShuttle</span>
                            <input type="file" accept="image/*" class="service-logo-input" data-service="shuttle">
                        </div>
                        <div class="upload-preview service-preview-shuttle">
                            <img src="" alt="Service Preview">
                            <div class="upload-actions">
                                <button type="button" class="btn btn-outline btn-sm change-service" data-service="shuttle">Ganti</button>
                                <button type="button" class="btn btn-outline btn-sm remove-service" data-service="shuttle">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Nama Layanan</label>
                        <input type="text" class="service-name" data-service="shuttle" value="SmartShuttle">
                    </div>

                    <div class="form-group">
                        <label class="required">Deskripsi Layanan</label>
                        <textarea rows="3" class="service-desc" data-service="shuttle">Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.</textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="service-status" data-service="shuttle">
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="form-controls">
                        <button type="button" class="btn btn-outline btn-sm edit-service" data-service="shuttle">Edit</button>
                        <button type="button" class="btn btn-outline btn-sm delete-service" data-service="shuttle">Hapus</button>
                    </div>
                </div>

                <div class="service" id="service-send">
                    <div class="form-group">
                        <label>Logo Layanan</label>
                        <div class="upload">
                            <span>Upload Logo SmartSend</span>
                            <input type="file" accept="image/*" class="service-logo-input" data-service="send">
                        </div>
                        <div class="upload-preview service-preview-send">
                            <img src="" alt="Service Preview">
                            <div class="upload-actions">
                                <button type="button" class="btn btn-outline btn-sm change-service" data-service="send">Ganti</button>
                                <button type="button" class="btn btn-outline btn-sm remove-service" data-service="send">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Nama Layanan</label>
                        <input type="text" class="service-name" data-service="send" value="SmartSend">
                    </div>

                    <div class="form-group">
                        <label class="required">Deskripsi Layanan</label>
                        <textarea rows="3" class="service-desc" data-service="send">Layanan pengiriman barang cepat dan aman dengan sistem tracking real-time untuk seluruh Indonesia.</textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="service-status" data-service="send">
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="form-controls">
                        <button type="button" class="btn btn-outline btn-sm edit-service" data-service="send">Edit</button>
                        <button type="button" class="btn btn-outline btn-sm delete-service" data-service="send">Hapus</button>
                    </div>
                </div>

                <div class="service" id="service-rent">
                    <div class="form-group">
                        <label>Logo Layanan</label>
                        <div class="upload">
                            <span>Upload Logo SmartRent</span>
                            <input type="file" accept="image/*" class="service-logo-input" data-service="rent">
                        </div>
                        <div class="upload-preview service-preview-rent">
                            <img src="" alt="Service Preview">
                            <div class="upload-actions">
                                <button type="button" class="btn btn-outline btn-sm change-service" data-service="rent">Ganti</button>
                                <button type="button" class="btn btn-outline btn-sm remove-service" data-service="rent">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Nama Layanan</label>
                        <input type="text" class="service-name" data-service="rent" value="SmartRent">
                    </div>

                    <div class="form-group">
                        <label class="required">Deskripsi Layanan</label>
                        <textarea rows="3" class="service-desc" data-service="rent">Penyewaan kendaraan jangka panjang dengan fasilitas lengkap dan maintenance terjamin.</textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="service-status" data-service="rent">
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="form-controls">
                        <button type="button" class="btn btn-outline btn-sm edit-service" data-service="rent">Edit</button>
                        <button type="button" class="btn btn-outline btn-sm delete-service" data-service="rent">Hapus</button>
                    </div>
                </div>
            `;

            serviceCount = 3;

            // Update badge
            const badge = document.querySelector('.badge-primary');
            if (badge) {
                badge.textContent = serviceCount + ' Layanan Aktif';
            }

            // Re-setup event listeners
            ['service-shuttle', 'service-send', 'service-rent'].forEach(serviceId => {
                setupServiceEvents(serviceId);
            });

            setupFileUploads();

            alert('Layanan berhasil di-refresh ke keadaan default.');
        }
    });

    // ====== COLLAPSE/EXPAND FUNCTIONALITY ======
    function setupCollapseExpand() {
        const collapseSections = [
            '.legal-section .section-title',
            '.formation-section .section-title',
            '.policy-section .section-title'
        ];

        collapseSections.forEach(selector => {
            const titleElement = document.querySelector(selector);
            if (titleElement) {
                const contentElement = titleElement.nextElementSibling;
                const iconElement = titleElement.querySelector('i');

                // Set initial state
                contentElement.style.maxHeight = contentElement.scrollHeight + 'px';
                contentElement.style.opacity = '1';

                titleElement.addEventListener('click', function() {
                    if (contentElement.style.maxHeight && contentElement.style.maxHeight !== '0px') {
                        // Collapse
                        contentElement.style.maxHeight = '0px';
                        contentElement.style.opacity = '0';
                        contentElement.style.paddingTop = '0';
                        contentElement.style.paddingBottom = '0';
                        contentElement.style.overflow = 'hidden';
                        if (iconElement) iconElement.style.transform = 'rotate(180deg)';
                    } else {
                        // Expand
                        contentElement.style.maxHeight = contentElement.scrollHeight + 'px';
                        contentElement.style.opacity = '1';
                        contentElement.style.paddingTop = '';
                        contentElement.style.paddingBottom = '';
                        contentElement.style.overflow = '';
                        if (iconElement) iconElement.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });
    }

    // ====== COLLECT FORM DATA ======
    function collectFormData() {
        const formData = {
            nama_perusahaan: document.getElementById('namaPerusahaan').value,
            nama_dagang: document.getElementById('namaDagang').value,
            deskripsi: document.getElementById('deskripsi').value,
            alamat: document.getElementById('alamat').value,
            telepon: document.getElementById('telepon').value,
            email: document.getElementById('email').value,
            website: document.getElementById('website').value,
            visi: document.getElementById('visi').value,
            misi: document.getElementById('misi').value,
            npwp: document.getElementById('npwp').value,
            kode_izin: document.getElementById('kodeIzin').value,
            siup: document.getElementById('siup').value,
            nib: document.getElementById('nib').value,
            sertifikat: document.getElementById('sertifikat').value,
            tdp: document.getElementById('tdp').value,
            tanggal_berdiri: document.getElementById('tanggalBerdiri').value,
            penanggung_jawab: document.getElementById('penanggungJawab').value,
            nama_pendiri: document.getElementById('namaPendiri').value,
            link_refund: document.getElementById('linkRefund').value,
            link_privasi: document.getElementById('linkPrivasi').value,
            link_syarat: document.getElementById('linkSyarat').value,
            link_bantuan: document.getElementById('linkBantuan').value,
            link_faq: document.getElementById('linkFaq').value,
            layanan: {}
        };

        // Collect service data
        const serviceElements = document.querySelectorAll('.service');
        const serviceKeys = ['shuttle', 'send', 'rent'];

        serviceElements.forEach((service, index) => {
            const nameInput = service.querySelector('.service-name');
            const descInput = service.querySelector('.service-desc');
            const statusSelect = service.querySelector('.service-status');

            if (nameInput && descInput) {
                const key = serviceKeys[index] || 'service' + index;
                formData.layanan[key] = {
                    name: nameInput.value,
                    description: descInput.value,
                    status: statusSelect ? statusSelect.value : 'active'
                };
            }
        });

        return formData;
    }

    // ====== UPDATE RESULT VIEW ======
    function updateResultView(data = null) {
        if (!data) {
            // Try to get data from sessionStorage (preview) or localStorage (saved)
            const previewData = sessionStorage.getItem('preview_profile_data');
            const savedData = localStorage.getItem('profile_perusahaan_data');

            if (previewData) {
                data = JSON.parse(previewData);
            } else if (savedData) {
                data = JSON.parse(savedData);
            } else {
                // Use current form values
                data = collectFormData();
            }
        }

        console.log('Updating result view with data:', data);

        // Informasi Perusahaan
        const resultNamaPerusahaan = document.getElementById('resultNamaPerusahaan');
        const resultNamaDagang = document.getElementById('resultNamaDagang');
        const resultDeskripsi = document.getElementById('resultDeskripsi');
        const resultAlamat = document.getElementById('resultAlamat');
        const resultTelepon = document.getElementById('resultTelepon');
        const resultEmail = document.getElementById('resultEmail');
        const resultWebsite = document.getElementById('resultWebsite');

        if (resultNamaPerusahaan) resultNamaPerusahaan.textContent = data.nama_perusahaan || '';
        if (resultNamaDagang) resultNamaDagang.textContent = data.nama_dagang || '';
        if (resultDeskripsi) resultDeskripsi.textContent = data.deskripsi_singkat || data.deskripsi || '';
        if (resultAlamat) resultAlamat.textContent = 'Alamat: ' + (data.alamat_kantor_pusat || data.alamat || '');
        if (resultTelepon) resultTelepon.textContent = 'Telepon: ' + (data.telepon || '');
        if (resultEmail) resultEmail.textContent = 'Email: ' + (data.email || '');
        if (resultWebsite) resultWebsite.textContent = 'Website: ' + (data.website || '');

        // Visi Misi
        const resultVisi = document.getElementById('resultVisi');
        const resultMisi = document.getElementById('resultMisi');

        if (resultVisi) resultVisi.textContent = data.visi || '';

        // Format misi menjadi list
        if (resultMisi && data.misi) {
            const misiItems = data.misi.split('\n').filter(item => item.trim() !== '');
            resultMisi.innerHTML = '';

            misiItems.forEach(item => {
                const li = document.createElement('li');
                li.className = 'misi-item';
                li.innerHTML = `
                    <div class="misi-bullet"><i class="fi fi-rr-check"></i></div>
                    <div class="misi-text">${item.trim().replace('• ', '')}</div>
                `;
                resultMisi.appendChild(li);
            });
        }

        // Legal - Update all legal fields
        const resultNpwp = document.getElementById('resultNpwp');
        const resultKodeIzin = document.getElementById('resultKodeIzin');
        const resultSiup = document.getElementById('resultSiup');
        const resultNib = document.getElementById('resultNib');
        const resultSertifikat = document.getElementById('resultSertifikat');
        const resultTdp = document.getElementById('resultTdp');

        if (resultNpwp) resultNpwp.textContent = data.npwp || '';
        if (resultKodeIzin) resultKodeIzin.textContent = data.kode_izin_penyelenggaraan || data.kode_izin || '';
        if (resultSiup) resultSiup.textContent = data.siup || '';
        if (resultNib) resultNib.textContent = data.nib || '';
        if (resultSertifikat) resultSertifikat.textContent = data.nomor_sertifikat_transportasi || data.sertifikat || '';
        if (resultTdp) resultTdp.textContent = data.tdp || '';

        // Pembentukan
        const resultTanggal = document.getElementById('resultTanggal');
        const resultPenanggung = document.getElementById('resultPenanggung');
        const resultPendiri = document.getElementById('resultPendiri');

        if (resultTanggal) {
            if (data.tanggal_berdiri) {
                const date = new Date(data.tanggal_berdiri);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                resultTanggal.textContent = date.toLocaleDateString('id-ID', options);
            } else {
                resultTanggal.textContent = '';
            }
        }

        if (resultPenanggung) resultPenanggung.textContent = data.penanggung_jawab_utama || data.penanggung_jawab || '';
        if (resultPendiri) resultPendiri.textContent = data.nama_pendiri || '';

        // Kebijakan - Update link policy fields
        const linkRefund = document.getElementById('resultLinkRefund');
        const linkPrivasi = document.getElementById('resultLinkPrivasi');
        const linkSyarat = document.getElementById('resultLinkSyarat');

        if (linkRefund && (data.link_kebijakan_refund || data.link_refund)) {
            const link = data.link_kebijakan_refund || data.link_refund;
            linkRefund.href = link;
            linkRefund.innerHTML = '<i class="fi fi-rr-link" style="margin-right: 6px;"></i> ' + link;
        }

        if (linkPrivasi && (data.link_kebijakan_privasi || data.link_privasi)) {
            const link = data.link_kebijakan_privasi || data.link_privasi;
            linkPrivasi.href = link;
            linkPrivasi.innerHTML = '<i class="fi fi-rr-link" style="margin-right: 6px;"></i> ' + link;
        }

        if (linkSyarat && (data.link_syarat_ketentuan || data.link_syarat)) {
            const link = data.link_syarat_ketentuan || data.link_syarat;
            linkSyarat.href = link;
            linkSyarat.innerHTML = '<i class="fi fi-rr-link" style="margin-right: 6px;"></i> ' + link;
        }

        // Save to localStorage for persistence
        localStorage.setItem('profile_perusahaan_data', JSON.stringify(data));
    }

    // ====== HOVER EFFECTS FOR SERVICE CARDS ======
    function setupServiceCardHover() {
        const serviceCards = document.querySelectorAll('.service-big-card');

        serviceCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const image = this.querySelector('.service-image');
                if (image) {
                    image.style.transform = 'scale(1.05)';
                    image.style.transition = 'transform 0.3s ease';
                }
            });

            card.addEventListener('mouseleave', function() {
                const image = this.querySelector('.service-image');
                if (image) {
                    image.style.transform = 'scale(1)';
                }
            });
        });
    }

    // ====== INITIALIZATION ======
    function init() {
        setupFileUploads();
        setupCollapseExpand();
        setupServiceCardHover();

        // Load saved data if exists
        const savedData = localStorage.getItem('profile_perusahaan_data');
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                // You could auto-fill the form with saved data here if needed
            } catch (e) {
                console.error('Error parsing saved data:', e);
            }
        }
    }

    // Initialize everything
    init();
});
</script>
@endpush
@endsection
