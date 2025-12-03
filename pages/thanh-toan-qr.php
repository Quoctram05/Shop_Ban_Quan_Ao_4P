<?php
session_start();
require_once('../public/connect.php');
include('../header.php');

// === CẤU HÌNH TÀI KHOẢN NHẬN TIỀN CỦA BẠN ===
$bank_id = 'VIB';          // Mã ngân hàng (VD: MB, VCB, ACB, VPB, TCB...)
$account_no = '364548760'; // Số tài khoản của bạn
$account_name = 'NGUYEN QUOC TRAM'; // Tên chủ tài khoản (viết hoa không dấu)
// ============================================

// Lấy ID đơn hàng từ URL
$order_id = $_GET['order_id'] ?? 0;

// Lấy thông tin đơn hàng để biết số tiền cần thanh toán
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container py-5 text-center'><h3>Đơn hàng không tồn tại!</h3><a href='../index.php' class='btn'>Về trang chủ</a></div>";
    include('../footer.php');
    exit;
}

// Tạo nội dung chuyển khoản: "THANHTOAN [Mã đơn]"
$content = "THANHTOAN " . $order['id'];
$amount = (int)$order['tong_tien'];

// Link tạo QR tự động (Dùng API miễn phí của VietQR)
$qr_url = "https://img.vietqr.io/image/{$bank_id}-{$account_no}-compact2.png?amount={$amount}&addInfo={$content}&accountName={$account_name}";
?>

<style>
    .qr-container {
        background: white;
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
    }
    .qr-image {
        width: 100%;
        max-width: 350px;
        margin: 20px auto;
        border: 2px solid #f3f4f6;
        border-radius: 12px;
    }
    .amount-text {
        color: #b35d2a; /* Màu cam chủ đạo */
        font-size: 28px;
        font-weight: 800;
        margin: 10px 0;
    }
    .bank-info {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        text-align: left;
        font-size: 14px;
    }
    .btn-completed {
        display: inline-block;
        background: #b35d2a;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 20px;
        transition: 0.3s;
    }
    .btn-completed:hover {
        background: #9a3412;
        transform: translateY(-2px);
    }
</style>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="container">
        <div class="qr-container">
            <div class="mb-4">
                <i class="fa-solid fa-circle-check text-green-500 text-5xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Đặt hàng thành công!</h1>
            <p class="text-gray-500">Mã đơn hàng: <b>#<?php echo $order['id']; ?></b></p>
            
            <hr class="my-4 border-gray-100">

            <p class="mb-2 font-medium text-gray-700">Vui lòng quét mã QR bên dưới để thanh toán:</p>
            
            <img src="<?php echo $qr_url; ?>" alt="QR Code" class="qr-image">
            
            <p class="text-sm text-gray-500">Tổng tiền cần thanh toán</p>
            <div class="amount-text"><?php echo number_format($amount, 0, ',', '.'); ?>đ</div>

            <div class="bank-info">
                <p><b>Ngân hàng:</b> <?php echo $bank_id; ?></p>
                <p><b>Số tài khoản:</b> <?php echo $account_no; ?></p>
                <p><b>Chủ tài khoản:</b> <?php echo $account_name; ?></p>
                <p><b>Nội dung:</b> <span class="text-red-500 font-bold"><?php echo $content; ?></span></p>
            </div>

            <div class="mt-4 text-left bg-blue-50 p-3 rounded text-sm text-blue-800">
                <p class="font-bold mb-1">⚠️ Lưu ý:</p>
                <ul class="list-disc pl-4 space-y-1">
                    <li>Sau khi chuyển khoản thành công, vui lòng bấm nút <b>"Tôi đã thanh toán"</b> bên dưới.</li>
                    <li>Nhân viên 4P sẽ gọi điện xác nhận đơn hàng của bạn trong vòng 15 phút.</li>
                    <li>Giữ lại ảnh chụp màn hình giao dịch để đối chiếu khi cần thiết.</li>
                </ul>
            </div>

            <a href="../index.php" class="btn-completed">
                <i class="fa-solid fa-check mr-2"></i> TÔI ĐÃ THANH TOÁN
            </a>
        </div>
    </div>
</div>

<?php include('../footer.php'); ?>