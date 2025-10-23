document.addEventListener('DOMContentLoaded', function() {
    const fetchUrl = '../actions/fetch_brand_action.php';
    const addUrl = '../actions/add_brand_action.php';
    const updateUrl = '../actions/update_brand_action.php';
    const deleteUrl = '../actions/delete_brand_action.php';

    const listEl = document.getElementById('brandList');
    const addForm = document.getElementById('addBrandForm');
    const updateForm = document.getElementById('updateBrandForm');

    function showMsg(container, type, text) {
        container.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
        setTimeout(() => container.innerHTML = '', 3000);
    }

    function fetchBrands() {
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
                        <button class="btn btn-sm btn-outline-primary me-2" data-id="${r.brand_id}" data-name="${r.brand_name}" onclick="openEdit(this)">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" data-id="${r.brand_id}" onclick="doDelete(this)">Delete</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            listEl.innerHTML = html;
        })
        .catch(() => listEl.innerHTML = '<div class="text-danger">Error loading brands</div>');
    }

    if (addForm) {
        addForm.addEventListener('submit', e => {
            e.preventDefault();
            const msg = document.getElementById('addMsg');
            const brand_name = addForm.querySelector('input[name="brand_name"]').value.trim();
            if (!brand_name) return showMsg(msg, 'danger', 'Brand name required');
            const fd = new FormData();
            fd.append('brand_name', brand_name);

            fetch(addUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    addForm.reset();
                    fetchBrands();
                } else showMsg(msg, 'danger', j.message);
            })
            .catch(() => showMsg(msg, 'danger', 'Error'));
        });
    }

    window.openEdit = btn => {
        document.getElementById('update_brand_id').value = btn.dataset.id;
        document.getElementById('update_brand_name').value = btn.dataset.name;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('updateModal')).show();
    };

    if (updateForm) {
        updateForm.addEventListener('submit', e => {
            e.preventDefault();
            const msg = document.getElementById('updateMsg');
            const fd = new FormData(updateForm);

            fetch(updateUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
                    fetchBrands();
                } else showMsg(msg, 'danger', j.message);
            })
            .catch(() => showMsg(msg, 'danger', 'Error'));
        });
    }

    window.doDelete = btn => {
        if (!confirm('Delete this brand?')) return;
        const fd = new FormData();
        fd.append('brand_id', btn.dataset.id);
        fetch(deleteUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(j => {
            if (j.status === 'success') fetchBrands();
            else alert(j.message);
        })
        .catch(() => alert('Error deleting brand'));
    };

    fetchBrands();
});
