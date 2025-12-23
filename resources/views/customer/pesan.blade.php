@extends('layouts.app')

@section('title', 'Pesan Shuttle - Smart Shuttle')

@push('styles')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - Smart Shuttle</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon sosial media -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset dan gaya dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navigation - Mengambang */
        .nav-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 40px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Gaya untuk logo */
        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-image {
            height: 60px;
            width: auto;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
        }

        .nav-item {
            color: #123352;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 0;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-item:hover {
            color: #FF581E;
        }

        .nav-item.active {
            color: #FF581E;
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #FF581E;
            border-radius: 2px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-circle {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #FF581E 0%, #ff7b4d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
        }

        /* Responsif untuk Navbar */
        @media (max-width: 768px) {
            .nav-container {
                padding: 15px 20px;
            }

            .nav-menu {
                gap: 15px;
            }

            .logo-image {
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .nav-menu {
                gap: 10px;
            }

            .nav-item {
                font-size: 14px;
            }

            .logo-image {
                height: 40px;
            }
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 20px;
            flex: 1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Row dengan gap */
        .row.g-4 {
            gap: 20px;
        }

        /* Card Styling - DIUBAH untuk layout sejajar */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            margin-bottom: 0;
            transition: box-shadow 0.3s ease;
            background: white;
            height: 100%;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Form Card - Box Shadow yang Lebih Jelas */
        .form-card {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0,0,0,0.08);
            background: white;
        }

        /* Section Header Styling */
        .section-header {
            color: #00215E;
            border-bottom: 2px solid #FF581E;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 18px;
        }

        /* Detail Perjalanan - Layout yang Dirapikan */
        .detail-item {
            display: flex;
            margin-bottom: 10px;
            align-items: flex-start;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 100px;
        }

        .detail-value {
            font-weight: 500;
            color: #333;
            flex: 1;
        }

        /* Badge Fasilitas Shuttle */
        .fasilitas-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .fasilitas-badge {
            background: linear-gradient(135deg, #00215E 0%, #1a3d7c 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 2px 4px rgba(0, 33, 94, 0.2);
        }

        .fasilitas-badge i {
            font-size: 10px;
        }

        .fasilitas-badge.ac {
            background: linear-gradient(135deg, #00215E 0%, #1a3d7c 100%);
        }

        .fasilitas-badge.wifi {
            background: linear-gradient(135deg, #FF581E 0%, #ff7b4d 100%);
        }

        .fasilitas-badge.charger {
            background: linear-gradient(135deg, #28a745 0%, #4caf50 100%);
        }

        .fasilitas-badge.toilet {
            background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
        }

        .fasilitas-badge.reclining-seat {
            background: linear-gradient(135deg, #17a2b8 0%, #3dc8e0 100%);
        }

        .fasilitas-badge.tv {
            background: linear-gradient(135deg, #fd7e14 0%, #ff9a4d 100%);
        }

        .fasilitas-badge.blanket {
            background: linear-gradient(135deg, #e83e8c 0%, #f06ba8 100%);
        }

        .fasilitas-badge.snack {
            background: linear-gradient(135deg, #20c997 0%, #4de0b6 100%);
        }

        .fasilitas-badge.driver {
            background: linear-gradient(135deg, #343a40 0%, #5a6268 100%);
        }

        .fasilitas-badge.default {
            background: linear-gradient(135deg, #6c757d 0%, #868e96 100%);
        }

        /* Informasi Harga */
        .price-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-orange {
            background-color: #FF581E;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-orange:hover {
            background-color: #e54e1a;
            color: white;
        }

        .btn-outline-orange {
            background-color: transparent;
            color: #FF581E;
            border: 2px solid #FF581E;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-outline-orange:hover {
            background-color: #FF581E;
            color: white;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #FF581E;
            box-shadow: 0 0 0 0.2rem rgba(255, 88, 30, 0.25);
        }

        /* Form Label Styling */
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            display: block;
            font-size: 14px;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 20px;
        }

        /* Promo Section */
        .promo-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #FF581E;
        }

        .promo-title {
            font-size: 16px;
            font-weight: 600;
            color: #00215E;
            margin-bottom: 15px;
        }

        .promo-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .promo-input {
            flex: 1;
        }

        .promo-success {
            color: #28a745;
            font-size: 14px;
            font-weight: 500;
            margin-top: 8px;
            display: none;
        }

        .promo-error {
            color: #dc3545;
            font-size: 14px;
            font-weight: 500;
            margin-top: 8px;
            display: none;
        }

        /* Rute Display */
        .route-display {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #FFE8E0 0%, #FFF0EB 100%);
            border-radius: 12px;
            border: 2px solid #FF581E;
        }

        .city-name {
            font-size: 18px;
            font-weight: 700;
            color: #00215E;
            display: inline-block;
        }

        .route-arrow {
            color: #FF581E;
            font-size: 18px;
            margin: 0 10px;
            font-weight: bold;
        }

        /* Journey Details */
        .journey-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        /* Shuttle Info */
        .shuttle-info {
            background: #f0f7ff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #00215E;
        }

        /* Price Section */
        .price-section {
            background: #fff8f0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #FFE0D6;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .price-label {
            color: #555;
        }

        .price-value {
            font-weight: 500;
            color: #333;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 2px solid #FF581E;
            margin-top: 15px;
            font-size: 16px;
        }

        .total-label {
            font-weight: 700;
            color: #00215E;
        }

        .total-value {
            font-weight: 700;
            color: #FF581E;
        }

        /* Penumpang Group */
        .penumpang-group {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }

        .penumpang-title {
            color: #00215E;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .penumpang-number {
            background: #FF581E;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        /* Summary Section */
        .summary-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .summary-item.total {
            font-weight: 700;
            font-size: 16px;
            color: #00215E;
        }

        .btn-submit {
            background-color: #FF581E;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #e54e1a;
            color: white;
        }

        .btn-submit:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        /* Error Messages */
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Form Row */
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-col {
            flex: 1;
        }

        /* Alert */
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #ffe6e6;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: #e6ffed;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Promo Applied */
        .promo-applied {
            background: #e7f5ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #74c0fc;
        }

        /* Promo Info */
        .promo-info {
            margin-top: 10px;
            color: #6c757d;
            font-size: 12px;
        }

        /* Layout untuk card sejajar */
        .card-container {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .left-column {
            flex: 0 0 41.666667%; /* 5/12 dari grid */
        }

        .right-column {
            flex: 0 0 58.333333%; /* 7/12 dari grid */
        }

        /* Footer - Clean Style */
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

        /* Responsif */
        @media (max-width: 992px) {
            .card-container {
                flex-direction: column;
            }

            .left-column, .right-column {
                flex: 0 0 100%;
                width: 100%;
            }

            .main-content {
                margin-top: 80px;
                padding: 15px;
            }

            .fasilitas-badges {
                gap: 6px;
            }

            .fasilitas-badge {
                font-size: 11px;
                padding: 4px 10px;
            }
        }

        @media (max-width: 768px) {
            .form-row, .promo-input-group {
                flex-direction: column;
                gap: 10px;
            }

            .footer-main {
                flex-direction: column;
                gap: 30px;
                margin-bottom: 30px;
            }

            .footer-column {
                width: 100%;
            }

            .promo-input-group {
                flex-direction: column;
            }

            .promo-input-group .btn-outline-orange {
                width: 100%;
            }

            .fasilitas-badge {
                font-size: 10px;
                padding: 3px 8px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                margin-top: 70px;
                padding: 10px;
            }

            .card {
                padding: 15px;
            }

            .section-header {
                font-size: 16px;
            }

            .city-name {
                font-size: 16px;
            }

            .fasilitas-badges {
                justify-content: center;
            }
        }

        /* CSS untuk Input Error Telepon */
        .telepon-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Styling untuk input telepon yang valid */
        .is-valid-telepon {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }

        /* Style untuk input type tel */
        input[type="tel"] {
            font-family: monospace;
            letter-spacing: 1px;
        }

        /* Auto format untuk input telepon */
        .telepon-formatted {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
        }

        /* Promo Loading Spinner */
        .promo-loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        /* Style untuk checkbox Sama dengan Pemesan */
        .same-as-pemesan-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #00215E;
            cursor: pointer;
            accent-color: #FF581E;
        }

        .same-as-pemesan-checkbox:checked {
            background-color: #FF581E;
            border-color: #FF581E;
        }

        .same-as-pemesan-checkbox:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 88, 30, 0.25);
        }

        .same-as-pemesan-label {
            font-size: 13px;
            cursor: pointer;
            color: #00215E;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .same-as-pemesan-label:hover {
            color: #FF581E;
        }

        /* Checkbox disabled state */
        .same-as-pemesan-checkbox:disabled {
            cursor: not-allowed;
            opacity: 0.6;
            border-color: #cccccc;
        }

        .same-as-pemesan-label.disabled {
            color: #999;
            cursor: not-allowed;
        }

        .same-as-pemesan-label.disabled:hover {
            color: #999;
        }

        /* Style untuk form-check inline */
        .form-check-inline {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Style untuk penumpang group header */
        .penumpang-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        /* Auto-filled field styling */
        .auto-filled {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
        }

        /* Checked state styling */
        .checkbox-checked {
            color: #28a745 !important;
            font-weight: 600 !important;
        }

        /* Success badge */
        .success-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Exclusive checkbox styling */
        .exclusive-checkbox {
            border-color: #FF581E;
            position: relative;
        }

        .exclusive-checkbox:checked::after {
            content: '✓';
            color: white;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
        }

        /* Warning message for exclusive checkbox */
        .exclusive-warning {
            color: #FF581E;
            font-size: 11px;
            margin-top: 5px;
            font-style: italic;
            display: none;
        }

        /* Tambahan untuk email di form penumpang */
        .email-field {
            display: none;
        }

        .email-field.show {
            display: block;
        }

        /* Feedback untuk perubahan field */
        .field-feedback {
            color: #FF581E;
            font-size: 11px;
            margin-top: 5px;
            display: block;
        }
    </style>
@endpush

@section('content')
<!-- Main Content -->
<div class="main-content">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(!isset($jadwal) || !$jadwal)
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Data jadwal tidak ditemukan.
                <a href="{{ route('customer.search') }}" class="alert-link">Kembali ke pencarian</a>
            </div>
        @else
        <form id="booking-form" action="{{ route('customer.pemesanan.proses') }}" method="POST">
            @csrf
            <div class="card-container">
                <!-- CARD KIRI: DETAIL PERJALANAN -->
                <div class="left-column">
                    <div class="card p-4">
                        <h5 class="fw-bold section-header">DETAIL PERJALANAN</h5>

                        <!-- Rute -->
                        <div class="route-display">
                            <span class="city-name">{{ $outletAsal->nama_outlet ?? $jadwal->rute_pertama->kota_asal ?? 'Kota Asal' }}</span>
                            <span class="route-arrow">→</span>
                            <span class="city-name">{{ $outletTujuan->nama_outlet ?? $jadwal->rute_terakhir->kota_tujuan ?? 'Kota Tujuan' }}</span>
                        </div>

                        <!-- Detail Perjalanan -->
                        <div class="journey-details">
                            <div class="detail-row">
                                <div class="detail-label">Tanggal</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Waktu</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }} </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Penumpang</div>
                                <div class="detail-value">{{ $penumpang }} Orang</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Jenis Kendaraan   </div>
                                <div class="detail-value">{{ $jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle' }}</div>
                            </div>
                        </div>

                        <!-- Info Shuttle dengan Badges -->
                        <div class="shuttle-info">
                            <div class="detail-label mb-2" style="font-weight: 600; color: #00215E;">
                                {{ $jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle' }}
                            </div>
                            <div class="fasilitas-badges">
                                @if(isset($jadwal->shuttle->fasilitas))
                                    @php
                                        // Pisahkan fasilitas berdasarkan koma
                                        $fasilitasArray = explode(',', $jadwal->shuttle->fasilitas);
                                        $fasilitasMap = [
                                            'ac' => ['icon' => 'fas fa-snowflake', 'label' => 'AC'],
                                            'wifi' => ['icon' => 'fas fa-wifi', 'label' => 'WiFi'],
                                            'charger' => ['icon' => 'fas fa-bolt', 'label' => 'Charger'],
                                            'toilet' => ['icon' => 'fas fa-restroom', 'label' => 'Toilet'],
                                            'reclining seat' => ['icon' => 'fas fa-chair', 'label' => 'Reclining Seat'],
                                            'tv' => ['icon' => 'fas fa-tv', 'label' => 'TV'],
                                            'blanket' => ['icon' => 'fas fa-bed', 'label' => 'Blanket'],
                                            'snack' => ['icon' => 'fas fa-cookie-bite', 'label' => 'Snack'],
                                            'driver' => ['icon' => 'fas fa-user-tie', 'label' => 'Driver']
                                        ];
                                    @endphp

                                    @foreach($fasilitasArray as $fasilitas)
                                        @php
                                            $fasilitas = strtolower(trim($fasilitas));
                                            $found = false;

                                            // Cari fasilitas yang sesuai dalam map
                                            foreach($fasilitasMap as $key => $value) {
                                                if(strpos($fasilitas, $key) !== false) {
                                                    $icon = $value['icon'];
                                                    $label = $value['label'];
                                                    $found = true;
                                                    break;
                                                }
                                            }

                                            // Jika tidak ditemukan di map, gunakan default
                                            if(!$found) {
                                                $icon = 'fas fa-check';
                                                $label = trim($fasilitas);
                                            }
                                        @endphp

                                        <span class="fasilitas-badge {{ str_replace(' ', '-', $fasilitas) }}">
                                            <i class="{{ $icon }}"></i>
                                            {{ ucwords($label) }}
                                        </span>
                                    @endforeach
                                @else
                                    <!-- Default fasilitas -->
                                    <span class="fasilitas-badge ac">
                                        <i class="fas fa-snowflake"></i> AC
                                    </span>
                                    <span class="fasilitas-badge wifi">
                                        <i class="fas fa-wifi"></i> WiFi
                                    </span>
                                    <span class="fasilitas-badge charger">
                                        <i class="fas fa-bolt"></i> Charger
                                    </span>
                                    <span class="fasilitas-badge toilet">
                                        <i class="fas fa-restroom"></i> Toilet
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="price-section">
                            <div class="price-row">
                                <div class="price-label">Harga Tiket per orang</div>
                                <div class="price-value">Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}</div>
                            </div>
                            <div class="price-row">
                                <div class="price-label">Jumlah Penumpang</div>
                                <div class="price-value">× {{ $penumpang }}</div>
                            </div>
                            <div class="price-row">
                                <div class="price-label">Subtotal</div>
                                <div class="price-value" id="subtotal-amount-left">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                            </div>
                            <div class="price-row">
                                <div class="price-label">Diskon Promo</div>
                                <div class="price-value text-success" id="discount-amount-left">- Rp {{ number_format($diskon, 0, ',', '.') }}</div>
                            </div>
                            <div class="total-row">
                                <div class="total-label">Total Bayar</div>
                                <div class="total-value" id="total-amount-left">Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD KANAN: DATA PEMESANAN -->
                <div class="right-column">
                    <div class="card p-4 form-card">
                        <h5 class="fw-bold section-header">DATA PEMESANAN</h5>

                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                        <input type="hidden" name="jumlah_penumpang" value="{{ $penumpang }}">
                        <input type="hidden" id="kode_promo_input" name="kode_promo" value="{{ $appliedPromo['kode'] ?? '' }}">
                        <input type="hidden" id="diskon_amount" name="diskon_amount" value="{{ $diskon }}">
                        <input type="hidden" id="total_after_discount" name="total_after_discount" value="{{ $totalAfterDiscount }}">

                        <!-- Data Pemesan -->
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_pemesan" id="nama_pemesan"
                                value="{{ old('nama_pemesan', $userData->name ?? ($user['name'] ?? '')) }}"
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama_pemesan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Nomor Handphone</label>
                                    <input type="tel" class="form-control" name="telepon_pemesan" id="telepon_pemesan"
                                        value="{{ old('telepon_pemesan', $userData->phone ?? ($user['phone'] ?? '')) }}"
                                        placeholder="081234567890" required>
                                    @error('telepon_pemesan')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email_pemesan" id="email_pemesan"
                                        value="{{ old('email_pemesan', $userData->email ?? ($user['email'] ?? '')) }}"
                                        placeholder="email@contoh.com" required>
                                    @error('email_pemesan')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" name="catatan" rows="3"
                                      placeholder="Masukkan catatan tambahan jika ada">{{ old('catatan') }}</textarea>
                        </div>

                        <!-- Data Penumpang -->
                        <h5 class="fw-bold mt-4 section-header">DATA PENUMPANG</h5>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-warning"></i>
                                Fitur "Sama dengan pemesan" hanya bisa digunakan untuk satu penumpang saja.
                            </small>
                        </div>

                        @for($i = 1; $i <= $penumpang; $i++)
                        <div class="penumpang-group" id="penumpang-{{ $i }}">
                            <div class="penumpang-header">
                                <div class="penumpang-title">
                                    <span class="penumpang-number">{{ $i }}</span>
                                    Penumpang {{ $i }}
                                </div>

                                <!-- Checkbox "Sama dengan Pemesan" -->
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input same-as-pemesan-checkbox exclusive-checkbox"
                                           id="same-as-pemesan-{{ $i }}" data-index="{{ $i }}"
                                           {{ old("penumpang.$i.same_as_pemesan") == 'on' ? 'checked' : '' }}>
                                    <label class="form-check-label same-as-pemesan-label" for="same-as-pemesan-{{ $i }}" id="label-same-as-pemesan-{{ $i }}">
                                        <i class="fas fa-user-check"></i>
                                        Sama dengan pemesan
                                    </label>
                                </div>
                            </div>
                            <div class="exclusive-warning" id="warning-{{ $i }}">
                                <i class="fas fa-exclamation-circle"></i> Hanya satu penumpang yang bisa menggunakan fitur ini
                            </div>

                            <!-- Hidden input untuk email penumpang -->
                            <input type="hidden" name="penumpang[{{ $i }}][email]" id="email-penumpang-{{ $i }}">

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap Penumpang <span style="color: red;">*</span></label>
                                <input type="text" class="form-control penumpang-nama"
                                       name="penumpang[{{ $i }}][nama_lengkap]"
                                       id="nama-penumpang-{{ $i }}"
                                       value="{{ old("penumpang.$i.nama_lengkap") }}"
                                       placeholder="Masukkan nama lengkap penumpang"
                                       required
                                       data-index="{{ $i }}">
                                @error("penumpang.$i.nama_lengkap")
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label class="form-label">NIK Penumpang <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control nik-input penumpang-nik"
                                               name="penumpang[{{ $i }}][nik]"
                                               id="nik-{{ $i }}"
                                               value="{{ old("penumpang.$i.nik") }}"
                                               placeholder="16 digit NIK"
                                               minlength="16"
                                               maxlength="16"
                                               required
                                               data-index="{{ $i }}">
                                        <small style="color: #666; font-size: 12px;">* Wajib diisi (16 digit)</small>
                                        <div class="error-message nik-error" id="nik-error-{{ $i }}" style="display: none;"></div>
                                        @error("penumpang.$i.nik")
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label class="form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                                        <select class="form-control penumpang-jk"
                                                name="penumpang[{{ $i }}][jenis_kelamin]"
                                                id="jk-{{ $i }}"
                                                required
                                                data-index="{{ $i }}">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old("penumpang.$i.jenis_kelamin") == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old("penumpang.$i.jenis_kelamin") == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error("penumpang.$i.jenis_kelamin")
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Telepon Penumpang <span style="color: red;">*</span></label>
                                        <input type="tel" class="form-control telepon-input penumpang-telepon"
                                               name="penumpang[{{ $i }}][telepon]"
                                               id="telepon-{{ $i }}"
                                               value="{{ old("penumpang.$i.telepon") }}"
                                               placeholder="081234567890"
                                               required
                                               data-index="{{ $i }}">
                                        <small style="color: #666; font-size: 12px;">* Wajib diisi (minimal 10 digit)</small>
                                        <div class="error-message telepon-error" id="telepon-error-{{ $i }}" style="display: none;"></div>
                                        @error("penumpang.$i.telepon")
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Field email yang disembunyikan tapi akan diisi -->
                            <div class="form-group email-field" id="email-field-{{ $i }}">
                                <label class="form-label">Email Penumpang</label>
                                <input type="email" class="form-control"
                                       id="email-display-{{ $i }}"
                                       placeholder="Email akan terisi otomatis"
                                       readonly>
                            </div>
                        </div>
                        @endfor

                        <!-- PROMO SECTION -->
                        <div class="promo-section">
                            <h6 class="promo-title">Kode Promo</h6>

                            @if(isset($appliedPromo) && $diskon > 0)
                            <!-- Jika promo sudah diterapkan -->
                            <div class="promo-applied" id="promo-applied">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span class="ms-2" id="promo-applied-name">{{ $appliedPromo['nama'] ?? 'Promo' }} ({{ $appliedPromo['kode'] ?? '' }})</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="remove-promo">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="promo-info">
                                    <small id="promo-applied-desc">{{ $appliedPromo['deskripsi'] ?? '' }}</small>
                                </div>
                            </div>

                            <div class="promo-input-group" style="display: none;">
                                <input type="text" class="form-control promo-input" id="promo-code"
                                       placeholder="Masukkan kode promo">
                                <button type="button" class="btn btn-outline-orange" id="apply-promo">Terapkan</button>
                            </div>
                            @else
                            <!-- Jika belum ada promo, tampilkan input -->
                            <div class="promo-input-group">
                                <input type="text" class="form-control promo-input" id="promo-code" placeholder="Masukkan kode promo" value="{{ old('promo_code') }}">
                                <button type="button" class="btn btn-outline-orange" id="apply-promo">Terapkan</button>
                            </div>
                            @endif

                            <!-- Promo Success Message -->
                            <div class="promo-success" id="promo-success" style="display: none;">
                                <i class="fas fa-check-circle"></i>
                                <span id="promo-success-message">Kode promo berhasil diterapkan!</span>
                            </div>

                            <!-- Promo Error Message -->
                            <div class="promo-error" id="promo-error" style="display: none;">
                                <i class="fas fa-times-circle"></i>
                                <span id="promo-error-message">Kode promo tidak valid atau sudah kadaluarsa</span>
                            </div>

                            <div class="promo-info">
                                <small><i class="fas fa-info-circle"></i> Mohon Masukan Kode Promo Yang Sedia</small>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="summary-section">
                            <div class="summary-item">
                                <span>Subtotal</span>
                                <span id="subtotal-amount-right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>

                            <!-- Diskont Section -->
                            @if(isset($appliedPromo) && $diskon > 0)
                            <div class="summary-item" id="discount-item-right">
                                <span>Diskon ({{ $appliedPromo['kode'] ?? '' }})</span>
                                <span id="discount-amount-right" class="text-success">-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                            </div>
                            @else
                            <div class="summary-item" id="discount-item-right" style="display: none;">
                                <span>Diskon</span>
                                <span id="discount-amount-right" class="text-success">-Rp 0</span>
                            </div>
                            @endif

                            <div class="summary-item total">
                                <span>Total Pembayaran</span>
                                <span id="total-amount-right">Rp {{ number_format($totalAfterDiscount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-submit" id="submit-btn">
                            <i class="fas fa-ticket-alt"></i> Lanjutkan ke Pemilihan Kursi
                        </button>

                        <!-- Link Kembali -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('customer.search') }}" style="color: #00215E; text-decoration: none;">
                                <i class="fas fa-arrow-left"></i> Kembali ke Pencarian
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript loaded for booking page');

    // ========== VARIABLES ==========
    const promoInput = document.getElementById('promo-code');
    const applyPromoBtn = document.getElementById('apply-promo');
    const removePromoBtn = document.getElementById('remove-promo');
    const promoSuccess = document.getElementById('promo-success');
    const promoError = document.getElementById('promo-error');
    const promoApplied = document.getElementById('promo-applied');

    // Initialize variables from PHP
    const originalTotal = {{ $totalHarga ?? 0 }};
    const currentTotal = {{ $totalAfterDiscount ?? $totalHarga ?? 0 }};
    const currentDiskon = {{ $diskon ?? 0 }};

    // Data profil dari userData (dikirim dari controller)
    const userData = @json($userData ?? null);
    const userSession = @json($user ?? null);

    console.log('User Data from database:', userData);
    console.log('User Data from session:', userSession);
    console.log('Original Total:', originalTotal);
    console.log('Current Total:', currentTotal);
    console.log('Current Diskon:', currentDiskon);

    // ========== FUNGSI UNTUK "SAMA DENGAN PEMESAN" (EXCLUSIVE) ==========

    // Get all checkboxes
    const sameAsPemesanCheckboxes = document.querySelectorAll('.same-as-pemesan-checkbox');

    // Track currently checked checkbox
    let currentlyCheckedCheckbox = null;

    // Initialize - find if any checkbox is already checked from old data
    sameAsPemesanCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            currentlyCheckedCheckbox = checkbox;
            disableOtherCheckboxes(checkbox.dataset.index);
        }
    });

    // Fungsi untuk menonaktifkan checkbox lainnya
    function disableOtherCheckboxes(currentIndex) {
        sameAsPemesanCheckboxes.forEach(checkbox => {
            const index = checkbox.dataset.index;
            const label = document.getElementById(`label-same-as-pemesan-${index}`);
            const warning = document.getElementById(`warning-${index}`);

            if (index !== currentIndex) {
                checkbox.disabled = true;
                if (label) {
                    label.classList.add('disabled');
                }
            } else {
                // Show warning for the checked one
                if (warning) {
                    warning.style.display = 'block';
                }
            }
        });
    }

    // Fungsi untuk mengaktifkan semua checkbox
    function enableAllCheckboxes() {
        sameAsPemesanCheckboxes.forEach(checkbox => {
            const index = checkbox.dataset.index;
            const label = document.getElementById(`label-same-as-pemesan-${index}`);
            const warning = document.getElementById(`warning-${index}`);
            const emailField = document.getElementById(`email-field-${index}`);

            checkbox.disabled = false;
            if (label) {
                label.classList.remove('disabled');
            }
            if (warning) {
                warning.style.display = 'none';
            }
            if (emailField) {
                emailField.classList.remove('show');
            }
        });
        currentlyCheckedCheckbox = null;
    }

    // Fungsi untuk mendapatkan data profil pengguna
    function getProfileData() {
        // Ambil data dari form pemesan
        const namaPemesan = document.getElementById('nama_pemesan').value;
        const teleponPemesan = document.getElementById('telepon_pemesan').value;
        const emailPemesan = document.getElementById('email_pemesan').value;

        // Ambil data dari profil pengguna (database atau session)
        let nikUser = '';
        let jenisKelaminUser = '';

        // Prioritas 1: Data dari database (userData)
        if (userData) {
            nikUser = userData.nik || '';
            jenisKelaminUser = userData.jenis_kelamin || '';
        }
        // Prioritas 2: Data dari session
        else if (userSession && userSession.nik) {
            nikUser = userSession.nik || '';
            jenisKelaminUser = userSession.jenis_kelamin || '';
        }

        // Bersihkan format telepon (hapus karakter non-digit)
        const teleponClean = teleponPemesan.replace(/\D/g, '');

        return {
            nama: namaPemesan,
            telepon: teleponClean,
            teleponFormatted: teleponPemesan,
            email: emailPemesan,
            nik: nikUser,
            jenis_kelamin: jenisKelaminUser
        };
    }

    // Fungsi untuk mengisi data penumpang dengan data pemesan DAN profil
    function fillPenumpangWithPemesan(index) {
        const profileData = getProfileData();

        // Ambil elemen input penumpang berdasarkan index
        const namaPenumpang = document.getElementById(`nama-penumpang-${index}`);
        const teleponPenumpang = document.getElementById(`telepon-${index}`);
        const nikPenumpang = document.getElementById(`nik-${index}`);
        const jkPenumpang = document.getElementById(`jk-${index}`);
        const emailHidden = document.getElementById(`email-penumpang-${index}`);
        const emailDisplay = document.getElementById(`email-display-${index}`);
        const emailField = document.getElementById(`email-field-${index}`);

        // Simpan nilai asli sebelum diisi (untuk comparison nanti)
        if (namaPenumpang) namaPenumpang.setAttribute('data-original-value', namaPenumpang.value);
        if (teleponPenumpang) teleponPenumpang.setAttribute('data-original-value', teleponPenumpang.value);
        if (nikPenumpang) nikPenumpang.setAttribute('data-original-value', nikPenumpang.value);
        if (jkPenumpang) jkPenumpang.setAttribute('data-original-value', jkPenumpang.value);

        // Isi nilai ke field penumpang - HANYA jika field kosong atau masih sama dengan asli
        if (namaPenumpang && profileData.nama) {
            const currentValue = namaPenumpang.value;
            const originalValue = namaPenumpang.getAttribute('data-original-value') || '';

            if (!currentValue || currentValue === originalValue) {
                namaPenumpang.value = profileData.nama;
                namaPenumpang.classList.add('auto-filled');
                namaPenumpang.dispatchEvent(new Event('input'));
            }
        }

        if (teleponPenumpang && profileData.teleponFormatted) {
            const currentValue = teleponPenumpang.value;
            const originalValue = teleponPenumpang.getAttribute('data-original-value') || '';

            if (!currentValue || currentValue === originalValue) {
                teleponPenumpang.value = profileData.teleponFormatted;
                teleponPenumpang.classList.add('auto-filled');
                teleponPenumpang.dispatchEvent(new Event('input'));
            }
        }

        if (nikPenumpang && profileData.nik) {
            const currentValue = nikPenumpang.value;
            const originalValue = nikPenumpang.getAttribute('data-original-value') || '';

            if (!currentValue || currentValue === originalValue) {
                nikPenumpang.value = profileData.nik;
                nikPenumpang.classList.add('auto-filled');
                nikPenumpang.dispatchEvent(new Event('input'));
            }
        }

        if (jkPenumpang && profileData.jenis_kelamin) {
            const currentValue = jkPenumpang.value;
            const originalValue = jkPenumpang.getAttribute('data-original-value') || '';

            if (!currentValue || currentValue === originalValue) {
                jkPenumpang.value = profileData.jenis_kelamin;
                jkPenumpang.classList.add('auto-filled');
                jkPenumpang.dispatchEvent(new Event('input'));
            }
        }

        // Untuk email, selalu isi dari pemesan
        if (emailHidden && profileData.email) {
            emailHidden.value = profileData.email;
        }

        if (emailDisplay && profileData.email) {
            emailDisplay.value = profileData.email;
        }

        if (emailField && profileData.email) {
            emailField.classList.add('show');
        }

        // Update label checkbox
        const checkboxLabel = document.getElementById(`label-same-as-pemesan-${index}`);
        if (checkboxLabel) {
            checkboxLabel.classList.add('checkbox-checked');
        }

        console.log(`Mengisi data penumpang ${index} dengan data pemesan dan profil`, profileData);
    }

    // Fungsi untuk mengosongkan data penumpang (ketika checkbox di-uncheck)
    function clearPenumpangData(index) {
        const namaPenumpang = document.getElementById(`nama-penumpang-${index}`);
        const teleponPenumpang = document.getElementById(`telepon-${index}`);
        const nikPenumpang = document.getElementById(`nik-${index}`);
        const jkPenumpang = document.getElementById(`jk-${index}`);
        const emailHidden = document.getElementById(`email-penumpang-${index}`);
        const emailDisplay = document.getElementById(`email-display-${index}`);
        const emailField = document.getElementById(`email-field-${index}`);

        // Hapus class 'auto-filled' tapi jangan kosongkan nilai jika user sudah mengubahnya
        if (namaPenumpang && namaPenumpang.classList.contains('auto-filled')) {
            namaPenumpang.classList.remove('auto-filled');
        }

        if (teleponPenumpang && teleponPenumpang.classList.contains('auto-filled')) {
            teleponPenumpang.classList.remove('auto-filled');
        }

        if (nikPenumpang && nikPenumpang.classList.contains('auto-filled')) {
            nikPenumpang.classList.remove('auto-filled');
        }

        if (jkPenumpang && jkPenumpang.classList.contains('auto-filled')) {
            jkPenumpang.classList.remove('auto-filled');
        }

        // Kosongkan email karena ini khusus
        if (emailHidden) {
            emailHidden.value = '';
        }

        if (emailDisplay) {
            emailDisplay.value = '';
        }

        if (emailField) {
            emailField.classList.remove('show');
        }

        // Hapus data-original-value attributes
        if (namaPenumpang) namaPenumpang.removeAttribute('data-original-value');
        if (teleponPenumpang) teleponPenumpang.removeAttribute('data-original-value');
        if (nikPenumpang) nikPenumpang.removeAttribute('data-original-value');
        if (jkPenumpang) jkPenumpang.removeAttribute('data-original-value');

        // Update label checkbox
        const checkboxLabel = document.getElementById(`label-same-as-pemesan-${index}`);
        if (checkboxLabel) {
            checkboxLabel.classList.remove('checkbox-checked');
        }

        console.log(`Mengosongkan data auto-filled untuk penumpang ${index}`);
    }

    // ========== MODIFIED: EVENT LISTENERS UNTUK MANUAL INPUT ==========

    // Event listener untuk input manual pada field penumpang
    sameAsPemesanCheckboxes.forEach(checkbox => {
        const index = checkbox.dataset.index;

        // Event listener untuk perubahan checkbox
        checkbox.addEventListener('change', function() {
            const index = this.dataset.index;

            if (this.checked) {
                // Jika ada checkbox lain yang sudah dicentang, uncheck dulu
                if (currentlyCheckedCheckbox && currentlyCheckedCheckbox !== this) {
                    currentlyCheckedCheckbox.checked = false;
                    clearPenumpangData(currentlyCheckedCheckbox.dataset.index);
                    removeInputListeners(currentlyCheckedCheckbox.dataset.index);
                }

                // Set current checkbox sebagai yang aktif
                currentlyCheckedCheckbox = this;

                // Nonaktifkan checkbox lainnya
                disableOtherCheckboxes(index);

                // Isi dengan data pemesan DAN profil
                fillPenumpangWithPemesan(index);

                // Attach input event listeners
                attachInputListeners(index);
            } else {
                // Aktifkan semua checkbox
                enableAllCheckboxes();

                // Kosongkan data
                clearPenumpangData(index);

                // Remove input event listeners
                removeInputListeners(index);
            }
        });

        // Jika checkbox sudah dicentang dari awal (old data), attach listeners
        if (checkbox.checked) {
            attachInputListeners(index);
        }
    });

    // Event listener untuk perubahan data pemesan
    const pemesanInputs = ['nama_pemesan', 'telepon_pemesan', 'email_pemesan'];
    pemesanInputs.forEach(inputName => {
        const inputElement = document.getElementById(inputName);
        if (inputElement) {
            inputElement.addEventListener('input', function() {
                // Update data penumpang yang checkbox-nya dicentang
                if (currentlyCheckedCheckbox) {
                    const index = currentlyCheckedCheckbox.dataset.index;
                    fillPenumpangWithPemesan(index);
                }
            });
        }
    });

    // Fungsi untuk attach event listeners ke input field dengan debounce
    function attachInputListeners(index) {
        const namaInput = document.getElementById(`nama-penumpang-${index}`);
        const teleponInput = document.getElementById(`telepon-${index}`);
        const nikInput = document.getElementById(`nik-${index}`);
        const jkInput = document.getElementById(`jk-${index}`);
        const checkbox = document.querySelector(`#same-as-pemesan-${index}`);

        // Helper function untuk check jika nilai sudah berbeda
        function checkIfValueChanged(fieldType, newValue) {
            if (!checkbox || !checkbox.checked) return;

            const profileData = getProfileData();
            let shouldUncheck = false;

            switch(fieldType) {
                case 'nama':
                    shouldUncheck = (newValue !== profileData.nama);
                    break;
                case 'telepon':
                    const cleanedNewValue = newValue.replace(/\D/g, '');
                    shouldUncheck = (cleanedNewValue !== profileData.telepon);
                    break;
                case 'nik':
                    // Hanya uncheck jika profile memiliki NIK DAN user mengubahnya
                    shouldUncheck = (profileData.nik && newValue !== profileData.nik);
                    break;
                case 'jk':
                    // Hanya uncheck jika profile memiliki jenis kelamin DAN user mengubahnya
                    shouldUncheck = (profileData.jenis_kelamin && newValue !== profileData.jenis_kelamin);
                    break;
            }

            if (shouldUncheck) {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));

                // Tambahkan pesan feedback
                showFieldChangeFeedback(index, fieldType);
            }
        }

        // Attach listeners dengan debounce untuk menghindari trigger berlebihan
        if (namaInput) {
            const originalNamaValue = namaInput.value;
            namaInput.addEventListener('input', debounce(function() {
                if (this.value !== originalNamaValue) {
                    checkIfValueChanged('nama', this.value);
                }
            }, 500));
        }

        if (teleponInput) {
            const originalTeleponValue = teleponInput.value;
            teleponInput.addEventListener('input', debounce(function() {
                if (this.value !== originalTeleponValue) {
                    checkIfValueChanged('telepon', this.value);
                }
            }, 500));
        }

        if (nikInput) {
            const originalNikValue = nikInput.value;
            nikInput.addEventListener('input', debounce(function() {
                if (this.value !== originalNikValue) {
                    checkIfValueChanged('nik', this.value);
                }
            }, 500));
        }

        if (jkInput) {
            const originalJkValue = jkInput.value;
            jkInput.addEventListener('change', debounce(function() {
                if (this.value !== originalJkValue) {
                    checkIfValueChanged('jk', this.value);
                }
            }, 500));
        }
    }

    // Fungsi untuk remove event listeners
    function removeInputListeners(index) {
        const namaInput = document.getElementById(`nama-penumpang-${index}`);
        const teleponInput = document.getElementById(`telepon-${index}`);
        const nikInput = document.getElementById(`nik-${index}`);
        const jkInput = document.getElementById(`jk-${index}`);

        // Clone dan replace element untuk menghapus event listeners
        if (namaInput) {
            const newNamaInput = namaInput.cloneNode(true);
            namaInput.parentNode.replaceChild(newNamaInput, namaInput);
        }

        if (teleponInput) {
            const newTeleponInput = teleponInput.cloneNode(true);
            teleponInput.parentNode.replaceChild(newTeleponInput, teleponInput);
        }

        if (nikInput) {
            const newNikInput = nikInput.cloneNode(true);
            nikInput.parentNode.replaceChild(newNikInput, nikInput);
        }

        if (jkInput) {
            const newJkInput = jkInput.cloneNode(true);
            jkInput.parentNode.replaceChild(newJkInput, jkInput);
        }
    }

    // Fungsi debounce untuk mencegah trigger berlebihan
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Fungsi untuk menampilkan feedback ketika field diubah
    function showFieldChangeFeedback(index, fieldType) {
        const fieldNames = {
            'nama': 'Nama',
            'telepon': 'Nomor Telepon',
            'nik': 'NIK',
            'jk': 'Jenis Kelamin'
        };

        // Tampilkan pesan kecil di bawah field
        const fieldElement = document.getElementById(`${fieldType}-${index}`);
        if (fieldElement) {
            // Hapus feedback sebelumnya jika ada
            const existingFeedback = fieldElement.parentNode.querySelector('.field-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }

            const feedback = document.createElement('small');
            feedback.className = 'field-feedback';
            feedback.innerHTML = `<i class="fas fa-info-circle"></i> Perubahan ${fieldNames[fieldType]} menghapus otomatisasi`;
            fieldElement.parentNode.appendChild(feedback);

            // Hapus setelah 3 detik
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.remove();
                }
            }, 3000);
        }
    }

    // ========== FORMAT TELEPON PEMESAN ==========
    // Auto format untuk input telepon pemesan
    const teleponPemesanInput = document.getElementById('telepon_pemesan');
    if (teleponPemesanInput) {
        teleponPemesanInput.addEventListener('input', function() {
            // Hapus semua karakter selain angka
            let value = this.value.replace(/\D/g, '');

            // Format: 0812-3456-7890
            if (value.length > 4 && value.length <= 8) {
                value = value.replace(/(\d{4})(\d{0,4})/, '$1-$2');
            } else if (value.length > 8) {
                value = value.replace(/(\d{4})(\d{4})(\d{0,7})/, '$1-$2-$3');
            }

            this.value = value;

            // Validasi real-time
            const teleponValue = this.value.replace(/\D/g, '');
            if (teleponValue && !validateTelepon(teleponValue)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (teleponValue && validateTelepon(teleponValue)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
    }

    // ========== PROMO FUNCTIONS ==========

    // Debug element existence
    console.log('Promo Input exists:', !!promoInput);
    console.log('Apply Promo Button exists:', !!applyPromoBtn);
    console.log('Remove Promo Button exists:', !!removePromoBtn);

    // Check if promo is already applied
    @if(isset($appliedPromo) && $diskon > 0)
        console.log('Promo already applied:', '{{ $appliedPromo["kode"] ?? "" }}');
    @endif

    // Apply promo button click handler
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            applyPromo();
        });
    }

    // Remove promo button click handler
    if (removePromoBtn) {
        removePromoBtn.addEventListener('click', function() {
            removePromo();
        });
    }

    // Allow Enter key to apply promo
    if (promoInput) {
        promoInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (applyPromoBtn) applyPromoBtn.click();
            }
        });
    }

    // Fungsi untuk apply promo
    function applyPromo() {
        const promoCode = promoInput ? promoInput.value.trim().toUpperCase() : '';

        if (!promoCode) {
            showPromoError('Masukkan kode promo terlebih dahulu');
            return;
        }

        // Show loading
        if (applyPromoBtn) {
            applyPromoBtn.disabled = true;
            applyPromoBtn.innerHTML = '<span class="spinner"></span> Memproses...';
        }

        // Send AJAX request
        fetch('{{ route("customer.apply-promo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                promo_code: promoCode,
                total_amount: originalTotal
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Promo response:', data);

            // Reset button
            if (applyPromoBtn) {
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = 'Terapkan';
            }

            if (data.success) {
                showPromoSuccess(data.message);
                updatePromoDisplay(data);
                updateSummary(data.diskon, data.total_after_discount);

                // Update hidden inputs
                document.getElementById('kode_promo_input').value = data.promo.kode;
                document.getElementById('diskon_amount').value = data.diskon;
                document.getElementById('total_after_discount').value = data.total_after_discount;

            } else {
                showPromoError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (applyPromoBtn) {
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = 'Terapkan';
            }
            showPromoError(error.message || 'Terjadi kesalahan, silakan coba lagi');
        });
    }

    // Fungsi untuk remove promo
    function removePromo() {
        fetch('{{ route("customer.remove-promo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update tampilan tanpa reload
                hidePromoApplied();
                resetSummary();

                // Update hidden inputs
                document.getElementById('kode_promo_input').value = '';
                document.getElementById('diskon_amount').value = 0;
                document.getElementById('total_after_discount').value = originalTotal;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Fungsi untuk hide promo applied section
    function hidePromoApplied() {
        const promoApplied = document.getElementById('promo-applied');
        const promoInputGroup = document.querySelector('.promo-input-group');

        if (promoApplied) {
            promoApplied.style.display = 'none';
        }

        if (promoInputGroup) {
            promoInputGroup.style.display = 'flex';
        }

        // Clear input
        if (promoInput) {
            promoInput.value = '';
        }

        // Hide success/error messages
        if (promoSuccess) promoSuccess.style.display = 'none';
        if (promoError) promoError.style.display = 'none';
    }

    // Fungsi untuk reset summary
    function resetSummary() {
        // Update card kiri
        const discountAmountLeft = document.getElementById('discount-amount-left');
        const totalAmountLeft = document.getElementById('total-amount-left');

        if (discountAmountLeft) {
            discountAmountLeft.textContent = '- Rp 0';
            discountAmountLeft.className = 'price-value text-success';
        }
        if (totalAmountLeft) {
            totalAmountLeft.textContent = `Rp ${formatCurrency(originalTotal)}`;
        }

        // Update card kanan
        const discountItemRight = document.getElementById('discount-item-right');
        const discountAmountRight = document.getElementById('discount-amount-right');
        const totalAmountRight = document.getElementById('total-amount-right');

        if (discountItemRight) {
            discountItemRight.style.display = 'none';
        }
        if (discountAmountRight) {
            discountAmountRight.textContent = '-Rp 0';
        }
        if (totalAmountRight) {
            totalAmountRight.textContent = `Rp ${formatCurrency(originalTotal)}`;
        }
    }

    function showPromoSuccess(message) {
        if (!promoSuccess) return;

        if (promoError) promoError.style.display = 'none';
        promoSuccess.style.display = 'block';
        document.getElementById('promo-success-message').textContent = message;
    }

    function showPromoError(message) {
        if (!promoError) return;

        if (promoSuccess) promoSuccess.style.display = 'none';
        promoError.style.display = 'block';
        document.getElementById('promo-error-message').textContent = message;

        // Auto hide after 5 seconds
        setTimeout(() => {
            promoError.style.display = 'none';
        }, 5000);
    }

    function updatePromoDisplay(data) {
        // Jika ada elemen promo-applied, update
        if (promoApplied) {
            promoApplied.style.display = 'block';
            if (document.getElementById('promo-applied-name')) {
                document.getElementById('promo-applied-name').textContent =
                    `${data.promo.nama} (${data.promo.kode})`;
            }
            if (document.getElementById('promo-applied-desc')) {
                document.getElementById('promo-applied-desc').textContent =
                    data.promo.deskripsi;
            }
        }

        // Sembunyikan input group jika ada
        const promoInputGroup = document.querySelector('.promo-input-group');
        if (promoInputGroup) {
            promoInputGroup.style.display = 'none';
        }

        // Clear input
        if (promoInput) {
            promoInput.value = '';
        }
    }

    function updateSummary(diskon, totalAfterDiscount) {
        // Update card kiri
        const discountAmountLeft = document.getElementById('discount-amount-left');
        const totalAmountLeft = document.getElementById('total-amount-left');

        if (discountAmountLeft) {
            discountAmountLeft.textContent = `- Rp ${formatCurrency(diskon)}`;
            discountAmountLeft.className = 'price-value text-success';
        }
        if (totalAmountLeft) {
            totalAmountLeft.textContent = `Rp ${formatCurrency(totalAfterDiscount)}`;
        }

        // Update card kanan
        const discountItemRight = document.getElementById('discount-item-right');
        const discountAmountRight = document.getElementById('discount-amount-right');
        const totalAmountRight = document.getElementById('total-amount-right');

        if (discountItemRight && discountAmountRight) {
            discountItemRight.style.display = 'flex';
            discountAmountRight.textContent = `-Rp ${formatCurrency(diskon)}`;
        }
        if (totalAmountRight) {
            totalAmountRight.textContent = `Rp ${formatCurrency(totalAfterDiscount)}`;
        }
    }

    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ========== FORM VALIDATION FUNCTIONS ==========

    // Validasi NIK
    function validateNIK(nik) {
        // Cek apakah 16 digit dan hanya angka
        const nikRegex = /^\d{16}$/;
        return nikRegex.test(nik);
    }

    // Validasi format telepon
    function validateTelepon(telepon) {
        // Format: minimal 10 digit, maksimal 15 digit, boleh angka, spasi, dash, plus
        const teleponRegex = /^[\d\s\-+]{10,15}$/;
        return teleponRegex.test(telepon.replace(/\s/g, ''));
    }

    // Validasi semua form sebelum submit
    function validateForm() {
        let isValid = true;
        const nikInputs = document.querySelectorAll('.nik-input');
        const teleponInputs = document.querySelectorAll('.telepon-input');
        const teleponPemesanInput = document.getElementById('telepon_pemesan');

        // Reset error messages NIK
        nikInputs.forEach(input => {
            const penumpangIndex = input.dataset.index;
            const errorElement = document.getElementById(`nik-error-${penumpangIndex}`);
            if (errorElement) {
                errorElement.style.display = 'none';
                errorElement.textContent = '';
            }
        });

        // Reset error messages Telepon
        teleponInputs.forEach(input => {
            const penumpangIndex = input.dataset.index;
            const errorElement = document.getElementById(`telepon-error-${penumpangIndex}`);
            if (errorElement) {
                errorElement.style.display = 'none';
                errorElement.textContent = '';
            }
        });

        // Validasi telepon pemesan
        if (teleponPemesanInput) {
            const teleponPemesanValue = teleponPemesanInput.value.replace(/\D/g, '');
            if (!teleponPemesanValue) {
                isValid = false;
                teleponPemesanInput.classList.add('is-invalid');
            } else if (!validateTelepon(teleponPemesanValue)) {
                isValid = false;
                teleponPemesanInput.classList.add('is-invalid');
            } else {
                teleponPemesanInput.classList.remove('is-invalid');
                teleponPemesanInput.classList.add('is-valid');
            }
        }

        // Validasi setiap NIK
        nikInputs.forEach(input => {
            const nikValue = input.value.trim();
            const penumpangIndex = input.dataset.index;
            const errorElement = document.getElementById(`nik-error-${penumpangIndex}`);

            if (!nikValue) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent = 'NIK wajib diisi';
                    errorElement.style.display = 'block';
                }
                input.classList.add('is-invalid');
            } else if (!validateNIK(nikValue)) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent = 'NIK harus 16 digit angka';
                    errorElement.style.display = 'block';
                }
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });

        // Validasi setiap Telepon
        teleponInputs.forEach(input => {
            const teleponValue = input.value.trim();
            const teleponCleanValue = teleponValue.replace(/\D/g, '');
            const penumpangIndex = input.dataset.index;
            const errorElement = document.getElementById(`telepon-error-${penumpangIndex}`);

            if (!teleponValue) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent = 'Nomor telepon wajib diisi';
                    errorElement.style.display = 'block';
                }
                input.classList.add('is-invalid');
            } else if (!validateTelepon(teleponCleanValue)) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent = 'Nomor telepon minimal 10 digit dan maksimal 15 digit';
                    errorElement.style.display = 'block';
                }
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });

        return isValid;
    }

    // ========== EVENT LISTENERS ==========

    // Auto format untuk input NIK (hanya angka)
    const nikInputs = document.querySelectorAll('.nik-input');
    nikInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Hanya angka yang diperbolehkan
            this.value = this.value.replace(/\D/g, '');

            // Batasi maksimal 16 digit
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }

            // Validasi real-time
            const nikValue = this.value.trim();
            const penumpangIndex = this.dataset.index;
            const errorElement = document.getElementById(`nik-error-${penumpangIndex}`);

            if (nikValue && !validateNIK(nikValue)) {
                if (errorElement) {
                    errorElement.textContent = 'NIK harus 16 digit angka';
                    errorElement.style.display = 'block';
                }
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (nikValue && validateNIK(nikValue)) {
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    });

    // Auto format untuk input telepon penumpang
    const teleponInputs = document.querySelectorAll('.telepon-input');
    teleponInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Hapus semua karakter selain angka
            let value = this.value.replace(/\D/g, '');

            // Format: 0812-3456-7890
            if (value.length > 4 && value.length <= 8) {
                value = value.replace(/(\d{4})(\d{0,4})/, '$1-$2');
            } else if (value.length > 8) {
                value = value.replace(/(\d{4})(\d{4})(\d{0,7})/, '$1-$2-$3');
            }

            this.value = value;

            // Validasi real-time
            const teleponValue = this.value.replace(/\D/g, '');
            const penumpangIndex = this.dataset.index;
            const errorElement = document.getElementById(`telepon-error-${penumpangIndex}`);

            if (teleponValue && !validateTelepon(teleponValue)) {
                if (errorElement) {
                    errorElement.textContent = 'Nomor telepon minimal 10 digit angka';
                    errorElement.style.display = 'block';
                }
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (teleponValue && validateTelepon(teleponValue)) {
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    });

    // Form validation before submit
    const bookingForm = document.getElementById('booking-form');
    const submitBtn = document.getElementById('submit-btn');

    if (bookingForm && submitBtn) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi NIK dan Telepon
            if (!validateForm()) {
                alert('Harap periksa kembali data penumpang. Pastikan NIK (16 digit) dan nomor telepon (minimal 10 digit) sudah diisi dengan benar.');
                return false;
            }

            // Tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Memproses...';

            // Submit form jika valid
            this.submit();
        });
    }

    // ========== INITIALIZE FORM FIELDS ==========
    // Jika ada data dari profil, format telepon pemesan
    if (teleponPemesanInput && teleponPemesanInput.value) {
        // Trigger input event untuk format
        teleponPemesanInput.dispatchEvent(new Event('input'));
    }

    console.log('Form initialization complete');
});
</script>
@endpush
