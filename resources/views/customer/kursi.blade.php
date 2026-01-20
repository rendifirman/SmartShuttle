@extends('layouts.app')

@section('title', 'Smart Shuttle - Pilih Kursi')

@push('styles')
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
    }

    .seat.sold::before {
        transform: translate(-50%, -50%) rotate(45deg);
    }

    .seat.sold::after {
        transform: translate(-50%, -50%) rotate(-45deg);
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
<!-- ALERT NOTIFICATION - FIXED: Tidak mempengaruhi konten utama -->
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
@endif

<!-- Main Content -->
<main class="tiket-container">
    <div class="tiket-layout">

        <!-- Left Column - DETAIL PERJALANAN -->
        <div class="left-column">
            <!-- DETAIL PERJALANAN -->
            <div class="detail-card">
                <div class="card-content">
                    <h2 class="card-title">DETAIL PERJALANAN</h2>

                    <div class="journey-info">
                        <h3 class="route-title" id="route-title">
                            @if(isset($pemesanan) && $pemesanan->jadwal)
                                {{ $pemesanan->jadwal->rute_pertama->kota_asal ?? 'Kota Asal' }} →
                                {{ $pemesanan->jadwal->rute_terakhir->kota_tujuan ?? 'Kota Tujuan' }}
                            @else
                                {{ request('kota_asal', 'Jakarta') }} → {{ request('kota_tujuan', 'Jatinangor') }}
                            @endif
                        </h3>
                        <p class="journey-detail" id="journey-date">
                            @if(isset($pemesanan) && $pemesanan->jadwal)
                                @php
                                    $date = \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan);
                                    $hari = [
                                        'Sunday' => 'Minggu',
                                        'Monday' => 'Senin',
                                        'Tuesday' => 'Selasa',
                                        'Wednesday' => 'Rabu',
                                        'Thursday' => 'Kamis',
                                        'Friday' => 'Jumat',
                                        'Saturday' => 'Sabtu'
                                    ];
                                    $hariIndo = $hari[$date->format('l')];

                                    $bulan = [
                                        'January' => 'Januari',
                                        'February' => 'Februari',
                                        'March' => 'Maret',
                                        'April' => 'April',
                                        'May' => 'Mei',
                                        'June' => 'Juni',
                                        'July' => 'Juli',
                                        'August' => 'Agustus',
                                        'September' => 'September',
                                        'October' => 'Oktober',
                                        'November' => 'November',
                                        'December' => 'Desember'
                                    ];
                                    $bulanIndo = $bulan[$date->format('F')];

                                    echo $hariIndo . ', ' . $date->format('d') . ' ' . $bulanIndo . ' ' . $date->format('Y');
                                @endphp
                            @else
                                @php
                                    $departureDate = request('departure_date');
                                    if ($departureDate) {
                                        $date = \Carbon\Carbon::parse($departureDate);
                                        $hari = [
                                            'Sunday' => 'Minggu',
                                            'Monday' => 'Senin',
                                            'Tuesday' => 'Selasa',
                                            'Wednesday' => 'Rabu',
                                            'Thursday' => 'Kamis',
                                            'Friday' => 'Jumat',
                                            'Saturday' => 'Sabtu'
                                        ];
                                        $hariIndo = $hari[$date->format('l')];

                                        $bulan = [
                                            'January' => 'Januari',
                                            'February' => 'Februari',
                                            'March' => 'Maret',
                                            'April' => 'April',
                                            'May' => 'Mei',
                                            'June' => 'Juni',
                                            'July' => 'Juli',
                                            'August' => 'Agustus',
                                            'September' => 'September',
                                            'October' => 'Oktober',
                                            'November' => 'November',
                                            'December' => 'Desember'
                                        ];
                                        $bulanIndo = $bulan[$date->format('F')];

                                        echo $hariIndo . ', ' . $date->format('d') . ' ' . $bulanIndo . ' ' . $date->format('Y');
                                    } else {
                                        echo 'Sabtu, 15 November 2025';
                                    }
                                @endphp
                            @endif
                        </p>
                        <p class="journey-detail" id="journey-time">
                            @if(isset($pemesanan) && $pemesanan->jadwal)
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i') }} WIB
                            @else
                                09:00 WIB
                            @endif
                        </p>
                        <p class="journey-detail" id="passenger-count">
                            @if(isset($pemesanan))
                                {{ $pemesanan->jumlah_penumpang }} Penumpang
                            @else
                                {{ request('passengers', 1) }} Penumpang
                            @endif
                        </p>
                    </div>

                    <!-- Rute Perjalanan -->
                    <div class="route-section">
                        <h4 class="section-subtitle">Rute perjalanan</h4>
                        <div class="route-timeline" id="route-timeline">
                            @if(isset($pemesanan) && $pemesanan->jadwal && $pemesanan->jadwal->rutes)
                                @php
                                    $waktu = \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan);
                                @endphp
                                @foreach($pemesanan->jadwal->rutes as $index => $rute)
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
                                        $durasiMenit = ((int)$durasiParts[0] * 60) + ((int)($durasiParts[1] ?? 0));
                                        $waktu->addMinutes($durasiMenit);
                                    @endphp
                                    <div class="route-point">
                                        <div class="point-marker"></div>
                                        <div class="point-info">
                                            <p class="point-location">{{ $rute->kota_tujuan }}</p>
                                            <p class="point-time">{{ $waktu->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    @if($index < count($pemesanan->jadwal->rutes) - 1)
                                        <div class="route-connector"></div>
                                    @endif
                                @endforeach
                            @else
                                <!-- Default route jika tidak ada data -->
                                <div class="route-point">
                                    <div class="point-marker"></div>
                                    <div class="point-info">
                                        <p class="point-location">Jakarta Pusat</p>
                                        <p class="point-time">09.00 WIB</p>
                                    </div>
                                </div>
                                <div class="route-connector"></div>
                                <div class="route-point">
                                    <div class="point-marker"></div>
                                    <div class="point-info">
                                        <p class="point-location">Rest Area Bush Batu</p>
                                        <p class="point-time">11.30 WIB</p>
                                    </div>
                                </div>
                                <div class="route-connector"></div>
                                <div class="route-point">
                                    <div class="point-marker"></div>
                                    <div class="point-info">
                                        <p class="point-location">Jatinangor</p>
                                        <p class="point-time">12.30 WIB</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Route Details Toggle Button -->
                        <div class="route-details-toggle">
                            <button class="btn-route-toggle" onclick="toggleRouteDetails()">
                                <i class="fas fa-chevron-down"></i> Lihat Rute Detail
                            </button>
                        </div>

                        <!-- Route Details Dropdown -->
                        <div class="route-details-dropdown" id="route-details-dropdown">
                            <div class="route-details-content">
                                @if(isset($pemesanan) && $pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->count() > 0)
                                    @foreach($pemesanan->jadwal->rutes as $index => $rute)
                                        <div class="route-detail-item">
                                            <div class="route-detail-title">
                                                <i class="fas fa-route"></i> {{ $rute->nama_rute ?? 'Rute ' . ($index + 1) }}
                                            </div>

                                            <!-- Origin -->
                                            <div class="route-stop">
                                                <div class="stop-marker origin"></div>
                                                <div class="stop-info">
                                                    <div class="stop-name">{{ $rute->kota_asal }}</div>
                                                    <div class="stop-time">
                                                        Keberangkatan: {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Stops (Simplified - show only first 2 stops if many) -->
                                            @php
                                                $stops = json_decode($rute->rute_pemberhentian, true) ?? [];
                                                $currentTime = \Carbon\Carbon::parse($pemesanan->jadwal->waktu_keberangkatan);
                                                $showLimit = 2;
                                            @endphp

                                            @foreach(array_slice($stops, 0, $showLimit) as $stopIndex => $stop)
                                                @php
                                                    $currentTime->addMinutes(30);
                                                @endphp
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
                                                            @if(count($stop['outlets']) > 2)
                                                                +{{ count($stop['outlets']) - 2 }} lainnya
                                                            @endif
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @php
                                                    $currentTime->addMinutes($stop['durasi_singgah'] ?? 10);
                                                @endphp
                                            @endforeach

                                            @if(count($stops) > $showLimit)
                                                <div class="route-stop">
                                                    <div class="stop-marker stop"></div>
                                                    <div class="stop-info">
                                                        <div class="stop-name">+{{ count($stops) - $showLimit }} pemberhentian lainnya</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Destination -->
                                            @php
                                                $currentTime->addMinutes(30);
                                            @endphp
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

        <!-- Right Column - INFORMASI KURSI DAN DATA KURSI -->
        <div class="right-column">
            <!-- INFORMASI KURSI - DIPINDAHKAN KE ATAS DATA KURSI -->
            <div class="info-card" style="margin-bottom: 24px;">
                <div class="card-content">
                    <h2 class="card-title">INFORMASI KURSI</h2>

                    <!-- Kotak warna seukuran data kursi - DIPERBAIKI -->
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
                            <i class="fas fa-exclamation-triangle"></i> Pastikan Anda memilih kursi sesuai jumlah penumpang ({{ $pemesanan->jumlah_penumpang ?? 1 }} kursi).
                        </p>
                        <p class="notice-text" style="margin-top: 8px;">
                            <i class="fas fa-clock"></i> Anda memiliki waktu <strong>5 menit</strong> untuk menyelesaikan pemilihan kursi sebelum kunci kursi dilepas.
                        </p>
                    </div>

                    <!-- Info Harga - ENHANCED DESIGN -->
                    @if(isset($pemesanan))
                    <div class="price-summary">
                        <div class="price-item">
                            <span class="price-label">Harga Dasar:</span>
                            <span class="price-value">
                                Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}
                                <span class="price-breakdown">/orang</span>
                            </span>
                        </div>
                        <div class="price-item">
                            <span class="price-label">Total Penumpang:</span>
                            <span class="price-value">{{ $pemesanan->jumlah_penumpang }} orang</span>
                        </div>
                        <div class="price-item">
                            <span class="price-label">Subtotal:</span>
                            <span class="price-value">
                                Rp {{ number_format($pemesanan->harga_total * $pemesanan->jumlah_penumpang, 0, ',', '.') }}
                                <span class="price-breakdown">
                                    ({{ $pemesanan->jumlah_penumpang }} × {{ number_format($pemesanan->harga_total, 0, ',', '.') }})
                                </span>
                            </span>
                        </div>
                        @if($pemesanan->diskon > 0)
                        <div class="price-item discount">
                            <span class="price-label">Diskon:</span>
                            <span class="price-value">- Rp {{ number_format($pemesanan->diskon, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="price-item total">
                            <span class="price-label">Total Bayar:</span>
                            <span class="price-value">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- DATA KURSI -->
            <div class="seat-card">
                <div class="card-content">
                    <h2 class="card-title">DATA KURSI</h2>

                    @if(!isset($pemesanan))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Data pemesanan tidak ditemukan.
                            <a href="{{ route('customer.search') }}" class="alert-link">Kembali ke pencarian</a>
                        </div>
                    @else
                    <!-- Informasi Shuttle dengan Fasilitas Badges -->
                    <div class="shuttle-info-summary">
                        <div class="shuttle-name" id="shuttle-name">{{ $pemesanan->jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle Standard 2' }}</div>

                        <div class="shuttle-detail" id="shuttle-detail">
                            @if(isset($pemesanan->jadwal->shuttle->fasilitas))
                                @php
                                    $fasilitasArray = explode(',', $pemesanan->jadwal->shuttle->fasilitas);
                                @endphp
                                <div class="fasilitas-badges">
                                    @foreach($fasilitasArray as $fasilitas)
                                        <span class="badge-fasilitas">{{ trim($fasilitas) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <!-- Fallback jika tidak ada data fasilitas -->
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
                            <span class="price" id="shuttle-price">Rp {{ number_format($pemesanan->harga_total, 0, ',', '.') }}</span>
                            <span class="per-seat">/kursi</span>
                        </div>
                    </div>

                    <!-- Bagian Pengemudi -->
                    <div class="driver-section">
                        <div class="driver-indicator">
                            <i class="fas fa-steering-wheel"></i>
                            <span class="driver-text">Kursi Pengemudi</span>
                        </div>
                    </div>

                    <!-- Form untuk memilih kursi -->
                    <form id="kursi-form" action="{{ route('customer.kursi.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">

                        <!-- Seat Grid - Layout dari Controller -->
                        <div class="seat-grid" id="seat-grid">
                            @if(!empty($layoutKursi) && is_array($layoutKursi))
                                <!-- TAMPILKAN SEMUA KURSI DARI LAYOUT -->
                                @foreach($layoutKursi as $kursi)
                                    @php
                                        $seatClass = $kursi['class'] ?? 'available';
                                        $seatStatus = $kursi['status'] ?? 'tersedia';
                                        $seatIcon = $kursi['icon'] ?? 'fa-check';
                                        $seatNumber = $kursi['nomor'] ?? '';

                                        // Tentukan apakah kursi bisa diklik
                                        $isClickable = ($seatClass === 'available' || $seatClass === 'selected');
                                        $onclick = $isClickable ? "selectSeat(this, '{$seatNumber}')" : "";

                                        // Tentukan title berdasarkan status
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
                                         data-harga="{{ $pemesanan->harga_total + ($kursi['harga_tambahan'] ?? 0) }}"
                                         data-status="{{ $seatStatus }}"
                                         data-nomor="{{ $seatNumber }}"
                                         onclick="{{ $onclick }}"
                                         title="{{ $title }}"
                                         @if($seatClass === 'sold')
                                            style="pointer-events: none; cursor: not-allowed;"
                                         @endif>

                                        <span class="seat-number">{{ $seatNumber }}</span>

                                        @if(isset($kursi['tipe']) && $kursi['tipe'] === 'premium')
                                            <small class="seat-premium-badge">PREMIUM</small>
                                        @endif

                                        @if(isset($kursi['harga_tambahan']) && $kursi['harga_tambahan'] > 0)
                                            <small class="seat-price-extra">+{{ number_format($kursi['harga_tambahan'], 0, ',', '.') }}</small>
                                        @endif

                                        <!-- Icon status -->
                                        <i class="fas {{ $seatIcon }} seat-status-icon"></i>
                                    </div>
                                @endforeach
                            @else
                                <!-- FALLBACK: Generate 9 kursi default (jika layout kosong) -->
                                @php
                                    $totalKursi = $pemesanan->jadwal->shuttle->total_kursi ?? 9;
                                    $rows = ceil($totalKursi / 3);
                                    $kursiCounter = 1;
                                @endphp

                                @for($row = 1; $row <= $rows; $row++)
                                    @for($col = 1; $col <= 3; $col++)
                                        @if($kursiCounter <= $totalKursi)
                                            @php
                                                $seatNumber = $row . chr(64 + $col);
                                                $seatClass = 'available';
                                            @endphp

                                            <div class="seat {{ $seatClass }}"
                                                 data-seat="{{ $seatNumber }}"
                                                 data-harga="{{ $pemesanan->harga_total }}"
                                                 data-status="tersedia"
                                                 data-nomor="{{ $seatNumber }}"
                                                 onclick="selectSeat(this, '{{ $seatNumber }}')"
                                                 title="Tersedia">

                                                <span class="seat-number">{{ $seatNumber }}</span>
                                                <i class="fas fa-check seat-status-icon"></i>
                                            </div>

                                            @php $kursiCounter++; @endphp
                                        @endif
                                    @endfor
                                @endfor
                            @endif
                        </div>

                        <!-- Input hidden untuk menyimpan kursi yang dipilih -->
                        <div id="selected-seats-inputs">
                            <!-- Akan diisi oleh JavaScript -->
                        </div>

                        <!-- Informasi kursi yang dipilih -->
                        <div class="selected-seats">
                            <h4 class="selected-title">Kursi yang Dipilih (<span id="selected-count">0</span>/<span id="max-seats">{{ $pemesanan->jumlah_penumpang }}</span>):</h4>
                            <div class="selected-list" id="selected-list">
                                <!-- Akan diisi oleh JavaScript -->
                            </div>
                            <div class="total-price" id="total-price-container" style="display: none;">
                                <div class="d-flex justify-content-between">
                                    <span>Total: </span>
                                    <span class="total-amount" id="total-amount">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="action-buttons">
                            <button type="button" class="btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="payment-btn" id="payment-btn" disabled>
                                 Lanjutkan ke Detail Pesanan
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedSeats = [];
    const maxSeats = {{ $pemesanan->jumlah_penumpang ?? 1 }};
    const hargaPerKursi = {{ $pemesanan->harga_total ?? 0 }};

    // Inisialisasi kursi yang sudah dipilih sebelumnya
    document.querySelectorAll('.seat.selected').forEach(seatElement => {
        const seatId = seatElement.getAttribute('data-seat');
        const seatNumber = seatElement.querySelector('.seat-number')?.textContent || seatId;

        selectedSeats.push({
            id: seatId,
            number: seatNumber,
            price: hargaPerKursi
        });
    });

    // Fungsi pilih kursi (HANYA DI FRONTEND)
    window.selectSeat = function(seatElement, seatNumber) {
        const seatId = seatElement.getAttribute('data-seat');
        const seatStatus = seatElement.getAttribute('data-status');

        // JANGAN izinkan pilih kursi yang sudah terpesan
        if (seatStatus === 'terpesan') {
            showAlert('error', 'Kursi Tidak Tersedia', `Kursi ${seatNumber} sudah dipesan oleh penumpang lain!`);
            return;
        }

        const seatIndex = selectedSeats.findIndex(seat => seat.id === seatId);

        if (seatIndex > -1) {
            // Batalkan pilihan
            selectedSeats.splice(seatIndex, 1);
            updateSeatUI(seatElement, 'available', 'tersedia', 'fa-check');
            showAlert('info', 'Kursi Dibatalkan', `Kursi ${seatNumber} tidak lagi dipilih.`);
        } else {
            // Cek apakah sudah mencapai batas maksimal
            if (selectedSeats.length >= maxSeats) {
                showAlert('warning', 'Maksimal Kursi', `Anda hanya dapat memilih maksimal ${maxSeats} kursi!`);
                return;
            }

            // Tambahkan pilihan
            selectedSeats.push({
                id: seatId,
                number: seatNumber,
                price: hargaPerKursi
            });
            updateSeatUI(seatElement, 'selected', 'selected', 'fa-user-check');
            showAlert('success', 'Kursi Dipilih', `Kursi ${seatNumber} berhasil dipilih!`);
        }

        updateSelectedSeatsDisplay();
        updateFormInputs();
        updatePaymentButton();
    };

    function updateSeatUI(seatElement, cssClass, status, icon) {
        seatElement.classList.remove('available', 'selected', 'sold');
        seatElement.classList.add(cssClass);
        seatElement.setAttribute('data-status', status);

        // Update icon
        const iconElement = seatElement.querySelector('.seat-status-icon');
        if (iconElement) {
            iconElement.className = `fas ${icon} seat-status-icon`;
        }
    }

    function updateSelectedSeatsDisplay() {
        const selectedCount = document.getElementById('selected-count');
        const selectedList = document.getElementById('selected-list');
        const totalPriceContainer = document.getElementById('total-price-container');
        const totalAmount = document.getElementById('total-amount');

        selectedCount.textContent = selectedSeats.length;
        selectedList.innerHTML = '';

        selectedSeats.forEach(seat => {
            const badge = document.createElement('span');
            badge.className = 'seat-badge-item';
            badge.innerHTML = `${seat.number}`;
            selectedList.appendChild(badge);
        });

        if (selectedSeats.length > 0) {
            const finalTotal = hargaPerKursi * selectedSeats.length;
            totalAmount.textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
            totalPriceContainer.style.display = 'block';
        } else {
            totalPriceContainer.style.display = 'none';
        }
    }

    function updateFormInputs() {
        const container = document.getElementById('selected-seats-inputs');
        container.innerHTML = '';

        selectedSeats.forEach((seat, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `kursi[${index}]`;
            input.value = seat.id;
            container.appendChild(input);
        });
    }

    function updatePaymentButton() {
        const paymentBtn = document.getElementById('payment-btn');
        paymentBtn.disabled = selectedSeats.length !== maxSeats;
    }

    // Fungsi untuk toggle route details - VERSI DIPERBAIKI
    window.toggleRouteDetails = function() {
        const dropdown = document.getElementById('route-details-dropdown');
        const button = document.querySelector('.btn-route-toggle');

        if (dropdown.classList.contains('show')) {
            // Sembunyikan dropdown
            dropdown.classList.remove('show');
            button.classList.remove('active');
            button.innerHTML = '<i class="fas fa-chevron-down"></i> Lihat Rute Detail';
        } else {
            // Tampilkan dropdown
            dropdown.classList.add('show');
            button.classList.add('active');
            button.innerHTML = '<i class="fas fa-chevron-up"></i> Sembunyikan Rute Detail';

            // Optional: Scroll ke dropdown untuk mobile
            if (window.innerWidth < 768) {
                setTimeout(() => {
                    dropdown.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            }
        }
    };

    // Inisialisasi
    updateSelectedSeatsDisplay();
    updateFormInputs();
    updatePaymentButton();
});

// Fungsi untuk menampilkan alert baru
function showAlert(type, title, message) {
    // Remove existing alerts
    const existingContainer = document.querySelector('.global-alert-container');
    if (existingContainer) {
        existingContainer.remove();
    }

    // Create new alert
    const alertContainer = document.createElement('div');
    alertContainer.className = 'global-alert-container';

    const alert = document.createElement('div');
    alert.className = `global-alert alert-${type}`;

    let iconClass = '';
    switch(type) {
        case 'success':
            iconClass = 'fas fa-check-circle';
            break;
        case 'error':
            iconClass = 'fas fa-exclamation-circle';
            break;
        case 'warning':
            iconClass = 'fas fa-exclamation-triangle';
            break;
        case 'info':
            iconClass = 'fas fa-info-circle';
            break;
    }

    alert.innerHTML = `
        <div class="alert-content">
            <i class="alert-icon ${iconClass}"></i>
            <div class="alert-message">
                <strong>${title}</strong>
                <span>${message}</span>
            </div>
            <button class="alert-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="alert-progress"></div>
    `;

    alertContainer.appendChild(alert);
    document.body.appendChild(alertContainer);

    // Close button
    const closeBtn = alert.querySelector('.alert-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            alert.classList.add('fade-out');
            setTimeout(() => {
                if (alertContainer.parentElement) {
                    alertContainer.remove();
                }
            }, 300);
        });
    }

    // Auto-hide setelah 5 detik
    setTimeout(() => {
        if (alert && alert.parentElement) {
            alert.classList.add('fade-out');
            setTimeout(() => {
                if (alertContainer.parentElement) {
                    alertContainer.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Auto-hide untuk alert dari session
document.addEventListener('DOMContentLoaded', function() {
    const alertContainers = document.querySelectorAll('.global-alert-container');

    alertContainers.forEach(container => {
        const alert = container.querySelector('.global-alert');

        // Auto-hide setelah 5 detik
        setTimeout(() => {
            if (alert && container.parentElement) {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    if (container.parentElement) {
                        container.remove();
                    }
                }, 300);
            }
        }, 5000);

        // Close button functionality
        const closeBtn = container.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                const alert = this.closest('.global-alert');
                const container = this.closest('.global-alert-container');

                if (alert) {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        if (container && container.parentElement) {
                            container.remove();
                        }
                    }, 300);
                }
            });
        }
    });
});

// Handle form submission
document.getElementById('kursi-form').addEventListener('submit', function(e) {
    const selectedSeatsCount = document.getElementById('selected-count').textContent;
    const maxSeats = {{ $pemesanan->jumlah_penumpang ?? 1 }};

    if (parseInt(selectedSeatsCount) !== parseInt(maxSeats)) {
        e.preventDefault();
        showAlert('warning', 'Peringatan', `Anda harus memilih tepat ${maxSeats} kursi untuk melanjutkan.`);
    }
});
</script>
@endpush
