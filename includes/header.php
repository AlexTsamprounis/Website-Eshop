<?php
// includes/header.php
// Προϋπόθεση: Η σελίδα που το καλεί έχει ήδη session_start()
?>
<!DOCTYPE html>
<html>
<head>
    <title>AT.COLLECTION</title>
    <link href="test2.css" rel="stylesheet" type="text/css">

    <script>
        // Ασφαλές πέρασμα string σε JS (δεν σπάει με quotes)
        var currentUserEmail = <?php echo json_encode($_SESSION['user']['email'] ?? 'guest'); ?>;
    </script>

    <script src="Test2.js" type="text/javascript" defer></script>
    <script src="cart.js" type="text/javascript" defer></script>

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>

<body>
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-success">
        <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>

<header class="headline">
    <section class="header-container">
        <div class="header-left">
            <p class="main-Headline">AT.COLLECTION</p>
            <h5>Since 1954</h5>
        </div>

        <nav class="primary-menu">
            <ul>
                <li><a href="TEST2.php">HOME</a></li>

                <li>
                    <a href="#main-section">PRODUCTS</a>
                    <ul class="dropdown">
                        <li><a href="#best-sellers">Best Sellers</a></li>
                        <li>
                            Bags Collection
                            <ul class="dropdown">
                                <!-- Αφαιρέσαμε target=_blank για να μην ανοίγει tabs -->
                                <li><a href="Backpacks.php">Backpacks</a></li>
                                <li><a href="CrossbodyBags.php">Crossbody Bags</a></li>
                                <li><a href="ShoulderBags.php">Shoulder Bags</a></li>
                                <li><a href="ShoppingBags.php">Shopping Bags</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a>ABOUT</a>
                    <ul class="dropdown">
                        <li><a href="#main-section">History</a></li>
                        <li>
                            Team
                            <ul class="dropdown">
                                <li><a href="https://www.linkedin.com/in/alexandrostsamprounis/" target="_blank">ALEX</a></li>
                                <li><a href="https://www.linkedin.com/in/panagiotiszois/" target="_blank">PANOS</a></li>
                                <li><a href="https://www.linkedin.com/in/georgeorestisgiannakopoulos52611a253/" target="_blank">GEORGE</a></li>
                            </ul>
                        </li>
                        <li>Careers</li>
                    </ul>
                </li>

                <li><a href="#secondary-section">CONTACT</a></li>

                <li>
                    <a href="cart.php" style="color: #ff9d00; font-weight: bold;">
                        🛒 CART (<span id="cart-count">0</span>)
                    </a>
                </li>
            </ul>
        </nav>

        <div class="header-right">
            <?php if (!empty($_SESSION['user'])): ?>
    <span style="color: #ffa503; margin-right:12px;">
        Γειά σου, <?php echo htmlspecialchars($_SESSION['user']['firstname']); ?>
    </span>

    <a href="profile.php" class="auth-link" style="margin-right:10px;">My Profile</a>

    <a href="logout.php" class="auth-link">Logout</a>
<?php else: ?>
    <a href="login.php" class="auth-link">Login</a>
    <a href="TEST2.php#form" class="auth-button">Register</a>
<?php endif; ?>
        </div>
    </section>
</header>

<main class="main-content page-home">