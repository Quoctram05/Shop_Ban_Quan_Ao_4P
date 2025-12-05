<?php
// admin/dashboard.php

// --------------------------------------------------------------------------
// 1. KẾT NỐI DATABASE (Cấu hình cho shop_thoi_trang_hoc)
// --------------------------------------------------------------------------
// 1. KHỞI ĐỘNG SESSION & KẾT NỐI
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../public/connect.php';

// 2. CHECK QUYỀN ADMIN
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'QuanTriVien') {
    header('Location: /SHOP_BAN_QUAN_AO_4P/login.php');
    exit;
}

// Cấu hình kết nối dự phòng (SỬ DỤNG THÔNG TIN CỦA BẠN)
if (!isset($pdo) || !($pdo instanceof PDO)) {
    // --- SỬA THÔNG TIN TẠI ĐÂY ---
    $db_host = 'localhost';        
    $db_user = 'root';             
    $db_pass = '';                 
    $db_name = 'shop_thoi_trang_hoc'; // Tên CSDL bạn cung cấp

    try {
        $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
        $pdo = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } catch (PDOException $e) {
        die("<div style='color:red; padding:20px; background:#ffe6e6; border:1px solid red;'>
            <strong>LỖI KẾT NỐI:</strong> " . $e->getMessage() . "</div>");
    }
}
// --------------------------------------------------------------------------

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Hàm định dạng tiền tệ
function vnd($n){ return number_format($n, 0, ',', '.') . 'đ'; }

// 2. TÍNH TOÁN KPI
$kpi = [
    'revenue_today' => 0,
    'orders_new'    => 0,
    'low_stock'     => 0,
    'total_products'=> 0
];

try {
    // KPI 1: Doanh thu hôm nay (Chỉ tính đơn đã thanh toán/hoàn thành/đang giao)
    // Dựa trên bảng: don_hang (tong_tien, ngay_dat, trang_thai)
    $sqlRev = "SELECT SUM(tong_tien) 
               FROM don_hang 
               WHERE DATE(ngay_dat) = CURDATE() 
               AND trang_thai IN ('DaXacNhan', 'DangGiao', 'HoanThanh')";
    $kpi['revenue_today'] = (float)$pdo->query($sqlRev)->fetchColumn();

    // KPI 2: Đơn hàng mới (Chờ xử lý)
    $sqlNew = "SELECT COUNT(*) FROM don_hang WHERE trang_thai = 'ChoXuLy'";
    $kpi['orders_new'] = (int)$pdo->query($sqlNew)->fetchColumn();

    // KPI 3: Sản phẩm sắp hết hàng (Lấy từ bảng bien_the_san_pham)
    $sqlLow = "SELECT COUNT(*) FROM bien_the_san_pham WHERE so_luong_ton <= 10";
    $kpi['low_stock'] = (int)$pdo->query($sqlLow)->fetchColumn();

    // KPI 4: Tổng sản phẩm đang bán
    $sqlProd = "SELECT COUNT(*) FROM san_pham WHERE trang_thai = 'DangBan'";
    $kpi['total_products'] = (int)$pdo->query($sqlProd)->fetchColumn();


    // 3. BIỂU ĐỒ DOANH THU 7 NGÀY QUA
    // Group by ngày đặt
    $sqlChart = "SELECT DATE(ngay_dat) as d, SUM(tong_tien) as total
                 FROM don_hang
                 WHERE ngay_dat >= CURDATE() - INTERVAL 6 DAY
                 AND trang_thai != 'DaHuy'
                 GROUP BY d";
    $chartRaw = $pdo->query($sqlChart)->fetchAll(PDO::FETCH_KEY_PAIR); // [ '2025-12-01' => 500000, ... ]

    $chartData = [];
    $chartLabels = [];
    // Lấp đầy các ngày không có đơn bằng số 0
    for ($i=6; $i>=0; $i--) {
        $date = date('Y-m-d', strtotime("-$i day"));
        $chartLabels[] = date('d/m', strtotime($date));
        $chartData[]   = (float)($chartRaw[$date] ?? 0);
    }


    // 4. TOP SẢN PHẨM BÁN CHẠY (Top 5)
    // Join 3 bảng: chi_tiet_don_hang -> san_pham -> bien_the_san_pham (để lấy ảnh)
    $sqlTop = "SELECT sp.ten_san_pham, 
                      SUM(ct.so_luong) as da_ban,
                      (SELECT hinh_anh_dai_dien FROM bien_the_san_pham WHERE san_pham_id = sp.id LIMIT 1) as hinh
               FROM chi_tiet_don_hang ct
               JOIN san_pham sp ON ct.san_pham_id = sp.id
               GROUP BY sp.id
               ORDER BY da_ban DESC
               LIMIT 5";
    $topSell = $pdo->query($sqlTop)->fetchAll();


    // 5. DANH SÁCH SẮP HẾT HÀNG (Thay cho Hết hạn sử dụng)
    // Lấy chi tiết biến thể (Màu, Size) sắp hết
    $sqlStock = "SELECT sp.ten_san_pham, bt.mau_sac, bt.kich_co, bt.so_luong_ton, bt.sku
                 FROM bien_the_san_pham bt
                 JOIN san_pham sp ON bt.san_pham_id = sp.id
                 WHERE bt.so_luong_ton <= 10
                 ORDER BY bt.so_luong_ton ASC
                 LIMIT 10";
    $lowStockItems = $pdo->query($sqlStock)->fetchAll();

} catch (PDOException $e) {
    die("Lỗi truy vấn dữ liệu: " . $e->getMessage());
}

$active = 'dashboard';
// Header include
if (file_exists(__DIR__ . '/partials/header.php')) {
    include __DIR__ . '/partials/header.php';
} else {
    // Fallback header đơn giản nếu không có file
    echo '<div style="background:#f1f5f9; min-height:100vh; display:flex;">'; 
}
?>

<main class="flex-1 p-6 overflow-y-auto bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tổng quan Shop</h1>
                <p class="text-slate-500 text-sm mt-1">Hôm nay: <?= date('d/m/Y') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Doanh thu hôm nay</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1"><?= vnd($kpi['revenue_today']) ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Đơn chờ xử lý</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?= $kpi['orders_new'] ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-shopping-bag text-xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Biến thể sắp hết</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1"><?= $kpi['low_stock'] ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Sản phẩm Active</p>
                    <p class="text-2xl font-bold text-slate-700 mt-1"><?= $kpi['total_products'] ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 text-lg">Doanh thu 7 ngày qua</h3>
                <div class="h-[320px] w-full">
                    <canvas id="revChart"></canvas>
                </div>
            </div>

            <div class="lg:col-span-1 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Kho báo động</h3>
                    <span class="text-xs font-bold bg-red-100 text-red-600 px-2 py-1 rounded">SL ≤ 10</span>
                </div>
                
                <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1 custom-scrollbar">
                    <?php if(empty($lowStockItems)): ?>
                        <div class="text-slate-400 text-sm text-center py-4">Kho hàng dồi dào!</div>
                    <?php else: foreach($lowStockItems as $item): 
                        $sl = (int)$item['so_luong_ton'];
                        // Nếu hết hàng thì màu đỏ, sắp hết thì màu cam
                        $colorClass = ($sl == 0) ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200';
                        $textClass  = ($sl == 0) ? 'text-red-600' : 'text-amber-600';
                    ?>
                        <div class="p-3 rounded-xl border <?= $colorClass ?>">
                            <div class="font-medium text-slate-800 text-sm truncate" title="<?= htmlspecialchars($item['ten_san_pham']) ?>">
                                <?= htmlspecialchars($item['ten_san_pham']) ?>
                            </div>
                            <div class="flex justify-between mt-1 text-xs text-slate-500">
                                <span>Size: <b><?= htmlspecialchars($item['kich_co']) ?></b> - Màu: <?= htmlspecialchars($item['mau_sac']) ?></span>
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-[10px] bg-white border px-1 rounded text-slate-400">SKU: <?= htmlspecialchars($item['sku']) ?></span>
                                <span class="text-xs font-bold <?= $textClass ?>">
                                    Còn: <?= $sl ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            
            <div class="lg:col-span-3 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 text-lg">Sản phẩm bán chạy nhất</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <?php if(empty($topSell)): ?>
                        <div class="col-span-full text-center text-slate-400 py-4">Chưa có dữ liệu bán hàng.</div>
                    <?php else: foreach($topSell as $idx => $t): ?>
                        <div class="flex flex-col p-3 rounded-xl border border-slate-100 hover:shadow-md transition bg-white relative">
                            <div class="absolute top-2 left-2 w-6 h-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold shadow-md">
                                #<?= $idx + 1 ?>
                            </div>

                            <div class="w-full aspect-square bg-slate-100 rounded-lg overflow-hidden mb-3">
                                <?php 
                                    // Xử lý đường dẫn ảnh (trong DB đang lưu dạng ../assets/img/...)
                                    // Nếu file dashboard nằm trong admin/, thì đường dẫn này là chính xác
                                    $imgSrc = !empty($t['hinh']) ? htmlspecialchars($t['hinh']) : 'https://via.placeholder.com/150?text=No+Image';
                                ?>
                                <img src="<?= $imgSrc ?>" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                            </div>
                            
                            <div class="font-medium text-slate-700 text-sm line-clamp-2 min-h-[40px] mb-1" title="<?= htmlspecialchars($t['ten_san_pham']) ?>">
                                <?= htmlspecialchars($t['ten_san_pham']) ?>
                            </div>
                            <div class="text-xs text-indigo-600 font-semibold">
                                Đã bán: <?= number_format($t['da_ban']) ?>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = <?= json_encode($chartLabels) ?>;
    const data   = <?= json_encode($chartData) ?>;

    const ctx = document.getElementById('revChart').getContext('2d');
    
    // Tạo màu gradient đẹp
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // Màu xanh emerald nhạt
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: data,
                borderColor: '#10b981',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10b981',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.35 // Độ cong của đường
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#f1f5f9' },
                    ticks: {
                        callback: function(val) {
                            if(val >= 1000000) return (val/1000000) + ' Tr';
                            if(val >= 1000) return (val/1000) + ' k';
                            return val;
                        },
                        font: { size: 11, family: "'Inter', sans-serif" }
                    }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
</script>

<?php if (!file_exists(__DIR__ . '/partials/header.php')) echo '</div>'; ?>