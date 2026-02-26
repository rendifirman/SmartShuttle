@extends('layouts.app-admin')

@section('content')
<style>
/* ================= DASHBOARD ================= */
.dashboard {
    padding: 16px;
    background: #f8f8f6;
}

/* HEADER */
.dashboard-header {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

.dashboard-header h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.search-box {
    position: relative;
    width: 100%;
}

.search-box input {
    width: 100%;
    padding: 12px 44px 12px 16px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.search-box .icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: 0.6;
}

.branch-info {
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    text-align: center;
}

/* SUMMARY */
.summary {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
}

.summary-card h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.summary-card p {
    margin-top: 6px;
    font-size: 13px;
    color: #6b7280;
}

/* GRID */
.content-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

/* CARD */
.card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    overflow: hidden;
}

.card h4 {
    margin: 0 0 16px;
    font-size: 16px;
    font-weight: 600;
}

/* CHART CONTAINER */
.chart-container {
    position: relative;
    height: 250px;
    margin-top: 10px;
    width: 100%;
}

.chart-header {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.chart-filters {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 4px;
    -webkit-overflow-scrolling: touch;
}

.chart-filters::-webkit-scrollbar {
    display: none;
}

.chart-filter {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #fff;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    flex-shrink: 0;
}

.chart-filter.active {
    background: #ff6a21;
    color: white;
    border-color: #ff6a21;
}

.chart-filter:hover {
    border-color: #ff6a21;
}

.chart-stats {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stat-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    flex-shrink: 0;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    flex-shrink: 0;
}

.stat-value {
    font-size: 14px;
    font-weight: 600;
    margin-left: auto;
    text-align: right;
}

/* ORANGE CARD */
.card.highlight {
    background: #ff6a21;
    color: #ffffff;
}

.card.highlight h4 {
    color: #ffffff;
}

/* ROUTE LIST */
.route-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.route-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    padding: 12px 0;
}

.route-list li:not(:last-child) {
    border-bottom: 1px solid rgba(255,255,255,0.25);
}

/* TABLE CONTAINER */
.table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-top: 10px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.table thead {
    background: #0f2a44;
    color: #ffffff;
}

.table th,
.table td {
    padding: 12px 14px;
    font-size: 13px;
    text-align: left;
    white-space: nowrap;
}

.table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.3s;
}

.table tbody tr:hover {
    background: #f9fafb;
}

/* BADGE */
.badge {
    background: #60a5fa;
    color: #ffffff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge.departed {
    background: #4da3ff;
}

.badge.in-progress {
    background: #f59e0b;
}

.badge.completed {
    background: #10b981;
}

