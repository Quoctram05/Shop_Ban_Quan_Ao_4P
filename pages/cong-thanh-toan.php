<?php
session_start();
require_once('../public/connect.php');

$order_id = $_GET['order_id'] ?? 0;
$error = '';

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) die("Đơn hàng không tồn tại");

// --- XỬ LÝ KHI BẤM THANH TOÁN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank_user = $_POST['bank_user'];
    $bank_pass = $_POST['bank_pass'];

    // Giả lập kiểm tra tài khoản ngân hàng (Demo: admin / 123456)
    if ($bank_user === 'admin' && $bank_pass === '123456') {
        
        // 1. Cập nhật trạng thái thành 'ChoXuLy'
        // Ghi chú thêm là khách đã thao tác chuyển khoản
        $stmt_update = $conn->prepare("UPDATE don_hang SET trang_thai = 'ChoXuLy', ghi_chu = CONCAT(ghi_chu, ' [Khách đã thanh toán E-Banking - Chờ duyệt]') WHERE id = ?");
        $stmt_update->execute([$order_id]);

        // 2. Chuyển hướng về trang thành công
        // Bỏ tham số &status=paid để hiện badge "Chờ xử lý"
        header("Location: dat-hang-thanh-cong.php?id=$order_id");
        exit;
    } else {
        $error = "Tài khoản hoặc mật khẩu ngân hàng không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Thanh Toán An Toàn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-[#0056b3] p-6 text-center">
            <h2 class="text-white text-2xl font-bold uppercase tracking-wider">
                <i class="fa-solid fa-building-columns mr-2"></i> Secure Bank
            </h2>
            <p class="text-blue-100 text-sm mt-1">Cổng thanh toán trực tuyến an toàn</p>
        </div>

        <div class="p-8">
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600 text-sm">Đơn hàng:</span>
                    <span class="font-bold text-gray-800">#<?php echo $order['id']; ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600 text-sm">Nội dung:</span>
                    <span class="font-medium text-gray-800">Thanh toán mua hàng 4P</span>
                </div>
                <div class="flex justify-between border-t border-blue-200 pt-2 mt-2">
                    <span class="text-gray-600 font-bold">Số tiền:</span>
                    <span class="text-xl font-bold text-[#0056b3]"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded mb-4 text-sm border border-red-200 text-center animate-bounce">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tên đăng nhập E-Banking</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="bank_user" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Nhập user (test: admin)" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mật khẩu</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400"></i>
                        <input type="password" name="bank_pass" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Nhập pass (test: 123456)" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0056b3] hover:bg-[#004494] text-white font-bold py-3 rounded transition shadow-lg transform active:scale-95">
                    XÁC NHẬN THANH TOÁN
                </button>
                
                <div class="text-center mt-4">
                    <a href="../index.php" class="text-sm text-gray-500 hover:text-gray-800 underline">Hủy giao dịch</a>
                </div>
            </form>
        </div>
        
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-400">
                <i class="fa-solid fa-shield-halved mr-1"></i> Giao dịch được bảo mật bởi SSL 256-bit
            </p>
        </div>
    </div>

</body>
</html>