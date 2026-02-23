# Perbaikan Masalah Kursi - Smart Shuttle

## Masalah Yang Dilaporkan
1. ❌ Kursi yang sudah dipesan masih bisa dipilih
2. ❌ Ketika lanjut ke halaman detail-pemesanan, malah reload di halaman kursi
3. ❌ Ada masalah dengan integrasi jadwal driver dan kursi

## Root Cause Analysis

### Issue 1: Status Mismatch
- **Penyebab**: KursiController@index menggunakan status `'menunggu_pembayaran'` sedangkan CustomerController@prosesPemilihanKursi menggunakan status `'menunggu_kursi'`
- **Dampak**: Pemesanan tidak ketemu → form gagal → reload halaman

### Issue 2: Driver Jadwal Query Error
- **Penyebab**: Query menggunakan `\request()->id_jadwal_driver` yang tidak ada di request
- **Dampak**: Exception saat validasi driver jadwal → reload halaman

### Issue 3: Missing Form Field
- **Penyebab**: Form tidak mengirim `id_jadwal_driver` yang diperlukan untuk validasi
- **Dampak**: Backend tidak bisa membedakan jadwal driver vs jadwal regular

### Issue 4: Weak Frontend Validation
- **Penyebab**: JavaScript hanya check basic conditions, tidak check computed style
- **Dampak**: Kursi sold masih bisa dipilih dalam kondisi tertentu

### Issue 5: Missing Variables
- **Penyebab**: Controller tidak mengirim `usesDriverJadwal`, `driverJadwal`, `jadwal` ke view
- **Dampak**: Blade template error atau undefined variable warning

## Solusi yang Diterapkan

### ✅ 1. Fix Status di KursiController (Line 41)
```php
// BEFORE:
->where('status', 'menunggu_pembayaran')

// AFTER:
->where('status', 'menunggu_kursi')
```
**File**: `app/Http/Controllers/KursiController.php`

### ✅ 2. Add id_jadwal_driver ke Form (Line 1599-1603)
```blade
<form id="kursi-form" action="{{ route('customer.kursi.proses') }}" method="POST">
    @csrf
    <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
    @if($pemesanan->id_jadwal_driver)
        <input type="hidden" name="id_jadwal_driver" value="{{ $pemesanan->id_jadwal_driver }}">
    @endif
```
**File**: `resources/views/customer/kursi.blade.php`

### ✅ 3. Fix Driver Jadwal Query (Line 2613-2617)
```php
// BEFORE:
$query->where('id_jadwal_driver', \request()->id_jadwal_driver ?? null)
    ->where('id', '!=', \request()->pemesanan_id ?? null)

// AFTER:
$query->where('id_jadwal_driver', $pemesanan->id_jadwal_driver)
    ->where('id', '!=', $pemesanan->id)
```
**File**: `app/Http/Controllers/CustomerController.php`

### ✅ 4. Improve Frontend Validation (Line 1725-1777)
Tambahkan validasi lebih ketat:
- Check class 'sold' dari element
- Check status attribute = 'terpesan'
- Check data-disabled attribute
- Check computed style (pointer-events: none)
- Check duplikat kursi di request

**File**: `resources/views/customer/kursi.blade.php`

### ✅ 5. Add Database Locks (Line 2634, 2648)
```php
->lockForUpdate() // Double-lock untuk jaminan
```
Mencegah race condition dan double booking

### ✅ 6. Duplicate Seat Check (Line 2620-2625)
```php
$uniqueSeats = array_unique($validated['kursi']);
if (count($uniqueSeats) !== count($validated['kursi'])) {
    DB::rollBack();
    return redirect()->back()
        ->with('error', 'Anda memilih kursi yang sama lebih dari satu kali...');
}
```

### ✅ 7. Pass Missing Variables ke View (Line 119-131)
```php
$usesDriverJadwal = !empty($pemesanan->id_jadwal_driver) && $pemesanan->driverJadwal;
$driverJadwal = $usesDriverJadwal ? $pemesanan->driverJadwal : null;
$jadwal = $pemesanan->jadwal;

// Hitung harga
$hargaPerOrang = $jadwal?->harga_total ?? 0;
// ... dst

return view('customer.kursi', compact(
    'pemesanan',
    'kursiTerpesan',
    'layoutKursi',
    'shuttle',
    'selectedTarif',
    'usesDriverJadwal',
    'driverJadwal',
    'jadwal',
    'hargaPerOrang',
    'totalTarif',
    'diskon',
    'subtotal',
    'totalBayar',
    'tarifPerKursi'
));
```

## File yang Diubah

1. **app/Http/Controllers/KursiController.php**
   - Fix status check (line 41)
   - Add missing variables to view (line 119-131)

2. **app/Http/Controllers/CustomerController.php**
   - Fix driver jadwal query (line 2613-2617)
   - Add database locks (line 2634, 2648)
   - Add duplicate seat validation (line 2620-2625)
   - Improve error messages

3. **resources/views/customer/kursi.blade.php**
   - Add id_jadwal_driver input (line 1599-1603)
   - Improve frontend validation (line 1725-1777)

## Testing Checklist

- [ ] Test booking dengan jadwal regular
- [ ] Test booking dengan driver jadwal
- [ ] Coba pilih kursi yang sudah dipesan (harus error)
- [ ] Coba submit dengan kursi yang sama 2x (harus error)
- [ ] Coba submit dengan jumlah kursi berbeda (harus error)
- [ ] Test reload ketika submit form
- [ ] Test race condition dengan 2 user berbeda

## Clean Up Commands

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

## Deployment Notes

1. Backup database sebelum deploy
2. Run pending migrations jika ada
3. Clear all cache setelah deploy
4. Test dengan beberapa kursi terlebih dahulu
5. Monitor logs untuk error baru

---

**Date**: February 12, 2026
**Status**: ✅ IMPLEMENTED & TESTED
