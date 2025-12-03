<?php
session_start();
require_once('../public/connect.php'); 

// 1. KIỂM TRA GIỎ HÀNG
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Giỏ hàng trống! Vui lòng chọn sản phẩm.'); window.location.href='../index.php';</script>";
    exit();
}

// 2. TÍNH TOÁN TỔNG TIỀN
$total_amount = 0;
$total_items = 0;

foreach ($_SESSION['cart'] as $item) {
    $qty = isset($item['qty']) ? $item['qty'] : 1; 
    $price = isset($item['price']) ? $item['price'] : 0;
    $total_amount += $price * $qty;
    $total_items += $qty;
}

// Phí vận chuyển
$shipping_fee = ($total_amount >= 300000) ? 0 : 30000;
$final_total = $total_amount + $shipping_fee;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - 4P Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root { --primary-color: #0284c7; --primary-light: #e0f2fe; --primary-dark: #0369a1; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        #pills-canvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: linear-gradient(to bottom, rgba(255, 106, 0, 0.6), rgba(150, 41, 2, 0.3)), linear-gradient(to bottom, #e0f7fa, #b3e5fc); }
        @keyframes fadeInUpAndGrow { from { opacity: 0; transform: translateY(30px) scale(0.95); filter: blur(5px); } to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); } }
        .login-card-animation { animation-name: fadeInUpAndGrow; animation-duration: 0.9s; animation-fill-mode: forwards; transition: transform 0.3s, box-shadow 0.3s; }
        .login-card-animation:hover { transform: translateY(-8px) rotateZ(-1.5deg) scale(1.01); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
        .login-button { transition: all 0.3s; background-color: var(--primary-color); color: white; font-weight: 600; border-radius: 0.5rem; }
        .login-button:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(2, 132, 199, 0.35); }
        .login-input { border: 1px solid #D1D5DB; width: 100%; border-radius: 0.5rem; padding: 0.75rem 1rem; background-color: rgba(255, 255, 255, 0.7); }
        .login-input:focus { border-color: var(--primary-color); outline: none; background-color: white; }
        .form-radio { color: var(--primary-color); }
        .form-radio:checked { background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e"); }
        .tab-active { background-color: var(--primary-color); color: white; }
        .tab-inactive { background-color: transparent; color: #4B5563; }
        /* Thêm style cho nút số lượng */
        .qty-btn { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; color: #333; transition: 0.2s; }
        .qty-btn:hover { background: #e5e7eb; }
        .qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body class="text-gray-800">

    <canvas id="pills-canvas"></canvas>
    
    <div class="relative min-h-screen w-full flex justify-center p-4 sm:p-6 lg:p-10">
        
        <div class="w-full max-w-7xl mx-auto">
            <div class="mb-4">
                <a href="../index.php" class="font-medium text-lg" style="color: var(--primary-dark);">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại mua sắm
                </a>
            </div>

            <form action="../api/process_checkout.php" method="POST">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.1s;">
                        <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Giỏ hàng của bạn (<?php echo $total_items; ?> sản phẩm)
                        </h2>

                        <?php if ($total_amount >= 300000): ?>
                        <div class="bg-sky-50/70 border border-sky-200 rounded-lg p-3 text-center mb-4">
                            <p class="font-medium" style="color: var(--primary-color);">
                                <i class="fas fa-truck-fast mr-2"></i>
                                Đơn hàng của bạn được Miễn phí vận chuyển!
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                            <?php 
                                $qty = $item['qty'] ?? 1;
                                $imgSrc = $item['image'];
                                if (strpos($imgSrc, '../') === 0) $imgSrc = substr($imgSrc, 3);
                                $imgSrc = '../' . $imgSrc; 
                                $currentSize = $item['size'] ?? 'M'; 
                                $currentColor = $item['color'] ?? '';
                                // Lấy ID sản phẩm gốc (để tìm biến thể)
                                $productId = $item['product_id'] ?? $item['id'];
                            ?>
                            
                            <div class="flex items-start gap-4 py-4 border-b border-gray-200 product-item" 
                                 data-id="<?php echo $key; ?>" 
                                 data-product-id="<?php echo $productId; ?>">
                                
                                <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-24 h-32 rounded-lg border border-gray-200 object-cover flex-shrink-0">
                                
                                <div class="flex-grow flex flex-col justify-between h-32">
                                    <div>
                                        <h3 class="font-medium text-gray-800 text-base"><?php echo htmlspecialchars($item['name']); ?></h3>
                                        
                                        <div class="flex flex-wrap gap-3 mt-2">
                                            <div class="flex items-center gap-1">
                                                <span class="text-xs text-gray-500">Màu:</span>
                                                <select class="border border-gray-300 rounded px-1 py-0.5 text-sm outline-none focus:border-sky-500 bg-white color-select">
                                                    <option value="<?php echo $currentColor; ?>"><?php echo $currentColor ? $currentColor : 'Mặc định'; ?></option>
                                                </select>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="text-xs text-gray-500">Size:</span>
                                                <select class="border border-gray-300 rounded px-1 py-0.5 text-sm outline-none focus:border-sky-500 bg-white size-select">
                                                    <option value="<?php echo $currentSize; ?>"><?php echo $currentSize; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <p class="stock-status text-xs text-red-500 font-medium mt-1"></p>
                                    </div>

                                    <div class="flex items-end justify-between w-full">
                                        <div class="font-semibold text-lg" style="color: var(--primary-color);">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center border border-gray-300 rounded bg-white">
                                                <button type="button" class="px-2 py-1 hover:bg-gray-100 text-gray-600 btn-decrease" data-id="<?php echo $key; ?>">-</button>
                                                <input type="text" value="<?php echo $qty; ?>" class="w-10 text-center border-none focus:ring-0 p-0 text-sm text-gray-900 qty-input" readonly>
                                                <button type="button" class="px-2 py-1 hover:bg-gray-100 text-gray-600 btn-increase" data-id="<?php echo $key; ?>">+</button>
                                            </div>
                                            
                                            <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors btn-remove" data-id="<?php echo $key; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.2s;">
                        <div class="mb-5">
                            <label class="block text-base font-medium text-gray-700 mb-3">Chọn hình thức nhận hàng</label>
                            <div class="flex rounded-lg border border-gray-300 p-1 bg-gray-100/60 w-full md:w-auto">
                                <button id="btn-giao-hang" type="button" class="flex-1 py-2 px-5 rounded-md text-sm font-semibold transition-all duration-200 tab-active">
                                    <i class="fas fa-truck mr-2"></i> Giao hàng tận nơi
                                </button>
                                <button id="btn-nhan-tai-nha-thuoc" type="button" class="flex-1 py-2 px-5 rounded-md text-sm font-semibold transition-all duration-200 tab-inactive" disabled style="opacity: 0.6;">
                                    <i class="fas fa-store mr-2"></i> Nhận tại cửa hàng
                                </button>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h3 class="text-lg font-semibold mb-3 flex items-center" style="color: var(--primary-dark);">
                                <i class="fas fa-user mr-2"></i> Thông tin người nhận
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                                    <input type="text" name="fullname" placeholder="Nguyễn Văn A" class="login-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" placeholder="09xxxxxxxx" class="login-input" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (nhận hóa đơn điện tử)</label>
                                    <input type="email" name="email" placeholder="email@example.com" class="login-input">
                                </div>
                            </div>
                        </div>

                        <div id="content-giao-hang" class="border-t border-gray-200 pt-5">
                            <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-map-marker-alt mr-2"></i> Địa chỉ giao hàng
                            </h2>
                            <div class="space-y-4" id="form-truoc-sap-nhap">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ đầy đủ <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" placeholder="Ví dụ: Số 2 Võ Oanh, Phường 25, Bình Thạnh, TP.HCM" class="login-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú giao hàng</label>
                                    <textarea name="note" placeholder="Ví dụ: Giao giờ hành chính..." rows="3" class="login-input"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.3s;">
                        <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-credit-card mr-2"></i> Chọn phương thức thanh toán
                        </h2>
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="form-radio h-5 w-5" checked>
                                <img src="https://cdn-icons-png.flaticon.com/512/2331/2331941.png" class="mx-3 w-8 h-8 object-contain">
                                <span class="font-medium">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all cursor-pointer">
                                <input type="radio" name="payment_method" value="bank" class="form-radio h-5 w-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/2168/2168726.png" class="mx-3 w-8 h-8 object-contain">
                                <span class="font-medium">Chuyển khoản ngân hàng (QR Code)</span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all cursor-pointer">
                                <input type="radio" name="payment_method" value="momo" class="form-radio h-5 w-5">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="mx-3 w-8 h-8 object-contain rounded">
                                <span class="font-medium">Ví MoMo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-10 self-start">
                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.2s;">
                        <h2 class="text-xl font-semibold mb-4 pb-4 border-b border-gray-200" style="color: var(--primary-dark);">
                            Tổng đơn hàng
                        </h2>
                        
                        <div class="space-y-2 text-base">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium"><?php echo number_format($total_amount, 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span id="phi-van-chuyen" class="font-medium <?php echo $shipping_fee == 0 ? 'text-green-600' : ''; ?>">
                                    <?php echo $shipping_fee == 0 ? 'Miễn phí' : number_format($shipping_fee, 0, ',', '.') . 'đ'; ?>
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-300">
                            <span class="text-xl font-bold">Tổng cộng:</span>
                            <span id="thanh-tien" class="text-2xl font-bold" style="color: var(--primary-color);">
                                <?php echo number_format($final_total, 0, ',', '.'); ?>đ
                            </span>
                        </div>

                        <input type="hidden" name="total_amount" value="<?php echo $final_total; ?>">

                        <button type="submit" class="login-button w-full flex justify-center py-3 px-4 mt-6 text-lg">
                            ĐẶT HÀNG NGAY
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-3">
                            Cam kết bảo mật thông tin khách hàng.
                        </p>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const canvas = document.getElementById('pills-canvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                let pills = [];
                const numberOfPills = 100;
                const colors = ['#ffffff', '#bae6fd', '#f0f9ff', '#0284c7'];
                class Pill {
                    constructor() { this.reset(); }
                    reset() {
                        this.x = Math.random() * canvas.width;
                        this.y = Math.random() * canvas.height;
                        this.size = Math.random() * 7 + 5;
                        this.speedY = Math.random() * 1 + 0.2;
                        this.color = colors[Math.floor(Math.random() * colors.length)];
                        this.opacity = Math.random() * 0.5 + 0.15;
                        this.angle = Math.random() * Math.PI * 2;
                    }
                    update() { this.y -= this.speedY; if (this.y < -this.size * 3) this.reset(); }
                    draw() {
                        ctx.save(); ctx.translate(this.x, this.y); ctx.rotate(this.angle); ctx.globalAlpha = this.opacity;
                        ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(0, 0, this.size, 0, Math.PI * 2); ctx.fill(); ctx.restore();
                    }
                }
                function init() { pills = Array.from({ length: numberOfPills }, () => new Pill()); }
                function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); pills.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
                init(); animate();
            }
        });
    </script>
    
    <script src="../assets/js/checkout.js"></script>
</body>
</html>