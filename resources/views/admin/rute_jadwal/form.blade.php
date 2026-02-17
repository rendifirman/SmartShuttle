<?php /** @var \Illuminate\Contracts\View\View $mode */ ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Buat Jadwal</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.rute_jadwal.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <?php $currentMode = appSetting('jadwal_flow_mode', 'driver_confirmation'); ?>
            
            <!-- Flow Mode Info -->
            <div class="alert alert-info mb-4" role="alert">
                <i class="fas fa-info-circle"></i>
                @if($currentMode === 'driver_confirmation')
                    <strong>Mode:</strong> Driver Confirmation - Drivers will view and claim this schedule
                @else
                    <strong>Mode:</strong> Direct Assign - You must select a driver now, schedule will be active immediately
                @endif
            </div>

            <form method="POST" action="{{ route('admin.rute_jadwal.store') }}" id="jadwalForm">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rute <span class="text-danger">*</span></label>
                        <input type="number" name="id_rute" class="form-control @error('id_rute') is-invalid @enderror" 
                               value="{{ old('id_rute') }}" required />
                        @error('id_rute')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shuttle <span class="text-danger">*</span></label>
                        <input type="number" name="id_shuttle" class="form-control @error('id_shuttle') is-invalid @enderror" 
                               value="{{ old('id_shuttle') }}" required />
                        @error('id_shuttle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal') }}" required />
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Berangkat <span class="text-danger">*</span></label>
                        <input type="time" name="jam_berangkat" class="form-control @error('jam_berangkat') is-invalid @enderror" 
                               value="{{ old('jam_berangkat') }}" required />
                        @error('jam_berangkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Driver Selection (only shown/required in direct_assign mode) -->
                @if($currentMode === 'direct_assign')
                    <div class="mb-3">
                        <label class="form-label">Driver <span class="text-danger">*</span></label>
                        <select name="id_driver" class="form-control @error('id_driver') is-invalid @enderror" required>
                            <option value="">-- Pilih Driver --</option>
                            {{-- Populate drivers if available --}}
                            @if(isset($drivers) && $drivers->count() > 0)
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('id_driver') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No drivers available</option>
                            @endif
                        </select>
                        @error('id_driver')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-warning"></i> In Direct Assign mode, you must select a driver. The schedule will be created with status "active".
                        </small>
                    </div>
                @else
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> In Driver Confirmation mode, drivers will see this schedule as "open" and can claim it.
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Jadwal
                    </button>
                    <a href="{{ route('admin.rute_jadwal.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Real-time validation feedback
    const form = document.getElementById('jadwalForm');
    const requiredFields = form.querySelectorAll('input[required], select[required]');
    
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        field.addEventListener('change', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Prevent double submission
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
</script>
@endpush
