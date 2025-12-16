<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Smart Shuttle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .error-icon {
            font-size: 80px;
            color: #e53e3e;
            margin-bottom: 20px;
        }
        
        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #e53e3e;
            margin-bottom: 10px;
        }
        
        .error-message {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-primary {
            background: #00215E;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-primary:hover {
            background: #001942;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 33, 94, 0.3);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
            border: 1px solid #ddd;
        }
        
        .btn-secondary:hover {
            background: #e9ecef;
        }
        
        .debug-info {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: left;
            font-size: 12px;
            color: #666;
        }
        
        .debug-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #00215E;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h1 class="error-title">E-Ticket Tidak Dapat Dimuat</h1>
        
        <div class="error-message">
            @if(session('error'))
                {{ session('error') }}
            @else
                Terjadi kesalahan saat memuat e-ticket Anda.
            @endif
        </div>
        
        <div class="button-group">
            <a href="{{ route('customer.riwayat') }}" class="btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            
            <a href="{{ route('customer.dashboardprofile') }}" class="btn-secondary">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
        
        @if(config('app.debug'))
        <div class="debug-info">
            <div class="debug-title">Debug Information:</div>
            <div>Kode Booking: {{ request()->kode_booking ?? 'N/A' }}</div>
            <div>Time: {{ now()->format('Y-m-d H:i:s') }}</div>
            <div>Error: {{ session('error') ?? 'Unknown error' }}</div>
        </div>
        @endif
    </div>
</body>
</html>