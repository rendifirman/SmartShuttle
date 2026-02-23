# Starting Outlet Implementation - Titik Awal dari Outlets

## 📋 Perubahan Implementasi

### Kebutuhan
> Titik awalnya diambil dari outlet yang ada di dalam branch yang ada di dalam rute yang ada di dalam jadwal

### Solusi
Starting point sekarang diambil dari **outlet pertama** dalam **branch pertama** dari **rute pemberhentian** dalam **jadwal**.

---

## 🔄 Data Flow

### Backend (DriverController.php)

```
Schedule (Jadwal)
  ├── Rutes (Rute)
  │    └── rute_pemberhentian [array]
  │         ├── [0] First Stop ✅ STARTING POINT
  │         │    ├── kota
  │         │    ├── outlets [nama_outlet, ...]
  │         │    └── durasi_singgah
  │         ├── [1] Second Stop
  │         ├── [2] Third Stop
  │         └── ...
  │
  └── Branch (Cabang)
       └── Outlets (aktif)
```

**getStopPointsFromSchedule()** function mengambil semua pemberhentian dan mengembalikan structured data:

```php
[
    [0] => [  // ← STOP PERTAMA = STARTING POINT
        'urutan' => 1,
        'kota' => 'Bandung',
        'branch_id' => 1,
        'branch_name' => 'Cabang Bandung',
        'durasi_singgah' => 10,
        'outlets' => [
            ['id' => 1, 'nama_outlet' => 'Terminal Leuwipanjang', 'alamat' => '...', 'kota' => 'Bandung'],
            ['id' => 2, 'nama_outlet' => 'Outlet Dago', 'alamat' => '...', 'kota' => 'Bandung'],
        ]
    ],
    [1] => [ // ← SUBSEQUENT STOPS
        'urutan' => 2,
        'kota' => 'Jakarta',
        'branch_id' => 2,
        'branch_name' => 'Cabang Jakarta',
        ...
    ],
    ...
]
```

**New Field Added:**
- `starting_outlet` - Extracted from first stop point untuk explicit reference

Response:
```php
'stop_points' => $stopPoints,          // All stops including first
'starting_outlet' => $startingOutlet,  // Explicit starting outlet reference
```

### Frontend (perjalanan.blade.php)

**buildJourneyDataFromStopPoints()** function:

```javascript
// ★★★ AMBIL TITIK AWAL DARI OUTLET DI STOP POINT PERTAMA ★★★
if (Array.isArray(stopPoints) && stopPoints.length > 0) {
    const firstStop = stopPoints[0];
    
    if (firstStop.outlets && firstStop.outlets.length > 0) {
        // Gunakan outlets dari stop point pertama sebagai starting point
        const outletNames = firstStop.outlets.map(o => o.nama_outlet).join(', ');
        journeyData.stops.push({
            name: firstStop.kota || tripData.from,
            detail: `${firstStop.branch_name} - ${outletNames}`,
            type: "start",
            outlets: firstStop.outlets || [],
            branch_id: firstStop.branch_id
        });
    }
}

// Subsequent stops start from index 1
if (Array.isArray(stopPoints) && stopPoints.length > 1) {
    stopPoints.forEach((stop, index) => {
        if (index === 0) return;  // Skip first stop (already used as starting point)
        // Add remaining stops...
    });
}
```

---

## 📊 Journey Structure

**Before:**
```
Progress: [Start] [Stop 1] [Stop 2] [Finish]

Journey:
  ├── Start: Jakarta (tripData.from)
  ├── Stop 1: Bandung 
  ├── Stop 2: Jakarta
  └── Finish: Surabaya (tripData.to)
```

**After:**
```
Progress: [Start] [Stop 2] [Stop 3] [Finish]

Journey:
  ├── Start: Bandung (from first outlet in first stop point)
  │         ├── Terminal Leuwipanjang
  │         └── Outlet Dago
  ├── Stop 2: Jakarta (from subsequent stop points)
  │          ├── Terminal Harharaji
  │          └── Outlet Cililitan
  ├── Stop 3: Semarang
  │          └── Terminal Johar
  └── Finish: Surabaya (tripData.to)
```

---

## 🎯 Display Logic

### Starting Location
```javascript
journeyData.stops[0] = {
    name: 'Bandung',
    detail: 'Cabang Bandung - Terminal Leuwipanjang, Outlet Dago',
    type: 'start',
    outlets: [
        { nama_outlet: 'Terminal Leuwipanjang', alamat: '...', ... },
        { nama_outlet: 'Outlet Dago', alamat: '...', ... }
    ]
}
```

