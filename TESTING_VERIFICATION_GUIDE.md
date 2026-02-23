# PANDUAN TESTING & VERIFIKASI PERBAIKAN PERJALANAN DRIVER

## ✅ STATUS PERBAIKAN

### Perbaikan 1: Field DetailPenumpang ✅ SELESAI
- **File:** `DriverController.php`
- **Lokasi:** Baris 458-459 & 819-820
- **Perubahan:** 
  - `$passenger->nama_penumpang` → `$passenger->nama_lengkap`
  - `$passenger->nomor_telepon` → `$passenger->telepon`
  - Hapus `status_verifikasi` (field tidak ada), ganti default dengan `'terverifikasi'`
- **Status Syntax:** ✅ No syntax errors detected

---

## 🧪 TESTING CHECKLIST

### 1. Backend Testing

#### 1.1 Verifikasi Database
```bash
# Login ke MySQL/Database
# Jalankan query berikut:

-- Cek struktur table detail_penumpang
DESCRIBE detail_penumpang;
-- Harapan: Fields = id, pemesanan_id, nama_lengkap, nik, jenis_kelamin, telepon, nomor_kursi, timestamps

-- Cek data sample
SELECT id, pemesanan_id, nama_lengkap, jenis_kelamin, telepon 
FROM detail_penumpang 
LIMIT 5;

-- Cek data di driver_jadwals
SELECT id_jadwal_driver, rute_id, rute, tanggal, waktu_keberangkatan 
FROM driver_jadwals 
LIMIT 5;

-- Cek relasi di rute_jadwals
SELECT jadwal_id, rute_id, urutan 
FROM rute_jadwals 
LIMIT 5;
```

#### 1.2 Verifikasi Model Relationships
```bash
# Terminal
cd c:/laragon/www/smart/SmartShuttle

# Test model relationship via Artisan
php artisan tinker

# Di Tinker shell:
>>> $booking = App\Models\Pemesanan::first();
>>> $booking->detailPenumpang()->first(); // Cek ada data?
>>> $passenger = $booking->detailPenumpang()->first();
>>> echo $passenger->nama_lengkap;  // Harapan: Nama valid
>>> echo $passenger->telepon;       // Harapan: Nomor telepon
>>> exit;
```

---

### 2. Frontend Testing

#### 2.1 Setup Test Environment
1. Buka browser (Chrome/Firefox)
2. Login ke aplikasi sebagai Driver
3. Navigasi ke halaman "Perjalanan"

#### 2.2 Test Halaman Perjalanan
```
✓ Halaman Daftar Perjalanan:
  [ ] Data rute tampil (XXX → YYY)
  [ ] Data waktu tampil
  [ ] Kursi tampil (X/Y)
  [ ] Status tampil dengan benar

✓ Halaman Detail Perjalanan:
  [ ] Klik "Lihat Detail" pada salah satu perjalanan
  [ ] Halaman detail terbuka tanpa error
  [ ] Data penumpang terload di "Daftar Penumpang"
```

#### 2.3 Verifikasi Data Penumpang
1. Buka Developer Console: **F12**
2. Tab **Console**, pastikan tidak ada error merah
3. Jalankan perintah ini di console:
```javascript
// Lihat data yang dikirim dari backend
console.log('Trips Data:', tripsData);

// Lihat data penumpang di trip pertama
if (tripsData.length > 0) {
    console.log('First Trip Passengers:', tripsData[0].passengers);
    // Harapan: Array of passengers dengan struktur:
    // {
    //   id: 1,
    //   name: "Ahmad Supriadi",        ← nama_lengkap
    //   phone: "081234567890",         ← telepon
    //   seat: "A1",
    //   status: "terverifikasi"
    // }
}
```

#### 2.4 Periksa Daftar Penumpang
1. Di halaman detail perjalanan, scroll ke bawah
2. Lihat section "Daftar Penumpang"
3. Verifikasi:
   - [ ] Nama penumpang tampil dengan benar
   - [ ] Nomor telepon tampil (jika ada)
   - [ ] Nomor kursi tampil
   - [ ] Status terverifikasi tampil

```
Expected Output:
┌─────────────────────────────────┐
│ DAFTAR PENUMPANG                │
│ Total: 5 penumpang              │
├─────────────────────────────────┤
│ Ahmad Supriadi                  │
│ 081234567890                    │
│         Kursi: A1  ✓ Terveri... │
├─────────────────────────────────┤
│ Budi Santoso                    │
│ 082234567890                    │
│         Kursi: A2  ✓ Terverif.. │
└─────────────────────────────────┘
```

---

### 3. Database Field Verification

#### 3.1 Verifikasi Field Tepat
```bash
mysql> SELECT 
    pd.id,
    pd.nama_lengkap,        # ← Harus ada
    pd.telepon,             # ← Harus ada
    pd.jenis_kelamin,
    pd.nomor_kursi
FROM detail_penumpang pd
LIMIT 1;
```

