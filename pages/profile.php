<?php
session_start();
require_once('../public/connect.php');
include('../header.php');
include('../navbar.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 2. Lấy lịch sử đơn hàng
$stmt_orders = $conn->prepare("SELECT * FROM don_hang WHERE nguoi_dung_id = ? ORDER BY id DESC");
$stmt_orders->execute([$user_id]);
$orders = $stmt_orders->fetchAll();

// 3. Tính toán thống kê nhanh
$total_orders = count($orders);
$total_spent = 0;
foreach ($orders as $o) {
    if ($o['trang_thai'] != 'Huy') {
        $total_spent += $o['tong_tien'];
    }
}
?>

<!-- CSS HIỆU ỨNG RIÊNG -->
<style>
    :root {
        --primary: #9a3412;
        --primary-light: #fff7ed;
        --primary-hover: #7c2d10;
        --gray-text: #4b5563;
    }

    /* 1. Animation Global */
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-entry {
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0; /* Mặc định ẩn để chờ animation */
    }
    
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }

    /* 2. Card Effects */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    
    .order-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    
    .order-card:hover {
        transform: translateY(-4px) scale(1.005);
        box-shadow: 0 20px 25px -5px rgba(154, 52, 18, 0.1), 0 10px 10px -5px rgba(154, 52, 18, 0.04);
        border-color: rgba(154, 52, 18, 0.2);
    }

    /* 3. Filter Tabs */
    .filter-btn {
        position: relative;
        transition: all 0.3s ease;
        background: transparent;
        color: #6b7280;
    }
    
    .filter-btn.active {
        color: var(--primary);
        font-weight: 700;
        background-color: var(--primary-light);
    }
    
    .filter-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 40%;
        height: 3px;
        background-color: var(--primary);
        border-radius: 99px;
    }

    /* 4. Utilities */
    .copy-icon {
        opacity: 0;
        transition: opacity 0.2s;
        cursor: pointer;
    }
    .group:hover .copy-icon {
        opacity: 1;
    }

    /* 5. Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
</style>

<div class="bg-[#fcfaf8] min-h-screen py-10 font-sans text-slate-800">
    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <div class="container mx-auto px-4 max-w-6xl">
        
        <!-- HEADER + STATS -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-10 animate-entry">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Xin chào, <span class="text-[#9a3412]"><?php echo htmlspecialchars($user['ho_ten']); ?></span>
                </h1>
                <p class="text-gray-500 mt-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Thành viên thân thiết
                </p>
            </div>
            <div class="mt-6 md:mt-0 flex gap-4">
                <div class="glass-card px-6 py-4 rounded-2xl flex items-center gap-4 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-[#9a3412] text-xl">
                        <i class="fa-solid fa-shopping-bag"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Tổng đơn</p>
                        <p class="font-bold text-2xl text-gray-800 counter" data-target="<?php echo $total_orders; ?>">0</p>
                    </div>
                </div>
                <div class="glass-card px-6 py-4 rounded-2xl flex items-center gap-4 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-[#9a3412] text-xl">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Đã chi tiêu</p>
                        <p class="font-bold text-2xl text-[#9a3412]"><?php echo number_format($total_spent, 0, ',', '.'); ?>đ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT SIDEBAR -->
            <div class="lg:col-span-3 animate-entry delay-100">
                <div class="glass-card rounded-2xl overflow-hidden sticky top-24">
                    <div class="h-28 bg-gradient-to-br from-[#9a3412] to-[#ea580c] relative">
                        <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2">
                            <div class="p-1.5 bg-white rounded-full shadow-lg">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border-2 border-white">
                                    <i class="fa-solid fa-user text-3xl text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-12 pb-6 px-4 text-center">
                        <h2 class="font-bold text-lg text-gray-900"><?php echo htmlspecialchars($user['ho_ten']); ?></h2>
                        <p class="text-sm text-gray-500 mb-6 truncate"><?php echo htmlspecialchars($user['email']); ?></p>
                        
                        <div class="space-y-1 text-left">
                            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-orange-50 text-[#9a3412] font-semibold transition-all">
                                <i class="fa-solid fa-box-open w-5 text-center"></i> Đơn mua
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-user-pen w-5 text-center"></i> Hồ sơ
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-map-location-dot w-5 text-center"></i> Địa chỉ
                            </button>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <a href="../logout.php" class="flex items-center justify-center gap-2 w-full py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-xl transition font-semibold text-sm">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="lg:col-span-9 space-y-6 animate-entry delay-200">
                
                <!-- Filter & Search Bar -->
                <div class="glass-card rounded-2xl p-2 flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Tabs -->
                    <div class="flex p-1 bg-gray-100/50 rounded-xl w-full md:w-auto overflow-x-auto">
                        <button onclick="filterOrders('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm whitespace-nowrap" id="btn-all">Tất cả</button>
                        <button onclick="filterOrders('ChoXuLy')" class="filter-btn px-4 py-2 rounded-lg text-sm whitespace-nowrap" id="btn-ChoXuLy">Chờ xử lý</button>
                        <button onclick="filterOrders('DangGiao')" class="filter-btn px-4 py-2 rounded-lg text-sm whitespace-nowrap" id="btn-DangGiao">Đang giao</button>
                        <button onclick="filterOrders('HoanThanh')" class="filter-btn px-4 py-2 rounded-lg text-sm whitespace-nowrap" id="btn-HoanThanh">Hoàn thành</button>
                        <button onclick="filterOrders('Huy')" class="filter-btn px-4 py-2 rounded-lg text-sm whitespace-nowrap" id="btn-Huy">Đã hủy</button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <input type="text" id="orderSearch" onkeyup="searchOrder()" placeholder="Tìm mã đơn hàng..." 
                            class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:border-[#9a3412] focus:ring-1 focus:ring-[#9a3412] transition-all bg-white text-sm">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Order List -->
                <div id="orderList" class="space-y-5">
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): 
                            // Get items
                            $stmt_items = $conn->prepare("
                                SELECT c.*, b.hinh_anh_dai_dien 
                                FROM chi_tiet_don_hang c 
                                LEFT JOIN bien_the_san_pham b ON c.bien_the_id = b.id 
                                WHERE c.don_hang_id = ?
                            ");
                            $stmt_items->execute([$order['id']]);
                            $items = $stmt_items->fetchAll();
                            
                            // Status Logic
                            $stt_bg = 'bg-gray-100 text-gray-600 border-gray-200';
                            $stt_text = 'Đang xử lý';
                            $stt_icon = 'fa-spinner fa-spin';
                            
                            if ($order['trang_thai'] == 'ChoXuLy') {
                                $stt_bg = 'bg-orange-50 text-orange-700 border-orange-100';
                                $stt_text = 'Chờ xử lý';
                                $stt_icon = 'fa-clock';
                            } elseif ($order['trang_thai'] == 'DangGiao') {
                                $stt_bg = 'bg-blue-50 text-blue-700 border-blue-100';
                                $stt_text = 'Đang giao hàng';
                                $stt_icon = 'fa-truck-fast';
                            } elseif ($order['trang_thai'] == 'HoanThanh') {
                                $stt_bg = 'bg-green-50 text-green-700 border-green-100';
                                $stt_text = 'Hoàn thành';
                                $stt_icon = 'fa-circle-check';
                            } elseif ($order['trang_thai'] == 'Huy') {
                                $stt_bg = 'bg-red-50 text-red-700 border-red-100';
                                $stt_text = 'Đã hủy';
                                $stt_icon = 'fa-circle-xmark';
                            }
                        ?>
                        
                        <!-- Order Item Block -->
                        <div class="order-card bg-white rounded-2xl p-6 shadow-sm group relative" data-status="<?php echo $order['trang_thai']; ?>" data-id="<?php echo $order['id']; ?>">
                            
                            <!-- Header -->
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-5 border-b border-gray-100 pb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-gray-800 text-white uppercase tracking-wider">Đơn hàng</span>
                                        <div class="flex items-center gap-2 cursor-pointer group/id" onclick="copyToClipboard('#<?php echo $order['id']; ?>')">
                                            <span class="text-lg font-bold text-gray-800">#<?php echo $order['id']; ?></span>
                                            <i class="fa-regular fa-copy text-gray-400 text-xs copy-icon group-hover/id:text-[#9a3412]"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fa-regular fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?>
                                    </p>
                                </div>
                                <div class="px-3 py-1.5 rounded-full border <?php echo $stt_bg; ?> text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid <?php echo $stt_icon; ?>"></i> <?php echo $stt_text; ?>
                                </div>
                            </div>

                            <!-- Products -->
                            <div class="space-y-4 mb-5">
                                <?php foreach ($items as $item): ?>
                                    <div class="flex items-start gap-4 p-2 hover:bg-gray-50 rounded-xl transition-colors">
                                        <div class="w-16 h-16 rounded-lg border border-gray-100 overflow-hidden flex-shrink-0 bg-white shadow-sm">
                                            <?php if(!empty($item['hinh_anh_dai_dien'])): ?>
                                                <img src="<?php echo $item['hinh_anh_dai_dien']; ?>" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500" alt="Product">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50"><i class="fa-solid fa-image"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-800 text-sm truncate"><?php echo htmlspecialchars($item['ten_san_pham']); ?></h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Phân loại: <span class="text-gray-700">Mặc định</span></p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600">x<?php echo $item['so_luong']; ?></span>
                                                <span class="text-sm font-bold text-gray-700"><?php echo number_format($item['don_gia'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Footer -->
                            <div class="flex flex-col sm:flex-row justify-between items-center pt-4 border-t border-gray-100 gap-4">
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fa-regular fa-credit-card"></i> Thanh toán: <span class="font-semibold text-gray-700 uppercase"><?php echo $order['phuong_thuc_thanh_toan']; ?></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500">Thành tiền:</span>
                                    <span class="text-xl font-bold text-[#9a3412]"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Chưa có đơn hàng nào.</p>
                            <a href="../index.php" class="inline-block mt-4 text-[#9a3412] font-semibold hover:underline">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../footer.php'); ?>

<!-- JAVASCRIPT HIỆU ỨNG -->
<script>
    // 1. Filter Orders Logic
    function filterOrders(status) {
        // Active Button styling
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('btn-' + status).classList.add('active');

        const orders = document.querySelectorAll('.order-card');
        
        orders.forEach(order => {
            if (status === 'all' || order.getAttribute('data-status') === status) {
                order.style.display = 'block';
                // Reset animation
                order.style.animation = 'none';
                order.offsetHeight; /* trigger reflow */
                order.style.animation = 'slideUpFade 0.4s ease forwards';
            } else {
                order.style.display = 'none';
            }
        });
    }

    // 2. Search Logic
    function searchOrder() {
        const input = document.getElementById('orderSearch').value.toLowerCase();
        const orders = document.querySelectorAll('.order-card');

        orders.forEach(order => {
            const id = order.getAttribute('data-id').toLowerCase();
            // Check if ID contains input AND if it is currently visible (based on filter tabs)
            // Simplified: Just search ID for now
            if (id.includes(input)) {
                order.style.display = 'block';
            } else {
                order.style.display = 'none';
            }
        });
    }

    // 3. Copy to Clipboard & Toast
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Đã sao chép mã: ${text}`, 'success');
        }, () => {
            showToast('Lỗi sao chép', 'error');
        });
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        // Style cho toast
        const bgColor = type === 'success' ? 'bg-gray-800' : 'bg-red-500';
        toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300 min-w-[200px]`;
        toast.innerHTML = `
            <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'} text-[#9a3412]"></i>
            <span class="font-medium text-sm">${message}</span>
        `;

        container.appendChild(toast);

        // Animate In
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        });

        // Remove after 3s
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-5');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // 4. Counter Animation for Stats
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 1000; // ms
            const increment = target / (duration / 16); // 60fps
            
            let current = 0;
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });
    });
</script>