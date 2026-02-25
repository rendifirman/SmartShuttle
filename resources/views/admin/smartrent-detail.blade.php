@extends('layouts.app-admin')

@section('title', 'Detail Pesanan Smartrent')

@push('styles')
<style>
:root{
    --primary:#FF6B2C;
    --dark:#163A5F;
    --border:#E5E5E5;
    --bg:#F3F4F6;
    --text:#333;
    --success:#2ECC71;
}

* {
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
}

.content-wrapper{
    background: var(--bg);
    padding: 30px;
    min-height: 100vh;
}

/* PAGE TITLE */
.page-title{
    font-size: 20px;
    font-weight: 600;
    color: #444;
    margin-bottom: 20px;
    animation: slideInLeft 0.3s ease-out;
}

/* GRID */
.detail-grid{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

/* CARD */
.detail-card{
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
    position: relative;
    overflow: hidden;
}

.detail-card:nth-child(1) { animation-delay: 0.1s; }
.detail-card:nth-child(2) { animation-delay: 0.2s; }
.detail-card:nth-child(3) { animation-delay: 0.3s; }
.detail-card:nth-child(4) { animation-delay: 0.4s; }

.detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), #ff9f6e);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.detail-card:hover::before {
    transform: scaleX(1);
}

/* CARD HEADER */
.card-title{
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 15px;
    position: relative;
    padding-bottom: 8px;
    font-size: 15px;
}

.card-title::after{
    content:"";
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:2px;
    background: var(--primary);
}

/* TABLE STYLE */
.detail-table{
    width:100%;
    border-collapse: collapse;
    font-size: 13px;
}

.detail-table tr{
    border-bottom:1px solid var(--border);
    transition: background-color 0.2s ease;
}

.detail-table tr:last-child{
    border-bottom:none;
}

.detail-table tr:hover{
    background-color: rgba(255, 107, 44, 0.03);
}

.detail-table td{
    padding:8px 0;
    color:#555;
}

.detail-table td:first-child{
    width:55%;
}

.detail-table td:last-child{
    text-align:right;
    font-weight:500;
    color:#000;
}

/* BUTTON */
.btn-action{
    display:block;
    width:100%;
    margin-top:20px;
    padding:12px;
    border-radius:6px;
    border:none;
    font-weight:600;
    color:#fff;
    cursor:pointer;
    font-size:14px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-action::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-action:active::after {
    width: 300px;
    height: 300px;
}

.btn-orange{
    background: var(--primary);
}

.btn-orange:hover{
    background:#e65a22;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 44, 0.3);
}

.btn-green{
    background: var(--success);
}

.btn-green:hover{
    background:#27ae60;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
}

/* ANIMATIONS */
@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

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

/* RESPONSIVE */
@media(max-width:992px){
    .detail-grid{
        grid-template-columns:1fr;
    }
}

/* ================= MODAL YANG DI RAPIHIN ================= */
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999;
    backdrop-filter: blur(4px);
}

.modal-container{
    width:800px;
    max-width: 95%;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    animation: modalFadeIn 0.3s ease;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
}

@keyframes modalFadeIn{
    from{
        opacity:0;
        transform: scale(0.95);
    }
    to{
        opacity:1;
        transform: scale(1);
    }
}

.modal-header{
    background: var(--dark);
    color:#fff;
    padding:18px 24px;
    font-weight:600;
    font-size: 16px;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-header i {
    color: var(--primary);
    font-size: 18px;
}

.modal-body{
    padding:24px;
    max-height:80vh;
    overflow-y:auto;
}

/* BOOKING INFO YANG DI RAPIHIN */
.booking-info{
    background: #f8f9fc;
    padding:16px 20px;
    border-radius:8px;
    display:flex;
    justify-content:space-between;
    align-items: center;
    margin-bottom:24px;
    font-size:13px;
    border: 1px solid #eef2f6;
}

.booking-info div {
    display: flex;
    align-items: center;
    gap: 6px;
}

.booking-info i {
    color: var(--primary);
    font-size: 14px;
}

.booking-info small {
    color: #64748b;
    font-size: 12px;
    font-weight: 400;
}

.booking-info strong {
    color: var(--dark);
    font-size: 13px;
    font-weight: 600;
}

/* SECTION TITLE YANG DI RAPIHIN */
.section-title{
    font-weight:600;
    font-size: 14px;
    color: var(--dark);
    margin:24px 0 16px;
    padding-bottom:8px;
    border-bottom:2px solid var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: var(--primary);
    font-size: 16px;
}

/* FORM GRID YANG DI RAPIHIN */
.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.form-grid > div {
    display: flex;
    flex-direction: column;
}

.form-grid label {
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    margin-bottom: 6px;
}

.form-grid input,
.form-grid select,
.form-grid textarea {
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    width:100%;
    padding:10px 12px;
    border:1px solid #e2e8f0;
    border-radius:6px;
    font-size:13px;
    transition: all 0.2s ease;
    background: #fff;
}

.form-grid input:hover,
.form-grid select:hover {
    border-color: #cbd5e1;
}

.form-grid input:focus,
.form-grid select:focus,
.form-grid textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.1);
    outline: none;
}

