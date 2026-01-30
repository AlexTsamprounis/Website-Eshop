<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link href="test2.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <main class="login-page">
        <div class="login-card centered">
            <h1 class="login-title">Login</h1>
            
            <?php if (!empty($_SESSION['flash'])): ?>
                <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-radius:5px; text-align:center;">
                    <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
                </div>
            <?php endif; ?>
            <div class="login-links">
                <a href="forgot_password.php">Ξέχασες τον κωδικό σου;</a>
                <a href="register.php">Create an account</a>
            </div>

            <form method="post" action="login.php" class="login-form">
                <label class="floating">Email</label>
                <input type="email" name="email" required placeholder="">

                <label class="floating">Password</label>
                <input type="password" name="password" required placeholder="">

                <button type="submit" class="primary">Login</button>
            </form>
        </div>
    </main>
    </body>
    </html>
    <?php
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    die('Provide email and password.');
}

// Προσθέτουμε το lastname στο SELECT
$stmt = mysqli_prepare($conn, "SELECT id, firstname, lastname, password_hash FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

// Προσθέτουμε τη μεταβλητή $lastname στο bind_result
mysqli_stmt_bind_result($stmt, $id, $firstname, $lastname, $password_hash);

if (mysqli_stmt_fetch($stmt)) {
    if (password_verify($password, $password_hash)) {
        // Αποθηκεύουμε ΚΑΙ το lastname στο Session
        $_SESSION['user'] = [
            'id' => $id, 
            'firstname' => $firstname, 
            'lastname' => $lastname, 
            'email' => $email
        ];
        header('Location: cart.php');
        exit;
    }
}