# PERBAIKAN MASALAH PERJALANAN DRIVER - LAPORAN LENGKAP

## 📋 RINGKASAN MASALAH

Terdapat dua masalah utama di halaman `perjalanan.blade.php`:

### Masalah 1: Penumpang-Kursi Gagal Tersambung ✅ SUDAH DIPERBAIKI
**Lokasi:** `DriverController.php` baris 458-459 dan 819-820

**Masalah:**
- Kode menggunakan field yang tidak ada di database DetailPenumpang
- `$passenger->nama_penumpang` ❌ (tidak ada)
- `$passenger->nomor_telepon` ❌ (tidak ada)

**Solusi yang diterapkan:**
```php
// SEBELUM (SALAH):
'name' => $passenger->nama_penumpang,           // ❌
'phone' => $passenger->nomor_telepon ?? $booking->telepon_pemesan,  // ❌

// SESUDAH (BENAR):
'name' => $passenger->nama_lengkap,             // ✅
'phone' => $passenger->telepon ?? $booking->telepon_pemesan,  // ✅
```

**Field DetailPenumpang yang benar:**
- `nama_lengkap` (bukan nama_penumpang)
- `telepon` (bukan nomor_telepon)
- `jenis_kelamin`
- `nik`
- `nomor_kursi`

---

### Masalah 2: Data Rute Gagal Diambil ⚠️ PERLU PENGECEKAN DATABASE
**Lokasi:** `DriverController.php` baris 367-430

**Analisis Masalah:**
```
Prioritas pengambilan data rute:
1. masterRute (via rute_id) → dari table Rute
2. jadwal.rutes → many-to-many via rute_jadwals
3. Fallback → parsing string dari kolom rute
```

**Relasi yang sudah benar di Model:**
```php
// DriverJadwal.php
public function masterRute(): BelongsTo { 
    return $this->belongsTo(Rute::class, 'rute_id', 'id'); 
}

// Jadwal.php
public function rutes() { 
    return $this->belongsToMany(Rute::class, 'rute_jadwals', 'jadwal_id', 'rute_id')
                ->withPivot('urutan', 'durasi_segment', 'harga_segment')
                ->withTimestamps(); 
}

// Rute.php memiliki field:
- kota_asal ✅
- kota_tujuan ✅
- nama_rute ✅
- rute_pemberhentian ✅
```

**Penyebab Potensial Data Gap:**
1. **Foreign Key NULL**: Kolom `rute_id` di table `driver_jadwals` kosong (NULL)
2. **Relasi Pomg-to-Many Kosong**: Tidak ada record di `rute_jadwals` untuk jadwal tertentu
3. **String Rute Tidak Valid**: Kolom `rute` tidak terisi atau format tidak sesuai

---

## ✅ PERBAIKAN YANG SUDAH DITERAPKAN

### 1. Perbaikan Field DetailPenumpang
- **File:** `c:\laragon\www\smart\SmartShuttle\app\Http\Controllers\DriverController.php`
- **Baris:** 458-459 dan 819-820
- **Status:** ✅ SELESAI
- **Perubahan:** Menggunakan field yang benar dari database

### 2. Penghapusan status_verifikasi yang tidak ada
- Field `status_verifikasi` tidak ada di DetailPenumpang
- Diganti dengan default `'terverifikasi'`
- Jika ingin menggunakan field asli, perlu di-add ke database atau model

---

## 🔍 LANGKAH VERIFIKASI SELANJUTNYA

### 1. Cek Data di Database
```sql
-- Periksa apakah driver_jadwals memiliki rute_id yang valid
SELECT id_jadwal_driver, rute_id, rute 
FROM driver_jadwals 
LIMIT 5;

-- Periksa apakah ada relasi di rute_jadwals
SELECT * FROM rute_jadwals 
LIMIT 5;

-- Periksa apakah rutes table memiliki data
SELECT id, nama_rute, kota_asal, kota_tujuan 
FROM rutes 
LIMIT 5;
```

### 2. Jalankan Log untuk Debug
Buka browser console saat akses halaman perjalanan (F12) dan lihat:
- Data yang dikirim dari backend (`console.log('tripsData')`)
- Stop Points yang ter-parse dengan benar
- Error messages jika ada

