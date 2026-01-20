@extends('layouts.app-driver')

@section('title', 'Laporan Driver - Smart Shuttle')

@push('styles')
<style>
    :root {
        --primary-color: #0d3559;
        --secondary-color: #ff6a00;
        --accent-color: #2E86AB;
        --background-color: #f5f7fa;
        --text-dark: #333333;
    }

    /* ================= TAB MENU ================= */
    .tab-wrapper {
        background: var(--primary-color);
        padding: 15px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 25px;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 25px;
    }

    .tab-btn {
        padding: 10px 25px;
        border-radius: 20px;
        border: none;
        background: transparent;
        color: white;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .tab-active {
        background: var(--secondary-color);
    }

    .add-btn {
        margin-left: auto;
        background: white;
        color: black;
        border-radius: 20px;
        padding: 10px 24px;
        cursor: pointer;
        border: 2px solid var(--secondary-color);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        background: var(--secondary-color);
        color: white;
    }

    /* ================= TABLE ================= */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        color: var(--text-dark);
    }

    .table-title {
        text-align: center;
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: bold;
        color: var(--primary-color);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background: white;
        color: var(--text-dark);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    th {
        background: var(--primary-color);
        color: white;
        padding: 12px 15px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
    }

    td {
        padding: 12px 15px;
        font-size: 14px;
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }

    tr:hover {
        background-color: #f5f7fa;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-selesai {
        background: #e7f7ef;
        color: #28a745;
    }

    .status-proses {
        background: #fff3cd;
        color: #856404;
    }

    .status-batal {
        background: #f8d7da;
        color: #721c24;
    }

    /* Data yang tersembunyi */
    .data-perjalanan, .data-paket, .data-armada {
        display: none;
    }

    .data-semua {
        display: table-row;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
    }

    .page-btn {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .page-btn:hover {
        background: #f0f0f0;
    }

    .page-active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tab-wrapper {
            flex-wrap: wrap;
            gap: 10px;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        .add-btn {
            margin-left: 0;
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .tab-btn {
            padding: 8px 15px;
            font-size: 13px;
        }

        .add-btn {
            padding: 8px 15px;
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')

<h2>Laporan Driver</h2>
<hr>

<!-- TAB MENU -->
<div class="tab-wrapper">
    <button class="tab-btn tab-active" data-filter="semua">Semua</button>
    <button class="tab-btn" data-filter="perjalanan">Perjalanan</button>
    <button class="tab-btn" data-filter="paket">Paket</button>
    <button class="tab-btn" data-filter="armada">Armada</button>
    <button class="add-btn"><i class="fas fa-download"></i> Unduh Laporan</button>
</div>

<!-- TABLE -->
<div class="table-container">
    <h4 class="table-title">DAFTAR LAPORAN PERJALANAN</h4>

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
            <!-- Data Semua (default tampil) -->
            <tr class="data-semua data-perjalanan">
                <td>1.</td>
                <td>19 - 11 - 2025</td>
                <td>Jakarta - Bandung</td>
                <td>12</td>
                <td>3</td>
                <td>Bus A</td>
                <td><span class="status-badge status-selesai">Selesai</span></td>
            </tr>

            <tr class="data-semua data-perjalanan">
                <td>2.</td>
                <td>19 - 11 - 2025</td>
                <td>Bandung - Jakarta</td>
                <td>10</td>
                <td>0</td>
                <td>Bus A</td>
                <td><span class="status-badge status-selesai">Selesai</span></td>
            </tr>

            <tr class="data-semua data-perjalanan">
                <td>3.</td>
                <td>20 - 11 - 2025</td>
                <td>Jakarta - Surabaya</td>
                <td>8</td>
                <td>5</td>
                <td>Bus B</td>
                <td><span class="status-badge status-proses">Dalam Proses</span></td>
            </tr>

            <!-- Data Paket -->
            <tr class="data-paket">
                <td>4.</td>
                <td>21 - 11 - 2025</td>
                <td>Jakarta - Bandung</td>
                <td>0</td>
                <td>8</td>
                <td>Truk C</td>
                <td><span class="status-badge status-selesai">Selesai</span></td>
            </tr>

            <tr class="data-paket">
                <td>5.</td>
                <td>22 - 11 - 2025</td>
                <td>Bandung - Surabaya</td>
                <td>0</td>
                <td>12</td>
                <td>Truk D</td>
                <td><span class="status-badge status-proses">Dalam Proses</span></td>
            </tr>

            <!-- Data Armada -->
            <tr class="data-armada">
                <td>6.</td>
                <td>23 - 11 - 2025</td>
                <td>Depo - Jakarta</td>
                <td>0</td>
                <td>0</td>
                <td>Bus E</td>
                <td><span class="status-badge status-selesai">Selesai</span></td>
            </tr>

            <tr class="data-armada">
                <td>7.</td>
                <td>24 - 11 - 2025</td>
                <td>Jakarta - Depo</td>
                <td>0</td>
                <td>0</td>
                <td>Bus F</td>
                <td><span class="status-badge status-batal">Dibatalkan</span></td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="page-btn page-active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Laporan Driver loaded');

        // Tab functionality dengan filter
        const tabButtons = document.querySelectorAll('.tab-btn');

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
            });
        });

        // Download button
        const downloadBtn = document.querySelector('.add-btn');
        downloadBtn.addEventListener('click', function() {
            const activeTab = document.querySelector('.tab-active').getAttribute('data-filter');
            alert(`Laporan ${activeTab} akan diunduh dalam format PDF`);
        });
    });
</script>
@endpush
