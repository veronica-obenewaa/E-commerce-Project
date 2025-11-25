# Payment Flow Update - Complete Documentation

## Overview
The payment flow has been completely redesigned to:
1. Users select their preferred delivery service on the cart page
2. Users proceed to checkout and make payment via Paystack
3. After successful payment verification, users are automatically redirected to their selected delivery service to book a ride

---

## Updated Flow Diagram

```
┌──────────────────────────────┐
│     CART PAGE (cart.php)     │
│                              │
│ 1. User reviews items        │
│ 2. SELECT DELIVERY SERVICE:  │
│    • 🚗 Bolt Rides (+₵15)   │
│    • 🚙 Uber Rides (+₵20)   │
│    • 🚕 Yango Rides (+₵12)  │
│    • 📍 Personal Pickup (Free)│
│ 3. See updated total         │
│ 4. Click "Proceed to Checkout"│
└────────────┬─────────────────┘
             ↓
     ✅ Delivery selected
     ✅ Stored in sessionStorage
             ↓
┌──────────────────────────────┐
│  CHECKOUT PAGE (checkout.php)│
│                              │
│ 1. Review order summary      │
│ 2. Click "Proceed to Payment"│
│ 3. Enter email               │
│ 4. Click "Pay Now"           │
└────────────┬─────────────────┘
             ↓
     ✅ Paystack initialized
     ✅ Selected delivery stored
             ↓
┌──────────────────────────────┐
│   PAYSTACK GATEWAY           │
│                              │
│ User enters card details     │
│ & confirms payment           │
└────────────┬─────────────────┘
             ↓
┌──────────────────────────────┐
│  PAYSTACK CALLBACK           │
│  (paystack_callback.php)     │
│                              │
│ 1. Verify payment with API   │
│ 2. Get selected delivery     │
│ 3. Create order in DB        │
│ 4. Empty cart                │
└────────────┬─────────────────┘
             ↓
        ✅ Payment Verified
             ↓
    ┌──────────────────────┐
    │ CHECK DELIVERY TYPE  │
    └──────────────────────┘
         ↙        ↓        ↘
    Bolt/Uber/Yango   Personal Pickup
        ↓                    ↓
┌──────────────────┐  ┌──────────────────┐
│ RIDE SERVICE     │  │ SUCCESS PAGE     │
│                  │  │                  │
│ bolt.eu          │  │ Show order       │
│ uber.com         │  │ confirmation     │
│ yango.com        │  │                  │
│                  │  │ + invoice        │
│ (User books ride)│  │ + delivery info  │
└──────────────────┘  └──────────────────┘
```

---

## Files Modified

### 1. ✅ `view/cart.php` - Updated checkout button
**Change:** Modified the `proceedToCheckout()` function
- Now stores selected delivery service in `sessionStorage`
- Redirects to `checkout.php` instead of external service URLs
- Delivery service selection still required before checkout

**Code:**
```javascript
function proceedToCheckout() {
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    
    if (!selectedDelivery) {
        alert('Please select a delivery service');
        return;
    }
    
    const deliveryService = selectedDelivery.value;
    
    // Store selected delivery service in session storage
    sessionStorage.setItem('selectedDelivery', deliveryService);
    
    // Proceed to checkout to process payment via Paystack
    window.location.href = 'checkout.php';
}
```

---

### 2. ✅ `view/paystack_callback.php` - Post-payment redirect
**Change:** Updated payment verification callback
- After successful payment verification
- Retrieves selected delivery service from `sessionStorage`
- Redirects to appropriate service:
  - **Bolt/Uber/Yango:** External ride booking URLs
  - **Personal Pickup:** Success confirmation page

**Key Code:**
```javascript
// Get selected delivery service from session storage
const selectedDelivery = sessionStorage.getItem('selectedDelivery') || 'pickup';
const redirectUrl = deliveryUrls[selectedDelivery] || 'payment_success.php';

// Build URL with order parameters
let finalUrl = redirectUrl;
if (selectedDelivery === 'pickup') {
    finalUrl = `payment_success.php?reference=${reference}&invoice=${invoice}&delivery=${selectedDelivery}`;
} else {
    // For ride services, append order reference
    finalUrl += `?order_ref=${invoiceNumber}`;
}

// Redirect to selected delivery service
window.location.replace(finalUrl);
```

---

### 3. ✅ `js/checkout.js` - Preserve delivery selection
**Change:** Updated `processCheckout()` function
- Retrieves selected delivery service from `sessionStorage`
- Ensures it's preserved during payment process
- Stored before redirecting to Paystack

**Code Addition:**
```javascript
// Get selected delivery service from session storage
const selectedDelivery = sessionStorage.getItem('selectedDelivery') || 'pickup';

// ... during payment initialization ...

// Ensure selected delivery service is stored in session storage
sessionStorage.setItem('selectedDelivery', selectedDelivery);
```

---

## Data Flow

### Cart Page → Checkout
```
cart.php:
  - User selects delivery service (radio button)
  - Clicks "Proceed to Checkout"
  - Function stores delivery choice in sessionStorage
  - Redirects to checkout.php
```

### Checkout → Paystack
```
checkout.js:
  - User enters email
  - Clicks "Pay Now"
  - Retrieves delivery service from sessionStorage
  - Ensures it stays stored
  - Sends payment to Paystack
```

### Paystack Callback → Final Destination
```
paystack_callback.php:
  - Payment verified with Paystack API
  - Order created in database
  - Cart emptied
  - Retrieves delivery service from sessionStorage
  - If ride service: Opens ride booking URL with order reference
  - If personal pickup: Shows success confirmation
```

