<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// 1) Μόνο logged in κάνει checkout
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

$user_email = (string)($_SESSION['user']['email'] ?? '');
if ($user_email === '') {
    $_SESSION['flash'] = "Πρόβλημα session. Κάντε login ξανά.";
    header("Location: login.php");
    exit;
}

// 2) Δέχεται μόνο POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

// 3) Παίρνουμε items από payment.php (hidden input)
$items = (string)($_POST['cart_items'] ?? '');

if ($items === '' || $items === '[]') {
    $_SESSION['flash'] = "Το καλάθι είναι άδειο ή τα δεδομένα παραγγελίας είναι άκυρα.";
    header("Location: cart.php");
    exit;
}

// optional limit
if (strlen($items) > 10000) {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: πολύ μεγάλα δεδομένα παραγγελίας.";
    header("Location: cart.php");
    exit;
}

// 4) Decode + validate JSON items
try {
    $decodedItems = json_decode($items, true, 512, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    $decodedItems = null;
}

if (!is_array($decodedItems) || empty($decodedItems)) {
    $_SESSION['flash'] = "Άκυρα δεδομένα καλαθιού.";
    header("Location: cart.php");
    exit;
}

// 5) Re-calc total (security: δεν εμπιστευόμαστε POST total_price)
$total = 0.0;
foreach ($decodedItems as $it) {
    $price = isset($it['price']) ? (float)$it['price'] : 0.0;
    $qty   = isset($it['quantity']) ? (int)$it['quantity'] : 0;

    if ($price <= 0 || $qty <= 0) continue;
    $total += $price * $qty;
}

if ($total <= 0) {
    $_SESSION['flash'] = "Το καλάθι είναι άδειο.";
    header("Location: cart.php");
    exit;
}

// 6) Shipping fields
$full_name = trim($_POST['full_name'] ?? '');
$address   = trim($_POST['address'] ?? '');
$city      = trim($_POST['city'] ?? '');
$zip       = trim($_POST['zip'] ?? '');

if ($full_name === '' || $address === '' || $city === '' || !preg_match('/^\d{5}$/', $zip)) {
    $_SESSION['flash'] = "Παρακαλώ συμπληρώστε σωστά τα στοιχεία αποστολής.";
    header("Location: payment.php?total=" . urlencode(number_format($total, 2, '.', '')));
    exit;
}

// 7) Insert order (ΔΕΝ αποθηκεύουμε κάρτες)
$sql = "INSERT INTO orders (user_email, total_price, order_items, full_name, shipping_address, city, zip_code)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    $_SESSION['flash'] = "DB error: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}

// ✅ 7 params => types: s d s s s s s
mysqli_stmt_bind_param(
    $stmt,
    "sdsssss",
    $user_email,
    $total,
    $items,
    $full_name,
    $address,
    $city,
    $zip
);

$ok = mysqli_stmt_execute($stmt);
if (!$ok) {
    mysqli_stmt_close($stmt);
    $_SESSION['flash'] = "Σφάλμα βάσης: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}

$order_id = (int)mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// 8) Flash message
$_SESSION['flash'] = "✅ Συγχαρητήρια $full_name! Η παραγγελία σας καταχωρήθηκε και θα σταλεί στη διεύθυνση $address.";

// 9) Success UI page (localStorage clear + redirect handled by main.js)
$pageTitle = "Order Success | AT.COLLECTION";
$loadCartJs = false;

require_once __DIR__ . '/includes/header.php';
?>

<section class="container order-success-wrap" data-order-success="1" data-user-email="<?php echo htmlspecialchars($user_email); ?>" data-order-id="<?php echo (int)$order_id; ?>">
  <div class="order-success-card">
    <h1 class="order-success-title">✅ Η παραγγελία καταχωρήθηκε!</h1>

    <p class="order-success-text">
      Η παραγγελία σας καταχωρήθηκε. Σε λίγο θα μεταφερθείτε στις λεπτομέρειες παραγγελίας.
    </p>

    <div class="order-success-actions">
      <a class="auth-button btn-link" href="order_details.php?id=<?php echo (int)$order_id; ?>">
        Προβολή Παραγγελίας
      </a>
      <a class="auth-link" href="index.php">Επιστροφή στην αρχική</a>
    </div>

    <p class="order-success-small">
      (Αν δεν γίνει αυτόματα μεταφορά, πατήστε “Προβολή Παραγγελίας”.)
    </p>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>