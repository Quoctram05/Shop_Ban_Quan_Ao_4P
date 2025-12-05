<?php
/** admin/khach-hang.php
 *  Quản lý người dùng: Liệt kê / Thêm / Sửa / Xóa
 *  Dựa trên bảng: nguoi_dung (shop_thoi_trang_hoc)
 */

if (session_status() === PHP_SESSION_NONE) session_start();

/* ===== 1. KẾT NỐI DATABASE ===== */
$pdo = null;
$dbFile = __DIR__ . '/../public/connect.php';

if (file_exists($dbFile)) {
    require_once $dbFile;
    if (function_exists('pdo')) {
        $pdo = pdo();
    } elseif (function_exists('get_pdo')) {
        $pdo = get_pdo();
    }
}

if (!$pdo instanceof PDO) {
    // Fallback kết nối nếu không load được từ file chung
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=shop_thoi_trang_hoc;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (Throwable $e) {
        die('Lỗi kết nối database: ' . $e->getMessage());
    }
}

/* ===== 2. PHÂN QUYỀN (CHỈ QuanTriVien) ===== */
// Lấy role theo đúng cách các file admin khác đang dùng
$currentRole = $_SESSION['user_role'] ?? ($_SESSION['auth']['vai_tro'] ?? '');
// Nếu không phải Quản Trị Viên -> Chặn
if ($currentRole !== 'QuanTriVien') {
    http_response_code(403);
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>Truy cập bị từ chối</title><script src="https://cdn.tailwindcss.com"></script></head>';
    echo '<body class="bg-gray-100 h-screen flex items-center justify-center">';
    echo '<div class="bg-white p-8 rounded-lg shadow-md text-center">';
    echo '<h1 class="text-2xl font-bold text-red-600 mb-2">403 - Forbidden</h1>';
    echo '<p class="text-gray-600">Bạn không có quyền truy cập trang này.</p>';
    echo '<a href="/SHOP_BAN_QUAN_AO_4P/" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Về trang chủ</a>';
    echo '</div></body></html>';
    exit;
}

/* ===== 3. CSRF & HELPER ===== */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$CSRF = $_SESSION['csrf'];

// Hàm hiển thị badge vai trò
function get_role_badge($role)
{
    if ($role === 'QuanTriVien') {
        return '<span class="px-2 py-1 rounded text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">Quản Trị Viên</span>';
    }
    return '<span class="px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Khách Hàng</span>';
}

/* ===== 4. XỬ LÝ POST (CREATE / UPDATE / DELETE) ===== */
$flash = null;

