<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "shop_thoi_trang_hoc";   // đúng tên DB trong script của bạn

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { die("Kết nối CSDL thất bại: " . mysqli_connect_error()); }
mysqli_set_charset($conn, "utf8mb4");
?>
