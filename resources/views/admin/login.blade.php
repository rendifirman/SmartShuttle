@extends('layouts.app-admin')

@section('title', 'Admin Login - SmartShuttle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login Admin
                    </h4>
                    <small class="text-primary-50">SmartShuttle Management System</small>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal Login!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.post') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="remember"
                                   name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>

                    @if(app()->environment('local'))
                    <div class="mt-4">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Test Credentials (Development Only):</strong>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Admin Pusat:</strong><br>
                                    <small>admin@smartshuttle.test<br>admin123</small>
                                </div>
                                <div class="col-6">
                                    <strong>Branch Admin:</strong><br>
                                    <small>jakarta@smartshuttle.test<br>password123</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .min-vh-100 {
        min-height: 100vh !important;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-bottom: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .text-primary-50 {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-fill test credentials in development
    @if(app()->environment('local'))
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers for test credentials
        const testCredentials = document.querySelectorAll('.alert-warning small');
        testCredentials.forEach(cred => {
            cred.style.cursor = 'pointer';
            cred.addEventListener('click', function() {
                const text = this.textContent;
                const lines = text.split('\n');
                const email = lines[0].trim();
                const password = lines[1] ? lines[1].trim() : '';

                document.getElementById('email').value = email;
                document.getElementById('password').value = password;
            });
        });
    });
    @endif
</script>
@endpush
@endsection
