<?php
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="best-sellers" id="category">
    <div class="best-sellers__header">
        <h2 class="best-sellers__title">Backpacks Collection!!</h2>
    </div>

    <div class="products-flex">
        <?php
        // Παίρνουμε backpacks από DB
        $query = "SELECT * FROM products WHERE category = 'backpack'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "<p style='color:#ff6b6b;'>DB Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        } elseif (mysqli_num_rows($result) === 0) {
            echo "<p style='color:white;'>Δεν βρέθηκαν Backpacks στη βάση δεδομένων.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {

                $name  = $row['name'] ?? '';
                $desc  = $row['Description'] ?? ($row['description'] ?? '');
                $price = (float)($row['price'] ?? 0);
                $img   = $row['image_path'] ?? '';
                ?>
                <article class="product-card">
                    <img class="product-card__img"
                         src="<?php echo htmlspecialchars($img); ?>"
                         alt="<?php echo htmlspecialchars($name); ?>">

                    <div class="product-card__body">
                        <h3 class="product-card__name"><?php echo htmlspecialchars($name); ?></h3>
                        <p class="product-card__desc"><?php echo htmlspecialchars($desc); ?></p>
                        <p class="product-card__price"><?php echo number_format($price, 2); ?> €</p>

                        <button onclick='addToCart(<?php echo json_encode($name); ?>, <?php echo $price; ?>)'
                                class="auth-button"
                                style="width:100%; cursor:pointer; margin-bottom:10px; background-color:#ff9d00; border:none; padding:10px;">
                            Add to Cart
                        </button>
                    </div>
                </article>
                <?php
            }
        }
        ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>