<?php
session_start();
// Đảm bảo đường dẫn này đúng với cấu trúc thư mục của bạn
require_once('../public/connect.php'); 
include('../header.php');
include('../navbar.php');

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 3. Lấy lịch sử đơn hàng
$stmt_orders = $conn->prepare("SELECT * FROM don_hang WHERE nguoi_dung_id = ? ORDER BY id DESC");
$stmt_orders->execute([$user_id]);
$orders = $stmt_orders->fetchAll();

// 4. Thống kê nhanh
$total_orders = count($orders);
$total_spent = 0;
foreach ($orders as $o) {
    if ($o['trang_thai'] != 'Huy') {
        $total_spent += $o['tong_tien'];
    }
}
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #b35d2a; /* Màu cam đất chủ đạo */
        --primary-light: #fff7ed;
        --gray-light: #f9fafb;
    }
    
    body { background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

    /* Animation */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up { animation: slideUp 0.5s ease-out forwards; }
    .delay-100 { animation-delay: 0.1s; }

    /* Sidebar Menu */
    .profile-menu-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px;
        color: #4b5563; font-weight: 500;
        border-radius: 8px; transition: all 0.2s;
        text-decoration: none;
    }
    .profile-menu-item:hover, .profile-menu-item.active {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    .profile-menu-item i { width: 20px; text-align: center; }

    /* Order Card */
    .order-card {
        background: white; border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden; transition: all 0.3s;
        margin-bottom: 20px;
    }
    .order-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #fdba74;
        transform: translateY(-2px);
    }

    /* Tabs lọc trạng thái */
    .filter-tab {
        padding: 8px 16px; border-radius: 99px;
        font-size: 14px; font-weight: 600; color: #6b7280;
        cursor: pointer; transition: all 0.2s;
        border: 1px solid transparent; background: none; white-space: nowrap;
    }
    .filter-tab:hover { background-color: #fff; color: #333; }
    .filter-tab.active {
        background-color: var(--primary); color: white;
        box-shadow: 0 4px 6px -1px rgba(180, 83, 9, 0.2);
    }
    
    /* Status Badge Colors */
    .badge-status { 
        padding: 4px 10px; border-radius: 6px; font-size: 12px; 
        font-weight: 700; text-transform: uppercase; display: inline-block;
    }
    .status-ChoXuLy { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .status-DangGiao { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .status-HoanThanh { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .status-Huy { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
</style>

<div class="container mx-auto px-4 py-8 max-w-7xl min-h-screen">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-3 space-y-6 animate-fade-up">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-r from-[#b35d2a] to-[#ea580c]"></div>
                
                <div class="relative mt-4">
                    <div class="w-24 h-24 mx-auto bg-white p-1 rounded-full shadow-md">
                        <div class="w-full h-full bg-orange-50 rounded-full flex items-center justify-center text-4xl text-[#b35d2a]">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mt-3"><?php echo htmlspecialchars($user['ho_ten']); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <div class="mt-4 flex justify-center gap-2">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">Thành viên thân thiết</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Đơn hàng</p>
                        <p class="text-lg font-bold text-gray-800"><?php echo $total_orders; ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Chi tiêu</p>
                        <p class="text-lg font-bold text-[#b35d2a]"><?php echo number_format($total_spent); ?>đ</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <nav class="space-y-1 flex flex-col">
                    <a href="#" class="profile-menu-item active">
                        <i class="fa-solid fa-box-open"></i> Đơn mua của tôi
                    </a>
                    <a href="#" class="profile-menu-item">
                        <i class="fa-solid fa-user-gear"></i> Hồ sơ cá nhân
                    </a>
                    <a href="#" class="profile-menu-item">
                        <i class="fa-solid fa-map-location-dot"></i> Sổ địa chỉ
                    </a>
                    <div class="border-t border-gray-100 my-2"></div>
                    <a href="../logout.php" onclick="return confirm('Bạn có chắc muốn đăng xuất?')" class="profile-menu-item text-red-600 hover:bg-red-50 hover:text-red-700">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <div class="lg:col-span-9 animate-fade-up delay-100">
            
            <div class="flex flex-wrap items-center gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
                <button class="filter-tab active" onclick="filterOrders('all', this)">Tất cả</button>
                <button class="filter-tab" onclick="filterOrders('ChoXuLy', this)">Chờ xử lý</button>
                <button class="filter-tab" onclick="filterOrders('DangGiao', this)">Đang giao</button>
                <button class="filter-tab" onclick="filterOrders('HoanThanh', this)">Hoàn thành</button>
                <button class="filter-tab" onclick="filterOrders('Huy', this)">Đã hủy</button>
            </div>

            <div id="order-list" class="space-y-4">
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <?php 
                            // Lấy chi tiết sản phẩm
                            $stmt_items = $conn->prepare("SELECT ct.*, b.hinh_anh_dai_dien FROM chi_tiet_don_hang ct LEFT JOIN bien_the_san_pham b ON ct.bien_the_id = b.id WHERE don_hang_id = ?");
                            $stmt_items->execute([$order['id']]);
                            $items = $stmt_items->fetchAll();
                            
                            // Xác định trạng thái
                            $statusKey = $order['trang_thai'];
                            $statusLabel = match($statusKey) {
                                'ChoXuLy' => 'Chờ xử lý',
                                'DangGiao' => 'Đang giao hàng',
                                'HoanThanh' => 'Giao thành công',
                                'Huy' => 'Đã hủy',
                                default => $statusKey
                            };
                        ?>
                        
                        <div class="order-card p-6 group" data-status="<?php echo $statusKey; ?>">
                            <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="text-lg font-bold text-gray-900">#<?php echo $order['id']; ?></span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-sm text-gray-500"><?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></span>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fa-regular fa-credit-card"></i> Thanh toán: <span class="uppercase font-semibold"><?php echo $order['phuong_thuc_thanh_toan']; ?></span>
                                    </p>
                                </div>
                                <span class="badge-status status-<?php echo $statusKey; ?>">
                                    <?php echo $statusLabel; ?>
                                </span>
                            </div>

                            <div class="space-y-4">
                                <?php foreach ($items as $index => $item): ?>
                                    <?php 
                                        // Xử lý ảnh
                                        $imgSrc = !empty($item['hinh_anh_dai_dien']) 
                                            ? '../' . str_replace('../', '', $item['hinh_anh_dai_dien']) 
                                            : '../assets/img/no-image.jpg';
                                    ?>
                                    <div class="flex gap-4">
                                        <div class="w-16 h-20 flex-shrink-0 rounded-md border border-gray-200 overflow-hidden bg-gray-50">
                                            <img src="<?php echo $imgSrc; ?>" class="w-full h-full object-cover" alt="Img" onerror="this.src='https://via.placeholder.com/64x80'">
                                        </div>
                                        
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($item['ten_san_pham']); ?></h4>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">x<?php echo $item['so_luong']; ?></span>
                                                <span class="text-sm font-medium text-gray-900"><?php echo number_format($item['don_gia'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a href="#" class="text-sm text-[#b35d2a] font-medium hover:underline">Xem chi tiết</a>
                                <div class="text-right">
                                    <span class="text-sm text-gray-500 mr-2">Thành tiền:</span>
                                    <span class="text-xl font-bold text-[#b35d2a]"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Chưa có đơn hàng nào</h3>
                        <p class="text-gray-500 mb-6">Hãy khám phá các sản phẩm mới nhất của chúng tôi.</p>
                        <a href="../index.php" class="px-6 py-3 bg-[#b35d2a] text-white rounded-lg font-semibold hover:bg-[#9a1902] transition text-decoration-none inline-block">
                            Mua sắm ngay
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include('../footer.php'); ?>

<script>
function filterOrders(status, btn) {
    // 1. Active nút
    document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // 2. Lọc danh sách
    const orders = document.querySelectorAll('.order-card');
    orders.forEach(order => {
        if (status === 'all' || order.dataset.status === status) {
            order.style.display = 'block';
            order.style.animation = 'slideUp 0.3s ease-out forwards';
        } else {
            order.style.display = 'none';
        }
    });
}
</script>
</body>
</html>