// Lấy id người đang đăng nhập (để chặn tự xóa / update session)
$currentUserId = (int)($_SESSION['auth']['id'] ?? ($_SESSION['user_id'] ?? 0));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die('Lỗi xác thực form (CSRF). Vui lòng tải lại trang.');
    }

    $action = $_POST['action'] ?? '';

    try {
        /* --- TẠO MỚI --- */
        if ($action === 'create') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $sdt    = trim($_POST['so_dien_thoai'] ?? '');
            $pass   = trim($_POST['mat_khau'] ?? '');
            $role   = $_POST['vai_tro'] ?? 'KhachHang';

            if ($sdt === '') $sdt = null;

            if ($email === '' || $pass === '') {
                throw new Exception('Email và Mật khẩu là bắt buộc.');
            }
            if (strlen($pass) < 6) {
                throw new Exception('Mật khẩu phải từ 6 ký tự trở lên.');
            }

            // Check trùng Email hoặc SĐT (nếu có nhập)
            $checkSql = "SELECT COUNT(*) FROM nguoi_dung WHERE email = ?";
            $params   = [$email];
            if ($sdt !== null) {
                $checkSql .= " OR so_dien_thoai = ?";
                $params[] = $sdt;
            }
            $st = $pdo->prepare($checkSql);
            $st->execute($params);
            if ($st->fetchColumn() > 0) {
                throw new Exception('Email hoặc Số điện thoại đã tồn tại.');
            }

            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $sql  = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro) VALUES (?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$ho_ten, $email, $hash, $sdt, $role]);

            $flash = ['ok' => true, 'msg' => 'Thêm người dùng thành công!'];
        }

        /* --- CẬP NHẬT --- */
        elseif ($action === 'update') {
            $id     = (int)($_POST['id'] ?? 0);
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $sdt    = trim($_POST['so_dien_thoai'] ?? '');
            $pass   = trim($_POST['mat_khau'] ?? '');
            $role   = $_POST['vai_tro'] ?? 'KhachHang';

            if ($sdt === '') $sdt = null;

            if ($id <= 0) {
                throw new Exception('ID không hợp lệ.');
            }
            if ($email === '') {
                throw new Exception('Email không được để trống.');
            }

            // Check trùng (trừ chính nó)
            $checkSql = "SELECT COUNT(*) FROM nguoi_dung WHERE (email = ?";
            $params   = [$email];
            if ($sdt !== null) {
                $checkSql .= " OR so_dien_thoai = ?";
                $params[] = $sdt;
            }
            $checkSql .= ") AND id <> ?";
            $params[] = $id;

            $st = $pdo->prepare($checkSql);
            $st->execute($params);
            if ($st->fetchColumn() > 0) {
                throw new Exception('Email hoặc SĐT đã được sử dụng bởi tài khoản khác.');
            }

            if ($pass !== '') {
                if (strlen($pass) < 6) {
                    throw new Exception('Mật khẩu mới quá ngắn.');
                }
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                $sql  = "UPDATE nguoi_dung SET ho_ten=?, email=?, so_dien_thoai=?, vai_tro=?, mat_khau=? WHERE id=?";
                $pdo->prepare($sql)->execute([$ho_ten, $email, $sdt, $role, $hash, $id]);
            } else {
                $sql = "UPDATE nguoi_dung SET ho_ten=?, email=?, so_dien_thoai=?, vai_tro=? WHERE id=?";
                $pdo->prepare($sql)->execute([$ho_ten, $email, $sdt, $role, $id]);
            }

            // Nếu tự sửa mình -> update lại session nếu đang dùng kiểu auth
            if ($currentUserId === $id) {
                if (isset($_SESSION['auth'])) {
                    $_SESSION['auth']['ho_ten'] = $ho_ten;
                    $_SESSION['auth']['vai_tro'] = $role;
                }
                if (isset($_SESSION['user_name'])) {
                    $_SESSION['user_name'] = $ho_ten;
                }
                $_SESSION['user_role'] = $role;
            }

            $flash = ['ok' => true, 'msg' => 'Cập nhật thành công!'];
        }

        /* --- XÓA --- */
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID không hợp lệ.');
            }

            // Không cho tự xóa chính mình
            if ($currentUserId === $id) {
                throw new Exception('Không thể tự xóa tài khoản đang đăng nhập.');
            }

            $pdo->prepare("DELETE FROM nguoi_dung WHERE id=?")->execute([$id]);
            $flash = ['ok' => true, 'msg' => 'Đã xóa người dùng.'];
        }

    } catch (Exception $e) {
        $flash = ['ok' => false, 'msg' => $e->getMessage()];
    }

    // PRG Redirect để tránh resubmit form khi F5
    $_SESSION['flash_tk'] = $flash;
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($_GET));
    exit;
}

if (isset($_SESSION['flash_tk'])) {
    $flash = $_SESSION['flash_tk'];
    unset($_SESSION['flash_tk']);
}

