@extends('layouts.app-admin')

@section('title', 'Edit Jadwal - Master Data')
@section('page-title', 'Edit Jadwal')

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
    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
    </a>

    <div class="form-card">
        <h3>Edit Jadwal Perjalanan</h3>

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" id="jadwalForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="layanan_id">Layanan <span style="color: red">*</span></label>
                    <select id="layanan_id" name="layanan_id" required onchange="loadShuttlesByLayanan()">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" 
                                {{ ($jadwal->shuttle && $jadwal->shuttle->layanan_id == $layanan->id_layanan) || old('layanan_id') == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shuttle_id">Armada <span style="color: red">*</span></label>
                    <select id="shuttle_id" name="shuttle_id" required>
                        <option value="">-- Memuat armada...</option>
                        @if($jadwal->shuttle)
                            <option value="{{ $jadwal->shuttle_id }}" selected data-max-seats="{{ $jadwal->shuttle->total_kursi }}">
                                {{ $jadwal->shuttle->nama_shuttle }} ({{ $jadwal->shuttle->nomor_polisi }}) - {{ $jadwal->shuttle->total_kursi }} kursi
                            </option>
                        @endif
                    </select>
                    <small class="text-muted">Pilih layanan terlebih dahulu untuk melihat armada</small>
                    @error('shuttle_id')
                        <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_keberangkatan">Tanggal Keberangkatan <span style="color: red">*</span></label>
                    <input type="date" id="tanggal_keberangkatan" name="tanggal_keberangkatan" 
                           value="{{ old('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan) }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_keberangkatan')
                        <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Waktu Perjalanan <span style="color: red">*</span></label>
                <div class="time-row">
                    <div>
                        <input type="time" id="waktu_keberangkatan" name="waktu_keberangkatan" 
                               value="{{ old('waktu_keberangkatan', $jadwal->waktu_keberangkatan) }}" required>
                        <small>Waktu Keberangkatan</small>
                        @error('waktu_keberangkatan')
                            <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="time-separator">-</div>
                    <div>
                        <input type="time" id="waktu_kedatangan" name="waktu_kedatangan" 
                               value="{{ old('waktu_kedatangan', $jadwal->waktu_kedatangan) }}" required>
                        <small>Waktu Kedatangan</small>
                        @error('waktu_kedatangan')
                            <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="harga_total">Harga Tiket <span style="color: red">*</span></label>
                    <div class="price-input">
                        <span>Rp</span>
                        <input type="number" id="harga_total" name="harga_total" 
                               value="{{ old('harga_total', $jadwal->harga_total) }}" 
                               placeholder="Contoh: 150000" min="0" required>
                    </div>
                    @error('harga_total')
                        <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kursi_tersedia">Kursi Tersedia <span style="color: red">*</span></label>
                    <input type="number" id="kursi_tersedia" name="kursi_tersedia" 
                           value="{{ old('kursi_tersedia', $jadwal->kursi_tersedia) }}" 
                           placeholder="Jumlah kursi tersedia" min="1" required>
                    <small class="text-muted" id="max-seats-info">Maksimum: {{ $jadwal->shuttle ? $jadwal->shuttle->total_kursi : 0 }} kursi</small>
                    @error('kursi_tersedia')
                        <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" 
                          placeholder="Tambahkan keterangan jadwal">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="text-muted" style="color: var(--danger-color);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-save" type="submit">
                    <i class="fas fa-save"></i> Update Jadwal
                </button>
                <button class="btn btn-reset" type="button" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            
            // PERBAIKAN: Gunakan URL dengan parameter yang benar
            const url = '{{ route("admin.jadwal.shuttles-by-layanan") }}';
            const fullUrl = `${url}?layanan_id=${encodeURIComponent(layananId)}`;
            
            console.log('Fetching shuttles from:', fullUrl);
            
            // Tambahkan timeout untuk mencegah hanging
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 detik timeout
            
            const response = await fetch(fullUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            shuttleLoading.style.display = 'none';
            
            console.log('API Response:', data);
            
            if (data.success) {
                if (data.shuttles && data.shuttles.length > 0) {
                    shuttleSelect.innerHTML = '<option value="">-- Pilih Armada --</option>';
                    
                    data.shuttles.forEach(shuttle => {
                        const option = document.createElement('option');
                        option.value = shuttle.id;
                        option.textContent = shuttle.display_text || 
                            `${shuttle.nama_shuttle} (${shuttle.nomor_polisi}) - ${shuttle.total_kursi} kursi`;
                        option.setAttribute('data-max-seats', shuttle.total_kursi);
                        option.setAttribute('data-nomor-polisi', shuttle.nomor_polisi);
                        shuttleSelect.appendChild(option);
                    });
                    
                    shuttleSelect.disabled = false;
                    
                    // Jika ada old shuttle_id, set selected
                    const oldShuttleId = {{ old('shuttle_id', 'null') }};
                    if (oldShuttleId) {
                        shuttleSelect.value = oldShuttleId;
                        updateMaxSeatsInfo();
                    }
                    
                    // Trigger change event jika perlu
                    if (shuttleSelect.value) {
                        shuttleSelect.dispatchEvent(new Event('change'));
                    }
                } else {
                    shuttleSelect.innerHTML = '<option value="">-- Tidak ada armada tersedia --</option>';
                    shuttleSelect.disabled = true;
                    showToast('warning', data.message || 'Tidak ada armada tersedia untuk layanan ini');
                }
            } else {
                shuttleSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                shuttleSelect.disabled = true;
                showToast('error', data.message || 'Terjadi kesalahan saat memuat armada');
            }
            
        } catch (error) {
            console.error('Error loading shuttles:', error);
            shuttleLoading.style.display = 'none';
            shuttleSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
            shuttleSelect.disabled = true;
            
            if (error.name === 'AbortError') {
                showToast('error', 'Permintaan timeout. Silakan coba lagi.');
            } else {
                showToast('error', 'Terjadi kesalahan jaringan. Silakan refresh halaman dan coba lagi.');
            }
        }
    }

    // Fungsi untuk menampilkan toast notification
    function showToast(type, message) {
        // Buat elemen toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Tambahkan ke body
        document.body.appendChild(toast);
        
        // Hilangkan otomatis setelah 5 detik
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    // Tambahkan style untuk toast
    const toastStyle = document.createElement('style');
    toastStyle.textContent = `
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            max-width: 400px;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .toast-success {
            background: var(--success-color);
            border-left: 4px solid #0d8b00;
        }
        
        .toast-error {
            background: var(--danger-color);
            border-left: 4px solid #c0392b;
        }
        
        .toast-warning {
            background: var(--warning-color);
            border-left: 4px solid #d68900;
            color: #333;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .toast-close:hover {
            opacity: 1;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(toastStyle);
    
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
        } else {
            document.getElementById('max-seats-info').textContent = 'Maksimum: - kursi';
            kursiInput.max = '';
            kursiInput.placeholder = 'Jumlah kursi tersedia';
        }
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Load shuttles berdasarkan layanan saat ini
        const currentLayananId = {{ $jadwal->shuttle ? $jadwal->shuttle->layanan_id : 0 }};
        if (currentLayananId > 0) {
            // Tunggu sebentar agar DOM siap
            setTimeout(() => {
                loadShuttlesByLayanan();
            }, 100);
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
    });
</script>
@endpush