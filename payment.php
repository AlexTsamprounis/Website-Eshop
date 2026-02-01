<?php
session_start();
$pageTitle  = "Order Details | AT.COLLECTION";
$pageClass  = "page-order-details";
$loadCartJs = true;
require_once __DIR__ . '/db_connect.php';

// Μόνο logged-in χρήστης
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

$pageTitle = "Payment | AT.COLLECTION";

// Total από cart.js redirect (για εμφάνιση μόνο)
$total = $_GET['total'] ?? '0.00';
$totalFloat = (float)$total;
if ($totalFloat < 0) $totalFloat = 0;

require_once __DIR__ . '/includes/header.php';
?>

<section id="form">
  <form action="finish_order.php" method="POST" id="payment-form" autocomplete="on">
    <h2>Ολοκλήρωση Παραγγελίας</h2>

    <p class="payment-amount">
      Ποσό: <?php echo htmlspecialchars(number_format($totalFloat, 2, '.', '')); ?> €
    </p>

    <!-- Δεν το εμπιστευόμαστε στο backend, το κρατάμε για συμβατότητα/UX -->
    <input type="hidden" name="total_price" value="<?php echo htmlspecialchars(number_format($totalFloat, 2, '.', '')); ?>">
    <input type="hidden" name="cart_items" id="hidden_items" value="">

    <fieldset>
      <legend>Στοιχεία Αποστολής</legend>
      <input type="text" name="full_name" placeholder="Ονοματεπώνυμο" required>
      <input type="text" name="address" placeholder="Διεύθυνση και Αριθμός" required>
      <input type="text" name="city" placeholder="Πόλη" required>
      <input type="text" name="zip" placeholder="ΤΚ (5 ψηφία)" maxlength="5" required>
    </fieldset>

    <fieldset class="payment-card-fieldset">
      <legend>Στοιχεία Κάρτας (demo)</legend>
      <input type="text" name="card_name" placeholder="Όνομα στην Κάρτα" required>
      <input type="text" name="card_number" placeholder="Αριθμός Κάρτας (16 ψηφία)" maxlength="16" inputmode="numeric" required>

      <div class="payment-card-row">
        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" inputmode="numeric" required>
        <input type="text" id="card_cvv" name="card_cvv" placeholder="CVV (3 ψηφία)" maxlength="3" inputmode="numeric" required>
      </div>

      <p class="payment-note">
        * Δεν αποθηκεύουμε στοιχεία κάρτας. (Demo form για την εργασία)
      </p>
    </fieldset>

    <div class="action">
      <button type="submit" id="pay-now">ΠΛΗΡΩΜΗ ΤΩΡΑ</button>
    </div>
  </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>