# DriverJadwal Integration for Customer Views

## Overview

This document describes how customer-facing views (beranda and search) are now connected to the `DriverJadwal` model. All customer schedule data comes exclusively from the `DriverJadwal` table, which contains schedules that have been claimed by drivers.

## Key Principle

**Customers can only see schedules that have been claimed by drivers.**

- ✅ Data Source: `DriverJadwal` (driver_jadwals table)
- ❌ NOT Used: `AdminJadwal` or raw `Jadwal` data for customer views

## Routes Configuration

### Beranda Routes

```php
// Halaman utama - GET /
Route::get('/', [CustomerController::class, 'beranda'])->name('customer.beranda');

// Beranda dengan filter
Route::get('/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda.filtered');
Route::get('/customer/beranda', [CustomerController::class, 'beranda']);
```

**Supported Filters:**
- `asal` - Origin city (string, optional)
- `tujuan` - Destination city (string, optional)
- `tanggal` - Travel date (date format: YYYY-MM-DD, optional)
- `penumpang` - Number of passengers (integer, min: 1, max: 10, optional)

**Example URLs:**
```
/ or /customer/beranda
/beranda?asal=Jakarta&tujuan=Bandung
/beranda?asal=Jakarta&tujuan=Bandung&tanggal=2026-02-15&penumpang=2
```

### Search Routes

```php
// Search form page
Route::get('/cari-shuttle', [CustomerController::class, 'showSearch'])->name('customer.search');
Route::get('/customer/search', [CustomerController::class, 'showSearch']);

// Search processing (both GET and POST)
Route::post('/cari-shuttle', [CustomerController::class, 'search'])->name('customer.search.post');
Route::post('/customer/search', [CustomerController::class, 'search']);
```

**Supported Query Parameters:**
- `asal` - Origin city
- `tujuan` - Destination city
- `tanggal` - Travel date (YYYY-MM-DD)
- `penumpang` - Number of passengers (default: 1)

**Example URLs:**
```
GET /cari-shuttle
POST /cari-shuttle?asal=Jakarta&tujuan=Bandung&tanggal=2026-02-15&penumpang=2
GET /customer/search?asal=Jakarta&tujuan=Bandung
```

## Controller Methods

### 1. `beranda()` - Beranda Page

**Location:** `App\Http\Controllers\CustomerController::beranda()`

**Purpose:** Display homepage with available schedules from DriverJadwal

**Data Handling:**
- Retrieves only active schedules from `DriverJadwal`
- Filters by availability (seats remaining)
- Supports dynamic filtering by origin, destination, date, and passenger count
- Paginates results (12 per page)
- Provides dropdown data for filter selection

**Returns:**
```php
return view('customer.beranda', [
    'user' => $user,                  // Authenticated user data
    'jadwals' => $jadwals,            // Paginated DriverJadwal collection
    'kotaAsalList' => $kotaAsalList,  // Unique origin cities
    'kotaTujuanList' => $kotaTujuanList, // Unique destination cities
    'outletsGrouped' => $outletsGrouped, // Grouped outlets by city
    'layanan' => $layanan,            // Available services
    'profile' => $profile,            // Company profile
    'reviews' => $reviews,            // Customer reviews
    // ... other data
]);
```

### 2. `search()` - Search Processing

**Location:** `App\Http\Controllers\CustomerController::search()`

**Purpose:** Process search queries and return filtered results

**Input Validation:**
```php
$validated = $request->validate([
    'asal' => 'nullable|string|max:255',
    'tujuan' => 'nullable|string|max:255',
    'tanggal' => 'nullable|date|min_date:today',
    'penumpang' => 'nullable|integer|min:1|max:10'
]);
```

**Query Logic:**
- Uses `DriverJadwal::tersediaUntukCustomer()` scope
- Filters by origin city (case-insensitive LIKE)
- Filters by destination city (case-insensitive LIKE)
- Filters by departure date
- Filters by available seats (seats_available >= num_passengers)
- Orders by date, then departure time

