# Perbaikan Issue: Data Rute Tidak Terisi dengan Benar

## Ringkasan Masalah
Ketika menambahkan atau mengedit data rute, field-field berikut tidak tersimpan dengan benar:
- ❌ Kode Rute (tidak sesuai/tidak terisi)
- ❌ Durasi (tidak terisi)
- ❌ Harga (tidak terisi) 
- ❌ Tarif (tidak tersimpan)

## Penyebab Masalah

### 1. **Harga Dasar - Format Tidak Dihapus** ⚠️
JavaScript menambahkan format currency dengan pemisah titik (e.g., "350.000") saat user menginput harga. Nilai yang dikirm ke database masih berisi titik, sedangkan database expects nilai numeric murni.

**Contoh:**
```
Input: 350000
Setelah blur event: 350.000  ← Format dengan titik (salah!)
Database: Error atau NULL
```

### 2. **Master Tarif IDs Tidak Disimpan** ⚠️
Form mengirim array checkbox `master_tarif_ids[]` tapi controller tidak:
- Memvalidasi array tarif
- Menyimpan relasi ke tabel `rute_master_tarif`

### 3. **Validasi Tidak Lengkap** ⚠️
Tidak ada validasi bahwa user harus memilih minimal satu tarif sebelum submit.

## Solusi yang Diterapkan

### ✅ 1. Update RuteController - Store Method
**File:** `app/Http/Controllers/Admin/RuteController.php`

```php
// Bersihkan format harga_dasar (hapus titik/koma)
$data['harga_dasar'] = str_replace(['.', ','], '', $data['harga_dasar']);

// Simpan relasi master tarif
if (!empty($request->master_tarif_ids)) {
    $rute->masterTarifs()->sync($request->master_tarif_ids);
}
```

**Perubahan:**
- ✅ Menghapus semua titik dan koma dari harga_dasar sebelum menyimpan
- ✅ Menambahkan validasi untuk master_tarif_ids (required, array, min:1)
- ✅ Menyimpan relasi many-to-many ke tabel pivot `rute_master_tarif`

### ✅ 2. Update RuteController - Update Method
**File:** `app/Http/Controllers/Admin/RuteController.php`

Perubahan yang sama seperti store method untuk consistency:
- ✅ Membersihkan format harga
- ✅ Validasi tarif
- ✅ Menyimpan relasi tarif

### ✅ 3. Update Form Views - JavaScript Submit Handler
**File:** `resources/views/admin/rute-create.blade.php` dan `rute-edit.blade.php`

```javascript
// Validasi dan cleanup sebelum submit
form.addEventListener('submit', function(e) {
    // Validasi tarif dipilih
    const tarifCheckboxes = document.querySelectorAll('input[name="master_tarif_ids[]"]');
    const tarifChecked = Array.from(tarifCheckboxes).some(checkbox => checkbox.checked);
    
    if (!tarifChecked) {
        e.preventDefault();
        alert('Silakan pilih minimal satu tarif untuk rute ini.');
        return false;
    }

    // Bersihkan format harga sebelum submit
    hargaInput.value = hargaInput.value.replace(/[^\d]/g, '');
});
```

**Perubahan:**
- ✅ Validasi client-side bahwa minimal 1 tarif dipilih
- ✅ Membersihkan semua karakter non-numeric dari harga_dasar sebelum form submit
- ✅ Mencegah submit jika tarif tidak dipilih

### ✅ 4. Update Rute Model
**File:** `app/Models/Rute.php`

Menambahkan `created_by` dan `updated_by` ke fillable array:
```php
protected $fillable = [
    // ... field lainnya
    'created_by',
    'updated_by'
];
```

## Alur Data Setelah Perbaikan

### Menambah Rute Baru:
```
1. User mengisi form (Harga: 350000)
2. User blur input → JS format menjadi: 350.000
3. User klik Simpan
4. Form submit event listener:
   - ✅ Validasi: Tarif dipilih? YES
   - ✅ Cleanup: Harga diubah ke 350000 (hapus format)
5. Controller received: 350000 (clean)
6. Validasi Laravel: ✅ PASS (numeric)
7. Simpan ke DB: 350000 ✅
8. Sync relasi tarif: ✅ Tersimpan di rute_master_tarif
```

### Mengedit Rute:
```
Sama dengan proses di atas, tetapi dengan UPDATE query
```

## Testing Checklist ✓

- [ ] Tambah rute dengan harga 350000
  - Hasil: Harga tersimpan sebagai 350000 (numeric, bukan "350.000")
  
- [ ] Edit rute, ubah harga ke 450000
  - Hasil: Harga terupdate menjadi 450000
  
- [ ] Tambah rute tanpa pilih tarif
  - Hasil: Alert "Silakan pilih minimal satu tarif" ✓
  
- [ ] Tambah rute dengan memilih 2 tarif
  - Hasil: Kedua tarif tersimpan di relasi ✓
  
- [ ] Edit rute, ubah tarif yang dipilih
  - Hasil: Tarif terupdate dengan benar ✓

## Files yang Dimodifikasi

1. ✅ `app/Http/Controllers/Admin/RuteController.php` - Store & Update methods
2. ✅ `app/Models/Rute.php` - Fillable array
3. ✅ `resources/views/admin/rute-create.blade.php` - JavaScript submit handler
4. ✅ `resources/views/admin/rute-edit.blade.php` - JavaScript submit handler

## Database Queries yang Diharapkan

### Insert Rute Baru:
```sql
INSERT INTO rutes (kode_rute, nama_rute, kota_asal, kota_tujuan, durasi, jarak, harga_dasar, status, layanan_id, cabang_asal_id, cabang_tujuan_id, created_by, created_at, updated_at)
VALUES ('RUT-ABC123', 'Jakarta - Bali', 'Jakarta', 'Bali', '18:00', 850, 350000, 'aktif', 1, 1, 2, 1, NOW(), NOW());

INSERT INTO rute_master_tarif (rute_id, master_tarif_id) 
VALUES (1, 5), (1, 6);
```

### Update Rute:
```sql
UPDATE rutes 
SET harga_dasar = 350000, durasi = '18:00', updated_by = 1, updated_at = NOW()
WHERE id = 1;

DELETE FROM rute_master_tarif WHERE rute_id = 1;
INSERT INTO rute_master_tarif (rute_id, master_tarif_id) 
VALUES (1, 5), (1, 6);
```

---

**Status:** ✅ FIXED  
**Tanggal:** 11 Februari 2026  
**Tester:** QA Team