.badge.scheduled {
    background: #8b5cf6;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* RESPONSIVE FOR TABLET */
@media (min-width: 768px) {
    .dashboard {
        padding: 20px 24px 32px;
    }

    .summary {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .summary-card {
        padding: 32px 24px;
    }

    .content-grid {
        flex-direction: row;
        gap: 20px;
    }

    .content-grid > .card {
        flex: 1;
    }

    .chart-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .chart-filters {
        overflow-x: visible;
    }

    .chart-stats {
        flex-direction: row;
        gap: 20px;
    }

    .stat-value {
        margin-left: 4px;
    }

    .branch-info {
        text-align: left;
        padding: 12px 16px;
    }
}

/* RESPONSIVE FOR DESKTOP */
@media (min-width: 1024px) {
    .dashboard {
        padding: 24px 32px 40px;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 4fr 3fr;
        gap: 22px;
    }

    .summary {
        gap: 22px;
        margin-bottom: 28px;
    }

    .summary-card {
        padding: 42px;
    }

    .dashboard-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .search-box {
        max-width: 300px;
    }

    .table {
        min-width: 100%;
    }
}

/* RESPONSIVE FOR LARGE DESKTOP */
@media (min-width: 1280px) {
    .search-box {
        max-width: 400px;
    }
}

/* TOUCH FRIENDLY FOR MOBILE */
@media (max-width: 767px) {
    .chart-filter {
        padding: 10px 16px;
        font-size: 13px;
    }

    .table th,
    .table td {
        padding: 10px 12px;
        font-size: 12px;
    }

    .badge {
        padding: 6px 12px;
        font-size: 11px;
    }

    .route-list li {
        font-size: 13px;
        padding: 10px 0;
    }
}

/* IMPROVE TOUCH TARGETS */
@media (max-width: 480px) {
    .summary-card {
        padding: 20px;
    }

    .card {
        padding: 16px;
    }

    .chart-container {
        height: 220px;
    }

    .stat-item {
        font-size: 11px;
    }
}
</style>

<!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard">

    {{-- HEADER --}}
    <div class="dashboard-header">
        <div class="search-box">
            <input type="text" placeholder="Search">
            <span class="icon">🔍</span>
        </div>
        @if(Auth::guard('admin')->user()->hasRole('admin_cabang'))
        <div class="branch-info">
            <strong>Cabang: {{ Auth::guard('admin')->user()->branch->nama_cabang ?? 'Tidak Ditentukan' }}</strong>
        </div>
        @endif
    </div>

    {{-- SUMMARY CARD --}}
    <div class="summary">
        <div class="summary-card">
            <h3>{{ $totalPerjalanan ?? 0 }}</h3>
            <p>Total perjalanan hari ini</p>
        </div>

        <div class="summary-card">
            <h3>{{ $totalPenumpang ?? 0 }}</h3>
            <p>Total penumpang hari ini</p>
        </div>

        <div class="summary-card">
            <h3>{{ $formatRupiah($totalPendapatan ?? 0) }}</h3>
            <p>Total pendapatan hari ini</p>
        </div>
    </div>

    {{-- GRAFIK & RUTE --}}
    <div class="content-grid">
        <div class="card">
            <div class="chart-header">
                <h4>Grafik Penjualan</h4>
                <div class="chart-filters">
                    <button class="chart-filter active" data-period="daily">Harian</button>
                    <button class="chart-filter" data-period="weekly">Mingguan</button>
                    <button class="chart-filter" data-period="monthly">Bulanan</button>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>

            <div class="chart-stats">
                <div class="stat-item">
                    <div class="stat-color" style="background: #4da3ff;"></div>
                    <span class="stat-label">Smart Shuttle:</span>
                    <span class="stat-value" id="stat-shuttle">Rp 0</span>
                </div>
                <div class="stat-item">
                    <div class="stat-color" style="background: #ff6a21;"></div>
                    <span class="stat-label">SmartSend:</span>
                    <span class="stat-value" id="stat-send">Rp 0</span>
                </div>
                <div class="stat-item">
                    <div class="stat-color" style="background: #10b981;"></div>
                    <span class="stat-label">SmartRent:</span>
                    <span class="stat-value" id="stat-rent">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="card highlight">
            <h4>Rute Terpopuler</h4>

            @if($rutePopuler->count() > 0)
            <ul class="route-list">
                @foreach($rutePopuler as $rute)
                <li>
                    <span>{{ $rute['nama'] ?? 'Rute Tidak Diketahui' }}</span>
                    <strong>{{ $rute['total'] ?? 0 }}</strong>
                </li>
                @endforeach
            </ul>
            @else
            <div class="empty-state">
                <p>Belum ada data rute populer</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <h4>Perjalanan Hari Ini</h4>

        @if($perjalananHariIni->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Rute</th>
                        <th>Kode Armada</th>
                        <th>Driver</th>
                        <th>Kursi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perjalananHariIni as $jadwal)
                    <tr>
                        <td>{{ $jadwal->waktu_keberangkatan ?? '-' }}</td>
                        <td>
                            @if($jadwal->rutes && $jadwal->rutes->count() > 0)
                                {{ $jadwal->rutes->first()->kota_asal ?? '' }} → {{ $jadwal->rutes->first()->kota_tujuan ?? '' }}
                            @else
                                Rute Tidak Diketahui
                            @endif
                        </td>
                        <td>{{ $jadwal->shuttle->plat_nomor ?? $jadwal->shuttle->nama_shuttle ?? '-' }}</td>
                        <td>{{ $jadwal->driver->name ?? 'Belum Ditentukan' }}</td>
                        <td>{{ ($jadwal->shuttle->kapasitas_kursi ?? $jadwal->shuttle->total_kursi ?? 0) - ($jadwal->kursi_tersedia ?? 0) }}/{{ $jadwal->shuttle->kapasitas_kursi ?? $jadwal->shuttle->total_kursi ?? 0 }}</td>
                        <td>
                            @if($jadwal->status == 'selesai' || $jadwal->status == 'completed')
                                <span class="badge completed">Selesai</span>
                            @elseif($jadwal->status == 'berangkat' || $jadwal->status == 'in_progress')
                                <span class="badge in-progress">Dalam Perjalanan</span>
                            @elseif($jadwal->status == 'tersedia' || $jadwal->status == 'available')
                                <span class="badge scheduled">Tersedia</span>
                            @else
                                <span class="badge">{{ ucfirst($jadwal->status ?? 'Unknown') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <p>Tidak ada perjalanan hari ini</p>
        </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get canvas element
    const ctx = document.getElementById('salesChart').getContext('2d');

    // Adjust chart for mobile
    const isMobile = window.innerWidth < 768;
    const chartHeight = isMobile ? 220 : 250;

    // Data untuk grafik harian (dari controller)
    const dailyData = {
        labels: {!! json_encode($labels7 ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
        datasets: [
            {
                label: 'Smart Shuttle',
                data: {!! json_encode($values7Shuttle ?? [0,0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(77, 163, 255, 0.1)',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: {!! json_encode($values7Send ?? [0,0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(255, 106, 33, 0.1)',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: {!! json_encode($values7Rent ?? [0,0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Data untuk grafik mingguan
    const weeklyData = {
        labels: {!! json_encode($labelsWeek ?? ['Mg 1', 'Mg 2', 'Mg 3', 'Mg 4']) !!},
        datasets: [
            {
                label: 'Smart Shuttle',
                data: {!! json_encode($valuesWeekShuttle ?? [0,0,0,0]) !!},
                backgroundColor: 'rgba(77, 163, 255, 0.1)',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: {!! json_encode($valuesWeekSend ?? [0,0,0,0]) !!},
                backgroundColor: 'rgba(255, 106, 33, 0.1)',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: {!! json_encode($valuesWeekRent ?? [0,0,0,0]) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Data untuk grafik bulanan
    const monthlyData = {
        labels: {!! json_encode($labelsMonth ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
        datasets: [
            {
                label: 'Smart Shuttle',
                data: {!! json_encode($valuesMonthShuttle ?? [0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(77, 163, 255, 0.1)',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: {!! json_encode($valuesMonthSend ?? [0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(255, 106, 33, 0.1)',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: {!! json_encode($valuesMonthRent ?? [0,0,0,0,0,0]) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Format currency
    const formatCurrency = (value) => {
        if (value >= 1000000) {
            return 'Rp ' + (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
        }
        if (value >= 1000) {
            return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
        }
        return 'Rp ' + value.toString();
    };

    // Calculate totals for stats
    const calculateTotals = (data) => {
        const shuttleTotal = data.datasets[0].data.reduce((a, b) => a + b, 0);
        const sendTotal = data.datasets[1].data.reduce((a, b) => a + b, 0);
        const rentTotal = data.datasets[2].data.reduce((a, b) => a + b, 0);

        document.getElementById('stat-shuttle').textContent = formatCurrency(shuttleTotal);
        document.getElementById('stat-send').textContent = formatCurrency(sendTotal);
        document.getElementById('stat-rent').textContent = formatCurrency(rentTotal);
    };

    // Calculate initial totals
    calculateTotals(dailyData);

    // Create chart
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: dailyData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: !isMobile,
                    position: 'top',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: isMobile ? 11 : 12
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    bodyFont: {
                        size: isMobile ? 11 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += formatCurrency(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 11 : 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        },
                        font: {
                            size: isMobile ? 11 : 12
                        },
                        maxTicksLimit: isMobile ? 5 : 8
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });

    // Filter buttons functionality
    const filterButtons = document.querySelectorAll('.chart-filter');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Get period from data attribute
            const period = this.getAttribute('data-period');

            // Update chart data based on period
            switch(period) {
                case 'daily':
                    salesChart.data = dailyData;
                    break;
                case 'weekly':
                    salesChart.data = weeklyData;
                    break;
                case 'monthly':
                    salesChart.data = monthlyData;
                    break;
            }

            // Calculate totals for the new data
            calculateTotals(salesChart.data);

            // Update chart
            salesChart.update();
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                alert(`Akan mencari: ${searchTerm}`);
                // Implementasi pencarian bisa ditambahkan di sini
            }
        }
    });

    // Handle window resize for chart
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            salesChart.resize();
        }, 250);
    });
});
</script>
@endsection
