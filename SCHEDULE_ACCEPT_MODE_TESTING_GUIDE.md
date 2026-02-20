# Quick Reference: Schedule Accept Mode Implementation

## Langkah Implementasi Cepat

### 1. Jalankan Database Migrations

```bash
php artisan migrate
```

Atau migrasi file tertentu:
```bash
php artisan migrate --path=database/migrations/2026_02_18_000000_add_schedule_accept_mode_to_users.php
php artisan migrate --path=database/migrations/2026_02_18_000001_add_driver_schedule_fields_to_jadwals.php
```

### 2. Verifikasi Database

```sql
-- Check users table
DESC users;
-- Pastikan kolom 'schedule_accept_mode' ada dengan ENUM('AUTO_ACCEPT', 'MANUAL_CONFIRM')

-- Check jadwals table
DESC jadwals;
-- Pastikan ada kolom 'driver_id', 'is_global_schedule'
```

---

## Testing Workflow

### Test 1: Admin Membuat Jadwal dengan AUTO_ACCEPT Driver

1. Login sebagai Admin
2. Buka: `/admin/jadwal-create`
3. Isi form:
   - Armada: Pilih sembarang
   - Rute: Pilih sembarang
   - Tanggal: Pilih hari ini atau besok
   - Waktu: Isi waktu berangkat dan tiba
   - **Tugaskan ke Driver: Pilih driver dengan mode AUTO_ACCEPT**
4. Klik "Simpan"
5. ✅ Jadwal berhasil dibuat dan DriverJadwal otomatis dibuat
6. Di database: `jadwals.driver_id` terisi, `is_global_schedule = 0`, `status_admin = 'diambil'`

### Test 2: Admin Membuat Jadwal Global

1. Login sebagai Admin
2. Buka: `/admin/jadwal-create`
3. Isi form sama seperti Test 1, **TAPI jangan pilih driver**
4. Klik "Simpan"
5. ✅ Jadwal berhasil dibuat sebagai jadwal global
6. Di database: `jadwals.driver_id = NULL`, `is_global_schedule = 1`, `status_admin = NULL`

### Test 3: Driver AUTO_ACCEPT Melihat Jadwal yang Di-Assign

1. Buka database dan cek driver mana yang punya `schedule_accept_mode = 'AUTO_ACCEPT'`
2. Login sebagai driver tersebut
3. Buka: `/driver/jadwal-tersedia`
4. ✅ Bisa melihat jadwal yang di-assign ke driver ini
5. ✅ Melihat pesan: "Mode: Penerimaan Otomatis"

### Test 4: Driver MANUAL_CONFIRM Melihat Jadwal Global

1. Update driver ke mode MANUAL_CONFIRM:
   ```sql
   UPDATE users SET schedule_accept_mode = 'MANUAL_CONFIRM' WHERE id = <driver_id>;
   ```
2. Login sebagai driver tersebut
3. Buka: `/driver/jadwal-tersedia`
4. ✅ Bisa melihat jadwal global (jadwal yang tidak di-assign ke driver manapun)
5. ✅ Melihat pesan: "Mode: Konfirmasi Manual"

### Test 5: Driver MANUAL_CONFIRM Mengklaim Jadwal Global

1. Dari Test 4, driver masih di halaman jadwal tersedia
2. Klik "Ambil Jadwal Ini" pada salah satu jadwal global
3. Centang checkbox "Saya siap melayani jadwal ini"
4. Klik "Ambil Jadwal Ini"
5. ✅ Jadwal berhasil diklaim
6. Di database:
   - `jadwals.driver_id` sekarang terisi dengan driver yang mengklaim
   - `jadwals.is_global_schedule` berubah dari true ke false
   - `jadwals.status_admin = 'diambil'`
7. ✅ Driver lain tidak bisa mengklaim jadwal yang sama lagi

### Test 6: Driver Mengubah Mode Penerimaan

1. Login sebagai driver
2. Buka: `/driver/pengaturan`
3. ✅ Bisa melihat dua pilihan mode
4. Pilih mode yang berbeda dari sekarang
5. Klik "Simpan Pengaturan"
6. ✅ Melihat pesan sukses
7. Di database: `users.schedule_accept_mode` berubah
8. Kembali ke `/driver/jadwal-tersedia`
9. ✅ Daftar jadwal berubah sesuai mode baru

### Test 7: Multiple Drivers Claim Jadwal Global (Race Condition)

1. Buat jadwal global
2. Siapkan 2 driver dengan mode MANUAL_CONFIRM (buka 2 browser/tab berbeda)
3. Kedua driver logout/login lagi atau refresh halaman
4. Di tab/browser pertama: Driver A klik "Ambil Jadwal Ini"
5. Di tab/browser kedua: Driver B klik "Ambil Jadwal Ini" (sebelum refresh)
6. ✅ Salah satu akan berhasil (yang pertama submit)
7. ✅ Yang lain akan melihat error: "Jadwal ini sudah diambil driver lain"

