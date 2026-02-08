# DriverJadwal Customer Implementation - Code Examples

## Quick Reference

### Simple Examples

#### Example 1: Get all available schedules (Beranda)

```php
$jadwals = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
    ->tersediaUntukCustomer()
    ->orderBy('tanggal', 'asc')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->paginate(12);
```

#### Example 2: Search with filters (Search)

```php
$jadwals = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
    ->tersediaUntukCustomer()
    ->whereHas('jadwal.rutes', function($q) {
        $q->where('kota_asal', 'like', '%Jakarta%');
        $q->where('kota_tujuan', 'like', '%Bandung%');
    })
    ->where('tanggal', '2026-02-15')
    ->whereRaw('(total_kursi - kursi_terisi) >= 2')
    ->orderBy('tanggal', 'asc')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->paginate(10);
```

#### Example 3: Get unique cities for dropdown

```php
$kotaAsalList = DriverJadwal::with(['jadwal.rutes'])
    ->tersediaUntukCustomer()
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();
```

#### Example 4: Get price range

```php
$priceRange = DriverJadwal::tersediaUntukCustomer()
    ->selectRaw('MIN(harga) as min_harga, MAX(harga) as max_harga')
    ->first();

echo $priceRange->min_harga;  // Rp 100,000
echo $priceRange->max_harga;  // Rp 500,000
```

---

## Controller Implementation Patterns

### Pattern 1: Beranda Controller (Full Filter Support)

```php
public function beranda(Request $request)
{
    // Get user data
    $user = session()->get('user');
    
    // Base query - only DriverJadwal
    $query = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
        ->tersediaUntukCustomer();
    
    // Apply filters
    if ($request->filled('asal')) {
        $query->whereHas('jadwal.rutes', function($q) use ($request) {
            $q->where('kota_asal', 'like', '%' . $request->asal . '%');
        });
    }
    
    if ($request->filled('tujuan')) {
        $query->whereHas('jadwal.rutes', function($q) use ($request) {
            $q->where('kota_tujuan', 'like', '%' . $request->tujuan . '%');
        });
    }
    
    if ($request->filled('tanggal')) {
        $query->where('tanggal', $request->tanggal);
    }
    
    if ($request->filled('penumpang')) {
        $penumpang = (int) $request->penumpang;
        $query->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);
    }
    
    // Get results with pagination
    $jadwals = $query->orderBy('tanggal', 'asc')
        ->orderBy('waktu_keberangkatan', 'asc')
        ->paginate(12);
    
    // Get filter options
    $kotaAsalList = DriverJadwal::tersediaUntukCustomer()
        ->with('jadwal.rutes')
        ->get()
        ->mapWithKeys(function($item) {
            $detail = $item->getDetailRute();
            return [$detail['kota_asal'] => $detail['kota_asal']];
        })
        ->unique()
        ->sort();
    
    return view('customer.beranda', compact(
        'user',
        'jadwals',
        'kotaAsalList'
    ));
}
```

### Pattern 2: Search Controller (Request Validation)

```php
public function search(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'asal' => 'nullable|string|max:255',
        'tujuan' => 'nullable|string|max:255',
        'tanggal' => 'nullable|date|min_date:today',
        'penumpang' => 'nullable|integer|min:1|max:10'
    ]);
    
    // Build query
    $query = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
        ->tersediaUntukCustomer();
    
    // Apply all filters
    $this->applySearchFilters($query, $validated);
    
    // Get paginated results
    $jadwals = $query->orderBy('tanggal', 'asc')
        ->orderBy('waktu_keberangkatan', 'asc')
        ->paginate(10);
    
    return view('customer.search', $validated + compact('jadwals'));
}

private function applySearchFilters($query, array $filters)
{
    if (!empty($filters['asal'])) {
        $query->whereHas('jadwal.rutes', function($q) use ($filters) {
            $q->where('kota_asal', 'like', '%' . $filters['asal'] . '%');
        });
    }
    
    if (!empty($filters['tujuan'])) {
        $query->whereHas('jadwal.rutes', function($q) use ($filters) {
            $q->where('kota_tujuan', 'like', '%' . $filters['tujuan'] . '%');
        });
    }
    
    if (!empty($filters['tanggal'])) {
        $query->where('tanggal', $filters['tanggal']);
    }
    
    if (!empty($filters['penumpang'])) {
        $penumpang = (int) $filters['penumpang'];
        $query->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);
    }
}
```

### Pattern 3: Get Unique Cities Helper

