<?php
session_start();
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

// Φέρνουμε ΜΟΝΟ παραγγελία που ανήκει στον συγκεκριμένο user (ασφάλεια)
$sql = "SELECT id, user_email, total_price, order_items, order_date, full_name, shipping_address, city, zip_code
        FROM orders
        WHERE id = ? AND user_email = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $order_id, $user_email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['flash'] = "Η παραγγελία δεν βρέθηκε (ή δεν σας ανήκει).";
    header("Location: my_orders.php");
    exit;
}

$order = mysqli_fetch_assoc($res);

// Decode items JSON
$itemsRaw = $order['order_items'] ?? '';
$itemsArr = json_decode($itemsRaw, true);
if (!is_array($itemsArr)) $itemsArr = [];

require_once __DIR__ . '/includes/header.php';
?>

<section class="container" style="padding:30px;">
    <h1 style="color:#ff9d00;">🧾 Order Details</h1>

    <div style="background:#1f1f1f; padding:20px; border-radius:12px; margin-top:20px; color:white;">
        <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap;">
            <div>
                <div style="font-weight:700;">Order #<?php echo (int)$order['id']; ?></div>
                <div style="color:#bbb; font-size:14px;">
                    Date: <?php echo htmlspecialchars($order['order_date']); ?>
                </div>
            </div>

            <div style="text-align:right;">
                <div style="font-size:18px;">
                    Total: <span style="color:#ff9d00; font-weight:700;">
                        <?php echo number_format((float)$order['total_price'], 2); ?> €
                    </span>
                </div>
            </div>
        </div>

        <hr style="border-color:#333; margin:15px 0;">

        <div style="color:#ddd; font-size:14px;">
            <strong>Shipping To:</strong><br>
            <?php echo htmlspecialchars($order['full_name'] ?? ''); ?><br>
            <?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?><br>
            <?php echo htmlspecialchars(($order['city'] ?? '') . " " . ($order['zip_code'] ?? '')); ?>
        </div>

        <hr style="border-color:#333; margin:15px 0;">

        <h3 style="margin:0 0 10px; color:#ff9d00;">Items</h3>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; color:white;">
                <thead>
                    <tr style="background:#2a2a2a;">
                        <th style="padding:10px; text-align:left;">Product</th>
                        <th style="padding:10px;">Qty</th>
                        <th style="padding:10px;">Price</th>
                        <th style="padding:10px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($itemsArr) === 0): ?>
                    <tr>
                        <td colspan="4" style="padding:12px; color:#ccc;">
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
                            $subtotal = $price * $qty;
                            $calcTotal += $subtotal;
                    ?>
                        <tr style="border-bottom:1px solid #333;">
                            <td style="padding:10px;"><?php echo htmlspecialchars($name); ?></td>
                            <td style="padding:10px; text-align:center;"><?php echo $qty; ?></td>
                            <td style="padding:10px; text-align:center;"><?php echo number_format($price, 2); ?> €</td>
                            <td style="padding:10px; text-align:center;"><?php echo number_format($subtotal, 2); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (count($itemsArr) > 0): ?>
            <div style="text-align:right; margin-top:15px; color:#ccc;">
                Calculated Total: <span style="color:#ff9d00; font-weight:700;"><?php echo number_format($calcTotal, 2); ?> €</span>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-top:25px; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="my_orders.php" class="auth-button" style="text-decoration:none;">⬅ Back to My Orders</a>
        <a href="profile.php" class="auth-button" style="text-decoration:none;">👤 Profile</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>