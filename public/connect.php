<?php
// (File này đã được làm sạch, không còn ký tự rác)
// ----- KẾT NỐI CSDL BẰNG PDO -----
// $host = "sql100.infinityfree.com";
// $user = "if0_40585191";
// $pass = "1254635984p"; // Sửa mật khẩu của bạn ở đây (nếu có)
// $db   = "if0_40585191_shop_thoi_trang_hoc";
// $charset = "utf8mb4";

$host = "localhost"; 
$user = "root";
$pass = ""; // Sửa mật khẩu của bạn ở đây (nếu có)
$db   = "shop_thoi_trang_hoc";
$charset = "utf8mb4";
// Cấu hình DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Cấu hình các tùy chọn
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Tạo biến $conn (Đây là đối tượng PDO)
    $conn = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    // QUAN TRỌNG: Nếu kết nối thất bại
    
    // 1. Báo cho trình duyệt biết đây là JSON (ngay cả khi lỗi)
    header('Content-Type: application/json');
    http_response_code(500); // Lỗi Server
    
    // 2. Trả về một thông báo lỗi JSON
    echo json_encode([
        'success' => false, 
        'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()
    ]);

    exit();
}

?>