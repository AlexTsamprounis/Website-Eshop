<?php
session_start();
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

$total = isset($_POST['total']) ? (float)$_POST['total'] : 0;
$items = $_POST['items'] ?? '';

$user_email = $_SESSION['user']['email'];

if ($total <= 0 || $items === '') {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: άδειο καλάθι ή άκυρα δεδομένα.";
    header("Location: cart.php");
    exit;
}

// (προαιρετικό) basic limit για να μην περάσει τεράστιο string
if (strlen($items) > 10000) {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: πολύ μεγάλα δεδομένα παραγγελίας.";
    header("Location: cart.php");
    exit;
}

$sql = "INSERT INTO orders (user_email, total_price, order_items) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash'] = "DB error: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "sds", $user_email, $total, $items);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash'] = "✅ Η παραγγελία σας καταχωρήθηκε επιτυχώς!";
    header("Location: finish_order.php");
    exit;
} else {
    $_SESSION['flash'] = "DB error: " . mysqli_error($conn);
    header("Location: cart.php");
    exit;
}