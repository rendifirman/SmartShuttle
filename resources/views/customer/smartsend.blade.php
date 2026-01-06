<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSend - Kirim Paket</title>
    <!-- Font Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #123352;
            --secondary-color: #FF581E;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --modal-bg: rgba(74, 66, 62, 0.50); /* 95% opacity */
        }

        /* FIX: Reset margin dan padding untuk body - TAMBAHKAN overflow-x: hidden */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden; /* FIX: Mencegah scroll horizontal */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: white;
            width: 100%;
        }

        .content-wrapper {
            flex: 1;
            background-color: white;
            width: 100%;
            overflow-x: hidden; /* FIX: Tambahkan ini */
        }

        /* ========== NAVBAR MOBILE FIX ========== */
        .custom-navbar {
            background: transparent;
            padding: 15px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            min-height: 70px;
            transform: translateY(0);
            box-shadow: none;
            width: 100%;
            max-width: 100vw; /* FIX: Pastikan tidak melebihi viewport */
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
            position: relative;
        }

        /* Panel Oval untuk Navbar - TRANSPARAN DENGAN BLUR */
        .nav-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50px;
            padding: 8px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            overflow: hidden; /* FIX: Hindari overflow */
        }

        .nav-brand img {
            height: 30px;
            width: auto;
            max-width: 100%;
        }

        /* MOBILE MENU TOGGLE - DIUBAH MENJADI PANAH KE BAWAH */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--primary-color);
            cursor: pointer;
            padding: 8px 10px;
            z-index: 1001;
            transition: all 0.3s ease;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            position: absolute;
            bottom: -22px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .mobile-menu-toggle:hover {
            background-color: rgba(18, 51, 82, 0.1);
        }

        .mobile-menu-toggle.active i {
            transform: rotate(180deg);
        }

        .mobile-menu-toggle i {
            transition: transform 0.3s ease;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            flex: 1;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 0.85rem;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
            padding: 5px 8px;
            font-family: 'Roboto', sans-serif;
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
            bottom: -2px;
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
            align-items: center;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-family: 'Roboto', sans-serif;
            font-size: 0.85rem;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        /* Navbar saat di-scroll */
        .custom-navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .custom-navbar.scrolled .mobile-menu-toggle {
            background: rgba(255, 255, 255, 0.95);
        }

        /* ========== HERO SECTION MOBILE FIX ========== */
        .hero-section {
            position: relative;
            height: 100vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 6%;
            margin-bottom: 30px;
            width: 100%;
            overflow: hidden; /* FIX: Hindari overflow */
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 50%;
            color: white;
            width: 100%;
        }

        .hero-title {
            font-size: 54px;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .hero-desc {
            font-size: 18px;
            line-height: 1.7;
            max-width: 520px;
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
        }

        .hero-services {
            display: flex;
            text-decoration: none;
            justify-content: flex-start;
            gap: 8px;
            margin-top: 35px;
            max-width: 400px;
            width: 100%;
        }

        .hero-service {
            text-decoration: none;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            backdrop-filter: blur(6px);
            transition: background 0.3s, transform 0.3s, border 0.3s;
            flex: 1;
            min-width: 110px;
            border: 2px solid transparent;
            font-family: 'Roboto', sans-serif;
        }

        .hero-service:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .hero-service.active {
            background: rgba(255, 255, 255, 0.3) !important;
            border: 2px solid var(--secondary-color) !important;
            transform: scale(1.05);
        }

        .hero-service i {
            font-size: 32px;
            color: #fff;
        }

        .hero-service span {
            color: #fff;
            font-weight: 600;
            font-family: 'Roboto', sans-serif;
        }

        /* ========== SEARCH SECTION MOBILE FIX ========== */
        .search-section {
            position: relative;
            z-index: 20;
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: -138px;
            background: transparent;
            padding: 0 20px; /* FIX: Tambahkan padding */
            box-sizing: border-box;
        }

        .search-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.25);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.18);
            overflow: hidden; /* FIX: Hindari overflow */
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: center;
            width: 100%;
        }

        /* FIELD */
        .search-field {
            width: 100%;
            position: relative;
            height: auto;
            min-height: fit-content;
        }

        /* Tombol dengan layout vertikal (teks rata kiri, tanpa ikon) */
        .search-btn.vertical-btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            padding: 15px 20px;
            height: auto !important;
            min-height: fit-content;
            text-align: left;
            width: 100%;
            font-family: 'Roboto', sans-serif;
            border-radius: 12px;
            background: white;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }

        /* Container untuk teks (tanpa container ikon) */
        .btn-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            width: 100%;
        }

        /* Teks utama tombol */
        .btn-main-text {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
            color: inherit;
            text-align: left;
            width: 100%;
            font-family: 'Roboto', sans-serif;
        }

        /* Label di bawah teks utama */
        .search-btn.vertical-btn .btn-label {
            font-size: 12px;
            line-height: 1.4;
            margin-top: 0;
            color: inherit;
            font-weight: 500;
            max-width: 100%;
            text-align: left;
            font-family: 'Roboto', sans-serif;
        }

        /* Untuk tombol Cek Paket (default state) */
        #btn-cek-paket .btn-main-text,
        #btn-cek-paket .btn-label {
            color: var(--secondary-color);
        }

        /* Untuk tombol Kirim Paket (default state) */
        #btn-kirim-paket .btn-main-text,
        #btn-kirim-paket .btn-label {
            color: var(--secondary-color);
        }

        /* Hover state untuk kedua tombol */
        #btn-cek-paket:hover .btn-main-text,
        #btn-cek-paket:hover .btn-label,
        #btn-kirim-paket:hover .btn-main-text,
        #btn-kirim-paket:hover .btn-label {
            color: white !important;
        }

        .search-btn.vertical-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Cek Paket - DISATUKAN DENGAN TOMBOL */
        .modal-cek-paket {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--modal-bg);
            border-radius: 12px;
            padding: 0;
            z-index: 10;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            box-sizing: border-box;
            overflow: hidden;
            min-height: fit-content;
        }

        .modal-cek-paket.show {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            height: auto;
        }

        /* Header Modal - SAMA SEPERTI TOMBOL CEK PAKET */
        .modal-header {
            background: var(--modal-bg);
            padding: 25px 30px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
            backdrop-filter: blur(5px);
            flex-shrink: 0;
        }

        /* Teks utama modal dengan warna secondary */
        .modal-main-text {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--secondary-color);
            text-align: left;
            width: 100%;
            font-family: 'Roboto', sans-serif;
        }

        /* Label di bawah teks utama */
        .modal-label {
            font-size: 14px;
            line-height: 1.4;
            margin-top: 0;
            color: white;
            font-weight: 500;
            max-width: 100%;
            text-align: left;
            font-family: 'Roboto', sans-serif;
        }

        /* Garis pemisah */
        .modal-divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 0;
            border: none;
        }

        /* Container untuk form input */
        .modal-body {
            width: 100%;
            padding: 30px;
            box-sizing: border-box;
            background: var(--modal-bg);
            height: auto;
            min-height: fit-content;
            flex-shrink: 0;
        }

        /* Form Cek Resi - layout horizontal */
        .resi-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            height: auto;
        }

        /* Container untuk input dan tombol - DIUBAH MENJADI FLEX ROW */
        .resi-input-group {
            display: flex;
            flex-direction: row;
            gap: 15px;
            width: 100%;
            align-items: center;
            height: auto;
            min-height: fit-content;
        }

        /* Input field - DIUBAH MENJADI FLEXIBLE */
        .form-control {
            flex: 1;
            padding: 14px 18px;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.3);
            background: white;
            color: #333;
            font-size: 15px;
            box-sizing: border-box;
            text-align: left;
            transition: all 0.3s ease;
            min-width: 0;
            font-family: 'Roboto', sans-serif;
            width: 100%;
        }

        .form-control::placeholder {
            color: rgba(87, 65, 65, 0.7);
            text-align: left;
            font-family: 'Roboto', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.2);
        }

        /* Tombol CEK - DIUBAH WIDTH MENJADI AUTO */
        .btn-cek-resi {
            width: auto;
            padding: 15px 30px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            white-space: nowrap;
            flex-shrink: 0;
            height: 48px;
            font-family: 'Roboto', sans-serif;
        }

        .btn-cek-resi:hover {
            background: #E54E1A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Tombol close modal */
        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            z-index: 20;
            font-family: 'Roboto', sans-serif;
        }

        .close-modal:hover {
            background: rgba(255,255,255,0.2);
            color: var(--secondary-color);
            transform: rotate(90deg);
        }

        /* Modal Kirim Paket */
        .modal-kirim-paket {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--modal-bg);
            border-radius: 12px;
            padding: 0;
            z-index: 10;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            box-sizing: border-box;
            overflow: hidden;
            min-height: fit-content;
        }

        .modal-kirim-paket.show {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            height: auto;
        }

        /* Form Kirim Paket - lebih kompleks */
        .kirim-paket-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            height: auto;
        }

        /* Form group untuk setiap baris - DIUBAH: label dan input dalam satu baris */
        .form-group {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 15px;
            width: 100%;
            flex-wrap: nowrap;
        }

        /* Label untuk form - DIUBAH: lebar tetap */
        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: white;
            min-width: 120px;
            white-space: nowrap;
            flex-shrink: 0;
            font-family: 'Roboto', sans-serif;
        }

        /* Input container - DIUBAH: mengambil sisa space */
        .form-input-container {
            flex: 1;
            min-width: 0;
            width: 100%;
        }

        /* Select2 untuk modal */
        .select2-modal {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single.select2-modal {
            height: 48px !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            border-radius: 8px !important;
            background: white !important;
            font-family: 'Roboto', sans-serif !important;
        }

        .select2-container--default .select2-selection--single.select2-modal .select2-selection__rendered {
            line-height: 48px !important;
            color: #333 !important;
            font-size: 15px !important;
            padding-left: 18px !important;
            font-family: 'Roboto', sans-serif !important;
        }

        /* Input dengan suffix (kg, cm) - DIUBAH: lebih kompak */
        .input-with-suffix {
            display: flex;
            align-items: center;
            width: 100%;
            position: relative;
        }

        .input-with-suffix .form-control {
            padding-right: 50px;
            width: 100%;
        }

        .input-suffix {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color:  #666 !important;
            font-size: 14px !important;
            padding: 5px !important;
            border-radius: 3px !important;
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 100 !important;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
        }

        /* Volume container - 3 input dalam satu baris - DIUBAH: lebih kompak */
        .volume-container {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .volume-input {
            flex: 1;
            position: relative;
            min-width: 0;
        }

        .volume-input .form-control {
            padding-right: 40px;
            width: 100%;
        }

        .volume-suffix {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 14px;
            font-weight: 500;
            pointer-events: none;
            font-family: 'Roboto', sans-serif;
        }

        /* Tombol Cek Harga di tengah - UKURAN REFERENSI */
        .btn-cek-harga {
            width: 100%;
            padding: 15px 30px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            white-space: nowrap;
            flex-shrink: 0;
            height: 48px;
            margin-top: 20px;
            font-family: 'Roboto', sans-serif;
        }

        .btn-cek-harga:hover {
            background: #E54E1A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Animasi untuk hasil perhitungan */
        #hasil-perhitungan {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        #hasil-perhitungan.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Style untuk hasil perhitungan yang lebih sederhana */
        .total-harga-container {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 14px 20px;
            margin: 15px 0;
            border: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            min-height: 51px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .total-harga-label {
            font-size: 12px;
            color: #ccc;
            margin-bottom: 4px;
            display: block;
            line-height: 1.2;
            font-family: 'Roboto', sans-serif;
        }

        .total-harga-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 4px;
            display: block;
            line-height: 1.2;
            font-family: 'Roboto', sans-serif;
        }

        .total-harga-desc {
            font-size: 10px;
            color: #aaa;
            margin-top: 4px;
            display: block;
            line-height: 1.2;
            font-family: 'Roboto', sans-serif;
        }

        .success-icon {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 6px;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
            margin: 50px 0;
            opacity: 0.6;
            width: 100%;
        }

        /* ========== ARTIKEL SECTION MOBILE FIX ========== */
        .articles-section {
            padding: 80px 40px;
            background: #f8f9fa;
            text-align: center;
            margin-bottom: 50px;
            width: 100%;
            overflow: hidden; /* FIX: Hindari overflow */
            box-sizing: border-box;
        }

        .articles-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .articles-subtitle {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
            max-width: 780px;
            margin: 0 auto 50px;
            font-family: 'Roboto', sans-serif;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .article-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: left;
            height: 100%;
            border: 1px solid #e0e0e0;
            width: 100%;
        }

        .article-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary-color);
        }

        .article-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #f0f0f0;
        }

        .article-content {
            padding: 25px;
        }

        .article-category {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            font-family: 'Roboto', sans-serif;
        }

        .article-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            line-height: 1.4;
            font-family: 'Roboto', sans-serif;
        }

        .article-excerpt {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-family: 'Roboto', sans-serif;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
            font-family: 'Roboto', sans-serif;
        }

        .article-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .article-read-more {
            color: var(--secondary-color);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
            font-family: 'Roboto', sans-serif;
            cursor: pointer;
            font-size: 12px;
            white-space: nowrap;
        }

        .article-read-more:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .view-all-articles {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 30px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
        }

        .view-all-articles:hover {
            background: #E54E1A;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 88, 30, 0.3);
            text-decoration: none;
            color: white;
        }

        /* ========== FOOTER MOBILE FIX ========== */
        .site-footer {
            background: #123352;
            color: #123352;
            padding: 50px 40px 20px;
            margin-top: auto;
            border-top: 2px solid #123352;
            width: 100%;
            overflow: hidden; /* FIX: Hindari overflow */
            box-sizing: border-box;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
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
            color: var(--secondary-color);
            font-family: 'Roboto', sans-serif;
        }

        .footer-subtitle {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--secondary-color);
            font-family: 'Roboto', sans-serif;
        }

        .footer-text {
            font-size: 14px;
            color: #ffffffff;
            line-height: 1.6;
            margin-bottom: 15px;
            font-family: 'Roboto', sans-serif;
        }

        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-line {
            font-size: 14px;
            color: #ffffffff;
            line-height: 1.4;
            font-family: 'Roboto', sans-serif;
        }

        .address {
            font-size: 13px;
            line-height: 1.5;
            font-family: 'Roboto', sans-serif;
        }

        .social-buttons {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }

        .social-button {
            width: 32px;
            height: 32px;
            background: var(--secondary-color);
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

        .footer-bottom {
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
            width: 100%;
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
            color: #ffffffff;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .footer-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .footer-link {
            font-size: 14px;
            color: #ffffffff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }

        .footer-link:hover {
            color: var(--secondary-color);
        }

        /* ========== PROFILE DROPDOWN ========== */
        .profile-wrapper {
            position: relative;
            display: inline-block;
            z-index: 100;
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
            z-index: 101;
            position: relative;
            font-family: 'Roboto', sans-serif;
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
            font-family: 'Roboto', sans-serif;
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
            font-family: 'Roboto', sans-serif;
        }

        .dropdown-menu {
            z-index: 1000;
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 170px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 10px 0;
            border: 1px solid #e0e0e0;
            animation: fadeIn 0.2s ease-out;
            font-family: 'Roboto', sans-serif;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 16px;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 0;
            margin: 0;
            transition: background-color 0.2s;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
        }

        .dropdown-menu a:hover {
            background-color: rgba(255, 88, 30, 0.1);
            color: var(--secondary-color);
        }

        .dropdown-menu form {
            margin: 0;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .dropdown-menu button[type="submit"] {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 16px;
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .dropdown-menu button[type="submit"]:hover {
            background-color: rgba(255, 88, 30, 0.1);
            color: var(--secondary-color);
        }

        .dropdown-menu.show {
            display: block;
        }

        .nav-auth a.btn-login {
            pointer-events: auto !important;
            position: relative;
            z-index: 10;
        }

        /* ========== RESPONSIVE STYLES ========== */
        /* Tablet (1024px and below) */
        @media (max-width: 1024px) {
            .nav-panel {
                padding: 8px 20px;
            }

            .nav-links {
                gap: 12px;
            }

            .nav-links a {
                font-size: 0.8rem;
                padding: 4px 6px;
            }

            .hero-title {
                font-size: 42px;
            }

            .hero-desc {
                font-size: 16px;
            }

            .search-row {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .search-btn.vertical-btn {
                padding: 12px 15px;
            }

            .btn-main-text {
                font-size: 14px;
            }

            .search-btn.vertical-btn .btn-label {
                font-size: 11px;
            }

            /* Articles Responsive */
            .articles-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
            }

            .articles-section {
                padding: 60px 30px;
            }

            .article-image {
                height: 180px;
            }

            .article-content {
                padding: 20px;
            }

            .article-title {
                font-size: 16px;
            }

            .article-excerpt {
                font-size: 13px;
            }
        }

        /* Mobile (768px and below) */
        @media (max-width: 768px) {
            /* NAVBAR MOBILE - PANAH KE BAWAH DI TENGAH */
            .custom-navbar {
                padding: 12px 4% 30px;
                min-height: 60px;
                width: 100vw;
                max-width: 100vw;
                left: 0;
                right: 0;
            }

            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                max-width: 100%;
            }

            .nav-panel {
                position: relative;
                padding: 10px 15px;
                border-radius: 25px;
                flex-wrap: nowrap;
                overflow: visible;
                width: 100%;
            }

            /* TOMBOL PANAH KE BAWAH DI TENGAH BAWAH NAVBAR */
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .mobile-menu-toggle i {
                font-size: 18px;
                color: var(--primary-color);
            }

            .nav-brand {
                order: 1;
                flex-shrink: 0;
            }

            .nav-brand img {
                height: 28px;
            }

            .nav-auth {
                order: 3;
                flex-shrink: 0;
            }

            /* MENU DROPDOWN DI BAWAH TOMBOL PANAH */
            .nav-menu {
                position: absolute;
                top: calc(100% + 35px);
                left: 0;
                right: 0;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                padding: 15px;
                display: none;
                z-index: 999;
                overflow: hidden;
                border: 1px solid #e0e0e0;
                max-height: none;
                overflow: visible;
                width: calc(100% - 30px);
                margin: 0 15px;
                box-sizing: border-box;
            }

            .nav-menu.active {
                display: block;
                animation: slideDown 0.3s ease;
            }

            @keyframes slideDown {
                from { transform: translateY(-10px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            /* NAV-LINKS MENJADI HORIZONTAL SCROLL SEPERTI SEBELUMNYA */
            .nav-links {
                flex-direction: row;
                gap: 8px;
                align-items: center;
                width: 100%;
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                padding-bottom: 5px;
                justify-content: flex-start;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none; /* Firefox */
                max-width: 100%;
            }

            .nav-links::-webkit-scrollbar {
                display: none; /* Chrome, Safari, Opera */
            }

            .nav-links a {
                font-size: 0.8rem;
                padding: 8px 12px;
                white-space: nowrap;
                flex-shrink: 0;
                border-bottom: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .nav-links a:hover,
            .nav-links a.active {
                border-bottom: 2px solid var(--secondary-color);
                background: rgba(255, 88, 30, 0.05);
                border-radius: 6px;
            }

            .nav-links a::after {
                display: none; /* Hilangkan underline animasi di mobile */
            }

            .profile-name {
                display: none;
            }

            /* HERO MOBILE */
            .hero-section {
                height: auto;
                min-height: 90vh;
                padding: 120px 20px 60px;
                background-position: center center;
                width: 100%;
                max-width: 100vw;
                overflow: hidden;
            }

            .hero-content {
                max-width: 100%;
                text-align: center;
                width: 100%;
                padding: 0 10px;
            }

            .hero-title {
                font-size: 32px;
                margin-bottom: 15px;
                line-height: 1.2;
                word-wrap: break-word;
            }

            .hero-desc {
                font-size: 15px;
                margin: 0 auto 20px;
                max-width: 100%;
                padding: 0 10px;
                box-sizing: border-box;
            }

            .hero-services {
                flex-direction: column;
                max-width: 100%;
                gap: 12px;
                padding: 0 10px;
                width: 100%;
            }

            .hero-service {
                width: 100%;
                max-width: 280px;
                margin: 0 auto;
                padding: 15px;
                flex-direction: row;
                justify-content: center;
                gap: 15px;
                box-sizing: border-box;
            }

            .hero-service i {
                font-size: 28px;
            }

            .hero-service span {
                font-size: 15px;
            }

            /* SEARCH MOBILE */
            .search-section {
                margin-top: -120px;
                padding: 0 15px;
                width: 100%;
                box-sizing: border-box;
            }

            .search-container {
                padding: 20px;
                border-radius: 12px;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .search-row {
                grid-template-columns: 1fr;
                gap: 15px;
                width: 100%;
            }

            .search-btn.vertical-btn {
                padding: 15px;
                width: 100%;
                box-sizing: border-box;
            }

            .btn-main-text {
                font-size: 15px;
            }

            .search-btn.vertical-btn .btn-label {
                font-size: 12px;
            }

            /* MODAL MOBILE */
            .modal-cek-paket,
            .modal-kirim-paket {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 500px;
                max-height: 80vh;
                overflow-y: auto;
                box-sizing: border-box;
            }

            .modal-header {
                padding: 20px;
            }

            .modal-body {
                padding: 20px;
            }

            .resi-input-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn-cek-resi {
                width: 100%;
                box-sizing: border-box;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                width: 100%;
            }

            .form-label {
                min-width: 100%;
                margin-bottom: 5px;
            }

            .volume-container {
                flex-direction: column;
                gap: 10px;
            }

            /* Articles Responsive */
            .articles-section {
                padding: 50px 20px;
                width: 100%;
                max-width: 100vw;
                box-sizing: border-box;
            }

            .articles-title {
                font-size: 22px;
                padding: 0 10px;
            }

            .articles-subtitle {
                font-size: 13px;
                margin-bottom: 30px;
                padding: 0 15px;
                box-sizing: border-box;
            }

            .articles-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                width: 100%;
            }

            .article-image {
                height: 180px;
            }

            .article-content {
                padding: 20px;
            }

            .article-title {
                font-size: 17px;
            }

            .article-excerpt {
                font-size: 13px;
            }

            .article-meta {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .view-all-articles {
                padding: 10px 25px;
                font-size: 13px;
            }

            /* FOOTER MOBILE */
            .site-footer {
                padding: 40px 20px 20px;
                width: 100%;
                max-width: 100vw;
            }

            .footer-main {
                flex-direction: column;
                gap: 30px;
                margin-bottom: 30px;
                width: 100%;
            }

            .footer-column {
                width: 100%;
                text-align: center;
                padding: 0 10px;
                box-sizing: border-box;
            }

            .social-buttons {
                justify-content: center;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                width: 100%;
            }

            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Small Mobile (480px and below) */
        @media (max-width: 480px) {
            .custom-navbar {
                padding: 8px 3% 25px;
                width: 100vw;
            }

            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                padding: 6px 8px;
                bottom: -20px;
            }

            .mobile-menu-toggle i {
                font-size: 16px;
            }

            .nav-brand img {
                height: 25px;
            }

            .nav-links {
                gap: 6px;
            }

            .nav-links a {
                font-size: 0.75rem;
                padding: 6px 10px;
            }

            .hero-title {
                font-size: 28px;
                padding: 0 5px;
            }

            .hero-desc {
                font-size: 14px;
                padding: 0 5px;
            }

            .hero-service {
                padding: 12px;
                max-width: 100%;
            }

            .hero-service i {
                font-size: 24px;
            }

            .hero-service span {
                font-size: 13px;
            }

            .search-section {
                margin-top: -110px;
                padding: 0 10px;
            }

            .search-container {
                padding: 15px;
            }

            .search-btn.vertical-btn {
                padding: 12px;
            }

            .btn-main-text {
                font-size: 14px;
            }

            .search-btn.vertical-btn .btn-label {
                font-size: 11px;
            }

            .btn-login {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .profile-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            /* Articles Responsive */
            .articles-title {
                font-size: 20px;
            }

            .article-image {
                height: 160px;
            }

            .article-content {
                padding: 15px;
            }

            .article-category {
                font-size: 11px;
                padding: 3px 10px;
            }

            .article-title {
                font-size: 15px;
            }

            .article-excerpt {
                font-size: 12px;
            }

            /* Modal lebih kecil */
            .modal-cek-paket,
            .modal-kirim-paket {
                width: 95%;
                max-height: 85vh;
                left: 50%;
                transform: translateX(-50%);
                margin: 0;
            }

            .modal-header {
                padding: 15px;
            }

            .modal-main-text {
                font-size: 16px;
            }

            .modal-label {
                font-size: 12px;
            }

            .modal-body {
                padding: 15px;
            }

            .form-control {
                padding: 12px 15px;
                font-size: 14px;
            }

            .btn-cek-resi,
            .btn-cek-harga {
                padding: 12px 20px;
                font-size: 14px;
                height: 44px;
            }
        }

        /* Landscape Mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .hero-section {
                min-height: 120vh;
                padding: 100px 20px 40px;
            }

            .hero-content {
                padding-top: 40px;
            }

            .hero-title {
                font-size: 28px;
                margin-bottom: 10px;
            }

            .hero-desc {
                font-size: 14px;
                margin-bottom: 15px;
            }

            .hero-services {
                margin-top: 20px;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .hero-service {
                min-width: 100px;
                padding: 10px;
            }

            .nav-menu {
                max-height: 200px;
            }

            /* Modal lebih pendek di landscape */
            .modal-cek-paket,
            .modal-kirim-paket {
                max-height: 70vh;
            }
        }

        /* Fix untuk iOS Safari */
        @supports (-webkit-touch-callout: none) {
            .hero-section {
                height: -webkit-fill-available;
                min-height: -webkit-fill-available;
            }

            .modal-cek-paket,
            .modal-kirim-paket {
                max-height: -webkit-fill-available;
            }
        }

        /* FIX TAMBAHAN UNTUK MENGATASI OVERFLOW */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        img, video, iframe {
            max-width: 100%;
            height: auto;
        }
    </style>
    @php
        use App\Models\MProfilePerusahaan;
        $profile = MProfilePerusahaan::first();

        // Data user dari session
        $user = session()->get('user', null);

        // Data artikel/berita
        $articles = collect([
            [
                'id' => 1,
                'title' => 'Tips Perjalanan Aman dengan Shuttle Selama Liburan',
                'excerpt' => 'Pelajari cara mempersiapkan perjalanan shuttle yang aman dan nyaman selama musim liburan untuk pengalaman terbaik.',
                'full_content' => '<h3>Persiapan Sebelum Perjalanan</h3>
                <p>Perjalanan dengan shuttle selama liburan memerlukan persiapan yang matang. Pastikan Anda memesan tiket jauh-jauh hari untuk mendapatkan harga terbaik dan kursi pilihan. Smart Shuttle menawarkan pemesanan online yang mudah melalui website atau aplikasi kami.</p>

                <h3>Packing yang Tepat</h3>
                <p>Bawalah barang secukupnya sesuai durasi perjalanan. Gunakan tas yang mudah disimpan di bagasi shuttle. Jangan lupa membawa charger ponsel, makanan ringan, dan baju hangat karena AC shuttle biasanya cukup dingin.</p>

                <h3>Keamanan Selama Perjalanan</h3>
                <p>Selalu jaga barang berharga Anda di dekat tempat duduk. Smart Shuttle menyediakan bagasi tertutup yang aman, namun tetap disarankan untuk tidak membawa barang berharga berlebihan.</p>

                <h3>Manfaatkan Fitur Tracking</h3>
                <p>Gunakan fitur tracking real-time dari Smart Shuttle untuk memantau perjalanan Anda. Fitur ini membantu Anda memperkirakan waktu kedatangan dan mempersiapkan diri sebelum sampai di tujuan.</p>',
                'category' => 'Tips & Trik',
                'image' => asset('images/articles/cobacoba.jpg'),
                'date' => '15 Maret 2024',
                'read_time' => '3 min read',
                'tags' => ['Perjalanan', 'Tips', 'Liburan', 'Shuttle']
            ],
            [
                'id' => 2,
                'title' => 'Cara Kirim Paket dengan SmartSend yang Aman',
                'excerpt' => 'Panduan lengkap mengirim paket dengan aman dan mudah menggunakan layanan SmartSend.',
                'full_content' => '<h3>Persiapan Paket</h3>
                <p>Pastikan paket Anda dalam kondisi baik sebelum dikirim. Gunakan kemasan yang kuat dan tahan air untuk melindungi isi paket. Jangan lupa menulis alamat dengan jelas dan lengkap.</p>

                <h3>Proses Pengiriman</h3>
                <p>Datang ke outlet SmartShuttle terdekat atau gunakan layanan pickup dari SmartSend. Tim kami akan membantu menimbang dan mengukur paket Anda untuk menentukan biaya pengiriman.</p>

                <h3>Pelacakan Paket</h3>
                <p>Dapatkan nomor resi untuk melacak perjalanan paket Anda secara real-time. Anda dapat memantau lokasi paket kapan saja melalui website atau aplikasi SmartShuttle.</p>',
                'category' => 'SmartSend',
                'image' => asset('images/articles/paket.jpg'),
                'date' => '10 Maret 2024',
                'read_time' => '4 min read',
                'tags' => ['Pengiriman', 'Paket', 'SmartSend']
            ],
            [
                'id' => 3,
                'title' => 'Keunggulan Layanan SmartShuttle untuk Bisnis',
                'excerpt' => 'Mengapa SmartShuttle menjadi pilihan terbaik untuk kebutuhan transportasi bisnis Anda.',
                'full_content' => '<h3>Fleksibilitas Jadwal</h3>
                <p>SmartShuttle menawarkan jadwal yang fleksibel untuk mendukung mobilitas bisnis Anda. Dengan frekuensi keberangkatan yang tinggi, Anda dapat merencanakan perjalanan dengan lebih mudah.</p>

                <h3>Kenyamanan dan Keamanan</h3>
                <p>Armada terbaru dengan fasilitas lengkap seperti WiFi, charging port, dan kursi yang ergonomis. Semua driver kami telah melalui pelatihan khusus untuk menjamin keselamatan penumpang.</p>

                <h3>Layanan Korporat</h3>
                <p>SmartShuttle menyediakan layanan khusus untuk perusahaan dengan fasilitas seperti pembayaran terpusat, laporan keuangan, dan manajemen perjalanan yang terintegrasi.</p>',
                'category' => 'Bisnis',
                'image' => asset('images/articles/bisnis.jpg'),
                'date' => '5 Maret 2024',
                'read_time' => '5 min read',
                'tags' => ['Bisnis', 'Korporat', 'Transportasi']
            ],
        ]);

        // Untuk halaman kirim paket, service default adalah 'kirim-paket'
        $activeService = 'kirim-paket';
    @endphp
</head>
<body>
    <!-- Custom Navbar TRANSPARAN -->
    <nav class="custom-navbar" id="navbar">
        <div class="nav-container">
            <!-- TOMBOL PANAH KE BAWAH DI TENGAH BAWAH NAVBAR -->
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="fas fa-chevron-down"></i>
            </button>

            <div class="nav-panel">
                <div class="nav-brand">
                    <img src="{{ asset($profile->logo_perusahaan ?? '/images/smartshuttlelogo.png') }}" alt="{{ $profile->nama_dagang ?? 'Smart Shuttle' }}">
                </div>

                <!-- MENU NAVIGASI YANG AKAN DITAMPILKAN DI BAWAH TOMBOL PANAH -->
                <div class="nav-menu" id="nav-menu">
                    <ul class="nav-links">
                        <li><a href="/customer/beranda">Beranda</a></li>
                        <li><a href="{{ route('customer.search') }}">Cari Tiket</a></li>
                        <li><a href="{{ route('customer.outlet') }}">Outlet</a></li>
                        <li><a href="{{ route('customer.smartsend') }}" class="active" id="nav-kirim-paket">Kirim Paket</a></li>
                        <li><a href="#" onclick="alert('Fitur Sewa Armada akan segera hadir!'); return false;">Sewa Armada</a></li>
                        <li><a href="{{ route('customer.contact') }}">Kontak</a></li>
                        <li><a href="{{ route('customer.cek-reservasi') }}">Cek Reservasi</a></li>
                    </ul>
                </div>

                <div class="nav-auth">
                    @if($user && isset($user['id']) && $user['id'])
                        <div class="profile-wrapper">
                            <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                                @if(!empty($user['avatar']))
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
                                <a href="{{ route('customer.riwayat') }}">Riwayat</a>
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn-login" id="login-btn">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section dengan Background Image -->
    <div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
        <div class="hero-content">
            <h1 class="hero-title">SmartSend</h1>
            <p class="hero-desc">
                Setiap kiriman punya tujuan — Paket terkirim cepat, aman, dan terpantau.
            </p>
            <div class="hero-services">
                <a href="/customer/beranda" class="hero-service" id="shuttle-link">
                    <i class="fas fa-shuttle-van"></i>
                    <span>Tiket Shuttle</span>
                </a>
                <a href="{{ route('customer.smartsend') }}" class="hero-service active" id="kirim-paket-link">
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

    <!-- Search Section -->
    <div class="search-section">
        <div class="search-container">
            <!-- Form Kirim Paket -->
            <div class="search-row" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Tombol Cek Paket -->
                <div class="search-field">
                    <button type="button" class="search-btn vertical-btn" id="btn-cek-paket">
                        <div class="btn-text">
                            <div class="btn-main-text">CEK PAKET</div>
                            <div class="btn-label">
                                Cek status paket yang sudah anda kirim kan
                            </div>
                        </div>
                    </button>
                    <!-- Modal Cek Paket - DISATUKAN DENGAN TOMBOL -->
                    <div class="modal-cek-paket" id="modal-cek-paket">
                        <button type="button" class="close-modal" id="close-modal-cek-paket">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Header - SAMA SEPERTI TOMBOL CEK PAKET -->
                        <div class="modal-header">
                            <div class="modal-main-text">CEK PAKET</div>
                            <div class="modal-label">Cek status paket yang sudah anda kirim kan</div>
                        </div>

                        <!-- Garis pemisah -->
                        <hr class="modal-divider">

                        <!-- Body dengan form input -->
                        <div class="modal-body">
                            <div class="resi-form">
                                <!-- Input dan tombol CEK dalam satu baris -->
                                <div class="resi-input-group">
                                    <input type="text" class="form-control" id="kode-resi"
                                        placeholder="Kode Resi">
                                    <button type="button" class="btn-cek-resi" id="btn-cek-resi">
                                        <i class="fas fa-search"></i> CEK STATUS PAKET
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol Kirim Paket -->
                <div class="search-field">
                    <button type="button" class="search-btn vertical-btn" id="btn-kirim-paket">
                        <div class="btn-text">
                            <div class="btn-main-text">KIRIM PAKET</div>
                            <div class="btn-label">
                                Kirim paket ke beberapa daerah
                            </div>
                        </div>
                    </button>
                    <!-- Modal Kirim Paket - DISATUKAN DENGAN TOMBOL -->
                    <div class="modal-kirim-paket" id="modal-kirim-paket">
                        <button type="button" class="close-modal" id="close-modal-kirim-paket">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Header - SAMA SEPERTI TOMBOL KIRIM PAKET -->
                        <div class="modal-header">
                            <div class="modal-main-text">CEK HARGA PAKET</div>
                            <div class="modal-label">Cek harga pengiriman paket antar kota</div>
                        </div>

                        <!-- Garis pemisah -->
                        <hr class="modal-divider">

                        <!-- Body dengan form input -->
                        <div class="modal-body">
                            <div class="kirim-paket-form" id="form-kirim-paket">
                                <!-- Data Paket -->
                                <h4 style="color: white; margin-bottom: 15px;">Data Paket</h4>

                                <div class="form-group">
                                    <label class="form-label">Kota Asal</label>
                                    <div class="form-input-container">
                                        <select class="form-control select2-modal" id="asal-paket" name="asal_paket">
                                            <option value="">Pilih Kota Asal</option>
                                            <option value="Bandung">Bandung</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Surabaya">Surabaya</option>
                                            <option value="Yogyakarta">Yogyakarta</option>
                                            <option value="Semarang">Semarang</option>
                                            <option value="Malang">Malang</option>
                                            <option value="Bali">Bali</option>
                                            <option value="Medan">Medan</option>
                                            <option value="Palembang">Palembang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Kota Tujuan</label>
                                    <div class="form-input-container">
                                        <select class="form-control select2-modal" id="tujuan-paket" name="tujuan_paket">
                                            <option value="">Pilih Kota Tujuan</option>
                                            <option value="Bandung">Bandung</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Surabaya">Surabaya</option>
                                            <option value="Yogyakarta">Yogyakarta</option>
                                            <option value="Semarang">Semarang</option>
                                            <option value="Malang">Malang</option>
                                            <option value="Bali">Bali</option>
                                            <option value="Medan">Medan</option>
                                            <option value="Palembang">Palembang</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Berat -->
                                <div class="form-group">
                                    <label class="form-label">Berat Paket</label>
                                    <div class="form-input-container">
                                        <div class="input-with-suffix">
                                            <input type="number" class="form-control" id="berat-paket"
                                                name="berat_paket" placeholder="0.1" min="0.1" step="0.1" value="0.1" style="max-width: 150px;">
                                            <span class="input-suffix">kg</span>
                                        </div>
                                        <small style="color: #ccc; font-size: 12px; margin-top: 5px; display: block;">
                                            *Minimum 0.1 kg
                                        </small>
                                    </div>
                                </div>

                                <!-- Volume - Opsional -->
                                <div class="form-group">
                                    <label class="form-label">Dimensi Paket <span style="color: #ccc; font-weight: normal;">(Opsional)</span></label>
                                    <div class="form-input-container">
                                        <div class="volume-container">
                                            <div class="volume-input">
                                                <input type="number" class="form-control" id="panjang-paket"
                                                    name="panjang_paket" placeholder="Panjang" min="0" step="0.1">
                                                <span class="volume-suffix">cm</span>
                                            </div>
                                            <div class="volume-input">
                                                <input type="number" class="form-control" id="lebar-paket"
                                                    name="lebar_paket" placeholder="Lebar" min="0" step="0.1">
                                                <span class="volume-suffix">cm</span>
                                            </div>
                                            <div class="volume-input">
                                                <input type="number" class="form-control" id="tinggi-paket"
                                                    name="tinggi_paket" placeholder="Tinggi" min="0" step="0.1">
                                                <span class="volume-suffix">cm</span>
                                            </div>
                                        </div>
                                        <small style="color: #ccc; font-size: 12px; margin-top: 5px; display: block;">
                                            *Berat volumetric dihitung: (P × L × T) ÷ 6000
                                        </small>
                                    </div>
                                </div>

                                <!-- Tombol Cek Harga -->
                                <button type="button" class="btn-cek-harga" id="btn-cek-harga">
                                    <i class="fas fa-calculator"></i> CEK HARGA SEKARANG
                                </button>

                                <!-- HASIL PERHITUNGAN (akan muncul setelah cek harga) -->
                                <div id="hasil-perhitungan" style="display: none; margin-top: 25px;">
                                    <h4 style="color: white; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                                        <i class="fas fa-check-circle"></i> Hasil Perhitungan Harga
                                    </h4>

                                    <!-- Container Total Harga yang Sederhana DAN LEBIH KECIL -->
                                    <div class="total-harga-container">
                                        <div class="success-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <span class="total-harga-label">Total Biaya Pengiriman:</span>
                                        <span class="total-harga-value" id="harga-total">Rp 0</span>
                                        <span class="total-harga-desc">
                                            Harga sudah termasuk semua biaya pengiriman standar
                                        </span>
                                    </div>

                                    <!-- Informasi tambahan -->
                                    <div style="margin-top: 15px; padding: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 6px; border-left: 3px solid var(--secondary-color);">
                                        <p style="color: #ccc; font-size: 12px; margin: 0;">
                                            <i class="fas fa-info-circle" style="margin-right: 5px; color: var(--secondary-color);"></i>
                                            Harga sudah termasuk biaya pengiriman standar. Berat yang digunakan adalah berat terbesar antara berat aktual dan berat volumetric.
                                        </p>
                                    </div>

                                    <!-- Tombol Reset -->
                                    <button type="button" class="btn-cek-ulang" id="btn-cek-ulang"
                                            style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; margin-top: 15px;">
                                        <i class="fas fa-redo"></i> CEK HARGA LAINNYA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- === ARTIKEL/BERITA SECTION === -->
    <section class="articles-section">
        <h2 class="articles-title">Artikel & Berita Terbaru</h2>
        <p class="articles-subtitle">
            Dapatkan informasi terbaru seputar layanan transportasi, tips perjalanan, dan berita terbaru dari Smart Shuttle.
        </p>

        <div class="articles-grid">
            @foreach($articles as $index => $article)
            <div class="article-card">
                <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="article-image"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-article.jpg') }}';">
                <div class="article-content">
                    <span class="article-category">{{ $article['category'] }}</span>
                    <h3 class="article-title">{{ $article['title'] }}</h3>
                    <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                    <div class="article-meta">
                        <div class="article-date">
                            <i class="far fa-calendar-alt"></i>
                            {{ $article['date'] }}
                        </div>
                        <a href="#" class="article-read-more" data-article-id="{{ $article['id'] }}">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <a href="{{ route('customer.artikel') }}" class="view-all-articles">
            Lihat Semua Artikel <i class="fas fa-arrow-right"></i>
        </a>
    </section>

    <!-- Footer -->
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

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        /* ========== MOBILE MENU TOGGLE - PANAH KE BAWAH ========== */
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const navMenu = document.getElementById('nav-menu');

        if (mobileMenuToggle && navMenu) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                navMenu.classList.toggle('active');
                mobileMenuToggle.classList.toggle('active');

                if (navMenu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });

            // Close menu when clicking on a link
            const navLinks = navMenu.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!navMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }

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

        /* ---------- PROFILE DROPDOWN ---------- */
        const dropdownButton = document.getElementById('profile-dropdown');
        const dropdownMenu = document.getElementById('dropdown-menu');

        if (dropdownButton && dropdownMenu) {
            dropdownButton.setAttribute('aria-haspopup', 'true');
            dropdownButton.setAttribute('aria-expanded', 'false');

            dropdownButton.addEventListener('click', function (e) {
                e.stopPropagation();
                const isShown = dropdownMenu.classList.toggle('show');
                dropdownButton.setAttribute('aria-expanded', isShown ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (dropdownMenu.classList.contains('show')) {
                    if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownButton.setAttribute('aria-expanded', 'false');
                    }
                }

                // Tutup nav menu jika terbuka
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    dropdownButton.focus();
                }

                if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                }
            });

            dropdownMenu.addEventListener('click', function (e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        /* ---------- MODAL CEK PAKET ---------- */
        const btnCekPaket = document.getElementById('btn-cek-paket');
        const modalCekPaket = document.getElementById('modal-cek-paket');
        const closeModalCekPaket = document.getElementById('close-modal-cek-paket');
        const btnCekResi = document.getElementById('btn-cek-resi');

        // Show modal cek paket - menggantikan tombol
        if (btnCekPaket) {
            btnCekPaket.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Tutup modal Kirim Paket jika terbuka
                if (modalKirimPaket && modalKirimPaket.classList.contains('show')) {
                    modalKirimPaket.classList.remove('show');
                    btnKirimPaket.style.visibility = 'visible';

                    const searchFieldKirim = btnKirimPaket.closest('.search-field');
                    if (searchFieldKirim) {
                        searchFieldKirim.style.height = '';
                        searchFieldKirim.style.minHeight = '';
                    }
                }

                // Buka modal Cek Paket
                modalCekPaket.classList.toggle('show');
                btnCekPaket.style.visibility = 'hidden';

                const searchField = btnCekPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = 'auto';
                    searchField.style.minHeight = 'fit-content';
                }
            });
        }

        // Close modal when clicking close button
        if (closeModalCekPaket) {
            closeModalCekPaket.addEventListener('click', function(e) {
                e.preventDefault();
                modalCekPaket.classList.remove('show');
                btnCekPaket.style.visibility = 'visible';

                const searchField = btnCekPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = '';
                    searchField.style.minHeight = '';
                }
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (modalCekPaket.classList.contains('show')) {
                if (!modalCekPaket.contains(e.target) && !btnCekPaket.contains(e.target)) {
                    modalCekPaket.classList.remove('show');
                    btnCekPaket.style.visibility = 'visible';

                    const searchField = btnCekPaket.closest('.search-field');
                    if (searchField) {
                        searchField.style.height = '';
                        searchField.style.minHeight = '';
                    }
                }
            }
        });

        // Handle cek resi button
        if (btnCekResi) {
            btnCekResi.addEventListener('click', function(e) {
                e.preventDefault();
                const kodeResi = document.getElementById('kode-resi').value.trim();

                if (!kodeResi) {
                    alert('Silakan masukkan kode resi terlebih dahulu!');
                    return;
                }

                // Simulasi cek resi
                alert('Mencari informasi untuk resi: ' + kodeResi + '\n\n(Sistem cek paket akan ditampilkan di sini)');

                // Reset form
                document.getElementById('kode-resi').value = '';
                modalCekPaket.classList.remove('show');
                // Tampilkan kembali tombol utama
                btnCekPaket.style.visibility = 'visible';

                // Kembalikan ukuran container search
                const searchField = btnCekPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = '';
                    searchField.style.minHeight = '';
                }
            });
        }

        /* ---------- MODAL KIRIM PAKET ---------- */
        const btnKirimPaket = document.getElementById('btn-kirim-paket');
        const modalKirimPaket = document.getElementById('modal-kirim-paket');
        const closeModalKirimPaket = document.getElementById('close-modal-kirim-paket');

        // Show modal kirim paket - menggantikan tombol
        if (btnKirimPaket) {
            btnKirimPaket.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Tutup modal Cek Paket jika terbuka
                if (modalCekPaket && modalCekPaket.classList.contains('show')) {
                    modalCekPaket.classList.remove('show');
                    btnCekPaket.style.visibility = 'visible';

                    const searchFieldCek = btnCekPaket.closest('.search-field');
                    if (searchFieldCek) {
                        searchFieldCek.style.height = '';
                        searchFieldCek.style.minHeight = '';
                    }
                }

                // Buka modal Kirim Paket
                modalKirimPaket.classList.toggle('show');
                btnKirimPaket.style.visibility = 'hidden';

                const searchField = btnKirimPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = 'auto';
                    searchField.style.minHeight = 'fit-content';
                }

                // Initialize Select2 untuk modal
                setTimeout(() => {
                    $('#asal-paket').select2({
                        placeholder: "Pilih Kota Asal",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: modalKirimPaket
                    });
                    $('#tujuan-paket').select2({
                        placeholder: "Pilih Kota Tujuan",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: modalKirimPaket
                    });
                }, 100);
            });
        }

        // Close modal when clicking close button
        if (closeModalKirimPaket) {
            closeModalKirimPaket.addEventListener('click', function(e) {
                e.preventDefault();
                modalKirimPaket.classList.remove('show');
                btnKirimPaket.style.visibility = 'visible';

                const searchField = btnKirimPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = '';
                    searchField.style.minHeight = '';
                }
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (modalKirimPaket.classList.contains('show')) {
                if (!modalKirimPaket.contains(e.target) && !btnKirimPaket.contains(e.target)) {
                    modalKirimPaket.classList.remove('show');
                    btnKirimPaket.style.visibility = 'visible';

                    const searchField = btnKirimPaket.closest('.search-field');
                    if (searchField) {
                        searchField.style.height = '';
                        searchField.style.minHeight = '';
                    }
                }
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            // Tutup modal Cek Paket jika terbuka
            if (e.key === 'Escape' && modalCekPaket.classList.contains('show')) {
                modalCekPaket.classList.remove('show');
                btnCekPaket.style.visibility = 'visible';

                const searchField = btnCekPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = '';
                    searchField.style.minHeight = '';
                }
            }

            // Tutup modal Kirim Paket jika terbuka
            if (e.key === 'Escape' && modalKirimPaket.classList.contains('show')) {
                modalKirimPaket.classList.remove('show');
                btnKirimPaket.style.visibility = 'visible';

                const searchField = btnKirimPaket.closest('.search-field');
                if (searchField) {
                    searchField.style.height = '';
                    searchField.style.minHeight = '';
                }
            }
        });

        // Session messages
        const successMsg = @json(session('success'));
        const errorMsg = @json(session('error'));

        if (successMsg) {
            alert(successMsg);
        }
        if (errorMsg) {
            alert(errorMsg);
        }

        /* ---------- CEK HARGA PAKET AJAX ---------- */
        const btnCekHarga = document.getElementById('btn-cek-harga');
        const btnCekUlang = document.getElementById('btn-cek-ulang');
        const hasilPerhitungan = document.getElementById('hasil-perhitungan');

        if (btnCekHarga) {
            btnCekHarga.addEventListener('click', function(e) {
                e.preventDefault();

                const asal = document.getElementById('asal-paket').value;
                const tujuan = document.getElementById('tujuan-paket').value;
                const berat = parseFloat(document.getElementById('berat-paket').value) || 0.1;
                const panjang = parseFloat(document.getElementById('panjang-paket').value) || 0;
                const lebar = parseFloat(document.getElementById('lebar-paket').value) || 0;
                const tinggi = parseFloat(document.getElementById('tinggi-paket').value) || 0;

                // Validasi dasar
                if (!asal || !tujuan) {
                    alert('Silakan pilih asal dan tujuan terlebih dahulu!');
                    return;
                }

                if (asal === tujuan) {
                    alert('Kota asal dan tujuan tidak boleh sama!');
                    return;
                }

                if (berat <= 0) {
                    alert('Silakan isi berat paket (minimal 0.1 kg)!');
                    return;
                }

                // Validasi dimensi jika salah satu diisi
                if ((panjang > 0 || lebar > 0 || tinggi > 0) &&
                    (panjang <= 0 || lebar <= 0 || tinggi <= 0)) {
                    alert('Jika mengisi dimensi, semua kolom panjang, lebar, dan tinggi harus diisi!');
                    return;
                }

                // Tampilkan loading
                const originalText = btnCekHarga.innerHTML;
                btnCekHarga.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghitung...';
                btnCekHarga.disabled = true;

                // Kirim AJAX request
                fetch('{{ route("customer.cek-harga-paket") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        asal: asal,
                        tujuan: tujuan,
                        berat: berat,
                        panjang: panjang,
                        lebar: lebar,
                        tinggi: tinggi
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Kembalikan tombol ke keadaan semula
                    btnCekHarga.innerHTML = originalText;
                    btnCekHarga.disabled = false;

                    if (data.success) {
                        // Tampilkan hasil perhitungan
                        hasilPerhitungan.style.display = 'block';

                        // Update hanya total harga
                        document.getElementById('harga-total').textContent = data.data.harga_total;

                        // Scroll ke hasil perhitungan
                        hasilPerhitungan.scrollIntoView({ behavior: 'smooth', block: 'start' });

                        // Tambahkan class untuk animasi
                        hasilPerhitungan.classList.add('show');

                        // RESET KOTA ASAL DAN TUJUAN SETELAH BERHASIL
                        $('#asal-paket').val('').trigger('change');
                        $('#tujuan-paket').val('').trigger('change');

                        // Reset berat ke default 0.1 kg
                        document.getElementById('berat-paket').value = '0.1';

                        // Reset dimensi
                        document.getElementById('panjang-paket').value = '';
                        document.getElementById('lebar-paket').value = '';
                        document.getElementById('tinggi-paket').value = '';

                    } else {
                        alert(data.message || 'Terjadi kesalahan saat menghitung harga.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btnCekHarga.innerHTML = originalText;
                    btnCekHarga.disabled = false;
                    alert('Terjadi kesalahan saat menghitung harga.');
                });
            });
        }

        // Tombol cek ulang
        if (btnCekUlang) {
            btnCekUlang.addEventListener('click', function() {
                // Reset kota asal dan tujuan
                $('#asal-paket').val('').trigger('change');
                $('#tujuan-paket').val('').trigger('change');

                // Reset berat
                document.getElementById('berat-paket').value = '0.1';

                // Reset dimensi
                document.getElementById('panjang-paket').value = '';
                document.getElementById('lebar-paket').value = '';
                document.getElementById('tinggi-paket').value = '';

                // Sembunyikan hasil
                hasilPerhitungan.style.display = 'none';

                // Scroll ke atas form
                document.getElementById('form-kirim-paket').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        // Initialize Select2 untuk modal
        $(document).ready(function() {
            $('#asal-paket').select2({
                placeholder: "Pilih Kota Asal",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modal-kirim-paket')
            });

            $('#tujuan-paket').select2({
                placeholder: "Pilih Kota Tujuan",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modal-kirim-paket')
            });
        });
    });
    </script>
</body>
</html>
