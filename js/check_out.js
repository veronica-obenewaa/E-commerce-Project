// js/checkout.js
document.addEventListener('DOMContentLoaded', () => {
  const processUrl = '../actions/process_checkout_action.php';
  const checkoutBtn = document.getElementById('confirmPaymentBtn');
  if (!checkoutBtn) return;

  checkoutBtn.addEventListener('click', () => {
    // Show a loading state
    checkoutBtn.disabled = true;
    checkoutBtn.textContent = 'Processing...';

    fetch(processUrl, { method: 'POST' })
      .then(r => r.json())
      .then(j => {
        checkoutBtn.disabled = false;
        checkoutBtn.textContent = 'Confirm Payment';
        if (j.status === 'success') {
          // show success modal / redirect to success page
          alert('Payment simulated. Order ref: ' + j.invoice_no);
          window.location.href = '../view/payment_success.php?order_id=' + encodeURIComponent(j.order_id);
        } else {
          alert('Checkout failed: ' + (j.message || 'Unknown'));
        }
      })
      .catch(err => {
        checkoutBtn.disabled = false;
        checkoutBtn.textContent = 'Confirm Payment';
        alert('Error processing checkout');
        console.error(err);
      });
  });
});
