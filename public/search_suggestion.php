<?php
include('connect.php');
header('Content-Type: application/json; charset=utf-8');

$q = $_GET['q'] ?? '';
$q = trim($q);

if (mb_strlen($q, 'UTF-8') > 1) {
  // Prepared statement để an toàn ký tự tiếng Việt
  $stmt = $conn->prepare("SELECT name FROM product WHERE name LIKE CONCAT('%', ?, '%') LIMIT 5");
  $stmt->bind_param("s", $q);
  $stmt->execute();
  $res = $stmt->get_result();

  $out = [];
  while ($row = $res->fetch_assoc()) {
    $out[] = $row['name'];
  }
  echo json_encode($out, JSON_UNESCAPED_UNICODE);
  exit;
}

echo json_encode([], JSON_UNESCAPED_UNICODE);
