@extends('layouts.app-driver')

@section('title', 'Jadwal Tersedia - Driver')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Jadwal Tersedia</h1>
                    <p class="text-muted">Pilih jadwal yang tersedia untuk Anda ambil</p>
                </div>
                <div>
                    <a href="{{ route('driver.jadwal.saya') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Jadwal Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Jadwal Tersedia -->
    <div class="row">
        @forelse($jadwalTersedia as $jadwal)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ $jadwal->rutes->first()->kota_asal ?? '' }} → 
                        {{ $jadwal->rutes->first()->kota_tujuan ?? '' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-calendar me-1"></i> Tanggal
                            </h6>
                            <p class="mb-2">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->format('d F Y') }}
                            </p>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-clock me-1"></i> Waktu
                            </h6>
                            <p class="mb-2">
                                {{ $jadwal->waktu_keberangkatan }} - {{ $jadwal->waktu_kedatangan }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-bus me-1"></i> Armada
                            </h6>
                            <p class="mb-2">
                                {{ $jadwal->shuttle->nama_shuttle ?? 'Tidak diketahui' }}
                            </p>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-chair me-1"></i> Kursi Tersedia
                            </h6>
                            <p class="mb-2">
                                <span class="badge bg-info">
                                    {{ $jadwal->kursi_tersedia }} kursi
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-route me-1"></i> Rute
                        </h6>
                        <p class="mb-2">{{ $jadwal->rutes->first()->nama_rute ?? 'Tidak diketahui' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-money-bill me-1"></i> Harga
                        </h6>
                        <h5 class="text-primary mb-0">
                            Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}
                        </h5>
                    </div>

                    <form action="{{ route('driver.jadwal.ambil', $jadwal->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin mengambil jadwal ini?')">
                        @csrf
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" 
                                   id="konfirmasi{{ $jadwal->id }}" name="konfirmasi" required>
                            <label class="form-check-label" for="konfirmasi{{ $jadwal->id }}">
                                Saya siap melayani jadwal ini
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i> Ambil Jadwal Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Tidak ada jadwal tersedia</h4>
                    <p class="text-muted mb-4">Tidak ada jadwal yang tersedia untuk diambil saat ini.</p>
                    <a href="{{ route('driver.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jadwalTersedia->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{ $jadwalTersedia->links() }}
                </ul>
            </nav>
        </div>
    </div>
    @endif
</div>
@endsection