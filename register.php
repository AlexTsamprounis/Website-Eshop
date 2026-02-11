<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$loadCartJs = true;
require_once __DIR__ . '/db_connect.php';

$pageTitle = "Register | AT.COLLECTION";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname     = trim($_POST['firstname'] ?? '');
    $lastname      = trim($_POST['lastname'] ?? '');
    $gender        = trim($_POST['formGender'] ?? '');
    $email         = trim($_POST['emailAdress'] ?? '');
    $passwordPlain = (string)($_POST['formPassword'] ?? '');
    $newsletter    = trim($_POST['formNewsletter'] ?? '');

    // --- allowlists (security + data consistency) ---
    $allowedGenders = ['male', 'female', 'other'];
    $allowedNews    = ['yes', 'no'];

    if ($firstname === '') $errors[] = 'First name is required.';
    if ($lastname === '')  $errors[] = 'Last name is required.';

    if ($gender === '' || !in_array($gender, $allowedGenders, true)) {
        $errors[] = 'Gender is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($passwordPlain === '') $errors[] = 'Password is required.';

    if ($newsletter === '' || !in_array($newsletter, $allowedNews, true)) {
        $errors[] = 'Newsletter choice is required.';
    }

    if (!isset($_POST['agreeTerms'])) $errors[] = 'You must accept the Terms of Service.';

    // Email unique check (Infinity safe: no get_result)
    if (empty($errors)) {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        if (!$check) {
            $errors[] = "DB error.";
        } else {
            mysqli_stmt_bind_param($check, 's', $email);
            mysqli_stmt_execute($check);
            mysqli_stmt_bind_result($check, $existing_id);

            if (mysqli_stmt_fetch($check)) {
                $errors[] = 'This email is already registered.';
            }
            mysqli_stmt_close($check);
        }
    }

    if (empty($errors)) {
        $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users (firstname, lastname, gender, email, password_hash, newsletter)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            $errors[] = "DB error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssssss', $firstname, $lastname, $gender, $email, $passwordHash, $newsletter);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                $_SESSION['flash'] = "✅ Η εγγραφή ολοκληρώθηκε! Κάντε login.";
                header("Location: login.php");
                exit;
            }

            $errors[] = 'Database error: ' . mysqli_error($conn);
            mysqli_stmt_close($stmt);
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section id="form">
  <form action="register.php" method="POST">
    <h2 class="form-title">Register Form</h2>

    <?php if (!empty($errors)): ?>
      <div class="form-error form-error--mb">
        <strong>Please fix the following:</strong>
        <ul class="form-error-list">
          <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <fieldset>
      <legend><b>Personal Details</b></legend>

      <label for="firstname"><span class="req">*</span> First Name:</label>
      <input type="text" id="firstname" name="firstname" placeholder="John"
             value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>" required>

      <label for="lastname"><span class="req">*</span> Last Name:</label>
      <input type="text" id="lastname" name="lastname" placeholder="Doe"
             value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>" required>
    </fieldset>

    <label for="formGender"><span class="req">*</span> Gender:</label>
    <select id="formGender" name="formGender" required>
      <option value="">Choose your gender !</option>
      <option value="male"   <?php echo (($_POST['formGender'] ?? '') === 'male') ? 'selected' : ''; ?>>Male</option>
      <option value="female" <?php echo (($_POST['formGender'] ?? '') === 'female') ? 'selected' : ''; ?>>Female</option>
      <option value="other"  <?php echo (($_POST['formGender'] ?? '') === 'other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <label for="emailAdress"><span class="req">*</span> E-mail:</label>
    <input type="email" id="emailAdress" name="emailAdress" placeholder="example@gmail.com"
           value="<?php echo htmlspecialchars($_POST['emailAdress'] ?? ''); ?>" required>

    <label for="formPassword"><span class="req">*</span> Password:</label>
    <input type="password" id="formPassword" name="formPassword" placeholder="Password" required>




    <fieldset class="newsletter">
      <legend><b>Please sign up to our newsletter</b></legend>
      <p class="newsletter-text"><span class="req">*</span> Sign up to our newsletter !</p>

      <div class="newsletter-options">
        <label>
          <input type="radio" name="formNewsletter" value="yes"
                 <?php echo (($_POST['formNewsletter'] ?? '') === 'yes') ? 'checked' : ''; ?> required>
          <span>YES</span>
        </label>

        <label>
          <input type="radio" name="formNewsletter" value="no"
                 <?php echo (($_POST['formNewsletter'] ?? '') === 'no') ? 'checked' : ''; ?> required>
          <span>NO</span>
        </label>
      </div>
    </fieldset>

    <div class="agreeTerms">
      <label for="agreeTerms"><span class="req">*</span> I have read the terms of Service !</label>
      <input type="checkbox" id="agreeTerms" name="agreeTerms" <?php echo isset($_POST['agreeTerms']) ? 'checked' : ''; ?> required>
    </div>

    <div class="action">
      <input type="submit" value="REGISTER">
    </div>
  </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>