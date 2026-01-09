@extends('layouts.app-profile')

@section('title', 'Dashboard - SmartShuttle')

@push('styles')
<style>
    /* STYLES KHUSUS DASHBOARD */
    * {
        box-sizing: border-box;
    }

    body {
        overflow-x: hidden;
    }

    .dashboard-container {
        max-width: 100%;
        overflow-x: hidden;
        padding: 0;
    }

    .welcome-box {
        background: linear-gradient(135deg, #00274D 0%, #004080 100%);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 39, 77, 0.2);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }

    .welcome-box::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: white;
        line-height: 1.2;
    }

    .welcome-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        max-width: 100%;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    @media (min-width: 1024px) {
        .content-grid {
            grid-template-columns: 350px 1fr;
            gap: 2rem;
            max-width: 1400px;
        }
    }

    /* PROFILE CARD */
    .profile-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e8ecef;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
        max-width: 100%;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .profile-image-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .profile-image {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        border: 4px solid #00274D;
        box-shadow: 0 8px 20px rgba(0, 39, 77, 0.3);
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: #00274D;
        position: relative;
        overflow: hidden;
    }

    .profile-image-initials {
        font-size: 3.5rem;
        font-weight: 700;
        color: #00274D;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: #e0e0e0;
        color: #00274D;
        border-radius: 50%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-name {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00274D;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        word-wrap: break-word;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
        width: 100%;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        font-weight: 600;
        color: #6A3900;
        font-size: 0.875rem;
        margin-bottom: 0.375rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label i {
        color: #FF6B2C;
        font-size: 0.9rem;
    }

    .info-value {
        color: #00274D;
        font-size: 1rem;
        font-weight: 500;
        word-wrap: break-word;
    }

    .data-empty {
        color: #ff6b6b;
        font-style: italic;
        font-size: 0.9rem;
    }

    /* MEMBERSHIP CARD */
    .membership-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        padding: 0;
        border: 1px solid #e8ecef;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .membership-header {
        display: flex;
        align-items: stretch;
        min-height: 220px;
    }

    .membership-badge {
        flex: 1;
        background: linear-gradient(145deg, #6A3900 0%, #8B4513 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(106, 57, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .membership-badge::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        animation: shine 3s infinite linear;
    }

    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .membership-icon {
        font-size: 3.5rem;
        color: #FFD700;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .membership-badge span {
        color: #FFD700;
        font-weight: 700;
        font-size: 1.75rem;
        text-align: center;
        position: relative;
        z-index: 1;
        line-height: 1.3;
    }

    .membership-level {
        flex: 1;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: white;
    }

    .membership-level h4 {
        color: #6A3900;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .membership-level h4 i {
        color: #FFD700;
    }

    .points-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .point-card {
        background: linear-gradient(135deg, #00274D 0%, #004080 100%);
        border-radius: 0.75rem;
        box-shadow: 0 8px 25px rgba(0, 39, 77, 0.2);
        padding: 1.5rem 1rem;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .point-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 39, 77, 0.3);
    }

    .point-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        line-height: 1;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .point-label {
        font-size: 0.9375rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    /* NOTIFICATIONS CARD */
    .notifications-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e8ecef;
        width: 100%;
        max-width: 100%;
    }

    .notifications-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00274D;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .no-notifications {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #9ca3af;
    }

    .no-notifications i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
        color: #00274D;
    }

    .no-notifications p {
        font-size: 1rem;
        opacity: 0.7;
        margin-bottom: 0.5rem;
    }

    .no-notifications .text-muted {
        font-size: 0.875rem;
        opacity: 0.6;
    }

    .view-all-btn {
        background: #FF6B2C;
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 50px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: block;
        margin: 1.5rem auto 0;
        min-width: 180px;
        max-width: 100%;
        box-shadow: 0 5px 15px rgba(255, 107, 44, 0.3);
    }

    .view-all-btn:hover {
        background: #e55a1f;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 44, 0.4);
    }

    .view-all-btn i {
        margin-left: 6px;
        font-size: 0.875rem;
    }

    .right-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        width: 100%;
    }

    /* Responsive untuk layar kecil */
    @media (max-width: 1023px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 0.5rem;
        }

        .membership-header {
            flex-direction: column;
            min-height: auto;
        }

        .membership-badge {
            padding: 1.5rem;
            min-height: 160px;
        }

        .membership-icon {
            font-size: 3rem;
        }

        .membership-badge span {
            font-size: 1.5rem;
        }

        .membership-level {
            padding: 1.5rem;
        }

        .points-container {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .welcome-title {
            font-size: 1.5rem;
        }

        .welcome-box {
            padding: 1.5rem;
        }

        .profile-card {
            padding: 1.25rem;
        }

        .profile-image {
            width: 120px;
            height: 120px;
        }

        .profile-image-initials {
            font-size: 3rem;
        }

        .profile-name {
            font-size: 1.375rem;
        }

        .notifications-card {
            padding: 1.25rem;
        }

        .membership-badge {
            min-height: 140px;
        }

        .membership-icon {
            font-size: 2.5rem;
        }

        .membership-badge span {
            font-size: 1.25rem;
        }

        .membership-level h4 {
            font-size: 1.5rem;
        }

        .point-value {
            font-size: 2rem;
        }

        .view-all-btn {
            width: 100%;
            max-width: 200px;
        }
    }

    @media (max-width: 640px) {
        .profile-image {
            width: 100px;
            height: 100px;
        }

        .profile-image-initials {
            font-size: 2.5rem;
        }

        .profile-name {
            font-size: 1.25rem;
        }

        .membership-badge {
            padding: 1.25rem;
        }

        .membership-icon {
            font-size: 2.25rem;
        }

        .membership-badge span {
            font-size: 1.125rem;
        }

        .points-container {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .point-card {
            padding: 1.25rem 1rem;
        }

        .point-value {
            font-size: 1.875rem;
        }
    }

    @media (max-width: 480px) {
        .welcome-title {
            font-size: 1.375rem;
        }

        .welcome-text {
            font-size: 0.9375rem;
        }

        .profile-image {
            width: 90px;
            height: 90px;
        }

        .profile-image-initials {
            font-size: 2rem;
        }

        .profile-name {
            font-size: 1.125rem;
        }

        .membership-badge {
            min-height: 120px;
        }

        .membership-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .membership-badge span {
            font-size: 1rem;
        }

        .membership-level {
            padding: 1.25rem;
        }

        .membership-level h4 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 360px) {
        .welcome-box {
            padding: 1.25rem;
        }

        .profile-card,
        .notifications-card {
            padding: 1rem;
        }

        .membership-badge {
            padding: 1rem;
        }

        .membership-level {
            padding: 1rem;
        }
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
    <!-- HELLO BOX -->
    <div class="welcome-box">
        <h3 class="welcome-title">Hello, {{ $user->name ?? 'Luna' }}! 👋</h3>
        <p class="welcome-text">Siap untuk melakukan perjalanan selanjutnya? Mari jelajahi destinasi baru bersama SmartShuttle!</p>
    </div>

    <!-- CONTENT GRID (2 KOLOM: KIRI = PROFILE, KANAN = MEMBERSHIP + NOTIFIKASI) -->
    <div class="content-grid">
        <!-- KIRI - PROFILE -->
        <div class="profile-card">
            <div class="profile-image-container">
                <!-- Ganti bagian profile image dengan: -->
                <div class="profile-image">
                    @php
                        $avatarData = $user->getAvatarOrInitials();
                    @endphp
                    
                    @if($avatarData['has_avatar'])
                        <img src="{{ $avatarData['avatar_url'] }}" 
                            alt="{{ $user->name }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                    @else
                        <div class="profile-image-initials">
                            {{ $avatarData['initials'] }}
                        </div>
                    @endif
                </div>
            </div>

            <h3 class="profile-name">
                {{ $user->name ?? 'Luna Ayna' }}
            </h3>

            <div class="profile-info">
                <div class="info-item">
                    <p class="info-label"><i class="fas fa-user-tag"></i> Username</p>
                    <p class="info-value">{{ $user->username ?? ($user->email ? explode('@', $user->email)[0] : 'lunaayna') }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label"><i class="fas fa-id-card"></i> NIK</p>
                    @if(!empty($user->nik))
                        <p class="info-value">{{ $user->nik }}</p>
                    @else
                        <p class="info-value data-empty">Belum ditambahkan</p>
                    @endif
                </div>
                <div class="info-item">
                    <p class="info-label"><i class="fas fa-envelope"></i> Email</p>
                    <p class="info-value">{{ $user->email ?? 'L.Ayna@gmail.com' }}</p>
                </div>
                <div class="info-item">
                    <p class="info-label"><i class="fas fa-phone"></i> No Handphone</p>
                    @if(!empty($user->phone))
                        <p class="info-value">{{ $user->phone }}</p>
                    @else
                        <p class="info-value data-empty">Belum ditambahkan</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- KANAN = MEMBERSHIP + NOTIFIKASI -->
        <div class="right-column">
            <!-- MEMBERSHIP -->
            <div class="membership-card">
                <div class="membership-header">
                    <!-- LEFT BROWN CARD -->
                    <div class="membership-badge">
                        <i class="fas fa-crown membership-icon"></i>
                        <span>{{ $user->membership_level ?? 'Bronze' }}<br>MEMBER</span>
                    </div>

                    <!-- RIGHT INFO -->
                    <div class="membership-level">
                        <h4>Status Membership</h4>
                        <div class="points-container">
                            <div class="point-card">
                                <p class="point-value">
                                    {{ $user->member_point ?? 0 }}
                                </p>
                                <p class="point-label">Member Points</p>
                            </div>

                            <div class="point-card">
                                <p class="point-value">
                                    {{ $user->loyalty_point ?? 0 }}
                                </p>
                                <p class="point-label">Loyalty Points</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTIFIKASI -->
            <div class="notifications-card">
                <h3 class="notifications-title">
                    <i class="fas fa-bell"></i> Notifikasi
                </h3>

                <div class="no-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p>Tidak ada notifikasi baru</p>
                    <p class="text-muted">Semua notifikasi Anda akan muncul di sini</p>
                </div>

                <div class="text-center">
                    <button class="view-all-btn" id="viewAllNotifications">
                        Lihat Semua Notifikasi <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // View all notifications button
        const viewAllBtn = document.getElementById('viewAllNotifications');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function() {
                alert('Halaman notifikasi akan segera tersedia!');
            });
        }

        // Animate point cards on hover
        const pointCards = document.querySelectorAll('.point-card');
        pointCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Prevent horizontal scroll
        document.body.style.overflowX = 'hidden';
        document.documentElement.style.overflowX = 'hidden';
    });
</script>
@endpush
    