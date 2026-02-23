@extends('layouts.app-admin')

@section('title', 'Edit Master Tarif')

@push('styles')
<style>
body {
    background: #f4f6fb;
}

.page-container {
    padding: 25px;
    min-height: 100vh;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.btn-back {
    background: #f0f0f0;
    color: #0b2a4a;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #e0e0e0;
}

.page-header h2 {
    color: #0b2a4a;
    font-size: 24px;
    margin: 0;
    font-weight: 700;
}

.form-container {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    max-width: 900px;
}

.form-section {
    margin-bottom: 30px;
}

.form-section h4 {
    color: #0b2a4a;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section h4 i {
    color: #1e88e5;
    font-size: 18px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 13px;
    font-weight: 700;
    color: #0b2a4a;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group label .required {
    color: #f44336;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    font-family: 'Segoe UI', sans-serif;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
    background: #f8faff;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    font-size: 12px;
    color: #999;
    margin-top: 6px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.btn-submit {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
    color: #fff;
    padding: 14px 32px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #0d74d1 0%, #0a47a3 100%);
    box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
    transform: translateY(-2px);
}

.btn-cancel {
    background: #f0f0f0;
    color: #0b2a4a;
    padding: 14px 32px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel:hover {
    background: #e0e0e0;
}

/* Error Messages */
.alert-danger {
    background: #ffebee;
    border-left: 4px solid #f44336;
    color: #c62828;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-danger ul {
    margin: 10px 0 0 20px;
    padding-left: 0;
}

.alert-danger li {
    margin: 5px 0;
}

.info-box {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 12px;
    border-radius: 4px;
    font-size: 12px;
    color: #1565c0;
    margin-bottom: 20px;
}

@media (max-width: 767px) {
    .form-container {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.master-tarif.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2>Edit Master Tarif</h2>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert-danger">
            <strong>Validasi Gagal!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Info Box -->
    <div class="info-box">
        <i class="fas fa-info-circle"></i> Kode Tarif: <strong>{{ $tarif->kode_tarif }}</strong> |
        Dibuat: <strong>{{ $tarif->created_at->format('d/m/Y H:i') }}</strong>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.master-tarif.update', $tarif->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="form-section">
            <h4><i class="fas fa-info-circle"></i> Informasi Dasar</h4>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Tarif <span class="required">*</span></label>
                    <input type="text" name="nama_tarif" required value="{{ old('nama_tarif', $tarif->nama_tarif) }}" placeholder="Misal: Tarif Reguler">
                </div>
                <div class="form-group">
                    <label>Jenis Tarif <span class="required">*</span></label>
                    <select name="jenis_tarif" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="penumpang" {{ old('jenis_tarif', $tarif->jenis_tarif) == 'penumpang' ? 'selected' : '' }}>Penumpang</option>
                        <option value="paket" {{ old('jenis_tarif', $tarif->jenis_tarif) == 'paket' ? 'selected' : '' }}>Paket</option>
                        <option value="cargo" {{ old('jenis_tarif', $tarif->jenis_tarif) == 'cargo' ? 'selected' : '' }}>Cargo</option>
                        <option value="charter" {{ old('jenis_tarif', $tarif->jenis_tarif) == 'charter' ? 'selected' : '' }}>Charter</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>SK Tarif</label>
                    <input type="text" name="sk_tarif" value="{{ old('sk_tarif', $tarif->sk_tarif) }}" placeholder="Misal: No. SK-2026-001">
                    <div class="form-help">Nomor Surat Keputusan atau referensi peraturan tarif</div>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan', $tarif->keterangan) }}" placeholder="Deskripsi singkat">
                </div>
            </div>
        </div>

        <!-- Harga dan Diskon -->
        <div class="form-section">
            <h4><i class="fas fa-money-bill"></i> Harga dan Diskon</h4>

            <div class="form-row">
                <div class="form-group">
                    <label>Harga Dasar <span class="required">*</span></label>
                    <input type="number" name="harga_dasar" required min="0" step="100" value="{{ old('harga_dasar', $tarif->harga_dasar) }}" placeholder="0">
                    <div class="form-help">Harga dasar tarif sebelum diskon</div>
                </div>
                <div class="form-group">
                    <label>Harga Minimum <span class="required">*</span></label>
                    <input type="number" name="harga_minimum" required min="0" step="100" value="{{ old('harga_minimum', $tarif->harga_minimum) }}" placeholder="0">
                    <div class="form-help">Harga terendah yang diizinkan</div>
                </div>
                <div class="form-group">
                    <label>Harga Maksimum</label>
                    <input type="number" name="harga_maksimum" min="0" step="100" value="{{ old('harga_maksimum', $tarif->harga_maksimum) }}" placeholder="Kosongkan jika tidak dibatasi">
                    <div class="form-help">Biarkan kosong untuk tidak membatasi</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Diskon Persentase (%)</label>
                    <input type="number" name="diskon_persentase" min="0" max="100" step="0.01" value="{{ old('diskon_persentase', $tarif->diskon_persentase) }}" placeholder="0">
                    <div class="form-help">Diskon dalam bentuk persen (0-100%)</div>
                </div>
                <div class="form-group">
                    <label>Diskon Nominal</label>
                    <input type="number" name="diskon_nominal" min="0" step="100" value="{{ old('diskon_nominal', $tarif->diskon_nominal) }}" placeholder="0">
                    <div class="form-help">Diskon dalam bentuk nominal</div>
                </div>
            </div>
        </div>

        <!-- Periode Berlaku -->
        <div class="form-section">
            <h4><i class="fas fa-calendar"></i> Periode Berlaku</h4>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Berlaku Dari</label>
                    <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku', $tarif->tanggal_berlaku?->format('Y-m-d')) }}">
                    <div class="form-help">Tanggal dimulainya tarif berlaku</div>
                </div>
                <div class="form-group">
                    <label>Tanggal Kadaluarsa Hingga</label>
                    <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $tarif->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                    <div class="form-help">Tanggal akhir berlakunya tarif</div>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="form-section">
            <h4><i class="fas fa-sticky-note"></i> Catatan Tambahan</h4>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" placeholder="Catatan atau kondisi khusus untuk tarif ini...">{{ old('catatan', $tarif->catatan) }}</textarea>
            </div>
        </div>

        <!-- Status -->
        <div class="form-section">
            <h4><i class="fas fa-toggle-on"></i> Status</h4>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" required>
                    <option value="aktif" {{ old('status', $tarif->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ old('status', $tarif->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.master-tarif.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection
