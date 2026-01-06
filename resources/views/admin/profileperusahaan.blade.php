@extends('layouts.app-admin')

@section('title', 'Profile Perusahaan - Smart Shuttle Admin')
@section('page-title', 'Profile Perusahaan')

@section('content')
<style>
.profile {
    padding: 24px 32px 40px;
    background: #f8f8f6;
}

/* TITLE */
.profile h2 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #0d3559;
    padding-bottom: 12px;
    border-bottom: 2px solid #ff6a21;
}

/* CARD */
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

/* FORM */
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

/* LAYANAN */
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

/* RESPONSIVE */
@media (max-width: 1024px) {
    .profile {
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
}

@media (max-width: 640px) {
    .profile {
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
}

/* FILE INPUT STYLING */
.file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

/* FORM SECTION */
.form-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

/* INFO TEXT */
.info-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
    font-style: italic;
}

/* REQUIRED FIELD */
.required::after {
    content: " *";
    color: #ef4444;
}

/* READONLY STYLE */
.readonly-field {
    background: #f9fafb !important;
    border-color: #e5e7eb !important;
    color: #6b7280 !important;
    cursor: not-allowed !important;
}

/* ACTION BUTTONS IN CARD */
.card-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 16px;
}

/* LOADING STATE */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* UPLOAD AREA TEXT */
.upload-text {
    margin-top: 4px;
    color: #9ca3af;
    font-size: 11px;
}

/* SERVICE FORM GROUPS */
.service .form-group {
    flex: 0 0 auto;
}

.service .form-group:last-of-type {
    margin-bottom: 0;
}

/* SCROLLABLE CONTAINER */
.scrollable-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 8px;
}

.scrollable-container::-webkit-scrollbar {
    width: 6px;
}

.scrollable-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.scrollable-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.scrollable-container::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
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
</style>

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
                <input type="text" value="PT. Smart Shuttle Indonesia">
                <div class="info-text">Nama resmi sesuai akta pendirian</div>
            </div>
            <div class="form-group">
                <label>Nama Dagang</label>
                <input type="text" value="Smart Shuttle Group">
            </div>
        </div>

        <div class="form-group">
            <label class="required">Deskripsi Singkat</label>
            <textarea rows="3">Smart Shuttle adalah solusi transportasi cerdas yang menghubungkan berbagai kota dan mempermudah mobilitas masyarakat dengan layanan yang cepat dan terpercaya.</textarea>
        </div>

        <div class="form-group">
            <label class="required">Alamat</label>
            <textarea rows="2">Jl. Sudirman No. 45, Jakarta Selatan</textarea>
        </div>

        <div class="form-row three">
            <div class="form-group">
                <label class="required">Telepon</label>
                <input type="tel" value="(021) 555-1234">
            </div>
            <div class="form-group">
                <label class="required">Email</label>
                <input type="email" value="info@smartshuttle.co.id">
            </div>
            <div class="form-group">
                <label>Website</label>
                <input type="url" value="www.smartshuttle.co.id">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Logo Perusahaan</label>
                <div class="upload">
                    <span>Upload Logo (PNG/JPG, max 2MB)</span>
                    <input type="file" accept="image/*" class="file-input">
                </div>
                <div class="upload-preview" id="logoPreview">
                    <img src="" alt="Logo Preview">
                    <div class="upload-actions">
                        <button class="btn btn-outline btn-sm">Ganti</button>
                        <button class="btn btn-outline btn-sm">Hapus</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Foto Kantor</label>
                <div class="upload">
                    <span>Upload Foto Kantor (PNG/JPG, max 2MB)</span>
                    <input type="file" accept="image/*" class="file-input">
                </div>
                <div class="upload-preview" id="officePreview">
                    <img src="" alt="Office Preview">
                    <div class="upload-actions">
                        <button class="btn btn-outline btn-sm">Ganti</button>
                        <button class="btn btn-outline btn-sm">Hapus</button>
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

        <div class="services">
            @php
                $services = [
                    ['name' => 'SmartShuttle', 'desc' => 'Layanan transportasi cerdas untuk perjalanan antar kota dengan armada modern dan pengemudi profesional.'],
                    ['name' => 'SmartSend', 'desc' => 'Layanan pengiriman barang cepat dan aman dengan sistem tracking real-time untuk seluruh Indonesia.'],
                    ['name' => 'SmartRent', 'desc' => 'Penyewaan kendaraan jangka panjang dengan fasilitas lengkap dan maintenance terjamin.']
                ];
            @endphp

            @foreach ($services as $index => $service)
            <div class="service">
                <div class="form-group">
                    <label>Logo Layanan</label>
                    <div class="upload">
                        <span>Upload Logo {{ $service['name'] }}</span>
                        <input type="file" accept="image/*" class="service-logo-input" data-service="{{ $index }}">
                    </div>
                    <div class="upload-preview service-preview-{{ $index }}">
                        <img src="" alt="Service Preview">
                        <div class="upload-actions">
                            <button type="button" class="btn btn-outline btn-sm change-service" data-service="{{ $index }}">Ganti</button>
                            <button type="button" class="btn btn-outline btn-sm remove-service" data-service="{{ $index }}">Hapus</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="required">Nama Layanan</label>
                    <input type="text" value="{{ $service['name'] }}" class="service-name" data-service="{{ $index }}">
                </div>

                <div class="form-group">
                    <label class="required">Deskripsi Layanan</label>
                    <textarea rows="3" class="service-desc" data-service="{{ $index }}">{{ $service['desc'] }}</textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="service-status" data-service="{{ $index }}">
                        <option value="active" selected>Aktif</option>
                        <option value="inactive">Non-Aktif</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="form-controls">
                    <button type="button" class="btn btn-outline btn-sm">Edit</button>
                    <button type="button" class="btn btn-outline btn-sm">Hapus</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="required">Visi Perusahaan</label>
                <textarea rows="4">Menjadi perusahaan terdepan di Indonesia dalam menyediakan solusi mobilitas dan logistik yang inovatif dan berkelanjutan demi kemudahan masyarakat.</textarea>
            </div>
            <div class="form-group">
                <label class="required">Misi Perusahaan</label>
                <textarea rows="4">• Menyediakan layanan transportasi dan logistik yang cepat, aman, dan ramah lingkungan.
