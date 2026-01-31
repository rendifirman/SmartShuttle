@extends('layouts.app-admin')

@section('title', 'Profile Perusahaan - Smart Shuttle Admin')
@section('page-title', 'Kontak Perusahaan')

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
    display: flex;
    align-items: center;
    gap: 4px;
}

.form-group label.required::after {
    content: '*';
    color: #ef4444;
    margin-left: 4px;
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

/* URL Helper Text */
.url-helper {
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
    display: block;
}

.url-helper i {
    font-size: 10px;
    margin-right: 4px;
}

/* JAM OPERASIONAL */
.jam-operasional {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.jam-item {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #e5e7eb;
}

.jam-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.jam-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.jam-input {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

/* SOCIAL MEDIA ICONS */
.social-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.social-input {
    position: relative;
}

.social-input i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 18px;
}

.social-input input {
    padding-left: 40px !important;
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

/* SOCIAL MEDIA */
.social-section {
    margin-top: 32px;
}

.social-grid-result {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.social-card {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    text-align: center;
}

.social-card:hover {
    border-color: #ff6a21;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 106, 33, 0.1);
}

.social-icon {
    font-size: 32px;
    margin-bottom: 12px;
    color: #ff6a21;
}

.social-name {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.social-link {
    font-size: 12px;
    color: #6b7280;
    word-break: break-all;
}

/* JAM OPERASIONAL RESULT */
.jam-operasional-result {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.jam-item-result {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed #e5e7eb;
}

.jam-item-result:last-child {
    border-bottom: none;
}

.jam-day {
    font-weight: 600;
    color: #374151;
}

.jam-time {
    color: #6b7280;
    font-weight: 500;
}

/* POLICY LINKS */
.policy-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.policy-link-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.policy-link-item i {
    color: #ff6a21;
}

.policy-link-text {
    flex: 1;
    font-size: 14px;
    color: #374151;
}

.policy-link-url {
    font-size: 13px;
    color: #3b82f6;
    text-decoration: none;
    word-break: break-all;
    max-width: 300px;
}

.policy-link-url:hover {
    text-decoration: underline;
}

.edit-button {
    text-align: right;
    margin-bottom: 20px;
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

    .company-info-grid {
        grid-template-columns: 1fr;
    }

    .social-grid,
    .social-grid-result {
        grid-template-columns: 1fr 1fr;
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

    .social-grid,
    .social-grid-result {
        grid-template-columns: 1fr;
    }

    .jam-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .policy-link-url {
        max-width: 200px;
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
            <button class="tab-btn" data-tab="input">Input Kontak</button>
        </div>
    </div>

    <!-- TAB INPUT FORM -->
    <div id="inputTab" class="tab-content">
        <form id="kontakForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
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

                    <div class="form-group">
                        <label class="required">Nama Perusahaan</label>
                        <input type="text" id="namaPerusahaan" name="nama_perusahaan" value="{{ $kontak['nama_perusahaan'] ?? 'Citra Solusi Teknologi' }}">
                    </div>

                    <div class="form-group">
                        <label class="required">Deskripsi Singkat</label>
                        <textarea id="deskripsiSingkat" name="deskripsi_singkat" rows="3">{{ $kontak['deskripsi_singkat'] ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas Anda' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="required">Alamat Kantor Pusat</label>
                        <textarea id="alamatKantorPusat" name="alamat_kantor_pusat" rows="2">{{ $kontak['alamat_kantor_pusat'] ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Email Utama</label>
                            <input type="email" id="emailUtama" name="email_utama" value="{{ $kontak['email_utama'] ?? 'rndcitrasolusi@gmail.com' }}">
                        </div>
                        <div class="form-group">
                            <label>Email Dukungan</label>
                            <input type="email" id="emailDukungan" name="email_dukungan" value="{{ $kontak['email_dukungan'] ?? 'support@smartshuttle.com' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Telepon Utama</label>
                            <input type="tel" id="teleponUtama" name="telepon_utama" value="{{ $kontak['telepon_utama'] ?? '0858-1122-4321' }}">
                        </div>
                        <div class="form-group">
                            <label>Telepon Dukungan</label>
                            <input type="tel" id="teleponDukungan" name="telepon_dukungan" value="{{ $kontak['telepon_dukungan'] ?? '0858-1122-4321' }}">
                        </div>
                    </div>
                </div>

                {{-- Media Sosial --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Media Sosial</h4>
                    </div>

                    <div class="social-grid">
                        <div class="form-group social-input">
                            <label>Facebook</label>
                            <i class="fi fi-brands-facebook"></i>
                            <input type="text" id="facebookUrl" name="facebook_url"
                                   placeholder="facebook.com/username"
                                   value="{{ $kontak['facebook_url'] ?? 'https://facebook.com/smartshuttle' }}">
                            <span class="url-helper"><i class="fi fi-rr-info"></i> Contoh: facebook.com/smartshuttle atau https://facebook.com/smartshuttle</span>
                        </div>

                        <div class="form-group social-input">
                            <label>Instagram</label>
                            <i class="fi fi-brands-instagram"></i>
                            <input type="text" id="instagramUrl" name="instagram_url"
                                   placeholder="instagram.com/username"
                                   value="{{ $kontak['instagram_url'] ?? 'https://instagram.com/smartshuttle' }}">
                            <span class="url-helper"><i class="fi fi-rr-info"></i> Contoh: instagram.com/smartshuttle atau https://instagram.com/smartshuttle</span>
                        </div>

                        <div class="form-group social-input">
                            <label>Twitter</label>
                            <i class="fi fi-brands-twitter"></i>
                            <input type="text" id="twitterUrl" name="twitter_url"
                                   placeholder="twitter.com/username"
                                   value="{{ $kontak['twitter_url'] ?? 'https://twitter.com/smartshuttle' }}">
                            <span class="url-helper"><i class="fi fi-rr-info"></i> Contoh: twitter.com/smartshuttle atau https://twitter.com/smartshuttle</span>
                        </div>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Jam Operasional</h4>
                    </div>

                    <div class="jam-operasional">
                        @php
                            $jamOperasional = [];

                            if (isset($kontak['jam_operasional'])) {
                                if (is_string($kontak['jam_operasional'])) {
                                    $decoded = @json_decode($kontak['jam_operasional'], true);
                                    if ($decoded && is_array($decoded)) {
                                        $jamOperasional = $decoded;
                                    }
                                } elseif (is_array($kontak['jam_operasional'])) {
                                    $jamOperasional = $kontak['jam_operasional'];
                                }
                            }

                            if (empty($jamOperasional)) {
                                $jamOperasional = [
                                    ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                                    ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                                    ['hari' => 'Minggu', 'jam' => 'Tutup']
                                ];
                            }
                        @endphp

                        @foreach($jamOperasional as $index => $jam)
                        <div class="jam-item" data-index="{{ $index }}">
                            <div class="jam-label">{{ $jam['hari'] ?? 'Hari' }}</div>
                            <input type="hidden" class="jam-hari-input" name="jam_hari[{{ $index }}]" value="{{ $jam['hari'] ?? '' }}">
                            <input type="text" class="jam-input jam-waktu" name="jam_waktu[{{ $index }}]" value="{{ $jam['jam'] ?? '' }}" placeholder="Contoh: 08:00 - 17:00">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kebijakan --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Link Kebijakan</h4>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kebijakan Privasi</label>
                            <input type="text" id="linkKebijakanPrivasi" name="link_kebijakan_privasi"
                                   placeholder="contoh.com/privacy"
                                   value="{{ $kontak['link_kebijakan_privasi'] ?? '#' }}">
                            <span class="url-helper"><i class="fi fi-rr-info"></i> Kosongkan atau isi dengan # jika belum ada</span>
                        </div>
                        <div class="form-group">
                            <label>Syarat & Ketentuan</label>
                            <input type="text" id="linkSyaratKetentuan" name="link_syarat_ketentuan"
                                   placeholder="contoh.com/terms"
                                   value="{{ $kontak['link_syarat_ketentuan'] ?? '#' }}">
                            <span class="url-helper"><i class="fi fi-rr-info"></i> Kosongkan atau isi dengan # jika belum ada</span>
                        </div>
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
                    <i class="fi fi-rr-edit"></i> Edit Kontak
                </button>
            </div>

            <h1>Kontak Perusahaan</h1>

            <!-- Informasi Perusahaan Section -->
            <div class="card">
                <div class="section-title">Informasi Perusahaan</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="company-info-grid">
                        <!-- Kiri: Nama Perusahaan dan Deskripsi -->
                        <div class="company-left">
                            <div class="info-field">
                                <div class="field-label">Nama Perusahaan</div>
                                <div class="field-value" id="resultNamaPerusahaan">{{ $kontak['nama_perusahaan'] ?? 'Citra Solusi Teknologi' }}</div>
                            </div>

                            <div class="info-field">
                                <div class="field-label">Deskripsi Singkat</div>
                                <div class="field-value" id="resultDeskripsiSingkat">{{ $kontak['deskripsi_singkat'] ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas Anda' }}</div>
                            </div>
                        </div>

                        <!-- Kanan: Kontak -->
                        <div class="company-right">
                            <div class="contact-field">
                                <div class="field-label">Kontak</div>
                                <div class="contact-details">
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-marker"></i></div>
                                        <div class="contact-text" id="resultAlamatKantorPusat">
                                            {{ $kontak['alamat_kantor_pusat'] ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}
                                        </div>
                                    </div>
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-envelope"></i></div>
                                        <div class="contact-text" id="resultEmailUtama">Email Utama: {{ $kontak['email_utama'] ?? 'rndcitrasolusi@gmail.com' }}</div>
                                    </div>
                                    @if(!empty($kontak['email_dukungan']) && $kontak['email_dukungan'] !== '#')
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-envelope"></i></div>
                                        <div class="contact-text" id="resultEmailDukungan">Email Dukungan: {{ $kontak['email_dukungan'] ?? 'support@smartshuttle.com' }}</div>
                                    </div>
                                    @endif
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-phone-call"></i></div>
                                        <div class="contact-text" id="resultTeleponUtama">Telepon Utama: {{ $kontak['telepon_utama'] ?? '0858-1122-4321' }}</div>
                                    </div>
                                    @if(!empty($kontak['telepon_dukungan']) && $kontak['telepon_dukungan'] !== '#')
                                    <div class="contact-row">
                                        <div class="contact-icon"><i class="fi fi-rr-phone-call"></i></div>
                                        <div class="contact-text" id="resultTeleponDukungan">Telepon Dukungan: {{ $kontak['telepon_dukungan'] ?? '0858-1122-4321' }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Sosial Section -->
            @if(!empty($kontak['facebook_url']) || !empty($kontak['instagram_url']) || !empty($kontak['twitter_url']))
            <div class="card social-section">
                <div class="section-title">Media Sosial</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="social-grid-result">
                        @if(!empty($kontak['facebook_url']) && $kontak['facebook_url'] !== '#')
                        <div class="social-card">
                            <div class="social-icon"><i class="fi fi-brands-facebook"></i></div>
                            <div class="social-name">Facebook</div>
                            <div class="social-link" id="resultFacebookUrl">
                                <a href="{{ $kontak['facebook_url'] }}" target="_blank" style="color: #6b7280; text-decoration: none;">
                                    {{ $kontak['facebook_url'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(!empty($kontak['instagram_url']) && $kontak['instagram_url'] !== '#')
                        <div class="social-card">
                            <div class="social-icon"><i class="fi fi-brands-instagram"></i></div>
                            <div class="social-name">Instagram</div>
                            <div class="social-link" id="resultInstagramUrl">
                                <a href="{{ $kontak['instagram_url'] }}" target="_blank" style="color: #6b7280; text-decoration: none;">
                                    {{ $kontak['instagram_url'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(!empty($kontak['twitter_url']) && $kontak['twitter_url'] !== '#')
                        <div class="social-card">
                            <div class="social-icon"><i class="fi fi-brands-twitter"></i></div>
                            <div class="social-name">Twitter</div>
                            <div class="social-link" id="resultTwitterUrl">
                                <a href="{{ $kontak['twitter_url'] }}" target="_blank" style="color: #6b7280; text-decoration: none;">
                                    {{ $kontak['twitter_url'] }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Jam Operasional Section -->
            <div class="card">
                <div class="section-title">Jam Operasional</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="jam-operasional-result">
                        @php
                            $jamDisplay = [];

                            if (isset($kontak['jam_operasional'])) {
                                if (is_string($kontak['jam_operasional'])) {
                                    $jamDisplay = json_decode($kontak['jam_operasional'], true);
                                } elseif (is_array($kontak['jam_operasional'])) {
                                    $jamDisplay = $kontak['jam_operasional'];
                                }
                            }

                            if (empty($jamDisplay)) {
                                $jamDisplay = [
                                    ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                                    ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                                    ['hari' => 'Minggu', 'jam' => 'Tutup']
                                ];
                            }
                        @endphp

                        @foreach($jamDisplay as $jam)
                        <div class="jam-item-result">
                            <span class="jam-day">{{ $jam['hari'] ?? 'Hari' }}</span>
                            <span class="jam-time">{{ $jam['jam'] ?? 'Jam' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kebijakan Section -->
            <div class="card">
                <div class="section-title">Kebijakan</div>

                <div class="section-content" style="max-height: 1000px; opacity: 1;">
                    <div class="policy-links">
                        <div class="policy-link-item">
                            <i class="fi fi-rr-document"></i>
                            <div class="policy-link-text">Kebijakan Privasi</div>
                            @if(!empty($kontak['link_kebijakan_privasi']) && $kontak['link_kebijakan_privasi'] !== '#')
                            <a href="{{ $kontak['link_kebijakan_privasi'] }}" class="policy-link-url" target="_blank" id="resultLinkKebijakanPrivasi">
                                {{ $kontak['link_kebijakan_privasi'] }}
                            </a>
                            @else
                            <span class="policy-link-url" style="color: #6b7280;">Belum tersedia</span>
                            @endif
                        </div>

                        <div class="policy-link-item">
                            <i class="fi fi-rr-document"></i>
                            <div class="policy-link-text">Syarat & Ketentuan</div>
                            @if(!empty($kontak['link_syarat_ketentuan']) && $kontak['link_syarat_ketentuan'] !== '#')
                            <a href="{{ $kontak['link_syarat_ketentuan'] }}" class="policy-link-url" target="_blank" id="resultLinkSyaratKetentuan">
                                {{ $kontak['link_syarat_ketentuan'] }}
                            </a>
                            @else
                            <span class="policy-link-url" style="color: #6b7280;">Belum tersedia</span>
                            @endif
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

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        let icon = 'fi fi-rr-info';
        if (type === 'success') icon = 'fi fi-rr-check';
        else if (type === 'error') icon = 'fi fi-rr-exclamation';
        else if (type === 'warning') icon = 'fi fi-rr-exclamation-triangle';

        notification.innerHTML = `
            <div class="notification-icon"><i class="${icon}"></i></div>
            <div class="notification-message">${message}</div>
            <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(notification);

        setTimeout(() => notification.classList.add('show'), 10);

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

            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId + 'Tab') {
                    content.classList.add('active');
                }
            });

            if (tabId === 'result') {
                updateResultView();
            }
        });
    });

    // ====== URL VALIDATION ======
    function cleanUrl(url) {
        if (!url || url.trim() === '') return '';

        url = url.trim();

        // Jika hanya #, return #
        if (url === '#') return '#';

        // Jika sudah ada protokol, return as is
        if (url.startsWith('http://') || url.startsWith('https://')) {
            return url;
        }

        // Jika tidak ada protokol, tambahkan https://
        return 'https://' + url;
    }

    // ====== VALIDATE FORM ======
    function validateForm() {
        const requiredFields = [
            { id: 'namaPerusahaan', name: 'Nama Perusahaan' },
            { id: 'deskripsiSingkat', name: 'Deskripsi Singkat' },
            { id: 'alamatKantorPusat', name: 'Alamat Kantor Pusat' },
            { id: 'emailUtama', name: 'Email Utama' },
            { id: 'teleponUtama', name: 'Telepon Utama' }
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
        const emailUtama = document.getElementById('emailUtama').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailUtama)) {
            showNotification('Format email utama tidak valid', 'error');
            document.getElementById('emailUtama').focus();
            return false;
        }

        const emailDukungan = document.getElementById('emailDukungan').value;
        if (emailDukungan && !emailRegex.test(emailDukungan)) {
            showNotification('Format email dukungan tidak valid', 'error');
            document.getElementById('emailDukungan').focus();
            return false;
        }

        return true;
    }

    // ====== COLLECT FORM DATA ======
    function collectFormData() {
        // Kumpulkan jam operasional
        const jamItems = document.querySelectorAll('.jam-item');
        const jamOperasional = [];

        jamItems.forEach(item => {
            const hariInput = item.querySelector('.jam-hari-input');
            const waktuInput = item.querySelector('.jam-waktu');

            if (hariInput && waktuInput) {
                const hari = hariInput.value;
                const jam = waktuInput.value;
                if (hari && jam) {
                    jamOperasional.push({ hari, jam });
                }
            }
        });

        // Clean URLs
        const cleanUrlFields = (url) => {
            if (!url) return '';
            url = url.trim();
            if (url === '' || url === '#') return '#';
            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                return 'https://' + url;
            }
            return url;
        };

        return {
            nama_perusahaan: document.getElementById('namaPerusahaan').value,
            deskripsi_singkat: document.getElementById('deskripsiSingkat').value,
            alamat_kantor_pusat: document.getElementById('alamatKantorPusat').value,
            email_utama: document.getElementById('emailUtama').value,
            email_dukungan: document.getElementById('emailDukungan').value || '',
            telepon_utama: document.getElementById('teleponUtama').value,
            telepon_dukungan: document.getElementById('teleponDukungan').value || '',
            facebook_url: cleanUrlFields(document.getElementById('facebookUrl').value),
            instagram_url: cleanUrlFields(document.getElementById('instagramUrl').value),
            twitter_url: cleanUrlFields(document.getElementById('twitterUrl').value),
            link_kebijakan_privasi: cleanUrlFields(document.getElementById('linkKebijakanPrivasi').value),
            link_syarat_ketentuan: cleanUrlFields(document.getElementById('linkSyaratKetentuan').value),
            jam_operasional: JSON.stringify(jamOperasional)
        };
    }

    // ====== SAVE DATA ======
    document.getElementById('saveBtn').addEventListener('click', async function() {
    const saveBtn = this;
    const originalText = saveBtn.innerHTML;

    try {
        console.log('Save button clicked');

        // Validasi form
        if (!validateForm()) {
            return;
        }

        // Show loading state
        saveBtn.classList.add('loading');
        saveBtn.innerHTML = '<i class="fi fi-rr-spinner"></i> Menyimpan...';
        saveBtn.disabled = true;

        // Kumpulkan data jam operasional
        const jamItems = document.querySelectorAll('.jam-item');
        const jamOperasional = [];

        jamItems.forEach(item => {
            const hari = item.querySelector('.jam-label').textContent;
            const waktu = item.querySelector('.jam-waktu').value;
            jamOperasional.push({ hari, jam: waktu });
        });

        // Buat FormData
        const formData = new FormData();

        // Tambahkan CSRF token dan method spoofing
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        const fields = {
            nama_perusahaan: document.getElementById('namaPerusahaan').value,
            deskripsi_singkat: document.getElementById('deskripsiSingkat').value,
            alamat_kantor_pusat: document.getElementById('alamatKantorPusat').value,
            email_utama: document.getElementById('emailUtama').value,
            email_dukungan: document.getElementById('emailDukungan').value,
            telepon_utama: document.getElementById('teleponUtama').value,
            telepon_dukungan: document.getElementById('teleponDukungan').value,
            facebook_url: document.getElementById('facebookUrl').value,
            instagram_url: document.getElementById('instagramUrl').value,
            twitter_url: document.getElementById('twitterUrl').value,
            link_kebijakan_privasi: document.getElementById('linkKebijakanPrivasi').value,
            link_syarat_ketentuan: document.getElementById('linkSyaratKetentuan').value,
            jam_operasional: JSON.stringify(jamOperasional)
        };

        // Add to FormData
        Object.keys(fields).forEach(key => {
            formData.append(key, fields[key]);
        });

        console.log('Saving data:', fields);

        // ✅ GUNAKAN POST METHOD
        const response = await fetch("{{ route('admin.kontakperusahaan.update', ['id' => $kontak['id'] ?? 1]) }}", {
            method: 'POST', // ✅ INI HARUS POST
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        console.log('Response:', data);

        if (data.success) {
            showNotification('✅ ' + data.message, 'success', 3000);

            // Update tampilan hasil
            updateResultViewFromForm();

            // Pindah ke tab result setelah 1 detik
            setTimeout(() => {
                document.querySelector('.tab-btn[data-tab="result"]').click();
            }, 1000);

            // Refresh page setelah 3 detik
            setTimeout(() => {
                location.reload();
            }, 3000);

        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error', 5000);
    } finally {
        // Reset button state
        saveBtn.classList.remove('loading');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }
});

    // ====== UPDATE RESULT VIEW FROM FORM ======
    function updateResultViewFromForm() {
        const data = collectFormData();
        updateResultView(data);
    }

    // ====== PREVIEW BUTTON ======
    document.getElementById('previewBtn').addEventListener('click', function() {
        if (validateForm()) {
            updateResultViewFromForm();
            sessionStorage.setItem('preview_kontak_data', JSON.stringify(collectFormData()));
            document.querySelector('.tab-btn[data-tab="result"]').click();
        }
    });

    // ====== EDIT PROFILE BUTTON ======
    document.getElementById('editProfileBtn').addEventListener('click', function() {
        document.querySelector('.tab-btn[data-tab="input"]').click();
    });

    // ====== CANCEL BUTTON ======
    document.getElementById('cancelBtn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
            location.reload();
        }
    });

    // ====== UPDATE RESULT VIEW ======
    function updateResultView(data = null) {
        if (!data) {
            const previewData = sessionStorage.getItem('preview_kontak_data');
            const savedData = localStorage.getItem('kontak_perusahaan_data');

            if (previewData) {
                data = JSON.parse(previewData);
            } else if (savedData) {
                data = JSON.parse(savedData);
            } else {
                data = collectFormData();
            }
        }

        console.log('Updating result view with data:', data);

        // Update informasi perusahaan
        const resultNamaPerusahaan = document.getElementById('resultNamaPerusahaan');
        const resultDeskripsiSingkat = document.getElementById('resultDeskripsiSingkat');
        const resultAlamatKantorPusat = document.getElementById('resultAlamatKantorPusat');
        const resultEmailUtama = document.getElementById('resultEmailUtama');
        const resultEmailDukungan = document.getElementById('resultEmailDukungan');
        const resultTeleponUtama = document.getElementById('resultTeleponUtama');
        const resultTeleponDukungan = document.getElementById('resultTeleponDukungan');

        if (resultNamaPerusahaan) resultNamaPerusahaan.textContent = data.nama_perusahaan || '';
        if (resultDeskripsiSingkat) resultDeskripsiSingkat.textContent = data.deskripsi_singkat || '';
        if (resultAlamatKantorPusat) resultAlamatKantorPusat.textContent = data.alamat_kantor_pusat || '';
        if (resultEmailUtama) resultEmailUtama.textContent = 'Email Utama: ' + (data.email_utama || '');
        if (resultEmailDukungan) resultEmailDukungan.textContent = 'Email Dukungan: ' + (data.email_dukungan || '');
        if (resultTeleponUtama) resultTeleponUtama.textContent = 'Telepon Utama: ' + (data.telepon_utama || '');
        if (resultTeleponDukungan) resultTeleponDukungan.textContent = 'Telepon Dukungan: ' + (data.telepon_dukungan || '');

        // Update media sosial
        const resultFacebookUrl = document.getElementById('resultFacebookUrl');
        const resultInstagramUrl = document.getElementById('resultInstagramUrl');
        const resultTwitterUrl = document.getElementById('resultTwitterUrl');

        if (resultFacebookUrl) {
            const link = resultFacebookUrl.querySelector('a');
            if (link && data.facebook_url && data.facebook_url !== '#') {
                link.href = data.facebook_url;
                link.textContent = data.facebook_url;
                resultFacebookUrl.style.display = 'block';
            } else {
                resultFacebookUrl.style.display = 'none';
            }
        }

        if (resultInstagramUrl) {
            const link = resultInstagramUrl.querySelector('a');
            if (link && data.instagram_url && data.instagram_url !== '#') {
                link.href = data.instagram_url;
                link.textContent = data.instagram_url;
                resultInstagramUrl.style.display = 'block';
            } else {
                resultInstagramUrl.style.display = 'none';
            }
        }

        if (resultTwitterUrl) {
            const link = resultTwitterUrl.querySelector('a');
            if (link && data.twitter_url && data.twitter_url !== '#') {
                link.href = data.twitter_url;
                link.textContent = data.twitter_url;
                resultTwitterUrl.style.display = 'block';
            } else {
                resultTwitterUrl.style.display = 'none';
            }
        }

        // Update jam operasional
        const jamOperasionalResult = document.querySelector('.jam-operasional-result');
        if (jamOperasionalResult) {
            let html = '';
            let jamData = [];

            if (data.jam_operasional) {
                if (typeof data.jam_operasional === 'string') {
                    jamData = JSON.parse(data.jam_operasional);
                } else if (Array.isArray(data.jam_operasional)) {
                    jamData = data.jam_operasional;
                }
            }

            if (jamData.length > 0) {
                jamData.forEach(item => {
                    html += `
                        <div class="jam-item-result">
                            <span class="jam-day">${item.hari}</span>
                            <span class="jam-time">${item.jam}</span>
                        </div>
                    `;
                });
            } else {
                // Default data
                html = `
                    <div class="jam-item-result">
                        <span class="jam-day">Senin - Jumat</span>
                        <span class="jam-time">08:00 - 17:00</span>
                    </div>
                    <div class="jam-item-result">
                        <span class="jam-day">Sabtu</span>
                        <span class="jam-time">08:00 - 15:00</span>
                    </div>
                    <div class="jam-item-result">
                        <span class="jam-day">Minggu</span>
                        <span class="jam-time">Tutup</span>
                    </div>
                `;
            }
            jamOperasionalResult.innerHTML = html;
        }

        // Update kebijakan
        const resultLinkKebijakanPrivasi = document.getElementById('resultLinkKebijakanPrivasi');
        const resultLinkSyaratKetentuan = document.getElementById('resultLinkSyaratKetentuan');

        if (resultLinkKebijakanPrivasi) {
            if (data.link_kebijakan_privasi && data.link_kebijakan_privasi !== '#') {
                resultLinkKebijakanPrivasi.href = data.link_kebijakan_privasi;
                resultLinkKebijakanPrivasi.textContent = data.link_kebijakan_privasi;
                resultLinkKebijakanPrivasi.style.display = 'inline';
                resultLinkKebijakanPrivasi.previousElementSibling.style.display = 'none';
            } else {
                resultLinkKebijakanPrivasi.style.display = 'none';
                resultLinkKebijakanPrivasi.previousElementSibling.style.display = 'inline';
            }
        }

        if (resultLinkSyaratKetentuan) {
            if (data.link_syarat_ketentuan && data.link_syarat_ketentuan !== '#') {
                resultLinkSyaratKetentuan.href = data.link_syarat_ketentuan;
                resultLinkSyaratKetentuan.textContent = data.link_syarat_ketentuan;
                resultLinkSyaratKetentuan.style.display = 'inline';
                resultLinkSyaratKetentuan.previousElementSibling.style.display = 'none';
            } else {
                resultLinkSyaratKetentuan.style.display = 'none';
                resultLinkSyaratKetentuan.previousElementSibling.style.display = 'inline';
            }
        }

        // Save to localStorage
        localStorage.setItem('kontak_perusahaan_data', JSON.stringify(data));
    }

    // ====== INITIALIZATION ======
    function init() {
        // Setup collapse/expand jika ada
        const sectionTitles = document.querySelectorAll('.section-title');

        sectionTitles.forEach(title => {
            const content = title.nextElementSibling;
            const icon = title.querySelector('i');

            if (content && content.classList.contains('section-content')) {
                title.addEventListener('click', function() {
                    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                        content.style.maxHeight = '0px';
                        content.style.opacity = '0';
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.maxHeight = content.scrollHeight + 'px';
                        content.style.opacity = '1';
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });
    }

    // Initialize everything
    init();
});
</script>
@endpush
@endsection
