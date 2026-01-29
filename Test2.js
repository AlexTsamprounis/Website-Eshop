document.addEventListener("DOMContentLoaded", function () {
  console.log("Test2.js loaded");

    // =========================
  // 0) MOBILE HAMBURGER (GLOBAL - runs on all pages)
  // =========================
  const headerEl = document.querySelector("header.headline");
  const menuToggle = document.querySelector(".menu-toggle");

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

  // Κλειδί: Αν ΔΕΝ υπάρχουν τα πεδία του register, ΔΕΝ πειράζουμε κανένα submit (payment/cart κλπ)
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


document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("registerModal");
  const closeBtn = document.getElementById("btnCloseRegister");

  // Θα το δέσουμε με κουμπιά/links που έχουν data-open="register"
  const openers = document.querySelectorAll('[data-open="register"]');

  if (!modal || !closeBtn) return;

  function openRegister() {
    modal.classList.add("open");
    modal.setAttribute("aria-hidden", "false");
  }

  function closeRegister() {
    modal.classList.remove("open");
    modal.setAttribute("aria-hidden", "true");
  }

  openers.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      openRegister();
    });
  });

  closeBtn.addEventListener("click", closeRegister);

  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeRegister();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeRegister();
  });

  // optional: αν έρθεις από login με ?register=1 ανοίγει αυτόματα
  const params = new URLSearchParams(window.location.search);
  if (params.get("register") === "1") {
    openRegister();

    // Kαθαρίζει το URL για να μην ανοίγει στο refresh
    const url = new URL(window.location.href);
    url.searchParams.delete("register");
    window.history.replaceState({}, document.title, url.pathname + url.search + url.hash);
  }
});