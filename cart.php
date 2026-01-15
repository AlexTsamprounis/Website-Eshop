<?php
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Ειδικό title για cart (προαιρετικό): αν θες, το αφήνουμε ως έχει στο header.php -->

<!-- Το cart.js περιμένει renderCart(), οπότε το καλούμε με ασφάλεια όταν φορτώσει η σελίδα -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof renderCart === 'function') {
      renderCart();
    }
    if (typeof updateCartCount === 'function') {
      updateCartCount();
    }
  });
</script>

<main class="main-content" style="padding: 50px; min-height: 400px;">
    <h2 style="color: #ff9d00; border-bottom: 2px solid #ff9d00; padding-bottom: 10px;">ΤΟ ΚΑΛΑΘΙ ΜΟΥ</h2>

    <table style="width: 100%; color: white; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="text-align: left; background: #222;">
                <th style="padding: 15px;">Product</th>
                <th>Quantity</th>
                <th>Price per Product</th>
                <th>Total Price</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody id="cart-items-container">
            <!-- γεμίζει από cart.js -->
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 40px; background: #222; padding: 20px;">
        <h3 style="font-size: 28px;">
            ΓΕΝΙΚΟ ΣΥΝΟΛΟ:
            <span id="grand-total" style="color: #ff9d00;">0</span> €
        </h3>

        <br>

        <?php if (!empty($_SESSION['user'])): ?>
            <button
                onclick="checkout()"
                class="auth-button"
                style="background: #ff9d00; padding: 15px 40px; border:none; cursor:pointer; color: black; font-weight: bold;">
                ΟΛΟΚΛΗΡΩΣΗ ΑΓΟΡΑΣ
            </button>
        <?php else: ?>
            <div style="background: #333; color: #fff; padding: 15px; border-left: 5px solid #ff9d00; display: inline-block; text-align: left;">
                <strong>Προσοχή:</strong> Για να ολοκληρώσετε την αγορά σας πρέπει να είστε συνδεδεμένοι.<br>
                <a href="login.php" style="color: #ff9d00; font-weight: bold; text-decoration: underline;">Συνδεθείτε εδώ</a>
                ή κάντε εγγραφή.
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>