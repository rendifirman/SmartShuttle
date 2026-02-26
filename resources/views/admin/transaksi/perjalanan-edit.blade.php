@extends('layouts.app-admin')

@section('title', 'Edit Pemesanan Perjalanan')

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
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-back:hover {
    background: #5a6268;
}

.btn-save {
    background: #28a745;
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

.btn-save:hover {
    background: #218838;
}

.booking-info {
    background: #e3f2fd;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 25px;
    border-left: 4px solid #1565c0;
}

.booking-info h3 {
    margin: 0 0 10px 0;
    color: #0b2a4a;
    font-size: 18px;
}

.booking-info .info-row {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.booking-info .info-item {
    display: flex;
    flex-direction: column;
}

.booking-info .info-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 2px;
}

.booking-info .info-value {
    font-size: 14px;
    font-weight: 600;
    color: #0b2a4a;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

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

.form-group input[readonly] {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

textarea {
    resize: none;
    min-height: 80px;
}

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

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    justify-content: flex-end;
}

.btn-save-form {
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

.btn-save-form:hover {
    background: #1a3a5f;
}

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

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

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

    .btn-save-form {
        width: 100%;
        text-align: center;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .booking-info .info-row {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 15px;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">
    <div id="edit-page">
        <div class="page-header">
            <h2>Edit Pemesanan Perjalanan</h2>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.perjalanan') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" form="editPerjalananForm" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="booking-info">
            <h3>Kode Booking: {{ $pemesanan->kode_booking }}</h3>
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($pemesanan->status == 'pending')
                            <span class="status-badge status-pending">PENDING</span>
                        @elseif($pemesanan->status == 'completed' || $pemesanan->status == 'selesai')
                            <span class="status-badge status-completed">SELESAI</span>
                        @elseif($pemesanan->status == 'cancelled' || $pemesanan->status == 'dibatalkan')
                            <span class="status-badge status-cancelled">DIBATALKAN</span>
                        @else
                            {{ strtoupper($pemesanan->status) }}
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Pemesanan</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($pemesanan->created_at)->locale('id')->format('d M Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Pembayaran</span>
                    <span class="info-value">Rp {{ number_format($pemesanan->total_bayar ?? $pemesanan->harga_total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Mohon periksa kesalahan di bawah ini:
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="editPerjalananForm" method="POST" action="{{ route('admin.perjalanan.update', $pemesanan->id) }}">
            @csrf
            @method('PUT')

            <div class="form-card">
                <h3>Data Customer</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_pemesan" class="required">Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" id="nama_pemesan" value="{{ old('nama_pemesan', $pemesanan->nama_pemesan) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="telepon_pemesan" class="required">Nomor Telepon</label>
                        <input type="tel" name="telepon_pemesan" id="telepon_pemesan" value="{{ old('telepon_pemesan', $pemesanan->telepon_pemesan) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email_pemesan" class="required">Email</label>
                        <input type="email" name="email_pemesan" id="email_pemesan" value="{{ old('email_pemesan', $pemesanan->email_pemesan) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h3>Data Jadwal Perjalanan</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="rute_id" class="required">Rute</label>
                        <select name="rute_id" id="rute_id" required onchange="loadJadwal()">
                            <option value="">-- Pilih Rute --</option>
                            @foreach($rutes as $rute)
                                <option value="{{ $rute->id }}" {{ old('rute_id', $selectedRuteId) == $rute->id ? 'selected' : '' }}>
                                    {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_keberangkatan" class="required">Tanggal Keberangkatan</label>
                        <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', $pemesanan->jadwal->tanggal_keberangkatan ?? '') }}" required onchange="loadJadwal()">
                    </div>
                    <div class="form-group">
                        <label for="jadwal_id" class="required">Jadwal (Waktu & Shuttle)</label>
                        <select name="jadwal_id" id="jadwal_id" required onchange="updateScheduleDisplay()">
                            <option value="">-- Pilih Jadwal --</option>
                            @if($jadwals ?? false)
                                @foreach($jadwals as $jadwal)
                                    <option value="{{ $jadwal->id }}" 
                                        data-harga="{{ $jadwal->harga_total }}" 
                                        data-waktu="{{ $jadwal->waktu_keberangkatan }}"
                                        data-shuttle="{{ $jadwal->shuttle->nama_shuttle ?? 'Shuttle' }}"
                                        {{ old('jadwal_id', $pemesanan->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                                        {{ $jadwal->waktu_keberangkatan }} - {{ $jadwal->shuttle->nama_shuttle ?? 'Shuttle' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h3>Data Penumpang</h3>
                <div class="form-group">
                    <label for="jumlah_penumpang" class="required">Jumlah Penumpang</label>
                    <input type="number" name="jumlah_penumpang" id="jumlah_penumpang" min="1" max="10" value="{{ old('jumlah_penumpang', $pemesanan->jumlah_penumpang ?? 1) }}" required onchange="generatePassengerForms()">
                </div>

                <div id="passengerForms">
                    @if($pemesanan->detailPenumpangs && $pemesanan->detailPenumpangs->count() > 0)
                        @foreach($pemesanan->detailPenumpangs as $index => $penumpang)
                            <div class="passenger-item" data-index="{{ $index + 1 }}">
                                <div class="passenger-header">
                                    <h4>Penumpang {{ $index + 1 }}</h4>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="required">Nama Lengkap</label>
                                        <input type="text" name="penumpang[{{ $index + 1 }}][nama_lengkap]" value="{{ old('penumpang.'.($index + 1).'.nama_lengkap', $penumpang->nama_lengkap) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">NIK</label>
                                        <input type="text" name="penumpang[{{ $index + 1 }}][nik]" value="{{ old('penumpang.'.($index + 1).'.nik', $penumpang->nik) }}" maxlength="16" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Jenis Kelamin</label>
                                        <select name="penumpang[{{ $index + 1 }}][jenis_kelamin]" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ old('penumpang.'.($index + 1).'.jenis_kelamin', $penumpang->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('penumpang.'.($index + 1).'.jenis_kelamin', $penumpang->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="tel" name="penumpang[{{ $index + 1 }}][telepon]" value="{{ old('penumpang.'.($index + 1).'.telepon', $penumpang->telepon ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="penumpang[{{ $index + 1 }}][email]" value="{{ old('penumpang.'.($index + 1).'.email', $penumpang->email ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                                    <label>Nomor Telepon</label>
                                    <input type="tel" name="penumpang[1][telepon]">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="penumpang[1][email]">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-card">
                <h3>Promo & Catatan</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="kode_promo">Kode Promo</label>
                        <input type="text" name="kode_promo" id="kode_promo" placeholder="Masukkan kode promo" value="{{ old('kode_promo', $pemesanan->kode_promo ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea name="catatan" id="catatan" placeholder="Masukkan catatan tambahan">{{ old('catatan', $pemesanan->catatan ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status Pemesanan</label>
                        <select name="status" id="status">
                            <option value="menunggu_pembayaran" {{ old('status', $pemesanan->status) == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="menunggu_konfirmasi" {{ old('status', $pemesanan->status) == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="diproses" {{ old('status', $pemesanan->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dibayar" {{ old('status', $pemesanan->status) == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="selesai" {{ old('status', $pemesanan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $pemesanan->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pricing-summary">
                <h3>Ringkasan Pembayaran</h3>
                <div class="pricing-row">
                    <span class="pricing-label">Harga per Kursi</span>
                    <span class="pricing-value" id="hargaPerKursi">Rp {{ number_format($pemesanan->jadwal->harga_total ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Jumlah Penumpang</span>
                    <span class="pricing-value" id="jumlahPenumpangDisplay">{{ $pemesanan->jumlah_penumpang ?? 1 }} Orang</span>
                </div>
                <div class="pricing-row">
                    <span class="pricing-label">Subtotal</span>
                    <span class="pricing-value" id="subtotal">Rp {{ number_format(($pemesanan->jadwal->harga_total ?? 0) * ($pemesanan->jumlah_penumpang ?? 1), 0, ',', '.') }}</span>
                </div>
                <div class="pricing-row" style="border-top: 2px solid #0b2a4a; margin-top: 10px; padding-top: 15px;">
                    <span class="pricing-label" style="font-size: 16px;">Total Bayar</span>
                    <span class="pricing-value pricing-total" id="totalBayar">Rp {{ number_format($pemesanan->total_bayar ?? $pemesanan->harga_total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save-form">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let jadwalData = [];
let currentPassengerCount = {{ $pemesanan->jumlah_penumpang ?? 1 }};

function loadJadwal() {
    const rute_id = document.getElementById('rute_id').value;
    const tanggal = document.getElementById('tanggal_keberangkatan').value;
    const jadwalSelect = document.getElementById('jadwal_id');

    if (!rute_id || !tanggal) {
        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
        updatePricing();
        return;
    }

    fetch(`/admin/api/jadwal?rute_id=${rute_id}&tanggal=${tanggal}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Jadwal --</option>';
            jadwalData = data.jadwal || [];
            
            if (jadwalData.length > 0) {
                jadwalData.forEach(jadwal => {
                    const selected = '{{ $pemesanan->jadwal_id }}' == jadwal.id ? 'selected' : '';
                    options += `<option value="${jadwal.id}" data-harga="${jadwal.harga_total}" data-waktu="${jadwal.waktu_keberangkatan}" data-shuttle="${jadwal.shuttle?.nama_shuttle || 'Shuttle'}" ${selected}>
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
                    <label>Nomor Telepon</label>
                    <input type="tel" name="penumpang[${i}][telepon]">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="penumpang[${i}][email]">
                </div>
            </div>
        </div>
        `;
    }

    container.innerHTML = html;
    updatePricing();
}

function updatePricing() {
    const jadwalSelect = document.getElementById('jadwal_id');
    const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
    const hargaPerKursi = parseFloat(selectedOption?.dataset?.harga) || 0;
    const jumlahPenumpang = parseInt(document.getElementById('jumlah_penumpang').value) || 1;
    
    const subtotal = hargaPerKursi * jumlahPenumpang;
    
    document.getElementById('hargaPerKursi').textContent = formatRupiah(hargaPerKursi);
    document.getElementById('jumlahPenumpangDisplay').textContent = jumlahPenumpang + ' Orang';
    document.getElementById('subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('totalBayar').textContent = formatRupiah(subtotal);
}

function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

// Update pricing when jadwal selection changes
function updateScheduleDisplay() {
    updatePricing();
}

document.addEventListener('DOMContentLoaded', function() {
    loadJadwal();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
