<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// 1) Μόνο logged-in
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε λεπτομέρειες παραγγελίας.";
    header("Location: login.php");
    exit;
}

$user_email = (string)($_SESSION['user']['email'] ?? '');

// 2) Παίρνουμε order id
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    $_SESSION['flash'] = "Μη έγκυρο Order ID.";
    header("Location: my_orders.php");
    exit;
}

// 3) Φέρνουμε ΜΟΝΟ παραγγελία του συγκεκριμένου χρήστη (security)
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

// ✅ InfinityFree-safe: bind_result + fetch (NO get_result)
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

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    $_SESSION['flash'] = "Η παραγγελία δεν βρέθηκε ή δεν έχετε πρόσβαση.";
    header("Location: my_orders.php");
    exit;
}

mysqli_stmt_close($stmt);

// 4) Decode items (από localStorage JSON)
$items_raw = (string)($order_items ?? '');
$items = json_decode($items_raw, true);
$items_ok = is_array($items);

require_once __DIR__ . '/includes/header.php';
?>

<section class="best-sellers od2-wrap">
  <div class="best-sellers__header">
    <h2 class="best-sellers__title">Order Details</h2>
  </div>

  <div class="od2-container">

    <div class="od2-topbar">
      <a href="my_orders.php" class="auth-link">← Πίσω στις Παραγγελίες</a>

      <div class="od2-meta">
        Order #<strong class="od2-highlight"><?php echo (int)$id; ?></strong>
        <span class="od2-sep">|</span>
        Ημερομηνία: <strong><?php echo htmlspecialchars((string)$order_date); ?></strong>
      </div>
    </div>

    <article class="od2-card">
      <div class="od2-card-head">
        <div class="od2-ship">
          <h3 class="od2-h3">Αποστολή</h3>
          <div><strong>Όνομα:</strong> <?php echo htmlspecialchars((string)$full_name); ?></div>
          <div><strong>Διεύθυνση:</strong> <?php echo htmlspecialchars((string)$shipping_address); ?></div>
          <div><strong>Πόλη:</strong> <?php echo htmlspecialchars((string)$city); ?></div>
          <div><strong>ΤΚ:</strong> <?php echo htmlspecialchars((string)$zip_code); ?></div>
          <div class="od2-email">
            Email παραγγελίας: <?php echo htmlspecialchars((string)$db_user_email); ?>
          </div>
        </div>

        <div class="od2-totalbox">
          <h3 class="od2-h3">Σύνολο</h3>
          <div class="od2-total">
            <?php echo number_format((float)$total_price, 2); ?> €
          </div>
        </div>
      </div>

      <hr class="od2-hr">

      <h3 class="od2-h3 od2-h3--items">Προϊόντα</h3>

      <?php if ($items_ok && count($items) > 0): ?>
        <?php
          $calc_total = 0.0;
          foreach ($items as $it) {
            $p = isset($it['price']) ? (float)$it['price'] : 0.0;
            $q = isset($it['quantity']) ? (int)$it['quantity'] : 0;
            $calc_total += $p * $q;
          }
        ?>

        <div class="od2-table-wrap">
          <table class="od2-table">
            <thead>
              <tr>
                <th class="od2-th od2-th-left">Product</th>
                <th class="od2-th od2-th-center">Qty</th>
                <th class="od2-th od2-th-right">Price</th>
                <th class="od2-th od2-th-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <?php
                  $name = isset($it['name']) ? (string)$it['name'] : '';
                  $qty  = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                  $price = isset($it['price']) ? (float)$it['price'] : 0.0;
                  $subtotal = $price * $qty;
                ?>
                <tr class="od2-tr">
                  <td class="od2-td od2-td-left"><?php echo htmlspecialchars($name); ?></td>
                  <td class="od2-td od2-td-center"><?php echo $qty; ?></td>
                  <td class="od2-td od2-td-right"><?php echo number_format($price, 2); ?> €</td>
                  <td class="od2-td od2-td-right"><?php echo number_format($subtotal, 2); ?> €</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="od2-summary-row">
          <div class="od2-summary">
            <div class="od2-summary-line">
              <span>Σύνολο από items:</span>
              <strong><?php echo number_format((float)$calc_total, 2); ?> €</strong>
            </div>

            <div class="od2-summary-line od2-summary-line--big">
              <span>Σύνολο (DB):</span>
              <strong class="od2-highlight"><?php echo number_format((float)$total_price, 2); ?> €</strong>
            </div>

            <?php if (abs((float)$calc_total - (float)$total_price) > 0.01): ?>
              <div class="od2-note">
                * Σημείωση: μικρή διαφορά total (items vs DB). Συνήθως λόγω rounding.
              </div>
            <?php endif; ?>
          </div>
        </div>

      <?php else: ?>
        <div class="od2-json-error">
          Δεν ήταν δυνατή η ανάγνωση των items (raw JSON):<br>
          <code class="od2-code"><?php echo htmlspecialchars($items_raw); ?></code>
        </div>
      <?php endif; ?>

    </article>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>