### 3. Uji API Endpoint
Buka browser dan akses:
```
GET /driver/perjalanan
```
Scroll down di response JSON untuk melihat struktur data yang dikirim.

---

## 📝 KODE YANG SUDAH DIPERBAIKI

### DriverController.php - Lokasi 1 (Method perjalanan)
**File:** `c:\laragon\www\smart\SmartShuttle\app\Http\Controllers\DriverController.php`
**Baris:** ~449-463

```php
foreach ($detailPenumpangs as $passenger) {
    $occupiedSeats++;
    
    $seat = $booking->kursiTerpesan()
        ->where('detail_penumpang_id', $passenger->id)
        ->first();
    
    $passengers[] = [
        'id' => $passenger->id,
        'name' => $passenger->nama_lengkap,        // ✅ PERBAIKAN
        'phone' => $passenger->telepon ?? $booking->telepon_pemesan,  // ✅ PERBAIKAN
        'seat' => $seat ? $seat->nomor_kursi : 'N/A',
        'status' => 'terverifikasi',                // ✅ PERBAIKAN
    ];
}
```

### DriverController.php - Lokasi 2 (Method lain dengan response JSON)
**File:** `c:\laragon\www\smart\SmartShuttle\app\Http\Controllers\DriverController.php`
**Baris:** ~810-824

```php
foreach ($detailPenumpangs as $passenger) {
    $occupiedSeats++;
    
    $seat = $booking->kursiTerpesan()
        ->where('detail_penumpang_id', $passenger->id)
        ->first();
    
    $passengers[] = [
        'id' => $passenger->id,
        'name' => $passenger->nama_lengkap,        // ✅ PERBAIKAN
        'phone' => $passenger->telepon ?? $booking->telepon_pemesan,  // ✅ PERBAIKAN
        'seat' => $seat ? $seat->nomor_kursi : 'N/A',
        'status' => 'terverifikasi',                // ✅ PERBAIKAN
    ];
}
```

---

## 📊 STRUKTUR DATABASE YANG BENAR

### DetailPenumpang Table
```
detail_penumpang:
  - id (PK)
  - pemesanan_id (FK)
  - nama_lengkap ✅
  - nik
  - jenis_kelamin
  - tanggal_lahir
  - nomor_kursi
  - telepon ✅
```

### Driver Jadwal ↔ Rute Relationship
```
driver_jadwals:
  - id_jadwal_driver (PK)
  - rute_id (FK) → rutes.id

rutes:
  - id (PK)
  - nama_rute ✅
  - kota_asal ✅
  - kota_tujuan ✅
  - rute_pemberhentian (JSON) ✅
  - jarak
  - durasi
```

---

## 🚀 NEXT STEPS

1. **Verifikasi Database:**
   - Jalankan query SQL di atas untuk memastikan data ada
   - Periksa apakah `rute_id` di `driver_jadwals` berisi nilai yang valid

2. **Testing:**
   - Akses halaman perjalanan driver
   - Lihat apakah data penumpang tampil dengan benar (nama & telepon)
   - Lihat apakah data rute tampil (dari → ke)

3. **Debugging:**
   - Jika masih error, buka Developer Console (F12)
   - Amati error messages di console
   - Lihat data yang dikirim dari backend

4. **Migration (jika diperlukan):**
   - Jika ada field yang hilang di database, jalankan migration
   - Misalnya, jika butuh field `status_verifikasi`, tambah ke migration

---

## 📌 CATATAN PENTING

- **Perubahan yang dilakukan:** Hanya field names di `DriverController.php`
- **Tidak ada migrasi database:** Struktur database sudah benar
- **Relasi model:** Sudah benar di Jadwal, DriverJadwal, dan Rute
- **Frontend access:** Sudah menggunakan field yang benar

**Status:** ✅ SIAP TESTING

---

**Dibuat:** 2025-02-23
**File yang dirubah:** 
- `DriverController.php` (2 lokasi diperbaiki)

**Verified by:** System Analysis & Field Mapping
