@extends('layouts.admin')

@section('title', 'Manajemen Promo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="fas fa-tags me-2"></i>Manajemen Promo
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromoModal">
            <i class="fas fa-plus me-1"></i>Tambah Promo Baru
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Total Promo</div>
                        <div class="h4">{{ $totalPromos }}</div>
                    </div>
                    <i class="fas fa-tags fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Promo Aktif</div>
                        <div class="h4">{{ $activePromos }}</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Promo Nonaktif</div>
                        <div class="h4">{{ $inactivePromos }}</div>
                    </div>
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Promo Kadaluarsa</div>
                        <div class="h4">{{ $expiredPromos }}</div>
                    </div>
                    <i class="fas fa-calendar-times fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>Filter Promo
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.promo') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kode Promo</label>
                        <input type="text" class="form-control" name="kode_promo" value="{{ request('kode_promo') }}" placeholder="Masukkan kode promo">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nama Promo</label>
                        <input type="text" class="form-control" name="nama_promo" value="{{ request('nama_promo') }}" placeholder="Cari nama promo">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Jenis Diskon</label>
                        <select class="form-select" name="jenis_diskon">
                            <option value="">Semua</option>
                            <option value="persentase" {{ request('jenis_diskon') == 'persentase' ? 'selected' : '' }}>Persentase</option>
                            <option value="nominal" {{ request('jenis_diskon') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="kategori_promo">
                            <option value="">Semua</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('kategori_promo') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Pencarian</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari promo...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('admin.promo') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Promo Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>Daftar Promo
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="promoTable">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Kode</th>
                            <th>Nama Promo</th>
                            <th width="100">Jenis</th>
                            <th width="100">Diskon</th>
                            <th width="100">Kategori</th>
                            <th width="100">Kuota</th>
                            <th width="100">Status</th>
                            <th width="100">Tipe</th>
                            <th width="150">Periode</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promos as $index => $promo)
                            <tr>
                                <td>{{ $promos->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $promo->kode_promo }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $promo->nama_promo }}</div>
                                    <small class="text-muted">{{ Str::limit($promo->deskripsi, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $promo->jenis_diskon == 'persentase' ? 'success' : 'primary' }}">
                                        {{ $promo->jenis_diskon == 'persentase' ? 'Persen' : 'Nominal' }}
                                    </span>
                                </td>
                                <td>
                                    @if($promo->jenis_diskon == 'persentase')
                                        {{ $promo->nilai_diskon }}%
                                    @else
                                        Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $categoryColors = [
                                            'keluarga' => 'bg-warning',
                                            'membership' => 'bg-danger',
                                            'umum' => 'bg-success',
                                        ];
                                    @endphp
                                    <span class="badge {{ $categoryColors[$promo->kategori_promo] ?? 'bg-secondary' }}">
                                        {{ ucfirst($promo->kategori_promo) }}
                                    </span>
                                </td>
                                <td>
                                    @if($promo->kuota)
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $percentage = $promo->kuota > 0 ? ($promo->terpakai / $promo->kuota) * 100 : 0;
                                                $progressClass = $percentage >= 80 ? 'bg-danger' : ($percentage >= 50 ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                style="width: {{ $percentage }}%" 
                                                aria-valuenow="{{ $percentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $promo->terpakai }}/{{ $promo->kuota }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-info">Unlimited</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isExpired = now()->greaterThan($promo->tanggal_berakhir);
                                        $isActive = $promo->status && !$isExpired && (!$promo->kuota || $promo->terpakai < $promo->kuota);
                                    @endphp
                                    @if($isExpired)
                                        <span class="badge bg-danger">Kadaluarsa</span>
                                    @elseif($promo->status && $isActive)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $promo->tipe_promo == 'all' ? 'primary' : 'info' }}">
                                        {{ ucfirst($promo->tipe_promo) }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <div><strong>Mulai:</strong> {{ $promo->tanggal_mulai->format('d/m/Y') }}</div>
                                        <div><strong>Akhir:</strong> {{ $promo->tanggal_berakhir->format('d/m/Y') }}</div>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailPromoModal"
                                                onclick="showPromoDetail({{ $promo->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPromoModal"
                                                onclick="editPromo({{ $promo->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $promo->id }}, '{{ $promo->nama_promo }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <h5>Tidak ada promo ditemukan</h5>
                                        <p class="text-muted">Mulai dengan menambahkan promo baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan {{ $promos->firstItem() ?? 0 }} - {{ $promos->lastItem() ?? 0 }} dari {{ $promos->total() }} promo
                </div>
                {{ $promos->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Promo Modal -->
<div class="modal fade" id="addPromoModal" tabindex="-1" aria-labelledby="addPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addPromoForm" action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPromoModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Promo Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_promo" class="form-label">Kode Promo *</label>
                            <input type="text" class="form-control" id="kode_promo" name="kode_promo" required
                                   placeholder="Contoh: PROMO50" maxlength="20">
                            <div class="form-text">Kode unik untuk promo</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_promo" class="form-label">Nama Promo *</label>
                            <input type="text" class="form-control" id="nama_promo" name="nama_promo" required
                                   placeholder="Contoh: Promo Tahun Baru">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="jenis_diskon" class="form-label">Jenis Diskon *</label>
                            <select class="form-select" id="jenis_diskon" name="jenis_diskon" required>
                                <option value="">Pilih Jenis</option>
                                <option value="persentase">Persentase (%)</option>
                                <option value="nominal">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nilai_diskon" class="form-label">Nilai Diskon *</label>
                            <input type="number" class="form-control" id="nilai_diskon" name="nilai_diskon" required
                                   step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="maksimal_diskon" class="form-label">Maksimal Diskon</label>
                            <input type="number" class="form-control" id="maksimal_diskon" name="maksimal_diskon"
                                   step="0.01" min="0" placeholder="0 untuk tidak terbatas">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="minimal_pembelian" class="form-label">Minimal Pembelian (Rp)</label>
                            <input type="number" class="form-control" id="minimal_pembelian" name="minimal_pembelian"
                                   step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="min_tiket" class="form-label">Minimal Tiket</label>
                            <input type="number" class="form-control" id="min_tiket" name="min_tiket"
                                   min="0" placeholder="0 untuk tidak ada">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kuota" class="form-label">Kuota Penggunaan</label>
                            <input type="number" class="form-control" id="kuota" name="kuota"
                                   min="0" placeholder="0 untuk unlimited">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategori_promo" class="form-label">Kategori *</label>
                            <select class="form-select" id="kategori_promo" name="kategori_promo" required>
                                <option value="">Pilih Kategori</option>
                                <option value="umum">Umum</option>
                                <option value="keluarga">Keluarga</option>
                                <option value="membership">Membership</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tipe_promo" class="form-label">Tipe Layanan *</label>
                            <select class="form-select" id="tipe_promo" name="tipe_promo" required>
                                <option value="">Pilih Tipe</option>
                                <option value="all">Semua Layanan</option>
                                <option value="shuttle">Shuttle</option>
                                <option value="paket">Kirim Paket</option>
                                <option value="sewa">Sewa Armada</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="khusus_member" class="form-label">Khusus Member</label>
                            <select class="form-select" id="khusus_member" name="khusus_member">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir *</label>
                            <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Promo *</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required
                                  placeholder="Deskripsi singkat tentang promo"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="pesan_error" class="form-label">Pesan Error</label>
                        <input type="text" class="form-control" id="pesan_error" name="pesan_error"
                               placeholder="Pesan yang ditampilkan jika promo tidak valid">
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Promo</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, JPEG. Maks: 2MB</div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                        <label class="form-check-label" for="status">
                            Aktifkan promo
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Promo Modal -->
<div class="modal fade" id="editPromoModal" tabindex="-1" aria-labelledby="editPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPromoForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editPromoModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Promo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form fields will be populated by JavaScript -->
                    <div id="editPromoContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data promo...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Promo Modal -->
<div class="modal fade" id="detailPromoModal" tabindex="-1" aria-labelledby="detailPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailPromoModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detail Promo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailPromoContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail promo...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus promo <strong id="deletePromoName"></strong>?</p>
                <p class="text-danger">Promo yang sudah digunakan tidak dapat dipulihkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deletePromoForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form validation for add promo
    document.getElementById('addPromoForm').addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('tanggal_mulai').value);
        const endDate = new Date(document.getElementById('tanggal_berakhir').value);
        
        if (endDate < startDate) {
            e.preventDefault();
            alert('Tanggal berakhir tidak boleh sebelum tanggal mulai!');
            document.getElementById('tanggal_berakhir').focus();
        }
        
        const maxDiscount = parseFloat(document.getElementById('maksimal_diskon').value);
        const discount = parseFloat(document.getElementById('nilai_diskon').value);
        const jenisDiskon = document.getElementById('jenis_diskon').value;
        
        if (jenisDiskon === 'nominal' && maxDiscount > 0 && maxDiscount !== discount) {
            e.preventDefault();
            alert('Untuk diskon nominal, maksimal diskon harus sama dengan nilai diskon!');
            document.getElementById('maksimal_diskon').focus();
        }
    });

    // Dynamic form behavior
    document.getElementById('jenis_diskon').addEventListener('change', function() {
        const jenis = this.value;
        const nilaiDiskon = document.getElementById('nilai_diskon');
        const maksDiskon = document.getElementById('maksimal_diskon');
        
        if (jenis === 'persentase') {
            nilaiDiskon.placeholder = 'Contoh: 25 untuk 25%';
            nilaiDiskon.max = 100;
            maksDiskon.disabled = false;
        } else {
            nilaiDiskon.placeholder = 'Contoh: 50000';
            nilaiDiskon.max = null;
            maksDiskon.value = nilaiDiskon.value;
            maksDiskon.disabled = true;
        }
    });

    // Show promo detail
    function showPromoDetail(promoId) {
        fetch(`/api/admin/promo/${promoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const promo = data.promo;
                    let html = `
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Status Promo</h6>
                                        ${getStatusBadge(promo)}
                                        <div class="mt-2">
                                            <small class="text-muted">Digunakan: ${promo.terpakai} dari ${promo.kuota || '∞'}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Diskon</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Jenis Diskon:</small><br>
                                                <strong>${promo.jenis_diskon === 'persentase' ? 'Persentase' : 'Nominal'}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Nilai Diskon:</small><br>
                                                <strong>${formatDiscount(promo)}</strong>
                                            </div>
                                        </div>
                                        ${promo.maksimal_diskon > 0 ? `
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <small class="text-muted">Maksimal Diskon:</small><br>
                                                    <strong>Rp ${formatNumber(promo.maksimal_diskon)}</strong>
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Promo</label>
                                    <div class="form-control bg-light">${promo.kode_promo}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Promo</label>
                                    <div class="form-control bg-light">${promo.nama_promo}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <div class="form-control bg-light">${promo.kategori_promo.charAt(0).toUpperCase() + promo.kategori_promo.slice(1)}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Layanan</label>
                                    <div class="form-control bg-light">${promo.tipe_promo.charAt(0).toUpperCase() + promo.tipe_promo.slice(1)}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Khusus Member</label>
                                    <div class="form-control bg-light">${promo.khusus_member ? 'Ya' : 'Tidak'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Minimal Pembelian</label>
                                    <div class="form-control bg-light">Rp ${formatNumber(promo.minimal_pembelian)}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Minimal Tiket</label>
                                    <div class="form-control bg-light">${promo.min_tiket || 'Tidak ada'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <div class="form-control bg-light">${formatDate(promo.tanggal_mulai)}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Berakhir</label>
                                    <div class="form-control bg-light">${formatDate(promo.tanggal_berakhir)}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <div class="form-control bg-light" style="min-height: 80px;">${promo.deskripsi}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan Error</label>
                            <div class="form-control bg-light">${promo.pesan_error || '-'}</div>
                        </div>

                        ${promo.gambar ? `
                            <div class="mb-3">
                                <label class="form-label">Gambar Promo</label>
                                <div class="text-center">
                                    <img src="/storage/${promo.gambar}" alt="${promo.nama_promo}" 
                                         class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            </div>
                        ` : ''}
                    `;
                    
                    document.getElementById('detailPromoContent').innerHTML = html;
                    document.getElementById('detailPromoModalLabel').innerHTML = `<i class="fas fa-info-circle me-2"></i>${promo.nama_promo}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detailPromoContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Gagal memuat data promo. Silakan coba lagi.
                    </div>
                `;
            });
    }

    // Edit promo
    function editPromo(promoId) {
        fetch(`/api/admin/promo/${promoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const promo = data.promo;
                    
                    let html = `
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_kode_promo" class="form-label">Kode Promo *</label>
                                <input type="text" class="form-control" id="edit_kode_promo" name="kode_promo" 
                                       value="${promo.kode_promo}" required maxlength="20">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_promo" class="form-label">Nama Promo *</label>
                                <input type="text" class="form-control" id="edit_nama_promo" name="nama_promo" 
                                       value="${promo.nama_promo}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_jenis_diskon" class="form-label">Jenis Diskon *</label>
                                <select class="form-select" id="edit_jenis_diskon" name="jenis_diskon" required>
                                    <option value="persentase" ${promo.jenis_diskon === 'persentase' ? 'selected' : ''}>Persentase (%)</option>
                                    <option value="nominal" ${promo.jenis_diskon === 'nominal' ? 'selected' : ''}>Nominal (Rp)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_nilai_diskon" class="form-label">Nilai Diskon *</label>
                                <input type="number" class="form-control" id="edit_nilai_diskon" name="nilai_diskon" 
                                       value="${promo.nilai_diskon}" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_maksimal_diskon" class="form-label">Maksimal Diskon</label>
                                <input type="number" class="form-control" id="edit_maksimal_diskon" name="maksimal_diskon"
                                       value="${promo.maksimal_diskon}" step="0.01" min="0" 
                                       ${promo.jenis_diskon === 'nominal' ? 'disabled' : ''}>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_minimal_pembelian" class="form-label">Minimal Pembelian (Rp)</label>
                                <input type="number" class="form-control" id="edit_minimal_pembelian" name="minimal_pembelian"
                                       value="${promo.minimal_pembelian}" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_min_tiket" class="form-label">Minimal Tiket</label>
                                <input type="number" class="form-control" id="edit_min_tiket" name="min_tiket"
                                       value="${promo.min_tiket || ''}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_kuota" class="form-label">Kuota Penggunaan</label>
                                <input type="number" class="form-control" id="edit_kuota" name="kuota"
                                       value="${promo.kuota || ''}" min="0" placeholder="0 untuk unlimited">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_kategori_promo" class="form-label">Kategori *</label>
                                <select class="form-select" id="edit_kategori_promo" name="kategori_promo" required>
                                    <option value="umum" ${promo.kategori_promo === 'umum' ? 'selected' : ''}>Umum</option>
                                    <option value="keluarga" ${promo.kategori_promo === 'keluarga' ? 'selected' : ''}>Keluarga</option>
                                    <option value="membership" ${promo.kategori_promo === 'membership' ? 'selected' : ''}>Membership</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_tipe_promo" class="form-label">Tipe Layanan *</label>
                                <select class="form-select" id="edit_tipe_promo" name="tipe_promo" required>
                                    <option value="all" ${promo.tipe_promo === 'all' ? 'selected' : ''}>Semua Layanan</option>
                                    <option value="shuttle" ${promo.tipe_promo === 'shuttle' ? 'selected' : ''}>Shuttle</option>
                                    <option value="paket" ${promo.tipe_promo === 'paket' ? 'selected' : ''}>Kirim Paket</option>
                                    <option value="sewa" ${promo.tipe_promo === 'sewa' ? 'selected' : ''}>Sewa Armada</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_khusus_member" class="form-label">Khusus Member</label>
                                <select class="form-select" id="edit_khusus_member" name="khusus_member">
                                    <option value="0" ${!promo.khusus_member ? 'selected' : ''}>Tidak</option>
                                    <option value="1" ${promo.khusus_member ? 'selected' : ''}>Ya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                                <input type="date" class="form-control" id="edit_tanggal_mulai" name="tanggal_mulai" 
                                       value="${promo.tanggal_mulai.split(' ')[0]}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tanggal_berakhir" class="form-label">Tanggal Berakhir *</label>
                                <input type="date" class="form-control" id="edit_tanggal_berakhir" name="tanggal_berakhir" 
                                       value="${promo.tanggal_berakhir.split(' ')[0]}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi Promo *</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required>${promo.deskripsi}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_pesan_error" class="form-label">Pesan Error</label>
                            <input type="text" class="form-control" id="edit_pesan_error" name="pesan_error"
                                   value="${promo.pesan_error || ''}" placeholder="Pesan yang ditampilkan jika promo tidak valid">
                        </div>

                        <div class="mb-3">
                            <label for="edit_gambar" class="form-label">Gambar Promo</label>
                            ${promo.gambar ? `
                                <div class="mb-2">
                                    <img src="/storage/${promo.gambar}" alt="${promo.nama_promo}" 
                                         class="img-fluid rounded" style="max-height: 100px;">
                                </div>
                            ` : ''}
                            <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
                            <div class="form-text">Kosongkan jika tidak ingin mengubah gambar</div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1" 
                                   ${promo.status ? 'checked' : ''}>
                            <label class="form-check-label" for="edit_status">
                                Aktifkan promo
                            </label>
                        </div>
                    `;
                    
                    document.getElementById('editPromoContent').innerHTML = html;
                    document.getElementById('editPromoModalLabel').innerHTML = `<i class="fas fa-edit me-2"></i>Edit ${promo.nama_promo}`;
                    document.getElementById('editPromoForm').action = `/admin/promo/${promoId}`;
                    
                    // Enable dynamic behavior for edit form
                    document.getElementById('edit_jenis_diskon').addEventListener('change', function() {
                        const jenis = this.value;
                        const maksDiskon = document.getElementById('edit_maksimal_diskon');
                        
                        if (jenis === 'nominal') {
                            const nilaiDiskon = document.getElementById('edit_nilai_diskon').value;
                            maksDiskon.value = nilaiDiskon;
                            maksDiskon.disabled = true;
                        } else {
                            maksDiskon.disabled = false;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('editPromoContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Gagal memuat data promo. Silakan coba lagi.
                    </div>
                `;
            });
    }

    // Confirm delete
    function confirmDelete(promoId, promoName) {
        document.getElementById('deletePromoName').textContent = promoName;
        document.getElementById('deletePromoForm').action = `/admin/promo/${promoId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Helper functions
    function getStatusBadge(promo) {
        const now = new Date();
        const endDate = new Date(promo.tanggal_berakhir);
        const isExpired = now > endDate;
        const isActive = promo.status && !isExpired && (!promo.kuota || promo.terpakai < promo.kuota);
        
        if (isExpired) {
            return '<span class="badge bg-danger">Kadaluarsa</span>';
        } else if (isActive) {
            return '<span class="badge bg-success">Aktif</span>';
        } else {
            return '<span class="badge bg-secondary">Nonaktif</span>';
        }
    }

    function formatDiscount(promo) {
        if (promo.jenis_diskon === 'persentase') {
            return `${promo.nilai_diskon}%`;
        } else {
            return `Rp ${formatNumber(promo.nilai_diskon)}`;
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Auto set dates for new promo
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        const nextMonthStr = nextMonth.toISOString().split('T')[0];
        
        document.getElementById('tanggal_mulai').value = today;
        document.getElementById('tanggal_berakhir').value = nextMonthStr;
        document.getElementById('tanggal_mulai').min = today;
        document.getElementById('tanggal_berakhir').min = today;
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorMessages = @json($errors->all());
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: errorMessages.join('<br>'),
            timer: 5000,
            showConfirmButton: true
        });
    });
</script>
@endif
@endsection

@section('styles')
<style>
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-bar {
        font-size: 11px;
        font-weight: bold;
    }
    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    .empty-state i {
        opacity: 0.5;
    }
    .modal-header {
        padding: 1rem 1.5rem;
    }
    .modal-title i {
        font-size: 1.2em;
    }
    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 1;
    }
</style>
@endsection