# PAYSTACK INTEGRATION - TECHNICAL REFERENCE

## Complete API Integration Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                          PAYMENT FLOW DIAGRAM                        │
└─────────────────────────────────────────────────────────────────────┘

CLIENT SIDE                          SERVER SIDE                PAYSTACK API
─────────────────────────────────────────────────────────────────────
     │                                    │                           │
     │ 1. POST /actions/                  │                           │
     │    paystack_init_transaction.php  │                           │
     │──────────────────────────────────>│                           │
     │                                    │ 2. Verify user logged in  │
     │                                    │    with isLoggedIn()      │
     │                                    │                           │
     │                                    │ 3. Call API               │
     │                                    │    paystack_initialize_   │
     │                                    │    transaction()          │
     │                                    │──────────────────────────>│
     │                                    │                           │
     │                                    │ 4. Return auth URL        │
     │                                    │<──────────────────────────│
     │ 5. JSON response with URL         │                           │
     │<──────────────────────────────────│                           │
     │                                    │                           │
     │ 6. Redirect to Paystack          │                           │
     │────────────────────────────────────────────────────────────>│
     │                                    │                           │
     │ 7. User enters payment details    │                           │
     │    and confirms                    │                           │
     │                                    │                           │
     │ 8. Paystack returns to callback   │                           │
     │<────────────────────────────────────────────────────────────│
     │                                    │                           │
     │ 9. Submit reference to backend   │                           │
     │    POST /actions/                │                           │
     │    paystack_verify_payment.php   │                           │
     │──────────────────────────────────>│                           │
     │                                    │ 10. Call Paystack API     │
     │                                    │     to verify             │
     │                                    │──────────────────────────>│
     │                                    │                           │
     │                                    │ 11. Get transaction       │
     │                                    │     details               │
     │                                    │<──────────────────────────│
     │                                    │                           │
     │                                    │ 12. Validate amount       │
     │                                    │     & status              │
     │                                    │                           │
     │                                    │ 13. Create order          │
     │                                    │ 14. Add order details     │
     │                                    │ 15. Record payment        │
     │                                    │ 16. Empty cart            │
     │                                    │ 17. Commit transaction    │
     │                                    │                           │
     │ 18. JSON: success response       │                           │
     │<──────────────────────────────────│                           │
     │                                    │                           │
     │ 19. Show success page             │                           │
     │────────────────────────────────>│                           │
     │                                    │                           │
