@extends('layouts.app-admin')

@section('title', $title ?? 'Edit Armada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $pageTitle ?? 'Edit Armada' }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.armada.show', $shuttle->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.armada.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.armada.update', $shuttle->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="layanan_id">Layanan <span class="text-danger">*</span></label>
                                    <select name="layanan_id" id="layanan_id" class="form-control @error('layanan_id') is-invalid @enderror" required>
                                        <option value="">Pilih Layanan</option>
                                        @foreach($layanans ?? [] as $layanan)
                                            <option value="{{ $layanan->id_layanan }}" {{ (old('layanan_id') ?? $shuttle->layanan_id) == $layanan->id_layanan ? 'selected' : '' }}>
                                                {{ $layanan->nama_layanan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('layanan_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_shuttle">Nama Shuttle <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_shuttle" id="nama_shuttle" class="form-control @error('nama_shuttle') is-invalid @enderror" value="{{ old('nama_shuttle', $shuttle->nama_shuttle) }}" required>
                                    @error('nama_shuttle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe_shuttle">Tipe Shuttle <span class="text-danger">*</span></label>
                                    <input type="text" name="tipe_shuttle" id="tipe_shuttle" class="form-control @error('tipe_shuttle') is-invalid @enderror" value="{{ old('tipe_shuttle', $shuttle->tipe_shuttle) }}" required>
                                    @error('tipe_shuttle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kapasitas_kursi">Kapasitas Kursi <span class="text-danger">*</span></label>
                                    <input type="number" name="kapasitas_kursi" id="kapasitas_kursi" class="form-control @error('kapasitas_kursi') is-invalid @enderror" value="{{ old('kapasitas_kursi', $shuttle->kapasitas_kursi) }}" min="1" required>
                                    @error('kapasitas_kursi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_kursi">Total Kursi <span class="text-danger">*</span></label>
                                    <input type="number" name="total_kursi" id="total_kursi" class="form-control @error('total_kursi') is-invalid @enderror" value="{{ old('total_kursi', $shuttle->total_kursi) }}" min="1" required>
                                    @error('total_kursi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_polisi">Nomor Polisi <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_polisi" id="nomor_polisi" class="form-control @error('nomor_polisi') is-invalid @enderror" value="{{ old('nomor_polisi', $shuttle->nomor_polisi) }}" required>
                                    @error('nomor_polisi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ (old('status') ?? $shuttle->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ (old('status') ?? $shuttle->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fasilitas">Fasilitas</label>
                            <textarea name="fasilitas" id="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" rows="3">{{ old('fasilitas', $shuttle->fasilitas) }}</textarea>
                            @error('fasilitas')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gambar_depan">Gambar Depan</label>
                                    <input type="file" name="gambar_depan" id="gambar_depan" class="form-control @error('gambar_depan') is-invalid @enderror" accept="image/*">
                                    @if($shuttle->gambar_depan)
                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Gambar saat ini akan dipertahankan.</small>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('shuttles/' . $shuttle->gambar_depan) }}" alt="Depan" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    @error('gambar_depan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gambar_samping">Gambar Samping</label>
                                    <input type="file" name="gambar_samping" id="gambar_samping" class="form-control @error('gambar_samping') is-invalid @enderror" accept="image/*">
                                    @if($shuttle->gambar_samping)
                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Gambar saat ini akan dipertahankan.</small>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('shuttles/' . $shuttle->gambar_samping) }}" alt="Samping" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    @error('gambar_samping')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gambar_belakang">Gambar Belakang</label>
                                    <input type="file" name="gambar_belakang" id="gambar_belakang" class="form-control @error('gambar_belakang') is-invalid @enderror" accept="image/*">
                                    @if($shuttle->gambar_belakang)
                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Gambar saat ini akan dipertahankan.</small>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('shuttles/' . $shuttle->gambar_belakang) }}" alt="Belakang" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    @error('gambar_belakang')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gambar_interior">Gambar Interior</label>
                                    <input type="file" name="gambar_interior" id="gambar_interior" class="form-control @error('gambar_interior') is-invalid @enderror" accept="image/*">
                                    @if($shuttle->gambar_interior)
                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Gambar saat ini akan dipertahankan.</small>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('shuttles/' . $shuttle->gambar_interior) }}" alt="Interior" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    @error('gambar_interior')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.armada.show', $shuttle->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.armada.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize any additional JavaScript if needed
});
</script>
@endsection
