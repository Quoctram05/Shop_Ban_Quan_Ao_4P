<?php
session_start();
require_once('../public/connect.php');

$order_id = $_GET['order_id'] ?? 0;

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) die("Đơn hàng không tồn tại");

// --- XỬ LÝ KHI BẤM NÚT "XÁC NHẬN THANH TOÁN" ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Cập nhật trạng thái thành 'ChoXuLy' thay vì 'DaThanhToan'
    // Thêm ghi chú để Admin biết khách đã thao tác qua cổng MoMo
    $stmt_update = $conn->prepare("UPDATE don_hang SET trang_thai = 'ChoXuLy', ghi_chu = CONCAT(ghi_chu, ' [Khách đã xác nhận thanh toán MoMo]') WHERE id = ?");
    $stmt_update->execute([$order_id]);

    // 2. Chuyển hướng về trang Cảm ơn
    // LƯU Ý: Đã bỏ tham số "&status=paid" để trang cảm ơn hiện trạng thái "Chờ xử lý"
    header("Location: dat-hang-thanh-cong.php?id=$order_id");
    exit;
}

// Tạo mã QR MoMo giả lập
$momo_phone = "0901234567"; 
$amount = (int)$order['tong_tien'];
$content = "MOMO" . $order['id'];
$qr_url = "https://img.vietqr.io/image/MOMO-{$momo_phone}-compact2.png?amount={$amount}&addInfo={$content}";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán MoMo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f8; font-family: Arial, sans-serif; }
        .momo-bg { background-color: #a50064; } /* Màu hồng đặc trưng MoMo */
        .momo-text { color: #d82d8b; }
        .card-shadow { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .qr-frame { border: 2px solid #d82d8b; border-radius: 8px; padding: 5px; }
        
        /* Animation loading */
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        .expire-timer { animation: pulse 2s infinite; }
    </style>
</head>
<body>

    <div class="momo-bg h-60 w-full absolute top-0 left-0 z-0"></div>

    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4">
        
        <div class="mb-6 text-white text-center">
            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-16 bg-white rounded-xl p-2 mx-auto mb-2 shadow-lg">
            <h1 class="text-2xl font-bold">Cổng Thanh Toán MoMo</h1>
        </div>

        <div class="bg-white rounded-xl card-shadow w-full max-w-4xl flex flex-col md:flex-row overflow-hidden">
            
            <div class="w-full md:w-1/2 p-8 border-r border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Thông tin đơn hàng</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Mã đơn hàng:</span>
                        <span class="font-bold text-gray-800">#<?php echo $order['id']; ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Nhà cung cấp:</span>
                        <span class="font-medium">4P Shop</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Khách hàng:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($order['ho_ten']); ?></span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-gray-500">Số tiền:</span>
                        <span class="text-2xl font-bold momo-text"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <div class="mt-8 bg-yellow-50 border border-yellow-100 p-4 rounded-lg text-sm text-yellow-800">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Lưu ý: Đây là trang mô phỏng. Vui lòng bấm <b>"Xác nhận thanh toán"</b> bên dưới để hoàn tất quy trình đặt hàng.
                </div>
                
                <form method="POST" class="mt-6">
                    <button type="submit" class="w-full momo-bg hover:bg-[#8a0053] text-white font-bold py-3 px-4 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> XÁC NHẬN THANH TOÁN
                    </button>
                </form>
                
                <div class="mt-3 text-center">
                    <a href="../index.php" class="text-gray-500 hover:text-gray-800 text-sm underline">Hủy giao dịch</a>
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 bg-gray-50 flex flex-col items-center justify-center text-center">
                <h3 class="font-bold text-gray-800 mb-2">Quét mã để thanh toán</h3>
                <p class="text-sm text-gray-500 mb-6">Sử dụng <b>App MoMo</b> hoặc ứng dụng Camera hỗ trợ QR code</p>
                
                <div class="bg-white p-4 rounded-xl shadow-sm qr-frame mb-4">
                    <img src="<?php echo $qr_url; ?>" alt="QR MoMo" class="w-48 h-48 object-contain">
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 expire-timer">
                    <i class="fa-regular fa-clock"></i>
                    Đơn hàng hết hạn sau: <span class="font-bold text-red-500" id="countdown">10:00</span>
                </div>
            </div>
        </div>

        <p class="text-white/80 mt-6 text-xs">© 2025 MoMo Simulation. Protected by 4P.</p>
    </div>

    <script>
        let time = 600; // 10 phút
        const countdownEl = document.getElementById('countdown');
        setInterval(() => {
            const min = Math.floor(time / 60);
            let sec = time % 60;
            sec = sec < 10 ? '0' + sec : sec;
            countdownEl.innerText = `${min}:${sec}`;
            if (time > 0) time--;
        }, 1000);
    </script>

</body>
</html>