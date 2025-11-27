# Fixing "Failed to create order in database" Error

## The Issue
Your Paystack payment verification is failing at the order creation step with:
```json
{
    "status": "error",
    "verified": false,
    "message": "Payment processing error: Failed to create order in database"
}
```

## Diagnosis Steps

### 1. **Check Database Schema** 
Visit: `http://localhost/mvc_skeleton_template/check_db_schema.php`

Verify that:
- `orders` table exists with columns: `order_id` (INT, AUTO_INCREMENT), `customer_id` (INT), `invoice_no` (VARCHAR), `order_date` (DATE), `order_status` (VARCHAR)
- `payment` table exists
- `orderdetails` table exists

### 2. **Test Order Creation**
Visit: `http://localhost/mvc_skeleton_template/test_order_creation.php`

This will test:
- Direct order creation via `order_class.php`
- Order creation via controller function
- Database connection status
- Recent error logs

### 3. **Check PHP Error Logs**
Look for errors in:
- `C:\xampp\apache\logs\error.log`
- `C:\xampp\apache\logs\access.log`
- Your project's `php_errors.log` if it exists

## Common Issues & Fixes

### Issue 1: Prepared Statement Binding Error
**Symptom**: "Bind param failed" in logs
**Fix**: Ensure customer_id is an integer
✅ Already fixed in the updated `order_class.php`

### Issue 2: Missing Table or Column
**Symptom**: "Table doesn't exist" or "Unknown column" error
**Fix**: Check database schema and ensure tables are created

### Issue 3: Foreign Key Constraint
**Symptom**: "Foreign key constraint fails"
**Fix**: Ensure customer_id exists in the `customer` table

### Issue 4: Database Connection Issue
**Symptom**: "Connection failed" in logs
**Fix**: Check `settings/db_cred.php` has correct credentials

## Changes Made

1. **Updated `paystack_verify_payment.php`**:
   - Added `require_once '../controllers/order_controller.php';` at the top
   - Improved error logging with more details

2. **Enhanced `order_class.php` `create_order()` method**:
   - Added type logging to help debug issues
   - Better error handling for bind_param
   - More detailed error messages

## Next Steps

1. Run the diagnostic scripts above
2. Check error logs for specific MySQL errors
3. Verify database tables and columns exist
4. Ensure `customer_id` in orders table matches a valid customer

## Debug Tips

If still having issues:
1. Check if `customer_id` is NULL - it shouldn't be
2. Verify the customer actually exists in the database
3. Check if there are any database triggers that might be failing
4. Look at raw error messages in PHP error log

## Files Created for Debugging
- `test_order_creation.php` - Tests order creation with detailed output
- `check_db_schema.php` - Displays database schema for all order-related tables
