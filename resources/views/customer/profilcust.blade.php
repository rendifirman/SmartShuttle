@extends('layouts.app-profile')

@section('title', 'Profil Saya - SmartShuttle')

<!-- TAMBAHKAN INI -->
@section('header-title', 'Profil Saya')
@section('header-icon', '<i class="fas fa-user-circle"></i>')

@push('styles')
<style>
    /* STYLES KHUSUS HALAMAN PROFIL SAYA */
    .profile-box {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-box h3 {
        font-size: 32px;
        margin-bottom: 8px;
        color: #00274D;
        font-weight: 700;
    }

    .profile-box p {
        color: #666;
        font-size: 18px;
    }

    .form-card {
        background: #00274D;
        padding: 30px;
        border-radius: 10px;
        color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        margin-bottom: 30px;
        display: none; /* Sembunyikan form edit awal */
    }

    .form-card.show {
        display: block; /* Tampilkan form edit */
    }

    .form-card label {
        display: block;
        font-size: 16px;
        margin-bottom: 8px;
        margin-top: 20px;
        font-weight: 500;
    }

    .form-card input {
        width: 100%;
        padding: 14px;
        border-radius: 6px;
        border: none;
        outline: none;
        font-size: 15px;
        background: rgba(255, 255, 255, 0.95);
        color: #333;
    }

    .form-card input:focus {
        box-shadow: 0 0 0 2px #FF6B2C;
        background: white;
    }

    .form-card input::placeholder {
        color: #999;
    }

    .form-card input[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    button[type="submit"] {
        width: 200px;
        margin-top: 30px;
        padding: 14px;
        background: #FF6B2C;
        color: white;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: block;
        margin-left: auto;
        margin-right: auto;
        transition: background 0.3s, transform 0.2s;
    }

    button[type="submit"]:hover {
        background: #e55a20;
        transform: translateY(-2px);
    }

    button[type="submit"] i {
        margin-right: 8px;
    }

    /* Tombol Edit Profil */
    .edit-profile-btn {
        width: 200px;
        padding: 14px;
        background: #FF6B2C;
        color: white;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: block;
        margin: 30px auto;
        transition: background 0.3s, transform 0.2s;
    }

    .edit-profile-btn:hover {
        background: #e55a20;
        transform: translateY(-2px);
    }

    .edit-profile-btn i {
        margin-right: 8px;
    }

    /* Additional info section */
    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .info-card h4 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #00274D;
        font-weight: 600;
        border-bottom: 2px solid #FF6B2C;
        padding-bottom: 10px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #00274D;
        min-width: 150px;
    }

    .info-value {
        color: #666;
        flex: 1;
        text-align: right;
    }

    .data-empty {
        color: #ff6b6b;
        font-style: italic;
        font-size: 0.9em;
    }

    .edit-icon {
        color: #FF6B2C;
        cursor: pointer;
        margin-left: 10px;
        font-size: 14px;
    }

    .edit-icon:hover {
        color: #e55a20;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-secondary {
        background: #fff;
        color: #00274D;
        border: 2px solid #00274D;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        background: #00274D;
        color: white;
    }

    /* Profile header with avatar */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #FF6B2C;
    }

    .profile-avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #FF6B2C;
        background: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 700;
        color: #00274D;
        text-transform: uppercase;
    }

    .profile-header-info {
        flex: 1;
    }

    .profile-header-info h2 {
        font-size: 28px;
        color: #00274D;
        margin-bottom: 5px;
    }

    .profile-header-info p {
        color: #666;
        font-size: 16px;
    }

    .avatar-edit-btn {
        background: #eee;
        padding: 8px 16px;
        border-radius: 20px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .avatar-edit-btn:hover {
        background: #ddd;
    }

    /* Success message */
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .success-message i {
        font-size: 18px;
    }

    /* Tambahkan di bagian atas CSS setelah .data-empty */
    .info-value span.data-empty {
        color: #ff6b6b;
        font-style: italic;
        font-size: 0.9em;
        background: #fff3f3;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px dashed #ff6b6b;
    }

    /* Cancel edit button */
    .cancel-edit-btn {
        background: #fff;
        color: #dc3545;
        border: 2px solid #dc3545;
        padding: 14px 24px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto 30px;
    }

    .cancel-edit-btn:hover {
        background: #dc3545;
        color: white;
    }

    /* File upload */
    .file-input-container {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    /* Responsive untuk layar kecil */
    @media (max-width: 768px) {
        .profile-box h3 {
            font-size: 28px;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .info-value {
            text-align: left;
            width: 100%;
        }

        .action-buttons {
            flex-direction: column;
        }

        button[type="submit"], .btn-secondary, .edit-profile-btn, .cancel-edit-btn {
            width: 100%;
        }
    }

    /* Grid layout for form */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Form note */
    .form-note {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 5px;
        font-style: italic;
    }

    /* Required field indicator */
    .required::after {
        content: ' *';
        color: #ff6b6b;
    }
</style>
@endpush

@php
    // Function untuk mendapatkan inisial dari nama
    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        // Jika hanya 1 kata, ambil 2 karakter pertama
        if (strlen($initials) == 1) {
            $initials = strtoupper(substr($name, 0, 2));
        } else {
            // Ambil maksimal 2 huruf inisial
            $initials = substr($initials, 0, 2);
        }

        return $initials;
    }
@endphp

@section('content')
<!-- Success Message (if any) -->
@if(session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Error Messages -->
@if($errors->any())
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Profile Header with Avatar -->
<div class="profile-header">
    <div style="position: relative;">
        @if(!empty($user->avatar_url))
            <img src="{{ $user->avatar_url }}" alt="Profile Picture" class="profile-avatar">
        @else
            <div class="profile-avatar-initials">
                {{ getInitials($user->name) }}
            </div>
        @endif
        <div class="file-input-container" style="margin-top: 10px;">
            <button class="avatar-edit-btn" onclick="document.getElementById('avatar').click()">
                <i class="fas fa-camera"></i> Ganti Foto
            </button>
        </div>
    </div>
    <div class="profile-header-info">
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->email }}</p>
        <p style="color: #FF6B2C; font-weight: 600; margin-top: 5px;">
            <i class="fas fa-crown"></i> {{ $user->membership_level ?? 'Bronze' }} Member
        </p>
    </div>
</div>

<!-- Welcome Message -->
<div class="profile-box">
    <h3>Hello, {{ $user->name }}</h3>
    <p>Kelola informasi akun dan keamanan Anda</p>
</div>

<!-- Profile Information Cards -->
<div class="profile-info-grid">
    <!-- Personal Information Card -->
    <div class="info-card">
        <h4><i class="fas fa-id-card"></i> Informasi Pribadi</h4>
        <div class="info-item">
            <span class="info-label">Username</span>
            <span class="info-value">{{ $user->username ?? 'Belum diisi' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $user->email ?? 'Belum diisi' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">No. Telepon</span>
            <span class="info-value">
                @if(!empty($user->phone))
                    {{ $user->phone }}
                @else
                    <span class="data-empty">Belum diisi</span>
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">NIK</span>
            <span class="info-value">
                @if(!empty($user->nik))
                    {{ $user->nik }}
                @else
                    <span class="data-empty">Belum diisi</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Account Information Card -->
    <div class="info-card">
        <h4><i class="fas fa-user-circle"></i> Informasi Akun</h4>
        <div class="info-item">
            <span class="info-label">Membership</span>
            <span class="info-value" style="color: #FF6B2C; font-weight: 600;">
                {{ $user->membership_level ?? 'Bronze' }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Member Points</span>
            <span class="info-value" style="color: #FF6B2C; font-weight: 600;">
                {{ $user->member_point ?? 0 }} Points
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Loyalty Points</span>
            <span class="info-value" style="color: #FF6B2C; font-weight: 600;">
                {{ $user->loyalty_point ?? 0 }} Points
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Tanggal Bergabung</span>
            <span class="info-value">
                @if($user->created_at)
                    {{ $user->created_at->format('d F Y') }}
                @else
                    Belum tersedia
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Status Akun</span>
            <span class="info-value" style="color: #28a745;">
                <i class="fas fa-check-circle"></i> Aktif
            </span>
        </div>
    </div>
</div>

<!-- Tombol Edit Profil (Awalnya Tampil) -->
<button class="edit-profile-btn" id="showEditFormBtn">
    <i class="fas fa-edit"></i> Edit Profil
</button>

<!-- Cancel Edit Button (Awalnya Tersembunyi) -->
<button class="cancel-edit-btn" id="cancelEditBtn" style="display: none;">
    <i class="fas fa-times"></i> Batalkan Edit
</button>

<!-- Edit Profile Form (Awalnya Tersembunyi) -->
<div class="form-card" id="editProfileForm">
    <h4 style="color: white; font-size: 20px; margin-bottom: 20px; border-bottom: 2px solid #FF6B2C; padding-bottom: 10px;">
        <i class="fas fa-edit"></i> Edit Profil
    </h4>

    <form action="{{ route('customer.profilcust.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="handleAvatarChange(event)">

        <div class="form-grid">
            <div>
                <label for="name" class="required">Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" value="{{ old('username', $user->username) }}">
                <div class="form-note">Jika kosong, akan menggunakan email tanpa domain</div>
                @error('username')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <label for="email" class="required">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
        @enderror

        <div class="form-grid">
            <div>
                <label for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone', $user->phone) }}">
                <div class="form-note">Format: 08xxxxxxxxxx</div>
                @error('phone')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="nik">NIK</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" value="{{ old('nik', $user->nik) }}" maxlength="16">
                <div class="form-note">16 digit angka</div>
                @error('nik')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid">
            <div>
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru">
                <div class="form-note">Kosongkan jika tidak ingin mengubah</div>
                @error('password')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru">
                @error('password_confirmation')
                    <span class="form-note" style="color: #ff6b6b;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <button type="submit">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </form>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <button type="button" class="btn-secondary" onclick="location.href='{{ route('customer.dashboardprofile') }}'">
        <i class="fas fa-home"></i> Kembali ke Dashboard
    </button>

    <button type="button" class="btn-secondary" onclick="location.href='{{ route('customer.riwayat') }}'">
        <i class="fas fa-history"></i> Lihat Riwayat Pesanan
    </button>

    <button type="button" class="btn-secondary" onclick="location.href='{{ route('customer.membership') }}'">
        <i class="fas fa-crown"></i> Cek Membership
    </button>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const showEditFormBtn = document.getElementById('showEditFormBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const editProfileForm = document.getElementById('editProfileForm');

        // Show edit form when button is clicked
        if (showEditFormBtn) {
            showEditFormBtn.addEventListener('click', function() {
                // Hide edit button
                showEditFormBtn.style.display = 'none';

                // Show cancel button
                cancelEditBtn.style.display = 'flex';

                // Show edit form
                editProfileForm.classList.add('show');

                // Scroll to form
                editProfileForm.scrollIntoView({ behavior: 'smooth' });
            });
        }

        // Hide edit form when cancel button is clicked
        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', function() {
                // Show edit button
                showEditFormBtn.style.display = 'block';

                // Hide cancel button
                cancelEditBtn.style.display = 'none';

                // Hide edit form
                editProfileForm.classList.remove('show');

                // Scroll back to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Phone number validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // Ensure starts with 08
                if (value.length > 0 && !value.startsWith('08')) {
                    value = '08' + value.replace(/^08/, '');
                }

                // Limit to 12-13 digits
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }

                // Format: 0812-3456-7890
                if (value.length > 4) {
                    value = value.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
                }

                e.target.value = value;
            });
        }

        // NIK validation (16 digits only)
        const nikInput = document.getElementById('nik');
        if (nikInput) {
            nikInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.substring(0, 16);
                }
                e.target.value = value;
            });
        }

        // Password confirmation validation
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');

        function validatePasswords() {
            if (passwordInput.value && passwordConfirmInput.value) {
                if (passwordInput.value !== passwordConfirmInput.value) {
                    passwordConfirmInput.style.borderColor = '#ff6b6b';
                    return false;
                } else {
                    passwordConfirmInput.style.borderColor = '';
                    return true;
                }
            }
            return true;
        }

        if (passwordInput && passwordConfirmInput) {
            passwordInput.addEventListener('input', validatePasswords);
            passwordConfirmInput.addEventListener('input', validatePasswords);
        }

        // Form submission validation
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                if (!validatePasswords()) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak sama!');
                    return;
                }

                // Show loading
                const submitBtn = profileForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                // Form will submit normally
            });
        }
    });

    // Handle avatar change
    function handleAvatarChange(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                event.target.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                event.target.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarContainer = document.querySelector('.profile-avatar, .profile-avatar-initials');
                if (avatarContainer.classList.contains('profile-avatar-initials')) {
                    // Replace initials with image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'profile-avatar';
                    img.alt = 'Profile Picture';
                    avatarContainer.parentNode.replaceChild(img, avatarContainer);
                } else if (avatarContainer.classList.contains('profile-avatar')) {
                    // Update existing image
                    avatarContainer.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);

            // Show success message
            showSuccessMessage('Foto profil akan diubah setelah disimpan!');
        }
    }

    // Function to show success message
    function showSuccessMessage(message) {
        // Remove existing success messages
        const existingMessages = document.querySelectorAll('.success-message');
        existingMessages.forEach(msg => msg.remove());

        // Create new success message
        const successMessage = document.createElement('div');
        successMessage.className = 'success-message';
        successMessage.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        `;

        // Insert success message at the top
        const profileHeader = document.querySelector('.profile-header');
        profileHeader.parentNode.insertBefore(successMessage, profileHeader);

        // Auto remove message after 5 seconds
        setTimeout(() => {
            if (successMessage.parentNode) {
                successMessage.remove();
            }
        }, 5000);
    }
</script>
@endpush
