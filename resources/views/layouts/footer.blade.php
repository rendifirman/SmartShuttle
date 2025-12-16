<!-- Footer - Clean Style -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-main">
            <!-- Smart Shuttle -->
            <div class="footer-column">
                <h3 class="footer-title">Smart Shuttle</h3>
                <p class="footer-text">
                    Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.
                </p>
            </div>

            <!-- Kontak -->
            <div class="footer-column">
                <h4 class="footer-subtitle">Kontak</h4>
                <div class="contact-list">
                    <div class="contact-line">
                        <span>Whatsapp: +62 858-1122-4321</span>
                    </div>
                    <div class="contact-line">
                        <span>Email: mdcitrasolusi@gmail.com</span>
                    </div>
                    <div class="contact-line">
                        <span class="address">Alamat: Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434</span>
                    </div>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="footer-column">
                <h4 class="footer-subtitle">Sosial Media</h4>
                <p class="footer-text">
                    Dengan layanan unggulan yang kami hadirkan, kami berkomitmen untuk menjadikan setiap momen perjalanan Anda lebih istimewa.
                </p>
                <div class="social-buttons">
                    <a href="#" class="social-button">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="citrasolusi.id" class="social-button">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-button">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="copyright">
                    &copy; 2024 Smart Shuttle. All rights reserved.
                </p>
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: #00215E;
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
        gap: 8px;
    }

    .contact-line {
        font-size: 14px;
        color: #e0e0e0;
        line-height: 1.4;
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
    }

    .social-button {
        width: 32px;
        height: 32px;
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
    }

    .social-button i {
        color: white;
        font-size: 12px;
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
        color: white;
    }
    
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
    }
</style>