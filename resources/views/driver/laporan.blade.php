@extends('layouts.app-driver')

@section('title', 'Laporan Driver - Smart Shuttle')

@push('styles')
<style>
    /* ==========================================================================
       LAPORAN DRIVER - SMART SHUTTLE
       Theme Match dengan Halaman Bantuan & Jadwal (#0d3559 & #ff6a00)
       ========================================================================== */

    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --success-green: #10b981;
        --success-green-light: rgba(16, 185, 129, 0.1);
        --danger-red: #ef4444;
        --danger-red-light: rgba(239, 68, 68, 0.1);
        --info-blue: #3b82f6;
        --info-blue-light: rgba(59, 130, 246, 0.1);
        --warning-yellow: #f59e0b;
        --warning-yellow-light: rgba(245, 158, 11, 0.1);
        --gray-bg: #f5f7fa;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --gray-dark: #334155;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --transition: all 0.3s ease;
    }

    /* Container & Layout */
    .container-fluid {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION - MATCHING BANTUAN PAGE ===== */
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

    .profile-box {
        background: var(--white);
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary-dark);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }

    .profile-box:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .profile-box i {
        color: var(--primary-orange);
        font-size: 1rem;
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    /* ================= FILTER BAR ================= */
    .filter-bar {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        align-items: flex-end;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .filter-bar:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-orange);
    }

    .filter-bar > div {
        min-width: 180px;
        flex: 1;
    }

    .filter-bar label {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--gray-text);
        margin-bottom: 0.35rem;
        display: block;
    }

    .filter-bar label i {
        color: var(--primary-orange);
        margin-right: 0.35rem;
        font-size: 0.7rem;
    }

    .filter-bar select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        color: var(--gray-dark);
        background: var(--white);
        cursor: pointer;
        transition: var(--transition);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%230d3559' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }

    .filter-bar select:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.1);
    }

    .filter-bar select:hover {
        border-color: var(--primary-orange);
    }

    .filter-btn {
        padding: 0.75rem 2rem;
        background: var(--primary-dark);
        color: var(--white);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 120px;
        position: relative;
        overflow: hidden;
    }

    .filter-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .filter-btn:hover::after {
        width: 200px;
        height: 200px;
    }

    .filter-btn:hover {
        background: var(--primary-orange);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3);
    }

    .filter-btn:active {
        transform: translateY(0);
    }

    /* ================= STATS CARDS ================= */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        text-align: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.25s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        transform: translateY(-4px);
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
        transition: width 0.3s ease;
    }

    .stat-card:hover::before {
        width: 6px;
        opacity: 1;
    }

    .stat-card h5 {
        margin: 0 0 0.75rem 0;
        font-size: 0.8rem;
        color: var(--gray-text);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .stat-card h5 i {
        color: var(--primary-orange);
        font-size: 0.9rem;
    }

    .stat-card .value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        line-height: 1.2;
        transition: var(--transition);
    }

    .stat-card:hover .value {
        color: var(--primary-orange);
        transform: scale(1.05);
    }

    /* ================= TAB MENU ================= */
    .tab-wrapper {
        background: var(--primary-dark);
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .tab-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
    }

    .tab-btn {
        padding: 0.6rem 1.5rem;
        border-radius: 30px;
        border: none;
        background: transparent;
        color: var(--white);
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .tab-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .tab-btn:hover::after {
        width: 100px;
        height: 100px;
    }

    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .tab-active {
        background: var(--primary-orange);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3);
    }

    .tab-active:hover {
        background: var(--primary-orange);
    }

    .download-btn {
        margin-left: auto;
        background: var(--white);
        color: var(--primary-dark);
        border-radius: 30px;
        padding: 0.6rem 1.8rem;
        cursor: pointer;
        border: none;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .download-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 106, 0, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .download-btn:hover::after {
        width: 200px;
        height: 200px;
    }

    .download-btn:hover {
        background: var(--primary-orange);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .download-btn i {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }

    .download-btn:hover i {
        transform: translateY(2px);
    }

    /* ================= TABLE ================= */
    .table-container {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        padding: 1.5rem;
        color: var(--gray-dark);
        transition: var(--transition);
        animation: fadeIn 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .table-container:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-orange);
    }

    .table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .table-title {
        text-align: center;
        margin: 0 0 1.5rem 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .table-title i {
        color: var(--primary-orange);
        font-size: 1.1rem;
        animation: bounce 2s infinite;
    }

    .table-responsive {
        overflow-x: auto;
        margin: 0 -0.25rem;
        border-radius: var(--radius-sm);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: var(--white);
        color: var(--gray-dark);
        border-radius: var(--radius-sm);
        overflow: hidden;
        font-size: 0.85rem;
    }

    th {
        background: var(--primary-dark);
        color: var(--white);
        padding: 0.9rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        white-space: nowrap;
    }

    td {
        padding: 0.9rem 0.8rem;
        font-size: 0.85rem;
        border-bottom: 1px solid var(--gray-border);
        text-align: center;
        white-space: nowrap;
        transition: var(--transition);
    }

    tbody tr {
        transition: var(--transition);
    }

    tbody tr:hover {
        background: var(--primary-orange-light);
    }

    tbody tr:hover td {
        color: var(--primary-dark);
    }

    .status-badge {
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 90px;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .status-selesai {
        background: var(--success-green-light);
        color: var(--success-green);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-proses {
        background: var(--warning-yellow-light);
        color: #92400e;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .status-batal {
        background: var(--danger-red-light);
        color: var(--danger-red);
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* Data yang tersembunyi */
    .data-perjalanan, .data-paket, .data-armada {
        display: none;
    }

    .data-semua {
        display: table-row;
    }

    /* Empty State - MATCHING BANTUAN PAGE */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--gray-text);
        animation: fadeInScale 0.5s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray-border);
        margin-bottom: 1rem;
        animation: float 3s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state p {
        font-size: 1rem;
        color: var(--gray-dark);
        margin-bottom: 1.5rem;
    }

    .empty-state .filter-btn {
        display: inline-flex;
        min-width: auto;
        padding: 0.6rem 1.5rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .page-btn {
        min-width: 36px;
        height: 36px;
        padding: 0 0.5rem;
        border: 1px solid var(--gray-border);
        background: var(--white);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--gray-dark);
    }

    .page-btn:hover {
        background: var(--primary-orange-light);
        border-color: var(--primary-orange);
        color: var(--primary-orange);
        transform: translateY(-2px);
    }

    .page-active {
        background: var(--primary-orange);
        color: var(--white);
        border-color: var(--primary-orange);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .page-active:hover {
        background: var(--primary-orange);
        color: var(--white);
    }

    /* ===== RESPONSIVE MOBILE IMPROVEMENTS ===== */
    @media screen and (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
        }

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

        /* Filter bar mobile */
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
            padding: 1.25rem;
            gap: 1rem;
        }

        .filter-bar > div {
            min-width: 100%;
        }

        .filter-bar select {
            padding: 0.7rem;
            font-size: 0.85rem;
        }

        .filter-btn {
            width: 100%;
            padding: 0.7rem;
        }

        /* Stats cards mobile */
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-card h5 {
            font-size: 0.7rem;
            margin-bottom: 0.5rem;
        }

        .stat-card h5 i {
            font-size: 0.8rem;
        }

        .stat-card .value {
            font-size: 1.5rem;
        }

        /* Tab wrapper mobile */
        .tab-wrapper {
            flex-direction: column;
            align-items: stretch;
            padding: 1rem;
            gap: 0.75rem;
        }

        .tab-btn {
            width: 100%;
            padding: 0.7rem;
            text-align: center;
        }

        .download-btn {
            margin-left: 0;
            width: 100%;
            justify-content: center;
            padding: 0.7rem;
        }

        /* Table mobile */
        .table-container {
            padding: 1rem;
        }

        .table-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .table-title i {
            font-size: 1rem;
        }

        .table-responsive {
            margin: 0;
        }

        /* Table card view untuk mobile */
        table thead {
            display: none;
        }

        table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-border);
            border-radius: var(--radius-md);
            padding: 1rem;
            background: var(--white);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        table tbody tr:hover {
            transform: translateX(0) translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-orange);
        }

        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px dashed var(--gray-border);
            white-space: normal;
            font-size: 0.8rem;
            text-align: left;
        }

        table tbody td:last-child {
            border-bottom: none;
        }

        table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            min-width: 90px;
        }

        .status-badge {
            min-width: auto;
            padding: 0.2rem 0.8rem;
            font-size: 0.65rem;
        }

        /* Pagination mobile */
        .pagination {
            gap: 0.25rem;
        }

        .page-btn {
            min-width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        /* Empty state mobile */
        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state p {
            font-size: 0.9rem;
        }
    }

    @media screen and (max-width: 576px) {
        .stats-container {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem 1.25rem;
        }

        .stat-card .value {
            font-size: 1.8rem;
        }

        table tbody td::before {
            min-width: 80px;
            font-size: 0.65rem;
        }

        .status-badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.6rem;
        }
    }

    @media screen and (max-width: 360px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

        .stat-card .value {
            font-size: 1.5rem;
        }

        table tbody td {
            font-size: 0.75rem;
        }

        table tbody td::before {
            min-width: 70px;
            font-size: 0.6rem;
        }
    }

    /* Landscape mode optimization */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .stats-container {
            grid-template-columns: repeat(4, 1fr);
        }

        .tab-wrapper {
            flex-direction: row;
        }

        .tab-btn {
            width: auto;
        }

        .download-btn {
            width: auto;
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .container-fluid {
            padding: 1rem;
        }

        .stats-container {
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-card .value {
            font-size: 1.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- HEADER SECTION - MATCHING BANTUAN PAGE -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-file-alt menu-icon"></i>
            Laporan Driver
        </h1>
    </div>

    <div class="divider"></div>

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('driver.laporan') }}" class="filter-bar">
        <div>
            <label for="bulan"><i class="fas fa-calendar"></i> Bulan</label>
            <select name="bulan" id="bulan">
                @foreach($availableMonths as $month)
                    <option value="{{ $month['bulan'] }}" {{ $bulan == $month['bulan'] ? 'selected' : '' }}>
                        {{ $month['label'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tahun"><i class="fas fa-calendar-alt"></i> Tahun</label>
            <select name="tahun" id="tahun">
                @php
                    $years = range(date('Y'), date('Y') - 5);
                @endphp
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="filter-btn">
            <i class="fas fa-filter"></i> Terapkan Filter
        </button>
    </form>

    <!-- STATS CARDS -->
    <div class="stats-container">
        <div class="stat-card">
            <h5><i class="fas fa-bus"></i> Total Perjalanan</h5>
            <div class="value">{{ $totalPerjalanan ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h5><i class="fas fa-users"></i> Total Penumpang</h5>
            <div class="value">{{ $totalPenumpang ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h5><i class="fas fa-box"></i> Total Paket</h5>
            <div class="value">{{ $totalPaket ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h5><i class="fas fa-check-circle"></i> Selesai</h5>
            <div class="value">{{ $totalSelesai ?? 0 }}</div>
        </div>
    </div>

    <!-- TAB MENU -->
    <div class="tab-wrapper">
        <button class="tab-btn tab-active" data-filter="semua">
            <i class="fas fa-th-large"></i> Semua
        </button>
        <button class="tab-btn" data-filter="perjalanan">
            <i class="fas fa-bus"></i> Perjalanan
        </button>
        <button class="tab-btn" data-filter="paket">
            <i class="fas fa-box"></i> Paket
        </button>
        <button class="tab-btn" data-filter="armada">
            <i class="fas fa-truck"></i> Armada
        </button>
        <button class="download-btn" onclick="downloadLaporan()">
            <i class="fas fa-download"></i> Unduh Laporan
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <h4 class="table-title">
            <i class="fas fa-file-alt"></i>
            DAFTAR LAPORAN PERJALANAN
        </h4>

        @if(count($laporanData) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Rute</th>
                        <th>Penumpang</th>
                        <th>Paket</th>
                        <th>Armada</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanData as $index => $data)
                    <tr class="data-semua data-{{ $data['kategori'] }}">
                        <td data-label="No">{{ $index + 1 }}.</td>
                        <td data-label="Tanggal">{{ $data['tanggal'] }}</td>
                        <td data-label="Rute">{{ $data['rute'] }}</td>
                        <td data-label="Penumpang">{{ $data['penumpang'] }}</td>
                        <td data-label="Paket">{{ $data['paket'] }}</td>
                        <td data-label="Armada">{{ $data['armada'] }}</td>
                        <td data-label="Status">
                            @if($data['status_raw'] == 'selesai')
                                <span class="status-badge status-selesai">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            @elseif($data['status_raw'] == 'aktif' || $data['status_raw'] == 'dalam_perjalanan')
                                <span class="status-badge status-proses">
                                    <i class="fas fa-spinner fa-spin"></i> Dalam Proses
                                </span>
                            @elseif($data['status_raw'] == 'dibatalkan')
                                <span class="status-badge status-batal">
                                    <i class="fas fa-times-circle"></i> Dibatalkan
                                </span>
                            @else
                                <span class="status-badge">{{ $data['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="page-btn page-active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Tidak ada data laporan untuk periode yang dipilih.</p>
            <button class="filter-btn" onclick="window.location.href='{{ route('driver.laporan') }}'">
                <i class="fas fa-sync-alt"></i> Reset Filter
            </button>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        console.log('Laporan Driver loaded');

        // Tab functionality dengan filter
        const tabButtons = document.querySelectorAll('.tab-btn:not(.download-btn)');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Hapus class active dari semua tab
                tabButtons.forEach(btn => btn.classList.remove('tab-active'));

                // Tambah class active ke tab yang diklik
                this.classList.add('tab-active');

                // Dapatkan filter value
                const filter = this.getAttribute('data-filter');

                // Sembunyikan semua data
                const allData = document.querySelectorAll('tbody tr');
                allData.forEach(row => {
                    row.style.display = 'none';
                });

                // Tampilkan data berdasarkan filter
                if (filter === 'semua') {
                    // Tampilkan semua data
                    allData.forEach(row => {
                        row.style.display = 'table-row';
                    });
                } else {
                    // Tampilkan data sesuai kategori
                    const filteredData = document.querySelectorAll(`.data-${filter}`);
                    filteredData.forEach(row => {
                        row.style.display = 'table-row';
                    });
                }

                // Animasi untuk row yang muncul
                const visibleRows = document.querySelectorAll('tbody tr[style="display: table-row;"]');
                visibleRows.forEach((row, index) => {
                    row.style.animation = `fadeIn 0.3s ease ${index * 0.05}s both`;
                });
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                if (alert) {
                    alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(20px)';
                    setTimeout(() => alert.remove(), 400);
                }
            });
        }, 5000);

        // Add hover effect for table rows
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.2s ease';
            });
        });

        // Animate stat numbers on page load
        const statNumbers = document.querySelectorAll('.stat-card .value');
        statNumbers.forEach(number => {
            const finalValue = number.innerText;
            let start = 0;
            const end = parseInt(finalValue);
            if (isNaN(end)) return;

            const duration = 1000;
            const increment = end / (duration / 16);

            const timer = setInterval(() => {
                start += increment;
                if (start >= end) {
                    number.innerText = finalValue;
                    clearInterval(timer);
                } else {
                    number.innerText = Math.floor(start);
                }
            }, 16);
        });
    });

    // Download function with improvement
    function downloadLaporan() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const bulanName = document.getElementById('bulan').options[document.getElementById('bulan').selectedIndex].text;

        // Get active tab
        const activeTab = document.querySelector('.tab-btn.tab-active');
        const activeFilter = activeTab ? activeTab.getAttribute('data-filter') : 'semua';
        const filterName = activeTab ? activeTab.innerText.trim() : 'Semua';

        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += `Laporan Driver - ${bulanName} ${tahun} (Filter: ${filterName})\n`;
        csvContent += "No,Tanggal,Rute,Penumpang,Paket,Armada,Status\n";

        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach((row, index) => {
            // Check if row is visible (based on current filter)
            const isVisible = window.getComputedStyle(row).display !== 'none';

            if (isVisible) {
                visibleCount++;
                const cells = row.querySelectorAll('td');
                let rowData = [];

                // Skip the first cell (No) and get all cell values
                for (let i = 1; i < cells.length; i++) {
                    let cellText = cells[i].innerText.replace(/\n/g, ' ').trim();

                    // Remove icons and extra spaces
                    cellText = cellText.replace(/[^\x20-\x7E]/g, '').replace(/\s+/g, ' ').trim();

                    // Wrap in quotes if contains comma
                    if (cellText.includes(',')) {
                        cellText = `"${cellText}"`;
                    }
                    rowData.push(cellText);
                }

                // Add row number
                csvContent += visibleCount + "," + rowData.join(",") + "\n";
            }
        });

        // Add summary
        csvContent += `\nTotal Data,${visibleCount} baris`;

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `laporan_driver_${bulan}_${tahun}_${activeFilter}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Show success message (optional)
        alert(`Laporan berhasil diunduh dengan ${visibleCount} data.`);
    }

    // Pagination functionality (simulasi)
    document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.classList.contains('page-active') && !this.querySelector('.fa-chevron-left') && !this.querySelector('.fa-chevron-right')) {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('page-active'));
                this.classList.add('page-active');

                // Scroll to top of table
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
@endpush