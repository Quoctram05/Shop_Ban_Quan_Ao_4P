<?php
include('connect.php'); 
header('Content-Type: application/json; charset=utf-8');

$q = $_GET['q'] ?? '';
$q = trim($q);
$suggestions = [];

if (mb_strlen($q, 'UTF-8') > 1) {
    try {
        $q_lower = mb_strtolower($q, 'UTF-8');
        $like_query = '%' . $q_lower . '%';

        // SỬA CÂU SQL: Lấy thêm cột hình ảnh (dùng subquery)
        $stmt = $conn->prepare("
            SELECT 
                sp.ten_san_pham,
                -- Lấy 1 ảnh đại diện từ bảng biến thể
                (SELECT hinh_anh_dai_dien FROM bien_the_san_pham 
                 WHERE san_pham_id = sp.id 
                 ORDER BY id ASC LIMIT 1) as hinh_anh
            FROM san_pham sp
            WHERE LOWER(sp.ten_san_pham) LIKE BINARY ?
            LIMIT 5
        ");
        
        $stmt->execute([$like_query]);
        
        // Lấy kết quả dạng mảng kết hợp (Associative Array) để có cả tên và ảnh
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
    } catch (PDOException $e) {
        $suggestions = []; 
    }
}

echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
?>