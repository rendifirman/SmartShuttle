@extends('layouts.app-admin')

@section('title', 'Tambah Jadwal Baru')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ================= THEME VARIABLES ================= */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --danger-gradient: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --dark-gradient: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        --light-gradient: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        
        --primary-color: #667eea;
        --primary-hover: #5a67d8;
        --secondary-color: #764ba2;
        --success-color: #43e97b;
        --warning-color: #fa709a;
        --danger-color: #ff6a00;
        --info-color: #4facfe;
        --dark-color: #2d3748;
        --light-color: #f7fafc;
        
        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --text-muted: #718096;
        --border-color: #e2e8f0;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ================= GLOBAL STYLES ================= */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
        margin: 0;
        color: var(--text-primary);
    }

    /* ================= PAGE LAYOUT ================= */
    .page-wrapper {
        padding: 20px;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ================= HEADER ================= */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border-left: 6px solid var(--success-color);
        animation: slideInLeft 0.6s ease-out;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title h1 {
        font-size: 28px;
        font-weight: 700;
        background: var(--success-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .page-title .icon-wrapper {
        width: 50px;
        height: 50px;
        background: var(--success-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        box-shadow: 0 4px 15px rgba(67, 233, 123, 0.4);
    }

    /* ================= FORM CARD ================= */
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        animation: slideInUp 0.7s ease-out;
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
    }

    .form-header i {
        color: var(--success-color);
        font-size: 24px;
    }

    .form-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    /* ================= FORM STYLES ================= */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .form-label span {
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 14px;
        transition: var(--transition);
        background: var(--light-color);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: var(--primary-color);
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group-prepend {
        position: absolute;
        left: 0;
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        background: var(--light-color);
        border-right: 1px solid var(--border-color);
        border-radius: 10px 0 0 10px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .input-group-prepend + .form-control {
        padding-left: 60px;
    }

    /* ================= TIME INPUT GROUP ================= */
    .time-group {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 15px;
        align-items: center;
    }

    .time-separator {
        text-align: center;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 18px;
    }

    /* ================= SELECT STYLES ================= */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '▼';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        font-size: 12px;
        transition: var(--transition);
    }

    .select-wrapper select:focus + ::after {
        transform: translateY(-50%) rotate(180deg);
    }

    /* ================= BUTTONS ================= */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .btn:focus:not(:active)::after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% { transform: scale(0, 0); opacity: 0.5; }
        100% { transform: scale(20, 20); opacity: 0; }
    }

    .btn-primary {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 233, 123, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
    }

    .btn-outline {
        background: transparent;
        color: var(--text-secondary);
        border: 2px solid var(--border-color);
    }

    .btn-outline:hover {
        background: var(--light-color);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    /* ================= FORM ACTIONS ================= */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    /* ================= INFO CARDS ================= */
    .info-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        border-left: 4px solid var(--primary-color);
    }

    .info-card h4 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 10px 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card p {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* ================= PRICE INPUT STYLE ================= */
    .price-container {
        position: relative;
    }

    .price-display {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: var(--text-muted);
        background: var(--light-color);
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        min-width: 100px;
        text-align: right;
    }

    /* ================= RESPONSIVE DESIGN ================= */
    @media (max-width: 768px) {
        .page-wrapper {
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .time-group {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .time-separator {
            display: none;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .page-title h1 {
            font-size: 24px;
        }
        
        .page-title .icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .form-card {
            padding: 20px;
        }
        
        .form-header h3 {
            font-size: 18px;
        }
    }

    /* ================= VALIDATION STYLES ================= */
    .is-invalid {
        border-color: var(--danger-color) !important;
        background: linear-gradient(135deg, rgba(255, 106, 0, 0.05) 0%, rgba(238, 9, 121, 0.05) 100%) !important;
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 12px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .is-valid {
        border-color: var(--success-color) !important;
        background: linear-gradient(135deg, rgba(67, 233, 123, 0.05) 0%, rgba(56, 249, 215, 0.05) 100%) !important;
    }

    /* ================= ANIMATIONS ================= */
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* ================= SLIDER STYLES ================= */
    .slider-container {
        margin-top: 10px;
    }

    .slider {
        width: 100%;
        height: 8px;
        border-radius: 4px;
        background: var(--border-color);
        outline: none;
        -webkit-appearance: none;
        margin: 15px 0;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .slider-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <div class="icon-wrapper">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div>
                <h1>Tambah Jadwal Baru</h1>
                <p class="text-muted" style="margin: 5px 0 0 0; font-size: 14px;">
                    Buat jadwal perjalanan shuttle yang bisa diambil oleh driver
                </p>
            </div>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-calendar-check"></i>
            <h3>Informasi Jadwal</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.jadwal.store') }}" id="jadwalForm">
            @csrf
            
            <div class="form-grid">
                <!-- Armada -->
                <div class="form-group">
                    <label class="form-label">Armada <span>*</span></label>
                    <div class="select-wrapper">
                        <select name="shuttle_id" class="form-control" required>
                            <option value="">Pilih Armada</option>
                            @foreach($shuttles as $shuttle)
                                <option value="{{ $shuttle->id }}" 
                                        data-kapasitas="{{ $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0 }}"
                                        {{ old('shuttle_id') == $shuttle->id ? 'selected' : '' }}>
                                    {{ $shuttle->nama_shuttle }} ({{ $shuttle->plat_nomor }}) - 
                                    {{ $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0 }} kursi
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Rute -->
                <div class="form-group">
                    <label class="form-label">Rute <span>*</span></label>
                    <div class="select-wrapper">
                        <select name="rute_id" class="form-control" required>
                            <option value="">Pilih Rute</option>
                            @foreach($rutes as $rute)
                                <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                    {{ $rute->nama_rute }} ({{ $rute->kota_asal }} → {{ $rute->kota_tujuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Tanggal Keberangkatan -->
                <div class="form-group">
                    <label class="form-label">Tanggal Keberangkatan <span>*</span></label>
                    <input type="date" name="tanggal_keberangkatan" class="form-control" 
                           value="{{ old('tanggal_keberangkatan') }}" required>
                </div>
                
                <!-- Waktu -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Waktu Perjalanan <span>*</span></label>
                    <div class="time-group">
                        <div>
                            <input type="time" name="waktu_keberangkatan" class="form-control" 
                                   value="{{ old('waktu_keberangkatan', '06:00') }}" required>
                            <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 12px;">
                                Waktu Keberangkatan (bisa melewati tengah malam)
                            </small>
                        </div>
                        <div class="time-separator">
                            →
                        </div>
                        <div>
                            <input type="time" name="waktu_kedatangan" class="form-control" 
                                   value="{{ old('waktu_kedatangan', '09:00') }}" required>
                            <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 12px;">
                                Waktu Kedatangan (contoh: 21:00 → 03:30)
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Harga dengan slider -->
                <div class="form-group">
                    <label class="form-label">Harga Total <span>*</span></label>
                    <div class="price-container">
                        <div class="input-group">
                            <div class="input-group-prepend">Rp</div>
                            <input type="number" name="harga_total" id="harga_input" class="form-control" 
                                   value="{{ old('harga_total', 150000) }}" min="1000" step="1000" required>
                        </div>
                        <div class="price-display" id="price_display">Rp 150.000</div>
                    </div>
                    
                    <div class="slider-container">
                        <input type="range" class="slider" id="harga_slider" 
                               min="1000" max="1000000" step="1000" value="150000">
                        <div class="slider-info">
                            <span>Rp 1.000</span>
                            <span>Rp 500.000</span>
                            <span>Rp 1.000.000</span>
                        </div>
                    </div>
                </div>
                
                <!-- Kapasitas Kursi -->
                <div class="form-group">
                    <label class="form-label">Total Kursi</label>
                    <input type="text" id="total_kursi_display" class="form-control" readonly 
                           placeholder="Otomatis sesuai armada">
                    <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 12px;">
                        Kapasitas kursi akan menyesuaikan armada yang dipilih
                    </small>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="info-card">
                <h4><i class="fas fa-info-circle"></i> Informasi Penting</h4>
                <p>
                    1. Waktu bisa melewati tengah malam (contoh: 21:00 → 03:30)<br>
                    2. Jadwal yang dibuat akan langsung tersedia untuk diambil oleh driver<br>
                    3. Pastikan semua data sudah benar sebelum menyimpan<br>
                    4. Harga sudah termasuk semua biaya operasional<br>
                    5. Status jadwal akan otomatis disesuaikan dengan ketersediaan kursi
                </p>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tanggal_keberangkatan"]').min = today;
    
    // Update total kursi display based on selected shuttle
    const shuttleSelect = document.querySelector('select[name="shuttle_id"]');
    const kursiDisplay = document.getElementById('total_kursi_display');
    
    function updateKursiDisplay() {
        const selectedOption = shuttleSelect.options[shuttleSelect.selectedIndex];
        const kapasitas = selectedOption.getAttribute('data-kapasitas');
        if (kapasitas) {
            kursiDisplay.value = kapasitas + ' kursi';
        } else {
            kursiDisplay.value = 'Belum memilih armada';
        }
    }
    
    shuttleSelect.addEventListener('change', updateKursiDisplay);
    updateKursiDisplay(); // Initial call
    
    // Format harga display
    const hargaInput = document.getElementById('harga_input');
    const hargaSlider = document.getElementById('harga_slider');
    const priceDisplay = document.getElementById('price_display');
    
    function formatPrice(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }
    
    function updatePriceDisplay() {
        const value = hargaInput.value;
        if (value) {
            const formatted = formatPrice(value);
            priceDisplay.textContent = formatted;
        } else {
            priceDisplay.textContent = 'Rp 0';
        }
    }
    
    // Sync slider with input
    hargaInput.addEventListener('input', function() {
        hargaSlider.value = this.value;
        updatePriceDisplay();
    });
    
    hargaSlider.addEventListener('input', function() {
        hargaInput.value = this.value;
        updatePriceDisplay();
    });
    
    updatePriceDisplay(); // Initial call
    
    // Form validation
    const form = document.getElementById('jadwalForm');
    form.addEventListener('submit', function(e) {
        const harga = hargaInput.value;
        
        if (!harga || parseFloat(harga) < 1000) {
            e.preventDefault();
            alert('Harga minimal Rp 1.000!');
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        return true;
    });
    
    // Auto-calculate arrival time based on selected route
    const ruteSelect = document.querySelector('select[name="rute_id"]');
    ruteSelect.addEventListener('change', function() {
        const selectedRute = this.options[this.selectedIndex].text;
        let suggestedPrice = 150000; // Default price
        
        if (selectedRute.includes('Jakarta') && selectedRute.includes('Bandung')) {
            suggestedPrice = 150000;
            // Auto set waktu untuk Jakarta-Bandung (3 jam)
            document.querySelector('input[name="waktu_keberangkatan"]').value = '06:00';
            document.querySelector('input[name="waktu_kedatangan"]').value = '09:00';
        } else if (selectedRute.includes('Jakarta') && selectedRute.includes('Surabaya')) {
            suggestedPrice = 300000;
            // Auto set waktu untuk Jakarta-Surabaya (8 jam)
            document.querySelector('input[name="waktu_keberangkatan"]').value = '21:00';
            document.querySelector('input[name="waktu_kedatangan"]').value = '05:00';
        } else if (selectedRute.includes('Bandung') && selectedRute.includes('Surabaya')) {
            suggestedPrice = 250000;
            // Auto set waktu untuk Bandung-Surabaya (7 jam)
            document.querySelector('input[name="waktu_keberangkatan"]').value = '22:00';
            document.querySelector('input[name="waktu_kedatangan"]').value = '05:00';
        }
        
        // Only suggest if input is empty
        if (!hargaInput.value || hargaInput.value == '0') {
            hargaInput.value = suggestedPrice;
            hargaSlider.value = suggestedPrice;
            updatePriceDisplay();
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // Set default harga if empty
    if (!hargaInput.value) {
        hargaInput.value = 150000;
        hargaSlider.value = 150000;
        updatePriceDisplay();
    }
</script>
@endpush