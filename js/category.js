// document.addEventListener('DOMContentLoaded', function() {
//     const fetchUrl = '/mvc_skeleton_template/actions/fetch_category_action.php';
//     const addUrl = '/mvc_skeleton_template/actions/add_category_action.php';
//     const updateUrl = '/mvc_skeleton_template/actions/update_category_action.php';
//     const deleteUrl = '/mvc_skeleton_template/actions/delete_category_action.php';

//     const listEl = document.getElementById('categoryList');
//     const addForm = document.getElementById('addCategoryForm');
//     const updateForm = document.getElementById('updateCategoryForm');

//     function showMsg(container, type, text) {
//         container.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
//         setTimeout(()=> container.innerHTML = '', 3000);
//     }

//     function fetchCategories() {
//         fetch(fetchUrl)
//         .then(r => r.json())
//         .then(json => {
//             if (json.status !== 'success') {
//                 listEl.innerHTML = '<div class="text-danger">Failed to load categories</div>';
//                 return;
//             }
//             const rows = json.data;
//             if (!rows.length) {
//                 listEl.innerHTML = '<div class="text-muted">No categories yet</div>';
//                 return;
//             }
//             let html = '<ul class="list-group">';
//             rows.forEach(r => {
//                 html += `<li class="list-group-item d-flex justify-content-between align-items-center">
//                     <span>${r.category_name}</span>
//                     <div>
//                         <button class="btn btn-sm btn-outline-primary me-2" data-id="${r.category_id}" data-name="${escapeHtml(r.category_name)}" onclick="openEdit(this)">Edit</button>
//                         <button class="btn btn-sm btn-outline-danger" data-id="${r.category_id}" onclick="doDelete(this)">Delete</button>
//                     </div>
//                 </li>`;
//             });
//             html += '</ul>';
//             listEl.innerHTML = html;
//         })
//         .catch(err => {
//             listEl.innerHTML = '<div class="text-danger">Error loading categories</div>';
//             console.error(err);
//         });
//     }

//     // escape for attribute insertion
//     function escapeHtml(str) {
//         return String(str).replace(/"/g, '&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
//     }

//     // add
//     if (addForm) {
//         addForm.addEventListener('submit', function(e) {
//             e.preventDefault();
//             const name = addForm.querySelector('input[name="category_name"]').value.trim();
//             const msg = document.getElementById('addMsg');
//             if (!name) return showMsg(msg, 'danger', 'Category name is required');
//             const fd = new FormData();
//             fd.append('category_name', name);

//             fetch(addUrl, { method: 'POST', body: fd })
//             .then(r => r.json())
//             .then(j => {
//                 if (j.status === 'success') {
//                     showMsg(msg, 'success', j.message);
//                     addForm.reset();
//                     fetchCategories();
//                 } else showMsg(msg, 'danger', j.message);
//             })
//             .catch(err => showMsg(msg, 'danger', 'Error'));
//         });
//     }

//     // expose edit functions to window to be callable from buttons
//     window.openEdit = function(btn) {
//         const id = btn.getAttribute('data-id');
//         const name = btn.getAttribute('data-name');
//         document.getElementById('update_category_id').value = id;
//         document.getElementById('update_category_name').value = name;
//         // show modal if using bootstrap modal
//         const modalEl = document.getElementById('updateModal');
//         if (modalEl) {
//             const bs = bootstrap.Modal.getOrCreateInstance(modalEl);
//             bs.show();
//         }
//     };

//     // update
//     if (updateForm) {
//         updateForm.addEventListener('submit', function(e) {
//             e.preventDefault();
//             const id = updateForm.querySelector('input[name="category_id"]').value;
//             const name = updateForm.querySelector('input[name="category_name"]').value.trim();
//             const msg = document.getElementById('updateMsg');
//             if (!id || !name) return showMsg(msg, 'danger', 'Invalid data');
//             const fd = new FormData();
//             fd.append('category_id', id);
//             fd.append('category_name', name);

//             fetch(updateUrl, { method: 'POST', body: fd })
//             .then(r => r.json())
//             .then(j => {
//                 if (j.status === 'success') {
//                     showMsg(msg, 'success', j.message);
//                     fetchCategories();
//                     const modalEl = document.getElementById('updateModal');
//                     if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();
//                 } else showMsg(msg, 'danger', j.message);
//             }).catch(err => showMsg(msg, 'danger', 'Error'));
//         });
//     }

