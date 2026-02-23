# Update Tampilan Tarif di Halaman Pesan Shuttle

## Ringkasan Perubahan
Halaman pemesanan (`resources/views/customer/pesan.blade.php`) telah diupdate untuk menampilkan **semua tarif yang tersedia di rute** dalam perhitungan harga, bukan hanya satu tarif terpilih.

## Lokasi File yang Diubah
- **File**: `resources/views/customer/pesan.blade.php`

## Detail Perubahan

### 1. **Card Kiri (Price Summary) - Bagian Harga**
Ditambahkan section baru yang menampilkan semua tarif tersedia dari rute sebelum perhitungan harga:

```
Tarif Rute:
┌─────────────────────────────────────┐
│ Tarif 1          Rp 100.000/orang   │
│ Tarif 2          Rp 110.000/orang   │
│ Tarif 3          Rp 95.000/orang    │
└─────────────────────────────────────┘

Harga Tiket per orang (Terpakai): Rp 100.000
Jumlah Penumpang: × 2
Subtotal: Rp 200.000
Diskon Promo: - Rp 0
Total Bayar: Rp 200.000
```

**Fitur:**
- Menampilkan semua tarif dalam list dengan scrollable container (max-height: 150px)
- Setiap tarif menampilkan:
  - Nama tarif
  - Jenis tarif (reguler, premium, dll)
  - Harga per orang
  - Delta (selisih dari harga dasar) dengan warna hijau untuk peningkatan, merah untuk penurunan

### 2. **Card Kanan (Data Pemesanan) - Tarif Rute Section**
Ditambahkan section baru setelah Data Penumpang, sebelum Promo Section:

```
Tarif Tersedia di Rute
┌─────────────────────────────────┐
│ Tarif 1 [TERPAKAI]      Rp 100K │
│ Tarif 2                 Rp 110K │
│ Tarif 3                 Rp 95K  │
└─────────────────────────────────┘
```

**Fitur:**
- Menampilkan list tarif dengan scrollable container (max-height: 180px)
- Tarif yang sedang dipakai (index 0) ditandai dengan badge "TERPAKAI" dan border kiri berwarna orange (#FF581E)
- Tarif lainnya memiliki border kiri warna abu-abu untuk membedakan
- Setiap baris tarif menampilkan harga dan delta perubahan

## Data yang Digunakan
Data tarif berasal dari controller `CustomerController.php` method `pesan()`:

```php
$availableTarifs = $ruteObj->masterTarifs()->where('status','aktif')
    ->where(function($q){
        $q->whereNull('tanggal_berlaku')->orWhere('tanggal_berlaku','<=',now());
    })->where(function($q){
        $q->whereNull('tanggal_kadaluarsa')->orWhere('tanggal_kadaluarsa','>=',now());
    })->get();
```

Setiap tarif dalam array `$availableTarifs` sudah memiliki:
- `nama_tarif`: Nama tarif
- `jenis_tarif`: Jenis (reguler, premium, etc)
- `harga_dasar`: Harga dasar
- `final_price`: Harga setelah kalkulasi
- `delta`: Selisih dari harga dasar

## Keuntungan Perubahan
1. **Transparansi**: Customer bisa melihat semua opsi tarif yang tersedia untuk rute tersebut
2. **Informatif**: Menunjukkan harga tarifan dan selisihnya dibanding harga dasar
3. **User-friendly**: Dua lokasi untuk memudahkan customer membaca informasi tarif
4. **Responsive**: Layout teradaptasi baik di berbagai ukuran layar

## Kompatibilitas
Perubahan ini **100% backward compatible** karena:
- Menggunakan data yang sudah ada di controller
- Menggunakan conditional check `@if(!empty($availableTarifs) && count($availableTarifs) > 0)` sehingga tidak error jika data kosong
- Tidak mengubah logika perhitungan harga sama sekali
- Hanya menambah tampilan informatif

## Testing
Untuk testing:
1. Login sebagai customer
2. Lakukan pencarian rute yang memiliki multiple tarif
3. Klik to pemesanan
4. Verifikasi tampilan tarif di kedua card (kiri dan kanan)
5. Verifikasi harga yang terpakai sudah sesuai dengan tarif yang dipilih oleh controller

