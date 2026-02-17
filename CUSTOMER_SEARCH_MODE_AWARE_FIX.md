# Customer Schedule Search - Mode-Aware Implementation

## Overview

The customer schedule search system has been updated to support both operational modes:

1. **Driver Confirmation Mode** (`driver_confirmation`)
   - Admin creates schedules in `jadwals` table as `status='open'`
   - Drivers claim schedules → data migrates to `driver_jadwals` table
   - Customers see schedules from `driver_jadwals` (status='aktif')

2. **Direct Assign Mode** (`direct_assign`)
   - Admin creates schedules in `jadwals` table with driver selected (status='active')
   - No `driver_jadwals` table involved
   - Customers see schedules directly from `jadwals` (status='active')

## What Was Fixed

### Issue 1: Mode-Unaware Search
**Problem:** Customer search only queried `driver_jadwals`, failing in direct_assign mode
**Solution:** Implemented mode-aware queries that check `appSetting('jadwal_flow_mode')` at runtime

### Issue 2: Route Matching Issues
**Problem:** Searching "Jakarta → Bekasi" would show "Jakarta → Bandung" results
**Solution:** Replaced LIKE-based queries with STRICT EXACT matching using `=` operator

### Issue 3: Incomplete Results
**Problem:** Schedules didn't appear even when they should be available
**Solution:** Added proper relationship loading and eliminated fallback/OR conditions

### Issue 4: Missing Empty State Feedback
**Problem:** No clear message when route not available
**Solution:** Enhanced blade template to display specific message: "Rute dari {asal} ke {tujuan} tidak memiliki jadwal"

## Implementation Details

### Configuration
- **Mode Setting:** `app_settings` table, key `jadwal_flow_mode`
- **Access:** `appSetting('jadwal_flow_mode', 'driver_confirmation')`
- **Update:** Admin Panel → Jadwal List → Config Button

### Controller Methods

#### `showSearch()` - Main search page
```php
public function showSearch(Request $request)
{
    $flowMode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    if ($flowMode === 'driver_confirmation') {
        $schedules = $this->searchDriverConfirmationMode($asal, $tujuan, $tanggal, $penumpang);
    } else {
        $schedules = $this->searchDirectAssignMode($asal, $tujuan, $tanggal, $penumpang);
    }
    
    return view('customer.search', compact(...));
}
```

#### Query Methods

**Driver Confirmation Mode:**
```php
private function searchDriverConfirmationMode($asal, $tujuan, $tanggal, $penumpang)
{
    return DriverJadwal::query()
        ->join('rutes', 'driver_jadwals.rute_id', '=', 'rutes.id')
        ->where('driver_jadwals.status', 'aktif')
        ->where('rutes.kota_asal', '=', $asal)  // ← EXACT MATCH
        ->where('rutes.kota_tujuan', '=', $tujuan)  // ← EXACT MATCH
        ->paginate(10);
}
```

**Direct Assign Mode:**
```php
private function searchDirectAssignMode($asal, $tujuan, $tanggal, $penumpang)
{
    return Jadwal::query()
        ->join('rute_jadwals', 'jadwals.id', '=', 'rute_jadwals.jadwal_id')
        ->join('rutes', 'rute_jadwals.rute_id', '=', 'rutes.id')
        ->where('jadwals.status', 'active')
        ->where('rutes.kota_asal', '=', $asal)  // ← EXACT MATCH
        ->where('rutes.kota_tujuan', '=', $tujuan)  // ← EXACT MATCH
        ->paginate(10);
}
```

### Key Features

✅ **Strict EXACT Matching**
- Origin: `rutes.kota_asal = '{asal}'` (NOT LIKE)
- Destination: `rutes.kota_tujuan = '{tujuan}'` (NOT LIKE)
- NO partial matching, NO fallback queries

✅ **Proper Empty State Handling**
```blade
@if(!isset($driverJadwals) || $driverJadwals->isEmpty())
    <div class="empty-state">
        @if(isset($validated['asal']) && isset($validated['tujuan']))
            <h3>Rute tidak tersedia</h3>
            <p>Rute dari <strong>{{ $validated['asal'] }}</strong> 
               ke <strong>{{ $validated['tujuan'] }}</strong> 
               tidak memiliki jadwal yang tersedia.</p>
        @endif
    </div>
@endif
```

✅ **Mode-Specific Data Fetching**
- Dropdown cities sourced from appropriate table per mode
- Price range calculated from correct data source
- Seat availability checked per mode

