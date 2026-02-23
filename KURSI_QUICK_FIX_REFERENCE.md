# QUICK FIX REFERENCE - Kursi Perbaikan

## Ringkas Masalah
- ✅ **Kursi sold masih bisa dipilih** → Fixed dengan lebih ketat validation
- ✅ **Reload di halaman kursi** → Fixed dengan status mismatch & query error
- ✅ **Masalah driver jadwal** → Fixed dengan proper variable passing & query

## Perbaikan Utama (7 Items)

### 1️⃣ Status Mismatch
```
File: KursiController.php (line 41)
Change: menunggu_pembayaran → menunggu_kursi
Why: Harus match dengan status yang dicheck di prosesPemilihanKursi
```

### 2️⃣ Add id_jadwal_driver Field
```
File: kursi.blade.php (line 1599-1603)
Add:  <input type="hidden" name="id_jadwal_driver" value="{{ ... }}">
Why: Backend perlu field ini untuk validasi driver jadwal flow
```

### 3️⃣ Fix Driver Jadwal Query
```
File: CustomerController.php (line 2613-2617)
Change: request()->id_jadwal_driver → $pemesanan->id_jadwal_driver
Why: Request tidak ada field ini, ambil dari object saja
```

### 4️⃣ Frontend Validation Lebih Ketat
```
File: kursi.blade.php (line 1725-1777)
Add: 5 validasi tambahan (class, status, disabled, computed style)
Why: Kursi sold harus fully blocked dari berbagai sudut
```

### 5️⃣ Database Lock
```
File: CustomerController.php (line 2634, 2648)
Add: ->lockForUpdate()
Why: Prevent race condition & double booking
```

### 6️⃣ Duplicate Seat Check
```
File: CustomerController.php (line 2620-2625)
Add: Check array_unique kursi
Why: User tidak boleh pilih kursi yang sama 2x
```

### 7️⃣ Pass Missing Variables
```
File: KursiController.php (line 119-131)
Add: usesDriverJadwal, driverJadwal, jadwal, harga data
Why: Blade template membutuhkan variables ini
```

## Testing Command

```bash
# 1. Clear cache
php artisan view:clear && php artisan cache:clear

# 2. Test form dengan curl (optional)
curl -X POST http://localhost:8000/customer/kursi/proses \
  -H "Content-Type: application/json" \
  -d '{"pemesanan_id": 1, "kursi": ["1", "2", "3"]}'

# 3. Check logs
tail -f storage/logs/laravel-2026-02-12.log
```

## Browser Testing Steps

1. **Login sebagai customer**
2. **Go to booking page** → Search & book shuttle
3. **Go to kursi page** → Verify:
   - ✅ Kursi sold tampil abu-abu & disable
   - ✅ Tidak bisa click kursi sold
   - ✅ Bisa select kursi tersedia
4. **Submit form** → Verify:
   - ✅ Tidak reload
   - ✅ Redirect ke detail pesanan
5. **Try another booking** dengan driver jadwal

## What's Fixed

| Issue | Before | After |
|-------|--------|-------|
| Kursi sold bisa dipilih | ❌ YES | ✅ NO |
| Frontend validation | ⚠️ Lemah | ✅ Ketat |
| Backend lock | ❌ Tidak ada | ✅ Ada |
| Reload di form | ❌ SERING | ✅ NO |
| Driver jadwal support | ⚠️ Error | ✅ Fixed |

## Files Modified

1. `app/Http/Controllers/KursiController.php` → 2 edits
2. `app/Http/Controllers/CustomerController.php` → 3 edits  
3. `resources/views/customer/kursi.blade.php` → 2 edits

**Total Changes**: 7 critical fixes
**Status**: ✅ READY FOR TEST

---

**Next Steps**:
1. Test semua booking flow (regular + driver jadwal)
2. Test concurrent bookings (race condition)
3. Monitor logs untuk error baru
4. Update documentation jika ada issue
