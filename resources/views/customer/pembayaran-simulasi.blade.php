<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Simulasi Pembayaran</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px} .box{border:1px solid #ddd;padding:16px;border-radius:6px;margin-bottom:12px}</style>
</head>
<body>
    <h1>Simulasi Pembayaran</h1>

    <div class="box">
        <strong>Hasil:</strong>
        @if(isset($success) && $success)
            <div style="color:green">{{ $message ?? 'Berhasil' }}</div>
        @else
            <div style="color:red">{{ $message ?? 'Gagal' }}</div>
        @endif
    </div>

    <div class="box">
        <h3>Data Pembayaran</h3>
        @if(isset($pembayaran) && $pembayaran)
            <div>No: {{ $pembayaran->kode_pembayaran ?? '-' }}</div>
            <div>Jumlah: {{ $pembayaran->jumlah ?? '-' }}</div>
            <div>Status: {{ $pembayaran->status ?? '-' }}</div>
        @else
            <div>-</div>
        @endif
    </div>

    <div class="box">
        <h3>Detail Payment Data</h3>
        @if(!empty($payment_data))
            <div>Metode: {{ $payment_data['payment_method'] ?? '-' }}</div>
            <div>VA: {{ $payment_data['virtual_account'] ?? '-' }}</div>
            <div>Platform Trade No: {{ $payment_data['platform_trade_no'] ?? '-' }}</div>
            @if(!empty($payment_data['qr_code']))
                <div>QR Code:</div>
                <div><img src="{{ $payment_data['qr_code'] }}" alt="qr" style="max-width:260px"></div>
            @endif
        @else
            <div>Tidak ada data pembayaran tersedia.</div>
        @endif
    </div>

    <div style="margin-top:16px">
        <a href="{{ url()->previous() }}">Kembali</a>
    </div>
</body>
</html>
