<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// 1) Μόνο logged in κάνει checkout
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

// 2) Δέχεται μόνο POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

$user_email = $_SESSION['user']['email'];

// 3) Παίρνουμε δεδομένα από payment.php
$total = isset($_POST['total_price']) ? (float)$_POST['total_price'] : 0;
$items = $_POST['cart_items'] ?? '';

$full_name = trim($_POST['full_name'] ?? '');
$address   = trim($_POST['address'] ?? '');
$city      = trim($_POST['city'] ?? '');
$zip       = trim($_POST['zip'] ?? '');
$card_name   = trim($_POST['card_name'] ?? '');
$card_number = trim($_POST['card_number'] ?? '');
$card_expiry = trim($_POST['card_expiry'] ?? '');
// 4) Basic validation
if ($total <= 0 || $items === '' || $items === '[]') {
    $_SESSION['flash'] = "Το καλάθι είναι άδειο ή τα δεδομένα παραγγελίας είναι άκυρα.";
    header("Location: cart.php");
    exit;
}

if ($full_name === '' || $address === '' || $city === '' || !preg_match('/^\d{5}$/', $zip)) {
    $_SESSION['flash'] = "Παρακαλώ συμπληρώστε σωστά τα στοιχεία αποστολής.";
    header("Location: payment.php?total=" . urlencode(number_format($total, 2, '.', '')));
    exit;
}

// 5) Insert: ΑΠΟΘΗΚΕΥΟΥΜΕ shipping στοιχεία + items + total
//    Δεν αποθηκεύουμε κάρτες (παρότι υπάρχουν πεδία).
$sql = "INSERT INTO orders (user_email, total_price, order_items, full_name, shipping_address, city, zip_code, card_name, card_number, card_expiry)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    $_SESSION['flash'] = "DB error: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "sdssssssss",
    $user_email,
    $total,
    $items,
    $full_name,
    $address,
    $city,
    $zip,
    $card_name,
    $card_number,
    $card_expiry
);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['flash'] = "Σφάλμα βάσης: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}

// 6) Flash message για home
$_SESSION['flash'] = "✅ Συγχαρητήρια $full_name! Η παραγγελία σας καταχωρήθηκε και θα σταλεί στη διεύθυνση $address.";

// 7) Success page που καθαρίζει localStorage και πάει TEST2.php
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <meta name="robots" content="noindex,nofollow">
</head>
<body>
  <script>
    (function () {
      const userEmail = <?php echo json_encode($user_email); ?>;
      localStorage.removeItem('at_cart_guest');
      localStorage.removeItem('at_cart_' + userEmail);
      window.location.href = 'TEST2.php?order=success';
    })();
  </script>

  <p>Η παραγγελία καταχωρήθηκε. Μεταφορά στην αρχική...</p>
</body>
</html>