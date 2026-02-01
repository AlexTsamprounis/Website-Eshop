<?php
session_start();
$loadCartJs = false;
require_once __DIR__ . '/db_connect.php';

/**
 * Αν θες redirect μετά το login:
 * login.php?redirect=cart.php
 */
$redirect = $_GET['redirect'] ?? 'TEST2.php';
$redirect = trim($redirect);

// Basic safety: μην επιτρέπεις full URLs (open redirect)
if ($redirect === '' || str_contains($redirect, '://') || str_starts_with($redirect, '//')) {
    $redirect = 'TEST2.php';
}

$errors = [];

/**
 * POST: κάνουμε login
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $redirectPost = trim($_POST['redirect'] ?? 'TEST2.php');

    if ($redirectPost !== '' && !str_contains($redirectPost, '://') && !str_starts_with($redirectPost, '//')) {
        $redirect = $redirectPost;
    }

    if ($email === '' || $password === '') {
        $errors[] = "Συμπλήρωσε email και κωδικό.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, firstname, lastname, email, password_hash FROM users WHERE email = ? LIMIT 1");
        if (!$stmt) {
            $errors[] = "DB error.";
        } else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $firstname, $lastname, $db_email, $password_hash);

            $ok = false;
            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $password_hash)) {
                    $ok = true;
                }
            }
            mysqli_stmt_close($stmt);

            if ($ok) {
                // prevent session fixation
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id'        => (int)$id,
                    'firstname' => (string)$firstname,
                    'lastname'  => (string)$lastname,
                    'email'     => (string)$db_email
                ];

                header("Location: " . $redirect);
                exit;
            } else {
                $errors[] = "Λάθος email ή κωδικός.";
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="container login-wrap">
    <div class="login-card centered">
        <h1 class="login-title">Login</h1>

        <?php if (!empty($errors)): ?>
            <div class="flash-error flash-error--login">
                <?php echo htmlspecialchars(implode(" ", $errors)); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="flash-error flash-error--login">
                <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <div class="login-links login-links--mb">
            <a href="forgot_password.php">Ξέχασες τον κωδικό σου;</a>
            <a href="register.php">Create an account</a>
        </div>

        <form method="post" action="login.php<?php echo $redirect ? '?redirect=' . urlencode($redirect) : ''; ?>" class="login-form" autocomplete="on">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <label class="floating">Email</label>
            <input type="email" name="email" required>

            <label class="floating">Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="primary">Login</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>