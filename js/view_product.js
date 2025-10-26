// /js/product.js
document.addEventListener('DOMContentLoaded', () => {
  const api = '../actions/product_actions.php';
  const pageSize = 12;

  // detect page type
  const productsGrid = document.getElementById('productsGrid');
  const singleArea = document.getElementById('productArea');

  // fetch filters populate dropdowns
  function loadFilters() {
    fetch(`${api}?action=filters`)
      .then(r => r.json())
      .then(j => {
        if (j.status !== 'success') return;
        const cats = j.categories || [];
        const brands = j.brands || [];
        const catEl = document.getElementById('filterCategory');
        const brandEl = document.getElementById('filterBrand');
        if (catEl) {
          cats.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.cat_id;
            opt.textContent = c.cat_name;
            catEl.appendChild(opt);
          });
        }
        if (brandEl) {
          brands.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.brand_id;
            opt.textContent = b.brand_name;
            brandEl.appendChild(opt);
          });
        }
      }).catch(console.error);
  }

  // Render product card
  function renderCard(p) {
    const img = p.product_image ? p.product_image : '/uploads/placeholder.png';
    const slug = encodeURIComponent(p.product_id);
    return `
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card product-card h-100">
          <img src="${img}" class="card-img-top" style="height:180px;object-fit:cover" alt="${escapeHtml(p.product_title)}">
          <div class="card-body d-flex flex-column">
            <small class="text-muted mb-1">${escapeHtml(p.brand_name || '')}</small>
            <h6 class="card-title" style="min-height:46px">${escapeHtml(p.product_title)}</h6>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <div>
                <div class="price">GHS ${Number(p.product_price).toFixed(2)}</div>
                <small class="text-muted">${escapeHtml(p.cat_name || '')}</small>
              </div>
              <div>
                <a href="../view/single_product.php?id=${slug}" class="btn btn-sm btn-outline-primary">View</a>
              </div>
            </div>
          </div>
        </div>
      </div>`;
  }

  function escapeHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // Fetch products with current UI state
  async function fetchProducts(page = 1) {
    const q = document.getElementById('globalSearch') ? document.getElementById('globalSearch').value.trim() : '';
    const cat_id = document.getElementById('filterCategory') ? document.getElementById('filterCategory').value : 0;
    const brand_id = document.getElementById('filterBrand') ? document.getElementById('filterBrand').value : 0;
    const sort = document.getElementById('sortBy') ? document.getElementById('sortBy').value : 'newest';

    const url = `${api}?action=list&q=${encodeURIComponent(q)}&cat_id=${cat_id}&brand_id=${brand_id}&page=${page}&page_size=${pageSize}&sort=${sort}`;
    const res = await fetch(url);
    const json = await res.json();
    if (json.status !== 'success') {
      if (productsGrid) productsGrid.innerHTML = `<div class="col-12 text-danger">Failed to load products</div>`;
      return;
    }
    const items = json.data || [];
    const total = json.total || 0;

    if (productsGrid) {
      if (!items.length) {
        productsGrid.innerHTML = `<div class="col-12 text-muted">No products found</div>`;
      } else {
        productsGrid.innerHTML = items.map(renderCard).join('');
      }
      // results info
      const info = document.getElementById('resultsInfo');
      if (info) info.textContent = `${total} result(s)`;
      renderPagination(total, page);
    }
  }

  // pagination
  function renderPagination(total, currentPage) {
    const pages = Math.ceil(total / pageSize);
    const container = document.getElementById('pagination');
    if (!container) return;
    container.innerHTML = '';
    if (pages <= 1) return;
    const makeLi = (n, label = null, active = false) => {
      const li = document.createElement('li');
      li.className = 'page-item' + (active ? ' active' : '');
      li.innerHTML = `<a class="page-link" href="#">${label ?? n}</a>`;
      li.addEventListener('click', (e) => { e.preventDefault(); fetchProducts(n); });
      return li;
    };
    for (let i = 1; i <= pages; i++) container.appendChild(makeLi(i, null, i === currentPage));
  }

  // Single product loader
  async function loadSingleProduct() {
    const params = new URLSearchParams(location.search);
    const id = params.get('id');
    if (!id) {
      document.getElementById('singleMsg').innerHTML = '<div class="alert alert-danger">Missing product ID</div>';
      return;
    }
    const res = await fetch(`${api}?action=view_single&id=${encodeURIComponent(id)}`);
    const j = await res.json();
    if (j.status !== 'success') {
      document.getElementById('singleMsg').innerHTML = `<div class="alert alert-danger">${j.message || 'Not found'}</div>`;
      return;
    }
    const p = j.data;
    document.getElementById('prodTitle').textContent = p.product_title;
    document.getElementById('prodPrice').textContent = `GHS ${Number(p.product_price).toFixed(2)}`;
    document.getElementById('prodDesc').textContent = p.product_desc || '';
    document.getElementById('prodCategory').textContent = p.cat_name || '';
    document.getElementById('prodBrand').textContent = p.brand_name || '';
    document.getElementById('prodKeywords').textContent = p.product_keywords || '';
    document.getElementById('prodImage').src = p.product_image || '/uploads/placeholder.png';
  }

  // bind UI listeners (filters, search)
  function bindUI() {
    const search = document.getElementById('globalSearch');
    if (search) {
      let timer = null;
      search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => fetchProducts(1), 450); });
    }
    const cat = document.getElementById('filterCategory');
    if (cat) cat.addEventListener('change', () => fetchProducts(1));
    const brand = document.getElementById('filterBrand');
    if (brand) brand.addEventListener('change', () => fetchProducts(1));
    const sort = document.getElementById('sortBy');
    if (sort) sort.addEventListener('change', () => fetchProducts(1));
  }

  // init for listing page
  if (productsGrid) {
    loadFilters();
    bindUI();
    fetchProducts(1);
  }

  // init for single product page
  if (singleArea) {
    loadFilters(); // optional: still load filters for nav
    loadSingleProduct();
  }
});
