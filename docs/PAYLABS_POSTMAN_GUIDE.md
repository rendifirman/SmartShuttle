# Paylabs End-to-End Testing Guide via Postman

## Overview

This guide provides comprehensive instructions for performing end-to-end testing of Paylabs integration using Postman. The testing focuses on **REAL API FLOW** (not mock/simulated), where requests go through Laravel Controller → PaylabsService → Paylabs sandbox API → real response back to Postman.

**Environment:** Sandbox (`PAYLABS_TESTING=false`)  
**Purpose:** Report to supervisor with real Paylabs responses

## Prerequisites

### 1. Environment Setup

Ensure your `.env` file contains the following (replace with your actual Paylabs credentials):

```env
PAYLABS_ENVIRONMENT=sandbox
PAYLABS_BASE_URL=https://sit-paylabs.co.id
PAYLABS_MID=your_merchant_id
PAYLABS_PRIVATE_KEY=your_private_key_without_pem_headers
PAYLABS_PUBLIC_KEY=your_public_key_without_pem_headers
PAYLABS_CALLBACK_URL=https://your-ngrok-domain.ngrok.io/api/payment/callback-v23
```

### 2. Laravel Application

- Start Laravel server: `php artisan serve`
- Expose local server using ngrok: `ngrok http 8000`
- Update `PAYLABS_CALLBACK_URL` with ngrok domain

### 3. Postman Setup

- Import the collection or create requests manually
- Set base URL: `http://localhost:8000`

## Testing Endpoints

All testing endpoints are under `/api/dev/paylabs/` prefix and are **public** (no authentication required).

### 1. Create QRIS Payment

**Endpoint:** `POST /api/dev/paylabs/qris/create`

**Headers:**
```
Content-Type: application/json
```

**Request Body (JSON):**
```json
{
  "amount": 10000,
  "productName": "Smart Shuttle Ticket Test",
  "merchantTradeNo": "QRIS-TEST-001",
  "notifyUrl": "https://your-ngrok-domain.ngrok.io/api/payment/callback-v23",
  "feeType": "BEN"
}
```

**Successful Response (HTTP 200):**
```json
{
  "success": true,
  "http_status": 200,
  "request": {
    "url": "https://sit-paylabs.co.id/payment/v2.3/qris/create",
    "timestamp": "2024-01-15T10:30:45.123+07:00",
    "requestId": "241115103045123"
  },
  "response": {
    "errCode": "0",
    "errCodeDes": "Success",
    "requestId": "241115103045123",
    "merchantId": "010529",
    "platformTradeNo": "PLT20240115103045123",
    "qrCode": "00020101021126660014ID.LINKAJA.WWW011893600898000000000000000021150000000000000000000152041234530310005802ID6007Jakarta61051234562850110Test QRIS6304ABCD",
    "qrisUrl": "https://sit-paylabs.co.id/qris/PLT20240115103045123",
    "nmid": "ID1021234567890",
    "status": "01",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045"
  }
}
```

### 2. Check QRIS Payment Status

**Endpoint:** `POST /api/dev/paylabs/qris/query`

**Headers:**
```
Content-Type: application/json
```

**Request Body (JSON):**
```json
{
  "merchantTradeNo": "QRIS-TEST-001"
}
```

**Successful Response (HTTP 200):**
```json
{
  "success": true,
  "http_status": 200,
  "request": {
    "url": "https://sit-paylabs.co.id/payment/v2.3/qris/query",
    "timestamp": "2024-01-15T10:35:00.456+07:00",
    "requestId": "241115103500456"
  },
  "response": {
    "errCode": "0",
    "errCodeDes": "Success",
    "requestId": "241115103500456",
    "merchantId": "010529",
    "platformTradeNo": "PLT20240115103045123",
    "merchantTradeNo": "QRIS-TEST-001",
    "amount": "10000.00",
    "status": "01",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045",
    "successTime": null
  }
}
```

### 3. Create Virtual Account Payment

**Endpoint:** `POST /api/dev/paylabs/va/create`

**Headers:**
```
Content-Type: application/json
```

