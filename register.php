<?php
// register.php — standalone register page (NO modal on homepage)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname     = trim($_POST['firstname'] ?? '');
    $lastname      = trim($_POST['lastname'] ?? '');
    $gender        = trim($_POST['formGender'] ?? '');
    $email         = trim($_POST['emailAdress'] ?? '');
    $passwordPlain = $_POST['formPassword'] ?? '';
    $newsletter    = trim($_POST['formNewsletter'] ?? '');

    // Basic validation (same spirit as before)
    if ($firstname === '') $errors[] = 'First name is required.';
    if ($lastname === '')  $errors[] = 'Last name is required.';
    if ($gender === '')    $errors[] = 'Gender is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if ($passwordPlain === '') $errors[] = 'Password is required.';
    if ($newsletter === '') $errors[] = 'Newsletter choice is required.';
    if (!isset($_POST['agreeTerms'])) $errors[] = 'You must accept the Terms of Service.';

    if (empty($errors)) {
        $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        }
        $check->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO users (firstname, lastname, gender, email, password, newsletter) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $firstname, $lastname, $gender, $email, $passwordHash, $newsletter);

        if ($stmt->execute()) {
            $stmt->close();
            // back to homepage with message
            header('Location: TEST2.php?registration=success');
            exit;
        }

        $errors[] = 'Database error: ' . $stmt->error;
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | AT COLLECTION</title>
  <link rel="stylesheet" href="test2.css" />
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<section id="form">
  <form action="register.php" method="POST">
    <h2 style="margin-top:0; font-family: OswaldBold, sans-serif;">Register</h2>

    <?php if (!empty($errors)): ?>
      <div class="form-error" style="text-decoration:none; margin-bottom: 12px;">
        <strong>Please fix the following:</strong>
        <ul style="margin: 8px 0 0 18px; padding:0;">
          <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <fieldset>
      <legend><b>Personal Details</b></legend>

      <label for="firstname"><span style="color:red;">*</span> First Name:</label>
      <input type="text" id="firstname" name="firstname" placeholder="John" value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>" required>

      <label for="lastname"><span style="color:red;">*</span> Last Name:</label>
      <input type="text" id="lastname" name="lastname" placeholder="Doe" value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>" required>
    </fieldset>

    <label for="formGender"><span style="color:red;">*</span> Gender:</label>
    <select id="formGender" name="formGender" required>
      <option value="">Choose your gender !</option>
      <option value="male"   <?php echo (($_POST['formGender'] ?? '') === 'male') ? 'selected' : ''; ?>>Male</option>
      <option value="female" <?php echo (($_POST['formGender'] ?? '') === 'female') ? 'selected' : ''; ?>>Female</option>
      <option value="other"  <?php echo (($_POST['formGender'] ?? '') === 'other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <label for="emailAdress"><span style="color:red;">*</span> E-mail:</label>
    <input type="email" id="emailAdress" name="emailAdress" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($_POST['emailAdress'] ?? ''); ?>" required>

    <label for="formPassword"><span style="color:red;">*</span> Password:</label>
    <input type="password" id="formPassword" name="formPassword" placeholder="Password" required>

    <label for="formComments">Comments</label>
    <textarea id="formComments" name="formComments" maxlength="400" placeholder="Add your comments here !!"><?php echo htmlspecialchars($_POST['formComments'] ?? ''); ?></textarea>
    <p style="margin: 0 0 2px;"><b>Your comments must be no more than 400 characters.</b></p>
    <p style="margin: 0 0 12px;"><b>Remaining characters:</b> <span id="remainingChars">400</span></p>

    <fieldset class="newsletter">
      <legend><b>Please sign up to our newsletter</b></legend>

      <p class="newsletter-text"><span style="color:red;">*</span> Sign up to our newsletter !</p>

      <div class="newsletter-options">
        <label>
          <input type="radio" name="formNewsletter" value="yes" <?php echo (($_POST['formNewsletter'] ?? '') === 'yes') ? 'checked' : ''; ?> required>
          <span>YES</span>
        </label>

        <label>
          <input type="radio" name="formNewsletter" value="no" <?php echo (($_POST['formNewsletter'] ?? '') === 'no') ? 'checked' : ''; ?> required>
          <span>NO</span>
        </label>
      </div>
    </fieldset>

    <div class="agreeTerms">
      <label for="agreeTerms"><span style="color:red;">*</span> I have read the terms of Service !</label>
      <input type="checkbox" id="agreeTerms" name="agreeTerms" <?php echo isset($_POST['agreeTerms']) ? 'checked' : ''; ?> required>
    </div>

    <div class="action">
      <input type="submit" value="REGISTER">
    </div>
  </form>
</section>

<script src="Test2.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
