@extends('layouts.app-driver')

@section('title', 'Jadwal Tersedia')

@section('content')
<div class="container">
    <h2>Jadwal Tersedia dari Admin</h2>
    
    <div class="row">
        @forelse($availableSchedules as $schedule)
@foreach ($availableSchedules as $schedule)
    <div>
        <h4>Rute:</h4>
        @foreach ($schedule->rutes as $rute)
            <p>{{ $rute->nama_rute ?? 'N/A' }} ({{ $rute->kota_asal ?? '' }} → {{ $rute->kota_tujuan ?? '' }})</p>
        @endforeach
    </div>
@endforeach

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $schedule->rute->kota_asal }} → {{ $schedule->rute->kota_tujuan }}
                    </h5>
                    <p class="card-text">
                        <i class="fas fa-calendar"></i> 
                        {{ \Carbon\Carbon::parse($schedule->tanggal_keberangkatan)->format('d M Y') }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-clock"></i> 
                        {{ $schedule->waktu_keberangkatan }} - {{ $schedule->waktu_kedatangan }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-money-bill"></i> 
                        Rp {{ number_format($schedule->harga_total, 0, ',', '.') }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-chair"></i> 
                        {{ $schedule->kursi_tersedia }} kursi tersedia
                    </p>
                    
                    <form action="{{ route('driver.schedule.take', $schedule->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Ambil jadwal ini?')">
                            <i class="fas fa-check"></i> Ambil Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Tidak ada jadwal tersedia saat ini.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection