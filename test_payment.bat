@echo off
echo === SMARTSHUTTLE PAYMENT CREATION TEST ===
echo.

echo Step 1: Logging in to get authentication token...
curl.exe -s -X POST http://127.0.0.1:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password\"}" > login_response.json

if errorlevel 1 (
    echo ERROR: Failed to connect to server. Make sure Laravel is running.
    goto :end
)

findstr /C:"\"success\":true" login_response.json >nul
if errorlevel 1 (
    echo ERROR: Login failed
    type login_response.json
    goto :end
)

echo Login successful!

REM Extract token from JSON response
for /f "tokens=*" %%i in ('powershell -command "(Get-Content login_response.json | ConvertFrom-Json).token"') do set TOKEN=%%i

echo Token obtained: !TOKEN:~0,20!...
echo.

echo Step 2: Testing payment creation...

REM Test with different booking codes
set BOOKING_CODES=BOOK123456 BOOK001 BOOK002

for %%b in (%BOOKING_CODES%) do (
    echo Testing with booking code: %%b

    curl.exe -s -X POST http://127.0.0.1:8000/api/payment/create ^
      -H "Authorization: Bearer !TOKEN!" ^
      -H "Content-Type: application/json" ^
      -d "{\"kode_booking\":\"%%b\",\"payment_method\":\"qris\"}" > payment_response.json

    findstr /C:"\"success\":true" payment_response.json >nul
    if not errorlevel 1 (
        echo SUCCESS: Payment created!
        powershell -command "$data = Get-Content payment_response.json | ConvertFrom-Json; Write-Host 'Payment Code:' $data.data.payment.kode_pembayaran; Write-Host 'Amount:' $data.data.payment.jumlah; Write-Host 'Status:' $data.data.payment.status"
        goto :success
    ) else (
        echo FAILED for %%b
        type payment_response.json
        echo.
    )
)

echo All test booking codes failed. You may need to create a real booking first.
goto :end

:success
echo.
echo === PAYMENT CREATION TEST SUCCESSFUL ===
echo You can now use the payment code above to test payment status:
echo curl -X GET http://127.0.0.1:8000/api/payment/status/YOUR_PAYMENT_CODE ^
echo   -H "Authorization: Bearer !TOKEN!"

:end
echo.
echo === TEST COMPLETED ===
pause
