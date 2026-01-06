
document.addEventListener("DOMContentLoaded", function () {
   console.log("Test2.js loaded");
  // =========================
  // 1) Textarea character counter
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
  // 2) Form validation 
  // =========================
  const form = document.querySelector("#form form");
  if (!form) return; // σημαντικό: στις άλλες σελίδες δεν υπάρχει φόρμα

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    clearErrors(form);

    const firstname = document.querySelector("#firstname");
    const lastname = document.querySelector("#lastname");
    const gender = document.querySelector("#formGender");
    const email = document.querySelector("#emailAdress");
    const password = document.querySelector("#formPassword");
    const agreeTerms = document.querySelector("#agreeTerms");
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

    // Newsletter radio (YES/NO) - αν το θες υποχρεωτικό, άστο έτσι
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

    // Success
    showSuccess(form, "✅ Επιτυχής υποβολή! Ευχαριστούμε 🙂");
    form.reset();

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

    // checkbox: πιο σταθερό να μπει μέσα στο parent
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

  function showSuccess(root, message) {
    const box = document.createElement("div");
    box.className = "form-success";
    box.textContent = message;
    root.appendChild(box);
  }

  function isValidEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }
});




