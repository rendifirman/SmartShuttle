@extends('layouts.app-admin')

@section('content')
<style>
    /* ==========================================================================
       SYSTEM SETTINGS - SCHEDULE FLOW
       Minimalist Design dengan Deep Neutral Palette
       ========================================================================== */

    /* CSS Variables - Deep Neutral Palette */
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f8f8;
        --bg-tertiary: #f0f0f0;
        
        --text-primary: #1a1a1a;
        --text-secondary: #4a4a4a;
        --text-tertiary: #6b6b6b;
        --text-muted: #8a8a8a;
        
        --border-light: #e5e5e5;
        --border-medium: #d4d4d4;
        
        --accent-soft: #2563eb;
        --accent-soft-bg: #e8f0fe;
        --accent-success: #10b981;
        --accent-success-bg: #e6f7f0;
        
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.02);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        --radius-xl: 12px;
        
        --transition: all 0.15s ease;
    }

    /* ===== Base Layout ===== */
    .settings-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* ===== Header ===== */
    .settings-header {
        margin-bottom: 2rem;
    }

    .settings-header h1 {
        font-size: 1.75rem;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.01em;
    }

    .settings-breadcrumb {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .settings-breadcrumb a {
        color: var(--text-tertiary);
        text-decoration: none;
        transition: var(--transition);
    }

    .settings-breadcrumb a:hover {
        color: var(--text-primary);
    }

    .settings-breadcrumb span {
        margin: 0 0.5rem;
        color: var(--border-medium);
    }

    /* ===== Alert Minimal ===== */
    .alert-minimal {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
        animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-minimal i {
        font-size: 1.1rem;
        color: var(--accent-success);
    }

    .alert-minimal .btn-close-minimal {
        margin-left: auto;
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        transition: var(--transition);
    }

    .alert-minimal .btn-close-minimal:hover {
        background: var(--bg-tertiary);
        color: var(--text-primary);
    }

    /* ===== Card Minimal ===== */
    .card-minimal {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .card-header-minimal {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header-minimal i {
        font-size: 1.25rem;
        color: var(--text-tertiary);
    }

    .card-header-minimal h5 {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }

    .card-body-minimal {
        padding: 1.5rem;
    }

    /* ===== Radio Options ===== */
    .radio-option {
        display: block;
        margin-bottom: 1rem;
        cursor: pointer;
    }

    .radio-option:last-child {
        margin-bottom: 0;
    }

    .radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .radio-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        transition: var(--transition);
    }

    .radio-option:hover .radio-content {
        background: var(--bg-secondary);
        border-color: var(--border-medium);
    }

    .radio-option input[type="radio"]:checked + .radio-content {
        background: var(--bg-secondary);
        border-color: var(--text-tertiary);
    }

    .radio-dot {
        width: 18px;
        height: 18px;
        border: 2px solid var(--border-medium);
        border-radius: 50%;
        background: var(--bg-primary);
        transition: var(--transition);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .radio-option:hover .radio-dot {
        border-color: var(--text-tertiary);
    }

    .radio-option input[type="radio"]:checked + .radio-content .radio-dot {
        border-color: var(--text-primary);
        background: var(--text-primary);
        box-shadow: inset 0 0 0 4px var(--bg-primary);
    }

    .radio-text {
        flex: 1;
    }

    .radio-title {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.35rem;
        font-size: 1rem;
    }

    .radio-description {
        color: var(--text-tertiary);
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .radio-badge {
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.2rem 0.75rem;
        background: var(--bg-tertiary);
        color: var(--text-tertiary);
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .radio-option input[type="radio"]:checked + .radio-content .radio-badge {
        background: var(--bg-primary);
        color: var(--text-secondary);
    }

    /* ===== Info Bar ===== */
    .info-bar {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .info-bar i {
        font-size: 1.25rem;
        color: var(--text-tertiary);
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .info-value span {
        font-weight: 500;
        color: var(--text-primary);
    }

    .info-badge {
        background: var(--bg-tertiary);
        padding: 0.2rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--text-tertiary);
    }

    /* ===== Features List Minimal ===== */
    .features-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-tertiary);
        font-size: 0.9rem;
    }

    .feature-item i {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    /* ===== Buttons Minimal ===== */
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-minimal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 400;
        border-radius: var(--radius-md);
        border: 1px solid transparent;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        background: var(--bg-primary);
        color: var(--text-secondary);
    }

    .btn-primary-minimal {
        background: var(--text-primary);
        color: var(--bg-primary);
        border-color: var(--text-primary);
    }

    .btn-primary-minimal:hover {
        background: var(--text-secondary);
        border-color: var(--text-secondary);
    }

    .btn-secondary-minimal {
        background: var(--bg-primary);
        border-color: var(--border-medium);
        color: var(--text-tertiary);
    }

    .btn-secondary-minimal:hover {
        background: var(--bg-secondary);
        border-color: var(--text-tertiary);
        color: var(--text-primary);
    }

    .btn-minimal i {
        font-size: 0.9rem;
    }

    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading i {
        opacity: 0;
    }

    .btn-loading:after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin: -8px 0 0 -8px;
        border: 1px solid rgba(255,255,255,0.3);
        border-top-color: var(--bg-primary);
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== Divider ===== */
    .divider {
        height: 1px;
        background: var(--border-light);
        margin: 1.5rem 0;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .settings-container {
            padding: 0 1rem;
            margin: 1.5rem auto;
        }

        .settings-header h1 {
            font-size: 1.5rem;
        }

        .card-header-minimal {
            padding: 1rem 1.25rem;
        }

        .card-body-minimal {
            padding: 1.25rem;
        }

        .radio-content {
            padding: 1rem;
        }

        .info-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
        }

        .info-value {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .button-group {
            flex-direction: column;
        }

        .btn-minimal {
            width: 100%;
        }

        .features-list {
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .settings-header h1 {
            font-size: 1.35rem;
        }

        .radio-description {
            font-size: 0.85rem;
        }

        .features-list {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>

<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <h1>System Settings — Schedule Flow</h1>
        <div class="settings-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.jadwal.index') }}">Jadwal</a>
            <span>/</span>
            <span>Schedule Flow</span>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert-minimal" id="successAlert">
            <i class="fas fa-check"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close-minimal" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="card-minimal">
        <div class="card-header-minimal">
            <i class="fas fa-sliders-h"></i>
            <h5>Konfigurasi Flow Jadwal</h5>
        </div>
        
        <div class="card-body-minimal">
            <form method="POST" action="{{ route('admin.system_settings.schedule_flow.update') }}" id="scheduleFlowForm">
                @csrf

                <!-- Driver Confirmation -->
                <label class="radio-option">
                    <input type="radio" name="jadwal_flow_mode" value="driver_confirmation" {{ $mode === 'driver_confirmation' ? 'checked' : '' }}>
                    <div class="radio-content">
                        <span class="radio-dot"></span>
                        <div class="radio-text">
                            <div class="radio-title">Driver Confirmation</div>
                            <p class="radio-description">
                                Admin creates open schedules; drivers claim them
                            </p>
                            <span class="radio-badge">
                                Driver-driven workflow
                            </span>
                        </div>
                    </div>
                </label>

                <!-- Direct Assign -->
                <label class="radio-option">
                    <input type="radio" name="jadwal_flow_mode" value="direct_assign" {{ $mode === 'direct_assign' ? 'checked' : '' }}>
                    <div class="radio-content">
                        <span class="radio-dot"></span>
                        <div class="radio-text">
                            <div class="radio-title">Direct Assign</div>
                            <p class="radio-description">
                                Admin assigns driver; schedules active immediately
                            </p>
                            <span class="radio-badge">
                                Admin-driven workflow
                            </span>
                        </div>
                    </div>
                </label>

                <!-- Info Bar -->
                <div class="info-bar">
                    <i class="fas fa-info-circle"></i>
                    <div class="info-content">
                        <div class="info-label">Current Mode</div>
                        <div class="info-value">
                            <span>
                                @if($mode === 'driver_confirmation')
                                    Driver Confirmation
                                @else
                                    Direct Assign
                                @endif
                            </span>
                            <span class="info-badge">
                                @if($mode === 'driver_confirmation')
                                    <i class="fas fa-user-check"></i> Driver Choice
                                @else
                                    <i class="fas fa-user-tie"></i> Admin Choice
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="features-list">
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>Real-time updates</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Role-based access</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-history"></i>
                        <span>Audit trail</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn-minimal btn-primary-minimal" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Save Settings
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn-minimal btn-secondary-minimal">
                        <i class="fas fa-arrow-left"></i>
                        Back to Schedules
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    // DOM Elements
    const form = document.getElementById('scheduleFlowForm');
    const submitBtn = document.getElementById('submitBtn');
    const radioOptions = document.querySelectorAll('.radio-option');
    
    // State
    let isSubmitting = false;

    // Initialize
    function init() {
        attachEventListeners();
    }

    // Event Listeners
    function attachEventListeners() {
        // Form submit
        form.addEventListener('submit', handleSubmit);

        // Radio option click
        radioOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                // Don't trigger if clicking on the dot area
                if (e.target.classList.contains('radio-dot')) {
                    return;
                }
                
                const radio = this.querySelector('input[type="radio"]');
                if (radio && !radio.checked) {
                    radio.checked = true;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    radio.dispatchEvent(event);
                }
            });
        });
    }

    // Handle form submit
    function handleSubmit(e) {
        e.preventDefault();

        const selected = document.querySelector('input[name="jadwal_flow_mode"]:checked');
        
        if (!selected) {
            showMessage('Please select a schedule flow mode');
            return;
        }

        if (isSubmitting) return;

        isSubmitting = true;
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;

        // Submit form
        setTimeout(() => {
            e.target.submit();
        }, 100);
    }

    // Show message
    function showMessage(text) {
        const msg = document.createElement('div');
        msg.className = 'alert-minimal';
        msg.style.marginTop = '1rem';
        msg.innerHTML = `
            <i class="fas fa-info-circle"></i>
            <span>${text}</span>
            <button type="button" class="btn-close-minimal" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        form.appendChild(msg);
        
        setTimeout(() => {
            if (msg.parentNode) {
                msg.remove();
            }
        }, 3000);
    }

    // Auto-hide success alert
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            if (successAlert.parentNode) {
                successAlert.remove();
            }
        }, 5000);
    }

    // Initialize
    init();
})();
</script>
@endsection