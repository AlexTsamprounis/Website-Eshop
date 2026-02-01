/* cart.js (LOCAL READY - CLEAN - NO INLINE ONCLICK) */

(function () {
  // -------------------------
  // User + Storage keys
  // -------------------------
  const userEmail =
    (typeof window !== "undefined" &&
      window.currentUserEmail &&
      String(window.currentUserEmail).trim())
      ? String(window.currentUserEmail).trim()
      : "guest";

  const CART_STORAGE_KEY = "at_cart_" + userEmail;
  const GUEST_KEY = "at_cart_guest";

  // LOCAL: relative endpoint
  const PRODUCT_ENDPOINT = "get_product.php?id=";

  // -------------------------
  // Helpers
  // -------------------------
  function safeParse(json, fallback) {
    try {
      const v = JSON.parse(json);
      return v !== null && v !== undefined ? v : fallback;
    } catch {
      return fallback;
    }
  }

  function getStorageKey() {
    return userEmail === "guest" ? GUEST_KEY : CART_STORAGE_KEY;
  }

  function getCart(key) {
    const raw = localStorage.getItem(key);
    const cart = safeParse(raw, []);
    return Array.isArray(cart) ? cart : [];
  }

  function saveCart(cart, key) {
    localStorage.setItem(key, JSON.stringify(cart));
  }

  function normalizeCart(cart) {
    const cleaned = [];

    for (const it of cart) {
      const id = Number(it && it.id);
      const name = (it && typeof it.name === "string") ? it.name.trim() : "";
      const price = Number(it && it.price);
      const quantity = parseInt(it && it.quantity, 10);

      if (!Number.isFinite(id) || id <= 0) continue;
      if (!name) continue;
      if (!Number.isFinite(price) || price <= 0) continue;
      if (!Number.isFinite(quantity) || quantity <= 0) continue;

      cleaned.push({ id, name, price, quantity });
    }

    return cleaned;
  }

  function findItemIndexById(cart, id) {
    return cart.findIndex((x) => Number(x.id) === Number(id));
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  // -------------------------
  // Guest -> User merge
  // -------------------------
  function mergeGuestCartIfLoggedIn() {
    if (userEmail === "guest") return;

    const guestCart = normalizeCart(getCart(GUEST_KEY));
    if (!guestCart.length) return;

    let userCart = normalizeCart(getCart(CART_STORAGE_KEY));

    for (const g of guestCart) {
      const idx = findItemIndexById(userCart, g.id);
      if (idx >= 0) userCart[idx].quantity += g.quantity;
      else userCart.push({ id: g.id, name: g.name, price: g.price, quantity: g.quantity });
    }

    userCart = normalizeCart(userCart);
    saveCart(userCart, CART_STORAGE_KEY);
    localStorage.removeItem(GUEST_KEY);

    alert("✅ Τα προϊόντα του guest καλαθιού μεταφέρθηκαν στον λογαριασμό σας.");
  }

  // -------------------------
  // Fetch product details
  // -------------------------
  async function fetchProductById(productId) {
    const id = Number(productId);
    if (!Number.isFinite(id) || id <= 0) return { ok: false, reason: "bad id" };

    const url = PRODUCT_ENDPOINT + encodeURIComponent(String(id));

    let res;
    try {
      res = await fetch(url, { method: "GET", headers: { Accept: "application/json" } });
    } catch {
      return { ok: false, reason: "fetch failed", url };
    }

    if (!res.ok) return { ok: false, reason: "http " + res.status, url };

    let data;
    try {
      data = await res.json();
    } catch {
      return { ok: false, reason: "bad json", url };
    }

    const pid = Number(data && data.id);
    const name = (data && typeof data.name === "string") ? data.name.trim() : "";
    const price = Number(data && data.price);

    if (!Number.isFinite(pid) || pid <= 0) return { ok: false, reason: "invalid pid", url, data };
    if (!name) return { ok: false, reason: "missing name", url, data };
    if (!Number.isFinite(price) || price <= 0) return { ok: false, reason: "invalid price", url, data };

    return { ok: true, product: { id: pid, name, price }, url };
  }

  // -------------------------
  // Totals + Count
  // -------------------------
  function getTotals() {
    const key = getStorageKey();
    const cart = normalizeCart(getCart(key));

    let count = 0;
    let total = 0;

    for (const it of cart) {
      count += it.quantity;
      total += it.price * it.quantity;
    }

    return { count, total };
  }

  function updateCartCount() {
    const el = document.getElementById("cart-count");
    if (!el) return;

    const { count } = getTotals();
    el.textContent = String(count);
  }

  // -------------------------
  // Cart operations
  // -------------------------
  async function addToCart(productId) {
    const id = Number(productId);
    if (!Number.isFinite(id) || id <= 0) {
      alert("⚠️ Μη έγκυρο προϊόν (λάθος id).");
      return;
    }

    const r = await fetchProductById(id);
    if (!r.ok) {
      console.error("addToCart failed:", r);
      alert("⚠️ Μη έγκυρο προϊόν.\n\nΔες Console για λεπτομέρειες.");
      return;
    }

    const product = r.product;

    const key = getStorageKey();
    let cart = normalizeCart(getCart(key));

    const idx = findItemIndexById(cart, product.id);
    if (idx >= 0) cart[idx].quantity += 1;
    else cart.push({ id: product.id, name: product.name, price: product.price, quantity: 1 });

    cart = normalizeCart(cart);
    saveCart(cart, key);

    updateCartCount();
    alert("🛒 Προστέθηκε: " + product.name);
  }

  function removeFromCart(productId) {
    const id = Number(productId);
    if (!Number.isFinite(id) || id <= 0) return;

    const key = getStorageKey();
    let cart = normalizeCart(getCart(key));

    cart = cart.filter((it) => Number(it.id) !== id);
    saveCart(cart, key);

    renderCart();
    updateCartCount();
  }

  function changeQuantity(productId, delta) {
    const id = Number(productId);
    if (!Number.isFinite(id) || id <= 0) return;

    const d = Number(delta);
    if (!Number.isFinite(d) || d === 0) return;

    const key = getStorageKey();
    let cart = normalizeCart(getCart(key));

    const idx = findItemIndexById(cart, id);
    if (idx < 0) return;

    cart[idx].quantity += d;

    if (cart[idx].quantity <= 0) {
      cart = cart.filter((it) => Number(it.id) !== id);
    }

    cart = normalizeCart(cart);
    saveCart(cart, key);

    renderCart();
    updateCartCount();
  }

  function clearCart() {
    const key = getStorageKey();
    localStorage.removeItem(key);
    renderCart();
    updateCartCount();
  }

  // -------------------------
  // Render cart (NO inline onclick)
  // -------------------------
  function renderCart() {
    const tableBody = document.getElementById("cart-items-container");
    const totalDisplay = document.getElementById("grand-total");
    if (!tableBody) return;

    const key = getStorageKey();
    let cart = normalizeCart(getCart(key));
    saveCart(cart, key);

    tableBody.innerHTML = "";

    if (!cart.length) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="cart-empty">Το καλάθι είναι άδειο.</td>
        </tr>
      `;
      if (totalDisplay) totalDisplay.textContent = "0.00";
      return;
    }

    let total = 0;

    for (const it of cart) {
      const subtotal = it.price * it.quantity;
      total += subtotal;

      tableBody.insertAdjacentHTML("beforeend", `
        <tr class="cart-row">
          <td class="cart-td">${escapeHtml(it.name)}</td>

          <td class="cart-td cart-qty-cell">
            <button class="qty-btn" type="button" data-action="dec" data-id="${it.id}">-</button>
            <strong class="qty-value">${it.quantity}</strong>
            <button class="qty-btn" type="button" data-action="inc" data-id="${it.id}">+</button>
          </td>

          <td class="cart-td">${it.price.toFixed(2)} €</td>
          <td class="cart-td">${subtotal.toFixed(2)} €</td>

          <td class="cart-td">
            <button class="remove-btn" type="button" data-action="remove" data-id="${it.id}">Remove</button>
          </td>
        </tr>
      `);
    }

    if (totalDisplay) totalDisplay.textContent = total.toFixed(2);
  }

  // -------------------------
  // Checkout
  // -------------------------
  function handleCheckoutClick() {
    if (userEmail === "guest") {
      alert("⚠️ Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.");
      window.location.href = "login.php?redirect=" + encodeURIComponent("cart.php");
      return;
    }

    const { total } = getTotals();
    if (!total || total <= 0) {
      alert("Το καλάθι σας είναι άδειο!");
      return;
    }

    window.location.href = "payment.php?total=" + encodeURIComponent(total.toFixed(2));
  }

  // -------------------------
  // HERO SLIDER (NO inline onclick)
  // Works only if #hero exists
  // -------------------------
  function initHeroSlider() {
    const hero = document.getElementById("hero");
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll(".hero-slide"));
    const dotsWrap = hero.querySelector(".hero-dots");
    const dots = Array.from(hero.querySelectorAll(".hero-dots .dot"));
    const prevBtn = hero.querySelector("[data-hero-prev]");
    const nextBtn = hero.querySelector("[data-hero-next]");

    if (!slides.length) return;

    let index = slides.findIndex((s) => s.classList.contains("active"));
    if (index < 0) index = 0;

    function render(i) {
      index = i;
      slides.forEach((s, idx) => s.classList.toggle("active", idx === index));
      dots.forEach((d, idx) => d.classList.toggle("active", idx === index));
    }

    function next() {
      render((index + 1) % slides.length);
    }

    function prev() {
      render((index - 1 + slides.length) % slides.length);
    }

    if (prevBtn) prevBtn.addEventListener("click", prev);
    if (nextBtn) nextBtn.addEventListener("click", next);

    if (dotsWrap) {
      dotsWrap.addEventListener("click", (e) => {
        const dot = e.target.closest("[data-hero-dot][data-hero-index]");
        if (!dot) return;
        const i = Number(dot.getAttribute("data-hero-index"));
        if (!Number.isFinite(i)) return;
        if (i < 0 || i >= slides.length) return;
        render(i);
      });
    }

    // Auto-play (optional)
    const AUTO_PLAY = true;
    const AUTO_MS = 5000;
    let timer = null;

    function startAuto() {
      if (!AUTO_PLAY) return;
      stopAuto();
      timer = setInterval(next, AUTO_MS);
    }
    function stopAuto() {
      if (timer) clearInterval(timer);
      timer = null;
    }

    hero.addEventListener("mouseenter", stopAuto);
    hero.addEventListener("mouseleave", startAuto);
    hero.addEventListener("touchstart", stopAuto, { passive: true });
    hero.addEventListener("touchend", startAuto);

    render(index);
    startAuto();
  }

  // -------------------------
  // DOM events
  // -------------------------
  function bindCartEvents() {
    // Cart table buttons
    const tableBody = document.getElementById("cart-items-container");
    if (tableBody) {
      tableBody.addEventListener("click", (e) => {
        const btn = e.target.closest("button[data-action][data-id]");
        if (!btn) return;

        const action = btn.getAttribute("data-action");
        const id = Number(btn.getAttribute("data-id"));
        if (!Number.isFinite(id) || id <= 0) return;

        if (action === "inc") changeQuantity(id, +1);
        else if (action === "dec") changeQuantity(id, -1);
        else if (action === "remove") removeFromCart(id);
      });
    }

    // Checkout button (must exist on cart.php)
    const checkoutBtn = document.getElementById("checkout-btn");
    if (checkoutBtn) checkoutBtn.addEventListener("click", handleCheckoutClick);

    // Add-to-cart buttons (optional alternative to inline onclick)
    document.addEventListener("click", (e) => {
      const btn = e.target.closest("button[data-add-to-cart][data-product-id]");
      if (!btn) return;

      const id = Number(btn.getAttribute("data-product-id"));
      if (!Number.isFinite(id) || id <= 0) return;

      addToCart(id);
    });
  }

  // -------------------------
  // Expose minimal globals (only if still needed)
  // -------------------------
  window.addToCart = addToCart; // keep for product cards for now
  window.clearCart = clearCart;

  // -------------------------
  // Init
  // -------------------------
  document.addEventListener("DOMContentLoaded", () => {
    mergeGuestCartIfLoggedIn();
    bindCartEvents();
    updateCartCount();
    renderCart();
    initHeroSlider();
  });
})();