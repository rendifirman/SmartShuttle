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

            <!-- Flow Mode Info -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; color: white; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-info-circle" style="font-size: 18px;"></i>
                <div>
                    @if(isset($mode) && $mode === 'direct_assign')
                        <strong>Mode:</strong> Direct Assign - Anda harus memilih driver sekarang, jadwal akan langsung aktif
                    @else
                        <strong>Mode:</strong> Driver Confirmation - Driver akan melihat jadwal ini dan dapat mengambilnya
                    @endif
                </div>
            </div>
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
                        <select name="rute_id" id="rute_select" class="form-control" required>
                            <option value="">Pilih Rute</option>
                            @foreach($rutes as $rute)
                                <option value="{{ $rute->id }}"
                                    data-harga="{{ $rute->harga_dasar }}"
                                    data-durasi="{{ $rute->durasi }}"
                                    {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
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

                <!-- Driver Selection (only shown/required in direct_assign mode) -->
                @if(isset($mode) && $mode === 'direct_assign')
                    <div class="form-group">
                        <label class="form-label">Driver <span>*</span></label>
                        <div class="select-wrapper">
                            <select name="driver_id" class="form-control" required>
                                <option value="">Pilih Driver</option>
                                @if(isset($drivers) && $drivers->count() > 0)
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Driver tidak tersedia</option>
                                @endif
                            </select>
                        </div>
                        <small style="display: block; margin-top: 8px; color: var(--text-muted); font-size: 12px;">
                            <i class="fas fa-warning"></i> Dalam mode Direct Assign, Anda harus memilih driver. Jadwal akan dibuat dengan status aktif.
                        </small>
                    </div>
                @else
                    <div style="background: rgba(102, 126, 234, 0.1); border-radius: 10px; padding: 15px 20px; border-left: 4px solid #667eea; margin-bottom: 20px;">
                        <i class="fas fa-info-circle" style="color: #667eea; margin-right: 8px;"></i>
                        <span style="color: var(--text-secondary);">Dalam mode Driver Confirmation, driver akan melihat jadwal ini sebagai "terbuka" dan dapat mengambilnya.</span>
                    </div>
                @endif

                <!-- Harga dari Rute (Readonly) -->
                <div class="form-group">
                    <label class="form-label">Harga <span>*</span></label>
                    <div class="price-container">
                        <div class="input-group">
                            <div class="input-group-prepend">Rp</div>
                            <input type="text" id="harga_display" class="form-control"
                                   readonly placeholder="Harga akan otomatis terisi sesuai rute">
                        </div>
                        <small style="display: block; margin-top: 8px; color: var(--text-muted); font-size: 12px;">
                            <i class="fas fa-info-circle"></i> Harga diambil otomatis dari harga dasar rute yang dipilih
                        </small>
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
                    4. Harga otomatis diambil dari harga dasar rute<br>
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
    const hargaDisplay = document.getElementById('harga_display');
    const ruteSelect = document.getElementById('rute_select');

    function formatPrice(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }

    function updateHargaDisplay() {
        const selectedOption = ruteSelect.options[ruteSelect.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.harga) {
            const harga = parseFloat(selectedOption.dataset.harga);
            hargaDisplay.value = formatPrice(harga);
        } else {
            hargaDisplay.value = '';
        }
    }

    ruteSelect.addEventListener('change', updateHargaDisplay);
    updateHargaDisplay(); // Initial call

    // Form validation
    const form = document.getElementById('jadwalForm');
    form.addEventListener('submit', function(e) {
        const ruteId = ruteSelect.value;

        if (!ruteId) {
            e.preventDefault();
            alert('Silakan pilih rute terlebih dahulu!');
            return false;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;

        return true;
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

    // Auto-fill waktu_kedatangan based on selected rute duration and waktu_keberangkatan
    const waktuBerangkatInput = document.querySelector('input[name="waktu_keberangkatan"]');
    const waktuKedatanganInput = document.querySelector('input[name="waktu_kedatangan"]');

    function parseDurationToMinutes(duration) {
        if (!duration) return 0;
        duration = duration.toString().trim();

        // HH:MM format
        const hhmm = duration.match(/^(\d{1,2}):(\d{2})$/);
        if (hhmm) {
            return parseInt(hhmm[1], 10) * 60 + parseInt(hhmm[2], 10);
        }

        // X jam Y menit
        const jamMatch = duration.match(/(\d+)\s*jam/i);
        const menitMatch = duration.match(/(\d+)\s*menit/i);
        let minutes = 0;
        if (jamMatch) minutes += parseInt(jamMatch[1], 10) * 60;
        if (menitMatch) minutes += parseInt(menitMatch[1], 10);

        // If still zero and numeric, assume minutes
        if (minutes === 0 && !isNaN(parseInt(duration, 10))) {
            minutes = parseInt(duration, 10);
        }

        return minutes;
    }

    function computeArrivalTime() {
        const dep = waktuBerangkatInput.value; // HH:MM
        if (!dep) return;
        const selected = ruteSelect.options[ruteSelect.selectedIndex];
        const dur = selected ? selected.getAttribute('data-durasi') : null;
        const durMinutes = parseDurationToMinutes(dur);

        const parts = dep.split(':');
        if (parts.length < 2) return;
        let h = parseInt(parts[0], 10);
        let m = parseInt(parts[1], 10);
        let total = h * 60 + m + durMinutes;

        // wrap around 24h
        total = total % (24 * 60);
        const ah = Math.floor(total / 60).toString().padStart(2, '0');
        const am = (total % 60).toString().padStart(2, '0');
        waktuKedatanganInput.value = `${ah}:${am}`;
    }

    // Recompute when departure changes or route changes
    waktuBerangkatInput.addEventListener('change', computeArrivalTime);
    ruteSelect.addEventListener('change', computeArrivalTime);
    // initial compute
    computeArrivalTime();
</script>
@endpush