.form-grid input::placeholder {
    color: #a0aec0;
    font-size: 12px;
}

/* CHECKBOX GRID YANG DI RAPIHIN */
.checkbox-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
    font-size:13px;
    background: #fafbfc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #edf2f7;
}

.checkbox-grid label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 6px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: 13px;
    color: #334155;
}

.checkbox-grid label:hover {
    background: rgba(255, 107, 44, 0.05);
}

.checkbox-grid input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--primary);
    margin: 0;
}

/* UPLOAD SECTION */
.upload-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.upload-item {
    display: flex;
    flex-direction: column;
}

.upload-item label {
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    margin-bottom: 6px;
}

.upload-item input[type="file"] {
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    padding: 8px;
    border: 1px dashed #cbd5e1;
    border-radius: 6px;
    background: #f8fafc;
    font-size: 12px;
    cursor: pointer;
}

.upload-item input[type="file"]:hover {
    border-color: var(--primary);
    background: #fff5f0;
}

/* TEXTAREA */
textarea {
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 13px;
    resize: vertical;
    min-height: 80px;
}

textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.1);
    outline: none;
}

/* MODAL FOOTER YANG DI RAPIHIN */
.modal-footer{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:28px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.btn-cancel{
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding:10px 24px;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
    font-weight:500;
    color: #475569;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: #e2e8f0;
    color: #334155;
}

.btn-confirm{
    background: var(--success);
    border:none;
    padding:10px 24px;
    color:#fff;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(46, 204, 113, 0.2);
}

.btn-confirm:hover{
    background:#27ae60;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(46, 204, 113, 0.3);
}

.btn-confirm i {
    font-size: 14px;
}

/* Loading spinner */
.btn-confirm.loading {
    pointer-events: none;
    opacity: 0.8;
    position: relative;
    padding-left: 40px;
}

.btn-confirm.loading::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 50%;
    width: 16px;
    height: 16px;
    margin-top: -8px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive modal */
@media (max-width: 768px) {
    .modal-body {
        padding: 16px;
    }
    
    .booking-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .checkbox-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .upload-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-confirm {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-title" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 8px;"></i>
            Detail Pesanan Smartrent
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.smartrent.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $penyerahan = $smartRent->additional_data['penyerahan'] ?? [];
        $pengembalian = $smartRent->additional_data['pengembalian'] ?? [];
    @endphp

    <!-- Flash messages -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">
            {{ session('success') }}
        </div>
        <script>alert("{{ session('success') }}");</script>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="detail-grid">

        <!-- DATA PEMESANAN -->
        <div class="detail-card">
            <div class="card-title">
                <i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 8px;"></i>
                Data Pemesanan
            </div>
            <table class="detail-table">
                <tr>
                    <td>Kode Booking:</td>
                    <td><span style="font-weight: 600; color: var(--primary);">{{ $smartRent->order_number ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td>Waktu Mulai Sewa:</td>
                    <td>
                        @if(!empty($smartRent->start_date))
                            {{ \Carbon\Carbon::parse($smartRent->start_date)->format('d-m-Y') }}@if(!empty($smartRent->start_time)), {{ \Carbon\Carbon::parse($smartRent->start_time)->format('H:i') }}@endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Waktu Selesai Sewa:</td>
                    <td>
                        @if(!empty($smartRent->end_date))
                            {{ \Carbon\Carbon::parse($smartRent->end_date)->format('d-m-Y') }}@if(!empty($smartRent->end_time)), {{ \Carbon\Carbon::parse($smartRent->end_time)->format('H:i') }}@endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Durasi Sewa:</td>
                    <td>{{ !empty($smartRent->duration) ? $smartRent->duration . ' Hari' : '-' }}</td>
                </tr>
                <tr>
                    <td>Nama:</td>
                    <td>{{ $smartRent->customer_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telepon:</td>
                    <td>{{ $smartRent->customer_phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $smartRent->customer_email ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Mobil:</td>
                    <td>
                        {{ $smartRent->vehicle_name ?? '-' }}
                        @if(!empty($smartRent->vehicle_plate))
                            <span style="color: #94a3b8;">({{ $smartRent->vehicle_plate }})</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- DATA PEMBAYARAN -->
        <div class="detail-card">
            <div class="card-title">
                <i class="fas fa-credit-card" style="color: var(--primary); margin-right: 8px;"></i>
                Data Pembayaran
            </div>
            <table class="detail-table">
                <tr>
                    <td>Biaya Sewa:</td>
                    <td>Rp. {{ isset($smartRent->vehicle_total) ? number_format($smartRent->vehicle_total,0,',','.') : (isset($smartRent->vehicle_price) ? number_format($smartRent->vehicle_price,0,',','.') : '-') }}</td>
                </tr>
                <tr>
                    <td>Biaya Supir:</td>
                    <td>Rp. {{ isset($smartRent->driver_total) ? number_format($smartRent->driver_total,0,',','.') : '0' }}</td>
                </tr>
                <tr>
                    <td>Total Bayar:</td>
                    <td><span style="font-weight: 700; color: var(--primary);">Rp. {{ isset($smartRent->total_price) ? number_format($smartRent->total_price,0,',','.') : (isset($smartRent->vehicle_total) ? number_format($smartRent->vehicle_total + ($smartRent->driver_total ?? 0),0,',','.') : '-') }}</span></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran:</td>
                    <td>{{ $smartRent->payment_method ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if(isset($smartRent->payment_status) && $smartRent->payment_status === 'paid')
                            <span style="background: rgba(46, 204, 113, 0.1); color: var(--success); padding: 4px 8px; border-radius: 4px; font-weight: 500; font-size: 12px;">✓ Dibayar</span>
                        @else
                            <span style="color:#64748b;">{{ ucfirst(str_replace('_',' ', $smartRent->payment_status ?? 'unpaid')) }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran:</td>
                    <td>
                        @if(!empty($smartRent->paid_at))
                            {{ \Carbon\Carbon::parse($smartRent->paid_at)->format('d-m-Y, H:i') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- DATA PENYERAHAN -->
        <div class="detail-card">
            <div class="card-title">
                <i class="fas fa-truck" style="color: var(--primary); margin-right: 8px;"></i>
                Data Penyerahan
            </div>
            <table class="detail-table">
                <tr><td>Petugas:</td><td>{{ $penyerahan['petugas'] ?? '-' }}</td></tr>
                <tr><td>Tanggal Penyerahan:</td><td>{{ !empty($penyerahan['tanggal']) ? \Carbon\Carbon::parse($penyerahan['tanggal'])->format('d-m-Y') : '-' }}</td></tr>
                <tr><td>Waktu Penyerahan:</td><td>{{ $penyerahan['jam'] ?? '-' }}</td></tr>
                <tr><td>BBM Awal:</td><td>{{ $penyerahan['bbm_awal'] ?? '-' }}</td></tr>
                <tr><td>KM Awal:</td><td>{{ $penyerahan['km_awal'] ?? '-' }}</td></tr>
                <tr><td>Catatan:</td><td>{{ $penyerahan['catatan'] ?? '-' }}</td></tr>
            </table>

            <button class="btn-action btn-orange" onclick="openModal('penyerahan')">
                <i class="fas fa-pen" style="margin-right: 8px;"></i>
                Penyerahan Mobil
            </button>
        </div>

        <!-- DATA PENGEMBALIAN -->
        <div class="detail-card">
            <div class="card-title">
                <i class="fas fa-undo-alt" style="color: var(--primary); margin-right: 8px;"></i>
                Data Pengembalian
            </div>
            <table class="detail-table">
                <tr><td>Petugas:</td><td>{{ $pengembalian['petugas'] ?? '-' }}</td></tr>
                <tr><td>Tanggal Pengembalian:</td><td>{{ !empty($pengembalian['tanggal']) ? \Carbon\Carbon::parse($pengembalian['tanggal'])->format('d-m-Y') : '-' }}</td></tr>
                <tr><td>Waktu Pengembalian:</td><td>{{ $pengembalian['jam'] ?? '-' }}</td></tr>
                <tr><td>BBM Akhir:</td><td>{{ $pengembalian['bbm_akhir'] ?? '-' }}</td></tr>
                <tr><td>KM Akhir:</td><td>{{ $pengembalian['km_akhir'] ?? '-' }}</td></tr>
                <tr><td>Biaya Tambahan:</td><td>Rp. {{ isset($pengembalian['biaya_tambahan']) ? number_format($pengembalian['biaya_tambahan'],0,',','.') : '0' }}</td></tr>
                <tr><td>Total Akhir:</td><td>Rp. {{ isset($pengembalian['total_akhir']) ? number_format($pengembalian['total_akhir'],0,',','.') : '0' }}</td></tr>
                <tr><td>Catatan:</td><td>{{ $pengembalian['catatan'] ?? '-' }}</td></tr>
            </table>

            <button class="btn-action btn-green" onclick="openModal('pengembalian')">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                Pengembalian Mobil
            </button>

        </div>

    </div>
</div>

<!-- MODAL YANG DI RAPIHIN -->
<div class="modal-overlay" id="modalForm" onclick="if(event.target === this) closeModal()">
    <div class="modal-container">

        <div class="modal-header">
            <i class="fas fa-car"></i>
            <span id="modalTitle">Form Penyerahan Mobil</span>
        </div>

        <div class="modal-body">
            <form id="modalFormElement" method="POST" enctype="multipart/form-data">
                @csrf
            <!-- INFO BOOKING -->
            <div class="booking-info">
                <div>
                    <i class="fas fa-hashtag"></i>
                    <small>ID Booking:</small>
                    <strong>{{ $smartRent->order_number ?? '-' }}</strong>
                </div>
                <div>
                    <i class="fas fa-user"></i>
                    <small>Pelanggan:</small>
                    <strong>{{ $smartRent->customer_name ?? '-' }}</strong>
                </div>
                <div>
                    <i class="fas fa-car"></i>
                    <small>Armada:</small>
                    <strong>{{ $smartRent->vehicle_name ?? '-' }}</strong>
                </div>
            </div>

            <!-- DATA -->
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                Data Penyerahan
            </div>

            <div class="form-grid">
                <div>
                    <label>Nama Petugas <span style="color: var(--primary);">*</span></label>
                    <input type="text" name="petugas" placeholder="Masukkan nama petugas">
                </div>

                <div>
                    <label>Tanggal <span style="color: var(--primary);">*</span></label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label>Jam <span style="color: var(--primary);">*</span></label>
                    <input type="time" name="jam" value="{{ date('H:i') }}">
                </div>
            </div>

            <!-- KONDISI -->
            <div class="section-title">
                <i class="fas fa-clipboard-check"></i>
                Kondisi Mobil
            </div>

            <div class="checkbox-grid">
                <label><input type="checkbox" name="kondisi[]" value="body_mulus"> Body Mulus</label>
                <label><input type="checkbox" name="kondisi[]" value="ada_goresan"> Ada Goresan</label>
                <label><input type="checkbox" name="kondisi[]" value="penyok"> Penyok</label>
                <label><input type="checkbox" name="kondisi[]" value="lampu_normal"> Lampu Normal</label>
                <label><input type="checkbox" name="kondisi[]" value="ac_normal"> AC Normal</label>
                <label><input type="checkbox" name="kondisi[]" value="spion_normal"> Spion Normal</label>
                <label><input type="checkbox" name="kondisi[]" value="kaca_retak"> Kaca Retak</label>
                <label><input type="checkbox" name="kondisi[]" value="ban_normal"> Ban Normal</label>
                <label><input type="checkbox" name="kondisi[]" value="ban_gundul"> Ban Gundul</label>
                <label><input type="checkbox" name="kondisi[]" value="dongkrak"> Dongkrak</label>
                <label><input type="checkbox" name="kondisi[]" value="ban_serep"> Ban Serep</label>
                <label><input type="checkbox" name="kondisi[]" value="toolkit"> Toolkit</label>
                <label><input type="checkbox" name="kondisi[]" value="plat_lengkap"> Plat Lengkap</label>
                <label><input type="checkbox" name="kondisi[]" value="stnk"> STNK</label>
            </div>

            <!-- BBM & KM -->
            <div class="section-title">
                <i class="fas fa-tachometer-alt"></i>
                BBM & KM
            </div>

            <div class="form-grid">
                <div>
                    <label>BBM <span style="color: var(--primary);">*</span></label>
                    <input type="text" name="bbm" placeholder="Contoh: 3/4 atau Full">
                </div>
                <div>
                    <label>KM <span style="color: var(--primary);">*</span></label>
                    <input type="text" name="km" placeholder="Contoh: 12500">
                </div>
            </div>
            <div class="form-grid pengembalian-extra" style="display:none;">
                <div>
                    <label>Biaya Tambahan</label>
                    <input type="number" name="biaya_tambahan" placeholder="0">
                </div>
                <div>
                    <label>Total Akhir</label>
                    <input type="number" name="total" placeholder="0">
                </div>
            </div>

            <!-- UPLOAD -->
            <div class="section-title">
                <i class="fas fa-upload"></i>
                Upload Dokumen
            </div>

            <div class="upload-grid">
                <div class="upload-item">
                    <label>Dokumen Sewa</label>
                    <input type="file" name="dokumen_sewa">
                </div>
                <div class="upload-item">
                    <label>Foto STNK</label>
                    <input type="file" name="foto_stnk">
                </div>
                <div class="upload-item">
                    <label>Foto Mobil (Depan)</label>
                    <input type="file" name="foto_depan">
                </div>
                <div class="upload-item">
                    <label>Foto Mobil (Belakang)</label>
                    <input type="file" name="foto_belakang">
                </div>
            </div>

            <!-- CATATAN -->
            <div style="margin-top:20px;">
                <label style="font-size: 12px; font-weight: 500; color: #475569; margin-bottom: 6px; display: block;">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" placeholder="Masukkan catatan tambahan..."></textarea>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button class="btn-confirm" id="confirmBtn">
                    <i class="fas fa-check"></i>
                    Konfirmasi Penyerahan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// prepare existing data for javascript
const penyerahanData = @json($penyerahan);
const pengembalianData = @json($pengembalian);

function openModal(type){
    const modal = document.getElementById('modalForm');
    const title = document.getElementById('modalTitle');
    const confirmBtn = document.getElementById('confirmBtn');
    const form = document.getElementById('modalFormElement');

    // reset fields
    form.reset();
    document.querySelectorAll('.pengembalian-extra').forEach(el => el.style.display = 'none');

    if(type === 'penyerahan'){
        title.innerText = 'Form Penyerahan Mobil';
        confirmBtn.innerHTML = '<i class="fas fa-check"></i> Konfirmasi Penyerahan';
        form.action = "{{ route('admin.smartrent.penyerahan.store', $smartRent->id) }}";
        document.querySelector('.section-title i.fa-info-circle').parentElement.innerHTML = 
            '<i class="fas fa-info-circle"></i> Data Penyerahan';

        // fill existing values
        if(penyerahanData){
            form.petugas.value = penyerahanData.petugas || '';
            form.tanggal.value = penyerahanData.tanggal || '';
            form.jam.value = penyerahanData.jam || '';
            form.bbm.value = penyerahanData.bbm || '';
            form.km.value = penyerahanData.km || '';
            form.catatan.value = penyerahanData.catatan || '';
            if(penyerahanData.kondisi){
                penyerahanData.kondisi.forEach(v => {
                    const cb = form.querySelector('input[name="kondisi[]"][value="'+v+'"]');
                    if(cb) cb.checked = true;
                });
            }
        }
    } else {
        title.innerText = 'Form Pengembalian Mobil';
        confirmBtn.innerHTML = '<i class="fas fa-check"></i> Konfirmasi Pengembalian';
        form.action = "{{ route('admin.smartrent.pengembalian.store', $smartRent->id) }}";
        document.querySelector('.section-title i.fa-info-circle').parentElement.innerHTML = 
            '<i class="fas fa-info-circle"></i> Data Pengembalian';
        document.querySelectorAll('.pengembalian-extra').forEach(el => el.style.display = 'block');

        if(pengembalianData){
            form.petugas.value = pengembalianData.petugas || '';
            form.tanggal.value = pengembalianData.tanggal || '';
            form.jam.value = pengembalianData.jam || '';
            form.bbm.value = pengembalianData.bbm || '';
            form.km.value = pengembalianData.km || '';
            form.biaya_tambahan.value = pengembalianData.biaya_tambahan || '';
            form.total.value = pengembalianData.total || '';
            form.catatan.value = pengembalianData.catatan || '';
            if(pengembalianData.kondisi){
                pengembalianData.kondisi.forEach(v => {
                    const cb = form.querySelector('input[name="kondisi[]"][value="'+v+'"]');
                    if(cb) cb.checked = true;
                });
            }
        }
    }

    modal.style.display = 'flex';
}

function closeModal(){
    document.getElementById('modalForm').style.display = 'none';
}

// handle form submission with loading indicator
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmBtn');
    const form = document.getElementById('modalFormElement');
    if(confirmBtn && form) {
        confirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('loading');
            form.submit();
        });
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>
