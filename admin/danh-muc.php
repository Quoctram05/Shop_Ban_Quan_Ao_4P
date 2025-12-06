<?php
// admin/management.php - Quản lý Danh mục (Tương thích CSDL shop_thoi_trang_hoc)
session_start();

require_once __DIR__ . '/../public/connect.php';   // dùng chung
// alias $conn -> $pdo để giữ nguyên code cũ
$pdo = $conn;
// HELPER: Tạo Slug tự động từ tiếng Việt
function create_slug($string)
{
    $search = [
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\_]/",
    ];
    $replace = [
        'a', 'e', 'i', 'o', 'u', 'y', 'd',
        'A', 'E', 'I', 'O', 'U', 'Y', 'D',
        '-',
    ];
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    return strtolower(trim($string, '-'));
}

// ================== XỬ LÝ API (AJAX) ==================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' ||
    $_SERVER['REQUEST_METHOD'] === 'DELETE' ||
    (isset($_GET['action']) && $_GET['action'] == 'get_detail')
) {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    // 1. Lấy chi tiết danh mục
    if (isset($_GET['action']) && $_GET['action'] == 'get_detail' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM danh_muc WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch()]);
        exit;
    }

    // 2. Xóa danh mục
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM danh_muc WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error'   => 'Không thể xóa danh mục này (có thể đang chứa sản phẩm hoặc danh mục con).'
            ]);
        }
        exit;
    }

    // 3. Thêm / Sửa danh mục
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id     = $input['id'] ?? null;
        $ten    = trim($input['ten_danh_muc'] ?? '');
        $parent = !empty($input['danh_muc_cha_id']) ? $input['danh_muc_cha_id'] : null;

        $slug = !empty($input['slug']) ? $input['slug'] : create_slug($ten);

        if (empty($ten)) {
            echo json_encode(['success' => false, 'error' => 'Tên danh mục không được để trống']);
            exit;
        }

        try {
            if ($id) {
                // Update
                $sql  = "UPDATE danh_muc SET ten_danh_muc = ?, danh_muc_cha_id = ?, slug = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$ten, $parent, $slug, $id]);
            } else {
                // Insert
                $check = $pdo->prepare("SELECT COUNT(*) FROM danh_muc WHERE slug = ?");
                $check->execute([$slug]);
                if ($check->fetchColumn() > 0) {
                    $slug .= '-' . time();
                }

                $sql  = "INSERT INTO danh_muc (ten_danh_muc, danh_muc_cha_id, slug) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$ten, $parent, $slug]);
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

// ================== PHẦN HIỂN THỊ HTML ==================

// Lọc & phân trang
$q          = trim($_GET['q'] ?? '');
$filterType = $_GET['filter_type'] ?? 'all'; // all, root, sub
$perPage    = max(1, (int)($_GET['per'] ?? 8));
$page       = max(1, (int)($_GET['page'] ?? 1));
$offset     = ($page - 1) * $perPage;

$whereArr = [];
$params   = [];

if ($q !== '') {
    $whereArr[]   = "dm.ten_danh_muc LIKE :q";
    $params[':q'] = "%$q%";
}

if ($filterType === 'root') {
    $whereArr[] = "dm.danh_muc_cha_id IS NULL";
} elseif ($filterType === 'sub') {
    $whereArr[] = "dm.danh_muc_cha_id IS NOT NULL";
}

$whereSql = empty($whereArr) ? '' : 'WHERE ' . implode(' AND ', $whereArr);

// Đếm tổng số bản ghi sau lọc
$countSql = "SELECT COUNT(*) FROM danh_muc dm $whereSql";
$stmt     = $pdo->prepare($countSql);
$stmt->execute($params);
$totalFiltered = (int)$stmt->fetchColumn();
$pages         = max(1, (int)ceil($totalFiltered / $perPage));

// Lấy dữ liệu danh mục
$sql = "SELECT dm.*, p.ten_danh_muc AS ten_danh_muc_cha 
        FROM danh_muc dm
        LEFT JOIN danh_muc p ON dm.danh_muc_cha_id = p.id
        $whereSql
        ORDER BY dm.danh_muc_cha_id ASC, dm.ten_danh_muc ASC
        LIMIT :lim OFFSET :off";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

// Thống kê
$statsTotal = $pdo->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
$statsRoot  = $pdo->query("SELECT COUNT(*) FROM danh_muc WHERE danh_muc_cha_id IS NULL")->fetchColumn();
$statsSub   = $pdo->query("SELECT COUNT(*) FROM danh_muc WHERE danh_muc_cha_id IS NOT NULL")->fetchColumn();

// Danh sách danh mục gốc cho dropdown "Danh mục cha"
$parentList = $pdo->query("SELECT id, ten_danh_muc FROM danh_muc WHERE danh_muc_cha_id IS NULL ORDER BY ten_danh_muc")->fetchAll();

function build_url($q, $type, $page, $per)
{
    return htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query([
        'q'           => $q,
        'filter_type' => $type,
        'page'        => $page,
        'per'         => $per
    ]);
}

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý danh mục</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Card kính (glass) cho các box thống kê + item danh mục */
        .glass {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        /* Hiệu ứng xuất hiện nhẹ nhàng cho từng card */
        .fade-in {
            animation: fade 0.35s ease-out both;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Modal overlay */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 50;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        /* Box form trong modal */
        .modal > div {
            max-height: calc(100vh - 3rem);
            overflow-y: auto;
        }

        /* Card danh mục cao đều */
        .category-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .category-card-actions {
            margin-top: auto;
        }

        /* Grid danh mục co giãn full chiều ngang */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .category-grid {
                gap: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .glass {
                border-radius: 14px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            }
        }
    </style>
</head>
<body class="text-slate-800 font-sans">
<div class="flex min-h-screen w-full">
    <?php
    // Menu bên trái (hoặc header) – nếu chưa có thì dùng placeholder
    if (file_exists(__DIR__ . '/partials/header.php')) {
        $active = 'products';
        include __DIR__ . '/partials/header.php';
    } else {
        echo '<div class="w-64 bg-white border-r p-4 hidden md:block">Placeholder Menu</div>';
    }
    ?>

    <main class="flex-1 w-full relative z-10 p-4 md:p-6 overflow-x-hidden">        <!-- Tiêu đề + nút thêm -->
        <div class="w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Quản lý Danh mục</h1>
                <p class="text-slate-500 text-sm mt-1">
                    Quản lý cấu trúc phân loại sản phẩm của cửa hàng
                </p>
            </div>
            <div class="flex gap-2">
                <button onclick="openAddModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center gap-2 font-medium">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14m7-7H5"/>
                    </svg>
                    Thêm danh mục
                </button>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="glass p-5 rounded-2xl shadow-sm">
                <div class="text-slate-500 text-sm font-medium">Tổng danh mục</div>
                <div class="text-3xl font-bold text-slate-800 mt-1"><?= $statsTotal ?></div>
            </div>
            <div class="glass p-5 rounded-2xl shadow-sm border-l-4 border-green-500">
                <div class="text-slate-500 text-sm font-medium">Danh mục Gốc (Cấp 1)</div>
                <div class="text-3xl font-bold text-green-600 mt-1"><?= $statsRoot ?></div>
            </div>
            <div class="glass p-5 rounded-2xl shadow-sm border-l-4 border-amber-500">
                <div class="text-slate-500 text-sm font-medium">Danh mục Con</div>
                <div class="text-3xl font-bold text-amber-600 mt-1"><?= $statsSub ?></div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="glass rounded-2xl p-4 mb-6 shadow-sm">
            <form method="get" class="flex flex-col md:flex-row gap-3 items-center">
                <div class="relative flex-1 w-full">
                    <input
                        name="q"
                        value="<?= htmlspecialchars($q) ?>"
                        placeholder="Tìm kiếm tên danh mục..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                    >
                    <svg class="absolute left-3 top-2.5 text-slate-400" width="20" height="20" fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="9" cy="9" r="7"/>
                        <path d="m21 21-6-6"/>
                    </svg>
                </div>

                <select
                    name="filter_type"
                    class="px-4 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer"
                >
                    <option value="all">Tất cả loại</option>
                    <option value="root" <?= $filterType == 'root' ? 'selected' : '' ?>>Danh mục gốc</option>
                    <option value="sub" <?= $filterType == 'sub' ? 'selected' : '' ?>>Danh mục con</option>
                </select>

                <button
                    class="px-6 py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-900 transition font-medium">
                    Lọc
                </button>

                <?php if ($q !== '' || $filterType !== 'all'): ?>
                    <a href="management.php"
                       class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Xóa lọc
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Danh sách danh mục -->
        <div class="category-grid">
            <?php if (count($rows) == 0): ?>
                <div class="col-span-full py-12 text-center text-slate-400">
                    Không tìm thấy danh mục nào.
                </div>
            <?php endif; ?>

            <?php foreach ($rows as $r):
                $isRoot = empty($r['danh_muc_cha_id']);
                ?>
                <div
                    class="glass rounded-xl p-5 border hover:shadow-md transition fade-in relative group category-card">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <span
                                class="inline-block px-2 py-1 rounded text-xs font-bold mb-2
                                <?= $isRoot ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                                <?= $isRoot ? 'ROOT (Cấp 1)' : 'SUB (Danh mục con)' ?>
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 leading-tight mb-1">
                                <?= htmlspecialchars($r['ten_danh_muc']) ?>
                            </h3>
                            <div
                                class="text-xs text-slate-400 font-mono bg-slate-100 inline-block px-1.5 py-0.5 rounded">
                                /<?= htmlspecialchars($r['slug']) ?>
                            </div>
                        </div>

                        <?php if (!$isRoot): ?>
                            <div class="text-right">
                                <div class="text-xs text-slate-400 uppercase tracking-wider">Thuộc về</div>
                                <div class="text-sm font-medium text-blue-600">
                                    <?= htmlspecialchars($r['ten_danh_muc_cha']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div
                        class="mt-4 pt-4 border-t border-slate-100 flex gap-2 justify-end opacity-0 group-hover:opacity-100 transition-opacity category-card-actions">
                        <button
                            onclick="openEditModal(<?= $r['id'] ?>)"
                            class="px-3 py-1.5 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium">
                            Sửa
                        </button>
                        <button
                            onclick="deleteCategory(<?= $r['id'] ?>)"
                            class="px-3 py-1.5 text-sm bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium">
                            Xóa
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($pages > 1): ?>
            <div class="mt-8 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="<?= build_url($q, $filterType, $i, $perPage) ?>"
                       class="w-10 h-10 flex items-center justify-center rounded-lg border
                       <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 hover:bg-slate-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal Thêm / Sửa danh mục -->
<div id="categoryModal" class="modal">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform scale-100 transition-transform">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 id="modalTitle" class="text-xl font-bold text-slate-800">Thêm Danh mục</h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m18 6-12 12m0-12 12 12"/>
                </svg>
            </button>
        </div>

        <form id="categoryForm" class="space-y-4">
            <input type="hidden" id="categoryId">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tên danh mục <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="ten_danh_muc"
                    required
                    oninput="generateSlug()"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Đường dẫn (Slug)
                    <span class="text-slate-400 font-normal text-xs">(Tự động)</span>
                </label>
                <input
                    type="text"
                    id="slug"
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-600 focus:ring-2 focus:ring-blue-500 outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Danh mục cha
                </label>
                <select
                    id="danh_muc_cha_id"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                >
                    <option value="">-- Là danh mục gốc --</option>
                    <?php foreach ($parentList as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['ten_danh_muc'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-slate-500 mt-1">
                    Để trống nếu đây là danh mục cấp cao nhất.
                </p>
            </div>

            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex-1 px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 text-slate-700 font-medium">
                    Hủy
                </button>
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 font-medium transition">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const API_URL = 'management.php';

    // Helper: Tạo slug từ tiếng Việt phía client
    function stringToSlug(str) {
        str = str.toLowerCase();
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
        str = str.replace(/[đĐ]/g, "d");
        str = str.replace(/([^0-9a-z-\s])/g, '');
        str = str.replace(/(\s+)/g, '-');
        str = str.replace(/-+/g, '-');
        str = str.replace(/^-+|-+$/g, '');
        return str;
    }

    function generateSlug() {
        const name = document.getElementById('ten_danh_muc').value;
        document.getElementById('slug').value = stringToSlug(name);
    }

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Thêm Danh mục';
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryId').value = '';
        document.getElementById('categoryModal').classList.add('active');
    }

    async function openEditModal(id) {
        try {
            const response = await fetch(`${API_URL}?action=get_detail&id=${id}`);
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                document.getElementById('modalTitle').textContent = 'Sửa Danh mục';
                document.getElementById('categoryId').value = data.id;
                document.getElementById('ten_danh_muc').value = data.ten_danh_muc;
                document.getElementById('slug').value = data.slug;
                document.getElementById('danh_muc_cha_id').value = data.danh_muc_cha_id || '';

                document.getElementById('categoryModal').classList.add('active');
            } else {
                alert('Không tải được dữ liệu');
            }
        } catch (error) {
            console.error(error);
            alert('Có lỗi xảy ra khi tải dữ liệu');
        }
    }

    function closeModal() {
        document.getElementById('categoryModal').classList.remove('active');
    }

    document.getElementById('categoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('categoryId').value;
        const data = {
            id: id ? id : null,
            ten_danh_muc: document.getElementById('ten_danh_muc').value,
            slug: document.getElementById('slug').value,
            danh_muc_cha_id: document.getElementById('danh_muc_cha_id').value || null
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                alert('Lưu thành công!');
                location.reload();
            } else {
                alert('Lỗi: ' + result.error);
            }
        } catch (error) {
            alert('Có lỗi xảy ra: ' + error.message);
        }
    });

    async function deleteCategory(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa?')) return;

        try {
            const response = await fetch(`${API_URL}?id=${id}`, {method: 'DELETE'});
            const result = await response.json();

            if (result.success) {
                alert('Đã xóa!');
                location.reload();
            } else {
                alert('Lỗi: ' + result.error);
            }
        } catch (error) {
            alert('Lỗi kết nối');
        }
    }

    // Đóng modal khi click ra ngoài
    document.getElementById('categoryModal').addEventListener('click', (e) => {
        if (e.target.id === 'categoryModal') closeModal();
    });
</script>
</body>
</html>
