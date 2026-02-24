@extends('layouts.app-driver')

@section('title', 'Jadwal Saya - Driver')

@push('styles')
{{-- Phosphor Icons --}}
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">

<style>
    /* ==========================================================================
       JADWAL SAYA - DRIVER DASHBOARD
       Enhanced & Responsive Design - MATCHING BANTUAN PAGE
       ========================================================================== */

    :root {
        --primary-dark: #0d3559;
        --primary-orange: #ff6a00;
        --primary-orange-light: rgba(255, 106, 0, 0.1);
        --success-green: #10b981;
        --success-green-light: rgba(16, 185, 129, 0.1);
        --danger-red: #ef4444;
        --danger-red-light: rgba(239, 68, 68, 0.1);
        --info-blue: #3b82f6;
        --info-blue-light: rgba(59, 130, 246, 0.1);
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
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    /* Container & Layout */
    .container-fluid {
        width: 100%;
        padding: 1rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION - MATCHING BANTUAN PAGE ===== */
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

    .profile-box {
        background: var(--white);
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary-dark);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
        transition: var(--transition);
    }

    .profile-box:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .profile-box i {
        color: var(--primary-orange);
        font-size: 1rem;
    }

    .divider {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange), transparent);
        margin: 0 0 1.5rem 0;
        border-radius: 3px;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    /* Buttons dengan efek lebih menarik */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        line-height: 1.3;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .btn:hover::after {
        width: 200px;
        height: 200px;
    }

    .btn i {
        font-size: 1rem;
        transition: transform 0.2s ease;
    }

    .btn:hover i {
        transform: translateX(2px) scale(1.1);
    }

    .btn-primary {
        background: var(--primary-orange);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .btn-primary:hover {
        background: #e65c00;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Statistik Cards dengan efek hidup */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.2rem 1.5rem;
        border: 1px solid var(--gray-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        border-color: var(--primary-orange);
        box-shadow: var(--shadow-hover);
        transform: translateY(-4px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-orange);
        opacity: 0.5;
        transition: width 0.3s ease;
    }

    .stat-card:hover::before {
        width: 6px;
        opacity: 1;
    }

    .stat-info {
        flex: 1;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
        color: var(--gray-text);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.2;
        transition: var(--transition);
    }

    .stat-card:hover .stat-number {
        color: var(--primary-orange);
        transform: scale(1.02);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--gray-border);
        opacity: 0.5;
        transition: var(--transition);
    }

    .stat-card:hover .stat-icon {
        opacity: 0.8;
        transform: rotate(5deg) scale(1.1);
        color: var(--primary-orange);
    }

    /* Progress Bar dengan efek */
    .progress-wrapper {
        margin-top: 0.5rem;
    }

    .progress {
        background: var(--gray-bg);
        height: 4px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-dark), var(--primary-orange));
        height: 100%;
        border-radius: 10px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-text {
        font-size: 0.65rem;
        color: var(--gray-text);
        font-weight: 500;
    }

    /* Alert dengan efek - MATCHING BANTUAN PAGE */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        position: relative;
        border-left: 4px solid transparent;
        animation: slideIn 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: var(--success-green-light);
        color: #065f46;
        border-left-color: var(--success-green);
    }

    .alert-danger {
        background: var(--danger-red-light);
        color: #991b1b;
        border-left-color: var(--danger-red);
    }

    .alert i {
        font-size: 1.1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .alert .btn-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.5;
        padding: 0 0.5rem;
        transition: var(--transition);
    }

    .alert .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* Card dengan efek - MATCHING BANTUAN PAGE */
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

    .card-header-custom {
        border-bottom: 1px solid var(--gray-border);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .card-header-custom::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--primary-orange);
    }

    .card-header-custom h6 {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header-custom h6 i {
        color: var(--primary-orange);
        font-size: 1.1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Badges dengan efek - MATCHING BANTUAN PAGE */
    .badge {
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge-success {
        background: var(--success-green-light);
        color: var(--success-green);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .badge-warning {
        background: #fef3c7;
        color: #f59e0b;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .badge-danger {
        background: var(--danger-red-light);
        color: var(--danger-red);
        border-color: rgba(239, 68, 68, 0.2);
    }

    .badge-secondary {
        background: var(--gray-bg);
        color: var(--gray-dark);
        border-color: var(--gray-border);
    }

    /* Table dengan desain lebih baik */
    .table-responsive {
        overflow-x: auto;
        margin: 0 -0.25rem;
        border-radius: var(--radius-sm);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
    }

    table thead th {
        background: linear-gradient(to bottom, var(--gray-bg), #f1f5f9);
        color: var(--primary-dark);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.8rem 0.6rem;
        border-bottom: 2px solid var(--gray-border);
        text-align: left;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    table tbody td {
        padding: 0.8rem 0.6rem;
        border-bottom: 1px solid var(--gray-border);
        color: var(--gray-dark);
        vertical-align: middle;
        white-space: nowrap;
        transition: var(--transition);
    }

    table tbody tr {
        transition: var(--transition);
    }

    table tbody tr:hover {
        background: linear-gradient(90deg, var(--gray-bg), transparent);
        transform: translateX(4px);
    }

    table tbody tr:hover td {
        color: var(--primary-dark);
    }

    /* Action Buttons dengan efek lebih baik */
    .table-action-group {
        display: flex;
        gap: 0.3rem;
    }

    .table-action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-border);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        background: var(--white);
        color: var(--gray-dark);
        padding: 0;
        position: relative;
        overflow: hidden;
    }

    .table-action-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }

    .table-action-btn:hover::after {
        width: 50px;
        height: 50px;
    }

    .table-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: transparent;
    }

    .table-action-btn:active {
        transform: translateY(0);
    }

    .table-action-btn.info:hover {
        background: var(--info-blue);
        color: var(--white);
    }

    .table-action-btn.success:hover {
        background: var(--success-green);
        color: var(--white);
    }

    .table-action-btn.danger:hover {
        background: var(--danger-red);
        color: var(--white);
    }

    .table-action-btn i {
        font-size: 0.7rem;
        transition: var(--transition);
    }

    .table-action-btn:hover i {
        transform: scale(1.2);
    }

    /* Empty State dengan animasi */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        animation: fadeInScale 0.5s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray-border);
        margin-bottom: 1rem;
        animation: float 3s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        color: var(--gray-text);
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Empty State Buttons */
    .btn-empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        line-height: 1.2;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn-empty i {
        font-size: 0.8rem;
        animation: none;
        margin-bottom: 0;
    }

    .btn-empty-primary {
        background: var(--primary-orange);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(255, 106, 0, 0.2);
    }

    .btn-empty-primary:hover {
        background: #e65c00;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 106, 0, 0.3);
    }

    .btn-empty-secondary {
        background: var(--white);
        color: var(--primary-dark);
        border: 1px solid var(--gray-border);
    }

    .btn-empty-secondary:hover {
        background: var(--gray-bg);
        border-color: var(--gray-text);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Utilities */
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .me-2 { margin-right: 0.5rem; }
    .me-3 { margin-right: 0.75rem; }
    .d-flex { display: flex; }
    .align-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .flex-wrap { flex-wrap: wrap; }
    .gap-2 { gap: 1rem; }

    /* ===== RESPONSIVE MOBILE IMPROVEMENTS - MATCHING BANTUAN PAGE ===== */
    @media screen and (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
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

        .header-actions {
            width: 100%;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            padding: 0.7rem;
            font-size: 0.9rem;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.6rem;
        }

        .stat-icon {
            font-size: 2rem;
        }

        .card {
            padding: 1.25rem;
        }

        .card-header-custom h6 {
            font-size: 1.1rem;
        }

        /* Table mobile improvements */
        .table-responsive {
            margin: 0;
            border-radius: var(--radius-sm);
        }

        table thead {
            display: none;
        }

        table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-border);
            border-radius: var(--radius-md);
            padding: 1rem;
            background: var(--white);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        table tbody tr:hover {
            transform: translateX(0) translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-orange);
        }

        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px dashed var(--gray-border);
            white-space: normal;
            font-size: 0.8rem;
        }

        table tbody td:last-child {
            border-bottom: none;
        }

        table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            min-width: 80px;
        }

        .table-action-group {
            width: 100%;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .table-action-btn {
            width: 32px;
            height: 32px;
        }

        .table-action-btn i {
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.8rem;
        }

        /* Empty state mobile */
        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state h4 {
            font-size: 1.2rem;
        }

        .empty-state p {
            font-size: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .empty-state .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .empty-state .btn-group .btn-empty {
            width: 100%;
            padding: 0.6rem;
        }

        /* Alert mobile */
        .alert {
            padding: 0.8rem 1rem;
            font-size: 0.8rem;
            gap: 0.6rem;
            margin-bottom: 1.2rem;
        }

        .alert i {
            font-size: 0.9rem;
        }

        .alert .btn-close {
            font-size: 1rem;
            padding: 0 0.3rem;
        }

        /* Progress text */
        .progress-text {
            font-size: 0.6rem;
        }
    }

    @media screen and (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .title {
            font-size: 1.3rem;
        }

        .title i {
            font-size: 1.3rem;
        }

        .stat-number {
            font-size: 1.4rem;
        }

        .stat-icon {
            font-size: 1.8rem;
        }

        .card {
            padding: 1rem;
        }

        .card-header-custom h6 {
            font-size: 1rem;
        }

        table tbody tr {
            padding: 0.8rem;
        }

        table tbody td {
            font-size: 0.75rem;
            padding: 0.5rem 0;
        }

        table tbody td::before {
            font-size: 0.65rem;
            min-width: 70px;
        }

        .table-action-group {
            gap: 0.3rem;
        }

        .table-action-btn {
            width: 28px;
            height: 28px;
        }

        .badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.6rem;
        }
    }

    @media screen and (max-width: 360px) {
        .title {
            font-size: 1.2rem;
        }

        .title i {
            font-size: 1.2rem;
        }

        .card-header-custom h6 {
            font-size: 0.95rem;
        }

        table tbody td::before {
            min-width: 60px;
            font-size: 0.6rem;
        }

        .table-action-btn {
            width: 26px;
            height: 26px;
        }

        .empty-state i {
            font-size: 2.5rem;
        }

        .empty-state h4 {
            font-size: 1.1rem;
        }
    }

    /* Landscape mode optimization */
    @media screen and (max-width: 896px) and (orientation: landscape) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .header-actions {
            flex-direction: row;
        }

        .btn {
            width: auto;
        }
    }

    /* Tablet devices */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .container-fluid {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .stat-number {
            font-size: 1.8rem;
        }

        .btn {
            padding: 0.5rem 1rem;
        }
    }

    /* Loading animation for progress bar */
    .progress-bar {
        position: relative;
        overflow: hidden;
    }

    .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        100% {
            left: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- HEADER SECTION - MATCHING BANTUAN PAGE -->
    <div class="header-section">
        <h1 class="title">
            <i class="fas fa-calendar-alt menu-icon"></i>
            Jadwal Saya
        </h1>
        <div class="header-actions">
            <a href="{{ route('driver.dashboard') }}" class="btn btn-secondary">
                <i class="ph ph-house"></i>
                Dashboard
            </a>
            <a href="{{ route('driver.jadwal.tersedia') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle"></i>
                Ambil Jadwal Baru
            </a>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Statistik Cards dengan animasi -->
    <div class="stats-grid">
        @php
            $jumlahBulanIni = $jumlahJadwalBulanIni ?? 0;
            $sisaKuota = 20 - $jumlahBulanIni;
            $jadwalAktif = $jadwalSaya ? $jadwalSaya->where('status', 'aktif')->count() : 0;
            $totalJadwal = $jadwalSaya ? $jadwalSaya->count() : 0;
        @endphp
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="ph ph-calendar"></i> Jadwal Bulan Ini
                </div>
                <div class="stat-number">{{ $jumlahBulanIni }}/20</div>
                <div class="progress-wrapper">
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ min($jumlahBulanIni * 5, 100) }}%;"></div>
                    </div>
                    <div class="progress-text">
                        <i class="ph ph-hourglass"></i> Sisa kuota: {{ max($sisaKuota, 0) }} jadwal
                    </div>
                </div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-calendar"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="ph ph-play-circle"></i> Jadwal Aktif
                </div>
                <div class="stat-number">{{ $jadwalAktif }}</div>
                <div class="progress-text" style="margin-top: 0.5rem;">
                    <i class="ph ph-arrow-right"></i> Dalam perjalanan
                </div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-play-circle"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="ph ph-clipboard-text"></i> Total Jadwal
                </div>
                <div class="stat-number">{{ $totalJadwal }}</div>
                <div class="progress-text" style="margin-top: 0.5rem;">
                    <i class="ph ph-check-circle"></i> Semua jadwal
                </div>
            </div>
            <div class="stat-icon">
                <i class="ph ph-clipboard-text"></i>
            </div>
        </div>
    </div>

    <!-- Notifikasi dengan animasi -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="ph ph-check-circle"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="ph ph-warning-circle"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
    @endif

    <!-- Tabel Jadwal dengan desain lebih baik -->
    <div class="card">
        <div class="card-header-custom">
            <h6>
                <i class="ph ph-calendar"></i>
                Daftar Jadwal Perjalanan
            </h6>
        </div>
        <div class="card-body">
            @if($jadwalSaya && $jadwalSaya->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Jadwal</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Armada</th>
                            <th>Harga</th>
                            <th>Kursi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalSaya as $index => $jadwal)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="ID Jadwal">
                                <span class="badge badge-secondary">#{{ $jadwal->id_jadwal_driver }}</span>
                            </td>
                            <td data-label="Rute">
                                <strong>{{ $jadwal->rute }}</strong>
                            </td>
                            <td data-label="Tanggal">
                                <i class="ph ph-calendar-blank" style="margin-right: 4px;"></i>
                                {{ $jadwal->tanggal_formatted }}
                            </td>
                            <td data-label="Waktu">
                                <i class="ph ph-clock" style="margin-right: 4px;"></i>
                                {{ $jadwal->waktu_berangkat_formatted }}
                            </td>
                            <td data-label="Armada">{{ $jadwal->armada }}</td>
                            <td data-label="Harga">
                                <strong style="color: var(--primary-orange);">{{ $jadwal->harga_formatted }}</strong>
                            </td>
                            <td data-label="Kursi">
                                <span class="badge {{ $jadwal->kursi_terisi >= $jadwal->total_kursi ? 'badge-danger' : 'badge-warning' }}">
                                    <i class="ph ph-users"></i>
                                    {{ $jadwal->kursi_terisi }}/{{ $jadwal->total_kursi }}
                                </span>
                            </td>
                            <td data-label="Status">
                                @if($jadwal->status == 'aktif')
                                    <span class="badge badge-success">
                                        <i class="ph ph-play-circle"></i> Aktif
                                    </span>
                                @elseif($jadwal->status == 'selesai')
                                    <span class="badge badge-secondary">
                                        <i class="ph ph-check-circle"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="ph ph-x-circle"></i> Batal
                                    </span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="table-action-group">
                                    <a href="{{ route('driver.jadwal.detail', $jadwal->id_jadwal_driver) }}"
                                       class="table-action-btn info" title="Lihat Detail">
                                        <i class="ph ph-eye"></i>
                                    </a>

                                    @if($jadwal->status == 'aktif')
                                    <form action="{{ route('driver.jadwal.update-status', $jadwal->id_jadwal_driver) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="table-action-btn success"
                                                onclick="return confirm('Tandai jadwal sebagai selesai?')"
                                                title="Selesaikan Jadwal">
                                            <i class="ph ph-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('driver.jadwal.batalkan', $jadwal->id_jadwal_driver) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action-btn danger"
                                                onclick="return confirm('Yakin ingin membatalkan jadwal ini?')"
                                                title="Batalkan Jadwal">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="ph ph-calendar-blank"></i>
                <h4>Belum Ada Jadwal</h4>
                <p>Anda belum mengambil jadwal apapun. Yuk ambil jadwal tersedia sekarang!</p>
                <div class="btn-group">
                    <a href="{{ route('driver.dashboard') }}" class="btn-empty btn-empty-secondary">
                        <i class="ph ph-house"></i> Dashboard
                    </a>
                    <a href="{{ route('driver.jadwal.tersedia') }}" class="btn-empty btn-empty-primary">
                        <i class="ph ph-plus-circle"></i> Ambil Jadwal
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        'use strict';

        // Auto-hide alerts with smooth animation
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                if (alert && !alert.classList.contains('alert-info')) {
                    alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(20px)';
                    setTimeout(() => alert.remove(), 400);
                }
            });
        }, 5000);

        // Add hover effect for table rows
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.2s ease';
            });
        });

        // Add click effect for action buttons
        const actionBtns = document.querySelectorAll('.table-action-btn');
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Animate stat numbers on page load
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(number => {
            const finalValue = number.innerText;
            const isFraction = finalValue.includes('/');
            
            if (!isFraction) {
                let start = 0;
                const end = parseInt(finalValue);
                if (isNaN(end)) return;
                
                const duration = 1000;
                const increment = end / (duration / 16);
                
                const timer = setInterval(() => {
                    start += increment;
                    if (start >= end) {
                        number.innerText = finalValue;
                        clearInterval(timer);
                    } else {
                        number.innerText = Math.floor(start);
                    }
                }, 16);
            }
        });

        // Add pulse animation to active badges
        const activeBadges = document.querySelectorAll('.badge-success');
        activeBadges.forEach(badge => {
            setInterval(() => {
                badge.style.transition = 'all 0.3s ease';
                badge.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 300);
            }, 2000);
        });

        // Responsive table helper
        function adjustTableForMobile() {
            const isMobile = window.innerWidth <= 768;
            const tableCells = document.querySelectorAll('tbody td');
            
            tableCells.forEach(cell => {
                if (isMobile) {
                    cell.setAttribute('data-label', cell.getAttribute('data-label') || '');
                }
            });
        }

        // Call on load and resize
        adjustTableForMobile();
        window.addEventListener('resize', adjustTableForMobile);
    })();
</script>
@endpush