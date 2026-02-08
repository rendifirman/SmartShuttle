# Search Jadwal Fix - Testing & Verification Guide

## Issue Fixed
The search feature was showing "jadwal tidak tersedia" even when matching schedules existed in the `driver_jadwals` table.

## Root Causes Identified & Fixed

### 1. **Dropdown Parsing Issue (FIXED)**
**Problem:** The search() method was using naive `explode('→',...)` on rute fields like:
```
'Jakarta - Bandung Via Bekasi (Jakarta → Bandung)'
```
This would incorrectly extract: `"Jakarta - Bandung Via Bekasi (Jakarta"` instead of just `"Jakarta"`

**Solution:** Changed dropdown generation to use `getDetailRute()` method which properly parses the rute field format

**File:** `/app/Http/Controllers/CustomerController.php` - Lines 1238-1253

### 2. **Query Filter Improvement (FIXED)**
**Problem:** LIKE query matching was too broad and could fail with certain rute formats

**Solution:** 
- Load relationships: `with(['jadwal.rutes', 'driver'])`
- Improved LIKE pattern matching for asal field:
  ```php
  where('rute', 'like', '%(' . $asal . '%')
  ->orWhere('rute', 'like', '% ' . $asal . '%')
  ```
- This correctly matches the format: `"(Jakarta → " or " Jakarta "`

**File:** `/app/Http/Controllers/CustomerController.php` - Lines 1205-1235

### 3. **Collection Method Syntax (FIXED)**
**Problem:** `unique('asal')` called on string collection (invalid syntax)

**Solution:** Changed to `unique()` without parameter for string collections

**File:** `/app/Http/Controllers/CustomerController.php` - Lines 495-510

### 4. **Debugging Logging (IMPLEMENTED)**
**Added:** Comprehensive logging in search() method to track:
- Request parameters
- Extracted filter values
- Query SQL and bindings
- Result counts

**File:** `/app/Http/Controllers/CustomerController.php` - Lines 1173-1190

## How to Test the Fix

### Test Setup
1. Ensure you have test data in `driver_jadwals` table with:
   - `status = 'aktif'`
   - `tanggal >= today's date`
   - `kursi_terisi < total_kursi` (at least 1 available seat)
   - `rute` field in format: `Name (City → City)` e.g., `"Jakarta - Bandung Via Bekasi (Jakarta → Bandung)"`

### Test Case 1: Visit Beranda
1. Navigate to `/customer` (beranda page)
2. Check that "Pilih Kota Asal" dropdown shows available cities
3. Check that "Pilih Kota Tujuan" dropdown shows available cities
4. **Expected:** Dropdowns populated with unique cities from driver_jadwals

### Test Case 2: Submit Search Form
1. From beranda, select:
   - Kota Asal: `Jakarta`
   - Kota Tujuan: `Bandung`
   - Tanggal: (leave empty or select future date)
   - Penumpang: `1`
2. Click "CEK SHUTTLE"
3. **Expected:** Redirected to search results page with matching results displayed

### Test Case 3: Check Logs
When search is executed:
1. Open `storage/logs/laravel-*.log`
2. Look for entries: `CustomerController::search() called`
3. **Expected:** Should see:
   ```
   all_params: {"asal": "Jakarta", "tujuan": "Bandung", ...}
   has_asal: true
   has_tujuan: true
   Search parameters extracted: asal="Jakarta", tujuan="Bandung", ...
   Search query executed: total_results=X
   ```

### Common Issues & Solutions

#### Issue: "Jadwal tidak tersedia" still showing
**Checklist:**
- [ ] Is `driver_jadwals` table populated with test data?
- [ ] Do records have `status = 'aktif'`?
- [ ] Are record dates >= today's date? (Check: `SELECT MIN(tanggal), MAX(tanggal) FROM driver_jadwals`)
- [ ] Do records have available seats? (Check: `SELECT * FROM driver_jadwals WHERE kursi_terisi < total_kursi`)
- [ ] Is rute field in correct format? (Should be: `Name (City → City)`)

#### Issue: Dropdowns showing wrong values
**Solution:** Clear session and cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### Issue: Dropdowns showing partial city names
**Cause:** getDetailRute() might be falling back to incorrect parsing  
**Solution:** Check rute field format in database - verify it matches expected pattern

## Data Requirements

For the search to work, ensure `driver_jadwals` records have:

```sql
-- Check your rute format
SELECT DISTINCT rute FROM driver_jadwals LIMIT 5;

-- Should return something like:
-- "Jakarta - Bandung Via Bekasi (Jakarta → Bandung)"
-- "(City1 → City2)"

-- Verify date range
SELECT DATE(MIN(tanggal)) as min_date, DATE(MAX(tanggal)) as max_date FROM driver_jadwals;

-- Should show recent/future dates

-- Check active records with available seats
SELECT COUNT(*) FROM driver_jadwals 
WHERE status = 'aktif' 
AND tanggal >= CURDATE()
AND kursi_terisi < total_kursi;

-- Should return count > 0
```

## Debug Commands

### Quick Database Check
```bash
php artisan tinker

# Check total records
>>> App\Models\DriverJadwal::count()

# Check active and available
>>> App\Models\DriverJadwal::where('status', 'aktif')->where('tanggal', '>=', now()->toDateString())->count()

# Check unique cities
>>> App\Models\DriverJadwal::get()->map(fn($dj) => $dj->getDetailRute()['kota_asal'])->unique()->values()

# Simulate search
>>> $dj = App\Models\DriverJadwal::where('rute', 'like', '%Jakarta%')->first()
>>> $dj->getDetailRute()
```

### Check Logs
```bash
# Tail logs to see requests in real-time
tail -f storage/logs/laravel-*.log | grep "search()"
```

## Fixed Files Summary

1. **`app/Http/Controllers/CustomerController.php`**
   - Lines 495-510: Fixed unique() syntax in beranda()
   - Lines 1173-1190: Added logging in search()
   - Lines 1205-1235: Improved query with relationships and LIKE patterns
   - Lines 1238-1253: Fixed dropdown generation using getDetailRute()

2. **Tests Created (for reference)**
   - `/test_search_debug.php` - Initial debugging
   - `/test_improved_search.php` - Verify improved logic
   - `/verify_search_flow.php` - Complete flow verification

## Next Steps

If search still doesn't work after these fixes:

1. **Check logs** at `storage/logs/laravel-*.log` for error messages
2. **Verify database data** with the SQL queries above
3. **Clear all cache** with the commands above
4. **Test with fresh data** - create new DriverJadwal records if needed
5. **Post log excerpts** showing the failed search attempt for further debugging

## Expected Behavior After Fix

✅ Dropdowns on beranda show available cities from driver_jadwals  
✅ Form submissions are properly logged  
✅ Search queries return matching results  
✅ Results display in search.blade.php without "jadwal tidak tersedia" message  
✅ User can click "Pesan Sekarang" to proceed to booking

---

**Note:** This fix ensures the search uses ONLY the `driver_jadwals` table (not the legacy `jadwals` table), as per requirements: "Semua data jadwal untuk customer WAJIB diambil dari tabel: driver_jadwals"
