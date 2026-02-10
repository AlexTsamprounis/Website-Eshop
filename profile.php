<?php
session_start();
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε το προφίλ σας.";
    header("Location: login.php");
    exit;
}

$pageTitle  = "My Profile | AT.COLLECTION";
$pageClass  = "page-profile";
$loadCartJs = true;


$user_email = $_SESSION['user']['email'];

// 1) Total orders + lifetime spend
$sql1 = "SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_price), 0) AS total_spent
         FROM orders
         WHERE user_email = ?";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt1, "s", $user_email);
mysqli_stmt_execute($stmt1);

mysqli_stmt_bind_result($stmt1, $total_orders_raw, $total_spent_raw);
mysqli_stmt_fetch($stmt1);
mysqli_stmt_close($stmt1);

$total_orders = (int)($total_orders_raw ?? 0);
$total_spent  = (float)($total_spent_raw ?? 0);

// 2) Last order
$sql2 = "SELECT id, total_price, order_date
         FROM orders
         WHERE user_email = ?
         ORDER BY order_date DESC
         LIMIT 1";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "s", $user_email);
mysqli_stmt_execute($stmt2);

mysqli_stmt_bind_result($stmt2, $last_id, $last_total, $last_date);
$has_last = mysqli_stmt_fetch($stmt2);
mysqli_stmt_close($stmt2);

$last_order = $has_last ? [
  'id' => (int)$last_id,
  'total_price' => (float)$last_total,
  'order_date' => (string)$last_date
] : null;

require_once __DIR__ . '/includes/header.php';
?>

<section class="container profile-wrap">
  <h1 class="profile-title">My Profile</h1>

  <div class="profile-card">
    <p><strong>Όνομα:</strong>
      <?php echo htmlspecialchars(($_SESSION['user']['firstname'] ?? '') . " " . ($_SESSION['user']['lastname'] ?? '')); ?>
    </p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
  </div>

  <div class="profile-stats">
    <div class="profile-stat">
      <div class="profile-label">Total Orders</div>
      <div class="profile-value"><?php echo $total_orders; ?></div>
    </div>

    <div class="profile-stat">
      <div class="profile-label">Lifetime Spend</div>
      <div class="profile-value"><?php echo number_format($total_spent, 2); ?> €</div>
    </div>

    <div class="profile-stat profile-stat--wide">
      <div class="profile-label">Last Order</div>

      <?php if (!$last_order): ?>
        <div class="profile-muted profile-mt-8">Δεν υπάρχει ακόμη παραγγελία.</div>
      <?php else: ?>
        <div class="profile-mt-8">
          <strong>Order #<?php echo (int)$last_order['id']; ?></strong>
          <span class="profile-muted profile-date">
            — <?php echo htmlspecialchars($last_order['order_date']); ?>
          </span>
        </div>

        <div class="profile-amount profile-mt-6">
          Amount:
          <span class="profile-amount-highlight">
            <?php echo number_format((float)$last_order['total_price'], 2); ?> €
          </span>
        </div>

        <div class="profile-mt-12">
          <a href="order_details.php?id=<?php echo (int)$last_order['id']; ?>"
             class="auth-button btn-link">
            View Last Order Details
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="profile-actions">
    <a href="my_orders.php" class="auth-button btn-link">📦 My Orders</a>
    <a href="cart.php" class="auth-button btn-link">🛒 Go to Cart</a>
    <a href="index.php" class="auth-button btn-link">🏠 Home</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>