• Mengoptimalkan penggunaan teknologi untuk meningkatkan efisiensi dan kepuasan pelanggan.
• Membangun jaringan luas untuk mendukung mobilitas masyarakat di seluruh Indonesia.
• Mengedepankan keselamatan dan kenyamanan dalam setiap layanan.</textarea>
            </div>
        </div>

        <div class="card-actions">
            <button type="button" class="btn btn-outline">Tambah Layanan</button>
            <button type="button" class="btn btn-outline">Refresh</button>
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
                <input type="text" value="01.234.567.8-901.000">
            </div>
            <div class="form-group">
                <label>Kode Izin Penyelenggaraan</label>
                <input type="text" value="KIP-56789-XYZ">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>SIUP</label>
                <input type="text" value="SIUP-2024-12345">
            </div>
            <div class="form-group">
                <label>NIB</label>
                <input type="text" value="1234567890123">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nomor Sertifikat Transportasi</label>
                <input type="text" value="TRNS-00012345">
            </div>
            <div class="form-group">
                <label>TDP</label>
                <input type="text" value="TDP-2024-98765">
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Upload Dokumen Legal</label>
                <div class="upload" style="min-height: 120px;">
                    <span>Drag & drop atau klik untuk upload dokumen</span>
                    <div class="upload-text">Format: PDF, JPG, PNG (Max 5MB per file)</div>
                    <input type="file" accept=".pdf,.jpg,.jpeg,.png" multiple>
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
                <input type="date" value="2025-11-10">
            </div>
            <div class="form-group">
                <label class="required">Penanggung Jawab Utama</label>
                <input type="text" value="Dr. Rina Dewi">
            </div>
            <div class="form-group">
                <label class="required">Nama Pendiri</label>
                <input type="text" value="Ir. Agus Santoso">
            </div>
        </div>

        <div class="form-group">
            <label>Struktur Organisasi</label>
            <div class="upload" style="min-height: 120px;">
                <span>Upload Struktur Organisasi (PDF/JPG/PNG)</span>
                <input type="file" accept=".pdf,.jpg,.jpeg,.png">
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
                <input type="url" value="https://smartshuttle.co.id/refund-policy">
            </div>
            <div class="form-group">
                <label>Link Privasi</label>
                <input type="url" value="https://smartshuttle.co.id/privacy-policy">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Link Syarat & Ketentuan</label>
                <input type="url" value="https://smartshuttle.co.id/terms">
            </div>
            <div class="form-group">
                <label>Link Bantuan</label>
                <input type="url" value="https://smartshuttle.co.id/bantuan">
            </div>
        </div>

        <div class="form-group">
            <label>Link FAQ</label>
            <input type="url" value="https://smartshuttle.co.id/faq">
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="card">
        <div class="form-actions">
            <button type="button" class="btn btn-secondary">Batal</button>
            <button type="button" class="btn btn-outline">Preview</button>
            <button type="button" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload functionality
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

        // Prevent drag default behavior
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#ff6a21';
            this.style.background = '#fff7ed';
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = '#cbd5e1';
            this.style.background = '#f9fafb';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#cbd5e1';
            this.style.background = '#f9fafb';

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    });

    // Preview image removal
    document.querySelectorAll('.remove-service').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const serviceId = this.getAttribute('data-service');
            const preview = document.querySelector(`.service-preview-${serviceId}`);

            if (preview) {
                const img = preview.querySelector('img');
                if (img) img.src = '';
                preview.style.display = 'none';

                // Reset file input
                const fileInput = preview.closest('.form-group').querySelector('input[type="file"]');
                if (fileInput) fileInput.value = '';
            }
        });
    });

    // Change service image
    document.querySelectorAll('.change-service').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const serviceId = this.getAttribute('data-service');
            const fileInput = document.querySelector(`.service-logo-input[data-service="${serviceId}"]`);

            if (fileInput) {
                fileInput.click();
            }
        });
    });

    // Form validation
    const form = document.querySelector('.profile');
    const saveButton = form.querySelector('.btn-primary');

    saveButton.addEventListener('click', function() {
        // Show loading state
        this.classList.add('loading');
        this.innerHTML = 'Menyimpan...';

        // Simulate API call
        setTimeout(() => {
            this.classList.remove('loading');
            this.innerHTML = 'Simpan Perubahan';

            // Show success message
            alert('Profile perusahaan berhasil diperbarui!');
        }, 1500);
    });

    // Add service button
    const addServiceBtn = document.querySelector('.card-actions .btn-outline:first-child');
    if (addServiceBtn && addServiceBtn.textContent.includes('Tambah Layanan')) {
        addServiceBtn.addEventListener('click', function() {
            const servicesContainer = document.querySelector('.services');
            const serviceCount = servicesContainer.children.length;
            const newIndex = serviceCount;

            const newService = document.createElement('div');
            newService.className = 'service';
            newService.innerHTML = `
                <div class="form-group">
                    <label>Logo Layanan</label>
                    <div class="upload">
                        <span>Upload Logo Layanan Baru</span>
                        <input type="file" accept="image/*" class="service-logo-input" data-service="${newIndex}">
                    </div>
                    <div class="upload-preview service-preview-${newIndex}">
                        <img src="" alt="Service Preview">
                        <div class="upload-actions">
                            <button type="button" class="btn btn-outline btn-sm change-service" data-service="${newIndex}">Ganti</button>
                            <button type="button" class="btn btn-outline btn-sm remove-service" data-service="${newIndex}">Hapus</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="required">Nama Layanan</label>
                    <input type="text" class="service-name" data-service="${newIndex}" placeholder="Nama Layanan Baru">
                </div>

                <div class="form-group">
                    <label class="required">Deskripsi Layanan</label>
                    <textarea rows="3" class="service-desc" data-service="${newIndex}" placeholder="Deskripsi layanan baru"></textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="service-status" data-service="${newIndex}">
                        <option value="active" selected>Aktif</option>
                        <option value="inactive">Non-Aktif</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="form-controls">
                    <button type="button" class="btn btn-outline btn-sm">Edit</button>
                    <button type="button" class="btn btn-outline btn-sm">Hapus</button>
                </div>
            `;

            servicesContainer.appendChild(newService);

            // Re-attach event listeners to new service
            const removeBtn = newService.querySelector('.remove-service');
            const changeBtn = newService.querySelector('.change-service');
            const uploadArea = newService.querySelector('.upload');
            const fileInput = newService.querySelector('input[type="file"]');

            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const serviceId = this.getAttribute('data-service');
                    const preview = document.querySelector(`.service-preview-${serviceId}`);

                    if (preview) {
                        const img = preview.querySelector('img');
                        if (img) img.src = '';
                        preview.style.display = 'none';

                        if (fileInput) fileInput.value = '';
                    }
                });
            }

            if (changeBtn) {
                changeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const serviceId = this.getAttribute('data-service');
                    const fileInput = document.querySelector(`.service-logo-input[data-service="${serviceId}"]`);

                    if (fileInput) {
                        fileInput.click();
                    }
                });
            }

            if (uploadArea && fileInput) {
                const parent = uploadArea.closest('.form-group');
                const preview = parent.querySelector('.upload-preview');

                uploadArea.addEventListener('click', function(e) {
                    if (e.target !== fileInput) {
                        fileInput.click();
                    }
                });

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
            }

            alert('Layanan baru berhasil ditambahkan. Silakan isi data layanan.');
        });
    }
});
</script>
@endsection
