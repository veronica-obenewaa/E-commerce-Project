// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize checkout with server-side rendered data
    initializeCheckout();
});

/**
 * Initialize checkout with data from server
 */
function initializeCheckout() {
    console.log('Initializing checkout...');
    console.log('Cart items:', window.currentCartItems);
    console.log('Total:', window.checkoutTotal);
    
    if (window.currentCartItems && window.checkoutTotal !== undefined) {
        console.log('Checkout data loaded successfully');
        // Data is already rendered server-side
        // Just prepare the payment total for the modal
        window.checkoutTotal = parseFloat(window.checkoutTotal);
    } else {
        console.error('Checkout data not found');
        const container = document.getElementById('checkoutItemsContainer');
        if (container) {
            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc2626;">Error: Cart data not available</div>';
        }
    }
}

/**
 * Display checkout items
 */
function displayCheckoutItems(items, total) {
    const container = document.getElementById('checkoutItemsContainer');
    container.innerHTML = '';
    
    items.forEach((item, index) => {
        const imagePath = item.product_image ? 
            (item.product_image.startsWith('../') ? item.product_image : '../' + item.product_image) :
            'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="60" height="60"%3E%3Crect width="60" height="60" fill="%23fef2f2"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="10" fill="%23dc2626"%3ENo Image%3C/text%3E%3C/svg%3E';
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'checkout-item';
        itemDiv.style.cssText = 'display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #f3f4f6; align-items: center;';
        
        itemDiv.innerHTML = `
            <img src="${escapeHtml(imagePath)}" 
                 alt="${escapeHtml(item.product_title)}" 
                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; flex-shrink: 0;"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22%3E%3Crect width=%2260%22 height=%2260%22 fill=%22%23fef2f2%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2210%22 fill=%22%23dc2626%22%3ENo Image%3C/text%3E%3C/svg%3E'">
            <div style="flex: 1;">
                <div style="font-weight: 600; margin-bottom: 4px;">${escapeHtml(item.product_title)}</div>
                <div style="font-size: 13px; color: #6b7280;">Qty: ${item.qty} × GHS ${parseFloat(item.product_price).toFixed(2)}</div>
            </div>
            <div style="font-weight: 700; color: #dc2626; font-size: 1.1rem;">
                GHS ${parseFloat(item.subtotal).toFixed(2)}
            </div>
        `;
        
        container.appendChild(itemDiv);
    });
    
    const totalElement = document.getElementById('checkoutTotal');
    if (totalElement) {
        totalElement.textContent = `GHS ${parseFloat(total).toFixed(2)}`;
    }
    
    // Store total for payment modal
    window.checkoutTotal = parseFloat(total).toFixed(2);
}

/**
 * Show payment modal
 */
function showPaymentModal() {
    const modal = document.getElementById('paymentModal');
    const amountDisplay = document.getElementById('paymentAmount');
    
    amountDisplay.textContent = `GHS ${window.checkoutTotal || '0.00'}`;
    
    modal.style.display = 'flex';
    
    // Add animation
    setTimeout(() => {
        modal.style.opacity = '1';
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
        }
    }, 10);
}

/**
 * Close payment modal
 */
function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.style.opacity = '0';
    
    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.style.transform = 'scale(0.9)';
    }
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

/**
 * Process checkout via Paystack
 */
