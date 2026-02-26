# Fitur Admin Melakukan Pemesanan Untuk Customer

## Overview
Fitur ini memungkinkan admin (Admin Pusat atau Admin Cabang) untuk melakukan pemesanan tiket shuttle untuk customer. Ketika admin melakukan pemesanan, admin akan otomatis masuk ke modul customer dengan session khusus, sehingga bisa melakukan pemesanan seperti customer biasa, namun dengan indicator yang menunjukkan bahwa ini adalah admin yang sedang melakukan pemesanan.

## Fitur-Fitur

### 1. **Button "Pesan Untuk Customer" di Halaman Admin Transaksi**
- Tombol ini berada di header halaman `/admin/transaksi/perjalanan` (Admin > Transaksi Perjalanan)
- Ketika diklik, admin akan diarahkan ke route `/admin/admin-booking`
- Tombol diubah dari modal form menjadi link langsung untuk pengalaman yang lebih natural

**File:** `resources/views/admin/transaksi/perjalanan.blade.php` (Line 367-371)

### 2. **Admin Booking Session Management**
- Ketika admin mengakses `/admin/admin-booking`, session khusus akan dibuat yang menyimpan:
  - `admin_booking_session` = true (flag bahwa ini adalah admin booking)
  - `admin_id` = ID admin yang sedang melakukan pemesanan
  - `admin_name` = Nama admin
  - `admin_email` = Email admin
  - `admin_role` = Role admin (admin_pusat/admin_cabang)

**File:** `app/Http/Controllers/Admin/AdminPemesananController.php` 
- Method: `adminBooking()` (Line 355-384)

### 3. **Redirect ke Customer Pesan dengan Session Admin**
- Setelah session dibuat, admin akan diarahkan ke `/customer/pesan` (halaman pemesanan customer)
- Di halaman ini, admin bisa melakukan pemesanan seperti customer biasa
- Admin bisa memilih jadwal, passenger info, dan menyelesaikan pemesanan

### 4. **Admin Indicator & Back Button**
#### Di Halaman Pesan Customer (`resources/views/customer/pesan.blade.php`)
- Admin akan melihat indicator berwarna orange yang menunjukkan bahwa ini adalah admin mode
- Indicator menampilkan: "Admin Mode: [Nama Admin] sedang melakukan pemesanan untuk Customer"
- Ada tombol "Kembali ke Admin" yang dapat diklik untuk kembali ke halaman admin transaksi
- CSS untuk styling: Line 1167-1211

#### Di Halaman Beranda Customer (`resources/views/customer/beranda.blade.php`)
- Jika admin mengakses halaman beranda customer, indikator dan tombol digeser ke menu navigasi
  untuk menjaga tata letak yang konsisten.
- Dalam *profile dropdown* (klik avatar/user name di kanan atas):
  - Elemen teks **Admin Mode: [Nama Admin]** dengan ikon user‑tie.
  - Tautan **Kembali Admin** dengan ikon panah kiri.
- Styling ditambahkan di header melalui kelas `.admin-menu-indicator` dan
  `.admin-dropdown-link` (warna oranye, font tebal).  CSS khusus di beranda sudah
  dihapus.

### 5. **Back to Admin Functionality**
- Ketika admin mengklik "Kembali ke Admin", route `/admin/back-to-admin` akan diakses
- Method `backToAdmin()` akan clear session admin booking
- Admin akan diarahkan kembali ke halaman `/admin/transaksi/perjalanan`

**File:** `app/Http/Controllers/Admin/AdminPemesananController.php`
- Method: `backToAdmin()` (Line 386-394)

### 6. **Session Cleanup pada Logout**
- Ketika admin logout, session admin booking juga akan dihapus secara otomatis
- Ini memastikan bahwa tidak ada session yang tertinggal

**File:** `app/Http/Controllers/AdminController.php`
- Method: `logout()` (Line 1726-1742)

## Routes yang Ditambahkan

