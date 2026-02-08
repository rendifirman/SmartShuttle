@extends('layouts.app-driver')

@section('title', 'Jadwal Saya - Driver')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Jadwal Saya</h1>
                    <p class="text-muted">Kelola jadwal yang telah Anda ambil</p>
                </div>
                <div>
                    <a href="{{ route('driver.jadwal.tersedia') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Ambil Jadwal Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jadwal Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jumlahJadwalBulanIni ?? 0 }}/20
                            </div>
                            <div class="mt-2">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: {{ min(($jumlahJadwalBulanIni ?? 0) * 5, 100) }}%"
                                         aria-valuenow="{{ $jumlahJadwalBulanIni ?? 0 }}" 
                                         aria-valuemin="0" aria-valuemax="20">
                                    </div>
                                </div>
                                <small class="text-muted">Sisa kuota: {{ $sisaKuota ?? 20 }} jadwal</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jadwal Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jadwalSaya->where('status', 'aktif')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Jadwal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jadwalSaya->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
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

    <!-- Tabel Jadwal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-alt me-2"></i>Daftar Jadwal Saya
            </h6>
        </div>
        <div class="card-body">
            @if($jadwalSaya->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Jadwal Driver</th>
                            <th>ID Jadwal Admin</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Armada</th>
                            <th>Harga</th>
                            <th>Kursi</th>
                            <th>Status</th>
                            <th>Diambil Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalSaya as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $jadwal->id_jadwal_driver }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $jadwal->id_jadwal }}</span>
                            </td>
                            <td>
                                <strong>{{ $jadwal->rute }}</strong>
                            </td>
                            <td>{{ $jadwal->tanggal_formatted }}</td>
                            <td>
                                {{ $jadwal->waktu_berangkat_formatted }} - 
                                {{ $jadwal->waktu_tiba_formatted }}
                            </td>
                            <td>{{ $jadwal->armada }}</td>
                            <td>{{ $jadwal->harga_formatted }}</td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ $jadwal->kursi_terisi }}/{{ $jadwal->total_kursi }}
                                </span>
                            </td>
                            <td>
                                @if($jadwal->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($jadwal->status == 'selesai')
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $jadwal->waktu_diambil_formatted }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('driver.jadwal.detail', $jadwal->id_jadwal_driver) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($jadwal->status == 'aktif')
                                    <form action="{{ route('driver.jadwal.update-status', $jadwal->id_jadwal_driver) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="btn btn-success"
                                                onclick="return confirm('Tandai jadwal sebagai selesai?')"
                                                title="Selesai">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('driver.jadwal.batalkan', $jadwal->id_jadwal_driver) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Batalkan jadwal ini?')"
                                                title="Batalkan">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Belum ada jadwal</h4>
                <p class="text-muted mb-4">Anda belum mengambil jadwal apapun.</p>
                <a href="{{ route('driver.jadwal.tersedia') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Ambil Jadwal Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // DataTable initialization
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "order": [[0, "desc"]]
        });
    });
</script>
@endsection