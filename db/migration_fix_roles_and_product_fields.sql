-- Migration: Fix user roles mapping and convert product fields to text
-- Date: 2025-11-18
-- Description:
-- 1. Fix user role mapping: 1 = pharmaceutical company, 2 = customer, 3 = physician
-- 2. Convert product_cat and product_brand from INT to VARCHAR to store category and brand names directly

-- =========================================
-- Step 1: Update existing user_role values to match new mapping
-- =========================================
-- Note: You may need to adjust these based on your current data
-- This assumes:
--   Current role 1 (admin) → New role 1 (pharmaceutical company)
--   Current role 2 (customer) → Stays role 2 (customer)
--   Current role 3 (physician) → Stays role 3 (physician)

-- If your current data has different mappings, update the WHERE clauses accordingly.
-- Example: If current admins should become companies, adjust the UPDATE statement.

-- =========================================
-- Step 2: Convert products table to use text for category and brand
-- =========================================

-- Backup original columns (optional but recommended)
-- ALTER TABLE products ADD COLUMN product_cat_old INT;
-- ALTER TABLE products ADD COLUMN product_brand_old INT;
-- UPDATE products SET product_cat_old = product_cat, product_brand_old = product_brand;
-- UPDATE products SET product_brand_old = product_brand, product_brand_old = product_brand;

-- Convert product_cat and product_brand to VARCHAR(100)
ALTER TABLE products 
MODIFY COLUMN product_cat VARCHAR(100) NOT NULL COMMENT 'Category name (text)';

ALTER TABLE products 
MODIFY COLUMN product_brand VARCHAR(100) NOT NULL COMMENT 'Brand name (text)';

-- Drop the old foreign key indexes if they exist
-- ALTER TABLE products DROP FOREIGN KEY products_ibfk_1;  -- adjust if needed
-- ALTER TABLE products DROP FOREIGN KEY products_ibfk_2;  -- adjust if needed

-- =========================================
-- Step 3: Add additional fields if migrating from old schema
-- =========================================

-- Ensure products table has created_by and timestamps (if not present)
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS created_by INT,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- =========================================
-- Step 4: Verify the schema
-- =========================================
-- SELECT * FROM products LIMIT 1;
-- SELECT * FROM customer LIMIT 1;
