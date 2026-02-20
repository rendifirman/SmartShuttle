@extends('layouts.app-admin')

@section('title', 'Manajemen Jadwal')

@push('styles')
<style>
    :root {
        --bg-primary: #f8f7f3;
        --bg-secondary: #ffffff;
        --bg-card: #ffffff;
        --text-primary: #0b2a4a;
        --text-secondary: #333333;
        --text-muted: #777777;
        --border-color: #dddddd;
        --shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        --shadow-light: 0 2px 8px rgba(0, 0, 0, 0.05);
        --primary-color: #ff6a00;
        --secondary-color: #1e88e5;
        --success-color: #12b600;
        --warning-color: #f9b000;
        --danger-color: #e74c3c;
        --info-color: #6c757d;
        --status-available: #b8f0a3;
        --status-available-text: #1e7e34;
        --status-full: #ff9a9a;
        --status-full-text: #8b0000;
        --status-almost: #ffd699;
        --status-almost-text: #b35900;
    }

    /* ================= BASE ================= */
    body {
        background: #f4f6fb;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        overflow-x: hidden;
    }

    .page-container {
        padding: 15px;
        min-height: 100vh;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ================= UTILITIES ================= */
    .hidden {
        display: none !important;
    }

    /* ================= PAGE CONTAINER ================= */
    .page-container {
        padding: 20px;
        min-height: 100vh;
    }

    /* ================= HEADER ================= */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h2 {
        font-size: 22px;
        color: var(--text-primary);
        margin: 0;
        font-weight: 700;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* ================= BUTTONS ================= */
    .btn {
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-add {
        background: var(--primary-color);
        color: #fff;
    }

    .btn-add:hover {
        background: #e55c00;
    }

    .btn-edit {
        background: var(--warning-color);
        color: #fff;
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
    }

    .btn-delete {
        background: var(--danger-color);
        color: #fff;
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
    }

    .btn-excel {
        background: var(--success-color);
        color: #fff;
        padding: 8px 18px;
        border-radius: 20px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-pdf {
        background: var(--border-color);
        color: var(--text-secondary);
        padding: 8px 18px;
        border-radius: 20px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-action {
        padding: 6px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        margin-right: 5px;
        transition: all 0.3s;
        font-weight: 600;
    }

    .btn-view {
        background: #888;
        color: #fff;
    }

    .btn-view:hover {
        background: #777;
    }

    .btn-edit {
        background: var(--warning-color);
        color: #fff;
    }

    .btn-edit:hover {
        background: #e09b00;
    }

    /* ================= SUMMARY ================= */
    .summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .summary-card {
        background: var(--bg-card);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: var(--shadow-light);
    }

    .summary-card h3 {
        margin: 0;
        font-size: 24px;
        color: var(--text-primary);
    }

    .summary-card p {
        margin: 5px 0 0;
        color: var(--text-muted);
        font-size: 13px;
    }

    /* ================= IMPROVED FILTER ================= */
    .filter-box {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group label i {
        color: var(--primary-color);
    }

    .filter-box select,
    .filter-box input {
        padding: 12px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        font-size: 14px;
        background: var(--bg-card);
        color: var(--text-secondary);
        width: 100%;
        transition: all 0.2s;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    .filter-box select:focus,
    .filter-box input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.1);
    }

    .filter-box select::-ms-expand {
        display: none;
    }

    /* ================= TABLE ================= */
    .table-wrapper {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 20px;
        box-shadow: var(--shadow);
        overflow-x: auto;
        margin-bottom: 20px;
    }

    .table-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 1100px;
    }

    thead {
        background: rgba(0, 0, 0, 0.05);
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
        font-size: 13px;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        font-size: 13px;
    }

    tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }

    .status-tersedia {
        background: var(--status-available);
        color: var(--status-available-text);
    }

    .status-penuh {
        background: var(--status-full);
        color: var(--status-full-text);
    }

    .status-hampir-penuh {
        background: var(--status-almost);
        color: var(--status-almost-text);
    }

    /* Seat indicator */
    .seat-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
    }

    .seat-indicator .seats {
        color: var(--text-secondary);
    }

    .seat-indicator .total {
        color: var(--text-muted);
    }

    /* ================= MODERN PAGINATION ================= */
    .modern-pagination {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .pagination-info {
        font-size: 13px;
        color: var(--text-muted);
        text-align: center;
        background: #f8f9fa;
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #eaeaea;
    }

    .pagination-container {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        background: white;
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .pagination-btn:hover:not(:disabled):not(.active) {
        background: #f8f9fa;
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pagination-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .pagination-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        background: white;
        color: var(--text-secondary);
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pagination-arrow:hover:not(:disabled) {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pagination-arrow:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .pagination-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        color: var(--text-muted);
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .pagination-btn,
        .pagination-arrow {
            width: 36px;
            height: 36px;
            font-size: 13px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- ================= HEADER ================= -->
    <div class="page-header">
        <h2>Manajemen Jadwal</h2>
        <div class="header-actions">
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-add">
                <i class="fas fa-plus"></i> <span>Tambah Jadwal</span>
            </a>
        </div>
    </div>

    <!-- ================= SUMMARY ================= -->
    <div class="summary">
        <div class="summary-card">
            <h3>{{ $totalJadwal }}</h3>
            <p>Total Jadwal</p>
        </div>
        <div class="summary-card">
            <h3>{{ $tersedia }}</h3>
            <p>Tersedia</p>
        </div>
        <div class="summary-card">
            <h3>{{ $hampirPenuh }}</h3>
            <p>Hampir Penuh</p>
        </div>
        <div class="summary-card">
            <h3>{{ $penuh }}</h3>
            <p>Penuh</p>
        </div>
    </div>

    <!-- ================= IMPROVED FILTER ================= -->
    <div class="filter-box">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" id="filterForm">
            <div class="filter-grid">
                <!-- Rute Filter -->
                <div class="filter-group">
                    <label for="rute_id">
                        <i class="fas fa-route"></i> Rute
                    </label>
                    <select name="rute_id" id="rute_id" class="form-control">
                        <option value="">Semua Rute</option>
                        @foreach($rutes as $rute)
                            <option value="{{ $rute->id }}" {{ request('rute_id') == $rute->id ? 'selected' : '' }}>
                                {{ $rute->nama_rute }} ({{ $rute->kota_asal }} → {{ $rute->kota_tujuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Filter -->
                <div class="filter-group">
                    <label for="tanggal">
                        <i class="far fa-calendar-alt"></i> Tanggal
                    </label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}">
                </div>

                <!-- Status Filter -->
                <div class="filter-group">
                    <label for="status">
                        <i class="fas fa-info-circle"></i> Status
                    </label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="hampir_penuh" {{ request('status') == 'hampir_penuh' ? 'selected' : '' }}>Hampir Penuh</option>
                        <option value="penuh" {{ request('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                    </select>
                </div>

                <!-- Armada Filter -->
                <div class="filter-group">
                    <label for="shuttle_id">
                        <i class="fas fa-bus"></i> Armada
                    </label>
                    <select name="shuttle_id" id="shuttle_id" class="form-control">
                        <option value="">Semua Armada</option>
                        @foreach($shuttles as $shuttle)
                            <option value="{{ $shuttle->id }}" {{ request('shuttle_id') == $shuttle->id ? 'selected' : '' }}>
                                {{ $shuttle->nama_shuttle }} ({{ $shuttle->plat_nomor }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="table-wrapper">
        <div class="table-actions">
            <button class="btn-excel">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn-pdf">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rute</th>
                    <th>Tanggal</th>
                    <th>Armada</th>
                    <th>Waktu Keberangkatan</th>
                    <th>Waktu Kedatangan</th>
                    <th>Driver</th>
                    <th>Harga</th>
                    <th>Kursi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                        @php
                        // Jika jadwal sudah diambil driver dan memiliki DriverJadwal, gunakan data dari driver_jadwals
                        $shuttleCapacity = $jadwal->shuttle->kapasitas_kursi ?? $jadwal->shuttle->total_kursi ?? 0;

                        if (!empty($jadwal->driverJadwal)) {
                            $dj = $jadwal->driverJadwal;
                            $totalKursi = $dj->total_kursi ?? $shuttleCapacity;
                            $kursiTerisi = $dj->kursi_terisi ?? ($totalKursi - $jadwal->kursi_tersedia);
                            // status dari driver jadwal jika tersedia
                            $statusSource = $dj->status ?? $jadwal->status;
                        } else {
                            $totalKursi = $shuttleCapacity;
                            $kursiTerisi = $totalKursi - $jadwal->kursi_tersedia;
                            $statusSource = $jadwal->status;
                        }

                        // Tentukan status tampilan (mengutamakan status dari driver jadwal jika ada)
                        if ($statusSource == 'penuh' || ($totalKursi > 0 && $kursiTerisi >= $totalKursi) || ($jadwal->kursi_tersedia !== null && $jadwal->kursi_tersedia <= 0 && empty($jadwal->driverJadwal))) {
                            $statusTampilan = 'penuh';
                            $statusClass = 'status-penuh';
                        } elseif ($totalKursi > 0 && (($totalKursi - $kursiTerisi) / $totalKursi) <= 0.2) {
                            $statusTampilan = 'hampir penuh';
                            $statusClass = 'status-hampir-penuh';
                        } else {
                            $statusTampilan = 'tersedia';
                            $statusClass = 'status-tersedia';
                        }
                        @endphp
                    <tr>
                        <td>{{ $loop->iteration + ($jadwals->currentPage() - 1) * $jadwals->perPage() }}</td>
                        <td>
                            @if($jadwal->rutes->isNotEmpty())
                                {{ $jadwal->rutes->first()->kota_asal }} → {{ $jadwal->rutes->first()->kota_tujuan }}
                            @else
                                Rute tidak ditemukan
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d M Y') }}</td>
                        <td>{{ $jadwal->shuttle->nama_shuttle ?? '-' }} ({{ $jadwal->shuttle->plat_nomor ?? '-' }})</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->waktu_kedatangan)->format('H:i') }}</td>
                        <td>
                            @if(!empty($jadwal->driverJadwal) && $jadwal->driverJadwal->driver)
                                <span style="font-weight: 600; color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> {{ $jadwal->driverJadwal->driver->name ?? 'Tidak diketahui' }}
                                </span>
                            @elseif($jadwal->driver_id)
                                <span style="font-weight: 600; color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> {{ $jadwal->driver->name ?? 'Tidak diketahui' }}
                                </span>
                            @else
                                <span style="color: var(--text-muted);">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}</td>
                        <td>
                            <div class="seat-indicator">
                                <span class="seats">{{ $kursiTerisi }}</span>
                                <span>/</span>
                                <span class="total">{{ $totalKursi }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($statusTampilan) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.jadwal.penumpang', $jadwal->id) }}" class="btn-action btn-view" title="Lihat Penumpang">
                                    <i class="fas fa-users"></i> Penumpang
                                </a>
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Hapus jadwal ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 40px;">
                            <p style="color: var(--text-muted);">Tidak ada data jadwal.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modern Pagination -->
        @if($jadwals->hasPages())
        <div class="modern-pagination">
            <div class="pagination-info">
                Menampilkan {{ $jadwals->firstItem() ?? 0 }} - {{ $jadwals->lastItem() ?? 0 }} dari {{ $jadwals->total() }} data
            </div>

            <div class="pagination-container">
                {{-- Previous Page Link --}}
                @if($jadwals->onFirstPage())
                    <button class="pagination-arrow" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $jadwals->previousPageUrl() }}" class="pagination-arrow">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $current = $jadwals->currentPage();
                    $last = $jadwals->lastPage();
                    $delta = 2;
                    $left = $current - $delta;
                    $right = $current + $delta + 1;
                    $range = [];
                    $rangeWithDots = [];

                    for ($i = 1; $i <= $last; $i++) {
                        if ($i == 1 || $i == $last || $i >= $left && $i < $right) {
                            $range[] = $i;
                        }
                    }

                    $prev = 0;
                    foreach ($range as $i) {
                        if ($prev) {
                            if ($i - $prev == 2) {
                                $rangeWithDots[] = $prev + 1;
                            } elseif ($i - $prev != 1) {
                                $rangeWithDots[] = '...';
                            }
                        }
                        $rangeWithDots[] = $i;
                        $prev = $i;
                    }
                @endphp

                @foreach ($rangeWithDots as $page)
                    @if (is_string($page))
                        <span class="pagination-dots">...</span>
                    @else
                        <a href="{{ $jadwals->url($page) }}"
                           class="pagination-btn {{ $page == $current ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($jadwals->hasMorePages())
                    <a href="{{ $jadwals->nextPageUrl() }}" class="pagination-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button class="pagination-arrow" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto submit filter when dropdown values change
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const filterInputs = document.querySelectorAll('#rute_id, #tanggal, #status, #shuttle_id');

        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    });
</script>
@endpush