**Displayed as:**
```
🟢 Bandung
   Cabang Bandung - Terminal Leuwipanjang, Outlet Dago
```

### Update Lokasi Modal
When driver clicks "Update Lokasi", modal shows outlets for **next stop point**:

```
┌─────────────────────────────────┐
│ Update Lokasi                   │
│ Lokasi bus akan berpindah...    │
│ Menuju: Jakarta                 │
│                                 │
│ Outlets di Pemberhentian:       │
│ • Terminal Harharaji            │
│   Jl. Harharaji No.01, Jakarta  │
│ • Outlet Cililitan              │
│   Jl. Cililitan No.02, Jakarta  │
│                                 │
│ [Batal] [Update]                │
└─────────────────────────────────┘
```

---

## ✅ Key Changes

### Backend Changes (DriverController.php)

1. **Extract Starting Outlet Info:**
   ```php
   $startingOutlet = null;
   if (!empty($stopPoints) && is_array($stopPoints) && isset($stopPoints[0])) {
       $firstStop = $stopPoints[0];
       if (isset($firstStop['outlets']) && !empty($firstStop['outlets'])) {
           $startingOutlet = [
               'kota' => $firstStop['kota'],
               'branch_id' => $firstStop['branch_id'],
               'branch_name' => $firstStop['branch_name'],
               'outlets' => $firstStop['outlets']
           ];
       }
   }
   ```

2. **Pass to Frontend:**
   ```php
   'starting_outlet' => $startingOutlet,
   'stop_points' => $stopPoints,
   ```

### Frontend Changes (perjalanan.blade.php)

1. **Use First Stop Point as Starting Location:**
   - Instead of using `tripData.from`, use outlets from `stopPoints[0]`
   - Display branch name and outlet names
   - Attach outlet data to starting stop

2. **Adjust Subsequent Stops:**
   - Loop through `stopPoints` starting from index 1
   - Each becomes a regular "stop" type
   - Keep outlet data for each

3. **Modal Display:**
   - Enhanced modal shows outlets for next stop point
   - Outlet names and addresses displayed clearly

---

## 🔍 Data Hierarchy

```
Jadwal (Schedule)
  ↓
  Rutes (1-N) 
    ↓
    rute_pemberhentian (JSON Array)
      ↓
      Stop[0] → Branch → Outlets[0,1,2,...] ← STARTING POINT
      Stop[1] → Branch → Outlets[0,1,2,...]
      Stop[2] → Branch → Outlets[0,1,2,...]
      ...
    ↓
getStopPointsFromSchedule()
    ↓
stop_points array with full outlet data
    ↓
Frontend buildJourneyDataFromStopPoints()
    ↓
journeyData.stops[0] = Starting location from outlets
journeyData.stops[1,2,...] = Subsequent stops from remaining outlets
journeyData.stops[n] = Final destination (tripData.to)
```

---

## 🧪 Testing

### Test Case 1: Trip with Multiple Stops
```
Schedule: Jakarta → Bandung → Jakarta → Surabaya
Rute: [Bandung, Jakarta, Semarang, Surabaya]

Expected Result:
  Start: Bandung (with outlets from first stop in route)
  Stop 1: Jakarta
  Stop 2: Semarang
  Finish: Surabaya
```

### Test Case 2: Trip with Only One Stop
```
Schedule: Jakarta → Bandung
Rute: [Bandung]

Expected Result:
  Start: Bandung (with outlets from stop in route)
  Finish: Bandung or original destination
```

### Test Case 3: Trip Without Stop Points
```
Schedule: Jakarta → Bandung
Rute: (empty)

Expected Result (Fallback):
  Start: Jakarta (from tripData.from)
  Stop 1: Default Stop
  Stop 2: Default Stop
  Finish: Bandung
```

---

## ✨ Benefits

✅ **Accurate Starting Point** - Uses actual outlets from schedule/route data
✅ **Clear Hierarchy** - Shows branch → outlets relationship clearly
✅ **Better UX** - Driver sees which outlets to pick up from initially
✅ **Consistent Structure** - All stops follow same outlet-based structure
✅ **Fallback Support** - Gracefully handles missing data

---

## 📝 Notes

- Starting outlet info is now explicit in backend response
- Frontend no longer relies on `tripData.from` for starting location
- All stops follow consistent outlet-based structure
- Progress bar adapts based on number of actual stops
- Modal shows outlet details for each stop point

