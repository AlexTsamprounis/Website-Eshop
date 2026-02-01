<?php
session_start();
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να ολοκληρώσετε την αγορά.";
    header("Location: login.php");
    exit;
}

$user_email = (string)($_SESSION['user']['email'] ?? '');

// Δέχεται μόνο POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

$total = isset($_POST['total']) ? (float)$_POST['total'] : 0.0;
$items = (string)($_POST['items'] ?? '');

if ($total <= 0 || $items === '' || $items === '[]') {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: άδειο καλάθι ή άκυρα δεδομένα.";
    header("Location: cart.php");
    exit;
}

// limit για να μην περνάει τεράστιο string
if (strlen($items) > 10000) {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: πολύ μεγάλα δεδομένα παραγγελίας.";
    header("Location: cart.php");
    exit;
}

// ✅ Validate ότι είναι JSON array
$decoded = json_decode($items, true);
if (!is_array($decoded)) {
    $_SESSION['flash'] = "Κάτι πήγε λάθος: τα items δεν είναι έγκυρο JSON.";
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

try {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // ✅ πάρε order id για redirect
    $order_id = (int)mysqli_insert_id($conn);

    $_SESSION['flash'] = "✅ Η παραγγελία σας καταχωρήθηκε επιτυχώς!";
    header("Location: finish_order.php?id=" . $order_id);
    exit;

} catch (mysqli_sql_exception $e) {
    mysqli_stmt_close($stmt);
    $_SESSION['flash'] = "DB error: Σφάλμα αποθήκευσης παραγγελίας.";
    header("Location: cart.php");
    exit;
}