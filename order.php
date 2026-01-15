<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// 1) Μόνο logged-in
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "Πρέπει να κάνετε login για να δείτε λεπτομέρειες παραγγελίας.";
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];

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
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['flash'] = "Η παραγγελία δεν βρέθηκε ή δεν έχετε πρόσβαση.";
    header("Location: my_orders.php");
    exit;
}

$order = mysqli_fetch_assoc($res);

// 4) Decode items (από localStorage JSON)
$items_raw = $order['order_items'] ?? '';
$items = json_decode($items_raw, true);
$items_ok = is_array($items);

require_once __DIR__ . '/includes/header.php';
?>

<section class="best-sellers" style="padding: 40px 20px;">
    <div class="best-sellers__header">
        <h2 class="best-sellers__title">Order Details</h2>
    </div>

    <div style="max-width: 1100px; margin: 0 auto;">

        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
            <a href="my_orders.php" class="auth-link">← Πίσω στις Παραγγελίες</a>

            <div style="color:#bbb;">
                Order #<strong style="color:#ff9d00;"><?php echo (int)$order['id']; ?></strong>
                &nbsp;|&nbsp;
                Ημερομηνία: <strong><?php echo htmlspecialchars($order['order_date']); ?></strong>
            </div>
        </div>

        <article style="background:#1b1b1b; border:1px solid #333; border-radius:12px; padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                <div style="color:#ddd;">
                    <h3 style="margin:0 0 10px; color:#fff;">Αποστολή</h3>
                    <div><strong>Όνομα:</strong> <?php echo htmlspecialchars($order['full_name'] ?? ''); ?></div>
                    <div><strong>Διεύθυνση:</strong> <?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?></div>
                    <div><strong>Πόλη:</strong> <?php echo htmlspecialchars($order['city'] ?? ''); ?></div>
                    <div><strong>ΤΚ:</strong> <?php echo htmlspecialchars($order['zip_code'] ?? ''); ?></div>
                    <div style="margin-top:10px; color:#999; font-size:12px;">
                        Email παραγγελίας: <?php echo htmlspecialchars($order['user_email']); ?>
                    </div>
                </div>

                <div style="text-align:right;">
                    <h3 style="margin:0 0 10px; color:#fff;">Σύνολο</h3>
                    <div style="font-size:22px; font-weight:700; color:#ff9d00;">
                        <?php echo number_format((float)$order['total_price'], 2); ?> €
                    </div>
                </div>
            </div>

            <hr style="border:none; border-top:1px solid #333; margin:16px 0;">

            <h3 style="margin:0 0 12px; color:#fff;">Προϊόντα</h3>

            <?php if ($items_ok && count($items) > 0): ?>
                <?php
                // Υπολογισμός subtotal από τα items (για έλεγχο/εμφάνιση)
                $calc_total = 0.0;
                foreach ($items as $it) {
                    $p = isset($it['price']) ? (float)$it['price'] : 0.0;
                    $q = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                    $calc_total += $p * $q;
                }
                ?>

                <table style="width:100%; border-collapse: collapse; color:#eee;">
                    <thead>
                        <tr style="background:#222;">
                            <th style="text-align:left; padding:12px; border-bottom:1px solid #333;">Product</th>
                            <th style="text-align:center; padding:12px; border-bottom:1px solid #333;">Qty</th>
                            <th style="text-align:right; padding:12px; border-bottom:1px solid #333;">Price</th>
                            <th style="text-align:right; padding:12px; border-bottom:1px solid #333;">Subtotal</th>
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
                            <tr style="border-bottom:1px solid #333;">
                                <td style="padding:12px;"><?php echo htmlspecialchars($name); ?></td>
                                <td style="padding:12px; text-align:center;"><?php echo $qty; ?></td>
                                <td style="padding:12px; text-align:right;"><?php echo number_format($price, 2); ?> €</td>
                                <td style="padding:12px; text-align:right;"><?php echo number_format($subtotal, 2); ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="display:flex; justify-content:flex-end; margin-top:16px;">
                    <div style="background:#222; padding:14px 16px; border-radius:10px; min-width:320px;">
                        <div style="display:flex; justify-content:space-between; color:#ddd;">
                            <span>Σύνολο από items:</span>
                            <strong><?php echo number_format($calc_total, 2); ?> €</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:10px; color:#fff; font-size:18px;">
                            <span>Σύνολο (DB):</span>
                            <strong style="color:#ff9d00;"><?php echo number_format((float)$order['total_price'], 2); ?> €</strong>
                        </div>
                        <?php if (abs($calc_total - (float)$order['total_price']) > 0.01): ?>
                            <div style="margin-top:10px; color:#ff6b6b; font-size:12px;">
                                * Σημείωση: υπάρχει μικρή διαφορά total (items vs DB). (Συνήθως λόγω rounding/τύπου δεδομένων)
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div style="color:#bbb; background:#222; padding:12px; border-radius:8px;">
                    Δεν ήταν δυνατή η ανάγνωση των items (raw JSON):<br>
                    <code style="color:#ff9d00;"><?php echo htmlspecialchars($items_raw); ?></code>
                </div>
            <?php endif; ?>

        </article>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>