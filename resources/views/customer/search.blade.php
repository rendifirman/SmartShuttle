@extends('layouts.app')

@section('title', 'Cari Shuttle - Smart Shuttle')

@push('styles')
<style>
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
        --accent-color: #ff7b4d;
        --card-bg: #ffffff;
        --light-bg: #f8f9fa;
        --muted-text: #6c757d;
    }

    .container-fluid {
        padding: 0;
        margin: 0;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    .main-content {
        display: flex;
        min-height: calc(100vh - 80px);
        margin: 0;
        width: 100%;
        max-width: 100%;
    }

    .left-panel {
        flex: 0 0 320px;
        max-width: 320px;
        background-color: var(--card-bg);
        padding: 25px 20px;
        box-shadow: 3px 0 15px rgba(0,0,0,0.05);
        border-right: 1px solid #e9ecef;
        height: 100vh;
        position: sticky;
        top: 0;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--secondary-color) #f1f1f1;
    }

    .left-panel::-webkit-scrollbar {
        width: 6px;
    }

    .left-panel::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .left-panel::-webkit-scrollbar-thumb {
        background: var(--secondary-color);
        border-radius: 10px;
    }

    .right-panel {
        flex: 1;
        min-width: 0;
        padding: 20px;
        background-color: var(--light-bg);
        width: calc(100% - 320px);
        max-width: calc(100% - 320px);
    }

    .search-section {
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 0;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
    }

    .search-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--secondary-color) 0%, var(--accent-color) 100%);
    }

    .search-section h4 {
        color: var(--primary-color);
        margin-bottom: 24px !important;
        font-weight: 700;
        font-size: 18px;
        position: relative;
        padding-bottom: 12px;
    }

    .search-section h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
        box-sizing: border-box;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        font-size: 14px;
        gap: 6px;
    }

    .form-label i {
        color: var(--secondary-color);
        font-size: 12px;
    }

    /* PERBAIKAN UTAMA: Membuat semua form control konsisten */
    .form-control,
    .form-control-select,
    input[type="date"],
    input[type="number"] {
        display: block;
        width: 100%;
        box-sizing: border-box;
        height: 44px !important;
        min-height: 44px;
        line-height: 1.2;
        padding: 10px 15px !important;
        font-size: 14px;
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        background: white;
        transition: all 0.25s ease;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        color: inherit;
        font-family: inherit;
        margin: 0;
    }

    /* Khusus untuk date input di beberapa browser */
    input[type="date"] {
        position: relative;
        padding-right: 15px !important;
    }

    /* Custom arrow untuk select */
    .form-control-select {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23123352' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px;
        padding-right: 45px !important;
    }

    /* Hilangkan spinner di input number */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Fokus seragam */
    .form-control:focus,
    .form-control-select:focus,
    input[type="date"]:focus,
    input[type="number"]:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 4px rgba(255, 88, 30, 0.12);
        transform: translateY(-1px);
    }

    /* Placeholder styling */
    .form-control::placeholder,
    input[type="date"]::placeholder,
    input[type="number"]::placeholder {
        color: #999;
        opacity: 0.8;
    }

    /* Penyesuaian untuk browser WebKit (Chrome, Safari) */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        input[type="date"],
        input[type="number"] {
            line-height: 1.2 !important;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0.5;
            cursor: pointer;
            margin-left: 5px;
        }
    }

    /* Penyesuaian untuk Firefox */
    @-moz-document url-prefix() {
        input[type="date"],
        input[type="number"] {
            padding: 9px 14px !important;
        }
    }

    .btn-search {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-search:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
    }

    .btn-search:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .loading-spinner {
        display: none;
        text-align: center;
        padding: 40px 20px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--accent-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .results-section {
        margin-bottom: 0;
        width: 100%;
        max-width: 100%;
    }

    .result-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
        background-color: var(--card-bg);
        animation: fadeIn 0.5s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .shuttle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        gap: 16px;
    }

    .shuttle-info {
        flex: 1;
        min-width: 0;
    }

    .shuttle-type {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 16px;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .shuttle-name {
        font-size: 13px;
        color: var(--muted-text);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .route-info {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 8px;
        gap: 8px;
    }

    .time-info {
        font-weight: 600;
        font-size: 15px;
        color: var(--primary-color);
    }

    .route-arrow {
        color: var(--accent-color);
        font-weight: bold;
    }

    .right-section {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        flex-shrink: 0;
    }

    .price-info {
        text-align: right;
    }

    .price {
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 18px;
    }

    .per-kursi {
        font-size: 11px;
        color: var(--muted-text);
    }

    .btn-pilih {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        min-width: 90px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-pilih:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 88, 30, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-pilih:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .location-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .location {
        font-size: 13px;
        color: var(--muted-text);
        font-weight: 500;
        max-width: 45%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .route-details-toggle {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 8px;
    }

    .btn-filter, .btn-route {
        background-color: transparent;
        color: var(--primary-color);
        border: 1.5px solid var(--primary-color);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        flex: 1;
    }

    .btn-filter:hover, .btn-route:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .route-details, .shuttle-details {
        display: none;
        margin-top: 12px;
        padding: 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        border-left: 4px solid var(--secondary-color);
    }

    .route-details h4, .shuttle-details h4 {
        margin-bottom: 12px;
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 600;
    }

    .route-stop {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #dee2e6;
    }

    .route-stop:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .stop-location {
        font-weight: 500;
        color: var(--primary-color);
        font-size: 13px;
    }

    .stop-time {
        color: var(--muted-text);
        font-size: 12px;
    }

    .info-section {
        margin-bottom: 20px;
    }

    .info-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted-text);
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 100%;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #dee2e6;
    }

    .empty-state h3 {
        margin-bottom: 12px;
        color: var(--muted-text);
        font-weight: 600;
        font-size: 18px;
    }

    .empty-state p {
        font-size: 14px;
        line-height: 1.5;
        max-width: 350px;
        margin: 0 auto;
    }

    .search-summary {
        background: white;
        padding: 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    .search-summary h4 {
        margin-bottom: 12px;
        color: var(--primary-color);
        font-size: 16px;
        font-weight: 600;
    }

    .search-info {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .search-info-item {
        display: flex;
        flex-direction: column;
    }

    .search-info-label {
        font-size: 11px;
        color: var(--muted-text);
        margin-bottom: 3px;
        font-weight: 500;
    }

    .search-info-value {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 13px;
    }

    /* New Styles */
    .shuttle-status {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-available {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-almost-full {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-full {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .seat-count {
        font-size: 13px;
        color: #6b7280;
    }

    .route-tag {
        display: inline-block;
        background: #f3f4f6;
        color: #4b5563;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .route-tag.direct {
        background: #dbeafe;
        color: #1e40af;
    }

    .route-tag.transit {
        background: #f0f9ff;
        color: #0369a1;
    }

    .shuttle-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .facility-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #6b7280;
        background: #f9fafb;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .route-duration {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .outlet-selection {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 16px;
    }

    .outlet-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .outlet-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .outlet-details h5 {
        margin: 0;
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 600;
    }

    .outlet-details p {
        margin: 0;
        font-size: 12px;
        color: var(--muted-text);
    }

    .search-filters {
        background: white;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e9ecef;
    }

    .filter-section {
        margin-bottom: 16px;
    }

    .filter-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 8px;
    }

    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-btn {
        padding: 8px 16px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-btn:hover {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }

    .filter-btn.active {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }

    /* Shuttle Info Styles */
    .shuttle-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .shuttle-info-item {
        background: #ffffff;
        padding: 12px;
        border-radius: 8px;
        border-left: 4px solid var(--secondary-color);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .shuttle-info-item i {
        color: var(--secondary-color);
        margin-right: 8px;
        font-size: 14px;
    }

    .shuttle-info-item strong {
        color: var(--primary-color);
        font-size: 13px;
    }

    .shuttle-gallery {
        margin: 20px 0;
    }

    .shuttle-gallery h6 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 14px;
        font-weight: 600;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        height: 150px;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px;
        text-align: center;
        font-size: 12px;
    }

    .fasilitas-section {
        background: #ffffff;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .fasilitas-section h6 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 14px;
        font-weight: 600;
    }

    .fasilitas-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
    }

    .fasilitas-list-item {
        display: flex;
        align-items: center;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 5px;
        font-size: 13px;
    }

    .fasilitas-list-item i {
        color: #28a745;
        margin-right: 10px;
        font-size: 12px;
    }

    .shuttle-status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 12px;
        display: inline-block;
    }

    .status-aktif {
        background-color: #d4edda;
        color: #155724;
    }

    .status-nonaktif {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-servis {
        background-color: #fff3cd;
        color: #856404;
    }

    .info-divider {
        height: 1px;
        background: #e9ecef;
        margin: 15px 0;
    }

    /* DYNAMIC SEAT LAYOUT STYLES - DIBAWAH INI YANG BARU */
    .dynamic-seat-layout {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .seat-row-dynamic {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .seat-dynamic {
        width: 45px;
        height: 45px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 8px;
        font-weight: bold;
        font-size: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 2px solid #28a745;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .seat-dynamic.seat-premium {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%) !important;
        border-color: #FF8C00 !important;
        color: #000 !important;
    }

    .seat-dynamic.seat-middle {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        border-color: #0056b3 !important;
    }

    .seat-number {
        font-size: 13px;
        font-weight: 700;
        line-height: 1;
    }

    .seat-badge-premium {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(255, 215, 0, 0.9);
        color: #000;
        border-radius: 50%;
        width: 12px;
        height: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6px;
    }

    .seat-price-extra {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        font-size: 8px;
        padding: 1px;
        text-align: center;
    }

    /* Seat Information Panel */
    .seat-info-panel {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #495057;
    }

    .info-icon.available {
        background: #d1fae5;
        color: #065f46;
    }

    .info-icon.premium {
        background: #fef3c7;
        color: #92400e;
    }

    .info-text {
        flex: 1;
    }

    .info-label {
        font-size: 11px;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 700;
        color: #343a40;
    }

    /* Seat Legend */
    .seat-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid;
    }

    .legend-box.seat-regular {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-color: #28a745;
    }

    .legend-box.seat-premium-legend {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-color: #FF8C00;
    }

    .legend-box.seat-middle-legend {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-color: #0056b3;
    }

    .legend-box.seat-sold {
        background: #6c757d;
        border-color: #495057;
    }

    .legend-text {
        font-size: 12px;
        color: #495057;
        font-weight: 500;
    }

    /* Animation for seat hover */
    .seat-dynamic:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        cursor: pointer;
    }

    /* Status colors for seats */
    .seat-dynamic.status-tersedia {
        opacity: 1;
    }

    .seat-dynamic.status-terpesan {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
        border-color: #495057 !important;
        opacity: 0.7;
        cursor: not-allowed;
    }

    .seat-dynamic.status-terpesan:hover {
        transform: none;
        box-shadow: none;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .left-panel {
            flex: 0 0 280px;
            max-width: 280px;
        }

        .right-panel {
            width: calc(100% - 280px);
            max-width: calc(100% - 280px);
        }
    }

    @media (max-width: 992px) {
        .main-content {
            flex-direction: column;
        }

        .left-panel {
            flex: 0 0 auto;
            max-width: 100%;
            width: 100%;
            position: static;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
            height: auto;
        }

        .right-panel {
            width: 100%;
            max-width: 100%;
            flex: 1;
        }

        .search-section {
            max-width: 500px;
            margin: 0 auto;
        }

        /* Penyesuaian form controls di mobile */
        .form-control,
        .form-control-select,
        input[type="date"],
        input[type="number"] {
            height: 48px !important;
            min-height: 48px;
            font-size: 15px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            margin-bottom: 15px;
        }

        .left-panel, .right-panel {
            padding: 15px;
        }

        .search-section {
            padding: 20px;
        }

        .shuttle-header {
            flex-direction: column;
            gap: 12px;
        }

        .right-section {
            align-items: stretch;
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .price-info {
            text-align: left;
        }

        .btn-pilih {
            width: auto;
            min-width: 100px;
        }

        .search-info {
            flex-direction: column;
            gap: 12px;
        }

        .route-details-toggle {
            flex-direction: column;
        }

        .search-summary {
            padding: 15px;
        }

        .result-card {
            padding: 15px;
        }

        .location {
            max-width: 40%;
        }

        .outlet-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .filter-options {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
            text-align: center;
        }

        /* Shuttle info responsive */
        .shuttle-info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .fasilitas-list {
            grid-template-columns: 1fr;
        }

        /* Dynamic seat layout responsive */
        .seat-dynamic {
            width: 40px;
            height: 40px;
            font-size: 11px;
        }

        .seat-row-dynamic {
            gap: 8px;
        }

        .info-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .left-panel, .right-panel {
            padding: 12px 10px;
        }

        .search-section {
            padding: 16px;
        }

        .empty-state {
            padding: 40px 15px;
        }

        .empty-state i {
            font-size: 40px;
        }

        .info-title {
            font-size: 18px;
        }

        .right-section {
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
        }

        .price-info {
            text-align: center;
        }

        .btn-pilih {
            width: 100%;
        }

        /* Penyesuaian form controls di mobile kecil */
        .form-control,
        .form-control-select,
        input[type="date"],
        input[type="number"] {
            height: 46px !important;
            min-height: 46px;
            padding: 8px 12px !important;
        }

        .gallery-item {
            height: 120px;
        }

        /* Dynamic seat layout responsive small */
        .seat-dynamic {
            width: 35px;
            height: 35px;
            font-size: 10px;
        }

        .info-row {
            grid-template-columns: 1fr;
        }

        .seat-legend {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    /* Utility Classes */
    .text-center { text-align: center; }
    .w-100 { width: 100%; }
    .mb-0 { margin-bottom: 0; }
    .mt-0 { margin-top: 0; }

    /* Additional styling untuk memastikan konsistensi */
    .uniform-input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Styling untuk placeholder date di browser yang tidak support */
    input[type="date"]:invalid::-webkit-datetime-edit {
        color: #999;
    }

    /* Pastikan button search memiliki tinggi yang konsisten */
    #search-button {
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <!-- Panel Kiri: Form Pencarian -->
        <div class="left-panel">
            <div class="search-section">
                <h4 class="mb-0" style="color: var(--primary-color); margin-bottom: 16px !important; font-weight: 600; font-size: 16px;">Cari Shuttle</h4>
                <form id="search-form" action="{{ route('customer.search') }}" method="GET">
                    <div class="form-group">
                        <label class="form-label">Outlet Asal</label>
                        <select class="form-control-select" id="departure-outlet" name="departure_outlet" required>
                            <option value="">Pilih Outlet Asal</option>
                            @foreach($outletsGrouped as $kota => $outletGroup)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outletGroup as $outlet)
                                        <option value="{{ $outlet['id'] }}"
                                            {{ isset($validated['departure_outlet']) && $validated['departure_outlet'] == $outlet['id'] ? 'selected' :
                                            (request('departure_outlet') == $outlet['id'] ? 'selected' : '') }}>
                                            {{ $outlet['nama_outlet'] }} - {{ $outlet['alamat'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @if(isset($validated['departure_outlet_data']))
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 4px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $validated['departure_outlet_data']->alamat_lengkap }}
                            </small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Outlet Tujuan</label>
                        <select class="form-control-select" id="destination-outlet" name="destination_outlet" required>
                            <option value="">Pilih Outlet Tujuan</option>
                            @foreach($outletsGrouped as $kota => $outletGroup)
                                <optgroup label="{{ $kota }}">
                                    @foreach($outletGroup as $outlet)
                                        <option value="{{ $outlet['id'] }}"
                                            {{ isset($validated['destination_outlet']) && $validated['destination_outlet'] == $outlet['id'] ? 'selected' :
                                            (request('destination_outlet') == $outlet['id'] ? 'selected' : '') }}>
                                            {{ $outlet['nama_outlet'] }} - {{ $outlet['alamat'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @if(isset($validated['destination_outlet_data']))
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 4px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $validated['destination_outlet_data']->alamat_lengkap }}
                            </small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="departure-date" name="departure_date"
                               value="{{ isset($validated['departure_date']) ? $validated['departure_date'] :
                                       (request('departure_date') ? request('departure_date') : \Carbon\Carbon::today()->format('Y-m-d')) }}"
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penumpang</label>
                        <input type="number" class="form-control" id="passenger-count" name="passenger_count"
                               value="{{ isset($validated['passenger_count']) ? $validated['passenger_count'] :
                                       (request('passenger_count') ? request('passenger_count') : 1) }}" min="1" max="10" required>
                    </div>
                    <button type="submit" class="btn-search" id="search-button">
                        <span id="search-text">CARI SHUTTLE</span>
                        <div class="spinner" id="search-spinner" style="display: none;"></div>
                    </button>
                </form>
            </div>
        </div>

        <!-- Panel Kanan: Hasil Pencarian -->
        <div class="right-panel">
            <div id="results-container">
                <!-- Summary Pencarian -->
                @if(isset($validated) && isset($validated['departure_outlet_data']) && isset($validated['destination_outlet_data']))
                <div class="outlet-selection">
                    <div class="outlet-info">
                        <div class="outlet-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="outlet-details">
                            <h5>{{ $validated['departure_outlet_data']->nama_outlet ?? 'Outlet Asal' }}</h5>
                            <p>{{ $validated['departure_outlet_data']->alamat_lengkap ?? '' }}</p>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <i class="fas fa-arrow-right" style="color: var(--secondary-color); font-size: 20px;"></i>
                        </div>
                        <div class="outlet-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="outlet-details">
                            <h5>{{ $validated['destination_outlet_data']->nama_outlet ?? 'Outlet Tujuan' }}</h5>
                            <p>{{ $validated['destination_outlet_data']->alamat_lengkap ?? '' }}</p>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 8px;">
                      <span style="font-size: 12px; color: var(--muted-text);">
    {{ \Carbon\Carbon::parse($validated['departure_date'])->locale('id')->isoFormat('dddd, D MMMM YYYY') }} •
    {{ $validated['passenger_count'] }} Penumpang
</span>
                    </div>
                </div>

                <!-- Search Filters -->
                <div class="search-filters">
                    <div class="filter-section">
                        <div class="filter-title">Filter Waktu Keberangkatan</div>
                        <div class="filter-options">
                            <button class="filter-btn active" data-filter="all">Semua</button>
                            <button class="filter-btn" data-filter="morning">Pagi (06:00-12:00)</button>
                            <button class="filter-btn" data-filter="afternoon">Siang (12:00-18:00)</button>
                            <button class="filter-btn" data-filter="evening">Malam (18:00-24:00)</button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Loading state -->
                <div class="loading-spinner" id="loading-results" style="display: none;">
                    <div class="spinner"></div>
                    <p style="color: var(--muted-text); font-size: 14px; margin: 0;">Mencari shuttle tersedia...</p>
                </div>

                <!-- Hasil pencarian akan ditampilkan di sini -->
                <div class="results-section" id="results-section">
                    <h3 class="info-title">Shuttle Tersedia</h3>
                    <div id="search-results">
                        @if(isset($validated))
                            @if(!isset($jadwals) || $jadwals->isEmpty())
                                <div class="empty-state" id="no-results-state">
                                    <i class="fas fa-times-circle"></i>
                                    <h3>Jadwal Tidak Tersedia</h3>
                                    @if(isset($validated) && isset($validated['departure_outlet_data']) && isset($validated['destination_outlet_data']))
                                        <p>Maaf, tidak ada jadwal shuttle yang tersedia dari
                                            {{ $validated['departure_outlet_data']->nama_outlet }} ke
                                            {{ $validated['destination_outlet_data']->nama_outlet }}
                                            pada tanggal {{ \Carbon\Carbon::parse($validated['departure_date'])->isoFormat('D MMM YYYY') }}.</p>
                                    @endif
                                    <p style="font-size: 13px; margin-top: 8px;">Silakan pilih outlet lain atau tanggal lain.</p>
                                    <button onclick="window.location.reload()" style="margin-top: 16px; background: var(--secondary-color); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;">
                                        <i class="fas fa-redo"></i> Cari Ulang
                                    </button>
                                </div>
                            @else
                                <!-- Results Counter -->
                                <div style="margin-bottom: 16px; color: var(--muted-text); font-size: 14px;">
                                    {{ $jadwals->count() }} jadwal tersedia
                                </div>

                                @foreach($jadwals as $jadwal)
                                @php
                                    $shuttle = $jadwal->shuttle;
                                    $fasilitasArray = $shuttle->fasilitas_array ?? [];
                                @endphp
                                <div class="result-card" data-departure-time="{{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }}">
                                    <div class="shuttle-header">
                                        <div class="shuttle-info">
                                            <div class="shuttle-type">{{ $shuttle->nama_shuttle ?? 'Smart Shuttle' }}</div>

                                            <!-- Route Tags -->
                                            <div style="margin-bottom: 8px;">
                                                <span class="route-tag direct">
                                                    <i class="fas fa-bus"></i> {{ $jadwal->rute_string ?? 'Rute Tidak Diketahui' }}
                                                </span>
                                                @if($shuttle->tipe_shuttle)
                                                <span class="route-tag">
                                                    <i class="fas fa-star"></i> {{ $shuttle->tipe_shuttle }}
                                                </span>
                                                @endif
                                            </div>

                                            <div class="route-info">
                                                <div class="time-info" style="font-size: 16px;">
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }}
                                                    <span style="font-size: 12px; color: var(--muted-text); margin-left: 4px;">
                                                        {{ $validated['departure_city'] }}
                                                    </span>
                                                </div>
                                                <div class="route-arrow" style="margin: 0 12px;">
                                                    <i class="fas fa-long-arrow-alt-right"></i>
                                                </div>
                                                <div class="time-info" style="font-size: 16px;">
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_kedatangan)->format('H:i') }}
                                                    <span style="font-size: 12px; color: var(--muted-text); margin-left: 4px;">
                                                        {{ $validated['destination_city'] }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Duration -->
                                            <div class="route-duration">
                                                <i class="far fa-clock"></i>
                                                Estimasi: {{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)
                                                    ->diff(\Carbon\Carbon::parse($jadwal->waktu_kedatangan))
                                                    ->format('%h jam %i menit') }}
                                            </div>

                                            <!-- Shuttle Status -->
                                            <div class="shuttle-status">
                                                @php
                                                    $seatPercentage = ($jadwal->kursi_tersedia / ($shuttle->kapasitas_kursi ?? 12)) * 100;
                                                    if ($seatPercentage > 50) {
                                                        $statusClass = 'status-available';
                                                        $statusText = 'Tersedia';
                                                    } elseif ($seatPercentage > 20) {
                                                        $statusClass = 'status-almost-full';
                                                        $statusText = 'Hampir Penuh';
                                                    } else {
                                                        $statusClass = 'status-full';
                                                        $statusText = 'Terbatas';
                                                    }
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                                <span class="seat-count">
                                                    <i class="fas fa-chair"></i> {{ $jadwal->kursi_tersedia }} kursi tersedia
                                                </span>
                                            </div>

                                            <!-- Shuttle Facilities -->
                                            @if(!empty($fasilitasArray))
                                            <div class="shuttle-facilities">
                                                @foreach(array_slice($fasilitasArray, 0, 3) as $fasilitasItem)
                                                <span class="facility-item">
                                                    @if(str_contains(strtolower($fasilitasItem), 'wifi'))
                                                        <i class="fas fa-wifi"></i>
                                                    @elseif(str_contains(strtolower($fasilitasItem), 'ac'))
                                                        <i class="fas fa-snowflake"></i>
                                                    @elseif(str_contains(strtolower($fasilitasItem), 'tv'))
                                                        <i class="fas fa-tv"></i>
                                                    @elseif(str_contains(strtolower($fasilitasItem), 'charger'))
                                                        <i class="fas fa-charging-station"></i>
                                                    @else
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                    {{ trim($fasilitasItem) }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>

                                        <div class="right-section">
                                            <div class="price-info">
                                                <div class="price">Rp {{ number_format($jadwal->harga_total, 0, ',', '.') }}</div>
                                                <div class="per-kursi">/kursi</div>
                                                @if($shuttle->kapasitas_kursi)
                                                <div style="font-size: 11px; color: var(--muted-text); margin-top: 2px;">
                                                    Total: Rp {{ number_format($jadwal->harga_total * $validated['passenger_count'], 0, ',', '.') }}
                                                </div>
                                                @endif
                                            </div>

                                            @if($jadwal->kursi_tersedia >= $validated['passenger_count'])
                                               <a href="{{ route('customer.pesan', [
                                                    'jadwal_id' => $jadwal->id,
                                                    'penumpang' => $validated['passenger_count'],
                                                    'outlet_asal' => $validated['departure_outlet'],
                                                    'outlet_tujuan' => $validated['destination_outlet']
                                                      ]) }}" class="btn-pilih">
                                                    <i class="fas fa-ticket-alt"></i> Pesan Sekarang
                                                </a>
                                            @else
                                                <button class="btn-pilih" disabled>
                                                    <i class="fas fa-times-circle"></i> Hanya {{ $jadwal->kursi_tersedia }} kursi
                                                </button>
                                            @endif

                                            <!-- Additional Info -->
                                            <div style="text-align: center; margin-top: 8px;">
                                                <span style="font-size: 11px; color: var(--muted-text);">
                                                    <i class="fas fa-shield-alt"></i> Terjamin & Terpercaya
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Route Details Toggle -->
                                    <div class="route-details-toggle">
                                        <button class="btn-route" onclick="toggleRouteDetails({{ $jadwal->id }})">
                                            <i class="fas fa-route"></i> Lihat Rute Detail
                                        </button>
                                        <button class="btn-filter" onclick="toggleShuttleInfo({{ $jadwal->id }})">
                                            <i class="fas fa-info-circle"></i> Info Shuttle
                                        </button>
                                    </div>

                                    <!-- Route Details (Collapsed) -->
                                    <div class="route-details" id="route-details-{{ $jadwal->id }}" style="display: none;">
                                        <h4>Rincian Perjalanan</h4>
                                        @if($jadwal->rutes && $jadwal->rutes->count() > 0)
                                            @foreach($jadwal->rutes as $index => $rute)
                                                <div style="margin-bottom: 16px;">
                                                    <h5 style="color: var(--primary-color); margin-bottom: 8px; font-size: 14px;">
                                                        {{ $rute->nama_rute }}
                                                    </h5>

                                                    <!-- Main Route -->
                                                    <div style="display: flex; align-items: center; margin-bottom: 12px; padding-left: 20px;">
                                                        <div style="width: 8px; height: 8px; background: var(--secondary-color); border-radius: 50%; margin-right: 12px;"></div>
                                                        <div>
                                                            <strong>{{ $rute->kota_asal }}</strong>
                                                            <div style="font-size: 12px; color: var(--muted-text);">
                                                                Keberangkatan: {{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Stops -->
                                                    @php
                                                        $stops = json_decode($rute->rute_pemberhentian, true) ?? [];
                                                        $currentTime = \Carbon\Carbon::parse($jadwal->waktu_keberangkatan);
                                                    @endphp

                                                    @foreach($stops as $stopIndex => $stop)
                                                        @php
                                                            // Add travel time to stop (simplified)
                                                            $currentTime->addMinutes(30); // Simplified travel time
                                                        @endphp
                                                        <div style="display: flex; align-items: center; margin-bottom: 12px; padding-left: 20px;">
                                                            <div style="width: 8px; height: 8px; background: #94a3b8; border-radius: 50%; margin-right: 12px;"></div>
                                                            <div>
                                                                <strong>{{ $stop['kota'] ?? 'Kota' }}</strong>
                                                                <div style="font-size: 12px; color: var(--muted-text);">
                                                                    {{ $currentTime->format('H:i') }} •
                                                                    Durasi: {{ $stop['durasi_singgah'] ?? 10 }} menit
                                                                </div>
                                                                @if(!empty($stop['outlets']))
                                                                <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                                                    Outlet: {{ implode(', ', $stop['outlets']) }}
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @php
                                                            $currentTime->addMinutes($stop['durasi_singgah'] ?? 10);
                                                        @endphp
                                                    @endforeach

                                                    <!-- Destination -->
                                                    @php
                                                        $currentTime->addMinutes(30); // Final travel time
                                                    @endphp
                                                    <div style="display: flex; align-items: center; padding-left: 20px;">
                                                        <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; margin-right: 12px;"></div>
                                                        <div>
                                                            <strong>{{ $rute->kota_tujuan }}</strong>
                                                            <div style="font-size: 12px; color: var(--muted-text);">
                                                                Kedatangan: {{ $currentTime->format('H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>Informasi rute tidak tersedia</p>
                                        @endif
                                    </div>

                                    <!-- Shuttle Details (Collapsed) - MENGGUNAKAN LAYOUT DINAMIS -->
                                    <div class="shuttle-details" id="shuttle-details-{{ $jadwal->id }}" style="display: none;">
                                        <h4><i class="fas fa-bus"></i> Informasi Shuttle</h4>

                                        <!-- Shuttle Specifications -->
                                        <div class="shuttle-info-grid">
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-bus"></i>
                                                <strong>Nama Shuttle:</strong> {{ $shuttle->nama_shuttle }}
                                            </div>
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-tag"></i>
                                                <strong>Tipe Shuttle:</strong> {{ $shuttle->tipe_shuttle ?? 'Standard' }}
                                            </div>
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-users"></i>
                                                <strong>Kapasitas:</strong> {{ $shuttle->kapasitas_kursi }} Penumpang
                                            </div>
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-car"></i>
                                                <strong>No. Polisi:</strong> {{ $shuttle->nomor_polisi ?? '-' }}
                                            </div>
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-info-circle"></i>
                                                <strong>Status:</strong>
                                                <span class="shuttle-status-badge status-{{ $shuttle->status ?? 'aktif' }}">
                                                    {{ ucfirst($shuttle->status ?? 'aktif') }}
                                                </span>
                                            </div>
                                            <!-- Tambahkan info total kursi jika berbeda dari kapasitas -->
                                            @if(isset($shuttle->total_kursi) && $shuttle->total_kursi != $shuttle->kapasitas_kursi)
                                            <div class="shuttle-info-item">
                                                <i class="fas fa-layer-group"></i>
                                                <strong>Layout Kursi:</strong> {{ $shuttle->total_kursi }} Posisi
                                            </div>
                                            @endif
                                        </div>

                                        <div class="info-divider"></div>

                                        <!-- Shuttle Gallery -->
                                        <div class="shuttle-gallery">
                                            <h6><i class="fas fa-images"></i> Gallery Shuttle</h6>
                                            <div class="gallery-grid">
                                                <div class="gallery-item">
                                                    <img src="{{ $shuttle->gambar_depan ? asset('storage/shuttles/' . $shuttle->gambar_depan) : 'https://via.placeholder.com/400x200?text=Tampak+Depan' }}"
                                                         alt="Tampak Depan">
                                                    <div class="gallery-caption">Tampak Depan</div>
                                                </div>
                                                <div class="gallery-item">
                                                    <img src="{{ $shuttle->gambar_samping ? asset('storage/shuttles/' . $shuttle->gambar_samping) : 'https://via.placeholder.com/400x200?text=Tampak+Samping' }}"
                                                         alt="Tampak Samping">
                                                    <div class="gallery-caption">Tampak Samping</div>
                                                </div>
                                                <div class="gallery-item">
                                                    <img src="{{ $shuttle->gambar_belakang ? asset('storage/shuttles/' . $shuttle->gambar_belakang) : 'https://via.placeholder.com/400x200?text=Tampak+Belakang' }}"
                                                         alt="Tampak Belakang">
                                                    <div class="gallery-caption">Tampak Belakang</div>
                                                </div>
                                                <div class="gallery-item">
                                                    <img src="{{ $shuttle->gambar_interior ? asset('storage/shuttles/' . $shuttle->gambar_interior) : 'https://via.placeholder.com/400x200?text=Interior+Shuttle' }}"
                                                         alt="Interior">
                                                    <div class="gallery-caption">Interior</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-divider"></div>

                                        <!-- Shuttle Facilities -->
                                        <div class="fasilitas-section">
                                            <h6><i class="fas fa-cogs"></i> Fasilitas Shuttle</h6>
                                            @if(!empty($fasilitasArray))
                                            <div class="fasilitas-list">
                                                @foreach($fasilitasArray as $fasilitasItem)
                                                <div class="fasilitas-list-item">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>{{ trim($fasilitasItem) }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p style="color: var(--muted-text); font-size: 13px;">Fasilitas tidak tersedia</p>
                                            @endif
                                        </div>

                                        <div class="info-divider"></div>

                                        <!-- DYNAMIC SEAT LAYOUT BASED ON DATABASE -->
                                        <div class="seat-layout-section">
                                            <h6>
                                                <i class="fas fa-chair"></i>
                                                Layout Kursi ({{ $shuttle->total_kursi ?? 9 }} Kursi)
                                                <small style="font-size: 12px; color: #666; margin-left: 8px;">
                                                    {{ $shuttle->seat_rows ?? 3 }} Baris × {{ $shuttle->seat_columns ?? 3 }} Kolom
                                                </small>
                                            </h6>

                                            <div class="dynamic-seat-layout">
                                                @if(isset($shuttle->seat_grid) && !empty($shuttle->seat_grid))
                                                    @foreach($shuttle->seat_grid as $rowIndex => $row)
                                                    <div class="seat-row-dynamic">
                                                        @foreach($row as $seat)
                                                        <div class="seat-dynamic
                                                            @if(($seat['tipe'] ?? 'reguler') === 'premium') seat-premium @endif
                                                            @if(($seat['posisi'] ?? '') === 'tengah') seat-middle @endif">

                                                            <div class="seat-number">{{ $seat['nomor'] ?? '?' }}</div>

                                                            @if(($seat['tipe'] ?? 'reguler') === 'premium')
                                                            <div class="seat-badge-premium">
                                                                <i class="fas fa-crown"></i>
                                                            </div>
                                                            @endif

                                                            @if(($seat['harga_tambahan'] ?? 0) > 0)
                                                            <div class="seat-price-extra">
                                                                +{{ number_format($seat['harga_tambahan'], 0, ',', '.') }}
                                                            </div>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <!-- Fallback: Generate layout secara manual -->
                                                    @php
                                                        $totalSeats = $shuttle->total_kursi ?? 9;
                                                        $rows = ceil($totalSeats / 3);
                                                        $seatCounter = 0;
                                                    @endphp

                                                    @for($row = 1; $row <= $rows; $row++)
                                                    <div class="seat-row-dynamic">
                                                        @for($col = 1; $col <= 3; $col++)
                                                            @php
                                                                if($seatCounter >= $totalSeats) break;
                                                                $colLetter = chr(64 + $col); // A, B, C
                                                                $seatNumber = $row . $colLetter; // 1A, 1B, 1C, 2A, dst
                                                                $seatCounter++;
                                                            @endphp

                                                            <div class="seat-dynamic">
                                                                <div class="seat-number">{{ $seatNumber }}</div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    @endfor
                                                @endif
                                            </div>

                                            <!-- Seat Information Panel -->
                                            <div class="seat-info-panel">
                                                <div class="info-row">
                                                    <div class="info-item">
                                                        <div class="info-icon">
                                                            <i class="fas fa-chair"></i>
                                                        </div>
                                                        <div class="info-text">
                                                            <div class="info-label">Total Kapasitas</div>
                                                            <div class="info-value">{{ $shuttle->kapasitas_kursi ?? 12 }} kursi</div>
                                                        </div>
                                                    </div>

                                                    <div class="info-item">
                                                        <div class="info-icon available">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <div class="info-text">
                                                            <div class="info-label">Tersedia</div>
                                                            <div class="info-value">{{ $jadwal->kursi_tersedia ?? 0 }} kursi</div>
                                                        </div>
                                                    </div>

                                                    <div class="info-item">
                                                        <div class="info-icon">
                                                            <i class="fas fa-layer-group"></i>
                                                        </div>
                                                        <div class="info-text">
                                                            <div class="info-label">Layout</div>
                                                            <div class="info-value">
                                                                {{ $shuttle->total_kursi ?? 9 }} kursi
                                                                ({{ ceil(($shuttle->total_kursi ?? 9) / 3) }}×3)
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(isset($shuttle->dynamic_seat_layout) && is_array($shuttle->dynamic_seat_layout))
                                                        @php
                                                            $premiumCount = collect($shuttle->dynamic_seat_layout)
                                                                ->where('tipe', 'premium')
                                                                ->count();
                                                        @endphp
                                                        @if($premiumCount > 0)
                                                        <div class="info-item">
                                                            <div class="info-icon premium">
                                                                <i class="fas fa-crown"></i>
                                                            </div>
                                                            <div class="info-text">
                                                                <div class="info-label">Kursi Premium</div>
                                                                <div class="info-value">{{ $premiumCount }} kursi</div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endif
                                                </div>

                                                <!-- Seat Legend -->
                                                <div class="seat-legend">
                                                    <div class="legend-item">
                                                        <div class="legend-box seat-regular"></div>
                                                        <span class="legend-text">Reguler</span>
                                                    </div>
                                                    <div class="legend-item">
                                                        <div class="legend-box seat-premium-legend"></div>
                                                        <span class="legend-text">Premium</span>
                                                    </div>
                                                    <div class="legend-item">
                                                        <div class="legend-box seat-middle-legend"></div>
                                                        <span class="legend-text">Tengah</span>
                                                    </div>
                                                    <div class="legend-item">
                                                        <div class="legend-box seat-sold"></div>
                                                        <span class="legend-text">Terjual</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        @else
                            <!-- State awal: belum ada pencarian -->
                            <div class="empty-state" id="empty-state">
                                <i class="fas fa-search"></i>
                                <h3>Cari Shuttle Anda</h3>
                                <p>Isi form pencarian di sebelah kiri untuk menemukan shuttle yang tersedia.</p>
                                <div style="margin-top: 20px; padding: 16px; background: #f8f9fa; border-radius: 8px; text-align: left;">
                                    <h5 style="color: var(--primary-color); margin-bottom: 8px;">Tips Pencarian:</h5>
                                    <ul style="font-size: 13px; color: var(--muted-text); padding-left: 20px; margin: 0;">
                                        <li>Pilih outlet asal dan tujuan</li>
                                        <li>Tentukan tanggal keberangkatan</li>
                                        <li>Masukkan jumlah penumpang</li>
                                        <li>Klik "CARI SHUTTLE" untuk melihat jadwal</li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    const resultCards = document.querySelectorAll('.result-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filter = this.dataset.filter;
            filterResults(filter);
        });
    });

    function filterResults(filter) {
        resultCards.forEach(card => {
            const departureTime = card.dataset.departureTime;
            const hour = parseInt(departureTime.split(':')[0]);

            let showCard = true;

            switch(filter) {
                case 'morning':
                    showCard = hour >= 6 && hour < 12;
                    break;
                case 'afternoon':
                    showCard = hour >= 12 && hour < 18;
                    break;
                case 'evening':
                    showCard = hour >= 18 && hour < 24;
                    break;
                // case 'all' or default
            }

            if (showCard) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                }, 10);
            } else {
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }

    // Toggle route details
    window.toggleRouteDetails = function(jadwalId) {
        const details = document.getElementById(`route-details-${jadwalId}`);
        const shuttleDetails = document.getElementById(`shuttle-details-${jadwalId}`);

        // Close shuttle details if open
        if (shuttleDetails && shuttleDetails.style.display === 'block') {
            shuttleDetails.style.display = 'none';
        }

        if (details.style.display === 'block') {
            details.style.display = 'none';
        } else {
            details.style.display = 'block';
        }
    }

    // Toggle shuttle info
    window.toggleShuttleInfo = function(jadwalId) {
        const details = document.getElementById(`shuttle-details-${jadwalId}`);
        const routeDetails = document.getElementById(`route-details-${jadwalId}`);

        // Close route details if open
        if (routeDetails && routeDetails.style.display === 'block') {
            routeDetails.style.display = 'none';
        }

        if (details.style.display === 'block') {
            details.style.display = 'none';
        } else {
            details.style.display = 'block';
        }
    }

    // Form submission
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const departureOutlet = document.getElementById('departure-outlet').value;
            const destinationOutlet = document.getElementById('destination-outlet').value;

            if (departureOutlet && destinationOutlet && departureOutlet === destinationOutlet) {
                alert('Outlet keberangkatan dan tujuan tidak boleh sama!');
                return;
            }

            // Show loading
            const searchButton = document.getElementById('search-button');
            const searchText = document.getElementById('search-text');
            const searchSpinner = document.getElementById('search-spinner');
            const loadingResults = document.getElementById('loading-results');

            searchText.style.display = 'none';
            if (searchSpinner) searchSpinner.style.display = 'block';
            if (searchButton) searchButton.disabled = true;
            if (loadingResults) loadingResults.style.display = 'block';

            // Submit form
            this.submit();
        });
    }

    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('departure-date');
    if (dateInput) {
        dateInput.setAttribute('min', today);
        if (!dateInput.value) {
            dateInput.value = today;
        }
    }

    // Force uniform height for form controls (additional safety)
    function enforceUniformHeight() {
        const formControls = document.querySelectorAll('.form-control, .form-control-select, input[type="date"], input[type="number"]');
        formControls.forEach(control => {
            control.style.height = '44px';
            control.style.minHeight = '44px';
            control.style.boxSizing = 'border-box';
        });
    }

    // Run on load and resize
    enforceUniformHeight();
    window.addEventListener('resize', enforceUniformHeight);
});
</script>
@endpush
