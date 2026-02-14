# FIX: Kursi Redirect Bug - User Balik ke Halaman Kursi Saat Submit

## Problem
Ketika user memilih kursi dan klik "Lanjutkan ke Detail Pesanan", halaman malah redirect **kembali ke halaman kursi** (bukan ke detail pesanan). Namun kursi yang dipilih **SUDAH tersimpan di database**.

### Gejala
1. ✗ User memilih kursi A, B, C
2. ✗ User klik "Lanjutkan ke Detail Pesanan"
3. ✗ Halaman balik ke kursi kembali
4. ✓ Tapi kursi sudah tersimpan di database

## Root Cause Analysis
Ditemukan masalah **DOUBLE INCREMENT** pada `driver_jadwal.kursi_terisi`:

### Alur Masalah:
```
1. User select kursi A → lockSeat() API AJAX
   → KursiTerpesan.create({status: 'terpesan'})
   → driver_jadwal.kursi_terisi += 1  (NOW = 1)

2. User select kursi B → lockSeat() API AJAX
   → KursiTerpesan.create({status: 'terpesan'})
   → driver_jadwal.kursi_terisi += 1  (NOW = 2)

3. User select kursi C → lockSeat() API AJAX
   → KursiTerpesan.create({status: 'terpesan'})
   → driver_jadwal.kursi_terisi += 1  (NOW = 3)

4. User SUBMIT form → prosesPemilihanKursi()
   → Validation Check Capacity:
      current_occupied = 3 (dari step 1-3)
      new_selected = 3 (dari $validated['kursi'])
      if (3 + 3 > total_kursi (mis. 9)) → PASS ✓ → continue
      
   → UPDATE KURSI (BUG TERJADI DI SINI):
      driver_jadwal.kursi_terisi += 3  (NOW = 6) ❌ DOUBLE!
      
   → RESULT:
      kursi_terisi menjadi 6, seharusnya 3
      Jika total_kursi = 6 atau kurang, maka booking selanjutnya akan FAIL
```

## Perbaikan Dilakukan

### 1. Logging yang Lebih Detail
Di `CustomerController::prosesPemilihanKursi()`, ditambahkan logging comprehensive:
- Log saat request dimulai
- Log untuk setiap validation yang fail
- Log untuk verification di driver_jadwal
- Output ke file: `storage/logs/laravel-*.log`

