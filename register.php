<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη στοιχείων από τη φόρμα
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($conn, $_POST['lastname']);
    $gender    = mysqli_real_escape_string($conn, $_POST['formGender']);
    $email     = mysqli_real_escape_string($conn, $_POST['emailAdress']);
    $password  = $_POST['formPassword'];
    $newsletter = isset($_POST['formNewsletter']) ? $_POST['formNewsletter'] : 'no';

    // Κρυπτογράφηση κωδικού για ασφάλεια
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // SQL εντολή για εισαγωγή στον πίνακα users
    $sql = "INSERT INTO users (firstname, lastname, gender, email, password_hash, newsletter) 
            VALUES ('$firstname', '$lastname', '$gender', '$email', '$password_hash', '$newsletter')";

    if (mysqli_query($conn, $sql)) {
        // Επιτυχία: Σύνδεση του χρήστη αμέσως και ανακατεύθυνση
        $_SESSION['user'] = [
            'id' => mysqli_insert_id($conn),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email
        ];
        header("Location: TEST2.php?registration=success");
        exit;
    } else {
        echo "Σφάλμα εγγραφής: " . mysqli_error($conn);
    }
}
?>