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

                <!-- Kota & waktu -->
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="font-bold text-gray-700 text-lg">{{ $from }}</p>
                        <p class="text-sm text-gray-600">
                            @php
                                // Format tanggal Indonesia
                                $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                                $tanggal = $date ?? now();
                                if (!($tanggal instanceof \DateTime)) {
                                    $tanggal = new DateTime($tanggal);
                                }

                                $hari = $hariIndo[$tanggal->format('w')];
                                $bulan = $bulanIndo[$tanggal->format('n') - 1];
                            @endphp
                            {{ $hari }}, {{ $tanggal->format('d') }} {{ $bulan }} {{ $tanggal->format('Y') }}<br>
                            {{ $time }}
                        </p>
                    </div>
                    <div class="text-right">
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
            <div x-data="paymentHandler()" class="bg-white p-6 md:p-8 rounded-xl box-shadow">
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

                <!-- TIMER: hanya muncul setelah memilih metode -->
                <div
                    x-show="metode !== ''"
                    x-transition
                    class="flex items-center justify-between bg-[#00215E] text-white px-5 py-3 rounded-lg mb-6"
                >
                    <span class="font-semibold">Selesaikan Pembayaran dalam waktu</span>
                    <span class="text-xl font-bold" x-text="formatTime(timeLeft)"></span>
                </div>

                <h2 class="text-xl font-bold mb-4">PEMBAYARAN</h2>
                <div class="dashed mb-6"></div>

                <p class="font-semibold mb-3">Pilih Metode Pembayaran</p>

                <div class="space-y-3 mb-6">
                    @foreach($metodePembayaran as $metode)
                    <button
                        @click="selectMetode('{{ $metode->kode }}')"
                        :class="selectedMetode == '{{ $metode->kode }}' ? 'border-blue-600 bg-blue-50' : 'border'"
                        class="w-full rounded-lg py-4 font-semibold text-left px-4 transition flex justify-between items-center">
                        <span>{{ $metode->nama }}</span>
                        @if($metode->biaya_admin > 0)
                        <span class="text-sm text-gray-600">+Rp {{ number_format($metode->biaya_admin, 0, ',', '.') }}</span>
                        @endif
                    </button>
                    @endforeach
                </div>

                <!-- Form untuk pilih metode -->
                <form id="formMetode" action="{{ route('customer.pembayaran.pilih_metode', ['kode' => $pemesanan->kode_booking]) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="metode" id="inputMetode">
                </form>

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
        selectedMetode: '{{ $pembayaran->metode }}',
        timeLeft: {{ $pembayaran->waktu_kadaluarsa->diffInSeconds(now()) }},
        interval: null,

        init() {
            // Mulai timer jika metode sudah dipilih
            if (this.selectedMetode) {
                this.startTimer();
            }
        },

        selectMetode(metode) {
            this.selectedMetode = metode;
            this.startTimer();

            // Submit form untuk menyimpan metode
            document.getElementById('inputMetode').value = metode;
            document.getElementById('formMetode').submit();
        },

        startTimer() {
            // clear timer lama
            if (this.interval) {
                clearInterval(this.interval);
            }

            // mulai timer baru (30 menit)
            this.timeLeft = 1800;

            // mulai timer baru
            this.interval = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    clearInterval(this.interval);
                    // Refresh halaman untuk update status
                    window.location.reload();
                }
            }, 1000);
        },

        formatTime() {
            let m = String(Math.floor(this.timeLeft / 60)).padStart(2, '0');
            let s = String(this.timeLeft % 60).padStart(2, '0');
            return `${m}:${s}`;
        }
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Buat notifikasi kecil
        showToast('Teks berhasil disalin!');
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
        showToast('Gagal menyalin teks', 'error');
    });
}

function showToast(message, type = 'success') {
    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    // Hapus toast setelah 3 detik
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Tampilkan modal konfirmasi
function showConfirmationModal() {
    document.getElementById('confirmationModal').classList.remove('hidden');
}

// Sembunyikan modal konfirmasi
function hideConfirmationModal() {
    document.getElementById('confirmationModal').classList.add('hidden');
}

// Proses pembayaran
function processPayment() {
    hideConfirmationModal();

    // Tampilkan loading state
    const successModal = document.getElementById('successModal');
    successModal.querySelector('p').innerHTML = '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memproses pembayaran...</div>';
    successModal.classList.remove('hidden');

    // Simulasi delay
    setTimeout(() => {
        // Kirim request ke server
        fetch(`{{ route('customer.pembayaran.simulasi', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tampilkan modal sukses
                    successModal.querySelector('h3').textContent = 'Pembayaran Berhasil!';
                    successModal.querySelector('p').textContent = 'Selamat! Pembayaran Anda telah berhasil diproses. Tiket Anda sudah aktif.';

                    // Tampilkan modal poin jika ada
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
    }, 1500); // Delay 1.5 detik untuk simulasi
}

// Tampilkan modal poin
function showPointsModal(data) {
    document.getElementById('loyaltyPoints').textContent = data.loyalty_points_added || '50';
    document.getElementById('membershipLevel').textContent = data.membership_level || 'Bronze';
    document.getElementById('pointsModal').classList.remove('hidden');
}

// Sembunyikan modal poin
function hidePointsModal() {
    document.getElementById('pointsModal').classList.add('hidden');
}

// Redirect ke halaman riwayat
function redirectToRiwayat() {
    window.location.href = '{{ route("customer.riwayat") }}';
}

// Auto refresh QR code jika metode QRIS dipilih
function refreshQRCode() {
    const qrImage = document.getElementById('qrCodeImage');
    if (qrImage) {
        // Tambahkan timestamp untuk menghindari cache
        const timestamp = new Date().getTime();
        qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTSHUTTLE-{{ $pembayaran->kode_pembayaran }}-${timestamp}&format=png&margin=10&color=0A2540&bgcolor=F8FAFC`;
        qrImage.classList.add('qr-loading');
    }
}

// Auto cek status pembayaran setiap 10 detik
setInterval(() => {
    fetch(`{{ route('customer.pembayaran.cek_status', ['kodePembayaran' => $pembayaran->kode_pembayaran]) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'berhasil') {
                // Tampilkan modal sukses
                document.getElementById('successModal').classList.remove('hidden');
            } else if (data.status === 'gagal' || data.status === 'kadaluarsa') {
                // Refresh halaman untuk update status
                window.location.reload();
            }
        });
}, 10000);

// Auto refresh QR code setiap 2 menit jika metode QRIS aktif
setInterval(() => {
    if (document.querySelector('[x-show\\="selectedMetode == \'qris\'"]').style.display !== 'none') {
        refreshQRCode();
    }
}, 120000); // 2 menit
</script>
@endsection
