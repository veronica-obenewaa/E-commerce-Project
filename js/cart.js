// js/cart.js
document.addEventListener('DOMContentLoaded', () => {
  const addUrl = '../actions/add_to_cart_action.php';
  const updateUrl = '../actions/update_quantity_action.php';
  const removeUrl = '../actions/remove_from_cart_action.php';
  const emptyUrl = '../actions/empty_cart_action.php';

  // Helper: display bootstrap messages
  function showMsg(container, text, type = 'info') {
    container.innerHTML = `<div class="alert alert-${type} mt-2 p-2">${text}</div>`;
    setTimeout(() => container.innerHTML = '', 3000);
  }

  // Handle Add to Cart button clicks dynamically
  document.body.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-add-to-cart')) {
      const btn = e.target;
      const productId = btn.dataset.productId;
      const qty = btn.dataset.qty ? parseInt(btn.dataset.qty) : 1;

      if (!productId) return;

      fetch(addUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ p_id: productId, qty })
      })
        .then(r => r.json())
        .then(j => {
          const container = document.getElementById(`cartMsg-${productId}`) || document.getElementById('cartMsg') || document.body;


          if (j.status === 'success') {
            showMsg(container, '' + j.message, 'success');
            // 
            if (typeof refreshCartUI === 'function') refreshCartUI();
          } else if (j.status === 'login_required') {
            showMsg(container, 'Please log in to add items to your cart.', 'warning');
            setTimeout(() => window.location.href = '../Login/login.php', 1500);
          } else {
            showMsg(container, '' + j.message, 'danger');
          }
        })
        .catch(() => {
          const container = document.getElementById('cartMsg') || document.body;
          showMsg(container, 'Network error adding to cart.', 'danger');
        });
    }
  });

  // Update cart quantity (used in cart.php)
  window.updateCartQty = (productId, qty, callback) => {
    fetch(updateUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ product_id: productId, qty })
    })
      .then(r => r.json())
      .then(j => callback && callback(j))
      .catch(() => callback && callback({ status: 'error', message: 'Network error' }));
  };

  // Remove item from cart
  window.removeCartItem = (productId, callback) => {
    fetch(removeUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ p_id: productId })
    })
      .then(r => r.json())
      .then(j => callback && callback(j))
      .catch(() => callback && callback({ status: 'error', message: 'Network error' }));
  };

  // Empty entire cart
  window.emptyCart = (callback) => {
    fetch(emptyUrl, { method: 'POST' })
      .then(r => r.json())
      .then(j => callback && callback(j))
      .catch(() => callback && callback({ status: 'error', message: 'Network error' }));
  };
});
