@php
    use Illuminate\Support\Str;

    // Fungsi helper untuk gambar outlet
    function getOutletImage($outlet) {
        if (!empty($outlet->foto_outlet)) {
            // Jika sudah URL lengkap
            if (Str::startsWith($outlet->foto_outlet, ['http://', 'https://'])) {
                return $outlet->foto_outlet;
            }

            // Cek apakah file ada di public/images/outlets/
            $filename = basename($outlet->foto_outlet);
            $publicPath = 'images/outlets/' . $filename;

            if (file_exists(public_path($publicPath))) {
                return asset($publicPath);
            }

            // Coba langsung path yang ada
            if (file_exists(public_path($outlet->foto_outlet))) {
                return asset($outlet->foto_outlet);
            }
        }

        return asset('images/placeholder-outlet.jpg');
    }

    // Set default values jika variabel tidak ada
    $totalOutlets = $totalOutlets ?? $outlets->count();
    $hasMore = $hasMore ?? false;

    // Set gambar outlet jika belum ada
    $gambar = $gambar ?? getOutletImage($outlet);
@endphp

<div class="outlet-card" data-city="{{ $outlet->branch ? $outlet->branch->kota : '' }}">
    <div class="outlet-card-inner">
        <div class="card-header">
            {{ $outlet->nama_outlet }}
        </div>
        <div class="card-image">
            <img src="{{ $gambar }}"
                 alt="{{ $outlet->nama_outlet }}"
                 class="outlet-img"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-outlet.jpg') }}'">
        </div>
        <div class="card-body">
            <!-- Grid informasi outlet -->
            <div class="info-grid">
                <!-- Cabang -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-store"></i> CABANG
                    </div>
                    <div class="info-value">
                        {{ $outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui' }}
                    </div>
                </div>

                <!-- Kota -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-city"></i> KOTA
                    </div>
                    <div class="info-value">
                        {{ $outlet->branch ? $outlet->branch->kota : 'Tidak diketahui' }}
                    </div>
                </div>

                <!-- Alamat (Full Width) -->
                <div class="info-item full-width">
                    <div class="info-label">
                        <i class="fas fa-map-marker-alt"></i> ALAMAT
                    </div>
                    <div class="info-value address">
                        {{ $outlet->alamat_lengkap ?? $outlet->alamat }}
                    </div>
                </div>
            </div>

            <!-- Contact & Hours -->
            <div class="contact-hours">
                <div class="contact-hours-grid">
                    <!-- Telepon -->
                    <div class="contact-item">
                        <div class="contact-label">
                            <i class="fas fa-phone"></i> TELEPON
                        </div>
                        <div class="contact-value">
                            {{ $outlet->telepon ?? '-' }}
                        </div>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="hours-item">
                        <div class="hours-label">
                            <i class="fas fa-clock"></i> JAM OPERASIONAL
                        </div>
                        <div class="hours-value">
                            {{ $outlet->jam_operasional ?? '24 Jam' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Detail -->
            <button class="btn-detail" onclick="showOutletPopup({{ $outlet->id }})">
                <i class="fas fa-eye"></i> Lihat Detail
            </button>
        </div>
    </div>
</div>
