<?php
session_start();
include 'db_connect.php';

// ΑΝ Ο ΧΡΗΣΤΗΣ ΠΑΤΗΣΕΙ ΤΟ ΚΟΥΜΠΙ "ΑΠΟΣΤΟΛΗ" (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if ($email === '') {
        $_SESSION['flash'] = 'Παρακαλώ συμπληρώστε ένα email.';
    } else {
        // Εδώ θα έμπαινε ο κώδικας για την αποστολή email.
        // Για την εργασία, απλά βγάζουμε ένα μήνυμα επιτυχίας.
        $_SESSION['flash'] = 'Αν υπάρχει λογαριασμός με αυτό το email, θα λάβετε σύντομα οδηγίες.';
    }
    header('Location: forgot_password.php');
    exit;
}

// ΑΝ Ο ΧΡΗΣΤΗΣ ΑΠΛΑ ΕΠΙΣΚΕΠΤΕΤΑΙ ΤΗ ΣΕΛΙΔΑ (GET)
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ανάκτηση Κωδικού | AT.COLLECTION</title>
    <link href="test2.css" rel="stylesheet" type="text/css">
</head>
<body>
<main class="login-page">
    <div class="login-card centered">
        <h1 class="login-title">Ανάκτηση Κωδικού</h1>
        
        <?php if (!empty($_SESSION['flash'])): ?>
            <div style="background:#d1ecf1; color:#0c5460; padding:10px; margin-bottom:15px; border-radius:5px; text-align:center; border: 1px solid #bee5eb;">
                <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="forgot_password.php" class="login-form">
            <p style="color: #ccc; margin-bottom: 20px; text-align: center;">
                Εισάγετε το email σας για να ξεκινήσει η διαδικασία επαναφοράς.
            </p>
            
            <label class="floating">Email</label>
            <input type="email" name="email" required placeholder="example@mail.com">

            <button type="submit" class="primary">Αποστολή Οδηγιών</button>
        </form>

        <div class="login-links" style="margin-top: 20px;">
            <a href="login.php">← Επιστροφή στο Login</a>
        </div>
    </div>
</main>
</body>
</html>