//     // delete
//     window.doDelete = function(btn) {
//         if (!confirm('Delete this category?')) return;
//         const id = btn.getAttribute('data-id');
//         const fd = new FormData();
//         fd.append('category_id', id);
//         fetch(deleteUrl, { method: 'POST', body: fd })
//         .then(r => r.json())
//         .then(j => {
//             if (j.status === 'success') fetchCategories();
//             else alert(j.message || 'Delete failed');
//         }).catch(err => alert('Error deleting'));
//     };

//     // initial load
//     fetchCategories();
// });

document.addEventListener('DOMContentLoaded', function() {
    const fetchUrl = '/mvc_skeleton_template/actions/fetch_category_action.php';
    const addUrl = '/mvc_skeleton_template/actions/add_category_action.php';
    const updateUrl = '/mvc_skeleton_template/actions/update_category_action.php';
    const deleteUrl = '/mvc_skeleton_template/actions/delete_category_action.php';

    const listEl = document.getElementById('categoryList');
    const addForm = document.getElementById('addCategoryForm');
    const updateForm = document.getElementById('updateCategoryForm');

    function showMsg(container, type, text) {
        container.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
        setTimeout(()=> container.innerHTML = '', 3000);
    }

    function fetchCategories() {
        if (!listEl) return; 
        fetch(fetchUrl)
        .then(r => r.json())
        .then(json => {
            if (json.status !== 'success') {
                listEl.innerHTML = '<div class="text-danger">Failed to load categories</div>';
                return;
            }
            const rows = json.data;
            if (!rows.length) {
                listEl.innerHTML = '<div class="text-muted">No categories yet</div>';
                return;
            }
            let html = '<ul class="list-group">';
            rows.forEach(r => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${r.cat_name}</span>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" 
                                data-id="${r.cat_id}" 
                                data-name="${escapeHtml(r.cat_name)}" 
                                onclick="openEdit(this)">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" 
                                data-id="${r.cat_id}" 
                                onclick="doDelete(this)">Delete</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            listEl.innerHTML = html;
        })
        .catch(err => {
            listEl.innerHTML = '<div class="text-danger">Error loading categories</div>';
            console.error(err);
        });
    }

    // escape for attribute insertion
    function escapeHtml(str) {
        return String(str).replace(/"/g, '&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // ADD CATEGORY 
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = addForm.querySelector('input[name="cat_name"]').value.trim();
            const msg = document.getElementById('addMsg');
            if (!name) return showMsg(msg, 'danger', 'Category name is required');

            const fd = new FormData();
            fd.append('cat_name', name);

            fetch(addUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    addForm.reset();

                    // if we are on category_add.php → redirect to category.php
                    if (window.location.pathname.includes('/mvc_skeleton_template/admin/category_add.php')) {
                        setTimeout(() => {
                            window.location.href = '/mvc_skeleton_template/admin/category.php';
                        }, 1200);
                    } else {
                        // if on category.php just refresh the list
                        fetchCategories();
                    }
                } else {
                    showMsg(msg, 'danger', j.message);
                }
            })
            .catch(() => showMsg(msg, 'danger', 'Error adding category'));
        });
    }

    // expose edit functions to window to be callable from buttons
    window.openEdit = function(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        document.getElementById('update_category_id').value = id;
        document.getElementById('update_category_name').value = name;
        // show modal if using bootstrap modal
        const modalEl = document.getElementById('updateModal');
        if (modalEl) {
            const bs = bootstrap.Modal.getOrCreateInstance(modalEl);
            bs.show();
        }
    };

    // UPDATE
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = updateForm.querySelector('input[name="cat_id"]').value;
            const name = updateForm.querySelector('input[name="cat_name"]').value.trim();
            const msg = document.getElementById('updateMsg');
            if (!id || !name) return showMsg(msg, 'danger', 'Invalid data');

            const fd = new FormData();
            fd.append('cat_id', id);
            fd.append('cat_name', name);

            fetch(updateUrl, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(j => {
                if (j.status === 'success') {
                    showMsg(msg, 'success', j.message);
                    fetchCategories();
                    const modalEl = document.getElementById('updateModal');
                    if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();
                } else {
                    showMsg(msg, 'danger', j.message);
                }
            }).catch(() => showMsg(msg, 'danger', 'Error updating'));
        });
    }

    // DELETE
    window.doDelete = function(btn) {
        if (!confirm('Delete this category?')) return;
        const id = btn.getAttribute('data-id');
        const fd = new FormData();
        fd.append('cat_id', id);
        fetch(deleteUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(j => {
            if (j.status === 'success') fetchCategories();
            else alert(j.message || 'Delete failed');
        }).catch(() => alert('Error deleting'));
    };

    // initial load only if list exists (category.php page)
    if (listEl) fetchCategories();
});



