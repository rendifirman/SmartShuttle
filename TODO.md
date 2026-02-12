# TODO: Remove Pengadaan Bahan Feature

## Tasks
- [x] Remove permission `view_pengadaan_bahan` from PermissionSeeder.php
- [x] Remove route `/admin/pengadaan-bahan` from routes/web.php
- [x] Remove `pengadaanBahan()` method from AdminController.php
- [x] Remove menu item from admin sidebar layout (app-admin.blade.php)
- [x] Delete view file `resources/views/admin/pengadaan-bahan.blade.php`

## Followup Steps
- [ ] Run database seeder to update permissions
- [ ] Clear route cache
