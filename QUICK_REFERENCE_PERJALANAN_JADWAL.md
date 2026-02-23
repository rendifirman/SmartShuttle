# QUICK REFERENCE: Integrasi Perjalanan dengan Jadwal Driver

## 🎯 Yang Dilakukan

Sistem perjalanan driver sudah diintegrasikan dengan jadwal driver sehingga:
1. **Data perjalanan** disesuaikan dengan **jadwal driver** yang dipilih
2. **Titik pemberhentian** ditampilkan dengan **outlets yang sesuai**
3. **Outlets diambil dari branch** yang ada dalam rute di jadwal

## 📍 Alur Singkat

```
Driver membuka "Perjalanan"
    ↓
Melihat daftar trip berdasarkan jadwal driver
    ↓
Klik "Lihat Detail" pada trip
    ↓
Melihat detail perjalanan dengan:
    - Titik awal (kota asal)
    - Titik pemberhentian (dengan outlets dari branch)
    - Tujuan akhir (kota tujuan)
```

## 🔧 File yang Diubah

### 1. `app/Http/Controllers/DriverController.php`
- **Tambah imports:** Outlet, Branch
- **Tambah ke perjalanan():**
  - Load `'jadwal.rutes'` di query
  - Panggil `getStopPointsFromSchedule($trip)`
  - Return `'stop_points'` di trip data
- **Tambah method:** `getStopPointsFromSchedule($trip)` → extract stop points dari jadwal

### 2. `resources/views/driver/perjalanan.blade.php`
- **Tambah function:** `buildJourneyDataFromStopPoints(tripData)` → membuat journey data dari stop points
- **Update function:** `showDetailPerjalanan(tripData)` → load stop_points dari fullTripData

## 📊 Data yang Dikirim

Setiap trip sekarang mengandung:

```javascript
{
  id_jadwal_driver: 1,
  from: "Jakarta",
  to: "Depok",
  date: "2026-02-19",
  time: "08:00",
  // ... existing fields ...
  
  // ★ BARU ★
  stop_points: [
    {
      urutan: 1,
      kota: "Jakarta",
      branch_name: "Cabang Jakarta Pusat",
      durasi_singgah: 10,
      outlets: [
        { id: 3, nama_outlet: "Sudirman", alamat: "...", kota: "Jakarta" },
        { id: 4, nama_outlet: "Blok M", alamat: "...", kota: "Jakarta" }
      ]
    },
    {
      urutan: 2,
      kota: "Depok",
      branch_name: "Cabang Depok",
      durasi_singgah: 15,
      outlets: [
        { id: 11, nama_outlet: "Margonda", alamat: "...", kota: "Depok" }
      ]
    }
  ]
}
```

## 💾 Database Structure yang Digunakan

```
driver_jadwals
  ├─ id_jadwal_driver
  └─ id_jadwal (FK)
     └─ jadwals
        └─ rutes
           ├─ rute_pemberhentian (JSON)
           │  └─ [
           │     {"kota": "Jakarta", "outlets": ["Sudirman", "Blok M"], ...},
           │     {"kota": "Depok", "outlets": ["Margonda"], ...}
           │   ]
           └─ (other rute fields)

branches
  ├─ id
  ├─ kota
  └─ outlets
     └─ outlets
        ├─ id
        ├─ nama_outlet
        ├─ status
        └─ (other outlet fields)
```

## 🧪 Test Cases

### ✅ Normal Case
- Jadwal dengan 2 rutes
- Setiap rute memiliki pemberhentian dengan outlets
- Branch ditemukan untuk setiap kota
- Outlets cocok dengan daftar di rute_pemberhentian
- **Result:** Stop points ditampilkan dengan outlets yang benar

### ✅ Fallback Case
- Jadwal tanpa rutes atau rute_pemberhentian kosong
- **Result:** Tampilkan default stops (fallback data)

### ✅ Edge Case
- Branch tidak ditemukan untuk kota tertentu
- Outlets tidak ditemukan atau tidak aktif
- **Result:** Stop diabaikan, lanjut ke stop berikutnya

## 🔍 Verifikasi

Jalankan test untuk verifikasi:
```bash
# Test 1: Data relationships
php test_integration_simple.php

# Test 2: Stop points function
php test_stop_points_function.php
```

## 📋 Checklist Deployment

- [ ] Backup database (jika diperlukan)
- [ ] Deploy DriverController.php
- [ ] Deploy perjalanan.blade.php
- [ ] Run `php artisan cache:clear`
- [ ] Test di `/driver/perjalanan`
- [ ] Verify stop points muncul dengan outlets

## ❓ FAQ

**Q: Bagaimana jika tidak ada stop_points?**
A: Frontend akan menggunakan fallback data (default stops)

**Q: Apakah perlu migrasi database?**
A: Tidak, menggunakan struktur yang sudah ada

**Q: Outlet mana yang ditampilkan?**
A: Outlets yang ada di:
- Table `outlets` dengan status 'aktif'
- Branch yang sesuai dengan kota di pemberhentian
- Nama outlet ada di daftar `rute_pemberhentian` untuk stop itu

**Q: Berapa stop points yang ditampilkan?**
A: Semua stops dari `rute_pemberhentian` yang memiliki outlets aktif

## 📞 Support

Jika ada masalah:
1. Check file logs: `storage/logs/laravel.log`
2. Run test scripts untuk debugging
3. Verify database data sesuai dengan checklist

---

**Last Updated:** 2026-02-19
**Status:** ✅ IMPLEMENTED & TESTED
