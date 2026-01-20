@extends('layouts.app-admin')

@section('title', 'Detail Artikel')

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
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-edit {
    background: #0b2a4a;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-right: 10px;
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
}

/* ================= INFO GRID ================= */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.info-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #0b2a4a;
}
.info-label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.info-value {
    font-size: 16px;
    color: #333;
    font-weight: 500;
}

/* ================= CONTENT SECTION ================= */
.content-section {
    margin-bottom: 30px;
}
.content-section h4 {
    font-size: 18px;
    color: #0b2a4a;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}
.article-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    line-height: 1.6;
    color: #333;
    white-space: pre-wrap;
}

/* ================= IMAGE SECTION ================= */
.image-section {
    margin-bottom: 30px;
}
.image-section h4 {
    font-size: 18px;
    color: #0b2a4a;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}
.article-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,.1);
}

/* ================= STATUS BADGE ================= */
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-published {
    background: #d4edda;
    color: #155724;
}
.status-draft {
    background: #fff3cd;
    color: #856404;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .btn-back,
    .btn-edit {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Detail Artikel</h2>
        <div>
            <button class="btn-edit" onclick="window.location.href='{{ route('admin.artikel.edit', $artikel->id) }}'">
                <i class="fas fa-edit"></i> Edit Artikel
            </button>
            <button class="btn-back" onclick="window.location.href='{{ route('admin.artikel.index') }}'">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </button>
        </div>
    </div>

    <!-- DETAIL CARD -->
    <div class="detail-card">
        <h3>{{ $artikel->judul }}</h3>

        <!-- INFO GRID -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value">{{ ucfirst($artikel->kategori) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Penulis</div>
                <div class="info-value">{{ $artikel->penulis }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Publikasi</div>
                <div class="info-value">{{ $artikel->tanggal_publikasi ? $artikel->tanggal_publikasi->format('d M Y') : '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge {{ $artikel->status ? 'status-published' : 'status-draft' }}">
                        {{ $artikel->status ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Dibuat</div>
                <div class="info-value">{{ $artikel->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Terakhir Diupdate</div>
                <div class="info-value">{{ $artikel->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div class="content-section">
            <h4>Konten Artikel</h4>
            <div class="article-content">
                {{ $artikel->konten }}
            </div>
        </div>

        <!-- IMAGE SECTION -->
        @if($artikel->gambar)
        <div class="image-section">
            <h4>Gambar Artikel</h4>
            <img src="{{ Storage::url($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="article-image">
        </div>
        @endif

    </div>

</div>
@endsection
