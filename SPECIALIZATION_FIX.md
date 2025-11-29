# Physician Specialization Fix

## Problem
Physician specialties were showing as numbers (e.g., "15, 8, 9") instead of specialty names (e.g., "Radiology, Orthopedics, Neurology").

## Root Cause
There was a mismatch between:
1. **medical_specializations.json**: Contains 20 specialization definitions (IDs 1-20)
2. **Database specializations table**: Only had 6 specializations (IDs 1-6)

When a physician registered with specialization IDs 7-20 (which don't exist in the database):
- The registration form sent numeric IDs like "8", "9", "15"
- The old controller code treated these as specialization names
- It created NEW specializations with names "8", "9", "15" instead of looking up the existing IDs
- This resulted in customers and doctors seeing numeric values instead of proper specialty names

## Solution Applied

### 1. Fixed Controller Logic (`controllers/customer_controller.php`)
Updated `register_customer_ctr()` method to:
- Check if the provided value is numeric (an ID) or text (a name)
- If numeric: use it directly as a specialization ID
- If text: create/lookup the specialization by name

**Before:**
```php
foreach ($specs as $specName) {
    if (empty($specName)) continue;
    $specId = $this->customerModel->addSpecialization($specName);  // Always treats as name
    if ($specId) $this->customerModel->addCustomerSpecialization($customer_id, $specId);
}
```

**After:**
```php
foreach ($specs as $spec) {
    if (empty($spec)) continue;
    if (is_numeric($spec)) {
        // It's a specialization ID, use it directly
        $specId = (int)$spec;
    } else {
        // It's a specialization name, get or create it
        $specId = $this->customerModel->addSpecialization($spec);
    }
    if ($specId) $this->customerModel->addCustomerSpecialization($customer_id, $specId);
}
```

### 2. Database Migration (`db/migration_fix_specializations.sql`)
Execute this SQL migration to:
1. Remove corrupted specializations (numeric names)
2. Add missing specializations (IDs 7-20)
3. Clean up any customer-specialization links to corrupted entries

**To apply the migration:**
```bash
mysql -u root med_epharma < db/migration_fix_specializations.sql
```

Or in phpMyAdmin:
1. Copy the content of `migration_fix_specializations.sql`
2. Go to phpMyAdmin → med_epharma database → SQL tab
3. Paste and execute

## How to Test

### Before fix:
- Physicians showed specialties as numbers: "15, 8, 9"

### After fix:
1. Run the migration SQL
2. Navigate to doctor selection page (`/view/select_doctor.php`)
3. Physicians should now show proper specialty names: "Radiology, Orthopedics, Neurology"

## Files Modified
1. `controllers/customer_controller.php` - Fixed registration logic
2. `db/migration_fix_specializations.sql` - Created migration script

## Database Schema
The database now properly supports:
- `specializations` table: Contains 20 medical specialization definitions (IDs 1-20)
- `customer_specializations` table: Links physicians to their specializations
- Proper JOIN queries in `customer_class.php` retrieve specialty names correctly
