@extends('layouts.app-driver')

@section('title', 'Profile Driver - Smart Shuttle')

@push('styles')
<style>
    /* ======== VARIABLES ======== */
    :root {
        --primary-color: #0d3559;
        --secondary-color: #ff6a00;
        --accent-color: #2E86AB;
        --background-color: #f5f7fa;
        --text-dark: #333333;
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --gray-bg: #f5f7fa;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --transition: all 0.3s ease;
    }

    .content-wrapper {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION (SESUAI DENGAN HALAMAN LAIN) ===== */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .title i {
        color: var(--primary-orange);
        font-size: 1.8rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    /* ======== PROFILE CARD ======== */
    .profile-card {
        background: #0d3559;
        color: white;
        padding: 40px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-border);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 30px;
        margin-bottom: 30px;
        position: relative;
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
        transition: var(--transition);
    }

    .profile-photo img:hover {
        transform: scale(1.05);
        border-color: var(--primary-orange);
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .edit-profile-btn:hover {
        background: #e55e00;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .edit-profile-btn i {
        font-size: 12px;
    }

    .profile-status {
        display: inline-block;
        background: #2ecc71;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 14px;
        margin-top: 5px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
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
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-group:nth-child(even) {
        animation-delay: 0.1s;
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

    .missing-data {
        background: #fff3cd;
        color: #856404;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 4px;
        border-left: 3px solid #ff6a00;
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
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

    .existing-file {
        margin-top: 8px;
        padding: 8px 12px;
        background: #e8f5e9;
        border-radius: 4px;
        font-size: 12px;
        color: #2e7d32;
        border-left: 3px solid #2ecc71;
    }

    .existing-file a {
        color: #1976d2;
        text-decoration: none;
        margin-left: 8px;
    }

    .existing-file a:hover {
        text-decoration: underline;
    }

    /* Alert Styles */
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
        animation: slideDown 0.3s ease;
        border-left: 4px solid transparent;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-left-color: #28a745;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
        border-left-color: #ffc107;
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
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 1.5rem;
        }

        .title i {
            font-size: 1.5rem;
        }

        .divider {
            width: 80px;
            margin-bottom: 1rem;
        }

        .profile-card {
            padding: 25px;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }

        .profile-id-section {
            justify-content: center;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-group {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

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

        .form-group label {
            font-size: 13px;
        }

        .form-group input {
            padding: 10px;
            font-size: 14px;
        }
    }

    @media (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }

        .profile-id-section {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- HEADER SECTION - SESUAI DENGAN HALAMAN LAIN -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-user-circle"></i>
            Profile Driver
        </h1>
    </div>

    <div class="divider"></div>

    @if ($errors->any())
        <div class="alert alert-warning">
            <strong>Kesalahan:</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-card">
        <!-- PROFILE HEADER DENGAN FOTO DI SAMPING -->
        <div class="profile-header">
            <div class="profile-photo">
                @if ($driver->photo_file && Storage::disk('public')->exists($driver->photo_file))
                    <img src="{{ Storage::url($driver->photo_file) }}" alt="Foto Profil">
                @else
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Foto Profil Default">
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ $driver->name }}</h2>
                <div class="profile-id-section">
                    <div class="profile-id">ID Pengemudi: {{ $driver->id_pengemudi ?? 'Belum ada' }}</div>
                    <a href="{{ route('driver.profile.edit') }}" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                <div class="profile-status">
                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i>
                    {{ ucfirst($driver->status) }}
                </div>
                <div class="profile-id" style="font-size: 13px; margin-top: 8px;">
                    <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                    Bergabung: {{ $driver->created_at->format('d F Y') }}
                </div>
            </div>
        </div>

        <div class="form-grid">
            <!-- KOLOM KIRI -->
            <div class="form-column">
                <div class="form-group">
                    <label><i class="fas fa-user" style="margin-right: 5px;"></i> Nama Lengkap</label>
                    <input type="text" value="{{ $driver->name }}" readonly>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope" style="margin-right: 5px;"></i> Email</label>
                    <input type="text" value="{{ $driver->email }}" readonly>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-phone" style="margin-right: 5px;"></i> Nomor Telepon</label>
                    <input type="text" value="{{ $driver->phone ?? 'Belum diisi' }}" readonly>
                    @if (!$driver->phone)
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Data belum dilengkapi
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label><i class="fas fa-id-card" style="margin-right: 5px;"></i> NIK (16 digit)</label>
                    <input type="text" value="{{ $driver->nik ?? 'Belum diisi' }}" readonly>
                    @if (!$driver->nik)
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Data belum dilengkapi
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label><i class="fas fa-file-image" style="margin-right: 5px;"></i> Upload KTP<br><small>.JPG/PNG Max 5MB</small></label>
                    @if ($driver->ktp_file && Storage::disk('public')->exists($driver->ktp_file))
                        <div class="existing-file">
                            <i class="fas fa-check-circle" style="margin-right: 5px;"></i>
                            File sudah diupload
                            <a href="{{ Storage::url($driver->ktp_file) }}" target="_blank">
                                <i class="fas fa-eye"></i> Lihat File
                            </a>
                        </div>
                    @else
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Belum ada file KTP
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="form-column">
                <div class="form-group">
                    <label><i class="fas fa-calendar-plus" style="margin-right: 5px;"></i> Tanggal Bergabung</label>
                    <input type="text" value="{{ $driver->created_at->format('d F Y') }}" readonly>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-barcode" style="margin-right: 5px;"></i> ID Pengemudi</label>
                    <input type="text" value="{{ $driver->id_pengemudi ?? 'Akan dibuat otomatis' }}" readonly>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-car" style="margin-right: 5px;"></i> Nomor SIM</label>
                    <input type="text" value="{{ $driver->nomor_sim ?? 'Belum diisi' }}" readonly>
                    @if (!$driver->nomor_sim)
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Data belum dilengkapi
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label><i class="fas fa-calendar-times" style="margin-right: 5px;"></i> Masa Berlaku SIM</label>
                    <input type="text" value="{{ $driver->masa_berlaku_sim ? $driver->masa_berlaku_sim->format('d F Y') : 'Belum diisi' }}" readonly>
                    @if (!$driver->masa_berlaku_sim)
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Data belum dilengkapi
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label><i class="fas fa-file-image" style="margin-right: 5px;"></i> Upload SIM<br><small>.JPG/PNG Max 5MB</small></label>
                    @if ($driver->sim_file && Storage::disk('public')->exists($driver->sim_file))
                        <div class="existing-file">
                            <i class="fas fa-check-circle" style="margin-right: 5px;"></i>
                            File sudah diupload
                            <a href="{{ Storage::url($driver->sim_file) }}" target="_blank">
                                <i class="fas fa-eye"></i> Lihat File
                            </a>
                        </div>
                    @else
                        <div class="missing-data">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            Belum ada file SIM
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Profile Driver page loaded');

        // Set active menu untuk halaman profile
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
            if (link.id === 'profile-link') {
                link.classList.add('menu-active');
            }
        });

        // Notifikasi jika ada data yang belum dilengkapi
        const missingDataElements = document.querySelectorAll('.missing-data');
        if (missingDataElements.length > 0) {
            console.log('Data yang belum dilengkapi:', missingDataElements.length, 'field(s)');
            
            // Tambahkan efek highlight untuk missing data
            missingDataElements.forEach((element, index) => {
                element.style.animation = `shake 0.5s ease ${index * 0.1}s`;
            });
        }

        // Animasi untuk form groups
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach((group, index) => {
            group.style.animationDelay = `${index * 0.05}s`;
        });
    });
</script>
@endpush