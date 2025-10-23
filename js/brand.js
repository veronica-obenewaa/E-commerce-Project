document.addEventListener('DOMContentLoaded', function() {
    const fetchUrl = '../actions/fetch_brand_action.php';
    const addUrl = '../actions/add_brand_action.php';
    const updateUrl = '../actions/update_brand_action.php';
    const deleteUrl = '../actions/delete_brand_action.php';

    const listEl = document.getElementById('brandList');
    const addForm = document.getElementById('addBrandForm');
    const updateForm = document.getElementById('updateBrandForm');

    //Show message in alert boxes
    function showMsg(container, type, text) {
        container.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
        setTimeout(() => container.innerHTML = '', 3000);
    }

    //Fetch all brands
    function fetchBrands() {
        if (!listEl) return; 
        fetch(fetchUrl)
        .then(r => r.json())
        .then(json => {
            if (json.status !== 'success') {
                listEl.innerHTML = '<div class="text-danger">Failed to load brands</div>';
                return;
            }

            const rows = json.data;
            if (!rows.length) {
                listEl.innerHTML = '<div class="text-muted">No brands yet</div>';
                return;
            }

            let html = '<ul class="list-group">';
            rows.forEach(r => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${r.brand_name}</span>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" 
                                data-id="${r.brand_id}" 
                                data-name="${escapeHtml(r.brand_name)}" 
                                onclick="openEdit(this)">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" 
                                data-id="${r.brand_id}" 
                                onclick="doDelete(this)">Delete</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            listEl.innerHTML = html;
        })
        .catch(err => {
            listEl.innerHTML = '<div class="text-danger">Error loading brands</div>';
            console.error(err);
        });
    }

    //Escape text safely
    function escapeHtml(str) {
        return String(str)
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    // ADD BRAND
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = document.getElementById('addMsg');
            const name = addForm.querySelector('input[name="brand_name"]').value.trim();
            if (!name) return showMsg(msg, 'danger', 'Brand name is required');

            const fd = new FormData();
            fd.append('brand_name', name);

            fetch(addUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    addForm.reset();
                    // Redirect to brand list page after success
                    setTimeout(() => {
                        window.location.href = '../admin/brand.php';
                    }, 1200);
                } else {
                    showMsg(msg, 'danger', j.message);
                }
            })
            .catch(() => showMsg(msg, 'danger', 'Error adding brand'));
        });
    }

    //OPEN EDIT MODAL
    window.openEdit = function(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        document.getElementById('update_brand_id').value = id;
        document.getElementById('update_brand_name').value = name;

        const modalEl = document.getElementById('updateModal');
        if (modalEl) {
            const bs = bootstrap.Modal.getOrCreateInstance(modalEl);
            bs.show();
        }
    };

    //UPDATE BRAND
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = updateForm.querySelector('input[name="brand_id"]').value;
            const name = updateForm.querySelector('input[name="brand_name"]').value.trim();
            const msg = document.getElementById('updateMsg');
            if (!id || !name) return showMsg(msg, 'danger', 'Invalid data');

            const fd = new FormData();
            fd.append('brand_id', id);
            fd.append('brand_name', name);

            fetch(updateUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    fetchBrands();
                    const modalEl = document.getElementById('updateModal');
                    if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();
                } else {
                    showMsg(msg, 'danger', j.message);
                }
            })
            .catch(() => showMsg(msg, 'danger', 'Error updating brand'));
        });
    }

    //DELETE BRAND
    window.doDelete = function(btn) {
        if (!confirm('Delete this brand?')) return;
        const id = btn.getAttribute('data-id');
        const fd = new FormData();
        fd.append('brand_id', id);

        fetch(deleteUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(j => {
            if (j.status === 'success') fetchBrands();
            else alert(j.message || 'Delete failed');
        })
        .catch(() => alert('Error deleting brand'));
    };

    //INITIAL LOAD (only on brand.php page)
    if (listEl) fetchBrands();
});
