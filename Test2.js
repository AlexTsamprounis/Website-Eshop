document.addEventListener("DOMContentLoaded", function () {
  console.log("Test2.js loaded");

    // =========================
  // 0) MOBILE HAMBURGER (GLOBAL - runs on all pages)
  // =========================
  const headerEl = document.querySelector("header");
  const menuToggle = document.querySelector(".menu-toggle");

  console.log("headerEl found?", !!headerEl, headerEl);
  console.log("menuToggle found?", !!menuToggle, menuToggle);

  if (headerEl && menuToggle) {
    menuToggle.addEventListener("click", () => {
      const isOpen = headerEl.classList.toggle("nav-open");
      menuToggle.setAttribute("aria-expanded", String(isOpen));
      console.log("Hamburger clicked. nav-open =", isOpen);
    });
  }

  // =========================
  // 1) Textarea character counter (μόνο αν υπάρχει στο register)
  // =========================
  const commentsInput = document.querySelector("#formComments");
  const remainingCharsDisplay = document.querySelector("#remainingChars");
  const maxChars = 400;

  if (commentsInput && remainingCharsDisplay) {
    const updateRemaining = () => {
      remainingCharsDisplay.textContent = maxChars - commentsInput.value.length;
    };

    commentsInput.addEventListener("input", updateRemaining);
    updateRemaining();
  }

  // =========================
  // 2) Form validation — ΜΟΝΟ για register form
  // =========================

  // ✅ Κλειδί: Αν ΔΕΝ υπάρχουν τα πεδία του register, ΔΕΝ πειράζουμε κανένα submit (payment/cart κλπ)
  const firstname = document.querySelector("#firstname");
  const lastname  = document.querySelector("#lastname");
  const gender    = document.querySelector("#formGender");
  const email     = document.querySelector("#emailAdress");
  const password  = document.querySelector("#formPassword");
  const agreeTerms = document.querySelector("#agreeTerms");

  // Αν δεν είναι register σελίδα/φόρμα → βγες
  if (!firstname || !lastname || !gender || !email || !password || !agreeTerms) {
    return;
  }

  // Τώρα ξέρουμε 100% ότι είμαστε στο register form
  const form = firstname.closest("form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    clearErrors(form);

    const newsletterChecked = document.querySelector('input[name="formNewsletter"]:checked');
    let ok = true;

    // Firstname
    if (!firstname.value.trim()) {
      showError(firstname, "Συμπλήρωσε το First Name.");
      ok = false;
    } else if (firstname.value.trim().length < 2) {
      showError(firstname, "Το First Name πρέπει να έχει τουλάχιστον 2 χαρακτήρες.");
      ok = false;
    }

    // Lastname
    if (!lastname.value.trim()) {
      showError(lastname, "Συμπλήρωσε το Last Name.");
      ok = false;
    } else if (lastname.value.trim().length < 2) {
      showError(lastname, "Το Last Name πρέπει να έχει τουλάχιστον 2 χαρακτήρες.");
      ok = false;
    }

    // Gender
    if (!gender.value || gender.value === "null") {
      showError(gender, "Επέλεξε Gender.");
      ok = false;
    }

    // Email
    const emailValue = email.value.trim();
    if (emailValue === "") {
      showError(email, "Το email είναι υποχρεωτικό.");
      ok = false;
    } else if (!isValidEmail(emailValue)) {
      showError(email, "Βάλε έγκυρο email (π.χ. name@example.com).");
      ok = false;
    }

    // Password: 8+ και 1 αριθμός
    const pw = password.value;
    if (pw.length < 8 || !/\d/.test(pw)) {
      showError(password, "Password: 8+ χαρακτήρες και τουλάχιστον 1 αριθμό.");
      ok = false;
    }

    // Newsletter radio
    if (!newsletterChecked) {
      const newsletterFieldset = document.querySelector("fieldset.newsletter");
      if (newsletterFieldset) {
        showBlockError(newsletterFieldset, "Διάλεξε YES ή NO για το newsletter.");
      }
      ok = false;
    }

    // Agree terms
    if (!agreeTerms.checked) {
      showError(agreeTerms, "Πρέπει να αποδεχτείς τους όρους χρήσης.");
      ok = false;
    }

    if (!ok) return;

    // ✅ submit κανονικά προς PHP
    form.submit();

    // reset counter
    if (commentsInput && remainingCharsDisplay) {
      remainingCharsDisplay.textContent = maxChars;
    }
  });

  // =========================
  // Helpers
  // =========================
  function showError(inputEl, message) {
    inputEl.classList.add("input-error");

    const err = document.createElement("div");
    err.className = "form-error";
    err.textContent = message;

    if (inputEl.type === "checkbox") {
      inputEl.parentElement.appendChild(err);
    } else {
      inputEl.insertAdjacentElement("afterend", err);
    }
  }

  function showBlockError(containerEl, message) {
    const err = document.createElement("div");
    err.className = "form-error";
    err.textContent = message;
    containerEl.appendChild(err);
  }

  function clearErrors(root) {
    root.querySelectorAll(".form-error").forEach((el) => el.remove());
    root.querySelectorAll(".input-error").forEach((el) => el.classList.remove("input-error"));
    root.querySelectorAll(".form-success").forEach((el) => el.remove());
  }

  function isValidEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }
});

