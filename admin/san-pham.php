<?php
// admin/san-pham.php

// 1. KHỞI ĐỘNG SESSION & KẾT NỐI
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../public/connect.php'; // $conn = PDO

// 2. CHECK QUYỀN ADMIN
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'QuanTriVien') {
    header('Location: /Shop_Ban_Quan_Ao_4P/login.php');
    exit;
}

// Hàm tạo slug đơn giản từ tên sản phẩm
function create_slug($str)
{
    // bỏ dấu tiếng Việt (cách đơn giản)
    $str = mb_strtolower($str, 'UTF-8');
    $from = ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ',
             'ă','ằ','ắ','ặ','ẳ','ẵ','è','é','ẹ','ẻ','ẽ',
             'ê','ề','ế','ệ','ể','ễ','ì','í','ị','ỉ','ĩ',
             'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ',
             'ơ','ờ','ớ','ợ','ở','ỡ','ù','ú','ụ','ủ','ũ',
             'ư','ừ','ứ','ự','ử','ữ','ỳ','ý','ỵ','ỷ','ỹ',
             'đ'];
    $to   = ['a','a','a','a','a','a','a','a','a','a','a',
             'a','a','a','a','a','a','e','e','e','e','e',
             'e','e','e','e','e','e','i','i','i','i','i',
             'o','o','o','o','o','o','o','o','o','o','o',
             'o','o','o','o','o','o','u','u','u','u','u',
             'u','u','u','u','u','u','y','y','y','y','y',
             'd'];
    $str = str_replace($from, $to, $str);
    $str = preg_replace('/[^a-z0-9\-]+/i', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

// 3. XỬ LÝ FORM SUBMIT (THÊM / SỬA / XÓA)
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'delete') {
            // Xóa sản phẩm
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM san_pham WHERE id = ?");
                $stmt->execute([$id]);
                $msg = 'Xóa sản phẩm thành công!';
            }

        } elseif ($action === 'save') {
            // Thêm hoặc Sửa
            $id   = $_POST['id'] ?? '';
            $ten  = trim($_POST['ten_san_pham'] ?? '');
            $dm   = (int)($_POST['danh_muc_id'] ?? 0);
            $mota = $_POST['mo_ta'] ?? '';

            if ($ten === '' || $dm === 0) {
                $msg = 'Vui lòng nhập đầy đủ Tên sản phẩm và chọn Danh mục.';
            } else {
                // Tạo slug
                $slug = create_slug($ten);

                // Nếu thêm mới thì đảm bảo slug không trùng
                if ($id === '' || $id === null) {
                    $baseSlug = $slug;
                    $i = 1;
                    $check = $conn->prepare("SELECT COUNT(*) FROM san_pham WHERE slug = ?");
                    while (true) {
                        $check->execute([$slug]);
                        if ($check->fetchColumn() == 0) break;
                        $slug = $baseSlug . '-' . $i++;
                    }

                    // INSERT: bảng san_pham KHÔNG có cột hinh_anh_chinh
                    $stmt = $conn->prepare("
                        INSERT INTO san_pham (ten_san_pham, danh_muc_id, mo_ta, slug, trang_thai)
                        VALUES (?, ?, ?, ?, 'DangBan')
                    ");
                    $stmt->execute([$ten, $dm, $mota, $slug]);
                    $msg = 'Thêm mới sản phẩm thành công! Hãy vào chi tiết để thêm biến thể / giá.';
                } else {
                    // UPDATE
                    // Nếu đổi tên -> có thể đổi slug nhưng không được trùng với sản phẩm khác
                    $baseSlug = $slug;
                    $i = 1;
                    $check = $conn->prepare("SELECT COUNT(*) FROM san_pham WHERE slug = ? AND id <> ?");
                    while (true) {
                        $check->execute([$slug, $id]);
                        if ($check->fetchColumn() == 0) break;
                        $slug = $baseSlug . '-' . $i++;
                    }

                    $stmt = $conn->prepare("
                        UPDATE san_pham 
                        SET ten_san_pham = ?, danh_muc_id = ?, mo_ta = ?, slug = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$ten, $dm, $mota, $slug, $id]);
                    $msg = 'Cập nhật sản phẩm thành công!';
                }
            }
        }
    } catch (Exception $e) {
        $msg = 'Lỗi: ' . $e->getMessage();
    }
}

// 4. LẤY DỮ LIỆU HIỂN THỊ
$q      = trim($_GET['q'] ?? '');
$dm     = (int)($_GET['dm'] ?? 0);   // id danh mục chọn trong bộ lọc
$page   = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset  = ($page - 1) * $perPage;

