@extends('layouts.app-driver')

@section('title', 'Jadwal Driver - Smart Shuttle')

@section('page-title', 'Jadwal Saya')

@push('styles')
<style>
    /* Define CSS Variables */
    :root {
        --primary: #3498db;
        --secondary: #2c3e50;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light: #f5f7fa;
        --dark: #2c3e50;
        --gray: #7f8c8d;
    }

    /* Main Content Styling */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title h1 {
        font-size: 28px;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .page-title p {
        color: #7f8c8d;
        margin: 0;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-success {
        background-color: #2ecc71;
        color: white;
    }

    .btn-success:hover {
        background-color: #27ae60;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    /* Tabs */
    .tabs {
        display: flex;
        background: white;
        border-radius: 10px;
        padding: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
    }

    .tab {
        flex: 1;
        padding: 15px 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s;
        font-weight: 500;
        color: #7f8c8d;
        min-width: 120px;
    }

    .tab:hover {
        background: rgba(52, 152, 219, 0.1);
    }

    .tab.active {
        background: #3498db;
        color: white;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Schedule Cards */
    .schedule-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        border: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
    }

    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .schedule-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .schedule-header.active {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .schedule-header.completed {
        background: linear-gradient(135deg, #7f8c8d, #95a5a6);
    }

    .schedule-header.cancelled {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .schedule-header.upcoming {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .schedule-title {
        font-size: 18px;
        font-weight: 600;
        flex: 1;
        line-height: 1.4;
    }

    .schedule-status {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: capitalize;
        backdrop-filter: blur(10px);
    }

    .schedule-body {
        padding: 20px;
        flex: 1;
    }

    .schedule-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .info-icon.route {
        background: #3498db;
    }

    .info-icon.time {
        background: #9b59b6;
    }

    .info-icon.price {
        background: #2ecc71;
    }

    .info-icon.passenger {
        background: #f39c12;
    }

    .info-icon.bus {
        background: #e74c3c;
    }

    .info-text {
        flex: 1;
    }

    .info-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 3px;
        font-weight: 500;
    }

    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: #2c3e50;
        line-height: 1.4;
    }

    .schedule-footer {
        padding: 15px 20px;
        background: #f9f9f9;
        border-top: 1px solid #eee;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .driver-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .driver-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }

    .driver-name {
        font-size: 14px;
        font-weight: 500;
        color: #2c3e50;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .action-btn.view {
        background: #3498db;
        color: white;
    }

    .action-btn.view:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .action-btn.edit {
        background: #f39c12;
        color: white;
    }

    .action-btn.edit:hover {
        background: #e67e22;
        transform: translateY(-2px);
    }

    .action-btn.complete {
        background: #2ecc71;
        color: white;
    }

    .action-btn.complete:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }

    .action-btn.cancel {
        background: #e74c3c;
        color: white;
    }

    .action-btn.cancel:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #bdc3c7;
    }

    .empty-state h3 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .empty-state p {
        color: #7f8c8d;
        margin-bottom: 20px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-completed {
        background: #e2e3e5;
        color: #383d41;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    /* Form inline */
    form.d-inline {
        display: inline-block;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .schedule-container {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .page-title {
            text-align: center;
        }

        .tabs {
            flex-direction: column;
        }

        .tab {
            padding: 12px 15px;
            min-width: auto;
        }

        .schedule-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .action-buttons {
            width: 100%;
            justify-content: flex-start;
        }

        .driver-info {
            width: 100%;
            justify-content: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 480px) {
        .schedule-card {
            border-radius: 8px;
        }
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
            padding: 10px;
        }
        
        form.d-inline {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    // Tambahkan fallback untuk mencegah error
    $schedules = $schedules ?? collect();
    $driver = $driver ?? auth()->user();
@endphp

<div class="page-header">
    <div class="page-title">
        <h1>Jadwal Saya</h1>
        <p>Kelola jadwal perjalanan Anda</p>
    </div>
    <a href="{{ route('driver.available-schedules') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ambil Jadwal Baru
    </a>
</div>

<!-- Tabs -->
<div class="tabs">
    <div class="tab active" data-tab="all">Semua Jadwal</div>
    <div class="tab" data-tab="active">Aktif</div>
    <div class="tab" data-tab="completed">Selesai</div>
    <div class="tab" data-tab="cancelled">Dibatalkan</div>
</div>

<!-- All Schedules Tab -->
<div class="tab-content active" id="all-tab">
    <div class="schedule-container">
        @forelse($schedules as $schedule)
        <div class="schedule-card">
            <div class="schedule-header {{ $schedule->status ?? 'active' }}">
                <div class="schedule-title">
                    {{ $schedule->rute->kota_asal ?? 'N/A' }} → {{ $schedule->rute->kota_tujuan ?? 'N/A' }}
                </div>
                <div class="schedule-status">
                    @if(($schedule->status ?? 'active') == 'active')
                        Aktif
                    @elseif(($schedule->status ?? 'active') == 'completed')
                        Selesai
                    @elseif(($schedule->status ?? 'active') == 'cancelled')
                        Dibatalkan
                    @else
                        {{ ucfirst($schedule->status ?? 'active') }}
                    @endif
                </div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon route">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute</div>
                            <div class="info-value">
                                {{ $schedule->rute->nama_rute ?? 'Tidak diketahui' }}
                                @if($schedule->rute && $schedule->rute->kota_asal && $schedule->rute->kota_tujuan)
                                    ({{ $schedule->rute->kota_asal }} → {{ $schedule->rute->kota_tujuan }})
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon time">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Tanggal & Waktu</div>
                            <div class="info-value">
                                @if($schedule->tanggal_berangkat)
                                    {{ \Carbon\Carbon::parse($schedule->tanggal_berangkat)->format('d M Y') }}
                                @else
                                    Belum ditentukan
                                @endif
                                | {{ $schedule->jam_berangkat ?? '00:00' }}
                                @if($schedule->jam_kedatangan)
                                    - {{ $schedule->jam_kedatangan }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon price">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Harga</div>
                            <div class="info-value">
                                Rp {{ number_format($schedule->harga ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @if($schedule->rute && $schedule->rute->bus)
                    <div class="info-row">
                        <div class="info-icon bus">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Armada</div>
                            <div class="info-value">
                                {{ $schedule->rute->bus->nama_bus ?? 'Tidak diketahui' }}
                                @if($schedule->rute->bus->plat_nomor)
                                    - {{ $schedule->rute->bus->plat_nomor }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    @php
                        $driver = auth()->user();
                        $driverInitials = '';
                        if ($driver && $driver->name) {
                            $nameParts = explode(' ', $driver->name);
                            if (count($nameParts) >= 2) {
                                $driverInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $driverInitials = strtoupper(substr($driver->name, 0, 2));
                            }
                        } else {
                            $driverInitials = 'DR';
                        }
                    @endphp
                    <div class="driver-avatar">{{ $driverInitials }}</div>
                    <div class="driver-name">{{ $driver->name ?? 'Driver' }}</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view" onclick="showScheduleDetail({{ $schedule->id }})">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    
                    @if(($schedule->status ?? 'active') == 'active')
                    <form action="{{ route('driver.schedule.update-status', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="action-btn complete">
                            <i class="fas fa-check"></i> Tandai Selesai
                        </button>
                    </form>
                    
                    <form action="{{ route('driver.schedule.update-status', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="action-btn cancel" onclick="return confirm('Batalkan jadwal ini?')">
                            <i class="fas fa-times"></i> Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-shuttle-van"></i>
            <h3>Belum ada jadwal</h3>
            <p>Anda belum mengambil jadwal dari admin.</p>
            <a href="{{ route('driver.available-schedules') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Ambil Jadwal
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Active Tab -->
<div class="tab-content" id="active-tab">
    <div class="schedule-container">
        @php 
            $activeSchedules = $schedules->where('status', 'active');
        @endphp
        @forelse($activeSchedules as $schedule)
        <div class="schedule-card">
            <div class="schedule-header active">
                <div class="schedule-title">
                    {{ $schedule->rute->kota_asal ?? 'N/A' }} → {{ $schedule->rute->kota_tujuan ?? 'N/A' }}
                </div>
                <div class="schedule-status">Aktif</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon route">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute</div>
                            <div class="info-value">
                                {{ $schedule->rute->nama_rute ?? 'Tidak diketahui' }}
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon time">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Tanggal & Waktu</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($schedule->tanggal_berangkat)->format('d M Y') }}
                                {{ $schedule->jam_berangkat }}
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon price">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Harga</div>
                            <div class="info-value">
                                Rp {{ number_format($schedule->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    @php
                        $driver = auth()->user();
                        $driverInitials = '';
                        if ($driver && $driver->name) {
                            $nameParts = explode(' ', $driver->name);
                            if (count($nameParts) >= 2) {
                                $driverInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $driverInitials = strtoupper(substr($driver->name, 0, 2));
                            }
                        } else {
                            $driverInitials = 'DR';
                        }
                    @endphp
                    <div class="driver-avatar">{{ $driverInitials }}</div>
                    <div class="driver-name">{{ $driver->name ?? 'Driver' }}</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view" onclick="showScheduleDetail({{ $schedule->id }})">
                        Detail
                    </button>
                    <form action="{{ route('driver.schedule.update-status', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="action-btn edit">
                            Tandai Selesai
                        </button>
                    </form>
                    <form action="{{ route('driver.schedule.update-status', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="action-btn edit" onclick="return confirm('Batalkan jadwal ini?')">
                            Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-clock"></i>
            <h3>Tidak ada jadwal aktif</h3>
            <p>Tidak ada jadwal perjalanan yang aktif saat ini.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Completed Tab -->
<div class="tab-content" id="completed-tab">
    <div class="schedule-container">
        @php 
            $completedSchedules = $schedules->where('status', 'completed');
        @endphp
        @forelse($completedSchedules as $schedule)
        <div class="schedule-card">
            <div class="schedule-header completed">
                <div class="schedule-title">
                    {{ $schedule->rute->kota_asal ?? 'N/A' }} → {{ $schedule->rute->kota_tujuan ?? 'N/A' }}
                </div>
                <div class="schedule-status">Selesai</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon route">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute</div>
                            <div class="info-value">
                                {{ $schedule->rute->nama_rute ?? 'Tidak diketahui' }}
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon time">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Tanggal & Waktu</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($schedule->tanggal_berangkat)->format('d M Y') }}
                                {{ $schedule->jam_berangkat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    @php
                        $driver = auth()->user();
                        $driverInitials = '';
                        if ($driver && $driver->name) {
                            $nameParts = explode(' ', $driver->name);
                            if (count($nameParts) >= 2) {
                                $driverInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $driverInitials = strtoupper(substr($driver->name, 0, 2));
                            }
                        } else {
                            $driverInitials = 'DR';
                        }
                    @endphp
                    <div class="driver-avatar">{{ $driverInitials }}</div>
                    <div class="driver-name">{{ $driver->name ?? 'Driver' }}</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view" onclick="showScheduleDetail({{ $schedule->id }})">
                        Detail
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>Tidak ada jadwal selesai</h3>
            <p>Tidak ada jadwal perjalanan yang telah selesai.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Cancelled Tab -->
<div class="tab-content" id="cancelled-tab">
    <div class="schedule-container">
        @php 
            $cancelledSchedules = $schedules->where('status', 'cancelled');
        @endphp
        @forelse($cancelledSchedules as $schedule)
        <div class="schedule-card">
            <div class="schedule-header cancelled">
                <div class="schedule-title">
                    {{ $schedule->rute->kota_asal ?? 'N/A' }} → {{ $schedule->rute->kota_tujuan ?? 'N/A' }}
                </div>
                <div class="schedule-status">Dibatalkan</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon route">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute</div>
                            <div class="info-value">
                                {{ $schedule->rute->nama_rute ?? 'Tidak diketahui' }}
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon time">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Tanggal & Waktu</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($schedule->tanggal_berangkat)->format('d M Y') }}
                                {{ $schedule->jam_berangkat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    @php
                        $driver = auth()->user();
                        $driverInitials = '';
                        if ($driver && $driver->name) {
                            $nameParts = explode(' ', $driver->name);
                            if (count($nameParts) >= 2) {
                                $driverInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $driverInitials = strtoupper(substr($driver->name, 0, 2));
                            }
                        } else {
                            $driverInitials = 'DR';
                        }
                    @endphp
                    <div class="driver-avatar">{{ $driverInitials }}</div>
                    <div class="driver-name">{{ $driver->name ?? 'Driver' }}</div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-ban"></i>
            <h3>Tidak ada jadwal dibatalkan</h3>
            <p>Tidak ada jadwal perjalanan yang dibatalkan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to current tab and content
                this.classList.add('active');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });

        // Confirm before cancelling schedule
        const cancelButtons = document.querySelectorAll('.action-btn.cancel, .action-btn.edit[onclick*="confirm"]');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin membatalkan jadwal ini?')) {
                    e.preventDefault();
                }
            });
        });
    });

    // Show schedule detail function
    function showScheduleDetail(scheduleId) {
        // You can implement this function based on your requirements
        // For now, we'll show an alert with the schedule ID
        alert(`Detail jadwal ID: ${scheduleId}\nFitur detail sedang dalam pengembangan.`);
        
        // Alternatively, you can redirect to a detail page:
        // window.location.href = `/driver/schedules/${scheduleId}`;
    }

    // Update schedule status function (if not using form submission)
    function updateScheduleStatus(scheduleId, status) {
        if (confirm(`Apakah Anda yakin ingin ${status === 'completed' ? 'menandai selesai' : 'membatalkan'} jadwal ini?`)) {
            // You can implement AJAX call here or use form submission
            // For form submission, we'll find and submit the corresponding form
            const form = document.querySelector(`form[action*="${scheduleId}"] input[value="${status}"]`)?.closest('form');
            if (form) {
                form.submit();
            }
        }
    }
</script>
@endpush