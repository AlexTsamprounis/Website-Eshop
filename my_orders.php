<?php
session_start();
$pageTitle  = "My Orders | AT.COLLECTION";
$pageClass  = "page-orders";
$loadCartJs = true;
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε τις παραγγελίες σας.";
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];

$sql = "SELECT id, total_price, order_items, order_date, full_name, shipping_address, city, zip_code
        FROM orders
        WHERE user_email = ?
        ORDER BY order_date DESC";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("DB error: " . htmlspecialchars(mysqli_error($conn)));
}

mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);

/**
 * InfinityFree-safe: NO mysqli_stmt_get_result()
 * Fetch with bind_result + fetch loop.
 */
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $total_price,
    $order_items,
    $order_date,
    $full_name,
    $shipping_address,
    $city,
    $zip_code
);

$orders = [];
while (mysqli_stmt_fetch($stmt)) {
    $orders[] = [
        'id' => (int)$id,
        'total_price' => (float)$total_price,
        'order_items' => (string)$order_items,
        'order_date' => (string)$order_date,
        'full_name' => (string)$full_name,
        'shipping_address' => (string)$shipping_address,
        'city' => (string)$city,
        'zip_code' => (string)$zip_code,
    ];
}
mysqli_stmt_close($stmt);

require_once __DIR__ . '/includes/header.php';
?>

<section class="container orders-wrap">
  <h1 class="orders-title">📦 My Orders</h1>
  <p class="orders-subtitle">Εδώ βλέπεις το ιστορικό παραγγελιών σου.</p>

  <?php if (count($orders) === 0): ?>
    <div class="orders-empty">
      Δεν υπάρχουν παραγγελίες ακόμα.
    </div>
  <?php else: ?>

    <?php foreach ($orders as $row): ?>
      <?php
        // order_items είναι JSON string από localStorage
        $itemsRaw = $row['order_items'] ?? '';
        $itemsArr = json_decode($itemsRaw, true);
        if (!is_array($itemsArr)) $itemsArr = []; // safety
      ?>

      <div class="orders-card">
        <div class="orders-card-top">
          <div>
            <div class="orders-card-id">
              Order #<?php echo (int)$row['id']; ?>
            </div>
            <div class="orders-card-date">
              Date: <?php echo htmlspecialchars($row['order_date']); ?>
            </div>
          </div>

          <div class="orders-card-right">
            <div class="orders-total">
              Total:
              <span class="orders-total-amount">
                <?php echo number_format((float)$row['total_price'], 2); ?> €
              </span>
            </div>

            <a href="order_details.php?id=<?php echo (int)$row['id']; ?>"
               class="auth-button btn-link">
              View Details
            </a>
          </div>
        </div>

        <hr class="orders-hr">

        <div class="orders-shipping">
          <strong>Shipping:</strong>
          <?php
            echo htmlspecialchars(
              ($row['full_name'] ?? '') .
              " — " .
              ($row['shipping_address'] ?? '') .
              ", " .
              ($row['city'] ?? '') .
              " " .
              ($row['zip_code'] ?? '')
            );
          ?>
        </div>

        <div class="orders-table-wrap">
          <table class="orders-table">
            <thead>
              <tr>
                <th class="orders-th orders-th-left">Product</th>
                <th class="orders-th orders-th-center">Qty</th>
                <th class="orders-th orders-th-center">Price</th>
                <th class="orders-th orders-th-center">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($itemsArr) === 0): ?>
                <tr>
                  <td colspan="4" class="orders-td orders-td-empty">
                    Δεν υπάρχουν αποθηκευμένα items (ή το JSON δεν διαβάζεται σωστά).
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($itemsArr as $it): ?>
                  <?php
                    $name = isset($it['name']) ? (string)$it['name'] : '';
                    $price = isset($it['price']) ? (float)$it['price'] : 0;
                    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                    if ($qty < 0) $qty = 0;
                    $subtotal = $price * $qty;
                  ?>
                  <tr class="orders-tr">
                    <td class="orders-td orders-td-left"><?php echo htmlspecialchars($name); ?></td>
                    <td class="orders-td orders-td-center"><?php echo (int)$qty; ?></td>
                    <td class="orders-td orders-td-center"><?php echo number_format((float)$price, 2); ?> €</td>
                    <td class="orders-td orders-td-center"><?php echo number_format((float)$subtotal, 2); ?> €</td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>

  <?php endif; ?>

  <div class="orders-back">
    <a href="profile.php" class="auth-button btn-link">⬅ Back to Profile</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>