// ===== HERO SLIDER =====
let heroIndex = 0;

function heroSlides() {
  return document.querySelectorAll('.hero-slide');
}
function heroDots() {
  return document.querySelectorAll('.hero-dots .dot');
}

function heroGo(i){
  const slides = heroSlides();
  const dots = heroDots();
  if(!slides.length) return;

  heroIndex = (i + slides.length) % slides.length;

  slides.forEach((s, idx) => s.classList.toggle('active', idx === heroIndex));
  dots.forEach((d, idx) => d.classList.toggle('active', idx === heroIndex));
}

function heroNext(){ heroGo(heroIndex + 1); }
function heroPrev(){ heroGo(heroIndex - 1); }

// autoplay
document.addEventListener('DOMContentLoaded', () => {
  if (heroSlides().length) {
    setInterval(() => heroNext(), 6000);
  }
});

// =========================
// PAYMENT PAGE logic (only if payment form exists)
// =========================
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("payment-form");
  if (!form) return; // ✅ important: don't run on other pages

  // Χρησιμοποιούμε το ίδιο key με cart.js
  const userEmail =
    (typeof window !== "undefined" && window.currentUserEmail && String(window.currentUserEmail).trim())
      ? String(window.currentUserEmail).trim()
      : "guest";

  const CART_STORAGE_KEY = (userEmail === "guest") ? "at_cart_guest" : ("at_cart_" + userEmail);

  const hiddenItems = document.getElementById("hidden_items");

  const expiryInput = document.getElementById("card_expiry");
  const cvvInput = document.getElementById("card_cvv");

  const cardInput = form.elements["card_number"];
  const zipInput  = form.elements["zip"];

  if (!hiddenItems || !expiryInput || !cvvInput || !cardInput || !zipInput) return;

  // Μόνο digits για κάρτα/cvv/zip (nice-to-have)
  cardInput.addEventListener("input", () => {
    cardInput.value = cardInput.value.replace(/\D/g, "").slice(0, 16);
  });

  cvvInput.addEventListener("input", () => {
    cvvInput.value = cvvInput.value.replace(/\D/g, "").slice(0, 3);
  });

  zipInput.addEventListener("input", () => {
    zipInput.value = zipInput.value.replace(/\D/g, "").slice(0, 5);
  });

  // expiry MM/YY
  expiryInput.addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "").slice(0, 4);
    if (v.length >= 3) e.target.value = v.substring(0, 2) + "/" + v.substring(2, 4);
    else e.target.value = v;
  });

  form.addEventListener("submit", function (e) {
    const errors = [];

    const card = cardInput.value.trim();
    const cvv  = cvvInput.value.trim();
    const zip  = zipInput.value.trim();
    const expiry = expiryInput.value.trim();

    if (card.length !== 16) errors.push("Η κάρτα πρέπει να έχει 16 ψηφία.");
    if (cvv.length !== 3) errors.push("Το CVV πρέπει να έχει 3 ψηφία.");
    if (zip.length !== 5) errors.push("Ο ΤΚ πρέπει να έχει 5 ψηφία.");
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) errors.push("Λάθος μορφή ημερομηνίας (MM/YY).");

    const cartData = localStorage.getItem(CART_STORAGE_KEY);
    if (!cartData || cartData === "[]") errors.push("Το καλάθι είναι άδειο.");

    if (errors.length > 0) {
      e.preventDefault();
      alert("⚠️ Σφάλματα:\n\n" + errors.join("\n"));
      return;
    }

    hiddenItems.value = cartData;
  });
});

// =========================
// ORDER SUCCESS (finish_order.php) - clear cart + redirect
// =========================
document.addEventListener("DOMContentLoaded", function () {
  const successEl = document.querySelector("[data-order-success='1']");
  if (!successEl) return;

  const userEmail = successEl.getAttribute("data-user-email") || "guest";
  const orderId = successEl.getAttribute("data-order-id") || "";

  // clear carts
  try {
    localStorage.removeItem("at_cart_guest");
    if (userEmail && userEmail !== "guest") {
      localStorage.removeItem("at_cart_" + userEmail);
    }
  } catch (e) {
    // ignore storage errors
  }

  // auto redirect after short delay (gives user feedback)
  if (orderId) {
    setTimeout(() => {
      window.location.href = "order_details.php?id=" + encodeURIComponent(orderId);
    }, 800);
  }
});