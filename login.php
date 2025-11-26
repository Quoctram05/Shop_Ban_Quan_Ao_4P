<?php
declare(strict_types=1);

// Cấu hình Cookie bảo mật
$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => $secureCookie,
    'path' => '/',
]);
session_start();

// === HÀM TIỆN ÍCH (GIỮ NGUYÊN) ===
function client_ip(): string {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $parts = explode(',', (string)$_SERVER[$key]);
            $ip = trim($parts[0]);
            if ($ip !== '') return $ip;
        }
    }
    return '0.0.0.0';
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return (string)$_SESSION['csrf'];
}

// === KẾT NỐI CSDL (SỬA LẠI ĐƯỜNG DẪN CHO ĐÚNG) ===
// Giả sử file connect.php nằm ở public/connect.php
require_once __DIR__ . '/public/connect.php';
// Biến kết nối là $conn (PDO) đã có sẵn từ file connect.php

$errorMessage = '';
$successMessage = '';
$csrfToken = csrf_token();

if (isset($_SESSION['register_success'])) {
    $successMessage = (string)$_SESSION['register_success'];
    unset($_SESSION['register_success']);
}

// === XỬ LÝ ĐĂNG NHẬP ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra CSRF
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        $errorMessage = 'Phiên đăng nhập không hợp lệ. Vui lòng tải lại trang.';
    } else {
        $email = trim((string)($_POST['email'] ?? '')); // Sửa: Đăng nhập bằng Email
        $password = (string)($_POST['password'] ?? '');

        // Tìm người dùng trong bảng nguoi_dung
        try {
            $stmt = $conn->prepare('SELECT id, ho_ten, mat_khau, email, vai_tro FROM nguoi_dung WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        } catch (Exception $e) {
            $user = null;
        }

        if (!$user) {
            $errorMessage = 'Email hoặc mật khẩu không đúng.';
        } else {
            // Kiểm tra mật khẩu (Giả sử mật khẩu đã hash bằng password_hash)
            // Nếu trong DB bạn đang lưu mật khẩu thường (chưa hash) để test thì dùng: if ($password === $user['mat_khau'])
            if (password_verify($password, $user['mat_khau'])) {
                
                // Đăng nhập thành công -> Lưu Session
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['ho_ten'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['vai_tro']; // 'KhachHang' hoặc 'QuanTriVien'

                // Chuyển hướng dựa trên vai trò
                if ($user['vai_tro'] === 'QuanTriVien') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $errorMessage = 'Email hoặc mật khẩu không đúng.';
            }
        }
    }
}

$page_title = 'Đăng Nhập - 4MEN Shop';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* === TÔNG MÀU 4MEN (CAM ĐẤT) === */
        :root {
            --primary-color: #b35d2a;
            --primary-light: #fff7ed;
            --primary-dark: #9a3412;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            overflow: hidden; 
        }

        /* Hiệu ứng nền Canvas (Màu cam nhẹ) */
        #pills-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; 
            background: linear-gradient(to bottom, #fff7ed, #ffedd5);
        }

        /* Animation form bay lên */
        @keyframes fadeInUpAndGrow {
            from { opacity: 0; transform: translateY(30px) scale(0.95); filter: blur(5px); }
            to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        .login-card-animation {
            animation: fadeInUpAndGrow 0.9s ease-out forwards;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .login-card-animation:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(179, 93, 42, 0.15);
        }
        
        /* Nút bấm */
        .login-button {
            transition: all 0.3s;
            background-color: var(--primary-color);
            color: white; font-weight: 600; border-radius: 0.5rem;
            box-shadow: 0 4px 14px 0 rgba(179, 93, 42, 0.25);
        }
        .login-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px 0 rgba(179, 93, 42, 0.35);
        }
        
        /* Input */
        .login-input {
            transition: all 0.2s;
            border: 1px solid #D1D5DB; width: 100%; border-radius: 0.5rem;
            padding: 0.75rem 1rem; padding-left: 2.5rem; /* Chừa chỗ cho icon */
            background-color: rgba(255, 255, 255, 0.8);
        }
        .login-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(179, 93, 42, 0.2);
            outline: none; background-color: white;
        }
    </style>
</head>
<body class="bg-orange-50 text-gray-800">

<canvas id="pills-canvas"></canvas>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('pills-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            let items = [];
            // Màu sắc hạt: Cam, Trắng, Xám nhạt
            const colors = ['#ffffff', '#fed7aa', '#fdba74', '#b35d2a']; 
            
            class Item {
                constructor() { this.reset(); }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 5 + 2; // Hạt nhỏ hơn
                    this.speedY = Math.random() * 0.5 + 0.1;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.opacity = Math.random() * 0.5 + 0.1;
                }
                update() { this.y -= this.speedY; if (this.y < 0) this.reset(); }
                draw() {
                    ctx.save(); ctx.globalAlpha = this.opacity; ctx.fillStyle = this.color;
                    ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI*2); ctx.fill(); ctx.restore();
                }
            }
            function init() { items = Array.from({ length: 60 }, () => new Item()); }
            function animate() { 
                ctx.clearRect(0, 0, canvas.width, canvas.height); 
                items.forEach(i => { i.update(); i.draw(); }); 
                requestAnimationFrame(animate); 
            }
            init(); animate();
        }
    });
</script>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/90 backdrop-blur-lg p-8 md:p-10 rounded-2xl shadow-xl w-full max-w-md login-card-animation border border-orange-100">
        
        <div class="flex flex-col items-center mb-6">
            <div class="p-3 rounded-full bg-orange-100 text-[#b35d2a]">
                <i class="fas fa-shirt text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold mt-4 text-[#b35d2a]">4MEN SHOP</h1>
            <p class="text-gray-500 mt-1">Đẳng cấp thời trang phái mạnh</p>
        </div>

        <?php if ($errorMessage !== ''): ?>
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <i class="fas fa-circle-exclamation mr-1"></i> <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php elseif ($successMessage !== ''): ?>
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">
                <i class="fas fa-check-circle mr-1"></i> <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="#" method="POST" class="space-y-5">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrfToken); ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" placeholder="example@gmail.com" required class="login-input">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" required class="login-input">
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#b35d2a] focus:ring-[#b35d2a]">
                    <span class="text-gray-600">Ghi nhớ đăng nhập</span>
                </label>
                <a href="#" class="text-[#b35d2a] hover:underline font-medium">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="login-button w-full py-3 text-lg">
                Đăng Nhập
            </button>

            <div class="text-center text-sm text-gray-600 mt-4">
                Chưa có tài khoản? 
                <a href="register.php" class="text-[#b35d2a] font-bold hover:underline">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>