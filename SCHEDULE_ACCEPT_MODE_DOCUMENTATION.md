# Dokumentasi Fitur: Schedule Accept Mode untuk Driver

## Ringkasan Fitur

Fitur **Schedule Accept Mode** memungkinkan sistem untuk mengelola penerimaan jadwal oleh driver dengan dua cara berbeda:

1. **AUTO_ACCEPT**: Admin langsung menugaskan jadwal ke driver, dan jadwal langsung aktif tanpa perlu konfirmasi
2. **MANUAL_CONFIRM**: Admin membuat jadwal global, dan driver dapat melihat dan mengambil jadwal tersebut melalui halaman "Ambil Jadwal"

---

## Implementasi Teknis

### 1. Database Migrations

#### Migration 1: Add schedule_accept_mode to users table
**File**: `database/migrations/2026_02_18_000000_add_schedule_accept_mode_to_users.php`

Menambahkan kolom `schedule_accept_mode` ke tabel `users`:
```sql
ALTER TABLE users ADD COLUMN schedule_accept_mode ENUM('AUTO_ACCEPT', 'MANUAL_CONFIRM') DEFAULT 'AUTO_ACCEPT'
```

**Default Value**: `AUTO_ACCEPT` (untuk backward compatibility)

#### Migration 2: Add driver schedule fields to jadwals table
**File**: `database/migrations/2026_02_18_000001_add_driver_schedule_fields_to_jadwals.php`

Menambahkan dua kolom ke tabel `jadwals`:
- `driver_id` (nullable): Foreign key ke `users` table untuk AUTO_ACCEPT assignment
- `is_global_schedule` (boolean, default false): Flag untuk menandai jadwal global

---

### 2. Model Updates

#### User Model (`app/Models/User.php`)
- Tambahkan `schedule_accept_mode` ke `$fillable` array
- Tambahkan default value di `$attributes` array

#### Jadwal Model (`app/Models/Jadwal.php`)
- Tambahkan `driver_id` dan `is_global_schedule` ke `$fillable`
- Tambahkan relasi `driver()`: belongsTo User
- Tambahkan method dan scope baru:
  - `scopeJadwalGlobal()`: Filter jadwal global untuk MANUAL_CONFIRM
  - `scopeJadwalAssigned()`: Filter jadwal yang di-assign untuk AUTO_ACCEPT
  - `isGlobalSchedule()`: Check apakah jadwal adalah global
  - `isAssignedToDriver()`: Check apakah jadwal sudah di-assign
  - `assignToDriver()`: Assign jadwal ke driver
  - `makeGlobal()`: Convert jadwal menjadi global
  - `storeDriverJadwal()`: Create DriverJadwal record

---

### 3. Controller Updates

#### Admin/JadwalController (`app/Http/Controllers/Admin/JadwalController.php`)

**Perubahan pada `create()` method**:
```php
$driversAutoAccept = User::where('schedule_accept_mode', 'AUTO_ACCEPT')
    ->where('status', 'active')
    ->orderBy('name')
    ->get();

return view('admin.jadwal-create', compact('shuttles', 'rutes', 'driversAutoAccept'));
```

**Perubahan pada `store()` method**:
- Tambahkan validasi `driver_id` (optional)
- Tentukan apakah jadwal adalah global atau assign ke driver
- Jika ada `driver_id`, set:
  - `is_global_schedule = false`
  - `status_admin = 'diambil'`
  - Buat `DriverJadwal` record langsung
- Jika tidak ada `driver_id`, set:
  - `is_global_schedule = true`
  - `status_admin = null`

#### DriverJadwalController (`app/Http/Controllers/DriverJadwalController.php`)

**Perubahan pada `daftarJadwalTersedia()` method**:
```php
if ($driver->schedule_accept_mode === 'MANUAL_CONFIRM') {
    // Tampilkan jadwal global saja
    $jadwalTersedia = Jadwal::jadwalGlobal()->paginate(10);
} else {
    // Tampilkan jadwal yang di-assign ke driver ini saja
    $jadwalTersedia = Jadwal::jadwalAssigned()
        ->where('driver_id', $driver->id)
        ->where('status_admin', '!=', 'diambil')
        ->paginate(10);
}
```

**Perubahan pada `ambilJadwal()` method**:
- Validasi berbeda berdasarkan `schedule_accept_mode`
- Untuk AUTO_ACCEPT: Cek apakah jadwal di-assign ke driver ini
- Untuk MANUAL_CONFIRM: Cek apakah jadwal adalah jadwal global
- Saat driver mengklaim jadwal global, assign jadwal ke driver:
  ```php
  if ($jadwal->is_global_schedule && $driver->schedule_accept_mode === 'MANUAL_CONFIRM') {
      $jadwal->driver_id = $driver->id;
      $jadwal->is_global_schedule = false;
      $jadwal->save();
  }
  ```

#### DriverController (`app/Http/Controllers/DriverController.php`)

**Update `pengaturan()` method**:
```php
public function pengaturan()
{
    $driver = Auth::guard('driver')->user();
    return view('driver.pengaturan', compact('driver'));
}
```

**Tambahkan method baru `updateScheduleAcceptMode()`**:
```php
public function updateScheduleAcceptMode(Request $request)
{
    $validated = $request->validate([
        'schedule_accept_mode' => 'required|in:AUTO_ACCEPT,MANUAL_CONFIRM'
    ]);

    $driver = Auth::guard('driver')->user();
    $driver->update($validated);

    return back()->with('success', 'Mode penerimaan jadwal berhasil diubah');
}
```

---

### 4. Routes

**File**: `routes/web.php`

Tambahkan route baru di dalam middleware `auth:driver`:
```php
Route::post('/pengaturan/update-schedule-accept-mode', [DriverController::class, 'updateScheduleAcceptMode'])
    ->name('driver.pengaturan.update-schedule-accept-mode');
```

---

### 5. Views

#### Admin - Jadwal Create (`resources/views/admin/jadwal-create.blade.php`)

Tambahkan form group untuk pemilihan driver setelah field Rute:
```blade
<!-- Tugaskan ke Driver (Optional) -->
<div class="form-group">
    <label class="form-label">Tugaskan ke Driver <span class="badge bg-info ms-2">Opsional</span></label>
    <div class="select-wrapper">
        <select name="driver_id" class="form-control">
            <option value="">-- Tidak Ditugaskan (Jadwal Global) --</option>
            @forelse($driversAutoAccept as $driver)
                <option value="{{ $driver->id }}">
                    {{ $driver->name }} ({{ $driver->email }})
                </option>
            @empty
                <option value="" disabled>Tidak ada driver dengan mode AUTO_ACCEPT</option>
            @endforelse
        </select>
    </div>
    <small>
        Jika ditugaskan: Jadwal langsung aktif untuk driver tanpa konfirmasi.
        Jika tidak: Jadwal menjadi jadwal global untuk driver MANUAL_CONFIRM.
    </small>
</div>
```

#### Driver - Pengaturan (`resources/views/driver/pengaturan.blade.php`)

Buat halaman settings baru dengan:
- Dua kartu radio untuk memilih mode (AUTO_ACCEPT vs MANUAL_CONFIRM)
- Penjelasan detail untuk setiap mode
- Form submit untuk menyimpan pengaturan

#### Driver - Jadwal Tersedia (`resources/views/driver/jadwal-tersedia.blade.php`)

Tambahkan alert di atas daftar jadwal yang menjelaskan:
- Untuk AUTO_ACCEPT: "Mode Penerimaan Otomatis - Jadwal yang ditugaskan admin khusus untuk Anda"
- Untuk MANUAL_CONFIRM: "Mode Konfirmasi Manual - Jadwal global yang dapat Anda ambil"

---

## Alur Penggunaan

### Skenario 1: Admin Menugaskan Jadwal ke Driver AUTO_ACCEPT

1. Admin membuka halaman "Tambah Jadwal Baru"
2. Admin memilih:
   - Armada
   - Rute
   - Tanggal & Waktu
   - **Driver** (dari dropdown driversAutoAccept)
3. Admin klik "Simpan"
4. Sistem membuat:
   - Record `Jadwal` dengan `driver_id = selected_driver` dan `is_global_schedule = false`
   - Record `DriverJadwal` secara otomatis
5. Driver AUTO_ACCEPT melihat jadwal di halaman "Ambil Jadwal" sebagai jadwal yang sudah ditugaskan

### Skenario 2: Admin Membuat Jadwal Global untuk Driver MANUAL_CONFIRM

1. Admin membuka halaman "Tambah Jadwal Baru"
2. Admin memilih:
   - Armada
   - Rute
   - Tanggal & Waktu
   - **Tidak memilih Driver** (biarkan kosong)
3. Admin klik "Simpan"
4. Sistem membuat:
   - Record `Jadwal` dengan `driver_id = null` dan `is_global_schedule = true`
   - `status_admin = null` (belum diambil)
5. Semua driver MANUAL_CONFIRM melihat jadwal di halaman "Ambil Jadwal" sebagai jadwal global
6. Driver yang pertama kali klik "Ambil Jadwal Ini" akan:
   - Menjadi pemilik jadwal (assign ke driver tersebut)
   - Jadwal berubah dari `is_global_schedule = true` menjadi `false`
   - Driver lain tidak bisa mengambil jadwal tersebut lagi

### Skenario 3: Driver Mengubah Mode Penerimaan

1. Driver masuk ke halaman "Pengaturan"
2. Driver melihat dua pilihan mode:
   - **Penerimaan Otomatis** (AUTO_ACCEPT)
   - **Konfirmasi Manual** (MANUAL_CONFIRM)
3. Driver memilih mode yang diinginkan
4. Driver klik "Simpan Pengaturan"
5. Settings disimpan ke kolom `schedule_accept_mode` di tabel `users`
6. Halaman "Ambil Jadwal" akan menampilkan jadwal yang sesuai dengan mode yang dipilih

---

## Backward Compatibility

- Default value dari `schedule_accept_mode` adalah `AUTO_ACCEPT`
- Existing drivers akan otomatis memiliki mode `AUTO_ACCEPT`
- Jadwal-jadwal yang sudah ada tidak terpengaruh karena kolom baru `driver_id` dan `is_global_schedule` dapat null/false

---

## Validasi dan Error Handling

### Validasi di JadwalController:
- Driver yang dipilih harus memiliki `schedule_accept_mode = 'AUTO_ACCEPT'`
- Driver yang dipilih harus memiliki `status = 'active'`

### Validasi di DriverJadwalController:
- AUTO_ACCEPT driver hanya bisa mengklaim jadwal yang di-assign ke mereka
- MANUAL_CONFIRM driver hanya bisa mengklaim jadwal global
- Jika jadwal sudah diambil driver lain, tidak bisa diambil lagi
- Maksimal 20 jadwal per driver per bulan tetap berlaku
- Race condition di-handle dengan row locking (`lockForUpdate()`)

---

## Testing Checklist

- [ ] Migration berjalan tanpa error
- [ ] Default value `AUTO_ACCEPT` bekerja untuk existing users
- [ ] Admin dapat membuat jadwal dengan assign driver AUTO_ACCEPT
- [ ] Admin dapat membuat jadwal global tanpa assign driver
- [ ] DriverJadwal dibuat otomatis saat jadwal di-assign admin
- [ ] Driver AUTO_ACCEPT melihat hanya jadwal yang di-assign
- [ ] Driver MANUAL_CONFIRM melihat hanya jadwal global
- [ ] Driver MANUAL_CONFIRM dapat mengklaim jadwal global
- [ ] Jadwal global berubah menjadi assigned setelah diklaim
- [ ] Driver lain tidak bisa mengklaim jadwal yang sudah diklaim
- [ ] Driver dapat mengubah mode di halaman pengaturan
- [ ] Perubahan mode langsung tercermin di halaman "Ambil Jadwal"
- [ ] Pesan error yang sesuai ditampilkan
- [ ] Race condition tidak terjadi saat multiple drivers claim jadwal global

---

## File yang Dimodifikasi/Dibuat

### Baru Dibuat:
1. `database/migrations/2026_02_18_000000_add_schedule_accept_mode_to_users.php`
2. `database/migrations/2026_02_18_000001_add_driver_schedule_fields_to_jadwals.php`
3. `resources/views/driver/pengaturan.blade.php` (update)

### Dimodifikasi:
1. `app/Models/User.php`
2. `app/Models/Jadwal.php`
3. `app/Http/Controllers/Admin/JadwalController.php`
4. `app/Http/Controllers/DriverJadwalController.php`
5. `app/Http/Controllers/DriverController.php`
6. `resources/views/admin/jadwal-create.blade.php`
7. `resources/views/driver/jadwal-tersedia.blade.php`
8. `routes/web.php`

---

## Kesimpulan

Fitur ini memberikan fleksibilitas kepada admin dalam mengelola penerimaan jadwal oleh driver dengan dua mode berbeda, sekaligus memberikan kontrol kepada driver untuk memilih mode mana yang mereka inginkan. Implementasi menggunakan transaction dan row locking untuk mencegah race condition dan memastikan konsistensi data.
