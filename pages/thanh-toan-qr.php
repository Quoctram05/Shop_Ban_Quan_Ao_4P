<?php
include('../public/connect.php');
$order_id = $_GET['order_id'] ?? 0;

// Lấy thông tin đơn hàng để hiển thị số tiền
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) die("Đơn hàng không tồn tại");

// CẤU HÌNH TÀI KHOẢN NGÂN HÀNG CỦA BẠN (SỬA Ở ĐÂY)
$bank_id = "VCBVCB"; // Ví dụ: MB, VCB, ACB...
$account_no = "1041817509"; // Số tài khoản của bạn
$account_name = "NGUYEN QUOC TRAM"; // Tên chủ tài khoản
$amount = $order['tong_tien'];
$description = "THANHTOAN DONHANG $order_id";

// Link tạo QR tự động của VietQR
$qr_url = "https://img.vietqr.io/image/$bank_id-$account_no-compact2.png?amount=$amount&addInfo=$description&accountName=$account_name";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán chuyển khoản</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg text-center max-w-md">
        <h2 class="text-2xl font-bold text-green-600 mb-4">Đặt hàng thành công!</h2>
        <p class="mb-4 text-gray-600">Mã đơn hàng: <b>#<?php echo $order_id; ?></b></p>
        <p class="mb-6">Vui lòng quét mã QR bên dưới để thanh toán:</p>
        
        <img src="<?php echo $qr_url; ?>" alt="QR Code" class="mx-auto w-64 border border-gray-200 rounded-lg">
        
        <p class="mt-4 text-xl font-bold text-red-500">
            <?php echo number_format($amount, 0, ',', '.'); ?>đ
        </p>
        <p class="text-sm text-gray-500 mt-2">Nội dung: <?php echo $description; ?></p>
        
        <a href="../index.php" class="block mt-6 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Về trang chủ
        </a>
    </div>
</body>
</html>