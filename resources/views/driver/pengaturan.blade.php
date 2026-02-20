@extends('layouts.app-driver')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pengaturan Driver</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mode Penerimaan Jadwal -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Mode Penerimaan Jadwal</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Pilih bagaimana Anda ingin menerima jadwal dari admin.
                            </p>

                            <form action="{{ route('driver.pengaturan.update-schedule-accept-mode') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- AUTO_ACCEPT Mode -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                                           id="auto_accept" value="AUTO_ACCEPT"
                                                           {{ $driver->schedule_accept_mode === 'AUTO_ACCEPT' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="auto_accept">
                                                        <strong>Penerimaan Otomatis</strong>
                                                    </label>
                                                </div>
                                                <div class="ps-4 mt-3">
                                                    <p class="small mb-2">
                                                        <span class="badge bg-success">Aktif</span>
                                                    </p>
                                                    <p class="small text-muted mb-0">
                                                        Admin dapat langsung menugaskan jadwal kepada Anda. Jadwal akan otomatis aktif tanpa perlu konfirmasi.
                                                    </p>
                                                    <ul class="small text-muted mb-0 mt-2">
                                                        <li>Jadwal langsung menjadi milik Anda</li>
                                                        <li>Jadwal langsung aktif tanpa perlu diambil</li>
                                                        <li>Tidak ada kompetisi dengan driver lain</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MANUAL_CONFIRM Mode -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="schedule_accept_mode"
                                                           id="manual_confirm" value="MANUAL_CONFIRM"
                                                           {{ $driver->schedule_accept_mode === 'MANUAL_CONFIRM' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="manual_confirm">
                                                        <strong>Konfirmasi Manual</strong>
                                                    </label>
                                                </div>
                                                <div class="ps-4 mt-3">
                                                    <p class="small mb-2">
                                                        <span class="badge bg-info">Pilihan</span>
                                                    </p>
                                                    <p class="small text-muted mb-0">
                                                        Admin membuat jadwal global yang dapat Anda lihat dan ambil melalui halaman "Ambil Jadwal".
                                                    </p>
                                                    <ul class="small text-muted mb-0 mt-2">
                                                        <li>Lihat semua jadwal global yang tersedia</li>
                                                        <li>Berebut untuk mengambil jadwal pilihan Anda</li>
                                                        <li>Jadwal yang pertama diklaim akan menjadi milik driver tersebut</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Mode Saat Ini -->
                    <div class="alert alert-info">
                        <strong>Mode Saat Ini:</strong>
                        @if($driver->schedule_accept_mode === 'AUTO_ACCEPT')
                            <span class="badge bg-success">Penerimaan Otomatis</span>
                            <br><small>Admin dapat langsung menugaskan jadwal kepada Anda.</small>
                        @else
                            <span class="badge bg-info">Konfirmasi Manual</span>
                            <br><small>Anda dapat melihat dan mengambil jadwal global melalui halaman "Ambil Jadwal".</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card.border {
        transition: all 0.3s ease;
    }

    .card.border:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection
