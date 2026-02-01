<?php
session_start();
$pageTitle  = "Order Details | AT.COLLECTION";
$pageClass  = "page-order-details";
$loadCartJs = true;
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε τα στοιχεία παραγγελίας.";
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    $_SESSION['flash'] = "Μη έγκυρη παραγγελία.";
    header("Location: my_orders.php");
    exit;
}

/**
 * Ασφάλεια: παίρνουμε ΜΟΝΟ παραγγελία που ανήκει στον user
 */
$sql = "SELECT id, user_email, total_price, order_items, order_date, full_name, shipping_address, city, zip_code
        FROM orders
        WHERE id = ? AND user_email = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    $_SESSION['flash'] = "DB error: " . mysqli_error($conn);
    header("Location: my_orders.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "is", $order_id, $user_email);
mysqli_stmt_execute($stmt);

/**
 * InfinityFree-safe: NO mysqli_stmt_get_result()
 * bind_result + fetch.
 */
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $db_user_email,
    $total_price,
    $order_items,
    $order_date,
    $full_name,
    $shipping_address,
    $city,
    $zip_code
);

$found = mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$found) {
    $_SESSION['flash'] = "Η παραγγελία δεν βρέθηκε (ή δεν σας ανήκει).";
    header("Location: my_orders.php");
    exit;
}

$order = [
    'id' => (int)$id,
    'user_email' => (string)$db_user_email,
    'total_price' => (float)$total_price,
    'order_items' => (string)$order_items,
    'order_date' => (string)$order_date,
    'full_name' => (string)$full_name,
    'shipping_address' => (string)$shipping_address,
    'city' => (string)$city,
    'zip_code' => (string)$zip_code,
];

// Decode items JSON
$itemsRaw = $order['order_items'] ?? '';
$itemsArr = json_decode($itemsRaw, true);
if (!is_array($itemsArr)) $itemsArr = [];

require_once __DIR__ . '/includes/header.php';
?>

<section class="container od-wrap">
  <h1 class="od-title">🧾 Order Details</h1>

  <div class="od-card">
    <div class="od-top">
      <div>
        <div class="od-id">Order #<?php echo (int)$order['id']; ?></div>
        <div class="od-date">
          Date: <?php echo htmlspecialchars($order['order_date']); ?>
        </div>
      </div>

      <div class="od-right">
        <div class="od-total">
          Total:
          <span class="od-total-amount">
            <?php echo number_format((float)$order['total_price'], 2); ?> €
          </span>
        </div>
      </div>
    </div>

    <hr class="od-hr">

    <div class="od-shipping">
      <strong>Shipping To:</strong><br>
      <?php echo htmlspecialchars($order['full_name'] ?? ''); ?><br>
      <?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?><br>
      <?php echo htmlspecialchars(($order['city'] ?? '') . " " . ($order['zip_code'] ?? '')); ?>
    </div>

    <hr class="od-hr">

    <h3 class="od-items-title">Items</h3>

    <div class="od-table-wrap">
      <table class="od-table">
        <thead>
          <tr>
            <th class="od-th od-th-left">Product</th>
            <th class="od-th od-th-center">Qty</th>
            <th class="od-th od-th-center">Price</th>
            <th class="od-th od-th-center">Subtotal</th>
          </tr>
        </thead>
        <tbody>
        <?php if (count($itemsArr) === 0): ?>
          <tr>
            <td colspan="4" class="od-td od-td-empty">
              Δεν υπάρχουν αποθηκευμένα items (ή το JSON δεν διαβάζεται σωστά).
            </td>
          </tr>
        <?php else: ?>
          <?php
            $calcTotal = 0;
            foreach ($itemsArr as $it):
              $name = isset($it['name']) ? (string)$it['name'] : '';
              $price = isset($it['price']) ? (float)$it['price'] : 0;
              $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
              if ($qty < 0) $qty = 0;

              $subtotal = $price * $qty;
              $calcTotal += $subtotal;
          ?>
            <tr class="od-tr">
              <td class="od-td od-td-left"><?php echo htmlspecialchars($name); ?></td>
              <td class="od-td od-td-center"><?php echo (int)$qty; ?></td>
              <td class="od-td od-td-center"><?php echo number_format((float)$price, 2); ?> €</td>
              <td class="od-td od-td-center"><?php echo number_format((float)$subtotal, 2); ?> €</td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if (count($itemsArr) > 0): ?>
      <div class="od-calculated-total">
        Calculated Total:
        <span class="od-total-amount">
          <?php echo number_format((float)$calcTotal, 2); ?> €
        </span>
      </div>
    <?php endif; ?>
  </div>

  <div class="od-actions">
    <a href="my_orders.php" class="auth-button btn-link">⬅ Back to My Orders</a>
    <a href="profile.php" class="auth-button btn-link">👤 Profile</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>