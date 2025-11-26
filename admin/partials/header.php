<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

// Đặt base URL (Thư mục gốc của dự án)
$base_url = '/SHOP_BAN_QUAN_AO_4P';

// Lấy tên file hiện tại để active menu
$current_file = basename($_SERVER['PHP_SELF']);

// --- 1. KIỂM TRA QUYỀN ADMIN ---
// Logic này khớp với file login.php chúng ta đã làm
$user_role = $_SESSION['user_role'] ?? '';

if ($user_role !== 'QuanTriVien') {
    // Nếu không phải admin, đá về trang login
    header('Location: ' . $base_url . '/login.php');
    exit;
}

// Hàm active menu
function admin_active_if($file) {
    global $current_file;
    // So sánh tên file hiện tại với link menu
    if ($current_file === $file) {
        // Class cho trạng thái active (Màu cam đậm, chữ trắng)
        return ' bg-[#c72002] text-white shadow-md';
    }
    // Class mặc định (Chữ xám, hover nền cam nhạt)
    return ' text-gray-600 hover:bg-orange-50 hover:text-[#c72002]';
}

if (!isset($page_title)) {
    $page_title = 'Quản Trị - 4MEN Shop';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Tông màu 4MEN Shop */
            --primary-color: #c72002; /* Đỏ cam */
            --primary-light: #ffeae0; /* Cam rất nhạt */
            --primary-dark: #9a1902;  /* Đỏ đậm */
            --bg-surface: #ffffff;
            --text-primary: #0f172a;
        }
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden; /* Ẩn thanh cuộn ngoài */
            background-color: #f8fafc;
            color: var(--text-primary);
        }

        /* Nền Canvas hiệu ứng */
        #pills-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; 
            /* Gradient màu cam nhạt cho hợp với shop */
            background: linear-gradient(to bottom, #fff1eb, #ffd1b8); 
        }

        .sidebar-item {
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        /* Hiệu ứng thẻ Dashboard */
        .dashboard-card { transition: all 0.3s ease-in-out; }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(199, 32, 2, 0.15);
        }
    </style>
    
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = { darkMode: 'class' };
    </script>
</head>
<body class="bg-slate-50 text-slate-800">

<canvas id="pills-canvas"></canvas>
<script>
    // Script tạo hiệu ứng hạt bay (Đã chỉnh màu sang tông Cam/Trắng)
    document.addEventListener('DOMContentLoaded', (event) => {
        const canvas = document.getElementById('pills-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            let pills = [];
            const numberOfPills = 60;
            // Màu sắc hạt: Trắng, Cam nhạt, Cam đậm
            const colors = ['#ffffff', '#ffdac1', '#ff9e6e', '#c72002'];
            
            class Pill {
                constructor() { this.reset(); }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 5 + 3;
                    this.speedY = Math.random() * 0.5 + 0.1;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.opacity = Math.random() * 0.5 + 0.1;
                }
                update() { this.y -= this.speedY; if (this.y < -this.size) this.reset(); }
                draw() {
                    ctx.save(); ctx.globalAlpha = this.opacity; ctx.fillStyle = this.color;
                    ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); ctx.restore();
                }
            }
            function init() { pills = Array.from({ length: numberOfPills }, () => new Pill()); }
            function animate() { 
                ctx.clearRect(0, 0, canvas.width, canvas.height); 
                pills.forEach(p => { p.update(); p.draw(); }); 
                requestAnimationFrame(animate); 
            }
            window.addEventListener('resize', () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; init(); });
            init(); animate();
        }
    });
</script>

<div class="flex h-screen">
    
    <aside class="w-64 bg-white/90 backdrop-blur-lg shadow-xl flex flex-col p-4 border-r border-gray-200 z-10">
        
        <div class="flex items-center gap-3 px-2 py-5 border-b border-gray-100 mb-4">
            <a href="<?= $base_url ?>/admin/index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-shirt text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#c72002]">4MEN ADMIN</h1>
                    <p class="text-xs text-gray-500">Quản lý cửa hàng</p>
                </div>
            </a>
        </div>
        
        <nav class="flex-1 overflow-y-auto custom-scrollbar">
            <ul class="space-y-2" id="nav-menu">
                
                <li>
                    <a href="<?= $base_url ?>/admin/index.php" 
                       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= admin_active_if('index.php'); ?>">
                        <i class="fa-solid fa-chart-pie w-5 text-center"></i> 
                        <span>Tổng quan</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?>/admin/don-hang.php" 
                       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= admin_active_if('don-hang.php'); ?>">
                        <i class="fa-solid fa-box-open w-5 text-center"></i> 
                        <span>Đơn hàng</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?>/admin/san-pham.php" 
                       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= admin_active_if('san-pham.php'); ?>">
                        <i class="fa-solid fa-shirt w-5 text-center"></i> 
                        <span>Sản phẩm</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?>/admin/danh-muc.php" 
                       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= admin_active_if('danh-muc.php'); ?>">
                        <i class="fa-solid fa-tags w-5 text-center"></i> 
                        <span>Danh mục</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?>/admin/khach-hang.php" 
                       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= admin_active_if('khach-hang.php'); ?>">
                        <i class="fa-solid fa-users w-5 text-center"></i> 
                        <span>Khách hàng</span>
                    </a>
                </li>
                
            </ul>
        </nav>
        
        <div class="mt-auto pt-4 border-t border-gray-100">
            <a href="<?= $base_url ?>/logout.php" 
               onclick="return confirm('Bạn có chắc muốn đăng xuất?');"
               class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition-colors font-medium">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </div>

    </aside>
