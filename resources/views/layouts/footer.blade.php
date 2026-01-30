<!-- resources/views/layout/footer.blade.php -->
<footer class="site-footer">
    <div class="footer-container">
        @php
            // Data dari AppServiceProvider
            $kontak = $masterKontak ?? null;
            $service = $kontakService ?? app('kontakService');

            // Helper function untuk format telepon
            function formatPhone($phone) {
                if (!$phone || $phone === '#') return '';
                return $phone;
            }

            // Helper function untuk email
            function formatEmail($email) {
                if (!$email || $email === '#') return '';
                return $email;
            }
        @endphp

        <div class="footer-main">
            <!-- Smart Shuttle -->
            <div class="footer-column">
                <h3 class="footer-title">{{ $kontak->nama_perusahaan ?? 'Smart Shuttle' }}</h3>
                <p class="footer-text">
                    {{ $kontak->deskripsi_singkat ?? 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.' }}
                </p>
            </div>

            <!-- Kontak -->
            <div class="footer-column">
                <h4 class="footer-subtitle">Kontak</h4>
                <div class="contact-list">
                    @if($service->isValidUrl($kontak->telepon_utama ?? ''))
                    <div class="contact-line">
                        <span>
                            <i class="fab fa-whatsapp"></i>
                            @php
                                $whatsappNumber = $service->formatWhatsApp($kontak->telepon_utama);
                            @endphp
                            @if($whatsappNumber)
                            <a href="https://wa.me/{{ $whatsappNumber }}"
                               target="_blank"
                               style="color: white; text-decoration: none;">
                                {{ formatPhone($kontak->telepon_utama) }}
                            </a>
                            @else
                            <span>{{ formatPhone($kontak->telepon_utama) }}</span>
                            @endif
                        </span>
                    </div>
                    @endif

                    @if($service->isValidUrl($kontak->email_utama ?? ''))
                    <div class="contact-line">
                        <span>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ formatEmail($kontak->email_utama) }}"
                               style="color: white; text-decoration: none;">
                                {{ formatEmail($kontak->email_utama) }}
                            </a>
                        </span>
                    </div>
                    @endif

                    @if($service->isValidUrl($kontak->alamat_kantor_pusat ?? ''))
                    <div class="contact-line">
                        <span class="address">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $kontak->alamat_kantor_pusat }}
                        </span>
                    </div>
                    @endif

                    @if($service->isValidUrl($kontak->telepon_dukungan ?? ''))
                    <div class="contact-line">
                        <span>
                            <i class="fas fa-phone-alt"></i>
                            {{ formatPhone($kontak->telepon_dukungan) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="footer-column">
                <h4 class="footer-subtitle">Sosial Media</h4>
                <p class="footer-text">
                    Ikuti kami di sosial media untuk mendapatkan informasi terbaru dan promo menarik.
                </p>
                <div class="social-buttons">
                    @if($service->isValidUrl($kontak->facebook_url ?? ''))
                    @php
                        $facebookUrl = $service->formatSocialUrl($kontak->facebook_url, 'facebook');
                    @endphp
                    <a href="{{ $facebookUrl }}"
                       class="social-button"
                       target="_blank"
                       title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif

                    @if($service->isValidUrl($kontak->instagram_url ?? ''))
                    @php
                        $instagramUrl = $service->formatSocialUrl($kontak->instagram_url, 'instagram');
                    @endphp
                    <a href="{{ $instagramUrl }}"
                       class="social-button"
                       target="_blank"
                       title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif

                    @if($service->isValidUrl($kontak->twitter_url ?? ''))
                    @php
                        $twitterUrl = $service->formatSocialUrl($kontak->twitter_url, 'twitter');
                    @endphp
                    <a href="{{ $twitterUrl }}"
                       class="social-button"
                       target="_blank"
                       title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif

                    @if($service->isValidUrl($kontak->email_utama ?? ''))
                    <a href="mailto:{{ formatEmail($kontak->email_utama) }}"
                       class="social-button"
                       title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="copyright">
                    &copy; {{ date('Y') }} {{ $kontak->nama_perusahaan ?? 'Smart Shuttle' }}. All rights reserved.
                </p>
                <div class="footer-links">
                    @if($service->isValidUrl($kontak->link_kebijakan_privasi ?? ''))
                    <a href="{{ $kontak->link_kebijakan_privasi }}"
                       class="footer-link policy-link"
                       data-policy="privacy"
                       target="_blank">
                        Privacy Policy
                    </a>
                    @endif

                    @if($service->isValidUrl($kontak->link_syarat_ketentuan ?? ''))
                    <a href="{{ $kontak->link_syarat_ketentuan }}"
                       class="footer-link policy-link"
                       data-policy="terms"
                       target="_blank">
                        Terms of Service
                    </a>
                    @endif

                    @if($service->isValidUrl($kontak->email_utama ?? ''))
                    <a href="mailto:{{ formatEmail($kontak->email_utama) }}"
                       class="footer-link">
                        Contact Us
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.site-footer {
    background: #0f2942ff;
    color: white;
    padding: 50px 40px 20px;
    margin-top: auto;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
}

.footer-main {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 40px;
    gap: 40px;
}

.footer-column {
    flex: 1;
}

.footer-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #FF581E;
}

.footer-subtitle {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #FF581E;
}

.footer-text {
    font-size: 14px;
    color: #e0e0e0;
    line-height: 1.6;
    margin-bottom: 15px;
}

/* Contact List */
.contact-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-line {
    font-size: 14px;
    color: #e0e0e0;
    line-height: 1.4;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.contact-line i {
    color: #FF581E;
    width: 16px;
    margin-top: 2px;
    flex-shrink: 0;
}

.address {
    font-size: 13px;
    line-height: 1.5;
}

/* Social Buttons */
.social-buttons {
    display: flex;
    gap: 12px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.social-button {
    width: 36px;
    height: 36px;
    background: #FF581E;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-button:hover {
    background: #E54E1A;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.social-button i {
    color: white;
    font-size: 14px;
}

/* Footer Bottom */
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 20px;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.copyright {
    font-size: 14px;
    color: #b0b0b0;
    margin: 0;
}

.footer-links {
    display: flex;
    gap: 25px;
    align-items: center;
}

.footer-link {
    font-size: 14px;
    color: #b0b0b0;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-link:hover {
    color: #FF581E;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .site-footer {
        padding: 40px 20px 20px;
    }

    .footer-main {
        flex-direction: column;
        gap: 30px;
        margin-bottom: 30px;
    }

    .footer-column {
        width: 100%;
    }

    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .footer-links {
        flex-direction: column;
        gap: 10px;
    }

    .social-buttons {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .site-footer {
        padding: 30px 15px 15px;
    }

    .footer-title {
        font-size: 16px;
    }

    .footer-subtitle {
        font-size: 15px;
    }

    .contact-line {
        font-size: 13px;
    }
}
</style>
