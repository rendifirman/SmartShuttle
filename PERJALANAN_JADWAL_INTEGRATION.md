# Integrasi Perjalanan dengan Jadwal Driver & Outlet Pemberhentian

## Ringkasan Perubahan

Sistem telah diubah untuk menyesuaikan data perjalanan (journey) dengan jadwal driver dan menampilkan titik pemberhentian sesuai dengan outlets yang ada di branch yang terkait dengan rute dalam jadwal.

## Alur Data

```
DriverJadwal (id_jadwal_driver)
    ↓
Jadwal (with rutes)
    ↓
Rute (rute_pemberhentian JSON)
    ↓
Branch (dari kota di pemberhentian)
    ↓
Outlet (dari branch dengan status aktif)
    ↓
Frontend: Tampilkan di Stop Points
```

## Perubahan Backend

### File: `app/Http/Controllers/DriverController.php`

#### Perubahan 1: Tambah Imports
```php
use App\Models\Outlet;
use App\Models\Branch;
```

#### Perubahan 2: Method `perjalanan()`
- Tambah `with(['jadwal.rutes'])` pada query DriverJadwal
- Tambah `'stop_points' => $stopPoints` pada setiap trip data
- Panggil `getStopPointsFromSchedule($trip)` untuk mengambil pemberhentian

#### Perubahan 3: Method Baru `getStopPointsFromSchedule($trip)`
```php
/**
 * Mengambil titik pemberhentian dari jadwal driver
 * - Ambil rutes dari jadwal
 * - Parse rute_pemberhentian dari setiap rute
 * - Cari branch berdasarkan kota
 * - Ambil outlets aktif dari branch
 * - Return array structured stop points dengan outlets
 */
private function getStopPointsFromSchedule($trip): array
```

### Data Structure yang Dikembalikan

```php
'stop_points' => [
    [
        'urutan' => 1,
        'kota' => 'Jakarta',
        'branch_id' => 1,
        'branch_name' => 'PT Smart Shuttle - Jakarta',
        'durasi_singgah' => 10,
        'outlets' => [
            [
                'id' => 1,
                'nama_outlet' => 'Terminal Pusat',
                'alamat' => 'Jl. Merdeka No. 1',
                'kota' => 'Jakarta'
            ],
            ...
        ]
    ],
    [
        'urutan' => 2,
        'kota' => 'Bandung',
        'branch_id' => 2,
        'branch_name' => 'PT Smart Shuttle - Bandung',
        'durasi_singgah' => 15,
        'outlets' => [...]
    ],
    ...
]
```

## Perubahan Frontend

### File: `resources/views/driver/perjalanan.blade.php`

#### Perubahan 1: Function Baru `buildJourneyDataFromStopPoints(tripData)`
- Menerima `tripData` dengan `stop_points`
- Membuat struktur `journeyData.stops` yang sesuai
- Setiap stop berisi:
  - `name`: nama kota/stop
  - `detail`: detail dengan nama branch dan outlets
  - `type`: "start", "stop", atau "finish"
  - `outlets`: array outlets di stop ini
  - `duration`: durasi singgah

#### Perubahan 2: Update `showDetailPerjalanan(tripData)`
- Cari full trip data dari `tripsData` menggunakan tripId
- Panggil `buildJourneyDataFromStopPoints()` dengan data lengkap
- Initialize `journeyData` dengan stop points yang benar

## Alur Penggunaan

### 1. Load Halaman Perjalanan
```
GET /driver/perjalanan
↓
DriverController@perjalanan()
├─ Query trips dengan jadwal dan rutes
├─ Loop untuk setiap trip:
│  ├─ Ambil bookings & passengers
│  ├─ Call getStopPointsFromSchedule()
│  └─ Return stop_points
├─ Pass $tripsData ke view
└─ Render view dengan JS data
```

### 2. Load Detail Perjalanan (Frontend)
```
Click "Lihat Detail" pada trip item
↓
showDetailPerjalanan(tripData)
├─ Cari full trip data dari tripsData
├─ Extract stop_points
└─ buildJourneyDataFromStopPoints()
   ├─ Create journeyData.stops array
   │  ├─ Start point
   │  ├─ Each stop dengan outlets
   │  └─ Finish point
   └─ updateJourneyDisplay()
      ├─ Update UI dengan stop points
      ├─ Show outlets di setiap stop
      └─ Update progress bar
```

## Fitur Tambahan

### Stop Points Display
Ketika user membuka detail perjalanan, akan menampilkan:

1. **Titik Awal** (Start Point)
   - Kota asal dari jadwal

2. **Titik Pemberhentian** (Stop Points)
   - Untuk setiap stop:
     - Nama kota
     - Nama branch
     - Daftar outlets aktif di branch tersebut
     - Durasi singgah

3. **Tujuan Akhir** (Finish Point)
   - Kota tujuan dari jadwal

### Outlet Information
Setiap outlet menampilkan:
- Nama outlet
- Alamat lengkap
- Kota/branch

## Testing

### Test Case 1: Jadwal dengan Multiple Outlets
```
Jadwal: Jakarta → Bandung
Rute pemberhentian:
- Jakarta: Terminal Pusat, Terminal Bekasi
- Bandung: Terminal Utama

Expected stop points:
- Stop 1: Jakarta (2 outlets)
- Stop 2: Bandung (1 outlet)
```

### Test Case 2: Jadwal tanpa Outlets
```
Jadwal: Jakarta → Surabaya
Rute pemberhentian: Kosong

Expected result:
- Fallback data (default stops)
```

### Test Case 3: Branch tidak Ditemukan
```
Jadwal dengan rute pemberhentian kota yang tidak ada di master branch

Expected result:
- Stop diabaikan
- Lanjut ke stop berikutnya
```

## Error Handling

- Try-catch di `getStopPointsFromSchedule()` untuk handle error
- Log error ke file log
- Return empty array jika error
- Frontend fallback ke default stops jika kosong

## Performance Optimization

### Query Optimization
- With `['jadwal.rutes']` untuk avoid N+1 query
- Single loop untuk setiap trip

### Frontend Optimization
- Data stop points sudah di-render dari backend (no additional API call)
- JavaScript caching untuk `tripsData`
- Efficient DOM manipulation

## Future Improvements

1. Cache stop points data
2. Add real distance calculation between stops
3. Real-time location tracking
4. Navigation integration (Google Maps)
5. Estimated arrival time calculation

## Backward Compatibility

- Fallback mechanism jika stop_points kosong
- Existing JavaScript logic untuk default journey data masih berfungsi
- No breaking changes untuk existing features
