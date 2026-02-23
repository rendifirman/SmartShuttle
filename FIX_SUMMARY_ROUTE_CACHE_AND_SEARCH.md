# Search & Route Cache Fix Summary

## Issues Fixed

### 1. **Route Cache Error** ✅
**Error:** `LogicException: Unable to prepare route [forgot-password] for serialization. Another route has already been assigned name [password.email].`

**Root Cause:** Duplicate route names defined in multiple files:
- `/routes/web.php` lines 695-700 defined `password.email` for `/forgot-password` route
- `/routes/auth.php` lines 17-19 also defined `password.email` for `/forgot-password` route
- `/routes/web.php` lines 158-159 had additional `password.email` definition

**Solution:** Removed duplicate route definitions from `/routes/web.php` lines 695-700 and lines 158-159 (guest routes). Kept only the ones defined in `/routes/auth.php` which is loaded first.

**Result:** ✅ `php artisan route:cache` now works without errors

---

### 2. **Search Results Not Displaying** ✅  
**Problem:** When users selected cities in the search dropdown on beranda.blade.php, schedules weren't appearing in the results. The beranda page was trying to filter using complex `jadwal.rutes` relationships that weren't working properly.

**Root Cause:** 
- Beranda blade was duplicating the filtering logic instead of using what the controller provided
- Used `whereHas('jadwal.rutes')` filtering which was unreliable
- Blade wasn't receiving the filtered `$jadwals` from the controller—it was trying to do its own query

**Solution:** 
1. Updated `beranda()` method in `CustomerController` to:
   - Extract search parameters from request: `asal`, `tujuan`, `tanggal`, `penumpang`
   - Build the full filtered query using proper LIKE patterns on the `rute` field
   - Apply filters BEFORE passing to the view
   - Pass extra variables to blade: `asalParam`, `tujuanParam`, `tanggalParam`, `penumpangParam`

2. Simplified `beranda.blade.php` to:
   - Remove duplicate query logic from the blade template
   - Use pre-filtered `$jadwals` collection from the controller
   - Use pre-populated `$asalParam`, `$tujuanParam`, etc. from controller

**Result:** ✅ When user selects cities in dropdown and page loads, schedules now display correctly

---

## Code Changes

### File 1: `/routes/web.php`
**Change:** Removed duplicate password.email routes
```diff
- Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
-     ->middleware('guest')
-     ->name('password.email');
```

Also removed from guest routes inside the customer middleware group:
```diff
- Route::post('/password/email', [PasswordResetLinkController::class, 'store'])
-     ->name('password.email');
```

### File 2: `/app/Http/Controllers/CustomerController.php`
**Change:** Enhanced `beranda()` method
- Added parameter extraction from request
- Moved filtering logic from blade to controller
- Changed from `whereHas('jadwal.rutes')` to direct LIKE pattern matching on `rute` field
- Pass filtered results and params to view

**Key Query Logic:**
```php
if ($asalParam) {
    $query->where(function($q) use ($asalParam) {
        $q->where('rute', 'like', '%(' . $asalParam . '%')
          ->orWhere('rute', 'like', '% ' . $asalParam . '%');
    });
}

if ($tujuanParam) {
    $query->where('rute', 'like', '%' . $tujuanParam . '%');
}
```

### File 3: `/resources/views/customer/beranda.blade.php`
**Change:** Simplified blade template
- Removed duplicate DriverJadwal query from blade
- Removed `whereHas('jadwal.rutes')` logic from blade
- Use pre-filtered `$jadwals` from controller
- Use pre-populated parameter variables

**Before:**
```php
$jadwalsQuery = DriverJadwal::with([...])
    ->where('status', 'aktif')
    ...
    ->whereHas('jadwal.rutes', function($q) use ($asalParam) {
        $q->where('kota_asal', $asalParam);  // ❌ Unreliable
    });
```

**After:**
```php
// Just use what controller provided
$asalParam = $asalParam ?? '';
$tujuanParam = $tujuanParam ?? '';
// $jadwals is already filtered and ready to display
```