**Harapan:**
- `nama_lengkap`: Filled (tidak boleh kosong untuk passenger aktif)
- `telepon`: Filled atau NULL (tergantung data)
- `jenis_kelamin`: 'L' atau 'P' atau NULL
- `nomor_kursi`: String 'A1', 'A2', dll

#### 3.2 Verifikasi Relasi Rute
```bash
mysql> SELECT 
    dj.id_jadwal_driver,
    dj.rute_id,             # ← Foreign key
    r.nama_rute,            # ← Target table
    r.kota_asal,
    r.kota_tujuan
FROM driver_jadwals dj
LEFT JOIN rutes r ON dj.rute_id = r.id
LIMIT 5;
```

**Harapan:**
- `rute_id`: Tidak NULL atau memiliki nilai valid
- `nama_rute`: Filled (misalnya "Bandung - Jakarta")
- `kota_asal`: Filled (misalnya "Bandung")
- `kota_tujuan`: Filled (misalnya "Jakarta")

---

### 4. Error Troubleshooting

#### 4.1 Jika Nama/Telepon Tidak Tampil
**Penyebab: Field mungkin kosong di database**

```bash
# Check data di detail_penumpang
mysql> SELECT COUNT(*) as empty_nama 
FROM detail_penumpang 
WHERE nama_lengkap IS NULL OR nama_lengkap = '';

mysql> SELECT COUNT(*) as empty_telepon 
FROM detail_penumpang 
WHERE telepon IS NULL OR telepon = '';
```

**Solusi:**
- Populate data yang kosong dari seeder atau data migration
- Update booking untuk memastikan detail_penumpang terisi

#### 4.2 Jika Rute Tidak Tampil
**Penyebab: Foreign key null atau relasi kosong**

```bash
# Check apakah rute_id kosong
mysql> SELECT COUNT(*) as null_rute_id 
FROM driver_jadwals 
WHERE rute_id IS NULL;

# Check apakah ada di rute_jadwals
mysql> SELECT COUNT(*) FROM rute_jadwals;
```

**Solusi:**
- Populate `rute_id` di `driver_jadwals`
- Atau populate `rute_jadwals` junction table
- Jalankan soft migration atau seeder

#### 4.3 Jika Ada JavaScript Error di Console
**Debugging steps:**

1. **Buka F12 → Console**
2. **Catat error message**
3. **Lihat Network tab** untuk melihat response dari server
4. **Jalankan:**
   ```javascript
   // Di console, lihat raw data dari server
   console.table(tripsData);
   
   // Cek struktur passengers
   console.table(tripsData[0]?.passengers);
   ```

---

## 📋 QA VERIFICATION FORM

```
TESTING REPORT - Perbaikan Perjalanan Driver
═══════════════════════════════════════════════

Environment:
□ Development    □ Staging    □ Production
Date: _______________
Tester: _______________

BACKEND TESTS:
═════════════
□ Syntax check passed (php -l)
□ Model relationships working (tinker test)
□ Database fields verified
  □ detail_penumpang.nama_lengkap exists
  □ detail_penumpang.telepon exists
  □ driver_jadwals.rute_id populated
  □ rutes table has data

FRONTEND TESTS:
═══════════════
□ Halaman perjalanan loads without error
□ Trip list displays correctly
□ Detail perjalanan opens correctly
□ Passenger names display (nama_lengkap)
□ Passenger phone numbers display (telepon)
□ Seat numbers display
□ Status shows "Terverifikasi"

CRITICAL TESTS:
═══════════════
□ No console.error messages
□ No red error messages in browser
□ Data matches database queries
□ Mobile responsive (optional)

ISSUES FOUND:
═════════════
1. ________________
2. ________________
3. ________________

RECOMMENDATION:
□ Ready for Production
□ Needs Minor Fixes
□ Needs Major Fixes
□ Rollback Required

Sign Off: __________________ Date: __________
```

---

## 🚀 DEPLOYMENT STEPS

### Pre-Deployment
1. [ ] Backup database
2. [ ] Backup application files
3. [ ] Test in staging environment
4. [ ] Verify all QA tests pass

### Deployment
1. [ ] Pull latest changes
   ```bash
   cd c:/laragon/www/smart/SmartShuttle
   git pull origin main
   ```

2. [ ] No migrations needed (database structure unchanged)

3. [ ] Clear cache (if needed)
   ```bash
   php artisan view:clear
   php artisan config:clear
   ```

4. [ ] Test in production environment

### Post-Deployment
1. [ ] Monitor error logs
2. [ ] Check user reports
3. [ ] Verify functionality in production
4. [ ] Update documentation

---

## 📞 SUPPORT & ESCALATION

**If issues occur:**

1. **Check logs:**
   ```bash
   # Laravel logs
   tail -f storage/logs/laravel-*.log
   
   # Browser console (F12)
   ```

2. **Rollback (if needed):**
   ```bash
   git revert <commit-hash>
   php artisan view:clear
   ```

3. **Contact:**
   - Dev Team
   - Database Admin
   - System Admin

---

**Last Updated:** 2025-02-23
**Status:** ✅ Ready for Testing
