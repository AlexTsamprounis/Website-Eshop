<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// Μόνο logged-in χρήστης
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

$total = $_GET['total'] ?? '0.00';
require_once __DIR__ . '/includes/header.php';
?>

<section id="form">
    <form action="finish_order.php" method="POST" id="payment-form">
        <h2>Ολοκλήρωση Παραγγελίας</h2>

        <p style="text-align:center; font-weight:bold; color:#ff9d00;">
            Ποσό: <?php echo htmlspecialchars($total); ?> €
        </p>

        <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total); ?>">
        <input type="hidden" name="cart_items" id="hidden_items">

        <fieldset>
            <legend>Στοιχεία Αποστολής</legend>
            <input type="text" name="full_name" placeholder="Ονοματεπώνυμο" required>
            <input type="text" name="address" placeholder="Διεύθυνση και Αριθμός" required>
            <input type="text" name="city" placeholder="Πόλη" required>
            <input type="text" name="zip" placeholder="ΤΚ (5 ψηφία)" maxlength="5" required>
        </fieldset>

        <fieldset style="margin-top:20px;">
            <legend>Στοιχεία Κάρτας (Demo)</legend>
            <input type="text" name="card_name" placeholder="Όνομα στην Κάρτα" required>
            <input type="text" name="card_number" placeholder="Αριθμός Κάρτας (16 ψηφία)" maxlength="16" required>

            <div style="display:flex; gap:10px;">
                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" required>
                <input type="text" id="card_cvv" name="card_cvv" placeholder="CVV (3 ψηφία)" maxlength="3" required>
            </div>

            <p style="margin-top:10px; color:#999; font-size:12px;">
                * Demo form. Τα στοιχεία κάρτας δεν αποθηκεύονται στη βάση.
            </p>
        </fieldset>

        <div class="action">
    <button type="submit" id="pay-now">ΠΛΗΡΩΜΗ ΤΩΡΑ</button>
</div>
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ⬅️ ΧΡΗΣΙΜΟΠΟΙΟΥΜΕ ΤΟ ΙΔΙΟ KEY ΠΟΥ ΕΧΕΙ ΤΟ cart.js
    const userEmail = window.currentUserEmail || 'guest';
    const CART_STORAGE_KEY = 'at_cart_' + userEmail;

    const form = document.getElementById('payment-form');
    const expiryInput = document.getElementById('card_expiry');

    // expiry MM/YY
    expiryInput.addEventListener('input', e => {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length >= 2) e.target.value = v.substring(0,2) + '/' + v.substring(2,4);
        else e.target.value = v;
    });

    // submit
    form.addEventListener('submit', function (e) {
        console.log("SUBMIT FIRED");
        const errors = [];

        const card = form.card_number.value;
        const cvv  = document.getElementById('card_cvv').value;
        const zip  = form.zip.value;
        const expiry = expiryInput.value;

        if (card.length !== 16) errors.push("Η κάρτα πρέπει να έχει 16 ψηφία.");
        if (cvv.length !== 3) errors.push("Το CVV πρέπει να έχει 3 ψηφία.");
        if (zip.length !== 5) errors.push("Ο ΤΚ πρέπει να έχει 5 ψηφία.");

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
            errors.push("Λάθος μορφή ημερομηνίας (MM/YY).");
        }

        const cartData = localStorage.getItem(CART_STORAGE_KEY);
        if (!cartData || cartData === '[]') {
            errors.push("Το καλάθι είναι άδειο.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert("⚠️ Σφάλματα:\n\n" + errors.join("\n"));
            return;
        }

        document.getElementById('hidden_items').value = cartData;
    });
});
</script>

<style>
#form {
    position: relative;
    z-index: 9999;
}

#pay-now {
    position: relative;
    z-index: 10000;
    pointer-events: auto;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>