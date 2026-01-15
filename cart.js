/* cart.js (FINAL - CLEAN) */

// 1) Ορισμός χρήστη (έρχεται από header.php)
const userEmail = (typeof currentUserEmail !== 'undefined' && currentUserEmail)
  ? currentUserEmail
  : 'guest';

const CART_STORAGE_KEY = 'at_cart_' + userEmail;
const GUEST_KEY = 'at_cart_guest';

// -------------------------
// Helpers (Cart Storage)
// -------------------------
function safeParse(json, fallback) {
  try {
    const v = JSON.parse(json);
    return (v !== null && v !== undefined) ? v : fallback;
  } catch (e) {
    return fallback;
  }
}

// Cart format: [{ name: string, price: number, quantity: number }]
function getCart(key = CART_STORAGE_KEY) {
  const raw = localStorage.getItem(key);
  const cart = safeParse(raw, []);
  return Array.isArray(cart) ? cart : [];
}

function saveCart(cart, key = CART_STORAGE_KEY) {
  localStorage.setItem(key, JSON.stringify(cart));
}

function normalizeCart(cart) {
  const cleaned = [];
  for (const it of cart) {
    const name = (it && typeof it.name === 'string') ? it.name.trim() : '';
    const price = Number(it && it.price);
    const quantity = parseInt(it && it.quantity, 10);

    if (!name) continue;
    if (!Number.isFinite(price) || price <= 0) continue;
    if (!Number.isFinite(quantity) || quantity <= 0) continue;

    cleaned.push({ name, price, quantity });
  }
  return cleaned;
}

function findItemIndex(cart, name) {
  return cart.findIndex(x => (x.name || '') === name);
}

// -------------------------
// Guest -> User merge (μόνο όταν γίνει login)
// -------------------------
(function mergeGuestCartIfLoggedIn() {
  if (userEmail === 'guest') return;

  const guestCart = getCart(GUEST_KEY);
  if (!guestCart.length) return;

  let userCart = getCart(CART_STORAGE_KEY);

  for (const g of guestCart) {
    const idx = findItemIndex(userCart, g.name);
    if (idx >= 0) {
      userCart[idx].quantity += g.quantity;
    } else {
      userCart.push({ name: g.name, price: g.price, quantity: g.quantity });
    }
  }

  userCart = normalizeCart(userCart);
  saveCart(userCart, CART_STORAGE_KEY);
  localStorage.removeItem(GUEST_KEY);

  alert("✅ Τα προϊόντα του guest καλαθιού μεταφέρθηκαν στον λογαριασμό σας.");
})();

// -------------------------
// Public API (used in pages)
// -------------------------
function addToCart(name, price) {
  name = (name || '').toString().trim();
  const p = Number(price);

  if (!name || !Number.isFinite(p) || p <= 0) {
    alert("⚠️ Μη έγκυρο προϊόν.");
    return;
  }

  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;

  let cart = getCart(key);
  const idx = findItemIndex(cart, name);

  if (idx >= 0) cart[idx].quantity += 1;
  else cart.push({ name, price: p, quantity: 1 });

  cart = normalizeCart(cart);
  saveCart(cart, key);
  updateCartCount();

  alert("🛒 Προστέθηκε: " + name);
}

function removeFromCart(name) {
  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;
  let cart = getCart(key);

  cart = cart.filter(it => it.name !== name);
  saveCart(cart, key);

  renderCart();
  updateCartCount();
}

function changeQuantity(name, delta) {
  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;
  let cart = getCart(key);
  const idx = findItemIndex(cart, name);
  if (idx < 0) return;

  cart[idx].quantity += delta;

  if (cart[idx].quantity <= 0) {
    cart = cart.filter(it => it.name !== name);
  }

  cart = normalizeCart(cart);
  saveCart(cart, key);

  renderCart();
  updateCartCount();
}

function clearCart() {
  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;
  localStorage.removeItem(key);
  renderCart();
  updateCartCount();
}

function getTotals() {
  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;
  const cart = getCart(key);

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
  el.innerText = String(count);
}

function renderCart() {
  const tableBody = document.getElementById("cart-items-container");
  const totalDisplay = document.getElementById("grand-total");
  if (!tableBody) return;

  const key = (userEmail === 'guest') ? GUEST_KEY : CART_STORAGE_KEY;
  let cart = getCart(key);
  cart = normalizeCart(cart);
  saveCart(cart, key);

  tableBody.innerHTML = "";

  let total = 0;

  if (!cart.length) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="5" style="padding:15px; color:#ccc;">
          Το καλάθι είναι άδειο.
        </td>
      </tr>
    `;
    if (totalDisplay) totalDisplay.innerText = "0.00";
    return;
  }

  for (const it of cart) {
    const subtotal = it.price * it.quantity;
    total += subtotal;

    tableBody.innerHTML += `
      <tr style="border-bottom:1px solid #333;">
        <td style="padding:15px;">${escapeHtml(it.name)}</td>
        <td style="padding:15px;">
          <button onclick="changeQuantity('${escapeJs(it.name)}', -1)" style="padding:4px 10px; margin-right:8px;">-</button>
          <strong>${it.quantity}</strong>
          <button onclick="changeQuantity('${escapeJs(it.name)}', 1)" style="padding:4px 10px; margin-left:8px;">+</button>
        </td>
        <td style="padding:15px;">${it.price.toFixed(2)} €</td>
        <td style="padding:15px;">${subtotal.toFixed(2)} €</td>
        <td style="padding:15px;">
          <button onclick="removeFromCart('${escapeJs(it.name)}')" style="padding:6px 10px; background:#ff6b6b;">
            Remove
          </button>
        </td>
      </tr>
    `;
  }

  if (totalDisplay) totalDisplay.innerText = total.toFixed(2);
}

// Guest δεν κάνει checkout
function checkout() {
  if (userEmail === 'guest') {
    alert("⚠️ Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.");
    window.location.href = 'login.php'; // FIX
    return;
  }

  const { total } = getTotals();
  if (total <= 0) {
    alert("Το καλάθι σας είναι άδειο!");
    return;
  }

  window.location.href = 'payment.php?total=' + encodeURIComponent(total.toFixed(2));
}

// -------------------------
// Security-ish helpers for DOM strings
// -------------------------
function escapeHtml(str) {
  return String(str)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function escapeJs(str) {
  return String(str).replaceAll("\\", "\\\\").replaceAll("'", "\\'");
}

// -------------------------
// Init
// -------------------------
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
  renderCart();
});