---

## Testing

### Test 1: Route Caching
```bash
php artisan route:cache
# Expected: "Routes cached successfully."
# Before fix: "LogicException: Unable to prepare route..."
```

### Test 2: Beranda Filtering
1. Navigate to `/customer` (beranda page)
2. Verify the dropdowns show:
   - Kota Asal: Jakarta, Bandung
   - Kota Tujuan: Bandung, Semarang
3. Select: Jakarta → Bandung
4. **Expected:** Schedule for Jakarta → Bandung displays below

### Test 3: Search Page
1. From beranda, click "CEK SHUTTLE"
2. Navigate to `/customer/search?asal=Jakarta&tujuan=Bandung`
3. **Expected:** Schedule results display with matching routes

### Test 4: Clear All Caches
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
# All should complete successfully
```

---

## Files Modified
1. ✅ `/routes/web.php` - Removed duplicate password.email routes
2. ✅ `/app/Http/Controllers/CustomerController.php` - Enhanced beranda() method
3. ✅ `/resources/views/customer/beranda.blade.php` - Simplified filtering logic

---

## Data Requirements

Database must have `driver_jadwals` records with:
- `status = 'aktif'` 
- `tanggal >= TODAY()` (future or today)
- `kursi_terisi < total_kursi` (at least 1 seat available)
- `rute` field format: `"[Name] ([City → City])"` e.g., `"Jakarta - Bandung Via Bekasi (Jakarta → Bandung)"`

**Sample record:**
```sql
INSERT INTO driver_jadwals (rute, status, tanggal, total_kursi, kursi_terisi, ...)
VALUES ('Jakarta - Bandung Via Bekasi (Jakarta → Bandung)', 'aktif', '2026-02-08', 9, 0, ...);
```

---

## Verification Checklist

- [x] Route caching works without LogicException
- [x] No duplicate password.email route names
- [x] Beranda page loads without errors
- [x] Dropdowns on beranda show cities correctly
- [x] Selecting cities filters schedules on beranda
- [x] Search page shows filtered results
- [x] Schedule details display correctly
- [x] Form submission works properly

---

## Result

✅ **Route caching fixed** - No more LogicException  
✅ **Search filtering works** - Schedules display when cities selected  
✅ **Both beranda and search pages functional** - Consistent filtering logic  
✅ **Ready for production testing**

---

## How It Works Now

```
User Flow:
┌─────────────────────────────────────────────────┐
│ 1. User visits beranda (/customer)              │
├─────────────────────────────────────────────────┤
│ 2. beranda() method:                            │
│    - Extracts request params (asal, tujuan)    │
│    - Filters DriverJadwal using LIKE patterns  │
│    - Passes filtered $jadwals to blade         │
│    - Passes $asalParam, $tujuanParam vars      │
├─────────────────────────────────────────────────┤
│ 3. beranda.blade.php displays:                  │
│    - Dropdowns populated with cities           │
│    - Filtered schedules below                  │
│    - Form action to /customer/search           │
├─────────────────────────────────────────────────┤
│ 4. User selects Jakarta → Bandung              │
│    Submits form                                │
├─────────────────────────────────────────────────┤
│ 5. Page reloads with filter params in URL:     │
│    /customer?asal=Jakarta&tujuan=Bandung       │
├─────────────────────────────────────────────────┤
│ 6. beranda() method runs again:                │
│    - Sees asal=Jakarta, tujuan=Bandung         │
│    - Filters to matching records               │
│    - Returns only Jakarta→Bandung schedule     │
├─────────────────────────────────────────────────┤
│ 7. Blade displays results                      │
│    - Selected values stay in dropdowns         │
│    - Matching schedules displayed below        │
└─────────────────────────────────────────────────┘
```

All filtering happens in the **controller**, not the blade template, ensuring:
- Single source of truth for query logic
- Better performance
- Easier to debug and maintain
- Consistent results
