@extends('layouts.app-admin')

@section('title', 'Detail Pegawai - ' . $pegawai->name)

@push('styles')
<style>
.page-container {
    padding: 24px 30px;
    background: #f8f7f3;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 22px;
    color: #0b2a4a;
    margin: 0;
}

.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back:hover {
    background: #5a6268;
}

.detail-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    margin-bottom: 25px;
}

.detail-header {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.detail-avatar {
    flex-shrink: 0;
}

.detail-avatar img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1e88e5;
}

.detail-info h3 {
    margin: 0 0 5px 0;
    color: #0b2a4a;
    font-size: 20px;
}

.detail-info p {
    margin: 5px 0;
    color: #666;
    font-size: 14px;
}

.role-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-right: 8px;
    margin-top: 8px;
}

.role-badge.role-admin-pusat {
    background-color: #ff6a00;
    color: white;
}

.role-badge.role-admin-cabang {
    background-color: #ffc107;
    color: #333;
}

.role-badge.role-driver {
    background-color: #28a745;
    color: white;
}

.detail-section {
    margin-bottom: 30px;
}

.detail-section h4 {
    color: #0b2a4a;
    font-size: 16px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1e88e5;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 15px;
}

.detail-field {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
}

.detail-field label {
    display: block;
    color: #666;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.detail-field value {
    display: block;
    color: #0b2a4a;
    font-size: 14px;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-edit, .btn-delete {
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-edit {
    background: #1e88e5;
    color: #fff;
}

.btn-edit:hover {
    background: #0d74d1;
}

.btn-delete {
    background: #dc3545;
    color: #fff;
}

.btn-delete:hover {
    background: #c82333;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.badge-active {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.badge-inactive {
    background-color: #f8d7da;
    color: #721c24;
}
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- HEADER -->
    <div class="page-header">
        <h2>Detail Pegawai</h2>
        <a href="{{ route('admin.pegawai') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- DETAIL CARD -->
    <div class="detail-card">
        <!-- PROFILE HEADER -->
        <div class="detail-header">
            <div class="detail-avatar">
                <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : 'https://i.pravatar.cc/150?u=' . $pegawai->id }}"
                     alt="{{ $pegawai->name }}">
            </div>
            <div class="detail-info">
                <h3>{{ $pegawai->name }}</h3>
                @foreach($pegawai->roles as $role)
                    @php
                        $roleClass = str_replace('_', '-', $role->name);
                    @endphp
                    <span class="role-badge role-{{ $roleClass }}">
                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                    </span>
                @endforeach
                <p style="margin-top: 15px;">
                    <strong>Status:</strong>
                    <span class="status-badge {{ $pegawai->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $pegawai->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- DATA PRIBADI -->
        <div class="detail-section">
            <h4>Data Pribadi</h4>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Nama Lengkap</label>
                    <value>{{ $pegawai->name }}</value>
                </div>
                <div class="detail-field">
                    <label>NIK</label>
                    <value>{{ $pegawai->nik ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Tempat Lahir</label>
                    <value>{{ $pegawai->tempat_lahir ?? '-' }}</value>
                </div>
                <div class="detail-field">
                    <label>Tanggal Lahir</label>
                    <value>
                        @if($pegawai->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y') }}
                        @else
                            -
                        @endif
                    </value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Jenis Kelamin</label>
                    <value>{{ $pegawai->jenis_kelamin ?? '-' }}</value>
                </div>
                <div class="detail-field">
                    <label>Agama</label>
                    <value>{{ $pegawai->agama ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Status Pernikahan</label>
                    <value>{{ $pegawai->status_pernikahan ?? '-' }}</value>
                </div>
                <div class="detail-field">
                    <label>Email</label>
                    <value>{{ $pegawai->email ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Nomor Telepon</label>
                    <value>{{ $pegawai->phone ?? '-' }}</value>
                </div>
                <div class="detail-field">
                    <label>Kontak Darurat</label>
                    <value>{{ $pegawai->kontak_darurat ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-field" style="grid-column: 1 / -1;">
                <label>Alamat</label>
                <value>{{ $pegawai->alamat ?? '-' }}</value>
            </div>
        </div>

        <!-- DATA KEPEGAWAIAN -->
        <div class="detail-section">
            <h4>Data Kepegawaian</h4>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Tanggal Bergabung</label>
                    <value>
                        @if($pegawai->tanggal_bergabung)
                            {{ \Carbon\Carbon::parse($pegawai->tanggal_bergabung)->format('d M Y') }}
                        @else
                            -
                        @endif
                    </value>
                </div>
                <div class="detail-field">
                    <label>Status Pegawai</label>
                    <value>{{ $pegawai->status_pegawai ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Posisi</label>
                    <value>{{ $pegawai->posisi ?? '-' }}</value>
                </div>
                <div class="detail-field">
                    <label>Lokasi Kerja</label>
                    <value>{{ $pegawai->lokasi_kerja ?? '-' }}</value>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-field">
                    <label>Masa Kerja</label>
                    <value>{{ $pegawai->masa_kerja ?? '-' }}</value>
                </div>
                @if($pegawai->branch)
                <div class="detail-field">
                    <label>Cabang</label>
                    <value>{{ $pegawai->branch->nama_cabang }}</value>
                </div>
                @endif
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="detail-section">
            <div class="action-buttons">
                @can('manage_pegawai')
                <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
