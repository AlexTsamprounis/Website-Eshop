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

// Total από cart.js redirect
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

    <input type="hidden" name="total_price" value="<?php echo htmlspecialchars(number_format($totalFloat, 2, '.', '')); ?>">
    <input type="hidden" name="cart_items" id="hidden_items" value="">

    <fieldset>
      <legend>Στοιχεία Αποστολής</legend>
      <input type="text" name="full_name" id="full_name" placeholder="Πλήρες Ονοματεπώνυμο (π.χ. John Doe)" 
             pattern="^[a-zA-Z]+ [a-zA-Z]+$" title="Εισάγετε ακριβώς 2 λέξεις με λατινικούς χαρακτήρες." required>
      
      <input type="text" name="address" id="address" placeholder="Διεύθυνση (π.χ. Ermou 10)" 
             pattern="^([a-zA-Z]+|[a-zA-Z]+ [a-zA-Z]+) \d+$" 
             title="Εισάγετε το όνομα του δρόμου (1-2 λέξεις) και τον αριθμό (π.χ. Ermou 10)." required>
      
      <input type="text" name="city" placeholder="Πόλη" required>
      <input type="text" name="zip" placeholder="ΤΚ (5 ψηφία)" maxlength="5" pattern="\d{5}" title="Ο ΤΚ πρέπει να είναι 5 ψηφία" required>
    </fieldset>

    <fieldset class="payment-card-fieldset">
      <legend>Στοιχεία Κάρτας</legend>
      <input type="text" name="card_name" id="card_name" placeholder="Όνομα στην Κάρτα (Λατινικά)" 
             pattern="^[a-zA-Z]+ [a-zA-Z]+$" title="Εισάγετε το όνομα που αναγράφεται στην κάρτα με λατινικούς χαρακτήρες." required>
      
      <input type="text" name="card_number" id="card_number" placeholder="Αριθμός Κάρτας (16 ψηφία)" 
             maxlength="16" minlength="16" inputmode="numeric" required>

      <div class="payment-card-row">
        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" 
               maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" title="Μορφή MM/YY (π.χ. 12/26)" inputmode="numeric" required>
        <input type="text" id="card_cvv" name="card_cvv" placeholder="CVV (3 ψηφία)" 
               maxlength="3" minlength="3" inputmode="numeric" required>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const fullName = document.getElementById('full_name');
    const address = document.getElementById('address');
    const cardName = document.getElementById('card_name');
    const cardExpiry = document.getElementById('card_expiry');
    const cardNumber = document.getElementById('card_number');
    const cardCvv = document.getElementById('card_cvv');

    // 1. Αυτόματο "/" στην ημερομηνία
    cardExpiry.addEventListener('input', function() {
        let val = this.value.replace(/\D/g, ''); 
        if (val.length >= 2) {
            this.value = val.substring(0, 2) + '/' + val.substring(2, 4);
        } else {
            this.value = val;
        }
    });

    // 2. Περιορισμός κειμένων σε Λατινικά (A-Z)
    [fullName, cardName].forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });

    // 3. Περιορισμός Διεύθυνσης (Γράμματα, Κενά, Νούμερα)
    address.addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '');
    });

    // 4. Περιορισμός Κάρτας & CVV σε μόνο νούμερα
    [cardNumber, cardCvv].forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });

    // 5. Έλεγχος αν η κάρτα είναι ΕΝΕΡΓΗ κατά το Submit
    form.addEventListener('submit', function(e) {
        const expiryVal = cardExpiry.value;
        if (!/^\d{2}\/\d{2}$/.test(expiryVal)) return;

        const parts = expiryVal.split('/');
        const expMonth = parseInt(parts[0], 10);
        const expYear = parseInt("20" + parts[1], 10);

        const now = new Date();
        const currentMonth = now.getMonth() + 1; // getMonth() είναι 0-11
        const currentYear = now.getFullYear();

        if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
            alert("Σφάλμα: Η κάρτα έχει λήξει! Παρακαλώ χρησιμοποιήστε μια ενεργή κάρτα.");
            e.preventDefault(); 
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>