<?php
require_once '../public/connect.php';
header('Content-Type: application/json');

$product_id = $_GET['product_id'] ?? 0;

if (!$product_id) {
    echo json_encode([]);
    exit;
}

try {
    // Lấy tất cả biến thể của sản phẩm này
    $stmt = $conn->prepare("SELECT id, mau_sac, kich_co, so_luong_ton FROM bien_the_san_pham WHERE san_pham_id = ?");
    $stmt->execute([$product_id]);
    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'variants' => $variants]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>