---

## SQL Queries untuk Testing

```sql
-- 1. Lihat semua driver dengan mode AUTO_ACCEPT
SELECT id, name, email, schedule_accept_mode 
FROM users 
WHERE schedule_accept_mode = 'AUTO_ACCEPT' AND status = 'active';

-- 2. Lihat semua driver dengan mode MANUAL_CONFIRM
SELECT id, name, email, schedule_accept_mode 
FROM users 
WHERE schedule_accept_mode = 'MANUAL_CONFIRM';

-- 3. Lihat jadwal yang di-assign ke driver tertentu
SELECT id, shuttle_id, driver_id, is_global_schedule, status_admin, tanggal_keberangkatan
FROM jadwals 
WHERE driver_id = <driver_id>;

-- 4. Lihat jadwal global yang tersedia
SELECT id, shuttle_id, driver_id, is_global_schedule, status_admin, tanggal_keberangkatan
FROM jadwals 
WHERE is_global_schedule = 1 AND status_admin IS NULL;

-- 5. Lihat DriverJadwal yang dibuat dari jadwal yang di-assign
SELECT * FROM driver_jadwals WHERE id_jadwal = <jadwal_id>;

-- 6. Update schedule_accept_mode untuk driver tertentu
UPDATE users SET schedule_accept_mode = 'MANUAL_CONFIRM' WHERE id = <driver_id>;

-- 7. Reset semua driver ke AUTO_ACCEPT
UPDATE users SET schedule_accept_mode = 'AUTO_ACCEPT' WHERE schedule_accept_mode IS NOT NULL;
```

---

## Debugging Tips

### Jika jadwal tidak muncul untuk driver:

1. Cek value `schedule_accept_mode` driver:
   ```sql
   SELECT schedule_accept_mode FROM users WHERE id = <driver_id>;
   ```

2. Cek jika jadwal memenuhi kriteria jadwal global/assigned:
   ```sql
   SELECT is_global_schedule, driver_id, status_admin FROM jadwals WHERE id = <jadwal_id>;
   ```

3. Cek query di database:
   - Untuk AUTO_ACCEPT: Jadwal harus punya `driver_id = <driver_id>`
   - Untuk MANUAL_CONFIRM: Jadwal harus punya `is_global_schedule = 1` dan `status_admin IS NULL`

### Jika error saat mengklaim jadwal:

1. Cek apakah driver sudah mengklaim jadwal ini:
   ```sql
   SELECT * FROM driver_jadwals WHERE id_jadwal = <jadwal_id> AND id_driver = <driver_id>;
   ```

2. Cek apakah sudah mencapai limit 20 jadwal per bulan:
   ```sql
   SELECT COUNT(*) as total FROM driver_jadwals 
   WHERE id_driver = <driver_id> 
   AND YEAR(tanggal) = YEAR(NOW()) 
   AND MONTH(tanggal) = MONTH(NOW());
   ```

3. Lihat log untuk error detail:
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## Cleanup & Reset

```bash
# Rollback migrations (jika perlu reset)
php artisan migrate:rollback

# Fresh migration (delete and recreate)
php artisan migrate:fresh

# Seed data jika ada
php artisan db:seed
```

---

## Browser DevTools Tips

### Inspect element untuk melihat mode yang dipilih:

```javascript
// Di console browser, jalankan:
const modeAttr = document.querySelector('input[name="schedule_accept_mode"]:checked');
console.log('Mode saat ini:', modeAttr.value);
```

### Trigger form submit dari console:

```javascript
// Submit form pengaturan
document.querySelector('form').submit();
```

---

## Pesan Sukses/Error yang Diharapkan

### Success Messages:
- ✅ "Jadwal global berhasil dibuat! Dapat diambil oleh driver dengan mode MANUAL_CONFIRM."
- ✅ "Jadwal berhasil dibuat dan ditugaskan ke driver!"
- ✅ "Mode penerimaan jadwal berhasil diubah menjadi: Penerimaan Otomatis"
- ✅ "Mode penerimaan jadwal berhasil diubah menjadi: Konfirmasi Manual"
- ✅ "Jadwal berhasil diambil!"

### Error Messages:
- ❌ "Jadwal ini tidak di-assign untuk Anda."
- ❌ "Jadwal ini bukan jadwal global."
- ❌ "Jadwal ini sudah diambil driver lain."
- ❌ "Anda sudah mengambil jadwal ini."
- ❌ "Anda sudah mencapai batas 20 jadwal dalam bulan ini."
- ❌ "Tidak ada driver dengan mode AUTO_ACCEPT"

---

## Next Steps

1. ✅ Jalankan migrations
2. ✅ Test semua 7 test workflows di atas
3. ✅ Verifikasi database state
4. ✅ Test error handling dan race conditions
5. ✅ Deploy ke production
6. ✅ Monitor logs untuk issues