**File**: [app/Http/Controllers/CustomerController.php](app/Http/Controllers/CustomerController.php#L2606)

### 2. FIX: Jangan Increment Kursi_Terisi Lagi
**Sebelum:**
```php
// DOUBLE INCREMENT BUG!
if ($usesDriverJadwal) {
    $driverJadwal->kursi_terisi += $seatsAssigned;  // ❌ INCREMENT ULANG!
    $driverJadwal->save();
}
```

**Sesudah:**
```php
// PERBAIKAN: Hanya update status jika penuh, JANGAN increment ulang
if ($usesDriverJadwal) {
    $driverJadwal = $pemesanan->driverJadwal;
    
    // Verify actual count bukan increment
    $expectedOccupied = KursiTerpesan::where('id_jadwal_driver', $driverJadwal->id_jadwal_driver)
        ->where('status', 'terpesan')
        ->whereHas('pemesanan', function($q) {
            $q->whereNotIn('status', ['dibatalkan', 'expired']);
        })
        ->count();  // VERIFY, bukan increment!
    
    if ($expectedOccupied >= $driverJadwal->total_kursi) {
        $driverJadwal->status = 'penuh';  // Hanya update status
        $driverJadwal->save();
    }
}
```

**Alasan:**
- `kursi_terisi` sudah di-increment dari `lockSeat()` AJAX
- Di `prosesPemilihanKursi()`, hanya perlu update `detail_penumpang.nomor_kursi` dan set `detail_penumpang_id` di KursiTerpesan
- Jangan increment ulang untuk mencegah double-count

### 3. FIX: Jangan Create KursiTerpesan Baru, Update yang Sudah Ada
**Sebelum:**
```php
// BISA DOUBLE CREATE!
foreach ($detailPenumpang as $penumpang) {
    $nomorKursi = ...;
    KursiTerpesan::create($kursiData);  // ❌ CREATE BARU, PADAHAL SUDAH ADA FROM lockSeat()
}
```

**Sesudah:**
```php
// PERBAIKAN: Update existing KursiTerpesan dari lockSeat()
foreach ($detailPenumpang as $penumpang) {
    $nomorKursi = ...;
    
    // Find existing KursiTerpesan yang sudah dibuat dari lockSeat() AJAX
    $existingKursi = KursiTerpesan::where('nomor_kursi', $nomorKursi)
        ->where('pemesanan_id', $pemesanan->id)
        ->where('id_jadwal_driver', $pemesanan->id_jadwal_driver)
        ->first();
    
    if ($existingKursi) {
        // Update detail_penumpang_id, jangan create baru
        $existingKursi->update([
            'detail_penumpang_id' => $penumpang->id,
            'status' => 'terpesan'
        ]);
    } else {
        // Fallback: hanya jika tidak ditemukan (shouldn't happen)
        KursiTerpesan::create($kursiData);
    }
}
```

**Alasan:**
- `lockSeat()` AJAX sudah create KursiTerpesan record
- Di `prosesPemilihanKursi()`, hanya perlu update `detail_penumpang_id`
- Mencegah duplicate records dan double-increment

### 4. FIX: Validasi Lebih Ketat untuk Driver Jadwal
Ditambahkan validasi untuk memastikan kursi sudah di-lock:
```php
// VALIDASI: Kursi harus sudah di-lock (dari lockSeat AJAX)
$lockedSeatsForThisBooking = KursiTerpesan::where(...)
    ->whereIn('nomor_kursi', $validated['kursi'])
    ->where('pemesanan_id', $pemesanan->id)
    ->count();

if ($lockedSeatsForThisBooking !== count($validated['kursi'])) {
    // ERROR: Ada kursi yang belum di-lock!
    return redirect()->back()->with('error', '...');
}
```

## Files Modified
1. [app/Http/Controllers/CustomerController.php](app/Http/Controllers/CustomerController.php)
   - Line 2606-2650: Enhanced logging
   - Line 2700-2765: Fixed driver_jadwal validation
   - Line 2813-2900: Fixed kursi_terisi logic & KursiTerpesan update

## Testing Steps
### 1. Clear Cache
```bash
php artisan view:clear && php artisan cache:clear
```

### 2. Test Booking Flow
1. Login sebagai customer
2. Go ke booking page
3. Search & book shuttle (dengan 3 penumpang)
4. Go ke kursi page
5. **SELECT 3 KURSI** (misalnya A, B, C)
   - Verify: Kursi ada status "selected" dan ditampilkan di list
   - Verify: Button "Lanjutkan" menjadi enabled
6. **SUBMIT FORM** ("Lanjutkan ke Detail Pesanan")
   - ✅ HARUSNYA: Redirect ke detail_pesanan (bukan back ke kursi)
   - ✅ VERIFY: Nomor kursi sudah tersimpan di detail_penumpang

### 3. Check Logs
```bash
tail -f storage/logs/laravel-*.log
```

Cari untuk:
- `prosesPemilihanKursi START` → shows all basic info
- `VALIDATION FAILED` → jika ada error
- `Updated existing KursiTerpesan record` → shows update success
- `Seat Selection Completed` → shows completion

### 4. Database Verification
```sql
-- Check KursiTerpesan
SELECT * FROM kursi_terpesan WHERE pemesanan_id = 123;

-- Check DetailPenumpang
SELECT * FROM detail_penumpang WHERE pemesanan_id = 123;

-- Check DriverJadwal kursi_terisi
SELECT kursi_terisi, total_kursi FROM driver_jadwal WHERE id_jadwal_driver = '...';
```

## Expected Results After Fix
| Scenario | Before | After |
|----------|--------|-------|
| Select 3 kursi, submit form | ❌ Redirect balik ke kursi | ✅ Redirect ke detail_pesanan |
| Kursi tersimpan di database | ✓ Ya | ✓ Ya |
| kursi_terisi value | ❌ Double-counted | ✅ Correct count |
| KursiTerpesan records | ⚠️ Mungkin duplicate | ✅ Single record |
| Error alert di form | ⚠️ Tidak jelas | ✅ Detailed message |

## Additional Improvements
1. **Better logging** untuk debugging di production
2. **Fallback mechanism** jika lockSeat tidak dipanggil (e.g., browser back button)
3. **No double-increment** pada kursi_terisi
4. **Automatic status update** untuk driver_jadwal

## Notes untuk Developer
- Jangan ubah logic di `KursiController::lockSeat()` tanpa koordinasi dengan `prosesPemilihanKursi()`
- Selalu verify state dengan count() bukan increment
- Log semua validation failure untuk easier debugging
- Test dengan berbagai jumlah penumpang (1, 2, 3, 5 dst)

## Migration Steps (Production)
1. Deploy perubahan ke production
2. Clear cache: `php artisan view:clear && php artisan cache:clear`
3. Monitor logs untuk "VALIDATION FAILED" messages
4. Inform users kursi booking seharusnya lancar sekarang
