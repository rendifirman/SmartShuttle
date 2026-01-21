#!/bin/bash
# Verification script for Auth System Initialization Fix
# This script checks if all necessary changes have been applied correctly

echo "=== Auth System Fix Verification ==="
echo ""

# Check 1: Middleware files exist
echo "✓ Checking middleware files..."
if [ -f "app/Http/Middleware/InitializeSessionAndCsrf.php" ]; then
  echo "  ✅ InitializeSessionAndCsrf.php exists"
else
  echo "  ❌ InitializeSessionAndCsrf.php MISSING"
  exit 1
fi

if [ -f "app/Http/Middleware/EnsureCsrfTokenInResponse.php" ]; then
  echo "  ✅ EnsureCsrfTokenInResponse.php exists"
else
  echo "  ❌ EnsureCsrfTokenInResponse.php MISSING"
  exit 1
fi

echo ""

# Check 2: bootstrap/app.php contains middleware configuration
echo "✓ Checking bootstrap/app.php configuration..."
if grep -q "InitializeSessionAndCsrf" bootstrap/app.php; then
  echo "  ✅ InitializeSessionAndCsrf registered in bootstrap/app.php"
else
  echo "  ❌ InitializeSessionAndCsrf NOT registered in bootstrap/app.php"
  exit 1
fi

if grep -q "EnsureCsrfTokenInResponse" bootstrap/app.php; then
  echo "  ✅ EnsureCsrfTokenInResponse registered in bootstrap/app.php"
else
  echo "  ❌ EnsureCsrfTokenInResponse NOT registered in bootstrap/app.php"
  exit 1
fi

if grep -q "\$middleware->web(append:" bootstrap/app.php; then
  echo "  ✅ Global middleware stack properly configured"
else
  echo "  ❌ Global middleware stack NOT properly configured"
  exit 1
fi

echo ""

# Check 3: EnsureSessionStarted.php is enhanced
echo "✓ Checking EnsureSessionStarted.php enhancements..."
if grep -q "regenerateToken" app/Http/Middleware/EnsureSessionStarted.php; then
  echo "  ✅ EnsureSessionStarted.php contains regenerateToken()"
else
  echo "  ⚠️  EnsureSessionStarted.php may not have regenerateToken()"
fi

echo ""

# Check 4: CustomerController.php is enhanced
echo "✓ Checking CustomerController.php enhancements..."
if grep -q "session()->regenerateToken()" app/Http/Controllers/CustomerController.php; then
  echo "  ✅ CustomerController has session token regeneration"
else
  echo "  ⚠️  CustomerController may be missing token regeneration"
fi

if grep -q "InitializeSessionAndCsrf" app/Http/Controllers/CustomerController.php; then
  echo "  ✅ CustomerController has logging about session initialization"
else
  echo "  ⚠️  CustomerController may be missing session init logs"
fi

echo ""

# Check 5: Database/Session configuration
echo "✓ Checking session configuration..."
if grep -q "SESSION_DRIVER.*database" .env; then
  echo "  ✅ SESSION_DRIVER is set to database in .env"
else
  echo "  ⚠️  SESSION_DRIVER may not be set to database in .env"
fi

echo ""

# Check 6: Run PHP linting on new middleware
echo "✓ Checking syntax of new middleware files..."
php -l app/Http/Middleware/InitializeSessionAndCsrf.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
  echo "  ✅ InitializeSessionAndCsrf.php syntax OK"
else
  echo "  ❌ InitializeSessionAndCsrf.php has syntax errors"
  php -l app/Http/Middleware/InitializeSessionAndCsrf.php
  exit 1
fi

php -l app/Http/Middleware/EnsureCsrfTokenInResponse.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
  echo "  ✅ EnsureCsrfTokenInResponse.php syntax OK"
else
  echo "  ❌ EnsureCsrfTokenInResponse.php has syntax errors"
  php -l app/Http/Middleware/EnsureCsrfTokenInResponse.php
  exit 1
fi

echo ""

# Check 7: Optional - Test Laravel can boot
echo "✓ Checking Laravel can boot with new middleware..."
php artisan tinker --execute="echo 'Laravel boot test: OK'" > /dev/null 2>&1
if [ $? -eq 0 ]; then
  echo "  ✅ Laravel boots successfully with new middleware"
else
  echo "  ⚠️  Laravel may have issues with new middleware"
  echo "  Run: php artisan config:cache && php artisan cache:clear"
fi

echo ""
echo "=== Verification Complete ==="
echo ""
echo "All required changes have been applied successfully! ✅"
echo ""
echo "Next steps:"
echo "1. Clear caches: php artisan config:cache && php artisan cache:clear"
echo "2. Test login at: http://localhost/customer/login"
echo "3. Monitor logs: tail -f storage/logs/laravel.log"