```php
private function getUniqueCities($type = 'asal')
{
    return DriverJadwal::with(['jadwal.rutes'])
        ->tersediaUntukCustomer()
        ->get()
        ->map(function($jadwal) use ($type) {
            $detail = $jadwal->getDetailRute();
            return $detail["kota_$type"] ?? null;
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();
}

// Usage:
$kotaAsal = $this->getUniqueCities('asal');
$kotaTujuan = $this->getUniqueCities('tujuan');
```

---

## Blade Template Patterns

### Pattern 1: Display Schedule Card

```blade
@foreach($jadwals as $jadwal)
    <div class="schedule-card">
        <div class="schedule-header">
            <h4>{{ $jadwal->rute }}</h4>
            <span class="badge badge-{{ $jadwal->status_color }}">{{ $jadwal->status }}</span>
        </div>
        
        <div class="schedule-details">
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ $jadwal->tanggal_formatted }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Departure:</span>
                <span class="detail-value">{{ $jadwal->waktu_berangkat_formatted }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Arrival:</span>
                <span class="detail-value">{{ $jadwal->waktu_tiba_formatted }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Price:</span>
                <span class="detail-value">{{ $jadwal->harga_formatted }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Seats:</span>
                <span class="detail-value">{{ $jadwal->kursi_tersedia }} / {{ $jadwal->total_kursi }}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $jadwal->persentase_terisi }}%"></div>
                </div>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Driver:</span>
                <span class="detail-value">{{ $jadwal->driver->name }}</span>
            </div>
        </div>
        
        <div class="schedule-actions">
            @if($jadwal->kursi_tersedia > 0)
                <a href="{{ route('customer.pesan') }}" class="btn btn-primary">Book Now</a>
            @else
                <button class="btn btn-disabled">Sold Out</button>
            @endif
        </div>
    </div>
@endforeach
```

### Pattern 2: Search Filter Form

```blade
<form method="GET" action="{{ route('customer.search') }}" class="search-form">
    <div class="form-group">
        <label>Origin City</label>
        <select name="asal" class="form-control">
            <option value="">Select Origin...</option>
            @foreach($kotaAsalList as $kota)
                <option value="{{ $kota }}" 
                    {{ request('asal') == $kota ? 'selected' : '' }}>
                    {{ $kota }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>Destination City</label>
        <select name="tujuan" class="form-control">
            <option value="">Select Destination...</option>
            @foreach($kotaTujuanList as $kota)
                <option value="{{ $kota }}" 
                    {{ request('tujuan') == $kota ? 'selected' : '' }}>
                    {{ $kota }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>Travel Date</label>
        <input type="date" name="tanggal" class="form-control"
            value="{{ request('tanggal') }}" 
            min="{{ now()->format('Y-m-d') }}">
    </div>
    
    <div class="form-group">
        <label>Passengers</label>
        <input type="number" name="penumpang" class="form-control"
            min="1" max="10" value="{{ request('penumpang', 1) }}">
    </div>
    
    <button type="submit" class="btn btn-primary">Search</button>
</form>

@if(isset($jadwals))
    <div class="results">
        @forelse($jadwals as $jadwal)
            {{-- Display schedule card --}}
        @empty
            <p class="alert alert-info">No schedules found matching your criteria.</p>
        @endforelse
        
        {{-- Pagination --}}
        <div class="pagination">
            {{ $jadwals->links() }}
        </div>
    </div>
@endif
```

### Pattern 3: Display Filtered Results

```blade
@isset($jadwals)
    @if($jadwals->count() > 0)
        <div class="results-info">
            <p>Found {{ $jadwals->total() }} schedules</p>
            @if(request('asal'))
                <span class="filter-tag">From: {{ request('asal') }}</span>
            @endif
            @if(request('tujuan'))
                <span class="filter-tag">To: {{ request('tujuan') }}</span>
            @endif
            @if(request('tanggal'))
                <span class="filter-tag">Date: {{ request('tanggal') }}</span>
            @endif
        </div>
        
        <div class="schedules-list">
            @foreach($jadwals as $jadwal)
                {{-- Schedule card template --}}
            @endforeach
        </div>
        
        {{-- Pagination --}}
        {{ $jadwals->links('pagination::bootstrap-4') }}
    @else
        <div class="alert alert-warning">
            No schedules available for your search criteria.
            <a href="{{ route('customer.search') }}">Clear filters</a>
        </div>
    @endif
@endif
```

---

## Data Transformation Examples

### Example 1: Format for API Response

```php
$schedules = DriverJadwal::tersediaUntukCustomer()
    ->limit(10)
    ->get()
    ->map(function($jadwal) {
        return [
            'id' => $jadwal->id_jadwal_driver,
            'route' => $jadwal->rute,
            'date' => $jadwal->tanggal_singkat,
            'departure' => $jadwal->waktu_berangkat_formatted,
            'arrival' => $jadwal->waktu_tiba_formatted,
            'price' => $jadwal->harga,
            'price_formatted' => $jadwal->harga_formatted,
            'seats_available' => $jadwal->kursi_tersedia,
            'seats_total' => $jadwal->total_kursi,
            'driver' => [
                'id' => $jadwal->driver->id,
                'name' => $jadwal->driver->name,
                'phone' => $jadwal->driver->phone,
            ],
            'status' => $jadwal->status,
        ];
    });

return response()->json($schedules);
```

