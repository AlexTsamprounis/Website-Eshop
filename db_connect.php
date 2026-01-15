<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$username = "root";
$password = "";
$dbname   = "at_collection_db";

// Δοκιμάζουμε host + ports
$host  = "127.0.0.1";
$ports = [3306, 3307];

$conn = null;

foreach ($ports as $port) {
    try {
        $conn = mysqli_connect($host, $username, $password, $dbname, $port);
        mysqli_set_charset($conn, "utf8mb4");
        break; // αν πετύχει, σταματάμε
    } catch (mysqli_sql_exception $e) {
        // συνεχίζουμε να δοκιμάσουμε το επόμενο port
        $conn = null;
    }
}

if (!$conn) {
    $tried = implode(", ", $ports);
    die("<strong>Σφάλμα Σύνδεσης:</strong> Δεν μπόρεσα να συνδεθώ στη MySQL στο <code>$host</code> στις θύρες <code>$tried</code>.
    <br><em>Έλεγξε ότι το MySQL στο XAMPP είναι Running και ποια θύρα χρησιμοποιεί.</em>");
}
?>