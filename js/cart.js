// js/cart.js
document.addEventListener('DOMContentLoaded', () => {
  const addUrl = '../actions/add_to_cart_action.php';
  const updateUrl = '../actions/update_quantity_action.php';
  const removeUrl = '../actions/remove_from_cart_action.php';
  const emptyUrl = '../actions/empty_cart_action.php';
  //const fetchCartUrl = '../actions/get_cart_action.php'; 

  function showMsg(container, text, type='info') {
    container.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
    setTimeout(()=> container.innerHTML='', 3000);
  }

  // Attach add-to-cart clicks 
  document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const pid = btn.dataset.productId;
      const qty = btn.dataset.qty ? parseInt(btn.dataset.qty) : 1;
      fetch(addUrl, {
        method: 'POST',
        body: new URLSearchParams({ product_id: pid, qty })
      }).then(r=>r.json()).then(j=>{
        if (j.status === 'success') {
          // show small toast or update cart count
          const container = document.getElementById('cartMsg') || document.body;
          showMsg(container, j.message, 'success');
          //refresh cart UI
          refreshCartUI && refreshCartUI();
        } else {
          showMsg(document.getElementById('cartMsg')||document.body, j.message, 'danger');
        }
      }).catch(()=> showMsg(document.getElementById('cartMsg')||document.body,'Error adding to cart','danger'));
    });
  });

  // Generic functions to update quantity or remove action can be wired in cart.php render.
  window.updateCartQty = (productId, qty, callback) => {
    fetch(updateUrl, { method:'POST', body: new URLSearchParams({ product_id: productId, qty })})
      .then(r=>r.json()).then(j => {
        callback && callback(j);
      }).catch(()=> callback && callback({status:'error',message:'Network error'}));
  };

  window.removeCartItem = (productId, callback) => {
    fetch(removeUrl, { method:'POST', body: new URLSearchParams({ product_id: productId })})
      .then(r=>r.json()).then(j => callback && callback(j))
      .catch(()=> callback && callback({status:'error',message:'Network error'}));
  };

  window.emptyCart = (callback) => {
    fetch(emptyUrl, { method:'POST' })
      .then(r=>r.json()).then(j => callback && callback(j))
      .catch(()=> callback && callback({status:'error',message:'Network error'})); 
  };

});
