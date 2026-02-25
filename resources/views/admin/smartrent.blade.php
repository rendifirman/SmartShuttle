<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Smart Rent - Smart Shuttle</title>
    @extends('layouts.app-admin')
    
    <!-- Styles -->
    <style>
        /* ====== VARIABLES ====== */
        :root {
            --primary-color: #ff6a21;
            --primary-light: rgba(255, 106, 33, 0.1);
            --primary-dark: #e55d00;
            --secondary-color: #0d3559;
            --secondary-light: #f8f8f6;
            --text-dark: #374151;
            --text-medium: #6b7280;
            --text-light: #9ca3af;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --success-light: rgba(16, 185, 129, 0.1);
            --warning-color: #f59e0b;
            --warning-light: rgba(245, 158, 11, 0.1);
            --danger-color: #ef4444;
            --danger-light: rgba(239, 68, 68, 0.1);
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 6px 16px rgba(0,0,0,0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s ease;
        }

        /* ====== RESET & BASE ====== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            background: #f6f7fb;
            color: var(--text-dark);
        }

        .main-container {
            padding: 32px;
            min-height: 100vh;
        }

        /* ====== PAGE HEADER ====== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title h4 {
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0 0 4px 0;
        }

        .page-title p {
            font-size: 14px;
            color: var(--text-medium);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* ====== SUMMARY GRID ====== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .summary-card h3 {
            margin: 0 0 6px;
            font-size: 20px;
            color: var(--secondary-color);
        }

        .summary-card p {
            margin: 0;
            font-size: 13px;
        }

        .up { color: var(--success-color); }
        .down { color: var(--danger-color); }

        /* ====== FILTER SECTION ====== */
        .filter-box {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 12px;
        }

        .filter-row input,
        .filter-row select {
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            font-size: 14px;
            background: var(--white);
            transition: var(--transition);
        }

        .filter-row input:focus,
        .filter-row select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 106, 33, 0.1);
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 12px;
        }

        .btn-filter {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            height: 42px;
        }

        .btn-filter:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-reset {
            background: #f3f4f6;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* ====== TABLE SECTION ====== */
        .table-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .table-actions-left {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .table-actions-right {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--white);
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            height: 38px;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
            border: none;
        }

        .btn-excel {
            background: #19b44a;
            color: var(--white);
            border: none;
        }

        .btn-excel:hover {
            background: #148f3b;
        }

        .btn-pdf {
            background: #f40f02;
            color: var(--white);
            border: none;
        }

        .btn-pdf:hover {
            background: #c20c01;
        }

        .btn-info {
            background: #4da3ff;
            color: var(--white);
            border: none;
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            height: 30px;
        }

        .btn-info:hover {
            background: #2d8cff;
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
            height: 30px;
        }

        .table-box {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: var(--secondary-light);
        }

        th, td {
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        tbody tr:hover {
            background: var(--secondary-light);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-warning {
            background: var(--warning-light);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-danger {
            background: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-info {
            background: rgba(77, 163, 255, 0.1);
            color: #4da3ff;
            border: 1px solid rgba(77, 163, 255, 0.2);
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        /* ====== PAGINATION ====== */
        .pagination {
            display: flex;
            gap: 4px;
            margin-top: 20px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: var(--white);
            color: var(--text-dark);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination .page-link:hover {
            background: var(--secondary-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .pagination .page-link.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .pagination .page-link.disabled {
            opacity: 0.5;
            pointer-events: none;
            background: var(--secondary-light);
        }

        .pagination-info {
            margin-top: 12px;
            text-align: center;
            font-size: 12px;
            color: var(--text-medium);
        }

        /* ====== ALERT MESSAGES ====== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
        }

        .alert-success {
            background: var(--success-light);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background: var(--danger-light);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        .alert-warning {
            background: var(--warning-light);
            border: 1px solid var(--warning-color);
            color: var(--warning-color);
        }

        .close-alert {
            cursor: pointer;
            font-size: 18px;
            opacity: 0.7;
            transition: var(--transition);
        }

        .close-alert:hover {
            opacity: 1;
        }

        /* ====== LOADING SPINNER ====== */
        .spinner {
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 1200px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .main-container {
                padding: 20px;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .search-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .table-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .table-actions-left,
            .table-actions-right {
                width: 100%;
                justify-content: stretch;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px 4px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .pagination .page-link {
                min-width: 32px;
                height: 32px;
                font-size: 12px;
            }
        }
    </style>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @section('content')
    <div class="main-container">
        <!-- Session Messages -->
        @if(session('success'))
        <div class="alert alert-success" id="alert-success">
            <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
            <span class="close-alert" onclick="this.parentElement.style.display='none'">&times;</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger" id="alert-error">
            <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
            <span class="close-alert" onclick="this.parentElement.style.display='none'">&times;</span>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-warning" id="alert-info">
            <span><i class="fas fa-info-circle"></i> {{ session('info') }}</span>
            <span class="close-alert" onclick="this.parentElement.style.display='none'">&times;</span>
        </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h4>Smart Rent</h4>
                <p>Kelola semua transaksi smart rent</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.smartrent.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <h3>{{ $totalTodayFormatted ?? 'Rp 0' }}</h3>
                <p>Pendapatan Hari Ini</p>
                @php
                    $revenueClass = isset($revenuePercentage) && $revenuePercentage >= 0 ? 'up' : 'down';
                    $revenueIcon = isset($revenuePercentage) && $revenuePercentage >= 0 ? '↑' : '↓';
                    $revenueValue = isset($revenuePercentage) ? abs($revenuePercentage) : 0;
                @endphp
                <p class="{{ $revenueClass }}">{{ $revenueIcon }} {{ number_format($revenueValue, 1) }}% dari kemarin</p>
            </div>
            <div class="summary-card">
                <h3>{{ $countToday ?? 0 }}</h3>
                <p>Transaksi Hari Ini</p>
                @php
                    $countClass = isset($countPercentage) && $countPercentage >= 0 ? 'up' : 'down';
                    $countIcon = isset($countPercentage) && $countPercentage >= 0 ? '↑' : '↓';
                    $countValue = isset($countPercentage) ? abs($countPercentage) : 0;
                @endphp
                <p class="{{ $countClass }}">{{ $countIcon }} {{ number_format($countValue, 1) }}% dari kemarin</p>
            </div>
            <div class="summary-card">
                <h3>{{ $avgTransactionFormatted ?? 'Rp 0' }}</h3>
                <p>Rata-rata Transaksi</p>
                @php
                    $avgClass = isset($avgPercentage) && $avgPercentage >= 0 ? 'up' : 'down';
                    $avgIcon = isset($avgPercentage) && $avgPercentage >= 0 ? '↑' : '↓';
                    $avgValue = isset($avgPercentage) ? abs($avgPercentage) : 0;
                @endphp
                <p class="{{ $avgClass }}">{{ $avgIcon }} {{ number_format($avgValue, 1) }}% dari kemarin</p>
            </div>
            <div class="summary-card">
                <h3>{{ $failedToday ?? 0 }}</h3>
                <p>Transaksi Gagal</p>
                @php
                    $failedClass = isset($failedPercentage) && $failedPercentage <= 0 ? 'up' : 'down';
                    $failedIcon = isset($failedPercentage) && $failedPercentage <= 0 ? '↓' : '↑';
                    $failedValue = isset($failedPercentage) ? abs($failedPercentage) : 0;
                @endphp
                <p class="{{ $failedClass }}">{{ $failedIcon }} {{ number_format($failedValue, 1) }}% dari kemarin</p>
            </div>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('admin.smartrent.index') }}" id="filter-form">
            <div class="filter-box">
                <div class="filter-row">
                    <select name="service_type" id="filter-service-type">
                        <option value="">Semua Layanan</option>
                        <option value="self_drive" {{ request('service_type') == 'self_drive' ? 'selected' : '' }}>Self Drive</option>
                        <option value="with_driver" {{ request('service_type') == 'with_driver' ? 'selected' : '' }}>Dengan Driver</option>
                    </select>
                    
                    <select name="tanggal" id="filter-tanggal">
                        <option value="">Semua Tanggal</option>
                        <option value="hari_ini" {{ request('tanggal') == 'hari_ini' ? 'selected' : '' }}>Hari ini</option>
                        <option value="kemarin" {{ request('tanggal') == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                        <option value="minggu_ini" {{ request('tanggal') == 'minggu_ini' ? 'selected' : '' }}>Minggu ini</option>
                        <option value="bulan_ini" {{ request('tanggal') == 'bulan_ini' ? 'selected' : '' }}>Bulan ini</option>
                    </select>
                    
                    <select name="status" id="filter-status">
                        <option value="">Semua Status</option>
                        <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="search-row">
                    <input type="text" name="search" placeholder="Cari kode booking, nama pelanggan..." value="{{ request('search') }}">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    @if(request()->anyFilled(['service_type', 'tanggal', 'status', 'search']))
                        <a href="{{ route('admin.smartrent.index') }}" class="btn-reset">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table Actions -->
        <div class="table-actions">
            <div class="table-actions-left">
                <a href="{{ route('admin.smartrent.export.excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-excel">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('admin.smartrent.export.pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
            <div class="table-actions-right">
                <button class="btn" onclick="refreshTable()" id="refresh-btn">
                    <i class="fas fa-sync-alt" id="refresh-icon"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Selesai</th>
                        <th>Durasi</th>
                        <th>Mobil</th>
                        <th>Layanan</th>
                        <th>Metode</th>
                        <th>Total Bayar</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td><strong>{{ $transaction->order_number }}</strong></td>
                        <td>{{ $transaction->customer_name }}</td>
                        <td>{{ $transaction->start_date ? $transaction->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $transaction->end_date ? $transaction->end_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $transaction->duration }} Hari</td>
                        <td>
                            {{ $transaction->vehicle_name }}<br>
                            <small style="color: var(--text-light);">{{ $transaction->vehicle_type ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $transaction->service_type == 'with_driver' ? 'Dengan Driver' : 'Self Drive' }}</td>
                        <td>{{ $transaction->payment_method ?? '-' }}</td>
                        <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td>-</td>
                        <td>
                            @if($transaction->payment_status == 'paid')
                                <span class="badge badge-success">Sukses</span>
                            @elseif($transaction->payment_status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($transaction->payment_status == 'failed' || $transaction->payment_status == 'cancelled')
                                <span class="badge badge-danger">Gagal</span>
                            @else
                                <span class="badge badge-info">{{ ucfirst($transaction->payment_status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.smartrent.show', $transaction->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" style="text-align: center; padding: 40px;">
                            <i class="fas fa-inbox" style="font-size: 48px; color: var(--text-light); margin-bottom: 16px; display: block;"></i>
                            <p style="color: var(--text-medium); margin-bottom: 16px;">Belum ada data transaksi</p>
                            <a href="{{ route('admin.smartrent.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Transaksi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if(isset($transactions) && $transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if($transactions->onFirstPage())
                    <span class="page-link disabled">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @elseif($page >= $transactions->currentPage() - 2 && $page <= $transactions->currentPage() + 2)
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @elseif($page == 1 || $page == $transactions->lastPage())
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @elseif($page == $transactions->currentPage() - 3 || $page == $transactions->currentPage() + 3)
                        <span class="page-link disabled">...</span>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="page-link disabled">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>

            <div class="pagination-info">
                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} 
                dari {{ $transactions->total() }} data
            </div>
            @endif
        </div>
    </div>

    <script>
        function refreshTable() {
            const btn = document.getElementById('refresh-btn');
            const icon = document.getElementById('refresh-icon');
            
            // Disable button and show spinner
            btn.disabled = true;
            icon.className = 'fas fa-spinner fa-spin';
            
            // Reload the page with current filters
            window.location.href = window.location.pathname + window.location.search;
        }

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        });

        // Submit filter form on select change
        document.getElementById('filter-service-type')?.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
        
        document.getElementById('filter-tanggal')?.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
        
        document.getElementById('filter-status')?.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });

        // Highlight active payment method
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
    @endsection
</body>
</html>
