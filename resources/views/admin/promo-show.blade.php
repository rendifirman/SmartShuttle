@extends('layouts.app-admin')

@section('title', 'Detail Promo')

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
.status-aktif {
    background: #b8f0a3;
    color: #1e7e34;
}
.status-nonaktif {
    background: #ff9a9a;
    color: #8b0000;
}
.status-expired {
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
.type-umum {
    background: #e3f2fd;
    color: #1565c0;
}
.type-keluarga {
    background: #fff3e0;
    color: #ef6c00;
}
.type-membership {
    background: #f3e5f5;
    color: #7b1fa2;
}

/* ================= IMAGE SECTION ================= */
.image-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.image-section h4 {
    margin-bottom: 15px;
    color: #0b2a4a;
}
.promo-image {
    max-width: 300px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* ================= DESCRIPTION ================= */
.description-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.description-section h4 {
    margin-bottom: 15px;
    color: #0b2a4a;
}
.description-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ff6a00;
    white-space: pre-line;
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
        <h2>Detail Promo</h2>
        <a href="{{ route('admin.promo') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- DETAIL CARD -->
    <div class="detail-card">
        <h3>
            <span>{{ $promo->nama_promo }}</span>
            @php
                $typeClass = '';
                if($promo->kategori_promo == 'keluarga') $typeClass = 'type-keluarga';
                elseif($promo->kategori_promo == 'membership') $typeClass = 'type-membership';
                else $typeClass = 'type-umum';
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ $statusText }}
            </span>
        </h3>

        <div class="info-grid">
            <div class="info-group">
                <span class="info-label">Kode Promo</span>
                <div class="info-value"><strong>{{ $promo->kode_promo }}</strong></div>
            </div>

            <div class="info-group">
                <span class="info-label">Nama Promo</span>
                <div class="info-value">{{ $promo->nama_promo }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Jenis Diskon</span>
                <div class="info-value">{{ ucfirst($promo->jenis_diskon) }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Nilai Diskon</span>
                <div class="info-value">
                    @if($promo->jenis_diskon == 'persentase')
                        {{ $promo->nilai_diskon }}%
                    @else
                        Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                    @endif
                </div>
            </div>

            @if($promo->maksimal_diskon)
            <div class="info-group">
                <span class="info-label">Maksimal Diskon</span>
                <div class="info-value">Rp {{ number_format($promo->maksimal_diskon, 0, ',', '.') }}</div>
            </div>
            @endif

            @if($promo->minimal_pembelian)
            <div class="info-group">
                <span class="info-label">Minimal Pembelian</span>
                <div class="info-value">Rp {{ number_format($promo->minimal_pembelian, 0, ',', '.') }}</div>
            </div>
            @endif

            @if($promo->min_tiket)
            <div class="info-group">
                <span class="info-label">Minimal Tiket</span>
                <div class="info-value">{{ $promo->min_tiket }} tiket</div>
            </div>
            @endif

            @if($promo->kuota)
            <div class="info-group">
                <span class="info-label">Kuota Penggunaan</span>
                <div class="info-value">{{ $promo->kuota - $promo->terpakai }} tersisa dari {{ $promo->kuota }}</div>
            </div>
            @endif

            <div class="info-group">
                <span class="info-label">Kategori Promo</span>
                <div class="info-value">
                    <span class="type-badge {{ $typeClass }}">
                        {{ ucfirst($promo->kategori_promo) }}
                    </span>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Tipe Promo</span>
                <div class="info-value">{{ ucfirst($promo->tipe_promo) }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Tanggal Mulai</span>
                <div class="info-value">{{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d M Y') }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Tanggal Berakhir</span>
                <div class="info-value">{{ \Carbon\Carbon::parse($promo->tanggal_berakhir)->format('d M Y') }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Khusus Member</span>
                <div class="info-value">{{ $promo->khusus_member ? 'Ya' : 'Tidak' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Status</span>
                <div class="info-value">{{ $promo->status ? 'Aktif' : 'Nonaktif' }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Sudah Digunakan</span>
                <div class="info-value">{{ $promo->terpakai }} kali</div>
            </div>

            <div class="info-group">
                <span class="info-label">Dibuat Pada</span>
                <div class="info-value">{{ $promo->created_at->format('d M Y H:i') }}</div>
            </div>

            <div class="info-group">
                <span class="info-label">Diperbarui Pada</span>
                <div class="info-value">{{ $promo->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- IMAGE SECTION -->
        @if($promo->gambar)
        <div class="image-section">
            <h4><i class="fas fa-image"></i> Gambar Promo</h4>
            <img src="{{ asset('storage/' . $promo->gambar) }}" alt="Gambar Promo" class="promo-image">
        </div>
        @endif

        <!-- DESCRIPTION -->
        @if($promo->deskripsi)
        <div class="description-section">
            <h4><i class="fas fa-file-alt"></i> Deskripsi Promo</h4>
            <div class="description-content">
                {{ $promo->deskripsi }}
            </div>
        </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if($promo->pesan_error)
        <div class="description-section">
            <h4><i class="fas fa-exclamation-triangle"></i> Pesan Error</h4>
            <div class="description-content" style="border-left-color: #dc3545;">
                {{ $promo->pesan_error }}
            </div>
        </div>
        @endif

        <!-- ACTION BUTTONS -->
        <div class="action-buttons">
            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit Promo
            </a>
            <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    <i class="fas fa-trash"></i> Hapus Promo
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
        title: 'Hapus Promo?',
        text: "Data promo akan dihapus secara permanen!",
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
