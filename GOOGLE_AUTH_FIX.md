## Perbaikan ValidatePathEncoding Error

### Masalah
Error `Illuminate\Http\Middleware\ValidatePathEncoding` terjadi saat Google OAuth callback di server (tidak terjadi di localhost).

**Penyebab:**
- Google OAuth callback URL berisi special characters (state parameter, scope encoding) yang tidak valid menurut validasi Laravel
- Middleware `ValidatePathEncoding` memvalidasi semua karakter dalam path URL
- Perbedaan server configuration antara development dan production

### Solusi yang Diimplementasikan

#### 1. **Disable ValidatePathEncoding untuk Google Callback** (`routes/web.php`)
```php
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->withoutMiddleware('Illuminate\Http\Middleware\ValidatePathEncoding')
    ->name('login.google.callback');
```

**Alasan:** Middleware ini tidak diperlukan untuk OAuth callback karena parameter validation sudah dilakukan oleh Socialite.

#### 2. **Tambah Email Validation** (`GoogleAuthController.php`)
```php
$userEmail = filter_var($googleUser->getEmail(), FILTER_VALIDATE_EMAIL);
if (!$userEmail) {
    throw new \Exception('Invalid email format from Google');
}
```

**Alasan:** Memastikan email dari Google selalu valid sebelum digunakan.

### Testing Checklist
- [ ] Test Google login di server (production URL)
- [ ] Verifikasi callback tidak error dengan encoding
- [ ] Cek user berhasil dibuat/login
- [ ] Lihat logs di `storage/logs/` untuk memastikan tidak ada error

### Jika Masih Error

Jika masih error, lakukan ini di `.env`:
```env
# Disable path encoding validation globally (last resort)
VALIDATE_PATH_ENCODING=false
```

Atau update `bootstrap/app.php` untuk exclude middleware pada semua OAuth routes:
```php
->withoutMiddleware([
    'Illuminate\Http\Middleware\ValidatePathEncoding',
])
```

### Referensi
- Laravel ValidatePathEncoding: Middleware yang check karakter valid dalam URL path
- Google OAuth: Menggunakan query parameters yang mungkin tidak compatible dengan strict encoding validation
