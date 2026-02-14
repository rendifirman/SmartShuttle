# TODO: Fix Kursi Redirect Issue

## Problem
When user selects seats and clicks "Lanjutkan ke Detail Pesanan", the page redirects back to kursi instead of going to detail_pesanan. However, the seats ARE saved in the database.

## Root Cause Analysis
Looking at `CustomerController::prosesPemilihanKursi`, there are multiple validation points that could fail and redirect back:
1. Request validation (pemesanan_id, kursi array)
2. Double-submit check
3. Status validation (must be 'menunggu_kursi')
4. Seat count validation (must match jumlah_penumpang)
5. Duplicate seat validation
6. DriverJadwal/Legacy flow seat conflict validation

## Plan
1. Add more detailed logging to understand which validation is failing
2. Ensure the redirect to detail_pesanan works correctly
3. Add fallback handling for edge cases
4. Test the fix

## Files to Check
- app/Http/Controllers/CustomerController.php (prosesPemilihanKursi method)
- resources/views/customer/kursi.blade.php (form and JavaScript)
- routes/web.php (route definitions)

## Status
- [x] Analyze the code flow
- [ ] Add debugging/logging
- [ ] Test the fix
- [ ] Verify redirect works correctly
