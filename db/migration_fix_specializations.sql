-- Migration: Fix corrupted specialization data and sync with JSON
-- This script:
-- 1. Removes specializations with purely numeric names (corrupted entries)
-- 2. Adds missing specializations from the JSON file (IDs 7-20)

-- Step 1: Delete customer_specializations records linked to corrupted specializations
DELETE FROM customer_specializations 
WHERE specialization_id IN (
    SELECT id FROM specializations WHERE name REGEXP '^[0-9]+$'
);

-- Step 2: Delete the corrupted specialization entries
DELETE FROM specializations WHERE name REGEXP '^[0-9]+$';

-- Step 3: Add missing specializations (IDs 7-20)
INSERT IGNORE INTO specializations (id, name) VALUES
(7, 'Surgery'),
(8, 'Orthopedics'),
(9, 'Neurology'),
(10, 'Ophthalmology'),
(11, 'ENT (Otolaryngology)'),
(12, 'Gastroenterology'),
(13, 'Urology'),
(14, 'Oncology'),
(15, 'Radiology'),
(16, 'Pathology'),
(17, 'Anesthesiology'),
(18, 'Rheumatology'),
(19, 'Endocrinology'),
(20, 'Pulmonology');

-- Step 4: Verify the fix
SELECT 'All specializations after migration:' as action;
SELECT id, name FROM specializations ORDER BY id;

-- Step 5: Show any remaining customer specializations
SELECT 'Customer specializations:' as action;
SELECT cs.customer_id, cs.specialization_id, s.name, c.customer_name 
FROM customer_specializations cs 
LEFT JOIN specializations s ON cs.specialization_id = s.id 
LEFT JOIN customer c ON cs.customer_id = c.customer_id
ORDER BY c.customer_name;
