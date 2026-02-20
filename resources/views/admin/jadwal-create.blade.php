@extends('layouts.app-admin')

@section('title', 'Tambah Jadwal Baru')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ================= MINIMAL OPTIMIZED STYLES ================= */
    :root {
        --primary-color: #667eea;
        --primary-hover: #5a67d8;
        --success-color: #43e97b;
        --danger-color: #ff6a00;
        --warning-color: #fa709a;
        --info-color: #4facfe;
        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --text-muted: #718096;
        --border-color: #e2e8f0;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        margin: 0;
        color: var(--text-primary);
    }

    .page-wrapper {
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
        border-left: 6px solid var(--success-color);
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 14px;
        margin: 5px 0 0 0;
        line-height: 1.5;
    }

    .page-title .icon-wrapper {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .form-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: var(--card-shadow);
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .form-header i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .form-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .form-label span {
        color: var(--danger-color);
        margin-left: 2px;
    }

    .form-control {
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
        transition: var(--transition);
    }

    .select-wrapper select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .time-group {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 15px;
        align-items: flex-start;
    }

    .time-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 40px;
        color: var(--text-muted);
        font-weight: bold;
    }

    .input-group {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        overflow: hidden;
        background: white;
    }

    .input-group-prepend {
        padding: 10px 12px;
        background: #f7fafc;
        border-right: 1px solid var(--border-color);
        font-weight: 600;
        color: var(--text-secondary);
    }

    .input-group .form-control {
        border: none;
        margin: 0;
        padding: 10px 12px;
        flex: 1;
    }

    .info-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
        border-left: 4px solid var(--primary-color);
    }

    .info-card h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 10px 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card p {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.6;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .btn {
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a67d8 100%);
        color: white;
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-outline {
        background: white;
        border: 2px solid var(--border-color);
        color: var(--text-primary);
    }

    .btn-outline:hover {
        background: #f7fafc;
        border-color: var(--primary-color);
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, rgba(79, 172, 254, 0.2) 0%, rgba(0, 242, 254, 0.2) 100%);
        color: var(--info-color);
    }

    small {
        display: block;
        margin-top: 6px;
        color: var(--text-muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .text-muted {
        color: var(--text-muted);
    }

    .is-invalid {
        border-color: var(--danger-color) !important;
        background: linear-gradient(135deg, rgba(255, 106, 0, 0.05) 0%, rgba(238, 9, 121, 0.05) 100%) !important;
    }

    .is-valid {
        border-color: var(--success-color) !important;
    }

    @media (max-width: 768px) {
        .page-wrapper {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .time-group {
            grid-template-columns: 1fr;
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
                <p class="text-muted">
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

                <!-- Assign Driver (Optional) -->
                <div class="form-group">
                    <label class="form-label">Tugaskan ke Driver <span class="badge bg-info">Opsional</span></label>
                    <div class="select-wrapper">
                        <select name="driver_id" id="driver_select" class="form-control">
                            <option value="">-- Tidak Ditugaskan (Jadwal Global) --</option>
                        </select>
                    </div>
                    <small>
                        <i class="fas fa-info-circle"></i> <strong>Ditugaskan:</strong> Jadwal langsung aktif tanpa konfirmasi.
                        <strong>Tidak ditugaskan:</strong> Jadwal global untuk driver MANUAL_CONFIRM.
                    </small>
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
                            <small>Waktu Keberangkatan (bisa melewati tengah malam)</small>
                        </div>
                        <div class="time-separator">→</div>
                        <div>
                            <input type="time" name="waktu_kedatangan" class="form-control"
                                   value="{{ old('waktu_kedatangan', '09:00') }}" required>
                            <small>Waktu Kedatangan (contoh: 21:00 → 03:30)</small>
                        </div>
                    </div>
                </div>

                <!-- Harga dari Rute (Readonly) -->
                <div class="form-group">
                    <label class="form-label">Harga <span>*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">Rp</div>
                        <input type="text" id="harga_display" class="form-control"
                               readonly placeholder="Harga akan otomatis terisi sesuai rute">
                    </div>
                    <small><i class="fas fa-info-circle"></i> Harga diambil dari harga dasar rute yang dipilih</small>
                </div>

                <!-- Kapasitas Kursi -->
                <div class="form-group">
                    <label class="form-label">Total Kursi</label>
                    <input type="text" id="total_kursi_display" class="form-control" readonly
                           placeholder="Otomatis sesuai armada">
                    <small>Kapasitas kursi akan menyesuaikan armada yang dipilih</small>
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

    // ★ Update total kursi display
    const shuttleSelect = document.querySelector('select[name="shuttle_id"]');
    const kursiDisplay = document.getElementById('total_kursi_display');

    function updateKursiDisplay() {
        const selectedOption = shuttleSelect.options[shuttleSelect.selectedIndex];
        const kapasitas = selectedOption.getAttribute('data-kapasitas');
        if (kapasitas) {
            kursiDisplay.value = kapasitas + ' kursi';
        } else {
            kursiDisplay.value = '';
        }
    }

    shuttleSelect.addEventListener('change', updateKursiDisplay);
    updateKursiDisplay();

    // ★ Format dan update harga
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
    updateHargaDisplay();

    // ★ Auto-compute arrival time berdasarkan rute duration
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

        if (minutes === 0 && !isNaN(parseInt(duration, 10))) {
            minutes = parseInt(duration, 10);
        }

        return minutes;
    }

    function computeArrivalTime() {
        const dep = waktuBerangkatInput.value;
        if (!dep) return;
        const selected = ruteSelect.options[ruteSelect.selectedIndex];
        const dur = selected ? selected.getAttribute('data-durasi') : null;
        const durMinutes = parseDurationToMinutes(dur);

        const parts = dep.split(':');
        if (parts.length < 2) return;
        let h = parseInt(parts[0], 10);
        let m = parseInt(parts[1], 10);
        let total = h * 60 + m + durMinutes;

        total = total % (24 * 60);
        const ah = Math.floor(total / 60).toString().padStart(2, '0');
        const am = (total % 60).toString().padStart(2, '0');
        waktuKedatanganInput.value = `${ah}:${am}`;
    }

    waktuBerangkatInput.addEventListener('change', computeArrivalTime);
    ruteSelect.addEventListener('change', computeArrivalTime);
    computeArrivalTime();

    // ★ Load drivers by rute via AJAX
    const driverSelect = document.getElementById('driver_select');

    async function loadDriversByRute() {
        const ruteId = ruteSelect.value;

        if (!ruteId) {
            driverSelect.innerHTML = '<option value="">-- Tidak Ditugaskan (Jadwal Global) --</option>';
            return;
        }

        try {
            console.log('🔄 Loading drivers for rute_id:', ruteId);
            const response = await fetch('{{ route("admin.jadwal.driversByRute") }}?rute_id=' + ruteId);
            const data = await response.json();

            console.log('📋 Response data:', data);

            if (data.success) {
                let optionsHtml = '<option value="">-- Tidak Ditugaskan (Jadwal Global) --</option>';

                if (data.drivers && data.drivers.length > 0) {
                    if (data.branch_name) {
                        optionsHtml += `<option value="" disabled style="color: #666; font-weight: bold;">═══ Branch ${data.branch_name} (${data.drivers.length} driver) ═══</option>`;
                    }

                    data.drivers.forEach(driver => {
                        optionsHtml += `<option value="${driver.id}">${driver.name} (${driver.email})</option>`;
                    });

                    console.log('✅ Loaded', data.drivers.length, 'drivers');
                } else {
                    optionsHtml = '<option value="">-- Tidak ada driver di cabang asal (pesan: ' + (data.message || 'tidak diketahui') + ') --</option>';
                    console.warn('⚠️ No drivers found:', data.message);
                }

                driverSelect.innerHTML = optionsHtml;
            } else {
                driverSelect.innerHTML = '<option value="">-- Error: ' + (data.message || 'unknown error') + ' --</option>';
                console.error('❌ Error loading drivers:', data.message);
            }
        } catch (error) {
            console.error('❌ Exception loading drivers:', error);
            driverSelect.innerHTML = '<option value="">-- Error loading drivers --</option>';
        }
    }

    ruteSelect.addEventListener('change', loadDriversByRute);
    loadDriversByRute();

    // ★ Form validation
    const form = document.getElementById('jadwalForm');
    form.addEventListener('submit', function(e) {
        const ruteId = ruteSelect.value;

        if (!ruteId) {
            e.preventDefault();
            alert('Silakan pilih rute terlebih dahulu!');
            return false;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;

        return true;
    });
</script>
@endpush