```

---

## Database Schema Reference

### Orders Table
```sql
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    invoice_no VARCHAR(50) UNIQUE,
    order_date DATE,
    order_status VARCHAR(20),  -- 'Pending', 'Paid', 'Shipped', etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
);
```

### Order Details Table
```sql
CREATE TABLE orderdetails (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
```

### Payments Table (Optional - for detailed payment tracking)
```sql
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    amount DECIMAL(10, 2),
    currency VARCHAR(3),
    payment_method VARCHAR(50),  -- 'paystack', 'bank_transfer', etc.
    transaction_ref VARCHAR(100),  -- Reference from Paystack
    authorization_code VARCHAR(100),
    payment_channel VARCHAR(50),  -- 'card', 'mobile_money', etc.
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
```

### Cart Table
```sql
CREATE TABLE cart (
    c_id INT NOT NULL,        -- customer_id
    p_id INT NOT NULL,        -- product_id
    qty INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (c_id, p_id),
    FOREIGN KEY (c_id) REFERENCES customer(customer_id),
    FOREIGN KEY (p_id) REFERENCES products(product_id)
);
```

---

## Function Call Chain

### 1. Payment Initialization
```
checkout.php
    └─> paystack_init_transaction.php (AJAX)
        ├─> isLoggedIn() [core.php]
        ├─> getUserId() [core.php]
        └─> paystack_initialize_transaction() [paystack_config.php]
            └─> API: POST https://api.paystack.co/transaction/initialize
```

### 2. Callback Handling
```
paystack_callback.php
    ├─> isLoggedIn() [core.php]
    └─> JavaScript fetch paystack_verify_payment.php
```

### 3. Payment Verification & Order Creation
```
paystack_verify_payment.php
    ├─> isLoggedIn() [core.php]
    ├─> getUserId() [core.php]
    ├─> paystack_verify_transaction() [paystack_config.php]
    │   └─> API: GET https://api.paystack.co/transaction/verify/{reference}
    ├─> new cart_controller() [cart_controller.php]
    ├─> $cart_controller->get_user_cart_ctr() 
    │   └─> cart_class->getCartItems() [cart_class.php]
    ├─> create_order_ctr() [order_controller.php]
    │   └─> order_class->create_order() [order_class.php]
    ├─> add_order_details_ctr() [order_controller.php]
    │   └─> order_class->add_order_details() [order_class.php]
    ├─> record_payment_ctr() [order_controller.php]
    │   └─> order_class->record_payment() [order_class.php]
    ├─> $cart_controller->empty_cart_ctr()
    │   └─> cart_class->emptyCart() [cart_class.php]
    └─> Return: JSON success response
```

### 4. Success Display
```
payment_success.php
    ├─> isLoggedIn() [core.php]
    ├─> getUserId() [core.php]
    └─> Display order details
```

---

## Data Flow Example

### Cart Items Structure
**Returned by:** `getCartItems()`
**From:** cart table + products table JOIN
```php
Array (
    [0] => Array (
        'p_id' => 1,                          // From: cart.p_id
        'qty' => 2,                           // From: cart.qty
        'product_title' => 'Aspirin Pack',    // From: products.product_title
        'product_price' => 9.99,              // From: products.product_price
        'product_image' => 'aspirin.jpg'      // From: products.product_image
    ),
    [1] => Array (
        'p_id' => 3,
        'qty' => 1,
        'product_title' => 'Vitamin C',
        'product_price' => 5.50,
        'product_image' => 'vitamin-c.jpg'
    )
)
```

### Paystack Transaction Response
**From:** Paystack API
```php
Array (
    'status' => true,
    'message' => 'Authorization URL created',
    'data' => Array (
        'authorization_url' => 'https://checkout.paystack.com/...',
        'access_code' => 'xxxxxxxx',
        'reference' => 'xxxxxxxxxxxxx'
    )
)
```

### Paystack Verification Response
**From:** Paystack API (verify endpoint)
```php
Array (
    'status' => true,
    'message' => 'Verification successful',
    'data' => Array (
        'id' => 123456789,
        'reference' => 'xxxxxxxxxxxxx',
        'amount' => 1599,                  // Amount in pesewas (÷100 for GHS)
        'paid_at' => '2024-11-25T10:30:00.000Z',
        'status' => 'success',
        'customer' => Array (
            'email' => 'user@example.com',
            'id' => 987654
        ),
        'authorization' => Array (
            'authorization_code' => 'AUTH_xxxxxxxxxxxxx',
            'channel' => 'card',
            'last_four' => '1111'
        )
    )
)
```

### Our Success JSON Response
**To:** Frontend
```php
Array (
    'status' => 'success',
    'verified' => true,
    'message' => 'Payment successful! Order confirmed.',
    'order_id' => 42,
    'invoice_no' => 'INV-20241125-A1B2C3',
    'total_amount' => '15.99',
    'currency' => 'GHS',
    'order_date' => 'November 25, 2024',
    'item_count' => 2,
    'payment_reference' => 'xxxxxxxxxxxxx',
    'payment_method' => 'card',
    'customer_email' => 'user@example.com'
)
```

---

## Error Response Examples

### 1. User Not Logged In
```json
{
    "status": "error",
    "message": "Session expired. Please login again."
}
```

### 2. Invalid Amount
```json
{
    "status": "error",
    "verified": false,
    "message": "Payment amount does not match order total",
    "expected": "15.99",
    "paid": "20.00"
}
```

### 3. Payment Not Successful
```json
{
    "status": "error",
    "verified": false,
    "message": "Payment was not successful. Status: pending",
    "payment_status": "pending"
}
```

### 4. Database Error
```json
{
    "status": "error",
    "verified": false,
    "message": "Payment processing error: Failed to create order in database"
}
```

---

## Environment Variables

### Test Environment
```php
define('APP_ENVIRONMENT', 'test');
define('PAYSTACK_SECRET_KEY', 'sk_test_xxxx...');
define('PAYSTACK_PUBLIC_KEY', 'pk_test_xxxx...');
```

**Test Card Numbers:**
- Success: `4111 1111 1111 1111`
- Decline: `4000 0000 0000 0002`
- Invalid Card: `5425 2334 3010 9903`

**Test OTP:** `123456`

### Production Environment
```php
define('APP_ENVIRONMENT', 'live');
define('PAYSTACK_SECRET_KEY', 'sk_live_xxxx...');
define('PAYSTACK_PUBLIC_KEY', 'pk_live_xxxx...');
```

---

## Security Implementation

### 1. Authentication
- All endpoints check `isLoggedIn()`
- Session must be active
- Customer ID validated

### 2. Amount Validation
```php
// Server calculates total from cart
$calculated_total = sum(price * qty) for all items;

// Compare with Paystack
if (abs($amount_paid - $total_amount) > 0.01) {
    REJECT;  // More than 1 pesewa difference
}
```

### 3. Database Transactions
```php
mysqli_begin_transaction($conn);
try {
    // Create order
    // Add details
    // Record payment
    // Empty cart
    mysqli_commit($conn);  // All succeed together
} catch (Exception $e) {
    mysqli_rollback($conn);  // All roll back together
}
```

### 4. API Key Protection
- Secret key NEVER sent to frontend
- Secret key stored in server-side config only
- Public key safe for frontend (read-only)
- All verification happens server-side

---

## Testing Checklist

### Unit Tests (Manual)
- [ ] `isLoggedIn()` correctly identifies logged-in users
- [ ] `getUserId()` returns correct customer ID
- [ ] `new cart_controller()` instantiates properly
- [ ] `get_user_cart_ctr()` returns cart items with correct structure

### Integration Tests (Manual)
- [ ] User can add items to cart
- [ ] Checkout calculates correct total
- [ ] Paystack payment gateway loads
- [ ] Payment verification returns success
- [ ] Order created in database with correct data
- [ ] Payment recorded with correct amount
- [ ] Cart emptied after successful payment

### Scenario Tests
- [ ] Successful payment → Order created
- [ ] Failed payment → Order NOT created
- [ ] Amount mismatch → Payment rejected
- [ ] User session expires → Redirected to login
- [ ] Empty cart → Cannot checkout

---

## Debugging Tips

### View Error Logs
```bash
tail -f error_log
# Look for lines containing "PAYSTACK" or "Payment"
```

### Check Database Records
```sql
-- Last order created
SELECT * FROM orders ORDER BY order_id DESC LIMIT 1;

-- Details for specific order
SELECT * FROM orderdetails WHERE order_id = 42;

-- Payment records
SELECT * FROM payments WHERE order_id = 42;

-- Verify cart is empty
SELECT * FROM cart WHERE c_id = 5;
```

### Test Paystack API Directly
```bash
curl -H "Authorization: Bearer sk_test_xxxxx" \
     https://api.paystack.co/transaction/verify/REFERENCE
```

### Monitor Payment Flow
```
1. Check error_log for "PAYSTACK CALLBACK/VERIFICATION"
2. Look for "Verifying Paystack transaction - Reference: xxx"
3. Look for "Order created - ID: xxx"
4. Look for "Payment recorded - ID: xxx"
5. Look for "Cart emptied for customer: xxx"
```

---

## Deployment Checklist

- [ ] All files uploaded to correct directories
- [ ] Paystack config updated with production keys
- [ ] Database tables created and permissions set
- [ ] Error logs writable and monitored
- [ ] SSL/HTTPS enabled on domain
- [ ] Test payment completed successfully
- [ ] Production payment completed successfully
- [ ] Customer records verified in database
- [ ] Support team trained on process

---

**Technical Documentation Complete** ✅  
Last Updated: November 25, 2025
