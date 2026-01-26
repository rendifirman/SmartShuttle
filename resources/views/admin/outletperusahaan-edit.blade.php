@extends('layouts.app-admin')

@section('title', 'Edit Outlet')

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
    transition: background-color 0.3s;
}
.btn-back:hover {
    background: #5a6268;
}

/* ================= FORM CARD ================= */
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.form-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 12px;
    font-size: 20px;
    color: #0b2a4a;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    font-size: 14px;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
    color: #333;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
}
textarea {
    resize: none;
    min-height: 80px;
}

/* ================= FACILITIES CHECKBOXES ================= */
.facilities-section {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.facilities-section h4 {
    color: #0b2a4a;
    margin-bottom: 15px;
    font-size: 18px;
}
.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 10px;
}
.facility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    border-radius: 6px;
    background: #f8f9fa;
    transition: all 0.3s;
    border: 1px solid #e9ecef;
}
.facility-item:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}
.facility-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #1e88e5;
    cursor: pointer;
}
.facility-item label {
    font-size: 14px;
    color: #555;
    margin: 0;
    cursor: pointer;
    flex: 1;
}
.facility-item.selected {
    background: #e3f2fd;
    border-color: #bbdefb;
}
.facility-item.selected label {
    color: #1565c0;
    font-weight: 500;
}

/* ================= FORM ACTIONS ================= */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.btn-save {
    background: #0b2a4a;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-save:hover {
    background: #1a3a5f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(12, 45, 72, 0.2);
}
.btn-reset {
    background: #ff6a00;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-reset:hover {
    background: #e55c00;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
}

