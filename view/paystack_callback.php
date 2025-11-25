<?php
/**
 * Paystack Payment Callback Handler
 * This page is called after Paystack payment process
 * User is redirected here by Paystack after payment
 */

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login/login.php');
    exit();
}

// Get reference from URL
$reference = isset($_GET['reference']) ? trim($_GET['reference']) : null;

if (!$reference) {
    // Payment cancelled or reference missing
    header('Location: checkout.php?error=cancelled');
    exit();
}

error_log("=== PAYSTACK CALLBACK PAGE ===");
error_log("Reference from URL: $reference");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - Med-ePharma</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        
        .container { max-width: 500px; width: 90%; background: white; padding: 60px 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1); text-align: center; }
        
        .spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #059669;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 30px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        h1 { font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: #1a1a1a; margin-bottom: 15px; }
        p { color: #6b7280; font-size: 16px; line-height: 1.6; margin-bottom: 20px; }
        
        .reference { background: #f9fafb; padding: 15px; border-radius: 8px; margin: 25px 0; word-break: break-all; font-family: monospace; font-size: 12px; color: #6b7280; }
        
        .error { color: #991b1b; background: #fee2e2; border: 2px solid #fecaca; padding: 15px; border-radius: 8px; margin: 20px 0; display: none; }
        .success { color: #065f46; background: #d1fae5; border: 2px solid #6ee7b7; padding: 15px; border-radius: 8px; margin: 20px 0; display: none; }
        
        .button-group { display: flex; gap: 12px; margin-top: 30px; flex-direction: column; }
        .btn { padding: 14px 24px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; width: 100%; }
        .btn-primary { background: linear-gradient(135deg, #059669 0%, #059669 100%); color: white; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4); }
        .btn-secondary { background: white; color: #374151; border: 2px solid #e5e7eb; }
        .btn-secondary:hover { background: #f9fafb; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner" id="spinner"></div>
        
        <h1 id="mainTitle">Verifying Payment</h1>
        <p id="mainText">Please wait while we verify your payment with Paystack...</p>
        
        <div class="reference">
            Payment Reference: <strong><?php echo htmlspecialchars($reference); ?></strong>
        </div>
        
        <div class="error" id="errorBox">
            <strong>Error:</strong> <span id="errorMessage"></span>
        </div>
        
        <div class="success" id="successBox" style="display: none;">
            <strong>✅ Success!</strong> Your payment has been verified and your order is confirmed.
        </div>
        
        <div class="button-group" id="buttonGroup" style="display: none;">
            <button class="btn btn-primary" id="proceedBtn" onclick="proceedToDelivery()">
                Proceed to Next Step
            </button>
            <button class="btn btn-secondary" onclick="window.location.href='../index.php'">
                Back to Home
            </button>
        </div>
    </div>

    <script>
        /**
         * Get delivery service URLs
         */
        const deliveryUrls = {
            'bolt': 'https://www.bolt.eu/',
            'uber': 'https://www.uber.com/',
            'yango': 'https://yango.com/',
            'pickup': 'payment_success.php'  // For personal pickup, show success page
        };
        
        let redirectUrl = null;

        /**
         * Verify payment with backend
         */
        async function verifyPayment() {
            const reference = '<?php echo htmlspecialchars($reference); ?>';
            
            try {
                const response = await fetch('../actions/paystack_verify_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        reference: reference,
                        cart_items: null,
                        total_amount: null
                    })
                });
                
                const data = await response.json();
                console.log('Verification response:', data);
                
                // Hide spinner
                document.getElementById('spinner').style.display = 'none';
                
                if (data.status === 'success' && data.verified) {
                    // Payment verified successfully
                    document.getElementById('successBox').style.display = 'block';
                    document.getElementById('mainTitle').textContent = '✅ Payment Successful!';
                    document.getElementById('mainText').textContent = 'Your payment has been verified. Click the button below to proceed.';
                    
                    // Get selected delivery service from response
                    const selectedDelivery = data.delivery_service || sessionStorage.getItem('selectedDelivery') || 'pickup';
                    const baseUrl = deliveryUrls[selectedDelivery] || 'payment_success.php';
                    
                    // Build URL with order parameters
                    if (selectedDelivery === 'pickup') {
                        redirectUrl = `payment_success.php?reference=${encodeURIComponent(data.payment_reference)}&invoice=${encodeURIComponent(data.invoice_no)}&delivery=${selectedDelivery}`;
                    } else {
                        redirectUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + `order_ref=${encodeURIComponent(data.invoice_no)}`;
                    }
                    
                    console.log('Redirect URL set to:', redirectUrl);
                    
                    // Show button
                    document.getElementById('buttonGroup').style.display = 'flex';
                    
                    // Update button text based on delivery service
                    const btnText = getButtonText(selectedDelivery);
                    document.getElementById('proceedBtn').innerHTML = btnText;
                    
                } else {
                    // Payment verification failed
                    const errorMsg = data.message || 'Payment verification failed';
                    showError(errorMsg);
                    
                    // Show back button
                    document.getElementById('buttonGroup').style.display = 'flex';
                    document.getElementById('proceedBtn').textContent = 'Retry Payment';
                    document.getElementById('proceedBtn').onclick = function() {
                        window.location.href = 'checkout.php';
                    };
                }
                
            } catch (error) {
                console.error('Verification error:', error);
                showError('Connection error. Please try again or contact support.');
                
                // Show button
                document.getElementById('buttonGroup').style.display = 'flex';
                document.getElementById('proceedBtn').textContent = 'Back to Checkout';
                document.getElementById('proceedBtn').onclick = function() {
                    window.location.href = 'checkout.php';
                };
            }
        }
        
        /**
         * Get button text based on delivery service
         */
        function getButtonText(service) {
            const texts = {
                'bolt': '🚗 Book with Bolt',
                'uber': '🚙 Book with Uber',
                'yango': '🚕 Book with Yango',
                'pickup': '✅ View Order Confirmation'
            };
            return texts[service] || '➡️ Continue';
        }
        
        /**
         * Proceed to delivery service when button clicked
         */
        function proceedToDelivery() {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            } else {
                console.error('No redirect URL set');
            }
        }
        
        /**
         * Show error message
         */
        function showError(message) {
            document.getElementById('errorBox').style.display = 'block';
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('mainTitle').textContent = '❌ Payment Verification Failed';
            document.getElementById('mainText').textContent = '';
        }
        
        // Start verification when page loads
        window.addEventListener('load', verifyPayment);
    </script>
</body>
</html>
