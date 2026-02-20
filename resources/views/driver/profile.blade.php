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
        text-decoration: none;
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
    }

    .existing-file a {
        color: #1976d2;
        text-decoration: none;
        margin-left: 8px;
    }

    .existing-file a:hover {
        text-decoration: underline;
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

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
</style>
@endpush

@section('content')

<h2>Profile Driver</h2>
<hr>

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
                <a href="{{ route('driver.profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
            <div class="profile-status">{{ ucfirst($driver->status) }}</div>
            <div class="profile-id" style="font-size: 13px; margin-top: 8px;">
                Bergabung: {{ $driver->created_at->format('d F Y') }}
            </div>
        </div>
    </div>

    <div class="form-grid">
        <!-- KOLOM KIRI -->
        <div class="form-column">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="{{ $driver->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="{{ $driver->email }}" readonly>
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" value="{{ $driver->phone ?? 'Belum diisi' }}" readonly>
                @if (!$driver->phone)
                    <div class="missing-data">⚠️ Data belum dilengkapi</div>
                @endif
            </div>

            <div class="form-group">
                <label>NIK (16 digit)</label>
                <input type="text" value="{{ $driver->nik ?? 'Belum diisi' }}" readonly>
                @if (!$driver->nik)
                    <div class="missing-data">⚠️ Data belum dilengkapi</div>
                @endif
            </div>

            <div class="form-group">
                <label>Upload KTP<br><small>.JPG/PNG Max 5MB</small></label>
                @if ($driver->ktp_file && Storage::disk('public')->exists($driver->ktp_file))
                    <div class="existing-file">
                        ✓ File sudah diupload
                        <a href="{{ Storage::url($driver->ktp_file) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="missing-data">⚠️ Belum ada file KTP</div>
                @endif
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
                <label>Nomor SIM</label>
                <input type="text" value="{{ $driver->nomor_sim ?? 'Belum diisi' }}" readonly>
                @if (!$driver->nomor_sim)
                    <div class="missing-data">⚠️ Data belum dilengkapi</div>
                @endif
            </div>

            <div class="form-group">
                <label>Masa Berlaku SIM</label>
                <input type="text" value="{{ $driver->masa_berlaku_sim ? $driver->masa_berlaku_sim->format('d F Y') : 'Belum diisi' }}" readonly>
                @if (!$driver->masa_berlaku_sim)
                    <div class="missing-data">⚠️ Data belum dilengkapi</div>
                @endif
            </div>

            <div class="form-group">
                <label>Upload SIM<br><small>.JPG/PNG Max 5MB</small></label>
                @if ($driver->sim_file && Storage::disk('public')->exists($driver->sim_file))
                    <div class="existing-file">
                        ✓ File sudah diupload
                        <a href="{{ Storage::url($driver->sim_file) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="missing-data">⚠️ Belum ada file SIM</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Notifikasi jika ada data yang belum dilengkapi
    document.addEventListener('DOMContentLoaded', function() {
        const missingDataElements = document.querySelectorAll('.missing-data');
        if (missingDataElements.length > 0) {
            console.log('Data yang belum dilengkapi:', missingDataElements.length, 'field(s)');
        }
    });
</script>
@endpush

