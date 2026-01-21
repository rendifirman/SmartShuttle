<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Transaksi - Smart Shuttle</title>
    @extends('layouts.app-admin')
    
    <!-- Styles -->
    <style>
        /* ====== VARIABLES ====== */
        :root {
            --primary-color: #ff6a21;
            --primary-light: rgba(255, 106, 33, 0.1);
            --primary-dark: #e55d00;
            --secondary-color: #0d3559;
            --secondary-light: #f8f8f6;
            --text-dark: #374151;
            --text-medium: #6b7280;
            --text-light: #9ca3af;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --success-light: rgba(16, 185, 129, 0.1);
            --warning-color: #f59e0b;
            --warning-light: rgba(245, 158, 11, 0.1);
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 6px 16px rgba(0,0,0,0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s ease;
        }

        /* ====== RESET & BASE ====== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            background: #f6f7fb;
            color: var(--text-dark);
        }

        .main-container {
            padding: 32px;
            min-height: 100vh;
        }

        /* ====== PAGE HEADER ====== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title h4 {
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0 0 4px 0;
        }

        .page-title p {
            font-size: 14px;
            color: var(--text-medium);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* ====== TABS SYSTEM ====== */
        .tab-container {
            margin-bottom: 32px;
            display: none; /* Default: hidden */
        }

        .tabs {
            display: flex;
            gap: 8px;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 32px;
        }

        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-medium);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .tab-btn:hover {
            color: var(--text-dark);
        }

        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ====== DAFTAR TRANSAKSI PAGE ====== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .summary-card h3 {
            margin: 0 0 6px;
            font-size: 20px;
            color: var(--secondary-color);
        }

        .summary-card p {
            margin: 0;
            font-size: 13px;
        }

        .up { color: var(--success-color); }
        .down { color: #e53935; }

        /* ====== FILTER SECTION ====== */
        .filter-box {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 12px;
        }

        .filter-row input,
        .filter-row select {
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            font-size: 14px;
            background: var(--white);
            transition: var(--transition);
        }

        .filter-row input:focus,
        .filter-row select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 106, 33, 0.1);
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
        }

        .btn-filter {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 10px 28px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-filter:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* ====== TABLE SECTION ====== */
        .table-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            justify-content: space-between;
        }

        .table-actions-left {
            display: flex;
            gap: 10px;
        }

        .table-actions-right {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--white);
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-excel {
            background: #19b44a;
            color: var(--white);
            border: none;
        }

        .btn-pdf {
            background: var(--text-light);
            color: var(--white);
            border: none;
        }

        .btn-info {
            background: #4da3ff;
            color: var(--white);
            border: none;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            margin-right: 4px;
        }

        .btn-edit {
            background: var(--text-medium);
            color: var(--white);
            border: none;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
        }

        .table-box {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background: var(--secondary-light);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr:hover {
            background: var(--secondary-light);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* ====== PAGINATION ====== */
        .pagination {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            justify-content: center;
            align-items: center;
        }

        .page {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            cursor: pointer;
            background: var(--white);
            transition: var(--transition);
        }

        .page:hover {
            background: var(--secondary-light);
        }

        .page.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        /* ====== MODAL STYLES ====== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            width: 680px;
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: modalFade .25s ease;
        }

        @keyframes modalFade {
            from { transform: scale(.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 24px 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--secondary-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-medium);
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .modal-close:hover {
            background: var(--secondary-light);
            color: var(--text-dark);
        }

        .modal-divider {
            height: 1px;
            background: var(--border-color);
            margin: 16px 28px 0;
        }

        .modal-body {
            padding: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px 40px;
        }

        .label {
            font-size: 14px;
            color: var(--text-medium);
            margin-bottom: 4px;
        }

        .value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .modal-footer {
            background: var(--secondary-light);
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
        }

        .footer-item {
            font-size: 14px;
            color: var(--text-medium);
        }

        .footer-item strong {
            display: block;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 4px;
        }

        /* ====== PESAN TIKET BARU ====== */
        .card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 28px 32px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card h4, .card h5, .card h6 {
            margin: 0 0 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-color);
            font-weight: 700;
            color: var(--secondary-color);
        }

        .card h4 { font-size: 16px; }
        .card h5 { font-size: 15px; }
        .card h6 { font-size: 14px; }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 20px;
        }

        .form-row.two-column {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-dark);
            background: var(--white);
            transition: var(--transition);
            width: 100%;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 106, 33, 0.1);
        }

        .form-group.full-width {
            grid-column: span 3;
        }

        /* ====== ARMADA SECTION ====== */
        .armada-container {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            margin: 20px 0;
            background: var(--white);
            transition: var(--transition);
        }

        .armada-container:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-lg);
        }

        .armada-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 24px;
        }

        .armada-left {
            flex: 1;
            min-width: 300px;
        }

        .armada-right {
            min-width: 220px;
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 16px;
        }

        .armada-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 16px;
        }

        .armada-route {
            display: flex;
            align-items: center;
            gap: 100px;
            margin-bottom: 20px;
            background: var(--secondary-light);
            padding: 16px;
            border-radius: var(--radius-sm);
        }

        .route-time {
            font-weight: 700;
            font-size: 25px;
            color: var(--secondary-color);
            line-height: 1;
        }

        .route-city {
            font-size: 13px;
            color: var(--text-medium);
            margin-top: 4px;
        }

        .route-arrow {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
        }

        .route-duration {
            text-align: center;
            color: var(--text-light);
            font-size: 12px;
        }

        .armada-badges {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .badge-primary {
            background: var(--primary-light);
            color: var(--primary-color);
            border: 1px solid rgba(255, 106, 33, 0.2);
        }

        .badge-light {
            background: #f9fafb;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .armada-status {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-available {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-count {
            font-size: 13px;
            color: var(--text-medium);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .armada-facilities {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .facility-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: #f9fafb;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .price-section {
            margin-bottom: 16px;
            text-align: right;
        }

        .price-amount {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-top: 30px;
        }

        .price-unit {
            font-size: 14px;
            color: var(--text-light);
            margin-left: 4px;
            font-weight: 500;
        }

        .price-total {
            font-size: 13px;
            color: var(--text-medium);
            margin-top: 4px;
        }

        /* ====== COMBO DROPDOWN ====== */
        .combo-dropdown {
            position: relative;
        }

        .combo-input {
            width: 100%;
            padding-right: 40px !important;
        }

        .combo-dropdown-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-medium);
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .combo-dropdown-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            margin-top: 4px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .combo-search-input {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .combo-search-input input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 13px;
        }

        .combo-options {
            padding: 8px 0;
        }

        .combo-option {
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .combo-option:hover {
            background: var(--secondary-light);
        }

        .combo-option.selected {
            background: var(--primary-light);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* ====== ACCORDION ====== */
        .accordion-section {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 0;
            margin-bottom: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            overflow: hidden;
        }

        .accordion-section.expanded {
            box-shadow: var(--shadow-lg);
        }

        .accordion-header {
            padding: 20px 24px;
            cursor: pointer;
            transition: var(--transition);
            background: var(--white);
            position: relative;
        }

        .accordion-header:hover {
            background: var(--secondary-light);
        }

        .accordion-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            position: relative;
            width: 100%;
        }

        .accordion-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
        }

        .accordion-icon {
            font-size: 18px;
            color: var(--text-medium);
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .accordion-icon.rotated {
            transform: rotate(90deg);
        }

        .accordion-content {
            padding: 0 24px 24px 24px;
            display: none;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ====== SEAT SELECTION ====== */
        .seat-info {
            background: rgba(219, 234, 254, 0.3);
            padding: 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-size: 14px;
            color: var(--text-dark);
            border-left: 4px solid var(--primary-color);
        }

        .seat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            max-width: 400px;
            margin: 0 auto;
        }

        .seat {
            padding: 16px;
            text-align: center;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .seat.available {
            background: var(--white);
            border-color: var(--border-color);
            color: var(--text-dark);
        }

        .seat.available:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .seat.selected {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .seat.sold {
            background: #f3f4f6;
            color: var(--text-light);
            cursor: not-allowed;
            border-color: var(--border-color);
        }

        .seat-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-medium);
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        .legend-available { background: var(--white); border: 2px solid var(--border-color); }
        .legend-selected { background: var(--primary-color); }
        .legend-sold { background: #f3f4f6; }

        /* ====== PAYMENT SECTION ====== */
        .pilih-kursi-wrapper {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .pesan-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
        }

        .box {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 20px;
            font-size: 14px;
            color: var(--text-dark);
            background: var(--white);
        }

        .route-title {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .route-date {
            font-size: 13px;
            color: var(--text-medium);
            margin: 6px 0 20px;
        }

        .section-title {
            font-weight: 600;
            margin: 16px 0 10px;
            font-size: 14px;
            color: var(--secondary-color);
        }

        .dashed {
            border-top: 1px dashed var(--border-color);
            margin: 16px 0;
        }

        .kursi {
            background: var(--primary-color);
            color: var(--white);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            text-align: center;
            display: inline-block;
            min-width: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 13px;
        }

        .total-row strong {
            font-weight: 600;
        }

        .pay-label {
            font-size: 13px;
            color: var(--text-medium);
            margin-bottom: 8px;
            display: block;
        }

        .pay-input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-bottom: 12px;
            background: var(--white);
            color: var(--text-dark);
            transition: var(--transition);
        }

        .pay-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .btn-pay {
            width: 100%;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 14px;
            border-radius: var(--radius-sm);
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            margin-top: 8px;
        }

        .btn-pay:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .armada-section-title {
            margin: 32px 0 24px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .armada-hr {
            margin: 32px 0;
            border: none;
            border-top: 1px solid var(--border-color);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 1200px) {
            .armada-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .armada-left,
            .armada-right {
                width: 100%;
                text-align: left;
            }
            
            .armada-right {
                align-items: flex-start;
            }
            
            .price-section {
                text-align: left;
            }
        }

        @media (max-width: 1024px) {
            .main-container {
                padding: 20px;
            }
            
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .search-row {
                grid-template-columns: 1fr;
            }
            
            .form-row,
            .form-row.two-column {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .pesan-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .table-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px 6px;
            }
            
            .modal-box {
                width: 95%;
                margin: 10px;
            }
            
            .modal-body {
                grid-template-columns: 1fr;
            }
            
            .modal-footer {
                flex-direction: column;
                gap: 12px;
            }
            
            .armada-route {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .route-arrow {
                transform: rotate(90deg);
                margin: 8px 0;
            }
            
            .route-duration {
                align-self: flex-start;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .seat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .tabs {
                flex-direction: column;
            }
            
            .tab-btn {
                width: 100%;
                text-align: center;
            }
            
            .armada-badges {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .armada-facilities {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .accordion-title {
                font-size: 14px;
            }
            
            .accordion-content {
                padding: 0 16px 16px 16px;
            }
            
            .pilih-kursi-wrapper {
                padding: 16px;
            }
        }
    </style>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @section('content')
    <div class="main-container">
        <!-- Tabs Navigation -->
        <div class="tab-container" id="tabsNavigation">
            <div class="tabs">
                <button class="tab-btn active" data-tab="daftar">
                    <i class="fas fa-list"></i> Daftar Transaksi
                </button>
                <button class="tab-btn" data-tab="pesan">
                    <i class="fas fa-plus-circle"></i> Pesan Tiket Baru
                </button>
            </div>
        </div>

        <!-- TAB 1: Daftar Transaksi -->
        <div id="daftarTab" class="tab-content active">
            <div class="summary-grid">
                <div class="summary-card">
                    <h3>Rp. 2.500.000</h3>
                    <p>Pendapatan Hari Ini</p>
                    <p class="up">↑ 15% dari kemarin</p>
                </div>
                <div class="summary-card">
                    <h3>10</h3>
                    <p>Transaksi Hari Ini</p>
                    <p class="up">↑ 5% dari kemarin</p>
                </div>
                <div class="summary-card">
                    <h3>Rp 102.000</h3>
                    <p>Rata-rata Transaksi</p>
                    <p class="up">↑ 5% dari kemarin</p>
                </div>
                <div class="summary-card">
                    <h3>2</h3>
                    <p>Transaksi Gagal</p>
                    <p class="down">↓ 2% dari kemarin</p>
                </div>
            </div>

            <div class="filter-box">
                <div class="filter-row">
                    <select>
                        <option>Pilih rute</option>
                        <option>Jakarta - Bandung</option>
                        <option>Jakarta - Bali</option>
                        <option>Bandung - Surabaya</option>
                    </select>
                    <select>
                        <option>Pilih tanggal</option>
                        <option>Hari ini</option>
                        <option>Kemarin</option>
                        <option>Minggu ini</option>
                        <option>Bulan ini</option>
                    </select>
                    <select>
                        <option>Pilih Status</option>
                        <option>Sukses</option>
                        <option>Pending</option>
                        <option>Gagal</option>
                        <option>Dibatalkan</option>
                    </select>
                </div>

                <div class="search-row">
                    <input type="text" placeholder="Cari kode pembayaran, kode booking, nama pelanggan...">
                    <button class="btn-filter">Filter</button>
                </div>
            </div>

            <div class="table-actions">
                <div class="table-actions-left">
                     <button class="btn btn-primary" onclick="switchTab('pesan')">
                        <i class="fas fa-plus"></i> Pesan Tiket Baru
                    </button>
                </div>
            </div>

            <div class="table-box">
                 <button class="btn btn-excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                <table>
                    <thead>
                        <tr>
                            <th>Kode Pembayaran</th>
                            <th>Kode Booking</th>
                            <th>Nama Customer</th>
                            <th>Tanggal</th>
                            <th>Rute</th>
                            <th>Metode</th>
                            <th>Total Bayar</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PAY-260115-0003</td>
                            <td>SS202601359</td>
                            <td>Luna Ayna</td>
                            <td>01-01-2026</td>
                            <td>Jakarta - Bali</td>
                            <td>BCA Virtual Account</td>
                            <td>Rp. 350.000</td>
                            <td>sistem</td>
                            <td><span class="badge badge-success">Sukses</span></td>
                            <td>
                                <button class="btn btn-info" onclick="openDetail()">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>PAY-260115-0002</td>
                            <td>SS202601359</td>
                            <td>Jeno</td>
                            <td>15-12-2025</td>
                            <td>Bandung - Jakarta</td>
                            <td>QRIS</td>
                            <td>Rp. 150.000</td>
                            <td>admin</td>
                            <td><span class="badge badge-success">Sukses</span></td>
                            <td>
                                <button class="btn btn-info" onclick="openDetail()">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        <!-- More rows can be added here -->
                    </tbody>
                </table>

                <div class="pagination">
                    <div class="page">&lt;</div>
                    <div class="page active">1</div>
                    <div class="page">2</div>
                    <div class="page">3</div>
                    <div class="page">&gt;</div>
                </div>
            </div>

            <!-- Modal Detail Transaksi -->
            <div class="modal-overlay" id="detailModal">
                <div class="modal-box">
                    <div class="modal-header">
                        <h2>Detail Transaksi</h2>
                        <button class="modal-close" onclick="closeDetail()">&times;</button>
                    </div>
                    <div class="modal-divider"></div>

                    <div class="modal-body">
                        <div>
                            <div class="label">Kode Pembayaran</div>
                            <div class="value">PAY-260115-0003</div>
                        </div>
                        <div>
                            <div class="label">Kode Booking</div>
                            <div class="value">SS202601359</div>
                        </div>
                        <div>
                            <div class="label">Customer ID</div>
                            <div class="value">3</div>
                        </div>
                        <div>
                            <div class="label">Rute</div>
                            <div class="value">Jakarta → Bali</div>
                        </div>
                        <div>
                            <div class="label">Metode Pembayaran</div>
                            <div class="value">BCA Virtual Account</div>
                        </div>
                        <div>
                            <div class="label">Total Bayar</div>
                            <div class="value">Rp. 350.000</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="footer-item">
                            Created
                            <strong>Sistem | 15-01-2026 14:30</strong>
                        </div>
                        <div class="footer-item">
                            Updated
                            <strong>Admin | 15-01-2026 14:30</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: Pesan Tiket Baru -->
        <div id="pesanTab" class="tab-content">
            <div class="page-header">
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="switchTab('daftar')">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </button>
                </div>
            </div>

            <!-- Accordion: Pilih Rute & Armada -->
            <div class="accordion-section" id="accordion-rute">
                <div class="accordion-header" onclick="toggleAccordion('rute')">
                    <h6 class="accordion-title">
                        Pilih Rute & Armada
                        <span class="accordion-icon" id="icon-rute">›</span>
                    </h6>
                </div>
                <div class="accordion-content" id="content-rute">
                    <div class="card">
                        <!-- Filter Section -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tanggal Keberangkatan</label>
                                <div class="combo-dropdown">
                                    <input type="text" 
                                           class="form-control combo-input" 
                                           id="departure-date-input"
                                           placeholder="dd/mm/yy"
                                           value="15/12/2024"
                                           readonly>
                                    <button type="button" class="combo-dropdown-toggle">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <div class="combo-dropdown-results">
                                        <div class="combo-search-input">
                                            <input type="text" placeholder="Cari tanggal..." autocomplete="off">
                                        </div>
                                        <div class="combo-options">
                                            <div class="combo-option selected" data-value="15/12/2024">15 Desember 2024</div>
                                            <div class="combo-option" data-value="16/12/2024">16 Desember 2024</div>
                                            <div class="combo-option" data-value="17/12/2024">17 Desember 2024</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Waktu Keberangkatan</label>
                                <select class="form-control">
                                    <option>Pilih waktu</option>
                                    <option selected>Pagi (06:00 - 11:00)</option>
                                    <option>Siang (12:00 - 17:00)</option>
                                    <option>Malam (18:00 - 23:00)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Penumpang</label>
                                <input type="number" class="form-control" id="passenger-count" value="1" min="1" max="10">
                            </div>
                        </div>

                        <div class="form-row two-column">
                            <div class="form-group">
                                <label>Kota Asal</label>
                                <div class="combo-dropdown">
                                    <input type="text" 
                                           class="form-control combo-input" 
                                           id="departure-city-input"
                                           placeholder="Pilih kota asal"
                                           value="Jakarta"
                                           readonly>
                                    <button type="button" class="combo-dropdown-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="combo-dropdown-results">
                                        <div class="combo-search-input">
                                            <input type="text" placeholder="Cari kota asal..." autocomplete="off">
                                        </div>
                                        <div class="combo-options">
                                            <div class="combo-option selected" data-value="jakarta">Jakarta</div>
                                            <div class="combo-option" data-value="bandung">Bandung</div>
                                            <div class="combo-option" data-value="surabaya">Surabaya</div>
                                            <div class="combo-option" data-value="yogyakarta">Yogyakarta</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Kota Tujuan</label>
                                <div class="combo-dropdown">
                                    <input type="text" 
                                           class="form-control combo-input" 
                                           id="destination-city-input"
                                           placeholder="Pilih kota tujuan"
                                           value="Bandung"
                                           readonly>
                                    <button type="button" class="combo-dropdown-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="combo-dropdown-results">
                                        <div class="combo-search-input">
                                            <input type="text" placeholder="Cari kota tujuan..." autocomplete="off">
                                        </div>
                                        <div class="combo-options">
                                            <div class="combo-option" data-value="jakarta">Jakarta</div>
                                            <div class="combo-option selected" data-value="bandung">Bandung</div>
                                            <div class="combo-option" data-value="surabaya">Surabaya</div>
                                            <div class="combo-option" data-value="yogyakarta">Yogyakarta</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="armada-hr">

                        <!-- Pilih Armada Section -->
                        <h3 class="armada-section-title">Pilih Armada</h3>

                        <!-- Armada Card -->
                        <div class="armada-container">
                            <div class="armada-header">
                                <div class="armada-left">
                                    <h2 class="armada-name">Smart Shuttle Standar 2</h2>
                                    
                                    <div class="armada-badges">
                                        <span class="badge badge-primary">
                                            <i class="fas fa-van-shuttle"></i>
                                            Jakarta - Bandung
                                        </span>
                                        <span class="badge badge-light">
                                            <i class="fas fa-star"></i> Standar
                                        </span>
                                    </div>

                                    <div class="armada-route">
                                        <div>
                                            <div class="route-time">08:00</div>
                                            <div class="route-city">Jakarta</div>
                                        </div>
                                        <div>
                                            <div class="route-arrow">→</div>
                                            <div class="route-duration">3 Jam</div>
                                        </div>
                                        <div>
                                            <div class="route-time">11:00</div>
                                            <div class="route-city">Bandung</div>
                                        </div>
                                    </div>

                                    <div class="armada-status">
                                        <span class="status-badge status-available">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                        <span class="status-count">
                                            <i class="fas fa-chair"></i> <span id="available-seats">12</span> Kursi Tersedia
                                        </span>
                                    </div>

                                    <div class="armada-facilities">
                                        <span class="facility-badge">
                                            <i class="fas fa-snowflake"></i> AC Double
                                        </span>
                                        <span class="facility-badge">
                                            <i class="fas fa-wifi"></i> Wifi High Speed
                                        </span>
                                        <span class="facility-badge">
                                            <i class="fas fa-plug"></i> Charger USB-C
                                        </span>
                                    </div>
                                </div>

                                <div class="armada-right">
                                    <div class="price-section">
                                        <div class="price-amount">Rp. <span id="price-per-seat">150.000</span><span class="price-unit">/kursi</span></div>
                                        <div class="price-total">Total: Rp. <span id="total-price">150.000</span></div>
                                    </div>

                                    <button type="button" class="btn btn-primary" onclick="selectArmada()">
                                        <i class="fas fa-check-circle"></i> Pilih Armada Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion: Data Penumpang -->
            <div class="accordion-section" id="accordion-penumpang">
                <div class="accordion-header" onclick="toggleAccordion('penumpang')">
                    <h6 class="accordion-title">
                        Data Penumpang
                        <span class="accordion-icon" id="icon-penumpang">›</span>
                    </h6>
                </div>
                <div class="accordion-content" id="content-penumpang">
                    <div class="card">                
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Nama Lengkap *</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama lengkap sesuai KTP">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>NIK *</label>
                                <input type="text" class="form-control" placeholder="16 digit NIK">
                            </div>
                            
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>No. Telepon *</label>
                                <input type="tel" class="form-control" placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="email@contoh.com">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Alamat Lengkap</label>
                                <textarea class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion: Pilih Kursi -->
            <div class="accordion-section" id="accordion-kursi">
                <div class="accordion-header" onclick="toggleAccordion('kursi')">
                    <h6 class="accordion-title">
                       Pilih Kursi
                        <span class="accordion-icon" id="icon-kursi">›</span>
                    </h6>
                </div>
                <div class="accordion-content" id="content-kursi">
                    <div class="card">
                        <div class="seat-info">
                            <strong>Rute:</strong> Jakarta - Bandung<br>
                            <strong>Tanggal:</strong> Selasa, 20 Januari 2026<br>
                            <strong>Armada:</strong> Smart Shuttle Standar 2<br>
                            <strong>Waktu:</strong> 08:00 - 11:00 (3 Jam)
                        </div>
                        
                        <div class="seat-grid" id="seat-grid">
                            <!-- Seats will be generated by JavaScript -->
                        </div>
                        
                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="legend-color legend-available"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color legend-selected"></div>
                                <span>Terpilih</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color legend-sold"></div>
                                <span>Tidak Tersedia</span>
                            </div>
                        </div>
                        
                        <div style="margin-top: 24px; text-align: center;">
                            <p><strong>Kursi yang dipilih:</strong> <span id="selected-seats-display">Belum ada kursi yang dipilih</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion: Pembayaran -->
            <div class="accordion-section" id="accordion-pembayaran">
                <div class="accordion-header" onclick="toggleAccordion('pembayaran')">
                    <h6 class="accordion-title">
                        Pembayaran
                        <span class="accordion-icon" id="icon-pembayaran">›</span>
                    </h6>
                </div>
                <div class="accordion-content" id="content-pembayaran">
                    <div class="pilih-kursi-wrapper">
                        <div class="pesan-grid">
                            <!-- DETAIL PESANAN -->
                            <div class="box">
                                <div class="route-title">
                                    <span>DETAIL PESANAN</span>
                                </div>

                                <div class="dashed"></div>

                                <div class="route-title">
                                    <span>JAKARTA</span>
                                    <span>BANDUNG</span>
                                </div>
                                <div class="route-date">
                                    Selasa, 20 Januari 2026 | 08:00
                                </div>

                                <div class="dashed"></div>

                                <div class="section-title">DATA PEMESAN</div>
                                <div style="display:flex; justify-content:space-between;">
                                    <div>
                                        <div>Nama pemesan</div>
                                        <strong>Luna Ayra</strong>
                                    </div>
                                    <div style="text-align:right">
                                        <div>08589922391</div>
                                        <div>L.Ayra@gmail.com</div>
                                    </div>
                                </div>

                                <div class="dashed"></div>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Penumpang</th>
                                            <th>NIK</th>
                                            <th>Nomor Kursi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Luna Ayra</td>
                                            <td>23451234786007</td>
                                            <td><span class="kursi">3A</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="dashed"></div>

                                <div class="total-row">
                                    <span>Harga tiket</span>
                                    <span>Rp. 150.000</span>
                                </div>
                                <div class="total-row">
                                    <span>Jumlah penumpang</span>
                                    <span>X 1</span>
                                </div>
                                <div class="total-row">
                                    <span>Sub total</span>
                                    <span>Rp. 150.000</span>
                                </div>
                                <div class="total-row">
                                    <span>Diskon voucher</span>
                                    <span>Rp. 0</span>
                                </div>
                                <div class="total-row">
                                    <strong>Total harga</strong>
                                    <strong>Rp. 150.000</strong>
                                </div>
                            </div>

                            <!-- PEMBAYARAN -->
                            <div class="box">
                                <div class="section-title">PEMBAYARAN</div>

                                <label class="pay-label">Kode Qr</label>
                                <input class="pay-input" value="QRIS">

                                <label class="pay-label">Virtual Account</label>
                                <input class="pay-input" value="BCA Virtual Account">
                                <input class="pay-input" value="MANDIRI Virtual Account">

                                <button class="btn-pay" onclick="processPayment()">Lanjut pembayaran</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <script>
        // ====== TAB SYSTEM ======
        document.addEventListener('DOMContentLoaded', function() {
            setupTabs();
            initializeComboBoxes();
            generateSeatGrid();
            updatePricing();
            
            // Expand first accordion in pesan tab
            setTimeout(() => {
                toggleAccordion('rute', true);
            }, 100);
        });

        function setupTabs() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Update active tab button
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Show active tab content
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        if (content.id === tabId + 'Tab') {
                            content.classList.add('active');
                            
                            // SHOW/HIDE TABS NAVIGATION
                            const tabsNavigation = document.getElementById('tabsNavigation');
                            if (tabsNavigation) {
                                if (tabId === 'daftar') {
                                    tabsNavigation.style.display = 'none'; // Hide tabs in daftar transaksi
                                } else {
                                    tabsNavigation.style.display = 'block'; // Show tabs in pesan tiket
                                }
                            }
                            
                            // Initialize components when tab is shown
                            if (tabId === 'pesan') {
                                initializeComboBoxes();
                                generateSeatGrid();
                            }
                        }
                    });
                });
            });
            
            // Initialize: hide tabs navigation on daftar transaksi page
            const tabsNavigation = document.getElementById('tabsNavigation');
            if (tabsNavigation) {
                tabsNavigation.style.display = 'none'; // Default: hidden for daftar transaksi
            }
        }

        function switchTab(tabId) {
            const tabBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (tabBtn) {
                tabBtn.click();
            }
        }

        // ====== MODAL FUNCTIONALITY ======
        function openDetail() {
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetail();
            }
        });

        // ====== COMBO DROPDOWN FUNCTIONALITY ======
        function initializeComboBoxes() {
            setupComboBox('departure-city-input');
            setupComboBox('destination-city-input');
            setupComboBox('departure-date-input');
        }

        function setupComboBox(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const dropdown = input.parentNode.querySelector('.combo-dropdown-results');
            const toggleBtn = input.parentNode.querySelector('.combo-dropdown-toggle');
            const searchInput = dropdown?.querySelector('input[type="text"]');
            const options = dropdown?.querySelectorAll('.combo-option');
            
            if (!dropdown || !toggleBtn) return;
            
            // Toggle dropdown
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display === 'block';
                dropdown.style.display = isVisible ? 'none' : 'block';
                
                if (!isVisible && searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
            
            input.addEventListener('click', function(e) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
            
            // Select option
            options?.forEach(option => {
                option.addEventListener('click', function() {
                    input.value = this.textContent;
                    dropdown.style.display = 'none';
                    
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    options?.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? 'block' : 'none';
                    });
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        // ====== ACCORDION FUNCTIONALITY ======
        function toggleAccordion(section, forceExpand = false) {
            const accordion = document.getElementById('accordion-' + section);
            const content = document.getElementById('content-' + section);
            const icon = document.getElementById('icon-' + section);
            
            if (forceExpand) {
                content.style.display = 'block';
                icon.classList.add('rotated');
                accordion.classList.add('expanded');
                return;
            }
            
            if (content.style.display === 'block') {
                content.style.display = 'none';
                icon.classList.remove('rotated');
                accordion.classList.remove('expanded');
            } else {
                content.style.display = 'block';
                icon.classList.add('rotated');
                accordion.classList.add('expanded');
            }
        }

        // ====== PASSENGER COUNT & PRICING UPDATER ======
        const passengerInput = document.getElementById('passenger-count');
        const pricePerSeatEl = document.getElementById('price-per-seat');
        const totalPriceEl = document.getElementById('total-price');
        
        function updatePricing() {
            if (!passengerInput) return;
            
            const passengerCount = parseInt(passengerInput.value) || 1;
            const pricePerSeat = 150000;
            const totalTicketPrice = passengerCount * pricePerSeat;
            
            const formatRupiah = (number) => {
                return number.toLocaleString('id-ID');
            };
            
            if (pricePerSeatEl) pricePerSeatEl.textContent = formatRupiah(pricePerSeat);
            if (totalPriceEl) totalPriceEl.textContent = formatRupiah(totalTicketPrice);
        }
        
        if (passengerInput) {
            passengerInput.addEventListener('change', updatePricing);
            passengerInput.addEventListener('input', updatePricing);
            
            updatePricing();
        }

        // ====== SEAT SELECTION SYSTEM ======
        const seatGrid = document.getElementById('seat-grid');
        const selectedSeatsDisplay = document.getElementById('selected-seats-display');
        let selectedSeats = [];
        
        const seatData = [
            { id: '1A', available: true },
            { id: '2A', available: false },
            { id: '3A', available: true },
            { id: '1B', available: true },
            { id: '2B', available: false },
            { id: '3B', available: true },
            { id: '1C', available: true },
            { id: '2C', available: false },
            { id: '3C', available: false }
        ];
        
        function generateSeatGrid() {
            if (!seatGrid) return;
            
            seatGrid.innerHTML = '';
            
            seatData.forEach(seat => {
                const seatElement = document.createElement('div');
                seatElement.className = `seat ${seat.available ? 'available' : 'sold'}`;
                seatElement.textContent = seat.id;
                seatElement.dataset.id = seat.id;
                
                if (seat.available) {
                    seatElement.addEventListener('click', () => toggleSeatSelection(seat.id));
                }
                
                if (selectedSeats.includes(seat.id)) {
                    seatElement.classList.add('selected');
                }
                
                seatGrid.appendChild(seatElement);
            });
            
            updateSelectedSeatsDisplay();
        }
        
        function toggleSeatSelection(seatId) {
            const index = selectedSeats.indexOf(seatId);
            const passengerCount = passengerInput ? parseInt(passengerInput.value) || 1 : 1;
            
            if (index === -1) {
                if (selectedSeats.length < passengerCount) {
                    selectedSeats.push(seatId);
                } else {
                    if (selectedSeats.length > 0) {
                        const oldSeatId = selectedSeats[0];
                        selectedSeats[0] = seatId;
                        const oldSeat = document.querySelector(`.seat[data-id="${oldSeatId}"]`);
                        if (oldSeat) oldSeat.classList.remove('selected');
                    } else {
                        selectedSeats.push(seatId);
                    }
                }
            } else {
                selectedSeats.splice(index, 1);
            }
            
            generateSeatGrid();
        }
        
        function updateSelectedSeatsDisplay() {
            if (!selectedSeatsDisplay) return;
            
            if (selectedSeats.length === 0) {
                selectedSeatsDisplay.textContent = 'Belum ada kursi yang dipilih';
            } else {
                selectedSeatsDisplay.textContent = selectedSeats.join(', ');
            }
        }

        // ====== ARMADA SELECTION ======
        window.selectArmada = function() {
            alert('Armada berhasil dipilih! Silakan lanjutkan dengan mengisi data penumpang.');
            toggleAccordion('penumpang', true);
            
            const passengerCount = passengerInput ? parseInt(passengerInput.value) || 1 : 1;
            const availableSeatsEl = document.getElementById('available-seats');
            if (availableSeatsEl) {
                const currentSeats = parseInt(availableSeatsEl.textContent) || 12;
                const newAvailable = Math.max(0, currentSeats - passengerCount);
                availableSeatsEl.textContent = newAvailable;
                
                seatData.forEach(seat => {
                    if (selectedSeats.includes(seat.id)) {
                        seat.available = false;
                    }
                });
                
                generateSeatGrid();
            }
        };

        // ====== PAYMENT PROCESSING ======
        window.processPayment = function() {
            if (selectedSeats.length === 0) {
                alert('Silakan pilih kursi terlebih dahulu.');
                return;
            }
            
            const passengerCount = passengerInput ? parseInt(passengerInput.value) || 1 : 1;
            if (selectedSeats.length !== passengerCount) {
                alert(`Jumlah kursi yang dipilih (${selectedSeats.length}) tidak sesuai dengan jumlah penumpang (${passengerCount}).`);
                return;
            }
            
            alert('Pembayaran akan diproses...');
            
            setTimeout(() => {
                alert('Pembayaran berhasil! Tiket Anda telah dikirim ke email.');
                setTimeout(() => {
                    switchTab('daftar');
                }, 500);
            }, 1000);
        };
    </script>
</body>
</html>