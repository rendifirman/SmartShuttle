# Paylabs QRIS (v2.3) — SmartShuttle

Dokumen ini fokus untuk **Generate QRIS dulu** dan testing via **Postman**.

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

### A. Generate QRIS

`POST /api/dev/paylabs/qris/create`

Body (JSON):
```json
{
  "amount": 10000,
  "productName": "Test QRIS",
  "merchantTradeNo": "INV-TEST-001",
  "notifyUrl": "https://<ngrok-domain>/api/payment/callback-v23",
  "feeType": "BEN"
}
```

Field minimal:
- `amount`
- `productName`

Response sukses (contoh struktur):
- `success: true`
- `response.qrCode`
- `response.qrisUrl`
- `response.platformTradeNo`
- `response.status` (01/02/09)

### B. Query status QRIS

`POST /api/dev/paylabs/qris/query`

Body (pilih salah satu: `merchantTradeNo` **atau** `rrn`):
```json
{
  "merchantTradeNo": "INV-TEST-001"
}
```

### C. Cancel QRIS

`POST /api/dev/paylabs/qris/cancel`

Body:
```json
{
  "merchantTradeNo": "INV-TEST-001",
  "platformTradeNo": "2022041200000000026",
  "qrCode": "<optional>",
  "productName": "Test QRIS"
}
```

## 3) Callback Paylabs v2.3

Paylabs akan memanggil notify URL kamu (yang kamu kirim di create) ke:

`POST /api/payment/callback-v23`

Implementasi ada di controller:
- `callbackV23()` di [app/Http/Controllers/API/PaymentController.php](../app/Http/Controllers/API/PaymentController.php)

Catatan signature callback:
- String yang diverifikasi: `POST:{PATH}:{sha256(raw_body)}:{X-TIMESTAMP}`
- `{PATH}` diambil dari path URL callback (contoh: `/api/payment/callback-v23`).

## 4) Cara test cepat di Postman

1. Jalankan app Laravel (`php artisan serve`) dan jalankan ngrok untuk expose port 8000.
2. Set `.env`:
   - `PAYLABS_BASE_URL=https://sit-pay.paylabs.co.id`
   - `PAYLABS_CALLBACK_URL=https://<ngrok-domain>/api/payment/callback-v23`
3. Postman:
   - Method: `POST`
   - URL: `http://localhost:8000/api/dev/paylabs/qris/create`
   - Body: raw JSON (contoh di atas)
4. Scan QRIS / lakukan pembayaran di channel simulator Paylabs (sesuai environment kamu).
5. Lihat logs Laravel (`storage/logs/*.log`) untuk memastikan callback masuk.

---

Kalau kamu mau, next step setelah ini: kita sambungkan endpoint QRIS ini ke flow `createPayment` (berdasarkan `kode_booking`) dan simpan semua field QRIS (`qrCode`, `qrisUrl`, `nmid`, `platformTradeNo`, dll) ke tabel `pembayaran`.
