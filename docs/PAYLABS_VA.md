# Paylabs Virtual Account (VA) v2.3 — SmartShuttle

Dokumen ini fokus untuk **Generate Virtual Account** dan testing via **Postman**.

## 1) Environment yang wajib

Pastikan `.env` berisi (tanpa typo, dan nilainya sesuai akun Paylabs kamu):

- `PAYLABS_ENVIRONMENT=sandbox` (mapping ke SIT)
- `PAYLABS_BASE_URL=https://sit-pay.paylabs.co.id` (untuk SIT)
  - Production: `https://pay.paylabs.co.id`
- `MID=...` (atau `PAYLABS_MID=...`)
- `PRIVATE_KEY=...` (atau `PAYLABS_PRIVATE_KEY=...`) **tanpa header PEM**
- `PUBLIC_KEY=...` (atau `PAYLABS_PUBLIC_KEY=...`) **tanpa header PEM**
- `PAYLABS_CALLBACK_URL=https://<ngrok-domain>/api/payment/callback-v23`

Catatan penting:
- Pastikan `PAYLABS_NOTIFY_URL` / `PAYLABS_CALLBACK_URL` **valid URL**. Kalau pakai ngrok, harus ada `/api/payment/callback-v23`.
- Di repo ini signature dibuat di server; Postman tidak perlu generate signature.

## 2) Endpoint testing (server-side signing)

Semua endpoint ini ada di group `api/dev` dan **public** (untuk testing saja):

### A. Generate Virtual Account

`POST /api/dev/paylabs/va/create`

Body (JSON):
```json
{
  "amount": 10000,
  "paymentType": "BTNVA",
  "payer": "John Doe",
  "productName": "Smart Shuttle Ticket",
  "merchantTradeNo": "VA-TEST-001",
  "notifyUrl": "https://<ngrok-domain>/api/payment/callback-v23",
  "feeType": "BEN",
  "productInfo": [
    {
      "id": "TICKET001",
      "name": "Smart Shuttle Ticket",
      "price": "10000.00",
      "type": "Ticket",
      "quantity": 1
    }
  ]
}
```

Field minimal:
- `amount` (numeric, min 1)
- `paymentType` (string, salah satu: BCAVA, MandiriVA, BNIVA, BRIVA, PermataVA, CIMBVA, DanamonVA, MaybankVA, BTNVA, SinarmasVA, BJBVA, BTPNVA, OCBCVA)
- `payer` (string, max 60 chars)
- `productName` (string, max 100 chars)

Response sukses (contoh struktur):
```json
{
  "http_status": 200,
  "success": true,
  "request": {
    "url": "https://sit-pay.paylabs.co.id/payment/v2.3/va/create",
    "timestamp": "2024-01-15T10:30:45.123+07:00",
    "requestId": "241115103045123"
  },
  "response": {
    "errCode": "0",
    "errCodeDes": "Success",
    "requestId": "241115103045123",
    "merchantId": "010529",
    "platformTradeNo": "PLT20240115103045123",
    "vaCode": "888100001234567890",
    "vaNumber": "888100001234567890",
    "bankName": "BTN",
    "amount": "10000.00",
    "merchantTradeNo": "VA-TEST-001",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045",
    "status": "01",
    "feeType": "BEN",
    "payer": "John Doe"
  }
}
```

### B. Query Virtual Account status

`POST /api/dev/paylabs/va/query`

Body (pilih salah satu: `merchantTradeNo` **atau** `platformTradeNo`):
```json
{
  "merchantTradeNo": "VA-TEST-001"
}
```

Atau:
```json
{
  "platformTradeNo": "PLT20240115103045123"
}
```