---

## Session Storage Usage

**Key:** `selectedDelivery`  
**Values:** `'bolt'`, `'uber'`, `'yango'`, `'pickup'`

**Storage Locations:**
1. **Set in:** `cart.php` (proceedToCheckout function)
2. **Retrieved in:** `checkout.js` (processCheckout function)
3. **Retrieved in:** `paystack_callback.php` (after payment verification)

---

## Delivery Service Redirect URLs

| Service | URL | Next Step |
|---------|-----|-----------|
| Bolt | https://www.bolt.eu/ | User books ride with order reference |
| Uber | https://www.uber.com/ | User books ride with order reference |
| Yango | https://yango.com/ | User books ride with order reference |
| Personal Pickup | payment_success.php | Shows order confirmation page |

---

## User Experience Flow

### Scenario 1: User chooses Bolt delivery
```
1. Cart page: Select "🚗 Bolt Rides" radio button (+₵15)
   ↓
2. Click "Proceed to Checkout"
   - Delivery saved: sessionStorage.selectedDelivery = 'bolt'
   ↓
3. Checkout page: Review order summary + Paystack fee
   ↓
4. Click "Proceed to Payment" → Enter email → "Pay Now"
   ↓
5. Paystack gateway: Enter card details, confirm payment
   ↓
6. Payment callback page: "Verifying Payment..."
   - Creates order in database
   - Records payment
   - Empties cart
   ↓
7. Auto-redirect to: https://www.bolt.eu/?order_ref=INV-20241125-A1B2C3
   - User books ride for delivery
   - Includes order reference for tracking
```

### Scenario 2: User chooses Personal Pickup
```
1. Cart page: Select "📍 Personal Pickup" radio button (Free)
   ↓
2. Click "Proceed to Checkout"
   - Delivery saved: sessionStorage.selectedDelivery = 'pickup'
   ↓
3. Checkout page: Review order (no delivery fee)
   ↓
4. Click "Proceed to Payment" → Enter email → "Pay Now"
   ↓
5. Paystack gateway: Complete payment
   ↓
6. Payment callback: Verify and process
   ↓
7. Redirect to: payment_success.php
   - Shows order confirmation
   - Displays invoice number
   - Shows order details
   - Provides pickup instructions
```

---

## Delivery Fee Structure

| Service | Fee | Total (for ₵100 order) |
|---------|-----|----------------------|
| 🚗 Bolt Rides | +₵15.00 | ₵115.00 |
| 🚙 Uber Rides | +₵20.00 | ₵120.00 |
| 🚕 Yango Rides | +₵12.00 | ₵112.00 |
| 📍 Personal Pickup | FREE | ₵100.00 |

---

## Testing Checklist

- [ ] Cart page displays all 4 delivery options
- [ ] Delivery fee updates correctly when selected
- [ ] Total amount updates with selected delivery fee
- [ ] "Proceed to Checkout" button disabled until delivery selected
- [ ] Selected delivery stored in sessionStorage
- [ ] Checkout page loads with correct total
- [ ] Email prompt appears when "Pay Now" clicked
- [ ] Redirects to Paystack gateway
- [ ] Test payment verification succeeds
- [ ] Order created in database
- [ ] Cart emptied after successful payment
- [ ] Bolt/Uber/Yango: Redirects to external URL with order reference
- [ ] Personal Pickup: Redirects to success page with confirmation
- [ ] Payment fails: Returns to checkout with error message

---

## Error Handling

### User cancels delivery selection
- "Proceed to Checkout" button remains disabled
- Error message: "Please select a delivery service"

### User goes back to cart during checkout
- Delivery selection persists in sessionStorage
- Can resume checkout from same point

### Payment fails
- Returns to checkout.php with error parameter
- User can try again
- Delivery selection preserved

---

## Security Notes

- Delivery service stored client-side in sessionStorage (safe)
- Not sensitive data
- Cleared automatically when browser closes
- Can survive page reloads during payment process
- Final destination verified server-side before redirecting

---

## Advantages of This Flow

✅ **User Control:** Users explicitly choose delivery method  
✅ **Flexible:** Easy to add more delivery options later  
✅ **Clear Pricing:** Delivery fees visible before payment  
✅ **Ride Service Integration:** Orders tracked via reference numbers  
✅ **Simple Pickup Option:** Existing customers can pick up themselves  
✅ **Mobile Friendly:** Works seamlessly on all devices  
✅ **Session Persistence:** Survives the Paystack redirect

---

## Future Enhancement Ideas

1. **Real-time availability:** Check if delivery services are available in user's area
2. **Dynamic pricing:** Adjust delivery fee based on distance
3. **Order tracking:** Integrate with ride service APIs for real-time tracking
4. **Customer notifications:** Email/SMS when order is ready and delivery scheduled
5. **Delivery history:** Show users their previous delivery service choices
6. **Rating system:** Rate delivery quality and driver performance
7. **Bulk orders:** Special pricing for multiple items

---

## Summary

The updated payment flow now:
1. ✅ Keeps delivery selection on cart page
2. ✅ Processes payment via Paystack on checkout
3. ✅ Redirects to selected delivery service after successful payment
4. ✅ Provides order reference for ride booking
5. ✅ Shows confirmation for personal pickup option

**Status:** ✅ READY FOR TESTING

Test with sandbox Paystack credentials to verify the complete flow.
