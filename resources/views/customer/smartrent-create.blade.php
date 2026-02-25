<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Smart Rent - Smart Shuttle</title>
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

        .btn {
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--white);
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: var(--secondary-light);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
            border: none;
        }

        /* ====== FORM STYLES ====== */
        .form-container {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .form-section {
            padding: 28px 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0 0 24px 0;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-row.two-column {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group label .required {
            color: var(--primary-color);
            margin-left: 2px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-dark);
            background: var(--white);
            transition: var(--transition);
            width: 100%;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 106, 33, 0.1);
        }

        .form-group.full-width {
            grid-column: span 3;
        }

        .form-group.half-width {
            grid-column: span 1;
        }

        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .input-group input,
        .input-group select {
            border: none;
            border-radius: 0;
            flex: 1;
        }

        .input-group input:focus,
        .input-group select:focus {
            box-shadow: none;
        }

        .input-group-addon {
            padding: 0 16px;
            background: var(--secondary-light);
            color: var(--text-medium);
            font-size: 14px;
            display: flex;
            align-items: center;
            height: 100%;
            border-left: 1px solid var(--border-color);
        }

        /* ====== COMBO DROPDOWN ====== */
        .combo-dropdown {
            position: relative;
        }

        .combo-input {
            width: 100%;
            padding-right: 40px !important;
            cursor: pointer;
            background-color: var(--white);
        }

        .combo-dropdown-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-medium);
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .combo-dropdown-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            margin-top: 4px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .combo-search-input {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .combo-search-input input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 13px;
        }

        .combo-options {
            padding: 8px 0;
        }

        .combo-option {
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .combo-option:hover {
            background: var(--secondary-light);
        }

        .combo-option.selected {
            background: var(--primary-light);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* ====== ARMADA CARD ====== */
        .armada-container {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            margin: 20px 0;
            background: var(--white);
            transition: var(--transition);
            cursor: pointer;
        }

        .armada-container:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-lg);
        }

        .armada-container.selected {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
            border-width: 2px;
        }

        .armada-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 24px;
        }

        .armada-left {
            flex: 1;
            min-width: 300px;
        }

        .armada-right {
            min-width: 220px;
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 16px;
        }

        .armada-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 16px;
        }

        .armada-badges {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-primary {
            background: var(--primary-light);
            color: var(--primary-color);
            border: 1px solid rgba(255, 106, 33, 0.2);
        }

        .badge-light {
            background: #f9fafb;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .armada-route {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
            background: var(--secondary-light);
            padding: 16px;
            border-radius: var(--radius-sm);
        }

        .route-time {
            font-weight: 700;
            font-size: 18px;
            color: var(--secondary-color);
        }

        .route-city {
            font-size: 13px;
            color: var(--text-medium);
            margin-top: 4px;
        }

        .route-arrow {
            color: var(--primary-color);
            font-size: 20px;
        }

        .armada-status {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .status-count {
            font-size: 13px;
            color: var(--text-medium);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .armada-facilities {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .facility-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: #f9fafb;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .price-section {
            margin-bottom: 16px;
            text-align: right;
        }

        .price-amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        .price-unit {
            font-size: 14px;
            color: var(--text-light);
            margin-left: 4px;
            font-weight: 500;
        }

        .price-total {
            font-size: 13px;
            color: var(--text-medium);
            margin-top: 4px;
        }

        .price-total strong {
            color: var(--primary-color);
            font-size: 16px;
        }

        .armada-radio-hidden {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* ====== PENUMPANG TABLE ====== */
        .penumpang-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }

        .penumpang-table th {
            background: var(--secondary-light);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
        }

        .penumpang-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .penumpang-table input,
        .penumpang-table select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 13px;
        }

        .btn-add-row {
            background: var(--secondary-light);
            border: 1px dashed var(--border-color);
            padding: 10px;
            width: 100%;
            border-radius: var(--radius-sm);
            color: var(--text-medium);
            cursor: pointer;
            transition: var(--transition);
            font-size: 13px;
            margin-top: 10px;
        }

        .btn-add-row:hover {
            background: var(--primary-light);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-remove-row {
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 16px;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .btn-remove-row:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* ====== PAYMENT SECTION ====== */
        .payment-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 24px;
        }

        .payment-box {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            background: var(--white);
        }

        .payment-title {
            font-weight: 700;
            font-size: 16px;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .payment-detail-row.total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px dashed var(--border-color);
            font-weight: 700;
            font-size: 16px;
        }

        .payment-label {
            color: var(--text-medium);
        }

        .payment-value {
            color: var(--text-dark);
            font-weight: 500;
        }

        .payment-value.total {
            color: var(--primary-color);
            font-size: 20px;
        }

        .payment-method-group {
            margin: 20px 0;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            background: var(--primary-light);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: var(--primary-light);
        }

        .payment-method input[type="radio"] {
            margin-right: 12px;
            accent-color: var(--primary-color);
        }

        .payment-method-icon {
            width: 32px;
            height: 32px;
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--primary-color);
            font-size: 18px;
        }

        .payment-method-info {
            flex: 1;
        }

        .payment-method-name {
            font-weight: 600;
            font-size: 14px;
        }

        .payment-method-desc {
            font-size: 12px;
            color: var(--text-medium);
        }

        .btn-pay {
            width: 100%;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 14px;
            border-radius: var(--radius-sm);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 20px;
        }

        .btn-pay:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ====== FORM ACTIONS ====== */
        .form-actions {
            padding: 20px 32px;
            background: var(--secondary-light);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        /* ====== CATATAN SECTION ====== */
        .catatan-section {
            margin-top: 24px;
        }

        .catatan-section textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 1024px) {
            .main-container {
                padding: 20px;
            }
            
            .form-row,
            .form-row.two-column {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .payment-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .armada-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .armada-left,
            .armada-right {
                width: 100%;
                text-align: left;
            }
            
            .armada-right {
                align-items: flex-start;
            }
            
            .price-section {
                text-align: left;
            }
            
            .armada-route {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .route-arrow {
                transform: rotate(90deg);
                margin: 8px 0;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @section('content')
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Transaksi Smart Rent</h4>
                <p>Isi data transaksi smart rent dengan lengkap</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.smartrent.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Container -->
        <form action="{{ route('admin.smartrent.store') }}" method="POST" class="form-container" id="form-transaksi">
            @csrf

            <!-- Section 1: Data Pelanggan -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    Data Pelanggan
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama_pelanggan" placeholder="Masukkan nama lengkap" value="{{ old('nama_pelanggan') }}" required>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon <span class="required">*</span></label>
                        <input type="tel" name="telepon" placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>No. Identitas (KTP/SIM)</label>
                        <input type="text" name="no_identitas" placeholder="16 digit NIK / No. SIM" value="{{ old('no_identitas') }}">
                    </div>

                    <div class="form-group">
                        <label>Jenis Identitas</label>
                        <select name="jenis_identitas">
                            <option value="">Pilih Jenis Identitas</option>
                            <option value="ktp" {{ old('jenis_identitas') == 'ktp' ? 'selected' : '' }}>KTP</option>
                            <option value="sim" {{ old('jenis_identitas') == 'sim' ? 'selected' : '' }}>SIM</option>
                            <option value="paspor" {{ old('jenis_identitas') == 'paspor' ? 'selected' : '' }}>Paspor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Customer ID (Optional)</label>
                        <select name="customer_id" class="form-control">
                            <option value="">Pilih Customer (Optional)</option>
                            @if(isset($customers) && $customers->count() > 0)
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted">Pilih jika pelanggan sudah terdaftar</small>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pemesanan -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Data Pemesanan
                </div>

                <div class="form-row two-column">
                    <div class="form-group">
                        <label>Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai <span class="required">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+1 day'))) }}" required>
                    </div>
                </div>

                <div class="form-row two-column">
                    <div class="form-group">
                        <label>Durasi Sewa</label>
                        <div class="input-group">
                            <input type="number" name="durasi" id="durasi" value="{{ old('durasi', 1) }}" min="1" readonly>
                            <span class="input-group-addon">Hari</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Mobil</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_mobil" id="jumlah_mobil" value="{{ old('jumlah_mobil', 1) }}" min="1" max="5">
                            <span class="input-group-addon">Unit</span>
                        </div>
                    </div>
                </div>

                <div class="form-row two-column">
                    <div class="form-group">
                        <label>Kota Asal <span class="required">*</span></label>
                        <div class="combo-dropdown">
                            <input type="text" 
                                   class="form-control combo-input" 
                                   id="kota-asal-input"
                                   placeholder="Pilih kota asal"
                                   value="{{ old('kota_asal', 'Jakarta') }}"
                                   readonly>
                            <input type="hidden" name="kota_asal" id="kota_asal" value="{{ old('kota_asal', 'Jakarta') }}">
                            <button type="button" class="combo-dropdown-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="combo-dropdown-results" id="kota-asal-dropdown">
                                <div class="combo-search-input">
                                    <input type="text" placeholder="Cari kota asal..." autocomplete="off">
                                </div>
                                <div class="combo-options">
                                    <div class="combo-option" data-value="Jakarta">Jakarta</div>
                                    <div class="combo-option" data-value="Bandung">Bandung</div>
                                    <div class="combo-option" data-value="Surabaya">Surabaya</div>
                                    <div class="combo-option" data-value="Yogyakarta">Yogyakarta</div>
                                    <div class="combo-option" data-value="Bali">Bali</div>
                                    <div class="combo-option" data-value="Lombok">Lombok</div>
                                    <div class="combo-option" data-value="Medan">Medan</div>
                                    <div class="combo-option" data-value="Makassar">Makassar</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Kota Tujuan <span class="required">*</span></label>
                        <div class="combo-dropdown">
                            <input type="text" 
                                   class="form-control combo-input" 
                                   id="kota-tujuan-input"
                                   placeholder="Pilih kota tujuan"
                                   value="{{ old('kota_tujuan', 'Bandung') }}"
                                   readonly>
                            <input type="hidden" name="kota_tujuan" id="kota_tujuan" value="{{ old('kota_tujuan', 'Bandung') }}">
                            <button type="button" class="combo-dropdown-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="combo-dropdown-results" id="kota-tujuan-dropdown">
                                <div class="combo-search-input">
                                    <input type="text" placeholder="Cari kota tujuan..." autocomplete="off">
                                </div>
                                <div class="combo-options">
                                    <div class="combo-option" data-value="Jakarta">Jakarta</div>
                                    <div class="combo-option" data-value="Bandung">Bandung</div>
                                    <div class="combo-option" data-value="Surabaya">Surabaya</div>
                                    <div class="combo-option" data-value="Yogyakarta">Yogyakarta</div>
                                    <div class="combo-option" data-value="Bali">Bali</div>
                                    <div class="combo-option" data-value="Lombok">Lombok</div>
                                    <div class="combo-option" data-value="Medan">Medan</div>
                                    <div class="combo-option" data-value="Makassar">Makassar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Pilih Armada -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-car"></i>
                    Pilih Armada <span class="required">*</span>
                </div>

                <!-- Filter Armada -->
                <div class="form-row" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label>Kategori Mobil</label>
                        <select id="kategori-mobil">
                            <option value="">Semua Kategori</option>
                            <option value="MPV">MPV (7 Seat)</option>
                            <option value="SUV">SUV (7 Seat)</option>
                            <option value="Hatchback">Hatchback (5 Seat)</option>
                            <option value="Sedan">Sedan (5 Seat)</option>
                            <option value="Minibus">Minibus (12-15 Seat)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Layanan <span class="required">*</span></label>
                        <select name="layanan" id="layanan" required>
                            <option value="">Pilih Layanan</option>
                            <option value="lepas_kunci" {{ old('layanan') == 'lepas_kunci' ? 'selected' : '' }}>Lepas Kunci</option>
                            <option value="dengan_sopir" {{ old('layanan') == 'dengan_sopir' ? 'selected' : '' }}>Dengan Sopir</option>
                        </select>
                    </div>
                </div>

                @php
                    $armadaList = [
                        [
                            'id' => 1,
                            'nama' => 'Toyota Avanza',
                            'kategori' => 'MPV',
                            'kapasitas' => 7,
                            'nomor_polisi' => 'B 1234 CD',
                            'tahun' => 2019,
                            'harga' => 350000,
                            'fasilitas' => ['AC', 'Audio', 'Charger', 'Airbag'],
                            'bahan_bakar' => 'Bensin'
                        ],
                        [
                            'id' => 2,
                            'nama' => 'Honda Brio',
                            'kategori' => 'Hatchback',
                            'kapasitas' => 5,
                            'nomor_polisi' => 'B 5678 EF',
                            'tahun' => 2020,
                            'harga' => 250000,
                            'fasilitas' => ['AC', 'Audio', 'Airbag'],
                            'bahan_bakar' => 'Bensin'
                        ],
                        [
                            'id' => 3,
                            'nama' => 'Mitsubishi Xpander',
                            'kategori' => 'MPV',
                            'kapasitas' => 7,
                            'nomor_polisi' => 'B 9012 GH',
                            'tahun' => 2021,
                            'harga' => 450000,
                            'fasilitas' => ['AC Double', 'Wifi', 'USB Charger', 'Airbag'],
                            'bahan_bakar' => 'Bensin'
                        ],
                        [
                            'id' => 4,
                            'nama' => 'Toyota Innova',
                            'kategori' => 'MPV',
                            'kapasitas' => 7,
                            'nomor_polisi' => 'B 3456 IJ',
                            'tahun' => 2022,
                            'harga' => 550000,
                            'fasilitas' => ['AC Double', 'Audio', 'USB Charger', 'Airbag', 'TV'],
                            'bahan_bakar' => 'Diesel'
                        ],
                        [
                            'id' => 5,
                            'nama' => 'Daihatsu Xenia',
                            'kategori' => 'MPV',
                            'kapasitas' => 7,
                            'nomor_polisi' => 'B 7890 KL',
                            'tahun' => 2020,
                            'harga' => 300000,
                            'fasilitas' => ['AC', 'Audio', 'Charger'],
                            'bahan_bakar' => 'Bensin'
                        ]
                    ];
                @endphp

                <!-- Armada Cards -->
                <div id="armada-list">
                    @foreach($armadaList as $armada)
                    <div class="armada-container" data-armada-id="{{ $armada['id'] }}" data-kategori="{{ $armada['kategori'] }}" data-harga="{{ $armada['harga'] }}" data-nama="{{ $armada['nama'] }}" data-nopol="{{ $armada['nomor_polisi'] }}">
                        <input type="radio" name="armada_id" value="{{ $armada['id'] }}" class="armada-radio-hidden" {{ old('armada_id') == $armada['id'] ? 'checked' : '' }}>
                        <div class="armada-header">
                            <div class="armada-left">
                                <h2 class="armada-name">{{ $armada['nama'] }}</h2>
                                
                                <div class="armada-badges">
                                    <span class="badge badge-primary">
                                        <i class="fas fa-tag"></i>
                                        {{ $armada['kategori'] }} - {{ $armada['kapasitas'] }} Seat
                                    </span>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                </div>

                                <div class="armada-route">
                                    <div>
                                        <div class="route-time" id="jam-asal-{{ $armada['id'] }}">08:00</div>
                                        <div class="route-city" id="kota-asal-tampil-{{ $armada['id'] }}">Jakarta</div>
                                    </div>
                                    <div>
                                        <div class="route-arrow">→</div>
                                    </div>
                                    <div>
                                        <div class="route-time" id="jam-tujuan-{{ $armada['id'] }}">Selesai</div>
                                        <div class="route-city" id="kota-tujuan-tampil-{{ $armada['id'] }}">Bandung</div>
                                    </div>
                                </div>

                                <div class="armada-status">
                                    <span class="status-count">
                                        <i class="fas fa-gas-pump"></i> {{ $armada['bahan_bakar'] }}
                                    </span>
                                    <span class="status-count">
                                        <i class="fas fa-chair"></i> {{ $armada['kapasitas'] }} Kursi
                                    </span>
                                    <span class="status-count">
                                        <i class="fas fa-calendar-check"></i> {{ $armada['tahun'] }}
                                    </span>
                                </div>

                                <div class="armada-facilities">
                                    @foreach($armada['fasilitas'] as $fasilitas)
                                    <span class="facility-badge">
                                        @if($fasilitas == 'AC' || $fasilitas == 'AC Double')
                                            <i class="fas fa-snowflake"></i>
                                        @elseif($fasilitas == 'Audio')
                                            <i class="fas fa-music"></i>
                                        @elseif($fasilitas == 'Charger' || $fasilitas == 'USB Charger')
                                            <i class="fas fa-plug"></i>
                                        @elseif($fasilitas == 'Airbag')
                                            <i class="fas fa-shield-alt"></i>
                                        @elseif($fasilitas == 'Wifi')
                                            <i class="fas fa-wifi"></i>
                                        @elseif($fasilitas == 'TV')
                                            <i class="fas fa-tv"></i>
                                        @endif
                                        {{ $fasilitas }}
                                    </span>
                                    @endforeach
                                </div>

                                <div style="margin-top: 12px;">
                                    <small class="text-medium">Plat Nomor: <strong>{{ $armada['nomor_polisi'] }}</strong></small>
                                </div>
                            </div>

                            <div class="armada-right">
                                <div class="price-section">
                                    <div class="price-amount">Rp {{ number_format($armada['harga'], 0, ',', '.') }} <span class="price-unit">/hari</span></div>
                                    <div class="price-total" id="total-armada-{{ $armada['id'] }}">Total: <strong>Rp {{ number_format($armada['harga'], 0, ',', '.') }}</strong></div>
                                </div>

                                <div style="width: 100%;">
                                    <button type="button" class="btn btn-primary" style="width: 100%;" onclick="selectArmada({{ $armada['id'] }})">
                                        <i class="fas fa-check-circle"></i> Pilih Armada
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 4: Data Penumpang -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-users"></i>
                    Data Penumpang (Optional)
                </div>

                <table class="penumpang-table" id="penumpang-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Nama Lengkap</th>
                            <th style="width: 20%">NIK</th>
                            <th style="width: 15%">Jenis Kelamin</th>
                            <th style="width: 20%">No. Telepon</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="penumpang-body">
                        @if(old('penumpang'))
                            @foreach(old('penumpang') as $index => $penumpang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><input type="text" name="penumpang[{{ $index }}][nama]" value="{{ $penumpang['nama'] ?? '' }}" placeholder="Nama lengkap"></td>
                                <td><input type="text" name="penumpang[{{ $index }}][nik]" value="{{ $penumpang['nik'] ?? '' }}" placeholder="NIK"></td>
                                <td>
                                    <select name="penumpang[{{ $index }}][jenis_kelamin]">
                                        <option value="">Pilih</option>
                                        <option value="L" {{ ($penumpang['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ ($penumpang['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </td>
                                <td><input type="text" name="penumpang[{{ $index }}][telepon]" value="{{ $penumpang['telepon'] ?? '' }}" placeholder="No. Telepon"></td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-remove-row" onclick="removeRow(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td><input type="text" name="penumpang[0][nama]" placeholder="Nama lengkap"></td>
                            <td><input type="text" name="penumpang[0][nik]" placeholder="NIK"></td>
                            <td>
                                <select name="penumpang[0][jenis_kelamin]">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </td>
                            <td><input type="text" name="penumpang[0][telepon]" placeholder="No. Telepon"></td>
                            <td style="text-align: center;">
                                <button type="button" class="btn-remove-row" onclick="removeRow(this)" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <button type="button" class="btn-add-row" onclick="addPenumpangRow()">
                    <i class="fas fa-plus"></i> Tambah Penumpang
                </button>
            </div>

            <!-- Section 5: Metode Pembayaran -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i>
                    Metode Pembayaran
                </div>

                <div class="payment-grid">
                    <div class="payment-box">
                        <div class="payment-title">Ringkasan Pembayaran</div>
                        
                        <div class="payment-detail-row">
                            <span class="payment-label">Harga per hari</span>
                            <span class="payment-value" id="harga-perhari">Rp 0</span>
                        </div>
                        
                        <div class="payment-detail-row">
                            <span class="payment-label">Durasi sewa</span>
                            <span class="payment-value" id="durasi-sewa">1 hari</span>
                        </div>
                        
                        <div class="payment-detail-row">
                            <span class="payment-label">Jumlah mobil</span>
                            <span class="payment-value" id="jumlah-mobil">1 unit</span>
                        </div>
                        
                        <div class="payment-detail-row">
                            <span class="payment-label">Biaya layanan</span>
                            <span class="payment-value" id="biaya-layanan">Rp 0</span>
                        </div>
                        
                        <div class="payment-detail-row total">
                            <span class="payment-label">Total Bayar</span>
                            <span class="payment-value total" id="total-bayar">Rp 0</span>
                        </div>
                        
                        <input type="hidden" name="total_bayar" id="total-bayar-input" value="0">
                    </div>

                    <div class="payment-box">
                        <div class="payment-title">Pilih Metode <span class="required">*</span></div>
                        
                        <div class="payment-method-group">
                            <label class="payment-method {{ old('metode_pembayaran') == 'BCA VA' ? 'selected' : '' }}">
                                <input type="radio" name="metode_pembayaran" value="BCA VA" {{ old('metode_pembayaran') == 'BCA VA' ? 'checked' : '' }} required>
                                <span class="payment-method-icon">
                                    <i class="fas fa-university"></i>
                                </span>
                                <span class="payment-method-info">
                                    <span class="payment-method-name">BCA Virtual Account</span>
                                    <span class="payment-method-desc">Bayar melalui ATM/m-banking BCA</span>
                                </span>
                            </label>
                            
                            <label class="payment-method {{ old('metode_pembayaran') == 'Mandiri VA' ? 'selected' : '' }}">
                                <input type="radio" name="metode_pembayaran" value="Mandiri VA" {{ old('metode_pembayaran') == 'Mandiri VA' ? 'checked' : '' }}>
                                <span class="payment-method-icon">
                                    <i class="fas fa-university"></i>
                                </span>
                                <span class="payment-method-info">
                                    <span class="payment-method-name">Mandiri Virtual Account</span>
                                    <span class="payment-method-desc">Bayar melalui ATM/m-banking Mandiri</span>
                                </span>
                            </label>
                            
                            <label class="payment-method {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}">
                                <input type="radio" name="metode_pembayaran" value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'checked' : '' }}>
                                <span class="payment-method-icon">
                                    <i class="fas fa-qrcode"></i>
                                </span>
                                <span class="payment-method-info">
                                    <span class="payment-method-name">QRIS</span>
                                    <span class="payment-method-desc">Scan QR code dengan e-wallet/m-banking</span>
                                </span>
                            </label>
                            
                            <label class="payment-method {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}">
                                <input type="radio" name="metode_pembayaran" value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'checked' : '' }}>
                                <span class="payment-method-icon">
                                    <i class="fas fa-money-bill-transfer"></i>
                                </span>
                                <span class="payment-method-info">
                                    <span class="payment-method-name">Transfer Bank</span>
                                    <span class="payment-method-desc">Transfer ke rekening BCA/Mandiri/BNI</span>
                                </span>
                            </label>
                            
                            <label class="payment-method {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}">
                                <input type="radio" name="metode_pembayaran" value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'checked' : '' }}>
                                <span class="payment-method-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <span class="payment-method-info">
                                    <span class="payment-method-name">Cash</span>
                                    <span class="payment-method-desc">Bayar tunai di kasir</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="catatan-section">
                    <label for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" placeholder="Masukkan catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="petugas" value="{{ auth()->user()->name ?? 'sistem' }}">
                <input type="hidden" name="created_by" value="{{ auth()->id() ?? '' }}">
                <input type="hidden" name="status" value="pending">
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.smartrent.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <script>
        let penumpangCounter = 0;
        let selectedArmadaId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize counter based on existing rows
            const rows = document.querySelectorAll('#penumpang-body tr');
            penumpangCounter = rows.length;
            
            initializeComboBoxes();
            initializeDateCalculations();
            initializeArmadaSelection();
            initializePricing();
            
            // Set selected armada if any from old input
            const selectedArmada = document.querySelector('input[name="armada_id"]:checked');
            if (selectedArmada) {
                selectArmada(selectedArmada.value);
            }
            
            // Initialize payment method selection styling
            initializePaymentMethods();
        });

        // ====== COMBO DROPDOWN ======
        function initializeComboBoxes() {
            setupComboBox('kota-asal-input', 'kota_asal', 'kota-asal-dropdown');
            setupComboBox('kota-tujuan-input', 'kota_tujuan', 'kota-tujuan-dropdown');
        }

        function setupComboBox(inputId, hiddenInputId, dropdownId) {
            const input = document.getElementById(inputId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const dropdown = document.getElementById(dropdownId);
            
            if (!input || !dropdown) return;
            
            const toggleBtn = input.parentNode.querySelector('.combo-dropdown-toggle');
            const searchInput = dropdown.querySelector('input[type="text"]');
            const options = dropdown.querySelectorAll('.combo-option');
            
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display === 'block';
                dropdown.style.display = isVisible ? 'none' : 'block';
                
                if (!isVisible && searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
            
            input.addEventListener('click', function(e) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
            
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.dataset.value;
                    input.value = this.textContent;
                    hiddenInput.value = value;
                    dropdown.style.display = 'none';
                    
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Update route display in armada cards
                    updateArmadaRoute();
                });
            });
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? 'block' : 'none';
                    });
                });
            }
            
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        // ====== DATE CALCULATIONS ======
        function initializeDateCalculations() {
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const durasiInput = document.getElementById('durasi');

            if (tanggalMulai && tanggalSelesai && durasiInput) {
                function hitungDurasi() {
                    if (tanggalMulai.value && tanggalSelesai.value) {
                        const start = new Date(tanggalMulai.value);
                        const end = new Date(tanggalSelesai.value);
                        
                        // Reset time to avoid timezone issues
                        start.setHours(0, 0, 0, 0);
                        end.setHours(0, 0, 0, 0);
                        
                        const diffTime = end - start;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        
                        if (diffDays > 0) {
                            durasiInput.value = diffDays;
                            document.getElementById('durasi-sewa').textContent = diffDays + ' hari';
                            updateTotalHarga();
                        } else {
                            alert('Tanggal selesai harus setelah tanggal mulai');
                            tanggalSelesai.value = tanggalMulai.value;
                            durasiInput.value = 1;
                            document.getElementById('durasi-sewa').textContent = '1 hari';
                        }
                    }
                }

                tanggalMulai.addEventListener('change', function() {
                    const minDate = new Date(this.value);
                    minDate.setDate(minDate.getDate() + 1);
                    tanggalSelesai.min = minDate.toISOString().split('T')[0];
                    
                    if (new Date(tanggalSelesai.value) < new Date(this.value)) {
                        tanggalSelesai.value = minDate.toISOString().split('T')[0];
                    }
                    hitungDurasi();
                });
                
                tanggalSelesai.addEventListener('change', hitungDurasi);
                
                // Set min date for tanggal_selesai
                if (tanggalMulai.value) {
                    const minDate = new Date(tanggalMulai.value);
                    minDate.setDate(minDate.getDate() + 1);
                    tanggalSelesai.min = minDate.toISOString().split('T')[0];
                }
                
                hitungDurasi();
            }
        }

        // ====== ARMADA SELECTION ======
        function initializeArmadaSelection() {
            // Add click event to armada containers
            document.querySelectorAll('.armada-container').forEach(container => {
                container.addEventListener('click', function(e) {
                    // Don't select if clicking on button
                    if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                        return;
                    }
                    
                    const armadaId = this.dataset.armadaId;
                    selectArmada(armadaId);
                });
            });

            // Filter armada by kategori
            const kategoriFilter = document.getElementById('kategori-mobil');
            if (kategoriFilter) {
                kategoriFilter.addEventListener('change', filterArmada);
            }

            // Update route when kota asal/tujuan changes
            updateArmadaRoute();
        }

        function selectArmada(armadaId) {
            // Remove selected class from all containers
            document.querySelectorAll('.armada-container').forEach(container => {
                container.classList.remove('selected');
            });
            
            // Add selected class to chosen container
            const selectedContainer = document.querySelector(`.armada-container[data-armada-id="${armadaId}"]`);
            if (selectedContainer) {
                selectedContainer.classList.add('selected');
                
                // Check the radio button
                const radio = selectedContainer.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    selectedArmadaId = armadaId;
                    
                    // Update pricing
                    const harga = parseInt(selectedContainer.dataset.harga) || 0;
                    document.getElementById('harga-perhari').textContent = formatRupiah(harga);
                    
                    updateTotalHarga();
                }
            }
        }

        function filterArmada() {
            const kategori = document.getElementById('kategori-mobil').value;
            const armadaContainers = document.querySelectorAll('.armada-container');
            
            armadaContainers.forEach(container => {
                const containerKategori = container.dataset.kategori;
                
                if (!kategori || containerKategori === kategori) {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                }
            });
        }

        function updateArmadaRoute() {
            const kotaAsal = document.getElementById('kota_asal')?.value || 'Jakarta';
            const kotaTujuan = document.getElementById('kota_tujuan')?.value || 'Bandung';
            
            document.querySelectorAll('.armada-container').forEach(container => {
                const armadaId = container.dataset.armadaId;
                const kotaAsalElement = document.getElementById(`kota-asal-tampil-${armadaId}`);
                const kotaTujuanElement = document.getElementById(`kota-tujuan-tampil-${armadaId}`);
                
                if (kotaAsalElement) {
                    kotaAsalElement.textContent = kotaAsal;
                }
                
                if (kotaTujuanElement) {
                    kotaTujuanElement.textContent = kotaTujuan;
                }
            });
        }

        // ====== PRICE CALCULATIONS ======
        function updateTotalHarga() {
            const selectedContainer = document.querySelector('.armada-container.selected');
            if (!selectedContainer) return;

            const hargaPerHari = parseInt(selectedContainer.dataset.harga) || 0;
            const durasi = parseInt(document.getElementById('durasi').value) || 1;
            const jumlahMobil = parseInt(document.getElementById('jumlah_mobil').value) || 1;
            
            // Hitung biaya layanan (dengan sopir)
            let biayaLayanan = 0;
            const layananSelect = document.getElementById('layanan');
            
            if (layananSelect && layananSelect.value === 'dengan_sopir') {
                biayaLayanan = 150000 * durasi * jumlahMobil;
            }
            
            const total = (hargaPerHari * durasi * jumlahMobil) + biayaLayanan;
            
            document.getElementById('harga-perhari').textContent = formatRupiah(hargaPerHari);
            document.getElementById('durasi-sewa').textContent = durasi + ' hari';
            document.getElementById('jumlah-mobil').textContent = jumlahMobil + ' unit';
            document.getElementById('biaya-layanan').textContent = formatRupiah(biayaLayanan);
            document.getElementById('total-bayar').textContent = formatRupiah(total);
            document.getElementById('total-bayar-input').value = total;
            
            // Update total per armada
            const totalArmadaElement = document.getElementById(`total-armada-${selectedArmadaId}`);
            if (totalArmadaElement) {
                totalArmadaElement.innerHTML = `Total: <strong>${formatRupiah(hargaPerHari * durasi * jumlahMobil)}</strong>`;
            }
        }

        function formatRupiah(angka) {
            if (angka === 0) return 'Rp 0';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // ====== PENUMPANG TABLE ======
        function addPenumpangRow() {
            const tbody = document.getElementById('penumpang-body');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>${penumpangCounter + 1}</td>
                <td><input type="text" name="penumpang[${penumpangCounter}][nama]" placeholder="Nama lengkap"></td>
                <td><input type="text" name="penumpang[${penumpangCounter}][nik]" placeholder="NIK"></td>
                <td>
                    <select name="penumpang[${penumpangCounter}][jenis_kelamin]">
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
                <td><input type="text" name="penumpang[${penumpangCounter}][telepon]" placeholder="No. Telepon"></td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remove-row" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            penumpangCounter++;
            
            updateRowNumbers();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            if (document.querySelectorAll('#penumpang-body tr').length > 1) {
                row.remove();
                penumpangCounter--;
                updateRowNumbers();
            }
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#penumpang-body tr');
            rows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
                
                // Update name attributes
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
            });
        }

        // ====== PAYMENT METHODS ======
        function initializePaymentMethods() {
            const paymentMethods = document.querySelectorAll('input[name="metode_pembayaran"]');
            
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Remove selected class from all labels
                    document.querySelectorAll('.payment-method').forEach(label => {
                        label.classList.remove('selected');
                    });
                    
                    // Add selected class to parent label
                    if (this.checked) {
                        this.closest('.payment-method').classList.add('selected');
                    }
                });
            });
        }

        // ====== LAYANAN CHANGE ======
        document.getElementById('layanan')?.addEventListener('change', function() {
            updateTotalHarga();
        });

        // ====== JUMLAH MOBIL CHANGE ======
        document.getElementById('jumlah_mobil')?.addEventListener('input', function() {
            document.getElementById('jumlah-mobil').textContent = this.value + ' unit';
            updateTotalHarga();
        });

        // ====== FORM VALIDATION ======
        document.getElementById('form-transaksi')?.addEventListener('submit', function(e) {
            const selectedArmada = document.querySelector('input[name="armada_id"]:checked');
            if (!selectedArmada) {
                e.preventDefault();
                alert('Silakan pilih armada terlebih dahulu');
                return;
            }
            
            const layanan = document.getElementById('layanan').value;
            if (!layanan) {
                e.preventDefault();
                alert('Silakan pilih layanan');
                return;
            }
            
            const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (!metodePembayaran) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran');
                return;
            }
        });
    </script>
    @endsection
</body>
</html>