✅ **Comprehensive Logging**
- Logs current mode at search initiation
- Logs search parameters and flow mode
- Logs result count per mode

## Files Modified

1. **`app/Http/Controllers/CustomerController.php`**
   - Added mode-aware `showSearch()` method
   - Added mode-aware `search()` method
   - Added helper methods:
     - `searchDriverConfirmationMode()`
     - `searchDirectAssignMode()`
     - `buildDriverConfirmationSearch()`
     - `buildDirectAssignSearch()`
     - `getAvailableCitiesDriverConfirmation()`
     - `getAvailableCitiesDirectAssign()`

2. **`resources/views/customer/search.blade.php`**
   - Enhanced empty state message
   - Shows specific route that's unavailable
   - Professional error messaging

## Testing

Run the comprehensive test:
```bash
php test_mode_aware_search.php
```

Expected output:
- ✓ Strict EXACT matching verified
- ✓ Non-existent routes return empty
- ✓ Partial matches don't work
- ✓ Both modes produce consistent behavior

## Usage

### For Customers

1. Navigate to search page (`/cari-shuttle`)
2. Select origin city and destination city
3. System automatically uses appropriate data source per current mode
4. If route has no available schedules, displays: "Rute tidak tersedia"
5. If schedules exist, displays all matching results with pagination

### For Developers

#### Reading Current Mode
```php
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
// Returns: 'driver_confirmation' or 'direct_assign'
```

#### Adding Mode-Aware Features
```php
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

if ($mode === 'driver_confirmation') {
    // Use DriverJadwal model
    $schedules = DriverJadwal::where(...)->get();
} else {
    // Use Jadwal model
    $schedules = Jadwal::where(...)->get();
}
```

#### Never Hardcode Mode
```php
// ❌ WRONG - Never hardcode
if ($flowMode === 'driver_confirmation') { ... }

// ✅ CORRECT - Always read from appSetting
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
if ($mode === 'driver_confirmation') { ... }
```

## Troubleshooting

### Issue: "Scheduled not appearing in customer search"
**Check:** Is the current mode correct?
```php
$mode = appSetting('jadwal_flow_mode');
echo "Current mode: {$mode}";
```

**Solution:** 
- If `driver_confirmation`: Ensure schedule exists in `driver_jadwals` with `status='aktif'`
- If `direct_assign`: Ensure schedule exists in `jadwals` with `status='active'`

### Issue: "Searches wrong route showing up"
**Check:** Is the query using EXACT match logic?
```php
// ✅ CORRECT
->where('rutes.kota_asal', '=', $asal)
->where('rutes.kota_tujuan', '=', $tujuan)

// ❌ WRONG (would show to other routes)
->where('rutes.kota_asal', 'LIKE', "%{$asal}%")
```

### Issue: "Mode change didn't take effect"
**Solution:** Clear the cache
```php
Cache::forget('app_setting:jadwal_flow_mode');
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
```

## Database Schema Requirements

### For Driver Confirmation Mode
- ✅ `driver_jadwals` table with:
  - `id_jadwal_driver` (primary key)
  - `rute_id (foreign key to rutes)
  - `status` (aktif/inactive)
  - `tanggal`, `waktu_keberangkatan`, `waktu_kedatangan`
  - `total_kursi`, `kursi_terisi`, `harga`

### For Direct Assign Mode
- ✅ `jadwals` table with:
  - `id` (primary key)
  - `status` (active/inactive)
  - `tanggal_keberangkatan`
  - `waktu_keberangkatan`, `waktu_kedatangan`
  - `kursi_tersedia`
  - `harga_total`

- ✅ `rute_jadwals` table with:
  - `jadwal_id` (foreign key)
  - `rute_id` (foreign key)

## Performance Notes

- Queries use `.distinct()` to prevent duplicate rows from joins
- Proper indexing on:
  - `driver_jadwals.status`, `driver_jadwals.tanggal`
  - `jadwals.status`, `jadwals.tanggal_keberangkatan`
  - `rutes.id`, `rutes.kota_asal`, `rutes.kota_tujuan`
- Pagination limit: 10 results per page
- Relationships eagerly loaded: `.with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])`

## Migration from Previous Implementation

If you had previous customer search without mode awareness:

1. The new code is backward compatible
2. Default mode is `driver_confirmation`
3. No database migration required
4. Existing `driver_jadwals` records continue to work
5. Switch to `direct_assign` mode via Admin Panel when ready

