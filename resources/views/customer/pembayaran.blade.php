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
    </style>
</head>

<body class="min-h-screen">
    <main class="px-4 md:px-10 py-6 md:py-10">
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

                <!-- TIMER JAM-MENIT-DETIK -->
                <div :class="{'timer-expired': timeLeft <= 0}"
                     class="flex items-center justify-between bg-[#00215E] text-white px-5 py-3 rounded-lg mb-6">
                    <span class="font-semibold">Selesaikan Pembayaran dalam</span>
                    <div class="flex items-center space-x-1 countdown-timer">
                        <!-- JAM -->
                        <div class="timer-box"
                             :class="{'bg-red-600': hours === 0 && minutes < 5}">
                            <div class="timer-value"
                                 :class="{'text-red-300': hours === 0 && minutes < 5}"
                                 x-text="formatTwoDigits(hours)">00</div>
                            <div class="timer-label">JAM</div>
                        </div>

                        <div class="timer-separator">:</div>

                        <!-- MENIT -->
                        <div class="timer-box"
                             :class="{'bg-yellow-600': minutes < 5 && minutes > 0, 'bg-red-600': minutes === 0 && seconds > 0}">
                            <div class="timer-value"
                                 :class="{'text-yellow-300': minutes < 5 && minutes > 0, 'text-red-300': minutes === 0 && seconds > 0}"
                                 x-text="formatTwoDigits(minutes)">00</div>
                            <div class="timer-label">MENIT</div>
                        </div>

                        <div class="timer-separator">:</div>

                        <!-- DETIK -->
                        <div class="timer-box"
                             :class="{'bg-red-600 animate-pulse': seconds < 30 && seconds > 0}">
                            <div class="timer-value"
                                 :class="{'text-red-300 animate-pulse': seconds < 30 && seconds > 0}"
                                 x-text="formatTwoDigits(seconds)">00</div>
                            <div class="timer-label">DETIK</div>
                        </div>
                    </div>
                </div>

                <!-- Pesan waktu habis -->
                <div x-show="timeLeft <= 0" x-transition class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-semibold">Waktu pembayaran telah habis</span>
                    </div>
                    <p class="text-sm text-red-600 mt-1">Silakan hubungi customer service atau buat pemesanan baru.</p>
                </div>

                <h2 class="text-xl font-bold mb-4">PEMBAYARAN</h2>
                <div class="dashed mb-6"></div>

                <!-- Form untuk pilih metode -->
                <form id="formMetode" action="{{ route('customer.pembayaran.pilih_metode', ['kode_booking' => $pemesanan->kode_booking]) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="metode" id="inputMetode">
                </form>

                <div x-show="timeLeft > 0">
                    <p class="font-semibold mb-3">Pilih Metode Pembayaran</p>

                    <div class="space-y-3 mb-6">
                        @foreach($metodePembayaran as $metode)
                        <button
                            :disabled="timeLeft <= 0"
                            @click="selectMetode('{{ $metode->kode }}')"
                            :class="{
                                'border-blue-600 bg-blue-50': selectedMetode == '{{ $metode->kode }}',
                                'border': selectedMetode != '{{ $metode->kode }}',
                                'opacity-50 cursor-not-allowed': timeLeft <= 0
                            }"
                            class="w-full rounded-lg py-4 font-semibold text-left px-4 transition flex justify-between items-center">
                            <span>{{ $metode->nama }}</span>
                            @if($metode->biaya_admin > 0)
                            <span class="text-sm text-gray-600">+Rp {{ number_format($metode->biaya_admin, 0, ',', '.') }}</span>
                            @endif
                        </button>
                        @endforeach
                    </div>

                    <div class="dashed my-6"></div>

                    <!-- QRIS SECTION -->
                    <div x-show="selectedMetode == 'qris'" x-transition>
                        <h2 class="text-lg font-bold mb-4">QRIS</h2>

                        <div class="border rounded-lg p-5 flex flex-col items-center mb-5">
                            <!-- QR Code dari QR Server -->
                            <div class="mb-4 relative">
                                <img id="qrCodeImage"
                                     src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&margin=10&color=0A2540&bgcolor=F8FAFC"
                                     alt="QR Code Pembayaran"
                                     class="w-48 h-48 object-contain rounded-lg border qr-loading"
                                     onload="this.classList.remove('qr-loading')"
                                     onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}&format=png&error=L&margin=10'">
                            </div>

                            <!-- Nominal -->
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                <p class="font-bold text-xl text-[#00215E]">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>

                            <p class="text-sm text-gray-500 text-center mb-4">Scan QR Code dengan aplikasi e-wallet atau mobile banking yang mendukung QRIS</p>

                            <!-- Dukungan E-Wallet -->
                            <div class="mt-4 flex flex-wrap justify-center gap-2 mb-4">
                                <div class="bg-gray-100 px-3 py-1 rounded-lg">
                                    <span class="text-xs font-semibold">DANA</span>
                                </div>
                                <div class="bg-gray-100 px-3 py-1 rounded-lg">
                                    <span class="text-xs font-semibold">OVO</span>
                                </div>
                                <div class="bg-gray-100 px-3 py-1 rounded-lg">
                                    <span class="text-xs font-semibold">GOPAY</span>
                                </div>
                                <div class="bg-gray-100 px-3 py-1 rounded-lg">
                                    <span class="text-xs font-semibold">SHOPEEPAY</span>
                                </div>
                                <div class="bg-gray-100 px-3 py-1 rounded-lg">
                                    <span class="text-xs font-semibold">LinkAja</span>
                                </div>
                            </div>

                            <!-- Instruksi Singkat -->
                            <div class="bg-blue-50 p-4 rounded-lg w-full text-sm">
                                <p class="font-semibold text-blue-800 mb-2">Cara Pembayaran:</p>
                                <ol class="list-decimal pl-4 space-y-1 text-gray-700">
                                    <li>Buka aplikasi e-wallet atau mobile banking</li>
                                    <li>Pilih menu "Scan QR Code"</li>
                                    <li>Arahkan kamera ke QR Code di atas</li>
                                    <li>Konfirmasi dan selesaikan pembayaran</li>
                                </ol>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-3 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition">
                            Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- BCA VA SECTION -->
                    <div x-show="selectedMetode == 'bca_va'" x-transition>
                        <h2 class="text-lg font-bold mb-4">BCA Virtual Account</h2>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border rounded-lg p-3 font-semibold flex justify-between items-center bg-gray-50">
                                    <span id="vaNumber">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="text-blue-600 hover:text-blue-800">
                                        📋 Salin
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border rounded-lg p-3 font-semibold flex justify-between items-center bg-gray-50">
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="text-blue-600 hover:text-blue-800">
                                        📋 Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Instruksi Pembayaran:</h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm space-y-2">
                                <p>1. Buka aplikasi BCA Mobile atau m-BCA</p>
                                <p>2. Pilih menu "Transfer"</p>
                                <p>3. Pilih "BCA Virtual Account"</p>
                                <p>4. Masukkan nomor VA di atas</p>
                                <p>5. Konfirmasi dan bayar</p>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-3 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition">
                            Simulasi Pembayaran Berhasil
                        </button>
                    </div>

                    <!-- MANDIRI VA SECTION -->
                    <div x-show="selectedMetode == 'mandiri_va'" x-transition>
                        <h2 class="text-lg font-bold mb-4">Mandiri Virtual Account</h2>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-sm font-semibold block mb-2">Nomor Virtual Account</label>
                                <div class="border rounded-lg p-3 font-semibold flex justify-between items-center bg-gray-50">
                                    <span id="vaNumberMandiri">{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $pembayaran->no_virtual_account ?? '88' . rand(100000000, 999999999) }}')"
                                            class="text-blue-600 hover:text-blue-800">
                                        📋 Salin
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold block mb-2">Total Pembayaran</label>
                                <div class="border rounded-lg p-3 font-semibold flex justify-between items-center bg-gray-50">
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $total }}')"
                                            class="text-blue-600 hover:text-blue-800">
                                        📋 Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Pembayaran -->
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Instruksi Pembayaran:</h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm space-y-2">
                                <p>1. Buka aplikasi Livin by Mandiri</p>
                                <p>2. Pilih menu "Pembayaran"</p>
                                <p>3. Pilih "Virtual Account"</p>
                                <p>4. Masukkan nomor VA di atas</p>
                                <p>5. Konfirmasi dan bayar</p>
                            </div>
                        </div>

                        <button onclick="showConfirmationModal()"
                               class="w-full bg-[#FF581E] text-white py-3 rounded-lg font-semibold text-center block hover:bg-[#e54e1a] transition">
                            Simulasi Pembayaran Berhasil
                        </button>
                    </div>
                </div>

                <!-- Tombol ketika waktu habis -->
                <div x-show="timeLeft <= 0" x-transition>
                    <div class="text-center p-8">
                        <i class="fas fa-clock text-5xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Waktu Pembayaran Telah Habis</h3>
                        <p class="text-gray-600 mb-6">Pesanan Anda telah kadaluarsa karena melewati batas waktu pembayaran.</p>
                        <a href="{{ route('customer.riwayat') }}"
                           class="inline-block bg-[#00215E] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#001a4d] transition">
                            Lihat Riwayat Pemesanan
                        </a>
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
                    <button onclick="hideConfirmationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300">
                        Batal
                    </button>
                    <button onclick="processPayment()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Ya, Bayar Sekarang
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
        selectedMetode: '{{ $pembayaran->metode ?? '' }}',
        // Pastikan waktu dari server dipaksa menjadi number integer (prevent float)
        timeLeft: Math.max(0, Math.floor(Number('{{ $sisa_waktu_detik ?? 0 }}'))),

        // Variabel terpisah untuk jam, menit, detik
        hours: 0,
        minutes: 0,
        seconds: 0,

        interval: null,
        isProcessing: false,

        init() {
            console.log('Timer initialized. Total time left:', this.timeLeft, 'seconds');

            // Hitung jam, menit, detik awal
            this.calculateTimeParts();

            // Mulai timer jika masih ada waktu
            if (this.timeLeft > 0) {
                this.startTimer();
            } else {
                // Jika waktu sudah habis, disable semua
                this.disablePayment();

                // Cek status pembayaran untuk memastikan
                setTimeout(() => {
                    this.checkPaymentStatus();
                }, 1000);
            }

            // Load payment details
            this.loadPaymentDetails();
        },

        calculateTimeParts() {
            // Pastikan kita bekerja dengan integer >= 0
            const totalSeconds = Math.max(0, Math.floor(Number(this.timeLeft)));
            this.hours = Math.floor(totalSeconds / 3600);
            this.minutes = Math.floor((totalSeconds % 3600) / 60);
            this.seconds = totalSeconds % 60;
        },

        startTimer() {
            // Clear timer lama
            if (this.interval) {
                clearInterval(this.interval);
            }

            // Mulai timer baru
            this.interval = setInterval(() => {
                if (this.timeLeft > 0) {
                    // kurangi 1 detik dan pastikan tetap integer >= 0
                    this.timeLeft = Math.max(0, Math.floor(Number(this.timeLeft)) - 1);
                    this.calculateTimeParts();

                    // Cek jika waktu habis
                    if (this.timeLeft <= 0) {
                        this.handleTimerExpired();
                    }
                }
            }, 1000);
        },

        handleTimerExpired() {
            clearInterval(this.interval);
            this.disablePayment();

            // Set semua ke 0
            this.hours = 0;
            this.minutes = 0;
            this.seconds = 0;

            // Tampilkan notifikasi
            this.showExpiredNotification();

            // Cek status terakhir ke server
            setTimeout(() => {
                this.checkPaymentStatus(true); // force check
            }, 2000);
        },

        disablePayment() {
            // Disable semua tombol pembayaran (kecuali tombol salin)
            const buttons = Array.from(document.querySelectorAll('button')).filter(btn => {
                const onClick = btn.getAttribute('onclick') || '';
                return !onClick.includes('copyToClipboard');
            });
            buttons.forEach(button => {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
            });

            // Update warna timer menjadi merah
            const timerElement = document.querySelector('.countdown-timer');
            if (timerElement && timerElement.parentElement) {
                timerElement.parentElement.classList.add('timer-expired');
            }
        },

        showExpiredNotification() {
            // Buat notifikasi
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 bg-red-500 text-white animate-bounce';
            toast.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Waktu pembayaran telah habis!';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 5000);
        },

        formatTwoDigits(number) {
            number = Number(Math.floor(Number(number))) || 0;
            return number.toString().padStart(2, '0');
        },

        async selectMetode(metode) {
            if (this.timeLeft <= 0) {
                this.showExpiredNotification();
                return;
            }

            // perbaikan typo: gunakan parameter 'metode'
            this.selectedMetode = metode;
            this.isProcessing = true;

            try {
                const formData = new FormData();
                formData.append('metode', metode);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route("customer.pembayaran.pilih_metode", ["kode_booking" => $pemesanan->kode_booking]) }}', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    await this.loadPaymentDetails();
                    showToast('Metode pembayaran berhasil dipilih');
                } else {
                    showToast('Gagal memilih metode pembayaran', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            } finally {
                this.isProcessing = false;
            }
        },

        async loadPaymentDetails() {
            try {
                const response = await fetch(`{{ route('customer.pembayaran.cek_status', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`);
                const data = await response.json();

                if (data.success) {
                    // Jika pembayaran sudah berhasil, redirect
                    if (data.data.status === 'berhasil') {
                        window.location.href = '{{ route("customer.detail_pemesanan", ["kode_booking" => $pemesanan->kode_booking]) }}';
                        return;
                    }

                    // Jika waktu sudah habis di server, update timer
                    if (data.data.remaining_time !== undefined) {
                        const serverTimeLeft = Number(data.data.remaining_time);
                        if (serverTimeLeft <= 0 && this.timeLeft > 0) {
                            this.timeLeft = 0;
                            this.handleTimerExpired();
                        } else if (Math.abs(this.timeLeft - serverTimeLeft) > 10) {
                            // Sinkronkan jika perbedaan > 10 detik
                            this.timeLeft = Math.max(0, Math.floor(serverTimeLeft));
                            this.calculateTimeParts();
                            console.log('Timer synchronized with server:', this.timeLeft);
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading payment details:', error);
            }
        },

        async checkPaymentStatus(force = false) {
            try {
                const response = await fetch(`{{ route('customer.pembayaran.cek_status', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`);
                const data = await response.json();

                if (data.success) {
                    if (data.data.status === 'berhasil') {
                        // Redirect ke success page
                        window.location.href = '{{ route("customer.detail_pemesanan", ["kode_booking" => $pemesanan->kode_booking]) }}';
                    } else if (data.data.is_kadaluarsa && force) {
                        // Jika kadaluarsa, reload halaman
                        window.location.reload();
                    } else if (data.data.remaining_time !== undefined) {
                        // Sinkronisasi waktu jika diperlukan
                        const serverTimeLeft = Number(data.data.remaining_time);
                        if (Math.abs(this.timeLeft - serverTimeLeft) > 10) {
                            this.timeLeft = Math.max(0, Math.floor(serverTimeLeft));
                            this.calculateTimeParts();
                        }
                    }
                }
            } catch (error) {
                console.error('Error checking payment status:', error);
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
                // Redirect ke success page
                window.location.href = '{{ route("customer.detail_pemesanan", ["kode_booking" => $pemesanan->kode_booking]) }}';
            } else if (data.data.is_kadaluarsa) {
                // Refresh page
                window.location.reload();
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
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
    toast.textContent = message;
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
