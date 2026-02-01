<?php
// get_product.php (LOCAL READY - no get_result)

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid id"]);
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT id, name, price FROM products WHERE id = ? LIMIT 1");
if (!$stmt) {
  http_response_code(500);
  echo json_encode(["error" => "DB prepare failed"]);
  exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $pid, $name, $price);

if (!mysqli_stmt_fetch($stmt)) {
  mysqli_stmt_close($stmt);
  http_response_code(404);
  echo json_encode(["error" => "Not found"]);
  exit;
}

mysqli_stmt_close($stmt);

echo json_encode([
  "id"    => (int)$pid,
  "name"  => (string)$name,
  "price" => (float)$price
]);