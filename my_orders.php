<?php
session_start();
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
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

require_once __DIR__ . '/includes/header.php';
?>

<section class="container" style="padding:30px;">
    <h1 style="color:#ff9d00;">📦 My Orders</h1>
    <p style="color:#ccc;">Εδώ βλέπεις το ιστορικό παραγγελιών σου.</p>

    <?php if (!$result || mysqli_num_rows($result) === 0): ?>
        <div style="background:#1f1f1f; padding:18px; border-radius:12px; margin-top:20px; color:#fff;">
            Δεν υπάρχουν παραγγελίες ακόμα.
        </div>
    <?php else: ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                // order_items είναι JSON string από localStorage
                $itemsRaw = $row['order_items'] ?? '';
                $itemsArr = json_decode($itemsRaw, true);

                if (!is_array($itemsArr)) $itemsArr = []; // safety
            ?>
            <div style="margin-top:10px; text-align:right;">
    <a href="order_details.php?id=<?php echo (int)$row['id']; ?>"
       class="auth-button"
       style="text-decoration:none; display:inline-block;">
       View Details
    </a>
</div>

            <div style="background:#1f1f1f; padding:20px; border-radius:12px; margin-top:20px; color:white;">
                <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap;">
                    <div>
                        <div style="font-weight:700;">Order #<?php echo (int)$row['id']; ?></div>
                        <div style="color:#bbb; font-size:14px;">
                            Date: <?php echo htmlspecialchars($row['order_date']); ?>
                        </div>
                    </div>

                    <div style="text-align:right;">
                        <div style="font-size:18px;">
                            Total: <span style="color:#ff9d00; font-weight:700;">
                                <?php echo number_format((float)$row['total_price'], 2); ?> €
                            </span>
                        </div>
                    </div>
                </div>

                <hr style="border-color:#333; margin:15px 0;">

                <div style="color:#ddd; font-size:14px; margin-bottom:12px;">
                    <strong>Shipping:</strong>
                    <?php echo htmlspecialchars(($row['full_name'] ?? '') . " — " . ($row['shipping_address'] ?? '') . ", " . ($row['city'] ?? '') . " " . ($row['zip_code'] ?? '')); ?>
                </div>

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
                            <?php foreach ($itemsArr as $it): ?>
                                <?php
                                    $name = isset($it['name']) ? (string)$it['name'] : '';
                                    $price = isset($it['price']) ? (float)$it['price'] : 0;
                                    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                                    $subtotal = $price * $qty;
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
            </div>
        <?php endwhile; ?>

    <?php endif; ?>

    <div style="margin-top:25px;">
        <a href="profile.php" class="auth-button" style="text-decoration:none;">⬅ Back to Profile</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>