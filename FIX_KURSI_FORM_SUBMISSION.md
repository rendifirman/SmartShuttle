# FIX: Masalah Form Kursi Reload Tanpa Error

## Masalah yang Ditemukan

Ketika user memilih kursi dan mengklik "Lanjutkan ke Detail Pesanan", halaman malah reload kembali ke halaman kursi tanpa menampilkan pesan error. Ini membuat sulit untuk mengetahui apa yang sebenarnya terjadi.

### Root Cause Analysis

Penyebab masalah ada 2:

### 1. **Controller Menggunakan Format Error yang Salah**

Di `CustomerController@prosesPemilihanKursi`, ketika validasi gagal, controller mengirim error dengan format yang tidak konsisten:

**SEBELUM (SALAH):**
```php
return redirect()->back()->with('error', 'Pesan error...');
```

**SESUDAH (BENAR):**
```php
return redirect()->back()
    ->with('alert-type', 'error')
    ->with('alert-title', 'Judul Error')
    ->with('alert-message', 'Pesan error yang detail...');
```

### 2. **Blade Tidak Menampilkan Session Error sebagai Fallback**

File `kursi.blade.php` hanya memeriksa `session('alert-type')` tapi tidak menampilkan `session('error')` sebagai fallback. Ketika ada error session yang tidak sesuai format, error tidak ditampilkan sama sekali.

---

## Fix yang Sudah Diterapkan

### Fix #1: Update Controller (CustomerController.php)

Semua pesan error/success di method `prosesPemilihanKursi` telah diubah menjadi format alert yang konsisten:

- **Jumlah kursi tidak sesuai**: Sekarang menampilkan detail jumlah yang dipilih vs yang dibutuhkan
- **Duplikat kursi**: Dengan pesan yang jelas
- **Kursi sudah dipesan**: Menampilkan nomor kursi mana yang bermasalah
- **Kursi tidak tersedia**: Menampilkan sisa kapasitas
- **Success**: Redirect ke detail pemesanan dengan notifikasi sukses

### Fix #2: Add Error Fallback di Blade (kursi.blade.php)

Ditambahkan section baru setelah alert primary untuk menangani:

1. **Session Error** (`session('error')`)
2. **Session Success** (`session('success')`) 
3. **Validation Errors** (`$errors->any()`)

Dengan format yang sama seperti alert itn yang sudah ada.

### Fix #3: Add Debug Console Logging

Ditambahkan console logging di form submission handler untuk membantu debugging:

```javascript
console.log('=== FORM SUBMISSION DEBUG ===');
console.log('Selected Seats:', selectedSeats);
console.log('Required:', jumlahPenumpang);
console.log('Form Hidden Inputs:', ...);
```

---

## Cara Testing Fix Ini

### 1. **Test Form Submission**

1. Buka halaman kursi setelah melakukan pencarian shuttle
2. Pilih sejumlah kursi sesuai penumpang
3. Klik "Lanjutkan ke Detail Pesanan"
4. Jika valid → harus redirect ke halaman detail pesanan
5. Jika ada error → harus menampilkan pesan error di atas halaman

### 2. **Test Error Scenarios**

Buka browser DevTools (F12) dan buka Console tab untuk melihat debug logs.

#### Skenario A: Jumlah kursi tidak sesuai
- Sistem hanya mengizinkan jumlah kursi sama dengan jumlah penumpang
- Jika kurang atau lebih, tombol "Lanjutkan" akan disabled
- Jika somehow submit dengan jumlah salah → error message akan muncul

#### Skenario B: User memilih kursi yang sudah terpesan
- Sistem melakukan validasi real-time via AJAX saat `Lock Seat`
- Jika kursi ternyata sudah dipesan orang lain → error akan muncul
- Kursi akan di-unlock otomatis

#### Skenario C: Status pemesanan salah
- Jika user visit kursi page dengan status bukan `menunggu_kursi` → akan di-reject
- Error message akan muncul dengan status yang sekarang

### 3. **Baca Console Logs untuk Debug**

Klik F12 untuk buka DevTools, tab Console:

```
=== FORM SUBMISSION DEBUG ===
Selected Seats: [
  {id: "1", number: "1", price: 500000},
  {id: "2", number: "2", price: 500000}
]
Required: 2
Form Hidden Inputs: NodeList(2) [input, input]
✓ Validation PASSED - Form will submit
```

Ini menunjukkan bahwa:
- 2 kursi sudah dipilih (seat 1, 2)
- Sistem membutuhkan 2 kursi
- Form inputs sudah di-populate
- Validation berhasil

---

## File yang Diubah

1. **app/Http/Controllers/CustomerController.php**
   - Method `prosesPemilihanKursi()` - Updated error handling

2. **resources/views/customer/kursi.blade.php**
   - Section alert notification - Added fallback error handling
   - Form submission handler - Added console logging

---

## Jika Masalah Masih Terjadi

Jika setelah fix ini user masih mengalami masalah, lakukan:\1. **Cek browser console (F12)** untuk melihat error message yang sebenarnya
2. **Cek Laravel logs** di `storage/logs/laravel-*.log`
3. **Lakukan hard refresh** (Ctrl+Shift+R) untuk clear browser cache
4. **Clear session**: Di developer tools, "Application" tab → Cookies → hapus session cookie

---

## Detail Validasi yang Dilakukan Backend

Method `prosesPemilihanKursi` melakukan validasi berlapis:

1. ✓ CSRF Token validation (Laravel middleware)
2. ✓ pemesanan_id validation (must exist in database)
3. ✓ kursi field validation (must be array, min 1 element, each must be string and distinct)
4. ✓ Authentication check (user must own the pemesanan)
5. ✓ Status check (pemesanan must be in 'menunggu_kursi' status)
6. ✓ Seat count check (count == jumlah_penumpang)
7. ✓ Duplicate seat check (no duplicate in submission)
8. ✓ Seat availability check (seats not already booked by others)
9. ✓ Capacity check (total occupied won't exceed shuttle capacity)

Jika salah satu validasi gagal, akan return error message yang spesifik.

---

## Update Logs

- **2026-02-13**: Initial fix applied
  - Fixed controller error message format
  - Added fallback error handling in blade
  - Added form submission debug logging
