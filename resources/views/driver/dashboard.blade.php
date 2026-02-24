@extends('layouts.app-driver')

@section('title', 'Dashboard Driver - Smart Shuttle')

@push('styles')
<style>
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


    .dashboard-container {
            width: 100%;
            padding: 1rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
    /* ===== HEADER SECTION (DIUBAH SESUAI BANTUAN) ===== */
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

    /* Stats Grid - 4 kolom sejajar */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.5s ease;
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

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        text-align: center;
        border-left: 4px solid var(--secondary-color);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 120px;
        border: 1px solid var(--gray-border);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
        text-align: center;
    }

    /* Main Grid */
    .main-grid {
        display: grid;
        grid-template-columns: 3fr 4fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        align-items: stretch;
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease 0.1s both;
    }

    .profile-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
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

    /* Map Card */
    .map-card {
        background: var(--secondary-color);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 2rem;
        color: white;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: space-between;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        animation: fadeIn 0.5s ease 0.2s both;
    }

    .map-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-3px);
    }

    .map-card .card-header {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
        flex-shrink: 0;
    }

    /* Map Placeholder */
    .map-placeholder {
        background: white;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        padding: 2rem;
        text-align: center;
        flex-grow: 1;
        margin: 1rem 0;
        min-height: 180px;
    }

    .map-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--secondary-color);
    }

    .map-placeholder p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
        line-height: 1.4;
    }

    /* REVISI: Jadwal Card Memanjang */
    .schedule-full-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 2rem;
        grid-column: 1 / -1; /* Memanjang full width */
        margin-bottom: 1.5rem;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease 0.3s both;
    }

    .schedule-full-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
    }

    .schedule-full-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .schedule-full-card .card-header {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
        text-align: center;
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
    }

    .schedule-full-card .card-header i {
        color: var(--primary-orange);
        font-size: 1.3rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .schedule-full-card .card-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: var(--primary-orange);
    }

    /* REVISI: Schedule Table yang Lebih Besar */
    .schedule-table-full {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1rem;
        margin: 1.5rem 0;
    }

    .schedule-table-full tr {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .schedule-table-full tr:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .schedule-table-full td {
        padding: 1.2rem;
        vertical-align: middle;
        font-size: 1rem;
    }

    .schedule-table-full td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        font-weight: 700;
        color: var(--primary-color);
        width: 25%;
        font-size: 1.1rem;
    }

    .schedule-table-full td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 1rem;
        line-height: 1.5;
    }

    /* Profile Styles */
    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1.5rem;
        border: 4px solid var(--secondary-color);
    }

    .status-badge {
        background: #28a745;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .profile-info {
        text-align: center;
        width: 100%;
    }

    .profile-info p {
        margin: 0.75rem 0;
        color: #666;
        font-size: 1rem;
        text-align: center;
        line-height: 1.4;
    }

    .profile-info strong {
        color: var(--text-dark);
    }

    /* Buttons */
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: var(--secondary-color);
        color: white;
    }

    .btn-primary:hover {
        background: #e55a00;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: white;
        color: var(--secondary-color);
        border: 2px solid white;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }

    /* Card Headers */
    .card-header {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }

    .map-card .card-header {
        color: white;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .main-grid {
            grid-template-columns: 1fr;
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

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            min-height: 100px;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .dashboard-container {
            padding: 1rem;
        }

        .profile-card {
            padding: 1.5rem;
        }

        .profile-image {
            width: 80px;
            height: 80px;
        }

        .profile-name {
            font-size: 1.3rem;
        }

        .map-card {
            padding: 1.5rem;
        }

        .schedule-full-card {
            padding: 1.5rem;
        }

        .schedule-full-card .card-header {
            font-size: 1.2rem;
        }

        .schedule-table-full td {
            padding: 1rem;
            font-size: 0.9rem;
        }

        .schedule-table-full td:first-child {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            min-height: 90px;
        }

        .stat-number {
            font-size: 1.8rem;
        }

        .map-placeholder {
            padding: 1.5rem;
            min-height: 150px;
        }

        .schedule-table-full {
            border-spacing: 0 0.5rem;
        }

        .schedule-table-full td {
            padding: 0.8rem;
            font-size: 0.85rem;
        }

        .schedule-table-full td:first-child {
            font-size: 0.9rem;
            width: 30%;
        }
    }

    @media (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }
    }

    /* Landscape mode */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .dashboard-container {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- HEADER SECTION - DIUBAH SESUAI BANTUAN -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-chart-bar"></i>
            Dashboard Driver
        </h1>
    </div>

    <div class="divider"></div>

    <!-- Stats row - 4 KOLOM SEJAJAR -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $jumlahJadwalBulanIni ?? 0 }}</div>
            <div class="stat-label">Perjalanan bulan ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $jadwalAktif ?? 0 }}</div>
            <div class="stat-label">Jadwal aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $jadwalSelesai ?? 0 }}</div>
            <div class="stat-label">Perjalanan selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalJadwal ?? 0 }}</div>
            <div class="stat-label">Total perjalanan</div>
        </div>
    </div>

    <!-- Middle boxes -->
    <div class="main-grid">
        <!-- Profile Card -->
        <div class="profile-card">
            @if($driver && $driver->avatar)
                <img src="{{ asset('storage/' . $driver->avatar) }}" alt="Profile" class="profile-image">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" class="profile-image">
            @endif
            <div class="status-badge">{{ $jadwalAktif > 0 ? 'Sedang Bekerja' : 'Siap Berangkat' }}</div>
            <h3 class="profile-name">{{ $driver->name ?? 'Driver' }}</h3>
            <div class="profile-info">
                <p><strong>NIK:</strong> {{ $driver->nik ?? '-' }}</p>
                <p><strong>No. SIM:</strong> {{ $driver->nomor_sim ?? '-' }}</p>
                <p><strong>Kontak:</strong> {{ $driver->telepon ?? $driver->phone ?? '-' }}</p>
            </div>
        </div>

        <!-- Peta & Navigasi -->
        <div class="map-card">
            <h3 class="card-header">Peta & Navigasi</h3>
            <div class="map-placeholder">
                <i class="fas fa-map text-3xl mb-2"></i>
                <p class="text-center text-sm">Maps akan tampil saat perjalanan aktif</p>
            </div>
            <button class="btn btn-secondary">Mulai Navigasi</button>
        </div>
    </div>

    <!-- REVISI: Jadwal Hari Ini Memanjang -->
    <div class="schedule-full-card">
        <h3 class="card-header">
            <i class="fas fa-calendar-alt"></i>
            Jadwal Hari Ini
        </h3>
        @if(isset($schedules) && count($schedules) > 0)
            <table class="schedule-table-full">
                @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $loop->iteration }}.
                        @if(isset($schedule->masterRute) && $schedule->masterRute)
                            {{ $schedule->masterRute->nama_rute ?? $schedule->rute ?? 'Jadwal' }}
                        @else
                            {{ $schedule->rute ?? 'Jadwal' }}
                        @endif
                    </td>
                    <td>
                        <strong>
                            @if(isset($schedule->masterRute) && $schedule->masterRute)
                                {{ $schedule->masterRute->kota_asal ?? 'Asal' }} - {{ $schedule->masterRute->kota_tujuan ?? 'Tujuan' }}
                            @else
                                {{ $schedule->rute ?? 'Rute tidak tersedia' }}
                            @endif
                        </strong><br>
                        <span style="color: var(--secondary-color);">{{ $schedule->waktu_keberangkatan ?? '-' }} - {{ $schedule->waktu_kedatangan ?? '-' }}</span><br>
                        <small>
                            @if(isset($schedule->jadwal) && $schedule->jadwal && isset($schedule->jadwal->armada))
                                {{ $schedule->jadwal->armada }} -
                            @elseif($schedule->armada)
                                {{ $schedule->armada }} -
                            @endif
                            {{ $schedule->kursi_terisi ?? 0 }}/{{ $schedule->total_kursi ?? 0 }} Penumpang
                            @if($schedule->status)
                                <span style="color: var(--secondary-color); font-weight: 600;">
                                    ({{
                                        $schedule->status === 'aktif' ? 'Aktif' :
                                        ($schedule->status === 'selesai' ? 'Selesai' :
                                        ($schedule->status === 'dibatalkan' ? 'Dibatalkan' :
                                        ucfirst($schedule->status)))
                                    }})
                                </span>
                            @endif
                        </small>
                    </td>
                </tr>
                @endforeach
            </table>
        @else
            <table class="schedule-table-full">
                <tr>
                    <td style="text-align: center; color: #999;">
                        Tidak ada jadwal hari ini
                        @if(isset($totalJadwal) && $totalJadwal > 0)
                            <br><small>(Total {{ $totalJadwal }} jadwal tersedia)</small>
                        @endif
                    </td>
                </tr>
            </table>
        @endif
        <button class="btn btn-primary">Lihat Detail Jadwal</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Driver Dashboard loaded');

        // Set active menu untuk halaman dashboard
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
            if (link.id === 'dashboard-link') {
                link.classList.add('menu-active');
            }
        });

        // Contoh interaksi dengan tombol
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const buttonText = this.textContent.trim();
                switch(buttonText) {
                    case 'Mulai Navigasi':
                        alert('Fitur navigasi akan segera dimulai');
                        break;
                    case 'Lihat Detail Jadwal':
                        // Redirect ke halaman jadwal jika route ada
                        window.location.href = "{{ route('driver.jadwal') ?? '#' }}";
                        break;
                }
            });
        });
    });
</script>
@endpush