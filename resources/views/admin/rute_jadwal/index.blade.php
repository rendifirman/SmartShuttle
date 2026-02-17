@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Daftar Jadwal</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#configModal">
                <i class="fas fa-cog"></i> Config Rute Jadwal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Flow Mode Configuration Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="fas fa-exchange-alt"></i> Schedule Flow Mode
            </h5>
            
            <!-- Current Mode Display -->
            <div class="mb-3 pb-3 border-bottom">
                <strong>Current Mode:</strong>
                <?php $currentMode = appSetting('jadwal_flow_mode', 'driver_confirmation'); ?>
                @if($currentMode === 'driver_confirmation')
                    <span class="badge bg-info"><i class="fas fa-handshake"></i> Driver Confirmation</span>
                    <small class="text-muted d-block mt-2">Drivers can view and claim open schedules</small>
                @else
                    <span class="badge bg-warning"><i class="fas fa-user-tie"></i> Direct Assign</span>
                    <small class="text-muted d-block mt-2">Admin assigns drivers when creating schedules</small>
                @endif
            </div>

            <!-- Flow Mode Toggle Form -->
            <form method="POST" action="{{ route('admin.jadwal.config.update') }}" id="flowModeForm" class="d-flex gap-3 align-items-end flex-wrap">
                @csrf
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jadwal_flow_mode" id="mode_driver" 
                           value="driver_confirmation" {{ $currentMode === 'driver_confirmation' ? 'checked' : '' }}>
                    <label class="form-check-label" for="mode_driver">
                        <i class="fas fa-handshake"></i> Driver Confirmation
                        <br><small class="text-muted">Drivers claim schedules</small>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jadwal_flow_mode" id="mode_direct" 
                           value="direct_assign" {{ $currentMode === 'direct_assign' ? 'checked' : '' }}>
                    <label class="form-check-label" for="mode_direct">
                        <i class="fas fa-user-tie"></i> Direct Assign
                        <br><small class="text-muted">Admin assigns drivers</small>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save Mode
                </button>
            </form>
        </div>
    </div>

    <!-- Create Button -->
    <div class="mb-3">
        <a href="{{ route('admin.rute_jadwal.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Buat Jadwal
        </a>
    </div>

    <!-- Schedules Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Rute</th>
                    <th>Shuttle</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Driver</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $j)
                    <tr>
                        <td>#{{ $j->id }}</td>
                        <td>{{ $j->id_rute }}</td>
                        <td>{{ $j->id_shuttle }}</td>
                        <td>{{ $j->tanggal->format('Y-m-d') }}</td>
                        <td>{{ $j->jam_berangkat }}</td>
                        <td>{{ $j->id_driver ?? '-' }}</td>
                        <td>
                            @if($j->status === 'open')
                                <span class="badge bg-info">Open</span>
                            @elseif($j->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($j->status === 'done')
                                <span class="badge bg-secondary">Done</span>
                            @elseif($j->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning">{{ $j->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($jadwals->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Belum ada jadwal. <a href="{{ route('admin.rute_jadwal.create') }}">Buat jadwal baru</a>
        </div>
    @endif

    {{ $jadwals->links() }}
</div>

<!-- Config Modal (Placeholder for future rute-jadwal config) -->
<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-cog"></i> Rute Jadwal Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Route and schedule configuration options coming soon...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh mode state after form submission to ensure UI stays in sync
    document.getElementById('flowModeForm').addEventListener('submit', function() {
        // The form will POST and redirect back, which will reload the page with fresh mode value
    });
</script>
@endsection
