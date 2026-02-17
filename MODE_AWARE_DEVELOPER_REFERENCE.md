# Mode-Aware System: Developer Quick Reference

## Reading the Current Mode

```php
// Always use this pattern to read the current mode
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

// The appSetting() helper:
// - Checks cache first (fast)
// - Falls back to database if not cached
// - Defaults to 'driver_confirmation' if not set
// - Returns: 'driver_confirmation' or 'direct_assign'
```

## Branching Logic by Mode

```php
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

if ($mode === 'driver_confirmation') {
    // Driver Confirmation specific logic
    // Drivers claim schedules
    // Customers see confirmed schedules
} else {  // 'direct_assign'
    // Direct Assign specific logic
    // Admin assigns drivers
    // Customers see all active schedules
}
```

## Schedule Status Usage

```php
// Use RuteJadwal model constants (defined in app/Models/RuteJadwal.php)
RuteJadwal::STATUS_OPEN      // 'open'     - Unclaimed (confirmation mode)
RuteJadwal::STATUS_ACTIVE    // 'active'   - Claimed/Assigned (both modes)
RuteJadwal::STATUS_CANCELLED // 'cancelled' - Cancelled
RuteJadwal::STATUS_DONE      // 'done'     - Completed

// DO NOT use hardcoded strings
// ❌ WRONG: where('status', 'confirmed')
// ✓ CORRECT: where('status', RuteJadwal::STATUS_ACTIVE)
```

## Common Tasks by Mode

### Task 1: Show Unclaimed Schedules (Confirmation Mode Only)

```php
// Only use in confirmation mode or with mode check
if ($mode === 'driver_confirmation') {
    $schedules = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)
        ->orderBy('tanggal')
        ->get();
}
```

### Task 2: Show Driver's Assigned Schedules (Both Modes)

```php
// Works in both modes - same query, different interpretation
$schedules = RuteJadwal::where('id_driver', auth()->id())
    ->orderBy('tanggal')
    ->get();
```

### Task 3: Show Available Schedules to Customers (Both Modes)

```php
// Both modes: customers see active schedules
$schedules = RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)
    ->orderBy('tanggal')
    ->paginate(20);
```

### Task 4: Let Driver Claim Schedule (Confirmation Mode Only)

```php
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

// Only allowed in confirmation mode
if ($mode !== 'driver_confirmation') {
    abort(403, 'Cannot claim schedules in direct assign mode');
}

// Update schedule
$schedule = RuteJadwal::findOrFail($id);
$schedule->id_driver = auth()->id();
$schedule->status = RuteJadwal::STATUS_ACTIVE;
$schedule->save();
```

### Task 5: Create Schedule with Mode Awareness

```php
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

// Validation
$rules = [
    'id_rute' => 'required|integer',
    'id_shuttle' => 'required|integer',
    'tanggal' => 'required|date',
    'jam_berangkat' => 'required',
];

// Mode-specific validation
if ($mode === 'direct_assign') {
    $rules['id_driver'] = 'required|integer';
}

$data = $request->validate($rules);

// Create schedule
$schedule = new RuteJadwal($data);

// Mode-specific status assignment
if ($mode === 'direct_assign') {
    $schedule->status = RuteJadwal::STATUS_ACTIVE;
    $schedule->id_driver = $request->input('id_driver');
} else {
    $schedule->status = RuteJadwal::STATUS_OPEN;
    $schedule->id_driver = null;
}

$schedule->save();
```

## When You Need to Invalidate Cache

The system uses caching for performance. After changing the mode:

```php
// After updating app_settings with new mode
AppSetting::updateOrCreate(
    ['key' => 'jadwal_flow_mode'],
    ['value' => 'driver_confirmation']
);

// ALWAYS clear the cache after update
Cache::forget('app_setting:jadwal_flow_mode');

// Next appSetting() call will read fresh from database
```

## Adding New Features

### Checklist for New Features

```
□ Does your feature work differently in each mode?
  YES → Add mode check, branch logic
  NO → Add comment explaining why mode-independent

□ Does your feature read/write mode configuration?
  YES → Use appSetting() helper, invalidate cache after writes
  NO → Continue

□ Does your feature query schedules by status?
  YES → Use RuteJadwal::STATUS_* constants (not hardcoded strings)
  NO → Continue

□ Does your feature interact with schedules creation?
  YES → Respect mode when setting status and id_driver
  NO → Continue

□ Does your feature appear in Blade templates?
  YES → Use appSetting('jadwal_flow_mode') to show/hide conditionally
  NO → Continue

✓ Review MODE_AWARE_SYSTEM_DOCUMENTATION.md for full context
✓ Test both modes work correctly with your feature
✓ Add comments explaining mode-specific behavior
```

### Example: Adding Mode Check to Existing Feature

```php
// Before (mode-independent)
public function getScheduleStats()
{
    return RuteJadwal::count();  // Total schedules
}

// After (mode-aware)
public function getScheduleStats()
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    $stats = [
        'total' => RuteJadwal::count(),
        'active' => RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)->count(),
    ];
    
    if ($mode === 'driver_confirmation') {
        $stats['open'] = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)->count();
    }
    
    return $stats;
}
```

