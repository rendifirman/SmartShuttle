@extends('layouts.app-admin')

@section('title', 'Tambah Pemesanan Perjalanan')

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
    text-decoration: none;
}
.btn-back:hover {
    background: #5a6268;
}

/* ================= TABS ================= */
.tabs {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}
.tab {
    padding: 10px 0;
    color: #777;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    transition: color 0.3s;
}
.tab.active {
    color: #ff6a00;
}
.tab.active::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #ff6a00;
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
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
.form-group label.required::after {
    content: ' *';
    color: #dc3545;
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
.form-group input.error,
.form-group select.error,
.form-group textarea.error {
    border-color: #dc3545;
}
.form-error {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: none;
}
.form-error.show {
    display: block;
}
textarea {
    resize: none;
    min-height: 80px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}

/* ================= PASSENGER ITEMS ================= */
.passenger-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #ff6a00;
}
.passenger-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.passenger-header h4 {
    margin: 0;
    color: #0b2a4a;
    font-size: 16px;
}
.passenger-remove {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 5px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

/* ================= PRICING SUMMARY ================= */
.pricing-summary {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 25px;
    border: 2px solid #0b2a4a;
}
.pricing-summary h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #0b2a4a;
    font-size: 18px;
}
.pricing-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}
.pricing-row:last-child {
    border-bottom: none;
}
.pricing-label {
    color: #666;
    font-weight: 500;
}
.pricing-value {
    color: #0b2a4a;
    font-weight: 600;
}
.pricing-total {
    font-size: 20px;
    color: #ff6a00;
    font-weight: 700;
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
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-save:hover {
    background: #1a3a5f;
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
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-reset:hover {
    background: #e55c00;
}
.btn-cancel {
    background: #6c757d;
    color: #fff;
    padding: 12px 30px;
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
.btn-cancel:hover {
    background: #5a6268;
}
.btn-add-passenger {
    background: #28a745;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-add-passenger:hover {
    background: #218838;
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

/* ================= RESPONSIVE ================= */
@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-save,
    .btn-reset,
    .btn-cancel {
        width: 100%;
        text-align: center;
    }

    .tabs {
        flex-wrap: wrap;
        gap: 10px;
    }

    .tab {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 15px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .btn-back {
        width: 100%;
        justify-content: center;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= CREATE PAGE ================= -->
    <div id="form-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Tambah Pemesanan Perjalanan</h2>
            <a href="{{ route('admin.perjalanan') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <!-- TABS -->
        <div class="tabs">
            <a href="{{ route('admin.perjalanan') }}" class="tab">Tampilan Hasil</a>
            <div class="tab active">Input Pemesanan</div>
        </div>

        <!-- Display success/error messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('admin.perjalanan.store') }}" method="POST" id="perjalananForm">
            @csrf

            <!-- DATA CUSTOMER -->
            <div class="form-card">
                <h3>Data Customer</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer_id" class="required">Pilih Customer</label>
                        <select name="customer_id" id="customer_id">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama_pemesan" class="required">Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" id="nama_pemesan"
                               placeholder="Masukkan Nama Pemesan"
                               value="{{ old('nama_pemesan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="telepon_pemesan" class="required">Nomor Telepon</label>
                        <input type="tel" name="telepon_pemesan" id="telepon_pemesan"
                               placeholder="081234567890"
                               value="{{ old('telepon_pemesan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email_pemesan" class="required">Email</label>
                        <input type="email" name="email_pemesan" id="email_pemesan"
                               placeholder="email@contoh.com"
                               value="{{ old('email_pemesan') }}" required>
                    </div>
                </div>
            </div>

            <!-- DATA JADWAL -->
            <div class="form-card">
                <h3>Data Jadwal Perjalanan</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="rute_id" class="required">Rute</label>
                        <select name="rute_id" id="rute_id" required onchange="loadJadwal()">
                            <option value="">-- Pilih Rute --</option>
                            @foreach($rutes as $rute)
                                <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                    {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_keberangkatan" class="required">Tanggal Keberangkatan</label>
                        <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan"
                               value="{{ old('tanggal_keberangkatan') }}" required onchange="loadJadwal()">
                    </div>
                    <div class="form-group">
                        <label for="jadwal_id" class="required">Jadwal (Waktu & Shuttle)</label>
                        <select name="jadwal_id" id="jadwal_id" required onchange="updatePricing()">
                            <option value="">-- Pilih Jadwal --</option>
                            @if(old('jadwal_id') && old('rute_id') && old('tanggal_keberanglement'))
                                @php
                                    $jadwals = \App\Models\Jadwal::where('rute_id', old('rute_id'))
                                        ->whereDate('tanggal_keberangkatan', old('tanggal_keberangkatan'))
                                        ->get();
                                @endphp
                                @foreach($jadwals as $jadwal)
                                    <option value="{{ $jadwal->id }}" data-harga="{{ $jadwal->harga_total }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                        {{ $jadwal->waktu_keberangkatan }} - {{ $jadwal->shuttle->nama_shuttle ?? 'Shuttle' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <!-- DATA PENUMPANG -->
            <div class="form-card">
                <h3>Data Penumpang</h3>
                <div class="form-group">
                    <label for="jumlah_penumpang" class="required">Jumlah Penumpang</label>
                    <input type="number" name="jumlah_penumpang" id="jumlah_penumpang"
                           min="1" max="10" value="{{ old('jumlah_penumpang', 1) }}" 
                           required onchange="generatePassengerForms()">
                </div>

                <div id="passengerForms">
                    @if(old('jumlah_penumpang'))
                        @for($i = 1; $i <= old('jumlah_penumpang'); $i++)
                            <div class="passenger-item" data-index="{{ $i }}">
                                <div class="passenger-header">
                                    <h4>Penumpang {{ $i }}</h4>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="required">Nama Lengkap</label>
                                        <input type="text" name="penumpang[{{ $i }}][nama_lengkap]" 
                                               value="{{ old('penumpang.'.$i.'.nama_lengkap') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">NIK</label>
                                        <input type="text" name="penumpang[{{ $i }}][nik]" 
                                               value="{{ old('penumpang.'.$i.'.nik') }}" maxlength="16" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Jenis Kelamin</label>
                                        <select name="penumpang[{{ $i }}][jenis_kelamin]" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ old('penumpang.'.$i.'.jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('penumpang.'.$i.'.jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Nomor Telepon</label>
                                        <input type="tel" name="penumpang[{{ $i }}][telepon]" 
                                               value="{{ old('penumpang.'.$i.'.telepon') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email</label>
                                        <input type="email" name="penumpang[{{ $i }}][email]" 
                                               value="{{ old('penumpang.'.$i.'.email') }}" required>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @else
                        <div class="passenger-item" data-index="1">
                            <div class="passenger-header">
                                <h4>Penumpang 1</h4>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="required">Nama Lengkap</label>
                                    <input type="text" name="penumpang[1][nama_lengkap]" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">NIK</label>
                                    <input type="text" name="penumpang[1][nik]" maxlength="16" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Jenis Kelamin</label>
                                    <select name="penumpang[1][jenis_kelamin]" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">Nomor Telepon</label>
                                    <input type="tel" name="penumpang[1][telepon]" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Email</label>
                                    <input type="email" name="penumpang[1][email]" required>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PROMO & CATATAN -->
            <div class="form-card">
                <h3>Promo & Catatan</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="kode_promo">Kode Promo</label>
                        <input type="text" name="kode_promo" id="kode_promo"
                               placeholder="Masukkan kode promo"
                               value="{{ old('kode_promo') }}">
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea name="catatan" id="catatan" placeholder="Masukkan catatan tambahan">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- PRICING SUMMARY -->
            <div class="pricing-summary">
                <h3>Ringkasan Pembayaran</h3>
                <div class="pricing-row">
                    <span class="pricing-label">Harga per Kursi</span>
                    <span class="pricing-value" id="hargaPerKursi">Rp 0</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Jumlah Penumpang</span>
                    <span class="pricing-value" id="jumlahPenumpangDisplay">1</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Subtotal</span>
                    <span class="pricing-value" id="subtotal">Rp 0</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Diskon Promo</span>
                    <span class="pricing-value" id="diskon">Rp 0</span>
                </div>
                <div class="pricing-row" style="border-top: 2px solid #0b2a4a; margin-top: 10px; padding-top: 15px;">
                    <span class="pricing-label" style="font-size: 16px;">Total Bayar</span>
                    <span class="pricing-value pricing-total" id="totalBayar">Rp 0</span>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <button type="reset" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Pemesanan
                </button>
            </div>
        </form>

    </div>

</div>

<script>
// Global variables
let jadwalData = [];
let currentPassengerCount = {{ old('jumlah_penumpang', 1) }};

// Load jadwal based on selected rute and date
function loadJadwal() {
    const rute_id = document.getElementById('rute_id').value;
    const tanggal = document.getElementById('tanggal_keberangkatan').value;
    const jadwalSelect = document.getElementById('jadwal_id');

    if (!rute_id || !tanggal) {
        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
        updatePricing();
        return;
    }

    // Fetch available jadwal
    fetch(`/admin/api/jadwal?rute_id=${rute_id}&tanggal=${tanggal}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Jadwal --</option>';
            jadwalData = data.jadwal || [];
            
            if (jadwalData.length > 0) {
                jadwalData.forEach(jadwal => {
                    const selected = '{{ old('jadwal_id') }}' == jadwal.id ? 'selected' : '';
                    options += `<option value="${jadwal.id}" data-harga="${jadwal.harga_total}" ${selected}>
                        ${jadwal.waktu_keberangkatan} - ${jadwal.shuttle?.nama_shuttle || 'Shuttle'}
                    </option>`;
                });
            } else {
                options = '<option value="">-- Tidak ada jadwal tersedia --</option>';
            }
            jadwalSelect.innerHTML = options;
            updatePricing();
        })
        .catch(error => console.error('Error:', error));
}

// Generate passenger forms
function generatePassengerForms() {
    const count = parseInt(document.getElementById('jumlah_penumpang').value) || 1;
    const container = document.getElementById('passengerForms');
    
    if (count < 1 || count > 10) {
        alert('Jumlah penumpang minimal 1 dan maksimal 10');
        document.getElementById('jumlah_penumpang').value = currentPassengerCount;
        return;
    }

    currentPassengerCount = count;
    let html = '';

    for (let i = 1; i <= count; i++) {
        html += `
        <div class="passenger-item" data-index="${i}">
            <div class="passenger-header">
                <h4>Penumpang ${i}</h4>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="required">Nama Lengkap</label>
                    <input type="text" name="penumpang[${i}][nama_lengkap]" required>
                </div>
                <div class="form-group">
                    <label class="required">NIK</label>
                    <input type="text" name="penumpang[${i}][nik]" maxlength="16" required>
                </div>
                <div class="form-group">
                    <label class="required">Jenis Kelamin</label>
                    <select name="penumpang[${i}][jenis_kelamin]" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="required">Nomor Telepon</label>
                    <input type="tel" name="penumpang[${i}][telepon]" required>
                </div>
                <div class="form-group">
                    <label class="required">Email</label>
                    <input type="email" name="penumpang[${i}][email]" required>
                </div>
            </div>
        </div>
        `;
    }

    container.innerHTML = html;
    updatePricing();
}

// Update pricing summary
function updatePricing() {
    const jadwalSelect = document.getElementById('jadwal_id');
    const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
    const hargaPerKursi = parseFloat(selectedOption?.dataset?.harga) || 0;
    const jumlahPenumpang = parseInt(document.getElementById('jumlah_penumpang').value) || 1;
    
    const subtotal = hargaPerKursi * jumlahPenumpang;
    const diskon = 0; // Will be calculated server-side
    
    document.getElementById('hargaPerKursi').textContent = formatRupiah(hargaPerKursi);
    document.getElementById('jumlahPenumpangDisplay').textContent = jumlahPenumpang + ' Orang';
    document.getElementById('subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('diskon').textContent = formatRupiah(diskon);
    document.getElementById('totalBayar').textContent = formatRupiah(subtotal - diskon);
}

// Format Rupiah
function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

// Reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form?')) {
        document.getElementById('perjalananForm').reset();
        generatePassengerForms();
    }
}

// Handle customer selection
document.getElementById('customer_id')?.addEventListener('change', function() {
    if (this.value) {
        const option = this.options[this.selectedIndex];
        // If customer is selected, could auto-fill some fields
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePricing();
});
</script>

@endsection
