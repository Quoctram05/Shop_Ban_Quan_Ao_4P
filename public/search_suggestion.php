<?php
// 1. Nhúng file kết nối PDO (nằm cùng thư mục)
include('connect.php'); 

// 2. Báo cho trình duyệt biết đây là JSON
header('Content-Type: application/json; charset=utf-8');

// 3. Lấy từ khóa và chuẩn bị
$q = $_GET['q'] ?? '';
$q = trim($q);
$like_query = '%' . $q . '%';
$suggestions = []; // Mảng rỗng mặc định

// 4. Chỉ tìm nếu người dùng gõ ít nhất 2 ký tự
if (mb_strlen($q, 'UTF-8') > 1) {
    
    try {
        // 5. Chuẩn bị truy vấn (Dùng PDO, CSDL của chúng ta)
        // Dùng COLLATE utf8mb4_bin để tìm kiếm có phân biệt dấu (áo # ao)
        $stmt = $conn->prepare("
            SELECT ten_san_pham 
            FROM san_pham 
            WHERE ten_san_pham LIKE ? COLLATE utf8mb4_bin
            LIMIT 5
        ");
        
        // 6. Thực thi (Dùng PDO)
        $stmt->execute([$like_query]);
        
        // 7. Lấy kết quả (Dùng PDO)
        // Lấy về dạng mảng 1 chiều chỉ chứa tên, ví dụ: ["Áo Sơ Mi", "Áo Khoác"]
        $suggestions = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
        
    } catch (PDOException $e) {
        // (Chỉ trả về mảng rỗng nếu có lỗi SQL, không làm crash)
        $suggestions = ['Lỗi: ' . $e->getMessage()];
    }
}

// 8. Trả về JSON (kể cả khi mảng rỗng)
// JSON_UNESCAPED_UNICODE để giữ nguyên tiếng Việt
echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
?>