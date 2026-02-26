# Testing Guide: Admin Booking Feature

## Prerequisites
- [ ] Admin account sudah login di `/admin/login`
- [ ] Admin memiliki role `admin_pusat` atau `admin_cabang`
- [ ] Admin memiliki permission `manage_perjalanan_transaksi`
- [ ] Ada jadwal yang tersedia untuk pemesanan

## Test Cases

### Test 1: Akses Halaman Admin Transaksi
**Steps:**
1. Login sebagai admin
2. Pergi ke menu Admin > Transaksi > Perjalanan
3. Cari tombol "Pesan Untuk Customer"

**Expected Result:**
- [ ] Halaman Admin Transaksi terbuka
- [ ] Ada tombol "Pesan Untuk Customer" di header kanan
- [ ] Tombol berbentuk link dengan warna biru (#00215E)
- [ ] Icon tombol adalah plus circle dengan text "Pesan Untuk Customer"

---

### Test 2: Klik Tombol Pesan Untuk Customer
**Steps:**
1. Dari halaman Admin Transaksi
2. Klik tombol "Pesan Untuk Customer"

**Expected Result:**
- [ ] Browser navigate ke `/customer/pesan`
- [ ] Halaman customer pesan terbuka
- [ ] Admin NOT logout dari admin account
- [ ] Session admin tersimpan

---

### Test 3: Verifikasi Admin Indicator di Halaman Pesan
**Steps:**
1. Setelah navigate ke halaman pesan
2. Lihat bagian atas form (setelah alerts)

**Expected Result:**
- [ ] Ada indicator berwarna orange
- [ ] Text menampilkan: "Admin Mode: [Nama Admin] sedang melakukan pemesanan untuk Customer"
- [ ] Ada icon user-tie (👔) di sebelah kiri
- [ ] Ada tombol "Kembali ke Admin" di sebelah kanan indicator

---

### Test 4: Lakukan Pemesanan sebagai Admin
**Steps:**
1. Di halaman pesan, lengkapi form pemesanan:
   - Pilih rute
   - Pilih tanggal
   - Pilih jadwal
   - Isi jumlah penumpang
   - Isi data penumpang
   - (Optional) Masukkan kode promo
2. Klik submit/lanjutkan pemesanan

**Expected Result:**
- [ ] Form dapat diisi normal seperti customer biasa
- [ ] Kalkulasi harga berfungsi dengan baik
- [ ] Pemesanan dapat diproses
- [ ] Kode booking berhasil dibuat
- [ ] Data pemesanan tersimpan di database dengan `created_by = admin_id`

---

### Test 5: Dropdown Profil Menunjukkan Admin Mode (Optional)
**Steps:**
1. Dari halaman pesan, klik menu "Beranda" atau home
2. Klik avatar/name di pojok kanan atas untuk membuka dropdown profil

**Expected Result:**
- [ ] Dropdown profil berisi teks "Admin Mode: [Nama Admin]" dengan ikon user-tie
- [ ] Dropdown menampilkan link "Kembali Admin" berwarna oranye
- [ ] Dropdown lain (Profil, Riwayat, Logout) tetap ada
- [ ] Beranda berfungsi normal tanpa indicator di konten halaman

---

### Test 6: Klik Tombol "Kembali ke Admin"
**Steps:**
1. Dari halaman pesan (atau beranda)
2. Klik tombol "Kembali ke Admin"

**Expected Result:**
- [ ] Browser navigate ke `/admin/transaksi/perjalanan`
- [ ] Admin kembali ke halaman admin transaksi
- [ ] Indicator admin mode TIDAK terlihat
- [ ] Tombol "Kembali ke Admin" HILANG
- [ ] Admin masih login sebagai admin
- [ ] Session admin booking sudah di-clear

---

### Test 7: Logout Admin
**Steps:**
1. Login sebagai admin
2. Klik tombol "Pesan Untuk Customer"
3. Di halaman pesan, logout dari admin account

**Expected Result:**
- [ ] Admin redirect ke halaman login admin
- [ ] Session admin booking di-clear automatically
- [ ] Admin harus login ulang untuk melakukan aktivitas lagi

---

### Test 8: Permissions Check
**Steps:**
1. Buat user dengan role yang BUKAN admin (misal: customer)
2. Coba akses `/admin/admin-booking` secara langsung

**Expected Result:**
- [ ] Access denied / forbidden error
- [ ] User tidak bisa mengakses endpoint ini
- [ ] Error message menampilkan permission issue

---

### Test 9: Session Persistence Across Pages
**Steps:**
1. Klik "Pesan Untuk Customer"
2. Di halaman pesan, navigate ke halaman lain (search, beranda, dll)
3. Kembali ke halaman pesan

**Expected Result:**
- [ ] Indicator admin mode masih terlihat di semua halaman customer
- [ ] Tombol "Kembali ke Admin" masih ada
- [ ] Session tidak ter-clear

---

### Test 10: Browser Back Button
**Steps:**
1. Klik "Pesan Untuk Customer"
2. Di halaman pesan, tekan tombol back browser

**Expected Result:**
- [ ] Kembali ke halaman admin transaksi
- [ ] Session admin mungkin masih ada atau ter-clear (sesuai design)
- [ ] Admin masih login

---

## Database Verification

### Check Pemesanan dari Admin
```sql
SELECT * FROM pemesanan 
WHERE created_by = [admin_id] 
ORDER BY created_at DESC 
LIMIT 5;
```

**Expected:**
- [ ] Kolom `created_by` berisi ID admin yang membuat pemesanan
- [ ] Kolom `customer_id` bisa NULL atau berisi ID customer
- [ ] Data pemesanan lengkap dan valid

---

## Session Verification

### Check Session Values (Optional - untuk development)
```php
// Tambahkan temporary debug di routes
Route::get('/debug-session', function() {
    return [
        'admin_booking_session' => session('admin_booking_session'),
        'admin_id' => session('admin_id'),
        'admin_name' => session('admin_name'),
        'admin_email' => session('admin_email'),
        'admin_role' => session('admin_role'),
    ];
});
```

---

## Performance Checks
- [ ] Halaman pesan load dengan cepat (< 2 detik)
- [ ] Indicator dan tombol render dengan benar
- [ ] Tidak ada console errors
- [ ] Tidak ada logged errors di server

---

## Browser Compatibility
Test di browsers berikut:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Chrome (iOS/Android)

---

## Mobile Responsiveness
**Steps:**
1. Reduce browser width ke 375px (mobile width)
2. Lakukan Test 1-6 lagi

**Expected Result:**
- [ ] Indicator tetap terlihat dan readable
- [ ] Tombol "Kembali ke Admin" tetap terlihat
- [ ] Layout responsive dan tidak broken
- [ ] Touch targets > 44x44px (mobile friendly)

---

## Error Scenarios

### Scenario A: Session Timeout
**Steps:**
1. Klik "Pesan Untuk Customer"
2. Wait 30 menit (atau simulate session timeout)
3. Coba akses halaman pesan

**Expected Result:**
- [ ] User redirect ke login page
- [ ] Session admin di-clear

### Scenario B: Multiple Admin Sessions
**Steps:**
1. Admin A klik "Pesan Untuk Customer"
2. Di tab baru, Admin B juga klik "Pesan Untuk Customer"
3. Check kedua session

**Expected Result:**
- [ ] Masing-masing admin punya session sendiri
- [ ] Tidak ada conflict antar session
- [ ] Indicator menampilkan nama admin yang benar untuk setiap tab

### Scenario C: Clear Browser Cache
**Steps:**
1. Klik "Pesan Untuk Customer"
2. Clear browser cache/cookies
3. Reload halaman

**Expected Result:**
- [ ] Session hilang
- [ ] User di-redirect ke login atau error page
- [ ] Indicator tampil ulang setelah session di-restore

---

## Regression Testing
Pastikan fitur lama masih berfungsi:
- [ ] Customer masih bisa pesan normal tanpa admin session
- [ ] Admin transaksi list masih tampil dengan benar
- [ ] Logout admin masih berfungsi
- [ ] Login admin masih berfungsi
- [ ] Permissions masih berfungsi

---

## Sign-Off

**Tested by:** ____________
**Date:** ____________
**Status:** [ ] PASS [ ] FAIL

**Notes:**
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

**Approved by:** ____________
**Date:** ____________
