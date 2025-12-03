<?php
session_start();
require_once('../public/connect.php');
include('../header.php');

$order_id = $_GET['id'] ?? 0;
?>

<div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl text-center">
        <div>
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
                <i class="fa-solid fa-check text-4xl text-green-600"></i>
            </div>
            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">Đặt hàng thành công!</h2>
            <p class="mt-2 text-sm text-gray-600">
                Cảm ơn bạn đã mua sắm tại 4P Shop.
            </p>
        </div>
        
        <div class="py-4 border-t border-b border-gray-100">
            <p class="text-gray-700 mb-2">Mã đơn hàng của bạn:</p>
            <span class="text-3xl font-bold text-[#b35d2a]">#<?php echo htmlspecialchars($order_id); ?></span>
            <p class="text-sm text-gray-500 mt-4">
                Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng.<br>
                Vui lòng để ý điện thoại nhé!
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <a href="../index.php" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#b35d2a] hover:bg-[#9a1902] transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tiếp tục mua sắm
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                Xem lịch sử đơn hàng
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('../footer.php'); ?>