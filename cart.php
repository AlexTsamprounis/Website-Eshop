<?php
session_start();
$pageTitle = "AT.COLLECTION | Cart";
$pageClass = "page-cart";
$loadCartJs = true;
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/includes/header.php';
?>

<h2 class="cart-title">ΤΟ ΚΑΛΑΘΙ ΜΟΥ</h2>



<div class="cart-table-wrapper">
  <table class="cart-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price per Product</th>
        <th>Total Price</th>
        <th>Remove</th>
      </tr>
    </thead>

    <tbody id="cart-items-container">
      <tr>
        <td colspan="5" class="cart-empty">Φόρτωση καλαθιού...</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="cart-summary">
  <h3 class="cart-total">
    ΓΕΝΙΚΟ ΣΥΝΟΛΟ:
    <span id="grand-total">0.00</span> €
  </h3>

  <?php if (!empty($_SESSION['user'])): ?>
    <button type="button" class="cart-checkout-btn" id="checkout-btn">
      ΟΛΟΚΛΗΡΩΣΗ ΑΓΟΡΑΣ
    </button>
  <?php else: ?>
    <div class="cart-login-warning">
      <strong>Προσοχή:</strong> Για να ολοκληρώσετε την αγορά σας πρέπει να είστε συνδεδεμένοι.<br>
      <a class="cart-login-link" href="login.php">Συνδεθείτε εδώ</a> ή κάντε εγγραφή.
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>