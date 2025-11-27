# Order Creation Error Diagnosis

## What Changed

The `order_class.php` methods now throw detailed exceptions instead of silently returning `false`. This means when order creation fails, you'll get a specific error message telling you exactly what went wrong.

## Common Error Messages You Might See

### 1. "Database connection failed"
- **Cause**: Cannot connect to MySQL database
- **Fix**: 
  - Check `settings/db_cred.php` has correct credentials
  - Verify MySQL server is running
  - Check if database name is correct

### 2. "Statement prepare failed: [MySQL error]"
- **Cause**: The SQL query has a syntax error or references a non-existent table/column
- **Fix**:
  - Check that `orders` table exists
  - Verify column names: `customer_id`, `invoice_no`, `order_date`, `order_status`
  - Check database schema with: `CHECK_SCHEMA.php`

### 3. "Bind parameters failed: [MySQL error]"
- **Cause**: Parameter types don't match (e.g., passing string to integer parameter)
- **Fix**:
  - Ensure `customer_id` is an integer
  - Ensure `invoice_no`, `order_date`, `order_status` are strings
  - This is usually auto-handled, but can fail if data types are weird

### 4. "Execute failed: [MySQL error]"
- **Cause**: INSERT statement failed (often due to constraints)
- **Common reasons**:
  - **Foreign key error**: `customer_id` doesn't exist in `customer` table
  - **Duplicate key**: `invoice_no` already exists (shouldn't happen with random generation)
  - **Column too small**: Data is too long for the column
  - **Default value missing**: A required field is NULL
  
### 5. "Insert succeeded but no insert_id returned"
- **Cause**: MySQL inserted but didn't return the auto-increment ID
- **Fix**: Rarely happens; usually indicates table schema issue

## Example Error Response

When the order creation now fails, you'll see:

```json
{
  "status": "error",
  "verified": false,
  "message": "Payment processing error: Execute failed: Cannot add or update a child row: a foreign key constraint fails (`database`.`orders`, CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE RESTRICT)",
  "debug": {
    "customer_id": 11,
    "order_creation_result": false,
    "error_details": "Execute failed: Cannot add or update a child row...",
    "transaction_rolled_back": true
  }
}
```

## Troubleshooting Steps

1. **Copy the error message** from `"error_details"` in the debug section
2. **Read the MySQL error** - it usually tells you exactly what's wrong
3. **Check the specific issue**:
   - Foreign key? → Verify customer exists
   - Table not found? → Check database schema
   - Column doesn't exist? → Check table columns
   - Data type mismatch? → Check data being passed

4. **Use diagnostic tools**:
   - `check_db_schema.php` - See table structure
   - `debug_cart_paystack.php` - See cart data being used

## Database Schema Check

Run this to verify your orders table:

```sql
DESCRIBE orders;
```

Should show:
- `order_id` - INT AUTO_INCREMENT PRIMARY KEY
- `customer_id` - INT (should have FOREIGN KEY to customer table)
- `invoice_no` - VARCHAR
- `order_date` - DATE
- `order_status` - VARCHAR

