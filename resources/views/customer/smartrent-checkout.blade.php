@extends('layouts.app')

@section('title', 'Checkout SmartRent - SmartRent')

@push('styles')
<style>
    :root {
        --primary-color: #123352;
        --secondary-color: #FF581E;
    }
    
    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    .checkout-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 30px;
        text-align: center;
    }
    
    .booking-summary {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .summary-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .summary-label {
        color: #666;
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 16px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--secondary-color);
    }
    
    .btn-submit {
        width: 100%;
        padding: 15px;
        background: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn-submit:hover {
        background: #E54E1A;
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <h1 class="checkout-title">Form Pemesanan SmartRent</h1>
    
    <div class="booking-summary">
        <h3 class="summary-title">Ringkasan Pemesanan</h3>
        <div class="summary-item">
            <span class="summary-label">Kendaraan:</span>
            <span class="summary-value">{{ $vehicle['name'] ?? '' }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Layanan:</span>
            <span class="summary-value">
                @if(session('smartrent_checkout')['service_type'] == 'with-driver')
                    Dengan Sopir
                @else
                    Lepas Kunci
                @endif
            </span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Durasi:</span>
            <span class="summary-value">{{ session('smartrent_checkout')['duration'] ?? '' }} Hari</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Harga:</span>
            <span class="summary-value" style="color: var(--secondary-color); font-size: 20px;">
                Rp {{ number_format($total ?? 0, 0, ',', '.') }}
            </span>
        </div>
    </div>
    
    <form action="{{ route('smartrent.checkout') }}" method="POST">
        @csrf
        
        <input type="hidden" name="vehicle_id" value="{{ session('smartrent_checkout')['vehicle_id'] ?? '' }}">
        <input type="hidden" name="service_type" value="{{ session('smartrent_checkout')['service_type'] ?? '' }}">
        <input type="hidden" name="duration" value="{{ session('smartrent_checkout')['duration'] ?? '' }}">
        <input type="hidden" name="rent_date" value="{{ session('smartrent_checkout')['rent_date'] ?? '' }}">
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Nomor Telepon *</label>
            <input type="tel" name="phone" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Alamat Pengambilan Kendaraan *</label>
            <textarea name="pickup_address" class="form-control" rows="3" required></textarea>
        </div>
        
        <button type="submit" class="btn-submit">
            Lanjutkan ke Pembayaran
        </button>
    </form>
</div>
@endsection