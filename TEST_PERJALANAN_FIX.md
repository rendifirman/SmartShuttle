# 🧪 TEST PERJALANAN FIX - Update Lokasi Per-Outlet

## Masalah yang Diperbaiki
1. ✅ Sistem membaca update berdasarkan **outllet ke outlet**, bukan **branch ke branch**
2. ✅ Perpindahan antar outlet dalam branch yang sama sekarang terdeteksi sebagai update
3. ✅ Button "Update Lokasi" di modal sekarang bisa diklik (perbaikan event listener & CSRF token)

---

## Perubahan Kode

### 1. ✅ `buildJourneyDataFromStopPoints()` - Struktur Per-OUTLET (CRITICAL)
- **Sebelumnya**: Satu entry per branch/city dalam `journeyData.stops`
- **Sekarang**: Satu entry per OUTLET
- **Impact**: 
  - Perpindahan outlet 1 → outlet 2 dalam branch yang sama = perubahan `stop_index` ✓
  - Setiap outlet memiliki outlet_id, outlet_detail, kota, branch_id

```javascript
// STRUKTUR LAMA (PER-BRANCH):
journeyData.stops = [
  { name: "Jakarta", outlets: [outlet1, outlet2, outlet3] },  // branch_id=1
  { name: "Depok", outlets: [outlet4] }  // branch_id=2
]

// STRUKTUR BARU (PER-OUTLET):
journeyData.stops = [
  { name: "Sudirman", outlet_id: 101, branch_id: 1 },
  { name: "Blok M", outlet_id: 102, branch_id: 1 },
  { name: "Jakarta Kota", outlet_id: 103, branch_id: 1 },
  { name: "Margonda", outlet_id: 201, branch_id: 2 }
]
```

### 2. ✅ `confirmUpdateLokasi()` - Error Handling CSRF Token
- **Masalah**: "Cannot read properties of null (reading 'getAttribute')"
- **Perbaikan**:
  - 3 cara mengambil CSRF token (meta tag, hidden input, window.Laravel)
  - Fallback untuk setiap method
  - Error log jika CSRF token tidak ditemukan

```javascript
// CSRF Token - 3 cara fallback
let csrfToken = 
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
  document.querySelector('input[name="_token"]')?.value ||
  window.Laravel?.csrfToken;

if (!csrfToken) {
  console.error('❌ CSRF Token tidak ditemukan!');
  return;
}
```

### 3. ✅ Event Listeners - Dual Approach (Robust)
- **Event Delegation**: Manahdle click events di level document (tidak perlu wait untuk element load)
- **Direct Listeners**: Fallback untuk browser lama
- **Includes**: confirmUpdateBtn, updateLokasiBtn, mulaiPerjalananBtn, dll

```javascript
// Cara 1: Event Delegation (modern & robust)
document.addEventListener('click', function(e) {
  if (e.target?.id === 'confirmUpdateBtn') {
    confirmUpdateLokasi();
  }
});

// Cara 2: Direct listener (fallback)
document.getElementById('confirmUpdateBtn')?.addEventListener('click', confirmUpdateLokasi);
```

### 4. ✅ CSS - pointer-events & z-index
- Tambahkan `pointer-events: auto` untuk modal buttons
- Tambahkan `z-index: 2001` untuk modal-buttons agar selalu di atas

---

## Test Plan

### Test 1: Multi-Outlet satu Branch
```
Scenario: Driver update lokasi dari outlet 1 → outlet 2 → outlet 3 (semua di Jakarta branch)
Expected: 
  ✓ Stop index berubah: 1 → 2 → 3 (bukan langsung ke tujuan)
  ✓ Setiap outlet tercatat dalam location history
  ✓ Modal muncul dan button bisa diklik untuk setiap update
```

### Test 2: Button Click Dalam Modal
```
Step 1: Klik "Update Lokasi" → Modal muncul
Step 2: Klik button "Update" di dalam modal → Event handler dipanggil
Expected:
  ✓ Console log: "🔄 confirmUpdateLokasi dipanggil..."
  ✓ Console log: "📤 Mengirim update lokasi: {...}"
  ✓ Response sukses dari API
  ✓ Modal tertutup
  ✓ Stop index increment
```

### Test 3: Perjalanan Multi-Branch
```
Scenario: Jakarta (3 outlet) → Depok (1 outlet) → Bandung (2 outlet)
Expected:
  ✓ Total 6 stops dalam journeyData.stops
  ✓ Setiap perpindahan tercatat (outlet → outlet, bukan branch → branch)
  ✓ Final destination adalah outlet terakhir di Bandung
```

### Test 4: Browser Console Check
```
Buka Developer Tools (F12) → Console
Expected logs:
  ✅ Event listener untuk ... terdaftar
  ✅ Journey data built dengan struktur per-OUTLET
  ✅ 🔄 confirmUpdateLokasi dipanggil...
  ✅ 📤 Mengirim update lokasi: {...}
```

---

## Debugging Tips

### Jika Button Masih Tidak Bisa Diklik:
1. Buka DevTools → Console
2. Cari log: "Semua event listeners (delegation + direct) berhasil setup"
3. Jika tidak ada, reload page
4. Cek Network tab untuk CSRF token
5. Coba inline console: `document.getElementById('confirmUpdateBtn').click()`

### Jika API Error:
1. Buka Network tab → XHR requests
2. Cek status response (400 = client error, 500 = server error)
3. Lihat error response di console
4. Pastikan CSRF token ada: `console.log('CSRF:', document.querySelector('meta[name="csrf-token"]')?.content)`

### Jika Stop Index Tidak Berubah:
1. Cek `journeyData.stops.length` → harus per-outlet, bukan per-branch
2. Cek apakah `buildJourneyDataFromStopPoints()` dipanggil dengan benar
3. Log: `console.log('journeyData', journeyData)`

---

## Rollback (Jika Ada Issue)

Jika ada masalah, revert ke commit sebelumnya:
```bash
git revert HEAD~1
```

---

## ✅ Checklist Selesai

- [x] Struktur `journeyData.stops` berubah dari per-branch ke per-outlet
- [x] `buildJourneyDataFromStopPoints()` membuat entry per outlet
- [x] `showUpdateLokasiModal()` menampilkan outlet detail dengan benar  
- [x] `confirmUpdateLokasi()` punya error handling CSRF token
- [x] Event listeners dipasang dengan event delegation + direct
- [x] CSS pointer-events dan z-index dikonfigurasi
- [x] Console logs ditambahkan untuk debugging

---

**Status**: ✅ Ready for Testing
**Date**: 2026-02-22