Response sukses:
```json
{
  "http_status": 200,
  "success": true,
  "request": {
    "url": "https://sit-pay.paylabs.co.id/payment/v2.3/va/query",
    "timestamp": "2024-01-15T10:35:00.456+07:00",
    "requestId": "241115103500456"
  },
  "response": {
    "errCode": "0",
    "errCodeDes": "Success",
    "requestId": "241115103500456",
    "merchantId": "010529",
    "platformTradeNo": "PLT20240115103045123",
    "vaCode": "888100001234567890",
    "amount": "10000.00",
    "merchantTradeNo": "VA-TEST-001",
    "status": "01",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045",
    "successTime": null
  }
}
```

### C. Cancel Virtual Account

`POST /api/dev/paylabs/va/cancel`

Body:
```json
{
  "merchantTradeNo": "VA-TEST-001",
  "platformTradeNo": "PLT20240115103045123"
}
```

## 3) Callback Paylabs VA v2.3

Paylabs akan memanggil notify URL kamu (yang kamu kirim di create) ke:

`POST /api/payment/callback-v23`

Implementasi ada di controller:
- `callbackV23()` di [app/Http/Controllers/API/PaymentController.php](../app/Http/Controllers/API/PaymentController.php)

Catatan signature callback:
- String yang diverifikasi: `POST:{PATH}:{sha256(raw_body)}:{X-TIMESTAMP}`
- `{PATH}` diambil dari path URL callback (contoh: `/api/payment/callback-v23`).

## 4) Status Mapping

Virtual Account menggunakan status code:
- `01` = PENDING (menunggu pembayaran)
- `02` = PAID (sudah dibayar)
- `09` = FAILED (gagal)

## 5) Cara test cepat di Postman

1. Jalankan app Laravel (`php artisan serve`) dan jalankan ngrok untuk expose port 8000.
2. Set `.env`:
   - `PAYLABS_BASE_URL=https://sit-pay.paylabs.co.id`
   - `PAYLABS_CALLBACK_URL=https://<ngrok-domain>/api/payment/callback-v23`
3. Postman:
   - Method: `POST`
   - URL: `http://localhost:8000/api/dev/paylabs/va/create`
   - Body: raw JSON (contoh di atas)
4. Lakukan pembayaran ke nomor VA yang dihasilkan menggunakan channel simulator Paylabs (sesuai environment kamu).
5. Lihat logs Laravel (`storage/logs/*.log`) untuk memastikan callback masuk.

## 6) Supported Payment Types

- `BCAVA` - BCA Virtual Account
- `MandiriVA` - Mandiri Virtual Account
- `BNIVA` - BNI Virtual Account
- `BRIVA` - BRI Virtual Account
- `PermataVA` - Permata Virtual Account
- `CIMBVA` - CIMB Virtual Account
- `DanamonVA` - Danamon Virtual Account
- `MaybankVA` - Maybank Virtual Account
- `BTNVA` - BTN Virtual Account
- `SinarmasVA` - Sinarmas Virtual Account
- `BJBVA` - BJB Virtual Account
- `BTPNVA` - BTPN Virtual Account
- `OCBCVA` - OCBC Virtual Account

## 7) API Request Format (Direct to Paylabs)

Untuk testing langsung ke Paylabs API (bukan melalui Laravel endpoint):

### HTTP Headers:
```
Content-Type: application/json;charset=utf-8
X-TIMESTAMP: 2024-01-15T10:30:45.123+07:00
X-SIGNATURE: [generated_signature]
X-PARTNER-ID: [your_mid]
X-REQUEST-ID: [unique_request_id]
```

### HTTP Body:
```json
{
  "merchantId": "010529",
  "merchantTradeNo": "100100011649755895582",
  "requestId": "200100011649755895582",
  "paymentType": "BTNVA",
  "amount": "10000.00",
  "productName": "Test VA",
  "payer": "Test User"
}
```

---

Kalau kamu mau, next step setelah ini: kita sambungkan endpoint VA ini ke flow `createPayment` (berdasarkan `kode_booking`) dan simpan semua field VA (`vaCode`, `vaNumber`, `platformTradeNo`, dll) ke tabel `pembayaran`.
