<?php
session_start();
require_once('../public/connect.php');
include('../header.php');

// 1. Lấy dữ liệu từ URL
$order_id = $_GET['id'] ?? 0;
$status = $_GET['status'] ?? ''; 
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #b35d2a; /* Màu cam thương hiệu */
        --primary-hover: #9a461e;
    }
    
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8fafc;
    }

    /* Animation cho Card xuất hiện */
    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation cho dấu tích xanh */
    @keyframes checkZoom {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-card {
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
    }

    .check-icon-box {
        animation: checkZoom 0.6s 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
    }

    /* Style cho box mã đơn hàng */
    .order-ticket {
        background-image: radial-gradient(circle at 0 50%, transparent 8px, #fff7ed 9px), 
                          radial-gradient(circle at 100% 50%, transparent 8px, #fff7ed 9px);
        background-position: 0 0, 0 0;
        background-size: 50% 100%;
        background-repeat: no-repeat;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.05));
    }
</style>

<div class="min-h-[80vh] flex items-center justify-center p-4">
    
    <div class="success-card bg-white w-full max-w-md rounded-3xl overflow-hidden border border-gray-100 relative">
        
        <div class="h-2 w-full bg-gradient-to-r from-[#b35d2a] to-[#f97316]"></div>

        <div class="p-8 text-center">
            
            <div class="check-icon-box w-24 h-24 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center relative">
                <div class="absolute inset-0 bg-green-200 rounded-full animate-ping opacity-25"></div>
                <i class="fa-solid fa-check text-5xl text-green-600"></i>
            </div>

            <?php if ($status == 'paid'): ?>
                <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Thanh toán thành công!</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Giao dịch của bạn đã được xác nhận. <br>Hệ thống đang xử lý đơn hàng ngay lập tức.
                </p>
            <?php else: ?>
                <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Đặt hàng thành công!</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Cảm ơn bạn đã mua sắm tại 4MEN. <br>Chúng tôi sẽ sớm liên hệ để xác nhận đơn hàng.
                </p>
            <?php endif; ?>

            <div class="order-ticket border-2 border-dashed border-[#b35d2a]/30 p-4 rounded-xl mb-8 mx-2 relative bg-orange-50">
                <p class="text-xs uppercase tracking-widest text-gray-500 font-bold mb-1">Mã đơn hàng của bạn</p>
                <div class="text-3xl font-black text-[#b35d2a] tracking-wider selection:bg-orange-200">
                    #<?php echo htmlspecialchars($order_id); ?>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">
                    <i class="fa-regular fa-copy mr-1"></i> Vui lòng lưu lại mã này
                </p>
            </div>

            <div class="space-y-3">
                <a href="../index.php" class="block w-full py-3.5 px-6 rounded-xl bg-[#b35d2a] hover:bg-[#9a461e] text-white font-bold shadow-lg shadow-orange-900/20 transition-all transform hover:-translate-y-1 text-decoration-none">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Tiếp tục mua sắm
                </a>

                <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="block w-full py-3.5 px-6 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold border border-gray-200 transition-all text-decoration-none">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Xem lịch sử đơn hàng
                </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include('../footer.php'); ?>