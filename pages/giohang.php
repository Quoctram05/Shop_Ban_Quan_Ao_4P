<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - 4MEN Fashion</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0284c7; /* sky-600 */
            --primary-light: #e0f2fe; /* sky-100 */
            --primary-dark: #0369a1;  /* sky-700 */
        }
        body { 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden; 
        }

        #pills-canvas {
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            z-index: -1; 
            background: 
                linear-gradient(to bottom, rgba(255, 106, 0, 0.6), rgba(150, 41, 2, 0.3)),
                linear-gradient(to bottom, #e0f7fa, #b3e5fc);
        }

        @keyframes fadeInUpAndGrow {
            from { opacity: 0; transform: translateY(30px) scale(0.95); filter: blur(5px); }
            to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        .login-card-animation {
            animation-name: fadeInUpAndGrow;
            animation-duration: 0.9s;
            animation-timing-function: ease-out;
            animation-delay: 0s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .login-card-animation:hover {
            transform: translateY(-8px) rotateZ(-1.5deg) scale(1.01);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(2, 132, 199, 0.3); }
            50% { box-shadow: 0 0 35px rgba(2, 132, 199, 0.6); }
        }
        .login-button {
            transition: all 0.3s ease-in-out;
            animation: pulse-glow 2.5s infinite ease-in-out;
            box-shadow: 0 4px 14px 0 rgba(2, 132, 199, 0.25);
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        .login-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px 0 rgba(2, 132, 199, 0.35);
        }
        .login-button:active {
            transform: scale(0.98);
            box-shadow: 0 2px 10px 0 rgba(2, 132, 199, 0.2);
        }
        
        .login-input {
            transition: all 0.2s ease-in-out;
            border: 1px solid #D1D5DB;
            width: 100%;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: rgba(255, 255, 255, 0.7);
        }
        .login-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
            outline: none;
            background-color: white;
        }
        .login-input:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        .toggle-checkbox:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        .toggle-checkbox:checked + .toggle-label { background-color: var(--primary-color); }
        
        .form-radio { color: var(--primary-color); }
        .form-radio:checked { background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e"); }

        .tab-inactive { background-color: transparent; color: #4B5563; }
        .tab-inactive:hover { background-color: rgba(255, 255, 255, 0.8); }
        .tab-active { background-color: var(--primary-color); color: white; box-shadow: 0 4px 14px 0 rgba(2, 132, 199, 0.25); }

        .modal-backdrop {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); z-index: 40; opacity: 0;
            transition: opacity 0.3s ease-out; pointer-events: none;
        }
        .modal-backdrop.active { opacity: 1; pointer-events: auto; }
        .modal-panel {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95);
            background-color: white; border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            z-index: 50; width: 90%; max-width: 640px; opacity: 0;
            transition: all 0.3s ease-out; pointer-events: none;
        }
        .modal-panel.active { opacity: 1; transform: translate(-50%, -50%) scale(1); pointer-events: auto; }

        .slot-button {
            border: 1px solid #D1D5DB; background-color: white; color: #1F2937;
            border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500;
            transition: all 0.2s;
        }
        .slot-button:hover { background-color: #F9FAFB; }
        .slot-button.selected {
            border-color: var(--primary-color); background-color: var(--primary-light);
            color: var(--primary-dark); font-weight: 600;
        }
        .slot-button:disabled { background-color: #F3F4F6; color: #9CA3AF; cursor: not-allowed; }
    </style>
</head>
<body class="text-gray-800">

    <canvas id="pills-canvas"></canvas>
    
    <div class="relative min-h-screen w-full flex justify-center p-4 sm:p-6 lg:p-10">
        
        <div class="w-full max-w-7xl mx-auto">
            <div class="mb-4">
                <a href="#" class="font-medium text-lg" style="color: var(--primary-dark);">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại mua sắm
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.1s;">
                        <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Giỏ hàng của bạn
                        </h2>

                        <div class="bg-sky-50/70 border border-sky-200 rounded-lg p-3 text-center mb-4">
                            <p class="font-medium" style="color: var(--primary-color);">
                                <i class="fas fa-truck-fast mr-2"></i>
                                Miễn phí vận chuyển cho đơn hàng thời trang trên 300.000đ
                            </p>
                        </div>
                        
                        <div class="flex items-start gap-4 py-4 border-b border-gray-200">
                            <img src="https://placehold.co/100x100/e0f2fe/0284c7?text=Ao+Polo" alt="Sản phẩm 1" class="w-24 h-24 rounded-lg border border-gray-200 object-cover">
                            <div class="flex-grow">
                                <h3 class="font-medium text-gray-800">Áo Polo Nam 4MEN Phối Bo Cổ Form Regular - Đen</h3>
                                <p class="text-sm text-gray-500">Size: L | Màu: Đen</p>
                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        <span class="font-semibold text-lg" style="color: var(--primary-color);">350.000đ</span>
                                        <span class="text-gray-400 line-through ml-2">420.000đ</span>
                                    </div>
                                    <input type="number" value="1" min="1" max="10" class="login-input w-20 text-center py-1 px-2">
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash-alt fa-lg"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-start gap-4 py-4">
                            <img src="https://placehold.co/100x100/e0f2fe/0284c7?text=Quan+Jean" alt="Sản phẩm 2" class="w-24 h-24 rounded-lg border border-gray-200 object-cover">
                            <div class="flex-grow">
                                <h3 class="font-medium text-gray-800">Quần Jean Nam Rách Gối Slimfit Co Giãn - Xanh Nhạt</h3>
                                <p class="text-sm text-gray-500">Size: 32 | Màu: Xanh nhạt</p>
                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        <span class="font-semibold text-lg" style="color: var(--primary-color);">450.000đ</span>
                                    </div>
                                    <input type="number" value="1" min="1" max="10" class="login-input w-20 text-center py-1 px-2">
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash-alt fa-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.2s;">
                        
                        <div class="mb-5">
                            <label class="block text-base font-medium text-gray-700 mb-3">Chọn hình thức nhận hàng</label>
                            <div class="flex rounded-lg border border-gray-300 p-1 bg-gray-100/60 w-full md:w-auto">
                                <button id="btn-giao-hang" type="button" class="flex-1 py-2 px-5 rounded-md text-sm font-semibold transition-all duration-200 tab-active">
                                    <i class="fas fa-truck mr-2"></i> Giao hàng tận nơi
                                </button>
                                <button id="btn-nhan-tai-nha-thuoc" type="button" class="flex-1 py-2 px-5 rounded-md text-sm font-semibold transition-all duration-200 tab-inactive">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                                    <input type="text" placeholder="Nguyễn Văn A" class="login-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                                    <input type="text" placeholder="09xxxxxxxx" class="login-input">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (nhận hóa đơn điện tử)</label>
                                    <input type="email" placeholder="email@example.com" class="login-input">
                                </div>
                            </div>
                        </div>

                        <div id="content-giao-hang" class="border-t border-gray-200 pt-5">
                            <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-map-marker-alt mr-2"></i> Địa chỉ giao hàng
                            </h2>

                            <div class="flex items-center gap-6 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="sap-nhap" id="radio-truoc-sap-nhap" class="form-radio h-5 w-5" checked>
                                    <span class="ml-2 text-gray-700">Địa chỉ hiện tại</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="sap-nhap" id="radio-sau-sap-nhap" class="form-radio h-5 w-5">
                                    <span class="ml-2 text-gray-700">Địa chỉ mới (sau sáp nhập)</span>
                                </label>
                            </div>

                            <div class="space-y-4" id="form-truoc-sap-nhap">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
                                        <select class="login-input">
                                            <option>Chọn Tỉnh/Thành phố</option>
                                            <option selected>TP. Hồ Chí Minh</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
                                        <select class="login-input">
                                            <option>Chọn Quận/Huyện</option>
                                            <option selected>Quận Bình Thạnh</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã</label>
                                    <select class="login-input">
                                        <option>Chọn Phường/Xã</option>
                                        <option selected>Phường 25</option>
                                        <option>Phường 1</option>
                                        <option>Phường 2</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ chi tiết</label>
                                    <input type="text" placeholder="Số nhà, tên đường" class="login-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú giao hàng</label>
                                    <textarea placeholder="Ví dụ: Giao giờ hành chính..." rows="3" class="login-input"></textarea>
                                </div>
                            </div>

                            <div class="space-y-4" id="form-sau-sap-nhap" style="display: none;">
                                <div class="bg-sky-50/70 border border-sky-200 rounded-lg p-3 text-center">
                                    <p class="text-sm" style="color: var(--primary-color);">
                                        Tra cứu địa chỉ mới nếu khu vực của bạn vừa thay đổi hành chính.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    </div>
                            </div>
                        </div>

                        <div id="content-nhan-tai-nha-thuoc" class="border-t border-gray-200 pt-5" style="display: none;">
                            <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-store mr-2"></i> Chọn cửa hàng
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
                                    <select class="login-input" disabled><option selected>TP. Hồ Chí Minh</option></select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
                                    <select class="login-input" disabled><option selected>Quận Bình Thạnh</option></select>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">Có 1 cửa hàng phù hợp:</p>
                            <div class="space-y-3">
                                <label class="flex items-start p-4 border-2 border-sky-600 bg-sky-50 rounded-lg cursor-pointer shadow-md">
                                    <input type="radio" name="pharmacy" class="form-radio h-5 w-5 mt-1" checked>
                                    <div class="ml-4 flex-grow">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-base" style="color: var(--primary-dark);">4MEN Store - Võ Oanh</span>
                                            <a href="#" class="text-sm font-medium" style="color: var(--primary-color); white-space: nowrap;">
                                                <i class="fas fa-directions"></i> Chỉ đường
                                            </a>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm mt-1">
                                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                                <i class="fas fa-check-circle text-xs"></i> Còn hàng
                                            </span>
                                            <span class="text-gray-700">Mở cửa: 08:30 - 22:00</span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-2">
                                            <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                                            Số 2 Võ Oanh, Phường 25, Quận Bình Thạnh, TP. HCM
                                        </p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-xl mr-3" style="color: var(--primary-color);"></i>
                                    <div>
                                        <label class="text-base font-medium text-gray-800">Thời gian đến lấy dự kiến</label>
                                        <p id="thoi-gian-nhan-hang-text" class="text-sm font-semibold" style="color: var(--primary-dark);">
                                            Từ 13:00 - 14:00 Hôm nay, 13/11/2025
                                        </p>
                                    </div>
                                </div>
                                <button id="btn-thay-doi-gio" type="button" class="font-semibold text-sm" style="color: var(--primary-color);">
                                    Thay đổi
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-200">
                            <label for="hoa-don" class="text-base font-medium text-gray-800">Yêu cầu xuất hóa đơn</label>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="hoa-don" id="hoa-don" class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="hoa-don" class="toggle-label block overflow-hidden h-7 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-xl login-card-animation" style="animation-delay: 0.3s;">
                        <h2 class="text-xl font-semibold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-credit-card mr-2"></i> Chọn phương thức thanh toán
                        </h2>
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all">
                                <input type="radio" name="payment-method" class="form-radio h-5 w-5" checked>
                                <img src="https://placehold.co/40x40/e0f2fe/0284c7?text=COD" class="mx-3 rounded">
                                <span class="font-medium">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all">
                                <input type="radio" name="payment-method" class="form-radio h-5 w-5">
                                <img src="https://placehold.co/40x40/e0f2fe/0284c7?text=QR" class="mx-3 rounded">
                                <span class="font-medium">Chuyển khoản ngân hàng (QR Code)</span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50 transition-all">
                                <input type="radio" name="payment-method" class="form-radio h-5 w-5">
                                <img src="https://placehold.co/40x40/e0f2fe/0284c7?text=MOMO" class="mx-3 rounded">
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
                        
                        <div class="flex items-center gap-2 mb-4">
                            <input type="text" placeholder="Mã giảm giá" class="login-input py-2">
                            <button class="login-button py-2 px-4 text-sm whitespace-nowrap !shadow-none !animation-none">
                                Áp dụng
                            </button>
                        </div>

                        <div class="space-y-2 text-base">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium">800.000đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span id="phi-van-chuyen" class="font-medium text-green-600">Miễn phí</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-300">
                            <span class="text-xl font-bold">Tổng cộng:</span>
                            <span id="thanh-tien" class="text-2xl font-bold" style="color: var(--primary-color);">800.000đ</span>
                        </div>

                        <button class="login-button w-full flex justify-center py-3 px-4 mt-6 text-lg">
                            ĐẶT HÀNG
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-3">
                            Cam kết bảo mật thông tin khách hàng.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="thoi-gian-modal-backdrop" class="modal-backdrop"></div>
    <div id="thoi-gian-modal-panel" class="modal-panel">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" style="color: var(--primary-dark);">Chọn thời gian nhận hàng</h3>
                <button id="btn-close-modal" type="button" class="text-gray-400 hover:text-gray-700 transition-colors"><i class="fas fa-times fa-lg"></i></button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn ngày nhận:</label>
                    <div id="date-slot-container" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button type="button" class="slot-button selected" data-date-text="Hôm nay, 13/11/2025">Hôm nay</button>
                        <button type="button" class="slot-button" data-date-text="Ngày mai">Ngày mai</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn giờ nhận:</label>
                    <div id="time-slot-container" class="grid grid-cols-3 md:grid-cols-4 gap-3">
                        <button type="button" class="slot-button selected">13:00 - 14:00</button>
                        <button type="button" class="slot-button">14:00 - 15:00</button>
                        </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <button id="btn-xac-nhan-gio" type="button" class="login-button w-full py-3 !animation-none !shadow-md">Xác nhận</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // (SCRIPT CANVAS GIỮ NGUYÊN)
            const canvas = document.getElementById('pills-canvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                let pills = [];
                const numberOfPills = 100;
                const colors = ['#ffffff', '#bae6fd', '#f0f9ff', '#0284c7'];
                const mouse = { x: null, y: null, radius: 120 };
                window.addEventListener('mousemove', (e) => { mouse.x = e.x; mouse.y = e.y; });
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

            // (SCRIPT LOGIC TAB & MODAL - GIỮ NGUYÊN NHƯNG CẬP NHẬT ID NẾU CẦN)
            const btnGiaoHang = document.getElementById('btn-giao-hang');
            const btnNhanTaiNhaThuoc = document.getElementById('btn-nhan-tai-nha-thuoc');
            const contentGiaoHang = document.getElementById('content-giao-hang');
            const contentNhanTaiNhaThuoc = document.getElementById('content-nhan-tai-nha-thuoc');
            const modalBackdrop = document.getElementById('thoi-gian-modal-backdrop');
            const modalPanel = document.getElementById('thoi-gian-modal-panel');
            const btnThayDoiGio = document.getElementById('btn-thay-doi-gio');
            const btnCloseModal = document.getElementById('btn-close-modal');

            // Tab logic
            if(btnGiaoHang && btnNhanTaiNhaThuoc){
                btnGiaoHang.addEventListener('click', () => {
                    contentGiaoHang.style.display = 'block';
                    contentNhanTaiNhaThuoc.style.display = 'none';
                    btnGiaoHang.classList.replace('tab-inactive', 'tab-active');
                    btnNhanTaiNhaThuoc.classList.replace('tab-active', 'tab-inactive');
                });
                btnNhanTaiNhaThuoc.addEventListener('click', () => {
                    contentGiaoHang.style.display = 'none';
                    contentNhanTaiNhaThuoc.style.display = 'block';
                    btnNhanTaiNhaThuoc.classList.replace('tab-inactive', 'tab-active');
                    btnGiaoHang.classList.replace('tab-active', 'tab-inactive');
                });
            }

            // Modal logic
            if(btnThayDoiGio) btnThayDoiGio.addEventListener('click', () => { modalBackdrop.classList.add('active'); modalPanel.classList.add('active'); });
            if(btnCloseModal) btnCloseModal.addEventListener('click', () => { modalBackdrop.classList.remove('active'); modalPanel.classList.remove('active'); });
            if(modalBackdrop) modalBackdrop.addEventListener('click', () => { modalBackdrop.classList.remove('active'); modalPanel.classList.remove('active'); });
        });
    </script>
</body>
</html>