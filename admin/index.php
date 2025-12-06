<?php
// --- 1. KẾT NỐI CSDL ---
// Thay đường dẫn cho đúng với cấu trúc thư mục của bạn (thư mục 'public' nằm ở root)
require_once __DIR__ . '/../public/connect.php';
// Biến $conn (PDO) đã có sẵn

// --- 2. TÍNH TOÁN THỐNG KÊ ---
$stats = [
    'revenue_today' => 0,
    'orders_today' => 0,
    'low_stock' => 0,
    'total_products' => 0
];

try {
    // Doanh thu hôm nay
    $sql = "SELECT SUM(tong_tien) FROM don_hang WHERE DATE(ngay_dat) = CURDATE()";
    $stats['revenue_today'] = (float)$conn->query($sql)->fetchColumn();

    // Số đơn hàng hôm nay
    $sql = "SELECT COUNT(*) FROM don_hang WHERE DATE(ngay_dat) = CURDATE()";
    $stats['orders_today'] = (int)$conn->query($sql)->fetchColumn();

    // Sản phẩm sắp hết hàng (Dựa vào bảng biến thể)
    // Giả sử dưới 10 cái là sắp hết
    $sql = "SELECT COUNT(*) FROM bien_the_san_pham WHERE so_luong_ton <= 10 AND so_luong_ton > 0";
    $stats['low_stock'] = (int)$conn->query($sql)->fetchColumn();

    // Tổng số sản phẩm (Dựa vào bảng sản phẩm cha)
    $sql = "SELECT COUNT(*) FROM san_pham";
    $stats['total_products'] = (int)$conn->query($sql)->fetchColumn();

} catch (Exception $e) {
    // Nếu lỗi truy vấn thì để 0
}

// Hàm định dạng tiền tệ
if (!function_exists('money_vn')) {
    function money_vn($n) { return number_format((float)$n, 0, ',', '.'); }
}

// Cài đặt tiêu đề và menu active
$page_title = 'Trang chủ - Admin 4MEN Shop';
$active = 'home';

// Gọi Header
require __DIR__ . '/partials/header.php'; 

// Lấy thông tin admin từ session
$adminName = $_SESSION['user_name'] ?? 'Admin';
$adminRole = $_SESSION['user_role'] ?? 'QuanTriVien';
?>

<!-- =============================================== -->
<!-- NỘI DUNG CHÍNH DASHBOARD -->
<!-- =============================================== -->
<main class="flex-1 p-8 overflow-y-auto bg-slate-50/50">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Trang chủ</h2>
                <p class="text-gray-500 mt-1">Chào mừng quay trở lại, <?php echo htmlspecialchars($adminName); ?>!</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                        A
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold text-gray-700"><?php echo htmlspecialchars($adminName); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($adminRole); ?></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Các thẻ thống kê (Stats Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Card 1: Doanh thu -->
            <div class="dashboard-card bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-sack-dollar text-6xl text-orange-600"></i>
                </div>
                <h4 class="text-gray-500 font-medium text-sm uppercase tracking-wider">Doanh thu hôm nay</h4>
                <p class="text-3xl font-bold mt-2 text-gray-800">
                    <?=money_vn($stats['revenue_today'])?> <span class="text-lg text-gray-400">đ</span>
                </p>
                <p class="text-xs text-green-500 mt-2 font-medium flex items-center">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> Cập nhật realtime
                </p>
            </div>

            <!-- Card 2: Đơn hàng -->
            <div class="dashboard-card bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-cart-shopping text-6xl text-blue-600"></i>
                </div>
                <h4 class="text-gray-500 font-medium text-sm uppercase tracking-wider">Đơn hàng mới</h4>
                <p class="text-3xl font-bold mt-2 text-gray-800">
                    <?=$stats['orders_today']?>
                </p>
                <p class="text-xs text-blue-500 mt-2 font-medium">
                    Hôm nay
                </p>
            </div>

            <!-- Card 3: Sắp hết hàng -->
            <div class="dashboard-card bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-600"></i>
                </div>
                <h4 class="text-gray-500 font-medium text-sm uppercase tracking-wider">Sắp hết hàng</h4>
                <p class="text-3xl font-bold mt-2 text-gray-800">
                    <?=$stats['low_stock']?>
                </p>
                <p class="text-xs text-red-500 mt-2 font-medium">
                    Biến thể cần nhập thêm
                </p>
            </div>

            <!-- Card 4: Tổng sản phẩm -->
            <div class="dashboard-card bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-layer-group text-6xl text-purple-600"></i>
                </div>
                <h4 class="text-gray-500 font-medium text-sm uppercase tracking-wider">Tổng sản phẩm</h4>
                <p class="text-3xl font-bold mt-2 text-gray-800">
                    <?=$stats['total_products']?>
                </p>
                <p class="text-xs text-gray-400 mt-2 font-medium">
                    Đang kinh doanh
                </p>
            </div>
        </div>

        <!-- Khu vực Bảng đơn hàng mới nhất -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Đơn hàng vừa đặt</h3>
                <a href="don-hang.php" class="text-sm text-orange-600 hover:underline font-medium">Xem tất cả</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Mã đơn</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Khách hàng</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Tổng tiền</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Trạng thái</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        // Lấy 5 đơn hàng mới nhất từ bảng don_hang
                        try {
                            $stmt = $conn->query("SELECT * FROM don_hang ORDER BY id DESC LIMIT 5");
                            while ($row = $stmt->fetch()) {
                                // Xử lý màu trạng thái
                                $statusClass = 'bg-gray-100 text-gray-600';
                                if ($row['trang_thai'] == 'ChoXuLy') $statusClass = 'bg-yellow-100 text-yellow-700';
                                elseif ($row['trang_thai'] == 'HoanThanh') $statusClass = 'bg-green-100 text-green-700';
                                elseif ($row['trang_thai'] == 'Huy') $statusClass = 'bg-red-100 text-red-700';
                                
                                echo "<tr class='hover:bg-gray-50 transition'>";
                                echo "<td class='p-4 font-medium text-gray-900'>#{$row['id']}</td>";
                                echo "<td class='p-4 text-gray-600'>".htmlspecialchars($row['ho_ten'])."<br><span class='text-xs text-gray-400'>{$row['so_dien_thoai']}</span></td>";
                                echo "<td class='p-4 font-bold text-gray-800'>".money_vn($row['tong_tien'])."đ</td>";
                                echo "<td class='p-4'><span class='px-3 py-1 rounded-full text-xs font-bold {$statusClass}'>{$row['trang_thai']}</span></td>";
                                echo "<td class='p-4 text-sm text-gray-500'>".date('d/m/Y H:i', strtotime($row['ngay_dat']))."</td>";
                                echo "</tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' class='p-4 text-center text-red-500'>Lỗi tải dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Đóng thẻ main và body đã mở ở header.php -->
</div> 
</body>
</html>