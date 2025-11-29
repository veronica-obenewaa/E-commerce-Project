# How to Apply the Specialization Fix

## Summary
The issue was that physician specializations were showing as numbers instead of names because:
1. The database didn't have all specialization definitions (only had 6, needed 20)
2. The registration code was creating specializations with numeric names

## Quick Fix Steps

### Step 1: Code Fix (Already Applied ✓)
The controller code in `controllers/customer_controller.php` has been updated to properly handle specialization IDs.

### Step 2: Database Fix (YOU NEED TO DO THIS)

You have two options:

#### Option A: Using phpMyAdmin (Recommended for beginners)
1. Open phpMyAdmin in your browser
2. Select the `med_epharma` database
3. Click on the "SQL" tab
4. Copy-paste the contents of `db/migration_fix_specializations.sql`
5. Click "Go" to execute

#### Option B: Using MySQL Command Line
```bash
mysql -u root med_epharma < db/migration_fix_specializations.sql
```

### Step 3: Verify the Fix
1. Go to the "Select Doctor" page in your application
2. Check if physician specialties now show names like "Cardiology", "Orthopedics" instead of "15, 8, 9"
3. If doctors' profiles show correct specialty names, the fix worked!

## What the Migration Does
- Removes broken specialization entries with numeric names
- Adds missing specializations (Surgery, Orthopedics, Neurology, Radiology, etc.)
- Cleans up broken links between physicians and specializations
- Syncs the database with the `medical_specializations.json` file

## Result
After applying the fix:
- ✓ Physicians' specialties display correctly
- ✓ New physician registrations work properly
- ✓ Database is synced with the application's specialization list
