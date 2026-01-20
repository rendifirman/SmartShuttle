@extends('layouts.app-driver')

@section('title', 'Jadwal Driver - Smart Shuttle')

@section('page-title', 'Jadwal Driver')

@push('styles')
<style>
    /* Define CSS Variables */
    :root {
        --primary: #3498db;
        --secondary: #2c3e50;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light: #f5f7fa;
        --dark: #2c3e50;
        --gray: #7f8c8d;
    }

    /* Main Content Styling */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title h1 {
        font-size: 28px;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .page-title p {
        color: #7f8c8d;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    /* Tabs */
    .tabs {
        display: flex;
        background: white;
        border-radius: 10px;
        padding: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .tab {
        flex: 1;
        padding: 15px 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s;
        font-weight: 500;
        color: #7f8c8d;
    }

    .tab.active {
        background: #3498db;
        color: white;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Schedule Cards */
    .schedule-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 25px;
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
        border: 1px solid #e0e0e0;
    }

    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .schedule-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .schedule-header.ticket {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .schedule-header.package {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .schedule-header.fleet {
        background: linear-gradient(135deg, #f39c12, #e67e22);
    }

    .schedule-title {
        font-size: 18px;
        font-weight: 600;
    }

    .schedule-status {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .schedule-body {
        padding: 20px;
    }

    .schedule-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }

    .info-icon.ticket {
        background: #3498db;
    }

    .info-icon.package {
        background: #2ecc71;
    }

    .info-icon.fleet {
        background: #f39c12;
    }

    .info-text {
        flex: 1;
    }

    .info-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: #2c3e50;
    }

    .schedule-footer {
        padding: 15px 20px;
        background: #f9f9f9;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .driver-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .driver-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }

    .driver-name {
        font-size: 14px;
        font-weight: 500;
        color: #2c3e50;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .action-btn.view {
        background: #e8f4fd;
        color: #3498db;
        border: 1px solid #3498db;
    }

    .action-btn.view:hover {
        background: #3498db;
        color: white;
    }

    .action-btn.edit {
        background: #fff8e1;
        color: #f39c12;
        border: 1px solid #f39c12;
    }

    .action-btn.edit:hover {
        background: #f39c12;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #bdc3c7;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .schedule-container {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .tabs {
            flex-direction: column;
        }

        .tab {
            padding: 12px 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Jadwal Driver</h1>
        <p>Kelola jadwal untuk semua tugas driver</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jadwal
    </button>
</div>

<!-- Tabs -->
<div class="tabs">
    <div class="tab active" data-tab="all">Semua Tugas</div>
    <div class="tab" data-tab="ticket">Pesan Tiket</div>
    <div class="tab" data-tab="package">Kirim Paket</div>
    <div class="tab" data-tab="fleet">Armada</div>
</div>

<!-- All Tasks Tab -->
<div class="tab-content active" id="all-tab">
    <div class="schedule-container">
        <!-- Tiket Antar Kota -->
        <div class="schedule-card">
            <div class="schedule-header ticket">
                <div class="schedule-title">Pesan Tiket - Jakarta ke Bandung</div>
                <div class="schedule-status">Dalam Perjalanan</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon ticket">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute</div>
                            <div class="info-value">Jakarta - Bandung</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon ticket">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Waktu Keberangkatan</div>
                            <div class="info-value">08:00 - 12:00</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon ticket">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Penumpang</div>
                            <div class="info-value">25/30 terisi</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon ticket">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Armada</div>
                            <div class="info-value">Bus Mewah - B 1234 XYZ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    <div class="driver-avatar">DM</div>
                    <div class="driver-name">Dimas Mahendra</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view">Detail</button>
                    <button class="action-btn edit">Update</button>
                </div>
            </div>
        </div>

        <!-- Kirim Paket -->
        <div class="schedule-card">
            <div class="schedule-header package">
                <div class="schedule-title">Kirim Paket - Surabaya ke Malang</div>
                <div class="schedule-status">Menunggu Pickup</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon package">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Jenis Paket</div>
                            <div class="info-value">Dokumen Penting - 2kg</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon package">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Rute Pengiriman</div>
                            <div class="info-value">Surabaya Pusat - Malang Kota</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon package">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Estimasi Waktu</div>
                            <div class="info-value">3-4 jam</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon package">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Kendaraan</div>
                            <div class="info-value">Mobil Box - B 5678 ABC</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    <div class="driver-avatar">BS</div>
                    <div class="driver-name">Budi Santoso</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view">Lacak</button>
                    <button class="action-btn edit">Update</button>
                </div>
            </div>
        </div>

        <!-- Armada -->
        <div class="schedule-card">
            <div class="schedule-header fleet">
                <div class="schedule-title">Perawatan Armada - Bus Mewah</div>
                <div class="schedule-status">Terjadwal</div>
            </div>
            <div class="schedule-body">
                <div class="schedule-info">
                    <div class="info-row">
                        <div class="info-icon fleet">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Armada</div>
                            <div class="info-value">Bus Mewah - B 1234 XYZ</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon fleet">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Jenis Perawatan</div>
                            <div class="info-value">Service Rutin 10.000 km</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon fleet">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Jadwal</div>
                            <div class="info-value">15 Nov 2023, 09:00 WIB</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon fleet">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-text">
                            <div class="info-label">Lokasi</div>
                            <div class="info-value">Bengkel Pusat - Jakarta</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-footer">
                <div class="driver-info">
                    <div class="driver-avatar">RS</div>
                    <div class="driver-name">Rudi Santoso</div>
                </div>
                <div class="action-buttons">
                    <button class="action-btn view">Detail</button>
                    <button class="action-btn edit">Jadwalkan Ulang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tiket Tab -->
<div class="tab-content" id="ticket-tab">
    <div class="schedule-container">
        <div class="empty-state">
            <i class="fas fa-ticket-alt"></i>
            <h3>Tidak ada jadwal tiket hari ini</h3>
            <p>Jadwal untuk pesan tiket akan muncul di sini</p>
        </div>
    </div>
</div>

<!-- Package Tab -->
<div class="tab-content" id="package-tab">
    <div class="schedule-container">
        <div class="empty-state">
            <i class="fas fa-box"></i>
            <h3>Tidak ada pengiriman paket hari ini</h3>
            <p>Jadwal untuk kirim paket akan muncul di sini</p>
        </div>
    </div>
</div>

<!-- Fleet Tab -->
<div class="tab-content" id="fleet-tab">
    <div class="schedule-container">
        <div class="empty-state">
            <i class="fas fa-bus"></i>
            <h3>Tidak ada jadwal armada hari ini</h3>
            <p>Jadwal untuk armada akan muncul di sini</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to current tab and content
                this.classList.add('active');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });

        // Action buttons functionality
        const viewButtons = document.querySelectorAll('.action-btn.view');
        const editButtons = document.querySelectorAll('.action-btn.edit');

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.schedule-card');
                const title = card.querySelector('.schedule-title').textContent;
                alert(`Melihat detail: ${title}`);
            });
        });

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.schedule-card');
                const title = card.querySelector('.schedule-title').textContent;
                alert(`Mengupdate: ${title}`);
            });
        });
    });
</script>
@endpush
