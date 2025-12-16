<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Shuttle - Beranda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <style>
        /* CSS Variables */
        :root {
            --primary-color: #00215E;
            --secondary-color: #FF581E;
        }

        /* Reset margin dan padding untuk body */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden; /* Mencegah scroll horizontal */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
        }

        /* Custom Navbar Styles - HANYA UNTUK BERANDA */
        .custom-navbar {
            background: transparent;
            padding: 20px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            min-height: 80px;
            transform: translateY(0);
        }

        .custom-navbar.hidden {
            transform: translateY(-100%);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        /* Panel Oval untuk Navbar */
        .nav-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50px;
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .nav-brand img {
            height: 35px;
            width: auto;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            flex: 1;
        }

        .nav-links {
            display: flex;
            gap: 35px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .nav-links a.active {
            color: var(--secondary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--secondary-color);
            transition: width 0.3s;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-auth {
            display: flex;
            justify-content: flex-end;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Navbar saat di-scroll */
        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        }

        .custom-navbar.scrolled .nav-links a {
            color: var(--primary-color);
        }

        .custom-navbar.scrolled .btn-login {
            background-color: var(--secondary-color);
        }

        /* Hero Section dengan Background Image */
        .hero-section {
            position: relative;
            height: 100vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 6%;
            margin-bottom: 30px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;

        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 50%;
            color: white;
        }

        .hero-title {
            font-size: 54px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero-desc {
            font-size: 18px;
            line-height: 1.7;
            max-width: 520px;
        }

        .hero-services {
    display: flex;
    text-decoration: none;
    justify-content: flex-start; /* Ubah dari space-between */
    gap: 8px; /* Kurangi dari 15px */
    margin-top: 35px;
    max-width: 400px; /* Tambahkan batas maksimal lebar */
}

      .hero-service {
        text-decoration: none;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 12px 14px; /* Kurangi padding */
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px; /* Kurangi gap internal */
    font-size: 14px;
    backdrop-filter: blur(6px);
    transition: background 0.3s, transform 0.3s;
    flex: 1; /* Biarkan fleksibel */
    min-width: 110px; /* Atur lebar minimum */
}

        .hero-service:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .hero-service i {
            font-size: 32px;
            color: #fff;
        }

        .hero-service span {
            color: #fff;
            font-weight: 600;
        }


        .hero-visual {
            position: absolute;
            right: 5%;
            bottom: 80px;
            z-index: 2;
        }

        .hero-car {
            width: 520px;
        }

        .hero-box {
            width: 140px;
            position: absolute;
            right: -40px;
            bottom: -20px;
        }

        /* ================= SEARCH SECTION (DESIGN FINAL) ================= */

        .search-section {
            position: relative;
            z-index: 20;
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: -138px;
        }

        .search-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255,255,255,0.15);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.18);
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr auto;
            gap: 14px;
            align-items: center;
        }

        /* FIELD */
        .search-field {
            width: 100%;
        }

        .search-input {
            width: 100%;
            height: 48px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            background: #ffffff;
            color: black;
            font-weight: bold;
            padding: 0 12px;
            box-sizing: border-box;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.25);
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: none !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            font-weight: bold !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 48px !important;
            color: black !important;
            font-size: 14px !important;
            padding-left: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
        }

        .select2-dropdown {
            border: none !important;
            border-radius: 6px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        }

        /* BUTTON */
        .search-btn-container {
            height: 48px;

        }

        .search-btn {
            height: 100%;
            border-radius: 12px;
            background: #FF581E;
            color: #fff;
            border: none;
            font-weight: 700;
            padding: 0 32px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .search-btn:hover {
            filter: brightness(0.94);
            transform: translateY(-1px);
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {
            .search-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-btn-container {
                grid-column: span 2;
            }

            .search-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .search-row {
                grid-template-columns: 1fr;
            }

            .search-btn-container {
                grid-column: span 1;
            }
        }


        /* Header di atas gambar */
        .header {
            padding: 140px 40px 60px;
            text-align: left;
            color: white;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 50%;
        }

        .logo {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .tagline {
            font-size: 18px;
            opacity: 0.95;
            max-width: 600px;
            margin: 0;
            line-height: 1.8;
            color: white;
            text-shadow: 0 1px 5px rgba(0,0,0,0.3);
        }



        .autocomplete-container {
            position: relative;
            width: 100%;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .autocomplete-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            font-size: 12px;
            transition: background-color 0.2s;
        }

        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item.active {
            background-color: #FF581E;
            color: white;
        }

        /* ===== SERVICES SECTION ===== */
        .services-section {
            padding: 80px 0;
            background: #ffffff;
            text-align: center;
        }

        .services-title {
            font-size: 26px;
            font-weight: 700;
            color: #17375f;
            margin-bottom: 10px;
        }

        .services-subtitle {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
            max-width: 780px;
            margin: 0 auto 50px;
        }

        /* GRID */
        .services-grid {
            display: flex;
            justify-content: center;
            gap: 35px;
        }

        /* CARD */
        .service-card {
            width: 330px;
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px 28px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        /* LOGO WRAPPER */
        .service-logo-box {
            border: 1.8px solid #dcdcdc;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .service-logo-box img {
            width: 200px;
            height: auto;
        }

        /* TEXT */
        .service-desc {
            font-size: 13px;
            font-weight: 600;
            color: #17375f;
            line-height: 1.5;
        }

        /* ===== INTERACTION BASE ===== */
        .service-card {
            transition: all 0.35s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        /* CARD HOVER */
        .service-card:hover {
            transform: translateY(-10px) scale(1.01);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        /* BORDER GLOW EFFECT */
        .service-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            border: 2px solid transparent;
            transition: 0.35s;
        }

        .service-card:hover::before {
            border-color: #ff6b2c;
        }

        /* LOGO ANIMATION */
        .service-logo-box {
            transition: all 0.35s ease;
        }

        .service-card:hover .service-logo-box {
            transform: scale(1.03);
        }

        /* LOGO IMAGE */
        .service-logo-box img {
            transition: 0.35s ease;
        }

        .service-card:hover .service-logo-box img {
            transform: scale(1.06);
        }

        /* TEXT ANIMATION */
        .service-desc {
            transition: 0.35s ease;
        }

        .service-card:hover .service-desc {
            color: #ff6b2c;
        }

        /* SOFT SHINE EFFECT */
        .service-card::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -100%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                120deg,
                transparent 30%,
                rgba(255, 255, 255, 0.15) 40%,
                transparent 55%
            );
            transition: 0.6s;
        }

        .service-card:hover::after {
            top: -20%;
            left: -20%;
        }



        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
            margin: 50px 0;
            opacity: 0.6;
        }


        /* Features Section */
        .features-section {
            padding: 80px 40px;
            background: white;
            color: #333;
            text-align: center;
        }

        .features-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 60px;
            color: #123352;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .features-grid-6 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: #123352;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(18, 51, 82, 0.15);
            transition: all 0.3s ease;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(18, 51, 82, 0.25);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #FF581E;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 25px;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
        }

        .feature-label {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            line-height: 1.4;
        }

        .feature-desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin: 0;
        }

        /* Feedback Section */
        .feedback-section {
            padding: 80px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .feedback-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.12);
            padding: 50px;
        }

        .feedback-title {
            font-size: 32px;
            color: #123352;
            margin-bottom: 10px;
            font-weight: 700;
            text-align: left;
        }

        .feedback-line {
            width: 100%;
            height: 2px;
            background: #dcdcdc;
            margin-bottom: 30px;
        }

        .review-wrapper {
            display: grid;
            grid-template-columns: 1fr 0.9fr;
            gap: 40px;
        }

        /* LEFT REVIEW LIST */
        .review-card {
            background: white;
            border: 1px solid #e2e2e2;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            gap: 18px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #FF581E;
        }

        .review-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #FF581E;
        }

        .review-name {
            font-weight: bold;
            font-size: 18px;
            margin-top: 3px;
            color: #123352;
        }

        .review-text {
            margin-top: 8px;
            line-height: 1.5;
            color: #444;
            font-size: 14px;
        }

       .stars {
    color: #ff9d00;
    font-size: 20px;
    letter-spacing: 3px;
    }

    /* Untuk bintang kosong */
    .stars .empty-star {
        color: #ddd;
    }

    /* Atau gunakan ini di review section */
    .review-card .stars {
        color: #ff9d00;
        font-size: 20px;
        letter-spacing: 3px;
    }

        /* RIGHT REVIEW FORM */
        .form-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #123352;
        }

        .star-input {
            font-size: 32px;
            color: #ff9d00;
            margin-bottom: 20px;
            letter-spacing: 3px;
            cursor: pointer;
        }

        .star-input i {
            margin-right: 5px;
            transition: all 0.2s ease;
        }

        .star-input i:hover {
            transform: scale(1.1);
        }

        .form-textarea {
            width: 100%;
            height: 130px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #d1d1d1;
            font-size: 15px;
            resize: none;
            outline: none;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            border-color: #FF581E;
            box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.2);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #FF581E 0%, #ff7b4d 100%);
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
        }

        .btn-close {
            width: 100%;
            background: #333;
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            margin-top: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-close:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .review-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .feedback-container {
                padding: 40px 30px;
            }

            .nav-panel {
                padding: 8px 20px;
            }

            .nav-links {
                gap: 25px;
            }
        }

        @media (max-width: 768px) {
            .custom-navbar {
                padding: 15px 3%;
            }

            .nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-panel {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
                border-radius: 25px;
            }

            .nav-brand, .nav-menu, .nav-auth {
                width: 100%;
                justify-content: center;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .header {
                max-width: 90%;
                padding: 160px 20px 60px;
                text-align: center;
            }


            .services-section {
                padding: 40px 20px;
            }

            .services-grid {
                flex-direction: column;
                align-items: center;
            }

            .service-card {
                width: 100%;
                max-width: 330px;
                padding: 30px 15px 25px 15px;
            }

            .features-section {
                padding: 60px 20px;
            }

            .features-title {
                font-size: 28px;
                margin-bottom: 40px;
            }

            .features-grid-6 {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .feature-card {
                padding: 30px 25px;
            }

            .feature-icon {
                font-size: 40px;
                margin-bottom: 20px;
                height: 70px;
            }

            .feature-label {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .feature-desc {
                font-size: 13px;
            }

            .feedback-section {
                padding: 60px 20px;
            }

            .feedback-container {
                padding: 30px 25px;
                border-radius: 20px;
            }

            .feedback-title {
                font-size: 28px;
            }

            .review-card {
                padding: 15px;
                flex-direction: column;
                text-align: center;
            }

            .review-avatar {
                width: 50px;
                height: 50px;
                margin: 0 auto;
            }

            .stars {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header {
                max-width: 95%;
                padding: 140px 15px 40px;
            }

            .logo {
                font-size: 36px;
            }

            .tagline {
                font-size: 16px;
            }

            .features-title {
                font-size: 24px;
            }

            .feature-card {
                padding: 25px 20px;
            }

            .feature-icon {
                font-size: 36px;
                height: 60px;
            }

            .feature-label {
                font-size: 16px;
            }

            .feedback-title {
                font-size: 24px;
            }

            .form-title {
                font-size: 20px;
            }

            .star-input {
                font-size: 28px;
            }

            .btn-primary,
            .btn-close {
                padding: 12px;
                font-size: 16px;
            }

            .nav-links a {
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .nav-panel {
                padding: 12px;
                border-radius: 20px;
            }
        }

        /* Footer Styles */
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

        /* Profile icon + small name */
        .profile-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: none;
            padding: 6px 8px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-btn:hover,
        .profile-btn:focus {
            outline: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            background: rgba(0, 33, 94, 0.05);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--secondary-color), #ff7b4d);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            flex-shrink: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-name {
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 600;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

    /* pastikan dropdown ada di atas semua layer navbar/hero */
        .dropdown-menu {
            z-index: 3000; /* tingkat lebih tinggi agar tidak tertutup */
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 170px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 10px;
        }


        .dropdown-menu a {
            display: block;
            padding: 8px 12px;
            color: #00215E;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background-color 0.2s;
        }

        .dropdown-menu a:hover {
            background-color: rgba(0, 33, 94, 0.05);
        }

        .dropdown-menu form {
            margin: 0;
        }

        .dropdown-menu button[type="submit"] {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            background: none;
            border: none;
            color: #00215E;
            cursor: pointer;
            border-radius: 5px;
            font-family: inherit;
            font-size: inherit;
            transition: background-color 0.2s;
        }

        .dropdown-menu button[type="submit"]:hover {
            background-color: rgba(0, 33, 94, 0.05);
        }

        /* Tambahkan class untuk show */
        .dropdown-menu.show {
            display: block;
        }

        /* Tambahkan style ini di bagian CSS services section */
        .service-description {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-top: 10px;
            padding: 0 10px;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @php
        // Ambil data profil perusahaan dari database
        use App\Models\MProfilePerusahaan;
        $profile = MProfilePerusahaan::first();
    @endphp
</head>
<body>
    <!-- Custom Navbar Hanya untuk Beranda -->
    <nav class="custom-navbar" id="navbar">
    <div class="nav-container">
        <div class="nav-panel">
            <div class="nav-brand">
                <img src="{{ asset($profile->logo_perusahaan ?? '/images/smartshuttlelogo.png') }}" alt="{{ $profile->nama_dagang ?? 'Smart Shuttle' }}">
            </div>
                <div class="nav-menu">
                   <ul class="nav-links">
    <li><a href="/customer/beranda" class="active">Beranda</a></li>
    <li><a href="{{ route('customer.search') }}">Cari Tiket</a></li>
    <li><a href="{{ route('customer.outlet') }}">Outlet</a></li>
    <li><a href="/customer/contact">Kontak</a></li>
</ul>
                </div>
               <!-- BAGIAN NAV-AUTH -->
<div class="nav-auth">
    @if(isset($user) && $user)
        <div class="profile-wrapper">
            <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                @if(!empty($user['avatar'] ?? null))
                    <span class="profile-avatar">
                        <img src="{{ $user['avatar'] }}" alt="avatar">
                    </span>
                @else
                    <span class="profile-avatar">{{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}</span>
                @endif
                <span class="profile-name">{{ strlen($user['name'] ?? '') > 12 ? substr($user['name'], 0, 12).'...' : ($user['name'] ?? 'User') }}</span>
            </button>

            <div id="dropdown-menu" class="dropdown-menu">
                <a href="{{ route('customer.dashboardprofile') }}">Profil</a>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('customer.login') }}" class="btn-login">Login</a>
    @endif
</div>
            </div>
        </div>
    </nav>

  <!-- Hero Section dengan Background Image -->
<div class="hero-section" style="background-image:url('{{ asset($profile->background_website ?? 'images/bgSmartShuttle2.png') }}');">
    <div class="hero-content">
        <h1 class="hero-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h1>
        <p class="hero-desc">
            {{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}
        </p>

        <div class="hero-services">
            <a href="{{ route('customer.search') }}" class="hero-service">
                <i class="fas fa-shuttle-van"></i>
                <span>Tiket Shuttle</span>
            </a>
            <a href="#" class="hero-service" onclick="alert('Fitur Kirim Paket akan segera hadir!')">
                <i class="fas fa-box"></i>
                <span>Kirim Paket</span>
            </a>
            <a href="#" class="hero-service" onclick="alert('Fitur Sewa Armada akan segera hadir!')">
                <i class="fas fa-car"></i>
                <span>Sewa Armada</span>
            </a>
        </div>
    </div>
</div>
    </div>

</div>

   <!-- Search Section -->
<div class="search-section">
    <div class="search-container">
        <form action="{{ route('customer.search') }}" method="GET" id="search-form">
            <div class="search-row">
                <div class="search-field">
                    <div class="autocomplete-container">
                        <select class="search-input select2-dropdown" id="departure-outlet" name="departure_outlet" required>
                            <option value="">Pilih Outlet Keberangkatan</option>
                            @foreach($outletsGrouped as $kota => $outlets)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">
                                            {{ $outlet->nama_outlet }}
                                             {{-- - {{ $outlet->alamat_lengkap }} --}}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="search-field">
                    <div class="autocomplete-container">
                        <select class="search-input select2-dropdown" id="destination-outlet" name="destination_outlet" required>
                            <option value="">Pilih Outlet Tujuan</option>
                            @foreach($outletsGrouped as $kota => $outlets)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">
                                            {{ $outlet->nama_outlet }}
                                             {{-- - {{ $outlet->alamat_lengkap }} --}}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="search-field">
                    <input type="date" class="search-input" name="departure_date"
                           value="{{ old('departure_date', date('Y-m-d')) }}"
                           min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="search-field">
                    <input type="number" class="search-input" name="passenger_count"
                           value="{{ old('passenger_count', 1) }}"
                           placeholder="Jumlah..." min="1" max="10" required>
                </div>
                <div class="search-btn-container">
                    <button type="submit" class="search-btn">CEK SHUTTLE</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <!-- Divider -->
    <div class="divider"></div>

   <!-- Services Section -->
<div class="services-section">
    <h2 class="services-title">Layanan Utama {{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h2>

    <p class="services-subtitle">
        {{ $profile->services_subtitle ?? 'Nikmati tiga layanan unggulan kami – SmartShuttle, SmartRent, dan SmartSend. Dirancang untuk memenuhi kebutuhan perjalanan dan pengiriman Anda dengan mudah dan cepat.' }}
    </p>

    <div class="services-grid">
        @forelse($layanan as $item)
        <!-- Dynamic Service Cards -->
        <div class="service-card">
            <div class="service-logo-box">
                @if($item->logo)
                    <img src="{{ asset($item->logo) }}"
                        alt="{{ $item->nama_layanan }}"
                        onerror="this.onerror=null; this.src='/images/default-service.png';">
                @else
                    <img src="/images/default-service.png"
                        alt="{{ $item->nama_layanan }}">
                @endif
            </div>
            <p class="service-desc">
                {{ $item->nama_layanan }}
            </p>
        </div>
        @empty
        <!-- Fallback jika tidak ada data -->
        <div class="service-card">
            <div class="service-logo-box">
                <img src="{{ asset('images/lgsmartrent.png') }}" alt="Smart Rent">
            </div>
            <p class="service-desc">
                Layanan Penyewaan Armada
            </p>
        </div>

        <div class="service-card">
            <div class="service-logo-box">
                <img src="{{ asset('images/smartshuttlelogo.png') }}" alt="Smart Shuttle">
            </div>
            <p class="service-desc">
                Layanan Pemesanan Tiket Shuttle Antarkota
            </p>
        </div>

        <div class="service-card">
            <div class="service-logo-box">
                <img src="{{ asset('images/lgsmartsend.png') }}" alt="Smart Send">
            </div>
            <p class="service-desc">
                Layanan Pengiriman Barang Antar Kota
            </p>
        </div>
        @endforelse
    </div>
</div>

   <!-- Features Section -->
<div class="features-section">
    <h2 class="features-title">{{ strtoupper($profile->nama_dagang ?? 'SMART SHUTTLE') }} {{ $profile->features_title ?? 'SIAP MENEMANI SETIAP PERJALANANMU!' }}</h2>

    <div class="features-grid-6">
        @php
            $features = isset($profile->features) ? json_decode($profile->features, true) : [];
        @endphp

        @if(!empty($features))
            @foreach($features as $feature)
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="{{ $feature['icon'] ?? 'fas fa-star' }}"></i>
                </div>
                <h3 class="feature-label">{{ $feature['title'] ?? 'Judul Fitur' }}</h3>
                <p class="feature-desc">{{ $feature['description'] ?? 'Deskripsi fitur' }}</p>
            </div>
            @endforeach
        @else
            <!-- Fallback jika tidak ada data -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-route"></i>
                </div>
                <h3 class="feature-label">Perjalanan Tanpa Ribet</h3>
                <p class="feature-desc">Pesan tiket antar kota secara online dengan cepat dan nyaman, semua urusan perjalanan kamu lebih mudah!</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3 class="feature-label">Harga Bersahabat</h3>
                <p class="feature-desc">Nikmati perjalanan nyaman dengan tarif terjangkau tanpa kompromi kualitas.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-car-alt"></i>
                </div>
                <h3 class="feature-label">Sewa Fleksibel</h3>
                <p class="feature-desc">Butuh kendaraan pribadi atau bisnis? SmartRent siap kapan pun kamu butuh.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="feature-label">Kirim Cepat & Aman</h3>
                <p class="feature-desc">SmartSend bantu antar paketmu tepat waktu, dengan pelacakan real-time.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-label">Satu Aplikasi, Semua Bisa!</h3>
                <p class="feature-desc">Perjalanan, sewa, dan kirim barang – semua dalam satu platform SmartShuttle.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-label">Bantuan 24/7</h3>
                <p class="feature-desc">Tim kami selalu siap membantu setiap langkah perjalananmu.</p>
            </div>
        @endif
    </div>
</div>

<section class="feedback-section">
    <div class="feedback-container">
        <h2 class="feedback-title">Review</h2>
        <div class="feedback-line"></div>

        <div class="review-wrapper">
            <!-- ================= LEFT REVIEW LIST ================= -->
            <div>
                @php
                    $reviews = isset($profile->reviews) ? json_decode($profile->reviews, true) : [];
                @endphp

                @if(!empty($reviews))
                    @foreach($reviews as $review)
                    <div class="review-card">
                        <img src="{{ $review['avatar'] ?? 'https://randomuser.me/api/portraits/women/32.jpg' }}"
                             class="review-avatar"
                             alt="{{ $review['name'] ?? 'Reviewer' }}">
                        <div>
                            <div class="stars">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < ($review['stars'] ?? 5))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <div class="review-name">{{ $review['name'] ?? 'Nama Reviewer' }}</div>
                            <div class="review-text">
                                {{ $review['text'] ?? 'Teks review akan ditampilkan di sini.' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Fallback jika tidak ada data -->
                    <div class="review-card">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="review-avatar" alt="Luna Ayna">
                        <div>
                            <div class="stars">★★★★★</div>
                            <div class="review-name">Luna Ayna</div>
                            <div class="review-text">
                                Servisnya bagus, drivernya sopan dan nyetirnya halus jadi bisa tidur selama perjalanan.
                                Tracking lokasinya juga akurat. Bakal jadi langganan.
                            </div>
                        </div>
                    </div>

                    <div class="review-card">
                        <img src="https://randomuser.me/api/portraits/men/54.jpg" class="review-avatar" alt="Rizky Pratama">
                        <div>
                            <div class="stars">★★★★☆</div>
                            <div class="review-name">Rizky Pratama</div>
                            <div class="review-text">
                                Pertama kali coba SmartShuttle dan langsung puas. Mobilnya bersih, AC dingin, kursinya empuk.
                                Berangkat juga sesuai jadwal. Recommended banget buat yang sering PP Jakarta–Bandung!
                            </div>
                        </div>
                    </div>

                    <div class="review-card">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" class="review-avatar" alt="Sari Dewi">
                        <div>
                            <div class="stars">★★★★★</div>
                            <div class="review-name">Sari Dewi</div>
                            <div class="review-text">
                                Harganya menurut saya cukup murah dibanding shuttle lain, tapi kualitas layanannya tetap bagus.
                                Pemesanan lewat aplikasi juga gampang.
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- ================= RIGHT REVIEW FORM ================= -->
            <div>
                <div class="form-title">Berikan Review Anda</div>

                <div class="star-input" id="star-rating">
                    <i class="fas fa-star" data-rating="1"></i>
                    <i class="fas fa-star" data-rating="2"></i>
                    <i class="fas fa-star" data-rating="3"></i>
                    <i class="fas fa-star" data-rating="4"></i>
                    <i class="fas fa-star" data-rating="5"></i>
                </div>

                <textarea class="form-textarea" id="review-text" placeholder="Tulis pendapat anda..."></textarea>

                <button class="btn-primary" id="submit-review">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Review
                </button>
                <button class="btn-close" id="close-review">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</section>
    </div>
  <footer class="site-footer">
    <div class="footer-container">
        <div class="footer-main">
            <!-- Smart Shuttle -->
            <div class="footer-column">
                <h3 class="footer-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h3>
                <p class="footer-text">
                    {{ $profile->deskripsi_singkat ?? 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.' }}
                </p>
            </div>

            <!-- Kontak -->
<div class="footer-column">
    <h4 class="footer-subtitle">Kontak</h4>
    <div class="contact-list">
        <div class="contact-line">
            <span>Whatsapp: {{ $profile->telepon ?? '+62 858-1122-4321' }}</span>
        </div>
        <div class="contact-line">
            <span>Email: {{ $profile->email ?? 'mdcitrasolusi@gmail.com' }}</span>
        </div>
        <div class="contact-line">
            <span class="address">Alamat: {{ $profile->alamat_kantor_pusat ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434' }}</span>
        </div>
    </div>
</div>

<!-- Sosial Media -->
<div class="footer-column">
    <h4 class="footer-subtitle">Sosial Media</h4>
    <p class="footer-text">
        {{ $profile->footer_description ?? 'Dengan layanan unggulan yang kami hadirkan, kami berkomitmen untuk menjadikan setiap momen perjalanan Anda lebih istimewa.' }}
    </p>
    <div class="social-buttons">
        <a href="{{ $profile->facebook_url ?? '#' }}" class="social-button" target="_blank">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="{{ $profile->instagram_url ?? 'https://citrasolusi.id' }}" class="social-button" target="_blank">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="{{ $profile->twitter_url ?? '#' }}" class="social-button" target="_blank">
            <i class="fab fa-twitter"></i>
        </a>
    </div>
</div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="copyright">
                    &copy; {{ date('Y') }} {{ $profile->nama_dagang ?? 'Smart Shuttle' }}. All rights reserved.
                </p>
                <div class="footer-links">
                    <a href="{{ $profile->link_kebijakan_privasi ?? '#' }}" class="footer-link">Privacy Policy</a>
                    <a href="{{ $profile->link_syarat_ketentuan ?? '#' }}" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript yang sudah diperbaiki -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - init scripts');

    /* ---------- NAVBAR SCROLL ---------- */
    const navbar = document.getElementById('navbar');
    let lastScrollY = window.scrollY || 0;
    if (navbar) {
        window.addEventListener('scroll', function () {
            const currentY = window.scrollY || 0;
            if (currentY > 100) {
                navbar.classList.add('scrolled');
                if (currentY > lastScrollY && currentY > 100) {
                    navbar.classList.add('hidden');
                } else {
                    navbar.classList.remove('hidden');
                }
            } else {
                navbar.classList.remove('scrolled');
                navbar.classList.remove('hidden');
            }
            lastScrollY = currentY;
        }, { passive: true });
    }

    /* ---------- DATE MIN ---------- */
    const departureDateInput = document.querySelector('input[name="departure_date"]');
    if (departureDateInput) {
        const today = new Date().toISOString().split('T')[0];
        departureDateInput.setAttribute('min', today);
    }

    /* ---------- SEARCH FORM VALIDATION ---------- */
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const departureOutlet = document.getElementById('departure-outlet');
            const destinationOutlet = document.getElementById('destination-outlet');

            if (departureOutlet && destinationOutlet && departureOutlet.value === destinationOutlet.value) {
                e.preventDefault();
                alert('Outlet keberangkatan dan tujuan tidak boleh sama!');
                return false;
            }
        });
    }

    /* ---------- PROFILE DROPDOWN ---------- */
    const dropdownButton = document.getElementById('profile-dropdown');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (dropdownButton && dropdownMenu) {
        // Ensure button is keyboard focusable
        dropdownButton.setAttribute('aria-haspopup', 'true');
        dropdownButton.setAttribute('aria-expanded', 'false');

        // Toggle on click
        dropdownButton.addEventListener('click', function (e) {
            e.stopPropagation();
            const isShown = dropdownMenu.classList.toggle('show');
            dropdownButton.setAttribute('aria-expanded', isShown ? 'true' : 'false');
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Close when any link/button inside clicked
        dropdownMenu.addEventListener('click', function (e) {
            const tag = e.target.tagName;
            if (tag === 'A' || tag === 'BUTTON') {
                dropdownMenu.classList.remove('show');
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
                dropdownButton.setAttribute('aria-expanded', 'false');
                dropdownButton.focus();
            }
        });
    } else {
        console.log('Dropdown elements not present or not rendered for this user.');
    }


    const successMsg = @json(session('success'));
    const errorMsg = @json(session('error'));

    if (successMsg) {
        try { alert(successMsg); } catch (err) { console.log('Alert success failed', err, successMsg); }
    }
    if (errorMsg) {
        try { alert(errorMsg); } catch (err) { console.log('Alert error failed', err, errorMsg); }
    }
});

// Initialize Select2
$(document).ready(function() {
    $('#departure-outlet').select2({
        placeholder: "Pilih Outlet Keberangkatan",
        allowClear: true,
        width: '100%'
    });

    $('#destination-outlet').select2({
        placeholder: "Pilih Outlet Tujuan",
        allowClear: true,
        width: '100%'
    });
});
</script>

</body>
</html>
