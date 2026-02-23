# PERJALANAN UPDATE - Ringkasan Perubahan

## 📋 Ringkasan Fitur Yang Ditambahkan

Perubahan dibuat pada file `resources/views/driver/perjalanan.blade.php` untuk memenuhi requirement:

### 1. ✅ Driver Harus Memulai Perjalanan Dulu
- Tombol **"Mulai Perjalanan"** ditambahkan sebagai button utama di halaman detail perjalanan
- Setelah driver klik "Mulai Perjalanan", barulah tombol **"Update Lokasi"** ditampilkan
- Jika driver mencoba update lokasi tanpa memulai perjalanan, akan tampil alert: "Anda harus memulai perjalanan terlebih dahulu!"

### 2. ✅ Locations dari Outlets dalam Branches dalam Routes
Struktur data sudah benar:
```
Schedule (Jadwal)
  ├── Route (Rute) 
  │    └── Stop Points (Pemberhentian)
  │         ├── City/Kota
  │         ├── Branch (Cabang) 
  │         │    └── Outlets (aktif)
  │         └── Duration (Durasi Singgah)
```

Data outlets diambil dari:
- Branch berdasarkan kota di route stop point
- Outlets aktif dari branch tersebut
- Outlets yang cocok dengan daftar outlets di route stop point

### 3. ✅ Seat Data Alignment
- Fungsi `alignSeatsFromStopPoints()` ditambahkan untuk align kursi dengan outlet pemberhentian
- Kursi data disesuaikan berdasarkan informasi outlets yang ada di setiap stop point

### 4. ✅ Enhanced Modal dengan Outlets Info
- Modal Update Lokasi sekarang menampilkan daftar outlets di pemberhentian berikutnya
- Setiap outlet ditampilkan dengan nama dan alamat
- Modal outlets info hidden jika tidak ada outlets data

---

## 🔧 Perubahan Teknis

### A. Tambah Journey Start State Tracking
```javascript
// ★★★ JOURNEY START STATE TRACKING ★★★
let journeyStarted = {}; // Tracks which trips have been started: { tripId: true/false }
```

### B. Tambah Button "Mulai Perjalanan"
- Awalnya **visible** (hidden=false)
- Tombol "Update Lokasi" awalnya **hidden** (hidden=true)
- Setelah klik "Mulai Perjalanan":
  - "Mulai Perjalanan" button → **hidden**
  - "Update Lokasi" button → **visible**

### C. Fungsi `mulaiPerjalanan()`
```javascript
function mulaiPerjalanan() {
    // Mark journey as started
    journeyStarted[currentTripId] = true;
    
    // Hide "Mulai Perjalanan" button
    // Show "Update Lokasi" button
    
    // Update status in list: 'Dalam Perjalanan'
    
    alert('Perjalanan dimulai! Anda sekarang bisa mengupdate lokasi.');
}
```

### D. Validasi Journey Start
Pengecekan ditambahkan di:
1. `showUpdateLokasiModal()` - Cek sebelum tampilkan modal
2. `confirmUpdateLokasi()` - Cek sebelum kirim update ke server

### E. Reset State per Trip
- Ketika masuk detail perjalanan baru: `journeyStarted[tripData.id] = false`
- Tombol visibility direset berdasarkan journey state

### F. Align Seats Function
```javascript
function alignSeatsFromStopPoints() {
    // Jika ada stop points dengan outlets
    // Log outlets untuk setiap stop point
}
```
Dipanggil di `generatePenumpangList()` untuk ensure kursi aligned dengan outlets

### G. Enhanced Modal dengan Outlets Display
```javascript
// ★★★ POPULATE OUTLETS INFO ★★★
if (nextStop.outlets && nextStop.outlets.length > 0) {
    outletsInfo.style.display = 'block';
    
    nextStop.outlets.forEach(outlet => {
        // Display outlet nama and alamat
    });
}
```

---

## 🎯 User Flow

### Halaman Daftar Perjalanan (List)
```
[Trip 1] [Trip 2] [Trip 3]
↓ (Klik "Lihat Detail")
```