function processCheckout() {
    const confirmBtn = document.getElementById('confirmPaymentBtn');
    const originalText = confirmBtn.textContent;
    
    // Disable button and show loading
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Redirecting to Paystack...';
    
    // Get customer email from session or prompt
    const customerEmail = prompt('Please enter your email for payment:', '');
    
    if (!customerEmail) {
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
        showToast('Email is required for payment', 'error');
        return;
    }
    
    // Get selected delivery service from session storage (set in cart.php)
    const selectedDelivery = sessionStorage.getItem('selectedDelivery') || 'pickup';
    
    // Step 1: Initialize Paystack transaction
    fetch('../actions/paystack_init_transaction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            amount: window.checkoutTotal,
            email: customerEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Paystack init response:', data);
        
        if (data.status === 'success') {
            // Store data for verification after payment
            window.paymentReference = data.reference;
            window.cartItems = window.currentCartItems || null;
            window.totalAmount = window.checkoutTotal;
            
            // Ensure selected delivery service is stored in session storage
            sessionStorage.setItem('selectedDelivery', selectedDelivery);
            
            // Redirect to Paystack payment page
            closePaymentModal();
            showToast('Redirecting to secure payment...', 'success');
            
            setTimeout(() => {
                window.location.href = data.authorization_url;
            }, 1500);
        } else {
            showToast(data.message || 'Failed to initialize payment', 'error');
            confirmBtn.disabled = false;
            confirmBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Payment initialization failed. Please try again.', 'error');
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
    });
}

/**
 * Show success modal with order details
 */
function showSuccessModal(orderData) {
    const modal = document.getElementById('successModal');
    
    // Populate success modal with order details
    document.getElementById('successInvoice').textContent = orderData.invoice_no;
    document.getElementById('successAmount').textContent = `GHS ${orderData.total_amount}`;
    document.getElementById('successDate').textContent = orderData.order_date;
    document.getElementById('successItems').textContent = orderData.item_count;
    
    modal.style.display = 'flex';
    
    // Add animation
    setTimeout(() => {
        modal.style.opacity = '1';
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
        }
    }, 10);
    
    // Update cart count
    updateCartCount(0);
    
    // Confetti effect (optional)
    createConfetti();
}

/**
 * Continue shopping after successful order
 */
function continueShopping() {
    window.location.href = '../view/all_product.php';
}

/**
 * View orders after successful checkout
 */
function viewOrders() {
    window.location.href = '../view/orders.php';
}

/**
 * Create confetti effect for successful order
 */
function createConfetti() {
    const colors = ['#dc2626', '#ef4444', '#f97316', '#fbbf24'];
    const confettiCount = 50;
    
    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * 100}%;
                top: -10px;
                opacity: 1;
                transform: rotate(${Math.random() * 360}deg);
                z-index: 10001;
                pointer-events: none;
            `;
            
            document.body.appendChild(confetti);
            
            const duration = 2000 + Math.random() * 1000;
            const startTime = Date.now();
            
            function animateConfetti() {
                const elapsed = Date.now() - startTime;
                const progress = elapsed / duration;
                
                if (progress < 1) {
                    const top = progress * (window.innerHeight + 50);
                    const wobble = Math.sin(progress * 10) * 50;
                    
                    confetti.style.top = top + 'px';
                    confetti.style.left = `calc(${confetti.style.left} + ${wobble}px)`;
                    confetti.style.opacity = 1 - progress;
                    confetti.style.transform = `rotate(${progress * 720}deg)`;
                    
                    requestAnimationFrame(animateConfetti);
                } else {
                    confetti.remove();
                }
            }
            
            animateConfetti();
        }, i * 30);
    }
}

/**
 * Update cart count badge
 */
function updateCartCount(count) {
    const badges = document.querySelectorAll('.cart-count-badge');
    badges.forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    });
}

/**
 * Show toast notification
 */
function showToast(message, type) {
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.padding = '16px 24px';
    toast.style.borderRadius = '8px';
    toast.style.fontWeight = '600';
    toast.style.fontSize = '14px';
    toast.style.zIndex = '10000';
    toast.style.maxWidth = '400px';
    toast.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
    toast.style.animation = 'slideIn 0.3s ease';
    
    if (type === 'success') {
        toast.style.backgroundColor = '#d1fae5';
        toast.style.color = '#065f46';
        toast.style.border = '2px solid #6ee7b7';
    } else {
        toast.style.backgroundColor = '#fee2e2';
        toast.style.color = '#991b1b';
        toast.style.border = '2px solid #fecaca';
    }
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/**
 * Escape HTML
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modals when clicking outside
window.onclick = function(event) {
    const paymentModal = document.getElementById('paymentModal');
    if (event.target === paymentModal) {
        closePaymentModal();
    }
};