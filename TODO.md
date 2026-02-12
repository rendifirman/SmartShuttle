# TODO: Fix Seat Booking Inconsistency Between Jadwals and Driver_Jadwals

## Problem Description
The main problem is inconsistency between two seat booking flows:
- Old flow (using jadwals and kursi_terpesan tables)
- New flow (using driver_jadwals without recording seats to kursi_terpesan)

For driver_jadwals, the system only relies on searching seat numbers in detail_penumpang table (prone to format mismatches) and doesn't use database locking, causing race conditions where seats appear available but are actually booked by other users.

## Completed Tasks ✅

### 1. Database Schema Changes
- [x] Add `id_jadwal_driver` column to `kursi_terpesan` table
- [x] Create migration `2026_02_12_065116_add_driver_jadwal_id_to_kursi_terpesan_table.php`
- [x] Run migration successfully

### 2. Model Updates
- [x] Update `KursiTerpesan` model to include `id_jadwal_driver` in fillable fields
- [x] Add relationship to `DriverJadwal` model
- [x] Update `getLayoutWithStatus` method to support both jadwal_id and id_jadwal_driver
- [x] Fix undefined variable `$query` in `getKursiTersedia` method

### 3. Controller Updates
- [x] Update `KursiController` to pass `id_jadwal_driver` to `getLayoutWithStatus`
- [x] Update seat validation logic to check both jadwal_id and id_jadwal_driver
- [x] Update `CustomerController` to create `KursiTerpesan` records for both flows
- [x] Update seat layout generation for driver_jadwals to use `KursiTerpesan` model

### 4. Consistency Improvements
- [x] Ensure both jadwals and driver_jadwals flows create records in `kursi_terpesan` table
- [x] Use database locking (`lockForUpdate`) to prevent race conditions
- [x] Single source of truth for seat availability across all schedule types

## Testing Required 🔍

### 1. Functional Tests
- [ ] Test seat booking for jadwals flow (existing functionality)
- [ ] Test seat booking for driver_jadwals flow (new functionality)
- [ ] Test concurrent booking prevention (race condition test)
- [ ] Test seat availability display in both flows

### 2. Integration Tests
- [ ] Test migration doesn't break existing data
- [ ] Test both flows work with same seat numbers
- [ ] Test cancellation and seat release in both flows

### 3. Performance Tests
- [ ] Test database locking doesn't cause deadlocks
- [ ] Test query performance with new indexes

## Files Modified 📁

1. `database/migrations/2026_02_12_065116_add_driver_jadwal_id_to_kursi_terpesan_table.php`
2. `app/Models/KursiTerpesan.php`
3. `app/Http/Controllers/KursiController.php`
4. `app/Http/Controllers/CustomerController.php`

## Next Steps 🚀

1. Run comprehensive tests to verify the fix works
2. Monitor for any performance issues with database locking
3. Consider adding database indexes if needed for performance
4. Update documentation to reflect the unified seat booking approach

## Verification Commands 💻

```bash
# Test seat booking flows
php artisan test --filter=SeatBookingTest

# Check database schema
php artisan migrate:status

# Run specific test file
php test_booking_flow.php
php test_booking_comprehensive.php
