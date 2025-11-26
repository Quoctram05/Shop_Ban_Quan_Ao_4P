<?php
session_start();
require_once('../public/connect.php');
include('../header.php');
include('../navbar.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 2. Lấy lịch sử đơn hàng
$stmt_orders = $conn->prepare("SELECT * FROM don_hang WHERE nguoi_dung_id = ? ORDER BY id DESC");
$stmt_orders->execute([$user_id]);
$orders = $stmt_orders->fetchAll();
?>

<div class="container" style="padding: 40px 0;">
    <div class="flex flex-col md:flex-row gap-8">
        
        <div class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 text-center">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl text-[#b35d2a]">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h2 class="text-xl font-bold mb-1"><?php echo htmlspecialchars($user['ho_ten']); ?></h2>
                <p class="text-gray-500 text-sm mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                
                <hr class="my-4">
                
                <a href="../logout.php" class="block w-full py-2 px-4 bg-red-50 text-red-600 rounded hover:bg-red-100 font-medium transition">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Đăng xuất
                </a>
            </div>
        </div>

        <div class="w-full md:w-3/4">
            <h3 class="text-2xl font-bold mb-6 border-l-4 border-[#b35d2a] pl-3">Lịch sử đơn hàng</h3>
            
            <?php if (count($orders) > 0): ?>
                <div class="space-y-4">
                    <?php foreach ($orders as $order): ?>
                        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="font-bold text-lg text-[#b35d2a]">#<?php echo $order['id']; ?></span>
                                    <span class="text-gray-500 text-sm ml-2">| <?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></span>
                                </div>
                                <?php 
                                    $statusColor = 'bg-yellow-100 text-yellow-700';
                                    if($order['trang_thai'] == 'HoanThanh') $statusColor = 'bg-green-100 text-green-700';
                                    if($order['trang_thai'] == 'Huy') $statusColor = 'bg-red-100 text-red-700';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $statusColor; ?>">
                                    <?php echo $order['trang_thai']; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-end">
                                <div class="text-sm text-gray-600">
                                    <p><i class="fa-solid fa-location-dot mr-1"></i> <?php echo htmlspecialchars($order['dia_chi']); ?></p>
                                    <p class="mt-1"><i class="fa-solid fa-phone mr-1"></i> <?php echo htmlspecialchars($order['so_dien_thoai']); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Tổng tiền</p>
                                    <p class="text-xl font-bold text-[#b35d2a]"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                    <p class="text-gray-500">Bạn chưa có đơn hàng nào.</p>
                    <a href="../index.php" class="inline-block mt-4 text-[#b35d2a] font-medium hover:underline">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('../footer.php'); ?>