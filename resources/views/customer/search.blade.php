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
input[type="number"],
.searchable-select {
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
    background-image: url("data:image/svg+xml;charset=UTF8,%3csvg xmlns='http://www.w3.org2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23123352' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
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
input[type="number"]:focus,
.searchable-select:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 4px rgba(255, 88, 30, 0.12);
    transform: translateY(-1px);
}

/* Placeholder styling */
.form-control::placeholder,
input[type="date"]::placeholder,
input[type="number"]::placeholder,
.searchable-select::placeholder {
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

/* OUTLET SELECTION - UKURAN AWAL YANG LEBIH KECIL DAN RAPI */
.outlet-selection {
    background: rgba(255, 88, 31, 0.08);
    border: 1px solid rgba(255, 88, 31, 0.15);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.outlet-selection:hover {
    background: rgba(255, 88, 31, 0.12);
    border-color: rgba(255, 88, 31, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
}

.outlet-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: 100%;
}

.outlet-info::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 88, 31, 0.2), transparent);
    z-index: 1;
}

.outlet-item {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.outlet-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.outlet-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 88, 31, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid rgba(255, 88, 31, 0.15);
    transition: all 0.3s ease;
}

.outlet-item:hover .outlet-icon {
    background: rgba(255, 88, 31, 0.15);
    border-color: rgba(255, 88, 31, 0.25);
    transform: scale(1.05);
}

.outlet-icon i {
    font-size: 14px;
    color: var(--secondary-color);
}

.outlet-details {
    flex: 1;
    min-width: 0;
}

.outlet-details h5 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.outlet-details p {
    margin: 0;
    font-size: 11px;
    color: var(--muted-text);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.arrow-container {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    flex-shrink: 0;
    z-index: 2;
}

.arrow-container i {
    font-size: 14px;
    color: var(--secondary-color);
    background: rgba(255, 255, 255, 0.9);
    padding: 5px;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(255, 88, 31, 0.1);
    transition: all 0.3s ease;
}

.arrow-container:hover i {
    transform: scale(1.1);
    box-shadow: 0 3px 8px rgba(255, 88, 31, 0.2);
}

.search-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    padding-top: 10px;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    font-size: 11px;
    color: var(--primary-color);
    font-weight: 500;
}

.meta-item i {
    color: var(--secondary-color);
    font-size: 10px;
}

/* Responsive untuk outlet selection */
@media (max-width: 992px) {
    .outlet-info {
        flex-direction: column;
        gap: 12px;
    }

    .outlet-info::before {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(90deg);
        width: 60%;
    }

    .outlet-item {
        width: 100%;
        justify-content: flex-start;
    }

    .arrow-container {
        transform: rotate(90deg);
        padding: 8px 0;
    }

    .arrow-container i {
        transform: rotate(-90deg);
    }

    .search-meta {
        flex-direction: column;
        gap: 8px;
    }

    .meta-item {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .outlet-selection {
        padding: 12px;
        gap: 10px;
    }

    .outlet-item {
        padding: 8px 10px;
        gap: 10px;
    }

    .outlet-icon {
        width: 32px;
        height: 32px;
    }

    .outlet-icon i {
        font-size: 12px;
    }

    .outlet-details h5 {
        font-size: 13px;
    }

    .outlet-details p {
        font-size: 10px;
    }

    .arrow-container i {
        font-size: 12px;
        padding: 4px;
    }

    .meta-item {
        font-size: 10px;
        padding: 3px 8px;
    }
}

@media (max-width: 480px) {
    .outlet-info::before {
        display: none;
    }
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

/* Compact Carousel Styles */
.shuttle-gallery {
    margin: 20px 0;
    padding: 15px;
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}

.shuttle-gallery h6 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.shuttle-gallery h6 i {
    color: var(--secondary-color);
    font-size: 14px;
}

.compact-carousel {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #dee2e6;
}

.carousel-compact-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
}

.carousel-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    padding: 6px 12px;
    background: rgba(255, 88, 30, 0.08);
    border-radius: 6px;
    border-left: 3px solid var(--secondary-color);
}

.carousel-counter {
    font-size: 13px;
    color: var(--muted-text);
    background: white;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #dee2e6;
}

.carousel-compact-container {
    position: relative;
    width: 100%;
    height: 225px; /* 16:9 ratio - 400x225 */
    border-radius: 6px;
    overflow: hidden;
    background: #000;
    margin-bottom: 15px;
}

.carousel-track {
    display: flex;
    width: 400%;
    height: 100%;
    transition: transform 0.4s ease;
}

