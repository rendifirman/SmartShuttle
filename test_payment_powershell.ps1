# SMARTSHUTTLE PAYMENT CREATION TEST - PowerShell Version
Write-Host "=== SMARTSHUTTLE PAYMENT CREATION TEST ===" -ForegroundColor Green
Write-Host ""

# Step 1: Login to get token
Write-Host "Step 1: Logging in to get authentication token..." -ForegroundColor Yellow

try {
    $loginBody = @{
        email = "test@example.com"
        password = "password"
    } | ConvertTo-Json

    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method POST -Body $loginBody -ContentType "application/json"

    if ($loginResponse.success -eq $true) {
        $token = $loginResponse.token
        Write-Host "✓ Login successful! Token: $($token.Substring(0, 20))..." -ForegroundColor Green
        Write-Host ""
    } else {
        Write-Host "✗ Login failed: $($loginResponse.message)" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "✗ Login error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Make sure your Laravel server is running: php artisan serve" -ForegroundColor Yellow
    exit 1
}

# Step 2: Test payment creation with existing booking
Write-Host "Step 2: Testing payment creation with existing booking..." -ForegroundColor Yellow

$testBookings = @("BOOK123456", "BOOK001", "BOOK002")

foreach ($kodeBooking in $testBookings) {
    Write-Host "Testing with booking code: $kodeBooking" -ForegroundColor Cyan

    try {
        $paymentBody = @{
            kode_booking = $kodeBooking
            payment_method = "qris"
        } | ConvertTo-Json

        $headers = @{
            "Authorization" = "Bearer $token"
            "Content-Type" = "application/json"
        }

        $paymentResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/payment/create" -Method POST -Headers $headers -Body $paymentBody

        if ($paymentResponse.success -eq $true) {
            Write-Host "✓ Payment creation successful!" -ForegroundColor Green
            Write-Host "Payment Code: $($paymentResponse.data.payment.kode_pembayaran)" -ForegroundColor White
            Write-Host "Amount: $($paymentResponse.data.payment.jumlah)" -ForegroundColor White
            Write-Host "Status: $($paymentResponse.data.payment.status)" -ForegroundColor White
            Write-Host "QR Code: $($paymentResponse.data.payment.qr_code)" -ForegroundColor White
            Write-Host ""
            break
        } else {
            Write-Host "✗ Payment creation failed for $kodeBooking" -ForegroundColor Red
            Write-Host "Response: $($paymentResponse | ConvertTo-Json)" -ForegroundColor Red
            Write-Host ""
        }

    } catch {
        Write-Host "✗ Error testing $kodeBooking : $($_.Exception.Message)" -ForegroundColor Red
        Write-Host ""
    }
}

# Step 3: Show available payment methods
Write-Host "Step 3: Getting available payment methods..." -ForegroundColor Yellow

try {
    $methodsResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/payment/methods" -Method GET

    if ($methodsResponse.success -eq $true) {
        Write-Host "Available payment methods:" -ForegroundColor Green
        $methodsResponse.data | ForEach-Object {
            Write-Host "  - $($_.nama) ($($_.kode))" -ForegroundColor White
        }
    } else {
        Write-Host "Failed to get payment methods" -ForegroundColor Red
    }

} catch {
    Write-Host "Error getting payment methods: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== MANUAL TESTING INSTRUCTIONS ===" -ForegroundColor Green
Write-Host "If the automated test above doesn't work, try these manual steps:" -ForegroundColor White
Write-Host ""
Write-Host "1. Start your Laravel server:" -ForegroundColor Yellow
Write-Host "   php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "2. Use PowerShell to test (copy and modify these commands):" -ForegroundColor Yellow
Write-Host ""
Write-Host "   # Login" -ForegroundColor Cyan
Write-Host "   `$loginBody = @{email='test@example.com'; password='password'} | ConvertTo-Json" -ForegroundColor White
Write-Host "   `$loginResponse = Invoke-RestMethod -Uri 'http://localhost:8000/api/login' -Method POST -Body `$loginBody -ContentType 'application/json'" -ForegroundColor White
Write-Host "   `$token = `$loginResponse.token" -ForegroundColor White
Write-Host ""
Write-Host "   # Create payment" -ForegroundColor Cyan
Write-Host "   `$paymentBody = @{kode_booking='YOUR_BOOKING_CODE'; payment_method='qris'} | ConvertTo-Json" -ForegroundColor White
Write-Host "   `$headers = @{Authorization='Bearer ' + `$token; 'Content-Type'='application/json'}" -ForegroundColor White
Write-Host "   `$paymentResponse = Invoke-RestMethod -Uri 'http://localhost:8000/api/payment/create' -Method POST -Headers `$headers -Body `$paymentBody" -ForegroundColor White
Write-Host ""
Write-Host "3. Or use a GUI tool like Postman/Insomnia with these endpoints:" -ForegroundColor Yellow
Write-Host "   POST http://localhost:8000/api/login" -ForegroundColor White
Write-Host "   POST http://localhost:8000/api/payment/create" -ForegroundColor White
Write-Host "   GET http://localhost:8000/api/payment/status/{code}" -ForegroundColor White
Write-Host ""
Write-Host "=== TEST COMPLETED ===" -ForegroundColor Green
