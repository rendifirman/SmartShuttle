# QUICK SUMMARY: Kursi Redirect Bug Fix

## Problem
User memilih kursi dan submit → halaman redirect **BACK ke kursi** (bukan detail_pesanan)

Namun kursi sudah tersimpan di database ✓

## Root Cause
🐛 **DOUBLE INCREMENT** pada `driver_jadwal.kursi_terisi`:
- lockSeat() AJAX → increment kursi_terisi ✗ 
- prosesPemilihanKursi() → increment LAGI ✗ (DOUBLE!)

## Solution
1. ✅ Jangan increment kursi_terisi di prosesPemilihanKursi()
2. ✅ Update existing KursiTerpesan (jangan create baru)
3. ✅ Validasi lebih ketat (cek kursi sudah di-lock)
4. ✅ Enhanced logging untuk debugging

## Changes Made
**File: app/Http/Controllers/CustomerController.php**

### Change 1: Enhanced Logging (Line 2606-2650)
```diff
+ \Log::info('prosesPemilihanKursi START', [...]);
+ \Log::info('Validating seats for driver_jadwal', [...]);
+ \Log::warning('VALIDATION FAILED: ...', [...]);
```

### Change 2: Fix KursiTerpesan Update (Line 2820-2870)
```diff
- KursiTerpesan::create($kursiData);  // CREATE BARU (WRONG!)
+ 
+ $existingKursi = KursiTerpesan::where(...)
+     ->where('pemesanan_id', $pemesanan->id)
+     ->first();
+ 
+ if ($existingKursi) {
+     $existingKursi->update(['detail_penumpang_id' => $id]);  // UPDATE (CORRECT!)
+ }
```

### Change 3: Don't Increment Kursi Terisi (Line 2880-2900)
```diff
- $driverJadwal->kursi_terisi += $seatsAssigned;  // DOUBLE INCREMENT (WRONG!)
- 
+ $expectedOccupied = KursiTerpesan::where(...)->count();  // VERIFY (CORRECT!)
+
+ if ($expectedOccupied >= $driverJadwal->total_kursi) {
+     $driverJadwal->status = 'penuh';
+ }
```

## How to Verify
1. **Clear cache**
   ```bash
   php artisan view:clear && php artisan cache:clear
   ```

2. **Test booking flow**
   - Select 3 seats
   - Click "Lanjutkan ke Detail Pesanan"
   - ✅ Should redirect to detail_pesanan (NOT back to kursi)

3. **Check logs**
   ```bash
   tail -f storage/logs/laravel-2026-02-*.log
   ```
   Look for "Seat Selection Completed"

4. **Verify database**
   ```sql
   SELECT * FROM detail_penumpang WHERE pemesanan_id = 123;
   SELECT * FROM kursi_terpesan WHERE pemesanan_id = 123;
   ```
   All should have nomor_kursi filled

## Testing Scenarios
| Scenario | Expected Result |
|----------|-----------------|
| Select all required seats | ✅ Form submit works |
| Select fewer seats | ❌ Button disabled |
| Select more seats | ❌ Alert shown |
| Submit with no seats | ❌ Validation error |
| Refresh page mid-selection | ✅ Seats persist (from lockSeat) |

## Potential Issues to Monitor
1. **Logs show "VALIDATION FAILED"** → Fix those validation issues
2. **kursi_terisi still wrong** → Check if lockSeat() is being called
3. **Redirect still not working** → Check detail_pemesanan route exists

## Files Need Testing
- `/customer/kursi` (GET) - Show seat selection
- `/customer/kursi/proses` (POST) - Process seats
- `/customer/kursi/lock` (POST) - Lock seat
- `/customer/detail_pemesanan` (GET) - Detail page
