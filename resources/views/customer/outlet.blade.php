@extends('layouts.app')

@section('title', 'Lokasi Outlet SmartShuttle')

@push('styles')
<style>
    /* Outlet Page Styles dengan Background Foto */
    .outlet-page {
        background:
            linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0.95) 0%,
                rgba(255, 255, 255, 0.85) 50%,
                rgba(255, 255, 255, 0.95) 100%
            ),
            url('{{ asset("images/indonesia.jpeg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        padding-top: 80px;
        min-height: 100vh;
        position: relative;
    }

    /* Fallback jika foto tidak ada */
    .outlet-page.no-bg {
        background:
            linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0.95) 0%,
                rgba(255, 255, 255, 0.85) 50%,
                rgba(255, 255, 255, 0.95) 100%
            );
        background-attachment: fixed;
    }

    .outlet-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .outlet-title {
        font-size: 28px;
        font-weight: 700;
        color: #0C2D48;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
    }

    /* Filter Section */
    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        backdrop-filter: blur(5px);
    }
    .filter-section h4 {
        color: #0C2D48;
        margin-bottom: 15px;
        font-size: 18px;
    }
    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        background: white;
    }
    .form-control:focus {
        border-color: #FF581E;
        box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.2);
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    .col-md-4 {
        padding: 0 10px;
        flex: 0 0 33.333%;
        max-width: 33.333%;
    }
    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
    }

    /* Outlet Grid */
    .outlet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
        margin: 0 auto;
    }

    /* Outlet Card */
    .outlet-card {
        display: block;
    }

    .outlet-card-inner {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        border: 1px solid rgba(221, 221, 221, 0.5);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(5px);
    }

    .outlet-card-inner:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(12, 45, 72, 0.2);
        border-color: rgba(255, 88, 30, 0.3);
    }

    /* Card Header (Nama Outlet di ATAS) */
    .card-header {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        padding: 20px;
        text-align: center;
        font-weight: 600;
        font-size: 18px;
        order: 1;
        letter-spacing: 0.5px;
    }

    /* Card Image (Foto di BAWAH header) */
    .card-image {
        width: 100%;
        height: 200px;
        overflow: hidden;
        order: 2;
    }

    .outlet-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .outlet-card:hover .outlet-img {
        transform: scale(1.05);
    }

    /* Card Body (Alamat dan tombol) */
    .card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        order: 3;
    }

    .card-text {
        margin-bottom: 20px;
        color: #444;
        line-height: 1.6;
        flex: 1;
        font-size: 15px;
    }

    .card-text strong {
        color: #0C2D48;
    }

    .btn-detail {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
        margin-top: auto;
        align-self: flex-start;
        box-shadow: 0 4px 15px rgba(12, 45, 72, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #FF581E 0%, #FF7A4A 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 88, 30, 0.3);
    }

    .btn-detail:active {
        transform: translateY(0);
    }

    /* Popup Overlay Styles */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }

    .popup-container {
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    .popup-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        position: relative;
    }

    .popup-header {
        background: linear-gradient(135deg, #0C2D48 0%, #1A4A6E 100%);
        color: white;
        padding: 20px;
        text-align: center;
        font-weight: 600;
        font-size: 20px;
        position: relative;
        letter-spacing: 0.5px;
    }

    .btn-close-popup {
        position: absolute;
        top: 15px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close-popup:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .popup-image-container {
        width: 100%;
        height: 250px;
        overflow: hidden;
    }

    .popup-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .popup-content {
        padding: 25px;
    }

    .popup-info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .popup-info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .popup-label {
        font-weight: 600;
        color: #0C2D48;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .popup-value {
        color: #555;
        line-height: 1.5;
        font-size: 15px;
    }

    .popup-facilities {
        margin-top: 20px;
    }

    .popup-facilities-label {
        font-weight: 600;
        color: #0C2D48;
        margin-bottom: 12px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .popup-facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 8px;
    }

    .popup-facility-item {
        background: #e3f2fd;
        color: #0C2D48;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .popup-facility-item:hover {
        transform: translateY(-2px);
        background: #bbdefb;
    }

    /* Animasi */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .outlet-container {
            padding: 20px 16px;
        }

        .outlet-title {
            font-size: 24px;
            margin-bottom: 24px;
        }

        .outlet-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .filter-section {
            margin-bottom: 30px;
        }

        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }

        .card-image {
            height: 180px;
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            padding: 16px;
            font-size: 16px;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
        }

        .popup-container {
            width: 95%;
            max-width: 400px;
        }

        .popup-content {
            padding: 20px;
        }

        .popup-image-container {
            height: 200px;
        }

        .popup-facilities-grid {
            grid-template-columns: 1fr 1fr;
        }

        .outlet-page {
            background-attachment: scroll;
        }
    }

    @media (max-width: 480px) {
        .outlet-container {
            padding: 16px 12px;
        }

        .outlet-title {
            font-size: 22px;
        }

        .card-image {
            height: 160px;
        }

        .popup-content {
            padding: 18px;
        }

        .popup-image-container {
            height: 180px;
        }

        .popup-facilities-grid {
            grid-template-columns: 1fr;
        }

        .popup-header {
            padding: 16px;
            font-size: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="outlet-page" id="outletPage">
    <div class="outlet-container">
        <h1 class="outlet-title">LOKASI OUTLET SMARTSHUTTLE</h1>

        <!-- Filter Section -->
        <div class="filter-section">
            <h4>Filter Outlet</h4>
            <form method="GET" action="{{ route('customer.outlet.filter') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>Filter berdasarkan Kota:</label>
                        <select name="kota" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Kota</option>
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Filter berdasarkan Cabang:</label>
                        <select name="branch_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama_cabang }} - {{ $branch->kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Outlet -->
        <div class="outlet-grid">
            @foreach($outlets as $outlet)
                <div class="outlet-card" data-city="{{ $outlet->branch ? $outlet->branch->kota : '' }}">
                    <div class="outlet-card-inner">
                        <div class="card-header">
                            {{ $outlet->nama_outlet }}
                        </div>

                        <!-- Card Image - FIXED -->
                        <div class="card-image">
                            <img src="{{ $outlet->foto_url }}"
                                 alt="{{ $outlet->nama_outlet }}"
                                 class="outlet-img"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-outlet.jpg') }}'">
                        </div>

                        <div class="card-body">
                            <div class="card-text">
                                <strong>Cabang:</strong> {{ $outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui' }}<br>
                                <strong>Kota:</strong> {{ $outlet->branch ? $outlet->branch->kota : 'Tidak diketahui' }}<br>
                                <strong>Alamat:</strong> {{ \Illuminate\Support\Str::limit($outlet->alamat_lengkap ?? $outlet->alamat, 100) }}<br>
                                <strong>Telepon:</strong> {{ $outlet->telepon ?? '-' }}<br>
                                <strong>Jam Operasional:</strong> {{ $outlet->jam_operasional ?? '24 Jam' }}
                            </div>
                            <button class="btn-detail" onclick="showOutletPopup({{ $outlet->id }})">Lihat Detail</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Jika tidak ada outlet -->
        @if($outlets->isEmpty())
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px;">
            <h3>Tidak ada outlet ditemukan</h3>
            <p>Silakan coba filter lain atau <a href="{{ route('customer.outlet') }}">reset filter</a></p>
        </div>
        @endif

        <!-- Popup Overlay -->
        <div class="popup-overlay" id="popupOverlay" style="display: none;">
            <div class="popup-container" role="dialog" aria-modal="true" aria-labelledby="popupTitle">
                <div class="popup-card" id="popupCard">
                    <!-- Konten popup diisi lewat JS -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
use Illuminate\Support\Str;
$outletsArray = $outlets->map(function($o) {
    // fasilitas → array
    $fasilitas = $o->fasilitas
        ? array_map('trim', explode(',', $o->fasilitas))
        : [];

    // Buat array fasilitas tambahan dari boolean fields
    $fasilitasTambahan = [];
    if ($o->tersedia_toilet) $fasilitasTambahan[] = 'Toilet';
    if ($o->tersedia_musholla) $fasilitasTambahan[] = 'Musholla';
    if ($o->tersedia_atm) $fasilitasTambahan[] = 'ATM';
    if ($o->tersedia_wifi) $fasilitasTambahan[] = 'WiFi';

    // Gabungkan semua fasilitas
    $semuaFasilitas = array_merge($fasilitas, $fasilitasTambahan);

    return [
        'id' => $o->id,
        'nama' => $o->nama_outlet,
        'cabang' => $o->branch ? $o->branch->nama_cabang : 'Tidak diketahui',
        'kota' => $o->branch ? $o->branch->kota : 'Tidak diketahui',
        'alamat' => $o->alamat_lengkap ?? $o->alamat,
        'telepon' => $o->telepon,
        'email' => $o->email,
        'fasilitas' => $semuaFasilitas,
        'jam_operasional' => $o->jam_operasional ?? '24 Jam',
        'tipe_outlet' => $o->tipe_outlet,
        'zona_pelayanan' => $o->zona_pelayanan,
        'kapasitas_parkir' => $o->kapasitas_parkir,
        'gambar' => $o->foto_url, // Menggunakan foto_url dari model
    ];
})->values();
@endphp

@push('scripts')
<script>
    // Data outlets diambil dari server (Blade -> JS)
    const outletsData = @json($outletsArray);
    const placeholderImage = "{{ asset('images/placeholder-outlet.jpg') }}";

    // Utility: cari outlet berdasarkan id
    function getOutletById(id) {
        return outletsData.find(o => Number(o.id) === Number(id));
    }

    // Tampilkan popup
    function showOutletPopup(id) {
        const outlet = getOutletById(id);
        const popupCard = document.getElementById('popupCard');
        const popupOverlay = document.getElementById('popupOverlay');

        if (!outlet || !popupCard || !popupOverlay) return;

        // build fasilitas HTML dari array fasilitas
        const facilitiesHtml = (outlet.fasilitas && outlet.fasilitas.length)
            ? outlet.fasilitas.map(f => `<div class="popup-facility-item">${escapeHtml(f)}</div>`).join('')
            : '<div class="popup-value">Tidak ada data fasilitas</div>';

        const contentHtml = `
            <div class="popup-header" id="popupTitle">
                ${escapeHtml(outlet.nama)}
                <button class="btn-close-popup" aria-label="Tutup" onclick="hideOutletPopup()">×</button>
            </div>

            <div class="popup-image-container">
                <img src="${escapeHtml(outlet.gambar)}"
                     alt="${escapeHtml(outlet.nama)}"
                     class="popup-image"
                     onerror="this.onerror=null;this.src='${placeholderImage}'">
            </div>

            <div class="popup-content">
                <div class="popup-info-grid">
                    <div class="popup-info-item">
                        <div class="popup-label">Cabang</div>
                        <div class="popup-value">${escapeHtml(outlet.cabang)} - ${escapeHtml(outlet.kota)}</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Tipe Outlet</div>
                        <div class="popup-value">${escapeHtml(outlet.tipe_outlet || 'Standard')}</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Alamat Lengkap</div>
                        <div class="popup-value">${escapeHtml(outlet.alamat)}</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Jam Operasional</div>
                        <div class="popup-value">${escapeHtml(outlet.jam_operasional)}</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Zona Pelayanan</div>
                        <div class="popup-value">${escapeHtml(outlet.zona_pelayanan || 'Seluruh kota')}</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Kapasitas Parkir</div>
                        <div class="popup-value">${outlet.kapasitas_parkir || 0} kendaraan</div>
                    </div>

                    <div class="popup-info-item">
                        <div class="popup-label">Kontak</div>
                        <div class="popup-value">
                            Telepon: ${escapeHtml(outlet.telepon || '-')}<br>
                            Email: ${escapeHtml(outlet.email || '-')}
                        </div>
                    </div>
                </div>

                <div class="popup-facilities">
                    <div class="popup-facilities-label">Fasilitas</div>
                    <div class="popup-facilities-grid">
                        ${facilitiesHtml}
                    </div>
                </div>
            </div>
        `;

        popupCard.innerHTML = contentHtml;
        popupOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // fokus ke tombol close untuk aksesibilitas
        const closeBtn = popupCard.querySelector('.btn-close-popup');
        if (closeBtn) closeBtn.focus();
    }

    function hideOutletPopup() {
        const popupOverlay = document.getElementById('popupOverlay');
        if (!popupOverlay) return;
        popupOverlay.style.display = 'none';
        document.body.style.overflow = '';
        const popupCard = document.getElementById('popupCard');
        if (popupCard) popupCard.innerHTML = '';
    }

    // Escape HTML untuk mencegah XSS jika data tidak trusted
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Tutup popup saat klik di luar card
    document.addEventListener('click', function(event) {
        const popupOverlay = document.getElementById('popupOverlay');
        if (!popupOverlay) return;
        // jika klik tepat pada overlay (bukan isi)
        if (event.target === popupOverlay) {
            hideOutletPopup();
        }
    });

    // Tutup popup dengan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') hideOutletPopup();
    });

    // Cek apakah background image berhasil dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const outletPage = document.getElementById('outletPage');
        const bgImage = new Image();

        const localImageUrl = "{{ asset('images/indonesia.jpeg') }}";

        bgImage.onerror = function() {
            outletPage.classList.add('no-bg');
        };

        bgImage.src = localImageUrl;
    });
</script>
@endpush
