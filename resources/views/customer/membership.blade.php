<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Aktif - SmartShuttle</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @extends('layouts.app-profile')

    @section('title', 'Membership SmartShuttle')

    @push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
        }

        /* MEMBERSHIP AKTIF STYLES */
        .membership-active-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .level-up-notification {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .level-up-notification i {
            font-size: 24px;
            margin-right: 10px;
        }

        .welcome-profile-card {
            background: linear-gradient(135deg, #00274D 0%, #003366 100%);
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 39, 77, 0.2);
            color: white;
            position: relative;
            overflow: hidden;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .welcome-profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .profile-left {
            display: flex;
            align-items: center;
            gap: 25px;
            flex: 1;
        }

        .profile-image {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            flex-shrink: 0;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00274D;
            font-size: 36px;
            font-weight: bold;
        }

        .profile-content {
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .profile-content h3 {
            font-size: 28px;
            margin-bottom: 5px;
            color: white;
            font-weight: 700;
            line-height: 1.2;
        }

        .profile-content p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.4;
            max-width: 500px;
        }

        .level-badge {
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }

        .level-badge.bronze {
            background: #CD7F32;
        }

        .level-badge.silver {
            background: #C0C0C0;
        }

        .level-badge.gold {
            background: #FFD700;
        }

        .level-badge.platinum {
            background: #E5E4E2;
        }

        .points-right-container {
            display: flex;
            gap: 20px;
            margin-left: auto;
        }

        .point-box-right {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px 25px;
            text-align: center;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            min-width: 160px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .point-box-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B2C, #FF8E53);
        }

        .point-box-right:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 44, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .point-box-right .number {
            font-size: 32px;
            color: white;
            font-weight: 800;
            margin-bottom: 5px;
            line-height: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .point-box-right .label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .point-box-right .level-status {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-top: 5px;
        }

        .welcome-icon {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 80px;
            color: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }

        .progress-info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
        }

        .progress-info-card h6 {
            color: #00274D;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-oval-container {
            width: 100%;
            margin: 25px 0;
        }

        .progress-oval {
            width: 100%;
            height: 40px;
            background: #f0f0f0;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
            border: 2px solid #e0e0e0;
        }

        .progress-oval-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(90deg,
                #FF6B2C 0%,
                #FF8E53 30%,
                #FFA726 70%,
                #FFB74D 100%);
            border-radius: 20px;
            transition: width 2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 10px rgba(255, 107, 44, 0.3);
            z-index: 2;
        }

        .progress-oval-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                rgba(255,255,255,0.1) 0%,
                rgba(255,255,255,0.3) 50%,
                rgba(255,255,255,0.1) 100%);
            animation: shimmer 3s infinite;
            border-radius: 20px;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-oval-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding: 0 5px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            position: relative;
            padding-top: 25px;
        }

        .progress-label::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 10px;
            background: #ddd;
        }

        .progress-stats {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
            border: 1px solid #e0e7ff;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #FF6B2C;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }

        .level-card,
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
        }

        .level-card h6,
        .info-card h6 {
            color: #00274D;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .membership-tier {
            background: white;
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 5px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .membership-tier.bronze-tier {
            border-left: 5px solid #CD7F32;
        }

        .membership-tier.silver-tier {
            border-left: 5px solid #C0C0C0;
        }

        .membership-tier.gold-tier {
            border-left: 5px solid #FFD700;
        }

        .membership-tier.platinum-tier {
            border-left: 5px solid #E5E4E2;
        }

        .membership-tier.current-tier {
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            position: relative;
            border: 1px solid #e0e0e0;
        }

        .membership-tier.current-tier::before {
            content: '✓ Level Saat Ini';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }

        .membership-tier:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .tier-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .tier-name {
            font-weight: 700;
            font-size: 18px;
            color: #00274D;
        }

        .tier-points {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            margin-top: 2px;
        }

        .tier-reward {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            display: inline-block;
        }

        .membership-tier.bronze-tier .tier-reward {
            background: #CD7F32;
        }

        .membership-tier.silver-tier .tier-reward {
            background: #C0C0C0;
        }

        .membership-tier.gold-tier .tier-reward {
            background: #FFD700;
        }

        .membership-tier.platinum-tier .tier-reward {
            background: #E5E4E2;
        }

        .tier-description {
            color: #666;
            margin-bottom: 0;
            font-size: 15px;
            line-height: 1.5;
        }

        .tier-progress {
            margin-top: 15px;
            display: none;
        }

        .tier-progress.active {
            display: block;
        }

        .tier-progress .progress {
            height: 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .membership-tier.bronze-tier .tier-progress .progress-bar {
            background-color: #CD7F32;
        }

        .membership-tier.silver-tier .tier-progress .progress-bar {
            background-color: #C0C0C0;
        }

        .membership-tier.gold-tier .tier-progress .progress-bar {
            background-color: #FFD700;
        }

        .membership-tier.platinum-tier .tier-progress .progress-bar {
            background-color: #E5E4E2;
        }

        .tier-progress-text {
            font-size: 12px;
            color: #666;
            text-align: right;
            font-weight: 600;
        }

        .benefit-list {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
        }

        .benefit-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            color: #444;
            font-size: 15px;
            display: flex;
            align-items: center;
        }

        .benefit-list li:last-child {
            border-bottom: none;
        }

        .benefit-list li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            margin-top: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .membership-active-container {
                padding: 0 15px;
            }

            .welcome-profile-card {
                gap: 20px;
            }

            .point-box-right {
                min-width: 150px;
                padding: 18px 20px;
            }
        }

        @media (max-width: 992px) {
            .welcome-profile-card {
                flex-direction: column;
                text-align: center;
                padding: 25px;
                gap: 25px;
            }

            .profile-left {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }

            .profile-content {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .points-right-container {
                margin-left: 0;
                width: 100%;
                justify-content: center;
            }

            .progress-stats {
                flex-direction: column;
                gap: 15px;
            }

            .stat-item {
                border-bottom: 1px solid #e0e7ff;
                padding-bottom: 15px;
            }

            .stat-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .welcome-icon {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .membership-active-container {
                padding: 0 10px;
            }

            .tier-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .tier-reward {
                align-self: flex-start;
            }

            .points-right-container {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .point-box-right {
                width: 100%;
                max-width: 280px;
            }

            .progress-oval {
                height: 35px;
            }

            .profile-content h3 {
                font-size: 24px;
            }

            .profile-content p {
                font-size: 15px;
            }

            .membership-tier.current-tier::before {
                position: relative;
                top: 0;
                right: 0;
                margin-bottom: 10px;
                display: inline-block;
            }

            .point-box-right .number {
                font-size: 28px;
            }
        }

        @media (max-width: 576px) {
            .welcome-profile-card,
            .progress-info-card,
            .level-card,
            .info-card,
            .membership-tier {
                padding: 20px;
            }

            .point-box-right .number {
                font-size: 26px;
            }

            .level-card h6,
            .info-card h6 {
                font-size: 18px;
            }

            .progress-oval {
                height: 30px;
            }

            .profile-content h3 {
                font-size: 22px;
            }

            .profile-image {
                width: 80px;
                height: 80px;
                font-size: 28px;
            }

            .point-box-right {
                padding: 15px 20px;
            }
        }
    </style>
    @endpush
</head>
<body>
@section('content')
<!-- MAIN CONTENT -->
<div class="membership-active-container">
    <!-- PAGE 4: MEMBERSHIP AKTIF -->
    <div id="page-membership-active">
        <!-- Level Up Notification -->
        @if(session('level_up'))
        <div class="level-up-notification" id="levelUpNotification">
            <div>
                <i class="fas fa-trophy"></i>
                <strong id="levelUpMessage">{{ session('level_up_message') ?? 'Selamat! Anda sekarang menjadi Member Bronze!' }}</strong>
            </div>
            <div>
                <i class="fas fa-party-horn"></i>
            </div>
        </div>
        @endif

        <!-- KOMBINASI WELCOME DAN PROFILE CARD -->
        <div class="welcome-profile-card">
            <div class="profile-left">
                <div class="profile-image" id="profileImage">
                    @php
                        // Function untuk mendapatkan inisial dari nama
                        function getInitials($name) {
                            $words = explode(' ', $name);
                            $initials = '';

                            foreach ($words as $word) {
                                if (!empty($word)) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            }

                            // Jika hanya 1 kata, ambil 2 karakter pertama
                            if (strlen($initials) == 1) {
                                $initials = strtoupper(substr($name, 0, 2));
                            } else {
                                // Ambil maksimal 2 huruf inisial
                                $initials = substr($initials, 0, 2);
                            }

                            return $initials;
                        }
                    @endphp
                    {{ getInitials(Auth::user()->name) }}
                </div>

                <div class="profile-content">
                    <h3 id="welcomeTitle">Hello, {{ Auth::user()->name }}</h3>
                    <p id="welcomeMessage">Selamat datang di program membership Smart Shuttle!</p>

                    <div class="level-badge {{ strtolower($membership->level ?? 'bronze') }}" id="levelBadge">
                        {{ $membership->level ?? 'Bronze' }} Member
                    </div>
                </div>
            </div>

            <!-- POINT BOXES DI SEBELAH KANAN - HORIZONTAL SEJAJAR -->
            <div class="points-right-container">
                <div class="point-box-right">
                    <div class="number" id="currentPoints">{{ $membership->points ?? 0 }}</div>
                    <div class="label">Point Member</div>
                    <div class="level-status" id="levelStatus">
                        @php
                            $nextLevelPoints = 0;
                            $currentLevel = strtolower($membership->level ?? 'bronze');
                            if($currentLevel == 'bronze') $nextLevelPoints = 1000;
                            elseif($currentLevel == 'silver') $nextLevelPoints = 2500;
                            elseif($currentLevel == 'gold') $nextLevelPoints = 4500;
                            else $nextLevelPoints = 6000;

                            $pointsNeeded = $nextLevelPoints - ($membership->points ?? 0);
                        @endphp
                        @if($pointsNeeded <= 0 && $currentLevel != 'platinum')
                            🎉 Siap naik ke {{ ucfirst($nextLevel ?? 'Silver') }}!
                        @elseif($currentLevel == 'platinum' && ($membership->points ?? 0) >= 6000)
                            🏆 Level tertinggi tercapai!
                        @else
                            Butuh {{ $pointsNeeded }} PM untuk {{ ucfirst($nextLevel ?? 'Silver') }}
                        @endif
                    </div>
                </div>

                <div class="point-box-right">
                    <div class="number" id="loyaltyPoints">{{ $membership->loyalty_points ?? 0 }}</div>
                    <div class="label">Loyalty Point</div>
                </div>
            </div>

            <div class="welcome-icon">
                <i class="fas fa-crown"></i>
            </div>
        </div>

        <!-- PROGRESS BAR OVAL PANJANG -->
        <div class="progress-info-card">
            <h6><i class="fas fa-chart-line"></i> Progress Level Membership</h6>

            <div class="progress-oval-container">
                <div class="progress-oval">
                    @php
                        $progressPercentage = 0;
                        if(isset($membership)) {
                            $currentPoints = $membership->points ?? 0;
                            if($membership->level == 'Bronze') {
                                $progressPercentage = ($currentPoints / 1000) * 100;
                            } elseif($membership->level == 'Silver') {
                                $progressPercentage = (($currentPoints - 1000) / 1500) * 100;
                            } elseif($membership->level == 'Gold') {
                                $progressPercentage = (($currentPoints - 2500) / 2000) * 100;
                            } elseif($membership->level == 'Platinum') {
                                $progressPercentage = (($currentPoints - 4500) / 1500) * 100;
                            }
                            $progressPercentage = min(100, max(0, $progressPercentage));
                        }
                    @endphp
                    <div class="progress-oval-fill" id="progressOvalFill" style="width: {{ $progressPercentage }}%;"></div>
                </div>
                <div class="progress-oval-labels">
                    @php
                        $currentMin = 0;
                        $currentMax = 1000;
                        if($membership->level == 'Silver') {
                            $currentMin = 1000;
                            $currentMax = 2500;
                        } elseif($membership->level == 'Gold') {
                            $currentMin = 2500;
                            $currentMax = 4500;
                        } elseif($membership->level == 'Platinum') {
                            $currentMin = 4500;
                            $currentMax = 6000;
                        }
                    @endphp
                    <div class="progress-label" id="currentLevelLabel">{{ $membership->level ?? 'Bronze' }}<br>{{ $currentMin }} PM</div>
                    <div class="progress-label" id="nextLevelLabel">{{ $nextLevel ?? 'Silver' }}<br>{{ $currentMax }} PM</div>
                </div>
            </div>

            <div class="progress-stats">
                <div class="stat-item">
                    <div class="stat-number" id="progressPercentage">{{ number_format($progressPercentage, 1) }}%</div>
                    <div class="stat-label">Progress</div>
                </div>
                <div class="stat-item">
                    @php
                        $pointsNeeded = max(0, $currentMax - ($membership->points ?? 0));
                    @endphp
                    <div class="stat-number" id="pointsNeededStat">{{ $pointsNeeded }}</div>
                    <div class="stat-label">PM Dibutuhkan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="currentPointsStat">{{ $membership->points ?? 0 }}</div>
                    <div class="stat-label">PM Saat Ini</div>
                </div>
            </div>
        </div>

        <!-- TINGKAT MEMBERSHIP -->
        <div class="info-card">
            <h6><i class="fas fa-trophy"></i> Tingkat Membership</h6>

            @php
                $tiers = [
                    'bronze' => ['min' => 0, 'max' => 1000, 'reward' => '+50 LP'],
                    'silver' => ['min' => 1000, 'max' => 2500, 'reward' => '+60 LP'],
                    'gold' => ['min' => 2500, 'max' => 4500, 'reward' => '+80 LP'],
                    'platinum' => ['min' => 4500, 'max' => 6000, 'reward' => '+100 LP'],
                ];
                $currentLevel = strtolower($membership->level ?? 'bronze');
            @endphp

            @foreach($tiers as $tierName => $tierData)
            <div class="membership-tier {{ $tierName }}-tier {{ $currentLevel == $tierName ? 'current-tier' : '' }}" id="{{ $tierName }}Tier">
                <div class="tier-header">
                    <div>
                        <div class="tier-name">{{ ucfirst($tierName) }}</div>
                        <div class="tier-points">({{ $tierData['min'] }}–{{ $tierData['max'] }} PM)</div>
                    </div>
                    <div class="tier-reward">{{ $tierData['reward'] }} / pembelian</div>
                </div>
                <p class="tier-description">Reward: {{ $tierData['reward'] }} / pembelian</p>
                @if($currentLevel == $tierName || ($membership->points ?? 0) >= $tierData['min'])
                <div class="tier-progress active" id="{{ $tierName }}ProgressContainer">
                    @php
                        $tierProgress = 0;
                        if(($membership->points ?? 0) >= $tierData['min']) {
                            if(($membership->points ?? 0) >= $tierData['max']) {
                                $tierProgress = 100;
                            } else {
                                $tierProgress = ((($membership->points ?? 0) - $tierData['min']) / ($tierData['max'] - $tierData['min'])) * 100;
                            }
                        }
                    @endphp
                    <div class="progress">
                        <div class="progress-bar" id="{{ $tierName }}ProgressBar" style="width: {{ $tierProgress }}%"></div>
                    </div>
                    <div class="tier-progress-text" id="{{ $tierName }}ProgressText">
                        @if(($membership->points ?? 0) >= $tierData['min'])
                            {{ ($membership->points ?? 0) - $tierData['min'] }}/{{ $tierData['max'] - $tierData['min'] }} PM ({{ number_format($tierProgress, 1) }}%)
                        @else
                            0/{{ $tierData['max'] - $tierData['min'] }} PM (0%)
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- CARA KERJA POIN -->
        <div class="info-card">
            <h6><i class="fas fa-cogs"></i> Cara Kerja Poin</h6>

            <p style="font-size: 15px; margin-bottom: 15px; line-height: 1.6;">
                <strong>Point Member (PM)</strong> digunakan untuk naik level dan bertambah <strong>+100</strong> setiap pembelian.
            </p>
            <p style="font-size: 15px; margin-bottom: 15px; line-height: 1.6;">
                <strong>Loyalti Point (LP)</strong> dapat digunakan untuk potongan harga:
            </p>

            <ul class="benefit-list">
                <li>50 LoyaltiPoint = 5% Diskon</li>
                <li>100 LoyaltiPoint = 10% Diskon</li>
                <li>150 LoyaltiPoint = 15% Diskon</li>
                <li>Point Member (PM) tidak dapat digunakan untuk diskon, hanya untuk naik level membership</li>
                <li>LoyaltiPoint (LP) tidak dapat dikombinasikan dengan promo lainnya</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} SMART SHUTTLE. Semua hak dilindungi.</p>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Membership Active Page Loaded');

        // Auto-hide level up notification after 5 seconds
        const levelUpNotification = document.getElementById('levelUpNotification');
        if (levelUpNotification) {
            setTimeout(() => {
                levelUpNotification.style.display = 'none';
            }, 5000);
        }
    });
</script>
</body>
</html>
