// js/product.js
document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addProductForm');
    const bulkForm = document.getElementById('bulkUploadForm');
    const productListEl = document.getElementById('productList');

    function showMsg(el, type, text) {
        if (!el) return;
        el.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
        setTimeout(()=> el.innerHTML = '', 4000);
    }

    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            // default form submit will post to add_product_action.php and return JSON
            e.preventDefault();
            const fd = new FormData(addForm);
            fetch(addForm.action, { method: 'POST', body: fd })
            .then(r => {
                // Check for HTTP errors
                if (!r.ok) {
                    // Try to parse JSON error response, fall back to status text
                    return r.text().then(text => {
                        let errorMsg = 'HTTP ' + r.status + ' ' + r.statusText;
                        try {
                            const json = JSON.parse(text);
                            if (json.message) errorMsg = json.message;
                        } catch (e) {
                            // Not JSON, use the status text
                            errorMsg = text || errorMsg;
                        }
                        throw new Error(errorMsg);
                    });
                }
                return r.json();
            })
            .then(j => {
                showMsg(document.getElementById('addMsg'), j.status === 'success' ? 'success' : 'danger', j.message || 'Response');
                if (j.status === 'success') {
                    addForm.reset();
                    setTimeout(()=> window.location.href = '../admin/product.php', 900);
                }
            })
            .catch(err => {
                showMsg(document.getElementById('addMsg'), 'danger', 'Error: ' + (err.message || 'Unable to connect to server'));
                console.error('Add product error:', err);
            });
        });
    }

    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(bulkForm);
            showMsg(document.getElementById('bulkMsg'), 'info', 'Uploading...');
            fetch(bulkForm.action, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(document.getElementById('bulkMsg'), 'success', j.message);
                    if (j.errors && j.errors.length) {
                        const errhtml = j.errors.slice(0,10).map(x=>`<div>${x}</div>`).join('');
                        document.getElementById('bulkMsg').innerHTML += errhtml;
                    }
                    setTimeout(()=> window.location.href = '../admin/product.php', 1200);
                } else {
                    showMsg(document.getElementById('bulkMsg'), 'danger', j.message || 'Bulk upload failed');
                }
            })
            .catch(err => {
                showMsg(document.getElementById('bulkMsg'), 'danger', 'Error uploading ZIP');
                console.error(err);
            });
        });
    }

    // fetch and render products on admin/product.php
    async function fetchProducts() {
        if (!productListEl) return;
        try {
            const res = await fetch('../actions/fetch_product_action.php');
            const j = await res.json();
            if (j.status !== 'success') {
                productListEl.innerHTML = '<div class="text-danger">Failed to load products</div>';
                return;
            }
            const rows = j.data;
            if (!rows.length) { productListEl.innerHTML = '<div class="text-muted">No products yet.</div>'; return; }
            let html = '';
            rows.forEach(p => {
                html += `
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="../${p.product_image || 'images/no-image.png'}" class="img-fluid rounded-start" alt="${escapeHtml(p.product_title)}">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">${escapeHtml(p.product_title)}</h5>
                                <p class="card-text">${escapeHtml(p.product_desc || '')}</p>
                                <p class="card-text"><small>₵ ${p.product_price} • ${escapeHtml(p.brand_name || '')} • ${escapeHtml(p.cat_name || '')}</small></p>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div>
                                <button class="btn btn-sm btn-outline-primary mb-2" data-id=${p.product_id}" data-name="${p.product_name}" onclick="openEdit(this)">Edit</button>
                                <button class="btn btn-sm btn-outline-danger" data-id="${p.product_id}" onclick="doDelete(this)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            productListEl.innerHTML = html;
        } catch (e) {
            productListEl.innerHTML = '<div class="text-danger">Error loading products</div>';
            console.error(e);
        }
    }

    //function to delete a product
    window.doDelete = function(btn) {
        if (!confirm('Delete this product?')) return;
        const id = btn.dataset.id;
        fetch('../actions/delete_product_action.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'product_id=' + encodeURIComponent(id)
        }).then(r=>r.json()).then(j=>{
            if (j.status === 'success') fetchProducts();
            else alert(j.message || 'Delete failed');
        }).catch(()=> alert('Error deleting'));
    };

    function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    if (productListEl) fetchProducts();
});
