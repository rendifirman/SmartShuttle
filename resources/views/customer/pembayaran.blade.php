@extends('layouts.app')

@section('title', 'Smart Shuttle - Pembayaran')

@section('content')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Smart Shuttle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .box-shadow {
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .dashed {
            border-bottom: 2px dashed #d9d9d9;
        }

        .qr-loading {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .seat-badge {
            background-color: #FF581E;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            font-size: 14px;
        }

        .timer-expired {
            background-color: #dc2626 !important;
            animation: pulse 2s infinite;
        }

        .text-yellow-300 {
            color: #fbbf24;
        }

        .text-red-300 {
            color: #fca5a5;
        }

        .animate-pulse {
            animation: pulse 1s infinite;
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .countdown-timer {
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .timer-box {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 70px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .timer-label {
            font-size: 10px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .timer-value {
            font-size: 24px;
            font-weight: 800;
            line-height: 1;
        }

        .timer-separator {
            font-size: 24px;
            font-weight: 600;
            margin: 0 2px;
        }

        /* STYLE UNTUK PANAH SEDERHANA */
        .arrow-icon {
            color: #FF581E;
            font-size: 24px;
            margin: 0 10px;
        }

        .metode-selected {
            border: 2px solid #3b82f6 !important;
            background-color: #eff6ff !important;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Warning untuk waktu 5 menit terakhir */
        .timer-warning {
            background-color: #f59e0b !important;
            animation: warningPulse 1s infinite;
        }

        @keyframes warningPulse {
            0%, 100% { background-color: #f59e0b; }
            50% { background-color: #d97706; }
        }

        /* Danger untuk waktu 1 menit terakhir */
        .timer-danger {
            background-color: #dc2626 !important;
            animation: dangerPulse 0.5s infinite;
        }

        @keyframes dangerPulse {
            0%, 100% { background-color: #dc2626; }
            50% { background-color: #b91c1c; }
        }

        /* PERBAIKAN UTAMA: Atur padding pada body dan main untuk mencegah tumpukan dengan navbar */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f3f4f6;
    padding-top: 20px !important; /* Tambahkan padding atas untuk navbar fixed */
    min-height: 100vh;
}

/* Pastikan main content tidak tertumpuk dengan navbar */
main {
    min-height: calc(100vh - 80px);
    position: relative;
    z-index: 1;
}

/* Responsive padding untuk mobile */
@media (max-width: 768px) {
    body {
        padding-top: 60px !important;
    }

    main {
        min-height: calc(100vh - 60px);
    }
}

/* Layout khusus untuk halaman pembayaran */
.payment-page {
    padding-top: 20px;
}

.box-shadow {
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}

.dashed {
    border-bottom: 2px dashed #d9d9d9;
}

    </style>
</head>

<body class="min-h-screen">
    <main class="px-4 md:px-10 py-6 md:py-10 payment-page" style="padding-top: 100px;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">

            <!-- LEFT CARD - DETAIL PESANAN -->
            <div class="bg-white p-6 md:p-8 rounded-xl box-shadow">
                <h2 class="text-xl font-bold mb-4">DETAIL PESANAN</h2>
                <div class="dashed mb-4"></div>

                <!-- SHUTTLE INFO -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Kode Booking</p>
                            <p class="font-bold text-[#FF581E]">{{ $pemesanan->kode_booking }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Shuttle</p>
                            <p class="font-bold">{{ $pemesanan->jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Plat Nomor</p>
                            <p class="font-bold">{{ $pemesanan->jadwal->shuttle->plat_nomor ?? 'B 1234 CD' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kota & waktu DENGAN PANAH -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex-1">
                        <p class="font-bold text-gray-700 text-lg">{{ $from }}</p>
                        <p class="text-sm text-gray-600">
                            @php
                                use Carbon\Carbon;
                                // Format tanggal Indonesia
                                $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                                try {
                                    $tanggal = $date ? Carbon::parse($date) : now();
                                    $hari = $hariIndo[$tanggal->dayOfWeek] ?? 'Minggu';
                                    $bulan = $bulanIndo[$tanggal->month - 1] ?? 'Januari';
                                } catch (\Exception $e) {
                                    $tanggal = now();
                                    $hari = $hariIndo[$tanggal->dayOfWeek];
                                    $bulan = $bulanIndo[$tanggal->month - 1];
                                }
                            @endphp
                            {{ $hari }}, {{ $tanggal->format('d') }} {{ $bulan }} {{ $tanggal->format('Y') }}<br>
                            {{ $time }}
                        </p>
                    </div>

                    <!-- ICON PANAH SEDERHANA -->
                    <i class="fas fa-arrow-right arrow-icon"></i>

                    <div class="flex-1 text-right">
                        <p class="font-bold text-gray-700 text-lg">{{ $to }}</p>
                    </div>
                </div>

                <div class="dashed my-6"></div>

                <!-- DATA PEMESAN -->
                <h3 class="font-bold text-lg mb-3">DATA PEMESAN</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                    <div>
                        <p class="text-gray-600">Nama pemesan</p>
                        <p class="font-semibold">{{ $customer_name }}</p>
                    </div>
                    <div class="md:text-right">
                        <p class="text-gray-600">Nomor HP</p>
                        <p class="font-semibold">{{ $customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold">{{ $customer_email }}</p>
                    </div>
                </div>

                <!-- TABEL PENUMPANG -->
                <h3 class="font-bold text-lg mb-3">DATA PENUMPANG</h3>
                <p class="text-sm text-gray-600 mb-3">Jumlah penumpang: {{ $pemesanan->jumlah_penumpang }} orang</p>

                <div class="overflow-x-auto">
                    <table class="w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2">No</th>
                                <th class="border px-3 py-2">Nama Penumpang</th>
                                <th class="border px-3 py-2">NIK</th>
                                <th class="border px-3 py-2">Nomor Kursi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penumpang as $index => $p)
                            <tr>
                                <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border px-3 py-2">{{ $p->nama_lengkap }}</td>
                                <td class="border px-3 py-2">{{ $p->nik ?? '-' }}</td>
                                <td class="border px-3 py-2 text-center">
                                    @if(!empty($p->nomor_kursi))
                                        <span class="seat-badge">Kursi {{ $p->nomor_kursi }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="dashed my-6"></div>

                <!-- Detail harga -->
                <h3 class="font-bold text-lg mb-3">RINCIAN HARGA</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Harga tiket</span>
                        <span>Rp {{ number_format($pemesanan->harga_total / $pemesanan->jumlah_penumpang, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Jumlah penumpang</span>
                        <span>X {{ $pemesanan->jumlah_penumpang }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Sub total</span>
                        <span>Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}</span>
                    </div>

                    @if($pemesanan->diskon > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon voucher</span>
                        <span>-Rp {{ number_format($pemesanan->diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="dashed my-2"></div>

                    <div class="flex justify-between font-bold text-lg text-[#FF581E]">
                        <span>Total harga</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT CARD - PEMBAYARAN -->
            <div x-data="paymentHandler()" x-init="init()" class="bg-white p-6 md:p-8 rounded-xl box-shadow">
                <!-- KODE PEMBAYARAN -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Kode Pembayaran</p>
                            <p class="font-bold text-lg text-[#00215E]">{{ $pembayaran->kode_pembayaran }}</p>
                        </div>
                        <button onclick="copyToClipboard('{{ $pembayaran->kode_pembayaran }}')"
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded text-sm hover:bg-blue-200">
                            Salin
                        </button>
                    </div>
                </div>

                <!-- TIMER - AWALNYA DISEMBUNYIKAN -->
                <div x-show="showTimer" x-transition.fade.in
                     :class="{
                         'timer-expired': timeLeft <= 0,
                         'timer-warning': timeLeft <= 300 && timeLeft > 60, // 5-1 menit
                         'timer-danger': timeLeft <= 60 && timeLeft > 0 // <1 menit
                     }"
                     class="flex items-center justify-between bg-[#00215E] text-white px-5 py-3 rounded-lg mb-6 fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span class="font-semibold">Batas Waktu Pembayaran</span>
                    </div>
                    <div class="flex items-center space-x-1 countdown-timer">
                        <!-- MENIT -->
                        <div class="timer-box"
                             :class="{
                                 'bg-yellow-600': timeLeft <= 300 && timeLeft > 60,
                                 'bg-red-600': timeLeft <= 60 && timeLeft > 0
                             }">
                            <div class="timer-value"
                                 :class="{
                                     'text-yellow-300': timeLeft <= 300 && timeLeft > 60,
                                     'text-red-300': timeLeft <= 60 && timeLeft > 0
                                 }"
                                 x-text="formatTwoDigits(minutes)">00</div>
                            <div class="timer-label">MENIT</div>
                        </div>

                        <div class="timer-separator">:</div>

                        <!-- DETIK -->
                        <div class="timer-box"
                             :class="{
                                 'bg-yellow-600': timeLeft <= 300 && timeLeft > 60,
                                 'bg-red-600': timeLeft <= 60 && timeLeft > 0,
                                 'animate-pulse': timeLeft <= 60 && timeLeft > 0
                             }">
                            <div class="timer-value"
                                 :class="{
                                     'text-yellow-300': timeLeft <= 300 && timeLeft > 60,
                                     'text-red-300': timeLeft <= 60 && timeLeft > 0
                                 }"
                                 x-text="formatTwoDigits(seconds)">00</div>
                            <div class="timer-label">DETIK</div>
                        </div>
                    </div>
                </div>

                <!-- Pesan peringatan waktu -->
                <div x-show="showTimer && timeLeft <= 300 && timeLeft > 0" x-transition class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg fade-in">
                    <div class="flex items-center text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-semibold" x-text="getWarningMessage()"></span>
                    </div>
                    <p class="text-sm text-yellow-600 mt-1">Segera selesaikan pembayaran sebelum waktu habis!</p>
                </div>

                <!-- Pesan waktu habis -->
                <div x-show="showTimer && timeLeft <= 0" x-transition class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
                    <div class="flex items-center text-red-700">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span class="font-semibold">Waktu pembayaran telah habis</span>
                    </div>
                    <p class="text-sm text-red-600 mt-1">Pesanan Anda telah dibatalkan karena melewati batas waktu pembayaran.</p>
                    <div class="mt-3">
                        <a href="{{ route('customer.riwayat') }}"
                           class="inline-block bg-red-600 text-white py-2 px-4 rounded text-sm font-semibold hover:bg-red-700 transition">
                            Lihat Riwayat
                        </a>
                    </div>
                </div>

                <h2 class="text-xl font-bold mb-4">PEMBAYARAN</h2>
                <div class="dashed mb-6"></div>

                <!-- Form untuk pilih metode -->
                <form id="formMetode" action="{{ route('customer.pembayaran.pilih_metode', ['kode_booking' => $pemesanan->kode_booking]) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="metode" id="inputMetode">
                </form>

                <!-- STEP 1: PILIH METODE (hanya ini yang ditampilkan awal) -->
                <div x-show="!paymentMethodSelected" x-transition>
                    <div class="mb-6">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-2">1</div>
                            <p class="font-semibold text-lg">Pilih Metode Pembayaran</p>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            <i class="fas fa-clock text-orange-500 mr-1"></i>
                            <span class="font-semibold text-orange-600">Batas waktu: 30 menit</span> setelah memilih metode
                        </p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <!-- Hanya tampilkan QRIS, BCA, Mandiri, BNI, BRI -->
                        @php
                            $allowedMethods = ['qris', 'bca_va', 'mandiri_va', 'bni_va', 'bri_va'];
                            $filteredMethods = $metodePembayaran->whereIn('kode', $allowedMethods);
                        @endphp

                        @foreach($filteredMethods as $metode)
                        <button
                            @click="selectMetode('{{ $metode->kode }}', '{{ $metode->nama }}')"
                            :class="{
                                'metode-selected border-blue-600 bg-blue-50': selectedMetode == '{{ $metode->kode }}',
                                'border border-gray-300 hover:bg-gray-50': selectedMetode != '{{ $metode->kode }}'
                            }"
                            class="w-full rounded-lg py-4 font-semibold text-left px-4 transition flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-3 text-blue-500"></i>
                                <div>
                                    <p class="text-lg">{{ $metode->nama }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Batas waktu: 30 menit</p>
                                </div>
                            </div>
                            @if($metode->biaya_admin > 0)
                            <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">+Rp {{ number_format($metode->biaya_admin, 0, ',', '.') }}</span>
                            @endif
                        </button>
                        @endforeach
                    </div>

                    <!-- Tombol Lanjut ke Detail Pembayaran -->
                    <div class="mt-8 pt-6 border-t">
                        <div x-show="selectedMetode" x-transition>
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm font-semibold text-blue-800">Metode yang dipilih:</p>
                                <p class="text-lg font-bold text-blue-600" x-text="selectedMetodeName"></p>
                                <p class="text-sm text-orange-600 mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Setelah dikonfirmasi, timer 30 menit akan langsung dimulai!
                                </p>
                            </div>

                            <button @click="confirmPaymentMethod()"
                                    class="w-full bg-[#00215E] text-white py-3 rounded-lg font-semibold text-center hover:bg-[#001a4d] transition flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Konfirmasi Metode & Mulai Timer 30 Menit
                            </button>
                        </div>

                        <div x-show="!selectedMetode" class="text-center py-6">
                            <i class="fas fa-hand-pointer text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Silakan pilih metode pembayaran terlebih dahulu</p>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: DETAIL PEMBAYARAN & TIMER (muncul setelah metode dipilih) -->
                <div x-show="paymentMethodSelected" x-transition.fade.in>
                    <!-- Header dengan metode yang dipilih -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-2">2</div>
                                    <p class="font-bold text-lg text-blue-600" x-text="selectedMetodeName"></p>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    Selesaikan dalam <span class="font-semibold" x-text="getRemainingTimeText()"></span>
                                </p>
                            </div>
                            <button @click="changePaymentMethod()"
                                    class="px-3 py-1 bg-white text-blue-600 border border-blue-300 rounded text-sm hover:bg-blue-50">
                                <i class="fas fa-exchange-alt mr-1"></i> Ganti
                            </button>
                        </div>
                    </div>

                    <!-- QRIS SECTION -->
                    <div x-show="selectedMetode == 'qris'" x-transition.fade.in class="fade-in">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold mb-2">Scan QR Code</h3>
                            <p class="text-sm text-gray-600">Gunakan aplikasi e-wallet atau mobile banking untuk scan QR di bawah</p>
                        </div>

                        <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 flex flex-col items-center mb-6 bg-blue-50/30">
                            <!-- QR Code -->
                            <div class="mb-4 relative">
                                @if($pembayaran->qr_code)
                                    <img id="qrCodeImage"
                                         src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($pembayaran->qr_code) }}&format=png&margin=15&color=0A2540&bgcolor=FFFFFF"
                                         alt="QR Code Pembayaran"
                                         class="w-60 h-60 object-contain rounded-lg border-2 border-blue-200 shadow-md"
                                         onload="this.classList.remove('qr-loading')"
                                         onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&error=L&margin=15'">
                                @elseif($pembayaran->qris_url)
                                    <img id="qrCodeImage"
                                         src="{{ $pembayaran->qris_url }}"
                                         alt="QR Code Pembayaran"
                                         class="w-60 h-60 object-contain rounded-lg border-2 border-blue-200 shadow-md"
                                         onload="this.classList.remove('qr-loading')"
                                         onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&error=L&margin=15'">
                                @else
                                    <img id="qrCodeImage"
                                         src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&margin=15&color=0A2540&bgcolor=FFFFFF"
                                         alt="QR Code Pembayaran"
                                         class="w-60 h-60 object-contain rounded-lg border-2 border-blue-200 shadow-md qr-loading"
                                         onload="this.classList.remove('qr-loading')"
                                         onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&error=L&margin=15'">
                                @endif
                            </div>

                            <!-- Nominal -->
                            <div class="mb-4 p-4 bg-white border border-yellow-200 rounded-lg shadow-sm w-full max-w-md">
                                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                <p class="font-bold text-2xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>

                            <p class="text-sm text-gray-500 text-center mb-4">Scan QR Code dengan aplikasi yang mendukung QRIS</p>

                            <!-- Dukungan E-Wallet -->
                            <div class="mt-4 flex flex-wrap justify-center gap-3 mb-6">
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                                    <span class="text-xs font-semibold text-blue-700">DANA</span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                                    <span class="text-xs font-semibold text-purple-700">OVO</span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                                    <span class="text-xs font-semibold text-green-700">GOPAY</span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                                    <span class="text-xs font-semibold text-orange-700">SHOPEEPAY</span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                                    <span class="text-xs font-semibold text-red-700">LinkAja</span>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi -->
                        <div class="bg-blue-50 p-5 rounded-lg mb-6">
                            <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Cara Pembayaran QRIS:
                            </h4>
                            <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                                <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                <li>Pilih menu "Scan QR Code" atau "Bayar dengan QRIS"</li>
                                <li>Arahkan kamera ke QR Code di atas</li>
                                <li>Pastikan nominal sudah sesuai: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span></li>
                                <li>Konfirmasi dan selesaikan pembayaran</li>
                            </ol>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-4 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition text-lg">
                            <i class="fas fa-check-circle mr-2"></i> Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- BCA VA SECTION -->
                    <div x-show="selectedMetode == 'bca_va'" x-transition.fade.in class="fade-in">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold mb-2">BCA Virtual Account</h3>
                            <p class="text-sm text-gray-600">Bayar menggunakan Virtual Account BCA</p>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border-2 border-blue-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-white shadow-sm">
                                    <span id="vaNumber" class="text-xl text-blue-700 tracking-wider">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Salin nomor VA untuk melakukan pembayaran</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border-2 border-yellow-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-yellow-50 shadow-sm">
                                    <span class="text-2xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg hover:bg-yellow-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="bg-blue-50 p-5 rounded-lg mb-6">
                            <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Instruksi Pembayaran BCA VA:
                            </h4>
                            <div class="space-y-3 text-gray-700">
                                <div class="flex items-start">
                                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">1</span>
                                    <p>Buka aplikasi BCA Mobile atau m-BCA</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">2</span>
                                    <p>Pilih menu "Transfer" → "BCA Virtual Account"</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">3</span>
                                    <p>Masukkan nomor VA di atas: <span class="font-semibold" id="vaNumberDisplay">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">4</span>
                                    <p>Konfirmasi nominal: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">5</span>
                                    <p>Ikuti instruksi sampai pembayaran selesai</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-4 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition text-lg">
                            <i class="fas fa-check-circle mr-2"></i> Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- MANDIRI VA SECTION -->
                    <div x-show="selectedMetode == 'mandiri_va'" x-transition.fade.in class="fade-in">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold mb-2">Mandiri Virtual Account</h3>
                            <p class="text-sm text-gray-600">Bayar menggunakan Virtual Account Mandiri</p>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border-2 border-green-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-white shadow-sm">
                                    <span id="vaNumberMandiri" class="text-xl text-green-700 tracking-wider">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="bg-green-100 text-green-600 px-4 py-2 rounded-lg hover:bg-green-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Salin nomor VA untuk melakukan pembayaran</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border-2 border-yellow-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-yellow-50 shadow-sm">
                                    <span class="text-2xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg hover:bg-yellow-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="bg-green-50 p-5 rounded-lg mb-6">
                            <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Instruksi Pembayaran Mandiri VA:
                            </h4>
                            <div class="space-y-3 text-gray-700">
                                <div class="flex items-start">
                                    <span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">1</span>
                                    <p>Buka aplikasi Livin by Mandiri</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">2</span>
                                    <p>Pilih menu "Pembayaran" → "Virtual Account"</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">3</span>
                                    <p>Masukkan nomor VA di atas: <span class="font-semibold" id="vaNumberMandiriDisplay">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">4</span>
                                    <p>Konfirmasi nominal: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">5</span>
                                    <p>Ikuti instruksi sampai pembayaran selesai</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-4 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition text-lg">
                            <i class="fas fa-check-circle mr-2"></i> Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- BNI VA SECTION -->
                    <div x-show="selectedMetode == 'bni_va'" x-transition.fade.in class="fade-in">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold mb-2">BNI Virtual Account</h3>
                            <p class="text-sm text-gray-600">Bayar menggunakan Virtual Account BNI</p>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border-2 border-red-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-white shadow-sm">
                                    <span id="vaNumberBni" class="text-xl text-red-700 tracking-wider">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Salin nomor VA untuk melakukan pembayaran</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border-2 border-yellow-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-yellow-50 shadow-sm">
                                    <span class="text-2xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg hover:bg-yellow-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="bg-red-50 p-5 rounded-lg mb-6">
                            <h4 class="font-semibold text-red-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Instruksi Pembayaran BNI VA:
                            </h4>
                            <div class="space-y-3 text-gray-700">
                                <div class="flex items-start">
                                    <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">1</span>
                                    <p>Buka aplikasi BNI Mobile Banking</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">2</span>
                                    <p>Pilih menu "Transfer" → "Virtual Account Billing"</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">3</span>
                                    <p>Masukkan nomor VA di atas: <span class="font-semibold" id="vaNumberBniDisplay">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">4</span>
                                    <p>Konfirmasi nominal: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">5</span>
                                    <p>Ikuti instruksi sampai pembayaran selesai</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-4 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition text-lg">
                            <i class="fas fa-check-circle mr-2"></i> Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- BRI VA SECTION -->
                    <div x-show="selectedMetode == 'bri_va'" x-transition.fade.in class="fade-in">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold mb-2">BRI Virtual Account</h3>
                            <p class="text-sm text-gray-600">Bayar menggunakan Virtual Account BRI</p>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border-2 border-purple-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-white shadow-sm">
                                    <span id="vaNumberBri" class="text-xl text-purple-700 tracking-wider">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="bg-purple-100 text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Salin nomor VA untuk melakukan pembayaran</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border-2 border-yellow-300 rounded-lg p-4 font-semibold flex justify-between items-center bg-yellow-50 shadow-sm">
                                    <span class="text-2xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg hover:bg-yellow-200 transition">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="bg-purple-50 p-5 rounded-lg mb-6">
                            <h4 class="font-semibold text-purple-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Instruksi Pembayaran BRI VA:
                            </h4>
                            <div class="space-y-3 text-gray-700">
                                <div class="flex items-start">
                                    <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">1</span>
                                    <p>Buka aplikasi BRI Mobile Banking</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">2</span>
                                    <p>Pilih menu "Pembayaran" → "Virtual Account"</p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">3</span>
                                    <p>Masukkan nomor VA di atas: <span class="font-semibold" id="vaNumberBriDisplay">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">4</span>
                                    <p>Konfirmasi nominal: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">5</span>
                                    <p>Ikuti instruksi sampai pembayaran selesai</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-4 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition text-lg">
                            <i class="fas fa-check-circle mr-2"></i> Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                </div>

            </div>

        </div>

        <!-- Notifikasi -->
        @if(session('success'))
        <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Modal Konfirmasi Pembayaran -->
        <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
                <div class="text-[#FF581E] mb-4">
                    <i class="fas fa-exclamation-circle text-5xl mx-auto block text-center"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Konfirmasi Pembayaran</h3>
                <p class="text-gray-600 mb-6 text-center">Apakah Anda yakin ingin menyelesaikan pembayaran ini?</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="processPayment()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Ya, Bayar Sekarang
                    </button>
                    <button onclick="hideConfirmationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300">
                        Batal
                    </button>

                </div>
            </div>
        </div>

        <!-- Modal Pembayaran Berhasil -->
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center">
                <div class="text-green-500 mb-4">
                    <i class="fas fa-check-circle text-5xl mx-auto block"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-600 mb-6">Selamat! Pembayaran Anda telah berhasil diproses. Tiket Anda sudah aktif.</p>
                <button onclick="redirectToRiwayat()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Oke, Lihat Riwayat
                </button>
            </div>
        </div>

        <!-- Modal Poin Bertambah -->
        <div id="pointsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center">
                <div class="text-blue-500 mb-4">
                    <i class="fas fa-gift text-5xl mx-auto block"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">🎉 Poin Bertambah!</h3>
                <div class="mb-4">
                    <div class="text-lg font-semibold text-blue-700">+100 Member Points</div>
                    <div class="text-lg font-semibold text-green-700">+<span id="loyaltyPoints">50</span> Loyalty Points</div>
                    <div class="text-sm text-gray-500 mt-2">Status: <span id="membershipLevel">Bronze</span> Member</div>
                </div>
                <p class="text-gray-600 mb-6">Poin telah ditambahkan ke akun Anda. Terima kasih telah menggunakan Smart Shuttle!</p>
                <button onclick="hidePointsModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Terima Kasih
                </button>
            </div>
        </div>

    </main>
</body>

</html>

<script>
function paymentHandler() {
    return {
        selectedMetode: '',
        selectedMetodeName: '',
        paymentMethodSelected: false,
        showTimer: false,

        // WAKTU 30 MENIT = 1800 detik
        timeLeft: 0,
        minutes: 0,
        seconds: 0,
        interval: null,

        init() {
            console.log('Payment handler initialized - 30 menit');

            // Cek jika sudah ada metode dari server
            const serverMetode = '{{ $pembayaran->metode ?? "" }}';
            if (serverMetode) {
                this.selectedMetode = serverMetode;
                // Cari nama metode
                const metodeButtons = document.querySelectorAll('button[onclick*="selectMetode"]');
                metodeButtons.forEach(btn => {
                    const match = btn.getAttribute('onclick').match(/selectMetode\('([^']+)',\s*'([^']+)'/);
                    if (match && match[1] === this.selectedMetode) {
                        this.selectedMetodeName = match[2];
                    }
                });

                this.paymentMethodSelected = true;
                this.showTimer = true;

                // Set waktu dari server atau 30 menit
                this.timeLeft = Math.max(0, Math.floor(Number('{{ $sisa_waktu_detik ?? 0 }}')));
                if (this.timeLeft === 0) {
                    this.timeLeft = 30 * 60; // 30 menit
                }
                this.calculateTimeParts();
                this.startTimer();
            }
        },

        selectMetode(metode, nama) {
            this.selectedMetode = metode;
            this.selectedMetodeName = nama;

            // Remove all selected classes
            document.querySelectorAll('button[onclick*="selectMetode"]').forEach(btn => {
                btn.classList.remove('metode-selected');
            });

            // Add selected class to clicked button
            event.currentTarget.classList.add('metode-selected');
        },

        async confirmPaymentMethod() {
            if (!this.selectedMetode) {
                showToast('Silakan pilih metode pembayaran terlebih dahulu', 'error');
                return;
            }

            try {
                // Kirim ke server untuk menyimpan metode
                const formData = new FormData();
                formData.append('metode', this.selectedMetode);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route("customer.pembayaran.pilih_metode", ["kode_booking" => $pemesanan->kode_booking]) }}', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    // Tampilkan detail pembayaran dan timer
                    this.paymentMethodSelected = true;
                    this.showTimer = true;

                    // Set waktu 30 menit = 1800 detik
                    this.timeLeft = 30 * 60;
                    this.calculateTimeParts();
                    this.startTimer();

                    showToast('Metode pembayaran berhasil dipilih. Timer 30 menit dimulai!', 'success');

                    // Reload page to show updated payment data (VA number, etc.)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    const errorData = await response.json();
                    showToast(errorData.message || 'Gagal memilih metode pembayaran', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            }
        },

        changePaymentMethod() {
            if (confirm('Apakah Anda yakin ingin mengganti metode pembayaran?\n\nTimer 30 menit akan direset dan dimulai ulang.')) {
                this.paymentMethodSelected = false;
                this.showTimer = false;
                this.selectedMetode = '';
                this.selectedMetodeName = '';
                clearInterval(this.interval);

                // Remove selected class from all method buttons
                document.querySelectorAll('button[onclick*="selectMetode"]').forEach(btn => {
                    btn.classList.remove('metode-selected');
                });

                showToast('Silakan pilih metode pembayaran baru', 'info');
            }
        },

        calculateTimeParts() {
            const totalSeconds = Math.max(0, Math.floor(Number(this.timeLeft)));
            this.minutes = Math.floor(totalSeconds / 60);
            this.seconds = totalSeconds % 60;
        },

        startTimer() {
            // Hitung bagian waktu
            this.calculateTimeParts();

            // Clear timer lama
            if (this.interval) {
                clearInterval(this.interval);
            }

            // Mulai timer baru
            this.interval = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft = Math.max(0, Math.floor(Number(this.timeLeft)) - 1);
                    this.calculateTimeParts();

                    // Cek jika waktu habis
                    if (this.timeLeft <= 0) {
                        this.handleTimerExpired();
                    }

                    // Auto check payment status setiap 30 detik
                    if (this.timeLeft % 30 === 0) {
                        this.checkPaymentStatus();
                    }

                    // Tampilkan peringatan pada menit-menit tertentu
                    this.showTimeWarnings();
                }
            }, 1000);
        },

        showTimeWarnings() {
            // Peringatan pada menit-menit tertentu
            if (this.timeLeft === 300) { // 5 menit
                showToast('Sisa waktu 5 menit! Segera selesaikan pembayaran.', 'warning');
            } else if (this.timeLeft === 60) { // 1 menit
                showToast('Sisa waktu 1 menit! Waktu hampir habis!', 'error');
            }
        },

        handleTimerExpired() {
            clearInterval(this.interval);

            // Tampilkan notifikasi
            showToast('Waktu pembayaran 30 menit telah habis!', 'error');

            // Cek status terakhir ke server
            setTimeout(() => {
                this.checkPaymentStatus(true);
            }, 2000);
        },

        formatTwoDigits(number) {
            number = Number(Math.floor(Number(number))) || 0;
            return number.toString().padStart(2, '0');
        },

        getWarningMessage() {
            if (this.timeLeft <= 60) {
                return 'Waktu hampir habis! Sisa kurang dari 1 menit';
            } else if (this.timeLeft <= 300) {
                return `Sisa waktu ${Math.floor(this.timeLeft / 60)} menit!`;
            }
            return '';
        },

        getRemainingTimeText() {
            const mins = Math.floor(this.timeLeft / 60);
            const secs = this.timeLeft % 60;

            if (this.timeLeft <= 0) {
                return 'Waktu habis';
            } else if (this.timeLeft < 60) {
                return `${secs} detik`;
            } else {
                return `${mins} menit ${secs} detik`;
            }
        },

        async checkPaymentStatus(force = false) {
            try {
                const response = await fetch(`{{ route('customer.pembayaran.cek_status', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`);
                const data = await response.json();

                if (data.success) {
                    if (data.data.status === 'berhasil') {
                        window.location.href = '{{ route("customer.detail_pemesanan", ["kode_booking" => $pemesanan->kode_booking]) }}';
                    } else if (data.data.is_kadaluarsa && force) {
                        showToast('Pembayaran telah kadaluarsa', 'error');
                    }

                    // Update QR code if available in response
                    if (data.data.qr_code && this.selectedMetode === 'qris') {
                        this.updateQRCode(data.data.qr_code, data.data.qris_url);
                    }
                }
            } catch (error) {
                console.error('Error checking payment status:', error);
            }
        },

        updateQRCode(qrCode, qrisUrl) {
            const qrImage = document.getElementById('qrCodeImage');
            if (qrImage && qrisUrl) {
                qrImage.src = qrisUrl;
                qrImage.classList.remove('qr-loading');
            } else if (qrImage && qrCode) {
                qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrCode)}&format=png&margin=15&color=0A2540&bgcolor=FFFFFF`;
                qrImage.classList.remove('qr-loading');
            }
        },

        async refreshQRCode() {
            try {
                const response = await fetch(`{{ route('api.payment.qrcode', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`);
                const data = await response.json();

                if (data.success && data.data) {
                    this.updateQRCode(data.data.qr_code, data.data.qris_url);
                }
            } catch (error) {
                console.error('Error refreshing QR code:', error);
            }
        }
    }
}

// API function untuk cek status pembayaran (untuk polling)
async function checkPaymentStatusPolling() {
    try {
        const response = await fetch(`{{ route('customer.pembayaran.cek_status', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`);
        const data = await response.json();

        if (data.success) {
            if (data.data.status === 'berhasil') {
                window.location.href = '{{ route("customer.detail_pemesanan", ["kode_booking" => $pemesanan->kode_booking]) }}';
            }
        }
    } catch (error) {
        console.error('Error checking payment status:', error);
    }
}

// Polling setiap 10 detik untuk mengecek status pembayaran
setInterval(checkPaymentStatusPolling, 10000);

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Teks berhasil disalin!');
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
        showToast('Gagal menyalin teks', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' : type === 'warning' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white'}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'} mr-2"></i>${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function showConfirmationModal() {
    document.getElementById('confirmationModal').classList.remove('hidden');
}

function hideConfirmationModal() {
    document.getElementById('confirmationModal').classList.add('hidden');
}

function processPayment() {
    hideConfirmationModal();

    const successModal = document.getElementById('successModal');
    successModal.querySelector('p').innerHTML = '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memproses pembayaran...</div>';
    successModal.classList.remove('hidden');

    setTimeout(() => {
        fetch(`{{ route('customer.pembayaran.simulasi', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    successModal.querySelector('h3').textContent = 'Pembayaran Berhasil!';
                    successModal.querySelector('p').textContent = 'Selamat! Pembayaran Anda telah berhasil diproses. Tiket Anda sudah aktif.';

                    if (data.points_added) {
                        setTimeout(() => {
                            showPointsModal(data);
                        }, 1500);
                    }
                } else {
                    successModal.querySelector('h3').textContent = 'Pembayaran Gagal';
                    successModal.querySelector('p').textContent = data.message || 'Maaf, terjadi kesalahan saat memproses pembayaran.';
                    successModal.querySelector('button').textContent = 'Coba Lagi';
                    successModal.querySelector('button').onclick = function() {
                        window.location.reload();
                    };
                }
            })
            .catch(error => {
                console.error('Error:', error);
                successModal.querySelector('h3').textContent = 'Pembayaran Berhasil';
                successModal.querySelector('p').textContent = 'Selamat! Pembayaran Anda telah berhasil diproses. Tiket Anda sudah aktif.';
            });
    }, 1500);
}

function showPointsModal(data) {
    document.getElementById('loyaltyPoints').textContent = data.loyalty_points_added || '50';
    document.getElementById('membershipLevel').textContent = data.membership_level || 'Bronze';
    document.getElementById('pointsModal').classList.remove('hidden');
}

function hidePointsModal() {
    document.getElementById('pointsModal').classList.add('hidden');
}

function redirectToRiwayat() {
    window.location.href = '{{ route("customer.riwayat") }}';
}
</script>
@endsection
