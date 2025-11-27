# Paystack Payment Response - Debug Guide

## Understanding the JSON Response

Since error logging is not available on the server, all debugging information is now included in the JSON response under the `"debug"` field.

## Example Success Response

```json
{
  "status": "success",
  "verified": true,
  "message": "Payment successful! Order confirmed.",
  "order_id": 123,
  "invoice_no": "INV-20251127-ABC123",
  "total_amount": "150.00",
  "currency": "GHS",
  "item_count": 3,
  "payment_reference": "Med-ePharma-11-1764265057",
  "debug": {
    "customer_id": 5,
    "session_active": true,
    "cart_fetched_from_db": true,
    "cart_items_count": 3,
    "calculated_total": 150.00,
    "final_total_amount": 150.00,
    "order_id": 123,
    "payment_id": 456,
    "cart_emptied": true
  }
}
```

## Example Error Response

```json
{
  "status": "error",
  "verified": false,
  "message": "Cart is empty. Cannot create order without items.",
  "debug": {
    "customer_id": 5,
    "session_active": true,
    "reference": "Med-ePharma-11-1764265057",
    "cart_items_from_client": null,
    "total_amount_from_client": 0,
    "cart_fetched_from_db": true,
    "cart_items_count": 0,
    "cart_status": "EMPTY"
  }
}
```

## Reading the Debug Info

### Key Debug Fields

| Field | Meaning | Example |
|-------|---------|---------|
| `customer_id` | Customer logged in | `5` |
| `session_active` | User has valid session | `true` |
| `reference` | Paystack reference | `"Med-ePharma-11-1764265057"` |
| `cart_items_from_client` | Items sent by frontend | `null` or `[...]` |
| `total_amount_from_client` | Total sent by frontend | `0` or `150.00` |
| `cart_fetched_from_db` | Backend fetched cart | `true` |
| `cart_items_count` | Number of items found | `3` or `0` |
| `calculated_total` | Total calculated from items | `150.00` |
| `final_total_amount` | Final amount used for verification | `150.00` |
| `order_id` | Created order ID | `123` |
| `payment_id` | Created payment record ID | `456` |
| `cart_emptied` | Cart was cleared | `true` |
| `order_details_added` | Items added to order | `[{"product_id": 1, "quantity": 2}]` |
| `error_details` | Error message on failure | `"Failed to create order..."` |

## Common Issues & How to Read Them

### Issue 1: Empty Cart
**Response:**
```json
{
  "status": "error",
  "message": "Cart is empty. Cannot create order without items.",
  "debug": {
    "cart_items_count": 0,
    "cart_status": "EMPTY"
  }
}
```
**Fix:** Add items to cart before payment

### Issue 2: Session Expired
**Response:**
```json
{
  "status": "error",
  "message": "Session expired. Please login again.",
  "debug": {
    "session_active": false
  }
}
```
**Fix:** Log in again and add items to cart

### Issue 3: Amount Mismatch
**Response:**
```json
{
  "status": "error",
  "message": "Payment amount does not match order total",
  "debug": {
    "amount_mismatch": {
      "expected": 150.00,
      "paid": 100.00,
      "difference": 50.00
    }
  }
}
```
**Fix:** Verify cart total matches payment amount

### Issue 4: Order Creation Failed
**Response:**
```json
{
  "status": "error",
  "message": "Payment processing error: Failed to create order in database",
  "debug": {
    "order_creation_result": false,
    "error_details": "Failed to create order in database"
  }
}
```
**Fix:** Check database connection and customer_id validity

## How to Use This For Troubleshooting

1. **Get the full JSON response** from your payment verification endpoint
2. **Look at the "debug" section**
3. **Match your debug info** against the examples above
4. **Identify the issue** based on which field has the problem
5. **Apply the suggested fix**

## Tips for Debugging

- Always check `customer_id` is not null
- Verify `session_active` is true
- Make sure `cart_items_count` is > 0
- Check that `calculated_total` matches what you expect
- If `order_creation_failed` is true, there's a database issue