.carousel-slide {
    width: 25%; /* 4 slides = 25% each */
    height: 100%;
    flex-shrink: 0;
    padding: 2px;
}

.slide-img-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000;
}

.slide-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 4px;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid var(--secondary-color);
    border-radius: 50%;
    color: var(--secondary-color);
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 10;
    padding: 0;
}

.carousel-btn:hover {
    background: var(--secondary-color);
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.prev-btn {
    left: 10px;
}

.next-btn {
    right: 10px;
}

.carousel-dots-nav {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.carousel-dot {
    padding: 6px 12px;
    background: #e9ecef;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    font-size: 12px;
    color: var(--muted-text);
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.carousel-dot:hover {
    background: #dee2e6;
    border-color: var(--secondary-color);
    color: var(--primary-color);
}

.carousel-dot.active {
    background: var(--secondary-color);
    color: white;
    border-color: var(--secondary-color);
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .shuttle-gallery {
        padding: 12px;
    }

    .carousel-compact-container {
        height: 180px; /* Slightly smaller on mobile */
    }

    .carousel-title {
        font-size: 13px;
        padding: 5px 10px;
    }

    .carousel-counter {
        font-size: 12px;
        padding: 3px 8px;
    }

    .carousel-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }

    .carousel-dot {
        padding: 5px 10px;
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .carousel-compact-container {
        height: 150px;
    }

    .carousel-dots-nav {
        gap: 5px;
    }

    .carousel-dot {
        padding: 4px 8px;
        font-size: 10px;
    }
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

    /* STYLES UNTUK KOMBINASI DROPDOWN DAN SEARCH */
    .combo-dropdown {
        position: relative;
    }

    .combo-input-wrapper {
        position: relative;
    }

    .combo-input {
        width: 100%;
        padding-right: 40px;
        cursor: pointer;
    }

    .combo-dropdown-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 14px;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 4px;
    }

    .combo-dropdown-toggle:hover {
        color: var(--secondary-color);
        background: rgba(255, 88, 30, 0.1);
    }

    .combo-dropdown-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
        margin-top: 5px;
    }

    .combo-dropdown-results.show {
        display: block;
    }

    .combo-search-input {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .combo-search-input input {
        width: 100%;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .combo-search-input input:focus {
        border-color: var(--secondary-color);
    }

    .combo-options {
        max-height: 250px;
        overflow-y: auto;
    }

    .combo-optgroup {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .combo-optgroup-header {
        padding: 8px 15px;
        background: #f8f9fa;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 13px;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin-top: 5px;
    }

    .combo-optgroup-header:first-child {
        border-top: none;
        margin-top: 0;
    }

    .combo-option {
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f8f9fa;
    }

    .combo-option:hover {
        background-color: rgba(255, 88, 30, 0.08);
    }

    .combo-option.selected {
        background-color: rgba(255, 88, 30, 0.12);
        color: var(--secondary-color);
        font-weight: 500;
    }

    .combo-option:last-child {
        border-bottom: none;
    }

    .combo-option-main {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 2px;
    }

    .combo-option-detail {
        font-size: 11px;
        color: var(--muted-text);
        line-height: 1.4;
    }

    .combo-no-results {
        padding: 20px;
        text-align: center;
        color: var(--muted-text);
        font-size: 14px;
    }

    /* Clear button */
    .combo-clear-btn {
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        display: none;
        font-size: 14px;
        padding: 5px;
        z-index: 2;
    }

    .combo-clear-btn:hover {
        color: var(--secondary-color);
    }

    /* Side-by-side Layout for Gallery and Seats - PERBAIKAN UTAMA */
    .shuttle-media-combined {
        display: flex;
        gap: 20px;
        margin: 25px 0;
        align-items: stretch;
    }

    .shuttle-gallery-side {
        flex: 1;
        min-width: 0;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        min-height: 550px; /* Tambahkan tinggi minimum */
    }

    .seat-layout-side {
        flex: 1;
        min-width: 0;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        min-height: 550px; /* Tambahkan tinggi minimum */
    }

    /* Heading untuk kedua side */
    .shuttle-gallery-side h6,
    .seat-layout-side h6 {
        margin: 0 0 15px 0;
        padding: 0;
        color: var(--primary-color);
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 28px;
    }

    .shuttle-gallery-side h6 i,
    .seat-layout-side h6 i {
        color: var(--secondary-color);
        font-size: 14px;
    }

    /* Adjust Gallery untuk Side Layout */
    .shuttle-gallery-side .compact-carousel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #dee2e6;
        height: 100%;
        min-height: 400px; /* Tambahkan tinggi minimum untuk carousel */
    }

    .shuttle-gallery-side .carousel-compact-container {
        flex: 1;
        min-height: 200px;
        position: relative;
    }

    .shuttle-gallery-side .carousel-track {
        height: 100%;
    }

    .shuttle-gallery-side .carousel-slide {
        height: 100%;
    }

    .shuttle-gallery-side .slide-img-container {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .shuttle-gallery-side .slide-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Adjust Seat Layout untuk Side Layout */
    .seat-layout-side .dynamic-seat-layout {
        flex: 1;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 200px; /* Tinggi minimum untuk seat layout */
    }

    .seat-layout-side .seat-row-dynamic {
        gap: 8px;
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .seat-layout-side .seat-dynamic {
        width: 40px;
        height: 40px;
        font-size: 11px;
        flex-shrink: 0;
    }

    .seat-layout-side .seat-info-panel {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        flex-shrink: 0;
    }

    .seat-layout-side .info-row {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 15px;
    }

    .seat-layout-side .seat-legend {
        padding: 12px;
        margin-top: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Responsive Layout */
    @media (max-width: 1200px) {
        .shuttle-media-combined {
            gap: 20px;
        }

        .shuttle-gallery-side,
        .seat-layout-side {
            min-height: 500px; /* Sesuaikan untuk ukuran lebih kecil */
        }
    }

    @media (max-width: 992px) {
        .shuttle-media-combined {
            flex-direction: column;
            gap: 20px;
        }

        .shuttle-gallery-side,
        .seat-layout-side {
            width: 100%;
            min-height: auto; /* Hapus min-height untuk mobile */
        }

        .shuttle-gallery-side .compact-carousel {
            min-height: 300px; /* Tetap berikan tinggi untuk mobile */
        }

        .seat-layout-side .dynamic-seat-layout {
            min-height: auto;
        }
    }

    @media (max-width: 768px) {
        .seat-layout-side .seat-dynamic {
            width: 35px;
            height: 35px;
            font-size: 10px;
        }

        .seat-layout-side .info-row {
            grid-template-columns: 1fr;
        }
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
        input[type="number"],
        .combo-input {
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

        /* Combo dropdown responsive */
        .combo-dropdown-results {
            max-height: 250px;
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
        input[type="number"],
        .combo-input {
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

        .combo-option {
            padding: 8px 12px;
            font-size: 14px;
        }

        .combo-clear-btn {
            right: 40px;
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

    /* Carousel Styles */
.carousel {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
}

.carousel-inner {
    border-radius: 12px;
}

.carousel-item img {
    border-radius: 12px;
}

.carousel-indicators {
    bottom: 10px;
}

.carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    background-color: rgba(255, 255, 255, 0.5);
    margin: 0 4px;
}

.carousel-indicators button.active {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.carousel-control-prev,
.carousel-control-next {
    width: 40px;
    height: 40px;
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    margin: 0 15px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background-color: rgba(0, 0, 0, 0.5);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 20px;
    height: 20px;
}

.carousel-caption {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    padding: 8px 16px;
    left: 20px;
    right: 20px;
    bottom: 20px;
}

.carousel-caption .caption-bg {
    background: rgba(18, 51, 82, 0.8);
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
}

.carousel-caption h5 {
    color: white;
    font-size: 14px;
    margin: 0;
    font-weight: 600;
}

/* Thumbnail Styles */
.thumbnail-container {
    overflow-x: auto;
    padding-bottom: 10px;
}

.thumbnail-scroll {
    display: flex;
    gap: 10px;
    padding: 0 5px;
}

.thumbnail-item {
    flex: 0 0 auto;
    width: 80px;
    text-align: center;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.3s ease;
    position: relative;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    opacity: 1;
    transform: translateY(-2px);
}

.thumbnail-item.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--secondary-color);
    border-radius: 2px;
}

.thumbnail-item img {
    width: 100%;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.thumbnail-item.active img {
    border-color: var(--secondary-color);
}

.thumbnail-item:hover img {
    border-color: var(--accent-color);
}

.thumbnail-caption {
    font-size: 11px;
    color: var(--muted-text);
    margin-top: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .carousel-item img {
        height: 250px;
    }

    .carousel-caption {
        display: block !important;
        background: rgba(0, 0, 0, 0.6);
        padding: 5px 10px;
        left: 10px;
        right: 10px;
        bottom: 10px;
    }

    .carousel-caption h5 {
        font-size: 12px;
    }

    .thumbnail-item {
        width: 70px;
    }

    .thumbnail-item img {
        height: 50px;
    }
}

@media (max-width: 576px) {
    .carousel-item img {
        height: 200px;
    }

    .thumbnail-scroll {
        gap: 8px;
    }

    .thumbnail-item {
        width: 60px;
    }

    .thumbnail-item img {
        height: 40px;
    }
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
                    <!-- Outlet Asal dengan Combo Box -->
                    <div class="form-group combo-dropdown">
                        <label class="form-label">Outlet Asal</label>
                        <div class="combo-input-wrapper">
                            <input type="text"
                                   class="form-control combo-input"
                                   id="departure-outlet-input"
                                   placeholder="Pilih outlet asal..."
                                   value="{{ isset($validated['departure_outlet_data']) ? $validated['departure_outlet_data']->nama_outlet : '' }}"
                                   readonly
                                   required>
                            <input type="hidden" id="departure-outlet" name="departure_outlet" value="{{ isset($validated['departure_outlet']) ? $validated['departure_outlet'] : request('departure_outlet') }}">

                            <button type="button" class="combo-clear-btn" id="clear-departure-combo">
                                <i class="fas fa-times"></i>
                            </button>

                            <button type="button" class="combo-dropdown-toggle" id="toggle-departure-dropdown">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        <!-- Dropdown untuk outlet asal -->
                        <div class="combo-dropdown-results" id="departure-dropdown">
                            <div class="combo-search-input">
                                <input type="text"
                                       id="departure-search"
                                       placeholder="Cari outlet asal..."
                                       autocomplete="off">
                            </div>
                            <div class="combo-options" id="departure-options">
                                <!-- Options akan diisi oleh JavaScript -->
                            </div>
                        </div>

                        @if(isset($validated['departure_outlet_data']))
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 4px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $validated['departure_outlet_data']->alamat_lengkap }}
                            </small>
                        @endif
                    </div>

                    <!-- Outlet Tujuan dengan Combo Box -->
                    <div class="form-group combo-dropdown">
                        <label class="form-label">Outlet Tujuan</label>
                        <div class="combo-input-wrapper">
                            <input type="text"
                                   class="form-control combo-input"
                                   id="destination-outlet-input"
                                   placeholder="Pilih outlet tujuan..."
                                   value="{{ isset($validated['destination_outlet_data']) ? $validated['destination_outlet_data']->nama_outlet : '' }}"
                                   readonly
                                   required>
                            <input type="hidden" id="destination-outlet" name="destination_outlet" value="{{ isset($validated['destination_outlet']) ? $validated['destination_outlet'] : request('destination_outlet') }}">

                            <button type="button" class="combo-clear-btn" id="clear-destination-combo">
                                <i class="fas fa-times"></i>
                            </button>

                            <button type="button" class="combo-dropdown-toggle" id="toggle-destination-dropdown">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        <!-- Dropdown untuk outlet tujuan -->
                        <div class="combo-dropdown-results" id="destination-dropdown">
                            <div class="combo-search-input">
                                <input type="text"
                                       id="destination-search"
                                       placeholder="Cari outlet tujuan..."
                                       autocomplete="off">
                            </div>
                            <div class="combo-options" id="destination-options">
                                <!-- Options akan diisi oleh JavaScript -->
                            </div>
                        </div>

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
                        <!-- Outlet Asal -->
                        <div class="outlet-item">
                            <div class="outlet-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="outlet-details">
                                <h5>{{ $validated['departure_outlet_data']->nama_outlet ?? 'Outlet Asal' }}</h5>
                                <p>{{ $validated['departure_outlet_data']->alamat_lengkap ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="arrow-container">
                            <i class="fas fa-arrow-right"></i>
                        </div>

                        <!-- Outlet Tujuan -->
                        <div class="outlet-item">
                            <div class="outlet-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="outlet-details">
                                <h5>{{ $validated['destination_outlet_data']->nama_outlet ?? 'Outlet Tujuan' }}</h5>
                                <p>{{ $validated['destination_outlet_data']->alamat_lengkap ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Search Meta Info -->
                    <div class="search-meta">
                        <div class="meta-item">
                            <i class="far fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($validated['departure_date'])->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $validated['passenger_count'] }} Penumpang</span>
                        </div>
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
                                        <h2>Rincian Perjalanan</h2>
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

                                    <!-- Shuttle Details (Collapsed) - MENGGUNAKAN LAYOUT SIDE-BY-SIDE -->
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

<!-- COMBINED GALLERY AND SEAT LAYOUT (SIDE-BY-SIDE) -->
<div class="shuttle-media-combined">
    <!-- Left Column: Gallery -->
    <div class="shuttle-gallery-side">
        <h6><i class="fas fa-images"></i> Gallery Shuttle</h6>

        <div class="compact-carousel" id="shuttleCarousel-{{ $jadwal->id }}">
            <!-- Header -->
            <div class="carousel-compact-header">
                <div class="carousel-title">Tampak Depan</div>
                <div class="carousel-counter">1/4</div>
            </div>

            <!-- Slides Container -->
            <div class="carousel-compact-container">
                <div class="carousel-track" id="carouselTrack-{{ $jadwal->id }}">
                    @php
                        $images = [
                            ['gambar' => $shuttle->gambar_depan, 'caption' => 'Tampak Depan'],
                            ['gambar' => $shuttle->gambar_samping, 'caption' => 'Tampak Samping'],
                            ['gambar' => $shuttle->gambar_belakang, 'caption' => 'Tampak Belakang'],
                            ['gambar' => $shuttle->gambar_interior, 'caption' => 'Interior']
                        ];
                    @endphp

                    @foreach($images as $index => $image)
                    <div class="carousel-slide" data-index="{{ $index }}">
                        <div class="slide-img-container">
                            @if($image['gambar'])
                                <img src="{{ asset('images/shuttle/' . $image['gambar']) }}"
                                     alt="{{ $image['caption'] }}"
                                     class="slide-img"
                                     onerror="this.src='https://via.placeholder.com/400x225?text={{ urlencode($image['caption']) }}'">
                            @else
                                <img src="https://via.placeholder.com/400x225?text={{ urlencode($image['caption']) }}"
                                     alt="{{ $image['caption'] }}"
                                     class="slide-img">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <button class="carousel-btn prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Dots Navigation -->
            <div class="carousel-dots-nav">
                @for($i = 0; $i < 4; $i++)
                    <button class="carousel-dot {{ $i == 0 ? 'active' : '' }}">
                        {{ $images[$i]['caption'] ?? 'Slide ' . ($i+1) }}
                    </button>
                @endfor
            </div>
        </div>
    </div>

    <!-- Right Column: Seat Layout -->
    <div class="seat-layout-side">
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
    // Data outlet dari PHP
    const outletsData = @json($outletsGrouped);

    // Konversi data outlet ke format yang mudah dicari
    let allOutlets = [];
    let outletsByCity = {};

    Object.keys(outletsData).forEach(city => {
        outletsData[city].forEach(outlet => {
            const outletItem = {
                id: outlet.id,
                nama_outlet: outlet.nama_outlet,
                kota: city,
                alamat: outlet.alamat,
                alamat_lengkap: outlet.alamat_lengkap || outlet.alamat,
                searchText: `${outlet.nama_outlet} ${city} ${outlet.alamat}`.toLowerCase()
            };
            allOutlets.push(outletItem);

            if (!outletsByCity[city]) {
                outletsByCity[city] = [];
            }
            outletsByCity[city].push(outletItem);
        });
    });

    // Fungsi untuk membuat opsi dropdown
    function createDropdownOptions(filteredOutlets = null) {
        const outlets = filteredOutlets || allOutlets;
        let optionsHTML = '';
        let currentCity = null;

        if (outlets.length === 0) {
            return '<div class="combo-no-results">Tidak ada outlet yang ditemukan</div>';
        }

        outlets.forEach(outlet => {
            // Tambahkan header kota jika berbeda
            if (outlet.kota !== currentCity) {
                currentCity = outlet.kota;
                optionsHTML += `
                    <div class="combo-optgroup">
                        <div class="combo-optgroup-header">${outlet.kota}</div>
                `;
            }

            optionsHTML += `
                <div class="combo-option" data-id="${outlet.id}" data-name="${outlet.nama_outlet}" data-alamat="${outlet.alamat_lengkap}">
                    <div class="combo-option-main">${outlet.nama_outlet}</div>
                    <div class="combo-option-detail">${outlet.alamat_lengkap}</div>
                </div>
            `;

            // Tutup optgroup jika kota berikutnya berbeda
            const nextOutlet = outlets[outlets.indexOf(outlet) + 1];
            if (!nextOutlet || nextOutlet.kota !== currentCity) {
                optionsHTML += `</div>`;
            }
        });

        return optionsHTML;
    }

    // Setup untuk departure combo box
    const departureInput = document.getElementById('departure-outlet-input');
    const departureHidden = document.getElementById('departure-outlet');
    const departureDropdown = document.getElementById('departure-dropdown');
    const departureOptions = document.getElementById('departure-options');
    const departureSearch = document.getElementById('departure-search');
    const toggleDepartureBtn = document.getElementById('toggle-departure-dropdown');
    const clearDepartureBtn = document.getElementById('clear-departure-combo');

    // Setup untuk destination combo box
    const destinationInput = document.getElementById('destination-outlet-input');
    const destinationHidden = document.getElementById('destination-outlet');
    const destinationDropdown = document.getElementById('destination-dropdown');
    const destinationOptions = document.getElementById('destination-options');
    const destinationSearch = document.getElementById('destination-search');
    const toggleDestinationBtn = document.getElementById('toggle-destination-dropdown');
    const clearDestinationBtn = document.getElementById('clear-destination-combo');

    // Inisialisasi dropdown dengan semua opsi
    departureOptions.innerHTML = createDropdownOptions();
    destinationOptions.innerHTML = createDropdownOptions();

    // Fungsi untuk menampilkan/sembunyikan dropdown
    function toggleDropdown(dropdown, input) {
        const isVisible = dropdown.classList.contains('show');

        // Tutup semua dropdown yang terbuka
        document.querySelectorAll('.combo-dropdown-results.show').forEach(d => {
            d.classList.remove('show');
        });

        if (!isVisible) {
            dropdown.classList.add('show');
            input.focus();

            // Scroll ke opsi yang dipilih jika ada
            const selectedOption = dropdown.querySelector('.combo-option.selected');
            if (selectedOption) {
                selectedOption.scrollIntoView({ block: 'nearest' });
            }
        }
    }

    // Fungsi untuk memilih opsi
    function selectOption(option, inputElement, hiddenInput, dropdown) {
        const outletId = option.dataset.id;
        const outletName = option.dataset.name;
        const outletAlamat = option.dataset.alamat;

        // Update input value
        inputElement.value = outletName;

        // Update hidden input
        hiddenInput.value = outletId;

        // Update clear button visibility
        const clearBtn = inputElement.parentNode.querySelector('.combo-clear-btn');
        if (clearBtn) {
            clearBtn.style.display = 'block';
        }

        // Update toggle button icon
        const toggleBtn = inputElement.parentNode.querySelector('.combo-dropdown-toggle');
        if (toggleBtn) {
            toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
        }

        // Hilangkan class selected dari semua opsi
        dropdown.querySelectorAll('.combo-option.selected').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Tambahkan class selected ke opsi yang dipilih
        option.classList.add('selected');

        // Tutup dropdown
        dropdown.classList.remove('show');

        // Tampilkan info alamat jika ada
        const formGroup = inputElement.closest('.form-group');
        let infoElement = formGroup.querySelector('.outlet-info-text');

        if (!infoElement) {
            infoElement = document.createElement('small');
            infoElement.className = 'text-muted outlet-info-text';
            infoElement.style.fontSize = '11px';
            infoElement.style.display = 'block';
            infoElement.style.marginTop = '4px';
            formGroup.appendChild(infoElement);
        }

        infoElement.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${outletAlamat}`;
    }

    // Fungsi untuk mencari outlet
    function searchOutlets(query, currentId = null) {
        const searchTerm = query.toLowerCase().trim();

        if (!searchTerm) {
            return allOutlets;
        }

        const results = [];
        let exactMatches = [];
        let partialMatches = [];

        allOutlets.forEach(outlet => {
            if (currentId && outlet.id == currentId) return;

            const outletText = outlet.searchText;
            const outletName = outlet.nama_outlet.toLowerCase();

            // Cek apakah query cocok dengan nama outlet
            if (outletName.includes(searchTerm)) {
                exactMatches.push(outlet);
            }
            // Cek apakah query cocok dengan kota
            else if (outlet.kota.toLowerCase().includes(searchTerm)) {
                partialMatches.push(outlet);
            }
            // Cek apakah query cocok dengan alamat
            else if (outlet.alamat.toLowerCase().includes(searchTerm)) {
                partialMatches.push(outlet);
            }
            // Cek apakah query cocok dengan teks pencarian lengkap
            else if (outletText.includes(searchTerm)) {
                partialMatches.push(outlet);
            }
        });

        return [...exactMatches, ...partialMatches];
    }

    // Fungsi untuk mengupdate opsi dropdown berdasarkan pencarian
    function updateDropdownOptions(searchInput, optionsContainer, currentId) {
        const query = searchInput.value;
        const results = searchOutlets(query, currentId);
        optionsContainer.innerHTML = createDropdownOptions(results);

        // Attach event listeners ke opsi baru
        attachOptionListeners(optionsContainer);
    }

    // Fungsi untuk attach event listeners ke opsi
    function attachOptionListeners(optionsContainer) {
        optionsContainer.querySelectorAll('.combo-option').forEach(option => {
            option.addEventListener('click', function() {
                const comboBox = optionsContainer.closest('.combo-dropdown');
                const input = comboBox.querySelector('.combo-input');
                const hiddenInput = comboBox.querySelector('input[type="hidden"]');

                selectOption(this, input, hiddenInput, optionsContainer.closest('.combo-dropdown-results'));
            });
        });
    }

    // Initial attach listeners
    attachOptionListeners(departureOptions);
    attachOptionListeners(destinationOptions);

    // Event listeners untuk departure combo box
    if (toggleDepartureBtn) {
        toggleDepartureBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(departureDropdown, departureSearch);
        });
    }

    if (departureInput) {
        departureInput.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(departureDropdown, departureSearch);
        });
    }

    if (departureSearch) {
        departureSearch.addEventListener('input', function() {
            updateDropdownOptions(departureSearch, departureOptions, departureHidden.value);
        });

        departureSearch.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    if (clearDepartureBtn) {
        clearDepartureBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            departureInput.value = '';
            departureHidden.value = '';
            departureDropdown.classList.remove('show');

            // Hapus info alamat
            const infoElement = departureInput.closest('.form-group').querySelector('.outlet-info-text');
            if (infoElement) {
                infoElement.remove();
            }

            // Reset dropdown options
            departureOptions.innerHTML = createDropdownOptions();
            attachOptionListeners(departureOptions);

            // Hide clear button
            this.style.display = 'none';
        });
    }

    // Event listeners untuk destination combo box
    if (toggleDestinationBtn) {
        toggleDestinationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(destinationDropdown, destinationSearch);
        });
    }

    if (destinationInput) {
        destinationInput.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(destinationDropdown, destinationSearch);
        });
    }

    if (destinationSearch) {
        destinationSearch.addEventListener('input', function() {
            updateDropdownOptions(destinationSearch, destinationOptions, destinationHidden.value);
        });

        destinationSearch.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    if (clearDestinationBtn) {
        clearDestinationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            destinationInput.value = '';
            destinationHidden.value = '';
            destinationDropdown.classList.remove('show');

            // Hapus info alamat
            const infoElement = destinationInput.closest('.form-group').querySelector('.outlet-info-text');
            if (infoElement) {
                infoElement.remove();
            }

            // Reset dropdown options
            destinationOptions.innerHTML = createDropdownOptions();
            attachOptionListeners(destinationOptions);

            // Hide clear button
            this.style.display = 'none';
        });
    }

    // Close dropdown ketika klik di luar
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.combo-dropdown')) {
            document.querySelectorAll('.combo-dropdown-results').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Handle ESC key to close dropdown
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.combo-dropdown-results').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Set initial values jika ada data sebelumnya
    const initialDepartureId = departureHidden.value;
    if (initialDepartureId) {
        const outlet = allOutlets.find(o => o.id == initialDepartureId);
        if (outlet) {
            departureInput.value = outlet.nama_outlet;
            clearDepartureBtn.style.display = 'block';

            // Mark as selected in dropdown
            setTimeout(() => {
                const option = departureOptions.querySelector(`[data-id="${initialDepartureId}"]`);
                if (option) {
                    option.classList.add('selected');
                }
            }, 100);
        }
    }

    const initialDestinationId = destinationHidden.value;
    if (initialDestinationId) {
        const outlet = allOutlets.find(o => o.id == initialDestinationId);
        if (outlet) {
            destinationInput.value = outlet.nama_outlet;
            clearDestinationBtn.style.display = 'block';

            // Mark as selected in dropdown
            setTimeout(() => {
                const option = destinationOptions.querySelector(`[data-id="${initialDestinationId}"]`);
                if (option) {
                    option.classList.add('selected');
                }
            }, 100);
        }
    }

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
            // Inisialisasi carousel jika belum
            setTimeout(() => {
                initCarousel(jadwalId);
            }, 50);
        }
    }

    // Form submission
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const departureOutlet = departureHidden.value;
            const destinationOutlet = destinationHidden.value;

            if (departureOutlet && destinationOutlet && departureOutlet === destinationOutlet) {
                alert('Outlet keberangkatan dan tujuan tidak boleh sama!');
                return;
            }

            if (!departureOutlet) {
                alert('Silakan pilih outlet asal!');
                departureInput.focus();
                toggleDropdown(departureDropdown, departureSearch);
                return;
            }

            if (!destinationOutlet) {
                alert('Silakan pilih outlet tujuan!');
                destinationInput.focus();
                toggleDropdown(destinationDropdown, destinationSearch);
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

    // Force uniform height for form controls
    function enforceUniformHeight() {
        const formControls = document.querySelectorAll('.form-control, .form-control-select, input[type="date"], input[type="number"], .combo-input');
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

    /// Shuttle Gallery Carousel Functions
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi semua carousel saat halaman dimuat
    initializeAllCarousels();

    // Pastikan tombol "Info Shuttle" tetap berfungsi
    initShuttleInfoButtons();
});

function initializeAllCarousels() {
    document.querySelectorAll('.compact-carousel').forEach(container => {
        const jadwalId = container.id.replace('shuttleCarousel-', '');
        initCarousel(jadwalId);
    });
}

function initCarousel(jadwalId) {
    const container = document.getElementById(`shuttleCarousel-${jadwalId}`);
    if (!container) return;

    const track = container.querySelector('.carousel-track');
    const dots = container.querySelectorAll('.carousel-dot');
    const title = container.querySelector('.carousel-title');
    const counter = container.querySelector('.carousel-counter');
    const prevBtn = container.querySelector('.prev-btn');
    const nextBtn = container.querySelector('.next-btn');

    if (!track || dots.length === 0) return;

    // Inisialisasi state
    let currentIndex = 0;
    const totalSlides = 4; // Selalu 4 slide (depan, samping, belakang, interior)

    function updateCarousel() {
        // Update track position
        const translateX = -currentIndex * 25; // 25% per slide
        track.style.transform = `translateX(${translateX}%)`;

        // Update active dot
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });

        // Update title (ambil dari dot yang aktif)
        if (title && dots[currentIndex]) {
            title.textContent = dots[currentIndex].textContent.trim();
        }

        // Update counter
        if (counter) {
            counter.textContent = `${currentIndex + 1}/${totalSlides}`;
        }
    }

    // Event listeners untuk tombol navigasi
    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
        });
    }

    // Event listeners untuk dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentIndex = index;
            updateCarousel();
        });
    });

    // Inisialisasi awal
    updateCarousel();

    // Simpan reference ke carousel
    window[`carousel_${jadwalId}`] = {
        next: function() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
        },
        prev: function() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
        },
        goTo: function(index) {
            if (index >= 0 && index < totalSlides) {
                currentIndex = index;
                updateCarousel();
            }
        }
    };
}

function initShuttleInfoButtons() {
    // Pastikan tombol "Info Shuttle" berfungsi
    document.querySelectorAll('.btn-filter[onclick*="toggleShuttleInfo"]').forEach(button => {
        const match = button.getAttribute('onclick').match(/toggleShuttleInfo\((\d+)\)/);
        if (match) {
            const jadwalId = match[1];

            // Tambah event listener baru
            button.addEventListener('click', function(e) {
                e.preventDefault();
                toggleShuttleInfo(jadwalId);
            });

            // Hapus onclick lama agar tidak double
            button.removeAttribute('onclick');
        }
    });

    // Juga untuk tombol route details
    document.querySelectorAll('.btn-route[onclick*="toggleRouteDetails"]').forEach(button => {
        const match = button.getAttribute('onclick').match(/toggleRouteDetails\((\d+)\)/);
        if (match) {
            const jadwalId = match[1];

            button.addEventListener('click', function(e) {
                e.preventDefault();
                toggleRouteDetails(jadwalId);
            });

            button.removeAttribute('onclick');
        }
    });
}

// Fungsi toggle shuttle info (original)
window.toggleShuttleInfo = function(jadwalId) {
    const details = document.getElementById(`shuttle-details-${jadwalId}`);
    const routeDetails = document.getElementById(`route-details-${jadwalId}`);

    // Tutup route details jika terbuka
    if (routeDetails && routeDetails.style.display === 'block') {
        routeDetails.style.display = 'none';
    }

    if (details.style.display === 'block') {
        details.style.display = 'none';
    } else {
        details.style.display = 'block';
        // Inisialisasi carousel jika belum
        setTimeout(() => {
            initCarousel(jadwalId);
        }, 50);
    }
};

// Fungsi toggle route details (original)
window.toggleRouteDetails = function(jadwalId) {
    const details = document.getElementById(`route-details-${jadwalId}`);
    const shuttleDetails = document.getElementById(`shuttle-details-${jadwalId}`);

    // Tutup shuttle details jika terbuka
    if (shuttleDetails && shuttleDetails.style.display === 'block') {
        shuttleDetails.style.display = 'none';
    }

    if (details.style.display === 'block') {
        details.style.display = 'none';
    } else {
        details.style.display = 'block';
    }
};
</script>
@endpush