/* ===== 5. LẤY DỮ LIỆU ===== */
$kw       = trim($_GET['q'] ?? '');
$sort     = ($_GET['sort'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$nextSort = $sort === 'asc' ? 'desc' : 'asc';

$sql    = "SELECT * FROM nguoi_dung WHERE 1=1";
$params = [];
if ($kw) {
    $sql       .= " AND (ho_ten LIKE ? OR email LIKE ? OR so_dien_thoai LIKE ?)";
    $params [] = "%$kw%";
    $params [] = "%$kw%";
    $params [] = "%$kw%";
}
$sql .= " ORDER BY id " . strtoupper($sort);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

/* ===== 6. HEADER ===== */
$page_title = 'Quản Lý Người Dùng';
$active     = 'accounts';

if (file_exists(__DIR__ . '/partials/header.php')) {
    require_once __DIR__ . '/partials/header.php';
} else {
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>'.$page_title.'</title><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-gray-50 flex h-screen">';
    echo '<div class="w-64 bg-white border-r p-4 hidden md:block">Sidebar (Placeholder)</div>';
}
?>
<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Quản Lý Người Dùng</h1>
                <p class="text-gray-500 text-sm mt-1">Danh sách khách hàng và quản trị viên.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form class="relative">
                    <input type="hidden" name="sort" value="<?= $sort ?>">
                    <input name="q" value="<?= htmlspecialchars($kw) ?>" 
                           placeholder="Tìm tên, email, sđt..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none w-64 shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                <a href="?q=<?=urlencode($kw)?>&sort=<?=$nextSort?>" 
                   class="p-2 border border-gray-300 bg-white rounded-lg hover:bg-gray-50 text-gray-600 flex items-center gap-1" title="Sắp xếp ID">
                    <?= $sort === 'asc' ? '▲ Cũ nhất' : '▼ Mới nhất' ?>
                </a>

                <button onclick="openModal('create')" 
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 shadow-sm flex items-center gap-2 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Thêm mới
                </button>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="p-4 rounded-lg border flex items-center gap-3 <?= $flash['ok'] ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' ?>">
                <?php if($flash['ok']): ?>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?php else: ?>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php endif; ?>
                <span><?= htmlspecialchars($flash['msg']) ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold w-20">ID</th>
                            <th class="px-6 py-4 font-semibold">Thông tin cá nhân</th>
                            <th class="px-6 py-4 font-semibold">Liên hệ</th>
                            <th class="px-6 py-4 font-semibold text-center">Vai trò</th>
                            <th class="px-6 py-4 font-semibold text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($rows as $r): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-gray-500">#<?= $r['id'] ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($r['ho_ten'] ?? 'Chưa cập nhật') ?></div>
                                <div class="text-xs text-gray-400 mt-0.5">Ngày tạo: <?= date('d/m/Y H:i', strtotime($r['ngay_tao'])) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700"><?= htmlspecialchars($r['email']) ?></div>
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($r['so_dien_thoai'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?= get_role_badge($r['vai_tro']) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openModal('edit', <?= htmlspecialchars(json_encode([
                                        'id' => $r['id'],
                                        'ho_ten' => $r['ho_ten'],
                                        'email' => $r['email'],
                                        'so_dien_thoai' => $r['so_dien_thoai'],
                                        'vai_tro' => $r['vai_tro']
                                    ])) ?>)" 
                                            class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-200" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00-2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này? Dữ liệu đơn hàng liên quan có thể bị ảnh hưởng (hoặc lỗi nếu có ràng buộc khóa ngoại).');">
                                        <input type="hidden" name="csrf" value="<?= $CSRF ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button class="p-2 rounded-lg text-red-600 hover:bg-red-50 border border-transparent hover:border-red-200" title="Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                    Không tìm thấy dữ liệu nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
            <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center">
                <h3 id="modal-title" class="text-lg font-bold text-white">Thêm người dùng</h3>
                <button onclick="closeModal()" class="text-emerald-100 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form method="POST" class="p-6 space-y-4">
                <input type="hidden" name="csrf" value="<?= $CSRF ?>">
                <input type="hidden" name="action" id="inp-action" value="create">
                <input type="hidden" name="id" id="inp-id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                        <input type="text" name="ho_ten" id="inp-ho-ten" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="inp-email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" id="inp-sdt" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mật khẩu <span id="pass-hint" class="text-xs text-red-500 font-normal hidden">(Để trống nếu không muốn đổi mật khẩu)</span>
                        </label>
                        <input type="password" name="mat_khau" id="inp-password" placeholder="••••••"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                        <select name="vai_tro" id="inp-vai-tro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                            <option value="KhachHang">Khách Hàng</option>
                            <option value="QuanTriVien">Quản Trị Viên</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">Hủy</button>
                    <button type="submit" class="px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-medium shadow">Lưu dữ liệu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const inpAction = document.getElementById('inp-action');
    const inpId = document.getElementById('inp-id');
    const inpHoTen = document.getElementById('inp-ho-ten');
    const inpEmail = document.getElementById('inp-email');
    const inpSdt = document.getElementById('inp-sdt');
    const inpPass = document.getElementById('inp-password');
    const inpRole = document.getElementById('inp-vai-tro');
    const passHint = document.getElementById('pass-hint');

    function openModal(mode, data = null) {
        modal.classList.remove('hidden');
        if (mode === 'create') {
            modalTitle.textContent = "Thêm người dùng mới";
            inpAction.value = 'create';
            inpId.value = '';
            inpHoTen.value = '';
            inpEmail.value = '';
            inpSdt.value = '';
            inpPass.value = '';
            inpPass.required = true;  // Bắt buộc nhập pass khi tạo mới
            passHint.classList.add('hidden');
            inpRole.value = 'KhachHang';
        } else {
            modalTitle.textContent = "Cập nhật thông tin";
            inpAction.value = 'update';
            inpId.value = data.id;
            inpHoTen.value = data.ho_ten || '';
            inpEmail.value = data.email || '';
            inpSdt.value = data.so_dien_thoai || '';
            inpPass.value = '';
            inpPass.required = false; // Không bắt buộc nhập pass khi sửa
            passHint.classList.remove('hidden');
            inpRole.value = data.vai_tro;
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
    
    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if(e.key === "Escape") closeModal();
    });
</script>

</div>
</body>
</html>