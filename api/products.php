<?php
// (File api/products.php HOÀN CHỈNH - Đã thêm "Từ đồng nghĩa")
include('../public/connect.php');

// Báo cho trình duyệt biết đây là file JSON
header('Content-Type: application/json');

// ----- LẤY THAM SỐ -----
$category_slugs = (array)($_GET['category'] ?? []);
$page = (int)($_GET['page'] ?? 1);
$sort_by = $_GET['sort'] ?? 'default';

$products_per_page = 12; // 12 sản phẩm/trang
$offset = ($page - 1) * $products_per_page;


// === (MỚI) XỬ LÝ TỪ ĐỒNG NGHĨA TÌM KIẾM (Đã sửa logic) ===
$search_query = $_GET['q'] ?? '';
$search_query = trim($search_query);
// Chuẩn hóa về chữ thường để so khớp
$normalized_query = mb_strtolower($search_query, 'UTF-8'); 
$alias_map = [
    'mũ' => 'nón',
    // 'chữ sai' => 'chữ đúng'
];

// 2. Kiểm tra xem từ khóa có nằm trong danh sách đồng nghĩa không
if (isset($alias_map[$normalized_query])) {
    // Nếu có, thay thế từ khóa tìm kiếm bằng "từ đúng"
    $search_query = $alias_map[$normalized_query];
}

// 1. Định nghĩa các "bộ từ khóa"
// Key là slug (dễ nhớ), value là mảng các từ BẮT BUỘC phải có
$synonym_sets = [
    'ao-quan' => ['áo', 'quần'],
    'giay-dep' => ['giày', 'dép'],
];

// Biến để lưu slug category tìm được
$found_categories = [];

// 2. Vòng lặp qua các bộ từ khóa
foreach ($synonym_sets as $slug_key => $words_to_find) {
    $all_words_found = true;
    
    // 3. Kiểm tra xem query có chứa TẤT CẢ các từ trong bộ không
    foreach ($words_to_find as $word) {
        // Dùng mb_strpos để tìm chuỗi (an toàn cho UTF-8)
        if (mb_strpos($normalized_query, $word) === false) {
            $all_words_found = false;
            break; // Chỉ cần thiếu 1 từ là bỏ
        }
    }
    
    // 4. Nếu tìm thấy tất cả, gán category và dừng lại
    if ($all_words_found) {
        if ($slug_key === 'ao-quan') {
            $found_categories = ['ao', 'quan'];
        }
        if ($slug_key === 'giay-dep') {
            $found_categories = ['giay', 'dep-sandal'];
        }
        break; // Dừng vòng lặp ngoài (đã tìm thấy)
    }
}

// 5. Ghi đè (nếu tìm thấy)
if (!empty($found_categories)) {
    $category_slugs = $found_categories;
    $search_query = ''; // Xóa tìm kiếm LIKE
}
// === KẾT THÚC XỬ LÝ TỪ ĐỒNG NGHĨA ===


// ----- XÂY DỰNG MỆNH ĐỀ WHERE -----

// Mảng chứa các điều kiện WHERE
$sql_where_parts = [];
// Mảng chứa các tham số cho WHERE
$sql_params = []; 

// === 1. Xử lý CATEGORY (nếu có) ===
// (Khối này giờ sẽ chạy khi tìm "quần áo")
if (!empty($category_slugs)) {
    $placeholders = implode(',', array_fill(0, count($category_slugs), '?'));
    $stmt_cat = $conn->prepare("SELECT id FROM danh_muc WHERE slug IN ($placeholders)");
    $stmt_cat->execute($category_slugs);
    $requested_ids = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);

    $child_ids = [];
    if (!empty($requested_ids)) {
        $child_placeholders = implode(',', array_fill(0, count($requested_ids), '?'));
        $stmt_child = $conn->prepare("SELECT id FROM danh_muc WHERE danh_muc_cha_id IN ($child_placeholders)");
        $stmt_child->execute($requested_ids);
        $child_ids = $stmt_child->fetchAll(PDO::FETCH_COLUMN);
    }
    
    $final_category_ids = array_unique(array_merge($requested_ids, $child_ids));

    if (!empty($final_category_ids)) {
        $id_placeholders = implode(',', array_fill(0, count($final_category_ids), '?'));
        $sql_where_parts[] = "sp.danh_muc_id IN ($id_placeholders)";
        $sql_params = array_merge($sql_params, $final_category_ids);
    } else {
        $sql_where_parts[] = "1 = 0"; // Trả về rỗng
    }
}

// === 2. Xử lý BỘ LỌC KHUYẾN MÃI ===
$sale_filter = $_GET['sale'] ?? false;
if ($sale_filter === 'true') {
    $sql_where_parts[] = "EXISTS (
        SELECT 1 FROM bien_the_san_pham bsp 
        WHERE bsp.san_pham_id = sp.id AND bsp.gia_ban < bsp.gia_goc
    )";
}

// === 2b. Xử lý TÌM KIẾM (Bình thường) ===
// (Khối này sẽ bị bỏ qua khi tìm "quần áo")
if (!empty($search_query)) {
    $sql_where_parts[] = "LOWER(sp.ten_san_pham) LIKE BINARY ?";
    $sql_params[] = '%' . mb_strtolower($search_query, 'UTF-8') . '%';
}

// === 3. Gộp tất cả điều kiện WHERE lại ===
if (empty($sql_where_parts)) {
    $sql_where = " WHERE 1=1 "; // Lấy tất cả
} else {
    $sql_where = " WHERE " . implode(' AND ', $sql_where_parts);
}

// === 4. ĐỊNH NGHĨA CÁC SUBQUERY ===
$sql_price_subquery = "(SELECT MIN(gia_ban) FROM bien_the_san_pham WHERE san_pham_id = sp.id)";
$sql_original_price_subquery = "(SELECT MIN(gia_goc) FROM bien_the_san_pham WHERE san_pham_id = sp.id)";
$sql_image_subquery = "(SELECT hinh_anh_dai_dien FROM bien_the_san_pham WHERE san_pham_id = sp.id ORDER BY id ASC LIMIT 1)";


// ----- XÂY DỰNG MỆNH ĐỀ ORDER BY (Sắp xếp) -----
$sql_order = "ORDER BY ";
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
$stmt_count->execute($sql_params); 
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $products_per_page);


// ----- TRUY VẤN 2: LẤY SẢN PHẨM ĐỂ HIỂN THỊ -----
$sql = "
    SELECT 
        sp.id,
        sp.ten_san_pham,
        sp.slug,
        $sql_price_subquery AS display_price,
        $sql_original_price_subquery AS original_price,
        $sql_image_subquery AS image_url
    FROM 
        san_pham AS sp
    $sql_where
    $sql_order
    LIMIT ? OFFSET ? 
"; 

$stmt_products = $conn->prepare($sql);

// Bước 1: Bind (N) tham số cho mệnh đề WHERE
$i = 1;
foreach ($sql_params as $value) {
    if (is_int($value)) {
        $stmt_products->bindValue($i, $value, PDO::PARAM_INT);
    } else {
        $stmt_products->bindValue($i, $value, PDO::PARAM_STR);
    }
    $i++; // Tăng biến đếm
}

// Bước 2: Bind 2 tham số cho LIMIT và OFFSET (Luôn là SỐ)
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