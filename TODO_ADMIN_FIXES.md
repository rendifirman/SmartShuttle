# Admin Controller and Routes Fixes

## Issues Found:
1. Routes calling AdminController methods that should be in Admin\ArmadaController
2. Missing methods in AdminController (tiketPerjalanan, etc.)
3. Incorrect route structure and controller assignments

## Fixes Needed:

### 1. Update Routes to Use Correct Controllers
- [ ] Change armada routes to use Admin\ArmadaController instead of AdminController
- [ ] Update route names and middleware for armada routes
- [ ] Fix any other misassigned routes

### 2. Add Missing Methods to AdminController
- [ ] Add tiketPerjalanan method
- [ ] Add any other missing methods called by routes

### 3. Remove Duplicate/Incorrect Methods from AdminController
- [ ] Remove armada-related methods from AdminController (armada, createShuttle, storeShuttle, etc.)
- [ ] Keep only general admin methods in AdminController

### 4. Update Route Imports
- [ ] Add proper imports for Admin namespace controllers
- [ ] Ensure all routes use correct controller references

### 5. Test Routes
- [ ] Verify all admin routes work correctly
- [ ] Check for any remaining method not found errors
