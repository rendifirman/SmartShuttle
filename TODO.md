# Admin Login Fix - TODO

## Completed Tasks
- [x] Added missing admin dashboard route to routes/web.php
- [x] Modified CustomerController login method to use admin guard for admin users
- [x] Verified routes are properly registered
- [x] Checked for syntax errors

## Summary of Changes
1. **Added admin dashboard route**: Added `Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');` to the admin routes group in routes/web.php
2. **Fixed authentication guard**: Modified the login logic in CustomerController to log out from web guard and log in with admin guard when user has admin roles

## Testing Required
- Test admin login with admin@smartshuttle.test / admin123
- Verify admin is redirected to admin/dashboard.blade.php
- Verify customer login still works normally
