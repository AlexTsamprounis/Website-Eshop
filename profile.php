<?php
session_start();
require_once __DIR__ . '/db_connect.php';

if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε το προφίλ σας.";
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];

// 1) Total orders + lifetime spend
$sql1 = "SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_price), 0) AS total_spent
         FROM orders
         WHERE user_email = ?";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt1, "s", $user_email);
mysqli_stmt_execute($stmt1);
$res1 = mysqli_stmt_get_result($stmt1);
$stats = mysqli_fetch_assoc($res1);

$total_orders = (int)($stats['total_orders'] ?? 0);
$total_spent  = (float)($stats['total_spent'] ?? 0);

// 2) Last order
$sql2 = "SELECT id, total_price, order_date
         FROM orders
         WHERE user_email = ?
         ORDER BY order_date DESC
         LIMIT 1";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "s", $user_email);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
$last_order = ($res2 && mysqli_num_rows($res2) > 0) ? mysqli_fetch_assoc($res2) : null;

require_once __DIR__ . '/includes/header.php';
?>

<section class="container" style="padding:30px;">
    <h1 style="color:#ff9d00;">My Profile</h1>

    <div style="background:#1f1f1f; padding:20px; border-radius:12px; margin-top:20px; color:white;">
        <p><strong>Όνομα:</strong> <?php echo htmlspecialchars($_SESSION['user']['firstname'] . " " . $_SESSION['user']['lastname']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
    </div>

    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top:20px;">
        <div style="flex:1; min-width:220px; background:#1f1f1f; padding:18px; border-radius:12px; color:white;">
            <div style="color:#bbb; font-size:13px;">Total Orders</div>
            <div style="font-size:28px; font-weight:800; color:#ff9d00;"><?php echo $total_orders; ?></div>
        </div>

        <div style="flex:1; min-width:220px; background:#1f1f1f; padding:18px; border-radius:12px; color:white;">
            <div style="color:#bbb; font-size:13px;">Lifetime Spend</div>
            <div style="font-size:28px; font-weight:800; color:#ff9d00;"><?php echo number_format($total_spent, 2); ?> €</div>
        </div>

        <div style="flex:2; min-width:260px; background:#1f1f1f; padding:18px; border-radius:12px; color:white;">
            <div style="color:#bbb; font-size:13px;">Last Order</div>

            <?php if (!$last_order): ?>
                <div style="margin-top:8px; color:#ccc;">Δεν υπάρχει ακόμη παραγγελία.</div>
            <?php else: ?>
                <div style="margin-top:8px;">
                    <strong>Order #<?php echo (int)$last_order['id']; ?></strong>
                    <span style="color:#bbb; font-size:13px;">— <?php echo htmlspecialchars($last_order['order_date']); ?></span>
                </div>

                <div style="margin-top:6px; font-size:18px;">
                    Amount: <span style="color:#ff9d00; font-weight:800;"><?php echo number_format((float)$last_order['total_price'], 2); ?> €</span>
                </div>

                <div style="margin-top:12px;">
                    <a href="order_details.php?id=<?php echo (int)$last_order['id']; ?>"
                       class="auth-button"
                       style="text-decoration:none; display:inline-block;">
                        View Last Order Details
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
        <a href="my_orders.php" class="auth-button" style="text-decoration:none;">📦 My Orders</a>
        <a href="cart.php" class="auth-button" style="text-decoration:none;">🛒 Go to Cart</a>
        <a href="TEST2.php" class="auth-button" style="text-decoration:none;">🏠 Home</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>