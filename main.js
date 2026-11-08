(function () {
  'use strict';

  const CART_KEY = 'sufiCart';
  let discountRate = 0;   // used by the coupon form on cart page

  /* =============== Helpers =============== */
  function money(n) { return 'Rs. ' + Number(n).toLocaleString('en-PK'); }

  function setAll(selector, value) {
    document.querySelectorAll(selector).forEach(el => (el.textContent = value));
  }

  /* =============== Cart storage (demo only) =============== */
  function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch (e) { return []; }
  }

  function saveCart(items) {
    localStorage.setItem(CART_KEY, JSON.stringify(items));
    updateCartBadges();
    renderCartPage();
    updateTotals();
  }

  function cartCount()    { return getCart().reduce((s, i) => s + i.qty, 0); }
  function cartSubtotal() { return getCart().reduce((s, i) => s + i.price * i.qty, 0); }

  function seedCart() {
    if (localStorage.getItem(CART_KEY)) return;
    localStorage.setItem(CART_KEY, JSON.stringify([
      { id: 'p1', name: 'Gulab Jamun (1 kg)',     price: 1200, qty: 1, emoji: '🍮', cat: 'Sweets' },
      { id: 'p2', name: 'Kaju Katli (500 g)',     price: 1800, qty: 1, emoji: '🍥', cat: 'Sweets' },
      { id: 'p7', name: 'Pure Desi Ghee (1 kg)',  price: 2600, qty: 1, emoji: '🧈', cat: 'Dairy'  }
    ]));
  }

  function addToCart(id, name, price, emoji, cat) {
    const cart = getCart();
    const found = cart.find(i => i.id === id);
    if (found) found.qty += 1;
    else cart.push({ id, name, price, qty: 1, emoji: emoji || '🍬', cat: cat || 'Sweets' });
    saveCart(cart);
    toast(name + ' added to cart 🛒');
  }

  /* =============== Badge / totals =============== */
  function updateCartBadges() {
    const n = cartCount();
    document.querySelectorAll('.cart-count').forEach(el => (el.textContent = n));
  }

  function updateTotals() {
    const subtotal = cartSubtotal();
    const delivery = subtotal === 0 ? 0 : (subtotal >= 3000 ? 0 : 200);
    const discount = Math.round(subtotal * discountRate);
    const total    = subtotal + delivery - discount;

    setAll('[data-sum="subtotal"]', money(subtotal));
    setAll('[data-sum="delivery"]', delivery === 0 ? 'Free' : money(delivery));
    setAll('[data-sum="discount"]', discount ? '-' + money(discount) : money(0));
    setAll('[data-sum="total"]',    money(total));
    setAll('[data-sum="items"]',    cartCount() + ' item(s)');

    renderCheckoutItems();
  }

  /* =============== Cart page rendering =============== */
  function renderCartPage() {
    const tbody = document.getElementById('cartBody');
    if (!tbody) return;

    const cart = getCart();
    const tableWrap = document.getElementById('cartTableWrap');
    const emptyBox  = document.getElementById('cartEmpty');

    if (tableWrap) tableWrap.classList.toggle('d-none', cart.length === 0);
    if (emptyBox)  emptyBox.classList.toggle('d-none', cart.length > 0);

    tbody.innerHTML = cart.map(item => `
      <tr data-id="${item.id}">
        <td>
          <div class="d-flex align-items-center gap-3">
            <div class="cart-thumb">${item.emoji}</div>
            <div>
              <h6 class="mb-0">${item.name}</h6>
              <small class="text-muted">${item.cat}</small>
            </div>
          </div>
        </td>
        <td class="text-center">${money(item.price)}</td>
        <td class="text-center">
          <div class="input-group input-group-sm mx-auto" style="max-width:120px">
            <button class="btn btn-outline-secondary" type="button" data-action="dec" aria-label="Decrease">−</button>
            <input type="text" class="form-control text-center" value="${item.qty}" readonly aria-label="Quantity">
            <button class="btn btn-outline-secondary" type="button" data-action="inc" aria-label="Increase">+</button>
          </div>
        </td>
        <td class="text-end fw-semibold">${money(item.price * item.qty)}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-danger" type="button" data-action="remove" aria-label="Remove">✕</button>
        </td>
      </tr>`).join('');
  }

  function renderCheckoutItems() {
    const box = document.getElementById('checkoutItems');
    if (!box) return;
    const cart = getCart();
    box.innerHTML = cart.length
      ? cart.map(i => `
          <div class="d-flex justify-content-between small mb-2">
            <span>${i.emoji} ${i.name} × ${i.qty}</span>
            <span class="fw-semibold">${money(i.price * i.qty)}</span>
          </div>`).join('')
      : '<p class="text-muted small mb-0">Your cart is empty.</p>';
  }

  /* =============== Toast =============== */
  function toast(message) {
    let container = document.getElementById('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toastContainer';
      container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      document.body.appendChild(container);
    }
    const el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-dark border-0';
    el.setAttribute('role', 'alert');
    el.innerHTML = `<div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
    container.appendChild(el);
    new bootstrap.Toast(el, { delay: 2200 }).show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
  }

  /* =============== Global click handler =============== */
  document.addEventListener('click', function (e) {

    /* --- Add to cart (any card with data-id) --- */
    const addBtn = e.target.closest('.add-to-cart');
    if (addBtn) {
      const card = addBtn.closest('[data-id]');
      if (card) {
        addToCart(card.dataset.id, card.dataset.name, Number(card.dataset.price),
                  card.dataset.emoji, card.dataset.category);
      }
      return;
    }

    /* --- Cart quantity / remove --- */
    const actionBtn = e.target.closest('[data-action]');
    if (actionBtn) {
      const row = actionBtn.closest('tr[data-id]');
      if (!row) return;
      const cart = getCart();
      const item = cart.find(i => i.id === row.dataset.id);
      if (!item) return;

      const action = actionBtn.dataset.action;
      if (action === 'inc') item.qty += 1;
      if (action === 'dec') item.qty = Math.max(1, item.qty - 1);
      if (action === 'remove') {
        cart.splice(cart.indexOf(item), 1);
        toast(item.name + ' removed from cart');
      }
      saveCart(cart);
      return;
    }
  });

  /* =============== Products page: filters + search + quick view =============== */
  const filterBar = document.getElementById('filterBar');
  if (filterBar) {
    const cols       = Array.from(document.querySelectorAll('.product-col'));
    const searchBox  = document.getElementById('productSearch');
    const noResults  = document.getElementById('noResults');
    const resultCount= document.getElementById('resultCount');
    let activeCat = 'all';
    let term = '';

    function applyFilters() {
      let visible = 0;
      cols.forEach(col => {
        const matchCat  = activeCat === 'all' || col.dataset.category === activeCat;
        const matchTerm = col.dataset.name.toLowerCase().includes(term);
        const show = matchCat && matchTerm;
        col.classList.toggle('d-none', !show);
        if (show) visible++;
      });
      if (noResults)   noResults.classList.toggle('d-none', visible > 0);
      if (resultCount) resultCount.textContent = visible;
    }

    filterBar.addEventListener('click', function (e) {
      const btn = e.target.closest('[data-filter]');
      if (!btn) return;
      filterBar.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeCat = btn.dataset.filter;
      applyFilters();
    });

    if (searchBox) {
      searchBox.addEventListener('input', function () {
        term = searchBox.value.trim().toLowerCase();
        applyFilters();
      });
    }

    /* Deep-link from homepage category cards: products.html?category=dairy */
    const params = new URLSearchParams(location.search);
    const catParam = params.get('category');
    if (catParam) {
      const b = filterBar.querySelector('[data-filter="' + catParam + '"]');
      if (b) b.click();
    }
    const qParam = params.get('q');
    if (qParam && searchBox) {
      searchBox.value = qParam;
      term = qParam.toLowerCase();
      applyFilters();
    }
  }

  /* ---- Quick view modal ---- */
  let currentProduct = null;
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.quick-view');
    if (!btn) return;
    const card = btn.closest('[data-id]');
    if (!card) return;

    currentProduct = {
      id: card.dataset.id, name: card.dataset.name,
      price: card.dataset.price, emoji: card.dataset.emoji,
      cat: card.dataset.category, desc: card.dataset.desc
    };

    document.getElementById('qvEmoji').textContent = currentProduct.emoji;
    document.getElementById('qvName').textContent  = currentProduct.name;
    document.getElementById('qvCat').textContent   = currentProduct.cat;
    document.getElementById('qvPrice').textContent = money(currentProduct.price);
    document.getElementById('qvDesc').textContent  = currentProduct.desc;

    bootstrap.Modal.getOrCreateInstance(document.getElementById('quickViewModal')).show();
  });

  const qvAdd = document.getElementById('qvAdd');
  if (qvAdd) {
    qvAdd.addEventListener('click', function () {
      if (!currentProduct) return;
      addToCart(currentProduct.id, currentProduct.name, Number(currentProduct.price),
                currentProduct.emoji, currentProduct.cat);
      bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();
    });
  }

  /* =============== Coupon (cart page) =============== */
  const couponForm = document.getElementById('couponForm');
  if (couponForm) {
    couponForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const code = document.getElementById('couponCode').value.trim().toUpperCase();
      const msg  = document.getElementById('couponMsg');
      if (code === 'SUFI10') {
        discountRate = 0.10;
        msg.textContent = '✔ Coupon SUFI10 applied — 10% off';
        msg.className = 'small text-success mt-2';
      } else {
        discountRate = 0;
        msg.textContent = '✖ Invalid coupon code. Try SUFI10.';
        msg.className = 'small text-danger mt-2';
      }
      updateTotals();
    });
  }

  /* =============== Generic Bootstrap form validation =============== */
  document.querySelectorAll('form.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (form.checkValidity()) {
        const ok = form.querySelector('.form-success');
        if (ok) ok.classList.remove('d-none');
        toast('Submitted successfully ✔ (UI demo — no backend)');
        form.classList.remove('was-validated');
        form.reset();
        if (ok) ok.classList.remove('d-none');
      } else {
        form.classList.add('was-validated');
      }
    });
  });

  /* =============== Register: password match =============== */
  const regForm = document.getElementById('registerForm');
  if (regForm) {
    const pass    = document.getElementById('regPassword');
    const confirm = document.getElementById('regConfirm');
    const check = () => {
      confirm.setCustomValidity(confirm.value === pass.value ? '' : 'Passwords do not match');
    };
    pass.addEventListener('input', check);
    confirm.addEventListener('input', check);
  }

  /* =============== Login: show/hide password =============== */
  const togglePass = document.getElementById('togglePass');
  if (togglePass) {
    togglePass.addEventListener('click', function () {
      const input = document.getElementById('loginPassword');
      input.type = input.type === 'password' ? 'text' : 'password';
      togglePass.textContent = input.type === 'password' ? 'Show' : 'Hide';
    });
  }

  /* =============== Boot =============== */
  document.addEventListener('DOMContentLoaded', function () {
    seedCart();
    updateCartBadges();
    renderCartPage();
    updateTotals();
  });

  /* Expose small API (optional, for clarity) */
  window.SufiSweets = { addToCart: addToCart, toast: toast, money: money };

})();