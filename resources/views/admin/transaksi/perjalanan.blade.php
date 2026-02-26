@extends('layouts.app-admin')

@section('content')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* ===== ADMIN TRANSAKSI PERJALANAN STYLE ===== */
    .admin-transaksi-container {
        padding: 20px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Header Section */
    .transaksi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .transaksi-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #00215E;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-admin-primary {
        background: #00215E;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-admin-primary:hover {
        background: #001a47;
        color: white;
    }

    .badge-admin {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    /* Tab Navigation */
    .transaksi-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }

    .tab-nav {
        padding: 12px 20px;
        background: white;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .tab-nav.active {
        color: #FF581E;
        border-bottom-color: #FF581E;
    }

    .tab-nav:hover {
        color: #FF581E;
    }

    /* Search & Filter */
    .search-filter-section {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
    }

    /* Table Styles */
    .table-responsive-admin {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background: #00215E;
        color: white;
    }

    table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        font-size: 13px;
    }

    table tbody tr:hover {
        background: #f8f9fa;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-view, .btn-edit, .btn-delete {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-view {
        background: #0dcaf0;
        color: white;
    }

    .btn-view:hover {
        background: #0ba5d3;
    }

    .btn-edit {
        background: #0d6efd;
        color: white;
    }

    .btn-edit:hover {
        background: #0b5ed7;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #bb2d3b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    /* Finance cards */
    .finance-cards {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .finance-card {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        min-width: 220px;
        flex: 1 1 220px;
    }

    .finance-card h3 { margin: 0; font-size: 20px; color: #00215E; }
    .finance-card p { margin: 6px 0 0; color: #6c757d; font-weight: 600; }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #00215E;
    }

    /* Pagination */
    .pagination-section {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .pagination-btn {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .pagination-btn.active {
        background: #00215E;
        color: white;
        border-color: #00215E;
    }

    /* Modal Booking */
    .modal-header-admin {
        background: #00215E;
        color: white;
        border: none;
    }

    .modal-header-admin .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Booking Form Styles */
    .booking-form-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-section-title {
        color: #00215E;
        border-bottom: 2px solid #FF581E;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #FF581E;
        box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .transaksi-header {
            flex-direction: column;
        }
        .header-actions {
            width: 100%;
        }
    }

    /* Response Messages */
    .alert-admin {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
</style>

<div class="admin-transaksi-container">
    <!-- Header -->
    <div class="transaksi-header">
        <div>
            <h1><i class="fas fa-receipt"></i> Transaksi Perjalanan</h1>
            <p style="color: #6c757d; margin-top: 5px; font-size: 14px;">Kelola dan lihat semua transaksi pemesanan tiket shuttle</p>
        </div>
        <div class="header-actions">
                <a href="{{ route('admin.booking') }}" class="btn-admin-primary" style="text-decoration: none; display: inline-flex;">
                <i class="fas fa-plus-circle"></i>
                Pesan Untuk Customer
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert-admin alert-success">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Finance Summary -->
    @php $fs = $financeSummary ?? null; @endphp
    @if($fs)
    <div class="finance-cards">
        <div class="finance-card">
            <h3>Rp {{ number_format($fs['totalRevenue'] ?? 0, 0, ',', '.') }}</h3>
            <p>Total Pendapatan (sudah dibayar)</p>
        </div>
        <div class="finance-card">
            <h3>{{ $fs['paidCount'] ?? 0 }}</h3>
            <p>Transaksi Berhasil (count)</p>
        </div>
        <div class="finance-card">
            <h3>{{ number_format(array_sum($fs['values7'] ?? []), 0, ',', '.') }}</h3>
            <p>Pendapatan 7 Hari Terakhir</p>
        </div>
    </div>

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
        <div style="flex:1 1 420px; background:white; padding:12px; border-radius:8px;">
            <h4 style="margin:0 0 8px;">Pendapatan - 7 Hari</h4>
            <canvas id="chart7days" height="120"></canvas>
        </div>
        <div style="flex:1 1 420px; background:white; padding:12px; border-radius:8px;">
            <h4 style="margin:0 0 8px;">Pendapatan - 6 Bulan</h4>
            <canvas id="chart6months" height="120"></canvas>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-admin alert-error">
        <i class="fas fa-times-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="transaksi-tabs">
        <button class="tab-nav active" onclick="switchTab('semua')">
            <i class="fas fa-list"></i> Semua Transaksi
        </button>
        <button class="tab-nav" onclick="switchTab('pending')">
            <i class="fas fa-clock"></i> Pending
        </button>
        <button class="tab-nav" onclick="switchTab('completed')">
            <i class="fas fa-check-circle"></i> Selesai
        </button>
        <button class="tab-nav" onclick="switchTab('cancelled')">
            <i class="fas fa-ban"></i> Dibatalkan
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="search-filter-section">
        <input type="text" class="search-input" id="searchInput" placeholder="Cari kode booking, nama customer, atau nomor handphone...">
        <select class="filter-select" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    <!-- Transaksi Table -->
    <div class="table-responsive-admin">
        <table id="transaksiTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Customer</th>
                    <th>Rute</th>
                    <th>Tanggal</th>
                    <th>Penumpang</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemesanan as $index => $transaksi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $transaksi->kode_booking ?? 'N/A' }}</strong>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $transaksi->nama_pemesan ?? $transaksi->user->name ?? 'N/A' }}</div>
                        <small style="color: #6c757d;">{{ $transaksi->telepon_pemesan ?? $transaksi->user->phone ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($transaksi->jadwal && $transaksi->jadwal->rutes)
                            @php
                                $rutes = $transaksi->jadwal->rutes;
                                $rutePertama = $rutes->first();
                                $ruteTerakhir = $rutes->last();
                            @endphp
                            <div style="font-weight: 600;">
                                {{ $rutePertama->kota_asal ?? 'Kota Asal' }} →
                                {{ $ruteTerakhir->kota_tujuan ?? 'Kota Tujuan' }}
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($transaksi->jadwal)
                            {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal_keberangkatan)->locale('id')->format('d M Y') }}<br>
                            <small style="color: #6c757d;">{{ \Carbon\Carbon::parse($transaksi->jadwal->waktu_keberangkatan)->format('H:i') }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 600;">{{ $transaksi->jumlah_penumpang ?? 1 }} Orang</span>
                    </td>
                    <td>
                        <strong style="color: #FF581E;">Rp {{ number_format($transaksi->total_bayar ?? $transaksi->harga_total ?? 0, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($transaksi->status == 'pending')
                            <span class="badge-admin badge-pending">PENDING</span>
                        @elseif($transaksi->status == 'completed' || $transaksi->status == 'selesai')
                            <span class="badge-admin badge-completed">SELESAI</span>
                        @elseif($transaksi->status == 'cancelled' || $transaksi->status == 'dibatalkan')
                            <span class="badge-admin badge-cancelled">DIBATALKAN</span>
                        @else
                            <span class="badge-admin" style="background: #e9ecef; color: #495057;">{{ strtoupper($transaksi->status ?? 'UNKNOWN') }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="javascript:void(0)" onclick="viewTransaksi('{{ $transaksi->kode_booking }}')" class="btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editTransaksi('{{ $transaksi->id }}')" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteTransaksi('{{ $transaksi->id }}')" class="btn-delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Belum Ada Transaksi</h3>
                            <p>Silakan klik tombol "Pesan Untuk Customer" untuk membuat pemesanan baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($pemesanan->hasPages())
    <div class="pagination-section">
        {{ $pemesanan->links() }}
    </div>
    @endif
</div>

<!-- Modal: Pesan Untuk Customer -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-admin">
                <h5 class="modal-title">
                    <i class="fas fa-ticket-alt"></i> Pesan Tiket Untuk Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adminBookingForm">
                    <!-- Pilih Customer -->
                    <div class="booking-form-section">
                        <h6 class="form-section-title">Data Customer</h6>

                        <div class="form-group">
                            <label class="form-label">Pilih Customer atau Buat Baru</label>
                            <select class="form-control" id="customerSelect">
                                <option value="">-- Cari Customer --</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" data-email="{{ $customer->email }}">
                                    {{ $customer->name }} ({{ $customer->phone }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="namaPemesan" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Handphone <span style="color: red;">*</span></label>
                                <input type="tel" class="form-control" id="teleponPemesan" placeholder="081234567890" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="emailPemesan" placeholder="email@contoh.com" required>
                        </div>
                    </div>

                    <!-- Pilih Jadwal -->
                    <div class="booking-form-section">
                        <h6 class="form-section-title">Pilih Jadwal Perjalanan</h6>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Rute <span style="color: red;">*</span></label>
                                <select class="form-control" id="ruteSelect" required onchange="loadJadwal()">
                                    <option value="">-- Pilih Rute --</option>
                                    @foreach($rutes as $rute)
                                    <option value="{{ $rute->id }}">
                                        {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal <span style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="tanggalKeberangkatan" onchange="loadJadwal()" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jadwal (Waktu & Shuttle) <span style="color: red;">*</span></label>
                            <select class="form-control" id="jadwalSelect" required onchange="loadJadwalDetails()">
                                <option value="">-- Pilih Jadwal --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Data Penumpang -->
                    <div class="booking-form-section">
                        <h6 class="form-section-title">Jumlah Penumpang</h6>

                        <div class="form-group">
                            <label class="form-label">Jumlah Penumpang <span style="color: red;">*</span></label>
                            <input type="number" class="form-control" id="jumlahPenumpang" min="1" max="10" value="1" required onchange="loadPenumpangForm()">
                            <small style="color: #6c757d; margin-top: 5px; display: block;">Minimal 1, maksimal 10 penumpang</small>
                        </div>
                    </div>

                    <!-- Detail Penumpang -->
                    <div id="detailPenumpangContainer"></div>

                    <!-- Promo Section -->
                    <div class="booking-form-section">
                        <h6 class="form-section-title">Kode Promo (Opsional)</h6>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Kode Promo</label>
                                <input type="text" class="form-control" id="kodePromo" placeholder="Masukkan kode promo">
                            </div>
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="form-control" style="background: #FF581E; color: white; cursor: pointer; font-weight: 600;" onclick="validatePromo()">
                                    <i class="fas fa-check"></i> Validasi
                                </button>
                            </div>
                        </div>

                        <div id="promoResult" style="display: none; margin-top: 10px; padding: 10px; border-radius: 6px;"></div>
                    </div>

                    <!-- Catatan -->
                    <div class="booking-form-section">
                        <h6 class="form-section-title">Catatan (Opsional)</h6>

                        <div class="form-group">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" id="catatan" rows="3" placeholder="Masukkan catatan tambahan jika ada..."></textarea>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="booking-form-section" style="background: #F0F4F8; border: 2px solid #00215E;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="color: #666;">Total Penumpang:</span>
                            <span style="font-weight: 600; font-size: 16px;" id="summaryPenumpang">0 Orang</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="color: #666;">Subtotal:</span>
                            <span style="font-weight: 600; font-size: 16px;" id="summarySubtotal">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #ddd;">
                            <span style="color: #00215E; font-weight: 600;">Total Bayar:</span>
                            <span style="color: #FF581E; font-weight: 700; font-size: 18px;" id="summaryTotal">Rp 0</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn" style="background: #00215E; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="submitAdminBooking()">
                    <i class="fas fa-check-circle"></i> Lanjutkan Pemesanan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize datepicker if needed
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'), {});

    function openNewBookingModal() {
        // Open new admin booking flow page
        window.location.href = '/admin/transaksi/pemesanan-baru';
    }

    // Handle customer selection
    document.getElementById('customerSelect').addEventListener('change', function() {
        if(this.value) {
            const option = this.options[this.selectedIndex];
            document.getElementById('namaPemesan').value = option.dataset.name;
            document.getElementById('teleponPemesan').value = option.dataset.phone;
            document.getElementById('emailPemesan').value = option.dataset.email;
        }
    });

    // Handle jumlah penumpang change
    document.getElementById('jumlahPenumpang').addEventListener('change', function() {
        loadPenumpangForm();
        updateSummary();
    });

    // Load jadwal based on selected rute and date
    function loadJadwal() {
        const rute_id = document.getElementById('ruteSelect').value;
        const tanggal = document.getElementById('tanggalKeberangkatan').value;

        if(!rute_id || !tanggal) {
            document.getElementById('jadwalSelect').innerHTML = '<option value="">-- Pilih Jadwal --</option>';
            return;
        }

        // Fetch available jadwal
        fetch(`/admin/api/jadwal?rute_id=${rute_id}&tanggal=${tanggal}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Jadwal --</option>';
                if(data.jadwal && data.jadwal.length > 0) {
                    data.jadwal.forEach(jadwal => {
                        options += `<option value="${jadwal.id}" data-harga="${jadwal.harga_total}" data-shuttle="${jadwal.shuttle.nama_shuttle}">${jadwal.waktu_keberangkatan} - ${jadwal.shuttle.nama_shuttle}</option>`;
                    });
                } else {
                    options = '<option value="">-- Tidak ada jadwal tersedia --</option>';
                }
                document.getElementById('jadwalSelect').innerHTML = options;
                // Add listener untuk jadwal change
                document.getElementById('jadwalSelect').addEventListener('change', function() {
                    updateSummary();
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Load jadwal details
    function loadJadwalDetails() {
        const jadwalSelect = document.getElementById('jadwalSelect');
        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];

        // Update summary
        updateSummary();
    }

    // Load penumpang form
    function loadPenumpangForm() {
        const jumlah = parseInt(document.getElementById('jumlahPenumpang').value);
        let html = '';

        for(let i = 1; i <= jumlah; i++) {
            html += `
            <div class="booking-form-section">
                <h6 class="form-section-title">Penumpang ${i}</h6>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" class="form-control penumpang-nama" placeholder="Masukkan nama lengkap penumpang" data-index="${i}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIK <span style="color: red;">*</span></label>
                        <input type="text" class="form-control penumpang-nik" placeholder="16 digit NIK" minlength="16" maxlength="16" data-index="${i}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                        <select class="form-control penumpang-jk" data-index="${i}" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon <span style="color: red;">*</span></label>
                    <input type="tel" class="form-control penumpang-telepon" placeholder="081234567890" data-index="${i}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color: red;">*</span></label>
                    <input type="email" class="form-control penumpang-email" placeholder="email@contoh.com" data-index="${i}" required>
                </div>
            </div>
            `;
        }

        document.getElementById('detailPenumpangContainer').innerHTML = html;
        document.getElementById('summaryPenumpang').textContent = jumlah + ' Orang';
        updateSummary();
    }

    // Validate promo
    function validatePromo() {
        const kodePromo = document.getElementById('kodePromo').value;
        if(!kodePromo) {
            alert('Masukkan kode promo terlebih dahulu');
            return;
        }

        // Fetch promo validation
        fetch(`/admin/api/promo/validate?kode=${kodePromo}`)
            .then(response => response.json())
            .then(data => {
                const result = document.getElementById('promoResult');
                if(data.valid) {
                    result.style.display = 'block';
                    result.style.background = '#d4edda';
                    result.style.color = '#155724';
                    result.style.borderLeft = '4px solid #28a745';
                    result.innerHTML = `<i class="fas fa-check-circle"></i>&nbsp;Promo "${data.nama}" berlaku! Diskon ${data.diskon}%`;
                    // Store in hidden field for submission
                    document.getElementById('promoResult').dataset.valid = 'true';
                    document.getElementById('promoResult').dataset.diskon = data.diskon;
                    updateSummary();
                } else {
                    result.style.display = 'block';
                    result.style.background = '#f8d7da';
                    result.style.color = '#721c24';
                    result.style.borderLeft = '4px solid #dc3545';
                    result.innerHTML = `<i class="fas fa-times-circle"></i>&nbsp;${data.message || 'Kode promo tidak valid'}`;
                    document.getElementById('promoResult').dataset.valid = 'false';
                    document.getElementById('promoResult').dataset.diskon = '0';
                    updateSummary();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Update summary
    function updateSummary() {
        const jadwalSelect = document.getElementById('jadwalSelect');
        const selectedIndex = jadwalSelect.selectedIndex;

        if(selectedIndex <= 0) {
            document.getElementById('summarySubtotal').textContent = 'Rp 0';
            document.getElementById('summaryTotal').textContent = 'Rp 0';
            return;
        }

        const selectedOption = jadwalSelect.options[selectedIndex];
        const hargaPerSeat = parseFloat(selectedOption.dataset.harga) || 0;
        const jumlah = parseInt(document.getElementById('jumlahPenumpang').value) || 0;

        if(hargaPerSeat <= 0 || jumlah <= 0) {
            document.getElementById('summarySubtotal').textContent = 'Rp 0';
            document.getElementById('summaryTotal').textContent = 'Rp 0';
            return;
        }

        const subtotal = hargaPerSeat * jumlah;
        const diskonPercent = parseFloat(document.getElementById('promoResult').dataset.diskon || 0);
        const diskonAmount = (subtotal * diskonPercent) / 100;
        const total = subtotal - diskonAmount;

        // Format and update display
        document.getElementById('summarySubtotal').textContent = 'Rp ' + Math.round(subtotal).toLocaleString('id-ID');
        document.getElementById('summaryTotal').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');

        console.log('Summary Updated:', {
            hargaPerSeat: hargaPerSeat,
            jumlah: jumlah,
            subtotal: subtotal,
            diskonPercent: diskonPercent,
            diskonAmount: diskonAmount,
            total: total
        });
    }

    // Submit form
    function submitAdminBooking() {
        // Validate required fields
        const form = document.getElementById('adminBookingForm');
        if(!form.checkValidity()) {
            alert('Mohon isi semua field yang diperlukan');
            return;
        }

        // Collect penumpang data
        const penumpangData = [];
        const penumpangNama = document.querySelectorAll('.penumpang-nama');

        penumpangNama.forEach((nama, index) => {
            penumpangData.push({
                nama_lengkap: nama.value,
                nik: document.querySelectorAll('.penumpang-nik')[index].value,
                jenis_kelamin: document.querySelectorAll('.penumpang-jk')[index].value,
                telepon: document.querySelectorAll('.penumpang-telepon')[index].value,
                email: document.querySelectorAll('.penumpang-email')[index].value,
            });
        });

        // Create booking data
        const bookingData = {
            customer_id: document.getElementById('customerSelect').value || null,
            nama_pemesan: document.getElementById('namaPemesan').value,
            telepon_pemesan: document.getElementById('teleponPemesan').value,
            email_pemesan: document.getElementById('emailPemesan').value,
            jadwal_id: document.getElementById('jadwalSelect').value,
            jumlah_penumpang: document.getElementById('jumlahPenumpang').value,
            penumpang: penumpangData,
            kode_promo: document.getElementById('kodePromo').value,
            catatan: document.getElementById('catatan').value,
        };

        // Submit
        fetch('/admin/api/pemesanan/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Pemesanan berhasil dibuat!');
                bookingModal.hide();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Gagal membuat pemesanan'));
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // View transaksi
    function viewTransaksi(kodeBooking) {
        window.location.href = `/admin/transaksi/perjalanan/${kodeBooking}`;
    }

    // Edit transaksi
    function editTransaksi(id) {
        window.location.href = `/admin/transaksi/perjalanan/${id}/edit`;
    }

    // Delete transaksi
    function deleteTransaksi(id) {
        if(confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
            fetch(`/admin/api/pemesanan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Transaksi berhasil dihapus');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal menghapus transaksi'));
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Switch tab
    function switchTab(tab) {
        const tabs = document.querySelectorAll('.tab-nav');
        tabs.forEach(t => t.classList.remove('active'));
        event.target.classList.add('active');

        // Filter table
        const table = document.getElementById('transaksiTable').getElementsByTagName('tbody')[0];
        const rows = table.getElementsByTagName('tr');

        rows.forEach(row => {
            let status = row.querySelector('td:nth-child(8)').textContent.toLowerCase();

            if(tab === 'semua') {
                row.style.display = '';
            } else if(status.includes(tab)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Search filter
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.getElementById('transaksiTable').getElementsByTagName('tbody')[0];
        const rows = table.getElementsByTagName('tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Status filter
    document.getElementById('filterStatus').addEventListener('change', function(e) {
        const selectedStatus = e.target.value;
        const table = document.getElementById('transaksiTable').getElementsByTagName('tbody')[0];
        const rows = table.getElementsByTagName('tr');

        rows.forEach(row => {
            const status = row.querySelector('td:nth-child(8)').textContent.toLowerCase();
            row.style.display = !selectedStatus || status.includes(selectedStatus) ? '' : 'none';
        });
    });
</script>

@if(isset($financeSummary))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function(){
        const fs = @json($financeSummary);

        // 7-day chart
        const ctx7 = document.getElementById('chart7days');
        if(ctx7 && fs.labels7) {
            new Chart(ctx7.getContext('2d'), {
                type: 'line',
                data: {
                    labels: fs.labels7,
                    datasets: [{
                        label: 'Pendapatan (IDR)',
                        data: fs.values7,
                        borderColor: '#FF581E',
                        backgroundColor: 'rgba(255,88,30,0.12)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // 6-month chart
        const ctx6 = document.getElementById('chart6months');
        if(ctx6 && fs.monthLabels) {
            new Chart(ctx6.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: fs.monthLabels,
                    datasets: [{
                        label: 'Pendapatan (IDR)',
                        data: fs.monthValues,
                        backgroundColor: '#00215E'
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }
    })();
</script>
@endif

@endsection
