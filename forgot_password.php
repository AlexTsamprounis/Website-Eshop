<?php
session_start();

$pageTitle  = "Ανάκτηση Κωδικού | AT.COLLECTION";
$loadCartJs = false; // δεν χρειάζεται cart.js εδώ

// Δεν χρειάζεται DB για mock flow
// require_once __DIR__ . '/db_connect.php';

// POST: submit email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['emailAdress'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = 'Παρακαλώ συμπληρώστε ένα email με τη σωστή μορφή.';
    } else {
        // Mock / εργασία: δεν στέλνουμε πραγματικό email.
        // "Always success" μήνυμα για να μη γίνεται email enumeration.
        $_SESSION['flash'] = 'Αν υπάρχει λογαριασμός με αυτό το email, θα λάβετε σύντομα οδηγίες.';
    }

    header('Location: forgot_password.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="container forgot-wrap">
  <div class="login-card centered">

    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="flash-success flash-success--info">
        <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
      </div>
    <?php endif; ?>


    <h2 class="form-title" style="color: black; font-weight: bold;">Ανάκτηση Κωδικού</h2>

    <form method="post" action="forgot_password.php" class="login-form" autocomplete="on">
      <p class="forgot-hint">
        Εισάγετε το email σας για να ξεκινήσει η διαδικασία επαναφοράς.
      </p>


      <label class="floating">Email</label>
      <input type="email" id="emailAdress" name="emailAdress" placeholder="example@mail.com"   
           value="<?php echo htmlspecialchars($_POST['emailAdress'] ?? ''); ?>" required>

           
      <button type="submit" class="primary">Αποστολή Οδηγιών</button>
    </form>

    <div class="login-links forgot-links">
      <a href="login.php">← Επιστροφή στο Login</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