### Example 2: Build Cache Key

```php
private function getScheduleCacheKey($asal, $tujuan, $tanggal, $penumpang)
{
    return 'schedules:' . md5("{$asal}:{$tujuan}:{$tanggal}:{$penumpang}");
}

// Usage with cache:
$cacheKey = $this->getScheduleCacheKey($asal, $tujuan, $tanggal, $penumpang);
$jadwals = Cache::remember($cacheKey, 3600, function() use ($asal, $tujuan, $tanggal, $penumpang) {
    return DriverJadwal::with(['driver', 'jadwal.rutes'])
        ->tersediaUntukCustomer()
        ->whereHas('jadwal.rutes', function($q) use ($asal, $tujuan) {
            $q->where('kota_asal', $asal)->where('kota_tujuan', $tujuan);
        })
        ->where('tanggal', $tanggal)
        ->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang])
        ->get();
});
```

---

## Common Queries Cheat Sheet

| Task | Query |
|------|-------|
| Get all available | `DriverJadwal::tersediaUntukCustomer()->get()` |
| By specific route | `->whereHas('jadwal.rutes', fn($q) => $q->where('nama_rute', 'xyz'))` |
| By date range | `->whereBetween('tanggal', ['2026-02-01', '2026-02-28'])` |
| By price range | `->whereBetween('harga', [100000, 500000])` |
| By departure time | `->whereTime('waktu_keberangkatan', '>=', '08:00')` |
| Min seats available | `->whereRaw('(total_kursi - kursi_terisi) >= 5')` |
| Specific driver | `->where('id_driver', $driverId)` |
| This month | `->bulanIni()` |
| Order by date | `->orderBy('tanggal', 'asc')` |
| Order by price | `->orderBy('harga', 'asc')` |
| With pagination | `->paginate(10)` |
| Get 5 latest | `->latest()->take(5)->get()` |

---

## Debugging Tips

### View the SQL Query

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->where('tanggal', '2026-02-15')
    ->toSql();  // See the SQL query

dd($jadwals->getBindings());  // See bound parameters
```

### Log filtered results

```php
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->where('tanggal', $tanggal)
    ->get();

Log::info('Found schedules', [
    'count' => $jadwals->count(),
    'data' => $jadwals->toArray()
]);
```

### Check relationships

```php
$jadwal = DriverJadwal::with(['driver', 'jadwal.rutes'])->first();

if (!$jadwal->driver) {
    Log::warning('No driver for schedule', ['id' => $jadwal->id_jadwal_driver]);
}

if (!$jadwal->jadwal) {
    Log::warning('No admin jadwal for schedule', ['id' => $jadwal->id_jadwal_driver]);
}

if ($jadwal->jadwal->rutes->isEmpty()) {
    Log::warning('No routes for schedule', ['id' => $jadwal->id_jadwal_driver]);
}
```

---

## Performance Optimization

### Use Eager Loading

```php
// Good - loads all relationships at once
$jadwals = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
    ->tersediaUntukCustomer()
    ->get();

// Bad - N+1 queries
$jadwals = DriverJadwal::get();
foreach ($jadwals as $jadwal) {
    echo $jadwal->driver->name;  // Query for each row
}
```

### Use Pagination

```php
// Good - limits database queries
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->paginate(10);

// Bad - loads all rows
$jadwals = DriverJadwal::tersediaUntukCustomer()
    ->get();
```

### Cache Filter Options

```php
$kotaAsalList = Cache::remember('kota_asal_list', 3600, function() {
    return DriverJadwal::tersediaUntukCustomer()
        ->with('jadwal.rutes')
        ->get()
        ->map(fn($item) => $item->getDetailRute()['kota_asal'])
        ->filter()
        ->unique()
        ->sort()
        ->values();
});
```

### Use Caching for Results

```php
$cacheKey = "search:{$asal}:{$tujuan}:{$tanggal}";

$jadwals = Cache::remember($cacheKey, 600, function() use ($asal, $tujuan, $tanggal) {
    return DriverJadwal::tersediaUntukCustomer()
        ->whereHas('jadwal.rutes', function($q) {
            $q->where('kota_asal', $asal)->where('kota_tujuan', $tujuan);
        })
        ->where('tanggal', $tanggal)
        ->paginate(10);
});
```