**Request Body (JSON) - BCA Example:**
```json
{
  "amount": 10000,
  "paymentType": "BCAVA",
  "payer": "John Doe",
  "productName": "Smart Shuttle Ticket",
  "merchantTradeNo": "VA-BCA-TEST-001",
  "notifyUrl": "https://your-ngrok-domain.ngrok.io/api/payment/callback-v23",
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

**Request Body (JSON) - BRI Example:**
```json
{
  "amount": 25000,
  "paymentType": "BRIVA",
  "payer": "Jane Smith",
  "productName": "Smart Shuttle Premium",
  "merchantTradeNo": "VA-BRI-TEST-001",
  "notifyUrl": "https://your-ngrok-domain.ngrok.io/api/payment/callback-v23",
  "feeType": "BEN"
}
```

**Successful Response (HTTP 200):**
```json
{
  "success": true,
  "http_status": 200,
  "request": {
    "url": "https://sit-paylabs.co.id/payment/v2.3/va/create",
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
    "bankName": "BCA",
    "amount": "10000.00",
    "merchantTradeNo": "VA-BCA-TEST-001",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045",
    "status": "01",
    "feeType": "BEN",
    "payer": "John Doe"
  }
}
```

### 4. Check Virtual Account Payment Status

**Endpoint:** `POST /api/dev/paylabs/va/query`

**Headers:**
```
Content-Type: application/json
```

**Request Body (JSON):**
```json
{
  "merchantTradeNo": "VA-BCA-TEST-001"
}
```

**Successful Response (HTTP 200):**
```json
{
  "success": true,
  "http_status": 200,
  "request": {
    "url": "https://sit-paylabs.co.id/payment/v2.3/va/query",
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
    "merchantTradeNo": "VA-BCA-TEST-001",
    "status": "01",
    "createTime": "20240115103045",
    "expiredTime": "20240115113045",
    "successTime": null
  }
}
```

## Testing Flow

### Step-by-Step Testing Process

1. **Create Payment** (QRIS or VA)
   - Send create request to respective endpoint
   - Verify response has `errCode: "0"` and payment details
   - Note down `platformTradeNo` and `merchantTradeNo`

2. **Perform Payment** (Real Transaction)
   - **QRIS:** Scan the `qrCode` or visit `qrisUrl` and complete payment using Paylabs simulator
   - **VA:** Transfer money to the generated `vaNumber` using bank simulator

3. **Check Status** (After Payment)
   - Query the status using `/query` endpoint
   - Verify status changes from `"01"` (PENDING) to `"02"` (PAID)
   - Check callback logs in Laravel (`storage/logs/laravel.log`)

4. **Verify Callback**
   - Check Laravel logs for callback reception
   - Verify callback signature validation
   - Confirm payment status update in database

## Distinguishing Real vs Simulated Responses

### Real Paylabs Responses (Sandbox)
- **HTTP Status:** 200 (success) or appropriate error codes
- **Structure:** Contains `errCode`, `errCodeDes`, `requestId`
- **Success:** `errCode: "0"`, `errCodeDes: "Success"`
- **Real Data:** Actual `platformTradeNo`, `vaCode`, `qrCode` from Paylabs
- **Timing:** Responses take 1-3 seconds (network latency)

### Simulated/Mock Responses
- **HTTP Status:** Usually 200
- **Structure:** May lack proper Paylabs response format
- **Success:** May use different success indicators
- **Fake Data:** Generated locally, not from Paylabs API
- **Timing:** Instant responses (< 100ms)

## Important Parameters

### Mandatory Parameters

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `amount` | number | Payment amount (min 1) | `10000` |
| `merchantTradeNo` | string | Unique transaction ID (max 32 chars) | `"QRIS-TEST-001"` |
| `notifyUrl` | string | Callback URL for notifications | `"https://domain.ngrok.io/api/payment/callback-v23"` |

### QRIS Specific
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `productName` | string | Product description (max 100 chars) | `"Smart Shuttle Ticket"` |
| `feeType` | string | Fee type: `BEN` (beneficiary) or `OUR` (originator) | `"BEN"` |

### VA Specific
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `paymentType` | string | VA type (see supported types below) | `"BCAVA"` |
| `payer` | string | Customer name (max 60 chars) | `"John Doe"` |
| `productName` | string | Product description (max 100 chars) | `"Smart Shuttle Ticket"` |
| `feeType` | string | Fee type: `BEN` or `OUR` | `"BEN"` |

### Supported Virtual Account Types
- `BCAVA` - BCA Virtual Account
- `BRIVA` - BRI Virtual Account
- `MandiriVA` - Mandiri Virtual Account
- `BNIVA` - BNI Virtual Account
- `PermataVA` - Permata Virtual Account
- `CIMBVA` - CIMB Virtual Account
- `DanamonVA` - Danamon Virtual Account
- `MaybankVA` - Maybank Virtual Account
- `BTNVA` - BTN Virtual Account
- `SinarmasVA` - Sinarmas Virtual Account
- `BJBVA` - BJB Virtual Account
- `BTPNVA` - BTPN Virtual Account
- `OCBCVA` - OCBC Virtual Account

## Status Mapping

### QRIS Status
- `"01"` = PENDING (waiting for payment)
- `"02"` = PAID (successfully paid)
- `"09"` = FAILED (payment failed)
- `"06"` = CANCELLED (cancelled)

### Virtual Account Status
- `"01"` = PENDING (waiting for transfer)
- `"02"` = PAID (transfer received)
- `"09"` = FAILED (transfer failed)

## Common Errors and Solutions

### HTTP 403 Forbidden
**Cause:** Signature verification failed
**Solutions:**
- Check `PAYLABS_PRIVATE_KEY` and `PAYLABS_PUBLIC_KEY` in `.env`
- Ensure keys don't include PEM headers (`-----BEGIN PRIVATE KEY-----`)
- Verify timestamp format and signature generation

### paramInvalid Error
**Cause:** Invalid or missing required parameters
**Solutions:**
- Check parameter types and formats
- Ensure `amount` is numeric and >= 1
- Verify `merchantTradeNo` is unique and <= 32 characters
- Confirm `notifyUrl` is valid HTTPS URL

### notifyUrl Invalid
**Cause:** Callback URL format or accessibility issues
**Solutions:**
- Ensure URL starts with `https://`
- Verify ngrok domain is active and accessible
- Check that `/api/payment/callback-v23` path exists
- Confirm firewall/proxy allows incoming connections

### HTTP 502 Bad Gateway
**Cause:** Paylabs API connection issues
**Solutions:**
- Verify `PAYLABS_BASE_URL` is correct for sandbox
- Check internet connectivity
- Confirm Paylabs sandbox is operational
- Review Laravel logs for detailed error messages

### HTTP 422 Unprocessable Entity
**Cause:** Laravel validation failed
**Solutions:**
- Check request body JSON syntax
- Verify all required fields are present
- Ensure parameter values meet validation rules
- Review error messages in response for specific field issues

## Testing Checklist

### Pre-Testing
- [ ] Laravel server running (`php artisan serve`)
- [ ] Ngrok tunnel active and domain updated in `.env`
- [ ] Paylabs credentials configured correctly
- [ ] Postman collection ready with all endpoints

### QRIS Testing
- [ ] Create QRIS payment successfully (`errCode: "0"`)
- [ ] QR code and URL generated properly
- [ ] Scan QR and complete payment via simulator
- [ ] Status query shows payment received (`status: "02"`)
- [ ] Callback received and logged in Laravel
- [ ] Payment status updated in database

### VA Testing
- [ ] Create VA payment successfully (`errCode: "0"`)
- [ ] VA number generated for correct bank
- [ ] Transfer money via bank simulator
- [ ] Status query shows payment received (`status: "02"`)
- [ ] Callback received and logged in Laravel
- [ ] Payment status updated in database

### Error Testing
- [ ] Test invalid parameters (should return 422)
- [ ] Test duplicate `merchantTradeNo` (should fail)
- [ ] Test expired payment status
- [ ] Test invalid callback URL

## Troubleshooting Tips

1. **Check Laravel Logs:** `tail -f storage/logs/laravel.log`
2. **Verify Environment:** Run `php artisan tinker` and check config values
3. **Test Connection:** Use `/api/dev/paylabs/test-connection` endpoint
4. **Monitor Network:** Use Postman console to inspect request/response details
5. **Callback Testing:** Use ngrok web interface to monitor callback requests

## Reporting Template

When reporting to supervisor, include:

```
Paylabs Integration Testing Report
==================================

Environment: Sandbox
Date: [YYYY-MM-DD]
Tester: [Your Name]

Test Results:
✅ QRIS Create: PASS - errCode: 0, platformTradeNo: PLT20240115103045123
✅ QRIS Payment: PASS - Status changed from 01 to 02
✅ QRIS Callback: PASS - Received and processed
✅ VA Create: PASS - errCode: 0, vaNumber: 888100001234567890
✅ VA Payment: PASS - Status changed from 01 to 02
✅ VA Callback: PASS - Received and processed

Issues Found:
- [List any issues encountered]

Recommendations:
- [Any improvements or fixes needed]
```

This guide ensures comprehensive testing of the real Paylabs integration flow for accurate reporting and validation.
