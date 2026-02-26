# Dashboard Fix TODO

## Task:
- Fix sales chart data to show actual revenue per service
- Fix popular routes to be based on customer bookings

## Steps:
1. [x] Analyze AdminController.php dashboard method
2. [x] Analyze MLayanan model
3. [x] Analyze Pemesanan model
4. [x] Fix sales chart data - use actual layanan revenue from Pemesanan
5. [x] Fix popular routes - ensure proper join to Rute
6. [x] Test the changes (verify in browser)

## Changes Made:

### 1. Sales Chart Data (Grafik Penjualan)
- **Before:** Used proportional allocation (60% Shuttle, 25% Send, 15% Rent) based on total Pembayaran
- **After:** Query actual revenue per layanan from Pemesanan table joined through:
  - Pemesanan → Jadwal → Rutes (many-to-many) → MLayanan (via layanan_id)
- Query filters by status: ['paid', 'completed', 'selesai', 'dibayar']
- Uses `total_bayar` field from Pemesanan

### 2. Popular Routes (Rute Terpopuler)
- **Before:** Used `nama_rute` field from Rute
- **After:** Uses `kota_asal → kota_tujuan` format for better readability
- Added additional payment statuses: 'dibayar'
- Fixed route name format: "$rute->kota_asal . ' → ' . $rute->kota_tujuan"

### 3. Both admin_cabang and admin_pusat sections updated
- Both branches of the if-else now use actual service revenue data
