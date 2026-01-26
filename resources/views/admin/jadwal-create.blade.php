@extends('layouts.app-admin')

@section('title', 'Tambah Jadwal - Master Data')
@section('page-title', 'Tambah Jadwal')

@push('styles')
    <style>
        :root {
            --bg-primary: #f8f7f3;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #0b2a4a;
            --text-secondary: #333333;
            --text-muted: #777777;
            --border-color: #dddddd;
            --shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            --shadow-light: 0 2px 8px rgba(0, 0, 0, 0.05);
            --primary-color: #ff6a00;
            --secondary-color: #1e88e5;
            --success-color: #12b600;
            --warning-color: #f9b000;
            --danger-color: #e74c3c;
            --info-color: #6c757d;
        }

        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        .page-container {
            padding: 20px;
            min-height: 100vh;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-back {
            background: var(--info-color);
            color: #fff;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .btn-save {
            background: var(--primary-color);
            color: #fff;
        }

        .btn-save:hover {
            background: #e55c00;
        }

        .btn-reset {
            background: var(--secondary-color);
            color: #fff;
        }

        .btn-reset:hover {
            background: #0d6bb7;
        }

        .btn-cancel {
            background: var(--info-color);
            color: #fff;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .form-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .form-card h3 {
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 12px;
            font-size: 20px;
            color: var(--text-primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            transition: border-color 0.3s;
            background: var(--bg-card);
            color: var(--text-secondary);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.1);
        }

        textarea {
            resize: none;
            min-height: 80px;
        }

        .time-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 10px;
            align-items: center;
        }

        .time-separator {
            text-align: center;
            font-weight: bold;
            color: var(--text-muted);
        }

        .time-row small {
            display: block;
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .price-input {
            position: relative;
        }

        .price-input span {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .price-input input {
            padding-left: 40px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .text-muted {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 5px;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 12px;
            margin-top: 5px;
        }

        .loading {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .time-row {
                grid-template-columns: 1fr;
            }
            
            .time-separator {
                display: none;
            }
            
            .btn-save,
            .btn-reset,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="page-container">
    <a href="{{ route('admin.jadwal') }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
    </a>

    <div class="form-card">
        <h3>Tambah Jadwal Perjalanan</h3>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" id="jadwalForm">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="layanan_id">Layanan <span style="color: red">*</span></label>
                    <select id="layanan_id" name="layanan_id" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" {{ old('layanan_id') == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shuttle_id">Armada <span style="color: red">*</span></label>
                    <select id="shuttle_id" name="shuttle_id" required disabled>
                        <option value="">-- Pilih Layanan terlebih dahulu --</option>
                        @if(old('layanan_id') && old('shuttle_id'))
                            @php
                                $selectedShuttle = $shuttles->where('id', old('shuttle_id'))->first();
                            @endphp
                            @if($selectedShuttle)
                                <option value="{{ $selectedShuttle->id }}" selected data-max-seats="{{ $selectedShuttle->total_kursi }}">
                                    {{ $selectedShuttle->nama_shuttle }} ({{ $selectedShuttle->nomor_polisi }}) - {{ $selectedShuttle->total_kursi }} kursi
                                </option>
                            @endif
                        @endif
                    </select>
                    <div id="shuttle-loading" style="display: none; font-size: 12px; color: var(--text-muted); margin-top: 5px;">
                        <i class="fas fa-spinner fa-spin"></i> Memuat armada...
                    </div>
                    <small class="text-muted">Pilih layanan terlebih dahulu untuk melihat armada</small>
                    @error('shuttle_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_keberangkatan">Tanggal Keberangkatan <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_keberangkatan" name="tanggal_keberangkatan" 
                           value="{{ old('tanggal_keberangkatan') }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_keberangkatan')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="harga_total">Harga Tiket <span style="color: red">*</span></label>
                    <div class="price-input">
                        <span>Rp</span>
                        <input type="number" id="harga_total" name="harga_total" 
                               value="{{ old('harga_total') }}" 
                               placeholder="Contoh: 150000" min="1000" step="1000" required>
                    </div>
                    @error('harga_total')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Waktu Perjalanan <span style="color: red">*</span></label>
                <div class="time-row">
                    <div>
                        <input type="time" id="waktu_keberangkatan" name="waktu_keberangkatan" 
                               value="{{ old('waktu_keberangkatan', '06:00') }}" required>
                        <small>Waktu Keberangkatan</small>
                        @error('waktu_keberangkatan')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="time-separator">-</div>
                    <div>
                        <input type="time" id="waktu_kedatangan" name="waktu_kedatangan" 
                               value="{{ old('waktu_kedatangan', '09:00') }}" required>
                        <small>Waktu Kedatangan</small>
                        @error('waktu_kedatangan')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kursi_tersedia">Kursi Tersedia <span style="color: red">*</span></label>
                    <input type="number" id="kursi_tersedia" name="kursi_tersedia" 
                           value="{{ old('kursi_tersedia') }}" 
                           placeholder="Jumlah kursi tersedia" min="1" required>
                    <small class="text-muted" id="max-seats-info">Maksimum: - kursi</small>
                    @error('kursi_tersedia')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status <span style="color: red">*</span></label>
                    <select id="status" name="status" required>
                        <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="penuh" {{ old('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                        <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" 
                          placeholder="Tambahkan keterangan jadwal">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-save" type="submit">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
                <button class="btn btn-reset" type="button" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk memuat shuttle berdasarkan layanan
    async function loadShuttlesByLayanan() {
        const layananId = document.getElementById('layanan_id').value;
        const shuttleSelect = document.getElementById('shuttle_id');
        const shuttleLoading = document.getElementById('shuttle-loading');
        
        if (!layananId) {
            shuttleSelect.innerHTML = '<option value="">-- Pilih Layanan terlebih dahulu --</option>';
            shuttleSelect.disabled = true;
            shuttleLoading.style.display = 'none';
            document.getElementById('max-seats-info').textContent = 'Maksimum: - kursi';
            return;
        }
        
        try {
            shuttleLoading.style.display = 'block';
            shuttleSelect.disabled = true;
            shuttleSelect.innerHTML = '<option value="">Memuat armada...</option>';
            
            // Gunakan route yang benar
            const url = '{{ route("admin.jadwal.shuttles-by-layanan") }}';
            
            console.log('Loading shuttles for layanan_id:', layananId);
            console.log('URL:', url);
            
            const response = await fetch(`${url}?layanan_id=${layananId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            shuttleLoading.style.display = 'none';
            
            console.log('Response data:', data);
            
            if (data.success) {
                if (data.shuttles.length > 0) {
                    shuttleSelect.innerHTML = '<option value="">-- Pilih Armada --</option>';
                    
                    data.shuttles.forEach(shuttle => {
                        const option = document.createElement('option');
                        option.value = shuttle.id;
                        option.textContent = `${shuttle.nama_shuttle} (${shuttle.nomor_polisi}) - ${shuttle.total_kursi} kursi`;
                        option.setAttribute('data-max-seats', shuttle.total_kursi);
                        shuttleSelect.appendChild(option);
                    });
                    
                    shuttleSelect.disabled = false;
                    
                    // Jika ada old shuttle_id, set selected
                    const oldShuttleId = {{ old('shuttle_id', 0) }};
                    if (oldShuttleId > 0) {
                        shuttleSelect.value = oldShuttleId;
                        updateMaxSeatsInfo();
                    }
                } else {
                    shuttleSelect.innerHTML = '<option value="">-- Tidak ada armada tersedia --</option>';
                    shuttleSelect.disabled = true;
                }
            } else {
                shuttleSelect.innerHTML = '<option value="">-- Error memuat data --</option>';
                shuttleSelect.disabled = true;
                alert(data.message || 'Terjadi kesalahan saat memuat armada');
            }
            
        } catch (error) {
            console.error('Error loading shuttles:', error);
            shuttleLoading.style.display = 'none';
            shuttleSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
            shuttleSelect.disabled = true;
            alert('Terjadi kesalahan jaringan. Silakan refresh halaman dan coba lagi.');
        }
    }
    
    function updateMaxSeatsInfo() {
        const shuttleSelect = document.getElementById('shuttle_id');
        const selectedOption = shuttleSelect.options[shuttleSelect.selectedIndex];
        const kursiInput = document.getElementById('kursi_tersedia');
        
        if (selectedOption && selectedOption.value) {
            const maxSeats = selectedOption.getAttribute('data-max-seats') || 0;
            document.getElementById('max-seats-info').textContent = `Maksimum: ${maxSeats} kursi`;
            kursiInput.max = maxSeats;
            
            // Set placeholder
            kursiInput.placeholder = `Jumlah kursi (1-${maxSeats})`;
            
            // Jika kursi belum diisi dan ada old value, biarkan old value
            // Jika tidak ada old value dan belum diisi, set ke max seats
            if (!kursiInput.value && maxSeats > 0 && !{{ old('kursi_tersedia') ? 'true' : 'false' }}) {
                kursiInput.value = maxSeats;
            }
        } else {
            document.getElementById('max-seats-info').textContent = 'Maksimum: - kursi';
            kursiInput.max = '';
            kursiInput.placeholder = 'Jumlah kursi tersedia';
        }
    }
    
    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
            document.getElementById('jadwalForm').reset();
            document.getElementById('layanan_id').value = '';
            
            // Reset shuttle select
            const shuttleSelect = document.getElementById('shuttle_id');
            shuttleSelect.innerHTML = '<option value="">-- Pilih Layanan terlebih dahulu --</option>';
            shuttleSelect.disabled = true;
            
            // Hide loading
            document.getElementById('shuttle-loading').style.display = 'none';
            
            // Reset max seats info
            document.getElementById('max-seats-info').textContent = 'Maksimum: - kursi';
            
            // Set tanggal default (besok)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('tanggal_keberangkatan').value = tomorrow.toISOString().split('T')[0];
            
            // Set waktu default
            document.getElementById('waktu_keberangkatan').value = '06:00';
            document.getElementById('waktu_kedatangan').value = '09:00';
            
            // Set status default
            document.getElementById('status').value = 'tersedia';
        }
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk perubahan layanan
        document.getElementById('layanan_id').addEventListener('change', loadShuttlesByLayanan);
        
        // Event listener untuk perubahan armada
        document.getElementById('shuttle_id').addEventListener('change', updateMaxSeatsInfo);
        
        // Jika ada old layanan_id, load shuttles
        const oldLayananId = {{ old('layanan_id', 0) }};
        if (oldLayananId > 0) {
            // Tunggu sebentar agar DOM siap, lalu load shuttles
            setTimeout(() => {
                loadShuttlesByLayanan();
            }, 300);
        } else {
            // Set tanggal default (besok) jika tidak ada error
            @if(!$errors->any())
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('tanggal_keberangkatan').value = tomorrow.toISOString().split('T')[0];
            @endif
        }
        
        // Validasi waktu
        document.getElementById('waktu_kedatangan').addEventListener('change', function() {
            const waktuBerangkat = document.getElementById('waktu_keberangkatan').value;
            const waktuTiba = this.value;
            
            if (waktuBerangkat && waktuTiba && waktuBerangkat >= waktuTiba) {
                alert('Waktu kedatangan harus setelah waktu keberangkatan!');
                this.value = '';
            }
        });
        
        // Validasi kursi tidak boleh melebihi kapasitas
        document.getElementById('kursi_tersedia').addEventListener('change', function() {
            const maxSeats = parseInt(this.max);
            const value = parseInt(this.value);
            
            if (maxSeats && value > maxSeats) {
                alert(`Kursi tersedia tidak boleh melebihi ${maxSeats} kursi`);
                this.value = maxSeats;
            }
        });
        
        // Validasi form sebelum submit
        document.getElementById('jadwalForm').addEventListener('submit', function(e) {
            const shuttleId = document.getElementById('shuttle_id').value;
            const kursiTersedia = document.getElementById('kursi_tersedia').value;
            const maxSeats = parseInt(document.getElementById('kursi_tersedia').max);
            
            if (!shuttleId) {
                e.preventDefault();
                alert('Silakan pilih armada terlebih dahulu');
                return false;
            }
            
            if (maxSeats && parseInt(kursiTersedia) > maxSeats) {
                e.preventDefault();
                alert(`Kursi tersedia tidak boleh melebihi ${maxSeats} kursi`);
                return false;
            }
            
            return true;
        });
    });
</script>
@endpush