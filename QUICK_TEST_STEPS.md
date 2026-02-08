# Quick Verification Steps

## 1. Test Route Caching
```powershell
cd c:\laragon\www\smartshuttle
php artisan route:cache
```
✅ Expected: "Routes cached successfully."

## 2. Clear All Caches
```powershell
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 3. Start Server (if needed)
```powershell
# Using Laragon: Already running on http://localhost
# Or manually:
php artisan serve
# Then visit: http://localhost:8000/customer
```

## 4. Test Beranda Search Filtering

### Step A: Load beranda page
- **URL:** `http://localhost/customer` or `http://localhost:8000/customer`
- **Expected:** Page loads with:
  - ✅ "Pilih Kota Asal" dropdown showing: Jakarta, Bandung
  - ✅ "Pilih Kota Tujuan" dropdown showing: Bandung, Semarang
  - ✅ Schedule cards displayed below

### Step B: Test filtering
1. Click "Pilih Kota Asal" dropdown
2. Select "Jakarta"
3. Click "Pilih Kota Tujuan" dropdown
4. Select "Bandung"
5. Click "CEK SHUTTLE"

**Expected Results:**
- ✅ Page reloads with URL: `/customer?asal=Jakarta&tujuan=Bandung`
- ✅ Dropdowns show selected values (Jakarta and Bandung stay selected)
- ✅ Schedule cards show ONLY Jakarta → Bandung routes
- ✅ No "Belum ada jadwal tersedia" message (if data exists)

### Step C: Test search page
- **URL:** `http://localhost/customer/search?asal=Jakarta&tujuan=Bandung`
- **Expected:** Search results page displays matching schedules

## 5. Check Logs for Errors
```powershell
# View recent logs
Get-Content storage\logs\laravel-*.log -Tail 50

# Look for "ERROR" or "Exception" entries
# Should be empty or only show info/debug messages
```

## 6. Database Check (if no results showing)

```powershell
# Open MySQL/database tool and run:
SELECT * FROM driver_jadwals 
WHERE status = 'aktif' 
AND tanggal >= CURDATE()
LIMIT 5;

# You should see records with:
# - status = 'aktif'
# - tanggal >= today's date
# - kursi_terisi < total_kursi (at least 1 seat available)
# - rute like: "Jakarta - Bandung Via Bekasi (Jakarta → Bandung)"
```

## 7. Quick PHP Test (optional)

```powershell
# Run the test file we created
php test_fixed_search.php

# You should see: 
# "✓ Beranda filtering works: YES"
# "✓ Dropdowns populated: YES"  
# "✓ Search filtering works: YES"
```

## Troubleshooting

### Issue: Still showing "Belum ada jadwal tersedia"
**Solutions:**
1. Check database has data: `SELECT COUNT(*) FROM driver_jadwals WHERE status='aktif'`
2. Check dates: `SELECT MIN(tanggal), MAX(tanggal) FROM driver_jadwals`
3. Check seats: `SELECT * FROM driver_jadwals WHERE kursi_terisi < total_kursi`

### Issue: Dropdowns empty
**Solutions:**
1. Clear caches: `php artisan cache:clear && php artisan view:clear`
2. Check database has active records with future dates
3. Verify rute field format: `SELECT DISTINCT rute FROM driver_jadwals LIMIT 3`

### Issue: After selecting cities, no results appear
**Solutions:**
1. Check the rute field contains the selected city names
2. Verify date is >= today
3. Check seats available: `kursi_terisi < total_kursi`

## Success Indicators

You'll know everything is fixed when:
- ✅ Route caching works without errors
- ✅ Beranda page loads with dropdowns populated
- ✅ Selecting cities in dropdown filters the schedule cards
- ✅ Search page displays matching results
- ✅ No SQL/database errors in logs
- ✅ Selected values persist in dropdown after filtering

---

**Created:** February 8, 2026
**Status:** All fixes implemented and tested
