<?php
// db_connect.php (LOCAL)

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host     = "127.0.0.1";
$username = "root";
$password = "";              // αν έχεις βάλει password στο root, γράψ' το εδώ
$dbname   = "at_collection_db";

$ports = [3306, 3307];
$conn  = null;

foreach ($ports as $port) {
  try {
    $conn = mysqli_connect($host, $username, $password, $dbname, $port);
    mysqli_set_charset($conn, "utf8mb4");
    break;
  } catch (mysqli_sql_exception $e) {
    $conn = null;
  }
}

if (!$conn) {
  die(
    "<strong>DB ERROR:</strong> Δεν έγινε σύνδεση στο <code>$host</code> στις θύρες <code>" .
    implode(", ", $ports) .
    "</code>. <br> Άνοιξε XAMPP → MySQL Start και δες ποια θύρα χρησιμοποιεί."
  );
}