@extends('layouts.app-admin')

@section('content')
<style>
/* ================= DASHBOARD ================= */
.dashboard {
    padding: 24px 32px 40px;
    background: #f8f8f6;
}

/* HEADER */
.dashboard-header {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 28px;
}

.dashboard-header h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.search-box {
    position: relative;
    max-width: 1130px;
}

.search-box input {
    width: 100%;
    padding: 12px 44px 12px 16px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    outline: none;
}

.search-box .icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: 0.6;
}

/* SUMMARY */
.summary {
    display: grid;
    text-align: center;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
    margin-bottom: 28px;
}

.summary-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 42px;
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
    display: grid;
    grid-template-columns: 4fr 3fr;
    gap: 22px;
    margin-bottom: 28px;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* CARD */
.card {
    background: #ffffff;
    border-radius: 12px;
    padding: 22px 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.card h4 {
    margin: 0 0 16px;
    font-size: 15px;
    font-weight: 600;
}

/* CHART CONTAINER */
.chart-container {
    position: relative;
    height: 250px;
    margin-top: 10px;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-filters {
    display: flex;
    gap: 8px;
}

.chart-filter {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #fff;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
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
    gap: 20px;
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
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
}

.stat-value {
    font-size: 14px;
    font-weight: 600;
    margin-left: 4px;
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
    padding: 10px 0;
}

.route-list li:not(:last-child) {
    border-bottom: 1px solid rgba(255,255,255,0.25);
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
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

/* RESPONSIVE */
@media (max-width: 768px) {
    .dashboard {
        padding: 16px;
    }

    .summary {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .summary-card {
        padding: 24px;
    }

    .chart-container {
        height: 200px;
    }

    .table {
        display: block;
        overflow-x: auto;
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
    </div>

    {{-- SUMMARY CARD --}}
    <div class="summary">
        <div class="summary-card">
            <h3>3</h3>
            <p>Total perjalanan hari ini</p>
        </div>

        <div class="summary-card">
            <h3>21</h3>
            <p>Total penumpang hari ini</p>
        </div>

        <div class="summary-card">
            <h3>Rp. 230.000</h3>
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
                    <span class="stat-value">Rp 8,4 juta</span>
                </div>
                <div class="stat-item">
                    <div class="stat-color" style="background: #ff6a21;"></div>
                    <span class="stat-label">SmartSend:</span>
                    <span class="stat-value">Rp 3,2 juta</span>
                </div>
                <div class="stat-item">
                    <div class="stat-color" style="background: #10b981;"></div>
                    <span class="stat-label">SmartRent:</span>
                    <span class="stat-value">Rp 5,1 juta</span>
                </div>
            </div>
        </div>

        <div class="card highlight">
            <h4>Rute Terpopuler</h4>

            <ul class="route-list">
                <li>
                    <span>Jakarta → Bandung</span>
                    <strong>125</strong>
                </li>
                <li>
                    <span>Bandung → Jakarta</span>
                    <strong>87</strong>
                </li>
                <li>
                    <span>Sukabumi → Jakarta</span>
                    <strong>87</strong>
                </li>
                <li>
                    <span>Jakarta → Sukabumi</span>
                    <strong>65</strong>
                </li>
                <li>
                    <span>Bandung → Surabaya</span>
                    <strong>42</strong>
                </li>
            </ul>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <h4>Perjalanan Hari Ini</h4>

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
                <tr>
                    <td>08:00</td>
                    <td>Jakarta → Bandung</td>
                    <td>S-001</td>
                    <td>Dimas Mahendra</td>
                    <td>10/12</td>
                    <td><span class="badge departed">Berangkat</span></td>
                </tr>
                <tr>
                    <td>10:30</td>
                    <td>Bandung → Jakarta</td>
                    <td>S-002</td>
                    <td>Budi Santoso</td>
                    <td>8/12</td>
                    <td><span class="badge in-progress">Dalam Perjalanan</span></td>
                </tr>
                <tr>
                    <td>13:00</td>
                    <td>Sukabumi → Jakarta</td>
                    <td>S-003</td>
                    <td>Andi Wijaya</td>
                    <td>12/12</td>
                    <td><span class="badge completed">Selesai</span></td>
                </tr>
                <tr>
                    <td>15:30</td>
                    <td>Jakarta → Bandung</td>
                    <td>S-001</td>
                    <td>Dimas Mahendra</td>
                    <td>6/12</td>
                    <td><span class="badge scheduled">Terjadwal</span></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get canvas element
    const ctx = document.getElementById('salesChart').getContext('2d');

    // Data untuk grafik harian
    const dailyData = {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [
            {
                label: 'Smart Shuttle',
                data: [1200000, 1400000, 1100000, 1300000, 1600000, 2000000, 1800000],
                backgroundColor: '#4da3ff',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: [400000, 500000, 350000, 600000, 550000, 700000, 600000],
                backgroundColor: '#ff6a21',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: [800000, 900000, 700000, 1100000, 1000000, 1200000, 1100000],
                backgroundColor: '#10b981',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Data untuk grafik mingguan
    const weeklyData = {
        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        datasets: [
            {
                label: 'Tiket Reguler',
                data: [5200000, 5800000, 6100000, 6800000],
                backgroundColor: '#4da3ff',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: [1800000, 2200000, 2400000, 2600000],
                backgroundColor: '#ff6a21',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: [3200000, 3800000, 4100000, 4500000],
                backgroundColor: '#10b981',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Data untuk grafik bulanan
    const monthlyData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [
            {
                label: 'Tiket Reguler',
                data: [22000000, 24000000, 26000000, 28000000, 30000000, 32000000],
                backgroundColor: '#4da3ff',
                borderColor: '#4da3ff',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartSend',
                data: [9000000, 10000000, 11000000, 12000000, 13000000, 14000000],
                backgroundColor: '#ff6a21',
                borderColor: '#ff6a21',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'SmartRent',
                data: [15000000, 17000000, 18000000, 19000000, 21000000, 23000000],
                backgroundColor: '#10b981',
                borderColor: '#10b981',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    };

    // Format currency
    const formatCurrency = (value) => {
        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    // Create chart
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: dailyData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
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
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + ' jt';
                            }
                            return formatCurrency(value);
                        }
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
});
</script>
@endsection
