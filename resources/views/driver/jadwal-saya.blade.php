@extends('layouts.app-driver')

@section('title', 'Jadwal Saya - Driver')

@push('styles')
{{-- Phosphor Icons --}}
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">

<style>
    /* ==========================================================================
       JADWAL SAYA - DRIVER DASHBOARD
       Minimalist & Clean Design
       ========================================================================== */

    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --success-green: #10b981;
        --danger-red: #ef4444;
        --info-blue: #3b82f6;
        --gray-bg: #f8fafc;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --white: #ffffff;
        --radius-sm: 4px;
    }

    /* Container & Layout */
    .container-fluid {
        width: 100%;
        padding: 0.5rem 1rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin: 0 0 0.25rem 0;
        line-height: 1.2;
    }

    .header-title p {
        font-size: 0.85rem;
        color: var(--gray-text);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.4rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        line-height: 1.3;
        white-space: nowrap;
    }

    .btn i {
        font-size: 0.75rem;
    }

    .btn-primary {
        background: var(--primary-orange);
        color: var(--white);
    }

    .btn-primary:hover {
        background: #e65c00;
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
    }

    /* Statistik Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        border: 1px solid var(--gray-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.15s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        border-color: var(--gray-light);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transform: translateY(-1px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
    }

    .stat-card.primary::before {
        background: var(--primary-dark);
    }

    .stat-card.success::before {
        background: var(--success-green);
    }

    .stat-card.info::before {
        background: var(--info-blue);
    }

    .stat-info {
        flex: 1;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.25rem;
    }

    .stat-label.primary {
        color: var(--primary-dark);
    }

    .stat-label.success {
        color: var(--success-green);
    }

    .stat-label.info {
        color: var(--info-blue);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .stat-icon {
        font-size: 2rem;
        color: var(--gray-border);
        opacity: 0.5;
    }

    /* Progress Bar */
    .progress-wrapper {
        margin-top: 0.25rem;
    }

    .progress {
        background: var(--gray-bg);
        height: 3px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 0.2rem;
    }

    .progress-bar {
        background: var(--primary-dark);
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .progress-text {
        font-size: 0.6rem;
        color: var(--gray-text);
    }

    /* Alerts */
    .alert {
        padding: 0.875rem 1.25rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        position: relative;
        border-left: 3px solid transparent;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: var(--success-green);
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left-color: var(--danger-red);
    }

    .alert i {
        font-size: 0.9rem;
    }

    .alert .btn-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.1rem;
        cursor: pointer;
        opacity: 0.5;
        padding: 0 0.5rem;
    }

    .alert .btn-close:hover {
        opacity: 1;
    }

    /* Card */
    .card {
        background: var(--white);
        border-radius: 8px;
        border: 1px solid var(--gray-border);
        overflow: hidden;
    }

    .card-header {
        padding: 0.875rem 1.25rem;
        background: var(--white);
        border-bottom: 1px solid var(--gray-border);
    }

    .card-header h6 {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .card-header i {
        color: var(--primary-orange);
        font-size: 0.8rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        margin: 0 -0.25rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    table thead th {
        background: var(--gray-bg);
        color: var(--primary-dark);
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 0.6rem 0.4rem;
        border-bottom: 1px solid var(--gray-border);
        text-align: left;
        white-space: nowrap;
    }

    table tbody td {
        padding: 0.6rem 0.4rem;
        border-bottom: 1px solid var(--gray-border);
        color: #1e293b;
        vertical-align: middle;
        white-space: nowrap;
    }

    table tbody tr:hover {
        background: var(--gray-bg);
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.15rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 500;
        border-radius: 20px;
        background: var(--gray-bg);
        color: var(--gray-dark);
        white-space: nowrap;
    }

    .badge-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Action Buttons for Table */
    .table-action-group {
        display: flex;
        gap: 0.15rem;
    }

    .table-action-btn {
        width: 18px;
        height: 18px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-border);
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        background: var(--white);
        color: var(--gray-dark);
        padding: 0;
    }

    .table-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-color: transparent;
    }

    .table-action-btn.info:hover {
        background: var(--info-blue);
        color: var(--white);
    }

    .table-action-btn.success:hover {
        background: var(--success-green);
        color: var(--white);
    }

    .table-action-btn.danger:hover {
        background: var(--danger-red);
        color: var(--white);
    }

    .table-action-btn i {
        font-size: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: var(--gray-border);
        margin-bottom: 0.75rem;
    }

    .empty-state h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        font-size: 0.8rem;
        color: var(--gray-text);
        margin-bottom: 1.25rem;
    }

    .empty-state .btn-group {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Empty State Buttons (lebih kecil) */
    .btn-empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        padding: 0.2rem 0.6rem;
        font-size: 0.68rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        line-height: 1.2;
        white-space: nowrap;
    }

    .btn-empty i {
        font-size: 0.68rem;
    }

    .btn-empty-primary {
        background: var(--primary-orange);
        color: var(--white);
    }

    .btn-empty-primary:hover {
        background: #e65c00;
    }

    .btn-empty-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-empty-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
    }

    /* Utilities */
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .me-2 { margin-right: 0.5rem; }
    .d-flex { display: flex; }
    .align-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .flex-wrap { flex-wrap: wrap; }
    .gap-2 { gap: 1rem; }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .header-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        table thead {
            display: none;
        }

        table tbody tr {
            display: block;
            margin-bottom: 0.75rem;
            border: 1px solid var(--gray-border);
            border-radius: var(--radius-sm);
            padding: 0.75rem;
            background: var(--white);
        }

        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            border-bottom: 1px dashed var(--gray-border);
            white-space: normal;
        }

        table tbody td:last-child {
            border-bottom: none;
        }

        table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .table-action-group {
            width: 100%;
            justify-content: flex-end;
        }

        .empty-state .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .empty-state .btn-group .btn-empty {
            width: 100%;
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
        <div class="header-actions">
            <a href="{{ route('driver.dashboard') }}" class="btn btn-secondary">
                <i class="ph ph-house"></i>
                Dashboard
            </a>
            <a href="{{ route('driver.jadwal.tersedia') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle"></i>
                Ambil Jadwal
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-grid">
        @php
            $jumlahBulanIni = $jumlahJadwalBulanIni ?? 0;
            $sisaKuota = 20 - $jumlahBulanIni;
            $jadwalAktif = $jadwalSaya ? $jadwalSaya->where('status', 'aktif')->count() : 0;
            $totalJadwal = $jadwalSaya ? $jadwalSaya->count() : 0;
        @endphp
        <div class="stat-card primary">
            <div class="stat-info">
                <div class="stat-label primary">Jadwal Bulan Ini</div>
                <div class="stat-number">{{ $jumlahBulanIni }}/20</div>
                <div class="progress-wrapper">
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ min($jumlahBulanIni * 5, 100) }}%;"></div>
                    </div>
                    <div class="progress-text">Sisa: {{ max($sisaKuota, 0) }} jadwal</div>
                </div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-calendar"></i>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-info">
                <div class="stat-label success">Jadwal Aktif</div>
                <div class="stat-number">{{ $jadwalAktif }}</div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-play-circle"></i>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-info">
                <div class="stat-label info">Total Jadwal</div>
                <div class="stat-number">{{ $totalJadwal }}</div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-clipboard-text"></i>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="ph ph-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="ph ph-warning-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    <!-- Tabel Jadwal -->
    <div class="card">
        <div class="card-header">
            <h6>
                <i class="ph ph-calendar"></i>
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
                                <span class="badge badge-secondary">{{ $jadwal->id_jadwal_driver }}</span>
                            </td>
                            <td data-label="Rute">
                                <strong>{{ $jadwal->rute }}</strong>
                            </td>
                            <td data-label="Tanggal">{{ $jadwal->tanggal_formatted }}</td>
                            <td data-label="Waktu">{{ $jadwal->waktu_berangkat_formatted }}</td>
                            <td data-label="Armada">{{ $jadwal->armada }}</td>
                            <td data-label="Harga">
                                <strong>{{ $jadwal->harga_formatted }}</strong>
                            </td>
                            <td data-label="Kursi">
                                <span class="badge {{ $jadwal->kursi_terisi >= $jadwal->total_kursi ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $jadwal->kursi_terisi }}/{{ $jadwal->total_kursi }}
                                </span>
                            </td>
                            <td data-label="Status">
                                @if($jadwal->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($jadwal->status == 'selesai')
                                    <span class="badge badge-secondary">Selesai</span>
                                @else
                                    <span class="badge badge-danger">Batal</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="table-action-group">
                                    <a href="{{ route('driver.jadwal.detail', $jadwal->id_jadwal_driver) }}"
                                       class="table-action-btn info" title="Detail">
                                        <i class="ph ph-eye"></i>
                                    </a>

                                    @if($jadwal->status == 'aktif')
                                    <form action="{{ route('driver.jadwal.update-status', $jadwal->id_jadwal_driver) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="table-action-btn success"
                                                onclick="return confirm('Tandai jadwal sebagai selesai?')"
                                                title="Selesai">
                                            <i class="ph ph-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('driver.jadwal.batalkan', $jadwal->id_jadwal_driver) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action-btn danger"
                                                onclick="return confirm('Batalkan jadwal ini?')"
                                                title="Batalkan">
                                            <i class="ph ph-x"></i>
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
                <i class="ph ph-calendar-blank"></i>
                <h4>Belum Ada Jadwal</h4>
                <p>Anda belum mengambil jadwal apapun.</p>
                <div class="btn-group">
                    <a href="{{ route('driver.dashboard') }}" class="btn-empty btn-empty-secondary">
                        <i class="ph ph-house"></i> Dashboard
                    </a>
                    <a href="{{ route('driver.jadwal.tersedia') }}" class="btn-empty btn-empty-primary">
                        <i class="ph ph-plus-circle"></i> Ambil Jadwal
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    })();
</script>
@endpush
