@extends('layouts.app')

@section('title', $promo->nama_promo . ' - Smart Shuttle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('customer.beranda') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Beranda
        </a>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Promo Image -->
                <div class="md:w-1/3">
                    <img src="{{ $promo->gambar }}" alt="{{ $promo->nama_promo }}" class="w-full h-64 object-cover rounded-lg">
                </div>

                <!-- Promo Details -->
                <div class="md:w-2/3">
                    <div class="flex items-center gap-3 mb-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $promo->nama_promo }}</h1>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            Aktif
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4">{{ $promo->deskripsi }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Detail Promo</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kode Promo:</span>
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $promo->kode_promo }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jenis Diskon:</span>
                                    <span class="font-medium">
                                        @if($promo->jenis_diskon == 'persentase')
                                            {{ $promo->nilai_diskon }}%
                                        @else
                                            Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                @if($promo->maksimal_diskon)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Maksimal Diskon:</span>
                                    <span class="font-medium">Rp {{ number_format($promo->maksimal_diskon, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Minimal Pembelian:</span>
                                    <span class="font-medium">Rp {{ number_format($promo->minimal_pembelian, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Periode Promo</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Mulai:</span>
                                    <span class="font-medium">{{ $promo->tanggal_mulai->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Berakhir:</span>
                                    <span class="font-medium">{{ $promo->tanggal_berakhir->format('d M Y') }}</span>
                                </div>
                                @if($promo->kuota)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kuota Tersisa:</span>
                                    <span class="font-medium">{{ $promo->kuota - $promo->terpakai }} dari {{ $promo->kuota }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex gap-4">
                        <a href="{{ route('customer.search') }}"
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                            Pesan Sekarang
                        </a>
                        <button onclick="copyPromoCode('{{ $promo->kode_promo }}')"
                                class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-200">
                            Salin Kode Promo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions -->
    @if($promo->syarat_ketentuan)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Syarat & Ketentuan</h3>
        <div class="prose max-w-none">
            {!! nl2br(e($promo->syarat_ketentuan)) !!}
        </div>
    </div>
    @endif
</div>

<script>
function copyPromoCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        // Simple notification
        alert('Kode promo "' + code + '" berhasil disalin!');
    }, function(err) {
        console.error('Could not copy text: ', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Kode promo "' + code + '" berhasil disalin!');
    });
}
</script>
@endsection
