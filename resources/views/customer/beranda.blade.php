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
            --success-color: #28a745;
            --danger-color: #dc3545;
            --modal-bg: rgba(74, 66, 62, 0.50); /* 95% opacity */
        }

        /* Reset margin dan padding untuk body */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: transparent; /* UBAH: dari white menjadi transparent */
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
            box-shadow: none; /* HAPUS: shadow default */
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
            background: rgba(255, 255, 255, 0.9); /* UBAH: semi-transparan dengan blur effect */
            border-radius: 50px;
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px); /* TAMBAH: efek blur untuk glassmorphism */
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
            font-family: inherit;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        /* Navbar saat di-scroll - LEBIH TRANSPARAN */
        .custom-navbar.scrolled {
            background: rgba(255, 255, 255, 0.95); /* Sedikit lebih solid saat di-scroll */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .custom-navbar.scrolled .nav-panel {
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
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
        }

        .hero-desc {
            font-size: 18px;
            line-height: 1.7;
            max-width: 520px;
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
            border: 2px solid #e0e0e0 !important;
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
        }

        /* Container untuk teks (tanpa container ikon) */
        .btn-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            width: 100%;
        }

        /* Hapus container ikon karena tidak perlu */
        .btn-icon {
            display: none;
        }

        /* Teks utama tombol */
        .btn-main-text {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
            color: inherit;
            text-align: left;
            width: 100%;
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
        }

        /* Untuk tombol Cek Paket (default state) */
        #btn-cek-paket .btn-main-text,
        #btn-cek-paket .btn-label {
            color: var(--secondary-color);
        }

        /* Untuk tombol Kirim Paket (default state) */
        #kirim-paket-form .search-btn:not(#btn-cek-paket) .btn-main-text,
        #kirim-paket-form .search-btn:not(#btn-cek-paket) .btn-label {
            color: var(--secondary-color);
        }

        /* Hover state untuk kedua tombol */
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
        }

        .form-control::placeholder {
            color: rgba(87, 65, 65, 0.7);
            text-align: left;
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
        }

        /* Input container - DIUBAH: mengambil sisa space */
        .form-input-container {
            flex: 1;
            min-width: 0;
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
        }

        .select2-container--default .select2-selection--single.select2-modal .select2-selection__rendered {
            line-height: 48px !important;
            color: #333 !important;
            font-size: 15px !important;
            padding-left: 18px !important;
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
        }

        /* Harga total display - DIUBAH: tetap di satu baris */
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
        }

        .harga-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary-color);
            white-space: nowrap;
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

        /* Hover effect untuk rows tabel */
        .hasil-harga-container tr:hover td {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Responsive untuk tabel hasil */
        @media (max-width: 768px) {
            .hasil-harga-container table {
                font-size: 12px;
            }

            .harga-container {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .harga-label {
                font-size: 16px;
            }

            .harga-value {
                font-size: 20px;
            }
        }

        /* Responsive untuk modal */
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

            #kirim-paket-form .search-row {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }

            .modal-cek-paket,
            .modal-kirim-paket {
                position: relative;
                top: auto;
                left: auto;
                right: auto;
                margin-top: 15px;
                min-height: fit-content;
            }

            .search-container {
                padding: 20px;
            }

            #kirim-paket-form .search-btn {
                height: auto !important;
                min-height: fit-content;
            }

            .modal-header {
                padding: 20px;
            }

            .modal-body {
                padding: 20px;
            }

            /* Responsive untuk form horizontal */
            .resi-input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-cek-resi,
            .btn-cek-harga {
                width: 100%;
            }

            /* Responsive untuk form group dalam satu baris */
            .form-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .form-label {
                min-width: 100%;
                margin-bottom: 0;
            }

            .form-input-container {
                width: 100%;
            }

            /* Responsive untuk volume container */
            .volume-container {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .search-row {
                grid-template-columns: 1fr;
            }

            .search-btn-container {
                grid-column: span 1;
            }

            .modal-cek-paket,
            .modal-kirim-paket {
                min-height: fit-content;
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
                padding: 12px 15px;
                font-size: 14px;
                width: 100%;
                height: 44px;
            }

            #kirim-paket-form .search-btn {
                padding: 12px 15px;
                min-height: fit-content;
            }

            .harga-container {
                padding: 12px 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .harga-label {
                font-size: 14px;
            }

            .harga-value {
                font-size: 18px;
            }
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
        }

        .features-grid-6 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-align: center;
            color: #333;
            position: relative;
            overflow: hidden;
            border: 2px solid #e0e0e0;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            border-color: var(--secondary-color);
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
            color: var(--secondary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
        }

        .feature-label {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--secondary-color);
            line-height: 1.4;
        }

        .feature-desc {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin: 0;
        }

        /* === REVIEW SECTION YANG DIRAPIHKAN === */
        .feedback-section {
            padding: 80px 40px;
            background: white;
        }

        .feedback-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.12);
            padding: 50px;
            border: 2px solid #e0e0e0;
        }

        .feedback-title {
            font-size: 32px;
            color: var(--secondary-color);
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
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            align-items: start;
        }

        /* KIRI: REVIEW LIST CONTAINER */
        .review-list-container {
            background: #fff;
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
            color: var(--primary-color);
            margin-bottom: 15px;
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
            background: white;
            border-radius: 25px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
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
        }

        .stat-label {
            font-size: 12px;
            color: #666;
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
            background: #f9f9f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--secondary-color);
            transition: all 0.3s ease;
        }

        .review-item:hover {
            background: #fff;
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
            color: var(--primary-color);
            margin-bottom: 3px;
        }

        .review-date {
            font-size: 12px;
            color: #888;
        }

        .review-stars {
            color: #ffc107;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .review-content {
            color: #444;
            line-height: 1.6;
            font-size: 14px;
            margin-top: 10px;
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
        }

        .no-reviews p {
            font-size: 14px;
            color: #888;
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
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            color: #666;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
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
        }

        /* KANAN: REVIEW FORM */
        .review-form-container {
            background: #fff;
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
            color: var(--secondary-color);
        }

        .form-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
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
            color: #666;
            margin-left: 10px;
            font-weight: 500;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 25px;
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            resize: vertical;
            min-height: 120px;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(255, 88, 30, 0.1);
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: #888;
            margin-top: 5px;
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
        }

        .form-notice i {
            color: var(--secondary-color);
            margin-right: 8px;
        }

        /* Style untuk hasil perhitungan yang lebih sederhana - SAMA DENGAN TOMBOL CEK HARGA + 3px */
        .total-harga-container {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 14px 20px;
            margin: 15px 0;
            border: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            min-height: 51px; /* 48px (tombol) + 3px */
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
        }

        .total-harga-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 4px;
            display: block;
            line-height: 1.2;
        }

        .total-harga-desc {
            font-size: 10px;
            color: #aaa;
            margin-top: 4px;
            display: block;
            line-height: 1.2;
        }

        .success-icon {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 6px;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .review-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .review-form-container {
                position: static;
            }

            .review-stats {
                gap: 15px;
            }

            .stat-value {
                font-size: 20px;
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

            .services-grid {
                flex-direction: column;
                align-items: center;
            }

            .service-card {
                width: 100%;
                max-width: 400px;
            }

            .features-grid-6 {
                grid-template-columns: repeat(2, 1fr);
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

            .hero-section {
                height: auto;
                min-height: 100vh;
                padding: 120px 20px 60px;
            }

            .hero-content {
                max-width: 100%;
                text-align: center;
            }

            .hero-title {
                font-size: 36px;
            }

            .hero-desc {
                font-size: 16px;
                margin: 0 auto;
            }

            .hero-services {
                justify-content: center;
                max-width: 100%;
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

            .star-filter-buttons {
                justify-content: center;
            }

            .review-stats {
                flex-wrap: wrap;
                gap: 15px;
            }

            .stat-item {
                flex: 0 0 calc(50% - 15px);
            }

            .review-item {
                padding: 15px;
            }

            .review-header {
                flex-direction: column;
                gap: 10px;
            }

            .review-form-container {
                padding: 20px;
            }

            .total-harga-container {
                padding: 8px 15px;
                margin: 10px 0;
            }

            .success-icon {
                font-size: 22px;
                margin-bottom: 6px;
            }

            .total-harga-label {
                font-size: 11px;
                margin-bottom: 4px;
            }

            .total-harga-value {
                font-size: 16px;
                margin-bottom: 4px;
            }

            .total-harga-desc {
                font-size: 9px;
                margin-top: 4px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 28px;
            }

            .hero-desc {
                font-size: 14px;
            }

            .hero-service {
                min-width: 90px;
                padding: 10px;
            }

            .hero-service i {
                font-size: 24px;
            }

            .hero-service span {
                font-size: 12px;
            }

            .search-section {
                margin-top: -100px;
            }

            .search-container {
                padding: 15px;
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

            .star-rating-input i {
                font-size: 28px;
            }

            .btn-primary,
            .btn-close {
                padding: 12px;
                font-size: 16px;
            }

            .star-filter-btn {
                padding: 6px 12px;
                font-size: 13px;
            }

            .stat-item {
                flex: 0 0 100%;
            }

            .reviewer-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
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
            color: #00215E;
            padding: 50px 40px 20px;
            margin-top: auto;
            border-top: 2px solid #00215E;
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
        }

        .footer-subtitle {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .footer-text {
            font-size: 14px;
            color: #ffffffff;
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
            color: #ffffffff;
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
        }

        .footer-link:hover {
            color: var(--secondary-color);
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

        /* Profile icon + small name - PERBAIKAN */
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

        /* Dropdown Menu - PERBAIKAN */
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
            font-family: inherit;
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

        /* Pastikan tombol login di navbar bisa diklik */
        .nav-auth a.btn-login {
            pointer-events: auto !important;
            position: relative;
            z-index: 10;
        }
    </style>
    @php
        use App\Models\MProfilePerusahaan;
        $profile = MProfilePerusahaan::first();

        // Data user dari session
        $user = session()->get('user', null);

        // Data review dan statistik (contoh data statis)
        $totalReviews = 24;
        $averageRating = 4.8;
        $reviewStats = [
            5 => 18,
            4 => 4,
            3 => 1,
            2 => 1,
            1 => 0
        ];

        // Data review dari database (contoh)
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
            ],
            [
                'id' => 4,
                'avatar' => 'https://randomuser.me/api/portraits/men/45.jpg',
                'name' => 'Budi Santoso',
                'rating' => 5,
                'date' => '2024-03-12',
                'content' => 'Sangat puas dengan layanan SmartShuttle. Armada bersih, driver ramah, dan tepat waktu. Sudah beberapa kali menggunakan dan selalu puas.'
            ],
            [
                'id' => 5,
                'avatar' => 'https://randomuser.me/api/portraits/women/55.jpg',
                'name' => 'Maya Indah',
                'rating' => 4,
                'date' => '2024-03-11',
                'content' => 'Pelayanan bagus, harga terjangkau. Cuma kadang agak telat sedikit, tapi masih dalam batas wajar. Overall recommended!'
            ],
            [
                'id' => 6,
                'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'name' => 'Ahmad Fauzi',
                'rating' => 5,
                'date' => '2024-03-10',
                'content' => 'SmartShuttle membantu sekali untuk perjalanan bisnis saya. Jadwal fleksibel, booking mudah, dan selalu on time. Terima kasih!'
            ],
            [
                'id' => 7,
                'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'name' => 'Siti Rahayu',
                'rating' => 5,
                'date' => '2024-03-09',
                'content' => 'Pengiriman paket sangat cepat dan aman. Driver ramah dan profesional. Harga juga terjangkau. Sangat recommended!'
            ],
            [
                'id' => 8,
                'avatar' => 'https://randomuser.me/api/portraits/men/65.jpg',
                'name' => 'Hendra Wijaya',
                'rating' => 4,
                'date' => '2024-03-08',
                'content' => 'Layanan shuttle sangat nyaman, AC dingin, kursi empuk. Perjalanan Jakarta-Bandung jadi tidak melelahkan.'
            ],
            [
                'id' => 9,
                'avatar' => 'https://randomuser.me/api/portraits/women/29.jpg',
                'name' => 'Dewi Lestari',
                'rating' => 5,
                'date' => '2024-03-07',
                'content' => 'Sudah langganan 2 tahun, selalu puas. Tidak pernah telat dan armada selalu dalam kondisi bersih.'
            ]
        ]);
    @endphp
</head>
<body>
    <!-- Custom Navbar TRANSPARAN -->
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
            <h1 class="hero-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h1>
            <p class="hero-desc">
                {{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}
            </p>
            <div class="hero-services">
                <a href="{{ url()->current() }}?service=shuttle" class="hero-service" id="shuttle-link">
                    <i class="fas fa-shuttle-van"></i>
                    <span>Tiket Shuttle</span>
                </a>
                <a href="{{ url()->current() }}?service=kirim-paket" class="hero-service" id="kirim-paket-link">
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
            <!-- Form Tiket Shuttle (Default) -->
            <form action="{{ route('customer.search') }}" method="GET" id="search-form" class="service-form" data-service="shuttle">
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
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> CEK SHUTTLE
                        </button>
                    </div>
                </div>
            </form>

            <!-- Form Kirim Paket (Hidden by Default) -->
            <form action="{{ route('customer.kirim-paket') }}" method="GET" id="kirim-paket-form" class="service-form" data-service="kirim-paket" style="display: none;">
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
                <div class="feature-card {
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
                <div class="feature-card {
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-label">Bantuan 24/7</h3>
                    <p class="feature-desc">Tim kami selalu siap membantu setiap langkah perjalananmu.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- === FEEDBACK SECTION YANG DIRAPIHKAN DENGAN FILTER === -->
    <section class="feedback-section">
        <div class="feedback-container">
            <h2 class="feedback-title">Review Pelanggan</h2>
            <div class="feedback-line"></div>

            <div class="review-wrapper">
                <!-- KIRI: DAFTAR REVIEW DENGAN FILTER -->
                <div class="review-list-container">
                    <!-- FILTER BINTANG -->
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

                        <!-- INFO STATISTIK -->
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

                    <!-- DAFTAR REVIEW -->
                    <div class="reviews-list" id="reviews-list">
                        <!-- Review akan dimuat via JavaScript -->
                        <div class="loading-reviews">
                            <i class="fas fa-spinner fa-spin"></i> Memuat review...
                        </div>
                    </div>

                    <!-- PAGINATION -->
                    <div class="review-pagination" id="review-pagination">
                        <!-- Pagination akan di-generate oleh JavaScript -->
                    </div>
                </div>

                <!-- KANAN: FORM REVIEW -->
                <div class="review-form-container">
                    <div class="form-title">Berikan Penilaian Anda</div>
                    <p class="form-subtitle">Bagikan pengalaman Anda menggunakan layanan kami</p>

                    <!-- RATING INPUT -->
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

                    <!-- INPUT REVIEW -->
                    <div class="form-group">
                        <textarea class="form-textarea" id="review-text"
                                  placeholder="Ceritakan pengalaman Anda menggunakan layanan kami... (Minimal 10 karakter)"
                                  rows="5"></textarea>
                        <div class="char-count" id="char-count">0/500 karakter</div>
                    </div>

                    <!-- BUTTONS -->
                    <button class="btn-primary" id="submit-review">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Review
                    </button>
                    <button class="btn-close" id="reset-review">
                        <i class="fas fa-redo"></i>
                        Reset Form
                    </button>

                    <!-- NOTICE -->
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

        /* ---------- PROFILE DROPDOWN - PERBAIKAN ---------- */
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

            // Hanya tutup dropdown jika klik di luar dropdown DAN tombol profile
            document.addEventListener('click', function (e) {
                if (dropdownMenu.classList.contains('show')) {
                    // Jika klik di luar dropdown DAN di luar tombol profile
                    if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownButton.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Tutup dropdown saat tombol Escape ditekan
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    dropdownButton.focus();
                }
            });

            // Tutup dropdown saat item dipilih
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

        /* ---------- SERVICE SWITCHER ---------- */
        const shuttleLink = document.getElementById('shuttle-link');
        const kirimPaketLink = document.getElementById('kirim-paket-link');
        const serviceForms = document.querySelectorAll('.service-form');
        const heroServices = document.querySelectorAll('.hero-service');
        const heroTitle = document.querySelector('.hero-title');
        const heroDesc = document.querySelector('.hero-desc');
        const urlParams = new URLSearchParams(window.location.search);
        const activeService = urlParams.get('service') || 'shuttle';

        // Function to switch between forms
        function switchService(serviceType) {
            // Hide all forms
            serviceForms.forEach(form => {
                form.style.display = 'none';
            });

            // Show selected form
            const activeForm = document.querySelector(`.service-form[data-service="${serviceType}"]`);
            if (activeForm) {
                activeForm.style.display = 'block';
            }

            // Tutup kedua modal jika terbuka
            if (modalCekPaket) {
                modalCekPaket.classList.remove('show');
                btnCekPaket.style.visibility = 'visible';

                const searchFieldCek = btnCekPaket.closest('.search-field');
                if (searchFieldCek) {
                    searchFieldCek.style.height = '';
                    searchFieldCek.style.minHeight = '';
                }
            }

            if (modalKirimPaket) {
                modalKirimPaket.classList.remove('show');
                btnKirimPaket.style.visibility = 'visible';

                const searchFieldKirim = btnKirimPaket.closest('.search-field');
                if (searchFieldKirim) {
                    searchFieldKirim.style.height = '';
                    searchFieldKirim.style.minHeight = '';
                }
            }

            // Update hero title and description based on service
            updateHeroContent(serviceType);

            // Update URL without reloading page
            const newUrl = new URL(window.location);
            if (serviceType === 'shuttle') {
                newUrl.searchParams.delete('service');
            } else {
                newUrl.searchParams.set('service', serviceType);
            }
            window.history.pushState({}, '', newUrl);

            // Update hero services active state
            updateHeroServicesActiveState(serviceType);

            // Reinitialize Select2 for visible form
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
                }
            }, 100);
        }

        // Function to update hero content based on service
        function updateHeroContent(serviceType) {
            if (heroTitle && heroDesc) {
                if (serviceType === 'kirim-paket') {
                    // Ubah untuk SmartSend
                    heroTitle.textContent = 'SmartSend';
                    heroDesc.textContent = 'Setiap kiriman punya tujuan — Paket terkirim cepat, aman, dan terpantau.';
                } else {
                    // Kembalikan ke default
                    heroTitle.textContent = '{{ $profile->nama_dagang ?? 'Smart Shuttle' }}';
                    heroDesc.textContent = '{{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}';
                }
            }
        }

        // Function to update active state in hero services
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

        // Initialize based on URL parameter
        if (activeService === 'kirim-paket') {
            switchService('kirim-paket');
        } else {
            switchService('shuttle');
        }

        // Handle click on Tiket Shuttle link
        if (shuttleLink) {
            shuttleLink.addEventListener('click', function(e) {
                e.preventDefault();
                switchService('shuttle');

                // Scroll to search section
                document.querySelector('.search-section').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }

        // Handle click on Kirim Paket link
        if (kirimPaketLink) {
            kirimPaketLink.addEventListener('click', function(e) {
                e.preventDefault();
                switchService('kirim-paket');

                // Scroll to search section
                document.querySelector('.search-section').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const service = urlParams.get('service') || 'shuttle';
            switchService(service);
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

        /* === REVIEW MANAGEMENT SCRIPT === */
        // Data review (gunakan data dari server jika ada)
        let allReviews = @json($reviews ?? []);

        // Jika tidak ada data dari server, gunakan fallback
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
                },
                {
                    id: 3,
                    avatar: 'https://randomuser.me/api/portraits/women/68.jpg',
                    name: 'Sari Dewi',
                    rating: 5,
                    date: '2024-03-13',
                    content: 'Harganya menurut saya cukup murah dibanding shuttle lain, tapi kualitas layanannya tetap bagus. Pemesanan lewat aplikasi juga gampang.'
                },
                {
                    id: 4,
                    avatar: 'https://randomuser.me/api/portraits/men/45.jpg',
                    name: 'Budi Santoso',
                    rating: 5,
                    date: '2024-03-12',
                    content: 'Sangat puas dengan layanan SmartShuttle. Armada bersih, driver ramah, dan tepat waktu. Sudah beberapa kali menggunakan dan selalu puas.'
                },
                {
                    id: 5,
                    avatar: 'https://randomuser.me/api/portraits/women/55.jpg',
                    name: 'Maya Indah',
                    rating: 4,
                    date: '2024-03-11',
                    content: 'Pelayanan bagus, harga terjangkau. Cuma kadang agak telat sedikit, tapi masih dalam batas wajar. Overall recommended!'
                },
                {
                    id: 6,
                    avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
                    name: 'Ahmad Fauzi',
                    rating: 5,
                    date: '2024-03-10',
                    content: 'SmartShuttle membantu sekali untuk perjalanan bisnis saya. Jadwal fleksibel, booking mudah, dan selalu on time. Terima kasih!'
                },
                {
                    id: 7,
                    avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
                    name: 'Siti Rahayu',
                    rating: 5,
                    date: '2024-03-09',
                    content: 'Pengiriman paket sangat cepat dan aman. Driver ramah dan profesional. Harga juga terjangkau. Sangat recommended!'
                },
                {
                    id: 8,
                    avatar: 'https://randomuser.me/api/portraits/men/65.jpg',
                    name: 'Hendra Wijaya',
                    rating: 4,
                    date: '2024-03-08',
                    content: 'Layanan shuttle sangat nyaman, AC dingin, kursi empuk. Perjalanan Jakarta-Bandung jadi tidak melelahkan.'
                },
                {
                    id: 9,
                    avatar: 'https://randomuser.me/api/portraits/women/29.jpg',
                    name: 'Dewi Lestari',
                    rating: 5,
                    date: '2024-03-07',
                    content: 'Sudah langganan 2 tahun, selalu puas. Tidak pernah telat dan armada selalu dalam kondisi bersih.'
                }
            ];
        }

        // State variables
        let currentFilter = 0; // 0 = semua
        let currentPage = 1;
        const reviewsPerPage = 4;
        let selectedRating = 0;

        // DOM Elements
        const reviewsList = document.getElementById('reviews-list');
        const reviewPagination = document.getElementById('review-pagination');
        const filterButtons = document.querySelectorAll('.star-filter-btn');
        const starRatingInput = document.getElementById('star-rating');
        const ratingText = document.getElementById('rating-text');
        const reviewText = document.getElementById('review-text');
        const charCount = document.getElementById('char-count');
        const submitReviewBtn = document.getElementById('submit-review');
        const resetReviewBtn = document.getElementById('reset-review');

        // Initialize
        initReviewSection();

        function initReviewSection() {
            // Setup filter buttons
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active state
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Update filter
                    currentFilter = parseInt(this.dataset.rating);
                    currentPage = 1;

                    // Reload reviews
                    loadReviews();
                });
            });

            // Setup star rating input
            if (starRatingInput) {
                const stars = starRatingInput.querySelectorAll('i');
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.rating);
                        selectedRating = rating;

                        // Update star display
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });

                        // Update rating text
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

            // Setup character counter
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

            // Setup submit button
            if (submitReviewBtn) {
                submitReviewBtn.addEventListener('click', submitReview);
            }

            // Setup reset button
            if (resetReviewBtn) {
                resetReviewBtn.addEventListener('click', resetReviewForm);
            }

            // Load initial reviews
            loadReviews();
        }

        function loadReviews() {
            // Filter reviews
            let filteredReviews = allReviews;
            if (currentFilter > 0) {
                filteredReviews = allReviews.filter(review => review.rating === currentFilter);
            }

            // Calculate pagination
            const totalPages = Math.ceil(filteredReviews.length / reviewsPerPage);
            const startIndex = (currentPage - 1) * reviewsPerPage;
            const endIndex = startIndex + reviewsPerPage;
            const pageReviews = filteredReviews.slice(startIndex, endIndex);

            // Render reviews
            renderReviews(pageReviews);

            // Render pagination
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

            // Previous button
            paginationHTML += `
                <button class="page-btn ${currentPage === 1 ? 'disabled' : ''}"
                        onclick="goToPage(${currentPage - 1})"
                        ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
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

            // Next button
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
            // Validation
            if (selectedRating === 0) {
                alert('Silakan berikan rating terlebih dahulu!');
                return;
            }

            if (!reviewText.value.trim() || reviewText.value.trim().length < 10) {
                alert('Silakan tulis review Anda minimal 10 karakter!');
                reviewText.focus();
                return;
            }

            // Simulate API call
            const newReview = {
                id: allReviews.length + 1,
                avatar: 'https://ui-avatars.com/api/?name={{ $user["name"] ?? "Guest" }}&background=FF581E&color=fff',
                name: '{{ $user["name"] ?? "Guest" }}',
                rating: selectedRating,
                date: new Date().toISOString().split('T')[0],
                content: reviewText.value.trim()
            };

            // Add to reviews (in real app, this would be an AJAX call)
            allReviews.unshift(newReview);

            // Reset form
            resetReviewForm();

            // Reload reviews
            currentPage = 1;
            loadReviews();

            // Show success message
            alert('Terima kasih atas review Anda! Review telah berhasil dikirim.');
        }

        function resetReviewForm() {
            // Reset stars
            if (starRatingInput) {
                const stars = starRatingInput.querySelectorAll('i');
                stars.forEach(star => star.classList.remove('active'));
            }

            // Reset rating
            selectedRating = 0;
            ratingText.textContent = 'Pilih bintang';

            // Reset textarea
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

        // Make goToPage available globally
        window.goToPage = goToPage;
    });

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

    // Initialize Select2 for default form
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
