php# Migration and Route/Schedule Issues Fix

## Issues Identified
- Migration order problem: `rutes` table references `m_layanan` before it's created
- Duplicate migration: `add_layanan_id_to_rutes_table.php` is redundant
- Foreign key constraint failure during `php artisan migrate:fresh --seed`

## Tasks to Complete
- [ ] Rename `create_m_layanan_table.php` to run before `create_rutes_table.php`
- [ ] Remove duplicate `add_layanan_id_to_rutes_table.php` migration
- [ ] Fix foreign key reference in rutes migration (use correct column name)
- [ ] Test migration execution
- [ ] Verify route/schedule relationships work correctly