// Điều kiện lọc
$whereParts = [];
$params     = [];

if ($q !== '') {
    $whereParts[] = "sp.ten_san_pham LIKE ?";
    $params[]     = "%$q%";
}

if ($dm > 0) {
    // LỌC THEO CHÍNH DANH MỤC VÀ CẢ CÁC DANH MỤC CON CỦA NÓ
    // sp.danh_muc_id = dm.id  (sản phẩm nằm trực tiếp trong dm)
    // dm.danh_muc_cha_id = dm  (sản phẩm nằm trong danh mục con của dm)
    $whereParts[] = "(sp.danh_muc_id = ? OR dm.danh_muc_cha_id = ?)";
    $params[]     = $dm;
    $params[]     = $dm;
}

$whereSql = $whereParts ? 'WHERE ' . implode(' AND ', $whereParts) : '';

// Đếm tổng số dòng sau khi lọc
$countSql = "
    SELECT COUNT(DISTINCT sp.id)
    FROM san_pham sp
    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
    $whereSql
";
$countStmt = $conn->prepare($countSql);
$countStmt->execute($params);
$totalRows  = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($totalRows / $perPage);

// Lấy danh sách sản phẩm + TỒN KHO + GIÁ + ẢNH (từ biến thể)
$sql = "
    SELECT 
        sp.*, 
        dm.ten_danh_muc,
        COALESCE(SUM(bt.so_luong_ton), 0)       AS tong_ton_kho,
        MIN(bt.gia_goc)                         AS min_gia_goc,
        MIN(bt.gia_ban)                         AS min_gia_ban,
        MIN(bt.hinh_anh_dai_dien)              AS hinh_anh_dai_dien
    FROM san_pham sp
    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
    LEFT JOIN bien_the_san_pham bt ON sp.id = bt.san_pham_id
    $whereSql
    GROUP BY sp.id
    ORDER BY sp.id DESC
    LIMIT ? OFFSET ?
";
$paramsList   = $params;
$paramsList[] = $perPage;
$paramsList[] = $offset;

$stmt = $conn->prepare($sql);
$stmt->execute($paramsList);
$products = $stmt->fetchAll();


// Lấy danh mục cho dropdown
$cats = $conn->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc")->fetchAll(PDO::FETCH_ASSOC);

// Stats
$stats = [
    'total'     => $conn->query("SELECT COUNT(*) FROM san_pham")->fetchColumn(),
    'out_stock' => $conn->query("SELECT COUNT(*) FROM bien_the_san_pham WHERE so_luong_ton = 0")->fetchColumn(),
    'low_stock' => $conn->query("SELECT COUNT(*) FROM bien_the_san_pham WHERE so_luong_ton <= 10 AND so_luong_ton > 0")->fetchColumn(),
];

// Header admin (chứa <html>, <body>, sidebar, ...)
$page_title = 'Quản lý Sản phẩm - 4P SHOP Admin';
$active     = 'products';
require __DIR__ . '/partials/header.php';
?>

