# Driver Branch Implementation - Dokumentasi

## 📋 Ringkasan Masalah
**Error:** "The selected branch id is invalid" saat admin pusat menambahkan driver.

**Root Cause:** 
- Driver tidak memiliki branch_id yang required
- Pada saat membuat jadwal, tidak ada filtering driver berdasarkan branch asal

## ✅ Solusi yang Diimplementasikan

### 1. **Database & Model** ✓
- `users` table sudah memiliki `branch_id` column dengan foreign key ke `branches` table
- User model sudah memiliki relationship `branch()` → `belongsTo(Branch::class)`

### 2. **Form Tambah User** (`resources/views/admin/user.blade.php`)

#### Perubahan:
- **Branch field** sekarang ditampilkan untuk:
  - `admin_cabang` (yang sudah ada sebelumnya)
  - `driver` (BARU)

- **JavaScript update:**
  ```javascript
  // Sebelumnya
  if (roleSelect.value === 'admin_cabang') { ... }
  
  // Sekarang
  if (['admin_cabang', 'driver'].includes(roleSelect.value)) { ... }
  ```

- **Help text update:**
  ```
  "Field ini wajib diisi untuk role Admin Cabang dan Driver"
  ```

### 3. **Backend Validasi** (`app/Http/Controllers/AdminController.php`)

#### Perubahan di `storeUser()`:
```php
// Sebelumnya
'branch_id' => 'required_if:role,admin_cabang|exists:branches,id',

// Sekarang
'branch_id' => 'required_if:role,admin_cabang,driver|exists:branches,id',

// Dan saat menyimpan:
// Sebelumnya
if ($request->role === 'admin_cabang') {
    $data['branch_id'] = $request->branch_id;
}

// Sekarang
if (in_array($request->role, ['admin_cabang', 'driver'])) {
    $data['branch_id'] = $request->branch_id;
}
```

### 4. **Jadwal Controller** (`app/Http/Controllers/Admin/JadwalController.php`)

#### Tambah Method: `getDriversByRute()`
```php
/**
 * Fetch drivers by route/branch (AJAX endpoint)
 * Filter drivers yang berada di cabang asal rute yang dipilih
 */
public function getDriversByRute(Request $request)
{
    $ruteId = $request->get('rute_id');
    $rute = Rute::with('cabangAsal')->findOrFail($ruteId);
    
    // Get drivers dari cabang asal dengan mode AUTO_ACCEPT
    $drivers = User::where('branch_id', $rute->cabang_asal_id)
        ->where('schedule_accept_mode', 'AUTO_ACCEPT')
        ->where('status', 'active')
        ->orderBy('name')
        ->get();
    
    return response()->json(['drivers' => $drivers, ...]);
}
```

### 5. **Routes** (`routes/web.php`)

#### Tambah Route:
```php
Route::get('/jadwal/drivers-by-rute', [JadwalController::class, 'getDriversByRute'])
    ->name('jadwal.driversByRute');
```

**Catatan:** Route ditempatkan SEBELUM route `/{id}/edit` untuk menghindari konflik route matching.

### 6. **Jadwal Create Form** (`resources/views/admin/jadwal-create.blade.php`)

#### Tambah JavaScript:
```javascript
// Load drivers dynamically when rute changes
const driverSelect = document.querySelector('select[name="driver_id"]');

async function loadDriversByRute() {
    const ruteId = ruteSelect.value;
    
    if (!ruteId) {
        driverSelect.innerHTML = '<option value="">-- Tidak Ditugaskan --</option>';
        return;
    }
    
    const response = await fetch('{{ route("admin.jadwal.driversByRute") }}?rute_id=' + ruteId);
    const data = await response.json();
    
    // Populate driver options dengan driver dari branch yang sama
    // Tampilkan nama cabang untuk user reference
}

ruteSelect.addEventListener('change', loadDriversByRute);
loadDriversByRute(); // Initial load
```

## 📊 Alur Kerja Setelah Implementasi

### 1. **Menambah Driver**
```
Admin Pusat
    ↓
Form Tambah User → Pilih Role = "Driver"
    ↓
Branch Field Muncul (REQUIRED)
    ↓
Pilih Cabang (misal: Jakarta)
    ↓
Driver disimpan dengan branch_id = Jakarta
```

### 2. **Membuat Jadwal**
```
Admin Pusat
    ↓
Form Jadwal → Pilih Rute (misal: Jakarta → Bandung)
    ↓
Sistem ambil cabang_asal_id dari rute (Jakarta)
    ↓
AJAX Request ke /jadwal/drivers-by-rute
    ↓
Backend filter drivers dengan:
  - branch_id = Jakarta
  - schedule_accept_mode = AUTO_ACCEPT
  - status = active
    ↓
DropdownDriver otomatis populated dengan driver Jakarta saja
```

## 🧪 Testing Steps

### Test 1: Add Driver
```
1. Go to: /admin/user/create
2. Isi: Name, Email, Password
3. Role = "Driver"
4. Branch field harus muncul dan REQUIRED
5. Pilih cabang (misal: Jakarta)
6. Submit → Driver berhasil dibuat dengan branch_id = Jakarta
```

### Test 2: Create Schedule
```
1. Go to: /admin/jadwal/create
2. Pilih Armada
3. Pilih Rute dengan kota asal "Jakarta"
4. Check: Driver dropdown otomatis menampilkan drivers dari cabang Jakarta
5. Tidak ada drivers dari cabang lain yang muncul
```

### Test 3: Branch Validation
```
1. Try to submit user form tanpa memilih branch untuk driver
2. Error: "branch_id is required"
3. Submit dengan invalid branch_id
4. Error: "The selected branch_id is invalid"
```

## 🔄 Database State

Pastikan di database:
```sql
-- Check users table
SELECT id, name, role, branch_id FROM users WHERE role = 'driver';

-- Check branches
SELECT id, nama_cabang, kota FROM branches;

-- Check rutes
SELECT id, nama_rute, cabang_asal_id FROM rutes;
```

## ⚠️ Known Dependencies

- Rute model harus memiliki `cabang_asal_id` (untuk filter drivers)
- User model sudah memiliki `schedule_accept_mode` field
- User model sudah memiliki `branch()` relationship

## 📝 Files Modified

1. `app/Http/Controllers/AdminController.php` - Validation & storage update
2. `app/Http/Controllers/Admin/JadwalController.php` - New method + route
3. `resources/views/admin/user.blade.php` - JavaScript & form update
4. `resources/views/admin/jadwal-create.blade.php` - Dynamic driver loading
5. `routes/web.php` - New route definition

---
**Last Updated:** 2026-02-18
**Status:** ✅ Ready for Testing
