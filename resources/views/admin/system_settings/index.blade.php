@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>System Settings — Schedule Flow</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <i class="fas fa-exchange-alt"></i> Konfigurasi Flow Jadwal
            </h5>

            <form method="POST" action="{{ route('admin.system_settings.schedule_flow.update') }}">
                @csrf

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jadwal_flow_mode" id="driver_confirmation" value="driver_confirmation" {{ $mode === 'driver_confirmation' ? 'checked' : '' }}>
                        <label class="form-check-label" for="driver_confirmation">
                            <strong>Driver Confirmation</strong>
                            <br><small class="text-muted">Admin creates open schedules; drivers claim them</small>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jadwal_flow_mode" id="direct_assign" value="direct_assign" {{ $mode === 'direct_assign' ? 'checked' : '' }}>
                        <label class="form-check-label" for="direct_assign">
                            <strong>Direct Assign</strong>
                            <br><small class="text-muted">Admin assigns driver; schedules active immediately</small>
                        </label>
                    </div>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Current Mode:</strong> <code>{{ $mode }}</code>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Jadwal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
