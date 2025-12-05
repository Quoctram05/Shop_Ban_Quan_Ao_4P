<?php
// admin/order_detail.php
$page_title = 'Chi tiết đơn hàng';
$active = 'orders';

// 1. KẾT NỐI DATABASE
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'shop_thoi_trang_hoc';

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

require_once __DIR__ . '/partials/header.php';

// Lấy ID đơn hàng
$id = $_GET['id'] ?? 0;
$msg = '';

// Định nghĩa map trạng thái (Khớp với don-hang.php)
$statusMap = [
    'ChoXuLy'   => ['label' => 'Chờ xử lý',   'class' => 'bg-amber-100 text-amber-700'],
    'DaXacNhan' => ['label' => 'Đã xác nhận', 'class' => 'bg-blue-100 text-blue-700'],
    'DangGiao'  => ['label' => 'Đang giao',   'class' => 'bg-purple-100 text-purple-700'],
    'HoanThanh' => ['label' => 'Hoàn thành',  'class' => 'bg-green-100 text-green-700'],
    'DaHuy'     => ['label' => 'Đã hủy',      'class' => 'bg-red-100 text-red-700'],
];

// --- XỬ LÝ CẬP NHẬT TRẠNG THÁI ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['trang_thai'];

    // Kiểm tra trạng thái hợp lệ trong map
    if (array_key_exists($newStatus, $statusMap)) {
        $stmt = $pdo->prepare("UPDATE don_hang SET trang_thai = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        $msg = '<div class="bg-emerald-100 text-emerald-700 p-4 rounded-xl mb-6 shadow-sm border border-emerald-200 flex items-center gap-2">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Cập nhật trạng thái thành công!
                </div>';
    }
}

// --- LẤY DỮ LIỆU ĐƠN HÀNG (MASTER) ---
$stmt = $pdo->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='p-10 text-center'>Không tìm thấy đơn hàng! <a href='don-hang.php' class='text-blue-600 hover:underline'>Quay lại</a></div>";
    exit;
}

