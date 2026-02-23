@extends('layouts.app-admin')

@section('title', 'Master Data - Jadwal')
@section('page-title', 'Jadwal')

@push('styles')
    <style>
        :root {
            /* Light Mode Variables */
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
            background: #0d6bb7;
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
            background: #e55c00;
        }

        .btn-back {
            background: var(--info-color);
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .btn-edit-schedule {
            background: var(--info-color);
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

        .btn-save {
            background: var(--text-primary);
            color: #fff;
        }

        .btn-save:hover {
            background: #1a3a5f;
        }

        .btn-reset {
            background: var(--secondary-color);
            color: #fff;
        }

        .btn-reset:hover {
            background: #e55c00;
        }

        .btn-cancel {
            background: var(--info-color);
            color: #fff;
        }

        .btn-cancel:hover {
            background: #5a6268;
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

        /* ================= FILTER ================= */
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
            box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
        }

        .filter-bottom {
            display: flex;
            gap: 15px;
        }

        .filter-bottom input {
            flex: 1;
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

        /* ================= PAGINATION ================= */
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

        /* ================= FORM CARD ================= */
        .form-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .form-card h3 {
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 12px;
            font-size: 20px;
            color: var(--text-primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            transition: border-color 0.3s;
            background: var(--bg-card);
            color: var(--text-secondary);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
        }

        textarea {
            resize: none;
            min-height: 80px;
        }

        /* Time Row */
        .time-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 10px;
            align-items: center;
        }

        .time-separator {
            text-align: center;
            font-weight: bold;
            color: var(--text-muted);
        }

        .time-row small {
            display: block;
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 12px;
        }

        /* Price input */
        .price-input {
            position: relative;
        }

        .price-input span {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .price-input input {
            padding-left: 40px;
        }

        /* Seat slider */
        .seat-slider-container {
            padding: 10px 0;
        }

        .seat-slider {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            outline: none;
            -webkit-appearance: none;
            margin: 15px 0;
        }

        .seat-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
        }

        .seat-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: none;
        }

        .seat-info {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ================= FORM ACTIONS ================= */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        /* ================= DETAIL CARD ================= */
        .detail-container {
            display: grid;
            gap: 20px;
            max-width: 1200px;
        }

        .detail-card {
            background: var(--bg-card);
            border-radius: 14px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .detail-title {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 8px;
            color: var(--text-primary);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .detail-item label {
            font-size: 12px;
            color: var(--text-muted);
            display: block;
            margin-bottom: 5px;
        }

        .detail-item span {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            display: block;
            word-break: break-word;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .filter-top {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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

            .form-actions {
                flex-direction: column;
            }

            .time-row {
                grid-template-columns: 1fr;
            }

            .time-separator {
                display: none;
            }

            .btn-save,
            .btn-reset,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .table-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-excel, .btn-pdf {
                width: 100%;
                justify-content: center;
            }

            .pagination {
                flex-direction: column;
                gap: 10px;
            }

            .pagination-buttons {
                width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 10px;
                -webkit-overflow-scrolling: touch;
            }

            .pagination-info {
                margin-top: 5px;
                order: 3;
            }
        }

        @media (max-width: 576px) {
            .page-container {
                padding: 15px;
            }

            .summary {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 20px;
            }

            .btn-action {
                padding: 5px 10px;
                font-size: 11px;
                margin-bottom: 5px;
                margin-right: 3px;
            }

            .pagination-buttons {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 10px;
                gap: 4px;
                width: 100%;
                -webkit-overflow-scrolling: touch;
            }

            .pagination button {
                padding: 6px 10px;
                min-width: 32px;
                font-size: 12px;
                flex-shrink: 0;
            }
        }

        @media (max-width: 400px) {
            .page-header h2 {
                font-size: 18px;
            }

            .btn {
                padding: 10px 15px;
                font-size: 13px;
            }

            .btn-add span {
                display: none;
            }

            .btn-add i {
                margin-right: 0;
            }

            .detail-card {
                padding: 15px;
            }

            .filter-box {
                padding: 15px;
            }

            .pagination-buttons {
                gap: 3px;
            }

            .pagination button {
                padding: 5px 8px;
                min-width: 28px;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
<div class="page-container">
    <!-- ================= LIST PAGE ================= -->
    <div id="list-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Data Jadwal</h2>
            <div class="header-actions">
                @can('manage_jadwal')
                <button class="btn btn-add" onclick="showAddForm()">
                    <i class="fas fa-plus"></i> <span>Tambah Jadwal</span>
                </button>
                @endcan
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-card jadwal-total">
                <h3>14</h3>
                <p>Total Jadwal</p>
            </div>
            <div class="summary-card jadwal-available">
                <h3>10</h3>
                <p>Tersedia</p>
            </div>
            <div class="summary-card jadwal-almost">
                <h3>8</h3>
                <p>Hampir Penuh</p>
            </div>
            <div class="summary-card jadwal-full">
                <h3>2</h3>
                <p>Penuh</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <div class="filter-top">
                <select id="filter-rute">
                    <option value="">Pilih Rute</option>
                    <option value="bandung-jakarta">Bandung - Jakarta</option>
                    <option value="jakarta-bandung">Jakarta - Bandung</option>
                    <option value="bandung-surabaya">Bandung - Surabaya</option>
                    <option value="surabaya-bandung">Surabaya - Bandung</option>
                    <option value="jakarta-surabaya">Jakarta - Surabaya</option>
                </select>
                <input type="date" id="filter-tanggal">
                <select id="filter-status">
                    <option value="">Pilih Status</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="hampir">Hampir Penuh</option>
                    <option value="penuh">Penuh</option>
                </select>
            </div>
            <div class="filter-bottom">
                <input type="text" id="search-input" placeholder="Cari Rute/Kapasitas">
                <button class="btn-filter" onclick="applyFilter()">Filter</button>
            </div>
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
                        <th>Rute</th>
                        <th>Tanggal</th>
                        <th>Armada</th>
                        <th>Waktu Keberangkatan</th>
                        <th>Waktu Kedatangan</th>
                        <th>Harga</th>
                        <th>Kursi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="jadwal-table-body">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>

            <div class="pagination">
                <div class="pagination-buttons">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                </div>
                <span class="pagination-info">Menampilkan 1-10 dari 14 data</span>
            </div>
        </div>
    </div>

    <!-- ================= FORM TAMBAH/EDIT JADWAL ================= -->
    <div id="form-page" class="hidden">
        <button class="btn btn-back" onclick="showList()">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
        </button>

        <div class="form-card">
            <h3 id="form-title">Tambah Jadwal Perjalanan</h3>

            <form id="jadwalForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="rute">Rute <span style="color: red">*</span></label>
                        <select id="rute" required>
                            <option value="">-- Pilih Rute --</option>
                            <option value="bandung-jakarta">Bandung - Jakarta</option>
                            <option value="jakarta-bandung">Jakarta - Bandung</option>
                            <option value="bandung-surabaya">Bandung - Surabaya</option>
                            <option value="surabaya-bandung">Surabaya - Bandung</option>
                            <option value="jakarta-surabaya">Jakarta - Surabaya</option>
                            <option value="jakarta-yogyakarta">Jakarta - Yogyakarta</option>
                            <option value="yogyakarta-jakarta">Yogyakarta - Jakarta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal <span style="color: red">*</span></label>
                        <input type="date" id="tanggal" required>
                    </div>
                   <div class="form-group">
    <label for="armada">Armada <span style="color: red">*</span></label>
    <select id="armada" required>
        <option value="">-- Pilih Armada --</option>
        <option value="HIACE-001">HIACE-001 (Executive Class - 10 Seat)</option>
        <option value="HIACE-002">HIACE-002 (Business Class - 8 Seat)</option>
        <option value="HIACE-003">HIACE-003 (Economy Class - 12 Seat)</option>
        <option value="HIACE-004">HIACE-004 (Executive Class - 10 Seat)</option>
        <option value="HIACE-005">HIACE-005 (Business Class - 8 Seat)</option>
    </select>
</div>

                <div class="form-group">
                    <label>Waktu Perjalanan <span style="color: red">*</span></label>
                    <div class="time-row">
                        <div>
                            <input type="time" id="waktuBerangkat" required>
                            <small>Waktu Keberangkatan</small>
                        </div>
                        <div class="time-separator">-</div>
                        <div>
                            <input type="time" id="waktuTiba" required>
                            <small>Waktu Kedatangan</small>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="harga">Harga Tiket <span style="color: red">*</span></label>
                        <div class="price-input">
                            <span>Rp</span>
                            <input type="number" id="harga" placeholder="Contoh: 150000" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="totalKursi">Total Kursi</label>
                        <div class="seat-slider-container">
                            <input type="range" id="totalKursi" class="seat-slider" min="10" max="60" value="40">
                            <div class="seat-info">
                                <span id="seatMin">10</span>
                                <span id="seatValue">40 kursi</span>
                                <span id="seatMax">60</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kursiTerisi">Kursi Terisi</label>
                        <input type="number" id="kursiTerisi" placeholder="Kursi yang sudah terisi" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" rows="3" placeholder="Tambahkan keterangan jadwal (opsional)"></textarea>
                </div>

                <div class="form-actions">
                    <button class="btn btn-save" type="submit">
                        <i class="fas fa-save"></i> Simpan Jadwal
                    </button>
                    <button class="btn btn-reset" type="reset" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                    <button class="btn btn-cancel" type="button" onclick="showList()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= DETAIL PAGE ================= -->
    <div id="detail-page" class="hidden">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <button class="btn btn-back" onclick="showList()">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
            </button>
            <button class="btn btn-edit-schedule" onclick="editCurrentJadwal()">
                <i class="fas fa-edit"></i> Edit Jadwal
            </button>
        </div>

        <div class="detail-container">
            <!-- DATA JADWAL -->
            <div class="detail-card">
                <div class="detail-title">Data Jadwal</div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Rute</label><span id="detail-rute">Bandung - Jakarta</span></div>
                    <div class="detail-item"><label>Tanggal</label><span id="detail-tanggal">15 Januari 2024</span></div>
                    <div class="detail-item"><label>Armada</label><span id="detail-armada">BUS-001 (Executive Class)</span></div>
                    <div class="detail-item"><label>Status</label><span id="detail-status">Tersedia</span></div>
                </div>
            </div>

            <!-- WAKTU PERJALANAN -->
            <div class="detail-card">
                <div class="detail-title">Waktu Perjalanan</div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Waktu Keberangkatan</label>
                        <span id="detail-waktuBerangkat">06:00</span>
                    </div>
                    <div class="detail-item">
                        <label>Waktu Kedatangan</label>
                        <span id="detail-waktuTiba">09:00</span>
                    </div>
                    <div class="detail-item">
                        <label>Durasi</label>
                        <span id="detail-durasi">3 jam</span>
                    </div>
                </div>
            </div>

            <!-- INFORMASI KURSI -->
            <div class="detail-card">
                <div class="detail-title">Informasi Kursi</div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Total Kursi</label>
                        <span id="detail-totalKursi">40</span>
                    </div>
                    <div class="detail-item">
                        <label>Kursi Terisi</label>
                        <span id="detail-kursiTerisi">15</span>
                    </div>
                    <div class="detail-item">
                        <label>Kursi Kosong</label>
                        <span id="detail-kursiKosong">25</span>
                    </div>
                    <div class="detail-item">
                        <label>Tingkat Kepenuhan</label>
                        <span id="detail-penuh">37.5%</span>
                    </div>
                </div>
            </div>

            <!-- HARGA DAN INFORMASI LAIN -->
            <div class="detail-card">
                <div class="detail-title">Harga dan Informasi Lain</div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Harga Tiket</label>
                        <span id="detail-harga">Rp 150.000</span>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>Keterangan</label>
                        <span id="detail-keterangan">Jadwal reguler Bandung - Jakarta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
 // Di bagian const jadwalData, ubah semua "BUS-" menjadi "HIACE-"
const jadwalData = [
    {
        id: 'J001',
        rute: 'Bandung - Jakarta',
        tanggal: '2024-01-15',
        armada: 'HIACE-001',  // Ubah di sini
        armadaDetail: 'Executive Class - 10 Seat',  // Ubah jumlah seat
        waktuBerangkat: '06:00',
        waktuTiba: '09:00',
        harga: 150000,
        totalKursi: 10,  // Ubah jumlah kursi
        kursiTerisi: 3,
        keterangan: 'Jadwal reguler Bandung - Jakarta',
        status: 'tersedia'
    },
    {
        id: 'J002',
        rute: 'Jakarta - Bandung',
        tanggal: '2024-01-15',
        armada: 'HIACE-002',  // Ubah di sini
        armadaDetail: 'Business Class - 8 Seat',  // Ubah jumlah seat
        waktuBerangkat: '10:00',
        waktuTiba: '13:00',
        harga: 175000,
        totalKursi: 8,  // Ubah jumlah kursi
        kursiTerisi: 6,
        keterangan: 'Jadwal siang Jakarta - Bandung',
        status: 'hampir'
    },
    {
        id: 'J003',
        rute: 'Bandung - Surabaya',
        tanggal: '2024-01-15',
        armada: 'HIACE-003',  // Ubah di sini
        armadaDetail: 'Economy Class - 12 Seat',  // Ubah jumlah seat
        waktuBerangkat: '14:00',
        waktuTiba: '22:00',
        harga: 250000,
        totalKursi: 12,  // Ubah jumlah kursi
        kursiTerisi: 12,
        keterangan: 'Jadwal malam Bandung - Surabaya',
        status: 'penuh'
    },
    {
        id: 'J004',
        rute: 'Surabaya - Bandung',
        tanggal: '2024-01-16',
        armada: 'HIACE-004',  // Ubah di sini
        armadaDetail: 'Executive Class - 10 Seat',  // Ubah jumlah seat
        waktuBerangkat: '08:00',
        waktuTiba: '16:00',
        harga: 240000,
        totalKursi: 10,  // Ubah jumlah kursi
        kursiTerisi: 2,
        keterangan: '',
        status: 'tersedia'
    },
    {
        id: 'J005',
        rute: 'Jakarta - Surabaya',
        tanggal: '2024-01-16',
        armada: 'HIACE-005',  // Ubah di sini
        armadaDetail: 'Business Class - 8 Seat',  // Ubah jumlah seat
        waktuBerangkat: '20:00',
        waktuTiba: '04:00',
        harga: 300000,
        totalKursi: 8,  // Ubah jumlah kursi
        kursiTerisi: 7,
        keterangan: 'Jadwal malam dengan fasilitas lengkap',
        status: 'hampir'
    }
];

    // Mode form (tambah/edit)
    let formMode = 'add';
    let currentJadwalIndex = -1;
    let currentJadwalForDetail = null;

    // Fungsi untuk menghitung status berdasarkan kursi
    function calculateStatus(kursiTerisi, totalKursi) {
        const persentase = (kursiTerisi / totalKursi) * 100;
        if (persentase >= 100) return 'penuh';
        if (persentase >= 80) return 'hampir';
        return 'tersedia';
    }

    // Fungsi untuk mendapatkan teks status
    function getStatusText(status) {
        const statusMap = {
            'tersedia': 'Tersedia',
            'hampir': 'Hampir Penuh',
            'penuh': 'Penuh'
        };
        return statusMap[status] || 'Tidak Diketahui';
    }

    // Fungsi untuk mendapatkan class status
    function getStatusClass(status) {
        const classMap = {
            'tersedia': 'status-available',
            'hampir': 'status-almost',
            'penuh': 'status-full'
        };
        return classMap[status] || '';
    }

    // Fungsi untuk format harga
    function formatHarga(harga) {
        return 'Rp ' + harga.toLocaleString('id-ID');
    }

    // Fungsi untuk format tanggal
    function formatTanggal(tanggal) {
        const date = new Date(tanggal);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // Fungsi untuk menghitung durasi
    function calculateDurasi(waktuBerangkat, waktuTiba) {
        const [jam1, menit1] = waktuBerangkat.split(':').map(Number);
        const [jam2, menit2] = waktuTiba.split(':').map(Number);

        let totalMenit1 = jam1 * 60 + menit1;
        let totalMenit2 = jam2 * 60 + menit2;

        // Jika waktu tiba lebih kecil dari waktu berangkat, berarti melewati tengah malam
        if (totalMenit2 < totalMenit1) {
            totalMenit2 += 24 * 60; // Tambah 24 jam
        }

        const durasiMenit = totalMenit2 - totalMenit1;
        const jam = Math.floor(durasiMenit / 60);
        const menit = durasiMenit % 60;

        if (menit === 0) {
            return `${jam} jam`;
        }
        return `${jam} jam ${menit} menit`;
    }

    // Fungsi untuk render tabel jadwal
    function renderJadwalTable(data = jadwalData) {
        const tbody = document.getElementById('jadwal-table-body');
        tbody.innerHTML = '';

        data.forEach((jadwal, index) => {
            const kursiKosong = jadwal.totalKursi - jadwal.kursiTerisi;
            const status = calculateStatus(jadwal.kursiTerisi, jadwal.totalKursi);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${jadwal.rute}</td>
                <td>${formatTanggal(jadwal.tanggal)}</td>
                <td>${jadwal.armada}</td>
                <td>${jadwal.waktuBerangkat}</td>
                <td>${jadwal.waktuTiba}</td>
                <td>${formatHarga(jadwal.harga)}</td>
                <td>
                    <div class="seat-indicator">
                        <span class="seats">${jadwal.kursiTerisi}</span>
                        <span>/</span>
                        <span class="total">${jadwal.totalKursi}</span>
                    </div>
                </td>
                <td><span class="status-badge ${getStatusClass(status)}">${getStatusText(status)}</span></td>
                <td>
                    <button class="btn-action btn-view" onclick="showDetail(${index})">View</button>
                    <button class="btn-action btn-edit" onclick="showEditForm(${index})">Edit</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Fungsi untuk menampilkan halaman list
    function showList() {
        document.getElementById('list-page').classList.remove('hidden');
        document.getElementById('form-page').classList.add('hidden');
        document.getElementById('detail-page').classList.add('hidden');
        renderJadwalTable();
        window.scrollTo(0, 0);
    }

    // Fungsi untuk menampilkan form tambah
    function showAddForm() {
        formMode = 'add';
        document.getElementById('form-title').textContent = 'Tambah Jadwal Perjalanan';

        // Reset form
        resetForm();

        // Set tanggal default (besok)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('tanggal').value = tomorrow.toISOString().split('T')[0];

        document.getElementById('list-page').classList.add('hidden');
        document.getElementById('form-page').classList.remove('hidden');
        document.getElementById('detail-page').classList.add('hidden');
        window.scrollTo(0, 0);
    }

    // Fungsi untuk menampilkan form edit
    function showEditForm(index) {
        formMode = 'edit';
        currentJadwalIndex = index;

        const jadwal = jadwalData[index];
        if (!jadwal) {
            alert('Data jadwal tidak ditemukan!');
            return;
        }

        document.getElementById('form-title').textContent = 'Edit Jadwal Perjalanan';

        // Isi form dengan data
        document.getElementById('rute').value = jadwal.rute;
        document.getElementById('tanggal').value = jadwal.tanggal;
        document.getElementById('armada').value = jadwal.armada;
        document.getElementById('waktuBerangkat').value = jadwal.waktuBerangkat;
        document.getElementById('waktuTiba').value = jadwal.waktuTiba;
        document.getElementById('harga').value = jadwal.harga;
        document.getElementById('totalKursi').value = jadwal.totalKursi;
        document.getElementById('seatValue').textContent = jadwal.totalKursi + ' kursi';
        document.getElementById('kursiTerisi').value = jadwal.kursiTerisi;
        document.getElementById('keterangan').value = jadwal.keterangan || '';

        document.getElementById('list-page').classList.add('hidden');
        document.getElementById('form-page').classList.remove('hidden');
        document.getElementById('detail-page').classList.add('hidden');
        window.scrollTo(0, 0);
    }

    // Fungsi untuk menampilkan detail
    function showDetail(index) {
        const jadwal = jadwalData[index];
        if (!jadwal) {
            alert('Data jadwal tidak ditemukan!');
            return;
        }

        currentJadwalForDetail = index;

        // Hitung nilai yang diperlukan
        const kursiKosong = jadwal.totalKursi - jadwal.kursiTerisi;
        const persentase = (jadwal.kursiTerisi / jadwal.totalKursi) * 100;
        const durasi = calculateDurasi(jadwal.waktuBerangkat, jadwal.waktuTiba);
        const status = calculateStatus(jadwal.kursiTerisi, jadwal.totalKursi);

        // Isi detail dengan data
        document.getElementById('detail-rute').textContent = jadwal.rute;
        document.getElementById('detail-tanggal').textContent = formatTanggal(jadwal.tanggal);
        document.getElementById('detail-armada').textContent = jadwal.armada + ' (' + jadwal.armadaDetail.split(' - ')[0] + ')';
        document.getElementById('detail-status').textContent = getStatusText(status);
        document.getElementById('detail-waktuBerangkat').textContent = jadwal.waktuBerangkat;
        document.getElementById('detail-waktuTiba').textContent = jadwal.waktuTiba;
        document.getElementById('detail-durasi').textContent = durasi;
        document.getElementById('detail-totalKursi').textContent = jadwal.totalKursi;
        document.getElementById('detail-kursiTerisi').textContent = jadwal.kursiTerisi;
        document.getElementById('detail-kursiKosong').textContent = kursiKosong;
        document.getElementById('detail-penuh').textContent = persentase.toFixed(1) + '%';
        document.getElementById('detail-harga').textContent = formatHarga(jadwal.harga);
        document.getElementById('detail-keterangan').textContent = jadwal.keterangan || '-';

        document.getElementById('list-page').classList.add('hidden');
        document.getElementById('form-page').classList.add('hidden');
        document.getElementById('detail-page').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    // Fungsi untuk edit dari halaman detail
    function editCurrentJadwal() {
        if (currentJadwalForDetail !== null) {
            showEditForm(currentJadwalForDetail);
        }
    }

    // Fungsi untuk reset form
    function resetForm() {
        document.getElementById('jadwalForm').reset();

        // Set nilai default untuk slider kursi
        const seatSlider = document.getElementById('totalKursi');
        document.getElementById('seatValue').textContent = seatSlider.value + ' kursi';

        // Set waktu default
        document.getElementById('waktuBerangkat').value = '06:00';
        document.getElementById('waktuTiba').value = '09:00';

        // Set tanggal default (besok)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('tanggal').value = tomorrow.toISOString().split('T')[0];
    }

    // Fungsi untuk menerapkan filter
    function applyFilter() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const ruteFilter = document.getElementById('filter-rute').value;
        const tanggalFilter = document.getElementById('filter-tanggal').value;
        const statusFilter = document.getElementById('filter-status').value;

        const filteredData = jadwalData.filter(jadwal => {
            // Filter berdasarkan search term
            if (searchTerm && !(
                jadwal.rute.toLowerCase().includes(searchTerm) ||
                jadwal.armada.toLowerCase().includes(searchTerm)
            )) {
                return false;
            }

            // Filter berdasarkan dropdown
            if (ruteFilter && jadwal.rute.toLowerCase() !== ruteFilter) {
                // Untuk pencocokan rute (tanpa spasi)
                const jadwalRute = jadwal.rute.toLowerCase().replace(/\s+/g, '');
                const filterRute = ruteFilter.replace(/\s+/g, '');
                if (!jadwalRute.includes(filterRute)) return false;
            }

            if (tanggalFilter && jadwal.tanggal !== tanggalFilter) return false;

            if (statusFilter) {
                const status = calculateStatus(jadwal.kursiTerisi, jadwal.totalKursi);
                if (status !== statusFilter) return false;
            }

            return true;
        });

        renderJadwalTable(filteredData);

        // Update info pagination
        const paginationInfo = document.querySelector('.pagination-info');
        if (paginationInfo) {
            const total = filteredData.length;
            paginationInfo.textContent = `Menampilkan 1-${total} dari ${total} data`;
        }

        // Reset pagination
        document.querySelectorAll('.pagination button').forEach((btn, index) => {
            btn.classList.toggle('active', index === 0);
        });
    }

    // Event listener untuk slider kursi
    document.getElementById('totalKursi').addEventListener('input', function() {
        document.getElementById('seatValue').textContent = this.value + ' kursi';
    });

    // Form submission handler
    document.getElementById('jadwalForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validasi form
        const rute = document.getElementById('rute').value;
        const tanggal = document.getElementById('tanggal').value;
        const armada = document.getElementById('armada').value;
        const waktuBerangkat = document.getElementById('waktuBerangkat').value;
        const waktuTiba = document.getElementById('waktuTiba').value;
        const harga = document.getElementById('harga').value;
        const totalKursi = document.getElementById('totalKursi').value;
        const kursiTerisi = document.getElementById('kursiTerisi').value || 0;
        const keterangan = document.getElementById('keterangan').value;

        if (!rute || !tanggal || !armada || !waktuBerangkat || !waktuTiba || !harga) {
            alert('Harap isi semua field yang wajib diisi!');
            return;
        }

        // Validasi waktu
        if (waktuBerangkat >= waktuTiba) {
            alert('Waktu keberangkatan harus lebih awal dari waktu kedatangan!');
            return;
        }

        // Validasi kursi
        if (parseInt(kursiTerisi) > parseInt(totalKursi)) {
            alert('Kursi terisi tidak boleh lebih dari total kursi!');
            return;
        }

        // Get armada detail
        const armadaSelect = document.getElementById('armada');
        const armadaDetail = armadaSelect.options[armadaSelect.selectedIndex].text;

        // Buat objek jadwal
        const jadwalDataToSave = {
            id: formMode === 'add' ? 'J' + (jadwalData.length + 1).toString().padStart(3, '0') : jadwalData[currentJadwalIndex].id,
            rute: rute,
            tanggal: tanggal,
            armada: armada,
            armadaDetail: armadaDetail,
            waktuBerangkat: waktuBerangkat,
            waktuTiba: waktuTiba,
            harga: parseInt(harga),
            totalKursi: parseInt(totalKursi),
            kursiTerisi: parseInt(kursiTerisi),
            keterangan: keterangan
        };

        if (formMode === 'add') {
            // Tambah data baru
            jadwalData.push(jadwalDataToSave);
            alert('Jadwal berhasil ditambahkan!');
        } else {
            // Update data existing
            jadwalData[currentJadwalIndex] = jadwalDataToSave;
            alert('Jadwal berhasil diperbarui!');
        }

        // Kembali ke list
        showList();

        // Di aplikasi real, di sini akan ada AJAX request ke server
        console.log('Data jadwal disimpan:', jadwalDataToSave);
    });

    // Search dengan Enter
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });

    // Pagination
    document.querySelectorAll('.pagination button').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.pagination button').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            // Di aplikasi real, di sini akan ada request data untuk halaman yang dipilih
            console.log('Halaman', this.textContent, 'dipilih');
        });
    });

    // Inisialisasi
    window.addEventListener('DOMContentLoaded', function() {
        renderJadwalTable();

        // Set tanggal filter default (besok)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('filter-tanggal').value = tomorrow.toISOString().split('T')[0];

        // Set tanggal form default (besok)
        document.getElementById('tanggal').value = tomorrow.toISOString().split('T')[0];

        // Set min date untuk tanggal (hari ini)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').min = today;
        document.getElementById('filter-tanggal').min = today;
    });
</script>
@endpush
