@extends('layouts.app-driver')

@section('title', 'Perjalanan - Smart Shuttle Driver')

@push('styles')
<style>
    /* ==========================================================================
       PERJALANAN - SMART SHUTTLE DRIVER
       Theme Match dengan Halaman Lainnya (#0d3559 & #ff6a00)
       Optimized for Mobile
       ========================================================================== */

    :root {
        --primary-color: #0d3559;
        --secondary-color: #ff6a00;
        --accent-color: #2E86AB;
        --background-color: #f5f7fa;
        --text-dark: #333333;
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --success-green: #10b981;
        --gray-bg: #f5f7fa;
        --gray-border: #e2e8f0;
        --gray-text: #64748b;
        --gray-dark: #334155;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --transition: all 0.3s ease;
    }

    /* ===== MODERN CONFIRM MODAL ===== */
    .confirm-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 3000;
        justify-content: center;
        align-items: center;
        padding: 15px;
        backdrop-filter: blur(8px);
        pointer-events: auto;
    }

    .confirm-modal-content {
        background: var(--white);
        border-radius: 28px;
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
        animation: confirmModalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        pointer-events: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    @keyframes confirmModalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .confirm-modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #fee2e2 0%, #ffebee 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
        color: #ef4444;
        animation: pulseWarning 2s infinite;
    }

    @keyframes pulseWarning {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .confirm-modal-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 10px;
    }

    .confirm-modal-message {
        font-size: 16px;
        color: var(--gray-text);
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .confirm-modal-highlight {
        background: var(--gray-bg);
        padding: 15px;
        border-radius: 16px;
        margin: 20px 0;
        border-left: 4px solid var(--primary-orange);
        text-align: left;
    }

    .confirm-modal-highlight p {
        margin: 5px 0;
        font-size: 14px;
        color: var(--gray-dark);
    }

    .confirm-modal-highlight strong {
        color: var(--primary-orange);
        font-size: 16px;
    }

    .confirm-modal-buttons {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .confirm-btn-cancel {
        flex: 1;
        background: var(--gray-bg);
        border: 1px solid var(--gray-border);
        padding: 16px;
        border-radius: 16px;
        color: var(--gray-dark);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .confirm-btn-cancel:hover {
        background: var(--gray-border);
        color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .confirm-btn-confirm {
        flex: 1;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        padding: 16px;
        border-radius: 16px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
    }

    .confirm-btn-confirm:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(239, 68, 68, 0.4);
    }

    .confirm-btn-confirm:active {
        transform: translateY(0);
    }

    /* ===== CONTENT AREA ===== */
    .content-wrapper {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION (SESUAI DENGAN HALAMAN LAIN) ===== */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .title i {
        color: var(--primary-orange);
        font-size: 1.6rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    /* ===== BREADCRUMB ===== */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        font-size: 14px;
        color: var(--gray-text);
        flex-wrap: wrap;
    }

    .breadcrumb-item {
        color: var(--gray-text);
        text-decoration: none;
        transition: var(--transition);
    }

    .breadcrumb-item:hover {
        color: var(--primary-orange);
    }

    .breadcrumb-separator {
        color: #999;
    }

    .breadcrumb-current {
        color: var(--text-dark);
        font-weight: 600;
    }

    /* ===== CARD ===== */
    .card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title i {
        color: var(--primary-orange);
        font-size: 1.1rem;
    }

    .date-display {
        color: var(--gray-text);
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .date-display i {
        color: var(--primary-orange);
    }

    /* ===== DAFTAR PERJALANAN ===== */
    .trip-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .trip-item {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .trip-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--primary-orange);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .trip-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange);
    }

    .trip-item:hover::before {
        opacity: 1;
    }

    .trip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .trip-number {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-orange);
        background: var(--primary-orange-light);
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .seat-info {
        background: var(--gray-bg);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .seat-info i {
        color: var(--primary-orange);
    }

    .trip-route {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        word-break: break-word;
    }

    .trip-route i {
        color: var(--primary-orange);
        font-size: 14px;
        flex-shrink: 0;
    }

    .trip-time {
        font-size: 14px;
        color: var(--gray-text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .trip-time i {
        color: var(--primary-orange);
        font-size: 12px;
        flex-shrink: 0;
    }

    .trip-date {
        font-size: 13px;
        color: #a0aec0;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trip-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray-border);
        flex-wrap: wrap;
        gap: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status {
        background: var(--primary-orange-light);
        color: var(--primary-orange);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-selesai {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-dalam-perjalanan {
        background: #e3f2fd;
        color: #1976d2;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-detail {
        background: var(--primary-dark);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(13, 53, 89, 0.2);
    }

    .btn-detail:hover {
        background: var(--primary-orange);
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .btn-detail:active {
        transform: scale(0.98);
    }

    .btn-back {
        background: var(--gray-bg);
        color: var(--text-dark);
        padding: 8px 20px;
        border-radius: 8px;
        border: 1px solid var(--gray-border);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: var(--white);
        border-color: var(--primary-orange);
        color: var(--primary-orange);
    }

    /* ===== HISTORY SECTION ===== */
    .history-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-select {
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid var(--gray-border);
        background: var(--white);
        color: var(--text-dark);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        outline: none;
        transition: var(--transition);
        min-width: 150px;
    }

    .filter-select:hover {
        border-color: var(--primary-orange);
        box-shadow: 0 2px 8px var(--primary-orange-light);
    }

    .history-items {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .history-item {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        border: 1px solid var(--gray-border);
        transition: var(--transition);
        animation: slideIn 0.3s ease;
    }

    .history-item:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-md);
        transform: translateX(4px);
    }

    .history-route {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        word-break: break-word;
    }

    .history-route i {
        color: var(--primary-orange);
        font-size: 14px;
        flex-shrink: 0;
    }

    .history-date {
        font-size: 13px;
        color: var(--gray-text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .history-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed var(--gray-border);
        flex-wrap: wrap;
        gap: 10px;
    }

    .passenger-count {
        font-size: 14px;
        color: var(--gray-dark);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .passenger-count i {
        color: var(--primary-orange);
    }

    .status-completed {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ===== STYLE DETAIL PERJALANAN ===== */
    .card-aktif {
        display: flex;
        gap: 30px;
        padding: 2rem;
    }

    .card-left {
        flex: 1;
        min-width: 0;
    }

    .card-right {
        width: 380px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .card-title-detail {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary-dark);
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-border);
        position: relative;
        word-break: break-word;
    }

    .card-title-detail i {
        color: #36B35A;
        font-size: 1.3rem;
        flex-shrink: 0;
        animation: pulse 2s infinite;
    }

    .card-title-detail::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--primary-orange);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* ===== PROGRESS BAR ===== */
    .progress-container {
        margin: 25px 0 30px;
        background: var(--gray-bg);
        padding: 1.5rem;
        border-radius: var(--radius-md);
    }

    .progress-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .progress-title i {
        color: var(--primary-orange);
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--gray-border);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #36B35A, #4CAF50);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .progress-stops {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        position: relative;
        gap: 4px;
        overflow-x: auto;
        padding-bottom: 5px;
        -webkit-overflow-scrolling: touch;
    }

    .progress-stop {
        font-size: 12px;
        color: var(--gray-text);
        text-align: center;
        flex: 1;
        min-width: 60px;
        position: relative;
        padding-top: 20px;
        word-break: break-word;
    }

    .progress-stop::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 10px;
        height: 10px;
        background: var(--gray-border);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .progress-stop.active {
        color: #36B35A;
        font-weight: 600;
    }

    .progress-stop.active::before {
        background: #36B35A;
        box-shadow: 0 0 0 3px rgba(54, 179, 90, 0.2);
    }

    .progress-stop.completed {
        color: #1976d2;
        font-weight: 600;
    }

    .progress-stop.completed::before {
        background: #1976d2;
    }

    /* ===== LOCATION STYLES ===== */
    .location {
        margin-bottom: 20px;
        padding: 1.25rem;
        background: var(--gray-bg);
        border-radius: var(--radius-md);
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .location:hover {
        background: var(--white);
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-sm);
    }

    .loc-title {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary-dark);
        margin-bottom: 8px;
        word-break: break-word;
    }

    .loc-title i {
        font-size: 16px;
        flex-shrink: 0;
    }

    .loc-title i.fa-play {
        color: #36B35A;
    }

    .loc-title i.fa-location-dot {
        color: var(--primary-orange);
    }

    .loc-title i.fa-map-marker-alt {
        color: #0095FF;
    }

    .loc-sub {
        font-size: 14px;
        color: var(--gray-text);
        margin-left: 28px;
        line-height: 1.5;
        word-break: break-word;
    }

    .line {
        width: 2px;
        height: 25px;
        background: linear-gradient(180deg, var(--primary-orange) 0%, var(--primary-orange) 50%, transparent 100%);
        margin: 10px 0 10px 24px;
    }

    /* ===== INFO SECTION ===== */
    .info-section {
        background: var(--gray-bg);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--gray-border);
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--gray-border);
    }

    .info-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-row i {
        width: 24px;
        text-align: center;
        color: var(--primary-orange);
        font-size: 18px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-title {
        font-size: 13px;
        color: var(--gray-text);
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-dark);
        word-break: break-word;
    }

    /* ===== BUTTON SECTION ===== */
    .button-section {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-primary, .btn-success, .btn-warning {
        padding: 14px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }

    .btn-primary {
        background: #0095FF;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 149, 255, 0.2);
    }

    .btn-primary:hover {
        background: #007acc;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 149, 255, 0.3);
    }

    .btn-success {
        background: #36B35A;
        color: white;
        box-shadow: 0 4px 12px rgba(54, 179, 90, 0.2);
    }

    .btn-success:hover {
        background: #2d9c4a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(54, 179, 90, 0.3);
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }

    /* ===== MAP BOX ===== */
    .map-box {
        height: 250px;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #1a4d7a 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: var(--radius-md);
        position: relative;
        overflow: hidden;
    }

    .map-box::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .map-placeholder {
        text-align: center;
        color: white;
        font-size: 16px;
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: var(--radius-md);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 90%;
        max-width: 300px;
    }

    .map-placeholder i {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    /* ===== DAFTAR PENUMPANG ===== */
    .penumpang-section {
        padding: 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-border);
        flex-wrap: wrap;
        gap: 10px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary-orange);
    }

    .penumpang-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .penumpang-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-md);
        background: var(--white);
        transition: var(--transition);
        gap: 15px;
        animation: slideIn 0.3s ease;
    }

    .penumpang-item:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-sm);
        transform: translateX(4px);
    }

    .penumpang-info {
        flex: 1;
        min-width: 0;
    }

    .penumpang-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
        word-break: break-word;
    }

    .penumpang-name i {
        color: var(--primary-orange);
        font-size: 14px;
        flex-shrink: 0;
    }

    .penumpang-phone {
        font-size: 14px;
        color: var(--gray-text);
        display: flex;
        align-items: center;
        gap: 6px;
        word-break: break-word;
    }

    .penumpang-phone i {
        color: var(--primary-orange);
        font-size: 12px;
        flex-shrink: 0;
    }

    .penumpang-seat {
        text-align: right;
        min-width: 120px;
    }

    .seat-number {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-orange);
        margin-bottom: 5px;
        background: var(--primary-orange-light);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .seat-status {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }

    .status-refund {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-terdaftar {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-terverifikasi {
        background: #dcfce7;
        color: #16a34a;
    }

    /* ===== MODAL UPDATE LOKASI ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        justify-content: center;
        align-items: center;
        pointer-events: auto;
        padding: 15px;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: var(--white);
        border-radius: 24px;
        width: 100%;
        max-width: 450px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.4s ease;
        position: relative;
        pointer-events: auto;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid var(--gray-border);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .modal-header i {
        font-size: 48px;
        color: var(--primary-orange);
        margin-bottom: 15px;
        background: var(--primary-orange-light);
        padding: 15px;
        border-radius: 50%;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 10px;
    }

    .modal-subtitle {
        font-size: 15px;
        color: var(--gray-text);
        line-height: 1.5;
    }

    .modal-next-location {
        margin: 20px 0;
        padding: 15px;
        background: #f0f9ff;
        border-radius: 12px;
        border-left: 4px solid #0095FF;
    }

    .modal-next-location p {
        font-size: 14px;
        color: #0369a1;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .modal-next-location strong {
        font-size: 18px;
        color: #0284c7;
        word-break: break-word;
        display: block;
        margin-left: 20px;
    }

    #modalOutletsInfo {
        margin-top: 20px;
        padding: 15px;
        background: var(--gray-bg);
        border-radius: 12px;
        border: 1px solid var(--gray-border);
    }

    .modal-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        position: relative;
        z-index: 2001;
    }

    .btn-cancel {
        flex: 1;
        background: var(--gray-bg);
        border: 1px solid var(--gray-border);
        padding: 14px;
        border-radius: 12px;
        color: var(--gray-dark);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        pointer-events: auto;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: var(--gray-border);
        color: var(--primary-dark);
    }

    .btn-update {
        flex: 1;
        background: #0095FF;
        border: none;
        padding: 14px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        pointer-events: auto;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0, 149, 255, 0.3);
    }

    .btn-update:hover {
        background: #007acc;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 149, 255, 0.4);
    }

    /* ===== TOAST NOTIFICATION MODERN ===== */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
        pointer-events: none;
    }

    .toast {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        animation: toastSlideIn 0.3s ease;
        border-left: 4px solid;
        backdrop-filter: blur(10px);
        pointer-events: auto;
        border: 1px solid var(--gray-border);
    }

    .toast.success {
        border-left-color: #10b981;
    }

    .toast.error {
        border-left-color: #ef4444;
    }

    .toast.warning {
        border-left-color: #f59e0b;
    }

    .toast.info {
        border-left-color: #3b82f6;
    }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .toast.error .toast-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .toast.warning .toast-icon {
        background: #fef3c7;
        color: #f59e0b;
    }

    .toast.info .toast-icon {
        background: #dbeafe;
        color: #3b82f6;
    }

    .toast-content {
        flex: 1;
        min-width: 0;
    }

    .toast-title {
        font-weight: 700;
        font-size: 15px;
        color: var(--primary-dark);
        margin-bottom: 3px;
    }

    .toast-message {
        font-size: 13px;
        color: var(--gray-text);
        word-break: break-word;
    }

    .toast-close {
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        transition: color 0.3s ease;
        flex-shrink: 0;
    }

    .toast-close:hover {
        color: var(--primary-dark);
    }

    @keyframes toastSlideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes toastSlideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
        background: var(--gray-bg);
        border-radius: var(--radius-md);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--gray-border);
        margin-bottom: 15px;
    }

    .empty-state p {
        color: var(--gray-text);
        font-size: 15px;
    }

    .empty-state .sub {
        color: #94a3b8;
        font-size: 13px;
        margin-top: 5px;
    }

    /* KELAS UNTUK MENYEMBUNYIKAN ELEMEN */
    .hidden {
        display: none !important;
    }

    .visible {
        display: block !important;
    }

    /* ===== RESPONSIVE MOBILE ===== */
    @media screen and (max-width: 992px) {
        .content-wrapper {
            padding: 1rem;
        }
    }

    @media screen and (max-width: 768px) {
        .content-wrapper {
            padding: 1rem;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 1.5rem;
        }

        .title i {
            font-size: 1.5rem;
        }

        .divider {
            width: 80px;
            margin-bottom: 1rem;
        }

        .breadcrumb {
            font-size: 13px;
            margin-bottom: 20px;
            padding: 0;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card {
            padding: 1.25rem;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .card-title {
            font-size: 1.1rem;
        }

        .date-display {
            font-size: 13px;
            width: 100%;
        }

        .trip-list {
            gap: 12px;
        }

        .trip-item {
            padding: 1.25rem;
        }

        .trip-number {
            font-size: 15px;
        }

        .seat-info {
            font-size: 12px;
        }

        .trip-route {
            font-size: 16px;
        }

        .trip-time, .trip-date {
            font-size: 12px;
        }

        .trip-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .status-badge {
            width: 100%;
        }

        .status, .status-selesai, .status-dalam-perjalanan {
            width: 100%;
            justify-content: center;
        }

        .btn-detail {
            width: 100%;
            justify-content: center;
        }

        .card-aktif {
            flex-direction: column;
            padding: 1.25rem;
            gap: 20px;
        }

        .card-left, .card-right {
            width: 100%;
        }

        .card-title-detail {
            font-size: 1.2rem;
        }

        .progress-container {
            padding: 1.25rem;
        }

        .progress-stop {
            font-size: 11px;
            min-width: 50px;
        }

        .location {
            padding: 1rem;
        }

        .loc-title {
            font-size: 15px;
        }

        .loc-sub {
            font-size: 12px;
            margin-left: 24px;
        }

        .info-section {
            padding: 1.25rem;
        }

        .info-row {
            gap: 12px;
        }

        .info-value {
            font-size: 14px;
        }

        .btn-primary, .btn-success, .btn-warning {
            padding: 12px 20px;
            font-size: 14px;
        }

        .map-box {
            height: 200px;
        }

        .map-placeholder {
            padding: 1.5rem;
        }

        .map-placeholder i {
            font-size: 40px;
        }

        .penumpang-section {
            padding: 1.25rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .penumpang-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 1rem;
        }

        .penumpang-info {
            width: 100%;
        }

        .penumpang-name {
            font-size: 15px;
        }

        .penumpang-phone {
            font-size: 13px;
        }

        .penumpang-seat {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-width: auto;
        }

        .seat-number {
            font-size: 15px;
            margin-bottom: 0;
        }

        .history-filter {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-select {
            width: 100%;
        }

        .history-item {
            padding: 1rem;
        }

        .history-route {
            font-size: 15px;
        }

        .history-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .status-completed {
            width: 100%;
            justify-content: center;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .modal-header i {
            font-size: 40px;
            padding: 12px;
        }

        .modal-title {
            font-size: 20px;
        }

        .modal-next-location strong {
            font-size: 16px;
        }

        .modal-buttons {
            flex-direction: column-reverse;
        }

        .btn-cancel, .btn-update {
            width: 100%;
            padding: 12px;
        }

        .confirm-modal-content {
            padding: 1.5rem;
        }

        .confirm-modal-icon {
            width: 60px;
            height: 60px;
            font-size: 30px;
        }

        .confirm-modal-title {
            font-size: 20px;
        }

        .confirm-modal-message {
            font-size: 14px;
        }

        .confirm-modal-buttons {
            flex-direction: column-reverse;
        }

        .confirm-btn-cancel, .confirm-btn-confirm {
            width: 100%;
        }

        .toast-container {
            max-width: calc(100% - 30px);
            right: 15px;
            left: 15px;
        }

        .toast {
            padding: 14px 16px;
        }
    }

    @media screen and (max-width: 480px) {
        .title {
            font-size: 1.3rem;
        }

        .card-title-detail {
            font-size: 1.1rem;
        }

        .progress-stop {
            font-size: 10px;
            min-width: 45px;
        }

        .loc-title {
            font-size: 14px;
        }

        .map-placeholder {
            padding: 1rem;
            font-size: 14px;
        }

        .map-placeholder i {
            font-size: 32px;
        }

        .penumpang-name {
            font-size: 14px;
        }

        .seat-number {
            font-size: 14px;
            padding: 3px 10px;
        }

        .modal-title {
            font-size: 18px;
        }
    }

    @media screen and (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }

        .trip-number {
            font-size: 14px;
        }

        .trip-route {
            font-size: 15px;
        }

        .progress-stop {
            min-width: 40px;
            font-size: 9px;
        }
    }

    /* Landscape mode */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .card-aktif {
            flex-direction: row;
        }

        .card-left {
            width: 60%;
        }

        .card-right {
            width: 38%;
        }

        .button-section {
            gap: 8px;
        }

        .btn-primary, .btn-success, .btn-warning {
            padding: 10px 12px;
            font-size: 13px;
        }

        .penumpang-item {
            flex-direction: row;
            align-items: center;
        }

        .penumpang-seat {
            width: auto;
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .content-wrapper {
            padding: 1.5rem;
        }

        .card-aktif {
            gap: 20px;
        }

        .card-left {
            flex: 1.2;
        }

        .card-right {
            width: 300px;
        }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- HEADER SECTION - SESUAI DENGAN HALAMAN LAIN -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-route menu-icon"></i>
             Perjalanan
        </h1>
    </div>

    <div class="divider"></div>

    <!-- ========================== -->
    <!-- HALAMAN DAFTAR PERJALANAN -->
    <!-- ========================== -->
    <div id="daftarPerjalananPage" class="driver-page">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('driver.dashboard') }}" class="breadcrumb-item">Dashboard</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Perjalanan</span>
        </div>

        <!-- CARD DAFTAR PERJALANAN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-bus"></i>
                    Daftar Perjalanan Hari Ini
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="currentDateDisplay">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
                </div>
            </div>

            <!-- LIST PERJALANAN -->
            <div class="trip-list" id="tripListContainer">
                @forelse($tripsData as $trip)
                <div class="trip-item" data-trip-id="{{ $trip['id_jadwal_driver'] }}" data-from="{{ $trip['from'] }}" data-to="{{ $trip['to'] }}" data-time="{{ $trip['time'] }}" data-eta="{{ $trip['eta'] ?? '' }}" data-duration="{{ $trip['estimated_duration'] }}" data-distance="{{ $trip['distance'] ?? '' }}" data-seats="{{ $trip['occupied_seats'] }}/{{ $trip['total_seats'] }}" data-passengers="{{ $trip['occupied_seats'] }}">
                    <div class="trip-header">
                        <div class="trip-number">
                            <i class="fas fa-tag"></i> {{ $trip['trip_number'] }}
                        </div>
                        <div class="seat-info">
                            <i class="fas fa-users"></i> {{ $trip['occupied_seats'] }}/{{ $trip['total_seats'] }}
                        </div>
                    </div>

                    <div class="trip-route">
                        <i class="fas fa-arrow-right"></i> {{ $trip['from'] }} → {{ $trip['to'] }}
                    </div>

                    <div class="trip-time">
                        <i class="fas fa-clock"></i> {{ $trip['time'] }} • {{ $trip['estimated_duration'] ?? '-' }}
                    </div>

                    <div class="trip-date">
                        <i class="fas fa-calendar-day"></i> {{ $trip['date'] ?? date('d M Y') }}
                    </div>

                    <div class="trip-footer">
                        <div class="status-badge">
                            @php
                                $status = $trip['status'] ?? 'belum_dimulai';
                                $statusDisplay = match($status) {
                                    'belum_dimulai' => 'Akan Berangkat',
                                    'aktif' => 'Perjalanan Aktif',
                                    'selesai' => 'Selesai',
                                    default => ucfirst(str_replace('_', ' ', $status))
                                };
                            @endphp
                            <span class="status {{ $status === 'selesai' ? 'status-selesai' : ($status === 'aktif' ? 'status-dalam-perjalanan' : 'status') }}" id="status-{{ $trip['id_jadwal_driver'] }}">
                                <i class="fas {{ $status === 'selesai' ? 'fa-check-circle' : ($status === 'aktif' ? 'fa-play-circle' : 'fa-hourglass-half') }}"></i>
                                {{ $statusDisplay }}
                            </span>
                        </div>

                        <button type="button" class="btn-detail">
                            <i class="fas fa-arrow-right"></i> Lihat Detail
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>Tidak ada perjalanan hari ini</p>
                    <p class="sub">Menunggu jadwal dari admin</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- RIWAYAT PERJALANAN (DI HALAMAN AWAL) -->
        <div class="card">
            <div class="history-filter">
                <div class="card-title">
                    <i class="fas fa-history"></i>
                    Riwayat Perjalanan
                </div>
                <select class="filter-select" id="historyFilterSelect">
                    <option>Semua</option>
                    <option>Minggu ini</option>
                    <option>Bulan ini</option>
                    <option>3 bulan terakhir</option>
                </select>
            </div>

            <div class="history-items" id="historyItemsContainer">
                <!-- History items akan dirender oleh JavaScript -->
            </div>
        </div>
    </div>

    <!-- ========================== -->
    <!-- HALAMAN DETAIL PERJALANAN -->
    <!-- ========================== -->
    <div id="detailPerjalananPage" class="driver-page hidden">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="#" id="backToDaftar" class="breadcrumb-item">Perjalanan</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Detail Perjalanan</span>
        </div>

        <!-- Header dengan tombol kembali -->
        <div class="header-section">
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <button class="btn-back" id="backButton">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <h1 class="title">Detail Perjalanan</h1>
            </div>
        </div>

        <div class="divider"></div>

        <!-- CARD PERJALANAN AKTIF -->
        <div class="card card-aktif">
            <div class="card-left">
                <h3 class="card-title-detail">
                    <i class="fa-solid fa-circle-play" id="tripIcon"></i> 
                    <span id="tripTitle">Perjalanan Aktif</span>
                </h3>

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-title">
                        <i class="fas fa-chart-line"></i> Progress Perjalanan
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                    </div>
                    <div class="progress-stops" id="progressStops">
                        <!-- Progress stops akan diisi JavaScript -->
                    </div>
                </div>

                <!-- Rute Perjalanan -->
                <div class="location">
                    <div class="loc-title">
                        <i class="fa-solid fa-play"></i> 
                        <span id="currentLocation">Bandung</span>
                    </div>
                    <div class="loc-sub" id="currentLocationDetail">Terminal Bus Leuwipanjang</div>
                </div>

                <div class="line"></div>

                <div class="location">
                    <div class="loc-title">
                        <i class="fa-solid fa-location-dot"></i> 
                        <span id="finalDestination">Jakarta Pusat</span>
                    </div>
                    <div class="loc-sub" id="finalDestinationDetail">Terminal Bus Kampung Rambutan</div>
                </div>

                <!-- Titik Pemberhentian -->
                <div id="stopPoints" style="margin-top: 25px;">
                    <!-- Titik pemberhentian akan ditampilkan di sini -->
                </div>
            </div>

            <div class="card-right">
                <div class="info-section">
                    <div class="info-row">
                        <i class="fa-solid fa-clock"></i>
                        <div>
                            <div class="info-title">Waktu Tempuh</div>
                            <div class="info-value" id="travelTime">3 jam 15 menit</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-road"></i>
                        <div>
                            <div class="info-title">Jarak</div>
                            <div class="info-value" id="distance">145 km</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <div class="info-title">Penumpang</div>
                            <div class="info-value" id="passengerCount">10/12</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <i class="fa-solid fa-flag-checkered"></i>
                        <div>
                            <div class="info-title">Titik Selanjutnya</div>
                            <div class="info-value" id="nextStop">Rest Area KM 58</div>
                        </div>
                    </div>
                </div>

                <div class="button-section">
                    <button class="btn-success" id="mulaiPerjalananBtn">
                        <i class="fa-solid fa-play"></i> Mulai Perjalanan
                    </button>
                    <button class="btn-primary" id="updateLokasiBtn">
                        <i class="fa-solid fa-location-arrow"></i> Update Lokasi
                    </button>
                    <button class="btn-warning" id="selesaiPerjalananBtn">
                        <i class="fa-solid fa-flag-checkered"></i> Selesaikan Perjalanan
                    </button>
                </div>
            </div>
        </div>

        <!-- MAPS -->
        <div class="card map-box">
            <div class="map-placeholder">
                <i class="fa-solid fa-location-dot"></i>
                <p>Maps akan tampil saat perjalanan aktif</p>
                <div style="margin-top: 15px; font-size: 14px;">
                    <strong>Posisi Saat Ini:</strong> <span id="mapLocation">Bandung</span>
                </div>
            </div>
        </div>

        <!-- DAFTAR PENUMPANG (DI HALAMAN DETAIL) -->
        <div class="card penumpang-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Daftar Penumpang
                </h3>
                <div class="passenger-count">
                    Total: <strong id="totalPenumpang">10</strong> penumpang
                </div>
            </div>

            <!-- Daftar Penumpang - akan di-generate secara dinamis -->
            <div class="penumpang-list" id="penumpangList">
                <!-- Daftar penumpang akan di-generate disini -->
            </div>
        </div>
    </div>

    <!-- Toast Container untuk Notifikasi Modern -->
    <div class="toast-container" id="toastContainer"></div>
</div>

<!-- MODAL UPDATE LOKASI -->
<div class="modal-overlay" id="updateLokasiModal">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-map-marker-alt"></i>
            <h3 class="modal-title">Update Lokasi</h3>
            <p class="modal-subtitle">Lokasi bus akan berpindah ke titik berikutnya</p>
        </div>

        <div class="modal-next-location">
            <p><i class="fas fa-arrow-right"></i> Menuju:</p>
            <strong id="modalNextLocation"></strong>
        </div>

        <!-- OUTLETS INFO SECTION -->
        <div id="modalOutletsInfo" style="display: none;">
            <p style="font-weight: 600; margin-bottom: 10px; color: #1e293b;">
                <i class="fas fa-store" style="margin-right: 5px; color: #ff6a00;"></i>
                Outlets di Pemberhentian:
            </p>
            <div id="modalOutletsList"></div>
        </div>

        <div class="modal-buttons">
            <button class="btn-cancel" id="cancelUpdateBtn">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="btn-update" id="confirmUpdateBtn">
                <i class="fas fa-check"></i> Update
            </button>
        </div>
    </div>
</div>

<!-- MODERN CONFIRM MODAL UNTUK SELESAIKAN PERJALANAN -->
<div class="confirm-modal-overlay" id="confirmSelesaiModal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="confirm-modal-title">Selesaikan Perjalanan?</h3>
        <p class="confirm-modal-message">Apakah Anda yakin ingin menyelesaikan perjalanan ini?</p>
        
        <div class="confirm-modal-highlight">
            <p><i class="fas fa-route"></i> Rute: <strong id="confirmRute"></strong></p>
            <p><i class="fas fa-users"></i> Total Penumpang: <strong id="confirmPenumpang"></strong></p>
            <p><i class="fas fa-clock"></i> Waktu: <strong id="confirmWaktu"></strong></p>
        </div>

        <div class="confirm-modal-buttons">
            <button class="confirm-btn-cancel" id="confirmCancelBtn">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="confirm-btn-confirm" id="confirmYesBtn">
                <i class="fas fa-check"></i> Ya, Selesaikan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Server-provided trips data (generated in DriverController)
    const tripsData = {!! json_encode($tripsData ?? []) !!};
    const completedTrips = {!! json_encode($completedTrips ?? []) !!};
    const currentDriverId = {!! json_encode(auth()->guard('driver')->user()?->id ?? null) !!};

    // Flag untuk mencegah multiple toasts
    let isToastShowing = false;

    // ★★★ TOAST NOTIFICATION SYSTEM ★★★
    function showToast(title, message, type = 'success', duration = 3000) {
        // Prevent multiple toasts at the same time
        if (isToastShowing) {
            const existingToasts = document.querySelectorAll('.toast');
            existingToasts.forEach(toast => toast.remove());
        }

        const toastContainer = document.getElementById('toastContainer');
        
        // Clear any existing toasts
        toastContainer.innerHTML = '';
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = '';
        switch(type) {
            case 'success':
                icon = 'fa-check-circle';
                break;
            case 'error':
                icon = 'fa-exclamation-circle';
                break;
            case 'warning':
                icon = 'fa-exclamation-triangle';
                break;
            case 'info':
                icon = 'fa-info-circle';
                break;
        }
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icon}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <div class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        isToastShowing = true;
        
        // Auto remove after duration
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
                isToastShowing = false;
            }, 300);
        }, duration);
    }

    // ★★★ DEBUG: LOG DATA YANG DITERIMA ★★★
    console.log('%c=== PERJALANAN DATA DEBUG ===', 'color: blue; font-weight: bold; font-size: 14px;');
    console.log('Total Jadwal Aktif:', tripsData.length);
    console.log('Total Jadwal Selesai:', completedTrips.length);
    console.log('Driver ID:', currentDriverId);

    if (tripsData.length > 0) {
        console.log('%cJADWAL AKTIF:', 'color: green; font-weight: bold;');
        tripsData.forEach((t, idx) => {
            console.log(`  ${idx + 1}. [${t.id_jadwal_driver}] ${t.from} → ${t.to} | ${t.date} | ${t.time} | Status: ${t.status}`);
        });
    }

    if (completedTrips.length > 0) {
        console.log('%cJADWAL SELESAI:', 'color: orange; font-weight: bold;');
        completedTrips.forEach((t, idx) => {
            console.log(`  ${idx + 1}. [${t.id_jadwal_driver}] ${t.from} → ${t.to} | ${t.date || t.tanggal} | Status: ${t.status}`);
        });
    }
    console.log('%c==============================', 'color: blue; font-weight: bold;');

    // ★★★ JOURNEY START STATE TRACKING ★★★
    let journeyStarted = {}; // Tracks which trips have been started: { tripId: true/false }

    // Fallback journey data (used when route stop points are not available)
    let journeyData = {
        currentStopIndex: 0,
        stops: [
            { name: "Start", detail: "Lokasi Awal", type: "start" },
            { name: "Titik 1", detail: "Titik Pemberhentian 1", type: "stop" },
            { name: "Titik 2", detail: "Titik Pemberhentian 2", type: "stop" },
            { name: "Finish", detail: "Tujuan Akhir", type: "finish" }
        ],
        travelTimes: ["-", "-", "-", "-"],
        distances: ["-", "-", "-", "-"]
    };

    // Variable untuk menyimpan ID perjalanan yang sedang aktif
    let currentTripId = null;

    // Fungsi untuk mengatur status aktif pada menu sidebar
    function setActiveMenu() {
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });

        const currentPath = window.location.pathname;
        let activeLink = null;

        if (currentPath.includes('dashboard')) {
            activeLink = document.getElementById('dashboard-link');
        } else if (currentPath.includes('perjalanan')) {
            activeLink = document.getElementById('perjalanan-link');
        } else if (currentPath.includes('jadwal')) {
            activeLink = document.getElementById('jadwal-link');
        } else if (currentPath.includes('profile')) {
            activeLink = document.getElementById('profile-link');
        } else if (currentPath.includes('laporan')) {
            activeLink = document.getElementById('laporan-link');
        } else if (currentPath.includes('pengaturan')) {
            activeLink = document.getElementById('pengaturan-link');
        } else if (currentPath.includes('bantuan')) {
            activeLink = document.getElementById('bantuan-link');
        }

        if (!activeLink) {
            activeLink = document.getElementById('dashboard-link');
        }

        if (activeLink) {
            activeLink.classList.add('menu-active');
        }
    }

    // Fungsi untuk menampilkan modal konfirmasi selesai perjalanan
    function showConfirmSelesaiModal() {
        if (!currentTripId) return;
        
        const tripData = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));
        if (!tripData) return;
        
        document.getElementById('confirmRute').textContent = `${tripData.from || '-'} → ${tripData.to || '-'}`;
        document.getElementById('confirmPenumpang').textContent = tripData.occupied_seats || 0;
        document.getElementById('confirmWaktu').textContent = tripData.time || '-';
        
        document.getElementById('confirmSelesaiModal').style.display = 'flex';
    }
    
    function hideConfirmSelesaiModal() {
        document.getElementById('confirmSelesaiModal').style.display = 'none';
    }

    // ★★★ FUNGSI UNTUK MEMBANGUN JOURNEY DATA DARI STOP POINTS ★★★
    function buildJourneyDataFromStopPoints(tripData) {
        const stopPoints = tripData.stop_points || [];

        // Inisialisasi struktur journey data
        journeyData = {
            currentStopIndex: 0,
            stops: [],
            travelTimes: [],
            distances: []
        };

        // STEP 1: Ambil titik awal - buat entry untuk SETIAP outlet di stop point pertama
        if (Array.isArray(stopPoints) && stopPoints.length > 0) {
            const firstStop = stopPoints[0];

            if (firstStop.outlets && firstStop.outlets.length > 0) {
                firstStop.outlets.forEach((outlet, outletIdx) => {
                    const isFirstOutlet = outletIdx === 0;
                    journeyData.stops.push({
                        name: outlet.nama_outlet,
                        detail: `${firstStop.branch_name} (Awal)`,
                        type: isFirstOutlet ? "start" : "stop",
                        outlet_id: outlet.id,
                        outlet_detail: outlet,
                        branch_id: firstStop.branch_id,
                        kota: firstStop.kota,
                        is_starting_outlet: true,
                        is_outlet: true
                    });

                    journeyData.travelTimes.push("-");
                    journeyData.distances.push("-");
                });
            } else {
                journeyData.stops.push({
                    name: tripData.from,
                    detail: `Titik Awal - ${tripData.from}`,
                    type: "start",
                    is_outlet: false
                });
                journeyData.travelTimes.push("-");
                journeyData.distances.push("-");
            }
        } else {
            journeyData.stops.push({
                name: tripData.from,
                detail: `Titik Awal - ${tripData.from}`,
                type: "start",
                is_outlet: false
            });
            journeyData.travelTimes.push("-");
            journeyData.distances.push("-");
        }

        // STEP 2: Tambahkan outlets pemberhentian
        if (Array.isArray(stopPoints) && stopPoints.length > 1) {
            for (let stopIdx = 1; stopIdx < stopPoints.length; stopIdx++) {
                const stop = stopPoints[stopIdx];

                if (stop.outlets && stop.outlets.length > 0) {
                    stop.outlets.forEach((outlet, outletIdx) => {
                        journeyData.stops.push({
                            name: outlet.nama_outlet,
                            detail: `${stop.branch_name} | ${stop.durasi_singgah || 10} menit singgah`,
                            type: "stop",
                            outlet_id: outlet.id,
                            outlet_detail: outlet,
                            branch_id: stop.branch_id,
                            kota: stop.kota,
                            duration: stop.durasi_singgah || 10,
                            is_outlet: true,
                            stop_point_index: stopIdx
                        });

                        journeyData.travelTimes.push(`${stop.durasi_singgah || 10} menit singgah`);
                        journeyData.distances.push("-");
                    });
                } else {
                    journeyData.stops.push({
                        name: stop.kota || `Stop ${stopIdx}`,
                        detail: stop.branch_name || '',
                        type: "stop",
                        duration: stop.durasi_singgah || 10,
                        is_outlet: false,
                        stop_point_index: stopIdx
                    });

                    journeyData.travelTimes.push(`${stop.durasi_singgah || 10} menit singgah`);
                    journeyData.distances.push("-");
                }
            }
        }

        // STEP 3: Tambahkan titik akhir
        if (Array.isArray(stopPoints) && stopPoints.length > 0) {
            const lastStop = stopPoints[stopPoints.length - 1];

            if (lastStop.outlets && lastStop.outlets.length > 0) {
                lastStop.outlets.forEach((outlet, outletIdx) => {
                    const isLastOutlet = outletIdx === lastStop.outlets.length - 1;
                    journeyData.stops.push({
                        name: outlet.nama_outlet,
                        detail: `${lastStop.branch_name} (Tujuan Akhir)`,
                        type: isLastOutlet ? "finish" : "stop",
                        outlet_id: outlet.id,
                        outlet_detail: outlet,
                        branch_id: lastStop.branch_id,
                        kota: lastStop.kota,
                        is_outlet: true,
                        is_final_outlet: true
                    });

                    journeyData.travelTimes.push("-");
                    journeyData.distances.push("-");
                });
            } else {
                journeyData.stops.push({
                    name: tripData.to,
                    detail: `Tujuan Akhir - ${tripData.to}`,
                    type: "finish",
                    is_outlet: false
                });
                journeyData.travelTimes.push("-");
                journeyData.distances.push("-");
            }
        }

        console.log('✅ Journey data built dengan struktur per-OUTLET', journeyData);
    }

    // Fungsi untuk mengupdate tampilan perjalanan di halaman detail
    function updateJourneyDisplay() {
        if (!journeyData || !journeyData.stops || journeyData.stops.length === 0) {
            console.warn('⚠️ journeyData tidak valid');
            return;
        }
        
        const currentStop = journeyData.stops[journeyData.currentStopIndex];
        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        const isLastStop = journeyData.currentStopIndex === journeyData.stops.length - 1;

        console.log('🎯 updateJourneyDisplay - isLastStop:', isLastStop, 'currentStopIndex:', journeyData.currentStopIndex, 'totalStops:', journeyData.stops.length);

        // Update lokasi saat ini
        document.getElementById('currentLocation').textContent = currentStop.name;

        const currentLocationDetailEl = document.getElementById('currentLocationDetail');
        if (currentStop.is_outlet && currentStop.outlet_detail) {
            let html = '';
            if (currentStop.detail) html += `<strong>${currentStop.detail}</strong>`;
            html += `<div style="font-size:13px; color:#666; margin-top:6px;">`;
            html += `<div style="margin-bottom:6px;"><strong>${currentStop.outlet_detail.nama_outlet}</strong>`;
            if (currentStop.outlet_detail.alamat) {
                html += `<div style="font-size:11px; color:#666;">${currentStop.outlet_detail.alamat}</div>`;
            }
            html += `</div></div>`;
            currentLocationDetailEl.innerHTML = html;
        } else {
            currentLocationDetailEl.textContent = currentStop.detail || '';
        }
        document.getElementById('mapLocation').textContent = currentStop.name;

        // Update tujuan akhir
        const finalDestination = journeyData.stops[journeyData.stops.length - 1];
        document.getElementById('finalDestination').textContent = finalDestination.name;
        const finalDestinationEl = document.getElementById('finalDestinationDetail');
        if (finalDestination.is_outlet && finalDestination.outlet_detail) {
            let html = '';
            if (finalDestination.detail) html += `<strong>${finalDestination.detail}</strong>`;
            html += `<div style="font-size:13px; color:#666; margin-top:6px;">`;
            html += `<div style="margin-bottom:6px;"><strong>${finalDestination.outlet_detail.nama_outlet}</strong>`;
            if (finalDestination.outlet_detail.alamat) {
                html += `<div style="font-size:11px; color:#666;">${finalDestination.outlet_detail.alamat}</div>`;
            }
            html += `</div></div>`;
            finalDestinationEl.innerHTML = html;
        } else {
            finalDestinationEl.textContent = finalDestination.detail || '';
        }

        // Update info dari tripsData
        const activeTrip = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));

        if (activeTrip) {
            const travelEl = document.getElementById('travelTime');
            if (activeTrip.estimated_duration && activeTrip.estimated_duration !== '-') {
                travelEl.textContent = activeTrip.estimated_duration;
            } else if (activeTrip.time && activeTrip.eta) {
                travelEl.textContent = `${activeTrip.time} - ${activeTrip.eta}`;
            } else {
                travelEl.textContent = journeyData.travelTimes[journeyData.currentStopIndex] || '-';
            }

            const distEl = document.getElementById('distance');
            if (activeTrip.distance) {
                distEl.textContent = typeof activeTrip.distance === 'number' ? `${activeTrip.distance} km` : activeTrip.distance;
            } else {
                distEl.textContent = journeyData.distances[journeyData.currentStopIndex] || '-';
            }

            const passengerEl = document.getElementById('passengerCount');
            if (passengerEl) {
                passengerEl.textContent = `${activeTrip.occupied_seats || 0}/${activeTrip.total_seats || 0}`;
            }

            const totalPenumpangEl = document.getElementById('totalPenumpang');
            if (totalPenumpangEl) {
                totalPenumpangEl.textContent = activeTrip.passengers ? activeTrip.passengers.length : 0;
            }
        } else {
            document.getElementById('travelTime').textContent = journeyData.travelTimes[journeyData.currentStopIndex] || '-';
            document.getElementById('distance').textContent = journeyData.distances[journeyData.currentStopIndex] || '-';
        }

        // Update titik selanjutnya
        if (!isLastStop) {
            document.getElementById('nextStop').textContent = nextStop.name;
        } else {
            document.getElementById('nextStop').textContent = "Tujuan Akhir (Selesai)";
        }

        // Update progress stops
        const progressContainer = document.getElementById('progressStops');
        if (progressContainer) {
            progressContainer.innerHTML = '';

            journeyData.stops.forEach((stop, idx) => {
                const div = document.createElement('div');
                div.className = 'progress-stop';
                div.textContent = stop.name;
                if (idx === journeyData.currentStopIndex) {
                    div.classList.add('active');
                } else if (idx < journeyData.currentStopIndex) {
                    div.classList.add('completed');
                }
                progressContainer.appendChild(div);
            });

            // Update progress bar width
            const progressPercent = (journeyData.currentStopIndex / Math.max(1, (journeyData.stops.length - 1))) * 100;
            document.getElementById('progressFill').style.width = `${progressPercent}%`;
        }

        // Update status titik pemberhentian
        updateStopPoints();

        // SELALU RESET SEMUA TOMBOL TERLEBIH DAHULU
        const mulaiBtn = document.getElementById('mulaiPerjalananBtn');
        const updateBtn = document.getElementById('updateLokasiBtn');
        const selesaiBtn = document.getElementById('selesaiPerjalananBtn');
        const tripIcon = document.getElementById('tripIcon');
        const tripTitle = document.getElementById('tripTitle');
        
        // Sembunyikan semua tombol dulu
        mulaiBtn.classList.add('hidden');
        updateBtn.classList.add('hidden');
        selesaiBtn.classList.add('hidden');
        
        const activeTripDetail = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));
        const tripStatus = activeTripDetail ? activeTripDetail.status : 'belum_dimulai';
        
        console.log(`🎯 updateJourneyDisplay - Trip ${currentTripId}: status=${tripStatus}, currentStopIndex=${journeyData.currentStopIndex}, isLastStop=${isLastStop}, journeyStarted=${journeyStarted[currentTripId]}`);
        
        // CEK APAKAH PERJALANAN SUDAH SELESAI
        if (tripStatus === 'selesai') {
            // Perjalanan sudah selesai
            tripIcon.className = 'fa-solid fa-circle-check';
            tripIcon.style.color = '#2e7d32';
            tripTitle.textContent = 'Perjalanan Selesai';
            // Semua tombol tetap hidden
            console.log('✅ Perjalanan sudah selesai, semua tombol hidden');
        } 
        // CEK APAKAH SUDAH DI STOP TERAKHIR (TUJUAN AKHIR)
        else if (isLastStop) {
            // Sudah sampai di tujuan akhir, TAMPILKAN TOMBOL SELESAIKAN
            selesaiBtn.classList.remove('hidden');
            tripIcon.className = 'fa-solid fa-flag-checkered';
            tripIcon.style.color = '#f59e0b';
            tripTitle.textContent = 'Sampai di Tujuan Akhir';
            console.log('✅ Sudah di tujuan akhir, menampilkan tombol SELESAIKAN');
            
            // Pastikan journey started true
            if (!journeyStarted[currentTripId]) {
                journeyStarted[currentTripId] = true;
            }
        } 
        // CEK APAKAH PERJALANAN SEDANG BERLANGSUNG
        else if (journeyStarted[currentTripId]) {
            // Perjalanan sedang berlangsung
            updateBtn.classList.remove('hidden');
            tripIcon.className = 'fa-solid fa-circle-play';
            tripIcon.style.color = '#36B35A';
            tripTitle.textContent = 'Perjalanan Aktif';
            console.log('✅ Perjalanan sedang berlangsung, menampilkan tombol UPDATE');
        } 
        // PERJALANAN BELUM DIMULAI
        else {
            // Perjalanan belum dimulai
            mulaiBtn.classList.remove('hidden');
            tripIcon.className = 'fa-solid fa-hourglass-half';
            tripIcon.style.color = '#ff6a00';
            tripTitle.textContent = 'Perjalanan Belum Dimulai';
            console.log('✅ Perjalanan belum dimulai, menampilkan tombol MULAI');
        }

        // Update modal
        if (!isLastStop && nextStop) {
            document.getElementById('modalNextLocation').textContent = nextStop.name;
        }
    }

    // Fungsi untuk menampilkan titik pemberhentian
    function updateStopPoints() {
        const stopPointsContainer = document.getElementById('stopPoints');
        stopPointsContainer.innerHTML = '';

        for (let i = 1; i < journeyData.stops.length - 1; i++) {
            const stop = journeyData.stops[i];
            if (i <= journeyData.currentStopIndex) {
                const stopElement = document.createElement('div');
                stopElement.className = 'location';
                
                let detailHtmlPassed = '';
                if (stop.outlets && stop.outlets.length > 0) {
                    if (stop.detail) detailHtmlPassed += `<strong>${stop.detail}</strong>`;
                    detailHtmlPassed += '<div style="font-size:13px; color:#888; margin-top:6px;">';
                    stop.outlets.forEach(o => {
                        detailHtmlPassed += `<div style="margin-bottom:6px;"><strong>${o.nama_outlet}</strong>`;
                        if (o.alamat) detailHtmlPassed += `<div style="font-size:11px; color:#888;">${o.alamat}</div>`;
                        detailHtmlPassed += `</div>`;
                    });
                    detailHtmlPassed += '</div>';
                } else {
                    detailHtmlPassed = stop.detail || '';
                }

                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span style="text-decoration: line-through; color: #888;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #888;">${detailHtmlPassed} (Telah dilewati)</div>
                `;
                stopPointsContainer.appendChild(stopElement);

                if (i < journeyData.stops.length - 2) {
                    const line = document.createElement('div');
                    line.className = 'line';
                    stopPointsContainer.appendChild(line);
                }
            } else if (i === journeyData.currentStopIndex + 1) {
                const stopElement = document.createElement('div');
                stopElement.className = 'location';
                
                let detailHtmlNext = '';
                if (stop.outlets && stop.outlets.length > 0) {
                    if (stop.detail) detailHtmlNext += `<strong>${stop.detail}</strong>`;
                    detailHtmlNext += '<div style="font-size:13px; color:#0095FF; margin-top:6px;">';
                    stop.outlets.forEach(o => {
                        detailHtmlNext += `<div style="margin-bottom:6px;"><strong>${o.nama_outlet}</strong>`;
                        if (o.alamat) detailHtmlNext += `<div style="font-size:11px; color:#0095FF;">${o.alamat}</div>`;
                        detailHtmlNext += `</div>`;
                    });
                    detailHtmlNext += '</div>';
                } else {
                    detailHtmlNext = stop.detail || '';
                }

                stopElement.innerHTML = `
                    <div class="loc-title">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span style="color: #0095FF;">${stop.name}</span>
                    </div>
                    <div class="loc-sub" style="color: #0095FF;">${detailHtmlNext} (Titik Selanjutnya)</div>
                `;
                stopPointsContainer.appendChild(stopElement);

                if (i < journeyData.stops.length - 2) {
                    const line = document.createElement('div');
                    line.className = 'line';
                    stopPointsContainer.appendChild(line);
                }
            }
        }
    }

    // ★★★ FUNGSI UNTUK MEMULAI PERJALANAN ★★★
    function mulaiPerjalanan() {
        if (!currentTripId) {
            showToast('Error', 'Tidak ada perjalanan yang dipilih!', 'error');
            return;
        }

        const startBtn = document.getElementById('mulaiPerjalananBtn');
        const updateBtn = document.getElementById('updateLokasiBtn');
        const statusElement = document.getElementById(`status-${currentTripId}`);

        // Optimistic UI update
        journeyStarted[currentTripId] = true;
        if (startBtn) startBtn.classList.add('hidden');
        if (updateBtn) updateBtn.classList.remove('hidden');
        if (statusElement) {
            statusElement.innerHTML = '<i class="fas fa-play-circle"></i> Dalam Perjalanan';
            statusElement.className = 'status status-dalam-perjalanan';
        }

        // Update trip status in tripsData
        const tripIndex = tripsData.findIndex(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));
        if (tripIndex !== -1) {
            tripsData[tripIndex].status = 'aktif';
        }

        // Prepare payload
        const payload = {
            id_jadwal_driver: parseInt(currentTripId),
            total_stops: journeyData && journeyData.stops ? journeyData.stops.length : 0
        };

        // Get CSRF token
        let csrfToken = null;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) csrfToken = csrfMeta.getAttribute('content');
        if (!csrfToken) {
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) csrfToken = csrfInput.value;
        }

        if (!csrfToken) {
            console.error('CSRF token not found');
            showToast('Error', 'Terjadi kesalahan keamanan. Silakan refresh halaman.', 'error');
            journeyStarted[currentTripId] = false;
            if (startBtn) startBtn.classList.remove('hidden');
            if (updateBtn) updateBtn.classList.add('hidden');
            if (statusElement) {
                statusElement.innerHTML = '<i class="fas fa-hourglass-half"></i> Akan Berangkat';
                statusElement.className = 'status';
            }
            return;
        }

        fetch('{{ route("api.driver.journey.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(result => {
            if (result && result.success) {
                console.log('✅ Journey started persisted:', result.data);
                showToast('Sukses!', 'Perjalanan dimulai! Anda sekarang bisa mengupdate lokasi.', 'success');

                // Sinkronkan state
                fetchJourneyState(currentTripId).then(state => {
                    if (state) {
                        journeyStarted[currentTripId] = true;
                        const idx = state.current_stop_index ?? 0;
                        journeyData.currentStopIndex = parseInt(idx) || 0;
                        updateJourneyDisplay();
                    } else {
                        updateJourneyDisplay();
                    }
                });
            } else {
                console.error('Failed to persist journey start', result);
                showToast('Gagal', 'Gagal memulai perjalanan di server. Coba lagi.', 'error');
                journeyStarted[currentTripId] = false;
                if (startBtn) startBtn.classList.remove('hidden');
                if (updateBtn) updateBtn.classList.add('hidden');
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-hourglass-half"></i> Akan Berangkat';
                    statusElement.className = 'status';
                }
            }
        })
        .catch(err => {
            console.error('Error starting journey:', err);
            showToast('Error', 'Terjadi kesalahan saat memulai perjalanan.', 'error');
            journeyStarted[currentTripId] = false;
            if (startBtn) startBtn.classList.remove('hidden');
            if (updateBtn) updateBtn.classList.add('hidden');
            if (statusElement) {
                statusElement.innerHTML = '<i class="fas fa-hourglass-half"></i> Akan Berangkat';
                statusElement.className = 'status';
            }
        });
    }

    // Fungsi untuk menampilkan modal update lokasi
    function showUpdateLokasiModal() {
        if (!journeyStarted[currentTripId]) {
            showToast('Info', 'Anda harus memulai perjalanan terlebih dahulu!', 'warning');
            return;
        }

        if (journeyData.currentStopIndex >= journeyData.stops.length - 1) {
            showToast('Info', 'Anda sudah sampai di tujuan akhir!', 'info');
            return;
        }

        const nextStop = journeyData.stops[journeyData.currentStopIndex + 1];
        document.getElementById('modalNextLocation').textContent = nextStop.name;

        const outletsInfo = document.getElementById('modalOutletsInfo');
        const outletsList = document.getElementById('modalOutletsList');

        if (nextStop.is_outlet && nextStop.outlet_detail) {
            outletsInfo.style.display = 'block';
            outletsList.innerHTML = '';

            const outletItem = document.createElement('div');
            outletItem.style.cssText = 'margin-bottom: 10px; padding: 12px; background: white; border-radius: 4px; border-left: 4px solid #22c55e;';
            outletItem.innerHTML = `
                <strong style="color: #22c55e;">📍 ${nextStop.outlet_detail.nama_outlet}</strong><br>
                <small style="color: #666; display: block; margin-top: 5px;">
                    ${nextStop.outlet_detail.alamat || 'Alamat tidak tersedia'}
                </small>
            `;
            outletsList.appendChild(outletItem);
        } else {
            outletsInfo.style.display = 'none';
        }

        const modal = document.getElementById('updateLokasiModal');
        modal.style.display = 'flex';
    }

    function hideUpdateLokasiModal() {
        const modal = document.getElementById('updateLokasiModal');
        modal.style.display = 'none';
    }

    function confirmUpdateLokasi() {
        if (!journeyStarted[currentTripId]) {
            showToast('Info', 'Anda harus memulai perjalanan terlebih dahulu!', 'warning');
            hideUpdateLokasiModal();
            return;
        }

        if (!currentTripId) {
            showToast('Error', 'Tidak ada perjalanan aktif yang dipilih.', 'error');
            return;
        }

        const nextIndex = journeyData.currentStopIndex + 1;
        if (nextIndex >= journeyData.stops.length) {
            showToast('Info', 'Sudah mencapai tujuan akhir.', 'info');
            hideUpdateLokasiModal();
            return;
        }

        const nextStop = journeyData.stops[nextIndex];
        const status = nextStop.type === 'finish' ? 'completed' : 'arrived';

        const payload = {
            id_jadwal_driver: parseInt(currentTripId),
            location_name: nextStop.name,
            location_detail: nextStop.detail || '',
            latitude: null,
            longitude: null,
            stop_index: nextIndex,
            status: status,
            outlet_id: nextStop.outlet_id || null,
            kota: nextStop.kota || null,
            branch_id: nextStop.branch_id || null
        };

        let csrfToken = null;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) csrfToken = csrfMeta.getAttribute('content');
        if (!csrfToken) {
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) csrfToken = csrfInput.value;
        }

        if (!csrfToken) {
            showToast('Error', 'Terjadi kesalahan keamanan. Silakan refresh halaman.', 'error');
            hideUpdateLokasiModal();
            return;
        }

        fetch('{{ route("api.driver.location.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        }).then(r => r.json())
        .then(result => {
            if (result && result.success) {
                journeyData.currentStopIndex = nextIndex;
                
                // Update trip status if completed
                if (status === 'completed' || nextStop.type === 'finish') {
                    const tripIndex = tripsData.findIndex(t => parseInt(t.id_jadwal_driver) === parseInt(currentTripId));
                    if (tripIndex !== -1) {
                        tripsData[tripIndex].status = 'aktif'; // Tetap aktif sampai user klik selesaikan
                    }
                }
                
                updateJourneyDisplay();

                if (currentTripId) {
                    const statusElement = document.getElementById(`status-${currentTripId}`);
                    if (statusElement) {
                        if (status === 'completed' || nextStop.type === 'finish') {
                            statusElement.innerHTML = '<i class="fas fa-flag-checkered"></i> Sampai Tujuan';
                            statusElement.className = 'status-dalam-perjalanan';
                        } else {
                            statusElement.innerHTML = '<i class="fas fa-play-circle"></i> Dalam Perjalanan';
                            statusElement.className = 'status-dalam-perjalanan';
                        }
                    }
                }

                hideUpdateLokasiModal();
                
                if (status === 'completed' || nextStop.type === 'finish') {
                    showToast('Selamat!', `Anda telah sampai di tujuan akhir: ${nextStop.name}. Silakan klik "Selesaikan Perjalanan" untuk mengakhiri.`, 'success', 5000);
                } else {
                    showToast('Berhasil!', `Lokasi diupdate ke: ${nextStop.name}`, 'success');
                }

            } else {
                showToast('Gagal', 'Gagal mengupdate lokasi. Silakan coba lagi.', 'error');
            }
        }).catch(err => {
            console.error(err);
            showToast('Error', 'Terjadi kesalahan saat mengirim data lokasi.', 'error');
        });
    }

    function selesaikanPerjalanan() {
        // CEK APAKAH SUDAH DI STOP TERAKHIR
        if (journeyData.currentStopIndex === journeyData.stops.length - 1) {
            console.log('🎉 Selesaikan perjalanan ID:', currentTripId);

            let csrfToken = null;
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) csrfToken = csrfMeta.getAttribute('content');
            if (!csrfToken) {
                const csrfInput = document.querySelector('input[name="_token"]');
                if (csrfInput) csrfToken = csrfInput.value;
            }

            if (!csrfToken) {
                showToast('Error', 'Terjadi kesalahan keamanan. Silakan refresh halaman.', 'error');
                hideConfirmSelesaiModal();
                return;
            }

            fetch('{{ route("api.driver.trip.complete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_jadwal_driver: parseInt(currentTripId)
                })
            })
            .then(r => r.json())
            .then(result => {
                if (result && result.success) {
                    hideConfirmSelesaiModal();
                    showToast('Sukses!', 'Perjalanan berhasil diselesaikan!', 'success');

                    document.getElementById('tripIcon').style.color = "#2e7d32";
                    document.getElementById('tripIcon').className = "fa-solid fa-circle-check";
                    document.getElementById('tripTitle').textContent = "Perjalanan Selesai";

                    if (currentTripId) {
                        const statusElement = document.getElementById(`status-${currentTripId}`);
                        if (statusElement) {
                            statusElement.innerHTML = '<i class="fas fa-check-circle"></i> Selesai';
                            statusElement.className = "status-selesai";
                        }

                        const tripIndex = tripsData.findIndex(t => String(t.id_jadwal_driver) === String(currentTripId));
                        if (tripIndex !== -1) {
                            tripsData[tripIndex].status = 'selesai';
                        }

                        const tripData = tripsData.find(t => String(t.id_jadwal_driver) === String(currentTripId));
                        if (tripData && !completedTrips.find(t => String(t.id_jadwal_driver) === String(currentTripId))) {
                            completedTrips.push({
                                ...tripData,
                                status: 'selesai',
                                tanggal: tripData.date || new Date().toISOString().split('T')[0]
                            });
                        }

                        renderCompletedTripsHistory();
                        renderTripList();
                    }

                    // Update tampilan
                    updateJourneyDisplay();

                    setTimeout(() => {
                        showToast('Info', 'Mengalihkan ke halaman daftar...', 'info');
                        backToDaftarPerjalanan();
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }, 2000);
                } else {
                    showToast('Gagal', 'Gagal menyelesaikan perjalanan. Silakan coba lagi.', 'error');
                    hideConfirmSelesaiModal();
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
                hideConfirmSelesaiModal();
            });
        } else {
            showToast('Info', 'Anda belum mencapai tujuan akhir. Lanjutkan update lokasi hingga tujuan.', 'info');
            hideConfirmSelesaiModal();
        }
    }

    // Fungsi untuk mengambil detail trip
    function fetchTripDetail(tripId) {
        return new Promise((resolve, reject) => {
            if (!tripId) return resolve(null);

            const url = '{{ route("api.driver.trip.detail", ["tripId" => "__TRIPID__"]) }}'.replace('__TRIPID__', tripId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(r => r.json())
            .then(result => {
                if (result && result.success && result.data) {
                    console.log('✅ Trip detail berhasil di-fetch dari API:', {
                        stop_points: result.data.stop_points ? result.data.stop_points.length : 0,
                        passengers: result.data.passengers ? result.data.passengers.length : 0
                    });
                    resolve(result.data);
                } else {
                    resolve(null);
                }
            }).catch(err => {
                console.error('Error fetching trip detail:', err);
                resolve(null);
            });
        });
    }

    function fetchJourneyState(tripId) {
        return new Promise((resolve, reject) => {
            if (!tripId) return resolve(null);

            let url = '{{ route("api.driver.journey.state", ["tripId" => "__TRIPID__"]) }}'.replace('__TRIPID__', tripId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(r => r.json())
            .then(result => {
                if (result && result.success) {
                    resolve(result.data);
                } else {
                    resolve(null);
                }
            }).catch(err => {
                console.error('Error fetching journey state:', err);
                resolve(null);
            });
        });
    }

    function showDetailPerjalanan(tripData) {
        document.getElementById('daftarPerjalananPage').classList.add('hidden');
        document.getElementById('detailPerjalananPage').classList.remove('hidden');

        currentTripId = tripData.id;
        document.getElementById('tripTitle').textContent = `Perjalanan #${tripData.id} - ${tripData.from} → ${tripData.to}`;
        document.getElementById('passengerCount').textContent = tripData.seats;

        fetchTripDetail(tripData.id).then(fullApiData => {
            let tripDetailData = fullApiData || tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(tripData.id)) || tripData;

            if (!tripDetailData) {
                tripDetailData = tripData;
            }

            const passengerCountEl = document.getElementById('passengerCount');
            if (passengerCountEl && tripDetailData) {
                if (tripDetailData.occupied_seats !== undefined && tripDetailData.total_seats !== undefined) {
                    passengerCountEl.textContent = `${tripDetailData.occupied_seats}/${tripDetailData.total_seats}`;
                }
            }

            const travelEl = document.getElementById('travelTime');
            if (travelEl && tripDetailData) {
                let travelText = '-';
                if (tripDetailData.time && tripDetailData.eta) {
                    travelText = `${tripDetailData.time} - ${tripDetailData.eta}`;
                    if (tripDetailData.estimated_duration && tripDetailData.estimated_duration !== '-') {
                        travelText += ` (${tripDetailData.estimated_duration})`;
                    }
                } else if (tripDetailData.estimated_duration && tripDetailData.estimated_duration !== '-') {
                    travelText = tripDetailData.estimated_duration;
                }
                travelEl.textContent = travelText;
            }

            const distEl = document.getElementById('distance');
            if (distEl && tripDetailData && tripDetailData.distance) {
                const distanceText = (typeof tripDetailData.distance === 'number') ? `${tripDetailData.distance} km` : tripDetailData.distance;
                distEl.textContent = distanceText;
            }

            buildJourneyDataFromStopPoints({
                from: tripDetailData.from || tripData.from,
                to: tripDetailData.to || tripData.to,
                stop_points: tripDetailData.stop_points || [],
                id_jadwal_driver: tripData.id
            });

            journeyData.currentStopIndex = 0;
            journeyStarted[tripData.id] = false;

            // Fetch journey state dari server
            fetchJourneyState(tripData.id).then(state => {
                if (state) {
                    const status = state.status || (state.data && state.data.status) || 'not_started';
                    const idx = state.current_stop_index ?? state.data?.current_stop_index ?? 0;
                    
                    // Set journey started based on status
                    journeyStarted[tripData.id] = (status === 'in_progress' || status === 'aktif');
                    
                    // Set current stop index
                    journeyData.currentStopIndex = parseInt(idx) || 0;
                    
                    console.log(`📊 Journey state for trip ${tripData.id}: status=${status}, index=${journeyData.currentStopIndex}, started=${journeyStarted[tripData.id]}`);
                    
                    updateJourneyDisplay();
                } else {
                    // Cek dari tripsData
                    const tripFromData = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(tripData.id));
                    if (tripFromData && tripFromData.status === 'aktif') {
                        journeyStarted[tripData.id] = true;
                    } else if (tripFromData && tripFromData.status === 'selesai') {
                        journeyData.currentStopIndex = journeyData.stops.length - 1;
                        journeyStarted[tripData.id] = true;
                    }
                    updateJourneyDisplay();
                }
            }).catch(() => {
                updateJourneyDisplay();
            });

            generatePenumpangList(parseInt(tripData.id));

            window.scrollTo(0, 0);
        }).catch(err => {
            console.error('Error di showDetailPerjalanan:', err);
            window.scrollTo(0, 0);
        });
    }

    function backToDaftarPerjalanan() {
        if (passengerRefreshInterval) {
            clearInterval(passengerRefreshInterval);
            passengerRefreshInterval = null;
        }

        document.getElementById('detailPerjalananPage').classList.add('hidden');
        document.getElementById('daftarPerjalananPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    function renderCompletedTripsHistory() {
        const container = document.getElementById('historyItemsContainer');
        if (!container) return;

        container.innerHTML = '';

        if (!completedTrips || completedTrips.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-history"></i><p>Belum ada riwayat perjalanan</p></div>';
            return;
        }

        const finishedTrips = completedTrips.filter(trip => trip.status === 'selesai' || trip.status === 'completed');

        if (finishedTrips.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-history"></i><p>Belum ada riwayat perjalanan yang selesai</p></div>';
            return;
        }

        finishedTrips.forEach(trip => {
            const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
            const formattedDate = tripDate ? tripDate.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'N/A';

            const time = trip.waktu_keberangkatan || trip.time || '-';
            const from = trip.asal || trip.from || trip.jadwal?.asal || trip.masterRute?.kota_asal || '-';
            const to = trip.tujuan || trip.to || trip.jadwal?.tujuan || trip.masterRute?.kota_tujuan || '-';

            let passengerCount = 0;
            if (trip.bookings && Array.isArray(trip.bookings)) {
                trip.bookings.forEach(booking => {
                    const details = booking.detail_penumpang || booking.detailPenumpang || [];
                    if (Array.isArray(details)) {
                        passengerCount += details.length;
                    } else if (typeof details === 'object') {
                        passengerCount += Object.keys(details).length;
                    }
                });
            }
            if (passengerCount === 0 && trip.occupied_seats) {
                passengerCount = trip.occupied_seats;
            }

            const displayRoute = trip.route_name || (trip.rute && trip.rute.nama_rute) || (trip.jadwal && trip.jadwal.rutes && trip.jadwal.rutes[0] && trip.jadwal.rutes[0].nama_rute) || `${from} → ${to}`;

            const item = document.createElement('div');
            item.className = 'history-item';
            item.innerHTML = `
                <div class="history-route">
                    <i class="fas fa-route"></i> ${displayRoute}
                </div>
                <div class="history-date">
                    <i class="fas fa-calendar"></i> ${formattedDate} | <i class="fas fa-clock"></i> ${time}
                </div>
                <div class="history-footer">
                    <div class="passenger-count">
                        <i class="fas fa-users"></i> ${passengerCount} penumpang
                    </div>
                    <span class="status-completed">
                        <i class="fas fa-check-circle"></i> Selesai
                    </span>
                </div>
            `;

            container.appendChild(item);
        });
    }

    function filterCompletedTripsHistory(filterValue) {
        const container = document.getElementById('historyItemsContainer');
        if (!container) return;

        container.innerHTML = '';

        if (!completedTrips || completedTrips.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-history"></i><p>Belum ada riwayat perjalanan</p></div>';
            return;
        }

        let filteredTrips = completedTrips.filter(trip => trip.status === 'selesai' || trip.status === 'completed');

        if (filterValue && filterValue !== 'Semua') {
            const now = new Date();
            filteredTrips = filteredTrips.filter(trip => {
                const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
                if (!tripDate) return false;

                switch (filterValue) {
                    case 'Minggu ini':
                        const weekStart = new Date(now);
                        weekStart.setDate(now.getDate() - now.getDay());
                        return tripDate >= weekStart && tripDate <= now;
                    case 'Bulan ini':
                        return tripDate.getMonth() === now.getMonth() && tripDate.getFullYear() === now.getFullYear();
                    case '3 bulan terakhir':
                        const threeMonthsAgo = new Date(now);
                        threeMonthsAgo.setMonth(now.getMonth() - 3);
                        return tripDate >= threeMonthsAgo && tripDate <= now;
                    default:
                        return true;
                }
            });
        }

        if (filteredTrips.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-history"></i><p>Tidak ada riwayat perjalanan dalam periode yang dipilih</p></div>';
            return;
        }

        filteredTrips.forEach(trip => {
            const tripDate = trip.tanggal ? new Date(trip.tanggal) : null;
            const formattedDate = tripDate ? tripDate.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'N/A';

            const time = trip.waktu_keberangkatan || trip.time || '-';
            const from = trip.asal || trip.from || trip.jadwal?.asal || trip.masterRute?.kota_asal || '-';
            const to = trip.tujuan || trip.to || trip.jadwal?.tujuan || trip.masterRute?.kota_tujuan || '-';

            let passengerCount = 0;
            if (trip.bookings && Array.isArray(trip.bookings)) {
                trip.bookings.forEach(booking => {
                    const details = booking.detail_penumpang || booking.detailPenumpang || [];
                    if (Array.isArray(details)) {
                        passengerCount += details.length;
                    } else if (typeof details === 'object') {
                        passengerCount += Object.keys(details).length;
                    }
                });
            }
            if (passengerCount === 0 && trip.occupied_seats) {
                passengerCount = trip.occupied_seats;
            }

            const displayRoute = trip.route_name || (trip.rute && trip.rute.nama_rute) || (trip.jadwal && trip.jadwal.rutes && trip.jadwal.rutes[0] && trip.jadwal.rutes[0].nama_rute) || `${from} → ${to}`;

            const item = document.createElement('div');
            item.className = 'history-item';
            item.innerHTML = `
                <div class="history-route">
                    <i class="fas fa-route"></i> ${displayRoute}
                </div>
                <div class="history-date">
                    <i class="fas fa-calendar"></i> ${formattedDate} | <i class="fas fa-clock"></i> ${time}
                </div>
                <div class="history-footer">
                    <div class="passenger-count">
                        <i class="fas fa-users"></i> ${passengerCount} penumpang
                    </div>
                    <span class="status-completed">
                        <i class="fas fa-check-circle"></i> Selesai
                    </span>
                </div>
            `;

            container.appendChild(item);
        });
    }

    let passengerRefreshInterval = null;
    let lastPassengerCount = 0;

    function displayInitialPassengers(tripId) {
        console.log(`%c🎯 displayInitialPassengers: Rendering awal untuk trip ${tripId}`, 'color: purple; font-weight: bold;');

        const renderPassengers = (passengersData, source = 'unknown') => {
            const penumpangListElement = document.getElementById('penumpangList');
            const totalPenumpangElement = document.getElementById('totalPenumpang');
            const passengerCountEl = document.getElementById('passengerCount');

            if (!penumpangListElement) {
                console.warn('❌ penumpangList element tidak ditemukan');
                return;
            }

            penumpangListElement.innerHTML = '';

            if (!passengersData || passengersData.length === 0) {
                penumpangListElement.innerHTML = '<div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada penumpang untuk perjalanan ini</p></div>';
                if (totalPenumpangElement) totalPenumpangElement.textContent = '0';
                lastPassengerCount = 0;
                return;
            }

            if (totalPenumpangElement) {
                totalPenumpangElement.textContent = passengersData.length;
            }

            lastPassengerCount = passengersData.length;

            passengersData.forEach((passenger, idx) => {
                let statusClass = 'status-terverifikasi';
                let statusText = 'Terverifikasi';

                switch((passenger.status || '').toLowerCase()) {
                    case 'refund':
                        statusClass = 'status-refund';
                        statusText = 'Refund';
                        break;
                    case 'terdaftar':
                        statusClass = 'status-terdaftar';
                        statusText = 'Terdaftar';
                        break;
                    case 'terverifikasi':
                    case 'verified':
                    default:
                        statusClass = 'status-terverifikasi';
                        statusText = 'Terverifikasi';
                }

                const penumpangItem = document.createElement('div');
                penumpangItem.className = 'penumpang-item';
                penumpangItem.innerHTML = `
                    <div class="penumpang-info">
                        <div class="penumpang-name">
                            <i class="fas fa-user-circle"></i> ${passenger.name || 'Penumpang'}
                        </div>
                        <div class="penumpang-phone">
                            <i class="fas fa-phone"></i> ${passenger.phone || '-'}
                        </div>
                    </div>
                    <div class="penumpang-seat">
                        <div class="seat-number">${passenger.seat || 'N/A'}</div>
                        <span class="seat-status ${statusClass}">${statusText}</span>
                    </div>
                `;

                penumpangListElement.appendChild(penumpangItem);
            });

            console.log(`✅ Daftar penumpang (dari ${source}): ${passengersData.length} penumpang`);
        };

        const tripData = tripsData.find(t => parseInt(t.id_jadwal_driver) === parseInt(tripId));

        if (tripData && tripData.passengers && tripData.passengers.length > 0) {
            renderPassengers(tripData.passengers, 'tripsData [API]');
        } else if (tripData && tripData.passengers) {
            renderPassengers([], 'tripsData [empty]');
        } else {
            const penumpangListElement = document.getElementById('penumpangList');
            if (penumpangListElement) {
                penumpangListElement.innerHTML = '<div class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Memuat data penumpang...</p></div>';
            }
        }
    }

    function generatePenumpangList(tripId) {
        displayInitialPassengers(tripId);
    }

    function setupDetailButtonListener() {
        try {
            console.log('%c⚙️ Setup Event Listener untuk Button Detail', 'color: purple; font-weight: bold; font-size: 12px;');

            document.addEventListener('click', function(e) {
                const button = e.target.closest('button.btn-detail');

                if (button && button.closest('.trip-item')) {
                    e.preventDefault();
                    e.stopPropagation();

                    try {
                        const tripItem = button.closest('.trip-item');

                        if (tripItem) {
                            const tripId = tripItem.getAttribute('data-trip-id');

                            if (!tripId) {
                                console.error('❌ data-trip-id tidak ditemukan!');
                                return;
                            }

                            const tripDataToShow = {
                                id: tripId,
                                from: tripItem.getAttribute('data-from') || 'N/A',
                                to: tripItem.getAttribute('data-to') || 'N/A',
                                time: tripItem.getAttribute('data-time') || 'N/A',
                                eta: tripItem.getAttribute('data-eta') || 'N/A',
                                duration: tripItem.getAttribute('data-duration') || 'N/A',
                                distance: tripItem.getAttribute('data-distance') || 'N/A',
                                seats: tripItem.getAttribute('data-seats') || 'N/A',
                                passengers: tripItem.getAttribute('data-passengers') || '0'
                            };

                            showDetailPerjalanan(tripDataToShow);
                        }
                    } catch (err) {
                        console.error('❌ Error:', err);
                        showToast('Error', 'Terjadi kesalahan saat membuka detail', 'error');
                    }
                }
            }, true);

            console.log('%c✅ Event listener button detail ready', 'color: green; font-weight: bold;');
        } catch (error) {
            console.error('❌ Error di setupDetailButtonListener:', error);
        }
    }

    function renderTripList() {
        const container = document.getElementById('tripListContainer');
        if (!container) return;

        container.innerHTML = '';

        if (!tripsData || tripsData.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>Tidak ada perjalanan hari ini</p><p class="sub">Menunggu jadwal dari admin</p></div>';
            return;
        }

        const activeTrips = tripsData.filter(t => t.status !== 'selesai');
        const completedDisplayTrips = tripsData.filter(t => t.status === 'selesai');
        const sortedTrips = [...activeTrips, ...completedDisplayTrips];

        sortedTrips.forEach((t, idx) => {
            const tripId = t.id_jadwal_driver || '';
            const from = t.from || t.rute?.kota_asal || t.from || '-';
            const to = t.to || t.rute?.kota_tujuan || '-';
            const seats = (t.occupied_seats || 0) + '/' + (t.total_seats || 0);
            const passengers = (t.passengers || []).length || 0;
            const time = t.time || t.waktu_keberangkatan || '-';
            const eta = t.eta || t.waktu_kedatangan || '-';
            const distance = t.distance ? (typeof t.distance === 'number' ? `${t.distance} km` : t.distance) : '-';

            const statusText = t.status === 'selesai' ? 'Selesai' : (t.status || 'Akan Berangkat');
            const statusClass = t.status === 'selesai' ? 'status-selesai' : (t.status === 'aktif' ? 'status-dalam-perjalanan' : 'status');

            const travelTimeDisplay = time && eta ? `${time} - ${eta}` : time || '-';
            const durationDisplay = t.estimated_duration ? ` (${t.estimated_duration})` : '';

            const item = document.createElement('div');
            item.className = 'trip-item';
            item.setAttribute('data-trip-id', tripId);
            item.setAttribute('data-from', from);
            item.setAttribute('data-to', to);
            item.setAttribute('data-time', time);
            item.setAttribute('data-eta', eta);
            item.setAttribute('data-duration', t.estimated_duration || '-');
            item.setAttribute('data-distance', distance);
            item.setAttribute('data-seats', seats);
            item.setAttribute('data-passengers', passengers);
            item.setAttribute('data-status', t.status);
            item.setAttribute('data-date', t.date);
            item.setAttribute('data-trip', JSON.stringify(t));

            item.innerHTML = `
                <div class="trip-header">
                    <div class="trip-number">
                        <i class="fas fa-tag"></i> ${idx + 1}
                    </div>
                    <div class="seat-info">
                        <i class="fas fa-users"></i> ${seats}
                    </div>
                </div>

                <div class="trip-route">
                    <i class="fas fa-arrow-right"></i> ${from} → ${to}
                </div>

                <div class="trip-time">
                    <i class="fas fa-clock"></i> ${travelTimeDisplay}${durationDisplay}
                </div>

                <div class="trip-date">
                    <i class="fas fa-calendar-day"></i> ${t.date || '-'}
                </div>

                <div class="trip-footer">
                    <div class="status-badge">
                        <span class="${statusClass}" id="status-${tripId}">
                            <i class="fas ${t.status === 'selesai' ? 'fa-check-circle' : (t.status === 'aktif' ? 'fa-play-circle' : 'fa-hourglass-half')}"></i>
                            ${statusText}
                        </span>
                    </div>

                    <button type="button" class="btn-detail">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </button>
                </div>
            `;

            container.appendChild(item);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenu();
        renderCompletedTripsHistory();
        renderTripList();

        const historyFilter = document.getElementById('historyFilterSelect');
        if (historyFilter) {
            historyFilter.addEventListener('change', function() {
                filterCompletedTripsHistory(this.value);
            });
        }

        setTimeout(() => {
            setupDetailButtonListener();
        }, 100);

        document.addEventListener('click', function(e) {
            if (e.target?.id === 'mulaiPerjalananBtn' || e.target?.closest('#mulaiPerjalananBtn')) {
                e.preventDefault();
                mulaiPerjalanan();
            }

            if (e.target?.id === 'updateLokasiBtn' || e.target?.closest('#updateLokasiBtn')) {
                e.preventDefault();
                showUpdateLokasiModal();
            }

            if (e.target?.id === 'cancelUpdateBtn' || e.target?.closest('#cancelUpdateBtn')) {
                e.preventDefault();
                hideUpdateLokasiModal();
            }

            if (e.target?.id === 'confirmUpdateBtn' || e.target?.closest('#confirmUpdateBtn')) {
                e.preventDefault();
                confirmUpdateLokasi();
            }

            if (e.target?.id === 'selesaiPerjalananBtn' || e.target?.closest('#selesaiPerjalananBtn')) {
                e.preventDefault();
                showConfirmSelesaiModal();
            }

            if (e.target?.id === 'backButton' || e.target?.closest('#backButton')) {
                e.preventDefault();
                backToDaftarPerjalanan();
            }
            
            if (e.target?.id === 'confirmCancelBtn' || e.target?.closest('#confirmCancelBtn')) {
                e.preventDefault();
                hideConfirmSelesaiModal();
            }
            
            if (e.target?.id === 'confirmYesBtn' || e.target?.closest('#confirmYesBtn')) {
                e.preventDefault();
                selesaikanPerjalanan();
            }
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const confirmModal = document.getElementById('confirmSelesaiModal');
            if (e.target === confirmModal) {
                hideConfirmSelesaiModal();
            }
            
            const updateModal = document.getElementById('updateLokasiModal');
            if (e.target === updateModal) {
                hideUpdateLokasiModal();
            }
        });
    });
</script>
@endpush