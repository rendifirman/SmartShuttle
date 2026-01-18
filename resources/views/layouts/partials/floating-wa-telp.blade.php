<!-- Floating Customer Service Buttons -->
@if(isset($masterKontak) && $masterKontak)
    <div class="floating-cs-container">
        <!-- WhatsApp Button -->
        @php
            $whatsappNumber = $masterKontak->telepon_utama ?? '085811224321';
            // Format nomor WhatsApp: hapus semua karakter non-digit
            $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
            // Jika nomor diawali dengan 0, ganti dengan 62
            if (substr($whatsappNumber, 0, 1) === '0') {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            }
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=Halo%20" . urlencode($masterKontak->nama_perusahaan ?? 'Smart Shuttle') . "%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20shuttle.";
        @endphp
        <a href="{{ $whatsappUrl }}"
           target="_blank"
           class="cs-button whatsapp"
           data-tooltip="Chat WhatsApp"
           title="Chat via WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>

        <!-- Phone Button -->
        @php
            $phoneNumber = $masterKontak->telepon_utama ?? '0858-1122-4321';
            // Format untuk tel: link
            $phoneUrl = "tel:" . preg_replace('/[^0-9+]/', '', $phoneNumber);
        @endphp
        <a href="{{ $phoneUrl }}"
           class="cs-button phone"
           data-tooltip="Telepon Customer Service"
           title="Telepon Customer Service">
            <i class="fas fa-phone"></i>
        </a>
    </div>
@endif