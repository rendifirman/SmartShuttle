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
        display: inline-block;
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

    .form-group input[readonly] {
        background: #e9ecef;
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

    input[readonly] {
        background: #e9ecef;
        cursor: not-allowed;
    }

    /* Tambahan untuk preview dokumen */
    .document-preview {
        margin-top: 10px;
        width: 100%;
        max-width: 200px;
        border-radius: 4px;
        overflow: hidden;
    }

    .document-preview img {
        width: 100%;
        height: auto;
        border-radius: 4px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .no-document {
        background: #e9ecef;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        font-size: 14px;
        color: #6c757d;
        width: 100%;
        box-sizing: border-box;
        margin-top: 8px;
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

@if(session('success'))
<div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

<h2>Profile Driver</h2>
<hr>

<div class="profile-card">
    <!-- PROFILE HEADER DENGAN FOTO DI SAMPING -->
    <div class="profile-header">
        <div class="profile-photo">
            <img src="{{ $driver->avatar ? Storage::url($driver->avatar) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                 alt="Profile Photo">
        </div>
        <div class="profile-info">
            <h2>{{ $driver->name ?? 'Dimas Mahendra' }}</h2>
            <div class="profile-id-section">
                <div class="profile-id">ID Pengemudi: {{ $driver->driver_id ?? 'DRV-2023-001' }}</div>
                <a href="{{ route('driver.profile-edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
            <div class="profile-status">{{ $driver->status == 'active' ? 'Aktif' : 'Non-Aktif' }}</div>
        </div>
    </div>

    <div class="form-grid">
        <!-- KOLOM KIRI -->
        <div class="form-column">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="{{ $driver->name ?? 'Dimas Mahendra' }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="{{ $driver->email ?? 'dimas.pratama.driver@gmail.com' }}" readonly>
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" value="{{ $driver->phone ?? '0812-7788-3344' }}" readonly>
            </div>

            <div class="form-group">
                <label>NIK (16 digit)</label>
                <input type="text" value="{{ $driver->nik ?? '3201152206970004' }}" readonly>
            </div>

            <div class="form-group">
                <label>Upload KTP<br><small>.JPG/PNG Max 5MB</small></label>
                @if($driver->ktp_file)
                    <div class="document-preview">
                        <img src="{{ Storage::url($driver->ktp_file) }}" alt="KTP">
                    </div>
                @else
                    <div class="no-document">Belum upload KTP</div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="form-column">
            <div class="form-group">
                <label>Tanggal Bergabung</label>
                <input type="text" value="{{ $driver->join_date ? \Carbon\Carbon::parse($driver->join_date)->format('d F Y') : '12 Januari 2023' }}" readonly>
            </div>

            <div class="form-group">
                <label>ID Pengemudi</label>
                <input type="text" value="{{ $driver->driver_id ?? 'DRV-2023-001' }}" readonly>
            </div>

            <div class="form-group">
                <label>Nomor SIM</label>
                <input type="text" value="{{ $driver->sim_number ?? 'A9876543210' }}" readonly>
            </div>

            <div class="form-group">
                <label>Masa Berlaku SIM</label>
                <input type="text" value="{{ $driver->sim_expiry_date ? \Carbon\Carbon::parse($driver->sim_expiry_date)->format('d F Y') : '12 Januari 2027' }}" readonly>
            </div>

            <div class="form-group">
                <label>Upload SIM<br><small>.JPG/PNG Max 5MB</small></label>
                @if($driver->sim_file)
                    <div class="document-preview">
                        <img src="{{ Storage::url($driver->sim_file) }}" alt="SIM">
                    </div>
                @else
                    <div class="no-document">Belum upload SIM</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection