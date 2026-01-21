# Dokumentasi: Status Dinamis di Halaman Riwayat Pemesanan

## Ringkasan Perubahan

Halaman riwayat pemesanan (`riwayat.blade.php`) telah diubah dari menggunakan status statis menjadi membaca status terbaru secara dinamis dari database. Status sekarang terhubung langsung dengan data pembayaran sehingga akan otomatis terupdate ketika status pembayaran berubah.

## Masalah yang Diselesaikan

Sebelumnya, status di halaman riwayat ditampilkan berdasarkan hardcoded logic yang melihat `status_pemesanan`:
```php
// SEBELUM (statis dan tidak real-time)
if ($pemesanan->status_pemesanan == 'menunggu_pembayaran' || $pemesanan->status_pemesanan == 'diproses') {
    $status = 'proses';
} elseif ($pemesanan->status_pemesanan == 'selesai' || $pemesanan->status_pemesanan == 'dikonfirmasi') {
    $status = 'selesai';
}
```

Hal ini menyebabkan:
- Status tidak langsung terupdate ketika pembayaran berhasil
- Pengguna harus refresh halaman untuk melihat status terbaru
- Tidak ada sinkronisasi antara status pembayaran dan tampilan riwayat

## Solusi yang Diterapkan

### 1. Model `Pemesanan.php` - Tambah Relasi dan Methods

**a) Tambah Relasi Pembayaran:**
```php
// Relasi ke pembayaran
public function pembayaran()
{
    return $this->hasOne(Pembayaran::class, 'pemesanan_id');
}
```

**b) Method `getStatusDisplayAttribute()` - Logika Status Dinamis:**
```php
public function getStatusDisplayAttribute()
{
    // Ambil pembayaran terbaru dari relasi
    $pembayaran = $this->pembayaran;

    // Jika ada pembayaran dan statusnya 'sukses' atau 'dibayar', tampilkan 'selesai'
    if ($pembayaran && in_array($pembayaran->status, ['sukses', 'dibayar', 'berhasil', 'success'])) {
        return 'selesai';
    }

    // Jika status pemesanan adalah 'dibayar', 'selesai', atau 'dikonfirmasi'
    if (in_array($this->status, ['dibayar', 'selesai', 'dikonfirmasi', 'dikonfirmasi_pembayaran'])) {
        return 'selesai';
    }

    // Jika pemesanan sedang menunggu pembayaran atau diproses
    if (in_array($this->status, ['menunggu_pembayaran', 'diproses', 'menunggu_konfirmasi'])) {
        return 'proses';
    }

    // Status default: open (belum ada aksi)
    return 'open';
}
```

**c) Method `getStatusLabelAttribute()` - Label Display:**
```php
public function getStatusLabelAttribute()
{
    $statusDisplay = $this->getStatusDisplayAttribute();
    
    $labels = [
        'open' => 'Open',
        'proses' => 'Proses',
        'selesai' => 'Sukses'
    ];

    return $labels[$statusDisplay] ?? 'Open';
}
```

### 2. Controller `CustomerController.php` - Eager Load Pembayaran

Update method `showRiwayat()` untuk eager load relasi pembayaran:
```php
$riwayat = Pemesanan::with([
    'jadwal.shuttle',
    'jadwal.rutes',
    'detailPenumpang',
    'pembayaran' // ← Tambah eager load pembayaran
])
->where('customer_id', $user->id)
->orderBy('created_at', 'desc')
->get();
```

Ini memastikan data pembayaran sudah dimuat dalam satu query untuk efisiensi database.

### 3. View `riwayat.blade.php` - Gunakan Status Dinamis

**a) Ubah logika status dari statis ke dinamis:**
```php
// SESUDAH (dinamis dan real-time dari database)
$status = $pemesanan->status_display;      // Accessor yang membaca dari database
$statusLabel = $pemesanan->status_label;   // Label untuk tampilan
```

**b) Tampilkan status label secara dinamis:**
```blade
<div class="status {{ $status }}">
    {{ $statusLabel }}
</div>
```

## Alur Kerja Status

```
┌─────────────────────────────────────────────────────────┐
│ User membuka halaman riwayat pemesanan                   │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
        ┌──────────────────────────────────┐
        │ Controller query dengan           │
        │ eager load relasi pembayaran      │
        └──────────────────┬───────────────┘
                          │
                          ▼
        ┌──────────────────────────────────┐
        │ View mengakses attribute:         │
        │ $pemesanan->status_display      │
        └──────────────────┬───────────────┘
                          │
                          ▼
        ┌──────────────────────────────────────────┐
        │ Method getStatusDisplayAttribute()        │
        │ Membaca status dari tabel pembayaran      │
        └──────────────────┬───────────────────────┘
                          │
                    ┌─────┴─────┐
                    │           │
        ┌───────────▼──┐  ┌─────▼──────────┐
        │ Cek: Apakah  │  │ Cek: Apakah    │
        │ ada pembayaran│  │ status pemesanan│
        │ status sukses?│  │ = dibayar?     │
        └───────────┬──┘  └─────┬──────────┘
                    │           │
                 YA │        YA │
                    └─────┬─────┘
                          │
                          ▼
                    Return 'selesai'
                    (tampil sebagai 'Sukses')
```

## Keuntungan Implementasi Ini

1. **Real-Time Updates**: Status berubah otomatis ketika pembayaran berhasil tanpa perlu refresh manual
2. **Single Source of Truth**: Status selalu membaca dari database pembayaran terbaru
3. **Efisiensi Query**: Menggunakan eager loading untuk menghindari N+1 query problem
4. **Fleksibel**: Mudah menambahkan status baru atau mengubah logika di satu tempat
5. **Tidak Perlu Update Manual**: Field `status` di tabel `pemesanan` tidak perlu diupdate secara manual

## Testing

Untuk memverifikasi bahwa status dinamis bekerja dengan baik:

1. **Test Scenario 1 - Status Terbuka (Open):**
   - Buat pemesanan baru tanpa pembayaran
   - Halaman riwayat akan menampilkan status "Open"

2. **Test Scenario 2 - Status Diproses (Proses):**
   - Buat pemesanan dan mulai proses pembayaran
   - Halaman riwayat akan menampilkan status "Proses"

3. **Test Scenario 3 - Status Berhasil (Sukses):**
   - Selesaikan pembayaran (status pembayaran = 'sukses' atau 'dibayar')
   - Halaman riwayat akan menampilkan status "Sukses" secara otomatis
   - Tidak perlu refresh halaman, status akan terupdate ketika user membuka halaman riwayat

## Status Nilai Database

Method mempertimbangkan nilai status berikut dari tabel `pembayaran`:
- `'sukses'`, `'dibayar'`, `'berhasil'`, `'success'` → Tampil sebagai "Sukses" (selesai)
- `'menunggu_pembayaran'`, `'diproses'`, `'menunggu_konfirmasi'` → Tampil sebagai "Proses"
- Lainnya → Tampil sebagai "Open"

## File yang Diubah

1. **app/Models/Pemesanan.php**
   - Tambah relasi `pembayaran()`
   - Tambah method `getStatusDisplayAttribute()`
   - Tambah method `getStatusLabelAttribute()`

2. **app/Http/Controllers/CustomerController.php**
   - Update `showRiwayat()` untuk eager load pembayaran

3. **resources/views/customer/riwayat.blade.php**
   - Ubah logika status dari statis ke dinamis
   - Gunakan `status_display` dan `status_label` attributes

## Catatan Penting

- Status ditampilkan berdasarkan data terbaru di database
- Query dilakukan dengan eager loading untuk performa optimal
- Tidak ada caching yang mengganggu, status selalu fresh
- Kompatibel dengan nilai status pembayaran yang berbeda-beda

## Pengembangan Lebih Lanjut

Untuk fitur yang lebih canggih di masa depan:

1. **Real-Time Notification**: Gunakan WebSocket/Pusher untuk update status real-time tanpa refresh
2. **Status History**: Simpan history perubahan status untuk audit trail
3. **Webhook Integration**: Integrasi dengan payment gateway untuk auto-update status pembayaran
