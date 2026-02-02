<?php
// includes/header.php
// Προϋπόθεση: session_start() έχει ήδη γίνει στο αρχείο που το καλεί.

$pageTitle  = $pageTitle ?? 'AT.COLLECTION';
$loadCartJs = $loadCartJs ?? false; // default: ΟΧΙ cart.js
$pageClass  = $pageClass ?? '';     // default: καμία page-specific class
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex,nofollow">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <link rel="stylesheet" href="test2.css">

  <script>
    window.currentUserEmail = <?php echo json_encode($_SESSION['user']['email'] ?? 'guest'); ?>;
  </script>

  <script src="Test2.js" defer></script>

  <?php if ($loadCartJs): ?>
    <script src="cart.js" defer></script>
  <?php endif; ?>
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

    <button class="menu-toggle" type="button"
            aria-controls="site-nav" aria-expanded="false"
            aria-label="Toggle menu">☰</button>

    <div class="header-collapse" id="site-nav">
      <nav class="primary-menu">
        <ul>
          <li><a href="TEST2.php">HOME</a></li>

          <li>
            <a href="TEST2.php#main-section">PRODUCTS</a>
            <ul class="dropdown">
              <li><a href="TEST2.php#best-sellers">Best Sellers</a></li>
              <li>
                Bags Collection
                <ul class="dropdown">
                  <li><a href="Backpacks.php">Backpacks</a></li>
                  <li><a href="CrossbodyBags.php">Crossbody Bags</a></li>
                  <li><a href="ShoulderBags.php">Shoulder Bags</a></li>
                  <li><a href="ShoppingBags.php">Shopping Bags</a></li>
                </ul>
              </li>
            </ul>
          </li>

          <li>
            <a href="TEST2.php#main-section">ABOUT</a>
            <ul class="dropdown">
              <li><a href="TEST2.php#main-section">History</a></li>
              <li>
                Team
                <ul class="dropdown">
                  <li><a href="https://www.linkedin.com/in/alexandrostsamprounis/" target="_blank" rel="noopener">ALEX</a></li>
                  <li><a href="https://www.linkedin.com/in/panagiotiszois/" target="_blank" rel="noopener">PANOS</a></li>
                  <li><a href="https://www.linkedin.com/in/georgeorestisgiannakopoulos52611a253/" target="_blank" rel="noopener">GEORGE</a></li>
                </ul>
              </li>
              <li><a href="careers.php">Careers</a></li>
            </ul>
          </li>

          <li><a href="TEST2.php#secondary-section">CONTACT</a></li>

          <li>
            <a href="cart.php" class="cart-link">
              🛒 CART (<span id="cart-count">0</span>)
            </a>
          </li>
        </ul>
      </nav>

      <div class="header-right">
        <?php if (!empty($_SESSION['user'])): ?>
          <span class="user-greeting">Γειά σου, <?php echo htmlspecialchars($_SESSION['user']['firstname'] ?? ''); ?></span>
          <a href="profile.php" class="auth-link auth-link--spaced">My Profile</a>
          <a href="logout.php" class="auth-link">Logout</a>
        <?php else: ?>
          <a href="login.php" class="auth-link">Login</a>
          <a href="register.php" class="auth-button">Register</a>
        <?php endif; ?>
      </div>

    </div>
  </section>
</header>

<main class="main-content <?php echo htmlspecialchars($pageClass); ?>">