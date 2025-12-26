<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Shuttle - Beranda</title>
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
            --modal-bg: rgba(74, 66, 62, 0.50);
        }

        /* Reset margin dan padding untuk body */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: white;
        }

        .content-wrapper {
            flex: 1;
            background-color: white;
        }

        /* Custom Navbar Styles - TRANSPARAN */
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
            box-shadow: none;
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

        /* Panel Oval untuk Navbar - TRANSPARAN DENGAN BLUR */
        .nav-panel {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
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
            gap: 25px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
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
            align-items: center;
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        /* Navbar saat di-scroll */
        .custom-navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
        }

        /* Hero Section */
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
            font-family: 'Roboto', sans-serif;
            letter-spacing: -0.5px;
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

        /* Search Section */
        .search-section {
            position: relative;
            z-index: 20;
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: -138px;
            background: transparent;
        }

        .search-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.25);
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
            position: relative;
            height: auto;
            min-height: fit-content;
        }

        .search-input {
            width: 100%;
            height: 48px;
            border-radius: 6px;
            border: 2px solid #e0e0e0;
            font-size: 14px;
            background: #ffffff;
            color: black;
            font-weight: bold;
            padding: 0 12px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(255, 88, 30, 0.25);
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: 2px solid #e0e0e0 !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            font-weight: bold !important;
            font-family: 'Roboto', sans-serif !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 48px !important;
            color: black !important;
            font-size: 14px !important;
            padding-left: 12px !important;
            font-family: 'Roboto', sans-serif !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
        }

        .select2-dropdown {
            border: 2px solid #e0e0e0 !important;
            border-radius: 6px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
            font-family: 'Roboto', sans-serif !important;
        }

        /* BUTTON */
        .search-btn-container {
            height: 48px;
        }

        .search-btn {
            height: 100%;
            border-radius: 12px;
            background: white;
            color: var(--secondary-color);
            border: 2px solid #e0e0e0;
            font-weight: 700;
            padding: 0 32px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Roboto', sans-serif;
        }

        /* Tombol dengan layout vertikal */
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
        }

        .btn-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            width: 100%;
        }

        .btn-icon {
            display: none;
        }

        .btn-main-text {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
            color: inherit;
            text-align: left;
            width: 100%;
            font-family: 'Roboto', sans-serif;
        }

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

        /* Untuk tombol Cek Paket */
        #btn-cek-paket .btn-main-text,
        #btn-cek-paket .btn-label {
            color: var(--secondary-color);
        }

        /* Untuk tombol Kirim Paket */
        #kirim-paket-form .search-btn:not(#btn-cek-paket) .btn-main-text,
        #kirim-paket-form .search-btn:not(#btn-cek-paket) .btn-label {
            color: var(--secondary-color);
        }

        /* Hover state */
        #btn-cek-paket:hover .btn-main-text,
        #btn-cek-paket:hover .btn-label,
        #kirim-paket-form .search-btn:not(#btn-cek-paket):hover .btn-main-text,
        #kirim-paket-form .search-btn:not(#btn-cek-paket):hover .btn-label {
            color: white !important;
        }

        .search-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Style khusus untuk form Kirim Paket */
        #kirim-paket-form .search-row {
            grid-template-columns: 1fr 1fr !important;
            gap: 20px !important;
        }

        #kirim-paket-form .search-btn {
            height: 55px !important;
            font-size: 16px !important;
            width: 100%;
            background: white;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
        }

        /* Tombol Cek Paket */
        #btn-cek-paket {
            background: white !important;
            color: var(--secondary-color) !important;
            border: 2px solid var(--secondary-color) !important;
        }

        #btn-cek-paket:hover {
            background: var(--secondary-color) !important;
            color: white !important;
        }

        /* Tombol Kirim Paket */
        #kirim-paket-form .search-btn:not(#btn-cek-paket) {
            background: white !important;
            color: var(--secondary-color) !important;
            border: 2px solid var(--secondary-color) !important;
        }

        #kirim-paket-form .search-btn:not(#btn-cek-paket):hover {
            background: var(--secondary-color) !important;
            color: white !important;
        }

        /* Style untuk form Tiket Shuttle */
        #search-form .search-btn {
            background: white;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
        }

        #search-form .search-btn:hover {
            background: var(--secondary-color);
            color: white;
        }

        /* Modal Cek Paket */
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

        .modal-main-text {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--secondary-color);
            text-align: left;
            width: 100%;
            font-family: 'Roboto', sans-serif;
        }

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

        .modal-divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 0;
            border: none;
        }

        .modal-body {
            width: 100%;
            padding: 30px;
            box-sizing: border-box;
            background: var(--modal-bg);
            height: auto;
            min-height: fit-content;
            flex-shrink: 0;
        }

        .resi-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            height: auto;
        }

        .resi-input-group {
            display: flex;
            flex-direction: row;
            gap: 15px;
            width: 100%;
            align-items: center;
            height: auto;
            min-height: fit-content;
        }

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

        .kirim-paket-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            height: auto;
        }

        .form-group {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 15px;
            width: 100%;
            flex-wrap: nowrap;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: white;
            min-width: 120px;
            white-space: nowrap;
            flex-shrink: 0;
            font-family: 'Roboto', sans-serif;
        }

        .form-input-container {
            flex: 1;
            min-width: 0;
        }

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

        .harga-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px 20px;
            margin-top: 10px;
            border: 1px solid rgba(255,255,255,0.2);
            flex-wrap: nowrap;
        }

        .harga-label {
            font-size: 16px;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            font-family: 'Roboto', sans-serif;
        }

        .harga-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary-color);
            white-space: nowrap;
            font-family: 'Roboto', sans-serif;
        }

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

        #hasil-perhitungan {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        #hasil-perhitungan.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* === STYLE UNTUK MODAL ARTIKEL === */
        .modal-artikel {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            overflow-y: auto;
        }

        .modal-artikel.show {
            display: block;
        }

        .modal-artikel-content {
            background-color: white;
            margin: 50px auto;
            width: 90%;
            max-width: 900px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-artikel-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, var(--primary-color), #00308F);
            color: white;
            position: relative;
        }

        .modal-artikel-category {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .modal-artikel-title {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .modal-artikel-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
        }

        .modal-artikel-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .close-modal-artikel {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .close-modal-artikel:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-artikel-body {
            padding: 30px;
            line-height: 1.8;
            color: #333;
        }

        .modal-artikel-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .modal-artikel-content-text {
            font-size: 16px;
            margin-bottom: 20px;
            text-align: justify;
        }

        .modal-artikel-content-text h3 {
            color: var(--primary-color);
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .modal-artikel-content-text p {
            margin-bottom: 15px;
        }

        .modal-artikel-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .modal-artikel-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .modal-artikel-tag {
            background: #e9ecef;
            color: var(--primary-color);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .modal-artikel-share {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-artikel-share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .modal-artikel-share-btn:hover {
            transform: translateY(-3px);
        }

        .modal-artikel-share-btn.facebook {
            background-color: #3b5998;
        }

        .modal-artikel-share-btn.twitter {
            background-color: #1da1f2;
        }

        .modal-artikel-share-btn.whatsapp {
            background-color: #25d366;
        }

        /* Services Section */
        .services-section {
            padding: 80px 0;
            background: #ffffff;
            text-align: center;
        }

        .services-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .services-subtitle {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
            max-width: 780px;
            margin: 0 auto 50px;
            font-family: 'Roboto', sans-serif;
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
            border: 2px solid #e0e0e0;
            transition: all 0.35s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        /* CARD HOVER */
        .service-card:hover {
            transform: translateY(-10px) scale(1.01);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
            border-color: var(--secondary-color);
        }

        /* LOGO WRAPPER */
        .service-logo-box {
            border: 1.8px solid #dcdcdc;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px;
            transition: all 0.35s ease;
        }

        .service-logo-box img {
            width: 200px;
            height: auto;
            transition: 0.35s ease;
        }

        .service-card:hover .service-logo-box {
            transform: scale(1.03);
        }

        .service-card:hover .service-logo-box img {
            transform: scale(1.06);
        }

        /* TEXT */
        .service-desc {
            font-size: 13px;
            font-weight: 600;
            color: #17375f;
            line-height: 1.5;
            transition: 0.35s ease;
            font-family: 'Roboto', sans-serif;
        }

        .service-card:hover .service-desc {
            color: var(--secondary-color);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
            margin: 50px 0;
            opacity: 0.6;
        }

        /* === PROMO SECTION BARU === */
        .promo-section {
            padding: 80px 0;
            background: #f9f9f9;
            text-align: center;
            position: relative;
        }

        .promo-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .promo-subtitle {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
            max-width: 780px;
            margin: 0 auto 50px;
            font-family: 'Roboto', sans-serif;
        }

        /* Promo Slider Container */
        .promo-slider-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 60px;
        }

        .promo-slider {
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .promo-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .promo-slide {
            flex: 0 0 100%;
            min-width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .promo-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid #e0e0e0;
        }

        .promo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary-color);
        }

        .promo-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-bottom: 2px solid #f0f0f0;
        }

        .promo-content {
            padding: 20px;
            text-align: left;
        }

        .promo-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .promo-desc {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-family: 'Roboto', sans-serif;
        }

        .promo-period {
            font-size: 12px;
            color: var(--secondary-color);
            font-weight: 600;
            font-family: 'Roboto', sans-serif;
        }

        /* Slider Controls */
        .slider-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            gap: 20px;
        }

        .slider-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .slider-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: scale(1.1);
        }

        .slider-dots {
            display: flex;
            gap: 10px;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background: var(--secondary-color);
            transform: scale(1.2);
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
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
            font-family: 'Roboto', sans-serif;
        }

        .features-grid-6 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Features Section - 6 KOTAK FITUR DIUBAH KE BIRU TANPA BAYANGAN LAIN */
        .feature-card {
            background: var(--primary-color);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--primary-color);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            border-color: var(--primary-color);
            background: #0f2942ff;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #001a4a;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 25px;
            color: white !important;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
        }

        .feature-label {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white !important;
            line-height: 1.4;
            font-family: 'Roboto', sans-serif;
        }

        .feature-desc {
            font-size: 14px;
            color: #e0e8ff !important;
            line-height: 1.6;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        /* === ARTIKEL/BERITA SECTION === */
        .articles-section {
            padding: 80px 40px;
            background: #f8f9fa;
            text-align: center;
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

        /* === REVIEW SECTION === */
        .feedback-section {
            padding: 80px 40px;
            background: var(--primary-color);
        }

        .feedback-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 253, 253, 0.1);
            border-radius: 25px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.12);
            padding: 50px;
        }

        .feedback-title {
            font-size: 32px;
            color: white;
            margin-bottom: 10px;
            font-weight: 700;
            text-align: left;
            font-family: 'Roboto', sans-serif;
        }

        .feedback-line {
            width: 100%;
            height: 2px;
            background: #dcdcdc;
            margin-bottom: 30px;
        }

        .review-wrapper {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            align-items: start;
        }

        /* KIRI: REVIEW LIST CONTAINER */
        .review-list-container {
            background:rgba(255, 253, 253, 0.1);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        /* FILTER SECTION */
        .star-filter-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-bottom: 15px;
            font-family: 'Roboto', sans-serif;
        }

        .star-filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .star-filter-btn {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: rgba(255, 253, 253, 0.1);
            border-radius: 25px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            font-family: 'Roboto', sans-serif;
        }

        .star-filter-btn:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .star-filter-btn.active {
            background: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .star-filter-btn i {
            font-size: 12px;
        }

        /* REVIEW STATS */
        .review-stats {
            display: flex;
            gap: 25px;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            line-height: 1;
            margin-bottom: 5px;
            font-family: 'Roboto', sans-serif;
        }

        .stat-label {
            font-size: 12px;
            color: white;
            font-family: 'Roboto', sans-serif;
        }

        /* REVIEWS LIST */
        .reviews-list {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .reviews-list::-webkit-scrollbar {
            width: 6px;
        }

        .reviews-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .reviews-list::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
        }

        .review-item {
            background: rgba(255, 253, 253, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--secondary-color);
            transition: all 0.3s ease;
        }

        .review-item:hover {
            background: rgba(255, 253, 253, 0.3);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .review-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary-color);
        }

        .reviewer-details {
            flex: 1;
        }

        .reviewer-name {
            font-weight: 600;
            color: white;
            margin-bottom: 3px;
            font-family: 'Roboto', sans-serif;
        }

        .review-date {
            font-size: 12px;
            color: white;
            font-family: 'Roboto', sans-serif;
        }

        .review-stars {
            color: #ffc107;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .review-content {
            color: white;
            line-height: 1.6;
            font-size: 14px;
            margin-top: 10px;
            font-family: 'Roboto', sans-serif;
        }

        /* NO REVIEWS */
        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .no-reviews i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #ddd;
        }

        .no-reviews h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #666;
            font-family: 'Roboto', sans-serif;
        }

        .no-reviews p {
            font-size: 14px;
            color: #888;
            font-family: 'Roboto', sans-serif;
        }

        /* LOADING STATE */
        .loading-reviews {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading-reviews i {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--secondary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* PAGINATION */
        .review-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .page-btn {
            width: 35px;
            height: 35px;
            background: rgba(255, 253, 253, 0.1);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }

        .page-btn:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .page-btn.active {
            background: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-dots {
            color: #999;
            padding: 0 5px;
            font-family: 'Roboto', sans-serif;
        }

        /* KANAN: REVIEW FORM */
        .review-form-container {
            background: rgba(255, 253, 253, 0.1);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 100px;
        }

        .form-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            color: white;
            font-family: 'Roboto', sans-serif;
        }

        .form-subtitle {
            color: white;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
            font-family: 'Roboto', sans-serif;
        }

        /* RATING INPUT */
        .rating-input-container {
            margin-bottom: 25px;
        }

        .rating-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .star-rating-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .star-rating-input i {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating-input i:hover {
            transform: scale(1.1);
        }

        .star-rating-input i.active {
            color: #ffc107;
        }

        .rating-text {
            font-size: 14px;
            color: white;
            margin-left: 10px;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 25px;
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            background: rgba(255, 253, 253, 0.1);
            resize: vertical;
            min-height: 120px;
            color: white;
            transition: all 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }

        .form-textarea::placeholder{
            color:white;
        }

        .form-textarea:focus {
            outline: none;
            border-color: white;
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: white;
            margin-top: 5px;
            font-family: 'Roboto', sans-serif;
        }

        .char-count.limit {
            color: #dc3545;
        }

        /* BUTTONS */
        .btn-primary {
            width: 100%;
            background: white;
            color: var(--secondary-color);
            padding: 15px;
            font-size: 18px;
            border: 2px solid var(--secondary-color);
            border-radius: 10px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-close {
            width: 100%;
            background: white;
            color: #333;
            padding: 15px;
            font-size: 18px;
            border: 2px solid #333;
            border-radius: 10px;
            margin-top: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .btn-close:hover {
            background: #333;
            color: white;
            transform: translateY(-2px);
        }

        /* FORM NOTICE */
        .form-notice {
            background: #f0f7ff;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 20px;
            font-size: 13px;
            color: var(--primary-color);
            border-left: 3px solid var(--secondary-color);
            font-family: 'Roboto', sans-serif;
        }

        .form-notice i {
            color: var(--secondary-color);
            margin-right: 8px;
        }

        /* Style untuk hasil perhitungan */
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

        /* =================== MOBILE RESPONSIVE STYLES =================== */
        @media (max-width: 1200px) {
            .nav-panel {
                padding: 8px 20px;
            }

            .nav-links {
                gap: 15px;
            }

            .nav-links a {
                font-size: 0.9rem;
            }

            .hero-title {
                font-size: 48px;
            }
        }

        @media (max-width: 992px) {
            /* Navbar Mobile */
            .menu-toggle {
                display: block;
            }

            .nav-panel {
                flex-wrap: wrap;
                padding: 12px 20px;
                border-radius: 25px;
            }

            .nav-container {
                position: relative;
            }

            .nav-menu {
                order: 3;
                width: 100%;
                margin-top: 15px;
                display: none;
            }

            .nav-menu.active {
                display: block;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
                padding: 15px 0;
                align-items: center;
            }

            .nav-links a {
                font-size: 1rem;
                padding: 8px 0;
                width: 100%;
                text-align: center;
            }

            .nav-auth {
                order: 2;
            }

            /* Hero Section */
            .hero-section {
                padding: 100px 20px 60px;
                height: auto;
                min-height: 80vh;
            }

            .hero-content {
                max-width: 100%;
                text-align: center;
            }

            .hero-title {
                font-size: 36px;
                margin-bottom: 15px;
            }

            .hero-desc {
                font-size: 16px;
                margin: 0 auto;
            }

            .hero-services {
                flex-direction: column;
                max-width: 100%;
                gap: 10px;
            }

            .hero-service {
                flex-direction: row;
                justify-content: center;
                padding: 15px;
                min-width: 100%;
            }

            .hero-service i {
                font-size: 24px;
                margin-right: 10px;
            }

            /* Search Section */
            .search-section {
                margin-top: -80px;
                padding: 0 20px;
            }

            .search-container {
                padding: 20px;
                margin-bottom: 30px;
            }

            .search-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            #kirim-paket-form .search-row {
                grid-template-columns: 1fr !important;
            }

            .search-btn-container {
                height: 48px;
                margin-top: 10px;
            }

            .search-btn {
                width: 100%;
                justify-content: center;
            }

            .search-btn.vertical-btn {
                padding: 15px;
                text-align: center;
                align-items: center;
            }

            .search-btn.vertical-btn .btn-text {
                align-items: center;
                text-align: center;
            }

            /* Modal Adjustments */
            .modal-cek-paket,
            .modal-kirim-paket {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 500px;
                max-height: 90vh;
                overflow-y: auto;
            }

            .modal-body {
                padding: 20px;
            }

            .resi-input-group {
                flex-direction: column;
                gap: 12px;
            }

            .btn-cek-resi {
                width: 100%;
            }

            /* Services Section */
            .services-section {
                padding: 50px 20px;
            }

            .services-grid {
                flex-direction: column;
                gap: 25px;
            }

            .service-card {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
                padding: 25px 20px;
            }

            .service-logo-box {
                padding: 15px;
            }

            .service-logo-box img {
                width: 150px;
            }

            /* Promo Section */
            .promo-section {
                padding: 50px 20px;
            }

            .promo-slider-container {
                padding: 0 20px;
            }

            .promo-image {
                height: 200px;
            }

            .promo-content {
                padding: 15px;
            }

            .promo-name {
                font-size: 16px;
            }

            .promo-desc {
                font-size: 13px;
            }

            /* Features Section */
            .features-section {
                padding: 50px 20px;
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
                padding: 30px 20px;
            }

            .feature-icon {
                font-size: 36px;
                height: 60px;
                margin-bottom: 20px;
            }

            .feature-label {
                font-size: 18px;
            }

            /* Articles Section */
            .articles-section {
                padding: 50px 20px;
            }

            .articles-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .article-card {
                max-width: 400px;
                margin: 0 auto;
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

            /* Feedback Section */
            .feedback-section {
                padding: 50px 20px;
            }

            .feedback-container {
                padding: 30px 20px;
                border-radius: 20px;
            }

            .feedback-title {
                font-size: 24px;
            }

            .review-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .review-form-container {
                position: static;
            }

            .review-stats {
                flex-wrap: wrap;
                justify-content: space-around;
            }

            .stat-item {
                flex: 0 0 45%;
                margin-bottom: 15px;
            }

            .review-item {
                padding: 15px;
            }

            .review-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .reviewer-info {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .review-avatar {
                width: 40px;
                height: 40px;
            }

            /* Modal Artikel */
            .modal-artikel-content {
                width: 95%;
                margin: 20px auto;
            }

            .modal-artikel-header {
                padding: 20px;
            }

            .modal-artikel-title {
                font-size: 22px;
            }

            .modal-artikel-body {
                padding: 20px;
            }

            .modal-artikel-image {
                height: 250px;
            }

            /* Divider */
            .divider {
                margin: 30px 20px;
            }
        }

        @media (max-width: 768px) {
            /* Form Adjustments */
            .form-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .form-label {
                min-width: 100%;
                margin-bottom: 5px;
            }

            .form-input-container {
                width: 100%;
            }

            .volume-container {
                flex-direction: column;
                gap: 10px;
            }

            .volume-input {
                width: 100%;
            }

            /* Select2 Mobile */
            .select2-container--default .select2-selection--single {
                height: 44px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 44px !important;
                font-size: 14px !important;
            }

            /* Button Adjustments */
            .btn-login {
                padding: 8px 16px;
                font-size: 14px;
            }

            /* Profile Dropdown */
            .profile-name {
                display: none;
            }

            .profile-btn {
                padding: 5px;
            }

            .profile-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            /* Hero Section */
            .hero-section {
                padding: 120px 15px 40px;
                min-height: 70vh;
            }

            .hero-title {
                font-size: 28px;
            }

            .hero-desc {
                font-size: 14px;
            }

            .hero-service {
                padding: 12px;
            }

            .hero-service i {
                font-size: 20px;
            }

            .hero-service span {
                font-size: 12px;
            }

            /* Search Section */
            .search-section {
                margin-top: -60px;
            }

            .search-container {
                padding: 15px;
            }

            .search-input {
                height: 44px;
                font-size: 14px;
            }

            /* Promo Slider */
            .promo-image {
                height: 160px;
            }

            .promo-name {
                font-size: 14px;
            }

            .promo-desc {
                font-size: 12px;
            }

            .slider-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            /* Features Section */
            .features-title {
                font-size: 24px;
                margin-bottom: 30px;
            }

            .feature-card {
                padding: 25px 15px;
            }

            .feature-icon {
                font-size: 32px;
                height: 50px;
                margin-bottom: 15px;
            }

            .feature-label {
                font-size: 16px;
            }

            .feature-desc {
                font-size: 13px;
            }

            /* Articles Section */
            .article-image {
                height: 160px;
            }

            .article-content {
                padding: 15px;
            }

            .article-title {
                font-size: 15px;
            }

            .article-excerpt {
                font-size: 13px;
            }

            /* Feedback Section */
            .feedback-container {
                padding: 25px 15px;
            }

            .feedback-title {
                font-size: 20px;
            }

            .form-title {
                font-size: 18px;
            }

            .star-rating-input i {
                font-size: 28px;
            }

            .btn-primary,
            .btn-close {
                padding: 12px;
                font-size: 16px;
            }

            /* Modal Artikel */
            .modal-artikel-title {
                font-size: 18px;
            }

            .modal-artikel-body {
                padding: 15px;
            }

            .modal-artikel-image {
                height: 200px;
            }
        }

        @media (max-width: 400px) {
            .nav-panel {
                padding: 10px 15px;
                border-radius: 20px;
            }

            .nav-brand img {
                height: 28px;
            }

            .hero-title {
                font-size: 24px;
            }

            .hero-services {
                gap: 8px;
            }

            .hero-service {
                padding: 10px 8px;
            }

            .hero-service i {
                font-size: 18px;
                margin-right: 6px;
            }

            .hero-service span {
                font-size: 11px;
            }

            .promo-image {
                height: 140px;
            }

            .article-image {
                height: 140px;
            }

            .stat-item {
                flex: 0 0 100%;
            }
        }

        /* Footer Styles */
        .site-footer {
            background: #123352;
            color: #123352;
            padding: 50px 40px 20px;
            margin-top: auto;
            border-top: 2px solid #123352;
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

        /* Contact List */
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

        /* Social Buttons */
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

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid #e0e0e0;
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

        /* Footer Mobile Responsive */
        @media (max-width: 768px) {
            .site-footer {
                padding: 40px 20px 20px;
            }

            .footer-main {
                flex-direction: column;
                gap: 30px;
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
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
        }

        /* Profile icon + small name */
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

        /* Dropdown Menu */
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

        /* Tambahkan class untuk show */
        .dropdown-menu.show {
            display: block;
        }

        /* Mobile Dropdown Menu */
        @media (max-width: 992px) {
            .dropdown-menu {
                position: fixed;
                top: auto;
                right: 20px;
                left: 20px;
                bottom: 20px;
                min-width: auto;
                z-index: 1002;
            }
        }
    </style>
    @php
        use App\Models\MProfilePerusahaan;
        use App\Models\Promo;
        use App\Models\Artikel;
        use Carbon\Carbon;
        $profile = MProfilePerusahaan::first();

        // Data user dari session
        $user = session()->get('user', null);

        // Data review dan statistik
        $totalReviews = 24;
        $averageRating = 4.8;
        $reviewStats = [
            5 => 18,
            4 => 4,
            3 => 1,
            2 => 1,
            1 => 0
        ];

        // Data review
        $reviews = collect([
            [
                'id' => 1,
                'avatar' => 'https://randomuser.me/api/portraits/women/32.jpg',
                'name' => 'Luna Ayna',
                'rating' => 5,
                'date' => '2024-03-15',
                'content' => 'Servisnya bagus, drivernya sopan dan nyetirnya halus jadi bisa tidur selama perjalanan. Tracking lokasinya juga akurat. Bakal jadi langganan.'
            ],
            [
                'id' => 2,
                'avatar' => 'https://randomuser.me/api/portraits/men/54.jpg',
                'name' => 'Rizky Pratama',
                'rating' => 4,
                'date' => '2024-03-14',
                'content' => 'Pertama kali coba SmartShuttle dan langsung puas. Mobilnya bersih, AC dingin, kursinya empuk. Berangkat juga sesuai jadwal. Recommended banget!'
            ],
            [
                'id' => 3,
                'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'name' => 'Sari Dewi',
                'rating' => 5,
                'date' => '2024-03-13',
                'content' => 'Harganya menurut saya cukup murah dibanding shuttle lain, tapi kualitas layanannya tetap bagus. Pemesanan lewat aplikasi juga gampang.'
            ]
        ]);

        // Data promo
        $promos = Promo::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($promo) {
                return [
                    'id' => $promo->id,
                    'nama' => $promo->nama_promo,
                    'deskripsi' => $promo->deskripsi,
                    'gambar' => asset('storage/' . $promo->gambar_promo),
                    'periode' => Carbon::parse($promo->tanggal_mulai)->format('d M') . ' - ' . Carbon::parse($promo->tanggal_selesai)->format('d M Y'),
                ];
            })
            ->toArray();

        // Jika tidak ada promo, gunakan data default
        if (empty($promos)) {
            $promos = [
                [
                    'id' => 1,
                    'nama' => 'Diskon 30% Shuttle',
                    'deskripsi' => 'Nikmati diskon 30% untuk semua rute shuttle reguler. Berlaku untuk pemesanan minimal 2 tiket.',
                    'gambar' => asset('images/promo1.jpg'),
                    'periode' => '1 Mar - 31 Mar 2024',
                ]
            ];
        }

        // Data artikel
        $artikelsFromDB = Artikel::orderBy('tanggal_publikasi', 'desc')->take(3)->get();
        $articles = [];

        foreach ($artikelsFromDB as $artikel) {
            $articles[] = [
                'id' => $artikel->id,
                'image' => asset('images/default-article.jpg'),
                'category' => $artikel->kategori,
                'title' => $artikel->judul,
                'excerpt' => substr(strip_tags($artikel->konten), 0, 100) . '...',
                'date' => Carbon::parse($artikel->tanggal_publikasi)->translatedFormat('d F Y'),
                'read_time' => '5 min read',
                'tags' => explode(', ', $artikel->meta_keywords),
                'full_content' => $artikel->konten,
                'author' => $artikel->penulis
            ];
        }

        if (empty($articles)) {
            $articles = [
                [
                    'id' => 1,
                    'image' => asset('images/default-article.jpg'),
                    'category' => 'Tips & Trik',
                    'title' => 'Tips Perjalanan Aman dengan Shuttle Selama Liburan',
                    'excerpt' => 'Pelajari cara mempersiapkan perjalanan shuttle yang aman dan nyaman selama musim liburan untuk pengalaman terbaik.',
                    'date' => '15 Maret 2024',
                    'read_time' => '5 min read',
                    'tags' => ['Perjalanan', 'Tips', 'Liburan'],
                    'full_content' => '<h3>Persiapan Sebelum Perjalanan</h3><p>Perjalanan dengan shuttle selama liburan memerlukan persiapan yang matang. Pastikan Anda memesan tiket jauh-jauh hari untuk mendapatkan harga terbaik dan kursi pilihan. Smart Shuttle menawarkan pemesanan online yang mudah melalui website atau aplikasi kami.</p>',
                    'author' => 'Admin SmartShuttle'
                ]
            ];
        }

        $activeService = request()->get('service', 'shuttle');
    @endphp
</head>
<body>
    <!-- Custom Navbar -->
    <nav class="custom-navbar" id="navbar">
        <div class="nav-container">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-panel">
                <div class="nav-brand">
                    <img src="{{ asset($profile->logo_perusahaan ?? '/images/smartshuttlelogo.png') }}" alt="{{ $profile->nama_dagang ?? 'Smart Shuttle' }}">
                </div>
                <div class="nav-menu" id="navMenu">
                    <ul class="nav-links">
                        <li><a href="/customer/beranda" class="{{ $activeService === 'shuttle' ? 'active' : '' }}">Beranda</a></li>
                        <li><a href="{{ route('customer.search') }}">Cari Tiket</a></li>
                        <li><a href="{{ route('customer.outlet') }}">Outlet</a></li>
                        <li><a href="{{ url()->current() }}?service=kirim-paket" class="{{ $activeService === 'kirim-paket' ? 'active' : '' }}" id="nav-kirim-paket">Kirim Paket</a></li>
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

    <!-- Hero Section -->
    <div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
        <div class="hero-content">
            <h1 class="hero-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h1>
            <p class="hero-desc">
                {{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}
            </p>
            <div class="hero-services">
                <a href="{{ url()->current() }}?service=shuttle" class="hero-service {{ $activeService === 'shuttle' ? 'active' : '' }}" id="shuttle-link">
                    <i class="fas fa-shuttle-van"></i>
                    <span>Tiket Shuttle</span>
                </a>
                <a href="{{ url()->current() }}?service=kirim-paket" class="hero-service {{ $activeService === 'kirim-paket' ? 'active' : '' }}" id="kirim-paket-link">
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
            <!-- Form Tiket Shuttle -->
            <form action="{{ route('customer.search') }}" method="GET" id="search-form" class="service-form" data-service="shuttle" style="{{ $activeService === 'shuttle' ? 'display: block;' : 'display: none;' }}">
                <div class="search-row">
                    <div class="search-field">
                        <select class="search-input select2-dropdown" id="departure-outlet" name="departure_outlet" required>
                            <option value="">Pilih Outlet Keberangkatan</option>
                            @foreach($outletsGrouped as $kota => $outlets)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">
                                            {{ $outlet->nama_outlet }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <select class="search-input select2-dropdown" id="destination-outlet" name="destination_outlet" required>
                            <option value="">Pilih Outlet Tujuan</option>
                            @foreach($outletsGrouped as $kota => $outlets)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">
                                            {{ $outlet->nama_outlet }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
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
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> CEK SHUTTLE
                        </button>
                    </div>
                </div>
            </form>

            <!-- Form Kirim Paket -->
            <form action="{{ route('customer.kirim-paket') }}" method="GET" id="kirim-paket-form" class="service-form" data-service="kirim-paket" style="{{ $activeService === 'kirim-paket' ? 'display: block;' : 'display: none;' }}">
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
                        <!-- Modal Cek Paket -->
                        <div class="modal-cek-paket" id="modal-cek-paket">
                            <button type="button" class="close-modal" id="close-modal-cek-paket">
                                <i class="fas fa-times"></i>
                            </button>

                            <div class="modal-header">
                                <div class="modal-main-text">CEK PAKET</div>
                                <div class="modal-label">Cek status paket yang sudah anda kirim kan</div>
                            </div>

                            <hr class="modal-divider">

                            <div class="modal-body">
                                <div class="resi-form">
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
                                <div class="btn-main-text">CEK HARGA</div>
                                <div class="btn-label">
                                    Kirim paket ke beberapa daerah
                                </div>
                            </div>
                        </button>
                        <!-- Modal Kirim Paket -->
                        <div class="modal-kirim-paket" id="modal-kirim-paket">
                            <button type="button" class="close-modal" id="close-modal-kirim-paket">
                                <i class="fas fa-times"></i>
                            </button>

                            <div class="modal-header">
                                <div class="modal-main-text">CEK HARGA PAKET</div>
                                <div class="modal-label">Cek harga pengiriman paket antar kota</div>
                            </div>

                            <hr class="modal-divider">

                            <div class="modal-body">
                                <div class="kirim-paket-form" id="form-kirim-paket">
                                    <h4 style="color: white; margin-bottom: 15px;">Data Paket</h4>

                                    <div class="form-group">
                                        <label class="form-label">Kota Asal</label>
                                        <div class="form-input-container">
                                            <select class="form-control select2-modal" id="asal-paket" name="asal_paket">
                                                <option value="">Pilih Kota Asal</option>
                                                @foreach($outletsGrouped as $kota => $outlets)
                                                    <option value="{{ $kota }}">{{ $kota }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Kota Tujuan</label>
                                        <div class="form-input-container">
                                            <select class="form-control select2-modal" id="tujuan-paket" name="tujuan_paket">
                                                <option value="">Pilih Kota Tujuan</option>
                                                @foreach($outletsGrouped as $kota => $outlets)
                                                    <option value="{{ $kota }}">{{ $kota }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

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

                                    <button type="button" class="btn-cek-harga" id="btn-cek-harga">
                                        <i class="fas fa-calculator"></i> CEK HARGA SEKARANG
                                    </button>

                                    <div id="hasil-perhitungan" style="display: none; margin-top: 25px;">
                                        <h4 style="color: white; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                                            <i class="fas fa-check-circle"></i> Hasil Perhitungan Harga
                                        </h4>

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

                                        <div style="margin-top: 15px; padding: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 6px; border-left: 3px solid var(--secondary-color);">
                                            <p style="color: #ccc; font-size: 12px; margin: 0;">
                                                <i class="fas fa-info-circle" style="margin-right: 5px; color: var(--secondary-color);"></i>
                                                Harga sudah termasuk biaya pengiriman standar. Berat yang digunakan adalah berat terbesar antara berat aktual dan berat volumetric.
                                            </p>
                                        </div>

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

    <!-- Promo Section -->
    <div class="promo-section">
        <h2 class="promo-title">Promo Spesial {{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h2>
        <p class="promo-subtitle">
            Nikmati berbagai penawaran menarik khusus untuk Anda. Dapatkan diskon dan promo eksklusif untuk layanan kami.
        </p>

        <div class="promo-slider-container">
            <div class="promo-slider">
                <div class="promo-track" id="promo-track">
                    @foreach($promos as $promo)
                    <div class="promo-slide">
                        <div class="promo-card">
                            <img src="{{ $promo['gambar'] }}" alt="{{ $promo['nama'] }}" class="promo-image"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-promo.jpg') }}';">
                            <div class="promo-content">
                                <h3 class="promo-name">{{ $promo['nama'] }}</h3>
                                <p class="promo-desc">{{ $promo['deskripsi'] }}</p>
                                <p class="promo-period">Periode: {{ $promo['periode'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Slider Controls -->
            <div class="slider-controls">
                <button class="slider-btn" id="prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="slider-dots" id="slider-dots">
                    <!-- Dots akan di-generate oleh JavaScript -->
                </div>
                <button class="slider-btn" id="next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
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

    <!-- Articles Section -->
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

    <!-- Modal Artikel -->
    <div class="modal-artikel" id="modal-artikel">
        <div class="modal-artikel-content">
            <button class="close-modal-artikel" id="close-modal-artikel">
                <i class="fas fa-times"></i>
            </button>
            <div id="modal-artikel-content"></div>
        </div>
    </div>

    <!-- Feedback Section -->
    <section class="feedback-section">
        <div class="feedback-container">
            <h2 class="feedback-title">Review Pelanggan</h2>
            <div class="feedback-line"></div>

            <div class="review-wrapper">
                <div class="review-list-container">
                    <div class="star-filter-section">
                        <h4 class="filter-title">Filter berdasarkan Rating:</h4>
                        <div class="star-filter-buttons">
                            <button class="star-filter-btn active" data-rating="0">Semua</button>
                            <button class="star-filter-btn" data-rating="5">
                                <i class="fas fa-star"></i> 5 ({{ $reviewStats[5] ?? 0 }})
                            </button>
                            <button class="star-filter-btn" data-rating="4">
                                <i class="fas fa-star"></i> 4 ({{ $reviewStats[4] ?? 0 }})
                            </button>
                            <button class="star-filter-btn" data-rating="3">
                                <i class="fas fa-star"></i> 3 ({{ $reviewStats[3] ?? 0 }})
                            </button>
                            <button class="star-filter-btn" data-rating="2">
                                <i class="fas fa-star"></i> 2 ({{ $reviewStats[2] ?? 0 }})
                            </button>
                            <button class="star-filter-btn" data-rating="1">
                                <i class="fas fa-star"></i> 1 ({{ $reviewStats[1] ?? 0 }})
                            </button>
                        </div>

                        <div class="review-stats">
                            <div class="stat-item">
                                <span class="stat-value">{{ $totalReviews ?? 0 }}</span>
                                <span class="stat-label">Total Review</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">{{ $averageRating ?? '5.0' }}</span>
                                <span class="stat-label">Rating Rata-rata</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">{{ $totalReviews > 0 ? round(($reviewStats[5] / $totalReviews) * 100) : 0 }}%</span>
                                <span class="stat-label">5 Bintang</span>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-list" id="reviews-list">
                        <div class="loading-reviews">
                            <i class="fas fa-spinner fa-spin"></i> Memuat review...
                        </div>
                    </div>

                    <div class="review-pagination" id="review-pagination">
                        <!-- Pagination akan di-generate oleh JavaScript -->
                    </div>
                </div>

                <div class="review-form-container">
                    <div class="form-title">Berikan Penilaian Anda</div>
                    <p class="form-subtitle">Bagikan pengalaman Anda menggunakan layanan kami</p>

                    <div class="rating-input-container">
                        <div class="rating-label">Rating:</div>
                        <div class="star-rating-input" id="star-rating">
                            <i class="fas fa-star" data-rating="1"></i>
                            <i class="fas fa-star" data-rating="2"></i>
                            <i class="fas fa-star" data-rating="3"></i>
                            <i class="fas fa-star" data-rating="4"></i>
                            <i class="fas fa-star" data-rating="5"></i>
                            <span class="rating-text" id="rating-text">Pilih bintang</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea class="form-textarea" id="review-text"
                                  placeholder="Ceritakan pengalaman Anda menggunakan layanan kami... (Minimal 10 karakter)"
                                  rows="5"></textarea>
                        <div class="char-count" id="char-count">0/500 karakter</div>
                    </div>

                    <button class="btn-primary" id="submit-review">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Review
                    </button>
                    <button class="btn-close" id="reset-review">
                        <i class="fas fa-redo"></i>
                        Reset Form
                    </button>

                    <div class="form-notice">
                        <i class="fas fa-info-circle"></i>
                        Review Anda akan membantu kami meningkatkan layanan
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-main">
                <div class="footer-column">
                    <h3 class="footer-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h3>
                    <p class="footer-text">
                        {{ $profile->deskripsi_singkat ?? 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.' }}
                    </p>
                </div>

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
        /* ========== MOBILE MENU TOGGLE ========== */
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                navMenu.classList.toggle('active');
                menuToggle.innerHTML = navMenu.classList.contains('active')
                    ? '<i class="fas fa-times"></i>'
                    : '<i class="fas fa-bars"></i>';
            });

            // Tutup menu saat klik di luar
            document.addEventListener('click', function(e) {
                if (navMenu.classList.contains('active') &&
                    !navMenu.contains(e.target) &&
                    !menuToggle.contains(e.target)) {
                    navMenu.classList.remove('active');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });

            // Tutup menu saat window di-resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992 && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        }

        /* ========== NAVBAR SCROLL ========== */
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

        /* ========== DATE MIN ========== */
        const departureDateInput = document.querySelector('input[name="departure_date"]');
        if (departureDateInput) {
            const today = new Date().toISOString().split('T')[0];
            departureDateInput.setAttribute('min', today);
        }

        /* ========== SEARCH FORM VALIDATION ========== */
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

        /* ========== PROFILE DROPDOWN ========== */
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
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    dropdownButton.focus();
                }
            });

            dropdownMenu.addEventListener('click', function (e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        /* ========== MODAL CEK PAKET ========== */
        const btnCekPaket = document.getElementById('btn-cek-paket');
        const modalCekPaket = document.getElementById('modal-cek-paket');
        const closeModalCekPaket = document.getElementById('close-modal-cek-paket');
        const btnCekResi = document.getElementById('btn-cek-resi');

        if (btnCekPaket) {
            btnCekPaket.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (modalKirimPaket && modalKirimPaket.classList.contains('show')) {
                    modalKirimPaket.classList.remove('show');
                }

                modalCekPaket.classList.toggle('show');
                document.body.style.overflow = 'hidden';
            });
        }

        if (closeModalCekPaket) {
            closeModalCekPaket.addEventListener('click', function(e) {
                e.preventDefault();
                modalCekPaket.classList.remove('show');
                document.body.style.overflow = 'auto';
            });
        }

        document.addEventListener('click', function(e) {
            if (modalCekPaket.classList.contains('show')) {
                if (!modalCekPaket.contains(e.target) && !btnCekPaket.contains(e.target)) {
                    modalCekPaket.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });

        if (btnCekResi) {
            btnCekResi.addEventListener('click', function(e) {
                e.preventDefault();
                const kodeResi = document.getElementById('kode-resi').value.trim();

                if (!kodeResi) {
                    alert('Silakan masukkan kode resi terlebih dahulu!');
                    return;
                }

                alert('Mencari informasi untuk resi: ' + kodeResi + '\n\n(Sistem cek paket akan ditampilkan di sini)');

                document.getElementById('kode-resi').value = '';
                modalCekPaket.classList.remove('show');
                document.body.style.overflow = 'auto';
            });
        }

        /* ========== MODAL KIRIM PAKET ========== */
        const btnKirimPaket = document.getElementById('btn-kirim-paket');
        const modalKirimPaket = document.getElementById('modal-kirim-paket');
        const closeModalKirimPaket = document.getElementById('close-modal-kirim-paket');

        if (btnKirimPaket) {
            btnKirimPaket.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (modalCekPaket && modalCekPaket.classList.contains('show')) {
                    modalCekPaket.classList.remove('show');
                }

                modalKirimPaket.classList.toggle('show');
                document.body.style.overflow = 'hidden';

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

        if (closeModalKirimPaket) {
            closeModalKirimPaket.addEventListener('click', function(e) {
                e.preventDefault();
                modalKirimPaket.classList.remove('show');
                document.body.style.overflow = 'auto';
            });
        }

        document.addEventListener('click', function(e) {
            if (modalKirimPaket.classList.contains('show')) {
                if (!modalKirimPaket.contains(e.target) && !btnKirimPaket.contains(e.target)) {
                    modalKirimPaket.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (modalCekPaket.classList.contains('show')) {
                    modalCekPaket.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }

                if (modalKirimPaket.classList.contains('show')) {
                    modalKirimPaket.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }

                if (modalArtikel.classList.contains('show')) {
                    modalArtikel.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });

        /* ========== SERVICE SWITCHER ========== */
        const shuttleLink = document.getElementById('shuttle-link');
        const kirimPaketLink = document.getElementById('kirim-paket-link');
        const navKirimPaket = document.getElementById('nav-kirim-paket');
        const serviceForms = document.querySelectorAll('.service-form');
        const heroServices = document.querySelectorAll('.hero-service');
        const heroTitle = document.querySelector('.hero-title');
        const heroDesc = document.querySelector('.hero-desc');
        const navLinks = document.querySelectorAll('.nav-links a');
        const urlParams = new URLSearchParams(window.location.search);
        const activeService = urlParams.get('service') || 'shuttle';

        function updateNavbarActiveState(serviceType) {
            navLinks.forEach(link => {
                link.classList.remove('active');

                if (serviceType === 'shuttle' && link.getAttribute('href') === '/customer/beranda') {
                    link.classList.add('active');
                }

                if (serviceType === 'kirim-paket' && link.id === 'nav-kirim-paket') {
                    link.classList.add('active');
                }
            });
        }

        function switchService(serviceType) {
            serviceForms.forEach(form => {
                form.style.display = 'none';
            });

            const activeForm = document.querySelector(`.service-form[data-service="${serviceType}"]`);
            if (activeForm) {
                activeForm.style.display = 'block';
            }

            updateNavbarActiveState(serviceType);

            if (modalCekPaket) {
                modalCekPaket.classList.remove('show');
                document.body.style.overflow = 'auto';
            }

            if (modalKirimPaket) {
                modalKirimPaket.classList.remove('show');
                document.body.style.overflow = 'auto';
            }

            updateHeroContent(serviceType);

            const newUrl = new URL(window.location);
            if (serviceType === 'shuttle') {
                newUrl.searchParams.delete('service');
            } else {
                newUrl.searchParams.set('service', serviceType);
            }
            window.history.pushState({}, '', newUrl);

            updateHeroServicesActiveState(serviceType);

            setTimeout(() => {
                if (serviceType === 'shuttle') {
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
                } else if (serviceType === 'kirim-paket') {
                    if ($('#asal-paket').data('select2') === undefined) {
                        $('#asal-paket').select2({
                            placeholder: "Pilih Kota Asal",
                            allowClear: true,
                            width: '100%'
                        });
                        $('#tujuan-paket').select2({
                            placeholder: "Pilih Kota Tujuan",
                            allowClear: true,
                            width: '100%'
                        });
                    }
                }
            }, 100);
        }

        function updateHeroContent(serviceType) {
            if (heroTitle && heroDesc) {
                if (serviceType === 'kirim-paket') {
                    heroTitle.textContent = 'SmartSend';
                    heroDesc.textContent = 'Setiap kiriman punya tujuan — Paket terkirim cepat, aman, dan terpantau.';
                } else {
                    heroTitle.textContent = '{{ $profile->nama_dagang ?? 'Smart Shuttle' }}';
                    heroDesc.textContent = '{{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}';
                }
            }
        }

        function updateHeroServicesActiveState(serviceType) {
            heroServices.forEach(service => {
                service.classList.remove('active');

                if (service.id === 'shuttle-link' && serviceType === 'shuttle') {
                    service.classList.add('active');
                } else if (service.id === 'kirim-paket-link' && serviceType === 'kirim-paket') {
                    service.classList.add('active');
                }
            });
        }

        if (activeService === 'kirim-paket') {
            switchService('kirim-paket');
        } else {
            switchService('shuttle');
        }

        if (shuttleLink) {
            shuttleLink.addEventListener('click', function(e) {
                e.preventDefault();
                switchService('shuttle');
                document.querySelector('.search-section').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }

        if (kirimPaketLink) {
            kirimPaketLink.addEventListener('click', function(e) {
                e.preventDefault();
                switchService('kirim-paket');
                document.querySelector('.search-section').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }

        if (navKirimPaket) {
            navKirimPaket.addEventListener('click', function(e) {
                e.preventDefault();
                switchService('kirim-paket');
                document.querySelector('.search-section').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }

        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const service = urlParams.get('service') || 'shuttle';
            switchService(service);
        });

        /* ========== MODAL ARTIKEL ========== */
        const modalArtikel = document.getElementById('modal-artikel');
        const closeModalArtikel = document.getElementById('close-modal-artikel');
        const modalArtikelContent = document.getElementById('modal-artikel-content');
        const readMoreButtons = document.querySelectorAll('.article-read-more');

        const articlesData = @json($articles);

        function showArticleModal(articleId) {
            const article = articlesData.find(a => a.id == articleId);

            if (!article) {
                alert('Artikel tidak ditemukan!');
                return;
            }

            modalArtikelContent.innerHTML = `
                <div class="modal-artikel-header">
                    <span class="modal-artikel-category">${article.category}</span>
                    <h2 class="modal-artikel-title">${article.title}</h2>
                    <div class="modal-artikel-meta">
                        <div class="modal-artikel-date">
                            <i class="far fa-calendar-alt"></i>
                            ${article.date}
                        </div>
                        <div>
                            <i class="far fa-clock"></i>
                            ${article.read_time}
                        </div>
                        <div>
                            <i class="fas fa-user"></i>
                            ${article.author}
                        </div>
                    </div>
                </div>
                <div class="modal-artikel-body">
                    ${article.image.includes('default') ?
                        `<div class="default-article-img" style="background: linear-gradient(135deg, var(--primary-color), #00308F); width: 100%; height: 400px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold; text-align: center; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                            ${article.title}
                        </div>` :
                        `<img src="${article.image}" alt="${article.title}" class="modal-artikel-image"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-article.jpg') }}';">`
                    }
                    <div class="modal-artikel-content-text">
                        ${article.full_content}
                    </div>
                </div>
                <div class="modal-artikel-footer">
                    <div class="modal-artikel-tags">
                        ${article.tags.map(tag => `<span class="modal-artikel-tag">${tag}</span>`).join('')}
                    </div>
                    <div class="modal-artikel-share">
                        <a href="#" class="modal-artikel-share-btn facebook" onclick="shareOnFacebook('${article.title}', window.location.href)">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="modal-artikel-share-btn twitter" onclick="shareOnTwitter('${article.title}', window.location.href)">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="modal-artikel-share-btn whatsapp" onclick="shareOnWhatsApp('${article.title}', window.location.href)">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            `;

            modalArtikel.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeArticleModal() {
            modalArtikel.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        readMoreButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const articleId = this.getAttribute('data-article-id');
                showArticleModal(articleId);
            });
        });

        if (closeModalArtikel) {
            closeModalArtikel.addEventListener('click', closeArticleModal);
        }

        modalArtikel.addEventListener('click', function(e) {
            if (e.target === modalArtikel) {
                closeArticleModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalArtikel.classList.contains('show')) {
                closeArticleModal();
            }
        });

        window.shareOnFacebook = function(title, url) {
            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(title)}`;
            window.open(shareUrl, '_blank', 'width=600,height=400');
        };

        window.shareOnTwitter = function(title, url) {
            const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
            window.open(shareUrl, '_blank', 'width=600,height=400');
        };

        window.shareOnWhatsApp = function(title, url) {
            const shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(title + ' ' + url)}`;
            window.open(shareUrl, '_blank', 'width=600,height=400');
        };

        /* ========== REVIEW MANAGEMENT ========== */
        let allReviews = @json($reviews ?? []);

        if (!allReviews || allReviews.length === 0) {
            allReviews = [
                {
                    id: 1,
                    avatar: 'https://randomuser.me/api/portraits/women/32.jpg',
                    name: 'Luna Ayna',
                    rating: 5,
                    date: '2024-03-15',
                    content: 'Servisnya bagus, drivernya sopan dan nyetirnya halus jadi bisa tidur selama perjalanan. Tracking lokasinya juga akurat. Bakal jadi langganan.'
                },
                {
                    id: 2,
                    avatar: 'https://randomuser.me/api/portraits/men/54.jpg',
                    name: 'Rizky Pratama',
                    rating: 4,
                    date: '2024-03-14',
                    content: 'Pertama kali coba SmartShuttle dan langsung puas. Mobilnya bersih, AC dingin, kursinya empuk. Berangkat juga sesuai jadwal. Recommended banget!'
                }
            ];
        }

        let currentFilter = 0;
        let currentPage = 1;
        const reviewsPerPage = 4;
        let selectedRating = 0;

        const reviewsList = document.getElementById('reviews-list');
        const reviewPagination = document.getElementById('review-pagination');
        const filterButtons = document.querySelectorAll('.star-filter-btn');
        const starRatingInput = document.getElementById('star-rating');
        const ratingText = document.getElementById('rating-text');
        const reviewText = document.getElementById('review-text');
        const charCount = document.getElementById('char-count');
        const submitReviewBtn = document.getElementById('submit-review');
        const resetReviewBtn = document.getElementById('reset-review');

        initReviewSection();

        function initReviewSection() {
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    currentFilter = parseInt(this.dataset.rating);
                    currentPage = 1;

                    loadReviews();
                });
            });

            if (starRatingInput) {
                const stars = starRatingInput.querySelectorAll('i');
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.rating);
                        selectedRating = rating;

                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });

                        const ratingTexts = [
                            'Pilih bintang',
                            'Sangat Buruk',
                            'Buruk',
                            'Cukup',
                            'Baik',
                            'Sangat Baik'
                        ];
                        ratingText.textContent = ratingTexts[rating];
                    });
                });
            }

            if (reviewText) {
                reviewText.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = `${length}/500 karakter`;

                    if (length > 500) {
                        charCount.classList.add('limit');
                        this.value = this.value.substring(0, 500);
                    } else {
                        charCount.classList.remove('limit');
                    }
                });
            }

            if (submitReviewBtn) {
                submitReviewBtn.addEventListener('click', submitReview);
            }

            if (resetReviewBtn) {
                resetReviewBtn.addEventListener('click', resetReviewForm);
            }

            loadReviews();
        }

        function loadReviews() {
            let filteredReviews = allReviews;
            if (currentFilter > 0) {
                filteredReviews = allReviews.filter(review => review.rating === currentFilter);
            }

            const totalPages = Math.ceil(filteredReviews.length / reviewsPerPage);
            const startIndex = (currentPage - 1) * reviewsPerPage;
            const endIndex = startIndex + reviewsPerPage;
            const pageReviews = filteredReviews.slice(startIndex, endIndex);

            renderReviews(pageReviews);
            renderPagination(totalPages);
        }

        function renderReviews(reviews) {
            if (!reviewsList) return;

            if (reviews.length === 0) {
                reviewsList.innerHTML = `
                    <div class="no-reviews">
                        <i class="fas fa-comment-slash"></i>
                        <h3>Tidak ada review</h3>
                        <p>Belum ada review untuk rating yang dipilih.</p>
                    </div>
                `;
                return;
            }

            reviewsList.innerHTML = reviews.map(review => `
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="${review.avatar}"
                                 class="review-avatar"
                                 alt="${review.name}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=FF581E&color=fff'">
                            <div class="reviewer-details">
                                <div class="reviewer-name">${review.name}</div>
                                <div class="review-date">${formatDate(review.date)}</div>
                            </div>
                        </div>
                        <div class="review-stars">
                            ${getStarIcons(review.rating)}
                        </div>
                    </div>
                    <div class="review-content">
                        ${review.content}
                    </div>
                </div>
            `).join('');
        }

        function renderPagination(totalPages) {
            if (!reviewPagination) return;

            if (totalPages <= 1) {
                reviewPagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            paginationHTML += `
                <button class="page-btn ${currentPage === 1 ? 'disabled' : ''}"
                        onclick="goToPage(${currentPage - 1})"
                        ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                        <button class="page-btn ${i === currentPage ? 'active' : ''}"
                                onclick="goToPage(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === 2 || i === totalPages - 1) {
                    paginationHTML += `<span class="page-dots">...</span>`;
                }
            }

            paginationHTML += `
                <button class="page-btn ${currentPage === totalPages ? 'disabled' : ''}"
                        onclick="goToPage(${currentPage + 1})"
                        ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            reviewPagination.innerHTML = paginationHTML;
        }

        function goToPage(page) {
            currentPage = page;
            loadReviews();
            reviewsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function submitReview() {
            if (selectedRating === 0) {
                alert('Silakan berikan rating terlebih dahulu!');
                return;
            }

            if (!reviewText.value.trim() || reviewText.value.trim().length < 10) {
                alert('Silakan tulis review Anda minimal 10 karakter!');
                reviewText.focus();
                return;
            }

            const newReview = {
                id: allReviews.length + 1,
                avatar: 'https://ui-avatars.com/api/?name={{ $user["name"] ?? "Guest" }}&background=FF581E&color=fff',
                name: '{{ $user["name"] ?? "Guest" }}',
                rating: selectedRating,
                date: new Date().toISOString().split('T')[0],
                content: reviewText.value.trim()
            };

            allReviews.unshift(newReview);
            resetReviewForm();
            currentPage = 1;
            loadReviews();
            alert('Terima kasih atas review Anda! Review telah berhasil dikirim.');
        }

        function resetReviewForm() {
            if (starRatingInput) {
                const stars = starRatingInput.querySelectorAll('i');
                stars.forEach(star => star.classList.remove('active'));
            }

            selectedRating = 0;
            ratingText.textContent = 'Pilih bintang';

            if (reviewText) {
                reviewText.value = '';
                charCount.textContent = '0/500 karakter';
                charCount.classList.remove('limit');
            }
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        function getStarIcons(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating ? '★' : '☆';
            }
            return stars;
        }

        window.goToPage = goToPage;

        /* ========== PROMO SLIDER ========== */
        const promoSlider = {
            track: document.getElementById('promo-track'),
            slides: document.querySelectorAll('.promo-slide'),
            dotsContainer: document.getElementById('slider-dots'),
            prevBtn: document.getElementById('prev-btn'),
            nextBtn: document.getElementById('next-btn'),
            currentIndex: 0,
            totalSlides: 0,
            autoSlideInterval: null,

            init: function() {
                this.totalSlides = this.slides.length;

                for (let i = 0; i < this.totalSlides; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'slider-dot';
                    if (i === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => this.goToSlide(i));
                    this.dotsContainer.appendChild(dot);
                }

                this.prevBtn.addEventListener('click', () => this.prevSlide());
                this.nextBtn.addEventListener('click', () => this.nextSlide());

                this.startAutoSlide();

                this.track.parentElement.addEventListener('mouseenter', () => this.stopAutoSlide());
                this.track.parentElement.addEventListener('mouseleave', () => this.startAutoSlide());

                this.track.addEventListener('touchstart', this.handleTouchStart.bind(this));
                this.track.addEventListener('touchmove', this.handleTouchMove.bind(this));

                this.updateSlider();
            },

            goToSlide: function(index) {
                this.currentIndex = index;
                this.updateSlider();
                this.resetAutoSlide();
            },

            prevSlide: function() {
                this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
                this.updateSlider();
                this.resetAutoSlide();
            },

            nextSlide: function() {
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
                this.updateSlider();
                this.resetAutoSlide();
            },

            updateSlider: function() {
                this.track.style.transform = `translateX(-${this.currentIndex * 100}%)`;

                const dots = this.dotsContainer.querySelectorAll('.slider-dot');
                dots.forEach((dot, index) => {
                    if (index === this.currentIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            },

            startAutoSlide: function() {
                this.stopAutoSlide();
                this.autoSlideInterval = setInterval(() => this.nextSlide(), 5000);
            },

            stopAutoSlide: function() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                    this.autoSlideInterval = null;
                }
            },

            resetAutoSlide: function() {
                this.stopAutoSlide();
                this.startAutoSlide();
            },

            touchStartX: 0,
            touchEndX: 0,

            handleTouchStart: function(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            },

            handleTouchMove: function(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
            },

            handleSwipe: function() {
                const threshold = 50;
                const diff = this.touchStartX - this.touchEndX;

                if (Math.abs(diff) > threshold) {
                    if (diff > 0) {
                        this.nextSlide();
                    } else {
                        this.prevSlide();
                    }
                }

                this.touchStartX = 0;
                this.touchEndX = 0;
            }
        };

        if (document.getElementById('promo-track')) {
            promoSlider.init();
        }

        /* ========== CEK HARGA PAKET AJAX ========== */
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

                if ((panjang > 0 || lebar > 0 || tinggi > 0) &&
                    (panjang <= 0 || lebar <= 0 || tinggi <= 0)) {
                    alert('Jika mengisi dimensi, semua kolom panjang, lebar, dan tinggi harus diisi!');
                    return;
                }

                const originalText = btnCekHarga.innerHTML;
                btnCekHarga.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghitung...';
                btnCekHarga.disabled = true;

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
                    btnCekHarga.innerHTML = originalText;
                    btnCekHarga.disabled = false;

                    if (data.success) {
                        hasilPerhitungan.style.display = 'block';
                        document.getElementById('harga-total').textContent = data.data.harga_total;
                        hasilPerhitungan.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        hasilPerhitungan.classList.add('show');

                        $('#asal-paket').val('').trigger('change');
                        $('#tujuan-paket').val('').trigger('change');
                        document.getElementById('berat-paket').value = '0.1';
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

        if (btnCekUlang) {
            btnCekUlang.addEventListener('click', function() {
                $('#asal-paket').val('').trigger('change');
                $('#tujuan-paket').val('').trigger('change');
                document.getElementById('berat-paket').value = '0.1';
                document.getElementById('panjang-paket').value = '';
                document.getElementById('lebar-paket').value = '';
                document.getElementById('tinggi-paket').value = '';
                hasilPerhitungan.style.display = 'none';
                document.getElementById('form-kirim-paket').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        /* ========== SELECT2 INITIALIZATION ========== */
        $(document).ready(function() {
            if ($('#asal-paket').length) {
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
            }

            if ($('#departure-outlet').length) {
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
            }
        });

        /* ========== SESSION MESSAGES ========== */
        const successMsg = @json(session('success'));
        const errorMsg = @json(session('error'));

        if (successMsg) {
            setTimeout(() => alert(successMsg), 500);
        }
        if (errorMsg) {
            setTimeout(() => alert(errorMsg), 500);
        }
    });
    </script>
</body>
</html>