// --- LẤY CHI TIẾT ĐƠN HÀNG (DETAIL + ẢNH BIẾN THỂ) ---
$stmtItems = $pdo->prepare("
    SELECT c.*,
           b.hinh_anh_dai_dien AS hinh_anh
    FROM chi_tiet_don_hang c
    LEFT JOIN bien_the_san_pham b ON c.bien_the_id = b.id
    WHERE c.don_hang_id = ?
");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

// Tính toán tổng tiền hàng (để hiển thị tạm tính)
$tongTienHang = 0;
foreach ($items as $it) {
    $tongTienHang += ($it['don_gia'] * $it['so_luong']);
}

// Lấy thông tin trạng thái hiện tại để hiển thị
$currentStatus = $statusMap[$order['trang_thai']] ?? ['label' => $order['trang_thai'], 'class' => 'bg-gray-100 text-gray-700'];
?>

<style>
    .glass { background: rgba(255,255,255,0.95); backdrop-filter: saturate(180%) blur(10px); }
</style>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-4">
                <a href="don-hang.php" class="p-2.5 rounded-full bg-white shadow-sm border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Đơn hàng #<?= $order['id'] ?></h1>
                    <p class="text-slate-500 text-sm">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['ngay_dat'])) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-lg font-bold text-sm <?= $currentStatus['class'] ?>">
                    <?= $currentStatus['label'] ?>
                </span>
                <button onclick="window.print()" class="p-2.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                </button>
            </div>
        </div>

        <?= $msg ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-lg mb-4 text-slate-800 flex items-center gap-2">
                        <svg class="text-blue-600" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 11V7a4 4 0 0 0-8 0v4"/><path d="M5 9h14l1 12H4L5 9z"/></svg>
                        Danh sách sản phẩm
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-slate-500 border-b uppercase tracking-wider">
                                    <th class="pb-3 pl-2">Sản phẩm</th>
                                    <th class="pb-3 text-center">Đơn giá</th>
                                    <th class="pb-3 text-center">SL</th>
                                    <th class="pb-3 text-right pr-2">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                <?php foreach ($items as $item):
                                    // Lấy path ảnh: dữ liệu CSDL đã là '../assets/img/...'
                                    $hinhAnh = !empty($item['hinh_anh'])
                                        ? $item['hinh_anh']
                                        : 'https://placehold.co/100x100?text=No+Image';
                                ?>
                                <tr>
                                    <td class="py-4 pl-2">
                                        <div class="flex items-center gap-4">
                                            <img src="<?= htmlspecialchars($hinhAnh) ?>"
                                                 class="w-16 h-16 object-cover rounded-lg border border-slate-100 shadow-sm"
                                                 onerror="this.src='https://placehold.co/100x100?text=Err'">
                                            <div>
                                                <div class="font-medium text-slate-800 line-clamp-2">
                                                    <?= htmlspecialchars($item['ten_san_pham']) ?>
                                                </div>
                                                <div class="text-xs text-slate-500 mt-1">
                                                    Mã SP: #<?= $item['san_pham_id'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-4 text-slate-600">
                                        <?= number_format($item['don_gia'], 0, ',', '.') ?>đ
                                    </td>
                                    <td class="text-center py-4 font-medium text-slate-800">
                                        x<?= $item['so_luong'] ?>
                                    </td>
                                    <td class="text-right py-4 pr-2 font-bold text-slate-800">
                                        <?= number_format($item['don_gia'] * $item['so_luong'], 0, ',', '.') ?>đ
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-2 items-end">
                        <div class="w-full md:w-1/2 space-y-2">
                            <div class="flex justify-between text-sm text-slate-600">
                                <span>Tạm tính:</span>
                                <span class="font-medium"><?= number_format($tongTienHang, 0, ',', '.') ?>đ</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-blue-600 pt-3 border-t border-slate-100 mt-2">
                                <span>Tổng thanh toán:</span>
                                <span><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-lg mb-4 text-slate-800">Cập nhật đơn hàng</h3>
                    <form method="POST">
                        <label class="block text-sm font-medium text-slate-600 mb-2">Trạng thái đơn hàng</label>
                        <select name="trang_thai" class="w-full p-2.5 border border-slate-300 rounded-lg bg-white mb-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <?php foreach ($statusMap as $key => $val): ?>
                                <option value="<?= $key ?>" <?= $order['trang_thai'] === $key ? 'selected' : '' ?>>
                                    <?= $val['label'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_status" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-sm shadow-blue-200">
                            Lưu thay đổi
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-lg mb-4 text-slate-800 flex items-center gap-2">
                        <svg class="text-blue-600" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Thông tin khách hàng
                    </h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="block text-slate-500 text-xs font-medium uppercase mb-1">Họ tên người nhận</span>
                            <span class="font-semibold text-slate-800 text-base">
                                <?= htmlspecialchars($order['ho_ten'] ?? 'Khách vãng lai') ?>
                            </span>
                        </div>
                        <div>
                            <span class="block text-slate-500 text-xs font-medium uppercase mb-1">Số điện thoại</span>
                            <span class="font-medium text-slate-800 flex items-center gap-2">
                                <svg class="text-slate-400" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.05 12.05 0 0 0 .57 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.03 12.03 0 0 0 2.81.57A2 2 0 0 1 22 16.92z"/></svg>
                                <?= htmlspecialchars($order['so_dien_thoai']) ?>
                            </span>
                        </div>
                        <div>
                            <span class="block text-slate-500 text-xs font-medium uppercase mb-1">Địa chỉ giao hàng</span>
                            <span class="font-medium text-slate-800 block leading-relaxed">
                                <?= nl2br(htmlspecialchars($order['dia_chi'])) ?>
                            </span>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <span class="block text-slate-500 text-xs font-medium uppercase mb-2">Thanh toán</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded border border-slate-200 bg-slate-50 text-slate-600 text-xs font-medium">
                                <?= strtoupper(htmlspecialchars($order['phuong_thuc_thanh_toan'] ?? 'COD')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