/* ================= ALERTS ================= */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ================= SELECT2 STYLING ================= */
.select2-container--default .select2-selection--single {
    border: 1px solid #ddd;
    border-radius: 6px;
    height: 44px;
    padding: 8px 12px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
    color: #333;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-save,
    .btn-reset {
        width: 100%;
        text-align: center;
    }

    .page-container {
        padding: 15px;
    }
    
    .facilities-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .facilities-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Edit Outlet</h2>
        <a href="{{ route('admin.outletperusahaan') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Display success/error messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- FORM -->
    <form action="{{ route('admin.outletperusahaan.update', $outlet->id) }}" method="POST" enctype="multipart/form-data" id="editOutletForm">
        @csrf
        @method('PUT')

        <div class="form-card">
            <h3><i class="fas fa-edit"></i> Edit Informasi Outlet</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="branch_id">Cabang <span style="color: red;">*</span></label>
                    <select name="branch_id" id="branch_id" class="select2" required>
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $outlet->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }} - {{ $branch->kota }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_outlet">Nama Outlet <span style="color: red;">*</span></label>
                    <input type="text" name="nama_outlet" id="nama_outlet" required
                           value="{{ old('nama_outlet', $outlet->nama_outlet) }}"
                           placeholder="Masukkan nama outlet">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="alamat_lengkap">Alamat Lengkap <span style="color: red;">*</span></label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" required rows="3"
                              placeholder="Masukkan alamat lengkap outlet">{{ old('alamat_lengkap', $outlet->alamat_lengkap) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="telepon">Telepon <span style="color: red;">*</span></label>
                    <input type="text" name="telepon" id="telepon" required
                           value="{{ old('telepon', $outlet->telepon) }}"
                           placeholder="Masukkan nomor telepon">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $outlet->email) }}"
                           placeholder="Masukkan alamat email">
                </div>

                <!-- TIPE OUTLET - YANG DIGUNAKAN -->
                <div class="form-group">
                    <label for="tipe_outlet">Tipe Outlet <span style="color: red;">*</span></label>
                    <select name="tipe_outlet" id="tipe_outlet" class="form-control" required>
                        <option value="">Pilih Tipe Outlet</option>
                        <option value="mall" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'mall') ? 'selected' : '' }}>Mall</option>
                        <option value="pusat_perbelanjaan" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'pusat_perbelanjaan') ? 'selected' : '' }}>Pusat Perbelanjaan</option>
                        <option value="perkantoran" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'perkantoran') ? 'selected' : '' }}>Perkantoran</option>
                        <option value="stasiun" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'stasiun') ? 'selected' : '' }}>Stasiun</option>
                        <option value="bandara" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'bandara') ? 'selected' : '' }}>Bandara</option>
                        <option value="jalan_utama" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'jalan_utama') ? 'selected' : '' }}>Jalan Utama</option>
                        <option value="kawasan_komersial" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'kawasan_komersial') ? 'selected' : '' }}>Kawasan Komersial</option>
                        <option value="perumahan" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'perumahan') ? 'selected' : '' }}>Perumahan</option>
                        <option value="kampus" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'kampus') ? 'selected' : '' }}>Kampus</option>
                        <option value="rumah_sakit" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'rumah_sakit') ? 'selected' : '' }}>Rumah Sakit</option>
                        <option value="hotel" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'hotel') ? 'selected' : '' }}>Hotel</option>
                        <option value="wisata" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'wisata') ? 'selected' : '' }}>Wisata</option>
                        <option value="pusat_kota" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'pusat_kota') ? 'selected' : '' }}>Pusat Kota</option>
                        <option value="lainnya" {{ (old('tipe_outlet', $outlet->tipe_outlet) == 'lainnya') ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kapasitas_parkir">Kapasitas Parkir</label>
                    <input type="number" name="kapasitas_parkir" id="kapasitas_parkir" min="0"
                           value="{{ old('kapasitas_parkir', $outlet->kapasitas_parkir) }}"
                           placeholder="Masukkan kapasitas parkir">
                </div>

                <div class="form-group">
                    <label for="zona_pelayanan">Zona Pelayanan</label>
                    <input type="text" name="zona_pelayanan" id="zona_pelayanan"
                           value="{{ old('zona_pelayanan', $outlet->zona_pelayanan) }}"
                           placeholder="Masukkan zona pelayanan">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jam_operasional">Jam Operasional</label>
                    <input type="text" name="jam_operasional" id="jam_operasional"
                           value="{{ old('jam_operasional', $outlet->jam_operasional) }}"
                           placeholder="Contoh: 08:00 - 20:00">
                </div>

                <div class="form-group">
                    <label for="status">Status <span style="color: red;">*</span></label>
                    <select name="status" id="status" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status', $outlet->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $outlet->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="maintenance" {{ old('status', $outlet->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            <!-- Fasilitas -->
            <div class="facilities-section">
                <h4><i class="fas fa-star"></i> Fasilitas Outlet</h4>
                
                <div class="facilities-grid">
                    @php
                        $allFacilitiesList = [
                            'Ruang Tunggu' => 'Ruang Tunggu',
                            'Toilet' => 'Toilet', 
                            'AC' => 'AC',
                            'WiFi' => 'WiFi',
                            'Food Court' => 'Food Court',
                            'ATM' => 'ATM',
                            'Musholla' => 'Musholla',
                            'Parkir Luas' => 'Parkir Luas',
                            'Cafe' => 'Cafe',
                            'Restoran' => 'Restoran',
                            'Mini Market' => 'Mini Market',
                            'Toilet Disabilitas' => 'Toilet Disabilitas',
                            'Ruang Menyusui' => 'Ruang Menyusui',
                            'Area Merokok' => 'Area Merokok',
                            '24 Jam' => '24 Jam',
                            'Informasi Tiket' => 'Informasi Tiket',
                            'Charger USB' => 'Charger USB',
                            'Layanan Tiket Online' => 'Layanan Tiket Online',
                            'Layanan Antar Jemput' => 'Layanan Antar Jemput',
                            'Bagasi' => 'Bagasi',
                            'Asuransi Perjalanan' => 'Asuransi Perjalanan',
                        ];
                        
                        $selectedFacilities = $selectedFacilities ?? [];
                    @endphp
                    
                    @foreach($allFacilitiesList as $key => $label)
                        @php
                            $isSelected = in_array($key, $selectedFacilities);
                        @endphp
                        <div class="facility-item {{ $isSelected ? 'selected' : '' }}">
                            <input type="checkbox" 
                                   name="fasilitas[]" 
                                   value="{{ $key }}" 
                                   id="facility_{{ \Illuminate\Support\Str::slug($key) }}"
                                   {{ $isSelected ? 'checked' : '' }}>
                            <label for="facility_{{ \Illuminate\Support\Str::slug($key) }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <div class="form-group" style="margin-top: 20px;">
                    <label for="fasilitas_tambahan">Tambahkan Fasilitas Lain (dipisah koma):</label>
                    <input type="text" name="fasilitas_tambahan" id="fasilitas_tambahan"
                           class="form-control" 
                           placeholder="Contoh: Ruang Rapat, Lounge, dll.">
                </div>
            </div>

            <!-- Foto Outlet -->
            <div class="form-row">
                <div class="form-group">
                    <label for="foto_outlet">Foto Outlet</label>
                    <input type="file" name="foto_outlet" id="foto_outlet" 
                           accept="image/*" 
                           onchange="previewImage(this)">
                    
                    @if($outlet->foto_outlet)
                    <div style="margin-top: 10px;">
                        <img src="{{ asset($outlet->foto_outlet) }}" 
                             alt="Foto Outlet" 
                             style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                        <p style="font-size: 12px; color: #666; margin-top: 5px;">
                            Foto saat ini
                        </p>
                    </div>
                    @endif
                    
                    <div id="imagePreview" style="display: none; margin-top: 10px;">
                        <img id="preview" src="#" alt="Preview" 
                             style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save" id="submitBtn">
                    <i class="fas fa-save"></i> Update Outlet
                </button>
                <button type="button" class="btn-reset" id="resetBtn">
                    <i class="fas fa-undo"></i> Reset Form
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Pilih Cabang",
        allowClear: true
    });
    
    // Form validation
    const form = document.getElementById('editOutletForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const tipeOutlet = document.getElementById('tipe_outlet').value;
            
            if (!tipeOutlet) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Tipe Outlet Kosong',
                    text: 'Silakan pilih tipe outlet',
                    confirmButtonColor: '#d33',
                });
                return false;
            }
            
            // Show loading
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.disabled = true;
            
            return true;
        });
    }
    
    // Reset form handler
    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Reset Form?',
                text: "Semua perubahan akan direset ke nilai awal dari database.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff6a00',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset tipe_outlet ke nilai database
                    const originalTipeOutlet = "{{ $outlet->tipe_outlet ?? '' }}";
                    document.getElementById('tipe_outlet').value = originalTipeOutlet;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Form telah direset',
                        text: 'Form telah dikembalikan ke nilai awal',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    }
    
    // Facility checkboxes
    document.querySelectorAll('input[name="fasilitas[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parent = this.closest('.facility-item');
            if (this.checked) {
                parent.classList.add('selected');
            } else {
                parent.classList.remove('selected');
            }
        });
    });
    
    // Image preview function
    window.previewImage = function(input) {
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            imagePreview.style.display = 'none';
        }
    };
});

// SweetAlert notifications
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
});
@endif

@if(session('error'))
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
});
@endif
</script>
@endpush