**Returns:**
```php
return view('customer.search', [
    'user' => $user,
    'jadwals' => $jadwals,            // Paginated results (10 per page)
    'kotaAsalList' => $kotaAsalList,  // For dropdown filter
    'kotaTujuanList' => $kotaTujuanList, // For dropdown filter
    'priceRange' => $priceRange,      // Min/Max price for filter
    'outletsGrouped' => $outletsGrouped,
    'asal' => $asal,                  // Current filter values
    'tujuan' => $tujuan,
    'tanggal' => $tanggal,
    'penumpang' => $penumpang
]);
```

### 3. `showSearch()` - Search Page

**Location:** `App\Http\Controllers\CustomerController::showSearch()`

**Purpose:** Display search form and handle both new and legacy parameters

**Dual-Mode Support:**
1. **New Mode:** Parameters: `asal`, `tujuan`, `tanggal`, `penumpang`
   - Delegates to `search()` method
   - Uses DriverJadwal data

2. **Legacy Mode:** Parameters: `departure_outlet`, `destination_outlet`, `departure_date`, `passenger_count`
   - Delegates to `processSearch()` method
   - For backward compatibility

**Returns:**
```php
// When showing form only
return view('customer.search', [
    'user' => $user,
    'outletsGrouped' => $outletsGrouped,
]);

// When search results exist
return view('customer.search', [
    'user' => $user,
    'jadwals' => $jadwals,
    'validated' => $validated,  // Legacy mode
    'outletsGrouped' => $outletsGrouped,
    // ... other data
]);
```

## DriverJadwal Model Features

### Relationships

```php
// Get related admin schedule
$driverJadwal->jadwal;     // Admin\Jadwal

// Get driver user
$driverJadwal->driver;     // User (driver)
```

### Scopes (Query Filters)

```php
// Get only active schedules with available seats
DriverJadwal::tersediaUntukCustomer();

// Get schedules for specific driver
DriverJadwal::byDriver($driverId);

// Get schedules for current month
DriverJadwal::bulanIni();

// Search with parameters
DriverJadwal::search(['rute' => 'Jakarta', 'harga_min' => 100000]);
```

### Accessors (Formatted Data)

```php
$jadwal->tanggal_formatted;      // d F Y format
$jadwal->tanggal_singkat;         // d/m/Y format
$jadwal->waktu_berangkat_formatted; // H:i format
$jadwal->waktu_tiba_formatted;   // H:i format
$jadwal->harga_formatted;        // Rp X.XXX format
$jadwal->kursi_tersedia;         // Total seats - filled seats
$jadwal->sisa_kursi;             // Alias for kursi_tersedia
$jadwal->persentase_terisi;      // 0-100
$jadwal->status_kursi;           // 'penuh' | 'hampir penuh' | 'tersedia'
$jadwal->kota_asal;              // Parsed from rute
$jadwal->kota_tujuan;            // Parsed from rute
```

### Helper Methods

```php
// Get detailed route information
$detilRute = $jadwal->getDetailRute();
// Returns: ['kota_asal' => '...', 'kota_tujuan' => '...', 'nama_rute' => '...']

// Check if available for customer
$isAvailable = $jadwal->isAvailableForCustomer();

// Update filled seats
$jadwal->updateKursiTerisi($jumlah);

// Check seat availability
$tersedia = $jadwal->cekKursiTersedia($jumlahPenumpang);

// Update schedule status
$jadwal->updateStatus('selesai');

// Get API response format
$apiData = $jadwal->toApiResponse();
```

## Data Flow Diagram

```
Browser Request
    ↓
Route (web.php)
    ↓
CustomerController::beranda() or search()
    ↓
DriverJadwal::tersediaUntukCustomer()
    ↓
Filter by: asal, tujuan, tanggal, penumpang
    ↓
Paginate results
    ↓
Load blade template (beranda.blade.php or search.blade.php)
    ↓
Display to customer
```

## Database Query Examples

### Get all available schedules

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->get();
```

### Get schedules by origin and destination

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->whereHas('jadwal.rutes', function($q) {
        $q->where('kota_asal', 'Jakarta')
          ->where('kota_tujuan', 'Bandung');
    })
    ->get();
```

