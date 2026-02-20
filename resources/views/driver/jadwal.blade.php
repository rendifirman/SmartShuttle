@extends('layouts.app-driver')

@section('title', 'Jadwal Saya - Driver')

@push('styles')
<style>
    /* ===== CUSTOM CSS UNTUK HALAMAN JADWAL ===== */
    /* Container dan Layout */
    .container-fluid {
        width: 100%;
        padding: 0;
    }
    
    /* Header Section */
    .header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e5ec;
    }
    
    .header-title h1 {
        font-size: 26px;
        font-weight: 700;
        color: #0d3559;
        margin-bottom: 8px;
    }
    
    .header-title p {
        font-size: 15px;
        color: #7a7a7a;
    }
    
    /* Button Style */
    .btn-custom {
        background: #ff6a00;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-custom:hover {
        background: #e65c00;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        color: white;
    }
    
    .btn-secondary-custom {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-secondary-custom:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        color: white;
    }
    
    /* Statistik Cards */
    .row-stats {
        display: flex;
        gap: 25px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    
    .col-stats {
        flex: 1;
        min-width: 250px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        border-left: 5px solid;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card-primary {
        border-left-color: #0d3559;
    }
    
    .stat-card-success {
        border-left-color: #28a745;
    }
    
    .stat-card-info {
        border-left-color: #17a2b8;
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-label {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .stat-label-primary {
        color: #0d3559;
    }
    
    .stat-label-success {
        color: #28a745;
    }
    
    .stat-label-info {
        color: #17a2b8;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    
    .stat-icon {
        font-size: 45px;
        color: #e0e5ec;
    }
    
    /* Progress Bar */
    .progress-wrapper {
        margin-top: 10px;
    }
    
    .progress {
        background: #e9ecef;
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 5px;
    }
    
    .progress-bar {
        background: #0d3559;
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    
    .progress-text {
        font-size: 12px;
        color: #7a7a7a;
    }
    
    /* Alert Notifications */
    .alert {
        padding: 18px 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
        position: relative;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 5px solid #28a745;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 5px solid #dc3545;
    }
    
    .alert i {
        font-size: 18px;
    }
    
    .btn-close {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    /* Card Utama */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .card-header {
        padding: 20px 25px;
        background: white;
        border-bottom: 2px solid #f0f2f5;
    }
    
    .card-header h6 {
        font-size: 16px;
        font-weight: 700;
        color: #0d3559;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }
    
    .card-header i {
        color: #ff6a00;
    }
    
    .card-body {
        padding: 25px;
    }
    
    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table thead th {
        background: #f8f9fc;
        color: #0d3559;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 12px;
        border-bottom: 3px solid #e3e6f0;
        text-align: left;
    }
    
    table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid #e9ecef;
        color: #4a4a4a;
        font-size: 14px;
        vertical-align: middle;
    }
    
    table tbody tr:hover {
        background: #f8f9fc;
    }
    
    /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 30px;
        text-align: center;
    }
    
    .badge-primary {
        background: #0d3559;
        color: white;
    }
    
    .badge-secondary {
        background: #6c757d;
        color: white;
    }
    
    .badge-success {
        background: #28a745;
        color: white;
    }
    
    .badge-warning {
        background: #ffc107;
        color: #333;
    }
    
    .badge-danger {
        background: #dc3545;
        color: white;
    }
    
    .badge-info {
        background: #17a2b8;
        color: white;
    }
    
    /* Button Actions */
    .btn-group {
        display: flex;
        gap: 5px;
    }
    
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        text-decoration: none;
    }
    
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    
    .btn-info:hover {
        background: #138496;
        color: white;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background: #218838;
        color: white;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 70px;
        color: #d1d3e2;
        margin-bottom: 25px;
    }
    
    .empty-state h4 {
        font-size: 22px;
        color: #5a5c69;
        margin-bottom: 12px;
    }
    
    .empty-state p {
        font-size: 15px;
        color: #858796;
        margin-bottom: 30px;
    }
    
    .btn-empty {
        background: #ff6a00;
        color: white;
        padding: 14px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }
    
    .btn-empty:hover {
        background: #e65c00;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .content {
            margin-left: 260px;
            padding: 30px;
        }
    }
    
    @media (max-width: 768px) {
        .content {
            margin-left: 70px;
            padding: 20px;
        }
        
        .header-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .btn-custom, 
        .btn-secondary-custom {
            width: 100%;
            justify-content: center;
        }
        
        .col-stats {
            min-width: 100%;
        }
        
        table thead {
            display: none;
        }
        
        table tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
        }
        
        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        table tbody td:last-child {
            border-bottom: none;
        }
        
        table tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #0d3559;
            margin-right: 15px;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-action {
            flex: 1;
            height: 40px;
        }
    }
    
    @media (max-width: 576px) {
        .content {
            margin-left: 0;
            padding: 20px;
            margin-top: 70px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="header-wrapper">
        <div class="header-title">
            <h1>Jadwal Saya</h1>
            <p>Kelola jadwal yang telah Anda ambil</p>
        </div>
        <div>
            <a href="{{ route('driver.jadwal.tersedia') ?? '#' }}" class="btn-custom">
                <i class="fas fa-plus"></i>
                Ambil Jadwal Baru
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row-stats">
        <div class="col-stats">
            <div class="stat-card stat-card-primary">
                <div class="stat-info">
                    <div class="stat-label stat-label-primary">Jadwal Bulan Ini</div>
                    <div class="stat-number">{{ $jumlahJadwalBulanIni ?? 0 }}/20</div>
                    <div class="progress-wrapper">
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ min(($jumlahJadwalBulanIni ?? 0) * 5, 100) }}%;"></div>
                        </div>
                        <div class="progress-text">Sisa kuota: {{ $sisaKuota ?? 20 - ($jumlahJadwalBulanIni ?? 0) }} jadwal</div>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-stats">
            <div class="stat-card stat-card-success">
                <div class="stat-info">
                    <div class="stat-label stat-label-success">Jadwal Aktif</div>
                    <div class="stat-number">{{ $jadwalSaya ? $jadwalSaya->where('status', 'aktif')->count() : 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-stats">
            <div class="stat-card stat-card-info">
                <div class="stat-info">
                    <div class="stat-label stat-label-info">Total Jadwal</div>
                    <div class="stat-number">{{ $jadwalSaya ? $jadwalSaya->count() : 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    @endif

    <!-- Tabel Jadwal -->
    <div class="card">
        <div class="card-header">
            <h6>
                <i class="fas fa-calendar-alt"></i>
                Daftar Jadwal Saya
            </h6>
        </div>
        <div class="card-body">
            @if($jadwalSaya && $jadwalSaya->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Jadwal</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Armada</th>
                            <th>Harga</th>
                            <th>Kursi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalSaya as $index => $jadwal)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="ID Jadwal">
                                <span class="badge badge-secondary">{{ $jadwal->id_jadwal_driver ?? 'JD' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td data-label="Rute">
                                <strong>{{ $jadwal->rute ?? 'Jakarta - Bandung' }}</strong>
                            </td>
                            <td data-label="Tanggal">{{ $jadwal->tanggal_formatted ?? date('d/m/Y') }}</td>
                            <td data-label="Waktu">
                                {{ $jadwal->waktu_berangkat_formatted ?? '08:00' }} - 
                                {{ $jadwal->waktu_tiba_formatted ?? '12:00' }}
                            </td>
                            <td data-label="Armada">{{ $jadwal->armada ?? 'Toyota Hiace' }}</td>
                            <td data-label="Harga">
                                <strong>{{ $jadwal->harga_formatted ?? 'Rp 150.000' }}</strong>
                            </td>
                            <td data-label="Kursi">
                                <span class="badge {{ ($jadwal->kursi_terisi ?? 5) >= ($jadwal->total_kursi ?? 12) ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $jadwal->kursi_terisi ?? 5 }}/{{ $jadwal->total_kursi ?? 12 }}
                                </span>
                            </td>
                            <td data-label="Status">
                                @php
                                    $status = $jadwal->status ?? 'aktif';
                                @endphp
                                @if($status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($status == 'selesai')
                                    <span class="badge badge-secondary">Selesai</span>
                                @else
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="btn-group">
                                    <a href="{{ route('driver.jadwal.detail', $jadwal->id_jadwal_driver ?? 1) }}" 
                                       class="btn-action btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(($jadwal->status ?? '') == 'aktif')
                                    <form action="{{ route('driver.jadwal.update-status', $jadwal->id_jadwal_driver ?? 1) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="btn-action btn-success" 
                                                onclick="return confirm('Tandai jadwal sebagai selesai?')"
                                                title="Selesai">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('driver.jadwal.batalkan', $jadwal->id_jadwal_driver ?? 1) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger"
                                                onclick="return confirm('Batalkan jadwal ini?')"
                                                title="Batalkan">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h4>Belum ada jadwal</h4>
                <p>Anda belum mengambil jadwal apapun.</p>
                <a href="{{ route('driver.jadwal.tersedia') ?? '#' }}" class="btn-empty">
                    <i class="fas fa-plus"></i>
                    Ambil Jadwal Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
</script>
@endpush