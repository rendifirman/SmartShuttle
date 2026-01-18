@extends('layouts.app-admin')

@section('title', 'Detail Outlet')

@push('styles')
<style>
/* ================= BASE ================= */
.page-container {
    padding: 24px 30px;
    background: #f8f7f3;
    min-height: 100vh;
}

/* ================= HEADER ================= */
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
    margin-bottom: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

/* ================= DETAIL CARD ================= */
.detail-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.detail-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 12px;
    font-size: 20px;
    color: #0b2a4a;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.info-group {
    margin-bottom: 20px;
}
.info-label {
    font-size: 14px;
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    display: block;
}
.info-value {
    font-size: 15px;
    color: #333;
    font-weight: 500;
}
.info-value strong {
    color: #0b2a4a;
}

/* Status Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}
.status-active {
    background: #b8f0a3;
    color: #1e7e34;
}
.status-inactive {
    background: #ff9a9a;
    color: #8b0000;
}
.status-maintenance {
    background: #ffd699;
    color: #cc6600;
}

/* Type Badges */
.type-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}
.type-regular {
    background: #e3f2fd;
    color: #1565c0;
}
.type-premium {
    background: #fff3e0;
    color: #ef6c00;
}
.type-express {
    background: #f3e5f5;
    color: #7b1fa2;
}

/* ================= FACILITIES ================= */
.facilities-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}
.facility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.facility-item i {
    color: #1e88e5;
}

/* ================= ACTION BUTTONS ================= */
.action-buttons {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.btn-edit {
    background: #f9b000;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-edit:hover {
    background: #e09b00;
}
.btn-delete {
    background: #dc3545;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-delete:hover {
    background: #c82333;
}
.btn-print {
    background: #17a2b8;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-print:hover {
    background: #138496;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-edit,
    .btn-delete,
    .btn-print {
        width: 100%;
        text-align: center;
    }

    .page-container {
        padding: 15px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Detail Outlet</h2>
        <a href="{{ route('admin.outletperusahaan') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- DETAIL CARD -->
    <div class="detail-card">
        <h3>
            <span>{{ $outlet->nama_outlet }}</span>
            @php
                $statusClass = '';
                if($outlet->status == 'aktif') $statusClass = 'status-active';
                elseif($outlet->status == 'nonaktif') $statusClass = 'status-inactive';
                else $statusClass = 'status-maintenance';
                
                $typeClass = '';
                if($outlet->tipe_outlet == 'premium') $typeClass = 'type-premium';
                elseif($outlet->tipe_outlet == 'express') $typeClass = 'type-express';
                else $typeClass = 'type-regular';
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ ucfirst($outlet->status) }}
            </span>
        </h3>

        <div class="info-grid">
            <div class="info-group">
                <span class="info-label">Kode Outlet</span>
                <div class="info-value"><strong>{{ $outlet->kode_outlet }}</strong></div>
            </div>

            <div class="info-group">
                <span class="info-label">Cabang</span>
                <div class="info-value">{{ $outlet->branch->nama_cabang ?? '-' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Kota</span>
                <div class="info-value">{{ $outlet->kota }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Tipe Outlet</span>
                <div class="info-value">
                    <span class="type-badge {{ $typeClass }}">
                        {{ ucfirst($outlet->tipe_outlet) }}
                    </span>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Telepon</span>
                <div class="info-value">{{ $outlet->telepon }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Email</span>
                <div class="info-value">{{ $outlet->email ?? '-' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Alamat Lengkap</span>
                <div class="info-value">{{ $outlet->alamat_lengkap }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Kapasitas Parkir</span>
                <div class="info-value">{{ $outlet->kapasitas_parkir ?? '0' }} kendaraan</div>
            </div>

            <div class="info-group">
                <span class="info-label">Zona Pelayanan</span>
                <div class="info-value">{{ $outlet->zona_pelayanan ?? '-' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Jam Operasional</span>
                <div class="info-value">{{ $outlet->jam_operasional ?? '-' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Dibuat Pada</span>
                <div class="info-value">{{ $outlet->created_at->format('d M Y H:i') }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Diperbarui Pada</span>
                <div class="info-value">{{ $outlet->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- FACILITIES -->
        @if(count($fasilitasArray) > 0)
        <div class="facilities-section">
            <h4><i class="fas fa-th-list"></i> Fasilitas</h4>
            <div class="facilities-grid">
                @foreach($fasilitasArray as $fasilitas)
                <div class="facility-item">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $fasilitas }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ACTION BUTTONS -->
        <div class="action-buttons">
            <a href="{{ route('admin.outletperusahaan.edit', $outlet->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit Outlet
            </a>
            <form action="{{ route('admin.outletperusahaan.destroy', $outlet->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    <i class="fas fa-trash"></i> Hapus Outlet
                </button>
            </form>
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak Detail
            </button>
        </div>
    </div>

</div>

<script>
function confirmDelete(event) {
    event.preventDefault();
    const form = event.target.closest('form');

    Swal.fire({
        title: 'Hapus Outlet?',
        text: "Data outlet akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection