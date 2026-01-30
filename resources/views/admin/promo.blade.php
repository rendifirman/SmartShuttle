@extends('layouts.app-admin')

@section('title', 'Data Promo')

@push('styles')
<style>
/* ================= BASE ================= */
body {
    background: #f4f6fb;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
}

.page-container {
    padding: 25px;
    min-height: 100vh;
}

/* ================= HEADER ================= */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    color: #0b2a4a;
    font-size: 22px;
    margin: 0;
    font-weight: 600;
}

.btn-add {
    background: #1e88e5;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-add:hover {
    background: #0d74d1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 136, 229, 0.2);
}

/* ================= SUMMARY ================= */
.summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.summary-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
}

.summary-card h3 {
    margin: 0;
    font-size: 24px;
    color: #0b2a4a;
}

.summary-card p {
    margin: 5px 0 0;
    color: #777;
    font-size: 13px;
}

/* ================= FILTER ================= */
.filter-box {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    margin-bottom: 25px;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.filter-box select,
.filter-box input {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    width: 100%;
    transition: border-color 0.3s;
}

.filter-box select:focus,
.filter-box input:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
}

.filter-action {
    display: flex;
    gap: 15px;
    align-items: center;
}

.filter-action input {
    flex: 1;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}

.filter-action input:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
}

.btn-filter {
    background: #ff6a00;
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    background: #e55c00;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
}

/* ================= TABLE ================= */
.table-wrapper {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
    margin-bottom: 20px;
    /* Hapus overflow-x: auto dari sini */
}

.table-content-wrapper {
    overflow-x: auto;
    margin-bottom: 20px;
}

.table-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.btn-excel {
    background: #12b600;
    color: #fff;
    border-radius: 20px;
    padding: 10px 20px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-excel:hover {
    background: #0fa000;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(18, 182, 0, 0.2);
}

.btn-pdf {
    background: #ddd;
    color: #333;
    border-radius: 20px;
    padding: 10px 20px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-pdf:hover {
    background: #ccc;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1000px;
}

thead {
    background: #f1f1f1;
}

th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #ddd;
    font-size: 13px;
    white-space: nowrap;
}

td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    vertical-align: middle;
}

tbody tr {
    transition: background-color 0.2s ease;
}

tbody tr:hover {
    background-color: #f9f9f9;
}

/* Status Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    line-height: 1.2;
}

.status-aktif {
    background: #b8f0a3;
    color: #1e7e34;
    border: 1px solid #a3e891;
}

.status-nonaktif {
    background: #ff9a9a;
    color: #8b0000;
    border: 1px solid #ff8080;
}

.status-expired {
    background: #ffd8a3;
    color: #8b5700;
    border: 1px solid #ffc880;
}

/* Kategori Badges */
.kategori-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    line-height: 1.2;
}

.kategori-umum {
    background: #a3d8ff;
    color: #0056b3;
    border: 1px solid #80c6ff;
}

.kategori-keluarga {
    background: #ffa3d1;
    color: #8b0053;
    border: 1px solid #ff80c0;
}

.kategori-membership {
    background: #d8a3ff;
    color: #5e008b;
    border: 1px solid #c980ff;
}

/* Tipe Badges */
.tipe-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    line-height: 1.2;
}

.tipe-all {
    background: #a3ffb8;
    color: #008b1e;
    border: 1px solid #80ff9e;
}

.tipe-shuttle {
    background: #a3e0ff;
    color: #00608b;
    border: 1px solid #80d0ff;
}

.tipe-paket {
    background: #ffd8a3;
    color: #8b5e00;
    border: 1px solid #ffc880;
}

.tipe-sewa {
    background: #ffa3a3;
    color: #8b0000;
    border: 1px solid #ff8080;
}

/* Discount Display */
.discount-value {
    font-weight: 600;
    color: #0b2a4a;
    display: block;
    font-size: 14px;
}

.discount-type {
    font-size: 11px;
    color: #777;
    display: block;
    margin-top: 2px;
}