## Testing Mode-Specific Features

```php
// In your test
public function testDriverConfirmationMode()
{
    // Set mode to driver_confirmation
    AppSetting::updateOrCreate(
        ['key' => 'jadwal_flow_mode'],
        ['value' => 'driver_confirmation']
    );
    Cache::forget('app_setting:jadwal_flow_mode');
    
    // Test your feature
    $response = $this->get('/driver/schedules');
    $this->assertSee('Claim');  // Claim button should exist
}

public function testDirectAssignMode()
{
    // Set mode to direct_assign
    AppSetting::updateOrCreate(
        ['key' => 'jadwal_flow_mode'],
        ['value' => 'direct_assign']
    );
    Cache::forget('app_setting:jadwal_flow_mode');
    
    // Test your feature
    $response = $this->postJson('/driver/schedules/1/claim');
    $this->assertEquals(403, $response->status());  // Should be forbidden
}
```

## Performance Tips

### Tip 1: Read Mode Once Per Request

```php
// ✓ GOOD - Read once, reuse
public function index()
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    if ($mode === 'confirmation') {
        $schedules = $this->getConfirmationSchedules();
    } else {
        $schedules = $this->getDirectSchedules();
    }
}

// ❌ BAD - Reading multiple times
public function index()
{
    foreach ($items as $item) {
        if (appSetting('jadwal_flow_mode') === 'confirmation') {  // Don't do this
            // ...
        }
    }
}
```

### Tip 2: Use Query Scopes for Common Patterns

```php
// In app/Models/RuteJadwal.php
public function scopeForConfirmationMode($query)
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    if ($mode === 'driver_confirmation') {
        return $query->where('status', RuteJadwal::STATUS_OPEN);
    }
    return $query;
}

// Usage in controller
$schedules = RuteJadwal::forConfirmationMode()->get();  // Cleaner!
```

## Debugging Tips

### Check Current Mode in Database

```
Database: SELECT * FROM app_settings WHERE key = 'jadwal_flow_mode';

If missing → Falls back to 'driver_confirmation' (default)
If cached → appSetting() returns cached value
If stale → Clear cache: Cache::forget('app_setting:jadwal_flow_mode');
```

### trace Mode in Controller

```php
// Quick debug helper
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
\Log::info("Current mode: {$mode}");
dd($mode);  // Dump and die
```

### Test Query by Mode

```php
// In tinker or test
$mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
$count = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)->count();
// Should be 0 in direct_assign mode (no open schedules expected)
// Should be > 0 in driver_confirmation mode (unclaimed schedules)
```

## Code Examples Library

### Example 1: Mode-Aware Report Generation

```php
public function generateReport()
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    $report = [
        'period' => 'Monthly',
        'mode' => $mode,
    ];
    
    if ($mode === 'driver_confirmation') {
        $report['unclaimed'] = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)->count();
        $report['claimed'] = RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)->count();
    } else {
        $report['assigned'] = RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)->count();
        $report['unassigned'] = RuteJadwal::whereNull('id_driver')->count();
    }
    
    return $report;
}
```

### Example 2: Mode-Aware Notification

```php
public function notifyAvailableSchedules()
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    $schedules = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)->get();
    
    foreach ($schedules as $schedule) {
        if ($mode === 'driver_confirmation') {
            // Notify all drivers about open schedules
            Notification::send($drivers, new ScheduleAvailable($schedule));
        } else {
            // No notification (admin assigned drivers already know)
            // Or send only to assigned driver
            Notification::send(
                User::find($schedule->id_driver),
                new ScheduleAssigned($schedule)
            );
        }
    }
}
```

### Example 3: Mode-Aware API Response

```php
public function getSchedules()
{
    $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
    
    $schedules = RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)->get();
    
    return response()->json([
        'mode' => $mode,
        'schedules' => $schedules->map(fn($s) => [
            'id' => $s->id,
            'route' => $s->rute->name,
            'date' => $s->tanggal,
            'driver' => $s->driver->name,
            'can_claim' => $mode === 'driver_confirmation' && $s->status === RuteJadwal::STATUS_OPEN,
        ]),
    ]);
}
```

---

## Reference Tables

### Status Values (from app/Models/RuteJadwal.php)

| Constant | Value | Meaning | Used In |
|----------|-------|---------|---------|
| STATUS_OPEN | 'open' | Unclaimed schedule | confirmation mode |
| STATUS_ACTIVE | 'active' | Claimed/Assigned | both modes |
| STATUS_CANCELLED | 'cancelled' | Cancelled | both modes |
| STATUS_DONE | 'done' | Completed | both modes |

### Mode Values

| Mode | Flow | Driver Init | Status Init | Has Claim |
|------|------|------------|------------|-----------|
| driver_confirmation | Flexible | None | open | Yes |
| direct_assign | Admin-controlled | Required | active | No |

### Cache Keys

| Key | TTL | Invalidate When |
|-----|-----|-----------------|
| app_setting:jadwal_flow_mode | Forever | Mode changes |

---

**Last Updated:** 2024-01-20  
**For Questions:** See MODE_AWARE_SYSTEM_DOCUMENTATION.md
