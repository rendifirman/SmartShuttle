@extends('layouts.app')

@section('title', 'Beranda - Smart Shuttle')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* CSS Variables */
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --modal-bg: rgba(74, 66, 62, 0.50);
        --whatsapp-green: #25D366;
        --phone-blue: #3498DB;
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
        margin-top: -60px; /* Untuk mengkompensasi navbar fixed */
        padding-top: 60px; /* Untuk memberi ruang untuk navbar */
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 50%;
        color: white;
        width: 100%;
    }

    .hero-title {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
        font-family: 'Roboto', sans-serif;
        line-height: 1.1;
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
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
        gap: 14px;
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

    /* PERBAIKAN SELECT2 DI BERANDA - HAPUS STYLING YANG DOUBLE */
    .search-field .select2-container {
        width: 100% !important;
    }

    .search-field .select2-selection {
        height: 48px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
    }

    .search-field .select2-selection__rendered {
        line-height: 46px !important;
        color: black !important;
        font-size: 14px !important;
        padding-left: 12px !important;
        font-weight: bold !important;
        font-family: 'Roboto', sans-serif !important;
    }

    .search-field .select2-selection__arrow {
        height: 46px !important;
    }

    .search-field .select2-dropdown {
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

    /* Divider */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #FF581E 50%, transparent 100%);
        margin: 50px 0;
        opacity: 0.6;
        width: 100%;
    }

    /* ========== AVAILABLE SCHEDULES SECTION (DARI FILE KEDUA) ========== */
    .available-schedules {
        padding: 60px 40px;
        background: #f8f9fa;
        width: 100%;
        box-sizing: border-box;
    }

    .available-schedules .container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .available-schedules h2 {
        text-align: center;
        color: #FF581E;
        margin-bottom: 30px;
        font-family: 'Roboto', sans-serif;
        font-size: 32px;
    }

    .available-schedules h2 i {
        margin-right: 10px;
    }

    .schedules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        width: 100%;
    }

    .schedule-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-left: 4px solid #FF581E;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .schedule-card .route {
        font-weight: bold;
        font-size: 18px;
        color: #123352;
        margin-bottom: 15px;
        font-family: 'Roboto', sans-serif;
    }

    .schedule-details {
        margin-top: 10px;
    }

    .schedule-details p {
        margin: 5px 0;
        font-family: 'Roboto', sans-serif;
        color: #333;
    }

    .schedule-details i {
        color: #FF581E;
        margin-right: 8px;
        width: 20px;
    }

    .btn-book {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 20px;
        background: #FF581E;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.3s ease;
        font-family: 'Roboto', sans-serif;
        text-align: center;
    }

    .btn-book:hover {
        background: #E54E1A;
        color: white;
        text-decoration: none;
    }

    .btn-book i {
        margin-right: 5px;
    }

    /* ========== SERVICES SECTION MOBILE FIX ========== */
    .services-section {
        padding: 80px 0;
        background: #ffffff;
        text-align: center;
        width: 100%;
        overflow: hidden; /* FIX: Hindari overflow */
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

    .services-grid {
        display: flex;
        justify-content: center;
        gap: 35px;
        flex-wrap: wrap;
        width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
    }

    .service-card {
        width: 330px;
        max-width: 100%;
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

    .service-card:hover {
        transform: translateY(-10px) scale(1.01);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        border-color: var(--secondary-color);
    }

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
        max-width: 100%;
        transition: 0.35s ease;
    }

    .service-card:hover .service-logo-box {
        transform: scale(1.03);
    }

    .service-card:hover .service-logo-box img {
        transform: scale(1.06);
    }

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

    /* ========== PROMO SECTION MOBILE FIX ========== */
    .promo-section {
        padding: 80px 0;
        background: #f9f9f9;
        text-align: center;
        position: relative;
        width: 100%;
        overflow: hidden; /* FIX: Hindari overflow */
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

    .promo-slider-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 60px;
        width: 100%;
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

    /* ========== FEATURES SECTION MOBILE FIX ========== */
    .features-section {
        padding: 80px 40px;
        background: white;
        color: #333;
        text-align: center;
        width: 100%;
        overflow: hidden; /* FIX: Hindari overflow */
        box-sizing: border-box;
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
        width: 100%;
    }

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
        border: none;
        background: none;
        padding: 0;
        font-size: 12px;
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

    /* ========== REVIEW SECTION MOBILE FIX ========== */
    .feedback-section {
        padding: 80px 40px;
        background: var(--primary-color);
        width: 100%;
        overflow: hidden;
        box-sizing: border-box;
        margin-bottom: 80px;
    }

    .feedback-container {
        max-width: 1200px;
        margin: 0 auto;
        background: rgba(255, 253, 253, 0.1);
        border-radius: 25px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.12);
        padding: 50px;
        width: 100%;
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

    .review-list-container {
        background: rgba(255, 253, 253, 0.1);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

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

    .rating-input-container {
        margin-bottom: 25px;
    }

    .rating-label {
        font-size: 14px;
        font-weight: 600;
        color: white;
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

    .form-group {
        margin-bottom: 25px;
    }

    .form-textarea {
        width: 92%;
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

    .form-textarea::placeholder {
        color: white;
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

    /* ========== FLOATING CUSTOMER SERVICE BUTTONS ========== */
    .floating-cs-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        transition: all 0.3s ease;
    }

    .cs-button {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        text-decoration: none;
        animation: float 3s ease-in-out infinite;
        overflow: hidden;
    }

    .cs-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        border-radius: 50%;
    }

    .cs-button.whatsapp {
        background: linear-gradient(135deg, var(--whatsapp-green), #128C7E);
    }

    .cs-button.phone {
        background: linear-gradient(135deg, var(--phone-blue), #2980b9);
    }

    .cs-button i {
        color: white;
        font-size: 28px;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .cs-button:hover {
        transform: translateY(-8px) scale(1.15);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }

    .cs-button:hover i {
        transform: rotate(15deg) scale(1.1);
    }

    .cs-button:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* Tooltip untuk button */
    .cs-button::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 15px);
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .cs-button::before.tooltip-arrow {
        content: '';
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.85) transparent transparent transparent;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10001;
    }

    .cs-button:hover::before.tooltip-arrow {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* Animasi floating */
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }

    /* ========== MODAL CEK PAKET ========== */
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
        color: #666 !important;
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

    /* ========== RESPONSIVE STYLES ========== */
    /* Tablet (1024px and below) */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 42px;
        }

        .hero-desc {
            font-size: 16px;
        }

        .search-row {
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        .search-btn {
            grid-column: span 3;
            width: 100%;
        }

        .services-grid {
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .service-card {
            width: 100%;
            max-width: 450px;
        }

        .features-grid-6 {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

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

        .promo-slider-container {
            padding: 0 40px;
        }

        .promo-image {
            height: 250px;
        }

        .features-section {
            padding: 60px 30px;
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

        /* Available Schedules Responsive */
        .available-schedules {
            padding: 40px 30px;
        }

        .schedules-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
    }

    /* Mobile (768px and below) */
    @media (max-width: 768px) {
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

        #kirim-paket-form .search-row {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
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

        /* AVAILABLE SCHEDULES MOBILE */
        .available-schedules {
            padding: 30px 20px;
        }

        .available-schedules h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .schedules-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .schedule-card {
            padding: 15px;
        }

        /* SERVICES MOBILE */
        .services-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .services-title {
            font-size: 22px;
            padding: 0 10px;
        }

        .services-subtitle {
            font-size: 13px;
            margin-bottom: 30px;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .services-grid {
            flex-direction: column;
            align-items: center;
            gap: 25px;
            width: 100%;
            padding: 0;
        }

        .service-card {
            width: 100%;
            max-width: 320px;
            padding: 20px 15px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .service-logo-box {
            padding: 15px;
        }

        .service-logo-box img {
            width: 180px;
        }

        /* PROMO MOBILE */
        .promo-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .promo-title {
            font-size: 22px;
            padding: 0 10px;
        }

        .promo-subtitle {
            font-size: 13px;
            margin-bottom: 30px;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .promo-slider-container {
            padding: 0 20px;
            width: 100%;
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

        .slider-btn {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        /* FEATURES MOBILE */
        .features-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .features-title {
            font-size: 24px;
            margin-bottom: 40px;
            line-height: 1.4;
            padding: 0 10px;
        }

        .features-grid-6 {
            grid-template-columns: 1fr;
            gap: 20px;
            width: 100%;
        }

        .feature-card {
            padding: 25px 20px;
        }

        .feature-icon {
            font-size: 36px;
            margin-bottom: 15px;
            height: 60px;
        }

        .feature-label {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .feature-desc {
            font-size: 13px;
        }

        /* ARTICLES MOBILE */
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

        .view-all-articles {
            padding: 10px 25px;
            font-size: 13px;
        }

        /* REVIEW MOBILE */
        .feedback-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .feedback-container {
            padding: 30px 20px;
            border-radius: 20px;
            width: 100%;
        }

        .feedback-title {
            font-size: 24px;
            text-align: center;
        }

        .star-filter-buttons {
            justify-content: center;
        }

        .star-filter-btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .review-stats {
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .stat-item {
            flex: 0 0 calc(50% - 15px);
            min-width: 120px;
        }

        .review-item {
            padding: 15px;
        }

        .review-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }

        .reviewer-info {
            width: 100%;
        }

        .review-form-container {
            padding: 20px;
        }

        .form-title {
            font-size: 20px;
        }

        .form-textarea {
            width: 100%;
        }

        .star-rating-input i {
            font-size: 28px;
        }

        .btn-primary,
        .btn-close {
            padding: 12px;
            font-size: 16px;
        }

        /* Responsive floating buttons */
        .floating-cs-container {
            bottom: 20px;
            right: 20px;
        }

        .cs-button {
            width: 55px;
            height: 55px;
        }

        .cs-button i {
            font-size: 22px;
        }

        .cs-button::after {
            font-size: 12px;
            padding: 8px 12px;
        }
    }

    /* Small Mobile (480px and below) */
    @media (max-width: 480px) {
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

        .search-btn {
            padding: 12px;
            font-size: 13px;
        }

        /* Available Schedules Mobile */
        .available-schedules h2 {
            font-size: 20px;
        }

        .schedule-card .route {
            font-size: 16px;
        }

        /* Responsive floating buttons */
        .floating-cs-container {
            bottom: 15px;
            right: 15px;
            gap: 10px;
        }

        .cs-button {
            width: 50px;
            height: 50px;
        }

        .cs-button i {
            font-size: 20px;
        }

        .cs-button::after {
            font-size: 11px;
            padding: 6px 10px;
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

        .promo-image {
            height: 160px;
        }

        .stat-item {
            flex: 0 0 100%;
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

        /* Available Schedules Landscape */
        .available-schedules {
            padding: 30px 20px;
        }

        .schedules-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Fix untuk iOS Safari */
    @supports (-webkit-touch-callout: none) {
        .hero-section {
            height: -webkit-fill-available;
            min-height: -webkit-fill-available;
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
@endpush

@section('content')
@php
    use App\Models\MProfilePerusahaan;
    use App\Models\Promo;
    use App\Models\Artikel;
    use App\Models\DriverJadwal;
    use App\Models\Rute;
    use Carbon\Carbon;
    
    $profile = MProfilePerusahaan::first();

    // Data user dari session
    $user = session()->get('user', null);

    // ========== AMBIL DATA UNTUK DROPDOWN KOTA (ASAL & TUJUAN) - DARI FILE KEDUA ==========
    // Ambil daftar kota asal dan tujuan dari DriverJadwal yang aktif
    $jadwalsActive = DriverJadwal::with('jadwal.rutes')
        ->where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->get();

    // Ekstrak kota unik menggunakan getDetailRute()
    $kotaAsalList = $jadwalsActive->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })->filter()->unique()->values();

    $kotaTujuanList = $jadwalsActive->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_tujuan'] ?? null;
    })->filter()->unique()->values();

    // Jika tidak ada data, ambil dari Rute master
    if($kotaAsalList->isEmpty()) {
        $kotaAsalList = Rute::distinct()->pluck('kota_asal')->filter();
    }
    if($kotaTujuanList->isEmpty()) {
        $kotaTujuanList = Rute::distinct()->pluck('kota_tujuan')->filter();
    }

    // ========== PARAMETER PENCARIAN - DARI FILE KEDUA ==========
    $asalParam = request()->get('asal', '');
    $tujuanParam = request()->get('tujuan', '');
    $tanggalParam = request()->get('tanggal', date('Y-m-d'));
    $penumpangParam = request()->get('penumpang', 1);
    
    // Data jadwal untuk Available Schedules (ambil 4 jadwal terdekat)
    $jadwals = DriverJadwal::with(['jadwal.rutes', 'driver'])
        ->where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->orderBy('tanggal', 'asc')
        ->orderBy('waktu_keberangkatan', 'asc')
        ->take(4)
        ->get();

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
                'gambar' => asset('images/Promo.png' . $promo->gambar_promo),
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
                'gambar' => asset('images/Promo.jpg'),
                'periode' => '1 Mar - 31 Mar 2024',
            ]
        ];
    }

    // Data artikel
    $artikelsFromDB = Artikel::orderBy('tanggal_publikasi', 'desc')->take(3)->get();
    $articles = [];

    // List nama file foto kamu yang ada di public/images/
    $fotoKamu = [
        'AR1.png',
        'AR2.png',
        'AR3.png',
    ];

    foreach ($artikelsFromDB as $index => $artikel) {
        $fotoIndex = $index % count($fotoKamu);
        $namaFoto = $fotoKamu[$fotoIndex];

        $articles[] = [
            'id' => $artikel->id,
            'image' => asset('images/' . $namaFoto),
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
                'image' => asset('/images/AR1.png'),
                'category' => 'Tips & Trik',
                'title' => 'Tips Perjalanan Aman dengan Shuttle Selama Liburan',
                'excerpt' => 'Pelajari cara mempersiapkan perjalanan shuttle yang aman dan nyaman selama musim liburan untuk pengalaman terbaik.',
                'date' => '15 Maret 2024',
                'read_time' => '5 min read',
                'tags' => ['Perjalanan', 'Tips', 'Liburan'],
                'full_content' => '<h3>Persiapan Sebelum Perjalanan</h3><p>Perjalanan dengan shuttle selama liburan memerlukan persiapan yang matang. Pastikan Anda memesan tiket jauh-jauh hari untuk mendapatkan harga terbaik dan kursi pilihan. Smart Shuttle menawarkan pemesanan online yang mudah melalui website atau aplikasi kami.</p>',
                'author' => 'Admin SmartShuttle'
            ],
            [
                'id' => 2,
                'image' => asset('images/article2.jpg'),
                'category' => 'Promo',
                'title' => 'Diskon Spesial SmartSend untuk Pengiriman Paket',
                'excerpt' => 'Dapatkan diskon 25% untuk semua pengiriman paket antar kota melalui layanan SmartSend. Berlaku hingga akhir bulan.',
                'date' => '10 Maret 2024',
                'read_time' => '3 min read',
                'tags' => ['Promo', 'SmartSend', 'Diskon'],
                'full_content' => '<h3>Diskon SmartSend</h3><p>Nikmati diskon 25% untuk semua pengiriman paket antar kota melalui layanan SmartSend. Berlaku hingga akhir bulan Maret 2024.</p>',
                'author' => 'Admin SmartShuttle'
            ],
            [
                'id' => 3,
                'image' => asset('images/article3.jpg'),
                'category' => 'Berita',
                'title' => 'Rute Baru SmartShuttle: Jakarta - Bandung',
                'excerpt' => 'SmartShuttle kini melayani rute baru Jakarta - Bandung dengan armada terbaru dan fasilitas lengkap.',
                'date' => '5 Maret 2024',
                'read_time' => '4 min read',
                'tags' => ['Berita', 'Rute Baru', 'Jakarta-Bandung'],
                'full_content' => '<h3>Rute Baru Jakarta - Bandung</h3><p>SmartShuttle kini melayani rute baru Jakarta - Bandung dengan armada terbaru dan fasilitas lengkap untuk kenyamanan perjalanan Anda.</p>',
                'author' => 'Admin SmartShuttle'
            ]
        ];
    }

    $activeService = request()->get('service', 'shuttle');
@endphp

<!-- Hero Section -->
<div class="hero-section" style="background-image:url('{{ asset('images/bg.png') }}');">
    <div class="hero-content">
        <h1 class="hero-title">{{ $profile->nama_dagang ?? 'Smart Shuttle' }}</h1>
        <p class="hero-desc">
            {{ $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas anda' }}
        </p>
        <div class="hero-services">
            <!-- TIKET SHUTTLE - Tetap di halaman beranda -->
            <a href="{{ url()->current() }}" class="hero-service active" id="shuttle-link">
                <i class="fas fa-shuttle-van"></i>
                <span>Tiket Shuttle</span>
            </a>

            <!-- KIRIM PAKET - Langsung ke halaman smartsend (dari file pertama) -->
            <a href="{{ route('customer.smartsend') }}" class="hero-service" id="kirim-paket-link">
                <i class="fas fa-box"></i>
                <span>Kirim Paket</span>
            </a>

            <!-- SEWA ARMADA - Langsung ke halaman smartrent (dari file pertama) -->
            <a href="{{ route('customer.smartrent') }}" class="hero-service" id="sewa-armada-link">
                <i class="fas fa-car"></i>
                <span>Sewa Armada</span>
            </a>
        </div>
    </div>
</div>

<!-- Search Section (menggunakan form pencarian dari file kedua) -->
<div class="search-section">
    <div class="search-container">
        <!-- Form Tiket Shuttle (dari file kedua) -->
        <form action="{{ route('customer.showSearch') }}" method="GET" id="search-form" class="service-form">
            <div class="search-row">
                <div class="search-field">
                    <select class="search-input" id="departure-outlet" name="asal" required>
                        <option value="">Pilih Kota Asal</option>
                        @foreach($kotaAsalList as $kota)
                            <option value="{{ $kota }}" {{ $asalParam == $kota ? 'selected' : '' }}>
                                {{ $kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="search-field">
                    <select class="search-input" id="destination-outlet" name="tujuan" required>
                        <option value="">Pilih Kota Tujuan</option>
                        @foreach($kotaTujuanList as $kota)
                            <option value="{{ $kota }}" {{ $tujuanParam == $kota ? 'selected' : '' }}>
                                {{ $kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="search-field">
                    <input type="date" class="search-input" name="tanggal" 
                           value="{{ $tanggalParam }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="search-field">
                    <input type="number" class="search-input" name="penumpang" 
                           value="{{ $penumpangParam }}" min="1" max="10" placeholder="Penumpang">
                </div>
                <div class="search-btn-container">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> CEK SHUTTLE
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Divider -->
<div class="divider"></div>

<!-- Available Schedules Section (dari file kedua) -->
<section class="available-schedules">
    <div class="container">
        <h2><i class="fas fa-calendar-alt"></i> Jadwal Tersedia</h2>
        
        @if($jadwals->count() > 0)
        <div class="schedules-grid">
            @foreach($jadwals as $jadwal)
            @php
                $detail = $jadwal->getDetailRute();
                // Ensure we have a single Rute model (jadwal->rutes is a collection)
                $rute = null;
                if (isset($jadwal->jadwal) && isset($jadwal->jadwal->rutes)) {
                    try {
                        $rute = $jadwal->jadwal->rutes instanceof \Illuminate\Database\Eloquent\Collection
                            ? $jadwal->jadwal->rutes->first()
                            : $jadwal->jadwal->rutes;
                    } catch (\Exception $e) {
                        $rute = null;
                    }
                }
                $jamBerangkat = \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i');
                $jamTiba = \Carbon\Carbon::parse($jadwal->waktu_kedatangan)->format('H:i');
                $tanggal = \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y');
            @endphp
            
            <div class="schedule-card">
                <div class="route">
                    {{ $detail['kota_asal'] ?? 'N/A' }} → {{ $detail['kota_tujuan'] ?? 'N/A' }}
                </div>
                
                <div class="schedule-details">
                    <p><i class="far fa-clock"></i> {{ $jamBerangkat }} - {{ $jamTiba }}</p>
                    <p><i class="far fa-calendar"></i> {{ $tanggal }}</p>
                    
                    @if($rute)
                    <p><i class="fas fa-route"></i> {{ $rute->nama_rute ?? 'Rute' }}</p>
                    @endif
                    
                    @php $shuttle = $jadwal->shuttle ?? null; @endphp
                    @if($shuttle)
                    <p><i class="fas fa-bus"></i> {{ $shuttle->nama_shuttle ?? ($jadwal->armada ?? 'Armada') }}</p>
                    @endif
                    
                    @if($jadwal->driver)
                    <p><i class="fas fa-user"></i> {{ $jadwal->driver->nama_driver ?? 'Driver' }}</p>
                    @endif
                </div>
                
                <a href="{{ route('customer.showSearch', [
                    'asal' => $detail['kota_asal'] ?? '',
                    'tujuan' => $detail['kota_tujuan'] ?? '',
                    'tanggal' => $jadwal->tanggal
                ]) }}" class="btn-book">
                    <i class="fas fa-ticket-alt"></i> Pesan Sekarang
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-schedules" style="text-align: center; padding: 30px; background: white; border-radius: 10px;">
            <i class="fas fa-calendar-times" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
            <h3 style="color: #666; margin-bottom: 10px;">Belum ada jadwal tersedia</h3>
            <p style="color: #888;">Silakan cari jadwal menggunakan form pencarian di atas</p>
        </div>
        @endif
    </div>
</section>

<!-- Services Section (dari file pertama) -->
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

<!-- Promo Section (dari file pertama) -->
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

<!-- Features Section (dari file pertama) -->
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

<!-- Articles Section (dari file pertama) -->
<section class="articles-section">
    <h2 class="articles-title">Artikel & Berita Terbaru</h2>
    <p class="articles-subtitle">
        Dapatkan informasi terbaru seputar layanan transportasi, tips perjalanan, dan berita terbaru dari Smart Shuttle.
    </p>

    <div class="articles-grid">
        @foreach($articles as $index => $article)
        <div class="article-card">
            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="article-image">
            <div class="article-content">
                <span class="article-category">{{ $article['category'] }}</span>
                <h3 class="article-title">{{ $article['title'] }}</h3>
                <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                <div class="article-meta">
                    <div class="article-date">
                        <i class="far fa-calendar-alt"></i>
                        {{ $article['date'] }}
                    </div>
                    @php
                        $artikelModel = \App\Models\Artikel::find($article['id']);
                        $slug = $artikelModel ? $artikelModel->slug : $article['id'];
                    @endphp

                    <a href="{{ route('customer.artikel.detail', $slug) }}" class="article-read-more">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <a href="{{ route('customer.artikel') }}" class="view-all-articles">
        Lihat Semua Artikel <i class="fas fa-arrow-right"></i>
    </a>
</section>

<!-- Feedback Section (dari file pertama) -->
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

<!-- Floating Customer Service Buttons (dari file pertama) -->
<div class="floating-cs-container">
    <!-- WhatsApp Button -->
    <a href="https://wa.me/6285811224321?text=Halo%20Smart%20Shuttle%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20shuttle."
       target="_blank"
       class="cs-button whatsapp"
       data-tooltip="Chat WhatsApp"
       title="Chat via WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Phone Button -->
    <a href="tel:+6285811224321"
       class="cs-button phone"
       data-tooltip="Telepon Customer Service"
       title="Telepon Customer Service">
        <i class="fas fa-phone"></i>
    </a>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SMART SHUTTLE INIT ===');

    /* ========== SELECT2 INITIALIZATION - PERBAIKAN ========== */
    function initializeSelect2() {
        console.log('Initializing Select2...');

        // Hapus select2 yang sudah ada
        if ($('#departure-outlet').data('select2')) {
            $('#departure-outlet').select2('destroy');
        }

        if ($('#destination-outlet').data('select2')) {
            $('#destination-outlet').select2('destroy');
        }

        // Inisialisasi ulang dengan konfigurasi minimal (menggunakan placeholder dari file kedua)
        $('#departure-outlet').select2({
            placeholder: "Pilih Kota Asal",
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 3,
            dropdownParent: $('#search-form')
        });

        $('#destination-outlet').select2({
            placeholder: "Pilih Kota Tujuan",
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 3,
            dropdownParent: $('#search-form')
        });

        console.log('✓ Select2 initialized for kota dropdowns');
    }

    // Inisialisasi dengan delay kecil
    setTimeout(initializeSelect2, 300);

    /* ========== PROMO SLIDER ========== */
    const promoSlider = document.getElementById('promo-track');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const sliderDots = document.getElementById('slider-dots');

    if (promoSlider && promoSlider.children.length > 0) {
        let currentSlide = 0;
        const totalSlides = promoSlider.children.length;

        // Create dots
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = 'slider-dot';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            sliderDots.appendChild(dot);
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            promoSlider.style.transform = `translateX(-${currentSlide * 100}%)`;

            // Update dots
            document.querySelectorAll('.slider-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Next slide
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                goToSlide(currentSlide);
            });
        }

        // Previous slide
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                goToSlide(currentSlide);
            });
        }

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }, 5000);
    }

    console.log('=== INITIALIZATION COMPLETE ===');
});
</script>
@endpush
@endsection