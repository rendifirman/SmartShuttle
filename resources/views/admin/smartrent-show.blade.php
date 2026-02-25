@extends('layouts.app-admin')

@section('content')
<div style="padding: 32px;">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h4 style="font-size: 24px; font-weight: 700; color: #0d3559; margin: 0 0 4px 0;">Detail Transaksi SmartRent</h4>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">Kode Pemesanan: <strong>{{ $smartRent->kode_booking }}</strong></p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.smartrent.edit', $smartRent->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.smartrent.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    <div style="padding: 16px; border-radius: 8px; margin-bottom: 24px; 
                background: {{ $smartRent->status == 'completed' ? '#d1fae5' : ($smartRent->status == 'pending' ? '#fef3c7' : '#fee2e2') }}; 
                border-left: 4px solid {{ $smartRent->status == 'completed' ? '#10b981' : ($smartRent->status == 'pending' ? '#f59e0b' : '#ef4444') }};">
        <strong>Status:</strong> 
        <span style="color: {{ $smartRent->status == 'completed' ? '#10b981' : ($smartRent->status == 'pending' ? '#f59e0b' : '#ef4444') }};">
            {{ ucfirst($smartRent->status) }}
        </span>
        <span style="color: #6b7280; margin-left: 16px;">Dibuat pada: {{ $smartRent->created_at->format('d M Y H:i') }}</span>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Left Column -->
        <div>
            <!-- Customer Information -->
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; background: white;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 16px 0;">
                    <i class="fas fa-user"></i> Data Pelanggan
                </h5>
                <table style="width: 100%; font-size: 14px;">
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Nama:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->nama_pelanggan }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Telepon:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->telepon }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Email:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->email ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Alamat:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->alamat ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">No. Identitas:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->no_identitas ?? '-' }} ({{ $smartRent->jenis_identitas ?? '-' }})</td>
                    </tr>
                </table>
            </div>

            <!-- Booking Information -->
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; background: white;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 16px 0;">
                    <i class="fas fa-calendar"></i> Data Pemesanan
                </h5>
                <table style="width: 100%; font-size: 14px;">
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Tanggal Mulai:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->tanggal_mulai->format('d M Y') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Tanggal Selesai:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->tanggal_selesai->format('d M Y') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Durasi:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->durasi }} hari</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Rute:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->kota_asal }} → {{ $smartRent->kota_tujuan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Catatan:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->catatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Vehicle Information -->
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; background: white;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 16px 0;">
                    <i class="fas fa-car"></i> Data Armada
                </h5>
                @if($smartRent->armada)
                <table style="width: 100%; font-size: 14px;">
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Nama:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->armada->nama }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Tipe:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->armada->tipe }} - {{ $smartRent->armada->kapasitas }} Kursi</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Plat Nomor:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->armada->nomor_polisi }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Harga/Hari:</td>
                        <td style="padding: 12px 0; color: #0d3559;">Rp {{ number_format($smartRent->armada->harga_dasar, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Layanan:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ ucfirst(str_replace('_', ' ', $smartRent->layanan)) }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Jumlah Unit:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->jumlah_mobil }} unit</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Fasilitas:</td>
                        <td style="padding: 12px 0; color: #0d3559;">
                            @if($smartRent->armada->fasilitas)
                                {{ implode(', ', $smartRent->armada->fasilitas) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
                @else
                <p style="color: #6b7280;">Armada tidak ditemukan</p>
                @endif
            </div>

            <!-- Passengers -->
            @if($smartRent->penumpang && count($smartRent->penumpang) > 0)
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; background: white;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 16px 0;">
                    <i class="fas fa-users"></i> Data Penumpang
                </h5>
                <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; text-align: left; color: #0d3559; font-weight: 600;">Nama</th>
                            <th style="padding: 12px; text-align: left; color: #0d3559; font-weight: 600;">NIK</th>
                            <th style="padding: 12px; text-align: left; color: #0d3559; font-weight: 600;">Jenis Kelamin</th>
                            <th style="padding: 12px; text-align: left; color: #0d3559; font-weight: 600;">Telepon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($smartRent->penumpang as $penumpang)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px;">{{ $penumpang['nama'] ?? '-' }}</td>
                            <td style="padding: 12px;">{{ $penumpang['nik'] ?? '-' }}</td>
                            <td style="padding: 12px;">{{ $penumpang['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td style="padding: 12px;">{{ $penumpang['telepon'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Right Column - Summary -->
        <div>
            <!-- Price Summary -->
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; background: white; position: sticky; top: 20px;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 2px solid #ff6a21;">
                    <i class="fas fa-receipt"></i> Ringkasan Pembayaran
                </h5>

                @if($smartRent->armada)
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                        <span style="color: #6b7280;">Harga/Hari:</span>
                        <span style="color: #0d3559; font-weight: 500;">Rp {{ number_format($smartRent->armada->harga_dasar, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                        <span style="color: #6b7280;">Durasi:</span>
                        <span style="color: #0d3559; font-weight: 500;">{{ $smartRent->durasi }} hari</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                        <span style="color: #6b7280;">Jumlah Unit:</span>
                        <span style="color: #0d3559; font-weight: 500;">{{ $smartRent->jumlah_mobil }} unit</span>
                    </div>
                    @if($smartRent->layanan == 'dengan_sopir' && $smartRent->armada->harga_dengan_sopir)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                        <span style="color: #6b7280;">Biaya Sopir/Hari:</span>
                        <span style="color: #0d3559; font-weight: 500;">Rp {{ number_format($smartRent->armada->harga_dengan_sopir, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <div style="padding-top: 16px; border-top: 2px dashed #e5e7eb; margin-top: 16px;">
                    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700;">
                        <span style="color: #0d3559;">Total Bayar:</span>
                        <span style="color: #ff6a21;">Rp {{ number_format($smartRent->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment Information -->
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; background: white;">
                <h5 style="font-size: 16px; font-weight: 700; color: #0d3559; margin: 0 0 16px 0;">
                    <i class="fas fa-credit-card"></i> Pembayaran
                </h5>
                <table style="width: 100%; font-size: 14px;">
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Metode:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->metode_pembayaran }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #6b7280; font-weight: 500;">Petugas:</td>
                        <td style="padding: 12px 0; color: #0d3559;">{{ $smartRent->petugas ?? $smartRent->creator?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .btn {
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e5e7eb;
        background: white;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #374151;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-primary {
        background: #ff6a21;
        color: white;
        border-color: #ff6a21;
    }

    .btn-primary:hover {
        background: #e55d00;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .btn-outline:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
</style>
@endsection
