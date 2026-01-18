@extends('layouts.app-admin')

@section('title', 'Dashboard Driver - SmartShuttle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Driver
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">
                            <i class="fas fa-check-circle"></i> Selamat datang, {{ Auth::guard('driver')->user()->name }}!
                        </h5>
                        <p class="mb-0">Anda telah berhasil login ke portal driver SmartShuttle.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-route"></i> Rute Hari Ini
                                    </h5>
                                    <h2 class="mb-0">-</h2>
                                    <small>Rute yang dijadwalkan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-users"></i> Penumpang
                                    </h5>
                                    <h2 class="mb-0">-</h2>
                                    <small>Total penumpang hari ini</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-clock"></i> Status
                                    </h5>
                                    <h4 class="mb-0">Aktif</h4>
                                    <small>Status driver</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Informasi Driver</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nama:</strong> {{ Auth::guard('driver')->user()->name }}</p>
                                            <p><strong>Email:</strong> {{ Auth::guard('driver')->user()->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Role:</strong> Driver</p>
                                            <p><strong>Status:</strong> <span class="badge badge-success">Aktif</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
