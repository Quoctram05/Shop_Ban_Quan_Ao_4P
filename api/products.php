<?php
/**
 * API Lấy danh sách sản phẩm linh hoạt
 * * Hỗ trợ:
 * - Lọc theo 1 danh mục (con): ?category=ao-so-mi
 * - Lọc theo nhiều danh mục (con): ?category[]=ao-vest&category[]=ao-ghile
 * - Lọc theo 1 danh mục (cha): ?category=ao (Tự động lấy tất cả con)
 * - Sắp xếp: &sort=price-asc | price-desc | default
 * - Phân trang: &page=1
 */

// ------------------------------------
// KẾT NỐI CSDL
// !!! Sửa 4 dòng này cho đúng với CSDL của bạn
// ------------------------------------
$servername = "localhost";
$username = "root";
$password = ""; // Mật khẩu XAMPP/MAMPP thường là rỗng
$dbname = "shop_thoi_trang_hoc"; // Tên CSDL chúng ta đã tạo

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    http_response_code(500); // Lỗi Server
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()]);
    exit();
}

// Báo cho trình duyệt biết đây là file JSON
header('Content-Type: application/json');

// ----- LẤY THAM SỐ -----
// Ép $_GET['category'] thành 1 mảng. 
// ?category=ao-so-mi -> $category_slugs = ['ao-so-mi']
// ?category[]=ao-vest&category[]=ao-ghile -> $category_slugs = ['ao-vest', 'ao-ghile']
$category_slugs = (array)($_GET['category'] ?? []);
$page = (int)($_GET['page'] ?? 1);
$sort_by = $_GET['sort'] ?? 'default';

$products_per_page = 12; // 12 sản phẩm/trang
$offset = ($page - 1) * $products_per_page;
$final_category_ids = []; // Mảng chứa ID danh mục cuối cùng

// ----- XÂY DỰNG MỆNH ĐỀ WHERE -----

if (empty($category_slugs)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp một danh mục (category).']);
    exit();
}

// 1. Tạo các dấu ? cho slug (ví dụ: "?,?")
$placeholders = implode(',', array_fill(0, count($category_slugs), '?'));

// 2. Lấy ID của các danh mục ĐƯỢC YÊU CẦU (ví dụ: 'ao' -> [1])
$stmt_cat = $conn->prepare("SELECT id FROM danh_muc WHERE slug IN ($placeholders)");
$stmt_cat->execute($category_slugs);
$requested_ids = $stmt_cat->fetchAll(PDO::FETCH_COLUMN); // [1] (nếu gọi 'ao')

if (empty($requested_ids)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục nào.']);
    exit();
}

// 3. TÌM TẤT CẢ DANH MỤC CON (NÂNG CẤP MỚI)
// Tìm tất cả ID con của các ID vừa yêu cầu (ví dụ: tìm con của [1])
$child_placeholders = implode(',', array_fill(0, count($requested_ids), '?'));
$stmt_child = $conn->prepare("SELECT id FROM danh_muc WHERE danh_muc_cha_id IN ($child_placeholders)");
$stmt_child->execute($requested_ids);
$child_ids = $stmt_child->fetchAll(PDO::FETCH_COLUMN); // [5, 6, 10, 11, 12, 13, 14, 15]

// 4. Gộp mảng ID cha và ID con lại (và loại bỏ trùng lặp)
$final_category_ids = array_unique(array_merge($requested_ids, $child_ids)); // [1, 5, 6, 10, ...]

// 5. Tạo mệnh đề WHERE cho câu truy vấn SẢN PHẨM
// Ví dụ: $final_category_ids = [1, 5, 6] -> $id_placeholders = "?,?,?"
$id_placeholders = implode(',', array_fill(0, count($final_category_ids), '?'));
$sql_where = " WHERE sp.danh_muc_id IN ($id_placeholders) ";

// Mảng tham số MỚI, sẽ được dùng cho cả 2 truy vấn
$sql_params = $final_category_ids;


// ----- XÂY DỰNG MỆNH ĐỀ ORDER BY (Sắp xếp) -----
$sql_order = "ORDER BY ";
// Chúng ta cần 1 subquery để lấy giá, và sắp xếp theo giá đó
$sql_price_subquery = "(SELECT MIN(gia_ban) FROM bien_the_san_pham WHERE san_pham_id = sp.id)";

switch ($sort_by) {
    case 'price-asc':
        $sql_order .= "$sql_price_subquery ASC";
        break;
    case 'price-desc':
        $sql_order .= "$sql_price_subquery DESC";
        break;
    case 'default':
    default:
        $sql_order .= "sp.id DESC"; // Mới nhất
        break;
}


// ----- TRUY VẤN 1: ĐẾM TỔNG SẢN PHẨM (Để phân trang) -----
$stmt_count = $conn->prepare("SELECT COUNT(sp.id) FROM san_pham AS sp $sql_where");
// $sql_params bây giờ là [1, 5, 6, 10...]
$stmt_count->execute($sql_params); 
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $products_per_page);


// ----- TRUY VẤN 2: LẤY SẢN PHẨM ĐỂ HIỂN THỊ -----
$sql = "
    SELECT 
        sp.id,
        sp.ten_san_pham,
        sp.slug,
        
        -- Lấy giá bán thấp nhất (hoặc giá bán duy nhất) từ các biến thể
        $sql_price_subquery AS display_price,
        
        -- Lấy hình ảnh đại diện từ biến thể đầu tiên
        (SELECT hinh_anh_dai_dien FROM bien_the_san_pham 
         WHERE san_pham_id = sp.id 
         ORDER BY id ASC LIMIT 1) AS image_url
        
    FROM 
        san_pham AS sp
    $sql_where
    $sql_order
    LIMIT ? OFFSET ? 
"; // SQL có (N) dấu ? cho IN, và 2 dấu ? cho LIMIT/OFFSET

$stmt_products = $conn->prepare($sql);

// Bước 1: Bind (N) tham số cho mệnh đề IN
// $sql_params là [1, 5, 6, 10...]
$i = 1;
foreach ($sql_params as $id) {
    $stmt_products->bindValue($i, $id, PDO::PARAM_INT);
    $i++; // Tăng biến đếm
}

// Bước 2: Bind 2 tham số cho LIMIT và OFFSET
// $i bây giờ là (số danh mục + 1)
// SỬA LỖI 1064: Dùng PDO::PARAM_INT
$stmt_products->bindValue($i, $products_per_page, PDO::PARAM_INT);
$stmt_products->bindValue($i + 1, $offset, PDO::PARAM_INT);

// Thực thi
$stmt_products->execute();
$products = $stmt_products->fetchAll();


// ----- TRẢ KẾT QUẢ -----
echo json_encode([
    'success' => true,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => (int)$total_pages,
        'total_products' => (int)$total_products
    ],
    'products' => $products
]);
?>