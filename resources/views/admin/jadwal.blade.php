@extends('layouts.app-admin')

@section('title', 'Master Data - Jadwal')
@section('page-title', 'Jadwal')

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
            text-decoration: none;
        }

        .btn-add:hover {
            background: #e55c00;
        }

        .btn-filter {
            background: var(--secondary-color);
            color: #fff;
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-filter:hover {
            background: #0d6bb7;
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
            transition: all 0.3s;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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

        .btn-delete {
            background: var(--danger-color);
            color: #fff;
        }

        .btn-delete:hover {
            background: #c82333;
        }

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

        .filter-box {
            background: var(--bg-card);
            border-radius: 14px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .filter-top {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-box select,
        .filter-box input {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            background: var(--bg-card);
            color: var(--text-secondary);
        }

        .filter-box select:focus,
        .filter-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.1);
        }

        .filter-bottom {
            display: flex;
            gap: 15px;
        }

        .filter-bottom input {
            flex: 1;
        }

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

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .status-available {
            background: var(--status-available);
            color: var(--status-available-text);
        }

        .status-full {
            background: var(--status-full);
            color: var(--status-full-text);
        }

        .status-almost {
            background: var(--status-almost);
            color: var(--status-almost-text);
        }

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

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination button {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: #000000;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            min-width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .pagination button.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-muted);
            text-align: center;
            width: 100%;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .filter-top {
                grid-template-columns: 1fr;
            }
            
            .filter-bottom {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
<div class="page-container">
    <!-- HEADER -->
    <div class="page-header">
        <h2>Data Jadwal</h2>
        <div class="header-actions">
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-add">
                <i class="fas fa-plus"></i> <span>Tambah Jadwal</span>
            </a>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="summary-card jadwal-total">
            <h3>{{ $totalJadwals }}</h3>
            <p>Total Jadwal</p>
        </div>
        <div class="summary-card jadwal-available">
            <h3>{{ $tersediaJadwals }}</h3>
            <p>Tersedia</p>
        </div>
        <div class="summary-card jadwal-almost">
            <h3>{{ $hampirPenuhJadwals }}</h3>
            <p>Hampir Penuh</p>
        </div>
        <div class="summary-card jadwal-full">
            <h3>{{ $penuhJadwals }}</h3>
            <p>Penuh</p>
        </div>
    </div>

    <!-- FILTER -->
    <div class="filter-box">
        <form id="filterForm" method="GET" action="{{ route('admin.jadwal') }}">
            <div class="filter-top">
                <select id="filter-layanan" name="layanan_id">
                    <option value="">Pilih Layanan</option>
                    @foreach($layanans as $layanan)
                        <option value="{{ $layanan->id_layanan }}" {{ request('layanan_id') == $layanan->id_layanan ? 'selected' : '' }}>
                            {{ $layanan->nama_layanan }}
                        </option>
                    @endforeach
                </select>
                <select id="filter-shuttle" name="shuttle_id">
                    <option value="">Pilih Armada</option>
                    @foreach($shuttles as $shuttle)
                        <option value="{{ $shuttle->id }}" 
                                data-layanan="{{ $shuttle->layanan_id }}"
                                {{ request('shuttle_id') == $shuttle->id ? 'selected' : '' }}>
                            {{ $shuttle->nama_shuttle }} ({{ $shuttle->nomor_polisi }})
                        </option>
                    @endforeach
                </select>
                <input type="date" id="filter-tanggal" name="tanggal" value="{{ request('tanggal') }}">
                <select id="filter-status" name="status">
                    <option value="">Pilih Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="penuh" {{ request('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                    <option value="berangkat" {{ request('status') == 'berangkat' ? 'selected' : '' }}>Berangkat</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="filter-bottom">
                <input type="text" id="search-input" name="search" placeholder="Cari Armada/Layanan" value="{{ request('search') }}">
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
        <div class="table-actions">
            <button class="btn-excel">X | Excel</button>
            <button class="btn-pdf">M | PDF</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Armada</th>
                    <th>Tanggal</th>
                    <th>Waktu Keberangkatan</th>
                    <th>Waktu Kedatangan</th>
                    <th>Harga</th>
                    <th>Kursi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                    @php
                        $kapasitas = $jadwal->shuttle ? $jadwal->shuttle->total_kursi : 0;
                        $kursiTerisi = $kapasitas - $jadwal->kursi_tersedia;
                        
                        // Status badge
                        $statusClass = 'status-available';
                        $statusText = 'Tersedia';
                        
                        if($jadwal->status == 'penuh') {
                            $statusClass = 'status-full';
                            $statusText = 'Penuh';
                        } elseif($jadwal->status == 'berangkat') {
                            $statusClass = 'status-almost';
                            $statusText = 'Berangkat';
                        } elseif($jadwal->status == 'dibatalkan') {
                            $statusClass = 'status-full';
                            $statusText = 'Dibatalkan';
                        } elseif($jadwal->status == 'tersedia' && $jadwal->kursi_tersedia <= ceil($kapasitas * 0.2)) {
                            $statusClass = 'status-almost';
                            $statusText = 'Hampir Penuh';
                        }
                    @endphp
                    <tr>
                        <td>
                            {{ $jadwal->shuttle && $jadwal->shuttle->layanan ? $jadwal->shuttle->layanan->nama_layanan : 'N/A' }}
                        </td>
                        <td>
                            {{ $jadwal->shuttle ? $jadwal->shuttle->nama_shuttle . ' (' . $jadwal->shuttle->nomor_polisi . ')' : 'N/A' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d/m/Y') }}</td>
                        <td>{{ substr($jadwal->waktu_keberangkatan, 0, 5) }}</td>
                        <td>{{ substr($jadwal->waktu_kedatangan, 0, 5) }}</td>
                        <td>Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}</td>
                        <td>
                            <div class="seat-indicator">
                                <span class="seats">{{ $kursiTerisi }}</span>
                                <span>/</span>
                                <span class="total">{{ $kapasitas }}</span>
                            </div>
                        </td>
                        <td><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            Tidak ada data jadwal
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($jadwals->hasPages())
        <div class="pagination">
            <div class="pagination-buttons">
                {{ $jadwals->links('vendor.pagination.custom') }}
            </div>
            <div class="pagination-info">
                Menampilkan {{ $jadwals->firstItem() }} - {{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} data
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter shuttle berdasarkan layanan
    document.getElementById('filter-layanan').addEventListener('change', function() {
        const layananId = this.value;
        const shuttleSelect = document.getElementById('filter-shuttle');
        const allOptions = shuttleSelect.querySelectorAll('option');
        
        if (!layananId) {
            // Show all shuttles
            allOptions.forEach(option => {
                option.style.display = '';
            });
            return;
        }
        
        // Filter shuttles by layanan
        allOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = '';
            } else {
                const shuttleLayanan = option.getAttribute('data-layanan');
                if (shuttleLayanan === layananId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        });
        
        // Reset shuttle selection if filtered out
        const selectedOption = shuttleSelect.options[shuttleSelect.selectedIndex];
        if (selectedOption && selectedOption.style.display === 'none') {
            shuttleSelect.value = '';
        }
    });
    
    // Initialize filter based on current selection
    document.addEventListener('DOMContentLoaded', function() {
        const layananId = document.getElementById('filter-layanan').value;
        if (layananId) {
            document.getElementById('filter-layanan').dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush