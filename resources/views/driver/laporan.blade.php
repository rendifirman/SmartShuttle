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

    /* ================= FILTER BAR ================= */
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .filter-bar label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .filter-bar select {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        color: var(--text-dark);
        background: white;
        cursor: pointer;
    }

    .filter-bar select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .filter-btn {
        padding: 8px 20px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background: #1a4a7a;
    }

    /* ================= STATS CARDS ================= */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-card h5 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .stat-card .value {
        font-size: 28px;
        font-weight: bold;
        color: var(--primary-color);
    }

    .stat-card.perjalanan {
        border-left: 4px solid var(--primary-color);
    }

    .stat-card.penumpang {
        border-left: 4px solid #28a745;
    }

    .stat-card.paket {
        border-left: 4px solid var(--secondary-color);
    }

    .stat-card.selesai {
        border-left: 4px solid #17a2b8;
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

    .download-btn {
        margin-left: auto;
        background: white;
        color: black;
        border-radius: 20px;
        padding: 10px 24px;
        cursor: pointer;
        border: 2px solid var(--secondary-color);
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .download-btn:hover {
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .empty-state i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 15px;
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

        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-bar select {
            width: 100%;
        }

        .stats-container {
            grid-template-columns: 1fr 1fr;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        .download-btn {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .tab-btn {
            padding: 8px 15px;
            font-size: 13px;
        }

        .download-btn {
            padding: 8px 15px;
            font-size: 13px;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<h2>Laporan Driver</h2>
<hr>

<!-- FILTER BAR -->
<form method="GET" action="{{ route('driver.laporan') }}" class="filter-bar">
    <div>
        <label for="bulan">Bulan:</label>
        <select name="bulan" id="bulan">
            @foreach($availableMonths as $month)
                <option value="{{ $month['bulan'] }}" {{ $bulan == $month['bulan'] ? 'selected' : '' }}>
                    {{ $month['label'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="tahun">Tahun:</label>
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
    <button type="submit" class="filter-btn">Filter</button>
</form>

<!-- STATS CARDS -->
<div class="stats-container">
    <div class="stat-card perjalanan">
        <h5>Total Perjalanan</h5>
        <div class="value">{{ $totalPerjalanan ?? 0 }}</div>
    </div>
    <div class="stat-card penumpang">
        <h5>Total Penumpang</h5>
        <div class="value">{{ $totalPenumpang ?? 0 }}</div>
    </div>
    <div class="stat-card paket">
        <h5>Total Paket</h5>
        <div class="value">{{ $totalPaket ?? 0 }}</div>
    </div>
    <div class="stat-card selesai">
        <h5>Selesai</h5>
        <div class="value">{{ $totalSelesai ?? 0 }}</div>
    </div>
</div>

<!-- TAB MENU -->
<div class="tab-wrapper">
    <button class="tab-btn tab-active" data-filter="semua">Semua</button>
    <button class="tab-btn" data-filter="perjalanan">Perjalanan</button>
    <button class="tab-btn" data-filter="paket">Paket</button>
    <button class="tab-btn" data-filter="armada">Armada</button>
    <button class="download-btn" onclick="downloadLaporan()">
        <i class="fas fa-download"></i> Unduh Laporan
    </button>
</div>

<!-- TABLE -->
<div class="table-container">
    <h4 class="table-title">DAFTAR LAPORAN PERJALANAN</h4>

    @if(count($laporanData) > 0)
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
                <td>{{ $index + 1 }}.</td>
                <td>{{ $data['tanggal'] }}</td>
                <td>{{ $data['rute'] }}</td>
                <td>{{ $data['penumpang'] }}</td>
                <td>{{ $data['paket'] }}</td>
                <td>{{ $data['armada'] }}</td>
                <td>
                    @if($data['status_raw'] == 'selesai')
                        <span class="status-badge status-selesai">Selesai</span>
                    @elseif($data['status_raw'] == 'aktif' || $data['status_raw'] == 'dalam_perjalanan')
                        <span class="status-badge status-proses">Dalam Proses</span>
                    @elseif($data['status_raw'] == 'dibatalkan')
                        <span class="status-badge status-batal">Dibatalkan</span>
                    @else
                        <span class="status-badge">{{ $data['status'] }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
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
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>Tidak ada data laporan untuk periode yang dipilih.</p>
    </div>
    @endif
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
    });

    // Download function
    function downloadLaporan() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;

        // Create a simple CSV download
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "No,Tanggal,Rute,Penumpang,Paket,Armada,Status\n";

        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                let rowData = [];
                cells.forEach(cell => {
                    rowData.push(cell.innerText.replace(/\n/g, ' ').trim());
                });
                csvContent += (index + 1) + "," + rowData.join(",") + "\n";
            }
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "laporan_driver_" + bulan + "_" + tahun + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush
