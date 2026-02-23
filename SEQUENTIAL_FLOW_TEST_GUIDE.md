# Phase 2 Sequential Flow - Quick Test Script

This script demonstrates the sequential flow enforcement in action.

## Prerequisites

```bash
cd c:\laragon\www\smartshuttle
php artisan migrate  # Already done in Phase 1
```

## Test 1: Valid Sequential Flow (Driver Jadwal)

```bash
# Start at /customer/pesan/{id_jadwal_driver}
# This displays the booking form (Step 1)

# Simulate: User fills form and submits
# POST /customer/pemesanan/proses with:
{
    "id_jadwal_driver": 3,
    "jumlah_penumpang": 2,
    "nama_pemesan": "Test User",
    "telepon_pemesan": "081234567890",
    "email_pemesan": "test@example.com",
    "penumpang": [
        {
            "nama_lengkap": "Passenger 1",
            "nik": "1234567890123456",
            "jenis_kelamin": "L"
        },
        {
            "nama_lengkap": "Passenger 2",
            "nik": "1234567890123457",
            "jenis_kelamin": "P"
        }
    ]
}

# Expected Result:
# ✅ Pemesanan created with status='menunggu_kursi'
# ✅ DetailPenumpang created (2 records) with nomor_kursi=NULL
# ✅ Redirect to /customer/kursi?pemesanan_id=1
# ✅ Database: 
#    - pemesanan.id=1, status='menunggu_kursi', id_jadwal_driver=3
#    - detail_penumpang.nomor_kursi=NULL (both records)
#    - driver_jadwals kursi_terisi=UNCHANGED (not updated yet)
```

## Test 2: Seat Selection (Driver Jadwal)

```bash
# User is redirected to /customer/kursi?pemesanan_id=1
# This shows seat layout (Step 2 display)

# Simulate: User selects seats and submits
# POST /customer/kursi/proses with:
{
    "pemesanan_id": 1,
    "kursi": ["1", "2"]
}

# Expected Result:
# ✅ Validation passed (status='menunggu_kursi')
# ✅ DetailPenumpang updated with nomor_kursi=1,2
# ✅ DriverJadwal kursi_terisi incremented by 2
# ✅ Pemesanan status changed to 'menunggu_konfirmasi'
# ✅ Redirect to /customer/detail-pemesanan/{kode_booking}
# ✅ Database:
#    - pemesanan.status='menunggu_konfirmasi'
#    - detail_penumpang[0].nomor_kursi='1'
#    - detail_penumpang[1].nomor_kursi='2'
#    - driver_jadwals.kursi_terisi+=2
```

## Test 3: View Detail & Confirm (Step 3)

```bash
# User is redirected to /customer/detail-pemesanan/{kode_booking}
# This shows booking summary (Step 3 display)

# Simulate: User confirms details
# POST /customer/detail-pemesanan/{kode_booking}/konfirmasi

# Expected Result:
# ✅ Status validation passed (status='menunggu_konfirmasi')
# ✅ All passengers have seat assignments
# ✅ Pemesanan status changed to 'menunggu_pembayaran'
# ✅ Redirect to /customer/pembayaran/{kode_booking}
# ✅ Database:
#    - pemesanan.status='menunggu_pembayaran'
```

## Test 4: Payment Processing (Step 4)

```bash
# User is redirected to /customer/pembayaran/{kode_booking}
# This shows payment options (Step 4)

# Simulate: User selects payment method and completes payment
# POST /customer/pembayaran/pilih-metode/{kode_booking} with:
{
    "metode": "qris"
}

# [Paylabs integration processes payment]
# [On success callback: updatePemesananAfterPayment()]

# Expected Result:
# ✅ Pemesanan status changed to 'dibayar'
# ✅ Transaksi record created
# ✅ Redirect to e-ticket or riwayat
# ✅ Database:
#    - pemesanan.status='dibayar'
#    - pemesanan.waktu_pembayaran=NOW()
#    - transaksi record created
```

## Test 5: View E-Ticket (Steps 5-6)

```bash
# User views booking history
# GET /customer/riwayat

# User views e-ticket
# GET /customer/e-ticket/{kode_booking}

# Expected Result:
# ✅ Booking visible with status='dibayar'
# ✅ All passenger details displayed with seat numbers
# ✅ E-ticket shows:
#    - Kode booking: {kode_booking}
#    - Route: {from} → {to}
#    - Date/Time: {date} {time}
#    - Passengers with seats: Passenger 1 (Seat 1), Passenger 2 (Seat 2)
#    - Total price: {total_bayar}
```

## Test 6: URL Tampering Prevention

```bash
# Attacker tries to bypass Step 2 by accessing detail directly
# GET /customer/detail-pemesanan/{kode_booking}

# Current database state: status='menunggu_kursi' (Step 1)

# Expected Result:
# ❌ Error message: "Akses tidak sah. Status pemesanan: menunggu_kursi"
# ❌ Redirect to /customer/beranda
# ❌ User cannot proceed without completing Step 2
```

## Test 7: Payment Before Confirmation

```bash
# Attacker tries to skip Step 3 confirmation
# GET /customer/pembayaran/{kode_booking}

# Current database state: status='menunggu_konfirmasi' (Step 3)

# Expected Result:
# ❌ Error/Info message displayed
# ❌ User must complete Step 3 first
# ❌ Redirect to detail_pesanan or error display
```

## Test 8: Data Consistency Check

```bash
# After completing a full booking cycle, verify:

SELECT 
    p.kode_booking,
    p.status,
    p.jumlah_penumpang,
    d.nomor_kursi,
    dj.kursi_terisi,
    t.kode_transaksi
FROM pemesanan p
LEFT JOIN detail_penumpang d ON p.id = d.pemesanan_id
LEFT JOIN driver_jadwals dj ON p.id_jadwal_driver = dj.id_jadwal_driver
LEFT JOIN transaksi t ON p.id = t.pemesanan_id
WHERE p.kode_booking = '{kode_booking}'
ORDER BY d.id;

# Expected Result:
# ✅ Exactly 2 rows (one per passenger)
# ✅ All d.nomor_kursi have values ('1', '2')
# ✅ p.status = 'dibayar'
# ✅ dj.kursi_terisi incremented by 2
# ✅ t.kode_transaksi populated
# ✅ No NULL values in required fields
```

## Automated Test - Using Artisan

```bash
# Test database integrity
php artisan tinker << 'EOF'
$pemesanan = \App\Models\Pemesanan::where('status', 'dibayar')->first();
if ($pemesanan) {
    echo "✅ Found completed booking: " . $pemesanan->kode_booking . "\n";
    echo "   - Penumpang: " . $pemesanan->detailPenumpang->count() . "\n";
    echo "   - Kursi terpilih: " . $pemesanan->detailPenumpang->pluck('nomor_kursi')->implode(', ') . "\n";
    echo "   - Status: " . $pemesanan->status . "\n";
    if ($pemesanan->driverJadwal) {
        echo "   - Using driver_jadwals (NEW FLOW) ✅\n";
        echo "   - Total kursi terisi: " . $pemesanan->driverJadwal->kursi_terisi . "\n";
    } else {
        echo "   - Using legacy jadwals (OLD FLOW)\n";
    }
} else {
    echo "❌ No completed bookings found in database\n";
}
EOF
```

## Manual Testing Checklist

- [ ] Create booking with driver_jadwal schedule
- [ ] Verify status='menunggu_kursi' after Step 1
- [ ] Select seats and verify kursi_terisi updated
- [ ] Verify status='menunggu_konfirmasi' after Step 2
- [ ] Confirm details and verify status='menunggu_pembayaran'
- [ ] Try accessing payment with valid status
- [ ] Try accessing detail_pesanan after Step 3 confirmation
- [ ] Try accessing detail_pesanan before completing Step 2 (should fail)
- [ ] Complete payment and verify status='dibayar'
- [ ] Verify e-ticket shows all passenger details with seats
- [ ] Verify booking appears in riwayat
- [ ] Test with legacy jadwal booking (backward compatibility)

## Expected Database States

### After Step 1

```sql
SELECT id, status, id_jadwal_driver, jumlah_penumpang FROM pemesanan 
WHERE kode_booking = 'BK20260209001';

-- Expected:
-- id=1, status='menunggu_kursi', id_jadwal_driver=3, jumlah_penumpang=2

SELECT pemesanan_id, nama_lengkap, nomor_kursi FROM detail_penumpang 
WHERE pemesanan_id = 1;

-- Expected:
-- pemesanan_id=1, nama_lengkap='Passenger 1', nomor_kursi=NULL
-- pemesanan_id=1, nama_lengkap='Passenger 2', nomor_kursi=NULL
```

### After Step 2

```sql
SELECT id, status, id_jadwal_driver FROM pemesanan 
WHERE kode_booking = 'BK20260209001';

-- Expected:
-- id=1, status='menunggu_konfirmasi', id_jadwal_driver=3

SELECT pemesanan_id, nama_lengkap, nomor_kursi FROM detail_penumpang 
WHERE pemesanan_id = 1;

-- Expected:
-- pemesanan_id=1, nama_lengkap='Passenger 1', nomor_kursi='1'
-- pemesanan_id=1, nama_lengkap='Passenger 2', nomor_kursi='2'

SELECT id_jadwal_driver, kursi_terisi, total_kursi FROM driver_jadwals 
WHERE id_jadwal_driver = 3;

-- Expected: kursi_terisi incremented by 2
```

### After Step 3

```sql
SELECT id, status FROM pemesanan WHERE kode_booking = 'BK20260209001';

-- Expected: status='menunggu_pembayaran'
```

### After Step 4 (Payment)

```sql
SELECT id, status, waktu_pembayaran, metode_pembayaran FROM pemesanan 
WHERE kode_booking = 'BK20260209001';

-- Expected: status='dibayar', waktu_pembayaran=NOW(), metode_pembayaran='qris'

SELECT pemesanan_id, kode_transaksi, jumlah FROM transaksi 
WHERE pemesanan_id = 1;

-- Expected: 1 row with kode_transaksi populated
```

## Key Validation Points to Verify

### ✅ Status Validation Working

```bash
# Test 1: Try to access Step 3 from Step 1
GET /customer/detail-pemesanan/{kode_booking}  # status='menunggu_kursi'
# Should FAIL with error

# Test 2: Try to access Step 4 from Step 2
GET /customer/pembayaran/{kode_booking}  # status='menunggu_konfirmasi'
# Should redirect or show info message

# Test 3: Access Step 2 from Step 1 (ALLOWED)
GET /customer/kursi?pemesanan_id=1  # status='menunggu_kursi'
# Should SUCCEED ✅
```

### ✅ Data Integrity

```bash
# Verify no orphaned records
SELECT COUNT(*) FROM detail_penumpang dp
WHERE NOT EXISTS (SELECT 1 FROM pemesanan p WHERE p.id = dp.pemesanan_id);
# Expected: 0 rows

# Verify seat assignments are unique per jadwal
SELECT COUNT(*) FROM (
    SELECT dp.nomor_kursi 
    FROM detail_penumpang dp
    JOIN pemesanan p ON dp.pemesanan_id = p.id
    WHERE p.id_jadwal_driver = 3
    GROUP BY dp.nomor_kursi
    HAVING COUNT(*) > 1
) t;
# Expected: 0 rows (no duplicate seats per schedule)
```

### ✅ Seat Availability Accuracy

```bash
# For driver_jadwals (NEW FLOW)
SELECT 
    dj.id_jadwal_driver,
    dj.total_kursi,
    dj.kursi_terisi,
    COUNT(DISTINCT dp.pemesanan_id) as booking_count,
    SUM(p.jumlah_penumpang) as total_assigned
FROM driver_jadwals dj
LEFT JOIN detail_penumpang dp ON dj.id_jadwal_driver = (
    SELECT id_jadwal_driver FROM pemesanan WHERE id = dp.pemesanan_id
)
LEFT JOIN pemesanan p ON dp.pemesanan_id = p.id
WHERE dj.id_jadwal_driver = 3
GROUP BY dj.id_jadwal_driver;

# Expected: kursi_terisi = total_assigned (for your test booking)
```

---

## Troubleshooting Guide

### Issue: "Access denied - Invalid status"

**Cause**: Trying to access a step without completing previous steps  
**Check**: `SELECT status FROM pemesanan WHERE kode_booking='...'`  
**Fix**: Complete all previous steps in order or use different booking

### Issue: Seats not updating in driver_jadwals

**Cause**: Using legacy jadwal flow instead of driver_jadwals workflow  
**Check**: `SELECT id_jadwal_driver FROM pemesanan WHERE id=...`  
**Fix**: Ensure booking uses `id_jadwal_driver` and seats updated in Step 2

### Issue: Duplicate seat selection

**Cause**: Multiple bookings selecting same seat number  
**Check**: Run seat uniqueness verification query  
**Fix**: Verify prosesPemilihanKursi checks other bookings' selections

### Issue: Kursi_terisi mismatch

**Cause**: Manual database edits or inconsistent booking cancellations  
**Check**: Run seat availability accuracy query  
**Fix**: Audit detail_penumpang records and recalculate kursi_terisi

---

## Performance Notes

- [ ] Status checks are indexed (@index on status column)
- [ ] Seat queries filter by id_jadwal_driver (indexed)
- [ ] Owned-by-user checks are fast (indexed on customer_id)
- [ ] No N+1 queries - using eager loading (with)
- [ ] Database transactions prevent race conditions
- [ ] Seat availability updates happen in Step 2 (immediately visible)

---

## Regression Testing

Before deploying to production, test:

1. [ ] Legacy jadwal bookings still work
2. [ ] Old bookings can still be viewed in riwayat
3. [ ] E-tickets generated for both booking sources
4. [ ] Payment system works unchanged
5. [ ] Search returns both driver_jadwals and jadwals schedules
6. [ ] Admin can view all bookings regardless of source
7. [ ] Cancellation logic works for both flows
8. [ ] Reports and analytics still accurate

---

**Test Coverage**: ✅ 8 test scenarios + 8 validation checks + regression tests

**All tests should PASS before deployment**
