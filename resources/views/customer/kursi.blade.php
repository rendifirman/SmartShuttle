{{-- resources/views/customer/kursi.blade.php --}}
@extends('layouts.app')

@section('title', 'Smart Shuttle - Pilih Kursi')

@push('styles')
{{-- SALIN SEMUA CSS DARI FILE KURSI.BLADE.PHP ASLI --}}
<style>
    /* Main Container */
    .tiket-page {
        min-height: 100vh;
        background: #F5F5F5;
    }

    .tiket-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        padding-bottom: 120px;
        padding-top:120px;
    }

    .tiket-layout {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    @media (min-width: 1024px) {
        .tiket-layout {
            flex-direction: row;
        }
    }

    /* Columns */
    .left-column, .right-column {
        flex: 1;
    }

    /* Cards */
    .detail-card, .info-card, .seat-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .seat-card {
        height: fit-content;
    }

    .card-content {
        padding: 24px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #00215E;
        margin-bottom: 16px;
    }

    /* GLOBAL ALERT STYLES - FIXED POSITION */
    .global-alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        width: 100%;
        animation: slideInRight 0.3s ease-out;
    }

    .global-alert {
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .global-alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
    }

    .global-alert:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    /* Alert Types */
    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #e8f5e9 100%);
        color: #155724;
        border-color: rgba(76, 175, 80, 0.3);
    }

    .alert-success::before {
        background: linear-gradient(to bottom, #28a745, #20c997);
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da 0%, #fde8e8 100%);
        color: #721c24;
        border-color: rgba(220, 53, 69, 0.3);
    }

    .alert-error::before {
        background: linear-gradient(to bottom, #dc3545, #e63946);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #fff9e6 100%);
        color: #856404;
        border-color: rgba(255, 193, 7, 0.3);
    }

    .alert-warning::before {
        background: linear-gradient(to bottom, #ffc107, #ffd166);
    }

    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #e8f4f8 100%);
        color: #0c5460;
        border-color: rgba(23, 162, 184, 0.3);
    }

    .alert-info::before {
        background: linear-gradient(to bottom, #17a2b8, #00b4d8);
    }

    /* Alert Content */
    .alert-content {
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 1;
    }

    .alert-icon {
        font-size: 22px;
        min-width: 24px;
        text-align: center;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .alert-success .alert-icon {
        color: #28a745;
    }

    .alert-error .alert-icon {
        color: #dc3545;
    }

    .alert-warning .alert-icon {
        color: #ffc107;
    }

    .alert-info .alert-icon {
        color: #17a2b8;
    }

    .alert-message {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .alert-message strong {
        font-size: 15px;
        font-weight: 700;
        line-height: 1.3;
    }

    .alert-message span {
        font-size: 13px;
        line-height: 1.4;
        opacity: 0.95;
    }

    .alert-close {
        background: rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: inherit;
        font-size: 12px;
    }

    .alert-close:hover {
        background: rgba(0, 0, 0, 0.2);
        transform: rotate(90deg);
    }

    /* Progress Bar */
    .alert-progress {
        position: absolute;
        bottom: 0;
        left: 6px;
        right: 0;
        height: 3px;
        background: rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .alert-progress::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        animation: progressBar 5s linear forwards;
    }

    .alert-success .alert-progress::after {
        background: linear-gradient(to right, #28a745, #20c997);
    }

    .alert-error .alert-progress::after {
        background: linear-gradient(to right, #dc3545, #e63946);
    }

    .alert-warning .alert-progress::after {
        background: linear-gradient(to right, #ffc107, #ffd166);
    }

    .alert-info .alert-progress::after {
        background: linear-gradient(to right, #17a2b8, #00b4d8);
    }

    /* Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes progressBar {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    .global-alert.fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    /* Responsive Design for Alerts */
    @media (max-width: 768px) {
        .global-alert-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }

        .global-alert {
            padding: 14px;
            border-radius: 10px;
        }

        .alert-content {
            gap: 12px;
        }

        .alert-icon {
            font-size: 20px;
        }

        .alert-message strong {
            font-size: 14px;
        }

        .alert-message span {
            font-size: 12px;
        }

        .alert-close {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .global-alert-container {
            top: 8px;
            right: 8px;
            left: 8px;
        }

        .global-alert {
            padding: 12px;
        }

        .alert-content {
            gap: 10px;
        }
    }

    /* Shuttle Info Summary */
    .shuttle-info-summary {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid #FF581E;
        margin-bottom: 20px;
    }

    .shuttle-name {
        font-size: 16px;
        font-weight: 600;
        color: #00215E;
        margin-bottom: 8px;
    }

    .shuttle-detail {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
    }

    /* Fasilitas Badges */
    .fasilitas-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }

    .badge-fasilitas {
        background: linear-gradient(135deg, #E8F4FD 0%, #D4E9FA 100%);
        color: #00215E;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #BFDCF7;
        box-shadow: 0 2px 4px rgba(0, 33, 94, 0.1);
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .badge-fasilitas:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 33, 94, 0.15);
    }

    .price-info {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .price {
        font-size: 18px;
        font-weight: 700;
        color: #FF581E;
    }

    .per-seat {
        font-size: 12px;
        color: #666;
    }

    /* Journey Info */
    .journey-info {
        margin-bottom: 24px;
    }

    .route-title {
        font-size: 18px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 8px;
    }

    .journey-detail {
        font-size: 14px;
        color: #00215E;
        margin-bottom: 4px;
    }

    /* Route Section */
    .route-section {
        margin-top: 16px;
    }

    .section-subtitle {
        font-size: 14px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 12px;
    }

    .route-timeline {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .route-point {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .point-marker {
        width: 12px;
        height: 12px;
        background: #FF581E;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .point-info {
        flex: 1;
    }

    .point-location {
        font-size: 14px;
        font-weight: 500;
        color: #00215E;
        margin-bottom: 2px;
    }

    .point-time {
        font-size: 12px;
        color: #666666;
    }

    .route-connector {
        margin-left: 6px;
        border-left: 2px dashed #d1d5db;
        height: 16px;
    }

    /* Route Details Toggle Button */
    .route-details-toggle {
        margin-top: 16px;
        display: flex;
        justify-content: center;
    }

    .btn-route-toggle {
        background: #00215E;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-route-toggle:hover {
        background: #0038A8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 33, 94, 0.2);
    }

    .btn-route-toggle i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .btn-route-toggle.active i {
        transform: rotate(180deg);
    }

    /* Route Details Dropdown */
    .route-details-dropdown {
        margin-top: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
    }

    .route-details-dropdown.show {
        max-height: 400px;
    }

    .route-details-content {
        padding: 20px;
        max-height: 350px;
        overflow-y: auto;
    }

    /* Custom Scrollbar untuk route details */
    .route-details-content::-webkit-scrollbar {
        width: 6px;
    }

    .route-details-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .route-details-content::-webkit-scrollbar-thumb {
        background: #FF581E;
        border-radius: 10px;
    }

    .route-details-content::-webkit-scrollbar-thumb:hover {
        background: #E54E1A;
    }

    .route-detail-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .route-detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .route-detail-title {
        font-size: 14px;
        font-weight: 600;
        color: #FF581E;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .route-detail-title i {
        font-size: 12px;
    }

    .route-stop {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-left: 8px;
    }

    .stop-marker {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .stop-marker.origin {
        background: #00215E;
    }

    .stop-marker.stop {
        background: #94a3b8;
    }

    .stop-marker.destination {
        background: #10b981;
    }

    .stop-info {
        flex: 1;
    }

    .stop-name {
        font-size: 13px;
        font-weight: 500;
        color: #00215E;
        margin-bottom: 2px;
    }

    .stop-time {
        font-size: 11px;
        color: #666;
        margin-bottom: 4px;
    }

    .stop-outlet {
        font-size: 10px;
        color: #FF581E;
        background: rgba(255, 88, 30, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    /* Seat Legend - SIMPLIFIED */
    .seat-legend {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .legend-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .seat-box {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seat-box.available {
        background: white;
        border: 2px solid #d1d5db;
    }

    .seat-box.sold {
        background: #D9D9D9;
        position: relative;
        overflow: hidden;
    }

    .seat-box.sold::before,
    .seat-box.sold::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80%;
        height: 3px;
        background: #ff4444;
    }

    .seat-box.sold::before {
        transform: translate(-50%, -50%) rotate(45deg);
    }

    .seat-box.sold::after {
        transform: translate(-50%, -50%) rotate(-45deg);
    }

    .seat-box.selected {
        background: #FF581E;
    }

    .legend-text {
        font-size: 14px;
        color: #00215E;
        font-weight: 500;
    }

    /* Info Notice */
    .info-notice {
        background: #fef3cd;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ffeaa7;
    }

    .notice-text {
        font-size: 12px;
        color: #00215E;
        line-height: 1.5;
    }

    /* Price Summary - ENHANCED DESIGN */
    .price-summary {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .price-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #FF581E, #00215E);
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .price-item:last-child {
        border-bottom: none;
    }

    .price-label {
        font-size: 14px;
        color: #00215E;
        font-weight: 500;
    }

    .price-value {
        font-size: 14px;
        font-weight: 600;
        color: #00215E;
    }

    .price-item.discount .price-value {
        color: #10b981;
        font-weight: 700;
    }

    .price-item.total {
        margin-top: 8px;
        padding-top: 16px;
        padding-bottom: 0;
        border-top: 2px solid rgba(255, 88, 30, 0.2);
        border-bottom: none;
    }

    .price-item.total .price-label {
        font-size: 16px;
        font-weight: 700;
        color: #00215E;
    }

    .price-item.total .price-value {
        font-size: 20px;
        font-weight: 800;
        color: #FF581E;
        text-shadow: 0 1px 2px rgba(255, 88, 30, 0.1);
    }

    .price-breakdown {
        font-size: 11px;
        color: #666;
        margin-left: 4px;
        font-weight: normal;
        opacity: 0.8;
    }

    /* Driver Section */
    .driver-section {
        margin-bottom: 24px;
        text-align: center;
    }

    .driver-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .driver-indicator i {
        color: #00215E;
        font-size: 14px;
    }

    .driver-text {
        font-size: 14px;
        font-weight: 500;
        color: #00215E;
    }

    /* Seat Grid - 9 KURSI (3x3) */
    .seat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    .seat {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        position: relative;
        flex-direction: column;
        overflow: hidden;
    }

    .seat.available {
        background: white;
        border: 2px solid #d1d5db;
        color: #00215E;
    }

    .seat.available:hover {
        background: #f8f9fa;
        border-color: #FF581E;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* PERBAIKAN: Styling untuk kursi terpesan agar tidak bisa diklik */
    .seat.sold {
        background: #D9D9D9 !important;
        color: #999 !important;
        cursor: not-allowed !important;
        border: 2px solid #c0c0c0 !important;
        opacity: 0.8;
        filter: grayscale(80%);
        position: relative;
        pointer-events: none;
        overflow: visible;
    }

    .seat.sold::before,
    .seat.sold::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80%;
        height: 3px;
        background: #ff4444;
        transform: translate(-50%, -50%);
        z-index: 10;
    }

    .seat.sold::before {
        transform: translate(-50%, -50%) rotate(45deg);
        z-index: 10;
    }

    .seat.sold::after {
        transform: translate(-50%, -50%) rotate(-45deg);
        z-index: 10;
    }

    .seat.sold .seat-number {
        opacity: 0.4;
        position: relative;
        z-index: 1;
    }

    .seat.sold .seat-status-icon {
        color: #ff4444 !important;
        opacity: 1 !important;
        z-index: 1;
    }

    .seat.sold .seat-premium-badge,
    .seat.sold .seat-price-extra {
        opacity: 0.3;
        z-index: 1;
    }

    /* Styling untuk kursi yang dipilih */
    .seat.selected {
        background: #FF581E;
        color: white;
        border: 2px solid #FF581E;
        box-shadow: 0 0 12px rgba(255, 88, 30, 0.4);
        animation: pulse-selected 2s infinite;
    }

    @keyframes pulse-selected {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 88, 30, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 88, 30, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 88, 30, 0);
        }
    }

    /* Selected Seats */
    .selected-seats {
        background: #dbeafe;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
        margin-bottom: 24px;
    }

    .selected-title {
        font-size: 14px;
        font-weight: 600;
        color: #00215E;
        margin-bottom: 8px;
    }

    .selected-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        min-height: 20px;
    }

    .seat-badge-item {
        background: #FF581E;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .seat-badge-item .remove-seat {
        font-size: 10px;
        opacity: 0.8;
        cursor: pointer;
    }

    .seat-badge-item .remove-seat:hover {
        opacity: 1;
    }

    .total-price {
        text-align: right;
        padding-top: 8px;
        border-top: 1px solid #bfdbfe;
    }

    .total-amount {
        font-size: 16px;
        font-weight: 700;
        color: #FF581E;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .payment-btn, .btn-secondary {
        flex: 1;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .payment-btn {
        background: #FF581E;
        color: white;
    }

    .payment-btn:hover:not(:disabled) {
        background: #E54E1A;
    }

    .payment-btn:disabled {
        background: #D9D9D9;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: white;
        color: #00215E;
        border: 2px solid #00215E;
    }

    .btn-secondary:hover {
        background: #00215E;
        color: white;
    }

    /* Additional styles for seat layout */
    .seat-status-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        font-size: 12px;
        opacity: 0.7;
    }

    .available-icon {
        color: #28a745;
    }

    .selected-icon {
        color: white;
    }

    .seat.sold .seat-status-icon {
        color: #ff4444;
    }

    .seat-number {
        font-weight: bold;
        font-size: 16px;
    }

    .seat-premium-badge {
        display: block;
        font-size: 9px;
        background: gold;
        color: #000;
        padding: 2px 4px;
        border-radius: 3px;
        margin-top: 2px;
    }

    .seat-price-extra {
        display: block;
        font-size: 10px;
        color: #ff581e;
        font-weight: bold;
    }

    .payment-btn.disabled {
        background: #D9D9D9;
        cursor: not-allowed;
    }

    /* Pesan Validasi */
    .validation-message {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 16px;
        display: none;
    }

    .validation-message.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .validation-message.danger {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .validation-message.success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .validation-message.info {
        background: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tiket-container {
            padding: 16px;
            padding-bottom: 100px;
            padding-top: 100px;
        }

        .card-content {
            padding: 16px;
        }

        .seat-legend {
            grid-template-columns: repeat(2, 1fr);
        }

        .seat-grid {
            max-width: 250px;
            grid-template-columns: repeat(3, 1fr);
        }

        .seat {
            width: 50px;
            height: 50px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .fasilitas-badges {
            gap: 4px;
        }

        .badge-fasilitas {
            font-size: 10px;
            padding: 4px 8px;
        }

        .route-details-content {
            padding: 16px;
            max-height: 300px;
        }
    }

    @media (max-width: 480px) {
        .tiket-container {
            padding: 12px;
            padding-bottom: 80px;
            padding-top: 100px;
        }

        .card-title {
            font-size: 18px;
        }

        .seat-legend {
            grid-template-columns: 1fr;
        }

        .seat-grid {
            max-width: 200px;
            gap: 12px;
        }

        .seat {
            width: 45px;
            height: 45px;
            font-size: 14px;
        }

        .seat.sold::before,
        .seat.sold::after {
            height: 2px;
        }

        .fasilitas-badges {
            grid-template-columns: repeat(2, 1fr);
            display: grid;
        }

        .badge-fasilitas {
            font-size: 9px;
            padding: 3px 6px;
            text-align: center;
        }

        .price-summary {
            padding: 16px;
        }

        .price-item.total .price-value {
            font-size: 18px;
        }
    }

    /* Responsive untuk layar sangat kecil */
    @media (max-width: 360px) {
        .seat-grid {
            max-width: 180px;
            gap: 10px;
        }

        .seat {
            width: 40px;
            height: 40px;
            font-size: 12px;
        }

        .seat-premium-badge {
            font-size: 8px;
            padding: 1px 3px;
        }

        .seat-price-extra {
            font-size: 8px;
        }
    }
</style>
@endpush

@section('content')
{{-- ALERT NOTIFICATION --}}
@if(session('alert-type'))
    <div class="global-alert-container">
        <div class="global-alert alert-{{ session('alert-type') }}">
            <div class="alert-content">
                @if(session('alert-icon'))
                    <i class="alert-icon {{ session('alert-icon') }}"></i>
                @else
                    <i class="alert-icon fas
                        @if(session('alert-type') == 'success') fa-check-circle
                        @elseif(session('alert-type') == 'error') fa-exclamation-circle
                        @elseif(session('alert-type') == 'warning') fa-exclamation-triangle
                        @elseif(session('alert-type') == 'info') fa-info-circle
                        @endif"></i>
                @endif
                <div class="alert-message">
                    <strong>{{ session('alert-title', '') }}</strong>
                    <span>{{ session('alert-message', '') }}</span>
                </div>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="alert-progress"></div>
        </div>
    </div>
@elseif(session('error'))
    <div class="global-alert-container">
        <div class="global-alert alert-error">
            <div class="alert-content">
                <i class="alert-icon fas fa-exclamation-circle"></i>
                <div class="alert-message">
                    <strong>Gagal Memilih Kursi</strong>
                    <span>{{ session('error') }}</span>
                </div>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="alert-progress"></div>
        </div>
    </div>
@elseif(session('success'))
    <div class="global-alert-container">
        <div class="global-alert alert-success">
            <div class="alert-content">
                <i class="alert-icon fas fa-check-circle"></i>
                <div class="alert-message">
                    <strong>Sukses</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="alert-progress"></div>
        </div>
    </div>
@elseif($errors->any())
    <div class="global-alert-container">
        <div class="global-alert alert-error">
            <div class="alert-content">
                <i class="alert-icon fas fa-exclamation-circle"></i>
                <div class="alert-message">
                    <strong>Validasi Gagal</strong>
                    <span>
                        @foreach($errors->all() as $error)
                            • {{ $error }}<br>
                        @endforeach
                    </span>
                </div>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="alert-progress"></div>
        </div>
    </div>
@endif

@php
    // ======================================================================
    //   INISIALISASI DATA HARGA – SINKRON DENGAN PESAN.BLADE.PHP
    // ======================================================================
    // Pastikan data pemesanan tersedia
    if (!isset($pemesanan) || !$pemesanan) {
        echo '<div class="alert alert-danger">Data pemesanan tidak ditemukan. <a href="'.route('customer.search').'">Kembali ke pencarian</a></div>';
        return;
    }

    $jadwal           = $pemesanan->jadwal;
    $jumlahPenumpang  = $pemesanan->jumlah_penumpang;

    // 1. Harga per tiket = harga_total dari jadwal (sama seperti di pesan.blade.php)
    $hargaPerOrang    = $jadwal->harga_total ?? 0;

    // 2. Total tarif tambahan (dikirim oleh controller, fallback 0)
    $totalTarif       = $totalTarif ?? 0;

    // 3. Subtotal = (harga per orang * jumlah penumpang) + total tarif
    $subtotal         = ($hargaPerOrang * $jumlahPenumpang) + $totalTarif;

    // 4. Diskon (dikirim controller, fallback dari pemesanan)
    $diskon           = $diskon ?? ($pemesanan->diskon ?? 0);

    // 5. Total bayar setelah diskon
    $totalBayar       = max(0, $subtotal - $diskon);

    // 6. Tarif per kursi untuk perhitungan proporsional di JavaScript
    $tarifPerKursi    = $jumlahPenumpang > 0 ? $totalTarif / $jumlahPenumpang : 0;
@endphp

<main class="tiket-container">
    <div class="tiket-layout">

        {{-- LEFT COLUMN – DETAIL PERJALANAN --}}
        <div class="left-column">
            <div class="detail-card">
                <div class="card-content">
                    <h2 class="card-title">DETAIL PERJALANAN</h2>

                    <div class="journey-info">
                        <h3 class="route-title" id="route-title">
                            {{ $jadwal->rute_pertama->kota_asal ?? 'Kota Asal' }} →
                            {{ $jadwal->rute_terakhir->kota_tujuan ?? 'Kota Tujuan' }}
                        </h3>
                        <p class="journey-detail" id="journey-date">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_keberangkatan)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                        <p class="journey-detail" id="journey-time">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }} WIB
                        </p>
                        <p class="journey-detail" id="passenger-count">
                            {{ $jumlahPenumpang }} Penumpang
                        </p>
                    </div>

                    {{-- Rute Perjalanan --}}
                    <div class="route-section">
                        <h4 class="section-subtitle">Rute perjalanan</h4>
                        <div class="route-timeline" id="route-timeline">
                            @if($jadwal && $jadwal->rutes)
                                @php $waktu = \Carbon\Carbon::parse($jadwal->waktu_keberangkatan); @endphp
                                @foreach($jadwal->rutes as $index => $rute)
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">{{ $rute->kota_asal }}</p>
                                            <p class="point-time">{{ $waktu->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="route-connector"></div>
                                    @php
                                        $durasiParts = explode(':', $rute->durasi);
                                        $durasiMenit = ((int)($durasiParts[0] ?? 0) * 60) + ((int)($durasiParts[1] ?? 0));
                                        $waktu->addMinutes($durasiMenit);
                                    @endphp
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">{{ $rute->kota_tujuan }}</p>
                                            <p class="point-time">{{ $waktu->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    @if($index < count($jadwal->rutes) - 1)
                                        <div class="route-connector"></div>
                                    @endif
                                @endforeach
                            @else
                                {{-- Fallback --}}
                                <div class="route-point">...</div>
                            @endif
                        </div>

                        <div class="route-details-toggle">
                            <button class="btn-route-toggle" onclick="toggleRouteDetails()">
                                <i class="fas fa-chevron-down"></i> Lihat Rute Detail
                            </button>
                        </div>

                        <div class="route-details-dropdown" id="route-details-dropdown">
                            <div class="route-details-content">
                                @if($jadwal && $jadwal->rutes && $jadwal->rutes->count() > 0)
                                    @foreach($jadwal->rutes as $index => $rute)
                                        <div class="route-detail-item">
                                            <div class="route-detail-title">
                                                <i class="fas fa-route"></i> {{ $rute->nama_rute ?? 'Rute ' . ($index + 1) }}
                                            </div>
                                            {{-- Origin --}}
                                            <div class="route-stop">
                                                <div class="stop-marker origin"></div>
                                                <div class="stop-info">
                                                    <div class="stop-name">{{ $rute->kota_asal }}</div>
                                                    <div class="stop-time">
                                                        Keberangkatan: {{ \Carbon\Carbon::parse($jadwal->waktu_keberangkatan)->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $stops = json_decode($rute->rute_pemberhentian, true) ?? [];
                                                $currentTime = \Carbon\Carbon::parse($jadwal->waktu_keberangkatan);
                                                $showLimit = 2;
                                            @endphp
                                            @foreach(array_slice($stops, 0, $showLimit) as $stopIndex => $stop)
                                                @php $currentTime->addMinutes(30); @endphp
                                                <div class="route-stop">
                                                    <div class="stop-marker stop"></div>
                                                    <div class="stop-info">
                                                        <div class="stop-name">{{ $stop['kota'] ?? 'Kota Singgah' }}</div>
                                                        <div class="stop-time">
                                                            {{ $currentTime->format('H:i') }} • Durasi: {{ $stop['durasi_singgah'] ?? 10 }} menit
                                                        </div>
                                                        @if(!empty($stop['outlets']))
                                                        <div class="stop-outlet">
                                                            Outlet: {{ is_array($stop['outlets']) ? implode(', ', array_slice($stop['outlets'], 0, 2)) : $stop['outlets'] }}
                                                            @if(count($stop['outlets']) > 2) +{{ count($stop['outlets']) - 2 }} lainnya @endif
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @php $currentTime->addMinutes($stop['durasi_singgah'] ?? 10); @endphp
                                            @endforeach
                                            @if(count($stops) > $showLimit)
                                                <div class="route-stop">
                                                    <div class="stop-marker stop"></div>
                                                    <div class="stop-info">
                                                        <div class="stop-name">+{{ count($stops) - $showLimit }} pemberhentian lainnya</div>
                                                    </div>
                                                </div>
                                            @endif
                                            @php $currentTime->addMinutes(30); @endphp
                                            <div class="route-stop">
                                                <div class="stop-marker destination"></div>
                                                <div class="stop-info">
                                                    <div class="stop-name">{{ $rute->kota_tujuan }}</div>
                                                    <div class="stop-time">
                                                        Kedatangan: {{ $currentTime->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p style="text-align: center; color: #666; padding: 20px;">
                                        Informasi rute detail tidak tersedia
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN – INFORMASI KURSI & DATA KURSI --}}
        <div class="right-column">

            {{-- INFORMASI KURSI + RINGKASAN HARGA --}}
            <div class="info-card" style="margin-bottom: 24px;">
                <div class="card-content">
                    <h2 class="card-title">INFORMASI KURSI</h2>

                    <div class="seat-legend">
                        <div class="legend-item">
                            <div class="seat-box available"></div>
                            <span class="legend-text">Tersedia</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-box sold"></div>
                            <span class="legend-text">Sudah Dipesan</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-box selected"></div>
                            <span class="legend-text">Dipilih Anda</span>
                        </div>
                    </div>

                    <div class="info-notice">
                        <p class="notice-text">
                            <i class="fas fa-info-circle"></i> <strong>Kursi berwarna abu-abu</strong> sudah dipesan oleh penumpang lain dan <strong>tidak dapat dipilih</strong>.
                        </p>
                        <p class="notice-text" style="margin-top: 8px;">
                            <i class="fas fa-exclamation-triangle"></i> Pastikan Anda memilih kursi sesuai jumlah penumpang ({{ $jumlahPenumpang }} kursi).
                        </p>
                        <p class="notice-text" style="margin-top: 8px;">
                            <i class="fas fa-clock"></i> Anda memiliki waktu <strong>5 menit</strong> untuk menyelesaikan pemilihan kursi sebelum kunci kursi dilepas.
                        </p>
                    </div>

<br></br>

            {{-- DATA KURSI --}}
            <div class="seat-card">
                <div class="card-content">
                    <h2 class="card-title">DATA KURSI</h2>

                    <div class="shuttle-info-summary">
                        @php
                            // Determine shuttle object based on flow
                            $shuttle_obj = $usesDriverJadwal ? $driverJadwal?->shuttle : $jadwal?->shuttle;
                            $shuttle_name = $shuttle_obj?->nama_shuttle ?? 'Smart Shuttle Standard 2';
                            $shuttle_fasilitas = $shuttle_obj?->fasilitas ?? null;
                        @endphp
                        <div class="shuttle-name" id="shuttle-name">{{ $shuttle_name }}</div>
                        <div class="shuttle-detail" id="shuttle-detail">
                            @if(!empty($shuttle_fasilitas))
                                @php $fasilitasArray = explode(',', $shuttle_fasilitas); @endphp
                                <div class="fasilitas-badges">
                                    @foreach($fasilitasArray as $fasilitas)
                                        <span class="badge-fasilitas">{{ trim($fasilitas) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="fasilitas-badges">
                                    <span class="badge-fasilitas">Share Shuttle</span>
                                    <span class="badge-fasilitas">AC</span>
                                    <span class="badge-fasilitas">WiFi</span>
                                    <span class="badge-fasilitas">Charger</span>
                                    <span class="badge-fasilitas">TV LED</span>
                                    <span class="badge-fasilitas">Snack Premium</span>
                                    <span class="badge-fasilitas">Mineral Water</span>
                                </div>
                            @endif
                        </div>
                        <div class="price-info">
                            <span class="price" id="shuttle-price">
                                Rp {{ number_format($hargaPerOrang, 0, ',', '.') }}
                            </span>
                            <span class="per-seat">/kursi</span>
                        </div>
                    </div>

                    <div class="driver-section">
                        <div class="driver-indicator">
                            <i class="fas fa-steering-wheel"></i>
                            <span class="driver-text">Kursi Pengemudi</span>
                        </div>
                    </div>

                    <form id="kursi-form" action="{{ route('customer.kursi.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
                        @if($pemesanan->id_jadwal_driver)
                            <input type="hidden" name="id_jadwal_driver" value="{{ $pemesanan->id_jadwal_driver }}">
                        @endif

                        {{-- GRID KURSI --}}
                        <div class="seat-grid" id="seat-grid">
                            @if(!empty($layoutKursi) && is_array($layoutKursi))
                                @foreach($layoutKursi as $kursi)
                                    @php
                                        $seatClass  = $kursi['class'] ?? 'available';
                                        $seatStatus = $kursi['status'] ?? 'tersedia';
                                        $seatIcon   = $kursi['icon'] ?? 'fa-check';
                                        $seatNumber = $kursi['nomor'] ?? '';
                                        $hargaKursi = $hargaPerOrang + ($kursi['harga_tambahan'] ?? 0);
                                        $isClickable = ($seatClass === 'available' || $seatClass === 'selected');
                                        $onclick = $isClickable ? "selectSeat(this, '{$seatNumber}')" : "";
                                        if ($seatClass === 'sold' && $seatStatus === 'terpesan') {
                                            $title = 'Sudah dipesan penumpang lain';
                                        } elseif ($seatClass === 'sold' && $seatStatus === 'dikunci') {
                                            $title = 'Sedang dipilih user lain';
                                        } elseif ($seatClass === 'selected') {
                                            $title = 'Dipilih oleh Anda';
                                        } else {
                                            $title = 'Tersedia';
                                        }
                                    @endphp
                                     <div class="seat {{ $seatClass }}"
                                         data-seat="{{ $seatNumber }}"
                                         data-harga="{{ $hargaKursi }}"
                                         data-status="{{ $seatStatus }}"
                                         data-nomor="{{ $seatNumber }}"
                                         @if($isClickable) onclick="selectSeat(this, '{{ $seatNumber }}')" @endif
                                         title="{{ $title }}"
                                         @if($seatClass === 'sold') aria-disabled="true" data-disabled="1" style="pointer-events:none; cursor:not-allowed;" @endif>
                                        <span class="seat-number">{{ $seatNumber }}</span>
                                        @if(isset($kursi['tipe']) && $kursi['tipe'] === 'premium')
                                            <small class="seat-premium-badge">PREMIUM</small>
                                        @endif
                                        @if(!empty($kursi['harga_tambahan']))
                                            <small class="seat-price-extra">
                                                +{{ number_format($kursi['harga_tambahan'],0,',','.') }}
                                            </small>
                                        @endif
                                        <i class="fas {{ $seatIcon }} seat-status-icon"></i>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div id="selected-seats-inputs"></div>

                        <div class="selected-seats">
                            <h4 class="selected-title">
                                Kursi yang Dipilih (<span id="selected-count">0</span>/<span id="max-seats">{{ $jumlahPenumpang }}</span>):
                            </h4>
                            <div class="selected-list" id="selected-list"></div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="payment-btn" id="payment-btn" disabled>
                                Lanjutkan ke Detail Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
// =====================================================
//   FORM SUBMISSION HANDLER - ANTI-DOUBLE-CLICK & LOADING
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kursi-form');
    const submitBtn = document.getElementById('payment-btn');
    let isSubmitting = false;

    if (form) {
        form.addEventListener('submit', function(e) {
            // PREVENT DOUBLE SUBMIT
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            // VALIDATE BEFORE SUBMIT
            const selectedSeatsInputs = document.querySelectorAll('#selected-seats-inputs input[type="hidden"]');
            const jumlahPenumpang = parseInt(document.getElementById('max-seats').textContent);

            if (selectedSeatsInputs.length !== jumlahPenumpang) {
                e.preventDefault();
                showAlert('error', 'Kursi Tidak Lengkap', `Anda harus memilih ${jumlahPenumpang} kursi sebelum melanjutkan.`);
                return false;
            }

            // MARK AS SUBMITTING
            isSubmitting = true;

            // DISABLE BUTTON & SHOW LOADING
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'wait';

            // SET TIMEOUT TO PREVENT INFINITE LOADING (allow form to submit)
            setTimeout(() => {
                if (isSubmitting) {
                    console.warn('Form submission still pending...');
                    // Jangan reset, biar form submit sesuai response dari server
                }
            }, 30000); // 30 second timeout

            // NORMAL FORM SUBMISSION - tidak perlu preventDefault
            // Form akan submit secara normal dan redirect akan handle di server
            return true;
        });
    }

    // NOTE: Removed beforeunload handler to avoid browser showing
    // the default "Changes you made may not be saved." dialog.
});

document.addEventListener('DOMContentLoaded', function() {
    // =====================================================
    //   DATA HARGA DARI SERVER – SINKRON DENGAN PESAN
    // =====================================================
    const hargaPerKursi     = {{ $hargaPerOrang ?? 0 }};
    const jumlahPenumpang   = {{ $jumlahPenumpang ?? 1 }};
    const totalTarifServer  = {{ $totalTarif ?? 0 }};      // total tarif tambahan (untuk semua tiket)
    const diskonServer      = {{ $diskon ?? 0 }};          // diskon absolut
    const subtotalServer    = {{ $subtotal ?? 0 }};        // (harga * jumlah) + totalTarif
    const totalBayarServer  = {{ $totalBayar ?? 0 }};      // subtotal - diskon
    const tarifPerKursi     = {{ $tarifPerKursi ?? 0 }};   // tarif per kursi (untuk proporsi)

    // State kursi yang dipilih
    let selectedSeats = [];

    const pemesananId = {{ $pemesanan->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    // Inisialisasi kursi yang sudah terpilih sebelumnya
    document.querySelectorAll('.seat.selected').forEach(seatElement => {
        const seatId = seatElement.getAttribute('data-seat');
        const seatNumber = seatElement.querySelector('.seat-number')?.textContent || seatId;
        selectedSeats.push({
            id: seatId,
            number: seatNumber,
            price: hargaPerKursi
        });
    });

    // =====================================================
    //   FUNGSI-FUNGSI UTAMA (PERBAIKAN VALIDASI KETAT)
    // =====================================================
    window.selectSeat = function(seatElement, seatNumber) {
        // SAFETY CHECK 1: Pastikan element valid
        if (!seatElement || !seatElement.classList) {
            console.error('Invalid seat element');
            return false;
        }

        // SAFETY CHECK 2: Triple-check kursi adalah SOLD - HARUS BLOCK!
        if (seatElement.classList.contains('sold')) {
            console.warn('Attempt to select SOLD seat:', seatNumber);
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} sudah dipesan dan TIDAK dapat dipilih.`);
            return false;
        }

        // SAFETY CHECK 3: Check pointer-events CSS
        const computedStyle = window.getComputedStyle(seatElement);
        if (computedStyle.pointerEvents === 'none') {
            console.warn('Attempt to select seat with pointer-events:none:', seatNumber);
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} sudah dipesan.`);
            return false;
        }

        // SAFETY CHECK 4: Check data attributes
        const seatId = seatElement.getAttribute('data-seat');
        const seatStatus = seatElement.getAttribute('data-status');
        const seatDisabled = seatElement.getAttribute('data-disabled') === '1' || seatElement.getAttribute('aria-disabled') === 'true';

        if (seatStatus === 'terpesan') {
            console.warn('Attempt to select seat with status=terpesan:', seatNumber);
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} sudah dipesan oleh penumpang lain.`);
            return false;
        }

        if (seatDisabled) {
            console.warn('Attempt to select disabled seat:', seatNumber);
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} tidak dapat dipilih.`);
            return false;
        }

        // SAFETY CHECK 5: Kursi harus ada class 'available' atau 'selected'
        if (!seatElement.classList.contains('available') && !seatElement.classList.contains('selected')) {
            console.warn('Seat does not have available/selected class:', seatNumber, 'Current classes:', seatElement.className);
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} tidak dalam kondisi tersedia.`);
            return false;
        }

        // ===== SEMUANYA OK, LANJUTKAN LOGIKA NORMAL =====
        const index = selectedSeats.findIndex(s => s.id === seatId);

        if (index > -1) {
            // Batalkan pilihan -> unlock via AJAX first
            fetch("{{ route('customer.kursi.unlock') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pemesanan_id: pemesananId, nomor_kursi: seatNumber })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    selectedSeats.splice(index, 1);
                    updateSeatUI(seatElement, 'available', 'tersedia', 'fa-check');
                    showAlert('info', 'Kursi Dibatalkan', `Kursi ${seatNumber} tidak lagi dipilih.`);
                    updateSelectedSeatsDisplay();
                    updateFormInputs();
                    updatePaymentButton();
                } else {
                    showAlert('error', 'Gagal', res.message || 'Gagal membatalkan kursi');
                }
            }).catch(err => {
                console.error(err);
                showAlert('error', 'Gagal', 'Terjadi kesalahan jaringan saat membatalkan kursi');
            });
        } else {
            // Tambah pilihan -> lock via AJAX first
            if (selectedSeats.length >= jumlahPenumpang) {
                showAlert('warning', 'Maksimal Kursi', `Anda hanya dapat memilih maksimal ${jumlahPenumpang} kursi!`);
                return false;
            }

            fetch("{{ route('customer.kursi.lock') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pemesanan_id: pemesananId, nomor_kursi: seatNumber })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    selectedSeats.push({ id: seatId, number: seatNumber, price: hargaPerKursi });
                    updateSeatUI(seatElement, 'selected', 'selected', 'fa-user-check');
                    showAlert('success', 'Kursi Dipilih', `Kursi ${seatNumber} berhasil dipilih!`);
                    updateSelectedSeatsDisplay();
                    updateFormInputs();
                    updatePaymentButton();
                } else {
                    showAlert('error', 'Gagal', res.message || 'Gagal mengunci kursi');
                }
            }).catch(err => {
                console.error(err);
                showAlert('error', 'Gagal', 'Terjadi kesalahan jaringan saat memilih kursi');
            });
        }

        updateSelectedSeatsDisplay();
        updateFormInputs();
        updatePaymentButton();

        return false; // Prevent any default action
    };

    function updateSeatUI(seat, cssClass, status, icon) {
        seat.classList.remove('available', 'selected', 'sold');
        seat.classList.add(cssClass);
        seat.setAttribute('data-status', status);
        const iconEl = seat.querySelector('.seat-status-icon');
        if (iconEl) iconEl.className = `fas ${icon} seat-status-icon`;
    }

    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Update ringkasan harga berdasarkan jumlah kursi yang dipilih
    function updateSummaryForSelection(selectedCount) {
        // Subtotal = (harga per kursi + tarif per kursi) * jumlah dipilih
        const subtotal = Math.round((hargaPerKursi + tarifPerKursi) * selectedCount);
        // Diskon proporsional
        const diskonProporsional = Math.round(diskonServer * (selectedCount / (jumlahPenumpang || 1)));
        const totalAfter = Math.max(0, subtotal - diskonProporsional);

        // Update elemen di kolom kiri (jika ada di halaman ini)
        const subtotalEl = document.getElementById('subtotal-amount-left');
        const discountEl = document.getElementById('discount-amount-left');
        const totalLeftEl = document.getElementById('total-amount-left');

        if (subtotalEl) subtotalEl.textContent = `Rp ${formatCurrency(subtotal)}`;
        if (discountEl) discountEl.textContent = `- Rp ${formatCurrency(diskonProporsional)}`;
        if (totalLeftEl) totalLeftEl.textContent = `Rp ${formatCurrency(totalAfter)}`;

        // Update total kecil di bawah kursi yang dipilih
        const totalAmountSpan = document.getElementById('total-amount');
        if (totalAmountSpan) totalAmountSpan.textContent = `Rp ${formatCurrency(totalAfter)}`;
    }

    function updateSelectedSeatsDisplay() {
        const selectedCount = selectedSeats.length;
        const selectedCountEl = document.getElementById('selected-count');
        if (selectedCountEl) selectedCountEl.textContent = selectedCount;

        const list = document.getElementById('selected-list');
        if (list) list.innerHTML = '';
        selectedSeats.forEach(seat => {
            const badge = document.createElement('span');
            badge.className = 'seat-badge-item';
            badge.innerHTML = seat.number;
            if (list) list.appendChild(badge);
        });

        const totalPriceContainer = document.getElementById('total-price-container');
        if (selectedCount > 0) {
            updateSummaryForSelection(selectedCount);
            if (totalPriceContainer) totalPriceContainer.style.display = 'block';
        } else {
            // Kembalikan ke nilai server (semua penumpang)
            const subtotalEl = document.getElementById('subtotal-amount-left');
            const discountEl = document.getElementById('discount-amount-left');
            const totalLeftEl = document.getElementById('total-amount-left');
            const totalAmountSpan = document.getElementById('total-amount');

            if (subtotalEl) subtotalEl.textContent = `Rp ${formatCurrency(subtotalServer)}`;
            if (discountEl) discountEl.textContent = `- Rp ${formatCurrency(diskonServer)}`;
            if (totalLeftEl) totalLeftEl.textContent = `Rp ${formatCurrency(totalBayarServer)}`;
            if (totalAmountSpan) totalAmountSpan.textContent = `Rp ${formatCurrency(totalBayarServer)}`;
            if (totalPriceContainer) totalPriceContainer.style.display = 'none';
        }
    }

    function updateFormInputs() {
        const container = document.getElementById('selected-seats-inputs');
        if (!container) return;
        container.innerHTML = '';
        selectedSeats.forEach((seat, idx) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `kursi[${idx}]`;
            input.value = seat.id;
            container.appendChild(input);
        });
    }

    function updatePaymentButton() {
        const btn = document.getElementById('payment-btn');
        if (!btn) return;
        btn.disabled = selectedSeats.length !== jumlahPenumpang;
    }

    // =====================================================
    //   ALERT & UTILITY
    // =====================================================
    window.showAlert = function(type, title, message) {
        const existing = document.querySelector('.global-alert-container');
        if (existing) existing.remove();

        const container = document.createElement('div');
        container.className = 'global-alert-container';

        const alert = document.createElement('div');
        alert.className = `global-alert alert-${type}`;

        let icon = '';
        switch(type) {
            case 'success': icon = 'fa-check-circle'; break;
            case 'error':   icon = 'fa-exclamation-circle'; break;
            case 'warning': icon = 'fa-exclamation-triangle'; break;
            case 'info':    icon = 'fa-info-circle'; break;
        }

        alert.innerHTML = `
            <div class="alert-content">
                <i class="alert-icon fas ${icon}"></i>
                <div class="alert-message">
                    <strong>${title}</strong>
                    <span>${message}</span>
                </div>
                <button class="alert-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="alert-progress"></div>
        `;

        container.appendChild(alert);
        document.body.appendChild(container);

        const closeBtn = alert.querySelector('.alert-close');
        closeBtn.addEventListener('click', function() {
            alert.classList.add('fade-out');
            setTimeout(() => container.remove(), 300);
        });

        setTimeout(() => {
            if (alert.parentElement) {
                alert.classList.add('fade-out');
                setTimeout(() => container.remove(), 300);
            }
        }, 5000);
    };

    window.toggleRouteDetails = function() {
        const dropdown = document.getElementById('route-details-dropdown');
        const button = document.querySelector('.btn-route-toggle');
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
            button.classList.remove('active');
            button.innerHTML = '<i class="fas fa-chevron-down"></i> Lihat Rute Detail';
        } else {
            dropdown.classList.add('show');
            button.classList.add('active');
            button.innerHTML = '<i class="fas fa-chevron-up"></i> Sembunyikan Rute Detail';
        }
    };

    // ===== ANTI-CLICK HANDLER UNTUK KURSI SOLD =====
    // Block SEMUA kursi dengan class 'sold' agar TIDAK BISA diklik
    function blockSoldSeats() {
        const soldSeats = document.querySelectorAll('.seat.sold');

        soldSeats.forEach(seat => {
            // Remove onclick attribute jika ada
            // Hapus onclick agar tidak memanggil `selectSeat` dari atribut inline
            seat.removeAttribute('onclick');

            // Pastikan kursi relatif sehingga overlay dapat diposisikan di atasnya
            if (window.getComputedStyle(seat).position === 'static') {
                seat.style.position = 'relative';
            }

            // Tambahkan overlay penuh yang menangkap semua event pointer untuk kursi yang sold
            // Overlay ini mencegah anak-elemen menerima event klik/touch, sehingga kursi benar-benar tidak bisa dipilih
            const overlay = document.createElement('div');
            overlay.className = 'seat-overlay';
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.right = '0';
            overlay.style.bottom = '0';
            overlay.style.zIndex = '25';
            overlay.style.cursor = 'not-allowed';

            // Tangani berbagai event pada overlay sebagai fallback
            const blockHandler = function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                showAlert('error', 'Kursi Tidak Tersedia', 'Kursi ' + (seat.getAttribute('data-seat') || 'ini') + ' sudah dipesan oleh penumpang lain.');
                return false;
            };

            overlay.addEventListener('click', blockHandler, { capture: true });
            overlay.addEventListener('mousedown', blockHandler, { capture: true });
            overlay.addEventListener('touchstart', blockHandler, { capture: true });

            // Pastikan semua anak-elemen tidak menerima pointer (fallback tambahan)
            seat.querySelectorAll('*').forEach(child => {
                child.style.pointerEvents = 'none';
            });

            // Tambahkan atribut aksesibilitas dan gaya
            seat.setAttribute('aria-disabled', 'true');
            seat.setAttribute('data-disabled', '1');
            seat.style.setProperty('cursor', 'not-allowed', 'important');

            // Sisipkan overlay di akhir isi kursi
            seat.appendChild(overlay);
        });
    }

    // Inisialisasi
    blockSoldSeats(); // BLOCK KURSI SOLD DULU
    updateSelectedSeatsDisplay();
    updateFormInputs();
    updatePaymentButton();

    // Submit form validation
    document.getElementById('kursi-form').addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION DEBUG ===');
        console.log('Selected Seats:', selectedSeats);
        console.log('Required:', jumlahPenumpang);
        console.log('Form Hidden Inputs:', document.querySelectorAll('#selected-seats-inputs input'));

        if (selectedSeats.length !== jumlahPenumpang) {
            e.preventDefault();
            console.warn('✗ Validation FAILED: ' + selectedSeats.length + ' != ' + jumlahPenumpang);
            showAlert('warning', 'Peringatan', `Anda harus memilih tepat ${jumlahPenumpang} kursi untuk melanjutkan.`);
            return false;
        } else {
            console.log('✓ Validation PASSED - Form will submit');
            // Form submit langsung tanpa confirmation
            return true;
        }
    });
});
</script>
@endpush
