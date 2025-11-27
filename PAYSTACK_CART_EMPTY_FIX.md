# Paystack Payment Verification - Cart Empty Issue

## The Problem

Your payment verification script is failing because the cart is empty when it tries to create an order:

```
Payload sent: {"reference":"Med-ePharma-11-1764265057","cart_items":null,"total_amount":null}
Error: "Failed to create order in database"
```

The issue is: **Your cart has no items** when the payment verification script runs.

## Root Causes

1. **Cart is truly empty** - No items were added before checkout
2. **Cart items have `c_id = NULL`** - Items weren't properly associated with your customer ID
3. **Different session/customer ID** - The customer ID changed between adding items and verification

## Diagnostic Steps

### Step 1: Check Your Cart
Visit: `http://localhost/mvc_skeleton_template/debug_cart_paystack.php`

This will show:
- ✅ How many items are in your cart
- ❌ If your cart is empty
- ⚠️ If there are orphaned items (NULL customer ID)
- 📊 Total items in database

### Step 2: Manual Verification

1. **Add items to cart first**
   - Browse products
   - Add at least one item to cart
   - Verify cart count increases

2. **Check that items are saved**
   - Refresh the page
   - Items should still be there

3. **Try checkout again**
   - Proceed to Paystack payment
   - Complete payment
   - Payment verification should now work

### Step 3: Verify Session

The payment verification needs:
- ✅ Active login session (`$_SESSION['customer_id']`)
- ✅ Matching customer ID between add-to-cart and checkout
- ✅ Items in cart with correct `c_id`

## How the Flow Should Work

```
1. User adds item to cart
   → INSERT INTO cart (p_id, c_id, qty) VALUES (...)
   
2. User goes to checkout
   → Cart retrieved with c_id = customer_id
   
3. User pays via Paystack
   → Payment sent back to verification script
   
4. Verification script:
   → Gets customer_id from session
   → Fetches cart items WHERE c_id = customer_id
   → Creates order with those items
   → Empties cart
```

## Changes Made to Fix This

1. **Better debugging in `paystack_verify_payment.php`**:
   - Logs when cart is fetched
   - Shows how many items found
   - Rejects payment if cart is empty (prevents orderless orders)
   
2. **New diagnostic tool**: `debug_cart_paystack.php`
   - Shows your current cart status
   - Identifies NULL c_id issues
   - Helps diagnose session problems

## Quick Fixes

### If cart is empty:
1. Add items to cart first
2. Verify they appear in the cart page
3. Then proceed to checkout

### If items have NULL c_id:
```sql
-- THIS WILL DELETE ORPHANED ITEMS - Be careful!
DELETE FROM cart WHERE c_id IS NULL;

-- Then add items again to your cart
```

### If session is not persisting:
1. Make sure browser accepts cookies
2. Check that you're logged in
3. Try clearing browser cache and logging in again

## Testing the Fix

1. Login to your account
2. Visit: `http://localhost/mvc_skeleton_template/debug_cart_paystack.php`
3. Check the cart status
4. If empty, add items first
5. Try payment again

## Files Created/Modified

- **Created**: `debug_cart_paystack.php` - Cart debugging tool
- **Modified**: `paystack_verify_payment.php` - Better error handling and logging

## Support

If still having issues:
1. Check the debug output from `debug_cart_paystack.php`
2. Look for error logs in `C:\xampp\apache\logs\error.log`
3. Check your PHP error log (usually in project root as `php_errors.log`)
4. Verify database connection credentials