```php
// Admin booking - redirect to customer pesan with admin session
Route::get('/admin-booking', [AdminPemesananController::class, 'adminBooking'])
    ->middleware('permission:manage_perjalanan_transaksi')
    ->name('admin.booking');

// Back to admin from customer pesan
Route::get('/back-to-admin', [AdminPemesananController::class, 'backToAdmin'])
    ->middleware('permission:manage_perjalanan_transaksi')
    ->name('admin.back');
```

**File:** `routes/web.php` (Line 557-563)

## User Flow

```
1. Admin Login
   ↓
2. Admin ke Halaman Admin > Transaksi Perjalanan
   ↓
3. Klik Tombol "Pesan Untuk Customer"
   ↓
4. Session Admin dibuat (/admin/admin-booking)
   ↓
5. Admin diarahkan ke /customer/pesan
   ↓
6. Admin melihat Indicator Admin Mode + Tombol Back
   ↓
7. Admin melakukan pemesanan seperti customer biasa
   ↓
8. Opsi A: Selesai Pemesanan → Kembali ke login
   Opsi B: Klik "Kembali ke Admin" → Kembali ke halaman admin
   ↓
9. Session admin booking di-clear
```

## Styling & UI

### Indicator Admin Mode
- **Background:** Gradient orange (#FF581E to #ff7b4d)
- **Text Color:** White
- **Padding:** 12px 20px
- **Border Radius:** 8px
- **Icon:** User Tie (👔)
- **Shadow:** Box shadow dengan opacity 0.3

### Back to Admin Button
- **Background:** Gradient dark blue (#00215E to #1a3d7c)
- **Text Color:** White
- **Padding:** 10px 20px
- **Border Radius:** 8px
- **Icon:** Arrow Left (←)
- **Hover Effect:** Darker gradient + translate up + shadow
- **Font Weight:** 600

## Permissions
- Route `/admin/admin-booking` dan `/admin/back-to-admin` memerlukan permission `manage_perjalanan_transaksi`
- Hanya Admin Pusat dan Admin Cabang yang memiliki permission ini

## Database Impact
- Tidak ada perubahan database struktur
- Data pemesanan yang dibuat oleh admin akan memiliki `created_by = admin_id`
- Semua proses pemesanan sama dengan customer biasa

## Testing Checklist

- [ ] Admin dapat mengakses tombol "Pesan Untuk Customer" di halaman transaksi
- [ ] Klik tombol membawa admin ke `/customer/pesan` dengan session admin
- [ ] Admin dapat melihat indicator mode di halaman pesan dan beranda
- [ ] Admin dapat melakukan pemesanan untuk customer
- [ ] Tombol "Kembali ke Admin" terbaca dan dapat diklik
- [ ] Klik "Kembali ke Admin" menghapus session dan membawa ke halaman admin
- [ ] Logout admin juga menghapus session booking
- [ ] Pemesanan dari admin terekam dengan `created_by` = ID admin

## Security Notes
- Session admin booking hanya tersimpan di session, tidak di database
- Session akan di-clear ketika:
  - Admin klik "Kembali ke Admin"
  - Admin logout
  - Session timeout
- Route `/admin/admin-booking` dan `/admin/back-to-admin` dilindungi dengan middleware `permission:manage_perjalanan_transaksi`

## Files Modified
1. `app/Http/Controllers/Admin/AdminPemesananController.php` - Added 2 methods
2. `app/Http/Controllers/AdminController.php` - Modified logout method
3. `resources/views/admin/transaksi/perjalanan.blade.php` - Changed button to link
4. `resources/views/customer/pesan.blade.php` - Added indicator & button + CSS
5. `resources/views/customer/beranda.blade.php` - Added indicator & button + CSS
6. `routes/web.php` - Added 2 new routes

## Future Enhancements
- [ ] Add option to assign pemesanan ke specific customer account
- [ ] Add audit log untuk melacak pemesanan dari admin
- [ ] Add note field untuk dokumentasi mengapa admin membuat pemesanan ini
- [ ] Add analytics untuk melihat berapa banyak pemesanan dari admin
