@extends('layouts.app-admin')

@section('title', 'Tambah Rute')

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
    font-size: 14px;
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
.form-group small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
}

/* ================= ALERT ================= */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}
.alert-error {
    background-color: #fdecea;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.alert ul {
    margin: 0;
    padding-left: 20px;
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
    text-decoration: none;
}
.btn-cancel:hover {
    background: #5a6268;
}

/* ================= RUTE PEMBERHENTIAN ================= */
.rute-pemberhentian-container {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    margin-top: 10px;
}
.rute-pemberhentian-container textarea {
    font-family: 'Courier New', monospace;
    font-size: 13px;
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
    .btn-reset,
    .btn-cancel {
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
        <h2>Tambah Data Rute</h2>
        <a href="{{ route('admin.rute') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rute
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <div class="form-card">
        <h3>Informasi Rute</h3>

        <form method="POST" action="{{ route('admin.rute.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="layanan_id">Layanan <span style="color: red">*</span></label>
                    <select name="layanan_id" id="layanan_id" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" {{ old('layanan_id') == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }} ({{ $layanan->kode_layanan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="kode_rute">Kode Rute <span style="color: red">*</span></label>
                    <input type="text" name="kode_rute" id="kode_rute"
                           value="{{ old('kode_rute') }}"
                           placeholder="Contoh: JKT-BAL-001" required>
                    <small>Format: KOTAASAL-KOTATUJUAN-001</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nama_rute">Nama Rute <span style="color: red">*</span></label>
                    <input type="text" name="nama_rute" id="nama_rute"
                           value="{{ old('nama_rute') }}"
                           placeholder="Contoh: Jakarta - Bali" required>
                </div>

                <div class="form-group">
                    <label for="kota_asal">Kota Asal <span style="color: red">*</span></label>
                    <input type="text" name="kota_asal" id="kota_asal"
                           value="{{ old('kota_asal') }}"
                           placeholder="Contoh: Jakarta" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kota_tujuan">Kota Tujuan <span style="color: red">*</span></label>
                    <input type="text" name="kota_tujuan" id="kota_tujuan"
                           value="{{ old('kota_tujuan') }}"
                           placeholder="Contoh: Bali" required>
                </div>

                <div class="form-group">
                    <label for="durasi">Durasi (HH:MM) <span style="color: red">*</span></label>
                    <input type="text" name="durasi" id="durasi"
                           value="{{ old('durasi') }}"
                           placeholder="Contoh: 18:00" required>
                    <small>Format: jam:menit (contoh: 4:30 untuk 4 jam 30 menit)</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jarak">Jarak (km) <span style="color: red">*</span></label>
                    <input type="number" name="jarak" id="jarak"
                           value="{{ old('jarak') }}"
                           placeholder="Contoh: 850" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="harga_dasar">Harga Dasar (Rp) <span style="color: red">*</span></label>
                    <input type="text" name="harga_dasar" id="harga_dasar"
                           value="{{ old('harga_dasar') }}"
                           placeholder="Contoh: 350000" required>
                    <small>Isikan angka tanpa tanda titik</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status <span style="color: red">*</span></label>
                    <select name="status" id="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <!-- Spacer untuk menjaga grid -->
                </div>
            </div>

            <div class="form-group">
                <label for="rute_pemberhentian">Rute Pemberhentian (JSON)</label>
                <div class="rute-pemberhentian-container">
                    <textarea name="rute_pemberhentian" id="rute_pemberhentian" rows="5"
                              placeholder='Contoh: [{"kota": "Jakarta", "outlets": ["Sudirman", "Blok M"], "durasi_singgah": 15}, {"kota": "Bekasi", "outlets": ["Bekasi Barat"], "durasi_singgah": 20}]'>{{ old('rute_pemberhentian') }}</textarea>
                    <small>Format JSON untuk pemberhentian. Biarkan kosong jika tidak ada.</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Rute
                </button>
                <button type="reset" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <a href="{{ route('admin.rute') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate kode rute based on kota_asal and kota_tujuan
    const kotaAsalInput = document.getElementById('kota_asal');
    const kotaTujuanInput = document.getElementById('kota_tujuan');
    const kodeRuteInput = document.getElementById('kode_rute');
    const formElement = document.querySelector('form');

    function generateKodeRute() {
        if (kotaAsalInput.value && kotaTujuanInput.value) {
            const asal = kotaAsalInput.value.substring(0, 3).toUpperCase();
            const tujuan = kotaTujuanInput.value.substring(0, 3).toUpperCase();

            // Generate number based on existing rutes (you might want to fetch this from server)
            const randomNum = Math.floor(Math.random() * 100) + 1;
            kodeRuteInput.value = `${asal}-${tujuan}-${randomNum.toString().padStart(3, '0')}`;
        }
    }

    kotaAsalInput.addEventListener('change', generateKodeRute);
    kotaTujuanInput.addEventListener('change', generateKodeRute);

    // Format harga input
    const hargaInput = document.getElementById('harga_dasar');
    const jarakInput = document.getElementById('jarak');

    // Format saat input kehilangan fokus
    hargaInput.addEventListener('blur', function() {
        if (this.value) {
            const numericValue = this.value.replace(/[^\d]/g, '');
            if (numericValue) {
                this.value = parseInt(numericValue).toLocaleString('id-ID');
            }
        }
    });

    // Hapus format saat input mendapatkan fokus
    hargaInput.addEventListener('focus', function() {
        this.value = this.value.replace(/[^\d]/g, '');
    });

    // Handle form submission to clean up formatted values
    formElement.addEventListener('submit', function(e) {
        // Remove formatting from harga_dasar
        const hargaValue = hargaInput.value.replace(/[^\d]/g, '');
        hargaInput.value = hargaValue || '0';
        
        // Remove formatting from jarak
        const jarakValue = jarakInput.value.replace(/[^\d.,]/g, '');
        jarakInput.value = jarakValue || '0';
    });

    // Validasi durasi format
    const durasiInput = document.getElementById('durasi');
    durasiInput.addEventListener('blur', function() {
        const value = this.value;
        if (value && !/^\d{1,2}:\d{2}$/.test(value)) {
            alert('Format durasi harus HH:MM (contoh: 4:30 untuk 4 jam 30 menit)');
            this.focus();
        }
    });
});

// Fungsi untuk reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang.')) {
        document.querySelector('form').reset();

        // Reset harga format
        const hargaInput = document.getElementById('harga_dasar');
        hargaInput.value = '';
    }
}
</script>
@endsection