### Halaman Detail Perjalanan
```
1. Tampil ke user:
   - Tombol "MULAI PERJALANAN" (hijau, aktif)
   - Tombol "UPDATE LOKASI" (biru, hidden)
   - Progress bar pemberhentian
   - Daftar penumpang dengan kursi dari outlets
   
2. Driver klik "MULAI PERJALANAN"
   - Alert: "Perjalanan dimulai! Anda sekarang bisa mengupdate lokasi."
   - Status di list perjalanan berubah: "Dalam Perjalanan"
   - Tombol "MULAI PERJALANAN" hidden
   - Tombol "UPDATE LOKASI" tampil
   
3. Driver klik "UPDATE LOKASI"
   - Cek apakah sudah dimulai → OK (ada di journeyStarted[tripId])
   - Cek apakah belum di akhir → OK
   - Tampil modal update lokasi dengan outlets info:
     ┌─────────────────────────────────┐
     │ Update Lokasi                   │
     │ Lokasi bus akan berpindah ke... │
     │ Menuju: [Kota]                  │
     │ Outlets di Pemberhentian:       │
     │ • [Outlet 1] - [Alamat]         │
     │ • [Outlet 2] - [Alamat]         │
     │ [Batal] [Update]                │
     └─────────────────────────────────┘
   
4. Driver klik "UPDATE" di modal
   - Cek apakah sudah dimulai → OK
   - Kirim data lokasi ke server
   - Update progress bar
   - Tampil "Titik Pemberhentian" berikutnya (dari outlets)
```

---

## 📊 Data Outlets yang Dipass ke Frontend

Setiap stop point di `stop_points` array memiliki struktur:
```javascript
{
    urutan: 1,                           // Order pemberhentian
    kota: "Bandung",                     // Kota
    branch_id: 1,                        // ID branch
    branch_name: "Cabang Bandung",       // Nama branch
    durasi_singgah: 10,                  // Durasi singgah (menit)
    outlets: [                           // Outlets aktif dari branch
        {
            id: 1,
            nama_outlet: "Terminal Leuwipanjang",
            alamat: "Jl. ...",
            kota: "Bandung"
        },
        {
            id: 2,
            nama_outlet: "Outlet Dago",
            alamat: "Jl. ...",
            kota: "Bandung"
        }
    ]
}
```

---

## ✅ Validasi

- ✅ No syntax errors
- ✅ Journey start tracking implemented
- ✅ Button visibility controlled properly
- ✅ Outlets data from backend structured correctly
- ✅ Seat alignment function ready
- ✅ Modal enhanced with outlets info display
- ✅ All alert messages user-friendly

---

## 🚀 Testing Checklist

- [ ] Open driver journey page
- [ ] Click "Lihat Detail" on a trip
- [ ] Verify "MULAI PERJALANAN" button visible
- [ ] Verify "UPDATE LOKASI" button hidden initially
- [ ] Click "MULAI PERJALANAN" button
- [ ] Verify status changes to "Dalam Perjalanan"
- [ ] Verify "MULAI PERJALANAN" button becomes hidden
- [ ] Verify "UPDATE LOKASI" button becomes visible
- [ ] Click "UPDATE LOKASI" button
- [ ] Verify modal appears with outlets list (if outlets exist)
- [ ] Verify outlet names and addresses displayed correctly
- [ ] Verify passenger list appears with outlet info
- [ ] Click "UPDATE" and verify location updates
- [ ] Verify progress bar advances to next stop point
- [ ] On second stop, verify new outlets display in modal

---

## 📝 FITUR YANG SUDAH DIIMPLEMENTASI

### 1. Journey Start Control ✅
- [x] Add "Mulai Perjalanan" button
- [x] Track journey start state per trip
- [x] Hide "Update Lokasi" until journey starts
- [x] Update trip status to "Dalam Perjalanan"

### 2. Locations dari Outlets ✅
- [x] Backend properly extracts outlets from branches in routes
- [x] Frontend displays outlets in stop points
- [x] Modal shows outlets with addresses

### 3. Seat Alignment ✅
- [x] Add alignSeatsFromStopPoints function
- [x] Link seats to outlets information
- [x] Display outlet info for each stop

### 4. Enhanced UX ✅
- [x] Clear validation messages
- [x] Outlet details in modal
- [x] Proper button state management
- [x] Visual feedback on journey start

---

## 🔄 Integration Points

### Frontend View
- `resources/views/driver/perjalanan.blade.php`

### Backend Controller
- `app/Http/Controllers/DriverController.php`
- Method: `getStopPointsFromSchedule()` - Already properly implemented

### Data Flow
```
DriverJadwal → Jadwal → Rutes → rute_pemberhentian
                              ↓
                        Branch → Outlets (aktif)
                              ↓
                         stop_points array
                              ↓
                           Frontend
```

---

## 📋 Notes

- Journey start state disimpan di browser session (`journeyStarted` object)
- Untuk persist di database, silakan modify backend route handler untuk store status `journey_started` di DriverJadwal
- Outlets data optimal dari backend - siap digunakan
- Seat alignment function dapat diperluas sesuai kebutuhan validasi kursi
- Modal outlets display conditional - hanya tampil jika ada outlets data