/* ================= ACTION BUTTONS IMPROVED ================= */
.action-container {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.btn-action {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 80px;
    text-decoration: none;
    text-align: center;
}

.btn-action i {
    font-size: 12px;
}

/* View Button */
.btn-view {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: #fff;
    border: 1px solid #5a6268;
}

.btn-view:hover {
    background: linear-gradient(135deg, #5a6268, #495057);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Edit Button */
.btn-edit {
    background: linear-gradient(135deg, #f9b000, #e09b00);
    color: #fff;
    border: 1px solid #e09b00;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #e09b00, #c78900);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(249, 176, 0, 0.3);
}

/* Delete Button */
.btn-delete {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: #fff;
    border: 1px solid #c82333;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c82333, #b21f2d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Action Button Group - Desktop */
.action-group {
    display: flex;
    gap: 6px;
}

/* Mobile Action Menu */
.mobile-action-menu {
    display: none;
    position: relative;
}

.mobile-action-toggle {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}

.mobile-action-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    min-width: 150px;
    z-index: 100;
    overflow: hidden;
}

.mobile-action-dropdown.show {
    display: block;
}

.mobile-action-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.mobile-action-item:last-child {
    border-bottom: none;
}

.mobile-action-item:hover {
    background-color: #f8f9fa;
}

.mobile-action-item i {
    width: 16px;
    text-align: center;
}

/* Member Only Indicator */
.member-indicator {
    display: inline-block;
    padding: 2px 8px;
    background: #fff3cd;
    color: #856404;
    border-radius: 10px;
    font-size: 11px;
    margin-top: 4px;
    border: 1px solid #ffeaa7;
}

/* ================= PAGINATION - FIXED POSITION ================= */
.pagination-wrapper {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    width: 100%;
    overflow: visible !important;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    width: 100%;
    position: relative;
    z-index: 1;
}

.pagination-info {
    font-size: 13px;
    color: #666;
    flex-shrink: 0;
}

.pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
    flex-grow: 1;
    min-width: 0;
    overflow: visible !important;
}

.pagination-btn {
    padding: 8px 14px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
    min-width: 40px;
    text-align: center;
    text-decoration: none;
    color: #333;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.pagination-btn:hover:not(.active):not(:disabled) {
    background: #f8f9fa;
    border-color: #adb5bd;
    text-decoration: none;
}

.pagination-btn.active {
    background: #0b2a4a;
    color: white;
    border-color: #0b2a4a;
    cursor: default;
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn i {
    font-size: 12px;
}

/* ================= FORM CARD ================= */
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 25px;
}

.form-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 12px;
    font-size: 20px;
    color: #0b2a4a;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
}

.form-group .checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: normal;
    cursor: pointer;
}

.form-group .checkbox-label input[type="checkbox"] {
    width: auto;
    transform: scale(1.2);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

/* ================= FORM ACTIONS ================= */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-save {
    background: #0b2a4a;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-save:hover {
    background: #1a3a5f;
    transform: translateY(-1px);
}

.btn-reset {
    background: #ff6a00;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-reset:hover {
    background: #e55c00;
    transform: translateY(-1px);
}

.btn-cancel {
    background: #6c757d;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* ================= DETAIL CARD ================= */
.detail-container {
    display: grid;
    gap: 20px;
    max-width: 1200px;
}

.detail-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}

.detail-title {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 15px;
    border-bottom: 2px solid #ff6a00;
    padding-bottom: 8px;
    color: #0b2a4a;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.detail-item label {
    font-size: 12px;
    color: #777;
    display: block;
    margin-bottom: 5px;
}

.detail-item span {
    font-weight: 600;
    font-size: 13px;
    color: #333;
}

.detail-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

/* Image Preview */
.image-preview {
    max-width: 200px;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px solid #ddd;
}

/* ================= UTILITIES ================= */
.hidden {
    display: none !important;
}

.btn-back {
    background: #6c757d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-bottom: 20px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* ================= ALERTS ================= */
.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ================= MODAL ================= */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(2px);
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    justify-content: flex-end;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
    .summary {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .summary {
        grid-template-columns: 1fr;
    }

    .filter-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-save,
    .btn-reset,
    .btn-cancel {
        width: 100%;
        text-align: center;
    }

    .detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .detail-grid-2 {
        grid-template-columns: 1fr;
    }

    .table-actions {
        flex-wrap: wrap;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    /* Mobile Action Menu */
    .action-group {
        display: none;
    }
    
    .mobile-action-menu {
        display: block;
    }
    
    .btn-action {
        width: 100%;
        justify-content: flex-start;
        padding: 10px 15px;
    }
    
    /* Pagination Mobile */
    .pagination-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .pagination-info {
        margin-bottom: 10px;
    }
}

@media (max-width: 576px) {
    .filter-row {
        grid-template-columns: 1fr;
    }

    .filter-action {
        flex-direction: column;
    }

    .filter-action input {
        width: 100%;
    }

    .btn-filter {
        width: 100%;
    }

    .page-container {
        padding: 15px;
    }

    .btn-action {
        padding: 10px 15px;
        font-size: 13px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .action-container {
        justify-content: flex-start;
    }
    
    /* Pagination Small Mobile */
    .pagination {
        gap: 5px;
    }
    
    .pagination-btn {
        padding: 6px 10px;
        min-width: 35px;
        font-size: 12px;
    }
}

/* Tablet Landscape */
@media (min-width: 769px) and (max-width: 1024px) {
    .action-group {
        flex-direction: column;
        gap: 4px;
    }
    
    .btn-action {
        min-width: 70px;
        padding: 6px 10px;
        font-size: 12px;
    }
}

/* Custom Scrollbar for Table */
.table-content-wrapper::-webkit-scrollbar {
    height: 8px;
}

.table-content-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-content-wrapper::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-content-wrapper::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endpush

@section('content')
<div class="page-container">

    <!-- ================= LIST PAGE ================= -->
    <div id="list-page">
        <!-- HEADER -->
        <div class="page-header">
            <h2>Data Promo</h2>
            <a href="{{ route('admin.promo.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Promo
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-card promo-total">
                <h3>{{ $totalPromo }}</h3>
                <p>Total Promo</p>
            </div>
            <div class="summary-card promo-aktif">
                <h3>{{ $activePromo }}</h3>
                <p>Promo Aktif</p>
            </div>
            <div class="summary-card promo-berjalan">
                <h3>{{ $ongoingPromo }}</h3>
                <p>Sedang Berjalan</p>
            </div>
            <div class="summary-card promo-expired">
                <h3>{{ $expiredPromo }}</h3>
                <p>Promo Expired</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <form method="GET" action="{{ route('admin.promo') }}" id="filterForm">
                <div class="filter-row">
                    <select name="kategori_promo" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        <option value="umum" {{ request('kategori_promo') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="keluarga" {{ request('kategori_promo') == 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                        <option value="membership" {{ request('kategori_promo') == 'membership' ? 'selected' : '' }}>Membership</option>
                    </select>
                    
                    <select name="tipe_promo" id="filter-tipe">
                        <option value="">Semua Tipe</option>
                        <option value="all" {{ request('tipe_promo') == 'all' ? 'selected' : '' }}>All (Semua)</option>
                        <option value="shuttle" {{ request('tipe_promo') == 'shuttle' ? 'selected' : '' }}>Shuttle</option>
                        <option value="paket" {{ request('tipe_promo') == 'paket' ? 'selected' : '' }}>Paket</option>
                        <option value="sewa" {{ request('tipe_promo') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                    </select>
                    
                    <select name="jenis_diskon" id="filter-diskon">
                        <option value="">Jenis Diskon</option>
                        <option value="persentase" {{ request('jenis_diskon') == 'persentase' ? 'selected' : '' }}>Persentase</option>
                        <option value="nominal" {{ request('jenis_diskon') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                    </select>
                    
                    <select name="status" id="filter-status">
                        <option value="">Status Promo</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <div class="filter-action">
                    <input type="text" name="search" id="search-promo" placeholder="Cari Kode, Nama Promo" value="{{ request('search') }}">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.promo') }}" class="btn-cancel" style="padding: 12px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-actions">
                <button class="btn-excel" onclick="exportExcel()">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportPDF()">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>

            <!-- Scrollable Table Container -->
            <div class="table-content-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Promo</th>
                            <th>Nama Promo</th>
                            <th>Diskon</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="promo-table-body">
                        @forelse($promos as $promo)
                        <tr>
                            <td>
                                <strong>{{ $promo->kode_promo }}</strong>
                                @if($promo->khusus_member)
                                    <div class="member-indicator">Member Only</div>
                                @endif
                            </td>
                            <td>{{ $promo->nama_promo }}</td>
                            <td>
                                <span class="discount-value">
                                    @if($promo->jenis_diskon == 'persentase')
                                        {{ number_format($promo->nilai_diskon, 0) }}%
                                    @else
                                        Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                    @endif
                                </span>
                                <small class="discount-type">
                                    @if($promo->jenis_diskon == 'persentase' && $promo->maksimal_diskon)
                                        Maks: Rp {{ number_format($promo->maksimal_diskon, 0, ',', '.') }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="kategori-badge kategori-{{ $promo->kategori_promo }}">
                                    {{ ucfirst($promo->kategori_promo) }}
                                </span>
                            </td>
                            <td>
                                <span class="tipe-badge tipe-{{ $promo->tipe_promo }}">
                                    {{ ucfirst($promo->tipe_promo) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($promo->tanggal_berakhir)->format('d/m/Y') }}</td>
                            <td>
                                @if($promo->kuota)
                                    {{ $promo->terpakai }} / {{ $promo->kuota }}
                                @else
                                    Unlimited
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = 'status-nonaktif';
                                    $statusText = 'Nonaktif';
                                    
                                    if($promo->status) {
                                        $now = now();
                                        $startDate = \Carbon\Carbon::parse($promo->tanggal_mulai);
                                        $endDate = \Carbon\Carbon::parse($promo->tanggal_berakhir);
                                        
                                        if($now->between($startDate, $endDate)) {
                                            $statusClass = 'status-aktif';
                                            $statusText = 'Aktif';
                                        } else if($now->gt($endDate)) {
                                            $statusClass = 'status-expired';
                                            $statusText = 'Expired';
                                        }
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                <!-- Desktop Action Buttons -->
                                <div class="action-container">
                                    <div class="action-group">
                                        <a href="{{ route('admin.promo.show', $promo->id) }}" class="btn-action btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $promo->id }}', '{{ $promo->kode_promo }}')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    
                                    <!-- Mobile Action Menu -->
                                    <div class="mobile-action-menu">
                                        <button class="mobile-action-toggle" onclick="toggleMobileMenu(this)">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <span>Aksi</span>
                                        </button>
                                        <div class="mobile-action-dropdown">
                                            <a href="{{ route('admin.promo.show', $promo->id) }}" class="mobile-action-item">
                                                <i class="fas fa-eye"></i>
                                                <span>Detail</span>
                                            </a>
                                            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="mobile-action-item">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button" class="mobile-action-item" onclick="confirmDelete('{{ $promo->id }}', '{{ $promo->kode_promo }}')" style="background: none; border: none; width: 100%; text-align: left; font: inherit; cursor: pointer;">
                                                <i class="fas fa-trash" style="color: #dc3545;"></i>
                                                <span style="color: #dc3545;">Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Form (hidden) -->
                                    <form id="delete-form-{{ $promo->id }}" action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 30px;">
                                <p style="color: #666;">Tidak ada data promo ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION - DI LUAR SCROLLABLE CONTAINER -->
            <div class="pagination-wrapper">
                <div class="pagination-container">
                    <div class="pagination-info">
                        Menampilkan {{ $promos->firstItem() ?? 0 }} - {{ $promos->lastItem() ?? 0 }} dari {{ $promos->total() }} data
                    </div>
                    
                    <!-- Custom Pagination Links -->
                    @if($promos->hasPages())
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if($promos->onFirstPage())
                            <span class="pagination-btn disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $promos->previousPageUrl() }}" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $current = $promos->currentPage();
                            $last = $promos->lastPage();
                            $start = max($current - 2, 1);
                            $end = min($current + 2, $last);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $promos->url(1) }}" class="pagination-btn">1</a>
                            @if($start > 2)
                                <span class="pagination-btn disabled">...</span>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $current)
                                <span class="pagination-btn active">{{ $i }}</span>
                            @else
                                <a href="{{ $promos->url($i) }}" class="pagination-btn">{{ $i }}</a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="pagination-btn disabled">...</span>
                            @endif
                            <a href="{{ $promos->url($last) }}" class="pagination-btn">{{ $last }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if($promos->hasMorePages())
                            <a href="{{ $promos->nextPageUrl() }}" class="pagination-btn">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-btn disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="modal hidden">
    <div class="modal-content">
        <h3 style="margin-top: 0; color: #0b2a4a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>
            Konfirmasi Hapus
        </h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus promo ini?</p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-cancel" style="padding: 10px 20px;">
                <i class="fas fa-times"></i> Batal
            </button>
            <button id="confirmDelete" class="btn-save" style="background: #dc3545; padding: 10px 20px;">
                <i class="fas fa-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
// Variables
let deleteUrl = '';
let deleteFormId = '';

// Function to toggle mobile menu
function toggleMobileMenu(button) {
    const dropdown = button.nextElementSibling;
    dropdown.classList.toggle('show');
    
    // Close other dropdowns
    document.querySelectorAll('.mobile-action-dropdown.show').forEach(item => {
        if (item !== dropdown) {
            item.classList.remove('show');
        }
    });
}

// Close mobile dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.mobile-action-menu')) {
        document.querySelectorAll('.mobile-action-dropdown.show').forEach(item => {
            item.classList.remove('show');
        });
    }
});

// Function to confirm delete
function confirmDelete(id, kodePromo) {
    deleteFormId = `delete-form-${id}`;
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus promo "${kodePromo}"?`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Function to close delete modal
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deleteFormId = '';
}

// Function to execute delete
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteFormId) {
        const deleteForm = document.getElementById(deleteFormId);
        if (deleteForm) {
            deleteForm.submit();
        }
    }
});

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
        closeDeleteModal();
    }
});

// Export functions
function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.open(`{{ route('admin.promo') }}?${params.toString()}`, '_blank');
}

function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.open(`{{ route('admin.promo') }}?${params.toString()}`, '_blank');
}

// Search with Enter
document.getElementById('search-promo')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('filterForm').submit();
    }
});

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Promo management page loaded');
});
</script>
@endsection