<style>
    .glass { background: rgba(255, 255, 255, 0.95); border: 1px solid #f0f0f0; }
    .stat-card { transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-3px); }
    .product-img { width: 70px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
    .fade-in { animation: fadeIn 0.4s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
</style>

<main class="flex-1 overflow-y-auto bg-slate-50 p-6">

    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Sản phẩm</h1>
            <p class="text-sm text-gray-500">Quản lý danh sách quần áo</p>
        </div>

        <div class="flex gap-3">
            <button onclick="openModal()" class="px-4 py-2 bg-[#c72002] hover:bg-[#9a1902] text-white rounded-lg shadow-sm transition flex items-center gap-2 font-medium">
                <i class="fa-solid fa-plus"></i> Thêm mới
            </button>
        </div>
    </header>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 stat-card">
            <p class="text-gray-500 text-sm font-medium">Tổng mẫu mã</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($stats['total']) ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 stat-card">
            <p class="text-gray-500 text-sm font-medium">Mẫu hết hàng</p>
            <p class="text-2xl font-bold text-red-600 mt-1"><?= number_format($stats['out_stock']) ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 stat-card">
            <p class="text-gray-500 text-sm font-medium">Sắp hết (<=10)</p>
            <p class="text-2xl font-bold text-orange-500 mt-1"><?= number_format($stats['low_stock']) ?></p>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-wrap gap-4 items-center">
        <form method="GET" class="flex flex-1 gap-3 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm tên quần áo..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
            </div>
            <select name="dm" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                <option value="0">Tất cả danh mục</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $dm == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['ten_danh_muc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">Lọc</button>
            <a href="san-pham.php" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    <?php if ($msg): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> <?= $msg ?>
        </div>
    <?php endif; ?>

    <!-- Danh sách sản phẩm -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($products as $p):
            // Ưu tiên ảnh biến thể, nếu không có thì dùng placeholder
            $imgSrc = !empty($p['hinh_anh_dai_dien'])
                ? $p['hinh_anh_dai_dien']
                : '/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg';

            $badge = ($p['tong_ton_kho'] == 0)
                ? '<span class="ml-auto px-2 py-1 text-xs rounded bg-red-100 text-red-700 font-bold">Hết hàng</span>'
                : '<span class="ml-auto px-2 py-1 text-xs rounded bg-green-100 text-green-700 font-bold">Tổng tồn: ' . number_format($p['tong_ton_kho']) . '</span>';

            $priceDisplay = ($p['min_gia_ban'])
                ? number_format($p['min_gia_ban']) . 'đ'
                : '<span class="text-sm text-gray-400">Chưa có giá</span>';
        ?>
            <div class="glass rounded-xl p-4 fade-in hover:shadow-md transition flex flex-col">
                <div class="flex gap-4 mb-3">
                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="product-img" alt="Img"
                         onerror="this.src='https://placehold.co/70x90?text=No+Img'">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-800 truncate pr-2"
                                title="<?= htmlspecialchars($p['ten_san_pham']) ?>">
                                <?= htmlspecialchars($p['ten_san_pham']) ?>
                            </h3>
                        </div>

                        <p class="text-xs text-gray-500 mt-1 mb-2">
                            <span class="bg-gray-100 px-2 py-0.5 rounded">
                                <?= htmlspecialchars($p['ten_danh_muc'] ?? 'Khác') ?>
                            </span>
                        </p>

                        <div class="flex items-center justify-between mt-2">
                            <div>
                                <span class="text-lg font-bold text-[#c72002]"><?= $priceDisplay ?></span>
                            </div>
                            <?= $badge ?>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-t border-gray-100 flex gap-2 justify-end">
                    <a href="product_detail.php?id=<?= $p['id'] ?>"
                       class="px-3 py-1.5 text-sm border border-gray-300 text-gray-600 rounded hover:bg-gray-50">
                        Chi tiết / Biến thể
                    </a>

                    <button onclick='openModal(<?= json_encode($p, JSON_UNESCAPED_UNICODE) ?>)'
                            class="px-3 py-1.5 text-sm bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                        Sửa
                    </button>

                    <form method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="px-3 py-1.5 text-sm bg-red-50 text-red-600 rounded hover:bg-red-100">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex justify-center gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&q=<?= urlencode($q) ?>&dm=<?= $dm ?>"
                   class="px-4 py-2 rounded-lg <?= $i == $page ? 'bg-[#c72002] text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</main>

<!-- MODAL THÊM / SỬA SẢN PHẨM -->
<div id="productModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 p-6 transform scale-95 transition-transform duration-300" id="modalContent">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800" id="modalTitle">Thêm Sản Phẩm Mới</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form method="POST" id="productForm" class="space-y-4">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" id="prodId">

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
                    <input type="text" name="ten_san_pham" id="prodName" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#c72002] focus:outline-none">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                    <select name="danh_muc_id" id="prodCat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#c72002] focus:outline-none">
                        <?php foreach ($cats as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['ten_danh_muc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                    <textarea name="mo_ta" id="prodDesc" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-[#c72002] focus:outline-none"></textarea>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-4">
                <button type="button" onclick="closeModal()" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-[#c72002] text-white rounded-lg hover:bg-[#9a1902] font-medium">
                    Lưu thông tin
                </button>
            </div>

            <p class="text-xs text-center text-gray-500 mt-2">
                * Ghi chú: Ảnh, màu sắc, kích cỡ và giá sẽ cấu hình trong phần <b>Chi tiết / Biến thể</b>.
            </p>
        </form>
    </div>
</div>

<script>
    const modal        = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');
    const form         = document.getElementById('productForm');

    function openModal(data = null) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);

        if (data) {
            // Sửa
            document.getElementById('modalTitle').innerText = 'Sửa Sản Phẩm';
            document.getElementById('prodId').value   = data.id;
            document.getElementById('prodName').value = data.ten_san_pham;
            document.getElementById('prodCat').value  = data.danh_muc_id;
            document.getElementById('prodDesc').value = data.mo_ta || '';
        } else {
            // Thêm
            document.getElementById('modalTitle').innerText = 'Thêm Sản Phẩm Mới';
            form.reset();
            document.getElementById('prodId').value = '';
        }
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Click ra ngoài để đóng modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
</script>

</body>
</html>