### Get schedules with available seats for 3 passengers

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->whereRaw('(total_kursi - kursi_terisi) >= 3')
    ->get();
```

### Get schedules for specific date ordered by time

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->where('tanggal', '2026-02-15')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->paginate(10);
```

## Blade Template Usage

### In beranda.blade.php

```blade
@foreach($jadwals as $jadwal)
    <div class="schedule-card">
        <h3>{{ $jadwal->rute }}</h3>
        <p>{{ $jadwal->tanggal_formatted }} - {{ $jadwal->waktu_berangkat_formatted }}</p>
        <p>Price: {{ $jadwal->harga_formatted }}</p>
        <p>Available seats: {{ $jadwal->kursi_tersedia }} / {{ $jadwal->total_kursi }}</p>
        <p>Driver: {{ $jadwal->driver->name }}</p>
    </div>
@endforeach

{{ $jadwals->links() }} {{-- Pagination --}}
```

### In search.blade.php

```blade
<form method="GET" action="{{ route('customer.search') }}">
    <select name="asal">
        <option value="">Select Origin</option>
        @foreach($kotaAsalList as $kota)
            <option value="{{ $kota }}" {{ request('asal') == $kota ? 'selected' : '' }}>
                {{ $kota }}
            </option>
        @endforeach
    </select>

    <select name="tujuan">
        <option value="">Select Destination</option>
        @foreach($kotaTujuanList as $kota)
            <option value="{{ $kota }}" {{ request('tujuan') == $kota ? 'selected' : '' }}>
                {{ $kota }}
            </option>
        @endforeach
    </select>

    <input type="date" name="tanggal" value="{{ request('tanggal') }}">
    <input type="number" name="penumpang" min="1" max="10" value="{{ request('penumpang', 1) }}">
    
    <button type="submit">Search</button>
</form>

@if(isset($jadwals))
    @foreach($jadwals as $jadwal)
        {{-- Display schedule --}}
    @endforeach
    {{ $jadwals->links() }}
@endif
```

## Important Notes

1. **Customer Can Only See Driven Schedules:** The `tersediaUntukCustomer()` scope ensures only schedules with status='aktif' and available seats are shown.

2. **No AdminJadwal Used:** All customer views exclusively use `DriverJadwal` model. Admin schedules are never displayed to customers.

3. **Pagination:** Results are paginated (beranda: 12/page, search: 10/page) for better performance.

4. **Seat Validation:** Schedules are filtered to show only those with available seats for the requested number of passengers.

5. **Date Filter:** Only schedules for today or future dates are shown.

6. **Formatting:** All date, time, and price data are automatically formatted using Eloquent accessors.

## Testing

### Test Beranda

```bash
curl "http://localhost/beranda"
curl "http://localhost/beranda?asal=Jakarta&tujuan=Bandung"
curl "http://localhost/beranda?tanggal=2026-02-15&penumpang=2"
```

### Test Search

```bash
curl "http://localhost/cari-shuttle"
curl "http://localhost/cari-shuttle?asal=Jakarta&tujuan=Bandung&tanggal=2026-02-15&penumpang=1"
```

## Troubleshooting

### No schedules showing up?
- Check that DriverJadwal records have `status = 'aktif'`
- Check that `kursi_terisi < total_kursi` (seats available)
- Check that `tanggal >= TODAY`
- Verify relationships are properly loaded with `->with(['driver', 'jadwal.rutes'])`

### Filters not working?
- Verify the request parameters are being passed correctly
- Check that the related Jadwal and Rute records have correct city data
- Use `dd($jadwals)` to debug the query results

### Pagination issues?
- Check that the route accepts page parameters (Laravel handles this automatically)
- Verify blade template calls `$jadwals->links()` for pagination links

## Migration Notes

If migrating from old system:
1. Ensure all DriverJadwal records have been created from admin schedules
2. Set `status = 'aktif'` for schedules drivers have claimed
3. Update seats count: `kursi_terisi = booked_seats`, `total_kursi = vehicle_capacity`
4. Verify all required relationships (driver, jadwal, rutes) are properly populated
