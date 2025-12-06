<?php
// admin/product_detail.php

// 1. KHỞI ĐỘNG SESSION & KẾT NỐI
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Điều chỉnh đường dẫn này nếu cần thiết
require_once __DIR__ . '/../public/connect.php';

// 2. CHECK QUYỀN ADMIN
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'QuanTriVien') {
    header('Location: /Shop_Ban_Quan_Ao_4P/login.php');
    exit;
}

// 3. LẤY ID SẢN PHẨM TỪ URL
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: san-pham.php'); 
    exit;
}

// 4. TRUY VẤN DỮ LIỆU (KẾT HỢP 2 BẢNG)
try {
    // A. Lấy thông tin SẢN PHẨM (Bảng Cha)
    $stmt = $conn->prepare("
        SELECT sp.*, dm.ten_danh_muc
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON dm.id = sp.danh_muc_id
        WHERE sp.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Sản phẩm không tồn tại hoặc đã bị xóa.");
    }

    // B. Lấy danh sách BIẾN THỂ (Bảng Con)
    // Đây là nơi chứa Giá và Tồn kho thực tế
    $stmt_variants = $conn->prepare("
        SELECT * FROM bien_the_san_pham 
        WHERE san_pham_id = :id 
        ORDER BY mau_sac, kich_co
    ");
    $stmt_variants->execute([':id' => $id]);
    $variants = $stmt_variants->fetchAll();

    // C. Xử lý dữ liệu tổng hợp
    
    // 1. Ảnh đại diện: Ưu tiên ảnh của biến thể đầu tiên nếu sản phẩm cha không có
    $mainImage = '/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg';
    
    // Kiểm tra ảnh cha trước
    if (!empty($product['hinh_anh_chinh'])) {
        $imgUrl = str_replace('../', '', $product['hinh_anh_chinh']);
        $mainImage = '/Shop_Ban_Quan_Ao_4P/' . $imgUrl;
    } 
    // Nếu không có, lấy ảnh của biến thể đầu tiên
    elseif (!empty($variants) && !empty($variants[0]['hinh_anh_dai_dien'])) {
        $imgUrl = str_replace('../', '', $variants[0]['hinh_anh_dai_dien']);
        $mainImage = '/Shop_Ban_Quan_Ao_4P/' . $imgUrl;
    }

    // 2. Tính Tổng Tồn Kho & Khoảng Giá (Min - Max)
    $totalStock = 0;
    $minPrice = 0;
    $maxPrice = 0;
    $prices = [];

    foreach ($variants as $v) {
        $totalStock += $v['so_luong_ton'];
        $prices[] = $v['gia_ban']; // Lấy giá bán thực tế
    }

    if (!empty($prices)) {
        $minPrice = min($prices);
        $maxPrice = max($prices);
    }

} catch (Exception $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

// Hàm định dạng tiền tệ
if (!function_exists('money_vn')) {
    function money_vn($n) { return number_format((float)$n, 0, ',', '.'); }
}

// Cấu hình Header
$page_title = 'Chi tiết: ' . $product['ten_san_pham'];
$active = 'products'; 
include __DIR__.'/partials/header.php'; 
?>

<style>
    .glass { background: rgba(255, 255, 255, 0.95); }
    .fade-in { animation: fade .5s ease both; }
    @keyframes fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
    
    /* Chấm màu sắc */
    .color-dot {
        display: inline-block; width: 12px; height: 12px; border-radius: 50%; 
        border: 1px solid #ddd; margin-right: 5px;
        vertical-align: middle;
    }
</style>

<main class="flex-1 overflow-y-auto relative z-10 bg-slate-50/50">
    
    <header class="sticky top-0 z-20 glass border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="san-pham.php" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition" title="Quay lại">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Chi tiết sản phẩm</h1>
                    <div class="text-sm text-gray-500">Quản lý thông tin & kho hàng</div>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="alert('Tính năng thêm biến thể sẽ được cập nhật sau')" class="px-4 py-2 text-sm font-medium text-white bg-[#c72002] hover:bg-[#9a1902] rounded-lg transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm biến thể
                </button>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 fade-in">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col items-center justify-center relative">
                    <div class="absolute top-4 right-4">
                        <?php if ($product['trang_thai'] == 'DangBan'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Đang bán</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Ngừng bán</span>
                        <?php endif; ?>
                    </div>

                    <img src="<?=htmlspecialchars($mainImage)?>" 
                         alt="<?=htmlspecialchars($product['ten_san_pham'])?>" 
                         class="w-full max-h-[400px] object-contain rounded-xl"
                         onerror="this.src='/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg'">
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6 fade-in" style="animation-delay: 0.1s">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl"></div>

                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                <i class="fa-solid fa-layer-group mr-1"></i>
                                <?=htmlspecialchars($product['ten_danh_muc'] ?? 'Chưa phân loại')?>
                            </span>
                            <span class="text-gray-400 text-sm font-mono">#ID: <?=$product['id']?></span>
                        </div>

                        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-2">
                            <?=htmlspecialchars($product['ten_san_pham'])?>
                        </h2>

                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fa-solid fa-globe"></i> Slug: 
                            <span class="font-mono bg-gray-100 px-2 rounded text-xs"><?=$product['slug']?></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Khoảng giá bán</p>
                            <?php if ($minPrice > 0): ?>
                                <p class="text-2xl font-black text-[#c72002]">
                                    <?=money_vn($minPrice)?> <?php if($minPrice != $maxPrice) echo ' - ' . money_vn($maxPrice); ?>đ
                                </p>
                            <?php else: ?>
                                <p class="text-lg font-bold text-gray-400">Chưa thiết lập giá</p>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                            <p class="text-sm text-orange-600 mb-1">Tổng tồn kho</p>
                            <p class="text-2xl font-black text-orange-700"><?=number_format($totalStock)?></p>
                        </div>
                    </div>

                    <div class="prose text-gray-600 text-sm leading-relaxed">
                        <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-[#c72002]"></i> Mô tả sản phẩm
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 whitespace-pre-line">
                            <?= !empty($product['mo_ta']) ? $product['mo_ta'] : 'Chưa có mô tả cho sản phẩm này.' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sitemap text-[#c72002]"></i>
                    Danh sách Biến thể (Màu / Size)
                </h3>
                <span class="text-sm font-medium bg-white px-4 py-1.5 rounded-full border shadow-sm text-gray-600">
                    Số lượng biến thể: <strong><?=count($variants)?></strong>
                </span>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                <?php if ($variants): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold w-20">Ảnh</th>
                                <th class="px-6 py-4 font-semibold">SKU</th>
                                <th class="px-6 py-4 font-semibold">Màu sắc</th>
                                <th class="px-6 py-4 font-semibold">Kích cỡ</th>
                                <th class="px-6 py-4 font-semibold text-right">Giá gốc</th>
                                <th class="px-6 py-4 font-semibold text-right">Giá bán</th>
                                <th class="px-6 py-4 font-semibold text-center">Tồn kho</th>
                                <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($variants as $v): 
                                $imgVar = str_replace('../', '', $v['hinh_anh_dai_dien'] ?? '');
                                $imgVarSrc = !empty($imgVar) ? '/Shop_Ban_Quan_Ao_4P/' . $imgVar : '/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg';
                                $isOutOfStock = $v['so_luong_ton'] <= 0;
                                
                                // Màu sắc chấm tròn minh họa
                                $colorHex = match($v['mau_sac']) { 
                                    'Đen'=>'#000', 'Trắng'=>'#fff', 'Xám'=>'#888', 'Xanh'=>'blue', 'Đỏ'=>'red', 'Vàng'=>'yellow', 
                                    default=>'#eee'
                                };
                            ?>
                            <tr class="hover:bg-orange-50/30 transition group">
                                <td class="px-6 py-3">
                                    <img src="<?=$imgVarSrc?>" class="w-10 h-12 object-cover rounded border border-gray-200" alt="Var">
                                </td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-500">
                                    <?=htmlspecialchars($v['sku'] ?? '---')?>
                                </td>
                                <td class="px-6 py-3 font-medium text-gray-800">
                                    <div class="flex items-center gap-2">
                                        <span class="color-dot" style="background-color: <?=$colorHex?>"></span>
                                        <?=htmlspecialchars($v['mau_sac'])?>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold text-gray-600 border border-gray-200">
                                        <?=htmlspecialchars($v['kich_co'])?>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right text-gray-400 line-through text-sm">
                                    <?=money_vn($v['gia_goc'])?>đ
                                </td>
                                <td class="px-6 py-3 text-right font-bold text-[#c72002]">
                                    <?=money_vn($v['gia_ban'])?>đ
                                </td>
                                <td class="px-6 py-3 text-center font-bold text-gray-700">
                                    <?=number_format($v['so_luong_ton'])?>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <?php if ($isOutOfStock): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            Hết hàng
                                        </span>
                                    <?php elseif ($v['so_luong_ton'] < 10): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            Sắp hết
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            Sẵn sàng
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="p-12 text-center text-gray-400 flex flex-col items-center">
                        <i class="fa-solid fa-box-open text-4xl mb-3 opacity-30"></i>
                        <p class="mb-2">Sản phẩm này chưa có biến thể (Màu/Size).</p>
                        <p class="text-sm text-gray-400">Vui lòng thêm biến thể để bắt đầu bán hàng.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

</div>
</body>
</html>