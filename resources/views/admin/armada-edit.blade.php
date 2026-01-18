@extends('layouts.app-admin')

@section('title', 'Edit Data Armada')

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

/* ================= KELENGKAPAN DINAMIS ================= */
.kelengkapan-container {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    margin-top: 10px;
}

.kelengkapan-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.kelengkapan-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
    background: white;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.kelengkapan-item input[type="checkbox"] {
    margin: 0;
}

.kelengkapan-item label {
    flex: 1;
    margin: 0;
    font-weight: normal;
    font-size: 13px;
}

.remove-kelengkapan {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 4px;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.add-kelengkapan-form {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.add-kelengkapan-form input {
    flex: 1;
    padding: 10px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.btn-add-kelengkapan {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-add-kelengkapan:hover {
    background: #219653;
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

/* ================= PREVIEW GAMBAR ================= */
.image-preview {
    display: flex;
    gap: 15px;
    margin-top: 10px;
    flex-wrap: wrap;
}

.image-preview-item {
    position: relative;
    width: 100px;
    height: 100px;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview-item span {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
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
    }

    .kelengkapan-list {
        grid-template-columns: 1fr;
    }

    .add-kelengkapan-form {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Edit Data Armada</h2>
        <button class="btn-back" onclick="window.location.href='{{ route('admin.armada') }}'">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Armada
        </button>
    </div>

    <!-- FORM -->
    <div class="form-card">
        <h3>Edit Informasi Armada</h3>

        <form method="POST" action="{{ route('admin.armada.update', $shuttle->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="layanan_id">Layanan <span style="color: red">*</span></label>
                    <select id="layanan_id" name="layanan_id" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" {{ $shuttle->layanan_id == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kode">Kode Armada <span style="color: red">*</span></label>
                    <input type="text" id="kode" name="kode" value="{{ old('kode', $shuttle->kode) }}" placeholder="Contoh: ARM 01" required>
                    @error('kode')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nama_shuttle">Nama Shuttle <span style="color: red">*</span></label>
                    <input type="text" id="nama_shuttle" name="nama_shuttle" value="{{ old('nama_shuttle', $shuttle->nama_shuttle) }}" placeholder="Contoh: Hiace Premium" required>
                    @error('nama_shuttle')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="merk">Merk <span style="color: red">*</span></label>
                    <input type="text" id="merk" name="merk" value="{{ old('merk', $shuttle->merk) }}" placeholder="Contoh: Toyota" required>
                    @error('merk')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="model">Model <span style="color: red">*</span></label>
                    <input type="text" id="model" name="model" value="{{ old('model', $shuttle->model) }}" placeholder="Contoh: Hiace" required>
                    @error('model')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipe_shuttle">Tipe/Kualitas Kendaraan <span style="color: red">*</span></label>
                    <select id="tipe_shuttle" name="tipe_shuttle" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="standar" {{ old('tipe_shuttle', $shuttle->tipe_shuttle) == 'standar' ? 'selected' : '' }}>Standar</option>
                        <option value="premium" {{ old('tipe_shuttle', $shuttle->tipe_shuttle) == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="luxury" {{ old('tipe_shuttle', $shuttle->tipe_shuttle) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        <option value="eksekutif" {{ old('tipe_shuttle', $shuttle->tipe_shuttle) == 'eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                        <option value="new" {{ old('tipe_shuttle', $shuttle->tipe_shuttle) == 'new' ? 'selected' : '' }}>New</option>
                    </select>
                    @error('tipe_shuttle')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun Pembuatan <span style="color: red">*</span></label>
                    <input type="number" id="tahun" name="tahun" min="2000" max="{{ date('Y') + 1 }}" value="{{ old('tahun', $shuttle->tahun) }}" placeholder="Contoh: 2020" required>
                    @error('tahun')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="warna">Warna <span style="color: red">*</span></label>
                    <input type="text" id="warna" name="warna" value="{{ old('warna', $shuttle->warna) }}" placeholder="Contoh: Putih" required>
                    @error('warna')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kapasitas_kursi">Kapasitas Kursi <span style="color: red">*</span></label>
                    <input type="number" id="kapasitas_kursi" name="kapasitas_kursi" min="1" max="50" value="{{ old('kapasitas_kursi', $shuttle->kapasitas_kursi) }}" placeholder="Contoh: 12" required>
                    @error('kapasitas_kursi')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nomor_polisi">Nomor Polisi <span style="color: red">*</span></label>
                    <input type="text" id="nomor_polisi" name="nomor_polisi" value="{{ old('nomor_polisi', $shuttle->nomor_polisi) }}" placeholder="Contoh: B 1234 AD" required>
                    @error('nomor_polisi')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jenis_kepemilikan">Jenis Kepemilikan <span style="color: red">*</span></label>
                    <select id="jenis_kepemilikan" name="jenis_kepemilikan" required>
                        <option value="">-- Pilih Jenis Kepemilikan --</option>
                        <option value="milik-perusahaan" {{ old('jenis_kepemilikan', $shuttle->jenis_kepemilikan) == 'milik-perusahaan' ? 'selected' : '' }}>Milik Perusahaan</option>
                        <option value="sewa" {{ old('jenis_kepemilikan', $shuttle->jenis_kepemilikan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="vendor" {{ old('jenis_kepemilikan', $shuttle->jenis_kepemilikan) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                    </select>
                    @error('jenis_kepemilikan')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nama_pemilik">Nama Pemilik/Vendor</label>
                    <input type="text" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik', $shuttle->nama_pemilik) }}" placeholder="Nama pemilik atau vendor">
                    @error('nama_pemilik')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk Operasi <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $shuttle->tanggal_masuk ? \Carbon\Carbon::parse($shuttle->tanggal_masuk)->format('Y-m-d') : '') }}" required>
                    @error('tanggal_masuk')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nilai_asset">Nilai Asset (Rp)</label>
                    <input type="text" id="nilai_asset" name="nilai_asset" value="{{ old('nilai_asset', $shuttle->nilai_asset) }}" placeholder="Contoh: 350.000.000">
                    @error('nilai_asset')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status Armada <span style="color: red">*</span></label>
                    <select id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status', $shuttle->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $shuttle->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="servis" {{ old('status', $shuttle->status) == 'servis' ? 'selected' : '' }}>Servis</option>
                        <option value="perbaikan" {{ old('status', $shuttle->status) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    @error('status')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fasilitas">Fasilitas</label>
                    <input type="text" id="fasilitas" name="fasilitas" value="{{ old('fasilitas', $shuttle->fasilitas) }}" placeholder="Contoh: AC Double,WiFi High Speed">
                    @error('fasilitas')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="no_stnk">Nomor STNK</label>
                    <input type="text" id="no_stnk" name="no_stnk" value="{{ old('no_stnk', $shuttle->no_stnk) }}" placeholder="Nomor STNK">
                    @error('no_stnk')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="masa_stnk">Masa Berlaku STNK</label>
                    <input type="date" id="masa_stnk" name="masa_stnk" value="{{ old('masa_stnk', $shuttle->masa_stnk ? \Carbon\Carbon::parse($shuttle->masa_stnk)->format('Y-m-d') : '') }}">
                    @error('masa_stnk')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="no_kir">Nomor KIR</label>
                    <input type="text" id="no_kir" name="no_kir" value="{{ old('no_kir', $shuttle->no_kir) }}" placeholder="Nomor KIR">
                    @error('no_kir')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="masa_kir">Masa Berlaku KIR</label>
                    <input type="date" id="masa_kir" name="masa_kir" value="{{ old('masa_kir', $shuttle->masa_kir ? \Carbon\Carbon::parse($shuttle->masa_kir)->format('Y-m-d') : '') }}">
                    @error('masa_kir')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="asuransi">Jenis Asuransi</label>
                    <select id="asuransi" name="asuransi">
                        <option value="">-- Pilih Asuransi --</option>
                        <option value="TLO" {{ old('asuransi', $shuttle->asuransi) == 'TLO' ? 'selected' : '' }}>TLO (Total Loss Only)</option>
                        <option value="Comprehensive" {{ old('asuransi', $shuttle->asuransi) == 'Comprehensive' ? 'selected' : '' }}>Comprehensive</option>
                        <option value="All Risk" {{ old('asuransi', $shuttle->asuransi) == 'All Risk' ? 'selected' : '' }}>All Risk</option>
                    </select>
                    @error('asuransi')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="masa_asuransi">Masa Asuransi</label>
                    <input type="date" id="masa_asuransi" name="masa_asuransi" value="{{ old('masa_asuransi', $shuttle->masa_asuransi ? \Carbon\Carbon::parse($shuttle->masa_asuransi)->format('Y-m-d') : '') }}">
                    @error('masa_asuransi')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="masa_kontrak">Masa Kontrak</label>
                    <input type="text" id="masa_kontrak" name="masa_kontrak" value="{{ old('masa_kontrak', $shuttle->masa_kontrak) }}" placeholder="Contoh: 15-01-2022 s/d 14-01-2025">
                    @error('masa_kontrak')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- KELENGKAPAN & PERLENGKAPAN DINAMIS -->
            <div class="form-group">
                <label>Kelengkapan & Perlengkapan</label>
                <div class="kelengkapan-container">
                    <div id="kelengkapanList" class="kelengkapan-list">
                        <!-- Kelengkapan akan ditambahkan dinamis di sini -->
                        @php
                            $kelengkapanData = [];
                            if ($shuttle->kelengkapan) {
                                if (is_string($shuttle->kelengkapan)) {
                                    $kelengkapanData = json_decode($shuttle->kelengkapan, true);
                                } else {
                                    $kelengkapanData = $shuttle->kelengkapan;
                                }
                            }
                        @endphp
                        @if(!empty($kelengkapanData))
                            @foreach($kelengkapanData as $index => $item)
                                <div class="kelengkapan-item">
                                    <input type="checkbox" id="kelengkapan-{{ $index }}" name="kelengkapan[{{ $index }}][checked]" {{ $item['checked'] ?? false ? 'checked' : '' }}>
                                    <input type="hidden" name="kelengkapan[{{ $index }}][name]" value="{{ $item['name'] }}">
                                    <label for="kelengkapan-{{ $index }}">{{ $item['name'] }}</label>
                                    <button type="button" class="remove-kelengkapan" onclick="removeKelengkapan('{{ $item['name'] }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="add-kelengkapan-form">
                        <input type="text" id="newKelengkapan" placeholder="Masukkan kelengkapan baru...">
                        <button type="button" class="btn-add-kelengkapan" onclick="addKelengkapan()">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>

            <!-- INPUT GAMBAR -->
            <div class="form-row">
                <div class="form-group">
                    <label for="gambar_depan">Gambar Depan</label>
                    @if($shuttle->gambar_depan)
                        <div class="image-preview">
                            <div class="image-preview-item">
                                <img src="{{ asset('storage/' . $shuttle->gambar_depan) }}" alt="Gambar Depan">
                                <span onclick="removeImage('gambar_depan')">×</span>
                                <input type="hidden" id="current_gambar_depan" name="current_gambar_depan" value="{{ $shuttle->gambar_depan }}">
                            </div>
                        </div>
                    @endif
                    <input type="file" id="gambar_depan" name="gambar_depan" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="gambar_samping">Gambar Samping</label>
                    @if($shuttle->gambar_samping)
                        <div class="image-preview">
                            <div class="image-preview-item">
                                <img src="{{ asset('storage/' . $shuttle->gambar_samping) }}" alt="Gambar Samping">
                                <span onclick="removeImage('gambar_samping')">×</span>
                                <input type="hidden" id="current_gambar_samping" name="current_gambar_samping" value="{{ $shuttle->gambar_samping }}">
                            </div>
                        </div>
                    @endif
                    <input type="file" id="gambar_samping" name="gambar_samping" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="gambar_belakang">Gambar Belakang</label>
                    @if($shuttle->gambar_belakang)
                        <div class="image-preview">
                            <div class="image-preview-item">
                                <img src="{{ asset('storage/' . $shuttle->gambar_belakang) }}" alt="Gambar Belakang">
                                <span onclick="removeImage('gambar_belakang')">×</span>
                                <input type="hidden" id="current_gambar_belakang" name="current_gambar_belakang" value="{{ $shuttle->gambar_belakang }}">
                            </div>
                        </div>
                    @endif
                    <input type="file" id="gambar_belakang" name="gambar_belakang" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="gambar_interior">Gambar Interior</label>
                    @if($shuttle->gambar_interior)
                        <div class="image-preview">
                            <div class="image-preview-item">
                                <img src="{{ asset('storage/' . $shuttle->gambar_interior) }}" alt="Gambar Interior">
                                <span onclick="removeImage('gambar_interior')">×</span>
                                <input type="hidden" id="current_gambar_interior" name="current_gambar_interior" value="{{ $shuttle->gambar_interior }}">
                            </div>
                        </div>
                    @endif
                    <input type="file" id="gambar_interior" name="gambar_interior" accept="image/*">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update Data
                </button>
                <button type="button" class="btn-reset" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.armada') }}'">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Daftar kelengkapan standar
const defaultKelengkapan = [
    'Dongkrak',
    'P3K',
    'APAR',
    'Ban Cadangan',
    'Spare Tire',
    'Jumper Cable',
    'Emergency Light',
    'First Aid Kit',
    'Fire Extinguisher',
    'Warning Triangle',
    'Reflective Vest',
    'Tire Pressure Gauge',
    'Jack Stand',
    'Tools Kit',
    'Charger'
];

// Fungsi untuk render daftar kelengkapan
function renderKelengkapanList(kelengkapanItems) {
    const container = document.getElementById('kelengkapanList');
    container.innerHTML = '';

    kelengkapanItems.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'kelengkapan-item';
        div.innerHTML = `
            <input type="checkbox" id="kelengkapan-${index}" name="kelengkapan[${index}][checked]" ${item.checked ? 'checked' : ''}>
            <input type="hidden" name="kelengkapan[${index}][name]" value="${item.name}">
            <label for="kelengkapan-${index}">${item.name}</label>
            <button type="button" class="remove-kelengkapan" onclick="removeKelengkapan('${item.name}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });
}

// Fungsi untuk menambah kelengkapan baru
function addKelengkapan() {
    const input = document.getElementById('newKelengkapan');
    const name = input.value.trim();

    if (!name) {
        alert('Masukkan nama kelengkapan terlebih dahulu!');
        return;
    }

    // Cek apakah sudah ada
    const container = document.getElementById('kelengkapanList');
    const existingItems = Array.from(container.querySelectorAll('.kelengkapan-item label'));
    const alreadyExists = existingItems.some(label => label.textContent === name);

    if (alreadyExists) {
        alert('Kelengkapan ini sudah ada dalam daftar!');
        return;
    }

    // Tambah item baru
    const div = document.createElement('div');
    div.className = 'kelengkapan-item';
    const index = container.children.length;
    div.innerHTML = `
        <input type="checkbox" id="kelengkapan-new-${index}" name="kelengkapan[${index}][checked]" checked>
        <input type="hidden" name="kelengkapan[${index}][name]" value="${name}">
        <label for="kelengkapan-new-${index}">${name}</label>
        <button type="button" class="remove-kelengkapan" onclick="removeKelengkapan('${name}')">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);

    // Reset input
    input.value = '';
    input.focus();
}

// Fungsi untuk menghapus kelengkapan
function removeKelengkapan(name) {
    if (!confirm(`Apakah Anda yakin ingin menghapus "${name}" dari daftar kelengkapan?`)) {
        return;
    }

    const container = document.getElementById('kelengkapanList');
    const items = Array.from(container.querySelectorAll('.kelengkapan-item'));

    items.forEach(item => {
        const label = item.querySelector('label');
        if (label && label.textContent === name) {
            container.removeChild(item);
        }
    });
}

// Fungsi untuk menghapus gambar
function removeImage(imageType) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        const currentInput = document.getElementById('current_' + imageType);
        if (currentInput) {
            currentInput.value = '';
        }
        const preview = document.querySelector(`[onclick="removeImage('${imageType}')"]`).parentElement;
        preview.style.display = 'none';
    }
}

// Fungsi untuk reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan akan hilang.')) {
        document.querySelector('form').reset();

        // Render kelengkapan default
        const kelengkapanData = @json($shuttle->kelengkapan ?: []);
        if (kelengkapanData.length > 0) {
            // Pastikan kelengkapanData dalam format yang benar
            let formattedData = [];
            if (typeof kelengkapanData === 'string') {
                try {
                    formattedData = JSON.parse(kelengkapanData);
                } catch (e) {
                    console.error('Error parsing kelengkapan data:', e);
                }
            } else {
                formattedData = kelengkapanData;
            }
            renderKelengkapanList(formattedData);
        } else {
            renderKelengkapanList(defaultKelengkapan.map(name => ({ name, checked: false })));
        }
    }
}

// Inisialisasi kelengkapan saat halaman dimuat
window.onload = function() {
    const kelengkapanData = @json($shuttle->kelengkapan ?: []);
    if (!kelengkapanData || kelengkapanData.length === 0) {
        renderKelengkapanList(defaultKelengkapan.map(name => ({ name, checked: false })));
    }
};
</script>
@endsection
